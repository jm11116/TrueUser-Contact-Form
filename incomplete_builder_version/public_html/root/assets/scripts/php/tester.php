<?php

class Tester {
    static function getTestCode($to, $subject, $message, $from){ //Protect this
        $test_data = "To: " . $to . "\n" .
                     "Subject: " . $subject . "\n" .
                     "Message: " . $message . "\n" .
                     "From: " . $from;
        file_put_contents("code.txt", $test_data);
    }
    static function getTestEmail($to, $subject, $message, $from){
        echo "<br><br><br>Your email has been sent!";
        $test_data = "To: " . $to . "\n" .
                     "Subject: " . $subject . "\n" .
                     "Message: " . $message . "\n" .
                     "Headers: " . $from;
        file_put_contents("email.txt", $test_data);
    }
}

$tester = new Tester();

?>