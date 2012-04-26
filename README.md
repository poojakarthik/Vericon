##SQL
```sql
--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `id` varchar(15) NOT NULL,
  `street` varchar(512) NOT NULL,
  `suburb` varchar(64) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `allowedip`
--

CREATE TABLE IF NOT EXISTS `allowedip` (
  `IP` varchar(50) NOT NULL,
  `status` int(25) NOT NULL,
  `Description` varchar(500) NOT NULL
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
```

```sql
--
-- Table structure for table `archive_mcrm_customers`
--

CREATE TABLE IF NOT EXISTS `archive_mcrm_customers` (
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
-- Table structure for table `archive_mcrm_packages`
--

CREATE TABLE IF NOT EXISTS `archive_mcrm_packages` (
  `sid` varchar(32) NOT NULL,
  `line` varchar(32) NOT NULL,
  `plan` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `archive_mytpv_sales`
--

CREATE TABLE IF NOT EXISTS `archive_mytpv_sales` (
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

```sql
--
-- Table structure for table `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
  `user` varchar(25) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `type` varchar(300) NOT NULL,
  `access` varchar(25) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `status` varchar(25) NOT NULL,
  `first` varchar(300) NOT NULL,
  `last` varchar(300) NOT NULL,
  `alias` varchar(300) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` varchar(16) NOT NULL,
  `campaign` varchar(128) NOT NULL,
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
-- Table structure for table `csform`
--

CREATE TABLE IF NOT EXISTS `csform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(300) NOT NULL,
  `date` varchar(300) NOT NULL,
  `time` varchar(300) NOT NULL,
  `staff_name` varchar(300) NOT NULL,
  `campaign` varchar(300) NOT NULL,
  `customer_name` varchar(300) NOT NULL,
  `customer_contact` varchar(300) NOT NULL,
  `account_number` varchar(300) NOT NULL,
  `type` varchar(300) NOT NULL,
  `call` varchar(300) NOT NULL,
  `comments` longtext NOT NULL,
  `act_date` varchar(300) NOT NULL,
  `act_time` varchar(300) NOT NULL,
  `agent_name` varchar(300) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
```

```sql
--
-- Table structure for table `currentuser`
--

CREATE TABLE IF NOT EXISTS `currentuser` (
  `hash` varchar(300) NOT NULL,
  `user` varchar(300) NOT NULL,
  `type` varchar(300) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` varchar(15) NOT NULL,
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
  `mobile` varchar(10) NOT NULL,
  `billing` varchar(25) NOT NULL,
  `welcome` varchar(25) NOT NULL,
  `promotions` varchar(8) NOT NULL,
  `physical` varchar(300) NOT NULL,
  `postal` varchar(300) NOT NULL,
  `id_type` varchar(25) NOT NULL,
  `id_num` varchar(25) NOT NULL,
  `abn` varchar(25) NOT NULL,
  `position` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `gnaf`
--

