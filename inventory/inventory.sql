-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 02:29 PM
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
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `company_name` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `added_on` datetime DEFAULT current_timestamp(),
  `last_interaction` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `company`, `address`, `is_active`, `company_name`, `status`, `added_on`, `last_interaction`, `created_at`) VALUES
(1, 'Test', 'test@gmail.com', '1234567890', 'Live In Computers', 'sdrg', 1, NULL, 'Active', '2025-07-05 15:40:22', NULL, '2025-07-05 12:10:22'),
(2, 'Test', 'test@gmail.com', '1234567890', 'Live In Computers', 'ugusd', 1, NULL, 'Active', '2025-07-05 15:40:44', NULL, '2025-07-05 12:10:44');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`id`, `customer_name`, `email`, `phone`, `product_id`, `quantity`, `order_date`) VALUES
(1, 'vishal', 'test@gmail.com', '1234567890', 1, 1, '2025-07-04 11:28:01'),
(2, 'vishalll', 'social.media@liveincomputers.com', '1234567890', 6, 1, '2025-07-04 11:53:47'),
(3, 'rajj', 'social.media@liveincomuters.com', '879676797', 8, 1, '2025-07-04 11:54:18'),
(4, 'sahil', 'social.media@liveincomuters.com', '9076000529', 1, 1, '2025-07-07 04:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `customer_services`
--

CREATE TABLE `customer_services` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_active` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `role`, `mobile`, `email`, `gender`, `is_active`, `last_active`) VALUES
(1, 'vishal', 'dev', '1234567890', 'test@gmail.com', 'Male', 1, '2025-07-05 09:50:41');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `pickup_address` text DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `status` enum('New','Scheduled','Completed') DEFAULT 'New',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `customer_name`, `contact_number`, `pickup_address`, `service_type`, `pickup_date`, `status`, `created_at`) VALUES
(1, 'vishal', '7789456', 'gorgoan', 'repair', '2025-07-03', 'New', '2025-07-07 07:37:32'),
(2, 'vishal', '7789456', 'gorgoan', 'repair', '2025-07-03', 'New', '2025-07-07 07:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `quantity`, `price`, `order_date`, `customer_name`, `customer_email`, `customer_phone`) VALUES
(1, 1, 1, 19999.00, '2025-07-04 12:13:52', NULL, NULL, NULL),
(2, 2, 1, 49999.00, '2025-07-04 12:14:52', NULL, NULL, NULL),
(3, 1, 1, 19999.00, '2025-07-04 12:39:10', NULL, NULL, NULL),
(4, 1, 2, 19999.00, '2025-07-04 12:39:40', NULL, NULL, NULL),
(5, 1, 1, 19999.00, '2025-07-04 16:20:43', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pickup_delivery`
--

CREATE TABLE `pickup_delivery` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Picked','Delivered') DEFAULT 'Pending',
  `pickup_date` datetime DEFAULT current_timestamp(),
  `delivery_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_delivery`
--

INSERT INTO `pickup_delivery` (`id`, `order_id`, `employee_id`, `status`, `pickup_date`, `delivery_date`, `notes`) VALUES
(1, 4, 1, 'Delivered', '2025-07-07 10:59:10', '2025-07-07 10:59:15', 'pura paisa lena');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `stock`, `image_url`, `price`, `category`) VALUES
(1, 'Smartphone', 13, 'images\\download.jpeg', 19999.00, 'Mobile'),
(2, 'Laptop', 14, 'images\\download (1).jpeg', 49999.00, 'Computers'),
(3, 'Tablet', 10, 'images/download (2).jpeg', 29999.00, 'Mobile'),
(4, 'Smartwatch', 25, 'images/download (3).jpeg', 9999.00, 'Wearables'),
(5, 'Bluetooth Speaker', 30, 'images/download (4).jpeg', 2499.00, 'Audio'),
(6, 'Wireless Mouse', 49, 'images/download (5).jpeg', 799.00, 'Accessories'),
(7, 'Gaming Console', 5, 'images/download (6).jpeg', 39999.00, 'Gaming'),
(8, 'Drone Camera', 7, 'images/download (7).jpeg', 59999.00, 'Cameras');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `sales_number` varchar(50) DEFAULT NULL,
  `sales_no` varchar(50) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `product_name` text DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_received` decimal(10,2) DEFAULT NULL,
  `payment_remaining` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `sales_number`, `sales_no`, `customer_name`, `product_name`, `total_amount`, `payment_received`, `payment_remaining`, `payment_status`, `created_at`) VALUES
(1, '1', NULL, 'misbahh', NULL, 1000.00, 1000.00, 0.00, 'Paid', '2025-07-05 07:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `is_active`, `created_at`) VALUES
(1, 'Laptop Repair ', '', 0.00, 0, '2025-07-05 18:37:05'),
(2, 'Laptop Repair ', NULL, NULL, 1, '2025-07-05 18:37:24'),
(3, 'Laptop Repair ', '', 100.00, 1, '2025-07-05 18:37:30'),
(4, 'Mac repair', NULL, NULL, 1, '2025-07-05 18:38:08'),
(5, 'Mac repair', NULL, NULL, 1, '2025-07-05 18:38:11'),
(6, 'Mac repair', '', 2000.00, 1, '2025-07-05 15:10:21'),
(7, 'Mac repair', '', 2000.00, 1, '2025-07-05 15:10:24'),
(8, 'Mac repair', '', 2000.00, 1, '2025-07-05 15:14:53'),
(9, 'Pc Repair', '', 900.00, 1, '2025-07-05 15:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `task_title` varchar(255) DEFAULT NULL,
  `task_description` text DEFAULT NULL,
  `assigned_date` datetime DEFAULT current_timestamp(),
  `due_date` date DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `employee_id`, `task_title`, `task_description`, `assigned_date`, `due_date`, `status`) VALUES
(1, 1, 'make a website', 'huiyihgdi', '2025-07-05 15:17:36', '2025-08-11', 'Completed'),
(2, 1, 'make a website', 'huiyihgdi', '2025-07-05 15:19:42', '2025-08-11', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') DEFAULT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$LxCStuGLcY.qr09lsIXDw.MfcjuswPB0CqybCWOA5wjkY3XCu5GkC', 'employee'),
(2, 'alice', '{PASSWORD_HASH_HERE}', 'admin'),
(3, 'bob', '{PASSWORD_HASH_HERE}', 'employee'),
(4, 'bob', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa6l0LrJz9nYOR8j6b/8x3POJ.e', 'employee'),
(5, 'vishal', '$2y$10$xWoNTVtaDoYJYzIy9.TkOOfkHiMF6vqS6Rk7nN9P8pTqZzaXXRNXO', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_services`
--
ALTER TABLE `customer_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pickup_delivery`
--
ALTER TABLE `pickup_delivery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `employee_id` (`employee_id`);

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
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_services`
--
ALTER TABLE `customer_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pickup_delivery`
--
ALTER TABLE `pickup_delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_services`
--
ALTER TABLE `customer_services`
  ADD CONSTRAINT `customer_services_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `customer_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `pickup_delivery`
--
ALTER TABLE `pickup_delivery`
  ADD CONSTRAINT `pickup_delivery_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `pickup_delivery_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
