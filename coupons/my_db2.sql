-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-13 10:53:49
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `my_db2`
--USE `my_db2`;
USE `my_db2`;
SET FOREIGN_KEY_CHECKS = 0;
SET FOREIGN_KEY_CHECKS = 1;


-- --------------------------------------------------------

--
-- 資料表結構 `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `discount_type` tinyint(1) NOT NULL,
  `discount` decimal(7,2) NOT NULL,
  `min_discount` int(11) DEFAULT 0,
  `max_amount` int(11) DEFAULT NULL,
  `start_at` date DEFAULT NULL,
  `end_at` date DEFAULT NULL,
  `valid_days` int(11) DEFAULT NULL,
  `is_valid` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `code`, `discount_type`, `discount`, `min_discount`, `max_amount`, `start_at`, `end_at`, `valid_days`, `is_valid`, `created_at`) VALUES
(1, '新年家居折扣券', 'KSHUHPW8', 1, 120.00, 600, NULL, '2024-02-01', '2024-03-01', NULL, 1, '2025-06-13 13:28:18'),
(2, '夏日超值折扣券', 'EPIXFGK7', 2, 0.80, 0, NULL, NULL, NULL, 20, 1, '2025-06-13 13:29:06'),
(3, '全館家電折扣', '9ZGRQX69', 1, 180.00, 700, NULL, '2025-07-01', '2025-07-15', NULL, 1, '2025-06-13 13:29:52'),
(4, '床墊新品促銷', 'SFRUX1UU', 2, 0.85, 300, 500, '2024-10-01', '2024-11-01', NULL, 1, '2025-06-13 13:31:19'),
(5, '會員專屬折價券', '2O0K6F6Q', 1, 250.00, 800, NULL, NULL, NULL, 25, 1, '2025-06-13 13:31:56'),
(6, '餐廳家電超值折扣', '6FU9TCQB', 1, 90.00, 400, NULL, '2025-05-25', '2025-06-25', NULL, 1, '2025-06-13 13:32:43'),
(7, '親子空間促銷券', '5SOUQVV7', 2, 0.75, 0, NULL, NULL, NULL, 30, 1, '2025-06-13 13:33:25'),
(8, '全館清倉折扣', 'A3NF5SXQ', 1, 400.00, 2000, NULL, '2024-07-10', '2024-08-10', NULL, 1, '2025-06-13 13:34:24'),
(9, '新會員歡迎禮', 'RLMEGULB', 2, 0.90, 0, NULL, '2025-06-28', '2025-07-20', NULL, 1, '2025-06-13 13:35:26'),
(10, '家具滿額現折', 'P9GNXFPM', 1, 300.00, 1500, 400, '2025-05-15', '2025-06-20', NULL, 1, '2025-06-13 13:37:20'),
(11, '年度特賣券', '36W7BM33', 1, 200.00, 500, 80, '2024-12-01', '2025-01-10', NULL, 1, '2025-06-13 13:39:27'),
(12, '夏季家具8折券', '67S1N46E', 2, 0.80, 0, NULL, '2025-06-10', '2025-07-10', NULL, 1, '2025-06-13 13:40:29'),
(13, '辦公空間專屬券', 'VCSSK4M7', 1, 150.00, 600, 300, '2025-07-01', '2025-07-20', NULL, 1, '2025-06-13 13:41:22'),
(14, '家具新品體驗券', 'FA56SJSK', 1, 100.00, 300, NULL, NULL, NULL, 15, 1, '2025-06-13 13:42:25'),
(15, '臥室床架折扣券', '3G4Q3G1W', 1, 250.00, 1000, 400, '2025-05-25', '2025-06-30', NULL, 1, '2025-06-13 13:43:09'),
(16, '親子房限量優惠券', 'H31HS6MR', 2, 0.85, 200, 90, '2024-08-01', '2024-09-01', NULL, 1, '2025-06-13 13:44:21'),
(17, '餐廳廚房現金券', '3KJ9C4UY', 1, 120.00, 400, NULL, '2025-06-05', '2025-06-25', NULL, 1, '2025-06-13 13:44:59'),
(18, '原木家具會員專屬券', 'VAB79GO1', 2, 0.75, 500, 400, NULL, NULL, 20, 1, '2025-06-13 13:45:51'),
(19, '客廳沙發超值券', 'WTOAHZBE', 1, 300.00, 1200, 1200, '2024-11-15', '2024-12-15', NULL, 1, '2025-06-13 13:46:40'),
(20, '木芽會員滿額折扣券', 'KADKA8KX', 2, 0.90, 500, 320, '2025-06-13', '2025-07-20', NULL, 1, '2025-06-13 13:47:43'),
(21, '全館會員尊享券', 'CBFR3LK4', 1, 150.00, 500, 2000, '2024-09-01', '2024-10-01', NULL, 1, '2025-06-13 13:49:38'),
(22, '新會員歡迎折扣', 'XH6IC0HO', 2, 0.85, 0, NULL, NULL, NULL, 30, 1, '2025-06-13 13:50:12'),
(23, '臥室床墊現折券', 'BOZ19BE6', 1, 200.00, 1000, 100, '2025-05-10', '2025-06-15', NULL, 1, '2025-06-13 13:50:41'),
(24, '兒童房家居優惠券', '4KTBBWOX', 2, 0.80, 300, 600, '2025-07-01', '2025-07-25', NULL, 1, '2025-06-13 13:51:27'),
(25, '廚房家電現折券', 'W12USGY5', 1, 100.00, 400, NULL, '2025-06-05', '2025-07-05', NULL, 1, '2025-06-13 13:52:19'),
(26, '原木會員專屬8折券', 'OQ3E8RJM', 2, 0.80, 500, 1000, NULL, NULL, 20, 1, '2025-06-13 13:52:43'),
(27, '全館滿額現折券', 'MZCKP7J1', 1, 300.00, 1500, 5000, '2024-10-15', '2024-11-15', NULL, 1, '2025-06-13 13:53:35'),
(28, '會員專屬限量現金券', 'DOSRF2RP', 1, 180.00, 600, 150, '2025-06-15', '2025-07-15', NULL, 1, '2025-06-13 13:54:27'),
(29, '家具清倉折扣券', 'O9ALQ4P9', 1, 250.00, 1000, NULL, '2024-07-20', '2024-08-20', NULL, 1, '2025-06-13 13:55:27'),
(30, '辦公空間滿額8折券', 'QCVSR1K4', 2, 0.80, 800, 1000, '2025-07-05', '2025-07-25', NULL, 1, '2025-06-13 13:56:10'),
(31, '全館任選9折券', '6DDUINGI', 2, 0.90, 300, NULL, '2025-06-01', '2025-06-30', NULL, 1, '2025-06-13 14:06:36'),
(32, '收納用品專屬現折券', '6QBB82H5', 1, 120.00, 400, 450, '2024-10-01', '2024-11-01', NULL, 1, '2025-06-13 14:14:21'),
(33, '會員專屬滿額折扣券', 'DQI0W808', 1, 300.00, 1500, NULL, NULL, NULL, 7, 1, '2025-06-13 14:15:07'),
(34, '兒童房超值折扣券', 'MQPCG24K', 2, 0.75, 200, 180, '2025-07-10', '2025-07-31', NULL, 1, '2025-06-13 14:15:54'),
(35, '全館清倉特賣券', 'U7S5VZHU', 1, 400.00, 2000, 1100, '2024-08-10', '2024-09-10', NULL, 1, '2025-06-13 14:17:01'),
(36, '客廳家電現金券', 'S8OC6VOY', 1, 180.00, 600, NULL, '2025-06-10', '2025-07-15', NULL, 1, '2025-06-13 14:17:38'),
(37, '原木家具專屬券', '8MLIZM96', 2, 0.80, 500, 600, NULL, NULL, 14, 1, '2025-06-13 14:18:12'),
(38, '餐廳廚房滿額現折券', 'NEPTRRU3', 1, 150.00, 700, 30, '2025-07-01', '2025-07-20', NULL, 1, '2025-06-13 14:18:43'),
(39, '會員限定高額折扣券', 'FUIE0P27', 1, 500.00, 2500, 50, '2024-07-25', '2024-08-25', NULL, 1, '2025-06-13 14:19:29'),
(40, '全館自由折扣券', 'AZ93120Q', 2, 0.85, 300, NULL, NULL, NULL, 30, 1, '2025-06-13 14:25:30'),
(41, '全館家具折扣券', '5XMCV1PK', 1, 200.00, 500, 250, '2025-06-01', '2025-06-30', NULL, 1, '2025-06-13 14:27:17'),
(42, '兒童房限量優惠券', '6K0SBMTB', 2, 0.80, 300, 30, '2025-07-05', '2025-07-25', NULL, 1, '2025-06-13 14:27:57'),
(43, '全館自由現折券', 'SYF7JOGB', 1, 150.00, 400, NULL, NULL, NULL, 21, 1, '2025-06-13 14:28:56'),
(44, '餐廳廚房現金優惠券', 'WT37E058', 1, 100.00, 500, 50, '2024-08-15', '2024-09-15', NULL, 1, '2025-06-13 14:29:39'),
(45, '家具超值9折券', 'CDLSZC2B', 2, 0.90, 600, NULL, '2025-06-10', '2025-07-10', NULL, 1, '2025-06-13 14:30:15'),
(46, '會員限定高額現折券', '6UAYH101', 1, 400.00, 2000, 30, '2024-07-20', '2024-08-20', NULL, 1, '2025-06-13 14:31:08'),
(47, '親子空間滿額現折券', '0ZNHXJBM', 1, 180.00, 800, 400, '2025-07-01', '2025-07-20', NULL, 1, '2025-06-13 14:31:59'),
(48, '會員專屬折扣碼', 'PLYXFTGD', 2, 0.85, 500, NULL, NULL, NULL, 7, 1, '2025-06-13 14:32:29'),
(49, '全館清倉現折券', '5MFIYPLC', 1, 300.00, 1500, 150, '2025-01-01', '2025-02-28', NULL, 1, '2025-06-13 14:33:20'),
(50, '辦公空間滿額8折券', 'MAZM03B4', 2, 0.80, 700, 45, '2025-06-25', '2025-07-25', NULL, 1, '2025-06-13 14:34:02'),
(51, '全館快閃限量券', 'IHG3K9UN', 1, 250.00, 1000, 35, '2025-02-01', '2025-03-31', NULL, 1, '2025-06-13 14:35:20'),
(52, '木芽會員滿額9折券', 'ECJ4UP3G', 2, 0.90, 500, NULL, NULL, NULL, 30, 1, '2025-06-13 14:35:54'),
(53, '餐廳家電限時優惠券', 'JUNHGT88', 1, 180.00, 600, 280, '2025-06-10', '2025-07-31', NULL, 1, '2025-06-13 14:36:49'),
(54, '親子房新品促銷券', 'S14I8JHS', 1, 200.00, 700, 1300, '2025-07-05', '2025-07-25', NULL, 1, '2025-06-13 14:37:35'),
(55, '全館自由使用85折券', 'T6PGZCJW', 2, 0.85, 300, NULL, NULL, NULL, 21, 1, '2025-06-13 14:38:18');

-- --------------------------------------------------------

--
-- 資料表結構 `coupon_categories`
--

CREATE TABLE `coupon_categories` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `coupon_categories`
--

INSERT INTO `coupon_categories` (`id`, `coupon_id`, `category_id`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 1, 5),
(4, 2, 4),
(5, 2, 6),
(6, 3, 1),
(7, 3, 2),
(8, 3, 5),
(9, 3, 6),
(10, 4, 3),
(11, 4, 4),
(12, 5, 1),
(13, 5, 2),
(14, 5, 3),
(15, 5, 4),
(16, 5, 5),
(17, 5, 6),
(18, 6, 2),
(19, 6, 6),
(20, 7, 2),
(21, 7, 3),
(22, 7, 4),
(23, 8, 1),
(24, 8, 2),
(25, 8, 3),
(26, 8, 4),
(27, 8, 5),
(28, 8, 6),
(29, 9, 1),
(30, 9, 2),
(31, 9, 3),
(32, 9, 4),
(33, 9, 5),
(34, 9, 6),
(35, 10, 2),
(36, 10, 3),
(37, 10, 4),
(38, 11, 1),
(39, 11, 2),
(40, 12, 3),
(41, 12, 6),
(42, 13, 5),
(43, 14, 1),
(44, 14, 2),
(45, 14, 3),
(46, 14, 4),
(47, 14, 5),
(48, 14, 6),
(49, 15, 3),
(50, 16, 4),
(51, 17, 2),
(52, 17, 6),
(53, 18, 1),
(54, 18, 5),
(55, 19, 1),
(56, 20, 1),
(57, 20, 2),
(58, 20, 3),
(59, 20, 4),
(60, 20, 5),
(61, 20, 6),
(62, 21, 1),
(63, 21, 2),
(64, 21, 6),
(65, 22, 1),
(66, 22, 2),
(67, 22, 3),
(68, 22, 4),
(69, 22, 5),
(70, 22, 6),
(71, 23, 3),
(72, 24, 4),
(73, 24, 6),
(74, 25, 2),
(75, 25, 5),
(76, 25, 6),
(77, 26, 1),
(78, 26, 2),
(79, 26, 3),
(80, 26, 4),
(81, 26, 5),
(82, 26, 6),
(83, 27, 1),
(84, 27, 2),
(85, 27, 3),
(86, 28, 2),
(87, 28, 3),
(88, 28, 4),
(89, 28, 5),
(90, 29, 1),
(91, 29, 5),
(92, 29, 6),
(93, 30, 5),
(94, 30, 6),
(95, 31, 1),
(96, 31, 6),
(97, 32, 6),
(98, 33, 1),
(99, 33, 2),
(100, 33, 3),
(101, 33, 4),
(102, 33, 5),
(103, 33, 6),
(104, 34, 4),
(105, 35, 1),
(106, 35, 2),
(107, 35, 3),
(108, 35, 5),
(109, 36, 1),
(110, 36, 2),
(111, 37, 1),
(112, 37, 2),
(113, 37, 3),
(114, 37, 4),
(115, 37, 5),
(116, 37, 6),
(117, 38, 2),
(118, 38, 6),
(119, 39, 1),
(120, 39, 5),
(121, 40, 1),
(122, 40, 2),
(123, 40, 3),
(124, 40, 4),
(125, 40, 5),
(126, 40, 6),
(127, 41, 1),
(128, 41, 2),
(129, 41, 3),
(130, 42, 4),
(131, 43, 1),
(132, 43, 2),
(133, 43, 3),
(134, 43, 4),
(135, 43, 5),
(136, 43, 6),
(137, 44, 2),
(138, 44, 6),
(139, 45, 1),
(140, 45, 2),
(141, 45, 3),
(142, 45, 5),
(143, 46, 1),
(144, 46, 5),
(145, 46, 6),
(146, 47, 2),
(147, 47, 3),
(148, 47, 4),
(149, 48, 1),
(150, 48, 2),
(151, 48, 3),
(152, 48, 4),
(153, 48, 5),
(154, 48, 6),
(155, 49, 1),
(156, 49, 2),
(157, 49, 6),
(158, 50, 5),
(159, 50, 6),
(160, 51, 1),
(161, 51, 2),
(162, 51, 6),
(163, 52, 1),
(164, 52, 2),
(165, 52, 3),
(166, 52, 4),
(167, 52, 5),
(168, 52, 6),
(169, 53, 2),
(170, 53, 5),
(171, 53, 6),
(172, 54, 2),
(173, 54, 3),
(174, 54, 4),
(175, 55, 1),
(176, 55, 2),
(177, 55, 3),
(178, 55, 4),
(179, 55, 5),
(180, 55, 6);

-- --------------------------------------------------------

--
-- 資料表結構 `coupon_levels`
--

CREATE TABLE `coupon_levels` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `coupon_levels`
--

INSERT INTO `coupon_levels` (`id`, `coupon_id`, `level_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 2),
(5, 3, 1),
(6, 3, 2),
(7, 3, 3),
(8, 4, 2),
(9, 4, 3),
(10, 5, 2),
(11, 5, 3),
(12, 6, 3),
(13, 7, 1),
(14, 7, 2),
(15, 8, 1),
(16, 8, 2),
(17, 8, 3),
(18, 9, 1),
(19, 9, 2),
(20, 9, 3),
(21, 10, 1),
(22, 10, 2),
(23, 11, 1),
(24, 11, 2),
(25, 11, 3),
(26, 12, 2),
(27, 12, 3),
(28, 13, 3),
(29, 14, 1),
(30, 15, 2),
(31, 15, 3),
(32, 16, 2),
(33, 17, 3),
(34, 18, 2),
(35, 19, 1),
(36, 19, 2),
(37, 19, 3),
(38, 20, 1),
(39, 21, 1),
(40, 21, 2),
(41, 21, 3),
(42, 22, 1),
(43, 23, 1),
(44, 23, 2),
(45, 23, 3),
(46, 24, 3),
(47, 25, 2),
(48, 25, 3),
(49, 26, 2),
(50, 27, 1),
(51, 27, 2),
(52, 27, 3),
(53, 28, 1),
(54, 28, 2),
(55, 28, 3),
(56, 29, 1),
(57, 29, 2),
(58, 29, 3),
(59, 30, 2),
(60, 30, 3),
(61, 31, 1),
(62, 31, 2),
(63, 32, 2),
(64, 32, 3),
(65, 33, 1),
(66, 33, 2),
(67, 33, 3),
(68, 34, 3),
(69, 35, 1),
(70, 35, 2),
(71, 35, 3),
(72, 36, 2),
(73, 36, 3),
(74, 37, 2),
(75, 38, 3),
(76, 39, 1),
(77, 39, 2),
(78, 40, 1),
(79, 40, 2),
(80, 40, 3),
(81, 41, 1),
(82, 41, 2),
(83, 41, 3),
(84, 42, 3),
(85, 43, 2),
(86, 43, 3),
(87, 44, 2),
(88, 45, 1),
(89, 45, 2),
(90, 45, 3),
(91, 46, 1),
(92, 46, 2),
(93, 47, 2),
(94, 47, 3),
(95, 48, 1),
(96, 48, 2),
(97, 48, 3),
(98, 49, 1),
(99, 49, 2),
(100, 49, 3),
(101, 50, 2),
(102, 50, 3),
(103, 51, 1),
(104, 51, 2),
(105, 51, 3),
(106, 52, 1),
(107, 53, 2),
(108, 53, 3),
(109, 54, 1),
(110, 54, 2),
(111, 55, 1),
(112, 55, 2),
(113, 55, 3);

