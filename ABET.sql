-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2018 at 04:56 AM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ABET`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessmentId` int(11) NOT NULL,
  `assessmentPlan` varchar(255) NOT NULL,
  `assessmentWeight` float NOT NULL,
  `rubricId` int(11) NOT NULL,
  `courseOutcomeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessmentId`, `assessmentPlan`, `assessmentWeight`, `rubricId`, `courseOutcomeId`) VALUES
(1, 'Lab 1', 0.2, 1, 1),
(2, 'Lab 1', 0.2, 1, 2),
(3, 'Lab 1', 0.2, 1, 6),
(4, 'Lab 2', 0.2, 1, 1),
(5, 'Lab 3', 0.2, 1, 1),
(6, 'Lab 4', 0.2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `courseOutcomes`
--

CREATE TABLE `courseOutcomes` (
  `courseOutcomeId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `outcomeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courseOutcomes`
--

INSERT INTO `courseOutcomes` (`courseOutcomeId`, `courseId`, `outcomeId`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 3, 3),
(4, 3, 4),
(5, 3, 5),
(6, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseId` int(11) NOT NULL,
  `major` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseId`, `major`, `course`, `userId`) VALUES
(1, 'EE', 'ECE 255', 1),
(2, 'CpE', 'ECE 255', 1),
(3, 'CS', 'COSC 465', 1);

-- --------------------------------------------------------

--
-- Table structure for table `outcomes`
--

CREATE TABLE `outcomes` (
  `outcomeId` int(11) NOT NULL,
  `outcome` varchar(4098) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `outcomes`
--

INSERT INTO `outcomes` (`outcomeId`, `outcome`) VALUES
(1, 'Analyze a complex computing problem and to apply principles of computing and other relevant disciplines to identify solutions.'),
(2, 'Design, implement, and evaluate a computing-based solution to meet a given set of computing requirements in the context of the programs discipline.'),
(3, 'Communicate effectively in a variety of professional contexts.'),
(4, 'Recognize professional responsibilities and make informed judgments in computing practice based on legal and ethical principles.'),
(5, 'Function effectively as a member or leader of a team engaged in activities appropriate to the programs discipline.'),
(6, 'Apply computer science theory and software development fundamentals to produce computing-based solutions.');

-- --------------------------------------------------------

--
-- Table structure for table `rubrics`
--

CREATE TABLE `rubrics` (
  `rubricId` int(11) NOT NULL,
  `rubric` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rubrics`
--

INSERT INTO `rubrics` (`rubricId`, `rubric`) VALUES
(1, 'Exceeds Expectations: To exceed expectations means the student read the required materials, researched the online documentation, and emplemented a solution flawlessly.Meets Expectations: To meet expectations means the student read some required materials, researched the online documentation, and emplemented a solution that could have been better.Didnt Meet Expectations: To not meet expectations means the student did not read the required materials, did not research the online documentation, and emplemented a solution that did not work.');

-- --------------------------------------------------------

--
-- Table structure for table `studentAssessmentResults`
--

CREATE TABLE `studentAssessmentResults` (
  `studentId` int(11) NOT NULL,
  `assessmentId` int(11) NOT NULL,
  `results` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentAssessmentResults`
--

INSERT INTO `studentAssessmentResults` (`studentId`, `assessmentId`, `results`) VALUES
(1, 1, 'Exceeds Expectations'),
(1, 2, 'Didnt Meet Expectations'),
(1, 3, 'Meets Expectations'),
(1, 4, 'Exceeds Expectations'),
(2, 1, 'Didnt Meet Expectations');

-- --------------------------------------------------------

--
-- Table structure for table `studentCourses`
--

CREATE TABLE `studentCourses` (
  `studentId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentCourses`
--

INSERT INTO `studentCourses` (`studentId`, `courseId`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `studentOutcomeNarrativeSummary`
--

CREATE TABLE `studentOutcomeNarrativeSummary` (
  `studentId` int(11) NOT NULL,
  `courseOutcomeId` int(11) NOT NULL,
  `strengths` text NOT NULL,
  `weaknesses` text NOT NULL,
  `suggestions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentOutcomeNarrativeSummary`
--

INSERT INTO `studentOutcomeNarrativeSummary` (`studentId`, `courseOutcomeId`, `strengths`, `weaknesses`, `suggestions`) VALUES
(1, 1, 'Good use of software engineering techniques to organize project', 'Student needed to be coached to come up with multiple alternative designs', 'Add a lecture on how to think outside of the box to widen students perspective when coming up with alternate designs');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `studentId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`studentId`, `email`) VALUES
(1, 'bigjimmy@yahoo.com'),
(2, 'carl@biglips.org');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `email`, `password`, `firstname`, `lastname`) VALUES
(1, 'mgiffor2@vols.utk.edu', 'pastmist', 'Michael', 'Gifford');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessmentId`);

--
-- Indexes for table `courseOutcomes`
--
ALTER TABLE `courseOutcomes`
  ADD PRIMARY KEY (`courseOutcomeId`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseId`);

--
-- Indexes for table `outcomes`
--
ALTER TABLE `outcomes`
  ADD PRIMARY KEY (`outcomeId`);

--
-- Indexes for table `rubrics`
--
ALTER TABLE `rubrics`
  ADD PRIMARY KEY (`rubricId`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `courseOutcomes`
--
ALTER TABLE `courseOutcomes`
  MODIFY `courseOutcomeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `outcomes`
--
ALTER TABLE `outcomes`
  MODIFY `outcomeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `rubrics`
--
ALTER TABLE `rubrics`
  MODIFY `rubricId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `studentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
