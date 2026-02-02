<?php
session_start();
require_once '../php/config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// Handle Create
if (isset($_POST['add_filiere'])) {
    $nom = escape($_POST['nom']);
    $ecole_id = (int)$_POST['ecole_id'];
    $conn->query("INSERT INTO filieres (nom, ecole_id) VALUES ('$nom', $ecole_id)");
    header("Location: filieres.php?msg=added"); exit();
}

// Handle Update
if (isset($_POST['update_filiere'])) {
    $id = (int)$_POST['filiere_id'];
    $nom = escape($_POST['nom']);
    $ecole_id = (int)$_POST['ecole_id'];
    $conn->query("UPDATE filieres SET nom = '$nom', ecole_id = $ecole_id WHERE id = $id");
    header("Location: filieres.php?msg=updated"); exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM filieres WHERE id = $id");
    header("Location: filieres.php?msg=deleted"); exit();
}

$filieres = fetchAll("SELECT f.*, e.nom as ecole_nom FROM filieres f JOIN ecoles e ON f.ecole_id = e.id ORDER BY e.nom, f.nom");
$ecoles = fetchAll("SELECT * FROM ecoles ORDER BY nom");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Filières - UCAO</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .edit-modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 2rem; border-radius: 12px; width: 450px; }
    </style>
</head>
<body>
        <div class="sidebar">
        <div style="padding: 1.5rem; text-align: center;">
            <img src="https://ui-avatars.com/api/?name=UCAO&background=fff&color=1a237e" style="border-radius: 50%; width: 50px; margin-bottom: 0.5rem;">
            <h2 style="font-size: 1.2rem; margin: 0; letter-spacing: 1px;">UCAO ADMIN</h2>
            <p style="font-size: 0.7rem; color: rgba(255,255,255,0.5);">Panel de Gestion</p>
        </div>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="documents.php"><i class="fas fa-folder-open"></i> Documents</a></li>
                <li><a href="ecoles.php"><i class="fas fa-university"></i> Écoles</a></li>
                <li><a href="filieres.php" class="active"><i class="fas fa-graduation-cap"></i> Filières</a></li>
                <li><a href="users.php"><i class="fas fa-users-cog"></i> Utilisateurs</a></li>
                        </ul>
        </nav>
        <div style="margin-top: auto; padding: 1rem;">
            <a href="logout.php" style="color: #ff5252; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; padding: 0.8rem; border-radius: 8px; background: rgba(255,82,82,0.1);">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>

        <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 style="color: var(--bleu-nuit); margin-bottom: 5px;">Filières d'Études</h1>
                <p style="color: #888; font-size: 0.9rem;">Gestion des programmes par faculté.</p>
            </div>
            <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right;">
                    <p style="font-weight: 600; margin: 0;"><?= $_SESSION['admin_name'] ?></p>
                    <p style="font-size: 0.75rem; color: #888;">Panel Admin</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['admin_name']) ?>&background=1a237e&color=fff" style="border-radius: 50%; width: 45px;">
            </div>
        </div>

        <div class="data-card" style="border-left: 4px solid var(--rouge-nuit);">
            <h4 style="margin-bottom: 1rem;">Nouvelle Filière</h4>
            <form method="POST" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <input type="text" name="nom" placeholder="Nom de la filière (ex: Informatique de Gestion)" required style="flex: 2; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                <select name="ecole_id" required style="flex: 1; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="">Choisir l`École/Faculté...</option>
                    <?php foreach($ecoles as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="add_filiere" class="btn-admin" style="background: var(--rouge-nuit); color: white;"><i class="fas fa-plus"></i> Créer</button>
            </form>
        </div>

        <div class="data-card">
            <table>
                <thead>
                    <tr>
                        <th>Filière</th>
                        <th>École / Faculté</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($filieres as $f): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($f['nom']) ?></td>
                        <td><span style="color: #666; font-size: 0.9rem;"><?= htmlspecialchars($f['ecole_nom']) ?></span></td>
                        <td>
                            <div style="display: flex; gap: 1rem;">
                                <button onclick="openEdit(<?= htmlspecialchars(json_encode($f)) ?>)" style="color: #ff9800; border:none; background:none; cursor:pointer;"><i class="fas fa-edit"></i></button>
                                <a href="filieres.php?delete=<?= $f['id'] ?>" onclick="return confirm('Supprimer cette filière ?')" style="color:#b71c1c;"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="edit-modal">
        <div class="modal-content">
            <h3>Modifier la Filière</h3>
            <form method="POST" style="margin-top: 1.5rem;">
                <input type="hidden" name="filiere_id" id="edit_id">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Nom de la filière</label>
                    <input type="text" name="nom" id="edit_nom" required style="width: 100%; padding: 0.8rem; margin-top: 5px;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label>Rattaché à</label>
                    <select name="ecole_id" id="edit_ecole_id" style="width: 100%; padding: 0.8rem; margin-top: 5px;">
                        <?php foreach($ecoles as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn-admin">Annuler</button>
                    <button type="submit" name="update_filiere" class="btn-admin" style="background: var(--bleu-nuit); color: white;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEdit(filiere) {
            document.getElementById('edit_id').value = filiere.id;
            document.getElementById('edit_nom').value = filiere.nom;
            document.getElementById('edit_ecole_id').value = filiere.ecole_id;
            document.getElementById('editModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>


