-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 31, 2017 at 09:53 PM
-- Server version: 5.7.9
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_chatsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_group`
--

CREATE TABLE IF NOT EXISTS `chat_group` (
  `chat_group_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_group`
--

INSERT INTO `chat_group` (`chat_group_id`, `name`, `create_date`) VALUES
(1, 'soccer', '2017-03-31 21:38:31'),
(2, 'coding', '2017-03-31 21:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `chat_group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `content`, `user_id`, `create_time`, `chat_group_id`) VALUES
(1, 'Hey guys! I really like soccer, my favorite team is Valencia FC, a club from Valencia, Spain.', 1, '2017-03-31 21:40:12', 1),
(2, 'Did you guys see the new version of PHP? Is the 7.1 and is really amazing, they are trying to update the programming language into a much strict language.', 2, '2017-03-31 21:40:12', 2),
(3, 'Real Madrid is always buying players, that has no merit.', 1, '2017-03-31 21:49:17', 1),
(4, 'Valencia is always having administrative issues, that is what is blocking his potencial.', 1, '2017-03-31 21:49:17', 1),
(5, 'Barcelo depends to much on messi.', 1, '2017-03-31 21:50:09', 1),
(6, 'Would you stop criticising so much? You should start you own team then.', 2, '2017-03-31 21:50:09', 1),
(7, 'I don;t know anything about soccer. I think is a boring sport.', 3, '2017-03-31 21:51:29', 1),
(8, 'Are you stupid or blind? is the best sport in the world. What do you like??', 2, '2017-03-31 21:51:29', 1),
(9, 'The top language for the next 10 years is javascript. Is growing like creazy. Adoption rate is at the ceiling.', 4, '2017-03-31 21:53:09', 2),
(10, 'I don''t agree. Javascript is to flexible. Python is better than javascript in every way. He does everything faster and better.', 2, '2017-03-31 21:53:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'alesanchezr', 'a@breatheco.de', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(2, 'support', 'info@breatheco.de', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(3, 'merk123', 'merquito@breathe.co', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(4, 'cheperio', 'chepe@breatheco.de', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- --------------------------------------------------------

--
-- Table structure for table `user_chat_group`
--

CREATE TABLE IF NOT EXISTS `user_chat_group` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `chat_group_id` int(10) UNSIGNED NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_chat_group`
--

INSERT INTO `user_chat_group` (`user_id`, `chat_group_id`, `create_time`) VALUES
(1, 1, '2017-03-31 21:41:46'),
(1, 2, '2017-03-31 21:41:46'),
(2, 1, '2017-03-31 21:44:40'),
(2, 2, '2017-03-31 21:44:40'),
(3, 1, '2017-03-31 21:44:51'),
(4, 1, '2017-03-31 21:44:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_group`
--
ALTER TABLE `chat_group`
  ADD PRIMARY KEY (`chat_group_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_chat_group`
--
ALTER TABLE `user_chat_group`
  ADD PRIMARY KEY (`user_id`,`chat_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_group`
--
ALTER TABLE `chat_group`
  MODIFY `chat_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
