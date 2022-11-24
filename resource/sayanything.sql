-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 24, 2022 at 12:45 AM
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
-- Database: `sayanything`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `round_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `round_id` (`round_id`),
  KEY `answer` (`answer`(333))
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42),
(43),
(44),
(45),
(46),
(47),
(48),
(49),
(50);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` char(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `state` enum('waiting-for-players','asking-question','answering-question','voting','game-started','results','game-over') DEFAULT NULL,
  `time_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `time_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `creator_id` (`creator_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` char(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` enum('martini-glass','dollar-sign','high-heels','computer','car','football','guitar','clapperboard') NOT NULL,
  `turn` int(11) DEFAULT NULL,
  `skip_turn` tinyint(1) NOT NULL DEFAULT 0,
  `must_wait_for_next_round` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_id_2` (`game_id`,`token`),
  KEY `game_id` (`game_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `question` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=264 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `card_id`, `question`) VALUES
(1, 1, 'What\'s the best leisure activity?'),
(2, 1, 'Who\'s the most overrated band of all time?'),
(3, 1, 'What\'s the best movie sequel of all time?'),
(4, 1, 'What would I most want for my next birthday?'),
(5, 1, 'If I could have a BIG anything, what would it be?'),
(21, 10, 'What city would be the most fun to live in?'),
(20, 9, 'What would be the most difficult item to sell door-to-door?'),
(19, 9, 'What\'s the most important invention of all time?'),
(18, 9, 'Which famous person would be the most difficult to have as an in-law?'),
(17, 9, 'Which movie prop would be the coolest to own?'),
(16, 9, 'What would be the most fun thing to throw off a tall building?'),
(22, 10, 'What\'s the best animated movie of all time?'),
(23, 10, 'Which athlete would be the most fun to be?'),
(24, 10, 'What\'s the one thing I would most want to do before I die?'),
(25, 10, 'I just got to Las Vegas. What\'s the first thing I do?'),
(26, 11, 'What\'s the best thing you can buy for five bucks?'),
(27, 11, 'What movie is required viewing for all geeks?'),
(28, 11, 'What celebrity would make the best nanny?'),
(29, 11, 'What\'s the biggest waste of money?'),
(30, 11, 'I just got fired. Why?'),
(31, 12, 'What\'s the most delicious fruit?'),
(32, 12, 'What\'s the best song of all time?'),
(33, 12, 'If I could go on a date with anyone, who would it be?'),
(34, 12, 'What\'s the ideal romantic evening?'),
(35, 12, 'What\'s the most confusing thing ever?'),
(36, 13, 'What\'s the most pleasant kitchen aroma?'),
(37, 13, 'Who\'s the best movie couple of all time?'),
(38, 13, 'Which famous historical figure would be the best prom date?'),
(39, 13, 'What\'s the most important quality in a parent?'),
(40, 13, 'What really ticks people off?'),
(41, 14, 'What\'s the best holiday?'),
(42, 14, 'Who\'s the funniest TV character ever?'),
(43, 14, 'What would make next weekend more exciting?'),
(44, 14, 'What\'s the best activity for a first date?'),
(45, 14, 'A genie just granted me a wish. What should I ask for?'),
(46, 15, 'What\'s the best Halloween costume?'),
(47, 15, 'What\'s the best drama currently on TV?'),
(48, 15, 'If I could be anyone famous, who would I choose?'),
(49, 15, 'What should more people pay attention to?'),
(50, 15, 'Who\'s the man with the master plan?'),
(51, 16, 'What would be the coolest thing to be able to predict?'),
(52, 16, 'What\'s the best dramatic movie of all time?'),
(53, 17, 'Who\'s the coolest superhero?'),
(54, 17, 'What\'s the world\'s most impressive manmade structure?'),
(55, 17, 'What would make me more hip?'),
(56, 18, 'What\'s the best way to waste time?'),
(57, 18, 'What TV show is the guiltiest pleasure?'),
(58, 18, 'Who\'s the world\'s biggest knucklehead?'),
(59, 18, 'What would be the most romantic Valentine\'s Day gift?'),
(60, 18, 'What would be the dumbest thing to do in public?'),
(61, 2, 'What do most kids want to be when they grow up?'),
(62, 2, 'Which professional athlete would be the best addition to my dodgeball team?'),
(63, 2, 'What\'s the funniest newspaper comic strip?'),
(64, 2, 'What\'s the most important quality in a friend?'),
(65, 2, 'What would be the weirdest thing to collect?'),
(66, 3, 'Which country would be the most interesting to travel to?'),
(67, 3, 'Who\'s the most memorable movie character ever?'),
(68, 3, 'What\'s the best fashion trend of all time?'),
(69, 3, 'What company would I most want to run?'),
(70, 3, 'What would be worst thing to have in your mouth?'),
(71, 4, 'What\'s the most refreshing drink?'),
(72, 4, 'What\'s the best TV drama of all time?'),
(73, 4, 'What\'s the best Olympic sport?'),
(74, 4, 'Who was the most important person of the past 100 years?'),
(75, 4, 'What\'s my biggest pet peeve?'),
(76, 5, 'What word is the most fun to say out loud?'),
(77, 5, 'What\'s the best Beatles song?'),
(78, 5, 'What\'s the best way to spend a Saturday night?'),
(79, 5, 'What am I most likely to be doing in 20 years?'),
(80, 5, 'What shouldn\'t be done while driving?'),
(81, 6, 'Which technology product would be the hardest to live without?'),
(82, 6, 'What\'s the best movie sequel of all time?'),
(83, 6, 'Who\'s the best dressed celebrity?'),
(84, 6, 'Who was the most controversial figure of the past 50 years?'),
(85, 6, 'What would be the weirdest fortune to find in a fortune cookie?'),
(86, 7, 'What gift would I be most surprised to receive for my birthday?'),
(87, 7, 'Which politician, past or present, would make the greatest super villain?'),
(88, 7, 'If I could bring one person back to life, who would I choose?'),
(89, 7, 'What\'s the worst thing my neighbors could catch me doing?'),
(90, 7, 'What\'s the most embarrassing thing that could happen on a blind date?'),
(91, 8, 'What\'s the greatest board game of all time?'),
(92, 8, 'What\'s the most overrated movie of all time?'),
(93, 8, 'What organization would we be worse off without?'),
(94, 8, 'What\'s the worst thing about money?'),
(95, 8, 'The world will end in one week. What should I do?'),
(96, 19, 'When would be the worst time to burst out laughing?'),
(97, 19, 'What\'s the best song that has been used in a movie?'),
(98, 19, 'Who\'s the most memorable book character?'),
(99, 19, 'What\'s the most underrated place for a date?'),
(100, 19, 'What would be the most likely reason for me to end up in jail?'),
(101, 20, 'Where\'s the best place to go for a spring break?'),
(102, 20, 'What\'s the most annoying commercial of all time?'),
(103, 20, 'What\'s the best musical of all time?'),
(104, 20, 'What\'s the greatest thing about living in the country?'),
(105, 20, 'What\'s the dumbest thing to try to do in the dark?'),
(106, 21, 'What\'s the most romantic place for a honeymoon?'),
(107, 21, 'What\'s the best movie to randomly catch on TV?'),
(108, 21, 'What\'s the best place to get the news?'),
(109, 21, 'What\'s the most annoying thing about being a woman?'),
(110, 21, 'Where would be the worst place to wake up?'),
(111, 22, 'What\'s the most important household item?'),
(112, 22, 'What\'s the best animated TV show of all time?'),
(113, 22, 'Who would be the coolest person to trade places with?'),
(114, 22, 'What\'s the sexiest personality trait for a woman?'),
(115, 22, 'What would be the grossest thing to kiss?'),
(116, 23, 'What\'s the scariest musical animal?'),
(117, 23, 'What\'s the best reality TV show of all time?'),
(118, 23, 'What\'s the greatest creative work of all time?'),
(119, 23, 'What\'s the worst habit to have?'),
(120, 23, 'What would my pet say about me if it could talk?'),
(121, 24, 'What dance would I most want to be good at?'),
(122, 24, 'What\'s the scariest movie ever?'),
(123, 24, 'Who\'s the most annoying person in show business?'),
(124, 24, 'What\'s the worst thing about being a man?'),
(125, 24, 'What should my gravestone say?'),
(126, 25, 'What\'s the best way to pamper yourself?'),
(127, 25, 'Who\'s the best looking actress of all time?'),
(128, 25, 'What famous person should never be allowed to rap?'),
(129, 25, 'What would be the best topic for a college class?'),
(130, 25, 'If I was invisible for the day, what would I do?'),
(131, 26, 'What\'s the best dessert of all time?'),
(132, 26, 'What\'s the worst show currently on TV?'),
(133, 26, 'Which famous person would be the most comical as the star of a musical?'),
(134, 26, 'What would be the best company to work for?'),
(135, 26, 'What\'s the dumbest thing that someone has actually done?'),
(136, 27, 'What would I do if I inherited $100,000?'),
(137, 27, 'What\'s the best cable TV channel?'),
(138, 27, 'Who\'s the greatest painter of all time?'),
(139, 27, 'What\'s the best present to get for a significant other?'),
(140, 27, 'Who would be the worst person to sit next to on an airplane?'),
(141, 28, 'What would be the best store to work at?'),
(142, 28, 'What\'s the cheesiest pop song ever?'),
(143, 28, 'What historical event would have been the coolest to witness in person?'),
(144, 28, 'What\'s the most important aspect of a good relationship?'),
(145, 28, 'Who would you be least surprised to find out is an alien?'),
(146, 29, 'What would I most want to see constructed out of Legos?'),
(147, 29, 'What\'s the best TV show to watch in re-runs?'),
(148, 29, 'Who\'s the most creative artist of all time?'),
(149, 29, 'What\'s the least interesting academic subject?'),
(150, 29, 'What\'s a husband most likely to forget?'),
(151, 30, 'What\'s the most useless household item?'),
(152, 30, 'What hit song should never have been recorded?'),
(153, 30, 'If my life was a movie, what would it be?'),
(154, 30, 'What living person would be the coolest to have dinner with?'),
(155, 30, 'What\'s the worst place for a date?'),
(156, 31, 'What would be the worst job to have?'),
(157, 31, 'Who\'s the most overrated actress of all time?'),
(158, 31, 'What\'s the lamest newspaper comic strip?'),
(159, 31, 'What\'s the sexiest personality trait for a man?'),
(160, 31, 'What wouldn\'t I want my taxi driver to say?'),
(161, 32, 'What\'s the tastiest ethnic cuisine?'),
(162, 32, 'Who\'s the most memorable TV character ever?'),
(163, 32, 'What website would be the hardest to live without?'),
(164, 32, 'What\'s the most pressing issue the world will face over the next 50 years?'),
(165, 32, 'What doesn\'t taste better with ketchup?'),
(166, 33, 'What would be the worst possible pizza topping?'),
(167, 33, 'Who\'s the greatest villain of all time?'),
(168, 33, 'What\'s the most fun song to sing at a karaoke party?'),
(169, 33, 'I\'ve been voted the world\'s best parent. Why?'),
(170, 33, 'What would be the dumbest gift to take from a stranger?'),
(171, 34, 'What would be the funniest food to throw in a friend\'s face?'),
(172, 34, 'Who\'s the best looking actor of all time?'),
(173, 34, 'What\'s the best toy of all time?'),
(174, 34, 'What should the government spend more money on?'),
(175, 34, 'What do zoo animals do when the people go home?'),
(176, 35, 'What\'s the most exhausting physical activity?'),
(177, 35, 'Which current TV show would be the hardest to live without?'),
(178, 35, 'What famous person best defines the word \"courageous\"?'),
(179, 35, 'What would I most like to do after I retire?'),
(180, 35, 'What\'s a good t-shirt slogan?'),
(181, 36, 'If I could be holding anything in my hands right now, what would it be?'),
(182, 36, 'Which two people (real or fictional) would you most like to see fight each other?'),
(183, 36, 'Which fictional character do I most wish actually existed?'),
(184, 36, 'What is the worst idea for a themed wedding?'),
(185, 35, 'What would be the worst thing to have thrown in my face?'),
(186, 36, 'Which animal would make the greatest pet?'),
(187, 36, 'What musician or band would be the most embarrassing to have in your collection?'),
(188, 36, 'Who\'s the best movie actor of all time?'),
(189, 36, 'What one word best describes me?'),
(190, 36, 'What\'s the worst thing to say to a cop after getting pulled over?'),
(191, 37, 'What would be the most fun thing to throw off a tall building?'),
(192, 37, 'Which movie prop would be coolest to own?'),
(193, 37, 'Which famous person would be the most difficult to have as an in-law?'),
(194, 37, 'What\'s the most important invention of all time?'),
(195, 37, 'What would be the most difficult item to sell door-to-door?'),
(196, 38, 'What\'s the best way to relax?'),
(197, 38, 'Who\'s the biggest musical icon of the past 100 years?'),
(198, 38, 'Who would be the coolest movie character to be?'),
(199, 38, 'What\'s the best thing about being a woman?'),
(200, 38, 'What would be the worst song for a first dance at a wedding?'),
(201, 39, 'What would I do if I never had to work for a living?'),
(202, 39, 'What movie or TV show quote is the most fun to say?'),
(203, 39, 'What single item would I put in a time capsule to be opened in the year 3000?'),
(204, 39, 'What would be the worst thing to be trapped in an elevator with?'),
(205, 39, 'What\'s the best excuse for forgetting an anniversary?'),
(206, 40, 'What\'s the mot relaxing vacation spot?'),
(207, 40, 'Who\'s the best TV couple of all time?'),
(208, 40, 'Who would be the most interesting person to take a class from?'),
(209, 40, 'What do kids hate most?'),
(210, 40, 'Where\'s the best place to take off your pants?'),
(211, 41, 'What\'s the most relaxing vacation spot?'),
(212, 41, 'Who\'s the best TV couple of all time?'),
(213, 41, 'Who would be the most interesting person to take a class from?'),
(214, 41, 'What do kids hate most?'),
(215, 41, 'Where\'s the best place to take off your pants?'),
(216, 42, 'What would be the coolest car to own?'),
(217, 42, 'What\'s the funniest show currently on TV?'),
(218, 42, 'Which celebrity has no business being a celebrity?'),
(219, 42, 'What\'s the most important thing in life?'),
(220, 42, 'If you could train a monkey to do anything, what would it be?'),
(221, 43, 'What\'s the greatest thing about living in a city?'),
(222, 43, 'What TV channel would be the hardest to live without?'),
(223, 43, 'What\'s the best sit-down restaurant chain?'),
(224, 43, 'What\'s the meaning of life?'),
(225, 43, 'Why did the chicken cross the road?'),
(226, 44, 'What would be the best job to have?'),
(227, 44, 'Who\'s the greatest musician or band of all time?'),
(228, 44, 'Which store is the most fun to shop in?'),
(229, 44, 'What\'s the most interesting field of study?'),
(230, 44, 'What\'s going through my head right now?'),
(231, 45, 'What would be the best job to have?'),
(232, 45, 'Who\'s the greatest musician or band of all time?'),
(233, 45, 'Which store is the most fun to shop in?'),
(234, 45, 'What\'s the most interesting field of the study?'),
(235, 45, 'What\'s going through my head right now?'),
(236, 46, 'What magical power would be the coolest to have?'),
(237, 46, 'What\'s the mot romantic movie of all time?'),
(238, 46, 'Who was the most important person of the past 10 years?'),
(239, 46, 'What\'s the best way to spend the day when playing hooky?'),
(240, 46, 'If I could have a BIG anything, what would it be?'),
(241, 47, 'What would be the best Mother\'s Day gift?'),
(242, 47, 'Who\'s the greatest character from a children\'s television show or movie?'),
(243, 47, 'If you had to adopt one historical figure as a baby, who would it be?'),
(244, 47, 'What would be the best way to get fired from a job?'),
(245, 47, 'What message would I write on the moon for all to see?'),
(246, 48, 'What\'s the most fun activity to do in your free time?'),
(247, 48, 'What\'s the best superhero movie of all time?'),
(248, 48, 'What\'s the most interesting book of all time?'),
(249, 48, 'If I could have anything, what would it be?'),
(250, 48, 'What do people say to dogs that you shouldn\'t say your boss?'),
(251, 49, 'What\'s the best excuse to give when you didn\'t finish an assignment?'),
(252, 49, 'What theme song should play when I enter a room?'),
(253, 49, 'What\'s the last thing I want to find at home after a vacation?'),
(254, 49, 'What would be the coolest robotic attachment to add to my body?'),
(255, 49, 'What does Santa do every day of the year other than Christmas?'),
(256, 50, 'Which fast food chain would be the hardest to live without?'),
(257, 50, 'What would be the worst movie to get remade with a completely nude cast?'),
(258, 50, 'What\'s the greatest thing about being a celebrity?'),
(259, 50, 'I just wrote a book. What\'s it called?'),
(260, 50, 'What would be the most inappropriate thing to have on your desk?'),
(261, 16, 'What\'s the most delicious ice cream flavor?'),
(262, 16, 'What\'s the best music album of all time?'),
(263, 16, 'What\'s the best Saturday morning cartoon ever?');

-- --------------------------------------------------------

--
-- Table structure for table `rounds`
--

DROP TABLE IF EXISTS `rounds`;
CREATE TABLE IF NOT EXISTS `rounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` char(11) NOT NULL,
  `judge_id` int(11) NOT NULL,
  `card_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `chosen_answer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_id_2` (`game_id`,`card_id`),
  KEY `game_id` (`game_id`),
  KEY `active_player_id` (`judge_id`),
  KEY `card_id` (`card_id`),
  KEY `chosen_answer_id` (`chosen_answer_id`),
  KEY `question_id` (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `name` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`name`) VALUES
('guitar'),
('computer'),
('clapperboard'),
('racecar'),
('martini-glass'),
('dollar-sign'),
('football'),
('high-heels');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `round_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `answer_id` (`answer_id`),
  KEY `round_id` (`round_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
