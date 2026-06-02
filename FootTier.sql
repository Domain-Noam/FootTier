-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 02 juin 2026 à 18:44
-- Version du serveur : 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `FootTier`
--

-- --------------------------------------------------------

--
-- Structure de la table `action_foot`
--

CREATE TABLE `action_foot` (
  `id_action` int(11) NOT NULL,
  `joueur` varchar(100) NOT NULL,
  `competition` varchar(100) NOT NULL,
  `url_media` varchar(255) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `url_image` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `action_foot`
--

INSERT INTO `action_foot` (`id_action`, `joueur`, `competition`, `url_media`, `id_categorie`, `url_image`) VALUES
(10, 'Zinedine Zidane', 'France 1998', '', 1, ''),
(16, 'Roberto Carlos', 'France 1998', '', 6, ''),
(21, 'Kylian Mbappé', 'Qatar 2022', 'ressources/medias/mbappe2022.mp4', 2, 'ressources/img/mbappe2022.jpg'),
(22, 'Benjamin Pavard', 'Russie 2018', 'ressources/medias/pavard2018.mp4', 2, 'ressources/img/pavard2018.jpg'),
(23, 'Maradona', 'Espagne 1982', 'ressources/medias/maradona1982.mp4', 7, 'ressources/img/maradona1982.jpg'),
(24, 'Thibault Courtois', 'Russie 2018', 'ressources/medias/courtois2018.mp4', 4, 'ressources/img/courtois2018.jpg'),
(25, 'Emiliano Martinez', 'Qatar 2022', 'ressources/medias/martinez2022.mp4', 4, 'ressources/img/martinez2022.jpg'),
(26, 'Kylian Mbappé', 'Russie 2018', 'ressources/medias/mbappe2018.mp4', 8, 'ressources/img/mbappe2018.jpg'),
(27, 'Ronaldinho', 'Japon-Corée 2002', 'ressources/medias/ronaldinho2002.mp4', 6, 'ressources/img/ronaldinho2002.jpg'),
(28, 'Ronaldo R9', 'France 1998', 'ressources/medias/ronaldo1998.mp4', 1, 'ressources/img/ronaldo1998.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`) VALUES
(1, 'But de la tête'),
(2, 'Reprise de volée'),
(3, 'Dribble'),
(4, 'Arrêt'),
(5, 'But longue distance'),
(6, 'Coup franc'),
(7, 'But en solo'),
(8, 'Contre décisif'),
(9, 'Passe décisive'),
(10, 'Penalty');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(11) NOT NULL,
  `contenu` varchar(500) NOT NULL,
  `date_publication` datetime DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL,
  `id_tierlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `contenu`, `date_publication`, `id_user`, `id_tierlist`) VALUES
(9, 'Que pensez-vous de ma tierlist ?', '2026-06-02 19:54:46', 9, 98),
(10, 'J\\&#039;adore !', '2026-06-02 19:57:35', 10, 98),
(11, 'Un avis ?', '2026-06-02 20:00:07', 10, 101),
(12, 'J\\&#039;aime bien, mais l\\&#039;arrêt de Martinez ne mérite pas D', '2026-06-02 20:32:30', 8, 98),
(13, 'Très correct', '2026-06-02 20:34:01', 8, 101);

-- --------------------------------------------------------

--
-- Structure de la table `contenu_tierlist`
--

CREATE TABLE `contenu_tierlist` (
  `id_tierlist` int(11) NOT NULL,
  `id_action` int(11) NOT NULL,
  `tier` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contenu_tierlist`
--

INSERT INTO `contenu_tierlist` (`id_tierlist`, `id_action`, `tier`) VALUES
(98, 21, 'S'),
(98, 22, 'S'),
(98, 25, 'D'),
(98, 26, 'B'),
(99, 24, 'S'),
(99, 25, 'D'),
(100, 21, 'S'),
(100, 26, 'A'),
(101, 21, 'S'),
(101, 22, 'A'),
(101, 27, 'B'),
(101, 28, 'C');

-- --------------------------------------------------------

--
-- Structure de la table `like_tierlist`
--

CREATE TABLE `like_tierlist` (
  `id_user` int(11) NOT NULL,
  `id_tierlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `like_tierlist`
--

INSERT INTO `like_tierlist` (`id_user`, `id_tierlist`) VALUES
(8, 98),
(8, 101),
(9, 98),
(10, 98),
(10, 101);

-- --------------------------------------------------------

--
-- Structure de la table `tierlist`
--

CREATE TABLE `tierlist` (
  `id_tierlist` int(11) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `est_publique` tinyint(1) DEFAULT 0,
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_modification` datetime DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tierlist`
--

INSERT INTO `tierlist` (`id_tierlist`, `titre`, `est_publique`, `date_creation`, `date_modification`, `id_user`, `id_categorie`) VALUES
(96, 'Sans titre', 0, '2026-06-02 19:45:19', '2026-06-02 19:45:19', 9, NULL),
(97, 'Sans titre', 0, '2026-06-02 19:52:45', '2026-06-02 19:52:45', 9, NULL),
(98, 'Allez les bleus', 1, '2026-06-02 19:53:16', '2026-06-02 19:54:28', 9, NULL),
(99, 'Les gardiens', 0, '2026-06-02 19:55:10', '2026-06-02 19:56:21', 9, NULL),
(100, 'Kyky', 0, '2026-06-02 19:57:49', '2026-06-02 19:58:20', 10, NULL),
(101, 'Les meilleures actions !', 1, '2026-06-02 19:58:28', '2026-06-02 19:59:57', 10, NULL),
(102, 'Sans titre', 0, '2026-06-02 20:00:12', '2026-06-02 20:00:12', 10, NULL),
(103, 'Sans titre', 0, '2026-06-02 20:30:07', '2026-06-02 20:30:07', 10, NULL),
(104, 'Sans titre', 0, '2026-06-02 20:41:12', '2026-06-02 20:41:12', 8, NULL),
(105, 'Sans titre', 0, '2026-06-02 20:41:31', '2026-06-02 20:41:31', 8, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `est_admin` tinyint(1) DEFAULT 0,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `pseudo`, `mot_de_passe`, `est_admin`, `date_inscription`) VALUES
(8, 'admin', '$2y$10$PpD.cMBmrK77IiMK6QsK8.Uve6a7sPF5mtrMFOLA5ZGbhp1Zmnjay', 1, '2026-06-02 19:33:18'),
(9, 'Adri1', '$2y$10$bZBDOzg.hKv6jPwm5b9sYOPo4uQTb8qQ5mscrQUGyxm.GYAOALSP.', 0, '2026-06-02 19:33:56'),
(10, 'Noam', '$2y$10$csroo1FVd4JcmQqKEf/Q2e2cH2peD86EM.5bqyIQI/ocEvqSNNUCe', 0, '2026-06-02 19:34:12');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `action_foot`
--
ALTER TABLE `action_foot`
  ADD PRIMARY KEY (`id_action`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_tierlist` (`id_tierlist`);

--
-- Index pour la table `contenu_tierlist`
--
ALTER TABLE `contenu_tierlist`
  ADD PRIMARY KEY (`id_tierlist`,`id_action`),
  ADD KEY `id_action` (`id_action`);

--
-- Index pour la table `like_tierlist`
--
ALTER TABLE `like_tierlist`
  ADD PRIMARY KEY (`id_user`,`id_tierlist`),
  ADD KEY `id_tierlist` (`id_tierlist`);

--
-- Index pour la table `tierlist`
--
ALTER TABLE `tierlist`
  ADD PRIMARY KEY (`id_tierlist`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `action_foot`
--
ALTER TABLE `action_foot`
  MODIFY `id_action` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `tierlist`
--
ALTER TABLE `tierlist`
  MODIFY `id_tierlist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `action_foot`
--
ALTER TABLE `action_foot`
  ADD CONSTRAINT `action_foot_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`),
  ADD CONSTRAINT `action_foot_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_tierlist`) REFERENCES `tierlist` (`id_tierlist`);

--
-- Contraintes pour la table `contenu_tierlist`
--
ALTER TABLE `contenu_tierlist`
  ADD CONSTRAINT `contenu_tierlist_ibfk_1` FOREIGN KEY (`id_tierlist`) REFERENCES `tierlist` (`id_tierlist`),
  ADD CONSTRAINT `contenu_tierlist_ibfk_2` FOREIGN KEY (`id_action`) REFERENCES `action_foot` (`id_action`);

--
-- Contraintes pour la table `like_tierlist`
--
ALTER TABLE `like_tierlist`
  ADD CONSTRAINT `like_tierlist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `like_tierlist_ibfk_2` FOREIGN KEY (`id_tierlist`) REFERENCES `tierlist` (`id_tierlist`);

--
-- Contraintes pour la table `tierlist`
--
ALTER TABLE `tierlist`
  ADD CONSTRAINT `tierlist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `tierlist_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `tierlist_ibfk_3` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
