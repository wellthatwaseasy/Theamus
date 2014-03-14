<?php

/**
 * tTheme - Theamus theme parsing class
 * PHP Version 5.5.3
 * Version 1.0
 * @package Theamus
 * @link http://www.theamus.com/
 * @author Matthew Tempinski (Eyraahh) <matt.tempinski@theamus.com>
 * @copyright 2014 Matthew Tempinski
 */

class tTheme {
    /**
     * Holds all of the information relative to the user
     *
     * @var object $tUser
     */
    private $tUser;


    /**
     * Class variable to handle data
     *
     * @var object $tDataClass
     */
    private $tDataClass;


    /**
     * Mysqli object
     *
     * @var object $tData
     */
    private $tData;


    /**
     * Holds the data given by the call
     *
     * @var array $data
     */
    protected $data;


    /**
     * Holds the data found in the theme's configuration file
     *
     * @var array $config
     */
    protected $config;


    /**
     * Holds the data that is accessible to the theme
     *
     * @var array $nice_data
     */
    private $nice_data;


    /**
     * The template file found in the theme's configuration settings
     *
     * @var string $templage
     */
    private $template;


    /**
     * The contents of the HTML markup in the header tags (<head></head>)
     *
     * @var string $header
     */
    private $header;


    /**
     * True/False on whether or not the admin panel is accessible
     *
     * @var boolean $admin
     */
    private $admin;


    /**
     * The contents of the body HTML markup before the content
     *
     * @var string body
     */
    private $body;


    /**
     * The contents of the footer HTML markup after the content
     *
     * @var string $footer
     */
    private $footer;


    /**
     * Holds the value of whether or not this output has extra navigation associated
     *  with it and needs to include the extra navigation class
     *
     * @var array $nav
     */
    private $nav;


    /**
     * Starts and initializes variables to be used by the class.
     * Then performs a try/catch statement to check/run the theme parsing
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->data = $data;
        $this->tUser = new tUser();
        $this->tDataClass = new tData();
        $this->tData = $this->tDataClass->connect();
        $this->tDataClass->prefix = $this->tDataClass->get_system_prefix();

        try {
            $this->nice_data = $this->clean_data();
            $this->config = $this->get_config();
            $this->template = $this->get_template();
            $contents = $this->get_template_contents();
            $contents = $this->parse_template_contents($contents);
            $this->define_parts($contents);
        } catch (Exception $e) {
            die("<strong>Theamus Theme Error:</strong> ".$e->getMessage());
        }
    }


    /**
     * Output the page header
     */
    public function print_header() {
        echo $this->header;
    }


    /**
     * Output the page body
     */
    public function print_body() {
        echo $this->body;
    }


    /**
     * Output the page footer
     */
    public function print_footer() {
        echo $this->footer;
    }


    /**
     * Gets the theme folder from the data given
     *
     * @return string
     */
    private function get_theme_folder() {
        $split = strpos($this->data['theme'], "\\") !== false ? "\\" : "/";
        $path = explode($split, trim($this->data['theme'], $split));
        return array_pop($path);
    }


    /**
     * Gets the platform settings from the database
     *
     * @return array
     * @throws Exception
     */
    private function get_settings() {
        $q = $this->tData->query("SELECT * FROM `".$this->tDataClass->prefix."_settings`");
        if (!$q) throw new Exception("Error getting system settings information from the database.");
        return $q->fetch_assoc();
    }


    /**
     * Cleans the incoming data from the call and sanitizes it to be called from
     *  the theme
     *
     * @return array
     */
    private function clean_data() {
        $settings = $this->get_settings();
        $ret['title'] = isset($this->data['title']) ? $this->data['title']." - ".$this->data['name'] : $this->data['name'];
        $ret['header'] = isset($this->data['header']) ? $this->data['header'] : "";
        $ret['theme_path'] = trim(web_path(trim(str_replace(ROOT, "", $this->data['theme']), "/")), "/")."/";
        $ret['wrapper_top'] = $this->tUser->is_admin() ? "style='top:37px;'" : "";
        $ret['site_name'] = urldecode(stripslashes($settings['name']));
        $ret['error_type'] = isset($this->data['error_type']) ? $this->data['error_type'] : 0;
        return $ret;
    }


    /**
     * Gets information from the theme's configuration file
     *
     * @return array
     * @throws Exception
     */
    private function get_config() {
        $config_path = path($this->data['theme']."/config.json");
        if (file_exists($config_path)) {
            return json_decode(file_get_contents($config_path));
        }
        throw new Exception("Cannot locate the theme configuration file.");
    }


