<?php
class Eleve {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function genererMatricule($nom, $prenom) {
        // Obtenir le mois et l'année actuels
        $mois = date('m'); // Mois en chiffres (01 à 12)
        $annee = date('y'); // Deux derniers chiffres de l'année (00 à 99)
    
        // Générer un matricule basé sur le nom, le prénom, le mois et l'année
        $matricule = 'ELV' . strtoupper(substr($nom, 0, 3)) . strtoupper(substr($prenom, 0, 3)) . $mois . $annee . rand(100, 999);
        return $matricule;
    }

    public function ajouterEleve($data) {
        // Vérification des champs requis
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['date_naissance']) || 
            empty($data['sexe']) || empty($data['adresse']) || empty($data['telephone']) || 
            empty($data['email']) || empty($data['matricule'])) {
            throw new Exception("Tous les champs de l'élève sont requis.");
        }
    
        // Vérification des champs requis pour le tuteur
        if (empty($data['tuteur_nom']) || empty($data['tuteur_prenom']) || empty($data['tuteur_email']) || 
            empty($data['tuteur_telephone'])) {
            throw new Exception("Tous les champs du tuteur sont requis.");
        }
    
        // Commencer une transaction
        $this->conn->beginTransaction();
    
        try {
            // Préparer la requête d'insertion pour le tuteur
            $queryTuteur = "INSERT INTO tuteurs (tuteur_nom, tuteur_prenom, tuteur_email, tuteur_telephone) 
                            VALUES (?, ?, ?, ?)";
            $stmtTuteur = $this->conn->prepare($queryTuteur);
            $stmtTuteur->execute([
                $data['tuteur_nom'],
                $data['tuteur_prenom'],
                $data['tuteur_email'],
                $data['tuteur_telephone']
            ]);
    
            // Récupérer l'ID du tuteur inséré
            $id_tuteur = $this->conn->lastInsertId();
    
            // Préparer la requête d'insertion pour l'élève
            $queryEleve = "INSERT INTO eleves (nom, prenom, date_naissance, sexe, adresse, telephone, email, matricule, id_tuteur) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtEleve = $this->conn->prepare($queryEleve);
            $stmtEleve->execute([
                $data['nom'],
                $data['prenom'],
                $data['date_naissance'],
                $data['sexe'],
                $data['adresse'],
                $data['telephone'],
                $data['email'],
                $data['matricule'],
                $id_tuteur // Utiliser l'ID du tuteur nouvellement inséré
            ]);
    
            // Valider la transaction
            $this->conn->commit();
            return true;
    
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollBack();
            throw new Exception("Erreur lors de l'ajout : " . $e->getMessage());
        }
    }
    
    
    public function getEleves() {
        $query = "SELECT eleves.*, tuteurs.tuteur_nom, tuteurs.tuteur_prenom, tuteurs.tuteur_email, tuteurs.tuteur_telephone
                  FROM eleves
                  INNER JOIN tuteurs ON eleves.id_tuteur = tuteurs.id_tuteur";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEleveById($id) {
        $query = "SELECT eleves.*, tuteurs.tuteur_nom, tuteurs.tuteur_prenom, tuteurs.tuteur_email, tuteurs.tuteur_telephone
                  FROM eleves
                  INNER JOIN tuteurs ON eleves.id_tuteur = tuteurs.id_tuteur
                  WHERE eleves.id_eleve = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierEleve($data) {
        try {
            // Commencer une transaction
            $this->conn->beginTransaction();
    
            // Mise à jour des informations de l'élève
            $stmtEleve = $this->conn->prepare("UPDATE eleves 
                                              SET nom = ?, prenom = ?, date_naissance = ?, sexe = ?, 
                                                  adresse = ?, telephone = ?, email = ?, matricule = ? 
                                              WHERE id_eleve = ?");
            $stmtEleve->execute([
                $data['nom'], $data['prenom'], $data['date_naissance'],
                $data['sexe'], $data['adresse'], $data['telephone'],
                $data['email'], $data['matricule'], $data['id_eleve']
            ]);
    
            // Mise à jour des informations du tuteur
            $stmtTuteur = $this->conn->prepare("UPDATE tuteurs 
                                               SET tuteur_nom = ?, tuteur_prenom = ?, tuteur_email = ?, tuteur_telephone = ? 
                                               WHERE id_tuteur = ?");
            $stmtTuteur->execute([
                $data['tuteur_nom'], $data['tuteur_prenom'],
                $data['tuteur_email'], $data['tuteur_telephone'], $data['id_tuteur']
            ]);
    
            // Valider la transaction
            $this->conn->commit();
            return true;
    
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollBack();
            throw new Exception("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }


    public function archiverEleve($id_eleve) {
        // Vérifier si l'ID de l'élève est fourni
        if (empty($id_eleve)) {
            throw new Exception("L'ID de l'élève est requis pour l'archivage.");
        }
        
        // Commencer une transaction
        $this->conn->beginTransaction();
        
        try {
            // Vérifier si l'élève existe
            $stmt = $this->conn->prepare("SELECT id_eleve FROM eleves WHERE id_eleve = ?");
            $stmt->execute([$id_eleve]);
            $eleve = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$eleve) {
                throw new Exception("Élève non trouvé.");
            }
        
            // Archiver l'élève en mettant à jour la colonne 'archive'
            $stmtArchiver = $this->conn->prepare("UPDATE eleves SET archive = 1 WHERE id_eleve = ?");
            $stmtArchiver->execute([$id_eleve]);
        
            // Valider la transaction
            $this->conn->commit();
        
            return true;  // Succès
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            $this->conn->rollBack();
            throw new Exception("Erreur lors de l'archivage de l'élève : " . $e->getMessage());
        }
    }
    


    public function supprimerEleve($id_eleve) {
        // Vérifier si l'ID de l'élève est fourni
        if (empty($id_eleve)) {
            throw new Exception("L'ID de l'élève est requis pour la suppression.");
        }
    
        // Commencer une transaction
        $this->conn->beginTransaction();
    
        try {
            // Récupérer l'élève pour vérifier son existence et obtenir l'id_tuteur
            $stmtEleve = $this->conn->prepare("SELECT id_tuteur FROM eleves WHERE id_eleve = ?");
            $stmtEleve->execute([$id_eleve]);
            $eleve = $stmtEleve->fetch(PDO::FETCH_ASSOC);
    
            if (!$eleve) {
                throw new Exception("Élève non trouvé.");
            }
    
            // Supprimer l'élève de la table 'eleves'
            $stmtSupprimerEleve = $this->conn->prepare("DELETE FROM eleves WHERE id_eleve = ?");
            $stmtSupprimerEleve->execute([$id_eleve]);
    
            // Vérifier si le tuteur peut être supprimé (si aucun autre élève n'est lié à ce tuteur)
            if ($eleve['id_tuteur']) {
                // Vérifier combien d'élèves sont liés à ce tuteur
                $stmtTuteur = $this->conn->prepare("SELECT COUNT(*) FROM eleves WHERE id_tuteur = ?");
                $stmtTuteur->execute([$eleve['id_tuteur']]);
                $count = $stmtTuteur->fetchColumn();
    
                // Si aucun autre élève n'est lié à ce tuteur, on peut le supprimer
                if ($count == 0) {
                    $stmtSupprimerTuteur = $this->conn->prepare("DELETE FROM tuteurs WHERE id_tuteur = ?");
                    $stmtSupprimerTuteur->execute([$eleve['id_tuteur']]);
                }
            }
    
            // Valider la transaction
            $this->conn->commit();
            return true;
    
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollBack();
            throw new Exception("Erreur lors de la suppression de l'élève : " . $e->getMessage());
        }
    }
    
    
   
}

