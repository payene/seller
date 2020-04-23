-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 23 déc. 2019 à 09:12
-- Version du serveur :  10.1.37-MariaDB
-- Version de PHP :  7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `smspnbgj_pf`
--

-- --------------------------------------------------------

--
-- Structure de la table `arrivage`
--

CREATE TABLE `arrivage` (
  `id` int(11) NOT NULL,
  `article` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `qte` int(11) NOT NULL,
  `prixAchat` int(11) NOT NULL,
  `tva` int(11) NOT NULL,
  `taxes` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `marge` int(11) NOT NULL,
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `arrivage`
--

INSERT INTO `arrivage` (`id`, `article`, `user`, `qte`, `prixAchat`, `tva`, `taxes`, `createdAt`, `marge`, `updatedAt`) VALUES
(1, 3, 2, 200, 1000, 50000, 15000, '2019-09-29 16:40:11', 500, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `designation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) DEFAULT NULL,
  `defaultPrice` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `source1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `designation`, `description`, `category`, `defaultPrice`, `source1`, `source2`, `source3`, `source4`, `stock`) VALUES
(1, 'Blanco', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/1.jpg', '', '', '', 0),
(2, 'Gomme', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/2.jpg', '', '', '', 0),
(3, 'Agrafeusee', 'Agrafeusee', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/3.jpg', '', '', '', 200),
(4, 'Pot de colle', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/4.jpg', '', '', '', 0),
(5, 'Marqueur', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/5.jpg', '', '', '', 0),
(6, 'Boite Trombone 32mm', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/6.jpg', '', '', '', 0),
(7, 'Post-it 75 x 75 mm', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/7.jpg', '', '', '', 0),
(8, 'Ôte agrafe', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/8.jpg', '', '', '', 0),
(9, 'Règle graduée 30cm', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/9.jpg', '', '', '', 0),
(10, 'Perforateur', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/10.jpg', '', '', '', 0),
(11, 'Pot à crayon', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/11.jpg', '', '', '', 0),
(12, 'Cahier grand format 192 pages', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/12.jpg', '', '', '', 0),
(13, 'Taille crayon', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/13.jpg', '', '', '', 0),
(14, 'Paires de ciseaux', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/14.jpg', '', '', '', 0),
(15, 'Ruban à machine 13mm x10MTRS', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/15.jpg', '', '', '', 0),
(16, 'Encre pour encrier', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/16.jpg', '', '', '', 0),
(17, 'Paquet intercalaire', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/17.jpg', '', '', '', 0),
(18, 'Couverture Toilée cartonnée', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/18.jpg', '', '', '', 0),
(19, 'Couverture PVC transparent', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/19.jpg', '', '', '', 0),
(20, 'Stylo à bille scheneider bleu 505', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/20.jpg', '', '', '', 0),
(21, 'Stylo à bille scheneider rouge 505', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/21.jpg', '', '', '', 0),
(22, 'Stylo à bille scheneider noir  505', '', 1, '17500', 'http://proforma.smspro.tg/rideo/img/products/22.jpg', '', '', '', 0),
(23, 'Stylo feutre bleu', '', 3, '17500', 'http://proforma.smspro.tg/rideo/img/products/23.jpg', '', '', '', 0),
(24, 'Stylo feutre noir', '', 3, '17500', 'http://proforma.smspro.tg/rideo/img/products/24.jpg', '', '', '', 0),
(25, 'Stylo feutre rouge', '', 3, '17500', 'http://proforma.smspro.tg/rideo/img/products/25.jpg', '', '', '', 0),
(26, 'toner hp 05A', 'article sollicite par tout les clients', 2, '25000', NULL, NULL, NULL, NULL, 0),
(27, 'toner hp 130 A black', 'machine en cours d\'utilistation', 2, '0', NULL, NULL, NULL, NULL, 0),
(28, 'toner hp 130 A cyan', 'utilisable par les clients', 2, '0', NULL, NULL, NULL, NULL, 0),
(29, 'toner hp 130 A yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(30, 'toner hp 130 Amagenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(31, 'toner hp 131A  black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(32, 'toner hp 131 A cyan', '0', 2, '00', NULL, NULL, NULL, NULL, 0),
(33, 'toner hp 131  A yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(34, 'toner hp 131A magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(35, 'toner hp 201A black', '0', 2, '00', NULL, NULL, NULL, NULL, 0),
(36, 'toner hp 201A cyan', '0', 2, '00', NULL, NULL, NULL, NULL, 0),
(37, 'toner hp 201  A yellow', '0', 2, '00', NULL, NULL, NULL, NULL, 0),
(38, 'toner hp 201  A magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(39, 'toner hp 55 A black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(40, 'toner hp 90A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(41, 'toner hp 36 A black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(42, 'toner hp  654 A black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(43, 'toner hp 654 A yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(44, 'toner hp 654 A cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(45, 'toner hp 654 A magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(46, 'PIMAX 40', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(47, 'PIMAX 41', '0', 2, '00', NULL, NULL, NULL, NULL, 0),
(48, 'HP 953 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(49, 'HP 953 magenta', '0', 2, '00', NULL, NULL, NULL, NULL, 0),
(50, 'HP 953 yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(51, 'HP 953 cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(52, 'toner xerox 106R01306', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(53, 'Ruban  YMCKO R3011 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(54, 'Ruban  YMCKO R5F008EAA color', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(55, 'toner hp 124A black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(56, 'toner hp 124 A yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(57, 'toner hp 124 A cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(58, 'toner hp 124 A magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(59, 'toner hp 125 A black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(60, 'toner hp 125A cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(61, 'toner hp 125 A yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(62, 'toner hp 125 A magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(63, 'canon 737 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(64, 'canon 728 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(65, 'Tambour 126 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(66, 'toner hp 312 A black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(67, 'toner hp 312 A cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(68, 'toner hp 312 A yellow', '0', 2, '00', NULL, NULL, NULL, NULL, 0),
(69, 'toner hp 312 A magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(70, 'toner hp 304 A black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(71, 'toner hp 304 A cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(72, 'toner hp 304 A yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(73, 'toner hp 304 A magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(74, 'Toner hp 205 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(75, 'Toner hp 205  cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(76, 'Toner hp 205 yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(77, 'Toner hp 205 magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(78, 'Toner hp 203 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(79, 'Toner hp 203  cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(80, 'Toner hp 203 yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(81, 'Toner hp 203 magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(82, 'toner hp 85A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(83, 'toner hp 83 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(84, 'toner hp 78 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(85, 'toner hp 35 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(86, 'toner hp 80 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(87, 'toner hp 12 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(88, 'toner hp 17 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(89, 'EXV  33', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(90, 'EX V 40', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(91, 'EXV42', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(92, 'EX V 14', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(93, 'toner hp 53A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(94, 'toner hp 26 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(95, 'toner hp 126 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(96, 'toner hp 126 cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(97, 'toner hp 126 magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(98, 'toner hp 126 yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(99, 'TONER HP 045 BLACK', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(100, 'TONER HP 045 CYAN', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(101, 'TONER HP 045 YELLOW', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(102, 'TONER HP 045 MAGENTA', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(103, 'toner hp 312 black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(104, 'toner hp 312 cyan', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(105, 'toner hp 312magenta', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(106, 'toner hp 312 yellow', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(107, 'TONER hp 128 A BLACK', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(108, 'TONER HP 128 YELLOW', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(109, 'TONER HP 128 CYAN', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(110, 'TONER HP 128 MAGENTA', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(111, 'TONER XEROX 5230', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(112, 'Ruban RC T011NAA', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(113, 'TONER TASS PLUS 78A Black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(114, 'TONER TASS PLUS 85A/35 A Black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(115, 'TONER TASS PLUS 05 A/80 A Black', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(116, 'TONER HP 30 A', '0', 2, '0', NULL, NULL, NULL, NULL, 0),
(117, 'fgzsgrfer', 'rtzere', 2, 'ergtrz', NULL, NULL, NULL, NULL, 222);

-- --------------------------------------------------------

--
-- Structure de la table `article_stock`
--

CREATE TABLE `article_stock` (
  `id` int(11) NOT NULL,
  `article` int(11) DEFAULT NULL,
  `CreatedAt` datetime NOT NULL,
  `qte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `qte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `catname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `catdesc` longtext COLLATE utf8_unicode_ci,
  `parent_id` int(11) DEFAULT NULL,
  `icone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `catname`, `catdesc`, `parent_id`, `icone`) VALUES
(1, 'Fournitures de bureau', 'fourniture de bureaux', 1, NULL),
(2, 'Consommables et accessoires', 'consommables et accessoires', 1, NULL),
(3, 'boissons', 'boissons', 9, NULL),
(4, 'entretien', 'entretien', 1, NULL),
(5, 'fruits et légumes', NULL, 1, NULL),
(6, 'hygiène et beauté', NULL, 1, NULL),
(7, 'Cosmétiques', 'Cosmétiques Cosmétiques', 7, NULL),
(8, 'Pommade', 'Pommade', 7, NULL),
(9, 'Alimentation générale', 'Alimentation générale', 9, NULL),
(10, 'Categ test Rayons', 'Categ test Rayons', 10, NULL),
(11, 'Sous rayon 1', 'Sous rayon 1', 10, NULL),
(12, 'Categorie AA1', 'Categorie AA1', 12, NULL),
(13, 'Categorie AA1 - BA', 'Categorie AA1 - BA', 12, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `raisoc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `raisoc`, `adresse`, `telephone`, `email`) VALUES
(1, 'EMF-FINAM.SA', '..', 'MICROFINACE DE 2iem CATEGORIE', 'Quartier ancien SOBRAGA Avenue Lubin martial Ntoutoume Obame', '+241 01444623/ 05542132', 'contact@finagabon.com'),
(2, '.NSIA  ASSURANCE GABON  VIE', '..', 'COMPAGNIE D\'ASSURANCE', 'BP 2221  Libreville - Gabon Quartier Glass  Immeuble NSIA', '+24106380145.', '.WWW.groupensia.com'),
(3, 'NSIA  ASSURANCE GABON', '.', 'COMPAGNIE D\'ASSURANCE', 'BP 2221 LIBREVILLE Quartier Glass Immeuble NSIA', '+24106380145', 'WWW.groupensia.com'),
(4, 'AXA ASSURANCE GABON', '.', 'COMPAGNIE D\'ASSURANCE', '1935, bd de l\'indépendance BP: 4047 Libreville -Gabon', '+24101798093', 'www.axa.ga'),
(5, 'PALME D\'OR EVEN\'S', '.', 'RESIDENCE HOTELIERE', 'BOULEVARD , de L\'INDEPENDANCE', '0024102785397', 'claire.kothor@residencepalmedor.com'),
(6, NULL, NULL, NULL, NULL, '98979897', NULL),
(7, NULL, NULL, NULL, NULL, '97929859', NULL),
(8, NULL, NULL, NULL, NULL, '90358957', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fos_user`
--

CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL,
  `suscriber` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `fos_user`
--

INSERT INTO `fos_user` (`id`, `suscriber`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(1, 1, 'test', 'test', 'test@test.com', 'test@test.com', 1, NULL, '$2y$13$MNVxmxdeVd1HJDglFd9IaudLZudSJPoLPsMmHoqYGy6j15yk95nkC', '2018-11-22 16:16:06', NULL, NULL, 'a:0:{}'),
(2, 2, 'admin', 'admin', 'admin@gmail.com', 'admin@gmail.com', 1, NULL, '$2y$13$2eNDeU2kw2sinwQQj93AXO6uesJKHE031Dvzvxz.fZkcSLlXg0q6a', '2019-12-23 09:10:46', NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(3, 3, '+24106380145', '+24106380145', 'WWW.groupensia.com', 'www.groupensia.com', 1, NULL, '$2y$13$UjKyTl/YZV7SBug84u9CJ.puvB5gFOfDH4UFOOW24wc3htX34RTCy', NULL, NULL, NULL, 'a:0:{}'),
(4, 4, '+24101798093', '+24101798093', 'www.axa.ga', 'www.axa.ga', 1, NULL, '$2y$13$CV0b3/oXvDkj/bjZovg8VuATvkJMg8Hst3aKYjnuv/mikspK/01Uy', NULL, NULL, NULL, 'a:0:{}'),
(5, NULL, '000', '000', '000', '000', 1, NULL, '$2y$13$jkxsXPxhyIaE9XraHZJf2.Tm.zu2UmfZl5xmrbGq3quzfvFP.RIaO', NULL, NULL, NULL, 'a:0:{}'),
(6, 5, '0024102785397', '0024102785397', 'claire.kothor@residencepalmedor.com', 'claire.kothor@residencepalmedor.com', 1, NULL, '$2y$13$4QoghWysG3YTaP6zM2yNW.r8CDlOBJmfB5G8K6h47kIUEbTjLjyeG', NULL, NULL, NULL, 'a:0:{}');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `raisoc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id`, `user`, `raisoc`, `adresse`, `telephone`, `email`) VALUES
(1, 5, 'DEBO ELECNTRONIC', 'CENTREV VILLE', '000', '000');

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

CREATE TABLE `inventaire` (
  `id` int(11) NOT NULL,
  `dateDeb` datetime NOT NULL,
  `dateFin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `inventaire`
--

INSERT INTO `inventaire` (`id`, `dateDeb`, `dateFin`) VALUES
(1, '2019-07-11 23:58:41', '2019-07-12 00:01:04'),
(2, '2019-07-15 00:08:00', '2019-07-15 00:25:56');

-- --------------------------------------------------------

--
-- Structure de la table `inventaire_article`
--

CREATE TABLE `inventaire_article` (
  `id` int(11) NOT NULL,
  `inventaire_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `qte` int(11) NOT NULL,
  `stockLogiciel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `inventaire_article`
--

INSERT INTO `inventaire_article` (`id`, `inventaire_id`, `article_id`, `qte`, `stockLogiciel`) VALUES
(1, 2, 87, 8, 0),
(2, 2, 111, 3, 0),
(3, 2, 64, 5, 0),
(4, 2, 63, 3, 0),
(5, 2, 39, 4, 0),
(6, 2, 41, 5, 0),
(7, 2, 91, 10, 0),
(8, 2, 116, 4, 0),
(9, 2, 99, 1, 0),
(10, 2, 100, 1, 0),
(11, 2, 101, 1, 0),
(12, 2, 102, 1, 0),
(13, 2, 28, 4, 0),
(14, 2, 29, 5, 0),
(15, 2, 30, 4, 0),
(16, 2, 66, 2, 0),
(17, 2, 67, 2, 0),
(18, 2, 69, 2, 0),
(19, 2, 68, 2, 0),
(20, 2, 37, 2, 0),
(21, 2, 38, 1, 0),
(22, 2, 107, 3, 0),
(23, 2, 108, 3, 0),
(24, 2, 109, 3, 0),
(25, 2, 110, 3, 0),
(26, 2, 96, 3, 0),
(27, 2, 97, 3, 0),
(28, 2, 98, 3, 0),
(29, 2, 74, 2, 0),
(30, 2, 76, 4, 0),
(31, 2, 75, 4, 0),
(32, 2, 77, 3, 0),
(33, 2, 65, 19, 0),
(34, 2, 48, 3, 0),
(35, 2, 49, 3, 0),
(36, 2, 50, 3, 0),
(37, 2, 51, 3, 0),
(38, 2, 46, 4, 0),
(39, 2, 47, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_proformat`
--

CREATE TABLE `ligne_proformat` (
  `id` int(11) NOT NULL,
  `article` int(11) DEFAULT NULL,
  `proformat` int(11) DEFAULT NULL,
  `prix` double NOT NULL,
  `qte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `ligne_proformat`
--

INSERT INTO `ligne_proformat` (`id`, `article`, `proformat`, `prix`, `qte`) VALUES
(1, NULL, 3, 6500, 1),
(2, NULL, 3, 7000, 1),
(3, 4, 8, 6500, 1),
(4, 2, 8, 7000, 1),
(5, 1, 8, 9800, 1),
(6, 4, 9, 6500, 1),
(7, 2, 9, 7000, 1),
(8, 1, 9, 9800, 1),
(9, 4, 20, 6500, 1),
(10, 2, 20, 7000, 1),
(11, 1, 20, 8900, 1),
(12, 4, 21, 6500, 1),
(13, 2, 21, 7000, 1),
(14, 1, 21, 8900, 2),
(15, 4, 24, 6500, 1),
(16, 2, 24, 7000, 1),
(17, 1, 24, 8900, 2),
(18, 3, 25, 8500, 1),
(19, 2, 25, 7000, 1),
(20, 3, 26, 8500, 1),
(21, 2, 26, 7000, 1),
(22, 2, 27, 7000, 2),
(23, 1, 27, 9800, 2),
(24, 3, 28, 8500, 1),
(25, 4, 28, 6500, 1),
(26, 2, 28, 7000, 2),
(27, 3, 33, 8500, 1),
(28, 2, 33, 7000, 1),
(29, 1, 33, 9800, 1),
(30, 3, 34, 8500, 1),
(31, 2, 34, 7000, 1),
(32, 1, 34, 9800, 1),
(33, 4, 35, 6500, 1),
(34, 3, 35, 8500, 1),
(35, 2, 35, 7000, 1),
(36, 1, 35, 9800, 1),
(37, 4, 36, 6500, 1),
(38, 3, 36, 8500, 1),
(39, 2, 36, 7000, 1),
(40, 1, 36, 9800, 1),
(41, 4, 37, 6500, 1),
(42, 3, 37, 8500, 1),
(43, 2, 37, 7000, 1),
(44, 1, 37, 9800, 1),
(45, 4, 38, 6500, 1),
(46, 3, 38, 8500, 1),
(47, 2, 38, 7000, 1),
(48, 1, 38, 9800, 1),
(49, 4, 39, 6500, 1),
(50, 3, 39, 8500, 1),
(51, 2, 39, 7000, 1),
(52, 1, 39, 9800, 1),
(53, 4, 40, 6500, 1),
(54, 3, 40, 8500, 1),
(55, 2, 40, 7000, 1),
(56, 1, 40, 9800, 1),
(57, 16, 41, 17500, 1),
(58, 5, 46, 17500, 1),
(59, 5, 48, 17500, 1),
(60, 4, 48, 17500, 1),
(61, 5, 49, 17500, 1),
(62, 4, 49, 17500, 1),
(63, 6, 49, 17500, 1),
(64, 1, 49, 17500, 1),
(65, 5, 50, 17500, 1),
(66, 4, 50, 17500, 1),
(67, 6, 50, 17500, 1),
(68, 1, 50, 17500, 1),
(69, 2, 52, 17500, 1),
(70, 4, 52, 17500, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_vente`
--

CREATE TABLE `ligne_vente` (
  `id` int(11) NOT NULL,
  `article` int(11) DEFAULT NULL,
  `vente_id` int(11) DEFAULT NULL,
  `montant` int(11) NOT NULL,
  `qte` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `del` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateAjout` datetime NOT NULL,
  `article` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `titre`, `source`, `dateAjout`, `article`) VALUES
(1, 'photo A', 'https://cdn.pixabay.com/photo/2013/04/06/11/50/image-editing-101040_960_720.jpg', '2018-12-22 00:00:00', 3),
(3, 'photo B', 'http://localhost/elmt-rideo/rideo/img/products/l1.jpg', '2018-12-22 00:00:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `motif_sortie_stock`
--

CREATE TABLE `motif_sortie_stock` (
  `id` int(11) NOT NULL,
  `motif` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mvt_stock`
--

CREATE TABLE `mvt_stock` (
  `id` int(11) NOT NULL,
  `arrivage` int(11) DEFAULT NULL,
  `sortie_stock` int(11) DEFAULT NULL,
  `ligne_vente` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `qte` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_arvg` int(11) NOT NULL,
  `mvtType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `mvt_stock`
--

INSERT INTO `mvt_stock` (`id`, `arrivage`, `sortie_stock`, `ligne_vente`, `user`, `createdAt`, `qte`, `stock`, `stock_arvg`, `mvtType`) VALUES
(1, 1, NULL, NULL, 2, '2019-09-29 16:40:11', 200, 200, 200, 1);

-- --------------------------------------------------------

--
-- Structure de la table `news_letter`
--

CREATE TABLE `news_letter` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id` int(11) NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `vente_id` int(11) DEFAULT NULL,
  `montant` int(11) NOT NULL,
  `resteAPayer` int(11) NOT NULL,
  `mode` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prix`
--

CREATE TABLE `prix` (
  `id` int(11) NOT NULL,
  `article` int(11) DEFAULT NULL,
  `client` int(11) DEFAULT NULL,
  `montant` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateDebut` datetime NOT NULL,
  `dateFin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `prix`
--

INSERT INTO `prix` (`id`, `article`, `client`, `montant`, `dateDebut`, `dateFin`) VALUES
(1, 1, 1, '8900', '2013-01-01 00:00:00', '2019-01-01 00:00:00'),
(4, 1, 2, '8500', '2013-01-01 00:00:00', '2020-01-01 00:00:00'),
(5, 3, 1, '8500', '2013-01-01 00:00:00', '2020-01-01 00:00:00'),
(6, 27, 3, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(7, 113, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(8, 114, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(9, 115, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(10, 95, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(11, 96, 3, '25000', '2014-01-01 00:00:00', '2019-12-31 00:00:00'),
(12, 97, 3, '25000', '2014-01-01 00:00:00', '2019-12-31 00:00:00'),
(13, 98, 3, '25000', '2014-01-01 00:00:00', '2019-12-31 00:00:00'),
(14, 28, 3, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(15, 29, 3, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(16, 30, 3, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(17, 65, 3, '120000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(18, 74, 3, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(19, 75, 3, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(20, 76, 3, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(21, 77, 3, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(22, 82, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(23, 83, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(24, 84, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(25, 85, 3, '27000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(26, 113, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(27, 114, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(28, 115, 2, '25000', '2019-01-01 00:00:00', '0201-12-31 00:00:00'),
(29, 95, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(30, 96, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(31, 97, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(32, 98, 2, '25000', '2019-01-01 00:00:00', '2009-12-31 00:00:00'),
(33, 27, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(34, 28, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(35, 29, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(36, 30, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(37, 65, 2, '120000', '2019-01-01 00:00:00', '0201-12-31 00:00:00'),
(38, 74, 2, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(39, 75, 2, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(40, 76, 2, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(41, 77, 2, '42000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(42, 82, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(43, 83, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(44, 84, 2, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(45, 85, 2, '27000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(46, 113, 4, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(47, 114, 4, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(48, 115, 4, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(49, 27, 4, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(50, 28, 4, '30000', '2019-01-01 00:00:00', '0201-12-31 00:00:00'),
(51, 29, 4, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(52, 30, 4, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(53, 31, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(54, 32, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(55, 33, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(56, 34, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(57, 35, 4, '40000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(58, 36, 4, '40000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(59, 37, 4, '40000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(60, 38, 4, '40000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(61, 39, 4, '45000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(62, 40, 4, '85000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(63, 41, 4, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(64, 59, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(65, 60, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(66, 61, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(67, 62, 4, '35000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(68, 63, 4, '55000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(69, 64, 4, '55000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(70, 65, 4, '120000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(71, 83, 4, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(72, 26, 4, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(73, 86, 4, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(74, 46, 1, '11400', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(75, 47, 1, '16150', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(76, 48, 1, '20000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(77, 49, 1, '19000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(78, 50, 1, '19000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(79, 51, 1, '19000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(80, 113, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(81, 114, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(82, 115, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(83, 107, 1, '28500', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(84, 108, 1, '28500', '2019-01-01 00:00:00', '2009-12-31 00:00:00'),
(85, 109, 1, '28500', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(86, 110, 1, '28500', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(87, 82, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(88, 26, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(89, 84, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(90, 85, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(91, 86, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(92, 87, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(93, 88, 1, '52250', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(94, 89, 1, '25650', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(95, 90, 1, '33250', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(96, 91, 1, '25650', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(97, 92, 1, '25650', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(98, 93, 1, '23750', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(99, 94, 1, '47500', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(100, 99, 1, '64600', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(101, 100, 1, '64600', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(102, 101, 1, '64600', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(103, 102, 1, '64600', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(104, 28, 4, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(105, 95, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(106, 96, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(107, 97, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(108, 98, 2, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(109, 65, 2, '120000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(110, 87, 3, '25000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(111, 27, 5, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(112, 28, 5, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(113, 29, 5, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00'),
(114, 30, 5, '30000', '2019-01-01 00:00:00', '2019-12-31 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `proformat`
--

CREATE TABLE `proformat` (
  `id` int(11) NOT NULL,
  `client` int(11) DEFAULT NULL,
  `dateProformat` datetime NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `proformat`
--

INSERT INTO `proformat` (`id`, `client`, `dateProformat`, `total`) VALUES
(36, 1, '2019-01-03 11:57:01', 31800),
(37, 1, '2019-01-03 11:57:22', 31800),
(38, 1, '2019-01-03 11:57:23', 31800),
(39, 1, '2019-01-03 12:03:50', 31800),
(40, 1, '2019-01-03 12:03:51', 31800),
(41, 1, '2019-02-14 16:05:42', 17500),
(42, 1, '2019-05-09 10:50:41', 0),
(43, 1, '2019-05-09 10:51:07', 0),
(44, 1, '2019-05-09 10:51:19', 0),
(45, 1, '2019-05-09 10:55:28', 0),
(46, 1, '2019-05-09 10:56:09', 17500),
(47, 1, '2019-05-09 10:56:46', 0),
(48, 1, '2019-05-09 10:57:34', 35000),
(49, 1, '2019-05-09 10:59:46', 70000),
(50, 1, '2019-05-09 11:00:22', 70000),
(51, 1, '2019-06-02 22:21:36', 0),
(52, 1, '2019-08-24 16:14:53', 35000);

-- --------------------------------------------------------

--
-- Structure de la table `sortie_stock`
--

CREATE TABLE `sortie_stock` (
  `id` int(11) NOT NULL,
  `motif_sortie_stock` int(11) DEFAULT NULL,
  `arrivage` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `qteSortie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `arrivage` int(11) DEFAULT NULL,
  `qte` int(11) NOT NULL,
  `punit` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `qteInit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id`, `arrivage`, `qte`, `punit`, `createdAt`, `qteInit`) VALUES
(1, 1, 200, 332, '2019-09-29 16:40:11', 200);

-- --------------------------------------------------------

--
-- Structure de la table `suscriber`
--

CREATE TABLE `suscriber` (
  `id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `client` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `suscriber`
--

INSERT INTO `suscriber` (`id`, `createdAt`, `client`) VALUES
(1, '2018-12-21 00:00:00', 2),
(2, '2018-12-21 00:00:00', 1),
(3, '2019-06-02 17:30:05', 3),
(4, '2019-06-30 21:25:54', 4),
(5, '2019-07-30 20:30:41', 5);

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE `vente` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `tva` int(11) NOT NULL,
  `remise` int(11) NOT NULL,
  `total_ht` int(11) NOT NULL,
  `reste_a_payer` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `arrivage`
--
ALTER TABLE `arrivage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C079315823A0E66` (`article`),
  ADD KEY `IDX_C07931588D93D649` (`user`);

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_23A0E668947610D` (`designation`),
  ADD KEY `IDX_23A0E6664C19C1` (`category`);

--
-- Index pour la table `article_stock`
--
ALTER TABLE `article_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3C81247123A0E66` (`article`);

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64C19C197AFB2EC` (`catname`),
  ADD KEY `IDX_64C19C1727ACA70` (`parent_id`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C7440455450FF010` (`telephone`),
  ADD UNIQUE KEY `UNIQ_C7440455E7927C74` (`email`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F804D3B98D93D649` (`user`);

--
-- Index pour la table `fos_user`
--
ALTER TABLE `fos_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`),
  ADD UNIQUE KEY `UNIQ_957A6479B0995892` (`suscriber`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_369ECA328D93D649` (`user`);

--
-- Index pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inventaire_article`
--
ALTER TABLE `inventaire_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E52174A0CE430A85` (`inventaire_id`),
  ADD KEY `IDX_E52174A07294869C` (`article_id`);

--
-- Index pour la table `ligne_proformat`
--
ALTER TABLE `ligne_proformat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_25BCA16E23A0E66` (`article`),
  ADD KEY `IDX_25BCA16EEA59AEE6` (`proformat`);

--
-- Index pour la table `ligne_vente`
--
ALTER TABLE `ligne_vente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8B26C07C23A0E66` (`article`),
  ADD KEY `IDX_8B26C07C7DC7170A` (`vente_id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6A2CA10C23A0E66` (`article`);

--
-- Index pour la table `motif_sortie_stock`
--
ALTER TABLE `motif_sortie_stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A0F12EF87D377BB` (`motif`);

--
-- Index pour la table `mvt_stock`
--
ALTER TABLE `mvt_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_49F1052DC0793158` (`arrivage`),
  ADD KEY `IDX_49F1052DD238F364` (`sortie_stock`),
  ADD KEY `IDX_49F1052D8B26C07C` (`ligne_vente`),
  ADD KEY `IDX_49F1052D8D93D649` (`user`);

--
-- Index pour la table `news_letter`
--
ALTER TABLE `news_letter`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B1DC7A1EB03A8386` (`created_by_id`),
  ADD KEY `IDX_B1DC7A1E7DC7170A` (`vente_id`);

--
-- Index pour la table `prix`
--
ALTER TABLE `prix`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F7EFEA5E23A0E66` (`article`),
  ADD KEY `IDX_F7EFEA5EC7440455` (`client`);

--
-- Index pour la table `proformat`
--
ALTER TABLE `proformat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_EA59AEE6855D0083` (`dateProformat`),
  ADD KEY `IDX_EA59AEE6C7440455` (`client`);

--
-- Index pour la table `sortie_stock`
--
ALTER TABLE `sortie_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D238F364A0F12EF` (`motif_sortie_stock`),
  ADD KEY `IDX_D238F364C0793158` (`arrivage`),
  ADD KEY `IDX_D238F3648D93D649` (`user`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4B365660C0793158` (`arrivage`);

--
-- Index pour la table `suscriber`
--
ALTER TABLE `suscriber`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B0995892C7440455` (`client`);

--
-- Index pour la table `vente`
--
ALTER TABLE `vente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_888A2A4CA76ED395` (`user_id`),
  ADD KEY `IDX_888A2A4C19EB6921` (`client_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `arrivage`
--
ALTER TABLE `arrivage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT pour la table `article_stock`
--
ALTER TABLE `article_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fos_user`
--
ALTER TABLE `fos_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `inventaire`
--
ALTER TABLE `inventaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `inventaire_article`
--
ALTER TABLE `inventaire_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `ligne_proformat`
--
ALTER TABLE `ligne_proformat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `ligne_vente`
--
ALTER TABLE `ligne_vente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `motif_sortie_stock`
--
ALTER TABLE `motif_sortie_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mvt_stock`
--
ALTER TABLE `mvt_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `news_letter`
--
ALTER TABLE `news_letter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `prix`
--
ALTER TABLE `prix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT pour la table `proformat`
--
ALTER TABLE `proformat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `sortie_stock`
--
ALTER TABLE `sortie_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `suscriber`
--
ALTER TABLE `suscriber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `arrivage`
--
ALTER TABLE `arrivage`
  ADD CONSTRAINT `FK_C079315823A0E66` FOREIGN KEY (`article`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_C07931588D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E6664C19C1` FOREIGN KEY (`category`) REFERENCES `category` (`id`);

--
-- Contraintes pour la table `article_stock`
--
ALTER TABLE `article_stock`
  ADD CONSTRAINT `FK_3C81247123A0E66` FOREIGN KEY (`article`) REFERENCES `article` (`id`);

--
-- Contraintes pour la table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`);

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `FK_F804D3B98D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `fos_user`
--
ALTER TABLE `fos_user`
  ADD CONSTRAINT `FK_957A6479B0995892` FOREIGN KEY (`suscriber`) REFERENCES `suscriber` (`id`);

--
-- Contraintes pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD CONSTRAINT `FK_369ECA328D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `inventaire_article`
--
ALTER TABLE `inventaire_article`
  ADD CONSTRAINT `FK_E52174A07294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_E52174A0CE430A85` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaire` (`id`);

--
-- Contraintes pour la table `ligne_proformat`
--
ALTER TABLE `ligne_proformat`
  ADD CONSTRAINT `FK_25BCA16E23A0E66` FOREIGN KEY (`article`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_25BCA16EEA59AEE6` FOREIGN KEY (`proformat`) REFERENCES `proformat` (`id`);

--
-- Contraintes pour la table `ligne_vente`
--
ALTER TABLE `ligne_vente`
  ADD CONSTRAINT `FK_8B26C07C23A0E66` FOREIGN KEY (`article`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_8B26C07C7DC7170A` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`id`);

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `FK_6A2CA10C23A0E66` FOREIGN KEY (`article`) REFERENCES `article` (`id`);

--
-- Contraintes pour la table `mvt_stock`
--
ALTER TABLE `mvt_stock`
  ADD CONSTRAINT `FK_49F1052D8B26C07C` FOREIGN KEY (`ligne_vente`) REFERENCES `ligne_vente` (`id`),
  ADD CONSTRAINT `FK_49F1052D8D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`),
  ADD CONSTRAINT `FK_49F1052DC0793158` FOREIGN KEY (`arrivage`) REFERENCES `arrivage` (`id`),
  ADD CONSTRAINT `FK_49F1052DD238F364` FOREIGN KEY (`sortie_stock`) REFERENCES `sortie_stock` (`id`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `FK_B1DC7A1E7DC7170A` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`id`),
  ADD CONSTRAINT `FK_B1DC7A1EB03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `prix`
--
ALTER TABLE `prix`
  ADD CONSTRAINT `FK_F7EFEA5E23A0E66` FOREIGN KEY (`article`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_F7EFEA5EC7440455` FOREIGN KEY (`client`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `proformat`
--
ALTER TABLE `proformat`
  ADD CONSTRAINT `FK_EA59AEE6C7440455` FOREIGN KEY (`client`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `sortie_stock`
--
ALTER TABLE `sortie_stock`
  ADD CONSTRAINT `FK_D238F3648D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`),
  ADD CONSTRAINT `FK_D238F364A0F12EF` FOREIGN KEY (`motif_sortie_stock`) REFERENCES `motif_sortie_stock` (`id`),
  ADD CONSTRAINT `FK_D238F364C0793158` FOREIGN KEY (`arrivage`) REFERENCES `arrivage` (`id`);

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `FK_4B365660C0793158` FOREIGN KEY (`arrivage`) REFERENCES `arrivage` (`id`);

--
-- Contraintes pour la table `suscriber`
--
ALTER TABLE `suscriber`
  ADD CONSTRAINT `FK_B0995892C7440455` FOREIGN KEY (`client`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `vente`
--
ALTER TABLE `vente`
  ADD CONSTRAINT `FK_888A2A4C19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_888A2A4CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
