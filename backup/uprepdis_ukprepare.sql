-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 29, 2024 at 11:00 AM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uprepdis_ukprepare`
--

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_unique_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_doc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `contract_signing_date` date NOT NULL,
  `procurement_contract` bigint(20) DEFAULT NULL,
  `end_date` date NOT NULL,
  `bid_Fee` bigint(20) NOT NULL,
  `contract_agency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorized_personel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contractor_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aadhaar_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_resgistered_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forclose_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `contract_unique_id`, `contract_number`, `contract_doc`, `user_id`, `project_id`, `contract_signing_date`, `procurement_contract`, `end_date`, `bid_Fee`, `contract_agency`, `authorized_personel`, `contact`, `email`, `contractor_address`, `company_name`, `aadhaar_no`, `company_resgistered_no`, `registration_type`, `cancel_type`, `cancel_reason`, `forclose_reason`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '65d47192649da', '12345', '17084215222055MIS-.pdf', 24, 10, '2024-02-19', 700000, '2026-02-19', 800000, NULL, 'XYZ', '1234567890', 'abc@gmail.com', 'abcd', 'ABC', NULL, '1289900', 'LLP', NULL, NULL, NULL, 1, '2024-02-20 04:02:02', '2024-02-20 04:02:02', NULL),
(2, '65d867e26b618', '121', '17086811865696U-PREPARE-DB-SCHEMA.pdf', 24, 11, '2024-02-22', 2000000, '2024-02-22', 111, NULL, 'wqewqewqe', '1234512345', 'akansharwt50@gmail.com', 'wqewqec qwe wqewqe qwe qwewq e', 'wqewqewqe', NULL, '122333', 'wqewqewqe', NULL, NULL, NULL, 1, '2024-02-23 04:09:46', '2024-02-23 04:20:53', NULL),
(3, '65d869743ea04', 'fdgert', '1708681588825U-PREPARE-DB-SCHEMA_11zon.pdf', 24, 9, '2024-02-20', 213213, '2024-02-21', 21321321, NULL, 'wqewqewqe', '8218392090', 'nikhillsaini1000@gmail.com', 'ewqewqewqe', 'wqewqewqe', NULL, '12313', 'wqeqwewqe', NULL, NULL, NULL, 1, '2024-02-23 04:16:28', '2024-02-23 04:16:28', NULL),
(4, '65d885105aba3', '123', '17086886564599aaaaa.pdf', 24, 13, '2024-02-20', 1234, '2024-09-25', 1234, NULL, 'qwe', '1234567890', 'sumit@cafaladvisors.com', 'asdasd', 'weq', NULL, '123', 'qwe', NULL, NULL, NULL, 1, '2024-02-23 06:14:16', '2024-02-23 06:14:16', NULL),
(5, '65d88695e2bef', 'fjjkf', '17086890452120aaaaa.pdf', 24, 12, '2024-02-20', 1234, '2024-09-25', 63465, NULL, 'qwe', '1234567890', 'sumit@cafaladvisors.com', 'cgjfjg', 'weq', NULL, '123', 'qwe', NULL, NULL, NULL, 1, '2024-02-23 06:20:45', '2024-02-23 06:20:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contract_amend`
--

CREATE TABLE `contract_amend` (
  `id` bigint(20) NOT NULL,
  `contract_id` bigint(20) NOT NULL,
  `cost` bigint(20) NOT NULL,
  `amend_date` date DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contract_amend`
--

INSERT INTO `contract_amend` (`id`, `contract_id`, `cost`, `amend_date`, `document`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 700000, '2026-02-19', '', 1, '2024-02-20 04:02:02', '2024-02-20 04:02:02', NULL),
(2, 2, 111, '2024-02-22', '', 1, '2024-02-23 04:09:46', '2024-02-23 04:09:46', NULL),
(3, 3, 213213, '2024-02-21', '', 1, '2024-02-23 04:16:28', '2024-02-23 04:16:28', NULL),
(4, 2, 2000000, '2024-02-22', '17086818534072_image.pdf', 1, '2024-02-23 04:20:53', '2024-02-23 04:20:53', NULL),
(5, 4, 1234, '2024-09-25', '', 1, '2024-02-23 06:14:16', '2024-02-23 06:14:16', NULL),
(6, 5, 1234, '2024-09-25', '', 1, '2024-02-23 06:20:45', '2024-02-23 06:20:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contract_details`
--

CREATE TABLE `contract_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `form_of_security` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `example_of_security` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `security_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_security_date` date NOT NULL,
  `issuing_authority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contract_details`
--

INSERT INTO `contract_details` (`id`, `contract_id`, `name`, `form_of_security`, `example_of_security`, `start_date`, `security_number`, `end_security_date`, `issuing_authority`, `amount`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'Dummy security', 'Bond', NULL, '2024-02-01', '12341234', '2024-02-26', 'PMG', '12000', 1, '2024-02-27 05:19:50', '2024-02-27 05:19:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `define_project`
--

CREATE TABLE `define_project` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `hpc_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hpc_date` date DEFAULT NULL,
  `scope_of_work` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `objective` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_of_procurement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bid_number` int(11) DEFAULT NULL,
  `bid_fee` bigint(20) NOT NULL DEFAULT '0',
  `bid_validity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bid_completion_days` int(11) NOT NULL DEFAULT '0',
  `earnest_money_deposit` bigint(20) NOT NULL DEFAULT '0',
  `supervisor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor_deisgnation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `epd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `define_project`
--

INSERT INTO `define_project` (`id`, `user_id`, `project_id`, `hpc_number`, `hpc_date`, `scope_of_work`, `objective`, `method_of_procurement`, `bid_number`, `bid_fee`, `bid_validity`, `bid_completion_days`, `earnest_money_deposit`, `supervisor_name`, `supervisor_deisgnation`, `supervisor_contact`, `epd`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 24, 7, NULL, NULL, NULL, NULL, 'Request for Proposals', NULL, 50000, '90', 100, 4000, NULL, NULL, NULL, NULL, 1, '2024-02-19 06:44:52', '2024-02-19 06:44:52', NULL),
(2, 24, 4, NULL, NULL, NULL, NULL, 'Request for Bids', NULL, 500, '80', 90, 600, NULL, NULL, NULL, NULL, 1, '2024-02-19 06:57:02', '2024-02-19 06:57:02', NULL),
(3, 24, 6, NULL, NULL, NULL, NULL, 'Request for Proposals', NULL, 6000, '70', 80, 7000, NULL, NULL, NULL, NULL, 1, '2024-02-19 07:08:12', '2024-02-19 07:08:12', NULL),
(11, 24, 8, NULL, NULL, NULL, NULL, 'Request for Proposals', NULL, 120000, '20', 10, 120000, NULL, NULL, NULL, NULL, 1, '2024-02-20 03:10:42', '2024-02-20 03:10:42', NULL),
(12, 24, 10, NULL, NULL, '<p>abcdb</p>', '<p>xyz</p>', 'Request for Proposals', NULL, 70000, '80', 70, 50000, 'abc', 'deo', '2345671235', NULL, 1, '2024-02-20 03:58:21', '2024-02-20 05:26:45', NULL),
(13, 24, 9, NULL, NULL, NULL, NULL, 'Request for Proposals', NULL, 12000, '20', 20, 12000, NULL, NULL, NULL, NULL, 1, '2024-02-20 04:26:48', '2024-02-20 04:26:48', NULL),
(17, 24, 5, NULL, NULL, NULL, NULL, 'Request for Bids', NULL, 120, '20', 20, 20, NULL, NULL, NULL, NULL, 1, '2024-02-21 04:28:52', '2024-02-21 04:28:52', NULL),
(18, 24, 11, NULL, NULL, NULL, NULL, 'Request for Bids', NULL, 100, '20', 20, 100, NULL, NULL, NULL, NULL, 1, '2024-02-21 06:21:24', '2024-02-21 06:21:24', NULL),
(19, 24, 3, NULL, NULL, NULL, NULL, 'Request for Quotations', NULL, 5000, '180', 90, 5000, NULL, NULL, NULL, NULL, 1, '2024-02-22 05:54:54', '2024-02-22 05:54:54', NULL),
(20, 24, 12, NULL, NULL, NULL, NULL, 'Request for Proposals', NULL, 10000, '7', 10, 15000, NULL, NULL, NULL, NULL, 1, '2024-02-23 04:49:28', '2024-02-23 04:49:28', NULL),
(21, 24, 13, NULL, NULL, '<div>bbbbbbbbbbbbbbbbbb</div>', '<div>cccccccccccccccccccc<br><br></div>', 'Request for Bids', NULL, 5000, '120', 365, 527000, 'Vineet singh', 'jr. pwd', '1234512345', NULL, 1, '2024-02-23 05:49:31', '2024-02-25 23:05:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Almora', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(2, 'Bageshwar', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(3, 'Chamoli', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(4, 'Champawat', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(5, 'Dehradun', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(6, 'Haridwar', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(7, 'Nainital', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(8, 'Pauri Garhwal	', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(9, 'Pithoragarh', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(10, 'Rudraprayag', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(11, 'Tehri Garhwal', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(12, 'Udham Singh Nagar', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(13, 'Uttarkashi', 1, '2023-11-07 08:18:13', '2023-11-07 08:18:13', NULL),
(14, 'All', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medias`
--

CREATE TABLE `medias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mediable_id` bigint(20) UNSIGNED NOT NULL,
  `mediable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medias`
--

INSERT INTO `medias` (`id`, `mediable_id`, `mediable_type`, `name`, `year`, `status`, `created_at`, `updated_at`) VALUES
(6, 11, 'App\\Models\\Projects', 'project_approval_document_65d5943f6338eU-PREPARE DB SCHEMA.pdf', 2024, 1, '2024-02-21 00:42:15', '2024-02-21 00:42:15'),
(7, 11, 'App\\Models\\Projects', 'project_approval_document_65d594c0c0eb3U-PREPARE DB SCHEMA.pdf', 2024, 1, '2024-02-21 00:44:24', '2024-02-21 00:44:24'),
(8, 11, 'App\\Models\\Projects', 'project_approval_document_65d595dc68d73.pdf', 2024, 1, '2024-02-21 00:49:08', '2024-02-21 00:49:08'),
(9, 11, 'App\\Models\\Projects', 'project_approval_document_65d5960922373.pdf', 2024, 1, '2024-02-21 00:49:53', '2024-02-21 00:49:53'),
(10, 11, 'App\\Models\\Projects', 'project_approval_document_65d596ff4740a.pdf', 2024, 1, '2024-02-21 00:53:59', '2024-02-21 00:53:59'),
(11, 11, 'App\\Models\\Projects', 'project_approval_document_65d5975a7edf1.pdf', 2024, 1, '2024-02-21 00:55:30', '2024-02-21 00:55:30'),
(12, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d83e10efccf.pdf', 2024, 1, '2024-02-23 01:11:20', '2024-02-23 01:11:20'),
(13, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d83e27bc491.pdf', 2024, 1, '2024-02-23 01:11:43', '2024-02-23 01:11:43'),
(14, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d83e45a2538.pdf', 2024, 1, '2024-02-23 01:12:13', '2024-02-23 01:12:13'),
(15, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d83e5ec4aae.pdf', 2024, 1, '2024-02-23 01:12:38', '2024-02-23 01:12:38'),
(16, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d841a21d0ef.pdf', 2024, 1, '2024-02-23 01:26:34', '2024-02-23 01:26:34'),
(17, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d84610ace66.pdf', 2024, 1, '2024-02-23 01:45:28', '2024-02-23 01:45:28'),
(18, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d846d51100e.pdf', 2024, 1, '2024-02-23 01:48:45', '2024-02-23 01:48:45'),
(19, 18, 'App\\Models\\DefineProject', 'project_bid_document_65d8499e1119d.pdf', 2024, 1, '2024-02-23 02:00:38', '2024-02-23 02:00:38'),
(20, 12, 'App\\Models\\DefineProject', 'project_bid_document_65d84a45dceed.pdf', 2024, 1, '2024-02-23 02:03:25', '2024-02-23 02:03:25'),
(21, 12, 'App\\Models\\Projects', 'project_approval_document_65d86c76b4da1.pdf', 2024, 1, '2024-02-23 04:29:18', '2024-02-23 04:29:18'),
(22, 20, 'App\\Models\\DefineProject', 'project_bid_document_65d876d4eb28a.pdf', 2024, 1, '2024-02-23 05:13:32', '2024-02-23 05:13:32'),
(23, 13, 'App\\Models\\Projects', 'project_approval_document_65d87e8c9e3cd.pdf', 2024, 1, '2024-02-23 05:46:28', '2024-02-23 05:46:28'),
(24, 21, 'App\\Models\\DefineProject', 'project_bid_document_65d882b780dca.pdf', 2024, 1, '2024-02-23 06:04:15', '2024-02-23 06:04:15'),
(25, 16, 'App\\Models\\MilestoneValues', '17089356083525graphic-mobileslider.jpg', 2024, 1, '2024-02-26 02:50:08', '2024-02-26 02:50:08'),
(26, 16, 'App\\Models\\MilestoneValues', '17089404329888logo-adx1-e1691681878913.png', 2024, 1, '2024-02-26 04:10:32', '2024-02-26 04:10:32'),
(27, 14, 'App\\Models\\Projects', 'project_approval_document_65dd84765afb6.pdf', 2024, 1, '2024-02-27 01:13:02', '2024-02-27 01:13:02'),
(28, 15, 'App\\Models\\Projects', 'project_approval_document_65dd99e175abf.pdf', 2024, 1, '2024-02-27 02:44:25', '2024-02-27 02:44:25'),
(29, 1, 'App\\Http\\Controllers\\Security', '17090309909238U-PREPARE-DB-SCHEMA_11zon.pdf', 2024, 1, '2024-02-27 05:19:50', '2024-02-27 05:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `milestone_documents`
--

CREATE TABLE `milestone_documents` (
  `id` bigint(20) NOT NULL,
  `milestone_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `milestone_documents`
--

INSERT INTO `milestone_documents` (`id`, `milestone_id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(17, 1, 'doc two', 1, '2024-02-24 00:58:47', '2024-02-25 23:18:49', NULL),
(18, 1, 'doc three', 1, '2024-02-25 23:19:04', '2024-02-25 23:19:04', NULL),
(19, 1, 'doc 4', 1, '2024-02-27 01:01:44', '2024-02-27 01:01:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `milestone_values`
--

CREATE TABLE `milestone_values` (
  `id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `milestone_id` bigint(20) NOT NULL,
  `percentage` tinyint(4) NOT NULL DEFAULT '0',
  `amount` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `milestone_values`
--

INSERT INTO `milestone_values` (`id`, `type`, `milestone_id`, `percentage`, `amount`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(16, 1, 2, 100, NULL, 1, '2024-02-26 02:10:05', '2024-02-26 02:10:05', NULL),
(18, 1, 1, 50, NULL, 1, '2024-02-26 02:38:41', '2024-02-26 02:38:41', NULL),
(20, 1, 1, 20, NULL, 1, '2024-02-27 01:05:08', '2024-02-27 01:05:08', NULL),
(21, 1, 1, 20, NULL, 1, '2024-02-27 01:09:30', '2024-02-27 01:09:30', NULL),
(22, 2, 1, 90, '1080', 1, '2024-02-27 07:29:19', '2024-02-27 07:29:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `milestone_value_docuements`
--

CREATE TABLE `milestone_value_docuements` (
  `id` bigint(20) NOT NULL,
  `milestone_value_id` bigint(20) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `milestone_value_docuements`
--

INSERT INTO `milestone_value_docuements` (`id`, `milestone_value_id`, `document_name`, `file`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(18, 17, 'payment_slip', '17089332395404_payment_slip_.pdf', 1, '2024-02-26 02:10:39', '2024-02-26 02:10:39', NULL),
(19, 18, 'doc two', '17089349218483_milestone_doc_image.pdf', 1, '2024-02-26 02:38:41', '2024-02-26 02:38:41', NULL),
(20, 18, 'doc three', '17089349212330_milestone_doc_image.pdf', 1, '2024-02-26 02:38:41', '2024-02-26 02:38:41', NULL),
(21, 19, 'payment_slip', '17089349832165_payment_slip_.pdf', 1, '2024-02-26 02:39:44', '2024-02-26 02:39:44', NULL),
(22, 20, 'doc two', '17090157081634_milestone_doc_image.pdf', 1, '2024-02-27 01:05:08', '2024-02-27 01:05:08', NULL),
(23, 20, 'doc three', '17090157089541_milestone_doc_image.pdf', 1, '2024-02-27 01:05:08', '2024-02-27 01:05:08', NULL),
(24, 20, 'doc 4', '17090157087213_milestone_doc_image.pdf', 1, '2024-02-27 01:05:08', '2024-02-27 01:05:08', NULL),
(25, 21, 'doc two', '17090159706138_milestone_doc_image.pdf', 1, '2024-02-27 01:09:30', '2024-02-27 01:09:30', NULL),
(26, 21, 'doc three', '17090159701300_milestone_doc_image.pdf', 1, '2024-02-27 01:09:30', '2024-02-27 01:09:30', NULL),
(27, 21, 'doc 4', '17090159707796_milestone_doc_image.pdf', 1, '2024-02-27 01:09:30', '2024-02-27 01:09:30', NULL),
(28, 22, 'payment_slip', '1709038759558_payment_slip_.pdf', 1, '2024-02-27 07:29:19', '2024-02-27 07:29:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_params`
--

CREATE TABLE `procurement_params` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `category_type` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` float NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procurement_params`
--

INSERT INTO `procurement_params` (`id`, `category_id`, `category_type`, `name`, `weight`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Request for Bids', 'Preparation of bid document', 30, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(2, 1, 'Request for Bids', 'Approval of bid document ', 5, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(3, 1, 'Request for Bids', 'Publication/Issuance of notice', 5, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(4, 1, 'Request for Bids', 'Pre-bid meeting', 10, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(5, 1, 'Request for Bids', 'Technical opening', 20, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(6, 1, 'Request for Bids', 'Financial opening ', 10, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(7, 1, 'Request for Bids', 'Notification of award', 10, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(8, 1, 'Request for Bids', 'Contract signing', 10, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(9, 3, 'Request for Quotations', 'Preparation of Quotation document', 30, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(10, 3, 'Request for Quotations', 'Approval of Quotation document ', 20, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(11, 3, 'Request for Quotations', 'Publication/Issuance of notice', 20, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(13, 3, 'Request for Quotations', 'Quotation opening ', 30, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(14, 3, 'Request for Quotations', 'Notification of award', 10, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(15, 2, 'Consultancy', 'Preparation of bid document', 30, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(16, 2, 'Consultancy', 'Approval of bid document ', 5, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(17, 2, 'Consultancy', 'Publication of EOI', 5, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(18, 2, 'Consultancy', 'Pre-bid meeting', 5, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL),
(19, 2, 'Consultancy', 'Evaluation of EOI', 10, 1, '2023-11-21 07:54:30', '2023-11-21 07:54:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `procuremnt_details_project`
--

CREATE TABLE `procuremnt_details_project` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `eot_number` bigint(20) NOT NULL,
  `revised_contract_end_date` date NOT NULL,
  `revised_contract_value` bigint(20) NOT NULL,
  `contract_value_variation` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procuremnt_milestones`
--

CREATE TABLE `procuremnt_milestones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '[''1'' => ''normal project milestone'',''2'' => envireonment milestone'',''3'' => ''social milestones'']',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` bigint(20) NOT NULL DEFAULT '0',
  `percent_of_work` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amended_start_date` date DEFAULT NULL,
  `amended_end_date` date DEFAULT NULL,
  `financial_progress` int(11) DEFAULT '0',
  `physical_progress` int(11) DEFAULT '0',
  `accumulative` bigint(20) DEFAULT NULL,
  `documents` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procuremnt_milestones`
--

INSERT INTO `procuremnt_milestones` (`id`, `type`, `user_id`, `project_id`, `name`, `budget`, `percent_of_work`, `start_date`, `end_date`, `amended_start_date`, `amended_end_date`, `financial_progress`, `physical_progress`, `accumulative`, `documents`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 18, 13, 'one', 1200, 20, '2024-02-01', '2024-02-29', NULL, NULL, 0, 0, NULL, NULL, 1, '2024-02-24 00:58:47', '2024-02-27 00:54:32', NULL),
(2, 1, 18, 13, 'Mile Stone two', 100000, 80, '2024-03-01', '2024-03-31', NULL, NULL, 0, 0, NULL, NULL, 1, '2024-02-26 00:47:13', '2024-02-26 00:47:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `procuremnt_value_by_category`
--

CREATE TABLE `procuremnt_value_by_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `procurement_param_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` double(8,2) NOT NULL,
  `days` bigint(20) DEFAULT '0',
  `planned_date` date DEFAULT NULL,
  `actual_date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procuremnt_value_by_category`
--

INSERT INTO `procuremnt_value_by_category` (`id`, `project_id`, `procurement_param_id`, `name`, `weight`, `days`, `planned_date`, `actual_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(50, 10, 0, 'identification of purchasing need', 30.00, 10, '2024-03-21', '2024-02-28', 1, NULL, '2024-02-23 02:01:53', NULL),
(51, 9, 0, 'step one', 50.00, 2, '2024-02-01', NULL, 1, NULL, NULL, NULL),
(52, 9, 0, 'step two', 50.00, 2, '2024-02-03', NULL, 1, NULL, NULL, NULL),
(53, 5, 0, 'Step One', 50.00, 1, '2024-02-23', NULL, 1, NULL, NULL, NULL),
(54, 5, 0, 'Step Two', 50.00, 1, '2024-02-29', NULL, 1, NULL, NULL, NULL),
(55, 10, 0, 'step tow', 10.00, 20, '2024-02-15', NULL, 1, '2024-02-22 01:47:15', '2024-02-22 02:35:10', '2024-02-22 02:35:10'),
(56, 10, 0, 'step three', 60.00, 30, '2024-02-01', NULL, 1, '2024-02-22 01:49:00', '2024-02-22 02:34:49', '2024-02-22 02:34:49'),
(57, 11, 0, 'one', 20.00, 2, '2024-02-23', '2024-02-25', 1, '2024-02-22 02:35:49', '2024-02-23 00:10:24', NULL),
(58, 11, 0, 'two', 40.00, 20, '2024-02-23', '2024-02-24', 1, '2024-02-22 02:36:12', '2024-02-23 00:06:14', NULL),
(59, 3, 0, 'Bid Preparation', 10.00, 10, '2024-03-04', NULL, 1, NULL, NULL, NULL),
(60, 3, 0, 'Bid Publish & Evaluation', 90.00, 90, '2024-05-28', NULL, 1, NULL, NULL, NULL),
(61, 12, 0, 'Step 1', 50.00, 1, '2024-02-24', '2024-02-24', 1, NULL, '2024-02-23 05:11:26', NULL),
(62, 12, 0, 'step 2', 50.00, 2, '2024-02-25', '2024-02-28', 1, NULL, '2024-02-23 05:11:37', NULL),
(63, 13, 0, 'Bid Preparation', 20.00, 30, '2024-03-22', '2024-03-24', 1, NULL, '2024-02-23 06:01:39', NULL),
(64, 13, 0, 'Publishing', 10.00, 10, '2024-03-15', '2024-03-16', 1, NULL, '2024-02-23 06:01:46', NULL),
(65, 13, 0, 'Evaluation and Opening', 70.00, 40, '2024-04-30', '2024-05-01', 1, NULL, '2024-02-23 06:01:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `assign_to` bigint(20) UNSIGNED NOT NULL,
  `procure_level_3` bigint(20) DEFAULT NULL,
  `assign_level_2` bigint(20) NOT NULL DEFAULT '0',
  `environment_level_3` bigint(20) DEFAULT NULL,
  `social_level_3` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval_date` date NOT NULL,
  `estimate_budget` bigint(20) NOT NULL,
  `scope_of_work` text COLLATE utf8mb4_unicode_ci,
  `objective` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `stage` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `unique_id`, `user_id`, `category_id`, `district_id`, `assign_to`, `procure_level_3`, `assign_level_2`, `environment_level_3`, `social_level_3`, `name`, `agency`, `approved_by`, `approval_number`, `number`, `approval_date`, `estimate_budget`, `scope_of_work`, `objective`, `start_date`, `end_date`, `status`, `stage`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '65d2f1a07cfdc', 1, 1, 3, 8, NULL, 0, NULL, NULL, 'Bridge001', NULL, 'PMU', '101', 'B001', '2024-01-01', 8400000, NULL, NULL, NULL, NULL, 0, 0, '2024-02-19 00:43:52', '2024-02-19 00:44:25', NULL),
(2, '65d30657bf39f', 1, 2, 5, 8, NULL, 0, NULL, NULL, 'AAA', NULL, 'ASD', '1234', 'CS001', '2024-01-18', 1200000, NULL, NULL, NULL, NULL, 0, 0, '2024-02-19 02:12:15', '2024-02-26 02:44:45', NULL),
(3, '65d315f76edd6', 1, 3, 8, 13, 27, 0, NULL, NULL, 'KLJ', NULL, 'QWE', 'KLJ10101', '10101', '2024-02-19', 8000000, NULL, NULL, NULL, NULL, 0, 1, '2024-02-19 03:18:55', '2024-02-22 05:56:51', NULL),
(4, '65d31c3928bd5', 1, 1, 10, 6, 26, 0, NULL, NULL, 'Buildings', NULL, 'PMU001', '121212111', 'P001', '2024-02-01', 2121000, NULL, NULL, NULL, NULL, 0, 0, '2024-02-19 03:45:37', '2024-02-19 06:57:02', NULL),
(5, '65d31f711f3b5', 1, 3, 3, 14, 26, 0, NULL, NULL, 'Rehabilitation', NULL, 'Rehab', 'PR0121', 'R001', '2024-02-12', 76800000, NULL, NULL, NULL, NULL, 0, 1, '2024-02-19 03:59:21', '2024-02-21 04:40:40', NULL),
(6, '65d31fee5d2d2', 1, 3, 12, 3, 26, 0, NULL, NULL, 'GIS001', NULL, 'AXZS', '12562365', 'G01011', '2024-02-19', 236000, NULL, NULL, NULL, NULL, 0, 0, '2024-02-19 04:01:26', '2024-02-19 07:08:12', NULL),
(7, '65d32be0c2a7b', 1, 3, 3, 8, 26, 0, NULL, NULL, 'anb', NULL, 'gbhbt', '3433', 'vn1233', '2024-02-19', 120000, NULL, NULL, NULL, NULL, 0, 0, '2024-02-19 04:52:24', '2024-02-19 06:44:52', NULL),
(8, '65d434b620bad', 1, 1, 14, 3, 26, 0, NULL, NULL, 'Dummy Project', NULL, 'HPC', '123123', '1237809', '2024-02-20', 10000000, NULL, NULL, NULL, NULL, 0, 1, '2024-02-19 23:42:22', '2024-02-20 02:40:56', NULL),
(9, '65d45946981bc', 1, 1, 14, 14, 26, 0, NULL, NULL, 'Bridge', NULL, 'HPC', '12345', '87654', '2024-02-13', 1000000, NULL, NULL, NULL, NULL, 0, 2, '2024-02-20 02:18:22', '2024-02-23 04:16:28', NULL),
(10, '65d46f713be27', 1, 1, 9, 9, 28, 29, NULL, NULL, 'Building', NULL, 'HPC', '123456', '123456', '2024-02-16', 200000, NULL, NULL, NULL, NULL, 0, 1, '2024-02-20 03:52:57', '2024-02-23 02:03:25', NULL),
(11, '65d58dc8c0d6f', 1, 4, 1, 3, 28, 0, NULL, NULL, 'dummy project eeqr', NULL, '121212', '1234567', '2321323', '2024-02-20', 2321323, NULL, NULL, NULL, NULL, 0, 2, '2024-02-21 00:14:40', '2024-02-23 04:09:46', NULL),
(12, '65d86c76b42ff', 1, 1, 9, 3, 26, 0, NULL, NULL, 'Building & Bridge', NULL, 'HPC', '127336', 'BB101', '2024-02-23', 1200400, NULL, NULL, NULL, NULL, 0, 2, '2024-02-23 04:29:18', '2024-02-23 06:20:45', NULL),
(13, '65d87e8c9cbac', 1, 1, 4, 8, 27, 29, NULL, NULL, 'Construction of 65m span single lane steel truss pedestrian bridge at Gandakhali Village  to ucholigoth village in district champawat', NULL, 'HPC', '467/UDRP-AF/2022', '11/BR/RFB/UGRIDP/2023', '2022-12-26', 32200000, NULL, NULL, NULL, NULL, 0, 3, '2024-02-23 05:46:28', '2024-02-27 02:35:05', NULL),
(14, '65dd84765a60a', 1, 4, 5, 9, NULL, 0, NULL, NULL, 'New School construction in dehradun', NULL, 'PD', '1202022', '12002', '2024-02-27', 200000, NULL, NULL, NULL, NULL, 0, 0, '2024-02-27 01:13:02', '2024-02-27 01:13:02', NULL),
(15, '65dd99e174e81', 1, 1, 8, 8, NULL, 0, NULL, NULL, 'Construction of Bridge of 50m span', NULL, 'PD', 'PAN/101', 'BRI/0101/4534', '2024-02-27', 35050000, NULL, NULL, NULL, NULL, 0, 0, '2024-02-27 02:44:25', '2024-02-27 02:44:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projects_category`
--

CREATE TABLE `projects_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `methods_of_procurement` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects_category`
--

INSERT INTO `projects_category` (`id`, `name`, `methods_of_procurement`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Works', '[\"Request for Proposals\",\"Request for Bids\",\"Request for Quotations\",\"Direct Selection\"]', '1', NULL, NULL, NULL),
(2, 'Consultancy Services', '[\"Quality and Cost-Budget Selection\",\"Fixed Budget Selection\",\"Least Cost Selection\",\"Quality Based Selection\",\"Consultant Qualification Selection\",\"Direct Selection\",\"Individual Consultant Selection\"]', '1', NULL, NULL, NULL),
(3, 'Goods', '[\"Request for Proposals\",\"Request for Bids\",\"Request for Quotations\"]', '1', NULL, NULL, NULL),
(4, 'Others', '[\"Request for Proposals\",\"Request for Bids\",\"Request for Quotations\",\"Direct Selection\"]', '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_finance_expense`
--

CREATE TABLE `project_finance_expense` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `agency_id` bigint(20) UNSIGNED NOT NULL,
  `quarter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `office_equipment_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `electricty_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `transport_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `salaries_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `rent_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `miscelleneous_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `total_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_finance_expense`
--

INSERT INTO `project_finance_expense` (`id`, `user_id`, `agency_id`, `quarter`, `year`, `office_equipment_exp`, `electricty_exp`, `transport_exp`, `salaries_exp`, `rent_exp`, `miscelleneous_exp`, `total_exp`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 18, 8, '1', '2024', '10000', '10000', '10000', '10000', '10000', '12000', '62000', 1, '2024-02-25 23:21:41', '2024-02-27 07:00:57', NULL),
(2, 18, 8, '2', '2024', '5500', '3500', '2500', '40000', '4800', '25000', '81300', 1, '2024-02-27 01:48:30', '2024-02-27 07:00:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_visits`
--

CREATE TABLE `project_visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `self_image` date NOT NULL,
  `visit_date` date NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `affilaited` int(11) NOT NULL DEFAULT '0',
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `affilaited`, `department`, `level`, `level_name`, `created_at`, `updated_at`) VALUES
(1, 'SUPER-ADMIN', 0, 'ADMIN', NULL, NULL, NULL, NULL),
(2, 'PMU-LEVEL-ONE', 0, 'PMU', 'ONE', 'Lvl-1', NULL, NULL),
(3, 'PMU-LEVEL-TWO', 2, 'PMU', 'TWO', 'Lvl-2', NULL, NULL),
(4, 'PMU-LEVEL-THREE', 3, 'PMU', 'THREE', 'Lvl-3', NULL, NULL),
(6, 'PROCUREMENT-LEVEL-TWO', 2, 'PROCUREMENT', 'TWO', 'Lvl-2', NULL, NULL),
(7, 'PMU-PROCUREMENT-LEVEL-THREE', 6, 'PMU-PROCUREMENT', 'THREE', 'Lvl-3', NULL, NULL),
(8, 'PIU-LEVEL-TWO-PWD', 2, 'PWD', 'TWO', 'Lvl-2', NULL, NULL),
(9, 'PIU-LEVEL-TWO-RWD', 2, 'RWD', 'TWO', 'Lvl-2', NULL, NULL),
(13, 'PIU-LEVEL-TWO-FOREST', 2, 'FOREST', 'TWO', 'Lvl-2', NULL, NULL),
(14, 'PIU-LEVEL-TWO-USDMA', 2, 'USDMA', 'TWO', 'Lvl-2', NULL, NULL),
(15, 'PIU-LEVEL-THREE-PWD', 8, 'PWD', 'THREE', 'Lvl-3', NULL, NULL),
(16, 'PIU-LEVEL-THREE-RWD', 9, 'RWD', 'THREE', 'Lvl-3', NULL, NULL),
(17, 'PIU-LEVEL-THREE-FOREST', 13, 'FOREST', 'THREE', 'Lvl-3', NULL, NULL),
(18, 'PIU-LEVEL-THREE-USDMA', 14, 'USDMA', 'THREE', 'Lvl-3', NULL, NULL),
(19, 'ENVIRONMENT-LEVEL-TWO', 2, 'ENVIRONMENT', 'TWO', 'Lvl-2', NULL, NULL),
(20, 'PWD-PROCUREMENT-LEVEL-THREE', 6, 'PWD-PROCUREMENT', 'THREE', 'Lvl-3', NULL, NULL),
(21, 'RWD-PROCUREMENT-LEVEL-THREE', 6, 'RWD-PROCUREMENT', 'THREE', 'Lvl-3', NULL, NULL),
(22, 'FOREST-PROCUREMENT-LEVEL-THREE', 6, 'FOREST-PROCUREMENT', 'THREE', 'Lvl-3', NULL, NULL),
(23, 'USDMA-PROCUREMENT-LEVEL-THREE', 6, 'USDMA-PROCUREMENT', 'THREE', 'Lvl-3', NULL, NULL),
(24, 'ENVIRONMENT-LEVEL-THREE', 19, 'ENVIRONMENT-THREE', 'THREE', 'Lvl-3', NULL, NULL),
(25, 'SOCIAL-LEVEL-TWO', 2, 'SOCIAL', 'TWO', 'Lvl-2', NULL, NULL),
(26, 'SOCIAL-LEVEL-THREE', 25, 'SOCIAL-THREE', 'THREE', 'Lvl-3', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` bigint(20) NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `role_id`, `profile_image`, `designation`, `name`, `phone_no`, `username`, `email`, `email_verified_at`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 0, 2, NULL, 'PMU', 'Arjun Patwal', 0, 'ADMIN', 'nikhillsaini100@gmail.com', NULL, '$2y$12$S9vGIEN3EKFuiW3lyUkY4eyz//7qLV5RWjM/OtN1X/IBHzZ4zA1vO', '1', '8zro6ncha0uEIyvOeroepfYMNN0TGp7d2f3dpfsLUXpUri7ggvQxVeenJK4f', '2023-11-06 00:21:39', '2023-11-06 00:21:39'),
(18, 1, 8, NULL, 'Manager Civil', 'Amit', 9760683149, 'Amit', 'sumit@cafaladvisors.com', NULL, '$2y$12$6s2RN/jlATMjL8yh8QVQp.vl69lF94Q652hmyYF/Q7Ad7DzMQvvr.', '1', NULL, '2024-02-17 04:10:02', '2024-02-27 00:53:40'),
(19, 1, 9, NULL, 'Manager Civil', 'XYZ', 8766362803, 'ABC', 'sumit@gmail.com', NULL, '$2y$12$4msCaZd6Dwr7koIYqZ1ZWu.RpUwV6fe25JymSI0JTltG/gn2XAvXG', '1', NULL, '2024-02-19 00:48:52', '2024-02-27 02:22:08'),
(20, 19, 16, NULL, 'Civil', 'XX', 1111111111, 'XY-Field', 'xxy@gmail.com', NULL, '$2y$12$r6.HYxfYNmeWl9VjlPZREOmg1BQGbV7dyS20CrhuVvaHwB95wRFwe', '1', NULL, '2024-02-19 01:49:50', '2024-02-19 01:51:50'),
(21, 1, 3, NULL, 'sdcsdi', 'PPPMMM', 1212121212, 'PMU001', 'pp@rt.com', NULL, '$2y$12$PK5rSU6MZJBgDuA3EABJ3.6pO8fwYEkudadrP2lAlzglQpAgG8vYq', '1', NULL, '2024-02-19 04:04:19', '2024-02-19 04:04:19'),
(22, 1, 14, NULL, 'IT Expert', 'adaa', 8787878787, 'ADWE', 'ada@g.com', NULL, '$2y$12$gnlG2GN6rOJFeU6cmeUILOOUFTDwtRJo6Mgiux43irYopt2ny8a8m', '1', NULL, '2024-02-19 04:07:31', '2024-02-20 02:56:43'),
(23, 1, 13, NULL, 'FRM', 'FRMfrm', 1010101010, 'fsgrbrb', 'f@g.com', NULL, '$2y$12$aNbntyK9IpzFDTBKbmycduabtPR6PG.0m5FkxItgsmyQx8oD7UEmS', '1', NULL, '2024-02-19 04:09:08', '2024-02-19 04:09:08'),
(24, 1, 6, NULL, 'asdf', 'Shailja', 1234567890, 'Shailja', 'p@g.com', NULL, '$2y$12$tFbGGEOSn1UX9LvgkNLwce5yB1J0Ya6QrWf6MfxyDF0ELKrKuoJGu', '1', NULL, '2024-02-19 04:10:52', '2024-02-22 05:53:01'),
(25, 1, 19, NULL, 'Env Expert', 'Sourabh', 1234567891, 'Sourabh', 'e@g.com', NULL, '$2y$12$PGXGT7b8eqloRNPyqd0Rv.Qa46NrWIRsOqxQmOM4PWA4JKcwJTZpu', '1', NULL, '2024-02-19 04:18:43', '2024-02-20 03:49:46'),
(26, 24, 20, NULL, 'WQE', 'Rohit', 9999999999, 'AWQ', 'Rohit@gmail.com', NULL, '$2y$12$JCzqbWeihS6ZnocrcHcgAu4QWjZIWFw.bYyCJZuNW6NfDmLj2A92G', '1', NULL, '2024-02-19 06:44:17', '2024-02-20 04:30:46'),
(27, 24, 20, NULL, 'Civil', 'Vijay', 2222222222, 'Vijay01', 'v@gmail.com', NULL, '$2y$12$EqP24p7rN4XwjlvIbl9.Y.fcRwsZUOu4pQnLlGp7dKEBno4wq9gC6', '1', NULL, '2024-02-19 07:01:33', '2024-02-22 05:57:17'),
(28, 24, 20, NULL, 'PWD officer', 'Vinit', 8826512967, 'Vinit', 'vinit@gmail.com', NULL, '$2y$12$kN9SPVlMBoBbUntbEWvCiuIMXAX9P0LOq6imAqczJB46fvNEfHurm', '1', NULL, '2024-02-20 03:57:28', '2024-02-26 23:54:05'),
(29, 18, 15, NULL, 'field officer', 'sumit', 3456781234, 'sumit', 'sumit12@gmail.com', NULL, '$2y$12$f6nyz2JsjD8kmKJbr9q4nOQCaQbrl21bQAVbex/BUFY2fxflcnWm.', '1', NULL, '2024-02-20 04:13:03', '2024-02-27 01:11:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_projects`
--

CREATE TABLE `user_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contracts_user_id_index` (`user_id`),
  ADD KEY `contracts_project_id_index` (`project_id`);

--
-- Indexes for table `contract_amend`
--
ALTER TABLE `contract_amend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract_details`
--
ALTER TABLE `contract_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_details_contract_id_index` (`contract_id`);

--
-- Indexes for table `define_project`
--
ALTER TABLE `define_project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`),
  ADD KEY `define_project_user_id_index` (`user_id`),
  ADD KEY `define_project_project_id_index` (`project_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `medias`
--
ALTER TABLE `medias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medias_mediable_id_mediable_type_index` (`mediable_id`,`mediable_type`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestone_documents`
--
ALTER TABLE `milestone_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestone_values`
--
ALTER TABLE `milestone_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestone_value_docuements`
--
ALTER TABLE `milestone_value_docuements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `procurement_params`
--
ALTER TABLE `procurement_params`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procuremnt_details_project`
--
ALTER TABLE `procuremnt_details_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procuremnt_details_project_user_id_index` (`user_id`),
  ADD KEY `procuremnt_details_project_project_id_index` (`project_id`);

--
-- Indexes for table `procuremnt_milestones`
--
ALTER TABLE `procuremnt_milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procuremnt_milestones_user_id_index` (`user_id`),
  ADD KEY `procuremnt_milestones_project_id_index` (`project_id`);

--
-- Indexes for table `procuremnt_value_by_category`
--
ALTER TABLE `procuremnt_value_by_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procuremnt_value_by_category_project_id_index` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`number`),
  ADD KEY `projects_user_id_index` (`user_id`),
  ADD KEY `projects_category_id_index` (`category_id`),
  ADD KEY `projects_unique_id_index` (`unique_id`);

--
-- Indexes for table `projects_category`
--
ALTER TABLE `projects_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_category_name_unique` (`name`);

--
-- Indexes for table `project_finance_expense`
--
ALTER TABLE `project_finance_expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_finance_expense_user_id_index` (`user_id`),
  ADD KEY `project_finance_expense_project_id_index` (`agency_id`);

--
-- Indexes for table `project_visits`
--
ALTER TABLE `project_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_visits_user_id_project_id_index` (`user_id`,`project_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `user_projects`
--
ALTER TABLE `user_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_projects_user_id_project_id_index` (`user_id`,`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contract_amend`
--
ALTER TABLE `contract_amend`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contract_details`
--
ALTER TABLE `contract_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `define_project`
--
ALTER TABLE `define_project`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medias`
--
ALTER TABLE `medias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `milestone_documents`
--
ALTER TABLE `milestone_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `milestone_values`
--
ALTER TABLE `milestone_values`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `milestone_value_docuements`
--
ALTER TABLE `milestone_value_docuements`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_params`
--
ALTER TABLE `procurement_params`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `procuremnt_details_project`
--
ALTER TABLE `procuremnt_details_project`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procuremnt_milestones`
--
ALTER TABLE `procuremnt_milestones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `procuremnt_value_by_category`
--
ALTER TABLE `procuremnt_value_by_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `projects_category`
--
ALTER TABLE `projects_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_finance_expense`
--
ALTER TABLE `project_finance_expense`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_visits`
--
ALTER TABLE `project_visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user_projects`
--
ALTER TABLE `user_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
