-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 04:19 PM
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
-- Database: `simaka`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` varchar(36) NOT NULL,
  `jadwal_id` varchar(36) NOT NULL,
  `mapel_id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_absen` time NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpha','Terlambat') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `foto` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `jadwal_id`, `mapel_id`, `user_id`, `tanggal`, `jam_absen`, `status`, `keterangan`, `foto`, `created_at`, `updated_at`) VALUES
('bdbe3c8f-00cf-4438-954d-a3eb260fb1e5', '99d07fce-11d7-41dd-8263-6ef979abba43', 'f34db4d8-b226-4213-be63-762dd8794e43', '1cb92c7f-4815-47f4-b8ee-c8b9d3d63c1b', '2025-09-04', '21:07:00', 'Hadir', NULL, 'uploads/bukti/1756994820_68b99d04e2e6a.webp', '2025-09-04 14:07:00', '2025-09-04 14:07:00');

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
('6f2a0f4a-e08c-4129-8c2c-c23b4d1ee06e', 'Kepala Sekolah', 1000000, '100000', '2025-08-30 01:19:46', '2025-08-30 02:56:24'),
('ab0c3ddc-5594-4080-81b7-63a44b93e3b5', 'sekretaris', 1000000, '400000', '2025-08-30 03:11:22', '2025-08-30 03:12:05'),
('de25a748-ce98-4e41-9aad-d6fb83552a00', 'Bendahara', 900000, '200000', '2025-08-30 02:56:46', '2025-08-30 02:56:46'),
('f65ef857-a886-4c7d-9ea5-47693ce7a9f5', 'Guru', 500000, '200000', '2025-08-30 01:20:39', '2025-08-30 02:46:06');

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `ruangan_id` varchar(36) NOT NULL,
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

INSERT INTO `jadwals` (`id`, `user_id`, `ruangan_id`, `mapel_id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
('0d8b50ee-c017-4052-be5b-11598f1efd6a', 'ad75c065-d5f2-4b7a-a2a8-fa75156456fd', 'd4efd910-0c23-4263-82a4-09b43d66f831', '222b8c19-9689-4dc2-b886-792308c8f950', '53c03f6e-4f7e-42ec-b953-79ab2fc00434', 'Senin', '08:08:00', '10:08:00', '2025-08-30 06:08:52', '2025-09-04 08:43:06'),
('166e789b-346d-4cde-8eaa-6985da12047f', '1cb92c7f-4815-47f4-b8ee-c8b9d3d63c1b', '01c35dde-8f21-442f-90f7-fe4a341daae3', 'c1891009-1065-41cc-b8e9-f46a86f18066', '1c70bca5-5de3-44c7-b324-23bf7827246a', 'Rabu', '01:00:00', '03:30:00', '2025-09-02 18:35:01', '2025-09-02 18:35:01'),
('707e0e84-ac5e-41b3-9f87-ea953c841c17', '1cb92c7f-4815-47f4-b8ee-c8b9d3d63c1b', 'd4efd910-0c23-4263-82a4-09b43d66f831', '72d2e65b-067a-48c0-8e46-5854e0569462', '1c70bca5-5de3-44c7-b324-23bf7827246a', 'Kamis', '16:00:00', '17:00:00', '2025-09-04 09:02:10', '2025-09-04 09:02:28'),
('99d07fce-11d7-41dd-8263-6ef979abba43', '1cb92c7f-4815-47f4-b8ee-c8b9d3d63c1b', 'd4efd910-0c23-4263-82a4-09b43d66f831', 'f34db4d8-b226-4213-be63-762dd8794e43', '53c03f6e-4f7e-42ec-b953-79ab2fc00434', 'Kamis', '21:00:00', '22:00:00', '2025-09-04 14:02:34', '2025-09-04 14:02:34'),
('df257ede-121b-4368-8ea3-2e60e4ea1a40', '1cb92c7f-4815-47f4-b8ee-c8b9d3d63c1b', '01c35dde-8f21-442f-90f7-fe4a341daae3', '222b8c19-9689-4dc2-b886-792308c8f950', '1c70bca5-5de3-44c7-b324-23bf7827246a', 'Rabu', '15:00:00', '17:00:00', '2025-09-03 08:17:22', '2025-09-03 08:17:22'),
('fe9276eb-4b40-4b75-a73d-39315dcfd6c6', 'ad75c065-d5f2-4b7a-a2a8-fa75156456fd', '01c35dde-8f21-442f-90f7-fe4a341daae3', '893a4199-7299-4504-a131-edebf50baea4', '1c70bca5-5de3-44c7-b324-23bf7827246a', 'Rabu', '01:00:00', '04:00:00', '2025-09-02 18:03:03', '2025-09-02 18:03:03'),
('ffed8819-fbd4-40be-b2c8-030d940cb1eb', 'ad75c065-d5f2-4b7a-a2a8-fa75156456fd', '01c35dde-8f21-442f-90f7-fe4a341daae3', '72d2e65b-067a-48c0-8e46-5854e0569462', '6ef91338-9b59-454b-9c23-ceb63c368089', 'Senin', '13:00:00', '14:30:00', '2025-08-30 06:29:56', '2025-09-02 18:30:43');

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
-- Table structure for table `jurusans`
--

