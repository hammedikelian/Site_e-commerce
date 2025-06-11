<?php
function connect() {
    global $config;  // Utilise la variable globale
    try {
        $pdo = new PDO(
            "mysql:host=" . $config['serveur'] . ";dbname=" . $config['db'] . ";charset=utf8",
            $config['login'],
            $config['mdp']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        return null;
    }
}
