window.openModal = async function(btn, url){
    // *** Déclenchez l'événement personnalisé ***
    const event = new CustomEvent('open-modal', {
        detail: { url: url } // Passez l'URL dans 'detail'
    });
    document.dispatchEvent(event); 
}
