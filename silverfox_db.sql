-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2024 at 09:33 AM
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
-- Database: `silverfox_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `national_id` varchar(20) NOT NULL,
  `residence_or_work` varchar(255) NOT NULL,
  `mobile_phone` varchar(15) NOT NULL,
  `work_phone` varchar(15) DEFAULT NULL,
  `personal_phone` varchar(15) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `employee_id` int(11) DEFAULT NULL,
  `last_active` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `full_name`, `national_id`, `residence_or_work`, `mobile_phone`, `work_phone`, `personal_phone`, `username`, `password`, `qr_code_path`, `created_at`, `employee_id`, `last_active`) VALUES
(2, 'ايهاب علي ابو شنب ', '120600600', 'طفس', '0962075999', '0152772', '1962075261', 'ayhab_tfs_4687', '$2y$10$BgFmC0LJYWQdUz2Bd7Lsm.LUJjgC5bnKDUNsjOZ348TQ4Fhd7WhHa', '../uploads/qrcodes/ayhab_tfs_4687_qr.png', '2024-10-19 20:23:25', 9, '2024-10-23 20:03:50'),
(4, 'محمد نعيم البردان ', '12060012121', 'طفس', '0162047123', '', '', 'mhmd_tfs_4857', '$2y$10$z07RAZ3n6Kl2ypfP9r559.C9lw1PW8Z07T1WO/wdlD5xnS4Sg1J3S', '../uploads/qrcodes/mhmd_tfs_4857_qr.png', '2024-10-20 15:49:54', 9, '2024-10-23 20:04:32');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `national_id` varchar(11) NOT NULL,
  `residence` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `start_date` date NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `path_qr_Employee` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `full_name`, `national_id`, `residence`, `role`, `shift_start`, `shift_end`, `start_date`, `username`, `password`, `created_at`, `path_qr_Employee`) VALUES
(1, 'اسمامة الرميلاي', '120600100', 'طفس', 'supervisor', '10:00:00', '16:00:00', '2024-02-20', 'asmamh9293', '$2y$10$Njq5axkXOrCKN5NHa8Z6AuwdJkwUPDT.6ZCgpyikeWe7xkIKYEXKq', '2024-10-20 06:49:47', NULL),
(4, 'محمد تيسير الفقيه ', '120600123', 'درعا المحطة', 'general_manager', '08:00:00', '16:00:00', '2024-10-01', 'mhmd4154', '$2y$10$A03k8XQ8n7LqP7S1xXaj3egm7Fe.1PhWRNmNHP8Xb25UmAjyopPQS', '2024-10-20 07:38:35', NULL),
(5, 'Mahmoud ali', '12060035839', 'طفس', 'general_manager', '08:04:00', '16:00:00', '2024-10-20', 'mahmoud1721', '$2y$10$JGqRBtLxy7wgW9WHlgZEsuyhXND2x0urS9nmhMYzSRGI9S22yVKLS', '2024-10-20 08:45:17', NULL),
(6, 'ali', '12060012345', 'طفس', 'general_manager', '09:00:00', '21:00:00', '2024-10-21', 'ali7052', '$2y$10$1nzohdmPqBSuIzZwFbeW3utCR7LPMEDkoWxShCGedMgBYTrsZVGFK', '2024-10-20 08:55:12', NULL),
(7, 'ابو زيد ', '12060012333', 'طفس', 'supervisor', '08:00:00', '16:00:00', '2024-10-20', 'abw2675', '$2y$10$1otZuOAG0syjCzyMQzqI0uf.4GPJ7fU/D/.YD.Lhd.IF/kzDTUWgK', '2024-10-20 09:39:34', NULL),
(8, 'علي النابلسي ', '12060054321', 'درعا المحطة', 'accountant', '08:00:00', '16:00:00', '2024-10-15', 'aly8870', '$2y$10$u3ujNOd07.KpIe5IR563NuB63guTbSNfZAUg6Yh3RYKUHe5DR02XS', '2024-10-20 10:56:10', '/uploads/qrcodes/aly8870_qr.png'),
(9, 'Momen ', '12020030012', 'درعا البلد ', 'accountant', '09:00:00', '16:00:00', '2024-10-01', 'momen2466', '$2y$10$5J3/kQs.jt8HDE5LRWwTBeb4n7rmEfnYX4hEss0asDywsKWq0w2/W', '2024-10-20 11:01:41', '/uploads/qrcodes/momen2466_qr.png');

-- --------------------------------------------------------

--
-- Table structure for table `employee_activity`
--

CREATE TABLE `employee_activity` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `activity_description` text NOT NULL,
  `activity_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_tracking`
--

CREATE TABLE `login_tracking` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `user_type` enum('employee','client') NOT NULL,
  `username` varchar(255) NOT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `device_ip` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_tracking`
