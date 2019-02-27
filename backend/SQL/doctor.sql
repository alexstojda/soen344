-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2019 at 11:57 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

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

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `doctor`
(
    `id`            int(11)      NOT NULL AUTO_INCREMENT,
    `user_id`       int(11)      NOT NULL,
    `permit_number` int(7)       NOT NULL,
    `specialty`     varchar(255) NOT NULL,
    `city`          varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `doctor_id_uindex` (`id`),
    UNIQUE KEY `doctor_permit_number_uindex` (`permit_number`),
    KEY `doctor_user_id_fk` (`user_id`),
    CONSTRAINT `doctor_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor`
--

/*!40000 ALTER TABLE `doctor`
    DISABLE KEYS */;
INSERT INTO `doctor` (`id`, `user_id`, `permit_number`, `specialty`, `city`)
VALUES (1, 8, 69696, 'Gynecology', 'Montreal');
/*!40000 ALTER TABLE `doctor`
    ENABLE KEYS */;


/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
