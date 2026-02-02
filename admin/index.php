<?php
session_start();
require_once '../php/config.php';

// Auth check
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// Handle filter / delete in index if needed (simplified)
if (isset($_GET['delete_doc'])) {
    $id = (int)$_GET['delete_doc'];
    $conn->query("DELETE FROM documents WHERE id = $id");
    header("Location: index.php?msg=deleted"); exit();
}

// Handle rapid upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document_file'])) {
    $titre = escape($_POST['titre']);
    $type = escape($_POST['type']);
    $filiere_id = (int)$_POST['filiere_id'];
    $semestre = escape($_POST['semestre'] ?? '');
    $annee = date('Y') . '-' . (date('Y')+1);
    
    $file = $_FILES['document_file'];
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = "../uploads/" . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $filePath = "uploads/" . $fileName;
        $conn->query("INSERT INTO documents (titre, type, filiere_id, annee, semestre, file_path) VALUES ('$titre', '$type', $filiere_id, '$annee', '$semestre', '$filePath')");
        header("Location: index.php?msg=success"); exit();
    }
}

// Stats logic
$doc_count = fetchOne("SELECT COUNT(*) as total FROM documents")['total'];
$filiere_count = fetchOne("SELECT COUNT(*) as total FROM filieres")['total'];
$ecole_count = fetchOne("SELECT COUNT(*) as total FROM ecoles")['total'];
$user_count = fetchOne("SELECT COUNT(*) as total FROM users")['total'];

// Trends (This month vs last month)
$docs_this_month = fetchOne("SELECT COUNT(*) as total FROM documents WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")['total'];
$docs_last_month = fetchOne("SELECT COUNT(*) as total FROM documents WHERE created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)")['total'];

$doc_trend = 0;
if ($docs_last_month > 0) {
    $doc_trend = (($docs_this_month - $docs_last_month) / $docs_last_month) * 100;
} elseif ($docs_this_month > 0) {
    $doc_trend = 100;
}

// Data
$recent_docs = fetchAll("SELECT d.*, f.nom as filiere_nom FROM documents d JOIN filieres f ON d.filiere_id = f.id ORDER BY d.created_at DESC LIMIT 8");
$filieres_list = fetchAll("SELECT * FROM filieres ORDER BY nom");


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - UCAO</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
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
                <li><a href="index.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="documents.php"><i class="fas fa-folder-open"></i> Documents</a></li>
                <li><a href="ecoles.php"><i class="fas fa-university"></i> Écoles</a></li>
                <li><a href="filieres.php"><i class="fas fa-graduation-cap"></i> Filières</a></li>
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
                <h1 style="color: var(--bleu-nuit); margin-bottom: 5px;">Tableau de Bord</h1>
                <p style="color: #888; font-size: 0.9rem;">Bienvenue, <?= $_SESSION['admin_name'] ?>.</p>
            </div>
            <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right;">
                    <p style="font-weight: 600; margin: 0;"><?= $_SESSION['admin_name'] ?></p>
                    <p style="font-size: 0.75rem; color: #888;">Administrateur Principal</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['admin_name']) ?>&background=1a237e&color=fff" style="border-radius: 50%; width: 45px;">
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div style="padding: 1rem; border-radius: 8px; margin-bottom: 2rem; background: #e8f5e9; color: #2e7d32; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle"></i>
                <?= $_GET['msg'] == 'success' ? 'Document archivé !' : 'Document supprimé !' ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(26,35,126,0.1); color: var(--bleu-nuit);"><i class="fas fa-file-pdf"></i></div>
                <div class="stat-info">
                    <h3><?= $doc_count ?></h3>
                    <p>Documents 
                        <span style="font-size: 0.7rem; color: <?= $doc_trend >= 0 ? '#4caf50' : '#f44336' ?>; margin-left: 5px;">
                            <i class="fas fa-arrow-<?= $doc_trend >= 0 ? 'up' : 'down' ?>"></i> <?= round(abs($doc_trend)) ?>%
                        </span>
                    </p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(183,28,28,0.1); color: var(--rouge-nuit);"><i class="fas fa-graduation-cap"></i></div>
                <div class="stat-info">
                    <h3><?= $filiere_count ?></h3>
                    <p>Filières 
                        <span style="font-size: 0.7rem; color: #4caf50; margin-left: 5px;">
                            <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Actif
                        </span>
                    </p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(76,175,80,0.1); color: #4caf50; border-left: 4px solid #4caf50;"><i class="fas fa-users-cog"></i></div>
                <div class="stat-info">
                    <h3><?= $user_count ?></h3>
                    <p>Équipe Admin
                        <span style="font-size: 0.7rem; color: #888; margin-left: 5px;">
                            En ligne
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <?php include "inc_charts.php"; ?>

        <!-- Rapid Upload Section -->
        <div class="data-card" style="border-top: 4px solid var(--bleu-nuit);">
            <h4 style="margin-bottom: 1.5rem;"><i class="fas fa-bolt" style="color: #ffc107;"></i> Archivage Rapide</h4>
            <form action="index.php" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="titre" placeholder="Ex: Epreuve Microéconomie" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type">
                        <option value="epreuve">Épreuve</option>
                        <option value="cours">Cours</option>
                        <option value="compilation">Compilation (Semestre)</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Semestre</label>
                    <select name="semestre">
                        <option value="S1">Semestre 1</option>
                        <option value="S2">Semestre 2</option>
                        <option value="Annuel">Annuel</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Filière</label>
                    <select name="filiere_id" required>
                        <?php foreach($filieres_list as $f): ?>
                            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>PDF (Max 10MB)</label>
                    <input type="file" name="document_file" accept=".pdf" required>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-admin" style="background: var(--bleu-nuit); color: white; width: 100%; height: 45px;">
                        <i class="fas fa-cloud-upload-alt"></i> Archiver
                    </button>
                </div>
            </form>
        </div>

        <div class="data-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h4 style="margin: 0;">Documents Récents</h4>
                <a href="documents.php" class="btn-admin" style="text-decoration: none; font-size: 0.8rem; background: #f0f0f0; color: #333;">Voir tout</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Filière</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_docs as $doc): ?>
                    <tr>
                        <td style="font-weight: 600; font-size: 0.85rem;"><?= htmlspecialchars($doc['titre']) ?></td>
                        <td style="font-size: 0.85rem;"><?= htmlspecialchars($doc['filiere_nom']) ?></td>
                        <td><span class="badge" style="background: <?= $doc['type']=='epreuve'?'#ffebee':'#e8eaf6' ?>; color: <?= $doc['type']=='epreuve'?'#b71c1c':'#1a237e' ?>"><?= strtoupper($doc['type']) ?></span></td>
                        <td>
                            <div style="display: flex; gap: 1rem;">
                                <a href="../<?= $doc['file_path'] ?>" target="_blank" style="color: #4caf50;"><i class="fas fa-eye"></i></a>
                                <a href="index.php?delete_doc=<?= $doc['id'] ?>" onclick="return confirm('Supprimer ?')" style="color: var(--rouge-nuit);"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    
</body>
</html>

