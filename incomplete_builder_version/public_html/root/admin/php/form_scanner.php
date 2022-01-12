<?php

class FormScanner {
    public function __construct(){
        $this->form_locations = dirname(__DIR__, 1) . "/forms/";
        $this->folders = $this->getFoldersArray();
        $this->number_of_folders = $this->getFolderNumbers();
        $this->createFormIfNone();
        $this->form_names = $this->getFormNames();
        if ($_POST["request"] === "refresh_forms_dropdown"){
            $this->echoFormNamesAsNav();
        }
    }
    static function getFoldersArray(){
        $form_locations = dirname(__DIR__, 1) . "/forms/";
        $raw_folders = scandir($form_locations);
        $folders = [];
        foreach ($raw_folders as $folder){
            if ($folder[0] !== "."){
                if (is_dir($form_locations . $folder)){
                    array_push($folders, $folder);
                };
            }
        }
        return $folders;
    }
    public function getFolderNumbers(){
        $number = 0;
        foreach ($this->folders as $folder){
            $number++;
        }
        return $number;
    }
    private function copyFolderToDest($src, $dst){
        $dir = opendir($src);
        @mkdir($dst); 
        while( $file = readdir($dir) ) { 
            if (($file != '.') && ( $file != '..' )){ 
                if ( is_dir($src . '/' . $file)){
                    $this->copyFolderToDest($src . '/' . $file, $dst . '/' . $file); 

                } else { 
                    copy($src . '/' . $file, $dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir);
    }
    private function createFormIfNone(){
        if ($this->getFolderNumbers() === 0){
            $this->copyFolderToDest("templates/example_9786588", 
                dirname(__DIR__, 1) . "/forms/example_9786588/");
        }
    }
    public function getFormNames(){
        $names = [];
        foreach ($this->folders as $folder){
            $name_location = $this->form_locations . $folder . "/name.txt";
            if (file_exists($name_location)){
                $name = file_get_contents($name_location);
                array_push($names, $name);
            } else {
                return false;
            }
        }
        return $names;
    }
    public function echoFormNamesAsNav(){
        $i = 0;
        foreach ($this->form_names as $name){
            echo '<a class="dropdown-item form-list-item" href="" data-path="'. $this->form_locations . $this->folders[$i] . '" data-folder="' . $this->folders[$i] . '"data-name="' . file_get_contents($this->form_locations . $this->folders[$i] . "/name.txt") . '">' . htmlspecialchars($name)  . '</a>';
            $i++;
        }
    }
    public function echoFirstFormAsJSVar(){
        if (!$this->getFormNames()){
            return "<script>var currently_editing = 'example_2221345';</script>";
        } else {
            return "<script>var currently_editing = '" . $this->folders[0] . "';</script>";
        }
    }
    public function echoFirstFormName(){
        echo $this->form_names[0];
    }
    public function getFormURL(){
        return $url = str_replace("admin/application.php", "", htmlspecialchars($_SERVER["PHP_SELF"]));
    }
    public function getFormURLPartJavaScript(){
        $url = "<script> var php_self = '";
        $url .= str_replace("admin/application.php", "", htmlspecialchars($_SERVER["PHP_SELF"]));
        $url .= "';</script>";
        return $url;
    }
}

$form_scanner = new FormScanner();

?>