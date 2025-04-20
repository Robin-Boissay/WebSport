// assets/controllers/collection_type_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    // Définit les "cibles" que notre contrôleur doit connaître :
    // - collectionHolder: L'élément qui contient les éléments de la collection (souvent un <ul> ou <div>)
    // - prototype: L'élément (souvent caché) qui contient le modèle HTML pour un nouvel élément
    // - item: Chaque élément individuel dans la collection (<li>, <div>...)
    static targets = ["collectionHolder", "prototype", "item"];

    // Propriété pour suivre l'index du prochain élément à ajouter
    index;

    connect() {
        // Initialise l'index au démarrage
        // Soit depuis data-index (s'il est défini), soit en comptant les éléments existants
        this.index = parseInt(this.element.dataset.index || this.collectionHolderTarget.children.length);
        this.element.dataset.index = this.index; // Met à jour l'attribut data-index
    }

    add(event) {
        event.preventDefault();

        // Vérifie si la cible prototype existe
        if (!this.hasPrototypeTarget) {
            console.error('Cannot add item: Prototype target not found for', this.element);
            return;
        }

        const prototypeHtml = this.prototypeTarget.dataset.prototype;
        if (!prototypeHtml) {
            console.error('Cannot add item: data-prototype attribute missing on prototype target for', this.element);
            return;
        }

        // Remplace le placeholder "__name__" par l'index actuel
        const newHtml = prototypeHtml.replace(/__name__/g, this.index);
        // Incrémente l'index pour la prochaine addition
        this.index++;
        this.element.dataset.index = this.index; // Met à jour l'attribut pour la persistance simple

        // Insère le nouvel élément HTML dans le conteneur de la collection
        // 'beforeend' ajoute le nouvel élément à la fin du collectionHolderTarget
        this.collectionHolderTarget.insertAdjacentHTML('beforeend', newHtml);

        // Émet un événement pour signaler qu'un élément a été ajouté (utile pour d'autres logiques)
        this.dispatch('added', { detail: { element: this.collectionHolderTarget.lastElementChild } });
        // console.log('Item added, new index:', this.index);
    }

    remove(event) {
        event.preventDefault();

        // event.currentTarget est le bouton "Supprimer" qui a été cliqué
        // Trouve l'élément "item" le plus proche (parent) à supprimer
        const itemToRemove = event.currentTarget.closest('[data-collection-type-target="item"]');

        if (itemToRemove) {
            itemToRemove.remove();
            // Émet un événement pour signaler qu'un élément a été supprimé
            this.dispatch('removed', { detail: { element: itemToRemove } });
            // console.log('Item removed:', itemToRemove);
        } else {
            console.warn('Could not find item target to remove for button:', event.currentTarget);
        }
    }

    
    
}