-- Base De Données du projet FootTier, avec des exemples pré-remplis
-- 
-- Structure de la table `categorie`
-- 

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` INTEGER NOT NULL AUTO_INCREMENT,
  `nom_categorie` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_categorie`)
);

-- 
-- Contenu de la table `categorie`
-- 

INSERT INTO `categorie` VALUES (1, 'But de la tête');
INSERT INTO `categorie` VALUES (2, 'Reprise de volée');
INSERT INTO `categorie` VALUES (3, 'Dribble');
INSERT INTO `categorie` VALUES (4, 'Arrêt');
INSERT INTO `categorie` VALUES (5, 'But longue distance');
INSERT INTO `categorie` VALUES (6, 'Coup franc');
INSERT INTO `categorie` VALUES (7, 'But en solo');
INSERT INTO `categorie` VALUES (8, 'Contre décisif');
INSERT INTO `categorie` VALUES (9, 'Passe décisive');
INSERT INTO `categorie` VALUES (10, 'Penalty');

-- --------------------------------------------------------

-- 
-- Structure de la table `utilisateur`
-- 

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` INTEGER NOT NULL AUTO_INCREMENT,
  `pseudo` VARCHAR(50) NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL,
  `est_admin` BOOLEAN DEFAULT 0,
  `date_inscription` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY (`pseudo`)
);

-- 
-- Contenu de la table `utilisateur`
-- 

INSERT INTO `utilisateur` VALUES (1, 'admin', '$2y$10$u5QwGl3yvYG5N7E1uF3zVuVF6YHGLKl1Yd3IZoFJYbKkfH1uXOMWe', 1, '2026-05-19 09:16:16');

-- --------------------------------------------------------

-- 
-- Structure de la table `action_foot`
-- 

DROP TABLE IF EXISTS `action_foot`;
CREATE TABLE IF NOT EXISTS `action_foot` (
  `id_action` INTEGER NOT NULL AUTO_INCREMENT,
  `joueur` VARCHAR(100) NOT NULL,
  `competition` VARCHAR(100) NOT NULL,
  `url_media` VARCHAR(255) DEFAULT '',
  `id_categorie` INTEGER NOT NULL,
  `url_image` VARCHAR(255) DEFAULT '',
  PRIMARY KEY (`id_action`)
);

-- 
-- Contenu de la table `action_foot`
-- 

INSERT INTO `action_foot` VALUES (1, 'Diego Maradona', 'Mexique 1986', '', 7, '');
INSERT INTO `action_foot` VALUES (2, 'Dennis Bergkamp', 'France 1998', '', 7, '');
INSERT INTO `action_foot` VALUES (3, 'Ronaldo R9', 'Japon-Corée 2002', '', 7, '');
INSERT INTO `action_foot` VALUES (4, 'Kylian Mbappé', 'Russie 2018', '', 7, '');
INSERT INTO `action_foot` VALUES (5, 'Pelé', 'Suède 1958', '', 7, '');
INSERT INTO `action_foot` VALUES (6, 'Andrés Iniesta', 'Afrique du Sud 2010', '', 7, '');
INSERT INTO `action_foot` VALUES (7, 'Zinedine Zidane', 'France 1998', '', 1, '');
INSERT INTO `action_foot` VALUES (8, 'Benjamin Pavard', 'Russie 2018', '', 2, '');
INSERT INTO `action_foot` VALUES (9, 'James Rodríguez', 'Brésil 2014', '', 2, '');
INSERT INTO `action_foot` VALUES (10, 'Zinedine Zidane', 'France 1998', '', 1, '');
INSERT INTO `action_foot` VALUES (11, 'Harry Kane', 'Russie 2018', '', 1, '');
INSERT INTO `action_foot` VALUES (12, 'Ronaldinho', 'Japon-Corée 2002', '', 3, '');
INSERT INTO `action_foot` VALUES (13, 'Kylian Mbappé', 'Russie 2018', '', 3, '');
INSERT INTO `action_foot` VALUES (14, 'Thibaut Courtois', 'Russie 2018', '', 4, '');
INSERT INTO `action_foot` VALUES (15, 'Iker Casillas', 'Afrique du Sud 2010', '', 4, '');
INSERT INTO `action_foot` VALUES (16, 'Roberto Carlos', 'France 1998', '', 6, '');
INSERT INTO `action_foot` VALUES (17, 'Xabi Alonso', 'Afrique du Sud 2010', '', 5, '');
INSERT INTO `action_foot` VALUES (18, 'Ronaldo R9', 'France 1998', '', 10, '');
INSERT INTO `action_foot` VALUES (19, 'Pelé', 'Mexique 1970', '', 9, '');
INSERT INTO `action_foot` VALUES (20, 'Kylian Mbappé', 'Qatar 2022', '', 7, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `tierlist`
-- 

DROP TABLE IF EXISTS `tierlist`;
CREATE TABLE IF NOT EXISTS `tierlist` (
  `id_tierlist` INTEGER NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(150) NOT NULL,
  `est_publique` BOOLEAN DEFAULT 0,
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `date_modification` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `id_user` INTEGER NOT NULL,
  PRIMARY KEY (`id_tierlist`)
);

-- 
-- Contenu de la table `tierlist`
-- 

INSERT INTO `tierlist` VALUES (1, 'Mes buts de CDM préférés', 1, '2026-04-10 14:23:00', '2026-04-10 15:01:00', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `commentaire`
-- 

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` INTEGER NOT NULL AUTO_INCREMENT,
  `contenu` VARCHAR(500) NOT NULL,
  `date_publication` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `id_user` INTEGER NOT NULL,
  `id_tierlist` INTEGER NOT NULL,
  PRIMARY KEY (`id_commentaire`)
);

