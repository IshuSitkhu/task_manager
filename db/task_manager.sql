-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2026 at 02:15 PM
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
-- Database: `task_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epics`
--

CREATE TABLE `epics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('not_started','in_progress','testing','completed') NOT NULL DEFAULT 'not_started',
  `planned_start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epics`
--

INSERT INTO `epics` (`id`, `project_id`, `title`, `description`, `owner_id`, `priority`, `status`, `planned_start_date`, `planned_end_date`, `progress`, `created_at`, `updated_at`) VALUES
(1, 1, 'Authentication system', 'dsacfsad', 2, 'critical', 'in_progress', '2026-06-15', NULL, 10, '2026-06-15 01:04:45', '2026-06-19 03:42:12'),
(3, 1, 'Product Section', 'fdvd', 2, 'high', 'testing', '2026-06-26', NULL, 92, '2026-06-15 02:02:29', '2026-06-19 01:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_11_111532_add_role_to_users_table', 1),
(5, '2026_06_12_044414_create_projects_table', 1),
(6, '2026_06_12_060651_create_project_user_table', 1),
(7, '2026_06_12_100421_create_epics_table', 1),
(8, '2026_06_15_061217_create_sprints_table', 1),
(9, '2026_06_15_061237_create_tasks_table', 1),
(10, '2026_06_18_051132_change_status_to_string_in_tasks_table', 2),
(11, '2026_06_18_051418_create_project_statuses_table', 2),
(12, '2026_06_19_100541_add_image_to_tasks_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','completed','archived') NOT NULL DEFAULT 'active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `created_by`, `status`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 'ecommerce project', 'E-commerce, or electronic commerce, is the process of buying and selling goods and services over the internet. It involves the exchange of products or services between businesses, consumers, or both.\r\n\r\nE-commerce business is facilitated through platforms such as websites, mobile apps, or online marketplaces.\r\n\r\nWhere e-commerce once described a simple process, a consumer purchase from an e-commerce site, for instance, the term has expanded as technologies have advanced. Today, e-commerce can refer to business-to-business commerce or internal business transactions. It can also apply to, for example:\r\n\r\nThe online stores of multichannel retailers with brick-and-mortar locations\r\nSharing economy platforms facilitating the purchase of services like rideshares\r\nSocial media sites like Facebook where consumers engage with so-called social commerce\r\nOrganizations selling digital products for enterprise use\r\nAs the e-commerce industry has developed, it has also grown to encompass related technologies that facilitate the sales process, such as mobile payment platforms and secure data transfer technologies. Today, artificial intelligence (AI) and automation play an outsized role in the industry, helping customers intuitively discover products and facilitating data-driven advertising, content and logistics support. With the help of these technologies, e-commerce businesses can provide superior customer experiences and increase sales.', 1, 'active', '2026-06-15', '2026-06-23', '2026-06-15 00:52:46', '2026-06-15 00:52:46'),
(2, 'Card Game', NULL, 1, 'active', '2026-06-18', '2026-07-18', '2026-06-18 00:44:12', '2026-06-18 00:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `project_statuses`
--

CREATE TABLE `project_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_statuses`
--

INSERT INTO `project_statuses` (`id`, `project_id`, `name`, `slug`, `color`, `order`, `created_at`, `updated_at`) VALUES
(2, 1, 'In Progress', 'in_progress', '#3b82f6', 2, '2026-06-18 00:06:08', '2026-06-18 00:36:00'),
(3, 1, 'Review', 'review', '#a855f7', 3, '2026-06-18 00:06:08', '2026-06-18 00:36:00'),
(4, 1, 'Done', 'done', '#22c55e', 4, '2026-06-18 00:06:08', '2026-06-18 00:36:00'),
(5, 1, 'Drop', 'drop', '#f20707', 5, '2026-06-18 00:25:48', '2026-06-18 00:25:48'),
(6, 2, 'Todo', 'todo', '#6b7280', 1, '2026-06-18 00:44:12', '2026-06-18 00:44:12'),
(7, 2, 'In Progress', 'in_progress', '#3b82f6', 2, '2026-06-18 00:44:12', '2026-06-18 00:44:12'),
(8, 2, 'Review', 'review', '#a855f7', 3, '2026-06-18 00:44:12', '2026-06-18 00:44:12'),
(9, 2, 'Done', 'done', '#22c55e', 4, '2026-06-18 00:44:12', '2026-06-18 00:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `project_user`
--

CREATE TABLE `project_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_user`
--

INSERT INTO `project_user` (`id`, `project_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-06-15 00:52:58', '2026-06-15 00:52:58'),
(2, 1, 2, '2026-06-15 01:04:56', '2026-06-15 01:04:56'),
(3, 1, 3, '2026-06-19 01:49:28', '2026-06-19 01:49:28');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('MwgAkqyDPIOmYHekqO8pdc352wMoC7f04NdP8ePQ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUmQ5V1g4ckd0VkNFZmNMTVQ4WmxtMHBKbHoyaEhkb0Q2clA0ZDVqSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9qZWN0cy8xL3Rhc2tzL2JvYXJkIjtzOjU6InJvdXRlIjtzOjIwOiJwcm9qZWN0cy50YXNrcy5ib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1781871223);

-- --------------------------------------------------------

--
-- Table structure for table `sprints`
--

CREATE TABLE `sprints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `goal` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planned','active','closed') NOT NULL DEFAULT 'planned',
  `progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sprints`
--

INSERT INTO `sprints` (`id`, `project_id`, `name`, `goal`, `start_date`, `end_date`, `status`, `progress`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sprint 1', 'cadfvf', '2026-06-15', '2026-06-16', 'active', 10, '2026-06-15 03:46:58', '2026-06-15 05:40:21'),
(2, 1, 'Sprint 2', NULL, '2026-06-15', '2026-06-17', 'closed', 65, '2026-06-15 06:25:46', '2026-06-15 06:25:46'),
(3, 1, 'sprint 3', 'Sprint 3 goal', '2026-06-16', '2026-06-24', 'active', 20, '2026-06-16 02:09:52', '2026-06-16 05:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `epic_id` bigint(20) UNSIGNED NOT NULL,
  `sprint_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'todo',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `type` enum('feature','bug','ui','backend','test') NOT NULL DEFAULT 'feature',
  `github_link` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `epic_id`, `sprint_id`, `title`, `description`, `image`, `assigned_to`, `status`, `priority`, `type`, `github_link`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'login api bug', 'dsads', 'tasks/eYHIs0KSia3FrDCnIYpmMx6pXYwrduqLoiCUmzyP.jpg', 2, 'drop', 'high', 'ui', NULL, NULL, '2026-06-15 01:06:02', '2026-06-19 05:53:46'),
