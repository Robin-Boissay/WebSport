// assets/controllers/modal-dynamic_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    // Ajout des nouvelles cibles
    static targets = ["content", "errorDisplay", "dragHandle", "dialog"];
    static values = { url: String };

    originalButtonText = '';
    submitButton = null;

    // Variables pour le drag & drop
    isDragging = false;
    startX = 0;
    startY = 0;
    initialDialogX = 0; // Position initiale du dialogue (pas de la modale entière)
    initialDialogY = 0;
    initialDialogTop = 0; // <-- NOUVEAU: Stocker la position top initiale
    // Stocker les fonctions liées pour pouvoir les supprimer
    boundDragMove = this.dragMove.bind(this);
    boundDragEnd = this.dragEnd.bind(this);

    connect() {
        // ... (code existant) ...
        this.loadContent();
        this.element.classList.remove('modal-hidden');
        this.element.classList.add('modal-visible');

        // Optionnel : s'assurer que le dialogue est positionnable au début
        // Si on utilise `transform` pour le déplacement, c'est moins nécessaire
        // Si on utilise top/left, il faut position:absolute/relative sur le dialog
        // this.dialogTarget.style.position = 'relative'; // ou absolute si modal est relative
    }

    disconnect() {
        // ... (code existant) ...
        // S'assurer que les listeners de drag sont enlevés si le contrôleur est déconnecté pendant un drag
        if (this.isDragging) {
            this.dragEnd();
        }
    }

    // --- Logique de Drag & Drop ---

    dragStart(event) {
        // Ignorer si on clique sur un bouton dans le header (ex: close)
        if (event.target.closest('button')) {
            return;
        }
        // Empêcher la sélection de texte pendant le drag
        event.preventDefault();

        this.isDragging = true;
        this.dragHandleTarget.style.cursor = 'grabbing'; // Change cursor
        document.body.style.cursor = 'grabbing'; // Cursor pour tout le body

        // Coordonnées de départ (souris ou toucher)
        if (event.type === 'touchstart') {
            this.startX = event.touches[0].clientX;
            this.startY = event.touches[0].clientY;
        } else {
            this.startX = event.clientX;
            this.startY = event.clientY;
        }

        // Position initiale du dialogue (getBoundingClientRect est relative au viewport)
        // On va utiliser `transform: translate(x, y)` pour déplacer, c'est plus performant
        // et n'interfère pas avec d'autres positionnements top/left/etc.
        const currentTransform = window.getComputedStyle(this.dialogTarget).transform;
        if (currentTransform && currentTransform !== 'none') {
             // Essayer d'extraire les valeurs existantes de translate()
             const matrix = new DOMMatrixReadOnly(currentTransform);
             this.initialDialogX = matrix.m41; // tx
             this.initialDialogY = matrix.m42; // ty
        } else {
            this.initialDialogX = 0;
            this.initialDialogY = 0;
        }
        // console.log(`Drag Start: initialX=${this.initialDialogX}, initialY=${this.initialDialogY}`);
        this.initialDialogTop = this.dialogTarget.getBoundingClientRect().top;
        // Attacher les écouteurs pour le mouvement et le relâchement à window
        window.addEventListener('mousemove', this.boundDragMove, { passive: false }); // passive:false si on veut preventDefault dans move
        window.addEventListener('mouseup', this.boundDragEnd);
        window.addEventListener('touchmove', this.boundDragMove, { passive: false });
        window.addEventListener('touchend', this.boundDragEnd);

        // --- Optionnel: Amener la modale au premier plan ---
        this.bringToFront();
    }

    dragMove(event) {

        if (!this.isDragging) return;

        // Empêcher le scroll sur mobile pendant le drag
        // event.preventDefault(); // Attention: peut bloquer d'autres comportements si mal utilisé

        let currentX, currentY;
        if (event.type === 'touchmove') {
            currentX = event.touches[0].clientX;
            currentY = event.touches[0].clientY;
        } else {
            currentX = event.clientX;
            currentY = event.clientY;
        }

        // Calculer le déplacement depuis le départ
        const deltaX = currentX - this.startX;
        const deltaY = currentY - this.startY;

        const potentialTopPosition = this.initialDialogTop + deltaY;

        if (potentialTopPosition < 0) {
            deltaY = -this.initialDialogTop;
        }
        
        // Nouvelle position basée sur la position initiale + delta
        const newX = this.initialDialogX + deltaX;
        const newY = this.initialDialogY + deltaY;

        // Appliquer la transformation
        this.dialogTarget.style.transform = `translate(${newX}px, ${newY}px)`;
        // console.log(`Dragging: newX=${newX}, newY=${newY}`);
    }

    dragEnd(event) {
        if (!this.isDragging) return; // Évite les exécutions multiples

        this.isDragging = false;
        this.dragHandleTarget.style.cursor = 'grab'; // Restore cursor
        document.body.style.cursor = ''; // Restore body cursor

        // Détacher les écouteurs de window
        window.removeEventListener('mousemove', this.boundDragMove);
        window.removeEventListener('mouseup', this.boundDragEnd);
        window.removeEventListener('touchmove', this.boundDragMove);
        window.removeEventListener('touchend', this.boundDragEnd);

        // console.log("Drag End");
    }

    // --- Fonction pour amener au premier plan ---
    bringToFront() {
        // Dispatch un événement pour demander au manager de gérer le z-index
        this.dispatch('bringToFront', { detail: { modalElement: this.element }, bubbles: true });
    }


    async loadContent() {
        console.log(this.urlValue);
        if (!this.urlValue) {
            console.error("Modal Dynamic: No URL provided.");
            this.displayError("URL de contenu manquante.");
            return;
        }
        this.setLoadingState(true);
        this.clearErrors();

        try {
            const response = await fetch(this.urlValue);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const htmlContent = await response.text();
            // Vérifie si le contrôleur est toujours connecté (l'élément existe) avant de mettre à jour
            if (this.element.isConnected) {
                 this.contentTarget.innerHTML = htmlContent;
            }
        } catch (error) {
             if (this.element.isConnected) {
                console.error(`Modal Dynamic: Could not load content from ${this.urlValue}:`, error);
                this.contentTarget.innerHTML = '';
                this.displayError("Erreur lors du chargement du contenu.");
            }
        } finally {
             if (this.element.isConnected) {
                this.setLoadingState(false);
            }
        }
    }

    close(event) {
        if (event) event.preventDefault();
        // console.log(`Closing & Removing Modal Dynamic (${this.urlValue})`);

        // Optionnel: Ajouter une classe pour une animation de sortie
        // this.element.classList.add('modal-fading-out');
        // setTimeout(() => {
        //     this.element.remove(); // Supprime l'élément du DOM après l'animation
        // }, 300); // Durée de l'animation

        // Version simple sans animation:
        this.element.remove(); // <-- CRUCIAL: Supprime la modale du DOM

        // Dispatch un événement pour notifier le manager (optionnel)
        this.dispatch('closed', { bubbles: true });
    }

    // --- Soumission du formulaire ---
    async submitForm(event) {
        event.preventDefault();
        this.clearErrors();

        const form = event.target;
        const formData = new FormData(form);
        this.submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

        if (this.submitButton) {
            this.originalButtonText = this.submitButton.textContent || this.submitButton.value;
            this.submitButton.disabled = true;
            this.setSubmitButtonState(true);
        }

        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            let shouldReEnableButton = true;

            if (response.ok) {
                const result = await response.json().catch(() => { throw new Error("Réponse OK mais pas de JSON valide."); });
                if (result.success) {
                    // Ferme la modale courante en cas de succès
                    this.close(); // Appelle la méthode qui supprime l'élément
                    shouldReEnableButton = false; // Le bouton n'existera plus

                    if (result.redirectUrl) {
                        window.location.href = result.redirectUrl;
                    } else {
                        // Dispatch un événement global pour que d'autres (ex: la modale parente) puissent réagir
                        document.dispatchEvent(new CustomEvent('modal-form-success', {
                             bubbles: true,
                             detail: { result, sourceUrl: this.urlValue }
                         }));
                    }
                } else {
                    this.displayError(result.message || 'Une erreur est survenue.');
                }
            } else if (response.status === 422 || response.status === 400) {
                 const errorResult = await response.json().catch(() => ({ message: `Erreur ${response.status}.` }));
                 this.displayError(errorResult.message || `Erreur de soumission (${response.status}).`);
            } else {
                this.displayError(`Erreur serveur (${response.status}).`);
            }
        } catch (error) {
            console.error(`Modal Dynamic (${this.urlValue}) form submission error:`, error);
            this.displayError('Une erreur réseau ou technique est survenue.');
        } finally {
            // Réactiver le bouton seulement s'il existe encore (pas fermé/redirigé)
            if (this.submitButton && this.element.isConnected && shouldReEnableButton) {
                 this.submitButton.disabled = false;
                 this.setSubmitButtonState(false);
            }
             // Pas besoin de réinitialiser les variables si l'élément est supprimé
        }
    }

    // --- Fonctions utilitaires (inchangées) ---
     displayError(message) {
         if (this.hasErrorDisplayTarget && this.element.isConnected) {
             this.errorDisplayTarget.textContent = message;
         } else {
            // console.warn(`Modal Dynamic (${this.urlValue}): No errorDisplay target / Element disconnected. Error: ${message}`);
         }
     }
     clearErrors() {
         if (this.hasErrorDisplayTarget && this.element.isConnected) {
             this.errorDisplayTarget.textContent = '';
         }
     }
     setLoadingState(isLoading) { /* ... */ }
     setSubmitButtonState(isSubmitting) { /* ... */ }
}