-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2025 at 07:20 PM
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
-- Table structure for table `nilai_pc`
--

CREATE TABLE `nilai_pc` (
  `id_nilai_pc` int(11) NOT NULL,
  `id_atribut` int(11) NOT NULL,
  `id_pc` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_pc`
--

INSERT INTO `nilai_pc` (`id_nilai_pc`, `id_atribut`, `id_pc`, `nilai`) VALUES
(1, 1, 44, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nilai_pc`
--
ALTER TABLE `nilai_pc`
  ADD PRIMARY KEY (`id_nilai_pc`),
  ADD KEY `fk_atribut` (`id_atribut`),
  ADD KEY `fk_pc` (`id_pc`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nilai_pc`
--
ALTER TABLE `nilai_pc`
  MODIFY `id_nilai_pc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai_pc`
--
ALTER TABLE `nilai_pc`
  ADD CONSTRAINT `fk_atribut` FOREIGN KEY (`id_atribut`) REFERENCES `atribut` (`id_atribut`),
  ADD CONSTRAINT `fk_pc` FOREIGN KEY (`id_pc`) REFERENCES `nama_pc` (`id_pc`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
