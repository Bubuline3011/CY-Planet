document.addEventListener('DOMContentLoaded', function () {
    const selectTri = document.getElementById('tri');
    if (!selectTri) return;

    selectTri.addEventListener('change', function () {
        const critere = this.value;
        const container = document.querySelector('.destination-recherche');
        const cartes = Array.from(container.querySelectorAll('.destination'));

        const getValeur = (el, attr) => parseFloat(el.dataset[attr]);

        cartes.sort((a, b) => {
            switch (critere) {
                case 'prix': return getValeur(a, 'prix') - getValeur(b, 'prix');
                case 'prix_desc': return getValeur(b, 'prix') - getValeur(a, 'prix');
                case 'duree': return getValeur(a, 'duree') - getValeur(b, 'duree');
                case 'duree_desc': return getValeur(b, 'duree') - getValeur(a, 'duree');
                case 'note': return getValeur(a, 'note') - getValeur(b, 'note');
                case 'note_desc': return getValeur(b, 'note') - getValeur(a, 'note');
                default: return 0;
            }
        });

        container.innerHTML = '';
        cartes.forEach(carte => container.appendChild(carte));
    });
});

