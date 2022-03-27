-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 27, 2022 at 11:57 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safeboda_test_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `event_code` varchar(30) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_venue` varchar(100) NOT NULL DEFAULT '',
  `event_date` varchar(30) NOT NULL DEFAULT '',
  `event_onwer` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `event_code`, `event_name`, `event_venue`, `event_date`, `event_onwer`, `status`) VALUES
(1, 'EV00001', 'AFRICAN ROOTS FESTIVAL', 'KIGALI ARENA', '2022-04-10', 'ONE WORLD ENTERTAINMENT LTD', 'Y'),
(3, 'EV00002', 'TOUR DU RWANDA', 'CANAL OLYMPIA KIGALI RWANDA', '2022-04-10', 'ONE WORLD ENTERTAINMENT LTD', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `event_promo_code`
--

CREATE TABLE `event_promo_code` (
  `id` int(11) NOT NULL,
  `promo_code` varchar(30) NOT NULL,
  `event_code` varchar(30) NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `radius` double NOT NULL DEFAULT 0,
  `status` varchar(10) NOT NULL DEFAULT 'Y',
  `expirty_date` varchar(30) NOT NULL,
  `created_at` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_promo_code`
--

INSERT INTO `event_promo_code` (`id`, `promo_code`, `event_code`, `amount`, `radius`, `status`, `expirty_date`, `created_at`) VALUES
(1, 'SB9J2RJD', 'EV00001', 1000, 100, 'D', '2022-03-28', '2022-03-27 19:52:25'),
(2, 'SBI2FMBZ', 'EV00001', 1000, 100, 'D', '2022-03-28', '2022-03-27 19:58:08'),
(3, 'SBS277V6', 'EV00001', 2500, 100, 'Y', '2022-03-29', '2022-03-27 19:59:22'),
(4, 'SBLBPGCD', 'EV00001', 2500, 150, 'Y', '2022-03-29', '2022-03-27 20:00:53'),
(5, 'SB8EKYT4', 'EV00001', 2500, 190, 'D', '2022-03-29', '2022-03-27 21:30:41'),
(6, 'SBF6OP2Y', 'EV00001', 2500, 180, 'Y', '2022-03-29', '2022-03-27 21:32:21'),
(7, 'SBA5RHCK', 'EV00001', 2500, 10, 'Y', '2022-03-29', '2022-03-27 21:34:58'),
(8, 'SBKD8L53', 'EV00001', 2500, 100, 'D', '2022-03-29', '2022-03-27 21:38:09'),
(9, 'SB37G8FK', 'EV00001', 2500, 1500, 'Y', '2022-03-29', '2022-03-27 21:44:44'),
(10, 'SBFZFW9D', 'EV00001', 2500, 1500, 'Y', '2022-03-27', '2022-03-27 21:47:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_code` (`event_code`);

--
-- Indexes for table `event_promo_code`
--
ALTER TABLE `event_promo_code`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_code` (`promo_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_promo_code`
--
ALTER TABLE `event_promo_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
