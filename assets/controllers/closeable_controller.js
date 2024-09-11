import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
   close() {
       console.log(this);
       this.element.remove();
   }
}
