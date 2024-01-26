-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 24 jan. 2024 à 14:04
-- Version du serveur : 8.0.35
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `epicerie`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `category_order`) VALUES
(35, NULL, 'Produits frais', 'produit frais', 1),
(36, 35, 'Fruits', 'fruits', 2),
(37, 35, 'Legumes', 'legumes', 3),
(41, 35, 'Viandes', 'viandes', 4),
(42, 35, 'Fromages', 'fromages', 5);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240110230525', '2024-01-11 14:55:58', 57),
('DoctrineMigrations\\Version20240111134224', '2024-01-11 15:01:14', 53),
('DoctrineMigrations\\Version20240111135504', '2024-01-11 15:01:14', 45),
('DoctrineMigrations\\Version20240111140450', '2024-01-11 15:04:54', 60),
('DoctrineMigrations\\Version20240111141025', '2024-01-11 15:10:29', 53),
('DoctrineMigrations\\Version20240111141226', '2024-01-11 15:12:30', 20),
('DoctrineMigrations\\Version20240111141907', '2024-01-11 15:19:11', 123),
('DoctrineMigrations\\Version20240112095158', '2024-01-12 10:52:41', 20),
('DoctrineMigrations\\Version20240112145637', '2024-01-12 15:56:48', 16),
('DoctrineMigrations\\Version20240119092327', '2024-01-19 10:23:37', 53),
('DoctrineMigrations\\Version20240120124526', '2024-01-20 13:46:50', 18),
('DoctrineMigrations\\Version20240120140146', '2024-01-20 15:01:49', 29),
('DoctrineMigrations\\Version20240120145334', '2024-01-20 16:59:12', 64),
('DoctrineMigrations\\Version20240122093233', '2024-01-22 10:32:36', 113),
('DoctrineMigrations\\Version20240122095548', '2024-01-22 10:55:53', 161);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `products_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `name`, `products_id`) VALUES
(25, '63de813a284890b86007035ad91b1297.jpg', 26),
(26, '4a56be6382313a92b6f5a44c60a67efe.jpg', 27),
(27, '75e4aece7864bcab3f5d89674554c066.jpg', 28),
(28, '2b493ff75505bbc989b0989c63e4b59c.jpg', 29),
(29, 'a0d002dcf85ccf09442921cd6f0308b6.jpg', 30),
(30, '5e90d5f4844687cb997e9e293113d54f.jpg', 31),
(31, '1ec55829fb2b0a14b94b9e59628df0b2.jpg', 32),
(35, 'f9ae4bbfdf1e4d7cf3a363baa4c1d3f3.jpg', 37),
(36, '2543273e410032457e24680262d66561.jpg', 38),
(37, 'd06b5ed6a9eaa6d63f2a22bb6aa7f58f.jpg', 39),
(40, '5c476bcf14f5edebc226fdef7e0213de.webp', 40),
(41, 'ad1d7b0edb893e66525375a6b9c4dcf8.webp', 36),
(42, 'ab0f806141453d1a36724c8e6e77e8c4.webp', 35),
(43, 'a66c46795635d487b65470ed380d70bc.webp', 41),
(44, 'dcf36376998b824daf859c358800d035.webp', 42),
(45, 'a5cfb5d4d54347c44e8584a738916b7f.webp', 43),
(46, 'bf767db0648baafa896036b63e39bfe5.webp', 34),
(47, '1c483439c9d1e4c473841692ee4eb74b.webp', 44),
(48, '199e64a1995ed1f56ed5520b995a2bfc.jpg', 45);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `users_id` int NOT NULL,
  `reference` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `users_id`, `reference`, `created_at`, `status`) VALUES
(1, 22, '202401-00', '2024-01-16 01:08:42', 'Envoyée'),
(3, 22, '202401-02', '2024-01-16 12:17:41', 'En préparation'),
(4, 22, '202401-03', '2024-01-17 23:33:06', ''),
(5, 22, '202401-04', '2024-01-17 23:37:41', ''),
(6, 22, '202401-05', '2024-01-17 23:43:15', ''),
(7, 22, '202401-06', '2024-01-17 23:43:38', ''),
(8, 22, '202401-07', '2024-01-17 23:44:35', ''),
(9, 22, '202401-08', '2024-01-17 23:48:35', ''),
(10, 22, '202401-09', '2024-01-17 23:49:47', ''),
(11, 22, '202401-10', '2024-01-17 23:50:19', ''),
(12, 22, '202401-11', '2024-01-17 23:53:44', ''),
(13, 22, '202401-12', '2024-01-19 09:04:42', ''),
(14, 36, '202401-13', '2024-01-23 12:17:31', 'En préparation'),
(15, 37, '202401-14', '2024-01-23 14:40:41', 'En préparation');

-- --------------------------------------------------------

--
-- Structure de la table `orders_details`
--

CREATE TABLE `orders_details` (
  `orders_id` int NOT NULL,
  `products_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders_details`
--

INSERT INTO `orders_details` (`orders_id`, `products_id`, `quantity`, `price`) VALUES
(1, 26, 1, 1200),
(3, 26, 2, 1200),
(3, 27, 2, 800),
(3, 28, 1, 1300),
(4, 26, 1, 1200),
(5, 28, 1, 1300),
(6, 32, 1, 600),
(7, 26, 1, 1200),
(8, 30, 1, 1400),
(9, 28, 1, 1300),
(10, 31, 1, 300),
(11, 27, 1, 800),
(12, 28, 1, 1300),
(13, 26, 1, 1200),
(13, 28, 1, 1300),
(14, 29, 1, 129),
(14, 30, 2, 763),
(14, 40, 2, 2403),
(14, 42, 1, 732),
(15, 26, 1, 120);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `categories_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `categories_id`, `name`, `description`, `price`, `stock`, `created_at`, `slug`) VALUES
(26, 36, 'Banane', 'Origine Guadeloupe', 120, 3, '2024-01-14 23:37:36', 'Banane'),
(27, 37, 'Tomate', 'Origine France', 320, 6, '2024-01-15 00:45:42', 'Tomate'),
(28, 36, 'Framboise', 'Origine Italie', 499, 5, '2024-01-15 00:50:52', 'Framboise'),
(29, 37, 'Choux', 'Le chou est une excellente source de bêta-carotène (vitamine A), qui aide à la bonne santé de la peau, des os et des yeux. Le chou est une excellente source de vitamine C. Cette dernière nous aide à garder gencives, dents et peau en bonne santé.', 129, 1, '2024-01-15 00:51:21', 'Choux'),
(30, 36, 'Fraise', 'Origine France', 763, 15, '2024-01-15 00:51:55', 'Fraise'),
(31, 36, 'Pomme', 'Origine France', 291, 34, '2024-01-15 00:52:39', 'Pomme'),
(32, 37, 'Pomme de terre', 'Origine France', 532, 20, '2024-01-15 00:52:58', 'Pomme-de-terre'),
(34, 42, 'Camembert', 'Le camembert de Normandie est un fromage au lait cru de vache, à pâte molle légèrement salée et à croûte fleurie', 600, 2, '2024-01-20 15:02:14', 'Camembert'),
(35, 41, 'Bavette', 'Nos pièces de bœuf proviennent de notre propre cheptel ou de bêtes sélectionnées sur pieds chez des éleveurs locaux afin de vous garantir une viande de grande qualité.', 981, 2, '2024-01-20 18:13:24', 'Bavette'),
(36, 41, 'Rôti', 'Nos pièces de bœuf proviennent de notre propre cheptel ou de bêtes sélectionnées sur pieds chez des éleveurs locaux afin de vous garantir une viande de grande qualité.', 1207, 6, '2024-01-20 18:16:27', 'Roti'),
(37, 36, 'Abricot', 'Origine France', 467, 24, '2024-01-20 18:17:23', 'Abricot'),
(38, 36, 'Raisins', 'Origine Italie', 893, 9, '2024-01-20 18:24:09', 'Raisins'),
(39, 36, 'Clémentine', 'Origine Espagne', 220, 6, '2024-01-20 18:27:14', 'Clementine'),
(40, 41, 'Entrecôte', 'Nos pièces de bœuf proviennent de notre propre cheptel ou de bêtes sélectionnées sur pieds chez des éleveurs locaux afin de vous garantir une viande de grande qualité.', 2403, 0, '2024-01-20 19:52:48', 'Entrecote'),
(41, 41, 'Tournedos', 'Nos pièces de bœuf proviennent de notre propre cheptel ou de bêtes sélectionnées sur pieds chez des éleveurs locaux afin de vous garantir une viande de grande qualité.', 2999, 4, '2024-01-20 20:01:52', 'Tournedos'),
(42, 42, 'Conté', 'Le comté tout le monde connaît et tout le monde aime le comté. Il s\'agit de l\'un des fromages phares de la célèbre fromagerie Xavier.\r\nLe comté présent ici, est un comté avec 18 mois d\'affinage, parfait pour relever ses notes fruitées.', 732, 2, '2024-01-20 20:03:04', 'Conte'),
(43, 42, 'Cantal', 'Le cantal est un si ce n\'est le fromage français, le plus ancien, son goût et sa texture seront parfaits pour vos futures dégustations et plateaux-fromages.\r\nLe cantal proposé ici est un cantal dit entre-deux, affiné ici de 6 mois environ.', 864, 6, '2024-01-20 20:04:26', 'Cantal'),
(44, 42, 'Emmental', 'Le fromage le plus consommé en France, aussi bien adoré par les n\'enfants et leurs goûters que par les grands et leurs entrées.\r\nL\'emmental de Savoie possède un goût doux, une pâte ferme, souple et d\'innombrables trous.', 699, 16, '2024-01-20 20:12:26', 'Emmental'),
(45, 37, 'Brocolis', 'Origine France', 258, 17, '2024-01-20 21:01:28', 'Brocolis');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `is_verified` tinyint(1) NOT NULL,
  `reset_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `lastname`, `firstname`, `adress`, `zipcode`, `city`, `created_at`, `is_verified`, `reset_token`) VALUES
(22, 'admin@gmail.com', '[\"ROLE_SUPER_ADMIN\"]', '$2y$13$PzKaSkXmmsQIGB80G2bjn.MOWM2VYuVBLDF5trjexjNt54WBJRvUm', 'Mora-Bouty', 'Aaron', '140 rue Emile Combes', '33000', 'BORDEAUX', '2024-01-11 01:06:56', 1, ''),
(23, 'vmaury@georges.net', '[]', '$2y$13$uLpBYWX.GOSkF.4bnuPoNuX0a2cwQt3rFnf3JZL9LMRnwaBe9Uuli', 'Samson', 'Margot', '992, chemin de Carlier', '65756', 'Lucas', '2024-01-11 01:06:56', 0, ''),
(24, 'margaret.laroche@free.fr', '[]', '$2y$13$9gM2tNqpmzbtZ.uyd1WXi.IOHceRJeG2jkrtPqhOn70vUAHvObs7O', 'Jacques', 'Benoît', '3, avenue de Pelletier', '05286', 'Berger', '2024-01-11 01:06:56', 0, ''),
(25, 'marie53@noos.fr', '[]', '$2y$13$oHDwm8/IeFOgcZG/bZQ/fO9ODZtZAtI/2Hw4Fl7tpBSwoOsmBLByW', 'Couturier', 'Mathilde', '66, rue de Masse', '75945', 'PichonBourg', '2024-01-11 01:06:57', 0, ''),
(26, 'mleconte@cousin.com', '[]', '$2y$13$5Zb/Q/VkJ8hpzEOdiWklYOnZB5k6RVIBfX6YsLWYNqB5vQ/uQiyzu', 'Gay', 'Brigitte', 'rue Stéphane Chauvin', '71002', 'Hoareau', '2024-01-11 01:06:57', 0, ''),
(27, 'honore10@guillou.com', '[]', '$2y$13$ZNM.6zKCG6ZnuW3ZuqQ7f.xQtdUHr/GwP.QVuUzCe3VE0vRF0Gq4C', 'Ferreira', 'Océane', '13, boulevard Riou', '76725', 'Mercier-sur-Humbert', '2024-01-11 01:06:58', 0, ''),
(28, 'admin2@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$XnNmLLen/Uzf7lpWQuQSQewg0vVCTjMRkvs7UOMSZM6Clkis6JqI2', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-12 13:59:41', 0, ''),
(29, 'admin3@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$.eGWs3HKYBnowiQJuXfIQuGlFKM20IzcW17GMDVXiyqJ4KrxJT8JS', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-12 14:00:32', 1, ''),
(30, 'admin4@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$adrX/qw1EP9cNT/k5u3Da..8Wgu.sOA011Ft8rcdNWuhM./9kwaC.', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-12 14:00:55', 0, ''),
(31, 'admin5@gmail.com', '[]', '$2y$13$eogrtYscePLqdxjeYs/JkOwBpUbXKSgbgj18Pk0wop0GyZRUyKl6i', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-12 14:02:33', 1, 'y11KEUBTDP8sHnmsEpL6D6uSXSm3VU4lD7nYgW2DoPs'),
(32, 'admin6@gmail.com', '[]', '$2y$13$U1OoArA.7YzsBSlwS6oF9uroBTw8WBu4YtJCL7rbZ2XVbovlxwCla', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-12 14:34:25', 0, ''),
(33, 'admin7@gmail.com', '[]', '$2y$13$NZ9bNRvsEjXGcG6touC5vev21t2y2isvc8dFdtMmWpQ1Jl0o6G4Fy', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-12 14:35:53', 0, ''),
(36, 'testfinal@gmail.com', '[\"ROLE_USER\"]', '$2y$13$Tkkq6VNEWF9IlBn40RqzTeHUr3lX/hEjqWSEieScpYpnKrHaPLDbG', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-23 12:11:03', 1, ''),
(37, 'testfinal2@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$1Yv6WC92LLp9D/uNDjhfSuTZIBIO74/Ou9NnPDO.vZFdMZBoTKh1S', 'Mora', 'Raphael', '30 rue du tauzin, appt 346', '33000', '33000 - BORDEAUX', '2024-01-23 14:40:38', 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `user_product_like`
--

CREATE TABLE `user_product_like` (
  `products_id` int NOT NULL,
  `users_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_product_like`
--

INSERT INTO `user_product_like` (`products_id`, `users_id`) VALUES
(26, 22),
(26, 23),
(26, 25),
(26, 26),
(26, 32),
(27, 22),
(27, 23),
(27, 27),
(27, 29),
(27, 31),
(28, 22),
(28, 24),
(28, 26),
(28, 27),
(29, 23),
(29, 24),
(29, 30),
(29, 33),
(30, 23),
(30, 28),
(30, 29),
(30, 30),
(30, 31),
(31, 22),
(31, 31),
(31, 33),
(32, 24),
(32, 26),
(32, 27),
(32, 28),
(34, 25),
(34, 26),
(34, 29),
(34, 31),
(34, 33),
(35, 24),
(35, 25),
(35, 29),
(35, 30),
(35, 31),
(35, 33),
(36, 25),
(36, 28),
(36, 31),
(36, 33),
(37, 23),
(37, 28),
(37, 31),
(37, 33),
(38, 23),
(38, 25),
(38, 28),
(38, 31),
(39, 22),
(39, 26),
(39, 27),
(39, 30),
(40, 24),
(40, 26),
(40, 31),
(40, 32),
(41, 26),
(41, 29),
(41, 30),
(42, 25),
(42, 28),
(42, 31),
(43, 23),
(43, 27),
(43, 28),
(43, 30),
(43, 31),
(44, 22),
(44, 23),
(44, 26),
(45, 26),
(45, 31),
(45, 32),
(45, 33);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3AF34668727ACA70` (`parent_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E01FBE6A6C8A81A9` (`products_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E52FFDEEAEA34913` (`reference`),
  ADD KEY `IDX_E52FFDEE67B3B43D` (`users_id`);

--
-- Index pour la table `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`orders_id`,`products_id`),
  ADD KEY `IDX_835379F1CFFE9AD6` (`orders_id`),
  ADD KEY `IDX_835379F16C8A81A9` (`products_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B3BA5A5AA21214B7` (`categories_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- Index pour la table `user_product_like`
--
ALTER TABLE `user_product_like`
  ADD PRIMARY KEY (`products_id`,`users_id`),
  ADD KEY `IDX_A1471E106C8A81A9` (`products_id`),
  ADD KEY `IDX_A1471E1067B3B43D` (`users_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `FK_3AF34668727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `FK_E01FBE6A6C8A81A9` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE67B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `orders_details`
--
ALTER TABLE `orders_details`
  ADD CONSTRAINT `FK_835379F16C8A81A9` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `FK_835379F1CFFE9AD6` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_B3BA5A5AA21214B7` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `user_product_like`
--
ALTER TABLE `user_product_like`
  ADD CONSTRAINT `FK_A1471E1067B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A1471E106C8A81A9` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
