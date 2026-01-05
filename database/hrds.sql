-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 03:04 PM
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
(0, 'encoder', 'Access to Admin', 'Access to Admin', 'Access2Admin', 'FOR Encoder', ''),
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
('24018', 'THIRDY', '2027-01-01'),
('24014', 'CARMELA PENELOPE D. DILAY', '2006-06-18'),
('24014', 'AMINA DOMINIQUE D. DILAY', '2012-09-24'),
('22025', 'KHYLA NICOLE M. DE GUZMAN			', '2003-07-30'),
('22025', 'KEN M. DE GUZMAN			', '2011-12-18'),
('10010', 'LUKAS CRUZ L. GOCO', '2007-10-04'),
('22010', 'SHAINDEL ELIANA M. MANALO', '2008-05-07'),
('22010', 'SAVANNA HAILEY M. MANALO', '2012-12-04'),
('24018', 'JUNIOR', '2026-01-01'),
('24018', 'THIRDY', '2027-01-01'),
('01097', 'BRUCAL, CARLOS MIGUEL T.', '2010-01-11'),
('20004', 'ALREN ANGELO T. LIBRE', '2006-06-18'),
('02009', 'MARK JOHN KAROL B. SORIANO', '2007-02-24'),
('02009', 'PRECIOUS YURI B. SORIANO', '2011-01-19'),
('22015', 'JELAINE FREY I. ADANTE', '2019-03-27'),
('22015', 'LUCYLAINE FREY I. ADANTE', '2021-03-01'),
('22015', 'JEFFREY I. ADANTE JR', '2022-02-22'),
('03033', 'Geline Grace B. Manalo-Macatangay', '1993-02-24'),
('03033', 'Kean Gino B. Manalo', '1997-08-24'),
('24014', 'CARMELA PENELOPE D. DILAY', '2006-06-18'),
('24014', 'AMINA DOMINIQUE D. DILAY', '2012-09-24'),
('22025', 'KHYLA NICOLE M. DE GUZMAN			', '2003-07-30'),
('22025', 'KEN M. DE GUZMAN			', '2011-12-18'),
('10010', 'LUKAS CRUZ L. GOCO', '2007-10-04'),
('22010', 'SHAINDEL ELIANA M. MANALO', '2008-05-07'),
('22010', 'SAVANNA HAILEY M. MANALO', '2012-12-04'),
('24018', 'JUNIOR', '2026-01-01'),
('24018', 'THIRDY', '2027-01-01'),
('01097', 'BRUCAL, CARLOS MIGUEL T.', '2010-01-11'),
('20004', 'ALREN ANGELO T. LIBRE', '2006-06-18'),
('02009', 'MARK JOHN KAROL B. SORIANO', '2007-02-24'),
('02009', 'PRECIOUS YURI B. SORIANO', '2011-01-19'),
('22015', 'JELAINE FREY I. ADANTE', '2019-03-27'),
('22015', 'LUCYLAINE FREY I. ADANTE', '2021-03-01'),
('22015', 'JEFFREY I. ADANTE JR', '2022-02-22'),
('03033', 'Geline Grace B. Manalo-Macatangay', '1993-02-24'),
('03033', 'Kean Gino B. Manalo', '1997-08-24');

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
(46, '6013', 'Locator', '2025-10-16', '2025-10-16', '12:31:00', '15:31:00', 'hr', 'Official', '', 'support', 'Approved', '2025-10-16 03:30:25', '2025-10-29 02:53:14'),
(47, '6013', 'Locator', '2025-10-29', '2025-10-29', '11:56:00', '14:55:00', 'landbank', 'Official', '', 'deposit', 'Pending', '2025-10-29 02:55:57', '2025-10-29 02:55:57'),
(48, '6013', 'ETA', '2025-10-29', '2025-10-29', '00:00:00', '00:00:00', 'vencios', 'Conference', '', 'travel', 'Approved', '2025-10-29 03:26:06', '2025-10-29 03:28:12');

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
('24018', 'Driver\'s License', 'calapan', '2025-09-22'),
('6013', 'Lisence ID', 'D-05-12345', '2021-10-01'),
('24018', 'Driver\'s License', 'dito lang', '2025-08-13'),
('22025', 'PASSPORT', 'Passport P6110923C', '2023-12-07'),
('22025', 'PASSPORT', 'Passport P6110923C', '2023-12-07'),
('24018', 'Driver\'s License', 'calapan', '2025-08-13'),
('22024', 'PRC', '2000940', '2023-03-23'),
('22021', 'CGC ID', '22021', '2022-02-16'),
('20004', 'National ID', '3761-2890-5380-7139', '2022-08-12'),
('20004', 'National ID', '3761-2890-5380-7139', '2022-08-12'),
('03033', 'City Government Employee\'s ID', '03033', '2007-03-28'),
('03033', 'City Government Employee\'s ID', '03033', '2007-03-28');

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
  `EmploymentStatus` varchar(30) NOT NULL,
  `profile_pic` varchar(50) NOT NULL,
  `Privacy` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `i`
--

