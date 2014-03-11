<?php

function open_page($header) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Install Theamus</title>
            <base href="<?= $header['base'] ?>" />
            <link rel="stylesheet" href="themes/installer/css/main.css" />
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
            <?=$header['js']?>
            <script src="themes/installer/js/slider.js"></script>
            <script src="themes/installer/js/install.js"></script>
        </head>
        <body>
            <div class="site_wrapper">
                <div class="black_bar">
                    <div id="default-result" style="visibility: hidden;"></div>
                    <header class="site_header">
                        <div class="site_header-company left">
                            <a href="#">
                                <span class="site_header-company-logo">
                                    <!-- Company logo -->
                                </span>
                                <span class="site_header-company-text">
                                    Theamus
                                    <span class="site_header-company-tag">
                                        Install
                                    </span>
                                </span>
                            </a>
                        </div>
                    </header>
                </div>
                <div class="site_content">
                    <div class="site_content-nav">
                        <ul class="nav" id="nav">
                            <li parent="home" class="current"><a href="javascript:void(0);">Welcome</a></li>
                            <li parent="config"><a href="javascript:void(0);">Configuration File</a></li>
                            <li parent="db"><a href="javascript:void(0);">Create Database</a></li>
                            <li><hr /></li>
                            <li parent="user"><a href="javascript:void(0);">Create First User</a></li>
                            <li parent="custom"><a href="javascript:void(0);">Customization</a></li>
                            <li parent="done"><a href="javascript:void(0);">Done!</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="site_content-main-header" id="content-header"></div>
                    <div class="site_content-main" id="content">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php
}

function close_page() {
                ?>
            </div>
        </body>
    </html>
<?php
}