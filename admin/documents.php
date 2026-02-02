<?php
session_start();
require_once '../php/config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// Handle Search & Filter
$search = isset($_GET['search']) ? escape($_GET['search']) : '';
$filiere_filter = isset($_GET['filiere']) ? (int)$_GET['filiere'] : 0;

$query = "SELECT d.*, f.nom as filiere_nom FROM documents d JOIN filieres f ON d.filiere_id = f.id WHERE 1=1";
if ($filiere_filter > 0) $query .= " AND d.filiere_id = $filiere_filter";
if ($search != '') $query .= " AND d.titre LIKE '%$search%'";
$query .= " ORDER BY d.created_at DESC";

$documents = fetchAll($query);
$filieres = fetchAll("SELECT f.*, e.nom as ecole_nom FROM filieres f JOIN ecoles e ON f.ecole_id = e.id ORDER BY f.nom");
$ecoles = fetchAll("SELECT * FROM ecoles ORDER BY nom");

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Optional: unlink file physically
    $conn->query("DELETE FROM documents WHERE id = $id");
    header("Location: documents.php?msg=deleted"); exit();
}

// Handle Edit (Post)
if (isset($_POST['update_doc'])) {
    $id = (int)$_POST['doc_id'];
    $titre = escape($_POST['titre']);
    $type = escape($_POST['type']);
    $filiere_id = (int)$_POST['filiere_id'];
    $semestre = escape($_POST['semestre'] ?? '');
    
    $conn->query("UPDATE documents SET titre = '$titre', type = '$type', filiere_id = $filiere_id, semestre = '$semestre' WHERE id = $id");
    header("Location: documents.php?msg=updated"); exit();
}

// Handle Add (Post)
if (isset($_POST['add_doc'])) {
    $titre = escape($_POST['titre']);
    $type = escape($_POST['type']);
    $filiere_id = (int)$_POST['filiere_id'];
    $annee = escape($_POST['annee']);
    $niveau = escape($_POST['niveau']);
    $semestre = escape($_POST['semestre'] ?? ''); // New field
    
    $file = $_FILES['document_file'];
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = "../uploads/" . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $filePath = "uploads/" . $fileName;
        // Adding semester to the query
        $conn->query("INSERT INTO documents (titre, type, filiere_id, annee, semestre, file_path) VALUES ('$titre', '$type', $filiere_id, '$annee', '$semestre', '$filePath')");
        header("Location: documents.php?msg=added"); exit();
    }
}

