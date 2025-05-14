document.addEventListener('DOMContentLoaded', () => {
    const voyages = [];
    let triActuel = '';

    // Récupère tous les voyages au chargement
    fetch('api_recherche.php')
        .then(res => res.json())
        .then(data => {
            voyages.push(...data);
            appliquerFiltres();
        });

    // Sélecteurs
    const rechercheInput = document.getElementById('recherche-input');
    const filtreTheme = document.getElementById('filtre-theme');
    const filtreTransport = document.getElementById('filtre-transport');
    const filtrePrixMin = document.getElementById('filtre-prix-min');
    const filtrePrixMax = document.getElementById('filtre-prix-max');
    const filtreDureeMax = document.getElementById('filtre-duree-max');
    const filtreNote = document.getElementById('filtre-note');
    const resetFiltresBtn = document.getElementById('reset-filtres');
    const resultatsDiv = document.getElementById('resultats');
    const triButtons = document.querySelectorAll('.actions-tri button');

    function appliquerFiltres() {
        let filtres = voyages.slice();

        // Recherche texte
        const search = rechercheInput.value.trim().toLowerCase();
        const url = new URL(window.location);
        if (search) {
            url.searchParams.set('q', search);
            filtres = filtres.filter(v => v.titre.toLowerCase().includes(search));
        } else {
            url.searchParams.delete('q');
        }
        history.replaceState(null, '', url);

        // Filtres
        if (filtreTheme.value) filtres = filtres.filter(v => v.theme === filtreTheme.value);
        if (filtreTransport.value) filtres = filtres.filter(v => v.transport === filtreTransport.value);
        if (filtrePrixMin.value) filtres = filtres.filter(v => v.prix_total >= parseInt(filtrePrixMin.value));
        if (filtrePrixMax.value) filtres = filtres.filter(v => v.prix_total <= parseInt(filtrePrixMax.value));
        if (filtreDureeMax.value) filtres = filtres.filter(v => v.duree <= parseInt(filtreDureeMax.value));
        if (filtreNote.value) filtres = filtres.filter(v => v.note >= parseInt(filtreNote.value));

        // Tri
        if (triActuel === 'prix') filtres.sort((a, b) => a.prix_total - b.prix_total);
        if (triActuel === 'duree') filtres.sort((a, b) => a.duree - b.duree);
        if (triActuel === 'note') filtres.sort((a, b) => b.note - a.note);

        afficherVoyages(filtres);
    }

    function afficherVoyages(voyages) {
        resultatsDiv.innerHTML = '';
        if (voyages.length === 0) {
            resultatsDiv.innerHTML = '<p>Aucun résultat.</p>';
            return;
        }
        voyages.forEach(voyage => {
            resultatsDiv.innerHTML += `
                <a href="voyage_detail.php?id=${voyage.id}" class="destination">
                    <img src="${voyage.image}" alt="Image de ${voyage.titre}">
                    <h3>${voyage.titre}</h3>
                    <p>${voyage.description}</p>
                    <p>${voyage.prix_total} €</p>
                    <p>Note : ${'⭐'.repeat(voyage.note)}</p>
                </a>
            `;
        });
    }

    // Rafraîchir à chaque changement de filtre
    [rechercheInput, filtreTheme, filtreTransport, filtrePrixMin, filtrePrixMax, filtreDureeMax, filtreNote].forEach(el => {
        el.addEventListener('input', appliquerFiltres);
    });

    // Tri dynamique
    triButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            triActuel = btn.dataset.tri;
            appliquerFiltres();
        });
    });

    // Reset
    resetFiltresBtn.addEventListener('click', () => {
        rechercheInput.value = '';
        filtreTheme.value = '';
        filtreTransport.value = '';
        filtrePrixMin.value = '';
        filtrePrixMax.value = '';
        filtreDureeMax.value = '';
        filtreNote.value = '';
        triActuel = '';
        appliquerFiltres();
    });
});
