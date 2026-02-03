-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 26, 2025 at 02:35 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comp1044_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(320) NOT NULL,
  `phone_num` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `user_id`, `company`, `email`, `phone_num`, `name`) VALUES
(1, 1, 'Tech Innovate Ltd', 'sarah.j@techinnovate.com', ' 0123456789', 'Sarah Johnson'),
(2, 1, 'Global Manufacturing', 'mchen@globalmanufacturing.com', '0134567890', 'Michael Chen'),
(3, 1, 'Bright Future Solutions', 'e.williams@brightfuture.org', '0145678901', 'Emma Williams');

-- --------------------------------------------------------

--
-- Table structure for table `customerinteraction`
--

CREATE TABLE `customerinteraction` (
  `customer_interaction_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `interaction_type` enum('Virtual','Physical','Pending') NOT NULL DEFAULT 'Pending',
  `description` varchar(255) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customerinteraction`
--

INSERT INTO `customerinteraction` (`customer_interaction_id`, `customer_id`, `user_id`, `interaction_type`, `description`, `date`) VALUES
(1, 3, 1, 'Physical', 'Initial meeting at office to discuss project requirements and timeline for the Bright Future Solutions implementation.', '2025-03-18'),
(2, 3, 1, 'Virtual', 'Zoom call to review proposed solutions and address questions about software integration capabilities.', '2025-04-21'),
(3, 2, 1, 'Physical', 'Site visit to Global Manufacturing facility to assess current systems and hardware compatibility.', '2025-04-01'),
(4, 2, 1, 'Virtual', 'Online demonstration of new production monitoring dashboard with Q&A session.', '2025-04-15'),
(5, 1, 1, 'Virtual', 'Initial online meeting via video conference (e.g., Zoom, Google Meet) to understand Tech Innovate Ltd.\'s current challenges and explore how our services/products could potentially address their needs.', '2025-03-15'),
(6, 1, 1, 'Physical', 'On-site visit to Tech Innovate Ltd.\'s office. Met with Sarah Johnson.', '2025-04-20');

-- --------------------------------------------------------

--
-- Table structure for table `customerreminder`
--

CREATE TABLE `customerreminder` (
  `customer_reminder_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `reminder` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customerreminder`
--

INSERT INTO `customerreminder` (`customer_reminder_id`, `customer_id`, `user_id`, `date`, `reminder`, `status`) VALUES
(1, 3, 1, '2025-05-01', 'Follow-up session scheduled to finalize contract details and discuss deployment schedule.', 1),
(2, 2, 1, '2025-05-15', 'Technical review meeting with Global Manufacturing\'s IT team to plan integration phases.', 1),
(3, 2, 1, '2025-05-31', 'Follow up with the contract review process and make final preparations before signing contract officially.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lead`
--

CREATE TABLE `lead` (
  `lead_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('New','Contacted','In Progress','Closed') NOT NULL DEFAULT 'New',
  `notes` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(320) NOT NULL,
  `phone_num` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lead`
--

INSERT INTO `lead` (`lead_id`, `user_id`, `status`, `notes`, `name`, `company`, `email`, `phone_num`) VALUES
(1, 1, 'New', 'Interested in automation solutions by the company for warehouse.', 'David Thompson', 'New Tech Ventures', 'david.t@newtechventures.com', '0178901234'),
(2, 1, 'In Progress', 'Looking for partners on upcoming government contract.', 'James Wilson', 'Smart City Builders', 'jwilson@smartcity.com', '0190123456'),
(3, 1, 'Contacted', 'Potential for large contract, requires demo next month.', 'Robert Garcia', 'Industrial Tech', 'r.garcia@industrialtech.net', '0123456788'),
(4, 1, 'Closed', 'Potential supplier for robot arm and intelligent sensors. (Contract Signed)', 'James Lee', 'Innovative Health Mechanics', ' sophia@innovativehealth.org', '0189012345');

-- --------------------------------------------------------

--
-- Table structure for table `leadinteraction`
--

CREATE TABLE `leadinteraction` (
  `lead_interaction_id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `interaction_type` enum('Virtual','Physical','Pending') NOT NULL DEFAULT 'Pending',
  `description` varchar(255) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leadreminder`
--

CREATE TABLE `leadreminder` (
  `lead_reminder_id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `reminder` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leadreminder`
--

INSERT INTO `leadreminder` (`lead_reminder_id`, `lead_id`, `user_id`, `date`, `reminder`, `status`) VALUES
(1, 1, 1, '2025-04-30', 'Urgent meeting to discuss about the company\'s funding issue.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Sales Representative');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT '2',
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(512) NOT NULL,
  `phone_num` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `role_id`, `username`, `name`, `email`, `password`, `phone_num`) VALUES
(1, 2, 'jsmith02', 'John Smith', 'jsmith.02@gmail.com', '12345', '0123304567'),
(2, 1, 'jcarlson124', 'Jimmy Carlson', 'jc124@gmail.com', 'abcde', '01899256743'),
(3, 1, 'james23', 'Lebron James', 'lbj@hotmail.com', 'abc123', '0178643674');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `customerinteraction`
--
ALTER TABLE `customerinteraction`
  ADD PRIMARY KEY (`customer_interaction_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `customerreminder`
--
ALTER TABLE `customerreminder`
  ADD PRIMARY KEY (`customer_reminder_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lead`
--
ALTER TABLE `lead`
  ADD PRIMARY KEY (`lead_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leadinteraction`
--
ALTER TABLE `leadinteraction`
  ADD PRIMARY KEY (`lead_interaction_id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leadreminder`
--
ALTER TABLE `leadreminder`
  ADD PRIMARY KEY (`lead_reminder_id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customerinteraction`
--
ALTER TABLE `customerinteraction`
  MODIFY `customer_interaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customerreminder`
--
ALTER TABLE `customerreminder`
  MODIFY `customer_reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lead`
--
ALTER TABLE `lead`
  MODIFY `lead_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leadinteraction`
--
ALTER TABLE `leadinteraction`
  MODIFY `lead_interaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leadreminder`
--
ALTER TABLE `leadreminder`
  MODIFY `lead_reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `customerinteraction`
--
ALTER TABLE `customerinteraction`
  ADD CONSTRAINT `customerinteraction_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customerinteraction_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `customerreminder`
--
ALTER TABLE `customerreminder`
  ADD CONSTRAINT `customerreminder_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customerreminder_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `lead`
--
ALTER TABLE `lead`
  ADD CONSTRAINT `lead_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `leadinteraction`
--
ALTER TABLE `leadinteraction`
  ADD CONSTRAINT `leadinteraction_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `lead` (`lead_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leadinteraction_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `leadreminder`
--
ALTER TABLE `leadreminder`
  ADD CONSTRAINT `leadreminder_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `lead` (`lead_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leadreminder_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
