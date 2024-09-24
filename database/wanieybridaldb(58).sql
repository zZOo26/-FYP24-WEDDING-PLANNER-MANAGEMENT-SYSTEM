-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2024 at 01:53 PM
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
-- Database: `wanieybridaldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`) VALUES
(1, 'Waniey Bridal', 'wanieybridal@gmail.com', '$2y$10$hbKLjINAu5EENq5szXJVVeovSiIR5OZnJgb42SXWm63U8WAua7FKS');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `app_id` bigint(20) NOT NULL,
  `cust_id` bigint(20) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`app_id`, `cust_id`, `purpose`, `location`, `date`, `time`, `status`, `created_at`) VALUES
(25, 3, 'Fitting ', 'Butik Waniey Bridal', '2024-06-21', '12:00:00', 1, '2024-06-21 16:36:11'),
(26, 3, 'View Hall', 'Qhall Event space', '2024-07-06', '13:03:00', 2, '2024-06-21 16:40:13'),
(27, 3, 'Discussion', 'Butik Waniey Bridal', '2024-07-20', '10:00:00', 0, '2024-06-21 16:56:56'),
(29, 12, 'Fitting', 'Butik Waniey Bridal', '2024-06-24', '12:00:00', 1, '2024-06-22 08:27:02'),
(30, 13, 'Fitting', 'Butik Waniey Bridal', '2024-07-01', '11:00:00', 0, '2024-06-22 09:32:43'),
(31, 3, 'View Hall', 'Qhall Event space', '2024-06-27', '11:00:00', 0, '2024-06-24 17:07:23'),
(32, 14, 'Fitting', 'Butik Waniey Bridal', '2024-07-01', '10:00:00', 0, '2024-06-26 06:25:45'),
(33, 3, 'Fitting', 'Butik Waniey Bridal', '2024-06-29', '10:00:00', 0, '2024-06-26 12:39:05'),
(34, 15, 'Fitting', 'Butik Waniey Bridal', '2024-06-29', '10:00:00', 2, '2024-06-27 02:23:31');

-- --------------------------------------------------------

--
-- Table structure for table `bridal_attire`
--

