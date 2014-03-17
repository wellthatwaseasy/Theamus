<?php

try {
    $Settings->prelim_update();
} catch (Exception $ex) {
    $Settings->abort_update($ex);
}

?>

<input type="hidden" name="filename" value="<?=$Settings->update_information['filename']?>" />

<div class="admin-formrow">
    <div class="admin-formlabel">Database Changes</div>
    <div class="admin-formtext"><?=$Settings->update_information['database_changes']?></div>
</div>
<div class="admin-formrow">
    <div class="admin-formlabel">File Changes</div>
    <div class="admin-formtext"><?=$Settings->update_information['file_changes']?></div>
</div>

<hr />

<div class="admin-formrow">
    <div class="admin-formlabel afl-float">Bug Fixes</div>
    <div class="admin-formtext">
        <ul style="margin: 0; padding: 0 20px;">
            <?php foreach ($Settings->update_information['bugs'] as $bug): ?>
            <li><?=$bug?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>