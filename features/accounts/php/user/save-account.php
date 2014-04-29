<?php

$query_data = array("table_name" => $tData->prefix."_users", "data" => array(), "clause" => array());

// Get the user's information
$user = $tUser->user;

if ($user != false) {
    $remove_picture = false;
    if (count($_FILES) >= 1) {
        $file = $_FILES['picture'];

        // Get the filetype
        $filetype_array = explode(".", $file['name']);
        $filetype = strtolower($filetype_array[count($filetype_array) - 1]);

        // Check filetype
        $allowed_types = array("jpg", "png", "jpeg", "bmp", "tiff");
        if (!in_array($filetype, $allowed_types)) {
            $error[] = "This type of file isn't accepted here.";
        } else {
            $remove_picture = $user['picture'] != "" ? $user['picture'] : false;
            $filename = md5(time().$file['name']).".".$filetype;
        }
    } else {
        $file = false;
    }

    $password = false;
    if ($_POST['change_pass'] == "true") {
        if ($_POST['password'] != "") {
            $password = $_POST['password'];
            if ($_POST['repeat_password'] != "") {
                $repeat_pass = $_POST['repeat_password'];
                if ($password == $repeat_pass) {
                    if (strlen($password) >= 4 && strlen($password) <= 30) {
                        $salt = $tDataClass->get_config_salt("password");
                        $password = hash('SHA256', $password.$salt);
                    } else {
                        $error[] = "Your new password must be between 4 and 30 characters.";
                    }
                } else {
                    $error[] = "The passwords you've provided don't match.";
                }
            } else {
                $error[] = "Please fill out the repeat password field.";
            }
        } else {
            $error[] = "Please fill out the new password field.";
        }
    }

    if (!empty($error)) {
        alert_notify("danger", $error[0]);
    } else {
        // Define the media path for profile pictures
        $media_path = path(ROOT."/media/profiles/");

        // Remove the picture if necessary
        if ($remove_picture != false) {
            if (!@unlink($media_path.$remove_picture)) {
                alert_notify("danger", "There was an error removing your old picture. "
                        . "This may be because of file permissions.");
                die();
            }
        }

        // Upload the file and the database
        if ($file != false) {
            if (@move_uploaded_file($file['tmp_name'], $media_path.$filename)) {
                $query_data['data'][] = array("picture" => $filename);
                $query_data['clause'][] = array("operator" => "", "conditions" => array("id" => $user['id']));

                // Run JS to update the pictures on the page
                run_after_ajax("update_pics", '{"pic":"'.$filename.'"}');
            } else {
                alert_notify("danger", "There was an error uploading your picture. "
                        . "This may be because of file permissions.");
            }
        }

        // Update the password
        if ($password != false) {
            $query_data['data'][] = array("password" => $password);
            $query_data['clause'][] = array("operator" => "", "conditions" => array("id" => $user['id']));
        }

        if (!empty($query_data['data'])) {
            if ($tData->update_table_row($query_data['table_name'], $query_data['data'], $query_data['clause'])) {
                alert_notify("success", "You account information has been saved.");
            } else {
                alert_notify("danger", "There was an error saving the account information.");
            }
        } else {
            alert_notify("success", "Your account information has been saved.");
        }
    }
}