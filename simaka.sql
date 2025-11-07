-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Nov 2025 pada 11.18
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `absensis`
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
-- Dumping data untuk tabel `absensis`
--

INSERT INTO `absensis` (`id`, `jadwal_id`, `mapel_id`, `guru_id`, `tanggal`, `jam_absen`, `status`, `keterangan`, `foto`, `created_at`, `updated_at`) VALUES
('19f0d9b5-8d7a-47a9-a9e3-8c6b393131d7', '5473c067-3fe2-4a63-b874-4e5ae8a02a0e', '222b8c19-9689-4dc2-b886-792308c8f950', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-09-27', '13:30:32', 'Hadir', NULL, 'uploads/bukti/1758954632_68d78488cdf06.webp', '2025-09-27 06:30:32', '2025-09-27 06:30:32'),
('39c9b8ed-233a-42d1-bed2-3ab729829fa8', 'd05218be-a1e9-43b6-9e14-24657e500d58', '72d2e65b-067a-48c0-8e46-5854e0569462', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-09-27', '16:44:23', 'Hadir', NULL, 'uploads/bukti/1758966263_68d7b1f7579f0.webp', '2025-09-27 09:44:23', '2025-09-27 09:44:23'),
('4c93bfc7-6dab-462a-9063-8f92da9a5344', '5473c067-3fe2-4a63-b874-4e5ae8a02a0e', '222b8c19-9689-4dc2-b886-792308c8f950', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-10-01', '15:53:09', 'Hadir', NULL, 'uploads/bukti/1759308795_68dcebfbeacb2.webp', '2025-10-01 08:53:19', '2025-10-01 08:53:19'),
('51a729d4-3eda-453f-b474-f03c07fadcda', 'd05218be-a1e9-43b6-9e14-24657e500d58', '72d2e65b-067a-48c0-8e46-5854e0569462', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-10-05', '11:39:59', 'Hadir', NULL, 'uploads/bukti/1759639204_68e1f6a4ceb80.webp', '2025-10-05 04:40:05', '2025-10-05 04:40:05'),
('53b6f37b-2e24-4f71-a933-f5f0ff0a29e1', 'd05218be-a1e9-43b6-9e14-24657e500d58', '72d2e65b-067a-48c0-8e46-5854e0569462', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-10-19', '14:40:02', 'Alpha', 'Tidak hadir, otomatis alpha oleh sistem', 'assets/img/blank.jpg', '2025-10-19 07:40:06', '2025-10-19 07:40:06'),
('5c4073ab-2feb-4fd6-8dc8-cac44eceb05b', 'd05218be-a1e9-43b6-9e14-24657e500d58', '72d2e65b-067a-48c0-8e46-5854e0569462', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-11-02', '14:20:03', 'Alpha', 'Tidak hadir, otomatis alpha oleh sistem', 'assets/img/blank.jpg', '2025-11-02 07:20:06', '2025-11-02 07:20:06'),
('64053c55-4d3e-41ec-85e9-bf51279fc9aa', '5473c067-3fe2-4a63-b874-4e5ae8a02a0e', '222b8c19-9689-4dc2-b886-792308c8f950', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-10-22', '21:40:01', 'Alpha', 'Tidak hadir, otomatis alpha oleh sistem', 'assets/img/blank.jpg', '2025-10-22 14:40:05', '2025-10-22 14:40:05'),
('d6bb5bc6-6fe5-4750-8927-39bcc123fe18', '5473c067-3fe2-4a63-b874-4e5ae8a02a0e', '222b8c19-9689-4dc2-b886-792308c8f950', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-10-08', '14:10:01', 'Alpha', 'Tidak hadir, otomatis alpha oleh sistem', 'assets/img/blank.jpg', '2025-10-08 07:10:01', '2025-10-08 07:10:01'),
('fc4e5984-337f-4e94-a654-6057c4a6450c', 'd05218be-a1e9-43b6-9e14-24657e500d58', '72d2e65b-067a-48c0-8e46-5854e0569462', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-10-26', '15:00:02', 'Alpha', 'Tidak hadir, otomatis alpha oleh sistem', 'assets/img/blank.jpg', '2025-10-26 08:00:05', '2025-10-26 08:00:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_harians`
--

CREATE TABLE `absensi_harians` (
  `id` varchar(36) NOT NULL,
  `guru_id` varchar(36) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_datang` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` enum('Hadir','Terlambat','Alpha','Izin','Sakit') NOT NULL DEFAULT 'Hadir',
  `foto` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `absensi_harians`
--

INSERT INTO `absensi_harians` (`id`, `guru_id`, `tanggal`, `jam_datang`, `jam_pulang`, `status`, `foto`, `lokasi`, `keterangan`, `created_at`, `updated_at`) VALUES
('08661a0a-66d1-493a-b85a-218f8195d6f0', '58a4a9c8-6470-4eac-832a-21655edc049a', '2025-11-02', '14:50:47', NULL, 'Hadir', 'uploads/absensi_harian/1762069847_69070d57e918c.jpg', '-7.938133,112.626366', NULL, '2025-11-02 07:50:48', '2025-11-02 07:50:48'),
('2cf9ff85-1e6f-4ee8-ae6b-c6539319a880', '58a4a9c8-6470-4eac-832a-21655edc049a', '2025-11-04', NULL, NULL, 'Izin', 'uploads/absensi_harian/1762162306_6908768235435.webp', NULL, 'keluar kota', '2025-11-03 09:31:46', '2025-11-03 09:31:46'),
('8253df91-93aa-4f81-bac8-1489d2052e99', '59bdfbd6-f911-4118-b84c-a2a3dbc135b1', '2025-10-04', '16:13:32', '16:18:37', 'Hadir', 'uploads/absensi_harian/1759569212_68e0e53cbe1e9.jpg', '-8.08938380181674,112.58669050308528', NULL, '2025-10-04 09:13:32', '2025-10-04 09:18:37'),
('baf12a37-7c7b-4441-8e83-c398ed10b760', '59bdfbd6-f911-4118-b84c-a2a3dbc135b1', '2025-10-05', '11:30:40', '20:56:57', 'Hadir', 'uploads/absensi_harian/1759638640_68e1f47046491.jpg', '-8.025602,112.524475', NULL, '2025-10-05 04:30:40', '2025-10-05 13:56:57'),
('f0f30bea-3681-4780-88c2-feb90619c0b6', '58a4a9c8-6470-4eac-832a-21655edc049a', '2025-11-03', '16:07:28', NULL, 'Hadir', 'uploads/absensi_harian/1762160848_690870d0de410.jpg', '-7.9382261,112.6264719', NULL, '2025-11-03 09:07:31', '2025-11-03 09:07:31'),
('f3269d06-effb-4c93-8a3d-f525b1739731', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '2025-10-10', '12:55:44', NULL, 'Hadir', 'uploads/absensi_harian/1760075744_68e89fe0a2c4b.jpg', '-8.016499,112.625801', NULL, '2025-10-10 05:55:47', '2025-10-10 05:55:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `gurus`
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
-- Dumping data untuk tabel `gurus`
--

INSERT INTO `gurus` (`id`, `jabatan_id`, `nik`, `nama`, `jk`, `no_hp`, `foto`, `created_at`, `updated_at`) VALUES
('3b67a84e-e773-4983-a042-3091e6f6b8e2', 'ab0c3ddc-5594-4080-81b7-63a44b93e3b5', '535763', 'sekre', 'L', '65653', 'uploads/1757945346_68c81e0232fce.webp', '2025-09-14 12:03:50', '2025-09-15 14:11:27'),
('58a4a9c8-6470-4eac-832a-21655edc049a', 'a1d65e0c-d012-4af8-8250-4aa5551b33de', '44345264546', 'pito', 'L', '08232553552', 'uploads/1762069502_69070bfe04e08.webp', '2025-11-02 07:45:04', '2025-11-02 07:45:04'),
('59bdfbd6-f911-4118-b84c-a2a3dbc135b1', '2b344078-2a25-4ee8-b539-763391512335', '46574', 'admin', 'L', '0813566565', 'uploads/1757945452_68c81e6c4c0ab.webp', '2025-09-14 03:30:02', '2025-09-15 14:11:00'),
('6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', 'acbb21cd-2ec6-4a27-ab4f-fa952c4140a5', '3625468567123', 'Adriyani S.Pd', 'P', '0813566565', 'uploads/1758965716_68d7afd419838.webp', '2025-09-12 15:06:11', '2025-09-27 09:35:16'),
('a0173faf-74ee-4083-8198-e97dcfe7fe61', 'de25a748-ce98-4e41-9aad-d6fb83552a00', '362546856712', 'bendahara', 'L', '0813566565', 'uploads/1757945429_68c81e55e746b.webp', '2025-09-12 14:29:33', '2025-09-15 14:13:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatans`
--

CREATE TABLE `jabatans` (
  `id` varchar(36) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `nominal_gaji` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatans`
--

INSERT INTO `jabatans` (`id`, `jabatan`, `nominal_gaji`, `created_at`, `updated_at`) VALUES
('2b344078-2a25-4ee8-b539-763391512335', 'admin', 10000, '2025-09-14 03:27:08', '2025-10-22 15:11:03'),
('6f2a0f4a-e08c-4129-8c2c-c23b4d1ee06e', 'Kepala Sekolah', 15000, '2025-08-30 01:19:46', '2025-10-22 14:59:09'),
('a1d65e0c-d012-4af8-8250-4aa5551b33de', 'karyawan', 10000, '2025-10-22 14:57:43', '2025-10-22 15:03:51'),
('ab0c3ddc-5594-4080-81b7-63a44b93e3b5', 'sekretaris', 14000, '2025-08-30 03:11:22', '2025-10-22 14:59:49'),
('acbb21cd-2ec6-4a27-ab4f-fa952c4140a5', 'guru', 13000, '2025-09-15 13:22:05', '2025-10-22 15:03:39'),
('de25a748-ce98-4e41-9aad-d6fb83552a00', 'Bendahara', 14000, '2025-08-30 02:56:46', '2025-10-22 14:59:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwals`
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
  `total_jam` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwals`
--

INSERT INTO `jadwals` (`id`, `guru_id`, `ruangan_id`, `mapel_id`, `kelas_id`, `hari`, `jam_mulai`, `jam_selesai`, `total_jam`, `created_at`, `updated_at`) VALUES
('5473c067-3fe2-4a63-b874-4e5ae8a02a0e', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '01c35dde-8f21-442f-90f7-fe4a341daae3', '222b8c19-9689-4dc2-b886-792308c8f950', '1c70bca5-5de3-44c7-b324-23bf7827246a', 'Rabu', '13:00:00', '14:00:00', '2', '2025-09-14 02:46:59', '2025-10-08 07:09:59'),
('d05218be-a1e9-43b6-9e14-24657e500d58', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', '01c35dde-8f21-442f-90f7-fe4a341daae3', '72d2e65b-067a-48c0-8e46-5854e0569462', '00e46c4f-c1b4-4d2a-bede-05a4fc5d34e3', 'Minggu', '11:30:00', '12:00:00', '1', '2025-09-14 03:44:38', '2025-10-05 04:33:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `jurusans`
--

CREATE TABLE `jurusans` (
  `id` varchar(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jurusans`
--

INSERT INTO `jurusans` (`id`, `nama`, `created_at`, `updated_at`) VALUES
('0f301b86-e044-442a-8cee-4461b1618425', 'ips', '2025-09-15 06:14:54', '2025-09-15 06:14:54'),
('4868a62c-eca1-4f7b-a99a-8f1065f7887e', 'tkj', '2025-09-04 09:29:57', '2025-09-04 09:29:57'),
('9f6aa7e2-18e4-4642-9e28-a41b04bf93a4', 'sija', '2025-09-04 13:10:33', '2025-09-04 13:33:35'),
('e3cbca19-3f85-4d95-b125-f653d7679896', 'ipa', '2025-09-15 06:14:54', '2025-09-15 06:14:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelass`
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
-- Dumping data untuk tabel `kelass`
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
-- Struktur dari tabel `mata_pelajarans`
--

CREATE TABLE `mata_pelajarans` (
  `id` varchar(36) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mata_pelajarans`
--

INSERT INTO `mata_pelajarans` (`id`, `nama_mapel`, `created_at`, `updated_at`) VALUES
('222b8c19-9689-4dc2-b886-792308c8f950', 'jaringan', '2025-08-30 06:08:18', '2025-08-30 06:08:18'),
('34efa3d6-69f3-4807-a240-528609733f4b', 'bahasa indos', '2025-09-15 10:58:41', '2025-10-03 08:07:08'),
('72d2e65b-067a-48c0-8e46-5854e0569462', 'desain visual', '2025-08-30 06:29:09', '2025-08-30 06:29:09'),
('893a4199-7299-4504-a131-edebf50baea4', 'ilustrasi', '2025-09-02 18:02:24', '2025-09-02 18:02:24'),
('c1891009-1065-41cc-b8e9-f46a86f18066', 'bahasa jawa', '2025-09-02 18:34:18', '2025-09-02 18:34:18'),
('f34db4d8-b226-4213-be63-762dd8794e43', 'indo', '2025-08-30 03:34:40', '2025-08-30 03:37:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
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
(88, '2025_09_09_121331_create_settings_table', 4),
(92, '2025_10_03_225832_create_qr_guru_table', 5),
(93, '2025_10_03_234250_create_absensi_harians_table', 5),
(94, '2025_11_02_025657_create_potongans_table', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `potongans`
--

CREATE TABLE `potongans` (
  `id` varchar(36) NOT NULL,
  `nama_potongan` varchar(100) NOT NULL,
  `jumlah_potongan` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `potongans`
--

INSERT INTO `potongans` (`id`, `nama_potongan`, `jumlah_potongan`, `keterangan`, `created_at`, `updated_at`) VALUES
('93424fa9-0f9a-4cf5-ac9f-86b25e3b43d1', 'bpjs', 15000, NULL, '2025-11-01 20:24:41', '2025-11-01 20:24:41'),
('b905ad12-c23d-41c5-8006-d806a3ec75e8', 'asuransi', 50000, 'asuransi jiwa', '2025-11-01 20:25:15', '2025-11-01 20:25:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `qr_guru`
--

CREATE TABLE `qr_guru` (
  `id` varchar(36) NOT NULL,
  `guru_id` varchar(36) NOT NULL,
  `token` varchar(100) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `qr_guru`
--

INSERT INTO `qr_guru` (`id`, `guru_id`, `token`, `aktif`, `file`, `created_at`, `updated_at`) VALUES
('3fe39d12-28eb-4a1b-9eb6-59ea7c8fba4f', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', 'd249c6d0-e991-40f4-a894-224c62556c8e', 1, 'uploads/qr/qrguru-Adriyani S.Pd.svg', '2025-10-04 08:59:01', '2025-10-04 08:59:01'),
('5fece3af-404b-44fd-aecb-85bfb32d4df9', '59bdfbd6-f911-4118-b84c-a2a3dbc135b1', 'aa6aecde-16eb-480b-8f0d-91e3ef5ef2af', 1, 'uploads/qr/qrguru-admin.svg', '2025-10-04 08:07:41', '2025-10-04 08:07:41'),
('9c4d5c76-2069-4b68-999e-8c6ae02bd87f', '3b67a84e-e773-4983-a042-3091e6f6b8e2', 'fc17bb30-f594-43a2-9a6a-f0d9be55b68a', 1, 'uploads/qr/qrguru-sekre.svg', '2025-10-04 08:05:17', '2025-10-04 08:05:17'),
('b528fc0c-56de-45cc-86bb-5485a019ad72', '58a4a9c8-6470-4eac-832a-21655edc049a', '95d2092d-595f-4efe-86f9-0768ef512f60', 1, 'uploads/qr/qrguru-pito.svg', '2025-11-02 07:45:18', '2025-11-02 07:45:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `qr_kelas`
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
-- Dumping data untuk tabel `qr_kelas`
--

INSERT INTO `qr_kelas` (`id`, `ruangan_id`, `token`, `aktif`, `file`, `created_at`, `updated_at`) VALUES
('5496a375-41ef-4afc-955a-b31f64f908ac', '0cb18345-eadc-4559-8c70-fc436ca3200b', 'e97408d6-a919-459d-8f0e-467f9c5f3a6c', 1, 'uploads/qr/qrruangan-fhf.svg', '2025-10-01 08:58:21', '2025-10-01 08:58:21'),
('59dbcc0f-d7c6-4832-a61c-fb282ce01074', 'd4efd910-0c23-4263-82a4-09b43d66f831', '52daeda9-9ddf-4715-a14c-93598f6094ba', 1, 'uploads/qr/qrkelas-d4efd910-0c23-4263-82a4-09b43d66f831.svg', '2025-09-04 07:53:52', '2025-09-04 07:53:52'),
('9f4bfdee-1cc3-4d02-beb5-be8659f3d975', '01c35dde-8f21-442f-90f7-fe4a341daae3', 'af0f7169-7cd1-4371-ac08-9cee7f8e2bcd', 1, 'uploads/qr/qrkelas-01c35dde-8f21-442f-90f7-fe4a341daae3.svg', '2025-09-04 07:59:58', '2025-09-04 07:59:58'),
('ff7d77e1-2276-4a02-823f-31c60f2beee2', 'dd8387c4-6e43-45e6-be23-3d0ba0896528', 'bf2e8bf6-ccbe-40b5-a526-d1fc9ac18fc7', 1, 'uploads/qr/qrkelas-dd8387c4-6e43-45e6-be23-3d0ba0896528.svg', '2025-09-14 02:55:03', '2025-09-14 02:55:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangans`
--

CREATE TABLE `ruangans` (
  `id` varchar(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ruangans`
--

INSERT INTO `ruangans` (`id`, `nama`, `created_at`, `updated_at`) VALUES
('01c35dde-8f21-442f-90f7-fe4a341daae3', 'x tkj 1', '2025-09-04 07:59:50', '2025-09-04 07:59:50'),
('0cb18345-eadc-4559-8c70-fc436ca3200b', 'fhf', '2025-10-01 08:58:14', '2025-10-01 08:58:14'),
('d4efd910-0c23-4263-82a4-09b43d66f831', 'lab tkj', '2025-09-04 07:42:29', '2025-09-04 07:42:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` varchar(36) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
('19c5327c-c293-4d21-bfc9-8ec1abdd7ace', 'minggu', '4', '2025-10-19 08:18:56', '2025-10-19 08:29:06'),
('1cace661-089b-42bd-99c4-e1005959c742', 'jp', '40', '2025-11-03 09:36:16', '2025-11-03 09:36:16'),
('8cbcd705-a082-4504-98ec-8476b6200180', 'gaji_mengajar', '12500', '2025-10-08 05:57:22', '2025-10-22 15:36:09'),
('a174d617-0c8d-4b9d-a7fe-9c65b52ed594', 'logo', 'uploads/1760862676_68f4a1d4a1119.webp', '2025-09-09 09:04:15', '2025-10-19 08:31:16'),
('b09c7446-9035-4ab4-b873-68114129bd50', 'nama', 'SIMAKA', '2025-09-09 08:57:45', '2025-10-19 08:30:55'),
('e138d7db-9010-4060-ad4d-7ef82095be0b', 'kop_surat', 'uploads/1757412080_68bffaf0075be.webp', '2025-09-09 09:05:58', '2025-09-09 10:01:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `guru_id`, `jabatan_id`, `email`, `password`, `status`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
('02d544a9-9453-409c-9ff3-9395dc6b0daf', '58a4a9c8-6470-4eac-832a-21655edc049a', 'a1d65e0c-d012-4af8-8250-4aa5551b33de', 'pito@gmail.com', '$2y$12$05G6pYUB57mJ7RrAw9QbRurvWibzsF2gp11nBBVtBQ6QKIteeZqTO', 1, NULL, NULL, '2025-11-02 07:45:04', '2025-11-02 07:45:04'),
('34af08e3-b8e3-481a-936e-6dfe46f6fa5d', '59bdfbd6-f911-4118-b84c-a2a3dbc135b1', '2b344078-2a25-4ee8-b539-763391512335', 'admin@gmail.com', '$2y$12$BZhT90MvYZlFn638YV4leuazupCjcwTMeBaNVeGATE.MTdbMEcZ..', 1, NULL, NULL, '2025-09-14 03:30:03', '2025-09-14 03:30:03'),
('56b7df91-07f6-4288-aea3-4834d8967823', 'a0173faf-74ee-4083-8198-e97dcfe7fe61', 'de25a748-ce98-4e41-9aad-d6fb83552a00', 'bend@gmail.com', '$2y$12$H8AC7KcivTEW6V/vc42IwOZjv86R4xlqqWteR2WHIc1Hd5oo2BsDi', 1, NULL, NULL, '2025-09-12 14:29:34', '2025-09-15 14:13:11'),
('9a05b6dc-472a-455c-93e0-cfe6146521eb', '6d9f1e2f-9db9-45b7-94e4-a588cdafaad7', 'acbb21cd-2ec6-4a27-ab4f-fa952c4140a5', 'guru1@gmail.com', '$2y$12$ArR7W5OynUZQRxbbeRVtsukw3hokavvrd0mPk2IjN..BthBqeK8EW', 1, NULL, NULL, '2025-09-12 15:06:12', '2025-09-15 14:12:30'),
('bee95759-401c-4971-97bb-b349b0159460', '3b67a84e-e773-4983-a042-3091e6f6b8e2', 'ab0c3ddc-5594-4080-81b7-63a44b93e3b5', 'sekre@gmail.com', '$2y$12$jCO/EAlfL2bheHk4jiG2dO/gTjjGedLnbKm7RW/l3K4i8sBXSRv.u', 1, NULL, NULL, '2025-09-14 12:03:51', '2025-09-26 08:47:57');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `absensi_harians`
--
ALTER TABLE `absensi_harians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `absensi_harians_guru_id_tanggal_unique` (`guru_id`,`tanggal`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `gurus`
--
ALTER TABLE `gurus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gurus_nik_unique` (`nik`);

--
-- Indeks untuk tabel `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurusans`
--
ALTER TABLE `jurusans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelass`
--
ALTER TABLE `kelass`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mata_pelajarans`
--
ALTER TABLE `mata_pelajarans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `potongans`
--
ALTER TABLE `potongans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `qr_guru`
--
ALTER TABLE `qr_guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_guru_token_unique` (`token`);

--
-- Indeks untuk tabel `qr_kelas`
--
ALTER TABLE `qr_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_kelas_token_unique` (`token`);

--
-- Indeks untuk tabel `ruangans`
--
ALTER TABLE `ruangans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
