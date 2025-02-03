-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 08:49 PM
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
-- Database: `pms_copy`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `admin_email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `admin_phone` varchar(11) NOT NULL,
  `admin_status` enum('Active','Inactive','Deleted') NOT NULL,
  `admin_type` int(11) DEFAULT NULL COMMENT '1: Admin\r\n2: Employee',
  `admin_created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_paid`
--

CREATE TABLE `bill_paid` (
  `bp_id` int(11) NOT NULL,
  `bp_company_id` int(11) DEFAULT NULL,
  `bp_purchase_id` int(11) DEFAULT NULL,
  `bp_amount` decimal(10,2) DEFAULT 0.00,
  `bp_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `contact_info_id` int(11) NOT NULL,
  `contact_info_company_id` int(11) NOT NULL,
  `contact_info_name` varchar(100) NOT NULL,
  `contact_info_email` varchar(100) DEFAULT NULL,
  `contact_info_phone` varchar(11) NOT NULL,
  `contact_info_fax` varchar(15) DEFAULT NULL,
  `contact_info_designation` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `customer_phone` varchar(11) DEFAULT NULL,
  `customer_address` mediumtext DEFAULT NULL,
  `customer_status` enum('Active','Inactive') NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_wise_order`
--

CREATE TABLE `customer_wise_order` (
  `cwo_id` int(11) NOT NULL,
  `cwo_customer_id` int(11) NOT NULL,
  `order_info_id` int(11) DEFAULT NULL,
  `cwo_order_id` varchar(25) NOT NULL,
  `cwo_order_total` decimal(10,2) NOT NULL,
  `cwo_due` decimal(10,2) DEFAULT NULL,
  `cwo_date` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `due_paid`
--

CREATE TABLE `due_paid` (
  `dp_id` int(11) NOT NULL,
  `dp_customer_id` int(11) DEFAULT NULL,
  `dp_amount` decimal(10,2) DEFAULT NULL,
  `dp_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(11) NOT NULL,
  `expense_criteria` varchar(255) NOT NULL,
  `expense_amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_code` varchar(25) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `item_company_id` int(11) DEFAULT NULL,
  `item_quantity` int(11) DEFAULT NULL,
  `item_rack_no` varchar(25) DEFAULT NULL,
  `item_buy_price` decimal(10,2) DEFAULT NULL,
  `item_sell_price` decimal(10,2) DEFAULT NULL,
  `item_expire_date` varchar(25) DEFAULT NULL,
  `item_reorder_level` int(11) DEFAULT NULL,
  `item_status` enum('Active','Inactive') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `item_category_id` int(11) NOT NULL,
  `item_category_name` varchar(100) NOT NULL,
  `item_category_status` enum('Active','Inactive') NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_company`
--

CREATE TABLE `item_company` (
  `item_company_id` int(11) NOT NULL,
  `item_company_name` varchar(255) NOT NULL,
  `item_company_status` enum('Active','Inactive') NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_details_id` int(11) NOT NULL,
  `order_details_order_info_id` varchar(50) NOT NULL,
  `order_info_id` int(11) DEFAULT NULL,
  `order_details_item_id` int(11) NOT NULL,
  `order_details_item_name` varchar(255) NOT NULL,
  `order_details_item_buy_price` decimal(10,2) NOT NULL,
  `order_details_item_sell_price` decimal(10,2) NOT NULL,
  `order_details_item_qty` int(11) NOT NULL,
  `order_details_item_expire_date` date NOT NULL,
  `order_details_item_profit` decimal(10,2) NOT NULL,
  `order_details_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_info`
--

