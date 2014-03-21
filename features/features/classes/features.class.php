<?php

class Features {
    public $install_sql = "hello moto";
    public function __construct() {
        // Initialize class variables
        $this->initialize_variables();
    }

    public function __destruct() {
        // Disconnect from the database
        $this->tDataClass->disconnect();
    }

    private function initialize_variables() {
        // Define the data class and connect to the database
        $this->tDataClass           = new tData();
        $this->tData                = $this->tDataClass->connect();
        $this->tDataClass->prefix   = $this->tDataClass->get_system_prefix();

        // Define the file management class
        $this->tFiles = new tFiles();
    }

    private function get_upload_file() {
        // Define the file array and the file name
        $file = $_FILES['file'];
        $name_arr = explode(".", $file['name']);

        // Check the filetype and return
        if (end($name_arr) !== "zip") throw new Exception("Only ZIP files may be uploaded.");
        else return $file;
    }

    private function extract_temp_feature($f) {
        // Define the path
        $path = ROOT."/features/features/temp/";

        // Extract the uploaded file
        if ($this->tFiles->extract_zip(path($path.$f), path(trim($path.$f, ".zip")))) return true;
        else throw new Exception("There was an issue extracting the theme");
    }

    private function check_structure($f) {
        // Define the path to the config file
        $path = ROOT."/features/features/temp/".trim($f, ".zip");

        // Check required files and folders
        $temp[] = file_exists(path($path."/config.php")) ? true : false;
        $temp[] = file_exists(path($path."/files.info.php")) ? true : false;
        $temp[] = is_dir(path($path."/views/")) ? true : false;

        // React to check, return
        if (in_array(false, $temp)) throw new Exception("One or more requirements are missing in the feature file/folder structure.");
    }

    private function check_config($f) {
        // Define the path
        $path = ROOT."/features/features/temp/".trim($f, ".zip");

        // Define the required variables
        $required = array("['scripts']['folder']", "['css']['folder']", "['js']['folder']", "['alias']", "['name']", "['groups']", "['db_prefix']");

        // Include the config file
        include path($path."/config.php");

        // Check the required variables
        foreach ($required as $item) {
            $var = "feature".$item;
            $temp[] = isset($var) ? true : false;
        }

        // Return
        if (in_array(false, $temp)) throw new Exception("One or more requirements are missing in the feature configuration file.");
        return $feature;
    }

    private function upload_feature() {
        // Check and define the file, the path, and the temp name
        if (count($_FILES) == 0) throw new Exception("Please select a file to upload.");
        $file = $this->get_upload_file();
        $path = ROOT."/features/features/temp/";
        $temp_name = md5(time()).".zip";

        // Upload the file
        if (move_uploaded_file($file['tmp_name'], path($path.$temp_name))) {
            chmod(path($path.$temp_name), 0777);
            return $temp_name;
        } else throw new Exception("The file failed to upload.");
    }

    private function gather_install_script($f, $config) {
        // Check for an install script
        if (!isset($config['install']['script'])) return false;

        // Define the install script
        $path = path(ROOT."/features/features/temp/".trim($f, ".zip")."/".$config['install']['script']);

        // Define the install class
        include path(ROOT."/features/features/classes/install.class.php");
        $Features = $this;
        $Features->install = new FeatureInstall();
        $Features->install->config = $config;

        // Include the install script
        if (file_exists($path)) include $path;

        // Define the install SQL
        $this->install_sql = $Features->install->get_install_sql();
    }

    public function clean_temp_folder() {
        // Define the path, all files and all folders
        $path = ROOT."/features/features/temp";
        $files = $this->tFiles->scan_folder($path);
        $folders = $this->tFiles->scan_folder($path, false, "folders");

        // Remove everything that isn't the blank file
        foreach ($files as $f) if ($f != path($path."/blank.txt")) unlink($f);
        foreach ($folders as $f) $this->tFiles->remove_folder($f);
    }

    public function prelim_install() {
        // Upload the file
        $filename = $this->upload_feature();
        $this->extract_temp_feature($filename);
        $this->check_structure($filename);
        $config = $this->check_config($filename);
        $this->gather_install_script($filename, $config);

        Pre($this->install_sql);

        $this->clean_temp_folder();
    }
}