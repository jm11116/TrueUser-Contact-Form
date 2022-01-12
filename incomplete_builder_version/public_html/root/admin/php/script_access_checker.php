<?php 

session_start();

class ScriptAccessChecker {
    public function __construct(){
        $this->checkAccessAll();
    }
    private function checkPost(){
        if ($_SESSION["REQUEST_METHOD"] !== "POST"){
            return false;
        } else {
            return true;
        }
    }
    private function checkScriptAccess(){
        $code = "xTfrzRTJhpLt13#@!CvccczzzssaLkiPPO0998";
        if ($_SESSION["script_access"] === $code){
            return true;
        } else {
            return false;
        }
    }
    private function throwAccessError(){
        die("Access forbidden from IP: " . $_SERVER["REMOTE_ADDR"]);
    }
    static function checkAjaxKey(){
        if ($_POST["key"] != "xFtGujikLz63@#"){
            die("Access forbidden from IP: " . $_SERVER["REMOTE_ADDR"]);
        } else {
            return true;
        }
    }
    public function checkAccessAll(){
        if ($this->checkPost() && $this->checkScriptAccess()){
            return true;
        } else {
            $this->throwAccessError();
        }
    }
}

$script_access_checker = new ScriptAccessChecker();

?>