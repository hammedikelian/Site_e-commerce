<?php
require_once __DIR__ . '/utils.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function validerControleur($db, $twig) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user'])) {
        header("Location: ?page=login");
        exit;
    }

    $userId = $_SESSION['user']['id'];

    // Etape 1 : Livraison
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['step'] === 'livraison') {
        $adresse = trim($_POST['adresse']);
        $ville = trim($_POST['ville']);
        $code_postal = trim($_POST['code_postal']);
        $pays = trim($_POST['pays']);

        if (!preg_match('/^[\p{L}0-9\s\-\,\.]{5,}$/u', $adresse)) die("❌ Adresse invalide");
        if (!preg_match('/^[\p{L}\s\-]{2,}$/u', $ville)) die("❌ Ville invalide");
        if (!preg_match('/^\d{5}$/', $code_postal)) die("❌ Code postal invalide");
        if (!preg_match('/^[\p{L}\s\-]{2,}$/u', $pays)) die("❌ Pays invalide");

        $_SESSION['livraison'] = [
            'adresse' => htmlspecialchars($adresse),
            'ville' => htmlspecialchars($ville),
            'code_postal' => htmlspecialchars($code_postal),
            'pays' => htmlspecialchars($pays),
        ];

        header("Location: ?page=valider&etape=paiement");
        exit;
    }

    // Etape 2 : Paiement
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['step'] === 'paiement') {
        try {
            $stmt = $db->prepare("SELECT p.id AS produit_id, p.nom, p.prix, p.prix_promo, pa.quantite
                                  FROM panier pa
                                  JOIN produits p ON pa.produit_id = p.id
                                  WHERE pa.user_id = :user_id");
            $stmt->execute(['user_id' => $userId]);
            $articles = $stmt->fetchAll();

            if (empty($articles)) {
                die("❌ Panier vide.");
            }

            $totalCommande = 0;
            $commandeId = time();

            foreach ($articles as $item) {
                // 🔍 Vérifier le stock disponible
                $checkStock = $db->prepare("SELECT stock FROM produits WHERE id = :id");
                $checkStock->execute(['id' => $item['produit_id']]);
                $stockDispo = (int) $checkStock->fetchColumn();

                if ($item['quantite'] > $stockDispo) {
                    // 👉 Préparer les données pour le template
                    $data = getPanierData($db);
                    $data['etape'] = 'paiement';
                    $data['livraison'] = $_SESSION['livraison'] ?? [];
                    $data['erreur_stock'] = "❌ Stock insuffisant pour le produit <strong>" . htmlspecialchars($item['nom']) . "</strong>. 
                    Quantité demandée : <strong>" . $item['quantite'] . "</strong>, 
                    stock disponible : <strong>" . $stockDispo . "</strong>.";

                    echo $twig->render('commande.html.twig', $data);
                    return;
                }

                // 💾 Traitement normal
                $prixUnitaire = $item['prix_promo'] > 0 ? $item['prix_promo'] : $item['prix'];
                $total = $prixUnitaire * $item['quantite'];
                $totalCommande += $total;

                $insert = $db->prepare("INSERT INTO ventes (produit_id, quantite, total, user_id, statut, commande_id)
                                        VALUES (:produit_id, :quantite, :total, :user_id, 'en attente', :commande_id)");
                $insert->execute([
                    'produit_id' => $item['produit_id'],
                    'quantite' => $item['quantite'],
                    'total' => $total,
                    'user_id' => $userId,
                    'commande_id' => $commandeId
                ]);

                $updateStock = $db->prepare("UPDATE produits SET stock = stock - :quantite WHERE id = :produit_id");
                $updateStock->execute([
                    'quantite' => $item['quantite'],
                    'produit_id' => $item['produit_id']
                ]);
            }


            $db->prepare("DELETE FROM panier WHERE user_id = :user_id")->execute(['user_id' => $userId]);
            unset($_SESSION['livraison']);

            header("Location: ?page=valider&etape=confirmation");
            exit;

        } catch (Throwable $e) {
            echo "<pre>💥 Erreur : " . $e->getMessage() . "\nFichier : " . $e->getFile() . "\nLigne : " . $e->getLine() . "</pre>";
            exit;
        }
    }

    // Affichage
    $etape = $_GET['etape'] ?? 'panier';
    $data = getPanierData($db);
    $data['etape'] = $etape;
    $data['livraison'] = $_SESSION['livraison'] ?? [];

    echo $twig->render('commande.html.twig', $data);
}
