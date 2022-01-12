<?php 

$GLOBALS["form_folder_name"] = file_get_contents("id.txt");
$GLOBALS["form_folder_path"] = "/forms/" . $GLOBALS["form_folder_name"];
$GLOBALS["form_json_path"] = "/forms/" . $GLOBALS["form_folder_name"] . "/form.json";
$GLOBALS["form_settings_path"] = "/forms/" . $GLOBALS["form_folder_name"] . "/settings.xml";

include "/base_form.php";

?>