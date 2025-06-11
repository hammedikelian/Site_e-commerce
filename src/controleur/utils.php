<?php

function getPanierData($db) {
    if (!isset($_SESSION['user'])) return ['panier' => [], 'total' => 0];

    $userId = $_SESSION['user']['id'];

    $stmt = $db->prepare("
        SELECT pa.id as panier_id, pr.id as produit_id, pr.nom, pr.image, pr.prix, pa.quantite
        FROM panier pa
        JOIN produits pr ON pa.produit_id = pr.id
        WHERE pa.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $userId]);
    $panier = $stmt->fetchAll();

    $total = 0;
    foreach ($panier as $item) {
        $total += $item['prix'] * $item['quantite'];
    }

    return ['panier' => $panier, 'total' => $total];
}
