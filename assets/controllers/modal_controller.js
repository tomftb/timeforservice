import { Controller } from '@hotwired/stimulus';

export default class extends Controller{
    static targets = ['dialog'];
    
    open(){
        /*
         * showModal() adds some extra features
         */
        this.dialogTarget.showModal();
        //this.dialogTarget.show();
        /*
         * TURN OFF PAGE SCROOL
         */
        document.body.classList.add('overflow-hidden');
    }
    close(){
        if(this.hasDialobTarget){
            this.dialogTarget.close();
        }
        document.body.classList.remove('overflow-hidden');
    }
    /*
     * close dialog element, if click outside of the dialog box 
     */
    closeOutside(event){
        if(event.target === this.dialogTarget){
            this.dialogTarget.close();
        }
    }
}