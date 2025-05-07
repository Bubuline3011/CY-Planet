document.addEventListener("DOMContentLoaded", function () {
    const champs = document.querySelectorAll(".profil-champ input");
    const modifierBtns = document.querySelectorAll(".modifier-btn");
    const boutonSoumettre = document.getElementById("bouton-soumettre");

    let valeursOriginales = {};
    let modificationsEnCours = new Set();

    // Stocke les valeurs d'origine
    champs.forEach((champ) => {
        if (champ.name) {
            valeursOriginales[champ.name] = champ.value;
        }
    });

    // Vérifie si au moins un champ a été modifié
    function verifierModifications() {
        if (modificationsEnCours.size > 0) {
            boutonSoumettre.style.display = "block";
        } else {
            boutonSoumettre.style.display = "none";
        }
    }

    // Crée les boutons ✔ et ✖
    function creerBoutonsValidation(champ, parent) {
        // Supprime les boutons existants s'il y en a déjà
        parent.querySelectorAll(".valider-btn, .annuler-btn").forEach(b => b.remove());

        const validerBtn = document.createElement("button");
        validerBtn.textContent = "✔";
        validerBtn.classList.add("valider-btn");
        validerBtn.title = "Valider";
        validerBtn.style.marginLeft = "5px";

        const annulerBtn = document.createElement("button");
        annulerBtn.textContent = "✖";
        annulerBtn.classList.add("annuler-btn");
        annulerBtn.title = "Annuler";
        annulerBtn.style.marginLeft = "5px";

        parent.appendChild(validerBtn);
        parent.appendChild(annulerBtn);

        // Action valider
        validerBtn.addEventListener("click", () => {
            champ.setAttribute("readonly", true);
            if (champ.name) modificationsEnCours.add(champ.name);
            verifierModifications();
            validerBtn.remove();
            annulerBtn.remove();
        });

        // Action annuler
        annulerBtn.addEventListener("click", () => {
            if (champ.name) champ.value = valeursOriginales[champ.name];
            champ.setAttribute("readonly", true);
            if (champ.name) modificationsEnCours.delete(champ.name);
            verifierModifications();
            validerBtn.remove();
            annulerBtn.remove();
        });
    }

    // Clique sur ✎
    modifierBtns.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            const champ = champs[index];
            champ.removeAttribute("readonly");
            champ.focus();
            creerBoutonsValidation(champ, btn.parentElement);
        });
    });

    // Masquer le bouton "Soumettre" au début
    verifierModifications();
});

