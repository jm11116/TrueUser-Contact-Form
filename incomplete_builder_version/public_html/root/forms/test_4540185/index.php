<?php 

$GLOBALS["form_folder_name"] = file_get_contents("id.txt");
$GLOBALS["form_folder_path"] = dirname(__DIR__, 2) . "/forms/" . $GLOBALS["form_folder_name"];
$GLOBALS["form_json_path"] = dirname(__DIR__, 2) . "/forms/" . $GLOBALS["form_folder_name"] . "/form.json";
$GLOBALS["form_settings_path"] = dirname(__DIR__, 2) . "/forms/" . $GLOBALS["form_folder_name"] . "/settings.xml";

include dirname(__DIR__, 2) . "/index.php";

?>