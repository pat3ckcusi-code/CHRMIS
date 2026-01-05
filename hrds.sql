-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 09:23 AM
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
-- Database: `hrds`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminusers`
--

CREATE TABLE `adminusers` (
  `EmpNo` int(20) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `AcctName` varchar(100) NOT NULL,
  `Dept` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `ContactNo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `adminusers`
--

INSERT INTO `adminusers` (`EmpNo`, `UserName`, `AcctName`, `Dept`, `Password`, `Status`, `ContactNo`) VALUES
(1, 'admin', 'Okuls', 'City Human Resource Management Department', 'p@55w0rdsuck', 'FOR APPROVAL', ''),
(2, 'arpee', 'Arpee Rodolfo S. Cuasay', 'City Human Resource Management Department', '12345', 'FOR APPROVAL', ''),
(12345, 'jabby', 'Madam Janet', 'City Budget Department', 'jabby', 'FOR RECOMMENDATION', '');

-- --------------------------------------------------------

--
-- Table structure for table `approvingdates`
--

CREATE TABLE `approvingdates` (
  `LeaveID` int(11) NOT NULL,
  `Recommended` datetime NOT NULL,
  `Checked` date NOT NULL,
  `Approved` date NOT NULL,
  `Disapproved` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `approvingdates`
--

INSERT INTO `approvingdates` (`LeaveID`, `Recommended`, `Checked`, `Approved`, `Disapproved`) VALUES
(1, '2025-09-03 00:00:00', '0000-00-00', '2025-09-03', '0000-00-00'),
(2, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', '0000-00-00'),
(3, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', '0000-00-00'),
(4, '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `children`
--

CREATE TABLE `children` (
  `EmpNo` varchar(50) NOT NULL,
  `ChildName` varchar(100) NOT NULL,
  `ChildBirth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `children`
--

INSERT INTO `children` (`EmpNo`, `ChildName`, `ChildBirth`) VALUES
('24018', 'JUNIOR', '2026-01-01'),
('24018', 'THIRDY', '2027-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `ctohistory`
--

CREATE TABLE `ctohistory` (
  `EmpNo` varchar(50) NOT NULL,
  `Date` date NOT NULL,
  `NumHours` varchar(5) NOT NULL,
  `Reason` varchar(100) NOT NULL,
  `HolidayType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `Dept_id` int(11) NOT NULL,
  `DeptCode` varchar(20) NOT NULL,
  `Dept_name` varchar(100) NOT NULL,
  `Department_head` varchar(100) NOT NULL,
  `Designation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`Dept_id`, `DeptCode`, `Dept_name`, `Department_head`, `Designation`) VALUES
(4, 'CCC', 'City College of Calapan', 'Ronald F. Cantos, DPA', 'College Administrator'),
(5, 'CBD', 'City Budget Office', 'Ate Jabby', 'OIC- Budget Officer');

-- --------------------------------------------------------

--
-- Table structure for table `eta_locator`
--

CREATE TABLE `eta_locator` (
  `id` int(10) UNSIGNED NOT NULL,
  `EmpNo` varchar(50) NOT NULL,
  `application_type` enum('ETA','Locator') NOT NULL,
  `travel_date` date NOT NULL,
  `arrival_date` date NOT NULL,
  `intended_departure` time DEFAULT NULL,
  `intended_arrival` time DEFAULT NULL,
  `destination` varchar(255) NOT NULL,
  `business_type` enum('Audit-Inspection-Licensing','Client Support','Conference','Construction Repair Maintenance','Economic Development','Legal-Law Enforcement','Legislator','Meeting','Training','Seminar','General Expense/Other','Official','Personal') NOT NULL,
  `other_purpose` varchar(255) DEFAULT NULL,
  `travel_detail` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled') DEFAULT 'Pending',
  `date_filed` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eta_locator`
--

INSERT INTO `eta_locator` (`id`, `EmpNo`, `application_type`, `travel_date`, `arrival_date`, `intended_departure`, `intended_arrival`, `destination`, `business_type`, `other_purpose`, `travel_detail`, `status`, `date_filed`, `last_updated`) VALUES
(38, '6013', 'ETA', '2025-10-02', '2025-10-03', '00:00:00', '00:00:00', 'here', 'Client Support', '', 'support\r\n', 'Cancelled', '2025-09-26 06:26:54', '2025-10-05 07:44:29'),
(39, '6013', 'Locator', '2025-09-26', '2025-09-26', '15:28:00', '16:28:00', 'to da moon', 'Official', '', 'tahimik', 'Pending', '2025-09-26 06:27:20', '2025-09-26 06:27:20'),
(40, '6013', 'ETA', '2025-09-29', '2025-10-01', '00:00:00', '00:00:00', 'to da moon', 'General Expense/Other', '', 'meron kami conference sa moon, wag kang magulo. payagan mo na ako please', 'Approved', '2025-09-26 07:04:58', '2025-09-26 07:19:48'),
(41, '6013', 'Locator', '2025-10-05', '2025-10-05', '15:15:00', '16:15:00', 'to da moon', 'Official', '', 'to da moon po', 'Approved', '2025-10-05 06:14:35', '2025-10-05 06:15:20'),
(42, '6013', 'Locator', '2025-10-05', '2025-10-05', '15:15:00', '16:15:00', 'to da sun', 'Personal', '', 'to the sun po', 'Approved', '2025-10-05 06:14:53', '2025-10-05 06:15:17'),
(43, '6013', 'ETA', '2025-10-05', '2025-10-05', '00:00:00', '00:00:00', 'Beerus', 'Client Support', '', 'support', 'Approved', '2025-10-05 06:18:58', '2025-10-05 06:19:16'),
(44, '6013', 'ETA', '2025-10-05', '2025-10-05', '00:00:00', '00:00:00', 'Beerus', 'Client Support', '', 'training with beerus sama', 'Rejected', '2025-10-05 06:26:17', '2025-10-05 06:26:35'),
(45, '6013', 'ETA', '2025-10-05', '2025-10-05', '00:00:00', '00:00:00', 'Beerus', 'General Expense/Other', 'Training with beerus sama', 'Training to be God of Destruction ', 'Approved', '2025-10-05 06:27:14', '2025-10-05 06:27:28'),
(46, '6013', 'Locator', '2025-10-06', '2025-10-06', '12:31:00', '15:31:00', 'hr', 'Official', '', 'support', 'Pending', '2025-10-06 03:30:25', '2025-10-06 03:30:25');

-- --------------------------------------------------------

--
-- Table structure for table `filedleave`
--

CREATE TABLE `filedleave` (
  `LeaveID` int(11) NOT NULL,
  `RefNo` varchar(20) NOT NULL,
  `EmpNo` varchar(50) NOT NULL,
  `LeaveType` varchar(50) NOT NULL,
  `Purpose` varchar(100) NOT NULL,
  `DateFrom` date NOT NULL,
  `DateTo` date NOT NULL,
  `NumDays` int(11) NOT NULL,
  `DateFiled` date NOT NULL,
  `Remarks` varchar(20) NOT NULL,
  `Reason` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `filedleave`
--

INSERT INTO `filedleave` (`LeaveID`, `RefNo`, `EmpNo`, `LeaveType`, `Purpose`, `DateFrom`, `DateTo`, `NumDays`, `DateFiled`, `Remarks`, `Reason`) VALUES
(1, '6013-20250901-1', '6013', 'Vacation Leave', '', '2025-09-08', '2025-09-08', 1, '2025-09-01', 'APPROVED', ''),
(2, '6013-20250903-2', '6013', 'Vacation Leave', '', '2025-09-10', '2025-09-10', 1, '2025-09-03', 'FOR RECOMMENDATION', ''),
(3, '24018-20250903-3', '24018', 'Vacation Leave', '', '2025-09-17', '2025-09-17', 1, '2025-09-03', 'FOR RECOMMENDATION', ''),
(4, '6013-20251002-4', '6013', 'Vacation Leave', '', '2025-10-10', '2025-10-10', 1, '2025-10-02', 'FOR RECOMMENDATION', '');

-- --------------------------------------------------------

--
-- Table structure for table `filedleave2`
--

CREATE TABLE `filedleave2` (
  `LeaveID` int(11) NOT NULL,
  `RefNo` varchar(20) NOT NULL,
  `EmpNo` varchar(50) NOT NULL,
  `LeaveType` varchar(50) NOT NULL,
  `Purpose` varchar(100) NOT NULL,
  `DateFrom` date NOT NULL,
  `DateTo` date NOT NULL,
  `NumDays` int(11) NOT NULL,
  `DateFiled` date NOT NULL,
  `Remarks` varchar(20) NOT NULL,
  `Reason` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `govid`
--

CREATE TABLE `govid` (
  `EmpNo` varchar(50) NOT NULL,
  `GovID` varchar(50) NOT NULL,
  `GovIDNo` varchar(50) NOT NULL,
  `Issuance` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `govid`
--

INSERT INTO `govid` (`EmpNo`, `GovID`, `GovIDNo`, `Issuance`) VALUES
('24018', 'Driver\'s License', 'calapan', '2025-09-22');

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
  `PlaceBirth` varchar(100) NOT NULL,
  `Gender` varchar(6) NOT NULL,
  `Civil` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Dept` varchar(50) NOT NULL,
  `Height` varchar(10) NOT NULL,
  `Weight` varchar(10) NOT NULL,
  `BloodType` varchar(5) NOT NULL,
  `GSIS` varchar(50) NOT NULL,
  `Pagibig` varchar(50) NOT NULL,
  `PHealth` varchar(50) NOT NULL,
  `SSS` varchar(50) NOT NULL,
  `Tin` varchar(50) NOT NULL,
  `AgencyEmpNo` varchar(50) NOT NULL,
  `Citizenship` varchar(100) NOT NULL,
  `Country` varchar(100) NOT NULL,
  `HouseNo` varchar(100) NOT NULL,
  `Street` varchar(100) NOT NULL,
  `Subd` varchar(100) NOT NULL,
  `Brgy` varchar(100) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Province` varchar(100) NOT NULL,
  `Zip` varchar(10) NOT NULL,
  `Perm_House` varchar(100) NOT NULL,
  `Perm_Street` varchar(100) NOT NULL,
  `Perm_Subd` varchar(100) NOT NULL,
  `Perm_Brgy` varchar(100) NOT NULL,
  `Perm_City` varchar(100) NOT NULL,
  `Perm_Province` varchar(100) NOT NULL,
  `Perm_Zip` varchar(10) NOT NULL,
  `TelNo` varchar(20) NOT NULL,
  `MobileNo` varchar(20) NOT NULL,
  `EMail` varchar(100) NOT NULL,
  `EmployementStatus` varchar(30) NOT NULL,
  `profile_pic` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `i`
--

INSERT INTO `i` (`EmpNo`, `Lname`, `Fname`, `Mname`, `Extension`, `BirthDate`, `PlaceBirth`, `Gender`, `Civil`, `Password`, `Dept`, `Height`, `Weight`, `BloodType`, `GSIS`, `Pagibig`, `PHealth`, `SSS`, `Tin`, `AgencyEmpNo`, `Citizenship`, `Country`, `HouseNo`, `Street`, `Subd`, `Brgy`, `City`, `Province`, `Zip`, `Perm_House`, `Perm_Street`, `Perm_Subd`, `Perm_Brgy`, `Perm_City`, `Perm_Province`, `Perm_Zip`, `TelNo`, `MobileNo`, `EMail`, `EmployementStatus`, `profile_pic`) VALUES
('01097', 'TAGUPA', 'MARIAN TERESA', '', '', '1984-02-27', '', 'Female', 'Single', 'password', 'City Human Resource Management Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('24018', 'CUSI', 'PATRICK', 'DILAO', 'Jr.', '1990-12-03', '', 'Male', 'Single', '$2y$12$jqw7n.5w0QLim1e1nX05Uet1tD0trBQDiXAr5jZOmslWkCCNjn3hG', 'City College of Calapan', '', '', '', '', '', '', '', '11', '1', 'Filipino', '', '', '', '', 'MAHAL NA PANGALAN', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', '', '', 'MAHAL NA PANGALAN', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '09063351625', '09063351625', 'PAT3CK.CUSI@GMAIL.COM', '', 'profile_24018.png'),
('6013', 'CAMACHO', 'CYRILLE ANNE', 'COSTALES', '', '1989-03-29', '', 'Female', 'Single', '$2y$12$8zex6n09iLYM7lG1BGIQwuEeW7FK08/S/A/zMIb0a9fWLgfpBVXUu', 'City Budget Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'profile_6013.png');

-- --------------------------------------------------------

--
-- Table structure for table `ii`
--

CREATE TABLE `ii` (
  `EmpNo` varchar(50) NOT NULL,
  `SLname` varchar(100) NOT NULL,
  `SFname` varchar(100) NOT NULL,
  `SMname` varchar(100) NOT NULL,
  `SExtension` varchar(10) NOT NULL,
  `SOccupation` varchar(100) NOT NULL,
  `EmpBusName` varchar(100) NOT NULL,
  `BussAdd` varchar(100) NOT NULL,
  `TelNo` varchar(20) NOT NULL,
  `FLname` varchar(100) NOT NULL,
  `FFname` varchar(100) NOT NULL,
  `FMname` varchar(100) NOT NULL,
  `FExtension` varchar(10) NOT NULL,
  `MLname` varchar(100) NOT NULL,
  `MFname` varchar(100) NOT NULL,
  `MMname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ii`
--

INSERT INTO `ii` (`EmpNo`, `SLname`, `SFname`, `SMname`, `SExtension`, `SOccupation`, `EmpBusName`, `BussAdd`, `TelNo`, `FLname`, `FFname`, `FMname`, `FExtension`, `MLname`, `MFname`, `MMname`) VALUES
('24018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '');

-- --------------------------------------------------------

--
-- Table structure for table `iii`
--

CREATE TABLE `iii` (
  `EmpNo` varchar(50) NOT NULL,
  `Level` varchar(100) NOT NULL,
  `SchoolName` text NOT NULL,
  `Course` varchar(100) NOT NULL,
  `PeriodFrom` varchar(50) NOT NULL,
  `PeriodTo` varchar(50) NOT NULL,
  `Units` varchar(20) NOT NULL,
  `YearGrad` varchar(4) NOT NULL,
  `Honors` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `iii`
--

INSERT INTO `iii` (`EmpNo`, `Level`, `SchoolName`, `Course`, `PeriodFrom`, `PeriodTo`, `Units`, `YearGrad`, `Honors`) VALUES
('24018', 'ELEMENTARY', 'elementary', '', '', '', '', '', ''),
('24018', 'SECONDARY', 'SECONDARY', '', '', '', '', '', ''),
('24018', 'VOCATIONAL/TRADE COURSE', 'VOCATIONAL', '', '', '', '', '', ''),
('24018', 'COLLEGE', 'COLLEGE	', '', '', '', '', '', ''),
('24018', 'GRADUATE STUDIES', 'GRADUATE ', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `iv`
--

CREATE TABLE `iv` (
  `EmpNo` varchar(50) NOT NULL,
  `Career` varchar(100) NOT NULL,
  `Rating` varchar(10) NOT NULL,
  `Date` date NOT NULL,
  `Place` varchar(100) NOT NULL,
  `LiNum` varchar(50) NOT NULL,
  `LiDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `iv`
--

INSERT INTO `iv` (`EmpNo`, `Career`, `Rating`, `Date`, `Place`, `LiNum`, `LiDate`) VALUES
('24018', 'CSC', '80', '2025-01-01', 'CALAPAN', '1', '2010-12-31'),
('24018', 'CSC', '80', '2026-01-01', 'CALAPAN', '2', '2028-01-03'),
('24018', 'csc', '85', '0000-00-00', 'CALAPAN', '3', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `leavecredits`
--

CREATE TABLE `leavecredits` (
  `EmpNo` varchar(50) NOT NULL,
  `VL` float NOT NULL,
  `SL` float NOT NULL,
  `CL` float NOT NULL,
  `SPL` float NOT NULL,
  `CTO` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leavecredits`
--

INSERT INTO `leavecredits` (`EmpNo`, `VL`, `SL`, `CL`, `SPL`, `CTO`) VALUES
('01097', 0, 0, 0, 0, 0),
('24018', 20, 0, 0, 0, 0),
('6013', 18, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leavecredits2`
--

CREATE TABLE `leavecredits2` (
  `EmpNo` varchar(50) NOT NULL,
  `VL` float NOT NULL,
  `SL` float NOT NULL,
  `CL` float NOT NULL,
  `SPL` float NOT NULL,
  `CTO` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthlyadd`
--

CREATE TABLE `monthlyadd` (
  `MonthYear` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `monthlyadd`
--

INSERT INTO `monthlyadd` (`MonthYear`) VALUES
('August 2025');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `EmpNo` varchar(50) NOT NULL,
  `34a_choice` varchar(10) NOT NULL,
  `34b_choice` varchar(10) NOT NULL,
  `34b_details` text NOT NULL,
  `35a_choice` varchar(10) NOT NULL,
  `35a_details` text NOT NULL,
  `35b_choice` varchar(10) NOT NULL,
  `35b_details` text NOT NULL,
  `36a_choice` varchar(10) NOT NULL,
  `36a_details` text NOT NULL,
  `37a_choice` varchar(10) NOT NULL,
  `37a_details` text NOT NULL,
  `38a_choice` varchar(10) NOT NULL,
  `38a_details` text NOT NULL,
  `38b_choice` varchar(10) NOT NULL,
  `38b_details` text NOT NULL,
  `39a_choice` varchar(10) NOT NULL,
  `39a_details` text NOT NULL,
  `40a_choice` varchar(10) NOT NULL,
  `40a_details` text NOT NULL,
  `40b_choice` varchar(10) NOT NULL,
  `40b_details` text NOT NULL,
  `40c_choice` varchar(10) NOT NULL,
  `40c_details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`EmpNo`, `34a_choice`, `34b_choice`, `34b_details`, `35a_choice`, `35a_details`, `35b_choice`, `35b_details`, `36a_choice`, `36a_details`, `37a_choice`, `37a_details`, `38a_choice`, `38a_details`, `38b_choice`, `38b_details`, `39a_choice`, `39a_details`, `40a_choice`, `40a_details`, `40b_choice`, `40b_details`, `40c_choice`, `40c_details`) VALUES
('6013', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('24018', 'NO', 'YES', 'a', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '');

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE `reference` (
  `EmpNo` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `Tel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reference`
--

INSERT INTO `reference` (`EmpNo`, `Name`, `Address`, `Tel`) VALUES
('24018', 'asd', '123', 'qwe'),
('24018', 'asd', '123', 'qwe'),
('24018', 'asd', '123', 'qwe');

-- --------------------------------------------------------

--
-- Table structure for table `servicerecord`
--

CREATE TABLE `servicerecord` (
  `EmpNo` varchar(50) NOT NULL,
  `ServiceFrom` date NOT NULL,
  `ServiceTo` date NOT NULL,
  `Designation` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Salary` double NOT NULL,
  `AssignStation` varchar(100) NOT NULL,
  `Branch` varchar(100) NOT NULL,
  `LeaveAbsence` varchar(100) NOT NULL,
  `SepaDate` date NOT NULL,
  `SepaCause` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `v`
--

CREATE TABLE `v` (
  `EmpNo` varchar(50) NOT NULL,
  `IndateFrom` date NOT NULL,
  `IndateTo` date NOT NULL,
  `Position` varchar(100) NOT NULL,
  `Dept` varchar(100) NOT NULL,
  `Month` varchar(20) NOT NULL,
  `Salary` varchar(20) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `GovService` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `v`
--

INSERT INTO `v` (`EmpNo`, `IndateFrom`, `IndateTo`, `Position`, `Dept`, `Month`, `Salary`, `Status`, `GovService`) VALUES
('6013', '2025-01-12', '0000-00-00', 'Budget Officer I', 'CBD', '40,000.00', '15-1', 'PERMANENT', 'Y'),
('24018', '2025-01-01', '0000-00-00', 'ASSISTANT PROFESSOR I', 'City College Of Calapan', '', '', 'permanent', 'Y'),
('24018', '2025-01-01', '2025-12-31', 'ASSISTANT PROFESSOR I', 'CCC', '', '', 'PERMANENT', 'Y'),
('24018', '2024-01-01', '2024-12-31', 'CCC', 'CCC', '', '', 'PERMANENT', 'Y'),
('24018', '2023-01-01', '2023-12-31', 'ccc', 'ccc', '', '', 'permanent', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `vacancy`
--

CREATE TABLE `vacancy` (
  `VacancyID` int(11) NOT NULL,
  `PositionTitle` text NOT NULL,
  `PlantillaNo` varchar(50) NOT NULL,
  `SalaryGrade` int(11) NOT NULL,
  `MonthlySalary` decimal(10,0) NOT NULL,
  `Education` text NOT NULL,
  `Training` text NOT NULL,
  `Experience` text NOT NULL,
  `Eligibility` text NOT NULL,
  `Competency` text NOT NULL,
  `Department` varchar(255) NOT NULL,
  `OfficeAssignment` text NOT NULL,
  `DatePosted` date NOT NULL,
  `FinalSubmission` date NOT NULL,
  `Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vi`
--

CREATE TABLE `vi` (
  `EmpNo` varchar(50) NOT NULL,
  `NameandAdd` varchar(100) NOT NULL,
  `InclusiveFrom` date NOT NULL,
  `InclusiveTo` date NOT NULL,
  `NumHours` varchar(10) NOT NULL,
  `Position` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vi`
--

INSERT INTO `vi` (`EmpNo`, `NameandAdd`, `InclusiveFrom`, `InclusiveTo`, `NumHours`, `Position`) VALUES
('24018', 'CCC', '0000-00-00', '0000-00-00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `vii`
--

CREATE TABLE `vii` (
  `EmpNo` varchar(50) NOT NULL,
  `Title` text NOT NULL,
  `InclusiveFrom` date NOT NULL,
  `InclusiveTo` date NOT NULL,
  `NumHours` varchar(10) NOT NULL,
  `LDType` varchar(50) NOT NULL,
  `ConBy` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vii`
--

INSERT INTO `vii` (`EmpNo`, `Title`, `InclusiveFrom`, `InclusiveTo`, `NumHours`, `LDType`, `ConBy`) VALUES
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-09', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'ad', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-09', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'qwe', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'qwe', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'asd', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'zxc', '2024-08-10', '2024-08-10', '', '', ''),
('6013', '123', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'dsa', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'zxcz', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'abc', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'def', '2024-08-10', '2024-08-10', '', '', ''),
('6013', 'okuls', '2024-08-10', '0000-00-00', '', '', ''),
('6013', 'Ouls', '2025-07-09', '2025-08-09', '', '', ''),
('24018', 'CCC', '2024-01-01', '2024-01-01', '8', 'TECHNICAL', 'CCC'),
('24018', 'CCC', '2023-01-01', '2023-01-01', '8', 'TECHNICAL', 'CCC');

-- --------------------------------------------------------

--
-- Table structure for table `viii`
--

CREATE TABLE `viii` (
  `EmpNo` varchar(50) NOT NULL,
  `Skills` varchar(100) NOT NULL,
  `Recognition` varchar(100) NOT NULL,
  `Membership` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `viii`
--

INSERT INTO `viii` (`EmpNo`, `Skills`, `Recognition`, `Membership`) VALUES
('24018', 'COMPUTER', '', ''),
('24018', 'KUMAIN NG MANOK', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminusers`
--
ALTER TABLE `adminusers`
  ADD PRIMARY KEY (`UserName`);

--
-- Indexes for table `approvingdates`
--
ALTER TABLE `approvingdates`
  ADD PRIMARY KEY (`LeaveID`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`Dept_id`);

--
-- Indexes for table `eta_locator`
--
ALTER TABLE `eta_locator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filedleave`
--
ALTER TABLE `filedleave`
  ADD PRIMARY KEY (`LeaveID`);

--
-- Indexes for table `filedleave2`
--
ALTER TABLE `filedleave2`
  ADD PRIMARY KEY (`LeaveID`);

--
-- Indexes for table `i`
--
ALTER TABLE `i`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `ii`
--
ALTER TABLE `ii`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `leavecredits`
--
ALTER TABLE `leavecredits`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `leavecredits2`
--
ALTER TABLE `leavecredits2`
  ADD PRIMARY KEY (`EmpNo`);

--
-- Indexes for table `monthlyadd`
--
ALTER TABLE `monthlyadd`
  ADD PRIMARY KEY (`MonthYear`);

--
-- Indexes for table `vacancy`
--
ALTER TABLE `vacancy`
  ADD PRIMARY KEY (`VacancyID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `Dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `eta_locator`
--
ALTER TABLE `eta_locator`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `filedleave`
--
ALTER TABLE `filedleave`
  MODIFY `LeaveID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `filedleave2`
--
ALTER TABLE `filedleave2`
  MODIFY `LeaveID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vacancy`
--
ALTER TABLE `vacancy`
  MODIFY `VacancyID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
