<?php

/**
 * tInstall - Theamus installer class
 * PHP Version 5.5.3
 * Version 1.0
 * @package Theamus
 * @link http://www.theamus.com/
 * @author Matthew Tempinski (Eyraahh) <matt.tempinski@theamus.com>
 * @copyright 2014 Matthew Tempinski
 */

class tInstall {
    /**
     * Complete base url to the current website (e.g. http://www.mysite.com/)
     * 
     * @var string $url
     */
    private $url;
    
    
    /**
     * Holds the answer to whether or not the database exists
     * 
     * @var boolean $db_exists
     */
    private $db_exists;
    
    
    /**
     * If the connection to the database fails, this is just false
     * If the connection doesn't fail, this holds the mysqli object
     * 
     * @var boolean|object $connection
     */
    private $connection;
    
    
    /**
     * Holds the answer to whether or not the database has been installed
     * 
     * @var boolean $installed
     */
    private $installed;

    
    /**
     * Constructs the class, defining class-specific variables
     * 
     * @param string $url
     * @return boolean
     */
    public function __construct($url) {
        $this->url = $url;
        $this->initiate_variables();
        return true;
    }
    
    /**
     * Deconstructs the class, closing the database connection if there is one
     * 
     * @return boolean
     */
    public function __destruct() {
        if ($this->connection) $this->connection->close();
        return true;
    }
    
    
    /**
     * Defines any class related variables
     * 
     * @return boolean
     */
    private function initiate_variables() {
        $this->config_file  = $this->check_configuration_file();
        $this->db_exists    = $this->check_database_existence();
        $this->installed    = $this->check_installation();
        return true;
    }

    
    /**
     * Checks for the existence of "config.php"
     * 
     * @return boolean
     */
    private function check_configuration_file() {
        return file_exists(path(ROOT."/config.php")) ? true : false;
    }

    
    /**
     * Performs a check to see whether or not the database credentials are valid
     *  and if the database exists
     * 
     * It also defines the mysqli connection object if everything is ok
     * 
     * @return boolean
     */
    private function check_database_existence() {
        if (file_exists(path(ROOT."/config.php"))) {
            include path(ROOT."/config.php");
            $connection = @new mysqli($config['Database']['Host Address'], 
                                      $config['Database']['Username'], 
                                      $config['Database']['Password'], 
                                      $config['Database']['Name']);

                if ($connection->connect_errno) {
                    $this->connection = false;
                    return false;
                } else {
                    $this->connection = $connection;
                    return true;
                }
        }
        return false;
    }

    
    /**
     * Checks to see if the site has been installed in the database or not
     * 
     * @return boolean
     */
    private function check_installation() {
        if ($this->connection) {
            $tDataClass = new tData();
            $tData      = $tDataClass->connect();

            $q = $tData->query("SELECT * FROM `".$tDataClass->get_system_prefix()."_settings` WHERE `installed`='1'");
            if ($q) $ret = $q->num_rows > 0 ? true : false;
            
            $tDataClass->disconnect();
            return isset($ret) ? $ret : false;
        }
        return false;
    }

    
    /**
     * If the site has not been installed, show the installer and go from there
     * 
     * @return boolean
     */
    public function run_installer() {
        if ($this->config_file == false || $this->db_exists == false || $this->installed == false) {
            $this->show_installer_page($this->url);
            return false;
        }
        return true;
    }

    
    /**
     * Shows the contents of the installer
     * 
     * @return boolean
     */
    private function show_installer_page()	{
        $data['base']   = $this->url;
        $data['js']     = "<script src='system/js/jquery.js'></script>";
        $data['js']     .= "<script src='system/js/ajax/ajax.js'></script>";
        $data['js']     .= "<script src='system/js/main.js'></script>";

        include path(ROOT."/themes/installer/html.php");
        open_page($data);
        close_page();
        
        return true;
    }
}