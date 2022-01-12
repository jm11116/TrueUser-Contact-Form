<?php 

require_once "file_encrypter.php";

class UserChecker {
    public function __construct(){
        $this->fake_code_loc = dirname(__DIR__, 1) . "/txt/fake_code.txt";
        $this->settings_loc = dirname(__DIR__, 2) . "/settings114332554123345jk.xml";
        $this->settings = simplexml_load_file($this->settings_loc);
        $this->malicious_ips = [];
        $this->getMaliciousIPs();
        $this->checkIfBadIP();
    }
    private function getMaliciousIPs(){
        $bad_ips = $this->settings->bad_ips;
        if (strpos(",", $bad_ips) != false){
            $ips = explode(",", trim($this->settings->bad_ips));
            foreach ($ips as $ip){
                array_push($this->malicious_ips, $ip);
            }
        } else {
            $this->malicious_ips[0] = trim($bad_ips);
        }
    }
    private function checkIfBadIP(){
        if (in_array($_SERVER["REMOTE_ADDR"], $this->malicious_ips)){
            die('<!DOCTYPE html><head><style>@font-face{font-family: crash;src: url(assets/dos.ttf);}body{text-align:justify;font-family:crash;font-size:1.4em;background-color:black;color:#d9d9d9;}</style></head><body>' . file_get_contents($this->fake_code_loc) . file_get_contents($this->fake_code_loc) . file_get_contents($this->fake_code_loc) . '<script src="scripts/js/crasher.js"></script></body></html>');
        }
    }
    public function trackLastAttempt(){
        if (!isset($_SESSION["attempts"])){
            $_SESSION["attempts"] = 1;
        } else {
            $_SESSION["attempts"]++;
        }
        $_SESSION["last_attempt"] = time();
        $_SESSION["next_attempt"] = time() + $this->settings->code_timeout_secs;
    }
    public function tooSoonSinceLastAttempt(){
        $timeout = $this->settings->code_timeout_secs;
        if ((time() - $_SESSION["last_attempt"]) <= $timeout){
            return false;
        } else {
            return true;
        }
    }
    public function calcTimeToWait(){
        return ($_SESSION["next_attempt"] - time()) 
            * 
        ($_SESSION["attempts"] - ($this->settings->attempts_until_timeout / 2));
    }
    public function getIPAttemptsToday(){
        $today_file = $GLOBALS["attempt_checker"]->getFilename("code");
        if (file_exists($today_file)){
            return $count = substr_count(file_get_contents($today_file), $_SERVER["REMOTE_ADDR"]);
        } else {
            return 0; //Should this really return zero?
        }
    }
}

$user_checker = new UserChecker();

?>