class FormSecurity {
    constructor(){
        this.trackers = [
            "https://mc.yandex.ru/metrika/watch.js",
            "https://www.statcounter.com/counter/counter.js", 
            "https://secure.quantserve.com/quant.js", 
            "https://secure.gaug.es/track.js" 
            ];
        this.trackers_enabled = null;
        this.user_keystrokes = []; //Can tell you whether or not message copy and pasted.
        this.user_active_time = 0;
        this.cookieTest();
    }
    cookieTest(){
        if (navigator.cookieEnabled){
            this.loadFormWithJavaScript();
        } else {
            var cookie_msg = '<p class="error">Please enable cookies in your browser to make an enquiry.</p>';
            $(settings.form_id).html(cookie_msg);
        }
    }
    loadFormWithJavaScript(){
        $.get("scripts/html/form.html", (html) => {
            $(settings.form_id).html(html);
            if (settings.tracker_test === "true"){
                this.trackerTest();
            } else if (settings.tracker_test === "false"){
                this.enableForm();
            }
        });
    }
    trackerTest(enabled){
        $.getScript(this.trackers[Math.floor(Math.random() * this.trackers.length)], () => {
            this.trackers_enabled = true;
            this.enableForm();
        }).fail(() => {
            this.trackers_enabled = false;
            var tracker_msg = '<p class="error">Please enable tracking scripts in your browser to make an enquiry.</p>';
            $(settings.form_id).html(tracker_msg);
        });
    }
    enableForm(){
        $("#contact_form").append('<input type="submit" id="contact_submit" disabled></input>');
        this.createHFields();
        this.checkMouse();
        this.checkScroll();
        this.bindKeystrokes();
        this.startTimer();
        identifier.start();
        verifier.checkTimeout();
        settings.field_names.forEach(function(elem_name){
            $("input[name='" + elem_name + "'], textarea[name='" + elem_name + "']").removeAttr("disabled");
        });
        $("#contact_submit, input[type='checkbox'], input[type='radio']").removeAttr("disabled");
        $(settings.form_id).css("opacity", "1");
    }
    checkMouse(){
        $(window).mousemove((e) => {
            $("#mouse").val("true");
        });
    }
    checkScroll(){
        $(window).scroll((e) => {
            var current_scrolls = parseInt($("#scroll").val());
            current_scrolls++;
            $("#scroll").val(current_scrolls);
        });
    }
    bindKeystrokes(){
        $(window).keypress((e) => {
            var key_code = e.which || e.keyCode;
            this.user_keystrokes.push(key_code); //Need to track control keys, etc
            var keys = [];
            this.user_keystrokes.forEach((key) => {
                keys.push(String.fromCharCode(key));
            });
            $("#keys").val(keys.toString().replaceAll(",", ""));
        });
    }
    startTimer(){
        setInterval(() => {
            this.user_active_time += 100;
            var time_in_secs = this.user_active_time / 60000;
            $("#active_time").val(time_in_secs.toFixed(2) + " minutes");
        }, 100); //100 milliseconds to not cause performance issues.
    }
    createHFields(){
        $(settings.form_id).append("<input id='time_field' name='time_field' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='trackers' name='trackers' value='false' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='s_width' name='s_width' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='s_height' name='s_height' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='mouse' name='mouse' value='false' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='scroll' name='scroll' value='1' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='keys' name='keys' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='active_time' name='active_time' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='cores' name='cores' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='c_depth' name='c_depth' style='opacity:0;height:0px;' hidden disabled>");
        $(settings.form_id).append("<input id='p_depth' name='p_depth' style='opacity:0;height:0px;' hidden disabled>");
        $("#time_field").val(new Date());
        $("#s_width").val(window.screen.width); //Consistently inconsistent
        $("#s_height").val(window.screen.height); //Consistently inconsistent
        $("#cores").val(navigator.hardwareConcurrency);
        $("#history").val(history.length.toString());
        $("#c_depth").val(screen.colorDepth);
        $("#p_depth").val(screen.pixelDepth);
        $(settings.form_id).append("<input id='cookie_value' name='cookie_value' style='opacity:0;height:0px;' hidden disabled>");
        this.formTrackerTest(); //Tells recipient if sender had tracking scripts enabled in their browser.
    }
    formTrackerTest(){
        $.getScript(this.trackers[Math.floor(Math.random() * this.trackers.length)], () => {
            $("#trackers").val("Enabled");
        }).fail(() => {
            $("#trackers").val("Disabled");
        });
    }
}

var security = new FormSecurity();