import { Controller } from '@hotwired/stimulus';

export default class extends Controller{
    static targets = ['dialog','dynamicContent','loadingContent'];
    
    observer = null;
    /*
     * OPEN MODAL
     */
    connect(){
        console.log('connect');
        console.log(this.dialogTarget);
        console.log(this.dialogTarget.classList);

        if(this.hasDynamicContentTarget ){
            console.log('hasDynamicContentTarget');
            // when the content changes, call this.open()
            this.observer = new MutationObserver(() => {
                const shouldOpen = this.dynamicContentTarget.innerHTML.trim().length > 0;
                
                if(shouldOpen && this.dialogTarget.classList.contains('hidden') ){
                    this.open();
                }
                else if (!shouldOpen && !this.dialogTarget.classList.contains('hidden') ){
                    this.close();
                }
                /*
                if(shouldOpen && ! this.dialogTarget.open ){
                    this.open();
                }
                else if (!shouldOpen && this.dialogTarget.open ){
                    this.close();
                }*/
            });
            this.observer.observe(this.dynamicContentTarget , {
                childList: true,
                characterData: true,
                subtree: true 
            });
        }
        else{
            console.log('NO hasDynamicContentTarget');
        }
    }
     /*
     * CLOSE MODAL
     */
    disconnect (){
        console.log('disconnect');
        if(this.observer){
            this.observer.disconnect();
        }
        if(!this.dialogTarget.classList.contains('hidden')){
            this.dialogTarget.classList.add('hidden');
        }
        /*
         * DIALOG FEATURE
        if(this.dialogTarget.open){
            this.close();
        }*/
    }
    
    open(){
        console.log('open dialog');
        /*
         * IF element IS dialog
         * showModal() adds some extra features
         * OLD VERSION - this.dialogTarget.show();
         this.dialogTarget.showModal();
          */
        /*
         * IF elements IS ex. div
         */
        if(this.dialogTarget.classList.contains('hidden')){
            this.dialogTarget.classList.remove('hidden');
        }
        /*
         * TURN OFF PAGE SCROOL
         */
        document.body.classList.add('overflow-hidden');
    }
    close(){
        console.log("close dialog");
        /*
         * IF element IS dialog
         * 
        if(this.hasDialogTarget){
            this.dialogTarget.close();
        }
         */
        /*
         * IF elements IS ex. div
         */
        if(!this.dialogTarget.classList.contains('hidden')){
            this.dialogTarget.classList.add('hidden');
        }
        document.body.classList.remove('overflow-hidden');
    }
    /*
     * close dialog element, if click outside of the dialog box 
     */
    closeOutside(event){
        console.log("closeOutside");
        if(!this.dialogTarget.classList.contains('hidden')){
            this.dialogTarget.classList.add('hidden');
        }
        /*
         * DIALOG FEATURE
         * 
        if(event.target === this.dialogTarget){
            this.dialogTarget.close();
        }*/
    }
    /*
     * SHOW LOADING CONTENT WHEN LOAD MODAL - DIALOG ELEMENT
     */
    showLoading(){
        console.log("showLoading");
        if(!this.dialogTarget.classList.contains('hidden')){
            return;
        }
        /*
         * DIALOG FEATURE
         * 
        if(this.dialogTarget.open){
            return;
        }
         */
        this.dynamicContentTarget.innerHTML = this.loadingContentTarget.innerHTML;
    }
}