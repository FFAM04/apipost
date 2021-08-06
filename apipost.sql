-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3309
-- Generation Time: Aug 06, 2021 at 11:29 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apipost`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `published` int(1) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `views` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id_post`, `title`, `slug`, `body`, `image`, `user_id`, `created_at`, `published`, `updated_at`, `views`) VALUES
(1, 'judlnnb', 'judl-slug', 'isi', 'http://localhost/storage/post/nrUwnjpNNlOvJpLsQWDehzNiyshEv2E9gEfeCE7L.png', 1, '2021-08-06 08:24:24', 0, '2021-08-06 01:33:47', NULL),
(3, 'judulw', 'judul-slugw', 'isi nyaw', '', 1, '2021-08-06 01:29:19', 1, NULL, NULL),
(4, 'judulw image', 'judul-slugwd', 'isi nyaw', 'Storage::disk(\'public\')->url(post/fVJTJ7YHBAkF9iK6OJXDwGaIw1vxXMGCHXvoimwL.png)', 1, '2021-08-06 01:30:05', 1, NULL, NULL),
(5, 'judulw image', 'judul-slugwdd', 'isi nyaw', 'http://localhost/storage/post/pA5WgTOuOYn8tEJNdW57RjKNwbqgVyDVwvZGUhxP.png', 1, '2021-08-06 01:30:57', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(1) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'username', 'name@gmail.com', 1, '$2y$10$LjnMf.95Bvz.AlvizpFW9elXMitRVpK410OVe.BlDl1w5xAcka4tK', '2018-01-08 05:52:58', '2021-08-06 02:11:35'),
(6, 'user2', 'user_7@gmai.com', 2, '$2y$10$y6x.rgAr2HyLQDgipp7Oh.9eHWG4KJwZlI6U1jcEmC6L9kKu0rEw2', '2021-08-06 02:05:29', NULL),
(7, 'user2', 'user_3@gmai.com', 2, '$2y$10$9DoulRsKpqtwoEwDMa8vA.0wKHtOPF9DwFIhfDLHjG2WMsjDeWi/m', '2021-08-06 02:17:59', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
