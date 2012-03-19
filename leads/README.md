#LEADS MANAGEMENT

![Flow](https://github.com/SBTelecom/VeriCon/raw/leads/leads/docs/flow.png)

##TODO


##NOTES
```
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
