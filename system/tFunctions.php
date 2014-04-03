<?php

/**
 * Prints out a notification on the website
 *
 * @param string $for
 * @param string $type
 * @param string $message
 * @param string $extras
 * @return boolean
 */
function notify($for, $type, $message, $extras = "", $return = false) {
    $ret = "<div class='" . $for . "-notify" . $type . "' id='notify' " . $extras . ">";
    $ret .= $message;
    $ret .= "</div>";
    if ($return == false) {
        echo $ret;
    } else {
        return $ret;
    }
}

/**
 * Prints out an alert on the website
 *
 * @param string $for
 * @param string $type
 * @param string $message
 * @param string $extras
 * @return boolean
 */
function alert_notify($type = "success", $message = "", $extras = "", $return = false) {
    $glyph = array(
        "success" => "ion-checkmark-round",
        "danger" => "ion-close",
        "warning" => "ion-alert",
        "info" => "ion-information"
    );
    $ret = "<div class='alert alert-$type' id='notify' $extras>";
    $ret .= "<span class='glyphicon ".$glyph[$type]."'></span>$message";
    $ret .= "</div>";

    if ($return == false) {
        echo $ret;
    } else {
        return $ret;
    }
}


/**
 * Prints out an input that requests the site to include an extra javascript file
 *
 * @param string $path
 * @return boolean
 */
function add_js($path) {
    echo "<input type='hidden' name='addscript' value='".$path."?x=".time()."' />";
    return true;
}


/**
 * Runs a javascript function after an ajax call
 *
 * @param string $function
 * @param string $arguments
 * @return boolean
 */
function run_after_ajax($function, $arguments="") {
    echo "<input type='hidden' name='run_after' function='" . $function . "' arguments='" . $arguments . "' />";
    return true;
}


/**
 * Shows the holder for a countdown timer
 *
 * @return string
 */
function js_countdown() {
    return "<span id='countdown'></span><span id='elipses'></span>";
}


/**
 * This function will configure paths to be acceptable on both
 *  Windows and *nix based machines.
 *
 * @param string $path
 * @return string
 */
function path($path) {
    if (strpos($path, ":\\") !== false) $path = str_replace("/", "\\", $path);
    return $path;
}


/**
 * This function will configure paths to be readable to web browsers
 *
 * @param string $path
 * @return string
 */
function web_path($path) {
    if (strpos($path, "\\") !== false) {
        $path = str_replace("\\", "/", $path);
    }
    return $path;
}


/**
 * Takes the user back a page
 *
 * @return header
 */
function back_up() {
    header("Location: ../");
}


/**
 * Shortcut to email people through the provided database information (and SMTP)
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return boolean
 */
function tMail($to, $subject, $message) {
    $tDataClass = new tData();
    $tData = $tDataClass->connect();

    $q = $tData->query("SELECT * FROM `".$tDataClass->get_system_prefix()."_settings`");
    $settings = $q->fetch_assoc();

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = $settings['email_protocol'];
    $mail->Host       = $settings['email_host'];
    $mail->Port       = $settings['email_port'];
    $mail->Username   = $settings['email_user'];
    $mail->Password   = $settings['email_password'];
    $mail->From       = $settings['email_user'];
    $mail->FromName   = $settings['name'];

    $mail->IsHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->AddAddress($to);

    return $mail->Send();
}


/**
 * Shows a holder for an upload progress bar and percentage holder
 *
 * @param string $pro
 * @param string $per
 * @return string
 */
function show_upload_progress($pro = "upload-progress", $per = "upload-percentage") {
    $prog = "<div id='$pro'>";
    $prog .= $per ? "<span id='$per'></span>" : "";
    $prog .= "</div>";

    return $prog;
}


/**
 * Shows all of the relevant page navigation defined by the links in the database
 *
 * @return boolean
 */
function show_page_navigation($loc = "main", $child_of = 0) {
    $ret = array();
    $tDataClass = new tData();
    $tData      = $tDataClass->connect();

    $tUser = new tUser();

    $q = $tData->query("SELECT * FROM `".$tDataClass->get_system_prefix()."_links` WHERE `location`='$loc' AND `child_of`='$child_of'");
    while ($link = $q->fetch_assoc()) {
        $in = array();
        foreach (explode(",", $link['groups']) as $group) $in[] = $tUser->in_group($group) ? "true" : "false";

        if (in_array("true", $in)) {
            $c = $tData->query("SELECT * FROM `".$tDataClass->get_system_prefix()."_links` WHERE `child_of`='".$link['id']."'");
            $ret[] = "<li>";
            $ret[] = "<a href='".$link['path']."'>".$link['text']."</a>";
            if ($c->num_rows > 0) $ret[] = "<ul>";
            $ret[] = show_page_navigation($loc, $link['id']);
            if ($c->num_rows > 0) $ret[] = "</ul>";
            $ret[] = "</li>";
        }
    }
    return implode($ret);
}


/**
 * Shows navigation that is made for the html-nav layout.  As defined by
 *  static pages or features
 *
 * @param string $navigation
 * @return string $nav|boolean
 */
function extra_page_navigation($navigation, $classes = "") {
    if (!empty($navigation)) {
        $class = ($classes != "") ? "class='$classes'" : "";
        $nav = "<ul $class>";
        foreach ($navigation as $text => $path) {
            if ($text != "path") {
                if ($text == "hr") $nav .= "<li><hr /></li>";
                elseif (is_array($navigation[$text])) {
                    $nav .= "<li><a href='".$navigation[$text]['path']."'>".$text."</a>";
                    $nav .= extra_page_navigation($navigation[$text]);
                    $nav .= "</li>";
                } else $nav .= "<li><a href='".$path."'>".$text."</a></li>";
            }
        }
        $nav .= "</ul>";

        return $nav;
    }
    return false;
}

/**
 * Prints out an array wrapped in <pre> tags.  Super helpful
 *
 * @param array $array
 */
function Pre($array, $return = false) {
    $ret[] = "<pre>";
    $ret[] = print_r($array, true);
    $ret[] = "</pre>";

    if ($return == true) return implode("", $ret);
    else echo implode("", $ret);
}
