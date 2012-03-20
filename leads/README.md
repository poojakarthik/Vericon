#LEADS MANAGEMENT

![Flow](https://github.com/SBTelecom/VeriCon/raw/leads/leads/docs/flow.png)


##SQL
```sql
--
-- Table structure for table `leads`
--

CREATE TABLE IF NOT EXISTS `leads` (
  `sid` varchar(10) NOT NULL,
  `title` varchar(25) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `middlename` varchar(300) NOT NULL,
  `lastname` varchar(300) NOT NULL,
  `first` varchar(32) NOT NULL,
  `last` varchar(32) NOT NULL,
  `bus_name` varchar(256) NOT NULL,
  `street` varchar(256) NOT NULL,
  `suburb` varchar(128) NOT NULL,
  `state` varchar(32) NOT NULL,
  `postcode` varchar(16) NOT NULL,
  `packet_name` varchar(128) NOT NULL,
  `centre` varchar(25) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `leads_request`
--

CREATE TABLE IF NOT EXISTS `leads_request` (
  `id` int(11) NOT NULL,
  `status` varchar(25) NOT NULL,
  `cc` int(11) NOT NULL,
  `req_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `action_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `req` int(11) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

```sql
--
-- Table structure for table `leads_cc_log`
--

CREATE TABLE IF NOT EXISTS `leads_cc_log` (
  `cc` varchar(20) NOT NULL,
  `log_ts` timestamp default NULL,
  `uniqueid` varchar(20) NOT NULL,
  `lead_id` int(9) unsigned NOT NULL,
  `list_id` bigint(14) unsigned default NULL,
  `campaign_id` varchar(8) default NULL,
  `call_date` datetime default NULL,
  `start_epoch` int(10) unsigned default NULL,
  `end_epoch` int(10) unsigned default NULL,
  `length_in_sec` int(10) default NULL,
  `status` varchar(6) default NULL,
  `phone_code` varchar(10) default NULL,
  `phone_number` varchar(18) default NULL,
  `user` varchar(20) default NULL,
  `comments` varchar(255) default NULL,
  `processed` enum('Y','N') default NULL,
  `user_group` varchar(20) default NULL,
  `term_reason` enum('CALLER','AGENT','QUEUETIMEOUT','ABANDON','AFTERHOURS','NONE') default 'NONE',
  `alt_dial` varchar(6) default 'NONE',
  KEY `lead_id` (`lead_id`),
  KEY `call_date` (`call_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

