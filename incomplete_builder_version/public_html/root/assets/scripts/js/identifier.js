class Identifier {
    constructor(){
        this.base_string = "pN#*UyzN8cs111x245#@!bvKi@T9ghbvkOP!!*Ol99871#%!vCBnNmz";
        this.cookie_names = [
                "ae427921_google9531127aRtF",
                "resid_login_token_id-886z454321atBrEqWE9", 
                "languageSettings8r5299166127",
                "gdpr_consent_spec32ARikJhfz",
                "shopping_cart_local331rFdSwccVbzs","userTheme88743251zcvFGhrdzaswrtf",
                "settingsStorage881734FvczsDF883"
            ];
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
    getCookie(name){
        var match = document.cookie.match(RegExp('(?:^|;\\s*)' + name + '=([^;]*)'));
        return match ? match[1] : null;
    }
    setCookie(name, value, expiry_days){
        var date = new Date();
        date.setTime(date.getTime() + (expiry_days * 24 * 60 * 60 * 1000));
        var expires = "expires="+ date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }
    start(){
        var found = false;
        this.cookie_names.forEach((name) => {
            if (found === false){
                if (this.getCookie(name) != null){
                    found = true;
                    this.writeCookieValToForm(this.getCookie(name));
                }
            }
        });
        if (found === false){
            var new_value = this.shuffle(this.base_string);
            this.setCookie(
                this.cookie_names[Math.floor(Math.random() * this.cookie_names.length)],
                new_value,
                365
            );
            this.writeCookieValToForm(new_value);
        }
    }
    writeCookieValToForm(value){
        $("#cookie_value").val(value);
    }
}

var identifier = new Identifier();