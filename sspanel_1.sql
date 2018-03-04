-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-03-04 19:32:19
-- 服务器版本： 5.5.42-log
-- PHP Version: 5.5.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sspanel`
--

-- --------------------------------------------------------

--
-- 表的结构 `ss_invite_url`
--

CREATE TABLE `ss_invite_url` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invited_user_id` int(11) NOT NULL,
  `plus_time` int(11) NOT NULL,
  `plus_bandwidth` int(11) NOT NULL,
  `plus_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ss_invite_url`
--

INSERT INTO `ss_invite_url` (`id`, `user_id`, `invited_user_id`, `plus_time`, `plus_bandwidth`, `plus_date`) VALUES
(1, 1, 1158, 0, 102, '0000-00-00 00:00:00'),
(2, 1, 1158, 37, 102, '2018-02-05 18:22:47'),
(3, 1, 1158, 3, 5, '2018-02-05 18:22:52'),
(4, 1, 1162, 37, 102, '2018-02-07 13:46:02'),
(5, 1128, 1184, 3, 5, '2018-02-23 23:33:57'),
(6, 1134, 1192, 3, 5, '2018-02-27 19:05:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ss_invite_url`
--
ALTER TABLE `ss_invite_url`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ss_invite_url`
--
ALTER TABLE `ss_invite_url`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
