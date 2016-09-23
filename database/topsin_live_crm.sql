-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2015 at 05:55 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `topsin_live_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'Enter name',
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `sha_key` varchar(50) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_category_master`
--

CREATE TABLE IF NOT EXISTS `blog_category_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) unsigned NOT NULL COMMENT 'child_website_domain_master',
  `category_name` varchar(255) NOT NULL,
  `copy_id` int(10) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-Publish, 0-Unpublish',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_post`
--

CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) unsigned NOT NULL COMMENT 'child_website_domain_master',
  `post_image` varchar(255) NOT NULL,
  `post_title` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `post_name` varchar(200) NOT NULL,
  `post_content` text NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `post_date` datetime NOT NULL,
  `comment_count` int(11) NOT NULL,
  `include_on_home_page` tinyint(4) NOT NULL,
  `meta_data_robot` tinyint(4) NOT NULL,
  `copy_id` int(10) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `post_status` tinytext NOT NULL COMMENT 'Default - 0. 1-Publish, 0-Unpublish',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_post_category_trans`
--

CREATE TABLE IF NOT EXISTS `blog_post_category_trans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_post_comments`
--

CREATE TABLE IF NOT EXISTS `blog_post_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL COMMENT 'From blog_post',
  `comment_author` varchar(255) NOT NULL,
  `comment_author_email` varchar(255) NOT NULL,
  `comment_author_url` varchar(255) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_content` text NOT NULL,
  `comment_parent` int(10) unsigned NOT NULL COMMENT 'From blog_post_comments',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From lead_user',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From lead_user',
  `comment_status` tinyint(1) NOT NULL COMMENT 'Default - 1. 1-Publish, 0-Unpublish',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bomb_template_master`
--

CREATE TABLE IF NOT EXISTS `bomb_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `template_subject` varchar(250) NOT NULL,
  `email_message` text NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `thumb_url` varchar(255) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_master`
--

