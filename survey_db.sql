-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 04:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `survey_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `area1_responses`
--

CREATE TABLE `area1_responses` (
  `id` int(11) NOT NULL,
  `q1` tinyint(4) NOT NULL,
  `q2` tinyint(4) NOT NULL,
  `q3` tinyint(4) NOT NULL,
  `q4` tinyint(4) NOT NULL,
  `q5` tinyint(4) NOT NULL,
  `q6` tinyint(4) NOT NULL,
  `q7` tinyint(4) NOT NULL,
  `q8` tinyint(4) NOT NULL,
  `q9` tinyint(4) NOT NULL,
  `q10` tinyint(4) NOT NULL,
  `q11` tinyint(4) NOT NULL,
  `q12` tinyint(4) NOT NULL,
  `q13` tinyint(4) NOT NULL,
  `q14` tinyint(4) NOT NULL,
  `q15` tinyint(4) NOT NULL,
  `q16` tinyint(4) NOT NULL,
  `q17` tinyint(4) NOT NULL,
  `q18` tinyint(4) NOT NULL,
  `q19` tinyint(4) NOT NULL,
  `q20` tinyint(4) NOT NULL,
  `q21` tinyint(4) NOT NULL,
  `q22` tinyint(4) NOT NULL,
  `q23` tinyint(4) NOT NULL,
  `q24` tinyint(4) NOT NULL,
  `q25` tinyint(4) NOT NULL,
  `q26` tinyint(4) NOT NULL,
  `q27` tinyint(4) NOT NULL,
  `q28` tinyint(4) NOT NULL,
  `submitted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area1_responses`
--

INSERT INTO `area1_responses` (`id`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `q10`, `q11`, `q12`, `q13`, `q14`, `q15`, `q16`, `q17`, `q18`, `q19`, `q20`, `q21`, `q22`, `q23`, `q24`, `q25`, `q26`, `q27`, `q28`, `submitted_at`) VALUES
(1, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, '2025-10-09 08:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `area2_responses`
--

CREATE TABLE `area2_responses` (
  `id` int(11) NOT NULL,
  `q1` tinyint(4) NOT NULL,
  `q2` tinyint(4) NOT NULL,
  `q3` tinyint(4) NOT NULL,
  `q4` tinyint(4) NOT NULL,
  `q5` tinyint(4) NOT NULL,
  `q6` tinyint(4) NOT NULL,
  `q7` tinyint(4) NOT NULL,
  `q8` tinyint(4) NOT NULL,
  `q9` tinyint(4) NOT NULL,
  `q10` tinyint(4) NOT NULL,
  `q11` tinyint(4) NOT NULL,
  `q12` tinyint(4) NOT NULL,
  `submitted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area2_responses`
--

INSERT INTO `area2_responses` (`id`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `q10`, `q11`, `q12`, `submitted_at`) VALUES
(1, 4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 3, 3, '2025-10-09 08:48:09'),
(2, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 2, 2, '2025-10-09 08:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `area3_responses`
--

CREATE TABLE `area3_responses` (
  `id` int(11) NOT NULL,
  `q1` tinyint(4) DEFAULT NULL,
  `q2` tinyint(4) DEFAULT NULL,
  `q3` tinyint(4) DEFAULT NULL,
  `q4` tinyint(4) DEFAULT NULL,
  `q5` tinyint(4) DEFAULT NULL,
  `q6` tinyint(4) DEFAULT NULL,
  `q7` tinyint(4) DEFAULT NULL,
  `q8` tinyint(4) DEFAULT NULL,
  `q9` tinyint(4) DEFAULT NULL,
  `q10` tinyint(4) DEFAULT NULL,
  `q11` tinyint(4) DEFAULT NULL,
  `q12` tinyint(4) DEFAULT NULL,
  `q13` tinyint(4) DEFAULT NULL,
  `q14` tinyint(4) DEFAULT NULL,
  `q15` tinyint(4) DEFAULT NULL,
  `q16` tinyint(4) DEFAULT NULL,
  `q17` tinyint(4) DEFAULT NULL,
  `q18` tinyint(4) DEFAULT NULL,
  `q19` tinyint(4) DEFAULT NULL,
  `q20` tinyint(4) DEFAULT NULL,
  `q21` tinyint(4) DEFAULT NULL,
  `q22` tinyint(4) DEFAULT NULL,
  `q23` tinyint(4) DEFAULT NULL,
  `q24` tinyint(4) DEFAULT NULL,
  `q25` tinyint(4) DEFAULT NULL,
  `q26` tinyint(4) DEFAULT NULL,
  `q27` tinyint(4) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `area1_responses`
--
ALTER TABLE `area1_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area2_responses`
--
ALTER TABLE `area2_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area3_responses`
--
ALTER TABLE `area3_responses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `area1_responses`
--
ALTER TABLE `area1_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `area2_responses`
--
ALTER TABLE `area2_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `area3_responses`
--
ALTER TABLE `area3_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