(2, 1, 1, 2, 'UI login page', 'ahdfd', NULL, 1, 'done', 'medium', 'bug', NULL, NULL, '2026-06-15 01:14:35', '2026-06-19 05:53:52'),
(7, 1, 3, 1, 'product database fix', NULL, NULL, 1, 'done', 'critical', 'bug', NULL, '2026-06-18', '2026-06-17 22:51:44', '2026-06-19 05:54:06'),
(12, 1, 1, 2, 'card page', 'cardd', NULL, 2, 'in_progress', 'medium', 'backend', NULL, '2026-06-18', '2026-06-18 04:47:34', '2026-06-19 05:54:02'),
(16, 1, 1, NULL, 'login testing', NULL, 'tasks/PYzPsXp0WQG4mR0otr1bpQl9btfnRC42eGPkQAQA.jpg', 2, 'review', 'low', 'test', NULL, '2026-06-18', '2026-06-19 01:10:17', '2026-06-19 05:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'krishna', 'krishna@gmail.com', NULL, '$2y$12$Az0wZh57cFWAyH9aj1fyrOd2DtIood9FvGJHX95XqvzYrKDQiAXEi', NULL, '2026-06-15 00:51:16', '2026-06-15 00:51:16', 'project_manager'),
(2, 'Swornim', 'swornim@gmail.com', NULL, '$2y$12$jiTOuuEJkvuPFbg8U3qMeOXClTOils0i9RK/xDzZxALX0LEciQxGW', NULL, '2026-06-15 01:03:07', '2026-06-15 01:03:07', 'employee'),
(3, 'Radha', 'radha@gmail.com', NULL, '$2y$12$P00UZY5DMsyRxeIztCpn/u34zbfLjI.xe2sVBFwQs1MIqUox1f3Di', NULL, '2026-06-15 01:03:50', '2026-06-15 01:03:50', 'project_manager');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `epics`
--
ALTER TABLE `epics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `epics_project_id_foreign` (`project_id`),
  ADD KEY `epics_owner_id_foreign` (`owner_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_created_by_foreign` (`created_by`);

--
-- Indexes for table `project_statuses`
--
ALTER TABLE `project_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_statuses_project_id_foreign` (`project_id`);

--
-- Indexes for table `project_user`
--
ALTER TABLE `project_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_user_project_id_foreign` (`project_id`),
  ADD KEY `project_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sprints`
--
ALTER TABLE `sprints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sprints_project_id_foreign` (`project_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_project_id_foreign` (`project_id`),
  ADD KEY `tasks_epic_id_foreign` (`epic_id`),
  ADD KEY `tasks_sprint_id_foreign` (`sprint_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `epics`
--
ALTER TABLE `epics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_statuses`
--
ALTER TABLE `project_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `project_user`
--
ALTER TABLE `project_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sprints`
--
ALTER TABLE `sprints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `epics`
--
ALTER TABLE `epics`
  ADD CONSTRAINT `epics_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `epics_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_statuses`
--
ALTER TABLE `project_statuses`
  ADD CONSTRAINT `project_statuses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_user`
--
ALTER TABLE `project_user`
  ADD CONSTRAINT `project_user_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sprints`
--
ALTER TABLE `sprints`
  ADD CONSTRAINT `sprints_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_epic_id_foreign` FOREIGN KEY (`epic_id`) REFERENCES `epics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_sprint_id_foreign` FOREIGN KEY (`sprint_id`) REFERENCES `sprints` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
