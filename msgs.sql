-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-04 08:16:25
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
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `is_valid` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `msgs`
--

INSERT INTO `msgs` (`id`, `name`, `category_id`, `content`, `img`, `create_at`, `update_at`, `end_at`, `is_valid`) VALUES
(1, 'ben', NULL, '第一則貼文哈哈', NULL, '2025-05-27 11:56:14', NULL, '2025-05-27 11:57:04', 1),
(2, 'jolin', 1, '旋轉', '1748827537.jpg', '2025-05-28 10:02:36', '2025-06-02 11:37:35', NULL, 1),
(3, '蔡依林', 3, '跳躍', NULL, '2025-05-28 10:02:50', '2025-05-28 11:04:00', NULL, 1),
(4, '青峰', 4, '這是一首簡單的小情歌', NULL, '2025-05-28 11:13:41', NULL, NULL, 1),
(5, '周杰倫', 1, '這個網站真的很好用，希望功能可以再多一點！', NULL, '2025-05-28 11:21:37', NULL, NULL, 1),
(6, '林佳慧', 2, '留言測試123，請忽略這則訊息。', NULL, '2025-05-28 11:21:48', NULL, NULL, 1),
(7, '阿宏', 4, '請問留言可以修改嗎？找不到編輯的地方。', NULL, '2025-05-28 11:22:09', NULL, NULL, 1),
(8, 'Alex Wang', 2, '自學程式設計的高中生，正在開發自己的第一款手機遊戲。\r\n', NULL, '2025-05-28 13:34:32', NULL, NULL, 1),
(9, 'Emily Lin	', 3, '創業中的服裝設計師，主打永續時尚與天然染料。\r\n', NULL, '2025-05-28 13:35:37', NULL, NULL, 1),
(10, 'Brian Ho	', 1, '科技公司行銷主管，對咖啡有深入研究，是當地手沖比賽冠軍。\r\n', NULL, '2025-05-28 13:35:47', NULL, NULL, 1),
(11, '吳佩珊	', 2, '雜誌編輯，擅長採訪與攝影，近期籌備自己的獨立刊物。\r\n\r\n', NULL, '2025-05-28 13:35:56', NULL, NULL, 1),
(12, '吳佩珊	', 1, '雜誌編輯，擅長採訪與攝影，近期籌備自己的獨立刊物。', NULL, '2025-05-28 13:36:12', NULL, NULL, 1),
(13, '哈囉', 2, '你好嗎', NULL, '2025-05-28 15:00:42', NULL, NULL, 1),
(14, '哈窩', 1, '你好嗎', NULL, '2025-05-28 15:01:03', NULL, NULL, 1),
(15, '吳佩珊', 1, 'c8', '1748488972jpg', '2025-05-29 11:22:52', NULL, NULL, 1),
(16, 'ariana', 2, '美國', '1748489023jpg', '2025-05-29 11:23:43', NULL, NULL, 1),
(17, 'grande', 2, '大峽谷', '1748489024jpg', '2025-05-29 11:23:43', NULL, NULL, 1),
(18, '吳佩珊', 3, '大', NULL, '2025-05-29 11:29:38', NULL, NULL, 1),
(19, '蔡依林', 1, '小', '1748489379jpg', '2025-05-29 11:29:38', NULL, NULL, 1),
(20, '蔡依林', 1, '123', '1748489569jpg', '2025-05-29 11:32:49', NULL, NULL, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