CREATE TABLE IF NOT EXISTS `calendar_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `task_id` int(11) NOT NULL,
  `event_inserted_type` int(1) NOT NULL COMMENT '1-Calendar, 2-Task',
  `task_user_id` int(11) NOT NULL COMMENT 'From user_master;If type = 2',
  `googleEventId` text NOT NULL,
  `event_title` varchar(250) NOT NULL,
  `event_notes` text NOT NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_date` date NOT NULL,
  `end_time` time NOT NULL,
  `is_all_day` enum('0','1') NOT NULL,
  `event_color` varchar(250) NOT NULL COMMENT 'Hash code',
  `event_for` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1: Own, 2: Agent',
  `assigned_user_id` int(11) NOT NULL COMMENT 'From user_master (If event_for = 2: Agent))',
  `assigned_user_group_id` int(11) NOT NULL COMMENT 'First record if for creating groun for create app for Agent (event_for = 2: Agent)',
  `is_public` enum('0','1') NOT NULL,
  `reminder_email_date` datetime NOT NULL COMMENT 'Reminder Date To Email Notification',
  `reminder_popup_date` datetime NOT NULL COMMENT 'Reminder Date To Pop-Up Notification',
  `is_close` enum('0','1') NOT NULL COMMENT '0. Open Popup 1 .Not Open Popup',
  `is_email` enum('0','1') NOT NULL COMMENT '1-Yes,0-No ',
  `is_mail_sent` int(1) NOT NULL DEFAULT '0',
  `email_time_before` int(11) NOT NULL,
  `email_time_type` enum('1','2') NOT NULL COMMENT '1-Hour,2-Day ',
  `is_popup` enum('0','1') NOT NULL COMMENT '1-Yes,0-No ',
  `popup_time_before` int(11) NOT NULL,
  `popup_time_type` enum('1','2') NOT NULL COMMENT '1-Hour,2-Day ',
  `is_pop_by` enum('0','1') NOT NULL,
  `is_gift` enum('0','1') NOT NULL,
  `ifRepeat` int(1) NOT NULL COMMENT '1.Yes, 0.No.',
  `repeatType` int(1) NOT NULL COMMENT '1.Hourly, 2.Daily, 3.Weekly,\n\n4.Monthly, 5.Yearly.(ifRepeat =1) ',
  `everyHours` int(11) NOT NULL COMMENT '(ifRepeat =1, repeatType = 1) ',
  `everyYears` int(11) NOT NULL COMMENT '(ifRepeat =1, repeatType = 5) ',
  `dailyType` int(1) NOT NULL COMMENT '1. N Days, 2.Weekdays, 3.Weekends.(ifRepeat =1, repeatType = 2) ',
  `everyDays` int(11) NOT NULL COMMENT '(ifRepeat =1, repeatType = 2, dailyType = 1) ',
  `everyWeeks` int(11) NOT NULL COMMENT '(ifRepeat =1, repeatType = 3) ',
  `everyWeekonMon` int(1) NOT NULL COMMENT '1.Yes, 0.No.(ifRepeat =1, repeatType = 3) ',
  `everyWeekonTue` int(1) NOT NULL COMMENT '1.Yes, 0.No.(ifRepeat =1, repeatType = 3) ',
  `everyWeekonWed` int(1) NOT NULL COMMENT '1.Yes, 0.No.(ifRepeat =1, repeatType = 3) ',
  `everyWeekonThu` int(1) NOT NULL COMMENT '1.Yes, 0.No.(ifRepeat =1, repeatType = 3) ',
  `everyWeekonFri` int(1) NOT NULL COMMENT '1.Yes, 0.No.(ifRepeat =1, repeatType = 3) ',
  `everyWeekonSat` int(1) NOT NULL COMMENT '1.Yes, 0.No.(ifRepeat =1, repeatType = 3) ',
  `everyWeekonSun` int(1) NOT NULL COMMENT '1.Yes, 0.No.(ifRepeat =1, repeatType = 3) ',
  `monthlyType` int(1) NOT NULL COMMENT '1. Date-Month, 2.Nth Date-Month.(ifRepeat =1, repeatType = 4) ',
  `monthDate` int(11) NOT NULL COMMENT 'ifRepeat =1, repeatType = 4, monthType=1 ',
  `monthCount` int(11) NOT NULL COMMENT 'ifRepeat =1, repeatType = 4, monthType=1 ',
  `nthDay` int(1) NOT NULL COMMENT '1.First,2.Second,…,5.Last.(ifRepeat =1, repeatType = 4, monthType=2) ',
  `nthDate` int(2) NOT NULL COMMENT '1 to 10 values. (1.Day,2.Weekday,3.Weekend,4.Mon,5.Tue,6.Wed,7.Thu,8.Fri,9.Sat,10.Sun)(ifRepeat =1, repeatType = 4, monthType=2) ',
  `nthMonthCount` int(11) NOT NULL COMMENT 'ifRepeat =1, repeatType = 4, monthType=2 ',
  `endTemplateType` int(1) NOT NULL COMMENT '1.Never, 2.After N occour, 3.Date-Time.(ifRepeat =1) ',
  `endCounter` int(11) NOT NULL COMMENT '(ifRepeat =1, endTemplateType = 2) ',
  `endTemplateDate` date NOT NULL COMMENT '(ifRepeat =1, endTemplateType = 3) ',
  `endTemplateTime` time NOT NULL COMMENT '(ifRepeat =1, endTemplateType = 3) ',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `task_user_id` (`task_user_id`),
  KEY `assigned_user_id` (`assigned_user_id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_repeat_trans`
--

CREATE TABLE IF NOT EXISTS `calendar_repeat_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `calendar_id` int(11) NOT NULL COMMENT 'From caledar_master',
  `event_start_date` date NOT NULL,
  `event_start_time` time NOT NULL,
  `event_end_date` date NOT NULL,
  `event_end_time` time NOT NULL,
  `event_title` varchar(255) DEFAULT NULL,
  `event_notes` text,
  `event_color` varchar(255) DEFAULT NULL,
  `edit_flag` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE IF NOT EXISTS `campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key and autoincrement',
  `name` varchar(100) DEFAULT NULL COMMENT 'unique',
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `agent_id` int(11) DEFAULT NULL COMMENT 'foreign key of id in agent table',
  `admin_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime NOT NULL,
  `status` int(1) NOT NULL COMMENT '1-Publish or 0-Unpublish',
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_blast`
--

CREATE TABLE IF NOT EXISTS `campaign_blast` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key and autoincrement',
  `subject` varchar(150) DEFAULT NULL,
  `campaign_id` int(11) DEFAULT NULL COMMENT 'foreign key of id of campaign',
  `agent_id` int(11) DEFAULT NULL COMMENT 'foreign key of id of campaign',
  `admin_id` int(11) DEFAULT NULL,
  `client_mail_id` mediumtext,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '1-Publish,0-Unpublish',
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `agent_id` (`agent_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_type`
--

CREATE TABLE IF NOT EXISTS `campaign_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key and Auto increment',
  `campaign_type` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1-Active,0-Deactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_admin_website`
--

CREATE TABLE IF NOT EXISTS `child_admin_website` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lw_admin_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `zopim_livechat_script` text,
  `google_analytics_code` text,
  `google_adword_code` text,
  `adword_registration` text NOT NULL,
  `adword_login` text NOT NULL,
  `adword_property_valuation` text NOT NULL,
  `adword_reg_two_property` text NOT NULL,
  `adword_detail_property` text NOT NULL,
  `adword_new_property` text NOT NULL,
  `meta_data_title` varchar(250) NOT NULL,
  `meta_data_description` text NOT NULL,
  `meta_data_keywords` text NOT NULL,
  `meta_data_robot` tinyint(1) NOT NULL COMMENT '0: No Follow, 1: Follow',
  `meta_data_default_city` varchar(255) NOT NULL,
  `mls_disclaimer` text NOT NULL,
  `mls_logo` varchar(255) NOT NULL,
  `footer_mls_disclaimer` text NOT NULL,
  `copyright_statement` mediumtext NOT NULL,
  `mls_id` int(10) unsigned NOT NULL,
  `selected_theme` tinyint(1) NOT NULL COMMENT 'From 1 to 5 - value',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `website_status` tinyint(4) NOT NULL COMMENT '1 - Active , 2 - Deactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_agents`
--

CREATE TABLE IF NOT EXISTS `child_website_agents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) NOT NULL COMMENT 'From login_master',
  `agent_name` varchar(250) NOT NULL,
  `mls_user_id` int(10) NOT NULL COMMENT 'From login_master',
  `title` varchar(100) NOT NULL,
  `agent_email_id` varchar(250) NOT NULL,
  `agent_description` mediumtext NOT NULL,
  `agent_pic` varchar(250) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `contact_form_embed_code` mediumtext NOT NULL,
  `show_on_web` tinyint(1) NOT NULL COMMENT '1- Yes, 0-No',
  `is_lender` tinyint(1) NOT NULL,
  `is_team_leader` tinyint(1) NOT NULL,
  `is_admin` tinyint(4) NOT NULL,
  `domain_id` int(10) NOT NULL COMMENT 'From child_website_domain_master',
  `skype_id` varchar(100) NOT NULL,
  `facebook_url` varchar(500) NOT NULL,
  `twitter_url` varchar(500) NOT NULL,
  `linkedin_url` varchar(500) NOT NULL,
  `pinterest_url` varchar(500) NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From child_website_users',
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From child_website_users',
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1- Active, 0- Deactive',
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`,`created_by`,`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_agents_contact_info`
--

CREATE TABLE IF NOT EXISTS `child_website_agents_contact_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `child_web_agent_id` int(11) NOT NULL COMMENT 'From child_website_agents',
  `office_name` varchar(255) NOT NULL,
  `address_line1` mediumtext NOT NULL,
  `address_line2` mediumtext NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip_code` varchar(15) NOT NULL,
  `country` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `fax_number` varchar(50) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1- Active, 0- Deactive',
  PRIMARY KEY (`id`),
  KEY `child_web_agent_id` (`child_web_agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_banner_master`
--

CREATE TABLE IF NOT EXISTS `child_website_banner_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banner_image` varchar(255) NOT NULL,
  `banner_sequence` int(11) NOT NULL,
  `domain_id` int(10) NOT NULL COMMENT 'From child_website_domain_master',
  `created_by` int(11) NOT NULL COMMENT 'From child_website_users (lead_users)',
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From child_website_users (lead_users)',
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-Active, 0-Inactive',
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`,`created_by`,`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_carousels_master`
--

CREATE TABLE IF NOT EXISTS `child_website_carousels_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `carousels_type` tinyint(1) NOT NULL COMMENT '1- Single Row, 2- Double Row',
  `carousels_name` varchar(255) NOT NULL,
  `domain_id` int(10) NOT NULL COMMENT 'From child_website_domain_master',
  `child_admin_id` int(11) NOT NULL COMMENT 'From child_admin_website',
  `child_record_id` int(11) NOT NULL COMMENT 'From child_website_carousels_master (Child DB ID)',
  `order_of_position` int(11) NOT NULL,
  `only_views` tinyint(1) NOT NULL,
  `only_shortsale` tinyint(1) NOT NULL,
  `only_new_construction` tinyint(1) NOT NULL,
  `only_open_houses` tinyint(1) NOT NULL,
  `only_firms_listing` tinyint(1) NOT NULL,
  `only_forclosures` tinyint(1) NOT NULL,
  `only_agent_listing` tinyint(1) NOT NULL,
  `only_waterfront` tinyint(1) NOT NULL,
  `custom_db_fields` text NOT NULL,
  `min_price` float NOT NULL,
  `max_price` float NOT NULL,
  `location_filter` tinyint(1) NOT NULL,
  `county` varchar(250) DEFAULT NULL,
  `state` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `community_name` varchar(250) NOT NULL,
  `zipcode` varchar(250) NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From child_website_users',
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From child_website_users',
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1 - Active,0 - Inactive',
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`,`created_by`,`modified_by`),
  KEY `child_admin_id` (`child_admin_id`),
  KEY `child_record_id` (`child_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_carousels_property_type_trans`
--

CREATE TABLE IF NOT EXISTS `child_website_carousels_property_type_trans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `carousels_id` int(11) NOT NULL COMMENT 'From child_website_carousels_master',
  `property_type_id` int(11) NOT NULL COMMENT 'From mls_property_type',
  PRIMARY KEY (`id`),
  KEY `carousels_id` (`carousels_id`),
  KEY `propetry_type_id` (`property_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_carousels_trans`
--

CREATE TABLE IF NOT EXISTS `child_website_carousels_trans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `carousels_id` int(10) NOT NULL COMMENT 'From mls_property_carousels_master',
  `property_id` int(11) NOT NULL COMMENT 'From mls_property_list_master',
  `created_by` int(11) NOT NULL COMMENT 'From child_website_users',
  `created_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1 - Active,0 - Inactive',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_cms_menu_trans`
--

CREATE TABLE IF NOT EXISTS `child_website_cms_menu_trans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cms_id` int(10) NOT NULL COMMENT 'From cms_master',
  `menu_title` varchar(255) NOT NULL,
  `menu_type` tinyint(1) NOT NULL COMMENT '1- System, 2- Main, 3- Middle, 4-Footer',
  `menu_sequence` int(10) unsigned NOT NULL,
  `menu_id` int(10) NOT NULL COMMENT 'From cms_master',
  `parent_menu_id` int(10) NOT NULL COMMENT 'From cms_master. If menu_type = ''Main''',
  `menu_level` int(11) NOT NULL,
  `external_url` varchar(255) DEFAULT NULL,
  `domain_id` int(11) NOT NULL COMMENT 'From child_website_domain_master',
  `created_by` int(11) NOT NULL COMMENT 'From child_website_users (lead_users)',
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From child_website_users (lead_users)',
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-Active, 0-Inactive',
  PRIMARY KEY (`id`),
  KEY `cms_id` (`cms_id`),
  KEY `parent_menu_id` (`parent_menu_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `menu_id` (`menu_id`),
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_domain_master`
--

CREATE TABLE IF NOT EXISTS `child_website_domain_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `login_id` int(11) NOT NULL COMMENT 'From login_master',
  `zopim_livechat_script` text NOT NULL,
  `property_count_before_login` int(2) NOT NULL,
  `selected_theme` tinyint(1) NOT NULL COMMENT 'From 1 to 5 - value',
  `logo_pic` varchar(250) NOT NULL,
  `favicon_pic` varchar(250) NOT NULL,
  `theme_color` tinyint(1) NOT NULL,
  `theme_font_family` tinyint(1) NOT NULL,
  `theme_layout` tinyint(1) NOT NULL,
  `theme_bg_color` varchar(10) NOT NULL,
  `theme_bg_image` varchar(255) NOT NULL,
  `content_page_css_code` text NOT NULL,
  `facebook_link` mediumtext NOT NULL,
  `twitter_link` mediumtext NOT NULL,
  `google_link` mediumtext NOT NULL,
  `linkedin_link` mediumtext NOT NULL,
  `youtube_link` mediumtext NOT NULL,
  `rss_link` mediumtext NOT NULL,
  `tumbler_link` mediumtext NOT NULL,
  `pinterest_link` mediumtext NOT NULL,
  `flicker_link` mediumtext NOT NULL,
  `yelp_link` mediumtext NOT NULL,
  `google_analytics_code` text,
  `google_adword_code` text,
  `adword_registration` text NOT NULL,
  `adword_login` text NOT NULL,
  `adword_property_valuation` text NOT NULL,
  `adword_reg_two_property` text NOT NULL,
  `adword_detail_property` text NOT NULL,
  `adword_new_property` text NOT NULL,
  `walk_score_api_key` varchar(250) NOT NULL,
  `below_banner_title` varchar(250) NOT NULL,
  `below_banner_subtitle` mediumtext NOT NULL,
  `footer_about_us` mediumtext NOT NULL,
  `footer_phone` varchar(30) NOT NULL,
  `footer_fax` varchar(30) NOT NULL,
  `footer_email` varchar(250) NOT NULL,
  `footer_mobile` varchar(30) NOT NULL,
  `meta_data_title` varchar(250) NOT NULL,
  `meta_data_description` text NOT NULL,
  `meta_data_keywords` text NOT NULL,
  `meta_data_robot` tinyint(1) NOT NULL COMMENT '0: No Follow, 1: Follow',
  `meta_data_default_city` varchar(255) NOT NULL,
  `mls_disclaimer` text NOT NULL,
  `mls_logo` varchar(255) NOT NULL,
  `valuation_image` varchar(250) DEFAULT NULL COMMENT 'Home Valuation background image',
  `footer_mls_disclaimer` text NOT NULL,
  `copyright_statement` mediumtext NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-Active, 0-Inactive',
  `website_status` tinyint(4) NOT NULL COMMENT '1 - Active , 2 - Deactive',
  PRIMARY KEY (`id`),
  KEY `login_id` (`login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_footer_links`
--

CREATE TABLE IF NOT EXISTS `child_website_footer_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `child_record_id` int(11) NOT NULL,
  `link_1` varchar(255) NOT NULL,
  `page_type_1` tinyint(1) NOT NULL,
  `page_1` varchar(255) NOT NULL,
  `url_1` mediumtext NOT NULL,
  `link_2` varchar(255) NOT NULL,
  `page_type_2` tinyint(1) NOT NULL,
  `page_2` varchar(255) NOT NULL,
  `url_2` mediumtext NOT NULL,
  `link_3` varchar(255) NOT NULL,
  `page_type_3` tinyint(1) NOT NULL,
  `page_3` varchar(255) NOT NULL,
  `url_3` mediumtext NOT NULL,
  `domain_id` int(10) NOT NULL,
  `superadmin_domain_id` int(10) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_home_social_links`
--

CREATE TABLE IF NOT EXISTS `child_website_home_social_links` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `social_image` varchar(250) NOT NULL,
  `social_title` varchar(200) NOT NULL,
  `social_link` mediumtext NOT NULL,
  `social_squence` int(10) NOT NULL COMMENT 'To display menu items in predefined order',
  `domain_id` int(10) NOT NULL COMMENT 'From child_website_domain_master',
  `created_by` int(11) NOT NULL COMMENT 'From child_website_users',
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From child_website_users',
  `modified_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1 - Active,0 - Inactive',
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`,`created_by`,`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `child_website_nearby_area`
--

CREATE TABLE IF NOT EXISTS `child_website_nearby_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL COMMENT 'From child_website_domain_master',
  `child_admin_id` int(11) NOT NULL COMMENT 'From child_admin_website',
  `child_record_id` int(11) NOT NULL COMMENT 'From child_website_nearby_area (Child DB ID)',
  `location_text` varchar(250) NOT NULL,
  `location_url` varchar(250) NOT NULL,
  `order_of_display` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1: Active, 0: Inactive',
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`),
  KEY `child_admin_id` (`child_admin_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `child_record_id` (`child_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key and Auto increment',
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(10) NOT NULL,
  `state_id` int(11) DEFAULT NULL COMMENT 'From state',
  `country_id` int(11) DEFAULT NULL COMMENT 'From country',
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1-Active,0-Deactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_master`
--

CREATE TABLE IF NOT EXISTS `cms_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `menu_title` varchar(225) NOT NULL,
  `menu_image` varchar(255) NOT NULL,
  `title` varchar(225) NOT NULL COMMENT 'CMS title',
  `page_type` enum('1','2') NOT NULL COMMENT '1 CMS page 2 Article page',
  `slug` varchar(250) NOT NULL,
  `page_position` enum('1','2','3','4') NOT NULL COMMENT '1 top,2 bottom,3 left, 4 right',
  `short_description` text NOT NULL,
  `description` text NOT NULL,
  `page_url` varchar(225) NOT NULL,
  `domain_id` int(10) NOT NULL COMMENT 'From child_website_domain_master',
  `superadmin_domain_id` int(11) NOT NULL,
  `meta_title` varchar(225) NOT NULL,
  `meta_keyword` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_robot` tinyint(1) NOT NULL COMMENT '1: Follow, 0: Nofollow',
  `menu_id` int(10) NOT NULL,
  `menu_level` int(11) NOT NULL,
  `publish_on` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1','2') NOT NULL COMMENT '0.Unpublish 1.Publish 2. Save as a Draft',
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_additionalfield_trans`
--

CREATE TABLE IF NOT EXISTS `contact_additionalfield_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `field_type` varchar(100) NOT NULL,
  `field_name` varchar(500) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_address_trans`
--

CREATE TABLE IF NOT EXISTS `contact_address_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `address_type` int(11) NOT NULL COMMENT 'From contact_address_type',
  `address_line1` varchar(500) NOT NULL,
  `address_line2` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_archive_master`
--

CREATE TABLE IF NOT EXISTS `contact_archive_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` enum('Mr.','Ms.','Mrs.') NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `middle_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `spousefirst_name` varchar(250) NOT NULL,
  `spousemiddle_name` varchar(250) DEFAULT NULL,
  `spouselast_name` varchar(250) NOT NULL,
  `contact_pic` varchar(250) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `company_post` varchar(250) NOT NULL,
  `is_lead` enum('0','1') NOT NULL COMMENT '0.No, 1.Yes',
  `notes` text NOT NULL,
  `contact_source` int(11) NOT NULL COMMENT 'From contact_source',
  `contact_method` int(11) NOT NULL COMMENT 'form contact method master',
  `birth_date` date NOT NULL,
  `anniversary_date` date NOT NULL,
  `contact_status` int(11) NOT NULL COMMENT 'From contact_status',
  `created_type` enum('1','2','3','4','5','6','7') NOT NULL COMMENT '1. Portal, 2.CSV,3 Facebook,4 Linkedin,5-From Lead,6.Joomla,7.Google',
  `fb_id` varchar(225) NOT NULL COMMENT 'From Facebook to Import',
  `fb_login_id` varchar(225) NOT NULL COMMENT 'Login Facebook User ID',
  `lead_id` int(11) NOT NULL COMMENT 'form lead master',
  `linkedin_id` varchar(225) NOT NULL COMMENT 'Login Linkedin User in Friend ID',
  `google_user_id` varchar(500) NOT NULL,
  `linkedin_message_id` double NOT NULL,
  `linkedin_user_id` varchar(255) NOT NULL COMMENT 'From Login Linkedin User ID',
  `csv_id` int(11) NOT NULL COMMENT 'From contact_csv',
  `joomla_user_id` int(11) NOT NULL COMMENT 'Joomla user id(From joomla website)',
  `joomla_domain_name` varchar(250) NOT NULL COMMENT 'Joomla domain name(From joomla website)',
  `domain_id` int(11) NOT NULL,
  `joomla_address` text NOT NULL,
  `joomla_ip_address` varchar(50) DEFAULT NULL,
  `is_subscribe` enum('0','1') NOT NULL DEFAULT '0',
  `price_range_from` float NOT NULL,
  `price_range_to` float NOT NULL,
  `min_area` int(11) NOT NULL,
  `max_area` int(11) NOT NULL,
  `house_style` varchar(250) NOT NULL,
  `area_of_interest` varchar(250) NOT NULL,
  `square_footage` varchar(250) NOT NULL,
  `no_of_bedrooms` int(11) NOT NULL,
  `no_of_bathrooms` float NOT NULL,
  `buyer_preferences_notes` text NOT NULL,
  `joomla_contact_type` enum('Buyer','Seller','Buyer/Seller') NOT NULL DEFAULT 'Buyer',
  `joomla_category` enum('New','Qualify','Nurture','Watch','Hot','Pending Transaction','Closed Transaction','Inactive Prospect','Bogus') NOT NULL DEFAULT 'New' COMMENT '1 New 2 Qualify 3 Nurture 4 Watch 5 Hot 6 Pending Transaction 7 Closed Transaction 8 Inactive Prospect 9 Bogus''',
  `joomla_timeframe` varchar(30) DEFAULT NULL,
  `form_id` int(11) NOT NULL COMMENT 'form_id from lead_master',
  `mailgun_admin_id` varchar(100) DEFAULT NULL,
  `mailgun_contact_id` varchar(100) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_source` (`contact_source`),
  KEY `contact_method` (`contact_method`),
  KEY `contact_status` (`contact_status`),
  KEY `csv_id` (`csv_id`),
  KEY `created_by` (`created_by`),
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_chat_history`
--

CREATE TABLE IF NOT EXISTS `contact_chat_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'from contact master table',
  `login_fb_id` varchar(50) NOT NULL COMMENT 'login user id',
  `from_fb_id` varchar(50) NOT NULL,
  `to_fb_id` varchar(50) NOT NULL,
  `from_fb_name` varchar(50) NOT NULL,
  `to_fb_name` varchar(50) NOT NULL,
  `msg` text NOT NULL,
  `msg_date_time` datetime NOT NULL COMMENT 'msg sent date time',
  `fb_msg_id` varchar(50) NOT NULL,
  `type` int(1) NOT NULL,
  `twitter_message_type` enum('1','2') NOT NULL COMMENT '1:direct,2:twit',
  `inserted_date_time` datetime NOT NULL COMMENT 'history inserted on',
  `created_by` int(11) NOT NULL COMMENT 'for login master table',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_communication_plan_trans`
--

CREATE TABLE IF NOT EXISTS `contact_communication_plan_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `communication_plan_id` int(11) NOT NULL COMMENT 'From communication_plan',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `communication_plan_id` (`communication_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_contacttype_trans`
--

CREATE TABLE IF NOT EXISTS `contact_contacttype_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `contact_type_id` int(11) NOT NULL COMMENT 'From contact_type_master',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_type_id` (`contact_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_contact_status_trans`
--

CREATE TABLE IF NOT EXISTS `contact_contact_status_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `contact_status_id` int(11) NOT NULL COMMENT 'From contact__status_master',
  `created_by` int(11) NOT NULL COMMENT 'As Login User Id',
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_status_id` (`contact_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_conversations_trans`
--

CREATE TABLE IF NOT EXISTS `contact_conversations_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `task_id` int(11) NOT NULL,
  `log_type` int(2) NOT NULL COMMENT '1-Manual,2-Assign Interaction Plan, 3-Assign to Agent,4-Re-assign to Agent, 5-Email(Plan),6-Email(Campaign), 7-SMS(Plan), 8-SMS(Campaign), 9-Mail-out,10-Remove From Interaction plan,11-task transaction, 12.Joomla notes',
  `interaction_type` int(11) NOT NULL COMMENT 'From interaction_plan__plan_type_master,If type = 1',
  `description` text NOT NULL COMMENT 'If type = 1',
  `disposition` int(11) NOT NULL COMMENT 'From contact__disposition_master, If type = 1',
  `plan_id` int(11) NOT NULL COMMENT 'From Interaction_plan_master,If type = 2',
  `plan_name` varchar(225) NOT NULL COMMENT 'Interaction paln if type 2,5,7,10',
  `assign_to` int(11) NOT NULL COMMENT 'From user_master, If type = 3 or 4',
  `interaction_id` int(11) NOT NULL COMMENT 'From interaction_master, If type = 5',
  `interaction_name` varchar(225) NOT NULL COMMENT 'Interaction paln if type 5,7',
  `campaign_id` int(11) NOT NULL COMMENT 'From email_campaign_master,If type = 6',
  `email_camp_template_id` int(11) NOT NULL COMMENT 'From email_campaign_recepient_trans, If type = 5 or 6',
  `email_camp_template_name` varchar(225) NOT NULL COMMENT 'From email_campaign_recepient_trans, If type = 5 or 6',
  `sms_camp_template_id` int(11) NOT NULL COMMENT 'From sms_campaign_recepient_trans, If type = 7 or 8',
  `sms_camp_template_name` varchar(225) NOT NULL COMMENT 'From sms_campaign_recepient_trans, If type = 7 or 8',
  `mail_out_type` varchar(225) NOT NULL COMMENT ' 1 Letter 2 Envelope 3 Label',
  `mail_out_template_id` int(11) NOT NULL,
  `mail_out_template_name` varchar(225) NOT NULL COMMENT 'Fetch template from envelope or label or letter lib as per type.',
  `is_completed_task` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-Task completed,0-Task pending',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `interaction_type` (`interaction_type`),
  KEY `plan_id` (`plan_id`),
  KEY `assign_to` (`assign_to`),
  KEY `interaction_id` (`interaction_id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `email_camp_template_id` (`email_camp_template_id`),
  KEY `sms_camp_template_id` (`sms_camp_template_id`),
  KEY `mail_out_template_id` (`mail_out_template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_csv_master`
--

CREATE TABLE IF NOT EXISTS `contact_csv_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `csv_file` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `additional_information` text NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_documents_trans`
--

CREATE TABLE IF NOT EXISTS `contact_documents_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `doc_type` int(11) NOT NULL COMMENT 'From contact_document_type',
  `doc_name` varchar(250) NOT NULL,
  `doc_desc` text NOT NULL,
  `doc_file` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_emails_trans`
--

CREATE TABLE IF NOT EXISTS `contact_emails_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `email_type` int(11) NOT NULL COMMENT 'From contact_email_type',
  `email_address` varchar(250) NOT NULL,
  `is_default` enum('0','1') NOT NULL COMMENT '0. No, 1.Yes',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_invitation_transcation`
--

CREATE TABLE IF NOT EXISTS `contact_invitation_transcation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'login_id',
  `contact_id` int(11) NOT NULL COMMENT 'from contact master',
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `contact_id` (`contact_id`),
  KEY `user_id_2` (`user_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_linkedin_trasection`
--

CREATE TABLE IF NOT EXISTS `contact_linkedin_trasection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linkedin_id` varchar(100) DEFAULT NULL,
  `linkedin_user_id` varchar(100) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `created_by_2` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_listing_last_seen`
--

CREATE TABLE IF NOT EXISTS `contact_listing_last_seen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) NOT NULL,
  `contact_last_seen` datetime NOT NULL,
  `listing_last_seen` datetime NOT NULL,
  `manual_contact_last_seen` datetime NOT NULL,
  `joomla_lead_last_seen` datetime NOT NULL,
  `form_lead_last_seen` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login_id` (`login_id`),
  KEY `login_id_2` (`login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_log`
--

CREATE TABLE IF NOT EXISTS `contact_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'Login id of the user',
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_master`
--

CREATE TABLE IF NOT EXISTS `contact_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` enum('Mr.','Ms.','Mrs.') NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `middle_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `spousefirst_name` varchar(250) NOT NULL,
  `spousemiddle_name` varchar(250) DEFAULT NULL,
  `spouselast_name` varchar(250) NOT NULL,
  `contact_pic` varchar(250) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `company_post` varchar(250) NOT NULL,
  `is_lead` enum('0','1') NOT NULL COMMENT '0.No, 1.Yes',
  `notes` text NOT NULL,
  `contact_source` int(11) NOT NULL COMMENT 'From contact_source',
  `contact_method` int(11) NOT NULL COMMENT 'form contact method master',
  `birth_date` date NOT NULL,
  `anniversary_date` date NOT NULL,
  `contact_status` int(11) NOT NULL COMMENT 'From contact_status',
  `created_type` enum('1','2','3','4','5','6','7') NOT NULL COMMENT '1. Portal, 2.CSV,3 Facebook,4 Linkedin,5-From Lead,6.Joomla,7.Google',
  `fb_id` varchar(225) NOT NULL COMMENT 'From Facebook to Import',
  `fb_login_id` varchar(225) NOT NULL COMMENT 'Login Facebook User ID',
  `lead_id` int(11) NOT NULL COMMENT 'form lead master',
  `linkedin_id` varchar(225) NOT NULL COMMENT 'Login Linkedin User in Friend ID',
  `linkedin_message_id` double NOT NULL,
  `linkedin_user_id` varchar(255) NOT NULL COMMENT 'From Login Linkedin User ID',
  `google_user_id` varchar(500) NOT NULL,
  `csv_id` int(11) NOT NULL COMMENT 'From contact_csv',
  `joomla_user_id` int(11) NOT NULL COMMENT 'Joomla user id(From joomla website)',
  `joomla_domain_name` varchar(250) NOT NULL COMMENT 'Joomla domain name(From joomla website)',
  `domain_id` int(11) NOT NULL,
  `joomla_address` text NOT NULL,
  `joomla_ip_address` varchar(50) DEFAULT NULL,
  `is_subscribe` enum('0','1') NOT NULL DEFAULT '0',
  `price_range_from` float NOT NULL,
  `price_range_to` float NOT NULL,
  `min_area` int(11) NOT NULL,
  `max_area` int(11) NOT NULL,
  `house_style` varchar(250) NOT NULL,
  `area_of_interest` varchar(250) NOT NULL,
  `square_footage` varchar(250) NOT NULL,
  `no_of_bedrooms` int(11) NOT NULL,
  `no_of_bathrooms` float NOT NULL,
  `buyer_preferences_notes` text NOT NULL,
  `joomla_contact_type` enum('Buyer','Seller','Buyer/Seller') NOT NULL DEFAULT 'Buyer',
  `joomla_category` enum('New','Qualify','Nurture','Watch','Hot','Pending Transaction','Closed Transaction','Inactive Prospect','Bogus') NOT NULL DEFAULT 'New' COMMENT '1 New 2 Qualify 3 Nurture 4 Watch 5 Hot 6 Pending Transaction 7 Closed Transaction 8 Inactive Prospect 9 Bogus''',
  `joomla_timeframe` varchar(30) DEFAULT NULL,
  `is_valuation_contact` enum('No','Yes') NOT NULL DEFAULT 'No',
  `form_id` int(11) NOT NULL COMMENT 'form_id from lead_master',
  `mailgun_admin_id` varchar(100) DEFAULT NULL,
  `mailgun_contact_id` varchar(100) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_source` (`contact_source`),
  KEY `contact_method` (`contact_method`),
  KEY `contact_status` (`contact_status`),
  KEY `csv_id` (`csv_id`),
  KEY `created_by` (`created_by`),
  KEY `lead_id` (`lead_id`),
  KEY `created_by_2` (`created_by`),
  KEY `lead_id_2` (`lead_id`),
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Triggers `contact_master`
--
DROP TRIGGER IF EXISTS `contact_master_trigger`;
DELIMITER //
CREATE TRIGGER `contact_master_trigger` BEFORE UPDATE ON `contact_master`
 FOR EACH ROW BEGIN
	INSERT INTO contact_log
	    SET log_type = 'contact_master_update',
		contact_id = OLD.id,
		created_by = OLD.modified_by,
		created_date = NOW();
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_notes_trans`
--

CREATE TABLE IF NOT EXISTS `contact_notes_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `note_details` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_phone_trans`
--

CREATE TABLE IF NOT EXISTS `contact_phone_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `phone_type` int(11) NOT NULL COMMENT 'From contact_phone_type',
  `phone_no` varchar(15) NOT NULL,
  `is_default` enum('0','1') NOT NULL COMMENT '0. No, 1.Yes',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_social_trans`
--

CREATE TABLE IF NOT EXISTS `contact_social_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `profile_type` varchar(100) NOT NULL,
  `website_name` varchar(500) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_tag_trans`
--

CREATE TABLE IF NOT EXISTS `contact_tag_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `tag` varchar(250) NOT NULL,
  `is_default` enum('1','2') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_twitter_trasection`
--

CREATE TABLE IF NOT EXISTS `contact_twitter_trasection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `twitter_id` varchar(100) NOT NULL,
  `twitter_user_id` varchar(100) NOT NULL,
  `twitter_handle` varchar(200) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact_website_trans`
--

CREATE TABLE IF NOT EXISTS `contact_website_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `website_type` varchar(100) NOT NULL,
  `website_name` varchar(500) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__additionalfield_master`
--

CREATE TABLE IF NOT EXISTS `contact__additionalfield_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `field_type` int(2) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__address_type_master`
--

CREATE TABLE IF NOT EXISTS `contact__address_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__csv_mapping_master`
--

CREATE TABLE IF NOT EXISTS `contact__csv_mapping_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__csv_mapping_trans`
--

CREATE TABLE IF NOT EXISTS `contact__csv_mapping_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `csv_mapping_id` int(11) NOT NULL,
  `contact_master_field` varchar(250) NOT NULL,
  `csv_field` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__disposition_master`
--

CREATE TABLE IF NOT EXISTS `contact__disposition_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `contact__disposition_master`
--

INSERT INTO `contact__disposition_master` (`id`, `name`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Spoke With Party', '2014-11-07 00:00:00', 2, '0000-00-00 00:00:00', 0, '1'),
(2, 'Left Message', '2014-11-07 00:00:00', 2, '0000-00-00 00:00:00', 0, '1'),
(3, 'No Answer/No VM', '2014-11-07 00:00:00', 2, '0000-00-00 00:00:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `contact__document_type_master`
--

CREATE TABLE IF NOT EXISTS `contact__document_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__email_type_master`
--

CREATE TABLE IF NOT EXISTS `contact__email_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `contact__email_type_master`
--

INSERT INTO `contact__email_type_master` (`id`, `name`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Spouse Email', '2015-01-10 00:00:00', 1, '2015-01-21 14:37:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `contact__method_master`
--

CREATE TABLE IF NOT EXISTS `contact__method_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__phone_type_master`
--

CREATE TABLE IF NOT EXISTS `contact__phone_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__social_type_master`
--

CREATE TABLE IF NOT EXISTS `contact__social_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `contact__social_type_master`
--

INSERT INTO `contact__social_type_master` (`id`, `name`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Facebook', '2014-07-08 08:13:54', 4, '0000-00-00 00:00:00', 0, '1'),
(2, 'Twitter', '2014-07-08 08:13:54', 4, '2014-07-30 07:33:38', 4, '1'),
(3, 'Linkedin', '2014-11-05 06:53:49', 2, '0000-00-00 00:00:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `contact__source_master`
--

CREATE TABLE IF NOT EXISTS `contact__source_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__status_master`
--

CREATE TABLE IF NOT EXISTS `contact__status_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__type_master`
--

CREATE TABLE IF NOT EXISTS `contact__type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact__websitetype_master`
--

CREATE TABLE IF NOT EXISTS `contact__websitetype_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `country_code` char(2) NOT NULL DEFAULT '',
  `country_name` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key and Auto increment',
  `country` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1-Active,0-Deactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cron_data`
--

CREATE TABLE IF NOT EXISTS `cron_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `property_status_cron_date` datetime NOT NULL,
  `new_property_cron_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cron_image_counter`
--

CREATE TABLE IF NOT EXISTS `cron_image_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_id` int(11) NOT NULL,
  `start_mls_id` int(11) NOT NULL,
  `no_of_mls_id` int(11) NOT NULL,
  `end_mls_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cron_image_test`
--

CREATE TABLE IF NOT EXISTS `cron_image_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(50) NOT NULL,
  `p_type` varchar(4) NOT NULL,
  `LN` bigint(20) NOT NULL,
  `begin_date` varchar(20) NOT NULL,
  `curr_date` varchar(20) NOT NULL,
  `created_date` datetime NOT NULL,
  `completed_date` varchar(20) DEFAULT NULL,
  `created_ip` varchar(50) DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cron_test`
--

CREATE TABLE IF NOT EXISTS `cron_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(50) NOT NULL,
  `p_type` varchar(4) NOT NULL,
  `begin_date` varchar(20) NOT NULL,
  `curr_date` varchar(20) NOT NULL,
  `created_date` datetime NOT NULL,
  `completed_date` varchar(20) DEFAULT NULL,
  `created_ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_master`
--

CREATE TABLE IF NOT EXISTS `custom_field_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1-Active,0-Deactive',
  `module_id` int(2) NOT NULL COMMENT '1-Email Campaign,2-SMS Campaign,3-Email library,4-Email library,5-SMS,6-Label Library,7-Letter Library',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Dumping data for table `custom_field_master`
--

INSERT INTO `custom_field_master` (`id`, `name`, `status`, `module_id`) VALUES
(1, 'Date', '1', 1),
(2, 'Date', '1', 2),
(3, 'Date', '1', 3),
(4, 'Day', '1', 1),
(5, 'Day', '1', 2),
(6, 'Day', '1', 3),
(7, 'Month', '1', 1),
(8, 'Month', '1', 2),
(9, 'Month', '1', 3),
(10, 'Year', '1', 1),
(11, 'Year', '1', 2),
(12, 'Year', '1', 3),
(13, 'Day Of Week', '1', 1),
(14, 'Day Of Week', '1', 2),
(15, 'Day Of Week', '1', 3),
(16, 'Agent Name', '1', 1),
(17, 'Agent Name', '1', 2),
(18, 'Agent Name', '1', 3),
(19, 'Contact First Name', '1', 1),
(20, 'Contact First Name', '1', 2),
(21, 'Contact First Name', '1', 3),
(22, 'Contact First Name', '1', 4),
(23, 'Contact First Name', '1', 6),
(24, 'Contact First Name', '1', 7),
(25, 'Contact First Name', '1', 5),
(26, 'Contact Spouse/Partner First Name', '1', 1),
(27, 'Contact Spouse/Partner First Name', '1', 2),
(28, 'Contact Spouse/Partner First Name', '1', 3),
(29, 'Contact Spouse/Partner First Name', '1', 4),
(30, 'Contact Spouse/Partner First Name', '1', 5),
(31, 'Contact Spouse/Partner First Name', '1', 6),
(32, 'Contact Spouse/Partner First Name', '1', 7),
(33, 'Contact Last Name', '1', 1),
(34, 'Contact Last Name', '1', 2),
(35, 'Contact Last Name', '1', 3),
(36, 'Contact Last Name', '1', 4),
(37, 'Contact Last Name', '1', 5),
(38, 'Contact Last Name', '1', 6),
(39, 'Contact Last Name', '1', 7),
(40, 'Contact Spouse/Partner Last Name', '1', 1),
(41, 'Contact Spouse/Partner Last Name', '1', 2),
(42, 'Contact Spouse/Partner Last Name', '1', 3),
(43, 'Contact Spouse/Partner Last Name', '1', 4),
(44, 'Contact Spouse/Partner Last Name', '1', 5),
(45, 'Contact Spouse/Partner Last Name', '1', 6),
(46, 'Contact Spouse/Partner Last Name', '1', 7),
(47, 'Contact Company Name', '1', 1),
(48, 'Contact Company Name', '1', 2),
(49, 'Contact Company Name', '1', 3),
(50, 'Contact Company Name', '1', 4),
(51, 'Contact Company Name', '1', 5),
(52, 'Contact Company Name', '1', 6),
(53, 'Contact Company Name', '1', 7),
(54, 'Agent First Name', '1', 4),
(55, 'Agent First Name', '1', 6),
(56, 'Agent First Name', '1', 7),
(57, 'Agent Last Name', '1', 4),
(58, 'Agent Last Name', '1', 6),
(59, 'Agent Last Name', '1', 7),
(60, 'Agent Company', '1', 4),
(61, 'Agent Company', '1', 6),
(62, 'Agent Company', '1', 7),
(63, 'Agent Title', '1', 4),
(64, 'Agent Title', '1', 6),
(65, 'Agent Title', '1', 7),
(66, 'Agent Address', '1', 4),
(67, 'Agent Address', '1', 6),
(68, 'Agent Address', '1', 7),
(69, 'Agent City', '1', 4),
(70, 'Agent City', '1', 6),
(71, 'Agent City', '1', 7),
(72, 'Agent State', '1', 4),
(73, 'Agent State', '1', 6),
(74, 'Agent State', '1', 7),
(75, 'Agent Zip', '1', 4),
(76, 'Agent Zip', '1', 6),
(77, 'Agent Zip', '1', 7),
(78, 'Contact Address', '1', 4),
(79, 'Contact Address', '1', 5),
(80, 'Contact Address', '1', 6),
(81, 'Contact Address', '1', 7),
(82, 'Contact City', '1', 4),
(83, 'Contact City', '1', 5),
(84, 'Contact City', '1', 6),
(85, 'Contact City', '1', 7),
(86, 'Contact State', '1', 4),
(87, 'Contact State', '1', 5),
(88, 'Contact State', '1', 6),
(89, 'Contact State', '1', 7),
(90, 'Contact Zip', '1', 4),
(91, 'Contact Zip', '1', 5),
(92, 'Contact Zip', '1', 6),
(93, 'Contact Zip', '1', 7);

-- --------------------------------------------------------

--
-- Table structure for table `email_campaign_attachments`
--

CREATE TABLE IF NOT EXISTS `email_campaign_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_campaign_id` int(11) NOT NULL,
  `attachment_name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email_campaign_id` (`email_campaign_id`),
  KEY `email_campaign_id_2` (`email_campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_campaign_master`
--

CREATE TABLE IF NOT EXISTS `email_campaign_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name_id` int(11) NOT NULL,
  `template_category_id` int(11) NOT NULL,
  `template_subcategory_id` int(11) NOT NULL,
  `template_subject` text NOT NULL,
  `email_message` text NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `thumb_url` varchar(255) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `email_signature` int(11) NOT NULL,
  `email_send_type` int(1) NOT NULL COMMENT '1-Now, 2-Datetime',
  `email_send_date` date NOT NULL,
  `email_send_time` time NOT NULL,
  `is_draft` enum('1','0') NOT NULL COMMENT '1-Yes, 0-No',
  `is_unsubscribe` enum('1','0') NOT NULL COMMENT '1-Yes, 0-No',
  `email_type` enum('Campaign','Intereaction_plan') NOT NULL COMMENT '1-Campaign,2-Intreaction Plan',
  `interaction_id` int(11) NOT NULL,
  `email_send_auto` enum('1','0') NOT NULL COMMENT '1-Yes, 0-No',
  `is_sent_to_all` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1-Yes(Default), 2-No',
  `total_sent` int(11) NOT NULL,
  `email_blast_type` int(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('Deactive','Active') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`),
  KEY `template_name_id` (`template_name_id`),
  KEY `template_category_id` (`template_category_id`),
  KEY `template_subcategory_id` (`template_subcategory_id`),
  KEY `interaction_id` (`interaction_id`),
  KEY `template_name_id_2` (`template_name_id`),
  KEY `template_category_id_2` (`template_category_id`),
  KEY `template_subcategory_id_2` (`template_subcategory_id`),
  KEY `interaction_id_2` (`interaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_campaign_recepient_trans`
--

CREATE TABLE IF NOT EXISTS `email_campaign_recepient_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_campaign_id` int(11) NOT NULL,
  `template_subject` text NOT NULL,
  `email_address` varchar(250) NOT NULL,
  `email_message` text NOT NULL,
  `contact_id` int(11) NOT NULL,
  `email_trans_id` int(11) NOT NULL,
  `contact_type` int(11) NOT NULL,
  `recepient_cc` text NOT NULL,
  `recepient_bcc` text NOT NULL,
  `is_send` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-Send,0-Not send',
  `info` varchar(255) NOT NULL,
  `is_email_exist` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1 - Exist , 0 - Not exist',
  `sent_date` datetime NOT NULL,
  `send_email_date` date NOT NULL COMMENT 'For interaction',
  PRIMARY KEY (`id`),
  KEY `email_campaign_id` (`email_campaign_id`),
  KEY `contact_id` (`contact_id`),
  KEY `email_campaign_id_2` (`email_campaign_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_event_master`
--

CREATE TABLE IF NOT EXISTS `email_event_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1-Active,2-Deactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `email_event_master`
--

INSERT INTO `email_event_master` (`id`, `name`, `title`, `status`) VALUES
(1, 'password_reset', 'Password Reset', '1'),
(2, 'new_home_listings', 'New Home Listings', '1'),
(3, 'price_changes', 'Price Changes', '1'),
(4, 'property_status_changes', 'Property Status Changes', '1'),
(5, 'welcome_message_for_buyer_seller', 'Welcome Message for Buyer/Seller', '1'),
(6, 'new_lead', 'New Lead', '1'),
(7, 'returning_lead', 'Returning Lead – more than 30 days since they have visited', '1'),
(8, 'request_a_visit', 'Request a Visit', '1'),
(9, 'new_home_valuation_request ', 'New Home Valuation Request ', '1'),
(10, 'welcome_message_for_buyer', 'Welcome Message for Buyer', '1'),
(11, 'welcome_message_for_seller', 'Welcome Message for Seller', '1');

-- --------------------------------------------------------

--
-- Table structure for table `email_signature_master`
--

CREATE TABLE IF NOT EXISTS `email_signature_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `signature_name` varchar(250) NOT NULL,
  `full_signature` text NOT NULL,
  `is_default` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-yes,0-no',
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1- activated, 0 -deactivated',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `content` text,
  `last_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_template_master`
--

CREATE TABLE IF NOT EXISTS `email_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `template_subject` varchar(250) NOT NULL,
  `email_message` text NOT NULL,
  `email_send_type` enum('1','2') NOT NULL COMMENT '1-Auto responder,2-News letter',
  `email_event` int(11) NOT NULL COMMENT 'From email event master',
  `is_unsubscribe` enum('1','0') NOT NULL COMMENT '1-Yes,0-No',
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`),
  KEY `template_subcategory` (`template_subcategory`),
  KEY `email_event` (`email_event`),
  KEY `template_category_2` (`template_category`),
  KEY `template_subcategory_2` (`template_subcategory`),
  KEY `email_event_2` (`email_event`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `envelope_template_master`
--

CREATE TABLE IF NOT EXISTS `envelope_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL COMMENT 'From marketing_master_lib__category_master',
  `template_subcategory` int(11) NOT NULL COMMENT 'From marketing_master_lib__category_master',
  `template_type` varchar(250) NOT NULL,
  `template_size_id` varchar(250) NOT NULL,
  `size_w` float(8,4) NOT NULL,
  `size_h` float(8,4) NOT NULL,
  `envelope_content` text NOT NULL,
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`),
  KEY `template_subcategory` (`template_subcategory`),
  KEY `template_category_2` (`template_category`),
  KEY `template_subcategory_2` (`template_subcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `error_data_master`
--

CREATE TABLE IF NOT EXISTS `error_data_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `description` varchar(300) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fb_chat_last_sync`
--

CREATE TABLE IF NOT EXISTS `fb_chat_last_sync` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `participent1` varchar(50) NOT NULL,
  `participent2` varchar(50) NOT NULL,
  `sync_date_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `finance_contact`
--

CREATE TABLE IF NOT EXISTS `finance_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `for_pre_qualified` tinyint(1) NOT NULL,
  `for_home_loan` tinyint(1) NOT NULL,
  `user_question` mediumtext NOT NULL,
  `user_firstname` varchar(250) NOT NULL,
  `user_lastname` varchar(250) NOT NULL,
  `user_email` varchar(250) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `contact_preference` enum('phone','email') NOT NULL,
  `contact_time` enum('any','morning','afternoon','evening') NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_builder_called_from_ip`
--

CREATE TABLE IF NOT EXISTS `form_builder_called_from_ip` (
  `id` int(11) unsigned NOT NULL,
  `form_widget` varchar(200) NOT NULL,
  `domain` varchar(200) NOT NULL,
  `created_ip` varchar(50) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `home_report_city_code`
--

CREATE TABLE IF NOT EXISTS `home_report_city_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `city` varchar(21) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `average` float(10,2) NOT NULL,
  `total_listing` int(11) NOT NULL,
  `median_price` float(10,2) NOT NULL,
  `average_price` float(10,2) NOT NULL,
  `average_bath` float(10,2) NOT NULL,
  `average_bed` float(10,2) NOT NULL,
  `avg_price_1bed` float(10,2) NOT NULL,
  `avg_price_2bed` float(10,2) NOT NULL,
  `avg_price_3bed` float(10,2) NOT NULL,
  `avg_price_4bed` float(10,2) NOT NULL,
  `avg_price_5bed` float(10,2) NOT NULL,
  `median` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `home_report_mls`
--

CREATE TABLE IF NOT EXISTS `home_report_mls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `zipcode_status` tinyint(1) NOT NULL COMMENT '1- pending, 2-completed',
  `neighbourhood_status` tinyint(1) NOT NULL COMMENT '1- pending, 2-completed',
  `city_status` tinyint(1) NOT NULL COMMENT '1- pending, 2-completed',
  `home_sold_zip` tinyint(1) NOT NULL COMMENT '1- pending, 2-completed',
  `home_sold_city` tinyint(1) NOT NULL COMMENT '1- pending, 2-completed',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `home_report_zip_code`
--

CREATE TABLE IF NOT EXISTS `home_report_zip_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `property_type` varchar(4) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `average` float(10,2) NOT NULL,
  `total_listing` int(11) NOT NULL,
  `median_price` float(10,2) NOT NULL,
  `average_price` float(10,2) NOT NULL,
  `average_bath` float(10,2) NOT NULL,
  `average_bed` float(10,2) NOT NULL,
  `avg_price_1bed` float(10,2) NOT NULL,
  `avg_price_2bed` float(10,2) NOT NULL,
  `avg_price_3bed` float(10,2) NOT NULL,
  `avg_price_4bed` float(10,2) NOT NULL,
  `avg_price_5bed` float(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_adminuser_trans`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_adminuser_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `user_id` int(11) NOT NULL COMMENT 'From login_master user_type 2',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `interaction_plan_id` (`interaction_plan_id`),
  KEY `user_id` (`user_id`),
  KEY `interaction_plan_id_2` (`interaction_plan_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_contacts_trans`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_contacts_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `start_date` date NOT NULL,
  `plan_start_type` enum('1','2') NOT NULL COMMENT '1-Contacts assignment date, 2-Start date',
  `plan_start_date` date NOT NULL,
  `is_completed` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-Pending,1-Completed',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `interaction_plan_id_2` (`interaction_plan_id`),
  KEY `interaction_plan_id` (`interaction_plan_id`,`contact_id`),
  KEY `interaction_plan_id_3` (`interaction_plan_id`),
  KEY `interaction_plan_id_4` (`interaction_plan_id`,`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_contact_activity_log_manual`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_contact_activity_log_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `interaction_type` int(11) NOT NULL COMMENT 'From interaction_plan__plan_type_master',
  `description` text NOT NULL,
  `disposition` int(11) NOT NULL COMMENT 'From contact__disposotion_master',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  PRIMARY KEY (`id`),
  KEY `interaction_type` (`interaction_type`),
  KEY `interaction_type_2` (`interaction_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_contact_communication_plan`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_contact_communication_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `interaction_plan_interaction_id` int(11) NOT NULL COMMENT 'From interaction_plan_interaction_master',
  `interaction_type` int(11) NOT NULL COMMENT 'From interaction_plan__plan_type_master',
  `is_done` enum('0','1') NOT NULL COMMENT '0-Not done(Default), 1-Done.',
  `is_manualy` enum('0','1') NOT NULL DEFAULT '0',
  `task_date` date NOT NULL,
  `completed_by` int(11) NOT NULL,
  `task_completed_date` datetime NOT NULL,
  `notes` text NOT NULL,
  `disposition` int(11) NOT NULL COMMENT 'From contact__disposition_master',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`,`interaction_plan_id`,`interaction_plan_interaction_id`),
  KEY `interaction_plan_id` (`interaction_plan_id`,`interaction_plan_interaction_id`),
  KEY `contact_id_2` (`contact_id`,`interaction_plan_id`,`interaction_plan_interaction_id`),
  KEY `interaction_plan_id_2` (`interaction_plan_id`,`interaction_plan_interaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_contact_personal_touches`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_contact_personal_touches` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `interaction_type` int(11) NOT NULL COMMENT 'From interaction_plan__plan_type_master',
  `task` text NOT NULL,
  `followup_date` date NOT NULL,
  `is_done` enum('0','1') NOT NULL COMMENT '0-Not done(Default), 1-Done.',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `contact_id_2` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_interaction_master`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_interaction_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `interaction_type` int(11) NOT NULL COMMENT 'From interaction_plan__plan_type_master',
  `description` text NOT NULL,
  `assign_to` int(11) NOT NULL COMMENT 'From user_master',
  `start_type` enum('1','2','3') NOT NULL COMMENT '1-From plan start date, 2-After preceding interaction, 3.Specific date',
  `number_count` int(11) NOT NULL COMMENT 'If start_type = 1 or 2',
  `number_type` enum('Days','Weeks','Months','Years') NOT NULL COMMENT 'Days,Weeks,Months,Years',
  `interaction_id` int(11) NOT NULL COMMENT 'If start_type = 2, From interaction_plan_interaction_master',
  `premium_plan_id` int(11) NOT NULL,
  `start_date` date NOT NULL COMMENT 'If start_type =3',
  `priority` enum('High','Medium','Low') NOT NULL COMMENT 'High,Medium,Low',
  `drop_type` enum('1','2','3') NOT NULL COMMENT '1- Do not drop, 2- Drop after schedule date, 3-Drop after Specific date',
  `drop_after_day` int(11) NOT NULL COMMENT 'If drop_type = 2',
  `drop_after_date` date NOT NULL COMMENT 'If drop_type = 3',
  `interaction_notes` text NOT NULL,
  `template_category` int(11) NOT NULL COMMENT 'From template category master',
  `template_subcategory` int(11) NOT NULL,
  `template_name` int(11) NOT NULL COMMENT 'From template master',
  `interaction_sequence_date` date NOT NULL,
  `send_automatically` enum('1','0') NOT NULL COMMENT '1-Yes,0-No',
  `include_signature` enum('1','0') NOT NULL COMMENT '1-Yes,0-No',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `interaction_plan_id` (`interaction_plan_id`),
  KEY `interaction_id` (`interaction_id`),
  KEY `template_category` (`template_category`),
  KEY `template_subcategory` (`template_subcategory`),
  KEY `interaction_plan_id_2` (`interaction_plan_id`),
  KEY `interaction_id_2` (`interaction_id`),
  KEY `template_category_2` (`template_category`),
  KEY `template_subcategory_2` (`template_subcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_interaction_master_premium`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_interaction_master_premium` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `interaction_type` int(11) NOT NULL COMMENT 'From interaction_plan__plan_type_master',
  `description` text NOT NULL,
  `assign_to` int(11) NOT NULL COMMENT 'From user_master',
  `start_type` enum('1','2','3') NOT NULL COMMENT '1-From plan start date, 2-After preceding interaction, 3.Specific date',
  `number_count` int(11) NOT NULL COMMENT 'If start_type = 1 or 2',
  `number_type` enum('Days','Weeks','Months','Years') NOT NULL COMMENT 'Days,Weeks,Months,Years',
  `interaction_id` int(11) NOT NULL COMMENT 'If start_type = 2, From interaction_plan_interaction_master',
  `start_date` date NOT NULL COMMENT 'If start_type =3',
  `priority` enum('High','Medium','Low') NOT NULL COMMENT 'High,Medium,Low',
  `drop_type` enum('1','2','3') NOT NULL COMMENT '1- Do not drop, 2- Drop after schedule date, 3-Drop after Specific date',
  `drop_after_day` int(11) NOT NULL COMMENT 'If drop_type = 2',
  `drop_after_date` date NOT NULL COMMENT 'If drop_type = 3',
  `interaction_notes` text NOT NULL,
  `template_category` int(11) NOT NULL COMMENT 'From template category master',
  `template_subcategory` int(11) NOT NULL,
  `template_name` int(11) NOT NULL COMMENT 'From template master',
  `interaction_sequence_date` date NOT NULL,
  `send_automatically` enum('1','0') NOT NULL COMMENT '1-Yes,0-No',
  `include_signature` enum('1','0') NOT NULL COMMENT '1-Yes,0-No',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `interaction_plan_id` (`interaction_plan_id`),
  KEY `interaction_id` (`interaction_id`),
  KEY `template_category` (`template_category`),
  KEY `template_subcategory` (`template_subcategory`),
  KEY `interaction_plan_id_2` (`interaction_plan_id`),
  KEY `interaction_id_2` (`interaction_id`),
  KEY `template_category_2` (`template_category`),
  KEY `template_subcategory_2` (`template_subcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_interaction_to_do`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_interaction_to_do` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `interaction_plan_interaction_id` int(11) NOT NULL COMMENT 'From interaction_plan_interaction_master',
  `notes` text NOT NULL,
  `next_interaction_date` date NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `status` enum('1','2') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `interaction_plan_interaction_id` (`interaction_plan_interaction_id`),
  KEY `interaction_plan_interaction_i_2` (`interaction_plan_interaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_master`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `plan_name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `plan_status` int(11) NOT NULL COMMENT 'From interaction_plan__status_master',
  `target_audience` text NOT NULL,
  `plan_start_type` enum('1','2') NOT NULL COMMENT '1-Contacts assignment date, 2-Start date',
  `start_date` date NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `p_p_id` int(11) NOT NULL COMMENT 'premium plan id from interaction_plan_master_premium',
  `version` enum('0','1') NOT NULL DEFAULT '0',
  `by_superadmin` int(1) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `p_p_id` (`p_p_id`),
  KEY `plan_status` (`plan_status`),
  KEY `p_p_id_2` (`p_p_id`),
  KEY `plan_status_2` (`plan_status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_master_premium`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_master_premium` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `plan_name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `plan_status` int(11) NOT NULL COMMENT 'From interaction_plan__status_master',
  `target_audience` text NOT NULL,
  `plan_start_type` enum('1','2') NOT NULL COMMENT '1-Contacts assignment date, 2-Start date',
  `start_date` date NOT NULL,
  `is_default` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `by_superadmin` int(1) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan_time_trans`
--

CREATE TABLE IF NOT EXISTS `interaction_plan_time_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `interaction_time_type` enum('1','2','3','4','5') NOT NULL COMMENT '1-First time Start , 2- Pause, 3-Stop, 4- start after pause/stop',
  `interaction_time` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  PRIMARY KEY (`id`),
  KEY `interaction_plan_id` (`interaction_plan_id`),
  KEY `interaction_plan_id_2` (`interaction_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan__plan_type_master`
--

CREATE TABLE IF NOT EXISTS `interaction_plan__plan_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `interaction_plan__plan_type_master`
--

INSERT INTO `interaction_plan__plan_type_master` (`id`, `name`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Label', '2014-07-21 11:28:34', 2, '2014-08-19 07:25:22', 4, '1'),
(2, 'Envelope', '2014-07-21 11:30:18', 2, '2014-08-19 07:25:14', 4, '1'),
(3, 'Text', '2014-07-21 11:30:18', 2, '2014-08-19 07:24:39', 4, '1'),
(4, 'Call', '2014-07-21 11:30:18', 2, '2014-08-19 07:24:37', 4, '1'),
(5, 'Letter', '2014-07-21 11:30:18', 2, '2014-08-19 07:24:27', 4, '1'),
(6, 'Email', '2014-07-21 11:30:36', 2, '2014-08-19 07:24:21', 4, '1'),
(7, 'To-Do', '2014-07-22 14:36:44', 4, '2014-08-11 16:44:45', 4, '1'),
(8, 'Bomb Bomb Emails', '2015-03-02 00:00:00', 2, '2015-03-02 00:00:00', 4, '1');

-- --------------------------------------------------------

--
-- Table structure for table `interaction_plan__status_master`
--

CREATE TABLE IF NOT EXISTS `interaction_plan__status_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `interaction_plan__status_master`
--

INSERT INTO `interaction_plan__status_master` (`id`, `name`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Active', '2014-08-12 08:00:43', 4, '0000-00-00 00:00:00', 0, '1'),
(2, 'Paused', '2014-08-12 08:01:28', 4, '0000-00-00 00:00:00', 0, '1'),
(3, 'Stop', '2014-08-12 08:01:28', 4, '0000-00-00 00:00:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `joomla_leads_plan_assign`
--

CREATE TABLE IF NOT EXISTS `joomla_leads_plan_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interaction_plan_id` int(11) NOT NULL,
  `prospect_type` enum('Buyer','Seller') NOT NULL COMMENT 'Buyer,Seller',
  `min_price` float NOT NULL,
  `max_price` float NOT NULL,
  `status` enum('Off','On') NOT NULL COMMENT 'On, Off',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `interaction_plan_id` (`interaction_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_mapping`
--

CREATE TABLE IF NOT EXISTS `joomla_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(250) NOT NULL,
  `lw_admin_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_property_cron_master`
--

CREATE TABLE IF NOT EXISTS `joomla_property_cron_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `property_id` int(10) unsigned NOT NULL COMMENT 'From mls_property_list_master',
  `country` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `neighborhood` varchar(255) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `cron_type` enum('Weekly','Monthly') NOT NULL COMMENT 'Weekly,Monthly',
  `radius_limit` float NOT NULL,
  `data_from` tinyint(4) NOT NULL COMMENT '1: From Joomla (Current), 2: From CRM',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_property_cron_trans`
--

CREATE TABLE IF NOT EXISTS `joomla_property_cron_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joomla_property_cron_master_id` int(11) NOT NULL COMMENT 'From joomla_property_cron_master',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `assigned_date` datetime NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `last_report_file` varchar(250) NOT NULL,
  `last_report_date` datetime NOT NULL,
  `mailgun_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `joomla_property_cron_master_id` (`joomla_property_cron_master_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_addon_multi_site`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_addon_multi_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `domain` varchar(250) NOT NULL,
  `lw_admin_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_bookmarks`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `mlsid` int(11) NOT NULL,
  `propery_name` varchar(250) NOT NULL,
  `date` datetime NOT NULL,
  `domain` varchar(250) NOT NULL,
  `domain_id` int(10) unsigned NOT NULL COMMENT 'From child_website_domain_master',
  `lw_admin_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_log`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `log_date` datetime NOT NULL,
  `ip` varchar(100) NOT NULL,
  `domain` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `lw_admin_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_property_contact`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_property_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `mlsid` int(11) DEFAULT NULL,
  `property_name` varchar(250) NOT NULL,
  `domain` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `lw_admin_id` int(11) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `comments` text NOT NULL,
  `preferred_time` varchar(100) DEFAULT NULL,
  `form_type` enum('property','propertydetail') NOT NULL DEFAULT 'property',
  `mailgun_id` varchar(100) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_property_valuation_searches`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_property_valuation_searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lw_admin_id` int(11) NOT NULL COMMENT 'From contact_master',
  `joomla_uid` int(11) NOT NULL COMMENT 'From Joomla Website',
  `search_address` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `state` varchar(250) NOT NULL,
  `zip_code` varchar(250) NOT NULL,
  `bedroom` varchar(10) NOT NULL,
  `bathroom` float NOT NULL,
  `square_feet` int(11) NOT NULL,
  `home_condition` varchar(50) NOT NULL,
  `iam` varchar(50) NOT NULL COMMENT 'Looking for',
  `property_type` varchar(50) NOT NULL,
  `note` text NOT NULL,
  `date` datetime NOT NULL,
  `domain` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `send_report` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `report_timeline` enum('Weekly','Monthly') NOT NULL DEFAULT 'Weekly',
  `created_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_savesearch`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_savesearch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'Saved Searches id from Joomla website table',
  `name` varchar(150) NOT NULL,
  `url` mediumtext NOT NULL,
  `search_criteria` text NOT NULL,
  `search_sorting` varchar(250) NOT NULL,
  `property_type` varchar(4) NOT NULL,
  `min_price` float NOT NULL,
  `max_price` float NOT NULL,
  `bedroom` int(11) NOT NULL,
  `bathroom` int(11) NOT NULL,
  `min_area` int(11) NOT NULL,
  `max_area` int(11) NOT NULL,
  `min_year_built` int(11) NOT NULL,
  `max_year_built` int(11) NOT NULL,
  `fireplaces_total` int(11) NOT NULL,
  `min_lotsize` float NOT NULL,
  `max_lotsize` float NOT NULL,
  `garage_spaces` int(11) NOT NULL,
  `architecture` varchar(250) DEFAULT NULL,
  `property_status` varchar(250) NOT NULL,
  `new_construction` varchar(250) NOT NULL,
  `short_sale` varchar(250) NOT NULL,
  `bank_owned` varchar(250) NOT NULL,
  `mls_id` varchar(250) NOT NULL,
  `CDOM` varchar(250) NOT NULL,
  `school_district` varchar(250) DEFAULT NULL,
  `waterfront` varchar(250) DEFAULT NULL,
  `s_view` varchar(250) DEFAULT NULL,
  `parking_type` varchar(250) DEFAULT NULL,
  `pids` int(11) NOT NULL,
  `state` int(1) NOT NULL,
  `squareft` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `domain` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL COMMENT 'Domain id from domain master',
  `lw_admin_id` int(11) NOT NULL,
  `search_category` varchar(100) DEFAULT NULL,
  `created_type` enum('1','2') NOT NULL COMMENT '1. Portal, 2.Joomla',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_track`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `mlsid` int(11) NOT NULL,
  `propery_name` varchar(250) NOT NULL,
  `log_date` datetime NOT NULL,
  `views` int(50) NOT NULL,
  `domain` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `lw_admin_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `joomla_rpl_valuation_contact`
--

CREATE TABLE IF NOT EXISTS `joomla_rpl_valuation_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `mlsid` int(11) DEFAULT NULL,
  `property_name` varchar(250) NOT NULL,
  `domain` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `lw_admin_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `comments` text NOT NULL,
  `preferred_time` varchar(100) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `label_template_master`
--

CREATE TABLE IF NOT EXISTS `label_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL COMMENT 'From marketing_master_lib__category_master',
  `template_subcategory` int(11) NOT NULL COMMENT 'From marketing_master_lib__category_master',
  `template_type` varchar(250) NOT NULL,
  `size_type` int(11) NOT NULL,
  `size_w` float(8,4) NOT NULL,
  `size_h` float(8,4) NOT NULL,
  `label_content` text NOT NULL,
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`),
  KEY `template_subcategory` (`template_subcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lead_contact_type_trans`
--

CREATE TABLE IF NOT EXISTS `lead_contact_type_trans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(11) NOT NULL,
  `contact_type_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lead_data`
--

CREATE TABLE IF NOT EXISTS `lead_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto increment',
  `form_id` int(11) NOT NULL COMMENT 'From lead_master',
  `form_widget_id` varchar(250) NOT NULL COMMENT 'md5(uniqid()_max_id())',
  `first_name_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `first_name_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `last_name_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `last_name_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `phone_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `phone_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `email_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `email_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `single_line_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `single_line_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `paragraph_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `paragraph_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `address_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `address_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `date_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `date_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `website_data` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `website_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `price_range_from` varchar(250) NOT NULL,
  `price_range_from_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `price_range_to` varchar(250) NOT NULL,
  `price_range_to_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `house_style` varchar(250) NOT NULL,
  `house_style_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `area_of_interest` varchar(250) NOT NULL,
  `area_of_interest_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `square_footage` varchar(250) NOT NULL,
  `square_footage_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `no_of_bedrooms` int(11) NOT NULL,
  `no_of_bedrooms_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `no_of_bathrooms` int(11) NOT NULL,
  `no_of_bathrooms_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `buyer_preferences_notes` text NOT NULL,
  `buyer_preferences_notes_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `file_name` varchar(250) NOT NULL,
  `file_name_title` text NOT NULL COMMENT 'Separate multiple data by {^}',
  `created_ip` varchar(255) NOT NULL COMMENT 'Save Ip address from where form is submitted.',
  `domain_name` varchar(255) NOT NULL COMMENT 'Website details where form is embeded.',
  `created_date` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `form_widget_id` (`form_widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lead_master`
--

CREATE TABLE IF NOT EXISTS `lead_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto increment',
  `form_widget_id` varchar(250) NOT NULL COMMENT 'md5(uniqid()_max_id())',
  `form_title` varchar(250) NOT NULL,
  `show_title` enum('0','1') NOT NULL DEFAULT '1',
  `form_desc` text NOT NULL,
  `show_desc` enum('0','1') NOT NULL DEFAULT '1',
  `success_msg` text NOT NULL,
  `assigned_interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `form_width` int(11) NOT NULL,
  `form_height` int(11) NOT NULL,
  `bg_color` varchar(20) NOT NULL,
  `lead_form` text NOT NULL COMMENT 'Save entire form with div',
  `first_name` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `last_name` int(11) NOT NULL,
  `phone_field` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `email_field` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `single_line_field` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `paragraph_field` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `address_field` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `date_field` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `website_field` int(5) NOT NULL COMMENT 'No. of times this field in the form',
  `area_of_interest` int(11) NOT NULL,
  `price_range` int(11) NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `bathrooms` int(11) NOT NULL,
  `buyer_preferences_notes` int(11) NOT NULL,
  `house_style` int(11) NOT NULL,
  `square_footage` int(11) NOT NULL,
  `file` int(11) NOT NULL,
  `created_ip` varchar(255) NOT NULL COMMENT 'Save Ip address',
  `assign_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `form_widget_id` (`form_widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lead_users`
--

CREATE TABLE IF NOT EXISTS `lead_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `lw_id` int(11) NOT NULL,
  `child_user_type` tinyint(1) NOT NULL COMMENT '1 - Admin, 2 - User',
  `domain` varchar(250) NOT NULL,
  `zopim_livechat_script` text,
  `phone_no` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `email_for_status_change` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-No 1-Yes',
  `email_for_favorite` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-No 1-Yes',
  `email_for_valuation_set` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-No 1-Yes',
  `email_for_valuation_done` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-No 1-Yes',
  `qualified_for_mortgage` tinyint(1) NOT NULL,
  `first_time_buyer` tinyint(1) NOT NULL,
  `plan_to_purchase` tinyint(1) NOT NULL,
  `home_for_sale` tinyint(1) NOT NULL,
  `home_for_sale_addr` text NOT NULL,
  `home_for_sale_beds` int(2) NOT NULL,
  `home_for_sale_baths` int(2) NOT NULL,
  `contact_no` int(12) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1 - Active,2 - Inactive',
  PRIMARY KEY (`id`),
  KEY `lw_id` (`lw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lender_rr_weightage_trans`
--

CREATE TABLE IF NOT EXISTS `lender_rr_weightage_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `user_weightage` int(11) NOT NULL,
  `assigned_contact_id` int(11) NOT NULL COMMENT 'From user_contact_trans',
  `round` int(11) NOT NULL,
  `round_value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `letter_template_master`
--

CREATE TABLE IF NOT EXISTS `letter_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `size_w` float(8,4) NOT NULL,
  `size_h` float(8,4) NOT NULL,
  `template_subject` varchar(250) NOT NULL,
  `letter_content` text NOT NULL,
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`),
  KEY `template_subcategory` (`template_subcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_master`
--

CREATE TABLE IF NOT EXISTS `login_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `user_type` enum('1','2','3','4','5') NOT NULL COMMENT '1,Superadmin, 2-Admin, 3-Agent, 4-Assistant. 5-Admin Assistant',
  `agent_type` enum('Inside Sales Agent','Lender','Buyer''s Agent') NOT NULL DEFAULT 'Inside Sales Agent' COMMENT '''Inside Sales Agent'',''Lender'',''Buyer''''s Agent''',
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `mls_user_id` int(10) NOT NULL,
  `mls_firm_id` int(10) unsigned NOT NULL,
  `agent_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `email_id` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL COMMENT 'Encrypted',
  `sha_key` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `number_of_users_allowed` int(11) NOT NULL COMMENT 'Additional User Licence',
  `admin_pic` varchar(250) NOT NULL COMMENT 'Profile Picture',
  `brokerage_pic` varchar(250) NOT NULL,
  `user_license_no` varchar(250) NOT NULL,
  `user_weightage_joomla` int(5) NOT NULL COMMENT 'User weightage - For assigning contacts.',
  `remain_emails` bigint(11) unsigned NOT NULL,
  `remain_sms` bigint(11) unsigned NOT NULL,
  `remain_contacts` int(11) NOT NULL,
  `db_name` varchar(250) NOT NULL,
  `host_name` varchar(250) NOT NULL,
  `db_user_name` varchar(250) NOT NULL,
  `db_user_password` varchar(250) NOT NULL,
  `linkedin_access_token` text,
  `linkedin_secret_access_token` text,
  `linkedin_username` varchar(200) DEFAULT NULL,
  `is_buyer_tab` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 buyer tab not view in Admin and user 1 all of view',
  `lead_dashboard_tab` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0: Off, 1: On',
  `market_watch_tab` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0: Off, 1: On (Neighborhood Valuation report)',
  `contact_form_tab` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0: Off, 1: On',
  `fb_api_key` text NOT NULL,
  `fb_secret_key` text NOT NULL,
  `twitter_access_token` text,
  `twitter_access_token_secret` text,
  `twitter_username` text,
  `twitter_id` varchar(100) DEFAULT NULL,
  `google_access_token` varchar(500) NOT NULL,
  `google_user_name` varchar(255) NOT NULL,
  `bombbomb_username` varchar(100) NOT NULL,
  `bombbomb_password` varchar(50) NOT NULL,
  `twilio_account_sid` varchar(100) DEFAULT NULL,
  `twilio_auth_token` varchar(100) DEFAULT NULL,
  `twilio_number` varchar(100) DEFAULT NULL,
  `twilio_sms_url` varchar(200) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `archive_date` datetime NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `user_type` (`user_type`),
  KEY `user_id` (`user_id`),
  KEY `agent_id` (`agent_id`),
  KEY `mls_firm_id` (`mls_firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `login_master`
--

INSERT INTO `login_master` (`id`, `user_type`, `agent_type`, `user_id`, `mls_user_id`, `mls_firm_id`, `agent_id`, `admin_name`, `email_id`, `password`, `sha_key`, `address`, `phone`, `number_of_users_allowed`, `admin_pic`, `brokerage_pic`, `user_license_no`, `user_weightage_joomla`, `remain_emails`, `remain_sms`, `remain_contacts`, `db_name`, `host_name`, `db_user_name`, `db_user_password`, `linkedin_access_token`, `linkedin_secret_access_token`, `linkedin_username`, `is_buyer_tab`, `lead_dashboard_tab`, `market_watch_tab`, `contact_form_tab`, `fb_api_key`, `fb_secret_key`, `twitter_access_token`, `twitter_access_token_secret`, `twitter_username`, `twitter_id`, `google_access_token`, `google_user_name`, `bombbomb_username`, `bombbomb_password`, `twilio_account_sid`, `twilio_auth_token`, `twilio_number`, `twilio_sms_url`, `timezone`, `archive_date`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, '1', '', 0, 0, 0, 0, 'Mohit Trivedi', 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '', '', '', 0, '', '', '', 0, 0, 0, 0, '', '', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '', '', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2014-09-01 00:00:00', 1, '0000-00-00 00:00:00', 0, '1'),
(2, '2', 'Inside Sales Agent', 0, 0, 0, 0, 'Tushar Solanki', 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '', '                                                                                                ', '', 100, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', '0', '0', '0', '', 'America/New_York', '0000-00-00 00:00:00', '2015-07-13 05:59:16', 1, '2015-07-14 06:06:01', 1, '1'),
(3, '3', 'Inside Sales Agent', 1, 0, 0, 0, '', 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-07-13 09:10:52', 1, '0000-00-00 00:00:00', 0, '1'),
(4, '2', 'Inside Sales Agent', 0, 0, 0, 0, 'Dipal Prajapati', 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '', '                                                                                                ', '', 20, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', '0', '0', '0', '', 'Asia/Kolkata', '0000-00-00 00:00:00', '2015-07-14 03:53:38', 1, '2015-07-14 04:55:29', 1, '1'),
(5, '3', 'Inside Sales Agent', 1, 0, 0, 0, '', 'visa2@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-08-10 18:10:41', 1, '0000-00-00 00:00:00', 0, '1'),
(6, '3', 'Inside Sales Agent', 2, 0, 0, 0, '', 'visa3@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-08-10 18:28:45', 1, '0000-00-00 00:00:00', 0, '1'),
(7, '3', 'Inside Sales Agent', 3, 0, 0, 0, '', 'visa1@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-08-10 18:34:27', 1, '0000-00-00 00:00:00', 0, '1'),
(8, '3', 'Inside Sales Agent', 4, 0, 0, 0, '', 'outbound1@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-08-10 19:47:17', 1, '0000-00-00 00:00:00', 0, '1'),
(9, '3', 'Inside Sales Agent', 5, 0, 0, 0, '', 'outbound2@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', '0', '0', '0', '', '', '0000-00-00 00:00:00', '2015-08-10 20:01:32', 1, '2015-08-10 20:11:08', 0, '1'),
(10, '3', 'Inside Sales Agent', 6, 0, 0, 0, '', 'outbound3@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-08-10 20:12:29', 1, '0000-00-00 00:00:00', 0, '1'),
(11, '3', 'Inside Sales Agent', 7, 0, 0, 0, '', 'outbound4@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-08-10 20:18:42', 1, '0000-00-00 00:00:00', 0, '1'),
(12, '3', 'Inside Sales Agent', 8, 0, 0, 0, '', 'outbound6@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 'localhost', 'root', '', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-08-10 20:21:01', 1, '0000-00-00 00:00:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `mail_blast_contact_trans`
--

CREATE TABLE IF NOT EXISTS `mail_blast_contact_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_blast_id` int(11) NOT NULL COMMENT 'From mail blast sent',
  `contact_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mail_blast_id` (`mail_blast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mail_blast_sent`
--

CREATE TABLE IF NOT EXISTS `mail_blast_sent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_out_type` varchar(20) NOT NULL,
  `category_id` int(11) NOT NULL COMMENT 'From category',
  `template_id` int(11) NOT NULL COMMENT 'From template',
  `message` text NOT NULL,
  `size_w` float(8,4) NOT NULL,
  `size_h` float(8,4) NOT NULL,
  `sort_by` varchar(20) NOT NULL,
  `save_type` varchar(20) DEFAULT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login',
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `template_id` (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `marketing_master_lib__category_master`
--

CREATE TABLE IF NOT EXISTS `marketing_master_lib__category_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL COMMENT 'Category name ',
  `parent` int(11) NOT NULL COMMENT '0 for Category, id for subcategory ',
  `superadmin_cat_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From user_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From user_master',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_amenity_data`
--

CREATE TABLE IF NOT EXISTS `mls_amenity_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `property_type` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `value_code` varchar(20) NOT NULL,
  `value_description` varchar(255) NOT NULL,
  `amenity_id` int(11) NOT NULL COMMENT 'Id from mls table',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`),
  KEY `amenity_id` (`amenity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_area_community_data`
--

CREATE TABLE IF NOT EXISTS `mls_area_community_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `area` varchar(10) NOT NULL,
  `community` varchar(255) NOT NULL,
  `area_community_id` int(11) NOT NULL COMMENT 'Id from mls table',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`),
  KEY `area_community_id` (`area_community_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_area_master`
--

CREATE TABLE IF NOT EXISTS `mls_area_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0: Inactive, 1:Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_assign_data`
--

CREATE TABLE IF NOT EXISTS `mls_assign_data` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `mls_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mls_child_table_mapping`
--

CREATE TABLE IF NOT EXISTS `mls_child_table_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `ofiice_table_name` varchar(100) NOT NULL,
  `school_table_name` varchar(100) NOT NULL,
  `member_table_name` varchar(100) NOT NULL,
  `area_community_table_name` varchar(100) NOT NULL,
  `amenity_table_name` varchar(100) NOT NULL,
  `property_history_table_name` varchar(100) NOT NULL,
  `image_table_name` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_csv_mapping_master`
--

CREATE TABLE IF NOT EXISTS `mls_csv_mapping_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_csv_mapping_trans`
--

CREATE TABLE IF NOT EXISTS `mls_csv_mapping_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `csv_mapping_id` int(11) NOT NULL,
  `mls_master_field` varchar(250) NOT NULL,
  `csv_field` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_csv_master`
--

CREATE TABLE IF NOT EXISTS `mls_csv_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `csv_file` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `additional_information` text NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_last_updated_date_data`
--

CREATE TABLE IF NOT EXISTS `mls_last_updated_date_data` (
  `id` int(11) NOT NULL,
  `table_type` tinyint(1) NOT NULL COMMENT '1-property list,2-area community ,3-member,4 - office,5 - school ,6 - amenity,7-property history,8-image',
  `mls_id` int(11) NOT NULL COMMENT 'from mls master',
  `last_updated_date` datetime NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mls_last_updated_offset_data`
--

CREATE TABLE IF NOT EXISTS `mls_last_updated_offset_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_type` tinyint(1) NOT NULL COMMENT '1-property list,2-area community ,3-member,4 - office,5 - school ,6 - amenity,7-property history,8-image',
  `mls_id` int(11) NOT NULL COMMENT 'from mls master',
  `last_updated_offset` int(6) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_livewire_table_mapping`
--

CREATE TABLE IF NOT EXISTS `mls_livewire_table_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `main_table` varchar(100) NOT NULL,
  `child_table1` varchar(200) NOT NULL,
  `child_table2` varchar(200) NOT NULL,
  `child_table3` varchar(200) NOT NULL,
  `child_table4` varchar(200) NOT NULL,
  `child_table5` varchar(200) NOT NULL,
  `child_table6` varchar(200) NOT NULL,
  `child_table7` varchar(200) NOT NULL,
  `child_table8` varchar(200) NOT NULL,
  `child_table9` varchar(200) NOT NULL,
  `child_table10` varchar(200) NOT NULL,
  `child_table11` varchar(200) NOT NULL,
  `child_table12` varchar(200) NOT NULL,
  `child_table13` varchar(200) NOT NULL,
  `child_table14` varchar(200) NOT NULL,
  `child_table15` varchar(200) NOT NULL,
  `child_table16` varchar(200) NOT NULL,
  `child_table17` varchar(200) NOT NULL,
  `child_table18` varchar(200) NOT NULL,
  `child_table19` varchar(200) NOT NULL,
  `child_table20` varchar(200) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_master`
--

CREATE TABLE IF NOT EXISTS `mls_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mls_name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master;',
  `status` tinyint(4) NOT NULL COMMENT '0.Deactive, 1.Active(Default) ',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_member_data`
--

CREATE TABLE IF NOT EXISTS `mls_member_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `member_mls_id` int(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `member_office_mls_id` int(6) NOT NULL,
  `member_office_name` varchar(100) NOT NULL,
  `member_office_area_code` int(6) NOT NULL,
  `member_office_phone` varchar(10) NOT NULL,
  `office_phone_extension` varchar(20) NOT NULL,
  `member_id` int(11) NOT NULL COMMENT 'Id from mls table',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_office_data`
--

CREATE TABLE IF NOT EXISTS `mls_office_data` (
  `id` int(11) NOT NULL,
  `mls_id` int(11) NOT NULL,
  `office_mls_id` int(11) NOT NULL,
  `office_name` varchar(100) NOT NULL,
  `street_care_of` varchar(255) NOT NULL,
  `street_address` varchar(100) NOT NULL,
  `street_city` varchar(50) NOT NULL,
  `street_state` varchar(50) NOT NULL,
  `street_zip_code` varchar(10) NOT NULL,
  `street_zip_plus4` varchar(10) NOT NULL,
  `street_county` varchar(50) NOT NULL,
  `office_area_code` int(8) NOT NULL,
  `office_phone` varchar(10) NOT NULL,
  `fax_area_code` varchar(10) NOT NULL,
  `fax_phone` varchar(15) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `webPage_address` varchar(100) NOT NULL,
  `office_type` varchar(100) NOT NULL,
  `office_id` int(11) NOT NULL COMMENT 'Id from mls table',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  KEY `mls_id` (`mls_id`),
  KEY `office_id` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mls_property_history_data`
--

CREATE TABLE IF NOT EXISTS `mls_property_history_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `property_type` varchar(10) NOT NULL,
  `ml_number` int(11) NOT NULL,
  `list_price` float NOT NULL,
  `change_date` datetime NOT NULL,
  `property_history_id` int(11) NOT NULL COMMENT 'Id from mls table',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`),
  KEY `property_history_id` (`property_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_property_image`
--

CREATE TABLE IF NOT EXISTS `mls_property_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `listing_number` int(11) NOT NULL COMMENT 'from mls_property_list_master',
  `image_name` varchar(100) NOT NULL,
  `image_height` varchar(50) NOT NULL,
  `image_width` varchar(50) NOT NULL,
  `image_desc` text NOT NULL,
  `last_modified_date` datetime NOT NULL,
  `Image_id` int(11) NOT NULL,
  `image_url` mediumtext NOT NULL,
  `image_big_url` text COMMENT 'Big image(970px)',
  `image_medium_url` mediumtext NOT NULL,
  `image_small_url` mediumtext NOT NULL,
  `upload_date` datetime NOT NULL,
  `mls_image_id` int(11) NOT NULL COMMENT 'Id from mls table',
  `image_no` int(3) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`),
  KEY `mls_image_id` (`mls_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_property_list_master`
--

CREATE TABLE IF NOT EXISTS `mls_property_list_master` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `LN` int(10) unsigned NOT NULL COMMENT 'Listing Number',
  `Internal_MLS_ID` varchar(100) NOT NULL COMMENT 'MLS ID only for florida mls',
  `PTYP` varchar(4) NOT NULL COMMENT 'Property Type',
  `LAG` int(10) unsigned NOT NULL COMMENT 'Listing Agent Number',
  `HSN` int(10) unsigned NOT NULL COMMENT 'House Number',
  `DRP` varchar(4) NOT NULL COMMENT 'Directional Prefix',
  `STR` varchar(30) NOT NULL COMMENT 'Street',
  `SSUF` varchar(6) NOT NULL COMMENT 'Street Suffix',
  `DRS` varchar(4) NOT NULL COMMENT 'Directional Suffix',
  `UNT` varchar(5) NOT NULL COMMENT 'Unit',
  `STA` varchar(2) NOT NULL COMMENT 'State',
  `ZIP` varchar(5) NOT NULL COMMENT 'Zip Code',
  `full_address` varchar(400) NOT NULL COMMENT 'Full address',
  `CLA` int(11) unsigned NOT NULL COMMENT 'Co-Listing Agent Number',
  `LO` int(10) unsigned NOT NULL COMMENT 'Listing Office Number',
  `COLO` int(10) unsigned NOT NULL COMMENT 'Co Office Number',
  `ST` varchar(5) NOT NULL COMMENT 'Status',
  `CIT` varchar(21) NOT NULL COMMENT 'City',
  `BR` float NOT NULL COMMENT 'Bedrooms',
  `BTH` float NOT NULL COMMENT 'Bathrooms',
  `ASF` int(10) unsigned NOT NULL COMMENT 'Approximate Square Footage',
  `UD` datetime NOT NULL COMMENT 'Update Date',
  `AR` int(10) unsigned NOT NULL COMMENT 'Area',
  `LD` datetime NOT NULL COMMENT 'List Date',
  `YBT` int(10) unsigned NOT NULL COMMENT 'Year Built',
  `DD` varchar(250) NOT NULL COMMENT 'Directions',
  `AVDT` datetime NOT NULL COMMENT 'Available Date',
  `COU` varchar(21) NOT NULL COMMENT 'County',
  `LP` float NOT NULL COMMENT 'List Price',
  `SP` float NOT NULL COMMENT 'Sold Price',
  `display_price` float NOT NULL COMMENT 'Price',
  `MR` text NOT NULL COMMENT 'Property Description',
  `SNR` text NOT NULL COMMENT 'Senior Housing',
  `CDOM` int(10) unsigned NOT NULL COMMENT 'Cumulative Days on Market',
  `SD` varchar(80) NOT NULL COMMENT 'School District Code',
  `DSR` varchar(40) NOT NULL COMMENT 'Community Name',
  `ADU` varchar(1) NOT NULL COMMENT 'Approved Accessory Dwelling',
  `ARC` varchar(1) NOT NULL COMMENT 'Architecture',
  `BDL` int(10) unsigned NOT NULL COMMENT 'Bedrooms Lower',
  `BDU` int(10) unsigned NOT NULL COMMENT 'Bedrooms Upper',
  `BLD` varchar(40) NOT NULL COMMENT 'Builder',
  `BLK` varchar(40) NOT NULL COMMENT 'Block',
  `BUS` varchar(1) NOT NULL COMMENT 'Bus Line Nearby',
  `EL` varchar(20) NOT NULL COMMENT 'Elementary School',
  `SH` varchar(20) NOT NULL COMMENT 'High School',
  `JH` varchar(20) NOT NULL COMMENT 'Junior High',
  `FP` int(10) unsigned NOT NULL COMMENT 'Fireplaces Total',
  `GAR` int(10) unsigned NOT NULL COMMENT 'Total Covered Parking',
  `HOD` int(10) unsigned NOT NULL COMMENT 'Annual Association Dues',
  `KES` varchar(1) NOT NULL COMMENT 'Kitchen with Eating Space Location',
  `LRM` varchar(1) NOT NULL COMMENT 'Living Room Location',
  `LSD` varchar(40) NOT NULL COMMENT 'Leased Equipment',
  `LSZ` varchar(40) NOT NULL COMMENT 'Lot Dimensions',
  `LT` varchar(40) NOT NULL COMMENT 'Lot Number',
  `MBD` varchar(1) NOT NULL COMMENT 'Master Bedroom Location',
  `MHM` varchar(40) NOT NULL COMMENT 'Manufactured Home Manufacturer',
  `MHN` varchar(40) NOT NULL COMMENT 'Manufactured Home Model Name',
  `MHS` varchar(40) NOT NULL COMMENT 'Manufactured Home Serial Number',
  `MOR` int(10) unsigned NOT NULL COMMENT 'Monthly Rent if Rented',
  `POC` varchar(40) NOT NULL COMMENT 'Power Company',
  `CMFE` varchar(100) NOT NULL COMMENT 'Community Features',
  `SFF` int(10) unsigned NOT NULL COMMENT 'Square Footage Finished',
  `SAP` int(10) DEFAULT NULL COMMENT 'Septic Approved for Number of Bedrooms',
  `SFS` varchar(40) NOT NULL COMMENT 'Square Footage Source',
  `SFU` int(10) unsigned NOT NULL COMMENT 'Square Footage Unfinished',
  `SWC` varchar(40) NOT NULL COMMENT 'Sewer Company',
  `TX` int(10) unsigned NOT NULL COMMENT 'Tax Amount',
  `TXY` int(10) unsigned NOT NULL COMMENT 'Tax Year',
  `WAC` varchar(40) NOT NULL COMMENT 'Water Company',
  `WFG` varchar(40) NOT NULL COMMENT 'Waterfront Footage',
  `WHT` varchar(40) NOT NULL COMMENT 'Water Heater Location',
  `APS` varchar(100) NOT NULL COMMENT 'Appliances That Stay',
  `BDI` varchar(100) NOT NULL COMMENT 'Building Information',
  `BSM` varchar(100) NOT NULL COMMENT 'Basement',
  `EXT` varchar(100) NOT NULL COMMENT 'Exterior',
  `FEA` varchar(100) NOT NULL COMMENT 'Interior Features',
  `FLS` varchar(100) NOT NULL COMMENT 'Floor Covering',
  `FND` varchar(100) NOT NULL COMMENT 'Foundation',
  `GR` varchar(100) NOT NULL COMMENT 'Parking Type',
  `LDE` varchar(100) NOT NULL COMMENT 'Lot Details',
  `LTV` varchar(100) NOT NULL COMMENT 'Lot Topography/Vegetation',
  `RF` varchar(100) NOT NULL COMMENT 'Roof',
  `RF1` varchar(150) NOT NULL COMMENT 'Roof',
  `RoofType` varchar(150) NOT NULL COMMENT 'Roof Type',
  `RoofType1` varchar(150) NOT NULL COMMENT 'Roof Type',
  `SIT` varchar(100) NOT NULL COMMENT 'Site Features',
  `SWR` varchar(100) NOT NULL COMMENT 'Sewer',
  `VEW` varchar(100) NOT NULL COMMENT 'View',
  `VEW1` varchar(150) NOT NULL COMMENT 'View',
  `VEW2` varchar(150) NOT NULL COMMENT 'View',
  `WAS` varchar(100) NOT NULL COMMENT 'Water Source',
  `WFT` varchar(100) NOT NULL COMMENT 'Waterfront',
  `WFT1` varchar(150) NOT NULL COMMENT 'Waterfront',
  `WFT2` varchar(150) NOT NULL COMMENT 'Waterfront',
  `WFT3` varchar(150) NOT NULL COMMENT 'Waterfront',
  `BUSR` varchar(20) NOT NULL COMMENT 'Bus Route Number',
  `ECRT` varchar(100) NOT NULL COMMENT 'Environmental Cert',
  `ZJD` varchar(100) NOT NULL COMMENT 'Zoning Jurisdiction',
  `ZNC` varchar(20) NOT NULL COMMENT 'Zoning Code',
  `PARQ` varchar(100) NOT NULL COMMENT 'Third Party Approval Required',
  `BREO` varchar(1) NOT NULL COMMENT 'Bank Owned',
  `BuiltGreenRating` varchar(32) NOT NULL COMMENT 'Built Green Certification Rating',
  `LEEDRating` varchar(32) NOT NULL COMMENT 'LEED Certification Rating',
  `NewConstruction` varchar(1) NOT NULL COMMENT 'New Construction',
  `EMP` int(10) unsigned NOT NULL COMMENT 'Number of Employees',
  `EQU` int(10) unsigned NOT NULL COMMENT 'Equipment',
  `EQV` int(10) unsigned NOT NULL COMMENT 'Equipment Value',
  `FRN` varchar(1) NOT NULL COMMENT 'Franchise',
  `GRS` int(10) unsigned NOT NULL COMMENT 'Annual Gross Sales',
  `GW` int(10) unsigned NOT NULL COMMENT 'Goodwill Value',
  `INV` int(10) unsigned NOT NULL COMMENT 'Inventory Value',
  `LNM` varchar(1) NOT NULL COMMENT 'Liens/Mortgages',
  `LSI` varchar(1) NOT NULL COMMENT 'Lease Income',
  `NA` varchar(40) NOT NULL COMMENT 'Business Name',
  `NP` int(10) unsigned NOT NULL COMMENT 'Net Proceeds',
  `PKU` int(10) unsigned NOT NULL COMMENT 'Total Uncovered Parking',
  `RES` int(10) unsigned NOT NULL COMMENT 'Real Estate Value',
  `RNT` int(10) unsigned NOT NULL COMMENT 'Annual Rent',
  `SIN` varchar(1) NOT NULL COMMENT 'Signage',
  `TEXP` int(10) unsigned NOT NULL COMMENT 'Annual Expenses',
  `TOB` varchar(40) NOT NULL COMMENT 'Type of Business',
  `LES` varchar(100) NOT NULL COMMENT 'Lease Terms',
  `LIC` varchar(100) NOT NULL COMMENT 'Licenses',
  `LIC1` varchar(150) NOT NULL COMMENT 'Licenses',
  `LOC` varchar(100) NOT NULL COMMENT 'Location',
  `LOC1` varchar(150) NOT NULL COMMENT 'Location',
  `LOC2` varchar(150) NOT NULL COMMENT 'Location',
  `MTB` varchar(100) NOT NULL COMMENT 'Major Type of Business',
  `LSZS` varchar(100) NOT NULL COMMENT 'Acres',
  `COO` varchar(1) NOT NULL COMMENT 'Co-op Yes or No',
  `NAS` int(10) unsigned NOT NULL COMMENT 'Number of Assigned Spaces',
  `NOC` int(10) unsigned NOT NULL COMMENT 'Number of Units in Complex',
  `NOS` int(10) unsigned NOT NULL COMMENT 'Number of Stories in Building',
  `NOU` int(10) unsigned NOT NULL COMMENT 'Number of Units',
  `OOC` int(10) unsigned NOT NULL COMMENT 'Owner Occupancy Percentage',
  `PKS` varchar(50) NOT NULL COMMENT 'Parking Space Number',
  `REM` varchar(1) NOT NULL COMMENT 'Remodeled',
  `SAA` int(10) unsigned NOT NULL COMMENT 'Special Assessment Amount',
  `SPA` varchar(1) NOT NULL COMMENT 'Special Assessment',
  `STL` varchar(50) NOT NULL COMMENT 'Storage Location',
  `TOF` varchar(1) NOT NULL COMMENT 'Type of Fireplace',
  `UFN` int(10) unsigned NOT NULL COMMENT 'Unit Floor Number',
  `APH` varchar(100) NOT NULL COMMENT 'Appliance Hookups',
  `CMN` varchar(100) NOT NULL COMMENT 'Common Features',
  `UNF` varchar(100) NOT NULL COMMENT 'Unit Features',
  `STRS` float NOT NULL COMMENT 'Number of Access Stairs',
  `STO` varchar(1) NOT NULL COMMENT 'Storage',
  `TMC` varchar(100) NOT NULL COMMENT 'Terms and Conditions',
  `TMC1` varchar(150) NOT NULL COMMENT 'Terms',
  `TMC2` varchar(150) NOT NULL COMMENT 'Terms',
  `TMC3` varchar(150) NOT NULL COMMENT 'Terms',
  `ELE` varchar(1) NOT NULL COMMENT 'Electricity',
  `ESM` varchar(40) NOT NULL COMMENT 'Easements',
  `GAS` varchar(1) NOT NULL COMMENT 'Gas',
  `LVL` varchar(40) NOT NULL COMMENT 'Level',
  `RD` varchar(40) NOT NULL COMMENT 'Road On Which Side of Property',
  `SDA` varchar(1) NOT NULL COMMENT 'Septic Designed and Applied for',
  `SEP` varchar(1) NOT NULL COMMENT 'Septic System Installed',
  `SFA` varchar(1) NOT NULL COMMENT 'Soils Feasibility Available',
  `SLP` varchar(40) NOT NULL COMMENT 'Slope of Property',
  `SUR` varchar(40) NOT NULL COMMENT 'Survey Information',
  `TER` varchar(40) NOT NULL COMMENT 'Terms Remarks',
  `WRJ` varchar(40) NOT NULL COMMENT 'Water Jurisdiction',
  `ZNR` varchar(20) NOT NULL COMMENT 'Zoning Remarks',
  `FTR` varchar(100) NOT NULL COMMENT 'Property Features',
  `GZC` varchar(100) NOT NULL COMMENT 'General Zoning Classification',
  `RDI` varchar(100) NOT NULL COMMENT 'Road Information',
  `RS2` varchar(100) NOT NULL COMMENT 'Restrictions',
  `RS21` varchar(150) NOT NULL COMMENT 'Restrictions',
  `TPO` varchar(100) NOT NULL COMMENT 'Topography',
  `CAP` float DEFAULT NULL COMMENT 'Cap Rate',
  `ELEX` int(10) unsigned DEFAULT NULL COMMENT 'Electrical Expenses',
  `GAI` int(10) unsigned DEFAULT NULL COMMENT 'Gross Adjusted Income',
  `GRM` int(10) unsigned DEFAULT NULL COMMENT 'Gross Rent Multiplier',
  `GSI` int(10) unsigned DEFAULT NULL COMMENT 'Gross Scheduled Income',
  `GSP` int(10) unsigned DEFAULT NULL COMMENT 'Number of Garage Spaces',
  `HET` int(10) unsigned DEFAULT NULL COMMENT 'Heating Expenses',
  `INS` int(10) unsigned DEFAULT NULL COMMENT 'Insurance Expenses',
  `NCS` int(10) unsigned DEFAULT NULL COMMENT 'Number of Carport Spaces',
  `NOI` int(10) unsigned DEFAULT NULL COMMENT 'Net Operating Income',
  `OTX` int(10) unsigned DEFAULT NULL COMMENT 'Other Expenses',
  `TEX` int(10) unsigned DEFAULT NULL COMMENT 'Total Expenses',
  `TIN` int(10) unsigned DEFAULT NULL COMMENT 'Total Monthly Income',
  `TSP` int(10) unsigned DEFAULT NULL COMMENT 'Total Number of Parking Spaces',
  `UBG` varchar(1) DEFAULT NULL COMMENT 'Units Below Grade',
  `USP` int(10) unsigned DEFAULT NULL COMMENT 'Number of Uncovered Spaces',
  `VAC` int(10) unsigned DEFAULT NULL COMMENT 'Vacancy Rate',
  `WSG` int(10) unsigned DEFAULT NULL COMMENT 'Water/Sewer/Garbage',
  `AMP` int(10) unsigned DEFAULT NULL COMMENT 'Power Service in AMPS',
  `AVP` int(10) unsigned DEFAULT NULL COMMENT 'Number of Available Pads',
  `BON` varchar(1) DEFAULT NULL COMMENT 'Boundary Survey',
  `CHT` varchar(40) DEFAULT NULL COMMENT 'Ceiling Height',
  `CHT1` varchar(150) NOT NULL COMMENT 'Ceiling',
  `DLT` int(10) unsigned DEFAULT NULL COMMENT 'Depth of Lot',
  `ENV` varchar(1) DEFAULT NULL COMMENT 'Environmental Survey',
  `EXA` varchar(1) DEFAULT NULL COMMENT 'Expansion Area',
  `NNN` int(10) unsigned DEFAULT NULL COMMENT 'Total Monthly NNN',
  `OSF` int(10) unsigned DEFAULT NULL COMMENT 'Approximate Office Square Feet',
  `PAD` varchar(1) DEFAULT NULL COMMENT 'Pad Ready',
  `STF` int(10) unsigned DEFAULT NULL COMMENT 'Site Frontage',
  `TRI` int(10) unsigned DEFAULT NULL COMMENT 'Total Monthly Rent',
  `TSF` int(10) unsigned DEFAULT NULL COMMENT 'Total Square Feet Rented',
  `VAI` int(10) unsigned DEFAULT NULL COMMENT 'Improved Assessed Value',
  `VAL` int(10) unsigned DEFAULT NULL COMMENT 'Land Assessed Value',
  `WSF` int(10) unsigned DEFAULT NULL COMMENT 'Approximate Whse/Mfg Square Feet',
  `YVA` int(10) unsigned DEFAULT NULL COMMENT 'Year Value Assessed',
  `LDG` varchar(100) DEFAULT NULL COMMENT 'Loading',
  `ACC` varchar(40) DEFAULT NULL COMMENT 'Acreage Comments',
  `BCC` varchar(40) DEFAULT NULL COMMENT 'Barn/Outbuilding Comments',
  `BRI` int(10) unsigned DEFAULT NULL COMMENT 'Boarding Income',
  `BSZ` varchar(40) DEFAULT NULL COMMENT 'Barn Size',
  `CCC` varchar(40) DEFAULT NULL COMMENT 'Crop & Soil Comments',
  `CRI` int(10) unsigned DEFAULT NULL COMMENT 'Crop Income',
  `EQI` int(10) unsigned DEFAULT NULL COMMENT 'Equity',
  `LCC` varchar(40) DEFAULT NULL COMMENT 'Livestock Comments',
  `IRRC` varchar(40) DEFAULT NULL COMMENT 'Irrigation Comments',
  `PSZ` varchar(40) DEFAULT NULL COMMENT 'Parlor Size',
  `SSZ` varchar(40) DEFAULT NULL COMMENT 'Storage Size',
  `TAC` int(10) unsigned DEFAULT NULL COMMENT 'Till Acres',
  `VCC` varchar(40) DEFAULT NULL COMMENT 'View Comments',
  `BFE` varchar(100) DEFAULT NULL COMMENT 'Barn Features',
  `BTP` varchar(100) DEFAULT NULL COMMENT 'Barn Type',
  `FEN` varchar(100) DEFAULT NULL COMMENT 'Fence',
  `FTP` varchar(100) DEFAULT NULL COMMENT 'Farm Type',
  `IRS` varchar(100) DEFAULT NULL COMMENT 'Irrigation Source',
  `ITP` varchar(100) DEFAULT NULL COMMENT 'Irrigation Type',
  `LTP` varchar(100) DEFAULT NULL COMMENT 'Livestock Type',
  `OUT1` varchar(100) DEFAULT NULL COMMENT 'Outbuildings',
  `STP` varchar(100) DEFAULT NULL COMMENT 'Soil Type',
  `ELEV` varchar(20) DEFAULT NULL COMMENT 'Elevation',
  `LNI` varchar(1) DEFAULT NULL COMMENT 'Labor and Industries Inspected',
  `MFY` varchar(1) DEFAULT NULL COMMENT 'Manufactured After 1976',
  `NOH` int(10) unsigned DEFAULT NULL COMMENT 'Number of Homes in Park',
  `PAS` varchar(1) DEFAULT NULL COMMENT 'Park For Sale',
  `PRK` varchar(40) DEFAULT NULL COMMENT 'Park Name',
  `SKR` varchar(40) DEFAULT NULL COMMENT 'Skirting Material',
  `SPR` int(10) unsigned DEFAULT NULL COMMENT 'Space Rent Per Month',
  `UCS` varchar(1) DEFAULT NULL COMMENT 'Unit Can Stay in Park After Sale',
  `MHF` varchar(100) DEFAULT NULL COMMENT 'Manufactured Home Features',
  `OTR` varchar(100) DEFAULT NULL COMMENT 'Other Rooms',
  `PKA` varchar(100) DEFAULT NULL COMMENT 'Park Amenities',
  `SRI` varchar(100) DEFAULT NULL COMMENT 'Space Rent Includes',
  `LONGI` float NOT NULL COMMENT 'Longitude',
  `LAT` float NOT NULL COMMENT 'Latitude',
  `CLO` datetime NOT NULL COMMENT 'Sold Date',
  `IMP` varchar(100) NOT NULL COMMENT 'Improvements',
  `OLP` float NOT NULL COMMENT 'Orginial Listing Price',
  `TAX` varchar(40) NOT NULL COMMENT 'Parcel Number',
  `PIC` int(10) unsigned NOT NULL COMMENT 'Pictures',
  `POS` varchar(100) NOT NULL COMMENT 'Possession',
  `POS1` varchar(150) NOT NULL COMMENT 'Possession',
  `POS2` varchar(150) NOT NULL COMMENT 'Possession',
  `POS3` varchar(150) NOT NULL COMMENT 'Possession',
  `STY` varchar(2) NOT NULL COMMENT 'Style',
  `STY1` varchar(150) NOT NULL COMMENT 'Style',
  `VIRT` varchar(200) NOT NULL COMMENT 'Virtual Tour URL',
  `SIZ` int(10) unsigned NOT NULL COMMENT 'Approx Building SqFt',
  `ENS` varchar(100) NOT NULL COMMENT 'Energy Source',
  `YRE` int(10) unsigned NOT NULL COMMENT 'Year Established',
  `ATF` varchar(100) NOT NULL COMMENT 'Assessment Fees',
  `POL` varchar(10) NOT NULL COMMENT 'Pool',
  `TAV` int(11) NOT NULL COMMENT 'Total Assessed Value',
  `property_id` int(11) NOT NULL,
  `TotalUnits` int(11) NOT NULL COMMENT 'Number of Units in Building',
  `BathsHalf` int(11) NOT NULL COMMENT '1/2 Bathrooms',
  `BathsForth` int(11) NOT NULL COMMENT '1/4 Bathrooms',
  `BathsThird` int(11) NOT NULL COMMENT '3/4 Bathrooms',
  `petsYN` varchar(55) NOT NULL COMMENT 'Cats & Dogs',
  `DaysOnMarket` int(11) NOT NULL COMMENT 'Days on Market',
  `ExteriorFeatures1` varchar(150) NOT NULL COMMENT 'Exterior Features',
  `PublicRemarks1` text NOT NULL COMMENT 'Property Description',
  `SqFtLevel1` int(11) NOT NULL COMMENT 'Sq Ft Level 1',
  `SqFtLevel2` int(11) NOT NULL COMMENT 'Sq Ft Level 2',
  `SqFtLevel3` int(11) NOT NULL COMMENT 'Sq Ft Level 3',
  `AdditionalStatus` varchar(140) NOT NULL COMMENT 'Additional Status',
  `BasementSqFtFinished` int(11) NOT NULL COMMENT 'Basement Sq Ft Finished',
  `BasementSqFtUnfinished` int(11) NOT NULL COMMENT 'Basement Sq Ft Unfinished',
  `Cooling` varchar(140) NOT NULL COMMENT 'Cooling',
  `Cooling1` varchar(150) NOT NULL COMMENT 'Cooling',
  `Cooling2` varchar(150) NOT NULL COMMENT 'Cooling',
  `Cooling3` varchar(150) NOT NULL COMMENT 'Cooling',
  `InsideCityLimitsYN` varchar(55) NOT NULL COMMENT 'Inside City Limits',
  `Porch` varchar(140) NOT NULL COMMENT 'Porch',
  `StateRdYN` varchar(55) NOT NULL COMMENT 'State Road',
  `WheelChairAccessYN` varchar(55) NOT NULL COMMENT 'Wheel Chair Accessible',
  `BusinessType` varchar(150) NOT NULL COMMENT 'Business Type',
  `Basement` varchar(105) NOT NULL COMMENT 'Basement',
  `Heating` varchar(135) NOT NULL COMMENT 'Heating',
  `Flooring` varchar(150) NOT NULL COMMENT 'Flooring',
  `Flooring1` varchar(150) NOT NULL COMMENT 'Flooring',
  `Flooring2` varchar(150) NOT NULL COMMENT 'Flooring',
  `Heating1` varchar(150) NOT NULL COMMENT 'Heating',
  `Heating2` varchar(150) NOT NULL COMMENT 'Heating',
  `Heating3` varchar(150) NOT NULL COMMENT 'Heating',
  `WoodedAcres` varchar(60) NOT NULL COMMENT 'Wooded Acres',
  `MfgHomesAllowedYN` varchar(100) NOT NULL COMMENT 'Manufactured Homes Allowed',
  `SuitableUse` varchar(100) NOT NULL COMMENT 'Suitable Use',
  `Foundation` varchar(100) NOT NULL COMMENT 'Manufactured Foundation',
  `SoldPricePerSqFt` varchar(65) NOT NULL COMMENT '$ sq/ft',
  `HOARentIncludes` text NOT NULL COMMENT 'Association Fee Includes',
  `HOAPaymentFreq` varchar(60) NOT NULL COMMENT 'Association Payment Freq.',
  `ParkingDescription1` varchar(150) NOT NULL COMMENT 'Parking',
  `SqFtLowerLevelTotal` int(11) NOT NULL COMMENT 'Sq/Ft Lower Level',
  `SqFtMainLevelTotal` int(11) NOT NULL COMMENT 'Sq/Ft Main Level',
  `SqFtUpperLevelTotal` int(11) NOT NULL COMMENT 'Sq/Ft Upper Level',
  `SqFtApximateManufacturing` int(11) NOT NULL COMMENT 'Approx Manufacturing Sq/Ft',
  `SqFtApproximateWarehouse` int(11) NOT NULL COMMENT 'Approx Warehouse Sq/Ft',
  `SaleIncludes` varchar(140) NOT NULL COMMENT 'Sale Includes',
  `SaleIncludes1` varchar(150) NOT NULL COMMENT 'Sale Includes',
  `RoadFrontage` int(11) NOT NULL COMMENT 'Road Frontage',
  `Stories` int(11) NOT NULL COMMENT 'Stories',
  `Construction` text NOT NULL COMMENT 'Construction',
  `Utilities` text NOT NULL COMMENT 'Utilities',
  `Utilities1` varchar(150) NOT NULL COMMENT 'Utilities',
  `Utilities2` varchar(150) NOT NULL COMMENT 'Utilities',
  `Utilities3` varchar(150) NOT NULL COMMENT 'Utilities',
  `SqFtApproximateGross` int(11) NOT NULL COMMENT 'Gross Sq/Ft',
  `Features` text NOT NULL COMMENT 'Features',
  `Acres` varchar(65) NOT NULL COMMENT 'Acres',
  `NumberOfLotsTotal` int(11) NOT NULL COMMENT 'Number of Lots',
  `HOAYN` varchar(55) NOT NULL COMMENT 'HOA',
  `PropertyCategory` varchar(70) NOT NULL COMMENT 'Property Category',
  `AccessibilityFeatures` text NOT NULL COMMENT 'Accesibility Features',
  `LotDescription` varchar(150) NOT NULL COMMENT 'Lot Description',
  `LotDescription1` varchar(150) NOT NULL COMMENT 'Lot Description',
  `SqftLiving` varchar(60) NOT NULL COMMENT 'SqFt - Living',
  `SqftGuestHouse` varchar(60) NOT NULL COMMENT 'SqFt - Guest House',
  `FrontExposure` varchar(150) NOT NULL COMMENT 'Front Exposure',
  `Subdivision` varchar(150) NOT NULL COMMENT 'Subdivision',
  `HOPA` varchar(150) NOT NULL COMMENT 'HOPA',
  `ModelName` varchar(80) NOT NULL COMMENT 'Model Name',
  `PetsAllowed` varchar(150) NOT NULL COMMENT 'Pets Allowed',
  `ApplicationFee` varchar(65) NOT NULL COMMENT 'Application Fee',
  `DevelopmentName` varchar(100) NOT NULL COMMENT 'Development Name',
  `BoatServices` varchar(150) NOT NULL COMMENT 'Boat Services',
  `EquestrianFeatures` varchar(150) NOT NULL COMMENT 'Equestrian Features',
  `Furnished` varchar(150) NOT NULL COMMENT 'Furnished',
  `GuestHouse` varchar(150) NOT NULL COMMENT 'Guest House',
  `UtilitiesonSite` varchar(150) NOT NULL COMMENT 'Utilities on Site',
  `ForLease` varchar(150) NOT NULL COMMENT 'For Lease',
  `ForSale` varchar(70) NOT NULL COMMENT 'For Sale',
  `TotalBuildingSqFt` varchar(60) NOT NULL COMMENT 'Total Building SqFt',
  `Offices` int(11) NOT NULL COMMENT 'Offices',
  `Bays` int(11) NOT NULL COMMENT 'Bays',
  `LoadingDocks` int(11) NOT NULL COMMENT 'Loading Docks',
  `SqFtIncluded` varchar(60) NOT NULL COMMENT 'SqFt Included',
  `SqFtOccupied` varchar(60) NOT NULL COMMENT 'SqFt  - Occupied',
  `Training` varchar(150) NOT NULL COMMENT 'Training',
  `Road` varchar(150) NOT NULL COMMENT 'Road',
  `TypeBuilding` varchar(150) NOT NULL COMMENT 'Type of Building',
  `mls_type_id` int(11) NOT NULL,
  `old_status` varchar(5) NOT NULL COMMENT 'Old Status (ST)',
  `is_status_change` tinyint(4) NOT NULL COMMENT '1: Yes, 0: No',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0 as deactive and 1 as active',
  `LngLatCoords` point NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `property_id` (`property_id`),
  KEY `mls_type_id` (`mls_type_id`),
  KEY `mls_id` (`mls_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_property_type`
--

CREATE TABLE IF NOT EXISTS `mls_property_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comment` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '''0'' for deactive and ''1'' for active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `mls_property_type`
--

INSERT INTO `mls_property_type` (`id`, `name`, `comment`, `created_date`, `status`) VALUES
(1, 'RESI', 'Residential', '2015-05-27 00:00:00', '1'),
(2, 'COND', 'Condominium', '2015-05-27 00:00:00', '1'),
(3, 'MULT', 'Multi-Family', '2015-05-27 00:00:00', '1'),
(4, 'MANU', 'Manufactured Home', '2015-05-27 00:00:00', '1'),
(5, 'FARM', 'Farm/Ranch', '2015-05-27 00:00:00', '1'),
(6, 'VACL', 'Vacant Land', '2015-05-27 00:00:00', '1'),
(7, 'COMI', 'Commercial', '2015-05-27 00:00:00', '1'),
(8, 'BUSO', 'Business Opportunity', '2015-05-27 00:00:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `mls_school_data`
--

CREATE TABLE IF NOT EXISTS `mls_school_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `school_district_code` varchar(150) NOT NULL,
  `school_district_description` varchar(150) NOT NULL,
  `school_id` int(11) NOT NULL COMMENT 'Id from mls table',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mls_id` (`mls_id`),
  KEY `school_id` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_status_master`
--

CREATE TABLE IF NOT EXISTS `mls_status_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0: Inactive, 1:Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_type_of_mls_mapping_master`
--

CREATE TABLE IF NOT EXISTS `mls_type_of_mls_mapping_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `master_field_name` varchar(50) NOT NULL,
  `field_comment` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=358 ;

--
-- Dumping data for table `mls_type_of_mls_mapping_master`
--

INSERT INTO `mls_type_of_mls_mapping_master` (`id`, `master_field_name`, `field_comment`, `created_date`, `created_by`, `status`) VALUES
(1, 'LN', 'Listing Number', '2015-06-11 06:18:11', 1, 1),
(2, 'PTYP', 'Property Type', '2015-06-11 06:18:11', 1, 1),
(3, 'LAG', 'Listing Agent Number', '2015-06-11 06:18:11', 1, 1),
(4, 'HSN', 'House Number', '2015-06-11 06:18:12', 1, 1),
(5, 'DRP', 'Directional Prefix', '2015-06-11 06:18:12', 1, 1),
(6, 'STR', 'Street', '2015-06-11 06:18:12', 1, 1),
(7, 'SSUF', 'Street Suffix', '2015-06-11 06:18:12', 1, 1),
(8, 'DRS', 'Directional Suffix', '2015-06-11 06:18:12', 1, 1),
(9, 'UNT', 'Unit', '2015-06-11 06:18:12', 1, 1),
(10, 'STA', 'State', '2015-06-11 06:18:12', 1, 1),
(11, 'ZIP', 'Zip Code', '2015-06-11 06:18:12', 1, 1),
(12, 'full_address', 'Full address', '2015-06-11 06:18:12', 1, 1),
(13, 'CLA', 'Co-Listing Agent Number', '2015-06-11 06:18:12', 1, 1),
(14, 'LO', 'Listing Office Number', '2015-06-11 06:18:12', 1, 1),
(15, 'COLO', 'Co Office Number', '2015-06-11 06:18:12', 1, 1),
(16, 'ST', 'Status', '2015-06-11 06:18:12', 1, 1),
(17, 'CIT', 'City', '2015-06-11 06:18:12', 1, 1),
(18, 'BR', 'Bedrooms', '2015-06-11 06:18:12', 1, 1),
(19, 'BTH', 'Bathrooms', '2015-06-11 06:18:12', 1, 1),
(20, 'ASF', 'Approximate Square Footage', '2015-06-11 06:18:12', 1, 1),
(21, 'UD', 'Update Date', '2015-06-11 06:18:12', 1, 1),
(22, 'AR', 'Area', '2015-06-11 06:18:12', 1, 1),
(23, 'LD', 'List Date', '2015-06-11 06:18:12', 1, 1),
(24, 'YBT', 'Year Built', '2015-06-11 06:18:12', 1, 1),
(25, 'DD', 'Directions', '2015-06-11 06:18:12', 1, 1),
(26, 'AVDT', 'Available Date', '2015-06-11 06:18:12', 1, 1),
(27, 'COU', 'County', '2015-06-11 06:18:12', 1, 1),
(28, 'LP', 'List Price', '2015-06-11 06:18:12', 1, 1),
(29, 'SP', 'Sold Price', '2015-06-11 06:18:12', 1, 1),
(30, 'display_price', 'Price', '2015-06-23 00:00:00', 1, 1),
(31, 'CDOM', 'Cumulative Days on Market', '2015-06-11 06:18:12', 1, 1),
(32, 'SD', 'School District Code', '2015-06-11 06:18:12', 1, 1),
(33, 'DSR', 'Community Name', '2015-06-11 06:18:12', 1, 1),
(34, 'ADU', 'Approved Accessory Dwelling', '2015-06-11 06:18:12', 1, 1),
(35, 'ARC', 'Architecture', '2015-06-11 06:18:12', 1, 1),
(36, 'BDL', 'Bedrooms Lower', '2015-06-11 06:18:13', 1, 1),
(37, 'BDU', 'Bedrooms Upper', '2015-06-11 06:18:13', 1, 1),
(38, 'BLD', 'Builder', '2015-06-11 06:18:13', 1, 1),
(39, 'BLK', 'Block', '2015-06-11 06:18:13', 1, 1),
(40, 'BUS', 'Bus Line Nearby', '2015-06-11 06:18:13', 1, 1),
(41, 'EL', 'Elementary School', '2015-06-11 06:18:13', 1, 1),
(42, 'SH', 'High School', '2015-06-11 06:18:13', 1, 1),
(43, 'JH', 'Junior High', '2015-06-11 06:18:13', 1, 1),
(44, 'FP', 'Fireplaces Total', '2015-06-11 06:18:13', 1, 1),
(45, 'GAR', 'Total Covered Parking', '2015-06-11 06:18:13', 1, 1),
(46, 'HOD', 'Annual Association Dues', '2015-06-11 06:18:13', 1, 1),
(47, 'KES', 'Kitchen with Eating Space Location', '2015-06-11 06:18:13', 1, 1),
(48, 'LRM', 'Living Room Location', '2015-06-11 06:18:13', 1, 1),
(49, 'LSD', 'Leased Equipment', '2015-06-11 06:18:13', 1, 1),
(50, 'LSZ', 'Lot Dimensions', '2015-06-11 06:18:13', 1, 1),
(51, 'LT', 'Lot Number', '2015-06-11 06:18:13', 1, 1),
(52, 'MBD', 'Master Bedroom Location', '2015-06-11 06:18:13', 1, 1),
(53, 'MHM', 'Manufactured Home Manufacturer', '2015-06-11 06:18:13', 1, 1),
(54, 'MHN', 'Manufactured Home Model Name', '2015-06-11 06:18:13', 1, 1),
(55, 'MHS', 'Manufactured Home Serial Number', '2015-06-11 06:18:13', 1, 1),
(56, 'MOR', 'Monthly Rent if Rented', '2015-06-11 06:18:13', 1, 1),
(57, 'POC', 'Power Company', '2015-06-11 06:18:13', 1, 1),
(58, 'CMFE', 'Community Features', '2015-06-11 06:18:13', 1, 1),
(59, 'SFF', 'Square Footage Finished', '2015-06-11 06:18:13', 1, 1),
(60, 'SAP', 'Septic Approved for Number of Bedrooms', '2015-06-11 06:18:13', 1, 1),
(61, 'SFS', 'Square Footage Source', '2015-06-11 06:18:13', 1, 1),
(62, 'SFU', 'Square Footage Unfinished', '2015-06-11 06:18:13', 1, 1),
(63, 'SWC', 'Sewer Company', '2015-06-11 06:18:13', 1, 1),
(64, 'TX', 'Tax Amount', '2015-06-11 06:18:13', 1, 1),
(65, 'TXY', 'Tax Year', '2015-06-11 06:18:13', 1, 1),
(66, 'WAC', 'Water Company', '2015-06-11 06:18:13', 1, 1),
(67, 'WFG', 'Waterfront Footage', '2015-06-11 06:18:14', 1, 1),
(68, 'WHT', 'Water Heater Location', '2015-06-11 06:18:14', 1, 1),
(69, 'APS', 'Appliances That Stay', '2015-06-11 06:18:14', 1, 1),
(70, 'BDI', 'Building Information', '2015-06-11 06:18:14', 1, 1),
(71, 'BSM', 'Basement', '2015-06-11 06:18:14', 1, 1),
(72, 'EXT', 'Exterior', '2015-06-11 06:18:14', 1, 1),
(73, 'FEA', 'Interior Features', '2015-06-11 06:18:14', 1, 1),
(74, 'FLS', 'Floor Covering', '2015-06-11 06:18:14', 1, 1),
(75, 'FND', 'Foundation', '2015-06-11 06:18:14', 1, 1),
(76, 'GR', 'Parking Type', '2015-06-11 06:18:14', 1, 1),
(77, 'LDE', 'Lot Details', '2015-06-11 06:18:14', 1, 1),
(78, 'LTV', 'Lot Topography/Vegetation', '2015-06-11 06:18:14', 1, 1),
(79, 'RF', 'Roof', '2015-06-11 06:18:14', 1, 1),
(80, 'RF1', 'Roof', '2015-06-11 06:18:14', 1, 1),
(81, 'RoofType', 'Roof Type', '2015-06-11 06:18:14', 1, 1),
(82, 'RoofType1', 'Roof Type', '2015-06-11 06:18:14', 1, 1),
(83, 'SIT', 'Site Features', '2015-06-11 06:18:14', 1, 1),
(84, 'SWR', 'Sewer', '2015-06-11 06:18:14', 1, 1),
(85, 'VEW', 'View', '2015-06-11 06:18:14', 1, 1),
(86, 'VEW1', 'View', '2015-06-11 06:18:14', 1, 1),
(87, 'VEW2', 'View', '2015-06-11 06:18:14', 1, 1),
(88, 'WAS', 'Water Source', '2015-06-11 06:18:14', 1, 1),
(89, 'WFT', 'Waterfront', '2015-06-11 06:18:14', 1, 1),
(90, 'WFT1', 'Waterfront', '2015-06-11 06:18:14', 1, 1),
(91, 'WFT2', 'Waterfront', '2015-06-11 06:18:14', 1, 1),
(92, 'WFT3', 'Waterfront', '2015-06-11 06:18:14', 1, 1),
(93, 'BUSR', 'Bus Route Number', '2015-06-11 06:18:14', 1, 1),
(94, 'ECRT', 'Environmental Cert', '2015-06-11 06:18:14', 1, 1),
(95, 'ZJD', 'Zoning Jurisdiction', '2015-06-11 06:18:14', 1, 1),
(96, 'ZNC', 'Zoning Code', '2015-06-11 06:18:14', 1, 1),
(97, 'PARQ', 'Third Party Approval Required', '2015-06-11 06:18:14', 1, 1),
(98, 'BREO', 'Bank Owned', '2015-06-11 06:18:15', 1, 1),
(99, 'BuiltGreenRating', 'Built Green Certification Rating', '2015-06-11 06:18:15', 1, 1),
(100, 'LEEDRating', 'LEED Certification Rating', '2015-06-11 06:18:15', 1, 1),
(101, 'NewConstruction', 'New Construction', '2015-06-11 06:18:15', 1, 1),
(102, 'EMP', 'Number of Employees', '2015-06-11 06:18:15', 1, 1),
(103, 'EQU', 'Equipment', '2015-06-11 06:18:15', 1, 1),
(104, 'EQV', 'Equipment Value', '2015-06-11 06:18:15', 1, 1),
(105, 'FRN', 'Franchise', '2015-06-11 06:18:15', 1, 1),
(106, 'GRS', 'Annual Gross Sales', '2015-06-11 06:18:15', 1, 1),
(107, 'GW', 'Goodwill Value', '2015-06-11 06:18:15', 1, 1),
(108, 'INV', 'Inventory Value', '2015-06-11 06:18:15', 1, 1),
(109, 'LNM', 'Liens/Mortgages', '2015-06-11 06:18:15', 1, 1),
(110, 'LSI', 'Lease Income', '2015-06-11 06:18:15', 1, 1),
(111, 'NA', 'Business Name', '2015-06-11 06:18:15', 1, 1),
(112, 'NP', 'Net Proceeds', '2015-06-11 06:18:15', 1, 1),
(113, 'PKU', 'Total Uncovered Parking', '2015-06-11 06:18:15', 1, 1),
(114, 'RES', 'Real Estate Value', '2015-06-11 06:18:15', 1, 1),
(115, 'RNT', 'Annual Rent', '2015-06-11 06:18:15', 1, 1),
(116, 'SIN', 'Signage', '2015-06-11 06:18:15', 1, 1),
(117, 'TEXP', 'Annual Expenses', '2015-06-11 06:18:15', 1, 1),
(118, 'TOB', 'Type of Business', '2015-06-11 06:18:15', 1, 1),
(119, 'LES', 'Lease Terms', '2015-06-11 06:18:15', 1, 1),
(120, 'LIC', 'Licenses', '2015-06-11 06:18:15', 1, 1),
(121, 'LIC1', 'Licenses', '2015-06-11 06:18:15', 1, 1),
(122, 'LOC', 'Location', '2015-06-11 06:18:15', 1, 1),
(123, 'LOC1', 'Location', '2015-06-11 06:18:15', 1, 1),
(124, 'LOC2', 'Location', '2015-06-11 06:18:15', 1, 1),
(125, 'MTB', 'Major Type of Business', '2015-06-11 06:18:15', 1, 1),
(126, 'LSZS', 'Acres', '2015-06-11 06:18:15', 1, 1),
(127, 'COO', 'Co-op Yes or No', '2015-06-11 06:18:15', 1, 1),
(128, 'NAS', 'Number of Assigned Spaces', '2015-06-11 06:18:15', 1, 1),
(129, 'NOC', 'Number of Units in Complex', '2015-06-11 06:18:15', 1, 1),
(130, 'NOS', 'Number of Stories in Building', '2015-06-11 06:18:15', 1, 1),
(131, 'NOU', 'Number of Units', '2015-06-11 06:18:15', 1, 1),
(132, 'OOC', 'Owner Occupancy Percentage', '2015-06-11 06:18:15', 1, 1),
(133, 'PKS', 'Parking Space Number', '2015-06-11 06:18:15', 1, 1),
(134, 'REM', 'Remodeled', '2015-06-11 06:18:15', 1, 1),
(135, 'SAA', 'Special Assessment Amount', '2015-06-11 06:18:16', 1, 1),
(136, 'SPA', 'Special Assessment', '2015-06-11 06:18:16', 1, 1),
(137, 'STL', 'Storage Location', '2015-06-11 06:18:16', 1, 1),
(138, 'TOF', 'Type of Fireplace', '2015-06-11 06:18:16', 1, 1),
(139, 'UFN', 'Unit Floor Number', '2015-06-11 06:18:16', 1, 1),
(140, 'APH', 'Appliance Hookups', '2015-06-11 06:18:16', 1, 1),
(141, 'CMN', 'Common Features', '2015-06-11 06:18:16', 1, 1),
(142, 'UNF', 'Unit Features', '2015-06-11 06:18:16', 1, 1),
(143, 'STRS', 'Number of Access Stairs', '2015-06-11 06:18:16', 1, 1),
(144, 'STO', 'Storage', '2015-06-11 06:18:16', 1, 1),
(145, 'TMC', 'Terms and Conditions', '2015-06-11 06:18:16', 1, 1),
(146, 'TMC1', 'Terms', '2015-06-11 06:18:16', 1, 1),
(147, 'TMC2', 'Terms', '2015-06-11 06:18:16', 1, 1),
(148, 'TMC3', 'Terms', '2015-06-11 06:18:16', 1, 1),
(149, 'ELE', 'Electricity', '2015-06-11 06:18:16', 1, 1),
(150, 'ESM', 'Easements', '2015-06-11 06:18:16', 1, 1),
(151, 'GAS', 'Gas', '2015-06-11 06:18:16', 1, 1),
(152, 'LVL', 'Level', '2015-06-11 06:18:16', 1, 1),
(153, 'RD', 'Road On Which Side of Property', '2015-06-11 06:18:16', 1, 1),
(154, 'SDA', 'Septic Designed and Applied for', '2015-06-11 06:18:16', 1, 1),
(155, 'SEP', 'Septic System Installed', '2015-06-11 06:18:16', 1, 1),
(156, 'SFA', 'Soils Feasibility Available', '2015-06-11 06:18:16', 1, 1),
(157, 'SLP', 'Slope of Property', '2015-06-11 06:18:17', 1, 1),
(158, 'SUR', 'Survey Information', '2015-06-11 06:18:17', 1, 1),
(159, 'TER', 'Terms Remarks', '2015-06-11 06:18:17', 1, 1),
(160, 'WRJ', 'Water Jurisdiction', '2015-06-11 06:18:17', 1, 1),
(161, 'ZNR', 'Zoning Remarks', '2015-06-11 06:18:17', 1, 1),
(162, 'FTR', 'Property Features', '2015-06-11 06:18:17', 1, 1),
(163, 'GZC', 'General Zoning Classification', '2015-06-11 06:18:17', 1, 1),
(164, 'RDI', 'Road Information', '2015-06-11 06:18:17', 1, 1),
(165, 'RS2', 'Restrictions', '2015-06-11 06:18:17', 1, 1),
(166, 'RS21', 'Restrictions', '2015-06-11 06:18:17', 1, 1),
(167, 'TPO', 'Topography', '2015-06-11 06:18:17', 1, 1),
(168, 'CAP', 'Cap Rate', '2015-06-11 06:18:17', 1, 1),
(169, 'ELEX', 'Electrical Expenses', '2015-06-11 06:18:17', 1, 1),
(170, 'GAI', 'Gross Adjusted Income', '2015-06-11 06:18:17', 1, 1),
(171, 'GRM', 'Gross Rent Multiplier', '2015-06-11 06:18:17', 1, 1),
(172, 'GSI', 'Gross Scheduled Income', '2015-06-11 06:18:17', 1, 1),
(173, 'GSP', 'Number of Garage Spaces', '2015-06-11 06:18:17', 1, 1),
(174, 'HET', 'Heating Expenses', '2015-06-11 06:18:17', 1, 1),
(175, 'INS', 'Insurance Expenses', '2015-06-11 06:18:17', 1, 1),
(176, 'NCS', 'Number of Carport Spaces', '2015-06-11 06:18:17', 1, 1),
(177, 'NOI', 'Net Operating Income', '2015-06-11 06:18:17', 1, 1),
(178, 'OTX', 'Other Expenses', '2015-06-11 06:18:17', 1, 1),
(179, 'TEX', 'Total Expenses', '2015-06-11 06:18:17', 1, 1),
(180, 'TIN', 'Total Monthly Income', '2015-06-11 06:18:17', 1, 1),
(181, 'TSP', 'Total Number of Parking Spaces', '2015-06-11 06:18:17', 1, 1),
(182, 'UBG', 'Units Below Grade', '2015-06-11 06:18:17', 1, 1),
(183, 'USP', 'Number of Uncovered Spaces', '2015-06-11 06:18:17', 1, 1),
(184, 'VAC', 'Vacancy Rate', '2015-06-11 06:18:17', 1, 1),
(185, 'WSG', 'Water/Sewer/Garbage', '2015-06-11 06:18:17', 1, 1),
(186, 'AMP', 'Power Service in AMPS', '2015-06-11 06:18:17', 1, 1),
(187, 'AVP', 'Number of Available Pads', '2015-06-11 06:18:18', 1, 1),
(188, 'BON', 'Boundary Survey', '2015-06-11 06:18:18', 1, 1),
(189, 'CHT', 'Ceiling Height', '2015-06-11 06:18:18', 1, 1),
(190, 'CHT1', 'Ceiling', '2015-06-11 06:18:18', 1, 1),
(191, 'DLT', 'Depth of Lot', '2015-06-11 06:18:18', 1, 1),
(192, 'ENV', 'Environmental Survey', '2015-06-11 06:18:18', 1, 1),
(193, 'EXA', 'Expansion Area', '2015-06-11 06:18:18', 1, 1),
(194, 'NNN', 'Total Monthly NNN', '2015-06-11 06:18:18', 1, 1),
(195, 'OSF', 'Approximate Office Square Feet', '2015-06-11 06:18:18', 1, 1),
(196, 'PAD', 'Pad Ready', '2015-06-11 06:18:18', 1, 1),
(197, 'STF', 'Site Frontage', '2015-06-11 06:18:18', 1, 1),
(198, 'TRI', 'Total Monthly Rent', '2015-06-11 06:18:18', 1, 1),
(199, 'TSF', 'Total Square Feet Rented', '2015-06-11 06:18:18', 1, 1),
(200, 'VAI', 'Improved Assessed Value', '2015-06-11 06:18:18', 1, 1),
(201, 'VAL', 'Land Assessed Value', '2015-06-11 06:18:18', 1, 1),
(202, 'WSF', 'Approximate Whse/Mfg Square Feet', '2015-06-11 06:18:18', 1, 1),
(203, 'YVA', 'Year Value Assessed', '2015-06-11 06:18:18', 1, 1),
(204, 'LDG', 'Loading', '2015-06-11 06:18:18', 1, 1),
(205, 'ACC', 'Acreage Comments', '2015-06-11 06:18:18', 1, 1),
(206, 'BCC', 'Barn/Outbuilding Comments', '2015-06-11 06:18:18', 1, 1),
(207, 'BRI', 'Boarding Income', '2015-06-11 06:18:18', 1, 1),
(208, 'BSZ', 'Barn Size', '2015-06-11 06:18:18', 1, 1),
(209, 'CCC', 'Crop & Soil Comments', '2015-06-11 06:18:18', 1, 1),
(210, 'CRI', 'Crop Income', '2015-06-11 06:18:18', 1, 1),
(211, 'EQI', 'Equity', '2015-06-11 06:18:18', 1, 1),
(212, 'LCC', 'Livestock Comments', '2015-06-11 06:18:19', 1, 1),
(213, 'IRRC', 'Irrigation Comments', '2015-06-11 06:18:19', 1, 1),
(214, 'PSZ', 'Parlor Size', '2015-06-11 06:18:19', 1, 1),
(215, 'SSZ', 'Storage Size', '2015-06-11 06:18:19', 1, 1),
(216, 'TAC', 'Till Acres', '2015-06-11 06:18:19', 1, 1),
(217, 'VCC', 'View Comments', '2015-06-11 06:18:19', 1, 1),
(218, 'BFE', 'Barn Features', '2015-06-11 06:18:19', 1, 1),
(219, 'BTP', 'Barn Type', '2015-06-11 06:18:19', 1, 1),
(220, 'FEN', 'Fence', '2015-06-11 06:18:19', 1, 1),
(221, 'FTP', 'Farm Type', '2015-06-11 06:18:19', 1, 1),
(222, 'IRS', 'Irrigation Source', '2015-06-11 06:18:19', 1, 1),
(223, 'ITP', 'Irrigation Type', '2015-06-11 06:18:19', 1, 1),
(224, 'LTP', 'Livestock Type', '2015-06-11 06:18:19', 1, 1),
(225, 'OUT1', 'Outbuildings', '2015-06-11 06:18:19', 1, 1),
(226, 'STP', 'Soil Type', '2015-06-11 06:18:19', 1, 1),
(227, 'ELEV', 'Elevation', '2015-06-11 06:18:19', 1, 1),
(228, 'LNI', 'Labor and Industries Inspected', '2015-06-11 06:18:19', 1, 1),
(229, 'MFY', 'Manufactured After 1976', '2015-06-11 06:18:19', 1, 1),
(230, 'NOH', 'Number of Homes in Park', '2015-06-11 06:18:19', 1, 1),
(231, 'PAS', 'Park For Sale', '2015-06-11 06:18:19', 1, 1),
(232, 'PRK', 'Park Name', '2015-06-11 06:18:19', 1, 1),
(233, 'SKR', 'Skirting Material', '2015-06-11 06:18:19', 1, 1),
(234, 'SPR', 'Space Rent Per Month', '2015-06-11 06:18:19', 1, 1),
(235, 'UCS', 'Unit Can Stay in Park After Sale', '2015-06-11 06:18:19', 1, 1),
(236, 'MHF', 'Manufactured Home Features', '2015-06-11 06:18:19', 1, 1),
(237, 'OTR', 'Other Rooms', '2015-06-11 06:18:19', 1, 1),
(238, 'PKA', 'Park Amenities', '2015-06-11 06:18:19', 1, 1),
(239, 'SRI', 'Space Rent Includes', '2015-06-11 06:18:19', 1, 1),
(240, 'LONGI', 'Longitude', '2015-06-11 06:18:19', 1, 1),
(241, 'LAT', 'Latitude', '2015-06-11 06:18:19', 1, 1),
(242, 'CLO', 'Sold Date', '2015-06-11 06:18:19', 1, 1),
(243, 'IMP', 'Improvements', '2015-06-11 06:18:19', 1, 1),
(244, 'OLP', 'Orginial Listing Price', '2015-06-11 06:18:19', 1, 1),
(245, 'TAX', 'Parcel Number', '2015-06-11 06:18:19', 1, 1),
(246, 'PIC', 'Pictures', '2015-06-11 06:18:20', 1, 1),
(247, 'POS', 'Possession', '2015-06-11 06:18:20', 1, 1),
(248, 'POS1', 'Possession', '2015-06-11 06:18:20', 1, 1),
(249, 'POS2', 'Possession', '2015-06-11 06:18:20', 1, 1),
(250, 'POS3', 'Possession', '2015-06-11 06:18:20', 1, 1),
(251, 'STY', 'Style', '2015-06-11 06:18:20', 1, 1),
(252, 'STY1', 'Style', '2015-06-11 06:18:20', 1, 1),
(253, 'VIRT', 'Virtual Tour URL', '2015-06-11 06:18:20', 1, 1),
(254, 'SIZ', 'Approx Building SqFt', '2015-06-11 06:18:20', 1, 1),
(255, 'ENS', 'Energy Source', '2015-06-11 06:18:20', 1, 1),
(256, 'YRE', 'Year Established', '2015-06-11 06:18:20', 1, 1),
(257, 'ATF', 'Assessment Fees', '2015-06-11 06:18:20', 1, 1),
(258, 'POL', 'Pool', '2015-06-11 06:18:20', 1, 1),
(259, 'TAV', 'Total Assessed Value', '2015-06-11 06:18:20', 1, 1),
(260, 'TotalUnits', 'Number of Units in Building', '2015-06-11 06:18:20', 1, 1),
(261, 'BathsHalf', '1/2 Bathrooms', '2015-06-11 06:18:20', 1, 1),
(262, 'BathsForth', '1/4 Bathrooms', '2015-06-11 06:18:20', 1, 1),
(263, 'BathsThird', '3/4 Bathrooms', '2015-06-11 06:18:20', 1, 1),
(264, 'petsYN', 'Cats & Dogs', '2015-06-11 06:18:20', 1, 1),
(265, 'DaysOnMarket', 'Days on Market', '2015-06-11 06:18:20', 1, 1),
(267, 'ExteriorFeatures1', 'Exterior Features', '2015-06-11 06:18:20', 1, 1),
(269, 'PublicRemarks1', 'Property Description', '2015-06-11 06:18:20', 1, 1),
(270, 'SqFtLevel1', 'Sq Ft Level 1', '2015-06-11 06:18:20', 1, 1),
(271, 'SqFtLevel2', 'Sq Ft Level 2', '2015-06-11 06:18:20', 1, 1),
(272, 'SqFtLevel3', 'Sq Ft Level 3', '2015-06-11 06:18:20', 1, 1),
(273, 'AdditionalStatus', 'Additional Status', '2015-06-11 06:18:20', 1, 1),
(274, 'BasementSqFtFinished', 'Basement Sq Ft Finished', '2015-06-11 06:18:20', 1, 1),
(275, 'BasementSqFtUnfinished', 'Basement Sq Ft Unfinished', '2015-06-11 06:18:20', 1, 1),
(276, 'Cooling', 'Cooling', '2015-06-11 06:18:20', 1, 1),
(277, 'Cooling1', 'Cooling', '2015-06-11 06:18:20', 1, 1),
(278, 'Cooling2', 'Cooling', '2015-06-11 06:18:20', 1, 1),
(279, 'Cooling3', 'Cooling', '2015-06-11 06:18:20', 1, 1),
(280, 'InsideCityLimitsYN', 'Inside City Limits', '2015-06-11 06:18:20', 1, 1),
(281, 'Porch', 'Porch', '2015-06-11 06:18:21', 1, 1),
(282, 'StateRdYN', 'State Road', '2015-06-11 06:18:21', 1, 1),
(283, 'WheelChairAccessYN', 'Wheel Chair Accessible', '2015-06-11 06:18:21', 1, 1),
(284, 'BusinessType', 'Business Type', '2015-06-11 06:18:21', 1, 1),
(285, 'Basement', 'Basement', '2015-06-11 06:18:21', 1, 1),
(286, 'Heating', 'Heating', '2015-06-11 06:18:21', 1, 1),
(287, 'Flooring', 'Flooring', '2015-06-11 06:18:21', 1, 1),
(288, 'Flooring1', 'Flooring', '2015-06-11 06:18:21', 1, 1),
(289, 'Flooring2', 'Flooring', '2015-06-11 06:18:21', 1, 1),
(290, 'Heating1', 'Heating', '2015-06-11 06:18:21', 1, 1),
(291, 'Heating2', 'Heating', '2015-06-11 06:18:21', 1, 1),
(292, 'Heating3', 'Heating', '2015-06-11 06:18:21', 1, 1),
(293, 'WoodedAcres', 'Wooded Acres', '2015-06-11 06:18:21', 1, 1),
(294, 'MfgHomesAllowedYN', 'Manufactured Homes Allowed', '2015-06-11 06:18:21', 1, 1),
(295, 'SuitableUse', 'Suitable Use', '2015-06-11 06:18:21', 1, 1),
(296, 'Foundation', 'Manufactured Foundation', '2015-06-11 06:18:21', 1, 1),
(297, 'SoldPricePerSqFt', '$ sq/ft', '2015-06-11 06:18:21', 1, 1),
(298, 'HOARentIncludes', 'Association Fee Includes', '2015-06-11 06:18:21', 1, 1),
(299, 'HOAPaymentFreq', 'Association Payment Freq.', '2015-06-11 06:18:21', 1, 1),
(300, 'MR', 'Property Description', '2015-06-23 00:00:00', 1, 1),
(301, 'SNR', 'Senior Housing', '2015-06-23 00:00:00', 1, 1),
(302, 'ParkingDescription1', 'Parking', '2015-06-11 06:18:21', 1, 1),
(305, 'SqFtLowerLevelTotal', 'Sq/Ft Lower Level', '2015-06-11 06:18:21', 1, 1),
(306, 'SqFtMainLevelTotal', 'Sq/Ft Main Level', '2015-06-11 06:18:21', 1, 1),
(307, 'SqFtUpperLevelTotal', 'Sq/Ft Upper Level', '2015-06-11 06:18:21', 1, 1),
(308, 'SqFtApximateManufacturing', 'Approx Manufacturing Sq/Ft', '2015-06-11 06:18:21', 1, 1),
(309, 'SqFtApproximateWarehouse', 'Approx Warehouse Sq/Ft', '2015-06-11 06:18:21', 1, 1),
(310, 'SaleIncludes', 'Sale Includes', '2015-06-11 06:18:21', 1, 1),
(311, 'SaleIncludes1', 'Sale Includes', '2015-06-11 06:18:21', 1, 1),
(312, 'RoadFrontage', 'Road Frontage', '2015-06-11 06:18:21', 1, 1),
(313, 'Stories', 'Stories', '2015-06-11 06:18:21', 1, 1),
(314, 'Construction', 'Construction', '2015-06-11 06:18:21', 1, 1),
(315, 'Construction1', 'Waterfront', '2015-06-11 06:18:21', 1, 1),
(316, 'Construction2', 'Waterfront', '2015-06-11 06:18:21', 1, 1),
(317, 'Construction3', 'Construction', '2015-06-11 06:18:21', 1, 1),
(318, 'Utilities', 'Utilities', '2015-06-11 06:18:22', 1, 1),
(319, 'Utilities1', 'Utilities', '2015-06-11 06:18:22', 1, 1),
(320, 'Utilities2', 'Utilities', '2015-06-11 06:18:22', 1, 1),
(321, 'Utilities3', 'Utilities', '2015-06-11 06:18:22', 1, 1),
(322, 'SqFtApproximateGross', 'Gross Sq/Ft', '2015-06-11 06:18:22', 1, 1),
(323, 'Features', 'Features', '2015-06-11 06:18:22', 1, 1),
(324, 'Acres', 'Acres', '2015-06-11 06:18:22', 1, 1),
(325, 'NumberOfLotsTotal', 'Number of Lots', '2015-06-11 06:18:22', 1, 1),
(326, 'HOAYN', 'HOA', '2015-06-11 06:18:22', 1, 1),
(327, 'PropertyCategory', 'Property Category', '2015-06-11 06:18:22', 1, 1),
(328, 'AccessibilityFeatures', 'Accesibility Features', '2015-06-11 06:18:22', 1, 1),
(329, 'LotDescription', 'Lot Description', '2015-06-11 06:18:22', 1, 1),
(330, 'LotDescription1', 'Lot Description', '2015-06-11 06:18:22', 1, 1),
(331, 'SqftLiving', 'SqFt - Living', '2015-06-11 06:18:22', 1, 1),
(332, 'SqftGuestHouse', 'SqFt - Guest House', '2015-06-11 06:18:22', 1, 1),
(333, 'FrontExposure', 'Front Exposure', '2015-06-11 06:18:22', 1, 1),
(334, 'Subdivision', 'Subdivision', '2015-06-11 06:18:22', 1, 1),
(335, 'HOPA', 'HOPA', '2015-06-11 06:18:22', 1, 1),
(336, 'ModelName', 'Model Name', '2015-06-11 06:18:22', 1, 1),
(337, 'PetsAllowed', 'Pets Allowed', '2015-06-11 06:18:22', 1, 1),
(338, 'ApplicationFee', 'Application Fee', '2015-06-11 06:18:22', 1, 1),
(339, 'DevelopmentName', 'Development Name', '2015-06-11 06:18:22', 1, 1),
(340, 'BoatServices', 'Boat Services', '2015-06-11 06:18:22', 1, 1),
(341, 'EquestrianFeatures', 'Equestrian Features', '2015-06-11 06:18:22', 1, 1),
(342, 'Furnished', 'Furnished', '2015-06-11 06:18:22', 1, 1),
(343, 'GuestHouse', 'Guest House', '2015-06-11 06:18:22', 1, 1),
(344, 'UtilitiesonSite', 'Utilities on Site', '2015-06-11 06:18:22', 1, 1),
(345, 'ForLease', 'For Lease', '2015-06-11 06:18:22', 1, 1),
(346, 'ForSale', 'For Sale', '2015-06-11 06:18:22', 1, 1),
(347, 'TotalBuildingSqFt', 'Total Building SqFt', '2015-06-11 06:18:23', 1, 1),
(348, 'Offices', 'Offices', '2015-06-11 06:18:23', 1, 1),
(349, 'Bays', 'Bays', '2015-06-11 06:18:23', 1, 1),
(350, 'LoadingDocks', 'Loading Docks', '2015-06-11 06:18:23', 1, 1),
(351, 'SqFtIncluded', 'SqFt Included', '2015-06-11 06:18:23', 1, 1),
(352, 'SqFtOccupied', 'SqFt  - Occupied', '2015-06-11 06:18:23', 1, 1),
(353, 'Training', 'Training', '2015-06-11 06:18:23', 1, 1),
(354, 'Road', 'Road', '2015-06-11 06:18:23', 1, 1),
(355, 'TypeBuilding', 'Type of Building', '2015-06-11 06:18:23', 1, 1),
(356, 'Internal_MLS_ID', 'MLS ID only for florida mls', '2015-06-11 06:18:20', 1, 1),
(357, 'property_id', 'Property primary key', '2015-06-27 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mls_type_of_mls_mapping_trans`
--

CREATE TABLE IF NOT EXISTS `mls_type_of_mls_mapping_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL COMMENT 'From mls_type_of_mls_master; Apply Index',
  `table_id` tinyint(1) NOT NULL,
  `mls_master_field_id` int(11) NOT NULL COMMENT 'For just a reference; From ''mls_type_of_mls_mapping_master''',
  `mls_master_field` varchar(250) NOT NULL COMMENT 'Used for insert/update data',
  `mls_field` varchar(250) NOT NULL,
  `mls_field_table` varchar(250) NOT NULL,
  `mls_transection_field` varchar(250) NOT NULL COMMENT 'Transection table field',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master; Apply index',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master; Apply index',
  `status` tinyint(1) NOT NULL COMMENT '0.Deactive, 1.Active(Default) ',
  PRIMARY KEY (`id`),
  KEY `type_of_mls_id` (`mls_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mls_type_of_mls_master`
--

CREATE TABLE IF NOT EXISTS `mls_type_of_mls_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` varchar(250) NOT NULL,
  `mapping_name` varchar(250) NOT NULL,
  `mls_hostname` varchar(250) NOT NULL,
  `mls_db_username` varchar(250) NOT NULL,
  `mls_db_password` varchar(250) NOT NULL,
  `mls_db_name` varchar(250) NOT NULL,
  `mls_image_url` varchar(250) NOT NULL,
  `mls_comment` mediumtext NOT NULL,
  `mls_dump` tinyint(1) NOT NULL COMMENT '0-not dump,1-dump',
  `mls_last_offset` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `module_master`
--

CREATE TABLE IF NOT EXISTS `module_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) NOT NULL,
  `module_unique_name` varchar(255) NOT NULL,
  `module_parent` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `module_right` varchar(255) NOT NULL,
  `default_right` int(1) NOT NULL,
  `position` int(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=233 ;

--
-- Dumping data for table `module_master`
--

INSERT INTO `module_master` (`id`, `module_name`, `module_unique_name`, `module_parent`, `module_id`, `module_right`, `default_right`, `position`, `created_by`, `created_date`, `modified_date`, `status`) VALUES
(1, 'Lead Dashboard', 'lead_dashboard', 0, 1, '', 0, 1, 6, '2015-01-28 11:02:11', '0000-00-00 00:00:00', 1),
(2, 'Lead Dashboard View', 'lead_dashboard_view', -1, 1, 'view', 0, 0, 6, '2015-01-28 11:02:11', '0000-00-00 00:00:00', 1),
(3, 'Lead Dashboard Edit', 'lead_dashboard_edit', -1, 1, 'edit', 0, 0, 6, '2015-01-28 11:02:11', '0000-00-00 00:00:00', 1),
(4, 'Lead Dashboard Delete', 'lead_dashboard_delete', -1, 1, 'delete', 0, 0, 6, '2015-01-28 11:02:11', '0000-00-00 00:00:00', 1),
(5, 'Auto Communication', 'auto_communication', 1, 5, '', 0, 0, 6, '2015-01-28 11:02:36', '0000-00-00 00:00:00', 1),
(6, 'Auto Communication View', 'auto_communication_view', 1, 5, 'view', 0, 0, 6, '2015-01-28 11:02:36', '0000-00-00 00:00:00', 1),
(7, 'Auto Communication Add', 'auto_communication_add', 1, 5, 'add', 0, 0, 6, '2015-01-28 11:02:36', '0000-00-00 00:00:00', 1),
(8, 'Auto Communication Edit', 'auto_communication_edit', 1, 5, 'edit', 0, 0, 6, '2015-01-28 11:02:36', '0000-00-00 00:00:00', 1),
(9, 'Auto Communication Delete', 'auto_communication_delete', 1, 5, 'delete', 0, 0, 6, '2015-01-28 11:02:36', '0000-00-00 00:00:00', 1),
(10, 'Lead Distribution - Agent', 'lead_distribution_agent', 1, 10, '', 0, 0, 6, '2015-01-28 11:03:13', '0000-00-00 00:00:00', 1),
(11, 'Lead Distribution - Agent View', 'lead_distribution_agent_view', 1, 10, 'view', 0, 0, 6, '2015-01-28 11:03:13', '0000-00-00 00:00:00', 1),
(12, 'Lead Distribution - Agent Edit', 'lead_distribution_agent_edit', 1, 10, 'edit', 0, 0, 6, '2015-01-28 11:03:13', '0000-00-00 00:00:00', 1),
(14, 'Lead Distribution - Lender', 'lead_distribution_lender', 1, 14, '', 0, 0, 6, '2015-01-28 11:03:59', '0000-00-00 00:00:00', 1),
(15, 'Lead Distribution - Lender View', 'lead_distribution_lender_view', 1, 14, 'view', 0, 0, 6, '2015-01-28 11:03:59', '0000-00-00 00:00:00', 1),
(16, 'Lead Distribution - Lender Edit', 'lead_distribution_lender_edit', 1, 14, 'edit', 0, 0, 6, '2015-01-28 11:03:59', '0000-00-00 00:00:00', 1),
(18, 'Contacts', 'contact', 0, 18, '', 1, 2, 6, '2015-01-28 11:04:42', '0000-00-00 00:00:00', 1),
(19, 'Contacts View', 'contact_view', -1, 18, 'view', 1, 0, 6, '2015-01-28 11:04:42', '0000-00-00 00:00:00', 1),
(20, 'Contacts Add', 'contact_add', -1, 18, 'add', 1, 0, 6, '2015-01-28 11:04:42', '0000-00-00 00:00:00', 1),
(21, 'Contacts Edit', 'contact_edit', -1, 18, 'edit', 1, 0, 6, '2015-01-28 11:04:42', '0000-00-00 00:00:00', 1),
(22, 'Contacts Delete', 'contact_delete', -1, 18, 'delete', 1, 0, 6, '2015-01-28 11:04:42', '0000-00-00 00:00:00', 1),
(23, 'Import Contacts', 'import_contacts', 18, 23, '', 1, 0, 6, '2015-01-28 11:05:21', '0000-00-00 00:00:00', 1),
(24, 'Import Contacts View', 'import_contacts_view', 18, 23, 'view', 1, 0, 6, '2015-01-28 11:05:21', '0000-00-00 00:00:00', 1),
(25, 'Contacts - Buyer Preferences', 'buyer_preferences', 0, 25, '', 0, 3, 6, '2015-01-28 11:06:28', '0000-00-00 00:00:00', 1),
(26, 'Contacts - Buyer Preferences View', 'buyer_preferences_view', -1, 25, 'view', 0, 0, 6, '2015-01-28 11:06:28', '0000-00-00 00:00:00', 1),
(27, 'COMMUNICATIONS', 'communications', 0, 27, '', 0, 4, 6, '2015-01-28 11:07:55', '0000-00-00 00:00:00', 1),
(28, 'COMMUNICATIONS View', 'communications_view', -1, 27, 'view', 0, 0, 6, '2015-01-28 11:07:55', '0000-00-00 00:00:00', 1),
(29, 'COMMUNICATIONS Add', 'communications_add', -1, 27, 'add', 0, 0, 6, '2015-01-28 11:07:55', '0000-00-00 00:00:00', 1),
(30, 'COMMUNICATIONS Edit', 'communications_edit', -1, 27, 'edit', 0, 0, 6, '2015-01-28 11:07:55', '0000-00-00 00:00:00', 1),
(31, 'COMMUNICATIONS Delete', 'communications_delete', -1, 27, 'delete', 0, 0, 6, '2015-01-28 11:07:55', '0000-00-00 00:00:00', 1),
(32, 'Premium Plans', 'premium_plans', 27, 32, '', 0, 0, 6, '2015-01-28 11:08:33', '0000-00-00 00:00:00', 1),
(33, 'Premium Plans View', 'premium_plans_view', 27, 32, 'view', 0, 0, 6, '2015-01-28 11:08:33', '0000-00-00 00:00:00', 1),
(34, 'Premium Plans Add', 'premium_plans_add', 27, 32, 'add', 0, 0, 6, '2015-01-28 11:08:33', '0000-00-00 00:00:00', 1),
(35, 'Premium Plans Edit', 'premium_plans_edit', 27, 32, 'edit', 0, 0, 6, '2015-01-28 11:08:33', '0000-00-00 00:00:00', 1),
(36, 'Premium Plans Delete', 'premium_plans_delete', 27, 32, 'delete', 0, 0, 6, '2015-01-28 11:08:33', '0000-00-00 00:00:00', 1),
(37, 'Play All, Pause All, Stop All', 'play_push_stop', 27, 37, '', 0, 0, 6, '2015-01-28 11:33:56', '0000-00-00 00:00:00', 1),
(38, 'Play All, Pause All, Stop All View', 'play_push_stop_view', 27, 37, 'view', 0, 0, 6, '2015-01-28 11:33:56', '0000-00-00 00:00:00', 1),
(39, 'Social', 'social', 0, 39, '', 1, 5, 6, '2015-01-28 11:35:08', '0000-00-00 00:00:00', 1),
(40, 'SOCIAL View', 'social_view', -1, 39, 'view', 1, 0, 6, '2015-01-28 11:35:08', '0000-00-00 00:00:00', 1),
(44, 'All Channels', 'all_channels', 39, 44, '', 1, 0, 6, '2015-01-28 12:08:13', '0000-00-00 00:00:00', 1),
(45, 'All Channels View', 'all_channels_view', 39, 44, 'view', 1, 0, 6, '2015-01-28 12:08:13', '0000-00-00 00:00:00', 1),
(46, 'All Channels Add', 'all_channels_add', 39, 44, 'add', 1, 0, 6, '2015-01-28 12:08:13', '0000-00-00 00:00:00', 1),
(47, 'All Channels Edit', 'all_channels_edit', 39, 44, 'edit', 1, 0, 6, '2015-01-28 12:08:13', '0000-00-00 00:00:00', 1),
(48, 'All Channels Delete', 'all_channels_delete', 39, 44, 'delete', 1, 0, 6, '2015-01-28 12:08:13', '0000-00-00 00:00:00', 1),
(49, 'Facebook_post', 'facebook_post', 39, 49, '', 1, 0, 6, '2015-01-28 12:11:44', '0000-00-00 00:00:00', 1),
(50, 'Facebook_Post View', 'facebook_post_view', 39, 49, 'view', 1, 0, 6, '2015-01-28 12:11:44', '0000-00-00 00:00:00', 1),
(51, 'Facebook_Post Add', 'facebook_post_add', 39, 49, 'add', 1, 0, 6, '2015-01-28 12:11:44', '0000-00-00 00:00:00', 1),
(52, 'Facebook_Post Edit', 'facebook_post_edit', 39, 49, 'edit', 1, 0, 6, '2015-01-28 12:11:44', '0000-00-00 00:00:00', 1),
(53, 'Facebook_Post Delete', 'facebook_post_delete', 39, 49, 'delete', 1, 0, 6, '2015-01-28 12:11:44', '0000-00-00 00:00:00', 1),
(54, 'Twitter', 'twitter', 39, 54, '', 1, 0, 6, '2015-01-28 12:15:17', '0000-00-00 00:00:00', 1),
(55, 'Twitter View', 'twitter_view', 39, 54, 'view', 1, 0, 6, '2015-01-28 12:15:17', '0000-00-00 00:00:00', 1),
(56, 'Twitter Add', 'twitter_add', 39, 54, 'add', 1, 0, 6, '2015-01-28 12:15:17', '0000-00-00 00:00:00', 1),
(57, 'Twitter Edit', 'twitter_edit', 39, 54, 'edit', 1, 0, 6, '2015-01-28 12:15:17', '0000-00-00 00:00:00', 1),
(58, 'Twitter Delete', 'twitter_delete', 39, 54, 'delete', 1, 0, 6, '2015-01-28 12:15:17', '0000-00-00 00:00:00', 1),
(59, 'Linkedin', 'linkedin', 39, 59, '', 1, 0, 6, '2015-01-28 12:15:44', '0000-00-00 00:00:00', 1),
(60, 'LinkedIn View', 'linkedin_view', 39, 59, 'view', 1, 0, 6, '2015-01-28 12:15:44', '0000-00-00 00:00:00', 1),
(61, 'LinkedIn Add', 'linkedin_add', 39, 59, 'add', 1, 0, 6, '2015-01-28 12:15:44', '0000-00-00 00:00:00', 1),
(62, 'LinkedIn Edit', 'linkedin_edit', 39, 59, 'edit', 1, 0, 6, '2015-01-28 12:15:44', '0000-00-00 00:00:00', 1),
(63, 'LinkedIn Delete', 'linkedin_delete', 39, 59, 'delete', 1, 0, 6, '2015-01-28 12:15:44', '0000-00-00 00:00:00', 1),
(64, 'Tasks', 'tasks', 0, 64, '', 1, 6, 6, '2015-01-28 12:16:29', '0000-00-00 00:00:00', 1),
(65, 'TASKS View', 'tasks_view', -1, 64, 'view', 1, 0, 6, '2015-01-28 12:16:29', '0000-00-00 00:00:00', 1),
(66, 'TASKS Add', 'tasks_add', -1, 64, 'add', 1, 0, 6, '2015-01-28 12:16:29', '0000-00-00 00:00:00', 1),
(67, 'TASKS Edit', 'tasks_edit', -1, 64, 'edit', 1, 0, 6, '2015-01-28 12:16:29', '0000-00-00 00:00:00', 1),
(68, 'TASKS Delete', 'tasks_delete', -1, 64, 'delete', 1, 0, 6, '2015-01-28 12:16:29', '0000-00-00 00:00:00', 1),
(69, 'Email Blast', 'email_blast', 0, 69, '', 1, 6, 6, '2015-01-28 12:16:45', '0000-00-00 00:00:00', 1),
(70, 'EMAIL BLAST View', 'email_blast_view', -1, 69, 'view', 1, 0, 6, '2015-01-28 12:16:45', '0000-00-00 00:00:00', 1),
(71, 'EMAIL BLAST Add', 'email_blast_add', -1, 69, 'add', 1, 0, 6, '2015-01-28 12:16:45', '0000-00-00 00:00:00', 1),
(72, 'EMAIL BLAST Edit', 'email_blast_edit', -1, 69, 'edit', 1, 0, 6, '2015-01-28 12:16:45', '0000-00-00 00:00:00', 1),
(73, 'EMAIL BLAST Delete', 'email_blast_delete', -1, 69, 'delete', 1, 0, 6, '2015-01-28 12:16:45', '0000-00-00 00:00:00', 1),
(74, 'Text Blast', 'text_blast', 8, 74, '', 1, 0, 6, '2015-01-28 12:20:03', '0000-00-00 00:00:00', 1),
(75, 'TEXT BLAST View', 'text_blast_view', -1, 74, 'view', 1, 0, 6, '2015-01-28 12:20:03', '0000-00-00 00:00:00', 1),
(76, 'TEXT BLAST Add', 'text_blast_add', -1, 74, 'add', 1, 0, 6, '2015-01-28 12:20:03', '0000-00-00 00:00:00', 1),
(77, 'TEXT BLAST Edit', 'text_blast_edit', -1, 74, 'edit', 1, 0, 6, '2015-01-28 12:20:03', '0000-00-00 00:00:00', 1),
(78, 'TEXT BLAST Delete', 'text_blast_delete', -1, 74, 'delete', 1, 0, 6, '2015-01-28 12:20:03', '0000-00-00 00:00:00', 1),
(79, 'Mail Blast', 'mail_blast', 0, 79, '', 1, 9, 6, '2015-01-28 12:20:22', '0000-00-00 00:00:00', 1),
(80, 'MAIL BLAST View', 'mail_blast_view', -1, 79, 'view', 1, 0, 6, '2015-01-28 12:20:22', '0000-00-00 00:00:00', 1),
(84, 'Letter', 'letter', 79, 84, '', 1, 0, 6, '2015-01-28 12:20:43', '0000-00-00 00:00:00', 1),
(85, 'Letter View', 'letter_view', 79, 84, 'view', 1, 0, 6, '2015-01-28 12:20:43', '0000-00-00 00:00:00', 1),
(86, 'Letter Add', 'letter_add', 79, 84, 'add', 1, 0, 6, '2015-01-28 12:20:43', '0000-00-00 00:00:00', 1),
(88, 'Letter Delete', 'letter_delete', 79, 84, 'delete', 1, 0, 6, '2015-01-28 12:20:43', '0000-00-00 00:00:00', 1),
(89, 'Envelope', 'envelope', 79, 89, '', 1, 0, 6, '2015-01-28 12:21:00', '0000-00-00 00:00:00', 1),
(90, 'Envelope View', 'envelope_view', 79, 89, 'view', 1, 0, 6, '2015-01-28 12:21:00', '0000-00-00 00:00:00', 1),
(91, 'Envelope Add', 'envelope_add', 79, 89, 'add', 1, 0, 6, '2015-01-28 12:21:00', '0000-00-00 00:00:00', 1),
(93, 'Envelope Delete', 'envelope_delete', 79, 89, 'delete', 1, 0, 6, '2015-01-28 12:21:00', '0000-00-00 00:00:00', 1),
(94, 'Label', 'label', 79, 94, '', 1, 0, 6, '2015-01-28 12:21:21', '0000-00-00 00:00:00', 1),
(95, 'Label View', 'label_view', 79, 94, 'view', 1, 0, 6, '2015-01-28 12:21:21', '0000-00-00 00:00:00', 1),
(96, 'Label Add', 'label_add', 79, 94, 'add', 1, 0, 6, '2015-01-28 12:21:21', '0000-00-00 00:00:00', 1),
(98, 'Label Delete', 'label_delete', 79, 94, 'delete', 1, 0, 6, '2015-01-28 12:21:21', '0000-00-00 00:00:00', 1),
(99, 'Calendar', 'calendar', 0, 99, '', 1, 10, 6, '2015-01-28 12:21:49', '0000-00-00 00:00:00', 1),
(100, 'CALENDAR View', 'calendar_view', -1, 99, 'view', 1, 0, 6, '2015-01-28 12:21:49', '0000-00-00 00:00:00', 1),
(101, 'CALENDAR Add', 'calendar_add', -1, 99, 'add', 1, 0, 6, '2015-01-28 12:21:49', '0000-00-00 00:00:00', 1),
(102, 'CALENDAR Edit', 'calendar_edit', -1, 99, 'edit', 1, 0, 6, '2015-01-28 12:21:49', '0000-00-00 00:00:00', 1),
(103, 'CALENDAR Delete', 'calendar_delete', -1, 99, 'delete', 1, 0, 6, '2015-01-28 12:21:49', '0000-00-00 00:00:00', 1),
(104, 'Integration With Google Calendar', 'google_calendar', 99, 104, '', 1, 0, 6, '2015-01-28 12:22:40', '0000-00-00 00:00:00', 1),
(105, 'Integration with Google Calendar View', 'google_calendar_view', 99, 104, 'view', 1, 0, 6, '2015-01-28 12:22:40', '0000-00-00 00:00:00', 1),
(109, 'Form Builder', 'form_builder', 0, 109, '', 0, 11, 6, '2015-01-28 12:23:11', '0000-00-00 00:00:00', 1),
(110, 'FORM BUILDER View', 'form_builder_view', -1, 109, 'view', 0, 0, 6, '2015-01-28 12:23:11', '0000-00-00 00:00:00', 1),
(111, 'FORM BUILDER Add', 'form_builder_add', -1, 109, 'add', 0, 0, 6, '2015-01-28 12:23:11', '0000-00-00 00:00:00', 1),
(112, 'FORM BUILDER Edit', 'form_builder_edit', -1, 109, 'edit', 0, 0, 6, '2015-01-28 12:23:11', '0000-00-00 00:00:00', 1),
(113, 'FORM BUILDER Delete', 'form_builder_delete', -1, 109, 'delete', 0, 0, 6, '2015-01-28 12:23:11', '0000-00-00 00:00:00', 1),
(114, 'Template Library', 'template_library', 0, 114, '', 1, 12, 6, '2015-01-28 12:25:45', '0000-00-00 00:00:00', 1),
(115, 'TEMPLATE LIBRARY View', 'template_library_view', -1, 114, 'view', 1, 0, 6, '2015-01-28 12:25:45', '0000-00-00 00:00:00', 1),
(116, 'Email Library', 'email_library', 114, 116, '', 1, 0, 6, '2015-01-28 12:26:27', '0000-00-00 00:00:00', 1),
(117, 'Email Library View', 'email_library_view', 114, 116, 'view', 1, 0, 6, '2015-01-28 12:26:27', '0000-00-00 00:00:00', 1),
(118, 'Email Library Add', 'email_library_add', 114, 116, 'add', 1, 0, 6, '2015-01-28 12:26:27', '0000-00-00 00:00:00', 1),
(119, 'Email Library Edit', 'email_library_edit', 114, 116, 'edit', 1, 0, 6, '2015-01-28 12:26:27', '0000-00-00 00:00:00', 1),
(120, 'Email Library Delete', 'email_library_delete', 114, 116, 'delete', 1, 0, 6, '2015-01-28 12:26:27', '0000-00-00 00:00:00', 1),
(121, 'Auto Responder', 'auto_responder', 114, 121, '', 1, 0, 6, '2015-01-28 12:37:27', '0000-00-00 00:00:00', 1),
(122, 'Auto Responder View', 'auto_responder_view', 114, 121, 'view', 1, 0, 6, '2015-01-28 12:37:27', '0000-00-00 00:00:00', 1),
(123, 'Auto Responder Add', 'auto_responder_add', 114, 121, 'add', 1, 0, 6, '2015-01-28 12:37:27', '0000-00-00 00:00:00', 1),
(124, 'Auto Responder Edit', 'auto_responder_edit', 114, 121, 'edit', 1, 0, 6, '2015-01-28 12:37:27', '0000-00-00 00:00:00', 1),
(125, 'Auto Responder Delete', 'auto_responder_delete', 114, 121, 'delete', 1, 0, 6, '2015-01-28 12:37:27', '0000-00-00 00:00:00', 1),
(126, 'Envelope Library', 'envelope_library', 114, 126, '', 1, 0, 6, '2015-01-28 12:37:44', '0000-00-00 00:00:00', 1),
(127, 'Envelope Library View', 'envelope_library_view', 114, 126, 'view', 1, 0, 6, '2015-01-28 12:37:44', '0000-00-00 00:00:00', 1),
(128, 'Envelope Library Add', 'envelope_library_add', 114, 126, 'add', 1, 0, 6, '2015-01-28 12:37:44', '0000-00-00 00:00:00', 1),
(129, 'Envelope Library Edit', 'envelope_library_edit', 114, 126, 'edit', 1, 0, 6, '2015-01-28 12:37:44', '0000-00-00 00:00:00', 1),
(130, 'Envelope Library Delete', 'envelope_library_delete', 114, 126, 'delete', 1, 0, 6, '2015-01-28 12:37:44', '0000-00-00 00:00:00', 1),
(131, 'Social Media Posts', 'social_media_posts', 114, 131, '', 1, 0, 6, '2015-01-28 12:38:05', '0000-00-00 00:00:00', 1),
(132, 'Social Media Posts View', 'social_media_posts_view', 114, 131, 'view', 1, 0, 6, '2015-01-28 12:38:05', '0000-00-00 00:00:00', 1),
(133, 'Social Media Posts Add', 'social_media_posts_add', 114, 131, 'add', 1, 0, 6, '2015-01-28 12:38:05', '0000-00-00 00:00:00', 1),
(134, 'Social Media Posts Edit', 'social_media_posts_edit', 114, 131, 'edit', 1, 0, 6, '2015-01-28 12:38:05', '0000-00-00 00:00:00', 1),
(135, 'Social Media Posts Delete', 'social_media_posts_delete', 114, 131, 'delete', 1, 0, 6, '2015-01-28 12:38:05', '0000-00-00 00:00:00', 1),
(136, 'Phone Call Scripts', 'phone_call_scripts', 114, 136, '', 1, 0, 6, '2015-01-28 12:38:25', '0000-00-00 00:00:00', 1),
(137, 'Phone Call Scripts View', 'phone_call_scripts_view', 114, 136, 'view', 1, 0, 6, '2015-01-28 12:38:25', '0000-00-00 00:00:00', 1),
(138, 'Phone Call Scripts Add', 'phone_call_scripts_add', 114, 136, 'add', 1, 0, 6, '2015-01-28 12:38:25', '0000-00-00 00:00:00', 1),
(139, 'Phone Call Scripts Edit', 'phone_call_scripts_edit', 114, 136, 'edit', 1, 0, 6, '2015-01-28 12:38:25', '0000-00-00 00:00:00', 1),
(140, 'Phone Call Scripts Delete', 'phone_call_scripts_delete', 114, 136, 'delete', 1, 0, 6, '2015-01-28 12:38:25', '0000-00-00 00:00:00', 1),
(141, 'Sms Texts', 'sms_texts', 114, 141, '', 1, 0, 6, '2015-01-28 12:38:42', '0000-00-00 00:00:00', 1),
(142, 'SMS Texts View', 'sms_texts_view', 114, 141, 'view', 1, 0, 6, '2015-01-28 12:38:42', '0000-00-00 00:00:00', 1),
(143, 'SMS Texts Add', 'sms_texts_add', 114, 141, 'add', 1, 0, 6, '2015-01-28 12:38:42', '0000-00-00 00:00:00', 1),
(144, 'SMS Texts Edit', 'sms_texts_edit', 114, 141, 'edit', 1, 0, 6, '2015-01-28 12:38:42', '0000-00-00 00:00:00', 1),
(145, 'SMS Texts Delete', 'sms_texts_delete', 114, 141, 'delete', 1, 0, 6, '2015-01-28 12:38:42', '0000-00-00 00:00:00', 1),
(146, 'Label Library', 'label_library', 114, 146, '', 1, 0, 6, '2015-01-28 12:39:08', '0000-00-00 00:00:00', 1),
(147, 'Label Library View', 'label_library_view', 114, 146, 'view', 1, 0, 6, '2015-01-28 12:39:08', '0000-00-00 00:00:00', 1),
(148, 'Label Library Add', 'label_library_add', 114, 146, 'add', 1, 0, 6, '2015-01-28 12:39:08', '0000-00-00 00:00:00', 1),
(149, 'Label Library Edit', 'label_library_edit', 114, 146, 'edit', 1, 0, 6, '2015-01-28 12:39:08', '0000-00-00 00:00:00', 1),
(150, 'Label Library Delete', 'label_library_delete', 114, 146, 'delete', 1, 0, 6, '2015-01-28 12:39:08', '0000-00-00 00:00:00', 1),
(151, 'Letter Library', 'letter_library', 114, 151, '', 1, 0, 6, '2015-01-28 12:39:45', '0000-00-00 00:00:00', 1),
(152, 'Letter Library View', 'letter_library_view', 114, 151, 'view', 1, 0, 6, '2015-01-28 12:39:45', '0000-00-00 00:00:00', 1),
(153, 'Letter Library Add', 'letter_library_add', 114, 151, 'add', 1, 0, 6, '2015-01-28 12:39:45', '0000-00-00 00:00:00', 1),
(154, 'Letter Library Edit', 'letter_library_edit', 114, 151, 'edit', 1, 0, 6, '2015-01-28 12:39:45', '0000-00-00 00:00:00', 1),
(155, 'Letter Library Delete', 'letter_library_delete', 114, 151, 'delete', 1, 0, 6, '2015-01-28 12:39:45', '0000-00-00 00:00:00', 1),
(156, 'Listing Manager', 'listing_manager', 0, 156, '', 0, 13, 6, '2015-01-28 13:43:29', '0000-00-00 00:00:00', 1),
(157, 'LISTING MANAGER View', 'listing_manager_view', -1, 156, 'view', 0, 0, 6, '2015-01-28 13:43:29', '0000-00-00 00:00:00', 1),
(158, 'LISTING MANAGER Add', 'listing_manager_add', -1, 156, 'add', 0, 0, 6, '2015-01-28 13:43:29', '0000-00-00 00:00:00', 1),
(159, 'LISTING MANAGER Edit', 'listing_manager_edit', -1, 156, 'edit', 0, 0, 6, '2015-01-28 13:43:29', '0000-00-00 00:00:00', 1),
(160, 'LISTING MANAGER Delete', 'listing_manager_delete', -1, 156, 'delete', 0, 0, 6, '2015-01-28 13:43:29', '0000-00-00 00:00:00', 1),
(161, 'Public Visibility', 'public_visibility', 156, 161, '', 0, 0, 6, '2015-01-28 13:43:49', '0000-00-00 00:00:00', 1),
(162, 'Public Visibility View', 'public_visibility_view', 156, 161, 'view', 0, 0, 6, '2015-01-28 13:43:49', '0000-00-00 00:00:00', 1),
(166, 'Flyer', 'flyer', 156, 166, '', 0, 0, 6, '2015-01-28 13:44:03', '0000-00-00 00:00:00', 1),
(167, 'Flyer View', 'flyer_view', 156, 166, 'view', 0, 0, 6, '2015-01-28 13:44:03', '0000-00-00 00:00:00', 1),
(171, 'Analytics', 'analytics', 0, 171, '', 1, 14, 6, '2015-01-28 13:44:23', '0000-00-00 00:00:00', 1),
(172, 'ANALYTICS View', 'analytics_view', -1, 171, 'view', 1, 0, 6, '2015-01-28 13:44:23', '0000-00-00 00:00:00', 1),
(176, 'Text Response', 'text_response', 0, 176, '', 1, 15, 6, '2015-01-28 13:46:24', '0000-00-00 00:00:00', 1),
(177, 'TEXT RESPONSE View', 'text_response_view', -1, 176, 'view', 1, 0, 6, '2015-01-28 13:46:24', '0000-00-00 00:00:00', 1),
(180, 'TEXT RESPONSE Delete', 'text_response_delete', -1, 176, 'delete', 1, 0, 6, '2015-01-28 13:46:24', '0000-00-00 00:00:00', 1),
(186, 'Configuration Contacts', 'configuration_contact', 0, 186, '', 1, 16, 6, '2015-01-28 13:51:47', '0000-00-00 00:00:00', 1),
(187, 'Contacts View', 'configuration_contact_view', -1, 186, 'view', 1, 0, 6, '2015-01-28 13:51:47', '0000-00-00 00:00:00', 1),
(191, 'Configuration Master Template Library', 'configuration_template_library', 0, 191, '', 1, 17, 6, '2015-01-28 13:52:33', '0000-00-00 00:00:00', 1),
(192, 'Master Template Library View', 'configuration_template_library_view', -1, 191, 'view', 1, 0, 6, '2015-01-28 13:52:33', '0000-00-00 00:00:00', 1),
(196, 'Configuration Listing Manager', 'configuration_listing_manager', 0, 196, '', 0, 18, 6, '2015-01-28 13:53:07', '0000-00-00 00:00:00', 1),
(197, 'Listing Manager View', 'configuration_listing_manager_view', -1, 196, 'view', 0, 0, 6, '2015-01-28 13:53:07', '0000-00-00 00:00:00', 1),
(201, 'Work Time Configuration', 'work_time_configuration', 0, 201, '', 1, 19, 6, '2015-01-28 13:53:27', '0000-00-00 00:00:00', 1),
(202, 'Work Time Configuration View', 'work_time_configuration_view', -1, 201, 'view', 1, 0, 6, '2015-01-28 13:53:27', '0000-00-00 00:00:00', 1),
(206, 'Social Account', 'social_account', 0, 206, '', 1, 20, 6, '2015-01-28 13:53:46', '0000-00-00 00:00:00', 1),
(207, 'Social Account View', 'social_account_view', -1, 206, 'view', 1, 0, 6, '2015-01-28 13:53:46', '0000-00-00 00:00:00', 1),
(211, 'Email Signature', 'email_signature', 0, 211, '', 1, 21, 6, '2015-01-28 13:54:05', '0000-00-00 00:00:00', 1),
(212, 'Email Signature View', 'email_signature_view', -1, 211, 'view', 1, 0, 6, '2015-01-28 13:54:05', '0000-00-00 00:00:00', 1),
(213, 'Email Signature Add', 'email_signature_add', -1, 211, 'add', 1, 0, 6, '2015-01-28 13:54:05', '0000-00-00 00:00:00', 1),
(214, 'Email Signature Edit', 'email_signature_edit', -1, 211, 'edit', 1, 0, 6, '2015-01-28 13:54:05', '0000-00-00 00:00:00', 1),
(215, 'Email Signature Delete', 'email_signature_delete', -1, 211, 'delete', 1, 0, 6, '2015-01-28 13:54:05', '0000-00-00 00:00:00', 1),
(216, 'User Management', 'user_management', 0, 216, '', 1, 22, 6, '2015-01-28 13:54:23', '0000-00-00 00:00:00', 1),
(217, 'User Management View', 'user_management_view', -1, 216, 'view', 1, 0, 6, '2015-01-28 13:54:23', '0000-00-00 00:00:00', 1),
(218, 'User Management Add', 'user_management_add', -1, 216, 'add', 1, 0, 6, '2015-01-28 13:54:23', '0000-00-00 00:00:00', 1),
(219, 'User Management Edit', 'user_management_edit', -1, 216, 'edit', 1, 0, 6, '2015-01-28 13:54:23', '0000-00-00 00:00:00', 1),
(220, 'User Management Delete', 'user_management_delete', -1, 216, 'delete', 1, 0, 6, '2015-01-28 13:54:23', '0000-00-00 00:00:00', 1),
(221, 'Market Watch', 'market_watch', 0, 221, '', 0, 23, 6, '2015-02-11 03:44:23', '0000-00-00 00:00:00', 1),
(222, 'Market Watch View', 'market_watch_view', -1, 221, 'view', 0, 0, 6, '2015-02-11 03:44:23', '0000-00-00 00:00:00', 1),
(223, 'Bomb Bomb Library', 'bomb_bomb_library', 114, 223, '', 0, 0, 1, '2015-03-05 02:15:43', '0000-00-00 00:00:00', 1),
(224, 'Bomb Bomb Library View', 'bomb_bomb_library_view', 114, 223, 'view', 0, 0, 1, '2015-03-05 02:15:43', '0000-00-00 00:00:00', 1),
(225, 'Bomb Bomb Library Add', 'bomb_bomb_library_add', 114, 223, 'add', 0, 0, 1, '2015-03-05 02:15:43', '0000-00-00 00:00:00', 1),
(226, 'Bomb Bomb Library Edit', 'bomb_bomb_library_edit', 114, 223, 'edit', 0, 0, 1, '2015-03-05 02:15:43', '0000-00-00 00:00:00', 1),
(227, 'Bomb Bomb Library Delete', 'bomb_bomb_library_delete', 114, 223, 'delete', 0, 0, 1, '2015-03-05 02:15:43', '0000-00-00 00:00:00', 1),
(228, 'Bomb Bomb Email Blast', 'bomb_bomb_email_blast', 0, 228, '', 0, 7, 1, '2015-03-09 01:16:50', '0000-00-00 00:00:00', 1),
(229, 'Bomb Bomb Email Blast View', 'bomb_bomb_email_blast_view', -1, 228, 'view', 0, 0, 1, '2015-03-09 01:16:50', '0000-00-00 00:00:00', 1),
(230, 'Bomb Bomb Email Blast Add', 'bomb_bomb_email_blast_add', -1, 228, 'add', 0, 0, 1, '2015-03-09 01:16:50', '0000-00-00 00:00:00', 1),
(231, 'Bomb Bomb Email Blast Edit', 'bomb_bomb_email_blast_edit', -1, 228, 'edit', 0, 0, 1, '2015-03-09 01:16:50', '0000-00-00 00:00:00', 1),
(232, 'Bomb Bomb Email Blast Delete', 'bomb_bomb_email_blast_delete', -1, 228, 'delete', 0, 0, 1, '2015-03-09 01:16:50', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `package_master`
--

CREATE TABLE IF NOT EXISTS `package_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(50) NOT NULL,
  `email_counter` int(11) unsigned NOT NULL,
  `sms_counter` int(11) unsigned NOT NULL,
  `contacts_counter` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('Active','Deactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `phone_call_script_master`
--

CREATE TABLE IF NOT EXISTS `phone_call_script_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `template_subject` varchar(250) NOT NULL,
  `calling_script` text NOT NULL,
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `phone_call_script_master`
--

INSERT INTO `phone_call_script_master` (`id`, `template_name`, `template_category`, `template_subcategory`, `template_subject`, `calling_script`, `publish_flag`, `superadmin_template_id`, `admin_publish_date`, `superadmin_publish_date`, `is_default`, `edit_flag`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Phone call temp superadmin', 2, 0, 'Thanks for your interest {(first name)}', 'asdf asf ds ds fs d asd fs df sdg sd g', 0, 0, NULL, '2015-01-20 13:22:58', 1, 0, '2015-01-20 13:22:58', 1, '0000-00-00 00:00:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `pm`
--

CREATE TABLE IF NOT EXISTS `pm` (
  `ID` int(11) DEFAULT NULL,
  `LN` int(10) DEFAULT NULL,
  `PIC` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_contact_trans`
--

CREATE TABLE IF NOT EXISTS `property_listing_contact_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key,auto increment',
  `property_id` int(11) NOT NULL COMMENT 'From property_listing_master',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_document_trans`
--

CREATE TABLE IF NOT EXISTS `property_listing_document_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT 'From contact_master',
  `document_type_id` int(11) NOT NULL COMMENT 'From contact_document_type',
  `doc_name` varchar(250) NOT NULL,
  `doc_desc` text NOT NULL,
  `doc_file` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_master`
--

CREATE TABLE IF NOT EXISTS `property_listing_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_no` varchar(50) NOT NULL,
  `assign_to` int(11) NOT NULL COMMENT 'From login_master',
  `assign_date` datetime NOT NULL,
  `property_title` varchar(250) NOT NULL,
  `property_type` int(11) NOT NULL,
  `property_type_name` varchar(250) NOT NULL,
  `transaction_type` int(11) NOT NULL,
  `transaction_type_name` varchar(250) NOT NULL,
  `status_id` int(11) NOT NULL,
  `status_name` varchar(250) NOT NULL,
  `listed_date` date NOT NULL,
  `listing_expire_date` date NOT NULL,
  `closed_date` date NOT NULL,
  `pending_date` date NOT NULL,
  `seller_name` varchar(250) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_unit` varchar(50) NOT NULL,
  `price_name` varchar(250) NOT NULL,
  `year_built` int(4) NOT NULL,
  `taxes` varchar(250) NOT NULL,
  `tax_id` varchar(250) NOT NULL,
  `lot_no` varchar(250) NOT NULL,
  `block` varchar(250) NOT NULL,
  `building_name` varchar(250) NOT NULL,
  `remarks` text NOT NULL,
  `address_line_1` varchar(250) NOT NULL,
  `address_line_2` varchar(250) NOT NULL,
  `district` varchar(250) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `living_area` decimal(12,2) NOT NULL,
  `living_area_unit` varchar(250) NOT NULL,
  `living_area_name` varchar(250) NOT NULL,
  `total_area` decimal(12,2) NOT NULL,
  `total_area_unit` varchar(50) NOT NULL,
  `total_area_name` varchar(250) NOT NULL,
  `total_unfinished` decimal(12,2) NOT NULL,
  `total_unfinished_unit` varchar(50) NOT NULL,
  `lot_type` int(11) NOT NULL,
  `lot_type_name` varchar(250) NOT NULL,
  `lot_size` decimal(12,2) NOT NULL,
  `lot_size_unit` varchar(50) NOT NULL,
  `lot_size_name` varchar(250) NOT NULL,
  `lot_dimension` decimal(12,2) NOT NULL,
  `bedrooms_count` int(5) NOT NULL,
  `bathrooms_count` int(5) NOT NULL,
  `half_bathrooms_count` int(5) NOT NULL,
  `parking_count` int(5) NOT NULL,
  `kitchen_count` int(5) NOT NULL,
  `floor_count` int(5) NOT NULL,
  `expected_commission` decimal(12,2) NOT NULL,
  `expected_commission_unit` varchar(50) NOT NULL,
  `expected_commission_name` varchar(250) NOT NULL,
  `commission_received` decimal(12,2) NOT NULL,
  `commission_received_unit` varchar(50) NOT NULL,
  `commission_received_name` varchar(250) NOT NULL,
  `interaction_plan_id` int(11) NOT NULL COMMENT 'From interaction_plan_master',
  `sewer_id` int(11) NOT NULL COMMENT 'From property_listing__sewer_master',
  `sewer_name` varchar(250) NOT NULL COMMENT 'From property_listing__sewer_master',
  `basement_id` int(11) NOT NULL COMMENT 'From property_listing__basement_master',
  `basement_name` varchar(250) NOT NULL COMMENT 'From property_listing__basement_master',
  `parking_type_id` int(11) NOT NULL COMMENT 'From property_listing__parking_type_master',
  `parking_type_name` varchar(250) NOT NULL COMMENT 'From property_listing__parking_type_master',
  `parking_spaces` varchar(250) NOT NULL,
  `builder_name` varchar(250) NOT NULL,
  `style_id` int(11) NOT NULL COMMENT 'From property_listing__style_master',
  `style_name` varchar(250) NOT NULL COMMENT 'From property_listing__style_master',
  `exterior_finish_id` int(11) NOT NULL COMMENT 'From property_listing__exterior_finish_master',
  `exterior_finish_name` int(11) NOT NULL COMMENT 'From property_listing__exterior_finish_master',
  `foundation_id` int(11) NOT NULL COMMENT 'From property_listing__foundation_master',
  `foundation_name` int(11) NOT NULL COMMENT 'From property_listing__foundation_master',
  `roof_id` int(11) NOT NULL COMMENT 'From property_listing__roof_master',
  `roof_name` varchar(250) NOT NULL COMMENT 'From property_listing__roof_master',
  `architecture_id` int(11) NOT NULL COMMENT 'From property_listing__architecture_master',
  `architecture_name` varchar(250) NOT NULL COMMENT 'From property_listing__architecture_master',
  `green_certification_id` int(11) NOT NULL COMMENT 'From property_listing__green_certification_master',
  `green_certification_name` varchar(250) NOT NULL COMMENT 'From property_listing__green_certification_master',
  `fireplace_id` int(11) NOT NULL COMMENT 'From property_listing__fireplace_master',
  `fireplace_name` varchar(250) NOT NULL COMMENT 'From property_listing__fireplace_master',
  `energy_source_id` int(11) NOT NULL COMMENT 'From property_listing__energy_source_master',
  `energy_source_name` varchar(250) NOT NULL COMMENT 'From property_listing__energy_source_master',
  `heating_cooling_id` int(11) NOT NULL COMMENT 'From property_listing__heating_cooling_master',
  `heating_cooling_name` varchar(250) NOT NULL COMMENT 'From property_listing__heating_cooling_master',
  `floor_covering_id` int(11) NOT NULL COMMENT 'From property_listing__floor_covering_master',
  `floor_covering_name` varchar(250) NOT NULL COMMENT 'From property_listing__floor_covering_master',
  `interior_feature_id` int(11) NOT NULL COMMENT 'From property_listing__interior_feature_master',
  `interior_feature_name` varchar(250) NOT NULL COMMENT 'From property_listing__interior_feature_master',
  `water_company_id` int(11) NOT NULL COMMENT 'From property_listing__water_company_master',
  `water_company_name` varchar(250) NOT NULL COMMENT 'From property_listing__water_company_master',
  `power_company_id` int(11) NOT NULL COMMENT 'From property_listing__power_company_master',
  `power_company_name` varchar(250) NOT NULL COMMENT 'From property_listing__power_company_master',
  `sewer_company_id` int(11) NOT NULL COMMENT 'From property_listing__sewer_company_master',
  `sewer_company_name` varchar(250) NOT NULL COMMENT 'From property_listing__sewer_company_master',
  `lockbox_type_id` int(11) NOT NULL COMMENT 'From lockbox_type_master',
  `lockbox_type_name` varchar(250) NOT NULL COMMENT 'From lockbox_type_master',
  `lockbox_serial` varchar(250) NOT NULL,
  `lockbox_combination` varchar(250) NOT NULL,
  `lockbox_location_on_property` varchar(250) NOT NULL,
  `lockbox_notes` text NOT NULL,
  `is_visible_to_public` enum('1','2') NOT NULL COMMENT '1 - Yes, 2 - No',
  `live_link` varchar(500) NOT NULL,
  `google_analytics_code` text NOT NULL,
  `property_selected_theme` enum('1','2','3') NOT NULL COMMENT '1,2,3',
  `slug` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'from login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'fromlogin_master',
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1=''active'',0=''inactive''',
  PRIMARY KEY (`id`),
  KEY `assign_to` (`assign_to`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_offers_trans`
--

CREATE TABLE IF NOT EXISTS `property_listing_offers_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT 'From property_listing_master',
  `offer_price` decimal(12,2) NOT NULL,
  `offer_price_unit_id` int(11) NOT NULL COMMENT 'from property_listing__unit_master',
  `offer_price_unit` varchar(50) NOT NULL COMMENT 'Insert - unit_name',
  `offer_date` date NOT NULL,
  `offer_agent_name` varchar(250) NOT NULL,
  `offer_phone` varchar(15) NOT NULL,
  `offer_notes` text NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_open_houses_trans`
--

CREATE TABLE IF NOT EXISTS `property_listing_open_houses_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT 'From property_listing_master',
  `open_house_date` date NOT NULL,
  `open_house_time` time NOT NULL COMMENT 'from property_listing__unit_master',
  `open_house_end_time` time NOT NULL,
  `open_house_notes` text NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_photo_trans`
--

CREATE TABLE IF NOT EXISTS `property_listing_photo_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT 'From property_listing_master',
  `photo` varchar(250) NOT NULL,
  `created_date` date NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_price_change_trans`
--

CREATE TABLE IF NOT EXISTS `property_listing_price_change_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT 'From property_listing_master',
  `price_change_date` date NOT NULL,
  `new_price` decimal(12,2) NOT NULL,
  `new_price_unit_id` int(11) NOT NULL COMMENT 'from property_listing__unit_master',
  `new_price_unit` varchar(50) NOT NULL COMMENT 'from property_listing__unit_master',
  `price_notes` text NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing_showings_trans`
--

CREATE TABLE IF NOT EXISTS `property_listing_showings_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT 'From property_listing_master',
  `showings_date` date NOT NULL,
  `showings_time` time NOT NULL COMMENT 'from property_listing__unit_master',
  `showings_notes` text NOT NULL,
  `showings_agent_name` varchar(250) NOT NULL,
  `showings_agent_id` int(50) NOT NULL,
  `showings_agent_phone` varchar(15) NOT NULL,
  `showings_agent_email` varchar(100) NOT NULL,
  `showings_agent_office` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'From login_master',
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL COMMENT 'From login_master',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__architecture_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__architecture_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__basement_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__basement_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__document_type_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__document_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__energy_source_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__energy_source_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__exterior_finish_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__exterior_finish_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__fireplace_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__fireplace_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__floor_covering_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__floor_covering_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__foundation_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__foundation_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__green_certification_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__green_certification_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__heating_cooling_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__heating_cooling_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__interior_feature_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__interior_feature_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__lockbox_type_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__lockbox_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__lot_type_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__lot_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__parking_type_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__parking_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__power_company_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__power_company_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__property_type_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__property_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__roof_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__roof_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__sewer_company_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__sewer_company_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__sewer_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__sewer_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__status_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__status_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__style_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__style_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__transaction_type_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__transaction_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__unit_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__unit_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `unit_type` int(2) NOT NULL COMMENT '1. Price, 2. Area, 3. Size',
  `unit_title` varchar(50) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_listing__water_company_master`
--

CREATE TABLE IF NOT EXISTS `property_listing__water_company_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rights_master`
--

CREATE TABLE IF NOT EXISTS `rights_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `rights_desc` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `save_property`
--

CREATE TABLE IF NOT EXISTS `save_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `property_id` int(10) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_campaign_master`
--

CREATE TABLE IF NOT EXISTS `sms_campaign_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` int(11) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `sms_message` text NOT NULL,
  `sms_send_type` int(11) NOT NULL COMMENT '1-Now, 2-Datetime',
  `sms_send_date` date NOT NULL,
  `sms_send_time` time NOT NULL,
  `is_draft` enum('1','0') NOT NULL COMMENT '1-Yes,0-No ',
  `sms_type` enum('Campaign','Intereaction_plan') NOT NULL COMMENT '1-Campaign, 2-Intreaction Plan',
  `interaction_id` int(11) NOT NULL,
  `sms_send_auto` enum('1','0') NOT NULL COMMENT '1-Yes,0-No ',
  `is_sent_to_all` enum('1','0') NOT NULL DEFAULT '1',
  `total_sent` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1-Active,0-Deactive',
  PRIMARY KEY (`id`),
  KEY `interaction_id` (`interaction_id`),
  KEY `template_category` (`template_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_campaign_recepient_trans`
--

CREATE TABLE IF NOT EXISTS `sms_campaign_recepient_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_campaign_id` int(11) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `sms_message` text NOT NULL,
  `contact_id` int(11) NOT NULL,
  `contact_type` text NOT NULL,
  `is_send` enum('1','0') NOT NULL DEFAULT '0',
  `is_sms_exist` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1 - Exist , 0 - Not exist',
  `sent_date` datetime NOT NULL,
  `send_sms_date` date NOT NULL COMMENT 'For interaction',
  PRIMARY KEY (`id`),
  KEY `sms_campaign_id` (`sms_campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_event_master`
--

CREATE TABLE IF NOT EXISTS `sms_event_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1-Active,2-Deactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sms_event_master`
--

INSERT INTO `sms_event_master` (`id`, `name`, `title`, `status`) VALUES
(1, 'new_lead', 'New Lead', '1'),
(2, 'new_home_valuation_request', 'New Home Valuation Request', '1'),
(3, 'welcome_message_for_buyer_or_seller', 'Welcome Message for Buyer/Seller', '1'),
(4, 'welcome_message_for_buyer', 'Welcome Message for Buyer', '1'),
(5, 'welcome_message_for_seller', 'Welcome Message for Seller', '1'),
(6, 'form_builder_lead', 'Form Builder Lead', '1');

-- --------------------------------------------------------

--
-- Table structure for table `sms_response`
--

CREATE TABLE IF NOT EXISTS `sms_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_number` varchar(20) NOT NULL,
  `to_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `from_city` varchar(100) DEFAULT NULL,
  `from_state` varchar(100) DEFAULT NULL,
  `from_country` varchar(100) DEFAULT NULL,
  `sms_staus` varchar(20) DEFAULT NULL,
  `response_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_text_template_master`
--

CREATE TABLE IF NOT EXISTS `sms_text_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `sms_message` text NOT NULL,
  `sms_send_type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1-Auto responder,2-News letter',
  `sms_event` int(11) NOT NULL COMMENT 'From sms_event_master',
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `social_master`
--

CREATE TABLE IF NOT EXISTS `social_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` int(1) NOT NULL,
  `page_name` varchar(500) DEFAULT NULL,
  `template_name` int(11) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `social_message` text NOT NULL,
  `social_send_type` int(11) NOT NULL COMMENT '1-Now, 2-Datetime',
  `social_send_date` date NOT NULL,
  `social_send_time` time NOT NULL,
  `is_draft` enum('1','0') NOT NULL COMMENT '1-Yes,0-No ',
  `is_sent_to_all` enum('1','0') NOT NULL,
  `total_sent` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1-Active,0-Deactive',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_template_master`
--

CREATE TABLE IF NOT EXISTS `social_media_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(250) NOT NULL,
  `template_category` int(11) NOT NULL,
  `template_subcategory` int(11) NOT NULL,
  `template_subject` varchar(250) NOT NULL,
  `post_content` text NOT NULL,
  `publish_flag` int(1) NOT NULL,
  `superadmin_template_id` int(11) NOT NULL,
  `admin_publish_date` datetime DEFAULT NULL,
  `superadmin_publish_date` datetime DEFAULT NULL,
  `is_default` int(1) NOT NULL,
  `edit_flag` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `template_category` (`template_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `social_media_template_platform_trans`
--

CREATE TABLE IF NOT EXISTS `social_media_template_platform_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `social_template_id` int(11) NOT NULL COMMENT 'From social_media_template_master',
  `platform` enum('Facebook','Twitter','Linkedin') NOT NULL COMMENT '1-Facebook,2-Twitter,3-Linkdin',
  PRIMARY KEY (`id`),
  KEY `social_template_id` (`social_template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `social_platform_trans`
--

CREATE TABLE IF NOT EXISTS `social_platform_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `social_template_id` int(11) NOT NULL COMMENT 'From social_media_template_master',
  `platform` enum('Facebook','Twitter','Linkedin') NOT NULL COMMENT '1-Facebook,2-Twitter,3-Linkdin',
  PRIMARY KEY (`id`),
  KEY `social_template_id` (`social_template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `social_recepient_trans`
--

CREATE TABLE IF NOT EXISTS `social_recepient_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `social_campaign_id` int(11) NOT NULL,
  `social_message` text NOT NULL,
  `contact_id` int(11) NOT NULL,
  `contact_type` text NOT NULL,
  `is_send` enum('1','0') NOT NULL DEFAULT '0',
  `is_social_exist` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1 - Exist , 0 - Not exist',
  `sent_date` datetime NOT NULL,
  `send_social_date` date NOT NULL COMMENT 'For interaction',
  PRIMARY KEY (`id`),
  KEY `social_campaign_id` (`social_campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sold_property_by_city`
--

CREATE TABLE IF NOT EXISTS `sold_property_by_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `mls_id` int(11) NOT NULL,
  `city` varchar(26) NOT NULL,
  `sold_date` datetime NOT NULL,
  `address` text NOT NULL,
  `price` float(10,2) NOT NULL,
  `price_per_sqft` float(10,2) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sold_property_by_zip`
--

CREATE TABLE IF NOT EXISTS `sold_property_by_zip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `mls_id` int(11) NOT NULL,
  `property_type` varchar(4) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `sold_date` datetime NOT NULL,
  `address` text NOT NULL,
  `price` float(10,2) NOT NULL,
  `price_per_sqft` float(10,2) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sold_property_city_status`
--

CREATE TABLE IF NOT EXISTS `sold_property_city_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `city` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-> pending, 2->working, 3-> completed',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sold_property_zip_status`
--

CREATE TABLE IF NOT EXISTS `sold_property_zip_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mls_id` int(11) NOT NULL,
  `property_type` varchar(4) NOT NULL,
  `zip_code` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-> pending, 2->working, 3-> completed',
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_master`
--

CREATE TABLE IF NOT EXISTS `task_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(250) NOT NULL,
  `desc` text NOT NULL,
  `task_date` date NOT NULL,
  `is_email` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-Yes,0-No',
  `email_time_before` int(11) NOT NULL,
  `email_time_type` enum('1','2') NOT NULL COMMENT '1-Hour,2-Day',
  `reminder_email_date` datetime NOT NULL COMMENT 'Reminder Date To Email Notification',
  `is_popup` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-Yes,0-No',
  `popup_time_before` int(11) NOT NULL,
  `popup_time_type` enum('1','2') NOT NULL COMMENT '1-Hour,2-Day',
  `reminder_popup_date` datetime NOT NULL COMMENT 'Reminder Date To Pop-Up Notification',
  `is_close` enum('0','1') NOT NULL COMMENT 'If Add User In Personal Task ',
  `is_completed` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-Completed,0-Not completed',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1-Activated,0-Deactivated',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_user_transcation`
--

CREATE TABLE IF NOT EXISTS `task_user_transcation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL COMMENT 'From task-master table',
  `user_id` int(11) NOT NULL COMMENT 'From user_master table',
  `is_mail_sent` int(1) NOT NULL DEFAULT '0',
  `is_completed` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1-Yes,0-No',
  `is_close` enum('0','1') NOT NULL COMMENT '0. Open Popup 1 .Not Open Popup',
  `completed_by` int(11) NOT NULL,
  `completed_date` datetime NOT NULL,
  `status` enum('1','0') NOT NULL COMMENT '1-Activated,0-Deactivated',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_address_trans`
--

CREATE TABLE IF NOT EXISTS `user_address_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `address_type` int(11) NOT NULL COMMENT 'From contact_address_type',
  `address_line1` varchar(500) NOT NULL,
  `address_line2` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_contact_trans`
--

CREATE TABLE IF NOT EXISTS `user_contact_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `agent_type` enum('Normal','Lender','Livewire') NOT NULL DEFAULT 'Normal' COMMENT 'Normal,Lender,Livewire',
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `contact_id` int(11) NOT NULL COMMENT 'From contact_master',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_domain_trans`
--

CREATE TABLE IF NOT EXISTS `user_domain_trans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `domain_id` int(11) NOT NULL COMMENT 'From child_website_domain_master',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_emails_trans`
--

CREATE TABLE IF NOT EXISTS `user_emails_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `email_type` int(11) NOT NULL COMMENT 'From contact_email_type',
  `email_address` varchar(250) NOT NULL,
  `is_default` enum('0','1') NOT NULL COMMENT '0. No, 1.Yes',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `email_type` (`email_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_leave_data`
--

CREATE TABLE IF NOT EXISTS `user_leave_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user master',
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_trans`
--

CREATE TABLE IF NOT EXISTS `user_login_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) NOT NULL COMMENT 'From login_master',
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_ip` varchar(50) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `db_name` varchar(255) NOT NULL,
  `user_type` tinyint(4) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `start_time_ist` datetime NOT NULL,
  `start_time_pst` datetime NOT NULL,
  `end_time_ist` datetime NOT NULL,
  `end_time_pst` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `user_login_trans`
--

INSERT INTO `user_login_trans` (`id`, `login_id`, `email_id`, `password`, `created_ip`, `admin_name`, `db_name`, `user_type`, `start_date`, `end_date`, `start_time_ist`, `start_time_pst`, `end_time_ist`, `end_time_pst`) VALUES
(1, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', '', 'CRM', 1, '2015-07-13 05:53:52', '2015-07-13 05:54:49', '2015-07-13 15:23:52', '2015-07-13 01:53:52', '2015-07-13 15:24:49', '2015-07-13 01:54:49'),
(2, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', '', 'CRM', 1, '2015-07-13 05:54:57', '2015-07-13 06:50:26', '2015-07-13 15:24:57', '2015-07-13 01:54:57', '2015-07-13 16:20:26', '2015-07-13 02:50:26'),
(3, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-13 06:50:31', '0000-00-00 00:00:00', '2015-07-13 16:20:31', '2015-07-13 02:50:31', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 3, 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 3, '2015-07-13 09:15:13', '2015-07-13 09:51:29', '2015-07-13 18:45:13', '2015-07-13 05:15:13', '2015-07-13 19:21:29', '2015-07-13 05:51:29'),
(5, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', '', 'CRM', 1, '2015-07-13 09:48:29', '2015-07-13 10:01:50', '2015-07-13 19:18:29', '2015-07-13 05:48:29', '2015-07-13 19:31:50', '2015-07-13 06:01:50'),
(6, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-13 09:50:02', '2015-07-13 09:51:25', '2015-07-13 19:20:02', '2015-07-13 05:50:02', '2015-07-13 19:21:25', '2015-07-13 05:51:25'),
(7, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', '', 'CRM', 1, '2015-07-13 09:51:36', '0000-00-00 00:00:00', '2015-07-13 19:21:36', '2015-07-13 05:51:36', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 3, 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.25.239', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 3, '2015-07-13 09:51:59', '0000-00-00 00:00:00', '2015-07-13 19:21:59', '2015-07-13 05:51:59', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-13 10:01:56', '2015-07-13 10:03:56', '2015-07-13 19:31:56', '2015-07-13 06:01:56', '2015-07-13 19:33:56', '2015-07-13 06:03:56'),
(10, 3, 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.25.239', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 3, '2015-07-13 10:04:09', '2015-07-13 10:04:37', '2015-07-13 19:34:09', '2015-07-13 06:04:09', '2015-07-13 19:34:37', '2015-07-13 06:04:37'),
(11, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.25.239', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-13 10:04:49', '2015-07-13 10:06:42', '2015-07-13 19:34:49', '2015-07-13 06:04:49', '2015-07-13 19:36:42', '2015-07-13 06:06:42'),
(12, 3, 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 3, '2015-07-13 10:11:35', '2015-07-13 10:11:56', '2015-07-13 19:41:35', '2015-07-13 06:11:35', '2015-07-13 19:41:56', '2015-07-13 06:11:56'),
(13, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-13 10:12:00', '0000-00-00 00:00:00', '2015-07-13 19:42:00', '2015-07-13 06:12:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.71.136', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-13 11:25:22', '0000-00-00 00:00:00', '2015-07-13 20:55:22', '2015-07-13 07:25:22', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.75.71', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-14 02:42:05', '2015-07-14 06:05:05', '2015-07-14 12:12:05', '2015-07-13 22:42:05', '2015-07-14 15:35:05', '2015-07-14 02:05:05'),
(16, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.75.71', '', 'CRM', 1, '2015-07-14 02:43:16', '0000-00-00 00:00:00', '2015-07-14 12:13:16', '2015-07-13 22:43:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.71.136', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-14 03:48:07', '2015-07-14 03:48:59', '2015-07-14 13:18:07', '2015-07-13 23:48:07', '2015-07-14 13:18:59', '2015-07-13 23:48:59'),
(18, 2, 'tushar.solanki@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.169.71.136', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-14 03:49:09', '2015-07-14 03:49:39', '2015-07-14 13:19:09', '2015-07-13 23:49:09', '2015-07-14 13:19:39', '2015-07-13 23:49:39'),
(19, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.71.136', '', 'CRM', 1, '2015-07-14 03:49:55', '2015-07-14 04:04:30', '2015-07-14 13:19:55', '2015-07-13 23:49:55', '2015-07-14 13:34:30', '2015-07-14 00:04:30'),
(20, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.71.136', '', 'CRM', 1, '2015-07-14 04:04:49', '2015-07-14 04:04:56', '2015-07-14 13:34:49', '2015-07-14 00:04:49', '2015-07-14 13:34:56', '2015-07-14 00:04:56'),
(21, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.169.71.136', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-07-14 04:05:11', '2015-07-14 04:55:05', '2015-07-14 13:35:11', '2015-07-14 00:05:11', '2015-07-14 14:25:05', '2015-07-14 00:55:05'),
(22, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.71.136', '', 'CRM', 1, '2015-07-14 04:55:10', '2015-07-14 04:55:35', '2015-07-14 14:25:10', '2015-07-14 00:55:10', '2015-07-14 14:25:35', '2015-07-14 00:55:35'),
(23, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.169.71.136', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-07-14 04:55:38', '0000-00-00 00:00:00', '2015-07-14 14:25:38', '2015-07-14 00:55:38', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', '', 'CRM', 1, '2015-07-14 06:04:12', '2015-07-14 06:04:38', '2015-07-14 15:34:12', '2015-07-14 02:04:12', '2015-07-14 15:34:38', '2015-07-14 02:04:38'),
(25, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.74.8', '', 'CRM', 1, '2015-07-14 06:05:43', '2015-07-14 06:06:06', '2015-07-14 15:35:43', '2015-07-14 02:05:43', '2015-07-14 15:36:06', '2015-07-14 02:06:06'),
(26, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-14 06:06:12', '2015-07-14 06:08:53', '2015-07-14 15:36:12', '2015-07-14 02:06:12', '2015-07-14 15:38:53', '2015-07-14 02:08:53'),
(27, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', '', 'CRM', 1, '2015-07-14 06:09:17', '2015-07-14 06:09:27', '2015-07-14 15:39:17', '2015-07-14 02:09:17', '2015-07-14 15:39:27', '2015-07-14 02:09:27'),
(28, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.74.8', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-14 06:09:57', '2015-07-14 06:12:35', '2015-07-14 15:39:57', '2015-07-14 02:09:57', '2015-07-14 15:42:35', '2015-07-14 02:12:35'),
(29, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.170.109.155', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-07-14 06:12:48', '0000-00-00 00:00:00', '2015-07-14 15:42:48', '2015-07-14 02:12:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.74.8', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-14 06:12:56', '2015-07-14 07:11:01', '2015-07-14 15:42:56', '2015-07-14 02:12:56', '2015-07-14 16:41:01', '2015-07-14 03:11:01'),
(31, 3, 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.74.8', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 3, '2015-07-14 07:11:06', '0000-00-00 00:00:00', '2015-07-14 16:41:06', '2015-07-14 03:11:06', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '182.70.74.8', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-07-14 09:39:52', '0000-00-00 00:00:00', '2015-07-14 19:09:52', '2015-07-14 05:39:52', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.73.104', '', 'CRM', 1, '2015-07-31 01:43:00', '2015-07-31 01:43:22', '2015-07-31 11:13:00', '2015-07-30 21:43:00', '2015-07-31 11:13:22', '2015-07-30 21:43:22'),
(34, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.169.73.104', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-07-31 01:43:35', '2015-07-31 01:43:41', '2015-07-31 11:13:35', '2015-07-30 21:43:35', '2015-07-31 11:13:41', '2015-07-30 21:43:41'),
(35, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-07-31 01:47:16', '0000-00-00 00:00:00', '2015-07-31 11:17:16', '2015-07-30 21:47:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-10 04:47:46', '0000-00-00 00:00:00', '2015-08-10 14:17:46', '2015-08-10 00:47:46', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '202.131.112.10', '', 'CRM', 1, '2015-08-10 08:34:06', '2015-08-10 08:34:19', '2015-08-10 18:04:06', '2015-08-10 04:34:06', '2015-08-10 18:04:19', '2015-08-10 04:34:19'),
(38, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '202.131.112.10', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-10 08:34:25', '0000-00-00 00:00:00', '2015-08-10 18:04:25', '2015-08-10 04:34:25', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-10 08:36:01', '2015-08-10 10:51:31', '2015-08-10 18:06:01', '2015-08-10 04:36:01', '2015-08-10 20:21:31', '2015-08-10 06:51:31'),
(40, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 00:56:56', '0000-00-00 00:00:00', '2015-08-11 10:26:56', '2015-08-10 20:56:56', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.170.98.123', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 02:47:44', '2015-08-11 03:09:19', '2015-08-11 12:17:44', '2015-08-10 22:47:44', '2015-08-11 12:39:19', '2015-08-10 23:09:19'),
(42, 8, 'outbound1@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '122.170.98.123', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 3, '2015-08-11 03:09:45', '2015-08-11 03:12:52', '2015-08-11 12:39:45', '2015-08-10 23:09:45', '2015-08-11 12:42:52', '2015-08-10 23:12:52'),
(43, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.170.98.123', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 03:13:06', '2015-08-11 03:21:32', '2015-08-11 12:43:06', '2015-08-10 23:13:06', '2015-08-11 12:51:32', '2015-08-10 23:21:32'),
(44, 5, 'visa2@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '122.170.98.123', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 3, '2015-08-11 03:21:49', '2015-08-11 03:23:53', '2015-08-11 12:51:49', '2015-08-10 23:21:49', '2015-08-11 12:53:53', '2015-08-10 23:23:53'),
(45, 9, 'outbound2@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '122.170.98.123', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 3, '2015-08-11 03:24:16', '2015-08-11 03:27:00', '2015-08-11 12:54:16', '2015-08-10 23:24:16', '2015-08-11 12:57:00', '2015-08-10 23:27:00'),
(46, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '122.170.98.123', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 03:27:14', '0000-00-00 00:00:00', '2015-08-11 12:57:14', '2015-08-10 23:27:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 06:08:49', '2015-08-11 06:13:14', '2015-08-11 15:38:49', '2015-08-11 02:08:49', '2015-08-11 15:43:14', '2015-08-11 02:13:14'),
(48, 8, 'outbound1@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 3, '2015-08-11 06:13:30', '2015-08-11 06:13:44', '2015-08-11 15:43:30', '2015-08-11 02:13:30', '2015-08-11 15:43:44', '2015-08-11 02:13:44'),
(49, 9, 'outbound2@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 3, '2015-08-11 06:14:08', '2015-08-11 06:15:17', '2015-08-11 15:44:08', '2015-08-11 02:14:08', '2015-08-11 15:45:17', '2015-08-11 02:15:17'),
(50, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 06:18:01', '0000-00-00 00:00:00', '2015-08-11 15:48:01', '2015-08-11 02:18:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 06:31:01', '0000-00-00 00:00:00', '2015-08-11 16:01:01', '2015-08-11 02:31:01', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-11 08:43:48', '0000-00-00 00:00:00', '2015-08-11 18:13:48', '2015-08-11 04:43:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '202.131.112.10', '', 'CRM', 1, '2015-08-12 06:33:53', '2015-08-12 06:34:02', '2015-08-12 16:03:53', '2015-08-12 02:33:53', '2015-08-12 16:04:02', '2015-08-12 02:34:02'),
(54, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '202.131.112.10', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-12 06:35:28', '0000-00-00 00:00:00', '2015-08-12 16:05:28', '2015-08-12 02:35:28', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '182.74.245.50', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-08-24 02:48:53', '0000-00-00 00:00:00', '2015-08-24 12:18:53', '2015-08-23 22:48:53', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '202.131.112.10', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-09-02 07:37:03', '0000-00-00 00:00:00', '2015-09-02 17:07:03', '2015-09-02 03:37:03', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 8, 'outbound1@all4season.com', 'KFXQYuhTsTAr/o84JnYmAEZiF85U01yDcaDCjax+ajI=', '202.131.112.10', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 3, '2015-09-02 07:53:52', '0000-00-00 00:00:00', '2015-09-02 17:23:52', '2015-09-02 03:53:52', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 4, 'dipal.prajapati@tops-int.com', 'YzvTWvO+eRaMRrRuUGJ9VA3PvsCC+mhTnTeQfS2Ta2k=', '202.131.112.10', 'Dipal Prajapati', 'topsin_live_crm_53190bd0bf3916cde3fca880e3d54430', 2, '2015-09-07 11:10:33', '0000-00-00 00:00:00', '2015-09-07 20:40:33', '2015-09-07 07:10:33', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', '', 'CRM', 1, '2015-10-06 06:59:31', '2015-10-06 06:59:45', '2015-10-06 16:29:31', '2015-10-06 02:59:31', '2015-10-06 16:29:45', '2015-10-06 02:59:45'),
(60, 3, 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 3, '2015-10-06 06:59:52', '2015-10-06 07:00:30', '2015-10-06 16:29:52', '2015-10-06 02:59:52', '2015-10-06 16:30:30', '2015-10-06 03:00:30'),
(61, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', '', 'CRM', 1, '2015-10-06 07:00:49', '2015-10-06 07:01:19', '2015-10-06 16:30:49', '2015-10-06 03:00:49', '2015-10-06 16:31:19', '2015-10-06 03:01:19'),
(62, 2, 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.170.109.155', 'Tushar Solanki', 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 2, '2015-10-06 07:01:42', '2015-10-06 07:02:16', '2015-10-06 16:31:42', '2015-10-06 03:01:42', '2015-10-06 16:32:16', '2015-10-06 03:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `user_master`
--

CREATE TABLE IF NOT EXISTS `user_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` int(11) NOT NULL COMMENT 'From user__user_type_master',
  `agent_id` int(11) NOT NULL COMMENT 'From user_master(If type = Assi)',
  `prefix` enum('Mr.','Ms.','Mrs.') NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `middle_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `contact_pic` varchar(250) NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `company_post` varchar(250) NOT NULL,
  `notes` text NOT NULL,
  `birth_date` date NOT NULL,
  `anniversary_date` date NOT NULL,
  `archive_date` datetime NOT NULL COMMENT 'admin archve user insert date',
  `user_weightage` int(11) NOT NULL,
  `minimum_price` float NOT NULL,
  `maximum_price` float NOT NULL,
  `min_area` int(11) NOT NULL,
  `max_area` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL COMMENT '0.Archive, 1.Active(Default), 2.Inactive, 3.Block',
  PRIMARY KEY (`id`),
  KEY `user_type` (`user_type`),
  KEY `agent_id` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_mls_area_trans`
--

CREATE TABLE IF NOT EXISTS `user_mls_area_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From login_master',
  `mls_area_master_id` int(11) NOT NULL COMMENT 'From mls_area_master',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_mls_property_type_trans`
--

CREATE TABLE IF NOT EXISTS `user_mls_property_type_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From login_master',
  `mls_property_type_id` int(11) NOT NULL COMMENT 'From mls_property_type',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_mls_status_trans`
--

CREATE TABLE IF NOT EXISTS `user_mls_status_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From login_master',
  `mls_status_master_id` int(11) NOT NULL COMMENT 'From mls_status_master',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_package_trans`
--

CREATE TABLE IF NOT EXISTS `user_package_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('Active','Deactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  KEY `login_id` (`login_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_package_trans`
--

INSERT INTO `user_package_trans` (`id`, `login_id`, `package_id`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 3, 1, '2015-01-19 12:28:50', 1, '0000-00-00 00:00:00', 0, 'Active'),
(2, 3, 1, '2015-03-09 08:53:13', 1, '0000-00-00 00:00:00', 0, 'Active'),
(3, 3, 1, '2015-03-09 09:08:54', 1, '0000-00-00 00:00:00', 0, 'Active'),
(4, 3, 2, '2015-03-10 03:12:57', 1, '0000-00-00 00:00:00', 0, 'Active'),
(5, 3, 1, '2015-03-10 03:18:52', 1, '0000-00-00 00:00:00', 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_phone_trans`
--

CREATE TABLE IF NOT EXISTS `user_phone_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `phone_type` int(11) NOT NULL COMMENT 'From contact_phone_type',
  `phone_no` varchar(15) NOT NULL,
  `is_default` enum('0','1') NOT NULL COMMENT '0. No, 1.Yes',
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `phone_type` (`phone_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_rights_trans`
--

CREATE TABLE IF NOT EXISTS `user_rights_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, Auto increment',
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `rights_id` int(11) NOT NULL COMMENT 'From rights_master',
  `inactive_date` date NOT NULL,
  `rights_value` enum('0','1') NOT NULL COMMENT '1-check,0 uncheck',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_right_transaction`
--

CREATE TABLE IF NOT EXISTS `user_right_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `assign_right` int(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`module_id`),
  KEY `user_id_2` (`user_id`,`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=936 ;

--
-- Dumping data for table `user_right_transaction`
--

INSERT INTO `user_right_transaction` (`id`, `user_id`, `module_id`, `assign_right`, `created_date`, `modified_date`, `status`) VALUES
(1, 3, 1, 1, '2015-03-10 09:46:27', '2015-03-10 09:46:27', '1'),
(2, 3, 2, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(3, 3, 3, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(4, 3, 4, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(5, 3, 5, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(6, 3, 6, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(7, 3, 7, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(8, 3, 8, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(9, 3, 9, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(10, 3, 10, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(11, 3, 11, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(12, 3, 12, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(13, 3, 14, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(14, 3, 15, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(15, 3, 16, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(16, 3, 18, 0, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(17, 3, 19, 0, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(18, 3, 20, 0, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(19, 3, 21, 0, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(20, 3, 22, 0, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(21, 3, 23, 0, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(22, 3, 24, 0, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(23, 3, 25, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(24, 3, 26, 1, '2015-03-10 09:46:28', '2015-03-10 09:46:28', '1'),
(25, 3, 27, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(26, 3, 28, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(27, 3, 29, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(28, 3, 30, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(29, 3, 31, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(30, 3, 32, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(31, 3, 33, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(32, 3, 34, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(33, 3, 35, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(34, 3, 36, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(35, 3, 37, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(36, 3, 38, 1, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(37, 3, 39, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(38, 3, 40, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(39, 3, 44, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(40, 3, 45, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(41, 3, 46, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(42, 3, 47, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(43, 3, 48, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(44, 3, 49, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(45, 3, 50, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(46, 3, 51, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(47, 3, 52, 0, '2015-03-10 09:46:29', '2015-03-10 09:46:29', '1'),
(48, 3, 53, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(49, 3, 54, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(50, 3, 55, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(51, 3, 56, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(52, 3, 57, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(53, 3, 58, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(54, 3, 59, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(55, 3, 60, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(56, 3, 61, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(57, 3, 62, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(58, 3, 63, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(59, 3, 64, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(60, 3, 65, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(61, 3, 66, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(62, 3, 67, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(63, 3, 68, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(64, 3, 69, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(65, 3, 70, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(66, 3, 71, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(67, 3, 72, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(68, 3, 73, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(69, 3, 74, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(70, 3, 75, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(71, 3, 76, 0, '2015-03-10 09:46:30', '2015-03-10 09:46:30', '1'),
(72, 3, 77, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(73, 3, 78, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(74, 3, 79, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(75, 3, 80, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(76, 3, 84, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(77, 3, 85, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(78, 3, 86, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(79, 3, 88, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(80, 3, 89, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(81, 3, 90, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(82, 3, 91, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(83, 3, 93, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(84, 3, 94, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(85, 3, 95, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(86, 3, 96, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(87, 3, 98, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(88, 3, 99, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(89, 3, 100, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(90, 3, 101, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(91, 3, 102, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(92, 3, 103, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(93, 3, 104, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(94, 3, 105, 0, '2015-03-10 09:46:31', '2015-03-10 09:46:31', '1'),
(95, 3, 109, 1, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(96, 3, 110, 1, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(97, 3, 111, 1, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(98, 3, 112, 1, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(99, 3, 113, 1, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(100, 3, 114, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(101, 3, 115, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(102, 3, 116, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(103, 3, 117, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(104, 3, 118, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(105, 3, 119, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(106, 3, 120, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(107, 3, 121, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(108, 3, 122, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(109, 3, 123, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(110, 3, 124, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(111, 3, 125, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(112, 3, 126, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(113, 3, 127, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(114, 3, 128, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(115, 3, 129, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(116, 3, 130, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(117, 3, 131, 0, '2015-03-10 09:46:32', '2015-03-10 09:46:32', '1'),
(118, 3, 132, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(119, 3, 133, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(120, 3, 134, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(121, 3, 135, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(122, 3, 136, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(123, 3, 137, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(124, 3, 138, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(125, 3, 139, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(126, 3, 140, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(127, 3, 141, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(128, 3, 142, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(129, 3, 143, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(130, 3, 144, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(131, 3, 145, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(132, 3, 146, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(133, 3, 147, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(134, 3, 148, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(135, 3, 149, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(136, 3, 150, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(137, 3, 151, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(138, 3, 152, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(139, 3, 153, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(140, 3, 154, 0, '2015-03-10 09:46:33', '2015-03-10 09:46:33', '1'),
(141, 3, 155, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(142, 3, 156, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(143, 3, 157, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(144, 3, 158, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(145, 3, 159, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(146, 3, 160, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(147, 3, 161, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(148, 3, 162, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(149, 3, 166, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(150, 3, 167, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(151, 3, 171, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(152, 3, 172, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(153, 3, 176, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(154, 3, 177, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(155, 3, 180, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(156, 3, 186, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(157, 3, 187, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(158, 3, 191, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(159, 3, 192, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(160, 3, 196, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(161, 3, 197, 1, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(162, 3, 201, 0, '2015-03-10 09:46:34', '2015-03-10 09:46:34', '1'),
(163, 3, 202, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(164, 3, 206, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(165, 3, 207, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(166, 3, 211, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(167, 3, 212, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(168, 3, 213, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(169, 3, 214, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(170, 3, 215, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(171, 3, 216, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(172, 3, 217, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(173, 3, 218, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(174, 3, 219, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(175, 3, 220, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(176, 3, 221, 1, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(177, 3, 222, 1, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(178, 3, 223, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(179, 3, 224, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(180, 3, 225, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(181, 3, 226, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(182, 3, 227, 0, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(183, 3, 228, 1, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(184, 3, 229, 1, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(185, 3, 230, 1, '2015-03-10 09:46:35', '2015-03-10 09:46:35', '1'),
(186, 3, 231, 1, '2015-03-10 09:46:36', '2015-03-10 09:46:36', '1'),
(187, 3, 232, 1, '2015-03-10 09:46:36', '2015-03-10 09:46:36', '1'),
(188, 27, 1, 1, '2015-05-12 03:36:28', '2015-05-12 03:36:28', '1'),
(189, 27, 2, 1, '2015-05-12 03:36:28', '2015-05-12 03:36:28', '1'),
(190, 27, 3, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(191, 27, 4, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(192, 27, 5, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(193, 27, 6, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(194, 27, 7, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(195, 27, 8, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(196, 27, 9, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(197, 27, 10, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(198, 27, 11, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(199, 27, 12, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(200, 27, 14, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(201, 27, 15, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(202, 27, 16, 1, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(203, 27, 18, 0, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(204, 27, 19, 0, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(205, 27, 20, 0, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(206, 27, 21, 0, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(207, 27, 22, 0, '2015-05-12 03:36:29', '2015-05-12 03:36:29', '1'),
(208, 27, 23, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(209, 27, 24, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(210, 27, 25, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(211, 27, 26, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(212, 27, 27, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(213, 27, 28, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(214, 27, 29, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(215, 27, 30, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(216, 27, 31, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(217, 27, 32, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(218, 27, 33, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(219, 27, 34, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(220, 27, 35, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(221, 27, 36, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(222, 27, 37, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(223, 27, 38, 1, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(224, 27, 39, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(225, 27, 40, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(226, 27, 44, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(227, 27, 45, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(228, 27, 46, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(229, 27, 47, 0, '2015-05-12 03:36:30', '2015-05-12 03:36:30', '1'),
(230, 27, 48, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(231, 27, 49, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(232, 27, 50, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(233, 27, 51, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(234, 27, 52, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(235, 27, 53, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(236, 27, 54, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(237, 27, 55, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(238, 27, 56, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(239, 27, 57, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(240, 27, 58, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(241, 27, 59, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(242, 27, 60, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(243, 27, 61, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(244, 27, 62, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(245, 27, 63, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(246, 27, 64, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(247, 27, 65, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(248, 27, 66, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(249, 27, 67, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(250, 27, 68, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(251, 27, 69, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(252, 27, 70, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(253, 27, 71, 0, '2015-05-12 03:36:31', '2015-05-12 03:36:31', '1'),
(254, 27, 72, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(255, 27, 73, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(256, 27, 74, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(257, 27, 75, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(258, 27, 76, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(259, 27, 77, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(260, 27, 78, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(261, 27, 79, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(262, 27, 80, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(263, 27, 84, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(264, 27, 85, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(265, 27, 86, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(266, 27, 88, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(267, 27, 89, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(268, 27, 90, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(269, 27, 91, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(270, 27, 93, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(271, 27, 94, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(272, 27, 95, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(273, 27, 96, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(274, 27, 98, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(275, 27, 99, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(276, 27, 100, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(277, 27, 101, 0, '2015-05-12 03:36:32', '2015-05-12 03:36:32', '1'),
(278, 27, 102, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(279, 27, 103, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(280, 27, 104, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(281, 27, 105, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(282, 27, 109, 1, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(283, 27, 110, 1, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(284, 27, 111, 1, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(285, 27, 112, 1, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(286, 27, 113, 1, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(287, 27, 114, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(288, 27, 115, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(289, 27, 116, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(290, 27, 117, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(291, 27, 118, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(292, 27, 119, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(293, 27, 120, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(294, 27, 121, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(295, 27, 122, 0, '2015-05-12 03:36:33', '2015-05-12 03:36:33', '1'),
(296, 27, 123, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(297, 27, 124, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(298, 27, 125, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(299, 27, 126, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(300, 27, 127, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(301, 27, 128, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(302, 27, 129, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(303, 27, 130, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(304, 27, 131, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(305, 27, 132, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(306, 27, 133, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(307, 27, 134, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(308, 27, 135, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(309, 27, 136, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(310, 27, 137, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(311, 27, 138, 0, '2015-05-12 03:36:34', '2015-05-12 03:36:34', '1'),
(312, 27, 139, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(313, 27, 140, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(314, 27, 141, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(315, 27, 142, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(316, 27, 143, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(317, 27, 144, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(318, 27, 145, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(319, 27, 146, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(320, 27, 147, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(321, 27, 148, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(322, 27, 149, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(323, 27, 150, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(324, 27, 151, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(325, 27, 152, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(326, 27, 153, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(327, 27, 154, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(328, 27, 155, 0, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(329, 27, 156, 1, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(330, 27, 157, 1, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(331, 27, 158, 1, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(332, 27, 159, 1, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(333, 27, 160, 1, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(334, 27, 161, 1, '2015-05-12 03:36:35', '2015-05-12 03:36:35', '1'),
(335, 27, 162, 1, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(336, 27, 166, 1, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(337, 27, 167, 1, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(338, 27, 171, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(339, 27, 172, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(340, 27, 176, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(341, 27, 177, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(342, 27, 180, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(343, 27, 186, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(344, 27, 187, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(345, 27, 191, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(346, 27, 192, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(347, 27, 196, 1, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(348, 27, 197, 1, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(349, 27, 201, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(350, 27, 202, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(351, 27, 206, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(352, 27, 207, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(353, 27, 211, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(354, 27, 212, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(355, 27, 213, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(356, 27, 214, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(357, 27, 215, 0, '2015-05-12 03:36:36', '2015-05-12 03:36:36', '1'),
(358, 27, 216, 0, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(359, 27, 217, 0, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(360, 27, 218, 0, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(361, 27, 219, 0, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(362, 27, 220, 0, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(363, 27, 221, 1, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(364, 27, 222, 1, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(365, 27, 223, 1, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(366, 27, 224, 1, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(367, 27, 225, 1, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(368, 27, 226, 1, '2015-05-12 03:36:37', '2015-05-12 03:36:37', '1'),
(369, 27, 227, 1, '2015-05-12 03:36:38', '2015-05-12 03:36:38', '1'),
(370, 27, 228, 1, '2015-05-12 03:36:38', '2015-05-12 03:36:38', '1'),
(371, 27, 229, 1, '2015-05-12 03:36:38', '2015-05-12 03:36:38', '1'),
(372, 27, 230, 1, '2015-05-12 03:36:38', '2015-05-12 03:36:38', '1'),
(373, 27, 231, 1, '2015-05-12 03:36:38', '2015-05-12 03:36:38', '1'),
(374, 27, 232, 1, '2015-05-12 03:36:38', '2015-05-12 03:36:38', '1'),
(375, 28, 1, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(376, 28, 2, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(377, 28, 3, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(378, 28, 4, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(379, 28, 5, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(380, 28, 6, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(381, 28, 7, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(382, 28, 8, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(383, 28, 9, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(384, 28, 10, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(385, 28, 11, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(386, 28, 12, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(387, 28, 14, 1, '2015-05-12 03:49:31', '2015-05-12 03:49:31', '1'),
(388, 28, 15, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(389, 28, 16, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(390, 28, 18, 0, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(391, 28, 19, 0, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(392, 28, 20, 0, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(393, 28, 21, 0, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(394, 28, 22, 0, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(395, 28, 23, 0, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(396, 28, 24, 0, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(397, 28, 25, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(398, 28, 26, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(399, 28, 27, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(400, 28, 28, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(401, 28, 29, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(402, 28, 30, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(403, 28, 31, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(404, 28, 32, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(405, 28, 33, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(406, 28, 34, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(407, 28, 35, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(408, 28, 36, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(409, 28, 37, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(410, 28, 38, 1, '2015-05-12 03:49:32', '2015-05-12 03:49:32', '1'),
(411, 28, 39, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(412, 28, 40, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(413, 28, 44, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(414, 28, 45, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(415, 28, 46, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(416, 28, 47, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(417, 28, 48, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(418, 28, 49, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(419, 28, 50, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(420, 28, 51, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(421, 28, 52, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(422, 28, 53, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(423, 28, 54, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(424, 28, 55, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(425, 28, 56, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(426, 28, 57, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(427, 28, 58, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(428, 28, 59, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(429, 28, 60, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(430, 28, 61, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(431, 28, 62, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(432, 28, 63, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(433, 28, 64, 0, '2015-05-12 03:49:33', '2015-05-12 03:49:33', '1'),
(434, 28, 65, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(435, 28, 66, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(436, 28, 67, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(437, 28, 68, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(438, 28, 69, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(439, 28, 70, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(440, 28, 71, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(441, 28, 72, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(442, 28, 73, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(443, 28, 74, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(444, 28, 75, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(445, 28, 76, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(446, 28, 77, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(447, 28, 78, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(448, 28, 79, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(449, 28, 80, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(450, 28, 84, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(451, 28, 85, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(452, 28, 86, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(453, 28, 88, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(454, 28, 89, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(455, 28, 90, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(456, 28, 91, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(457, 28, 93, 0, '2015-05-12 03:49:34', '2015-05-12 03:49:34', '1'),
(458, 28, 94, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(459, 28, 95, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(460, 28, 96, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(461, 28, 98, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(462, 28, 99, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(463, 28, 100, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(464, 28, 101, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(465, 28, 102, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(466, 28, 103, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(467, 28, 104, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(468, 28, 105, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(469, 28, 109, 1, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(470, 28, 110, 1, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(471, 28, 111, 1, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(472, 28, 112, 1, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(473, 28, 113, 1, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(474, 28, 114, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(475, 28, 115, 0, '2015-05-12 03:49:35', '2015-05-12 03:49:35', '1'),
(476, 28, 116, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(477, 28, 117, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(478, 28, 118, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(479, 28, 119, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(480, 28, 120, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(481, 28, 121, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(482, 28, 122, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(483, 28, 123, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(484, 28, 124, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(485, 28, 125, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(486, 28, 126, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(487, 28, 127, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(488, 28, 128, 0, '2015-05-12 03:49:36', '2015-05-12 03:49:36', '1'),
(489, 28, 129, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(490, 28, 130, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(491, 28, 131, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(492, 28, 132, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(493, 28, 133, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(494, 28, 134, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(495, 28, 135, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(496, 28, 136, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(497, 28, 137, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(498, 28, 138, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(499, 28, 139, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(500, 28, 140, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(501, 28, 141, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(502, 28, 142, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(503, 28, 143, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(504, 28, 144, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(505, 28, 145, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(506, 28, 146, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(507, 28, 147, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(508, 28, 148, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(509, 28, 149, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(510, 28, 150, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(511, 28, 151, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(512, 28, 152, 0, '2015-05-12 03:49:37', '2015-05-12 03:49:37', '1'),
(513, 28, 153, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(514, 28, 154, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(515, 28, 155, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(516, 28, 156, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(517, 28, 157, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(518, 28, 158, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(519, 28, 159, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(520, 28, 160, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(521, 28, 161, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(522, 28, 162, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(523, 28, 166, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(524, 28, 167, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(525, 28, 171, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(526, 28, 172, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(527, 28, 176, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(528, 28, 177, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(529, 28, 180, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(530, 28, 186, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(531, 28, 187, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(532, 28, 191, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(533, 28, 192, 0, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(534, 28, 196, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(535, 28, 197, 1, '2015-05-12 03:49:38', '2015-05-12 03:49:38', '1'),
(536, 28, 201, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(537, 28, 202, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(538, 28, 206, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(539, 28, 207, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(540, 28, 211, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(541, 28, 212, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(542, 28, 213, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(543, 28, 214, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(544, 28, 215, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(545, 28, 216, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(546, 28, 217, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(547, 28, 218, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(548, 28, 219, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(549, 28, 220, 0, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(550, 28, 221, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(551, 28, 222, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(552, 28, 223, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(553, 28, 224, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(554, 28, 225, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(555, 28, 226, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(556, 28, 227, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(557, 28, 228, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(558, 28, 229, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(559, 28, 230, 1, '2015-05-12 03:49:39', '2015-05-12 03:49:39', '1'),
(560, 28, 231, 1, '2015-05-12 03:49:40', '2015-05-12 03:49:40', '1'),
(561, 28, 232, 1, '2015-05-12 03:49:40', '2015-05-12 03:49:40', '1'),
(562, 2, 1, 1, '2015-07-13 05:59:16', '2015-07-13 05:59:16', '1'),
(563, 2, 2, 1, '2015-07-13 05:59:16', '2015-07-13 05:59:16', '1'),
(564, 2, 3, 1, '2015-07-13 05:59:16', '2015-07-13 05:59:16', '1'),
(565, 2, 4, 1, '2015-07-13 05:59:16', '2015-07-13 05:59:16', '1'),
(566, 2, 5, 1, '2015-07-13 05:59:16', '2015-07-13 05:59:16', '1'),
(567, 2, 6, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(568, 2, 7, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(569, 2, 8, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(570, 2, 9, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(571, 2, 10, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(572, 2, 11, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(573, 2, 12, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(574, 2, 14, 1, '2015-07-13 05:59:17', '2015-07-13 05:59:17', '1'),
(575, 2, 15, 1, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(576, 2, 16, 1, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(577, 2, 18, 0, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(578, 2, 19, 0, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(579, 2, 20, 0, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(580, 2, 21, 0, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(581, 2, 22, 0, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(582, 2, 23, 0, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(583, 2, 24, 0, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(584, 2, 25, 1, '2015-07-13 05:59:18', '2015-07-13 05:59:18', '1'),
(585, 2, 26, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(586, 2, 27, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(587, 2, 28, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(588, 2, 29, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(589, 2, 30, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(590, 2, 31, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(591, 2, 32, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(592, 2, 33, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(593, 2, 34, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(594, 2, 35, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(595, 2, 36, 1, '2015-07-13 05:59:19', '2015-07-13 05:59:19', '1'),
(596, 2, 37, 1, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(597, 2, 38, 1, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(598, 2, 39, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(599, 2, 40, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(600, 2, 44, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(601, 2, 45, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(602, 2, 46, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(603, 2, 47, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(604, 2, 48, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(605, 2, 49, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(606, 2, 50, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(607, 2, 51, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(608, 2, 52, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(609, 2, 53, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(610, 2, 54, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(611, 2, 55, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(612, 2, 56, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(613, 2, 57, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(614, 2, 58, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(615, 2, 59, 0, '2015-07-13 05:59:20', '2015-07-13 05:59:20', '1'),
(616, 2, 60, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(617, 2, 61, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(618, 2, 62, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(619, 2, 63, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(620, 2, 64, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(621, 2, 65, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(622, 2, 66, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(623, 2, 67, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(624, 2, 68, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(625, 2, 69, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(626, 2, 70, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(627, 2, 71, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(628, 2, 72, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(629, 2, 73, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(630, 2, 74, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(631, 2, 75, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(632, 2, 76, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(633, 2, 77, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(634, 2, 78, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(635, 2, 79, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(636, 2, 80, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(637, 2, 84, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(638, 2, 85, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(639, 2, 86, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(640, 2, 88, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(641, 2, 89, 0, '2015-07-13 05:59:21', '2015-07-13 05:59:21', '1'),
(642, 2, 90, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(643, 2, 91, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(644, 2, 93, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(645, 2, 94, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(646, 2, 95, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(647, 2, 96, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(648, 2, 98, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(649, 2, 99, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(650, 2, 100, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(651, 2, 101, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(652, 2, 102, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(653, 2, 103, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(654, 2, 104, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(655, 2, 105, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(656, 2, 109, 1, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(657, 2, 110, 1, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(658, 2, 111, 1, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(659, 2, 112, 1, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(660, 2, 113, 1, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(661, 2, 114, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(662, 2, 115, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(663, 2, 116, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(664, 2, 117, 0, '2015-07-13 05:59:22', '2015-07-13 05:59:22', '1'),
(665, 2, 118, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(666, 2, 119, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(667, 2, 120, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(668, 2, 121, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(669, 2, 122, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(670, 2, 123, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(671, 2, 124, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(672, 2, 125, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(673, 2, 126, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(674, 2, 127, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(675, 2, 128, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(676, 2, 129, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(677, 2, 130, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(678, 2, 131, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(679, 2, 132, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(680, 2, 133, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(681, 2, 134, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(682, 2, 135, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(683, 2, 136, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(684, 2, 137, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(685, 2, 138, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(686, 2, 139, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(687, 2, 140, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(688, 2, 141, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(689, 2, 142, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(690, 2, 143, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(691, 2, 144, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(692, 2, 145, 0, '2015-07-13 05:59:23', '2015-07-13 05:59:23', '1'),
(693, 2, 146, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(694, 2, 147, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(695, 2, 148, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(696, 2, 149, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(697, 2, 150, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(698, 2, 151, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(699, 2, 152, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(700, 2, 153, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(701, 2, 154, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(702, 2, 155, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(703, 2, 156, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(704, 2, 157, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(705, 2, 158, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(706, 2, 159, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(707, 2, 160, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(708, 2, 161, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(709, 2, 162, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(710, 2, 166, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(711, 2, 167, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(712, 2, 171, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(713, 2, 172, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(714, 2, 176, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(715, 2, 177, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(716, 2, 180, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(717, 2, 186, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(718, 2, 187, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(719, 2, 191, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(720, 2, 192, 0, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(721, 2, 196, 1, '2015-07-13 05:59:24', '2015-07-13 05:59:24', '1'),
(722, 2, 197, 1, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(723, 2, 201, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(724, 2, 202, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(725, 2, 206, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(726, 2, 207, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(727, 2, 211, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(728, 2, 212, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(729, 2, 213, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(730, 2, 214, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(731, 2, 215, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(732, 2, 216, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(733, 2, 217, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(734, 2, 218, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(735, 2, 219, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(736, 2, 220, 0, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(737, 2, 221, 1, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(738, 2, 222, 1, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(739, 2, 223, 1, '2015-07-13 05:59:25', '2015-07-13 05:59:25', '1'),
(740, 2, 224, 1, '2015-07-13 05:59:26', '2015-07-13 05:59:26', '1'),
(741, 2, 225, 1, '2015-07-13 05:59:26', '2015-07-13 05:59:26', '1'),
(742, 2, 226, 1, '2015-07-13 05:59:26', '2015-07-13 05:59:26', '1'),
(743, 2, 227, 1, '2015-07-13 05:59:26', '2015-07-13 05:59:26', '1'),
(744, 2, 228, 1, '2015-07-13 05:59:26', '2015-07-13 05:59:26', '1'),
(745, 2, 229, 1, '2015-07-13 05:59:26', '2015-07-13 05:59:26', '1'),
(746, 2, 230, 1, '2015-07-13 05:59:26', '2015-07-13 05:59:26', '1');
INSERT INTO `user_right_transaction` (`id`, `user_id`, `module_id`, `assign_right`, `created_date`, `modified_date`, `status`) VALUES
(747, 2, 231, 1, '2015-07-13 05:59:27', '2015-07-13 05:59:27', '1'),
(748, 2, 232, 1, '2015-07-13 05:59:27', '2015-07-13 05:59:27', '1'),
(749, 4, 1, 1, '2015-07-14 03:53:38', '2015-07-14 03:53:38', '1'),
(750, 4, 2, 1, '2015-07-14 03:53:38', '2015-07-14 03:53:38', '1'),
(751, 4, 3, 1, '2015-07-14 03:53:38', '2015-07-14 03:53:38', '1'),
(752, 4, 4, 1, '2015-07-14 03:53:38', '2015-07-14 03:53:38', '1'),
(753, 4, 5, 1, '2015-07-14 03:53:38', '2015-07-14 03:53:38', '1'),
(754, 4, 6, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(755, 4, 7, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(756, 4, 8, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(757, 4, 9, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(758, 4, 10, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(759, 4, 11, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(760, 4, 12, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(761, 4, 14, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(762, 4, 15, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(763, 4, 16, 1, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(764, 4, 18, 0, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(765, 4, 19, 0, '2015-07-14 03:53:39', '2015-07-14 03:53:39', '1'),
(766, 4, 20, 0, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(767, 4, 21, 0, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(768, 4, 22, 0, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(769, 4, 23, 0, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(770, 4, 24, 0, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(771, 4, 25, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(772, 4, 26, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(773, 4, 27, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(774, 4, 28, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(775, 4, 29, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(776, 4, 30, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(777, 4, 31, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(778, 4, 32, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(779, 4, 33, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(780, 4, 34, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(781, 4, 35, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(782, 4, 36, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(783, 4, 37, 1, '2015-07-14 03:53:40', '2015-07-14 03:53:40', '1'),
(784, 4, 38, 1, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(785, 4, 39, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(786, 4, 40, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(787, 4, 44, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(788, 4, 45, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(789, 4, 46, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(790, 4, 47, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(791, 4, 48, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(792, 4, 49, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(793, 4, 50, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(794, 4, 51, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(795, 4, 52, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(796, 4, 53, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(797, 4, 54, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(798, 4, 55, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(799, 4, 56, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(800, 4, 57, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(801, 4, 58, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(802, 4, 59, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(803, 4, 60, 0, '2015-07-14 03:53:41', '2015-07-14 03:53:41', '1'),
(804, 4, 61, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(805, 4, 62, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(806, 4, 63, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(807, 4, 64, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(808, 4, 65, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(809, 4, 66, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(810, 4, 67, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(811, 4, 68, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(812, 4, 69, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(813, 4, 70, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(814, 4, 71, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(815, 4, 72, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(816, 4, 73, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(817, 4, 74, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(818, 4, 75, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(819, 4, 76, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(820, 4, 77, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(821, 4, 78, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(822, 4, 79, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(823, 4, 80, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(824, 4, 84, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(825, 4, 85, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(826, 4, 86, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(827, 4, 88, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(828, 4, 89, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(829, 4, 90, 0, '2015-07-14 03:53:42', '2015-07-14 03:53:42', '1'),
(830, 4, 91, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(831, 4, 93, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(832, 4, 94, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(833, 4, 95, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(834, 4, 96, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(835, 4, 98, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(836, 4, 99, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(837, 4, 100, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(838, 4, 101, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(839, 4, 102, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(840, 4, 103, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(841, 4, 104, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(842, 4, 105, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(843, 4, 109, 1, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(844, 4, 110, 1, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(845, 4, 111, 1, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(846, 4, 112, 1, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(847, 4, 113, 1, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(848, 4, 114, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(849, 4, 115, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(850, 4, 116, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(851, 4, 117, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(852, 4, 118, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(853, 4, 119, 0, '2015-07-14 03:53:43', '2015-07-14 03:53:43', '1'),
(854, 4, 120, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(855, 4, 121, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(856, 4, 122, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(857, 4, 123, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(858, 4, 124, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(859, 4, 125, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(860, 4, 126, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(861, 4, 127, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(862, 4, 128, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(863, 4, 129, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(864, 4, 130, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(865, 4, 131, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(866, 4, 132, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(867, 4, 133, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(868, 4, 134, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(869, 4, 135, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(870, 4, 136, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(871, 4, 137, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(872, 4, 138, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(873, 4, 139, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(874, 4, 140, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(875, 4, 141, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(876, 4, 142, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(877, 4, 143, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(878, 4, 144, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(879, 4, 145, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(880, 4, 146, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(881, 4, 147, 0, '2015-07-14 03:53:44', '2015-07-14 03:53:44', '1'),
(882, 4, 148, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(883, 4, 149, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(884, 4, 150, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(885, 4, 151, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(886, 4, 152, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(887, 4, 153, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(888, 4, 154, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(889, 4, 155, 0, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(890, 4, 156, 1, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(891, 4, 157, 1, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(892, 4, 158, 1, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(893, 4, 159, 1, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(894, 4, 160, 1, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(895, 4, 161, 1, '2015-07-14 03:53:45', '2015-07-14 03:53:45', '1'),
(896, 4, 162, 1, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(897, 4, 166, 1, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(898, 4, 167, 1, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(899, 4, 171, 0, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(900, 4, 172, 0, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(901, 4, 176, 0, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(902, 4, 177, 0, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(903, 4, 180, 0, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(904, 4, 186, 0, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(905, 4, 187, 0, '2015-07-14 03:53:46', '2015-07-14 03:53:46', '1'),
(906, 4, 191, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(907, 4, 192, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(908, 4, 196, 1, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(909, 4, 197, 1, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(910, 4, 201, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(911, 4, 202, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(912, 4, 206, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(913, 4, 207, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(914, 4, 211, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(915, 4, 212, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(916, 4, 213, 0, '2015-07-14 03:53:47', '2015-07-14 03:53:47', '1'),
(917, 4, 214, 0, '2015-07-14 03:53:48', '2015-07-14 03:53:48', '1'),
(918, 4, 215, 0, '2015-07-14 03:53:48', '2015-07-14 03:53:48', '1'),
(919, 4, 216, 0, '2015-07-14 03:53:48', '2015-07-14 03:53:48', '1'),
(920, 4, 217, 0, '2015-07-14 03:53:48', '2015-07-14 03:53:48', '1'),
(921, 4, 218, 0, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(922, 4, 219, 0, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(923, 4, 220, 0, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(924, 4, 221, 1, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(925, 4, 222, 1, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(926, 4, 223, 1, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(927, 4, 224, 1, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(928, 4, 225, 1, '2015-07-14 03:53:49', '2015-07-14 03:53:49', '1'),
(929, 4, 226, 1, '2015-07-14 03:53:50', '2015-07-14 03:53:50', '1'),
(930, 4, 227, 1, '2015-07-14 03:53:50', '2015-07-14 03:53:50', '1'),
(931, 4, 228, 1, '2015-07-14 03:53:50', '2015-07-14 03:53:50', '1'),
(932, 4, 229, 1, '2015-07-14 03:53:50', '2015-07-14 03:53:50', '1'),
(933, 4, 230, 1, '2015-07-14 03:53:50', '2015-07-14 03:53:50', '1'),
(934, 4, 231, 1, '2015-07-14 03:53:50', '2015-07-14 03:53:50', '1'),
(935, 4, 232, 1, '2015-07-14 03:53:50', '2015-07-14 03:53:50', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_rr_weightage_trans`
--

CREATE TABLE IF NOT EXISTS `user_rr_weightage_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `agent_type` enum('Inside Sales Agent','Buyer''s Agent') NOT NULL COMMENT '''Inside Sales Agent'',''Buyer''''s Agent''',
  `user_weightage` int(11) NOT NULL,
  `assigned_contact_id` int(11) NOT NULL COMMENT 'From user_contact_trans',
  `round` int(11) NOT NULL,
  `round_value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_social_trans`
--

CREATE TABLE IF NOT EXISTS `user_social_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `profile_type` varchar(100) NOT NULL,
  `website_name` varchar(500) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_website_trans`
--

CREATE TABLE IF NOT EXISTS `user_website_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `website_type` varchar(100) NOT NULL,
  `website_name` varchar(500) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user__user_type_master`
--

CREATE TABLE IF NOT EXISTS `user__user_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0.Deactive, 1.Active(Default)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user__user_type_master`
--

INSERT INTO `user__user_type_master` (`id`, `name`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Super Admin', '2014-07-25 14:36:06', 4, '0000-00-00 00:00:00', 0, '1'),
(2, 'Admin', '2014-07-25 14:36:14', 4, '2014-08-23 09:38:05', 8, '1'),
(3, 'Agent', '2014-08-23 09:39:03', 8, '0000-00-00 00:00:00', 0, '1'),
(4, 'Assistant', '2014-08-23 09:39:12', 8, '0000-00-00 00:00:00', 0, '1');

-- --------------------------------------------------------

--
-- Table structure for table `work_time_config_master`
--

CREATE TABLE IF NOT EXISTS `work_time_config_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `if_mon` enum('1','0') NOT NULL,
  `mon_start_time` time NOT NULL,
  `mon_end_time` time NOT NULL,
  `if_tue` enum('1','0') NOT NULL,
  `tue_start_time` time NOT NULL,
  `tue_end_time` time NOT NULL,
  `if_wed` enum('1','0') NOT NULL,
  `wed_start_time` time NOT NULL,
  `wed_end_time` time NOT NULL,
  `if_thu` enum('1','0') NOT NULL,
  `thu_start_time` time NOT NULL,
  `thu_end_time` time NOT NULL,
  `if_fri` enum('1','0') NOT NULL,
  `fri_start_time` time NOT NULL,
  `fri_end_time` time NOT NULL,
  `if_sat` enum('1','0') NOT NULL,
  `sat_start_time` time NOT NULL,
  `sat_end_time` time NOT NULL,
  `if_sun` enum('1','0') NOT NULL,
  `sun_start_time` time NOT NULL,
  `sun_end_time` time NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `work_time_special_rules`
--

CREATE TABLE IF NOT EXISTS `work_time_special_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'From user_master',
  `nth_day` int(1) NOT NULL COMMENT '1.First,2.Second,…,5.Last',
  `nth_date` int(2) NOT NULL COMMENT '1 to 10 values.(1.Day,2.Weekday,3.Weekend,4.Mon,5.Tue,6.Wed,7.Thu,8.Fri,9.Sat,10.Sun)',
  `rule_type` enum('1','2') NOT NULL COMMENT '1-Off, 2-Special type',
  `start_time` time NOT NULL COMMENT 'If type =2',
  `end_time` time NOT NULL COMMENT 'If type =2',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
