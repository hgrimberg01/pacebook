-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 06, 2013 at 05:10 PM
-- Server version: 5.5.34-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qchen`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessLevels`
--

DROP TABLE IF EXISTS `accessLevels`;
CREATE TABLE IF NOT EXISTS `accessLevels` (
  `accessLevelID` int(11) NOT NULL AUTO_INCREMENT,
  `accessLevelDesc` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `accessLevelParentID` int(11) NOT NULL,
  PRIMARY KEY (`accessLevelID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(17) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` tinytext COLLATE utf8_unicode_ci,
  `last_activity` bigint(20) DEFAULT NULL,
  `user_data` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friendship`
--

DROP TABLE IF EXISTS `friendship`;
CREATE TABLE IF NOT EXISTS `friendship` (
  `firstUserId` int(11) NOT NULL,
  `secondUserId` int(11) NOT NULL,
  `isApproved` tinyint(1) NOT NULL DEFAULT '0',
  `approvalDate` datetime DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`firstUserId`,`secondUserId`),
  KEY `secondUserId` (`secondUserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `globalAccessGrants`
--

DROP TABLE IF EXISTS `globalAccessGrants`;
CREATE TABLE IF NOT EXISTS `globalAccessGrants` (
  `userId` int(11) NOT NULL,
  `userAccessLevel` int(11) NOT NULL,
  `grantDateTime` datetime NOT NULL,
  PRIMARY KEY (`userId`,`userAccessLevel`),
  UNIQUE KEY `userId` (`userId`,`userAccessLevel`),
  KEY `userAccessLevel` (`userAccessLevel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `imageID` int(11) NOT NULL AUTO_INCREMENT,
  `imageURL` text COLLATE utf8_unicode_ci NOT NULL,
  `imageDesc` text COLLATE utf8_unicode_ci,
  `imageCreationDate` date NOT NULL,
  PRIMARY KEY (`imageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `loginLog`
--

DROP TABLE IF EXISTS `loginLog`;
CREATE TABLE IF NOT EXISTS `loginLog` (
  `userID` int(13) NOT NULL,
  `userLoginDateTime` datetime NOT NULL,
  `userLoginUserAgent` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `networkMembership`
--

DROP TABLE IF EXISTS `networkMembership`;
CREATE TABLE IF NOT EXISTS `networkMembership` (
  `networkID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `accessLevel` int(11) NOT NULL,
  `requestDate` date NOT NULL,
  `approvalDate` date NOT NULL,
  `approvedByUserID` int(11) NOT NULL,
  PRIMARY KEY (`networkID`,`userID`),
  KEY `userID` (`userID`),
  KEY `approvedByUserID` (`approvedByUserID`),
  KEY `accessLevel` (`accessLevel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `networks`
--

DROP TABLE IF EXISTS `networks`;
CREATE TABLE IF NOT EXISTS `networks` (
  `networkID` int(11) NOT NULL AUTO_INCREMENT,
  `networkName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `networkDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `networkCreationDate` datetime NOT NULL,
  `networkProfileImgID` int(11) DEFAULT NULL,
  `networkIsActive` tinyint(1) NOT NULL DEFAULT '0',
  `networkApprovalDate` date NOT NULL,
  `networkApprovedByUserID` int(11) NOT NULL,
  `Note` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`networkID`),
  KEY `networkApprovedByUserID` (`networkApprovedByUserID`),
  KEY `networkProfileImgID` (`networkProfileImgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE IF NOT EXISTS `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userAddress` text COLLATE utf8_unicode_ci NOT NULL,
  `userPhone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userPassword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userCreationDate` datetime NOT NULL,
  `userActive` tinyint(1) NOT NULL DEFAULT '1',
  `userImageID` int(11) DEFAULT NULL,
  `userEmail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userName` (`userName`),
  UNIQUE KEY `userEmail` (`userEmail`),
  KEY `userImageID` (`userImageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `friendship_ibfk_1` FOREIGN KEY (`firstUserId`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friendship_ibfk_2` FOREIGN KEY (`secondUserId`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `globalAccessGrants`
--
ALTER TABLE `globalAccessGrants`
  ADD CONSTRAINT `globalAccessGrants_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `globalAccessGrants_ibfk_2` FOREIGN KEY (`userAccessLevel`) REFERENCES `accessLevels` (`accessLevelID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `networkMembership`
--
ALTER TABLE `networkMembership`
  ADD CONSTRAINT `networkMembership_ibfk_1` FOREIGN KEY (`networkID`) REFERENCES `networks` (`networkID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `networkMembership_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `networkMembership_ibfk_3` FOREIGN KEY (`approvedByUserID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `networkMembership_ibfk_4` FOREIGN KEY (`accessLevel`) REFERENCES `accessLevels` (`accessLevelID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `networkMembership_ibfk_5` FOREIGN KEY (`accessLevel`) REFERENCES `accessLevels` (`accessLevelID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `networks`
--
ALTER TABLE `networks`
  ADD CONSTRAINT `networks_ibfk_1` FOREIGN KEY (`networkApprovedByUserID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `networks_ibfk_2` FOREIGN KEY (`networkProfileImgID`) REFERENCES `images` (`imageID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `networks_ibfk_3` FOREIGN KEY (`networkProfileImgID`) REFERENCES `images` (`imageID`);

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`userImageID`) REFERENCES `images` (`imageID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Users_ibfk_2` FOREIGN KEY (`userImageID`) REFERENCES `images` (`imageID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
