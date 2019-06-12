-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2019 at 01:24 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prq`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `accessId` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`accessId`, `description`) VALUES
(1, 'csAdmin'),
(2, 'itAdmin'),
(3, 'picker'),
(4, 'delivery');

-- --------------------------------------------------------

--
-- Table structure for table `bin`
--

CREATE TABLE `bin` (
  `binId` int(11) NOT NULL,
  `partNo` varchar(100) NOT NULL,
  `location` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bin`
--

INSERT INTO `bin` (`binId`, `partNo`, `location`) VALUES
(1, '006501-02', 'EFCABIN1'),
(2, '006501-02', 'EFCABIN2'),
(3, '-04273-051', 'SER30101'),
(4, '-04273-051', 'SER30201'),
(5, '-04273-078', 'SER30104'),
(6, '-04273-120', 'SER30105');


--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logId` int(11) NOT NULL,
  `requestId` int(11) NOT NULL,
  `statusId` int(11) NOT NULL,
  `action` varchar(20) NOT NULL,
  `actionBy` varchar(50) NOT NULL,
  `actionAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`logId`, `requestId`, `statusId`, `action`, `actionBy`, `actionAt`, `remarks`) VALUES
(113, 115, 1, 'created', 'LDAP_USER', '2019-04-17 09:40:05', NULL),
(114, 116, 1, 'created', 'LDAP_USER', '2019-04-17 10:20:39', NULL),
(115, 117, 1, 'created', 'LDAP_USER', '2019-04-17 10:28:19', NULL),
(116, 117, 1, 'modified', 'LDAP_USER', '2019-04-17 10:31:56', NULL),
(117, 117, 1, 'modified', 'LDAP_USER', '2019-04-17 10:32:04', NULL),
(118, 117, 1, 'modified', 'LDAP_USER', '2019-04-17 10:32:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `messageId` int(11) NOT NULL,
  `sender` varchar(20) NOT NULL,
  `subject` varchar(20) NOT NULL,
  `message` longtext NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `viewed` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `part`
--

CREATE TABLE `part` (
  `partId` int(11) NOT NULL,
  `partNo` varchar(50) NOT NULL,
  `partDescription` varchar(100) NOT NULL,
  `stockRoomCode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `part`
--

INSERT INTO `part` (`partId`, `partNo`, `partDescription`, `stockRoomCode`) VALUES
(1, '000100-0179', 'CONNECTOR, ELEC', 'R0'),
(2, '0011-041-000', 'CONTACT', 'EI'),
(3, '-002-9701', 'SPRING', 'RI'),
(4, '006499', 'ID PLATE', 'E0'),
(5, '006501-02', 'NAMEPLATE 747 FEEL COMPT ASSY (162400)', 'EI'),
(6, '-010-249', '-010-249 CA18178-001 SERVOCONTROL AILERON A400M', 'R1'),
(7, '-010-250', '-010-250 CA18238-001 SERVOCONTROL ELEVATOR A400M', 'R1'),
(8, '-010-254', '-010-254 CA21500-001 HYD ACTUATOR ASSY ELECTRIC B', 'R1'),
(9, '-010-255', '-010-255 CA21501-001 SERVOCONTROL AY SPOILER 2', 'R1'),
(10, '-010-258A', 'C99160-001 AILERON PCU ASSY AND REU ASSY', 'E1'),
(11, '-010-258A-OP', 'OEM PART C99160-001 AILERON PCU ASSY AND REU ASSY', 'E5'),
(12, '-010-258B', 'C99160-002 AILERON PCU ASSY AND REU ASSY', 'E1'),
(13, '-010-258B-OP', 'OEM PART C99160-002 AILERON PCU ASSY AND REU ASSY', 'E5'),
(14, '-010-258C', 'C99160-003 AILERON PCU AND REU ASSY', 'E1'),
(15, '-010-258C-OP', 'OEM PART C99160-003 AILERON PCU AND REU ASSY', 'E5'),
(16, '-010-258D', 'C99160-004 AILERON PCU AND REU ASSY', 'E1'),
(17, '-010-258D-OP', 'OEM PART C99160-004 AILERON PCU AND REU ASSY', 'E5'),
(18, '-010-258-PLAN', 'PLANNING BILL -010-258', 'E1'),
(19, '-010-259A', 'C99632-001 FLAPERON/REU ACTUATOR SYSTEM INSTR READ', 'E1'),
(20, '-010-259B', 'C99161-001 FLAPERON/REU ACTUATOR SYSTEM PRODUCTION', 'E1'),
(21, '-010-260A', 'C99162-001 ELEVATOR PCU ASSY AND REU ASSY', 'E1'),
(22, '-010-260A-OP', 'OEM PART C99162-001 ELEVATOR PCU ASSY AND REU ASSY', 'E5'),
(23, '-010-260B', 'C99162-002 ELEVATOR PCU ASSY AND REU ASSY', 'E1'),
(24, '-010-260B-OP', 'OEM PART C99162-002 ELEVATOR PCU ASSY AND REU ASSY', 'E5'),
(25, '-010-260C', 'C99162-003 ELEVATOR PCU ASSY AND REU ASSY', 'E1'),
(26, '-010-260C-OP', 'OEM PART C99162-003 ELEV PCU ASSY AND REU ASSY', 'E5'),
(27, '-010-260D', 'C99162-004 ELEVATOR PCU ASSY AND REU ASSY', 'E1'),
(28, '-010-260D-OP', 'OEM PART C99162-004 ELEV PCU ASSY AND REU ASSY', 'E5'),
(29, '-010-260E', 'C99162-005 ELEVATOR PCU ASSY AND REU ASSY', 'E1'),
(30, '-010-260E-OP', 'OEM PART C99162-005 ELEVATOR PCU ASSY AND REU ASSY', 'E5'),
(31, '-010-260-PLAN', 'PLANNING BILL -010-260', 'E1'),
(32, '-010-261A', 'C99163-001 -8 RUDDER PCUASSY AND REU ASSY', 'E1'),
(33, '-010-261A-OP', 'OEM PART C99163-001 -8 RUDDER PCUASSY AND REU ASSY', 'E5'),
(34, '-010-261B', 'C99163-002 -8 RUDDER PCUASSY AND REU ASSY', 'E1'),
(35, '-010-261B-OP', 'OEM PART C99163-002 -8 RUDDER PCUASSY AND REU ASSY', 'E5'),
(36, '-010-261C', 'C99163-003 -8 RUDDER PCUASSY AND REU ASSY', 'E1'),
(37, '-010-261C-OP', 'OEM PART C99163-003 -8 RUDDER PCUASSY  REU ASSY', 'E5'),
(38, '-010-261D', 'C99163-004 -8 RUDDER PCUASSY AND REU ASSY', 'E1'),
(39, '-010-261D-OP', 'OEM PART C99163-004 -8 RUDDER PCUASSY AND REU', 'E5'),
(40, '-010-261E', 'C99163-005 -8 RUDDER PCUASSY AND REU ASSY', 'E1');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `requestId` int(11) NOT NULL,
  `partId` int(11) NOT NULL,
  `requestorName` varchar(100) NOT NULL,
  `samaccount` varchar(20) NOT NULL,
  `workCenter` varchar(100) NOT NULL,
  `contactNo` varchar(20) NOT NULL,
  `workOrder` varchar(255) DEFAULT NULL,
  `requestTypeId` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `binLocation` varchar(50) DEFAULT NULL,
  `requestedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statusId` int(11) NOT NULL DEFAULT '1',
  `pickedBy` varchar(100) DEFAULT NULL,
  `assignedPickerAt` datetime DEFAULT NULL,
  `deliveredBy` varchar(100) DEFAULT NULL,
  `assignedDeliveryAt` datetime DEFAULT NULL,
  `deliveredAt` datetime DEFAULT NULL,
  `receivedBy` varchar(20) DEFAULT NULL,
  `receivedAt` datetime DEFAULT NULL,
  `updatedBy` varchar(50) NOT NULL,
  `lastUpdatedAt` datetime DEFAULT NULL,
  `lastRemarks` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`requestId`, `partId`, `requestorName`, `samaccount`, `workCenter`, `contactNo`, `workOrder`, `requestTypeId`, `quantity`, `binLocation`, `requestedAt`, `statusId`, `pickedBy`, `assignedPickerAt`, `deliveredBy`, `assignedDeliveryAt`, `deliveredAt`, `receivedBy`, `receivedAt`, `updatedBy`, `lastUpdatedAt`, `lastRemarks`) VALUES
(142, 1, 'Ballug, Anthony Jr.', 'aballug', 'Information Technology', '1243458', '1:2:3:4:5', 1, '1:2:3:4:5', NULL, '2019-05-06 09:31:57', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'aballug', NULL, NULL);

--
-- Triggers `request`
--
DELIMITER $$
CREATE TRIGGER `after_insert` AFTER INSERT ON `request` FOR EACH ROW INSERT INTO logs SET requestId=new.requestId, statusId=new.statusId, action="created", actionBy=new.updatedBy
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update` AFTER UPDATE ON `request` FOR EACH ROW INSERT INTO logs SET requestId=new.requestId, statusId=new.statusId, action="modified", actionBy=new.updatedBy, remarks=new.lastRemarks
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `requesttype`
--

CREATE TABLE `requesttype` (
  `requestTypeId` int(11) NOT NULL,
  `requestType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requesttype`
--

INSERT INTO `requesttype` (`requestTypeId`, `requestType`) VALUES
(1, 'Replacement'),
(2, 'AFS (Approved for stock)'),
(3, '2 BIN'),
(4, 'Quality Issue');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusId` int(11) NOT NULL,
  `statusName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusId`, `statusName`) VALUES
(1, 'New'),
(2, 'For Picking'),
(3, 'For Delivery'),
(4, 'Delivered'),
(5, 'Received'),
(6, 'On-hold'),
(7, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `samaccount` varchar(50) NOT NULL,
  `displayName` varchar(200) NOT NULL,
  `accessId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `samaccount`, `displayName`, `accessId`) VALUES
(1, 'johnd', 'Doe, John.', 1),
(2, 'riemann', 'Riemann, Bernard', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`accessId`);

--
-- Indexes for table `bin`
--
ALTER TABLE `bin`
  ADD PRIMARY KEY (`binId`);

--
-- Indexes for table `binlocation`
--
ALTER TABLE `binlocation`
  ADD PRIMARY KEY (`binLocationId`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logId`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`messageId`);

--
-- Indexes for table `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`partId`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`requestId`);

--
-- Indexes for table `requesttype`
--
ALTER TABLE `requesttype`
  ADD PRIMARY KEY (`requestTypeId`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `accessId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bin`
--
ALTER TABLE `bin`
  MODIFY `binId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7110;

--
-- AUTO_INCREMENT for table `binlocation`
--
ALTER TABLE `binlocation`
  MODIFY `binLocationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `messageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `part`
--
ALTER TABLE `part`
  MODIFY `partId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24429;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `requestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `requesttype`
--
ALTER TABLE `requesttype`
  MODIFY `requestTypeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
