-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2020 at 05:31 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bavas`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groups`
--

CREATE TABLE `tbl_groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_groups`
--

INSERT INTO `tbl_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'sales', 'Sales'),
(3, 'subscriber', 'Subscriber');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login_attempts`
--

CREATE TABLE `tbl_login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_login_attempts`
--

INSERT INTO `tbl_login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(1, '::1', 'sankar@gmail.com', 1579612600),
(2, '::1', 'sankar@gmail.com', 1579612608),
(3, '::1', 'sankar@gmail.com', 1579612631);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `commission_price` float NOT NULL,
  `company_price` float NOT NULL,
  `active` enum('0','1') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `name`, `description`, `commission_price`, `company_price`, `active`, `created_by`, `created_date`) VALUES
(1, 'Bun', '', 13, 16, '1', 1, '2020-01-21 15:59:38'),
(2, 'Bread', 'desc', 21, 25, '1', 1, '2020-01-21 16:12:55'),
(3, 'Anarkali', '', 17, 20, '1', 1, '2020-01-21 16:13:44'),
(5, 'Kari', '', 20, 24, '1', 1, '2020-01-21 15:58:24'),
(6, 'resk', '', 16, 20, '1', 1, '2020-01-21 16:14:03'),
(7, 'dornut', '', 17, 20, '1', 1, '2020-01-21 15:54:22'),
(8, 'royal', '', 26, 30, '1', 1, '2020-01-21 15:55:23'),
(9, 'cup cake', '', 20, 24, '1', 1, '2020-01-21 15:56:32'),
(10, 'pan cake', '', 22, 25, '1', 1, '2020-01-21 15:57:07'),
(11, 'sweet role', '', 13, 16, '1', 1, '2020-01-21 15:57:55'),
(12, 'cone biscuit', '', 22, 25, '1', 1, '2020-01-21 15:59:11'),
(13, 'biscuit 200grm', '', 26, 30, '1', 1, '2020-01-21 16:02:45'),
(14, 'nankatti 190grm', '', 27, 30, '1', 1, '2020-01-21 16:03:40'),
(15, 'butter 180grm', '', 17, 20, '1', 1, '2020-01-21 16:04:38'),
(16, 'butter biscuit 350grm', '', 35, 40, '1', 1, '2020-01-21 16:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales`
--

CREATE TABLE `tbl_sales` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `price_type` enum('company_price','commission_price') NOT NULL,
  `description` text NOT NULL,
  `sales_man` int(11) NOT NULL,
  `sale_amount` double NOT NULL,
  `credit_amount` double NOT NULL,
  `extra_expense` text NOT NULL,
  `loss_and_profit` float NOT NULL,
  `total_expense_amount` float NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_user` int(11) NOT NULL,
  `last_update` datetime NOT NULL,
  `last_updated_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sales`
--

INSERT INTO `tbl_sales` (`id`, `date`, `price_type`, `description`, `sales_man`, `sale_amount`, `credit_amount`, `extra_expense`, `loss_and_profit`, `total_expense_amount`, `created_date`, `created_user`, `last_update`, `last_updated_user`) VALUES
(1, '2020-01-21', 'commission_price', '', 14, 6745, 0, '{\"0\":{\"name\":\"diesel\",\"amount\":\"400\"},\"1579623532299\":{\"name\":\"driver\",\"amount\":\"600\"},\"1579623552132\":{\"name\":\"food\",\"amount\":\"200\"},\"1579623562045\":{\"name\":\"redection\",\"amount\":\"20\"},\"1579623582055\":{\"name\":\"sales salary\",\"amount\":\"600\"}}', 4925, 1820, '2020-01-21 16:20:22', 1, '2020-01-21 17:20:22', 1),
(2, '2020-01-21', 'commission_price', '', 15, 13, 0, '[]', 13, 0, '2020-01-21 16:20:56', 1, '2020-01-21 17:20:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales_details`
--

