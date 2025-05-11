// Attend que le DOM soit complètement chargé avant d’exécuter le script
document.addEventListener("DOMContentLoaded", function () {
    // Récupère tous les champs de saisie dans les éléments ayant la classe .profil-champ
    const champs = document.querySelectorAll(".profil-champ input");
    // Récupère tous les boutons de modification (✎)
    const modifierBtns = document.querySelectorAll(".modifier-btn");
    // Récupère le bouton "Soumettre"
    const boutonSoumettre = document.getElementById("bouton-soumettre");

    // Objet pour stocker les valeurs originales des champs
    let valeursOriginales = {};
    // Ensemble pour suivre les champs en cours de modification validée
    let modificationsEnCours = new Set();

    // Stocke les valeurs d’origine des champs identifiés par leur attribut name
    champs.forEach((champ) => {
        if (champ.name) {
            valeursOriginales[champ.name] = champ.value;
        }
    });

    // Vérifie si des modifications ont été validées pour afficher ou non le bouton "Soumettre"
    function verifierModifications() {
        if (modificationsEnCours.size > 0) {
            boutonSoumettre.style.display = "block";
        } else {
            boutonSoumettre.style.display = "none";
        }
    }

    // Fonction pour créer les boutons de validation (✔) et d’annulation (✖)
    function creerBoutonsValidation(champ, parent) {
        // Supprime les boutons de validation/annulation déjà existants pour éviter les doublons
        parent.querySelectorAll(".valider-btn, .annuler-btn").forEach(b => b.remove());

        // Création du bouton ✔
        const validerBtn = document.createElement("button");
        validerBtn.textContent = "✔";
        validerBtn.classList.add("valider-btn");
        validerBtn.title = "Valider";
        validerBtn.style.marginLeft = "5px";

        // Création du bouton ✖
        const annulerBtn = document.createElement("button");
        annulerBtn.textContent = "✖";
        annulerBtn.classList.add("annuler-btn");
        annulerBtn.title = "Annuler";
        annulerBtn.style.marginLeft = "5px";

        // Ajout des boutons juste après le champ ou le bouton ✎
        parent.appendChild(validerBtn);
        parent.appendChild(annulerBtn);

        // Action quand on clique sur ✔
        validerBtn.addEventListener("click", () => {
            // Rends le champ à nouveau en lecture seule
            champ.setAttribute("readonly", true);
            // Marque ce champ comme modifié
            if (champ.name) modificationsEnCours.add(champ.name);
            // Vérifie si le bouton "Soumettre" doit être affiché
            verifierModifications();
            // Supprime les boutons ✔ et ✖
            validerBtn.remove();
            annulerBtn.remove();
        });

        // Action quand on clique sur ✖
        annulerBtn.addEventListener("click", () => {
            // Rétablit la valeur d’origine du champ
            if (champ.name) champ.value = valeursOriginales[champ.name];
            // Remet le champ en lecture seule
            champ.setAttribute("readonly", true);
            // Retire le champ de la liste des modifications en cours
            if (champ.name) modificationsEnCours.delete(champ.name);
            // Vérifie si le bouton "Soumettre" doit être caché
            verifierModifications();
            // Supprime les boutons ✔ et ✖
            validerBtn.remove();
            annulerBtn.remove();
        });
    }

    // Associe un événement clic à chaque bouton ✎ de modification
    modifierBtns.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            const champ = champs[index]; // Associe chaque bouton au champ correspondant
            champ.removeAttribute("readonly"); // Rend le champ modifiable
            champ.focus(); // Met le focus sur le champ
            creerBoutonsValidation(champ, btn.parentElement); // Ajoute les boutons ✔ et ✖
        });
    });

    // Au chargement initial, masque le bouton "Soumettre"
    verifierModifications();
});
