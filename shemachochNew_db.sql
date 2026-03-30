-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 30, 2026 at 04:33 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shemachochNew_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` enum('admin','customer') NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `p_num` varchar(15) NOT NULL,
  `h_num` varchar(10) NOT NULL,
  `coupon` varchar(50) DEFAULT NULL,
  `pwd` varchar(255) NOT NULL,
  `acc_num` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `f_name`, `l_name`, `email`, `p_num`, `h_num`, `coupon`, `pwd`, `acc_num`, `created_at`, `updated_at`) VALUES
(2, 'customer', 'Selam', 'Alemu', NULL, 'hi', 'hi', '123', '$2y$12$jIO1L2qLX8arT2/80jheEepRrUV4Nu1huuAS9seX3X2D161vWqZAe', 156, '2025-12-29 06:55:49', '2026-01-01 06:25:56'),
(4, 'customer', 'ji', 'ji', NULL, 'ji', 'ji', '12345', '$2y$12$2NsB1v35U.xlfH0mN0aaDeRPCrNZlVKv/tOGV.m8U6HVeGj1JwNxy', 123, '2025-12-31 07:36:36', '2025-12-31 07:36:36'),
(5, 'customer', 'hello', 'there', NULL, '2323', '2323', '123456', '$2y$12$mKzb.7B6DCVUyQAywira5OMBcFkn8OXUBTqF6AgNMFCd057l6OYsy', 123, '2025-12-31 10:09:15', '2025-12-31 10:09:15'),
(6, 'customer', 'Yakin', 'Tewodros', NULL, '0966805500', '2323', '1234567', '$2y$12$15xpKgdExwOKRLs009kq4eO8DxQsldiuE10szaJ6Wc0ZrEU9wiXHS', 123, '2025-12-31 10:26:00', '2025-12-31 10:26:00'),
(7, 'admin', 'Yakin', 'selam', 'olalao', '0909', '1212', NULL, '12345678', 123, '2025-12-31 13:58:13', '2026-01-01 06:14:27'),
(8, 'customer', 'Selam', 'alemu', 'amooma', '09898989898', 'admin2', '12345678', '$2y$10$9Rjbm.Et0U.TXivmQeWZwubF5oRy/EYla7i/AxkEPJt.GLGBpaW86', 156, '2025-12-31 13:59:25', '2026-01-01 08:06:27'),
(9, 'customer', 'okok', 'okok', NULL, 'okok', '', '1234', '$2y$12$rwPjnFefxyj1WQCylDGPOO6H29pLH4SQwjbgG1ZSpsaGHARXwvtvi', 123, '2025-12-31 14:26:29', '2025-12-31 14:26:29'),
(11, 'customer', 'erq', 'erq', NULL, '123', '', '12', '$2y$12$7YNd7DThc79/TTRh7HBWBOSEeyXI/ro6weAs1PlURbJwbGBGW21M.', 123, '2025-12-31 14:47:31', '2025-12-31 14:47:31'),
(12, 'customer', 'Hiwot', 'alem', NULL, '0909090909', 'House', '2111', '$2y$12$Qt5pj6Atrdy/e1luPkjUIOU8INHwu5vIPwMMJfDC2gc3NpextZVNi', 12345678, '2026-01-01 06:35:24', '2026-01-01 06:35:24'),
(17, 'customer', 'Selam', 'Ethiopia', NULL, '09899898', 'House', '2117', '$2y$12$b9L1SYx4KD00B.SyxBJMaOpbXDLJCVwEFUgAN1vX5jccYz6yGWisS', 156, '2026-03-30 14:17:29', '2026-03-30 14:17:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `p_num` (`p_num`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `coupon` (`coupon`),
  ADD KEY `acc_num` (`acc_num`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`acc_num`) REFERENCES `bank` (`acc_num`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
