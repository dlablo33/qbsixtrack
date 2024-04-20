-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 03, 2024 at 12:20 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quickbooks_panelone`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `AccountID` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountType` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BankAccountNumber` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TaxType` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EnablePaymentsToAccount` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `AccountStatus` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `BankAccountType` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ShowInExpenseClaims` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `AddToWatchlist` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountClass` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ReportingCode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ReportingCodeName` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HasAttachments` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` bigint UNSIGNED NOT NULL,
  `types_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `types_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BANK', 1, '2021-08-05 20:24:40', '2021-08-05 20:24:40'),
(2, 'CURRENT', 1, '2021-08-05 20:24:40', '2021-08-05 20:24:40'),
(3, 'CURRLIAB', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(4, 'DEPRECIATN', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(5, 'DIRECTCOSTS', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(6, 'EQUITY', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(7, 'EXPENSE', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(8, 'FIXED', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(9, 'INVENTORY', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(10, 'LIABILITY', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(11, 'NONCURRENT', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(12, 'OTHERINCOME', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(13, 'OVERHEADS', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(14, 'PREPAYMENT', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(15, 'REVENUE', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(16, 'SALES', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(17, 'TERMLIAB', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(18, 'PAYGLIABILITY', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(19, 'SUPERANNUATIONEXPENSE', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(20, 'SUPERANNUATIONLIABILITY', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00'),
(21, 'WAGESEXPENSE', 1, '2021-08-05 20:27:00', '2021-08-05 20:27:00');

-- --------------------------------------------------------

--
-- Table structure for table `auths`
--

CREATE TABLE `auths` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auths`
--

INSERT INTO `auths` (`id`, `user_id`, `token`, `created_at`, `updated_at`) VALUES
(1, 1, 'O:56:\"QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\":11:{s:72:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0accessTokenKey\";s:863:\"eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..qjJwkWbRZZSOfqGYiWqRIA.j6-p6u9E_vkaf5_kBIrK2mNaBIeWpsD04q6-U9KwEo_Q1XifBLFliOVWpjggE3JqZJN9czPU2MntJCaWttgau76CB2ako87EZT4EX7dEauTzWwjuTlVZ5dEuRgCTO-dPvzCleI5roVUMG4KNsK0xcVVUqRPDy2fJCuev87-h7LXE-22balGy2W0mAIX6cSVAs5sPn3bree1AzqaQPaP5i6CTMQAaafJwQego_PhD3a0gY1D2GcMmf4R51lnwCSuuM-Xpcty2tXBbFzQBJ73GmoqTNbiCAez5tQV-cV4GtYQex7RfpBeO1p34SyE-aMTFQFx_o--y1mj03yyRpJL8e-myRCWHdC2Vk9IP_WWhFPeBnpqpFJwmhPN745YijvuNR27OG4RvktC7JnTTfY6X-K2oBgxkz843c71jn8nOcIjgMl11TnSgEbf89l4lKO5lzDlSnBy3K1T_cNZIybu9NktDvAH3LIwmM1koBZCkg7H_x_cdUbKDkkAvOhdC39tSCPtZF_lyfveGQSyJCqY3oBN4KAdPXIEUsDig_UFhzQWtGMWTXJZjbM6NB8O5g4O4K6sL-UoEvXRFYXLcfMS2pD6n2IaKdwT3rdY7uh-tbnwG2iQZAP8Xr0WaPI7V0C7Y11fn8Y1rIhM7RI50d1H46lVZkuUV_erRD2AgYBlvyr7l8uCuhnRgsm9ssZH6pZoLaXROcpOogy4QMJKLMz82BiSaYxYdh1YYjEnjxhxcapo515QVmeQYN6qNP5r4fa5_.PSMSZzIS1gFcYgU0yj0cnA\";s:67:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0tokenType\";s:6:\"bearer\";s:71:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0refresh_token\";s:50:\"AB11714758888d8HIhTtZW8BjkGtSciDNu6LDaczECjKk1dxI1\";s:78:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0accessTokenExpiresAt\";i:1706113628;s:79:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0refreshTokenExpiresAt\";i:1714758891;s:85:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0accessTokenValidationPeriod\";i:3600;s:86:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0refreshTokenValidationPeriod\";i:8648863;s:66:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0clientID\";s:50:\"AB5kFbletRbjWcZWUqor6CHxtY730MlAZ9nEcuFNtmjfNwOdtU\";s:70:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0clientSecret\";s:40:\"oW2mxLmn6WgFxQOzKDn9xrSGV4j8i0RkKo6gaAYW\";s:65:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0realmID\";s:16:\"9130357849536636\";s:65:\"\0QuickBooksOnline\\API\\Core\\OAuth\\OAuth2\\OAuth2AccessToken\0baseURL\";s:1:\"/\";}', '2020-12-18 16:36:21', '2024-01-24 09:27:08'),
(2, 2, NULL, '2021-01-08 21:52:55', '2021-01-08 21:52:55');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `quickbooks_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SyncToken` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `quickbooks_id`, `name`, `email`, `phone`, `address`, `country`, `city`, `state`, `zip`, `SyncToken`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Md Arman', 'amd55077@gmail.com', '01787281564', 'Hathazari', NULL, 'Chittagong', 'Chittagong', 'CTG', 0, NULL, NULL),
(2, NULL, 'John Duo', 'joh@gmail.com', '0181827586', 'Address', 'BD', 'Dhaka', 'BD', '3465', 0, '2024-02-02 08:10:27', '2024-02-02 09:00:10'),
(3, NULL, 'Leo Messi', 'messi@gmail.com', '4567890', 'CTG', 'Argentina', 'Dhaka', 'ARG', '4356', 0, '2024-02-02 09:11:37', '2024-02-02 09:11:37'),
(4, NULL, 'MArdond', 'sds@gmail.com', '9876543', 'jhgfds', 'BD', 'sd', 'dd', 'd45', 0, '2024-02-02 09:32:00', '2024-02-02 09:32:00'),
(5, '66', 'Shakib Al HAsan', 'hasanAlihasn@gmail.com', '01877788456', 'Hatahzari CTG', 'BD', '3dasdsa', 'dwd', 'sf5446', 3, '2024-02-02 09:34:00', '2024-02-02 09:42:57'),
(6, '1', 'Amy\'s Bird Sanctuary', 'Birds@Intuit.com', '(650) 555-3311', '4581 Finch St.', NULL, 'Bayshore', 'CA', '94326', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(7, '2', 'Bill\'s Windsurf Shop', 'Surf@Intuit.com', '(415) 444-6538', '12 Ocean Dr.', NULL, 'Half Moon Bay', 'CA', '94213', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(8, '3', 'Cool Cars', 'Cool_Cars@intuit.com', '(415) 555-9933', '65 Ocean Dr.', NULL, 'Half Moon Bay', 'CA', '94213', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(9, '59', 'David', 'brck@gmail.com', '01818224554', 'London', 'England', 'London', '34535', '45656', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(10, '58', 'David Beckham', 'david@gmail.com', '01818298458', 'BD', 'SA', 'Dhaka', 'SA', '34545', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(11, '62', 'dd', 'arf@gmail.com', '234567890', 'dfghjkl;', 'tyuio', 'dfghj', 'dfgh', 'fgh', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(12, '64', 'DIago Maradona', 'y6@gmail.com', '534545', '646', 'rgg', 'gg', 'gr', 'gr', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(13, '4', 'Diego Rodriguez', 'Diego@Rodriguez.com', '(650) 555-4477', '321 Channing', NULL, 'Palo Alto', 'CA', '94303', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(14, '5', 'Dukes Basketball Camp', 'Dukes_bball@intuit.com', '(520) 420-5638', '25 Court St.', NULL, 'Tucson', 'AZ', '85719', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(15, '6', 'Dylan Sollfrank', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(16, '61', 'fdsds', 'david@gmail.com', 'sdsd', 'sdsrr', 'd332', '3dasdsa', 'fsaf', '35', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(17, '7', 'Freeman Sporting Goods', 'Sporting_goods@intuit.com', '(650) 555-0987', '370 Easy St.', NULL, 'Middlefield', 'CA', '94482', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(18, '8', '0969 Ocean View Road', 'Sporting_goods@intuit.com', '(415) 555-9933', '370 Easy St.', NULL, 'Middlefield', 'CA', '94482', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(19, '9', '55 Twin Lane', 'Sporting_goods@intuit.com', '(650) 555-0987', '370 Easy St.', NULL, 'Middlefield', 'CA', '94482', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(20, '10', 'Geeta Kalapatapu', 'Geeta@Kalapatapu.com', '(650) 555-0022', '1987 Main St.', NULL, 'Middlefield', 'CA', '94303', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(21, '11', 'Gevelber Photography', 'Photography@intuit.com', '(415) 222-4345', '1045 Main St.', NULL, 'Half Moon Bay', 'CA', '94213', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(22, '12', 'Jeff\'s Jalopies', 'Jalopies@intuit.com', '(650) 555-8989', '12 Willow Rd.', NULL, 'Menlo Park', 'CA', '94305', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(23, '13', 'John Melton', 'John@Melton.com', '(650) 555-5879', '85 Pine St.', NULL, 'Menlo Park', 'CA', '94304', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(24, '14', 'Kate Whelan', 'Kate@Whelan.com', '(650) 554-8822', '45 First St.', 'USA', 'Menlo Park', 'CA', '94304', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(25, '16', 'Kookies by Kathy', 'qbwebsamplecompany@yahoo.com', '(650) 555-7896', '789 Sugar Lane', NULL, 'Middlefield', 'CA', '94303', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(26, '63', 'Leo Messi', 'messi@gmail.com', '4567890', 'CTG', 'Argentina', 'Dhaka', 'ARG', '4356', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(27, '65', 'MArdond', 'sds@gmail.com', '9876543', 'jhgfds', 'BD', 'sd', 'dd', 'd45', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(28, '17', 'Mark Cho', 'Mark@Cho.com', '(650) 554-1479', '36 Willow Rd', NULL, 'Menlo Park', 'CA', '94304', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(29, '18', 'Paulsen Medical Supplies', 'Medical@intuit.com', '(650) 557-4569', '900 Main St.', NULL, 'Middlefield', 'CA', '94303', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(30, '15', 'Pye\'s Cakes', 'pyescakes@intuit.com', '(973) 555-4652', '350 Mountain View Dr.', NULL, 'South Orange', 'NJ', '07079', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(31, '19', 'Rago Travel Agency', 'Rago_Travel@intuit.com', '(650) 555-1596', '753 Cedar St.', NULL, 'Bayshore', 'CA', '94326', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(32, '20', 'Red Rock Diner', 'qbwebsamplecompany@yahoo.com', '(650) 555-4973', '500 Red Rock Rd.', NULL, 'Bayshore', 'CA', '94326', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(33, '21', 'Rondonuwu Fruit and Vegi', 'Tony@Rondonuwu.com', '(650) 555-2645', '847 California Ave.', NULL, 'San Jose', 'CA', '95021', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(34, '22', 'Shara Barnett', 'Shara@Barnett.com', '(650) 555-4563', '77 University', NULL, 'Palo Alto', 'CA', '94303', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(35, '23', 'Barnett Design', 'Design@intuit.com', '(650) 557-1289', '19 Main St.', NULL, 'Middlefield', 'CA', '94303', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(36, '24', 'Sonnenschein Family Store', 'Familiystore@intuit.com', '(650) 557-8463', '5647 Cypress Hill Ave.', NULL, 'Middlefield', 'CA', '94303', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(37, '25', 'Sushi by Katsuyuki', 'Sushi@intuit.com', '(505) 570-0147', '898 Elm St.', NULL, 'Maplewood', 'NJ', '07040', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(38, '60', 'tadg', 'david@gmail.com', '453434634', '436436', '6343', '6346', '664', '35', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(39, '26', 'Travis Waldron', 'Travis@Waldron.com', '(650) 557-9977', '78 First St.', NULL, 'Monlo Park', 'CA', '94304', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(40, '27', 'Video Games by Dan', 'Videogames@intuit.com', '(650) 555-3456', '202 Main St.', NULL, 'Tucson', 'AZ', '85704', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(41, '28', 'Wedding Planning by Whitney', 'Dream_Wedding@intuit.com', '(650) 557-2473', '135 Broadway', NULL, 'Menlo Park', 'CA', '94304', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15'),
(42, '29', 'Weiskopf Consulting', 'Consulting@intuit.com', '(650) 555-1423', '45612 Main St.', NULL, 'Bayshore', 'CA', '94326', 0, '2024-02-02 10:22:15', '2024-02-02 10:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `total_before_discount` decimal(10,2) NOT NULL,
  `total_after_discount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `total_before_discount`, `total_after_discount`, `status`, `payment_type`, `created_at`, `updated_at`) VALUES
(1, 1, '1657.00', '1574.15', 'pending', NULL, '2024-02-01 10:04:55', '2024-02-02 07:49:18'),
(2, 1, '50.00', '50.00', 'pending', NULL, '2024-02-01 10:14:58', '2024-02-01 10:14:58'),
(3, 1, '50.00', '47.50', 'pending', NULL, '2024-02-01 10:18:50', '2024-02-01 10:18:50'),
(4, 1, '50.00', '49.00', 'pending', NULL, '2024-02-01 10:21:53', '2024-02-01 10:21:53'),
(5, 1, '100.00', '100.00', 'pending', NULL, '2024-02-01 10:24:18', '2024-02-01 10:24:18'),
(6, 1, '150.00', '150.00', 'pending', NULL, '2024-02-01 10:25:45', '2024-02-01 10:25:45'),
(7, 1, '150.00', '150.00', 'pending', NULL, '2024-02-01 10:29:05', '2024-02-01 10:29:05'),
(8, 1, '200.00', '200.00', 'pending', NULL, '2024-02-01 10:38:29', '2024-02-01 10:38:29'),
(9, 1, '280.00', '268.80', 'pending', NULL, '2024-02-01 10:45:35', '2024-02-01 10:45:35'),
(10, 1, '200.00', '200.00', 'pending', NULL, '2024-02-01 10:47:18', '2024-02-01 10:47:18'),
(11, 1, '965.00', '897.45', 'pending', NULL, '2024-02-02 11:40:15', '2024-02-02 11:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_details`
--

INSERT INTO `invoice_details` (`id`, `invoice_id`, `product_id`, `quantity`, `unit_price`, `total`, `created_at`, `updated_at`) VALUES
(3, 2, 5, 1, '50.00', '50.00', '2024-02-01 10:14:58', '2024-02-01 10:14:58'),
(4, 3, 5, 1, '50.00', '50.00', '2024-02-01 10:18:50', '2024-02-01 10:18:50'),
(5, 4, 5, 1, '50.00', '50.00', '2024-02-01 10:21:53', '2024-02-01 10:21:53'),
(6, 5, 5, 2, '50.00', '100.00', '2024-02-01 10:24:18', '2024-02-01 10:24:18'),
(7, 6, 5, 3, '50.00', '150.00', '2024-02-01 10:25:45', '2024-02-01 10:25:45'),
(8, 7, 5, 3, '50.00', '150.00', '2024-02-01 10:29:05', '2024-02-01 10:29:05'),
(9, 8, 5, 4, '50.00', '200.00', '2024-02-01 10:38:29', '2024-02-01 10:38:29'),
(10, 9, 7, 4, '70.00', '280.00', '2024-02-01 10:45:35', '2024-02-01 10:45:35'),
(11, 10, 5, 4, '50.00', '200.00', '2024-02-01 10:47:18', '2024-02-01 10:47:18'),
(17, 1, 7, 4, '70.00', '280.00', '2024-02-02 07:49:18', '2024-02-02 07:49:18'),
(18, 1, 11, 5, '275.00', '1375.00', '2024-02-02 07:49:18', '2024-02-02 07:49:18'),
(19, 1, 16, 1, '2.00', '2.00', '2024-02-02 07:49:18', '2024-02-02 07:49:18'),
(20, 11, 13, 2, '70.00', '140.00', '2024-02-02 11:40:15', '2024-02-02 11:40:15'),
(21, 11, 17, 3, '275.00', '825.00', '2024-02-02 11:40:15', '2024-02-02 11:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_info`
--

CREATE TABLE `invoice_info` (
  `id` bigint UNSIGNED NOT NULL,
  `InvoiceID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ContactID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ContactName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `InvoiceNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InvoiceType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `SubTotal` double DEFAULT NULL,
  `Total` double DEFAULT NULL,
  `TotalTax` double DEFAULT NULL,
  `AmountDue` double DEFAULT NULL,
  `AmountPaid` double DEFAULT NULL,
  `AmountCredited` double DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `DueDate` date NOT NULL,
  `LineAmountTypes` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `BrandingThemeID` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `CurrencyCode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `CurrencyRate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `InvoiceStatus` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_info`
--

INSERT INTO `invoice_info` (`id`, `InvoiceID`, `ContactID`, `ContactName`, `InvoiceNumber`, `Reference`, `InvoiceType`, `SubTotal`, `Total`, `TotalTax`, `AmountDue`, `AmountPaid`, `AmountCredited`, `InvoiceDate`, `DueDate`, `LineAmountTypes`, `BrandingThemeID`, `CurrencyCode`, `CurrencyRate`, `InvoiceStatus`, `status`, `created_at`, `updated_at`) VALUES
(7, 'b9c23d92-2ea1-437d-ac6c-0dfbb726067f', 'cd8a78b4-fde6-429c-88cf-c01368a508f6', 'Xero to Custom Apps', 'INV-0007', 'test', 'ACCREC', 7, 7, 0, 7, 0, 0, '2022-03-27', '2022-04-10', 'Exclusive', 'db699719-c19f-48a0-97eb-71f754cc237c', 'BDT', '1', 'DRAFT', 1, '2022-03-26 23:50:16', '2022-03-26 23:50:16'),
(8, 'd1e1a277-40d9-4908-93d2-f229bebbc15f', '9e3b12a1-78cb-45a3-a49a-e1b7509a8fdb', 'Custom apps to Xero UpdatE', 'INV-0008', 'CustomRef-34019', 'ACCREC', 5, 5, 0, 5, 0, 0, '2022-03-27', '2022-04-12', 'NoTax', 'db699719-c19f-48a0-97eb-71f754cc237c', 'BDT', '1', 'SUBMITTED', 1, '2022-03-26 23:51:18', '2022-03-26 23:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

CREATE TABLE `invoice_products` (
  `id` bigint UNSIGNED NOT NULL,
  `invoiceId` int NOT NULL COMMENT 'table auto increment id of "invoice_ifo" table',
  `LineItemID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itemDescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `itemQuantity` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itemUnitAmount` double DEFAULT NULL,
  `DiscountRate` double DEFAULT NULL,
  `itemCode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itemAccountCode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itemTaxType` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itemTaxAmount` double DEFAULT NULL,
  `itemLineAmount` double DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_products`
--

INSERT INTO `invoice_products` (`id`, `invoiceId`, `LineItemID`, `itemDescription`, `itemQuantity`, `itemUnitAmount`, `DiscountRate`, `itemCode`, `itemAccountCode`, `itemTaxType`, `itemTaxAmount`, `itemLineAmount`, `status`, `created_at`, `updated_at`) VALUES
(20, 7, 'e86cf799-35a1-4bf7-8e94-f91e20d268f0', 'Item 2', '1.0000', 2, 0, '200', '200', 'OUTPUT', 0, 2, 1, '2022-03-26 23:50:16', '2022-03-26 23:50:16'),
(21, 7, '0b860f2e-19a9-4cfc-8620-b9d0f1054b47', 'Item Three', '1.0000', 5, 0, '260', '260', 'OUTPUT', 0, 5, 1, '2022-03-26 23:50:16', '2022-03-26 23:50:16'),
(23, 8, 'cb288f35-b86a-4a80-931e-4289336ba7de', 'This is testing', '1.0000', 5, 0, '260', '200', 'NONE', 0, 5, 1, '2022-03-26 23:51:28', '2022-03-26 23:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_11_28_181740_create_customer_table', 1),
(5, '2020_12_14_202303_create_settings_table', 1),
(6, '2020_12_18_172058_create_auths_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('moshahed777@gmail.com', '$2y$10$/u9mbDSZFognJZ2vVmmoxeCFJN9wVT1mqgwvkTEoLHg/LxVmRbGDy', '2023-12-19 21:52:52');

-- --------------------------------------------------------

--
-- Table structure for table `payment_requests`
--

CREATE TABLE `payment_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `ItemId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci,
  `Active` tinyint(1) DEFAULT '1',
  `FullyQualifiedName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Taxable` tinyint(1) DEFAULT NULL,
  `UnitPrice` decimal(10,2) DEFAULT '0.00',
  `Type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IncomeAccountRef` json DEFAULT NULL,
  `PurchaseCost` decimal(10,2) DEFAULT NULL,
  `TrackQtyOnHand` tinyint(1) DEFAULT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sparse` tinyint(1) DEFAULT NULL,
  `SyncToken` int DEFAULT NULL,
  `createdby` int NOT NULL,
  `updatedby` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `ItemId`, `Name`, `Description`, `Active`, `FullyQualifiedName`, `Taxable`, `UnitPrice`, `Type`, `IncomeAccountRef`, `PurchaseCost`, `TrackQtyOnHand`, `domain`, `sparse`, `SyncToken`, `createdby`, `updatedby`, `created_at`, `updated_at`) VALUES
(1, '19', 'Arman', NULL, 1, 'Arman', NULL, NULL, 'Category', NULL, NULL, NULL, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(2, '3', 'Concrete', 'Concrete for fountain installation', 1, 'Concrete', 0, '50.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 2, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(3, '22', 'dd Arman', 'Garden Description', 1, 'dd Arman', 0, '0.00', 'Inventory', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 1, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(4, '4', 'Design', 'Custom Design', 1, 'Design', 0, '100.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 1, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(5, '30', 'dsfdsfdsfdsfsfsfdf', 'fdgg', 1, 'dsfdsfdsfdsfsfsfdf', 0, '344.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(6, '29', 'dsfsfsfdf', 'fdgg', 1, 'dsfsfsfdf', 0, '344.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(7, '21', 'Garden Arman', NULL, 1, 'Garden Arman', 0, '0.00', 'Inventory', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 1, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(8, '20', 'Garden Supplies', NULL, 1, 'Garden Supplies', 0, '0.00', 'Inventory', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 1, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(9, '6', 'Gardening', 'Weekly Gardening Service', 1, 'Gardening', 0, '0.00', 'Service', '{\"name\": \"Landscaping Services\", \"value\": \"45\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(10, '2', 'Hours', NULL, 1, 'Hours', 0, '0.00', 'Service', '{\"name\": \"Services\", \"value\": \"1\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(11, '7', 'Installation', 'Installation of landscape design', 1, 'Installation', 0, '50.00', 'Service', '{\"name\": \"Installation\", \"value\": \"52\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(12, '8', 'Lighting', 'Garden Lighting', 1, 'Lighting', 1, '0.00', 'Service', '{\"name\": \"Fountains and Garden Lighting\", \"value\": \"48\"}', '0.00', 0, 'QBO', 0, 1, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(13, '9', 'Maintenance & Repair', 'Maintenance & Repair', 1, 'Maintenance & Repair', 0, '70.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 1, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(14, '10', 'Pest Control', 'Pest Control Services', 1, 'Pest Control', 0, '35.00', 'Service', '{\"name\": \"Pest Control Services\", \"value\": \"54\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(15, '11', 'Pump', 'Fountain Pump', 1, 'Pump', 1, '15.00', 'Inventory', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '10.00', 1, 'QBO', 0, 3, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(16, '12', 'Refunds & Allowances', 'Income due to refunds or allowances', 1, 'Refunds & Allowances', 0, '0.00', 'Service', '{\"name\": \"Other Income\", \"value\": \"83\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(17, '5', 'Rock Fountain', 'Rock Fountain', 1, 'Rock Fountain', 1, '275.00', 'Inventory', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '125.00', 1, 'QBO', 0, 2, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(18, '13', 'Rocks', 'Garden Rocks', 1, 'Rocks', 1, '0.00', 'Service', '{\"name\": \"Fountains and Garden Lighting\", \"value\": \"48\"}', '0.00', 0, 'QBO', 0, 1, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(19, '1', 'Services', NULL, 1, 'Services', 0, '0.00', 'Service', '{\"name\": \"Services\", \"value\": \"1\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(20, '14', 'Sod', 'Sod', 1, 'Sod', 1, '0.00', 'Service', '{\"name\": \"Plants and Soil\", \"value\": \"49\"}', '0.00', 0, 'QBO', 0, 1, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(21, '15', 'Soil', '2 cubic ft. bag', 1, 'Soil', 1, '10.00', 'Service', '{\"name\": \"Plants and Soil\", \"value\": \"49\"}', '6.50', 0, 'QBO', 0, 2, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(22, '16', 'Sprinkler Heads', 'Sprinkler Heads', 1, 'Sprinkler Heads', 1, '2.00', 'Inventory', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.75', 1, 'QBO', 0, 3, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(23, '17', 'Sprinkler Pipes', 'Sprinkler Pipes', 1, 'Sprinkler Pipes', 1, '4.00', 'Inventory', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '2.50', 1, 'QBO', 0, 3, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(24, '28', 'tadg', 'fdgg', 1, 'tadg', 0, '344.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(25, '27', 'Test dfdf', 'fffff', 1, 'Test dfdf', 0, '1000.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(26, '25', 'Test New', 'Test New', 1, 'Test New', 0, '0.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(27, '23', 'Test Service', NULL, 1, 'Test Service', 0, '0.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(28, '26', 'Test Service three', NULL, 1, 'Test Service three', 0, '500.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(29, '24', 'Test Service two', NULL, 1, 'Test Service two', 0, '0.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(30, '31', 'Thats all', 'fdgg', 1, 'Thats all', 0, '344.00', 'Service', '{\"name\": \"Sales of Product Income\", \"value\": \"79\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33'),
(31, '18', 'Trimming', 'Tree and Shrub Trimming', 1, 'Trimming', 0, '35.00', 'Service', '{\"name\": \"Landscaping Services\", \"value\": \"45\"}', '0.00', 0, 'QBO', 0, 0, 1, 1, '2024-02-02 10:16:33', '2024-02-02 10:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ClientID` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ClientSecret` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `AccessToken` text COLLATE utf8mb4_unicode_ci,
  `RedirectURI` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `baseUrl` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `QBORealmID` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `ClientID`, `ClientSecret`, `AccessToken`, `RedirectURI`, `scope`, `baseUrl`, `QBORealmID`, `created_at`, `updated_at`) VALUES
(1, 1, 'AB5kFbletRbjWcZWUqor6CHxtY730MlAZ9nEcuFNtmjfNwOdtU', 'oW2mxLmn6WgFxQOzKDn9xrSGV4j8i0RkKo6gaAYW', 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..gWtNOQ_npS-RSgrXYiPwYg.67ywnTiXUUt2NgXdT9GinH-9DUFHKgwYRlnlap1uVs-8Ihmm0Q5hhzRRLys9QWI6_y-KkZquQe1OChlUX5grnvEONy9efsRPAAnL4zl4m9j_5ARt79s1warmVuMOUego9XWrvRpVWZWWiqhelhw-lvT2--cgkOvoIIIytd1GaWGQzTh9j0ulVhczUDx_7esjHuKjbugwT_DrmK3sxbDjT1yes7411PILCdhgrNAKkZmNuR_AMAAGdIV0kEFBb-JS1pCALqtdtHWimKzVXppMtEYdGAFFaW5qjas4Vg5ABWLXBFqs-iyLbeMqtqh3VIlb3OdV4bQm07RGhp6-LUViTJXaLZeHNmPTrjZKz_GT690JEvFichHUkFpSpTMOkQViaaXJO4O5vc8KP6sdq9H_1pL4OywXFXcnWYiYUgPUazORkoyxhXPqwl0lSbh9ETsATflp7W2DZ3gYMbswJ7tEjGxvuNr_Y3ztY2VlSILSlTYzC1F41euiofNHJOHHjfqJF7CmK2L88Yg1SRsmPdxUnEJG-UzhlYkZeN1TleCTBj0Y_7BXeNVE1UgyLfyuutPvNR3lafgL1rx3m6RaMArsuHewx_Z_gS5ZhSgMw1jkMXsIbMLzHyf2NoBT-y8_mvnBe_6-hlgPlYt1DKIL9F7Ay8TW6klvUbI3sJmOmxBf1ktBmn6lbRyXcUjUM8yKzuNuvteB-mtUpbdCZlsBLPpGoqCdLCuvaTqABF1udA6RgXPCxScP52ma8pvfpddwPDJb.VjcBz6R9BcLDK1y9uPrmpA', 'http://127.0.0.1:8000/quickbook/1/callback', 'com.intuit.quickbooks.accounting', '', '9130357849536636', '2021-07-24 14:37:07', '2024-01-29 10:02:35'),
(2, 2, 'AB5kFbletRbjWcZWUqor6CHxtY730MlAZ9nEcuFNtmjfNwOdtU', 'oW2mxLmn6WgFxQOzKDn9xrSGV4j8i0RkKo6gaAYW', 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..gWtNOQ_npS-RSgrXYiPwYg.67ywnTiXUUt2NgXdT9GinH-9DUFHKgwYRlnlap1uVs-8Ihmm0Q5hhzRRLys9QWI6_y-KkZquQe1OChlUX5grnvEONy9efsRPAAnL4zl4m9j_5ARt79s1warmVuMOUego9XWrvRpVWZWWiqhelhw-lvT2--cgkOvoIIIytd1GaWGQzTh9j0ulVhczUDx_7esjHuKjbugwT_DrmK3sxbDjT1yes7411PILCdhgrNAKkZmNuR_AMAAGdIV0kEFBb-JS1pCALqtdtHWimKzVXppMtEYdGAFFaW5qjas4Vg5ABWLXBFqs-iyLbeMqtqh3VIlb3OdV4bQm07RGhp6-LUViTJXaLZeHNmPTrjZKz_GT690JEvFichHUkFpSpTMOkQViaaXJO4O5vc8KP6sdq9H_1pL4OywXFXcnWYiYUgPUazORkoyxhXPqwl0lSbh9ETsATflp7W2DZ3gYMbswJ7tEjGxvuNr_Y3ztY2VlSILSlTYzC1F41euiofNHJOHHjfqJF7CmK2L88Yg1SRsmPdxUnEJG-UzhlYkZeN1TleCTBj0Y_7BXeNVE1UgyLfyuutPvNR3lafgL1rx3m6RaMArsuHewx_Z_gS5ZhSgMw1jkMXsIbMLzHyf2NoBT-y8_mvnBe_6-hlgPlYt1DKIL9F7Ay8TW6klvUbI3sJmOmxBf1ktBmn6lbRyXcUjUM8yKzuNuvteB-mtUpbdCZlsBLPpGoqCdLCuvaTqABF1udA6RgXPCxScP52ma8pvfpddwPDJb.VjcBz6R9BcLDK1y9uPrmpA', 'http://127.0.0.1:8000/quickbook/2/callback', 'com.intuit.quickbooks.accounting', NULL, '9130357849536636', '2024-01-30 09:25:06', '2024-01-30 09:25:06'),
(3, 12, 'AB5kFbletRbjWcZWUqor6CHxtY730MlAZ9nEcuFNtmjfNwOdtU', 'oW2mxLmn6WgFxQOzKDn9xrSGV4j8i0RkKo6gaAYW', NULL, 'http://127.0.0.1:8000/quickbook/12/callback', 'com.intuit.quickbooks.accounting', NULL, '9130357849536636', '2024-02-02 11:46:12', '2024-02-02 11:46:12'),
(4, 13, 'Asafsdf', 'fsf', NULL, 'http://127.0.0.1:8000/quickbook/13/callback', 'ffdf', 'http://127.0.0.1:8000', 'ere', '2024-02-02 12:16:05', '2024-02-02 12:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `settings_old`
--

CREATE TABLE `settings_old` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `xero_webhook` int NOT NULL DEFAULT '0',
  `xero_webhook_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `xero_client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xero_client_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xero_redirect_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xero_auth_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings_old`
--

INSERT INTO `settings_old` (`id`, `user_id`, `xero_webhook`, `xero_webhook_key`, `xero_client_id`, `xero_client_secret`, `xero_redirect_url`, `xero_auth_token`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'p/kNH0fVYOcis7TjOaQVmSD8dUZh8l2swCFoxAeoHnVggjiB/71tQC7smkOL5XrztW75iueR4G4s5v+I3YW1Lg==', '1EA88DDD46A448A097BB7F57C925268A', '5dKJjg2o-E14pAAqsfOd1MC8eULpvUFyXHkE5ykzn7MhafwY', 'https://moshahedalam.com/xero/xero-demo/auth/1', 'O:38:\"League\\OAuth2\\Client\\Token\\AccessToken\":5:{s:14:\"\0*\0accessToken\";s:1262:\"eyJhbGciOiJSUzI1NiIsImtpZCI6IjFDQUY4RTY2NzcyRDZEQzAyOEQ2NzI2RkQwMjYxNTgxNTcwRUZDMTkiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJISy1PWm5jdGJjQW8xbkp2MENZVmdWY09fQmsifQ.eyJuYmYiOjE2NDgzMjAzMDgsImV4cCI6MTY0ODMyMjEwOCwiaXNzIjoiaHR0cHM6Ly9pZGVudGl0eS54ZXJvLmNvbSIsImF1ZCI6Imh0dHBzOi8vaWRlbnRpdHkueGVyby5jb20vcmVzb3VyY2VzIiwiY2xpZW50X2lkIjoiMUVBODhEREQ0NkE0NDhBMDk3QkI3RjU3QzkyNTI2OEEiLCJzdWIiOiJkMTcxNmEzMWZlMjQ1MTU5Yjk3MTgzNWI0YjQwYzMxOSIsImF1dGhfdGltZSI6MTY0ODMxNDIzMiwieGVyb191c2VyaWQiOiI5YjU1NzgwZC1mMTk0LTRhODctYTdlMi1jOWMzOTY2YzBkMDQiLCJnbG9iYWxfc2Vzc2lvbl9pZCI6IjAzYzM3NWZhOWY1MTQ2N2M5NDlkZjU0YWY3ZTQyOTFmIiwianRpIjoiMmMwZjc4MDA1ZTEwNGU4MTMwYTA4NWVkYTdlMGM1NDUiLCJhdXRoZW50aWNhdGlvbl9ldmVudF9pZCI6IjliMzM3NWYxLTNlN2ItNGMzMS1hOWUwLWM0ZTFlNjYwYjU4NCIsInNjb3BlIjpbImVtYWlsIiwicHJvZmlsZSIsIm9wZW5pZCIsImFjY291bnRpbmcuc2V0dGluZ3MiLCJhY2NvdW50aW5nLnRyYW5zYWN0aW9ucyIsImFjY291bnRpbmcuY29udGFjdHMiLCJvZmZsaW5lX2FjY2VzcyJdLCJhbXIiOlsic3NvIl19.qrThEgF-TuilDHh7vk3mmsxp_wqUjwuBh2wojc8MhB5ZEfl5BJHgB913V_imluq90y4M9eisxuhXqAQW51hTK2SD6s3vFcEpLqCMUl8b8PKUUsVNRK-DCKfEV57fHC8WLfDUN1PtCDLyYFpHkpd5rC1Xeq5i4a-PeMG5MJNvEEpPviGGLkvrKo1-IW_nGy7zXETl_5K10U0z9FCW0F6kp9a5Thnymkyxrhbq40FfTFdKUAaVIMmKorHSHhAptZDvvZjov47ZyKqANgvFnVIWQi5DSPpZGg_nTh4xtgJOnJ0zRtwIyQE21ChZxj3hXhZjr2tGhXVPO0ZZ7qddcXPJqw\";s:10:\"\0*\0expires\";i:1648322108;s:15:\"\0*\0refreshToken\";s:64:\"e9f2df5d2e75c8c9c2d0e84be942560d0c65a0d59a987fb109015292074972db\";s:18:\"\0*\0resourceOwnerId\";N;s:9:\"\0*\0values\";a:3:{s:8:\"id_token\";s:1209:\"eyJhbGciOiJSUzI1NiIsImtpZCI6IjFDQUY4RTY2NzcyRDZEQzAyOEQ2NzI2RkQwMjYxNTgxNTcwRUZDMTkiLCJ0eXAiOiJKV1QiLCJ4NXQiOiJISy1PWm5jdGJjQW8xbkp2MENZVmdWY09fQmsifQ.eyJuYmYiOjE2NDgzMjAzMDgsImV4cCI6MTY0ODMyMDYwOCwiaXNzIjoiaHR0cHM6Ly9pZGVudGl0eS54ZXJvLmNvbSIsImF1ZCI6IjFFQTg4RERENDZBNDQ4QTA5N0JCN0Y1N0M5MjUyNjhBIiwiaWF0IjoxNjQ4MzIwMzA4LCJhdF9oYXNoIjoiZTVibW9aQjc5TTFSS3VoS3pJUm5QZyIsInNpZCI6IjAzYzM3NWZhOWY1MTQ2N2M5NDlkZjU0YWY3ZTQyOTFmIiwic3ViIjoiZDE3MTZhMzFmZTI0NTE1OWI5NzE4MzViNGI0MGMzMTkiLCJhdXRoX3RpbWUiOjE2NDgzMTQyMzIsInhlcm9fdXNlcmlkIjoiOWI1NTc4MGQtZjE5NC00YTg3LWE3ZTItYzljMzk2NmMwZDA0IiwiZ2xvYmFsX3Nlc3Npb25faWQiOiIwM2MzNzVmYTlmNTE0NjdjOTQ5ZGY1NGFmN2U0MjkxZiIsInByZWZlcnJlZF91c2VybmFtZSI6InRhaG1pbmFjaHkwMUBnbWFpbC5jb20iLCJlbWFpbCI6InRhaG1pbmFjaHkwMUBnbWFpbC5jb20iLCJnaXZlbl9uYW1lIjoiTW9zaGFoZWQiLCJmYW1pbHlfbmFtZSI6IkFsYW0iLCJuYW1lIjoiTW9zaGFoZWQgQWxhbSIsImFtciI6WyJzc28iXX0.TlR2d4_vA21p9LoRKzCvoWTIi8TOdYGaEw189a2aAM-_uOG2IVZx_pmtyqGeAJCaUa4ZtBF7kpicEr_ECV0ssxoUnG0Gx9MVP6nO-n2MwNl0hTHoeDrzFNilsZ7CfszeX-2qhQCb0eSiFUAeNadqIP6HOaumhhJmCaX-mqFMyWEaOdJLGoNpyUlk_Oy-X-Xcmdo6uk7Tui--Qg60GVvg8rrT6uxhZ3T_SLH-IHgVdpBk-4PxNk-ACtwCEAIEe0SwP0UtgDv7WDkyWhEDig0uS1NFRd6EK317xnpoKQVlr--Gu4kNFsaxLm2OleE77KhTWTtV5hbMrd7FzTlIEroCfQ\";s:10:\"token_type\";s:6:\"Bearer\";s:5:\"scope\";s:99:\"openid email profile accounting.settings accounting.transactions accounting.contacts offline_access\";}}', '2021-07-24 14:37:07', '2022-03-26 23:45:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Demo', 'moshahed777@gmail.com', NULL, '$2y$10$GJPCm1EsCDOeR5AxpjP9G.6nfFhLEMNvsHXuk6Wv9MU3WUY8Rab9W', 'fW9wKaDgdh58PPQX1SiZYjE9nCzyPCe32IlSnwcy67EAEQelBvXCyWHbLE8R', '2020-12-18 16:36:21', '2021-07-25 12:50:47'),
(2, 'Md Arman', 'amd55077@gmail.com', NULL, '$2y$10$Rd/PCVPs5p/zMWuw50.uM.rKtlhSPa43VxZzjoRixli2PiYSCV7Mq', 'CLTZwEvmIBSzbI2orVqsvFqkGDoSt4ealDJgvC1zzTjRovS7kD9afbpBHNRT', '2024-01-26 08:30:06', '2024-01-26 08:30:06'),
(11, 'Md Arman Ullah', 'mdarmancse@gmail.com', NULL, '$2y$10$PtpSOJxfuKvKuKlr.GmUduiKrL18yNIWxNjwSR51DqI8FxhU2tnGG', NULL, '2024-02-01 13:11:58', '2024-02-01 13:11:58'),
(12, 'Moshahed Bhai', 'moshahed.alam@gmail.com', NULL, '$2y$10$qIyicLmOlDhihpn8W.km8uNHUSeBwF3VnIjGo9cLpMx26tyyt6QMS', NULL, '2024-02-02 11:44:06', '2024-02-02 11:44:06'),
(13, 'Md Arman', 'arman.ullah@nexdecade.com', NULL, '$2y$10$NHm/yoW/3uY82rG8dnlaQOk/HC1qacLw4w.YvpMg9O3n//UvTeiia', NULL, '2024-02-02 11:53:15', '2024-02-02 11:53:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auths`
--
ALTER TABLE `auths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_details_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `invoice_info`
--
ALTER TABLE `invoice_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191));

--
-- Indexes for table `payment_requests`
--
ALTER TABLE `payment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_requests_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_itemid_unique` (`ItemId`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings_old`
--
ALTER TABLE `settings_old`
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
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `invoice_info`
--
ALTER TABLE `invoice_info`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoice_products`
--
ALTER TABLE `invoice_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `payment_requests`
--
ALTER TABLE `payment_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD CONSTRAINT `invoice_details_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_requests`
--
ALTER TABLE `payment_requests`
  ADD CONSTRAINT `payment_requests_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
