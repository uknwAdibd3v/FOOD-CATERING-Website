-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2026 at 03:06 PM
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
-- Database: `food_caterer_new_5`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `BOOK_ID` int(11) NOT NULL,
  `CUS_ID` int(11) NOT NULL,
  `PACKAGE_ID` int(11) NOT NULL,
  `EVENT_CODE` varchar(10) NOT NULL,
  `ORDER_TOTAL` decimal(10,2) NOT NULL,
  `ORDER_DATE` date NOT NULL,
  `STAFF_ID` int(11) NOT NULL,
  `GUEST_COUNT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BOOK_ID`, `CUS_ID`, `PACKAGE_ID`, `EVENT_CODE`, `ORDER_TOTAL`, `ORDER_DATE`, `STAFF_ID`, `GUEST_COUNT`) VALUES
(1, 1, 4, 'E001', 4200.00, '2026-06-05', 1, 400),
(2, 1, 2, 'E001', 3500.00, '2026-06-06', 1, 50),
(3, 1, 3, 'E001', 1800.00, '2026-06-09', 1, 100),
(4, 2, 4, 'E001', 4200.00, '2026-06-03', 1, 200),
(5, 1, 4, 'E001', 4200.00, '2026-06-28', 1, 500),
(19, 1, 4, 'EVT-3397', 4200.00, '2026-07-07', 1, 500);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CUS_ID` int(11) NOT NULL,
  `CUS_NAME` varchar(100) NOT NULL,
  `CUS_PHONE` varchar(15) NOT NULL,
  `CUS_EMAIL` varchar(100) NOT NULL,
  `CUS_PASSWORD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CUS_ID`, `CUS_NAME`, `CUS_PHONE`, `CUS_EMAIL`, `CUS_PASSWORD`) VALUES
(1, 'cust1 new', '0123456789', 'cust1@gmail.com', '12345'),
(2, 'cust2', '0167676767', 'cust2@gmail.com', '6767'),
(9, 'Muhammad Fakrul', '01140494493', 'kasimfakrul98@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `EVENT_CODE` varchar(10) NOT NULL,
  `VENUE_ID` int(11) NOT NULL,
  `EVENT_DESC` varchar(255) NOT NULL,
  `EVENT_SESSION` varchar(100) NOT NULL,
  `EVENT_DATE` date NOT NULL,
  `STAFF_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`EVENT_CODE`, `VENUE_ID`, `EVENT_DESC`, `EVENT_SESSION`, `EVENT_DATE`, `STAFF_ID`) VALUES
('E001', 2, 'Wedding', '10:00AM - 4:00PM', '2026-10-01', 1),
('EVT-3397', 2, 'Dinner', 'Evening: 5:00 PM - 11:00 PM', '2026-07-28', 1),
('EVT-5607', 2, 'Birthday', 'Morning: 10:00 AM - 2:00 PM', '2026-07-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `PACKAGE_ID` int(11) NOT NULL,
  `PACKAGE_DESC` varchar(255) NOT NULL,
  `PACKAGE_PRICE` decimal(10,2) NOT NULL,
  `PACKAGE_PAX` int(11) NOT NULL,
  `STAFF_ID` int(11) NOT NULL,
  `PACKAGE_NAME` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`PACKAGE_ID`, `PACKAGE_DESC`, `PACKAGE_PRICE`, `PACKAGE_PAX`, `STAFF_ID`, `PACKAGE_NAME`) VALUES
(2, 'Suitable for small to medium events. Includes nasi minyak, ayam masak merah, daging black pepper, dalca sayur, acar jelatah, papadom, sirap ais, and mineral water', 3500.00, 150, 1, 'Warisan Istimewa'),
(3, 'Suitable for medium to large events. Includes nasi minyak, nasi putih, ayam masak merah, daging rendang, ikan sweet sour, dalca sayur, acar jelatah, papadom, sirap ais, orange cordial, mineral water, and mini apam balik', 1800.00, 100, 1, 'Santapan Keluarga'),
(4, 'Suitable for large or premium events. Includes nasi minyak, nasi beriani, ayam percik, daging rendang, kambing kurma, ikan sweet sour, dalca sayur, acar jelatah, papadom, sirap ais, orange cordial, mineral water, fruit platter, dessert and mini apam balik', 4200.00, 500, 1, 'Majlis Terindah');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PAYMENT_ID` int(11) NOT NULL,
  `BOOK_ID` int(11) NOT NULL,
  `PAYMENT_TOTAL` decimal(10,2) NOT NULL,
  `PAYMENT_DATE` date NOT NULL,
  `PAYMENT_METHOD` varchar(50) NOT NULL,
  `PAYMENT_DEPO` decimal(10,2) NOT NULL DEFAULT 0.00,
  `PAYMENT_STATUS` varchar(20) NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PAYMENT_ID`, `BOOK_ID`, `PAYMENT_TOTAL`, `PAYMENT_DATE`, `PAYMENT_METHOD`, `PAYMENT_DEPO`, `PAYMENT_STATUS`) VALUES
