-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 09:56 PM
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
-- Table structure for table `atribut`
--

CREATE TABLE `atribut` (
  `id_atribut` int(11) NOT NULL,
  `nama_atribut` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atribut`
--

INSERT INTO `atribut` (`id_atribut`, `nama_atribut`) VALUES
(1, 'PPI (CB R23)'),
(2, 'RAM (GB)'),
(3, 'OS Storage Speed (GB/s)'),
(4, 'OS Storage Capacity (GB)'),
(5, 'GPU PI (GPixel/s)'),
(6, 'VRAM GPU (GB)'),
(7, 'PSU (Watt)'),
(8, 'PSU % Efficiency (80 Plus Cert.)'),
(9, 'Upgradability (PCIe Socket)'),
(10, 'Harga (Juta Rupiah)');

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE `cluster` (
  `id_cluster` int(11) NOT NULL,
  `nama_cluster` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nama_pc`
--

CREATE TABLE `nama_pc` (
  `id_pc` int(11) NOT NULL,
  `nama_pc` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` varchar(50) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `avatar` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `email`, `password`, `role`, `status`, `avatar`) VALUES
(6, 'ahmad.rizki', 'Ahmad Rizki Hidayat', 'w.trisusanto022@gmail.com', '$2y$10$clpkfMyLXNuBVtgDOHAzZOJNbLv2H0HzNp45Vj8RLXYyAJhvPArVm', 'Admin', 'Aktif', '683f4a7f553a6.jpg'),
(14, 'user.user', 'Beta Tester', 'user@user.com', '$2y$10$oRc6LaP2y9WlMXODxe7zT.gaSQKcPkkNWGJoLA9Sk3QLgoc0b3Ecy', 'User', 'Aktif', '6665ceb967536.jpg'),
(30, 'user.user2', 'User 2', 'user2@user.com', '$2y$10$L1UjF3R2QK.PwaX0lJLsweXDH3NPWdVi6w1Rmr.kmov1UeQ5O3l2S', 'User', 'Aktif', '666dbb0bddccf.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atribut`
--
ALTER TABLE `atribut`
  ADD PRIMARY KEY (`id_atribut`);

--
-- Indexes for table `cluster`
--
ALTER TABLE `cluster`
  ADD PRIMARY KEY (`id_cluster`);

--
-- Indexes for table `nama_pc`
--
ALTER TABLE `nama_pc`
  ADD PRIMARY KEY (`id_pc`);

--
-- Indexes for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  ADD PRIMARY KEY (`id_nilai_cluster`);

--
-- Indexes for table `nilai_pc`
--
ALTER TABLE `nilai_pc`
  ADD PRIMARY KEY (`id_nilai_pc`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atribut`
--
ALTER TABLE `atribut`
  MODIFY `id_atribut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cluster`
--
ALTER TABLE `cluster`
  MODIFY `id_cluster` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nama_pc`
--
ALTER TABLE `nama_pc`
  MODIFY `id_pc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  MODIFY `id_nilai_cluster` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_pc`
--
ALTER TABLE `nilai_pc`
  MODIFY `id_nilai_pc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
