-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 15, 2020 at 04:19 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `youtube_comment`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

DROP TABLE IF EXISTS `campaign`;
CREATE TABLE IF NOT EXISTS `campaign` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `verify_number` int(11) DEFAULT NULL,
  `landing_page` varchar(512) DEFAULT NULL,
  `btn_text` varchar(128) DEFAULT NULL,
  `custom_css` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` (`id`, `name`, `verify_number`, `landing_page`, `btn_text`, `custom_css`) VALUES
(6, 'Dota2 Go Go Go', 2, 'https%3A%2F%2Fdota-2.vn%2F', 'Go Dota Now', ''),
(5, 'quan ao', 2, 'https%3A%2F%2Ffonts.google.com%2F%3Fsubset%3Dvietnamese2', 'Verify', ''),
(7, 'aaaaaaaa', 3, 'aaaaaaaaa', 'Verifyaaaaaaaaaaa', 'aaaaaaaa'),
(8, 'Dota2Vn', 3, 'https%3A%2F%2Fdota-2.vn%2F', 'Go Dota  22', 'adasdasdasdasdasd'),
(14, 'Dota2 Go Go Go - Copy', 2, 'https%3A%2F%2Fdota-2.vn%2F', 'Go Dota Now', ''),
(15, 'new campaign', 2, 'https%3A%2F%2Fdota-2.vn%2F', 'Verify', '21212');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` varchar(256) DEFAULT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `keyword_list` text DEFAULT NULL,
  `comment_list` text DEFAULT NULL,
  `channel` varchar(256) DEFAULT NULL,
  `url` varchar(512) DEFAULT NULL,
  `custom_html` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_campaign` (`campaign_id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `campaign_id`, `type`, `keyword_list`, `comment_list`, `channel`, `url`, `custom_html`) VALUES
