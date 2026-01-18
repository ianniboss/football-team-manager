-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

-- IMPORTANT: Disable foreign key checks FIRST to allow dropping tables
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table commentaire
DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int NOT NULL AUTO_INCREMENT,
  `id_joueur` int NOT NULL,
  `commentaire` text NOT NULL,
  `date_commentaire` date NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `fk_commentaire_joueur` (`id_joueur`),
  CONSTRAINT `fk_commentaire_joueur` FOREIGN KEY (`id_joueur`) REFERENCES `joueur` (`id_joueur`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table commentaire: French comments for each player
-- Comment dates aligned with match schedule (2025-10 to 2026-01)
INSERT INTO `commentaire` (`id_commentaire`, `id_joueur`, `commentaire`, `date_commentaire`) VALUES
	-- Mbappé (id_joueur: 1)
	(1, 1, 'Vitesse exceptionnelle sur les contre-attaques. Continue à progresser dans le jeu de tête.', '2025-10-16'),
	(2, 1, 'A marqué un doublé contre Lyon. Leadership remarquable sur le terrain.', '2025-12-06'),
	
	-- Griezmann (id_joueur: 2)
	(3, 2, 'Excellent dans les combinaisons offensives. Toujours disponible pour ses coéquipiers.', '2025-10-16'),
	(4, 2, 'Doit améliorer son efficacité devant le but. Trop de frappes non cadrées.', '2025-12-05'),
	
	-- Ramos (id_joueur: 3)
	(5, 3, 'Solide en défense centrale. Son expérience est précieuse pour les jeunes joueurs.', '2025-10-15'),
	(6, 3, 'Attention aux cartons jaunes. Déjà 3 avertissements cette saison.', '2025-12-21'),
	
	-- Messi (id_joueur: 4)
	(7, 4, 'Magie sur le ballon. Ses passes décisives changent le cours des matchs.', '2025-11-09'),
	(8, 4, 'A besoin de plus de repos entre les matchs. Fatigue visible en fin de match.', '2025-12-05'),
	
	-- Modric (id_joueur: 5)
	(9, 5, 'Métronome du milieu de terrain. Vision de jeu exceptionnelle.', '2025-10-15'),
	(10, 5, 'Continue de performer à un très haut niveau malgré son âge. Exemplaire.', '2025-12-21'),
	
	-- Donnarumma (id_joueur: 6)
	(11, 6, 'Excellente performance contre Paris SG. Plusieurs arrêts décisifs.', '2025-11-09'),
	(12, 6, 'Doit mieux communiquer avec sa défense sur les corners.', '2026-01-11'),
	
	-- Haaland (id_joueur: 7)
	(13, 7, 'Machine à marquer. Instinct de buteur naturel.', '2025-10-16'),
	(14, 7, 'Doit participer davantage au jeu collectif en dehors de la surface.', '2025-12-06'),
	(15, 7, 'Forme physique parfaite. Prêt pour le match contre Barcelone.', '2026-01-18'),
	
	-- Ronaldo (id_joueur: 8)
	(16, 8, 'Professionnalisme exemplaire à l''entraînement. Toujours le premier arrivé.', '2025-11-10'),
	(17, 8, 'Efficace en tant que remplaçant. Apporte de l''énergie en fin de match.', '2025-12-05'),
	
	-- Zidane (id_joueur: 9)
	(18, 9, 'Technique irréprochable. Un modèle pour les jeunes du centre de formation.', '2025-10-15'),
	(19, 9, 'Calme sous pression. Gère bien les situations de stress.', '2025-12-21'),
	
	-- Van Dijk (id_joueur: 10)
	(20, 10, 'Tour de contrôle en défense. Aucun attaquant ne passe facilement.', '2025-10-15'),
	(21, 10, 'Excellent dans le jeu aérien. 100% des duels gagnés contre Lyon.', '2025-12-06'),
	
	-- Maignan (id_joueur: 11)
	(22, 11, 'Gardien de grande classe. Relance au pied remarquable.', '2025-12-05'),
	(23, 11, 'Blessure mineure à surveiller. Prévoir du repos si nécessaire.', '2026-01-12'),
	
	-- Salah (id_joueur: 12)
	(24, 12, 'Très bon sur son côté droit. Centres précis et dangereux.', '2025-10-16'),
	(25, 12, 'Peut jouer à gauche également. Polyvalence appréciée.', '2025-12-05'),
	(26, 12, 'Doit améliorer son pied faible pour être plus imprévisible.', '2026-01-11');

-- Dumping structure for table joueur
DROP TABLE IF EXISTS `joueur`;
CREATE TABLE IF NOT EXISTS `joueur` (
  `id_joueur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `num_licence` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `taille` int DEFAULT NULL,
  `poids` decimal(5,2) DEFAULT NULL,
  `statut` enum('Actif','Blessé','Suspendu','Absent') NOT NULL DEFAULT 'Actif',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_joueur`),
  UNIQUE KEY `num_licence` (`num_licence`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table joueur: ~12 rows
INSERT INTO `joueur` (`id_joueur`, `nom`, `prenom`, `num_licence`, `date_naissance`, `taille`, `poids`, `statut`, `image`) VALUES
	(1, 'Mbappé', 'Kylian', 'FR001', '1998-12-20', 178, 73.50, 'Actif', 'kylian_mbappe_1.jpg'),
	(2, 'Griezmann', 'Antoine', 'FR002', '1991-03-21', 176, 70.00, 'Actif', 'antoine_griezmann_2.jpg'),
	(3, 'Ramos', 'Sergio', 'ES001', '1986-03-30', 184, 82.00, 'Actif', 'sergio_ramos_3.jpg'),
	(4, 'Messi', 'Lionel', 'AR001', '1987-06-24', 170, 72.00, 'Actif', 'lionel_messi_4.jpg'),
	(5, 'Modric', 'Luka', 'HR001', '1985-09-09', 172, 66.00, 'Actif', 'luka_modric_5.jpg'),
	(6, 'Donnarumma', 'Gianluigi', 'IT001', '1999-02-25', 196, 90.00, 'Actif', 'gianluigi_donnarumma_6.jpg'),
	(7, 'Haaland', 'Erling', 'NO001', '2000-07-21', 194, 88.00, 'Actif', 'erling_haaland_7.jpg'),
	(8, 'Ronaldo', 'Cristiano', 'PT001', '1985-02-05', 187, 83.00, 'Actif', 'cristiano_ronaldo_8.jpg'),
	(9, 'Zidane', 'Zinedine', 'AB2024', '1972-06-23', 185, 80.00, 'Actif', 'zinedine_zidane_9.jpg'),
	(10, 'Van Dijk', 'Virgil', 'CT045', '1991-07-08', 193, 92.00, 'Actif', 'virgil_van_dijk_10.jpg'),
	(11, 'Maignan', 'Mike', 'UI0909', '1995-07-03', 191, 89.00, 'Actif', 'mike_maignan_11.webp'),
	(12, 'Salah', 'Mohamed', 'QW1234', '1992-06-15', 175, 71.00, 'Actif', 'mohamed_salah_12.jpg');

-- Dumping structure for table participer
DROP TABLE IF EXISTS `participer`;
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table participer: Demo data with varied positions
-- Player positions reference:
-- 1-Mbappé: Attaquant/Ailier Droit
-- 2-Griezmann: Milieu Offensif/Attaquant
-- 3-Ramos: Défenseur Central
-- 4-Messi: Ailier Droit/Milieu Offensif
-- 5-Modric: Milieu Central/Milieu Défensif
-- 6-Donnarumma: Gardien
-- 7-Haaland: Attaquant
-- 8-Ronaldo: Attaquant/Ailier Gauche
-- 9-Zidane: Milieu Central/Milieu Offensif
-- 10-Van Dijk: Défenseur Central
-- 11-Maignan: Gardien
-- 12-Salah: Ailier Droit/Attaquant

INSERT INTO `participer` (`id_participation`, `id_rencontre`, `id_joueur`, `poste`, `titulaire`, `evaluation`) VALUES
	-- Match 1: vs FC Paris (Victoire) - 2023-12-10
	(1, 1, 6, 'Gardien', 1, 4),
	(2, 1, 3, 'Défenseur Central', 1, 5),
	(3, 1, 10, 'Défenseur Central', 1, 4),
	(4, 1, 5, 'Milieu Défensif', 1, 4),
	(5, 1, 9, 'Milieu Central', 1, 5),
	(6, 1, 2, 'Milieu Offensif', 1, 4),
	(7, 1, 4, 'Ailier Droit', 1, 5),
	(8, 1, 12, 'Ailier Gauche', 1, 4),
	(9, 1, 1, 'Attaquant', 1, 5),
	(10, 1, 7, 'Attaquant', 1, 4),
	(11, 1, 8, 'Attaquant', 0, 3),
	(12, 1, 11, 'Gardien', 0, NULL),
	
	-- Match 2: vs Paris SG (Defaite) - 2023-12-18
	(13, 2, 6, 'Gardien', 1, 3),
	(14, 2, 3, 'Défenseur Central', 1, 2),
	(15, 2, 10, 'Défenseur Central', 1, 3),
	(16, 2, 5, 'Milieu Central', 1, 3),
	(17, 2, 9, 'Milieu Offensif', 1, 2),
	(18, 2, 2, 'Attaquant', 1, 3),
	(19, 2, 4, 'Milieu Offensif', 1, 3),
	(20, 2, 12, 'Ailier Droit', 1, 2),
	(21, 2, 1, 'Ailier Droit', 1, 3),
	(22, 2, 7, 'Attaquant', 1, 2),
	(23, 2, 8, 'Ailier Gauche', 0, 2),
	(24, 2, 11, 'Gardien', 0, NULL),
	
	-- Match 3: vs Olympique Lyon (Victoire) - 2024-01-05
	(25, 3, 11, 'Gardien', 1, 5),
	(26, 3, 3, 'Défenseur Central', 1, 5),
	(27, 3, 10, 'Défenseur Central', 1, 5),
	(28, 3, 5, 'Milieu Central', 1, 5),
	(29, 3, 9, 'Milieu Central', 1, 5),
	(30, 3, 2, 'Milieu Offensif', 1, 5),
	(31, 3, 4, 'Ailier Droit', 1, 5),
	(32, 3, 12, 'Ailier Gauche', 1, 4),
	(33, 3, 1, 'Attaquant', 1, 5),
	(34, 3, 7, 'Attaquant', 1, 5),
	(35, 3, 8, 'Attaquant', 0, 4),
	(36, 3, 6, 'Gardien', 0, NULL),
	
	-- Match 4: vs Olympique Marseille (À venir) - 2025-12-19
	(37, 4, 6, 'Gardien', 1, NULL),
	(38, 4, 3, 'Défenseur Central', 1, NULL),
	(39, 4, 10, 'Défenseur Central', 1, NULL),
	(40, 4, 5, 'Milieu Défensif', 1, NULL),
	(41, 4, 9, 'Milieu Central', 1, NULL),
	(42, 4, 2, 'Milieu Offensif', 1, NULL),
	(43, 4, 4, 'Ailier Droit', 1, NULL),
	(44, 4, 12, 'Ailier Droit', 0, NULL),
	(45, 4, 1, 'Attaquant', 1, NULL),
	(46, 4, 7, 'Attaquant', 1, NULL),
	(47, 4, 8, 'Ailier Gauche', 1, NULL),
	(48, 4, 11, 'Gardien', 0, NULL),
	
	-- Match 5: vs AS Monaco (Defaite) - 2025-12-26
	(49, 5, 11, 'Gardien', 1, 3),
	(50, 5, 3, 'Défenseur Central', 1, 2),
	(51, 5, 10, 'Défenseur Central', 1, 2),
	(52, 5, 5, 'Milieu Central', 1, 2),
	(53, 5, 9, 'Milieu Offensif', 1, 3),
	(54, 5, 2, 'Attaquant', 1, 2),
	(55, 5, 4, 'Milieu Offensif', 1, 3),
	(56, 5, 12, 'Ailier Gauche', 1, 2),
	(57, 5, 1, 'Ailier Droit', 1, 3),
	(58, 5, 7, 'Attaquant', 1, 2),
	(59, 5, 8, 'Attaquant', 0, 2),
	(60, 5, 6, 'Gardien', 0, NULL),
	
	-- Match 6: vs Barcelona FC (À venir) - 2026-01-01
	(61, 6, 6, 'Gardien', 1, NULL),
	(62, 6, 3, 'Défenseur Central', 1, NULL),
	(63, 6, 10, 'Défenseur Central', 1, NULL),
	(64, 6, 5, 'Milieu Offensif', 1, NULL),
	(65, 6, 9, 'Milieu Central', 1, NULL),
	(66, 6, 2, 'Attaquant', 1, NULL),
	(67, 6, 4, 'Ailier Droit', 1, NULL),
	(68, 6, 12, 'Ailier Droit', 0, NULL),
	(69, 6, 1, 'Attaquant', 1, NULL),
	(70, 6, 7, 'Attaquant', 1, NULL),
	(71, 6, 8, 'Ailier Gauche', 1, NULL),
	(72, 6, 11, 'Gardien', 0, NULL);

-- Dumping structure for table rencontre
DROP TABLE IF EXISTS `rencontre`;
CREATE TABLE IF NOT EXISTS `rencontre` (
  `id_rencontre` int NOT NULL AUTO_INCREMENT,
  `date_rencontre` date NOT NULL,
  `heure` time NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `nom_equipe_adverse` varchar(50) NOT NULL,
  `lieu` enum('Domicile','Exterieur') NOT NULL,
  `resultat` enum('Victoire','Defaite','Nul') DEFAULT NULL,
  `image_stade` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rencontre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rencontre: ~7 rows
-- Current date reference: 2026-01-18
-- Past matches have results, upcoming matches have NULL
INSERT INTO `rencontre` (`id_rencontre`, `date_rencontre`, `heure`, `adresse`, `nom_equipe_adverse`, `lieu`, `resultat`, `image_stade`) VALUES
	(1, '2025-10-15', '15:00:00', 'Stade Municipal, Toulouse', 'FC Paris', 'Domicile', 'Victoire', 'stade_municipal.avif'),
	(2, '2025-11-08', '20:45:00', 'Parc des Princes, Paris', 'Paris SG', 'Exterieur', 'Defaite', 'parc_des_princes.jpeg'),
	(3, '2025-12-05', '14:00:00', 'Stade Municipal, Toulouse', 'Olympique Lyon', 'Domicile', 'Victoire', 'stade_municipal.avif'),
	(4, '2025-12-20', '16:00:00', 'Stade Vélodrome, Marseille', 'Olympique Marseille', 'Exterieur', 'Nul', 'stade_velodrome.png'),
	(5, '2026-01-10', '21:00:00', 'Stade Municipal, Toulouse', 'AS Monaco', 'Domicile', 'Defaite', 'stade_municipal.avif'),
	(6, '2026-01-25', '20:00:00', 'Camp Nou', 'Barcelona FC', 'Exterieur', NULL, 'camp_nou.png'),
	(7, '2026-02-15', '20:00:00', 'Santiago Bernabéu, Madrid', 'Real Madrid', 'Exterieur', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

-- RE-ENABLE FOREIGN KEY CHECKS
SET FOREIGN_KEY_CHECKS = 1;
