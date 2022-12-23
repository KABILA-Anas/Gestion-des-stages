-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 18 juin 2022 à 20:09
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd_fstage`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `ID_ADMIN` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USER` int(11) NOT NULL,
  PRIMARY KEY (`ID_ADMIN`),
  KEY `FK_USER_ADMIN` (`ID_USER`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`ID_ADMIN`, `ID_USER`) VALUES
(1, 15);

-- --------------------------------------------------------

--
-- Structure de la table `attente`
--

DROP TABLE IF EXISTS `attente`;
CREATE TABLE IF NOT EXISTS `attente` (
  `ID_ETU` int(11) NOT NULL,
  `ID_OFFRE` int(11) NOT NULL,
  `PRIORITE` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID_ETU`,`ID_OFFRE`),
  UNIQUE KEY `PR` (`PRIORITE`),
  KEY `FK_ATTENTE2` (`ID_OFFRE`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

DROP TABLE IF EXISTS `departement`;
CREATE TABLE IF NOT EXISTS `departement` (
  `ID_DEPART` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_DEPART` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_DEPART`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`ID_DEPART`, `NOM_DEPART`) VALUES
(3, 'Informatique'),
(4, 'Mathématique'),
(5, 'Biologie-Chimie');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

DROP TABLE IF EXISTS `enseignant`;
CREATE TABLE IF NOT EXISTS `enseignant` (
  `ID_ENS` int(11) NOT NULL AUTO_INCREMENT,
  `ID_DEPART` int(11) NOT NULL,
  `NOM_ENS` varchar(25) DEFAULT NULL,
  `PRENOM_ENS` varchar(25) DEFAULT NULL,
  `CIN_ENS` varchar(15) DEFAULT NULL,
  `EMAIL_ENS` varchar(30) DEFAULT NULL,
  `DATENAISS_ENS` date DEFAULT NULL,
  `NUMTEL_ENS` int(11) DEFAULT NULL,
  `ACTIVE_ENS` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID_ENS`),
  KEY `FK_FAIRE_PARTIE_DE` (`ID_DEPART`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`ID_ENS`, `ID_DEPART`, `NOM_ENS`, `PRENOM_ENS`, `CIN_ENS`, `EMAIL_ENS`, `DATENAISS_ENS`, `NUMTEL_ENS`, `ACTIVE_ENS`) VALUES
