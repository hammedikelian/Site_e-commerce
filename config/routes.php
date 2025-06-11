<?php

function getPage($db, $twig) {
    $lesPages = [
        'accueil' => 'accueilControleur',
        'login' => 'loginControleur',
        'produits' => 'produitsControleur',
        'logout' => 'logoutControleur',
        'panier' => 'panierControleur',
        'panier_ajax' => 'panierAjaxControleur',
        'valider' => 'validerControleur',
    ];

    $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'accueil';

    if (!isset($lesPages[$page])) {
        $page = 'accueil';
    }

    $controllerName = $lesPages[$page];
    $controllerPath = dirname(__DIR__) . "/src/controleur/" . $controllerName . ".php";

    if (file_exists($controllerPath)) {
        require_once($controllerPath);

        // Cas spécial pour le contrôleur AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $controllerName === 'panierAjaxControleur') {
            $controllerName($db);
            return;
        }

        if (function_exists($controllerName)) {
            $controllerName($db, $twig);
        } else {
            echo "Erreur : la fonction $controllerName n'existe pas.";
        }
    } else {
        echo "Erreur : le contrôleur '$controllerName' est introuvable.";
    }
}