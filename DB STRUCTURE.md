#Database Structure
As of 04/06/2012

| Table of Contents |
| ------------- |
| [ADSL](#adsl) |
| [Archive](#archive) |
| [GNAF](#gnaf) |
| [Leads](#leads) |
| [PAF](#paf) |
| Terralinks(#terralinks)  |
| [VeriCon](#vericon)  |

##ADSL
```sql
--
-- Table structure for table `ADSL2P`
--

CREATE TABLE IF NOT EXISTS `ADSL2P` (
  `State` varchar(32) NOT NULL,
  `Exch_Id` varchar(32) NOT NULL,
  `Exch_Desc` varchar(128) NOT NULL,
  PRIMARY KEY (`Exch_Id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `Enabled_Exchanges`
--

CREATE TABLE IF NOT EXISTS `Enabled_Exchanges` (
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
```

##Archive
```sql
--
-- Table structure for table `mcrm_customers`
--

CREATE TABLE IF NOT EXISTS `mcrm_customers` (
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
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search` (`saleAgent`,`fname`,`sname`,`dob`,`addr1`,`addr2`,`suburb`,`postcode`,`p_addr1`,`p_addr2`,`p_suburb`,`p_postcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `mcrm_packages`
--

CREATE TABLE IF NOT EXISTS `mcrm_packages` (
  `sid` varchar(32) NOT NULL,
  `line` varchar(32) NOT NULL,
  `plan` varchar(32) NOT NULL,
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `mytpv_sales`
--

CREATE TABLE IF NOT EXISTS `mytpv_sales` (
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
  `DirectDebit5` varchar(512) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

##GNAF
```sql
--
-- Table structure for table `ADDRESS_ALIAS`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_ALIAS` (
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
```
```sql
--
-- Table structure for table `ADDRESS_ALIAS_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_ALIAS_TYPE_AUT` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `ADDRESS_DETAIL`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_DETAIL` (
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
```
```sql
--
-- Table structure for table `ADDRESS_MESH_BLOCK`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_MESH_BLOCK` (
  `address_mesh_block_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `address_detail_pid` varchar(15) NOT NULL,
  `mesh_block_pid` varchar(15) NOT NULL,
  PRIMARY KEY (`address_mesh_block_pid`),
  KEY `ADDRESS_MESH_BLOCK_FK1` (`address_detail_pid`),
  KEY `ADDRESS_MESH_BLOCK_FK2` (`mesh_block_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `ADDRESS_MESH_BLOCK_2011`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_MESH_BLOCK_2011` (
  `address_mesh_block_2011_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `address_detail_pid` varchar(15) NOT NULL,
  `mb_2011_pid` varchar(15) NOT NULL,
  PRIMARY KEY (`address_mesh_block_2011_pid`),
  KEY `ADDRESS_MESH_BLOCK_2011_FK1` (`address_detail_pid`),
  KEY `ADDRESS_MESH_BLOCK_2011_FK2` (`mb_2011_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `ADDRESS_SITE`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_SITE` (
  `address_site_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `address_type` varchar(8) DEFAULT NULL,
  `address_site_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`address_site_pid`),
  KEY `ADDRESS_SITE_FK1` (`address_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `ADDRESS_SITE_GEOCODE`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_SITE_GEOCODE` (
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
```
```sql
--
-- Table structure for table `ADDRESS_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `ADDRESS_TYPE_AUT` (
  `code` varchar(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `FLAT_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `FLAT_TYPE_AUT` (
  `code` varchar(7) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `GEOCODED_LEVEL_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `GEOCODED_LEVEL_TYPE_AUT` (
  `code` decimal(2,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `GEOCODE_RELIABILITY_AUT`
--

CREATE TABLE IF NOT EXISTS `GEOCODE_RELIABILITY_AUT` (
  `code` decimal(1,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `GEOCODE_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `GEOCODE_TYPE_AUT` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `GNAF_REJECT_AUT`
--

CREATE TABLE IF NOT EXISTS `GNAF_REJECT_AUT` (
  `code` decimal(3,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `terminal` char(1) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `LEVEL_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `LEVEL_TYPE_AUT` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `LOCALITY`
--

CREATE TABLE IF NOT EXISTS `LOCALITY` (
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
```
```sql
--
-- Table structure for table `LOCALITY_ALIAS`
--

CREATE TABLE IF NOT EXISTS `LOCALITY_ALIAS` (
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
```
```sql
--
-- Table structure for table `LOCALITY_ALIAS_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `LOCALITY_ALIAS_TYPE_AUT` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `LOCALITY_CLASS_AUT`
--

CREATE TABLE IF NOT EXISTS `LOCALITY_CLASS_AUT` (
  `code` char(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `LOCALITY_NEIGHBOUR`
--

CREATE TABLE IF NOT EXISTS `LOCALITY_NEIGHBOUR` (
  `locality_neighbour_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `locality_pid` varchar(15) NOT NULL,
  `neighbour_locality_pid` varchar(15) NOT NULL,
  PRIMARY KEY (`locality_neighbour_pid`),
  KEY `LOCALITY_NEIGHBOUR_FK1` (`locality_pid`),
  KEY `LOCALITY_NEIGHBOUR_FK2` (`neighbour_locality_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `LOCALITY_POINT`
--

CREATE TABLE IF NOT EXISTS `LOCALITY_POINT` (
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
```
```sql
--
-- Table structure for table `MB_2011`
--

CREATE TABLE IF NOT EXISTS `MB_2011` (
  `mb_2011_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `mb_2011_code` varchar(15) NOT NULL,
  PRIMARY KEY (`mb_2011_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `MESH_BLOCK`
--

CREATE TABLE IF NOT EXISTS `MESH_BLOCK` (
  `mesh_block_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `mesh_block_code` decimal(13,0) NOT NULL,
  PRIMARY KEY (`mesh_block_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `PRIMARY_SECONDARY`
--

CREATE TABLE IF NOT EXISTS `PRIMARY_SECONDARY` (
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
```
```sql
--
-- Table structure for table `PS_JOIN_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `PS_JOIN_TYPE_AUT` (
  `code` decimal(2,0) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `STATE`
--

CREATE TABLE IF NOT EXISTS `STATE` (
  `state_pid` varchar(15) NOT NULL,
  `date_created` date NOT NULL,
  `date_retired` date DEFAULT NULL,
  `state_name` varchar(50) NOT NULL,
  `state_abbreviation` varchar(3) NOT NULL,
  PRIMARY KEY (`state_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `STREET_CLASS_AUT`
--

CREATE TABLE IF NOT EXISTS `STREET_CLASS_AUT` (
  `code` char(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `STREET_LOCALITY`
--

CREATE TABLE IF NOT EXISTS `STREET_LOCALITY` (
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
```
```sql
--
-- Table structure for table `STREET_LOCALITY_ALIAS`
--

CREATE TABLE IF NOT EXISTS `STREET_LOCALITY_ALIAS` (
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
```
```sql
--
-- Table structure for table `STREET_LOCALITY_ALIAS_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `STREET_LOCALITY_ALIAS_TYPE_AUT` (
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `STREET_LOCALITY_POINT`
--

CREATE TABLE IF NOT EXISTS `STREET_LOCALITY_POINT` (
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
```
```sql
--
-- Table structure for table `STREET_SUFFIX_AUT`
--

CREATE TABLE IF NOT EXISTS `STREET_SUFFIX_AUT` (
  `code` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `STREET_TYPE_AUT`
--

CREATE TABLE IF NOT EXISTS `STREET_TYPE_AUT` (
  `code` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

##Leads
```sql
--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group` varchar(16) NOT NULL,
  `centres` varchar(32) NOT NULL,
  PRIMARY KEY (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `leads`
--

CREATE TABLE IF NOT EXISTS `leads` (
  `cli` int(10) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `packet_expiry` date NOT NULL,
  PRIMARY KEY (`cli`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (cli)
PARTITIONS 30 */;
```
```sql
--
-- Table structure for table `leads_time`
--

CREATE TABLE IF NOT EXISTS `leads_time` (
  `centre` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`centre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `log_leads`
--

CREATE TABLE IF NOT EXISTS `log_leads` (
  `cli` int(10) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `packet_expiry` date NOT NULL,
  KEY `cli` (`cli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
/*!50100 PARTITION BY KEY (cli)
PARTITIONS 30 */;
```

##PAF
```sql
--
-- Table structure for table `pcode`
--

CREATE TABLE IF NOT EXISTS `pcode` (
  `postcode` int(4) NOT NULL,
  `locality` varchar(256) NOT NULL,
  `state` varchar(3) NOT NULL,
  KEY `postcode` (`postcode`),
  KEY `locality` (`locality`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

##Terralinks
```sql
--
-- Table structure for table `po`
--

CREATE TABLE IF NOT EXISTS `po` (
  `suburb` varchar(256) NOT NULL,
  `city_town` varchar(256) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  PRIMARY KEY (`suburb`,`city_town`,`postcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

##VeriCon
```sql
--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
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
```
```sql
--
-- Table structure for table `allowedip`
--

CREATE TABLE IF NOT EXISTS `allowedip` (
  `IP` varchar(50) NOT NULL,
  `status` int(25) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` varchar(8) NOT NULL,
  PRIMARY KEY (`IP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display` varchar(25) NOT NULL,
  `date` varchar(25) NOT NULL,
  `poster` varchar(300) NOT NULL,
  `department` varchar(300) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
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
```
```sql
--
-- Table structure for table `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` varchar(16) NOT NULL,
  `group` varchar(64) NOT NULL,
  `campaign` varchar(128) NOT NULL,
  `number` varchar(32) NOT NULL,
  `website` varchar(64) NOT NULL,
  `country` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `centres`
--

CREATE TABLE IF NOT EXISTS `centres` (
  `centre` varchar(32) NOT NULL,
  `campaign` varchar(512) NOT NULL,
  `type` varchar(16) NOT NULL,
  `status` varchar(16) NOT NULL,
  `leads` int(11) NOT NULL,
  PRIMARY KEY (`centre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `currentuser`
--

CREATE TABLE IF NOT EXISTS `currentuser` (
  `hash` varchar(300) NOT NULL,
  `user` varchar(300) NOT NULL,
  `current_page` varchar(300) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`hash`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
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
  `credit` int(11) NOT NULL,
  `ongoing_credit` int(11) NOT NULL,
  `onceoff_credit` int(11) NOT NULL,
  `payway` varchar(16) NOT NULL,
  `dd_type` varchar(16) NOT NULL,
  `billing_comments` text NOT NULL,
  `other_comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `customers_log`
--

CREATE TABLE IF NOT EXISTS `customers_log` (
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
  `credit` int(11) NOT NULL,
  `ongoing_credit` int(11) NOT NULL,
  `onceoff_credit` int(11) NOT NULL,
  `payway` varchar(16) NOT NULL,
  `dd_type` varchar(16) NOT NULL,
  `billing_comments` text NOT NULL,
  `other_comments` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `customers_notes`
--

CREATE TABLE IF NOT EXISTS `customers_notes` (
  `id` varchar(16) NOT NULL,
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(32) NOT NULL,
  `note` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `user` varchar(8) NOT NULL,
  `id` varchar(32) NOT NULL,
  PRIMARY KEY (`user`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` varchar(16) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `international`
--

CREATE TABLE IF NOT EXISTS `international` (
  `country` varchar(50) NOT NULL,
  `rate` varchar(50) NOT NULL,
  PRIMARY KEY (`country`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `log_access`
--

CREATE TABLE IF NOT EXISTS `log_access` (
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `page` varchar(300) NOT NULL,
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `log_gnaf`
--

CREATE TABLE IF NOT EXISTS `log_gnaf` (
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` varchar(8) NOT NULL,
  `input` varchar(512) NOT NULL,
  `result` int(1) NOT NULL,
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `log_login`
--

CREATE TABLE IF NOT EXISTS `log_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(32) NOT NULL,
  `user` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `log_unauthorised`
--

CREATE TABLE IF NOT EXISTS `log_unauthorised` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(32) NOT NULL,
  `user` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `operations`
--

CREATE TABLE IF NOT EXISTS `operations` (
  `user` varchar(16) NOT NULL,
  `centres` text NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
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
```
```sql
--
-- Table structure for table `packages_log`
--

CREATE TABLE IF NOT EXISTS `packages_log` (
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
```
```sql
--
-- Table structure for table `plan_matrix`
--

CREATE TABLE IF NOT EXISTS `plan_matrix` (
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
```
```sql
--
-- Table structure for table `plan_rates`
--

CREATE TABLE IF NOT EXISTS `plan_rates` (
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
```
```sql
--
-- Table structure for table `portals`
--

CREATE TABLE IF NOT EXISTS `portals` (
  `id` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `portals_access`
--

CREATE TABLE IF NOT EXISTS `portals_access` (
  `user` varchar(16) NOT NULL,
  `pages` text NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `portals_pages`
--

CREATE TABLE IF NOT EXISTS `portals_pages` (
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
```
```sql
--
-- Table structure for table `portals_template`
--

CREATE TABLE IF NOT EXISTS `portals_template` (
  `user` varchar(16) NOT NULL,
  `type` varchar(16) NOT NULL,
  `pages` text NOT NULL,
  PRIMARY KEY (`user`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `providers`
--

CREATE TABLE IF NOT EXISTS `providers` (
  `name` varchar(64) NOT NULL,
  `value` varchar(64) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `qa_customers`
--

CREATE TABLE IF NOT EXISTS `qa_customers` (
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
```
```sql
--
-- Table structure for table `recordings`
--

CREATE TABLE IF NOT EXISTS `recordings` (
  `id` varchar(16) NOT NULL,
  `sale_id` varchar(16) NOT NULL,
  `type` varchar(64) NOT NULL,
  `name` varchar(512) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `sales_customers`
--

CREATE TABLE IF NOT EXISTS `sales_customers` (
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
  `ongoing_credit` int(11) NOT NULL,
  `onceoff_credit` int(11) NOT NULL,
  `payway` varchar(16) NOT NULL,
  `dd_type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `centre` (`centre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `sales_customers_temp`
--

CREATE TABLE IF NOT EXISTS `sales_customers_temp` (
  `lead_id` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `agent` varchar(10) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `campaign` varchar(300) NOT NULL,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`lead_id`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `sales_packages`
--

CREATE TABLE IF NOT EXISTS `sales_packages` (
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
```
```sql
--
-- Table structure for table `sales_packages_temp`
--

CREATE TABLE IF NOT EXISTS `sales_packages_temp` (
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
```
```sql
--
-- Table structure for table `script_order`
--

CREATE TABLE IF NOT EXISTS `script_order` (
  `id` varchar(16) NOT NULL,
  `campaign` varchar(16) NOT NULL,
  `type` varchar(32) NOT NULL,
  `page` int(11) NOT NULL,
  `question` int(11) NOT NULL,
  `back` varchar(1) NOT NULL,
  `next` varchar(1) NOT NULL,
  PRIMARY KEY (`id`,`campaign`,`type`,`page`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `script_plans`
--

CREATE TABLE IF NOT EXISTS `script_plans` (
  `id` varchar(16) NOT NULL,
  `campaign` varchar(32) NOT NULL,
  `script` text NOT NULL,
  PRIMARY KEY (`id`,`campaign`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `script_questions`
--

CREATE TABLE IF NOT EXISTS `script_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `question` text NOT NULL,
  `input` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `sct_dnc`
--

CREATE TABLE IF NOT EXISTS `sct_dnc` (
  `cli` varchar(16) NOT NULL,
  PRIMARY KEY (`cli`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `timesheet`
--

CREATE TABLE IF NOT EXISTS `timesheet` (
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
```
```sql
--
-- Table structure for table `timesheet_designation`
--

CREATE TABLE IF NOT EXISTS `timesheet_designation` (
  `user` varchar(16) NOT NULL,
  `designation` varchar(64) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `timesheet_mcost`
--

CREATE TABLE IF NOT EXISTS `timesheet_mcost` (
  `centre` varchar(16) NOT NULL,
  `week` varchar(16) NOT NULL,
  `m_cost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`centre`,`week`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `timesheet_other`
--

CREATE TABLE IF NOT EXISTS `timesheet_other` (
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
```
```sql
--
-- Table structure for table `timesheet_rate`
--

CREATE TABLE IF NOT EXISTS `timesheet_rate` (
  `user` varchar(16) NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `timesheet_tiered`
--

CREATE TABLE IF NOT EXISTS `timesheet_tiered` (
  `designation` varchar(32) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  KEY `designation` (`designation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `tpv_notes`
--

CREATE TABLE IF NOT EXISTS `tpv_notes` (
  `id` varchar(25) NOT NULL,
  `status` varchar(25) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lead_id` varchar(10) NOT NULL,
  `centre` varchar(8) NOT NULL,
  `verifier` varchar(25) NOT NULL,
  `note` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `tutorials`
--

CREATE TABLE IF NOT EXISTS `tutorials` (
  `id` varchar(16) NOT NULL,
  `portal` varchar(16) NOT NULL,
  `name` varchar(64) NOT NULL,
  `link` varchar(512) NOT NULL,
  `thumbnail` mediumblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(25) NOT NULL,
  `poster` varchar(300) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `verification_lock`
--

CREATE TABLE IF NOT EXISTS `verification_lock` (
  `id` varchar(16) NOT NULL,
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `welcome`
--

CREATE TABLE IF NOT EXISTS `welcome` (
  `id` varchar(16) NOT NULL,
  `status` varchar(64) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` varchar(8) NOT NULL,
  `dd` int(1) NOT NULL,
  `cancellation_reason` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `welcome_cb`
--

CREATE TABLE IF NOT EXISTS `welcome_cb` (
  `id` varchar(16) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```
```sql
--
-- Table structure for table `welcome_lock`
--

CREATE TABLE IF NOT EXISTS `welcome_lock` (
  `id` varchar(16) NOT NULL,
  `user` varchar(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;
```