(1, 1, 4200.00, '2026-05-31', 'CARD', 0.00, 'CONFIRMED'),
(2, 2, 3500.00, '2026-05-31', 'CARD', 0.00, 'CONFIRMED'),
(3, 3, 1800.00, '2026-05-31', 'BANK TRANSFER', 0.00, 'CONFIRMED'),
(4, 4, 4200.00, '2026-05-31', 'BANK TRANSFER', 0.00, 'CANCELLED'),
(5, 5, 4200.00, '2026-06-11', 'PAYPAL', 0.00, 'CANCELLED'),
(19, 19, 4200.00, '2026-07-07', 'BANK TRANSFER', 0.00, 'CONFIRMED');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `STAFF_ID` int(11) NOT NULL,
  `STAFF_NAME` varchar(100) NOT NULL DEFAULT '',
  `STAFF_PHONE` varchar(20) NOT NULL DEFAULT '',
  `STAFF_EMAIL` varchar(100) NOT NULL,
  `STAFF_PASSWORD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`STAFF_ID`, `STAFF_NAME`, `STAFF_PHONE`, `STAFF_EMAIL`, `STAFF_PASSWORD`) VALUES
(1, '', '', 'staff1@gmail.com', '12345'),
(2, '', '', 'staff2@gmail.com', '12345'),
(3, '', '', 'staff3@gmail.com', '12345'),
(4, 'MUHD IZZMAN SYAHMI', '011123456', 'izzman123@gmail.com', '12345'),
(5, 'AMOGUS', '01167676767', 'amogus@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `VENUE_ID` int(11) NOT NULL,
  `VENUE_NAME` varchar(100) NOT NULL,
  `VENUE_LOCATION` varchar(150) NOT NULL,
  `VENUE_CAPACITY` int(11) NOT NULL,
  `STAFF_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`VENUE_ID`, `VENUE_NAME`, `VENUE_LOCATION`, `VENUE_CAPACITY`, `STAFF_ID`) VALUES
(2, 'Dewan Perdana', 'Cheras, Kuala Lumpur', 1500, 1),
(3, 'Dewan Sri Melati', 'Seremban, Negeri Sembilan', 1000, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BOOK_ID`),
  ADD KEY `fk_booking_customer` (`CUS_ID`),
  ADD KEY `fk_booking_package` (`PACKAGE_ID`),
  ADD KEY `fk_booking_event` (`EVENT_CODE`),
  ADD KEY `fk_booking_staff` (`STAFF_ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CUS_ID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`EVENT_CODE`),
  ADD KEY `fk_event_venue` (`VENUE_ID`),
  ADD KEY `fk_event_staff` (`STAFF_ID`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`PACKAGE_ID`),
  ADD KEY `fk_package_staff` (`STAFF_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PAYMENT_ID`),
  ADD KEY `fk_payment_book` (`BOOK_ID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`STAFF_ID`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`VENUE_ID`),
  ADD KEY `fk_venue_staff` (`STAFF_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BOOK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CUS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `PACKAGE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PAYMENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `STAFF_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `VENUE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_customer` FOREIGN KEY (`CUS_ID`) REFERENCES `customer` (`CUS_ID`),
  ADD CONSTRAINT `fk_booking_event` FOREIGN KEY (`EVENT_CODE`) REFERENCES `event` (`EVENT_CODE`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_package` FOREIGN KEY (`PACKAGE_ID`) REFERENCES `package` (`PACKAGE_ID`),
  ADD CONSTRAINT `fk_booking_staff` FOREIGN KEY (`STAFF_ID`) REFERENCES `staff` (`STAFF_ID`);

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_event_staff` FOREIGN KEY (`STAFF_ID`) REFERENCES `staff` (`STAFF_ID`),
  ADD CONSTRAINT `fk_event_venue` FOREIGN KEY (`VENUE_ID`) REFERENCES `venue` (`VENUE_ID`);

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `fk_package_staff` FOREIGN KEY (`STAFF_ID`) REFERENCES `staff` (`STAFF_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_book` FOREIGN KEY (`BOOK_ID`) REFERENCES `booking` (`BOOK_ID`);

--
-- Constraints for table `venue`
--
ALTER TABLE `venue`
  ADD CONSTRAINT `fk_venue_staff` FOREIGN KEY (`STAFF_ID`) REFERENCES `staff` (`STAFF_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
