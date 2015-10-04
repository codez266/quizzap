-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 04, 2015 at 11:43 AM
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
  `attempts` int(10) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `max_attempts` int(11) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `name`, `username`, `roll`, `mobile`, `email`, `pass`, `qualified`) VALUES
(1, 'dfs', 'xyz2', '43', '434', 'a4@g.com', '$2y$10$piW7wRkgURPM2MyMinyYkeuI3M5c9zMZCZkE1FdrjvNmOz4iBE3qC', '0'),
(2, 'ssser', 'xyz', '12344', '7583927411', 'arindam.cs13@iitp.ac.in', '$2y$10$5Fy8dfvIAnLwwNsEmWr8ZugaJJyHuERJ2ubuQZaQgMWRY9xpXS42K', '0'),
(3, 'rewr', 'xyz1', '123445', '44', 'as@g.com', '$2y$10$EMzvjNm1L0Dbr7U2c8hEp.AewoQ4hRIlg0Uo/ptACRT24GuduEB3q', '0'),
(6, 'sumit', 'sumit', '12322', '7583927411', 'ew0555@yahoo.com', '$2y$10$MqcDwsHJJjCcRWLh056dNOyudsyHTgFv7KE5VmvcNIkNa2/LRaO2a', '0'),
(10, 'dfds', 'sumit1', '34324', '1434', 'aaaa@a.com', '$2y$10$lrQtehV9V/VBZ7zypfeazOuhVf7UL0CRKqaX7Mh6c1Zy8T9//Fdle', '0'),
(12, 'dsf', 'sumit2', '342342', '134234', 'aaa@a.com', '$2y$10$PYVA14bZAy3UBkDskj66N.X6x9n6pdmduEHXjMZrqtkXQotxkuJNq', '0'),
(14, 'Sumita', 'sumit3aa', '1301cs41a', '123455', 'asa@g.com', '$2y$10$z58ilx9mFGL2ZcuhCMu8Bu3TYS/9uHxuTYyGLkXkeJNuM2PY4Ohpi', '0');

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
  `hints_used` int(10) NOT NULL DEFAULT '1' COMMENT 'to be changed',
  `l_score` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

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
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_data`
--
ALTER TABLE `user_data`
  ADD CONSTRAINT `fk_uid` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
