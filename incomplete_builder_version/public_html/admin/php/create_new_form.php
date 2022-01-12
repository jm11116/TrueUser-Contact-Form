<?php
    require_once dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php/paths.php"; 
    $paths = new Paths(); 
?>

<?php 

//require_once "script_access_checker.php"; //Reenable!
require_once $paths->admin_php . "/form_scanner.php";

if ($_SERVER["REQUEST_METHOD"] === "POST"){

class CreateNewForm {
    public function __construct(){
        $this->name = htmlspecialchars($_POST["name"]);
        $this->form_folder_location = $GLOBALS["paths"]->forms . "/";
        $this->folder_name = strtolower(str_replace(" ", "_", $this->name)) . "_" . rand(0, 9999999);
        $this->checkExists();
        $this->createFolder();
    }
    private function checkExists(){
        $folders = FormScanner::getFoldersArray();
        if (in_array($this->folder_name, $folders)){
            $this->folder_name = $this->name . "_" . rand(0, 9999999);
        }
    }
    private function createFolder(){
        $existing_names = $GLOBALS["form_scanner"]->getFormNames();
        if (in_array($this->name, $existing_names)){
            echo $this->name . " already exists! Please choosen another name.";
        } else {
            mkdir($this->form_folder_location . $this->folder_name);
            file_put_contents($this->form_folder_location . $this->folder_name . "/index.php", file_get_contents($GLOBALS["paths"]->templates . "/index.php"));
            file_put_contents($this->form_folder_location . $this->folder_name . "/form.txt", file_get_contents($GLOBALS["paths"]->templates . "/form.txt"));
            file_put_contents($this->form_folder_location . $this->folder_name . "/form.json", file_get_contents($GLOBALS["paths"]->templates . "/form.json"));
            file_put_contents($this->form_folder_location . $this->folder_name . "/settings.xml", file_get_contents($GLOBALS["paths"]->templates . "/settings.xml"));
            file_put_contents($this->form_folder_location . $this->folder_name . "/name.txt", $this->name);
            file_put_contents($this->form_folder_location . $this->folder_name . "/id.txt", $this->folder_name);
            echo $this->name . " has been created!";
        }
    }
}

} else {
    die("Access forbidden!");
}

$create_new_form = new CreateNewForm();