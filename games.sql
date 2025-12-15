-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 15 Des 2025 pada 03.08
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
-- Database: `gameplate`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `genres_id` bigint(20) UNSIGNED NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` longtext NOT NULL,
  `price` varchar(255) NOT NULL DEFAULT '0',
  `stock` int(11) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `game_link` varchar(255) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `games`
--

INSERT INTO `games` (`id`, `name`, `genres_id`, `publisher`, `user_id`, `description`, `price`, `stock`, `image`, `game_link`, `comments`, `created_at`, `updated_at`) VALUES
(1, 'Shadow Blade', 1, 'Indie Studio', NULL, 'Fast-paced ninja action game.', '50000', 5, NULL, 'https://example.com/shadow-blade', NULL, '2025-12-14 19:00:22', '2025-12-14 19:00:22'),
(2, 'Legend of Eldoria', 3, 'Fantasy Corp', NULL, 'Open world RPG with deep story.', '75000', 10, NULL, 'https://example.com/eldoria', NULL, '2025-12-14 19:00:22', '2025-12-14 19:00:22'),
(3, 'Nightmare Asylum', 6, 'Horror Labs', NULL, 'Psychological horror survival game.', '60000', 2, NULL, 'https://example.com/nightmare', NULL, '2025-12-14 19:00:22', '2025-12-14 19:04:46');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `games_genres_id_foreign` (`genres_id`),
  ADD KEY `games_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_genres_id_foreign` FOREIGN KEY (`genres_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `games_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