INSERT INTO `i` (`EmpNo`, `Lname`, `Fname`, `Mname`, `Extension`, `BirthDate`, `PlaceBirth`, `Gender`, `Civil`, `Password`, `Dept`, `Height`, `Weight`, `BloodType`, `GSIS`, `Pagibig`, `PHealth`, `SSS`, `Tin`, `AgencyEmpNo`, `Citizenship`, `Country`, `HouseNo`, `Street`, `Subd`, `Brgy`, `City`, `Province`, `Zip`, `Perm_House`, `Perm_Street`, `Perm_Subd`, `Perm_Brgy`, `Perm_City`, `Perm_Province`, `Perm_Zip`, `TelNo`, `MobileNo`, `EMail`, `EmploymentStatus`, `profile_pic`, `Privacy`) VALUES
('00034', 'MAÑIBO', 'MARVIE', 'DIMAANO', 'N/A', '1974-08-09', '', 'Female', 'Married', 'password', 'City Education Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('00070', 'BENTER', 'CLAIRE', 'PACIS', 'N/A', '1961-03-21', '', 'Female', 'Married', 'password', 'City Public Library', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('00073', 'AGUA', 'BENJAMIN', 'MENDOZA', 'JR.', '1961-03-23', '', 'Male', 'Married', 'password', 'Person with Disability Affairs Office', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('00075', 'COLLERA', 'ELEANNOR', 'VILLAS', '', '1961-03-21', '', 'Female', 'Married', 'password', 'City Cooperative Development Office', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('00096', 'DEL MUNDO', 'LOUIELYN JOSELITO', 'EVANGELISTA', 'N/A', '1974-10-11', '', 'Male', 'Married', 'password', 'City Information Office', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('00132', 'ESCOSORA', 'DENNIS', 'TOLENTINO', 'N/A', '1978-12-27', '', 'Male', 'Married', 'password', 'City Disaster Risk Reduction Management Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('00153', 'BENTER', 'NEPO JEROME', 'GESTA', 'N/A', '1977-09-30', '', 'Male', 'Married', 'password', 'City Economic Enterprise Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('01035', 'DALISAY', 'ARNEL', 'ALBANIA', 'N/A', '1978-04-20', '', 'Male', 'Married', 'password', 'City Administrator, Chief of Staff, and Secretary ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('01097', 'TAGUPA', 'MARIAN TERESA', 'GLORIA', 'N/A', '1984-02-27', 'CALAPAN, ORIENTAL MINDORO', 'Female', 'Single', '$2y$12$ys/cUBI7H10yj/D7VTmKZ.c8hrTToSDEFGkME1q6KS1ak4.kAapdS', 'City Human Resource Management Department', '1.53', '55', 'AB+', '02004082862', '121010633449', '090502169962', '0419507330', '', '', 'Filipino', '', 'BLOCK 3', 'MANGGA ST.', 'SANTA MARIA VILLAGE', 'SMV', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', 'BLOCK 3', 'MANGGA ST.', 'SANTA MARIA VILLAGE', 'SMV', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', 'N.A.', '09171572702', 'marianteresa.tagupa@gmail.com', '', '', ''),
('02005', 'CUETO', 'DORINA ROXANNE', 'ROJAS', 'N/A', '1982-07-01', '', 'Female', 'Married', 'password', 'City Human Resource Management Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('02009', 'SORIANO', 'ELMA', 'BONQUIN', 'N/A', '1979-04-12', 'Calapan', 'Female', 'Married', '$2y$12$FBUg93NDLj0DUoGjgMXS2.AwPTdUbJw/3kFZU6fBuGACDYm.PD1G.', 'City Human Resource Management Department', '1.52m', '55kg', 'A+', '2005930682', '121258813149', '092516345200', '0411823999', '940-987-270', '02009', 'Filipino', '', '', 'SITIO B3', '', 'CAMANSIHAN ', 'Calapan City', 'Oriental Mindoro', '5200', ' ', 'Sitio B3', '', 'Camansihan ', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', 'N/A', '09660284502', 'elmasoriano38@gmail.com', '', '', ''),
('03001', 'PANAHON', 'MARVIN', 'LOPEZ', 'N/A', '1976-05-18', '', 'Male', 'Married', 'password', 'City Youth and Sports Development Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('03010', 'REYES', 'REDENTOR', 'ACEVEDA', 'JR.', '1976-06-19', '', 'Male', 'Married', 'password', 'City Housing and Urban Settlements Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('03016', 'BAUTISTA', 'JOSELITO', 'ROJAS', 'N/A', '1968-11-22', '', 'Male', 'Married', 'password', 'Urban Planning and Development Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('03033', 'MANALO', 'GIERLY', 'BOONGALING', 'N/A', '1968-07-12', 'Adrialuna, Naujan, Oriental Mindoro', 'Female', 'Married', '$2y$12$uVwKLvbSq1Z2MGhWHcdzduEuAVoNOQsrJQuFyDrdF8CmPKmfrW4Cu', 'City Human Resource Management Department', '1.50', '47.0', '0', '4076-2951-8', '1490-0049-7724', '09-000070753-7', '04-0762951-8', '153 598 417 000', '03033', 'Filipino', '', '', 'Centro', '', 'Managpi', 'Calapan City', 'Oriental Mindoro', '5200', '', 'Centro', '', 'Managpi', 'Calapan City', 'Oriental Mindoro', '5200', '', '09194537335', 'gingmanalo1@gmail.com', '', '', ''),
('05005', 'PEREZ', 'DARWIN', 'MANALO', 'N/A', '1977-12-20', '', 'Male', 'Married', 'password', 'Bids and Awards Committee', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('06005', 'VILLAS', 'JANNETTE', 'MIRAPLES', 'N/A', '1972-09-18', '', 'Female', 'Married', 'password', 'City Budget Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('06007', 'ABAS', 'AUBREY ROSE', 'ORDILLANO', 'N/A', '1978-12-29', '', 'Female', 'Married', '$2y$12$a/q.V2u8zytrV/MdU1OfAetDmKcAxt1XOOAk4hXZ/3/jMy0XCAHLO', 'City Human Resource Management Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('07001', 'BASILAN', 'EDGARDO', 'CASTILLO', 'N/A', '1973-01-04', '', 'Male', 'Married', 'password', 'City Accounting and Internal Audit Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('08011', 'CATAPANG', 'NICASIO', 'DINGLASAN', 'N/A', '1962-11-13', '', 'Male', 'Married', 'password', 'City Treasury Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('09012', 'MASONGSONG', 'JELSON', 'OGBAC', 'N/A', '1967-12-04', '', 'Male', 'Married', 'password', 'City Assessor Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('10002', 'LEONAR', 'THELMA', 'DECENA', 'N/A', '1964-04-01', '', 'Female', 'Married', 'password', 'City Legal Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('10008', 'MEIM', 'RAMIL', 'ROMERO', 'N/A', '1980-02-18', '', 'Male', 'Married', 'password', 'City Legal Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('10009', 'ARUINO', 'JONATHAN', 'NACELO', 'N/A', '1976-07-19', '', 'Male', 'Married', 'password', 'City Legal Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('10010', 'GOCO', 'CHONA', 'LOMIO', 'N/A', '1979-12-17', 'STA. ROSA, LAGUNA', 'Female', 'Married', '$2y$12$KgapBmg8Ag6C6uF9/TN5k.F3reOjCC5iRZkUckJXxni0naqet6EZW', 'City Legal Department', '5\'4\"', '58kls', 'B+', '021158202335', '121168130856', '092008364518', 'N/A', '', '', 'Filipino', '', '', 'CAMIA STREET', '', 'TAWIRAN', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', '', 'CAMIA', '', 'TAWIRAN', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', '', '09172470113', 'unaygoco@gmail.com', '', '', ''),
('10011', 'DALISAY', 'ARNEL', 'ALBANIA', 'N/A', '1978-04-20', '', 'Male', 'Married', 'password', 'City Legal Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('10013', 'QUIAMBAO', 'BIENVINIDO', 'SALCEDO', 'N/A', '1977-11-29', '', 'Male', 'Married', 'password', 'City Legal Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('10016', 'DIOMAMPO', 'MA. ELLAINE KRIS', 'RUBIO', 'N/A', '1987-03-07', '', 'Female', 'Single', 'password', 'City Legal Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('10017', 'CIRUJANO', 'HELYNE', 'LIDAY', 'N/A', '1994-10-14', '', 'Female', 'Married', 'password', 'City Legal Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('11001', 'LLANTO', 'BASILISA', 'MAGSINO', 'N/A', '1962-04-10', '', 'Female', 'Single', 'password', 'City Health and Sanitation Department – City Plaza', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('11002', 'RAQUEPO', 'GLENDA', 'MENDOZA', 'N/A', '1969-07-28', '', 'Female', 'Married', 'password', 'City Nutrition Office', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('11006', 'BOLOR', 'MA. TERESITA', 'NIEVA', 'N/A', '1964-03-10', '', 'Female', 'Married', 'password', 'City Health and Sanitation Department – City Plaza', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('12001', 'BAHIA', 'JUVY', 'LAYGO', 'N/A', '1964-10-12', '', 'Female', 'Married', 'password', 'City Social Welfare Development Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('13012', 'RAGO', 'PAMELA', 'ESCAREZ', 'N/A', '1973-07-13', '', 'Female', 'Married', 'password', 'City Agricultural Services Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('14001', 'MANGLICMOT', 'FEBY DAR', 'CAOLI', 'N/A', '1974-02-03', '', 'Female', 'Married', 'password', 'City Veterinary Services Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('16001', 'VILLAS', 'ELMER', 'CHAVEZ', 'N/A', '1967-06-06', '', 'Male', 'Married', 'password', 'City Architectural Planning and Design Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('18003', 'CANTOS', 'RONALD', 'FERNANDEZ', 'N/A', '1977-03-13', '', 'Male', 'Married', 'password', 'City College of Calapan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('18006', 'REDUBLO', 'EDER APOLINAR', 'MARASIGAN', 'N/A', '1963-07-23', '', 'Male', 'Married', 'password', 'City Public Employment Services Office', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('18008', 'GAUD', 'CHRISTIAN', 'ENRIQUEZ', 'N/A', '1978-10-13', '', 'Male', 'Married', 'password', 'City Tourism, Culture and Arts Office', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('18009', 'LEYNES', 'JOANNE MARGARET', 'ORITO', 'N/A', '1980-10-25', '', 'Female', 'Single', 'password', 'City Trade and Industry Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('19062', 'MENDOZA', 'DESIREE', 'PEÑAESCOSA', 'N/A', '1973-09-27', '', 'Female', 'Married', 'password', 'City Human Resource Management Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('19063', 'MENDOZA', 'JOHN MICHAEL', 'DUKA', 'N/A', '1999-04-13', 'Calapan City', 'Male', 'Married', '$2y$12$0RzuprgIwb7KlWDiLk0UT.d/ZiwJ0exoyVAdOeR8j3/npTLpyg/ci', 'City Legal Department', '1.71', '69', 'O+', '', '', '', '', '', '', 'Filipino', '', 'N/A', 'N/A', 'Proper', 'Canubing 1', 'Calapan City', 'Oriental Mindoro', '5200', '', '', 'Proper', 'Canubing 1', 'Calapan City', 'Oriental Mindoro', '5200', '', '09084815994', 'jmm46764@gmail.com', '', '', ''),
('19086', 'CABANDING', 'ERMIN', 'CAYABYAB', 'N/A', '1970-12-12', '', 'Male', 'Married', 'password', 'City Public Safety Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('20001', 'LANDICHO', 'WILFREDO', 'GIPAN', 'N/A', '1967-11-04', '', 'Male', 'Married', 'password', 'City Environment and Natural Resources Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('20004', 'LIBRE', 'ALEXIS', 'SALOME', 'N/A', '1974-01-23', 'CALAPAN CITY, ORIENTAL MINDORO', 'Male', 'Married', '$2y$12$tgqO3sHaggxf8I3JS.Jiqe3FvEUkaLWKNf.y/DEWpJwFe51SYrfqe', 'City Human Resource Management Department', '1.72', '85', 'A', '006-0021-7301-0', '1490-0046-6717', '09-000058846-5', '04-3366811-6', '930-391-243', '20004', 'Filipino', 'PHILIPPINES', '', 'SITIO UBASAN', '', 'MAHAL NA PANGALAN', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', '', 'SITIO UBASAN', '', 'MAHAL NA PANGALAN', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', '043 288 9402', '0915 218 8669', 'aslibre@gmail.com', '', '', ''),
('21001', 'MAURO', 'DEO MAR FRANCIS', 'FABIAN', 'N/A', '1973-08-17', '', 'Male', 'Married', 'password', 'City Civil Registry Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('22005', 'ABAS', 'POLICARPIO EDMUND', 'BACULO', 'N/A', '1972-12-01', '', 'Male', 'Married', 'password', 'City General Services Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
('22010', 'MANALO', 'MA. HAIDEE', 'MARTINEZ', 'N/A', '1976-10-23', 'CALAPAN', 'Female', 'Married', '$2y$12$GGoSO0MMpGdo5i9pf8hSC.KaY0UwWhvDnh4ATZHPU9gcjbTQiUuB.', 'City Human Resource Management Department', '5\'6', '70', 'B+', '', '', '', '', '', '', 'Filipino', '', '', '', '', 'STA, RITA', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', '', '', 'STA. RITA', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', '09999972995', 'haidee_martinez@yahoo.com', '', '', ''),
('22012', 'CUASAY', 'ARPEE RODOLFO', 'SERVAN', 'N/A', '1986-12-19', 'Calapan, Oriental Mindoro', 'Male', 'Married', '$2y$12$EHiSJRqNWO/5sNps8MBWjO3.MTlk1MUo/oT2BnaK.SOEa191/MZ7y', 'City Human Resource Management Department', '5\'7 (1.71)', '100', 'B', '02003966591', '1210-9765-0385', '090251955424', '0440324155', '', '22012', 'Filipino', '', '', '', 'Sitio Camarin 2', 'Biga', 'Calapan City', 'Oriental Mindoro', '5200', '', '', 'Sitio Camarin 2', 'Biga', 'Calapan City', 'Oriental Mindoro', '5200', 'n/a', '0910-743-7448', 'arjcuasay@yahoo.com', '', '', ''),
('22015', 'ADANTE', 'ELAINE', 'ILAGAN', 'N/A', '1985-03-16', 'CALAPAN CITY, ORIENTAL MINDORO', 'Female', 'Married', '$2y$12$dAvg/VWemgkygqmk1n3XQOURe4PeleoNezMkq6vktlP8Ynnm7m3Pi', 'City Human Resource Management Department', '1.49 ', '56', 'O+', '2005073078', '00103084-2301', '09-025336999-9', '0415395999', '295-366-245-000', '22015', 'by Birth', '', '', 'HULO', '', 'BARUYAN', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', 'HULO', '', 'BARUYAN', 'CALAPAN', 'ORIENTAL MINDORO', '5200', 'N/A', '09177378387', 'elaine.adante@gmail.com', '', '', ''),
('22020', 'GARCIA', 'BENIFE', 'DALISAY', 'N/A', '1982-06-12', 'CALAPAN', 'Female', 'Married', '$2y$12$T4ExGqdeqr/ylUZ5.wvvWub4WA6VbkcpOO0YLqZGfdViD.9EApxC6', 'City Human Resource Management Department', '1.54', '73', '0+', '2005679015', '121261051449', '092516354293', 'NONE', '265586995000', '', 'by Birth', '', '', '', 'SITIO COMON', 'BALINGAYAN', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', '', '', 'SITIO COMON', 'BALINGAYAN', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', 'JOJO A. GARCIA', '09365167191', '', '', '', ''),
('22021', 'CANTOS', 'REGINALD', 'ALCAÑIECES', 'N/A', '1999-06-06', 'CALAPAN CITY', 'Female', 'Single', '$2y$12$psm6tPI0N9QuaW2cBNhbEONDE2QJuFgUX.dFq3BuSLx96jhiPaplq', 'City Human Resource Management Department', '5\'2', '76', 'O+', '2006019638', '121294425463', '09-251610578-1', 'N/A', '720-579-514-000', '22021', 'Filipino', '', 'N/A', 'IRRIGATION A', 'N/A', 'MASIPIT', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', 'N/A', 'IRRIGATION A', 'N/A', 'MASIPIT', 'CALAPAN CITY', 'ORIENTAL MINDORO', '5200', 'N/A', '09985913108', 'reigncantos@yahoo.com', '', '', ''),
('22022', 'ADAME', 'FE', 'DE LEMOS', 'N/A', '1978-05-08', 'Calapan', 'Female', 'Single', '$2y$12$9CFi1RLBqol93DsXj4FOSuEssv.INi0jhiukgXWRl/Yy/FZ3VK6LS', 'City Human Resource Management Department', '5\'2', '65', 'B+', '2002166807', '1212-3312-0294', '09-202116058-1', '', '925-336-742', '22022', 'Filipino', '', '', 'M. Quezon', '', 'Calero', 'Calapan City', 'Or. Mindoro', '5200', '', 'M. Quezon', '', 'Calero', 'Calapan City', 'Or. Mindoro', '5200', '', '09467510562', 'adamedorife0508@gmail.com', '', '', ''),
('22023', 'PURIO', 'CLEO DENISE', 'VILLANUEVA', 'N/A', '1979-12-27', 'Catiningan Socorro Oriental Mindoro', 'Female', 'Married', '$2y$12$J8AaoJ/0p49/HUTeOCfcyOfeJEzDoI9.cwGCTOixKKCAO9Z/o28IS', 'City Human Resource Management Department', '5\'3', '92', 'O', '', '', '', '', '', '22023', 'Filipino', '', '', 'Block II', '', 'Sta Maria Village', 'Calapan City', 'Oriental Mindoro', '5200', '', '', '', 'Catiningan', 'Socorro', 'Oriental Mindoro', '5207', '', '09061940811', 'cleodenisev@gmail.com', '', '', ''),
('22024', 'GUTIERREZ', 'MA. ALLYSA ROSE', 'CAMPOS', 'N/A', '1998-07-15', 'CALAPAN CITY', 'Female', 'Single', '$2y$12$sBNP7i8E33x6vR9eRaruc.DMPd3Ej.WgzK9PV/VZbX/2tbJ0jnrCK', 'City Human Resource Management Department', '', '60', 'A+', '', '121240262289', '09-251631960-9		', '04-4160405-2		', '', '', 'Filipino', '', '', '', '', 'BAYANAN I', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', '', '', 'BAYANAN I', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', '09635235907', 'gutierrezmaallysarose@gmail.com', '', '', ''),
('22025', 'DE GUZMAN', 'NONALYN', 'MARTINEZ', 'N/A', '1984-11-12', 'TIAONG, QUEZON', 'Female', 'Married', '$2y$12$zbHcTa3vzQn/ZE6dVNyj2.q5.WcDyv2v9FynLbOGn6EEqpyHwJ6vO', 'City Human Resource Management Department', '1.64592 m', '68', 'A+', '2006433886', '1211-6459-6347		 		', '09-251587324-6		', '04-1185329-9', '', '', 'Filipino', '', 'BLOCK 5', '', '', 'PACHOCA', 'CALAPAN', 'ORIENTAL MINDORO', '5200', 'BLOCK 5', '', '', 'PACHOCA', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', '09098246300', 'len_deguzman@yahoo.com', '', '', ''),
('24014', 'DILAY', 'MELSON', 'BERON', 'N/A', '1979-05-15', 'WAWA, CALAPAN  ORIENTAL MINDORO		', 'Male', 'Married', '$2y$12$khzPBBJSxn1keF8Rp.Bfh.8t6INxHxo30Z5Ty7XdEheEEg.Yr0/yC', 'City Human Resource Management Department', '1.75		 		', '81		', 'O		 	', 'CRN-4188-1724-8		 		', '1490-0053-3358		 		', '09-0500-72231-1		', '04-1181724-8		', '', '', 'Filipino', '', 'NA		', 'NA		', 'NA		', 'WAWA		 		', 'CALAPAN		', 'ORIENTAL MINDORO		', '5200					', 'NA		', 'NA		', 'NA		', 'WAWA		', 'CALAPAN		', 'ORIENTAL MINDORO		', '5200					', 'NA					', '09778030054					', 'melsondilay56@gmail.com', '', '', ''),
('24018', 'CUSI', 'PATRICK', 'DILAO', '', '1990-12-03', '', 'Male', 'Single', '$2y$12$dz8m5NlO/g.Dgi/ISHXnYezQ91.HGANVVnb.UQKmNfTLrF1B9149e', 'Management Information System Office', '', '', '', '', '', '', '', '', '24018', 'Filipino', '', '', '', '', 'MAHAL NA PANGALAN', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '', '', '', 'MAHAL NA PANGALAN', 'CALAPAN', 'ORIENTAL MINDORO', '5200', '09063351625', '09063351625', 'PAT3CK.CUSI@GMAIL.COM', '', '', ''),
('6013', 'CAMACHO', 'CYRILLE ANNE', 'COSTALES', '', '1989-03-29', '', 'Female', 'Single', '$2y$12$8zex6n09iLYM7lG1BGIQwuEeW7FK08/S/A/zMIb0a9fWLgfpBVXUu', 'City Budget Department', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'profile_6013.png', 'Yes');

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
('01097', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'TAGUPA', 'TERESITO', 'PRADO', 'N/A', 'N/A', 'VERONICA', 'MANQUERIA'),
('02009', 'SORIANO', 'NESTOR', 'BACAY', 'N/A', 'GOV\'T EMPLOYEE', 'CITY GOVERNMENT OF CALAPAN', 'GUINOBATAN, CALAPAN CITY, ORIENTAL MINDORO', 'N/A', 'BONQUIN', 'PABLITO +', 'ROMASANTA', 'N/A', 'CABUHAL', 'TERESA +', 'CARPIO'),
('03033', 'Manalo', 'Nonilon', 'Matibag', '', 'N/A', 'N/A', 'N/A', 'N/A', 'Boongaling', 'Regino ', 'Hernandez', 'Sr.', 'Saulong ', 'Beverly', 'Barrientos'),
('10010', 'GOCO', 'EISEN LOWELL', 'ATIENZA', 'N/A', 'GOV\'T EMPLOYEE', 'DOTR', 'MANILA', 'N/A', 'LOMIO', 'CARLO', 'DELA ROSA', 'N/A', 'FLORES', 'RIZALINA', 'ERCILLO'),
('19063', 'Mendoza', 'Niña Ericka', 'Matining', 'N/A', 'Pharmacist', 'SouthStarDrug. Inc', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('20004', 'TIPA', 'KARREN', 'DIARES', 'N/A', 'GOVERNMENT EMPLOYEE', 'DEPARTMENT OF EDUCATION', 'QUEZON DRIVE, BARANGAY CALERO, CALAPAN CITY, ORIENTAL MINDORO', '043 288 1581', 'LIBRE', 'ARNOLD', 'DAYRIT', 'SR', 'SALOME', 'YOLANDA', 'ACEVEDA'),
('22010', 'MANALO', 'TIRSO', 'DE CHAVEZ', 'JR.', 'SEAFARER', 'MAGSAYSAY MARITIME CORPORATION', 'G.E. ANTONIO BLDG, KALAW AVE, ERMITA MANILA, 1000', '02 8526 8888', 'MARTINEZ', 'EDYMAR', 'TAMPILIK', 'N/A', 'MARTINEZ', 'LUZVIMINDA', 'CASTILLO'),
('22012', 'CUASAY', 'JONALY', 'JOTA', 'N/A', 'GOVERNMENT EMPLOYEE (Administrative Officer V)', 'CITY GOVERNMENT OF CALAPAN', 'BRGY. GUINOBATAN, CALAPAN CITY, ORIENTAL MINDORO', 'N/A', 'CUASAY', 'RODOLFO', 'MARASIGAN', 'N/A', 'SERVAN', 'AGAPITA', 'CALAPIT'),
('22015', 'ADANTE', 'JEFFREY', 'SANDOVAL', 'N/A', 'GOVERNMENT EMPLOYEE', 'CITY GOVERNMENT OF CALAPAN', 'GUINOBATAN, CALAPAN CITY, ORIENTAL MINDORO', 'N/A', 'ILAGAN', 'MANUEL', 'MAÑIBO', 'JR.', 'ABACA', 'LORNA', 'ALBANIA'),
('22021', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'CANTOS', 'RONALD', 'FERNANDEZ', 'N/A', 'ALCAÑICES', 'LERMA', 'RECAÑA'),
('22023', 'Purio', 'MN/A', 'Cabrera', 'N/A', 'Service Engineer', 'N/A', 'Ortigas Metro Manila', 'N/A', 'Villanueva', 'N/A', 'Torrado', 'N/A', 'Siena', 'Lea Flor', 'Cruzado'),
('22024', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'GUTIERREZ				', 'ALBERTO		', 'DELA CRUZ				', 'N/A', 'CAMPOS		', 'ROSALINA				', 'ENDAYA			'),
('22025', 'DE GUZMAN', 'EMMANUEL', 'ROMARATE', 'N/A', 'DRIVER', 'INFINITY INTEGRATED AGRIFARM RESORT CORP', 'BACO, ORIENTAL MINDORO', 'N/A', 'MARTINEZ', 'NESTOR (DECEASED)', 'CRUSIT', 'N/A', 'GARCIA', 'NATALIA', 'MALABANAN'),
('24014', 'DILAY', 'MARY ROSE		', 'DIMAUNAHAN				', 'N/A', 'GOVERNMENT EMPLOYEE', 'DEPARTMENT OF EDUCATION- ORIENTAL MINDORO DIVISION', 'CALAPAN CITY, ORIENTAL MINDORO', 'N/A', 'DILAY', 'MELCHOR', 'CARENA', 'N/A', 'DILAY', 'NENITA', 'BERON'),
('24018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 'N/A', 'N/A', 'N/A', '');

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
('24018', 'GRADUATE STUDIES', 'GRADUATE ', '', '', '', '', '', ''),
('24014', 'ELEMENTARY', 'WAWA ELEMENTARY SCHOOL		', 'ELEMENTARY GRADUATE		', '1986', '1992', 'NA', '1992', ''),
('24014', 'SECONDARY', 'DIVINE WORD COLLEGE OF CALAPAN		', 'HIGH SCHOOL GRADUATE		', '1992', '1996', 'NA', '1996', ''),
('24014', 'VOCATIONAL/TRADE COURSE', 'TESDA		', 'CERTIFICATION ON COMPLETION FOOD PROCESSING NC II		', '2016', '2016', 'NA', '2016', ''),
('24014', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN		', 'BACHELOR OF SECONDARY EDUCATION		', '1996', '2001', 'NA', '2001', ''),
('24014', 'GRADUATE STUDIES', 'DIVINE WORD COLLEGE OF CALAPAN		', ' MASTER OF ARTS IN MATHEMATICS		', '2003', '2009', 'NA', '2009', ''),
('22025', 'ELEMENTARY', 'PAIISA ELEMENTARY SCHOOL', 'ELEMENTARY', '1992', '1998', 'GRADUATED', '1998', '2ND HONORABLE MENTION'),
('22025', 'SECONDARY', 'PAIISA NATIONAL HIGH SCHOOL', 'HIGH SCHOOL', '1998', '2002', 'GRADUATED', '2002', 'TOP 7'),
('22025', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22025', 'COLLEGE', 'ACLC COLLEGE OF CALAPAN		', 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION/ HUMAN RESOURCE DEVELOPMENT MANAGEMENT		', '2012', '2015', 'GRADUATED', '2015', 'CUM LAUDE'),
('22025', 'GRADUATE STUDIES', 'DIVINE WORD COLLEGE OF CALAPAN		', 'MASTERS IN PUBLIC ADMINISTRATION		', '2025', 'PRESENT', 'N/A', 'N/A', 'N/A'),
('19063', 'ELEMENTARY', 'Canubinh Elementary School', '', '', '', '', '', ''),
('10010', 'ELEMENTARY', 'JOSE L. BASA MEM SCHOOL		', 'ELEMENTARY', '1986', '1992', 'N/A', 'GRAD', 'N/A'),
('10010', 'SECONDARY', 'NAUJAN ACADEMY		', 'SECONDARY', '1992', '1996', 'N/A', 'GRAD', 'N/A'),
('10010', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('10010', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN		', 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE		', '1996', '2000', 'N/A', 'GRAD', 'N/A'),
('10010', 'GRADUATE STUDIES', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22010', 'ELEMENTARY', 'BUNGAHAN ELEMENTARY SCHOOL', 'ELEMENTARY', '1983', '1989', 'GRADUATED', '1989', ''),
('22010', 'SECONDARY', 'DIVINE WORD COLLEGE OF CALAPAN', 'HIGH SCHOOL', '1989', '1993', 'GRADUATED', '1993', 'FULL SCHOLARSHIP'),
('22010', 'COLLEGE', 'NATIONAL TEACHERS COLLEGE', 'AB PSYCHOLOGY', '1993', '1997', 'GRADUATED', '1997', ''),
('22024', 'ELEMENTARY', 'BAYANAN I ELEMENTARY SCHOOL					', 'Primary		', ' 2004 ', '2010', 'GRADUATED', '2010', '1st HONOR'),
('22024', 'SECONDARY', 'HOLY INFANT ACADEMY		', 'Secondary		', '2010', '2014', 'GRADUATED', '2014', ''),
('22024', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22024', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN		', 'BACHELOR OF ARTS IN PSYCHOLOGY		', '2014', '2018', 'GRADUATED', '2018', 'CHED'),
('22024', 'GRADUATE STUDIES', 'MINDORO STATE UNIVERSITY		', 'Unit Earner		', '2021', '2022', 'GRADUATED', '2022', ''),
('22012', 'ELEMENTARY', 'HOLY INFANT ACADEMY', 'PRIMARY', '1993', '1999', 'N/A', '1999', 'N/A'),
('22012', 'SECONDARY', 'HOLY INFANT ACADEMY', 'SECONDARY', '1999', '2003', 'N/A', '2003', 'N/A'),
('22012', 'VOCATIONAL/TRADE COURSE', 'CLCC-ICAT', 'COMPUTER PROGRAMMING', '2003', '2006', 'N/A', '2006', 'N/A'),
('22012', 'COLLEGE', 'CITY COLLEGE OF CALAPAN', 'BACHELOR OF SCIENCE IN INFORMATION SYSTEM', '2017', '2022', 'N/A', '2022', 'CGC Scholar (Executive Class)'),
('22012', 'GRADUATE STUDIES', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('24018', 'ELEMENTARY', 'elementary', '', '', '', '', '', ''),
('24018', 'SECONDARY', 'SECONDARY', '', '', '', '', '', ''),
('24018', 'VOCATIONAL/TRADE COURSE', 'VOCATIONAL', '', '', '', '', '', ''),
('24018', 'COLLEGE', 'COLLEGE	', '', '', '', '', '', ''),
('22021', 'ELEMENTARY', 'ADRIATICO MEMORIAL SCHOOL', 'ELEMENTARY', '2005', '2011', 'N/A', '2010', 'N/A'),
('22021', 'SECONDARY', 'HOLY INFANT ACADEMY', 'HIGH SCHOOL', '2011', '2015', 'N/A', '2011', 'N/A'),
('22021', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22021', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN', 'AB PSYCHOLOGY', '2015', '2018', '', '2018', 'N/A'),
('22021', 'GRADUATE STUDIES', 'MINDORO STATE UNIVERSITY', 'MA GUIDANCE & COUNSELING', '2020', 'PRESENT', '', 'N/A', 'N/A'),
('01097', 'ELEMENTARY', 'CALAPAN CHU ENG SCHOOL', 'N/A', '1990', '1996', 'N/A', '1996', 'SALUTATORIAN'),
('01097', 'SECONDARY', 'ORIENTAL MINDORO NAT’L HIGH SCHOOL', 'N/A', '1996', '2001', 'N/A', '2001', 'HS DIPLOMA'),
('01097', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('01097', 'COLLEGE', 'PHILIPPINE WOMEN’S UNIVERSITY', 'BSHRM', '2003', '2007', 'N/A', '2007', 'GRADUATE, BSHRM'),
('01097', 'GRADUATE STUDIES', 'BATANGAS STATE UNIVERSITY', 'JURIS DOCTOR - NON THESIS', '2019', '2025', 'N/A', '2025', 'GRADUATE, JD'),
('20004', 'ELEMENTARY', 'Adriatico Memorial School', 'Elementary', '1981', '1987', 'N/A', '1987', 'N/A'),
('20004', 'SECONDARY', 'Oriental Mindoro National High School (formerly Jose J. Leido, Jr. National Memorial High School)', 'Secondary', '1987', '1991', 'N/A', '1991', 'N/A'),
('20004', 'VOCATIONAL/TRADE COURSE', 'National Manpower Youth Center/Technical Education and Skills Development Authority', 'General Electricity and Industrial Electricity', 'May 1995 / November 1995', 'August 1995 / December 1995', 'N/A', '1995', 'N/A'),
('20004', 'COLLEGE', 'Divine Word College of Calapan', 'BS Commerce major in Management', '1996', '2000', 'N/A', '2000', 'N/A'),
('20004', 'GRADUATE STUDIES', 'Ateneo De Manila University - School of Government', 'Master in Public Management', '2005', '2007', 'N/A', '2007', 'CITY GOVERNMENT OF CALAPAN SCHOLAR'),
('02009', 'ELEMENTARY', 'CAMANSIHAN ELEM. SCHOOL', 'PRIMARY EDUCATION', '1996', '1992', 'N/A', '1992', 'N/A'),
('02009', 'SECONDARY', 'MANAGPI NAT\'L GIGH SCHOOL', 'SECONDARY EDUCATION', '1993', '1997', 'N/A', '1997', 'N/A'),
('02009', 'VOCATIONAL/TRADE COURSE', 'DIVINE WORD COLLEGE OF CALAPAN', 'ASSOCIATE COMP. SECRETARIAL', '1999', '2002', 'N/A', '2002', 'N/A'),
('02009', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN', 'BACHELOR OF SCIENCE IN OFFICE ADMINISTRATION', '2013', '2015', 'N/A', '2015', 'N/A'),
('22015', 'ELEMENTARY', 'BARUYAN ELEMENTARY SCHOOL', 'PRIMARY', '1991', '1997', '', '1997', ''),
('22015', 'SECONDARY', 'HOLY INFANT ACADEMY', 'SECONDARY', '1997', '2001', '', '2001', ''),
('22015', 'VOCATIONAL/TRADE COURSE', 'NA', '', '', '', '', '', ''),
('22015', 'COLLEGE', 'LYCEUM OF BATANGAS', 'BACHELO OF SCIENCE IN BUSINESS & COMPUTER MANAGEMENT', '2001', '2005', '', '2005', ''),
('03033', 'ELEMENTARY', 'Adrialuna Elem. School', 'Elementary', '1975', '1981', '', '1981', 'Third Honors'),
('03033', 'SECONDARY', 'Porfirio G. Comia Mem. High School', 'High School', '1981', '1985', '', '1985', 'Top Honors'),
('03033', 'VOCATIONAL/TRADE COURSE', 'Arabel Vocational School', 'Steno-Typing (Summer Class)', '1985', '1986', '', '1986', 'First Honors '),
('03033', 'COLLEGE', 'Divine Word College of Calapan', 'BSC-Management', '1985', '1989', '', '1989', 'First Runner-Up Best Stenographer'),
('03033', 'GRADUATE STUDIES', 'University of Makati', 'Master in Development Management and Governance', '2015', '2017', '', '2017', 'City Government of Calapan Scholar'),
('24014', 'ELEMENTARY', 'WAWA ELEMENTARY SCHOOL		', 'ELEMENTARY GRADUATE		', '1986', '1992', 'NA', '1992', ''),
('24014', 'SECONDARY', 'DIVINE WORD COLLEGE OF CALAPAN		', 'HIGH SCHOOL GRADUATE		', '1992', '1996', 'NA', '1996', ''),
('24014', 'VOCATIONAL/TRADE COURSE', 'TESDA		', 'CERTIFICATION ON COMPLETION FOOD PROCESSING NC II		', '2016', '2016', 'NA', '2016', ''),
('24014', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN		', 'BACHELOR OF SECONDARY EDUCATION		', '1996', '2001', 'NA', '2001', ''),
('24014', 'GRADUATE STUDIES', 'DIVINE WORD COLLEGE OF CALAPAN		', ' MASTER OF ARTS IN MATHEMATICS		', '2003', '2009', 'NA', '2009', ''),
('22025', 'ELEMENTARY', 'PAIISA ELEMENTARY SCHOOL', 'ELEMENTARY', '1992', '1998', 'GRADUATED', '1998', '2ND HONORABLE MENTION'),
('22025', 'SECONDARY', 'PAIISA NATIONAL HIGH SCHOOL', 'HIGH SCHOOL', '1998', '2002', 'GRADUATED', '2002', 'TOP 7'),
('22025', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22025', 'COLLEGE', 'ACLC COLLEGE OF CALAPAN		', 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION/ HUMAN RESOURCE DEVELOPMENT MANAGEMENT		', '2012', '2015', 'GRADUATED', '2015', 'CUM LAUDE'),
('22025', 'GRADUATE STUDIES', 'DIVINE WORD COLLEGE OF CALAPAN		', 'MASTERS IN PUBLIC ADMINISTRATION		', '2025', 'PRESENT', 'N/A', 'N/A', 'N/A'),
('19063', 'ELEMENTARY', 'Canubinh Elementary School', '', '', '', '', '', ''),
('10010', 'ELEMENTARY', 'JOSE L. BASA MEM SCHOOL		', 'ELEMENTARY', '1986', '1992', 'N/A', 'GRAD', 'N/A'),
('10010', 'SECONDARY', 'NAUJAN ACADEMY		', 'SECONDARY', '1992', '1996', 'N/A', 'GRAD', 'N/A'),
('10010', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('10010', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN		', 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE		', '1996', '2000', 'N/A', 'GRAD', 'N/A'),
('10010', 'GRADUATE STUDIES', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22010', 'ELEMENTARY', 'BUNGAHAN ELEMENTARY SCHOOL', 'ELEMENTARY', '1983', '1989', 'GRADUATED', '1989', ''),
('22010', 'SECONDARY', 'DIVINE WORD COLLEGE OF CALAPAN', 'HIGH SCHOOL', '1989', '1993', 'GRADUATED', '1993', 'FULL SCHOLARSHIP'),
('22010', 'COLLEGE', 'NATIONAL TEACHERS COLLEGE', 'AB PSYCHOLOGY', '1993', '1997', 'GRADUATED', '1997', ''),
('22024', 'ELEMENTARY', 'BAYANAN I ELEMENTARY SCHOOL					', 'Primary		', ' 2004 ', '2010', 'GRADUATED', '2010', '1st HONOR'),
('22024', 'SECONDARY', 'HOLY INFANT ACADEMY		', 'Secondary		', '2010', '2014', 'GRADUATED', '2014', ''),
('22024', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22024', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN		', 'BACHELOR OF ARTS IN PSYCHOLOGY		', '2014', '2018', 'GRADUATED', '2018', 'CHED'),
('22024', 'GRADUATE STUDIES', 'MINDORO STATE UNIVERSITY		', 'Unit Earner		', '2021', '2022', 'GRADUATED', '2022', ''),
('22012', 'ELEMENTARY', 'HOLY INFANT ACADEMY', 'PRIMARY', '1993', '1999', 'N/A', '1999', 'N/A'),
('22012', 'SECONDARY', 'HOLY INFANT ACADEMY', 'SECONDARY', '1999', '2003', 'N/A', '2003', 'N/A'),
('22012', 'VOCATIONAL/TRADE COURSE', 'CLCC-ICAT', 'COMPUTER PROGRAMMING', '2003', '2006', 'N/A', '2006', 'N/A'),
('22012', 'COLLEGE', 'CITY COLLEGE OF CALAPAN', 'BACHELOR OF SCIENCE IN INFORMATION SYSTEM', '2017', '2022', 'N/A', '2022', 'CGC Scholar (Executive Class)'),
('22012', 'GRADUATE STUDIES', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('24018', 'ELEMENTARY', 'elementary', '', '', '', '', '', ''),
('24018', 'SECONDARY', 'SECONDARY', '', '', '', '', '', ''),
('24018', 'VOCATIONAL/TRADE COURSE', 'VOCATIONAL', '', '', '', '', '', ''),
('24018', 'COLLEGE', 'COLLEGE	', '', '', '', '', '', ''),
('22021', 'ELEMENTARY', 'ADRIATICO MEMORIAL SCHOOL', 'ELEMENTARY', '2005', '2011', 'N/A', '2010', 'N/A'),
('22021', 'SECONDARY', 'HOLY INFANT ACADEMY', 'HIGH SCHOOL', '2011', '2015', 'N/A', '2011', 'N/A'),
('22021', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('22021', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN', 'AB PSYCHOLOGY', '2015', '2018', '', '2018', 'N/A'),
('22021', 'GRADUATE STUDIES', 'MINDORO STATE UNIVERSITY', 'MA GUIDANCE & COUNSELING', '2020', 'PRESENT', '', 'N/A', 'N/A'),
('01097', 'ELEMENTARY', 'CALAPAN CHU ENG SCHOOL', 'N/A', '1990', '1996', 'N/A', '1996', 'SALUTATORIAN'),
('01097', 'SECONDARY', 'ORIENTAL MINDORO NAT’L HIGH SCHOOL', 'N/A', '1996', '2001', 'N/A', '2001', 'HS DIPLOMA'),
('01097', 'VOCATIONAL/TRADE COURSE', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
('01097', 'COLLEGE', 'PHILIPPINE WOMEN’S UNIVERSITY', 'BSHRM', '2003', '2007', 'N/A', '2007', 'GRADUATE, BSHRM'),
('01097', 'GRADUATE STUDIES', 'BATANGAS STATE UNIVERSITY', 'JURIS DOCTOR - NON THESIS', '2019', '2025', 'N/A', '2025', 'GRADUATE, JD'),
('20004', 'ELEMENTARY', 'Adriatico Memorial School', 'Elementary', '1981', '1987', 'N/A', '1987', 'N/A'),
('20004', 'SECONDARY', 'Oriental Mindoro National High School (formerly Jose J. Leido, Jr. National Memorial High School)', 'Secondary', '1987', '1991', 'N/A', '1991', 'N/A'),
('20004', 'VOCATIONAL/TRADE COURSE', 'National Manpower Youth Center/Technical Education and Skills Development Authority', 'General Electricity and Industrial Electricity', 'May 1995 / November 1995', 'August 1995 / December 1995', 'N/A', '1995', 'N/A'),
('20004', 'COLLEGE', 'Divine Word College of Calapan', 'BS Commerce major in Management', '1996', '2000', 'N/A', '2000', 'N/A'),
('20004', 'GRADUATE STUDIES', 'Ateneo De Manila University - School of Government', 'Master in Public Management', '2005', '2007', 'N/A', '2007', 'CITY GOVERNMENT OF CALAPAN SCHOLAR'),
('02009', 'ELEMENTARY', 'CAMANSIHAN ELEM. SCHOOL', 'PRIMARY EDUCATION', '1996', '1992', 'N/A', '1992', 'N/A'),
('02009', 'SECONDARY', 'MANAGPI NAT\'L GIGH SCHOOL', 'SECONDARY EDUCATION', '1993', '1997', 'N/A', '1997', 'N/A'),
('02009', 'VOCATIONAL/TRADE COURSE', 'DIVINE WORD COLLEGE OF CALAPAN', 'ASSOCIATE COMP. SECRETARIAL', '1999', '2002', 'N/A', '2002', 'N/A'),
('02009', 'COLLEGE', 'DIVINE WORD COLLEGE OF CALAPAN', 'BACHELOR OF SCIENCE IN OFFICE ADMINISTRATION', '2013', '2015', 'N/A', '2015', 'N/A'),
('22015', 'ELEMENTARY', 'BARUYAN ELEMENTARY SCHOOL', 'PRIMARY', '1991', '1997', '', '1997', ''),
('22015', 'SECONDARY', 'HOLY INFANT ACADEMY', 'SECONDARY', '1997', '2001', '', '2001', ''),
('22015', 'VOCATIONAL/TRADE COURSE', 'NA', '', '', '', '', '', ''),
('22015', 'COLLEGE', 'LYCEUM OF BATANGAS', 'BACHELO OF SCIENCE IN BUSINESS & COMPUTER MANAGEMENT', '2001', '2005', '', '2005', ''),
('03033', 'ELEMENTARY', 'Adrialuna Elem. School', 'Elementary', '1975', '1981', '', '1981', 'Third Honors'),
('03033', 'SECONDARY', 'Porfirio G. Comia Mem. High School', 'High School', '1981', '1985', '', '1985', 'Top Honors'),
('03033', 'VOCATIONAL/TRADE COURSE', 'Arabel Vocational School', 'Steno-Typing (Summer Class)', '1985', '1986', '', '1986', 'First Honors '),
('03033', 'COLLEGE', 'Divine Word College of Calapan', 'BSC-Management', '1985', '1989', '', '1989', 'First Runner-Up Best Stenographer'),
('03033', 'GRADUATE STUDIES', 'University of Makati', 'Master in Development Management and Governance', '2015', '2017', '', '2017', 'City Government of Calapan Scholar');

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
('24018', 'csc', '85', '0000-00-00', 'CALAPAN', '3', '0000-00-00'),
('6013', 'CSC Professional', '80', '2021-10-01', '', '', '0000-00-00'),
('22012', 'N/A', 'N/A', '0000-00-00', 'N/A', 'N/A', '0000-00-00'),
('06007', 'CAREER SERVICE PROFESSIONAL', '84.59', '1999-08-15', 'ABADA COLLEGE, PINAMALAYAN OR. MDO.', '97-10627', '1999-09-14'),
('06007', 'CAREER SERVICE SUB-PROFESSIONAL', '84.79', '1997-10-19', 'JJLJNMHS, CALAPAN CITY', '96227553', '1997-11-28'),
('24014', 'LICENSURE EXAMINATION FOR TEACHERS (LET)				', '78.80%', '2001-08-01', 'MANILA, PHILIPPINES		', '739297', '2027-05-15'),
('01097', 'LICENSURE EXAMINATION FOR TEACHERS', '82.02%', '2025-09-30', 'UNIBERSIDAD DE MANILA', '1162368', '2028-02-27'),
('01097', 'CAREER CIVIL SERVICE - PROFESSIONAL EXAMINATION', '81.66%', '2008-11-16', 'JOSE J. LEIDO JR. MEMORIAL NATIONAL HIGH SCHOOL', 'N/A', '0000-00-00'),
('20004', 'Career Service Professional', '80.27', '2003-04-13', 'Jose J. Leido, Jr. Memorial National High School', '064806', '0000-00-00'),
('20004', 'Career Service Sub-Professional', '80.56', '1998-08-16', 'Juan Morente Memorial Pilot School', '564655', '0000-00-00'),
('24018', 'CSC', '80', '2025-01-01', 'CALAPAN', '1', '2010-12-31'),
('24018', 'CSC', '80', '2026-01-01', 'CALAPAN', '2', '2028-01-03'),
('22025', 'HONOR GRADUATE ELIGIBILITY				', 'N/A', '0000-00-00', 'N/A', '100104170303', '0000-00-00'),
('22024', 'LICENSURE EXAMINATION FOR TEACHERS				', '83.00', '2022-10-02', 'Adriatico Memorial School		', '2000940', '2026-12-07'),
('22024', 'CIVIL SERVICE PROFESSIONAL				', '80.74', '2022-08-07', 'Oriental Mindoro National High School		', '', '0000-00-00'),
('22024', 'CIVIL SERVICE SUBPROFESSIONAL				', '80.86', '2022-03-13', 'Adriatico Memorial School		', '', '0000-00-00'),
('22010', 'Career Service Professional', '80%', '2024-03-03', 'OMHS, Calapan City', '2410-233', '2024-12-11'),
('22021', 'CS PROFESSIONAL', '81.04', '2019-03-17', 'CALAPAN CITY', '', '0000-00-00'),
('22021', 'RA 1080-PSYCHOMETRICIAN', '', '2019-10-25', 'MANILA', '0022992', '2025-06-06'),
('22021', 'RA 1080-LPT', '', '2023-03-23', 'CALAPAN CITY', '2000943', '2026-06-06'),
('22015', 'CIVIL SERVICE COMMISSION CAREER SERVICE PROFESSIONAL ELIGIBILITY', '80.13', '2012-05-27', 'J.J. LEIDO JR. MEMORIAL NATIONAL HIGH SCHOOL - CALAPAN CITY', '', '0000-00-00'),
('03033', 'Civil Service Professional Eligibility', '80.26', '1993-10-17', 'JJ Leido National High School', '', '0000-00-00'),
('22012', 'N/A', 'N/A', '0000-00-00', 'N/A', 'N/A', '0000-00-00'),
('06007', 'CAREER SERVICE PROFESSIONAL', '84.59', '1999-08-15', 'ABADA COLLEGE, PINAMALAYAN OR. MDO.', '97-10627', '1999-09-14'),
('06007', 'CAREER SERVICE SUB-PROFESSIONAL', '84.79', '1997-10-19', 'JJLJNMHS, CALAPAN CITY', '96227553', '1997-11-28'),
('24014', 'LICENSURE EXAMINATION FOR TEACHERS (LET)				', '78.80%', '2001-08-01', 'MANILA, PHILIPPINES		', '739297', '2027-05-15'),
('01097', 'LICENSURE EXAMINATION FOR TEACHERS', '82.02%', '2025-09-30', 'UNIBERSIDAD DE MANILA', '1162368', '2028-02-27'),
('01097', 'CAREER CIVIL SERVICE - PROFESSIONAL EXAMINATION', '81.66%', '2008-11-16', 'JOSE J. LEIDO JR. MEMORIAL NATIONAL HIGH SCHOOL', 'N/A', '0000-00-00'),
('20004', 'Career Service Professional', '80.27', '2003-04-13', 'Jose J. Leido, Jr. Memorial National High School', '064806', '0000-00-00'),
('20004', 'Career Service Sub-Professional', '80.56', '1998-08-16', 'Juan Morente Memorial Pilot School', '564655', '0000-00-00'),
('24018', 'CSC', '80', '2025-01-01', 'CALAPAN', '1', '2010-12-31'),
('24018', 'CSC', '80', '2026-01-01', 'CALAPAN', '2', '2028-01-03'),
('22025', 'HONOR GRADUATE ELIGIBILITY				', 'N/A', '0000-00-00', 'N/A', '100104170303', '0000-00-00'),
('22024', 'LICENSURE EXAMINATION FOR TEACHERS				', '83.00', '2022-10-02', 'Adriatico Memorial School		', '2000940', '2026-12-07'),
('22024', 'CIVIL SERVICE PROFESSIONAL				', '80.74', '2022-08-07', 'Oriental Mindoro National High School		', '', '0000-00-00'),
('22024', 'CIVIL SERVICE SUBPROFESSIONAL				', '80.86', '2022-03-13', 'Adriatico Memorial School		', '', '0000-00-00'),
('22010', 'Career Service Professional', '80%', '2024-03-03', 'OMHS, Calapan City', '2410-233', '2024-12-11'),
('22021', 'CS PROFESSIONAL', '81.04', '2019-03-17', 'CALAPAN CITY', '', '0000-00-00'),
('22021', 'RA 1080-PSYCHOMETRICIAN', '', '2019-10-25', 'MANILA', '0022992', '2025-06-06'),
('22021', 'RA 1080-LPT', '', '2023-03-23', 'CALAPAN CITY', '2000943', '2026-06-06'),
('22015', 'CIVIL SERVICE COMMISSION CAREER SERVICE PROFESSIONAL ELIGIBILITY', '80.13', '2012-05-27', 'J.J. LEIDO JR. MEMORIAL NATIONAL HIGH SCHOOL - CALAPAN CITY', '', '0000-00-00'),
('03033', 'Civil Service Professional Eligibility', '80.26', '1993-10-17', 'JJ Leido National High School', '', '0000-00-00');

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
('01097', 1.25, 1.25, 0, 0, 0),
('24018', 21.25, 1.25, 0, 0, 0),
('6013', 19.25, 1.25, 0, 0, 0);

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
('August 2025'),
('October 2025');

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
('24018', 'NO', 'YES', 'a', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('6013', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('22012', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('06007', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('24014', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('01097', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'YES', 'END OF TERM', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'YES', 'MARRIAGE DECLARED VOID AB INITIO'),
('22010', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('22025', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('24018', 'NO', 'YES', 'a', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('22024', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'YES', 'RESIGNATION', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('22021', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'YES', '17-5205-037-0001068', 'NO', ''),
('20004', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', ''),
('03033', 'NO', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '', 'NO', '');

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
('24018', 'asd', '123', 'qwe'),
('22012', 'Mr. POLICARPIO EDMUND B. ABAS, MDMG', 'Pachoca, Calapan City, Or. Mindoro', '09178050971'),
('22012', 'Engr. REDENTOR A. REYES, JR.', 'Lalud, Calapan City, Or. Mindoro', '288-1739'),
('22012', 'Dr. RENE M. COLOCAR', 'Calapan City', '288-7013'),
('01097', 'ATTY. HARVEY ECKER ZAMORA				', 'CALAPAN, ORIENTAL MINDORO', '+63 998 580 4645		'),
('01097', 'NERISSA GARCIA, LPT		', 'BONGABONG, ORIENTAL MINDORO', '+63 956 334 8279		'),
('01097', 'NOEL CIRUJANO				', 'CALAPAN, ORIENTAL MINDORO', '+63 977 810 8448		');

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
('24018', '2025-01-01', '0000-00-00', 'ASSISTANT PROFESSOR I', 'City College Of Calapan', '', '', 'permanent', 'Y'),
('24018', '2025-01-01', '2025-12-31', 'ASSISTANT PROFESSOR I', 'CCC', '', '', 'PERMANENT', 'Y'),
('24018', '2024-01-01', '2024-12-31', 'CCC', 'CCC', '', '', 'PERMANENT', 'Y'),
('24018', '2023-01-01', '2023-12-31', 'ccc', 'ccc', '', '', 'permanent', 'Y'),
('6013', '2025-01-12', '0000-00-00', 'Budget Officer I', 'CBD', '40,000.00', '15-1', 'PERMANENT', 'Y'),
('22012', '2006-09-15', '2010-02-28', 'Computer Operator', 'City Government of Calapan', '6200', 'N/A', 'Job Order', 'N'),
('22012', '2010-03-01', '2019-01-31', 'Administrative Aide II', 'City Human Resource Management Department', '9316', '2-4', 'Permanent', 'Y'),
('22012', '2019-02-01', '0000-00-00', 'Administrative Assistant I', 'City Human Resource Management Department', '15000', '7-1', 'Permanent', 'Y'),
('22012', '2022-01-03', '0000-00-00', 'Administrative Assistant IV', 'City Human Resource Management Department', '24000', '10-1', 'Permanent', 'Y'),
('24014', '2020-08-03', '2025-09-04', 'SUPERVISING ADMINISTRATIVE OFFICER		SUPERVISING ADMINISTRATIVE OFFICER		', 'CITY EDUCATION DEPARTMENT		', '78,000.00', 'SG-22 step 2', 'Permanent', 'Y'),
('24014', '2016-09-01', '2020-08-03', 'ASSOCIATE PROFESSOR I', 'CITY COLLEGE OF CALAPAN', '42,800.00', 'SG-19 step 1', 'Permanent', 'Y'),
('24014', '2012-02-15', '2016-01-09', 'ASSISTANT PROFESSOR I', 'CITY COLLEGE OF CALAPAN', '28,600.00', 'SG-15 step 1', 'Permanent', 'Y'),
('24014', '2001-06-01', '2011-02-15', 'FACULTY', 'DIVINE WORD COLLEGE OF CALAPAN', '17,500', '', 'Permanent', 'N'),
('01097', '2025-08-08', '2025-08-09', 'SUPERVISING LABOR AND EMPLOYMENT OFFICER, DESIGNATED AS: *OIC - CITY HUMAN RESOURCE MANAGEMENT OFFIC', 'CITY GOVERNMENT OF CALAPAN', 'P74,360.00', 'SG - 22', 'PERMANENT', 'Y'),
('01097', '2025-07-04', '2025-08-08', 'EXECUTIVE ASSISTANT IV, DESIGNATED AS: *OIC - CITY HUMAN RESOURCE MANAGEMENT OFFICE *OIC - CITY LEGA', 'CITY GOVERNMENT OF CALAPAN', 'P74,360.00', 'SG - 22', 'COTERMINUS', 'Y'),
('01097', '2022-06-30', '2025-06-30', 'ELECTED OFFICIAL - SANGGUNIANG PANLUNGSOD MEMBER, CALAPAN CITY', 'CITY GOVERNMENT OF CALAPAN', 'P93,695.00', 'SG 25 - 3', 'ELECTED', 'Y'),
('01097', '2019-06-30', '2022-06-30', 'ELECTED OFFICIAL - SANGGUNIANG PANLUNGSOD MEMBER, CALAPAN CITY', 'CITY GOVERNMENT OF CALAPAN', 'P86,972.00', 'SG 25 - 2', 'ELECTED', 'Y'),
('01097', '2016-06-30', '2019-06-30', 'ELECTED OFFICIAL - SANGGUNIANG PANLUNGSOD MEMBER, CALAPAN CITY', 'CITY GOVERNMENT OF CALAPAN', 'P54,774.00', 'SG 25 - 1 ', 'ELECTED', 'Y'),
('01097', '2009-06-01', '2015-10-15', 'ASSOCIATE PROFESSOR I, DESIGNATED AS: *PESO COORDINATOR - SCHOOL - BASED *CHAIRPERSON, COMMITTEE ON ', 'CITY COLLEGE OF CALAPAN', 'P30,473.00', 'SG 19', 'PERMANENT (PROVISIONAL)', 'Y'),
('01097', '2007-11-15', '2009-04-30', 'MARKETING SUPERVISOR, BSHRM INSTRUCTOR', 'MARKETING SUPERVISOR, BSHRM INSTRUCTOR', 'P12,000.00', 'N/A', 'CONTRACTUAL', 'N'),
('01097', '2007-08-01', '2007-10-30', 'BSHRM INSTRUCTOR', 'DIVINE WORD COLLEGE OF CALAPAN		', 'P12,500.00', 'N/A', 'CONTRACTUAL', 'N'),
('01097', '2025-08-10', '0000-00-00', 'College President', 'City Government', 'P90,000.00', 'n/a', 'permanent', 'y'),
('20004', '2023-12-01', '0000-00-00', 'Supervising Administrative Officer', 'City Government of Calapan', '', '22', 'Permanent', 'Y'),
('20004', '2014-09-18', '2023-11-30', 'Administrative Officer V', 'City Government of Calapan', '', '18', 'Permanent', 'Y'),
('20004', '2013-02-20', '2014-09-17', 'Administrative Officer IV', 'City Government of Calapan', '', '15', 'Permanent', 'Y'),
('20004', '2010-03-18', '2013-02-19', 'Project Development Assistant', 'City Government of Calapan', '', '8', 'Permanent', 'Y'),
('20004', '2004-02-02', '2010-03-17', 'Administrative Aide III', 'City Government of Calapan', '', '3', 'Permanent', 'Y'),
('24018', '2024-01-01', '2025-12-31', 'ASSISTANT PROFESSOR I', ' CCC', '36123', '15', 'PERNAMENT', 'Y'),
('24018', '1990-12-03', '2024-12-01', 'PROGRAMMER', 'PROGRAM', '100000', '25', 'CASUAL', 'N'),
('24018', '2025-09-11', '0000-00-00', 'ASSISTANT PROFESSOR I', 'ccc', '36123', '15', 'permanent', 'Y'),
('22025', '2024-02-01', '2025-09-14', 'Administrative Officer IV		', 'LGU Calapan City		', '?40,208.00', 'SG-15', 'PERMANENT', 'Y'),
('22025', '2022-07-01', '2024-01-31', 'Branch Supervisor II		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?14,262.04', 'N/A', 'REGULAR', 'N'),
('22025', '2019-03-01', '2022-06-30', 'Branch Supervisor I', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?11,273.60', 'N/A', 'REGULAR', 'N'),
('22025', '2018-01-01', '2019-02-28', 'Vault Custodian I		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?9,023.04', 'N/A', 'REGULAR', 'N'),
('22025', '2017-04-23', '2017-12-31', 'Branch Cashier I		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?7,852.00', 'N/A', 'REGULAR', 'N'),
('22025', '2016-06-11', '2017-04-22', 'Branch Cashier 		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?7,202.00', 'N/A', 'REGULAR', 'N'),
('22025', '2016-02-23', '2016-06-10', 'HR CLERK', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?7,202.00', 'N/A', 'REGULAR', 'N'),
('22025', '2015-04-28', '2015-07-21', 'Cash Section (On the Job Training)		', 'National Food Authority		', 'N/A', 'N/A', 'N/A', 'N'),
('22024', '2025-06-27', '0000-00-00', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '', '', 'PERMANENT', 'Y'),
('22024', '2025-01-01', '2025-06-26', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '16 209.00', '4', 'PERMANENT', 'Y'),
('22024', '2024-08-02', '2024-12-31', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '16 209.00', '4', 'PERMANENT', 'Y'),
('22024', '2023-01-01', '2023-08-01', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '14 027.00', '4', 'PERMANENT', 'Y'),
('22024', '2022-06-27', '2022-12-31', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '13 494.00', '4', 'PERMANENT', 'Y'),
('22024', '2021-09-22', '2022-06-26', 'FRONTLINE OFFICER', 'CITY GOVERNMENT OF CALAPAN', '6 600.00', 'N/A', 'JOB ORDER', 'N'),
('22024', '2019-02-16', '2021-09-18', 'CREDIT AND COLLECTION STAFF', 'GOOD HEART MARKETING INC.', '12 000.00', 'N/A', 'REGULAR', 'N'),
('22024', '2018-11-15', '2019-01-20', 'TICKETING CLERK', 'NPMSI', '8 400.00', 'N/A', 'CONTRACTUAL', 'N'),
('22021', '2020-01-20', '2022-02-15', 'GUIDANCE COUNSELOR I', 'MINDORO STATE UNIVERSITY, CALAPAN CAMPUS-GUIDANCE OFFICE', '', '', 'COS', 'N'),
('22021', '2022-02-16', '0000-00-00', 'ADMINISTRATIVE OFFICER III', 'CITY GOVERNMENT OF CALAPAN, CITY HUMAN RESOURCE MANAGEMENT DEPARTMENT', '37,024.00', '14-2', 'PERMANENT', 'Y'),
('22015', '2021-10-18', '0000-00-00', 'ADMINISTRATIVE ASSISTANT II (HRM ASSISTANT)', 'CITY HUMAN RESOURCE MANAGEMENT DEPARTMENT', '', '', 'PERMANENT', 'Y'),
('03033', '2024-01-16', '2025-10-02', 'Administrative Officer V (HRMO III)', 'CHRMD, City Government of Calapan', '', '', 'Permanent', 'Y'),
('03033', '2018-07-02', '2024-01-15', 'Administrative Officer IV (HRMO II)', 'CHRMD, City Government of Calapan', '33,297.00', 'SG 15-2', 'Permanent', 'Y'),
('03033', '2016-09-16', '2018-07-01', 'Administrative Officer II (HRMO I)', 'CHRMD, City Government of Calapan', '17,169.00', 'SG 11-1', 'Permanent', 'Y'),
('03033', '2011-11-08', '2016-09-15', 'Zoning Inspector II', 'CHUSD, City Government of Calapan', '13,981.00', 'SG 8-2', 'Permanent', 'Y'),
('03033', '2007-03-28', '2011-11-07', 'Administrative Aide IV (HRM Aide)', 'CHRMD, City Government of Calapan', '8,721.00', 'SG 4-2', 'Permanent', 'Y'),
('03033', '1999-10-04', '2007-03-27', 'Casual Employee', 'CEPWD, City Government of Calapan', '5,363.82', '', 'Non-Permanent', 'Y'),
('03033', '1998-07-02', '1999-09-15', 'Cashier/Bookkeeper', 'Gliceria L. Concepcion Marketing', '5,000.00', '', 'Contractual', 'N'),
('03033', '1993-08-31', '1998-04-15', 'Sales Officer', 'Naujan Multi-Purpose Cooperative', '4,390.00', '', 'Regular', 'N'),
('03033', '1991-08-01', '1993-08-30', 'Accounting Clerk', 'Naujan Multi-Purpose Cooperative', '3,790.00', '', 'Regular', 'N'),
('22012', '2006-09-15', '2010-02-28', 'Computer Operator', 'City Government of Calapan', '6200', 'N/A', 'Job Order', 'N'),
('22012', '2010-03-01', '2019-01-31', 'Administrative Aide II', 'City Human Resource Management Department', '9316', '2-4', 'Permanent', 'Y'),
('22012', '2019-02-01', '0000-00-00', 'Administrative Assistant I', 'City Human Resource Management Department', '15000', '7-1', 'Permanent', 'Y'),
('22012', '2022-01-03', '0000-00-00', 'Administrative Assistant IV', 'City Human Resource Management Department', '24000', '10-1', 'Permanent', 'Y'),
('24014', '2020-08-03', '2025-09-04', 'SUPERVISING ADMINISTRATIVE OFFICER		SUPERVISING ADMINISTRATIVE OFFICER		', 'CITY EDUCATION DEPARTMENT		', '78,000.00', 'SG-22 step 2', 'Permanent', 'Y'),
('24014', '2016-09-01', '2020-08-03', 'ASSOCIATE PROFESSOR I', 'CITY COLLEGE OF CALAPAN', '42,800.00', 'SG-19 step 1', 'Permanent', 'Y'),
('24014', '2012-02-15', '2016-01-09', 'ASSISTANT PROFESSOR I', 'CITY COLLEGE OF CALAPAN', '28,600.00', 'SG-15 step 1', 'Permanent', 'Y'),
('24014', '2001-06-01', '2011-02-15', 'FACULTY', 'DIVINE WORD COLLEGE OF CALAPAN', '17,500', '', 'Permanent', 'N'),
('01097', '2025-08-08', '2025-08-09', 'SUPERVISING LABOR AND EMPLOYMENT OFFICER, DESIGNATED AS: *OIC - CITY HUMAN RESOURCE MANAGEMENT OFFIC', 'CITY GOVERNMENT OF CALAPAN', 'P74,360.00', 'SG - 22', 'PERMANENT', 'Y'),
('01097', '2025-07-04', '2025-08-08', 'EXECUTIVE ASSISTANT IV, DESIGNATED AS: *OIC - CITY HUMAN RESOURCE MANAGEMENT OFFICE *OIC - CITY LEGA', 'CITY GOVERNMENT OF CALAPAN', 'P74,360.00', 'SG - 22', 'COTERMINUS', 'Y'),
('01097', '2022-06-30', '2025-06-30', 'ELECTED OFFICIAL - SANGGUNIANG PANLUNGSOD MEMBER, CALAPAN CITY', 'CITY GOVERNMENT OF CALAPAN', 'P93,695.00', 'SG 25 - 3', 'ELECTED', 'Y'),
('01097', '2019-06-30', '2022-06-30', 'ELECTED OFFICIAL - SANGGUNIANG PANLUNGSOD MEMBER, CALAPAN CITY', 'CITY GOVERNMENT OF CALAPAN', 'P86,972.00', 'SG 25 - 2', 'ELECTED', 'Y'),
('01097', '2016-06-30', '2019-06-30', 'ELECTED OFFICIAL - SANGGUNIANG PANLUNGSOD MEMBER, CALAPAN CITY', 'CITY GOVERNMENT OF CALAPAN', 'P54,774.00', 'SG 25 - 1 ', 'ELECTED', 'Y'),
('01097', '2009-06-01', '2015-10-15', 'ASSOCIATE PROFESSOR I, DESIGNATED AS: *PESO COORDINATOR - SCHOOL - BASED *CHAIRPERSON, COMMITTEE ON ', 'CITY COLLEGE OF CALAPAN', 'P30,473.00', 'SG 19', 'PERMANENT (PROVISIONAL)', 'Y'),
('01097', '2007-11-15', '2009-04-30', 'MARKETING SUPERVISOR, BSHRM INSTRUCTOR', 'MARKETING SUPERVISOR, BSHRM INSTRUCTOR', 'P12,000.00', 'N/A', 'CONTRACTUAL', 'N'),
('01097', '2007-08-01', '2007-10-30', 'BSHRM INSTRUCTOR', 'DIVINE WORD COLLEGE OF CALAPAN		', 'P12,500.00', 'N/A', 'CONTRACTUAL', 'N'),
('01097', '2025-08-10', '0000-00-00', 'College President', 'City Government', 'P90,000.00', 'n/a', 'permanent', 'y'),
('20004', '2023-12-01', '0000-00-00', 'Supervising Administrative Officer', 'City Government of Calapan', '', '22', 'Permanent', 'Y'),
('20004', '2014-09-18', '2023-11-30', 'Administrative Officer V', 'City Government of Calapan', '', '18', 'Permanent', 'Y'),
('20004', '2013-02-20', '2014-09-17', 'Administrative Officer IV', 'City Government of Calapan', '', '15', 'Permanent', 'Y'),
('20004', '2010-03-18', '2013-02-19', 'Project Development Assistant', 'City Government of Calapan', '', '8', 'Permanent', 'Y'),
('20004', '2004-02-02', '2010-03-17', 'Administrative Aide III', 'City Government of Calapan', '', '3', 'Permanent', 'Y'),
('24018', '2024-01-01', '2025-12-31', 'ASSISTANT PROFESSOR I', ' CCC', '36123', '15', 'PERNAMENT', 'Y'),
('24018', '1990-12-03', '2024-12-01', 'PROGRAMMER', 'PROGRAM', '100000', '25', 'CASUAL', 'N'),
('24018', '2025-09-11', '0000-00-00', 'ASSISTANT PROFESSOR I', 'ccc', '36123', '15', 'permanent', 'Y'),
('22025', '2024-02-01', '2025-09-14', 'Administrative Officer IV		', 'LGU Calapan City		', '?40,208.00', 'SG-15', 'PERMANENT', 'Y'),
('22025', '2022-07-01', '2024-01-31', 'Branch Supervisor II		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?14,262.04', 'N/A', 'REGULAR', 'N'),
('22025', '2019-03-01', '2022-06-30', 'Branch Supervisor I', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?11,273.60', 'N/A', 'REGULAR', 'N'),
('22025', '2018-01-01', '2019-02-28', 'Vault Custodian I		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?9,023.04', 'N/A', 'REGULAR', 'N'),
('22025', '2017-04-23', '2017-12-31', 'Branch Cashier I		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?7,852.00', 'N/A', 'REGULAR', 'N'),
('22025', '2016-06-11', '2017-04-22', 'Branch Cashier 		', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?7,202.00', 'N/A', 'REGULAR', 'N'),
('22025', '2016-02-23', '2016-06-10', 'HR CLERK', 'D.A. Martinez Pawnshop (MINDORO), Inc.		', '?7,202.00', 'N/A', 'REGULAR', 'N'),
('22025', '2015-04-28', '2015-07-21', 'Cash Section (On the Job Training)		', 'National Food Authority		', 'N/A', 'N/A', 'N/A', 'N'),
('22024', '2025-06-27', '0000-00-00', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '', '', 'PERMANENT', 'Y'),
('22024', '2025-01-01', '2025-06-26', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '16 209.00', '4', 'PERMANENT', 'Y'),
('22024', '2024-08-02', '2024-12-31', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '16 209.00', '4', 'PERMANENT', 'Y'),
('22024', '2023-01-01', '2023-08-01', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '14 027.00', '4', 'PERMANENT', 'Y'),
('22024', '2022-06-27', '2022-12-31', 'ADMINISTRATIVE AIDE IV', 'CITY GOVERNMENT OF CALAPAN', '13 494.00', '4', 'PERMANENT', 'Y'),
('22024', '2021-09-22', '2022-06-26', 'FRONTLINE OFFICER', 'CITY GOVERNMENT OF CALAPAN', '6 600.00', 'N/A', 'JOB ORDER', 'N'),
('22024', '2019-02-16', '2021-09-18', 'CREDIT AND COLLECTION STAFF', 'GOOD HEART MARKETING INC.', '12 000.00', 'N/A', 'REGULAR', 'N'),
('22024', '2018-11-15', '2019-01-20', 'TICKETING CLERK', 'NPMSI', '8 400.00', 'N/A', 'CONTRACTUAL', 'N'),
('22021', '2020-01-20', '2022-02-15', 'GUIDANCE COUNSELOR I', 'MINDORO STATE UNIVERSITY, CALAPAN CAMPUS-GUIDANCE OFFICE', '', '', 'COS', 'N'),
('22021', '2022-02-16', '0000-00-00', 'ADMINISTRATIVE OFFICER III', 'CITY GOVERNMENT OF CALAPAN, CITY HUMAN RESOURCE MANAGEMENT DEPARTMENT', '37,024.00', '14-2', 'PERMANENT', 'Y'),
('22015', '2021-10-18', '0000-00-00', 'ADMINISTRATIVE ASSISTANT II (HRM ASSISTANT)', 'CITY HUMAN RESOURCE MANAGEMENT DEPARTMENT', '', '', 'PERMANENT', 'Y'),
('03033', '2024-01-16', '2025-10-02', 'Administrative Officer V (HRMO III)', 'CHRMD, City Government of Calapan', '', '', 'Permanent', 'Y'),
('03033', '2018-07-02', '2024-01-15', 'Administrative Officer IV (HRMO II)', 'CHRMD, City Government of Calapan', '33,297.00', 'SG 15-2', 'Permanent', 'Y'),
('03033', '2016-09-16', '2018-07-01', 'Administrative Officer II (HRMO I)', 'CHRMD, City Government of Calapan', '17,169.00', 'SG 11-1', 'Permanent', 'Y'),
('03033', '2011-11-08', '2016-09-15', 'Zoning Inspector II', 'CHUSD, City Government of Calapan', '13,981.00', 'SG 8-2', 'Permanent', 'Y'),
('03033', '2007-03-28', '2011-11-07', 'Administrative Aide IV (HRM Aide)', 'CHRMD, City Government of Calapan', '8,721.00', 'SG 4-2', 'Permanent', 'Y'),
('03033', '1999-10-04', '2007-03-27', 'Casual Employee', 'CEPWD, City Government of Calapan', '5,363.82', '', 'Non-Permanent', 'Y'),
('03033', '1998-07-02', '1999-09-15', 'Cashier/Bookkeeper', 'Gliceria L. Concepcion Marketing', '5,000.00', '', 'Contractual', 'N'),
('03033', '1993-08-31', '1998-04-15', 'Sales Officer', 'Naujan Multi-Purpose Cooperative', '4,390.00', '', 'Regular', 'N'),
('03033', '1991-08-01', '1993-08-30', 'Accounting Clerk', 'Naujan Multi-Purpose Cooperative', '3,790.00', '', 'Regular', 'N');

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
('24018', 'CCC', '0000-00-00', '0000-00-00', '', ''),
('01097', 'COLLEGE OF LAW ALUMNI ASSOCIATION - BATANGAS STATE UNIVERSITY	', '2025-07-22', '0000-00-00', 'N/A', 'MEMBER			'),
('01097', 'ASSOCIATION OF LAW STUDENTS OF THE PHILIPPINES 			', '2019-08-25', '2025-07-22', 'N/A', 'MEMBER	'),
('01097', 'PHILIPPINE COUNCILORS LEAGUE - MIMAROPA COUNCIL', '2016-09-25', '2025-06-30', 'N/A', 'MEMBER (2022 - 2025); VICE PRESIDENT (2019 - 2022);  SECRETARY GENERAL (2016 - 2019)'),
('01097', 'PHILIPPINE WOMENS UNIVERSITY ALUMNI ASSOCIATION			', '2007-08-25', '0000-00-00', 'N/A', 'MEMBER'),
('01097', 'PHILIPPINE COUNCILORS LEAGUE - ORIENTAL MINDORO 			', '2016-07-25', '2025-06-30', 'N/A', 'MEMBER'),
('01097', 'PHILIPPINE ASSOCIATION FOR TEACHER EDUCATION (PAFTE) 			', '2013-09-25', '0000-00-00', 'N/A', 'MEMBER'),
('22025', 'N/A', '0000-00-00', '0000-00-00', 'N/A', 'N/A'),
('24018', 'CCC', '0000-00-00', '0000-00-00', '', ''),
('22024', 'NATIONAL POLICE COMMISSION MIMAROPA', '2025-04-27', '2025-04-27', '4', 'PROCTOR'),
('20004', 'N/A', '0000-00-00', '0000-00-00', 'N/A', 'N/A'),
('03033', 'Naujan Multi-Purpose Cooperative', '0000-00-00', '0000-00-00', '', 'Member'),
('01097', 'COLLEGE OF LAW ALUMNI ASSOCIATION - BATANGAS STATE UNIVERSITY	', '2025-07-22', '0000-00-00', 'N/A', 'MEMBER			'),
('01097', 'ASSOCIATION OF LAW STUDENTS OF THE PHILIPPINES 			', '2019-08-25', '2025-07-22', 'N/A', 'MEMBER	'),
('01097', 'PHILIPPINE COUNCILORS LEAGUE - MIMAROPA COUNCIL', '2016-09-25', '2025-06-30', 'N/A', 'MEMBER (2022 - 2025); VICE PRESIDENT (2019 - 2022);  SECRETARY GENERAL (2016 - 2019)'),
('01097', 'PHILIPPINE WOMENS UNIVERSITY ALUMNI ASSOCIATION			', '2007-08-25', '0000-00-00', 'N/A', 'MEMBER'),
('01097', 'PHILIPPINE COUNCILORS LEAGUE - ORIENTAL MINDORO 			', '2016-07-25', '2025-06-30', 'N/A', 'MEMBER'),
('01097', 'PHILIPPINE ASSOCIATION FOR TEACHER EDUCATION (PAFTE) 			', '2013-09-25', '0000-00-00', 'N/A', 'MEMBER'),
('22025', 'N/A', '0000-00-00', '0000-00-00', 'N/A', 'N/A'),
('24018', 'CCC', '0000-00-00', '0000-00-00', '', ''),
('22024', 'NATIONAL POLICE COMMISSION MIMAROPA', '2025-04-27', '2025-04-27', '4', 'PROCTOR'),
('20004', 'N/A', '0000-00-00', '0000-00-00', 'N/A', 'N/A'),
('03033', 'Naujan Multi-Purpose Cooperative', '0000-00-00', '0000-00-00', '', 'Member');

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
('24018', 'CCC', '2024-01-01', '2024-01-01', '8', 'TECHNICAL', 'CCC'),
('24018', 'CCC', '2023-01-01', '2023-01-01', '8', 'TECHNICAL', 'CCC'),
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
('22012', 'CHRMD: Orientation Seminar on Anti-Red Tape Act (ARTA) Reengineering and Citizen\'s Charter Workshop', '2024-07-25', '2025-07-25', '8', 'Administartive', 'Regional Anti-Red Tape Authority'),
('22012', '4th Quarter 2019 Nationwide Simultaneous Earthquake Drill (NSED)', '2019-11-14', '2019-11-14', '8', 'Emergency Response', 'Office of the Civil Defense'),
('22012', 'Discipline and Good Charcater Approach: Serbisyong Nagbibigay Galak', '2019-09-12', '2019-09-13', '16', 'Technical', 'City Government of Calapan'),
('22012', '26th Annual Regional Conference of Human Resource Management Practitioners', '2019-03-26', '2019-03-29', '24', 'Managerial', 'Civil Service Commission'),
('22025', 'Seminar entitled ', '2023-06-18', '2023-06-18', '8', 'Technical', 'D.A. Martinez Pawnshop (Mindoro), Inc.		'),
('22025', 'Supervisory Development Course Track I', '2024-08-27', '2024-08-30', '32', 'Supervisory', 'Civil Service Commission'),
('22025', 'Stress Management and Mental Health Awareness', '2024-09-24', '2024-09-24', '4', 'Technical', 'City Human Resource Management Department'),
('22025', 'Supervisory Development Course Track II', '2025-05-27', '2025-05-30', '32', 'Supervisory', 'Civil Service Commission'),
('24018', 'CCC', '2024-01-01', '2024-01-01', '8', 'TECHNICAL', 'CCC'),
('22024', 'UPDATING OF CALAPAN CITY COMPREHENSIVE DEVELOPMENT PLAN CY 2026-2031', '2025-09-10', '2025-09-10', '24', 'TECHNICAL', 'URBAN PLANNING AND DEVELOPMENT DEPARTMENT'),
('22024', 'RECOGNIZE TO ENERGIZE: ENHANCING R&R PROGRAMS FOR HIGHER ENGAGEMENT', '2025-08-18', '2025-08-18', '8', 'TECHNICAL', 'CIVIL SERVICE COMMISSION'),
('22024', 'UPDATING OF CALAPAN CITY COMPREHENSIVE DEVELOPMENT PLAN CY 2026-2031', '2025-08-15', '2025-08-15', '8', 'TECHNICAL', 'URBAN PLANNING AND DEVELOPMENT DEPARTMENT'),
('22024', 'CONTACT CENTER NG BAYAN VIRTUAL ORIENTATION FOR BILIS AKSYON PARTNERS AND FOCAL PERSONS', '2025-04-24', '2025-04-24', '2', 'TECHNICAL', 'CIVIL SERVICE COMMISSION'),
('22024', 'ONLINE GENERAL ORIENTATION ON R.A. 11032 OR THE EASE OF DOING BUSINESS AND EFFIVIENT GOVERNMENT SERVICE DELIVERY ACT OF 2018 AND OTHER DATA COMPLIANCES ', '2024-11-14', '2024-11-14', '8', 'TECHNICAL', 'COMPLIANCE MONITORING AND EVALUATION OFFICE OF ANTI-RED TAPE AUTHORITY'),
('22024', 'STRESS MANAGEMENT AND MENTAL HEALTH AWARENESS', '2024-09-04', '2024-09-04', '4', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN'),
('22024', 'REENGINEERING AND CITIZEN\'S CHARTER WORKSHOP REPUBLIC ACT NO.11032: AN ACT PROMOTING EASE OF DOING BUSINESS AND EFFICIENT DELIVERY OF GOVERNMENT SERVICES', '2024-07-25', '2024-07-25', '8', 'TECHNICAL', 'ANTI RED TAPE AUTHORITY, SOUTHERN LUZON FIELD OFFICE'),
('22024', 'BASIC SIGN LANGUAGE REFRESHER COURSE ', '2023-11-17', '2023-11-17', '8', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN/ PERSONS WITH DISABILITY AFFAIRS OFFICE'),
('22024', '3RD LEVEL TRAINING ON COMMUNITY-BASED MONITORING SYSTEM DATA PROCESSING', '2023-10-03', '2023-10-06', '32', 'TECHNICAL', 'PHILIPPINE STATISTICS AUTHORITY'),
('22024', 'PAGLILINANG PARA SA PAG-UNLAD NG SARILI', '2023-09-14', '2023-09-14', '8', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN'),
('22024', 'COMMUNITY-BASED MONITORING SYSTEM', '2023-06-22', '2023-06-30', '64', 'TECHNICAL', 'PHILIPPINE STATISTICS AUTHORITY'),
('22024', 'BASIC SIGN LANGUAGE SEMINAR', '2022-06-25', '2022-06-26', '16', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN/ PERSONS WITH DISABILITY AFFAIRS OFFICE'),
('22024', 'CGC EMPLOYEES ORIENTATION on R.A. 11313 or \"SAFE SPACES ACT\"', '2022-04-29', '2025-04-29', '8', 'SOFT SKILL', 'DEPARTMENT OF INTERIOR AND LOCAL GOVERNMENT'),
('20004', 'Effective Method and Advice in Processing and Organizing Files in Public Office', '2022-10-03', '2022-10-05', '24 hours', 'Supervisory', 'Government Records Officers\' Association of the Philippines, Inc. (GROAP)'),
('20004', 'Webinar on the Amendment to Section 4 of the 2017 Omnibus Rules on Appointments and Other Human Resource Actions (ORAOHRA), as Amended and on the Civil Service Eligibility Verification System (CSEVS) ', '2023-07-26', '2023-07-26', '3 hours', 'Foundational', 'CSC - Civil Service Institute'),
('20004', '29th Regional Conference of Human Resource Management Practitioners (RCHRMPs)', '2024-06-18', '2024-06-20', '16 hours', 'Leadership', 'Civil Service Commission Regional Office IV'),
('20004', ' Reengineering and Citizen\'s Charter Workshop', '2024-07-25', '2024-07-25', '8 hours', 'Technical', 'ARTA Southern Luzon Regional Field Office and City Government of Calapan'),
('20004', 'Online General Orientation on R.A. 11032 or the Ease of Doing Business and Efficient Government Service Delivery Act of 2018 and Other ARTA Compliances', '2024-11-14', '2024-11-14', '8 hours', 'Technical', 'ARTA - Compliance Monitoring and Evaluation Office'),
('20004', 'Statement of Assets, Liabilities and Net Worth (SALN) Overview (Batch 3)  ', '2025-02-05', '2025-02-05', '3 hours', 'Leadership/Management', 'CSC - Civil Service Institute'),
('20004', 'Webinar Orientation on the 2025 Omnibus Rules on Appointments and Other Human Resource Actions (ORAOHRA)', '2025-08-06', '2025-08-07', '10 hours', 'Foundation/ Leadership and Management', 'CSC - Civil Service Institute'),
('20004', 'Orientation, Assessment and Action Planning on PRIME-HRM Maturity Level II for Learning and Development', '2025-08-13', '2025-08-14', '16 hours', 'Technical', 'Civil Service Commission Regional Office IV'),
('03033', 'Handholding Session on Strategic Performance Management System', '2012-08-08', '2012-08-10', '24', 'Technical', 'Civil Service Commission'),
('03033', 'Trainor\'s Training on Registry System on Basic Sectors in Agriculture (RSBSA)', '2012-08-27', '2012-08-30', '24', 'Technical', 'NSO, DBM, DILG'),
('03033', 'Training on Sustainable Integrated Management & Planning for Local Ecosystem (SIMPLE)', '2012-11-05', '2012-11-20', '80', 'Technical', 'German International Cooperation (GIZ)/ Adoption to Climate Change in Coastal Areas'),
('03033', 'Competency Training of Technical Advisers/Trainers for the Magna Carta of Women Project', '2013-12-05', '2013-12-16', '16', 'Technical', 'Philippine Commission on Women/Provincial Government of Oriental Mindoro'),
('03033', 'Introduction to ISO 9001:2008: Understanding and Implementing its Requirements', '2013-12-10', '2013-12-11', '16', 'Technical/Quality', 'Certification International Philippines, Incorporated'),
('03033', 'Effective Internal Auditing to ISO 9001:2008', '2013-12-12', '2013-12-13', '16', 'Technical/Quality', 'Certification International Philippines, Incorporated'),
('03033', 'Training on World Health Organization\'s Community Based Rehabilitation Guidelines', '2014-01-09', '2014-01-10', '16', 'Soft/Technical', 'NORFIL Foundation Inc./Provincial Government of Oriental Mindoro'),
('03033', 'Orientation & Training Program for CBMS 2nd Round Survey Enumerator', '2014-09-09', '2014-09-10', '16', 'Technical', 'Provincial Planning Office'),
('03033', 'Character Enhancement Seminar (Values at Work)', '0205-03-11', '2015-03-12', '16', 'Soft Skills', 'City Government of Calapan'),
('03033', 'Training of Technical Advisers on GAD w/ HGDG', '2015-05-11', '2015-05-13', '24', 'Technical', 'Provincial Social Welfare & Development Office'),
('03033', 'GAD Orientation on Planning & Budgeting', '2015-07-07', '2015-07-09', '24', 'Technical', 'City Government of Calapan'),
('03033', 'ASIA Pacific Housing Forum 5', '2015-09-03', '2015-09-04', '16', 'Technical/Legal', 'Habitat for Humanity International, Manila'),
('03033', 'Formulation of Local Shelter Plan for Local Government Unit', '2015-09-08', '2015-09-11', '32', 'Technical', 'Housing & Urban Development Coordinating Council'),
('03033', 'Writeshop on Local Shelter Plan Formulation for LGU', '2015-11-17', '2015-11-19', '24', 'Technical', 'Housing & Urban Development Coordinating Council'),
('03033', 'Training on Gender Analysis & Gender Responsive Planning & Budgeting', '2016-06-22', '2016-06-24', '24', 'Technical', 'City Government of Calapan'),
('03033', 'Final National Consultation and Validation Workshop on Adaptation to Climate Change-Coastal Cities at Risk', '2016-07-20', '2016-07-20', '8', 'Safety/Technical', 'Ateneo de Manila University, Manila Observatory'),
('03033', 'Workshop on GAD Planning & Budgeting', '2016-07-25', '2016-07-26', '16', 'Technical', 'City Government of Calapan'),
('03033', 'Workshop on Establishment of GAD Data Base ', '2016-08-04', '2016-08-05', '16', 'Technical', 'City Government of Calapan'),
('22012', 'CHRMD: Orientation Seminar on Anti-Red Tape Act (ARTA) Reengineering and Citizen\'s Charter Workshop', '2024-07-25', '2025-07-25', '8', 'Administartive', 'Regional Anti-Red Tape Authority'),
('22012', '4th Quarter 2019 Nationwide Simultaneous Earthquake Drill (NSED)', '2019-11-14', '2019-11-14', '8', 'Emergency Response', 'Office of the Civil Defense'),
('22012', 'Discipline and Good Charcater Approach: Serbisyong Nagbibigay Galak', '2019-09-12', '2019-09-13', '16', 'Technical', 'City Government of Calapan'),
('22012', '26th Annual Regional Conference of Human Resource Management Practitioners', '2019-03-26', '2019-03-29', '24', 'Managerial', 'Civil Service Commission'),
('22025', 'Seminar entitled ', '2023-06-18', '2023-06-18', '8', 'Technical', 'D.A. Martinez Pawnshop (Mindoro), Inc.		'),
('22025', 'Supervisory Development Course Track I', '2024-08-27', '2024-08-30', '32', 'Supervisory', 'Civil Service Commission'),
('22025', 'Stress Management and Mental Health Awareness', '2024-09-24', '2024-09-24', '4', 'Technical', 'City Human Resource Management Department'),
('22025', 'Supervisory Development Course Track II', '2025-05-27', '2025-05-30', '32', 'Supervisory', 'Civil Service Commission'),
('24018', 'CCC', '2024-01-01', '2024-01-01', '8', 'TECHNICAL', 'CCC'),
('22024', 'UPDATING OF CALAPAN CITY COMPREHENSIVE DEVELOPMENT PLAN CY 2026-2031', '2025-09-10', '2025-09-10', '24', 'TECHNICAL', 'URBAN PLANNING AND DEVELOPMENT DEPARTMENT'),
('22024', 'RECOGNIZE TO ENERGIZE: ENHANCING R&R PROGRAMS FOR HIGHER ENGAGEMENT', '2025-08-18', '2025-08-18', '8', 'TECHNICAL', 'CIVIL SERVICE COMMISSION'),
('22024', 'UPDATING OF CALAPAN CITY COMPREHENSIVE DEVELOPMENT PLAN CY 2026-2031', '2025-08-15', '2025-08-15', '8', 'TECHNICAL', 'URBAN PLANNING AND DEVELOPMENT DEPARTMENT'),
('22024', 'CONTACT CENTER NG BAYAN VIRTUAL ORIENTATION FOR BILIS AKSYON PARTNERS AND FOCAL PERSONS', '2025-04-24', '2025-04-24', '2', 'TECHNICAL', 'CIVIL SERVICE COMMISSION'),
('22024', 'ONLINE GENERAL ORIENTATION ON R.A. 11032 OR THE EASE OF DOING BUSINESS AND EFFIVIENT GOVERNMENT SERVICE DELIVERY ACT OF 2018 AND OTHER DATA COMPLIANCES ', '2024-11-14', '2024-11-14', '8', 'TECHNICAL', 'COMPLIANCE MONITORING AND EVALUATION OFFICE OF ANTI-RED TAPE AUTHORITY'),
('22024', 'STRESS MANAGEMENT AND MENTAL HEALTH AWARENESS', '2024-09-04', '2024-09-04', '4', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN'),
('22024', 'REENGINEERING AND CITIZEN\'S CHARTER WORKSHOP REPUBLIC ACT NO.11032: AN ACT PROMOTING EASE OF DOING BUSINESS AND EFFICIENT DELIVERY OF GOVERNMENT SERVICES', '2024-07-25', '2024-07-25', '8', 'TECHNICAL', 'ANTI RED TAPE AUTHORITY, SOUTHERN LUZON FIELD OFFICE'),
('22024', 'BASIC SIGN LANGUAGE REFRESHER COURSE ', '2023-11-17', '2023-11-17', '8', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN/ PERSONS WITH DISABILITY AFFAIRS OFFICE'),
('22024', '3RD LEVEL TRAINING ON COMMUNITY-BASED MONITORING SYSTEM DATA PROCESSING', '2023-10-03', '2023-10-06', '32', 'TECHNICAL', 'PHILIPPINE STATISTICS AUTHORITY'),
('22024', 'PAGLILINANG PARA SA PAG-UNLAD NG SARILI', '2023-09-14', '2023-09-14', '8', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN'),
('22024', 'COMMUNITY-BASED MONITORING SYSTEM', '2023-06-22', '2023-06-30', '64', 'TECHNICAL', 'PHILIPPINE STATISTICS AUTHORITY'),
('22024', 'BASIC SIGN LANGUAGE SEMINAR', '2022-06-25', '2022-06-26', '16', 'SOFT SKILL', 'CITY GOVERNMENT OF CALAPAN/ PERSONS WITH DISABILITY AFFAIRS OFFICE'),
('22024', 'CGC EMPLOYEES ORIENTATION on R.A. 11313 or \"SAFE SPACES ACT\"', '2022-04-29', '2025-04-29', '8', 'SOFT SKILL', 'DEPARTMENT OF INTERIOR AND LOCAL GOVERNMENT'),
('20004', 'Effective Method and Advice in Processing and Organizing Files in Public Office', '2022-10-03', '2022-10-05', '24 hours', 'Supervisory', 'Government Records Officers\' Association of the Philippines, Inc. (GROAP)'),
('20004', 'Webinar on the Amendment to Section 4 of the 2017 Omnibus Rules on Appointments and Other Human Resource Actions (ORAOHRA), as Amended and on the Civil Service Eligibility Verification System (CSEVS) ', '2023-07-26', '2023-07-26', '3 hours', 'Foundational', 'CSC - Civil Service Institute'),
('20004', '29th Regional Conference of Human Resource Management Practitioners (RCHRMPs)', '2024-06-18', '2024-06-20', '16 hours', 'Leadership', 'Civil Service Commission Regional Office IV'),
('20004', ' Reengineering and Citizen\'s Charter Workshop', '2024-07-25', '2024-07-25', '8 hours', 'Technical', 'ARTA Southern Luzon Regional Field Office and City Government of Calapan'),
('20004', 'Online General Orientation on R.A. 11032 or the Ease of Doing Business and Efficient Government Service Delivery Act of 2018 and Other ARTA Compliances', '2024-11-14', '2024-11-14', '8 hours', 'Technical', 'ARTA - Compliance Monitoring and Evaluation Office'),
('20004', 'Statement of Assets, Liabilities and Net Worth (SALN) Overview (Batch 3)  ', '2025-02-05', '2025-02-05', '3 hours', 'Leadership/Management', 'CSC - Civil Service Institute'),
('20004', 'Webinar Orientation on the 2025 Omnibus Rules on Appointments and Other Human Resource Actions (ORAOHRA)', '2025-08-06', '2025-08-07', '10 hours', 'Foundation/ Leadership and Management', 'CSC - Civil Service Institute'),
('20004', 'Orientation, Assessment and Action Planning on PRIME-HRM Maturity Level II for Learning and Development', '2025-08-13', '2025-08-14', '16 hours', 'Technical', 'Civil Service Commission Regional Office IV'),
('03033', 'Handholding Session on Strategic Performance Management System', '2012-08-08', '2012-08-10', '24', 'Technical', 'Civil Service Commission'),
('03033', 'Trainor\'s Training on Registry System on Basic Sectors in Agriculture (RSBSA)', '2012-08-27', '2012-08-30', '24', 'Technical', 'NSO, DBM, DILG'),
('03033', 'Training on Sustainable Integrated Management & Planning for Local Ecosystem (SIMPLE)', '2012-11-05', '2012-11-20', '80', 'Technical', 'German International Cooperation (GIZ)/ Adoption to Climate Change in Coastal Areas'),
('03033', 'Competency Training of Technical Advisers/Trainers for the Magna Carta of Women Project', '2013-12-05', '2013-12-16', '16', 'Technical', 'Philippine Commission on Women/Provincial Government of Oriental Mindoro'),
('03033', 'Introduction to ISO 9001:2008: Understanding and Implementing its Requirements', '2013-12-10', '2013-12-11', '16', 'Technical/Quality', 'Certification International Philippines, Incorporated'),
('03033', 'Effective Internal Auditing to ISO 9001:2008', '2013-12-12', '2013-12-13', '16', 'Technical/Quality', 'Certification International Philippines, Incorporated'),
('03033', 'Training on World Health Organization\'s Community Based Rehabilitation Guidelines', '2014-01-09', '2014-01-10', '16', 'Soft/Technical', 'NORFIL Foundation Inc./Provincial Government of Oriental Mindoro'),
('03033', 'Orientation & Training Program for CBMS 2nd Round Survey Enumerator', '2014-09-09', '2014-09-10', '16', 'Technical', 'Provincial Planning Office'),
('03033', 'Character Enhancement Seminar (Values at Work)', '0205-03-11', '2015-03-12', '16', 'Soft Skills', 'City Government of Calapan'),
('03033', 'Training of Technical Advisers on GAD w/ HGDG', '2015-05-11', '2015-05-13', '24', 'Technical', 'Provincial Social Welfare & Development Office'),
('03033', 'GAD Orientation on Planning & Budgeting', '2015-07-07', '2015-07-09', '24', 'Technical', 'City Government of Calapan'),
('03033', 'ASIA Pacific Housing Forum 5', '2015-09-03', '2015-09-04', '16', 'Technical/Legal', 'Habitat for Humanity International, Manila'),
('03033', 'Formulation of Local Shelter Plan for Local Government Unit', '2015-09-08', '2015-09-11', '32', 'Technical', 'Housing & Urban Development Coordinating Council'),
('03033', 'Writeshop on Local Shelter Plan Formulation for LGU', '2015-11-17', '2015-11-19', '24', 'Technical', 'Housing & Urban Development Coordinating Council'),
('03033', 'Training on Gender Analysis & Gender Responsive Planning & Budgeting', '2016-06-22', '2016-06-24', '24', 'Technical', 'City Government of Calapan'),
('03033', 'Final National Consultation and Validation Workshop on Adaptation to Climate Change-Coastal Cities at Risk', '2016-07-20', '2016-07-20', '8', 'Safety/Technical', 'Ateneo de Manila University, Manila Observatory'),
('03033', 'Workshop on GAD Planning & Budgeting', '2016-07-25', '2016-07-26', '16', 'Technical', 'City Government of Calapan'),
('03033', 'Workshop on Establishment of GAD Data Base ', '2016-08-04', '2016-08-05', '16', 'Technical', 'City Government of Calapan');

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
('24018', 'KUMAIN NG MANOK', '', ''),
('22012', 'Biking', 'Program to Institutionalize Meritocracy and Excellence -Human Resource Management (PRIME-HRM) Maturi', 'N/A'),
('22012', 'Playing Drums', '', ''),
('22012', 'Cooking', '', ''),
('24014', 'PLAYING DIFFERENT MUSICAL/BAND INSTRUMENT', '', 'PHILIPPINE TAEKWONDO ASSOCIATION		'),
('24014', '', '', 'MATHEMATICS TEACHERS ASSOCIATION OF THE PHILIPPINES		'),
('24014', '', '', 'PHILIPPINE ASSOCIATION OF RESEARCHERS AND SOFTWARE USERS		'),
('01097', 'DRIVING', 'THREE - TERM COUNCILOR ( CITY OF CALAPAN)					', ''),
('01097', 'COMPUTER LITERATE', '', ''),
('22025', 'N/A', 'N/A', 'N/A'),
('24018', 'COMPUTER', '', ''),
('24018', 'KUMAIN NG MANOK', '', ''),
('22024', 'COMPUTER LITERATE', '', ''),
('22024', 'CLERICAL WORKS', '', ''),
('22024', 'CUSTOMER SERVICE', '', ''),
('22024', 'TIME MANAGEMENT', '', ''),
('22024', 'ADAPTABILITY', '', ''),
('20004', 'HOUSE/BUILDING ELECTRICAL WIRING INSTALLATION', 'DEVELOPMENT-ORIENTED YOUTH AWARD (FINALIST)', 'CITY GOVERNMENT OF CALAPAN EMPLOYEES\' COOPERATIVE (CGCEMCO)'),
('20004', 'DRIVING', '', 'CITY GOVERNMENT OF CALAPAN EMPLOYEES\' ASSOCIATION (CGCEA)'),
('20004', 'PLAYING BASKETBALL', '', ''),
('22012', 'Biking', 'Program to Institutionalize Meritocracy and Excellence -Human Resource Management (PRIME-HRM) Maturi', 'N/A'),
('22012', 'Playing Drums', '', ''),
('22012', 'Cooking', '', ''),
('24014', 'PLAYING DIFFERENT MUSICAL/BAND INSTRUMENT', '', 'PHILIPPINE TAEKWONDO ASSOCIATION		'),
('24014', '', '', 'MATHEMATICS TEACHERS ASSOCIATION OF THE PHILIPPINES		'),
('24014', '', '', 'PHILIPPINE ASSOCIATION OF RESEARCHERS AND SOFTWARE USERS		'),
('01097', 'DRIVING', 'THREE - TERM COUNCILOR ( CITY OF CALAPAN)					', ''),
('01097', 'COMPUTER LITERATE', '', ''),
('22025', 'N/A', 'N/A', 'N/A'),
('24018', 'COMPUTER', '', ''),
('24018', 'KUMAIN NG MANOK', '', ''),
('22024', 'COMPUTER LITERATE', '', ''),
('22024', 'CLERICAL WORKS', '', ''),
('22024', 'CUSTOMER SERVICE', '', ''),
('22024', 'TIME MANAGEMENT', '', ''),
('22024', 'ADAPTABILITY', '', ''),
('20004', 'HOUSE/BUILDING ELECTRICAL WIRING INSTALLATION', 'DEVELOPMENT-ORIENTED YOUTH AWARD (FINALIST)', 'CITY GOVERNMENT OF CALAPAN EMPLOYEES\' COOPERATIVE (CGCEMCO)'),
('20004', 'DRIVING', '', 'CITY GOVERNMENT OF CALAPAN EMPLOYEES\' ASSOCIATION (CGCEA)'),
('20004', 'PLAYING BASKETBALL', '', '');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

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
