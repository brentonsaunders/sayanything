-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 18, 2022 at 09:48 PM
-- Server version: 10.6.5-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brothasmentor`
--

-- --------------------------------------------------------

--
-- Table structure for table `direct_messages`
--

DROP TABLE IF EXISTS `direct_messages`;
CREATE TABLE IF NOT EXISTS `direct_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentees`
--

DROP TABLE IF EXISTS `mentees`;
CREATE TABLE IF NOT EXISTS `mentees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `short_bio` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentee_read_posts`
--

DROP TABLE IF EXISTS `mentee_read_posts`;
CREATE TABLE IF NOT EXISTS `mentee_read_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mentor_post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`,`mentor_post_id`),
  KEY `user_id` (`user_id`),
  KEY `mentor_post_id` (`mentor_post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentors`
--

DROP TABLE IF EXISTS `mentors`;
CREATE TABLE IF NOT EXISTS `mentors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_initial` varchar(2) NOT NULL,
  `preferred_name` varchar(255) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `short_bio` text NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_applications`
--

DROP TABLE IF EXISTS `mentor_applications`;
CREATE TABLE IF NOT EXISTS `mentor_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_application_answers`
--

DROP TABLE IF EXISTS `mentor_application_answers`;
CREATE TABLE IF NOT EXISTS `mentor_application_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mentor_application_id` int(11) NOT NULL,
  `mentor_application_question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mentor_application_id` (`mentor_application_id`),
  KEY `mentor_application_question_id` (`mentor_application_question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_application_questions`
--

DROP TABLE IF EXISTS `mentor_application_questions`;
CREATE TABLE IF NOT EXISTS `mentor_application_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_posts`
--

DROP TABLE IF EXISTS `mentor_posts`;
CREATE TABLE IF NOT EXISTS `mentor_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('text','video') NOT NULL,
  `title` tinytext NOT NULL,
  `summary` tinytext NOT NULL,
  `content` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `min_age` int(11) NOT NULL,
  `max_age` int(11) NOT NULL,
  `quiz_id` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_post_likes`
--

DROP TABLE IF EXISTS `mentor_post_likes`;
CREATE TABLE IF NOT EXISTS `mentor_post_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mentor_post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mentor_post_id_2` (`mentor_post_id`,`user_id`),
  UNIQUE KEY `mentor_post_id_3` (`mentor_post_id`,`user_id`),
  KEY `mentor_post_id` (`mentor_post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_post_tags`
--

DROP TABLE IF EXISTS `mentor_post_tags`;
CREATE TABLE IF NOT EXISTS `mentor_post_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mentor_post_id` int(11) NOT NULL,
  `tag_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mentor_post_id_2` (`mentor_post_id`,`tag_id`) USING HASH,
  KEY `mentor_post_id` (`mentor_post_id`),
  KEY `tag_id` (`tag_id`(250))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_quizzes`
--

DROP TABLE IF EXISTS `mentor_quizzes`;
CREATE TABLE IF NOT EXISTS `mentor_quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mentor_post_id` int(11) NOT NULL,
  `is_multiple_choice` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mentor_post_id` (`mentor_post_id`,`is_multiple_choice`),
  KEY `user_id` (`mentor_post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_quiz_answers`
--

DROP TABLE IF EXISTS `mentor_quiz_answers`;
CREATE TABLE IF NOT EXISTS `mentor_quiz_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mentor_quiz_question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `is_correct_answer` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mentor_quiz_question_id` (`mentor_quiz_question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_quiz_questions`
--

DROP TABLE IF EXISTS `mentor_quiz_questions`;
CREATE TABLE IF NOT EXISTS `mentor_quiz_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mentor_quiz_id` int(11) NOT NULL,
  `question` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mentor_quiz` (`mentor_quiz_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_sites`
--

DROP TABLE IF EXISTS `mentor_sites`;
CREATE TABLE IF NOT EXISTS `mentor_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mentor','mentee') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`) USING HASH
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_tags`
--

DROP TABLE IF EXISTS `user_tags`;
CREATE TABLE IF NOT EXISTS `user_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`,`tag_id`),
  KEY `user_id` (`user_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
