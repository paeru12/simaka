-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2025 at 04:23 PM
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
  `guru_id` varchar(36) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_absen` time DEFAULT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpha','Terlambat') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `jadwal_id`, `mapel_id`, `guru_id`, `tanggal`, `jam_absen`, `status`, `keterangan`, `foto`, `created_at`, `updated_at`) VALUES
('34157323-a83f-4e85-8c30-a15eef0af979', 'd05218be-a1e9-43b6-9e14-24657e500d58', '72d2e65b-067a-48c0-8e46-5854e0569462', 'a0173faf-74ee-4083-8198-e97dcfe7fe61', '2025-09-21', '18:16:00', 'Izin', 'sakit', 'uploads/bukti/1757848716_68c6a48cd462f.webp', '2025-09-14 11:18:36', '2025-09-14 11:18:36'),
('a3f16994-5133-43a6-9399-4d7a2588de10', '5473c067-3fe2-4a63-b874-4e5ae8a02a0e', '222b8c19-9689-4dc2-b886-792308c8f950', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-09-15', '19:43:53', 'Hadir', NULL, 'uploads/bukti/1757940233_68c80a099a660.webp', '2025-09-15 12:43:53', '2025-09-15 12:43:53'),
('dc3787cd-6869-4ae2-b299-b2243c9929b3', 'd05218be-a1e9-43b6-9e14-24657e500d58', '72d2e65b-067a-48c0-8e46-5854e0569462', 'a0173faf-74ee-4083-8198-e97dcfe7fe61', '2025-09-15', '19:20:04', 'Alpha', 'Tidak hadir, otomatis alpha oleh sistem', 'assets/img/blank.jpg', '2025-09-15 12:20:04', '2025-09-15 12:20:04');

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
  `jk` enum('L','P') NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gurus`
--

