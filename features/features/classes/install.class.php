<?php

class FeatureInstall {
    private $install_sql = array();
    public $config = array();

    public function __construct() {
        // Define class variables
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

        // Define the features class
        $this->Features = new Features();
    }

    public function create_table($table, $columns) {
        // Query the database for this table's existance
        $query = $this->tData->query("SHOW TABLES LIKE '$table'");
        if ($query->num_rows == 1) return;

        // Create the query to run to create this table
        foreach ($columns as $column) {
            if (!is_array($column)) $table_columns[] = $column;
            else $table_columns[] = implode(" ", $column);
        }
        $create_query = "CREATE TABLE `$table` (".implode(", ", $table_columns).")";

        // Add the query to the global install sql
        $this->install_sql[] = $create_query;
    }

    private function define_data($data) {
        // Loop through all of the data
        foreach ($data as $key => $val) {
            // If it's an array, recurse!
            if (is_array($val) && !empty($val)) {
                $temp[] = $this->define_data($val);
            } else {
                // Add the key/value combination to be returned
                $temp['keys'][] = "`".$key."`";
                $temp['vals'][] = "'".$this->tData->real_escape_string(htmlspecialchars($val))."'";
            }
        }

        // Return the data
        return $temp;
    }

    public function table_data($table, $data) {
        // Define the data to be sql friendly
        $data = $this->define_data($data);
        if (count($data) == 2) $data = array($data);

        // Loop through all of the data
        foreach ($data as $item) {
            // Add the query to the global install sql
            $this->install_sql[] = "INSERT INTO `$table` (".implode(", ", $item['keys']).") VALUES (".implode(", ", $item['vals']).");";
        }
    }

    public function permissions($p) {
        // Loop through all of the permissions
        foreach ($p as $item) {
            // Add the query to the global install sql
            $this->install_sql[] = "INSERT INTO `".$this->tDataClass->prefix."_permissions` (`feature`, `permission`) VALUES ('".$this->config['alias']."', '".$this->tData->real_escape_string($item)."');";
        }
    }

    public function group($alias = "", $name = "", $permissions = "", $home = "false") {
        // Check the requirements
        if ($alias == "") throw new Exception("The alias to the group being created cannot be blank.");
        if ($name == "") throw new Exception("The name to the group being created cannot be blank.");

        // Sanitize the variables to be db friendly
        $alias = $this->tData->real_escape_string($alias);
        $name = $this->tData->real_escape_string($name);
        $permissions = $this->tData->real_escape_string($permissions);
        $home = $this->tData->real_escape_string($home);

        // Create the query to add this group
        $query = "INSERT INTO `".$this->tDataClass->prefix."_groups` (`alias`, `name`, `permissions`, `permanent`, `home_override`) VALUES ('$alias', '$name', '$permissions', 0, '$home');";

        // Add the query to the global install sql
        $this->install_sql[] = $query;
    }

    public function get_install_sql() {
        return $this->install_sql;
    }
}