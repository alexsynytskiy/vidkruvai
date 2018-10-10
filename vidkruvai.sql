-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2018 at 12:45 PM
-- Server version: 5.5.59-MariaDB-1ubuntu0.14.04.1
-- PHP Version: 5.6.37-1+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vidkruvai`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievement`
--

CREATE TABLE `achievement` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `class_name` varchar(512) NOT NULL,
  `required_steps` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `description` varchar(1028) NOT NULL,
  `archived` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `achievement`
--

INSERT INTO `achievement` (`id`, `group_id`, `name`, `class_name`, `required_steps`, `priority`, `description`, `archived`, `created_at`) VALUES
(1, 4, 'Реєстрація в системі', 'RegistrationAchievement', 1, 1, '', 'no', '2018-09-26 10:54:55'),
(5, 6, 'Перший досвід', 'ReachedExperience', 100, 1, '', 'no', '2018-09-26 11:34:03'),
(6, 6, 'Новачок', 'ReachedExperience', 1500, 1, '', 'no', '2018-09-26 11:37:52'),
(7, 6, 'Командний гравець', 'ReachedExperience', 3500, 1, '', 'no', '2018-09-26 11:38:51');

-- --------------------------------------------------------

--
-- Table structure for table `achievement_award`
--

CREATE TABLE `achievement_award` (
  `achievement_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `achievement_award`
--

INSERT INTO `achievement_award` (`achievement_id`, `award_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 1),
(6, 1),
(7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

CREATE TABLE `award` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `type` varchar(256) NOT NULL,
  `value` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archived` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `award`
--

INSERT INTO `award` (`id`, `name`, `description`, `type`, `value`, `created_at`, `archived`) VALUES
(1, 'Досвід 100', 'Нагорода досвіду 100 одиниць', 'experience', 100, '2018-09-26 08:47:35', 'no'),
(2, 'Досвід 150', 'Нагорода досвіду 150 одиниць', 'experience', 150, '2018-09-26 10:41:09', 'no'),
(3, 'Досвід 10', 'Нагорода досвіду 10 одиниць', 'experience', 10, '2018-09-26 10:41:09', 'no');

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
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `type` varchar(256) NOT NULL,
  `slug` varchar(1024) NOT NULL,
  `tree` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `status` varchar(64) NOT NULL,
  `archived` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `type`, `slug`, `tree`, `lft`, `rgt`, `depth`, `status`, `archived`, `created_at`) VALUES
(1, 'Рівні користувача', 'Рівні користувача, досягаються за нарахованим досвідом', 'level', 'rivni-koristuvaca', 1, 1, 4, 0, 'active', 'no', '2018-09-26 09:07:27'),
(2, 'Новачок', 'Базова група рівнів', 'level-group', 'novacok', 1, 2, 3, 1, 'active', 'no', '2018-09-26 10:08:38'),
(3, 'Досягнення', 'Досягнення користувачыв', 'achievement', 'dosagnenna', 3, 1, 8, 0, 'active', 'no', '2018-09-26 10:23:12'),
(4, 'Досягнення профілю', 'Досягнення, повязані з діями з профілем користувача', 'achievement-group', 'dosagnenna-profilu', 3, 6, 7, 1, 'active', 'no', '2018-09-26 10:27:07'),
(5, 'Досягнення рівня', 'Досягнення, повязані з досягненням нового рівня', 'achievement-group', 'dosagnenna-rivna', 3, 4, 5, 1, 'active', 'no', '2018-09-26 10:28:40'),
(6, 'Досягнення досвіду', 'Досягнення, повязані з отриманням досвіду', 'achievement-group', 'dosagnenna-dosvidu', 3, 2, 3, 1, 'active', 'no', '2018-09-26 10:30:22');

-- --------------------------------------------------------

--
-- Table structure for table `category_entity`
--

CREATE TABLE `category_entity` (
  `category_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `state_id`, `city`) VALUES
(1, 2, 'Жмеринка'),
(2, 2, 'Могилів-Подільський'),
(3, 2, 'Хмільник'),
(4, 2, 'Гайсин'),
(5, 2, 'Козятин'),
(6, 2, 'Ладижин'),
(7, 2, 'Калинівка'),
(8, 2, 'Бар'),
(9, 2, 'Тульчин'),
(10, 2, 'Бершадь'),
(11, 2, 'Гнівань'),
(12, 2, 'Немирів'),
(13, 2, 'Іллінці'),
(14, 2, 'Ямпіль'),
(15, 3, 'Ковель'),
(16, 3, 'Нововолинськ'),
(17, 3, 'Володимир-Волинський'),
(18, 3, 'Ківерці'),
(19, 3, 'Рожище'),
(20, 3, 'Камінь-Каширський'),
(21, 3, 'Любомль'),
(22, 4, 'Кам`янське (Дніпродзержинськ)'),
(23, 4, 'Нікополь'),
(24, 4, 'Павлоград'),
(25, 4, 'Новомосковськ'),
(26, 4, 'Марганець'),
(27, 4, 'Жовті Води'),
(28, 4, 'Покров(Орджонікідзе)'),
(29, 4, 'Синельникове'),
(30, 4, 'Першотравенськ'),
(31, 4, 'Тернівка'),
(32, 4, 'Вільногірськ'),
(33, 4, 'Підгородне'),
(34, 4, 'П\'ятихатки'),
(35, 4, 'Верхньодніпровськ'),
(36, 4, 'Апостолове'),
(37, 4, 'Зеленодольськ'),
(38, 4, 'Перещепине'),
(39, 4, 'Верхівцеве'),
(40, 5, 'Краматорськ'),
(41, 5, 'Слов\'янськ'),
(42, 5, 'Бахмут (Артемівськ)'),
(43, 5, 'Костянтинівка'),
(44, 5, 'Покровськ (Красноармійськ)'),
(45, 5, 'Дружківка'),
(46, 5, 'Мирноград (Димитров)'),
(47, 5, 'Авдіївка'),
(48, 5, 'Торецьк'),
(49, 5, 'Добропілля'),
(50, 5, 'Селидове'),
(51, 5, 'Волноваха'),
(52, 5, 'Лиман'),
(53, 5, 'Курахове'),
(54, 5, 'Білозерське'),
(55, 5, 'Красногорівка'),
(56, 5, 'Миколаївка'),
(57, 5, 'Вугледар'),
(58, 5, 'Новогродівка'),
(59, 5, 'Часів Яр'),
(60, 5, 'Світлодарськ'),
(61, 5, 'Сіверськ'),
(62, 5, 'Українськ'),
(63, 5, 'Соледар'),
(64, 5, 'Гірник'),
(65, 5, 'Родинське'),
(66, 5, 'Білицьке'),
(67, 5, 'Мар\'їнка'),
(68, 6, 'Бердичів'),
(69, 6, 'Коростень'),
(70, 6, 'Новоград-Волинський'),
(71, 6, 'Малин'),
(72, 6, 'Коростишів'),
(73, 6, 'Овруч'),
(74, 6, 'Радомишль'),
(75, 6, 'Баранівка'),
(76, 6, 'Олевськ'),
(77, 7, 'Мукачеве'),
(78, 7, 'Хуст'),
(79, 7, 'Виноградів'),
(80, 7, 'Берегове'),
(81, 7, 'Свалява'),
(82, 7, 'Рахів'),
(83, 8, 'Мелітополь'),
(84, 8, 'Бердянськ'),
(85, 8, 'Енергодар'),
(86, 8, 'Токмак'),
(87, 8, 'Пологи'),
(88, 8, 'Дніпрорудне'),
(89, 8, 'Вільнянськ'),
(90, 8, 'Оріхів'),
(91, 8, 'Гуляйполе'),
(92, 8, 'Василівка'),
(93, 8, 'Кам\'янка-Дніпровська'),
(94, 8, 'Приморськ'),
(95, 1, 'Калуш'),
(96, 1, 'Коломия'),
(97, 1, 'Надвірна'),
(98, 1, 'Долина'),
(99, 1, 'Бурштин'),
(100, 1, 'Болехів'),
(101, 1, 'Снятин'),
(102, 10, 'Біла Церква'),
(103, 10, 'Бровари'),
(104, 10, 'Бориспіль'),
(105, 10, 'Фастів'),
(106, 10, 'Ірпінь'),
(107, 10, 'Вишневе'),
(108, 10, 'Васильків'),
(109, 10, 'Боярка'),
(110, 10, 'Обухів'),
(111, 10, 'Буча'),
(112, 10, 'Переяслав-Хмельницький'),
(113, 10, 'Вишгород'),
(114, 10, 'Славутич'),
(115, 10, 'Яготин'),
(116, 10, 'Богуслав'),
(117, 10, 'Сквира'),
(118, 10, 'Березань'),
(119, 10, 'Українка'),
(120, 10, 'Кагарлик'),
(121, 10, 'Тетіїв'),
(122, 10, 'Узин'),
(123, 10, 'Миронівка'),
(124, 10, 'Тараща'),
(125, 9, 'Олександрія'),
(126, 9, 'Світловодськ'),
(127, 9, 'Знам\'янка'),
(128, 9, 'Долинська'),
(129, 9, 'Новоукраїнка'),
(130, 9, 'Гайворон'),
(131, 9, 'Новомиргород'),
(132, 9, 'Мала Виска'),
(133, 9, 'Бобринець'),
(134, 9, 'Помічна'),
(135, 11, 'Сєвєродонецьк'),
(136, 11, 'Лисичанськ'),
(137, 11, 'Рубіжне'),
(138, 11, 'Попасна'),
(139, 11, 'Кремінна'),
(140, 11, 'Сватове'),
(141, 11, 'Старобільськ'),
(142, 11, 'Щастя'),
(143, 12, 'Дрогобич'),
(144, 12, 'Червоноград'),
(145, 12, 'Стрий'),
(146, 12, 'Самбір'),
(147, 12, 'Борислав'),
(148, 12, 'Новояворівськ'),
(149, 12, 'Трускавець'),
(150, 12, 'Новий Розділ'),
(151, 12, 'Золочів'),
(152, 12, 'Броди'),
(153, 12, 'Сокаль'),
(154, 12, 'Стебник'),
(155, 12, 'Городок'),
(156, 12, 'Миколаїв'),
(157, 12, 'Жовква'),
(158, 12, 'Яворів'),
(159, 12, 'Соснівка'),
(160, 12, 'Жидачів'),
(161, 12, 'Кам\'янка-Бузька'),
(162, 12, 'Дубляни'),
(163, 13, 'Первомайськ'),
(164, 13, 'Южноукраїнськ'),
(165, 13, 'Вознесенськ'),
(166, 13, 'Новий Буг'),
(167, 13, 'Очаків'),
(168, 13, 'Снігурівка'),
(169, 13, 'Баштанка'),
(170, 13, 'Нова Одеса'),
(171, 14, 'Ізмаїл'),
(172, 14, 'Чорноморськ'),
(173, 14, 'Білгород-Дністровський'),
(174, 14, 'Котовськ'),
(175, 14, 'Южне'),
(176, 14, 'Кілія'),
(177, 14, 'Рені'),
(178, 14, 'Балта'),
(179, 14, 'Роздільна'),
(180, 14, 'Болград'),
(181, 14, 'Арциз'),
(182, 14, 'Біляївка'),
(183, 14, 'Татарбунари'),
(184, 14, 'Теплодар'),
(185, 15, 'Кременчук'),
(186, 15, 'Горішні Плавні(Комсомольськ)'),
(187, 15, 'Лубни'),
(188, 15, 'Миргород'),
(189, 15, 'Гадяч'),
(190, 15, 'Пирятин'),
(191, 15, 'Карлівка'),
(192, 15, 'Хорол'),
(193, 15, 'Лохвиця'),
(194, 15, 'Гребінка'),
(195, 15, 'Кобеляки'),
(196, 15, 'Зіньків'),
(197, 16, 'Вараш (Кузнецовськ)'),
(198, 16, 'Дубно'),
(199, 16, 'Костопіль'),
(200, 16, 'Сарни'),
(201, 16, 'Здолбунів'),
(202, 16, 'Острог'),
(203, 16, 'Березне'),
(204, 16, 'Радивилів'),
(205, 17, 'Конотоп'),
(206, 17, 'Шостка'),
(207, 17, 'Охтирка'),
(208, 17, 'Ромни'),
(209, 17, 'Глухів'),
(210, 17, 'Лебедин'),
(211, 17, 'Кролевець'),
(212, 17, 'Тростянець'),
(213, 17, 'Білопілля'),
(214, 17, 'Путивль'),
(215, 18, 'Чортків'),
(216, 18, 'Кременець'),
(217, 18, 'Бережани'),
(218, 18, 'Збараж'),
(219, 18, 'Теребовля'),
(220, 18, 'Бучач'),
(221, 18, 'Борщів'),
(222, 19, 'Лозова'),
(223, 19, 'Ізюм'),
(224, 19, 'Чугуїв'),
(225, 19, 'Первомайський'),
(226, 19, 'Балаклія'),
(227, 19, 'Куп\'янськ'),
(228, 19, 'Мерефа'),
(229, 19, 'Люботин'),
(230, 19, 'Красноград'),
(231, 19, 'Вовчанськ'),
(232, 19, 'Дергачі'),
(233, 19, 'Богодухів'),
(234, 19, 'Зміїв'),
(235, 20, 'Нова Каховка'),
(236, 20, 'Каховка'),
(237, 20, 'Олешки (Цюрупинськ)'),
(238, 20, 'Генічеськ'),
(239, 20, 'Скадовськ'),
(240, 20, 'Гола Пристань'),
(241, 20, 'Берислав'),
(242, 20, 'Таврійськ'),
(243, 21, 'Кам\'янець-Подільський'),
(244, 21, 'Шепетівка'),
(245, 21, 'Нетішин'),
(246, 21, 'Славута'),
(247, 21, 'Старокостянтинів'),
(248, 21, 'Полонне'),
(249, 21, 'Красилів'),
(250, 21, 'Волочиськ'),
(251, 21, 'Ізяслав'),
(252, 21, 'Городок'),
(253, 21, 'Дунаївці'),
(254, 21, 'Деражня'),
(255, 22, 'Умань'),
(256, 22, 'Сміла'),
(257, 22, 'Золотоноша'),
(258, 22, 'Канів'),
(259, 22, 'Корсунь-Шевченківський'),
(260, 22, 'Звенигородка'),
(261, 22, 'Шпола'),
(262, 22, 'Ватутіне'),
(263, 22, 'Городище'),
(264, 22, 'Тальне'),
(265, 22, 'Жашків'),
(266, 22, 'Кам\'янка'),
(267, 22, 'Христинівка'),
(268, 24, 'Ніжин'),
(269, 24, 'Прилуки'),
(270, 24, 'Бахмач'),
(271, 24, 'Носівка'),
(272, 24, 'Новгород-Сіверський'),
(273, 24, 'Корюківка'),
(274, 24, 'Городня'),
(275, 24, 'Мена'),
(276, 24, 'Сновськ'),
(277, 24, 'Ічня'),
(278, 24, 'Бобровиця'),
(279, 24, 'Борзна'),
(280, 23, 'Сторожинець'),
(281, 23, 'Заставна'),
(282, 23, 'Новодністровськ'),
(283, 23, 'Сокиряни'),
(284, 23, 'Хотин');

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
  `client_type` varchar(128) NOT NULL,
  `order_type` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
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
(9, 'news', 'yii\\easyii\\modules\\news\\NewsModule', 'Новини', 'bullhorn', '{\"enableThumb\":true,\"enablePhotos\":true,\"enableShort\":true,\"shortMaxLength\":256,\"enableTags\":true}', 0, 90, 1),
(10, 'page', 'yii\\easyii\\modules\\page\\PageModule', 'Страницы', 'file', '[]', 0, 45, 1),
(11, 'shopcart', 'yii\\easyii\\modules\\shopcart\\ShopcartModule', 'Заказы', 'shopping-cart', '{\"mailAdminOnNewOrder\":true,\"subjectOnNewOrder\":\"New order\",\"templateOnNewOrder\":\"@easyii\\/modules\\/shopcart\\/mail\\/en\\/new_order\",\"subjectNotifyUser\":\"Your order status changed\",\"templateNotifyUser\":\"@easyii\\/modules\\/shopcart\\/mail\\/en\\/notify_user\",\"frontendShopcartRoute\":\"\\/shopcart\\/order\",\"enablePhone\":true,\"enableEmail\":true}', 0, 120, 0),
(12, 'subscribe', 'yii\\easyii\\modules\\subscribe\\SubscribeModule', 'E-mail рассылка', 'envelope', '[]', 0, 50, 0),
(13, 'text', 'yii\\easyii\\modules\\text\\TextModule', 'Текстовые блоки', 'font', '[]', 0, 10, 1),
(17, 'siteusers', 'yii\\easyii\\modules\\siteusers\\SiteUsersModule', 'Пользователи', 'user', '[]', 0, 110, 1);

