// Fonction pour créer ou mettre à jour un cookie
function setCookie(name, value, days = 30) {
  const d = new Date();
  d.setTime(d.getTime() + (days*24*60*60*1000));
  const expires = "expires="+ d.toUTCString();
  document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Fonction pour lire un cookie
function getCookie(name) {
  const cname = name + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');
  for(let c of ca) {
    while (c.charAt(0) === ' ') c = c.substring(1);
    if (c.indexOf(cname) === 0) return c.substring(cname.length, c.length);
  }
  return "";
}

// Fonction pour changer de thème
function changerTheme() {
  const link = document.getElementById("theme-css");
  const actuel = link.getAttribute("href");

  const nouveau = actuel.includes("style_sombre.css") ? "style.css" : "style_sombre.css";
  link.setAttribute("href", nouveau);
  setCookie("theme", nouveau);
}

// Appliquer automatiquement le thème choisi
window.addEventListener("DOMContentLoaded", () => {
  const savedTheme = getCookie("theme");
  const link = document.getElementById("theme-css");

  if (savedTheme && (savedTheme === "style.css" || savedTheme === "style_sombre.css")) {
    link.setAttribute("href", savedTheme);
  } else {
    setCookie("theme", "style.css");
  }

  // Ajout d’un bouton si tu veux l'insérer dynamiquement
  const bouton = document.getElementById("bouton-theme");
  if (bouton) {
    bouton.addEventListener("click", changerTheme);
  }
});