CREATE TABLE `jurusans` (
  `id` varchar(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurusans`
--

INSERT INTO `jurusans` (`id`, `nama`, `created_at`, `updated_at`) VALUES
('4868a62c-eca1-4f7b-a99a-8f1065f7887e', 'tkj', '2025-09-04 09:29:57', '2025-09-04 09:29:57'),
('9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'sija', '2025-09-04 13:10:33', '2025-09-04 13:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `kelass`
--

CREATE TABLE `kelass` (
  `id` varchar(36) NOT NULL,
  `jurusan_id` varchar(36) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `rombel` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelass`
--

INSERT INTO `kelass` (`id`, `jurusan_id`, `kelas`, `rombel`, `created_at`, `updated_at`) VALUES
('1c70bca5-5de3-44c7-b324-23bf7827246a', '4868a62c-eca1-4f7b-a99a-8f1065f7887e', '1', 's', '2025-08-30 01:18:40', '2025-08-30 01:18:40'),
('53c03f6e-4f7e-42ec-b953-79ab2fc00434', '4868a62c-eca1-4f7b-a99a-8f1065f7887e', '2', 'a', '2025-08-30 01:19:31', '2025-08-30 01:19:31'),
('6ef91338-9b59-454b-9c23-ceb63c368089', '4868a62c-eca1-4f7b-a99a-8f1065f7887e', '3', 'a', '2025-08-30 04:17:38', '2025-08-30 04:17:38'),
('77c11486-a0b5-4188-b829-4050677a14c1', '9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'x', 'a', '2025-09-03 13:23:12', '2025-09-04 13:10:46');

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
('222b8c19-9689-4dc2-b886-792308c8f950', 'jaringan', 'jr-11', 8000, '2025-08-30 06:08:18', '2025-08-30 06:08:18'),
('72d2e65b-067a-48c0-8e46-5854e0569462', 'desain visual', 'dkv-1', 8000, '2025-08-30 06:29:09', '2025-08-30 06:29:09'),
('893a4199-7299-4504-a131-edebf50baea4', 'ilustrasi', 'ils123', 8000, '2025-09-02 18:02:24', '2025-09-02 18:02:24'),
('c1891009-1065-41cc-b8e9-f46a86f18066', 'bahasa jawa', 'bjw123', 8000, '2025-09-02 18:34:18', '2025-09-02 18:34:18'),
('f34db4d8-b226-4213-be63-762dd8794e43', 'indo', 'idbs', 7000, '2025-08-30 03:34:40', '2025-08-30 03:37:14');

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
(74, '0001_01_01_000000_create_users_table', 1),
(75, '0001_01_01_000001_create_cache_table', 1),
(76, '0001_01_01_000002_create_jobs_table', 1),
(77, '2025_08_10_094116_create_gurus_table', 1),
(78, '2025_08_10_094125_create_mata_pelajarans_table', 1),
(79, '2025_08_10_094132_create_jadwals_table', 1),
(80, '2025_08_10_094140_create_absensis_table', 1),
(81, '2025_08_10_094148_create_penggajians_table', 1),
(82, '2025_08_18_054456_create_jabatans_table', 1),
(83, '2025_08_18_093158_create_kelass_table', 1),
(84, '2025_09_02_110104_create_qr_kelas_table', 2),
(85, '2025_09_04_133642_create_jurusans_table', 3),
(86, '2025_09_04_133653_create_ruangans_table', 3);

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
-- Table structure for table `qr_kelas`
--

CREATE TABLE `qr_kelas` (
  `id` varchar(36) NOT NULL,
  `ruangan_id` varchar(36) NOT NULL,
  `token` varchar(100) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qr_kelas`
--

INSERT INTO `qr_kelas` (`id`, `ruangan_id`, `token`, `aktif`, `file`, `created_at`, `updated_at`) VALUES
('59dbcc0f-d7c6-4832-a61c-fb282ce01074', 'd4efd910-0c23-4263-82a4-09b43d66f831', '52daeda9-9ddf-4715-a14c-93598f6094ba', 1, 'uploads/qr/qrkelas-d4efd910-0c23-4263-82a4-09b43d66f831.svg', '2025-09-04 07:53:52', '2025-09-04 07:53:52'),
('9f4bfdee-1cc3-4d02-beb5-be8659f3d975', '01c35dde-8f21-442f-90f7-fe4a341daae3', 'af0f7169-7cd1-4371-ac08-9cee7f8e2bcd', 1, 'uploads/qr/qrkelas-01c35dde-8f21-442f-90f7-fe4a341daae3.svg', '2025-09-04 07:59:58', '2025-09-04 07:59:58');

-- --------------------------------------------------------

--
-- Table structure for table `ruangans`
--

CREATE TABLE `ruangans` (
  `id` varchar(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangans`
--

INSERT INTO `ruangans` (`id`, `nama`, `created_at`, `updated_at`) VALUES
('01c35dde-8f21-442f-90f7-fe4a341daae3', 'x tkj 1', '2025-09-04 07:59:50', '2025-09-04 07:59:50'),
('d4efd910-0c23-4263-82a4-09b43d66f831', 'lab tkj', '2025-09-04 07:42:29', '2025-09-04 07:42:29');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `jabatan_id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `jk` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `jabatan_id`, `name`, `no_hp`, `jk`, `email`, `password`, `foto`, `role`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
('1cb92c7f-4815-47f4-b8ee-c8b9d3d63c1b', 'de25a748-ce98-4e41-9aad-d6fb83552a00', 'susi pujiastuti s.pd', '064733234', 'Perempuan', 'admin@admin.com', '$2y$12$4C6vJRA2j0JWLjFw9/y/zeWJhksHGZC4/w.RBEdzQpLM8xR20arde', 'uploads/1756904811_68b83d6b30e7d.webp', 'guru', NULL, NULL, '2025-08-30 01:33:50', '2025-09-03 13:06:51'),
('2bdb3f3e-cf8e-443f-a37d-e8d597dbfa20', 'f65ef857-a886-4c7d-9ea5-47693ce7a9f5', 'admin', '08345636', 'Laki-laki', 'admin@gmail.com', '$2y$12$s5zHm4FgViyDaEcwS3YhR.eYHm5HItqBhPBvmZF1W8jjm48e.BAsa', 'uploads/1756576657_68b33b91dd6e5.webp', 'admin', NULL, NULL, '2025-08-30 10:57:38', '2025-09-04 14:18:06'),
('ad75c065-d5f2-4b7a-a2a8-fa75156456fd', 'f65ef857-a886-4c7d-9ea5-47693ce7a9f5', 'saya', '078723847', 'Laki-laki', 'saya@gmail.com', '$2y$12$4rpSCKdsRyJuMPZNcUmC.OzysleWD2Zh02dGGYg31aHvINYn8kbbS', 'uploads/1756559268_68b2f7a4c557c.webp', 'guru', NULL, NULL, '2025-08-30 06:07:49', '2025-08-30 06:07:49'),
('f29e5ebb-a891-433e-97f4-ec9861e86110', '6f2a0f4a-e08c-4129-8c2c-c23b4d1ee06e', 'ahmad s.pd', '0813566565', 'Laki-laki', 'ahmad@gmail.com', '$2y$12$u0oz362TCp4RYq8zq2BaBeFsTS7cdwULGyqLfN7zaJ05p/idmVB2S', 'uploads/1756883254_68b7e936c8f9b.webp', 'guru', NULL, NULL, '2025-09-03 07:07:37', '2025-09-03 07:07:56');

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
-- Indexes for table `jurusans`
--
ALTER TABLE `jurusans`
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
-- Indexes for table `qr_kelas`
--
ALTER TABLE `qr_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_kelas_token_unique` (`token`);

--
-- Indexes for table `ruangans`
--
ALTER TABLE `ruangans`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
