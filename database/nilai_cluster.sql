-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2025 at 10:19 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev-arh`
--

-- --------------------------------------------------------

--
-- Table structure for table `nilai_cluster`
--

CREATE TABLE `nilai_cluster` (
  `id_nilai_cluster` int(11) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `id_atribut` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_cluster`
--

INSERT INTO `nilai_cluster` (`id_nilai_cluster`, `id_cluster`, `id_atribut`, `nilai`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(10, 1, 10, 1),
(11, 2, 1, 1),
(12, 2, 2, 1),
(13, 2, 3, 1),
(14, 2, 4, 1),
(15, 2, 5, 1),
(16, 2, 6, 1),
(17, 2, 7, 1),
(18, 2, 8, 1),
(19, 2, 9, 1),
(20, 2, 10, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  ADD PRIMARY KEY (`id_nilai_cluster`),
  ADD KEY `fk_nilaicluster` (`id_cluster`),
  ADD KEY `fk_nilaiatribut` (`id_atribut`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  MODIFY `id_nilai_cluster` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  ADD CONSTRAINT `fk_nilaiatribut` FOREIGN KEY (`id_atribut`) REFERENCES `atribut` (`id_atribut`),
  ADD CONSTRAINT `fk_nilaicluster` FOREIGN KEY (`id_cluster`) REFERENCES `cluster` (`id_cluster`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