INSERT INTO `gurus` (`id`, `jabatan_id`, `nik`, `nama`, `jk`, `no_hp`, `foto`, `created_at`, `updated_at`) VALUES
('3b67a84e-e773-4983-a042-3091e6f6b8e2', 'ab0c3ddc-5594-4080-81b7-63a44b93e3b5', '535763', 'sekre', 'L', '65653', 'uploads/1757945346_68c81e0232fce.webp', '2025-09-14 12:03:50', '2025-09-15 14:11:27'),
('59bdfbd6-f911-4118-b84c-a2a3dbc135b1', '2b344078-2a25-4ee8-b539-763391512335', '46574', 'admin', 'L', '0813566565', 'uploads/1757945452_68c81e6c4c0ab.webp', '2025-09-14 03:30:02', '2025-09-15 14:11:00'),
('6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', 'acbb21cd-2ec6-4a27-ab4f-fa952c4140a5', '3625468567123', 'guru1', 'L', '0813566565', 'uploads/1757945415_68c81e477fdd4.webp', '2025-09-12 15:06:11', '2025-09-15 14:12:20'),
('a0173faf-74ee-4083-8198-e97dcfe7fe61', 'de25a748-ce98-4e41-9aad-d6fb83552a00', '362546856712', 'bendahara', 'L', '0813566565', 'uploads/1757945429_68c81e55e746b.webp', '2025-09-12 14:29:33', '2025-09-15 14:13:02');

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
('2b344078-2a25-4ee8-b539-763391512335', 'admin', 100000, '100000', '2025-09-14 03:27:08', '2025-09-15 05:08:30'),
('6f2a0f4a-e08c-4129-8c2c-c23b4d1ee06e', 'Kepala Sekolah', 1000000, '100000', '2025-08-30 01:19:46', '2025-09-15 05:37:10'),
('ab0c3ddc-5594-4080-81b7-63a44b93e3b5', 'sekretaris', 1000000, '400000', '2025-08-30 03:11:22', '2025-08-30 03:12:05'),
('acbb21cd-2ec6-4a27-ab4f-fa952c4140a5', 'guru', 1000000, '200000', '2025-09-15 13:22:05', '2025-09-15 13:22:05'),
('de25a748-ce98-4e41-9aad-d6fb83552a00', 'Bendahara', 900000, '200000', '2025-08-30 02:56:46', '2025-08-30 02:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` varchar(36) NOT NULL,
  `guru_id` varchar(36) NOT NULL,
  `ruangan_id` varchar(36) NOT NULL,
  `mapel_id` varchar(36) NOT NULL,
  `kelas_id` varchar(36) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `guru_id`, `ruangan_id`, `mapel_id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
('5473c067-3fe2-4a63-b874-4e5ae8a02a0e', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '01c35dde-8f21-442f-90f7-fe4a341daae3', '222b8c19-9689-4dc2-b886-792308c8f950', '1c70bca5-5de3-44c7-b324-23bf7827246a', 'Senin', '18:30:00', '20:00:00', '2025-09-14 02:46:59', '2025-09-15 12:43:16'),
('d05218be-a1e9-43b6-9e14-24657e500d58', 'a0173faf-74ee-4083-8198-e97dcfe7fe61', 'd4efd910-0c23-4263-82a4-09b43d66f831', '72d2e65b-067a-48c0-8e46-5854e0569462', '00e46c4f-c1b4-4d2a-bede-05a4fc5d34e3', 'Senin', '18:44:00', '19:00:00', '2025-09-14 03:44:38', '2025-09-15 11:59:31');

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
('0f301b86-e044-442a-8cee-4461b1618425', 'ips', '2025-09-15 06:14:54', '2025-09-15 06:14:54'),
('4868a62c-eca1-4f7b-a99a-8f1065f7887e', 'tkj', '2025-09-04 09:29:57', '2025-09-04 09:29:57'),
('9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'sija', '2025-09-04 13:10:33', '2025-09-04 13:33:35'),
('e3cbca19-3f85-4d95-b125-f653d7679896', 'ipa', '2025-09-15 06:14:54', '2025-09-15 06:14:54');

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
('00e46c4f-c1b4-4d2a-bede-05a4fc5d34e3', '9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'X', 'b', '2025-09-09 12:05:56', '2025-09-09 12:05:56'),
('1c70bca5-5de3-44c7-b324-23bf7827246a', '4868a62c-eca1-4f7b-a99a-8f1065f7887e', '1', 's', '2025-08-30 01:18:40', '2025-08-30 01:18:40'),
('2c10d3dd-ad2e-4446-b8a3-e116cdbb9563', '9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'x', 'e', '2025-09-15 06:26:57', '2025-09-15 06:26:57'),
('53c03f6e-4f7e-42ec-b953-79ab2fc00434', '4868a62c-eca1-4f7b-a99a-8f1065f7887e', '2', 'a', '2025-08-30 01:19:31', '2025-08-30 01:19:31'),
('575b4336-404c-43f6-8b05-4b1c292d4936', '9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'X', 'c', '2025-09-09 12:05:56', '2025-09-09 12:05:56'),
('6ef91338-9b59-454b-9c23-ceb63c368089', '4868a62c-eca1-4f7b-a99a-8f1065f7887e', '3', 'a', '2025-08-30 04:17:38', '2025-08-30 04:17:38'),
('77c11486-a0b5-4188-b829-4050677a14c1', '9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'x', 'a', '2025-09-03 13:23:12', '2025-09-04 13:10:46'),
('9811c6e9-3b1f-412d-94dd-427e91b5b8a0', '9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'X', 'd', '2025-09-09 12:05:56', '2025-09-09 12:05:56'),
('fd16ad7e-28cc-4490-9742-f3d3d201b273', '9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'x', 'f', '2025-09-15 06:26:57', '2025-09-15 06:26:57');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajarans`
--

CREATE TABLE `mata_pelajarans` (
  `id` varchar(36) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `gaji` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_pelajarans`
--

INSERT INTO `mata_pelajarans` (`id`, `nama_mapel`, `gaji`, `created_at`, `updated_at`) VALUES
('222b8c19-9689-4dc2-b886-792308c8f950', 'jaringan', 8000, '2025-08-30 06:08:18', '2025-08-30 06:08:18'),
('34efa3d6-69f3-4807-a240-528609733f4b', 'bahasa indo', 8000, '2025-09-15 10:58:41', '2025-09-15 10:58:41'),
('72d2e65b-067a-48c0-8e46-5854e0569462', 'desain visual', 8000, '2025-08-30 06:29:09', '2025-08-30 06:29:09'),
('893a4199-7299-4504-a131-edebf50baea4', 'ilustrasi', 8000, '2025-09-02 18:02:24', '2025-09-02 18:02:24'),
('c1891009-1065-41cc-b8e9-f46a86f18066', 'bahasa jawa', 8000, '2025-09-02 18:34:18', '2025-09-02 18:34:18'),
('f34db4d8-b226-4213-be63-762dd8794e43', 'indo', 7000, '2025-08-30 03:34:40', '2025-08-30 03:37:14');

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
(86, '2025_09_04_133653_create_ruangans_table', 3),
(88, '2025_09_09_121331_create_settings_table', 4);

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
('9f4bfdee-1cc3-4d02-beb5-be8659f3d975', '01c35dde-8f21-442f-90f7-fe4a341daae3', 'af0f7169-7cd1-4371-ac08-9cee7f8e2bcd', 1, 'uploads/qr/qrkelas-01c35dde-8f21-442f-90f7-fe4a341daae3.svg', '2025-09-04 07:59:58', '2025-09-04 07:59:58'),
('ff7d77e1-2276-4a02-823f-31c60f2beee2', 'dd8387c4-6e43-45e6-be23-3d0ba0896528', 'bf2e8bf6-ccbe-40b5-a526-d1fc9ac18fc7', 1, 'uploads/qr/qrkelas-dd8387c4-6e43-45e6-be23-3d0ba0896528.svg', '2025-09-14 02:55:03', '2025-09-14 02:55:03');

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
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` varchar(36) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
('a174d617-0c8d-4b9d-a7fe-9c65b52ed594', 'logo', 'uploads/1757942776_68c813f8124c1.webp', '2025-09-09 09:04:15', '2025-09-15 13:26:16'),
('b09c7446-9035-4ab4-b873-68114129bd50', 'nama', 'SIMAKAs', '2025-09-09 08:57:45', '2025-09-09 09:56:58'),
('e138d7db-9010-4060-ad4d-7ef82095be0b', 'kop_surat', 'uploads/1757412080_68bffaf0075be.webp', '2025-09-09 09:05:58', '2025-09-09 10:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `guru_id` varchar(36) NOT NULL,
  `jabatan_id` varchar(36) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `guru_id`, `jabatan_id`, `email`, `password`, `status`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
('34af08e3-b8e3-481a-936e-6dfe46f6fa5d', '59bdfbd6-f911-4118-b84c-a2a3dbc135b1', '2b344078-2a25-4ee8-b539-763391512335', 'admin@gmail.com', '$2y$12$BZhT90MvYZlFn638YV4leuazupCjcwTMeBaNVeGATE.MTdbMEcZ..', 1, NULL, NULL, '2025-09-14 03:30:03', '2025-09-14 03:30:03'),
('56b7df91-07f6-4288-aea3-4834d8967823', 'a0173faf-74ee-4083-8198-e97dcfe7fe61', 'de25a748-ce98-4e41-9aad-d6fb83552a00', 'bend@gmail.com', '$2y$12$H8AC7KcivTEW6V/vc42IwOZjv86R4xlqqWteR2WHIc1Hd5oo2BsDi', 1, NULL, NULL, '2025-09-12 14:29:34', '2025-09-15 14:13:11'),
('9a05b6dc-472a-455c-93e0-cfe6146521eb', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', 'acbb21cd-2ec6-4a27-ab4f-fa952c4140a5', 'guru1@gmail.com', '$2y$12$ArR7W5OynUZQRxbbeRVtsukw3hokavvrd0mPk2IjN..BthBqeK8EW', 1, NULL, NULL, '2025-09-12 15:06:12', '2025-09-15 14:12:30'),
('bee95759-401c-4971-97bb-b349b0159460', '3b67a84e-e773-4983-a042-3091e6f6b8e2', 'ab0c3ddc-5594-4080-81b7-63a44b93e3b5', 'sekre@gmail.com', '$2y$12$jCO/EAlfL2bheHk4jiG2dO/gTjjGedLnbKm7RW/l3K4i8sBXSRv.u', 1, NULL, NULL, '2025-09-14 12:03:51', '2025-09-15 14:11:38');

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
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