CREATE TABLE `bridal_attire` (
  `attire_id` bigint(20) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `min_size` varchar(255) NOT NULL,
  `max_size` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `accessories` text NOT NULL,
  `normal_price` decimal(10,2) NOT NULL,
  `deposit` decimal(10,2) NOT NULL,
  `promo_code` varchar(255) NOT NULL DEFAULT 'N/A',
  `attire_desc` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bridal_attire`
--

INSERT INTO `bridal_attire` (`attire_id`, `image`, `name`, `category`, `min_size`, `max_size`, `color`, `accessories`, `normal_price`, `deposit`, `promo_code`, `attire_desc`, `created_at`, `updated_at`) VALUES
(33, '1717434476.png', 'Kebaya Moden Mint Green', 'Songket', 'XS', 'XL', 'Mint Green', 'Keris, Tanjak, Sampin, Bengkung, Kerongsang, Rantai, Veil, Crown, Bunga Tangan (excluded)', 299.00, 299.00, 'N/A', 'Design kebaya moden berkain songket sepenuhnya. ', '2024-06-03 17:07:56', '2024-06-26 12:51:38'),
(36, '1718173909.jpg', 'Songket Thai Maroon', 'Songket', 'XS', 'XL', 'Maroon', 'rantai, gelang pinggang, crown, bunga tngan', 699.00, 699.00, 'N/A', 'songket thai', '2024-06-12 06:31:49', '2024-06-12 06:31:49'),
(39, '1719043673.jpg', 'Royal Yellow Thai', 'Songket', 'XXS', '2XL', 'Yellow', 'Tanjak, Keris, Rantai, Tali Pinggang, Crown, Bunga Tangan', 699.00, 100.00, 'N/A', 'Songket Thai berwarna Kuning Raja yang menawan.', '2024-06-22 08:07:53', '2024-06-26 12:51:13'),
(40, '1719043745.jpg', 'Emerald Green Thai', 'Songket', 'XXS', 'XL', 'Emerald Green', 'Tanjak, Keris, Crown, Gelang pinggang, Bunga Tangan', 699.00, 100.00, 'N/A', 'Songket Thai yang menawan hati para peminat songket.', '2024-06-22 08:09:05', '2024-06-23 10:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `bridal_dais`
--

CREATE TABLE `bridal_dais` (
  `dais_id` bigint(20) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `dais_size` varchar(255) NOT NULL,
  `normal_price` decimal(10,2) NOT NULL,
  `deposit` decimal(10,2) NOT NULL,
  `items` mediumtext NOT NULL,
  `dais_desc` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bridal_dais`
--

INSERT INTO `bridal_dais` (`dais_id`, `image`, `name`, `category`, `dais_size`, `normal_price`, `deposit`, `items`, `dais_desc`, `created_at`, `updated_at`) VALUES
(18, '1717150083.jpg', 'White Flowery Garden', 'Hall/Dewan', '20 - 25ft', 1599.00, 100.00, 'jet t, backdrop, bunga', 'Pelamin bersaiz besar sesuai untuk dewan sahaja.', '2024-05-31 10:08:03', '2024-06-16 06:04:18'),
(20, '1717409266.jpg', 'Pelamin Flowery Single Ring', 'Mini', '8 - 10ft', 399.00, 100.00, 'Backdrop, Jet T, Ring, Roses, Hydrangeas, Apple Leaves, Artificial Grass, Mini Sofa, Artificial Candles, Wooden Stands, Spotlights, Furry Carpet, Bunga Tangan\r\n\r\n\r\n', 'The overall effect is one of opulence, romance, and timeless beauty. The bridal dais with its floral ring backdrop serves as a stunning focal point, perfect for photographs and creating unforgettable memories. This setup ensures that the bride and groom are the center of attention, surrounded by the beauty and symbolism of flowers and the eternal ring.', '2024-06-03 10:07:46', '2024-06-16 06:03:54'),
(23, '1718622483.jpg', 'Pelamin Gold Drape', 'Home/Rumah', '10-12ft', 450.00, 100.00, 'backdrop, sofa, bunga rose, bunga hydrangea, daun, rumput, jet T, carpet', 'Pelamin drape backdrop gold lace. Can custom flower colors.', '2024-06-17 11:08:03', '2024-06-17 11:08:03'),
(24, '1719042867.jpg', 'Pelamin Silver Drape', 'Mini', '8 - 10ft', 350.00, 100.00, 'backdrop, jet T, sofa mini, bunga rose,bunga hydrangea, spotlight, carpet', 'Pelamin mini design drape silver', '2024-06-22 07:54:27', '2024-06-22 07:54:27'),
(25, '1719043003.png', 'Pelamin Rich Gold', 'Hall/Dewan', '20 - 25ft', 790.00, 100.00, 'Sofa Saiz Besar, Backdrop, Hiasan Backdrop, Bunga-Bungaan, Jet T, Lmapu, Spotlight', 'Hiasan gold backdrop', '2024-06-22 07:56:43', '2024-06-22 07:56:43'),
(26, '1719043095.png', 'Pelamin Blue Flowers', 'Hall/Dewan', '20 - 25ft', 750.00, 100.00, 'Sofa, Bunga-bungaan, hiasan tambahan, spotlight, carpet', 'pelamin dewan style blue coral', '2024-06-22 07:58:15', '2024-06-22 07:58:15'),
(27, '1719043532.png', 'Pelamin DIY ', 'DIY', '8-10ft', 250.00, 100.00, 'Backdrop, sofa mini, jet T, bunga-bungaan, carpet, spotlight', 'Pelamin DIY adalah pelamin yang disewa dan tidak termasuk service pemasangan. Pelamin perlu dipasang oleh pihak pelanggan dengan sendiri.', '2024-06-22 08:05:32', '2024-06-26 12:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cust_id` bigint(20) NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phoneNo` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `acc_no` bigint(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cust_id`, `profile_img`, `firstname`, `lastname`, `phoneNo`, `email`, `bank_name`, `acc_no`, `password`, `created_at`) VALUES
(3, '1717350703.jpg', 'Zufaraha', 'Zakaria', 199269554, 'zz@gmail.com', 'Maybank', 17546987265, '$2y$10$KTx3zCgZFkWmpNplgHAD0eWRXKrawZ23.6BZfXcfKQECG9n2YFu02', '2024-05-01 13:34:14'),
(11, '', 'Nursafiya', 'Zailani', 147852369, 'safiya@gmail.com', '', 0, '', '2024-06-21 09:17:26'),
(12, '1719044372.jpg', 'Siti Aminah', 'Ali', 123654789, 'sitiaminah@gmail.com', 'Maybank', 123456789123, '$2y$10$UIrazrqVM5upf4pL335cY.Le1tPScwChvkwAoA9z1KK5l6gQjQ3UG', '2024-06-22 08:18:25'),
(13, '1719048473.jpeg', 'Siti Aishah', 'Kamaruzzaman', 123456789, 'sitiaishah@gmail.com', 'Bank Muamalat', 123045236897, '$2y$10$wJ7b.sE.T7i8FDNK0UBF9eSCuxc26dtPLqPB7uNAT8gm.IrS9apxO', '2024-06-22 09:27:08'),
(14, '', 'Aishah', 'Khadri', 199269544, 'aishah@gmail.com', '', 0, '$2y$10$l.xYrwZpKuyM0DrVpPHkc.FDcWjcOQnYbsXWTbvACftG9OaHGCB/6', '2024-06-26 06:24:53'),
(15, '1719451649.jpeg', 'Suzana', 'Kassim', 123456789, 'suzana@gmail.com', '', 0, '$2y$10$DOsvH6822l6.KbYlzlhFMum2hpGXEivcGPZioY3Msx9Gk1eMKvpIO', '2024-06-27 01:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `event_slot`
--

CREATE TABLE `event_slot` (
  `slot_id` int(10) NOT NULL,
  `start_time` time NOT NULL,
  `slot` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_slot`
--

INSERT INTO `event_slot` (`slot_id`, `start_time`, `slot`) VALUES
(1, '11:00:00', 'Slot Pagi'),
(2, '18:00:00', 'Slot Petang');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` bigint(20) NOT NULL,
  `expense_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `expense_name`, `amount`, `description`, `date_created`, `date_updated`) VALUES
(1, 'Barang NSK ', 295.00, 'barang basah', '2024-03-13 21:18:55', '2024-06-26 12:53:28'),
(2, 'Bunga Hydrangea', 579.00, 'bunga hydrangea baru untuk pelamin', '2024-01-24 09:21:03', '2024-06-26 12:53:50'),
(3, 'Kain Alas Meja', 800.00, 'Kain alas meja putih satu set 10 helai.', '2024-04-27 18:45:40', '2024-04-27 10:45:40'),
(4, 'Modal Katering April2024', 8990.50, 'barang katering termasuk barang basah, kering dan utility.', '2024-04-29 21:11:21', '2024-06-21 13:19:30'),
(5, 'Barang Katering Mei2024', 9099.35, 'barang katering termasuk barang basah, kerign dan utiliti.', '2024-05-31 21:12:14', '2024-06-21 13:19:18'),
(6, 'Bunga Rose Baru', 560.00, 'bunga rose putih dan cream untuk pelamin', '2024-06-21 21:24:19', '2024-06-21 13:24:19'),
(7, 'Katering 21/7', 1000.00, 'Katering utk majlis aisyah', '2024-06-26 15:35:10', '2024-06-26 07:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `ex_resources`
--

CREATE TABLE `ex_resources` (
  `resource_id` bigint(20) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phoneNo` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `acc_no` bigint(20) NOT NULL,
  `remark` text NOT NULL,
  `resource_ctg_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ex_resources`
--

INSERT INTO `ex_resources` (`resource_id`, `fullname`, `phoneNo`, `email`, `bank_name`, `acc_no`, `remark`, `resource_ctg_id`, `created_at`) VALUES
(1, 'Enizatul Binti Omar', 123456789, 'eniez@gmail.com', 'maybank', 12345678, 'Rate Harga:\r\nTunang/Nikah - RM300\r\nSanding - RM350', 1, '2024-05-02 15:05:57'),
(8, 'Shahrizal Bin Ahmad ', 147852369, 'shah@gmail.com', 'Maybank', 67589641235, 'wedding = 500\r\nnikah/tunang = 300\r\nnikah/sanding = 600', 2, '2024-05-02 15:05:57'),
(9, 'Zahirah Zakaria', 194308687, 'zahirah@gmail.com', 'CIMB', 11152664899, 'Photo (raw + edited) + Photobook\r\nWedding = RM700\r\nNikah/tunang = RM500\r\n\r\nPhoto (raw + edited) \r\nWedding = RM600\r\nNikah/tunang = RM400\r\n', 3, '2024-05-02 15:05:57'),
(10, 'Kuih By Cikgu', 196325874, 'kuihbycikgu@gmail.com', 'Maybank', 67854652190, 'Seri Muka - RM30/BOX', 5, '2024-05-31 03:23:36'),
(11, 'AlHaj Canopy', 152364789, 'alhajcanopy@gmail.com', 'Maybank', 7896541265, 'Satu kanopi (32 seating) :RM 400', 4, '2024-06-18 02:16:43'),
(12, 'Rizal bin Mohamad', 123654789, 'rizal@gmail.com', 'Maybank', 1523698745, 'Wedding: RM300\r\nNikah/Tunang: RM250', 2, '2024-06-22 05:28:28'),
(13, 'Syaza binti Salleh', 195632487, 'syazasalleh@gmail.com', 'CIMB Bank', 12547856923, 'Wedding: RM300\r\nNikah/Tunang: RM250', 1, '2024-06-22 05:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `fb_id` bigint(20) NOT NULL,
  `cust_id` bigint(20) NOT NULL,
  `feedback` text NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `rental_id` bigint(20) DEFAULT NULL,
  `booking_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `view` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`fb_id`, `cust_id`, `feedback`, `rating`, `rental_id`, `booking_id`, `created_at`, `view`) VALUES
(13, 3, 'Baju sangat cantik.', 4, 43, NULL, '2024-06-25 13:11:33', 1),
(14, 13, 'Koleksi baju dari Waniey Bridal memang sangat cantik dan murah. Harga sangat berpatutan.', 5, 39, NULL, '2024-06-25 15:30:49', 1),
(15, 13, 'pakej yang sangat berbaloi', 4, NULL, 22, '2024-06-25 17:01:31', 1),
(17, 14, 'Sangat berbaloi', 4, NULL, 30, '2024-06-26 06:34:48', 1),
(18, 14, 'Sangat cantik', 5, 56, NULL, '2024-06-26 07:31:54', 1),
(19, 15, 'Sangat cantik', 4, NULL, 38, '2024-06-27 02:14:56', 0),
(20, 15, 'Baju cantik', 5, 62, NULL, '2024-06-27 02:22:23', 0),
(22, 3, 'Pakej yang sangat berbaloi dan murah. ', 4, NULL, 40, '2024-07-01 09:36:57', 1),
(23, 3, 'Pelamin sangat cantik', 5, 64, NULL, '2024-07-01 09:41:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `img_id` int(11) NOT NULL,
  `img_file` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`img_id`, `img_file`, `description`, `created_at`) VALUES
(6, '1714581609.jpg', 'Majlis Sanding Tasha & Hazim', '2024-05-01 16:40:09'),
(7, '1714581621.jpg', 'Majlis Sanding Tasha & Hazim', '2024-05-01 16:40:21'),
(8, '1714581644.jpg', 'Majlis Sanding ', '2024-05-01 16:40:44'),
(9, '1714581659.jpg', 'Majlis Sanding', '2024-05-01 16:40:59'),
(10, '1714581674.jpg', 'Majlis Sanding ', '2024-05-01 16:41:14'),
(11, '1718618436.jpg', 'Majlis Sanding Tasha & Hazim', '2024-06-17 10:00:36'),
(12, '1718618724.jpg', 'Majlis Sanding Tasha & Hazim', '2024-06-17 10:05:24'),
(13, '1719043791.jpg', 'Majlis Sanding Tasha & Hazim', '2024-06-22 08:09:51'),
(14, '1719043827.jpg', 'Majlis Perkahwinan Haifa & Faiz', '2024-06-22 08:10:27'),
(15, '1719043853.jpg', 'Majlis Persandingan Joe dan Alisha', '2024-06-22 08:10:53'),
(17, '1719418777.jpg', 'Majlis Sanding', '2024-06-26 16:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` bigint(20) NOT NULL,
  `menu_img` varchar(255) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `price_per_pax` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `menu_ctg_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_img`, `menu_name`, `price_per_pax`, `description`, `created_at`, `updated_at`, `menu_ctg_id`) VALUES
(1, '1719025059.jpeg', 'Daging Masak Hitam', 4.00, '     Daging Masak Hitam is the beef cooked with some spices and soy sauce.   ', '2024-02-26 02:45:47', '2024-06-22 02:57:39', 1),
(4, '1719025146.jpeg', 'Orange Fruit', 1.00, '  Sunkist or Navel Orange  ', '2024-04-30 04:44:08', '2024-06-22 02:59:06', 8),
(5, '1719025069.jpeg', 'Ayam Masak Merah', 4.00, '  Ayam masak merah is fried chicken with spicy gravy  ', '2024-04-30 04:46:56', '2024-06-22 03:06:14', 1),
(6, '1719025137.jpeg', 'Sirap', 0.50, ' Air sirap as the main beverage of the event. ', '2024-04-30 04:48:08', '2024-06-22 02:58:57', 7),
(7, '1719140783.jpg', 'Teh Tarik', 1.00, ' Teh tarik only served 30% of the total order. ', '2024-04-30 04:50:10', '2024-06-23 11:06:23', 7),
(9, '1719140765.jpg', 'Kuih Muih (Campur)', 2.00, '   kuih seri muka   ', '2024-05-31 03:32:32', '2024-06-23 11:06:05', 9),
(11, '1719025130.jpeg', 'Nasi Minyak', 3.00, '   asdasdas   ', '2024-06-05 16:59:19', '2024-06-22 03:06:06', 1),
(12, '1719025598.jpg', 'Nasi Putih', 2.00, 'nasi putih', '2024-06-22 03:06:38', '2024-06-22 03:06:38', 1),
(13, '1719025633.jpg', 'Dalca sayur', 2.00, 'dalca sayur', '2024-06-22 03:07:13', '2024-06-22 03:07:13', 8),
(14, '1719025657.jpg', 'Cendol', 1.50, 'cendol\r\n', '2024-06-22 03:07:37', '2024-06-22 03:07:37', 9),
(15, '1719025700.webp', 'Ayam Bakar (1 ekor)', 20.00, 'ayam bakar untuk pengantin', '2024-06-22 03:08:20', '2024-06-22 03:08:20', 1),
(16, '1719025726.jpg', 'Masakan Udang', 3.00, 'Makanan pengantin', '2024-06-22 03:08:46', '2024-06-22 03:08:46', 1),
(17, '1719025753.jpg', 'Ayam Berempah', 4.00, 'Ayam berempah\r\n', '2024-06-22 03:09:13', '2024-06-22 03:09:13', 1),
(18, '1719025826.JPG', 'Udang Cucuk', 10.00, 'udang  dihias pada nanas', '2024-06-22 03:10:26', '2024-06-22 03:10:26', 1),
(19, '1719387176.jpg', 'Kuih Seri Muka', 0.50, 'Dessert', '2024-06-26 07:32:56', '2024-06-26 07:32:56', 9);

-- --------------------------------------------------------

--
-- Table structure for table `menu_category`
--

CREATE TABLE `menu_category` (
  `menu_ctg_id` bigint(20) NOT NULL,
  `ctg_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_category`
--

INSERT INTO `menu_category` (`menu_ctg_id`, `ctg_name`, `created_at`) VALUES
(1, 'Main ', '2024-05-03 04:32:38'),
(7, 'Beverage', '2024-05-03 04:32:38'),
(8, 'Side', '2024-05-03 04:32:38'),
(9, 'Dessert', '2024-05-03 04:32:38');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `pkg_id` bigint(20) NOT NULL,
  `pkg_img` varchar(255) NOT NULL,
  `pkg_name` varchar(255) NOT NULL,
  `duration` tinyint(4) NOT NULL,
  `pkg_price` decimal(10,2) NOT NULL,
  `total_pax` int(11) NOT NULL,
  `deposit` decimal(10,2) NOT NULL,
  `pkg_desc` text NOT NULL,
  `free_items` text NOT NULL DEFAULT 'N/A',
  `pkg_ctg_id` bigint(20) NOT NULL,
  `eventspace_inc` tinyint(4) NOT NULL DEFAULT 0,
  `dais_inc` tinyint(4) NOT NULL DEFAULT 0,
  `attire_inc` tinyint(4) NOT NULL DEFAULT 0,
  `makeup_inc` tinyint(4) NOT NULL DEFAULT 0,
  `photographer_inc` tinyint(4) NOT NULL DEFAULT 0,
  `eventhost_inc` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`pkg_id`, `pkg_img`, `pkg_name`, `duration`, `pkg_price`, `total_pax`, `deposit`, `pkg_desc`, `free_items`, `pkg_ctg_id`, `eventspace_inc`, `dais_inc`, `attire_inc`, `makeup_inc`, `photographer_inc`, `eventhost_inc`, `created_at`) VALUES
(27, '1718816184.jpeg', 'Pakej Tunang/Nikah Bajet', 3, 3000.00, 100, 1000.00, 'pakej termasuk catering, pelamin, dan andaman', 'N/A', 1, 0, 1, 0, 1, 0, 0, '2024-06-24 12:41:35'),
(47, '1718815747.jpeg', 'Pakej Kahwin Bajet 2023/2024', 5, 4100.00, 100, 1000.00, 'Pakej Kahwin Bajet termasuk katering beserta kelengkapan katering, kanopi 3/4 set(scallop, kipas, lampu) untuk 96-128 seating, dekorasi dan andaman.', 'N/A', 2, 0, 1, 1, 1, 0, 0, '2024-06-24 12:49:57'),
(49, '1718878289.jpeg', 'Gold Package ', 5, 8700.00, 100, 1000.00, 'pakej perkahwinan lengkap termasuk katering, event space, andaman, baju pengantin, pelmin sedia ada, dan sebagainya.', 'Event Host, Pramusaji, Basic PA System, Bilik Hotel (1H1M)', 2, 1, 0, 1, 1, 1, 1, '2024-06-23 10:52:26');

-- --------------------------------------------------------

--
-- Table structure for table `package_bookings`
--

CREATE TABLE `package_bookings` (
  `booking_id` bigint(20) NOT NULL,
  `cust_id` bigint(20) NOT NULL,
  `event_date` date NOT NULL,
  `event_loc` varchar(255) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `pkg_id` bigint(20) NOT NULL,
  `pax` int(11) NOT NULL,
  `total_payment` decimal(10,2) NOT NULL,
  `deposit_status` tinyint(4) NOT NULL DEFAULT 0,
  `payment_bal` decimal(10,2) NOT NULL,
  `full_payment_date` date NOT NULL,
  `full_payment_status` tinyint(4) NOT NULL DEFAULT 0,
  `groom_name` varchar(255) NOT NULL,
  `bride_name` varchar(255) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_status` tinyint(4) NOT NULL DEFAULT 0,
  `makeup_artist` bigint(20) DEFAULT NULL,
  `event_host` bigint(20) DEFAULT NULL,
  `photographer` bigint(20) DEFAULT NULL,
  `dais_id` bigint(20) DEFAULT NULL,
  `attire_id` bigint(20) DEFAULT NULL,
  `remarks` text NOT NULL,
  `promo_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_bookings`
--

INSERT INTO `package_bookings` (`booking_id`, `cust_id`, `event_date`, `event_loc`, `slot_id`, `pkg_id`, `pax`, `total_payment`, `deposit_status`, `payment_bal`, `full_payment_date`, `full_payment_status`, `groom_name`, `bride_name`, `booking_date`, `booking_status`, `makeup_artist`, `event_host`, `photographer`, `dais_id`, `attire_id`, `remarks`, `promo_code`) VALUES
(16, 11, '2024-09-28', 'Kampung Baru Kuala Lumpur', 1, 47, 0, 5200.00, 1, 4200.00, '2024-09-14', 0, 'Muhammad', 'Safiya', '2024-03-12 10:22:55', 0, NULL, NULL, NULL, NULL, NULL, 'Sila buat pembayaran penuh selewat-lewatnya pada tarikh yg tertera (full payment date)', ''),
(22, 13, '2024-07-20', '123, jalan 1, kg lama, seri kembangan, selangor', 1, 27, 100, 4100.00, 1, 3100.00, '2024-07-06', 0, 'ali', 'siti aishah', '2024-05-01 09:29:59', 1, NULL, NULL, NULL, 27, 40, 'test update', 'N/A'),
(26, 12, '2024-06-22', '123, jalan 1, kg lama, seri kembangan, selangor', 2, 47, 200, 5300.00, 1, 4300.00, '2024-07-06', 0, 'Fahmi', 'Aminah', '2024-06-25 14:50:15', 1, NULL, NULL, NULL, NULL, NULL, 'test edit booking status', ''),
(27, 13, '2024-06-29', 'Qhall Event Space', 1, 49, 200, 10171.00, 1, 9171.00, '2024-06-15', 0, 'fakri', 'aishah', '2024-06-25 16:00:22', 0, 1, 12, 9, NULL, 39, 'test update', ''),
(30, 14, '2024-08-10', '123, jalan 1, kg lama, seri kembangan, selangor', 1, 27, 100, 2900.00, 1, 1900.00, '2024-07-27', 0, 'Ahmad', 'Aishah', '2024-06-26 06:28:57', 1, NULL, NULL, NULL, NULL, NULL, 'completed', ''),
(38, 15, '2024-08-31', 'Qhall Event Space', 1, 49, 100, 8600.00, 1, 7600.00, '2024-08-17', 1, 'Ahmad', 'Suzana', '2024-06-27 01:55:46', 1, 1, 8, 9, NULL, 36, 'test update', 'NEWYEARRSALE'),
(40, 3, '2024-11-02', '123, jalan 1, kg lama, seri kembangan, selangor', 1, 27, 100, 2900.00, 0, 1900.00, '2024-10-19', 0, 'Ali', 'Farah', '2024-07-01 09:31:54', 1, NULL, NULL, NULL, NULL, NULL, 'Booking completed', 'NEWYEARRSALE');

-- --------------------------------------------------------

--
-- Table structure for table `package_category`
--

CREATE TABLE `package_category` (
  `pkg_ctg_id` bigint(20) NOT NULL,
  `ctg_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_category`
--

INSERT INTO `package_category` (`pkg_ctg_id`, `ctg_name`, `created_at`) VALUES
(1, 'Pakej Tunang/Nikah', '2024-06-23 06:49:45'),
(2, 'Pakej Kahwin', '2024-06-23 06:49:33'),
(7, 'Pakej Akikah', '2024-06-27 00:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `package_menu`
--

CREATE TABLE `package_menu` (
  `pkg_menu_id` bigint(20) NOT NULL,
  `pkg_id` bigint(20) NOT NULL,
  `section` varchar(255) NOT NULL,
  `menu_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_menu`
--

INSERT INTO `package_menu` (`pkg_menu_id`, `pkg_id`, `section`, `menu_id`, `qty`, `total_price`) VALUES
(76, 27, 'Main Course', 1, 100, 400.00),
(77, 27, 'Main Course', 5, 100, 400.00),
(78, 27, 'Main Course', 11, 50, 150.00),
(79, 27, 'Main Course', 6, 100, 50.00),
(80, 27, 'Main Course', 4, 100, 100.00),
(98, 47, 'Main Course', 1, 100, 400.00),
(99, 47, 'Main Course', 11, 50, 150.00),
(107, 47, 'Main Course', 5, 100, 400.00),
(108, 49, 'Main Course', 1, 100, 400.00),
(109, 49, 'Main Course', 5, 100, 400.00),
(110, 49, 'Main Course', 11, 100, 300.00),
(111, 49, 'Main Course', 4, 100, 100.00),
(112, 49, 'Main Course', 6, 100, 50.00),
(113, 49, 'Tea Corner', 7, 60, 60.00),
(114, 49, 'Tea Corner', 9, 30, 60.00),
(115, 49, 'Bride Meals', 15, 1, 20.00),
(116, 49, 'Bride Meals', 16, 2, 6.00),
(117, 49, 'Santai Corner', 14, 50, 75.00),
(118, 27, 'Main Course', 12, 50, 100.00),
(119, 47, 'Main Course', 12, 50, 100.00),
(120, 47, 'Main Course', 4, 100, 100.00),
(121, 47, 'Main Course', 6, 100, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `promo_id` bigint(20) NOT NULL,
  `poster` varchar(255) NOT NULL,
  `promo_code` varchar(255) NOT NULL,
  `amount_off` int(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `promo_status` varchar(255) NOT NULL,
  `promo_desc` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `promo_ctg_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`promo_id`, `poster`, `promo_code`, `amount_off`, `start_date`, `end_date`, `promo_status`, `promo_desc`, `created_at`, `promo_ctg_id`) VALUES
(8, '1719143701.png', 'HAJISALE', 20, '2024-06-15', '2024-07-15', 'Ongoing', 'selected dais only. Discount up to RM20\r\n', '2024-05-01 10:29:33', 2),
(15, '1718621097.png', 'NEWYEARRSALE', 100, '2024-06-20', '2024-07-06', 'Ongoing', 'Only applicable to package booking only. Discount up to RM100.', '2024-06-17 10:44:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promotion_category`
--

CREATE TABLE `promotion_category` (
  `promo_ctg_id` bigint(20) NOT NULL,
  `ctg_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion_category`
--

INSERT INTO `promotion_category` (`promo_ctg_id`, `ctg_name`, `created_at`) VALUES
(1, 'Package', '2024-05-03 04:32:56'),
(2, 'Dais', '2024-05-03 04:32:56'),
(3, 'Attire', '2024-05-03 04:32:56');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_request`
--

CREATE TABLE `quotation_request` (
  `request_id` bigint(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phoneNo` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `event_location` varchar(255) NOT NULL,
  `total_pax` int(11) NOT NULL,
  `question` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_request`
--

INSERT INTO `quotation_request` (`request_id`, `fullname`, `email`, `phoneNo`, `event_date`, `event_type`, `event_location`, `total_pax`, `question`, `created_at`, `status`) VALUES
(12, 'Siti Aishah', 'aishah@gmail.com', 123456789, '2024-07-20', 'tunang', 'seri kembangan selangor', 100, 'Let me know what is the best package to choose.', '2024-06-22 09:26:18', 1),
(13, 'Humairah Ahmad', 'humairah@gmail.com', 199548657, '2024-08-24', 'sanding', 'Puncak Jalil, Selangor', 300, 'suggest an affordable wedding package include hall.', '2024-06-25 11:59:43', 1),
(14, 'Zufaraha', 'zz@gmail.com', 199269554, '2024-06-29', 'Sanding', 'Kuala Lumpur', 300, 'Saya nak tahu pakej yang sesuai', '2024-06-26 12:41:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` bigint(20) NOT NULL,
  `cust_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rental_type` enum('attire','dais') NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `event_date` date NOT NULL,
  `event_loc` varchar(255) NOT NULL,
  `return_date` date NOT NULL,
  `total_payment` decimal(10,2) NOT NULL,
  `deposit` decimal(10,2) NOT NULL,
  `deposit_status` tinyint(4) NOT NULL DEFAULT 0,
  `payment_bal` decimal(10,2) NOT NULL,
  `full_payment_date` date NOT NULL,
  `full_payment_status` tinyint(4) NOT NULL DEFAULT 0,
  `rental_status` tinyint(4) NOT NULL DEFAULT 0,
  `remark` text NOT NULL,
  `promo_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rental_id`, `cust_id`, `created_at`, `rental_type`, `item_id`, `event_date`, `event_loc`, `return_date`, `total_payment`, `deposit`, `deposit_status`, `payment_bal`, `full_payment_date`, `full_payment_status`, `rental_status`, `remark`, `promo_code`) VALUES
(38, 12, '2024-06-22 08:25:49', 'attire', 40, '2024-07-06', '123, jalan 1, kg lama, kajang, selangor, kajang, 123456 Selangor', '2024-07-09', 699.00, 0.00, 0, 349.00, '2024-06-22', 0, 0, '', 'NEWYEARRSALE'),
(39, 13, '2024-06-22 09:31:36', 'attire', 39, '2024-06-01', '123, jalan 1, kg lama, seri kembangan, 123456 Selangor', '2024-06-03', 699.00, 0.00, 1, 0.00, '2024-06-22', 1, 1, 'done', ''),
(43, 3, '2024-06-24 17:14:32', 'attire', 40, '2024-06-29', '123, jalan 1, kg lama,  seri kembangan, 123456 Selangor', '2024-07-02', 699.00, 0.00, 1, 0.00, '2024-06-15', 1, 1, 'Has been completed', ''),
(56, 14, '2024-06-26 06:38:19', 'dais', 27, '2024-06-29', '123, jalan 1, kg lama, seri kembangan, 22300 Selangor', '2024-07-02', 230.00, 0.00, 1, 0.00, '2024-06-15', 1, 1, 'update ', 'HAJISALE'),
(57, 14, '2024-06-26 06:39:59', 'dais', 27, '2024-07-06', '123, jalan 1, kg lama, seri kembangan, 123654 Selangor', '2024-07-09', 230.00, 0.00, 1, 115.00, '2024-06-22', 0, 0, '', 'HAJISALE'),
(59, 14, '2024-06-26 07:30:16', 'attire', 33, '2024-06-29', '123, jalan 1, kg lama, seri kembangan, 123456 Selangor', '2024-07-02', 699.00, 0.00, 1, 349.00, '2024-06-15', 0, 0, 'test update', ''),
(62, 15, '2024-06-27 02:18:57', 'attire', 39, '2024-08-03', '123, jalan 1, kg lama, seri kembangan, 123456 Selangor', '2024-08-06', 699.00, 0.00, 1, 349.00, '2024-07-20', 1, 1, 'test update', 'NEWYEARRSALE'),
(64, 3, '2024-07-01 09:38:59', 'dais', 27, '2024-07-27', '123, jalan 1, kg lama, Kajang, 12345 Selangor', '2024-07-30', 230.00, 0.00, 0, 130.00, '2024-07-13', 0, 1, 'complete', 'HAJISALE');

-- --------------------------------------------------------

--
-- Table structure for table `resource_category`
--

CREATE TABLE `resource_category` (
  `resource_ctg_id` bigint(20) NOT NULL,
  `ctg_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource_category`
--

INSERT INTO `resource_category` (`resource_ctg_id`, `ctg_name`, `created_at`) VALUES
(1, 'Makeup Artist', '2024-05-03 04:32:17'),
(2, 'Event Host', '2024-05-03 04:32:17'),
(3, 'Photographer', '2024-05-03 04:32:17'),
(4, 'Canopy Vendor', '2024-05-03 04:32:17'),
(5, 'Dessert Vendor', '2024-05-03 04:32:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `bridal_attire`
--
ALTER TABLE `bridal_attire`
  ADD PRIMARY KEY (`attire_id`);

--
-- Indexes for table `bridal_dais`
--
ALTER TABLE `bridal_dais`
  ADD PRIMARY KEY (`dais_id`),
  ADD UNIQUE KEY `dais_name` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `event_slot`
--
ALTER TABLE `event_slot`
  ADD PRIMARY KEY (`slot_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `ex_resources`
--
ALTER TABLE `ex_resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `resource_ctg_id` (`resource_ctg_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fb_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `rental_id` (`rental_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`img_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `menu_ctg_id` (`menu_ctg_id`);

--
-- Indexes for table `menu_category`
--
ALTER TABLE `menu_category`
  ADD PRIMARY KEY (`menu_ctg_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`pkg_id`),
  ADD KEY `pkg_ctg_id` (`pkg_ctg_id`);

--
-- Indexes for table `package_bookings`
--
ALTER TABLE `package_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `slot_id` (`slot_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `dais_id` (`dais_id`),
  ADD KEY `attire_id` (`attire_id`),
  ADD KEY `makeup_artist` (`makeup_artist`),
  ADD KEY `photographer` (`photographer`),
  ADD KEY `event_host` (`event_host`),
  ADD KEY `package_bookings_ibfk_5` (`pkg_id`);

--
-- Indexes for table `package_category`
--
ALTER TABLE `package_category`
  ADD PRIMARY KEY (`pkg_ctg_id`);

--
-- Indexes for table `package_menu`
--
ALTER TABLE `package_menu`
  ADD PRIMARY KEY (`pkg_menu_id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `pkg_id` (`pkg_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promo_id`),
  ADD KEY `promo_ctg_id` (`promo_ctg_id`);

--
-- Indexes for table `promotion_category`
--
ALTER TABLE `promotion_category`
  ADD PRIMARY KEY (`promo_ctg_id`);

--
-- Indexes for table `quotation_request`
--
ALTER TABLE `quotation_request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `resource_category`
--
ALTER TABLE `resource_category`
  ADD PRIMARY KEY (`resource_ctg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `app_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `bridal_attire`
--
ALTER TABLE `bridal_attire`
  MODIFY `attire_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `bridal_dais`
--
ALTER TABLE `bridal_dais`
  MODIFY `dais_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cust_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `event_slot`
--
ALTER TABLE `event_slot`
  MODIFY `slot_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ex_resources`
--
ALTER TABLE `ex_resources`
  MODIFY `resource_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `fb_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `menu_category`
--
ALTER TABLE `menu_category`
  MODIFY `menu_ctg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `pkg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `package_bookings`
--
ALTER TABLE `package_bookings`
  MODIFY `booking_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `package_category`
--
ALTER TABLE `package_category`
  MODIFY `pkg_ctg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `package_menu`
--
ALTER TABLE `package_menu`
  MODIFY `pkg_menu_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `promo_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `promotion_category`
--
ALTER TABLE `promotion_category`
  MODIFY `promo_ctg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotation_request`
--
ALTER TABLE `quotation_request`
  MODIFY `request_id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rental_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `resource_category`
--
ALTER TABLE `resource_category`
  MODIFY `resource_ctg_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`);

--
-- Constraints for table `ex_resources`
--
ALTER TABLE `ex_resources`
  ADD CONSTRAINT `ex_resources_ibfk_1` FOREIGN KEY (`resource_ctg_id`) REFERENCES `resource_category` (`resource_ctg_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `package_bookings` (`booking_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`rental_id`);

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`menu_ctg_id`) REFERENCES `menu_category` (`menu_ctg_id`);

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`pkg_ctg_id`) REFERENCES `package_category` (`pkg_ctg_id`);

--
-- Constraints for table `package_bookings`
--
ALTER TABLE `package_bookings`
  ADD CONSTRAINT `package_bookings_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `event_slot` (`slot_id`),
  ADD CONSTRAINT `package_bookings_ibfk_2` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`),
  ADD CONSTRAINT `package_bookings_ibfk_3` FOREIGN KEY (`dais_id`) REFERENCES `bridal_dais` (`dais_id`),
  ADD CONSTRAINT `package_bookings_ibfk_4` FOREIGN KEY (`attire_id`) REFERENCES `bridal_attire` (`attire_id`),
  ADD CONSTRAINT `package_bookings_ibfk_5` FOREIGN KEY (`pkg_id`) REFERENCES `packages` (`pkg_id`),
  ADD CONSTRAINT `package_bookings_ibfk_6` FOREIGN KEY (`makeup_artist`) REFERENCES `ex_resources` (`resource_id`),
  ADD CONSTRAINT `package_bookings_ibfk_7` FOREIGN KEY (`photographer`) REFERENCES `ex_resources` (`resource_id`),
  ADD CONSTRAINT `package_bookings_ibfk_8` FOREIGN KEY (`event_host`) REFERENCES `ex_resources` (`resource_id`);

--
-- Constraints for table `package_menu`
--
ALTER TABLE `package_menu`
  ADD CONSTRAINT `package_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`),
  ADD CONSTRAINT `package_menu_ibfk_2` FOREIGN KEY (`pkg_id`) REFERENCES `packages` (`pkg_id`);

--
-- Constraints for table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_ibfk_1` FOREIGN KEY (`promo_ctg_id`) REFERENCES `promotion_category` (`promo_ctg_id`);

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
