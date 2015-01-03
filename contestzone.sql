-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2015 at 05:12 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `contestzone`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts_tb11`
--

CREATE TABLE IF NOT EXISTS `blog_posts_tb11` (
  `post_id` int(10) NOT NULL AUTO_INCREMENT,
  `author_id` int(10) NOT NULL,
  `post_date` datetime NOT NULL,
  `message` text NOT NULL,
  `title` varchar(64) NOT NULL,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `broadcasts_tb11`
--

CREATE TABLE IF NOT EXISTS `broadcasts_tb11` (
  `broadcast_id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `time_a` datetime NOT NULL,
  `contest_id` int(10) NOT NULL,
  `broadcast` text NOT NULL,
  PRIMARY KEY (`broadcast_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `contests_tb11`
--

CREATE TABLE IF NOT EXISTS `contests_tb11` (
  `contest_id` int(10) NOT NULL AUTO_INCREMENT,
  `contest_name` varchar(64) NOT NULL,
  `contest_type` varchar(32) NOT NULL DEFAULT 'individual',
  `problem_B1_id` int(10) NOT NULL,
  `problem_B2_id` int(10) NOT NULL,
  `problem_B3_id` int(10) NOT NULL,
  `problem_B4_id` int(10) NOT NULL,
  `problem_B5_id` int(10) NOT NULL,
  `problem_B6_id` int(10) NOT NULL,
  `problem_B7_id` int(10) NOT NULL,
  `problem_B8_id` int(10) NOT NULL,
  `problem_B9_id` int(10) NOT NULL,
  `problem_BA_id` int(10) NOT NULL,
  `problem_BB_id` int(10) NOT NULL,
  `problem_BC_id` int(10) NOT NULL,
  `problem_A1_id` int(10) NOT NULL,
  `problem_A2_id` int(10) NOT NULL,
  `problem_A3_id` int(10) NOT NULL,
  `problem_A4_id` int(10) NOT NULL,
  `problem_A5_id` int(10) NOT NULL,
  `problem_A6_id` int(10) NOT NULL,
  `problem_A7_id` int(10) NOT NULL,
  `problem_A8_id` int(10) NOT NULL,
  `problem_A9_id` int(10) NOT NULL,
  `problem_AA_id` int(10) NOT NULL,
  `problem_AB_id` int(10) NOT NULL,
  `problem_AC_id` int(10) NOT NULL,
  `start_time` datetime NOT NULL,
  `number_problems` int(2) NOT NULL,
  `end_time` datetime NOT NULL,
  `visibility` varchar(32) NOT NULL DEFAULT 'none',
  `number_divisions` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`contest_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `contests_tb11`
--

INSERT INTO `contests_tb11` (`contest_id`, `contest_name`, `contest_type`, `problem_B1_id`, `problem_B2_id`, `problem_B3_id`, `problem_B4_id`, `problem_B5_id`, `problem_B6_id`, `problem_B7_id`, `problem_B8_id`, `problem_B9_id`, `problem_BA_id`, `problem_BB_id`, `problem_BC_id`, `problem_A1_id`, `problem_A2_id`, `problem_A3_id`, `problem_A4_id`, `problem_A5_id`, `problem_A6_id`, `problem_A7_id`, `problem_A8_id`, `problem_A9_id`, `problem_AA_id`, `problem_AB_id`, `problem_AC_id`, `start_time`, `number_problems`, `end_time`, `visibility`, `number_divisions`) VALUES
(1, 'Test Contest #1', 'individual', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-11-11 13:25:11', 1, '2022-11-19 13:25:11', 'visible', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contest_setup_tb11`
--

CREATE TABLE IF NOT EXISTS `contest_setup_tb11` (
  `contest_setup_id` int(10) NOT NULL AUTO_INCREMENT,
  `total_points_a` int(10) NOT NULL,
  `total_points_b` int(10) NOT NULL,
  `contest_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `level` varchar(1) NOT NULL,
  `room_number` int(10) NOT NULL,
  `last_time_graded` datetime NOT NULL,
  PRIMARY KEY (`contest_setup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=662 ;

--
-- Dumping data for table `contest_setup_tb11`
--

INSERT INTO `contest_setup_tb11` (`contest_setup_id`, `total_points_a`, `total_points_b`, `contest_id`, `user_id`, `level`, `room_number`, `last_time_graded`) VALUES
(1, 0, 0, 1, 1, 'B', 0, '0000-00-00 00:00:00'),
(2, 0, 0, 1, 2, 'B', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `problems_tb11`
--

CREATE TABLE IF NOT EXISTS `problems_tb11` (
  `problem_id` int(10) NOT NULL AUTO_INCREMENT,
  `author_id` int(10) NOT NULL,
  `contest_id` int(10) NOT NULL,
  `problem_name` varchar(64) NOT NULL,
  `problem_type` varchar(2) NOT NULL,
  `points` int(10) NOT NULL,
  `code` text NOT NULL,
  `statement` text NOT NULL,
  PRIMARY KEY (`problem_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10109 ;

--
-- Dumping data for table `problems_tb11`
--

INSERT INTO `problems_tb11` (`problem_id`, `author_id`, `contest_id`, `problem_name`, `problem_type`, `points`, `code`, `statement`) VALUES
(1, 0, 1, 'Test Problem', 'B1', 2, '', '<table border="1">\r\n<tr>\r\n<td style="text-align:left">Problem Description</td>\r\n<td style="text-align:left">\r\nOutput the string "This is a test!" without quotes.\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n<td style="text-align:left">Input</td>\r\n<td style="text-align:left">\r\nNone\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n<td style="text-align:left">Output</td>\r\n<td style="text-align:left">\r\nThis is a test!\r\n</td>\r\n</tr>\r\n\r\n<tr>\r\n<td style="text-align:left">Worth</td>\r\n<td style="text-align:left">\r\n2 points\r\n</td>\r\n</tr>\r\n</table>');

-- --------------------------------------------------------

--
-- Table structure for table `problem_suggestions_tb11`
--

CREATE TABLE IF NOT EXISTS `problem_suggestions_tb11` (
  `problem_suggestion_id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_statement` text NOT NULL,
  `assumptions` text NOT NULL,
  `input_format` text NOT NULL,
  `output_format` text NOT NULL,
  `title` varchar(64) NOT NULL,
  `solution` text NOT NULL,
  PRIMARY KEY (`problem_suggestion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `questions_tb11`
--

CREATE TABLE IF NOT EXISTS `questions_tb11` (
  `question_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `answerer_id` int(10) NOT NULL,
  `contest_id` int(10) NOT NULL,
  `question` text NOT NULL,
  `time_q` datetime NOT NULL,
  `answer` text NOT NULL,
  `time_a` datetime NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Table structure for table `submissions_tb11`
--

CREATE TABLE IF NOT EXISTS `submissions_tb11` (
  `submission_id` int(10) NOT NULL AUTO_INCREMENT,
  `author_id` int(10) NOT NULL,
  `contest_id` int(10) NOT NULL,
  `problem_type` varchar(2) NOT NULL,
  `code` text NOT NULL,
  `language` varchar(32) NOT NULL,
  `time_submitted` datetime NOT NULL,
  `time_graded` datetime NOT NULL,
  `time_taken` varchar(10) NOT NULL,
  `verdict` varchar(32) NOT NULL,
  `is_graded` tinyint(1) NOT NULL DEFAULT '0',
  `error_message` text NOT NULL,
  `counts_as` varchar(32) NOT NULL DEFAULT 'during',
  PRIMARY KEY (`submission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5981 ;

--
-- Dumping data for table `submissions_tb11`
--

INSERT INTO `submissions_tb11` (`submission_id`, `author_id`, `contest_id`, `problem_type`, `code`, `language`, `time_submitted`, `time_graded`, `time_taken`, `verdict`, `is_graded`, `error_message`, `counts_as`) VALUES
(5980, 1, 1, 'B1', 'import java.io.*;\r\nclass Main {\r\n  public static void main(String[] args) throws IOException {\r\n    System.out.println("This is a test!");\r\n  }\r\n}', 'Java', '2014-11-17 01:47:49', '0000-00-00 00:00:00', '', '', 0, '', 'during');

-- --------------------------------------------------------

--
-- Table structure for table `testers_tb11`
--

CREATE TABLE IF NOT EXISTS `testers_tb11` (
  `tester_id` int(10) NOT NULL AUTO_INCREMENT,
  `problem_id` int(10) NOT NULL,
  `input` longtext NOT NULL,
  `output` longtext NOT NULL,
  PRIMARY KEY (`tester_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1387 ;

--
-- Dumping data for table `testers_tb11`
--

INSERT INTO `testers_tb11` (`tester_id`, `problem_id`, `input`, `output`) VALUES
(1, 1, 'irrelevant input', 'This is a test!'),
(2, 1, '', 'This is a test!');

-- --------------------------------------------------------

--
-- Table structure for table `users_tb11`
--

CREATE TABLE IF NOT EXISTS `users_tb11` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `high_school_graduation` int(4) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `join_date` date NOT NULL,
  `first` varchar(32) NOT NULL,
  `last` varchar(32) NOT NULL,
  `about` text NOT NULL,
  `school` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10176 ;

--
-- Dumping data for table `users_tb11`
--

INSERT INTO `users_tb11` (`id`, `high_school_graduation`, `username`, `password`, `join_date`, `first`, `last`, `about`, `school`, `email`, `type`) VALUES
(2, 2015, 'teststudent', '3c9206f0c3049f64c2d359d1db55aa5c2163a7d9', '2014-11-16', 'Test', 'Student', 'A test student. Does not have administrator privileges.', 'School', 'student@student.com', 'student'),
(1, 2015, 'testadmin', '743139240ff612253817440d98acb2ce7939fbb4', '2014-11-16', 'Test', 'Admin', 'A test administrator.', 'School', 'admin@admin.com', 'administrator');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
