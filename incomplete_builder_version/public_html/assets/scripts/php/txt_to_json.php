<?php

class TextToJSON {
    public function __construct(){
        $this->form_filename = $_SESSION["current_form_txt_loc"];
        $this->form_data = file_get_contents($this->form_filename);
        $this->fields = explode("\n\n", $this->form_data);
        $this->fields_array = $this->fieldsToArray();
    }
    private function fieldsToArray(){
        $field_count = 0;
        $fields_array = [];
        foreach ($this->fields as $field){
            $field_lines = explode("\n", $field);
            $fields_array["field" . $field_count] = NULL; //Create new numbered field
            foreach ($field_lines as $line){
                $line = str_replace("\"", "", $line);
                $line = str_replace("'", "", $line);
                $split_line = explode(":", trim($line));
                $label = strtolower(trim($split_line[0]));
                $value = trim($split_line[1]);
                if ($label === "name"){
                    $fields_array["field" . $field_count][$label] = str_replace(" ", "_", $value);
                } else {
                    $fields_array["field" . $field_count][$label] = $value;
                }
            }
            $field_count++;
        }
        file_put_contents($_SESSION["current_form_json_loc"], json_encode($fields_array));
    }
}

?>