-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Jun 2026 pada 14.51
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
-- Database: `db_restoran`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `kategori` enum('makanan','minuman') DEFAULT 'makanan',
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `deskripsi`, `kategori`, `foto`) VALUES
(1, 'Nasi Rendang Daging', 25000, 'Nasi hangat + Rendang Daging Sapi empuk + Sayur Nangka + Sambal Ijo.', 'makanan', ''),
(2, 'Nasi Ayam Pop', 22000, 'Nasi hangat + Ayam Pop + Sayur Nangka + Sambal Ijo.', 'makanan', ''),
(3, 'Nasi Tambsu', 27000, 'Gulai usus sapi khas Minang yang gurih', 'makanan', ''),
(6, 'Gulai Tunjang', 28000, 'Kikil sapi tebal nan empuk disiram kuah gulai kental gurih.', 'makanan', 'https://images.unsplash.com/photo-1626132647523-66f5bf380027?q=80&w=200&auto=format&fit=crop'),
(8, 'Teh Dingin', 5000, 'Teh Dengan Dauh Teh Asli', 'minuman', 'uploads/6a34c593ba21d.jpg'),
(10, 'Jus Alpukat', 10000, 'Jus Alpukat Dengan Buah yang Segar', 'minuman', 'uploads/6a34cdf632426.jpg'),
(11, 'Es Kosong', 2000, 'Air Putih+Es Batu', 'minuman', 'uploads/6a34d0622da84.jpg'),
(12, 'Minas', 17000, 'Indomie + Nasi goreng + Telur Dijamin Kenyang', 'makanan', 'uploads/6a34d0b9424f5.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `tanggal_pesan` datetime DEFAULT current_timestamp(),
  `status_pesanan` varchar(50) DEFAULT 'Diproses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `id_menu`, `jumlah`, `tanggal_pesan`, `status_pesanan`) VALUES
(1, 4, 1, 1, '2026-06-18 11:40:39', 'Selesai'),
(2, 4, 1, 2, '2026-06-18 11:41:35', 'Selesai'),
(3, 4, 2, 1, '2026-06-18 11:41:35', 'Selesai'),
(4, 4, 2, 1, '2026-06-18 12:02:56', 'Dibatalkan'),
(5, 4, 3, 1, '2026-06-18 12:02:56', 'Dibatalkan'),
(6, 3, 3, 1, '2026-06-19 11:52:20', 'Selesai'),
(7, 3, 6, 1, '2026-06-19 11:52:20', 'Selesai'),
(8, 3, 6, 1, '2026-06-19 11:53:09', 'Dibatalkan'),
(9, 3, 3, 1, '2026-06-19 11:53:09', 'Dibatalkan'),
(10, 5, 12, 1, '2026-06-19 12:21:31', 'Selesai'),
(11, 5, 8, 1, '2026-06-19 12:21:31', 'Selesai'),
(12, 4, 11, 1, '2026-06-22 12:01:22', 'Diproses'),
(13, 4, 12, 1, '2026-06-22 12:01:22', 'Diproses');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(3, 'admin123', 'admin123', 'Pemilik Restoran', 'admin'),
(4, 'abi123', '$2y$10$e2rwTD2WsECJCbHP8tpx/ulvT1xHboGDvaG7rkdy75Jfqhu5J2XlG', 'Abi Rafdi Rahmad', 'pelanggan'),
(5, 'abi321', '$2y$10$2hOZ.M2tUdsjQ79Itii/o.AwSTD0vJ96hKzxbrF4J79tXacTGHlVe', 'abi ganteng', 'pelanggan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
