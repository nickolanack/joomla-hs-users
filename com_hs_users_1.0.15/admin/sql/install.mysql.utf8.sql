--
-- Table structure for table `#__users_authentications`
--

CREATE TABLE IF NOT EXISTS `#__users_authentications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'refer to users.id',
  `provider` varchar(100) NOT NULL,
  `provider_uid` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `display_name` varchar(150) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `profile_url` varchar(300) NOT NULL,
  `photo_url` varchar(300) NOT NULL,
  `website_url` varchar(300) NOT NULL,
  `description` varchar(300) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `language` varchar(10) NOT NULL,
  `age` tinyint(3) unsigned NOT NULL,
  `birth_day` tinyint(3) unsigned NOT NULL,
  `birth_month` tinyint(3) unsigned NOT NULL,
  `birth_year` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `country` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_uid` (`provider_uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `#__users_extended`
--

CREATE TABLE IF NOT EXISTS `#__users_extended` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `image_folder` varchar(20) NOT NULL,
  `image_name` varchar(200) NOT NULL,
  `image_raw_name` varchar(50) NOT NULL,
  `external` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
