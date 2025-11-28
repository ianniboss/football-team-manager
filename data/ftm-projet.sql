-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.4.3 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour ftm-projet
CREATE DATABASE IF NOT EXISTS `ftm-projet` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ftm-projet`;

-- Listage de la structure de table ftm-projet. commentaire
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int NOT NULL AUTO_INCREMENT,
  `id_joueur` int NOT NULL,
  `commentaire` text NOT NULL,
  `date_commentaire` date NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `fk_commentaire_joueur` (`id_joueur`),
  CONSTRAINT `fk_commentaire_joueur` FOREIGN KEY (`id_joueur`) REFERENCES `joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ftm-projet.commentaire : ~0 rows (environ)

-- Listage de la structure de table ftm-projet. joueur
CREATE TABLE IF NOT EXISTS `joueur` (
  `id_joueur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `num_licence` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `taille` int DEFAULT NULL,
  `poids` decimal(5,2) DEFAULT NULL,
  `statut` enum('Actif','Blessé','Suspendu','Absent') NOT NULL DEFAULT 'Actif',
  PRIMARY KEY (`id_joueur`),
  UNIQUE KEY `num_licence` (`num_licence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ftm-projet.joueur : ~0 rows (environ)

-- Listage de la structure de table ftm-projet. participer
CREATE TABLE IF NOT EXISTS `participer` (
  `id_participation` int NOT NULL AUTO_INCREMENT,
  `id_rencontre` int NOT NULL,
  `id_joueur` int NOT NULL,
  `poste` varchar(50) NOT NULL,
  `titulaire` tinyint(1) NOT NULL DEFAULT '0',
  `evaluation` tinyint DEFAULT NULL,
  PRIMARY KEY (`id_participation`),
  UNIQUE KEY `uq_participer` (`id_rencontre`,`id_joueur`),
  KEY `fk_participer_joueur` (`id_joueur`),
  CONSTRAINT `fk_participer_joueur` FOREIGN KEY (`id_joueur`) REFERENCES `joueur` (`id_joueur`),
  CONSTRAINT `fk_participer_rencontre` FOREIGN KEY (`id_rencontre`) REFERENCES `rencontre` (`id_rencontre`),
  CONSTRAINT `chk_evaluation` CHECK (((`evaluation` between 1 and 5) or (`evaluation` is null)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ftm-projet.participer : ~0 rows (environ)

-- Listage de la structure de table ftm-projet. rencontre
CREATE TABLE IF NOT EXISTS `rencontre` (
  `id_rencontre` int NOT NULL AUTO_INCREMENT,
  `date_rencontre` date NOT NULL,
  `heure` time NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `nom_equipe_adverse` varchar(50) NOT NULL,
  `lieu` enum('Domicile','Exterieur') NOT NULL,
  `resultat` enum('Victoire','Defaite','Nul') DEFAULT NULL,
  PRIMARY KEY (`id_rencontre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table ftm-projet.rencontre : ~0 rows (environ)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
