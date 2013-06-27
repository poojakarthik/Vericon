SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `paf` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `paf`;

CREATE TABLE `pcode` (
  `postcode` int(4) NOT NULL,
  `locality` varchar(256) NOT NULL,
  `state` varchar(3) NOT NULL,
  KEY `postcode` (`postcode`),
  KEY `locality` (`locality`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;