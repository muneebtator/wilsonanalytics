-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 18, 2014 at 06:38 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appname` longtext NOT NULL,
  `applocation` longtext NOT NULL,
  `appicon` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `apps`
--

INSERT INTO `apps` (`id`, `appname`, `applocation`, `appicon`) VALUES
(1, 'GeoMe', 'apps/geome/index.html', 'geome/icon.png');

-- --------------------------------------------------------

--
-- Table structure for table `datastore`
--

CREATE TABLE IF NOT EXISTS `datastore` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `data` longtext NOT NULL,
  `storekey` longtext NOT NULL,
  `cate` longtext NOT NULL,
  `website` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `name`) VALUES
(1, 'iOS'),
(2, 'Android'),
(3, 'Windows'),
(4, 'Mac');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page` longtext NOT NULL,
  `website` bigint(20) NOT NULL,
  `hits` bigint(20) NOT NULL,
  `title` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `referrer`
--

CREATE TABLE IF NOT EXISTS `referrer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `refwebsite` longtext NOT NULL,
  `website` int(11) NOT NULL,
  `totalrefs` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `searchengines`
--

CREATE TABLE IF NOT EXISTS `searchengines` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `hits` bigint(20) NOT NULL,
  `website` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'username', 'admin'),
(2, 'password', ''),
(3, 'firstrun', 'true'),
(4, 'rootloc', ''),
(5, 'salt', ''),
(6, 'secret', '');

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `website` int(11) NOT NULL,
  `device` longtext NOT NULL,
  `hits` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `traffic`
--

CREATE TABLE IF NOT EXISTS `traffic` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `website` int(11) NOT NULL,
  `fullurl` longtext NOT NULL,
  `page` longtext NOT NULL,
  `ref` longtext NOT NULL,
  `kala` longtext NOT NULL,
  `refid` bigint(20) NOT NULL,
  `device` varchar(90) NOT NULL,
  `pagetitle` longtext NOT NULL,
  `smallurl` longtext NOT NULL,
  `newvisitor` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `websites`
--

CREATE TABLE IF NOT EXISTS `websites` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` longtext NOT NULL,
  `url` longtext NOT NULL,
  `origin` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
