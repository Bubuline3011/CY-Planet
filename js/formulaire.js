// Attend que le DOM soit complètement chargé avant d’exécuter le script
document.addEventListener("DOMContentLoaded", () => {
  // Récupération du formulaire présent dans la page
  const form = document.querySelector("form");

  // Si aucun formulaire n’est trouvé, on arrête l’exécution
  if (!form) return;

  // Affichage/masquage du mot de passe (icone œil)
  form.querySelectorAll(".toggle-password").forEach((btn) => {
    // Ajoute un événement de clic à chaque bouton d’affichage du mot de passe
    btn.addEventListener("click", () => {
      // Recherche l’input de mot de passe ou texte dans le même parent
      const input = btn.parentElement.querySelector("input[type='password'], input[type='text']");
      // Détermine si le mot de passe est actuellement visible
      const isVisible = input.type === "text";
      // Alterne le type entre 'password' et 'text'
      input.type = isVisible ? "password" : "text";
      // Met à jour l’icône du bouton selon la visibilité
      btn.textContent = isVisible ? "👁️" : "🙈"; // bonus : changement de l’icône
    });
  });

  // Gestion des compteurs dynamiques pour les champs avec une longueur maximale
  form.querySelectorAll("input[maxlength]").forEach((input) => {
    // Récupère la valeur maximale définie dans l’attribut maxlength
    const max = input.getAttribute("maxlength");
    // Crée un élément <small> pour afficher le compteur
    const counter = document.createElement("small");
    counter.textContent = `0 / ${max}`;
    // Ajoute le compteur dans le DOM, juste après l’input
    input.parentElement.appendChild(counter);

    // Met à jour le compteur à chaque saisie
    input.addEventListener("input", () => {
      counter.textContent = `${input.value.length} / ${max}`;
    });
  });

  // Validation des champs lors de la soumission du formulaire
  form.addEventListener("submit", (e) => {
    let valid = true; // indicateur global de validité
    let messages = []; // tableau pour stocker les messages d’erreur

    // Supprime tous les messages d’erreur existants
    form.querySelectorAll(".erreur").forEach(el => el.remove());

    // Validation de l’email
    const email = form.querySelector("input[name='email']");
    if (email && !/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/.test(email.value)) {
      valid = false;
      messages.push({ field: email, message: "Adresse email invalide." });
    }

    // Validation du mot de passe (au moins 6 caractères)
    const password = form.querySelector("input[name='motdepasse'], input[name='password']");
    if (password && password.value.length < 6) {
      valid = false;
      messages.push({ field: password, message: "Mot de passe trop court (min 6 caractères)." });
    }

    // Validation du prénom (au moins 2 caractères)
    const pseudo = form.querySelector("input[name='prenom']");
    if (pseudo && pseudo.value.length < 2) {
      valid = false;
      messages.push({ field: pseudo, message: "Prénom trop court." });
    }

    // Validation du nom (au moins 2 caractères)
    const nom = form.querySelector("input[name='nom']");
    if (nom && nom.value.length < 2) {
      valid = false;
      messages.push({ field: nom, message: "Nom trop court." });
    }

    // Validation de l’âge (doit être un nombre strictement positif)
    const age = form.querySelector("input[name='age']");
    if (age && (!/^[0-9]+$/.test(age.value) || parseInt(age.value) <= 0)) {
      valid = false;
      messages.push({ field: age, message: "Âge invalide." });
    }

    // Validation du numéro de téléphone (exactement 10 chiffres)
    const tel = form.querySelector("input[name='telephone']");
    if (tel && !/^[0-9]{10}$/.test(tel.value)) {
      valid = false;
      messages.push({ field: tel, message: "Téléphone invalide (10 chiffres attendus)." });
    }

    // Si des erreurs ont été détectées, empêche l’envoi du formulaire
    if (!valid) {
      e.preventDefault(); // Bloque l’envoi du formulaire

      // Affiche chaque message d’erreur sous le champ concerné
      messages.forEach(({ field, message }) => {
        const msg = document.createElement("p");
        msg.classList.add("erreur");
        msg.style.color = "red";
        msg.textContent = message;
        field.parentElement.appendChild(msg);
      });
    }
  });
});
