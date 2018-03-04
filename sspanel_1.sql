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
