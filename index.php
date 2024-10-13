<?php
include 'Config/config.php'; // Inclure la connexion à la base de données
include 'Controllers/EleveController.php';

$controller = new EleveController($conn);
$controller->ajouterEleve(); // Traitement pour ajouter un élève
$controller->modifierEleve(); // Traitement pour modifier un élève


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'archiver') {
        // Vérifiez que l'ID de l'élève est présent dans la requête
        if (isset($_POST['id_eleve']) && !empty($_POST['id_eleve'])) {
            $id_eleve = $_POST['id_eleve']; // Récupérez l'ID de l'élève
            try {
                $controller->archiverEleve($id_eleve); // Appel à la méthode d'archivage
                echo "<script>alert('Élève archivé avec succès.'); window.location.href='Views/Supervisors/supervisor_list.php.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Erreur : " . $e->getMessage() . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('L\'ID de l\'élève est requis pour l\'archivage.'); window.history.back();</script>";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Élèves</title>
</head>
<body>
    <?php include 'Views/Supervisors/supervisor_list.php.php'; ?>
</body>
</html>
