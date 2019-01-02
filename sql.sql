

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fresc512_dukkan`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_tokens`
--

CREATE TABLE `access_tokens` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` char(64) NOT NULL,
  `date_expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `access_tokens`
--

INSERT INTO `access_tokens` (`user_id`, `token`, `date_expires`) VALUES
(5, '6e8386f63edececbe04a161e961bd5a24c73934903b3640ed917a07e59462dee', '2018-07-31 16:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_session_id` char(32) NOT NULL,
  `product_id` mediumint(8) UNSIGNED NOT NULL,
  `product_code` int(10) DEFAULT NULL,
  `quantity` tinyint(3) UNSIGNED NOT NULL,
  `size` int(10) DEFAULT NULL,
  `image` varchar(70) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carts`
--

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `category` varchar(40) NOT NULL,
  `h1_title` varchar(190) DEFAULT NULL,
  `description` tinytext NOT NULL,
  `image` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `h1_title`, `description`, `image`) VALUES
(1, 'Coffees', 'Greek (Turkish) Coffee - Daily Roasted', 'Daily roasted fresh Turkish - Greek coffee in UK', 'coffees.jpg'),
(2, 'Confectionaries', 'Freshly Baked Greek Confectionery in UK', 'Freshly daily baked Greek confectionaries .', 'confectionary.jpg'),
(3, 'Fruitandveg', 'Fruits and Vegetables', 'Fresh fruits and vegetables delivered to your door for free.', 'groceries.jpg'),
(4, 'Baklava', 'Baklava order online', '', 'baklava.jpg'),
(5, 'Milk', 'Fresh Milk', '', ''),
(6, 'Nuts', 'Fresh nuts', 'Buy the freshest raw nuts with discounted cheaper prices. Definitely cheaper than Tesco!', ''),
(7, 'Honey', 'Fresh Greek Honey', 'Buy our raw forest honey from Greece with discounted, cheap prices.', ''),
(8, 'Almonds', 'Whole Almonds', 'Raw Almonds with No Additives, Preservatives or Added salt.', ''),
(9, 'Walnuts', 'Walnut Kernel', 'Walnut kernels, no additives or preservatibes. Fresh crop!', ''),
(10, 'Pistachios', 'Pistachio Nuts', 'Fresh Pistachios to buy with No Additives, Preservatives or Added salt.', ''),
(11, 'Hazelnuts', 'Organic Hazelnuts', 'Roasted, organic hazelnuts from Turkey. ', 'hazelnuts-400x400.png');

-- --------------------------------------------------------

--
-- Table structure for table `charges`
--

CREATE TABLE `charges` (
  `id` int(10) UNSIGNED NOT NULL,
  `charge_id` varchar(60) NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(18) NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `charge` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `charges`
--

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(80) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `address1` varchar(80) NOT NULL,
  `address2` varchar(80) DEFAULT NULL,
  `city` varchar(60) NOT NULL,
  `post_code` varchar(8) NOT NULL,
  `phone` char(10) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_slot` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `email`, `first_name`, `last_name`, `address1`, `address2`, `city`, `post_code`, `phone`, `date_created`, `delivery_slot`) VALUES
(2, 'test@hotmail.co.uk', 'Mr.firsnam', 'Mudhk', '74 sdfs Road', '', 'London', 'E7 6re', '0795456798', '2018-01-06 11:11:59', NULL)
;

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE `days` (
  `id` int(10) UNSIGNED NOT NULL,
  `day` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `day`) VALUES
