
-- Structure de la table `personne`
DROP TABLE IF EXISTS `personne`;
CREATE TABLE `personne` (
  `ID`        int(11)      NOT NULL AUTO_INCREMENT,
  `Maj`       datetime     NOT NULL,
  `nom`       varchar(256) NOT NULL,
  `prenom`    varchar(256) NOT NULL,
  `email`     varchar(512) NOT NULL,
  `affichage` int(11)      NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- Structure de la table `utilisateur`
DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `ID`       int(11)     NOT NULL AUTO_INCREMENT,
  `Personne` int(11)     NOT NULL,
  `login`    varchar(64) NOT NULL,
  `mdp`      varchar(64) NOT NULL,
  `admin`    tinyint(1)  NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Personne` (`Personne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- Contraintes pour la table `utilisateur`
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_personne` FOREIGN KEY (`Personne`) REFERENCES `personne` (`ID`);

-- --------------------------------------------------------

-- Structure de la table `visiteur`
DROP TABLE IF EXISTS `visiteur`;
CREATE TABLE `visiteur` (
  `ID`       int(11)      NOT NULL AUTO_INCREMENT,
  `Personne` int(11)      NOT NULL,
  `cle`      varchar(128) NOT NULL,
  `valide`   datetime     NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Personne` (`Personne`),
  UNIQUE KEY `cle` (`cle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- Contraintes pour la table `visiteur`
ALTER TABLE `visiteur`
  ADD CONSTRAINT `visiteur_personne` FOREIGN KEY (`Personne`) REFERENCES `personne` (`ID`);
  
-- --------------------------------------------------------

-- Structure de la table `photos`
DROP TABLE IF EXISTS `photos`;
CREATE TABLE `photos` (
  `ID`          int(11)      NOT NULL AUTO_INCREMENT,
  `Personne`    int(11)      NOT NULL,
  `nom`         varchar(128) NOT NULL,
  `chemin`      varchar(512) NOT NULL,
  `commentaire` mediumtext   NOT NULL,
  `date`        datetime     NOT NULL,
  `md5`         varchar(32)  NOT NULL COMMENT 'md5 du chemin',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `md5` (`md5`),
  KEY `Personne` (`Personne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- Contraintes pour la table `photos`
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_personne` FOREIGN KEY (`Personne`) REFERENCES `personne` (`ID`);
  
-- --------------------------------------------------------

-- Structure de la table `collection`
DROP TABLE IF EXISTS `collection`;
CREATE TABLE `collection` (
  `ID`            int(11)      NOT NULL AUTO_INCREMENT,
  `Personne`      int(11)      NOT NULL,
  `nom`           varchar(128) NOT NULL,
  `commentaire`   mediumtext   NOT NULL,
  `Photo_vitrine` int(11)      DEFAULT NULL,
  `periode_debut` date         DEFAULT NULL,
  `periode_fin`   date         DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Personne` (`Personne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- Contraintes pour la table `collection`
ALTER TABLE `collection`
  ADD CONSTRAINT `collection_personne` FOREIGN KEY (`Personne`) REFERENCES `personne` (`ID`);

-- --------------------------------------------------------

-- Structure de la table `visible_dans`
DROP TABLE IF EXISTS `visible_dans`;
CREATE TABLE `visible_dans` (
  `ID`         int(11) NOT NULL AUTO_INCREMENT,
  `Photos`     int(11) NOT NULL,
  `Collection` int(11) NOT NULL,
  `position`   int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Photos` (`Photos`),
  KEY `Collection` (`Collection`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- Contraintes pour la table `visible_dans`
ALTER TABLE `visible_dans`
  ADD CONSTRAINT `visible_dans_collection` FOREIGN KEY (`Collection`) REFERENCES `collection` (`ID`),
  ADD CONSTRAINT `photos_visible_dans` FOREIGN KEY (`Photos`) REFERENCES `photos` (`ID`);
