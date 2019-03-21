-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2019 at 08:52 PM
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
(3, 'owen_amour2@student.uml.edu', '$2y$10$7l0Mq/cGxJgN1tr18kwCn.KWfe4qBVGnY1ZaREPyXrmaRu1dPn0Vy', NULL);

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
(2, 'European History', 'European history from the Middle ages through the Reneisance', 3, 2),
(3, 'Algebra I', 'The foundations of Algebra', 1, 1),
(4, 'Algebra II', 'Advanced algebra building off of Algebra I', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `materialfor`
--

CREATE TABLE `materialfor` (
  `studyMaterialID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `assignedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `materialfor`
--

INSERT INTO `materialfor` (`studyMaterialID`, `sectionID`, `assignedDate`) VALUES
(2, 301, NULL),
(2, 401, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menteefor`
--

CREATE TABLE `menteefor` (
  `menteeID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mentorfor`
--

CREATE TABLE `mentorfor` (
  `mentorID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mentorfor`
--

INSERT INTO `mentorfor` (`mentorID`, `sectionID`, `courseID`) VALUES
(3, 301, 3);

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
(3, 3);

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
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `modfor`
--

CREATE TABLE `modfor` (
  `modID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `participatingin`
--

CREATE TABLE `participatingin` (
  `userID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `courseID` int(11) DEFAULT NULL,
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
(2, 201, 'The Dark Ages', 9, NULL, NULL, 45),
(3, 301, 'Algebra I', 7, NULL, NULL, 46),
(4, 401, 'Algebra II', 9, NULL, NULL, 47);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `sectionID` int(11) DEFAULT NULL,
  `sessionDate` date DEFAULT NULL,
  `sessionID` int(11) NOT NULL,
  `announcement` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sectionID`, `sessionDate`, `sessionID`, `announcement`) VALUES
(201, NULL, 2, 'Hello Class'),
(301, NULL, 3, 'No Announcements'),
(401, NULL, 4, 'WE A\'INT FOUND SHIT!');

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
(1, 0, 0, 0, 0, 0, 1, '08:00:00', '08:50:00'),
(0, 0, 1, 0, 0, 0, 2, '08:00:00', '08:50:00'),
(0, 0, 0, 0, 1, 0, 3, '08:00:00', '08:50:00'),
(1, 0, 0, 0, 0, 0, 4, '09:00:00', '09:50:00'),
(0, 0, 1, 0, 0, 0, 5, '09:00:00', '09:50:00'),
(0, 0, 0, 0, 1, 0, 6, '09:00:00', '09:50:00'),
(1, 0, 0, 0, 0, 0, 7, '10:00:00', '10:50:00'),
(0, 0, 1, 0, 0, 0, 8, '10:00:00', '10:50:00'),
(0, 0, 0, 0, 1, 0, 9, '10:00:00', '10:50:00'),
(1, 0, 0, 0, 0, 0, 10, '11:00:00', '11:50:00'),
(0, 0, 1, 0, 0, 0, 11, '11:00:00', '11:50:00'),
(0, 0, 0, 0, 1, 0, 12, '11:00:00', '11:50:00'),
(1, 0, 0, 0, 0, 0, 13, '12:00:00', '12:50:00'),
(0, 0, 1, 0, 0, 0, 14, '12:00:00', '12:50:00'),
(0, 0, 0, 0, 1, 0, 15, '12:00:00', '12:50:00'),
(1, 0, 0, 0, 0, 0, 16, '13:00:00', '13:50:00'),
(0, 0, 1, 0, 0, 0, 17, '13:00:00', '13:50:00'),
(0, 0, 0, 0, 1, 0, 18, '13:00:00', '13:50:00'),
(1, 0, 0, 0, 0, 0, 19, '14:00:00', '14:50:00'),
(0, 0, 1, 0, 0, 0, 20, '14:00:00', '14:50:00'),
(0, 0, 0, 0, 1, 0, 21, '14:00:00', '14:50:00'),
(1, 0, 0, 0, 0, 0, 22, '15:00:00', '15:50:00'),
(0, 0, 1, 0, 0, 0, 23, '15:00:00', '15:50:00'),
(0, 0, 0, 0, 1, 0, 24, '15:00:00', '15:50:00'),
(1, 0, 0, 0, 0, 0, 25, '16:00:00', '16:50:00'),
(0, 0, 1, 0, 0, 0, 26, '16:00:00', '16:50:00'),
(0, 0, 0, 0, 1, 0, 27, '16:00:00', '16:50:00'),
(1, 0, 0, 0, 0, 0, 28, '17:00:00', '17:50:00'),
(0, 0, 1, 0, 0, 0, 29, '17:00:00', '17:50:00'),
(0, 0, 0, 0, 1, 0, 30, '17:00:00', '17:50:00'),
(0, 1, 0, 0, 0, 0, 31, '08:00:00', '09:20:00'),
(0, 0, 0, 1, 0, 0, 32, '08:00:00', '09:20:00'),
(0, 1, 0, 0, 0, 0, 33, '09:30:00', '10:50:00'),
(0, 0, 0, 1, 0, 0, 34, '09:30:00', '10:50:00'),
(0, 1, 0, 0, 0, 0, 35, '11:00:00', '12:20:00'),
(0, 0, 0, 1, 0, 0, 36, '11:00:00', '12:20:00'),
(0, 1, 0, 0, 0, 0, 37, '12:30:00', '13:50:00'),
(0, 0, 0, 1, 0, 0, 38, '12:30:00', '13:50:00'),
(0, 1, 0, 0, 0, 0, 39, '14:00:00', '15:20:00'),
(0, 0, 0, 1, 0, 0, 40, '14:00:00', '15:20:00'),
(0, 1, 0, 0, 0, 0, 41, '15:30:00', '16:50:00'),
(0, 0, 0, 1, 0, 0, 42, '15:30:00', '16:50:00'),
(0, 1, 0, 0, 0, 0, 43, '17:00:00', '18:20:00'),
(0, 0, 0, 1, 0, 0, 44, '17:00:00', '18:20:00'),
(1, 0, 1, 0, 1, 0, 45, '08:00:00', '08:50:00'),
(0, 1, 0, 1, 0, 0, 46, '08:00:00', '09:20:00'),
(0, 1, 0, 1, 0, 0, 47, '09:30:00', '10:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` char(10) DEFAULT NULL,
  `gradeLevel` int(11) DEFAULT NULL,
  `isParent` tinyint(1) DEFAULT NULL,
  `isStudent` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `phone`, `gradeLevel`, `isParent`, `isStudent`) VALUES
(1, 'Oven Amuur', 'oamour22@gmail.com', '5084731921', NULL, 1, 0),
(3, 'Owel Manure2', 'owen_amour2@student.uml.edu', '5084986253', 4, 0, 1);

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
  ADD PRIMARY KEY (`studyMaterialID`,`sectionID`),
  ADD KEY `sectionID` (`sectionID`);

--
-- Indexes for table `menteefor`
--
ALTER TABLE `menteefor`
  ADD PRIMARY KEY (`menteeID`,`sectionID`),
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
  ADD PRIMARY KEY (`mentorID`,`sectionID`),
  ADD KEY `sectionID` (`sectionID`);

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
  ADD KEY `sectionID` (`sectionID`);

--
-- Indexes for table `parentchild`
--
ALTER TABLE `parentchild`
  ADD PRIMARY KEY (`parentID`,`childID`);

--
-- Indexes for table `participatingin`
--
ALTER TABLE `participatingin`
  ADD PRIMARY KEY (`userID`,`sectionID`,`sessionID`),
  ADD KEY `sectionID` (`sectionID`),
  ADD KEY `sessionID` (`sessionID`);

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
  ADD PRIMARY KEY (`sectionID`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sessionID`),
  ADD KEY `sectionID` (`sectionID`);

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
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `mentorfor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `modfor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`) ON DELETE CASCADE;

--
-- Constraints for table `participatingin`
--
ALTER TABLE `participatingin`
  ADD CONSTRAINT `participatingin_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `participatingin_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `participatingin_ibfk_3` FOREIGN KEY (`sessionID`) REFERENCES `sessions` (`sessionID`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
