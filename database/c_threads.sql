-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 17, 2018 at 06:19 PM
-- Server version: 5.7.20-0ubuntu0.17.04.1
-- PHP Version: 7.0.22-0ubuntu0.17.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canonizer`
--

-- --------------------------------------------------------

--
-- Table structure for table `c_threads`
--

CREATE TABLE `c_threads` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `camp_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `c_threads`
--

INSERT INTO `c_threads` (`id`, `user_id`, `camp_id`, `title`, `body`, `created_at`, `updated_at`, `topic_id`) VALUES
(1, 1, 1, 'Thanks, Curt, for the contribution.', 'Thanks, Curt, for the contribution.', '2018-01-08 08:07:42', '2018-01-11 08:07:42', 23),
(2, 328, 1, 'Second Thread', 'Related To some Camp Forums', '2018-01-11 08:07:42', '2018-01-11 08:07:42', 0),
(3, 0, 1, 'jkhsdf', 'jkha jahsdjh asjdh jkhasd jh', '2018-01-11 08:07:42', '2018-01-11 08:07:42', 88),
(4, 0, 1, 'jkhsdf', 'jkha jahsdjh asjdh jkhasd jh', '2018-01-11 08:07:58', '2018-01-11 08:07:58', 88),
(5, 1, 1, 'Text for Yes Camp', 'Text for Yes Camp', '2010-05-10 14:54:34', '2010-05-10 14:54:34', 46),
(6, 1, 3, 'Supporting yes camp?', 'Supporting yes camp?', '2010-05-03 22:52:17', '2010-05-03 22:52:17', 86),
(7, 1, 1, 'Environmentalism vs Wealth', 'Environmentalism vs Wealth', '2009-12-01 13:54:57', '2009-12-01 13:54:57', 105),
(8, 1, 1, 'The Bible as Political Document', 'The Bible as Political Document', '2012-01-14 22:45:37', '2012-01-14 22:45:37', 61),
(9, 188, 5, 'Hi PeerInfinity, thanks for all the work!', 'Hi PeerInfinity, thanks for all the work!', '2010-08-29 18:08:10', '2010-08-29 18:08:10', 94),
(10, 1, 1, 'Mastophilia of 1 ton+ breasts', 'Mastophilia of 1 ton+ breasts', '2012-05-06 15:49:16', '2012-05-06 15:49:16', 63),
(11, 1, 1, 'Supernatural Healing with Scientific Understanding', 'Supernatural Healing with Scientific Understanding', '2010-02-06 03:03:00', '2010-02-06 03:03:00', 92),
(12, 179, 1, 'War occurs in the absence of reason', 'War occurs in the absence of reason', '2014-07-24 15:34:22', '2014-07-24 15:34:22', 113),
(13, 75, 9, 'Help make it the right question.', 'Help make it the right question.', '2008-07-18 03:31:35', '2008-07-18 03:31:35', 70),
(14, 284, 1, 'Do you include ', 'Do you include ', '2012-11-09 15:35:51', '2012-11-09 15:35:51', 140),
(15, 118, 1, 'Model of Multiple Universes: Not an Extension of Physical World', 'Model of Multiple Universes: Not an Extension of Physical World', '2009-03-18 03:02:22', '2009-03-18 03:02:22', 99),
(16, 1, 1, 'Delegated Support', 'Delegated Support', '2012-01-15 03:42:20', '2012-01-15 03:42:20', 103),
(17, 83, 10, 'Thanks for contribution...', 'Thanks for contribution...', '2010-11-03 02:06:27', '2010-11-03 02:06:27', 16),
(18, 188, 6, 'Is this the right place in the structure for this camp?', 'Is this the right place in the structure for this camp?', '2007-12-03 13:30:46', '2007-12-03 13:30:46', 16),
(19, 1, 1, 'Welcome Ari', 'Welcome Ari', '2010-11-02 03:40:36', '2010-11-02 03:40:36', 16),
(20, 1, 1, 'How to better organize this topic?', 'How to better organize this topic?', '2011-06-27 23:18:08', '2011-06-27 23:18:08', 120),
(21, 1, 1, '3 great choices.', '3 great choices.', '2009-05-22 21:50:18', '2009-05-22 21:50:18', 102),
(22, 283, 1, 'Restructuring this topic?', 'Restructuring this topic?', '2013-01-01 16:22:58', '2013-01-01 16:22:58', 141),
(23, 1, 1, 'True Desires', 'True Desires', '2012-01-15 06:49:12', '2012-01-15 06:49:12', 64),
(24, 1, 1, 'This camp should support the YES camp.', 'This camp should support the YES camp.', '2012-10-17 01:31:24', '2012-10-17 01:31:24', 42),
(25, 1, 1, 'person versus human?', 'person versus human?', '2010-02-09 17:33:26', '2010-02-09 17:33:26', 51),
(26, 1, 1, 'Vitual Reality = Representational Qualia Theory', 'Vitual Reality = Representational Qualia Theory', '2012-03-24 21:30:43', '2012-03-24 21:30:43', 71),
(27, 92, 4, 'Change parent of camp to show support of parent camp.', 'Change parent of camp to show support of parent camp.', '2009-01-24 22:33:27', '2009-01-24 22:33:27', 43),
(28, 1, 1, 'Not good enough odds to be considered a rigorous consensus?', 'Not good enough odds to be considered a rigorous consensus?', '2009-09-13 19:25:47', '2009-09-13 19:25:47', 104),
(29, 1, 1, 'Two different topics', 'Two different topics', '2007-12-20 17:48:36', '2007-12-20 17:48:36', 22),
(30, 1, 5, 'How Mormon Exclusivity affects me.', 'How Mormon Exclusivity affects me.', '2008-10-19 18:31:17', '2008-10-19 18:31:17', 22),
(31, 1, 41, 'Ask questions', 'Ask questions', '2010-05-06 13:49:26', '2010-05-06 13:49:26', 88),
(32, 1, 20, 'Of free will and neural infrastructure', 'Of free will and neural infrastructure', '2012-07-12 15:59:29', '2012-07-12 15:59:29', 88),
(33, 1, 37, 'Some thoughts on this camp\'s statement', 'Some thoughts on this camp\'s statement', '2010-05-31 14:18:49', '2010-05-31 14:18:49', 88),
(34, 85, 13, 'UMSITW is the most succinct explanation of C available!', 'UMSITW is the most succinct explanation of C available!', '2010-05-17 14:23:21', '2010-05-17 14:23:21', 88),
(35, 1, 23, 'Welcome to the not supernatural camp.', 'Welcome to the not supernatural camp.', '2009-08-12 04:11:07', '2009-08-12 04:11:07', 88),
(36, 243, 7, 'New Name: ', 'New Name: ', '2009-07-06 14:24:49', '2009-07-06 14:24:49', 88),
(37, 1, 14, 'Reified higher dimensions are over-kill', 'Reified higher dimensions are over-kill', '2009-08-06 02:02:23', '2009-08-06 02:02:23', 88),
(38, 271, 54, 'Checking out your work.', 'Checking out your work.', '2012-07-20 03:12:33', '2012-07-20 03:12:33', 88),
(39, 1, 25, 'The Trail of Breadcrumbs', 'The Trail of Breadcrumbs', '2012-11-24 17:35:34', '2012-11-24 17:35:34', 88),
(40, 309, 58, 'Chalmers\' error', 'Chalmers\' error', '2013-10-06 17:49:01', '2013-10-06 17:49:01', 88),
(41, 261, 50, 'RQT Thread Sidebar', 'RQT Thread Sidebar', '2012-06-06 16:44:57', '2012-06-06 16:44:57', 88),
(42, 1, 29, 'The ', 'The ', '2010-03-08 20:05:56', '2010-03-08 20:05:56', 88),
(43, 35, 53, 'Helping to build more consensus.', 'Helping to build more consensus.', '2012-06-24 17:30:48', '2012-06-24 17:30:48', 88),
(44, 116, 6, 'Classic Qualia Possibility Proof?', 'Classic Qualia Possibility Proof?', '2009-01-25 23:32:10', '2009-01-25 23:32:10', 88),
(45, 38, 4, 'Updates of image links?', 'Updates of image links?', '2009-02-06 05:39:24', '2009-02-06 05:39:24', 88),
(46, 1, 2, 'Definition of consciousness?', 'Definition of consciousness?', '2009-05-28 01:53:51', '2009-05-28 01:53:51', 88),
(47, 35, 15, 'a quibble with substance', 'a quibble with substance', '2010-06-07 05:40:03', '2010-06-07 05:40:03', 88),
(48, 96, 1, 'TSC 2014 in Tucson', 'TSC 2014 in Tucson', '2008-12-29 16:20:00', '2008-12-29 16:20:00', 88),
(49, 1, 19, 'Is property dualism Cartesian dualism?', 'Is property dualism Cartesian dualism?', '2012-06-16 13:20:17', '2012-06-16 13:20:17', 88),
(50, 131, 39, 'Language & the recursive/self-reflective nature of consciousness', 'Language & the recursive/self-reflective nature of consciousness', '2010-05-29 07:31:01', '2010-05-29 07:31:01', 88),
(51, 1, 3, 'Propose moving this \'pan psychic\' camp.', 'Propose moving this \'pan psychic\' camp.', '2011-10-02 21:36:23', '2011-10-02 21:36:23', 88),
(52, 131, 31, 'Can you link me to some resource supporting this theory?', 'Can you link me to some resource supporting this theory?', '2011-09-19 10:48:19', '2011-09-19 10:48:19', 88),
(53, 1, 8, 'This can\'t be a corelary can it?', 'This can\'t be a corelary can it?', '2009-01-26 04:20:32', '2009-01-26 04:20:32', 88),
(54, 1, 34, 'How is this Panexperientialism different than rep qualia?', 'How is this Panexperientialism different than rep qualia?', '2011-04-19 03:23:26', '2011-04-19 03:23:26', 88),
(55, 159, 33, 'Moving the ELN camp?', 'Moving the ELN camp?', '2009-11-15 17:20:17', '2009-11-15 17:20:17', 88),
(56, 1, 1, 'conciliance vs canon', 'conciliance vs canon', '2007-11-28 16:16:03', '2007-11-28 16:16:03', 24),
(57, 1, 1, 'What rainbow? - I don\'t see any rainbow!', 'What rainbow? - I don\'t see any rainbow!', '2008-10-08 14:56:43', '2008-10-08 14:56:43', 1),
(58, 1, 10, 'Can we get a camp representing these important ideas started?', 'Can we get a camp representing these important ideas started?', '2011-07-08 03:36:26', '2011-07-08 03:36:26', 85),
(59, 1, 1, 'Expanding this survey topic.', 'Expanding this survey topic.', '2009-03-11 08:36:36', '2009-03-11 08:36:36', 97),
(60, 1, 1, 'Welcome to the Wiki Leaks survey topic', 'Welcome to the Wiki Leaks survey topic', '2010-12-05 23:45:34', '2010-12-05 23:45:34', 119),
(61, 108, 10, 'More Info Needed.', 'More Info Needed.', '2009-04-11 17:36:39', '2009-04-11 17:36:39', 53),
(62, 35, 2, 'Good improvements...', 'Good improvements...', '2010-02-27 18:43:36', '2010-02-27 18:43:36', 83),
(63, 1, 1, 'Thanks for contributuion.', 'Thanks for contributuion.', '2008-11-15 17:01:30', '2008-11-15 17:01:30', 83),
(64, 1, 1, 'Illusion Doctrin belongs in compatible camp.', 'Illusion Doctrin belongs in compatible camp.', '2012-01-14 22:09:05', '2012-01-14 22:09:05', 128),
(65, 1, 1, 'Welcom Dystopian Welcher!', 'Welcom Dystopian Welcher!', '2011-05-07 03:47:11', '2011-05-07 03:47:11', 121),
(66, 30, 1, 'from thread', 'from thread', '2007-11-08 01:03:54', '2007-11-08 01:03:54', 30),
(67, 1, 2, 'Theist-Friendly Atheism', 'Theist-Friendly Atheism', '2012-11-27 12:41:45', '2012-11-27 12:41:45', 54),
(68, 1, 4, 'Christian Transhumanist', 'Christian Transhumanist', '2008-08-23 16:13:25', '2008-08-23 16:13:25', 54),
(69, 1, 2, 'Google doc draft for this camp.', 'Google doc draft for this camp.', '2013-06-23 22:35:46', '2013-06-23 22:35:46', 154),
(70, 1, 1, 'new ', 'new ', '2013-07-11 22:20:56', '2013-07-11 22:20:56', 154),
(71, 35, 10, 'What is a Computer?', 'What is a Computer?', '2012-11-27 12:07:39', '2012-11-27 12:07:39', 23),
(72, 1, 15, 'Abandonmenet of Functional Property Dualism?', 'Abandonmenet of Functional Property Dualism?', '2010-05-13 14:37:37', '2010-05-13 14:37:37', 23),
(73, 1, 1, 'Unapproachable via science', 'Unapproachable via science', '2007-09-29 21:22:27', '2007-09-29 21:22:27', 23),
(74, 1, 7, 'Removeing \'Brain Linked\'?', 'Removeing \'Brain Linked\'?', '2008-10-21 20:58:37', '2008-10-21 20:58:37', 23),
(75, 38, 13, 'Hello JohnDe1941,', 'Hello JohnDe1941,', '2008-02-01 04:38:36', '2008-02-01 04:38:36', 23),
(76, 1, 11, 'Same camp as ', 'Same camp as ', '2007-10-25 23:56:44', '2007-10-25 23:56:44', 23),
(77, 1, 9, 'Transition to new consciousness theories topic?', 'Transition to new consciousness theories topic?', '2007-11-18 04:51:41', '2007-11-18 04:51:41', 23),
(78, 1, 8, 'I believe Chalmers will not agree with this camp', 'I believe Chalmers will not agree with this camp', '2007-10-20 15:43:37', '2007-10-20 15:43:37', 23),
(79, 85, 21, 'New Theories Topic.', 'New Theories Topic.', '2009-01-08 02:36:18', '2009-01-08 02:36:18', 23),
(80, 1, 1, '4-way muddle', '4-way muddle', '2010-01-05 17:38:26', '2010-01-05 17:38:26', 106),
(81, 1, 1, 'The meaning of equity', 'The meaning of equity', '2008-11-03 13:39:25', '2008-11-03 13:39:25', 7),
(82, 283, 1, 'Interesting...', 'Interesting...', '2012-11-30 17:08:16', '2012-11-30 17:08:16', 144),
(83, 180, 26, 'Welcome Les', 'Welcome Les', '2010-05-07 01:35:40', '2010-05-07 01:35:40', 81),
(84, 1, 25, 'Great start to a great camp.', 'Great start to a great camp.', '2010-03-13 20:21:53', '2010-03-13 20:21:53', 81),
(85, 1, 13, 'Canonizing Penrose-Hameroff (Orch OR)', 'Canonizing Penrose-Hameroff (Orch OR)', '2010-05-24 01:25:41', '2010-05-24 01:25:41', 81),
(86, 1, 4, 'resonance patterns', 'resonance patterns', '2008-11-04 19:09:21', '2008-11-04 19:09:21', 81),
(87, 1, 21, 'Cleaning up and making camps consistant.', 'Cleaning up and making camps consistant.', '2009-02-28 17:54:12', '2009-02-28 17:54:12', 81),
(88, 1, 1, 'Change \'Mind\' Experts to Consciousness Experts', 'Change \'Mind\' Experts to Consciousness Experts', '2008-11-13 18:44:02', '2008-11-13 18:44:02', 81),
(89, 1, 1, 'Heat Pollution', 'Heat Pollution', '2012-01-16 03:05:26', '2012-01-16 03:05:26', 66),
(90, 114, 1, 'Can\'t support agreement statement', 'Can\'t support agreement statement', '2012-11-16 14:42:53', '2012-11-16 14:42:53', 133),
(91, 1, 1, 'why doesn\'t italic work?', 'why doesn\'t italic work?', '2008-11-03 11:52:47', '2008-11-03 11:52:47', 73),
(92, 1, 1, 'A New Option Submitted, Please Adjust your Support', 'A New Option Submitted, Please Adjust your Support', '2012-03-22 17:13:57', '2012-03-22 17:13:57', 69),
(93, 1, 1, 'Lord Yo, thanks for contributions.', 'Lord Yo, thanks for contributions.', '2008-01-24 03:37:39', '2008-01-24 03:37:39', 49),
(94, 85, 1, 'Great topic.', 'Great topic.', '2010-05-15 15:00:50', '2010-05-15 15:00:50', 114),
(95, 35, 2, 'Welcome to this camp Xodarap', 'Welcome to this camp Xodarap', '2008-09-25 01:57:25', '2008-09-25 01:57:25', 2),
(96, 35, 4, 'I\'m not sure what the word ', 'I\'m not sure what the word ', '2008-09-24 15:34:56', '2008-09-24 15:34:56', 2),
(97, 1, 20, 'Gaia is not God', 'Gaia is not God', '2010-03-31 13:46:43', '2010-03-31 13:46:43', 2),
(98, 1, 1, 'Camp ', 'Camp ', '2010-02-03 05:49:32', '2010-02-03 05:49:32', 2),
(99, 1, 18, 'The broader conclusion is unwarranted', 'The broader conclusion is unwarranted', '2009-07-20 04:47:46', '2009-07-20 04:47:46', 2),
(100, 1, 1, 'Let\'s unify the yes camps.', 'Let\'s unify the yes camps.', '2009-10-25 21:50:44', '2009-10-25 21:50:44', 91),
(101, 1, 2, 'The most important endeavour?', 'The most important endeavour?', '2010-04-06 14:36:03', '2010-04-06 14:36:03', 91),
(102, 1, 3, 'Is this a yes or no camp?', 'Is this a yes or no camp?', '2009-02-05 14:51:34', '2009-02-05 14:51:34', 91),
(103, 30, 2, 'This is a test.', 'This is a test.', '2007-12-16 17:59:01', '2007-12-16 17:59:01', 29),
(104, 1, 6, 'Test some posting.', 'Test some posting.', '2008-12-16 02:28:10', '2008-12-16 02:28:10', 29),
(105, 1, 3, 'Testing, 2 3 4.', 'Testing, 2 3 4.', '2013-07-08 02:20:15', '2013-07-08 02:20:15', 29),
(106, 30, 1, 'bold italic test.', 'bold italic test.', '2008-11-29 23:00:38', '2008-11-29 23:00:38', 29),
(107, 172, 1, 'Great topic.', 'Great topic.', '2010-02-05 03:05:13', '2010-02-05 03:05:13', 112),
(108, 90, 1, 'Qualia exist - here\'s how', 'Qualia exist - here\'s how', '2008-12-28 06:01:52', '2008-12-28 06:01:52', 82);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `c_threads`
--
ALTER TABLE `c_threads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `c_threads`
--
ALTER TABLE `c_threads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
