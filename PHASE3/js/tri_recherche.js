document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.destination-recherche');
    const form = document.getElementById('form-filtrage');
    const selectTri = document.getElementById('tri');

    // Fonction d'affichage des cartes voyages
    function afficherVoyages(voyagesAAfficher) {
        container.innerHTML = '';

        voyagesAAfficher.forEach(v => {
            const carte = document.createElement('a');
            carte.href = `voyage_detail.php?id=${v.id}`;
            carte.className = 'destination';
            carte.dataset.prix = v.prix_total;
            carte.dataset.duree = v.duree;
            carte.dataset.note = v.note;

            carte.innerHTML = `
                <img src="${v.image}" alt="Image de ${v.titre}">
                <h3>${v.titre}</h3>
                <p>${v.description}</p>
                <p>${v.prix_total} €</p>
                <p>Note : ${'⭐'.repeat(v.note)}</p>
            `;
            container.appendChild(carte);
        });
    }

    // Fonction pour filtrer les voyages selon les champs du formulaire
    function appliquerFiltresEtTri() {
        const titre = form.querySelector('[name="titre"]').value.toLowerCase();
        const noteMin = parseInt(form.querySelector('[name="note_min"]').value) || 0;
        const prixMin = parseFloat(form.querySelector('[name="prix_min"]').value) || 0;
        const prixMax = parseFloat(form.querySelector('[name="prix_max"]').value) || Infinity;
        const dureeMax = parseInt(form.querySelector('[name="duree_max"]').value) || Infinity;
        const theme = form.querySelector('[name="theme"]').value;

        let filtres = voyages.filter(v => {
            return (
                (!titre || v.titre.toLowerCase().includes(titre)) &&
                v.note >= noteMin &&
                v.prix_total >= prixMin &&
                v.prix_total <= prixMax &&
                v.duree <= dureeMax &&
                (!theme || v.theme === theme)
            );
        });

        // Trie si sélectionné
        const critere = selectTri.value;
        if (critere) {
            const getValeur = (el, key) => parseFloat(el[key]) || 0;
            filtres.sort((a, b) => {
                switch (critere) {
                    case 'prix': return getValeur(a, 'prix_total') - getValeur(b, 'prix_total');
                    case 'prix_desc': return getValeur(b, 'prix_total') - getValeur(a, 'prix_total');
                    case 'duree': return getValeur(a, 'duree') - getValeur(b, 'duree');
                    case 'duree_desc': return getValeur(b, 'duree') - getValeur(a, 'duree');
                    case 'note': return getValeur(a, 'note') - getValeur(b, 'note');
                    case 'note_desc': return getValeur(b, 'note') - getValeur(a, 'note');
                }
            });
        }

        afficherVoyages(filtres);
    }

    // Empêche le formulaire de recharger la page
    form.addEventListener('submit', e => {
        e.preventDefault();
        appliquerFiltresEtTri();
    });

    // Filtrage dynamique à chaque saisie ou changement
    form.querySelectorAll('input, select').forEach(elem => {
        elem.addEventListener('input', appliquerFiltresEtTri);
    });

    // Tri dynamique
    selectTri.addEventListener('change', appliquerFiltresEtTri);

    // Affichage initial
    afficherVoyages(voyages);
});

