<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archives - UCAO Docs</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/ucao.png" alt="UCAO Logo">
            <h1>UCAO Docs</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="archives.php" class="active">Archives</a></li>
                <li><a href="ecoles.php">Écoles & Filières</a></li>
                <li><a href="apropos.php">À propos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero" style="padding: 4rem 10%; margin-bottom: 3rem;">
            <h2>Archives Documentaires</h2>
            <p>Accédez en toute simplicité à l'intégralité des ressources académiques partagées par la communauté de l'UCAO-Bénin.</p>
        </section>

        <div class="container">
            <section class="search-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Rechercher une épreuve, un cours, une année...">
                    <button id="searchButton"><i class="fas fa-search"></i> RECHERCHER</button>
                </div>
                
                <div class="filter-row">
                    <select id="schoolFilter">
                        <option value="">Toutes les écoles</option>
                    </select>
                    <select id="programFilter">
                        <option value="">Toutes les filières</option>
                    </select>
                    <select id="levelFilter">
                        <option value="">Tous les niveaux</option>
                        <option value="Licence 1">Licence 1</option>
                        <option value="Licence 2">Licence 2</option>
                        <option value="Licence 3">Licence 3</option>
                        <option value="Master 1">Master 1</option>
                        <option value="Master 2">Master 2</option>
                        <option value="Doctorat">Doctorat</option>
                    </select>
                    <select id="typeFilter">
                        <option value="">Tous les types</option>
                        <option value="epreuve">Épreuves</option>
                        <option value="cours">Supports de Cours</option>
                        <option value="compilation">Compilations (Packs)</option>
                        <option value="td_tp">TD / TP</option>
                    </select>
                    <select id="semesterFilter">
                        <option value="">Tous les semestres</option>
                        <option value="S1">Semestre 1 (S1)</option>
                        <option value="S2">Semestre 2 (S2)</option>
                        <option value="Annuel">Annuel</option>
                    </select>
                </div>
                <div class="results-info" style="margin-top: 1.2rem; display: flex; justify-content: space-between; align-items: center;">
                    <span id="resultsCount" style="font-weight: 700; color: var(--bleu-nuit); font-size: 0.9rem;"></span>
                    <button id="resetFiltersBtn" class="btn-secondary" style="display:none; padding: 0.4rem 1.2rem; font-size: 0.75rem; border: 1px solid #ddd;">Réinitialiser</button>
                </div>
            </section>

            <section id="documentsList" class="documents-grid" style="margin-bottom: 4rem;">
                <!-- Documents injected by JS -->
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

    <script src="js/main.js"></script>
</body>
</html>
