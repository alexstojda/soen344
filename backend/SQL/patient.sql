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
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `patient`
(
    `id`       int(11)                        NOT NULL AUTO_INCREMENT,
    `user_id`  int(11)                        NOT NULL,
    `gender`   enum ('MALE','FEMALE','OTHER') NOT NULL DEFAULT 'OTHER',
    `phone`    varchar(255)                   NOT NULL,
    `email`    varchar(255)                   NOT NULL,
    `address`  varchar(255)                   NOT NULL,
    `birthday` int(11)                        NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `patient_id_uindex` (`id`),
    UNIQUE KEY `patient_user_id_uindex` (`user_id`),
    CONSTRAINT `patient_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient`
--

/*!40000 ALTER TABLE `patient`
    DISABLE KEYS */;
INSERT INTO `patient` (`id`, `user_id`, `gender`, `phone`, `email`, `address`, `birthday`)
VALUES (1, 10, 'MALE', '5146972424', 'derrick@hotmail.com', '1234 boul. de la montagne\nMontreal, QC\nH3G1M8, CANADA',
        800726);
/*!40000 ALTER TABLE `patient`
    ENABLE KEYS */;


/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
