import { Controller } from '@hotwired/stimulus';

export default class extends Controller{
    static targets = ['dialog','dynamicContent','loadingContent'];
    
    observer = null;
    /*
     * OPEN MODAL
     */
    connect(){
        if(this.hasDynamicContentTarget ){
            // when the content changes, call this.open()
            this.observer = new MutationObserver(() => {
                const shouldOpen = this.dynamicContentTarget.innerHTML.trim().length > 0;
                if(shouldOpen && ! this.dialogTarget.open ){
                    this.open();
                }
                else if (!shouldOpen && this.dialogTarget.open ){
                    this.close();}
            });
            this.observer.observe(this.dynamicContentTarget , {
                childList: true,
                characterData: true,
                subtree: true 
            });
        }
    }
     /*
     * CLOSE MODAL
     */
    disconnect (){
        if(this.observer){
            this.observer.disconnect();
        }
        if(this.dialogTarget.open){
            this.close();
        }
    }
    
    open(){
        console.log('open dialog');
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
        console.log("close dialog");
        if(this.hasDialogTarget){
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
    /*
     * SHOW LOADING CONTENT WHEN LOAD MODAL - DIALOG ELEMENT
     */
    showLoading(){
        if(this.dialogTarget.open){
            return;
        }
        this.dynamicContentTarget.innerHTML = this.loadingContentTarget.innerHTML;
    }
}