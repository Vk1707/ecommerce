-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2024 at 01:19 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `multishop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_pass` varchar(250) NOT NULL,
  `user_status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `full_name`, `mobile`, `user_name`, `user_pass`, `user_status`) VALUES
(1, 'admin', '9865985698', 'admin@admin.com', '1234', 1);

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `subtitle` varchar(200) NOT NULL,
  `display_subtitle` tinyint(1) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `display_logo` tinyint(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` int(12) NOT NULL,
  `address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`id`, `name`, `subtitle`, `display_subtitle`, `logo`, `display_logo`, `email`, `phone`, `address`) VALUES
(1, 'Flipkart', 'XYZ BLOCK -B Delhi - 110001', 0, 'Array', 0, 'flipkart@gmail.com', 999999999, ''),
(2, 'VIVEK ESHOP', 'A Complete Platform For All Gadgets', 1, 'Array', 1, 'vivekeshop@gmail.com', 2147483647, 'B-467/11 Gourav Nagar\r\nPrem Nagar 2nd');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `discount_percent` int(100) NOT NULL,
  `active` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`id`, `name`, `description`, `discount_percent`, `active`, `created_at`, `modified_at`, `deleted_at`) VALUES
(5, 'Flipkart Days ', 'From 1st to 3rd December 2022, the Flipkart Days sale will take place. You will get amazing offers in the sale while it lasts. If payments are made using debit cards, credit cards or net banking, you will get 10% discounts over the already discounted prices.', 25, 'T', '2022-07-25 01:25:35', '2023-03-24 01:37:43', NULL),
(6, 'Big Billion Days', 'Smartphone Offers', 30, 'T', '2022-07-26 05:15:15', '2022-07-25 02:32:58', '2022-07-25 02:58:12'),
(7, 'BIg Savings Days', 'Grand offer On all Electronic Products', 20, 'F', '2022-07-25 02:37:30', '2023-03-24 01:37:52', NULL),
(8, 'Flipkart New Year Sale', '\"Flipkart New Year Sale is going to be a blockbuster because there are several discounts on many products. From 1st to 5th January, this sale will last.\"', 30, 'T', '2022-07-27 23:58:53', '2023-03-24 01:38:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ecommerce_store`
--

