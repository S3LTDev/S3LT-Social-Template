-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2022 at 03:36 PM
-- Server version: 5.7.38
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vixxyapp_vixxydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `a_rarity`
--

CREATE TABLE `a_rarity` (
  `id` int(11) NOT NULL,
  `rarity` varchar(255) NOT NULL,
  `del_flag` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `a_rarity`
--

INSERT INTO `a_rarity` (`id`, `rarity`, `del_flag`) VALUES
(1, 'Special', 0),
(2, 'Basic', 0),
(3, 'Advanced', 0),
(4, 'Vixxy Launch Series', 0);

-- --------------------------------------------------------

--
-- Table structure for table `a_subscriptions`
--

CREATE TABLE `a_subscriptions` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `del_flat` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `a_subscriptions`
--

INSERT INTO `a_subscriptions` (`id`, `name`, `del_flat`) VALUES
(1, 'free', 0),
(2, 'vixxy promo', 0),
(3, 'vixxy+', 0),
(4, 'vixxy deluxe', 0);

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

CREATE TABLE `badge` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `badge` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `badge`
--

INSERT INTO `badge` (`id`, `user_id`, `badge`) VALUES
(1, '746453649974100103', 'Verified'),
(2, '746453649974100103', 'Moderator'),
(3, '746453649974100103', 'Vixxy Deluxe'),
(4, '746453649974100103', 'Vixxy+'),
(14, '891773010871218226', 'Beta Tester'),
(15, '891773010871218226', 'Beta Tester'),
(16, '891773010871218226', 'Beta Tester'),
(17, '891773010871218226', 'Beta Tester'),
(18, '787082561301381130', 'Beta Tester'),
(19, '733875585499136022', 'Beta Tester'),
(20, '784589656334794785', 'Beta Tester'),
(21, '784589656334794785', 'Developer'),
(22, '784589656334794785', 'Verified');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `platform` varchar(255) NOT NULL,
  `game` varchar(255) NOT NULL,
  `start_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `host` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `user_id`, `item_id`) VALUES
(3, '751830101158526996', 2),
(5, '950455202018902077', 4),
(22, '950455202018902077', 3),
(23, '950455202018902077', 3),
(24, '950455202018902077', 3),
(26, '746453649974100103', 1),
(27, '746453649974100103', 3),
(28, '746453649974100103', 4);

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `asset` varchar(2000) NOT NULL,
  `rarity_id` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `name`, `asset`, `rarity_id`, `price`) VALUES
(1, 'Hazardous Material', 'https://cdn.vixxy.app/img/widgets/png/Masked.png?raw=true', 4, 750),
(3, 'Time To Shine', 'https://cdn.vixxy.app/img/widgets/png/Active%20Item.png?raw=true', 4, 750),
(4, 'Listen Along', 'https://cdn.vixxy.app/img/widgets/png/Listening%20Along.png?raw=true', 4, 750),
(5, 'Take Off', 'https://cdn.vixxy.app/img/widgets/png/Rocket%20Item.png', 3, 250);

-- --------------------------------------------------------

--
-- Table structure for table `user_storage`
--

CREATE TABLE `user_storage` (
  `user_id` varchar(25) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_avatar` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_gems` bigint(20) DEFAULT '0',
  `user_joined_timestamp` timestamp NULL DEFAULT NULL,
  `user_activity` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `admin` tinyint(1) DEFAULT '0',
  `user_suspend` tinyint(1) DEFAULT '0',
  `display_name` varchar(255) DEFAULT NULL,
  `update_display_timestamp` timestamp NULL DEFAULT NULL,
  `subscription_id` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `user_storage`
--

INSERT INTO `user_storage` (`user_id`, `user_name`, `user_avatar`, `access_token`, `session_id`, `user_gems`, `user_joined_timestamp`, `user_activity`, `admin`, `user_suspend`, `display_name`, `update_display_timestamp`, `subscription_id`) VALUES
('733875585499136022', 'fun54658#5327', '4985c04f5520012cf12b4c386d84e415', 'O3IZQuGTpkSy8PORfRYvEi4hiOTV1p', '05982d85115176ec300b0fc427f913b7', 0, '2022-06-05 15:22:07', '2022-06-05 15:32:15', 0, 0, 'fun54658', '2022-06-05 15:23:39', 1),
('746453649974100103', 'MaxxD99#3396', '65427b0a2c4b22a572bdb064690a9e04', 'yS2Z0wUUFfKKTU36d9vID2AKEMVRIn', '52760bc73e47ab6134e8fa5f8b3ef911', 68999995650, '2022-04-26 09:39:08', '2022-06-05 15:30:57', 1, 0, 'MaxxIsCool', '2022-05-19 16:04:50', 1),
('784589656334794785', 'DJKeiran#6648', 'cb548d28d671acd345099a7abc4b77b8', 'eNjSxSXAc3xdTLFCAvZHWPFTDAUyWc', '175dr8jv4k669ic3uin45cb9qd', 69, NULL, '2022-06-05 15:29:43', 1, 0, 'DJkeiran', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `a_rarity`
--
ALTER TABLE `a_rarity`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `a_subscriptions`
--
ALTER TABLE `a_subscriptions`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `index` (`user_id`) USING BTREE;

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `user_storage`
--
ALTER TABLE `user_storage`
  ADD PRIMARY KEY (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `a_rarity`
--
ALTER TABLE `a_rarity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `badge`
--
ALTER TABLE `badge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
