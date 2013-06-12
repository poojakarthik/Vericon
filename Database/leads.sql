SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `leads` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `leads`;

CREATE TABLE `groups` (
  `group` varchar(16) NOT NULL,
  `centres` text NOT NULL,
  PRIMARY KEY (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `leads` (
  `cli` int(10) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `packet_expiry` date NOT NULL,
  PRIMARY KEY (`cli`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (cli)
PARTITIONS 30 */;

CREATE TABLE `leads_time` (
  `centre` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`centre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `log_leads` (
  `cli` int(10) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `packet_expiry` date NOT NULL,
  KEY `cli` (`cli`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (cli)
PARTITIONS 30 */;
SET FOREIGN_KEY_CHECKS=1;
