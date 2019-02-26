-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2019 at 06:09 PM
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
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `userID` int(11) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `salt` char(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `materialfor`
--

CREATE TABLE `materialfor` (
  `studyMaterialID` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `mentorfor`
--

CREATE TABLE `mentorfor` (
  `mentorID` int(11) NOT NULL,
  `sectionID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mentors`
--

CREATE TABLE `mentors` (
  `mentorID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `moderators`
--

CREATE TABLE `moderators` (
  `modID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `courseID` int(11) DEFAULT NULL,
  `sectionID` int(11) NOT NULL,
  `name` int(11) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `timeSlotID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
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
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `materialfor`
--
ALTER TABLE `materialfor`
  ADD CONSTRAINT `materialfor_ibfk_1` FOREIGN KEY (`studyMaterialID`) REFERENCES `studymaterials` (`studyMaterialID`),
  ADD CONSTRAINT `materialfor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`);

--
-- Constraints for table `menteefor`
--
ALTER TABLE `menteefor`
  ADD CONSTRAINT `menteefor_ibfk_1` FOREIGN KEY (`menteeID`) REFERENCES `mentees` (`menteeID`),
  ADD CONSTRAINT `menteefor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`);

--
-- Constraints for table `mentees`
--
ALTER TABLE `mentees`
  ADD CONSTRAINT `mentees_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `mentorfor`
--
ALTER TABLE `mentorfor`
  ADD CONSTRAINT `mentorfor_ibfk_1` FOREIGN KEY (`mentorID`) REFERENCES `mentors` (`mentorID`),
  ADD CONSTRAINT `mentorfor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`);

--
-- Constraints for table `mentors`
--
ALTER TABLE `mentors`
  ADD CONSTRAINT `mentors_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `moderators`
--
ALTER TABLE `moderators`
  ADD CONSTRAINT `moderators_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `modfor`
--
ALTER TABLE `modfor`
  ADD CONSTRAINT `modfor_ibfk_1` FOREIGN KEY (`modID`) REFERENCES `moderators` (`modID`),
  ADD CONSTRAINT `modfor_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`);

--
-- Constraints for table `participatingin`
--
ALTER TABLE `participatingin`
  ADD CONSTRAINT `participatingin_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `participatingin_ibfk_2` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`),
  ADD CONSTRAINT `participatingin_ibfk_3` FOREIGN KEY (`sessionID`) REFERENCES `sessions` (`sessionID`);

--
-- Constraints for table `postmaterials`
--
ALTER TABLE `postmaterials`
  ADD CONSTRAINT `postmaterials_ibfk_1` FOREIGN KEY (`modID`) REFERENCES `moderators` (`modID`),
  ADD CONSTRAINT `postmaterials_ibfk_2` FOREIGN KEY (`studyMaterialID`) REFERENCES `studymaterials` (`studyMaterialID`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`courseID`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`sectionID`) REFERENCES `sections` (`sectionID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
