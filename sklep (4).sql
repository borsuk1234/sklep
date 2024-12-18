-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 11:55 AM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elo`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `session_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(2, 'Zegarki damskie'),
(4, 'Zegarki dziecięce'),
(1, 'Zegarki męskie'),
(3, 'Zegarki sportowe');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `products` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('new','processing','completed','cancelled') DEFAULT 'new',
  `payment_method` enum('credit_card','paypal','bank_transfer') DEFAULT 'credit_card',
  `delivery_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `first_name`, `last_name`, `address`, `phone`, `customer_id`, `products`, `total_price`, `status`, `payment_method`, `delivery_date`, `created_at`) VALUES
(1, '', '', '', '', 1, 'sadsadsad', 242.00, 'completed', 'credit_card', NULL, '2024-11-28 13:27:53'),
(2, 'Jan', 'Kowalski', 'ul. Polna 12, 00-123 Warszawa', '123456789', 1, 'Zegarek męski, Zegarek sportowy', 549.00, 'new', 'paypal', '2024-12-15', '2024-12-04 12:46:00'),
(8, '', '', '', '', 0, '[{\"id\":3,\"name\":\"Zegarek sportowy\",\"price\":\"299.00\"},{\"id\":7,\"name\":\"Zegarek tommy\",\"price\":\"1000.00\"}]', 1299.00, 'new', '', NULL, '2024-12-04 16:28:29'),
(9, '', '', '', '', 0, '[{\"id\":3,\"name\":\"Zegarek sportowy\",\"price\":\"299.00\"},{\"id\":7,\"name\":\"Zegarek tommy\",\"price\":\"1000.00\"}]', 1299.00, 'new', '', NULL, '2024-12-04 16:29:22'),
(10, '', '', '', '', 0, '[{\"id\":8,\"name\":\"sikor\",\"price\":\"200000.02\"}]', 200000.02, 'new', '', NULL, '2024-12-04 16:31:05'),
(11, '', '', '', '', 0, '[{\"id\":8,\"name\":\"sikor\",\"price\":\"200000.02\"}]', 200000.02, 'new', '', NULL, '2024-12-04 16:34:15'),
(12, '', '', '', '', 0, '[{\"id\":8,\"name\":\"sikor\",\"price\":\"200000.02\"}]', 200000.02, 'new', '', NULL, '2024-12-04 16:37:50'),
(13, '', '', '', '', 0, '[{\"id\":3,\"name\":\"Zegarek sportowy\",\"price\":\"299.00\"}]', 299.00, 'new', '', NULL, '2024-12-04 16:38:34'),
(14, '', '', '', '', 0, '[{\"id\":3,\"name\":\"Zegarek sportowy\",\"price\":\"299.00\"}]', 299.00, 'new', '', NULL, '2024-12-04 16:38:39');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT 'uploads/default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_path`, `created_at`) VALUES
(2, 'Zegarek damski', 'sasd', 349.20, 'watch2.png', '2024-11-26 16:06:56'),
(3, 'Zegarek sportowy', 'Zegarek sportowy', 299.00, 'watch3.png', '2024-11-26 16:06:56'),
(7, 'Zegarek tommy', 'fajny sikorek', 1000.00, 'tommy.png', '2024-11-27 16:12:09'),
(8, 'sikor', 'zloty sikor', 200000.02, 'sikor.png', '2024-11-27 16:12:28'),
(17, 'zegarek ciemny', 'bardzo czarny', 200.00, 'elo.png', '2024-12-04 21:23:32'),
(18, 'drogi', 'mega drogi zegarek ', 1000000.00, 'elko.png', '2024-12-04 21:24:51'),
(19, 'brydki', 'tak', 2.00, '1.png', '2024-12-05 10:53:03'),
(20, 'elo', 'sasasas', 300.00, '2.png', '2024-12-05 10:53:39'),
(21, 'siema', 'asasasa', 2.00, '3.png', '2024-12-05 10:54:11'),
(22, 'asasan', 'asasasas', 30000.00, '4.png', '2024-12-05 10:54:11'),
(23, 'aoksap[s', '12121212', 12222.00, '5.png', '2024-12-05 10:54:45'),
(24, 'asasasa', 'sasasasa', 12114121.00, '6.png', '2024-12-05 10:54:45'),
(25, 'asasa', 'sha9a9hduun', 9891.00, '7.png', '2024-12-05 10:55:20'),
(26, 'pa', 'asasaad', 872121.00, '8.png', '2024-12-05 10:55:20'),
(27, 'asoijasas', 'asasasas', 6000.00, '9.png', '2024-12-05 10:55:34');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`) VALUES
(19, 3, 1),
(20, 3, 3),
(33, 2, 2),
(35, 7, 4),
(36, 7, 1),
(38, 8, 2),
(39, 8, 1),
(40, 8, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product_parameters`
--

CREATE TABLE `product_parameters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','employee') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$qNm9UmX5qkOTMNIztFgw8.f6lQttfdhjO2FKTKyWs0.Qq7GQE6Ii.', 'admin'),
(2, 'szmata', '$2y$10$1uRMCsyrvOd6votSvfIY..F3/0FvpbeXM5sn777qmzD31zHw0/fR.', 'employee'),
(3, 'cos', '$2y$10$eHMa60LchVheV8XQQbkfHufySdkAd6T6TPBYm.AvDmFcxy5oIgR2S', 'employee'),
(6, 'ktos', '$2y$10$47nn013UMdcbmMDC6X7ypeBDTTXHV5CKXdzOlw7mTiXY/xFmwiDHK', 'user'),
(12, 'bambo', '$2y$10$GsdNDkrEk3wkdwxOPdLy4OTQeTxCFj.6RLIZ90qhIa2.MI1eJ/kKK', 'user'),
(16, 'kuba', '$2y$10$j3J.2ht5ll8xwje0Sdx7iuNwaJP3nnHy4T7VXYJfdx8tGI6N2dymW', 'user'),
(30, 'jaca', '$2y$10$gitcOaPNjFbpKiY4SEdzTerXdGS8CiHlkUdVTIqC7xmhnI/ECZONW', 'employee'),
(31, 'bosuk', '$2y$10$aqhOZVBlYbpQwExIyI3S4e/n95d44neaBIlnjJvN6PmOEUI1mY2G6', 'user'),
(32, 'siema', '$2y$10$JpPYaNECkGvlQXIuwnVlZOIyFXWVJ5Ab09bsL1Z/QIOn6aTZR.O4q', 'user'),
(35, 'dawid12', '$2y$10$5OkxrUNErqO2KeeMVfbarubuaKVTog22EEHkccMOXcpTtrgpVUhey', 'user');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeksy dla tabeli `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeksy dla tabeli `product_parameters`
--
ALTER TABLE `product_parameters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `product_parameters`
--
ALTER TABLE `product_parameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
