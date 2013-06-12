SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `archive` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `archive`;

CREATE TABLE `mcrm_customers` (
  `id` varchar(128) NOT NULL,
  `status` varchar(32) NOT NULL,
  `campaign` varchar(32) NOT NULL,
  `saleTS` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `saleAgent` varchar(32) NOT NULL,
  `tpvAgent` varchar(32) NOT NULL,
  `tpvTS` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fname` varchar(32) NOT NULL,
  `sname` varchar(32) NOT NULL,
  `dob` varchar(32) NOT NULL,
  `addr1` varchar(50) NOT NULL,
  `addr2` varchar(50) NOT NULL,
  `suburb` varchar(50) NOT NULL,
  `state` varchar(32) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `p_addr1` varchar(50) NOT NULL,
  `p_addr2` varchar(50) NOT NULL,
  `p_suburb` varchar(50) NOT NULL,
  `p_state` varchar(32) NOT NULL,
  `p_postcode` varchar(4) NOT NULL,
  `email` varchar(32) NOT NULL,
  `mobile` varchar(32) NOT NULL,
  `lines` varchar(32) NOT NULL,
  `abn` varchar(32) NOT NULL,
  `biz_name` varchar(128) NOT NULL,
  `biz_type` varchar(32) NOT NULL,
  `biz_start` varchar(32) NOT NULL,
  `idType` varchar(32) NOT NULL,
  `biz_asic` varchar(32) NOT NULL,
  `biz_position` varchar(128) NOT NULL,
  `idNum` varchar(32) NOT NULL,
  `billing` varchar(32) NOT NULL,
  `payment` varchar(32) NOT NULL,
  `ongoing_credit` varchar(32) NOT NULL,
  `agentNotes` text NOT NULL,
  `tpvNotes` text NOT NULL,
  `tpvFail` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `mcrm_packages` (
  `sid` varchar(32) NOT NULL,
  `line` varchar(32) NOT NULL,
  `plan` varchar(32) NOT NULL,
  KEY `line` (`line`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `mytpv_sales` (
  `sdate` varchar(512) NOT NULL,
  `stime` varchar(512) NOT NULL,
  `vcode` varchar(512) NOT NULL,
  `cencode` varchar(512) NOT NULL,
  `result` varchar(512) NOT NULL,
  `custFname` varchar(512) NOT NULL,
  `custLname` varchar(512) NOT NULL,
  `comments` varchar(1016) NOT NULL,
  `verificationcode` varchar(512) NOT NULL,
  `camp_id` varchar(512) NOT NULL,
  `salescode` varchar(512) NOT NULL,
  `phone1` varchar(512) NOT NULL,
  `phone2` varchar(512) NOT NULL,
  `phone3` varchar(512) NOT NULL,
  `phone4` varchar(512) NOT NULL,
  `camp_name` varchar(512) NOT NULL,
  `BillingOption` varchar(512) NOT NULL,
  `DOB` varchar(512) NOT NULL,
  `PhysicalAddress` varchar(512) NOT NULL,
  `PostalAddress` varchar(512) NOT NULL,
  `Mobile` varchar(512) NOT NULL,
  `email` varchar(512) NOT NULL,
  `IDForm` varchar(512) NOT NULL,
  `IDNo` varchar(512) NOT NULL,
  `DirectDebit` varchar(512) NOT NULL,
  `DirectDebit1` varchar(512) NOT NULL,
  `DirectDebit2` varchar(512) NOT NULL,
  `DirectDebit3` varchar(512) NOT NULL,
  `DirectDebit4` varchar(512) NOT NULL,
  `DirectDebit5` varchar(512) NOT NULL,
  KEY `phone1` (`phone1`),
  KEY `phone2` (`phone2`),
  KEY `phone3` (`phone3`),
  KEY `phone4` (`phone4`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
