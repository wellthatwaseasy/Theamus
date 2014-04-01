<!DOCTYPE html>
<html>
    <head>
        <?php echo $tTheme->get_page_variable("base"); ?>
		<title><?php echo $tTheme->get_page_variable("title"); ?></title>
        <?php echo $tTheme->get_page_variable("css"); ?>
		<link rel="stylesheet" href="<?php echo $tTheme->get_page_variable("theme_path"); ?>/css/main.css" />
        <?php echo $tTheme->get_page_variable("js"); ?>
    </head>
    <body>
        <?php echo $tTheme->content(); ?>
    </body>
</html>