<?php

class PasswordChecker {
    public function __construct(){
        $this->token = bin2hex(openssl_random_pseudo_bytes(16)); //Generates crytographically secure token for session.
        $this->entered_password = htmlspecialchars($_POST["password"]);
        $this->stored_password;
    }
    private function getStoredPassword(){
        return;
    }
    private function checkPassword(){
        if ($this->entered_password === $this->stored_password){
            return;
        }
    }
    private function emailLoginAlert(){

    }
}

$password_checker = new PasswordChecker();
echo $password_checker->token;

?>