-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2024 at 10:54 AM
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
-- Database: `attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `bu_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive`
--

INSERT INTO `archive` (`id`, `user_id`, `student_name`, `status`, `date`, `bu_no`) VALUES
(1, 1, 'Anobling, Janna Mae', 'Present', '2024-02-22 22:58:55', '2022-4743-23173'),
(2, 1, 'Arevalo, Kristine Zyra Mae ', 'Present', '2024-02-22 22:58:55', '2022-9769-26289'),
(3, 1, 'Aringo, Bea Mae', 'Present', '2024-02-22 22:58:55', '2022-2848-97073'),
(4, 1, 'Bautista, Madel Jandra', 'Present', '2024-02-22 22:58:55', '2022-2951-44568'),
(5, 1, 'Bonayon, Arabela', 'Present', '2024-02-22 22:58:55', '2022-8250-23124'),
(6, 1, 'Bordeos, Shieralyn ', 'Present', '2024-02-22 22:58:55', '2022-4974-55555'),
(7, 1, 'Bragais, Justine', 'Present', '2024-02-22 22:58:55', '2022-3574-32403'),
(8, 1, 'Buenconsejo, Alexis', 'Present', '2024-02-22 22:58:55', '2022-3450-44180'),
(9, 1, 'Casurog, Jem ', 'Present', '2024-02-22 22:58:55', '2022-3191-58503'),
(10, 1, 'Cate, Ken Dave ', 'Present', '2024-02-22 22:58:55', '2022-5072-45887'),
(11, 1, 'Escano, Joshua Mari Francis ', 'Present', '2024-02-22 22:58:55', '2022-2363-52006'),
(12, 1, 'Habana, Ivy', 'Present', '2024-02-22 22:58:55', '2022-1319-34988'),
(13, 1, 'Leoncito, Glenysse', 'Present', '2024-02-22 22:58:55', '2022-7280-54951'),
(14, 1, 'Lobos, Althea ', 'Present', '2024-02-22 22:58:55', '2022-6288-68920'),
(15, 1, 'Loneza, Walton John ', 'Present', '2024-02-22 22:58:55', '2022-2920-98466'),
(16, 1, 'Madara, Allysa ', 'Present', '2024-02-22 22:58:55', '2022-4552-96399'),
(17, 1, 'Moises, Elton John', 'Present', '2024-02-22 22:58:55', '2022-9110-55459'),
(18, 1, 'Moscoso, Aila', 'Present', '2024-02-22 22:58:55', '2022-3617-50142'),
(19, 1, 'Nas, Jonalyn ', 'Present', '2024-02-22 22:58:55', '2022-4712-10138'),
(20, 1, 'Nasayao, Rhea May', 'Present', '2024-02-22 22:58:55', '2022-4256-69735'),
(21, 1, 'Padua, Gwen ', 'Present', '2024-02-22 22:58:55', '2022-7753-24136'),
(22, 1, 'Pocaan, Lhea ', 'Present', '2024-02-22 22:58:55', '2022-7321-46478'),
(23, 1, 'Realisan, Jamaica', 'Present', '2024-02-22 22:58:55', '2022-9082-60646'),
(24, 1, 'Sabater, Christine Mae', 'Present', '2024-02-22 22:58:55', '2022-7033-68287'),
(25, 1, 'Saculo, Francyn Essy ', 'Present', '2024-02-22 22:58:55', '2022-7224-93591'),
(26, 1, 'Salceda, Juliana Alexa', 'Present', '2024-02-22 22:58:55', '2022-6972-77466'),
(27, 1, 'Serra, Lee Carter', 'Present', '2024-02-22 22:58:55', '2022-1987-66915'),
(28, 1, 'Serrano, Mark Erick', 'Present', '2024-02-22 22:58:55', '2022-5939-82677'),
(29, 1, 'Vasquez, Shiela Mae ', 'Present', '2024-02-22 22:58:55', '2022-4436-89811'),
(30, 1, 'Anobling, Janna Mae', 'Present', '2024-02-23 10:43:50', '2022-4743-23173'),
(31, 1, 'Arevalo, Kristine Zyra Mae ', 'Present', '2024-02-23 10:43:50', '2022-9769-26289'),
(32, 1, 'Aringo, Bea Mae', 'Present', '2024-02-23 10:43:50', '2022-2848-97073'),
(33, 1, 'Bautista, Madel Jandra', 'Present', '2024-02-23 10:43:50', '2022-2951-44568'),
(34, 1, 'Bonayon, Arabela', 'Present', '2024-02-23 10:43:50', '2022-8250-23124'),
(35, 1, 'Bordeos, Shieralyn ', 'Present', '2024-02-23 10:43:50', '2022-4974-55555'),
(36, 1, 'Bragais, Justine', 'Present', '2024-02-23 10:43:50', '2022-3574-32403'),
(37, 1, 'Buenconsejo, Alexis', 'Present', '2024-02-23 10:43:50', '2022-3450-44180'),
(38, 1, 'Casurog, Jem ', 'Present', '2024-02-23 10:43:50', '2022-3191-58503'),
(39, 1, 'Cate, Ken Dave ', 'Present', '2024-02-23 10:43:50', '2022-5072-45887'),
(40, 1, 'Escano, Joshua Mari Francis ', 'Present', '2024-02-23 10:43:50', '2022-2363-52006'),
(41, 1, 'Habana, Ivy', 'Present', '2024-02-23 10:43:50', '2022-1319-34988'),
(42, 1, 'Leoncito, Glenysse', 'Present', '2024-02-23 10:43:50', '2022-7280-54951'),
(43, 1, 'Lobos, Althea ', 'Present', '2024-02-23 10:43:50', '2022-6288-68920'),
(44, 1, 'Loneza, Walton John ', 'Present', '2024-02-23 10:43:50', '2022-2920-98466'),
(45, 1, 'Madara, Allysa ', 'Present', '2024-02-23 10:43:50', '2022-4552-96399'),
(46, 1, 'Moises, Elton John', 'Present', '2024-02-23 10:43:50', '2022-9110-55459'),
(47, 1, 'Moscoso, Aila', 'Excused', '2024-02-23 10:43:50', '2022-3617-50142'),
(48, 1, 'Nas, Jonalyn ', 'Present', '2024-02-23 10:43:50', '2022-4712-10138'),
(49, 1, 'Nasayao, Rhea May', 'Present', '2024-02-23 10:43:50', '2022-4256-69735'),
(50, 1, 'Padua, Gwen ', 'Present', '2024-02-23 10:43:50', '2022-7753-24136'),
(51, 1, 'Pocaan, Lhea ', 'Present', '2024-02-23 10:43:50', '2022-7321-46478'),
(52, 1, 'Realisan, Jamaica', 'Present', '2024-02-23 10:43:50', '2022-9082-60646'),
(53, 1, 'Sabater, Christine Mae', 'Present', '2024-02-23 10:43:50', '2022-7033-68287'),
(54, 1, 'Saculo, Francyn Essy ', 'Present', '2024-02-23 10:43:50', '2022-7224-93591'),
(55, 1, 'Salceda, Juliana Alexa', 'Present', '2024-02-23 10:43:50', '2022-6972-77466'),
(56, 1, 'Serra, Lee Carter', 'Present', '2024-02-23 10:43:50', '2022-1987-66915'),
(57, 1, 'Serrano, Mark Erick', 'Present', '2024-02-23 10:43:50', '2022-5939-82677'),
(58, 1, 'Vasquez, Shiela Mae ', 'Present', '2024-02-23 10:43:50', '2022-4436-89811'),
(59, 1, 'Anobling, Janna Mae', 'Present', '2024-02-23 11:03:46', '2022-4743-23173'),
(60, 1, 'Arevalo, Kristine Zyra Mae ', 'Present', '2024-02-23 11:03:46', '2022-9769-26289'),
(61, 1, 'Aringo, Bea Mae', 'Absent', '2024-02-23 11:03:46', '2022-2848-97073'),
(62, 1, 'Bautista, Madel Jandra', 'Present', '2024-02-23 11:03:46', '2022-2951-44568'),
(63, 1, 'Bonayon, Arabela', 'Present', '2024-02-23 11:03:46', '2022-8250-23124'),
(64, 1, 'Bordeos, Shieralyn ', 'Present', '2024-02-23 11:03:46', '2022-4974-55555'),
(65, 1, 'Bragais, Justine', 'Present', '2024-02-23 11:03:46', '2022-3574-32403'),
(66, 1, 'Buenconsejo, Alexis', 'Present', '2024-02-23 11:03:46', '2022-3450-44180'),
(67, 1, 'Casurog, Jem ', 'Present', '2024-02-23 11:03:46', '2022-3191-58503'),
(68, 1, 'Cate, Ken Dave ', 'Present', '2024-02-23 11:03:46', '2022-5072-45887'),
(69, 1, 'Escano, Joshua Mari Francis ', 'Present', '2024-02-23 11:03:46', '2022-2363-52006'),
(70, 1, 'Habana, Ivy', 'Present', '2024-02-23 11:03:46', '2022-1319-34988'),
(71, 1, 'Leoncito, Glenysse', 'Present', '2024-02-23 11:03:46', '2022-7280-54951'),
(72, 1, 'Lobos, Althea ', 'Present', '2024-02-23 11:03:46', '2022-6288-68920'),
(73, 1, 'Loneza, Walton John ', 'Present', '2024-02-23 11:03:46', '2022-2920-98466'),
(74, 1, 'Madara, Allysa ', 'Present', '2024-02-23 11:03:46', '2022-4552-96399'),
(75, 1, 'Moises, Elton John', 'Present', '2024-02-23 11:03:46', '2022-9110-55459'),
(76, 1, 'Moscoso, Aila', 'Present', '2024-02-23 11:03:46', '2022-3617-50142'),
(77, 1, 'Nas, Jonalyn ', 'Present', '2024-02-23 11:03:46', '2022-4712-10138'),
(78, 1, 'Nasayao, Rhea May', 'Present', '2024-02-23 11:03:46', '2022-4256-69735'),
(79, 1, 'Padua, Gwen ', 'Present', '2024-02-23 11:03:46', '2022-7753-24136'),
(80, 1, 'Pocaan, Lhea ', 'Present', '2024-02-23 11:03:46', '2022-7321-46478'),
(81, 1, 'Realisan, Jamaica', 'Present', '2024-02-23 11:03:46', '2022-9082-60646'),
(82, 1, 'Sabater, Christine Mae', 'Present', '2024-02-23 11:03:46', '2022-7033-68287'),
(83, 1, 'Saculo, Francyn Essy ', 'Present', '2024-02-23 11:03:46', '2022-7224-93591'),
(84, 1, 'Salceda, Juliana Alexa', 'Present', '2024-02-23 11:03:46', '2022-6972-77466'),
(85, 1, 'Serra, Lee Carter', 'Present', '2024-02-23 11:03:46', '2022-1987-66915'),
(86, 1, 'Serrano, Mark Erick', 'Present', '2024-02-23 11:03:46', '2022-5939-82677'),
(87, 1, 'Vasquez, Shiela Mae ', 'Present', '2024-02-23 11:03:46', '2022-4436-89811'),
(88, 2, 'Anobling, Janna Mae', 'Present', '2024-02-28 19:48:34', '2022-4743-23173'),
(89, 2, 'Arevalo, Kristine Zyra Mae ', 'Present', '2024-02-28 19:48:34', '2022-9769-26289'),
(90, 2, 'Aringo, Bea Mae', 'Present', '2024-02-28 19:48:34', '2022-2848-97073'),
(91, 2, 'Bautista, Madel Jandra', 'Present', '2024-02-28 19:48:34', '2022-2951-44568'),
(92, 2, 'Bonayon, Arabela', 'Present', '2024-02-28 19:48:34', '2022-8250-23124'),
(93, 2, 'Bordeos, Shieralyn ', 'Present', '2024-02-28 19:48:34', '2022-4974-55555'),
(94, 2, 'Bragais, Justine', 'Present', '2024-02-28 19:48:34', '2022-3574-32403'),
(95, 2, 'Buenconsejo, Alexis', 'Present', '2024-02-28 19:48:34', '2022-3450-44180'),
(96, 2, 'Casurog, Jem ', 'Present', '2024-02-28 19:48:34', '2022-3191-58503'),
(97, 2, 'Cate, Ken Dave ', 'Present', '2024-02-28 19:48:34', '2022-5072-45887'),
(98, 2, 'Escano, Joshua Mari Francis ', 'Present', '2024-02-28 19:48:34', '2022-2363-52006'),
(99, 2, 'Habana, Ivy', 'Present', '2024-02-28 19:48:34', '2022-1319-34988'),
(100, 2, 'Leoncito, Glenysse', 'Present', '2024-02-28 19:48:34', '2022-7280-54951'),
(101, 2, 'Lobos, Althea ', 'Present', '2024-02-28 19:48:34', '2022-6288-68920'),
(102, 2, 'Loneza, Walton John ', 'Present', '2024-02-28 19:48:34', '2022-2920-98466'),
(103, 2, 'Madara, Allysa ', 'Present', '2024-02-28 19:48:34', '2022-4552-96399'),
(104, 2, 'Moises, Elton John', 'Present', '2024-02-28 19:48:34', '2022-9110-55459'),
(105, 2, 'Moscoso, Aila', 'Present', '2024-02-28 19:48:34', '2022-3617-50142'),
(106, 2, 'Nas, Jonalyn ', 'Present', '2024-02-28 19:48:34', '2022-4712-10138'),
(107, 2, 'Nasayao, Rhea May', 'Present', '2024-02-28 19:48:34', '2022-4256-69735'),
(108, 2, 'Padua, Gwen ', 'Present', '2024-02-28 19:48:34', '2022-7753-24136'),
(109, 2, 'Pocaan, Lhea ', 'Absent', '2024-02-28 19:48:34', '2022-7321-46478'),
(110, 2, 'Realisan, Jamaica', 'Present', '2024-02-28 19:48:34', '2022-9082-60646'),
(111, 2, 'Sabater, Christine Mae', 'Present', '2024-02-28 19:48:34', '2022-7033-68287'),
(112, 2, 'Saculo, Francyn Essy ', 'Present', '2024-02-28 19:48:34', '2022-7224-93591'),
(113, 2, 'Salceda, Juliana Alexa', 'Present', '2024-02-28 19:48:34', '2022-6972-77466'),
(114, 2, 'Serra, Lee Carter', 'Present', '2024-02-28 19:48:34', '2022-1987-66915'),
(115, 2, 'Serrano, Mark Erick', 'Present', '2024-02-28 19:48:34', '2022-5939-82677'),
(116, 2, 'Vasquez, Shiela Mae ', 'Present', '2024-02-28 19:48:34', '2022-4436-89811');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `status` enum('Present','Absent','Late','Excused') DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `bu_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_records`
--

CREATE TABLE `deleted_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `bu_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deleted_records`
--

