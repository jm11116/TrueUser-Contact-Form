<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){

session_start();

require_once dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php/paths.php"; 
$paths = new Paths();

require $paths->admin_php . "/txt_to_json.php";
require $paths->admin_php . "/generate_form.php";

class SaveForm {
    public function __construct(){
        $this->setCurrentFormSessionVars();
        $this->form_text = $_POST["form_text"];
        if (file_put_contents($_SESSION["current_form_txt_loc"], $this->form_text)){
            new TextToJSON();
            new FormEcho();
            echo "Your form has been updated!";
        } else {
            die("Could not write new form data!");
        }
    }
    private function setCurrentFormSessionVars(){
        $_SESSION["current_folder"] = $_POST["current_folder"];
        $_SESSION["current_form_html_loc"] = dirname(__DIR__, 1) . "/forms/" . htmlspecialchars($_POST["current_folder"]) . "/form.html";
        $_SESSION["current_form_txt_loc"] = dirname(__DIR__, 1) . "/forms/" . htmlspecialchars($_POST["current_folder"]) . "/form.txt";
        $_SESSION["current_form_json_loc"] = dirname(__DIR__, 1) . "/forms/" . htmlspecialchars($_POST["current_folder"]) . "/form.json";
    }
}

$save_form = new SaveForm();

}

?>