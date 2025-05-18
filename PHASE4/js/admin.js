// Attend que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne tous les menus déroulants de rôle
    const selectRoles = document.querySelectorAll('.select-role');

    selectRoles.forEach(select => {
        select.addEventListener('change', () => {
            const email = select.dataset.email;
            const nouveauRole = select.value;

            // Récupère la ligne du tableau concernée
            const row = select.closest('tr');
            // Récupère le loader dans la cellule correspondante
            const loader = row.querySelector('.loader');

            // Affiche le loader
            loader.style.display = 'inline-block';
            select.disabled = true;

            // Envoie une requête AJAX en POST
            fetch('modifier_utilisateur.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&role=${encodeURIComponent(nouveauRole)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`✅ Rôle de ${email} mis à jour en "${nouveauRole}"`);
                } else {
                    console.error(`❌ Erreur : ${data.message}`);
                    alert("Erreur lors de la mise à jour");
                }
            })
            .catch(err => {
                console.error("❌ Erreur AJAX :", err);
                alert("Erreur réseau lors de la mise à jour");
            })
            .finally(() => {
                // Cache le loader et réactive le select
                loader.style.display = 'none';
                select.disabled = false;
            });
        });
    });
});

