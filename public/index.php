<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/parametres.php';
require_once __DIR__ . '/../config/connexion.php';
require_once __DIR__ . '/../config/routes.php';

require_once __DIR__ . '/../lib/vendor/autoload.php'; // Twig
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../src/vue');
$twig = new \Twig\Environment($loader, ['debug' => true]);

$twig->addGlobal('app', [
    'session' => $_SESSION ?? []
]);


$db = connect();
getPage($db, $twig);
