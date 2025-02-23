-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 23, 2025 at 12:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gravekeeperDB`
--

DROP DATABASE IF EXISTS gravekeeperDB;
CREATE DATABASE gravekeeperDB;
use gravekeeperDB;

-- --------------------------------------------------------

--
-- Table structure for table `bur_type`
--

CREATE TABLE `bur_type` (
  `type_id` int(11) NOT NULL,
  `bur_img` text DEFAULT NULL,
  `description` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bur_type`
--

INSERT INTO `bur_type` (`type_id`, `bur_img`, `description`) VALUES
(1, NULL, 'unassigned'),
(2, NULL, 'normal'),
(3, NULL, 'create');

-- --------------------------------------------------------

--
-- Table structure for table `plot`
--

CREATE TABLE `plot` (
  `plot_id` int(11) NOT NULL,
  `description` varchar(32) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `stat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plot`
--

INSERT INTO `plot` (`plot_id`, `description`, `section_id`, `type_id`, `stat_id`) VALUES
(1, 'Plot 1', 1, 1, 3),
(2, 'Plot 2', 1, 1, 3),
(3, 'Plot 3', 1, 1, 3),
(4, 'Plot 4', 1, 1, 3),
(5, 'Plot 5', 1, 1, 3),
(6, 'Plot 6', 1, 1, 3),
(7, 'Plot 7', 1, 1, 3),
(8, 'Plot 8', 1, 1, 3),
(9, 'Plot 9', 1, 1, 3),
(10, 'Plot 10', 1, 1, 3),
(11, 'Plot 11', 1, 1, 3),
(12, 'Plot 12', 1, 1, 3),
(13, 'Plot 13', 1, 1, 3),
(14, 'Plot 14', 1, 1, 3),
(15, 'Plot 15', 1, 1, 3),
(16, 'Plot 16', 1, 1, 3),
(17, 'Plot 17', 1, 1, 3),
(18, 'Plot 18', 1, 1, 3),
(19, 'Plot 19', 1, 1, 3),
(20, 'Plot 20', 1, 1, 3),
(21, 'Plot 1', 2, 1, 3),
(22, 'Plot 2', 2, 1, 3),
(23, 'Plot 3', 2, 1, 3),
(24, 'Plot 4', 2, 1, 3),
(25, 'Plot 5', 2, 1, 3),
(26, 'Plot 1', 3, 1, 3),
(27, 'Plot 2', 3, 1, 3),
(28, 'Plot 3', 3, 1, 3),
(29, 'Plot 4', 3, 1, 3),
(30, 'Plot 5', 3, 1, 3),
(31, 'Plot 6', 3, 1, 3),
(32, 'Plot 7', 3, 1, 3),
(33, 'Plot 1', 4, 1, 3),
(34, 'Plot 2', 4, 1, 3),
(35, 'Plot 3', 4, 1, 3),
(36, 'Plot 4', 4, 1, 3),
(37, 'Plot 5', 4, 1, 3),
(38, 'Plot 6', 4, 1, 3),
(39, 'Plot 7', 4, 1, 3),
(40, 'Plot 8', 4, 1, 3),
(41, 'Plot 9', 4, 1, 3),
(42, 'Plot 10', 4, 1, 3),
(43, 'Plot 1', 5, 1, 3),
(44, 'Plot 2', 5, 1, 3),
(45, 'Plot 3', 5, 1, 3),
(46, 'Plot 4', 5, 1, 3),
(47, 'Plot 5', 5, 1, 3),
(48, 'Plot 6', 5, 1, 3),
(49, 'Plot 7', 5, 1, 3),
(50, 'Plot 8', 5, 1, 3),
(51, 'Plot 9', 5, 1, 3),
(52, 'Plot 10', 5, 1, 3),
(53, 'Plot 11', 5, 1, 3),
(54, 'Plot 1', 6, 1, 3),
(55, 'Plot 2', 6, 1, 3),
(56, 'Plot 3', 6, 1, 3),
(57, 'Plot 4', 6, 1, 3),
(58, 'Plot 5', 6, 1, 3),
(59, 'Plot 6', 6, 1, 3),
(60, 'Plot 7', 6, 1, 3),
(61, 'Plot 8', 6, 1, 3),
(62, 'Plot 9', 6, 1, 3),
(63, 'Plot 10', 6, 1, 3),
(64, 'Plot 11', 6, 1, 3),
(65, 'Plot 12', 6, 1, 3),
(66, 'Plot 13', 6, 1, 3),
(67, 'Plot 14', 6, 1, 3),
(68, 'Plot 15', 6, 1, 3),
(69, 'Plot 16', 6, 1, 3),
(70, 'Plot 1', 7, 1, 3),
(71, 'Plot 2', 7, 1, 3),
(72, 'Plot 3', 7, 1, 3),
(73, 'Plot 4', 7, 1, 3),
(74, 'Plot 5', 7, 1, 3),
(75, 'Plot 6', 7, 1, 3),
(76, 'Plot 7', 7, 1, 3),
(77, 'Plot 8', 7, 1, 3),
(78, 'Plot 9', 7, 1, 3),
(79, 'Plot 10', 7, 1, 3),
(80, 'Plot 11', 7, 1, 3),
(81, 'Plot 12', 7, 1, 3),
(82, 'Plot 13', 7, 1, 3),
(83, 'Plot 14', 7, 1, 3),
(84, 'Plot 15', 7, 1, 3),
(85, 'Plot 16', 7, 1, 3),
(86, 'Plot 17', 7, 1, 3),
(87, 'Plot 1', 8, 1, 3),
(88, 'Plot 2', 8, 1, 3),
(89, 'Plot 3', 8, 1, 3),
(90, 'Plot 4', 8, 1, 3),
(91, 'Plot 5', 8, 1, 3),
(92, 'Plot 6', 8, 1, 3),
(93, 'Plot 7', 8, 1, 3),
(94, 'Plot 8', 8, 1, 3),
(95, 'Plot 9', 8, 1, 3),
(96, 'Plot 10', 8, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reserv_id` int(11) NOT NULL,
  `date_placed` date NOT NULL,
  `date_reserved` date DEFAULT NULL,
  `stat_id` int(11) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `plot_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `rev_id` int(11) NOT NULL,
  `rev_num` int(11) DEFAULT NULL,
  `rev_msg` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `description` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `description`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `sec_name` varchar(32) DEFAULT NULL,
  `description` varchar(32) DEFAULT NULL,
  `sec_img` text DEFAULT NULL,
  `num_plot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `sec_name`, `description`, `sec_img`, `num_plot`) VALUES
(1, 'Section 1', 'Aywan', 'images/section1.png', 20),
(2, 'Section 2', 'Di ko alam', 'images/section2.png', 5),
(3, 'Section 3', 'Bahala na', 'images/section3.png', 7),
(4, 'Section 4', 'I don\'t know', 'images/section4.png', 10),
(5, 'Section 5', 'Don\'t know', 'images/section5.png', 11),
(6, 'Section 6', 'Ernz', 'images/section6.png', 16),
(7, 'Section 7', 'Pogi', 'images/section7.png', 17),
(8, 'Section 8', 'wohoi', 'images/section8.png', 10);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `stat_id` int(11) NOT NULL,
  `description` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`stat_id`, `description`) VALUES
(1, 'active'),
(2, 'deactivated'),
(3, 'free'),
(4, 'occupied'),
(5, 'pending'),
(6, 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` char(64) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `stat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `name`, `phone`, `role_id`, `stat_id`) VALUES
(1, 'marbella@gmail.com', 'd8cb704698c8d6e24e8be1f1f161c030238e0376', 'Sharwin', '09756324515', 1, 1),
(2, 'yago@gmail.com', '5931ac353956df19fd34edb1dafa9a350d589981', 'Alvin', '09653548254', 1, 1),
(3, 'manalo@gmail.com', '8a66bb8c84eec6ee3f0cce4d3eff2fab81e34fef', 'Jett', '09853224562', 1, 1),
(4, 'jumoc@gmail.com', '7dd9ff017a73bbfe2c612450e7fb298ac7804330', 'Ernz', '09354528876', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bur_type`
--
ALTER TABLE `bur_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `plot`
--
ALTER TABLE `plot`
  ADD PRIMARY KEY (`plot_id`),
  ADD KEY `plot_section_id_fk` (`section_id`),
  ADD KEY `plot_type_id_fk` (`type_id`),
  ADD KEY `plot_stat_id_fk` (`stat_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reserv_id`),
  ADD KEY `reservation_stat_id_fk` (`stat_id`),
  ADD KEY `reservation_plot_id_fk` (`plot_id`),
  ADD KEY `reservation_user_id_fk` (`user_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`rev_id`),
  ADD KEY `review_user_id` (`user_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_role_id_fk` (`role_id`),
  ADD KEY `user_stat_id_fk` (`stat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bur_type`
--
ALTER TABLE `bur_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `plot`
--
ALTER TABLE `plot`
  MODIFY `plot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reserv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `rev_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plot`
--
ALTER TABLE `plot`
  ADD CONSTRAINT `plot_section_id_fk` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plot_stat_id_fk` FOREIGN KEY (`stat_id`) REFERENCES `status` (`stat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plot_type_id_fk` FOREIGN KEY (`type_id`) REFERENCES `bur_type` (`type_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_plot_id_fk` FOREIGN KEY (`plot_id`) REFERENCES `plot` (`plot_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_stat_id_fk` FOREIGN KEY (`stat_id`) REFERENCES `status` (`stat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_stat_id_fk` FOREIGN KEY (`stat_id`) REFERENCES `status` (`stat_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
