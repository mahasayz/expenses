-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2013 at 09:59 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `expense`
--
CREATE DATABASE IF NOT EXISTS `expense` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `expense`;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE IF NOT EXISTS `expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E287B43AA76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`id`, `user_id`, `amount`, `description`, `created`) VALUES
(6, 1, '23.00', 'sg', '2013-09-30 12:04:17'),
(7, 1, '25.00', 'gdgdg', '2013-10-28 12:04:42'),
(8, 2, '53.00', 'gfrgrg', '2013-10-28 12:08:47'),
(9, 1, '65.00', 'fgrgerg', '2013-10-29 07:54:53'),
(10, 1, '92.00', 'Halim', '2013-10-29 09:42:48'),
(11, 1, '200.00', 'Burger', '2013-10-29 09:42:56'),
(12, 1, '120.00', 'Transport cost', '2013-10-29 09:45:13'),
(13, 1, '10.00', 'Rickshaw cost', '2013-10-29 09:45:33'),
(14, 1, '20.00', 'Minibus cost', '2013-10-29 09:45:45'),
(15, 1, '50.00', 'Coke', '2013-10-29 09:46:09'),
(16, 1, '70.00', 'lunch: nun and baji', '2013-10-29 10:17:10'),
(17, 3, '23.00', 'dsffsdf', '2013-10-29 13:13:27'),
(18, 3, '32.00', 'web we', '2013-10-29 13:13:38'),
(19, 3, '12.00', 'rr rr', '2013-10-29 13:14:03'),
(20, 4, '3.00', 'ee', '2013-10-29 13:15:02'),
(21, 4, '4.00', 'gg', '2013-10-29 13:15:21'),
(22, 2, '32.20', 'fe', '2013-10-30 08:36:29'),
(23, 2, '4.00', 'fff', '2013-10-30 08:43:09'),
(24, 2, '32.00', 'gds', '2013-10-30 09:40:31');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F75B255457698A6A` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `role`) VALUES
(1, 'Role for customer.. show owner', 'CUSTOMER');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `countryCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstName`, `lastName`, `username`, `password`, `salt`, `countryCode`) VALUES
(1, 'Alim', NULL, 'alim', '5421251672d67b11ac42e86234c52c35c7d59c6f', 'a', 'USD'),
(2, 'Nowshad', NULL, 'maha', '5421251672d67b11ac42e86234c52c35c7d59c6f', 'a', 'BDT'),
(3, 'aa', 'aa', 'aa', '37104bc6908e3e13df5020afca8ecf46c09561f0', 'df10065514.5', ''),
(4, 'ee', 'ee', 'ee', 'bf0c2ffcfb7054b5ce2e3f09c215906c1909780a', 'df10065514.5', ''),
(5, 'rrr', 'rrr', 'rrr', 'f66c18123a103dd297e49ef39d30cc94d2e99b3b', 'df10065515', 'AUD'),
(6, 'ee', 'ee', 'ee', '221954b6b93b53c9ef6c2e7852cab6bfd9c1b7ed', 'df10065515', 'BDT');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  KEY `IDX_2DE8C6A3D60322AC` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `FK_E287B43AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2DE8C6A3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
