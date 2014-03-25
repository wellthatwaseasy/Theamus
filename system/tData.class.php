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
     * Holds the value of whether or not the api call failed.
     *
     * @var boolean $api_fail
     */
    private $api_fail = false;


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


    public function array_is_associative($array = array()) {
        if (empty($array)) return true;
        return array_keys($array) !== range(0, count($array) - 1);
    }

    public function string_is_json($string = "") {
        if ($string == "") return false;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    public function get_hash($user = false) {
        // Define the hash variables
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $date = $user == true ? date("Y-d-m") : "";
        $server_ip = $_SERVER['SERVER_ADDR'];

        // Return the hash
        return md5($user_ip.$date.$server_ip);
    }


    private function api_error($message = "") {
        $return = array();

        // Define the error
        $return['error']['status'] = 1;
        $return['error']['message'] = $message;

        // Define the response
        $return['response']['status'] = "null";
        $return['response']['headers'] = "";
        $return['response']['data'] = "";

        return $return;
    }


    private function api_check_args($args) {
        $return = array();

        // Define defaults
        $return['ajax'] = "api";

        // Define the type and the url
        if (isset($args['type']) && gettype($args['type']) == "string") {
            if ($args['type'] != "get" && $args['type'] != "post") $this->api_fail = "API request type must be 'post' or 'get'.";
            else $return['type'] = $args['type'];
        } else $this->api_fail = "Invalid API request type.";
        isset($args['url']) && gettype($args['url']) == "string" ? $return['url'] = urldecode($args['url']) : $this->api_fail = "Invalid API url.";

        // Define the method
        if (isset($args['method'])) {
            $return['method_class'] = "";
            if (gettype($args['method']) == "array") {
                count($args['method']) >= 1 ? $return['method_class'] = $args['method'][0] : $this->api_fail = "Undefined API method.";
                count($args['method']) >= 2 ? $return['method'] = $args['method'][1] : $this->api_fail = "Undefined API method after finding class.";
            } elseif (gettype($args['method'] == "string")) {
                $return['method'] = $args['method'];
            } else $this->api_fail = "Invalid API method defined.";
        } else $this->api_fail = "API method not defined.";

        // Define the data
        if (isset($args['data'])) {
            if (gettype($args['data']) == "array") {
                if ($this->array_is_associative($args['data']) == true) {
                    $return['data'] = $args['data'];
                } else $this->api_fail = "API data parameter must be a key => value array.";
            } else $this->api_fail = "API data parameter must be a key => value array.";
        } else $return['data'] = "";

        // Define the API key
        if (isset($args['key'])) {
            if (gettype($args['key']) == "string") {
                json_encode(array("key"=>$args['key']));
            } else $this->api_fail = "Invalid API key type.";
        } else $return['api-key'] = json_encode(array("key"=>$this->get_hash()));

        return $return;
    }


    private function check_curl() {
        if (function_exists("curl_version")) return true;
        return false;
    }


    private function define_api_variables($args) {
        $return = array();
        foreach ($args as $key => $value) {
            if (is_array($value)) $value = json_encode($value);
            $return[] = "$key=$value";
        }
        return implode("&", $return);
    }


    private function send_api($args) {
        // Open the connection
        $ch = curl_init();

        // Define options
        curl_setopt($ch, CURLOPT_URL, "http://localhost/Theamus-Development/default/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->define_api_variables($args));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Execute and return
        $result = curl_exec($ch);
        return $result;
    }


    public function api($args = array()) {
        $return = array();
        if (empty($args)) $return = $this->api_error("API arguments are required.");

        // Define and check the arguments
        $args = $this->api_check_args($args);

        // Make the call
        if ($this->check_curl() && $this->api_fail == false) {
            $return = $this->send_api($args);
        }

        // Throw errors or return
        if ($this->api_fail != false) $return = $this->api_error($this->api_fail);
        return json_decode($return, true);
    }
}
