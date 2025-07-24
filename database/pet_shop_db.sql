-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 04:45 AM
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
-- Database: `pet_shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(30) NOT NULL,
  `client_id` int(30) NOT NULL,
  `inventory_id` int(30) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `client_id`, `inventory_id`, `price`, `quantity`, `date_created`) VALUES
(7, 3, 11, 300, 1, '2025-03-18 22:34:55'),
(8, 3, 10, 120, 2, '2025-03-18 22:35:14'),
(12, 4, 17, 300, 1, '2025-06-03 11:52:04'),
(18, 21, 15, 1200, 2, '2025-06-23 00:25:15'),
(19, 45, 26, 1500, 3, '2025-06-29 13:35:01'),
(20, 57, 26, 1500, 1, '2025-06-29 15:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `category` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `description`, `status`, `date_created`) VALUES
(1, 'Food', 'Sample Description', 1, '2021-06-21 10:17:41'),
(4, 'Accessories', '&lt;p&gt;Sample Category&lt;/p&gt;', 1, '2021-06-21 16:34:04'),
(5, 'Pet', '&lt;p&gt;Buy your pets now&lt;/p&gt;', 1, '2025-03-17 17:08:58'),
(6, 'Item', '&lt;p&gt;Sample description&lt;/p&gt;', 1, '2025-06-29 00:33:47');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(30) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `default_delivery_address` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `firstname`, `lastname`, `gender`, `contact`, `email`, `password`, `default_delivery_address`, `date_created`) VALUES
(1, 'John', 'Smith', 'Male', '09123456789', 'jsmith@sample.com', '1254737c076cf867dc53d60a0364f38e', 'Sample Address', '2021-06-21 16:00:23'),
(3, 'rhey', 'labao', 'Female', '09107135234', 'rheymarlabao@gmail.com', 'ca9808a0ab128b08d9f5b94c00067edc', 'bangkal wakandabaw city', '2025-03-18 22:34:35'),
(4, 'test', 'test', 'Male', '09810841358', 'test@gmail.com', 'cee5ad84c76091a6c1bf50e9d2c1008b', 'Sardonyx Street Alzate Compound', '2025-06-03 10:52:12'),
(5, 'xai', 'glenn', 'Male', '+63 910 234 567', 'xaixai@gmail.com', '928db95a2c28078696f11d863f01fa2e', 'calinan davao city', '2025-06-03 14:00:01'),
(9, 'test', '2', 'Female', '09107135234', 'test2@gmail.com', '662af1cd1976f09a9f8cecc868ccc0a2', 'test2', '2025-06-12 23:14:15'),
(58, 'xai', 'bandala', 'Female', '09107135234', 'xaiglennbandala@gmail.com', '$2y$10$GYKpG6T/khn7vInKcZXIK.vm9o.kc.k8TH1kuC4ZpoaPIW0DlHd1W', 'davao', '2025-06-29 20:09:18'),
(60, 'Rhey Mar', 'Labao', 'Male', '09810841358', 'rheylabao@gmail.com', '$2y$10$YcYKMEHWQWb1FNz63s8wPOyafkexZne20z5hX0NOMRBL/Mr0wX3qK', 'Sardonyx Street Alzate Compound', '2025-06-30 10:39:35');

-- --------------------------------------------------------

--
-- Table structure for table `email_otps`
--

CREATE TABLE `email_otps` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `type` enum('verification','reset','password_change') NOT NULL DEFAULT 'verification',
  `expiry` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_otps`
--

