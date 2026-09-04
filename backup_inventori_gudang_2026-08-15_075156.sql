-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: inventori_gudang
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `inventori_gudang`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `inventori_gudang` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `inventori_gudang`;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `activity_logs_action_created_at_index` (`action`,`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_keluar`
--

DROP TABLE IF EXISTS `barang_keluar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_keluar` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barcode` varchar(100) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_keluar_barcode_foreign` (`barcode`),
  KEY `barang_keluar_user_id_foreign` (`user_id`),
  KEY `barang_keluar_created_by_foreign` (`created_by`),
  KEY `barang_keluar_updated_by_foreign` (`updated_by`),
  CONSTRAINT `barang_keluar_barcode_foreign` FOREIGN KEY (`barcode`) REFERENCES `master_barang` (`barcode`) ON DELETE CASCADE,
  CONSTRAINT `barang_keluar_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_keluar_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_keluar_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_keluar`
--

LOCK TABLES `barang_keluar` WRITE;
/*!40000 ALTER TABLE `barang_keluar` DISABLE KEYS */;
INSERT INTO `barang_keluar` VALUES (1,'BRG638708750684',1,'2026-03-07','untuk perumahan PKS','2026-03-06 19:43:28','2026-03-06 19:43:28',NULL,NULL,NULL,NULL),(2,'BRG587228042238',1,'2026-03-07','U/ IT SUPPORT','2026-03-06 20:50:16','2026-03-06 20:50:16',NULL,NULL,NULL,NULL),(3,'BRG638708750684',1,'2026-03-09','U/ BHMS','2026-03-08 20:31:08','2026-03-08 20:31:08',NULL,NULL,NULL,NULL),(4,'BRG636844663424',1,'2026-03-10','U/ POLIBUN','2026-03-10 01:58:09','2026-03-10 01:58:09',NULL,NULL,NULL,NULL),(5,'BRG636361073688',1,'2026-03-10','U/ POLIBUN','2026-03-10 01:58:36','2026-03-10 01:58:36',NULL,NULL,NULL,NULL),(6,'BRG653760095739',1,'2026-03-10','U/ KOPRASI KEBUN 2','2026-03-10 02:09:21','2026-03-10 02:09:21',NULL,NULL,NULL,NULL),(7,'BRG638708750684',1,'2026-03-10','U/ KOPRASI KEBUN 2','2026-03-10 02:10:13','2026-03-10 02:10:13',NULL,NULL,NULL,NULL),(8,'6972775380172',1,'2026-03-12','U/ KOPRASI KEBUN 2','2026-03-11 17:13:26','2026-03-11 17:13:26',NULL,NULL,NULL,NULL),(9,'BRG653760095739',1,'2026-03-13','percobaan','2026-03-12 17:27:33','2026-03-12 17:27:33',NULL,NULL,NULL,NULL),(10,'8885007028422',1,'2026-03-13','U/ KEBUN 1','2026-03-12 17:37:04','2026-03-12 17:37:04',NULL,NULL,NULL,NULL),(11,'BRG640638782065',1,'2026-03-16','U/ IT SUPPORT','2026-03-16 00:33:50','2026-03-16 00:33:50',NULL,NULL,NULL,NULL),(12,'BRG529010091667',2,'2026-03-17','U/ AFD 5','2026-03-16 20:22:27','2026-03-16 20:22:27',NULL,NULL,NULL,NULL),(13,'8885007028422',1,'2026-03-17','U/ KEBUN 1','2026-03-16 21:39:21','2026-03-16 21:39:21',NULL,NULL,NULL,NULL),(14,'BRG634721273339',1,'2026-03-25','U/ OPRASIONAL IT','2026-03-24 18:02:52','2026-03-24 18:02:52',NULL,NULL,NULL,NULL),(15,'4895093306261',1,'2026-03-31','untuk printer Logistik','2026-03-30 17:40:01','2026-03-30 17:40:01',NULL,4,NULL,NULL),(16,'BRG058783212744',1,'2026-04-04','U/ CP 5','2026-04-04 00:42:30','2026-04-04 00:42:30',NULL,4,NULL,NULL),(17,'BRG639486321852',1,'2026-04-06',NULL,'2026-04-05 17:14:27','2026-04-05 17:14:27',NULL,4,NULL,NULL),(18,'BRG588846009913',1,'2026-04-06',NULL,'2026-04-05 17:15:06','2026-04-05 17:15:06',NULL,4,NULL,NULL),(19,'BRG529010091667',4,'2026-04-06','untuk cas aki bekas cp 5','2026-04-06 01:08:02','2026-04-06 01:08:02',NULL,4,NULL,NULL),(20,'4977766748155',1,'2026-04-07','untuk kantor kebun 2','2026-04-06 18:19:38','2026-04-06 18:19:38',NULL,4,NULL,NULL),(21,'4977766748148',1,'2026-04-07','untuk kebun 2','2026-04-06 18:20:50','2026-04-06 18:20:50',NULL,4,NULL,NULL),(22,'8885007028422',3,'2026-04-07','untuk printer bhms','2026-04-06 21:27:42','2026-04-06 21:27:42',NULL,4,NULL,NULL),(23,'BRG606074191253',1,'2026-04-11',NULL,'2026-04-10 19:03:17','2026-04-10 19:03:17',NULL,4,NULL,NULL),(24,'BRG061840819969',1,'2026-04-11',NULL,'2026-04-10 19:03:30','2026-04-10 19:03:30',NULL,4,NULL,NULL),(25,'6922794744226',10,'2026-04-15','untuk operasional IT','2026-04-15 00:25:54','2026-04-15 00:25:54',NULL,4,NULL,NULL),(26,'BRG634721273339',1,'2026-04-15','untuk operasional  IT','2026-04-15 00:26:23','2026-04-15 00:26:23',NULL,4,NULL,NULL),(27,'BRG636844663424',2,'2026-04-17','untuk polibun','2026-04-16 17:02:32','2026-04-16 17:02:32',NULL,4,NULL,NULL),(28,'6922794735231',1,'2026-04-18','untuk ruang meeting','2026-04-17 18:53:59','2026-04-17 18:53:59',NULL,4,NULL,NULL),(29,'8885007028422',1,'2026-04-17','untuk keuangan','2026-04-17 18:55:06','2026-04-17 18:55:06',NULL,4,NULL,NULL),(30,'BRG657476721659',1,'2026-04-18','untuk penggantian milik Pak Ade','2026-04-17 18:57:18','2026-04-17 18:57:18',NULL,4,NULL,NULL),(31,'8885007028484',1,'2026-04-21','untuk logistik','2026-04-20 23:49:49','2026-04-20 23:49:49',NULL,4,NULL,NULL),(32,'8885007028460',1,'2026-04-21','untuk logistik','2026-04-20 23:50:14','2026-04-20 23:50:14',NULL,4,NULL,NULL),(33,'8885007028422',1,'2026-04-24','untuk pks/mill','2026-04-23 19:41:40','2026-04-23 19:41:40',NULL,4,NULL,NULL),(34,'BRG653760095739',1,'2026-04-23','untuk operasional IT','2026-04-23 19:43:01','2026-04-23 19:43:01',NULL,4,NULL,NULL),(35,'8885007028422',1,'2026-04-24','untuk cdo','2026-04-24 20:19:26','2026-04-24 20:19:26',NULL,4,NULL,NULL),(36,'BRG422506383850',2,'2026-04-28','untuk polibun','2026-04-27 19:11:51','2026-04-27 19:11:51',NULL,4,NULL,NULL),(37,'BRG543104445003',2,'2026-04-28','untuk polibun','2026-04-27 19:12:09','2026-04-27 19:12:09',NULL,4,NULL,NULL),(38,'BRG636844663424',1,'2026-04-28','untuk polibun','2026-04-27 21:19:25','2026-04-27 21:19:25',NULL,4,NULL,NULL),(39,'BRG636361073688',1,'2026-04-28','untuk polibun','2026-04-27 21:19:43','2026-04-27 21:19:43',NULL,4,NULL,NULL),(40,'BRG639486321852',4,'2026-04-30','untuk operasional IT','2026-04-29 17:27:04','2026-04-29 17:27:04',NULL,4,NULL,NULL),(41,'BRG468711699119',1,'2026-05-05','untuk operasional IT','2026-05-04 19:08:55','2026-05-04 19:08:55',NULL,4,NULL,NULL),(42,'8885007028422',1,'2026-05-07',NULL,'2026-05-06 17:35:39','2026-05-06 17:35:39',NULL,4,NULL,NULL),(43,'8885007028446',1,'2026-05-07',NULL,'2026-05-06 17:35:47','2026-05-06 17:35:47',NULL,4,NULL,NULL),(44,'BRG141659228843',1,'2026-05-04','untuk kebun 3','2026-05-06 17:37:18','2026-05-06 17:37:18',NULL,4,NULL,NULL),(45,'BRG141659228843',1,'2026-05-06','untuk backup server PKS/MILL','2026-05-06 17:37:44','2026-05-06 17:37:44',NULL,4,NULL,NULL),(46,'BRG529010091667',4,'2026-05-07','untuk isi air accu afd 12','2026-05-06 17:38:29','2026-05-06 17:38:29',NULL,4,NULL,NULL),(47,'BRG858279926362',1,'2026-05-05','Untuk operasional IT','2026-05-07 01:07:37','2026-05-07 01:07:37',NULL,4,NULL,NULL),(48,'BRG858279926362',2,'2026-05-07','Untuk kantor kebun 1','2026-05-07 01:08:20','2026-05-07 01:08:20',NULL,4,NULL,NULL),(49,'BRG587228042238',1,'2026-05-07','Untuk ruang meeting','2026-05-07 01:13:47','2026-05-07 01:13:47',NULL,4,NULL,NULL),(50,'BRG587228042238',1,'2026-05-07','Untuk operasional IT','2026-05-07 01:14:04','2026-05-07 01:14:04',NULL,4,NULL,NULL),(51,'0201600000524',1,'2026-05-07','Untuk kantor kebun 3','2026-05-07 01:20:45','2026-05-07 01:20:45',NULL,4,NULL,NULL),(52,'0095969678667',1,'2026-05-07','Untuk operasional IT','2026-05-07 01:22:51','2026-05-07 01:22:51',NULL,4,NULL,NULL),(53,'616202024171338',1,'2026-05-07','Untuk koperasi inti','2026-05-07 01:23:21','2026-05-07 01:23:21',NULL,4,NULL,NULL),(54,'BRG628944778296',3,'2026-05-07','Untuk kepala afdling 4,1,2','2026-05-07 01:33:47','2026-05-07 01:33:47',NULL,4,NULL,NULL),(55,'BRG636361073688',2,'2026-05-07','Untuk data center dan polibun','2026-05-07 01:36:39','2026-05-07 01:36:39',NULL,4,NULL,NULL),(56,'8885007028422',2,'2026-05-07','Untuk operasional it dan logistik','2026-05-07 01:50:34','2026-05-07 01:50:34',NULL,4,NULL,NULL),(57,'8885007028446',1,'2026-05-07','Untuk operasional IT','2026-05-07 01:52:02','2026-05-07 01:52:02',NULL,4,NULL,NULL),(58,'BRG662120559282',1,'2026-05-07','Untuk operasional IT','2026-05-07 02:03:28','2026-05-07 02:03:28',NULL,4,NULL,NULL),(59,'BRG529010091667',1,'2026-05-07','untuk mengisi aki afd 8','2026-05-07 02:59:31','2026-05-07 02:59:31',NULL,4,NULL,NULL),(60,'BRG789001124466',2,'2026-05-07','Untuk polibun','2026-05-07 03:16:25','2026-05-07 03:16:25',NULL,4,NULL,NULL),(69,'BRG639486321852',1,'2026-05-08','untuk afd 9','2026-05-07 19:19:28','2026-05-07 19:19:28',NULL,4,NULL,NULL),(70,'BRG657476721659',1,'2026-05-08','Untuk kepala afd 6','2026-05-08 10:55:53','2026-05-08 10:55:53',NULL,NULL,NULL,NULL),(71,'BRG657476721659',1,'2026-05-08','Untuk kepala afd 12','2026-05-08 03:58:25','2026-05-08 03:58:25',NULL,4,NULL,NULL),(72,'4909723118220',1,'2026-05-08','untuk kepala afd 6','2026-05-08 03:59:16','2026-05-08 03:59:16',NULL,4,NULL,NULL),(73,'8885007028422',1,'2026-05-09','Untuk BKE','2026-05-08 21:34:03','2026-05-08 21:34:03',NULL,4,NULL,NULL),(74,'BRG639486321852',1,'2026-05-11','Untuk box panel AFD 7','2026-05-11 01:08:52','2026-05-11 01:08:52',NULL,4,NULL,NULL),(75,'BRG639486321852',1,'2026-05-11','Untuk aki di shelter bukit HT','2026-05-11 01:09:16','2026-05-11 01:09:16',NULL,4,NULL,NULL),(76,'BRG639486321852',1,'2026-05-11','Untuk aki box panel AFD 19','2026-05-11 01:09:39','2026-05-11 01:09:39',NULL,4,NULL,NULL),(77,'BRG588846009913',1,'2026-05-15','Untuk cctv di shelter bukit HT','2026-05-14 17:10:23','2026-05-14 17:10:23',NULL,4,NULL,NULL),(78,'BRG529010091667',4,'2026-05-18','Untuk aki Pos Utama','2026-05-17 19:15:47','2026-05-17 19:15:47',NULL,4,NULL,NULL),(79,'BRG529010091667',4,'2026-05-18','Untuk aki AFD 9','2026-05-17 19:16:26','2026-05-17 19:16:26',NULL,4,NULL,NULL),(80,'BRG639486321852',1,'2026-05-18','Untuk aki CP 8','2026-05-17 21:49:17','2026-05-17 21:49:17',NULL,4,NULL,NULL),(81,'BRG789001124466',1,'2026-05-18','Untuk instalasi di Gudang BBM','2026-05-18 16:49:53','2026-05-18 16:49:53',NULL,4,NULL,NULL),(82,'8885007028422',1,'2026-05-19','Untuk printer Staff Teknik','2026-05-20 16:55:39','2026-05-20 16:55:39',NULL,4,NULL,NULL),(83,'BRG529010091667',2,'2026-05-22','Untuk Checkpoint 1','2026-05-21 17:18:51','2026-05-21 17:18:51',NULL,4,NULL,NULL),(84,'BRG657476721659',1,'2026-05-23','Untuk Bapak Pujo AFD 5','2026-05-22 20:13:17','2026-05-22 20:13:17',NULL,4,NULL,NULL),(85,'BRG529010091667',1,'2026-05-26','Untuk aki AFD 7','2026-05-25 17:43:18','2026-05-25 17:43:18',NULL,4,NULL,NULL),(86,'BRG061840819969',1,'2026-05-26','Scan dari Panda PRJ 777','2026-05-25 19:20:16','2026-05-25 19:20:16',NULL,4,NULL,NULL),(87,'BRG061840819969',1,'2026-05-26','Scan dari Panda PRJ 777','2026-05-25 19:21:56','2026-05-25 19:21:56',NULL,4,NULL,NULL),(88,'BRG061840819969',1,'2026-05-26','Quick scan dari dashboard','2026-05-25 19:39:44','2026-05-25 19:39:44',NULL,4,NULL,NULL),(89,'8885007028422',1,'2026-05-29','Untuk printer Agronomi','2026-05-28 17:52:59','2026-05-28 17:52:59',NULL,4,NULL,NULL),(90,'8885007028446',1,'2026-05-29','Untuk printer Agronomi','2026-05-28 17:53:20','2026-05-28 17:53:20',NULL,4,NULL,NULL),(91,'8885007028460',1,'2026-05-29','Untuk printer Agronomi','2026-05-28 17:53:35','2026-05-28 17:53:35',NULL,4,NULL,NULL),(92,'4977766748131',1,'2026-05-30','Untuk printer kantor Kebun 2','2026-05-29 21:15:14','2026-05-29 21:15:14',NULL,4,NULL,NULL),(93,'8885007028422',1,'2026-06-03','Untuk printer Kebun 4','2026-06-02 17:30:06','2026-06-02 17:30:06',NULL,4,NULL,NULL),(94,'8885007028446',1,'2026-06-03','Untuk printer Kebun 4','2026-06-02 17:30:25','2026-06-02 17:30:25',NULL,4,NULL,NULL),(95,'8885007028422',1,'2026-06-03','Untuk Printer EHS','2026-06-02 17:35:47','2026-06-02 17:35:47',NULL,4,NULL,NULL),(96,'8885007028484',1,'2026-06-03','Untuk Printer EHS','2026-06-02 17:36:02','2026-06-02 17:36:02',NULL,4,NULL,NULL),(97,'BRG634721273339',1,'2026-06-03','Untuk Oprasional IT','2026-06-02 17:39:04','2026-06-02 17:39:04',NULL,4,NULL,NULL),(98,'BRG587998210565',1,'2026-06-08','Untuk oprasional IT','2026-06-07 19:18:52','2026-06-07 19:18:52',NULL,4,NULL,NULL),(99,'6935364021153',1,'2026-06-08','Untuk Jaringan ke arah MILL','2026-06-07 22:39:02','2026-06-07 22:39:02',NULL,4,NULL,NULL),(100,'BRG587998210565',2,'2026-06-11','Untuk AFD 20','2026-06-10 17:33:14','2026-06-10 17:33:14',NULL,4,NULL,NULL),(101,'BRG636361073688',1,'2026-06-11','Untuk Kerani CDO','2026-06-10 17:33:55','2026-06-10 17:33:55',NULL,4,NULL,NULL),(102,'BRG639486321852',1,'2026-06-12','Untuk Aki AFD 20','2026-06-11 19:13:19','2026-06-11 19:13:19',NULL,4,NULL,NULL),(103,'BRG657476721659',1,'2026-06-12','Untuk Kepala AFD 15 Pak Hendra','2026-06-12 00:39:25','2026-06-12 00:39:25',NULL,4,NULL,NULL),(104,'6935364021153',1,'2026-06-17','Untuk Koprasi Kebun 2','2026-06-16 20:17:50','2026-06-16 20:17:50',NULL,4,NULL,NULL),(105,'BRG636361073688',1,'2026-06-17','Untuk kerani GS','2026-06-16 22:01:30','2026-06-16 22:01:30',NULL,4,NULL,NULL),(106,'6935364021153',1,'2026-06-21','rusak','2026-06-21 16:49:05','2026-06-21 16:49:05',NULL,4,NULL,NULL),(107,'8885007028422',1,'2026-06-22','Untuk kebun 3','2026-06-22 01:02:08','2026-06-22 01:02:08',NULL,4,NULL,NULL),(108,'8885007028422',1,'2026-06-22','Untuk keuangan','2026-06-22 01:05:35','2026-06-22 01:05:35',NULL,4,NULL,NULL),(109,'8885007028446',1,'2026-06-22','Untuk keuangan','2026-06-22 01:05:56','2026-06-22 01:05:56',NULL,4,NULL,NULL),(110,'8885007028460',1,'2026-06-22','Untuk keuangan','2026-06-22 01:06:20','2026-06-22 01:06:20',NULL,4,NULL,NULL),(111,'8885007028484',1,'2026-06-22','Untuk keuangan','2026-06-22 01:06:41','2026-06-22 01:06:41',NULL,4,NULL,NULL),(112,'8885007028422',2,'2026-06-23','Untuk printer kantor besar BHMS','2026-06-23 16:56:25','2026-06-23 16:56:25',NULL,4,NULL,NULL),(113,'8885007028484',1,'2026-06-23','Untuk printer kantor kebun 4','2026-06-23 16:56:52','2026-06-23 16:56:52',NULL,4,NULL,NULL),(114,'BRG636361073688',1,'2026-06-27','Untuk Admin MILL','2026-06-26 17:30:11','2026-06-26 17:30:11',NULL,6,NULL,NULL),(115,'BRG588846009913',1,'2026-06-29','Untuk Koprasi Kebun 4','2026-06-28 19:29:18','2026-06-28 19:29:18',NULL,4,NULL,NULL),(116,'BRG588846009913',1,'2026-06-29','Untuk Oprasional IT','2026-06-28 19:29:37','2026-06-28 19:29:37',NULL,4,NULL,NULL),(117,'8885007028422',1,'2026-07-01','Untuk Printer Logistik','2026-06-30 17:06:26','2026-06-30 17:06:26',NULL,4,NULL,NULL),(118,'8885007028484',1,'2026-07-01','Untuk Printer Logistik','2026-06-30 17:06:45','2026-06-30 17:06:45',NULL,4,NULL,NULL),(119,'8885007028460',1,'2026-07-01','Untuk printer logistik','2026-06-30 17:07:01','2026-06-30 17:07:01',NULL,4,NULL,NULL),(120,'BRG634721273339',1,'2026-07-05','Untuk oprasional IT','2026-07-05 16:54:13','2026-07-05 16:54:13',NULL,6,NULL,NULL),(121,'BRG173531920907',1,'2026-07-06','Untuk Polibun','2026-07-06 03:02:40','2026-07-06 03:02:40',NULL,4,NULL,NULL),(122,'BRG639486321852',1,'2026-07-17','Untuk Checkpoint 10','2026-07-16 17:07:32','2026-07-16 17:07:32',NULL,4,NULL,NULL),(123,'BRG634721273339',2,'2026-07-17','Untuk Oprasional IT','2026-07-16 17:09:11','2026-07-16 17:09:11',NULL,4,NULL,NULL),(124,'8885007028484',1,'2026-07-22','Untuk Kantor Agronomi','2026-07-21 22:47:33','2026-07-21 22:47:33',NULL,4,NULL,NULL);
/*!40000 ALTER TABLE `barang_keluar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_masuk`
--

DROP TABLE IF EXISTS `barang_masuk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_masuk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barcode` varchar(100) NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_masuk_barcode_foreign` (`barcode`),
  KEY `barang_masuk_user_id_foreign` (`user_id`),
  KEY `barang_masuk_created_by_foreign` (`created_by`),
  KEY `barang_masuk_updated_by_foreign` (`updated_by`),
  CONSTRAINT `barang_masuk_barcode_foreign` FOREIGN KEY (`barcode`) REFERENCES `master_barang` (`barcode`) ON DELETE CASCADE,
  CONSTRAINT `barang_masuk_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_masuk_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_masuk_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_masuk`
--

LOCK TABLES `barang_masuk` WRITE;
/*!40000 ALTER TABLE `barang_masuk` DISABLE KEYS */;
INSERT INTO `barang_masuk` VALUES (1,'BRG636844663424',1,'2026-03-11',NULL,'2026-03-10 17:19:03','2026-03-10 17:19:03',NULL,NULL,NULL,NULL),(2,'BRG529010091667',12,'2026-03-13',NULL,'2026-03-12 18:19:27','2026-03-12 18:19:27',NULL,NULL,NULL,NULL),(3,'BRG637820273103',100,'2026-03-23','U/ OPERASIONAL IT','2026-03-23 01:03:08','2026-03-23 01:03:08',NULL,NULL,NULL,NULL),(4,'BRG661336096437',3,'2026-03-26',NULL,'2026-03-26 02:21:30','2026-03-26 02:21:30',NULL,NULL,NULL,NULL),(5,'BRG638708750684',1,'2026-03-27',NULL,'2026-03-26 21:09:18','2026-03-26 21:09:18',NULL,4,NULL,NULL),(6,'8885007028422',5,'2026-04-14',NULL,'2026-04-14 01:05:24','2026-04-14 01:05:24',NULL,4,NULL,NULL),(7,'BRG636361073688',8,'2026-04-25',NULL,'2026-04-24 19:56:49','2026-04-24 19:56:49',NULL,4,NULL,NULL),(8,'8885007028422',8,'2026-04-25',NULL,'2026-04-24 20:20:26','2026-04-24 20:20:26',NULL,4,NULL,NULL),(9,'8885007028460',5,'2026-04-25',NULL,'2026-04-24 20:23:10','2026-04-24 20:23:10',NULL,4,NULL,NULL),(10,'8885007028484',5,'2026-04-25',NULL,'2026-04-24 20:23:22','2026-04-24 20:23:22',NULL,4,NULL,NULL),(11,'8885007028446',5,'2026-04-25',NULL,'2026-04-24 20:23:34','2026-04-24 20:23:34',NULL,4,NULL,NULL),(12,'BRG789001124466',4,'2026-05-07',NULL,'2026-05-06 18:28:40','2026-05-06 18:28:40',NULL,4,NULL,NULL),(13,'BRG639486321852',13,'2026-05-18',NULL,'2026-05-17 21:48:54','2026-05-17 21:48:54',NULL,4,NULL,NULL),(14,'6935364021153',2,'2026-06-08',NULL,'2026-06-07 22:38:22','2026-06-07 22:38:22',NULL,4,NULL,NULL),(15,'BRG587998210565',4,'2026-06-08',NULL,'2026-06-07 22:39:26','2026-06-07 22:39:26',NULL,4,NULL,NULL),(16,'8885007028422',5,'2026-06-17',NULL,'2026-06-16 18:07:27','2026-06-16 18:07:27',NULL,4,NULL,NULL),(17,'8885007028422',6,'2026-07-22',NULL,'2026-07-21 22:46:00','2026-07-21 22:46:00',NULL,4,NULL,NULL),(18,'BRG638708750684',1,'2026-07-22',NULL,'2026-07-21 22:46:44','2026-07-21 22:46:44',NULL,4,NULL,NULL),(19,'0887276429601',4,'2026-07-22',NULL,'2026-07-21 22:48:55','2026-07-21 22:48:55',NULL,4,NULL,NULL);
/*!40000 ALTER TABLE `barang_masuk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_retur`
--

DROP TABLE IF EXISTS `barang_retur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_retur` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `barang_keluar_id` bigint(20) unsigned NOT NULL,
  `barcode` varchar(100) NOT NULL,
  `jumlah_retur` int(11) NOT NULL,
  `tanggal_retur` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_retur_barang_keluar_id_foreign` (`barang_keluar_id`),
  KEY `barang_retur_barcode_foreign` (`barcode`),
  KEY `barang_retur_user_id_foreign` (`user_id`),
  KEY `barang_retur_created_by_foreign` (`created_by`),
  KEY `barang_retur_updated_by_foreign` (`updated_by`),
  CONSTRAINT `barang_retur_barang_keluar_id_foreign` FOREIGN KEY (`barang_keluar_id`) REFERENCES `barang_keluar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_retur_barcode_foreign` FOREIGN KEY (`barcode`) REFERENCES `master_barang` (`barcode`) ON DELETE CASCADE,
  CONSTRAINT `barang_retur_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_retur_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_retur_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_retur`
--

LOCK TABLES `barang_retur` WRITE;
/*!40000 ALTER TABLE `barang_retur` DISABLE KEYS */;
INSERT INTO `barang_retur` VALUES (2,18,'BRG588846009913',1,'2026-04-06','tidak jadi','2026-04-05 22:24:26','2026-04-05 22:24:26',NULL,4,NULL,NULL),(3,104,'6935364021153',1,'2026-06-17','tidak jadi di pasang','2026-06-17 00:10:47','2026-06-17 00:10:47',NULL,4,NULL,NULL),(4,115,'BRG588846009913',1,'2026-07-02','Tidak terpakai','2026-07-01 17:58:33','2026-07-01 17:58:33',NULL,4,NULL,NULL);
/*!40000 ALTER TABLE `barang_retur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_rusak`
--

DROP TABLE IF EXISTS `barang_rusak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_rusak` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomor` varchar(20) NOT NULL,
  `vehicle_group_code` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `tahun_perolehan` smallint(6) NOT NULL,
  `merek` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `lokasi_unit` varchar(100) NOT NULL,
  `kondisi_unit` enum('hidup','mati') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barang_rusak_nomor_unique` (`nomor`),
  KEY `barang_rusak_user_id_foreign` (`user_id`),
  KEY `barang_rusak_created_by_foreign` (`created_by`),
  KEY `barang_rusak_updated_by_foreign` (`updated_by`),
  CONSTRAINT `barang_rusak_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_rusak_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `barang_rusak_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_rusak`
--

LOCK TABLES `barang_rusak` WRITE;
/*!40000 ALTER TABLE `barang_rusak` DISABLE KEYS */;
/*!40000 ALTER TABLE `barang_rusak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_barang`
--

DROP TABLE IF EXISTS `master_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_barang` (
  `barcode` varchar(100) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `lokasi_rak` enum('A','B','C','D','E','F','G','H','O') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`barcode`),
  KEY `master_barang_user_id_foreign` (`user_id`),
  KEY `master_barang_created_by_foreign` (`created_by`),
  KEY `master_barang_updated_by_foreign` (`updated_by`),
  CONSTRAINT `master_barang_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `master_barang_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `master_barang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_barang`
--

LOCK TABLES `master_barang` WRITE;
/*!40000 ALTER TABLE `master_barang` DISABLE KEYS */;
INSERT INTO `master_barang` VALUES ('0095969678667','Digital Multimeter (FLUKE)',0,'B','2026-03-05 18:17:01','2026-05-07 01:22:51',NULL,NULL,NULL,NULL),('0201600000524','D-link 16 port',0,'B','2026-04-17 19:52:38','2026-05-07 01:20:45',NULL,4,NULL,NULL),('0606449159233','Netgear WAX615-100APS',1,'A','2026-03-05 17:58:14','2026-03-05 17:58:14',NULL,NULL,NULL,NULL),('0887276429601','SSD Samsung 500GB',6,'C','2026-03-05 19:35:05','2026-07-21 22:48:55',NULL,NULL,NULL,NULL),('4718009156241','LUX 650W active pfc',1,'B','2026-03-05 18:27:11','2026-03-05 18:27:11',NULL,NULL,NULL,NULL),('4719857309858','Kabel HDMI Bafo 15 meter',2,'B','2026-03-05 18:28:29','2026-03-05 18:28:29',NULL,NULL,NULL,NULL),('4752224004697','Mikrotik SXTsq 5ac',7,'A','2026-03-05 18:07:52','2026-05-07 01:16:14',NULL,NULL,4,NULL),('4752224008589','Mikrotik L009UiGS-RM',1,'A','2026-03-05 18:11:09','2026-03-05 18:11:09',NULL,NULL,NULL,NULL),('4895093306261','Epson ribbon cartridge',10,'D','2026-03-05 19:37:34','2026-05-07 01:46:47',NULL,NULL,4,NULL),('4909723118220','Icom FM Transceiver',5,'E','2026-03-05 19:53:01','2026-05-08 03:59:16',NULL,NULL,4,NULL),('4971850120353','Casio Tape Cartridge',5,'D','2026-04-09 01:50:58','2026-04-09 01:50:58',NULL,4,NULL,NULL),('4977766748131','Tinta brother C',12,'F','2026-03-05 19:47:03','2026-05-29 21:15:14',NULL,NULL,NULL,NULL),('4977766748148','Tinta brother M',14,'F','2026-03-05 19:49:05','2026-05-07 01:56:41',NULL,NULL,4,NULL),('4977766748155','Tinta brother Y',8,'F','2026-03-05 19:47:55','2026-04-06 18:19:38',NULL,NULL,NULL,NULL),('4977766786324','Tinta brother BK',21,'F','2026-03-05 19:46:13','2026-03-05 19:46:13',NULL,NULL,NULL,NULL),('4984824348554','Panasonic photo power Lithium',11,'A','2026-03-05 18:03:42','2026-03-05 18:03:42',NULL,NULL,NULL,NULL),('616202024171338','Fingerspot REVO W-202BNC',0,'B','2026-03-05 18:23:46','2026-05-07 01:23:21',NULL,NULL,NULL,NULL),('6922794735231','VGA to HDMI Converter',1,'C','2026-04-09 01:49:04','2026-04-17 18:53:59',NULL,4,NULL,NULL),('6922794744226','Connector RJ45 Bolong',10,'C','2026-04-09 01:48:08','2026-04-15 00:25:54',NULL,4,NULL,NULL),('6926150033825','Yeastar NeogateTA200',2,'B','2026-03-05 18:19:27','2026-03-05 18:19:27',NULL,NULL,NULL,NULL),('6931847166953','HIK Vision audio',1,'B','2026-05-07 03:10:30','2026-05-07 03:10:30',NULL,4,NULL,NULL),('6931847187026','HIK Vision CCTV',1,'B','2026-05-07 03:09:37','2026-05-07 03:09:37',NULL,4,NULL,NULL),('6935364021153','TP-Link 8 port Gigabit TL-SG108',0,'A','2026-03-05 17:56:07','2026-06-21 16:49:05',NULL,NULL,4,NULL),('6935364073084','TP-Link Gigabit POE+ Injector',1,'B','2026-03-05 18:20:51','2026-03-05 18:20:51',NULL,NULL,NULL,NULL),('6936678414594','Power bank Robot',12,'C','2026-03-05 19:13:49','2026-03-05 19:13:49',NULL,NULL,NULL,NULL),('6953156280885','Pengikat kabel Velcro',10,'C','2026-03-05 19:16:57','2026-03-05 19:16:57',NULL,NULL,NULL,NULL),('6972775380172','Ethernet Fiber Switch',0,'E','2026-03-05 20:09:56','2026-03-11 17:13:26',NULL,NULL,NULL,NULL),('850049670111','Starting ethernet adapter',1,'A','2026-03-05 18:04:36','2026-03-05 18:04:36',NULL,NULL,NULL,NULL),('8806094899511','Samsung Power Adapter',25,'H','2026-03-06 20:04:22','2026-03-06 20:04:22',NULL,NULL,NULL,NULL),('8885007024097','Tinta Epson 664 C',6,'D','2026-03-05 19:43:57','2026-05-07 01:42:12',NULL,NULL,4,NULL),('8885007024103','Tinta Epson 664 M',9,'D','2026-03-05 19:44:59','2026-05-07 01:56:03',NULL,NULL,4,NULL),('8885007024110','Tinta Epson 664 Y',1,'D','2026-03-05 19:43:06','2026-05-07 01:55:35',NULL,NULL,4,NULL),('8885007028422','Tinta Epson 003 BK',6,'D','2026-03-05 19:38:56','2026-07-21 22:46:00',NULL,NULL,4,NULL),('8885007028446','Tinta Epson 003 C',26,'D','2026-03-05 19:41:19','2026-06-22 01:05:56',NULL,NULL,NULL,NULL),('8885007028460','Tinta Epson 003 M',25,'D','2026-03-05 19:42:19','2026-06-30 17:07:01',NULL,NULL,4,NULL),('8885007028484','Tinta Epson 003 Y',14,'D','2026-03-05 19:39:58','2026-07-21 22:47:33',NULL,NULL,NULL,NULL),('8887549215193','Panasonic KX-TS505MX TELEPONE',1,'B','2026-03-05 18:33:24','2026-03-05 18:33:24',NULL,NULL,NULL,NULL),('BRG058783212744','SCC merah',1,'A','2026-03-31 18:11:38','2026-04-04 00:42:30',NULL,4,4,NULL),('BRG061840819969','Power Adapter 24 V',3,'B','2026-03-31 18:17:18','2026-05-25 19:39:44',NULL,4,4,NULL),('BRG141659228843','NVR HIKVISION',0,'O','2026-05-06 17:36:31','2026-05-06 17:37:44',NULL,4,NULL,NULL),('BRG173531920907','Netcom L300',0,'C','2026-05-06 18:29:29','2026-07-06 03:02:40',NULL,4,NULL,NULL),('BRG422506383850','Monitor 19 LG',0,'O','2026-04-27 19:11:09','2026-04-27 19:11:51',NULL,4,NULL,NULL),('BRG435110446226','Charger HT',1,'C','2026-05-07 01:45:20','2026-05-07 01:45:20',NULL,4,NULL,NULL),('BRG452126752751','PC Rakitan',3,'G','2026-05-07 02:13:47','2026-05-07 02:13:47',NULL,4,NULL,NULL),('BRG468711699119','SCC Besar Hitam',0,'A','2026-05-04 19:08:15','2026-07-01 18:19:11',NULL,4,4,NULL),('BRG481385407449','Duradus Box',10,'E','2026-05-07 03:02:30','2026-05-07 03:10:57',NULL,4,4,NULL),('BRG528449569703','Full Body Harness',2,'H','2026-03-06 20:07:58','2026-03-06 20:07:58',NULL,NULL,NULL,NULL),('BRG529010091667','Air accu Yuasa',0,'H','2026-03-06 20:08:44','2026-06-19 19:50:13',NULL,NULL,4,NULL),('BRG543104445003','Netcom L400',0,'C','2026-04-20 23:52:18','2026-04-27 19:12:09',NULL,4,NULL,NULL),('BRG587228042238','Camtech webcam',1,'A','2026-03-05 17:59:42','2026-07-15 01:52:07',NULL,NULL,4,NULL),('BRG587998210565','SCC biru',2,'A','2026-03-05 18:00:21','2026-07-01 18:19:36',NULL,NULL,4,NULL),('BRG588846009913','POE Passive Splitter',3,'A','2026-03-05 18:02:23','2026-07-01 17:58:33',NULL,NULL,4,NULL),('BRG593106647962','Mikrotik RB450Gx4',1,'A','2026-03-05 18:09:09','2026-03-06 20:39:14',NULL,NULL,NULL,NULL),('BRG596013200026','Kipas pendingin aksial (Sunon KD1208PTB1)',4,'B','2026-03-05 18:14:26','2026-03-05 18:14:26',NULL,NULL,NULL,NULL),('BRG601230119161','Gaintech 4 port HDMI Splitter',1,'B','2026-03-05 18:22:39','2026-03-05 18:22:39',NULL,NULL,NULL,NULL),('BRG602790446917','Mikrotik Groove series routerboard Groove 52',1,'A','2026-03-05 18:25:36','2026-05-07 01:21:08',NULL,NULL,4,NULL),('BRG606074191253','Adaptor 24V',2,'B','2026-03-05 18:30:26','2026-04-10 19:03:17',NULL,NULL,4,NULL),('BRG606479635367','Adaptor 48V',5,'B','2026-03-05 18:31:12','2026-03-05 18:31:12',NULL,NULL,NULL,NULL),('BRG608607470618','Adaptor LG',5,'B','2026-03-05 18:34:39','2026-03-05 18:34:39',NULL,NULL,NULL,NULL),('BRG618845555406','Maxell lithium battery CR2032',44,'C','2026-03-05 18:52:31','2026-05-08 01:53:41',NULL,NULL,4,NULL),('BRG619688449542','Maxell lithium battery CR1220',38,'C','2026-03-05 18:54:38','2026-05-08 01:54:14',NULL,NULL,4,NULL),('BRG628120415348','Camelion lithium CR2032',40,'C','2026-03-05 19:08:01','2026-05-07 01:29:32',NULL,NULL,4,NULL),('BRG628944778296','Icon Lion Battery Pack',6,'C','2026-03-05 19:09:05','2026-05-07 01:33:47',NULL,NULL,NULL,NULL),('BRG634721273339','Temflex isolasi listrik',0,'C','2026-03-05 19:18:38','2026-07-16 17:09:11',NULL,NULL,NULL,NULL),('BRG636361073688','Mouse',2,'C','2026-03-05 19:20:45','2026-06-26 18:56:39',NULL,NULL,4,NULL),('BRG636844663424','Keyboard',1,'C','2026-03-05 19:21:33','2026-04-27 21:19:25',NULL,NULL,4,NULL),('BRG637820273103','RJ45 Connector (Manual)',100,'C','2026-03-05 19:23:50','2026-03-23 01:03:32',NULL,NULL,NULL,NULL),('BRG638708750684','Netlink FOC',1,'E','2026-03-05 19:24:56','2026-07-21 22:46:44',NULL,NULL,4,NULL),('BRG639133688683','Step Up',5,'C','2026-03-05 19:25:34','2026-05-07 01:40:40',NULL,NULL,4,NULL),('BRG639486321852','Kepala kutup aki',3,'C','2026-03-05 19:26:33','2026-07-16 17:08:12',NULL,NULL,4,NULL),('BRG640638782065','Power USB jack 5V',14,'C','2026-03-05 19:30:36','2026-03-16 00:33:50',NULL,NULL,NULL,NULL),('BRG642837000064','Kabel HDMI Netline',1,'C','2026-03-05 19:31:35','2026-03-05 19:31:35',NULL,NULL,NULL,NULL),('BRG643102823036','Kabel USB male to mini',2,'C','2026-03-05 19:32:16','2026-03-05 19:32:16',NULL,NULL,NULL,NULL),('BRG653760095739','Klem mikotik (pack)',34,'F','2026-03-05 19:50:47','2026-04-23 19:43:01',NULL,NULL,NULL,NULL),('BRG657476721659','Antena HT R771',11,'E','2026-03-05 19:56:37','2026-06-12 00:39:25',NULL,NULL,4,NULL),('BRG658601324180','Bracket antena mobil DP-SPM',5,'E','2026-03-05 19:58:25','2026-03-05 19:58:25',NULL,NULL,NULL,NULL),('BRG659651316085','Hanger HT Icom',11,'E','2026-03-05 20:00:46','2026-05-07 02:05:50',NULL,NULL,4,NULL),('BRG661336096437','Ribbon printer Zebra',6,'E','2026-03-05 20:03:20','2026-05-07 02:04:50',NULL,NULL,4,NULL),('BRG662120559282','Label printer Zebra',9,'E','2026-03-05 20:04:39','2026-05-07 02:03:28',NULL,NULL,4,NULL),('BRG663874071292','Antena mobil HT',5,'E','2026-03-05 20:06:52','2026-05-07 01:59:43',NULL,NULL,4,NULL),('BRG666324798249','Fastcon FO',11,'E','2026-03-05 20:11:00','2026-05-07 02:00:38',NULL,NULL,4,NULL),('BRG789001124466','Terminal 4 Lubang',4,'C','2026-04-25 21:48:53','2026-05-18 16:49:53',NULL,4,NULL,NULL),('BRG858279926362','Mikrotik Gigabit POE',2,'C','2026-04-24 19:57:40','2026-05-07 01:08:20',NULL,4,NULL,NULL),('BRG870740358065','RAM 16 GB DDR4',4,'C','2026-04-24 20:18:39','2026-04-24 20:18:39',NULL,4,NULL,NULL);
/*!40000 ALTER TABLE `master_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_lokasi_unit`
--

DROP TABLE IF EXISTS `master_lokasi_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_lokasi_unit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lokasi` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_lokasi_unit_lokasi_unique` (`lokasi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_lokasi_unit`
--

LOCK TABLES `master_lokasi_unit` WRITE;
/*!40000 ALTER TABLE `master_lokasi_unit` DISABLE KEYS */;
INSERT INTO `master_lokasi_unit` VALUES (1,'SERVER STATE','2026-03-09 20:40:56','2026-03-09 20:40:56');
/*!40000 ALTER TABLE `master_lokasi_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_vehicle_group`
--

DROP TABLE IF EXISTS `master_vehicle_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_vehicle_group` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `master_vehicle_group_kode_unique` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_vehicle_group`
--

LOCK TABLES `master_vehicle_group` WRITE;
/*!40000 ALTER TABLE `master_vehicle_group` DISABLE KEYS */;
INSERT INTO `master_vehicle_group` VALUES (1,'ITUPSS01','2026-03-09 20:40:56','2026-03-09 20:40:56');
/*!40000 ALTER TABLE `master_vehicle_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_create_master_barang_table',1),(5,'2024_01_01_000002_create_barang_masuk_table',1),(6,'2024_01_01_000003_create_barang_keluar_table',1),(7,'2024_01_01_000004_create_master_vehicle_group_table',2),(8,'2024_01_01_000005_create_master_lokasi_unit_table',2),(9,'2024_01_01_000006_create_barang_rusak_table',2),(10,'2026_03_10_000001_alter_barang_rusak_tahun_perolehan_table',3),(11,'2026_03_11_000001_create_barang_retur_table',4),(12,'2026_03_25_000001_add_user_id_to_all_tables',5),(13,'2026_03_26_000001_add_role_to_users_table',6),(14,'2026_03_26_000002_add_username_to_users_table',7),(15,'2026_03_27_000001_add_profile_photo_and_user_tracking',8),(16,'2026_03_27_000002_add_menu_permissions_to_users_table',9),(17,'2026_06_02_000001_add_soft_deletes_to_transaction_tables',10),(18,'2026_08_10_000001_create_activity_logs_table',11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `profile_photo` varchar(255) DEFAULT NULL,
  `menu_permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`menu_permissions`)),
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'WINDU SMG','WINDU SMG','user',NULL,'[\"master_barang\",\"barang_masuk\",\"barang_keluar\",\"barang_retur\",\"barang_rusak\"]','windusmg@gmail.com',NULL,'$2y$12$5WjErkYEsKs/tGHfwxGA5.SWH5.A2dULAMujE3.ICmWmP6kg/5zAK',NULL,'2026-03-24 22:09:49','2026-04-10 01:20:40'),(2,'AGUS SMG','AGUS SMG','user',NULL,'[\"master_barang\",\"barang_masuk\",\"barang_keluar\",\"barang_retur\",\"barang_rusak\"]','agussmg@gmail.com',NULL,'$2y$12$w/RKSkOEiSWLHxMgYpAOd.Re6hr3gr2BL0hko58HkjiJSyL7CXPqW',NULL,'2026-03-24 22:09:49','2026-04-10 01:20:52'),(3,'SUNU SMG','SUNU SMG','user',NULL,'[\"master_barang\",\"barang_masuk\",\"barang_keluar\",\"barang_retur\",\"barang_rusak\"]','sunusmg@gmail.com',NULL,'$2y$12$tcvkpbFV.DeeZpfJW6y2m.VrcLjXAABgGc4m6H6gbFeaBnjDDayzm',NULL,'2026-03-24 22:09:50','2026-04-10 01:20:26'),(4,'RELUNG SMG','RELUNG SMG','user','1780115322_4.jpg','[\"master_barang\",\"barang_masuk\",\"barang_keluar\",\"barang_retur\",\"barang_rusak\"]','relungsmg@gmail.com',NULL,'$2y$12$CFV8KkiXVq4fj1fSkgzKSOxE8UKIvwqPTQ5H0IvJtnQptEG0.DgfS',NULL,'2026-03-24 22:23:17','2026-05-29 21:28:42'),(5,'GERBY SMG','GERBY SMG','user',NULL,'[\"master_barang\",\"barang_masuk\",\"barang_keluar\",\"barang_retur\",\"barang_rusak\"]','gerbysmg@gmail.com',NULL,'$2y$12$FYh93eWzdCa/l/vVS4oK9uSVYT.WoQNb48AhTSSky4939VtlHPBri',NULL,'2026-04-01 00:44:19','2026-04-26 01:17:45'),(6,'Administrator','admin','admin',NULL,NULL,'admin@inventori.local',NULL,'$2y$12$fUjVgWurA7de.7wjGJF1Ee6jALMzbRjQqcRvwjCgjfXrgFa.0gjfu',NULL,'2026-06-15 00:49:02','2026-06-15 00:49:02'),(7,'Kasir','kasir','user',NULL,'[\"master_barang\",\"barang_masuk\",\"barang_keluar\",\"barang_retur\",\"barang_rusak\"]','kasir@inventori.local',NULL,'$2y$12$A.1IlI4y/Rh6huuaY/tcde4pfRbDIlwzP7gXreCCeuuRKZg3k/0me',NULL,'2026-07-15 02:08:32','2026-07-15 02:08:32');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'inventori_gudang'
--

--
-- Dumping routines for database 'inventori_gudang'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-15 14:51:57