-- 
-- Contenu de la table `commentaire`
-- 

INSERT INTO `commentaire` VALUES (1, 'Super tierlist !', '2026-04-11 08:30:00', 1, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `contenu_tierlist`
-- 

DROP TABLE IF EXISTS `contenu_tierlist`;
CREATE TABLE IF NOT EXISTS `contenu_tierlist` (
  `id_tierlist` INTEGER NOT NULL,
  `id_action` INTEGER NOT NULL,
  `tier` VARCHAR(1) NOT NULL,
  PRIMARY KEY (`id_tierlist`,`id_action`)
);

-- 
-- Contenu de la table `contenu_tierlist`
-- 

INSERT INTO `contenu_tierlist` VALUES (1, 1, 'S');
INSERT INTO `contenu_tierlist` VALUES (1, 2, 'B');
INSERT INTO `contenu_tierlist` VALUES (1, 3, 'B');
INSERT INTO `contenu_tierlist` VALUES (1, 4, 'D');
INSERT INTO `contenu_tierlist` VALUES (1, 6, 'S');
INSERT INTO `contenu_tierlist` VALUES (1, 7, 'C');
INSERT INTO `contenu_tierlist` VALUES (1, 9, 'A');
INSERT INTO `contenu_tierlist` VALUES (1, 10, 'S');
INSERT INTO `contenu_tierlist` VALUES (1, 11, 'A');
INSERT INTO `contenu_tierlist` VALUES (1, 12, 'A');

-- --------------------------------------------------------

-- 
-- Structure de la table `like_tierlist`
-- 

DROP TABLE IF EXISTS `like_tierlist`;
CREATE TABLE IF NOT EXISTS `like_tierlist` (
  `id_user` INTEGER NOT NULL,
  `id_tierlist` INTEGER NOT NULL,
  PRIMARY KEY (`id_user`,`id_tierlist`)
);

-- 
-- Contenu de la table `like_tierlist`
-- 

INSERT INTO `like_tierlist` VALUES (1, 1);

-- --------------------------------------------------------

-- 
-- Contraintes pour les tables exportées
-- 

--
-- Contraintes pour la table `action_foot`
--
ALTER TABLE `action_foot`
  ADD FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);
  
--
-- Contraintes pour la table `tierlist`
--
ALTER TABLE `tierlist`
  ADD FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`),
  ADD FOREIGN KEY (`id_tierlist`) REFERENCES `tierlist` (`id_tierlist`);
  
--
-- Contraintes pour la table `contenu_tierlist`
--
ALTER TABLE `contenu_tierlist`
  ADD FOREIGN KEY (`id_tierlist`) REFERENCES `tierlist` (`id_tierlist`),
  ADD FOREIGN KEY (`id_action`) REFERENCES `action_foot` (`id_action`);
  
--
-- Contraintes pour la table `like_tierlist`
--
ALTER TABLE `like_tierlist`
  ADD FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`),
  ADD FOREIGN KEY (`id_tierlist`) REFERENCES `tierlist` (`id_tierlist`);