    /**
     * Gets the template file requested based on the configuration file and the call settings
     *
     * @return string $ret
     * @throws Exception
     */
    private function get_template() {
        for ($i = 0; $i < count($this->config->layouts); $i++) {
            if ($this->config->layouts[$i]->layout == "default") $ret = $this->config->layouts[$i]->file;
            elseif ($this->config->layouts[$i]->layout == $this->data['template']) $ret = $this->config->layouts[$i]->file;
        }
        if (isset($ret)) return $ret;
        throw new Exception("Cannot find the template file in the theme configuration.");
    }


    /**
     * Gets the contents of the template index file
     *
     * @return string
     * @throws Exception
     */
    private function get_template_contents() {
        $template_path = path($this->data['theme']."/".$this->template);
        if (file_exists($template_path)) {
            return file_get_contents($template_path);
        }
        throw new Exception("Cannot find the template file in the directory structure.");
    }


    /**
     * Parses all of the template file to include everything required and requested
     *
     * @param string $contents
     * @return string $ret
     */
    private function parse_template_contents($contents = "") {
        $this->get_areas($contents);
        $ret = $this->set_variables($contents);
        $ret = $this->add_header_data($ret);
        $ret = $this->set_areas($ret);
        $ret = $this->do_loops($ret);
        $ret = $this->get_navigation($ret);
        $ret = $this->set_user_areas($ret);
        $ret = $this->set_user_variables($ret);
        $ret = $this->set_variables($ret);
        return $ret;
    }


    /**
     * Finds any calls for variables in the theme contents and replaces them with actual
     *  data/values
     *
     * @param string $contents
     * @return string $output
     */
    private function set_variables($contents) {
        $output = preg_replace_callback(
            array("/{t:var='(.*?)':}/s", "/{t:var=\"(.*?)\":}/s"),
            function($match) { return str_replace($match[0], $this->nice_data[$match[1]], $match[0]); },
            $contents
        );
        return $output;
    }


    /**
     * Finds any calls for loops in the theme contents and executes them
     *
     * @param string $contents
     * @return string $output
     * @throws Exception
     */
    private function do_loops($contents) {
        $output = preg_replace_callback(
            "/{t:loop\((.*?)\):}(.*?){t:\/loop:}/s",
            function($match) {
                $theme_data_table = $this->tDataClass->prefix."_themes-data";
                $q = $this->tData->query("SELECT * FROM `$theme_data_table` WHERE `selector` LIKE '".trim($match[1], "\"")."-%' AND `theme`='".$this->get_theme_folder()."' ORDER BY `selector`");
                if ($q) {
                    $vars = [];
                    while ($row = $q->fetch_assoc()) $vars[] = $row;
                    $clean_vars = $this->clean_theme_vars($vars);
                    return $this->set_loop_variables($match[2], $clean_vars);
                } else throw new Exception("Invalid loop query.");
            },
            $contents
        );
        return $output;
    }


    /**
     * In a loop, finds any variables being requested and replaces them with actual data
     *
     * @param string $template
     * @param array $clean_vars
     * @return string
     */
    private function set_loop_variables($template, $clean_vars) {
        $return_template = "";
        foreach ($clean_vars as $cv) {
            $temp_template = preg_replace_callback(
                array("/{t:loop_var='(.*?)':}/s", "/{t:loop_var=\"(.*?)\":}/s"),
                function($match) use ($template, $cv) { return str_replace($match[0], $cv[$match[1]], $match[0]); },
                $template
            );
            $return_template .= $temp_template;
        }
        return $return_template;
    }


    /**
     * Cleans variables for a theme
     *
     * @param array $vars
     * @return array
     */
    private function clean_theme_vars($vars) {
        $ret = [];
        foreach ($vars as $v) {
            $ret[$v['selector']][$v['key']] = $v['value'];
            if (!array_key_exists("selector", $ret[$v['selector']])) $ret[$v['selector']]['selector'] = $v['selector'];
        }
        return $ret;
    }


    /**
     * Adds the platform header data to the <head></head> tags
     *
     * @param string $contents
     * @return string $output
     */
    private function add_header_data($contents) {
        $output = preg_replace_callback(
            "/<head>(.*?)<\/head>/s",
            function($match) {
                $base = "<base href='".$this->data['base']."' />";
                $info = $base.$this->data['js'].$this->data['css'].$match[1];
                return str_replace($match[1], $info, $match[0]);
            },
            $contents
        );
        return $output;
    }


    /**
     * Finds all of the areas in the theme contents, mostly looking for navigation areas
     *
     * @param string $contents
     * @return
     */
    private function get_areas($contents) {
        $output = preg_replace_callback(
            array("/{t:area=\"(.*?)\":}/s", "/{t:area='(.*?)':}/s"),
            function($match) {
                if ($match[1] == "extra-nav" && (!isset($this->data['nav']) || $this->data['nav'] == "")) $ret = "false";
                else $ret = "true";
                $this->nav[] = $ret;
                return;
            },
            $contents
        );
        if (is_array($this->nav)) {
            $this->nice_data['nav'] = in_array("false", $this->nav) ? "" : "site_with-nav";
        } else $this->nice_data['nav'] = "";
    }


