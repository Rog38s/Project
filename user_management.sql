-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 01:48 AM
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
(1, 'cat', 'user1@gmail.com', '$2y$10$YFOz23rs3rgPa0Gf2ffG7u4fZ9UPyNIcPNF.C8nKRbsIIrAoE/fwO', '0981111111', 'ชาย', 'uploads/profile_6730ae1cd11765.69495551.jpg', '2002-05-03', 'user', NULL, NULL),
(3, 'dog', 'user2@gmail.com', '$2y$10$spZwqVmdIL/s.4Z.yAp.rOE35jtzDjNl3zSbLb0qd/dufz183ypeW', '0981999999', 'หญิง', 'uploads/Maltese_puppy.jpeg.jpeg', NULL, 'user', NULL, NULL),
(4, 'Admin', 'tanatdith2545@gmail.com', '$2y$10$4y66du5pOmKocx09V/aa4.Gj8/qXFZAb4w/vCC3853eLCrGp08yE6', '0987654321', 'ชาย', 'uploads/admin.png', '2002-05-03', 'admin', 'a2139e2a6cee11762bc1351a4bd39837b54f801045238610de76718c2fd00c53b383e21d2cadee38f4f486d5f47ee5d38d6d', '2024-11-14 02:28:48'),
(5, 'user3', 'user3@gmail.com', '$2y$10$6oNonF.pbITCK4o.MIjY7eg2Lo98nksQvFVy5d/9MQaz5QIMWmf1y', '0981999999', 'ชาย', NULL, '2007-05-18', 'user', NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