(24, '2018-09-14'),
(25, '2018-09-15'),
(26, '2018-09-15'),
(27, '2018-09-20'),
(28, '2018-09-21'),
(29, '2018-09-22'),
(30, '2018-09-27'),
(31, '2018-09-28'),
(32, '2018-09-29'),
(33, '2018-10-05'),
(34, '2018-10-06'),
(35, '2018-10-07'),
(39, '2018-10-07'),
(40, '2018-10-08'),
(36, '2018-10-12'),
(37, '2018-10-13'),
(38, '2018-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `days_slots`
--

CREATE TABLE `days_slots` (
  `id` int(10) UNSIGNED NOT NULL,
  `day_id` int(10) UNSIGNED NOT NULL,
  `slot_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `days_slots`
--

INSERT INTO `days_slots` (`id`, `day_id`, `slot_id`) VALUES
(1, 8, 2),
(2, 8, 1),
(3, 8, 1),
(4, 8, 2),
(5, 8, 2),
(6, 11, 4),
(7, 15, 4),
(8, 22, 2),
(9, 21, 3),
(10, 21, 4),
(11, 22, 4),
(12, 23, 4),
(13, 2, 3),
(14, 1, 2),
(15, 7, 3),
(16, 7, 3),
(17, 11, 1),
(18, 13, 4),
(19, 11, 3),
(20, 22, 2);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(10) UNSIGNED NOT NULL,
  `img_name` varchar(45) NOT NULL,
  `prod_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `img_name`, `prod_id`) VALUES
(1, 'pourakia.png', 5),
(2, 'greek-coffee-loumidis.png', 9),
(3, 'greek-coffee-bravo.png', 10),
(4, 'greek-coffee-loumidis.png', 11),
(5, 'turkish-coffee-500g.png', 12),
(6, 'baklava-fistikli.jpg', 13),
(7, 'baklava-fistikli.jpg', 14),
(8, 'baklava-fistikli.jpg', 15),
(9, 'baklava-fistikli.jpg', 16),
(10, '070d1c8f0f49a0b1af358fe8912db87f38a17050.jpg', 20),
(11, 'strawberries.png', 21),
(12, 'bananas.png', 22),
(13, 'apples.png', 23),
(14, 'blueberries.png', 24),
(15, 'nectarine.png', 25),
(16, '1f281b9d64b63b50555629016e4eb11078912a13.png', 28),
(17, 'ede3a92e8d1b03ab51a6aac9a579a1a650121544.png', 29),
(18, '051779a557e87d16b6e0b2ea005bc5fea7e2b045.png', 30),
(19, '27c63ed82715f8b91ed64565dec2019d9b0ff565.png', 31),
(20, 'pistachios-750g.png', 33),
(21, 'walnuts-750-gr.png', 34),
(22, 'almonds-750g.png', 35),
(23, '347dbb6fc310ff72ed696816338a95dd707eed10.png', 36),
(24, 'lemons.png', 37),
(25, '420760a9bddd191f824e87c5a439231d092a8aa7.png', 38),
(26, 'almonds-750g.png', 39),
(27, 'almonds-750g.png', 40),
(28, 'almonds-750g.png', 41),
(29, 'walnuts-750-gr.png', 42),
(30, 'walnuts-750-gr.png', 43),
(31, 'walnuts-750-gr.png', 44),
(32, 'walnuts-750-gr.png', 45),
(33, 'pistachios-750g.png', 46),
(34, 'pistachios-750g.png', 47),
(35, 'pistachios-750g.png', 49),
(36, '4bdba3df5505a2b6618031027e61fc7da1f83a8d.png', 50),
(37, 'ec37c1aace2724481907879c902c1ee0f71c13af.png', 52),
(38, 'c4d346fe0c28046f359b2594eb6c2f53237308e4.png', 53),
(39, 'c4d346fe0c28046f359b2594eb6c2f53237308e4.png', 54);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `total` int(10) UNSIGNED DEFAULT NULL,
  `shipping` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `delivery_slot` varchar(60) DEFAULT NULL,
  `credit_card_number` mediumint(4) UNSIGNED ZEROFILL NOT NULL,
  `paid_status` varchar(60) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `total`, `shipping`, `delivery_slot`, `credit_card_number`, `paid_status`, `order_date`) VALUES
