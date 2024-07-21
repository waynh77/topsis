-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for topsis
CREATE DATABASE IF NOT EXISTS `topsis` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `topsis`;

-- Dumping structure for table topsis.guru
CREATE TABLE IF NOT EXISTS `guru` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noInduk` varchar(255) NOT NULL,
  `namaGuru` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table topsis.guru: ~5 rows (approximately)
DELETE FROM `guru`;
INSERT INTO `guru` (`id`, `noInduk`, `namaGuru`) VALUES
	(1, '1', 'AAA'),
	(2, '2', 'BBB'),
	(3, '3', 'CCC'),
	(4, '4', 'DDD'),
	(5, '5', 'EEE');

-- Dumping structure for table topsis.kriteria
CREATE TABLE IF NOT EXISTS `kriteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `kriteria` varchar(255) NOT NULL,
  `bobot` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table topsis.kriteria: ~5 rows (approximately)
DELETE FROM `kriteria`;
INSERT INTO `kriteria` (`id`, `kode`, `kriteria`, `bobot`) VALUES
	(1, 'C1', 'Loyalitas', 5),
	(2, 'C2', 'Teladan', 5),
	(3, 'C3', 'Kehadiran', 4),
	(4, 'C4', 'Administrasi', 3),
	(5, 'C5', 'Supervisi', 2);

-- Dumping structure for table topsis.nilaidetail
CREATE TABLE IF NOT EXISTS `nilaidetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idhead` int(11) NOT NULL,
  `kriteria` varchar(255) NOT NULL,
  `nilai` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nilaidetail_ibfk_1` (`idhead`),
  CONSTRAINT `nilaidetail_ibfk_1` FOREIGN KEY (`idhead`) REFERENCES `nilaihead` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table topsis.nilaidetail: ~0 rows (approximately)
DELETE FROM `nilaidetail`;
INSERT INTO `nilaidetail` (`id`, `idhead`, `kriteria`, `nilai`) VALUES
	(6, 30, 'Loyalitas', 4),
	(7, 30, 'Teladan', 5),
	(8, 30, 'Kehadiran', 5),
	(9, 30, 'Administrasi', 5),
	(10, 30, 'Supervisi', 5),
	(11, 31, 'Loyalitas', 5),
	(12, 31, 'Teladan', 4),
	(13, 31, 'Kehadiran', 5),
	(14, 31, 'Administrasi', 4),
	(15, 31, 'Supervisi', 4),
	(16, 32, 'Loyalitas', 5),
	(17, 32, 'Teladan', 5),
	(18, 32, 'Kehadiran', 5),
	(19, 32, 'Administrasi', 5),
	(20, 32, 'Supervisi', 5),
	(21, 33, 'Loyalitas', 4),
	(22, 33, 'Teladan', 3),
	(23, 33, 'Kehadiran', 3),
	(24, 33, 'Administrasi', 3),
	(25, 33, 'Supervisi', 2),
	(26, 34, 'Loyalitas', 3),
	(27, 34, 'Teladan', 3),
	(28, 34, 'Kehadiran', 3),
	(29, 34, 'Administrasi', 2),
	(30, 34, 'Supervisi', 1);

-- Dumping structure for table topsis.nilaihead
CREATE TABLE IF NOT EXISTS `nilaihead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idperiode` int(11) NOT NULL,
  `idguru` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table topsis.nilaihead: ~5 rows (approximately)
DELETE FROM `nilaihead`;
INSERT INTO `nilaihead` (`id`, `idperiode`, `idguru`) VALUES
	(30, 1, 1),
	(31, 1, 2),
	(32, 1, 3),
	(33, 1, 4),
	(34, 1, 5);

-- Dumping structure for table topsis.penilaian
CREATE TABLE IF NOT EXISTS `penilaian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kriteria` varchar(255) NOT NULL,
  `keterangan` varchar(20) NOT NULL,
  `bobot` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table topsis.penilaian: ~25 rows (approximately)
DELETE FROM `penilaian`;
INSERT INTO `penilaian` (`id`, `kriteria`, `keterangan`, `bobot`) VALUES
	(1, 'Loyalitas', 'Sangat Baik', 5),
	(2, 'Loyalitas', 'Baik', 4),
	(3, 'Loyalitas', 'Cukup', 3),
	(4, 'Loyalitas', 'Kurang', 2),
	(5, 'Loyalitas', 'Sangat Kurang', 1),
	(6, 'Teladan', 'Sangat Baik', 5),
	(7, 'Teladan', 'Baik', 4),
	(8, 'Teladan', 'Cukup', 3),
	(9, 'Teladan', 'Kurang', 2),
	(10, 'Teladan', 'Sangat Kurang', 1),
	(11, 'Kehadiran', 'Sangat Baik', 5),
	(12, 'Kehadiran', 'Baik', 4),
	(13, 'Kehadiran', 'Cukup', 3),
	(14, 'Kehadiran', 'Kurang', 2),
	(15, 'Kehadiran', 'Sangat Kurang', 1),
	(16, 'Administrasi', 'Sangat Baik', 5),
	(17, 'Administrasi', 'Baik', 4),
	(18, 'Administrasi', 'Cukup', 3),
	(19, 'Administrasi', 'Kurang', 2),
	(20, 'Administrasi', 'Sangat Kurang', 1),
	(21, 'Supervisi', 'Sangat Baik', 5),
	(22, 'Supervisi', 'Baik', 4),
	(23, 'Supervisi', 'Cukup', 3),
	(24, 'Supervisi', 'Kurang', 2),
	(25, 'Supervisi', 'Sangat Kurang', 1);

-- Dumping structure for table topsis.periode
CREATE TABLE IF NOT EXISTS `periode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `periode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table topsis.periode: ~2 rows (approximately)
DELETE FROM `periode`;
INSERT INTO `periode` (`id`, `periode`) VALUES
	(1, 'Ganjil 2021'),
	(2, 'Genap 2021');

-- Dumping structure for table topsis.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table topsis.user: ~0 rows (approximately)
DELETE FROM `user`;
INSERT INTO `user` (`id`, `nama`, `username`, `password`, `email`) VALUES
	(1, 'Admin', 'Admin', 'e10adc3949ba59abbe56e057f20f883e', 'adm@adm.com'),
	(5, 'x', 'x', '202cb962ac59075b964b07152d234b70', '');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
