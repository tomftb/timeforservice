import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    
    static values = {
        autoClose: Number
    }
    
    static targets = ['timebar'];
    
    connect(){
        if(this.autoCloseValue){
            setTimeout(()=>{
                this.close();
            },this.autoCloseValue);
        }
        if(this.hasTimebarTarget){
            setTimeout(()=>{
                 this.timebarTarget.style.width = 0;
            },10);
           
        }
    }
    
    close() {
       console.log(this);
       this.element.remove();
   }
}
