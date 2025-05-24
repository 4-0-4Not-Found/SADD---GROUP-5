-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2024 at 03:59 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `status` enum('Present','Absent','Late','Excused') DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `bu_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `block`
--

CREATE TABLE `block` (
  `block_id` int(11) NOT NULL,
  `block_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `block`
--

INSERT INTO `block` (`block_id`, `block_name`) VALUES
(1, 'BSIS2A'),
(2, 'BSIS2B'),
(3, 'BSIS3A'),
(4, 'BSIS3B');

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

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `ID` int(11) NOT NULL,
  `bu_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `reg_id` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email_add1` varchar(255) DEFAULT NULL,
  `email_add2` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `block` varchar(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`ID`, `bu_no`, `name`, `reg_id`, `status`, `birthdate`, `gender`, `year_level`, `contact_number`, `email_add1`, `email_add2`, `img`, `block`, `user_id`) VALUES
(1, '2022-4743-23173', 'ANOBLING JANNA MAE  NIPA', '307480', 'active', '2004-06-16', 'Female', 2, '9203143063', '', 'jmna2022-4743-23173@bicol-u.edu.ph', '', '1', 1),
(2, '2022-9769-26289', 'AREVALO KRISTINE ZYRA MAE  BALDE', '289372', 'active', '2004-09-26', 'Female', 2, '9983514612', '', 'kzmba2022-9769-26289@bicol-u.edu.ph', '', '1', 1),
(3, '2022-2848-97073', 'ARINGO BEA MAE  SARAMPOTE', '307045', 'active', '2004-09-17', 'Female', 2, '9097603151', '', 'bmsa2022-2848-97073@bicol-u.edu.ph', '', '1', 1),
(4, '2022-2951-44568', 'BAUTISTA MADEL JANDRA  NIDEA', '304001', 'active', '2003-12-12', 'Female', 2, '9157323568', '', 'mjnb2022-2951-44568@bicol-u.edu.ph', '', '1', 1),
(5, '2022-8250-23124', 'BONAYON ARABELA  JEMINO', '304527', 'active', '2003-11-25', 'Female', 2, '9152626833', '', 'ajb2022-8250-23124@bicol-u.edu.ph', '', '1', 1),
(6, '2022-4974-55555', 'BORDEOS SHIERALYN  PAULAR', '304541', 'active', '2004-05-31', 'Female', 2, '9632204115', '', 'spb2022-4974-55555@bicol-u.edu.ph', '', '1', 1),
(7, '2022-3574-32403', 'BRAGAIS JUSTINE  ', '304023', 'active', '2004-05-22', 'Male', 2, '9519321525', '', 'jb2022-3574-32403@bicol-u.edu.ph', '', '1', 1),
(8, '2022-3450-44180', 'BUENCONSEJO ALEXIS  FRONDOSO', '308243', 'active', '2003-04-28', 'Male', 2, '9636581634', '', 'afb2022-3450-44180@bicol-u.edu.ph', '', '1', 1),
(9, '2022-3191-58503', 'CASUROG JEM  NAVARRO', '304073', 'active', '2003-11-06', 'Female', 2, '9107366307', '', 'jnc2022-3191-58503@bicol-u.edu.ph', '', '1', 1),
(10, '2022-5072-45887', 'CATE KEN DAVE  AREVALO', '304418', 'active', '2003-10-07', 'Male', 2, '9453268955', '', 'kdac2022-5072-45887@bicol-u.edu.ph', '', '1', 1),
(11, '2022-2363-52006', 'ESCA�O JOSHUA MARI FRANCIS  TAGUM', '292742', 'active', '2003-05-03', 'Male', 2, '9386089939', '', 'jmfte2022-2363-52006@bicol-u.edu.ph', '', '1', 1),
(12, '2022-1319-34988', 'HABANA IVY  PORNILLOS', '307482', 'active', '2002-11-25', 'Female', 2, '9636250154', '', 'iph2022-1319-34988@bicol-u.edu.ph', '', '1', 1),
(13, '2022-7280-54951', 'LEONCITO GLENYSSE  ESPANTO', '304542', 'active', '2004-03-20', 'Female', 2, '9387173601', '', 'gel2022-7280-54951@bicol-u.edu.ph', '', '1', 1),
(14, '2022-6288-68920', 'LOBOS ALTHEA  RELAO', '304062', 'active', '2004-02-11', 'Female', 2, '9613559041', '', 'arl2022-6288-68920@bicol-u.edu.ph', '', '1', 1),
(15, '2022-2920-98466', 'LONEZA WALTON JOHN  LISABA', '308073', 'active', '2004-02-24', 'Male', 2, '9107171456', '', 'wjll2022-2920-98466@bicol-u.edu.ph', '', '1', 1),
(16, '2022-4552-96399', 'MADARA ALLYSA  GARAIS', '307170', 'active', '2004-12-26', 'Female', 2, '9384484424', '', 'agm2022-4552-96399@bicol-u.edu.ph', '', '1', 1),
(17, '2022-9110-55459', 'MOISES ELTON JOHN  BAGASALA', '308067', 'active', '2003-10-29', 'Male', 2, '9274788602', '', 'ejbm2022-9110-55459@bicol-u.edu.ph', '', '1', 1),
(18, '2022-3617-50142', 'MOSCOSO AILA  OPERIO', '307955', 'active', '2003-04-06', 'Female', 2, '9268403514', '', 'aom2022-3617-50142@bicol-u.edu.ph', '', '1', 1),
(19, '2022-4712-10138', 'NAS JONALYN  BOBIS', '304426', 'active', '2004-03-01', 'Female', 2, '9487553560', '', 'jbn2022-4712-10138@bicol-u.edu.ph', '', '1', 1),
(20, '2022-4256-69735', 'NASAYAO RHEA MAY  IBA�EZ', '307075', 'active', '2004-01-29', 'Female', 2, '9612281218', '', 'rmin2022-4256-69735@bicol-u.edu.ph', '', '1', 1),
(21, '2022-7753-24136', 'PADUA GWEN  CAMASIS', '307951', 'active', '2003-08-14', 'Female', 2, '9171875290', '', 'gcp2022-7753-24136@bicol-u.edu.ph', '', '1', 1),
(22, '2022-7321-46478', 'POCAAN LHEA  PALACIO', '292527', 'active', '2003-07-19', 'Female', 2, '9914688600', '', 'lpp2022-7321-46478@bicol-u.edu.ph', '', '1', 1),
(23, '2022-9082-60646', 'REALISAN JAMAICA  QUINTO', '307483', 'active', '2003-11-04', 'Female', 2, '9654817782', '', 'jqr2022-9082-60646@bicol-u.edu.ph', '', '1', 1),
(24, '2021-2867-10076', 'RODRIGUEZ JAZPER    CARDINO', '311890', 'active', '2003-03-10', 'Male', 2, '9663262858', '', 'jazpercardino.rodriguez@bicol-u.edu.ph', '', '1', 1),
(25, '2022-7033-68287', 'SABATER CHRISTINE MAE  REGORIS', '304427', 'active', '2004-07-31', 'Female', 2, '9512192542', '', 'cmrs2022-7033-68287@bicol-u.edu.ph', '', '1', 1),
(26, '2022-7224-93591', 'SACULO FRANCYN ESSY  MANJARES', '304521', 'active', '2002-11-19', 'Female', 2, '9637143422', '', 'fems2022-7224-93591@bicol-u.edu.ph', '', '1', 1),
(27, '2022-6972-77466', 'SALCEDA JULIANA ALEXA  CABAWATAN', '292728', 'active', '2004-07-20', 'Female', 2, '9208586992', '', 'jacs2022-6972-77466@bicol-u.edu.ph', '', '1', 1),
(28, '2022-5813-25653', 'SAN JUAN SHAINE  SORIA', '304039', 'active', '2003-05-07', 'Female', 2, '9202234319', '', 'sss2022-5813-25653@bicol-u.edu.ph', '', '1', 1),
(29, '2022-1987-66915', 'SERRA LEE CARTER  ALCOVENDAS', '307952', 'active', '2004-03-16', 'Male', 2, '9561581229', '', 'lcas2022-1987-66915@bicol-u.edu.ph', '', '1', 1),
(30, '2022-5939-82677', 'SERRANO MARK ERICK  PADUA', '304042', 'active', '2003-12-05', 'Male', 2, '9456823067', '', 'meps2022-5939-82677@bicol-u.edu.ph', '', '1', 1),
(31, '2022-4436-89811', 'VASQUEZ SHIELA MAE  ABONITA', '304445', 'active', '2004-02-12', 'Female', 2, '9701452624', '', 'smav2022-4436-89811@bicol-u.edu.ph', '', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `fullname`, `email`, `password`, `user_status`) VALUES
(1, 'Walton', 'Walton Loneza', 'walton@gmail.com', '$2y$10$FQPDqXCfBDTbuaT/W998Ue4AtFCo7CQIGvHeF41fq2YT.fNz0Pszm', 'active'),
(2, 'walts', 'Walton Loneza', 'walter@gmail.com', '$2y$10$5qe1Ro2PIJSqBH0S67v7C.lXk4aI0XsX9jSC4y8rao4tezvG2zDOy', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `block`
--
ALTER TABLE `block`
  ADD PRIMARY KEY (`block_id`);

--
-- Indexes for table `deleted_records`
--
ALTER TABLE `deleted_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`ID`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `block`
--
ALTER TABLE `block`
  MODIFY `block_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deleted_records`
--
ALTER TABLE `deleted_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
