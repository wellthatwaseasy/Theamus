<?php

$post = filter_input_array(INPUT_POST); // Filter in the user's input
$error = array(); // Error checking array

$version = "0.8"; // Default version

$reset = isset($post['reset']) ? $post['reset'] : "false";
if ($reset != "false") $Installer->reset_database();

// Table Prefix
if ($post['prefix'] != "") {
    $prefix = trim(strtolower($post['prefix']), "_");
    $prefix = $tData->real_escape_string($prefix);
} else {
    $error[] = "Please fill out the table prefix.";
}

/*****************************************************************************
 * DATABASE STRUCTURE QUERIES
 *****************************************************************************/

// Settings
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_settings` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`prefix` VARCHAR(10) NOT NULL,
`name` VARCHAR(100) NOT NULL,
`display_errors` INT NOT NULL,
`developer_mode` INT NOT NULL,
`email_host` VARCHAR(100) NOT NULL,
`email_protocol` VARCHAR(5) NOT NULL,
`email_port` VARCHAR(10) NOT NULL,
`email_user` VARCHAR(150) NOT NULL,
`email_password` VARCHAR(50) NOT NULL,
`installed` INT NOT NULL,
`home` VARCHAR(100) NOT NULL,
`version` VARCHAR(50) NOT NULL);";


// Features
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_features` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`id`),
`alias` VARCHAR(125) NOT NULL,
`name` VARCHAR(100) NOT NULL,
`groups` TEXT NOT NULL,
`permanent` INT NOT NULL,
`enabled` INT NOT NULL,
`db_prefix` VARCHAR(20) NOT NULL);";


// Groups
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_groups` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`id`),
`alias` VARCHAR(100) NOT NULL,
`name` VARCHAR(75) NOT NULL,
`permissions` TEXT NOT NULL,
`permanent` INT NOT NULL,
`home_override` VARCHAR(100) NOT NULL);";


// Links
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_links` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`id`),
`alias` VARCHAR(100) NOT NULL,
`text` VARCHAR(75) NOT NULL,
`path` TEXT NOT NULL,
`weight` INT NOT NULL,
`groups` TEXT NOT NULL,
`type` VARCHAR(50) NOT NULL,
`location` TEXT NOT NULL,
`child_of` INT NOT NULL);";


// Pages
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_pages` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`id`),
`alias` VARCHAR(250) NOT NULL,
`title` VARCHAR(200) NOT NULL,
`content` TEXT NOT NULL,
`views` INT NOT NULL,
`permanent` INT NOT NULL,
`groups` TEXT NOT NULL,
`theme` VARCHAR(50) NOT NULL,
`navigation` TEXT NOT NULL);";


// Permissions
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_permissions` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`id`),
`feature` VARCHAR(100) NOT NULL,
`permission` VARCHAR(100) NOT NULL);";


// Users
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_users` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`username` VARCHAR(25) NOT NULL,
`password` VARCHAR(100) NOT NULL,
`session` VARCHAR(64) NOT NULL,
`email` VARCHAR(150) NOT NULL,
`firstname` VARCHAR(30) NOT NULL,
`lastname` VARCHAR(30) NOT NULL,
`birthday` DATE NOT NULL,
`gender` VARCHAR(10) NOT NULL,
`admin` INT NOT NULL,
`groups` TEXT NOT NULL,
`permanent` INT NOT NULL,
`phone` VARCHAR(15) NOT NULL,
`picture` VARCHAR(64) NOT NULL,
`created` DATETIME NOT NULL,
`active` INT(11) NOT NULL,
`activation_code` VARCHAR(128) NOT NULL);";


// Themes
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_themes` (
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`alias` VARCHAR(125) NOT NULL,
`name` VARCHAR(100) NOT NULL,
`active` INT NOT NULL,
`permanent` INT NOT NULL);";