CREATE TABLE IF NOT EXISTS `gnaf` (
  `ADDRESS_DETAIL_PID` varchar(15) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `ADDRESS_SITE_PID` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `ALIAS_PRINCIPAL` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `BUILDING_NAME` varchar(4) CHARACTER SET latin1 DEFAULT NULL,
  `CONFIDENCE` int(2) DEFAULT NULL,
  `FLAT_NUMBER` int(5) DEFAULT NULL,
  `FLAT_NUMBER_PREFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `FLAT_NUMBER_SUFFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `FLAT_TYPE_CODE` varchar(7) CHARACTER SET latin1 DEFAULT NULL,
  `GNAF_PROPERTY_PID` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `LEGAL_PARCEL_ID` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `LEVEL_GEOCODED_CODE` int(2) DEFAULT NULL,
  `LEVEL_NUMBER` int(3) DEFAULT NULL,
  `LEVEL_NUMBER_PREFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `LEVEL_NUMBER_SUFFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `LEVEL_TYPE_CODE` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `LOCALITY_PID` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `LOCATION_DESCRIPTION` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `LOT_NUMBER` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
  `LOT_NUMBER_PREFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `LOT_NUMBER_SUFFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `NUMBER_FIRST` int(6) DEFAULT NULL,
  `NUMBER_FIRST_PREFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `NUMBER_FIRST_SUFFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `NUMBER_LAST` int(10) DEFAULT NULL,
  `NUMBER_LAST_PREFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `NUMBER_LAST_SUFFIX` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `POSTCODE` varchar(4) CHARACTER SET latin1 DEFAULT NULL,
  `PRIMARY_SECONDARY` varchar(1) CHARACTER SET latin1 DEFAULT NULL,
  `PRIVATE_STREET` varchar(75) CHARACTER SET latin1 DEFAULT NULL,
  `PROPERTY_PID` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `STREET_LOCALITY_PID` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `DATE_CREATED` datetime DEFAULT NULL,
  `DATE_LAST_MODIFIED` datetime DEFAULT NULL,
  `DATE_RETIRED` datetime DEFAULT NULL,
  `STREET_NAME` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `STREET_TYPE_CODE` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `STREET_SUFFIX_CODE` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `LOCALITY_NAME` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `STATE` varchar(3) CHARACTER SET latin1 DEFAULT NULL,
  `LATITUDE` decimal(11,8) DEFAULT NULL,
  `LONGITUDE` decimal(11,8) DEFAULT NULL,
  `RELIABILITY_CODE` int(1) DEFAULT NULL,
  PRIMARY KEY (`ADDRESS_DETAIL_PID`),
  KEY `POSTCODE` (`POSTCODE`),
  KEY `POSTCODE_2` (`POSTCODE`,`LOCALITY_NAME`),
  KEY `POSTCODE_3` (`POSTCODE`,`STREET_NAME`,`LOCALITY_NAME`),
  KEY `FLAT_NUMBER` (`FLAT_NUMBER`,`NUMBER_FIRST`,`NUMBER_LAST`,`POSTCODE`,`STREET_NAME`,`STREET_TYPE_CODE`,`LOCALITY_NAME`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

```sql
--
-- Table structure for table `international`
--

CREATE TABLE IF NOT EXISTS `international` (
  `country` varchar(50) NOT NULL,
  `rate` varchar(50) NOT NULL
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
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cli`),
  KEY `centre` (`centre`),
  KEY `cli` (`cli`,`centre`),
  KEY `centre_2` (`centre`,`expiry_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `leads_group`
--

CREATE TABLE IF NOT EXISTS `leads_group` (
  `group` varchar(16) NOT NULL,
  `centres` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `log_al`
--

CREATE TABLE IF NOT EXISTS `log_al` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(32) NOT NULL,
  `user` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4110 ;
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
  KEY `cli` (`cli`),
  KEY `cli_2` (`cli`,`centre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22355 ;
```

```sql
--
-- Table structure for table `log_sales`
--

CREATE TABLE IF NOT EXISTS `log_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` varchar(32) NOT NULL,
  `lead_id` varchar(32) NOT NULL,
  `reason` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;
```

```sql
--
-- Table structure for table `log_tpv_inbound`
--

CREATE TABLE IF NOT EXISTS `log_tpv_inbound` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `entered_id` varchar(32) NOT NULL,
  `actual_id` varchar(32) NOT NULL,
  `centre` varchar(32) NOT NULL,
  `status` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1110 ;
```

```sql
--
-- Table structure for table `operations`
--

CREATE TABLE IF NOT EXISTS `operations` (
  `user` varchar(16) NOT NULL,
  `centres` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` varchar(16) NOT NULL,
  `cli` varchar(16) NOT NULL,
  `plan` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `plan_matrix`
--

CREATE TABLE IF NOT EXISTS `plan_matrix` (
  `id` varchar(18) NOT NULL,
  `status` varchar(16) NOT NULL,
  `rating` varchar(16) NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(32) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`)
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
  `name` varchar(512) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `reworks`
--

CREATE TABLE IF NOT EXISTS `reworks` (
  `id` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `centre` varchar(8) NOT NULL,
  `agent` varchar(8) NOT NULL,
  `reason` varchar(512) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `roster`
--

CREATE TABLE IF NOT EXISTS `roster` (
  `date` date NOT NULL,
  `agent` varchar(16) NOT NULL,
  `centre` varchar(16) NOT NULL,
  `av_start` time NOT NULL,
  `av_end` time NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `na` varchar(8) NOT NULL
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
  `mobile` varchar(10) NOT NULL,
  `billing` varchar(25) NOT NULL,
  `welcome` varchar(25) NOT NULL,
  `promotions` varchar(8) NOT NULL,
  `physical` varchar(300) NOT NULL,
  `postal` varchar(300) NOT NULL,
  `id_type` varchar(25) NOT NULL,
  `id_num` varchar(25) NOT NULL,
  `abn` varchar(25) NOT NULL,
  `position` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `sales_packages`
--

CREATE TABLE IF NOT EXISTS `sales_packages` (
  `sid` varchar(10) NOT NULL,
  `cli` varchar(10) NOT NULL,
  `plan` varchar(300) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `plan` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
-- Table structure for table `sf_plan_code`
--

CREATE TABLE IF NOT EXISTS `sf_plan_code` (
  `id` varchar(16) NOT NULL,
  `plan` varchar(64) NOT NULL,
  `type` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `timesheet`
--

CREATE TABLE IF NOT EXISTS `timesheet` (
  `user` varchar(16) NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `hours` decimal(10,2) NOT NULL,
  `bonus` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `tmp_dsr`
--

CREATE TABLE IF NOT EXISTS `tmp_dsr` (
  `DSR_Number` varchar(8) NOT NULL,
  `Account_ID` varchar(16) NOT NULL,
  `Account_Number` varchar(16) NOT NULL,
  `Recording` varchar(16) NOT NULL,
  `Sale_ID` varchar(16) NOT NULL,
  `Account_Status` varchar(32) NOT NULL,
  `ADSL_Status` varchar(32) NOT NULL,
  `Wireless_Status` varchar(32) NOT NULL,
  `Agent` varchar(64) NOT NULL,
  `Centre` varchar(8) NOT NULL,
  `Date_of_Sale` date NOT NULL DEFAULT '0000-00-00',
  `Whoisit` varchar(8) NOT NULL,
  `Telco_Name` varchar(32) NOT NULL,
  `Rating` varchar(64) NOT NULL,
  `Industry` varchar(8) NOT NULL,
  `Title` varchar(8) NOT NULL,
  `First_Name` varchar(64) NOT NULL,
  `Middle_Name` varchar(64) NOT NULL,
  `Last_Name` varchar(64) NOT NULL,
  `Position` varchar(64) NOT NULL,
  `DOB` date NOT NULL DEFAULT '0000-00-00',
  `Account_Name` varchar(128) NOT NULL,
  `ABN` varchar(16) NOT NULL,
  `CLI_1` varchar(16) NOT NULL,
  `Plan_1` varchar(16) NOT NULL,
  `CLI_2` varchar(16) NOT NULL,
  `Plan_2` varchar(16) NOT NULL,
  `CLI_3` varchar(16) NOT NULL,
  `Plan_3` varchar(16) NOT NULL,
  `CLI_4` varchar(16) NOT NULL,
  `Plan_4` varchar(16) NOT NULL,
  `CLI_5` varchar(16) NOT NULL,
  `Plan_5` varchar(16) NOT NULL,
  `CLI_6` varchar(16) NOT NULL,
  `Plan_6` varchar(16) NOT NULL,
  `CLI_7` varchar(16) NOT NULL,
  `Plan_7` varchar(16) NOT NULL,
  `CLI_8` varchar(16) NOT NULL,
  `Plan_8` varchar(16) NOT NULL,
  `CLI_9` varchar(16) NOT NULL,
  `Plan_9` varchar(16) NOT NULL,
  `CLI_10` varchar(16) NOT NULL,
  `Plan_10` varchar(16) NOT NULL,
  `MSN_1` varchar(16) NOT NULL,
  `Mplan_1` varchar(16) NOT NULL,
  `MSN_2` varchar(16) NOT NULL,
  `Mplan_2` varchar(16) NOT NULL,
  `MSN_3` varchar(16) NOT NULL,
  `Mplan_3` varchar(16) NOT NULL,
  `WMSN_1` varchar(16) NOT NULL,
  `Wplan_1` varchar(16) NOT NULL,
  `WMSN_2` varchar(16) NOT NULL,
  `Wplan_2` varchar(16) NOT NULL,
  `ACLI` varchar(16) NOT NULL,
  `APLAN` varchar(16) NOT NULL,
  `Bundle` varchar(16) NOT NULL,
  `Building_Type` varchar(16) NOT NULL,
  `Building_Number` varchar(8) NOT NULL,
  `Building_Number_Suffix` varchar(8) NOT NULL,
  `Building_Name` varchar(64) NOT NULL,
  `Street_Number_Start` varchar(8) NOT NULL,
  `Street_Number_End` varchar(8) NOT NULL,
  `Street_Name` varchar(64) NOT NULL,
  `Street_Type` varchar(32) NOT NULL,
  `Suburb` varchar(64) NOT NULL,
  `State` varchar(8) NOT NULL,
  `Post_Code` varchar(8) NOT NULL,
  `PO_Box_Number_Only` varchar(8) NOT NULL,
  `Mail_Street_Number` varchar(8) NOT NULL,
  `Mail_Street` varchar(64) NOT NULL,
  `Mail_Suburb` varchar(64) NOT NULL,
  `Mail_State` varchar(8) NOT NULL,
  `Mail_Post_Code` varchar(8) NOT NULL,
  `Contract_Months` varchar(8) NOT NULL,
  `Credit_Offered` varchar(16) NOT NULL,
  `Welcome_Email` varchar(8) NOT NULL,
  `PayWay` varchar(16) NOT NULL,
  `Direct_Debit` varchar(16) NOT NULL,
  `EBill` varchar(8) NOT NULL,
  `Sale_Type` varchar(8) NOT NULL,
  `Mobile_Contact` varchar(16) NOT NULL,
  `Home_Number` varchar(16) NOT NULL,
  `Current_Provider` varchar(16) NOT NULL,
  `Email_Address` varchar(128) NOT NULL,
  `Additional_Information` varchar(512) NOT NULL,
  `Billing_Comment` varchar(512) NOT NULL,
  `Provisioning_Comment` varchar(512) NOT NULL,
  `Mobile_Comment` varchar(512) NOT NULL,
  `Other_Comment` varchar(512) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `tpv_lock`
--

CREATE TABLE IF NOT EXISTS `tpv_lock` (
  `user` varchar(16) NOT NULL,
  `id` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `note` text NOT NULL
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
```

```sql
--
-- Table structure for table `vicidial_assign`
--

CREATE TABLE IF NOT EXISTS `vicidial_assign` (
  `number` varchar(16) NOT NULL,
  `id` varchar(16) NOT NULL,
  `centre` varchar(8) NOT NULL,
  `agent` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `vicidial_live`
--

CREATE TABLE IF NOT EXISTS `vicidial_live` (
  `sale_id` varchar(32) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `vicidial_pool`
--

CREATE TABLE IF NOT EXISTS `vicidial_pool` (
  `number` varchar(16) NOT NULL,
  `status` varchar(8) NOT NULL,
  `indial` varchar(16) NOT NULL,
  `centre` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```