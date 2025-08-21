-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 05:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simaka`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` varchar(36) NOT NULL,
  `jadwal_id` varchar(36) NOT NULL,
  `guru_id` varchar(36) NOT NULL,
  `mapel_id` varchar(36) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_absen` time NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alfa','Terlambat') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `jadwal_id`, `guru_id`, `mapel_id`, `tanggal`, `jam_absen`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
('25981c0d-0a32-4b1b-ac7d-72dcec1e5d89', '169a44f0-5275-4434-b3dc-777345d8eac8', '52d43591-eaae-46f8-a7dd-464379852a6e', 'fa0e98d0-478b-41b9-8915-e5ebc0eb894c', '2025-09-16', '09:00:00', 'Alfa', NULL, '2025-08-20 10:22:13', '2025-08-20 10:22:13'),
('2a8d4dde-9c20-4509-baff-d65026950a79', '169a44f0-5275-4434-b3dc-777345d8eac8', '52d43591-eaae-46f8-a7dd-464379852a6e', 'fa0e98d0-478b-41b9-8915-e5ebc0eb894c', '2025-08-26', '08:52:00', 'Hadir', NULL, '2025-08-20 08:52:54', '2025-08-20 08:52:54'),
('9e824d25-dd57-4363-b7bc-5de64c6325bc', '169a44f0-5275-4434-b3dc-777345d8eac8', '52d43591-eaae-46f8-a7dd-464379852a6e', 'fa0e98d0-478b-41b9-8915-e5ebc0eb894c', '2025-09-09', '09:14:00', 'Sakit', 'fdj', '2025-08-20 10:18:09', '2025-08-20 10:18:09'),
('a54382cc-6e32-487e-be02-f8c93e179bba', '84ce9909-4200-4a3e-8ffa-70b45255bc23', '52d43591-eaae-46f8-a7dd-464379852a6e', 'e28faa04-8ba9-4713-a10c-65b8f947281a', '2025-08-25', '08:00:00', 'Hadir', NULL, '2025-08-20 08:56:21', '2025-08-20 08:56:21'),
('bae843b9-8e2b-4649-ac96-d6beaca9c491', '169a44f0-5275-4434-b3dc-777345d8eac8', '52d43591-eaae-46f8-a7dd-464379852a6e', 'fa0e98d0-478b-41b9-8915-e5ebc0eb894c', '2025-09-02', '09:12:00', 'Izin', 'kondangan', '2025-08-20 10:12:22', '2025-08-20 10:12:22');

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
-- Table structure for table `gurus`
--

CREATE TABLE `gurus` (
  `id` varchar(36) NOT NULL,
  `jabatan_id` varchar(36) NOT NULL,
  `nik` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gurus`
--

