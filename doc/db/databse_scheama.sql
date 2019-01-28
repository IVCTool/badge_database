-- Adminer 3.3.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `abstracttcs`;
CREATE TABLE `abstracttcs` (
  `filename` varchar(255) CHARACTER SET armscii8 NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(25) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `description` longtext NOT NULL,
  `requirements_id` int(10) unsigned NOT NULL,
  `version` varchar(45) NOT NULL,
  PRIMARY KEY (`id`,`requirements_id`),
  KEY `fk_abstracttcs_requirements1_idx` (`requirements_id`),
  CONSTRAINT `abstracttcs_ibfk_1` FOREIGN KEY (`requirements_id`) REFERENCES `requirements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `badges`;
CREATE TABLE `badges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` longtext NOT NULL,
  `graphicfile` varchar(255) DEFAULT NULL,
  `identifier` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `badges_has_badges`;
CREATE TABLE `badges_has_badges` (
  `badges_id` int(10) unsigned NOT NULL,
  `badges_id_dependency` int(10) unsigned NOT NULL,
  PRIMARY KEY (`badges_id`,`badges_id_dependency`),
  KEY `fk_badges_has_badges_badges2_idx` (`badges_id_dependency`),
  KEY `fk_badges_has_badges_badges1_idx` (`badges_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `badges_has_requirements`;
CREATE TABLE `badges_has_requirements` (
  `requirements_id` int(10) unsigned NOT NULL,
  `badges_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`requirements_id`,`badges_id`),
  KEY `fk_requirements_has_badges_badges1_idx` (`badges_id`),
  KEY `fk_requirements_has_badges_requirements1_idx` (`requirements_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `executabletcs`;
CREATE TABLE `executabletcs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Description` text NOT NULL,
  `classname` varchar(255) NOT NULL,
  `version` varchar(45) NOT NULL,
  `abstracttcs_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`abstracttcs_id`),
  KEY `fk_executabletcs_abstracttcs1_idx` (`abstracttcs_id`),
  CONSTRAINT `executabletcs_ibfk_1` FOREIGN KEY (`abstracttcs_id`) REFERENCES `abstracttcs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `reqcategories`;
CREATE TABLE `reqcategories` (
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `identifier` varchar(10) NOT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `requirements`;
CREATE TABLE `requirements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(25) NOT NULL,
  `description` longtext NOT NULL,
  `reqcategories_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_requirements_reqcategories_idx` (`reqcategories_id`),
  CONSTRAINT `fk_requirements_reqcategories` FOREIGN KEY (`reqcategories_id`) REFERENCES `reqcategories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(60) NOT NULL,
  `role` enum('admin','none') NOT NULL DEFAULT 'none',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2019-01-28 17:06:08