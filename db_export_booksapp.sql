-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 23, 2018 at 08:52 PM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booksapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
CREATE TABLE IF NOT EXISTS `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `name`, `description`) VALUES
(1, 'Mel Robbins', 'Melanie \"Mel\" Robbins is an American on-air CNN commentator, television host, author, and motivational speaker. Robbins is widely known for covering the George Zimmerman trial and her book The 5 Second Rule.'),
(2, 'Tom Harv', 'T. Harv Eker (born June 10, 1954) is an author, businessman and motivational speaker known for his theories on wealth and motivation'),
(3, 'Michael Wolff', 'Michael Wolff is an American author, essayist, journalist, and a columnist and contributor to USA Today, The Hollywood Reporter, and the UK edition of GQ'),
(4, 'Jordan B. Peterson', 'Jordan Bernt Peterson is a Canadian clinical psychologist and public intellectual, who is professor of psychology at the University of Toronto.'),
(7, 'Dav Pilkey', 'David Murray \"Dav\" Pilkey Jr. is an American author and illustrator of children\'s literature. Pilkey is best known as the author and illustrator of the children\'s book series Captain Underpants');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `year`, `type`) VALUES
(14, 'The Millionair Mind', '2', 2005, 'Business'),
(16, '5 Second Rule', '1', 2015, 'Self');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
