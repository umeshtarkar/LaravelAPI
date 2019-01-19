-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 19, 2019 at 06:32 PM
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
(1, 'umesh.kumar@enukesoftware.com', '$2y$10$rs6pRIB1Yjkwq9P6PGTRdeW1.VVqoUJ0ru3FMiP.EspkmLDqNf9L2', 1, '3616ba96a2ba0ab5280904a08491011d', NULL, '2018-12-23 10:47:09', '2019-01-19 04:57:16'),
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
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `cpd_article_id` int(11) NOT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `passing_marks` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `name`, `detail`, `cpd_article_id`, `total_questions`, `total_marks`, `passing_marks`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Special Cadre Officer Recruitment Exam', 'State Bank of India is conducting the exam across the country for the post of SCO on January 28, 2019', 1, 100, 200, 120, 1, '2019-01-19 06:31:59', '2019-01-19 06:35:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `marks` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exam_questions`
--

INSERT INTO `exam_questions` (`id`, `exam_id`, `question`, `answer`, `marks`, `created_at`, `updated_at`) VALUES
(1, 1, 'On what date did Our Prime Minister Narendra Modi relaunch \'Clean India Campaign\'', 'October 2, 2014', 2, '2019-01-19 06:55:04', '2019-01-19 06:57:11');

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
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `website` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `name`, `detail`, `website`, `user_id`, `city`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Woodland Shoes Sale', 'Flat 50% Discout', 'http://www.zeenews.com/shoe', 2, 'Delhi', 1, '2019-01-19 03:04:08', '2019-01-19 03:05:56', NULL),
(2, 'Levi\'s Stylish Jeans Sale', 'Flat 50% Discout', 'http://www.levi.com/jeans', 2, 'Delhi', 1, '2019-01-19 05:57:59', '2019-01-19 05:59:59', NULL);

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
  `image` varchar(255) DEFAULT NULL,
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

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `address`, `mobile_no`, `image`, `country_code`, `country`, `profession`, `council_reg_no`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Umesh', 'Sharma', 'uks.tarkar@gmail.com', '$2y$10$uA31Kg92X09zDBxGGJKGSeU6UjS4NUZLvF/GrO59mEdYNWijct0T.', NULL, '8750589063', NULL, NULL, NULL, 'Software Engineer', '2345', 1, NULL, '2018-12-19 18:00:54', '2018-12-19 19:49:00'),
(3, 'Umesh', 'Kumar', 'uks1.tarkar@gmail.com', '$2y$10$9CshHRQD6j8FTM8NmACLyuN1znbdJE1GGS6sEwHbI/uaWggB30fDe', NULL, '9758418690', NULL, NULL, NULL, 'Software Developer', '2342', 0, NULL, '2018-12-27 09:09:42', '2018-12-27 09:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_exams`
--

CREATE TABLE `user_exams` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `marks` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_exams`
--

INSERT INTO `user_exams` (`id`, `user_id`, `exam_id`, `question_id`, `answer`, `marks`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Our PM Narendra Modi launched \'Clean India Campaign\' from RamLila Maidan ,New Delhi on Oct 2,2014.Same day we commemorate Gandhi Jayanti', 2, '2019-01-19 07:15:03', '2019-01-19 07:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `vacancies`
--

CREATE TABLE `vacancies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `website` varchar(100) DEFAULT NULL,
  `type` smallint(6) NOT NULL DEFAULT '0' COMMENT '0=>Fulltime,1=>Local,2=>PartTime',
  `city` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vacancies`
--

INSERT INTO `vacancies` (`id`, `name`, `detail`, `user_id`, `website`, `type`, `city`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Senior Section Engineer', 'Indian Railway invites applications for the post of SSE', 2, 'http://www.zeenews.com/loan-waiver', 0, 'Chandigarh', 1, '2019-01-19 05:23:30', '2019-01-19 05:26:10', NULL),
(2, 'Senior Cadre Officer', 'State Bank of India invites applications for the post of SCO', 3, 'http://www.zeenews.com/sco', 0, 'Mumbai', 1, '2019-01-19 05:33:20', '2019-01-19 05:33:20', NULL);

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
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
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
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_exams`
--
ALTER TABLE `user_exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vacancies`
--
ALTER TABLE `vacancies`
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
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `exam_questions`
--
ALTER TABLE `exam_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_exams`
--
ALTER TABLE `user_exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vacancies`
--
ALTER TABLE `vacancies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
