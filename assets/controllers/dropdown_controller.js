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
        let actionList = document.getElementById(this.indexValue);
        actionList.classList.remove('hidden');
    }
    hide(){
        let actionList = document.getElementById(this.indexValue);
        actionList.classList.add('hidden');
    }
}