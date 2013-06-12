SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `adsl` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `adsl`;

CREATE TABLE `ADSL2P` (
  `State` varchar(32) NOT NULL,
  `Exch_Id` varchar(32) NOT NULL,
  `Exch_Desc` varchar(128) NOT NULL,
  PRIMARY KEY (`Exch_Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `Enabled_Exchanges` (
  `State` varchar(32) NOT NULL,
  `CCA` varchar(128) NOT NULL,
  `Exch_ID` varchar(32) NOT NULL,
  `Exch_Desc` varchar(128) NOT NULL,
  `AC` varchar(32) NOT NULL,
  `Range_From` int(32) NOT NULL,
  `Range_To` int(32) NOT NULL,
  `Full` varchar(32) NOT NULL,
  `Zone` varchar(32) NOT NULL,
  `HIBIS` varchar(32) NOT NULL,
  `HIBIS_Data` varchar(32) NOT NULL,
  KEY `Range_From` (`Range_From`,`Range_To`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
