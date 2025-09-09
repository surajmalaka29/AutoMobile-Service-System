-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 02:12 PM
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
-- Database: `cras_auto`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL,
  `start_time` time DEFAULT current_timestamp(),
  `status` varchar(100) DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `start_time`, `status`, `endDate`, `app_id`, `invoice_id`) VALUES
(1, '09:22:51', 'Completed', '2024-09-15 10:32:58', 1, 18),
(2, '09:22:54', 'Completed', '2025-09-28 10:33:02', 3, 19),
(3, '09:23:02', 'Completed', '2025-10-13 10:29:45', 5, 1),
(4, '09:23:08', 'Completed', '2025-10-18 10:31:25', 6, 13),
(5, '09:23:11', 'Completed', '2025-10-22 10:31:28', 7, 14),
(6, '09:23:19', 'Completed', '2025-10-27 10:29:50', 8, 2),
(7, '09:23:23', 'Completed', '2025-11-03 10:29:54', 9, 3),
(8, '09:23:28', 'Completed', '2025-11-14 10:31:32', 11, 15),
(9, '09:23:35', 'Completed', '2025-11-20 10:33:06', 12, 20),
(10, '09:23:37', 'Service Scheduled', NULL, 13, 0),
(11, '09:23:45', 'Completed', '2025-12-01 10:31:36', 14, 16),
(12, '09:23:51', 'Completed', '2025-12-12 10:29:59', 16, 4),
(13, '09:23:52', 'Completed', '2025-12-18 10:30:26', 17, 5),
(14, '09:23:54', 'Completed', '2025-12-22 10:30:30', 18, 6),
(15, '09:24:00', 'Completed', '2025-12-28 10:33:10', 19, 21),
(16, '09:24:06', 'Completed', '2025-01-03 10:31:40', 20, 17),
(17, '09:24:10', 'Completed', '2025-01-10 10:30:33', 21, 7),
(18, '09:24:14', 'Started', NULL, 23, 0),
(19, '09:24:19', 'Completed', '2025-02-03 10:33:15', 24, 22),
(20, '09:24:25', 'Service Scheduled', NULL, 25, 0),
(21, '09:24:42', 'Service Scheduled', NULL, 27, 0),
(22, '09:24:44', 'Not Started', NULL, 28, 0),
(23, '09:24:49', 'Not Started', NULL, 29, 0),
(24, '09:24:53', 'Started', NULL, 30, 0),
(25, '09:24:55', 'Not Started', NULL, 31, 0),
(26, '09:25:01', 'Not Started', NULL, 33, 0),
(27, '09:25:05', 'Not Started', NULL, 34, 0),
(28, '09:25:07', 'Not Started', NULL, 35, 0),
(29, '09:26:04', 'Not Started', NULL, 38, 0),
(30, '09:26:07', 'Not Started', NULL, 37, 0),
(31, '09:26:13', 'Not Started', NULL, 39, 0),
(32, '09:26:54', 'Completed', '2025-02-03 10:30:50', 40, 8),
(33, '09:26:55', 'Completed', '2025-02-03 10:30:55', 42, 9),
(34, '09:27:00', 'Completed', '2025-02-03 10:30:59', 46, 10),
(35, '09:27:03', 'Completed', '2025-02-03 10:31:03', 54, 11),
(36, '09:27:06', 'Completed', '2025-02-03 10:31:07', 50, 12),
(37, '09:27:08', 'Started', NULL, 53, 0),
(38, '09:27:10', 'Not Started', NULL, 44, 0),
(39, '09:27:12', 'Not Started', NULL, 48, 0),
(40, '09:27:15', 'Not Started', NULL, 55, 0);

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `app_id` int(11) NOT NULL,
  `date` date DEFAULT current_timestamp(),
  `app_date` date DEFAULT NULL,
  `app_time` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `activity_type` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `officer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`app_id`, `date`, `app_date`, `app_time`, `status`, `activity_type`, `message`, `vehicle_id`, `officer_id`) VALUES
(1, '2024-09-15', '2024-09-15', '08:45:00', 'Completed', 3, 'Detailing Service scheduled for your vehicle', 8, 6),
(2, '2024-09-22', '2024-09-22', '09:15:00', 'Cancelled', 1, 'Oil Change service requested', 12, NULL),
(3, '2024-09-28', '2024-09-28', '10:30:00', 'Completed', 4, 'Body Wash service scheduled for your vehicle', 19, 6),
(4, '2024-10-05', '2024-10-05', '11:00:00', 'Cancelled', 9, 'Engine Diagnostic service cancelled', 4, NULL),
(5, '2024-10-13', '2024-10-13', '08:30:00', 'Completed', 2, 'Full Service scheduled for your vehicle', 7, 2),
(6, '2024-10-18', '2024-10-18', '12:00:00', 'Completed', 7, 'AC Recharge scheduled for your vehicle', 16, 5),
(7, '2024-10-22', '2024-10-22', '13:15:00', 'Completed', 5, 'Brake Inspection scheduled for your vehicle', 10, 5),
(8, '2024-10-27', '2024-10-27', '14:30:00', 'Completed', 6, 'Battery Check scheduled for your vehicle', 13, 2),
(9, '2024-11-03', '2024-11-03', '15:00:00', 'Completed', 8, 'Exhaust System Check scheduled for your vehicle', 17, 2),
(10, '2024-11-08', '2024-11-08', '09:45:00', 'Cancelled', 3, 'Detailing Service cancelled', 22, NULL),
(11, '2024-11-14', '2024-11-14', '08:45:00', 'Completed', 10, 'Transmission Service scheduled for your vehicle', 24, 5),
(12, '2024-11-20', '2024-11-20', '10:00:00', 'Completed', 4, 'Body Wash service requested', 6, 6),
(13, '2024-11-27', '2024-11-27', '11:30:00', 'Confirmed', 2, 'Full Service scheduled for your vehicle', 21, 6),
(14, '2024-12-01', '2024-12-01', '08:30:00', 'Completed', 5, 'Brake Inspection scheduled for your vehicle', 5, 5),
(15, '2024-12-05', '2024-12-05', '10:45:00', 'Cancelled', 7, 'AC Recharge cancelled', 15, NULL),
(16, '2024-12-12', '2024-12-12', '09:00:00', 'Completed', 9, 'Engine Diagnostic scheduled for your vehicle', 11, 2),
(17, '2024-12-18', '2024-12-18', '13:30:00', 'Completed', 6, 'Battery Check scheduled for your vehicle', 23, 2),
(18, '2024-12-22', '2024-12-22', '14:00:00', 'Completed', 3, 'Detailing Service scheduled for your vehicle', 25, 2),
(19, '2024-12-28', '2024-12-28', '15:00:00', 'Completed', 1, 'Oil Change scheduled for your vehicle', 27, 6),
(20, '2025-01-03', '2025-01-03', '08:30:00', 'Completed', 10, 'Transmission Service scheduled for your vehicle', 30, 5),
(21, '2025-01-10', '2025-01-10', '09:30:00', 'Completed', 2, 'Full Service scheduled for your vehicle', 8, 2),
(22, '2025-01-15', '2025-01-15', '10:30:00', 'Cancelled', 4, 'Body Wash cancelled', 3, NULL),
(23, '2025-01-20', '2025-01-20', '11:00:00', 'Confirmed', 8, 'Exhaust System Check scheduled for your vehicle', 29, 2),
(24, '2025-01-25', '2025-01-25', '12:00:00', 'Completed', 5, 'Brake Inspection scheduled for your vehicle', 18, 6),
(25, '2024-09-16', '2024-09-16', '08:45:00', 'Confirmed', 7, 'AC Recharge scheduled for your vehicle', 16, 5),
(26, '2024-09-23', '2024-09-23', '09:00:00', 'Cancelled', 1, 'Oil Change cancelled', 12, NULL),
(27, '2024-09-29', '2024-09-29', '08:30:00', 'Confirmed', 3, 'Detailing Service requested', 5, 6),
(28, '2024-10-02', '2024-10-02', '10:00:00', 'Confirmed', 4, 'Body Wash scheduled for your vehicle', 25, 6),
(29, '2024-10-10', '2024-10-10', '14:30:00', 'Confirmed', 8, 'Exhaust System Check scheduled for your vehicle', 20, 2),
(30, '2024-10-14', '2024-10-14', '11:15:00', 'Confirmed', 6, 'Battery Check scheduled for your vehicle', 22, 5),
(31, '2024-10-17', '2024-10-17', '13:45:00', 'Confirmed', 2, 'Full Service scheduled for your vehicle', 15, 5),
(32, '2024-10-21', '2024-10-21', '09:30:00', 'Cancelled', 5, 'Brake Inspection cancelled', 14, NULL),
(33, '2024-10-23', '2024-10-23', '08:30:00', 'Confirmed', 9, 'Engine Diagnostic scheduled for your vehicle', 28, 6),
(34, '2024-10-26', '2024-10-26', '10:15:00', 'Confirmed', 1, 'Oil Change scheduled for your vehicle', 6, 2),
(35, '2024-11-01', '2024-11-01', '09:00:00', 'Confirmed', 3, 'Detailing Service scheduled for your vehicle', 30, 2),
(36, '2024-11-04', '2024-11-04', '11:30:00', 'Cancelled', 6, 'Battery Check cancelled', 19, NULL),
(37, '2024-11-10', '2024-11-10', '12:30:00', 'Confirmed', 4, 'Body Wash scheduled for your vehicle', 16, 5),
(38, '2024-11-13', '2024-11-13', '14:00:00', 'Confirmed', 7, 'AC Recharge scheduled for your vehicle', 13, 5),
(39, '2024-11-18', '2024-11-18', '15:00:00', 'Confirmed', 5, 'Brake Inspection scheduled for your vehicle', 10, 6),
(40, '2024-11-23', '2024-11-23', '08:30:00', 'Completed', 9, 'Engine Diagnostic scheduled for your vehicle', 17, 8),
(41, '2024-12-02', '2024-12-02', '09:45:00', 'Cancelled', 2, 'Full Service cancelled', 22, NULL),
(42, '2024-12-07', '2024-12-07', '10:00:00', 'Completed', 3, 'Detailing Service scheduled for your vehicle', 25, 8),
(43, '2024-12-10', '2024-12-10', '12:00:00', 'Pending', 6, 'Battery Check scheduled for your vehicle', 13, NULL),
(44, '2024-12-14', '2024-12-14', '14:15:00', 'Confirmed', 10, 'Transmission Service scheduled for your vehicle', 8, 8),
(45, '2024-12-20', '2024-12-20', '15:00:00', 'Pending', 4, 'Body Wash scheduled for your vehicle', 14, NULL),
(46, '2024-12-23', '2024-12-23', '08:30:00', 'Completed', 7, 'AC Recharge scheduled for your vehicle', 18, 8),
(47, '2024-12-26', '2024-12-26', '09:00:00', 'Pending', 1, 'Oil Change scheduled for your vehicle', 24, NULL),
(48, '2024-12-29', '2024-12-29', '10:30:00', 'Confirmed', 9, 'Engine Diagnostic scheduled for your vehicle', 28, 8),
(49, '2025-01-01', '2025-01-01', '11:00:00', 'Cancelled', 5, 'Brake Inspection cancelled', 6, NULL),
(50, '2025-01-05', '2025-01-05', '12:15:00', 'Completed', 8, 'Exhaust System Check scheduled for your vehicle', 30, 8),
(51, '2025-01-08', '2025-01-08', '13:45:00', 'Pending', 3, 'Detailing Service scheduled for your vehicle', 19, NULL),
(52, '2025-01-12', '2025-01-12', '14:30:00', 'Cancelled', 4, 'Body Wash cancelled', 10, NULL),
(53, '2025-01-18', '2025-01-18', '08:30:00', 'Confirmed', 7, 'AC Recharge scheduled for your vehicle', 20, 8),
(54, '2025-01-22', '2025-01-22', '09:30:00', 'Completed', 6, 'Battery Check scheduled for your vehicle', 12, 8),
(55, '2025-01-27', '2025-01-27', '10:00:00', 'Confirmed', 9, 'Engine Diagnostic scheduled for your vehicle', 15, 8);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cus_Id` int(11) NOT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `membership` varchar(50) DEFAULT NULL,
  `address_no` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `zip_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cus_Id`, `fname`, `lname`, `email`, `phone`, `password`, `profile_pic`, `membership`, `address_no`, `street`, `city`, `district`, `zip_code`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '1234567890', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '101', 'Main St', 'New York', 'NY', 10001),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '2345678901', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '202', 'Oak St', 'Los Angeles', 'CA', 90001),
