-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2024 at 09:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `author_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Active Student','Alumni') NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default-profile.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `user_type`, `profile_pic`, `created_at`) VALUES
(2, 'om ', 'ommetha1@gmail.com', '$2y$10$qzKTBetmg2u.RlfVP3ms5uBULHAZJW9O5hzJLZHcuJ2V2WoQG1mie', 'Active Student', 'default-profile.jpg', '2024-10-12 15:46:28'),
(3, 'om ', 'ommetha23@gmail.com', '$2y$10$nDRdeHM5MxF0j2Z5fUC3IuG.FDlzpmZPvYS3bKJOEhGNdEyK./2pm', 'Active Student', 'default-profile.jpg', '2024-10-12 15:49:24'),
(4, 'om ', 'ommetha7@gmail.com', '$2y$10$/5Jkr1hCYsnQbNCQzJ6HN.nnZgoe1wG5hTJzm.tMbSJEKZIvPxgHK', 'Active Student', 'default-profile.jpg', '2024-10-12 17:10:09'),
(5, 'om', 'ommetha123@gmail.com', '$2y$10$KbafZFEbjBoNpSU7xGsdEOoMyz6PtBfUTlA9gv90jR/rzgxf.NwB2', 'Active Student', 'default-profile.jpg', '2024-10-12 17:12:43');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `user_type` enum('Active Student','Alumni') DEFAULT NULL,
  `graduation_college` varchar(255) DEFAULT NULL,
  `graduation_course` varchar(255) DEFAULT NULL,
  `graduation_year` varchar(4) DEFAULT NULL,
  `post_graduation_college` varchar(255) DEFAULT NULL,
  `post_graduation_course` varchar(255) DEFAULT NULL,
  `phd_college` varchar(255) DEFAULT NULL,
  `phd_course` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `courses` text DEFAULT NULL,
  `internships` text DEFAULT NULL,
  `work_experience` text DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default-profile.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`profile_id`, `user_id`, `first_name`, `last_name`, `email`, `gender`, `bio`, `user_type`, `graduation_college`, `graduation_course`, `graduation_year`, `post_graduation_college`, `post_graduation_course`, `phd_college`, `phd_course`, `linkedin_url`, `github_url`, `instagram_url`, `facebook_url`, `twitter_url`, `skills`, `courses`, `internships`, `work_experience`, `profile_pic`, `created_at`) VALUES
(1, 3, 'om', '', 'ommetha23@gmail.com', NULL, '', 'Active Student', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'default-profile.jpg', '2024-10-12 16:00:35'),
(2, 4, 'om', '', 'ommetha7@gmail.com', NULL, '', 'Active Student', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'default-profile.jpg', '2024-10-12 17:10:25'),
(3, 5, 'om', '', 'ommetha123@gmail.com', NULL, '', 'Active Student', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'default-profile.jpg', '2024-10-12 17:13:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
