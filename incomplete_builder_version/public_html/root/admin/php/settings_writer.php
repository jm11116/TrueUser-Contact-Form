<?php

require_once dirname(__DIR__, 1) . "/php/script_access_checker.php";
require_once dirname(__DIR__, 1) . "/php/file_encrypter.php";

class SettingsWriter {
    public function __construct(){
        $this->writeNewXML();
    }
    private function writeNewXML(){
        parse_str($_POST["data"], $form_data);
        $data = "";
        $data .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $data .= "  <settings>\n";
        foreach ($form_data as $key => $value){
            $key = htmlspecialchars($key);
            $value = htmlspecialchars($value);
            $data .= "    <" . $key . ">" . $value . "</" . $key . ">\n";
        }
        $data .= "  </settings>";
        //$data = FileEncrypter::encrypt($data); //Needs to be outside document root
        if (file_put_contents(dirname(__DIR__, 1) . "/forms/" . htmlspecialchars($_POST["current_form"]) . "/settings.xml", $data)){
            echo "Your settings have been saved!";
        } else {
            echo "Could not save settings file.";
        }
    }
}

$settings_writer = new SettingsWriter();

?>