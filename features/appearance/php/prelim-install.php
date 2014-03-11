<?php

try {
    $config = $Appearance->prelim_install();
} catch (Exception $ex) {
    $Appearance->print_exception($ex);
}

?>
<input type="hidden" name="filename" value="<?=$config['upload']?>" />

<div class="admin-formrow">
    <div class="admin-formlabel">Total Theme Layouts</div>
    <div class="admin-formtext"><?=count($config['layouts'])?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Total Areas</div>
    <div class="admin-formtext"><?=count($config['areas'])?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Theme Settings</div>
    <div class="admin-formtext"><?php echo $config['settings'] == "true" ? "Yes" : "No"; ?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Navigation Areas</div>
    <div class="admin-formtext"><?=count($config['navigation'])?></div>
</div>

<hr />

<div class="admin-formrow">
    <div class="admin-formlabel">Theme Folder</div>
    <div class="admin-formtext"><?=$config['theme']['folder']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Theme Name</div>
    <div class="admin-formtext"><?=$config['theme']['name']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Theme Version</div>
    <div class="admin-formtext"><?=$config['theme']['version']?></div>
</div>

<hr />

<div class="admin-formrow">
    <div class="admin-formlabel">Author Name</div>
    <div class="admin-formtext"><?=$config['author']['name']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Author Alias</div>
    <div class="admin-formtext"><?=$config['author']['alias']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Author Company</div>
    <div class="admin-formtext"><?=$config['author']['company']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Author Email</div>
    <div class="admin-formtext"><?=$config['author']['email']?></div>
</div>