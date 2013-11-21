SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `gnaf` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gnaf`;

CREATE TABLE `ADDRESS_ALIAS` (
  `address_alias_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `principal_pid` varchar(15) NOT NULL,
  `alias_pid` varchar(15) NOT NULL,
  `alias_type_code` varchar(10) NOT NULL,
  `alias_comment` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`address_alias_pid`),
  KEY `ADDRESS_ALIAS_FK1` (`alias_pid`),
  KEY `ADDRESS_ALIAS_FK2` (`alias_type_code`),
  KEY `ADDRESS_ALIAS_FK3` (`principal_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ADDRESS_ALIAS_TYPE_AUT` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ADDRESS_DETAIL` (
  `address_detail_pid` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_last_modified` datetime DEFAULT NULL,
  `date_retired` datetime DEFAULT NULL,
  `building_name` varchar(45) DEFAULT NULL,
  `lot_number_prefix` varchar(2) DEFAULT NULL,
  `lot_number` varchar(5) DEFAULT NULL,
  `lot_number_suffix` varchar(2) DEFAULT NULL,
  `flat_type_code` varchar(7) DEFAULT NULL,
  `flat_number_prefix` varchar(2) DEFAULT NULL,
  `flat_number` decimal(5,0) DEFAULT NULL,
  `flat_number_suffix` varchar(2) DEFAULT NULL,
  `level_type_code` varchar(4) DEFAULT NULL,
  `level_number_prefix` varchar(2) DEFAULT NULL,
  `level_number` decimal(3,0) DEFAULT NULL,
  `level_number_suffix` varchar(2) DEFAULT NULL,
  `number_first_prefix` varchar(3) DEFAULT NULL,
  `number_first` decimal(6,0) DEFAULT NULL,
  `number_first_suffix` varchar(2) DEFAULT NULL,
  `number_last_prefix` varchar(3) DEFAULT NULL,
  `number_last` decimal(6,0) DEFAULT NULL,
  `number_last_suffix` varchar(2) DEFAULT NULL,
  `street_locality_pid` varchar(15) DEFAULT NULL,
  `location_description` varchar(45) DEFAULT NULL,
  `locality_pid` varchar(15) NOT NULL,
  `alias_principal` char(1) DEFAULT NULL,
  `postcode` varchar(4) DEFAULT NULL,
  `private_street` varchar(75) DEFAULT NULL,
  `legal_parcel_id` varchar(20) DEFAULT NULL,
  `confidence` decimal(1,0) DEFAULT NULL,
  `address_site_pid` varchar(15) NOT NULL,
  `level_geocoded_code` decimal(2,0) NOT NULL,
  `property_pid` varchar(15) DEFAULT NULL,
  `gnaf_property_pid` varchar(15) DEFAULT NULL,
  `primary_secondary` varchar(1) DEFAULT NULL,
  `street_name` varchar(100) NOT NULL,
  `street_type_code` varchar(15) DEFAULT NULL,
  `street_suffix_code` varchar(15) DEFAULT NULL,
  `locality_name` varchar(100) NOT NULL,
  `state` varchar(3) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `reliability_code` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`address_detail_pid`),
  KEY `ADDRESS_DETAIL_FK1` (`address_site_pid`),
  KEY `ADDRESS_DETAIL_FK2` (`flat_type_code`),
  KEY `ADDRESS_DETAIL_FK3` (`level_geocoded_code`),
  KEY `ADDRESS_DETAIL_FK4` (`level_type_code`),
  KEY `ADDRESS_DETAIL_FK5` (`locality_pid`),
  KEY `ADDRESS_DETAIL_FK6` (`street_locality_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ADDRESS_MESH_BLOCK` (
  `address_mesh_block_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `address_detail_pid` varchar(15) NOT NULL,
  `mesh_block_pid` varchar(15) NOT NULL,
  PRIMARY KEY (`address_mesh_block_pid`),
  KEY `ADDRESS_MESH_BLOCK_FK1` (`address_detail_pid`),
  KEY `ADDRESS_MESH_BLOCK_FK2` (`mesh_block_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ADDRESS_MESH_BLOCK_2011` (
  `address_mesh_block_2011_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `address_detail_pid` varchar(15) NOT NULL,
  `mb_2011_pid` varchar(15) NOT NULL,
  PRIMARY KEY (`address_mesh_block_2011_pid`),
  KEY `ADDRESS_MESH_BLOCK_2011_FK1` (`address_detail_pid`),
  KEY `ADDRESS_MESH_BLOCK_2011_FK2` (`mb_2011_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ADDRESS_SITE` (
  `address_site_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `address_type` varchar(8) DEFAULT NULL,
  `address_site_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`address_site_pid`),
  KEY `ADDRESS_SITE_FK1` (`address_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ADDRESS_SITE_GEOCODE` (
  `address_site_geocode_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `address_site_pid` varchar(15) DEFAULT NULL,
  `geocode_site_name` varchar(46) DEFAULT NULL,
  `geocode_site_description` varchar(45) DEFAULT NULL,
  `geocode_type_code` varchar(4) DEFAULT NULL,
  `reliability_code` decimal(1,0) DEFAULT NULL,
  `boundary_extent` decimal(7,0) DEFAULT NULL,
  `planimetric_accuracy` decimal(12,0) DEFAULT NULL,
  `elevation` decimal(7,0) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  PRIMARY KEY (`address_site_geocode_pid`),
  KEY `ADDRESS_SITE_GEOCODE_FK1` (`address_site_pid`),
  KEY `ADDRESS_SITE_GEOCODE_FK2` (`geocode_type_code`),
  KEY `ADDRESS_SITE_GEOCODE_FK3` (`reliability_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ADDRESS_TYPE_AUT` (
  `code` varchar(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `FLAT_TYPE_AUT` (
  `code` varchar(7) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `GEOCODED_LEVEL_TYPE_AUT` (
  `code` decimal(2,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `GEOCODE_RELIABILITY_AUT` (
  `code` decimal(1,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `GEOCODE_TYPE_AUT` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `GNAF_REJECT_AUT` (
  `code` decimal(3,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `terminal` char(1) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `LEVEL_TYPE_AUT` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `LOCALITY` (
  `locality_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `locality_name` varchar(100) NOT NULL,
  `primary_postcode` varchar(4) DEFAULT NULL,
  `locality_class_code` char(1) NOT NULL,
  `state_pid` varchar(15) NOT NULL,
  `gnaf_locality_pid` varchar(15) DEFAULT NULL,
  `gnaf_reliability_code` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`locality_pid`),
  KEY `LOCALITY_FK1` (`gnaf_reliability_code`),
  KEY `LOCALITY_FK2` (`locality_class_code`),
  KEY `LOCALITY_FK3` (`state_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `LOCALITY_ALIAS` (
  `locality_alias_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `locality_pid` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `postcode` varchar(4) DEFAULT NULL,
  `alias_type_code` varchar(10) NOT NULL,
  `state_pid` varchar(15) NOT NULL,
  PRIMARY KEY (`locality_alias_pid`),
  KEY `LOCALITY_ALIAS_FK1` (`alias_type_code`),
  KEY `LOCALITY_ALIAS_FK2` (`locality_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `LOCALITY_ALIAS_TYPE_AUT` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `LOCALITY_CLASS_AUT` (
  `code` char(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `LOCALITY_NEIGHBOUR` (
  `locality_neighbour_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `locality_pid` varchar(15) NOT NULL,
  `neighbour_locality_pid` varchar(15) NOT NULL,
  PRIMARY KEY (`locality_neighbour_pid`),
  KEY `LOCALITY_NEIGHBOUR_FK1` (`locality_pid`),
  KEY `LOCALITY_NEIGHBOUR_FK2` (`neighbour_locality_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `LOCALITY_POINT` (
  `locality_point_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `locality_pid` varchar(15) NOT NULL,
  `planimetric_accuracy` decimal(12,0) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  PRIMARY KEY (`locality_point_pid`),
  KEY `LOCALITY_POINT_FK1` (`locality_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `MB_2011` (
  `mb_2011_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `mb_2011_code` varchar(15) NOT NULL,
  PRIMARY KEY (`mb_2011_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `MESH_BLOCK` (
  `mesh_block_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `mesh_block_code` decimal(13,0) NOT NULL,
  PRIMARY KEY (`mesh_block_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `PRIMARY_SECONDARY` (
  `primary_secondary_pid` varchar(15) NOT NULL,
  `primary_pid` varchar(15) NOT NULL,
  `secondary_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `ps_join_type_code` decimal(2,0) NOT NULL,
  `ps_join_comment` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`primary_secondary_pid`),
  KEY `PRIMARY_SECONDARY_FK1` (`primary_pid`),
  KEY `PRIMARY_SECONDARY_FK2` (`ps_join_type_code`),
  KEY `PRIMARY_SECONDARY_FK3` (`secondary_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `PS_JOIN_TYPE_AUT` (
  `code` decimal(2,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STATE` (
  `state_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `state_name` varchar(50) NOT NULL,
  `state_abbreviation` varchar(3) NOT NULL,
  PRIMARY KEY (`state_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STREET_CLASS_AUT` (
  `code` char(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STREET_LOCALITY` (
  `street_locality_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `street_class_code` char(1) NOT NULL,
  `street_name` varchar(100) NOT NULL,
  `street_type_code` varchar(15) DEFAULT NULL,
  `street_suffix_code` varchar(15) DEFAULT NULL,
  `locality_pid` varchar(15) NOT NULL,
  `gnaf_street_pid` varchar(15) DEFAULT NULL,
  `gnaf_street_confidence` decimal(1,0) DEFAULT NULL,
  `gnaf_reliability_code` decimal(1,0) DEFAULT NULL,
  PRIMARY KEY (`street_locality_pid`),
  KEY `STREET_LOCALITY_FK1` (`gnaf_reliability_code`),
  KEY `STREET_LOCALITY_FK2` (`locality_pid`),
  KEY `STREET_LOCALITY_FK3` (`street_class_code`),
  KEY `STREET_LOCALITY_FK4` (`street_suffix_code`),
  KEY `STREET_LOCALITY_FK5` (`street_type_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STREET_LOCALITY_ALIAS` (
  `street_locality_alias_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `street_locality_pid` varchar(15) NOT NULL,
  `street_name` varchar(100) NOT NULL,
  `street_type_code` varchar(15) DEFAULT NULL,
  `street_suffix_code` varchar(15) DEFAULT NULL,
  `alias_type_code` varchar(10) NOT NULL,
  PRIMARY KEY (`street_locality_alias_pid`),
  KEY `STREET_LOCALITY_ALIAS_FK1` (`alias_type_code`),
  KEY `STREET_LOCALITY_ALIAS_FK2` (`street_locality_pid`),
  KEY `STREET_LOCALITY_ALIAS_FK3` (`street_suffix_code`),
  KEY `STREET_LOCALITY_ALIAS_FK4` (`street_type_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STREET_LOCALITY_ALIAS_TYPE_AUT` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STREET_LOCALITY_POINT` (
  `street_locality_point_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `street_locality_pid` varchar(15) NOT NULL,
  `boundary_extent` decimal(7,0) DEFAULT NULL,
  `planimetric_accuracy` decimal(12,0) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  PRIMARY KEY (`street_locality_point_pid`),
  KEY `STREET_LOCALITY_POINT_FK1` (`street_locality_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STREET_SUFFIX_AUT` (
  `code` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `STREET_TYPE_AUT` (
  `code` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
