-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2016 at 05:01 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `clean`
--

-- --------------------------------------------------------

--
-- Table structure for table `cirms_sessions`
--

CREATE TABLE IF NOT EXISTS `cirms_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE IF NOT EXISTS `classrooms` (
  `classroom_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`classroom_id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `clusters`
--

CREATE TABLE IF NOT EXISTS `clusters` (
  `cluster_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `cluster_code` varchar(255) NOT NULL,
  `cluster_name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`cluster_id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

CREATE TABLE IF NOT EXISTS `computers` (
  `computer_id` int(11) NOT NULL AUTO_INCREMENT,
  `computer_name` varchar(255) NOT NULL,
  `computer_type` varchar(255) NOT NULL,
  `brand_clone_name` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`computer_id`),
  UNIQUE KEY `computer_name` (`computer_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `computer_designation`
--

CREATE TABLE IF NOT EXISTS `computer_designation` (
  `computer_id` int(11) NOT NULL,
  `designation_type` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `workstation_no` int(11) NOT NULL,
  `assigned_to` varchar(255) NOT NULL,
  `date_assigned` varchar(255) NOT NULL,
  PRIMARY KEY (`computer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `computer_resources`
--

CREATE TABLE IF NOT EXISTS `computer_resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_no` int(11) NOT NULL,
  `computer_name` varchar(255) NOT NULL,
  `activities` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_no` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_no` (`room_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_order`
--

CREATE TABLE IF NOT EXISTS `service_order` (
  `ref_no` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(255) NOT NULL,
  `emp_name` varchar(255) NOT NULL,
  `cluster_id` int(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `complaint_resource_id` int(11) NOT NULL,
  `complaint_details` varchar(255) NOT NULL,
  `computer_name` varchar(255) NOT NULL,
  `if_pulled_out` tinyint(1) NOT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ref_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_order_acceptance`
--

CREATE TABLE IF NOT EXISTS `service_order_acceptance` (
  `ref_no` int(11) NOT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `assigned_to` int(255) NOT NULL,
  `designate_to` int(225) DEFAULT NULL,
  `date_reported` varchar(255) NOT NULL,
  `time_reported` varchar(255) NOT NULL,
  PRIMARY KEY (`ref_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `service_order_completion`
--

CREATE TABLE IF NOT EXISTS `service_order_completion` (
  `ref_no` int(11) NOT NULL,
  `date_finished` varchar(255) DEFAULT NULL,
  `time_finished` varchar(255) DEFAULT NULL,
  `date_replaced` varchar(2555) NOT NULL,
  `time_replaced` varchar(255) NOT NULL,
  `completed_by` int(11) NOT NULL,
  `unit_status` varchar(255) NOT NULL DEFAULT 'under repair',
  `action_taken` varchar(255) DEFAULT NULL,
  `returned_to` varchar(255) DEFAULT NULL,
  `property_clerk` varchar(255) DEFAULT NULL,
  `property_date_received` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  PRIMARY KEY (`ref_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `service_order_timeline`
--

CREATE TABLE IF NOT EXISTS `service_order_timeline` (
  `ref_no` int(11) NOT NULL,
  `if_inverted` tinyint(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ref_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `access_rights` varchar(255) DEFAULT NULL,
  `cluster_id` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'assets/images/avatars/default_profile.png',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `contact_no` varchar(255) DEFAULT NULL,
  `last_login` varchar(255) DEFAULT NULL,
  `if_pass_changed` tinyint(4) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_id` (`emp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `emp_id`, `password`, `firstname`, `lastname`, `user_type`, `access_rights`, `cluster_id`, `avatar`, `status`, `contact_no`, `last_login`, `if_pass_changed`, `date_added`) VALUES
(1, 'CIT121', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Rolando', 'Tindoy', 'superadmin', 'ultimate_control', '5', 'assets/images/avatars/default_profile.png', 'active', '111', '2016-02-15 12:03:51', 0, '2016-01-16 06:23:02');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `classrooms_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `clusters`
--
ALTER TABLE `clusters`
  ADD CONSTRAINT `clusters_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `computer_designation`
--
ALTER TABLE `computer_designation`
  ADD CONSTRAINT `computer_designation_ibfk_1` FOREIGN KEY (`computer_id`) REFERENCES `computers` (`computer_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_order_acceptance`
--
ALTER TABLE `service_order_acceptance`
  ADD CONSTRAINT `service_order_acceptance_ibfk_1` FOREIGN KEY (`ref_no`) REFERENCES `service_order` (`ref_no`) ON DELETE CASCADE;

--
-- Constraints for table `service_order_completion`
--
ALTER TABLE `service_order_completion`
  ADD CONSTRAINT `service_order_completion_ibfk_1` FOREIGN KEY (`ref_no`) REFERENCES `service_order` (`ref_no`) ON DELETE CASCADE;

--
-- Constraints for table `service_order_timeline`
--
ALTER TABLE `service_order_timeline`
  ADD CONSTRAINT `service_order_timeline_ibfk_1` FOREIGN KEY (`ref_no`) REFERENCES `service_order` (`ref_no`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
