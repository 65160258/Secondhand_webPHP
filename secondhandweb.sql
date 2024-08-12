-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2024 at 08:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secondhandweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `shipping_method` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `shipping_method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `order_date`, `shipping_method`) VALUES
(31, 6, 3003.00, '2024-08-12 11:52:18', 'Next-Day Delivery'),
(32, 2, 300.00, '2024-08-12 12:53:46', 'Standard Shipping'),
(33, 5, 3000.00, '2024-08-12 13:23:33', 'Express Shipping'),
(34, 5, 3000.00, '2024-08-12 13:37:47', 'Next-Day Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(26, 31, 2, 1),
(28, 33, 1, 1),
(29, 34, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `seller_id`) VALUES
(1, 'รถ', 'รถเก่า', 3000.00, 'https://s.isanook.com/au/0/ud/13/65117/103.jpg', NULL),
(2, 'กล้อง', 'กล้องเก่า', 300.00, 'https://promotions.co.th/wp-content/uploads/2023/06/Sony-Cybershot-DSC-W220.jpg', NULL),
(7, 'เสื้อ', 'เสื้อเก่า', 20.00, 'https://down-th.img.susercontent.com/file/2a3855cb1831ea406dceb043ccff578f', NULL),
(8, 'รถเก่าๆ', 'รถเก่าๆ', 5000.00, 'https://static.thairath.co.th/media/HCtHFA7ele6Q2dULVOFm1XWVkV5epIh8lGpZiURbIeadVNRI2n94MhvIbErF3Dbgjg.webp', NULL),
(9, 'กางเกงยีนส์', 'กางเกงยีนส์เก่า', 1000.00, 'https://petmaya.com/wp-content/uploads/2022/10/levi-1880-01.jpg', NULL),
(10, 'Logitech G G403', 'เมาส์เก่า', 10.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS9etA0hTfwv2nkhjQCQcKuqVfhPW5tSrOCkA&s', NULL),
(11, 'จอ DELL 20', 'จอเก่าๆ', 5.00, 'https://inwfile.com/s-dn/ngm9qx.jpg', NULL),
(12, 'จอมือสอง LED IPS 27 นิ้ว', 'จอมือสอง LED IPS 27 นิ้ว', 79.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQFF1VN80tK3XL6_TJaUy6R8B53Qc-TUSJ98A&s', NULL),
(13, 'การ์ดจอ Nvidia Quadro 600 1Gb', 'การ์ดจอ Nvidia Quadro 600 1Gb', 43.00, 'https://inwfile.com/s-di/3welhn.jpg', NULL),
(14, 'Nvidia Quadro600 1GB', 'Nvidia Quadro600 1GB', 30.00, 'https://down-th.img.susercontent.com/file/18b4af4ad2962858615f3272de1670c1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('buyer','seller') NOT NULL DEFAULT 'buyer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `role`) VALUES
(1, 'ttser123', '$2y$10$2lWc1CXMrGgvvnveMD64LerYx.fmzvD66Eh0ljkTaJc4Np2UAxYaC', 'ttser123@gmail.com', '2024-08-12 02:30:16', 'buyer'),
(2, 'pp', '$2y$10$RFRfxxNtxBMHFnImhDzMe.yJwGit2i7eZFLYJj2Ve5ALQ/wRSsl8C', 'pp@gmail.com', '2024-08-12 02:33:23', 'buyer'),
(3, 'tt', '$2y$10$dkP1GFsqfp33LKHAe9SkAOkxSAWXY71HSblMnPtueHX692k85vtdK', 'tt@gmail.com', '2024-08-12 02:44:21', 'buyer'),
(4, 'rr', '$2y$10$Nqq2Fnoeknr0duy3K3U4XOVKpCEWTCR4c0mziwm0TpKwLX9eR2Z0G', 'rr@gmail.com', '2024-08-12 03:22:20', 'buyer'),
(5, 'aa', '$2y$10$ABlIU3Ncr0W7LVAUJN/Fu.FU20OcsKi3mzGaKgK.jbBIHnKCmfaAK', 'aa@gmail.com', '2024-08-12 03:49:40', 'buyer'),
(6, 'bb', '$2y$10$cY2sSNXenWNmEp3T4q0GXOjTH3Hi1j4EwsHX.DvjqL4sTrRsqng7K', 'bb@gmail.com', '2024-08-12 04:37:18', 'buyer'),
(7, 'gg', '$2y$10$e1I.LJcy7hLOxuX/8bQXee594gytm5RMfZaGd45QMjlKeeVpB6/aO', 'gg@gmail.com', '2024-08-12 05:26:09', 'buyer'),
(8, 'ss', '$2y$10$prTxah1ViOnk9APNHUzdDeMn/VLdH3smkfa.WBs3FJ.Yi9ZC72.6.', 'ss@gmail.com', '2024-08-12 05:28:48', 'seller'),
(9, 'uu', '$2y$10$8zXibqepI6WbmXjAR6d63OBiEjD1H53AV34uMIONTScf.qIihW5s2', 'uu@gmail.com', '2024-08-12 06:06:03', 'seller');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
