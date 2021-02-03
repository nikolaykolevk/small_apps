-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2019 at 02:36 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `education`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogs`
--

CREATE TABLE `adminlogs` (
  `ID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL,
  `action` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adminlogs`
--

INSERT INTO `adminlogs` (`ID`, `adminID`, `action`, `date`, `message`) VALUES
(31, 1, 1, '2019-12-15 11:46:01', 'Changed status of order number 1 for user: nikolaykolevk@gmail.com to DELIVERED'),
(32, 1, 1, '2019-12-15 11:52:08', 'Changed status of order number 2 for user: nikolaykolevk@gmail.com to DELIVERED'),
(33, 1, 1, '2019-12-15 11:52:21', 'Changed status of order number 1 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(34, 1, 1, '2019-12-15 12:03:32', 'Changed status of order number  for user:  to NOT DELIVERED'),
(35, 1, 1, '2019-12-15 12:24:46', 'Changed status of order number  for user:  to NOT DELIVERED'),
(36, 1, 2, '2019-12-15 13:44:36', 'Added product: Product8 Price: 39.99'),
(37, 1, 1, '2019-12-15 14:10:03', 'Changed status of order number  for user:  to NOT DELIVERED'),
(38, 1, 1, '2019-12-15 14:10:28', 'Changed status of order number  for user:  to NOT DELIVERED'),
(39, 1, 1, '2019-12-15 14:11:16', 'Changed status of order number  for user:  to NOT DELIVERED'),
(40, 1, 1, '2019-12-15 14:11:19', 'Changed status of order number  for user:  to NOT DELIVERED'),
(41, 1, 1, '2019-12-15 14:15:26', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(42, 1, 1, '2019-12-15 14:15:29', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(43, 1, 1, '2019-12-15 14:15:37', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(44, 1, 1, '2019-12-15 14:15:38', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(45, 1, 1, '2019-12-15 14:19:13', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(46, 1, 1, '2019-12-15 14:19:18', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(47, 1, 2, '2019-12-15 14:19:41', 'Added product: asd Price: asd'),
(48, 1, 3, '2019-12-15 14:20:08', 'remove product with ID3'),
(49, 1, 1, '2019-12-15 14:25:39', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(50, 1, 1, '2019-12-15 14:25:40', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(51, 1, 1, '2019-12-15 14:25:54', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(52, 1, 1, '2019-12-15 14:25:55', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(53, 1, 4, '2019-12-15 14:34:37', 'Changed status of admin with ID: 1'),
(54, 1, 4, '2019-12-15 14:34:39', 'Changed status of admin with ID: 1'),
(55, 1, 4, '2019-12-15 14:34:40', 'Changed status of admin with ID: 1'),
(56, 1, 4, '2019-12-15 14:34:42', 'Changed status of admin with ID: 1'),
(57, 1, 4, '2019-12-15 14:34:51', 'Changed status of admin with ID: 1'),
(58, 1, 4, '2019-12-15 14:35:11', 'Changed status of admin with ID: 1'),
(59, 1, 4, '2019-12-15 14:35:13', 'Changed status of admin with ID: 1'),
(60, 1, 4, '2019-12-15 14:35:15', 'Changed status of admin with ID: 2'),
(61, 1, 4, '2019-12-15 14:35:15', 'Changed status of admin with ID: 2'),
(62, 1, 4, '2019-12-15 14:35:16', 'Changed status of admin with ID: 2'),
(63, 1, 4, '2019-12-15 14:35:17', 'Changed status of admin with ID: 2'),
(64, 1, 4, '2019-12-15 14:35:18', 'Changed status of admin with ID: 2'),
(65, 1, 4, '2019-12-15 14:35:18', 'Changed status of admin with ID: 2'),
(66, 1, 4, '2019-12-15 14:35:19', 'Changed status of admin with ID: 2'),
(67, 1, 4, '2019-12-15 14:35:20', 'Changed status of admin with ID: 2'),
(68, 1, 4, '2019-12-15 14:37:49', 'Changed status of admin with ID: 1'),
(69, 1, 4, '2019-12-15 14:37:50', 'Changed status of admin with ID: 1'),
(70, 1, 5, '2019-12-15 14:41:19', 'Changed status of user with ID: 1'),
(71, 1, 5, '2019-12-15 14:41:20', 'Changed status of user with ID: 1'),
(72, 1, 5, '2019-12-15 14:41:22', 'Changed status of user with ID: 1'),
(73, 1, 5, '2019-12-15 14:41:31', 'Changed status of user with ID: 5'),
(74, 1, 4, '2019-12-15 14:44:15', 'Changed status of admin : nikolaykolevk'),
(75, 1, 4, '2019-12-15 14:45:28', 'Changed status of admin : nikolaykolevk to Blocked'),
(76, 1, 4, '2019-12-15 14:45:32', 'Changed status of admin : nikolaykolevk to Blocked'),
(77, 1, 4, '2019-12-15 14:45:36', 'Changed status of admin : nikolaykolevk to Not blocked'),
(78, 1, 4, '2019-12-15 14:45:38', 'Changed status of admin : nikolaykolevk to Not blocked'),
(79, 1, 4, '2019-12-15 14:46:08', 'Changed status of admin :  to Blocked'),
(80, 1, 4, '2019-12-15 14:46:31', 'Changed status of admin : nikolaykolevk to Not blocked'),
(81, 1, 4, '2019-12-15 14:46:35', 'Changed status of admin : turbo3 to Blocked'),
(82, 1, 5, '2019-12-15 14:47:41', 'Changed status of user : nikolaykolevk to Blocked'),
(83, 1, 5, '2019-12-15 14:47:42', 'Changed status of user : add to Not blocked'),
(84, 1, 5, '2019-12-15 14:47:46', 'Changed status of user : new to Not blocked'),
(85, 1, 5, '2019-12-15 14:47:55', 'Changed status of user : nikolaykolevk to Not blocked'),
(86, 1, 5, '2019-12-15 14:47:56', 'Changed status of user : add to Blocked'),
(87, 1, 5, '2019-12-15 14:47:57', 'Changed status of user : new to Blocked'),
(88, 1, 5, '2019-12-15 14:47:58', 'Changed status of user : viktor to Blocked'),
(89, 1, 6, '2019-12-15 14:57:25', 'Created new Admin: a1'),
(90, 1, 6, '2019-12-15 14:58:06', 'Created new Admin: a1'),
(91, 1, 6, '2019-12-15 14:59:03', 'Created new Admin: as'),
(92, 1, 1, '2019-12-15 15:05:42', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(93, 1, 1, '2019-12-15 15:05:44', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(94, 1, 1, '2019-12-15 15:05:50', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to Not Delivered'),
(95, 1, 1, '2019-12-15 15:05:56', 'Changed status of order number 10 for user: nikolaykolevk@gmail.com to DELIVERED'),
(96, 1, 1, '2019-12-15 15:07:19', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(97, 1, 1, '2019-12-15 15:07:30', 'Changed status of order number 10 for user: nikolaykolevk@gmail.com to DELIVERED'),
(98, 1, 1, '2019-12-15 15:08:14', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(99, 1, 1, '2019-12-15 15:08:32', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to NOT DELIVERED'),
(100, 1, 1, '2019-12-15 15:08:37', 'Changed status of order number 11 for user: nikolaykolevk@gmail.com to DELIVERED'),
(101, 1, 5, '2019-12-15 15:11:46', 'Changed status of user : nikolaykolevk to Blocked'),
(102, 1, 4, '2019-12-15 15:17:13', 'Changed status of admin : nikolaykolevk to Blocked'),
(103, 1, 5, '2019-12-15 15:19:56', 'Changed status of user : nikolaykolevk to Not blocked');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `ID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dateOfCreation` date NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`ID`, `username`, `password`, `email`, `dateOfCreation`, `status`) VALUES
(1, 'nikolaykolevk', '8c007b0c5f9099e5b09c74c1db61ca7a', 'nikolaykolevk@gmail.com', '2019-12-13', 1),
(2, 'turbo3', 'e10adc3949ba59abbe56e057f20f883e', 'niki@asd.bg', '2019-12-14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `User Order Number` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `userID`, `productID`, `User Order Number`, `quantity`, `status`, `date`) VALUES
(12, 1, 3, 1, 111, 0, '2019-12-15 11:58:54'),
(13, 1, 4, 1, 8, 0, '2019-12-15 11:58:54'),
(14, 1, 3, 2, 111, 0, '2019-12-15 11:58:54'),
(15, 1, 4, 2, 8, 0, '2019-12-15 11:58:54'),
(16, 1, 4, 3, 10, 1, '2019-12-15 11:58:54'),
(17, 1, 4, 4, 1, 1, '2019-12-15 11:58:54'),
(18, 1, 2, 8, 1, 1, '2019-12-15 11:58:54'),
(19, 1, 4, 9, 1, 1, '2019-12-15 11:58:54'),
(20, 1, 3, 10, 1, 1, '2019-12-15 11:58:54'),
(21, 1, 4, 11, 1, 1, '2019-12-15 11:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `name` text NOT NULL,
  `price` double NOT NULL,
  `imgSrc` text NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 4,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `category`, `name`, `price`, `imgSrc`, `rating`, `description`) VALUES
(4, 1, 'Adidas f50', 249.98, 'http://cdn.shopify.com/s/files/1/2333/3355/products/20190713_212126_1024x1024.jpg?v=1563239652', 2, 'Обувките на меси'),
(6, 2, 'Product4', 40, '/education/issue7/storeApp/images/tmp2.jpg', 4, 'mnogo e qk'),
(7, 5, 'produkt 78', 49.9, '/education/issue7/storeApp/images/tmp3.jpg', 2, 'ne znam veche'),
(9, 4, 'Product8', 39.99, '/education/issue7/storeApp/images/tmp.jpeg', 5, 'nov produkt 4');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `Orders Count` int(3) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `dateOfCreation` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `Orders Count`, `status`, `dateOfCreation`) VALUES
(1, 'nikolaykolevk', 'nikolaykolevk@gmail.com', '8c007b0c5f9099e5b09c74c1db61ca7a', 11, 1, '2019-12-15'),
(2, 'asd', 'asd', '7815696ecbf1c96e6894b779456d330e', 0, 0, '2019-12-15'),
(3, 'add', 'add', '34ec78fcc91ffb1e54cd85e4a0924332', 0, 0, '2019-12-15'),
(4, 'new', 'new', '22af645d1859cb5ca6da0c484f1f37ea', 0, 0, '2019-12-15'),
(5, 'viktor', 'vik@gmail.com', '4ebf4bcfb4c1cd34f09dc04280a3379f', 0, 0, '2019-12-15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogs`
--
ALTER TABLE `adminlogs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `adminID` (`adminID`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogs`
--
ALTER TABLE `adminlogs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adminlogs`
--
ALTER TABLE `adminlogs`
  ADD CONSTRAINT `adminID` FOREIGN KEY (`adminID`) REFERENCES `admins` (`ID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `productID` FOREIGN KEY (`productID`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `userID` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
