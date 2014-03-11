<?php

function home_type($array) {
    // Define the return array
    $return = array();

    // Explode the values
    if ($array == 'false') {
        $return['type'] = 'nooverride';
    } else {
        $return['type'] = $array['type'];
    }

    // Return the new array
    return $return;
}

$error = array();   // Define an empty error array

$get = filter_input_array(INPUT_GET);   // Define a filtered 'get'

$id = isset($get['id']) ? $get['id'] : "";  // Define the id or it's default
$id = $tData->real_escape_string($id);      // Sanitize for the DB

$table = $tDataClass->prefix."_groups";  // Define the group's table

// Query the database for the group
$sql['group'] = "SELECT * FROM `".$table."` WHERE `id`='".$id."'";
$qry['group'] = $tData->query($sql['group']);

// Get the group's information
if ($qry['group']) {                             // Check for a successful query
    if ($qry['group']->num_rows > 0) {           // Check for results
        $group = $qry['group']->fetch_assoc();   // Define the group's information

        $permanent = $group['permanent'] == "1" ? true : false;
        $permissions = explode(",", $group['permissions']);

        if ($group['home_override'] != "false") {
            $homeType = $tDataClass->t_decode($group['home_override']);
        } else {
            $homeType['type'] = 'nooverride';
        }
    } else {
        $error[] = "This group was not found.";
    }
} else {
    $error[] = "There was an error querying the database.";
}

?>

<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/groups/img/edit-groups.png" alt="" />
    </span>
    <div class="right" style="margin-top: -2px;">
        <input type="button" onclick="admin_go('accounts', 'groups/');" value="Go Back" />
    </div>
	<div class="admin_content-header-text">
        <?php if (empty($error)): ?>
            Edit Group "<?=$group['name']?>"
        <?php else: ?>
            Something's wrong here...
        <?php endif; ?>
    </div>
</div>

