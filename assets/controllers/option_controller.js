import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    
    idList = new Array();
    static values = {
        url: String
    };
    static targets = [ "href" ];
        
    connect() {
    }
    set(t){
        this.id = t.srcElement.value;
        var idx = this.idList.indexOf(t.srcElement.value);
        if(idx<0){
            this.idList.push(t.srcElement.value);
        }
        else{
            this.idList.splice(idx, 1);
        }
        this.updateUrl();
    }
    updateUrl(){
        if(this.idList.length!==0){
            let tmpList = this.idList.join(',');
            this.hrefTarget.href = this.urlValue+"?id="+tmpList;
        }
    }
}