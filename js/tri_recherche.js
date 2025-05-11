// Attend que le DOM soit complètement chargé avant d’exécuter le script
document.addEventListener('DOMContentLoaded', function () {
    // Récupère la liste déroulante de tri
    const selectTri = document.getElementById('tri');
    // Si aucun élément de tri n’est trouvé, on arrête le script
    if (!selectTri) return;

    // Écoute le changement de valeur du menu de tri
    selectTri.addEventListener('change', function () {
        // Récupère la valeur sélectionnée (ex: 'prix', 'duree_desc', etc.)
        const critere = this.value;
        // Récupère le conteneur des cartes de destination
        const container = document.querySelector('.destination-recherche');
        // Convertit les éléments .destination en tableau pour les trier
        const cartes = Array.from(container.querySelectorAll('.destination'));

        // Fonction utilitaire pour extraire une valeur numérique depuis un data-attribute
        const getValeur = (el, attr) => parseFloat(el.dataset[attr]);

        // Trie les cartes selon le critère choisi
        cartes.sort((a, b) => {
            switch (critere) {
                case 'prix': return getValeur(a, 'prix') - getValeur(b, 'prix'); // Tri croissant par prix
                case 'prix_desc': return getValeur(b, 'prix') - getValeur(a, 'prix'); // Tri décroissant par prix
                case 'duree': return getValeur(a, 'duree') - getValeur(b, 'duree'); // Tri croissant par durée
                case 'duree_desc': return getValeur(b, 'duree') - getValeur(a, 'duree'); // Tri décroissant par durée
                case 'note': return getValeur(a, 'note') - getValeur(b, 'note'); // Tri croissant par note
                case 'note_desc': return getValeur(b, 'note') - getValeur(a, 'note'); // Tri décroissant par note
                default: return 0; // Aucun tri si critère inconnu
            }
        });

        // Vide le conteneur avant de réinsérer les cartes triées
        container.innerHTML = '';
        // Réinsère les cartes dans le bon ordre
        cartes.forEach(carte => container.appendChild(carte));
    });
});
