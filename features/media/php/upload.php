<?php

$error = array();
$media_table = $tDataClass->prefix."_media";
$file = $_FILES['file'];
$allowed_media = array("jpg", "jpeg", "png", "gif", "webp", "pdf");
$upload_path = ROOT."/media/images/";

$name_array = explode(".", strtolower($file['name']));
$extension = $name_array[count($name_array) - 1];

if (in_array($extension, $allowed_media)) {
    $alias = md5(time()).".".$extension;
    $alias = $tData->real_escape_string($alias);

    $name = $tData->real_escape_string($file['name']);
    $size = $tData->real_escape_string($file['size']);
} else {
    $error[] = "This type of file is not allowed here. ($extension)";
}

// Define the media type
$images = array("jpg", "jpeg", "png", "gif", "webp");
if (in_array($extension, $images)) {
    $type = "image";
} elseif ($extension == "pdf") {
    $type = "object";
} else {
    $type = "image";
}

if (!empty($error)) {
    echo "Failed. <span class='media_error-title' title='".$error[0]."'>?</span>";
} else {
    if (move_uploaded_file($file['tmp_name'], $upload_path.$alias)) {
        $sql['add'] = "INSERT INTO `$media_table` ".
            "(`path`, `file_name`, `file_size`, `type`) VALUES ".
            "('$alias', '$name', '$size', '$type')";
        $qry['add'] = $tData->query($sql['add']);

        echo "Completed.";
    } else {
        echo "Failed. <span class='media_error-title' "
        . "title='There was an error moving the uploaded file.'>?</span>";
    }
}