-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 15, 2023 at 11:41 AM
-- Server version: 10.6.7-MariaDB-2ubuntu1.1
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sistem_pakar`
--

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `id` int(11) NOT NULL,
  `kode` char(3) NOT NULL,
  `nama_gejala` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gejala`
--

INSERT INTO `gejala` (`id`, `kode`, `nama_gejala`) VALUES
(1, 'G01', 'Sulit tidur'),
(2, 'G02', 'Mendengar suara aneh'),
(3, 'G03', 'Sering atau mudah menangis'),
(4, 'G04', 'Kehilangan minat untuk melakukan aktivitas'),
(5, 'G05', 'Emosi menjadi datar'),
(6, 'G06', 'Ingantan Terganggu'),
(7, 'G07', 'Menjauh dari lingkungan sosial'),
(8, 'G08', 'Pikiran dan berbicara kacau'),
(9, 'G09', 'Rasa takutdan khawatir berlebihan'),
(10, 'G10', 'Mimpi buruk'),
(11, 'G11', 'Sering merasa sedih'),
(12, 'G12', 'Mempercayai sesuatu yang tidak nyata'),
(13, 'G13', 'Sulit mengendalikan emosi'),
(14, 'G14', 'Diliputi perasaan bersalah yang berlebihan'),
(15, 'G15', 'Perasaan bermusuhan'),
(17, 'G16', 'Menghindari sebuah tempat/objek'),
(18, 'G17', 'Kehilangan motivasi'),
(19, 'G18', 'Sering cemas'),
(20, 'G19', 'Moody'),
(21, 'G20', 'Perasaan putus asa'),
(22, 'G21', 'Kurangnya daya ingat'),
(23, 'G22', 'Bicara terlalu cepat'),
(24, 'G23', 'Gangguan pernafasan'),
(25, 'G24', 'Gerakan tubuh dan pikiran lambat');

-- --------------------------------------------------------

--
-- Table structure for table `kasus_penyakit`
--

CREATE TABLE `kasus_penyakit` (
  `id` int(11) NOT NULL,
  `kode` char(3) NOT NULL,
  `diagnosa` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kasus_penyakit`
--

INSERT INTO `kasus_penyakit` (`id`, `kode`, `diagnosa`) VALUES
(1, 'P01', 'Skizofrenia'),
(2, 'P02', 'Bipolar'),
(3, 'P03', 'Depresi');

-- --------------------------------------------------------

--
-- Table structure for table `keputusan`
--

CREATE TABLE `keputusan` (
  `id` int(11) NOT NULL,
  `kode` char(3) NOT NULL,
  `jika` varchar(512) NOT NULL,
  `kode_diagnosa` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `keputusan`
--

INSERT INTO `keputusan` (`id`, `kode`, `jika`, `kode_diagnosa`) VALUES
(18, 'K01', 'G01ANDG02ANDG03ANDG04ANDG05ANDG06ANDG07ANDG08ANDG09ANDG10ANDG11', 'P01'),
(19, 'K02', 'G01ANDG02ANDG03ANDG04ANDG13ANDG14ANDG15', 'P02'),
(20, 'K03', 'G01ANDG07ANDG16ANDG17ANDG18', 'P03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `username` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `password`, `username`) VALUES
(1, 'Arif Setiawan', '$2y$10$S780GiAzLAZMZJnWjWDGpuA2GlhLV7Zsz.hZjHv/rNQu5jgD7fF0a', 'arif setiawan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `kasus_penyakit`
--
ALTER TABLE `kasus_penyakit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `keputusan`
--
ALTER TABLE `keputusan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `kode_diagnosa` (`kode_diagnosa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `kasus_penyakit`
--
ALTER TABLE `kasus_penyakit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `keputusan`
--
ALTER TABLE `keputusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keputusan`
--
ALTER TABLE `keputusan`
  ADD CONSTRAINT `kode_diagnosa` FOREIGN KEY (`kode_diagnosa`) REFERENCES `kasus_penyakit` (`kode`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
