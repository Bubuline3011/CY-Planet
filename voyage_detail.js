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
document.addEventListener("DOMContentLoaded", function () {
    
    // Fonction pour charger dynamiquement les options
    function chargerOptions(voyage, etapeId) {
        fetch(`PHASE4/fetch_options.php?voyage=${voyage}&etape=${etapeId}`)
            .then(response => response.json())
            .then(data => {
                // Sélection du conteneur des options
                const selectContainer = document.getElementById(`options_etape_${etapeId}`);
                selectContainer.innerHTML = ''; // On vide le contenu existant
                
                // Pour chaque option, on génère une liste déroulante
                data.forEach(option => {
                    const select = document.createElement('select');
                    select.id = `option_${etapeId}_${option.id}`;
                    select.classList.add('form-select');

                    option.valeurs_possibles.forEach(valeur => {
                        const opt = document.createElement('option');
                        opt.value = valeur;
                        opt.textContent = valeur;
                        opt.setAttribute('data-prix', option.prix_par_valeur[valeur]);
                        select.appendChild(opt);
                    });

                    selectContainer.appendChild(select);

                    // Ajout d'un écouteur pour le recalcul du prix si on change d'option
                    select.addEventListener('change', recalculerPrixTotal);
                });

                // On recalcul le prix au cas où les options par défaut changent
                recalculerPrixTotal();
            });
    }

    // Chargement initial lors de l'affichage de l'étape
    document.querySelectorAll(".etape-container").forEach(container => {
        const etapeId = container.dataset.etapeId;
        const voyage = document.getElementById("voyage_id").value;
        chargerOptions(voyage, etapeId);
    });
});
