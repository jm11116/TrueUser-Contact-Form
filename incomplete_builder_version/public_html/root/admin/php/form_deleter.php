<?php

//require_once "script_access_checker.php"; //Reenable!
require_once "form_scanner.php";

if ($_SERVER["REQUEST_METHOD"] === "POST"){

class FormDeleter {
    public function __construct(){
        $this->deleteForm();
    }
    private function deleteForm(){
        if (self::deleteDir($_POST["path"])){
            echo "Form has been deleted!";
        } else {
            die("ERROR: Form could not be deleted.");
        }
    }
    public static function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        if (rmdir($dirPath)){
            return true;
        } else {
            return false;
        }
    }
}

$form_delete = new FormDeleter();

}

?>