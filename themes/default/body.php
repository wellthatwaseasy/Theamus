<div class="site_content-header"><?php echo $tTheme->get_page_variable("header"); ?></div>
<div class="site_content-full <?php if ($tTheme->get_page_navigation("extra") != "") echo 'site_with-nav'; ?>">
    <?php $tTheme->content(); ?>
</div>
<div class="clearfix"></div>