CREATE TABLE `order_info` (
  `order_info_id` int(11) NOT NULL,
  `order_info_session_id` varchar(255) DEFAULT NULL,
  `order_info_track_no` varchar(50) DEFAULT NULL,
  `order_info_subtotal` decimal(10,2) DEFAULT NULL,
  `order_info_discount_type` int(11) DEFAULT NULL COMMENT '1:Flat\r\n2:Percentage',
  `order_info_discount` decimal(10,2) DEFAULT NULL,
  `order_info_total` decimal(10,2) DEFAULT NULL,
  `order_info_due` decimal(10,2) DEFAULT NULL,
  `order_info_date` date DEFAULT NULL,
  `order_info_status` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchase_id` int(11) NOT NULL,
  `purchase_company_id` int(11) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_invoice_no` varchar(100) DEFAULT NULL,
  `purchase_total_amount` decimal(10,2) DEFAULT NULL,
  `purchase_mode` enum('Paid','Due') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `id` int(11) NOT NULL,
  `store_title` varchar(100) NOT NULL,
  `store_name` varchar(25) NOT NULL,
  `store_email` varchar(50) DEFAULT NULL,
  `store_phone` varchar(11) DEFAULT NULL,
  `store_address` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_order`
--

CREATE TABLE `temp_order` (
  `temp_order_id` int(11) NOT NULL,
  `temp_order_session_id` varchar(255) NOT NULL,
  `temp_order_item_id` int(11) NOT NULL,
  `temp_order_item_name` varchar(255) NOT NULL,
  `temp_order_qty` int(11) NOT NULL,
  `temp_order_item_buy_price` decimal(10,2) NOT NULL,
  `temp_order_item_sell_price` decimal(10,2) NOT NULL,
  `temp_order_item_expire_date` date NOT NULL,
  `temp_order_total` decimal(10,2) NOT NULL,
  `temp_order_profit` decimal(10,2) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `admin_name` (`admin_name`,`admin_phone`);

--
-- Indexes for table `bill_paid`
--
ALTER TABLE `bill_paid`
  ADD PRIMARY KEY (`bp_id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`contact_info_id`),
  ADD KEY `contact_info_name` (`contact_info_name`),
  ADD KEY `contact_info_email` (`contact_info_email`),
  ADD KEY `contact_info_phone` (`contact_info_phone`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_name` (`customer_name`),
  ADD KEY `customer_phone` (`customer_phone`);

--
-- Indexes for table `customer_wise_order`
--
ALTER TABLE `customer_wise_order`
  ADD PRIMARY KEY (`cwo_id`),
  ADD KEY `cwo_order_id` (`cwo_order_id`),
  ADD KEY `cwo_customer_id` (`cwo_customer_id`),
  ADD KEY `order_info_id` (`order_info_id`);

--
-- Indexes for table `due_paid`
--
ALTER TABLE `due_paid`
  ADD PRIMARY KEY (`dp_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `expense_criteria` (`expense_criteria`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `medicine_code` (`item_code`),
  ADD KEY `medicine_name` (`item_name`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`item_category_id`),
  ADD KEY `medicine_category_name` (`item_category_name`);

--
-- Indexes for table `item_company`
--
ALTER TABLE `item_company`
  ADD PRIMARY KEY (`item_company_id`),
  ADD KEY `medicine_company_name` (`item_company_name`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_details_id`),
  ADD KEY `order_details_order_info_id` (`order_details_order_info_id`),
  ADD KEY `order_info_id` (`order_info_id`);

--
-- Indexes for table `order_info`
--
ALTER TABLE `order_info`
  ADD PRIMARY KEY (`order_info_id`),
  ADD KEY `order_info_track_no` (`order_info_track_no`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchase_id`),
  ADD UNIQUE KEY `purchase_invoice_no` (`purchase_invoice_no`),
  ADD KEY `purchase_invoice_no_2` (`purchase_invoice_no`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_order`
--
ALTER TABLE `temp_order`
  ADD PRIMARY KEY (`temp_order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_paid`
--
ALTER TABLE `bill_paid`
  MODIFY `bp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `contact_info_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_wise_order`
--
ALTER TABLE `customer_wise_order`
  MODIFY `cwo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `due_paid`
--
ALTER TABLE `due_paid`
  MODIFY `dp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `item_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_company`
--
ALTER TABLE `item_company`
  MODIFY `item_company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_info`
--
ALTER TABLE `order_info`
  MODIFY `order_info_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_order`
--
ALTER TABLE `temp_order`
  MODIFY `temp_order_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
