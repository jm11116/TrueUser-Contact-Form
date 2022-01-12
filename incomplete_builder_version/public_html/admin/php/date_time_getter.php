<?php

require_once dirname($_SERVER["DOCUMENT_ROOT"], 1) . "/true_user/php/paths.php"; 
$paths = new Paths();

class DateTimeGetter {
    public function __construct(){
        $this->admin_xml = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] . "/admin/admin.xml");
        $this->timezone = $this->admin_xml->timezone;
        $this->time_obj = new DateTime("now", new DateTimeZone($this->timezone));
        $this->time = $this->time_obj->format("h:i:s A");
        $this->date = $this->time_obj->format("D d M Y");
    }
}

$date_time_getter = new DateTimeGetter();

?>