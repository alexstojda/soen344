-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2019 at 01:21 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `soen344`
--

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `cart`
(
    `id`       int(11) NOT NULL AUTO_INCREMENT,
    `patient`  int(11) NOT NULL,
    `doctor`   int(11) NOT NULL,
    `room`     int(11) NOT NULL,
    `timeslot` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `cart_doctor_id_fk` (`doctor`),
    KEY `cart_patient_id_fk` (`patient`),
    KEY `cart_rooms_id_fk` (`room`),
    CONSTRAINT `cart_doctor_id_fk` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `cart_patient_id_fk` FOREIGN KEY (`patient`) REFERENCES `patient` (`id`) ON UPDATE CASCADE,
    CONSTRAINT `cart_rooms_id_fk` FOREIGN KEY (`room`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

/*!40000 ALTER TABLE `cart`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `cart`
    ENABLE KEYS */;


/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
