<?php
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']);
    $filePath = __DIR__ . '/../uploads/' . $fileName;

    // Pour la démo, nous allons créer un fichier factice s'il n'existe pas
    if (!file_exists($filePath)) {
        // Assurez-vous que le dossier uploads existe
        if (!is_dir(__DIR__ . '/../uploads')) {
            mkdir(__DIR__ . '/../uploads');
        }
        file_put_contents($filePath, "Ceci est un document de test pour {$fileName}.");
    }

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo "Fichier non trouvé.";
    }
} else {
    http_response_code(400);
    echo "Aucun fichier spécifié.";
}
?>