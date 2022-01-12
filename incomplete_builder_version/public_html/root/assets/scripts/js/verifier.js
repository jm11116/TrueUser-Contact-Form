class Verifier {
    constructor(){
        $.ajaxSetup({cache: false});
        this.form_id = "#contact_form"; //With CSS selector prepended
        this.verify_start = false;
        this.website = "www.website.com";
        this.bindSubmit(this.form_id);
        this.timeout = false;
    }
    bindSubmit(form_id){ //Make it so that the banned IP thing happens AFTER form submission, faking fullscreen permission with the button click!! Echo differences!
        $(form_id).submit((e) => {
            e.preventDefault();
            $(verifier.form_id).find("*").each(function(){
                $(this).removeAttr("disabled"); //Fields temp enabled to allow serialize() to work
            });
            this.startVerify(form_id);
        });
    }
    getErrors(){ //Might as well validate client-side since form doesn't even work with JS.
        var errors = [];
        field_names.forEach(function(elem_name, i){
            if ($("[name='" + elem_name + "']").val() == undefined || $("[name='" + elem_name + "']").val() == ""){
                var error = "ERROR: " + field_labels[i] + " field cannot be empty.";
                errors.push(error);
            }
            //Need mail validation
        });
        return errors;
    }
    startVerify(form_id){
        $.ajax({
            url: "contact_form/verify.php",
            cache: false,
            context: this,
            method: "POST",
            data: {
                request: "get_code",
                form_data: $(verifier.form_id).serialize(),
                field_names: field_names,
                labels: field_labels,
                email: $("[name='email']").val(),
                website: "website.com"
            },
            error: function(){
                alert("An error has occurred. Please try again later.");
            },
            success: function(data){
                alert(data);
                if (data.includes("Please check your inbox.")){
                    $.get("contact_form/verification_input.html", function(html){
                        $(form_id).find("*").each(function(){
                            $(this).prop("disabled", "true");
                        });
                        $("#verification_container").html(html);
                        $("#verification_container").show();
                        $("#contact_form").hide();
                    });
                } else if (data.includes("Too many")){
                    var timeout = this.getTimeout(data);
                    this.startSessionStorageTimeout(timeout);
                }
            }
        });
    }
    startSessionStorageTimeout(current_timeout){
        var timeout = current_timeout;
        $("#contact_submit, #resend_code").prop("disabled", "true");
        clearInterval(interval);
        var interval = setInterval(() => {
            $("#contact_submit, #resend_code").prop("disabled", "true");
            $("#contact_submit").prop("value", "Submit" + " (Wait " + timeout + " seconds)");
            $("#resend_code").html("(Resend in " + timeout + " secs)");
            timeout--;
            this.timeoutInSessionStorage("set", timeout);
            if (timeout <= 0){
                clearInterval(interval);
                this.timeoutInSessionStorage("remove");
                $("#contact_submit").prop("value", "Submit");
                $("#resend_code").html("Resend Code");
                $("#contact_submit, #resend_code").removeAttr("disabled");
            }
        }, 1000);
    }
    getTimeout(data){
        var split = data.split("Please wait ");
        var split2 = split[1].split(" seconds to try again.");
        var timeout = split2[0];
        this.timeoutInSessionStorage("set", timeout);
        return timeout;
    }
    timeoutInSessionStorage(type, timeout){
        if (type === "get"){
            return sessionStorage.getItem("timeout");
        } else if (type === "set"){
            sessionStorage.setItem("timeout", timeout)
        } else if (type === "remove"){
            sessionStorage.removeItem("timeout");
        }
    }
    checkTimeout(){ //Started with form enabled in security.js
        if (this.timeoutInSessionStorage("get") != "NaN" && this.timeoutInSessionStorage("get") != null){
            this.startSessionStorageTimeout(this.timeoutInSessionStorage("get"));
        }
    }
}

var verifier = new Verifier();