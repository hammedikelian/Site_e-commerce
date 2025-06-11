<?php
// Activer l'affichage des erreurs (important pour debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
require_once "../config/parametres.php";
require_once "../config/connexion.php";
$db = connect(); // Appel de la fonction connect()

// Test de la connexion à la base de données
if ($db) {
    echo "✅ Connexion à la base de données réussie !<br>";

    // Test d'une requête simple pour vérifier la connexion
    $stmt = $db->query("SELECT 1");
    if ($stmt) {
        echo "✅ Requête simple exécutée avec succès !<br>";
    } else {
        echo "❌ La requête n'a pas fonctionné.<br>";
    }
} else {
    echo "❌ Connexion à la base de données échouée.<br>";
}

// Test de la session
session_start();
if (isset($_SESSION['user'])) {
    echo "✅ Utilisateur connecté : " . $_SESSION['user']['prenom'] . " " . $_SESSION['user']['nom'] . "<br>";
} else {
    echo "❌ Aucun utilisateur connecté. La session est vide.<br>";
}
?>
