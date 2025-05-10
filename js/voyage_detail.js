document.addEventListener("DOMContentLoaded", function () {
    function recalculerPrixTotal() {
        let total = 0;

        // Parcours tous les blocs contenant une option
        document.querySelectorAll("select[id^='option_']").forEach(function (select) {
            const selectedOption = select.options[select.selectedIndex];
            const dataPrix = selectedOption.getAttribute("data-prix");

            if (dataPrix !== null) {
                const prixUnitaire = parseFloat(dataPrix) || 0;

                // Trouver le champ associé au nombre de personnes
                const selectIdParts = select.id.split("_"); // ex : "option_0_1"
                const inputId = "nb_" + selectIdParts[1] + "_" + selectIdParts[2];
                const inputNb = document.getElementById(inputId);
                const nbPersonnes = inputNb ? parseInt(inputNb.value) || 0 : 0;

                total += prixUnitaire * nbPersonnes;
            }
        });

        // Mise à jour de l'affichage
        const affichage = document.getElementById("valeur-prix-estime");
        if (affichage) {
            affichage.textContent = total.toFixed(2);
        }
    }

    // Événements sur tous les select et inputs de nombre
    document.querySelectorAll("select[id^='option_'], input[type='number'][id^='nb_']").forEach(function (el) {
        el.addEventListener("change", recalculerPrixTotal);
        el.addEventListener("input", recalculerPrixTotal);
    });

    // Recalcul initial
    recalculerPrixTotal();
});

