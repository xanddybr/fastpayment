-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 04:52 AM
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
-- Database: `u617177303_fastpay`
--

-- --------------------------------------------------------

--
-- Table structure for table `historic`
--

CREATE TABLE `historic` (
  `id_historic` int(11) NOT NULL,
  `id_person` int(11) NOT NULL,
  `id_schedule` int(11) NOT NULL,
  `status_payment` enum('pending','approved','rejected') DEFAULT 'pending',
  `payment_url` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `myevent`
--

CREATE TABLE `myevent` (
  `id_myevent` int(11) NOT NULL,
  `myevent` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `myevent`
--

INSERT INTO `myevent` (`id_myevent`, `myevent`, `price`, `created_at`) VALUES
(1, 'Reiki Nivel 1', 100.00, NULL),
(2, 'Reiki Nivel 2', 150.00, NULL),
(3, 'Baralho Cigano', 200.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id_person` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'active',
  `id_typeperson` enum('admin','client') NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id_person`, `full_name`, `email`, `password`, `status`, `id_typeperson`, `created_at`) VALUES
(10, 'admin', 'admin', '$2y$12$LhbZPYF7V2V9rceQB8xule6VtzwzC3tYZLmPG7Eha3jobcapQMqyW', 'active', 'admin', '2025-08-08 07:36:14');

-- --------------------------------------------------------

--
-- Table structure for table `person_details`
--

CREATE TABLE `person_details` (
  `id_PersonDetails` int(11) NOT NULL,
  `id_person` int(11) NOT NULL,
  `activity_professional` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `number` varchar(10) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `obs_motived` text DEFAULT NULL,
  `first_time` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id_schedule` int(11) NOT NULL,
  `id_myevent` int(11) NOT NULL,
  `id_tpevent` int(11) NOT NULL,
  `id_units` int(11) NOT NULL,
  `time` time NOT NULL,
  `date` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vacancies` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id_schedule`, `id_myevent`, `id_tpevent`, `id_units`, `time`, `date`, `vacancies`, `created_at`) VALUES
(48, 1, 6, 1, '10:30:00', '17/09/2025', 1, '2025-09-03 02:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `typeevent`
--

CREATE TABLE `typeevent` (
  `id_tpevent` int(11) NOT NULL,
  `tpevent` varchar(100) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typeevent`
--

INSERT INTO `typeevent` (`id_tpevent`, `tpevent`, `create_at`) VALUES
(1, 'Consulta', '0000-00-00 00:00:00'),
(4, 'Congresso', '0000-00-00 00:00:00'),
(5, 'Palestra', '0000-00-00 00:00:00'),
(6, 'Curso', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `typeperson`
--

CREATE TABLE `typeperson` (
  `id_typePerson` int(11) NOT NULL,
  `typePerson` varchar(50) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `typeperson`
--

INSERT INTO `typeperson` (`id_typePerson`, `typePerson`, `create_at`) VALUES
(1, 'admin', '0000-00-00 00:00:00'),
(2, 'user', '0000-00-00 00:00:00'),
(3, 'subscriber', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id_units` int(11) NOT NULL,
  `units` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id_units`, `units`, `created_at`) VALUES
(1, 'Pechincha', '0000-00-00 00:00:00'),
(3, 'Barra', '0000-00-00 00:00:00'),
(9, 'Coroa Grande', NULL),
(10, 'On-line', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `historic`
--
ALTER TABLE `historic`
  ADD PRIMARY KEY (`id_historic`),
  ADD KEY `id_person` (`id_person`),
  ADD KEY `id_schedule` (`id_schedule`);

--
-- Indexes for table `myevent`
--
ALTER TABLE `myevent`
  ADD PRIMARY KEY (`id_myevent`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id_person`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `person_details`
--
ALTER TABLE `person_details`
  ADD PRIMARY KEY (`id_PersonDetails`),
  ADD KEY `person_details_ibfk_1` (`id_person`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id_schedule`),
  ADD KEY `id_myevent` (`id_myevent`) USING BTREE,
  ADD KEY `id_schedule` (`id_tpevent`) USING BTREE,
  ADD KEY `id_units` (`id_units`);

--
-- Indexes for table `typeevent`
--
ALTER TABLE `typeevent`
  ADD PRIMARY KEY (`id_tpevent`);

--
-- Indexes for table `typeperson`
--
ALTER TABLE `typeperson`
  ADD PRIMARY KEY (`id_typePerson`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id_units`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `historic`
--
ALTER TABLE `historic`
  MODIFY `id_historic` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `myevent`
--
ALTER TABLE `myevent`
  MODIFY `id_myevent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id_person` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `person_details`
--
ALTER TABLE `person_details`
  MODIFY `id_PersonDetails` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id_schedule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `typeevent`
--
ALTER TABLE `typeevent`
  MODIFY `id_tpevent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `typeperson`
--
ALTER TABLE `typeperson`
  MODIFY `id_typePerson` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id_units` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `historic`
--
ALTER TABLE `historic`
  ADD CONSTRAINT `historic_ibfk_1` FOREIGN KEY (`id_person`) REFERENCES `person` (`id_person`),
  ADD CONSTRAINT `historic_ibfk_2` FOREIGN KEY (`id_schedule`) REFERENCES `schedule` (`id_schedule`);

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `fgkey_id_myevent` FOREIGN KEY (`id_myevent`) REFERENCES `myevent` (`id_myevent`),
  ADD CONSTRAINT `fgkey_id_tpevent` FOREIGN KEY (`id_tpevent`) REFERENCES `typeevent` (`id_tpevent`),
  ADD CONSTRAINT `fgkey_id_units` FOREIGN KEY (`id_units`) REFERENCES `units` (`id_units`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