INSERT INTO `email_otps` (`id`, `email`, `otp`, `type`, `expiry`, `used`, `created_at`) VALUES
(11, 'testuser6540@example.com', '524550', 'verification', '2025-06-29 08:42:54', 0, '2025-06-29 06:32:54'),
(12, 'testuser3438@example.com', '977780', 'verification', '2025-06-29 08:43:09', 1, '2025-06-29 06:33:09'),
(13, 'testuser2877@example.com', '394486', 'verification', '2025-06-29 08:43:23', 1, '2025-06-29 06:33:23'),
(22, 'xaiglennbandala@gmail.com', '072205', 'verification', '2025-06-29 14:18:45', 1, '2025-06-29 12:08:45'),
(24, 'rheylabao@gmail.com', '632941', 'verification', '2025-06-30 04:49:13', 1, '2025-06-30 02:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `size` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`, `unit`, `price`, `size`, `date_created`, `date_updated`) VALUES
(1, 1, 50, 'pcs', 250, 'M', '2021-06-21 13:01:30', '2021-06-21 13:05:23'),
(2, 1, 20, 'Sample', 300, 'L', '2021-06-21 13:07:00', NULL),
(3, 4, 150, 'pcs', 500, 'M', '2021-06-21 16:50:37', NULL),
(4, 3, 50, 'pack', 150, 'M', '2021-06-21 16:51:12', NULL),
(5, 5, 30, 'pcs', 50, 'M', '2021-06-21 16:51:35', NULL),
(6, 4, 10, 'pcs', 550, 'L', '2021-06-21 16:51:54', NULL),
(7, 6, 100, 'pcs', 150, 'S', '2021-06-22 15:50:47', NULL),
(8, 6, 150, 'pcs', 180, 'M', '2021-06-22 15:51:13', NULL),
(9, 7, 3, 'kg', 1000, 'M', '2025-03-17 16:44:20', NULL),
(10, 8, 1, 'pcs', 120, 'NONE', '2025-03-17 16:45:12', '2025-06-22 23:17:04'),
(11, 9, -2, 'kg', 300, 'M', '2025-03-17 17:04:57', '2025-06-30 01:30:07'),
(12, 10, 2, 'pcs', 800, 'M', '2025-03-17 17:06:39', '2025-06-04 11:45:32'),
(13, 11, 93, 'pcs', 90, 'S', '2025-03-17 17:07:47', '2025-06-30 01:30:07'),
(15, 13, 1, 'kg', 1200, 'L', '2025-03-17 17:12:42', '2025-06-23 00:25:15'),
(16, 14, 1, 'pcs', 100, 'S', '2025-03-17 17:14:15', '2025-06-12 00:00:41'),
(17, 15, 73, 'pcs', 300, 'NONE', '2025-03-17 17:15:14', '2025-06-30 00:21:26'),
(18, 16, 1, 'pcs', 600, 'NONE', '2025-03-17 17:16:20', '2025-06-30 01:53:58'),
(19, 9, 4, 'kg', 500, 'L', '2025-03-18 22:39:24', NULL),
(21, 18, 193, '', 100, '', '2025-06-11 23:34:05', '2025-06-30 09:55:46'),
(22, 19, 96, '', 1900, '', '2025-06-22 17:31:45', '2025-06-30 09:53:08'),
(23, 20, 100, '', 100, '', '2025-06-23 22:29:44', NULL),
(25, 22, 110, '', 1500, '', '2025-06-23 22:59:33', NULL),
(26, 23, 89, '', 1500, '', '2025-06-27 10:27:29', '2025-06-30 09:53:50'),
(27, 24, 980, '', 10, '', '2025-06-30 00:31:46', '2025-06-30 01:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(30) NOT NULL,
  `client_id` int(30) NOT NULL,
  `delivery_address` text NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `amount` double NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 0,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `delivery_address`, `payment_method`, `amount`, `status`, `paid`, `date_created`, `date_updated`) VALUES
(1, 1, 'Sample Address', 'Online Payment', 1100, 2, 1, '2021-06-22 13:48:54', '2021-06-22 14:49:15'),
(2, 1, 'Sample Address', 'cod', 750, 3, 1, '2021-06-22 15:26:07', '2021-06-22 15:32:55'),
(4, 2, 'lacson ', 'cod', 2000, 3, 1, '2025-03-17 17:00:34', '2025-03-17 17:01:09'),
(5, 4, 'Sardonyx Street Alzate Compound', 'cod', 1200, 2, 0, '2025-06-03 11:06:34', '2025-06-03 11:07:15'),
(6, 4, 'Sardonyx Street Alzate Compound', 'cod', 300, 0, 0, '2025-06-03 11:43:13', NULL),
(7, 6, 'bot ', 'cod', 1100, 0, 0, '2025-06-04 11:45:32', NULL),
(8, 6, 'bot ', 'cod', 0, 0, 0, '2025-06-04 11:52:37', NULL),
(9, 6, 'bot ', 'cod', 0, 0, 0, '2025-06-04 11:52:38', NULL),
(10, 6, 'bot ', 'cod', 0, 0, 0, '2025-06-04 11:52:38', NULL),
(11, 6, 'bot ', 'cod', 0, 0, 0, '2025-06-04 11:52:39', NULL),
(12, 31, 'Penano street', 'cod', 900, 0, 0, '2025-06-23 20:03:30', NULL),
(13, 58, 'davao', 'cod', 200, 4, 0, '2025-06-30 01:09:36', '2025-06-30 01:09:55'),
(14, 58, 'davao', 'cod', 6960, 4, 0, '2025-06-30 01:30:07', '2025-06-30 01:31:57'),
(15, 58, 'davao', 'cod', 640, 4, 0, '2025-06-30 01:30:39', '2025-06-30 01:32:04'),
(16, 58, 'davao', 'cod', 100, 3, 1, '2025-06-30 01:33:31', '2025-06-30 01:34:00'),
(17, 58, 'davao', 'cod', 8100, 3, 1, '2025-06-30 01:53:58', '2025-06-30 01:54:22'),
(18, 59, 'Sardonyx Street Alzate Compound', 'cod', 1900, 4, 0, '2025-06-30 09:53:08', '2025-06-30 09:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE `order_list` (
  `id` int(30) NOT NULL,
  `order_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `size` varchar(20) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `quantity` int(30) NOT NULL,
  `price` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `order_id`, `product_id`, `size`, `unit`, `quantity`, `price`, `total`) VALUES
(1, 1, 4, 'L', 'pcs', 2, 550, 1100),
(2, 2, 3, 'M', 'pack', 5, 150, 750),
(5, 4, 7, 'M', 'kg', 2, 1000, 2000),
(6, 5, 13, 'L', 'kg', 1, 1200, 1200),
(7, 6, 15, 'NONE', 'pcs', 1, 300, 300),
(8, 7, 9, 'M', 'kg', 1, 300, 300),
(9, 7, 10, 'M', 'pcs', 1, 800, 800),
(10, 12, 15, 'NONE', 'pcs', 3, 300, 900),
(11, 13, 18, '', '', 2, 100, 200),
(12, 14, 11, 'S', 'pcs', 5, 90, 450),
(13, 14, 9, 'M', 'kg', 2, 300, 600),
(14, 14, 24, '', '', 21, 10, 210),
(15, 14, 19, '', '', 3, 1900, 5700),
(16, 15, 24, '', '', 64, 10, 640),
(17, 16, 24, '', '', 10, 10, 100),
(18, 17, 23, '', '', 5, 1500, 7500),
(19, 17, 16, 'NONE', 'pcs', 1, 600, 600),
(20, 18, 19, '', '', 1, 1900, 1900);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `sub_category_id` int(30) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sub_category_id`, `product_name`, `description`, `status`, `date_created`) VALUES
(7, 1, 1, 'Nutri Chunks 10kg', '&lt;p&gt;10kg dog food&lt;/p&gt;', 1, '2025-03-17 16:28:00'),
(8, 4, 5, 'Leash', '&lt;p&gt;Leash for cat and dog&lt;/p&gt;', 1, '2025-03-17 16:30:04'),
(9, 4, 5, 'Cat Litter Box', '&lt;p&gt;&lt;span style=&quot;color: rgb(236, 236, 236); font-family: ui-sans-serif, -apple-system, system-ui, &amp;quot;Segoe UI&amp;quot;, Helvetica, &amp;quot;Apple Color Emoji&amp;quot;, Arial, sans-serif, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;; background-color: rgb(33, 33, 33);&quot;&gt;A&amp;nbsp;&lt;/span&gt;&lt;span data-start=&quot;2&quot; data-end=&quot;20&quot; style=&quot;border: 0px solid rgb(227, 227, 227); --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgba(69,89,164,.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; scrollbar-color: var(--main-surface-tertiary) transparent; font-weight: 600; color: rgb(236, 236, 236); font-family: ui-sans-serif, -apple-system, system-ui, &amp;quot;Segoe UI&amp;quot;, Helvetica, &amp;quot;Apple Color Emoji&amp;quot;, Arial, sans-serif, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;; background-color: rgb(33, 33, 33); user-select: text !important;&quot;&gt;cat litter box&lt;/span&gt;&lt;span style=&quot;color: rgb(236, 236, 236); font-family: ui-sans-serif, -apple-system, system-ui, &amp;quot;Segoe UI&amp;quot;, Helvetica, &amp;quot;Apple Color Emoji&amp;quot;, Arial, sans-serif, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;; background-color: rgb(33, 33, 33);&quot;&gt;&amp;nbsp;is a container filled with absorbent material where cats can urinate and defecate. It helps manage waste indoors, keeping homes clean and odor-free. Available in open, covered, and self-cleaning designs, it suits different cat preferences and owner needs.&lt;/span&gt;&lt;/p&gt;', 1, '2025-03-17 17:04:19'),
(10, 4, 5, 'Pet Cage', '&lt;p&gt;A &lt;strong&gt;pet cage&lt;/strong&gt; is an enclosure designed to house and secure pets safely. It provides a comfortable space for animals like birds, rabbits, hamsters, and small dogs. Available in various sizes and materials, pet cages often include ventilation, feeding areas, and bedding for the pet&rsquo;s well-being.&lt;/p&gt;', 1, '2025-03-17 17:06:18'),
(11, 4, 4, 'V-gard shampoo', '&lt;p&gt;Shampoo for dogs&lt;/p&gt;', 1, '2025-03-17 17:07:25'),
(13, 1, 1, 'Top Breed', '&lt;p&gt;Quality Dog Food&lt;/p&gt;', 1, '2025-03-17 17:12:21'),
(14, 4, 6, 'Methylene Blue', '&lt;p&gt;For Fish&lt;/p&gt;', 1, '2025-03-17 17:13:53'),
(15, 5, 8, 'Kohaku Koi', '&lt;p&gt;&lt;span style=&quot;font-family: &amp;quot;Segoe UI Historic&amp;quot;, &amp;quot;Segoe UI&amp;quot;, Helvetica, Arial, sans-serif; font-size: 15px; white-space-collapse: preserve; background-color: rgb(48, 48, 48);&quot;&gt;Kohaku Koi&lt;/span&gt;&lt;/p&gt;', 1, '2025-03-17 17:14:54'),
(16, 4, 6, 'Seabillion Pump', '&lt;p&gt;&lt;span style=&quot;font-family: &amp;quot;Segoe UI Historic&amp;quot;, &amp;quot;Segoe UI&amp;quot;, Helvetica, Arial, sans-serif; font-size: 15px; white-space-collapse: preserve; background-color: rgb(48, 48, 48);&quot;&gt;Seabillion Pump&lt;/span&gt;&lt;/p&gt;', 1, '2025-03-17 17:15:55'),
(18, 5, 8, 'white fish', '&lt;p&gt;sdfsdgfsdgfgfd&lt;/p&gt;', 1, '2025-06-11 23:32:29'),
(19, 5, 8, 'fighting fish', '&lt;p&gt;sdfggsfgf&lt;/p&gt;', 1, '2025-06-11 23:36:00'),
(20, 5, 8, 'oyeee', '&lt;p&gt;hahhahahahaa&lt;/p&gt;', 1, '2025-06-23 22:28:49'),
(22, 1, 7, 'OYEE FOOD', '&lt;p&gt;DFSSDFDSFSDD&lt;/p&gt;', 1, '2025-06-23 22:34:40'),
(23, 5, 8, 'aw aw', '&lt;p&gt;rawr&lt;/p&gt;', 1, '2025-06-27 10:27:21'),
(24, 1, 1, 'fish balls', '&lt;p&gt;lami kayuuuu&lt;/p&gt;', 1, '2025-06-30 00:31:07');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(30) NOT NULL,
  `order_id` int(30) NOT NULL,
  `total_amount` double NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `order_id`, `total_amount`, `date_created`) VALUES
