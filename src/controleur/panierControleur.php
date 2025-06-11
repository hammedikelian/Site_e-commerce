<?php
require_once __DIR__ . '/utils.php';

function panierControleur($db, $twig) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header('Location: ?page=login');
        exit;
    }

    $userId = $_SESSION['user']['id'];

    // === AJOUT AU PANIER ===
    if (isset($_GET['action']) && $_GET['action'] === 'ajouter' && isset($_GET['id'])) {
        $produitId = intval($_GET['id']);

        $check = $db->prepare("SELECT * FROM panier WHERE user_id = :user_id AND produit_id = :produit_id");
        $check->execute(['user_id' => $userId, 'produit_id' => $produitId]);
        $row = $check->fetch();

        if ($row) {
            $update = $db->prepare("UPDATE panier SET quantite = quantite + 1 WHERE id = :id");
            $update->execute(['id' => $row['id']]);
        } else {
            $insert = $db->prepare("INSERT INTO panier (user_id, produit_id, quantite) VALUES (:user_id, :produit_id, 1)");
            $insert->execute(['user_id' => $userId, 'produit_id' => $produitId]);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // === SUPPRIMER ===
    if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
        $idPanier = intval($_GET['id']);
        $delete = $db->prepare("DELETE FROM panier WHERE id = :id AND user_id = :user_id");
        $delete->execute(['id' => $idPanier, 'user_id' => $userId]);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // === AUGMENTER ===
    if (isset($_GET['action']) && $_GET['action'] === 'augmenter' && isset($_GET['id'])) {
        $idPanier = intval($_GET['id']);
        $update = $db->prepare("UPDATE panier SET quantite = quantite + 1 WHERE id = :id AND user_id = :user_id");
        $update->execute(['id' => $idPanier, 'user_id' => $userId]);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // === DIMINUER ===
    if (isset($_GET['action']) && $_GET['action'] === 'diminuer' && isset($_GET['id'])) {
        $idPanier = intval($_GET['id']);
        $check = $db->prepare("SELECT quantite FROM panier WHERE id = :id AND user_id = :user_id");
        $check->execute(['id' => $idPanier, 'user_id' => $userId]);
        $row = $check->fetch();

        if ($row) {
            if ($row['quantite'] <= 1) {
                $delete = $db->prepare("DELETE FROM panier WHERE id = :id AND user_id = :user_id");
                $delete->execute(['id' => $idPanier, 'user_id' => $userId]);
            } else {
                $update = $db->prepare("UPDATE panier SET quantite = quantite - 1 WHERE id = :id AND user_id = :user_id");
                $update->execute(['id' => $idPanier, 'user_id' => $userId]);
            }
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Pas de rendu ici, le panier est injecté dans base.html.twig via getPanierData
}
