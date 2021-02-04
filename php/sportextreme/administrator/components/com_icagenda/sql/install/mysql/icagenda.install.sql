--
-- iCagenda: Install Database `icagenda`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda`
--

CREATE TABLE IF NOT EXISTS `#__icagenda` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) DEFAULT NULL,
  `releasedate` varchar(255) DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__icagenda`
--

INSERT IGNORE INTO `#__icagenda` (`id`, `version`, `releasedate`, `params`) VALUES
(3,'3.7.10','2019-08-31','');

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_category`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `desc` text(65535) NOT NULL,
  `image` varchar(255) NOT NULL,
  `groups` varchar(255) NOT NULL DEFAULT '',
  `language` varchar(10) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_events`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `approval` int(11) NOT NULL DEFAULT '0',
  `site_itemid` int(10) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `language` varchar(10) NOT NULL DEFAULT '*',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL,
  `created_by_email` varchar(100) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `catid` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `displaytime` int(10) NOT NULL DEFAULT '1',
  `weekdays` varchar(255) NOT NULL,
  `daystime` varchar(255) NOT NULL,
  `startdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `period` text(65535) NOT NULL,
  `dates` text(65535) NOT NULL,
  `next` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `coordinate` varchar(255) NOT NULL,
  `lat` float( 20, 16 ) NOT NULL,
  `lng` FLOAT( 20, 16 ) NOT NULL,
  `shortdesc` text NOT NULL,
  `desc` text(65535) NOT NULL ,
  `metadesc` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_approval` (`approval`),
  KEY `idx_language` (`language`),
  KEY `idx_catid` (`catid`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_registration`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_registration` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `eventid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `date` text(65535) NOT NULL,
  `period` tinyint(1) NOT NULL DEFAULT '0',
  `people` int(2) NOT NULL,
  `notes` text(65535) NOT NULL ,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `params` text NOT NULL ,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_userid` (`userid`),
  KEY `idx_eventid` (`eventid`),
  KEY `idx_status` (`status`),
  KEY `idx_created_by` (`created_by`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_customfields`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_customfields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `parent_form` int(11) NOT NULL DEFAULT '0',
  `groups` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL,
  `options` mediumtext,
  `default` varchar(255) NOT NULL,
  `required` tinyint(3) NOT NULL DEFAULT '0',
  `language` varchar(10) NOT NULL DEFAULT '*',
  `params` mediumtext,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_parent_form` (`parent_form`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_customfields_data`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_customfields_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `slug` varchar(255) NOT NULL,
  `parent_form` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_parent_form` (`parent_form`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_language` (`language`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_feature`
--

CREATE TABLE IF NOT EXISTS  `#__icagenda_feature` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `desc` mediumtext NOT NULL,
  `icon` varchar(255) NOT NULL,
  `icon_alt` varchar(255) NOT NULL,
  `show_filter` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_feature_xref`
--

CREATE TABLE IF NOT EXISTS  `#__icagenda_feature_xref` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_event_id` (`event_id`),
  KEY `idx_feature_id` (`feature_id`)
) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_filters`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_filters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT '',
  `filter` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `option` varchar(255) NOT NULL DEFAULT '',
  `selected` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_user_actions`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_user_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0,
  `user_action` varchar(255) NOT NULL,
  `parent_form` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `action_subject` varchar(100) NOT NULL DEFAULT '',
  `action_body` text NOT NULL,
  `user_ip` varchar(100) NOT NULL DEFAULT '',
  `user_agent` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_parent_form` (`parent_form`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_state` (`state`)
) DEFAULT CHARSET=utf8;