(3, 'Robert', 'Johnson', 'robert.johnson@example.com', '3456789012', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '303', 'Pine St', 'Chicago', 'IL', 60601),
(4, 'Emily', 'Davis', 'emily.davis@example.com', '4567890123', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Bronze', '404', 'Birch St', 'Houston', 'TX', 77002),
(5, 'Michael', 'Wilson', 'michael.wilson@example.com', '5678901234', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '505', 'Cedar St', 'San Francisco', 'CA', 94101),
(6, 'Sarah', 'Martinez', 'sarah.martinez@example.com', '6789012345', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '606', 'Maple St', 'Seattle', 'WA', 98101),
(7, 'David', 'Anderson', 'david.anderson@example.com', '7890123456', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '707', 'Walnut St', 'Denver', 'CO', 80201),
(8, 'Laura', 'Thomas', 'laura.thomas@example.com', '8901234567', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Bronze', '808', 'Cherry St', 'Miami', 'FL', 33101),
(9, 'Daniel', 'Hernandez', 'daniel.hernandez@example.com', '9012345678', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '909', 'Peach St', 'Dallas', 'TX', 75001),
(10, 'Jessica', 'Clark', 'jessica.clark@example.com', '1230984567', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '1010', 'Pear St', 'Boston', 'MA', 2101),
(11, 'Brian', 'Lewis', 'brian.lewis@example.com', '2341095678', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '1111', 'Plum St', 'Phoenix', 'AZ', 85001),
(12, 'Olivia', 'Walker', 'olivia.walker@example.com', '3452106789', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Bronze', '1212', 'Lime St', 'Philadelphia', 'PA', 19101),
(13, 'Matthew', 'Allen', 'matthew.allen@example.com', '4563217890', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '1313', 'Melon St', 'San Antonio', 'TX', 78201),
(14, 'Sophia', 'Young', 'sophia.young@example.com', '5674328901', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '1414', 'Mango St', 'San Diego', 'CA', 92101),
(15, 'William', 'King', 'william.king@example.com', '6785439012', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '1515', 'Fig St', 'San Jose', 'CA', 95101),
(16, 'Isabella', 'Scott', 'isabella.scott@example.com', '7896540123', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Bronze', '1616', 'Grape St', 'Austin', 'TX', 73301),
(17, 'James', 'Green', 'james.green@example.com', '8907651234', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '1717', 'Berry St', 'Jacksonville', 'FL', 32201),
(18, 'Mia', 'Adams', 'mia.adams@example.com', '9018762345', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '1818', 'Coconut St', 'Indianapolis', 'IN', 46201),
(19, 'Ethan', 'Baker', 'ethan.baker@example.com', '1239873456', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '1919', 'Papaya St', 'Columbus', 'OH', 43201),
(20, 'Charlotte', 'Nelson', 'charlotte.nelson@example.com', '2340984567', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Bronze', '2020', 'Olive St', 'Fort Worth', 'TX', 76101),
(21, 'Benjamin', 'Carter', 'benjamin.carter@example.com', '3451095678', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '2121', 'Apricot St', 'Charlotte', 'NC', 28201),
(22, 'Amelia', 'Mitchell', 'amelia.mitchell@example.com', '4562106789', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '2222', 'Pomegranate St', 'Detroit', 'MI', 48201),
(23, 'Alexander', 'Perez', 'alexander.perez@example.com', '5673217890', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '2323', 'Persimmon St', 'El Paso', 'TX', 79901),
(24, 'Harper', 'Roberts', 'harper.roberts@example.com', '6784328901', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Bronze', '2424', 'Kiwi St', 'Seattle', 'WA', 98101),
(25, 'Lucas', 'Turner', 'lucas.turner@example.com', '7895439012', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '2525', 'Banana St', 'Denver', 'CO', 80201),
(26, 'Evelyn', 'Phillips', 'evelyn.phillips@example.com', '8906540123', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '2626', 'Lemon St', 'Washington', 'DC', 20001),
(27, 'Mason', 'Campbell', 'mason.campbell@example.com', '9017651234', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '2727', 'Orange St', 'Memphis', 'TN', 38101),
(28, 'Ella', 'Parker', 'ella.parker@example.com', '1238762345', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Bronze', '2828', 'Pineapple St', 'Baltimore', 'MD', 21201),
(29, 'Logan', 'Evans', 'logan.evans@example.com', '2349873456', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Gold', '2929', 'Cranberry St', 'Louisville', 'KY', 40201),
(30, 'Grace', 'Edwards', 'grace.edwards@example.com', '3450984567', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Silver', '3030', 'Blueberry St', 'Milwaukee', 'WI', 53201),
(31, 'Pasindu', 'Deshan', 'pdeshan@gmail.com', '0771234567', '$2y$10$mRBD34EvjYdmBhj3bkwkCO6DUFGS2ROhz8TTiCx5PyWg5JQ0Ip95C', NULL, 'Platinum', '', '', '', '', NULL),
(32, 'nippa', 'dhhhd', 'test@mail.com', NULL, '$2y$10$AyHz7YnLeh.DUp5nluafue3/U71DXr3c5Mi8K2MfegGBbblv8Wdou', NULL, NULL, '', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_buying_price` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `item_name`, `category`, `brand`, `quantity`, `unit_buying_price`, `unit_price`) VALUES
(1, 'Engine Oil', 'Oil Change', 'Castrol', 94, 5000.00, 6500.00),
(2, 'Oil Filter', 'Oil Change', 'Bosch', 149, 1200.00, 1500.00),
(3, 'Air Filter', 'Full Service', 'Mahle', 112, 3000.00, 4500.00),
(4, 'Cabin Air Filter', 'Full Service', 'Denso', 109, 2500.00, 3500.00),
(5, 'Spark Plugs', 'Full Service', 'NGK', 199, 500.00, 800.00),
(6, 'Brake Pads', 'Brake Inspection', 'Brembo', 78, 7000.00, 9000.00),
(7, 'Brake Discs', 'Brake Inspection', 'Ferodo', 75, 12000.00, 15000.00),
(8, 'Brake Fluid', 'Brake Inspection', 'Valvoline', 90, 3500.00, 4500.00),
(9, 'Radiator Coolant', 'Full Service', 'Shell', 88, 2000.00, 3000.00),
(10, 'Transmission Fluid', 'Transmission Service', 'Mobil', 66, 4500.00, 6000.00),
(11, 'Wiper Blades', 'Full Service', 'Bosch', 150, 1200.00, 1600.00),
(12, 'Battery', 'Battery Check', 'Exide', 49, 12000.00, 16000.00),
(13, 'Battery Terminals', 'Battery Check', 'Optima', 96, 800.00, 1200.00),
(14, 'Starter Motor', 'Battery Check', 'Bosch', 30, 8000.00, 10500.00),
(15, 'Alternator', 'Battery Check', 'Denso', 40, 10000.00, 13000.00),
(16, 'AC Filter', 'AC Recharge', 'Honeywell', 76, 1000.00, 1500.00),
(17, 'Compressor Oil', 'AC Recharge', 'R134A', 58, 4000.00, 5500.00),
(18, 'AC Coolant', 'AC Recharge', 'Thermofluid', 68, 6000.00, 7500.00),
(19, 'Exhaust Pipe', 'Exhaust System Check', 'Bosal', 47, 9000.00, 12000.00),
(20, 'Catalytic Converter', 'Exhaust System Check', 'Magnaflow', 34, 20000.00, 25000.00),
(21, 'Oxygen Sensor', 'Exhaust System Check', 'NTK', 79, 3500.00, 4500.00),
(22, 'Clutch Kit', 'Transmission Service', 'Sachs', 39, 15000.00, 18000.00),
(23, 'Fuel Filter', 'Full Service', 'Mann Filter', 129, 2500.00, 3500.00),
(24, 'Timing Belt', 'Full Service', 'Gates', 98, 5000.00, 7000.00),
(25, 'Thermostat', 'Full Service', 'Denso', 90, 2500.00, 3500.00),
(26, 'Headlamp Bulb', 'Full Service', 'Philips', 200, 1000.00, 1300.00),
(27, 'Tail Lamp Bulb', 'Full Service', 'Osram', 200, 800.00, 1200.00),
(28, 'Fuel Pump', 'Full Service', 'Bosch', 50, 15000.00, 18000.00),
(29, 'Radiator', 'Full Service', 'Nissens', 50, 12000.00, 16000.00),
(30, 'Shock Absorber', 'Full Service', 'KYB', 60, 10000.00, 13000.00),
(31, 'Wheel Alignment Kit', 'Full Service', 'Hunter', 9, 25000.00, 35000.00),
(32, 'Exhaust Manifold', 'Exhaust System Check', 'Victor Reinz', 18, 12000.00, 15000.00),
(33, 'Muffler', 'Exhaust System Check', 'Bosal', 23, 8000.00, 10000.00),
(34, 'Air Conditioning Duct', 'AC Recharge', 'Good Year', 30, 5000.00, 7000.00),
(35, 'A/C Compressor', 'AC Recharge', 'Sanden', 40, 18000.00, 23000.00),
(36, 'Radiator Hose', 'Full Service', 'Dayco', 60, 1500.00, 2500.00),
(37, 'Brake Line', 'Brake Inspection', 'Unbrako', 50, 2000.00, 2500.00),
(38, 'Strut Assembly', 'Full Service', 'Monroe', 29, 15000.00, 20000.00),
(39, 'Tie Rod End', 'Full Service', 'Moog', 40, 3500.00, 4500.00),
(40, 'Control Arm', 'Full Service', 'TRW', 50, 7000.00, 9000.00),
(41, 'Power Steering Pump', 'Full Service', 'Bosch', 35, 8000.00, 10000.00),
(42, 'Timing Chain', 'Full Service', 'INA', 60, 4500.00, 6000.00),
(43, 'Power Steering Fluid', 'Full Service', 'Valvoline', 98, 1500.00, 2000.00),
(44, 'Windshield Washer Fluid', 'Full Service', 'Shell', 120, 500.00, 800.00),
(45, 'Air Conditioning Freon', 'AC Recharge', 'HFC-134a', 100, 1500.00, 2200.00),
(46, 'Oxygen Sensor', 'Exhaust System Check', 'Bosch', 50, 4000.00, 5000.00),
(47, 'Crankshaft Seal', 'Full Service', 'Elring', 39, 6000.00, 8000.00),
(48, 'Water Pump', 'Full Service', 'Bosch', 49, 8000.00, 10000.00),
(49, 'Distributor Cap', 'Full Service', 'Delphi', 58, 3000.00, 4500.00),
(50, 'Fuel Injector', 'Full Service', 'Bosch', 69, 5000.00, 6500.00),
(51, 'Oil Drain Plug', 'Oil Change', 'ACDelco', 150, 500.00, 700.00),
(52, 'Timing Belt Kit', 'Full Service', 'SKF', 30, 12000.00, 15000.00),
(53, 'Crankshaft Pulley', 'Full Service', 'Dayco', 40, 7000.00, 8500.00),
(54, 'Fuel Tank', 'Full Service', 'Nissens', 25, 12000.00, 15000.00),
(55, 'Windshield Wiper Motor', 'Full Service', 'Valeo', 35, 8000.00, 10000.00),
(56, 'Ignition Coil', 'Full Service', 'BorgWarner', 50, 4500.00, 6000.00);

-- --------------------------------------------------------

--
-- Table structure for table `inventoryinvoice`
--

CREATE TABLE `inventoryinvoice` (
  `inventory_invoice_id` int(11) NOT NULL,
  `date` date DEFAULT current_timestamp(),
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventoryinvoice`
--

INSERT INTO `inventoryinvoice` (`inventory_invoice_id`, `date`, `item_id`, `quantity`, `total_price`, `activity_id`) VALUES
(1, '2025-02-03', 3, 1, 4500.00, 1),
(2, '2025-02-03', 5, 1, 800.00, 1),
(3, '2025-02-03', 48, 1, 10000.00, 2),
(4, '2025-02-03', 49, 1, 4500.00, 2),
(5, '2025-02-03', 31, 1, 35000.00, 2),
(6, '2025-02-03', 23, 1, 3500.00, 9),
(7, '2025-02-03', 43, 2, 4000.00, 9),
(8, '2025-02-03', 3, 2, 9000.00, 10),
(9, '2025-02-03', 24, 1, 7000.00, 10),
(10, '2025-02-03', 1, 1, 6500.00, 15),
(11, '2025-02-03', 2, 1, 1500.00, 15),
(12, '2025-02-03', 6, 1, 9000.00, 19),
(13, '2025-02-03', 8, 3, 13500.00, 19),
(14, '2025-02-03', 3, 2, 9000.00, 21),
(15, '2025-02-03', 9, 1, 3000.00, 21),
(16, '2025-02-03', 33, 2, 20000.00, 32),
(17, '2025-02-03', 19, 1, 12000.00, 32),
(18, '2025-02-03', 3, 1, 4500.00, 33),
(19, '2025-02-03', 1, 2, 13000.00, 33),
(20, '2025-02-03', 16, 1, 1500.00, 34),
(21, '2025-02-03', 17, 2, 11000.00, 34),
(22, '2025-02-03', 13, 2, 2400.00, 35),
(23, '2025-02-03', 19, 1, 12000.00, 36),
(24, '2025-02-03', 20, 1, 25000.00, 36),
(25, '2025-02-03', 16, 1, 1500.00, 4),
(26, '2025-02-03', 18, 2, 15000.00, 4),
(27, '2025-02-03', 8, 2, 9000.00, 5),
(28, '2025-02-03', 22, 1, 18000.00, 8),
(29, '2025-02-03', 10, 2, 12000.00, 8),
(30, '2025-02-03', 8, 3, 13500.00, 11),
(31, '2025-02-03', 6, 1, 9000.00, 11),
(32, '2025-02-03', 8, 1, 4500.00, 16),
(33, '2025-02-03', 10, 2, 12000.00, 16),
(34, '2025-02-03', 8, 1, 4500.00, 20),
(35, '2025-02-03', 16, 2, 3000.00, 20),
(36, '2025-02-03', 3, 2, 9000.00, 3),
(37, '2025-02-03', 4, 1, 3500.00, 3),
(38, '2025-02-03', 9, 1, 3000.00, 3),
(39, '2025-02-03', 12, 1, 16000.00, 6),
(40, '2025-02-03', 19, 1, 12000.00, 7),
(41, '2025-02-03', 32, 2, 30000.00, 7),
(42, '2025-02-03', 1, 3, 19500.00, 12),
(43, '2025-02-03', 21, 1, 4500.00, 12),
(44, '2025-02-03', 13, 2, 2400.00, 13),
(45, '2025-02-03', 38, 1, 20000.00, 13),
(46, '2025-02-03', 24, 1, 7000.00, 14),
(47, '2025-02-03', 49, 1, 4500.00, 14),
(48, '2025-02-03', 50, 1, 6500.00, 17),
(49, '2025-02-03', 47, 1, 8000.00, 17);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `officer_id` int(11) DEFAULT NULL,
  `cus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `date`, `amount`, `status`, `officer_id`, `cus_id`) VALUES
(1, '2024-10-13', 60500.00, 'UnPaid', 2, 7),
(2, '2024-10-27', 34000.00, 'UnPaid', 2, 13),
(3, '2024-11-03', 63000.00, 'UnPaid', 2, 17),
(4, '2024-12-12', 46500.00, 'UnPaid', 2, 11),
(5, '2024-12-18', 40400.00, 'UnPaid', 2, 23),
(6, '2024-12-22', 41500.00, 'UnPaid', 2, 25),
(7, '2025-01-10', 59500.00, 'UnPaid', 2, 8),
(8, '2025-02-03', 54500.00, 'UnPaid', 8, 17),
(9, '2025-02-03', 47500.00, 'UnPaid', 8, 25),
(10, '2025-02-03', 39500.00, 'UnPaid', 8, 18),
(11, '2025-02-03', 20400.00, 'UnPaid', 8, 12),
(12, '2025-02-03', 58000.00, 'UnPaid', 8, 30),
(13, '2024-10-18', 43500.00, 'UnPaid', 5, 16),
(14, '2024-10-22', 21000.00, 'UnPaid', 5, 10),
(15, '2024-11-14', 66000.00, 'UnPaid', 5, 24),
(16, '2024-12-01', 34500.00, 'UnPaid', 5, 5),
(17, '2025-01-03', 52500.00, 'UnPaid', 5, 30),
(18, '2024-09-15', 35300.00, 'UnPaid', 6, 8),
(19, '2024-09-28', 54500.00, 'UnPaid', 6, 19),
(20, '2024-11-20', 12500.00, 'UnPaid', 6, 6),
(21, '2024-12-28', 23000.00, 'UnPaid', 6, 27),
(22, '2025-02-03', 34500.00, 'UnPaid', 6, 18);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notify_id` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT '0',
  `delete_status` bit(1) DEFAULT b'0',
  `cus_id` int(11) DEFAULT NULL,
  `officer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notify_id`, `date`, `description`, `status`, `delete_status`, `cus_id`, `officer_id`) VALUES
(1, '2025-02-02 23:20:58', 'Your Account Is Created successfully. Please Reset Your Password', '0', b'0', 31, NULL),
(2, '2025-02-03 09:31:04', 'Your Quotation has been sent for your Car with the license plate number VWX-9101.', '0', b'0', 8, NULL),
(3, '2025-02-03 09:32:31', 'Your Quotation has been sent for your Van with the license plate number CDE-2345.', '0', b'0', 19, NULL),
(4, '2025-02-03 09:33:30', 'Your Quotation has been sent for your Car with the license plate number PQR-1234.', '0', b'0', 6, NULL),
(5, '2025-02-03 09:35:09', 'Your Quotation has been sent for your Car with the license plate number IJK-1234.', '0', b'0', 21, NULL),
(6, '2025-02-03 09:36:37', 'Your Quotation has been sent for your Bike with the license plate number ABC-5678.', '0', b'0', 27, NULL),
(7, '2025-02-03 09:37:32', 'Your Quotation has been sent for your Van with the license plate number ZAB-9101.', '0', b'0', 18, NULL),
(8, '2025-02-03 09:38:31', 'Your Quotation has been sent for your Truck with the license plate number MNO-6789.', '0', b'0', 5, NULL),
(9, '2025-02-03 09:40:18', 'Your Quotation has been sent for your Truck with the license plate number WXY-5678.', '0', b'0', 17, NULL),
(10, '2025-02-03 09:41:00', 'Your Quotation has been sent for your Car with the license plate number UVW-6789.', '0', b'0', 25, NULL),
(11, '2025-02-03 09:42:13', 'Your Quotation has been sent for your Van with the license plate number ZAB-9101.', '0', b'0', 18, NULL),
(12, '2025-02-03 09:43:07', 'Your Quotation has been sent for your Car with the license plate number HIJ-5678.', '0', b'0', 12, NULL),
(13, '2025-02-03 09:43:48', 'Your Quotation has been sent for your Bike with the license plate number JKL-6789.', '0', b'0', 30, NULL),
(14, '2025-02-03 09:44:54', 'Your Quotation has been sent for your Truck with the license plate number TUV-1234.', '0', b'0', 16, NULL),
(15, '2025-02-03 09:45:33', 'Your Quotation has been sent for your Car with the license plate number BCD-6789.', '0', b'0', 10, NULL),
(16, '2025-02-03 09:46:15', 'Your Quotation has been sent for your Car with the license plate number RST-2345.', '0', b'0', 24, NULL),
(17, '2025-02-03 09:47:35', 'Your Quotation has been sent for your Truck with the license plate number MNO-6789.', '0', b'0', 5, NULL),
(18, '2025-02-03 09:48:32', 'Your Quotation has been sent for your Bike with the license plate number JKL-6789.', '0', b'0', 30, NULL),
(19, '2025-02-03 09:49:19', 'Your Quotation has been sent for your Truck with the license plate number TUV-1234.', '0', b'0', 16, NULL),
(20, '2025-02-03 10:07:16', 'Your Quotation has been sent for your Car with the license plate number STU-5678.', '0', b'0', 7, NULL),
(21, '2025-02-03 10:07:51', 'Your Quotation has been sent for your Car with the license plate number KLM-9101.', '0', b'0', 13, NULL),
(22, '2025-02-03 10:08:33', 'Your Quotation has been sent for your Truck with the license plate number WXY-5678.', '0', b'0', 17, NULL),
(23, '2025-02-03 10:09:37', 'Your Quotation has been sent for your Car with the license plate number EFG-1234.', '0', b'0', 11, NULL),
(24, '2025-02-03 10:10:11', 'Your Quotation has been sent for your Car with the license plate number OPQ-9101.', '0', b'0', 23, NULL),
(25, '2025-02-03 10:11:32', 'Your Quotation has been sent for your Car with the license plate number UVW-6789.', '0', b'0', 25, NULL),
(26, '2025-02-03 10:12:13', 'Your Quotation has been sent for your Car with the license plate number VWX-9101.', '0', b'0', 8, NULL),
(27, '2025-02-03 10:19:23', 'Your Quotation has been sent for your Car with the license plate number VWX-9101.', '0', b'0', NULL, NULL),
(28, '2025-02-03 10:23:31', 'Your Quotation has been accepted by the customer for the Car with the license plate number VWX-9101.', '0', b'0', NULL, 2),
(29, '2025-02-03 10:23:31', 'Your Quotation has been accepted by the customer for the Car with the license plate number VWX-9101.', '0', b'0', NULL, 2),
(30, '2025-02-03 10:29:45', 'Your vehicle ( STU-5678 ) Service completed successfully.', '0', b'0', 7, NULL),
(31, '2025-02-03 10:29:50', 'Your vehicle ( KLM-9101 ) Service completed successfully.', '0', b'0', 13, NULL),
(32, '2025-02-03 10:29:54', 'Your vehicle ( WXY-5678 ) Service completed successfully.', '0', b'0', 17, NULL),
(33, '2025-02-03 10:29:59', 'Your vehicle ( EFG-1234 ) Service completed successfully.', '0', b'0', 11, NULL),
(34, '2025-02-03 10:30:26', 'Your vehicle ( OPQ-9101 ) Service completed successfully.', '0', b'0', 23, NULL),
(35, '2025-02-03 10:30:30', 'Your vehicle ( UVW-6789 ) Service completed successfully.', '0', b'0', 25, NULL),
(36, '2025-02-03 10:30:33', 'Your vehicle ( VWX-9101 ) Service completed successfully.', '0', b'0', 8, NULL),
(37, '2025-02-03 10:30:50', 'Your vehicle ( WXY-5678 ) Service completed successfully.', '0', b'0', 17, NULL),
(38, '2025-02-03 10:30:55', 'Your vehicle ( UVW-6789 ) Service completed successfully.', '0', b'0', 25, NULL),
(39, '2025-02-03 10:30:59', 'Your vehicle ( ZAB-9101 ) Service completed successfully.', '0', b'0', 18, NULL),
(40, '2025-02-03 10:31:03', 'Your vehicle ( HIJ-5678 ) Service completed successfully.', '0', b'0', 12, NULL),
(41, '2025-02-03 10:31:07', 'Your vehicle ( JKL-6789 ) Service completed successfully.', '0', b'0', 30, NULL),
(42, '2025-02-03 10:31:25', 'Your vehicle ( TUV-1234 ) Service completed successfully.', '0', b'0', 16, NULL),
(43, '2025-02-03 10:31:28', 'Your vehicle ( BCD-6789 ) Service completed successfully.', '0', b'0', 10, NULL),
(44, '2025-02-03 10:31:32', 'Your vehicle ( RST-2345 ) Service completed successfully.', '0', b'0', 24, NULL),
(45, '2025-02-03 10:31:36', 'Your vehicle ( MNO-6789 ) Service completed successfully.', '0', b'0', 5, NULL),
(46, '2025-02-03 10:31:40', 'Your vehicle ( JKL-6789 ) Service completed successfully.', '0', b'0', 30, NULL),
(47, '2025-02-03 10:32:58', 'Your vehicle ( VWX-9101 ) Service completed successfully.', '0', b'0', 8, NULL),
(48, '2025-02-03 10:33:02', 'Your vehicle ( CDE-2345 ) Service completed successfully.', '0', b'0', 19, NULL),
(49, '2025-02-03 10:33:06', 'Your vehicle ( PQR-1234 ) Service completed successfully.', '0', b'0', 6, NULL),
(50, '2025-02-03 10:33:10', 'Your vehicle ( ABC-5678 ) Service completed successfully.', '0', b'0', 27, NULL),
(51, '2025-02-03 10:33:15', 'Your vehicle ( ZAB-9101 ) Service completed successfully.', '0', b'0', 18, NULL),
(52, '2025-02-03 12:38:49', 'Your Account Is Created successfully. Please Reset Your Password', '0', b'0', 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `officer`
--

CREATE TABLE `officer` (
  `officer_id` int(11) NOT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `position` varchar(50) NOT NULL,
  `address_no` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `zip_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer`
--

INSERT INTO `officer` (`officer_id`, `fname`, `lname`, `email`, `phone`, `password`, `profile_pic`, `type`, `role`, `position`, `address_no`, `street`, `city`, `district`, `zip_code`) VALUES
(1, 'Pasindu', 'Fdo', 'pasindu2705@gmail.com', '0766101663', '$2y$10$xtT2iS3bqTNQw5XtQOAT1.ixBoV3ZubWX/1yCOP.uaAIgpfx7W4Ai', NULL, 'Full Time', 'admin', 'Administrator', '', '', '', '', NULL),
(2, 'Malaka', 'Perera', 'mperera@gmail.com', '0771234567', '$2y$10$FPIJe6LmNrCwnvRDHmvQFurppiAAz2Ii39kNbuTh50.kiARhHzmNq', '680e3527ceec91.68472661.png', 'Full Time', 'mechanic', 'Senior Mechanic', '', '', '', '', 0),
(3, 'Nipuna', 'Lakruwan', 'nlakruwan@gmail.com', '0771234567', '$2y$10$AFtn6yKeYXIiNFwCbOSTDOOwQkYJ2516PFOcy/ZGw4dnXFf/Bd5fW', NULL, 'Part Time', 'employee', 'Cashier', '', '', '', '', NULL),
(4, 'Devin', 'Johnathan', 'djperera@gmail.com', '0771234567', '$2y$10$1R08.kcf3CQTk/q0Xmfx3e5kmY4IdE.70NRtc5gFvynh15JVLFxeu', NULL, 'Full Time', 'admin', 'Assistant Manager', '', '', '', '', NULL),
(5, 'Thamindu', 'Kavishan', 'tkavishan@gmail.com', '0771234567', '$2y$10$PnyN6kS6jSMF8GMW6rgGN.6/.jiPuhn.nYeRwj1x1gcoEQ7UJCu8m', NULL, 'Part Time', 'mechanic', 'Junior Mechanic', '', '', '', '', NULL),
(6, 'Dilan', 'Channa', 'dcperera@gmail.com', '0771234567', '$2y$10$xbmn3hBJBnXm6Pn9kTv6ROqWtaaAlQifl4qQ8yu0MwhQMf0MBTEk2', NULL, 'Full Time', 'mechanic', 'Senior Mechanic', '', '', '', '', NULL),
(7, 'Sewmina', 'Fernando', 'sfdo@gmail.com', '0771234567', '$2y$10$AfGogIzJ3IZ8ptR/15A7feDjAMdlWP4xssBNsaRsKwqqAB/cW/7e.', NULL, 'Full Time', 'admin', 'Manager', '', '', '', '', NULL),
(8, 'Niklaus', 'Michaelson', 'nik@gmail.com', '0771234567', '$2y$10$bVgOkxvQf2t8zMq.8lr5m.DKmYmVppRC1Vc.RIfHT/1VkgJalK9Iy', NULL, 'Part Time', 'mechanic', 'Junior Mechanic', '', '', '', '', NULL),
(9, 'Inventory', 'Account', 'inventory@gmail.com', '0771234567', '$2y$10$btqVi6KBSBIIfuwSdaq8SOyStvGfYooGfhxUJPUqLPmzlCfj8Ncle', NULL, 'Full Time', 'inventory', 'Manager', '', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `pay_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `cus_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `hours` decimal(5,2) DEFAULT NULL,
  `service_charge` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_type`, `hours`, `service_charge`) VALUES
(1, 'Oil Change', 0.50, 15000.00),
(2, 'Full Service', 2.00, 45000.00),
(3, 'Detailing', 2.00, 30000.00),
(4, 'Body Wash', 0.50, 5000.00),
(5, 'Brake Inspection', 0.50, 12000.00),
(6, 'Battery Check', 0.50, 18000.00),
(7, 'AC Recharge', 0.70, 27000.00),
(8, 'Exhaust System Check', 0.50, 21000.00),
(9, 'Engine Diagnostic', 0.50, 22500.00),
(10, 'Transmission Service', 1.00, 36000.00);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` int(11) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `license_no` varchar(50) DEFAULT NULL,
  `engine_no` varchar(50) DEFAULT NULL,
  `chasis_no` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `cus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicle_id`, `model`, `company`, `year`, `license_no`, `engine_no`, `chasis_no`, `category`, `cus_id`) VALUES
(1, 'Model S', 'Tesla', 2022, 'ABC-1234', 'ENG123456', '1HGCM82633A123456', 'Car', 1),
(2, 'Civic', 'Honda', 2020, 'DEF-5678', 'ENG678901', '2HGFA16598H123457', 'Car', 2),
(3, 'Camry', 'Toyota', 2019, 'GHI-9101', 'ENG112233', '3N1AB7AP4KY123458', 'Car', 3),
(4, 'F-150', 'Ford', 2021, 'JKL-2345', 'ENG445566', '1FTFW1E5XMF123459', 'Truck', 4),
(5, 'Silverado', 'Chevrolet', 2018, 'MNO-6789', 'ENG778899', '1GCRCPEC0EZ123460', 'Truck', 5),
(6, 'X5', 'BMW', 2020, 'PQR-1234', 'ENG334455', '5UXCR6C57KL123461', 'Car', 6),
(7, 'Rav4', 'Toyota', 2021, 'STU-5678', 'ENG556677', 'JTMRFREV1LJ123462', 'Car', 7),
(8, 'CR-V', 'Honda', 2017, 'VWX-9101', 'ENG889900', '5J6RW2H50LL123463', 'Car', 8),
(9, 'E-Class', 'Mercedes', 2019, 'YZA-2345', 'ENG221133', 'WDDZF4JBXHA123464', 'Car', 9),
(10, 'A4', 'Audi', 2022, 'BCD-6789', 'ENG334466', 'WAULFAFL7GN123465', 'Car', 10),
(11, 'Altima', 'Nissan', 2018, 'EFG-1234', 'ENG778855', '1N4BL4CV7LC123466', 'Car', 11),
(12, 'Wrangler', 'Jeep', 2021, 'HIJ-5678', 'ENG992244', '1C4HJXDG9LW123467', 'Car', 12),
(13, 'Grand Cherokee', 'Jeep', 2019, 'KLM-9101', 'ENG665588', '1C4RJFBG2LC123468', 'Car', 13),
(14, 'CX-5', 'Mazda', 2020, 'NOP-2345', 'ENG112244', 'JM3KFBDM6L0123469', 'Car', 14),
(15, 'Outback', 'Subaru', 2018, 'QRS-6789', 'ENG556633', '4S4BSANC5K3123470', 'Car', 15),
(16, 'Ranger', 'Ford', 2022, 'TUV-1234', 'ENG223366', '1FTTW1F59ML123471', 'Truck', 16),
(17, 'Tacoma', 'Toyota', 2017, 'WXY-5678', 'ENG779922', '3TMCZ5AN0KM123472', 'Truck', 17),
(18, 'Transit', 'Ford', 2020, 'ZAB-9101', 'ENG448833', '1FTBW2CG2LKA123473', 'Van', 18),
(19, 'Sprinter', 'Mercedes', 2019, 'CDE-2345', 'ENG665599', 'WD4PF1CD4KP123474', 'Van', 19),
(20, 'Odyssey', 'Honda', 2021, 'FGH-6789', 'ENG882255', '5FNRL6H73MB123475', 'Van', 20),
(21, 'Pilot', 'Honda', 2018, 'IJK-1234', 'ENG334477', '5FNYF5H36JB123476', 'Car', 21),
(22, 'Pathfinder', 'Nissan', 2020, 'LMN-5678', 'ENG990044', '5N1DR2CM9LC123477', 'Car', 22),
(23, 'Charger', 'Dodge', 2019, 'OPQ-9101', 'ENG556677', '2C3CDXCT9LH123478', 'Car', 23),
(24, 'Mustang', 'Ford', 2021, 'RST-2345', 'ENG334488', '1FA6P8CF9M5123479', 'Car', 24),
(25, 'Corvette', 'Chevrolet', 2022, 'UVW-6789', 'ENG221155', '1G1YY26E675123480', 'Car', 25),
(26, 'Hayabusa', 'Suzuki', 2018, 'XYZ-1234', 'ENG778899', 'JS1GX72B0M2101234', 'Bike', 26),
(27, 'R1', 'Yamaha', 2019, 'ABC-5678', 'ENG665522', 'JYARN23E0GA123481', 'Bike', 27),
(28, 'Ninja 650', 'Kawasaki', 2021, 'DEF-9101', 'ENG448899', 'ML5EXN0XMC123482', 'Bike', 28),
(29, 'Panigale V4', 'Ducati', 2020, 'GHI-2345', 'ENG998877', 'ZDMMADV1XLB123483', 'Bike', 29),
(30, 'Scout', 'Indian', 2017, 'JKL-6789', 'ENG223344', '56KSMB004M123484', 'Bike', 30),
(31, 'Xterra', 'Nissan', 2018, 'MNO-1234', 'ENG557788', '5N1AN08U88C123485', 'Car', 1),
(32, 'Cayenne', 'Porsche', 2019, 'PQR-5678', 'ENG446622', 'WP1AB29P93LA123486', 'Car', 2),
(33, 'Explorer', 'Ford', 2021, 'STU-9101', 'ENG887755', '1FM5K8GC0MGA123487', 'Car', 3),
(34, 'Express', 'Chevrolet', 2022, 'VWX-2345', 'ENG992233', '1GAZGPFG3G1123488', 'Van', 4),
(35, 'Tundra', 'Toyota', 2020, 'YZA-6789', 'ENG334411', '5TFDY5F18KX123489', 'Truck', 5),
(36, 'Ram 1500', 'Dodge', 2018, 'BCD-1234', 'ENG556699', '1C6RR7FT8JS123490', 'Truck', 6),
(37, 'Accord', 'Honda', 2019, 'EFG-5678', 'ENG881144', '1HGCV1F17KA123491', 'Car', 7),
(38, 'Impreza', 'Subaru', 2021, 'HIJ-9101', 'ENG772255', 'JF1VA1G65M123492', 'Car', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `app_id` (`app_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `fk_vehicle_id` (`vehicle_id`),
  ADD KEY `activity_type` (`activity_type`),
  ADD KEY `officer_id` (`officer_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cus_Id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `inventoryinvoice`
--
ALTER TABLE `inventoryinvoice`
  ADD PRIMARY KEY (`inventory_invoice_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `inventoryinvoice_ibfk_2` (`activity_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `officer_id` (`officer_id`),
  ADD KEY `fk_cus_id` (`cus_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notify_id`),
  ADD KEY `cus_id` (`cus_id`),
  ADD KEY `officer_id` (`officer_id`);

--
-- Indexes for table `officer`
--
ALTER TABLE `officer`
  ADD PRIMARY KEY (`officer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `cus_id` (`cus_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `cus_id` (`cus_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cus_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `inventoryinvoice`
--
ALTER TABLE `inventoryinvoice`
  MODIFY `inventory_invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notify_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `officer`
--
ALTER TABLE `officer`
  MODIFY `officer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `appointment` (`app_id`);

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`activity_type`) REFERENCES `service` (`service_id`),
  ADD CONSTRAINT `appointment_ibfk_4` FOREIGN KEY (`officer_id`) REFERENCES `officer` (`officer_id`),
  ADD CONSTRAINT `fk_vehicle_id` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`);

--
-- Constraints for table `inventoryinvoice`
--
ALTER TABLE `inventoryinvoice`
  ADD CONSTRAINT `inventoryinvoice_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`),
  ADD CONSTRAINT `inventoryinvoice_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_cus_id` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`cus_Id`),
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`officer_id`) REFERENCES `officer` (`officer_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`cus_Id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`officer_id`) REFERENCES `officer` (`officer_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`cus_Id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`cus_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
