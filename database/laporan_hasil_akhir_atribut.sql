-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 10:47 PM
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
-- Table structure for table `laporan_hasil_akhir_atribut`
--

CREATE TABLE `laporan_hasil_akhir_atribut` (
  `id` int(11) NOT NULL,
  `id_laporan_hasil_akhir` int(11) NOT NULL,
  `nama_atribut` varchar(250) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan_hasil_akhir_atribut`
--
ALTER TABLE `laporan_hasil_akhir_atribut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_laporan_hasil_akhir` (`id_laporan_hasil_akhir`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan_hasil_akhir_atribut`
--
ALTER TABLE `laporan_hasil_akhir_atribut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5880;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan_hasil_akhir_atribut`
--
ALTER TABLE `laporan_hasil_akhir_atribut`
  ADD CONSTRAINT `fk_id_laporan_hasil_akhir` FOREIGN KEY (`id_laporan_hasil_akhir`) REFERENCES `laporan_hasil_akhir` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
