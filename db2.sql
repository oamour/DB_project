-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2019 at 03:31 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `userID` int(11) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `salt` char(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`userID`, `username`, `hash`, `salt`) VALUES
(11, 'oamour22@gmail.com', '$2y$10$T9xZ3rw9vlZUV17dowRBGu4pw6MwYVhWuyGeuITTPZmtVqK68Ontu', NULL),
(12, 'test_p1@gmail.com', '$2y$10$bd7umEBDywWYggUyG1PfpOpzmtmJpaLxzD6KqAJocuOenyEb0Bxlu', NULL),
(13, 'test_p2@gmail.com', '$2y$10$e120ed62kUGtgrR/AE9hmOuY5LCjWpfM3j6qiNlPaXO0h/2vw2sGe', NULL),
(14, 'test_p3@gmail.com', '$2y$10$PkFhyRXI3Q75JWEFD6Si5.6.YxJHopPKGXqO7/ncspgJz/jrhScbq', NULL),
(15, 'test_p4@gmail.com', '$2y$10$1J9QEqMq/LqvFH.27nKuU.G0j5O1LoruayWIbwWaq8M9BoWUwxoUe', NULL),
(16, 'test_p5@gmail.com', '$2y$10$5lXwgH9hQMpEyl/mZ0KnRO2qKdSnro0DcZTqnEeGyHsdIyIm.pNLu', NULL),
(17, 'test_p6@gmail.com', '$2y$10$XrjXp1JzvYirVkRWW1K3weDYfylFqEqlTxHqz947HNXpiv9CFtE2C', NULL),
(18, 'test_p7@gmail.com', '$2y$10$HskPzDsUad05J2Cv8gc1puYC3dyk7wMd2/gEvt5noQuLONV8HBKka', NULL),
(19, 'test_p8@gmail.com', '$2y$10$3us3h6hvcf0YKuO0cYHI4e2FSeHeYxI8k8Q6tYnasqF9nEdYRoRb.', NULL),
(20, 'test_p9@gmail.com', '$2y$10$PTH2rRw4UOKG/mG5twaJAuAqhIyMxYMFDo5Njaw8z.DGaP7y4P58u', NULL),
(30, 'test_s10@gmail.com', '$2y$10$WuOYabJuMdH2zHCptRKHze79NeVtkqN0L4dmZ7A4JJ3pMe1Ns5gEG', NULL),
(31, 'test_s11@gmail.com', '$2y$10$/QYneKLgct.96VekQxD.6ecAADANUZxVqben5ikStKEnGsOmA76uO', NULL),
(32, 'test_s12@gmail.com', '$2y$10$LaM6XQUeEwkcQIQYEySa0enUHatwH.bx3K2odElb7FPS.kjntEYtu', NULL),
(33, 'test_s13@gmail.com', '$2y$10$OCLcOr63v/Ihm42I/q3RaeznlEOJA91eNqMIJIF2Dx/5maxXcpNne', NULL),
(34, 'test_s14@gmail.com', '$2y$10$NvC6CBGNTGBW0svsHic5fuwfci9kECxFpXaiSY.2mTPdpgWmEpFia', NULL),
(35, 'test_s15@gmail.com', '$2y$10$qX41c9uxCn.fZXWuZ6Ttn.mNo/e56KmnRnJs/BwrYedUYsfFQih3C', NULL),
(36, 'test_s16@gmail.com', '$2y$10$Z7jyWZakyg14yj1aIQqgpuF915YGnzXfJIndtyaynUNIkRNKL2LBO', NULL),
(37, 'test_s17@gmail.com', '$2y$10$9sn/Gahs.I15iIYHjb2MiuS1Y1DyImK6uyZs3wuRowUxq9OWNAPLq', NULL),
(38, 'test_s18@gmail.com', '$2y$10$OPurmi8sE1jQu9e104puk.rQejkjAoe2lLTviAoC.RcfsWjHpCLMq', NULL),
(39, 'test_s19@gmail.com', '$2y$10$0kjn6F/5NXhlDc.9ULKMWeC35m8BzGUN0mPvLfOWeoH40BXcjispu', NULL),
(21, 'test_s1@gmail.com', '$2y$10$OW.WCtWNA/o7SuVugDZVZeTmXqpIIyg3PsOMF86A9wHWTELsf8eJe', NULL),
(40, 'test_s20@gmail.com', '$2y$10$ZnTeHXAlKpPQ9EbrMuqSVeKQ4s7LbsUFGKCa3xYqC5HUV8SnhjpkG', NULL),
(22, 'test_s2@gmail.com', '$2y$10$HYUs0Irk03jhEjnFgWcgGOtbwy501YG7UCMiN3jkdVQ9FCQyMQyby', NULL),
(23, 'test_s3@gmail.com', '$2y$10$OrMjp6Hz6FlmLfUal1jVUOmFllipT08Drb19AbG9pBUrGhevO.43q', NULL),
(24, 'test_s4@gmail.com', '$2y$10$ACis3gKDd.FJOQ6/aAq8L.M9wPgRUBUFGsG7Du9Iihm6b/aqJ5FQi', NULL),
(25, 'test_s5@gmail.com', '$2y$10$TxL1K0x7z.USgSIu53NbK.LB8Nf4zBq2JKWUnX0SwLsqUJw7E8GtS', NULL),
(26, 'test_s6@gmail.com', '$2y$10$V1R1hggIlCRdA5IaNFSfAOx6E/eWOFwfkfLGGN.5wOKFqnR538STS', NULL),
(27, 'test_s7@gmail.com', '$2y$10$znS0RfuNAgvtkz.UstlF7.kQWLKzVtSbQKP9sM.E91zpa3An6.1BG', NULL),
(28, 'test_s8@gmail.com', '$2y$10$hGJO6LEpXlLgiTndw35KJu/adYj3PIRhFGlA8npSRWzPBwRhOV3uu', NULL),
(29, 'test_s9@gmail.com', '$2y$10$gV3zEJoqez1YHJiAPiOeJuMqzJgMividZrfLF8xAr3vMFGFIQQe82', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseID` int(11) NOT NULL,
  `title` varchar(80) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `mentorReq` int(11) DEFAULT NULL,
  `menteeReq` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseID`, `title`, `description`, `mentorReq`, `menteeReq`) VALUES