// Theme data
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_themes-data` (".
        "`id` INT NOT NULL AUTO_INCREMENT, ".
        "PRIMARY KEY(`id`), ".
        "`key` TEXT NOT NULL, ".
        "`value` TEXT NOT NULL, ".
        "`selector` TEXT NOT NULL, ".
        "`theme` VARCHAR(50) NOT NULL);";


// Default home apps/widgets
$structure[] = "CREATE TABLE IF NOT EXISTS `dflt_home-apps` (".
    "`id` INT(11) NOT NULL AUTO_INCREMENT, ".
    "PRIMARY KEY(`id`), ".
    "`name` VARCHAR(100) NOT NULL, ".
    "`path` VARCHAR(100) NOT NULL, ".
    "`active` INT(11) NOT NULL, ".
    "`position` INT(11) NOT NULL, ".
    "`column` INT(11) NOT NULL);";


// Images
$structure[] = "CREATE TABLE IF NOT EXISTS `".$prefix."_images` (".
    "`id` INT(11) NOT NULL AUTO_INCREMENT, ".
    "PRIMARY KEY(`id`), ".
    "`path` VARCHAR(150) NOT NULL, ".
    "`file_name` VARCHAR(100) NOT NULL, ".
    "`file_size` INT(11) NOT NULL);";

/*****************************************************************************
 * DATA QUERIES
 *****************************************************************************/

// Settings
$data[] = "INSERT IGNORE INTO `".$prefix."_settings` ".
    "(`prefix`, `name`, `display_errors`, `developer_mode`, `email_host`, `email_protocol`, `email_port`, ".
    "`email_user`, `email_password`, `home`, `version`) VALUES ".
    "('$prefix', '', 0, 1, '', '', '', '', '', '{t:homepage;type=\"page\";id=\"1\":}', '$version');";


// Permissions
$data[] = "INSERT IGNORE INTO `".$prefix."_permissions` ".
    "(`feature`, `permission`) VALUES ".
    "('groups', '   create_groups'), ".
    "('groups',     'edit_groups'), ".
	"('groups',     'remove_groups'), ".
    "('accounts',   'add_users'), ".
	"('accounts',   'edit_users'), ".
    "('accounts',   'remove_users'), ".
	"('navigation', 'create_links'), ".
    "('navigation', 'edit_links'), ".
	"('navigation', 'remove_links'), ".
    "('features',   'install_features'), ".
	"('features',   'edit_features'), ".
    "('features',   'remove_features'), ".
	"('pages',      'create_pages'), ".
    "('pages',      'edit_pages'), ".
	"('pages',      'remove_pages'), ".
    "('appearance', 'install_themes'), ".
    "('appearance', 'edit_themes'), ".
    "('appearance', 'remove_themes'), ".
    "('media',      'add_media'), ".
    "('media',      'remove_media');";


// Groups
$data[] = "INSERT IGNORE INTO `".$prefix."_groups` ".
    "(`alias`, `name`, `permissions`, `permanent`, `home_override`) VALUES ".
    "('everyone',       'Everyone', '', 1, 'false'), ".
    "('administrators', 'Administrators', 'create_groups,edit_groups,remove_groups,".
        "add_users,edit_users,remove_users,create_links,edit_links,remove_links,".
        "install_features,edit_features,remove_features,create_pages,edit_pages,".
        "remove_pages,install_themes,edit_themes,remove_themes,add_media,remove_media', 1, 'false'), ".
    "('basic_users',    'Basic Users', '', 1, 'false');";


// Pages
$homepage_content = $this->tData->real_escape_string("Welcome to your new website.<br><br>Getting started is pretty easy if you ask me.  All you have to do is <a href='accounts/login/'>log in</a> then go to the administration panel.  That's where you can do the fun things like:<ul>  <li>Add Themes to change the appearance of your website</li>  <li>Add Features to change the functionality of your website</li>  <li>Manage users and groups</li>  <li>Create Pages for the visitors of your website</li></ul>Using the Theamus platform as your content management system will make your life easier and more customizable than ever before.  The modularity of the system and the seamless integration of stand-alone applications will give you the freedom you're looking for.  Freedom, that doesn't look like it was whipped together at the whim of someone looking to make a quick buck.<br><br>Hold on tight because documentation is coming soon.");
$data[] = "INSERT IGNORE INTO `".$prefix."_pages` ".
    "(`alias`, `title`, `content`, `views`, `permanent`, `groups`, `theme`, `navigation`) VALUES ".
    "('home_page', 'Hello, World!', '$homepage_content', 0, 1, 'everyone', 'homepage', '');";


// Features
$data[] = "INSERT IGNORE INTO `".$prefix."_features` ".
    "(`alias`,      `name`, `groups`, `permanent`, `enabled`, `db_prefix`) VALUES ".
    "('groups',     'Groups',       'administrators',   1, 1, '".$prefix."_'), ".
	"('navigation', 'Navigation',   'administrators',   1, 1, '".$prefix."_'), ".
	"('accounts',   'Accounts',     'everyone',         1, 1, '".$prefix."_'), ".
	"('default',    'Default',      'everyone',         1, 1, '".$prefix."_'), ".
	"('features',   'Features',     'administrators',   1, 1, '".$prefix."_'), ".
	"('pages',      'Pages',        'everyone',         1, 1, '".$prefix."_'), ".
	"('settings',   'Settings',     'administrators',   1, 1, '".$prefix."_'), ".
    "('media',      'Media',        'administrators',   1, 1, '".$prefix."_');";


// Themes
$data[] = "INSERT IGNORE INTO `".$prefix."_themes` ".
    "(`alias`, `name`, `active`, `permanent`) VALUES ".
    "('default',    'Default',      1, 1);";

$data[] = "INSERT IGNORE INTO `dflt_home-apps`".
    "(`name`, `path`, `active`, `position`, `column`) VALUES ".
    "('Page Counter',           'pagecounter',  1, 1, 1), ".
    "('Theamus Update Checker', 'updatecheck',  1, 1, 2), ".
    "('Site Summary',           'summary',      1, 2, 2);";

// Show errors
if (!empty($error)) {
    notify("install", "failure", $error[0]);
    run_after_ajax("undisable_form");
} else {
    if (!$Installer->check_database()) {
        // Run all of the structure queries
        foreach ($structure as $s_qry) {
            $queries[] = $tData->query($s_qry) ? "true" : "false";
        }

        // Run all of the data queries
        foreach ($data as $d_qry) {
            $queries[] = $tData->query($d_qry) ? "true" : "false";
        }

        // Check for all successfull queries and respond accordingly
        if (!in_array("false", $queries)) {
            notify("install", "success", "The Theamus database has been installed.<br />".js_countdown());
            run_after_ajax("go_step", '{"step":"user"}');
        } else {
            notify("install", "failure", "There was an error when creating the database structure and data.");
            run_after_ajax("undisable_form");

            // Delete anything that may have been created, so we can have a fresh start
            if ($tables = $tData->query("SHOW TABLES")) {
                while ($table = $tables->fetch_array(MYSQLI_NUM)) {
                    $tData->query("DROP TABLE IF EXISTS `".$table[0]."`");
                }
            }
        }
    } else {
        notify("install", "success", "Skipping to the next step.<br/>".js_countdown());
        run_after_ajax("go_step", '{"step":"user"}');
    }
}