(17, 3, 'LETRACH', 'Khadija', 'K110215', 'KhadijaLetrach@gmail.com', NULL, NULL, 1),
(16, 3, 'LEGHRIS', 'Mohammed', 'G144648', 'LEGHRISMohammed@gmail.com', NULL, NULL, 1),
(12, 3, 'RAMDANI', 'Mohammed', 'VB123445', 'RamdaniMohammed@gmail.com', NULL, NULL, 1),
(11, 3, 'BEGGAR', 'Omar', 'A31111', 'BEGGAROmar@gmail.com', NULL, NULL, 1),
(15, 3, 'ELBOUZIRI', 'Adil', 'B115448787', 'ADILBOUZIRI@gmail.com', NULL, NULL, 1),
(14, 3, 'KISSI', 'Mohammed', 'Z11111111', 'KISSIMohammed@gmail.com', NULL, NULL, 1),
(13, 4, 'HARFAOUI', 'Mohammed', 'R144455', 'HarfaouiMohammed@gmail.com', NULL, NULL, 1),
(18, 3, 'KHALIL', 'Mohammed', 'h548778', 'KhalilMohammed@gmail.com', NULL, NULL, 1),
(19, 3, 'ADIB', 'Mohammed', 'J1415665', 'ADIBMohammed@gmail.com', NULL, NULL, 1),
(20, 5, 'ZAHI', 'Mohammed', 'K15487', 'ZAHIMohammed@gmail.com', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `enseigner`
--

DROP TABLE IF EXISTS `enseigner`;
CREATE TABLE IF NOT EXISTS `enseigner` (
  `ID_FORM` int(11) NOT NULL,
  `ID_ENS` int(11) NOT NULL,
  PRIMARY KEY (`ID_FORM`,`ID_ENS`),
  KEY `FK_FORM` (`ID_FORM`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `enseigner`
--

INSERT INTO `enseigner` (`ID_FORM`, `ID_ENS`) VALUES
(11, 16),
(11, 18),
(11, 19),
(12, 11),
(12, 12),
(12, 15),
(12, 16),
(12, 17),
(14, 12);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

DROP TABLE IF EXISTS `entreprise`;
CREATE TABLE IF NOT EXISTS `entreprise` (
  `ID_ENTREP` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_ENTREP` varchar(25) DEFAULT NULL,
  `EMAIL_ENTREP` varchar(50) DEFAULT NULL,
  `VILLE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID_ENTREP`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`ID_ENTREP`, `NOM_ENTREP`, `EMAIL_ENTREP`, `VILLE`) VALUES
(14, 'ALTEN', 'yassinejrayfy35@gmail.com', 'FES');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `ID_ETU` int(11) NOT NULL AUTO_INCREMENT,
  `ID_FORM` int(11) NOT NULL,
  `NOM_ETU` varchar(50) DEFAULT NULL,
  `PRENOM_ETU` varchar(50) DEFAULT NULL,
  `CIN_ETU` varchar(20) DEFAULT NULL,
  `CNE` varchar(20) DEFAULT NULL,
  `NIVEAU` int(11) DEFAULT NULL,
  `PROMOTION` int(11) DEFAULT NULL,
  `DATENAISS_ETU` date DEFAULT NULL,
  `VILLE_ETU` varchar(50) DEFAULT NULL,
  `ADRESSE_ETU` varchar(50) DEFAULT NULL,
  `EMAIL_ETU` varchar(50) DEFAULT NULL,
  `NUMTEL_ETU` varchar(30) DEFAULT NULL,
  `CV` varchar(100) DEFAULT NULL,
  `ID_USER` int(11) NOT NULL,
  PRIMARY KEY (`ID_ETU`),
  KEY `FK_APPARTENIR` (`ID_FORM`),
  KEY `FK_USER_ETU` (`ID_USER`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`ID_ETU`, `ID_FORM`, `NOM_ETU`, `PRENOM_ETU`, `CIN_ETU`, `CNE`, `NIVEAU`, `PROMOTION`, `DATENAISS_ETU`, `VILLE_ETU`, `ADRESSE_ETU`, `EMAIL_ETU`, `NUMTEL_ETU`, `CV`, `ID_USER`) VALUES
(25, 12, 'Anas', 'KABILA', 'KB121212', 'R130073150', 3, 2021, '2001-07-29', 'Mohammedia', 'Saada BD CHOURAFA 12', 'ANASKABILA@gmail.com', '612141618', NULL, 49),
(27, 12, 'BANA', 'HAMZA', 'BB151515', 'R151515151', 3, 2021, '2000-01-01', 'CASABLANCA', 'SM 12', 'YASSINE@gmail.com', '600223344', NULL, 52);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `ID_FORM` int(11) NOT NULL AUTO_INCREMENT,
  `ID_ENS` int(11) NOT NULL,
  `FULL_NAME` varchar(100) DEFAULT NULL,
  `FILIERE` varchar(30) DEFAULT NULL,
  `TYPE_FORM` int(11) DEFAULT NULL,
  `ID_USER` int(11) NOT NULL,
  PRIMARY KEY (`ID_FORM`),
  KEY `FK_GERER2` (`ID_ENS`),
  KEY `FK_USER_RESP` (`ID_USER`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`ID_FORM`, `ID_ENS`, `FULL_NAME`, `FILIERE`, `TYPE_FORM`, `ID_USER`) VALUES
(12, 11, 'Ingénierie Logicielle et Intégration des Systèmes Informatiques', 'ILISI', 1, 43),
(11, 19, 'Informatique, Réseau et Multimédia', 'IRM', 0, 44),
(13, 20, 'Management de la Qualité, de la Sécurité et de l’Environnement', 'MQSE', 2, 47),
(14, 13, 'Génie Mathématique et Informatique', 'GMI', 1, 48);

-- --------------------------------------------------------

--
-- Structure de la table `juri`
--

DROP TABLE IF EXISTS `juri`;
CREATE TABLE IF NOT EXISTS `juri` (
  `ID_ENS` int(11) NOT NULL,
  `ID_STAGE` int(11) NOT NULL,
  `NOTE` float DEFAULT NULL,
  PRIMARY KEY (`ID_ENS`,`ID_STAGE`),
  KEY `FK_JURI2` (`ID_STAGE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `motcle`
--

DROP TABLE IF EXISTS `motcle`;
CREATE TABLE IF NOT EXISTS `motcle` (
  `ID_MOTCLE` int(11) NOT NULL AUTO_INCREMENT,
  `MOT` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID_MOTCLE`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

DROP TABLE IF EXISTS `offre`;
CREATE TABLE IF NOT EXISTS `offre` (
  `ID_OFFRE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_FORM` int(11) NOT NULL,
  `ID_ENTREP` int(11) NOT NULL,
  `STATUOFFRE` varchar(25) DEFAULT NULL,
  `NBRCANDIDAT` int(11) DEFAULT NULL,
  `POSTE` varchar(50) DEFAULT NULL,
  `DUREE` int(11) DEFAULT NULL,
  `DATEDEBUT` date DEFAULT NULL,
  `DATEFIN` date DEFAULT NULL,
  `DESCRIP` varchar(2000) DEFAULT NULL,
  `NIVEAU_OFFRE` int(11) DEFAULT NULL,
  `SOURCE_OFFRE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_OFFRE`),
  KEY `FK_CONCERNER` (`ID_FORM`),
  KEY `FK_PRESENTER` (`ID_ENTREP`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `offre`
--

INSERT INTO `offre` (`ID_OFFRE`, `ID_FORM`, `ID_ENTREP`, `STATUOFFRE`, `NBRCANDIDAT`, `POSTE`, `DUREE`, `DATEDEBUT`, `DATEFIN`, `DESCRIP`, `NIVEAU_OFFRE`, `SOURCE_OFFRE`) VALUES
(19, 12, 14, 'Nouveau', 2, 'Développeur PHP Full Stack senior', 180, '2022-06-18', '2022-07-29', 'Poste:\r\n\r\nDans le cadre du développement important de notre activité, nous recherchons avant tout des personnalités, des passionnés!\r\n\r\nVous intégrez une équipe de développeurs, concepteurs, analystes et experts.\r\n\r\nVous aurez l\'opportunité de participer aux tâches suivantes :\r\n\r\nConception et mise en place de l’architecture des applications\r\nDéveloppement des fonctionnalités\r\nMaintenance et Evolution d’applications web basées sur PHP\r\nMontage et intégration de maquettes en utilisant les langages de développement appropriés\r\nOptimisation des performances\r\nLa rédaction des scenarios de tests et de la documentation technique\r\nProfil recherché:\r\n\r\nDiplômé Bac + 5, spécialisé en informatique avec minimum 3 ans d\'expérience sur les technologies PHP .\r\n\r\nCompétences requises :\r\n\r\nSolides compétences en PHP (5 et +)\r\nDéjà travaillé avec un Framework, tel que : Zend, Symfony…\r\nSolides compétences en HTML5 / CSS3, Responsive Design, JavaScript / JQuery\r\nConnaissance des méthodologies de gestion de projet, spécialement la méthode Agile\r\nBonne communication en Français, et Anglais Technique\r\nCompétences appréciées :\r\n\r\nBonne connaissance du framework Zend1\r\nConnaissance en d’autres langages de développement : Symfony 2 +, Angular 4+,Java …\r\nSécurité des applications Web (Injection SQL, connexions certifiées…)', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `postuler`
--

DROP TABLE IF EXISTS `postuler`;
CREATE TABLE IF NOT EXISTS `postuler` (
  `ID_ETU` int(11) NOT NULL,
  `ID_OFFRE` int(11) NOT NULL,
  `STATU` varchar(30) DEFAULT NULL,
  `DATEREPONS` timestamp NULL DEFAULT NULL,
  `DATEPOST` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID_ETU`,`ID_OFFRE`),
  KEY `FK_POSTULER2` (`ID_OFFRE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `rapport`
--

DROP TABLE IF EXISTS `rapport`;
CREATE TABLE IF NOT EXISTS `rapport` (
  `ID_RAPP` int(11) NOT NULL AUTO_INCREMENT,
  `FICHIER` varchar(100) DEFAULT NULL,
  `ID_STAGE` int(11) NOT NULL,
  PRIMARY KEY (`ID_RAPP`),
  KEY `FK_STAGE` (`ID_STAGE`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `rapport`
--

INSERT INTO `rapport` (`ID_RAPP`, `FICHIER`, `ID_STAGE`) VALUES
(17, '../uploads/rapport/CONDITIONS_D_ADMISSION_EN_CYCLE_INGENIEUR.pdf', 48),
(20, '../uploads/rapport/doc_7.pdf', 49);

-- --------------------------------------------------------

--
-- Structure de la table `referencer`
--

DROP TABLE IF EXISTS `referencer`;
CREATE TABLE IF NOT EXISTS `referencer` (
  `ID_RAPP` int(11) NOT NULL,
  `ID_MOTCLE` int(11) NOT NULL,
  PRIMARY KEY (`ID_RAPP`,`ID_MOTCLE`),
  KEY `FK_REFERENCER2` (`ID_MOTCLE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

DROP TABLE IF EXISTS `stage`;
CREATE TABLE IF NOT EXISTS `stage` (
  `ID_STAGE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_OFFRE` int(11) NOT NULL,
  `ID_ENS` int(11) DEFAULT NULL,
  `ID_ETU` int(11) NOT NULL,
  `STATUSTG` int(11) NOT NULL DEFAULT '1',
  `DATEDEBUT_STAGE` timestamp NULL DEFAULT NULL,
  `NOTENCAD_ENTREP` float DEFAULT NULL,
  `NOTENCAD` float DEFAULT NULL,
  `CONTRAT` varchar(100) DEFAULT NULL,
  `NIVEAU_STAGE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_STAGE`),
  KEY `FK_ENCADRER` (`ID_ENS`),
  KEY `FK_PEUT_DEVENIR` (`ID_OFFRE`),
  KEY `FK_STAGIER` (`ID_ETU`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID_USER` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(30) NOT NULL,
  `PASSWORD` varchar(30) NOT NULL,
  `PICTURE` varchar(250) DEFAULT NULL,
  `ACTIVE` int(11) NOT NULL,
  `VERIFIED` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_USER`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`ID_USER`, `LOGIN`, `PASSWORD`, `PICTURE`, `ACTIVE`, `VERIFIED`) VALUES
(52, 'YASSINE@gmail.com', 'YASSINE123', NULL, 1, 0),
(44, 'ADIBMohammed@gmail.com', 'ADIBJ1415665', NULL, 1, 1),
(43, 'BEGGAROmar@gmail.com', 'BEGGARA31111', NULL, 1, 1),
(15, 'admin1', 'admin1123', NULL, 1, 1),
(49, 'ANASKABILA@gmail.com', 'ANAS2001', NULL, 1, 0),
(47, 'ZAHIMohammed@gmail.com', 'ZAHIK15487', NULL, 1, 1),
(48, 'HarfaouiMohammed@gmail.com', 'HARFAOUIR144455', NULL, 1, 1);

DELIMITER $$
--
-- Évènements
--
DROP EVENT `Closing_Offre`$$
CREATE DEFINER=`root`@`localhost` EVENT `Closing_Offre` ON SCHEDULE EVERY 1 DAY STARTS '2022-05-28 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE offre  SET STATUOFFRE="Closed" WHERE DATEFIN=CURRENT_DATE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
