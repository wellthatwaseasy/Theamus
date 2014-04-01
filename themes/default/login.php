<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?php echo $tTheme->get_page_variable("base"); ?>
        <title><?php echo $tTheme->get_page_variable("title"); ?></title>
        <?php echo $tTheme->get_page_variable("css"); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $tTheme->get_page_variable("theme_path"); ?>/css/login.css" />
        <?php echo $tTheme->get_page_variable("js"); ?>
    </head>
    <body>
        <header class="login_header">
            <span class="login_header-company"><?php echo $tTheme->get_system_variable("name"); ?></span>
        </header>
        <div class="login_area-wrapper">
            <div class="login_area"><?php $tTheme->content(); ?></div>
            <div class="login_site-link">
                <a href="./">< Back to <?php $tTheme->get_system_variable("name"); ?></a>
            </div>
        </div>
    </body>
</html>