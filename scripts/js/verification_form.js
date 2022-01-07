$("#verify_form").submit((e) => {
    e.preventDefault();
    $.ajax({
        url: "contact_form/verify.php",
        cache: false,
        context: this,
        method: "POST",
        data: {
            request: "verify",
            verification_code: $("#verification_code").val(),
            email: $("[name='email']").val(),
            website: "website.com"
            },
        error: function(xhr){
            alert("Error " + xhr.statusText + " occurred. Please try again later.");
        },
        success: function(data){
            if (data.includes("sent!")){
                verifier.timeoutInSessionStorage("remove");
                $("#verification_container").html("<center><br>Your message has been sent!</center>");
            } else {
                alert(data);
            }
        }
    });
});

$("#resend_code").click((e) => {
    e.preventDefault();
    $.ajax({
        url: "contact_form/verify.php",
        cache: false,
        context: this,
        method: "POST",
        data: {
            request: "resend",
            website: "website.com"
            },
        error: function(){
            alert("Error " + xhr.statusText + " occurred. Please try again later.");
        },
        success: function(data){
            $("#verification_code").val("");
            alert(data);
            var timeout = verifier.getTimeout(data);
            verifier.startSessionStorageTimeout(timeout);
        }
    });
});

$("#cancel").click(function(e){
    e.preventDefault();
    $("#verification_container").hide();
    $("#contact_form").show();
    $("#contact_form").find("*").each(function(){
        $(this).removeAttr("disabled");
    });
});