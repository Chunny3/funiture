-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-04 08:14:51
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
-- 資料庫： `my_db`
--

-- --------------------------------------------------------

--
-- 資料表結構 `msgs`
--

CREATE TABLE `msgs` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `img` varchar(50) DEFAULT NULL,
  `createTime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `is_valid` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `msgs`
--

INSERT INTO `msgs` (`id`, `name`, `category_id`, `content`, `img`, `createTime`, `update_at`, `end_at`, `is_valid`) VALUES
(1, 'Ben', 2, '第一則貼文哈哈', '1748827201.jpeg', '2025-05-23 16:08:50', '2025-06-02 09:20:01', NULL, 1),
(2, 'Ben', 3, '順利儲值了', NULL, '2025-05-23 16:11:35', '2025-05-28 10:19:55', NULL, 0),
(3, 'Mary', NULL, '今天天氣很好', NULL, '2025-05-23 16:14:06', NULL, '2025-05-27 11:14:57', 1),
(4, 'Ben', 3, '轉圈圈', NULL, '2025-05-23 16:23:27', '2025-05-28 10:20:02', NULL, 0),
(5, '123', NULL, '123', NULL, '2025-05-26 09:13:00', NULL, '2025-05-27 11:15:04', 1),
(6, '123', 2, '123', '1748834316.jpeg', '2025-05-26 09:17:48', '2025-06-02 11:18:36', NULL, 1),
(7, '111', 3, '111\r\n', NULL, '2025-05-26 09:17:57', '2025-05-28 10:20:13', NULL, 1),
(8, '222', 2, '222', NULL, '2025-05-26 09:24:37', '2025-05-28 10:20:19', NULL, 1),
(9, '333', 4, '333', NULL, '2025-05-26 09:29:25', '2025-05-28 10:20:30', NULL, 1),
(10, 'Ben', 2, '沒有淋到雨', '1748490507.jpeg', '2025-05-26 10:28:41', '2025-05-29 11:48:27', NULL, 1),
(11, 'Jay', 4, '沒有遇到雨', '1748490523.jpeg', '2025-05-26 10:28:41', '2025-05-29 11:48:43', NULL, 1),
(12, 'ken', 3, '沒有看到雨', NULL, '2025-05-26 10:28:41', '2025-05-28 10:20:51', NULL, 1),
(13, 'Bee', 1, '沒有玩到雨', NULL, '2025-05-26 10:28:41', '2025-05-28 10:20:58', NULL, 1),
(14, 'Anna', 3, 'hello', NULL, '2025-05-26 14:06:19', '2025-05-28 10:21:05', NULL, 1),
(15, 'Anna', 4, 'Hello Anna', NULL, '2025-05-26 14:08:01', '2025-05-28 10:21:14', NULL, 1),
(16, 'Anna', 4, 'Hello World', NULL, '2025-05-26 14:08:33', '2025-05-28 10:21:28', NULL, 1),
(17, 'Eddy', 1, 'Good Day', NULL, '2025-05-26 14:10:22', '2025-05-28 10:22:06', NULL, 1),
(18, 'starlle', 4, '垃圾筒在哪', NULL, '2025-05-26 14:16:31', '2025-05-28 10:22:14', NULL, 1),
(19, 'Ellen', 1, '開始檢查欄位', NULL, '2025-05-26 16:17:12', '2025-05-28 10:22:21', NULL, 1),
(23, 'keinn', 4, 'tennjyo', NULL, '2025-05-28 09:15:25', '2025-05-28 10:22:31', NULL, 1),
(24, 'aja', 2, 'ohaiyounn', NULL, '2025-05-28 09:15:25', '2025-05-28 10:22:38', NULL, 1),
(25, 'jushua', 1, 'kusogaki', NULL, '2025-05-28 09:15:25', '2025-05-28 10:22:45', NULL, 1),
(26, 'oto', 1, 'usagi', NULL, '2025-05-28 09:36:37', NULL, NULL, 1),
(27, 'kiruru', 1, 'gaia', NULL, '2025-05-28 09:36:37', NULL, NULL, 1),
(28, 'a子', 2, '肚子餓', NULL, '2025-05-28 09:37:04', '2025-05-28 10:02:40', NULL, 1),
(29, 'B子', 2, '漢堡', NULL, '2025-05-28 09:38:25', NULL, NULL, 1),
(30, 'C子', 2, '可樂', NULL, '2025-05-28 09:38:25', NULL, NULL, 1),
(31, 'D子', 4, '漫畫', NULL, '2025-05-28 09:38:25', NULL, NULL, 1),
(32, 'E子', 3, '看小說', NULL, '2025-05-28 09:38:25', NULL, NULL, 1),
(33, 'F子', 4, '耍廢', NULL, '2025-05-28 09:38:25', NULL, NULL, 1),
(34, 'Bibi', 1, '哈囉', NULL, '2025-05-28 11:21:25', NULL, NULL, 1),
(35, 'Aki', 1, 'sky', '1748488989.jpeg', '2025-05-29 11:23:09', NULL, NULL, 1),
(36, 'Ben', 1, '外面好吵', '1748489059.jpeg', '2025-05-29 11:24:19', NULL, NULL, 1),
(37, 'Ben', 4, '下雨了', '1748490635.jpeg', '2025-05-29 11:24:19', '2025-05-29 11:50:35', NULL, 1),
(38, 'Anna', 2, 'go to beach', NULL, '2025-05-29 11:30:31', NULL, NULL, 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `msgs`
--
ALTER TABLE `msgs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `msgs`
--
ALTER TABLE `msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `msgs`
--
ALTER TABLE `msgs`
  ADD CONSTRAINT `msgs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
