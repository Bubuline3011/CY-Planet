// Attend que le DOM soit complètement chargé avant d’exécuter le script
document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne tous les boutons ayant la classe 'role-btn'
    const buttons = document.querySelectorAll('.role-btn');

    // Parcourt chaque bouton sélectionné
    buttons.forEach(btn => {
        // Ajoute un écouteur d’événement au clic sur le bouton
        btn.addEventListener('click', () => {
            // Trouve la ligne (tr) parente la plus proche du bouton
            const row = btn.closest('tr');
            // Récupère l’attribut 'data-email' de la ligne
            const email = row.getAttribute('data-email');
            // Récupère l’attribut 'data-role' du bouton
            const role = btn.getAttribute('data-role');

            // Désactive le bouton pour empêcher d’autres clics
            btn.disabled = true;
            // Change le texte du bouton pour indiquer une mise à jour
            btn.textContent = 'Mise à jour...';

            // Simulation d’attente (3 secondes)
            setTimeout(() => {
                // Réactive le bouton après la simulation
                btn.disabled = false;
                // Met à jour le texte du bouton avec le rôle (première lettre en majuscule)
                btn.textContent = role.charAt(0).toUpperCase() + role.slice(1);

                // Affiche dans la console (ou alert pour test)
                console.log(`Simulé : ${email} devient ${role}`);
                // alert(`Utilisateur ${email} devient ${role}`);
            }, 3000); // Délai de 3000 millisecondes (3 secondes)
        });
    });
});
