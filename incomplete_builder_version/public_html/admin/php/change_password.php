<?php
    require_once dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php/paths.php"; 
    $paths = new Paths(); 
?>

<?php 

require_once "script_access_checker.php"; //Reenable!
require_once $paths->admin_php . "/form_scanner.php";
require_once $paths->admin_php . "/date_time_getter.php";

class PasswordChanger {
    public function __construct(){
        $this->hash = dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/user.php";
        $this->hash_contents;
        $this->password_file = dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/user.php";
        $this->old_password = htmlspecialchars($_POST["old_password"]);
        $this->new_password = htmlspecialchars($_POST["new_password"]);
        $this->confirmed_password = htmlspecialchars($_POST["new_password_confirm"]);
        $this->log_file = $GLOBALS["paths"]->logs . "/log.txt";
        if (!is_writable($GLOBALS["paths"]->logs)){
            die("NOPE!"); //Directory not writable for logs?
        }
        $this->checkExists();
    }
    private function checkExists(){
        if (!file_exists($this->hash)){
            $this->changePassword(false); //No need to verify old passworfd
        } else {
            $this->hash_contents = file_get_contents($this->hash);
            $this->changePassword(true);
        }
    }
    private function changePassword($verify){
        if (!$this->verifyOldPassword() && $verify === true){
            die("Old password is incorrect. Please try again.");
        } else if (!$this->passwordRuleCheck($this->new_password)){
            die("Your password must include at least one upper case letter, one lower case letter, one number, and be a minimum of eight characters long.");
        } else if (!$this->checkNewPasswordMatch()){
            die("New passwords do not match!");
        } else {
            $this->storeNewPassword();
        }
    }
    private function verifyOldPassword(){
        if (password_verify($this->old_password, $this->hash_contents)){
            return true;
        } else {
            return false;
        }
    }
    private function passwordRuleCheck($password){
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
            return false;
        } else {
            return true;
        }
    }
    private function checkNewPasswordMatch(){
        if ($this->new_password === $this->confirmed_password){
            return true;
        } else {
            return false;
        }
    }
    private function storeNewPassword(){
        $new_hash = password_hash($this->new_password, PASSWORD_DEFAULT);
        file_put_contents($this->hash, $new_hash);
        $this->logAttempt("password_change");
        echo "Your password has been successfully changed!";
    }
    private function logAttempt($type){
        if ($type === "password_change"){
            $data = "Password changed by: " . $_SERVER["REMOTE_ADDR"];
            $data .= " at " . $GLOBALS["date_time_getter"]->time . $GLOBALS["date_time_getter"]->date;
        }
        if (!file_exists($this->log_file)){
            file_put_contents($this->log_file, $data);
        } else {
            $existing = file_get_contents($this->log_file);
            $file = fopen($this->log_file, "a+");
            fwrite($file, $data);
            fwrite($file, $existing);
            fclose($file);
        }
    }
    private function emailAboutPasswordChange(){

    }
}

new PasswordChanger();

?>