-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-04 08:14:56
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
-- 資料表結構 `activies`
--

CREATE TABLE `activies` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `msg_id` int(11) DEFAULT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `activies`
--

INSERT INTO `activies` (`id`, `name`, `msg_id`, `start_at`, `end_at`) VALUES
(1, '123', 1, '2025-06-02 00:00:00', '2025-06-02 00:00:00'),
(2, 'LI', 1, '2025-06-03 00:00:00', '2025-06-03 00:00:00'),
(3, 'helo', 1, '2025-06-04 00:00:00', '2025-06-04 00:00:00');

-- --------------------------------------------------------

--
-- 資料表結構 `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, '家電', '家用電器相關'),
(2, '家具', '各類家具商品'),
(3, '文具', '辦公與學習用品');

-- --------------------------------------------------------

--
-- 資料表結構 `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `teacher` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `courses`
--

INSERT INTO `courses` (`id`, `title`, `teacher`) VALUES
(1, '資料科學概論', '王老師'),
(2, '國文寫作', '林老師'),
(3, '微積分', '陳老師');

-- --------------------------------------------------------

--
-- 資料表結構 `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enroll_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `enroll_date`) VALUES
(1, 1, 1, '2024-09-01'),
(2, 1, 2, '2024-09-01'),
(3, 2, 1, '2024-09-02'),
(4, 3, 3, '2024-09-03');

-- --------------------------------------------------------

--
-- 資料表結構 `imgs`
--

CREATE TABLE `imgs` (
  `id` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `file` varchar(50) NOT NULL,
  `is_valid` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `imgs`
--

INSERT INTO `imgs` (`id`, `msg_id`, `file`, `is_valid`) VALUES
(1, 1, '1748844092.jpeg', 1),
(2, 1, '1748844116.jpeg', 1),
(3, 1, '1748844135.jpeg', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `msgs`
--

CREATE TABLE `msgs` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `img` varchar(50) DEFAULT NULL,
  `createTime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `msgs`
--

INSERT INTO `msgs` (`id`, `name`, `content`, `img`, `createTime`, `update_at`, `end_at`, `category_id`) VALUES
(1, '你好', '123', '1748831823.jpeg', '2025-05-26 12:45:51', '2025-06-02 10:37:03', NULL, 1),
(2, '李時珍', '本草綱目', NULL, '2025-05-28 15:01:42', '2025-06-02 11:24:33', NULL, 3),
(3, 'coffee', '咖啡甜點', NULL, '2025-06-02 09:19:33', NULL, NULL, 2),
(4, '水水', '水水愛拍照', '1748827264.jpeg', '2025-06-02 09:21:04', NULL, NULL, 1),
(5, '螢幕', '螢幕顯示時間', '1748827385.jpeg', '2025-06-02 09:23:05', NULL, NULL, 1),
(6, '漫漫', '輕婚紗', '1748827480.jpeg', '2025-06-02 09:24:40', NULL, NULL, 2),
(7, '灰貓', '灰色貓咪', '1748828509.jpeg', '2025-06-02 09:41:49', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `text` varchar(200) DEFAULT NULL,
  `msg_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `replies`
--

INSERT INTO `replies` (`id`, `text`, `msg_id`) VALUES
(1, '123', 1),
(2, '222', 1),
(3, '123', 1),
(4, '', 1),
(5, '123', 1),
(6, '123', 1),
(7, '123', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `songs`
--

CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `author` varchar(20) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `start_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `songs`
--

INSERT INTO `songs` (`id`, `author`, `title`, `start_at`) VALUES
(1, NULL, NULL, NULL),
(2, NULL, NULL, NULL),
(3, NULL, NULL, NULL),
(4, 'betty', '1111', '2025-05-26 00:00:00');

-- --------------------------------------------------------

--
-- 資料表結構 `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `students`
--

INSERT INTO `students` (`id`, `name`, `grade`) VALUES
(1, '小安', 10),
(2, '小美', 11),
(3, '阿傑', 12),
(4, '阿德', 10);

-- --------------------------------------------------------

--
-- 資料表結構 `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `is_vaild` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `tags`
--

INSERT INTO `tags` (`id`, `name`, `is_vaild`) VALUES
(1, '你好', 1),
(2, 'HI', 1),
(3, '52222', 1),
(4, '52222', 1),
(5, '15151', 1),
(6, '15151', 1),
(7, '15151', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(30) NOT NULL,
  `img` varchar(30) DEFAULT NULL,
  `create_at` datetime DEFAULT current_timestamp(),
  `is_valid` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `img`, `create_at`, `is_valid`) VALUES
(1, 'ben@ben.com', '$2y$10$Lk9zwUCyhAbKS6uqWVb/Wu0S1RY0vrppGnLm9v8OFnWgKBAmQzy5K', 'ben chen', '1748930723.jpeg', '2025-06-03 14:05:23', 1),
(2, 'may@may.com', '$2y$10$1iXIsqUmFf8U0S6VGyKPp.X9KdgzAfz.kQyc/f7/4z3A0hw3l75UK', 'MayMayMay', '1748930787.jpeg', '2025-06-03 14:06:27', 1),
(3, 'ben2@ben.com', '$2y$10$zlKZDN8zb7srToEWB58g7.ZRheF8rb/ARndA29SRMu.CYIt0nP0be', 'Ben 2', NULL, '2025-06-03 15:04:47', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `activies`
--
ALTER TABLE `activies`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- 資料表索引 `imgs`
--
ALTER TABLE `imgs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `msg_id` (`msg_id`);

--
-- 資料表索引 `msgs`
--
ALTER TABLE `msgs`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `activies`
--
ALTER TABLE `activies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `imgs`
--
ALTER TABLE `imgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `msgs`
--
ALTER TABLE `msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- 資料表的限制式 `imgs`
--
ALTER TABLE `imgs`
  ADD CONSTRAINT `imgs_ibfk_1` FOREIGN KEY (`msg_id`) REFERENCES `msgs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