<div class="admin_page-content">
    <div id="group-result"></div>
	<?php
	if (!empty($error)):
		 notify("admin", "failure", $error[0]);
	else:
    ?>
    <form class="admin-form" id="group-form" onsubmit="return save_group();">
        <input type="hidden" name="group_id" value="<?=$group['id']?>" />
        <div class="admin-formrow">
            <div class="admin-formlabel">Group Name</div>
            <div class="admin-forminput">
                <input type="text" class="longtext" name="name" id="name" maxlength="100"
                       autocomplete="off"  value="<?=stripslashes($group['name'])?>"
                       <?php if($permanent) { echo "disabled"; } ?> />
            </div>
            <div class="admin-forminfo">
                <span>This is the name that will appear to users, a general name.</span>
            </div>
        </div>

        <hr />

        <div class="admin-formrow">
            <div class="admin-formlabel afl-float">Permissions</div>
            <div class="admin-forminput">
                <select name="permissions" id="permissions" size="20" multiple="multiple">
                    <?php
                    // Query the database for permissions
                    $ptable = $tDataClass->prefix . "_permissions";
                    $sql['perm'] = "SELECT * FROM `" . $ptable . "`";
                    $query['perm'] = $tData->query($sql['perm']);

                    // Loop through results
                    while ($results = $query['perm']->fetch_assoc()) {
                        // Clean up the text
                        $permission = str_replace("_", " ", $results['permission']);
                        $permission = ucwords($permission);
                        $feature = str_replace("_", " ", $results['feature']);
                        $feature = ucwords($feature);

                        // Define checked
                        $checked = in_array($results['permission'], $permissions) ? "selected" : "";

                        // Show options
                        echo "<option value='" . $results['permission'] . "' " . $checked . ">" .
                        $feature . " - " . $permission . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="admin-forminfo">
                <span>All of the permissions selected here will be available to any
                    user in this group.</span>
            </div>
        </div>
        <hr />
        <div class='admin-formheader'>Group Home Page</div>
        <div>
            <div class='admin-formcolumn' style='width:150px;'>
                <input type='hidden' id='type' value='<?=$homeType['type'] ?>' />
                <ul class='admin-columnlist'>
                    <li><a href='#' onclick='return switch_type("nooverride");'>No Override</a></li>
                    <li><a href='#' onclick='return switch_type("page");'>Page</a></li>
                    <li><a href='#' onclick='return switch_type("feature");'>Feature</a></li>
                    <li><a href='#' onclick='return switch_type("custom");'>Custom URL</a></li>
                </ul>
            </div>

            <!-- No Override -->
            <?php
            // Define no home variables
            $homeNone['show'] = 'display:none;';
            if ($homeType['type'] == 'nooverride') {
                $homeNone['show'] = 'display:block;';
            }
            ?>
            <div class='admin-formcolumn' id='nooverride' style='<?=$homeNone['show']?>width:auto;'>
                <div class='afi-col-nopad'>
                    <p>
                        User's in this group will go to the default home page that you
                        have set up in Site Settings.
                    </p>
                    <p>
                        To override the default home page, giving this group it's own
                        home page is easy. Choose an item to the left and follow the
                        instructions!
                    </p>
                    <p>
                        If you're looking to change the default home page,
                        <a href='#' onclick='return admin_go("settings", "settings/")'>
                            click here</a>.
                    </p>
                </div>
            </div>
            <!-- End No Override -->

            <!-- Pages -->
            <?php
            // Define home page variables
            $homePage['show'] = 'display:none;';
            $homePage['id'] = '';
            if  ($homeType['type'] == 'page') {
                $homePage['show'] = 'display:block;';
                $homePage['id'] = $homeType['id'];
            }
            ?>
            <div class='admin-formcolumn' id='page' style='<?=$homePage['show']?>width:auto;'>
                <div class='admin-formrow'>
                    <div class='admin-formlabel'>Home Page</div>
                    <div class='admin-forminput'>
                        <label class='admin-selectlabel'>
                            <select name='pageid'>
                            <?php
                            // Define the pages table
                            $pages = $tDataClass->prefix . '_pages';

                            // Query the database for all pages
                            $sql['pages'] = 'SELECT * FROM `' . $pages . '`';
                            $qry['pages'] = $tData -> query($sql['pages']);

                            // Make sure there are pages
                            if ($qry['pages'] -> num_rows > 0) {
                                // Grab the pages data and loop
                                while ($page = $qry['pages'] -> fetch_assoc()) {
                                    $homePage['selected'] = $homePage['id'] == $page['id'] ? 'selected' : '';
                                    echo '<option value="' . $page['id'] . '" ' . $homePage['selected'] . '>'
                                         . $page['title'] . '</option>';
                                }
                            } else {
                                echo '<option>Error!</option>';
                            }
                            ?>
                            </select>
                        </label>
                    </div>
                    <hr />
                    <div class='afi-col-nopad'>
                        <p>
                            Choosing this option will direct your users to a
                            static page that you've created with the Pages
                            feature within the Theamus system.
                        </p>
                        <p>
                            If you're looking to have a separate view for users
                            that are logged in and logged out, check out the
                            Session Views tab.
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Pages -->

            <!-- Features -->
            <?php
            // Define home feature variables
            $homeFeature['show'] = 'display:none;';
            $homeFeature['id'] = $homeFeature['file'] = '';
            if ($homeType['type'] == 'feature') {
                $homeFeature['show'] = 'display:block;';
                $homeFeature['id'] = $homeType['id'];
                $homeFeature['file'] = $homeType['file'];
            }
            ?>
            <div id='feature' style='<?=$homeFeature['show']?>width:auto;' class='admin-formcolumn'>
                <div class='admin-formrow'>
                    <div class='admin-formlabel'>Feature</div>
                    <div class='admin-forminput'>
                        <label class='admin-selectlabel'>
                            <select name='featurename' onchange="update_features(this.value)">
                                <?php
                                // Define the features table and query the database
                                // for all available features
                                $features = $tDataClass->prefix . '_features';
                                $sql['features'] = 'SELECT * FROM `' . $features . '`';
                                $qry['features'] = $tData->query($sql['features']);

                                // Make sure there are features to show
                                if ($qry['features'] -> num_rows > 0) {
                                    $fi = 0; // Counter!
                                    // Grab the feature information and loop
                                    while ($feature = $qry['features'] -> fetch_assoc()) {
                                        $homeFeature['selected'] = $homeFeature['id'] == $feature['id'] ? 'selected' : '';

                                        if ($homeFeature['id'] != '') {
                                            $sql['sfeature'] = 'SELECT * FROM `' . $features . '` WHERE `id`="'.$homeFeature['id'].'"';
                                            $qry['sfeature'] = $tData->query($sql['sfeature']);
                                            $sfeature = $qry['sfeature']->fetch_assoc();
                                            $firstFeature = $sfeature['alias'];
                                        } else {
                                            if ($fi == 0) {
                                                $firstFeature = $feature['alias'];
                                            }
                                        }
                                        echo '<option value="' . $feature['id'] . '" ' . $homeFeature['selected'] . '>' .
                                             $feature['name'] . '</option>';
                                        $fi++; // Add to the counter
                                    }
                                } else {
                                    $firstFeature = false;
                                }
                                ?>
                            </select>
                        </label>
                    </div>
                </div>
                <?php if ($firstFeature != false): ?>
                <div class='admin-formrow'>
                    <div class='admin-formlabel'>Feature File</div>
                    <div class='admin-forminput'>
                        <label class='admin-selectlabel'>
                            <select name='featurefile' id='feature-file-list'>
                            <?php
                            // Define the path to the feature's view files
                            $featurePath = path(ROOT . '/features/' . $firstFeature . '/views');

                            // Get all of the view files
                            $featureFiles = $tFiles -> scan_folder($featurePath, $featurePath);

                            // Make sure there are files
                            if (count($featureFiles) > 0) {
                                // Loop through all of the files
                                foreach ($featureFiles as $fFile) {
                                    $hfile = '';
                                    // Remove the file type
                                    $file = explode('.', $fFile);

                                    // Clean up the file name
                                    $fileName = str_replace('.php', '', $fFile);
                                    $fileName = str_replace('/', ' / ', $fileName);
                                    $fileName = str_replace('_', ' ', $fileName);
                                    $fileName = str_replace('-', ' ', $fileName);
                                    $fileName = ucwords($fileName);

                                    if ($homeFeature['file'] != '') {
                                        $hfile['selected'] = $homeFeature['file'].'.php' == $fFile ? 'selected' : '';
                                    } else if ($fFile == 'index.php') {
                                        $hfile['selected'] = 'selected';
                                    } else {
                                        $hfile['selected'] = '';
                                    }

                                    // Create the option
                                    echo '<option value="' . $file[0] . '" ' . $hfile['selected'] . '>'
                                    . $fileName . '</option>';
                                }
                            } else {
                                echo '<option>There aren\'t any files.</option>';
                            }
                            ?>
                            </select>
                        </label>
                    </div>
                </div>
                <?php endif; ?>
                <hr />
                <div class='afi-col-nopad'>
                    <p>
                        If you really want to go to a feature, you just have
                        to select it from the top selection box. That will
                        take you to the index page by default. If you want
                        or need to go to a specific page in the feature, just
                        select a different selection.
                    </p>
                </div>
            </div>
            <!-- End Features -->

            <!-- Custom URL -->
            <?php
            $homeCustom['show'] = 'display:none;';
            $homeCustom['url'] = '';
            if ($homeType['type'] == 'custom') {
                $homeCustom['show'] = 'display:block;';
                $homeCustom['url'] = $homeType['url'];
            }
            ?>
            <div class='admin-formcolumn' id='nocustom' style='display:none;width:auto;'>
                <div class='afi-col-nopad'>
                    You can't require a login to a custom url, that's just silly.
                    If you want to go to a custom url, you need to turn off the
                    required login. To do that,
                    <a href='#' onclick='return switch_type("login");'>click here</a>.
                </div>
            </div>
            <div class='admin-formcolumn' id='custom' style='<?=$homeCustom['show']?>width:auto;'>
                <div class='admin-formrow'>
                    <div class='admin-formlabel'>Custom URL</div>
                    <div class='admin-forminput'>
                        <input type='text' class='longtext' name='customurl'
                          maxlength='100' spellcheck='off' autocomplete='off'
                          autocapitalize='off' value='<?=$homeCustom['url']?>' />
                    </div>
                    <hr />
                    <div class='afi-col-nopad'>
                        <p>
                            The Custom URL that you're inputting here is to a
                            specific page within your site. It <b>cannot</b>
                            go to an external site.
                        </p>
                        <p>
                            For example, you have a blog and you want to link
                            to a specific post. Your URL would look like:
                            http://www.theamus.com/blog/posts/this-is-a-post
                        </p>
                        <p>
                            All you need to input is: blog/posts/this-is-a-post
                        </p>
                        <p>
                            Everything else, like the base of the path, is assumed.
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Custom URL -->
            <div class='clearfix'></div>
        </div>

        <hr />

        <div class="admin-formsubmitrow">
            <input type="submit" class="admin-greenbtn" value="Save Information" />
            <input type="button" class="admin-redbtn" value="Cancel"
                onclick="return admin_go('accounts', 'groups/')" />
        </div>
    </form>
	<?php endif; ?>
</div>