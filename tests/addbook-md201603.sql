-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2016 at 02:21 PM
-- Server version: 5.6.28-0ubuntu0.15.10.1
-- PHP Version: 5.6.11-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `addbook-md`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `Id` int(11) NOT NULL,
  `city` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT COMMENT='1 is for unspecified city';

--
-- RELATIONS FOR TABLE `cities`:
--

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`Id`, `city`) VALUES
(1, ' '),
(5, 'Aahalden'),
(4, 'Aarüti'),
(8, 'Köniz'),
(7, 'Lausanne'),
(9, 'Neuchâtel'),
(6, 'Zurich');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `Id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` text COLLATE utf8mb4_unicode_ci,
  `zip_code` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='For the demo address book application - Makhtar Diouf';

--
-- RELATIONS FOR TABLE `contacts`:
--   `city_id`
--       `cities` -> `Id`
--

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`Id`, `name`, `first_name`, `street`, `zip_code`, `city_id`) VALUES
(23, 'James', 'Wade CA1', 'Boulevard ouest', 12345, 7),
(29, 'Hello', 'CC1', 'Testing street', 234, 1),
(30, 'Olivia', 'Therese CB2', 'Towards group B street', 0, 9),
(31, 'Lin', 'Torvalds CC2', 'Philadelphia 123', 456, 5),
(32, 'Rubio', 'Trump CB1', 'Croisade', 9876, 1),
(33, 'Holly', 'Michou CD1', 'On the D street', 0, 1),
(34, 'Makhtar', ' Diouf CD2', 'Rue de l&#39;entente', 12345, 4),
(36, 'Wonder', 'Man CA2', 'street 2100009332', 71, 7),
(37, 'Test edcAb', 'ecbAd', 'street 304041015', 35, 7);

-- --------------------------------------------------------

--
-- Table structure for table `contacts_groups`
--

CREATE TABLE IF NOT EXISTS `contacts_groups` (
  `Cgid` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Many to Many relationship between Contacts and Group';

--
-- RELATIONS FOR TABLE `contacts_groups`:
--   `contact_id`
--       `contacts` -> `Id`
--   `group_id`
--       `groups` -> `Id`
--

--
-- Dumping data for table `contacts_groups`
--

INSERT INTO `contacts_groups` (`Cgid`, `contact_id`, `group_id`) VALUES
(1, 23, 5),
(4, 29, 8),
(5, 23, 9),
(6, 30, 6),
(7, 31, 8),
(8, 32, 6),
(9, 33, 1),
(10, 34, 1),
(13, 36, 5),
(14, 36, 9);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `Id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `parent_id` int(11) DEFAULT '0' COMMENT 'Parent group id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- RELATIONS FOR TABLE `groups`:
--

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`Id`, `name`, `parent_id`) VALUES
(1, 'GroupD', 8),
(5, 'GroupA', 0),
(6, 'GroupB', 0),
(8, 'GroupC', 5),
(9, 'GroupAA', 0),
(15, 'GroupTest225983913', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `city` (`city`),
  ADD KEY `Id` (`Id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `contacts_groups`
--
ALTER TABLE `contacts_groups`
  ADD PRIMARY KEY (`Cgid`),
  ADD KEY `contacts_id` (`contact_id`),
  ADD KEY `groups_id` (`group_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `Id` (`Id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contacts_groups`
--
ALTER TABLE `contacts_groups`
  MODIFY `Cgid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contacts_groups`
--
ALTER TABLE `contacts_groups`
  ADD CONSTRAINT `contacts_groups_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contacts_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
