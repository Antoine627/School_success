<?php
include '../../Controllers/EleveController.php';
$controller = new EleveController($conn);

// Traitement du formulaire d'ajout d'élève
if (isset($_POST['ajouter_eleve'])) {
    $controller->ajouterEleve($_POST);
}

// Traitement du formulaire de modification d'élève
if (isset($_POST['modifier_eleve'])) {
    $controller->modifierEleve($_POST);
}

// Traitement de la suppression d'élève
if (isset($_POST['supprimer_eleve'])) {
    $controller->supprimerEleve($_POST['id_eleve']);
}

$eleves = $controller->listerEleves();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Élèves</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Liste des Élèves</h2>

        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
            Ajouter un Élève
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de Naissance</th>
                    <th>Sexe</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                    <th>Email Élève</th>
                    <th>Matricule</th>
                    <th>Tuteur</th>
                    <th>Email Tuteur</th>
                    <th>Téléphone Tuteur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eleves as $eleve): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($eleve['id_eleve']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['nom']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['date_naissance']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['sexe']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['adresse']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['telephone']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['email']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['matricule']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['tuteur_nom'] . ' ' . $eleve['tuteur_prenom']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['tuteur_email']); ?></td>
                        <td><?php echo htmlspecialchars($eleve['tuteur_telephone']); ?></td>
                        <td>
                            <!-- Modifier -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal<?php echo $eleve['id_eleve']; ?>">
                                Modifier
                            </button>

                            <!-- Archiver -->
                            <button type="button" class="btn btn-warning" onclick="archiverEleve(<?php echo $eleve['id_eleve']; ?>)">Archiver</button>

                           <!-- Supprimer -->
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id_eleve" value="<?php echo $eleve['id_eleve']; ?>">
                                <input type="hidden" name="supprimer_eleve" value="1"> <!-- Ajout d'un champ caché pour identifier l'action -->
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')">Supprimer</button>
                            </form>

                            <!-- Modale de modification -->
                            <div class="modal fade" id="editModal<?php echo $eleve['id_eleve']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier l'élève</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="">
                                                <input type="hidden" name="id_eleve" value="<?php echo $eleve['id_eleve']; ?>">
                                                <input type="hidden" name="id_tuteur" value="<?php echo $eleve['id_tuteur']; ?>">
                                                <div class="form-group">
                                                    <label for="nom">Nom</label>
                                                    <input type="text" class="form-control" name="nom" value="<?php echo $eleve['nom']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="prenom">Prénom</label>
                                                    <input type="text" class="form-control" name="prenom" value="<?php echo $eleve['prenom']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="date_naissance">Date de Naissance</label>
                                                    <input type="date" class="form-control" name="date_naissance" value="<?php echo $eleve['date_naissance']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sexe">Sexe</label>
                                                    <select class="form-control" name="sexe">
                                                        <option value="Homme" <?php if($eleve['sexe'] == 'Homme') echo 'selected'; ?>>Homme</option>
                                                        <option value="Femme" <?php if($eleve['sexe'] == 'Femme') echo 'selected'; ?>>Femme</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="adresse">Adresse</label>
                                                    <input type="text" class="form-control" name="adresse" value="<?php echo $eleve['adresse']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="telephone">Téléphone</label>
                                                    <input type="tel" class="form-control" name="telephone" value="<?php echo $eleve['telephone']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" name="email" value="<?php echo $eleve['email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="matricule">Matricule</label>
                                                    <input type="text" class="form-control" name="matricule" value="<?php echo $eleve['matricule']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tuteur_nom">Nom du Tuteur</label>
                                                    <input type="text" class="form-control" name="tuteur_nom" value="<?php echo $eleve['tuteur_nom']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tuteur_prenom">Prénom du Tuteur</label>
                                                    <input type="text" class="form-control" name="tuteur_prenom" value="<?php echo $eleve['tuteur_prenom']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tuteur_email">Email du Tuteur</label>
                                                    <input type="email" class="form-control" name="tuteur_email" value="<?php echo $eleve['tuteur_email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tuteur_telephone">Téléphone du Tuteur</label>
                                                    <input type="tel" class="form-control" name="tuteur_telephone" value="<?php echo $eleve['tuteur_telephone']; ?>" required>
                                                </div>
                                                <button type="submit" name="modifier_eleve" class="btn btn-primary">Modifier l'Élève</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modale d'ajout -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un Élève</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" class="form-control" name="prenom" required>
                        </div>
                        <div class="form-group">
                            <label for="date_naissance">Date de Naissance</label>
                            <input type="date" class="form-control" name="date_naissance" required>
                        </div>
                        <div class="form-group">
                            <label for="sexe">Sexe</label>
                            <select class="form-control" name="sexe">
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <input type="text" class="form-control" name="adresse" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="matricule">Matricule</label>
                            <input type="text" class="form-control" name="matricule" required>
                        </div>
                        <div class="form-group">
                            <label for="tuteur_nom">Nom du Tuteur</label>
                            <input type="text" class="form-control" name="tuteur_nom" required>
                        </div>
                        <div class="form-group">
                            <label for="tuteur_prenom">Prénom du Tuteur</label>
                            <input type="text" class="form-control" name="tuteur_prenom" required>
                        </div>
                        <div class="form-group">
                            <label for="tuteur_email">Email du Tuteur</label>
                            <input type="email" class="form-control" name="tuteur_email" required>
                        </div>
                        <div class="form-group">
                            <label for="tuteur_telephone">Téléphone du Tuteur</label>
                            <input type="tel" class="form-control" name="tuteur_telephone" required>
                        </div>
                        <button type="submit" name="ajouter_eleve" class="btn btn-primary">Ajouter l'Élève</button>
                    </form>
                </div>
            </div>
        </div>
    </div><script>
function archiverEleve(id) {
    if (confirm('Êtes-vous sûr de vouloir archiver cet élève ?')) {
        window.location.href = "?archiver_eleve=1&id_eleve=" + id;
    }
}
</script>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
