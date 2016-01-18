-- phpMyAdmin SQL Dump
-- version OVH
-- http://www.phpmyadmin.net
--
-- Serveur: mysql5-10.perso
-- Généré le : Mer 09 Mars 2011 à 17:24
-- Version du serveur: 5.0.90
-- Version de PHP: 5.2.6-1+lenny8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `coyotebobdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `mini_chapine_liste_item`
--

CREATE TABLE IF NOT EXISTS `mini_chapine_liste_item` (
  `uid` int(11) NOT NULL auto_increment,
  `titre` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `photo` longtext NOT NULL,
  `tstamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `ordre` int(11) NOT NULL,
  `etat` int(11) NOT NULL,
  `response_uid` varchar(250) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `mini_chapine_liste_item`
--


-- --------------------------------------------------------

--
-- Structure de la table `mini_chapine_liste_reponse`
--

CREATE TABLE IF NOT EXISTS `mini_chapine_liste_reponse` (
  `uid` int(11) NOT NULL auto_increment,
  `nom` varchar(250) NOT NULL,
  `commentaire` text NOT NULL,
  `tstamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `mini_chapine_liste_reponse`
--

