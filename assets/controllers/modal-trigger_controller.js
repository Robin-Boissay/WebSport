// assets/controllers/modal-trigger-dynamic_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = { url: String }

    trigger(event) {
        event.preventDefault();

        if (this.urlValue) {
            console.log(this.urlValue);
            // Dispatch l'événement que le manager écoute
            const openEvent = new CustomEvent('dispatch-open-modal', {
                bubbles: true,
                detail: { url: this.urlValue }
            });
            this.element.dispatchEvent(openEvent);
        } else {
            console.error('Le bouton/lien déclencheur de modale n\'a pas d\'attribut data-modal-trigger-url-value.');
        }
    }
}