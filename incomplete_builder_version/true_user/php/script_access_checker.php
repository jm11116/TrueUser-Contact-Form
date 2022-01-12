<?php 

class ScriptAccessChecker {
    static function checkPost(){
        if ($_SERVER["REQUEST_METHOD"] !== "POST"){
            ScriptAccessChecker::throwAccessError();
        }
    }
    static function checkScriptAccess(){
        $code = "xTfrzRTJhpLt13#@!CvccczzzssaLkiPPO0998";
        if (constant("script_access") !== $code){
            ScriptAccessChecker::throwAccessError();
        }
    }
    static function throwAccessError(){
        die("Access forbidden from IP: " . $_SERVER["REMOTE_ADDR"]);
    }
}

?>