-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 30 jan. 2023 à 15:33
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `symfony_saisietpm`
--

-- --------------------------------------------------------

--
-- Structure de la table `champs`
--

DROP TABLE IF EXISTS `champs`;
CREATE TABLE IF NOT EXISTS `champs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_type_id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B34671BE1BD125E3` (`id_type_id`),
  KEY `IDX_B34671BEFB88E14F` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `champs`
--

INSERT INTO `champs` (`id`, `id_type_id`, `nom`, `utilisateur_id`) VALUES
(25, 1, 'Champs de test', 2),
(43, 1, 'Nom de borne', 1),
(44, 4, 'Prix', 1),
(45, 5, 'Date d\'intervention', 1);

-- --------------------------------------------------------

--
-- Structure de la table `champs_formulaire`
--

DROP TABLE IF EXISTS `champs_formulaire`;
CREATE TABLE IF NOT EXISTS `champs_formulaire` (
  `champs_id` int NOT NULL,
  `formulaire_id` int NOT NULL,
  PRIMARY KEY (`champs_id`,`formulaire_id`),
  KEY `IDX_D911E13A1ABA8B` (`champs_id`),
  KEY `IDX_D911E13A5053569B` (`formulaire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230109153523', '2023-01-09 15:35:58', 149),
('DoctrineMigrations\\Version20230110153844', '2023-01-11 07:40:12', 620),
('DoctrineMigrations\\Version20230112082214', '2023-01-12 08:22:47', 327),
('DoctrineMigrations\\Version20230116081959', '2023-01-16 08:23:56', 245),
('DoctrineMigrations\\Version20230116152721', '2023-01-16 15:27:44', 218),
('DoctrineMigrations\\Version20230117072459', '2023-01-17 07:25:14', 317),
('DoctrineMigrations\\Version20230118132847', '2023-01-18 13:28:52', 155),
('DoctrineMigrations\\Version20230130082133', '2023-01-30 08:21:57', 325);

-- --------------------------------------------------------

--
-- Structure de la table `formulaire`
--

DROP TABLE IF EXISTS `formulaire`;
CREATE TABLE IF NOT EXISTS `formulaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `relation_id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5BDD01A83256915B` (`relation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `renvoie_saisie`
--

DROP TABLE IF EXISTS `renvoie_saisie`;
CREATE TABLE IF NOT EXISTS `renvoie_saisie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fomulaire_id_id` int DEFAULT NULL,
  `user_id_id` int DEFAULT NULL,
  `saisie` json NOT NULL,
  `piecejointe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_33B2156C7F069F64` (`fomulaire_id_id`),
  KEY `IDX_33B2156C9D86650F` (`user_id_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_champs`
--

DROP TABLE IF EXISTS `type_champs`;
CREATE TABLE IF NOT EXISTS `type_champs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `typage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_champs`
--

INSERT INTO `type_champs` (`id`, `nom`, `typage`) VALUES
(1, 'Texte Simple', 'TextType::class'),
(2, 'Texte Long', 'TextareaType::class'),
(3, 'Email', 'EmailType::class'),
(4, 'Chiffre', 'NumberType::class'),
(5, 'Date', 'DateType::class'),
(7, 'Heure/mm/ss', 'TimeType::class'),
(8, 'Pièce Jointe', 'FileType::class');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`, `api_key`) VALUES
(1, 'desculier', '[\"ROLE_ADMIN\"]', '$2y$13$z3mHQzszqc9QyfyD9yldBOSa62gtZK1Wp24AWLK/V8s9af46q4uou', 'maclef'),
(2, 'userdetest', '[]', '$2y$13$z3mHQzszqc9QyfyD9yldBOSa62gtZK1Wp24AWLK/V8s9af46q4uou', 'maclef2');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `champs`
--
ALTER TABLE `champs`
  ADD CONSTRAINT `FK_B34671BE1BD125E3` FOREIGN KEY (`id_type_id`) REFERENCES `type_champs` (`id`),
  ADD CONSTRAINT `FK_B34671BEFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `champs_formulaire`
--
ALTER TABLE `champs_formulaire`
  ADD CONSTRAINT `FK_D911E13A1ABA8B` FOREIGN KEY (`champs_id`) REFERENCES `champs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D911E13A5053569B` FOREIGN KEY (`formulaire_id`) REFERENCES `formulaire` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `formulaire`
--
ALTER TABLE `formulaire`
  ADD CONSTRAINT `FK_5BDD01A83256915B` FOREIGN KEY (`relation_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `renvoie_saisie`
--
ALTER TABLE `renvoie_saisie`
  ADD CONSTRAINT `FK_33B2156C7F069F64` FOREIGN KEY (`fomulaire_id_id`) REFERENCES `formulaire` (`id`),
  ADD CONSTRAINT `FK_33B2156C9D86650F` FOREIGN KEY (`user_id_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
