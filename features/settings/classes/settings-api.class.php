<?php

class SettingsApi extends Settings {

    /**
     * Checks the update server for any newer versions of Theamus
     *
     * @return boolean
     */
    public function update_check() {
        // Define the system settings
        $settings = $this->get_system_info();

        // Get the update information from the update server
        $info = $this->get_update_info();

        // Check the results
        if ($info['error']['status'] == 1) {
            return false;
        } else {
            // Return true if this is an old version
            if (in_array($settings['version'], $info['response']['data']['old_versions'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function auto_update() {
        // Check if the user has cURL
        if ($this->tData->check_curl()) {
            $this->response_error("You must have the cURL extension available to your server.");
            return $this->return;
        }

        // Run the update
        try {
            // Download the file from the update server and get the information about it
            $filename = $this->download_update();
            $update_information = $this->get_update_information($filename);

            // Get the system info
            $system_info = $this->get_system_info();

            // Extract the update files to the root directory
            $this->extract_update($filename, "root");

            // Run the update scripts based on what's requested
            if ($update_information['run_update_script'] == true) {
                // Include the update files, run the update function if it's there
                $this->include_update_files($filename, $update_information['update_files']);
                if (function_exists("update")) {
                    if (!update($system_info)) {
                        throw new Exception("There was an error when running the update scripts.");
                    }
                }
            }

            // Clean the temp folder and notify the user
            $this->clean_temp_folder();
            $this->return['response']['data'] = notify("admin", "success", "Everything went smoothly.  In order for things to take effect, you need to <a href='./'>refresh the page</a>.", "", true);

            // Return the data
            return $this->return;
        } catch (Exception $ex) {
            $this->clean_temp_folder();
            $this->response_error($ex->getMessage());
            return $this->return;
        }
    }
}