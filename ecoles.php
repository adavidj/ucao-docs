<?php
require_once 'php/config.php';

// Fetch schools and their programs
try {
    $sql = "SELECT e.id as ecole_id, e.nom as ecole_nom, e.type as ecole_type, f.id as filiere_id, f.nom as filiere_nom 
            FROM ecoles e 
            LEFT JOIN filieres f ON e.id = f.ecole_id 
            ORDER BY e.nom, f.nom";
    $data = fetchAll($sql);
    
    $ecoles = [];
    foreach ($data as $row) {
        $eid = $row['ecole_id'];
        if (!isset($ecoles[$eid])) {
            $ecoles[$eid] = [
                'nom' => $row['ecole_nom'],
                'type' => $row['ecole_type'],
                'filieres' => []
            ];
        }
        if ($row['filiere_id']) {
            $ecoles[$eid]['filieres'][] = [
                'id' => $row['filiere_id'],
                'nom' => $row['filiere_nom']
            ];
        }
    }
} catch (Exception $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Écoles & Filières - UCAO Docs</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://ucaobenin.org/wp-content/uploads/2022/10/logo-ucao.png" alt="UCAO Logo">
            <h1>UCAO Docs</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="archives.php">Archives</a></li>
                <li><a href="ecoles.php" class="active">Écoles & Filières</a></li>
                <li><a href="apropos.php">À propos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero" style="padding: 4rem 10%; margin-bottom: 3rem;">
            <h2>Écoles & Facultés</h2>
            <p>Structure académique de l'UCAO-Bénin. Parcourez les départements pour accéder aux ressources spécifiques à chaque filière.</p>
        </section>

        <div class="container">
            <section class="ecoles-container" style="margin-bottom: 4rem;">
                <?php foreach ($ecoles as $id => $ecole): ?>
                <div class="ecole-card">
                    <h2><i class="fas fa-university"></i> <?php echo htmlspecialchars($ecole['nom']); ?></h2>
                    <p style="margin-bottom: 1.5rem; color: var(--rouge-nuit); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">
                        <?php echo $ecole['type'] == 'faculte' ? 'Faculté' : 'École'; ?>
                    </p>
                    <div class="filiere-list">
                        <?php if (empty($ecole['filieres'])): ?>
                            <p style="font-size: 0.85rem; color: #888;">Aucune filière enregistrée pour cette école.</p>
                        <?php else: ?>
                            <?php foreach ($ecole['filieres'] as $filiere): ?>
                            <div class="filiere-item">
                                <a href="archives.php?filiere=<?php echo $filiere['id']; ?>">
                                    <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: var(--rouge-nuit);"></i>
                                    <?php echo htmlspecialchars($filiere['nom']); ?>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>UCAO Docs</h4>
                <p>Plateforme officielle d'archivage des documents académiques de l'Université Catholique de l'Afrique de l'Ouest.</p>
            </div>
            <div class="footer-section">
                <h4>Liens Rapides</h4>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="archives.php">Archives</a></li>
                    <li><a href="ecoles.php">Écoles</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <ul>
                    <li><i class="fas fa-envelope"></i> info@ucaobenin.org</li>
                    <li><i class="fas fa-phone"></i> +229 XX XX XX XX</li>
                    <li><i class="fas fa-map-marker-alt"></i> Cotonou, Bénin</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 UCAO-Benin. Tous droits réservés. Présenté par l'équipe de développement.</p>
        </div>
    </footer>
</body>
</html>