    /**
     * Finds all of the requested areas in the theme's file contents and replaces them with
     *  their respective area files
     *
     * @param string $contents
     * @return string $output
     */
    private function set_areas($contents) {
        $output = preg_replace_callback(
            array("/{t:area=\"(.*?)\":}/s", "/{t:area='(.*?)':}/s"),
            function($match) { return $this->get_area_content($match[1]); },
            $contents
        );
        return $output;
    }


    /**
     * Finds the area file from the configuration settings and returns the contents of the file
     *  to be used
     *
     * @param string $area
     * @return string
     */
    private function get_area_content($area) {
        if ($area == "extra-nav" && (!isset($this->data['nav']) || $this->data['nav'] == "")) return "";
        if (property_exists($this->config->areas, $area)) {
            $path = path($this->data['theme']."/".$this->config->areas->$area);
            if (file_exists($path)) return $this->set_variables(file_get_contents($path));
        }
        return "";
    }


    /**
     * Finds all navigation areas and replaces them with the respective navigation
     *
     * @param string $contents
     * @return string $output
     */
    private function get_navigation($contents) {
        $output = preg_replace_callback(
            array("/{t:nav='(.*?)':}/s", "/{t:nav=\"(.*?)\":}/s"),
            function($match) {
                if ($match[1] == "main") {
                    return str_replace($match[0], show_page_navigation(), $match[0]);
                } elseif ($match[1] == "extra") {
                    if ($this->data['nav'] != "") return str_replace($match[0], extra_page_navigation($this->data['nav']), $match[0]);
                    else return;
                } else {
                    return str_replace($match[0], show_page_navigation($match[1]), $match[0]);
                }
            },
            $contents
        );
        return $output;
    }


    /**
     * Defines all of the user areas that can be used in a theme
     *
     * @param string $contents
     * @return string $output
     */
    private function set_user_areas($contents) {
        $ret = $contents;
        $types = array("admin", "noadmin", "useradmin", "user", "nouser");
        foreach ($types as $type) $ret = $this->set_user_area($ret, $type);
        return $ret;
    }


    /**
     * Loops through all of the possible types of user areas and performs the relative
     *  check in order to show content or not.
     *
     * @param string $contents
     * @param string $type
     * @return string $output
     */
    private function set_user_area($contents, $type) {
        switch ($type) {
            case "admin":
                $regex = "/{t:admin:}(.*?){t:\/admin:}*/s";
                $replace = $this->tUser->is_admin() ? true : false;
                break;
            case "noadmin":
                $regex = "/{t:!admin:}(.*?){t:\/!admin:}/s";
                $replace = !$this->tUser->is_admin() ? true : false;
                break;
            case "useradmin":
                $regex = "/{t:user&admin:}(.*?){t:\/user&admin:}/s";
                $replace = $this->tUser->user && $this->tUser->is_admin() ? true : false;
                break;
            case "user":
                $regex = "/{t:user:}(.*){t:\/user:}/s";
                $replace = $this->tUser->user ? true : false;
                break;
            case "nouser":
                $regex = "/{t:!user:}(.*?){t:\/!user:}/s";
                $replace = !$this->tUser->user ? true : false;
                break;
        }
        $output = preg_replace_callback(
            $regex,
            function($match) use($replace, $regex) {
                if ($replace) return str_replace($match[0], $match[1], $match[0]);
                else return str_replace($match[0], "", $match[0]);
            },
            $contents
        );
        return $output;
    }


    /**
     * Finds any requests to get the currently logged in user's information and replaces it
     *  with actual data
     *
     * @param string $contents
     * @return string
     */
    private function set_user_variables($contents) {
        $output = preg_replace_callback(
            array("/{t:user_var='(.*?)':}/s", "/{t:user_var=\"(.*?)\":}/s"),
            function($match) {
                return str_replace($match[0], urldecode($this->tUser->user[$match[1]]), $match[0]);
            },
            $contents
        );
        return $output;
    }


    /**
     * Defines the parts of the page that are able to be shown
     *
     * @param string $contents
     */
    private function define_parts($contents) {
        if (strpos($contents, "{t:admin_panel:}") !== false) {
            $this->admin = true;
            $header = explode("{t:admin_panel:}", $contents);
            $content = explode("{t:content:}", $header[1]);
            $this->header = $header[0];
            $this->body = $content[0];
            if (count($content) >= 2) $this->footer = $content[1];
        } elseif (strpos($contents, "{t:content:}") !== false) {
            $this->admin = false;
            $content = explode("{t:content:}", $contents);
            $this->header = $content[0];
            $this->body = false;
            $this->footer = $content[1];
        } else {
            $this->admin = false;
            $this->header = $contents;
            $this->body = false;
            $this->footer = false;
        }
    }
}