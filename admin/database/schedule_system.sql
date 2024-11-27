-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2023 at 05:16 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schedule_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `allowance_list`
--

CREATE TABLE `allowance_list` (
  `payslip_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `amount` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `allowance_list`
--

INSERT INTO `allowance_list` (`payslip_id`, `name`, `amount`) VALUES
(2, 'Gas Allowance', 1500),
(2, 'Rice', 500),
(2, 'Overtime', 540),
(3, 'Allowance 101', 1000),
(3, 'Allowance 102', 1500),
(3, 'Allowance 101', 2000),
(4, 'aw', 12),
(5, 'awaw', 321),
(41, 'dada', 222),
(45, 'aw', 213);

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `author` varchar(500) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`id`, `title`, `description`, `author`, `date_created`) VALUES
(1, 'HAPPY VALENTINES DAY', 'Enjoy your day with your special loveone.', '-Manager', '2023-02-02 11:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(8) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `delete_flag` int(1) NOT NULL,
  `notifications` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `comment`, `date_created`, `delete_flag`, `notifications`) VALUES
(41, 89765466, 'EW', '2023-02-12 12:26:51', 1, 'read'),
(42, 29775444, 'ew', '2023-02-12 12:49:38', 1, 'read'),
(43, 29775444, 'eww', '2023-02-12 12:49:38', 1, 'read'),
(44, 29775444, 'ewww', '2023-02-12 12:59:38', 1, 'read'),
(45, 15, 'ew', '2023-02-12 13:14:16', 1, 'read'),
(46, 15, 'w', '2023-02-12 13:21:16', 1, 'read'),
(47, 15, 'ew', '2023-02-12 13:34:48', 1, 'read'),
(48, 15, '123', '2023-02-12 13:36:34', 1, 'read'),
(49, 15, '123', '2023-02-12 13:36:36', 1, 'read'),
(50, 15, '123', '2023-02-12 13:36:43', 1, 'read'),
(51, 15, '123', '2023-02-12 13:36:43', 1, 'read'),
(52, 15, '123', '2023-02-12 13:36:44', 1, 'read'),
(53, 15, '123', '2023-02-12 13:36:44', 1, 'read'),
(54, 15, 'aw', '2023-02-12 13:37:19', 1, 'read'),
(55, 15, 'aw', '2023-02-12 13:37:45', 1, 'read'),
(56, 15, 'aw', '2023-02-12 13:38:18', 1, 'read'),
(57, 15, 'aw', '2023-02-12 14:08:14', 1, 'read'),
(58, 15, 'Looking for 1 HOST 12 am - 6 am', '2023-02-13 00:48:37', 1, 'read'),
(59, 33489476, 'I am in!', '2023-02-13 00:49:05', 1, 'read');

-- --------------------------------------------------------

--
-- Table structure for table `deduction_list`
--

CREATE TABLE `deduction_list` (
  `payslip_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `amount` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deduction_list`
--

INSERT INTO `deduction_list` (`payslip_id`, `name`, `amount`) VALUES
(2, 'PAGIBIG', 100),
(2, 'SSS', 300),
(3, 'Deduction 101', 1000),
(3, 'Deduction 102', 300),
(3, 'Deduction 103', 350),
(4, 'aw', 12),
(5, 'aaww', 312),
(41, 'GAS', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `leave_type` varchar(200) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `creationdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `leave_type`, `description`, `creationdate`) VALUES
(1, 'Casual Leave', 'Provided for urgent or unforeseen matters to the staffs.', '0000-00-00 00:00:00'),
(2, 'Medical Leave', 'Related to Health Problems of Staff', '0000-00-00 00:00:00'),
(3, 'Restricted Holiday', 'Holiday that is optional', '0000-00-00 00:00:00'),
(4, 'Paternity Leave', 'To take care of newborns', '0000-00-00 00:00:00'),
(5, 'Bereavement Leave', 'Grieve their loss of losing loved ones', '0000-00-00 00:00:00'),
(6, 'Compensatory Leave', 'For Overtime workers', '0000-00-00 00:00:00'),
(7, 'Maternity Leave', 'Taking care of newborn, recoveries', '0000-00-00 00:00:00'),
(8, 'Religious Holidays', 'Based on employee\'s followed religion', '0000-00-00 00:00:00'),
(9, 'Adverse Weather Leave', 'In terms of extreme weather conditions', '0000-00-00 00:00:00'),
(10, 'Voting Leave', 'For official election day', '0000-00-00 00:00:00'),
(11, 'Self-Quarantine Leave', 'Related to COVID-19 issues', '0000-00-00 00:00:00'),
(12, 'Personal Time Off', 'To manage some private matters', '0000-00-00 00:00:00'),
(13, 'Sick', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `delete_flag` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `code`, `start_date`, `end_date`, `type`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(149, '1-5-2023', '2023-01-05', '2023-01-15', '2', 0, 0, '2023-01-26 19:56:49', NULL),
(150, '1111', '2023-01-25', '2023-01-25', '1', 0, 1, '2023-01-30 09:11:04', '2023-02-05 23:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `payslip`
--

CREATE TABLE `payslip` (
  `id` int(11) NOT NULL,
  `payroll_id` int(30) NOT NULL,
  `id_no` int(11) NOT NULL,
  `minutes_present` int(11) NOT NULL,
  `days_present` float(10,1) NOT NULL,
  `days_absent` float(10,1) NOT NULL,
  `tardy_undertime` float(10,1) NOT NULL,
  `total_allowance` float NOT NULL,
  `total_deduction` float NOT NULL,
  `rate` float NOT NULL,
  `withholding_tax` int(11) NOT NULL,
  `net` float NOT NULL,
  `notifications` varchar(10) NOT NULL DEFAULT 'payslip',
  `file_path` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payslip`
--

INSERT INTO `payslip` (`id`, `payroll_id`, `id_no`, `minutes_present`, `days_present`, `days_absent`, `tardy_undertime`, `total_allowance`, `total_deduction`, `rate`, `withholding_tax`, `net`, `notifications`, `file_path`, `date_created`, `date_updated`) VALUES
(92, 150, 54532156, 0, 0.0, 0.0, 0.0, 0, 0, 123, 0, 0, 'payslip1', NULL, '2023-02-02 10:17:58', '2023-02-03 13:42:38'),
(93, 150, 43123231, 123, 0.0, 0.0, 213.0, 0, 0, 1233, 3210, -682.35, 'payslip', NULL, '2023-02-03 14:00:22', NULL),
(94, 149, 84990384, 3600, 20.0, 1.0, 0.0, 0, 0, 71.5, 0, 4290, 'payslip', NULL, '2023-02-13 07:09:45', '2023-02-13 07:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `id_no` varchar(500) NOT NULL,
  `title` varchar(200) NOT NULL,
  `schedule_type` tinyint(1) NOT NULL DEFAULT 1,
  `description` text NOT NULL,
  `station` varchar(100) NOT NULL,
  `is_repeating` tinyint(1) NOT NULL DEFAULT 1,
  `repeating_data` text NOT NULL,
  `schedule_date` date NOT NULL,
  `schedule_end` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `notifications` varchar(10) NOT NULL DEFAULT 'schedules'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `faculty_id`, `id_no`, `title`, `schedule_type`, `description`, `station`, `is_repeating`, `repeating_data`, `schedule_date`, `schedule_end`, `time_from`, `time_to`, `date_created`, `notifications`) VALUES
(227, 0, '54532156,29775444,43123231', 'LOBBY', 0, '', 'LOBBY', 0, '', '2023-02-15', '2023-02-15', '00:01:00', '06:00:00', '2023-02-13 07:24:46', 'schedules'),
(228, 0, '84990384,75724311,29687259', 'HOST', 0, '', 'HOST', 0, '', '2023-02-15', '2023-02-15', '00:01:00', '06:00:00', '2023-02-13 07:28:59', 'schedules'),
(230, 0, '0', 'MEETING', 2, '', 'NONE', 0, '', '2023-02-15', '2023-02-15', '10:30:00', '23:59:00', '2023-02-13 08:33:39', 'schedules'),
(231, 0, '33489476', 'PRESENTER', 0, '', 'PRESENTER', 0, '', '2023-02-15', '2023-02-15', '12:01:00', '18:00:00', '2023-02-13 08:35:53', 'schedules');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(30) NOT NULL,
  `id_no` int(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `age` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `datejoined` date NOT NULL,
  `authcode` int(7) NOT NULL,
  `last_login` datetime NOT NULL,
  `notifications` varchar(20) NOT NULL DEFAULT 'announcement',
  `notification` varchar(20) NOT NULL DEFAULT 'schedule',
  `notificationa` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `id_no`, `status`, `position`, `firstname`, `middlename`, `lastname`, `contact`, `gender`, `address`, `email`, `age`, `birthdate`, `datejoined`, `authcode`, `last_login`, `notifications`, `notification`, `notificationa`) VALUES
(19, 33489476, 'Regular', 'Manager', 'Shelina', 'Fulgencio', 'Santos', '09499324530', 'Female', '928 Panay St. Sampaloc, Manila', 'santos.sd.bsinfoteeech@gmail.com', 20, '2002-09-21', '2022-11-28', 2760064, '2023-02-13 09:57:20', 'read', 'read', 'read'),
(21, 29687259, 'Crew Trainer', 'Crew Trainer', 'Ivan Timothy', '', 'Guansing', '', 'Male', '44 P. NARCISO ST, BRGY CORAZON DE JESUS SAN JUAN ', 'ivan', 20, '2002-02-04', '2022-11-28', 9243211, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(22, 29775444, 'Probationary', 'Service Crew', 'Vanessa', '', 'Aquino', '', 'Female', '942 INT 24 BILIBID VIEJO ST. QUIAPO MANILA', 'raytos.r.bsinfotech@gmail.com', 17, '2003-10-21', '2022-11-28', 6074885, '2023-02-13 07:52:57', 'read', 'schedule', 'comment'),
(23, 28404951, 'Maintenance', 'Maintenance', 'Albert', '', 'Puerta', '', 'Male', '329 FROMAN BRGY SALAPAN, SAN JUAN CITY', 'albert', 38, '1984-11-15', '2022-11-28', 2618771, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(27, 28404871, 'Regular', 'Service Crew', 'Joyme', '', 'Tarnate', '', 'Female', '#296 E. CASTILLO ST. CALOOCAN CITY', 'joyme', 26, '1996-12-19', '2022-11-28', 8565422, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(28, 43123231, 'Crew Trainer', 'Crew Trainer', 'Bryan Domingo', '', 'Bernardo', '', 'Male', '1048 A LABO ST SOLIS, TONDO', 'bryan', 24, '1998-01-13', '2022-11-28', 654610, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(29, 89765466, 'Crew Trainer', 'Crew Trainer', 'Jan', '', 'Lazaro', '', 'Male', '0344 DAMKA ST. STA. MESA MANILA', 'jan', 23, '1999-05-09', '2022-11-28', 6707272, '2023-02-12 20:29:37', 'announcement', 'schedule', 'comment'),
(30, 54532156, 'Crew Trainer', 'Crew Trainer', 'Carl Joshua', '', 'Acero', '', 'Male', '25 MAGINOO ST PINYAHAN QC', 'raytos.r.bsinfotech@gmail.com', 23, '1999-06-16', '2022-11-28', 4403866, '2023-02-05 21:41:31', 'read', 'schedule', 'comment'),
(31, 83210123, 'Regular', 'Service Crew', 'Reynalyn', '', 'Refugia', '', 'Female', '731 INT 10 BAGUMBAYAN ST, BACOOD STA MESA MANILA', 'reynalyn', 22, '2000-04-09', '2022-11-28', 2539696, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(32, 47391025, 'Crew Trainer', 'Crew Trainer', 'Julianne Rei', '', 'Kumar', '', 'Female', '179 VISCAYA ST,. MARULAS A CALOOCAN CITY', 'julianne', 18, '2002-01-16', '2022-11-28', 2860833, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(33, 75724311, 'Crew Trainer', 'Crew Trainer', 'Christian', '', 'Conese', '', 'Male', '4715 PERALTA ST  V.MAPA STA MESA MANILA', 'raytos.r.bsinfotech@gmail.com', 21, '2001-04-12', '2022-11-28', 8134712, '2023-02-13 08:31:42', 'announcement', 'schedule', 'comment'),
(34, 84990384, 'Regular', 'Service Crew', 'LJ', '', 'Cain', '', 'Male', '#4803 ANG BAHAY ST. V.MAPA STA. MESA MANILA', 'lj', 21, '2001-12-30', '2001-12-30', 0, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(35, 63463612, 'Regular', 'Service Crew', 'Joanna', '', 'Uson', '', 'Female', '#2160 GERARDOMINDANAO SAMPALOC MANILA', 'joanna', 20, '2002-10-27', '2022-11-28', 0, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment'),
(36, 99348077, 'Regular', 'Service Crew', 'Gabriel', '', 'Terce', '', 'Male', '#1538 LORETO ST. SAMPALOC MANILA', 'gabriel', 25, '1997-01-24', '2022-11-28', 0, '0000-00-00 00:00:00', 'announcement', 'schedule', 'comment');

-- --------------------------------------------------------

--
-- Table structure for table `time-off-request`
--

CREATE TABLE `time-off-request` (
  `id` int(11) NOT NULL,
  `leave_type` varchar(100) NOT NULL,
  `from_date` varchar(100) NOT NULL,
  `to_date` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `stats` varchar(10) NOT NULL DEFAULT 'pending',
  `id_no` int(11) NOT NULL,
  `admin_remark` mediumtext NOT NULL,
  `time_remark` timestamp NOT NULL DEFAULT current_timestamp(),
  `notifications` varchar(10) NOT NULL DEFAULT 'request'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `time-off-request`
--

INSERT INTO `time-off-request` (`id`, `leave_type`, `from_date`, `to_date`, `description`, `date_created`, `stats`, `id_no`, `admin_remark`, `time_remark`, `notifications`) VALUES
(117, 'Paternity Leave', '2023-01-06', '2023-01-07', '', '2023-01-05 15:05:16', 'pending', 83210123, '---', '2023-01-05 15:05:16', 'request'),
(126, 'Casual Leave', '2023-11-19', '2023-01-26', 'aw', '2023-01-30 09:19:25', 'declined', 33489476, 'aw', '2023-01-31 13:16:05', 'request2'),
(142, 'Medical Leave', '2023-02-09', '2023-02-10', 'aw', '2023-02-12 12:50:14', 'pending', 29775444, '---', '2023-02-12 12:50:14', 'requested'),
(143, 'Paternity Leave', '2023-02-15', '2023-02-23', '', '2023-02-13 01:31:06', 'pending', 33489476, '', '2023-02-13 01:31:06', 'request');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2=Staff, 3= subscriber'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(2, 'manager', 'manager', '1d0258c2440a8d19e716292b231e3190', 2),
(15, 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allowance_list`
--
ALTER TABLE `allowance_list`
  ADD KEY `payslip_id` (`payslip_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deduction_list`
--
ALTER TABLE `deduction_list`
  ADD KEY `payslip_id` (`payslip_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip`
--
ALTER TABLE `payslip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time-off-request`
--
ALTER TABLE `time-off-request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `payslip`
--
ALTER TABLE `payslip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `time-off-request`
--
ALTER TABLE `time-off-request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
