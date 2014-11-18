-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 28, 2014 at 11:13 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `address_books`
--

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `city_name`, `description`) VALUES
(1, 'Ho Chi Minh', 'Ho Chi Minh City'),
(2, 'Da Nang', 'Da Nang city'),
(3, 'Ha Noi', 'Ha Noi capital');

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `id_city`, `name`, `first_name`, `street`, `zip_code`, `created`, `modified`) VALUES
(1, 2, 'CA1', 'Nguyen', '60 Le Duan, Street', '50000', '2014-04-28 03:45:25', '2014-04-28 03:45:25'),
(2, 1, 'CA2', 'Nguyen', '100 Collarado', '50000', '2014-04-28 03:45:44', '2014-04-28 03:45:44'),
(3, 3, 'CB1', 'Nguyen', '120 Collarado', '50000', '2014-04-28 03:46:30', '2014-04-28 03:46:30'),
(4, 2, 'CB2', 'Withneys', '10 Tran phu', '44444', '2014-04-28 03:47:03', '2014-04-28 03:47:03'),
(5, 2, 'CC1', 'Steven', '150 Long Beach', '44444', '2014-04-28 03:47:47', '2014-04-28 03:47:47'),
(6, 2, 'CC2', 'Tran', 'K10/6 Yen Bai, Street', '50000', '2014-04-28 03:48:14', '2014-04-28 03:48:14'),
(7, 2, 'CD1', 'Nguyen Huu', '45 Pastuer, street', '50000', '2014-04-28 03:48:58', '2014-04-28 03:48:58'),
(8, 2, 'CD2', 'Test', '40 Le Duan, Street', '50000', '2014-04-28 03:49:29', '2014-04-28 03:49:29');

--
-- Dumping data for table `contacts_groups`
--

INSERT INTO `contacts_groups` (`id`, `id_contact`, `id_group`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2),
(5, 3, 3),
(6, 4, 3),
(7, 5, 4),
(8, 6, 4),
(9, 7, 5),
(10, 8, 5);

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `description`) VALUES
(1, 'Group A', 'Group A description'),
(2, 'Group AA', 'This is content same contacts in Group A'),
(3, 'Group B', 'Same level with Group A'),
(4, 'Group C', 'Inherit Group A'),
(5, 'Group D', 'This is inherit Group C, AA, B');

--
-- Dumping data for table `group_relations`
--

INSERT INTO `group_relations` (`id`, `id_group`, `id_parent`) VALUES
(2, 4, 1),
(3, 5, 2),
(4, 5, 3),
(5, 5, 4);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
