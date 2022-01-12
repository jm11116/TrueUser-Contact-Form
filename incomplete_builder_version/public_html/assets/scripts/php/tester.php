<?php

require_once dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php/paths.php"; 
$paths = new Paths();

class Tester {
    public function __construct(){}
    static function getTestCode($to, $subject, $message, $from){ //Protect this
        $test_data = "To: " . $to . "\n" .
                     "Subject: " . $subject . "\n" .
                     "Message: " . $message . "\n" .
                     "From: " . $from;
        file_put_contents($GLOBALS["paths"]->test_folder . "/code.txt", $test_data);
    }
    static function getTestEmail($to, $subject, $message, $from){
        echo "<br><br><br>Your email has been sent!";
        $test_data = "To: " . $to . "\n" .
                     "Subject: " . $subject . "\n" .
                     "Message: " . $message . "\n" .
                     "Headers: " . $from;
        file_put_contents($GLOBALS["paths"]->test_folder . "/email.txt", $test_data);
    }
}

$tester = new Tester();

?>