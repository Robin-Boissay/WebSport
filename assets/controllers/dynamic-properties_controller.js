// assets/controllers/dynamic_properties_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    // L'élément select du type d'activité
    static targets = ["typeSelect", "dataActivitersCollectionHolder", "dataActivitersPrototype"];
    // Le contrôleur collection-type imbriqué qui gère les dataActiviters
    static outlets = ["collection-type"]; // Référence au contrôleur 'collection-type' des dataActiviters

    static values = {
        apiUrlTemplate: String, // ex: /api/type-activiter/{id}/proprietes
        proprieteFieldName: String // ex: proprieterActiviter
    }

    connect() {
        // Écoute initiale si une valeur est déjà sélectionnée au chargement (pour l'édition)
        if (this.typeSelectTarget.value) {
            this.fetchAndPopulateProperties();
        }
    }

    // Méthode déclenchée par l'action 'change' sur le select
    typeChanged(event) {
        this.fetchAndPopulateProperties();
    }

    async fetchAndPopulateProperties() {
        const typeId = this.typeSelectTarget.value;
        console.log(typeId);

        if (!typeId) {
            this.clearProperties();
            return; // Ne rien faire si "Choisir un type" est sélectionné
        }

        // Vérifie si l'outlet vers le contrôleur collection-type est disponible
        if (!this.outlets.controllerElement) {
            console.error("Collection-type outlet for 'dataActiviters' is missing!");
            return;
        }
        console.log(this.outlets.controllerElement);
        // Récupère l'instance du contrôleur collection-type imbriqué
        const dataActivitersCollectionController = this.outlets.controllerElement;


        const url = this.apiUrlTemplateValue.replace('PLACEHOLDER_ID', typeId);
        try {

            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const proprietes = await response.json();

            this.clearProperties();
            if (proprietes.length > 0) {
                 const prototypeHtml = this.dataActivitersPrototypeTarget.dataset.prototype;
                 if (!prototypeHtml) {
                    console.error("Prototype HTML for 'dataActiviters' is missing!");
                    return;
                 }


                 // Utilise l'index géré par le contrôleur collection-type imbriqué

                let currentIndex = 0;
                proprietes.forEach(prop => {
                    let newHtml = "";

                    const prototypeName = '_dataActiviters_'; // Récupère le nom du placeholder (défini dans le FormType ou par défaut '__name__')
                    
                    
                    newHtml = prototypeHtml;

                    console.log(prototypeHtml);
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = this.updateSingleDataActiviterIndexGeneric(newHtml, currentIndex);
                    const newItem = tempDiv.firstElementChild;

                    // Trouve le select de propriété dans le nouvel élément et sélectionne la bonne valeur
                    const propSelect = newItem.querySelector(`select[name*="[${this.proprieteFieldNameValue}]"]`);
                    if (propSelect) {
                        propSelect.value = prop.id;
                    } else {
                        console.warn(`Could not find property select with name containing [${this.proprieteFieldNameValue}] in new item`);
                    }

                    // Ajoute le nouvel élément au conteneur
                    this.dataActivitersCollectionHolderTarget.appendChild(newItem);
                    currentIndex++;
                });

                 // Met à jour l'index dans le contrôleur collection-type imbriqué
                 // Informe le contrôleur qu'il doit peut-être recompter ses éléments (facultatif)
            }

        } catch (error) {
            console.error('Failed to fetch or populate properties:', error);
            this.clearProperties(); // Efface en cas d'erreur
        }
    }

    clearProperties() {
         // Vérifie si l'outlet vers le contrôleur collection-type est disponible
         if (!this.outlets.controllerElement) {
            console.error("Cannot clear properties: Collection-type outlet for 'dataActiviters' is missing!");
            return;
        }
        let dataActivitersCollectionController = this.outlets.controllerElement;

        // Vide le conteneur des dataActiviters
        this.dataActivitersCollectionHolderTarget.innerHTML = '';
        // Réinitialise l'index du contrôleur collection-type imbriqué
    }

    updateSingleDataActiviterIndexGeneric(htmlString, newIndex) {
        // 1. Créer un conteneur temporaire pour manipuler le fragment DOM
        const container = document.createElement('div');
        container.innerHTML = htmlString.trim();
        const rootElement = container.firstElementChild;
    
        if (!rootElement) {
            console.error("La chaîne HTML fournie ne semble pas valide ou est vide.");
            return htmlString;
        }
    
        // 2. Sélectionner l'élément racine et tous les descendants pertinents
        //    On sélectionne largement au début, le filtrage se fera par les regex.
        const elementsToUpdate = [
            rootElement,
            ...rootElement.querySelectorAll('[id], [name], [for]') // Sélectionne tout élément ayant ces attributs
        ];
    
        // 3. Définir les patterns Regex pour CIBLER le deuxième index (\d+)
        //    tout en s'assurant de la structure générale.
    
        // Pattern pour les underscores: (quelquechose_index1_dataActiviters_)index2(_)
        // - Groupe 1: Capture tout jusqu'à _dataActiviters_ inclus
        // - Groupe 2: Capture l'index numérique ACTUEL de dataActiviters
        // - Groupe 3: Capture le reste (à partir de l'underscore suivant)
        const underscoreRegex = /(.*_activiterExercices_\d+_dataActiviters_)(\d+)(_.*)/g;
    
        // Pattern pour les crochets: (quelquechose[index1][dataActiviters][)index2(])
        // - Groupe 1: Capture tout jusqu'à [dataActiviters][ inclus
        // - Groupe 2: Capture l'index numérique ACTUEL de dataActiviters
        // - Groupe 3: Capture le reste (à partir du crochet fermant)
        const bracketPattern = `(.*\\\[activiterExercices\\\]\\\[\\d+\\\]\\\[dataActiviters\\\]\\\[)(\\d+)(\\].*)`;
        const bracketRegex = new RegExp(bracketPattern.replace(/\\\[/g, '\\[').replace(/\\\]/g, '\\]'), 'g'); // Compile la regex avec échappement
    
        // 4. Itérer sur les éléments et mettre à jour les attributs
        elementsToUpdate.forEach(el => {
            ['id', 'name', 'for'].forEach(attrName => {
                if (el.hasAttribute(attrName)) {
                    let currentValue = el.getAttribute(attrName);
                    let newValue = currentValue;
    
                    // Appliquer les remplacements si les patterns correspondent
                    // Note: on remplace l'index capturé (Groupe 2) par newIndex
                    newValue = newValue.replace(underscoreRegex, `$1${newIndex}$3`);
                    newValue = newValue.replace(bracketRegex, `$1${newIndex}$3`);
    
                    // Mettre à jour l'attribut seulement s'il a changé
                    if (newValue !== currentValue) {
                        el.setAttribute(attrName, newValue);
                    }
                }
            });
        });
    
        // 5. Retourner le HTML de l'élément racine modifié
        return rootElement.outerHTML;
    }
}