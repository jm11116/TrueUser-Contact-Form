<?php

class AttemptChecker { //Need to encrypt IP logs
    public function __construct(){
        $this->log_folder = dirname(__DIR__, 2) . "/ip_logs/";
        $this->settings_loc = dirname(__DIR__, 2) . "/settings.xml";
        $this->settings = /*FileEncrypter::decrypt(*/file_get_contents($this->settings_loc)/*)*/;
        $this->settings = simplexml_load_string($this->settings);
    }
    public function getFilename($type){
        $timezone = "Australia/Sydney"; //Get this from XML
        $time_obj = new DateTime("now", new DateTimeZone($timezone));
        $date = $time_obj->format("D d M Y");
        if ($type === "mail"){
            $filename = $this->log_folder . $date . "_mails.txt";
        } else if ($type === "code"){
            $filename = $this->log_folder . $date . "_attempts.txt";
        }
        return strtolower(str_replace(" ", "_", $filename));
    }
    private function checkLogFolder(){
        if (!is_dir($this->log_folder)){
           mkdir($this->log_folder); 
        }
    }
    public function logAttempt($email, $type){
        $this->checkLogFolder();
        if ($type === "mail"){
            $today_file = $this->getFilename("mail");
        } else if ($type === "code"){
            $today_file = $this->getFilename("code");
        }
        $new_entry = "\n" . $_SERVER["REMOTE_ADDR"] . " " . $email . " " . time();
        if (!file_exists($today_file)){
            file_put_contents($today_file, $new_entry);
        } else {
            $existing = file_get_contents($today_file);
            $file = fopen($today_file, "r+");
            fwrite($file, $new_entry);
            fwrite($file, $existing);
            fclose($file);
        }
    }
    public function maxMailsReached(){
        $max = $this->settings->max_mails_per_day;
        $today_file = "contact_form/" . $this->getFilename("mail");
        if (file_exists($today_file)){
            $count = substr_count(file_get_contents($today_file), $_SERVER["REMOTE_ADDR"] . " ");
            if ($count >= $max){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function checkIfTooManyTotalVerificationCodes($to){
        $max = $this->settings->max_codes_per_day;
        $today_file = $this->getFilename("code");
        if (file_exists($today_file)){
            $count = substr_count(file_get_contents($today_file), $to);
            if ($count >= $max){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

$attempt_checker = new AttemptChecker();

?>