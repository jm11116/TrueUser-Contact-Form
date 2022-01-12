<?php

class Paths {
    public function __construct(){
        $this->forms = $_SERVER["DOCUMENT_ROOT"] . "/forms";
        $this->templates = $_SERVER["DOCUMENT_ROOT"] . "/admin/new_form_templates";
        $this->test_folder = $_SERVER["DOCUMENT_ROOT"] . "/test";
        $this->outside_root = dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user";
        $this->outside_root_php = dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php";
        $this->admin_php = $_SERVER["DOCUMENT_ROOT"] . "/admin/php";
        $this->form_php = $_SERVER["DOCUMENT_ROOT"] . "/assets/scripts/php";
        $this->log_folder = $_SERVER["DOCUMENT_ROOT"] . "/admin/logs";
    }
}

?>