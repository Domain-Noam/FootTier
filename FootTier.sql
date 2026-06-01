-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 29 mai 2026 à 19:48
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
(1, 'Diego Maradona', 'Mexique 1986', '', 7, ''),
(2, 'Dennis Bergkamp', 'France 1998', '', 7, ''),
(3, 'Ronaldo R9', 'Japon-Corée 2002', '', 7, ''),
(4, 'Kylian Mbappé', 'Russie 2018', '', 7, ''),
(5, 'Pelé', 'Suède 1958', '', 7, ''),
(6, 'Andrés Iniesta', 'Afrique du Sud 2010', '', 7, ''),
(7, 'Zinedine Zidane', 'France 1998', '', 1, ''),
(8, 'Benjamin Pavard', 'Russie 2018', '', 2, ''),
(9, 'James Rodríguez', 'Brésil 2014', '', 2, ''),
(10, 'Zinedine Zidane', 'France 1998', '', 1, ''),
(11, 'Harry Kane', 'Russie 2018', '', 1, ''),
(12, 'Ronaldinho', 'Japon-Corée 2002', '', 3, ''),
(13, 'Kylian Mbappé', 'Russie 2018', '', 3, ''),
(14, 'Thibaut Courtois', 'Russie 2018', '', 4, ''),
(15, 'Iker Casillas', 'Afrique du Sud 2010', '', 4, ''),
(16, 'Roberto Carlos', 'France 1998', '', 6, ''),
(17, 'Xabi Alonso', 'Afrique du Sud 2010', '', 5, ''),
(18, 'Ronaldo R9', 'France 1998', '', 10, ''),
(19, 'Pelé', 'Mexique 1970', '', 9, ''),
(20, 'Kylian Mbappé', 'Qatar 2022', '', 7, '');

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
(1, 'Super tierlist !', '2026-04-11 08:30:00', 1, 1),
(7, 'super !', '2026-05-29 18:15:19', 5, 1);

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
(1, 1, 'S'),
(1, 2, 'B'),
(1, 3, 'B'),
(1, 4, 'D'),
(1, 6, 'S'),
(1, 7, 'C'),
(1, 9, 'A'),
(1, 10, 'S'),
(1, 11, 'A'),
(1, 12, 'A');

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
(1, 1);

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
(1, 'Mes buts de CDM préférés', 1, '2026-04-10 14:23:00', '2026-04-10 15:01:00', 1, NULL);

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
(1, 'admin', '$2y$10$u5QwGl3yvYG5N7E1uF3zVuVF6YHGLKl1Yd3IZoFJYbKkfH1uXOMWe', 1, '2026-05-19 09:16:16'),
(5, 'noam', '$2y$10$ugzXLsVGCcC11syGS/0RYO74URqE3tg5j5mNn0VQ/cQhiw4VDpYDS', 0, '2026-05-26 23:24:16');

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
  MODIFY `id_action` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `tierlist`
--
ALTER TABLE `tierlist`
  MODIFY `id_tierlist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