INSERT INTO `deleted_records` (`id`, `user_id`, `student_name`, `status`, `date`, `bu_no`) VALUES
(1, 1, 'Cristine Llenaresas', 'Present', '2024-02-13 11:04:44', '2022-9840-41970'),
(2, 1, 'Walton Loneza', 'Absent', '2024-02-13 11:04:44', '2022-2920-98466'),
(3, 1, 'Cristine Llenaresas', 'Late', '2024-02-13 11:05:00', '2022-9840-41970'),
(4, 1, 'Walton Loneza', 'Excused', '2024-02-13 11:05:00', '2022-2920-98466'),
(5, 1, 'Cristine Llenaresas', 'Excused', '2024-02-13 11:05:09', '2022-9840-41970'),
(6, 1, 'Walton Loneza', 'Absent', '2024-02-13 11:05:09', '2022-2920-98466'),
(7, 1, 'Cristine Llenaresas', 'Absent', '2024-02-22 09:43:37', '2022-9840-41970'),
(8, 1, 'Walton Loneza', 'Present', '2024-02-22 09:43:37', '2022-2920-98466'),
(9, 1, 'Cristine Llenaresas', 'Present', '2024-02-13 09:57:06', '2022-9840-41970'),
(10, 1, 'Walton Loneza', 'Present', '2024-02-13 09:57:06', '2022-2920-98466');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `bu_no` varchar(20) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `status`, `bu_no`, `img`) VALUES
(22, 'Anobling, Janna Mae', 'active', '2022-4743-23173', ''),
(23, 'Arevalo, Kristine Zyra Mae ', 'active', '2022-9769-26289', ''),
(24, 'Aringo, Bea Mae', 'active', '2022-2848-97073', ''),
(25, 'Bautista, Madel Jandra', 'active', '2022-2951-44568', ''),
(26, 'Bonayon, Arabela', 'active', '2022-8250-23124', ''),
(27, 'Bordeos, Shieralyn ', 'active', '2022-4974-55555', ''),
(28, 'Bragais, Justine', 'active', '2022-3574-32403', ''),
(29, 'Buenconsejo, Alexis', 'active', '2022-3450-44180', ''),
(30, 'Casurog, Jem ', 'active', '2022-3191-58503', ''),
(31, 'Cate, Ken Dave ', 'active', '2022-5072-45887', ''),
(32, 'Escano, Joshua Mari Francis ', 'active', '2022-2363-52006', ''),
(33, 'Habana, Ivy', 'active', '2022-1319-34988', ''),
(34, 'Leoncito, Glenysse', 'active', '2022-7280-54951', ''),
(35, 'Lobos, Althea ', 'active', '2022-6288-68920', ''),
(36, 'Loneza, Walton John ', 'active', '2022-2920-98466', 'stud_img/20230822_232646_0000.png'),
(37, 'Madara, Allysa ', 'active', '2022-4552-96399', 'stud_img/ly.jpg'),
(38, 'Moises, Elton John', 'active', '2022-9110-55459', ''),
(39, 'Moscoso, Aila', 'active', '2022-3617-50142', ''),
(40, 'Nas, Jonalyn ', 'active', '2022-4712-10138', ''),
(41, 'Nasayao, Rhea May', 'active', '2022-4256-69735', ''),
(42, 'Padua, Gwen ', 'active', '2022-7753-24136', ''),
(43, 'Pocaan, Lhea ', 'active', '2022-7321-46478', ''),
(44, 'Realisan, Jamaica', 'active', '2022-9082-60646', ''),
(45, 'Sabater, Christine Mae', 'active', '2022-7033-68287', ''),
(46, 'Saculo, Francyn Essy ', 'active', '2022-7224-93591', 'img/essy.jpg'),
(47, 'Salceda, Juliana Alexa', 'active', '2022-6972-77466', ''),
(48, 'Serra, Lee Carter', 'active', '2022-1987-66915', ''),
(49, 'Serrano, Mark Erick', 'active', '2022-5939-82677', ''),
(50, 'Vasquez, Shiela Mae ', 'active', '2022-4436-89811', ''),
(51, 'Jimenez, Jude Dwight Oscar ', 'active', '2022-5054-93340', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `fullname`, `user_email`, `password`, `user_type`, `status`) VALUES
(1, 'bu', 'bicol university', 'bu@bicol-u.edu.ph', 'bu', 'user', 'active'),
(2, 'Dum', 'Dummy Account', 'dummyacc@gmail.com', '12345', 'user', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deleted_records`
--
ALTER TABLE `deleted_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deleted_records`
--
ALTER TABLE `deleted_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
