<?php $col = ($tTheme->get_page_navigation("extra") != "") ? "col-9" : ""; ?>
<div class="site_content-header <?=$col?>"><?php echo $tTheme->get_page_variable("header"); ?></div>
<div class="site_content-full <?=$col?>">
    <?php $tTheme->content(); ?>
</div>
<div class="clearfix"></div>