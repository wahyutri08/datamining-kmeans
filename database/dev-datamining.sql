-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2024 at 07:15 PM
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
-- Database: `dev-datamining`
--

-- --------------------------------------------------------

--
-- Table structure for table `atribut`
--

CREATE TABLE `atribut` (
  `id_atribut` int(11) NOT NULL,
  `nama_atribut` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atribut`
--

INSERT INTO `atribut` (`id_atribut`, `nama_atribut`) VALUES
(1, 'BCA'),
(2, 'BNI'),
(3, 'CIMB'),
(4, 'MANDIRI');

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE `cluster` (
  `id_cluster` int(11) NOT NULL,
  `nama_cluster` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cluster`
--

INSERT INTO `cluster` (`id_cluster`, `nama_cluster`) VALUES
(1, 'Cluster 1'),
(2, 'Cluster 2'),
(3, 'Cluster 3');

-- --------------------------------------------------------

--
-- Table structure for table `kelurahan`
--

CREATE TABLE `kelurahan` (
  `id_kelurahan` int(11) NOT NULL,
  `nama_kelurahan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelurahan`
--

INSERT INTO `kelurahan` (`id_kelurahan`, `nama_kelurahan`) VALUES
(1, 'Cengkareng Barat'),
(2, 'Cengkareng Timur'),
(3, 'Duri Kosambi'),
(4, 'Grogol'),
(5, 'Grogol Utara'),
(6, 'Jelambar Baru'),
(7, 'Joglo'),
(8, 'Kalideres'),
(9, 'Kapuk'),
(10, 'Keagungan'),
(11, 'Kebon Jeruk'),
(12, 'Kemanggisan '),
(13, 'Kembangan Selatan'),
(14, 'Meruya Utara (Ilir) '),
(15, 'Pegadungan'),
(16, 'Rawa Buaya'),
(17, 'Semanan'),
(18, 'Taman Sari '),
(19, 'Tanjung Duren Selatan'),
(20, 'Tanjung Duren Utara'),
(21, 'Tegal Alur'),
(22, 'Tomang'),
(23, 'Cempaka Putih Barat'),
(24, 'Cempaka Putih Timur'),
(25, 'Gelora'),
(26, 'Gondangdia'),
(27, 'Karet Tengsin'),
(28, 'Kebon Sirih'),
(29, 'Senayan'),
(30, 'Cikini'),
(31, 'Bangka'),
(32, 'Bintaro'),
(33, 'Ciganjur'),
(34, 'Cilandak Barat '),
(35, 'Cilandak Timur'),
(36, 'Cipedak'),
(37, 'Cipete Selatan'),
(38, 'Cipete Utara'),
(39, 'Duren Tiga '),
(40, 'Gandaria Selatan'),
(41, 'Guntur'),
(42, 'Jagakarsa'),
(43, 'Jati Padang'),
(44, 'Kalibata'),
(45, 'Karet'),
(46, 'Karet Kuningan'),
(47, 'Karet Semanggi'),
(48, 'Kebayoran Lama Selatan'),
(49, 'Kebayoran Lama Utara'),
(50, 'Kebon Baru'),
(51, 'Kramat Pela'),
(52, 'Kuningan Timur'),
(53, 'Lebak Bulus'),
(54, 'Lenteng Agung'),
(55, 'Menteng'),
(56, 'Menteng Dalam'),
(57, 'Pancoran'),
(58, 'Pasar Minggu'),
(59, 'Pejaten Barat'),
(60, 'Pengadegan '),
(61, 'Petogogan'),
(62, 'Pondok Labu'),
(63, 'Pondok Pinang'),
(64, 'Ragunan'),
(65, 'Rawa Barat'),
(66, 'Rawajati (Rawa Jati)'),
(67, 'Selong'),
(68, 'Srengseng Sawah'),
(69, 'Tanjung Barat'),
(70, 'Tebet Barat'),
(71, 'Tebet Timur'),
(72, 'Ceger'),
(73, 'Kayu Putih'),
(74, 'Kramatjati'),
(75, 'Kelapa Gading Barat'),
(76, 'Kelapa Gading Timur'),
(77, 'Pluit');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_cluster`
--

CREATE TABLE `nilai_cluster` (
  `id_atribut` int(11) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `id_nilai_cluster` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_cluster`
--

INSERT INTO `nilai_cluster` (`id_atribut`, `id_cluster`, `id_nilai_cluster`, `nilai`) VALUES
(1, 1, 46, 350),
(2, 1, 47, 11),
(3, 1, 48, 1),
(4, 1, 49, 14),
(1, 2, 50, 419),
(2, 2, 51, 15),
(3, 2, 52, 19),
(4, 2, 53, 15),
(1, 3, 54, 3),
(2, 3, 55, 23),
(3, 3, 56, 20),
(4, 3, 57, 110);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_kelurahan`
--

CREATE TABLE `nilai_kelurahan` (
  `id_atribut` int(11) NOT NULL,
  `id_nilai_kelurahan` int(11) NOT NULL,
  `id_kelurahan` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_kelurahan`
--

INSERT INTO `nilai_kelurahan` (`id_atribut`, `id_nilai_kelurahan`, `id_kelurahan`, `nilai`) VALUES
(1, 10, 1, 383),
(2, 11, 1, 210),
(3, 12, 1, 14),
(4, 13, 1, 120),
(2, 14, 2, 103),
(1, 15, 2, 314),
(3, 16, 2, 9),
(4, 17, 2, 155),
(1, 18, 3, 233),
(2, 19, 3, 14),
(3, 21, 3, 1),
(1, 22, 4, 0),
(4, 23, 3, 4),
(2, 24, 4, 25),
(3, 25, 4, 0),
(4, 26, 4, 4),
(1, 27, 5, 1),
(2, 28, 5, 8),
(3, 29, 5, 5),
(4, 30, 5, 143),
(1, 31, 6, 2),
(2, 32, 6, 1),
(3, 33, 6, 0),
(4, 34, 6, 1),
(1, 35, 7, 0),
(2, 36, 7, 1),
(3, 37, 7, 1),
(4, 38, 7, 5),
(1, 39, 8, 350),
(2, 40, 8, 11),
(3, 41, 8, 1),
(4, 42, 8, 14),
(1, 43, 9, 132),
(2, 44, 9, 1),
(3, 45, 9, 0),
(4, 46, 9, 3),
(1, 47, 10, 0),
(2, 48, 10, 5),
(3, 49, 10, 6),
(4, 50, 10, 21),
(1, 51, 11, 0),
(2, 52, 11, 9),
(3, 53, 11, 2),
(4, 54, 11, 14),
(1, 55, 12, 0),
(2, 56, 12, 7),
(3, 57, 12, 2),
(4, 58, 12, 16),
(1, 59, 13, 1),
(2, 60, 13, 12),
(3, 61, 13, 11),
(4, 62, 13, 102),
(1, 63, 14, 3),
(2, 64, 14, 8),
(3, 65, 14, 0),
(4, 66, 14, 7),
(1, 67, 15, 285),
(2, 68, 15, 4),
(3, 69, 15, 0),
(4, 70, 15, 37),
(1, 71, 16, 148),
(2, 72, 16, 0),
(3, 73, 16, 0),
(4, 74, 16, 0),
(1, 75, 17, 111),
(2, 76, 17, 2),
(3, 77, 17, 2),
(4, 78, 17, 0),
(1, 79, 18, 0),
(2, 80, 18, 4),
(3, 81, 18, 0),
(4, 82, 18, 24),
(1, 83, 19, 3),
(2, 84, 19, 33),
(3, 85, 19, 14),
(4, 86, 19, 56),
(1, 87, 20, 0),
(2, 88, 20, 3),
(3, 89, 20, 1),
(4, 90, 20, 22),
(1, 91, 21, 184),
(2, 92, 21, 2),
(3, 93, 21, 2),
(4, 94, 21, 11),
(1, 95, 22, 0),
(2, 96, 22, 12),
(3, 97, 22, 13),
(4, 98, 22, 26),
(1, 99, 23, 0),
(2, 100, 23, 0),
(3, 101, 23, 4),
(4, 102, 23, 6),
(1, 103, 24, 1),
(2, 104, 24, 0),
(3, 105, 24, 2),
(4, 106, 24, 1),
(1, 107, 25, 0),
(2, 108, 25, 52),
(3, 109, 25, 6),
(4, 110, 25, 135),
(1, 111, 26, 0),
(2, 112, 26, 29),
(3, 113, 26, 10),
(4, 114, 26, 30),
(1, 115, 27, 0),
(2, 116, 27, 25),
(3, 117, 27, 4),
(4, 118, 27, 26),
(1, 119, 28, 1),
(2, 120, 28, 3),
(3, 121, 28, 8),
(4, 122, 28, 6),
(1, 123, 29, 2),
(2, 124, 29, 63),
(3, 125, 29, 37),
(4, 126, 29, 197),
(1, 127, 30, 0),
(2, 128, 30, 5),
(3, 129, 30, 0),
(4, 130, 30, 103),
(1, 131, 31, 0),
(2, 132, 31, 130),
(3, 133, 31, 50),
(4, 134, 31, 150),
(1, 135, 32, 20),
(2, 136, 32, 13),
(3, 137, 32, 5),
(4, 138, 32, 135),
(1, 139, 33, 94),
(2, 140, 33, 2),
(3, 141, 33, 0),
(4, 142, 33, 4),
(1, 143, 34, 419),
(2, 144, 34, 15),
(3, 145, 34, 19),
(4, 146, 34, 15),
(1, 147, 35, 137),
(2, 148, 35, 13),
(3, 149, 35, 42),
(4, 150, 35, 17),
(1, 151, 36, 45),
(2, 152, 36, 2),
(3, 153, 36, 0),
(4, 154, 36, 0),
(1, 155, 37, 205),
(2, 156, 37, 8),
(3, 157, 37, 0),
(4, 158, 37, 15),
(1, 159, 38, 3),
(2, 160, 38, 12),
(3, 161, 38, 2),
(4, 162, 38, 1),
(1, 163, 39, 140),
(2, 164, 39, 0),
(3, 165, 39, 1),
(4, 166, 39, 15),
(1, 167, 40, 106),
(2, 168, 40, 3),
(3, 169, 40, 7),
(4, 170, 40, 7),
(1, 171, 41, 0),
(2, 172, 41, 3),
(3, 173, 41, 4),
(4, 174, 41, 126),
(1, 175, 42, 129),
(2, 176, 42, 3),
(3, 177, 42, 0),
(4, 178, 42, 112),
(1, 179, 43, 143),
(2, 180, 43, 0),
(3, 181, 43, 1),
(4, 182, 43, 0),
(1, 183, 44, 112),
(2, 184, 44, 0),
(3, 185, 44, 0),
(4, 186, 44, 187),
(1, 187, 45, 0),
(2, 188, 45, 4),
(3, 189, 45, 2),
(4, 190, 45, 2),
(1, 191, 46, 2),
(2, 192, 46, 63),
(3, 193, 46, 37),
(4, 194, 46, 112),
(1, 195, 47, 1),
(2, 196, 47, 8),
(3, 197, 47, 5),
(4, 198, 47, 8),
(1, 199, 48, 0),
(2, 200, 48, 10),
(3, 201, 48, 2),
(4, 202, 48, 23),
(1, 203, 49, 3),
(2, 204, 49, 13),
(3, 205, 49, 5),
(4, 206, 49, 33),
(1, 207, 50, 1),
(2, 208, 50, 1),
(3, 209, 50, 3),
(4, 210, 50, 1),
(1, 211, 51, 1),
(2, 212, 51, 16),
(3, 213, 51, 15),
(4, 214, 51, 31),
(1, 215, 52, 0),
(2, 216, 52, 20),
(3, 217, 52, 8),
(4, 218, 52, 18),
(1, 219, 53, 253),
(2, 220, 53, 5),
(3, 221, 53, 8),
(4, 222, 53, 2),
(1, 223, 54, 2),
(2, 224, 54, 1),
(3, 225, 54, 1),
(4, 226, 54, 2),
(1, 227, 55, 0),
(2, 228, 55, 31),
(3, 229, 55, 11),
(4, 230, 55, 17),
(1, 231, 56, 3),
(2, 232, 56, 23),
(3, 233, 56, 20),
(4, 234, 56, 110),
(1, 235, 57, 63),
(2, 236, 57, 0),
(3, 237, 57, 0),
(4, 238, 57, 145),
(1, 239, 58, 30),
(2, 240, 58, 0),
(3, 241, 58, 0),
(4, 242, 58, 0),
(1, 243, 59, 160),
(2, 244, 59, 0),
(3, 245, 59, 3),
(4, 246, 59, 45),
(1, 247, 60, 42),
(2, 248, 60, 0),
(3, 249, 60, 1),
(4, 250, 60, 0),
(1, 251, 61, 4),
(2, 252, 61, 16),
(3, 253, 61, 9),
(4, 254, 61, 0),
(1, 255, 62, 126),
(2, 256, 62, 5),
(3, 257, 62, 1),
(4, 258, 62, 24),
(1, 259, 63, 1),
(2, 260, 63, 38),
(3, 261, 63, 36),
(4, 262, 63, 82),
(1, 263, 64, 97),
(2, 264, 64, 0),
(3, 265, 64, 0),
(4, 266, 64, 11),
(1, 267, 65, 0),
(2, 268, 65, 9),
(3, 269, 65, 11),
(4, 270, 65, 34),
(1, 271, 66, 178),
(2, 272, 66, 0),
(3, 273, 66, 0),
(4, 274, 66, 0),
(1, 275, 67, 5),
(2, 276, 67, 8),
(3, 277, 67, 10),
(4, 278, 67, 26),
(1, 279, 68, 43),
(2, 280, 68, 5),
(3, 281, 68, 0),
(4, 282, 68, 0),
(1, 283, 69, 173),
(2, 284, 69, 9),
(3, 285, 69, 0),
(4, 286, 69, 16),
(1, 287, 70, 0),
(2, 288, 70, 5),
(3, 289, 70, 3),
(4, 290, 70, 22),
(1, 291, 71, 0),
(2, 292, 71, 9),
(3, 293, 71, 0),
(4, 294, 71, 13),
(1, 295, 72, 0),
(2, 296, 72, 0),
(3, 297, 72, 10),
(4, 298, 72, 153),
(1, 299, 73, 0),
(2, 300, 73, 0),
(3, 301, 73, 0),
(4, 302, 73, 31),
(1, 303, 74, 1),
(2, 304, 74, 0),
(3, 305, 74, 5),
(4, 306, 74, 27),
(1, 307, 75, 0),
(2, 308, 75, 0),
(3, 309, 75, 11),
(4, 310, 75, 39),
(1, 311, 76, 0),
(2, 312, 76, 0),
(3, 313, 76, 6),
(4, 314, 76, 8),
(1, 315, 77, 0),
(2, 316, 77, 0),
(3, 317, 77, 15),
(4, 318, 77, 21);

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
  `avatar` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `email`, `password`, `role`, `avatar`) VALUES
(6, 'wahyu.tri', 'Wahyu Tri Susanto', 'admin@admin.com', '$2y$10$aA3gThdqO45Q59kgfwCwVegnDC6rlsvgtcBA03ZUs0EO5qaKN9zUO', 'Admin', '661c0d6082dd3.png'),
(14, 'user.user', 'User1', 'user@user.com', '$2y$10$oQGiKBNskyJd4fTBktMO5ea3EFa/7TEYh9x9MDn0181v7sK.z9ldG', 'User', '661c16ae3468d.jpg');

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
-- Indexes for table `kelurahan`
--
ALTER TABLE `kelurahan`
  ADD PRIMARY KEY (`id_kelurahan`);

--
-- Indexes for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  ADD PRIMARY KEY (`id_nilai_cluster`),
  ADD KEY `fk_nilaicluster_atribut` (`id_atribut`),
  ADD KEY `fk_nilaicluster_cluster` (`id_cluster`);

--
-- Indexes for table `nilai_kelurahan`
--
ALTER TABLE `nilai_kelurahan`
  ADD PRIMARY KEY (`id_nilai_kelurahan`),
  ADD KEY `fk_nilaikelurahan_atribut` (`id_atribut`),
  ADD KEY `fk_nilaikelurahan_kelurahan` (`id_kelurahan`);

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
  MODIFY `id_atribut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  MODIFY `id_nilai_cluster` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `nilai_kelurahan`
--
ALTER TABLE `nilai_kelurahan`
  MODIFY `id_nilai_kelurahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=551;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai_cluster`
--
ALTER TABLE `nilai_cluster`
  ADD CONSTRAINT `fk_nilaicluster_atribut` FOREIGN KEY (`id_atribut`) REFERENCES `atribut` (`id_atribut`),
  ADD CONSTRAINT `fk_nilaicluster_cluster` FOREIGN KEY (`id_cluster`) REFERENCES `cluster` (`id_cluster`);

--
-- Constraints for table `nilai_kelurahan`
--
ALTER TABLE `nilai_kelurahan`
  ADD CONSTRAINT `fk_nilaikelurahan_atribut` FOREIGN KEY (`id_atribut`) REFERENCES `atribut` (`id_atribut`),
  ADD CONSTRAINT `fk_nilaikelurahan_kelurahan` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahan` (`id_kelurahan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
