<?php

/**
 * tData - Theamus database access class
 * PHP Version 5.5.3
 * Version 1.0
 * @package Theamus
 * @link http://www.theamus.com/
 * @author Matthew Tempinski (Eyraahh) <matt.tempinski@theamus.com>
 * @copyright 2014 Matthew Tempinski
 */

class tData {
    /**
     * Contains the information given by the configuration file
     *
     * @var array $config
     */
    private $config;


    /**
     * The mysqli object for the class
     *
     * @var object $connection
     */
    private $connection;


    /**
     * The prefix to the system specific database tables
     *
     * @var string $prefix
     */
    public $prefix;


    /**
     * Initializes the class, defines the configuration given by the system
     *
     * @return boolean
     */
    public function __construct() {
        $this->config = $this->define_system_configuration();
        $this->set_timezone();
        return true;
    }


    /**
     * Defines the system configuration file into an array
     *
     * @return array $ret
     */
    private function define_system_configuration() {
        $config_path = path(ROOT."/config.php");
        $ret = array();
        if (file_exists($config_path)) {
            include $config_path;
            $ret = $config;
        }
        return $ret;
    }


    /**
     * Sets the timezone for the entire system
     *
     * @return
     */
    private function set_timezone() {
        $tz = isset($this->config['timezone']) ? $this->config['timezone'] : "America/Chicago";
        date_default_timezone_set($tz);
        return;
    }


    /**
     * Gets the salt value from the sytem's configuration file
     *
     * @param string $type
     * @return string
     */
    public function get_config_salt($type) {
        $salt = $this->config['salt'][$type];
        return $salt;
    }


    /**
     * Connects the system to the database, you know, to do database things
     *
     * @return boolean
     */
    public function connect() {
        $connection = @new mysqli($this->config['Database']['Host Address'],
                                  $this->config['Database']['Username'],
                                  $this->config['Database']['Password'],
                                  $this->config['Database']['Name']);

        if ($connection->connect_errno) return false;
        else {
            $this->connection = $connection;
            return $connection;
        }
        return false;
    }


    /**
     * Disconnects from the database
     *
     * @return boolean
     */
    public function disconnect() {
        if ($this->connection) $this->connection->close();
        return false;
    }


    /**
     * Gets the system's database prefixes
     *
     * e.g. "tm"
     *
     * @return boolean
     */
    public function get_system_prefix() {
        if ($this->connection) {
            $q = $this->connection->query("SHOW TABLES");

            if ($q) {
                while ($table = $q->fetch_array()) {
                    $explode = explode("_", $table[0]);

                    if ($explode[1] == "settings") return $explode[0];
                }
            }
        }
        return false;
    }


    /**
     * Checks to see if a query is any good, if it is, it will return the query results
     *
     * @param object $query
     * @return boolean
     */
    public function check_query_and_return($query) {
        if ($query) return $query->fetch_assoc();
        return false;
    }


    /**
     * Takes a multi-demensional array and converts it into a single array
     *
     * @param array $array
     * @return array $ret
     */
    public function flatten_array($array) {
        if (!is_array($array)) return array($array);
        $ret = array();
        foreach ($array as $value) $ret = array_merge($ret, flattenArray($value));
        return $ret;
    }


    /**
     * Decodes a Theamus specific encoding.
     * {t:<key>="<val>":} -> array("<key>"=>"<val>")s
     *
     * @param string $inp
     * @return array $ret
     * @throws Exception
     */
    public function t_decode($inp) {
        if ($inp == "") return array();

        preg_match_all('/{t:/i', $inp, $r);
        if (count($r[0]) > 1) throw new Exception("tData: Recusive encoding is not allowed.");

        preg_match("/{t:(.*?):}/i", $inp, $m);
        $exp = explode(";", $m[1]);
        foreach ($exp as $e) {
            if (strpos($e, "=") === false) $ret[] = $e;
            else {
                $iexp = explode("=", $e);
                $ret[$iexp[0]] = trim($iexp[1], "\"");
            }
        }

        return $ret;
    }
}
