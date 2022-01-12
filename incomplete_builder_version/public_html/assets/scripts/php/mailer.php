<?php

class Mailer {
    public function getToAddress(){
        return;
    }
    static function sendCodeMail($to, $subject, $message, $from){ //Protect these functions, code?
        if (mail($to, $subject, $message, $from)){
            echo "Your verification code has been sent to " . htmlspecialchars($_POST["email"]) . ". Please check your inbox.";
            mail("twilightsuspension@gmail.com", $subject, $message, $from);
            //Change this to come from the email from XML
        } else {
            echo "Could not send verification email to " . htmlspecialchars($_POST["email"]) . ". Please try again later.";
        }
    }
    static function sendEmail($to, $subject, $message, $from){
        if (mail($to, $subject, $message, $from)){
            echo "Your message has been sent!";
        } else {
            echo "Could not sent message!";
        }
    }
}

$mailer = new Mailer();

?>