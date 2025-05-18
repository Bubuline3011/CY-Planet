document.addEventListener("DOMContentLoaded", function () {
    const champs = document.querySelectorAll(".profil-champ input"); // Récupère les champs
    const modifierBtns = document.querySelectorAll(".modifier-btn"); // Récupère les boutons de modification
    const boutonSoumettre = document.getElementById("bouton-soumettre"); // Bouton de soumission

    // Objet pour stocker les valeurs originales des champs
    let valeursOriginales = {};
    // Ensemble pour suivre les champs modifiés validés
    let modificationsEnCours = new Set();

    // Stocke les valeurs d'origine des champs
    champs.forEach((champ) => {
        if (champ.name) {
            valeursOriginales[champ.name] = champ.value;
        }
    });

    // Vérifie si des modifications ont été validées pour afficher ou non le bouton "Soumettre"
    function verifierModifications() {
        if (modificationsEnCours.size > 0) {
            boutonSoumettre.style.display = "block"; // Affiche le bouton "Soumettre"
        } else {
            boutonSoumettre.style.display = "none"; // Masque le bouton si aucune modification
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
            champ.setAttribute("readonly", true); // Rend le champ non modifiable
            if (champ.name) modificationsEnCours.add(champ.name); // Marque ce champ comme modifié
            verifierModifications(); // Vérifie si le bouton Soumettre doit être affiché
            validerBtn.remove();
            annulerBtn.remove();
        });

        // Action quand on clique sur ✖
        annulerBtn.addEventListener("click", () => {
            champ.value = valeursOriginales[champ.name]; // Rétablit la valeur d’origine
            champ.setAttribute("readonly", true); // Remet le champ en lecture seule
            modificationsEnCours.delete(champ.name); // Retire le champ de la liste des modifications
            verifierModifications();
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

    // Fonction pour soumettre les modifications via AJAX avec fetch()
    function envoyerModifications() {
        const formData = new FormData();
        champs.forEach((champ) => {
            if (champ.name && champ.value !== valeursOriginales[champ.name]) {
                formData.append(champ.name, champ.value); // Ajoute les données modifiées
            }
        });

        // Envoie les données via fetch
        fetch("traitement_profil.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                alert("Les informations ont été mises à jour avec succès !");
                window.location.reload(); // Recharger la page pour afficher les nouvelles données
            } else {
                alert("Une erreur est survenue, veuillez réessayer.");
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
            alert("Une erreur est survenue, veuillez réessayer.");
        });
    }

    // Associe un événement de soumission du formulaire avec AJAX
    boutonSoumettre.addEventListener("click", function (e) {
        e.preventDefault(); // Empêche le rechargement de la page
        envoyerModifications(); // Envoie les modifications via AJAX
    });

    // Masque le bouton "Soumettre" au chargement initial
    verifierModifications();
});

