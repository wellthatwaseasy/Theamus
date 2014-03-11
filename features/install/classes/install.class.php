<?php

class Installer {
    private $tDataClass;
    private $tData;
    
    public function __construct() {
        $this->initialize_variables();
        return true;
    }
    
    public function __destruct() {
        $this->tDataClass->disconnect();
    }
    
    private function initialize_variables() {
        $this->tDataClass = new tData();
        $this->tData = $this->tDataClass->connect();
        $this->tDataClass->prefix = $this->tDataClass->get_system_prefix();
    }
    
    public function check_database() {
        $q = $this->tData->query("SELECT * FROM `".$this->tDataClass->prefix."_settings`");
        if ($q) return true;
        else return false;
    }
    
    public function reset_database() {
        $db = $this->tData->query("SELECT DATABASE()")->fetch_assoc();
        $q = $this->tData->query("DROP DATABASE `".$db['DATABASE()']."`");
        if ($q) $this->tData->query("CREATE DATABASE `".$db['DATABASE()']."`");
        else die(notify("install", "failure", "There was an error resetting the database."));
    }
    
    public function check_admin_user() {
        $q = $this->tData->query("SELECT * FROM `".$this->tDataClass->prefix."_users`");
        if ($q->num_rows > 0) return true;
        return false;
    }
    
    public function reset_users() {
        if ($this->tData->query("DELETE FROM `".$this->tDataClass->prefix."_users`")) {
            if ($this->tData->query("ALTER TABLE `".$this->tDataClass->prefix."_users` AUTO_INCREMENT=1")) {
                return true;
            }
        }
        return false;
    }
}