CREATE TABLE `tbl_sales_details` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `return` int(11) NOT NULL,
  `damage` int(11) NOT NULL,
  `sale` int(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sales_details`
--

INSERT INTO `tbl_sales_details` (`id`, `sale_id`, `product_id`, `product_name`, `price`, `quantity`, `return`, `damage`, `sale`, `amount`) VALUES
(1, 1, 1, 'Bun', 13, 65, 5, 3, 57, 741),
(2, 1, 2, 'Bread', 21, 110, 6, 7, 97, 2037),
(3, 1, 3, 'Anarkali', 17, 60, 5, 6, 49, 833),
(4, 1, 5, 'Kari', 20, 20, 3, 0, 17, 340),
(5, 1, 6, 'resk', 16, 15, 3, 0, 12, 192),
(6, 1, 7, 'dornut', 17, 60, 3, 6, 51, 867),
(7, 1, 8, 'royal', 26, 15, 0, 2, 13, 338),
(8, 1, 9, 'cup cake', 20, 30, 0, 2, 28, 560),
(9, 1, 10, 'pan cake', 22, 30, 0, 2, 28, 616),
(10, 1, 11, 'sweet role', 13, 30, 11, 2, 17, 221),
(11, 1, 12, 'cone biscuit', 22, 0, 0, 0, 0, 0),
(12, 1, 13, 'biscuit 200grm', 26, 0, 0, 0, 0, 0),
(13, 1, 14, 'nankatti 190grm', 27, 0, 0, 0, 0, 0),
(14, 1, 15, 'butter 180grm', 17, 0, 0, 0, 0, 0),
(15, 1, 16, 'butter biscuit 350grm', 35, 0, 0, 0, 0, 0),
(16, 2, 1, 'Bun', 13, 2, 1, 0, 1, 13),
(17, 2, 2, 'Bread', 21, 0, 0, 0, 0, 0),
(18, 2, 3, 'Anarkali', 17, 0, 0, 0, 0, 0),
(19, 2, 5, 'Kari', 20, 0, 0, 0, 0, 0),
(20, 2, 6, 'resk', 16, 0, 0, 0, 0, 0),
(21, 2, 7, 'dornut', 17, 0, 0, 0, 0, 0),
(22, 2, 8, 'royal', 26, 0, 0, 0, 0, 0),
(23, 2, 9, 'cup cake', 20, 0, 0, 0, 0, 0),
(24, 2, 10, 'pan cake', 22, 0, 0, 0, 0, 0),
(25, 2, 11, 'sweet role', 13, 0, 0, 0, 0, 0),
(26, 2, 12, 'cone biscuit', 22, 0, 0, 0, 0, 0),
(27, 2, 13, 'biscuit 200grm', 26, 0, 0, 0, 0, 0),
(28, 2, 14, 'nankatti 190grm', 27, 0, 0, 0, 0, 0),
(29, 2, 15, 'butter 180grm', 17, 0, 0, 0, 0, 0),
(30, 2, 16, 'butter biscuit 350grm', 35, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2y$12$4dD1TPurd6fwasc4K3Sg0Olwn.AdYKQKHfGxRZ/NoRAC0K9hrkJCi', 'nazeer', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1579621890, 1, 'Administrator', '01', 'ADMIN', '0'),
(14, '::1', 'sankar@gmail.com', '$2y$10$AHWhggmZjzem6VOrmQQzWOgJjpVACl8hLR/yd5qWrooJMzhktek6u', 'sankar@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1579600218, NULL, 1, 'Sankar', '01', NULL, '123456'),
(15, '::1', 'shafeek@gmail.com', '$2y$10$m70XDkaAu/G2qCzjdUFYceYBF4jxQ28.RidYYkWB/NpDrMRhkXavm', 'shafeek@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1579600249, NULL, 1, 'Shafeek', '02', NULL, '123456'),
(16, '::1', 'thoufeek@gmail.com', '$2y$10$8FsjUTWSEnv4CM1XxJOcyOAPg5obMoQRTrYhamZHR7Ro7j2edTHUu', 'thoufeek@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1579600281, NULL, 1, 'Thoufeek', '03', NULL, '123456');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_groups`
--

CREATE TABLE `tbl_users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_groups`
--

INSERT INTO `tbl_users_groups` (`id`, `user_id`, `group_id`) VALUES
(54, 1, 1),
(55, 1, 2),
(56, 1, 3),
(64, 14, 2),
(65, 14, 3),
(62, 15, 2),
(63, 15, 3),
(60, 16, 2),
(61, 16, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_login_attempts`
--
ALTER TABLE `tbl_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sales_details`
--
ALTER TABLE `tbl_sales_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_email` (`email`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`);

--
-- Indexes for table `tbl_users_groups`
--
ALTER TABLE `tbl_users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_groups`
--
ALTER TABLE `tbl_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_login_attempts`
--
ALTER TABLE `tbl_login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_sales_details`
--
ALTER TABLE `tbl_sales_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_users_groups`
--
ALTER TABLE `tbl_users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_users_groups`
--
ALTER TABLE `tbl_users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `tbl_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
