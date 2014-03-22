<?php

try {
    $info = $Features->prelim_install();
} catch (Exception $ex) {
    $Features->clean_temp_folder();
    die(notify("admin", "failure", $ex->getMessage()));
}

?>

<input type="hidden" name="filename" value="<?=$info['filename']?>" />

<div class="admin-formrow">
    <div class="admin-formlabel">Feature Name</div>
    <div class="admin-formtext"><?=$info['name']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Feature Folder</div>
    <div class="admin-formtext"><?=$info['alias']?></div>
</div>

<hr />

<div class="admin-formrow">
    <div class="admin-formlabel">Total Feature Files</div>
    <div class="admin-formtext"><?=$info['files']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">Feature File Size</div>
    <div class="admin-formtext"><?=$info['filesize']?></div>
</div>

<hr />

<div class="admin-formrow">
    <div class="admin-formlabel">Database Changes</div>
    <div class="admin-formtext"><?=$info['db_changes']?></div>
</div>

<?php
if ($info['version'] != "" || $info['notes'] != "") echo "<hr />";

if ($info['version'] != ""):
?>
<div class="admin-formrow">
    <div class="admin-formlabel">Feature Version</div>
    <div class="admin-formtext"><?=$info['version']?></div>
</div>
<?php 
endif;
if ($info['notes'] != ""):
?>
<div class="admin-formrow">
    <div class="admin-formlabel afl-float">Feature Notes</div>
    <div class="admin-formtext">
        <ul style="margin: 0; padding: 0 20px;">
        <?php foreach ($info['notes'] as $note): ?>
            <li><?=$note?></li>
        <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php
endif;

if ($info['author'] != "" || $info['alias'] != "" || $info['email'] != "" || $info['company'] != "") echo "<hr />";

if ($info['author'] != ""):
?>
<div class="admin-formrow">
    <div class="admin-formlabel">Author Name</div>
    <div class="admin-formtext"><?=$info['author']?></div>
</div>
<?php
endif;
if ($info['alias'] != ""):
?>
<div class="admin-formrow">
    <div class="admin-formlabel">Author Alias</div>
    <div class="admin-formtext"><?=$info['alias']?></div>
</div>
<?php
endif;
if ($info['email'] != ""):
?>
<div class="admin-formrow">
    <div class="admin-formlabel">Author Email</div>
    <div class="admin-formtext"><?=$info['email']?></div>
</div>
<?php
endif;
if ($info['company'] != ""):
?>
<div class="admin-formrow">
    <div class="admin-formlabel">Author Company</div>
    <div class="admin-formtext"><?=$info['company']?></div>
</div>
<?php
endif;