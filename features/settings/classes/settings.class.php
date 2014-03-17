<?php

class Settings {
    public function __construct() {
        $this->initialize();
        return;
    }

    public function __destruct() {
        $this->tDataClass->disconnect();
        return;
    }

    private function initialize() {
        $this->tDataClass = new tData();
        $this->tData = $this->tDataClass->connect();
        $this->tDataClass->prefix = $this->tDataClass->get_system_prefix();
        $this->tFiles = new tFiles();
        return;
    }

    private function get_system_home() {
        $q = $this->tData->query("SELECT `home` FROM `".$this->tDataClass->prefix."_settings`");
        if (!$q) throw new Exception("Cannot find the home page in the settings table.");
        if ($q->num_rows == 0) throw new Exception("There is no home page column in the settings table.");
        $r = $q->fetch_assoc();
        return $r['home'];
    }

    private function decode_home() {
        $d = $this->tDataClass->t_decode($this->get_system_home());
        if ($d[0] != "homepage") throw new Exception("Invalid home page information.");
        else return $d;
    }

    private function get_db_rows($w, $id = false) {
        $where = $id == false ? "" : "WHERE `id`='$id'";
        $q = $this->tData->query("SELECT * FROM `".$this->tDataClass->prefix."_$w` $where");
        if (!$q) throw new Exception("Error querying database for $w.");
        if ($q->num_rows == 0) throw new Exception("There are no $w to show.");
        while ($row = $q->fetch_assoc()) $ret[] = $row;
        return $ret;
    }

    private function get_system_info() {
        $q = $this->tData->query("SELECT * FROM `".$this->tDataClass->prefix."_settings`");
        if (!$q) throw new Exception("There was an issue querying the database for the custom settings");
        return $q->fetch_assoc();
    }

    public function get_home_info() {
        return $this->decode_home();
    }

    public function get_pages_select($h) {
        $id = $h['type'] == "page" ? $h['id'] : 0;
        try { $ps = $this->get_db_rows("pages"); }
        catch (Exception $ex) { echo $ex->getMessage(); }

        $ret[] = "<label class='admin-selectlabel'><select name='page-id'>";
        foreach ($ps as $p) {
            $s = $p['id'] == $id ? "selected" : "";
            $ret[] = "<option value='".$p['id']."' $s>".$p['title']."</option>";
        }
        $ret[] = "</select></label>";
        return implode("", $ret);
    }

    public function get_features_select($h) {
        $id = $h['type'] == "feature" ? $h['id'] : 0;
        try { $fs = $this->get_db_rows("features"); }
        catch (Exception $ex) { echo $ex->getMessage(); }

        $ret[] = "<label class='admin-selectlabel'><select name='feature-id'>";
        foreach ($fs as $f) {
            $s = $f['id'] == $id ? "selected" : "";
            $ret[] = "<option value='".$f['id']."' $s>".$f['name']."</option>";
        }
        $ret[] = "</select></label>";
        return implode("", $ret);
    }

    public function get_feature_files_select($id) {
        $h = $this->decode_home();
        try { $f = $this->get_db_rows("features", $id); }
        catch (Exception $ex) { echo $ex->getMessage(); }
        $path = path(ROOT."/features/".$f[0]['alias']."/views");
        $files = $this->tFiles->scan_folder($path, $path);
        $ret[] = "<label class='admin-selectlabel'><select name='feature-file'>";
        foreach ($files as $f) {
            $name = ucwords(str_replace(".php", "", str_replace("/", " / ", str_replace("_", " ", str_replace("-", " ", $f)))));
            if (array_key_exists("file", $h) && $h['file'] != "") $s = $h['file'].".php" == $f ? "selected" : "";
            elseif ($f == "index.php") $s = "selected";
            else $s = "";

            $ret[] = "<option value='".trim($f, ".php")."' $s>$name</option>";
        }
        $ret[] = "</select></label>";
        return implode("", $ret);
    }

    public function get_site_name() {
        $info = $this->get_system_info();
        return urldecode($info['name']);
    }

    public function get_session_value($h, $ba) {
        $ret[] = "$ba-type=\"".$h[$ba.'-type']."\";";
        if ($h[$ba.'-type'] == "page") $ret[] = "$ba-id=\"".$h[$ba.'-id']."\";";
        if ($h[$ba.'-type'] == "feature") {
            $ret[] = "$ba-id=\"".$h[$ba.'-id']."\";";
            $ret[] = "$ba-file=\"".$h[$ba.'-file']."\";";
        }
        if ($h[$ba.'-type'] == "url") $ret[] = "$ba-id=\"".$h[$ba.'-url']."\";";
        return implode("", $ret);
    }

    public function save_customization() {
        $post = filter_input_array(INPUT_POST);

        if (!isset($post['name']) || $post['name'] == "") throw new Exception("Please fill out the 'Site Name' field.");
        else $name = $this->tData->real_escape_string(urldecode($post['name']));

        if (!isset($post['home-page']) || $post['home-page'] == "") throw new Exception("Please choose a home page.");
        else $home = $this->tData->real_escape_string(urldecode($post['home-page']));

        $q = $this->tData->query("UPDATE `".$this->tDataClass->prefix."_settings` SET `name`='$name', `home`='$home'");
        if (!$q) throw new Exception("There was an error updating the settings database.");
        else return true;
    }
}