<?php
    require_once dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php/paths.php"; 
    $paths = new Paths(); 
?>

<?php

require_once $paths->outside_root_php . "/script_access_checker.php";
require_once $paths->outside_root_php . "/file_encrypter.php";

class XMLLoader {
    public function __construct(){
        $this->settings_loc = $this->getCurrentSettingsLoc();
        $this->checkExists($this->settings_loc);
        $this->xml_file = $this->loadXML();
        $this->tracker_test_true_val;
        $this->tracker_test_false_val;
        $this->non_western_block_true_val;
        $this->non_western_block_false_val;
        $this->getTrackerTestValue();
        $this->getWesternBlockValue();
    }
    public function getCurrentSettingsLoc(){
        return $GLOBALS["paths"]->forms . "/" . $GLOBALS["form_scanner"]->folders[0] . "/settings.xml";
    }
    public function checkExists($file){
        if (!file_exists($file)){
            file_put_contents($this->settings_loc, "");
        }
    }
    public function loadXML(){
        $xml_file = file_get_contents($this->settings_loc);
        //$xml_file = FileEncrypter::decrypt($xml_file);
        return simplexml_load_string($xml_file);
    }
    private function getTrackerTestValue(){
        if ($this->xml_file->tracker_test == "on"){
            $this->tracker_test_true_val = "checked";
        }
        if ($this->xml_file->tracker_test == "off"){
            $this->tracker_test_false_val = "checked";
        }
    }
    private function getWesternBlockValue(){
        if ($this->xml_file->non_western_country_ban == "on"){
            $this->non_western_block_true_val = "checked";
        }
        if ($this->xml_file->non_western_country_ban == "off"){
            $this->non_western_block_false_val = "checked";
        }
    }
}