INSERT INTO `gurus` (`id`, `jabatan_id`, `nik`, `nama`, `jenis_kelamin`, `alamat`, `no_hp`, `status_aktif`, `foto`, `created_at`, `updated_at`) VALUES
('52d43591-eaae-46f8-a7dd-464379852a6e', '5d70d7fe-0a73-4b53-948b-b73764be6b69', '246576556', 'Sudarsono s.pd', 'L', 'jl.sjknjk', '0845456453', 1, 'uploads/1755597663_68a44b5f0e44f.webp', '2025-08-19 03:01:03', '2025-08-19 03:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `jabatans`
--

CREATE TABLE `jabatans` (
  `id` varchar(36) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `gapok` int(11) NOT NULL,
  `tunjangan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatans`
--

INSERT INTO `jabatans` (`id`, `jabatan`, `gapok`, `tunjangan`, `created_at`, `updated_at`) VALUES
('5d70d7fe-0a73-4b53-948b-b73764be6b69', 'kepala sekolah', 1500000, '500000', '2025-08-19 02:25:04', '2025-08-19 02:45:51'),
('5e6a27fc-7a5a-44eb-8a09-cdb3231d66ad', 'sekretaris', 1200000, '400000', '2025-08-19 02:47:32', '2025-08-19 02:47:32'),
('89af1163-b083-49ef-abe9-d28fe59fe51e', 'bendahara', 1200000, '400000', '2025-08-19 02:25:56', '2025-08-19 02:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` varchar(36) NOT NULL,
  `guru_id` varchar(36) NOT NULL,
  `mapel_id` varchar(36) NOT NULL,
  `kelas_id` varchar(36) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `guru_id`, `mapel_id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
('169a44f0-5275-4434-b3dc-777345d8eac8', '52d43591-eaae-46f8-a7dd-464379852a6e', 'fa0e98d0-478b-41b9-8915-e5ebc0eb894c', '17216b16-4bf2-4c4e-bedf-ad4ee2a7dade', 'Selasa', '08:30:00', '09:00:00', '2025-08-20 08:49:46', '2025-08-20 08:49:46'),
('84ce9909-4200-4a3e-8ffa-70b45255bc23', '52d43591-eaae-46f8-a7dd-464379852a6e', 'e28faa04-8ba9-4713-a10c-65b8f947281a', '17216b16-4bf2-4c4e-bedf-ad4ee2a7dade', 'Senin', '07:30:00', '08:30:00', '2025-08-20 08:47:48', '2025-08-20 08:47:48');

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
-- Table structure for table `kelass`
--

CREATE TABLE `kelass` (
  `id` varchar(36) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `rombel` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelass`
--

INSERT INTO `kelass` (`id`, `kelas`, `rombel`, `created_at`, `updated_at`) VALUES
('17216b16-4bf2-4c4e-bedf-ad4ee2a7dade', '10', 'A', '2025-08-20 08:39:23', '2025-08-20 08:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajarans`
--

CREATE TABLE `mata_pelajarans` (
  `id` varchar(36) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kode_mapel` varchar(20) NOT NULL,
  `gaji` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_pelajarans`
--

INSERT INTO `mata_pelajarans` (`id`, `nama_mapel`, `kode_mapel`, `gaji`, `created_at`, `updated_at`) VALUES
('e28faa04-8ba9-4713-a10c-65b8f947281a', 'iggris', 'ing132', 8000, '2025-08-19 03:50:20', '2025-08-20 11:28:45'),
('fa0e98d0-478b-41b9-8915-e5ebc0eb894c', 'indo', 'id2424', 10000, '2025-08-19 03:49:22', '2025-08-19 03:49:22');

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
(64, '0001_01_01_000000_create_users_table', 1),
(65, '0001_01_01_000001_create_cache_table', 1),
(66, '0001_01_01_000002_create_jobs_table', 1),
(67, '2025_08_10_094116_create_gurus_table', 1),
(68, '2025_08_10_094125_create_mata_pelajarans_table', 1),
(69, '2025_08_10_094132_create_jadwals_table', 1),
(70, '2025_08_10_094140_create_absensis_table', 1),
(71, '2025_08_10_094148_create_penggajians_table', 1),
(72, '2025_08_18_054456_create_jabatans_table', 1),
(73, '2025_08_18_093158_create_kelass_table', 1);

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
-- Table structure for table `penggajians`
--

CREATE TABLE `penggajians` (
  `id` varchar(36) NOT NULL,
  `guru_id` varchar(36) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `total_jam_mengajar` int(11) NOT NULL DEFAULT 0,
  `total_hadir` int(11) NOT NULL DEFAULT 0,
  `total_izin` int(11) NOT NULL DEFAULT 0,
  `total_sakit` int(11) NOT NULL DEFAULT 0,
  `total_alpha` int(11) NOT NULL DEFAULT 0,
  `total_terlambat` int(11) NOT NULL DEFAULT 0,
  `gaji_per_jam` decimal(15,2) NOT NULL DEFAULT 0.00,
  `potongan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_gaji` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('1su3XqkEEildgIuooXeXNXcXuAiRzI3XIivuNdUQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUZJNk5sTjlUbHlFVDdsWncyUUpkOXplS3NRWGxvZ1BiQnRNa2tacSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYWppIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1755714848);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gurus`
--
ALTER TABLE `gurus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gurus_nik_unique` (`nik`);

--
-- Indexes for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `kelass`
--
ALTER TABLE `kelass`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mata_pelajarans`
--
ALTER TABLE `mata_pelajarans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_pelajarans_kode_mapel_unique` (`kode_mapel`);

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
-- Indexes for table `penggajians`
--
ALTER TABLE `penggajians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
