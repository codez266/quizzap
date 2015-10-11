-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 11, 2015 at 05:30 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `njath`
--

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE IF NOT EXISTS `info` (
  `u_id` int(11) NOT NULL,
  `q_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `attempts` int(10) NOT NULL DEFAULT '0',
  `success` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`u_id`, `q_id`, `time`, `attempts`, `success`) VALUES
(1, 111, '2015-10-11 07:39:59', 1, 1),
(1, 112, '2015-10-11 07:40:39', 1, 1),
(1, 114, '2015-10-11 07:50:03', 1, 1),
(1, 117, '2015-10-11 07:54:34', 1, 1),
(1, 118, '2015-10-11 07:54:44', 1, 1),
(1, 119, '2015-10-11 08:07:06', 1, 1),
(2, 113, '2015-10-11 08:08:05', 1, 1),
(2, 114, '2015-10-11 08:07:46', 0, 0),
(2, 116, '2015-10-11 08:07:46', 0, 0),
(2, 117, '2015-10-11 08:07:46', 0, 0),
(2, 118, '2015-10-11 08:07:46', 0, 0),
(2, 119, '2015-10-11 08:07:46', 0, 0),
(3, 112, '2015-10-11 15:17:14', 1, 1),
(3, 113, '2015-10-11 15:16:51', 0, 0),
(3, 116, '2015-10-11 15:16:51', 0, 0),
(3, 117, '2015-10-11 15:16:51', 0, 0),
(3, 118, '2015-10-11 15:16:51', 0, 0),
(3, 119, '2015-10-11 15:16:51', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `q_id` int(11) NOT NULL COMMENT 'change this to autoincrement after popoulation',
  `name` varchar(100) NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `img_name` varchar(20) DEFAULT NULL,
  `ans` varchar(100) NOT NULL,
  `hints` varchar(100) DEFAULT NULL,
  `level` int(1) NOT NULL,
  `max_attempts` int(11) NOT NULL DEFAULT '-1',
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`q_id`, `name`, `text`, `img_name`, `ans`, `hints`, `level`, `max_attempts`, `score`) VALUES
(111, 'q1', 'What is the first question?', NULL, 'answer to ques 1', NULL, 1, -1, 30),
(112, 'ques2', 'What is the question 2?', NULL, 'answer to ques 2', NULL, 1, -1, 30),
(113, 'ques3', 'What is the question 3?', NULL, 'answer to ques 3', NULL, 1, -1, 30),
(114, 'ques4', 'What is the question 4?', NULL, 'answer to ques 4', NULL, 1, -1, 30),
(115, 'ques5', 'What is the question 5?', NULL, 'answer to ques 5', NULL, 1, -1, 30),
(116, 'ques6', 'What is the question 6?', NULL, 'answer to ques 6', NULL, 1, -1, 30),
(117, 'ques7', 'What is the question 7?', NULL, 'answer to ques 7', NULL, 1, -1, 30),
(118, 'ques8', 'What is the question 8?', NULL, 'answer to ques 8', NULL, 1, -1, 30),
(119, 'ques9', 'What is the question 9?', NULL, 'answer to ques 9', NULL, 1, -1, 30);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `u_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `roll` varchar(9) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pass` varchar(100) NOT NULL,
  `qualified` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `name`, `username`, `roll`, `mobile`, `email`, `pass`, `qualified`) VALUES
(1, 'sumit', 'sumit', '1230222', '3435365', 'a4@g.com', '$2y$10$2LsQzVxLUgwDwzjoCpYgJeXSdaSWXBJV5nlZsC936NUulouOfPuy6', '0'),
(2, 'sumit122', 'sumit123', '1301cs22', '42324545', 'a4@g.com', '$2y$10$Qdr7FVIkeedLpPkNC1u14OlWm7u3da0gGbRcF2kJnUtispBhzioK6', '0'),
(3, 'Aditya Gupta', 'Aditya', '1301cs02', '839', 'aditya.guptak95@gmail.com', '$2y$10$zWPw6JHwwrtgCI9xxamFCOddJOl09DHomx2Fx73PIJP3q.j1R2uCi', '0');

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `user_data_update` AFTER INSERT ON `user`
 FOR EACH ROW INSERT INTO user_data (`u_id`) VALUES (NEW.u_id)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE IF NOT EXISTS `user_data` (
  `u_id` int(11) NOT NULL,
  `t_score` int(10) NOT NULL DEFAULT '0',
  `level` int(1) NOT NULL DEFAULT '1',
  `hints_used` int(10) NOT NULL DEFAULT '0' COMMENT 'to be changed',
  `l_score` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`u_id`, `t_score`, `level`, `hints_used`, `l_score`) VALUES
(1, 180, 1, 1, 180),
(2, 30, 1, 0, 30),
(3, 30, 1, 0, 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD KEY `fk_qid` (`q_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`q_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD KEY `fk_uid` (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `info`
--
ALTER TABLE `info`
  ADD CONSTRAINT `fk_qid` FOREIGN KEY (`q_id`) REFERENCES `questions` (`q_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_data`
--
ALTER TABLE `user_data`
  ADD CONSTRAINT `fk_uid` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
