<?php

class AccountsApi {
    private $tData;

    private $api_return = array("response"=>array("data"=>""), "error"=>array("status"=>0,"message"=>""));

    public function __construct() {
        $this->initialize_variables();
    }

    public function __destruct() {
        $this->tData->disconnect();
    }

    private function initialize_variables() {
        $this->tData = new tData();
        $this->tData->db = $this->tData->connect();
        $this->tData->prefix = $this->tData->get_system_prefix();
    }

    private function api_error($message = "") {
        $this->api_return['error'] = array("status"=>1,"message"=>$message);
    }

    public function login($args) {
        // Define the salts
        $session_salt = $this->tData->get_config_salt("session");

        // Check the username
        if (isset($args['username'])) {
            if ($args['username'] != "") {
                $username = $this->tData->db->real_escape_string(urldecode($args['username']));
            } else {
                $this->api_error("Please fill out the 'Username' field.");
                return $this->api_return;
            }
        } else {
            $this->api_error("There was an error finding the username variable.");
            return $this->api_return;
        }

        // Check the password
        if (isset($args['password'])) {
            if ($args['password'] != "") {
                $hashed_password = hash("SHA256", urldecode($args['password']).$this->tData->get_config_salt("password"));
                $password = $this->tData->db->real_escape_string($hashed_password);
            } else {
                $this->api_error("Please fill out the 'Password' field.");
                return $this->api_return;
            }
        } else {
            $this->api_error("There was an error finding the password variable.");
            return $this->api_return;
        }


        // Query the database for an existing user
        $fetch_query = $this->tData->db->query("SELECT * FROM `".$this->tData->prefix."_users` WHERE `username`='$username' AND `password`='$password'");
        if ($fetch_query->num_rows == 0) {
            $this->api_error("Invalid credentials.");
            return $this->api_return;
        }

        // Define the user information
        $row = $fetch_query->fetch_assoc();

        // Define a new session value and update the user's information with it
        $session = md5(time().$session_salt);
        $this->tData->db->query("UPDATE `".$this->tData->prefix."_users` SET `session`='$session' WHERE `id`='".$row['id']."'");

        // Cookie expiration time
        $expire = time() + 3600;
        if (isset($args['keep_session'])) {
            if ($args['keep_session'] == "true") {
                $expire = time() + (60 * 60 * 24 * 14);
            }
        }

        // Set the cookie for the user id and session id
        setcookie("userid", $row['id'], $expire, "/");
        setcookie("session", $session, $expire, "/");

        return true;
    }
}