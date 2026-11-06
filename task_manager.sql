-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 09:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','worker') NOT NULL DEFAULT 'worker',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Belisar Krasniqi', 'belisar123@gmail.com', '$2y$10$z0AXqAQPD4GIOWgZUippAebXxGk/78WFf5HQcehJ6A7WzZn4Fa.Tq', 'admin', '2026-10-23 06:37:13'),
(2, 'Edi Smerqaku', 'edi12@gmail.com', '$2y$10$ahXkOED1zFl7Lst3caHnZ.tG4HoQuFD3xqSKDnIMJsfuMrodnuizq', 'worker', '2026-10-23 07:06:42'),
(3, 'Diar Hoxha', 'diar1234@gmail.com', '$2y$10$U2palIkg4dEuLwGyRvpkg.bgpggOsAlx9J6WCKSqqStUU.iQoO1C6', 'worker', '2026-10-23 07:08:08'),
(4, 'Bekon Brestovci', 'bekon8@gmail.com', '$2y$10$wP41m.V5zO2T6q3r3YJ.MO7gU.dNBpY6ZdMsx4ymf./y7E9sf3buq', 'worker', '2026-10-23 07:08:59'),
(5, 'Erdi Hajdari', 'erdi0@gmail.com', '$2y$10$nFrz8.zGw/nwpQMrBm6CBO7NKpG/39dDXbdr7DhIn70Jm9CLQgLEu', 'worker', '2026-10-23 07:11:50'),
(6, 'Afrim Rexhepi', 'afrimrexhepi@gmail.com', '$2y$10$66k4rLLN1rdAqVqWDrPmBu5TrfkuBKdSUoE4Id82Xj0QBnFoPQtHy', 'worker', '2026-10-23 07:37:55'),
(7, 'rilind sahiti', 'lindisahiti98@gmail.com', '$2y$10$fkBQxgvXDgT2xssBjjenL.oX6Kh4jtI3mH7P5sdB99NChG37j0FJu', 'worker', '2026-10-23 08:01:06'),
(8, 'lunik ramadani', 'lunik12@gmail.com', '$2y$10$oXdv2p8Qc/Xscd/UUXiypucMB58kiC3qejwMqDa.j1Wzdyme4Zv1u', 'worker', '2026-10-23 08:01:37'),
(9, 'perparim haliti', 'perparimhaliti@gmail.com', '$2y$10$Ipj9e3OVrN.R/wm3DtDr3.quKVKV2WdnitLwi49U0Zd/IB4h/k6V2', 'worker', '2026-10-23 08:07:06'),
(10, 'LUNIK PETROFCI', 'lunik123@gmail.com', '$2y$10$MojeAaVs0djGjFJ2PRihaeZ/sE27k/NWorTzm6rLUR45cptMD4jGi', 'worker', '2026-10-23 08:09:52'),
(11, 'bekon boti i csgos', 'bekonnubi67@gmail.com', '$2y$10$R5sgjR36w80saNqb1.8LmOR.FRsUoRDXlm7r/v/v1FMUQPmsfqRDG', 'worker', '2026-10-23 08:10:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
