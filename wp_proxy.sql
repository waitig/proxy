-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-08-20 15:44:21
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wordpress`
--

-- --------------------------------------------------------

--
-- 表的结构 `wp_proxy`
--

CREATE TABLE IF NOT EXISTS `wp_proxy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `IP` varchar(20) COLLATE utf8_bin NOT NULL,
  `port` int(6) NOT NULL,
  `location` varchar(20) COLLATE utf8_bin NOT NULL,
  `anonymous` varchar(20) COLLATE utf8_bin NOT NULL,
  `type` varchar(20) COLLATE utf8_bin NOT NULL,
  `speed` double NOT NULL,
  `connection` double NOT NULL,
  `survival_time` varchar(20) COLLATE utf8_bin NOT NULL,
  `verification_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IP` (`IP`,`port`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