CREATE TABLE `ecommerce_store` (
  `estore_id` int(11) NOT NULL,
  `estore_name` varchar(50) NOT NULL,
  `estore_img` varchar(250) NOT NULL,
  `status` varchar(5) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ecommerce_store`
--

INSERT INTO `ecommerce_store` (`estore_id`, `estore_name`, `estore_img`, `status`, `created_at`, `modified_at`, `deleted_at`) VALUES
(5, 'Flipkart', 'flipkart-icon (2).jpg', '1', '2022-07-25 01:25:35', '2024-09-09 09:21:19', NULL),
(6, 'Amazon', 'amazon-icon.jpg', '1', '2022-07-26 05:15:15', '2024-09-09 07:19:02', NULL),
(7, 'Myntra', 'myntra-icon.jpg', '1', '2022-07-25 02:37:30', '2024-09-09 07:20:12', NULL),
(8, 'Relaince Digital', 'reliance-logo.png', '1', '2022-07-27 23:58:53', '2024-09-09 07:19:28', NULL),
(9, 'Meesho', 'meesho_logo.png', '1', '2024-08-26 00:54:28', '2024-09-09 07:21:07', NULL),
(36, 'ecom', '', '1', '2024-09-11 06:54:33', NULL, NULL),
(37, 'Estorejj', 'flipkart-icon (2).jpg', '1', '2024-09-11 06:57:48', '2024-09-11 10:28:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `user_id` int(50) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `mrp_price` decimal(10,2) NOT NULL,
  `discounted_price` decimal(10,2) NOT NULL,
  `estore_id` int(11) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `product_url` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `category_id`, `quantity`, `mrp_price`, `discounted_price`, `estore_id`, `featured`, `product_url`, `created_at`, `modified_at`, `deleted_at`) VALUES
(1, 'Realme GT Neo 3', 'Unleash the suppressed gamer in you and enjoy a top-notch user interface with the Realme GT NEO 3 smartphone. This phone is designed in such a way that it attracts the onlookers and performs so well that you can’t keep it down for a minute. This phone features an exquisite Dimensity 8100 5G processor that facilitates a silky smooth operation and delivers elevated performance. Additionally, the 50 MP Wide-angle Triple Camera of this phone enables you to take stunning photos and videos that last long in your cherished memories. Furthermore, the monstrous 4500 mAh battery and 150 W UltraDart Charge technology powers your phone in a short period of time and backs you up for an extended time.', 6, 1, 42999.00, 32999.00, 6, 1, 'https://www.flipkart.com', '2022-07-26 03:57:08', '2024-09-02 05:12:23', NULL),
(2, 'IPhone 13 128Gb', 'The iPhone 13 features a stylish design and innovative features that make it a delight to use. It is equipped with a Dual-camera system that adds an upgrade to the photos and videos that you take. It offers a big boost in battery so that you have enough time to watch content, game, and so on. And, the A15 Bionic helps load graphics-intensive games with ease.', 6, 1, 75999.00, 65999.00, 5, 1, 'https://www.flipkart.com/iqoo-12-legend-256-gb/', '2022-07-26 05:35:48', '2024-08-30 10:29:46', NULL),
(3, 'Samsung F13 (Waterfall Blue, 64 GB)  (4 GB RAM)', 'Enjoy seamless connectivity and an uninterrupted movie marathon with the impressive Samsung Galaxy F13 that is designed specifically to impress the entertainment fanatics. This smartphone features a terrific 16.62 cm (6.6) FHD+ LCD Display that can effortlessly blow your mind with its incredible performance. Furthermore, this phone boasts a 50 MP Triple Camera setup that allows you to capture awesomeness with a gentle tap. Moreover, the Samsung Galaxy F13 sports up to 8 GB of RAM and features an innovative RAM plus technology that taps into the phone’s internal storage to elevate its performance.', 6, 2, 11999.00, 9999.00, 5, 0, 'https://www.flipkart.com/poco-x6-pro-5g-yellow-256-gb/p/itm46a0b51f57a68', '2022-07-26 01:07:42', '2024-08-30 10:34:18', NULL),
(4, 'OnePlus 9 5G ', 'Rear Triple Camera Co-Developed by Hasselblad, 48 MP Main camera, 50 MP Ultra Wide Angle Camera with Free Form Lens, 2 MP Monochorme Lens. Also comes with a 16 MP Front Camera Qualcomm Snapdragon 888 Processor with Adreno 660 GPU 6.55 Inches Fluid AMOLED Display with 120Hz refresh rate OnePlus Oxygen OS based on Andriod 11 Comes with 4500 mAh Battery with 65W Wired Charging Hands-Free access to Alexa: Alexa on your phone lets you make phone calls, open apps, control smart home devices, access the library of Alexa skills, and more using just your voice while on-the-go. Download the Alexa app and complete hands-free setup to get started. Just ask - and Alexa will respond instantly', 6, 4, 42999.00, 40000.00, 5, 1, 'https://www.flipkart.com/poco-x6-pro', '2022-07-26 01:09:01', '2024-09-05 04:45:37', NULL),
(5, 'Asus Rog Phone 3', 'Robust, performance-oriented, and stylish, just like gamers, the ASUS ROG Phone 3 is a device meant for gaming. It is packed with multi-level processor cooling features so that your phone stays cool. It also comes with a whopping 6000 mAh battery to let you continue gaming for hours. With the presence of ultrasonic AirTriggers 3 with Dual Partition functionality, this phone even gives you the ability to enjoy console-like gaming.', 6, 5, 46999.00, 45000.00, 5, 0, NULL, '2022-07-26 01:10:51', '2024-08-30 10:34:18', NULL),
(6, 'Realme Buds Neo 2', 'Truewireless Earbuds\r\n', 10, 6, 2199.00, 1399.00, 5, 1, NULL, '2022-07-26 01:16:12', '2024-08-30 10:34:18', NULL),
(7, 'Lenovo IdeaPad 3 Core i3 11th Gen - (8 GB/512 GB S', 'Get an opportunity to work anytime and from anywhere as you bring home the Lenovo IdeaPad 3 Core i3 11th Gen laptop. It features a slim 19.9 mm body and weight of about 1.41 kg to ensure flexible portability and stylish looks no matter where you go. Also, its 35.56 cm (14) Full HD display with four-sided narrow bezels renders immersive visuals. Moreover, this laptop is powered by an 11th generation processor and high speed SSD storage that allows you to enjoy efficient performance and massive storage.', 9, 7, 39599.00, 31499.00, 5, 0, NULL, '2022-07-29 00:26:23', '2024-08-30 10:34:18', NULL),
(8, 'Sandisk Pendrive 64Gb Type C', 'Pendrive Type C', 10, 90, 949.00, 599.00, 5, 0, NULL, '2022-08-02 22:26:45', '2024-08-30 10:34:18', NULL),
(9, 'Vivo V27 ', 'With the amazing Vivo V27 Pro 5G, get the full sense of a brilliant display and flawless efficiency. This phone has a 3D Curved Screen that enhances user experience with its huge size of 17.22 cm (6.78), 120 Hz refresh rate, and up to 1.07 billion colours. Also, the extraordinary 50 MP front camera, Night Portrait with Aura Light, and the 50 MP primary camera with exceptional quality all let you take mesmerising photographs by dependably capturing precise, colourful details. Also, the MediaTek Dimensity 8200 in your phone provides effective performance and enables seamless multitasking.', 6, 1, 28999.00, 22555.00, 5, 0, 'https://www.flipkart.com/poco-x6-pro', '2023-03-22 01:19:35', '2024-09-05 05:05:39', NULL),
(10, 'Samsung Tab', 'Learn, play, and do more with the Galaxy Tab A8 Wi-Fi + 4G tablet. This tablet features an elegant and slim metal body to add an aesthetic appearance on this device. Also, this tablet comes with a range of secured and exciting features including Parental Control system, Screen Recording system, Continue Apps to let you remain engrossed to the device for a long time. Lastly, thanks to the Samsung Knox app, you can enjoy keeping your confidential data secured on this tablet.', 7, 5, 18990.00, 13990.00, 5, 0, 'https://www.flipkart.com', '2023-03-22 01:38:53', '2024-09-02 09:05:39', NULL),
(11, 'Iphone 14 Plus', '16.95 cm (6.7-inch) Super Retina XDR display\r\nAdvanced camera system for better photos in any light\r\nCinematic mode now in 4K Dolby Vision up to 30 fps\r\nAction mode for smooth, steady, handheld videos\r\nVital safety technology — Crash Detection calls for help when you can’t', 6, 8, 80990.00, 65990.00, 5, 1, NULL, '2023-03-21 20:45:53', '2024-08-30 10:34:18', NULL),
(12, 'LENOVO LAPTOP', 'Featuring a sleek and lightweight design, the Lenovo IdeaPad Gaming Laptop is ideal for on-the-go gaming, so you can take your powerful gaming system with you wherever you go. With this laptop, you can be confident that it will be durable and portable wherever you go, such as to college, the office, or even the airport. This laptop has undergone military specification tests, such as it has withstood exposure to high and low temperatures, temperature shock, drops, and vibrations. So, you may rest assured that this device can offer durable performance in a myriad of environments.', 9, 8, 57999.00, 50999.00, 5, 1, NULL, '2023-03-21 20:51:26', '2024-08-30 10:34:18', NULL),
(13, 'APPLE iPad (9th Gen) 64 GB ROM', 'Featuring a sleek and lightweight design, the Lenovo IdeaPad Gaming Laptop is ideal for on-the-go gaming, so you can take your powerful gaming system with you wherever you go. With this laptop, you can be confident that it will be durable and portable wherever you go, such as to college, the office, or even the airport. This laptop has undergone military specification tests, such as it has withstood exposure to high and low temperatures, temperature shock, drops, and vibrations. So, you may rest assured that this device can offer durable performance in a myriad of environments.', 7, 12, 58990.00, 50000.00, 5, 1, 'https://www.flipkart.com/poco-x6-pro', '2023-03-21 20:58:53', '2024-09-02 09:37:39', NULL),
(14, 'SAMSUNG TV ', 'Resolution: HD Ready (1366x768) | Refresh Rate: 60 hertz\nConnectivity: 2 HDMI ports to connect set top box, Blu Ray players, gaming console | 1 USB ports to connect hard drives and other USB devices\nSound : 20 Watts Output | Dolby Digital Plus\nSmart TV Features : Personal Computer | Screen Share | Music System | Content Guide | Connect Share Movie\nDisplay : LED Panel | Mega Contrast | PurColor | HD Picture Quality | Slim & Stylish Design', 11, 0, 14990.00, 12999.00, 5, 1, 'https://www.flipkart.com/iqoo-7', '2023-03-21 21:02:47', '2024-08-27 08:41:46', NULL),
(21, 'Poco X6 Pro', 'The POCO X6 Pro is more than just a smartphone; it\'s a powerhouse of innovation, design, and performance. From the revolutionary cooling system to the cutting-edge HyperOS, the immersive display, powerful camera capabilities, and distinctive aesthetics – the POCO X6 Pro is a testament to POCO\'s commitment to redefining the smartphone experience. Immerse yourself in a world where power meets elegance with the POCO X6 Pro.', 6, 5, 25999.00, 25899.00, 5, 0, 'https://www.flipkart.com/poco-x6-pro', '2024-08-25 05:21:48', '2024-08-30 10:34:18', NULL),
(24, 'IQoo Neo 7', 'Resolution: HD Ready (1366x768) | Refresh Rate: 60 hertz\nConnectivity: 2 HDMI ports to connect set top box, Blu Ray players, gaming console | 1 USB ports to connect hard drives and other USB devices\nSound : 20 Watts Output | Dolby Digital Plus\nSmart TV Features : Personal Computer | Screen Share | Music System | Content Guide | Connect Share Movie\nDisplay : LED Panel | Mega Contrast | PurColor | HD Picture Quality | Slim & Stylish Design', 6, 5, 25999.00, 21000.00, 5, 0, 'https://www.flipkart.com/iqoo-7', '2024-08-26 02:20:37', '2024-08-30 10:34:18', NULL),
(26, 'Lg Fridge 240ltr', 'This LG refrigerator has a smart inverter compressor that provides long-lasting freshness, reduces noise, and also ensures stabiliser-free operation. Its smart connect technology connects this refrigerator to your home inverter during power cuts and keeps the food fresh. This refrigerator can work on solar energy. The base stand of this refrigerator allows you to store vegetables at room temperature. You can keep your food fresh for a long time with the lattice-patterned box in this refrigerator. Its toughened glass shelves can hold up to 175 kg so that you can store heavy food items with ease.', 13, 2, 25999.00, 21999.00, 5, 0, 'https://www.flipkart.com/poco-x6-pro', '2024-08-27 02:17:34', '2024-08-27 06:17:05', NULL),
(40, 'Poco X6 Pro ', ' Poco X7 Pro  Poco X7 Pro  Poco X7 Pro  Poco X7 Pro  Poco X7 Pro  Poco X7 Pro  Poco X7 Pro ', 14, 2, 39999.00, 25999.00, 8, 0, 'https://www.flipkart.com', '2024-09-03 19:43:58', NULL, NULL),
(41, 'Motorola G45 5g ', 'Fast 5G phone with a Fast processor in the segment. The moto g45 5G is powered by the Snapdragon 6s Gen 3 octa-core processor and LPDDR4X memory. Experience the thrill of blazing-fast 5G speeds, enjoy seamless gaming, smooth multitasking, and capture stunning low-light photos. With lightning-fast downloads and unbeatable efficiency, moto g45 5G with Snapdragon 6s Gen 3 processor makes everything smoother and faster.', 6, 5, 15999.00, 11499.00, 6, 0, 'https://www.flipkart.com/poco-x6-pro', '2024-09-05 01:27:50', NULL, NULL),
(42, 'IQoo Neo 12 5g', 'khshjbldvid zgy hj whdsvxved u jhvdjcvvxchjAVD', 6, 2, 39999.00, 29999.00, 6, 1, 'https://www.flipkart.com/iqoo-7', '2024-09-05 04:55:23', NULL, NULL),
(43, 'IQoo Neo 12 5g', 'hhdbhbwudkwd', 6, 3, 25999.00, 22999.00, 7, 0, 'https://www.flipkart.com', '2024-09-06 03:23:06', NULL, NULL),
(44, 'test', 'test desc', 7, 5, 2599.00, 2256.00, 7, 0, 'https://www.flipkart.com', '2024-09-07 02:34:01', NULL, NULL),
(45, 'test1', 'test desc 4', 6, 2, 35999.00, 25566.00, 8, 0, 'https://www.flipkart.com', '2024-09-07 02:46:33', NULL, NULL),
(46, 'test2', 'desc2', 8, 5, 8698.00, 6565.00, 6, 1, 'https://www.flipkart.com/iqoo-8', '2024-09-07 02:55:34', '2024-09-11 07:20:50', NULL),
(50, 'b', 'j', 6, 1, 35888.00, 5444.00, 5, 0, 'https://www.flipkart.com', '2024-09-07 03:18:55', NULL, NULL),
(51, 'test2', 'jndjcnjsx', 6, 1, 35999.00, 25566.00, 5, 0, 'https://www.flipkart.com/poco-x6-pro', '2024-09-07 04:12:50', NULL, NULL),
(52, 'test', 'njnj', 8, 1, 5699.00, 2567.00, 5, 0, 'https://www.flipkart.com/poco-x6-pro', '2024-09-07 04:20:55', NULL, NULL),
(54, 'test product', 'test desc', 8, 1, 5698.99, 5000.66, 5, 0, 'https://www.flipkart.com', '2024-09-10 01:41:58', NULL, NULL),
(55, 'test product2', 'test desc', 12, 1, 4500.00, 3600.00, 6, 0, 'https://www.flipkart.com', '2024-09-10 02:20:35', '2024-09-10 10:47:33', NULL),
(71, 'test', 'uuuu', 6, 5, 18998.00, 9999.00, 6, 0, 'https://www.flipkart.com', '2024-09-10 05:52:15', NULL, NULL),
(74, 'testing', 'testing', 6, 5, 6598.00, 5599.00, 5, 0, 'https://www.flipkart.com', '2024-09-11 06:59:47', NULL, NULL),
(75, 'testing1', 'test ddees', 7, 5, 5555.00, 4444.00, 7, 0, 'https://www.flipkart.com', '2024-09-11 07:02:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`id`, `name`, `description`, `image`, `created_at`, `modified_at`, `deleted_at`) VALUES
(6, 'Smartphone', 'Buy Smartphones from Wide range of smartphones from popular brands like \r\nSamsung, Apple, Realme,Oppo,Vivo,Poco, Motorola,Asus & more.', 'WhatsApp Image 2024-08-26 at 12.05.50 PM.jpeg', '2022-07-28 13:23:04', '2024-08-26 03:10:22', NULL),
(7, 'Tablet', 'Shop for Latest Android Tablets in India at Flipkart.com. Check specifications, prices, ratings, reviews of Android Tablets made by Samsung, Lenovo, ...', 'WhatsApp Image 2024-08-26 at 12.05.05 PM.jpeg', '2022-07-28 13:23:19', '2024-08-26 03:10:51', NULL),
(8, 'Mobile Accessories', 'From mobile cases and covers, screen guards, chargers, cables, power banks, selfie sticks, memory cards, headphones to cleaning kits, enhance your mobile usage with our vast collection of nifty mobile accessories at exciting rates. Read reviews, compare prices and specs and only then take your pick. Flipkart is out to make your shopping experience quicker and simpler!', 'mobile-accessories.png', '2022-07-28 13:30:34', '2022-08-02 22:44:40', NULL),
(9, 'Laptop', 'Fulfilling all your tasks at work and home, laptops can be distinguished based on their size, portability, user-friendly interface, processor speeds, RAM &...\r\n', 'laptop.jpg', '2022-07-28 13:23:58', '2022-08-02 22:44:51', NULL),
(10, 'Computer Peripherals', 'Every modern home deserves a computer system, and every computer system deserves its posse of accessories. Here to help you make full use of your desktop is our collection of computer peripherals - we have printers, ink cartridges, ink toners, projectors, scanners, and personal PCs. Explore more, do more, with these computer necessities.', 'Computer-Accessories.png', '2022-07-27 21:55:29', '2022-08-02 22:45:01', NULL),
(11, 'Televisions', 'These days, it is almost surprising to find homes that do not have television sets. This is mainly because televisions have become such an important asset in every household. Televisions, by and large, are very essential than other appliances and are highly resourceful and entertaining in a variety of ways. Though, today’s generation mostly makes use of smartphones and other connected devices for entertainment, news, and binge-watching purposes, they still cannot beat the experience that a TV can deliver. In the present day, people mostly prefer LED, OLED, QLED, or smart TVs. Enjoy the incredible experience that the television presents and immerse yourself in the content. With enhanced visuals in modern TVs, you wouldn’t feel to take your eyes off the TV screen. Explore a wide range of smart TV options from Mi TVs, VU TVs, SAMSUNG, LG, SONY, and a myriad of other TV brands. It is best to research TV prices before making a purchase so that you can get your television set for the best price. For those who prefer LED variants, check online at Flipkart for LED TV prices and order for the best deals. ', 'television.png', '2022-08-02 22:43:56', '2022-08-02 22:43:56', NULL),
(12, 'Air Conditioners', 'An AC is just what you need to escape the unbearable heat of Indian summers and get a good night’s sleep. Select a split air conditioner for a larger room and efficient cooling or a window air conditioner for a compact space. Purchasing an air conditioner for all the rooms in a house might be tricky, so a portable AC 1.5 ton can do the job of cooling easily. Check out the various models of Lloyd portable ACs and other brands online. A tower air conditioner is a perfect choice for commercial spaces because of its instant cooling feature and portability. You can easily check the top 10 AC brands in India for all the categories mentioned above online to make the perfect choice for you. When choosing an AC for your home, choose between copper and aluminium condenser coils which affect the efficiency and cost of the machine. For window air conditioners, check out various Panasonic AC 1.5 ton models online. Panasonic CW-XN182AM has a copper condenser coil and an auto-restart feature. You can check and compare with Whirlpool AC 1.5 ton prices and features before making any decision. Brands like Voltas, LG, Hitachi, Lloyd, etc., offer user-friendly and durable air conditioners online.', 'air-conditioners.jpg', '2022-08-02 22:55:40', '2022-08-02 22:55:40', NULL),
(13, 'Refrigerators', 'Double door fridges offer extra storage space and an enhanced cooling effect that helps to preserve food for a longer period of time. The storage area in the fridge and the freezer seems to be ideal for a family of 4 to 5. These refrigerators boast elegant designs packed with a host of features that can auto adjust the chillness, detect temperature automatically, save energy by itself, work even during power cuts due to the smart inverter technology, retain freshness all the time due to the inbuilt deodorizer, and more. The uniform cooling feature allows for all-around cooling, keeping the perishables fresh and long-lasting. These fridges usually come in the capacity range of 230 to 500 liters. Double door fridge prices are usually lower during seasonal sales and festive discounts. Keep looking for ongoing offers online at Flipkart and place your order for your refrigerator model at the earliest, so you don’t miss out on the best deals. Brands like SAMSUNG, Whirlpool, LG, Haier, Godrej, etc. sell double door refrigerators in various colours and capacities with a range of innovative features.', 'refrigerators.jpg', '2022-08-02 23:04:04', '2022-08-02 23:04:04', NULL),
(14, 'Home Decor', 'Home Decor', 'product-3.jpg', '2024-09-02 03:10:41', '2024-09-02 03:10:41', NULL),
(39, 'Test Catgory', 'Test Description', '66e122ec036f3_flipkart-icon (2).jpg', '2024-09-09 00:47:12', '2024-09-11 01:28:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `url`) VALUES
(2, 2, '66cd8d01ac4ed_smartphone.jpeg'),
(4, 3, '66cd8dd10e07a_product-6.jpg'),
(5, 4, '66cd8de21bc09_product-6.jpg'),
(6, 4, '66cd8de21bc09_smartphone.jpeg'),
(7, 4, '66cd8de21bc09_smartphone2.jpeg'),
(8, 5, '66cd8deee8efc_smartphone2.jpeg'),
(9, 6, '66cd8dff52b77_smartphone.jpeg'),
(10, 7, '66cd8e0bd4b31_smartphone.jpeg'),
(11, 8, '66cd8e14d63ee_product-1.jpg'),
(12, 9, '66cd8e217df65_product-6.jpg'),
(14, 11, '66cd8e3e44e8c_smartphone2.jpeg'),
(15, 12, '66cd8e481a26c_product-6.jpg'),
(17, 14, '66cd8e5b012aa_product-1.jpg'),
(18, 21, '66cd8e66d32e8_smartphone2.jpeg'),
(19, 24, '66cd8e7116d0e_smartphone.jpeg'),
(20, 26, '66cd8e7c9e676_WhatsApp Image 2024-08-26 at 12.05.05 PM.jpeg'),
(59, 10, 'product-7.jpg'),
(60, 13, '66d587630026aproduct-1.jpg'),
(61, 13, '66d5876301847product-2.jpg'),
(64, 1, '66d58a2254016_product-8.jpg'),
(65, 1, '66d58a98b1fb8_product-1.jpg'),
(66, 1, '66d58a98b1fb8_product-3.jpg'),
(67, 1, '66d58a98b1fb8_product-4.jpg'),
(68, 1, '66d58a98b1fb8_product-5.jpg'),
(69, 1, '66d58ac1a5de7_product-2.jpg'),
(71, 9, '66d841ff0fbed_myntra-icon.jpg'),
(75, 9, '66d93c10daa3fimresizer-1725257539601.jpg'),
(77, 40, '66d96e6325fa1imresizer-1725257539601.jpg'),
(78, 41, '66d96ea39c3e2imresizer-1725257539601.jpg'),
(79, 42, '66d96ec929c6bimresizer-1725257539601.jpg'),
(80, 42, '66d96ec92a6daimresizer-1725257539601 - Copy.jpg'),
(81, 43, 'imresizer-1725257539601 - Copy.jpg'),
(83, 46, '66dbf1de87523_imresizer-1725257539601.jpg'),
(85, 54, '66dfd51e265d4_imresizer-1725257539601.jpg'),
(86, 55, '66dfde2b50d93_imresizer-1725257539601.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `account_no` int(11) NOT NULL,
  `expiry` date NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `payment_type`, `provider`, `account_no`, `expiry`, `email`, `password`) VALUES
(1, 'Vivek Kumar', '', '', 0, '0000-00-00', 'vivekkumarvk1707@gmail.com', ''),
(4, 'user vivek', '', '', 0, '0000-00-00', 'user1@gmail.com', 'user@1'),
(5, 'Vivek-user-1', '', '', 0, '0000-00-00', 'user@gmail.com', '1234'),
(7, 'Vivek Kumar', '', '', 0, '0000-00-00', 'vk1@gmail.com', ''),
(11, 'vivek1707', '', '', 0, '0000-00-00', 'vivek1707@gmail.com', 'vivek@17'),
(14, 'Vivek Kumar', '', '', 0, '0000-00-00', 'vk1@gmail.com', ''),
(15, 'Vivek Kumar', '', '', 0, '0000-00-00', 'vk1@gmail.com', ''),
(16, 'mahto', '', '', 0, '0000-00-00', 'mahto@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int(11) NOT NULL,
  `user_id` int(50) NOT NULL,
  `address_line1` varchar(50) NOT NULL,
  `address_line2` varchar(50) NOT NULL,
  `city` varchar(20) NOT NULL,
  `postal_code` varchar(6) NOT NULL,
  `country` varchar(50) NOT NULL,
  `telephone` varchar(11) NOT NULL,
  `mobile` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_payment`
--

CREATE TABLE `user_payment` (
  `id` int(11) NOT NULL,
  `user_id` int(50) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ecommerce_store`
--
ALTER TABLE `ecommerce_store`
  ADD PRIMARY KEY (`estore_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `estore_id` (`estore_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_ibfk_1` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_payment`
--
ALTER TABLE `user_payment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ecommerce_store`
--
ALTER TABLE `ecommerce_store`
  MODIFY `estore_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_details` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`estore_id`) REFERENCES `ecommerce_store` (`estore_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