(1, 3, 2220, 270, NULL, 1234, NULL, '2018-01-06 11:47:42'),
(2, 21, 3130, 270, NULL, 1234, NULL, '2018-01-31 16:41:06'),
(4, 30, 1980, 0, '25 Feb 2018 at 9pm-10pm', 0000, NULL, '2018-02-18 18:06:28'),
(5, 52, 35, 0, NULL, 0000, NULL, '2018-03-10 19:57:50'),
(7, 58, 35, 0, '23 Mar 2018 at 7pm-8pm', 0000, NULL, '2018-03-11 14:26:08'),
(8, 40, 40, 0, '28 Jul 2018, 8pm-9pm', 1234, NULL, '2018-07-26 14:26:48'),
(9, 66, 28, 0, NULL, 1234, NULL, '2018-07-28 13:42:22'),
(10, 68, 38, 10, NULL, 1234, NULL, '2018-07-28 14:29:27'),
(11, 69, 40, 10, NULL, 1234, NULL, '2018-07-28 18:31:40'),
(12, 70, 4240, 10, NULL, 1234, NULL, '2018-07-28 19:36:49'),
(13, 71, 30, 10, NULL, 1234, NULL, '2018-07-29 15:46:21'),
(14, 72, 1450, 0, '11 Aug 2018 at 6pm-7pm', 0000, NULL, '2018-08-05 17:56:09'),
(15, 74, 700, 0, '11 Aug 2018 at 8pm-9pm', 0000, NULL, '2018-08-06 12:04:09'),
(16, 75, 1510, 0, '24 Aug 2018 at 7pm-8pm', 0000, NULL, '2018-08-17 20:32:54'),
(17, 77, 2840, 0, NULL, 0000, NULL, '2018-08-24 22:51:31'),
(18, 78, 309, 299, NULL, 1234, NULL, '2018-09-10 16:10:46'),
(19, 87, 15, 5, NULL, 1234, NULL, '2018-09-11 12:16:33'),
(20, 88, 35, 5, NULL, 1234, NULL, '2018-09-11 12:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_contents`
--

CREATE TABLE `order_contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_code` int(10) UNSIGNED DEFAULT NULL,
  `product_id` mediumint(8) UNSIGNED NOT NULL,
  `quantity` tinyint(3) UNSIGNED NOT NULL,
  `price_per` int(10) UNSIGNED NOT NULL,
  `ship_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_contents`
--

INSERT INTO `order_contents` (`id`, `order_id`, `product_code`, `product_id`, `quantity`, `price_per`, `ship_date`) VALUES
(1, 1, 9, 36, 1, 1350, NULL),
(2, 1, 9, 33, 1, 600, NULL),
(5, 4, 9, 42, 2, 675, NULL),
(6, 4, 9, 35, 1, 630, NULL),
(7, 5, 9, 39, 1, 35, NULL),
(14, 12, 9, 40, 1, 1330, NULL),
(15, 12, 9, 44, 1, 2880, NULL),
(16, 8, 9, 35, 1, 40, NULL),
(17, 9, 9, 35, 1, 28, NULL),
(18, 10, 9, 35, 1, 28, NULL),
(19, 11, 9, 35, 1, 30, NULL),
(20, 12, 9, 35, 1, 20, NULL),
(21, 13, 9, 35, 1, 20, NULL),
(22, 14, 9, 36, 1, 1450, NULL),
(23, 15, 9, 35, 1, 700, NULL),
(24, 16, 9, 43, 1, 1510, NULL),
(25, 17, 9, 33, 4, 710, NULL),
(26, 18, 9, 52, 1, 10, NULL),
(27, 19, 9, 52, 1, 10, NULL),
(28, 20, 9, 52, 1, 30, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `category_id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text,
  `image` varchar(45) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `stock` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `size` int(11) DEFAULT NULL,
  `product_code` varchar(30) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `image`, `price`, `stock`, `size`, `product_code`, `date_created`) VALUES
(5, 2, 'Pourakia', 'Description for pourakia goes here', 'pourakia.png', 400, 0, 1, '4', '2016-06-09 18:27:11'),
(9, 1, 'Loumidis Greek Coffee', NULL, 'greek-coffee-loumidis.png', 260, 0, 5, '2', '2016-06-13 10:41:25'),
(10, 1, 'Bravo Greek Coffee', NULL, 'greek-coffee-bravo.png', 250, 0, 5, '5', '2016-06-13 11:54:33'),
(11, 1, 'Loumidis Greek Coffee', NULL, 'greek-coffee-loumidis.png', 140, 0, 6, '2', '2016-06-13 11:57:06'),
(12, 1, 'Mehmet Efendi Turkish Coffee', NULL, 'turkish-coffee-500g.png', 299, 0, 1, '3', '2016-06-13 11:59:44'),
(13, 4, 'Baklava with nuts', 'The freshest baklava you can find online is here. We deliver next day once you place the order.', 'baklava-fistikli.jpg', 800, 5, 2, '6', '2016-06-22 10:36:56'),
(14, 4, 'Baklava with nuts', 'The freshest baklava you can find online is here. We deliver next day once you place the order.', 'baklava-fistikli.jpg', 1100, 10, 3, '6', '2016-06-22 10:36:56'),
(15, 4, 'Daily baked baklava', 'Baklava with nuts', 'baklava-fistikli.jpg', 1400, 10, 4, '6', '2016-06-22 10:39:49'),
(16, 4, 'Daily baked baklava', 'Daily baked baklava with nuts, very tasty.', 'baklava-fistikli.jpg', 1700, 5, 9, '6', '2016-06-22 10:39:49'),
(18, 4, 'sdfa', 'fghdfhdf dfgsdfgdsgdsfgewa sdf', 'ac3164fcda60043b615e04c237178405053d6be1.jpg', 32, 3, NULL, NULL, '2016-07-14 15:27:32'),
(20, 4, 'Yeni Baklava', 'The freshest online baklava you can get in Lodnon.', '070d1c8f0f49a0b1af358fe8912db87f38a17050.jpg', 10, 2, 4, '6', '2016-07-18 14:42:00'),
(21, 3, 'Strawberries 500g', 'Freshly sourced British Strawberries', 'strawberries.png', 200, 10, 10, '7', '2016-07-29 12:42:55'),
(22, 3, 'Bananas', 'Loose Fairtrade bananas', 'bananas.png', 90, 10, 16, '7', '2016-07-29 12:55:39'),
(23, 3, 'Apples', 'Apples 1kg ', 'apples.png', 195, 10, 16, '7', '2016-07-29 13:20:03'),
(24, 3, 'Blueberries', 'Blueberries 2 x 200gr', 'blueberries.png', 340, 10, 12, '7', '2016-07-29 13:26:55'),
(25, 3, 'Nectarines x5', 'Delicious Nectarines, 5 for only £1.60', 'nectarine.png', 160, 10, 11, '7', '2016-07-29 13:48:26'),
(28, 5, 'Cravendale Semi Skimmed', 'Cravendale Purefilter Semi Skimmed - 2Lt', '1f281b9d64b63b50555629016e4eb11078912a13.png', 180, 10, 13, '8', '2016-08-01 12:12:54'),
(29, 5, 'Lactofree Semi Skimmed Milk 1L', 'Lactofree Semi Skimmed Milk 1L', 'ede3a92e8d1b03ab51a6aac9a579a1a650121544.png', 140, 10, 14, '8', '2016-08-01 12:20:05'),
(30, 5, 'British Semi Skimmed Milk 568ml (1 pint)', 'British Semi Skimmed Milk 568ml (1 pint)', '051779a557e87d16b6e0b2ea005bc5fea7e2b045.png', 50, 10, 11, '8', '2016-08-01 12:23:00'),
(31, 5, 'British Whole Milk 568ml (1 pint)', 'British Whole Milk 568ml (1 pint)', '27c63ed82715f8b91ed64565dec2019d9b0ff565.png', 50, 10, 11, '8', '2016-08-01 12:25:12'),
(33, 6, 'Pistachios', '750g raw pistachios. Non-salted and non treated', 'pistachios-750g.png', 845, 15, 3, '9', '2017-06-28 15:17:45'),
(34, 6, 'Walnuts', 'Fresh walnut halves, unsalted. £9 per kg', 'walnuts-750-gr.png', 770, 10, 3, '9', '2017-08-12 16:46:03'),
(35, 6, 'Raw Almonds', 'Raw almonds, unsalted. ', 'almonds-750g.png', 700, 15, 3, '9', '2017-08-12 16:51:23'),
(36, 6, 'Walnuts - Fresh', 'Fresh walnut. %80 halves, %20 pieces. £9 per kg', '347dbb6fc310ff72ed696816338a95dd707eed10.png', 1496, 10, 15, '9', '2017-08-16 12:39:21'),
(37, 3, 'Lemons x6', 'Fresh lemons x6 pieces', 'lemons.png', 150, 10, 11, '7', '2017-08-28 12:29:50'),
(38, 6, 'Walnut Pieces', 'Fresh walnut pieces (broken). Perfect for baking and snacking. £7.50 per 1kg.', '420760a9bddd191f824e87c5a439231d092a8aa7.png', 1290, 10, 15, '9', '2017-09-02 14:20:33'),
(39, 8, 'Almonds Raw', 'Unroasted, raw almonds with no additives, preservatives or added salt ', 'almonds-750g.png', 650, 10, 3, '9', '2017-11-26 16:44:31'),
(40, 8, 'Almonds Raw', 'Unroasted Almonds with No Additives, Preservatives or Added salt.', 'almonds-750g.png', 1350, 5, 15, '9', '2017-11-26 16:50:00'),
(41, 8, 'Almonds Raw', 'Unroasted Almonds with No Additives, Preservatives or Added salt.', 'almonds-750g.png', 2690, 0, 18, '9', '2017-11-26 16:56:48'),
(42, 9, 'Walnuts Kernels', 'Fresh walnut kernels. %80 halves, %20 pieces.', 'walnuts-750-gr.png', 745, 10, 3, '9', '2017-11-26 17:22:28'),
(43, 9, 'Walnuts Halves', 'Fresh walnut kernels. %80 halves, %20 pieces.', 'walnuts-750-gr.png', 1540, 10, 15, '9', '2017-11-26 17:22:28'),
(44, 9, 'Walnuts Halves', 'High quality Raw Walnut Kernel Halves. %80 halves, %20 pieces', 'walnuts-750-gr.png', 3076, 10, 18, '9', '2017-11-26 17:31:55'),
(45, 9, 'Walnut pieces', 'Walnut kernel pieces (broken). No Additives, Preservatives or Added salt.', 'walnuts-750-gr.png', 675, 10, 3, '9', '2017-11-26 17:47:55'),
(46, 10, 'Pistachios', 'Unsalted Pistachios. No additives or preservatives.', 'pistachios-750g.png', 845, 10, 3, '9', '2017-12-04 15:11:50'),
(47, 10, 'Pistachios', 'No additives or preservatives.', 'pistachios-750g.png', 1670, 10, 15, '9', '2017-12-10 17:50:05'),
(49, 10, 'Pistachios', 'Unsalted Pistachios. No additives or preservatives', 'pistachios-750g.png', 3245, 10, 18, '9', '2017-12-10 17:55:57'),
(50, 3, 'Pomegranates x 2', '2 large size pomegranates', '4bdba3df5505a2b6618031027e61fc7da1f83a8d.png', 230, 10, 11, '7', '2018-01-01 16:35:39'),
(52, 11, 'Organic Hazelnuts', 'Roasted, organic hazelnuts from Turkey\'s Black Sea region. ', 'ec37c1aace2724481907879c902c1ee0f71c13af.png', 1130, 20, 16, '9', '2018-02-25 16:30:28'),
(53, 11, 'Organic Hazelnuts', 'Roasted organic hazelnuts, best price!', 'c4d346fe0c28046f359b2594eb6c2f53237308e4.png', 2200, 10, 20, '9', '2018-02-28 14:16:08'),
(54, 6, 'Organic Hazelnuts', 'Roasted organic hazelnuts, best price!', 'c4d346fe0c28046f359b2594eb6c2f53237308e4.png', 2200, 10, 20, '9', '2018-02-28 14:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `product_code`
--

CREATE TABLE `product_code` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(164) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_code`
--

INSERT INTO `product_code` (`id`, `name`, `description`) VALUES
(1, 'TC1', 'Fresh daily roasted Turkish coffee daily roasted'),
(2, 'TC2', 'Loumidis Turkish coffee'),
(3, 'TC3', 'Turkish coffee mehmet efendi'),
(4, 'CONFEC1', 'Greek confectionery'),
(5, 'TC4', 'Bravo Greek Turkish Coffee'),
(6, 'BAKL', 'Freshly baked baklava'),
(7, 'FRUVEG', 'Fruits and vegetables'),
(8, 'MILK', 'Milk Products'),
(9, 'NUTS', 'Fresh nuts');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` mediumint(8) UNSIGNED NOT NULL,
  `product_code` int(10) DEFAULT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE `signup` (
  `signup_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` char(128) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `address1` varchar(180) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `postcode` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `confirm_code` varchar(60) NOT NULL DEFAULT '',
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `signup`
--

INSERT INTO `signup` (`signup_id`, `username`, `password`, `salt`, `email`, `address1`, `city`, `postcode`, `first_name`, `last_name`, `telephone`, `confirm_code`, `time_created`) VALUES
(1, '', '$4y$11$2YRVqySfNFOVOmy8WmM9pOiiP7Po8aRhx9nBctS54ClefVc6Qo/De', NULL, 'irfanssd@outlook.com', 'Cranwich Road', 'London', 'n16 5hz', 'irfan', 'kissa', '0742sdf179', '3120748669b5886a34bda6f6a3a111e7', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `size` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `size`) VALUES
(15, '1.5kg'),
(4, '1000gr'),
(7, '1000gr - 25 pieces'),
(14, '1000ML'),
(6, '100gr'),
(16, '1kg'),
(9, '2000gr'),
(13, '2000ML'),
(5, '200gr'),
(19, '2250g'),
(1, '250gr'),
(20, '2kg'),
(17, '340g'),
(18, '3kg'),
(12, '400gr'),
(2, '500gr'),
(8, '500gr - 12 pieces'),
(3, '750gr'),
(10, '800gr'),
(11, 'pieces');

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` int(10) UNSIGNED NOT NULL,
  `slot_name` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`id`, `slot_name`) VALUES
(1, '6pm-7pm'),
(2, '7pm-8pm'),
(3, '8pm-9pm'),
(4, '9pm-10pm');

-- --------------------------------------------------------

--
-- Table structure for table `testing`
--

CREATE TABLE `testing` (
  `id` int(11) NOT NULL,
  `name` varchar(600) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testing`
--

INSERT INTO `testing` (`id`, `name`) VALUES
(1, 'irfan kissa'),
(2, 'somethin mroe htereh'),
(3, NULL),
(4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` char(128) NOT NULL,
  `salt` varchar(150) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `type` enum('member','admin') NOT NULL DEFAULT 'member',
  `address1` varchar(80) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `postcode` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_expires` date DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `salt`, `email`, `type`, `address1`, `city`, `postcode`, `first_name`, `last_name`, `telephone`, `date_created`, `date_expires`, `date_modified`) VALUES
(1, '', '$sydf0dddddddddddddddddddddddddddddD3LfPw/pHODfIha1XKfJe.Ie', NULL, 'irfan@outlook.com', 'member', 'Cranwich Road', 'London', 'n16 5hz', 'irfan', 'kissa', '07429244179', '2018-04-23 15:31:11', NULL, '2018-04-23 15:57:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_tokens`
--
ALTER TABLE `access_tokens`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_type` (`product_id`),
  ADD KEY `user_session_id` (`user_session_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `charges`
--
ALTER TABLE `charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `charge_id` (`charge_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `days`
--
ALTER TABLE `days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `days_date` (`day`);

--
-- Indexes for table `days_slots`
--
ALTER TABLE `days_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prod_id` (`prod_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_date` (`order_date`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_contents`
--
ALTER TABLE `order_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ship_date` (`ship_date`),
  ADD KEY `product_code` (`product_code`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_code`
--
ALTER TABLE `product_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `start_date` (`start_date`);

--
-- Indexes for table `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`signup_id`),
  ADD UNIQUE KEY `confirm_code` (`confirm_code`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `login` (`email`,`password`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `size` (`size`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testing`
--
ALTER TABLE `testing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `login` (`email`,`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `charges`
--
ALTER TABLE `charges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `days`
--
ALTER TABLE `days`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `days_slots`
--
ALTER TABLE `days_slots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `order_contents`
--
ALTER TABLE `order_contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `product_code`
--
ALTER TABLE `product_code`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `signup`
--
ALTER TABLE `signup`
  MODIFY `signup_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `testing`
--
ALTER TABLE `testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
