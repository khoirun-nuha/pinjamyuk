-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Feb 2026 pada 18.05
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
-- Database: `db_pinjamyuk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alat`
--

CREATE TABLE `alat` (
  `id_alat` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alat`
--

INSERT INTO `alat` (`id_alat`, `id_kategori`, `nama_alat`, `deskripsi`, `stok`, `gambar`) VALUES
(1, 3, 'Ring Light', 'Alat Ring Light merk Shure kondisi baik. Inventaris #1', 5, 'ring_light.png'),
(2, 1, 'Proyektor', 'Alat Proyektor merk Canon kondisi baik. Inventaris #2', 38, 'projector_epson.png'),
(3, 5, 'Meja Rapat', 'Alat Meja Rapat merk HP kondisi baik. Inventaris #3', 10, 'meja_sekolah.png'),
(4, 6, 'Harddisk Eksternal', 'Alat Harddisk Eksternal merk Logitech kondisi baik. Inventaris #4', 21, 'harddisk_external.png'),
(5, 2, 'Mic Wireless', 'Alat Mic Wireless merk Samsung kondisi baik. Inventaris #5', 62, 'mic_wireless.png'),
(7, 6, 'Keyboard Mechanical', 'Alat Keyboard Mechanical merk Canon kondisi baik. Inventaris #7', 40, 'keyboard_mechanical.png'),
(8, 4, 'Kabel Power', 'Alat Kabel Power merk Sony kondisi baik. Inventaris #8', 8, 'kabel_hdmi.png'),
(9, 6, 'Mouse Wireless', 'Alat Mouse Wireless merk Ikea kondisi baik. Inventaris #9', 31, 'mouse_wireless.png'),
(12, 4, 'Kabel HDMI', 'Alat Kabel HDMI merk Epson kondisi baik. Inventaris #12', 50, 'kabel_hdmi.png'),
(13, 2, 'Mixer Audio', 'Alat Mixer Audio merk Shure kondisi baik. Inventaris #13', 40, 'mixer_audio.png'),
(14, 3, 'Tripod', 'Alat Tripod merk LG kondisi baik. Inventaris #14', 38, 'tripod.png'),
(18, 1, 'Printer Laser', 'Alat Printer Laser merk Ikea kondisi baik. Inventaris #18', 61, 'printer_laser.png'),
(19, 2, 'Speaker Active', 'Alat Speaker Active merk Epson kondisi baik. Inventaris #19', 4, 'speaker_active.png'),
(21, 1, 'Laptop', 'Alat Laptop merk LG kondisi baik. Inventaris #21', 25, 'laptop_generic.png'),
(24, 1, 'TV LED', 'Alat TV LED merk HP kondisi baik. Inventaris #24', 20, 'tv_led.png'),
(25, 6, 'Flashdisk 32GB', 'Alat Flashdisk 32GB merk Shure kondisi baik. Inventaris #25', 37, 'flashdisk.png'),
(30, 5, 'Lemari Arsip', 'Alat Lemari Arsip merk Yamaha kondisi baik. Inventaris #30', 0, 'default.jpg'),
(32, 5, 'Kursi Staff', 'Alat Kursi Staff merk Shure kondisi baik. Inventaris #32', 0, 'kursi_kayu.png'),
(35, 4, 'Kabel LAN 10m', 'Alat Kabel LAN 10m merk Shure kondisi baik. Inventaris #35', 8, 'kabel_hdmi.png'),
(36, 3, 'Lensa', 'Alat Lensa merk LG kondisi baik. Inventaris #36', 5, 'canon_700d.png'),
(45, 4, 'Converter HDMI-VGA', 'Alat Converter HDMI-VGA merk Logitech kondisi baik. Inventaris #45', 9, 'kabel_hdmi.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_alat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_peminjaman`, `id_alat`, `jumlah`) VALUES
(1, 2, 24, 2),
(2, 3, 12, 7),
(3, 4, 32, 2),
(4, 5, 1, 5),
(5, 6, 30, 4),
(6, 7, 30, 5),
(7, 8, 4, 9),
(8, 9, 4, 50),
(9, 10, 12, 5),
(10, 11, 12, 5),
(11, 12, 12, 5),
(12, 13, 12, 5),
(13, 14, 12, 5),
(14, 15, 7, 3),
(15, 16, 12, 2),
(16, 17, 5, 1),
(17, 18, 25, 2),
(18, 19, 25, 1),
(19, 20, 7, 2),
(20, 21, 45, 1),
(21, 22, 8, 1),
(22, 23, 25, 2),
(23, 23, 45, 1),
(24, 25, 45, 9),
(25, 27, 45, 2),
(26, 28, 1, 2),
(27, 29, 1, 2),
(28, 30, 1, 1),
(29, 31, 45, 9);

