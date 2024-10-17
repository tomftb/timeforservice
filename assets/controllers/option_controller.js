import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    
    idList = new Array();
    startedAt='';
    endedAt='';
    
    static values = {
        url: String
    };
    static targets = [ "href" ];
        
    connect() {
    }
    setId(t){
        this.id = t.srcElement.value;
        var idx = this.idList.indexOf(t.srcElement.value);
        if(idx<0){
            this.idList.push(t.srcElement.value);
        }
        else{
            this.idList.splice(idx, 1);
        }
        console.log('setId',this.id);
        this.updateUrl();
    }
    setStartedAt(t){
        console.log("setStartedAt",t.srcElement.value);
        this.startedAt=t.srcElement.value;
        this.updateUrl();
    }
    setEndedAt(t){
        console.log("setEndedAt",t.srcElement.value);
        this.endedAt=t.srcElement.value;
        this.updateUrl();
    }
    updateUrl(){
        let and='?';
        let url=this.urlValue;
        /*
         * SET CLEAR
         */
        this.hrefTarget.href = url
        /*
         * UPDATE WITH ARRAY LIST
         */
        if(this.idList.length!==0){
            let tmpList = this.idList.join(',');
            this.hrefTarget.href = url+"?id="+tmpList;
            url='';
            and='&';
        }
        /*
         * UPDATE WITH STARTED AT
         */
        if(this.startedAt!==''){
            this.hrefTarget.href+=and+"startedat="+this.startedAt;
            and='&';
        }
        /*
         * UPDATE WITH ENDED AT
         */
        if(this.endedAt!==''){
            this.hrefTarget.href+=and+"endedat="+this.endedAt;
        }
        console.log("url - ",this.hrefTarget.href);
    }
}