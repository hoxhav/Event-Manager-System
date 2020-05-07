-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 06, 2019 at 10:01 PM
-- Server version: 5.6.37
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendee`
--
DROP TABLE IF EXISTS `attendee` ;

CREATE TABLE IF NOT EXISTS `attendee` (
  `idattendee` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendee`
--

INSERT INTO `attendee` (`idattendee`, `name`, `password`, `role`) VALUES
(1, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1),
(2, 'tomas', '46badf9a864a3b0ea2f3bf0f0d30b0c665b168818fb373a21586d75d34b767b3', 3),
(3, 'david', '0f14089313b20c1723ec1d660b0aaa4f473cf5b321cd370f2d48b7bcf9a7b234', 3),
(4, 'johni', 'cf273f3f773e44ff7902a2f72ccd7537e1ac9d78a6f5b45f8aa2f3cf8ec5aa94', 2),
(5, 'peter', 'fd82f0e95c8034cfeacd4fb4d2853d50749364f1c98f780158aa3196fed7d0d7', 2);

-- --------------------------------------------------------

--
-- Table structure for table `attendee_event`
--
DROP TABLE IF EXISTS `attendee_event` ;

CREATE TABLE IF NOT EXISTS `attendee_event` (
  `event` int(11) NOT NULL,
  `attendee` int(11) NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendee_event`
--

INSERT INTO `attendee_event` (`event`, `attendee`, `paid`) VALUES
(1, 1, 0),
(2, 1, 0),
(2, 4, 0),
(3, 4, 0),
(4, 4, 0),
(2, 5, 0),
(4, 5, 0),
(5, 5, 0),
(6, 5, 0),
(2, 2, 0),
(5, 2, 0),
(3, 2, 0),
(6, 2, 0),
(6, 3, 0),
(3, 3, 0),
(4, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `attendee_session`
--
DROP TABLE IF EXISTS `attendee_session` ;


CREATE TABLE IF NOT EXISTS `attendee_session` (
  `session` int(11) NOT NULL,
  `attendee` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendee_session`
--

INSERT INTO `attendee_session` (`session`, `attendee`) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 2),
(3, 4),
(3, 5),
(4, 1),
(4, 2),
(4, 4),
(4, 5),
(5, 1),
(5, 2),
(5, 4),
(5, 5),
(6, 2),
(6, 3),
(6, 4),
(7, 2),
(7, 3),
(7, 4),
(8, 3),
(8, 4),
(8, 5),
(9, 2),
(9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event` ;


CREATE TABLE IF NOT EXISTS `event` (
  `idevent` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `datestart` datetime NOT NULL,
  `dateend` datetime NOT NULL,
  `numberallowed` int(11) NOT NULL,
  `venue` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`idevent`, `name`, `datestart`, `dateend`, `numberallowed`, `venue`) VALUES
(1, 'Welcome from admin', '2019-10-20 13:45:00', '2019-10-20 17:45:00', 675000, 1),
(2, 'Greeting Ceremony', '2019-10-21 00:00:00', '2019-10-22 00:00:00', 10500, 2),
(3, 'John Birthday', '2019-11-20 00:00:00', '2019-11-20 00:00:00', 10000, 2),
(4, 'Hello There', '2020-10-20 00:00:00', '2020-10-20 00:00:00', 312313, 1),
(5, 'Teste peter', '2019-10-20 13:45:00', '2019-10-20 17:45:00', 13121, 1),
(6, 'sadsasad d', '2019-10-20 13:45:00', '2019-10-20 17:45:00', 123123, 2);

-- --------------------------------------------------------

--
-- Table structure for table `manager_event`
--
DROP TABLE IF EXISTS `manager_event` ;

CREATE TABLE IF NOT EXISTS `manager_event` (
  `event` int(11) NOT NULL,
  `manager` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manager_event`
--

INSERT INTO `manager_event` (`event`, `manager`) VALUES
(1, 1),
(2, 1),
(3, 4),
(4, 4),
(5, 5),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--
DROP TABLE IF EXISTS `role` ;

CREATE TABLE IF NOT EXISTS `role` (
  `idrole` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`idrole`, `name`) VALUES
(1, 'admin'),
(2, 'event manager'),
(3, 'attendee');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--
DROP TABLE IF EXISTS `session` ;

CREATE TABLE IF NOT EXISTS `session` (
  `idsession` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `numberallowed` int(11) NOT NULL,
  `event` int(11) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`idsession`, `name`, `numberallowed`, `event`, `startdate`, `enddate`) VALUES
(1, 'Warm up', 60040, 1, '2019-10-20 00:00:00', '2019-10-20 00:00:00'),
(2, 'Dinner', 30500, 1, '2019-10-20 00:00:00', '2019-10-20 00:00:00'),
(3, 'Music Concert', 5500, 2, '2019-10-21 00:00:00', '2019-10-22 00:00:00'),
(4, 'Theatre', 4000, 2, '2019-10-20 00:00:00', '2019-10-20 00:00:00'),
(5, 'GoodBye', 10000, 2, '2019-10-22 00:00:00', '2019-10-22 00:00:00'),
(6, 'Before', 1323, 3, '2019-10-20 13:45:00', '2019-10-20 17:45:00'),
(7, 'test', 21331, 3, '2019-10-20 13:45:00', '2019-10-20 17:45:00'),
(8, 'sdaasas test', 3211, 4, '2019-10-20 13:45:00', '2019-10-20 17:45:00'),
(9, 'testerrr', 1132, 5, '2019-10-20 13:45:00', '2019-10-20 17:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--
DROP TABLE IF EXISTS `venue` ;

CREATE TABLE IF NOT EXISTS `venue` (
  `idvenue` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`idvenue`, `name`, `capacity`) VALUES
(1, 'Wembley Stadium', 90000),
(2, 'Boom Stadium', 87500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendee`
--
ALTER TABLE `attendee`
  ADD PRIMARY KEY (`idattendee`),
  ADD KEY `role_idx` (`role`);

--
-- Indexes for table `attendee_event`
--
ALTER TABLE `attendee_event`
  ADD PRIMARY KEY (`event`,`attendee`);

--
-- Indexes for table `attendee_session`
--
ALTER TABLE `attendee_session`
  ADD PRIMARY KEY (`session`,`attendee`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idevent`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD KEY `venue_fk_idx` (`venue`);

--
-- Indexes for table `manager_event`
--
ALTER TABLE `manager_event`
  ADD PRIMARY KEY (`event`,`manager`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`idrole`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`idsession`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`idvenue`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendee`
--
ALTER TABLE `attendee`
  MODIFY `idattendee` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `idevent` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `idrole` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `idsession` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `idvenue` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
