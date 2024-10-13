document.addEventListener("DOMContentLoaded", function() {
    const successOverlay = document.getElementById('successOverlay');
});

document.getElementById('loginForm').addEventListener('submit', function(e) {
    // Récupérer les éléments du formulaire
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const errorMessage = document.getElementById('errorMessage');

    // Réinitialiser les styles d'erreur
    emailInput.classList.remove('error');
    passwordInput.classList.remove('error');

    // Réinitialiser le message d'erreur
    errorMessage.textContent = '';
    errorMessage.style.display = 'none';

    let hasError = false;

    // Vérifier si le champ email est vide ou incorrect
    if (emailInput.value === '' || !validateEmail(emailInput.value)) {
        emailInput.classList.add('error');
        errorMessage.textContent = 'Veuillez saisir un email valide.';
        errorMessage.style.display = 'block';
        hasError = true;
    }

    // Vérifier si le champ mot de passe est vide
    if (passwordInput.value === '') {
        passwordInput.classList.add('error');
        errorMessage.textContent = 'Veuillez remplir tous les champs du formulaire.';
        errorMessage.style.display = 'block';
        hasError = true;
    }

    // Empêcher la soumission du formulaire si des erreurs sont présentes
    if (hasError) {
        e.preventDefault();
    }
});

// Fonction pour valider le format de l'email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
