<?php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_documents':
        getDocuments();
        break;
    case 'get_filters':
        getFilterData();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

function getDocuments() {
    $sql = "
        SELECT 
            d.id, d.titre, d.description, d.type, d.semestre, d.annee, d.file_path,
            f.id AS filiere_id, f.nom AS filiere_nom,
            e.id AS ecole_id, e.nom AS ecole_nom,
            d.niveau -- Using new level column
        FROM documents d
        JOIN filieres f ON d.filiere_id = f.id
        JOIN ecoles e ON f.ecole_id = e.id
        ORDER BY d.created_at DESC
    ";
    $documents = fetchAll($sql);
    echo json_encode($documents);
}

function getFilterData() {
    $ecoles = fetchAll("SELECT id, nom FROM ecoles ORDER BY nom");
    $filieres = fetchAll("SELECT id, nom FROM filieres ORDER BY nom");
    $niveaux = fetchAll("SELECT id, nom FROM niveaux ORDER BY id");

    echo json_encode([
        'ecoles' => $ecoles,
        'filieres' => $filieres,
        'niveaux' => $niveaux
    ]);
}
?>