--

INSERT INTO `login_tracking` (`id`, `employee_id`, `client_id`, `user_type`, `username`, `device_name`, `device_type`, `device_ip`, `location`, `login_time`) VALUES
(1, NULL, 4, 'client', 'mhmd_tfs_4857', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 07:58:36'),
(2, NULL, 4, 'client', 'mhmd_tfs_4857', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 08:01:40'),
(3, 6, NULL, 'employee', 'ali7052', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 08:02:18'),
(4, NULL, 4, 'client', 'mhmd_tfs_4857', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 08:21:37'),
(5, 6, NULL, 'employee', 'ali7052', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 08:28:45'),
(6, 6, NULL, 'employee', 'ali7052', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 08:57:30'),
(7, 6, NULL, 'employee', 'ali7052', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 09:00:05'),
(8, 9, 2, 'client', 'ayhab_tfs_4687', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 08:07:57'),
(9, 9, NULL, 'employee', 'momen2466', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 08:48:51'),
(10, 9, 4, 'client', 'mhmd_tfs_4857', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 09:05:02'),
(11, 9, NULL, 'employee', 'momen2466', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-04 09:29:50'),
(12, 9, NULL, 'employee', 'momen2466', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'Desktop', '::1', 'Unknown Location', '2024-11-05 07:19:10'),
(13, 9, NULL, 'employee', 'momen2466', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.98 Safari/537.36', 'Desktop', '127.0.0.1', 'Unknown Location', '2024-11-06 04:50:32');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sender_role` enum('client','employee') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `client_id`, `employee_id`, `message`, `image_path`, `sender_role`, `created_at`, `sent_at`, `is_read`) VALUES
(178, 4, 9, 'شششششششششش', NULL, 'employee', '2024-10-28 11:23:33', '2024-10-28 14:23:33', 1),
(179, 4, 9, 'شششششششششش', NULL, 'employee', '2024-10-28 11:23:33', '2024-10-28 14:23:33', 1),
(180, 4, 9, 'ش', NULL, 'employee', '2024-10-28 11:24:14', '2024-10-28 14:24:14', 1),
(181, 4, 9, 'ش', NULL, 'employee', '2024-10-28 11:24:14', '2024-10-28 14:24:14', 1),
(182, 4, 9, 'شش', NULL, 'employee', '2024-10-28 11:25:14', '2024-10-28 14:25:14', 1),
(183, 4, 9, 'شش', NULL, 'employee', '2024-10-28 11:25:14', '2024-10-28 14:25:14', 1),
(184, 4, 9, 'a', NULL, NULL, '2024-10-28 11:26:48', '2024-10-28 14:26:48', 1),
(185, 4, 9, 's', NULL, NULL, '2024-10-28 11:26:54', '2024-10-28 14:26:54', 1),
(186, 4, 9, 'as', NULL, NULL, '2024-10-29 06:52:35', '2024-10-29 09:52:35', 1),
(187, 4, 9, 'اختبارالاشعارات ', NULL, 'employee', '2024-10-29 07:19:52', '2024-10-29 10:19:52', 1),
(188, 4, 9, 'اختبارالاشعارات ', NULL, 'employee', '2024-10-29 07:19:52', '2024-10-29 10:19:52', 1),
(189, 4, 4, '11111', NULL, 'employee', '2024-10-29 07:24:19', '2024-10-29 10:24:19', 1),
(190, 4, 4, '11111', NULL, 'employee', '2024-10-29 07:24:19', '2024-10-29 10:24:19', 1),
(191, 4, 4, '22222', NULL, 'employee', '2024-10-29 07:26:26', '2024-10-29 10:26:26', 1),
(192, 4, 4, '22222', NULL, 'employee', '2024-10-29 07:26:26', '2024-10-29 10:26:26', 1),
(193, 4, 4, 'aaaa', NULL, 'employee', '2024-10-29 07:32:24', '2024-10-29 10:32:24', 1),
(194, 4, 4, 'aaaa', NULL, 'employee', '2024-10-29 07:32:24', '2024-10-29 10:32:24', 1),
(195, 4, 4, 'تاكيد اختبار الاشعارات ', NULL, 'employee', '2024-10-29 07:37:09', '2024-10-29 10:37:09', 1),
(196, 4, 4, 'تاكيد اختبار الاشعارات ', NULL, 'employee', '2024-10-29 07:37:09', '2024-10-29 10:37:09', 1),
(197, 4, 4, '33', NULL, 'employee', '2024-10-29 07:38:16', '2024-10-29 10:38:16', 1),
(198, 4, 4, '33', NULL, 'employee', '2024-10-29 07:38:16', '2024-10-29 10:38:16', 1),
(199, 4, 4, 'ششششششششششششششششششششششششششششششششش', NULL, 'employee', '2024-10-29 07:39:40', '2024-10-29 10:39:40', 0),
(200, 4, 4, 'ششششششششششششششششششششششششششششششششش', NULL, 'employee', '2024-10-29 07:39:40', '2024-10-29 10:39:40', 0),
(201, 4, 4, 'شششش', NULL, 'employee', '2024-10-29 07:40:21', '2024-10-29 10:40:21', 0),
(202, 4, 4, 'شششش', NULL, 'employee', '2024-10-29 07:40:21', '2024-10-29 10:40:21', 0),
(203, 4, 9, '1', NULL, 'employee', '2024-10-29 07:42:18', '2024-10-29 10:42:18', 0),
(204, 4, 4, '2', NULL, 'employee', '2024-10-29 07:42:34', '2024-10-29 10:42:34', 0),
(205, 4, 4, '2', NULL, 'employee', '2024-10-29 07:42:34', '2024-10-29 10:42:34', 0),
(206, 4, 4, '3', NULL, 'employee', '2024-10-29 07:43:56', '2024-10-29 10:43:56', 0),
(207, 4, 4, '3', NULL, 'employee', '2024-10-29 07:43:56', '2024-10-29 10:43:56', 0),
(208, 4, 4, 'ششش', NULL, 'employee', '2024-10-29 07:44:36', '2024-10-29 10:44:36', 0),
(209, 4, 4, 'ششش', NULL, 'employee', '2024-10-29 07:44:37', '2024-10-29 10:44:37', 0),
(210, 2, 4, 'ش', NULL, 'employee', '2024-10-29 07:45:25', '2024-10-29 10:45:25', 0),
(211, 2, 4, 'ش', NULL, 'employee', '2024-10-29 07:45:25', '2024-10-29 10:45:25', 0),
(212, 2, 4, 'ئ', NULL, 'employee', '2024-10-29 07:45:29', '2024-10-29 10:45:29', 0),
(213, 2, 4, 'ئ', NULL, 'employee', '2024-10-29 07:45:29', '2024-10-29 10:45:29', 0),
(214, 4, 9, '11', NULL, NULL, '2024-10-29 07:46:55', '2024-10-29 10:46:55', 0),
(215, 4, 4, '4', NULL, 'employee', '2024-10-29 07:50:43', '2024-10-29 10:50:43', 0),
(216, 4, 4, '4', NULL, 'employee', '2024-10-29 07:50:43', '2024-10-29 10:50:43', 0),
(217, 4, 4, 'a', NULL, 'employee', '2024-10-29 07:58:30', '2024-10-29 10:58:30', 0),
(218, 4, 4, 'a', NULL, 'employee', '2024-10-29 07:58:30', '2024-10-29 10:58:30', 0),
(219, 4, 4, '5', NULL, 'employee', '2024-10-29 07:58:59', '2024-10-29 10:58:59', 0),
(220, 4, 4, '5', NULL, 'employee', '2024-10-29 07:58:59', '2024-10-29 10:58:59', 0),
(221, 4, 4, '6', NULL, 'employee', '2024-10-29 07:59:54', '2024-10-29 10:59:54', 0),
(222, 4, 4, '6', NULL, 'employee', '2024-10-29 07:59:54', '2024-10-29 10:59:54', 0),
(223, 4, 4, '33', NULL, 'employee', '2024-10-29 08:03:05', '2024-10-29 11:03:05', 0),
(224, 4, 4, '33', NULL, 'employee', '2024-10-29 08:03:05', '2024-10-29 11:03:05', 0),
(225, 4, 4, '33', NULL, 'employee', '2024-10-29 08:05:44', '2024-10-29 11:05:44', 0),
(226, 4, 4, '33', NULL, 'employee', '2024-10-29 08:05:44', '2024-10-29 11:05:44', 0),
(227, 4, 4, '6', NULL, 'employee', '2024-10-29 08:10:07', '2024-10-29 11:10:07', 0),
(228, 4, 4, '6', NULL, 'employee', '2024-10-29 08:10:07', '2024-10-29 11:10:07', 0),
(229, 4, 4, '33333', NULL, 'employee', '2024-10-29 08:15:42', '2024-10-29 11:15:42', 0),
(230, 4, 4, '33333', NULL, 'employee', '2024-10-29 08:15:42', '2024-10-29 11:15:42', 0),
(231, 4, 9, '3333333333333333333333333333333333333333', NULL, 'employee', '2024-10-29 08:40:57', '2024-10-29 11:40:57', 0),
(232, 4, 9, '3333333333333333333333333333333333333333', NULL, 'employee', '2024-10-29 08:40:57', '2024-10-29 11:40:57', 0),
(233, 4, 4, '3', NULL, 'employee', '2024-10-29 08:45:43', '2024-10-29 11:45:43', 0),
(234, 4, 4, '3', NULL, 'employee', '2024-10-29 08:45:43', '2024-10-29 11:45:43', 0),
(235, 4, 4, '2', NULL, 'employee', '2024-10-29 08:46:34', '2024-10-29 11:46:34', 0),
(236, 4, 4, '2', NULL, 'employee', '2024-10-29 08:46:34', '2024-10-29 11:46:34', 0),
(237, 4, 4, '1', NULL, 'employee', '2024-10-29 08:47:06', '2024-10-29 11:47:06', 0),
(238, 4, 4, '1', NULL, 'employee', '2024-10-29 08:47:06', '2024-10-29 11:47:06', 0),
(239, 4, 4, '66', NULL, 'employee', '2024-10-29 08:48:08', '2024-10-29 11:48:08', 0),
(240, 4, 4, '66', NULL, 'employee', '2024-10-29 08:48:08', '2024-10-29 11:48:08', 0),
(241, 4, 4, '121', NULL, 'employee', '2024-10-29 08:50:40', '2024-10-29 11:50:40', 0),
(242, 4, 4, '121', 'الاستعلامات.png', 'employee', '2024-10-29 08:50:40', '2024-10-29 11:50:40', 0),
(243, 4, 4, 'ششششششششش', NULL, 'employee', '2024-10-29 08:52:42', '2024-10-29 11:52:42', 0),
(244, 4, 4, 'ششششششششش', NULL, 'employee', '2024-10-29 08:52:42', '2024-10-29 11:52:42', 0),
(245, 4, 4, 'aaaaa', NULL, 'employee', '2024-10-29 08:57:46', '2024-10-29 11:57:46', 0),
(246, 4, 4, 'aaaaa', NULL, 'employee', '2024-10-29 08:57:46', '2024-10-29 11:57:46', 0),
(247, 4, 4, 'ششش', NULL, 'employee', '2024-10-29 09:12:03', '2024-10-29 12:12:03', 0),
(248, 4, 4, 'ششش', NULL, 'employee', '2024-10-29 09:12:03', '2024-10-29 12:12:03', 0),
(249, 4, 4, '88', NULL, 'employee', '2024-10-29 09:16:23', '2024-10-29 12:16:23', 0),
(250, 4, 4, '88', NULL, 'employee', '2024-10-29 09:16:23', '2024-10-29 12:16:23', 0),
(251, 4, 9, 'as', NULL, NULL, '2024-10-29 09:20:03', '2024-10-29 12:20:03', 0),
(252, 4, 4, 'as', NULL, 'employee', '2024-10-29 09:20:13', '2024-10-29 12:20:13', 0),
(253, 4, 4, 'as', NULL, 'employee', '2024-10-29 09:20:13', '2024-10-29 12:20:13', 0),
(254, 4, 9, 'AS', NULL, NULL, '2024-10-29 09:33:05', '2024-10-29 12:33:05', 0),
(255, 4, 9, '1', NULL, 'employee', '2024-10-29 09:38:32', '2024-10-29 12:38:32', 0),
(256, 4, 9, '1', NULL, 'employee', '2024-10-29 09:38:32', '2024-10-29 12:38:32', 0),
(257, 9, 9, 'as', NULL, NULL, '2024-10-29 09:38:48', '2024-10-29 12:38:48', 0),
(258, 9, 9, 'as', NULL, NULL, '2024-10-29 09:38:57', '2024-10-29 12:38:57', 0),
(259, 9, 9, 'asssssssssssss', NULL, NULL, '2024-10-29 09:39:13', '2024-10-29 12:39:13', 0),
(260, 4, 9, 'aa', NULL, NULL, '2024-10-29 09:42:54', '2024-10-29 12:42:54', 0),
(261, 4, 9, 'a', NULL, 'client', '2024-10-29 09:44:24', '2024-10-29 12:44:24', 0),
(262, 4, 9, 'a', NULL, 'client', '2024-10-29 09:47:22', '2024-10-29 12:47:22', 0),
(263, 4, 9, 'ش', NULL, 'client', '2024-10-29 09:52:05', '2024-10-29 12:52:05', 0),
(264, 4, 4, 'شس', NULL, 'employee', '2024-10-29 09:52:12', '2024-10-29 12:52:12', 0),
(265, 4, 4, 'شس', NULL, 'employee', '2024-10-29 09:52:12', '2024-10-29 12:52:12', 0),
(266, 4, 4, '1', NULL, 'employee', '2024-10-29 09:53:27', '2024-10-29 12:53:27', 0),
(267, 4, 4, '1', NULL, 'employee', '2024-10-29 09:53:27', '2024-10-29 12:53:27', 0),
(268, 4, 9, '2', NULL, 'client', '2024-10-29 09:53:49', '2024-10-29 12:53:49', 0),
(269, 4, 4, 'ش', NULL, 'employee', '2024-10-29 09:59:07', '2024-10-29 12:59:07', 0),
(270, 4, 4, 'ش', NULL, 'employee', '2024-10-29 09:59:07', '2024-10-29 12:59:07', 0),
(271, 4, 4, 'ش', NULL, 'employee', '2024-10-29 09:59:11', '2024-10-29 12:59:11', 0),
(272, 4, 4, 'ش', NULL, 'employee', '2024-10-29 09:59:11', '2024-10-29 12:59:11', 0),
(273, 4, 9, 'a', NULL, 'employee', '2024-10-29 10:04:20', '2024-10-29 13:04:20', 0),
(274, 4, 9, 'a', NULL, 'employee', '2024-10-29 10:04:20', '2024-10-29 13:04:20', 0),
(275, 4, 9, '12', NULL, 'employee', '2024-10-29 10:09:07', '2024-10-29 13:09:07', 0),
(276, 4, 9, '12', NULL, 'employee', '2024-10-29 10:09:07', '2024-10-29 13:09:07', 0),
(277, 4, 9, '5', NULL, 'employee', '2024-10-29 10:11:10', '2024-10-29 13:11:10', 0),
(278, 4, 9, '5', NULL, 'employee', '2024-10-29 10:11:10', '2024-10-29 13:11:10', 0),
(279, 4, 9, 'ب', NULL, 'employee', '2024-10-29 10:11:32', '2024-10-29 13:11:32', 0),
(280, 4, 9, '222', NULL, 'client', '2024-10-29 10:17:48', '2024-10-29 13:17:48', 0),
(281, 4, 9, 'ش', NULL, 'client', '2024-10-29 10:19:22', '2024-10-29 13:19:22', 0),
(282, 4, 9, '222', NULL, 'client', '2024-10-29 10:20:18', '2024-10-29 13:20:18', 0),
(283, 4, 4, '6', 'uploads/6720bb848b5a0_الرئيسية.png', 'employee', '2024-10-29 10:40:04', '2024-10-29 13:40:04', 1),
(284, 2, 9, 'as', NULL, 'employee', '2024-10-29 11:30:21', '2024-10-29 14:30:21', 1),
(285, 2, 9, 'as', NULL, 'employee', '2024-10-29 11:30:27', '2024-10-29 14:30:27', 1),
(286, 2, 9, 'aa', NULL, 'employee', '2024-10-29 11:31:17', '2024-10-29 14:31:17', 1),
(287, 2, 9, 'aaaaaaaaa', NULL, 'employee', '2024-10-29 11:31:22', '2024-10-29 14:31:22', 1),
(288, 4, 9, 'aa', NULL, 'client', '2024-10-29 11:32:02', '2024-10-29 14:32:02', 0),
(289, 2, 4, 'aaaaaaaaa', NULL, 'employee', '2024-10-29 11:32:14', '2024-10-29 14:32:14', 1),
(290, 2, 9, '11', NULL, 'employee', '2024-10-29 11:33:47', '2024-10-29 14:33:47', 1),
(291, 2, 9, '111', NULL, 'employee', '2024-10-29 11:34:19', '2024-10-29 14:34:19', 1),
(292, 4, 9, '66', NULL, 'client', '2024-10-29 11:34:53', '2024-10-29 14:34:53', 0),
(293, 9, 9, 'تم اختبار الدردشة', NULL, 'client', '2024-10-29 12:02:02', '2024-10-29 15:02:02', 0),
(294, 9, 9, 'ي', NULL, 'client', '2024-10-29 12:02:12', '2024-10-29 15:02:12', 0),
(295, 2, 9, 'يي', NULL, 'employee', '2024-10-29 12:02:49', '2024-10-29 15:02:49', 1),
(296, 4, 9, 'ييي', NULL, 'employee', '2024-10-29 12:03:00', '2024-10-29 15:03:00', 1),
(297, 9, 9, '12', NULL, 'client', '2024-10-29 12:06:44', '2024-10-29 15:06:44', 0),
(298, 4, 9, 'aaaaaaaaaaaaaa', NULL, 'client', '2024-10-29 12:07:44', '2024-10-29 15:07:44', 0),
(299, 4, 9, 'aaaaaaaaa', NULL, 'employee', '2024-10-29 16:11:56', '2024-10-29 19:11:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `transaction_type` enum('مبيع','شراء','يومية') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `task_id` int(11) NOT NULL,
  `completed_report` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `is_site_active` tinyint(1) DEFAULT 1,
  `track_employee_activity` tinyint(1) DEFAULT 0,
  `disable_employee_section` tinyint(1) DEFAULT 0,
  `disable_client_section` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `is_site_active`, `track_employee_activity`, `disable_employee_section`, `disable_client_section`, `created_at`, `updated_at`) VALUES
(1, 'site_name', NULL, 1, 0, 0, 0, '2024-11-03 09:00:46', '2024-11-05 09:15:02'),
(2, 'admin_email', NULL, 1, 0, 0, 0, '2024-11-03 09:00:46', '2024-11-05 09:15:02');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `client_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_report` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `employee_id`, `task_name`, `client_id`, `status`, `created_at`, `updated_at`, `completed_report`, `note`) VALUES
(38, 9, 'Report Submission', 2, 'review_completed', '2024-10-28 06:49:46', '2024-10-28 07:45:26', '../uploads/1730098186_1.pdf', '122'),
(39, 7, 'Report Submission', 2, 'review_completed', '2024-10-28 07:18:59', '2024-10-28 07:55:49', '../uploads/1730099939_1.pdf', 'تم الانجاز للمراجعة '),
(40, 7, 'Report Submission', 4, 'completed', '2024-10-28 07:20:46', '2024-10-28 07:44:40', '../uploads/1730100046_غلاف مشروع التخرج (1).pdf', 'للمراجعة '),
(41, 9, 'Report Submission', 2, 'completed', '2024-10-28 07:30:17', '2024-10-28 07:43:39', '../uploads/Assignment_BAIT_INT202_F23.pdf', 'svuNote'),
(42, 7, 'Report Submission', 2, 'completed', '2024-10-28 07:50:34', '2024-10-28 07:50:55', '../uploads/Rabee kiean Chate.pdf', 'اختبار نتائج الباك ايند'),
(43, 9, 'Report Submission', 2, 'completed', '2024-10-28 08:05:30', '2024-10-28 08:07:05', '../uploads/Climate-Change-Primer-2017.pdf', '3'),
(44, 9, 'Report Submission', 2, 'pending', '2024-10-28 08:46:10', '2024-10-28 08:51:08', '../uploads/BAIT_INT202_F23_Mahmoud_277568_C3_Akram_195698_c3_Weissam_191531.pdf', 'testing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `national_id` (`national_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_employee` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `national_id` (`national_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `employee_activity`
--
ALTER TABLE `employee_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `login_tracking`
--
ALTER TABLE `login_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employee_activity`
--
ALTER TABLE `employee_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_tracking`
--
ALTER TABLE `login_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employee_activity`
--
ALTER TABLE `employee_activity`
  ADD CONSTRAINT `employee_activity_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `login_tracking`
--
ALTER TABLE `login_tracking`
  ADD CONSTRAINT `login_tracking_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `login_tracking_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
