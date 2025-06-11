<?php
require_once __DIR__ . '/utils.php';

function panierAjaxControleur($db) {
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    header('Content-Type: application/json');

    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'error' => 'Non connecté']);
        exit;
    }

    $userId = $_SESSION['user']['id'];

    if (!isset($_POST['action']) || !isset($_POST['id'])) {
        echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
        exit;
    }

    $idPanier = intval($_POST['id']);
    $action = $_POST['action'];

    switch ($action) {
        case 'augmenter':
            $stmt = $db->prepare("UPDATE panier SET quantite = quantite + 1 WHERE id = :id AND user_id = :user_id");
            $stmt->execute(['id' => $idPanier, 'user_id' => $userId]);
            break;

        case 'diminuer':
            $check = $db->prepare("SELECT quantite FROM panier WHERE id = :id AND user_id = :user_id");
            $check->execute(['id' => $idPanier, 'user_id' => $userId]);
            $row = $check->fetch();

            if ($row) {
                if ($row['quantite'] <= 1) {
                    $del = $db->prepare("DELETE FROM panier WHERE id = :id AND user_id = :user_id");
                    $del->execute(['id' => $idPanier, 'user_id' => $userId]);
                } else {
                    $up = $db->prepare("UPDATE panier SET quantite = quantite - 1 WHERE id = :id AND user_id = :user_id");
                    $up->execute(['id' => $idPanier, 'user_id' => $userId]);
                }
            }
            break;

        case 'supprimer':
            $stmt = $db->prepare("DELETE FROM panier WHERE id = :id AND user_id = :user_id");
            $stmt->execute(['id' => $idPanier, 'user_id' => $userId]);
            break;
    }

    // Retourner les données mises à jour
    echo json_encode(getPanierData($db));
    exit;
}
