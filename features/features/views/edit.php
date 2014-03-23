<?php

$get            = filter_input_array(INPUT_GET); // Clean the request information
$features_table = $tDataClass->prefix."_features"; // Define the features database tabel
$error          = array(); // Error checking array

$id = ""; // Default ID for check later

// Feature ID
if (isset($get['id']) && $get['id'] != "") {
    $id = $get['id'];
} else {
    $error[] = "Unknown feature ID.";
}

// Check the database for this feature
$sql['find'] = "SELECT * FROM `$features_table` WHERE `id`='$id'";
$qry['find'] = $tData->query($sql['find']);
if ($qry['find'] && $qry['find']->num_rows == 0) {
    $error[] = "There was an error finding this feature in the database.";
} else {
    // Grab all feature information
    $feature = $qry['find']->fetch_assoc();

    // Define the enabled checkbox
    $enable_check = $feature['enabled'] == 1 ? "checked" : "";
}

?>
<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/features/img/edit-features.png" alt="" />
    </span>
    <div class="right" style="margin-top: -2px;">
        <input type="button" name="cancel" value="Go Back" />
    </div>
	<div class="admin_content-header-text">
        <?php if (!empty($error)): ?>
        Oh no!
        <?php else: ?>
        Edit Feature "<?=$feature['name']?>"
        <?php endif; ?>
    </div>
</div>

<div class="admin_page-content">
    <?php if (!empty($error)):
            notify("admin", "failure", $error[0]);
    else: ?>
    <div id="edit-result"></div>
    <form class="admin-form" id="edit-form">
        <div class="admin-formheader">Feature Update</div>
        <div class="admin-formrow">
            <div class="admin-formlabel">Feature Files</div>
            <div class="admin-forminput">
                <input type="file" name="file" />
            </div>
            <div class="admin-forminfo">
                Features should come in the form of zip archives.<br />
                Select the feature you want to install and everything will be handled
                automatically from there.
            </div>
        </div>
        <div class="admin-formheader">Feature Accessibility</div>
        <input type="hidden" name="id" value="<?=$feature['id']?>" />
        <div class="admin-formrow">
            <div class="admin-formlabel afl-float">Allowed Groups</div>
            <div class="admin-forminput">
                <input type="hidden" id="groups" value="<?=$feature['groups']?>" />
                <select name="groups" id="group-select" multiple="multiple" size="10"></select>
            </div>
        </div>

        <div class="admin-formrow">
            <div class="admin-formlabel afl-float">Enabled</div>
            <div class="admin-forminput">
                <div class="admin-cboxwrapper">
                    <input type="checkbox" class="admin-switchcbox" name="enabled"
                      id="enabled" <?=$enable_check?>>
                    <label class="admin-switchlabel yn" for="enabled">
                      <span class="admin-switchinner"></span>
                      <span class="admin-switchswitch"></span>
                    </label>
                </div>
            </div>
        </div>

        <hr />

        <div class="admin-formsubmitrow">
            <input type="submit" value="Save" class="admin-greenbtn" />
            <input type="button" value="Cancel" class="admin-redbtn" name="cancel" />
        </div>
    </form>
    <?php endif; ?>
</div>