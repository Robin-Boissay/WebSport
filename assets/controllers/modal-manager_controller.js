// assets/controllers/modal-manager_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = []; // Le manager lui-même est la cible principale

    connect() {
        // console.log('Modal Manager Connected');
        this.openModalHandler = this.requestOpenModal.bind(this);
        this.bringToFrontHandler = this.handleBringToFront.bind(this);
        // Utiliser un nom d'événement spécifique
        document.addEventListener('dispatch-open-modal', this.openModalHandler);

        this.element.addEventListener('modal:bringToFront', this.bringToFrontHandler);
    }

    disconnect() {
        document.removeEventListener('dispatch-open-modal', this.openModalHandler);
        this.element.removeEventListener('modal:bringToFront', this.bringToFrontHandler);
        // console.log('Modal Manager Disconnected');
    }

    handleBringToFront(event) {
        const modalToFront = event.detail.modalElement;
        if (!modalToFront || !this.element.contains(modalToFront)) return;

        // 1. Trouver le z-index le plus élevé actuellement parmi les modales visibles
        let maxZ = 1040; // Valeur de base
        const visibleModals = this.element.querySelectorAll('.modal.modal-visible');
        visibleModals.forEach(modal => {
            const currentZ = parseInt(modal.style.zIndex || '1040', 10);
            if (currentZ > maxZ) {
                maxZ = currentZ;
            }
        });

        // 2. Si la modale à mettre devant n'a pas déjà le z-index le plus élevé + 10 (marge),
        //    on lui donne un z-index supérieur.
        const currentModalZ = parseInt(modalToFront.style.zIndex || '1040', 10);
        const targetZ = maxZ + 10; // Nouvelle valeur cible

        // On ne met à jour que si nécessaire pour éviter des changements inutiles
        if (currentModalZ < targetZ) {
            // console.log(`Bringing modal to front. Old Z: ${currentModalZ}, New Z: ${targetZ}`);
            modalToFront.style.zIndex = targetZ;
            const dialog = modalToFront.querySelector('.modal-dialog');
            if (dialog) {
                dialog.style.zIndex = targetZ + 1;
            }
        } else {
             // console.log(`Modal already has highest or sufficient z-index: ${currentModalZ}`);
        }
    }

    // Méthode appelée par l'événement dispatch-open-modal
    requestOpenModal(event) {
        const { url } = event.detail;
        if (!url) {
            console.error("Modal Manager: 'dispatch-open-modal' event received without URL.");
            return;
        }

        const template = document.getElementById('modal-template');
        if (!template) {
            console.error("Modal Manager: Cannot find #modal-template element.");
            return;
        }

        // Clone le contenu du template
        const modalElement = template.content.firstElementChild.cloneNode(true);

        // Calcule le z-index pour être au-dessus des autres
        const currentModals = this.element.querySelectorAll('.modal.modal-visible').length;
        const baseZIndex = 1040;
        const newZIndex = baseZIndex + (currentModals * 10); // Incrémente pour chaque modale
        modalElement.style.zIndex = newZIndex;
        modalElement.querySelector('.modal-dialog').style.zIndex = newZIndex + 1;

        // Attribue l'URL au contrôleur dynamique via une valeur Stimulus
        // Note: le contrôleur modal-dynamic sera automatiquement initialisé par Stimulus
        // car il est défini dans le HTML cloné.
        modalElement.dataset.modalUrlValue = url; // Passe l'URL

        // Ajoute la nouvelle modale au conteneur
        this.element.appendChild(modalElement);

        // console.log(`Modal Manager: Added new modal for URL ${url} with z-index ${newZIndex}`);
        // Le contrôleur 'modal-dynamic' attaché à modalElement prendra le relais pour le chargement/affichage.
    }

    // Gère la fermeture par ESC
    closeOnTop(event) {
        const visibleModals = Array.from(this.element.querySelectorAll('.modal.modal-visible'));
        if (visibleModals.length === 0) return;

        // Trouve la modale avec le z-index le plus élevé
        let topModalElement = visibleModals.reduce((top, current) => {
            const topZ = parseInt(top.style.zIndex || '0', 10);
            const currentZ = parseInt(current.style.zIndex || '0', 10);
            return currentZ > topZ ? current : top;
        }, visibleModals[0]);

        if (topModalElement) {
            // Trouve le contrôleur Stimulus associé à cet élément
            const topModalController = this.application.getControllerForElementAndIdentifier(topModalElement, 'modal');
            if (topModalController) {
                // Appelle la méthode 'close' du contrôleur de la modale
                // console.log(`Modal Manager: Requesting close for modal`, topModalElement);
                topModalController.close(event);
            }
        }
    }

     // Optionnel: Écouter quand une modale est fermée/supprimée
     handleModalClosed(event) {
        // console.log(`Modal Manager: Detected modal closed`, event.target);
        // Pourrait être utilisé pour réajuster les z-index si nécessaire,
        // mais la suppression de l'élément est souvent suffisante.
     }
}