(1, 1, 1100, '2021-06-22 13:48:54'),
(2, 2, 750, '2021-06-22 15:26:07'),
(4, 4, 2000, '2025-03-17 17:00:34'),
(5, 5, 1200, '2025-06-03 11:06:34'),
(6, 6, 300, '2025-06-03 11:43:13'),
(7, 7, 1100, '2025-06-04 11:45:32'),
(8, 12, 900, '2025-06-23 20:03:30'),
(9, 13, 200, '2025-06-30 01:09:36'),
(10, 14, 6960, '2025-06-30 01:30:07'),
(11, 15, 640, '2025-06-30 01:30:39'),
(12, 16, 100, '2025-06-30 01:33:31'),
(13, 17, 8100, '2025-06-30 01:53:58'),
(14, 18, 1900, '2025-06-30 09:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(30) NOT NULL,
  `size` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `size`) VALUES
(1, 'xs'),
(2, 's'),
(3, 'm'),
(4, 'l'),
(5, 'xl'),
(6, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(30) NOT NULL,
  `parent_id` int(30) NOT NULL,
  `sub_category` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `parent_id`, `sub_category`, `description`, `status`, `date_created`) VALUES
(1, 1, 'Dog Food', '&lt;p&gt;Sample only&lt;/p&gt;', 1, '2021-06-21 10:58:32'),
(3, 1, 'Cat Food', '&lt;p&gt;Sample&lt;/p&gt;', 1, '2021-06-21 16:34:59'),
(4, 4, 'Dog Needs', '&lt;p&gt;Sample&amp;nbsp;&lt;/p&gt;', 1, '2021-06-21 16:35:26'),
(5, 4, 'Cat Needs', '&lt;p&gt;Sample&lt;/p&gt;', 1, '2021-06-21 16:35:36'),
(6, 4, 'Fish Needs', '', 1, '2025-03-17 16:49:25'),
(7, 1, 'Fish Food', '', 1, '2025-03-17 16:49:37'),
(8, 5, 'Fish', '&lt;p&gt;Fish&amp;nbsp;&lt;/p&gt;', 1, '2025-03-17 17:10:20'),
(9, 6, 'Dog Items', '&lt;p&gt;Dog Items&lt;/p&gt;', 1, '2025-06-29 00:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(16, 'name', 'Yes yes yow'),
(17, 'short_name', 'Oyeee&apos;s Pet Shop'),
(18, 'logo', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(7, 'admin', '1', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/1750941960_IMG_4878.JPG', NULL, 0, '2025-06-25 12:05:24', '2025-06-26 20:46:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_otps`
--
ALTER TABLE `email_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_type_index` (`email`,`type`),
  ADD KEY `expiry_index` (`expiry`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `email_otps`
--
ALTER TABLE `email_otps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
