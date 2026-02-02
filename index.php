<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCAO Docs - Plateforme d'Archives</title>
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
                <li><a href="index.php" class="active">Accueil</a></li>
                <li><a href="archives.php">Archives</a></li>
                <li><a href="ecoles.php">Écoles & Filières</a></li>
                <li><a href="apropos.php">À propos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2>L'Excellence Académique à Portée de Main</h2>
            <p>Bienvenue sur la plateforme officielle de gestion documentaire de l'UCAO. Un espace centralisé, sécurisé et intelligent conçu pour accompagner chaque étudiant et enseignant dans sa quête d'excellence et de savoir.</p>
            <div class="cta-buttons">
                <a href="archives.php" class="btn btn-primary">Consulter les Archives</a>
                <a href="ecoles.php" class="btn btn-secondary">Découvrir les Filières</a>
            </div>
        </section>

        <div class="container">
            <section class="section-title">
                <h2>Nos Services & Avantages</h2>
                <p>Une solution digitale complète pour une gestion académique moderne.</p>
            </section>

            <section class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-file-archive"></i>
                    <h3>Patrimoine Numérique</h3>
                    <p>Accédez à un historique complet d'épreuves, de cours magistraux et de ressources pédagogiques validées.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-search"></i>
                    <h3>Recherche Intelligente</h3>
                    <p>Trouvez instantanément vos documents grâce à notre moteur de recherche multicritères et nos filtres par faculté.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Accès Sécurisé</h3>
                    <p>Vos documents sont protégés et accessibles à tout moment, garantissant la pérennité des ressources de l'UCAO.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-cloud-download-alt"></i>
                    <h3>Téléchargement Rapide</h3>
                    <p>Profitez d'une bande passante optimisée pour récupérer vos supports de cours en un seul clic, sans attente.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>Espace Collaboratif</h3>
                    <p>Une plateforme qui favorise le partage des connaissances entre les différentes écoles de notre université.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Qualité Certifiée</h3>
                    <p>Tous les documents archivés passent par un processus de vérification pour garantir leur pertinence académique.</p>
                </div>
            </section>

            <section class="info-container">
                <h2>À l'Attention de la Communauté UCAO</h2>
                <p>Cette plateforme n'est pas qu'un simple outil d'archivage ; c'est le pilier de notre transition numérique. En centralisant les ressources pédagogiques de toutes nos facultés, nous brisons les barrières de l'accès à l'information et offrons à nos futurs diplômés les meilleurs outils pour réussir leurs examens et concours.</p>
                <div style="margin-top: 2rem;">
                    <a href="apropos.php" class="btn btn-secondary" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid white;">En savoir plus sur notre mission</a>
                </div>
            </section>

            <section class="section-title" style="margin-top: 5rem;">
                <h2>Écoles & Facultés d'Excellence</h2>
                <p>L'UCAO-Bénin regroupe des établissements de prestige formant les leaders de demain.</p>
            </section>

            <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
                <div style="background: white; padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow); border-bottom: 4px solid var(--bleu-nuit); text-align: center;">
                    <i class="fas fa-microchip" style="font-size: 2.5rem; color: var(--bleu-nuit); margin-bottom: 1.5rem;"></i>
                    <h4 style="color: var(--bleu-nuit); margin-bottom: 1rem; text-transform: uppercase;">EGEI</h4>
                    <p style="font-size: 0.9rem; margin-bottom: 1.5rem;">École de Génie Électrique et Informatique - La pointe de la technologie et de l'innovation numérique.</p>
                </div>
                <div style="background: white; padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow); border-bottom: 4px solid var(--rouge-nuit); text-align: center;">
                    <i class="fas fa-chart-line" style="font-size: 2.5rem; color: var(--rouge-nuit); margin-bottom: 1.5rem;"></i>
                    <h4 style="color: var(--bleu-nuit); margin-bottom: 1rem; text-transform: uppercase;">ESMEA</h4>
                    <p style="font-size: 0.9rem; margin-bottom: 1.5rem;">École de Management et Économie Appliquée - Former les gestionnaires et économistes de haut niveau.</p>
                </div>
                <div style="background: white; padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow); border-bottom: 4px solid var(--bleu-clair); text-align: center;">
                    <i class="fas fa-balance-scale" style="font-size: 2.5rem; color: var(--bleu-clair); margin-bottom: 1.5rem;"></i>
                    <h4 style="color: var(--bleu-nuit); margin-bottom: 1rem; text-transform: uppercase;">FDE</h4>
                    <p style="font-size: 0.9rem; margin-bottom: 1.5rem;">Faculté de Droit et d'Économie - L'excellence juridique au service du développement.</p>
                </div>
            </section>

            <div style="text-align: center; margin-bottom: 6rem;">
                <a href="ecoles.php" class="btn btn-primary" style="padding: 1.2rem 3rem;">Voir toutes les Écoles & Filières <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
            </div>
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
