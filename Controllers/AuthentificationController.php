<?php
// controllers/AuthenticationController.php

require 'Models/AuthentificationModel.php';

class AuthentificationController 
{
    private $model;

    public function __construct($conn) 
    {
        $this->model = new AuthentificationModel($conn);
    }

    public function login() 
    {
        $errorMessage = []; // Initialiser un tableau pour stocker les messages d'erreur

        // Vérifiez si la méthode de requête est POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            // Vérifiez que les champs existent
            if (isset($_POST['email']) && isset($_POST['password'])) 
            {
                // Assainir et valider l'email
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                // Vérifiez que l'email est valide
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorMessage[] = "Veuillez saisir un email valide.";
                } else {
                    $admin = $this->model->getAdminByEmail($email);

                    if ($admin) {
                        // Vérification du mot de passe
                        if ($this->model->verifyPassword($password, $admin['password'])) 
                        {
                            // Connexion réussie, stocker les informations dans la session
                            $_SESSION['id_admin'] = $admin['id_admin'];
                            $_SESSION['email'] = $email;

                            // Redirection vers la page d'accueil
                            header("Location: Views/Components/Dashboard/Dashboard.php");
                            exit(); // Stoppe l'exécution après la redirection
                        } 
                        else 
                        {
                            $errorMessage[] = "Email ou mot de passe incorrect.";
                        }
                    } 
                    else 
                    {
                        $errorMessage[] = "L'utilisateur n'existe pas.";
                    }
                }
            } 
            else 
            {
                $errorMessage[] = "Veuillez remplir tous les champs du formulaire.";
            }
        }

        // Retourner les messages d'erreur, s'il y en a
        return $errorMessage;
    }

    public function logout() 
    {
        // Vérifier si une session est active
        if (session_status() == PHP_SESSION_ACTIVE) 
        {
            // Supprimer toutes les variables de session
            $_SESSION = array();

            // Si vous souhaitez aussi détruire la session côté serveur
            session_destroy();
        }

        // Redirection vers la page de connexion
        header("Location: ../Views/Authentification/login.php");
        exit(); // Stoppe l'exécution après la redirection
    }


    public function isLoggedIn() {
        // Vérifiez si l'utilisateur est connecté
        return isset($_SESSION['user_id']); // Ajustez cela selon votre logique de gestion des sessions
    }
}

