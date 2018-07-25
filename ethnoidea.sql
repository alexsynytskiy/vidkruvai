-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 12, 2018 at 02:30 PM
-- Server version: 5.5.59-MariaDB-1ubuntu0.14.04.1
-- PHP Version: 5.6.36-1+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coukraine`
--

-- --------------------------------------------------------

--
-- Table structure for table `catalog_items_i18n`
--

CREATE TABLE `catalog_items_i18n` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `language` varchar(6) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_admins`
--

CREATE TABLE `easyii_admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `auth_key` varchar(128) NOT NULL,
  `access_token` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_admins`
--

INSERT INTO `easyii_admins` (`admin_id`, `username`, `password`, `auth_key`, `access_token`) VALUES
(2, 'Oleksa', 'b8d8c821911007e51d1440f41739ae26b1d464d1', 'lb2DLg4ouCwgTQL_B8YNZkMrwQmHQTX2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_carousel`
--

CREATE TABLE `easyii_carousel` (
  `carousel_id` int(11) NOT NULL,
  `image` varchar(128) NOT NULL,
  `link` varchar(255) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `text` text,
  `order_num` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_catalog_categories`
--

CREATE TABLE `easyii_catalog_categories` (
  `category_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `fields` text NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `order_num` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_catalog_categories`
--

INSERT INTO `easyii_catalog_categories` (`category_id`, `title`, `image`, `fields`, `slug`, `tree`, `lft`, `rgt`, `depth`, `order_num`, `status`) VALUES
(1, 'Одежда', '', '[{\"name\":\"color\",\"title\":\"Color\",\"type\":\"checkbox\",\"options\":[\"white\",\"black\",\"green\",\"violet\"]},{\"name\":\"size\",\"title\":\"Size\",\"type\":\"checkbox\",\"options\":[\"s\",\"m\",\"l\"]}]', 'clothes', 1, 1, 2, 0, 2, 1),
(2, 'Аксесуари', '', '[]', 'accesories', 2, 1, 2, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_catalog_items`
--

CREATE TABLE `easyii_catalog_items` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `available` int(11) DEFAULT '1',
  `price` float DEFAULT '0',
  `discount` int(11) DEFAULT '0',
  `data` text NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `time` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_catalog_items`
--

INSERT INTO `easyii_catalog_items` (`item_id`, `category_id`, `available`, `price`, `discount`, `data`, `image`, `slug`, `time`, `status`) VALUES
(1, 1, 12, 100, 0, '{\"color\":[\"white\",\"black\",\"green\",\"violet\"],\"size\":[\"s\",\"m\",\"l\"]}', '/uploads/catalog/fruitoftheloom061372030simages13-48d03e0b8c.jpg', 't-shirt-woman', 1501859750, 1),
(2, 1, 5, 150, 0, '{\"color\":[\"white\",\"black\",\"violet\"],\"size\":[\"s\",\"l\"]}', '/uploads/catalog/ag4a0608001800x600w-0fcf817563.jpg', 'pants', 1501859609, 1),
(3, 1, 9, 90, 0, '{\"color\":[\"white\",\"black\",\"green\"],\"size\":[\"s\",\"m\"]}', '/uploads/catalog/kepka-nike-s-pryamym-kozyrkom-8e65b9772a.jpg', 'cap', 1501858225, 1),
(4, 2, 6, 200, 0, '{}', '/uploads/catalog/2017-08-04-181823-2cd712b155.png', 'bag', 1501859866, 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_catalog_item_data`
--

CREATE TABLE `easyii_catalog_item_data` (
  `data_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `value` varchar(1024) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_catalog_item_data`
--

INSERT INTO `easyii_catalog_item_data` (`data_id`, `item_id`, `name`, `value`) VALUES
(220, 2, 'size', 'l'),
(215, 1, 'size', 'l'),
(214, 1, 'size', 'm'),
(213, 1, 'size', 's'),
(219, 2, 'size', 's'),
(218, 2, 'color', 'violet'),
(224, 3, 'size', 's'),
(223, 3, 'color', 'green'),
(216, 2, 'color', 'white'),
(211, 1, 'color', 'green'),
(221, 3, 'color', 'white'),
(212, 1, 'color', 'violet'),
(222, 3, 'color', 'black'),
(217, 2, 'color', 'black'),
(210, 1, 'color', 'black'),
(209, 1, 'color', 'white'),
(225, 3, 'size', 'm');

-- --------------------------------------------------------

--
-- Table structure for table `easyii_faq`
--

CREATE TABLE `easyii_faq` (
  `faq_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `order_num` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_feedback`
--

CREATE TABLE `easyii_feedback` (
  `feedback_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `surname` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `text` text NOT NULL,
  `answer_subject` varchar(128) DEFAULT NULL,
  `answer_text` text,
  `time` int(11) DEFAULT '0',
  `ip` varchar(16) NOT NULL,
  `status` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_files`
--

CREATE TABLE `easyii_files` (
  `file_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `file` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `downloads` int(11) DEFAULT '0',
  `time` int(11) DEFAULT '0',
  `order_num` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_gallery_categories`
--

CREATE TABLE `easyii_gallery_categories` (
  `category_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `order_num` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_gallery_categories`
--

INSERT INTO `easyii_gallery_categories` (`category_id`, `title`, `image`, `slug`, `tree`, `lft`, `rgt`, `depth`, `order_num`, `status`) VALUES
(10, 'Партнери', '', 'partners', 10, 1, 2, 0, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_guestbook`
--

CREATE TABLE `easyii_guestbook` (
  `guestbook_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `text` text NOT NULL,
  `answer` text,
  `email` varchar(128) DEFAULT NULL,
  `time` int(11) DEFAULT '0',
  `ip` varchar(16) NOT NULL,
  `new` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_loginform`
--

CREATE TABLE `easyii_loginform` (
  `log_id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `user_agent` varchar(1024) NOT NULL,
  `time` int(11) DEFAULT '0',
  `success` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_loginform`
--

INSERT INTO `easyii_loginform` (`log_id`, `username`, `password`, `ip`, `user_agent`, `time`, `success`) VALUES
(1, 'root', '******', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', 1508083076, 1),
(2, 'root', '******', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', 1508254377, 1),
(3, 'root', '******', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', 1511125050, 1),
(4, 'root', '******', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36', 1511354614, 1),
(5, 'Nastya Vergeles', '******', '195.245.221.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5', 1512998911, 1),
(6, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7', 1514297633, 1),
(7, 'root', '******', '185.38.184.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36', 1514466146, 1),
(8, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7', 1516897671, 1),
(9, 'mlgdog', 'gagarina464', '195.245.221.107', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', 1516897819, 0),
(10, 'mlgdog', 'Gagarina464', '195.245.221.107', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', 1516897824, 0),
(11, 'mlgdog', 'Denblo101269', '195.245.221.107', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', 1516897835, 0),
(12, 'mlgdogie', 'Denblo101269', '195.245.221.107', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', 1516897840, 0),
(13, 'mlgdogie', 'gagarina464', '195.245.221.107', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', 1516897846, 0),
(14, 'mlgdogie', 'Gagarina464', '195.245.221.107', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', 1516897855, 0),
(15, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36', 1516898338, 1),
(16, 'Nastya Vergeles', '******', '93.73.34.164', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1517162475, 1),
(17, 'Nastya Vergeles', '******', '159.224.19.2', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1517397923, 1),
(18, 'Nastya Vergeles', '******', '93.73.34.164', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1517985763, 1),
(19, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1518440737, 1),
(20, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1518543234, 1),
(21, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1518609621, 1),
(22, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1518793524, 1),
(23, 'admin', 'As1326Nhcr', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1518957407, 0),
(24, 'admin', 'admin', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1518957413, 0),
(25, 'admin', 'As1326Nhcr', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1518957679, 0),
(26, 'Artem', '******', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1518957728, 1),
(27, 'Artem', '******', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1518959697, 1),
(28, 'Oleksa', 'As1326Nhcr', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1518959767, 0),
(29, 'Oleksa', '******', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1518959810, 1),
(30, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1519050713, 1),
(31, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1519141378, 1),
(32, 'Nastya Vergeles', '******', '195.245.221.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1519307533, 1),
(33, 'Nastya Vergeles', '******', '159.224.19.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1519808731, 1),
(34, 'Artem', '******', '91.214.85.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36', 1519809960, 1),
(35, 'Artem', '******', '91.214.85.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36', 1520018040, 1),
(36, 'Nastya Vergeles', '******', '159.224.19.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1520158779, 1),
(37, 'Nastya Vergeles', '******', '93.73.34.164', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1520273452, 1),
(38, 'Artem', '******', '91.214.85.71', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36', 1520350337, 1),
(39, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1520793241, 1),
(40, 'Nastya Vergeles', '******', '93.73.34.164', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1520961492, 1),
(41, 'root', '******', '217.20.164.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36', 1521105074, 1),
(42, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1521218765, 1),
(43, 'Artemiy', '4648744', '91.214.85.85', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522229259, 0),
(44, 'Artemiy', '4648744', '91.214.85.85', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522229488, 0),
(45, 'Artemiy', 'gagarina4648744', '91.214.85.85', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522229500, 0),
(46, 'Artemiy', 'Gagarina4648744', '91.214.85.85', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522229504, 0),
(47, 'Artem', '******', '91.214.85.85', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522229512, 1),
(48, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1522239899, 1),
(49, 'Nastya Vergeles', '******', '81.22.130.203', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1522576562, 1),
(50, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522672783, 0),
(51, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522672800, 0),
(52, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522672803, 0),
(53, 'NastiaVergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522672816, 0),
(54, 'NastiaVergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522672824, 0),
(55, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522672849, 0),
(56, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522678092, 0),
(57, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522678105, 0),
(58, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522678125, 0),
(59, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522678238, 0),
(60, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522678239, 0),
(61, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1522767621, 1),
(62, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1522768254, 1),
(63, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1522938496, 1),
(64, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1523348300, 1),
(65, 'Nastia Vergeles', '11081991', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1523352649, 0),
(66, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1523352702, 1),
(67, 'Nastya Vergeles', '******', '195.245.221.9', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1523616953, 1),
(68, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1524038981, 1),
(69, 'Nastya Vergeles', '******', '93.72.19.154', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36', 1524216730, 1),
(70, 'Nastya Vergeles', '******', '91.196.90.52', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1524393553, 1),
(71, 'Nastya Vergeles', '******', '178.165.91.171', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1524480445, 1),
(72, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1524578504, 1),
(73, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1525260285, 1),
(74, 'Nastya Vergeles', '******', '185.121.108.28', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1525598115, 1),
(75, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1525771543, 1),
(76, 'Nastya Vergeles', '******', '93.72.19.154', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36', 1525867906, 1),
(77, 'Nastya Vergeles', '******', '194.44.220.190', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1525871715, 1),
(78, 'root', '******', '217.20.164.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36', 1525952262, 1),
(79, 'Nastya Vergeles', '******', '194.44.220.190', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1526366307, 1),
(80, 'Nastya Vergeles', '******', '194.153.128.36', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1526820575, 1),
(81, 'root', '******', '217.20.164.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36', 1526895147, 1),
(82, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1527068464, 1),
(83, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1527163957, 1),
(84, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1527509816, 1),
(85, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1527854883, 1),
(86, 'root', '******', '93.73.76.229', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36', 1528041473, 1),
(87, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1528189957, 1),
(88, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/604.5.6 (KHTML, like Gecko) Version/11.0.3 Safari/604.5.6', 1528368597, 1),
(89, 'Nastya Vergeles', '******', '93.72.19.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36', 1528889078, 1),
(90, 'Nastya Vergeles', '******', '195.245.221.33', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36', 1528965150, 1),
(91, 'root', '******', '192.168.33.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36', 1531165446, 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_migration`
--

CREATE TABLE `easyii_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_migration`
--

INSERT INTO `easyii_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1497278953),
('m000000_000000_install', 1497278953);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_modules`
--

CREATE TABLE `easyii_modules` (
  `module_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `class` varchar(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  `icon` varchar(32) NOT NULL,
  `settings` text NOT NULL,
  `notice` int(11) DEFAULT '0',
  `order_num` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_modules`
--

INSERT INTO `easyii_modules` (`module_id`, `name`, `class`, `title`, `icon`, `settings`, `notice`, `order_num`, `status`) VALUES
(2, 'carousel', 'yii\\easyii\\modules\\carousel\\CarouselModule', 'Карусель', 'picture', '{\"enableTitle\":true,\"enableText\":true}', 0, 30, 0),
(3, 'catalog', 'yii\\easyii\\modules\\catalog\\CatalogModule', 'Каталог', 'list-alt', '{\"categoryThumb\":true,\"itemsInFolder\":false,\"itemThumb\":true,\"itemPhotos\":true,\"itemDescription\":true,\"itemSale\":true}', 0, 100, 0),
(4, 'faq', 'yii\\easyii\\modules\\faq\\FaqModule', 'Вопросы и ответы', 'question-sign', '[]', 0, 40, 0),
(5, 'feedback', 'yii\\easyii\\modules\\feedback\\FeedbackModule', 'Обратная связь', 'earphone', '{\"mailAdminOnNewFeedback\":true,\"subjectOnNewFeedback\":\"New feedback\",\"templateOnNewFeedback\":\"@easyii\\/modules\\/feedback\\/mail\\/uk\\/new_feedback\",\"answerTemplate\":\"@easyii\\/modules\\/feedback\\/mail\\/uk\\/answer\",\"answerSubject\":\"Answer on your feedback message\",\"answerHeader\":\"Hello,\",\"answerFooter\":\"Best regards.\",\"enableTitle\":false,\"enablePhone\":true,\"enableCaptcha\":false}', 0, 60, 1),
(6, 'file', 'yii\\easyii\\modules\\file\\FileModule', 'Файлы', 'floppy-disk', '[]', 0, 20, 1),
(7, 'gallery', 'yii\\easyii\\modules\\gallery\\GalleryModule', 'Фотогалерея', 'camera', '{\"categoryThumb\":true,\"itemsInFolder\":false}', 0, 80, 1),
(8, 'guestbook', 'yii\\easyii\\modules\\guestbook\\GuestbookModule', 'Гостевая книга', 'book', '{\"enableTitle\":false,\"enableEmail\":true,\"preModerate\":false,\"enableCaptcha\":false,\"mailAdminOnNewPost\":true,\"subjectOnNewPost\":\"New message in the guestbook.\",\"templateOnNewPost\":\"@easyii\\/modules\\/guestbook\\/mail\\/en\\/new_post\",\"frontendGuestbookRoute\":\"\\/guestbook\",\"subjectNotifyUser\":\"Your post in the guestbook answered\",\"templateNotifyUser\":\"@easyii\\/modules\\/guestbook\\/mail\\/en\\/notify_user\"}', 0, 70, 0),
(9, 'news', 'yii\\easyii\\modules\\news\\NewsModule', 'Публикации', 'bullhorn', '{\"enableThumb\":true,\"enablePhotos\":true,\"enableShort\":true,\"shortMaxLength\":256,\"enableTags\":true}', 0, 90, 1),
(10, 'page', 'yii\\easyii\\modules\\page\\PageModule', 'Страницы', 'file', '[]', 0, 45, 1),
(11, 'shopcart', 'yii\\easyii\\modules\\shopcart\\ShopcartModule', 'Заказы', 'shopping-cart', '{\"mailAdminOnNewOrder\":true,\"subjectOnNewOrder\":\"New order\",\"templateOnNewOrder\":\"@easyii\\/modules\\/shopcart\\/mail\\/en\\/new_order\",\"subjectNotifyUser\":\"Your order status changed\",\"templateNotifyUser\":\"@easyii\\/modules\\/shopcart\\/mail\\/en\\/notify_user\",\"frontendShopcartRoute\":\"\\/shopcart\\/order\",\"enablePhone\":true,\"enableEmail\":true}', 0, 120, 0),
(12, 'subscribe', 'yii\\easyii\\modules\\subscribe\\SubscribeModule', 'E-mail рассылка', 'envelope', '[]', 0, 50, 1),
(13, 'text', 'yii\\easyii\\modules\\text\\TextModule', 'Текстовые блоки', 'font', '[]', 0, 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_news`
--

CREATE TABLE `easyii_news` (
  `news_id` int(11) NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `time` int(11) DEFAULT '0',
  `views` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_news`
--

INSERT INTO `easyii_news` (`news_id`, `image`, `slug`, `category`, `time`, `views`, `status`) VALUES
(33, '/uploads/news/dla-sajtu-pro-balansi-c101ed1910.jpg', 'instagram-1', 'news', 1525260388, 128, 1),
(47, '/uploads/news/1-6690fdf7c0.png', 'vesilla-tetani-ta-igora-plastun', 'portfolio', 1524746940, 46, 1),
(34, '/uploads/news/6-64033a734f.jpg', 'oko-programi-fundacia-zapuskae-tretij-napramok-ohorona-zdorova', 'news', 1525780740, 66, 1),
(35, '/uploads/news/vidkrukr-2449-718163a7c7.jpg', 'kruglij-stil-pidtrimka-skolariv-aki-zdaut-zno-ce-nas-z-vami-ispit', 'news', 1525868084, 69, 1),
(36, '/uploads/news/32169245191828901153788075961818-7e5e52334b.jpg', 'svato-peremogi-ci-vsanuvanna-zertv-vijni', 'news', 1526820583, 191, 1),
(37, '/uploads/news/fest-55669afeb3.jpg', 'rekomendacii-vid-pavla-gorba-festivalnij-bum-2018', 'news', 1531231954, 263, 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_pages`
--

CREATE TABLE `easyii_pages` (
  `page_id` int(11) NOT NULL,
  `slug` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_photos`
--

CREATE TABLE `easyii_photos` (
  `photo_id` int(11) NOT NULL,
  `class` varchar(128) NOT NULL,
  `item_id` int(11) NOT NULL,
  `image` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `link` varchar(512) DEFAULT NULL,
  `order_num` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_photos`
--

INSERT INTO `easyii_photos` (`photo_id`, `class`, `item_id`, `image`, `description`, `link`, `order_num`) VALUES
(375, 'yii\\easyii\\modules\\news\\models\\News', 47, '/uploads/photos/2-4e3f5762e8.png', '', '', 3),
(376, 'yii\\easyii\\modules\\news\\models\\News', 47, '/uploads/photos/3-cd5122a878.png', '', '', 4),
(374, 'yii\\easyii\\modules\\gallery\\models\\Category', 10, '/uploads/photos/instagram-png-logo-5a3a21744cfd7-b72a31e969.jpg', '', '', 2),
(377, 'yii\\easyii\\modules\\news\\models\\News', 47, '/uploads/photos/1-e341ff502b.png', '', '', 5);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_seotext`
--

CREATE TABLE `easyii_seotext` (
  `seotext_id` int(11) NOT NULL,
  `class` varchar(128) NOT NULL,
  `item_id` int(11) NOT NULL,
  `h1` varchar(128) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `keywords` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_settings`
--

CREATE TABLE `easyii_settings` (
  `setting_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(128) NOT NULL,
  `value` varchar(1024) NOT NULL,
  `visibility` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_settings`
--

INSERT INTO `easyii_settings` (`setting_id`, `name`, `title`, `value`, `visibility`) VALUES
(1, 'easyii_version', 'System version', '1.0', 0),
(2, 'recaptcha_key', 'ReCaptcha key', '', 1),
(3, 'password_salt', 'Password salt', 'D7WhIgfxKDvKRftyhyusAaF2xqZli4r7', 0),
(4, 'root_auth_key', 'Root authorization key', 'WyigAQvqMvLN5AnWYy6L58u_F7w-2RMM', 0),
(5, 'root_password', 'Пароль разработчика', '7f4a0141b580229faba1821928814bbe7252ab65', 1),
(6, 'auth_time', 'Время авторизации', '86400', 1),
(7, 'robot_email', 'E-mail рассыльщика', 'info@coukraine.org', 1),
(8, 'admin_email', 'E-mail администратора', 'alexsynytskiy@gmail.com', 2),
(9, 'recaptcha_secret', 'ReCaptcha secret', '', 1),
(10, 'toolbar_position', 'Позиция панели на сайте (\"top\" or \"bottom\")', 'bottom', 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_shopcart_goods`
--

CREATE TABLE `easyii_shopcart_goods` (
  `good_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `options` varchar(255) NOT NULL,
  `price` float DEFAULT '0',
  `discount` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_shopcart_orders`
--

CREATE TABLE `easyii_shopcart_orders` (
  `order_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `comment` varchar(1024) NOT NULL,
  `remark` varchar(1024) NOT NULL,
  `access_token` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `time` int(11) DEFAULT '0',
  `new` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_subscribe_history`
--

CREATE TABLE `easyii_subscribe_history` (
  `history_id` int(11) NOT NULL,
  `subject` varchar(128) NOT NULL,
  `body` text NOT NULL,
  `sent` int(11) DEFAULT '0',
  `time` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_subscribe_history`
--

INSERT INTO `easyii_subscribe_history` (`history_id`, `subject`, `body`, `sent`, `time`) VALUES
(1, '\"Фестивальний бум\" від Павла Горба :)', '<p>Друзі, колеги, вітаю!&nbsp;</p><p>Раді, що ви підписались на наш сайт та згодились отримувати від нас цікаві новини. Ми цьому дуже раді!</p><p>Ви знаєете, що наша Фундація соціальних інновацій \"З країни в Україну\" працює у трьох напрямках - культура, освіта та охорона здоров\'я.&nbsp;</p><p>Сьогодні хочемо&nbsp;вас сьогодні познайомити з нашим одним з менеджерів - Павло Горб. Саме він відповідає за культурний напрямок. Щотижня Павло робить круті, культурні підбірки, які ми назвали #рекомендації від Павла Горба. І у нас на сайті у розліді \"культура\" ви зможете знайти найсвіжішу підбірку про фестивальний бум 2018 року. Саме там Павло розповідає про фестивалі, які будуть цього року, і які дійсно варто відвідати! Читайте, знайомтесь та будемо на зв\'язку :)</p><p>Нижче стаття :)</p><p><a href=\"https://coukraine.org/news/rekomendacii-vid-pavla-gorba-festivalnij-bum-2018\">https://coukraine.org/news/rekomendacii-vid-pavla-...</a></p><p>А ось це наш Павло</p><p><img src=\"/uploads/fest-b124567828.jpg\"></p>', 13, 1526903835);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_subscribe_subscribers`
--

CREATE TABLE `easyii_subscribe_subscribers` (
  `subscriber_id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `time` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_subscribe_subscribers`
--

INSERT INTO `easyii_subscribe_subscribers` (`subscriber_id`, `email`, `ip`, `time`) VALUES
(4, 'alexsynytskiy@ukr.net', '93.73.76.229', 1518958311);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_tags`
--

CREATE TABLE `easyii_tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `frequency` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_tags`
--

INSERT INTO `easyii_tags` (`tag_id`, `name`, `frequency`) VALUES
(144, 'Kids Of Action', 3),
(103, 'Конференція', 1),
(99, 'Підсумки', 1),
(97, 'Культура', 2),
(98, 'освіта', 1),
(96, 'Фундація', 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_tags_assign`
--

CREATE TABLE `easyii_tags_assign` (
  `class` varchar(128) NOT NULL,
  `item_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_texts`
--

CREATE TABLE `easyii_texts` (
  `text_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `slug` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `items_i18n`
--

CREATE TABLE `items_i18n` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `language` varchar(6) NOT NULL,
  `title` varchar(128) NOT NULL,
  `short` varchar(1024) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1497368558),
('m170613_153947_artist_module', 1497368560);

-- --------------------------------------------------------

--
-- Table structure for table `news_i18n`
--

CREATE TABLE `news_i18n` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `language` varchar(6) NOT NULL,
  `title` varchar(128) NOT NULL,
  `short` varchar(1024) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_i18n`
--

INSERT INTO `news_i18n` (`id`, `news_id`, `language`, `title`, `short`, `text`) VALUES
(189, 33, 'uk', 'Instagram test 1', 'Сьогодні розкажемо Вам про панельну дискусію «У пошуках балансу: чи можлива рівність у шкільній освіті?», на якій побувала менеджерка освітнього напрямку Фундації соціальних інновацій Альона Матвієнко.', '<blockquote class=\"instagram-media\" data-instgrm-captioned=\"\" data-instgrm-permalink=\"https://www.instagram.com/p/BkxkiyYlrx1/\" data-instgrm-version=\"8\" style=\" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);\">\r\n<p style=\"padding:8px;\"> </p><p style=\" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;\"> </p><p style=\" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;\">\r\n </p><p style=\" margin:8px 0 0 0; padding:0 4px;\"> <a href=\"https://www.instagram.com/p/BkxkiyYlrx1/\" style=\"color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: 14px; font-style: normal; font-weight: normal; text-decoration: none; word-wrap: break-word;\" target=\"_blank\">Полировка снова в моде! Поэтому мы оставили отлично сохранившееся покрытие на дверцах и ящиках тумбы (раньше это был сервант), добавив облику новизны в виде геометрических штрихов. -------- Фотосессия тумбы была бы неполной без декора от наших соседей @studiadecora #переделкамебели #переделкатумбочки #перекраскатумбы #перекраскамебели #furnituremakeover #furnituredesign #möbeldesign #midcenturytvstand #meublerelooké</a></p><p style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;\">A post shared by <a href=\"https://www.instagram.com/kamorka.workshop/\" style=\"color: rgb(201, 200, 205); font-family: Arial, sans-serif; font-size: 14px; font-style: normal; font-weight: normal;\" target=\"_blank\"> Винтажная мебель Masha&amp;Viktor</a> (@kamorka.workshop) on <time style=\" font-family:Arial,sans-serif; font-size:14px; line-height:17px;\" datetime=\"2018-07-03T15:28:33+00:00\">Jul 3, 2018 at 8:28am PDT</time></p></blockquote>'),
(190, 33, 'en', '', '', ''),
(191, 33, 'sk', '', '', ''),
(192, 33, 'pl', '', '', ''),
(193, 33, 'hu', '', '', ''),
(194, 33, 'ro', '', '', ''),
(195, 34, 'uk', 'Instagram test 2', 'Фундація соціальних інновацій «З країни в Україну» впроваджує новий напрямок діяльності - охорона здоров’я. Денис Блощинський розповів про реалізацію ОКО-програм та розкрив таємницю перших пілотних проектів.', '<blockquote class=\"instagram-media\" data-instgrm-captioned data-instgrm-permalink=\"https://www.instagram.com/p/BknF-Calf-U/\" data-instgrm-version=\"8\" style=\" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);\"><div style=\"padding:8px;\"> <div style=\" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;\"> <div style=\" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;\"></div></div> <p style=\" margin:8px 0 0 0; padding:0 4px;\"> <a href=\"https://www.instagram.com/p/BknF-Calf-U/\" style=\" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;\" target=\"_blank\">Сегодня представляем обновлённое кресло с красивыми изогнутыми подлокотниками. Мы привезли его из Словакии, так что это эксклюзив - в наших широтах таких не водится. Придумано и произведено в 60-х годах в Чехословакии. Пружины - в сиденье и в спинке! Цена 28 т.р. У кресла также есть собрат без пружин - под переделку, можно сделать в любом цвете. --------------------- Красиво сфотографировать кресло помог декор от наших творческих соседей @studiadecora #винтажноекресло #винтажнаямебель #vintagearmchair #midcenturyarmchair #czechoslovakianfurniture #стараямебель #староекресло</a></p> <p style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;\">A post shared by <a href=\"https://www.instagram.com/kamorka.workshop/\" style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;\" target=\"_blank\"> Винтажная мебель Masha&amp;Viktor</a> (@kamorka.workshop) on <time style=\" font-family:Arial,sans-serif; font-size:14px; line-height:17px;\" datetime=\"2018-06-29T13:48:59+00:00\">Jun 29, 2018 at 6:48am PDT</time></p></div></blockquote> <script async defer src=\"//www.instagram.com/embed.js\"></script>'),
(196, 34, 'en', '', '', ''),
(197, 34, 'sk', '', '', ''),
(198, 34, 'pl', '', '', ''),
(199, 34, 'hu', '', '', ''),
(200, 34, 'ro', '', '', ''),
(201, 35, 'uk', 'Instagram test 3', '', '<blockquote class=\"instagram-media\" data-instgrm-captioned data-instgrm-permalink=\"https://www.instagram.com/p/BkfmeHtF2XY/\" data-instgrm-version=\"8\" style=\" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);\"><div style=\"padding:8px;\"> <div style=\" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;\"> <div style=\" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;\"></div></div> <p style=\" margin:8px 0 0 0; padding:0 4px;\"> <a href=\"https://www.instagram.com/p/BkfmeHtF2XY/\" style=\" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;\" target=\"_blank\">В такую погоду даже столики не могут находиться в четырёх стенах! На фото - чехословацкий столик Mier Topolcany из 60-х, после лёгкой реставрации. Уже обрёл новый дом. #ретростолик #журнальныйстолик #винтажнаямебель #винтажнаямебельспб #ретромебель #мебельссср #vintagefurniture #vintagecoffeetable #czechoslovakianfurniture</a></p> <p style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;\">A post shared by <a href=\"https://www.instagram.com/kamorka.workshop/\" style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;\" target=\"_blank\"> Винтажная мебель Masha&amp;Viktor</a> (@kamorka.workshop) on <time style=\" font-family:Arial,sans-serif; font-size:14px; line-height:17px;\" datetime=\"2018-06-26T15:59:03+00:00\">Jun 26, 2018 at 8:59am PDT</time></p></div></blockquote> <script async defer src=\"//www.instagram.com/embed.js\"></script>'),
(202, 35, 'en', '', '', ''),
(203, 35, 'sk', '', '', ''),
(204, 35, 'pl', '', '', ''),
(205, 35, 'hu', '', '', ''),
(206, 35, 'ro', '', '', ''),
(207, 36, 'uk', 'Instagram test 4', 'День Перемоги чи День Пам\'яті? Менеджер культурного напрямку Павло Горб  -  про найвідомішу травневу подію, її місце у світі та суспільстві...', '<blockquote class=\"instagram-media\" data-instgrm-captioned data-instgrm-permalink=\"https://www.instagram.com/p/BjrwOlzF3f7/\" data-instgrm-version=\"8\" style=\" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);\"><div style=\"padding:8px;\"> <div style=\" background:#F8F8F8; line-height:0; margin-top:40px; padding:40.46296296296296% 0; text-align:center; width:100%;\"> <div style=\" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;\"></div></div> <p style=\" margin:8px 0 0 0; padding:0 4px;\"> <a href=\"https://www.instagram.com/p/BjrwOlzF3f7/\" style=\" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;\" target=\"_blank\">Три жёлтых финна! Созданы стать ярким акцентом в интерьере. #желтоекресло #дизайнерскаямебель #шоуруммебели #мастерскаямебели #советскиекресла #midcenturymodern #yellowarmchair #midcenturyforsale #vintagearmchair #meublevintage #переделкамебели #винтажныекресла</a></p> <p style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;\">A post shared by <a href=\"https://www.instagram.com/kamorka.workshop/\" style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;\" target=\"_blank\"> Винтажная мебель Masha&amp;Viktor</a> (@kamorka.workshop) on <time style=\" font-family:Arial,sans-serif; font-size:14px; line-height:17px;\" datetime=\"2018-06-06T12:43:48+00:00\">Jun 6, 2018 at 5:43am PDT</time></p></div></blockquote> <script async defer src=\"//www.instagram.com/embed.js\"></script>'),
(208, 36, 'en', '', '', ''),
(209, 36, 'sk', '', '', ''),
(210, 36, 'pl', '', '', ''),
(211, 36, 'hu', '', '', ''),
(212, 36, 'ro', '', '', ''),
(213, 37, 'uk', 'Instagram test 5', 'Хто вже готує валізи та відслідковує найспекотніші фестивалі цього року? У нашій постійній рубриці «Рекомендації» менеджер культурного напрямку Павло Горб поділиться підбіркою не типових фестивалів 2018, які не можна пропустити!', '<blockquote class=\"instagram-media\" data-instgrm-captioned data-instgrm-permalink=\"https://www.instagram.com/p/BjQBHX0l-T5/\" data-instgrm-version=\"8\" style=\" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);\"><div style=\"padding:8px;\"> <div style=\" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;\"> <div style=\" background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;\"></div></div> <p style=\" margin:8px 0 0 0; padding:0 4px;\"> <a href=\"https://www.instagram.com/p/BjQBHX0l-T5/\" style=\" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;\" target=\"_blank\">Мы редко переделываем мебель в подобном стиле, но иногда нужно расширять горизонты. Этот синий стул с бирюзовыми потёртостями готовится к далёкому путешествию - он отправляется за 4000 километров в красивый голубой дом к @morskaya_pipiska #перекраскастула #ретростул #перекраскамебели #моякраска #bluechair #paintedchair #перекраскастароймебели</a></p> <p style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;\">A post shared by <a href=\"https://www.instagram.com/kamorka.workshop/\" style=\" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;\" target=\"_blank\"> Винтажная мебель Masha&amp;Viktor</a> (@kamorka.workshop) on <time style=\" font-family:Arial,sans-serif; font-size:14px; line-height:17px;\" datetime=\"2018-05-26T18:12:38+00:00\">May 26, 2018 at 11:12am PDT</time></p></div></blockquote> <script async defer src=\"//www.instagram.com/embed.js\"></script>'),
(214, 37, 'en', '', '', ''),
(215, 37, 'sk', '', '', ''),
(216, 37, 'pl', '', '', ''),
(217, 37, 'hu', '', '', ''),
(218, 37, 'ro', '', '', ''),
(219, 47, 'uk', 'Весілля Тетяни та Ігора Пластун', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr...', '<p>-</p>'),
(220, 47, 'en', '', '', ''),
(221, 47, 'sk', '', '', ''),
(222, 47, 'pl', '', '', ''),
(223, 47, 'hu', '', '', ''),
(224, 47, 'ro', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pages_i18n`
--

CREATE TABLE `pages_i18n` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `language` varchar(6) NOT NULL,
  `title` varchar(128) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catalog_items_i18n`
--
ALTER TABLE `catalog_items_i18n`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `easyii_admins`
--
ALTER TABLE `easyii_admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `access_token` (`access_token`);

--
-- Indexes for table `easyii_carousel`
--
ALTER TABLE `easyii_carousel`
  ADD PRIMARY KEY (`carousel_id`);

--
-- Indexes for table `easyii_catalog_categories`
--
ALTER TABLE `easyii_catalog_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `easyii_catalog_items`
--
ALTER TABLE `easyii_catalog_items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `easyii_catalog_item_data`
--
ALTER TABLE `easyii_catalog_item_data`
  ADD PRIMARY KEY (`data_id`),
  ADD KEY `item_id_name` (`item_id`,`name`),
  ADD KEY `value` (`value`(300));

--
-- Indexes for table `easyii_faq`
--
ALTER TABLE `easyii_faq`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `easyii_feedback`
--
ALTER TABLE `easyii_feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `easyii_files`
--
ALTER TABLE `easyii_files`
  ADD PRIMARY KEY (`file_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `easyii_gallery_categories`
--
ALTER TABLE `easyii_gallery_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `easyii_guestbook`
--
ALTER TABLE `easyii_guestbook`
  ADD PRIMARY KEY (`guestbook_id`);

--
-- Indexes for table `easyii_loginform`
--
ALTER TABLE `easyii_loginform`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `easyii_migration`
--
ALTER TABLE `easyii_migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `easyii_modules`
--
ALTER TABLE `easyii_modules`
  ADD PRIMARY KEY (`module_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `easyii_news`
--
ALTER TABLE `easyii_news`
  ADD PRIMARY KEY (`news_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `easyii_pages`
--
ALTER TABLE `easyii_pages`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `easyii_photos`
--
ALTER TABLE `easyii_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `model_item` (`class`,`item_id`);

--
-- Indexes for table `easyii_seotext`
--
ALTER TABLE `easyii_seotext`
  ADD PRIMARY KEY (`seotext_id`),
  ADD UNIQUE KEY `model_item` (`class`,`item_id`);

--
-- Indexes for table `easyii_settings`
--
ALTER TABLE `easyii_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `easyii_shopcart_goods`
--
ALTER TABLE `easyii_shopcart_goods`
  ADD PRIMARY KEY (`good_id`);

--
-- Indexes for table `easyii_shopcart_orders`
--
ALTER TABLE `easyii_shopcart_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `easyii_subscribe_history`
--
ALTER TABLE `easyii_subscribe_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `easyii_subscribe_subscribers`
--
ALTER TABLE `easyii_subscribe_subscribers`
  ADD PRIMARY KEY (`subscriber_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `easyii_tags`
--
ALTER TABLE `easyii_tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `easyii_tags_assign`
--
ALTER TABLE `easyii_tags_assign`
  ADD KEY `class` (`class`),
  ADD KEY `item_tag` (`item_id`,`tag_id`);

--
-- Indexes for table `easyii_texts`
--
ALTER TABLE `easyii_texts`
  ADD PRIMARY KEY (`text_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `items_i18n`
--
ALTER TABLE `items_i18n`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `news_i18n`
--
ALTER TABLE `news_i18n`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages_i18n`
--
ALTER TABLE `pages_i18n`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catalog_items_i18n`
--
ALTER TABLE `catalog_items_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `easyii_admins`
--
ALTER TABLE `easyii_admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `easyii_carousel`
--
ALTER TABLE `easyii_carousel`
  MODIFY `carousel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `easyii_catalog_categories`
--
ALTER TABLE `easyii_catalog_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `easyii_catalog_items`
--
ALTER TABLE `easyii_catalog_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `easyii_catalog_item_data`
--
ALTER TABLE `easyii_catalog_item_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `easyii_faq`
--
ALTER TABLE `easyii_faq`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `easyii_feedback`
--
ALTER TABLE `easyii_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `easyii_files`
--
ALTER TABLE `easyii_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `easyii_gallery_categories`
--
ALTER TABLE `easyii_gallery_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `easyii_guestbook`
--
ALTER TABLE `easyii_guestbook`
  MODIFY `guestbook_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `easyii_loginform`
--
ALTER TABLE `easyii_loginform`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `easyii_modules`
--
ALTER TABLE `easyii_modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `easyii_news`
--
ALTER TABLE `easyii_news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `easyii_pages`
--
ALTER TABLE `easyii_pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `easyii_photos`
--
ALTER TABLE `easyii_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT for table `easyii_seotext`
--
ALTER TABLE `easyii_seotext`
  MODIFY `seotext_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `easyii_settings`
--
ALTER TABLE `easyii_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `easyii_shopcart_goods`
--
ALTER TABLE `easyii_shopcart_goods`
  MODIFY `good_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `easyii_shopcart_orders`
--
ALTER TABLE `easyii_shopcart_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `easyii_subscribe_history`
--
ALTER TABLE `easyii_subscribe_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `easyii_subscribe_subscribers`
--
ALTER TABLE `easyii_subscribe_subscribers`
  MODIFY `subscriber_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `easyii_tags`
--
ALTER TABLE `easyii_tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `easyii_texts`
--
ALTER TABLE `easyii_texts`
  MODIFY `text_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items_i18n`
--
ALTER TABLE `items_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_i18n`
--
ALTER TABLE `news_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT for table `pages_i18n`
--
ALTER TABLE `pages_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
