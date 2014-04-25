<?php  try { $tabs = $Appearance->get_tabs(); ?>
<div class="theme_tabs-wrapper">
    <ul>
    <?php foreach ($tabs as $key => $val): ?>
        <li><a href="#" name="theme_settings-tab" data-path="<?php echo trim($val, ".php"); ?>"><?=$key?></a></li>
    <?php endforeach; ?>
    </ul>
</div>
<div id="theme_settings-contents"></div>

<?php } catch (Exception $ex) { $Appearance->print_exception($ex); }