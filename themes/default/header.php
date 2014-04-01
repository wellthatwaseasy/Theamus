<div class="black_bar">
    <div id="result" style="visibility: hidden;"></div>
    <header class="site_header">
        <div class="site_header-company left">
            <a href="#">
                <span class="site_header-company-logo">
                    <!-- Company logo -->
                </span>
                <span class="site_header-company-text">
                    <?php echo $tTheme->get_system_variable("name"); ?>
                </span>
            </a>
        </div>

        <?php if (!$tUser->is_admin()): ?>
        <div class="site_header-user right">
            <?php if ($tUser->user != false): ?>
            <ul>
                <li>
                    <div class="site_header-user-pic">
                        <img src="media/profiles/{t:user_var='picture':}" alt="" />
                    </div>
                    <div class="site_header-user-name">
                        <?php echo $tUser->user['firstname']." ".$tUser->user['lastname']; ?>
                    </div>
                    <div class="site_header-user-arrow"></div>
                    <div class="clearfix"></div>

                    <ul>
                        <li><a href="accounts/user/edit-account/">Edit Profile</a></li>
                        <li class="site_header-hr"></li>
                        <li><a href="#" onclick="return user_logout();">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <?php else: ?>
            <span class="site_login-reg">
                <a href="accounts/login/">Login</a>
                <a href="accounts/register/">Register</a>
            </span>
            <?php endif; // End user not logged in ?>
        </div>
        <?php endif; // End user !admin ?>
        <div class="clearfix"></div>
    </header>

    <nav class="site_nav">
        <ul>
            <li class="home"><a href="#">Home</a></li>
            <?php echo $tTheme->get_page_navigation("main"); ?>
        </ul>
    </nav>
</div>