--
-- Trigger `detail_peminjaman`
--
DELIMITER $$
CREATE TRIGGER `tr_kurang_stok_insert` AFTER INSERT ON `detail_peminjaman` FOR EACH ROW BEGIN
    UPDATE alat SET stok = stok - NEW.jumlah 
    WHERE id_alat = NEW.id_alat;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Elektronik'),
(2, 'Perlengkapan Audio'),
(3, 'Perlengkapan Visual'),
(4, 'Kabel & Konektor'),
(5, 'Furnituree'),
(6, 'Aksesoris Komputer'),
(7, 'Olahraga'),
(8, 'Multimediaa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `aktivitas` enum('login','logout','peminjaman','pengembalian') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `waktu` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_pengguna`, `aktivitas`, `keterangan`, `waktu`) VALUES
(1, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 2)', '2026-01-22 08:53:04'),
(2, 1, 'login', 'Login berhasil', '2026-01-22 08:54:29'),
(3, 2, 'pengembalian', 'Memproses pengembalian ID: 2', '2026-01-22 08:55:41'),
(4, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 3)', '2026-01-22 09:08:43'),
(5, 2, 'pengembalian', 'Memproses pengembalian ID: 3. Stok dikembalikan: Canon Kabel HDMI 440 (7)', '2026-01-22 09:10:06'),
(6, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 4)', '2026-01-22 09:19:23'),
(7, 2, 'pengembalian', 'Memproses pengembalian ID: 4. Stok kembali: Shure Kursi Staff 233 (2 unit)', '2026-01-22 09:20:30'),
(8, 1, 'pengembalian', 'Memproses pengembalian ID: 5. Stok kembali: Shure Ring Light 514 (5 unit)', '2026-01-22 09:24:25'),
(9, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 6)', '2026-01-22 09:25:26'),
(10, 2, 'pengembalian', 'Memproses pengembalian ID: 6. Stok kembali: Yamaha Lemari Arsip 486 (4 unit)', '2026-01-22 09:26:19'),
(11, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 7)', '2026-01-22 09:31:18'),
(12, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 8)', '2026-01-22 09:36:24'),
(13, 2, 'pengembalian', 'Memproses pengembalian ID: 7. Stok kembali: Yamaha Lemari Arsip 898 (5 unit)', '2026-01-22 09:36:57'),
(14, 2, 'pengembalian', 'Memproses pengembalian ID: 8. Stok kembali: Dell Harddisk Eksternal 364 (9 unit)', '2026-01-22 09:37:22'),
(15, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 9)', '2026-01-22 09:42:55'),
(16, 2, 'pengembalian', 'Memproses pengembalian ID: 9. Stok kembali: Dell Harddisk Eksternal 364 (50 unit)', '2026-01-22 09:45:55'),
(17, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 10)', '2026-01-22 09:49:21'),
(18, 2, 'pengembalian', 'Memproses pengembalian ID: 10. Stok kembali: Dell Kabel HDMI 986 (5 unit)', '2026-01-22 09:49:52'),
(19, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 11)', '2026-01-22 09:50:27'),
(20, 2, 'pengembalian', 'Memproses pengembalian ID: 11. Stok kembali: Dell Kabel HDMI 986 (5 unit)', '2026-01-22 09:51:01'),
(21, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 12)', '2026-01-22 09:54:38'),
(22, 2, 'pengembalian', 'Memproses pengembalian ID: 12. Stok kembali: Dell Kabel HDMI 986 (5 unit)', '2026-01-22 09:56:29'),
(23, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 13)', '2026-01-22 11:24:43'),
(24, 2, 'pengembalian', 'Memproses pengembalian ID: 13. Items: Dell Kabel HDMI 986', '2026-01-22 11:27:26'),
(25, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 14)', '2026-01-22 11:28:05'),
(26, 1, 'login', 'Login berhasil', '2026-01-22 12:50:42'),
(27, 1, 'logout', 'Logout berhasil', '2026-01-22 13:58:05'),
(28, 2, 'login', 'Login berhasil', '2026-01-22 13:58:12'),
(29, 2, 'logout', 'Logout berhasil', '2026-01-22 14:12:20'),
(30, 3, 'login', 'Login berhasil', '2026-01-22 14:12:28'),
(31, 3, 'login', 'Login berhasil', '2026-01-23 04:50:32'),
(32, 1, 'login', 'Login berhasil', '2026-01-23 07:35:16'),
(33, 1, 'logout', 'Logout berhasil', '2026-01-23 08:24:20'),
(34, 2, 'login', 'Login berhasil', '2026-01-23 08:24:28'),
(35, 2, 'logout', 'Logout berhasil', '2026-01-23 08:38:59'),
(36, 1, 'login', 'Login berhasil', '2026-01-23 08:42:49'),
(37, 1, 'logout', 'Logout berhasil', '2026-01-23 11:35:59'),
(38, 3, 'login', 'Login berhasil', '2026-01-23 11:36:48'),
(39, 3, 'logout', 'Logout berhasil', '2026-01-23 11:56:10'),
(40, 1, 'login', 'Login berhasil', '2026-01-23 13:13:15'),
(41, 1, 'login', 'Login berhasil', '2026-01-23 13:25:19'),
(42, 1, 'logout', 'Logout berhasil', '2026-01-23 14:18:16'),
(43, 2, 'login', 'Login berhasil', '2026-01-23 14:18:34'),
(44, 1, 'login', 'Login berhasil', '2026-01-23 14:32:31'),
(45, 4, 'login', 'Login berhasil', '2026-01-23 14:53:29'),
(46, 4, 'login', 'Login berhasil', '2026-01-26 06:02:21'),
(47, 4, 'login', 'Login berhasil', '2026-01-26 20:15:22'),
(48, 4, 'logout', 'Logout berhasil', '2026-01-26 20:39:01'),
(49, 1, 'login', 'Login berhasil', '2026-01-26 20:40:29'),
(50, 1, 'logout', 'Logout berhasil', '2026-01-26 20:42:49'),
(51, 1, 'login', 'Login berhasil', '2026-01-26 21:57:18'),
(52, 1, 'login', 'Login berhasil', '2026-01-27 07:37:28'),
(53, 1, 'logout', 'Logout berhasil', '2026-01-27 07:52:14'),
(54, 1, 'login', 'Login berhasil', '2026-01-27 07:52:33'),
(55, 1, 'logout', 'Logout berhasil', '2026-01-27 07:52:39'),
(56, 4, 'login', 'Login berhasil', '2026-01-27 07:52:44'),
(57, 4, 'peminjaman', 'Mengajukan peminjaman baru (ID: 15)', '2026-01-27 08:08:22'),
(58, 2, 'login', 'Login berhasil', '2026-01-27 08:08:37'),
(59, 1, 'login', 'Login berhasil', '2026-01-27 08:29:46'),
(60, 1, 'login', 'Login berhasil', '2026-01-27 11:12:04'),
(61, 1, 'logout', 'Logout berhasil', '2026-01-27 11:12:13'),
(62, 1, 'login', 'Login berhasil', '2026-01-27 11:12:44'),
(63, 1, 'logout', 'Logout berhasil', '2026-01-27 11:24:23'),
(64, 1, 'login', 'Login berhasil', '2026-01-27 11:24:41'),
(65, 1, 'logout', 'Logout berhasil', '2026-01-27 11:27:17'),
(66, 1, 'login', 'Login berhasil', '2026-01-27 11:31:04'),
(67, 3, 'login', 'Login berhasil', '2026-01-27 11:50:25'),
(68, 1, 'login', 'Login berhasil', '2026-01-28 13:08:15'),
(69, 1, 'logout', 'Logout berhasil', '2026-01-28 13:09:20'),
(70, 2, 'login', 'Login berhasil', '2026-01-28 13:49:11'),
(71, 1, 'login', 'Login berhasil', '2026-01-28 13:56:16'),
(72, 1, 'login', 'Login berhasil', '2026-01-28 20:34:27'),
(73, 3, 'login', 'Login berhasil', '2026-01-28 20:39:45'),
(74, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 16)', '2026-01-28 20:48:47'),
(75, 2, 'login', 'Login berhasil', '2026-01-28 20:49:26'),
(76, 3, 'login', 'Login berhasil', '2026-01-29 08:04:09'),
(77, 2, 'login', 'Login berhasil', '2026-01-29 08:04:20'),
(78, 1, 'login', 'Login berhasil', '2026-01-29 08:04:36'),
(79, 2, 'pengembalian', 'Memproses pengembalian ID: 16. Items: Canon Kabel HDMI 440', '2026-01-29 08:12:09'),
(80, 1, 'logout', 'Logout berhasil', '2026-01-29 08:42:46'),
(81, 1, 'login', 'Login berhasil', '2026-01-29 08:45:22'),
(82, 3, 'logout', 'Logout berhasil', '2026-01-29 08:46:44'),
(83, 3, 'login', 'Login berhasil', '2026-01-29 08:48:57'),
(84, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 17)', '2026-01-29 08:55:29'),
(85, 2, 'pengembalian', 'Memproses pengembalian ID: 17. Items: Logitech Mic Wireless 755', '2026-01-29 09:01:27'),
(86, 1, 'logout', 'Logout berhasil', '2026-01-29 09:08:52'),
(87, 3, 'logout', 'Logout berhasil', '2026-01-29 09:47:17'),
(88, 1, 'login', 'Login berhasil', '2026-01-29 09:48:14'),
(89, 3, 'login', 'Login berhasil', '2026-01-29 12:17:48'),
(90, 1, 'login', 'Login berhasil', '2026-01-29 16:37:31'),
(91, 1, 'login', 'Login berhasil', '2026-01-29 19:07:49'),
(92, 16, 'login', 'Login berhasil', '2026-01-29 19:17:34'),
(93, 3, 'login', 'Login berhasil', '2026-01-29 19:20:56'),
(94, 3, 'logout', 'Logout berhasil', '2026-01-29 21:03:20'),
(95, 1, 'login', 'Login berhasil', '2026-01-30 06:40:24'),
(96, 1, 'login', 'Login berhasil', '2026-01-30 09:49:24'),
(97, 2, 'login', 'Login berhasil', '2026-01-30 09:57:56'),
(98, 16, 'login', 'Login berhasil', '2026-01-30 11:18:40'),
(99, 2, 'login', 'Login berhasil', '2026-01-30 11:19:04'),
(100, 3, 'login', 'Login berhasil', '2026-01-30 11:31:17'),
(101, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 18)', '2026-01-30 11:46:17'),
(102, 16, 'peminjaman', 'Memverifikasi peminjaman ID: 18 (Status: Disetujui)', '2026-01-30 11:46:41'),
(103, 2, 'pengembalian', 'Memproses pengembalian ID: 18. Items: Flashdisk 32GB', '2026-01-30 11:49:02'),
(104, 2, 'logout', 'Logout berhasil', '2026-01-30 11:49:38'),
(105, 3, 'login', 'Login berhasil', '2026-01-30 11:49:53'),
(106, 3, 'logout', 'Logout berhasil', '2026-01-30 11:50:00'),
(107, 2, 'login', 'Login berhasil', '2026-01-30 11:50:07'),
(108, 2, 'login', 'Login berhasil', '2026-01-30 12:48:01'),
(109, 2, 'login', 'Login berhasil', '2026-01-30 17:59:17'),
(110, 16, 'login', 'Login berhasil', '2026-01-30 17:59:39'),
(111, 3, 'login', 'Login berhasil', '2026-01-30 17:59:55'),
(112, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 19)', '2026-01-30 18:00:10'),
(113, 16, 'peminjaman', 'Memverifikasi peminjaman ID: 19 (Status: Disetujui)', '2026-01-30 18:00:21'),
(114, 2, 'pengembalian', 'Memproses pengembalian ID: 19. Items: Flashdisk 32GB', '2026-01-30 18:00:54'),
(115, 1, 'login', 'Login berhasil', '2026-01-31 13:15:44'),
(116, 2, 'login', 'Login berhasil', '2026-01-31 13:18:15'),
(117, 3, 'login', 'Login berhasil', '2026-01-31 13:19:22'),
(118, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 20)', '2026-01-31 13:20:13'),
(119, 3, 'peminjaman', 'Membatalkan pengajuan peminjaman ID: 20', '2026-01-31 13:20:22'),
(120, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 21)', '2026-01-31 13:21:12'),
(121, 2, 'peminjaman', 'Memverifikasi peminjaman ID: 21 (Status: Disetujui)', '2026-01-31 13:21:50'),
(122, 1, 'logout', 'Logout berhasil', '2026-01-31 13:22:37'),
(123, 16, 'login', 'Login berhasil', '2026-01-31 13:22:46'),
(124, 16, 'pengembalian', 'Memproses pengembalian ID: 21. Items: Converter HDMI-VGA', '2026-01-31 13:23:00'),
(125, 2, 'pengembalian', 'Memproses pengembalian ID: 21. Items: Converter HDMI-VGA', '2026-01-31 13:26:58'),
(126, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 22)', '2026-01-31 13:28:22'),
(127, 3, 'peminjaman', 'Membatalkan pengajuan peminjaman ID: 22', '2026-01-31 13:28:30'),
(128, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 23)', '2026-01-31 13:29:11'),
(129, 3, 'peminjaman', 'Membatalkan pengajuan peminjaman ID: 23', '2026-01-31 13:29:54'),
(130, 16, 'logout', 'Logout berhasil', '2026-01-31 13:38:52'),
(131, 2, 'logout', 'Logout berhasil', '2026-01-31 13:39:00'),
(132, 3, 'logout', 'Logout berhasil', '2026-01-31 13:40:35'),
(133, 1, 'login', 'Login berhasil', '2026-01-31 13:41:29'),
(134, 18, 'login', 'Login berhasil', '2026-01-31 13:45:23'),
(135, 2, 'login', 'Login berhasil', '2026-01-31 13:49:39'),
(136, 1, 'logout', 'Logout berhasil', '2026-01-31 13:49:47'),
(137, 16, 'login', 'Login berhasil', '2026-01-31 13:49:58'),
(138, 18, 'peminjaman', 'Mengajukan peminjaman baru (ID: 25)', '2026-01-31 13:52:16'),
(139, 16, 'peminjaman', 'Memverifikasi peminjaman ID: 25 (Status: Disetujui)', '2026-01-31 13:52:53'),
(140, 2, 'pengembalian', 'Memproses pengembalian ID: 25. Items: Converter HDMI-VGA', '2026-01-31 13:53:55'),
(141, 18, 'logout', 'Logout berhasil', '2026-01-31 13:56:13'),
(142, 1, 'login', 'Login berhasil', '2026-01-31 13:57:53'),
(143, 1, 'logout', 'Logout berhasil', '2026-01-31 14:58:05'),
(144, 3, 'login', 'Login berhasil', '2026-01-31 14:58:14'),
(145, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 27)', '2026-01-31 14:59:41'),
(146, 16, 'peminjaman', 'Memverifikasi peminjaman ID: 27 (Status: Ditolak)', '2026-01-31 15:00:09'),
(147, 1, 'login', 'Login berhasil', '2026-02-01 22:02:48'),
(148, 1, 'logout', 'Logout berhasil', '2026-02-01 22:05:50'),
(149, 1, 'login', 'Login berhasil', '2026-02-01 22:05:58'),
(150, 2, 'login', 'Login berhasil', '2026-02-01 22:35:08'),
(151, 3, 'login', 'Login berhasil', '2026-02-01 22:44:17'),
(152, 3, 'peminjaman', 'Mengajukan peminjaman baru (ID: 31)', '2026-02-01 23:12:40'),
(153, 2, 'peminjaman', 'Memverifikasi peminjaman ID: 31 (Status: Disetujui)', '2026-02-01 23:13:44'),
(154, 2, 'pengembalian', 'Memproses pengembalian ID: 31. Items: Converter HDMI-VGA', '2026-02-01 23:25:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `tgl_pengajuan_pinjam` datetime DEFAULT NULL,
  `tgl_pinjam` datetime DEFAULT NULL,
  `tgl_kembali_rencana` date DEFAULT NULL,
  `tgl_pengajuan_kembali` datetime DEFAULT NULL,
  `tgl_kembali_real` datetime DEFAULT NULL,
  `denda` int(11) DEFAULT 0,
  `status` enum('diajukan','dipinjam','dikembalikan','ditolak','diajukan_kembali') NOT NULL DEFAULT 'diajukan',
  `id_petugas` int(11) DEFAULT NULL,
  `id_petugas_kembali` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_pengguna`, `tgl_pengajuan_pinjam`, `tgl_pinjam`, `tgl_kembali_rencana`, `tgl_pengajuan_kembali`, `tgl_kembali_real`, `denda`, `status`, `id_petugas`, `id_petugas_kembali`) VALUES
(2, 3, NULL, '2026-01-22 08:53:04', '2026-01-22', NULL, '2026-01-30 08:55:41', 0, 'dikembalikan', 2, NULL),
(3, 3, NULL, '2026-01-22 09:08:42', '2026-01-23', NULL, '2026-01-22 09:10:06', 0, 'dikembalikan', 2, NULL),
(4, 3, NULL, '2026-01-22 09:19:23', '2026-01-22', NULL, '2026-01-22 09:20:30', 0, 'dikembalikan', 2, NULL),
(5, 1, NULL, '2026-01-22 00:00:00', '2026-01-22', NULL, '2026-01-22 09:24:25', 0, 'dikembalikan', 1, NULL),
(6, 3, NULL, '2026-01-22 09:25:26', '2026-01-23', NULL, '2026-01-22 09:26:19', 0, 'dikembalikan', 2, NULL),
(7, 3, NULL, '2026-01-22 09:31:18', '2026-01-22', NULL, '2026-01-22 09:36:57', 0, 'dikembalikan', 2, NULL),
(8, 3, NULL, '2026-01-22 09:36:24', '2026-01-22', NULL, '2026-01-22 09:37:22', 0, 'dikembalikan', 2, NULL),
(9, 3, NULL, '2026-01-22 09:42:55', '2026-01-22', NULL, '2026-01-22 09:45:55', 0, 'dikembalikan', 2, NULL),
(10, 3, NULL, '2026-01-22 09:49:21', '2026-01-23', NULL, '2026-01-22 09:49:52', 0, 'dikembalikan', 2, NULL),
(11, 3, NULL, '2026-01-22 09:50:27', '2026-01-22', NULL, '2026-01-22 09:51:01', 0, 'dikembalikan', 2, NULL),
(12, 3, NULL, '2026-01-22 09:54:38', '2026-01-22', NULL, '2026-01-22 09:56:29', 0, 'dikembalikan', 2, NULL),
(13, 3, NULL, '2026-01-22 11:24:42', '2026-01-23', NULL, '2026-01-22 11:27:26', 0, 'dikembalikan', 2, NULL),
(14, 3, NULL, '2026-01-22 11:28:05', '2026-01-23', NULL, NULL, 0, 'ditolak', 2, NULL),
(15, 4, NULL, '2026-01-27 08:08:22', '2026-01-29', NULL, NULL, 0, 'dipinjam', 2, NULL),
(16, 3, NULL, '2026-01-28 20:48:47', '2026-01-28', NULL, '2026-01-29 08:12:09', 4000, 'dikembalikan', 2, NULL),
(17, 3, NULL, '2026-01-29 08:55:29', '2026-02-28', NULL, '2026-01-29 09:01:27', 0, 'dikembalikan', 2, NULL),
(18, 3, NULL, '2026-01-30 11:46:17', '2026-01-30', NULL, '2026-01-30 11:49:02', 0, 'dikembalikan', 2, NULL),
(19, 3, NULL, '2026-01-30 18:00:10', '2026-01-30', NULL, '2026-01-30 18:00:54', 0, 'dikembalikan', 2, NULL),
(20, 3, NULL, '2026-01-31 13:20:13', '2026-01-31', NULL, NULL, 0, '', NULL, NULL),
(21, 3, NULL, '2026-01-31 13:21:12', '2026-01-31', NULL, '2026-01-31 13:26:58', 0, 'dikembalikan', 2, 2),
(22, 3, NULL, '2026-01-31 13:28:22', '2026-01-31', NULL, NULL, 0, '', NULL, NULL),
(23, 3, NULL, '2026-01-31 13:29:11', '2026-01-31', NULL, NULL, 0, '', NULL, NULL),
(25, 18, NULL, '2026-01-31 13:52:16', '2026-01-31', NULL, '2026-01-31 13:53:55', 0, 'dikembalikan', 16, 2),
(27, 3, NULL, '2026-01-31 14:59:41', '2026-01-31', NULL, NULL, 0, 'ditolak', 16, NULL),
(28, 5, NULL, '2026-01-26 22:53:44', '2026-01-31', NULL, '2026-02-01 22:53:44', 4000, 'dikembalikan', 2, 2),
(29, 5, NULL, '2026-01-25 22:53:44', '2026-01-30', NULL, '2026-02-01 22:53:44', 8000, 'dikembalikan', 2, 2),
(30, 5, NULL, '2026-01-22 22:53:44', '2026-01-27', NULL, '2026-02-01 22:53:44', 10000, 'dikembalikan', 2, 2),
(31, 3, '2026-02-01 23:12:40', '2026-02-01 23:13:44', '2026-02-08', '2026-02-01 23:15:49', '2026-02-01 23:25:14', 0, 'dikembalikan', 2, 2);

--
-- Trigger `peminjaman`
--
DELIMITER $$
CREATE TRIGGER `tr_restock_update` AFTER UPDATE ON `peminjaman` FOR EACH ROW BEGIN
    
    IF (NEW.status = 'ditolak' AND OLD.status != 'ditolak') OR 
       (NEW.status = 'dikembalikan' AND OLD.status != 'dikembalikan') THEN
       
        
        UPDATE alat a
        INNER JOIN detail_peminjaman dp ON a.id_alat = dp.id_alat
        SET a.stok = a.stok + dp.jumlah
        WHERE dp.id_peminjaman = NEW.id_peminjaman;
        
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `level` enum('admin','petugas','peminjam') NOT NULL,
  `gambar` varchar(255) DEFAULT 'default_profil.png',
  `status` enum('aktif','blokir') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `nama_lengkap`, `alamat`, `level`, `gambar`, `status`) VALUES
(1, 'admin', '$2y$10$zRLnrlheFQZfrg9o5hACC.E1QRBMYG/iPTIIpmdrUzOBPndW7lhs6', 'Administrator Utama', 'Kantor Pusat', 'admin', '697da5ef1c981.png', 'aktif'),
(2, 'petugas', '$2y$10$pVhPGIMa.Vi.cBzC9fb/6.j5naUVmf2yaNdlUA8dZqPGhkDZ.ocrq', 'Budi Santoso', 'Ruang Inventaris', 'petugas', 'default_profil.png', 'aktif'),
(3, 'siswa', '$2y$10$h3XajOzhdY7bFpmkrP8B7e0FbyQqe3jDf7uhRqZ01G0r3H50sfC02', 'Andi Pratama', 'Kelas XII RPL 1', 'peminjam', 'default_profil.png', 'aktif'),
(4, 'guru', '$2y$10$7n78vRmvmErzvEfSNTnanez9sNdZ6mLKreEwVW5MRH2gKTkXlfQtm', 'Pak Guru', 'Ruang Guru', 'peminjam', 'default_profil.png', 'aktif'),
(5, 'siswa2', '202cb962ac59075b964b07152d234b70', 'Siti Aminah', 'Kelas XI TKJ 2', 'peminjam', 'default_profil.png', 'aktif'),
(6, 'siswa3', '202cb962ac59075b964b07152d234b70', 'Doni Tata', 'Kelas X MM 1', 'peminjam', 'default_profil.png', 'aktif'),
(7, 'nuha', '$2y$10$HpwzsYrwiCECtN5Zzr7TOu41zRfJH9rhSh0VlhVzRbBTiHwcoOkYq', 'nuha', 'Tengger wetan', 'peminjam', 'default_profil.png', 'aktif'),
(8, 'nuha123', '$2y$10$dAuUdQOAFyDuWsN8ZPVimO6pDwm/hNwmM.hbXSoFI1U2lkNG9yJF6', 'nuha', 'Tengger wetan', 'peminjam', 'default_profil.png', 'aktif'),
(15, 'farid', '$2y$10$H76bG98dOkBt1p6CN6iNr.WSq3JFMEP7P64k5LkRSwWCgMFAhho4W', 'Farid', 'Merakurak', 'peminjam', 'default_profil.png', 'aktif'),
(16, 'khoirun', '$2y$10$flw7dDPRWb5t698DP4rDfeMQsuKFx/ruJY8y2L3Mj5i.zIQ0h1J7u', 'Khoirun nuha', 'Montong', 'petugas', 'default_profil.png', 'aktif'),
(18, 'dedy', '$2y$10$NXmP9iNa6Au/bGTuHqRCOOYZGQmrJATTSTcIAHrxIdFJCvxXwJPhe', 'Dedy', 'Senori', 'peminjam', 'default_profil.png', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id_alat`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_pengguna` (`id_pengguna`),
  ADD KEY `id_petugas` (`id_petugas`),
  ADD KEY `fk_pinjam_petugas_kembali` (`id_petugas_kembali`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alat`
--
ALTER TABLE `alat`
  MODIFY `id_alat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD CONSTRAINT `fk_alat_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `fk_detail_alat` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id_alat`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_pinjam` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_pinjam_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pinjam_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `pengguna` (`id_pengguna`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pinjam_petugas_kembali` FOREIGN KEY (`id_petugas_kembali`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
