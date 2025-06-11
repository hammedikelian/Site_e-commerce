<?php
require_once __DIR__ . '/utils.php';
function logoutControleur($db, $twig) {
    session_start();
    session_unset();
    session_destroy();
    header('Location: ?page=accueil');
    exit;
}
