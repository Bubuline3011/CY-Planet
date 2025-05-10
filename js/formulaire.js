document.addEventListener("DOMContentLoaded", () => {
  // Récupération du formulaire
  const form = document.querySelector("form");

  if (!form) return;

  // Affichage/masquage mot de passe
  form.querySelectorAll(".toggle-password").forEach((btn) => {
  btn.addEventListener("click", () => {
    const input = btn.parentElement.querySelector("input[type='password'], input[type='text']");
    const isVisible = input.type === "text";
    input.type = isVisible ? "password" : "text";
    btn.textContent = isVisible ? "👁️" : "🙈"; // bonus : changement de l’icône
  });
});


  // Compteurs dynamiques pour les champs limités
  form.querySelectorAll("input[maxlength]").forEach((input) => {
    const max = input.getAttribute("maxlength");
    const counter = document.createElement("small");
    counter.textContent = `0 / ${max}`;
    input.parentElement.appendChild(counter);

    input.addEventListener("input", () => {
      counter.textContent = `${input.value.length} / ${max}`;
    });
  });

  // Validation à la soumission
  form.addEventListener("submit", (e) => {
    let valid = true;
    let messages = [];

    // Réinitialise les messages d’erreur
    form.querySelectorAll(".erreur").forEach(el => el.remove());

    const email = form.querySelector("input[name='email']");
    if (email && !/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/.test(email.value)) {
      valid = false;
      messages.push({ field: email, message: "Adresse email invalide." });
    }

    const password = form.querySelector("input[name='motdepasse'], input[name='password']");
    if (password && password.value.length < 6) {
      valid = false;
      messages.push({ field: password, message: "Mot de passe trop court (min 6 caractères)." });
    }

    const pseudo = form.querySelector("input[name='prenom']");
    if (pseudo && pseudo.value.length < 2) {
      valid = false;
      messages.push({ field: pseudo, message: "Prénom trop court." });
    }

    const nom = form.querySelector("input[name='nom']");
    if (nom && nom.value.length < 2) {
      valid = false;
      messages.push({ field: nom, message: "Nom trop court." });
    }

    const age = form.querySelector("input[name='age']");
    if (age && (!/^[0-9]+$/.test(age.value) || parseInt(age.value) <= 0)) {
      valid = false;
      messages.push({ field: age, message: "Âge invalide." });
    }

    const tel = form.querySelector("input[name='telephone']");
    if (tel && !/^[0-9]{10}$/.test(tel.value)) {
      valid = false;
      messages.push({ field: tel, message: "Téléphone invalide (10 chiffres attendus)." });
    }

    // Si erreurs, on bloque l’envoi
    if (!valid) {
      e.preventDefault();

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

