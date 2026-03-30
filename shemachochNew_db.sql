-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 30, 2026 at 05:05 PM
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
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `acc_num` int(11) NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL,
  `p_num` varchar(15) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `balance` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`acc_num`, `f_name`, `l_name`, `p_num`, `pwd`, `balance`) VALUES
(123, '123', '123', '123', '123', 13.00),
(156, '156', '156', '156', '$2y$10$xvAAtSi33r6ZvLIL8qxnROTcti2H6mhZcTepA6ncaOsXkNLJ71rL2', 49999999760.00),
(456, '456', '456', '456', '$2y$10$LSG68KHLy4NRnpttwd31zu00gCKZee6SmKTf8nR1g474IvLHgtPMe', 456.00),
(789, '789', '789', '789', '$2y$10$SBFFp4qKJH.HkO6xK/z.Qu7T70wWEHvCpkgfJGStKZG71VuMJOV/G', 789.00),
(12345678, 'Test', 'BankUser', '0911111111', 'hashedpass', 3800.00);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `coupon_number` varchar(50) NOT NULL,
  `state` enum('assigned','unassigned','used') NOT NULL DEFAULT 'unassigned',
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_number`, `state`, `user_id`, `created_at`, `expiry_date`) VALUES
(1, '123', 'assigned', 2, '2025-12-29 04:48:37', '0000-00-00'),
(3, '2025', 'unassigned', NULL, '2025-12-29 06:50:27', '2026-12-31'),
(5, '1234', 'assigned', 9, '2025-12-31 06:40:15', '2025-12-31'),
(7, '123456789', 'unassigned', NULL, '2025-12-31 06:41:12', '2025-12-31'),
(8, '12', 'assigned', 11, '2025-12-31 06:41:12', '2025-12-31'),
(9, '1', 'unassigned', NULL, '2025-12-31 06:41:12', '2025-12-31'),
(10, '12345', 'assigned', 4, '2025-12-31 06:41:12', '2025-12-31'),
(11, '123456', 'assigned', 5, '2025-12-31 06:41:12', '2025-12-31'),
(12, '1234567', 'assigned', 6, '2025-12-31 06:41:12', '2025-12-31'),
(13, '12345678', 'unassigned', NULL, '2025-12-31 06:41:12', '2025-12-31'),
(14, '2111', 'assigned', 12, '2026-01-01 06:28:33', '2028-01-19'),
(18, '2117', 'assigned', 17, '2026-01-01 06:29:35', '2028-01-19'),
(19, '2112', 'unassigned', NULL, '2026-01-01 06:29:35', '2028-01-19'),
(20, '2113', 'unassigned', NULL, '2026-01-01 06:29:35', '2028-01-19'),
(21, '2114', 'unassigned', NULL, '2026-01-01 06:29:35', '2028-01-19'),
(22, '2115', 'unassigned', NULL, '2026-01-01 06:29:35', '2028-01-19'),
(23, '2116', 'unassigned', NULL, '2026-01-01 06:29:35', '2028-01-19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` enum('Available','Unavailable','Coming Soon','Archived') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `one_per_customer` tinyint(1) NOT NULL DEFAULT 0,
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `quantity`, `status`, `image_url`, `one_per_customer`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 'Hi', '', 67.00, 12, 'Archived', 'uploads/6954cecb436ca_Screenshot (15).png', 0, NULL, '2025-12-31 07:06:03', '2025-12-31 19:43:21'),
(2, 'ho', 'ho', 12.00, 13, 'Archived', '', 0, NULL, '2025-12-31 07:13:39', '2025-12-31 19:43:21'),
(3, 'ty', '', 781.00, 20, 'Archived', 'uploads/6954cf577b8af_Screenshot (26).png', 0, NULL, '2025-12-31 07:23:03', '2025-12-31 19:43:20'),
(4, 'ko', '', 1.00, 15, 'Archived', 'uploads/6954cf72427b4_Screenshot (19).png', 0, NULL, '2025-12-31 07:23:30', '2025-12-31 18:45:24'),
(5, 'hello', '', 60.00, 8, 'Archived', 'uploads/6955232741dea_AAPsZHZchNrv6.png', 0, NULL, '2025-12-31 13:20:39', '2025-12-31 18:42:49'),
(6, 'yiuo', '', 10.00, 8, 'Archived', '', 0, NULL, '2025-12-31 13:22:22', '2025-12-31 18:15:04'),
(7, 'yoyo', '', 100.00, 2322, 'Archived', '', 1, NULL, '2025-12-31 13:49:25', '2025-12-31 18:15:00'),
(8, 'Doro and 10 enkulal', '', 1213.00, 12, 'Archived', 'uploads/695567b6cf8c8_photo_2025-12-15_15-07-18.jpg', 0, NULL, '2025-12-31 18:13:10', '2025-12-31 18:14:52'),
(9, 'bole', 'bole fhalksdfh sajhfe lknd;ofs he', 123.00, 1, 'Archived', '', 1, NULL, '2025-12-31 19:12:37', '2025-12-31 19:43:19'),
(10, '5 liter Omar oil', '', 1900.00, 32, 'Unavailable', 'uploads/6956431e67344_69557bc1ea6fb_9574ebb1-f91e-4231-9dbd-85a3caf213be-removebg-preview.png', 0, NULL, '2025-12-31 19:34:51', '2026-01-01 09:49:18'),
(11, '5 kilo Duket', '', 345.00, 10, 'Available', 'uploads/6956441e6f139_6956187b491e1_burlap-sack-of-flour-with-wheat-removebg-preview.png', 1, NULL, '2025-12-31 19:38:41', '2026-01-01 09:53:34'),
(12, '5 kilo Sugar', '', 123.00, 6, 'Available', 'uploads/695586e6de6c1_10625311EA-checkers515Wx515H.png', 1, NULL, '2025-12-31 20:26:14', '2025-12-31 20:38:22'),
(13, 'pasta', '', 12.00, 343, 'Coming Soon', 'uploads/695644c82bf04_food3049781605518668858057-removebg-preview.png', 0, NULL, '2025-12-31 20:26:42', '2026-01-01 09:56:24'),
(14, '1kg Tesfaye Peanut Butter', NULL, 670.00, 123, 'Available', 'uploads/695644048a4c3_69558c2ec0dc3_152-removebg-preview.png', 0, NULL, '2025-12-31 20:48:46', '2026-01-01 09:53:08'),
(15, '1 Killo Buna ', NULL, 1900.00, 34, 'Available', 'uploads/695618f0db5b1_Coffee-beans-in-sack-582cd4c93df78c6f6a9f903a.jpg', 0, NULL, '2026-01-01 06:49:20', '2026-01-01 06:49:20'),
(16, '5 litter Largo', NULL, 200.00, 23, 'Available', 'uploads/69564435457fd_69561916b90d2_a154f8bf-c25b-4e1d-a83b-c011114bdf48-removebg-preview.png', 0, NULL, '2026-01-01 06:49:58', '2026-01-01 09:53:57'),
(17, 'Anbesa Tea', NULL, 20.00, 321, 'Available', 'uploads/69564305d878e_69561972937c9_AnbessaTea-Ethiopiantea-from-yabeto-removebg-preview.png', 0, NULL, '2026-01-01 06:51:30', '2026-01-01 09:48:53'),
(18, 'Mama Milk', NULL, 70.00, 49, 'Available', 'uploads/695643ed327c2_69561a0258970_FB_IMG_1705213210286-1-removebg-preview.png', 0, NULL, '2026-01-01 06:53:54', '2026-03-30 14:58:27'),
(19, '555 Soap', NULL, 70.00, 211, 'Available', 'uploads/695642fb870d6_69561a2ca4b1d_ffc2ff02-ab57-49c8-9678-0aec0c4ecc81-removebg-preview.png', 0, NULL, '2026-01-01 06:54:36', '2026-01-01 09:48:43'),
(20, '5 kilo Shinkurt ', NULL, 200.00, 316, 'Available', 'uploads/695642e91eb2a_69561a5c0fd20_fresh-onion-500x500-removebg-preview.png', 0, NULL, '2026-01-01 06:55:24', '2026-01-02 05:43:01'),
(21, '30 Eggs', NULL, 200.00, 123, 'Archived', 'uploads/695643e05221d_n0818h16207257468821-removebg-preview.png', 1, NULL, '2026-01-01 06:59:46', '2026-01-02 06:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `status` enum('pending','confirmed','picked_up','canceled') NOT NULL DEFAULT 'pending',
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `customer_id`, `product_id`, `quantity`, `status`, `reservation_date`, `confirmed_at`, `pickup_date`, `notes`) VALUES
(35, 12, 20, 5, 'picked_up', '2026-01-01 07:16:29', NULL, '2026-01-02', NULL),
(36, 8, 19, 1, 'picked_up', '2026-01-01 08:40:13', NULL, '2026-01-01', NULL),
(41, 12, 20, 1, 'confirmed', '2026-01-02 05:43:01', NULL, NULL, NULL),
(42, 17, 18, 1, 'confirmed', '2026-03-30 14:58:27', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `acc_num` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL CHECK (`amount` > 0),
  `balance_after` decimal(15,2) NOT NULL,
  `status` enum('failed','pending','completed') NOT NULL DEFAULT 'pending',
  `made_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `acc_num`, `amount`, `balance_after`, `status`, `made_at`) VALUES
(3, 4, 123, 12.00, 32.00, 'completed', '2025-12-31 10:38:08'),
(4, 4, 123, 1.00, 31.00, 'completed', '2025-12-31 10:38:18'),
(5, 4, 123, 1.00, 30.00, 'completed', '2025-12-31 12:20:47'),
(6, 2, 156, 12.00, 144.00, 'completed', '2025-12-31 13:24:09'),
(7, 2, 156, 1.00, 143.00, 'completed', '2025-12-31 13:24:21'),
(8, 6, 123, 1.00, 29.00, 'completed', '2025-12-31 13:26:21'),
(9, 2, 156, 1.00, 142.00, 'completed', '2025-12-31 16:14:40'),
(10, 2, 156, 60.00, 82.00, 'completed', '2025-12-31 16:20:57'),
(11, 2, 156, 12.00, 70.00, 'completed', '2025-12-31 16:37:38'),
(12, 2, 156, 12.00, 58.00, 'completed', '2025-12-31 16:37:42'),
(13, 2, 156, 12.00, 46.00, 'completed', '2025-12-31 16:37:44'),
(14, 2, 156, 100.00, 49999999900.00, 'completed', '2025-12-31 16:51:14'),
(15, 9, 123, 1.00, 28.00, 'completed', '2025-12-31 17:27:27'),
(16, 9, 123, 1.00, 27.00, 'completed', '2025-12-31 17:27:49'),
(17, 9, 123, 1.00, 26.00, 'completed', '2025-12-31 17:27:57'),
(18, 11, 123, 12.00, 14.00, 'completed', '2025-12-31 17:49:48'),
(19, 7, 123, 1.00, 13.00, 'completed', '2025-12-31 22:49:37'),
(20, 12, 12345678, 200.00, 4800.00, 'completed', '2026-01-01 10:16:29'),
(21, 8, 156, 70.00, 49999999830.00, 'completed', '2026-01-01 11:40:13'),
(22, 12, 12345678, 200.00, 4600.00, 'completed', '2026-01-02 08:35:43'),
(23, 12, 12345678, 200.00, 4400.00, 'completed', '2026-01-02 08:36:01'),
(24, 12, 12345678, 200.00, 4200.00, 'completed', '2026-01-02 08:38:19'),
(25, 12, 12345678, 200.00, 4000.00, 'completed', '2026-01-02 08:39:39'),
(26, 12, 12345678, 200.00, 3800.00, 'completed', '2026-01-02 08:43:01'),
(27, 17, 156, 70.00, 49999999760.00, 'completed', '2026-03-30 17:58:27');

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

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preference_key` varchar(255) NOT NULL,
  `preference_value` text NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`acc_num`),
  ADD UNIQUE KEY `p_num` (`p_num`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_number` (`coupon_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`,`product_id`,`status`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `acc_num` (`acc_num`);

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
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`preference_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`acc_num`) REFERENCES `bank` (`acc_num`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`acc_num`) REFERENCES `bank` (`acc_num`) ON DELETE NO ACTION;

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
