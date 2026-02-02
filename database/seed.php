<?php
require_once __DIR__ . '/../php/config.php';

// Lire et exécuter le fichier SQL pour créer le schéma et insérer les données
$sql = file_get_contents(__DIR__ . '/../database/schema.sql');
if ($conn->multi_query($sql)) {
    // Attendre que toutes les requêtes soient terminées
    while ($conn->next_result()) {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    }
    echo "Database schema and initial data created successfully.\n";
} else {
    echo "Error creating database schema: " . $conn->error . "\n";
}

// Création de quelques documents de test
$documents = [
    [
        'titre' => 'Épreuve de Mathématiques',
        'description' => 'Examen final de mathématiques pour les L1.',
        'type' => 'epreuve',
        'session' => 'Session normale',
        'annee' => 2023,
        'fichier' => 'epreuve_math_l1_2023.pdf',
        'filiere_id' => 17, // Sciences Juridiques
        'niveau_id' => 1 // Licence 1
    ],
    [
        'titre' => 'Cours d\'Introduction au Droit',
        'description' => 'Support de cours complet pour l\'introduction au droit.',
        'type' => 'cours',
        'session' => NULL,
        'annee' => 2023,
        'fichier' => 'cours_intro_droit_2023.pdf',
        'filiere_id' => 18, // Droit
        'niveau_id' => 1 // Licence 1
    ],
    [
        'titre' => 'TD de Comptabilité',
        'description' => 'Travaux dirigés de comptabilité analytique.',
        'type' => 'autre',
        'session' => NULL,
        'annee' => 2024,
        'fichier' => 'td_compta_2024.pdf',
        'filiere_id' => 6, // Finances Comptabilité Audit
        'niveau_id' => 2 // Licence 2
    ]
];

// Vider la table documents avant d'insérer pour éviter les doublons lors des tests
query("DELETE FROM documents");

$stmt = $conn->prepare("INSERT INTO `documents` (`titre`, `description`, `type`, `session`, `annee`, `fichier`, `filiere_id`, `niveau_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($documents as $doc) {
    $stmt->bind_param(
        "ssssisii",
        $doc['titre'],
        $doc['description'],
        $doc['type'],
        $doc['session'],
        $doc['annee'],
        $doc['fichier'],
        $doc['filiere_id'],
        $doc['niveau_id']
    );
    $stmt->execute();
}

echo "Sample documents inserted successfully.\n";

$stmt->close();
$conn->close();
?>