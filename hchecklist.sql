-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2025 at 03:02 AM
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
-- Database: `hchecklist`
--

-- --------------------------------------------------------

--
-- Table structure for table `i`
--

CREATE TABLE `i` (
  `EmpNo` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Fname` varchar(50) NOT NULL,
  `Mname` varchar(50) NOT NULL,
  `Extension` varchar(5) NOT NULL,
  `BirthDate` date NOT NULL,
  `Gender` varchar(6) NOT NULL,
  `Office` varchar(50) NOT NULL,
  `Position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `i`
--

INSERT INTO `i` (`EmpNo`, `Lname`, `Fname`, `Mname`, `Extension`, `BirthDate`, `Gender`, `Office`, `Position`) VALUES
('26991', 'CUSI', 'PATRICK', 'DILAO', '', '2018-01-12', 'Male', 'DOLE MIMAROPA', 'LEO II'),
('26991', 'CUSI', 'PATRICK', 'DILAO', '', '2018-01-13', 'Male', 'DOLE MIMAROPA', 'LEO II'),
('26991', 'CUSI', 'PATRICK', 'DILAO', '', '2010-11-13', 'Male', 'DOLE MIMAROPA', 'LEO II'),
('26991', 'CUSI', 'PATRICK', 'DILAO', '', '1990-03-17', 'Male', 'DOLE MIMAROPA', 'LEO II'),
('26992', 'CUSI', 'PATRICK', 'D', '', '2007-08-27', 'Male', 'TSSD', 'LEO II'),
('26993', 'DELA CRUZ', 'JUAN', 'D', '', '2007-08-27', 'Male', 'IMSD', 'LEO II'),
('26994', 'MIRANDA', 'CHITO', 'D', '', '2007-08-27', 'Male', 'OrMin', 'LEO II'),
('26987', 'CRUZ', 'JUAN', 'DELA', '', '1990-03-17', 'Male', 'TSSD', 'LEO'),
('26987', 'CRUZ', 'JUAN', 'DELA', '', '1990-03-17', 'Male', 'TSSD', 'LEO');

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `id` int(11) NOT NULL,
  `lrn` varchar(20) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `grade_level` varchar(10) NOT NULL,
  `section` varchar(20) NOT NULL,
  `school_year` varchar(10) DEFAULT NULL,
  `sex` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `age` int(11) DEFAULT NULL,
  `mother_tongue` varchar(100) DEFAULT NULL,
  `ethnic_group` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `hssp` varchar(30) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `municipality_city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_maiden_name` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relationship` varchar(100) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `learning_modality` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`id`, `lrn`, `student_name`, `grade_level`, `section`, `school_year`, `sex`, `birth_date`, `age`, `mother_tongue`, `ethnic_group`, `religion`, `hssp`, `barangay`, `municipality_city`, `province`, `father_name`, `mother_maiden_name`, `guardian_name`, `guardian_relationship`, `contact_number`, `learning_modality`, `remarks`, `created_at`) VALUES
(175, '11157515001713', 'ARGENTE,ALECZYS, MANCILLA', '9', '9 - A', '2025-2026', 'M', '2010-03-03', 14, 'Tagalog', NULL, 'Christianity', NULL, 'COMUNAL', 'CITY OF CALAPAN (Capital)', 'ORIENTAL MINDORO', 'ARGENTE, ZAIDY SANCHEZ', 'MANCILLA,LENY,DELOS SANTOS,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(176, '1105241500091', 'CARINGAL,MARK JUPITER, SAN JOSE', '9', '9 - A', '2025-2026', 'M', '1970-01-01', 13, 'Tagalog', NULL, 'Christianity', NULL, 'PANIQUIAN', 'NAUJAN', 'ORIENTAL MINDORO', 'CARINGAL, JEMCIE CLEOFE', 'SAN JOSE,MARY ANN,FRANCISCO,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(177, '1105241500131', 'LAT,JHON JAMES, DE BELEN', '9', '9 - A', '2025-2026', 'M', '1970-01-01', 14, 'Tagalog', NULL, 'Christianity', NULL, 'PANIQUIAN', 'NAUJAN', 'ORIENTAL MINDORO', 'LAT, JESSIE PALAW', 'DE BELEN,JENNIFER,RUIZ,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(178, '1097161500101', 'MARASIGAN,JHON BERNARD, MAGBOO', '9', '9 - A', '2025-2026', 'M', '1970-01-01', 14, 'Tagalog', NULL, 'Christianity', NULL, 'COMUNAL', 'CITY OF CALAPAN (Capital)', 'ORIENTAL MINDORO', 'MARASIGAN, BERNARDO YAGO', 'MAGBOO,PAULINE ANDRIE,JEBULLAN,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(179, '1115751500411', 'BACULO,MARICAR, MARASIGAN', '9', '9 - A', '2025-2026', 'F', '2010-09-04', 14, 'Tagalog', NULL, 'Christianity', NULL, 'MALAD', 'CITY OF CALAPAN (Capital)', 'ORIENTAL MINDORO', 'BACULO, MACARIO ALCANO', 'MARASIGAN,MARIBEL,ALBO,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(180, '1115751500511', 'MENDOZA,ANNE, SANCHEZ', '9', '9 - A', '2025-2026', 'F', '1970-01-01', 14, 'Tagalog', NULL, 'Christianity', NULL, 'COMUNAL', 'CITY OF CALAPAN (Capital)', 'ORIENTAL MINDORO', 'MENDOZA, ALAN NUÑEZ', 'SANCHEZ,CELESTE,DIMANZANA,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(181, '1105121500121', 'OMAÑA,JAREN, OPREDO', '9', '9 - A', '2025-2026', 'F', '1970-01-01', 14, 'Tagalog', NULL, 'Christianity', NULL, 'DEL PILAR', 'NAUJAN', 'ORIENTAL MINDORO', 'OMAÑA, RAMSON MARILIANO', 'OPREDO,JENIFER,ABIUL,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(182, '1105121500111', 'OPREDO,KAYE SHEENDI, TOLENTINO', '9', '9 - A', '2025-2026', 'F', '1970-01-01', 13, 'Tagalog', NULL, 'Christianity', NULL, 'DEL PILAR', 'NAUJAN', 'ORIENTAL MINDORO', 'OPREDO, FEDILITO ABIUL', 'TOLENTINO,FE,FODULLA,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(183, '1115751500851', 'RIVERA,XYRIEL, ICALLA', '9', '9 - A', '2025-2026', 'F', '1970-01-01', 13, 'Tagalog', NULL, 'Christianity', NULL, 'COMUNAL', 'CITY OF CALAPAN (Capital)', 'ORIENTAL MINDORO', 'RIVERA, JOSELITO PADILLA', 'ICALLA,CHERYLL,ORDANZA,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26'),
(184, '1115751500571', 'VILLANUEVA,AYEZZA, QUINZON', '9', '9 - A', '2025-2026', 'F', '2009-12-11', 14, 'Tagalog', NULL, 'Christianity', NULL, 'COMUNAL', 'CITY OF CALAPAN (Capital)', 'ORIENTAL MINDORO', 'VILLANUEVA, SALVADOR JARLEGO', 'QUINZON,MARICRIS,CABA,', NULL, NULL, NULL, NULL, NULL, '2025-01-27 06:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_checklist`
--

CREATE TABLE `tbl_checklist` (
  `EmpNo` varchar(50) NOT NULL,
  `EmpName` text NOT NULL,
  `Gender` varchar(5) NOT NULL,
  `Age` int(11) NOT NULL,
  `Office` text NOT NULL,
  `Position` text NOT NULL,
  `DateChecked` datetime NOT NULL,
  `Temperature` float NOT NULL,
  `Item1` varchar(5) NOT NULL,
  `Item2` varchar(5) NOT NULL,
  `Item3` varchar(5) NOT NULL,
  `Item4` varchar(5) NOT NULL,
  `Item5` varchar(5) NOT NULL,
  `Item6` varchar(5) NOT NULL,
  `Item7` varchar(5) NOT NULL,
  `Item8` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_checklist`
--

INSERT INTO `tbl_checklist` (`EmpNo`, `EmpName`, `Gender`, `Age`, `Office`, `Position`, `DateChecked`, `Temperature`, `Item1`, `Item2`, `Item3`, `Item4`, `Item5`, `Item6`, `Item7`, `Item8`) VALUES
('26991', 'PATRICK D. CUSI', 'Male', 30, 'DOLE MIMAROPA', '', '2021-03-10 10:25:17', 36.3, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'Yes'),
('26991', 'PATRICK D. CUSI', 'Male', 30, 'DOLE MIMAROPA', '', '2021-03-10 10:25:37', 36.3, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('26991', 'PATRICK D. CUSI', 'Male', 30, 'DOLE MIMAROPA', '', '2021-03-10 10:25:57', 36.3, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes'),
('26991', 'PATRICK CUSI', 'Male', 4, 'DOLE MIMAROPA', 'LEO II', '2021-03-15 02:18:11', 36.3, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes'),
('26991', 'PATRICK CUSI', 'Male', 3, 'DOLE MIMAROPA', 'LEO II', '2021-03-22 01:19:24', 36.6, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'No', 'No', 'No'),
('26991', 'PATRICK CUSI', 'Male', 3, 'PALAWAN', 'LEO II', '2021-03-26 08:43:02', 36.3, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('26991', 'PATRICK CUSI', 'Male', 3, 'PALAWAN', 'LEO II', '2021-04-12 03:31:04', 36.5, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('26991', 'PATRICK CUSI', 'Male', 3, 'PALAWAN', 'LEO II', '2021-04-12 08:15:41', 36.5, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('26992', 'PATRICK CUSI', 'Male', 13, 'TSSD', 'LEO II', '2021-03-22 03:52:05', 36.3, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('26993', 'JUAN DELA CRUZ', 'Male', 13, 'IMSD', 'LEO II', '2021-03-24 01:50:14', 36.9, 'No', 'No', 'No', 'No', 'Yes', 'No', 'No', 'No'),
('26994', 'CHITO MIRANDA', 'Male', 13, 'OrMin', 'LEO II', '2021-03-22 03:52:05', 36.3, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('26999', 'PATRICK CUSI', 'Male', 13, 'TSSD', 'LEO II', '2021-03-26 08:45:23', 36.3, 'No', 'No', 'No', 'No', 'Yes', 'Yes', 'Yes', 'Yes'),
('ABDC171113', 'ABEGAIL DE CLARO ', 'Femal', 29, 'TSSD', '1.61', '2021-03-15 02:18:11', 36.6, 'No', 'No', 'No', 'No', 'No', 'Yes', 'Yes', 'Yes'),
('JTG081616', 'JOHN CHRISTOPHER T. GUNDAY', 'Male', 33, 'DOLE MIMAROPA', '', '2020-09-02 00:00:00', 35.8, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('JTG081616', 'John Christopher Gunday', 'Male', 33, 'DOLE MIMAROPA', '', '2020-09-10 11:47:26', 36.1, 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'No'),
('JTG081616', 'John Christopher Gunday', 'Male', 34, 'IMSD', '', '2021-03-10 11:57:51', 36.4, 'Yes', 'No', 'No', 'No', 'No', 'No', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `EmpNo` varchar(80) NOT NULL,
  `EmpName` varchar(80) NOT NULL,
  `Gender` varchar(80) DEFAULT NULL,
  `Age` int(10) NOT NULL,
  `position` varchar(100) NOT NULL,
  `office` varchar(50) NOT NULL,
  `username` varchar(80) NOT NULL,
  `pass` varchar(80) NOT NULL,
  `access_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `EmpNo`, `EmpName`, `Gender`, `Age`, `position`, `office`, `username`, `pass`, `access_level`) VALUES
(1, 'JRD200829', 'DELA CRUZ', 'Male', 0, 'SENIOR LABOR AND EMPLOYMENT OFFICER', 'IMSD', 'JRD200829', 'admin', 'IMSD'),
(2, 'RRD200829', 'DELA CRUZ', 'Male', 0, 'ADMINISTRATIVE AIDE III', 'IMSD', 'RRD200829', 'admin', 'TSSD'),
(7, '26991', 'PATRICK D. CUSI', 'Male', 31, 'LEO II', 'TSSD', '26991', 'admin', 'TSSD');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_checklist`
--
ALTER TABLE `tbl_checklist`
  ADD PRIMARY KEY (`EmpNo`,`DateChecked`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
