voici le code sql de ma base de données 


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mer. 11 juin 2025 à 18:13
-- Version du serveur : 8.0.35
-- Version de PHP : 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `e-comerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `approvisionnements`
--

CREATE TABLE `approvisionnements` (
  `id` int NOT NULL,
  `produit_id` int NOT NULL,
  `quantite` int NOT NULL,
  `type_livraison` enum('standard','express') NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_reception_prevue` datetime DEFAULT NULL,
  `statut` enum('commandée','reçue') DEFAULT 'commandée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `approvisionnements`
--

INSERT INTO `approvisionnements` (`id`, `produit_id`, `quantite`, `type_livraison`, `prix_total`, `date_commande`, `date_reception_prevue`, `statut`) VALUES
(1, 2, 2, 'express', 401.99, '2025-05-18 18:27:50', '2025-05-18 18:27:51', 'reçue'),
(2, 2, 1, 'express', 202.99, '2025-05-18 19:24:17', '2025-05-18 19:24:18', 'reçue'),
(3, 3, 1, 'express', 402.99, '2025-05-18 20:24:47', '2025-05-18 20:24:48', 'reçue'),
(4, 3, 1, 'express', 402.99, '2025-05-20 18:32:28', '2025-05-20 18:32:29', 'reçue'),
(5, 3, 1, 'express', 402.99, '2025-05-20 18:33:50', '2025-05-20 18:33:51', 'reçue'),
(6, 1, 3, 'express', 300.99, '2025-05-22 14:25:18', '2025-05-22 14:25:18', 'reçue'),
(7, 3, 1, 'express', 402.99, '2025-05-22 15:03:55', '2025-05-22 15:03:55', 'reçue'),
(8, 1, 1, 'standard', 99.00, '2025-05-22 15:04:09', '2025-05-24 15:04:09', 'reçue'),
(9, 4, 6, 'express', 603.99, '2025-05-25 19:09:26', '2025-05-25 19:09:27', 'reçue'),
(10, 1, 8, 'express', 795.99, '2025-06-08 20:46:36', '2025-06-08 20:46:37', 'reçue'),
(11, 3, 9, 'express', 3594.99, '2025-06-08 20:59:58', '2025-06-08 20:59:58', 'reçue'),
(12, 11, 3, 'express', 753.99, '2025-06-10 18:47:12', '2025-06-10 18:47:12', 'reçue'),
(13, 2, 2, 'express', 401.99, '2025-06-11 11:12:25', '2025-06-11 11:12:26', 'reçue');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `produit_id` int NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `prix_achat` decimal(10,2) DEFAULT '0.00',
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP,
  `stock` int DEFAULT '0',
  `marque` varchar(255) DEFAULT NULL,
  `prix_promo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `image`, `prix`, `prix_achat`, `date_ajout`, `stock`, `marque`, `prix_promo`) VALUES
(1, 'ID 4', 'Interface audio USB-C compacte, 1 entrées / 1 sorties avec préamplis Audient de haute qualité.', 'assets/img/id4.png', 149.00, 99.00, '2025-04-10 00:33:53', 2, 'Audient', 0.00),
(2, 'ID 14', 'Interface audio USB-C 2 entrées / 2 sorties avec préamplis Audient et conversion audio de haute qualité.', 'assets/img/id14.png', 249.00, 199.00, '2025-04-10 00:33:53', 2, 'Audient', 200.00),
(3, 'ID 44', 'Interface audio USB-C 4 entrées / 4 sorties avec préamplis Audient et conversion audio de haute qualité.', 'assets/img/id44.png', 599.00, 399.00, '2025-04-10 00:33:53', 8, 'Audient', 0.00),
(4, 'ID 48', 'Interface audio USB-C 8 entrées / 8 sorties avec préamplis  Audient, convertisseurs 32 bits ESS et insert analogique', 'assets/img/iD48.png', 899.00, 100.00, '2025-05-25 18:52:20', 16, 'Audient', 0.00),
(11, 'SM7B', 'Microphone dynamique cardioïde pour voix, idéal studio et podcast, avec réponse en fréquence large et blindage contre les interférences.', 'assets/IMG/SM7B.jpeg', 389.99, 250.00, '2025-06-10 17:51:39', 8, 'SHURE', NULL),
(12, 'MV6', 'Microphone hybride XLR/USB pour voix, conçu pour le podcast et le streaming, avec traitement vocal intégré et sortie casque.', 'assets/img/MV6.jpg.avif', 148.00, 99.00, '2025-06-10 18:10:03', 13, 'SHURE', NULL),
(13, 'HS8', 'Enceinte de studio 8\" bi-amplifiée, offrant une réponse précise et linéaire, idéale pour le mixage et la production musicale.', 'assets/img/HS8.jpg', 350.00, 250.00, '2025-06-10 18:13:21', 14, 'YAMAHA', NULL),
(14, 'HS8 W', 'Enceinte de studio 8\" bi-amplifiée en finition blanche, avec réponse linéaire et précision idéale pour le mixage professionnel.', 'assets/IMG/HS8w.jpg', 380.00, 270.00, '2025-06-10 18:16:19', 13, 'YAMAHA', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `telephone`, `password`, `role`) VALUES
(3, 'gomez', 'Antoine', 'antoine@gmail.com', '0606060606', '$2y$10$0jmp.tfiN7mtFhO29axkLO36SWL0LMHOmbJ1bu853cVB0E/vB0y5a', 'user'),
(4, 'hammedi', 'kelian', 'hammedikelian@gmail.com', '0661171285', '$2y$10$PmvPnQBN2U9xQNcCZ/3s8.Nrbkh9SfkJSFRcj5LTPk5zt..31gAlK', 'super_admin'),
(8, 'prade', 'xinyu', 'prade@gmail.com', '06 06 06 06 06', '$2y$10$5XFBwXtKCO3CX1akvpehNefGT97TTI5eYXXod1dheYV5ZXhGlsoBu', 'super_admin'),
(10, 'testt', 'test', 'test@test.test', '09 09 09 09 90', '$2y$10$AixRVDbTkSJkKJPkfhY0fO.DcnKevIySjrBBAgS5WLh3YyZWcunge', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE `ventes` (
  `id` int NOT NULL,
  `produit_id` int NOT NULL,
  `quantite` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date_vente` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `statut` enum('en attente','expédiée') DEFAULT 'en attente',
  `commande_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ventes`
