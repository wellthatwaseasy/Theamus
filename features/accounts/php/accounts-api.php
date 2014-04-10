<?php

class AccountsApi extends Accounts {
    private $api_return = array("response"=>array("data"=>""), "error"=>array("status"=>0,"message"=>""));

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

        // Check for an active user
        if ($row['active'] == 0) {
            $this->api_error("Your account is not active.");
            return $this->api_return;
        }

        // Define a new session value
        $session = md5(time().$session_salt);

        // Cookie expiration time
        $expire = time() + 3600;
        if (isset($args['keep_session'])) {
            if ($args['keep_session'] == "true") {
                $expire = time() + (60 * 60 * 24 * 14);
            }
        }

        // Update the user's session in the database
        if ($this->tUser->add_user_session($row['id'], $session, $expire)) {
            return true;
        } else {
            $this->api_error("There was an error updating/creating the session.");
            return $this->api_return;
        }
    }

    public function check_username($args) {
        // Check for a username
        if (!isset($args['username']) || $args['username'] == "") {
            return "invalid";
        }

        // Return the accounts class username check
        return $this->define_username(urldecode($args['username']));
    }

    public function check_password($args) {
        // Check for a password
        if (!isset($args['password']) || $args['password'] == "") {
            return "invalid";
        }

        // Return the accounts class username check
        return $this->define_password(urldecode($args['password']));
    }

    public function check_email($args) {
        // Check for an email
        if (!isset($args['email']) || $args['email'] == "") {
            return "invalid";
        }

        // Return the accounts class username check
        return $this->define_email(urldecode($args['email']));
    }

    public function register_user($args) {
        return $this->create_registered_user($args);
    }
}