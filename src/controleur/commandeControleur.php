<?php
require_once __DIR__ . '/utils.php';

function commandeControleur($db, $twig) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: ?page=login');
        exit;
    }

    $userId = $_SESSION['user']['id'];
    $dataPanier = getPanierData($db);

    if (empty($dataPanier['panier'])) {
        header('Location: ?page=produits');
        exit;
    }

    $etape = $_GET['etape'] ?? 'panier';

    // Traitement infos livraison
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $etape === 'livraison') {
        $adresse = trim($_POST['adresse']);
        $ville = trim($_POST['ville']);
        $code_postal = trim($_POST['code_postal']);
        $pays = trim($_POST['pays']);

        $erreurs = [];

        if (!preg_match('/^[\p{L}0-9\s\-\,\.]{5,}$/u', $adresse)) {
            $erreurs[] = "Adresse invalide.";
        }

        if (!preg_match('/^[\p{L}\s\-]{2,}$/u', $ville)) {
            $erreurs[] = "Ville invalide.";
        }

        if (!preg_match('/^\d{5}$/', $code_postal)) {
            $erreurs[] = "Code postal invalide.";
        }

        if (!preg_match('/^[\p{L}\s\-]{2,}$/u', $pays)) {
            $erreurs[] = "Pays invalide.";
        }

        if (!empty($erreurs)) {
            echo $twig->render('commande.html.twig', array_merge($dataPanier, [
                'etape' => 'livraison',
                'livraison' => compact('adresse', 'ville', 'code_postal', 'pays'),
                'erreurs' => $erreurs
            ]));
            return;
        }

        $_SESSION['commande']['livraison'] = compact('adresse', 'ville', 'code_postal', 'pays');
        header('Location: ?page=commande&etape=paiement');
        exit;
    }

    // Traitement paiement simulé
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $etape === 'paiement') {
        $_SESSION['commande']['paiement'] = 'valide';
        header('Location: ?page=commande&etape=confirmation');
        exit;
    }

    echo $twig->render('commande.html.twig', array_merge($dataPanier, [
        'etape' => $etape,
        'livraison' => $_SESSION['commande']['livraison'] ?? null,
        'erreurs' => []
    ]));
}
