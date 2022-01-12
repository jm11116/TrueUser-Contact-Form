class Application {
    constructor(){
        this.current_folder_name = currently_editing;
        this.navbarSmoothScroll();
        this.loadJSON();
        this.bindAppendButtons();
        this.convertListToText();
        this.bindCreateFormButtons();
        this.bindCreateNewFormButton();
        this.sortable();
        this.bindChangeFormButtons();
        this.bindSelectChanges();
        this.current_form_path;
        this.current_form_name;
        this.setCurrentFormVars();
        this.current_field_type = "input";
        this.loadExistingFormData();
        this.bindApplicationSubmit();
        this.createEmailFieldIfNone();
        this.bindDeleteFormButton();
        this.fixNavbarToggler();
        this.bindSecurityButtons();
    }
    notes(){
        //form_name field is useless – the random name is generated via a method now. I can't be bothered removing all references to it, though, since the field used to dictate what gets written to the text file.
        //Needs to break up into modules.
    }
    bindSecurityButtons(){
        $("#change_password_button").click(function(){
            var fields = ["old_password", "new_password", "new_password_confirm"];
            var empty = false;
            fields.forEach(function(field){
                if ($("#" + field).val() === ""){
                    empty = true;
                }
            });
            if (empty === false){
                $.ajax({
                    url: "/admin/php/change_password.php",
                    cache: false,
                    context: this,
                    method: "POST",
                    data: {
                        old_password: $("#old_password").val(),
                        new_password: $("#new_password").val(),
                        new_password_confirm: $("#new_password_confirm").val(),
                        key: "xFtGujikLz63@#"
                    },
                    error: function(){
                        alert("An error has occurred. Please try again later.");
                    },
                    success: (data) => {
                        alert(data);
                        if (data.includes("successfully")){
                            window.location.href = window.location.href + "#security";
                            //Old password not verifying and has to reload to display old password field!
                        }
                    }
                });  
            } else {
                alert("Please fill out all fields!");
            }
        });
    }
    fixNavbarToggler(){
        $(".navbar-toggler").click(function(){
            $("#navbar").collapse("toggle");
        });
    }
    loadXML(){
        $.ajax({
            url: "/forms/" + this.current_folder_name + "/settings.xml",
            type: "GET",
            cache: false,
            context: this,
            dataType: "xml",
            error: function(){
                //alert("An error has occurred. Please try again later.");
            },
            success: function(xml){
                $("input[type='text']").each(function(){
                    if ($(this).attr("name") != "share_url"){
                        var name = $(this).attr("name");
                        if ($(xml).find(name).text() !== null){ //If if the name is found in the XML
                            $("input[name='" + name + "']").val($(xml).find(name).text());
                        }
                    }
                });
                if ($(xml).find("non_western_country_ban").text() === "on"){
                    $("#non_western_ban_on").click();
                } else {
                    $("#non_western_ban_off").click();
                }
                if ($(xml).find("tracker_test").text() === "on"){
                    $("#tracker_test_on").click();
                } else {
                    $("#tracker_test_off").click();
                }
                //Needs to work with radios.
            }
        });  
    }
    setCurrentFormVars(){
        this.current_form_path = $(".form-list-item").first().attr("data-path");
        this.current_folder_name = $(".form-list-item").first().attr("data-folder");
        this.current_form_name = $(".form-list-item").first().attr("data-name");
        this.setSharingOptions();
    }
    bindChangeFormButtons(){
        $(".form-list-item").click((e) => {
            e.preventDefault();
            $("#sortable>li").each(function(){
                $(this).remove();
            });
            $("#current_form_name").html("Editing: <i>" + $(e.currentTarget).attr("data-name") + "</a>");
            this.current_form_path = $(e.currentTarget).attr("data-path");
            this.current_folder_name = $(e.currentTarget).attr("data-folder");
            this.current_form_name = $(e.currentTarget).attr("data-name");
            //Each li gets above data attributes via form_scanner.php
            this.loadExistingFormData();
            this.setSharingOptions();
            this.loadXML();
            this.bindSelectChanges();
        });
    }
    setSharingOptions(){
        var form_url = window.location.hostname + "/forms/" + this.current_form_path.split("/")[this.current_form_path.split("/").length - 1];
        $("#share_url").val(form_url);
        $("#view_current_form_button").unbind().click(function(){
            window.open("http://" + form_url, "_blank");
        });
    }
    navbarSmoothScroll(){
        $('a[href^="#"]').click(function(e) {
            var id = $(this).attr("href");
            var offset = 100;
            var target = $(id).offset().top - offset;
            $('html, body').animate({scrollTop:target}, 500);
            $('.navbar-collapse').collapse('hide');
            e.preventDefault();
        });
    }
    bindCreateNewFormButton(){
        $("#create_new_form").click((e) => {
            e.preventDefault();
            var name = prompt("What would you like to call your new form?");
            if (name === null){
                return;
            } else if (name === "" || name === " ") {
                alert("Name cannot be empty!");
            } else {
                this.createNewForm(name);
            }
        });
    }
    createNewForm(name){
        $(".form-list-item").each(function(){
            $(this).remove();
        });
        this.createEmailFieldIfNone();
        $.ajax({
            url: "/admin/php/create_new_form.php",
            cache: false,
            context: this,
            method: "POST",
            data: {
                name: name,
                key: "xFtGujikLz63@#"
            },
            error: function(){
                alert("An error has occurred. Please try again later.");
            },
            success: (data) => {
                alert(data);
                if (data.includes("has been created!")){
                    this.refreshFormsDropdown("new", name);
                    this.bindChangeFormButtons();
                    //Need to set currently editing form.
                }
            }
        });  
    }
    refreshFormsDropdown(type, name){
        $.ajax({
            url: "/admin/php/form_scanner.php",
            cache: false,
            context: this,
            method: "POST",
            data: {
                request: "refresh_forms_dropdown",
                key: "xFtGujikLz63@#"
            },
            error: () => {
                alert("An error has occurred. Please try again later.");
            },
            success: (data) => {
                $("#your_forms").html(data);
                if (type === "new"){
                    this.makeNewFormActive(name);
                } else if (type === "delete"){
                    this.makeLastFormActive();
                }
                this.bindChangeFormButtons();
            }
        });
    }
    bindDeleteFormButton(){
        $("#delete_this_form").click((e) => {
            if ($(".form-list-item").length <= 1){
                alert("You cannot delete your last form!");
            } else {
                $.ajax({
                    url: "/admin/php/form_deleter.php",
                    cache: false,
                    context: this,
                    method: "POST",
                    data: {
                        path: this.current_form_path,
                        key: "xFtGujikLz63@#"
                    },
                    error: function(){
                        alert("An error has occurred. Please try again later.");
                    },
                    success: function(data){
                        alert(data);
                        if (data.includes("has been deleted!")){
                            this.refreshFormsDropdown("delete");
                            this.bindChangeFormButtons();
                            this.makeLastFormActive();
                        }
                    }
                });
            }
        });
    }
    makeNewFormActive(name){
        $(".form-list-item").each(function(){
            if ($(this).attr("data-name") === name){
                setTimeout(() => {  //Set timeout is required to make it work for some reason.
                    $(this).click();
                }, 1);
            }
        });
    }
    makeLastFormActive(){
        var li_list_length = $(".form-list-item").length;
        $(".form-list-item").each(function(i){
            if (i == li_list_length - 1){
                setTimeout(() => { //Set timeout is required to make it work for some reason.
                    $(this).click();
                }, 1);
            }
        });
    }
    loadJSON(){
        $.ajax({
            url: "/forms/" + this.current_folder_name + "/form.txt",
            async: false,
            dataType: "text",
            context: this,
            success: function(data){
                $("#editor").text(data);
            }
        });
    }
    loadExistingFormData(){
        $.ajax({
            url: "/forms/" + this.current_folder_name + "/form.json",
            cache: false,
            async: false,
            dataType: "json",
            context: this,
            success: function(data){
                this.createExistingSortableList(data);
            }
        });
    }
    bindApplicationSubmit(){
        $(".application_submit").click((e) => {
            e.preventDefault();
            $.ajax({
                url: "/admin/php/settings_writer.php",
                cache: false,
                context: this,
                method: "POST",
                data: {
                    data: $("#settings_form").serialize(),
                    current_form: this.current_folder_name,
                    key: "xFtGujikLz63@#"
                },
                error: function(){
                    alert("An error has occurred. Please try again later.");
                },
                success: function(data){
                    alert(data);
                }
            });
        });
    }
    createExistingSortableList(json){
        var json_keys = Object.keys(json);
        json_keys.forEach((key) => {
            var first_key = Object.keys(json[key])[0];
            switch (first_key) {
                case "heading":
                    var heading = json[key][first_key];
                    this.appendNewHeading(heading);
                    break;
                case "divider":
                    this.appendNewDivider();
                    break;
                case "spacer":
                    var height = json[key][first_key];
                    this.appendNewSpacer(height);
                    break;
                default:
                    var object = json[key];
                    this.appendNewListItem(object["type"], object["label"], object["required"]);
            }
        });
    }
    bindSelectChanges(){
        $("#height").hide();
        $("label[for='height']").hide();
        this.current_field_type = $("#input_type").val(); //To make validation work properly
        $("#form_name").val(this.getRandomFormName("input"));
        $(document).on("change", "#input_type", () => {
            var input_type = $("#input_type").val();
            this.current_field_type = input_type;
            $("#create_form>input, #create_form>label").hide();
            switch (input_type){
                case "input":
                    $("#label, label[for='label'], label[for='input_type']").show();
                    $("#form_name, label[for='form_name']").show();
                    $("#form_name").val(this.getRandomFormName("input"));
                    $("#height, label[for='height']").hide();
                    $("#required_true, #required_false, label[for='radio']").show();
                    break;
                case "email":
                    $("#label, label[for='label']").show();
                    $("#form_name, label[for='form_name']").show();
                    $("#form_name").val(this.getRandomFormName("email"));
                    $("#height, label[for='height']").hide();
                    $("#required_true, #required_false, label[for='radio']").show();
                    break;
                case "textarea":
                    $("#label, label[for='label'], label[for='input_type']").show();
                    $("#form_name, label[for='form_name']").show();
                    $("#form_name").val(this.getRandomFormName("textarea"));
                    $("#height, label[for='height']").hide();
                    $("#required_true, #required_false, label[for='radio']").show();
                    break;
                case "heading":
                    $("#label, label[for='label']").show();
                    $("#form_name, label[for='form_name']").hide();
                    $("#height, label[for='height']").hide();
                    $("#required_true, #required_false, label[for='radio']").hide();
                    break;
                case "divider":
                    $("#label, label[for='label']").hide();
                    $("#form_name, label[for='form_name']").hide();
                    $("#height, label[for='height']").hide();
                    $("#required_true, #required_false, label[for='radio']").hide();
                    break;
                case "spacer":
                    $("#label, label[for='label']").hide();
                    $("#form_name, label[for='form_name']").hide();
                    $("#height, label[for='height']").show();
                    $("#required_true, #required_false, label[for='radio']").hide();
                    break;
            }
        });
    }
    formBuilderSave(form_txt){
        //This is being triggered multiple times.
        $.ajax({
            url: "/assets/scripts/php/save_form.php",
            cache: false,
            context: this,
            method: "POST",
            data: {
                current_folder: this.current_folder_name,
                form_text: form_txt
            },
            error: function(){
                alert("An error has occurred. Please try again later.");
            },
            success: function(data){
                //alert(data);
                //alert(this.current_folder_name);
            }
        });
    }
    bindAppendButtons(){
        $(".editor-btn").click(function(){
            var button_type = $(this).attr("data-type");
            var url = "/assets/builder_templates/" + $(this).attr("data-type") + ".txt";
            $.get({
                url: url,
                context:this,
                cache: false
            }).then((data) => {
                if (button_type === "reset"){
                    $("#editor").val(data);
                } else {
                    var existing = $("#editor").val();
                    if ($("#editor").val() == ""){
                        var new_editor_contents = existing + data;
                    } else {
                        var new_editor_contents = existing.trim() + "\n\n" + data;
                    }
                    $("#editor").val(new_editor_contents);
                    var textarea = document.getElementById("editor");
                    textarea.scrollTop = textarea.scrollHeight;
                    var speed = 100;
                    $("#editor").animate({"opacity": "0"}, speed);
                    $("#editor").animate({"opacity": "1"}, speed);
                }
            });
        });
    }
    bindCreateFormButtons(){
        $("#create_button").click((e) => {
            this.validateCreateFormData();
        });
    }
    validateCreateFormData(){
        if (this.current_field_type === "input" || this.current_field_type === "email" || this.current_field_type === "textarea"){ //Field type from create new form field 'select' input
            if (!this.testIfAllFieldsEmpty()){
                alert("Please fill out all fields!");
            } else if (!this.validateNameFields()) {
                alert("The name '" + $("#form_name").val() + "' is already taken! Please try again.");
            } else {
                this.appendNewListItem($("#input_type").val(), $("#label").val(), this.getRequiredBool(), $("#height").val());
                this.emptyFormBuilderFields();
            }
        } else if (this.current_field_type === "spacer"){
            if (this.testIfFieldNumeric()){
                this.appendNewSpacer($("#height").val());
                this.emptyFormBuilderFields();
            } else {
                alert("Height field must be a number!");
            }
        } else if (this.current_field_type === "divider") {
            this.appendNewDivider();
            this.emptyFormBuilderFields();
        } else if (this.current_field_type === "heading"){
            if ($("#label").val() == ""){
                alert("Heading label cannot be empty!");
            } else {
                this.appendNewHeading($("#label").val());
                this.emptyFormBuilderFields();
            }
        } else {
            this.getCreateFormData();
            this.emptyFormBuilderFields();
        }
    }
    validateNameFields(){
        var names = [];
        $(".form-field-sortable").each(function() {
            names.push($(this).attr("data-name"));
        });
        if (names.includes($("#form_name").val())){
            return false;
        } else {
            return true;
        }
    }
    testIfFieldNumeric(){
        if (!$.isNumeric($("#height").val())){
            return false;
        } else {
            return true;
        }
    }
    testIfAllFieldsEmpty(){
        var error = [];
        $("#create_form>input").each(function(){
            if (!$(this).val() && $(this).attr("id") != "height"){
                error.push("empty");
            }
        });
        if (error.includes("empty")){
            return false;
        } else {
            return true;
        }
    }
    emptyFormBuilderFields(){
        $("#label").val("");
    }
    getRequiredBool(){
        if ($("#required_true").is(":checked")){
            return "true";
        } else {
            return "false";
        }
    }
    getCreateFormData(){
        var type = $("#input_type").val();
        var label = $("#label").val();
        var name = $("#form_name").val();
        var height = $("#height").val();
        this.appendNewListItem(type, label, this.getRequiredBool(), height);
    }
    shuffle(string) {
        var parts = string.split("");
        for (var i = parts.length; i > 0;) {
            var random = parseInt(Math.random() * i);
            var temp = parts[--i];
            parts[i] = parts[random];
            parts[random] = temp;
        }
        return parts.join("");
    }
    getRandomFormName(type){
        if (type !== "email"){
            return type + "_" + this.shuffle("12345678912345");
        } else {
            return type;
        }
    }
    appendNewListItem(type, label, required, height){
        var element = "<li ";
        element += "id='" + type + "'";
        element += " data-type='" + type + "'";
        element += " data-label='" + label + "'";
        if (type === "email"){
            element += " data-name='email'";
        } else {
            element += " data-name='" + this.getRandomFormName(type) + "'";
        }
        element += " data-required='" + required + "'";
        element += " data-height='" + height + "'";
        element += " class='ui-state-default form-field-sortable'";
        element += ">";
        element += label.replaceAll(":", "");
        element += "</li>";
        $("#sortable").append(element);
        this.addDataToSortItems();
        this.convertListToText();
    }
    appendNewSpacer(height){
        var element = "<li ";
        element += " data-type='spacer'";
        element += " data-height='" + height + "'";
        element += " class='ui-state-default form-field-sortable'";
        element += ">";
        element += "Spacer: " + height + "px";
        element += "</li>";
        $("#sortable").append(element);
        this.addDataToSortItems();
        this.convertListToText();
    }
    appendNewDivider(){
        $("#sortable").append("<li data-type='divider' class='ui-state-default form-field-sortable'>Divider</li>");
        this.addDataToSortItems();
        this.convertListToText();
    }
    appendNewHeading(heading){
        $("#sortable").append("<li data-type='heading' data-label='" + heading + "' class='ui-state-default form-field-sortable'>Heading: " + heading + "</li>");
        this.addDataToSortItems();
        this.convertListToText();
    }
    addDataToSortItems(){
        $(".sortable-delete, .sortable-type").remove();
        $(".form-field-sortable").each(function() {
            $(this).append("<span class='sortable-delete'>Delete</span>");
            $(this).append("<span class='sortable-type'>" + $(this).attr("data-type") + "</span>");
        });
        this.bindDeleteButtons();
    }
    getEmailFieldsNum(){
        var email_fields_num = 0;
        $(".form-field-sortable").each(function(){
            if ($(this).attr("data-type") === "email"){
                email_fields_num++;
            }
        });
        return email_fields_num;
    }
    createEmailFieldIfNone(){
        if (this.getEmailFieldsNum() <= 0){
            this.appendNewListItem("email", "Email", "true");
        }
    }
    bindDeleteButtons(){
        $(".sortable-delete").click((e) => {
            if ($(e.currentTarget).parent().attr("data-type") === "email" && this.getEmailFieldsNum() > 1){
                $(e.currentTarget).parent().animate({"opacity": 0}, 80);
                setTimeout(() => {
                    $(e.currentTarget).parent().remove();
                    this.convertListToText();
                }, 80);
            } else if ($(e.currentTarget).parent().attr("data-type") === "email" && this.getEmailFieldsNum() <= 1){
                alert("You can't delete the email field!");
            } else {
                $(e.currentTarget).parent().remove();
                this.convertListToText();
            }
        });
    }
    convertExistingToListItems(){
        var url = "/forms/" + this.current_folder_name + "/form.txt";
        $.get({
            url: url,
            context: this,
            cache: false
        }).then((data) => {
        });
    }
    convertListToText(stage){ //Text version to be sent to txt_to_json for conversion
        var field = "";
        $(".form-field-sortable").each(function(){
            var type = $(this).attr("data-type");
            var label = $(this).attr("data-label");
            var name = $(this).attr("data-name");
            var required = $(this).attr("data-required");
            var height = $(this).attr("data-height");
            if (type === "input" || type === "email" || type === "textarea"){
                field += "Type: " + type + "\n";
                field += "Label: " + label + "\n";
                field += "Name: " + name + "\n";
                field += "Required: " + required + "\n";
            } else if (type === "heading"){
                field += "Heading: " + label + "\n";
            } else if (type === "divider"){
                field += "Divider" + "\n";
            } else if (type === "spacer"){
                field += "Spacer: " + height + "\n";
            }
            field += "\n";
        });
        var form_txt = field.replaceAll("\n\n\n", "");
        form_txt = field.replaceAll("\n\n\n\n", "");
        form_txt = form_txt.trim();
        this.formBuilderSave(form_txt);
    }
    sortable(){
        $("#sortable").sortable({
            context: this,
            stop: (event, ui) => {
                this.convertListToText();
            }
        });
    }
}

var application = new Application();