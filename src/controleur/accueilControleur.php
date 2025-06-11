<?php
require_once __DIR__ . '/utils.php';
function accueilControleur($db, $twig) {
    // Si tu as des données spécifiques à passer, ajoute-les ici
    $data = getPanierData($db);

    echo $twig->render('accueil.html.twig', $data);
}
