SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `vericon` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `vericon`;

CREATE TABLE `address` (
  `id` varchar(15) NOT NULL,
  `building_type` varchar(32) NOT NULL,
  `building_number` varchar(32) NOT NULL,
  `building_number_suffix` varchar(32) NOT NULL,
  `building_name` varchar(512) NOT NULL,
  `number_first` varchar(32) NOT NULL,
  `number_last` varchar(32) NOT NULL,
  `street_name` varchar(512) NOT NULL,
  `street_type` varchar(128) NOT NULL,
  `suburb` varchar(128) NOT NULL,
  `city_town` varchar(128) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `allowedip` (
  `IP` varchar(50) NOT NULL,
  `status` int(25) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` varchar(8) NOT NULL,
  PRIMARY KEY (`IP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display` varchar(25) NOT NULL,
  `date` varchar(25) NOT NULL,
  `poster` varchar(300) NOT NULL,
  `department` varchar(300) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `auth` (
  `user` varchar(25) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `type` varchar(300) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `status` varchar(25) NOT NULL,
  `first` varchar(300) NOT NULL,
  `last` varchar(300) NOT NULL,
  `alias` varchar(300) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `campaigns` (
  `id` varchar(16) NOT NULL,
  `group` varchar(64) NOT NULL,
  `campaign` varchar(128) NOT NULL,
  `number` varchar(32) NOT NULL,
  `website` varchar(64) NOT NULL,
  `country` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `centres` (
  `centre` varchar(32) NOT NULL,
  `campaign` varchar(512) NOT NULL,
  `type` varchar(16) NOT NULL,
  `status` varchar(16) NOT NULL,
  `leads` int(11) NOT NULL,
  PRIMARY KEY (`centre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `currentuser` (
  `hash` varchar(300) NOT NULL,
  `user` varchar(300) NOT NULL,
  `current_page` varchar(300) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`hash`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

CREATE TABLE `customers` (
  `id` varchar(15) NOT NULL,
  `sf_id` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `tb_id` varchar(32) NOT NULL,
  `status` varchar(64) NOT NULL,
  `last_edit_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_edit_by` varchar(8) NOT NULL,
  `industry` varchar(25) NOT NULL,
  `lead_id` varchar(10) NOT NULL,
  `sale_id` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `agent` varchar(10) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `campaign` varchar(300) NOT NULL,
  `type` varchar(25) NOT NULL,
  `title` varchar(10) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `middlename` varchar(300) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(300) NOT NULL,
  `mobile` varchar(16) NOT NULL,
  `billing` varchar(25) NOT NULL,
  `welcome` varchar(25) NOT NULL,
  `promotions` varchar(8) NOT NULL,
  `physical` varchar(300) NOT NULL,
  `postal` varchar(300) NOT NULL,
  `id_type` varchar(25) NOT NULL,
  `id_num` varchar(25) NOT NULL,
  `abn` varchar(25) NOT NULL,
  `bus_name` varchar(256) NOT NULL,
  `position` varchar(300) NOT NULL,
  `best_buddy` varchar(16) NOT NULL,
  `modem_address` varchar(512) NOT NULL,
  `credit` int(11) NOT NULL,
  `ongoing_credit` int(11) NOT NULL,
  `onceoff_credit` int(11) NOT NULL,
  `promo_code` varchar(16) NOT NULL,
  `promo_cli` varchar(16) NOT NULL,
  `promo_plan` varchar(16) NOT NULL,
  `payway` varchar(16) NOT NULL,
  `dd_type` varchar(16) NOT NULL,
  `billing_comments` text NOT NULL,
  `other_comments` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sale_id` (`sale_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `customers_log` (
  `id` varchar(15) NOT NULL,
  `sf_id` varchar(64) NOT NULL,
  `tb_id` varchar(32) NOT NULL,
  `status` varchar(64) NOT NULL,
  `last_edit_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_edit_by` varchar(8) NOT NULL,
  `industry` varchar(25) NOT NULL,
  `lead_id` varchar(10) NOT NULL,
  `sale_id` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `agent` varchar(10) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `campaign` varchar(300) NOT NULL,
  `type` varchar(25) NOT NULL,
  `title` varchar(10) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `middlename` varchar(300) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(300) NOT NULL,
  `mobile` varchar(16) NOT NULL,
  `billing` varchar(25) NOT NULL,
  `welcome` varchar(25) NOT NULL,
  `promotions` varchar(8) NOT NULL,
  `physical` varchar(300) NOT NULL,
  `postal` varchar(300) NOT NULL,
  `id_type` varchar(25) NOT NULL,
  `id_num` varchar(25) NOT NULL,
  `abn` varchar(25) NOT NULL,
  `bus_name` varchar(256) NOT NULL,
  `position` varchar(300) NOT NULL,
  `best_buddy` varchar(16) NOT NULL,
  `modem_address` varchar(512) NOT NULL,
  `credit` int(11) NOT NULL,
  `ongoing_credit` int(11) NOT NULL,
  `onceoff_credit` int(11) NOT NULL,
  `payway` varchar(16) NOT NULL,
  `dd_type` varchar(16) NOT NULL,
  `billing_comments` text NOT NULL,
  `other_comments` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `customers_notes` (
  `id` varchar(16) NOT NULL,
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(32) NOT NULL,
  `note` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `employees` (
  `user` varchar(8) NOT NULL,
  `id` varchar(32) NOT NULL,
  PRIMARY KEY (`user`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `groups` (
  `id` varchar(16) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `international` (
  `country` varchar(50) NOT NULL,
  `rate` varchar(50) NOT NULL,
  PRIMARY KEY (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `log_access` (
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `page` varchar(300) NOT NULL,
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `log_gnaf` (
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` varchar(8) NOT NULL,
  `input` varchar(512) NOT NULL,
  `result` int(1) NOT NULL,
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `log_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(32) NOT NULL,
  `user` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `log_unauthorised` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(32) NOT NULL,
  `user` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `operations` (
  `user` varchar(16) NOT NULL,
  `centres` text NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `packages` (
  `id` varchar(16) NOT NULL,
  `cli` varchar(16) NOT NULL,
  `plan` varchar(64) NOT NULL,
  `provider` varchar(64) NOT NULL,
  `ac_number` varchar(32) NOT NULL,
  `adsl_provider` varchar(64) NOT NULL,
  `adsl_ac_number` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `edit_by` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `packages_log` (
  `id` varchar(16) NOT NULL,
  `cli` varchar(16) NOT NULL,
  `plan` varchar(64) NOT NULL,
  `provider` varchar(64) NOT NULL,
  `ac_number` varchar(32) NOT NULL,
  `adsl_provider` varchar(64) NOT NULL,
  `adsl_ac_number` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL,
  `edit_by` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `plan_matrix` (
  `id` varchar(18) NOT NULL,
  `campaign` varchar(64) NOT NULL,
  `t_id` varchar(32) NOT NULL,
  `s_id` varchar(32) NOT NULL,
  `status` varchar(16) NOT NULL,
  `rating` varchar(16) NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(32) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`,`campaign`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `plan_rates` (
  `plan_id` varchar(25) NOT NULL,
  `plan_code` varchar(300) NOT NULL,
  `sf_plan_code` varchar(32) NOT NULL,
  `plan_fee` varchar(300) NOT NULL,
  `contract_term` varchar(300) NOT NULL,
  `local` varchar(300) NOT NULL,
  `national` varchar(300) NOT NULL,
  `mobile` varchar(300) NOT NULL,
  `data` varchar(300) NOT NULL,
  `etf` varchar(300) NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `portals` (
  `id` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `portals_access` (
  `user` varchar(16) NOT NULL,
  `pages` text NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `portals_pages` (
  `id` varchar(32) NOT NULL,
  `portal` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `link` varchar(64) NOT NULL,
  `level` int(11) NOT NULL,
  `sub_level` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portal` (`portal`,`level`,`sub_level`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `portals_template` (
  `user` varchar(16) NOT NULL,
  `type` varchar(16) NOT NULL,
  `pages` text NOT NULL,
  PRIMARY KEY (`user`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `providers` (
  `name` varchar(64) NOT NULL,
  `value` varchar(64) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `qa_customers` (
  `id` varchar(15) NOT NULL,
  `status` varchar(25) NOT NULL,
  `lead_id` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `verifier` varchar(8) NOT NULL,
  `sale_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `agent` varchar(10) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `campaign` varchar(300) NOT NULL,
  `type` varchar(25) NOT NULL,
  `lead_check` int(8) NOT NULL,
  `recording_check` int(8) NOT NULL,
  `details_check` int(8) NOT NULL,
  `rejection_reason` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `recordings` (
  `id` varchar(16) NOT NULL,
  `sale_id` varchar(16) NOT NULL,
  `type` varchar(64) NOT NULL,
  `name` varchar(512) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `sales_customers` (
  `id` varchar(15) NOT NULL,
  `status` varchar(25) NOT NULL,
  `industry` varchar(25) NOT NULL,
  `lead_id` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approved_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `agent` varchar(10) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `campaign` varchar(300) NOT NULL,
  `type` varchar(25) NOT NULL,
  `title` varchar(10) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `middlename` varchar(300) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(300) NOT NULL,
  `mobile` varchar(16) NOT NULL,
  `billing` varchar(25) NOT NULL,
  `welcome` varchar(25) NOT NULL,
  `promotions` varchar(8) NOT NULL,
  `physical` varchar(300) NOT NULL,
  `postal` varchar(300) NOT NULL,
  `id_type` varchar(25) NOT NULL,
  `id_num` varchar(25) NOT NULL,
  `abn` varchar(25) NOT NULL,
  `bus_name` varchar(256) NOT NULL,
  `position` varchar(300) NOT NULL,
  `best_buddy` varchar(16) NOT NULL,
  `modem_address` varchar(512) NOT NULL,
  `ongoing_credit` int(11) NOT NULL,
  `onceoff_credit` int(11) NOT NULL,
  `promo_code` varchar(16) NOT NULL,
  `promo_cli` varchar(16) NOT NULL,
  `promo_plan` varchar(16) NOT NULL,
  `payway` varchar(16) NOT NULL,
  `dd_type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `centre` (`centre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `sales_customers_temp` (
  `lead_id` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `agent` varchar(10) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `campaign` varchar(300) NOT NULL,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`lead_id`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

CREATE TABLE `sales_packages` (
  `sid` varchar(10) NOT NULL,
  `cli` varchar(10) NOT NULL,
  `plan` varchar(300) NOT NULL,
  `provider` varchar(64) NOT NULL,
  `ac_number` varchar(32) NOT NULL,
  `adsl_provider` varchar(64) NOT NULL,
  `adsl_ac_number` varchar(32) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `sales_packages_temp` (
  `lead_id` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cli` varchar(10) NOT NULL,
  `plan` varchar(300) NOT NULL,
  `provider` varchar(64) NOT NULL,
  `ac_number` varchar(32) NOT NULL,
  `adsl_provider` varchar(64) NOT NULL,
  `adsl_ac_number` varchar(32) NOT NULL,
  KEY `lead_id` (`lead_id`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

CREATE TABLE `script_order` (
  `id` varchar(16) NOT NULL,
  `campaign` varchar(16) NOT NULL,
  `type` varchar(32) NOT NULL,
  `page` int(11) NOT NULL,
  `question` int(11) NOT NULL,
  `back` varchar(1) NOT NULL,
  `next` varchar(1) NOT NULL,
  PRIMARY KEY (`id`,`campaign`,`type`,`page`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `script_plans` (
  `id` varchar(16) NOT NULL,
  `campaign` varchar(32) NOT NULL,
  `script` text NOT NULL,
  PRIMARY KEY (`id`,`campaign`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `script_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `question` text NOT NULL,
  `input` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `sct_dnc` (
  `cli` varchar(16) NOT NULL,
  PRIMARY KEY (`cli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `timesheet` (
  `user` varchar(16) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `date` date NOT NULL,
  `designation` varchar(64) NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `hours` decimal(10,2) NOT NULL,
  `dialler_hours` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) NOT NULL,
  PRIMARY KEY (`user`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `timesheet_designation` (
  `user` varchar(16) NOT NULL,
  `designation` varchar(64) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `timesheet_mcost` (
  `centre` varchar(16) NOT NULL,
  `week` varchar(16) NOT NULL,
  `m_cost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`centre`,`week`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `timesheet_other` (
  `user` varchar(16) NOT NULL,
  `week` varchar(16) NOT NULL,
  `cancellations` int(11) NOT NULL,
  `op_hours` decimal(10,2) NOT NULL,
  `op_bonus` decimal(10,2) NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  `payg` decimal(10,2) NOT NULL,
  `annual` decimal(10,2) NOT NULL,
  `sick` decimal(10,2) NOT NULL,
  `comment` text NOT NULL,
  `pay_type` varchar(1) NOT NULL,
  `base_rate` decimal(10,4) NOT NULL,
  PRIMARY KEY (`user`,`week`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `timesheet_rate` (
  `user` varchar(16) NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `timesheet_tiered` (
  `designation` varchar(32) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  KEY `designation` (`designation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `tpv_notes` (
  `id` varchar(25) NOT NULL,
  `status` varchar(25) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lead_id` varchar(10) NOT NULL,
  `centre` varchar(8) NOT NULL,
  `verifier` varchar(25) NOT NULL,
  `note` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `tutorials` (
  `id` varchar(16) NOT NULL,
  `portal` varchar(16) NOT NULL,
  `name` varchar(64) NOT NULL,
  `link` varchar(512) NOT NULL,
  `thumbnail` mediumblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(25) NOT NULL,
  `poster` varchar(300) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `verification_lock` (
  `id` varchar(16) NOT NULL,
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

CREATE TABLE `vicidial_live` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `vicidial_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `input` varchar(16) NOT NULL,
  `result` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `welcome` (
  `id` varchar(16) NOT NULL,
  `status` varchar(64) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` varchar(8) NOT NULL,
  `dd` int(1) NOT NULL,
  `cancellation_reason` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `welcome_cb` (
  `id` varchar(16) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `welcome_lock` (
  `id` varchar(16) NOT NULL,
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
