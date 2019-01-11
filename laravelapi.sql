-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 11, 2019 at 10:00 AM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravelapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `auth_token` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `status`, `auth_token`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'umesh.kumar@enukesoftware.com', '$2y$10$rs6pRIB1Yjkwq9P6PGTRdeW1.VVqoUJ0ru3FMiP.EspkmLDqNf9L2', 1, '103f78dc03ef72fe19f25be9e6dbb43c', NULL, '2018-12-23 10:47:09', '2019-01-10 19:40:27'),
(2, 'uks.tarkar@gmail.com', '123456', 0, NULL, NULL, '2019-01-05 04:14:56', '2019-01-05 04:14:56'),
(3, 'uks1.tarkar@gmail.com', '$2y$10$rs6pRIB1Yjkwq9P6PGTRdeW1.VVqoUJ0ru3FMiP.EspkmLDqNf9L2', 0, NULL, NULL, '2019-01-05 04:16:12', '2019-01-05 04:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `cpd_articles`
--

CREATE TABLE `cpd_articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `text` text,
  `views` bigint(20) NOT NULL DEFAULT '0',
  `picture_large` int(11) DEFAULT NULL,
  `picture_small` int(11) DEFAULT NULL,
  `youtube_video_url` varchar(255) DEFAULT NULL,
  `news_category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cpd_articles`
--

INSERT INTO `cpd_articles` (`id`, `title`, `subtitle`, `text`, `views`, `picture_large`, `picture_small`, `youtube_video_url`, `news_category_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Quota Bill', 'Master Stoke in the winter sesion by Narendra Modi Government', 'Centeral goverment recentely announcec 10 percent reservation to economically weaker section of general category', 21, NULL, NULL, 'http://www.zeenews.com/quota-bill', 1, 1, 0, '2018-12-22 23:38:47', '2019-01-10 19:25:15'),
(2, 'Loan Waiver', 'Loan Waiver by Narendra Modi', 'Centeral government recently announced', 21, NULL, NULL, 'http://www.zeenews.com/loan-waiver', 1, 1, 1, '2019-01-05 04:19:05', '2019-01-05 04:19:05'),
(3, 'Indian Citizenship Amendment Act', 'Master Stroke by Narendra Modi', 'Centeral government recently announced', 21, NULL, NULL, 'http://www.zeenews.com/indian', 1, 1, 1, '2019-01-10 19:19:30', '2019-01-10 19:19:30');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `text` text,
  `views` bigint(20) NOT NULL DEFAULT '0',
  `picture_large` int(11) DEFAULT NULL,
  `picture_small` int(11) DEFAULT NULL,
  `youtube_video_url` varchar(255) DEFAULT NULL,
  `news_category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `subtitle`, `text`, `views`, `picture_large`, `picture_small`, `youtube_video_url`, `news_category_id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Loan Waiver', 'Loan Waiver by Narendra Modi Government', 'Centeral government recently announced', 21, NULL, NULL, 'http://www.zeenews.com/loan-waiver', 1, 1, 1, '2018-12-22 23:38:47', '2018-12-23 03:36:20'),
(2, 'Loan Waiver', 'Loan Waiver by Narendra Modi', 'Centeral government recently announced', 21, NULL, NULL, 'http://www.zeenews.com/loan-waiver', 1, 1, 1, '2019-01-05 04:19:05', '2019-01-05 04:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

CREATE TABLE `news_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `picture`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Umesh', '1545576403Screenshot from 2018-12-07 07-21-07.png', 1, '2018-12-23 05:03:40', '2019-01-05 03:58:47'),
(2, 'Kunj Bihari Lal', '1545561260JioRecharge.png', 1, '2018-12-23 05:04:20', '2018-12-23 05:04:20'),
(3, 'social', '1545580926Screenshot from 2018-12-20 18-40-29.png', 1, '2018-12-23 10:32:06', '2018-12-23 10:32:06'),
(4, 'Political', NULL, 1, '2019-01-05 03:48:44', '2019-01-05 03:48:44'),
(5, 'Economical', 'Image Upload', 1, '2019-01-05 03:50:23', '2019-01-05 03:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text,
  `mobile_no` varchar(255) DEFAULT NULL,
  `country_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `council_reg_no` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 for disable and 1 for enable',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `address`, `mobile_no`, `country_code`, `country`, `profession`, `council_reg_no`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Umesh', 'Sharma', 'uks.tarkar@gmail.com', '$2y$10$uA31Kg92X09zDBxGGJKGSeU6UjS4NUZLvF/GrO59mEdYNWijct0T.', NULL, '8750589063', NULL, NULL, 'Software Engineer', '2345', 1, NULL, '2018-12-19 18:00:54', '2018-12-19 19:49:00'),
(3, 'Umesh', 'Kumar', 'uks1.tarkar@gmail.com', '$2y$10$9CshHRQD6j8FTM8NmACLyuN1znbdJE1GGS6sEwHbI/uaWggB30fDe', NULL, '9758418690', NULL, NULL, 'Software Developer', '2342', 0, NULL, '2018-12-27 09:09:42', '2018-12-27 09:09:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpd_articles`
--
ALTER TABLE `cpd_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_categories`
--
ALTER TABLE `news_categories`
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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cpd_articles`
--
ALTER TABLE `cpd_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