// Handle Export CSV
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=archives_ucao_'.date('Y-m-d').'.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Titre', 'Type', 'Filiere', 'Annee', 'Date Ajout'));
    foreach ($documents as $doc) {
        fputcsv($output, array($doc['id'], $doc['titre'], $doc['type'], $doc['filiere_nom'], $doc['annee'], $doc['created_at']));
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Archives - UCAO</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .search-box { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; background: #fff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .edit-modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 2.5rem; border-radius: 16px; width: 700px; max-width: 95%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
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
                <li><a href="documents.php" class="active"><i class="fas fa-folder-open"></i> Documents</a></li>
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
                <h1 style="color: var(--bleu-nuit); margin-bottom: 5px;">Gestion de la Bibliothèque</h1>
                <p style="color: #888; font-size: 0.9rem;">Total: <strong><?= count($documents) ?></strong> documents trouvés</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="openAddModal()" class="btn-admin" style="background: var(--bleu-nuit); color: white; border: none; cursor: pointer;">
                    <i class="fas fa-plus"></i> Nouveau Document
                </button>
                <a href="?export=1&search=<?= urlencode($search) ?>&filiere=<?= $filiere_filter ?>" class="btn-admin" style="background: #4caf50; color: white; text-decoration: none;">
                    <i class="fas fa-file-export"></i> Exporter CSV
                </a>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div style="padding: 1rem; border-radius: 8px; margin-bottom: 2rem; background: #e3f2fd; color: #1565c0;">
                <i class="fas fa-info-circle"></i> Opération effectuée avec succès.
            </div>
        <?php endif; ?>

        <div class="search-box">
            <form method="GET" style="display: flex; gap: 1rem; flex: 1;">
                <input type="text" name="search" placeholder="Rechercher par titre..." value="<?= htmlspecialchars($search) ?>" style="flex: 2; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px;">
                <select name="filiere" style="flex: 1; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="0">Toutes les Filières</option>
                    <?php foreach($filieres as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= $filiere_filter == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-admin" style="background: #333; color: white;"><i class="fas fa-search"></i> Filtrer</button>
                <a href="documents.php" class="btn-admin" style="padding: 0.8rem; text-decoration: none; color: #666; border: 1px solid #ddd;">Reset</a>
            </form>
        </div>

        <div class="data-card">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Filière / École</th>
                        <th>Type</th>
                        <th>Semestre</th>
                        <th>Date d`Ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($documents as $doc): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($doc['titre']) ?></td>
                        <td style="font-size: 0.9rem; color: #555;"><?= htmlspecialchars($doc['filiere_nom']) ?></td>
                        <td>
                            <span class="badge" style="background: <?= $doc['type']=='epreuve'?'#ffebee':($doc['type']=='compilation'?'#e1f5fe':'#e8eaf6') ?>; color: <?= $doc['type']=='epreuve'?'#b71c1c':($doc['type']=='compilation'?'#0288d1':'#1a237e') ?>">
                                <?= strtoupper($doc['type']) ?>
                            </span>
                        </td>
                        <td><span style="font-weight: 600; color: var(--bleu-nuit);"><?= htmlspecialchars($doc['semestre'] ?? '-') ?></span></td>
                        <td style="font-size: 0.8rem;"><?= date('d M Y', strtotime($doc['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 0.8rem;">
                                <a href="../<?= $doc['file_path'] ?>" target="_blank" style="color: #4caf50;" title="Voir"><i class="fas fa-eye"></i></a>
                                <button onclick="openEdit(<?= htmlspecialchars(json_encode($doc)) ?>)" style="color: #ff9800; border:none; background:none; cursor:pointer;" title="Modifier"><i class="fas fa-edit"></i></button>
                                <a href="documents.php?delete=<?= $doc['id'] ?>" onclick="return confirm('Supprimer ce document définitivement ?')" style="color: var(--rouge-nuit);" title="Supprimer"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="edit-modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 10px; color: var(--bleu-nuit);">
                <i class="fas fa-file-upload"></i> Archiver un Nouveau Document
            </h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_doc" value="1">
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-weight: 600; color: #444;">Titre explicite du document</label>
                    <input type="text" name="titre" placeholder="Ex: Examen Final - Microéconomie I" required style="width: 100%; padding: 1rem; margin-top: 8px; border: 1px solid #ddd; border-radius: 10px; font-size: 1rem;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #444;">Type de document</label>
                        <select name="type" style="width: 100%; padding: 1rem; margin-top: 8px; border: 1px solid #ddd; border-radius: 10px; background: #f9f9f9;">
                            <option value="epreuve">Épreuve / Examen</option>
                            <option value="cours">Support de Cours</option>
                            <option value="td_tp">TD / TP</option>
                            <option value="memoire">Mémoire / Thèse</option>
                            <option value="compilation">Compilation de Semestre (Full Pack)</option>
                            <option value="autre">Autre Document</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #444;">Année Académique</label>
                        <select name="annee" style="width: 100%; padding: 1rem; margin-top: 8px; border: 1px solid #ddd; border-radius: 10px; background: #f9f9f9;">
                            <?php for($y = date('Y'); $y >= 2015; $y--): ?>
                                <option value="<?= $y ?>-<?= $y+1 ?>"><?= $y ?>-<?= $y+1 ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #444;">Détail Période / Semestre</label>
                        <select name="semestre" style="width: 100%; padding: 1rem; margin-top: 8px; border: 1px solid #ddd; border-radius: 10px; background: #f9f9f9;">
                            <option value="S1">Semestre 1 (S1)</option>
                            <option value="S2">Semestre 2 (S2)</option>
                            <option value="S3">Semestre 3 (S3)</option>
                            <option value="S4">Semestre 4 (S4)</option>
                            <option value="S5">Semestre 5 (S5)</option>
                            <option value="S6">Semestre 6 (S6)</option>
                            <option value="Annuel">Annuel / Rattrapage</option>
                            <option value="Autre">Non défini</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #444;">Niveau d`études</label>
                        <select name="niveau" style="width: 100%; padding: 1rem; margin-top: 8px; border: 1px solid #ddd; border-radius: 10px; background: #f9f9f9;">
                            <option value="Licence 1">Licence 1</option>
                            <option value="Licence 2">Licence 2</option>
                            <option value="Licence 3">Licence 3</option>
                            <option value="Master 1">Master 1</option>
                            <option value="Master 2">Master 2</option>
                            <option value="Doctorat">Doctorat</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 2rem; padding: 1.5rem; border: 2px dashed #1a237e; border-radius: 12px; background: #f0f2ff; text-align: center;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: var(--bleu-nuit);">Fichier PDF Unique</label>
                    <input type="file" name="document_file" accept=".pdf" required style="font-size: 0.9rem;">
                    <p style="font-size: 0.75rem; color: #666; margin-top: 10px;">Format PDF uniquement • Taille max: 10 Mo</p>
                </div>

                <div style="display: flex; gap: 1.5rem;">
                    <button type="submit" class="btn-admin" style="flex: 2; height: 55px; background: var(--bleu-nuit); color: white; border: none; font-size: 1.1rem; font-weight: 600; border-radius: 12px; cursor: pointer;">
                        <i class="fas fa-check-circle"></i> Confirmer l`Archivage
                    </button>
                    <button type="button" onclick="closeAddModal()" class="btn-admin" style="flex: 1; height: 55px; background: #f5f5f5; color: #666; border: none; font-weight: 600; border-radius: 12px; cursor: pointer;">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="edit-modal">
        <div class="modal-content">
            <h3 style="margin-bottom: 1.5rem;">Modifier le Document</h3>
            <form id="editForm" method="POST">
                <input type="hidden" name="doc_id" id="edit_id">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Titre</label>
                    <input type="text" name="titre" id="edit_titre" required style="width: 100%; padding: 0.8rem; margin-top: 5px;">
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Type</label>
                    <select name="type" id="edit_type" style="width: 100%; padding: 0.8rem; margin-top: 5px;">
                        <option value="epreuve">Épreuve / Examen</option>
                        <option value="cours">Support de Cours</option>
                        <option value="td_tp">TD / TP</option>
                        <option value="memoire">Mémoire / Thèse</option>
                        <option value="compilation">Compilation de Semestre (Full Pack)</option>
                        <option value="autre">Autre Document</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Semestre</label>
                    <select name="semestre" id="edit_semestre" style="width: 100%; padding: 0.8rem; margin-top: 5px;">
                        <option value="S1">Semestre 1 (S1)</option>
                        <option value="S2">Semestre 2 (S2)</option>
                        <option value="S3">Semestre 3 (S3)</option>
                        <option value="S4">Semestre 4 (S4)</option>
                        <option value="S5">Semestre 5 (S5)</option>
                        <option value="S6">Semestre 6 (S6)</option>
                        <option value="Annuel">Annuel / Rattrapage</option>
                        <option value="Autre">Non défini</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label>Filière</label>
                    <select name="filiere_id" id="edit_filiere" style="width: 100%; padding: 0.8rem; margin-top: 5px;">
                        <?php foreach($filieres as $f): ?>
                            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn-admin">Annuler</button>
                    <button type="submit" name="update_doc" class="btn-admin" style="background: var(--bleu-nuit); color: white;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
        function openEdit(doc) {
            document.getElementById('edit_id').value = doc.id;
            document.getElementById('edit_titre').value = doc.titre;
            document.getElementById('edit_type').value = doc.type;
            document.getElementById('edit_filiere').value = doc.filiere_id;
            document.getElementById('edit_semestre').value = doc.semestre;
            document.getElementById('editModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) closeModal();
            if (event.target == document.getElementById('addModal')) closeAddModal();
        }
    </script>
</body>
</html>

