<?php
require_once __DIR__ . '/utils.php';

function loginControleur($db, $twig) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $erreursLogin = [];
    $erreursRegister = [];
    $success = '';
    $showRegister = false;

    $valuesRegister = [
        'prenom' => '',
        'nom' => '',
        'email' => '',
        'telephone' => ''
    ];

    // === Connexion ===
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: ?page=accueil");
            exit;
        } else {
            $erreursLogin[] = "Identifiants incorrects.";
        }
    }

    // === Inscription ===
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);
        $email = htmlspecialchars($_POST['email']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        $valuesRegister = compact('prenom', 'nom', 'email', 'telephone');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreursRegister[] = "Email invalide.";
        }

        if ($password !== $confirm) {
            $erreursRegister[] = "Les mots de passe ne correspondent pas.";
        }

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch()) {
            $erreursRegister[] = "Un compte existe déjà avec cet email.";
        }

        // Vérification de la complexité du mot de passe
        if (strlen($password) < 9) {
            $erreursRegister[] = "Le mot de passe doit contenir au moins 9 caractères.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $erreursRegister[] = "Le mot de passe doit contenir au moins une majuscule.";
        }
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $erreursRegister[] = "Le mot de passe doit contenir au moins un caractère spécial.";
        }
        if (preg_match_all('/[0-9]/', $password) < 2) {
            $erreursRegister[] = "Le mot de passe doit contenir au moins deux chiffres.";
        }

        if (empty($erreursRegister)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare("
                INSERT INTO users (prenom, nom, email, telephone, password, role)
                VALUES (:prenom, :nom, :email, :telephone, :password, 'client')
            ");
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':password', $hash);
            $stmt->execute();

            $success = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
        } else {
            $showRegister = true;
        }
    }

    $data = getPanierData($db);

    echo $twig->render('login_register.html.twig', array_merge([
        'erreursLogin' => $erreursLogin,
        'erreursRegister' => $erreursRegister,
        'success' => $success,
        'showRegister' => $showRegister,
        'valuesRegister' => $valuesRegister
    ], $data));
}
