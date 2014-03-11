<?php

// Define a default for the javascript and css files
$feature['js']['file'] = array();
$feature['css']['file'] = array();

// Files to deny basic users
$admin_files = array(
    // Script files
    "create.php",
    "remove.php",
    "save.php",
    "users-list.php",

    // View files
    "add.php",
    "edit.php",
    "form.php",
    "index.php"
    );
// Deny bad users
$tUser->deny_non_admins($file, $admin_files);

// Customize files
switch ($file) {
    case "index.php":
        $feature['css']['file'][] = "main.css";
        $feature['js']['file'][] = "accounts-index.js";
        break;

    case "add.php":
        $tUser->check_permissions("add_users");
        $feature['js']['file'][] = "edit_user.js";
        break;

    case "edit.php":
        $tUser->check_permissions("edit_users");
        $feature['js']['file'][] = "edit_user.js";
        break;

    case "login.php" :
        if ($tUser->user) back_up();
        $feature['title'] = "Login";
        $feature['header'] = "Log In";
        $feature['js']['file'][] = "sessions.js";
        $feature['theme'] = "login";
        break;

    case "register.php":
        if ($tUser->user) {
            back_up();
        }
        $feature['title'] = "Register";
        $feature['header'] = "Register";
        $feature['js']['file'][] = "sessions.js";
        break;

    case "user/index.php":
        back_up();
        break;

    case "remove.php":
        $tUser->check_permissions("remove_users");
        break;

    case "activate.php":
        $feature['title'] = "Activate Your Account";
        $feature['header'] = "Activate Your Account";
        break;

    case "user/edit-account.php":
    case "user/edit-personal.php":
    case "user/edit-contact.php":
    case "user/other-information.php":
        if (!$tUser->user) { back_up(); }
        $feature['title'] = $feature['header'] = "Edit Your Account";
        switch ($file) {
            case "user/edit-account.php":
                $feature['title'] .= " - Account Information";
                $feature['header'] .= " - Account Information";
                break;
            case "user/edit-personal.php":
                $feature['title'] .= " - Personal Information";
                $feature['header'] .= " - Personal Information";
                break;
            case "user/edit-contact.php":
                $feature['title'] .= " - Contact Information";
                $feature['header'] .= " - Contact Information";
                break;
            case "user/other-information.php":
                $feature['title'] = $feature['header'] = "Other Account Information";
                break;
            default:
                $feature['title'] = "Edit Your Account";
                $feature['header'] = "Edit Your Account";
                break;
        }
        $feature['js']['file'][] = "edit_user.js";
        $feature['nav'] = array(
            "Account Information" => "accounts/user/edit-account",
            "Personal Information" => "accounts/user/edit-personal",
            "Contact Information" => "accounts/user/edit-contact",
            "Other Information" => "accounts/user/other-information"
        );

    default :
        break;
}