-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1

-- Generation Time: Mar 26, 2019 at 04:27 PM
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
(1, 'oamour22@gmail.com', '$2y$10$qStzXyp0CRJH15seykyUaeLc1VLLiAiRBE/Bghpp5te8LfljgiMxm', NULL),
(3, 'owen_amour2@student.uml.edu', '$2y$10$7l0Mq/cGxJgN1tr18kwCn.KWfe4qBVGnY1ZaREPyXrmaRu1dPn0Vy', NULL),
(5, 'test_p1@gmail.com', '$2y$10$lTM5FKwugwWrs6MPsJqC2ums5qgkdPBZiwVLwZRNg/81ulfvz2oeO', NULL),
(6, 'test_p2@gmail.com', '$2y$10$kCL72Uoh5LRv0vyxssHWeec4zosxzOMqhl58iFuIiYZy721Izixgy', NULL),
(7, 'test_p3@gmail.com', '$2y$10$Nn59svNTX/s5D3TuOfs6Luy1BeYXDty2PdVJiZji0/TMK1oMkzhS.', NULL),
(8, 'test_s1@gmail.com', '$2y$10$aKXWiUjH0jymYcdg6puZA.RN7XkpBlKezPPl9M9FtsxICqEVD4Nuu', NULL),
(9, 'test_s2@gmail.com', '$2y$10$DETk2Nna.6a6VxnyJrtWkuBUVZ1q.zS5fg3ysqwNxhz/2FPSEQWUW', NULL),
(10, 'test_s3@gmail.com', '$2y$10$.6GA/.jMvFj1rx6aO.eFdOK3BfAA1Pc61RKVztO4a3V2e9qhwXYXW', NULL);

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
(1, 'The History fo Funk', 'Funk Music', 3, 2),
(2, 'European History', 'European history from the Middle ages through the Reneisance', 3, 2),
(3, 'Algebra I', 'The foundations of Algebra', 1, 1),
(4, 'Algebra II', 'Advanced algebra building off of Algebra I', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `materialfor`
--

CREATE TABLE `materialfor` (
  `studyMaterialID` int(11) NOT NULL,
  `courseID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `assignedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(3, 1, 1),
(3, 2, 2);

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
(3, 3),
(8, 8),
(9, 9);

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
(3, 1, 4);

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
(3, 3),
(8, 8),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE `moderators` (
  `modID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `moderators`
--

INSERT INTO `moderators` (`modID`, `userID`) VALUES
(1, 1),
(5, 5);

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
(1, 1, 4);

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
(1, 2),
(1, 3),
(5, 8),
(5, 9),
(5, 10);

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
(3, 1, 2, 2, 0, 1),
(3, 2, 2, 2, 0, 1),
(3, 1, 1, 4, 1, 0);

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
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `courseID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `timeSlotID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`courseID`, `sectionID`, `name`, `capacity`, `startDate`, `endDate`, `timeSlotID`) VALUES
(1, 1, 'FUNk 101', 9999, '2019-04-10', '2019-05-14', 1),
(2, 1, 'The Dark Ages', 7, '2019-03-20', '2019-05-18', 1),
(3, 1, 'Algebra I', 5, '2018-10-31', '2018-12-31', 4),
(4, 1, 'Algebra II', 7, '2018-12-31', '2019-12-31', 15),
(2, 2, 'The Dark Ages', 7, '2019-03-20', '2019-05-18', 1),
(2, 3, 'The Dark Ages', 8, '2020-03-20', '2020-05-18', 18);

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
(2, 2, '2019-03-27', 1, 'Hello Class'),
(2, 2, '2019-03-29', 2, 'Hello Class'),
(3, 1, NULL, 1, 'No Announcements'),
(4, 1, '2019-03-26', 1, 'No Announcements');

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
(2, 'University Algebra', 'Mel Brooks', 'book', 'Now', 'When is then? Now. Well when will now be then? Soon.');

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
(1, 'Oven Amuur', 'oamour22@gmail.com', '5084731921', NULL, NULL, NULL, 1, 0),
(3, 'Owel Florin', 'owen_amour2@student.uml.edu', '5084986253', NULL, NULL, 4, 0, 1);
(5, 'Nick', 'test_p1@gmail.com', '1234567890', 'Nahant', 'MA', NULL, 1, 0),
(6, 'David', 'test_p2@gmail.com', '1234567890', 'Salem', 'NH', NULL, 1, 0),
(7, 'Jaime', 'test_p3@gmail.com', '1234567890', 'Salem', 'MA', NULL, 1, 0),
(8, 'Nora', 'test_s1@gmail.com', '1002003004', 'Nahant', 'MA', 4, 0, 1),
(9, 'Sarah', 'test_s2@gmail.com', '1002003004', 'Nahant', 'MA', 2, 0, 1),
(10, 'Ralph', 'test_s3@gmail.com', '1002003004', 'Nahant', 'MA', 3, 0, 1);

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
  ADD PRIMARY KEY (`modID`,`sectionID`),
  ADD KEY `sectionID` (`sectionID`),
  ADD KEY `modfor_ibfk_2` (`courseID`,`sectionID`);

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
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `materialfor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`) ON DELETE CASCADE;

--
-- Constraints for table `menteefor`
--
ALTER TABLE `menteefor`
  ADD CONSTRAINT `menteefor_ibfk_1` FOREIGN KEY (`menteeID`) REFERENCES `mentees` (`menteeID`) ON DELETE CASCADE,
  ADD CONSTRAINT `menteefor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`) ON DELETE CASCADE;

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
