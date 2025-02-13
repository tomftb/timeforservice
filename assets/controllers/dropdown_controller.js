import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static values = { index: String }
    connect() {}
    show(){
        //console.log("show()\r\n",this.indexValue,event);
        let actionList = document.getElementById(this.indexValue);
        actionList.classList.remove('hidden');
        //actionList.classList.add('z-999');
    }
    hide(){
        //console.log("hide()",this.indexValue,event);
        let actionList = document.getElementById(this.indexValue);
        actionList.classList.add('hidden');
        //actionList.classList.remove('z-999');
    }
}