import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    
    idList = new Array();
    endedFrom='';
    endedTo='';
    
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
    setEndedFrom(t){
        console.log("setEndedFrom",t.srcElement.value);
        this.endedFrom=t.srcElement.value;
        this.updateUrl();
    }
    setEndedTo(t){
        console.log("setEndedTo",t.srcElement.value);
        this.endedTo=t.srcElement.value;
        this.updateUrl();
    }
    updateUrl(){
        let and='?';
        let url=this.urlValue;
        /*
         * SET CLEAR
         */
        this.hrefTarget.href = url;
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
         * UPDATE WITH ENDED FROM
         */
        if(this.endedFrom!==''){
            this.hrefTarget.href+=and+"endedfrom="+this.endedFrom;
            and='&';
        }
        /*
         * UPDATE WITH ENDED TO
         */
        if(this.endedTo!==''){
            this.hrefTarget.href+=and+"endedto="+this.endedTo;
        }
        console.log("url - ",this.hrefTarget.href);
    }
}