-- --------------------------------------------------------

--
-- 資料表結構 `member_levels`
--

CREATE TABLE `member_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `member_levels`
--

INSERT INTO `member_levels` (`id`, `name`) VALUES
(1, '木芽會員'),
(2, '原木會員'),
(3, '森林會員');

-- --------------------------------------------------------

--
-- 資料表結構 `products_category`
--

CREATE TABLE `products_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `products_category`
--

INSERT INTO `products_category` (`category_id`, `category_name`) VALUES
(1, '客廳'),
(2, '餐廳/廚房'),
(3, '臥室'),
(4, '兒童房'),
(5, '辦公空間'),
(6, '收納用品');

-- --------------------------------------------------------

--
-- 資料表結構 `user_coupons`
--

CREATE TABLE `user_coupons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `get_at` datetime DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- 資料表索引 `coupon_categories`
--
ALTER TABLE `coupon_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `category_id` (`category_id`);

--
-- 資料表索引 `coupon_levels`
--
ALTER TABLE `coupon_levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `level_id` (`level_id`);

--
-- 資料表索引 `member_levels`
--
ALTER TABLE `member_levels`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `products_category`
--
ALTER TABLE `products_category`
  ADD PRIMARY KEY (`category_id`);

--
-- 資料表索引 `user_coupons`
--
ALTER TABLE `user_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `coupon_categories`
--
ALTER TABLE `coupon_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `coupon_levels`
--
ALTER TABLE `coupon_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `member_levels`
--
ALTER TABLE `member_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `products_category`
--
ALTER TABLE `products_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user_coupons`
--
ALTER TABLE `user_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `coupon_categories`
--
ALTER TABLE `coupon_categories`
  ADD CONSTRAINT `coupon_categories_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`),
  ADD CONSTRAINT `coupon_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `products_category` (`category_id`);

--
-- 資料表的限制式 `coupon_levels`
--
ALTER TABLE `coupon_levels`
  ADD CONSTRAINT `coupon_levels_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`),
  ADD CONSTRAINT `coupon_levels_ibfk_2` FOREIGN KEY (`level_id`) REFERENCES `member_levels` (`id`);

--
-- 資料表的限制式 `user_coupons`
--
ALTER TABLE `user_coupons`
  ADD CONSTRAINT `user_coupons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_coupons_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