(1, 'The History of Funk', 'Funk Music', 3, 2),
(2, 'European History', 'European history from the Middle ages through the Reneisance', 3, 2),
(3, 'Algebra I', 'The foundations of Algebra', 1, 1),
(4, 'Algebra II', 'Advanced algebra building off of Algebra I', 3, 2),
(5, 'Organic Chemistry', 'Advanced Organic chemistry for chemical engineers.', 2, 1),
(6, 'Computing I', 'Introductory Computing', 1, 1),
(7, 'Database', 'introduction to dabase management and creation.', 1, 1),
(8, 'Algorythms', 'how to design efficent algroythms.', 2, 2),
(9, 'Computer Security', 'a joke', 3, 1),
(10, 'Geology', 'the history of earth', 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `materialfor`
--

CREATE TABLE `materialfor` (
  `studyMaterialID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `assignedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `materialfor`
--

INSERT INTO `materialfor` (`studyMaterialID`, `courseID`, `sectionID`, `sessionID`, `assignedDate`) VALUES
(3, 2, 2, 2, '2019-03-28'),
(4, 2, 2, 2, '2019-03-28'),
(5, 2, 1, 1, '2019-03-28'),
(6, 2, 1, 1, '2019-03-28'),
(7, 2, 1, 1, '2019-03-28'),
(8, 2, 1, 1, '2019-03-28'),
(9, 3, 1, 1, '2019-03-28'),
(10, 3, 1, 1, '2019-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `menteefor`
--

CREATE TABLE `menteefor` (
  `menteeID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menteefor`
--

INSERT INTO `menteefor` (`menteeID`, `sectionID`, `courseID`) VALUES
(23, 1, 1),
(23, 10, 7),
(24, 1, 2),
(24, 1, 6),
(24, 10, 7),
(26, 1, 1),
(26, 1, 4),
(26, 3, 2),
(26, 10, 7),
(27, 1, 4),
(27, 2, 2),
(27, 6, 8);

-- --------------------------------------------------------

--
-- Table structure for table `mentees`
--

CREATE TABLE `mentees` (
  `menteeID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mentees`
--

INSERT INTO `mentees` (`menteeID`, `userID`) VALUES
(21, 21),
(23, 23),
(24, 24),
(26, 26),
(27, 27),
(29, 29),
(30, 30),
(32, 32),
(33, 33),
(35, 35),
(36, 36),
(38, 38),
(39, 39);

-- --------------------------------------------------------

--
-- Table structure for table `mentorfor`
--

CREATE TABLE `mentorfor` (
  `mentorID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mentorfor`
--

INSERT INTO `mentorfor` (`mentorID`, `sectionID`, `courseID`) VALUES
(22, 1, 6),
(22, 10, 7),
(23, 1, 4),
(23, 1, 6),
(23, 3, 2),
(26, 2, 6),
(26, 6, 8),
(29, 1, 6),
(29, 10, 7);

-- --------------------------------------------------------

--
-- Table structure for table `mentors`
--

CREATE TABLE `mentors` (
  `mentorID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mentors`
--

INSERT INTO `mentors` (`mentorID`, `userID`) VALUES
(22, 22),
(23, 23),
(25, 25),
(26, 26),
(28, 28),
(29, 29),
(31, 31),
(32, 32),
(34, 34),
(35, 35),
(37, 37),
(38, 38),
(40, 40);

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE `moderators` (
  `modID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `superMod` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `moderators`
--

INSERT INTO `moderators` (`modID`, `userID`, `superMod`) VALUES
(11, 11, 0),
(13, 13, 0),
(15, 15, 0),
(17, 17, 0),
(18, 18, 0),
(19, 19, 0);

-- --------------------------------------------------------

--
-- Table structure for table `modfor`
--

CREATE TABLE `modfor` (
  `modID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modfor`
--

INSERT INTO `modfor` (`modID`, `sectionID`, `courseID`) VALUES
(11, 1, 2),
(11, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `parentchild`
--

CREATE TABLE `parentchild` (
  `parentID` int(11) NOT NULL,
  `childID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parentchild`
--

INSERT INTO `parentchild` (`parentID`, `childID`) VALUES
(1, 3),
(5, 8),
(5, 9),
(5, 10),
(11, 21),
(11, 22),
(11, 23),
(12, 24),
(12, 25),
(12, 26),
(12, 27),
(14, 28),
(14, 29),
(14, 30),
(14, 31),
(14, 32),
(14, 33),
(18, 34),
(18, 35),
(18, 36),
(19, 37),
(19, 38),
(19, 39),
(19, 40);

-- --------------------------------------------------------

--
-- Table structure for table `participatingin`
--

CREATE TABLE `participatingin` (
  `userID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `mentor` tinyint(1) DEFAULT NULL,
  `mentee` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `participatingin`
--

INSERT INTO `participatingin` (`userID`, `sessionID`, `sectionID`, `courseID`, `mentor`, `mentee`) VALUES
(23, 1, 1, 4, 1, 0),
(23, 6, 1, 6, 1, 0),
(24, 6, 1, 6, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `postmaterials`
--

CREATE TABLE `postmaterials` (
  `modID` int(11) NOT NULL,
  `studyMaterialID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `postmaterials`
--

INSERT INTO `postmaterials` (`modID`, `studyMaterialID`) VALUES
(11, 5),
(11, 6),
(11, 7),
(11, 8),
(11, 9),
(11, 10);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `courseID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `menteeCapacity` int(11) DEFAULT '6',
  `mentorCapacity` int(11) NOT NULL DEFAULT '3',
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `timeSlotID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`courseID`, `sectionID`, `name`, `menteeCapacity`, `mentorCapacity`, `startDate`, `endDate`, `timeSlotID`) VALUES
(1, 1, 'FUNk 101', 5, 3, '2019-04-10', '2019-05-14', 1),
(2, 1, 'The Dark Ages', 6, 3, '2019-03-20', '2019-05-18', 1),
(3, 1, 'Algebra I', 5, 3, '2018-10-31', '2018-12-31', 4),
(4, 1, 'Algebra II', 6, 3, '2018-12-31', '2019-12-31', 15),
(6, 1, 'ER diagrams and YOU!', 6, 3, '2019-09-02', '2019-12-20', 6),
(2, 2, 'The Dark Ages', 6, 3, '2019-03-20', '2019-05-18', 1),
(6, 2, 'The SQL to database I', 5, 3, '2019-09-02', '2019-12-20', 7),
(2, 3, 'The Dark Ages', 4, 2, '2020-03-20', '2020-05-18', 18),
(8, 6, 'Kerberos 201', 6, 3, '2019-09-02', '2019-12-20', 6),
(7, 10, 'Algoythms 101', 4, 2, '2020-01-05', '2020-05-15', 12);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `courseID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `sessionDate` date DEFAULT NULL,
  `sessionID` int(11) NOT NULL,
  `announcement` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`courseID`, `sectionID`, `sessionDate`, `sessionID`, `announcement`) VALUES
(2, 1, NULL, 1, 'Hello Class'),
(2, 2, '2019-04-01', 1, 'Announcement I'),
(2, 2, '2019-04-03', 2, 'Announcement II'),
(3, 1, NULL, 1, 'No Announcements'),
(4, 1, '2019-04-06', 1, 'Announcement IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII'),
(6, 1, '2019-09-02', 1, 'No announcement'),
(6, 1, '2019-09-04', 2, 'No announcement'),
(6, 1, '2019-09-06', 3, 'No announcement'),
(6, 1, '2019-09-08', 4, 'No announcement'),
(6, 1, '2019-09-10', 5, 'No announcement'),
(6, 1, '2019-04-01', 6, 'No announcement');

-- --------------------------------------------------------

--
-- Table structure for table `studymaterials`
--

CREATE TABLE `studymaterials` (
  `studyMaterialID` int(11) NOT NULL,
  `title` varchar(80) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `materialType` varchar(80) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studymaterials`
--

INSERT INTO `studymaterials` (`studyMaterialID`, `title`, `author`, `materialType`, `url`, `notes`) VALUES
(1, 'Collonial America', 'James Dawson', 'Book', 'None', 'The textbook'),
(2, 'University Algebra', 'Mel Brooks', 'book', 'None', 'this is the testbook for Algebra I and II'),
(3, 'Moderating', 'A Moderator', 'Book', '127.0.0.1', 'This Goes Nowhere.'),
(4, 'Phase 2 Project', '', 'Homework', 'https://docs.google.com/viewer?a=v&pid=forums&srcid=MDE3MjkyNjM0MDcyOTI4NjMwNzgBMTQ5ODcyMzEwNjIwMDMxMzUyMDIBOE4yZjdONDNBUUFKATAuMQEBdjI&authuser=0', 'Ooops. How did that get there'),
(5, 'The History of Earth', 'Jamie Dawson', 'Slides', 'Insert URL HERE', 'There is a url attached.'),
(6, 'Alpha', 'Bear', 'Other', 'Not A URL', ''),
(7, 'Bravo', 'Phil Nevile', 'Homework', 'URL', 'This is too hard'),
(8, 'A history of Database U.', 'Owen Amour', 'Book', 'Not A URL', 'The History of our glorious University'),
(9, 'Moderates', 'liberal', 'Book', 'I DONT KNOW?!?', 'no notes'),
(10, 'AHHHHH', 'AHHHHH', 'Homework', 'AHHHHH', 'a homework');

-- --------------------------------------------------------

--
-- Table structure for table `timeslot`
--

CREATE TABLE `timeslot` (
  `M` tinyint(1) DEFAULT NULL,
  `T` tinyint(1) DEFAULT NULL,
  `W` tinyint(1) DEFAULT NULL,
  `Th` tinyint(1) DEFAULT NULL,
  `F` tinyint(1) DEFAULT NULL,
  `Sa` tinyint(1) DEFAULT NULL,
  `timeSlotID` int(11) NOT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timeslot`
--

INSERT INTO `timeslot` (`M`, `T`, `W`, `Th`, `F`, `Sa`, `timeSlotID`, `startTime`, `endTime`) VALUES
(1, 0, 1, 0, 1, 0, 1, '08:00:00', '08:50:00'),
(1, 0, 1, 0, 1, 0, 2, '09:00:00', '09:50:00'),
(1, 0, 1, 0, 1, 0, 3, '10:00:00', '10:50:00'),
(1, 0, 1, 0, 1, 0, 4, '11:00:00', '11:50:00'),
(1, 0, 1, 0, 1, 0, 5, '12:00:00', '12:50:00'),
(1, 0, 1, 0, 1, 0, 6, '13:00:00', '13:50:00'),
(1, 0, 1, 0, 1, 0, 7, '14:00:00', '14:50:00'),
(1, 0, 1, 0, 1, 0, 8, '15:00:00', '15:50:00'),
(1, 0, 1, 0, 1, 0, 9, '16:00:00', '16:50:00'),
(1, 0, 1, 0, 1, 0, 10, '17:00:00', '17:50:00'),
(0, 1, 0, 1, 0, 0, 11, '08:00:00', '09:15:00'),
(0, 1, 0, 1, 0, 0, 12, '09:30:00', '10:45:00'),
(0, 1, 0, 1, 0, 0, 13, '11:00:00', '12:15:00'),
(0, 1, 0, 1, 0, 0, 14, '12:30:00', '13:45:00'),
(0, 1, 0, 1, 0, 0, 15, '14:00:00', '15:15:00'),
(0, 1, 0, 1, 0, 0, 16, '15:30:00', '16:45:00'),
(0, 1, 0, 1, 0, 0, 17, '17:00:00', '18:15:00'),
(0, 0, 0, 0, 0, 1, 18, '08:00:00', '10:50:00'),
(0, 0, 0, 0, 0, 1, 19, '11:00:00', '13:50:00'),
(0, 0, 0, 0, 0, 1, 20, '14:00:00', '16:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` char(10) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `gradeLevel` int(11) DEFAULT NULL,
  `isParent` tinyint(1) DEFAULT NULL,
  `isStudent` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `phone`, `city`, `state`, `gradeLevel`, `isParent`, `isStudent`) VALUES
(11, 'Owen Amour', 'oamour22@gmail.com', '7736957397', 'Milford', 'MA', NULL, 1, 0),
(12, 'Nick Bishop', 'test_p1@gmail.com', '5846537133', 'Nahant', 'MA', NULL, 1, 0),
(13, 'Nora Bishop', 'test_p2@gmail.com', '6819935920', 'Nahant', 'MA', NULL, 1, 0),
(14, 'David Bishop', 'test_p3@gmail.com', '7008122447', 'Nahant', 'MA', NULL, 1, 0),
(15, 'Pierre Nash', 'test_p4@gmail.com', '1881453583', 'Salem', 'NH', NULL, 1, 0),
(16, 'Kieran Dickenson', 'test_p5@gmail.com', '6330677218', 'Salem', 'NH', NULL, 1, 0),
(17, 'Jaime Lowell', 'test_p6@gmail.com', '4821183286', 'Lowell', 'MA', NULL, 1, 0),
(18, 'Sammy Blue', 'test_p7@gmail.com', '6086308447', 'Lowell', 'MA', NULL, 1, 0),
(19, 'Carlota Baldwin', 'test_p8@gmail.com', '8010441598', 'Lowell', 'MA', NULL, 1, 0),
(20, 'Fern Paige', 'test_p9@gmail.com', '0158225357', 'Lowell', 'MA', NULL, 1, 0),
(21, 'Anona Wilkins', 'test_s1@gmail.com', '6187509188', 'Lowell', 'MA', 1, 0, 1),
(22, 'Matilda Bristow', 'test_s2@gmail.com', '5736854922', 'Lowell', 'MA', 2, 0, 1),
(23, 'Riley Payne', 'test_s3@gmail.com', '9318595665', 'Lowell', 'MA', 3, 0, 1),
(24, 'Alita Lowe', 'test_s4@gmail.com', '9192393951', 'Lowell', 'MA', 4, 0, 1),
(25, 'Langdon Dwight', 'test_s5@gmail.com', '1648595808', 'Lowell', 'MA', 1, 0, 1),
(26, 'Scott Glover', 'test_s6@gmail.com', '4239525757', 'Lowell', 'MA', 2, 0, 1),
(27, 'Stacee Jewel', 'test_s7@gmail.com', '1035359574', 'Lowell', 'MA', 3, 0, 1),
(28, 'Crofton Crawford', 'test_s8@gmail.com', '0033732978', 'Lowell', 'MA', 4, 0, 1),
(29, 'Tiara Linwood', 'test_s9@gmail.com', '2233020535', 'Lowell', 'MA', 1, 0, 1),
(30, 'Alec Deniau', 'test_s10@gmail.com', '5704324470', 'Lowell', 'MA', 2, 0, 1),
(31, 'Sawyer Mullins', 'test_s11@gmail.com', '0939915056', 'Lowell', 'MA', 3, 0, 1),
(32, 'Jessie Fishman', 'test_s12@gmail.com', '1433065767', 'Lowell', 'MA', 4, 0, 1),
(33, 'Darwin Bond', 'test_s13@gmail.com', '0680625999', 'Lowell', 'MA', 1, 0, 1),
(34, 'Candis Yamada', 'test_s14@gmail.com', '9665454603', 'Lowell', 'MA', 2, 0, 1),
(35, 'Juanita Danniel', 'test_s15@gmail.com', '6245442623', 'Lowell', 'MA', 3, 0, 1),
(36, 'Shichirou Lopez', 'test_s16@gmail.com', '0798942292', 'Lowell', 'MA', 4, 0, 1),
(37, 'Yancy Arriola', 'test_s17@gmail.com', '8535415964', 'Lowell', 'MA', 1, 0, 1),
(38, 'Praise Wilkinson', 'test_s18@gmail.com', '5014819117', 'Lowell', 'MA', 2, 0, 1),
(39, 'Jazmine Patrick', 'test_s19@gmail.com', '0082716621', 'Lowell', 'MA', 3, 0, 1),
(40, 'Rikuto Ott', 'test_s20@gmail.com', '7683357368', 'Lowell', 'MA', 4, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`username`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseID`);

--
-- Indexes for table `materialfor`
--
ALTER TABLE `materialfor`
  ADD PRIMARY KEY (`studyMaterialID`,`courseID`,`sectionID`),
  ADD KEY `sectionID` (`sectionID`);

--
-- Indexes for table `menteefor`
--
ALTER TABLE `menteefor`
  ADD PRIMARY KEY (`menteeID`,`sectionID`,`courseID`),
  ADD KEY `sectionID` (`sectionID`);

--
-- Indexes for table `mentees`
--
ALTER TABLE `mentees`
  ADD PRIMARY KEY (`menteeID`,`userID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `mentorfor`
--
ALTER TABLE `mentorfor`
  ADD PRIMARY KEY (`mentorID`,`sectionID`,`courseID`),
  ADD KEY `sectionID` (`sectionID`),
  ADD KEY `mentorfor_ibfk_2` (`courseID`,`sectionID`);

--
-- Indexes for table `mentors`
--
ALTER TABLE `mentors`
  ADD PRIMARY KEY (`mentorID`,`userID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `moderators`
--
ALTER TABLE `moderators`
  ADD PRIMARY KEY (`modID`,`userID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `modfor`
--
ALTER TABLE `modfor`
  ADD PRIMARY KEY (`modID`,`sectionID`,`courseID`),
  ADD KEY `modID` (`modID`),
  ADD KEY `courseID` (`courseID`,`sectionID`);

--
-- Indexes for table `parentchild`
--
ALTER TABLE `parentchild`
  ADD PRIMARY KEY (`parentID`,`childID`);

--
-- Indexes for table `participatingin`
--
ALTER TABLE `participatingin`
  ADD PRIMARY KEY (`userID`,`courseID`,`sectionID`,`sessionID`),
  ADD KEY `sessionID` (`sessionID`),
  ADD KEY `participatingin_ibfk_3` (`courseID`,`sectionID`,`sessionID`);

--
-- Indexes for table `postmaterials`
--
ALTER TABLE `postmaterials`
  ADD PRIMARY KEY (`modID`,`studyMaterialID`),
  ADD KEY `studyMaterialID` (`studyMaterialID`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`sectionID`,`courseID`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`courseID`,`sectionID`,`sessionID`),
  ADD KEY `sectionID` (`courseID`,`sectionID`);

--
-- Indexes for table `studymaterials`
--
ALTER TABLE `studymaterials`
  ADD PRIMARY KEY (`studyMaterialID`);

--
-- Indexes for table `timeslot`
--
ALTER TABLE `timeslot`
  ADD PRIMARY KEY (`timeSlotID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `materialfor`
--
ALTER TABLE `materialfor`
  ADD CONSTRAINT `materialfor_ibfk_1` FOREIGN KEY (`studyMaterialID`) REFERENCES `studymaterials` (`studyMaterialID`) ON DELETE CASCADE,
  ADD CONSTRAINT `materialfor_ibfk_2` FOREIGN KEY (`courseID`,`sectionID`) REFERENCES `sections` (`courseID`,`sectionID`) ON DELETE CASCADE;

--
-- Constraints for table `menteefor`
--
ALTER TABLE `menteefor`
  ADD CONSTRAINT `menteefor_ibfk_1` FOREIGN KEY (`menteeID`) REFERENCES `mentees` (`menteeID`) ON DELETE CASCADE,
  ADD CONSTRAINT `menteefor_ibfk_2` FOREIGN KEY (`courseID`,`sectionID`) REFERENCES `sections` (`courseID`,`sectionID`) ON DELETE CASCADE;

--
-- Constraints for table `mentees`
--
ALTER TABLE `mentees`
  ADD CONSTRAINT `mentees_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `mentorfor`
--
ALTER TABLE `mentorfor`
  ADD CONSTRAINT `mentorfor_ibfk_1` FOREIGN KEY (`mentorID`) REFERENCES `mentors` (`mentorID`) ON DELETE CASCADE,
  ADD CONSTRAINT `mentorfor_ibfk_2` FOREIGN KEY (`courseID`,`sectionID`) REFERENCES `sections` (`courseID`, `sectionID`) ON DELETE CASCADE;

--
-- Constraints for table `mentors`
--
ALTER TABLE `mentors`
  ADD CONSTRAINT `mentors_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `moderators`
--
ALTER TABLE `moderators`
  ADD CONSTRAINT `moderators_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `modfor`
--
ALTER TABLE `modfor`
  ADD CONSTRAINT `modfor_ibfk_1` FOREIGN KEY (`modID`) REFERENCES `moderators` (`modID`) ON DELETE CASCADE,
  ADD CONSTRAINT `modfor_ibfk_2` FOREIGN KEY (`courseID`,`sectionID`) REFERENCES `sections` (`courseID`, `sectionID`) ON DELETE CASCADE;

--
-- Constraints for table `participatingin`
--
ALTER TABLE `participatingin`
  ADD CONSTRAINT `participatingin_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `participatingin_ibfk_3` FOREIGN KEY (`courseID`,`sectionID`,`sessionID`) REFERENCES `sessions` (`courseID`, `sectionID`, `sessionID`) ON DELETE CASCADE;

--
-- Constraints for table `postmaterials`
--
ALTER TABLE `postmaterials`
  ADD CONSTRAINT `postmaterials_ibfk_1` FOREIGN KEY (`modID`) REFERENCES `moderators` (`modID`) ON DELETE CASCADE,
  ADD CONSTRAINT `postmaterials_ibfk_2` FOREIGN KEY (`studyMaterialID`) REFERENCES `studymaterials` (`studyMaterialID`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`courseID`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`courseID`,`sectionID`) REFERENCES `sections` (`courseID`, `sectionID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
