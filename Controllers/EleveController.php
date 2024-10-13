<?php
require 'C:\xampp\htdocs\cours\Projet\Ecole Reussite\Config\config.php';
require 'C:\xampp\htdocs\cours\Projet\Ecole Reussite\Models\Eleve.php';

class EleveController {
    private $eleveModel;

    public function __construct($conn) {
        $this->eleveModel = new Eleve($conn);
    }

    public function ajouterEleve() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_eleve'])) {
            $this->eleveModel->ajouterEleve($_POST);
            // header("Location: index.php"); // Redirection après ajout
            // exit();
        }
    }

    public function listerEleves() {
        return $this->eleveModel->getEleves();
    }

    public function modifierEleve() {
        if (isset($_POST['id_eleve'])) {
            $this->eleveModel->modifierEleve($_POST);
            // header("Location: index.php"); // Redirection après modification
            // exit();
        }
    }

    public function afficherEleve($id) {
        return $this->eleveModel->getEleveById($id);
    }

    // Méthode pour archiver un élève (au lieu de le supprimer)
    public function archiverEleve($id_eleve) {
        try {
            return $this->eleveModel->archiverEleve($id_eleve); // Appel à la nouvelle méthode d'archivage
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'archivage : " . $e->getMessage());
        }
    }


    // Nouvelle méthode pour supprimer un élève
    public function supprimerEleve($id_eleve) {
        try {
            $this->eleveModel->supprimerEleve($id_eleve); // Appel à la méthode de suppression du modèle
            header("Location: index.php"); // Redirection après suppression
            exit();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression : " . $e->getMessage());
        }
    }
}

