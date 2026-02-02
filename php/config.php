<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ucao_docs_archive');

// Connexion à la base de données
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Créer la base de données si elle n'existe pas
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
$conn->select_db(DB_NAME);

// Fonction pour exécuter une requête
function query($sql) {
    global $conn;
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error executing query: " . $conn->error . "\nQuery: " . $sql);
    }
    return $result;
}

// Fonction pour récupérer des lignes
function fetchAll($sql) {
    $result = query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fonction pour récupérer une seule ligne
function fetchOne($sql) {
    $result = query($sql);
    return $result->fetch_assoc();
}

// Fonction pour échapper les chaînes de caractères
function escape($value) {
    global $conn;
    return $conn->real_escape_string($value);
}
?>
