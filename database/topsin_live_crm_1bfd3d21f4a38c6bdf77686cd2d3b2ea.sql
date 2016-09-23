-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 06, 2015 at 01:26 PM
-- Server version: 5.5.45-cll
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `contact_additionalfield_trans`
--

INSERT INTO `contact_additionalfield_trans` (`id`, `contact_id`, `field_type`, `field_name`, `status`) VALUES
(1, 3, '1', '07/01/2015', '1'),
(2, 3, '2', 'Gopal', '1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contact_conversations_trans`
--

INSERT INTO `contact_conversations_trans` (`id`, `contact_id`, `task_id`, `log_type`, `interaction_type`, `description`, `disposition`, `plan_id`, `plan_name`, `assign_to`, `interaction_id`, `interaction_name`, `campaign_id`, `email_camp_template_id`, `email_camp_template_name`, `sms_camp_template_id`, `sms_camp_template_name`, `mail_out_type`, `mail_out_template_id`, `mail_out_template_name`, `is_completed_task`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 3, 0, 3, 0, '', 0, 0, '', 1, 0, '', 0, 0, '', 0, '', '', 0, '', '0', '2015-07-13 09:13:16', 1, '0000-00-00 00:00:00', 0, '1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `contact_listing_last_seen`
--

INSERT INTO `contact_listing_last_seen` (`id`, `login_id`, `contact_last_seen`, `listing_last_seen`, `manual_contact_last_seen`, `joomla_lead_last_seen`, `form_lead_last_seen`) VALUES
(1, 1, '2015-07-13 05:59:16', '2015-07-13 05:59:16', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `contact_master`
--

INSERT INTO `contact_master` (`id`, `prefix`, `first_name`, `middle_name`, `last_name`, `spousefirst_name`, `spousemiddle_name`, `spouselast_name`, `contact_pic`, `company_name`, `company_post`, `is_lead`, `notes`, `contact_source`, `contact_method`, `birth_date`, `anniversary_date`, `contact_status`, `created_type`, `fb_id`, `fb_login_id`, `lead_id`, `linkedin_id`, `linkedin_message_id`, `linkedin_user_id`, `google_user_id`, `csv_id`, `joomla_user_id`, `joomla_domain_name`, `domain_id`, `joomla_address`, `joomla_ip_address`, `is_subscribe`, `price_range_from`, `price_range_to`, `min_area`, `max_area`, `house_style`, `area_of_interest`, `square_footage`, `no_of_bedrooms`, `no_of_bathrooms`, `buyer_preferences_notes`, `joomla_contact_type`, `joomla_category`, `joomla_timeframe`, `is_valuation_contact`, `form_id`, `mailgun_admin_id`, `mailgun_contact_id`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(3, '', 'Sanjay', '', 'Chabhadiya', '', NULL, '', '', '', '', '0', '  ', 0, 0, '1969-12-31', '1969-12-31', 0, '1', '', '', 0, '', 0, '', '', 0, 0, '', 0, '', NULL, '0', 0, 0, 0, 0, '', '', '', 0, 0, '', 'Buyer', 'New', NULL, 'No', 0, NULL, NULL, '2015-07-14 02:42:43', 1, '2015-07-14 07:11:28', 2, '1');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `contact__additionalfield_master`
--

INSERT INTO `contact__additionalfield_master` (`id`, `name`, `field_type`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'Anniversary Date', 2, '2015-07-14 06:08:21', 1, '2015-07-14 06:36:54', 1, '1'),
(2, 'Child''s Name', 1, '2015-07-14 06:08:21', 1, '2015-07-14 06:36:54', 1, '1');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lead_master`
--

INSERT INTO `lead_master` (`id`, `form_widget_id`, `form_title`, `show_title`, `form_desc`, `show_desc`, `success_msg`, `assigned_interaction_plan_id`, `form_width`, `form_height`, `bg_color`, `lead_form`, `first_name`, `last_name`, `phone_field`, `email_field`, `single_line_field`, `paragraph_field`, `address_field`, `date_field`, `website_field`, `area_of_interest`, `price_range`, `bedrooms`, `bathrooms`, `buyer_preferences_notes`, `house_style`, `square_footage`, `file`, `created_ip`, `assign_user_id`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 'dG9wc2luX2xpdmVfY3JtXzFiZmQzZDIxZjRhMzhjNmJkZjc3Njg2Y2QyZDNiMmVh--b19a211e3b0bcb32b97650b82a342a97', 'test', '1', '', '1', 'Thank you for providing the requested information. We will get back to you as soon as possible.', 0, 0, 0, '#FFF', '\n							                                 <div class="dataTables_filter" id="fnamediv"><table><tbody><tr><td valign="middle" width="30%"><input name="fname_type[]" id="ftitle_0" value="First Name" type="hidden"><span id="fname_0">First Name</span><span class="val" id="fname_req_0"> *</span></td><td class="form-group"><label for="f_name"><input data-required="true" class="form-control parsley-validated" data-parsley-group="block1" name="f_name[]" id="f_name0" type="text"></label></td><td class=" dynamic_dml" width="20%"><a href="javascript:void(0);" onclick="fname_edit(''0'')" title="Edit record" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a></td></tr></tbody></table></div>\n								 \n							                                ', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '182.70.74.8', 0, '2015-07-14 06:28:59', 1, '0000-00-00 00:00:00', 0, '1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `login_master`
--

INSERT INTO `login_master` (`id`, `user_type`, `agent_type`, `user_id`, `mls_user_id`, `mls_firm_id`, `agent_id`, `admin_name`, `email_id`, `password`, `sha_key`, `address`, `phone`, `number_of_users_allowed`, `admin_pic`, `brokerage_pic`, `user_license_no`, `user_weightage_joomla`, `remain_emails`, `remain_sms`, `remain_contacts`, `db_name`, `host_name`, `db_user_name`, `db_user_password`, `linkedin_access_token`, `linkedin_secret_access_token`, `linkedin_username`, `is_buyer_tab`, `lead_dashboard_tab`, `market_watch_tab`, `contact_form_tab`, `fb_api_key`, `fb_secret_key`, `twitter_access_token`, `twitter_access_token_secret`, `twitter_username`, `twitter_id`, `google_access_token`, `google_user_name`, `bombbomb_username`, `bombbomb_password`, `twilio_account_sid`, `twilio_auth_token`, `twilio_number`, `twilio_sms_url`, `timezone`, `archive_date`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, '2', 'Inside Sales Agent', 0, 0, 0, 0, 'Tushar Solanki', 'tushar.solanki@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '', '                                                                                                ', '', 100, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 'localhost', 'topsin_u_2', 'topsin_u_2', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', '0', '0', '0', '', 'America/New_York', '0000-00-00 00:00:00', '2015-07-13 05:59:16', 1, '2015-07-14 06:06:01', 1, '1'),
(2, '3', 'Inside Sales Agent', 1, 0, 0, 0, '', 'nishit.modi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '', '', '0', 0, '', '', '', 0, 0, 0, 0, 'topsin_live_crm_1bfd3d21f4a38c6bdf77686cd2d3b2ea', 'localhost', 'topsin_u_2', 'topsin_u_2', NULL, NULL, NULL, '1', '1', '1', '1', '0', '0', NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, '', '', '0000-00-00 00:00:00', '2015-07-13 09:10:52', 1, '0000-00-00 00:00:00', 0, '1');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_contact_trans`
--

INSERT INTO `user_contact_trans` (`id`, `agent_type`, `user_id`, `contact_id`, `created_date`, `created_by`, `status`) VALUES
(3, '', 1, 3, '2015-07-13 09:17:05', 2, '1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_login_trans`
--

INSERT INTO `user_login_trans` (`id`, `login_id`, `email_id`, `password`, `created_ip`, `admin_name`, `db_name`, `user_type`, `start_date`, `end_date`, `start_time_ist`, `start_time_pst`, `end_time_ist`, `end_time_pst`) VALUES
(1, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', '', 'CRM', 1, '2015-07-13 05:53:52', '2015-07-13 05:54:49', '2015-07-13 15:23:52', '2015-07-13 01:53:52', '2015-07-13 15:24:49', '2015-07-13 01:54:49'),
(2, 1, 'mohit.trivedi@tops-int.com', 'Z7MrPIPSUh2/tuqeE11J3L541+ld2K0YtNHC2ravdkA=', '122.169.68.146', '', 'CRM', 1, '2015-07-13 05:54:57', '0000-00-00 00:00:00', '2015-07-13 15:24:57', '2015-07-13 01:54:57', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_master`
--

INSERT INTO `user_master` (`id`, `user_type`, `agent_id`, `prefix`, `first_name`, `middle_name`, `last_name`, `contact_pic`, `company_name`, `company_post`, `notes`, `birth_date`, `anniversary_date`, `archive_date`, `user_weightage`, `minimum_price`, `maximum_price`, `min_area`, `max_area`, `created_date`, `created_by`, `modified_date`, `modified_by`, `status`) VALUES
(1, 3, 0, '', 'Nishit', '', 'Modi', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00 00:00:00', 0, 0, 0, 0, 0, '2015-07-13 09:10:52', 1, '0000-00-00 00:00:00', 0, '1');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_rights_trans`
--

INSERT INTO `user_rights_trans` (`id`, `user_id`, `rights_id`, `inactive_date`, `rights_value`) VALUES
(1, 1, 1, '0000-00-00', '0'),
(2, 1, 2, '0000-00-00', '0'),
(3, 1, 3, '0000-00-00', '0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=318 ;

--
-- Dumping data for table `user_right_transaction`
--

INSERT INTO `user_right_transaction` (`id`, `user_id`, `module_id`, `assign_right`, `created_date`, `modified_date`, `status`) VALUES
(1, 2, 18, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(2, 2, 19, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(3, 2, 20, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(4, 2, 21, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(5, 2, 22, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(6, 2, 23, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(7, 2, 24, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(8, 2, 39, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(9, 2, 40, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(10, 2, 44, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(11, 2, 45, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(12, 2, 46, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(13, 2, 47, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(14, 2, 48, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(15, 2, 49, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(16, 2, 50, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(17, 2, 51, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(18, 2, 52, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(19, 2, 53, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(20, 2, 54, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(21, 2, 55, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(22, 2, 56, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(23, 2, 57, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(24, 2, 58, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(25, 2, 59, 1, '2015-07-13 09:10:52', '2015-07-13 09:10:52', '1'),
(26, 2, 60, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(27, 2, 61, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(28, 2, 62, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(29, 2, 63, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(30, 2, 64, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(31, 2, 65, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(32, 2, 66, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(33, 2, 67, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(34, 2, 68, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(35, 2, 69, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(36, 2, 70, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(37, 2, 71, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(38, 2, 72, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(39, 2, 73, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(40, 2, 74, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(41, 2, 75, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(42, 2, 76, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(43, 2, 77, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(44, 2, 78, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(45, 2, 79, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(46, 2, 80, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(47, 2, 84, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(48, 2, 85, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(49, 2, 86, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(50, 2, 88, 1, '2015-07-13 09:10:53', '2015-07-13 09:10:53', '1'),
(51, 2, 89, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(52, 2, 90, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(53, 2, 91, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(54, 2, 93, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(55, 2, 94, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(56, 2, 95, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(57, 2, 96, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(58, 2, 98, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(59, 2, 99, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(60, 2, 100, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(61, 2, 101, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(62, 2, 102, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(63, 2, 103, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(64, 2, 104, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(65, 2, 105, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(66, 2, 114, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(68, 2, 116, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(70, 2, 118, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(71, 2, 119, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(72, 2, 120, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(73, 2, 121, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(75, 2, 123, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(76, 2, 124, 1, '2015-07-13 09:10:54', '2015-07-13 09:10:54', '1'),
(77, 2, 125, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(78, 2, 126, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(80, 2, 128, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(81, 2, 129, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(82, 2, 130, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(83, 2, 131, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(85, 2, 133, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(86, 2, 134, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(87, 2, 135, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(88, 2, 136, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(90, 2, 138, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(91, 2, 139, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(92, 2, 140, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(93, 2, 141, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(95, 2, 143, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(96, 2, 144, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(97, 2, 145, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(98, 2, 146, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(100, 2, 148, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(101, 2, 149, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(102, 2, 150, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(103, 2, 151, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(105, 2, 153, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(106, 2, 154, 1, '2015-07-13 09:10:55', '2015-07-13 09:10:55', '1'),
(107, 2, 155, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(108, 2, 171, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(109, 2, 172, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(110, 2, 176, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(111, 2, 177, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(112, 2, 180, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(113, 2, 186, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(114, 2, 187, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(115, 2, 191, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(116, 2, 192, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(117, 2, 201, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(118, 2, 202, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(119, 2, 206, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(120, 2, 207, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(121, 2, 211, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(122, 2, 212, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(123, 2, 213, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(124, 2, 214, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(125, 2, 215, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(126, 2, 216, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(127, 2, 217, 1, '2015-07-13 09:10:56', '2015-07-13 09:10:56', '1'),
(128, 2, 218, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(129, 2, 219, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(130, 2, 220, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(131, 2, 1, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(132, 2, 2, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(133, 2, 3, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(134, 2, 4, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(135, 2, 5, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(136, 2, 6, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(137, 2, 7, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(138, 2, 8, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(139, 2, 9, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(140, 2, 10, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(141, 2, 11, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(142, 2, 12, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(143, 2, 14, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(144, 2, 15, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(145, 2, 16, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(146, 2, 18, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(147, 2, 19, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(148, 2, 20, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(149, 2, 21, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(150, 2, 22, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(151, 2, 23, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(152, 2, 24, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(153, 2, 25, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(154, 2, 26, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(155, 2, 27, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(156, 2, 28, 1, '2015-07-13 09:10:57', '2015-07-13 09:10:57', '1'),
(157, 2, 29, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(158, 2, 30, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(159, 2, 31, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(160, 2, 32, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(161, 2, 33, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(162, 2, 34, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(163, 2, 35, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(164, 2, 36, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(165, 2, 37, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(166, 2, 38, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(167, 2, 39, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(168, 2, 40, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(169, 2, 44, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(170, 2, 45, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(171, 2, 46, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(172, 2, 47, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(173, 2, 48, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(174, 2, 49, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(175, 2, 50, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(176, 2, 51, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(177, 2, 52, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(178, 2, 53, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(179, 2, 54, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(180, 2, 55, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(181, 2, 56, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(182, 2, 57, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(183, 2, 58, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(184, 2, 59, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(185, 2, 60, 1, '2015-07-13 09:10:58', '2015-07-13 09:10:58', '1'),
(186, 2, 61, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(187, 2, 62, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(188, 2, 63, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(189, 2, 64, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(190, 2, 65, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(191, 2, 66, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(192, 2, 67, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(193, 2, 68, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(194, 2, 69, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(195, 2, 70, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(196, 2, 71, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(197, 2, 72, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(198, 2, 73, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(199, 2, 74, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(200, 2, 75, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(201, 2, 76, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(202, 2, 77, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(203, 2, 78, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(204, 2, 79, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(205, 2, 80, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(206, 2, 84, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(207, 2, 85, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(208, 2, 86, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(209, 2, 88, 1, '2015-07-13 09:10:59', '2015-07-13 09:10:59', '1'),
(210, 2, 89, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(211, 2, 90, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(212, 2, 91, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(213, 2, 93, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(214, 2, 94, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(215, 2, 95, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(216, 2, 96, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(217, 2, 98, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(218, 2, 99, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(219, 2, 100, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(220, 2, 101, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(221, 2, 102, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(222, 2, 103, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(223, 2, 104, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(224, 2, 105, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(225, 2, 109, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(226, 2, 110, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(227, 2, 111, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(228, 2, 112, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(229, 2, 113, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(230, 2, 114, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(232, 2, 116, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(234, 2, 118, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(235, 2, 119, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(236, 2, 120, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(237, 2, 121, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(239, 2, 123, 1, '2015-07-13 09:11:00', '2015-07-13 09:11:00', '1'),
(240, 2, 124, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(241, 2, 125, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(242, 2, 126, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(244, 2, 128, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(245, 2, 129, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(246, 2, 130, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(247, 2, 131, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(249, 2, 133, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(250, 2, 134, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(251, 2, 135, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(252, 2, 136, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(254, 2, 138, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(255, 2, 139, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(256, 2, 140, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(257, 2, 141, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(259, 2, 143, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(260, 2, 144, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(261, 2, 145, 1, '2015-07-13 09:11:01', '2015-07-13 09:11:01', '1'),
(262, 2, 146, 1, '2015-07-13 09:11:02', '2015-07-13 09:11:02', '1'),
(264, 2, 148, 1, '2015-07-13 09:11:02', '2015-07-13 09:11:02', '1'),
(265, 2, 149, 1, '2015-07-13 09:11:02', '2015-07-13 09:11:02', '1'),
(266, 2, 150, 1, '2015-07-13 09:11:03', '2015-07-13 09:11:03', '1'),
(267, 2, 151, 1, '2015-07-13 09:11:03', '2015-07-13 09:11:03', '1'),
(269, 2, 153, 1, '2015-07-13 09:11:03', '2015-07-13 09:11:03', '1'),
(270, 2, 154, 1, '2015-07-13 09:11:03', '2015-07-13 09:11:03', '1'),
(271, 2, 155, 1, '2015-07-13 09:11:03', '2015-07-13 09:11:03', '1'),
(272, 2, 156, 1, '2015-07-13 09:11:03', '2015-07-13 09:11:03', '1'),
(273, 2, 157, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(274, 2, 158, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(275, 2, 159, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(276, 2, 160, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(277, 2, 161, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(278, 2, 162, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(279, 2, 166, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(280, 2, 167, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(281, 2, 171, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(282, 2, 172, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(283, 2, 176, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(284, 2, 177, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(285, 2, 180, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(286, 2, 186, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(287, 2, 187, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(288, 2, 191, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(289, 2, 192, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(290, 2, 196, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(291, 2, 197, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(292, 2, 201, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(293, 2, 202, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(294, 2, 206, 1, '2015-07-13 09:11:04', '2015-07-13 09:11:04', '1'),
(295, 2, 207, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(296, 2, 211, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(297, 2, 212, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(298, 2, 213, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(299, 2, 214, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(300, 2, 215, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(301, 2, 216, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(302, 2, 217, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(303, 2, 218, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(304, 2, 219, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(305, 2, 220, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(306, 2, 221, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(307, 2, 222, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(308, 2, 223, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(310, 2, 225, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(311, 2, 226, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(312, 2, 227, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(313, 2, 228, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(314, 2, 229, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(315, 2, 230, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(316, 2, 231, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1'),
(317, 2, 232, 1, '2015-07-13 09:11:05', '2015-07-13 09:11:05', '1');

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
