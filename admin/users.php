<?php
session_start();
require_once '../php/config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// Handle Create
if (isset($_POST['add_user'])) {
    $name = escape($_POST['name']);
    $email = escape($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
    header("Location: users.php?msg=added"); exit();
}

// Handle Update
if (isset($_POST['update_user'])) {
    $id = (int)$_POST['user_id'];
    $name = escape($_POST['name']);
    $email = escape($_POST['email']);
    
    $sql = "UPDATE users SET name = '$name', email = '$email'";
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql .= ", password = '$password'";
    }
    $sql .= " WHERE id = $id";
    $conn->query($sql);
    header("Location: users.php?msg=updated"); exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id != $_SESSION['admin_id']) {
        $conn->query("DELETE FROM users WHERE id = $id");
        header("Location: users.php?msg=deleted"); exit();
    } else {
        header("Location: users.php?error=self_delete"); exit();
    }
}

$users = fetchAll("SELECT id, name, email, created_at FROM users ORDER BY name");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Équipe - UCAO</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .edit-modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 2rem; border-radius: 12px; width: 400px; }
    </style>
</head>
<body>
        <div class="sidebar">
        <div style="padding: 1.5rem; text-align: center;">
            <img src="../img/ucao.png" style="width: 80%; margin-bottom: 0.5rem;">
            <h2 style="font-size: 1.2rem; margin: 0; letter-spacing: 1px;">UCAO ADMIN</h2>
            <p style="font-size: 0.7rem; color: rgba(255,255,255,0.5);">Panel de Gestion</p>
        </div>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="documents.php"><i class="fas fa-folder-open"></i> Documents</a></li>
                <li><a href="ecoles.php"><i class="fas fa-university"></i> Écoles</a></li>
                <li><a href="filieres.php"><i class="fas fa-graduation-cap"></i> Filières</a></li>
                <li><a href="users.php" class="active"><i class="fas fa-users-cog"></i> Utilisateurs</a></li>
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
                <h1 style="color: var(--bleu-nuit); margin-bottom: 5px;">Équipe Admin</h1>
                <p style="color: #888; font-size: 0.9rem;">Contrôle des accès au système.</p>
            </div>
            <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right;">
                    <p style="font-weight: 600; margin: 0;"><?= $_SESSION['admin_name'] ?></p>
                    <p style="font-size: 0.75rem; color: #888;">Admin Principal</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['admin_name']) ?>&background=1a237e&color=fff" style="border-radius: 50%; width: 45px;">
            </div>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div style="padding: 1rem; border-radius: 8px; margin-bottom: 1rem; background: #ffebee; color: #b71c1c;">
                <i class="fas fa-exclamation-triangle"></i> Vous ne pouvez pas supprimer votre propre compte !
            </div>
        <?php endif; ?>

        <div class="data-card" style="border-top: 4px solid #4caf50;">
            <h4 style="margin-bottom: 1rem;">Ajouter un Administrateur</h4>
            <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <input type="text" name="name" placeholder="Nom complet" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                <input type="email" name="email" placeholder="Email" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                <input type="password" name="password" placeholder="Mot de passe" required style="padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                <button type="submit" name="add_user" class="btn-admin" style="background: #4caf50; color: white;"><i class="fas fa-user-plus"></i> Créer</button>
            </form>
        </div>

        <div class="data-card">
            <table>
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><img src="https://ui-avatars.com/api/?name=<?= urlencode($u['name']) ?>&background=random" style="width: 35px; border-radius: 50%;"></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge" style="background: #e8eaf6; color: #1a237e;">ADMIN</span></td>
                        <td>
                            <div style="display: flex; gap: 1rem;">
                                <button onclick="openEdit(<?= htmlspecialchars(json_encode($u)) ?>)" style="color: #ff9800; border:none; background:none; cursor:pointer;"><i class="fas fa-edit"></i></button>
                                <?php if($u['id'] != $_SESSION['admin_id']): ?>
                                    <a href="users.php?delete=<?= $u['id'] ?>" onclick="return confirm('Supprimer ce compte ?')" style="color:#b71c1c;"><i class="fas fa-trash"></i></a>
                                <?php endif; ?>
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
            <h3>Modifier le Profil</h3>
            <form method="POST" style="margin-top: 1.5rem;">
                <input type="hidden" name="user_id" id="edit_id">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Nom complet</label>
                    <input type="text" name="name" id="edit_name" required style="width: 100%; padding: 0.8rem; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" required style="width: 100%; padding: 0.8rem; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label>Nouveau Mot de Passe (laisser vide pour garder l`actuel)</label>
                    <input type="password" name="password" placeholder="********" style="width: 100%; padding: 0.8rem; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn-admin">Fermer</button>
                    <button type="submit" name="update_user" class="btn-admin" style="background: var(--bleu-nuit); color: white;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEdit(user) {
            document.getElementById('edit_id').value = user.id;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('editModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>


