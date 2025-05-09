document.addEventListener("DOMContentLoaded", function () {
    function recalculerPrixTotal() {
        let total = 0;

        // Pour chaque div contenant une option
        document.querySelectorAll("div").forEach(function (bloc) {
            const select = bloc.querySelector("select");
            const inputNb = bloc.querySelector("input[type='number']");

            if (select && inputNb) {
                const selectedOption = select.value;
                const nbPersonnes = parseInt(inputNb.value) || 0;

                // On récupère le prix de l'option sélectionnée via un attribut data-* si possible
                const prixParValeur = select.options[select.selectedIndex].getAttribute("data-prix");

                let prix = 0;
                if (prixParValeur) {
                    prix = parseFloat(prixParValeur) * nbPersonnes;
                } else {
                    // Sinon on peut insérer une logique par défaut ou ignorer
                }

                total += prix;
            }
        });

        // Mise à jour de l'affichage
        const affichage = document.getElementById("valeur-prix-estime");
        if (affichage) {
            affichage.textContent = total.toFixed(2);
        }
    }

    // Ajouter les attributs data-prix depuis PHP (recommandé)
    // Si ce n'est pas fait côté PHP, le calcul ne sera pas possible sans données supplémentaires

    // Attache les événements
    document.querySelectorAll("select, input[type='number']").forEach(function (el) {
        el.addEventListener("change", recalculerPrixTotal);
    });

    // Recalcul initial
    recalculerPrixTotal();
});
