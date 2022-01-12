<?php

if ($_SERVER["REQUEST_METHOD"] === "POST"){

class FormEcho {
    public function __construct(){
        $this->filename = $_SESSION["current_form_html_loc"];
        $this->file;
        $this->getFile();
        $this->json = json_decode(file_get_contents($_SESSION["current_form_json_loc"]), true);
        $this->echoFieldNames();
        $this->echoLabelNames();
        $this->echoForm();
        $this->appendComment();
        fclose($this->file);
    }
    private function getFile(){
        file_put_contents($this->filename, "");
        $this->file = fopen($this->filename, "a+");
    }
    private function echoFieldNames(){
        $data = '<script>';
        $data .= 'var field_names = [';
        foreach ($this->json as $field){
            if ($field["type"] === "checkbox" && !isset($field["heading"]) && !isset($field["divider"]) && !isset($field["spacer"])){
                foreach ($field["choices"] as $choice){
                    $data .= '"' . strtolower($choice["label"]);
                    $data .= '", ';
                }
            } else if ($field["type"] !== "checkbox"  && !isset($field["heading"]) && !isset($field["divider"]) && !isset($field["spacer"])){
                $data .= '"' . strtolower($field["name"]);
                $data .= '", ';
            }
        }
        $data .= '];';
        $data .= '</script>';
        $data .= "\n\n";
        $data = str_replace(", ]", "]", $data);
        fwrite($this->file, $data);
    }
    private function echoDivider(){
        $data = '<div class="col-md-12 mb-3">';
        $data .= "<hr class='form-divider'>";
        $data .= '</div>';
        $data .= "\n\n";
        fwrite($this->file, $data);
    }
    private function echoSpacer($height){
        $data = '<div style="height:' . $height . 'px;"></div>';
        $data .= "\n\n";
        fwrite($this->file, $data);  
    }
    private function echoHeading($heading){
        $data = '<div class="col-md-12 mb-3">';
        $data .= "<h3 class='form-heading'>" . $heading . "</h3>";
        $data .= '</div>';
        $data .= "\n\n";
        fwrite($this->file, $data);
    }
    private function echoLabelNames(){
        $data = '<script>';
        $data .= 'var field_labels = [';
        foreach ($this->json as $field){
            if ($field["type"] === "checkbox" && !isset($field["heading"]) && !isset($field["divider"]) && !isset($field["spacer"])){
                foreach ($field["choices"] as $label){
                    $data .= '"' . ucwords($label["name"]);
                    $data .= '", ';
                }
            } else if ($field["type"] !== "checkbox" && !isset($field["heading"]) && !isset($field["divider"]) && !isset($field["spacer"])) {
                $data .= '"' . ucwords($field["name"]);
                $data .= '", ';
            }
        }
        $data .= '];';
        $data .= '</script>';
        $data .= "\n\n";
        $data = str_replace(", ]", "]", $data);
        fwrite($this->file, $data);
    }
    private function echoForm(){
        foreach ($this->json as $field){
            if (isset($field["heading"])){
                $this->echoHeading($field["heading"]);
            } else if (isset($field["divider"])){
                $this->echoDivider();
            } else if (isset($field["spacer"])){
                $this->echoSpacer($field["spacer"]); //Sends percentage to function. Need to validate or have default!
            } else {
                if ($field["type"] === "input" || $field["type"] === "email"){
                    if ($field["required"] === "false"){
                        $this->echoTextInput($field["label"], $field["name"], false);
                    } else if ($field["required"] === "true"){
                        $this->echoTextInput($field["label"], $field["name"], true);
                    }
                } else if ($field["type"] === "textarea"){
                    if ($field["required"] === "false"){
                        $this->echoTextArea($field["label"], $field["name"], false);
                    } else if ($field["required"] === "true"){
                        $this->echoTextArea($field["label"], $field["name"], true);
                    }
                } else if ($field["type"] === "radio"){
                    $this->echoRadioButtons($field["name"], $field["label"], $field["choices"]);
                } else if ($field["type"] === "checkbox"){
                    $this->echoCheckBoxes($field["name"], $field["label"], $field["choices"]);
                }
            }
        }
    }
    private function echoTextInput($label, $name, $required){
        if ($required == true){
            $label .= " *";
        }
        $data = '<div class="col-md-12 mb-3">';
        $data .= "\n";
        $data .= '<label for="' . $name . '" class="form-label">' . $label . '</label>';
        $data .= "\n";
        $data .= '<input name="' . $name . '" class="form-control" value="" disabled autocomplete="off"';
        if ($required === true){
            $data .= ' required';
        }
        $data .= '></input>';
        $data .= "\n";
        $data .= '</div>';
        $data .= "\n\n";
        fwrite($this->file, $data);
    }
    private function echoTextArea($label, $name, $required){
        if ($required == true){
            $label .= " *";
        }
        $data = '<div class="col-md-12 mb-3">';
        $data .= "\n";
        $data .= '<label for="' . $name . '" class="form-label">' . $label . '</label>';
        $data .= "\n";
        $data .= '<textarea name="' . $name . '" class="form-control" value="" rows="4" disabled autocomplete="off"';
        if ($required == true){
            $data .= " required";
        }
        $data .= '></textarea>';
        $data .= "\n";
        $data .= '</div>';
        $data .= "\n\n";
        fwrite($this->file, $data);
    }
    private function echoRadioButtons($name, $label, $choices){
        $data = '<div class="col-md-12 mb-3">';
        $data .= "\n";
        $data .= '<label for="' . $name . '" class="form-label">' . $label . '</label><br>';
        foreach ($choices as $choice){
            $data .= "\n";
            $data .= '<input type="radio" name="' . $name . '" value="' . $choice["value"]  . '" disabled required';
            $data .= '>';
            $data .= '<label for="' . $choice["value"] . '">  ' . $choice["label"] . '</label><br>';
        }
        $data .= "\n";
        $data .= '</div>';
        $data .= "\n\n";
        fwrite($this->file, $data);
    }
    private function echoCheckBoxes($name, $label, $choices){
        $data = '<div class="col-md-12 mb-3">';
        $data .= "\n";
        $data .= '<label for="' . $name . '" class="form-label">' . $label . '</label><br>';
        foreach ($choices as $choice){
            $data .= "\n";
            $data .= '<input type="checkbox" name="' . $choice["name"] . '" value="' . $choice["value"]  . '" disabled>';
            $data .= '<label for="' . $choice["value"] . '">  ' . $choice["label"] . '</label><br>';
        }
        $data .= "\n";
        $data .= '</div>';
        $data .= "\n\n";
        fwrite($this->file, $data);
    }
    private function appendComment(){
        $data = "\n";
        $data .= "<!--Submit button appended inside security.js so can't be submitted from form.html-->";
        fwrite($this->file, $data);
    }
}

} else {
    die("Access forbidden!");
}

?>