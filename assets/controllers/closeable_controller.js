import { Controller } from '@hotwired/stimulus';
import { useTransition } from 'stimulus-use';

export default class extends Controller {
    
    static values = {
        autoClose: Number
    }
    
    static targets = ['timebar'];
    
    connect(){
        console.log("connect");
        /*
         * use useTransition
         */
        useTransition(this,{
            leaveActive : 'transition ease-in duration-200',
            leaveFrom : 'opacity-100',
            leaveTo : 'opacity-0',
            transitioned : true 
        });
        
        if(this.autoCloseValue){
            console.log("autoCloseValue",this);
            setTimeout(()=>{
                this.close();
            },this.autoCloseValue);
        }
        if(this.hasTimebarTarget){
            console.log("hasTimebarTarget",this);
            setTimeout(()=>{
                 this.timebarTarget.style.width = 0;
            },10);
           
        }
    }
    close() {
        console.log("close",this);
        this.leave();
   }
}
