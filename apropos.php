<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos & Contact - UCAO Docs</title>
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
                <li><a href="ecoles.php">Écoles & Filières</a></li>
                <li><a href="apropos.php" class="active">À propos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero" style="padding: 4rem 10%; margin-bottom: 3rem;">
            <h2>À Propos de UCAO Docs</h2>
            <p>Découvrez l'histoire, la vision et les engagements derrière la première plateforme numérique d'archivage académique de l'UCAO-Bénin.</p>
        </section>

        <div class="container">
            <section class="info-container bg-white" style="text-align: left; padding: 4rem; border: 1px solid #eee;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;">
                    <div>
                        <h3 style="color: var(--bleu-nuit); font-size: 1.8rem; margin-bottom: 1.5rem;">Notre Vision Stratégique</h3>
                        <p style="margin-bottom: 1.5rem;">UCAO Docs est né de la volonté de moderniser l'accès aux ressources académiques au sein de l'UCAO-Bénin. Notre objectif est de préserver le patrimoine intellectuel de l'université et de fournir aux étudiants un outil performant pour leur réussite académique.</p>
                        <p>En digitalisant nos archives, nous garantissons non seulement leur sécurité face aux aléas du temps, mais nous offrons également une égalité des chances à tous nos étudiants en mettant à leur disposition les mêmes ressources de qualité.</p>
                    </div>
                    <div style="background: var(--gris-clair); padding: 2rem; border-radius: 15px; border-left: 5px solid var(--rouge-nuit);">
                        <h4 style="color: var(--bleu-nuit); margin-bottom: 1rem; text-transform: uppercase;">Nos Valeurs</h4>
                        <ul style="list-style: none;">
                            <li style="margin-bottom: 0.8rem;"><i class="fas fa-check-circle" style="color: var(--rouge-nuit)"></i> <strong>Accessibilité :</strong> Le savoir pour tous, partout.</li>
                            <li style="margin-bottom: 0.8rem;"><i class="fas fa-check-circle" style="color: var(--rouge-nuit)"></i> <strong>Innovation :</strong> Utiliser le meilleur de la tech pour l'éducation.</li>
                            <li style="margin-bottom: 0.8rem;"><i class="fas fa-check-circle" style="color: var(--rouge-nuit)"></i> <strong>Excellence :</strong> Une sélection rigoureuse des ressources.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="section-title" style="margin-top: 4rem;">
                <h2>Nous Contacter</h2>
                <p>Besoin d'aide ou envie de contribuer ? Notre équipe est à votre disposition.</p>
            </section>

            <div class="contact-grid" style="margin-bottom: 4rem;">
                <div class="contact-info" style="background: var(--blanc); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow);">
                    <div style="margin-bottom: 2rem;">
                        <h4 style="color: var(--bleu-nuit); margin-bottom: 0.8rem;"><i class="fas fa-map-marker-alt" style="color: var(--rouge-nuit)"></i> LOCALISATION</h4>
                        <p style="font-size: 0.9rem;">Campus Universitaire UCAO, Cotonou - Bénin</p>
                    </div>
                    <div style="margin-bottom: 2rem;">
                        <h4 style="color: var(--bleu-nuit); margin-bottom: 0.8rem;"><i class="fas fa-envelope" style="color: var(--rouge-nuit)"></i> SUPPORT TECHNIQUE</h4>
                        <p style="font-size: 0.9rem;">contact@ucaobenin.org</p>
                    </div>
                    <div style="margin-bottom: 2rem;">
                        <h4 style="color: var(--bleu-nuit); margin-bottom: 0.8rem;"><i class="fas fa-phone-alt" style="color: var(--rouge-nuit)"></i> SECRÉTARIAT</h4>
                        <p style="font-size: 0.9rem;">+229 XX XX XX XX</p>
                    </div>
                </div>
                <div class="contact-form" style="background: var(--blanc); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow);">
                    <form action="#" method="POST" onsubmit="alert('Votre message a été transmis avec succès. Merci !'); return false;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <input type="text" placeholder="Nom complet" required>
                            <input type="email" placeholder="Email institutionnel" required>
                        </div>
                        <input type="text" placeholder="Objet" required style="margin-top: 1.2rem;">
                        <textarea rows="4" placeholder="Votre message ou feedback" required></textarea>
                        <button type="submit" class="btn btn-primary" style="width: 100%; letter-spacing: 1px;">ENVOYER</button>
                    </form>
                </div>
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
</body>
</html>
