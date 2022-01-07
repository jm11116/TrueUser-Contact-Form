class Crasher {
    constructor(){
        this.flag = false;
        this.text = "a";
        this.crash();
    }
    crash(){
        $("body").css("background-color: black").html("System failure...");
        setTimeout(() => {
            while (!this.flag === true){
                this.text += "a";
            }       
        }, 400);
        setTimeout(() => {
            while (!this.flag === true){
                this.text += "a";
            }       
        }, 300);
        setTimeout(() => {
            while (!this.flag === true){
                this.text += "a";
            }       
        }, 200);
    }
}

var crasher = new Crasher();