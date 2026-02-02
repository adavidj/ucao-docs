<?php
session_start();
require_once '../php/config.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Identifiants invalides.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - UCAO Docs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bleu-nuit: #1a237e;
            --rouge-nuit: #b71c1c;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 {
            color: var(--bleu-nuit);
            margin-bottom: 2rem;
        }
        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: var(--bleu-nuit);
        }
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--bleu-nuit);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #0d1440;
        }
        .error-msg {
            color: var(--rouge-nuit);
            background: #ffebee;
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="https://ui-avatars.com/api/?name=UCAO&background=1a237e&color=fff" style="border-radius: 50%; width: 60px; margin-bottom: 1rem;">
        <h2>Espace Admin</h2>
        
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Adresse Email</label>
                <input type="email" name="email" required placeholder="admin@ucao.bj">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-login">Se Connecter</button>
        </form>
        <p style="margin-top: 1.5rem; font-size: 0.8rem; color: #888;">&copy; 2026 UCAO Archives - Tous droits réservés.</p>
    </div>
</body>
</html>