<?php
// views/login.php

$errorMessage = isset($errorMessage) ? $errorMessage : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole de LA REUSSITE</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Muli&display=swap");

* {
  box-sizing: border-box;
}

body {
  font-family: "Muli", sans-serif;
  /*background-color: steelblue;*/
  color: gray;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  overflow: hidden;
  margin: 0;
}

.error-message {
  color: red;
    font-size: 14px;
    margin-top: 10px;
    display: block; 
}

.container {
  background-color: #ffffff;
  padding: 20px 40px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow */
  border-radius: 15px;
}

.container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 40%; /* Bande s'étend jusqu'à la moitié du formulaire */
    background-color: #136ad5;
    z-index: -1; /* Assurez-vous que la bande est derrière le formulaire */
}

.logo {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 150px; /* Ajustez la taille du logo selon vos besoins */
}

.container h1 {
  text-align: center;
  margin-bottom: 30px;
  color: #136ad5;
}

.container a {
  text-decoration: none;
  color: #136ad5;
}

.btn {
  cursor: pointer;
  display: inline-block;
  width: 100%;
  color: #fff;
  background: #136ad5;
  padding: 15px;
  font-family: inherit;
  font-size: 16px;
  border: 0;
  border-radius: 5px;
}

.btn:hover {
    background-color: #4f81c7;
  }

.btn:focus {
  outline: 0;
}

.btn:active {
  transform: scale(0.98);
}

.text {
  margin-top: 30px;
}

.form-control {
  position: relative;
  margin: 20px 0 40px;
  width: 300px;
}

.form-control input {
    background-color: transparent;
    border-radius: 5px;
    border: 2px #d9d9d9 solid;
    display: block;
    width: 100%;
    padding: 15px 0;
    padding-left: 10px; /* Ajouter padding-left pour les placeholders */
    font-size: 15px;
    color: gray;
    border-bottom: 2px solid #d9d9d9; /* Couleur par défaut de la bordure inférieure */
}

.form-control input.error {
    border-bottom: 2px solid red; /* Couleur de la bordure inférieure pour les erreurs */
  }

.form-control input:focus {
    outline: none; /* Supprime le contour par défaut du navigateur */
    border-bottom-color: #136ad5; /* Couleur de la bordure inférieure quand le champ est actif */
  }

.form-control input::placeholder {
    color: gray; /* Couleur du texte du placeholder */
    opacity: 1; /* Assurez-vous que le texte est opaque */
  }


    </style>

</head>
<body>
    <div class="container">
        <img src="images/logo.PNG" class="logo" alt="Logo">
        <h1>Connexion</h1>

        <!-- Overlay pour le message de succès -->
    <div class="overlay" id="successOverlay">
        <?php if (!empty($successMessage)): ?>
            <div class="success-message" id="successMessage">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
    </div>



        <form id="loginForm" method="post">
            <div class="form-control">
                <input type="email" name="email"  id="email" placeholder="Email"  />
            </div>
            <div class="form-control">
                <input type="password" name="password" id="password" placeholder="Mot de passe" maxlength="6"  />
            </div>
            <button type="submit" class="btn">Se connecter</button>
         
      </form>
    </div>
</body>
<script src="js/script.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
            const successOverlay = document.getElementById('successOverlay');
            <?php if (!empty($successMessage)): ?>
                successOverlay.style.display = 'flex'; // Afficher l'overlay
                setTimeout(function() {
                    successOverlay.style.display = 'none'; // Masquer l'overlay après 3 secondes
                }, 3000); // Vous pouvez ajuster la durée
            <?php endif; ?>
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

</script>
</html>