-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 11:41 AM
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
-- Database: `user_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `role` varchar(10) DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `gender`, `profile_image`, `birth_date`, `role`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'อาทิต', 'user1@gmail.com', '$2y$10$YFOz23rs3rgPa0Gf2ffG7u4fZ9UPyNIcPNF.C8nKRbsIIrAoE/fwO', '0981111111', 'ชาย', 'uploads/profile_675561da114d82.21706250.png', '2002-05-03', 'user', NULL, NULL),
(3, 'กิ่งแก้ว', 'user2@gmail.com', '$2y$10$spZwqVmdIL/s.4Z.yAp.rOE35jtzDjNl3zSbLb0qd/dufz183ypeW', '0981999999', 'หญิง', 'uploads/profile_67556225f23a76.86870383.png', '2003-07-08', 'user', NULL, NULL),
(4, 'Admin', 'tanatdith2545@gmail.com', '$2y$10$FozuTn1iRaSG35jsz0gwlOhgGwICUJv4I.7a3zwt2AxqRurFLJgWq', '0987654321', 'ชาย', 'uploads/profile_67530f2e771223.37935963.png', '2002-05-03', 'admin', NULL, NULL),
(5, 'พัดชัย', 'user3@gmail.com', '$2y$10$6oNonF.pbITCK4o.MIjY7eg2Lo98nksQvFVy5d/9MQaz5QIMWmf1y', '0981999999', 'ชาย', NULL, '2007-05-18', 'user', NULL, NULL),
(6, 'bank', 'oazixisreal@gmail.com', '$2y$10$Az3eqQRQ4kIu.Fj0DX2V/egsS8T9cq8VnCE0kp0CnayIVn9Th8KKu', NULL, NULL, NULL, NULL, 'user', NULL, NULL),
(7, 'yoshi', 'youoo437@gmail.com', '$2y$10$EotgxjF.iCkoWLetETB2cOzvu/IMintVTkyWLToQ0gXihdRatrduS', '0889493522', 'male', NULL, '2005-07-21', 'user', NULL, NULL),
(8, 'เอียเอีย', 'kittiwara.ear@gmail.com', '$2y$10$3U/u8FN0qFVKTRR2qRQBzuLfRAAG8wuSW08BW6rcYK7tv/tPyrwOS', NULL, NULL, NULL, NULL, 'user', NULL, NULL),
(9, 'นาวี', 'user4@gmail.com', '$2y$10$Kci/vHCK5upfEM2eM33B9eSzT81BSonI15nkRK8JqKJRjPjQcw762', '0987654321', 'ชาย', 'uploads/profile_676a4e30d53a18.35493519.png', '2002-08-08', 'user', NULL, NULL),
(10, 'ฉลอง', 'user5@gmail.com', '$2y$10$9icQvPCjRuZnn0ENA5LnaewO1OgIB3Ajs1zeIy9DQDq0HTlbafdhe', NULL, NULL, NULL, NULL, 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