(24, 'spec video 12', 6, 3, '', 'spec video 12', '', 'spec+video+12', NULL),
(25, 'spec comment', 6, 4, '', 'spec comment', '', 'spec+comment', NULL),
(27, 'spec comment link 2', 6, 4, '', 'spec comment link 2', '', 'spec+comment+link+2', NULL),
(28, 'custom task', 6, 5, 'custom task', 'custom task', 'custom task', '', NULL),
(20, 'test update', 6, 5, 'dota2', 'test 1\r\ntest 2\r\ntest 3\r\ntest 4\r\ntest 5', 'dota2vn.com', '', NULL),
(21, 'test custom', 0, 5, 'dota2', 'test 1\r\ntest 2\r\ntest 3\r\ntest 4\r\ntest 5', 'dota2vn.com', '', NULL),
(22, 'test generate video update', 6, 1, 'test generate video 1\r\ntest generate video 2', 'comment 1\r\ncomment 2', '', '', NULL),
(23, 'genrate comment link 2', 6, 2, 'genrate comment link 2', 'genrate comment link 2', '', '', NULL),
(10, 'dsdsd', 8, 1, 'dasdasdasd', 'asd asdasd asd ', '', '', NULL),
(50, 'spec video 12', 14, 3, '', 'spec video 12', '', 'spec+video+12', NULL),
(51, 'spec comment', 14, 4, '', 'spec comment', '', 'spec+comment', NULL),
(52, 'spec comment link 2', 14, 4, '', 'spec comment link 2', '', 'spec+comment+link+2', NULL),
(53, 'custom task', 14, 5, 'custom task', 'custom task', 'custom task', '', NULL),
(54, 'test update', 14, 5, 'dota2', 'test 1\r\ntest 2\r\ntest 3\r\ntest 4\r\ntest 5', 'dota2vn.com', '', NULL),
(55, 'test generate video update', 14, 1, 'test generate video 1\r\ntest generate video 2', 'comment 1\r\ncomment 2', '', '', NULL),
(56, 'genrate comment link 2', 14, 2, 'genrate comment link 2', 'genrate comment link 2', '', '', NULL),
(58, 'test comment', 5, 2, 'dota 2 vn\r\nmiracle\r\nkuroky', 'comment 1\r\ncomment 2\r\ncomment 3\r\ncomment 4\r\ncomment 5\r\ncomment 6\r\ncomment 7', '', '', NULL),
(65, 'custom 1', 7, 5, '', 'custom comment 1\r\ncustom comment 2\r\ncustom comment 3\r\ncustom comment 4', NULL, '', 'custom html 1'),
(60, 'sdsdsd', 15, 3, '', 'asdasdds\r\nasdsadsdsdasd\r\nasdasdasd', '', 'https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DFKB_jQWiw88', NULL),
(61, 'sdsd', 15, 3, '', '123123\r\n454523\r\n896785 6\r\n90-6745', '', 'https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DgPK1aBwSHHI', NULL),
(62, '1sdasd', 15, 5, '', 'sdasdsad', NULL, '', 'ádasd'),
(63, '12312', 15, 6, '', '123123123123', NULL, '', '123123123'),
(64, 'update tét 3', 15, 7, '', 'update tét 3', '', '', 'đâsdas'),
(66, 'custom 1 1', 7, 5, '', 'custom 1 1', NULL, '', 'custom 1 1'),
(67, 'sdd', 7, 3, '', '123\r\n2353123\r\n12312', '', 'https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DFKB_jQWiw88', ''),
(68, 'asdsa', 7, 3, '', 'asdasd\r\nasdasdfsdas\r\nasdasdsxc\r\nasdsad', NULL, 'https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DgPK1aBwSHHI', '');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `key` varchar(128) DEFAULT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_campaign` (`campaign_id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `campaign_id`, `type`, `key`, `value`) VALUES
(1, 8, 1, 'header_html', '<h1>generate video</h1>\r\n<p>My first paragraph.</p>'),
(2, 8, 1, 'items_number', '1'),
(3, 8, 1, 'help_image', 'https://www.youtube.com/watch?v=9Y3eLOI2Vp4'),
(4, 8, 1, 'help_video', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/FKB_jQWiw88\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>'),
(5, 8, 2, 'header_html', '<h1>generate coment links</h1>\r\n<p>My first paragraph.</p>'),
(6, 8, 2, 'items_number', '2'),
(7, 8, 2, 'help_image', 'https://cldapi.net/HuVe/img/upvote_and_reply.gif'),
(8, 8, 2, 'help_video', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/FKB_jQWiw88222222\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>'),
(9, 8, 3, 'header_html', '<h1>generate video</h1>\r\n<p>My first paragraph.</p>'),
(10, 8, 3, 'items_number', '3'),
(11, 8, 3, 'help_image', '3333'),
(12, 8, 3, 'help_video', '3333'),
(13, 8, 4, 'header_html', '<h1>specifical comment link</h1>\r\n<p>My first paragraph.</p>'),
(14, 8, 4, 'items_number', '4'),
(15, 8, 4, 'help_image', '4444'),
(16, 8, 4, 'help_video', '4444'),
(17, 8, 5, 'header_html', '<h1>custom task</h1>\r\n<p>My first paragraph.</p>'),
(18, 8, 5, 'items_number', '5'),
(19, 8, 5, 'help_image', '555'),
(20, 8, 5, 'help_video', '55555'),
(21, 6, 1, 'header_html', '<div class=\"title\"><b>POST THE COMMENTS BELOW TO YOUTUBE</b></div>\r\n    <div class=\"description\"><p>To prevent robot abuse, you are required to complete human verification by posting each\r\n            comment below to the video on it\'s left.</p></div>'),
(22, 6, 1, 'items_number', '5'),
(23, 6, 1, 'help_image', 'https://cldapi.net/HuVe/img/upvote_and_reply.gif'),
(24, 6, 1, 'help_video', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/FKB_jQWiw88\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>'),
(25, 6, 2, 'items_number', '5'),
(26, 6, 2, 'header_html', '<div class=\"title\"><b>POST THE COMMENTS BELOW TO YOUTUBE COMMENT LINKS</b></div>\r\n    <div class=\"description\"><p>To prevent robot abuse, you are required to complete human verification by posting each\r\n            comment below to the video on it\'s left.</p></div>'),
(27, 6, 3, 'items_number', '5'),
(28, 6, 3, 'header_html', ''),
(29, 6, 4, 'items_number', '5'),
(30, 6, 4, 'header_html', ''),
(31, 6, 5, 'items_number', '5'),
(32, 6, 5, 'header_html', '<div class=\"title\"><b>POST THE COMMENTS BELOW TO YOUTUBE</b></div>\r\n    <div class=\"description\"><p>To prevent robot abuse, you are required to complete human verification by posting each\r\n            comment below to the video on it\'s left.</p></div>'),
(45, 14, 1, 'header_html', '<div class=\"title\"><b>POST THE COMMENTS BELOW TO YOUTUBE</b></div>\r\n    <div class=\"description\"><p>To prevent robot abuse, you are required to complete human verification by posting each\r\n            comment below to the video on it\'s left.</p></div>'),
(46, 14, 1, 'items_number', '5'),
(47, 14, 1, 'help_image', 'https://cldapi.net/HuVe/img/upvote_and_reply.gif'),
(48, 14, 1, 'help_video', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/FKB_jQWiw88\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>'),
(49, 14, 2, 'items_number', '5'),
(50, 14, 2, 'header_html', '<div class=\"title\"><b>POST THE COMMENTS BELOW TO YOUTUBE COMMENT LINKS</b></div>\r\n    <div class=\"description\"><p>To prevent robot abuse, you are required to complete human verification by posting each\r\n            comment below to the video on it\'s left.</p></div>'),
(51, 14, 3, 'items_number', '5'),
(52, 14, 3, 'header_html', ''),
(53, 14, 4, 'items_number', '5'),
(54, 14, 4, 'header_html', ''),
(55, 14, 5, 'items_number', '5'),
(56, 14, 5, 'header_html', ''),
(57, 15, 1, 'items_number', '5'),
(58, 15, 1, 'header_html', 'ewewewe'),
(59, 5, 2, 'items_number', '5'),
(60, 5, 2, 'header_html', ''),
(61, 7, 3, 'items_number', '5'),
(62, 7, 3, 'header_html', '<label>Spec video</label>'),
(63, 7, 4, 'items_number', '5'),
(64, 7, 4, 'header_html', ''),
(65, 15, 5, 'items_number', '5'),
(66, 15, 5, 'header_html', ''),
(67, 15, 3, 'items_number', '5'),
(68, 15, 3, 'header_html', ''),
(69, 7, 5, 'items_number', '5'),
(70, 7, 5, 'header_html', '<label>Custom Header 1</label>'),
(71, 7, 6, 'items_number', '5'),
(72, 7, 6, 'header_html', '<label>Custom Header 2</label>'),
(73, 7, 7, 'items_number', '0'),
(74, 7, 7, 'header_html', '<label>Custom Header 3</label>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) CHARACTER SET utf32 NOT NULL,
  `password` varchar(100) CHARACTER SET utf32 NOT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `active`) VALUES
(1, 'admin', 'ecd00aa1acd325ba7575cb0f638b04a5', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
