document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.role-btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const email = row.getAttribute('data-email');
            const role = btn.getAttribute('data-role');

            btn.disabled = true;
            btn.textContent = 'Mise à jour...';

            // Simulation d’attente (3 secondes)
            setTimeout(() => {
                btn.disabled = false;
                btn.textContent = role.charAt(0).toUpperCase() + role.slice(1);

                // Affiche dans la console (ou alert pour test)
                console.log(`Simulé : ${email} devient ${role}`);
                // alert(`Utilisateur ${email} devient ${role}`);
            }, 3000);
        });
    });
});