-- --------------------------------------------------------

--
-- Table structure for table `easyii_news`
--

CREATE TABLE `easyii_news` (
  `news_id` int(11) NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `slug` varchar(128) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `on_main` int(1) NOT NULL DEFAULT '0',
  `time` int(11) DEFAULT '0',
  `views` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
(3, 'password_salt', 'Password salt', '', 0),
(4, 'root_auth_key', 'Root authorization key', '-2RMM', 0),
(5, 'root_password', 'Пароль разработчика', '', 1),
(6, 'auth_time', 'Время авторизации', '86400', 1),
(7, 'robot_email', 'E-mail рассыльщика', 'info@vidkruvai.com.ua', 1),
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

-- --------------------------------------------------------

--
-- Table structure for table `easyii_tags`
--

CREATE TABLE `easyii_tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `frequency` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `easyii_tags_assign`
--

CREATE TABLE `easyii_tags_assign` (
  `class` varchar(128) NOT NULL,
  `item_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `easyii_texts`
--

CREATE TABLE `easyii_texts` (
  `text_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `slug` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `easyii_texts`
--

INSERT INTO `easyii_texts` (`text_id`, `text`, `slug`) VALUES
(1, 'Всі права захищені 2018', 'rights-reserved'),
(2, '+38 0', 'phone-number'),
(3, 'https://www.facebook.com/', 'facebook-link'),
(4, 'https://www.instagram.com/', 'instagram-link');

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
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `required_experience` int(11) NOT NULL,
  `base_level` int(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archived` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id`, `group_id`, `num`, `required_experience`, `base_level`, `created_at`, `archived`) VALUES
(1, 2, 1, 0, 1, '2018-09-26 10:19:00', 'no'),
(2, 2, 2, 100, 0, '2018-09-27 09:16:56', 'no'),
(3, 2, 3, 300, 0, '2018-09-27 09:17:15', 'no'),
(4, 2, 4, 500, 0, '2018-09-27 09:23:02', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `level_award`
--

CREATE TABLE `level_award` (
  `level_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `level_award`
--

INSERT INTO `level_award` (`level_id`, `award_id`) VALUES
(1, 1),
(2, 3),
(3, 3),
(4, 3);

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
('m000000_000000_base', 1497368558);

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

-- --------------------------------------------------------

--
-- Table structure for table `news_user_notification`
--

CREATE TABLE `news_user_notification` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `site_user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_user_notification`
--

INSERT INTO `news_user_notification` (`id`, `news_id`, `site_user_id`, `created_at`, `updated_at`) VALUES
(6, 68, 5, '2018-10-08 15:41:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `category` varchar(256) NOT NULL,
  `title` varchar(512) NOT NULL,
  `message` varchar(2056) NOT NULL,
  `target_link` varchar(512) DEFAULT NULL,
  `type` varchar(128) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification_user`
--

CREATE TABLE `notification_user` (
  `id` int(11) NOT NULL,
  `n_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE `school` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `type_id` int(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`id`, `city_id`, `type_id`, `number`, `name`) VALUES
(1, 7, 1, '35', ''),
(2, 0, 3, '1', ''),
(3, 0, 5, '2', ''),
(4, 0, 2, '1', ''),
(5, 0, 2, '3', ''),
(6, 0, 2, '4', ''),
(7, 1, 2, '4', ''),
(8, 0, 2, '2', ''),
(9, 1, 3, '2', ''),
(10, 7, 1, '1', ''),
(11, 1, 1, '3', 'YGC'),
(12, 11, 2, '3', ''),
(13, 1, 1, '2', ''),
(14, 0, 3, '3', ''),
(15, 0, 3, '11', ''),
(16, 0, 1, '18', ''),
(17, 5, 1, '4', ''),
(18, 4, 6, '7', ''),
(19, 4, 1, '4', ''),
(20, 4, 1, '2', ''),
(21, 2, 1, '3', ''),
(22, 0, 1, '4', ''),
(24, 3, 6, '2', ''),
(25, 3, 6, '24', ''),
(26, 0, 3, '19', ''),
(30, 0, 6, '19', ''),
(31, 0, 6, '30', ''),
(32, 8, 1, '5', ''),
(34, 4, 1, '3', ''),
(35, 3, 1, '6', ''),
(36, 3, 1, '35', ''),
(37, 1, 2, '12', ''),
(38, 2, 2, '10', ''),
(39, 0, 1, '2', ''),
(40, 4, 1, '35', ''),
(41, 1, 1, '37', ''),
(42, 4, 1, '10', ''),
(43, 2, 1, '1', ''),
(44, 6, 1, '10', ''),
(45, 2, 1, '43', ''),
(47, 2, 1, '10', ''),
(48, 6, 1, '2', ''),
(50, 0, 6, '3', ''),
(52, 2, 3, '1', ''),
(54, 2, 6, '1', ''),
(55, 5, 1, '35', ''),
(56, 0, 1, '25', ''),
(58, 0, 1, '17', ''),
(59, 2, 1, '47', ''),
(60, 2, 1, '145', ''),
(61, 1, 6, '2', ''),
(63, 3, 1, '2', ''),
(64, 0, 1, '5', ''),
(65, 4, 3, '35', ''),
(66, 0, 1, '32', ''),
(67, 2, 6, '2', ''),
(68, 3, 6, '38', ''),
(71, 0, 1, '44', ''),
(72, 0, 1, '35', ''),
(73, 3, 1, '5', ''),
(75, 0, 6, '2', ''),
(76, 3, 1, '15', ''),
(77, 1, 1, '32', ''),
(78, 11, 1, '2', ''),
(80, 16, 1, '2', ''),
(81, 10, 1, '6', ''),
(83, 14, 1, '16', ''),
(84, 1, 3, '83', ''),
(85, 8, 1, '2', ''),
(86, 0, 2, '52', ''),
(87, 0, 3, '26', ''),
(88, 0, 3, '5', ''),
(89, 2, 1, '32', ''),
(90, 5, 1, '6', ''),
(92, 3, 1, '32', ''),
(93, 4, 3, '32', ''),
(95, 5, 2, '3', ''),
(96, 5, 3, '2', ''),
(100, 2, 1, '5', ''),
(101, 4, 3, '2', ''),
(102, 22, 1, '3', ''),
(103, 13, 1, '64', ''),
(104, 0, 1, '37', ''),
(106, 0, 1, '58', ''),
(107, 2, 1, '15', ''),
(108, 14, 1, '5', ''),
(109, 0, 1, '8', ''),
(110, 11, 3, '125', ''),
(111, 0, 1, '109', ''),
(112, 2, 1, '18', ''),
(113, 4, 1, '14', ''),
(115, 1, 6, '14', ''),
(120, 4, 1, '113', ''),
(121, 4, 1, '32', ''),
(122, 11, 3, '12', ''),
(123, 6, 3, '6', ''),
(125, 2, 3, '56', ''),
(126, 4, 1, '56', ''),
(127, 4, 1, '60', ''),
(128, 0, 6, '26', ''),
(130, 4, 6, '2', ''),
(131, 0, 6, '9', ''),
(132, 15, 1, '3', ''),
(134, 11, 1, '327', ''),
(135, 17, 1, '3', ''),
(136, 7, 1, '5', ''),
(139, 10, 1, '2', ''),
(142, 1, 1, '38', ''),
(144, 0, 6, '131', ''),
(145, 6, 1, '32', ''),
(146, 6, 3, '5', ''),
(148, 24, 1, '16', ''),
(151, 9, 1, '5', ''),
(153, 8, 6, '32', ''),
(154, 0, 1, '16', ''),
(155, 6, 2, '3', ''),
(156, 4, 1, '109', ''),
(157, 5, 1, '5', ''),
(158, 0, 2, '15', ''),
(159, 17, 3, '2', ''),
(160, 3, 1, '38', ''),
(161, 17, 6, '2', ''),
(162, 2, 1, '2', ''),
(163, 5, 1, '109', ''),
(164, 3, 6, '35', ''),
(165, 2, 6, '0', ''),
(174, 5, 6, '165', ''),
(175, 2, 6, '165', ''),
(176, 5, 3, '165', ''),
(177, 5, 6, '2', ''),
(179, 10, 1, '3', ''),
(180, 5, 6, '18', ''),
(182, 17, 6, '165', ''),
(183, 15, 2, '2', ''),
(187, 2, 6, '6', ''),
(188, 0, 6, '32', ''),
(189, 5, 1, '165', ''),
(190, 2, 1, '6', ''),
(191, 9, 1, '2', ''),
(193, 11, 1, '165', ''),
(194, 0, 1, '165', ''),
(197, 0, 2, '165', ''),
(198, 2, 6, '32', ''),
(199, 7, 1, '32', ''),
(200, 0, 2, '0', ''),
(202, 0, 1, '31', ''),
(203, 0, 3, '83', ''),
(204, 11, 1, '18', ''),
(205, 2, 1, '38', ''),
(206, 0, 1, '76', ''),
(207, 1, 6, '109', ''),
(208, 6, 6, '3', ''),
(209, 1, 1, '15', ''),
(210, 5, 1, '2', ''),
(211, 7, 6, '2', ''),
(212, 5, 2, '165', ''),
(213, 7, 6, '165', ''),
(214, 2, 1, '35', ''),
(215, 2, 1, '109', ''),
(216, 7, 1, '6', ''),
(217, 1, 1, '109', ''),
(218, 3, 3, '2', ''),
(219, 0, 1, '3', ''),
(220, 1, 6, '165', ''),
(221, 258, 1, '2', ''),
(222, 284, 6, '6', ''),
(224, 1, 3, '5', ''),
(225, 16, 3, '2', ''),
(226, 134, 1, '5', ''),
(229, 228, 1, '16', ''),
(230, 224, 1, '35', ''),
(231, 283, 1, '6', ''),
(234, 232, 3, '26', ''),
(235, 206, 6, '5', ''),
(236, 224, 6, '32', ''),
(237, 42, 1, '38', ''),
(239, 40, 1, '131', ''),
(240, 217, 6, '165', ''),
(241, 208, 1, '32', ''),
(243, 125, 2, '15', ''),
(244, 17, 2, '35', ''),
(245, 157, 1, '5', ''),
(246, 129, 1, '109', ''),
(247, 106, 1, '113', ''),
(249, 256, 3, '5', ''),
(250, 199, 1, '32', ''),
(251, 19, 1, '5', ''),
(252, 160, 1, '3', ''),
(253, 83, 1, '25', ''),
(255, 16, 6, '165', ''),
(256, 246, 1, '5', ''),
(257, 131, 6, '2', ''),
(258, 258, 1, '5', ''),
(259, 131, 2, '2', ''),
(260, 14, 1, '2', ''),
(261, 62, 1, '2', ''),
(262, 86, 1, '35', ''),
(263, 16, 1, '5', 'Fortalecía'),
(265, 21, 1, '5', ''),
(266, 74, 2, '2', ''),
(267, 140, 1, '109', ''),
(268, 135, 1, '83', ''),
(269, 132, 1, '6', ''),
(270, 140, 6, '165', ''),
(271, 70, 3, '15', ''),
(272, 40, 6, '2', 'тестова'),
(273, 21, 1, '3', ''),
(274, 116, 1, '2', ''),
(275, 166, 1, '2', ''),
(277, 26, 1, '5', ''),
(278, 17, 1, '15', ''),
(279, 16, 6, '109', ''),
(280, 224, 1, '165', ''),
(281, 235, 3, '83', ''),
(282, 269, 6, '32', ''),
(283, 70, 1, '15', 'Агенти Змін'),
(284, 246, 1, '18', ''),
(285, 1, 6, '32', ''),
(286, 257, 1, '2', 'GOLDEN BRAINS ZOLO'),
(287, 243, 1, '3', ''),
(288, 149, 1, '5', ''),
(289, 21, 6, '2', ''),
(291, 103, 1, '5', ''),
(293, 228, 1, '2', ''),
(294, 165, 1, '38', ''),
(295, 33, 1, '2', ''),
(296, 144, 1, '109', ''),
(297, 1, 1, '35', ''),
(299, 190, 2, '3', ''),
(300, 190, 1, '6', 'Global'),
(301, 190, 2, '2', 'Ювентус'),
(302, 131, 1, '3', ''),
(303, 257, 6, '6', ''),
(304, 131, 6, '165', ''),
(305, 243, 1, '165', ''),
(306, 47, 1, '32', ''),
(307, 119, 6, '3', ''),
(308, 230, 3, '5', ''),
(309, 140, 1, '2', ''),
(310, 74, 6, '3', ''),
(311, 17, 1, '109', ''),
(312, 190, 1, '165', ''),
(313, 284, 1, '32', ''),
(314, 151, 1, '3', ''),
(315, 282, 1, '3', ''),
(316, 282, 1, '2', ''),
(317, 243, 3, '109', ''),
(318, 244, 1, '2', ''),
(319, 214, 6, '165', ''),
(320, 14, 6, '18', ''),
(321, 140, 6, '18', ''),
(322, 265, 1, '32', ''),
(323, 74, 1, '6', ''),
(324, 25, 1, '6', ''),
(325, 116, 1, '3', 'TOGETHER'),
(326, 16, 1, '109', ''),
(327, 89, 1, '5', ''),
(328, 17, 6, '6', ''),
(329, 37, 2, '165', ''),
(330, 149, 1, '2', ''),
(332, 224, 1, '131', ''),
(333, 209, 1, '35', ''),
(334, 106, 1, '14', 'Максимум'),
(335, 16, 1, '3', ''),
(336, 257, 1, '3', 'Школа № 3 Золотоноша'),
(337, 190, 1, '35', ''),
(338, 153, 1, '6', ''),
(339, 246, 6, '3', ''),
(340, 84, 6, '2', ''),
(341, 130, 1, '5', ''),
(342, 16, 6, '8', ''),
(343, 258, 6, '3', ''),
(344, 258, 6, '2', 'Канівська гімназія '),
(346, 190, 1, '4', ''),
(347, 74, 1, '4', ''),
(348, 97, 1, '2', ''),
(349, 1, 1, '5', ''),
(350, 257, 1, '6', 'Патріоти'),
(351, 51, 1, '21', ''),
(352, 131, 1, '2', ''),
(353, 40, 1, '3', ''),
(354, 130, 1, '1', 'Перлини Прибужжя'),
(355, 230, 3, '3', 'Вірні друзі'),
(356, 84, 6, '1', 'Ray of hope'),
(357, 166, 1, '1', 'Пульс'),
(358, 258, 1, '1', 'БАЛАНС'),
(359, 135, 1, '5', ''),
(360, 151, 1, '2', 'Carpe diem'),
(361, 116, 1, '1', 'Богуславська СШ №1'),
(363, 284, 6, '4', 'Intermezzo'),
(364, 238, 1, '100', ''),
(365, 42, 1, '10', ''),
(366, 208, 1, '4', ''),
(367, 160, 1, '2', 'Генератори ідей'),
(368, 134, 1, '3', ''),
(369, 19, 1, '3', ''),
(370, 224, 1, '2', ''),
(371, 40, 1, '9', 'Еко-9'),
(372, 16, 6, '1', ''),
(373, 140, 1, '1', ''),
(374, 199, 1, '5', ''),
(375, 282, 6, '1', ''),
(376, 246, 1, '1', ''),
(377, 24, 1, '11', ''),
(378, 137, 1, '8', ''),
(379, 256, 1, '11', ''),
(380, 258, 1, '3', ''),
(381, 16, 1, '8', ''),
(382, 217, 6, '0', ''),
(383, 206, 6, '3', ''),
(384, 190, 2, '0', ''),
(385, 282, 1, '1', 'Гарячі серця'),
(386, 83, 6, '9', ''),
(388, 75, 1, '2', ''),
(389, 207, 1, '1', ''),
(391, 232, 1, '5', ''),
(392, 17, 1, '2', ''),
(393, 21, 1, '2', ''),
(394, 218, 1, '3', '3баражКом'),
(395, 129, 1, '8', ''),
(398, 261, 3, '3', ''),
(414, 209, 1, '3', ''),
(415, 214, 6, '0', 'Фенікс'),
(416, 86, 3, '11', ''),
(417, 130, 3, '5', 'Майбутнє країни'),
(418, 74, 2, '1', 'ліцей №1 ім.Шевченка'),
(419, 58, 1, '10', 'ЗОШ №10'),
(427, 215, 1, '3', ''),
(428, 89, 1, '3', ''),
(429, 247, 1, '16', ''),
(430, 231, 6, '1', 'Флора Плюс'),
(431, 119, 1, '8', ''),
(440, 226, 1, '1', 'Ліга перших'),
(443, 119, 1, '2', ''),
(446, 265, 1, '4', 'Royal Academy'),
(447, 168, 6, '2', ''),
(450, 106, 1, '2', ''),
(460, 140, 6, '0', ''),
(462, 265, 1, '1', 'Переможці'),
(463, 180, 1, '8', ''),
(464, 214, 1, '1', ''),
(466, 139, 1, '2', ''),
(469, 51, 1, '6', 'Команда ФЕНІКС '),
(473, 132, 1, '3', 'Маловисківська третя'),
(474, 130, 6, '5', ''),
(475, 140, 1, '6', ''),
(476, 234, 2, '1', ''),
(477, 246, 1, '4', ''),
(478, 214, 1, '2', 'Легіон'),
(479, 256, 1, '5', '» Міцні Бобри «'),
(480, 171, 1, '2', ''),
(481, 48, 6, '1', 'Гімназисти'),
(482, 210, 1, '7', ''),
(483, 283, 3, '16', ''),
(484, 237, 6, '1', 'Отамани майбутнього'),
(485, 207, 1, '3', ''),
(486, 102, 1, '16', ''),
(487, 74, 1, '3', ''),
(489, 207, 1, '4', 'Команда \"Вишеньки\"'),
(490, 48, 1, '10', ''),
(491, 25, 3, '1', ''),
(492, 231, 2, '2', 'Вовчанський ліцей №2'),
(493, 276, 6, '3', 'Гімназія №3 \"Еліт\"'),
(494, 48, 3, '1', ''),
(496, 21, 6, '1', ''),
(497, 131, 1, '1', 'Непереможні'),
(498, 231, 1, '3', ''),
(499, 224, 6, '5', 'ЕДОП'),
(500, 40, 1, '24', ''),
(501, 40, 1, '15', ''),
(502, 130, 1, '3', 'Без обмежень'),
(503, 228, 1, '7', ''),
(504, 243, 1, '5', ''),
(506, 83, 3, '16', 'ПЕРСПЕКТИВА'),
(507, 199, 1, '6', ''),
(508, 144, 1, '8', ''),
(509, 263, 1, '3', ''),
(510, 165, 1, '10', '\"ПІК\" '),
(511, 132, 6, '1', 'Shake'),
(512, 131, 6, '1', ''),
(513, 135, 1, '8', ''),
(514, 197, 6, '1', ''),
(515, 214, 6, '3', ''),
(516, 135, 1, '18', 'Юные Изобретатели'),
(517, 51, 1, '7', ''),
(518, 17, 6, '4', 'Сhanger\'и'),
(519, 62, 1, '3', ''),
(520, 22, 1, '42', ''),
(521, 37, 2, '1', ''),
(522, 54, 1, '18', ''),
(523, 77, 3, '5', ''),
(524, 165, 1, '5', ''),
(525, 37, 1, '2', 'Джентльмены удачи'),
(526, 258, 1, '4', 'ВУ | Вектор Успіху'),
(527, 6, 1, '3', 'Школа №3'),
(528, 106, 1, '3', ''),
(529, 64, 2, '144', ''),
(530, 217, 1, '1', ''),
(531, 132, 1, '4', 'Immortal Fame'),
(532, 157, 1, '3', 'Жовківська ЗОШ №3'),
(533, 97, 1, '1', ''),
(534, 209, 1, '6', ''),
(535, 246, 1, '3', ''),
(536, 130, 1, '2', ''),
(537, 230, 1, '3', ''),
(538, 70, 6, '11', ''),
(539, 1, 2, '2', ''),
(540, 77, 1, '13', ''),
(541, 160, 6, '1', 'Ambo meliores'),
(542, 257, 6, '4', 'GTA'),
(543, 235, 1, '3', '\"НК life\"'),
(544, 210, 1, '3', ''),
(545, 230, 6, '5', 'Сіріус'),
(546, 83, 1, '23', ''),
(547, 199, 1, '3', ''),
(548, 17, 1, '5', ''),
(549, 183, 3, '2', ''),
(550, 223, 1, '12', ''),
(551, 86, 1, '1', ''),
(552, 256, 6, '14', ''),
(553, 26, 1, '3', 'Марганецька ЗОШ №3'),
(554, 187, 1, '7', '\"Васильки\"'),
(555, 135, 1, '16', ''),
(556, 261, 6, '3', 'NAVI'),
(557, 226, 1, '3', 'МАКСИМУМ'),
(558, 230, 3, '2', 'Творці майбутнього'),
(559, 165, 6, '1', '\"ТРІУМФ\"'),
(560, 234, 1, '2', ''),
(561, 25, 1, '8', ''),
(562, 169, 1, '2', 'AIR '),
(563, 256, 3, '14', ''),
(564, 215, 6, '1', '\"Імпульс\"'),
(565, 102, 1, '21', ''),
(566, 156, 1, '1', ''),
(567, 243, 3, '13', ''),
(568, 255, 1, '3', ''),
(569, 129, 1, '6', ''),
(570, 149, 1, '3', 'Трускавецькі Бескиди'),
(571, 220, 1, '3', ''),
(572, 169, 6, '0', ''),
(573, 86, 1, '2', ''),
(574, 273, 1, '4', 'Рада ООН '),
(575, 261, 3, '2', ''),
(576, 257, 1, '1', ''),
(577, 207, 1, '11', ''),
(578, 19, 3, '4', ''),
(579, 228, 1, '6', ''),
(580, 227, 1, '6', ''),
(581, 42, 1, '5', 'Бахмутская ОШ №5'),
(582, 273, 6, '0', 'ЛІБЕРТІ'),
(583, 83, 1, '11', ''),
(584, 261, 2, '2', ''),
(585, 125, 2, '11', ''),
(586, 22, 1, '12', 'СЗШ №12'),
(587, 26, 2, '3', ''),
(588, 228, 2, '2', 'Медичний ліцей'),
(589, 224, 1, '7', ''),
(590, 241, 1, '3', 'Серце доброти'),
(591, 45, 1, '7', 'Дружківські патріоти'),
(592, 129, 1, '1', '\"Апельсин\"'),
(593, 40, 1, '16', 'БЕЗ МЕЖ'),
(594, 160, 3, '1', ''),
(595, 16, 1, '4', ''),
(596, 200, 6, '5', 'Сарненська Гімназія'),
(597, 207, 6, '1', ''),
(598, 70, 1, '10', ''),
(599, 227, 1, '1', 'Куп\'янська ЗОШ №1'),
(600, 243, 2, '3', ''),
(601, 255, 3, '7', 'Колегіумісти '),
(602, 188, 6, '6', ''),
(603, 47, 1, '6', ''),
(604, 194, 1, '2', ''),
(605, 235, 1, '1', ''),
(606, 243, 3, '14', ''),
(607, 182, 1, '1', 'Made in Ukraine'),
(608, 255, 1, '5', ''),
(609, 84, 1, '11', 'SMASH'),
(610, 225, 1, '5', ''),
(611, 223, 1, '10', ''),
(612, 243, 3, '9', ''),
(613, 243, 6, '14', ''),
(614, 18, 6, '2', ''),
(615, 84, 1, '16', 'Пінгвіни'),
(616, 217, 1, '3', ''),
(617, 70, 1, '9', 'КДУ'),
(618, 47, 1, '7', 'Прометеї'),
(619, 20, 1, '1', ''),
(620, 84, 2, '1', ''),
(621, 216, 3, '5', ''),
(622, 83, 3, '100', ''),
(623, 216, 1, '1', ''),
(624, 139, 1, '1', ''),
(625, 19, 6, '4', ''),
(626, 165, 1, '3', ''),
(627, 22, 1, '31', ''),
(628, 246, 1, '7', ''),
(629, 86, 1, '6', 'Майбутнє'),
(630, 26, 1, '2', ''),
(631, 244, 2, '1', ''),
(632, 227, 1, '11', '“Обрані зорями”'),
(633, 40, 1, '2', ''),
(634, 210, 1, '6', ''),
(635, 244, 3, '1', '220V'),
(636, 282, 6, '3', 'Teen heroes'),
(637, 70, 1, '5', ''),
(638, 197, 1, '3', 'ДЕМОС'),
(639, 238, 3, '2', ''),
(640, 227, 1, '12', ''),
(641, 237, 1, '4', '\"OK\"'),
(642, 244, 3, '2', ''),
(643, 222, 1, '3', 'Світоч'),
(644, 230, 2, '4', 'ТЕЛЕСИКИ'),
(645, 238, 1, '3', ''),
(646, 51, 1, '1', ''),
(647, 135, 6, '9', 'Гімназія|Eco Power'),
(648, 223, 1, '2', ''),
(649, 243, 1, '16', ''),
(650, 139, 6, '5', ''),
(651, 30, 1, '3', 'Voice Generation'),
(652, 261, 1, '5', ''),
(653, 238, 3, '3', ''),
(654, 62, 1, '12', ''),
(655, 243, 1, '15', ''),
(656, 256, 1, '1', 'Мегаполіс ідей.'),
(657, 195, 3, '1', ''),
(658, 224, 1, '8', ''),
(659, 259, 3, '0', ''),
(660, 75, 6, '1', ''),
(661, 216, 1, '5', ''),
(662, 83, 6, '5', ''),
(663, 225, 1, '2', ''),
(664, 197, 1, '1', 'Дивосвіт ЗОШ №1'),
(665, 256, 1, '12', ''),
(666, 217, 1, '2', 'Алмаз'),
(667, 65, 1, '8', ''),
(668, 47, 1, '2', 'Яструби міста'),
(669, 163, 1, '5', ''),
(670, 237, 1, '3', ''),
(671, 133, 3, '1', ''),
(672, 250, 1, '5', ''),
(674, 84, 1, '2', ''),
(675, 133, 1, '1', ''),
(676, 227, 6, '1', 'Червоні вітрила'),
(677, 19, 1, '1', ''),
(678, 263, 2, '1', 'Економічний ліцей №1'),
(679, 223, 1, '6', ''),
(680, 110, 1, '4', ''),
(681, 70, 1, '6', ''),
(682, 70, 1, '3', ''),
(683, 129, 3, '7', ''),
(684, 231, 1, '7', ''),
(685, 16, 1, '6', ''),
(686, 8, 3, '14', ''),
(687, 74, 6, '2', ''),
(688, 279, 1, '1', ''),
(689, 110, 1, '2', ''),
(690, 215, 1, '6', ''),
(691, 19, 1, '2', ''),
(692, 226, 1, '2', 'Eco-LiFeSTYLE'),
(693, 225, 1, '6', ''),
(694, 133, 6, '1', ''),
(695, 133, 1, '5', ''),
(696, 134, 1, '1', ''),
(697, 86, 1, '5', ''),
(698, 33, 1, '3', ''),
(699, 245, 1, '1', 'ЗОШ №1'),
(700, 243, 1, '10', ''),
(701, 149, 1, '1', 'Фенікс'),
(702, 246, 6, '2', 'Крила надії'),
(703, 243, 1, '7', ''),
(704, 27, 3, '6', ''),
(705, 51, 1, '2', ''),
(706, 51, 1, '3', ''),
(707, 160, 6, '0', ''),
(708, 270, 6, '1', ''),
(709, 27, 1, '8', ''),
(710, 238, 6, '2', ''),
(711, 136, 2, '1', 'Санта-Марія'),
(712, 243, 1, '17', 'ЗОШ №17'),
(713, 37, 1, '1', ''),
(714, 97, 1, '18', ''),
(715, 270, 1, '5', 'Бахмацька ЗОШ №5'),
(716, 110, 3, '5', ''),
(717, 197, 1, '2', 'Школа №2'),
(718, 230, 3, '6', ''),
(719, 183, 1, '1', ''),
(720, 197, 1, '4', 'light'),
(721, 238, 3, '0', ''),
(722, 227, 3, '2', ''),
(723, 132, 6, '4', ''),
(724, 205, 6, '1', 'Конотопська гімназія'),
(725, 83, 1, '4', ''),
(726, 222, 6, '1', ''),
(727, 207, 1, '5', 'Школа№5ім.Р.К.Рапія'),
(728, 222, 3, '10', 'Riders on the Storm'),
(729, 223, 6, '1', ''),
(730, 223, 1, '4', ''),
(731, 244, 1, '4', ''),
(732, 207, 1, '6', ''),
(733, 84, 1, '4', ''),
(734, 223, 1, '5', ''),
(735, 223, 6, '3', ''),
(737, 110, 6, '1', ''),
(738, 263, 1, '2', ''),
(739, 222, 1, '1', ''),
(740, 175, 3, '4', 'Атрамент'),
(741, 188, 1, '7', ''),
(742, 234, 1, '1', ''),
(743, 119, 1, '7', ''),
(745, 110, 1, '3', ''),
(746, 86, 3, '12', ''),
(748, 84, 6, '3', ''),
(749, 42, 1, '12', ''),
(750, 86, 1, '4', ''),
(751, 225, 1, '7', 'GREEN TEAM'),
(752, 106, 3, '6', 'Modern Generation '),
(753, 239, 1, '2', ''),
(754, 191, 6, '0', ''),
(755, 272, 6, '3', ''),
(756, 110, 1, '8', ''),
(757, 27, 3, '11', ''),
(758, 272, 6, '100', ''),
(759, 219, 1, '2', 'Future Team'),
(760, 255, 3, '2', 'B.R.A.I.N.S'),
(761, 259, 6, '4', ''),
(762, 244, 1, '6', ''),
(763, 244, 1, '3', ''),
(764, 108, 1, '9', ''),
(765, 243, 1, '6', ''),
(766, 58, 1, '7', 'Школа №7 Діти Нації'),
(767, 245, 3, '3', ''),
(768, 219, 1, '1', ''),
(769, 37, 1, '3', ''),
(770, 256, 1, '3', 'Креативні колегіанти'),
(771, 219, 3, '1', 'Нестримні'),
(772, 9, 3, '18', ''),
(773, 255, 1, '7', ''),
(774, 215, 1, '7', 'PERPETUUM MOBILE'),
(775, 220, 1, '1', ''),
(776, 246, 1, '2', ''),
(777, 110, 6, '6', ''),
(778, 215, 2, '7', ''),
(779, 225, 6, '3', ''),
(780, 222, 1, '11', ''),
(781, 222, 1, '10', ''),
(782, 256, 1, '13', ''),
(783, 222, 1, '2', ''),
(784, 149, 6, '2', 'AEDIFICAREinPOSTERUM'),
(785, 83, 6, '19', 'Ключ на 19'),
(786, 226, 3, '5', ''),
(787, 125, 1, '2', 'Калина'),
(788, 259, 1, '5', ''),
(789, 263, 1, '1', ''),
(790, 165, 1, '8', 'ТЕМП'),
(791, 200, 3, '0', ''),
(792, 191, 1, '4', ''),
(793, 149, 3, '2', ''),
(794, 259, 1, '2', ''),
(795, 18, 1, '4', ''),
(796, 84, 2, '0', 'І-дія'),
(797, 17, 2, '17', ''),
(798, 83, 1, '8', 'Євро Діти'),
(799, 45, 1, '12', 'Заводной Апельсин'),
(800, 66, 1, '8', '8 елемент'),
(801, 262, 1, '5', ''),
(802, 163, 1, '3', ''),
(803, 262, 1, '7', ''),
(804, 70, 1, '4', ''),
(805, 27, 1, '10', ''),
(806, 17, 1, '4', ''),
(807, 1, 6, '5', ''),
(808, 44, 1, '35', ''),
(809, 218, 1, '1', ''),
(810, 8, 1, '4', ''),
(811, 197, 2, '13', ''),
(812, 102, 1, '9', ''),
(813, 225, 1, '4', ''),
(814, 44, 3, '1', ''),
(815, 215, 1, '2', ''),
(816, 28, 1, '2', ''),
(817, 83, 6, '10', 'KIK'),
(818, 207, 1, '2', ''),
(819, 108, 1, '1', ''),
(820, 227, 3, '3', '\"Школа-гімназія №3\" '),
(821, 168, 1, '3', ''),
(822, 219, 1, '4', ''),
(823, 259, 3, '6', ''),
(824, 160, 1, '1', ''),
(825, 243, 1, '12', ''),
(826, 70, 3, '11', ''),
(827, 256, 3, '3', ''),
(828, 275, 6, '1', ''),
(829, 18, 1, '5', ''),
(830, 161, 1, '1', ''),
(831, 43, 1, '9', 'школа 9 '),
(832, 110, 3, '1', ''),
(833, 128, 1, '1', ''),
(834, 256, 7, '12', ''),
(835, 242, 1, '7', ' Родзинки'),
(836, 102, 1, '1', ''),
(837, 102, 7, '12', ''),
(838, 160, 6, '3', ''),
(839, 210, 1, '1', ''),
(840, 127, 7, '33', ''),
(841, 135, 3, '3', 'Крила сходу'),
(842, 246, 3, '2', ''),
(843, 62, 1, '1', ''),
(844, 82, 1, '4', ''),
(845, 134, 3, '2', ''),
(846, 218, 1, '2', ''),
(847, 84, 6, '10', ''),
(848, 222, 3, '8', ''),
(849, 190, 7, '6', ''),
(850, 161, 1, '2', 'ЗОШ №2 '),
(851, 255, 6, '2', ''),
(852, 51, 1, '5', ''),
(853, 138, 1, '1', ''),
(854, 145, 7, '4', ''),
(855, 49, 1, '8', ''),
(856, 51, 3, '1', ''),
(857, 169, 6, '1', 'ТЕМП'),
(858, 10, 6, '2', ''),
(859, 234, 7, '3', ''),
(860, 265, 7, '1', ''),
(861, 244, 3, '3', ''),
(862, 273, 1, '1', ''),
(863, 190, 7, '4', ''),
(864, 82, 1, '1', 'Потенціал'),
(865, 144, 1, '12', 'Together'),
(866, 189, 1, '4', 'School # 4'),
(867, 215, 1, '5', ''),
(868, 166, 1, '7', ''),
(869, 95, 1, '4', ''),
(870, 21, 3, '1', ''),
(871, 95, 1, '6', 'Dream-->Way'),
(872, 176, 1, '3', ''),
(873, 92, 1, '1', ''),
(874, 35, 1, '3', ''),
(875, 18, 1, '10', ''),
(876, 127, 1, '4', ''),
(879, 268, 1, '30', ''),
(880, 185, 6, '1', ''),
(881, 12, 3, '1', ''),
(882, 270, 1, '11', ''),
(883, 35, 1, '5', ''),
(884, 30, 1, '4', ''),
(885, 208, 1, '10', 'Роменська ЗОШ №10'),
(886, 49, 1, '13', ''),
(887, 54, 1, '13', ''),
(888, 21, 1, '1', ''),
(889, 43, 1, '16', 'Школа № 16 '),
(890, 269, 6, '1', ''),
(906, 209, 1, '1', ''),
(908, 169, 1, '1', ''),
(909, 108, 1, '2', ''),
(910, 145, 1, '7', 'Стрийські Єноти'),
(911, 176, 2, '3', ''),
(912, 145, 1, '6', ''),
(913, 17, 3, '3', ''),
(914, 176, 2, '1', ''),
(915, 208, 1, '6', ''),
(918, 50, 1, '22', ''),
(919, 95, 1, '7', 'teenage dream'),
(920, 102, 1, '10', ''),
(921, 241, 1, '5', ''),
(929, 62, 1, '13', 'Українська ЗОШ №13'),
(930, 113, 3, '1', ''),
(931, 39, 3, '1', ''),
(932, 16, 3, '1', ''),
(935, 176, 1, '4', ''),
(936, 176, 3, '4', ''),
(937, 138, 6, '20', ''),
(938, 102, 1, '15', ''),
(939, 94, 1, '1', ''),
(940, 176, 6, '4', ''),
(941, 43, 1, '2', ''),
(945, 102, 1, '3', 'АКТИВ'),
(946, 102, 1, '5', ''),
(948, 102, 1, '11', ''),
(949, 208, 1, '5', 'ЗОШ №5 м.Ромни'),
(953, 108, 3, '4', 'Ми - діти галактики'),
(958, 102, 1, '17', ''),
(973, 229, 1, '6', 'Еверест'),
(981, 15, 3, '17', ''),
(984, 8, 1, '10', ''),
(985, 10, 2, '14', ''),
(986, 9, 1, '16', ''),
(987, 12, 2, '9', ''),
(988, 3, 1, '1', ''),
(989, 11, 2, '15', ''),
(995, 102, 1, '20', 'Мрія'),
(996, 102, 1, '13', ''),
(997, 102, 1, '4', 'БЗШ №4'),
(998, 224, 1, '1', 'ПРЕМ\'ЄР'),
(1000, 42, 1, '18', ''),
(1003, 202, 1, '3', 'Острозька ЗОШ №3'),
(1005, 54, 1, '15', 'ТВІСК'),
(1006, 1, 1, '6', ''),
(1007, 235, 1, '7', ''),
(1008, 188, 1, '9', ''),
(1009, 102, 1, '18', 'БЗШ І-ІІІ ст. №18'),
(1010, 102, 1, '6', ''),
(1011, 229, 1, '7', ''),
(1013, 232, 3, '1', ''),
(1014, 83, 1, '14', 'Час змін'),
(1015, 42, 3, '11', ''),
(1016, 102, 1, '7', 'Позитив'),
(1017, 102, 3, '13', ''),
(1020, 233, 2, '2', ''),
(1022, 232, 2, '2', 'Вперті реп\'яхи'),
(1023, 233, 3, '2', ''),
(1024, 224, 1, '0', 'Клугино-Башкировская'),
(1025, 233, 1, '2', 'Титани '),
(1029, 224, 1, '9', ''),
(1034, 126, 1, '9', 'СОКОЛИ'),
(1035, 45, 1, '1', 'Лідери'),
(1036, 167, 1, '4', ''),
(1038, 128, 1, '2', ''),
(1039, 280, 1, '1', 'Storozhynets ZOSH №1'),
(1040, 15, 1, '1', ''),
(1041, 280, 1, '25', ''),
(1042, 40, 1, '17', ''),
(1043, 48, 1, '21', ''),
(1044, 148, 1, '1', 'Новояворівськ №1'),
(1048, 255, 1, '6', ''),
(1051, 48, 1, '17', 'WINNERS'),
(1054, 138, 1, '15', ''),
(1055, 264, 1, '2', 'Енергія'),
(1056, 224, 3, '6', 'Бегущие по волнам'),
(1057, 44, 1, '12', ''),
(1058, 168, 1, '1', ''),
(1059, 264, 6, '1', ''),
(1060, 264, 3, '1', 'Тальнівський НВК '),
(1062, 246, 3, '9', ''),
(1063, 222, 1, '12', ''),
(1064, 200, 1, '4', 'Сарненська ЗОШ № 4'),
(1065, 176, 1, '2', ''),
(1066, 227, 6, '2', ''),
(1067, 233, 6, '2', ''),
(1068, 246, 1, '6', ''),
(1069, 145, 1, '10', ''),
(1070, 212, 1, '1', ''),
(1071, 283, 1, '1', ''),
(1078, 48, 1, '0', ''),
(1079, 236, 1, '2', ''),
(1080, 103, 1, '15', ''),
(1082, 42, 1, '7', ''),
(1083, 222, 1, '8', ''),
(1084, 1, 1, '1', ''),
(1085, 54, 1, '14', ''),
(1086, 45, 1, '6', 'Лідер'),
(1087, 204, 3, '2', 'Гучномовець'),
(1088, 232, 6, '3', 'R.G.S. '),
(1089, 45, 3, '14', 'Компас'),
(1090, 26, 2, '10', ''),
(1092, 246, 2, '9', ''),
(1097, 95, 1, '5', ''),
(1104, 66, 1, '10', ''),
(1105, 209, 3, '1', ''),
(1106, 212, 1, '5', ''),
(1107, 111, 1, '5', ''),
(1114, 1, 1, '4', ''),
(1115, 45, 1, '17', ''),
(1116, 68, 1, '8', 'СЗОШ №8'),
(1118, 246, 3, '1', ''),
(1119, 241, 1, '4', '\"ТЗМ\" Берислав'),
(1120, 230, 1, '2', ''),
(1121, 151, 1, '1', 'CREATIVE JUNIORS'),
(1122, 68, 1, '1', ''),
(1123, 243, 1, '1', ''),
(1124, 246, 3, '7', ''),
(1125, 13, 1, '1', ''),
(1126, 41, 1, '13', ''),
(1127, 166, 1, '10', ''),
(1129, 128, 3, '3', 'Долинська НВК№3'),
(1131, 128, 1, '4', ''),
(1136, 48, 1, '100', ''),
(1140, 145, 6, '1', ''),
(1142, 236, 3, '1', 'Каховський НВК'),
(1143, 102, 2, '0', ''),
(1144, 83, 1, '24', ''),
(1145, 113, 1, '3', ''),
(1146, 56, 1, '2', 'Невгамовні'),
(1147, 138, 6, '25', ''),
(1148, 70, 1, '7', ''),
(1149, 125, 3, '19', ''),
(1150, 136, 1, '7', ''),
(1151, 269, 1, '6', ''),
(1152, 48, 1, '12', ''),
(1153, 189, 1, '2', ''),
(1154, 189, 6, '1', ''),
(1155, 5, 1, '3', ''),
(1157, 83, 1, '20', ''),
(1158, 102, 6, '1', ''),
(1160, 13, 1, '2', 'Майбутнє Надсоб\'я'),
(1161, 186, 1, '2', 'StarDust Team'),
(1166, 257, 1, '5', ''),
(1167, 13, 6, '2', ''),
(1168, 193, 6, '1', 'Едельвейс'),
(1169, 189, 1, '3', ''),
(1170, 48, 1, '9', '\"Володарі мрій\"'),
(1171, 226, 1, '6', ''),
(1172, 269, 6, '5', ''),
(1173, 1, 2, '19', ''),
(1174, 265, 1, '2', ''),
(1175, 13, 1, '3', ''),
(1177, 44, 1, '5', ''),
(1179, 193, 1, '2', 'FULL ENERGY'),
(1180, 148, 1, '2', ''),
(1182, 128, 6, '3', ''),
(1185, 103, 2, '171', ''),
(1186, 44, 1, '3', ''),
(1187, 43, 1, '15', ''),
(1189, 40, 1, '25', ''),
(1190, 242, 1, '5', 'Поколіннямайбутнього'),
(1192, 13, 3, '2', ''),
(1193, 28, 3, '2', ''),
(1194, 145, 1, '3', ''),
(1195, 259, 6, '0', ''),
(1198, 18, 3, '2', ''),
(1199, 95, 6, '12', ''),
(1201, 230, 1, '1', 'ЗОШ №1'),
(1202, 244, 1, '1', ''),
(1203, 233, 6, '1', ''),
(1208, 222, 1, '0', 'SPIRITUS MOVENS'),
(1212, 129, 1, '4', ''),
(1217, 193, 1, '3', ''),
(1220, 165, 1, '4', ''),
(1221, 50, 1, '2', ''),
(1222, 100, 1, '1', ''),
(1223, 77, 1, '4', ''),
(1224, 78, 1, '3', ''),
(1225, 42, 1, '24', ''),
(1227, 229, 6, '1', ''),
(1228, 39, 1, '2', ''),
(1229, 59, 1, '17', ''),
(1231, 145, 1, '4', ''),
(1232, 257, 1, '8', ''),
(1233, 229, 1, '4', ''),
(1234, 165, 1, '6', ''),
(1238, 46, 1, '6', ''),
(1239, 165, 1, '2', ''),
(1242, 99, 1, '3', ''),
(1243, 244, 6, '5', ''),
(1251, 119, 6, '1', ''),
(1252, 119, 1, '6', ''),
(1258, 78, 1, '4', ''),
(1259, 49, 3, '5', ' Агенти змін'),
(1263, 145, 1, '9', ''),
(1265, 18, 1, '3', 'Вруна'),
(1266, 45, 1, '8', ''),
(1270, 227, 6, '3', ''),
(1271, 22, 1, '18', ''),
(1275, 86, 1, '8', 'Team Unity'),
(1276, 46, 1, '4', 'Нове Покоління'),
(1277, 106, 1, '17', ''),
(1278, 86, 1, '22', ''),
(1279, 86, 1, '10', ''),
(1280, 78, 1, '6', 'Хустські оленята'),
(1281, 49, 1, '17', 'м.Добропілля,ЗОШ №17'),
(1283, 46, 1, '5', ''),
(1286, 230, 3, '1', ''),
(1287, 50, 1, '6', ''),
(1288, 68, 1, '17', ''),
(1289, 212, 1, '3', ''),
(1290, 153, 1, '3', ''),
(1293, 86, 3, '100500', 'UABAND'),
(1312, 86, 1, '3', ''),
(1317, 98, 1, '6', ''),
(1319, 78, 3, '4', ''),
(1325, 267, 1, '1', ''),
(1327, 42, 1, '17', ''),
(1328, 46, 1, '8', ''),
(1334, 197, 1, '5', 'Промінь майбутнього'),
(1335, 272, 1, '2', ''),
(1336, 44, 1, '2', 'Команда \"Фенікс\"'),
(1337, 267, 1, '2', 'Христинівська ЗОШ №2'),
(1348, 86, 3, '1', ''),
(1357, 212, 1, '2', ''),
(1358, 44, 1, '4', 'Dividers'),
(1359, 86, 1, '9', ''),
(1361, 84, 1, '5', ''),
(1365, 236, 1, '6', 'Творці ЗОШ№6'),
(1366, 43, 3, '100500', 'Покоління NEXT'),
(1373, 86, 1, '0', ''),
(1382, 63, 1, '13', 'Долоні сонця'),
(1395, 259, 6, '1', ''),
(1396, 281, 6, '1', 'Господарі своєї школ'),
(1398, 209, 1, '100500', 'Глухівська ЗОШ-інтер'),
(1403, 244, 6, '2', ''),
(1404, 49, 3, '4', ''),
(1409, 42, 2, '11', ''),
(1414, 165, 2, '6', ''),
(1417, 37, 2, '3', ''),
(1428, 49, 3, '1', ''),
(1430, 40, 1, '8', ''),
(1432, 280, 6, '2', ''),
(1435, 43, 3, '11', ''),
(1436, 22, 1, '21', ''),
(1441, 205, 1, '10', ''),
(1449, 37, 2, '0', ''),
(1451, 93, 1, '3', 'Лідери'),
(1452, 109, 1, '5', ''),
(1453, 22, 3, '13', ''),
(1455, 127, 3, '2', ''),
(1456, 18, 6, '5', ''),
(1457, 260, 1, '1', ''),
(1458, 281, 1, '2', ''),
(1459, 272, 6, '1', ''),
(1460, 86, 1, '12', ''),
(1461, 41, 1, '4', 'Діти України '),
(1463, 20, 3, '1', 'Окрилені мрією'),
(1464, 205, 1, '9', ''),
(1465, 148, 1, '3', 'Територія щасливих'),
(1466, 102, 1, '12', 'Школа 12 БІЛА ЦЕРКВА'),
(1467, 109, 1, '1', ''),
(1470, 46, 1, '1', ''),
(1471, 204, 3, '1', 'РадоDrive'),
(1472, 78, 1, '5', ''),
(1475, 70, 1, '2', ''),
(1476, 171, 1, '16', ''),
(1477, 5, 1, '10', ''),
(1478, 244, 1, '15', ''),
(1479, 171, 1, '1', ''),
(1480, 78, 1, '1', ''),
(1482, 41, 1, '16', ''),
(1483, 118, 1, '1', ''),
(1484, 118, 1, '2', 'Stars'),
(1485, 172, 1, '3', ''),
(1487, 118, 1, '4', ''),
(1488, 103, 1, '10', ''),
(1489, 240, 6, '2', ''),
(1490, 153, 1, '2', ''),
(1491, 212, 1, '4', ''),
(1492, 52, 6, '6', ''),
(1493, 3, 3, '1', ''),
(1494, 153, 1, '4', 'Black falcon'),
(1495, 85, 1, '7', ''),
(1496, 40, 1, '4', 'КУБ '),
(1497, 82, 1, '3', ''),
(1498, 107, 1, '2', ''),
(1499, 114, 2, '1', ''),
(1500, 103, 1, '2', ''),
(1501, 20, 2, '1', ''),
(1502, 168, 6, '1', ''),
(1503, 40, 1, '20', ''),
(1505, 61, 1, '3', ''),
(1506, 236, 1, '5', ''),
(1507, 153, 6, '1', ''),
(1508, 87, 1, '1', ''),
(1509, 236, 1, '4', ''),
(1510, 52, 3, '1', ''),
(1512, 243, 3, '3', ''),
(1513, 20, 3, '2', ''),
(1515, 52, 1, '3', ''),
(1516, 6, 3, '1', ''),
(1517, 153, 1, '5', 'П\'ята мандрівка'),
(1518, 41, 1, '18', ''),
(1519, 189, 2, '4', ''),
(1522, 243, 3, '8', ''),
(1525, 269, 1, '7', ''),
(1531, 40, 1, '1', ''),
(1536, 186, 1, '3', ''),
(1537, 50, 6, '100500', 'Селидівська гімназія'),
(1538, 136, 6, '100500', '\"Разом\"'),
(1539, 278, 1, '1', ''),
(1540, 2, 3, '3', ''),
(1546, 27, 1, '11', ''),
(1550, 263, 2, '100', ''),
(1551, 8, 1, '1', ''),
(1555, 140, 6, '7', ''),
(1556, 145, 1, '1', ''),
(1557, 118, 3, '3', ''),
(1558, 8, 3, '18', ''),
(1559, 8, 6, '7', ''),
(1561, 182, 1, '2', ''),
(1562, 103, 1, '6', ''),
(1563, 196, 1, '2', ''),
(1564, 120, 1, '3', 'Покоління Z'),
(1565, 20, 6, '2', 'Вітер змін'),
(1566, 278, 1, '4', ''),
(1567, 103, 1, '7', 'НЕСТРИМНІ'),
(1569, 221, 1, '7', ''),
(1570, 2, 3, '5', ''),
(1571, 8, 6, '2', ''),
(1572, 106, 1, '12', ''),
(1573, 186, 1, '4', ''),
(1575, 2, 3, '4', 'Діамант'),
(1576, 6, 1, '1', ''),
(1577, 103, 1, '3', 'Гарт'),
(1579, 17, 2, '3', ''),
(1580, 103, 3, '8', ''),
(1581, 243, 1, '100', ''),
(1582, 202, 6, '2', ''),
(1583, 280, 6, '1', ''),
(1584, 41, 1, '15', ''),
(1585, 175, 1, '1', ''),
(1586, 52, 6, '0', ''),
(1587, 112, 1, '7', ''),
(1588, 112, 1, '3', 'Переяслав-ХмШкола №3'),
(1589, 124, 3, '100500', '\"Ерудит\"'),
(1591, 167, 1, '2', ''),
(1592, 112, 1, '2', ''),
(1593, 112, 1, '4', 'New generаtion'),
(1594, 13, 6, '3', ''),
(1595, 161, 1, '3', ''),
(1596, 265, 2, '1', ''),
(1598, 49, 1, '11', '\"ОЛІМПІЙЦІ\"'),
(1599, 119, 1, '1', ''),
(1600, 124, 6, '1', ''),
(1601, 124, 3, '1', ''),
(1611, 124, 1, '1', ''),
(1618, 251, 3, '2', ''),
(1620, 112, 1, '5', ''),
(1621, 85, 6, '3', ''),
(1622, 109, 3, '6', 'Флорауна'),
(1623, 172, 6, '1', 'Покоління NEXT'),
(1624, 18, 1, '1', ''),
(1625, 256, 1, '2', ''),
(1626, 141, 1, '15', ''),
(1627, 23, 1, '11', ''),
(1630, 136, 3, '28', '\"Діти \"Гаранту\"'),
(1633, 256, 1, '10', ''),
(1634, 136, 1, '28', ''),
(1637, 174, 1, '1', ''),
(1638, 136, 1, '30', ''),
(1639, 8, 3, '2', 'НВК ЗОШ №2-гімназія'),
(1640, 278, 1, '2', ''),
(1642, 175, 6, '3', ''),
(1643, 175, 1, '3', ''),
(1644, 145, 1, '2', ''),
(1645, 217, 3, '1', ''),
(1647, 141, 1, '4', ''),
(1648, 62, 6, '1', ''),
(1651, 186, 3, '5', ''),
(1652, 136, 1, '6', ''),
(1653, 238, 1, '0', ''),
(1655, 186, 1, '5', ''),
(1658, 108, 1, '7', ''),
(1660, 127, 2, '2', ''),
(1661, 129, 3, '9', ''),
(1662, 182, 3, '125', ''),
(1663, 136, 1, '27', 'Dreamers'),
(1669, 129, 1, '3', ''),
(1672, 167, 1, '1', ''),
(1673, 127, 1, '6', ''),
(1674, 98, 1, '7', ''),
(1676, 186, 1, '6', ''),
(1677, 2, 2, '4', ''),
(1678, 144, 1, '11', ''),
(1679, 127, 6, '3', ''),
(1680, 9, 1, '3', ''),
(1683, 127, 3, '3', ''),
(1685, 127, 1, '3', ''),
(1686, 31, 1, '7', ''),
(1687, 49, 2, '5', ''),
(1689, 207, 1, '8', ''),
(1691, 22, 3, '12', ''),
(1692, 190, 2, '1', ''),
(1693, 186, 6, '3', ''),
(1695, 131, 6, '100500', 'Команда АКМЕ'),
(1707, 259, 1, '0', ''),
(1710, 121, 6, '4', ''),
(1713, 121, 3, '4', ''),
(1715, 247, 1, '1', ''),
(1721, 200, 3, '1', 'НВК №1'),
(1725, 71, 1, '18', ''),
(1726, 44, 2, '2', ''),
(1733, 49, 6, '1', ''),
(1736, 216, 1, '3', ''),
(1737, 3, 1, 'Имені Ленона', 'зелений цукор'),
(1739, 236, 6, '1', ''),
(1744, 202, 3, '2', ''),
(1747, 103, 1, '8', ''),
(1751, 107, 3, '6', ''),
(1753, 113, 3, '8', ''),
(1757, 191, 6, '5', 'Імпульс'),
(1758, 217, 6, 'ім. Б. Лепкого', ''),
(1759, 142, 1, '1', ''),
(1761, 142, 6, '1', ''),
(1763, 13, 1, '6', ''),
(1764, 239, 3, '0', ''),
(1766, 45, 6, 'Гімназія \"Інтелект\"', 'Fast River'),
(1768, 17, 1, 'Загальноосвітня школа№2', 'ВОЛ.-ВОЛИНСЬКА ЗОШ 2'),
(1770, 129, 2, '36', ''),
(1771, 72, 3, '2', ''),
(1772, 163, 1, '1', ''),
(1773, 1, 6, '45', ''),
(1774, 13, 6, '15', ''),
(1775, 3, 3, '14', ''),
(1777, 2, 3, '2', ''),
(1778, 1, 3, '11', ''),
(1779, 1, 2, '14', ''),
(1781, 12, 1, '10', ''),
(1782, 2, 3, '15', ''),
(1783, 20, 2, '14', ''),
(1784, 42, 6, '2', ''),
(1785, 40, 1, '32', ''),
(1786, 1, 3, '16', ''),
(1787, 2, 2, '13', ''),
(1788, 1, 2, '3', ''),
(1789, 1, 3, '3', ''),
(1790, 3, 3, '16', ''),
(1791, 1, 3, '14', ''),
(1792, 209, 1, '', 'Інтернат 1-3 ст. ім. М. І. Жужоми'),
(1793, 3, 1, '100500', 'школа'),
(1794, 102, 2, '1', ''),
(1795, 166, 6, '1', ''),
(1796, 197, 2, '4', ''),
(1798, 48, 1, 'інтернат', ''),
(1801, 1, 2, '15', ''),
(1802, 3, 2, '1', ''),
(1803, 142, 2, '5', ''),
(1805, 13, 1, '34', ''),
(1806, 15, 2, '18', ''),
(1807, 2, 3, '17', ''),
(1808, 77, 3, 'Мукачівський НВК \"ДНЗ - ЗОШ І ст. - гімназія\"', 'Мукачівська гімназія'),
(1809, 2, 2, '16', ''),
(1810, 3, 2, '15', ''),
(1811, 102, 3, '1', ''),
(1812, 142, 1, '165', ''),
(1813, 193, 1, '1', ''),
(1816, 41, 1, '85', ''),
(1817, 13, 2, '14', ''),
(1818, 9, 2, '14', ''),
(1819, 9, 1, '1', ''),
(1820, 14, 2, '14', ''),
(1821, 7, 3, '17', ''),
(1822, 12, 2, '17', ''),
(1823, 270, 2, '17', ''),
(1824, 7, 3, '14', ''),
(1826, 50, 1, '1', ''),
(1827, 160, 2, '15', ''),
(1828, 15, 6, '18', ''),
(1829, 3, 2, '18', ''),
(1830, 14, 3, '16', ''),
(1831, 67, 3, '16', ''),
(1832, 222, 1, '5', ''),
(1833, 2, 6, '13', ''),
(1834, 5, 1, '91', ''),
(1835, 1, 1, '18', ''),
(1836, 15, 2, '19', ''),
(1837, 40, 6, '21', ''),
(1838, 10, 1, '19', ''),
(1839, 10, 2, '3', ''),
(1840, 17, 1, '17', ''),
(1841, 10, 3, '13', ''),
(1842, 8, 2, '13', ''),
(1843, 12, 2, '15', ''),
(1844, 16, 6, '18', ''),
(1845, 15, 3, '61', ''),
(1846, 13, 6, '53', ''),
(1847, 6, 6, '84', ''),
(1848, 10, 3, '17', ''),
(1849, 88, 1, '78', ''),
(1850, 14, 2, '55', ''),
(1851, 12, 2, '16', ''),
(1852, 2, 1, '17', ''),
(1853, 14, 2, '17', ''),
(1854, 15, 2, '73', ''),
(1855, 11, 2, '18', ''),
(1856, 13, 1, '19', ''),
(1857, 12, 1, '17', ''),
(1858, 11, 3, '17', ''),
(1859, 12, 6, '17', ''),
(1860, 109, 3, '15', ''),
(1861, 1, 1, '77', ''),
(1862, 15, 6, '34', ''),
(1863, 16, 6, '17', ''),
(1864, 23, 2, '43', ''),
(1865, 15, 2, '43', ''),
(1866, 101, 3, '55', ''),
(1867, 15, 3, '48', ''),
(1868, 17, 2, '18', ''),
(1869, 10, 1, '42', ''),
(1870, 17, 3, '19', ''),
(1871, 1, 3, '17', ''),
(1872, 16, 2, '18', ''),
(1873, 54, 3, '18', ''),
(1874, 2, 6, '19', ''),
(1875, 37, 3, '43', ''),
(1876, 16, 2, '44', ''),
(1877, 50, 3, '38', ''),
(1878, 26, 3, '14', ''),
(1879, 14, 3, '17', ''),
(1880, 63, 6, '17', ''),
(1881, 14, 2, '54', ''),
(1882, 16, 6, '16', ''),
(1883, 11, 6, '23', ''),
(1884, 57, 1, '82', ''),
(1885, 11, 1, '15', ''),
(1886, 8, 1, '12', ''),
(1887, 64, 1, '94', ''),
(1888, 105, 1, '68', ''),
(1889, 113, 1, '15', ''),
(1890, 82, 1, '83', ''),
(1891, 98, 1, '60', ''),
(1892, 43, 1, '60', ''),
(1893, 105, 1, '17', ''),
(1894, 65, 1, '14', ''),
(1895, 28, 1, '15', ''),
(1896, 17, 6, '17', ''),
(1897, 12, 3, '17', ''),
(1898, 16, 1, '17', ''),
(1899, 24, 3, '18', ''),
(1900, 15, 6, '17', ''),
(1901, 7, 1, '17', ''),
(1902, 85, 1, '16', ''),
(1903, 13, 1, '15', ''),
(1904, 8, 3, '15', ''),
(1905, 11, 6, '99', ''),
(1906, 65, 2, '93', ''),
(1907, 9, 1, '62', ''),
(1908, 69, 1, '17', ''),
(1909, 13, 1, '16', ''),
(1910, 40, 1, '70', ''),
(1911, 51, 1, '73', ''),
(1912, 105, 1, '50', ''),
(1913, 155, 1, '58', ''),
(1914, 12, 1, '81', ''),
(1915, 105, 1, '18', ''),
(1916, 99, 1, '71', ''),
(1917, 15, 1, '14', ''),
(1918, 49, 1, '12', ''),
(1919, 25, 1, '12', ''),
(1920, 16, 1, '18', ''),
(1921, 10, 1, '8', ''),
(1922, 4, 1, '17', ''),
(1923, 9, 1, '47', ''),
(1924, 74, 1, '39', ''),
(1925, 61, 1, '60', ''),
(1926, 49, 1, '47', ''),
(1927, 75, 1, '13', ''),
(1928, 47, 1, '14', ''),
(1929, 42, 1, '8', ''),
(1930, 35, 1, '14', ''),
(1931, 69, 1, '16', ''),
(1932, 42, 1, '11', ''),
(1933, 43, 1, '17', ''),
(1934, 55, 1, '13', ''),
(1935, 48, 1, '52', ''),
(1936, 48, 1, '99', ''),
(1937, 47, 1, '92', ''),
(1938, 47, 1, '79', ''),
(1939, 47, 1, '71', ''),
(1940, 47, 1, '97', ''),
(1941, 47, 1, '93', ''),
(1942, 47, 1, '87', ''),
(1943, 47, 1, '84', ''),
(1944, 47, 1, '77', ''),
(1945, 47, 1, '74', ''),
(1946, 57, 1, '91', ''),
(1947, 57, 1, '86', ''),
(1948, 57, 1, '97', ''),
(1949, 57, 1, '93', ''),
(1950, 57, 1, '80', ''),
(1951, 57, 1, '74', ''),
(1952, 57, 1, '95', ''),
(1953, 57, 1, '89', ''),
(1954, 55, 1, '77', ''),
(1955, 55, 1, '72', ''),
(1956, 55, 1, '62', ''),
(1957, 55, 1, '81', ''),
(1958, 40, 1, '99', ''),
(1959, 40, 1, '96', ''),
(1960, 40, 1, '91', ''),
(1961, 40, 1, '81', ''),
(1962, 40, 1, '92', ''),
(1963, 40, 1, '83', ''),
(1964, 40, 1, '76', ''),
(1965, 40, 1, '85', ''),
(1966, 40, 1, '98', ''),
(1967, 40, 1, '75', ''),
(1968, 40, 1, '69', ''),
(1969, 40, 1, '62', ''),
(1970, 40, 1, '86', ''),
(1971, 40, 1, '79', ''),
(1972, 40, 1, '89', ''),
(1973, 40, 1, '80', ''),
(1974, 40, 1, '71', ''),
(1975, 40, 1, '87', ''),
(1976, 47, 1, '75', ''),
(1977, 47, 1, '96', ''),
(1978, 47, 1, '89', ''),
(1979, 47, 1, '83', ''),
(1980, 47, 1, '78', ''),
(1981, 47, 1, '72', ''),
(1982, 47, 1, '81', ''),
(1983, 47, 1, '65', ''),
(1984, 47, 1, '94', ''),
(1985, 47, 1, '85', ''),
(1986, 47, 1, '82', ''),
(1987, 47, 1, '63', ''),
(1988, 47, 1, '73', ''),
(1989, 45, 1, '70', ''),
(1990, 45, 1, '65', ''),
(1991, 45, 1, '86', ''),
(1992, 45, 1, '71', ''),
(1993, 45, 1, '81', ''),
(1994, 45, 1, '79', ''),
(1995, 45, 1, '76', ''),
(1996, 45, 1, '66', ''),
(1997, 43, 1, '58', ''),
(1998, 43, 1, '69', ''),
(1999, 43, 1, '89', ''),
(2000, 43, 1, '84', ''),
(2001, 43, 1, '79', ''),
(2002, 43, 1, '73', ''),
(2003, 43, 1, '82', ''),
(2004, 43, 1, '80', ''),
(2005, 43, 1, '75', ''),
(2006, 43, 1, '70', ''),
(2007, 43, 1, '85', ''),
(2008, 53, 1, '90', ''),
(2009, 53, 1, '98', ''),
(2010, 53, 1, '91', ''),
(2012, 10, 2, '17', ''),
(2013, 273, 6, 'Корюківська', ''),
(2014, 201, 1, '3', ''),
(2016, 13, 6, 'ЗОШ2', ''),
(2018, 64, 1, '22', ''),
(2020, 84, 1, '20', ''),
(2021, 162, 3, '29', ''),
(2022, 94, 2, '46', ''),
(2023, 92, 2, '15', ''),
(2024, 175, 2, '16', ''),
(2025, 87, 2, '15', ''),
(2026, 8, 2, '15', ''),
(2027, 191, 3, '13', ''),
(2028, 151, 2, '18', ''),
(2029, 14, 1, '10', ''),
(2030, 120, 1, '5', ''),
(2031, 68, 2, '3', ''),
(2032, 61, 6, '4', ''),
(2033, 68, 2, '4', ''),
(2034, 184, 1, '1', ''),
(2035, 87, 1, '2', ''),
(2036, 153, 1, '1', ''),
(2037, 4, 1, '1', ''),
(2038, 146, 1, '2', ''),
(2039, 149, 2, '3', ''),
(2040, 185, 1, '11', ''),
(2041, 268, 3, '15', ''),
(2042, 111, 1, '8', ''),
(2043, 163, 3, '3', ''),
(2044, 131, 1, '5', ''),
(2045, 15, 3, '19', ''),
(2046, 16, 6, '15', ''),
(2047, 13, 2, '17', ''),
(2048, 10, 3, '16', ''),
(2049, 15, 6, '16', ''),
(2050, 8, 1, '63', ''),
(2051, 36, 1, '85', ''),
(2052, 26, 1, '95', ''),
(2053, 19, 1, '90', ''),
(2054, 33, 1, '84', ''),
(2055, 75, 1, '79', ''),
(2056, 92, 1, '79', ''),
(2057, 96, 1, '79', ''),
(2058, 84, 1, '94', ''),
(2059, 95, 1, '90', ''),
(2060, 113, 1, '82', ''),
(2061, 100, 1, '80', ''),
(2062, 100, 1, '75', ''),
(2063, 113, 1, '56', ''),
(2064, 100, 1, '51', ''),
(2065, 94, 1, '43', ''),
(2066, 113, 1, '64', ''),
(2067, 104, 1, '86', ''),
(2068, 129, 1, '93', ''),
(2069, 123, 1, '93', ''),
(2070, 119, 1, '89', ''),
(2071, 119, 1, '81', ''),
(2072, 109, 1, '78', ''),
(2073, 169, 1, '78', ''),
(2074, 162, 1, '68', ''),
(2075, 175, 1, '62', ''),
(2076, 197, 1, '84', ''),
(2077, 7, 1, '98', ''),
(2078, 35, 1, '57', ''),
(2079, 50, 1, '82', ''),
(2080, 29, 1, '76', ''),
(2081, 17, 1, '71', ''),
(2082, 12, 1, '65', ''),
(2083, 7, 1, '75', ''),
(2084, 2, 1, '96', ''),
(2085, 35, 1, '86', ''),
(2086, 100, 1, '81', ''),
(2087, 91, 1, '96', ''),
(2088, 106, 1, '74', ''),
(2089, 97, 1, '89', ''),
(2090, 113, 1, '83', ''),
(2091, 129, 1, '81', ''),
(2092, 121, 1, '75', ''),
(2093, 136, 1, '85', ''),
(2094, 157, 1, '83', ''),
(2095, 154, 1, '77', ''),
(2096, 149, 1, '97', ''),
(2097, 174, 1, '88', ''),
(2098, 215, 1, '83', ''),
(2099, 207, 1, '78', ''),
(2100, 193, 1, '72', ''),
(2101, 180, 1, '65', ''),
(2102, 173, 1, '63', ''),
(2103, 169, 1, '59', ''),
(2104, 138, 1, '80', ''),
(2105, 137, 1, '88', ''),
(2106, 136, 1, '84', ''),
(2107, 135, 1, '81', ''),
(2108, 157, 1, '100', ''),
(2109, 216, 1, '91', ''),
(2110, 212, 1, '83', ''),
(2111, 208, 1, '81', ''),
(2112, 228, 1, '97', ''),
(2113, 14, 1, '41', ''),
(2114, 38, 1, '16', ''),
(2115, 224, 1, '94', ''),
(2116, 230, 1, '86', ''),
(2117, 227, 1, '83', ''),
(2118, 224, 1, '74', ''),
(2119, 212, 1, '69', ''),
(2120, 208, 1, '63', ''),
(2121, 5, 1, '13', ''),
(2122, 12, 1, '98', ''),
(2123, 30, 1, '91', ''),
(2124, 20, 1, '85', ''),
(2125, 34, 1, '99', ''),
(2126, 74, 1, '93', ''),
(2127, 89, 1, '90', ''),
(2128, 103, 1, '87', ''),
(2129, 113, 1, '86', ''),
(2130, 122, 1, '90', ''),
(2131, 126, 1, '88', ''),
(2132, 131, 1, '99', ''),
(2133, 128, 1, '96', ''),
(2134, 132, 1, '95', ''),
(2135, 131, 1, '94', ''),
(2136, 127, 1, '92', ''),
(2137, 140, 1, '89', ''),
(2138, 139, 1, '85', ''),
(2139, 141, 1, '94', ''),
(2140, 138, 1, '90', ''),
(2141, 142, 1, '92', ''),
(2142, 141, 1, '90', ''),
(2143, 156, 1, '86', ''),
(2144, 153, 1, '83', ''),
(2145, 147, 1, '81', ''),
(2146, 146, 1, '77', ''),
(2147, 137, 1, '75', ''),
(2148, 146, 1, '79', ''),
(2149, 155, 1, '70', ''),
(2150, 11, 1, '63', ''),
(2151, 14, 1, '59', ''),
(2152, 29, 1, '73', ''),
(2153, 38, 1, '77', ''),
(2154, 72, 1, '93', ''),
(2155, 71, 1, '91', ''),
(2156, 284, 1, '100', ''),
(2157, 98, 3, '16', ''),
(2158, 15, 3, '16', ''),
(2159, 15, 1, '17', ''),
(2160, 15, 1, '19', ''),
(2161, 16, 2, '17', ''),
(2162, 14, 3, '19', ''),
(2164, 200, 6, '0', ''),
(2165, 70, 1, '11', ''),
(2166, 25, 2, '10', ''),
(2169, 230, 2, '5', ''),
(2173, 106, 2, '6', ''),
(2188, 153, 3, '4', '');

-- --------------------------------------------------------

--
-- Table structure for table `schooltypes`
--

CREATE TABLE `schooltypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schooltypes`
--

INSERT INTO `schooltypes` (`id`, `name`) VALUES
(1, 'ЗОШ'),
(2, 'Ліцей'),
(3, 'НВК'),
(6, 'Гімназія'),
(7, 'Має власну назву');

-- --------------------------------------------------------

--
-- Table structure for table `site_comment`
--

CREATE TABLE `site_comment` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `site_user_id` int(11) NOT NULL,
  `tree` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `message` varchar(512) NOT NULL,
  `rating` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `site_comment_channel`
--

CREATE TABLE `site_comment_channel` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(512) NOT NULL,
  `site_user_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `site_comment_vote`
--

CREATE TABLE `site_comment_vote` (
  `site_user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `site_user`
--

CREATE TABLE `site_user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `avatar` varchar(512) NOT NULL,
  `age` int(11) NOT NULL,
  `class` varchar(5) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL,
  `password` varchar(512) NOT NULL,
  `login_count` int(100) NOT NULL,
  `agreement_read` int(1) NOT NULL DEFAULT '0',
  `status` varchar(128) NOT NULL,
  `language` varchar(3) NOT NULL,
  `level_id` int(100) NOT NULL,
  `level_experience` int(11) NOT NULL,
  `total_experience` int(11) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `name`) VALUES
(1, 'Івано-Франківська'),
(2, 'Вінницька'),
(3, 'Волинська'),
(4, 'Дніпропетровська'),
(5, 'Донецька'),
(6, 'Житомирська'),
(7, 'Закарпатська'),
(8, 'Запорізька'),
(9, 'Кіровоградська'),
(10, 'Київська'),
(11, 'Луганська'),
(12, 'Львівська'),
(13, 'Миколаївська'),
(14, 'Одеська'),
(15, 'Полтавська'),
(16, 'Рівненська'),
(17, 'Сумська'),
(18, 'Тернопільська'),
(19, 'Харківська'),
(20, 'Херсонська'),
(21, 'Хмельницька'),
(22, 'Черкаська'),
(23, 'Чернівецька'),
(24, 'Чернігівська');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `level_id` int(11) NOT NULL,
  `level_experience` int(11) NOT NULL DEFAULT '0',
  `total_experience` int(11) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'UNCONFIRMED',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `team_site_user`
--

CREATE TABLE `team_site_user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `site_user_id` int(11) DEFAULT NULL,
  `status` varchar(256) NOT NULL DEFAULT 'UNCONFIRMED',
  `team_id` int(11) DEFAULT NULL,
  `role` varchar(32) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_achievement`
--

CREATE TABLE `user_achievement` (
  `id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `site_user_id` int(11) NOT NULL,
  `performed_steps` int(11) NOT NULL,
  `done` int(1) NOT NULL,
  `is_first` int(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `done_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Table structure for table `user_award`
--

CREATE TABLE `user_award` (
  `id` int(11) NOT NULL,
  `site_user_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `object_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_level_history`
--

CREATE TABLE `user_level_history` (
  `id` int(11) NOT NULL,
  `site_user_id` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievement`
--
ALTER TABLE `achievement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catalog_items_i18n`
--
ALTER TABLE `catalog_items_i18n`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
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
-- Indexes for table `level`
--
ALTER TABLE `level`
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
-- Indexes for table `news_user_notification`
--
ALTER TABLE `news_user_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_user`
--
ALTER TABLE `notification_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages_i18n`
--
ALTER TABLE `pages_i18n`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schooltypes`
--
ALTER TABLE `schooltypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_comment`
--
ALTER TABLE `site_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_comment_channel`
--
ALTER TABLE `site_comment_channel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_user`
--
ALTER TABLE `site_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_site_user`
--
ALTER TABLE `team_site_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_achievement`
--
ALTER TABLE `user_achievement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_award`
--
ALTER TABLE `user_award`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_level_history`
--
ALTER TABLE `user_level_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievement`
--
ALTER TABLE `achievement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `award`
--
ALTER TABLE `award`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `catalog_items_i18n`
--
ALTER TABLE `catalog_items_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

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
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `easyii_files`
--
ALTER TABLE `easyii_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `easyii_modules`
--
ALTER TABLE `easyii_modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `easyii_news`
--
ALTER TABLE `easyii_news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `easyii_pages`
--
ALTER TABLE `easyii_pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `easyii_photos`
--
ALTER TABLE `easyii_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

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
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;

--
-- AUTO_INCREMENT for table `easyii_texts`
--
ALTER TABLE `easyii_texts`
  MODIFY `text_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items_i18n`
--
ALTER TABLE `items_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `news_i18n`
--
ALTER TABLE `news_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `news_user_notification`
--
ALTER TABLE `news_user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `notification_user`
--
ALTER TABLE `notification_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `pages_i18n`
--
ALTER TABLE `pages_i18n`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2189;

--
-- AUTO_INCREMENT for table `schooltypes`
--
ALTER TABLE `schooltypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `site_comment`
--
ALTER TABLE `site_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `site_comment_channel`
--
ALTER TABLE `site_comment_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_user`
--
ALTER TABLE `site_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `team_site_user`
--
ALTER TABLE `team_site_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_achievement`
--
ALTER TABLE `user_achievement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_award`
--
ALTER TABLE `user_award`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user_level_history`
--
ALTER TABLE `user_level_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
