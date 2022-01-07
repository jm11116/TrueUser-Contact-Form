<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST"){ //Use a key here

require_once "file_encrypter.php";
require_once "mailer.php";
require_once "tester.php";
require_once "user_checker.php";
require_once "attempt_checker.php";

class Verifier {
    public function __construct(){
        session_start();
        $_SESSION["form_data"];
        $this->settings_loc = dirname(__DIR__, 2) . "/settings.xml";
        $this->settings = file_get_contents($this->settings_loc);
        $this->settings = simplexml_load_string($this->settings);
        $this->to_email = htmlspecialchars($this->settings->to_email);
        $this->getRequestType();
        $this->getMessage();
    }
    private function getRequestType(){
        if ($_POST["request"] === "get_code"){
            $this->saveFormData();
            $this->genOrReplaceOldCode();
        } else if ($_POST["request"] === "verify"){
            $this->verify();
        } else if ($_POST["request"] === "resend"){
            $this->genOrReplaceOldCode();
        } else {
            die("ERROR: Invalid request type.");
        }
    }
    private function saveFormData(){
        if (isset($_POST["form_data"]) && isset($_POST["labels"])){
            parse_str($_POST["form_data"], $_SESSION["form_data"]);
            $_SESSION["labels"] = $_POST["labels"];
            $_SESSION["field_names"] = $_POST["field_names"];
        }
    }
    private function getMessage(){
        $message = "";
        for ($i = 0; $i <= (count(array_values($_SESSION["labels"])) - 1); $i++){
            $message .= htmlspecialchars($_SESSION["labels"][$i]) . ": ";
            if ($_SESSION["field_names"][$i] === "email"){
                $message .= htmlspecialchars(strtolower($_SESSION["form_data"][$_SESSION["field_names"][$i]])) . "\n";
            } else {
                $message .= htmlspecialchars($_SESSION["form_data"][$_SESSION["field_names"][$i]]) . "\n";
            }
        }
        return $message;
    }
    private function genOrReplaceOldCode(){
        $_SESSION["user_email"] = $_POST["email"];
        $to = htmlspecialchars(strtolower($_SESSION["user_email"]));
        if (!$GLOBALS["attempt_checker"]->checkIfTooManyTotalVerificationCodes($to)){
            if (!$GLOBALS["user_checker"]->tooSoonSinceLastAttempt() && $_SESSION["attempts"] >= $this->settings->attempts_until_timeout){
                $secs_to_wait = $GLOBALS["user_checker"]->calcTimeToWait();
                die("Too many verification attempts. Please wait " . $secs_to_wait . " seconds to try again.");
            }
            $code = rand(10000, 99999) . " " . time();
            $_SESSION["code"] = $code; //Current user verification code
            $_SESSION["to"] = $to; //So resend knows where to resend code.
            $subject = "Your verification code from " . htmlspecialchars($_POST["website"]);
            $message = "Your verification code is: " . explode(" ", $code)[0] . " and was sent from IP address: " . $_SERVER["REMOTE_ADDR"] .
                            "\nGet IP information here: " . 
                                "http://www.scamalytics.com/ip/" . $_SERVER["REMOTE_ADDR"];
            $from = "From: alerts@" . htmlspecialchars($this->settings->from);
            echo "Your verification code has been sent to " . $to . ". Please check your inbox. This code will expire in " . $this->settings->code_expiry_mins . " minute/s.";
            $GLOBALS["attempt_checker"]->logAttempt($to, "code");
            $GLOBALS["user_checker"]->trackLastAttempt();
            Tester::getTestCode($to, $subject, $message, $from);
            //$mailer->sendVerifyEmail($to, $subject, $message, $from);
        } else {
            die("Too many verification codes sent to this email address. Please try again later.");
        }
    }
    private function verify(){
        $attempt_code = htmlspecialchars($_POST["verification_code"]);
        $current_time = time();
        $server_code = explode(" ", $_SESSION["code"])[0];
        $code_send_time = explode(" ", $_SESSION["code"])[1];
        if (($current_time - $code_send_time) > $this->settings->code_expiry_mins * 60){
            die("Your verification code has expired. Please try resending your verification code.");
        } else if ($attempt_code === $server_code){
            $this->formMailer();
        } else {
            die("Verification code is incorrect. Please try again.");
        }
    }
    private function getHeaders(){
        $headers = array(
        'From: "New message from ' . htmlspecialchars($this->settings->domain) . '" <mailer@' . htmlspecialchars($this->settings->to_email) . '>' ,
        'Reply-To: "'. htmlspecialchars($_SESSION["form_data"]["name"]) .'" <' . htmlspecialchars(strtolower(strtolower($_SESSION["user_email"]))) . '>' ,
        'X-Mailer: PHP/' . phpversion() ,
        'MIME-Version: 1.0' ,
        'Content-type: text/html; charset=iso-8859-1' ,
        );
        return $headers = implode("\r\n", $headers);
    }
    private function formMailer(){
        $subject = "New message from " . htmlspecialchars($this->settings->domain);
        $message = htmlspecialchars($this->getMessage()) . "\n" .
                   "User information: " . "\n" .
                   "IP address: " . $_SERVER["REMOTE_ADDR"] . "\n" .
                   "IP info: " . "http://www.scamalytics.com/ip/" . $_SERVER["REMOTE_ADDR"] . "\n" .
                   "Time from visitor's system clock: " . 
                        htmlspecialchars($_SESSION["form_data"]["time_field"]) . "\n" .
                   "JavaScript tracking scripts: " . htmlspecialchars($_SESSION["form_data"]["trackers"]) . "\n" .
                   "Mouse moved: " . htmlspecialchars($_SESSION["form_data"]["mouse"]) . "\n" .
                   "Pixels scrolled: " . htmlspecialchars($_SESSION["form_data"]["scroll"]) . "\n" .
                   "Keys pressed: " . htmlspecialchars($_SESSION["form_data"]["keys"]) . "\n" .
                   "Visitor active time: " . htmlspecialchars($_SESSION["form_data"]["active_time"]) . "\n" .
                   "Visitor's screen size: " . 
                        htmlspecialchars($_SESSION["form_data"]["s_width"]) . "px wide, " .
                            htmlspecialchars($_SESSION["form_data"]["s_height"]) . "px long" . "\n" .
                    "Visitor's PC core count: " . htmlspecialchars($_SESSION["form_data"]["cores"]) . "\n" .
                    "User's screen color depth: " . htmlspecialchars($_SESSION["form_data"]["c_depth"]) . "\n" .
                    "User's screen pixel depth: " . htmlspecialchars($_SESSION["form_data"]["p_depth"]) . "\n" .
                    "Cookie value: " . htmlspecialchars($_SESSION["form_data"]["cookie_value"]) . "\n" .
                    "Verification attempts from IP today: " . $GLOBALS["user_checker"]->getIPAttemptsToday() . "\n" .
                    "User agent string: " . htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
        $headers = $this->getHeaders();
        Tester::getTestEmail($this->to_email, $subject, $message, $headers);
        $GLOBALS["attempt_checker"]->logAttempt(htmlspecialchars(strtolower($_SESSION["user_email"])), "mail");
        $_SESSION["last_mail_sent_time"] = time();
        unset($_SESSION["attempts"]);
        //$mailer->sendEmail($this->to, $subject, $message, $headers);
    }

}

$verifier = new Verifier();

//Search email account for unique cookie values to see which ones are linked

}

?>