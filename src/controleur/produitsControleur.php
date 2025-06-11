<?php
require_once __DIR__ . '/utils.php';
function produitsControleur($db, $twig) {
    // Récupère la marque sélectionnée depuis l'URL
    $marqueSelectionnee = isset($_GET['marque']) ? htmlspecialchars($_GET['marque']) : '';

    // Récupère toutes les marques disponibles
    $sqlMarques = "SELECT DISTINCT marque FROM produits ORDER BY marque";
    $stmtMarques = $db->prepare($sqlMarques);
    $stmtMarques->execute();
    $marques = $stmtMarques->fetchAll();

    // Récupère les produits (filtrés ou non)
    if ($marqueSelectionnee) {
        $sqlProduits = "SELECT * FROM produits WHERE marque = :marque";
        $stmtProduits = $db->prepare($sqlProduits);
        $stmtProduits->bindParam(':marque', $marqueSelectionnee);
    } else {
        $sqlProduits = "SELECT * FROM produits";
        $stmtProduits = $db->prepare($sqlProduits);
    }

    $stmtProduits->execute();
    $produits = $stmtProduits->fetchAll();

    // Récupère le contenu du panier (quantité + total)
    $data = getPanierData($db);

    // Fusionne toutes les données à envoyer à Twig
    echo $twig->render('produits.html.twig', array_merge([
        'produits' => $produits,
        'marques' => $marques,
        'marqueActive' => $marqueSelectionnee
    ], $data));
}
