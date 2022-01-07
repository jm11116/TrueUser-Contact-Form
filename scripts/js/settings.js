class Settings {
    constructor(){
        this.settings_xml;
        this.website;
        this.tracker_test;
        this.form_id;
        this.field_names = [];
        this.field_labels = [];
        this.loadXML();
    }
    loadXML(){
        $.ajax({
            url: "settings.xml",
            type: "GET",
            async: false, //Defers rest of scripts until the XML has been loaded and parsed.
            cache: false,
            context: this,
            dataType: "xml",
            error: function(){
                alert("An error has occurred. Could not load XML file.");
            },
            success: function(xml){
                this.xml = xml;
                this.getSettings();
            }
        });  
    }
    getSettings(){
        this.website = "www." + $(this.xml).find("domain").text();
        this.tracker_test = $(this.xml).find("tracker_test").text();
        this.form_id = $(this.xml).find("form_id").text();
        $(this.xml).find("field_names").text().split(",").forEach((name) => {
            this.field_names.push(name.trim());
        });
        $(this.xml).find("field_labels").text().split(",").forEach((name) => {
            this.field_labels.push(name.trim());
        });
    }
}

var settings = new Settings();