--

INSERT INTO `ventes` (`id`, `produit_id`, `quantite`, `total`, `date_vente`, `user_id`, `statut`, `commande_id`) VALUES
(53, 4, 3, 2697.00, '2025-06-10 18:40:56', 10, 'expédiée', 1749573656),
(54, 12, 2, 296.00, '2025-06-10 18:40:56', 10, 'expédiée', 1749573656),
(55, 13, 2, 700.00, '2025-06-10 18:40:56', 10, 'expédiée', 1749573656),
(56, 14, 1, 380.00, '2025-06-10 18:40:56', 10, 'expédiée', 1749573656),
(57, 3, 1, 599.00, '2025-06-10 18:42:36', 10, 'en attente', 1749573756),
(58, 3, 1, 599.00, '2025-06-11 11:18:17', 10, 'expédiée', 1749633497),
(59, 11, 3, 1169.97, '2025-06-11 11:18:17', 10, 'expédiée', 1749633497);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `approvisionnements`
--
ALTER TABLE `approvisionnements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit_id` (`produit_id`),
  ADD KEY `fk_ventes_user` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `approvisionnements`
--
ALTER TABLE `approvisionnements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `approvisionnements`
--
ALTER TABLE `approvisionnements`
  ADD CONSTRAINT `approvisionnements_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `ventes`
--
ALTER TABLE `ventes`
  ADD CONSTRAINT `fk_ventes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ventes_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
