-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: koksan_misafirhane
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `warranty_expiration` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assets_location_id_foreign` (`location_id`),
  CONSTRAINT `assets_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets`
--

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;
INSERT INTO `assets` VALUES (1,6,'Buzdolabı','Arçelik','KX-1001','2025-12-08','2027-12-08','active','2025-12-08 12:28:00','2025-12-09 14:19:09','2025-12-09 14:19:09'),(2,9,'Buzdolabı','Arçelik','KX-1001','2025-12-09','2029-07-09','active','2025-12-09 14:38:00','2025-12-09 14:38:19','2025-12-09 14:38:19');
/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'ahmet usta','elektrik şirketi','0342 111 2233',NULL,'electric',NULL,'2025-12-09 05:43:01','2025-12-15 06:02:08','2025-12-15 06:02:08'),(2,'veli usta','doğalgaz şirketi','0342 111 2235',NULL,'gas',NULL,'2025-12-09 08:15:52','2025-12-09 14:14:34','2025-12-09 14:14:34'),(3,'mehmet usta','su şirketi','0342 111 2233',NULL,'water',NULL,'2025-12-12 08:32:47','2025-12-15 06:02:12','2025-12-15 06:02:12'),(4,'veli usta','su şirketi2','0342 111 2235',NULL,'water',NULL,'2025-12-12 08:33:26','2025-12-15 06:02:16','2025-12-15 06:02:16');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fileable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileable_id` bigint unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint NOT NULL,
  `collection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `files_fileable_type_fileable_id_index` (`fileable_type`,`fileable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `map_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('campus','site','block','apartment','room','common_area') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ownership` enum('owned','rented') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owned',
  `landlord_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landlord_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int NOT NULL DEFAULT '0',
  `wifi_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_parent_id_foreign` (`parent_id`),
  CONSTRAINT `locations_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,NULL,'C BLOK KAT 5 NO:17',NULL,NULL,'site','owned',NULL,NULL,0,NULL,'Singh +1 (HİNTLİ)',1,'2025-12-08 10:54:47','2025-12-08 11:17:43','2025-12-08 11:17:43'),(2,NULL,'C BLOK KAT 5 NO:17',NULL,NULL,'site','owned',NULL,NULL,0,NULL,'Singh +1 (HİNTLİ)',1,'2025-12-08 10:56:16','2025-12-08 11:17:46','2025-12-08 11:17:46'),(3,NULL,'KÖKSAN BEYTEPE LOJMAN (ELİF SİTESİ)',NULL,NULL,'site','owned',NULL,NULL,1,NULL,NULL,1,'2025-12-08 11:22:44','2025-12-15 06:01:57','2025-12-15 06:01:57'),(4,3,'C BLOK KAT 5 NO:17',NULL,NULL,'block','owned',NULL,NULL,0,NULL,'SİNGH +1 (HİNTLİ)',1,'2025-12-08 11:23:38','2025-12-08 11:23:56','2025-12-08 11:23:56'),(5,3,'C BLOK',NULL,NULL,'block','owned',NULL,NULL,0,NULL,NULL,1,'2025-12-08 11:24:10','2025-12-15 06:01:17','2025-12-15 06:01:17'),(6,5,'C BLOK KAT 5 NO:17',NULL,NULL,'apartment','owned',NULL,NULL,0,NULL,NULL,1,'2025-12-08 11:24:40','2025-12-09 14:20:57','2025-12-09 14:20:57'),(7,NULL,'Köksan Beytepe Lojmanları',NULL,NULL,'site','owned',NULL,NULL,0,NULL,NULL,1,'2025-12-08 11:31:48','2025-12-08 11:32:06','2025-12-08 11:32:06'),(8,5,'C BLOK KAT 8 NO:31',NULL,NULL,'apartment','owned',NULL,NULL,0,NULL,NULL,1,'2025-12-08 11:33:26','2025-12-09 14:09:31','2025-12-09 14:09:31'),(9,5,'C BLOK KAT 5 NO:17',NULL,NULL,'apartment','owned',NULL,NULL,4,NULL,NULL,1,'2025-12-09 14:21:23','2025-12-15 06:00:39','2025-12-15 06:00:39'),(10,3,'D BLOK',NULL,NULL,'block','owned',NULL,NULL,1,NULL,NULL,1,'2025-12-10 08:06:37','2025-12-15 06:01:46','2025-12-15 06:01:46'),(11,10,'D BLOK KAT 7 NO 25',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,NULL,1,'2025-12-10 08:08:02','2025-12-15 06:01:35','2025-12-15 06:01:35'),(12,NULL,'FISTIKLIK',NULL,NULL,'site','owned',NULL,NULL,1,NULL,NULL,1,'2025-12-10 08:47:32','2025-12-15 06:00:26','2025-12-15 06:00:26'),(13,5,'KAT 6 NO:23',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'MR.PATEL\'E TESLİM EDİLDİ.',1,'2025-12-10 09:16:14','2025-12-15 06:01:04','2025-12-15 06:01:04'),(14,12,'SİMFA GARDEN 27',NULL,NULL,'apartment','rented','MUSTAFA TÜMER','0555 555 55 55',1,NULL,'HÜSEYİN PARLAR +2 KİŞİ',1,'2025-12-10 09:23:59','2025-12-15 06:00:09','2025-12-15 06:00:09'),(15,39,'KAT 10 NO:37',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,NULL,1,'2025-12-29 11:11:54','2025-12-29 15:15:41',NULL),(16,40,'KAT 5 NO:18',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'MUSTAFA BUYRUKOĞLU 02.11.2025 TARİHİNDE TESLİM EDİLDİ',1,'2025-12-29 11:12:13','2025-12-29 15:15:42',NULL),(17,41,'KAT 7 NO:25',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'MR SAHU',1,'2025-12-29 11:12:44','2025-12-29 15:15:42',NULL),(18,NULL,'BEYTEPE ELİF SİTESİ  D BLOK KAT 7 NO:25',NULL,NULL,'site','owned',NULL,NULL,1,NULL,'MR SAHU',1,'2025-12-29 11:17:14','2025-12-29 11:22:38','2025-12-29 11:22:38'),(19,40,'KAT 5 NO:17',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'MR SİNGH+ 1 HİNLİ',1,'2025-12-29 11:23:03','2025-12-29 15:15:42',NULL),(20,40,'KAT 10 NO:37 KİRALIK',NULL,NULL,'apartment','rented','RECEP KASTÜL','05325044727',1,NULL,'MAVİ YAKA HİNTLİ',1,'2025-12-29 11:25:22','2025-12-29 15:15:42',NULL),(21,40,'KAT 8 NO:32',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'MEHMET KIVRAK',1,'2025-12-29 11:29:24','2025-12-29 15:15:42',NULL),(22,41,'KAT 2 NO:6',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'BEYAZ YAKA HİNTLİ',1,'2025-12-29 11:33:08','2025-12-29 15:15:42',NULL),(23,40,'KAT 5 NO:19',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'ÖZLEM YÜKSELİCİ',1,'2025-12-29 11:35:13','2025-12-29 15:15:42',NULL),(24,41,'KAT 8 NO:30',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'AYKUT KURUM',1,'2025-12-29 11:37:33','2025-12-29 15:15:42',NULL),(25,40,'KAT 4 NO:15',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'FEYZA TOKTAY',1,'2025-12-29 11:40:38','2025-12-29 15:15:42',NULL),(26,43,'FISTIKLIK MAHALLESİ    KAT 3 NO:12',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'MR.KHURANA',1,'2025-12-29 11:45:25','2025-12-29 15:15:42',NULL),(27,43,'FISTIKLIK MAHALLESİ   KAT 4 NO:15',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'ERHAN AKGÜL',1,'2025-12-29 11:53:19','2025-12-29 15:15:42',NULL),(28,43,'FISTIKLIK MAHALLESİ   KAT 6 NO:23',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'MR PATEL',1,'2025-12-29 11:58:16','2025-12-29 15:15:42',NULL),(29,43,'FISTIKLIK MAHALLESİ   KAT 8 NO:31',NULL,NULL,'apartment','rented','SERHAT TÜMER','05326965586',1,NULL,'ERCAN EREN',1,'2025-12-29 12:02:10','2025-12-29 15:15:42',NULL),(30,NULL,'FISTIKLI MAHALALESİ SİNFA GARDEN SİTESİ NO:27',NULL,NULL,'site','rented','MUSTAFA TÜMER','05432388378',1,NULL,'HÜSEYİN PARLAR',1,'2025-12-29 12:06:19','2025-12-29 12:07:15',NULL),(31,45,'FISTIKLIK MAHALLESİ   KAT 4 NO:15',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'ZEYNEL KIPIRTI',1,'2025-12-29 12:08:22','2025-12-29 15:15:42',NULL),(32,46,'KAT 1 NO:1',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'AHMET BAŞ',1,'2025-12-29 12:09:14','2025-12-29 15:15:42',NULL),(33,46,'KAT 1 NO:2',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'METİN AKDENİZ',1,'2025-12-29 12:09:36','2025-12-29 15:15:42',NULL),(34,46,'KAT 2 NO:3',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'AHMET AKIN-UMUT AKGÜN',1,'2025-12-29 12:10:01','2025-12-29 15:15:42',NULL),(35,46,'KAT 2 NO:4',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'RUŞEN KARABUDAK',1,'2025-12-29 12:10:21','2025-12-29 15:15:42',NULL),(36,46,'KAT 0  NO:5',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'EMİN KANDEMİR',1,'2025-12-29 12:11:00','2025-12-29 15:15:42',NULL),(37,46,'KAT 0 NO:6',NULL,NULL,'apartment','owned',NULL,NULL,1,NULL,'ASIM KÖKOĞLU',1,'2025-12-29 12:11:18','2025-12-29 15:15:42',NULL),(38,47,'KAT:2 DAİRE 7',NULL,NULL,'apartment','rented','ÖMER ASIM ÖZSEVER','05323416241',1,NULL,'MEHMET KUZU',1,'2025-12-29 12:18:26','2025-12-29 15:15:42',NULL),(39,NULL,'Beytepe Elif Sitesi',NULL,NULL,'site','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:41','2025-12-29 15:15:41',NULL),(40,39,'C Blok',NULL,NULL,'block','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(41,39,'D Blok',NULL,NULL,'block','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(42,NULL,'Fıstıklık Tuğçe Sitesi','Fıstıklık Mah.',NULL,'site','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(43,42,'C Blok',NULL,NULL,'block','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(44,NULL,'Fıstıklık Asrın Sitesi','Fıstıklık Mah.',NULL,'site','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(45,44,'A Blok',NULL,NULL,'block','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(46,NULL,'15 Temmuz Lojmanları',NULL,NULL,'site','owned',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(47,NULL,'Murat Apartmanı','Binevler Mah.',NULL,'site','rented',NULL,NULL,0,NULL,NULL,1,'2025-12-29 15:15:42','2025-12-29 15:15:42',NULL);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_08_124407_create_locations_table',1),(5,'2025_12_08_124426_create_assets_table',1),(6,'2025_12_08_124433_create_contacts_table',1),(7,'2025_12_08_124444_create_service_assignments_table',1),(8,'2025_12_08_124452_create_subscriptions_table',1),(9,'2025_12_08_124458_create_residents_table',1),(10,'2025_12_08_124504_create_stays_table',1),(11,'2025_12_08_125835_create_system_logs_table',1),(12,'2025_12_08_125858_create_files_table',1),(13,'2025_12_08_134210_add_landlord_columns_to_locations_table',2),(14,'2025_12_08_135106_add_notes_to_locations_table',3),(15,'2025_12_09_113316_add_role_to_users_table',4),(16,'2025_12_15_092741_add_soft_deletes_to_stays_table',5),(17,'2025_12_29_172143_add_address_to_locations_table',5),(18,'2025_12_29_172214_add_holder_name_to_subscriptions_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `residents`
--

DROP TABLE IF EXISTS `residents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `residents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tc_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `residents`
--

LOCK TABLES `residents` WRITE;
/*!40000 ALTER TABLE `residents` DISABLE KEYS */;
INSERT INTO `residents` VALUES (1,'ercan','eren',NULL,'0342 111 2233',NULL,'Bakım','2025-12-08 11:35:02','2025-12-09 14:46:36','2025-12-09 14:46:36'),(2,'aslıhan','aydın','12345567891',NULL,NULL,'opex','2025-12-09 05:11:56','2025-12-15 07:03:53','2025-12-15 07:03:53'),(3,'ercan','eren','78248726424','05123456212','123456','Bakım','2025-12-10 08:01:33','2025-12-15 06:03:07','2025-12-15 06:03:07'),(4,'HÜSEYİN','PARLAR',NULL,NULL,NULL,NULL,'2025-12-10 09:26:49','2025-12-15 06:03:11','2025-12-15 06:03:11');
/*!40000 ALTER TABLE `residents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_assignments`
--

DROP TABLE IF EXISTS `service_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `contact_id` bigint unsigned NOT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_assignments_location_id_service_type_unique` (`location_id`,`service_type`),
  KEY `service_assignments_contact_id_foreign` (`contact_id`),
  CONSTRAINT `service_assignments_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_assignments_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_assignments`
--

LOCK TABLES `service_assignments` WRITE;
/*!40000 ALTER TABLE `service_assignments` DISABLE KEYS */;
INSERT INTO `service_assignments` VALUES (1,6,1,'electric','2025-12-09 08:16:12','2025-12-09 08:16:12',NULL),(2,6,2,'gas','2025-12-09 08:16:22','2025-12-09 08:16:22',NULL),(3,9,1,'electric','2025-12-09 14:25:23','2025-12-10 07:45:36',NULL),(6,12,1,'electric','2025-12-10 09:30:02','2025-12-10 09:30:02',NULL),(7,12,4,'water','2025-12-12 08:33:08','2025-12-12 08:33:39',NULL);
/*!40000 ALTER TABLE `service_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('7BjW1A5p2v8VWGkmr1hk4PHse3Npzh0e7KOXJJZg',1,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoibVQzMllOSDMyeGxkdExJQ001YmNEa29uQVhzdk90ODJqUU5qWnBIQiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjcyOiJodHRwOi8vb3BleHNlcnZlci5rb2tzYW5wZXQubG9jYWwva29rc2FuX21pc2FmaXJoYW5lL3JlcG9ydHM/JTJGcmVwb3J0cz0iO3M6NToicm91dGUiO3M6MTM6InJlcG9ydHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1767075370),('jZjXFXQnbwHFgB3VSoATZPnBt7wayGUnRqLFOTIu',4,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVDJlQkg2UGpxNUhndldRWHJqUGI0UndCQ2Z2SVBPOVY5cVpESWtRdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTY6Imh0dHA6Ly9vcGV4c2VydmVyLmtva3NhbnBldC5sb2NhbC9rb2tzYW5fbWlzYWZpcmhhbmUvbG9jYXRpb25zLzMyL3Nob3c/JTJGbG9jYXRpb25zJTJGMzIlMkZzaG93PSI7czo1OiJyb3V0ZSI7czoxNDoibG9jYXRpb25zLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=',1767080148),('vVvACxp5OgWECdxRA0GlZwY60X25b9GHce8eU9Wh',NULL,'10.10.22.175','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWFVwS3g2S2pLNVpzNkxEVWpIQTF4UWM4S0xrWkFzVmZiY2VaRG9kUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MjoiaHR0cDovL29wZXhzZXJ2ZXIua29rc2FucGV0LmxvY2FsL2tva3Nhbl9taXNhZmlyaGFuZSI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjY4OiJodHRwOi8vb3BleHNlcnZlci5rb2tzYW5wZXQubG9jYWwva29rc2FuX21pc2FmaXJoYW5lL2xvZ2luPyUyRmxvZ2luPSI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1767098895);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stays`
--

DROP TABLE IF EXISTS `stays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stays` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `resident_id` bigint unsigned NOT NULL,
  `location_id` bigint unsigned NOT NULL,
  `check_in_date` datetime NOT NULL,
  `check_out_date` datetime DEFAULT NULL,
  `check_in_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `check_out_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stays_resident_id_foreign` (`resident_id`),
  KEY `stays_location_id_foreign` (`location_id`),
  CONSTRAINT `stays_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  CONSTRAINT `stays_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`),
  CONSTRAINT `stays_chk_1` CHECK (json_valid(`check_in_items`)),
  CONSTRAINT `stays_chk_2` CHECK (json_valid(`check_out_items`))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stays`
--

LOCK TABLES `stays` WRITE;
/*!40000 ALTER TABLE `stays` DISABLE KEYS */;
INSERT INTO `stays` VALUES (1,1,6,'2025-12-08 14:45:00','2025-12-08 14:48:00','{\"keys\":\"1\",\"wifi\":\"1\",\"clean\":\"1\",\"ac\":\"1\"}','{\"keys_returned\":\"1\",\"room_clear\":\"1\"}','\n[Çıkış Notu]: ','2025-12-08 11:45:15','2025-12-15 06:58:18','2025-12-15 06:58:18'),(2,2,6,'2025-12-09 08:11:00',NULL,'{\"keys\":\"1\",\"wifi\":\"1\",\"clean\":\"1\",\"ac\":\"1\"}',NULL,NULL,'2025-12-09 05:12:04','2025-12-15 06:58:18','2025-12-15 06:58:18'),(3,2,9,'2025-12-09 17:36:00','2025-12-09 17:37:00','{\"keys\":\"1\",\"wifi\":\"1\",\"clean\":\"1\",\"ac\":\"1\"}','{\"keys_returned\":\"1\",\"room_clear\":\"1\",\"damage_check\":\"1\",\"card_returned\":\"1\"}','\n[Çıkış Notu]: ','2025-12-09 14:37:03','2025-12-15 06:58:18','2025-12-15 06:58:18'),(4,4,14,'2025-12-10 12:26:00','2025-12-15 08:59:00','{\"keys\":\"1\",\"wifi\":\"1\",\"clean\":\"1\",\"ac\":\"1\"}','{\"keys_returned\":\"1\",\"room_clear\":\"1\",\"damage_check\":\"1\",\"card_returned\":\"1\"}','+2 KİŞİ\n[Çıkış Notu]: ','2025-12-10 09:26:59','2025-12-15 06:58:18','2025-12-15 06:58:18');
/*!40000 ALTER TABLE `stays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `location_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscriber_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holder_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meter_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_location_id_foreign` (`location_id`),
  CONSTRAINT `subscriptions_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1,2,'electric','1022417708',NULL,NULL,'2025-12-08 10:56:16','2025-12-08 10:56:16',NULL),(2,2,'water','595408',NULL,NULL,'2025-12-08 10:56:16','2025-12-08 10:56:16',NULL),(3,2,'internet','10820109546',NULL,NULL,'2025-12-08 10:56:16','2025-12-08 10:56:16',NULL),(4,4,'electric','1022417708',NULL,NULL,'2025-12-08 11:23:38','2025-12-08 11:23:38',NULL),(5,4,'water','595408',NULL,NULL,'2025-12-08 11:23:38','2025-12-08 11:23:38',NULL),(6,4,'internet','10820109546',NULL,NULL,'2025-12-08 11:23:38','2025-12-08 11:23:38',NULL),(7,6,'electric','1022417708',NULL,NULL,'2025-12-08 11:24:40','2025-12-08 11:24:40',NULL),(8,6,'water','595408',NULL,NULL,'2025-12-08 11:24:40','2025-12-08 11:24:40',NULL),(9,6,'internet','10820109546',NULL,NULL,'2025-12-08 11:24:40','2025-12-08 11:24:40',NULL),(10,8,'electric','1020298302',NULL,NULL,'2025-12-08 11:33:26','2025-12-08 11:33:26',NULL),(11,8,'water','549231',NULL,NULL,'2025-12-08 11:33:26','2025-12-08 11:33:26',NULL),(12,8,'gas','3723852',NULL,NULL,'2025-12-08 11:33:26','2025-12-08 11:33:26',NULL),(13,8,'internet','1092113217',NULL,NULL,'2025-12-08 11:33:26','2025-12-08 11:33:26',NULL),(14,9,'electric','12345678',NULL,NULL,'2025-12-10 07:24:49','2025-12-10 07:24:49',NULL),(15,11,'electric','1022417651',NULL,NULL,'2025-12-10 08:08:02','2025-12-10 08:08:02',NULL),(16,11,'water','595411',NULL,NULL,'2025-12-10 08:08:02','2025-12-10 08:08:02',NULL),(17,11,'gas','3809479',NULL,NULL,'2025-12-10 08:08:02','2025-12-10 08:08:02',NULL),(18,11,'internet','1082108497',NULL,NULL,'2025-12-10 08:08:02','2025-12-10 08:08:02',NULL),(19,13,'electric','1020298112',NULL,NULL,'2025-12-10 09:16:14','2025-12-10 09:16:14',NULL),(20,13,'water','549223',NULL,NULL,'2025-12-10 09:16:14','2025-12-10 09:16:14',NULL),(21,13,'gas','3723849',NULL,NULL,'2025-12-10 09:16:14','2025-12-10 09:16:14',NULL),(22,13,'internet','1082059404',NULL,NULL,'2025-12-10 09:16:14','2025-12-10 09:16:14',NULL),(23,15,'electric','1022417677',NULL,NULL,'2025-12-29 11:16:23','2025-12-29 11:16:23',NULL),(24,15,'water','573890',NULL,NULL,'2025-12-29 11:16:23','2025-12-29 11:16:23',NULL),(25,15,'gas','3565339',NULL,NULL,'2025-12-29 11:16:23','2025-12-29 11:16:23',NULL),(26,15,'internet','1082130847',NULL,NULL,'2025-12-29 11:16:23','2025-12-29 11:16:23',NULL),(27,17,'electric','1022417651',NULL,NULL,'2025-12-29 11:19:35','2025-12-29 11:19:35',NULL),(28,17,'water','595411',NULL,NULL,'2025-12-29 11:19:35','2025-12-29 11:19:35',NULL),(29,17,'gas','3809479',NULL,NULL,'2025-12-29 11:19:35','2025-12-29 11:19:35',NULL),(30,17,'internet','1082108497',NULL,NULL,'2025-12-29 11:19:35','2025-12-29 11:19:35',NULL),(31,16,'electric','1022417726',NULL,NULL,'2025-12-29 11:21:49','2025-12-29 11:21:49',NULL),(32,16,'water','595409',NULL,NULL,'2025-12-29 11:21:49','2025-12-29 11:21:49',NULL),(33,16,'gas','3896791',NULL,NULL,'2025-12-29 11:21:49','2025-12-29 11:21:49',NULL),(34,16,'internet','1082089282',NULL,NULL,'2025-12-29 11:21:49','2025-12-29 11:21:49',NULL),(35,19,'electric','1022417708','M. YILMAZ ASLAN (KÖKSAN)',NULL,'2025-12-29 11:24:25','2025-12-29 15:34:03',NULL),(36,19,'water','595408','M. YILMAZ ASLAN (KÖKSAN)',NULL,'2025-12-29 11:24:25','2025-12-29 15:34:03',NULL),(37,19,'gas','3793884','MUSTAFA BAHCECİ',NULL,'2025-12-29 11:24:25','2025-12-29 15:34:03',NULL),(38,19,'internet','10820109546','RÜVEYDA ÖZTEPİR',NULL,'2025-12-29 11:24:25','2025-12-29 15:34:03',NULL),(39,20,'electric','1023956561',NULL,NULL,'2025-12-29 11:28:34','2025-12-29 11:28:34',NULL),(40,20,'water','620478',NULL,NULL,'2025-12-29 11:28:34','2025-12-29 11:28:34',NULL),(41,20,'gas','184271230',NULL,NULL,'2025-12-29 11:28:34','2025-12-29 11:28:34',NULL),(42,20,'internet','108213408',NULL,NULL,'2025-12-29 11:28:34','2025-12-29 11:28:34',NULL),(43,21,'gas','3565346',NULL,NULL,'2025-12-29 11:32:29','2025-12-29 11:32:29',NULL),(44,22,'electric','1022423190',NULL,NULL,'2025-12-29 11:34:30','2025-12-29 11:34:30',NULL),(45,22,'water','1526494',NULL,NULL,'2025-12-29 11:34:30','2025-12-29 11:34:30',NULL),(46,22,'gas','3839932',NULL,NULL,'2025-12-29 11:34:30','2025-12-29 11:34:30',NULL),(47,22,'internet','1082062850',NULL,NULL,'2025-12-29 11:34:30','2025-12-29 11:34:30',NULL),(48,23,'electric','1022423382',NULL,NULL,'2025-12-29 11:36:45','2025-12-29 11:36:45',NULL),(49,23,'water','573853',NULL,NULL,'2025-12-29 11:36:45','2025-12-29 11:36:45',NULL),(50,23,'gas','3565344',NULL,NULL,'2025-12-29 11:36:45','2025-12-29 11:36:45',NULL),(51,24,'electric','1022417688',NULL,NULL,'2025-12-29 11:38:52','2025-12-29 11:38:52',NULL),(52,24,'water','573885',NULL,NULL,'2025-12-29 11:38:52','2025-12-29 11:38:52',NULL),(53,24,'gas','3859048',NULL,NULL,'2025-12-29 11:38:52','2025-12-29 11:38:52',NULL),(54,25,'water','573851',NULL,NULL,'2025-12-29 11:41:18','2025-12-29 11:41:18',NULL),(55,25,'gas','3565343',NULL,NULL,'2025-12-29 11:41:18','2025-12-29 11:41:18',NULL),(56,26,'electric','1020121302',NULL,NULL,'2025-12-29 11:52:16','2025-12-29 11:52:16',NULL),(57,26,'water','549212',NULL,NULL,'2025-12-29 11:52:16','2025-12-29 11:52:16',NULL),(58,26,'gas','3682070',NULL,NULL,'2025-12-29 11:52:17','2025-12-29 11:52:17',NULL),(59,26,'internet','1082049445',NULL,NULL,'2025-12-29 11:52:17','2025-12-29 11:52:17',NULL),(60,27,'electric','10200298160',NULL,NULL,'2025-12-29 11:57:47','2025-12-29 11:57:47',NULL),(61,27,'water','549215',NULL,NULL,'2025-12-29 11:57:47','2025-12-29 11:57:47',NULL),(62,27,'gas','3723844',NULL,NULL,'2025-12-29 11:57:47','2025-12-29 11:57:47',NULL),(63,27,'internet','1082057195',NULL,NULL,'2025-12-29 11:57:47','2025-12-29 11:57:47',NULL),(64,28,'electric','1020298112',NULL,NULL,'2025-12-29 12:01:34','2025-12-29 12:01:34',NULL),(65,28,'water','549223',NULL,NULL,'2025-12-29 12:01:34','2025-12-29 12:01:34',NULL),(66,28,'gas','3723849',NULL,NULL,'2025-12-29 12:01:34','2025-12-29 12:01:34',NULL),(67,28,'internet','1082059404',NULL,NULL,'2025-12-29 12:01:34','2025-12-29 12:01:34',NULL),(68,29,'electric','1020298302',NULL,NULL,'2025-12-29 12:04:59','2025-12-29 12:04:59',NULL),(69,29,'water','549231',NULL,NULL,'2025-12-29 12:04:59','2025-12-29 12:04:59',NULL),(70,29,'gas','3723852',NULL,NULL,'2025-12-29 12:04:59','2025-12-29 12:04:59',NULL),(71,29,'internet','1082113217',NULL,NULL,'2025-12-29 12:04:59','2025-12-29 12:04:59',NULL),(72,34,'gas','4086500',NULL,NULL,'2025-12-29 12:11:44','2025-12-29 12:11:44',NULL),(73,35,'gas','4086502',NULL,NULL,'2025-12-29 12:12:49','2025-12-29 12:13:38',NULL),(74,33,'gas','4086904',NULL,NULL,'2025-12-29 12:17:01','2025-12-29 12:17:01',NULL),(75,32,'gas','4086494',NULL,NULL,'2025-12-29 12:17:27','2025-12-29 12:17:27',NULL);
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loggable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loggable_id` bigint unsigned NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `system_logs_loggable_type_loggable_id_index` (`loggable_type`,`loggable_id`),
  CONSTRAINT `system_logs_chk_1` CHECK (json_valid(`old_values`)),
  CONSTRAINT `system_logs_chk_2` CHECK (json_valid(`new_values`))
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
INSERT INTO `system_logs` VALUES (1,1,'create','App\\Models\\Location',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 10:54:47','2025-12-08 10:54:47',NULL),(2,1,'create','App\\Models\\Location',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 10:56:16','2025-12-08 10:56:16',NULL),(3,1,'delete','App\\Models\\Location',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:17:43','2025-12-08 11:17:43',NULL),(4,1,'delete','App\\Models\\Location',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:17:46','2025-12-08 11:17:46',NULL),(5,1,'create','App\\Models\\Location',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:22:44','2025-12-08 11:22:44',NULL),(6,1,'create','App\\Models\\Location',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:23:38','2025-12-08 11:23:38',NULL),(7,1,'delete','App\\Models\\Location',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:23:56','2025-12-08 11:23:56',NULL),(8,1,'create','App\\Models\\Location',5,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:24:10','2025-12-08 11:24:10',NULL),(9,1,'create','App\\Models\\Location',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:24:40','2025-12-08 11:24:40',NULL),(10,1,'create','App\\Models\\Location',7,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:31:48','2025-12-08 11:31:48',NULL),(11,1,'delete','App\\Models\\Location',7,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:32:06','2025-12-08 11:32:06',NULL),(12,1,'create','App\\Models\\Location',8,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:33:26','2025-12-08 11:33:26',NULL),(13,1,'create','App\\Models\\Resident',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:35:02','2025-12-08 11:35:02',NULL),(14,1,'create','App\\Models\\Stay',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:45:15','2025-12-08 11:45:15',NULL),(15,1,'update','App\\Models\\Stay',1,'\"{\\\"id\\\":1,\\\"resident_id\\\":1,\\\"location_id\\\":6,\\\"check_in_date\\\":\\\"2025-12-08T14:45:00.000000Z\\\",\\\"check_out_date\\\":null,\\\"check_in_items\\\":{\\\"keys\\\":\\\"1\\\",\\\"wifi\\\":\\\"1\\\",\\\"clean\\\":\\\"1\\\",\\\"ac\\\":\\\"1\\\"},\\\"check_out_items\\\":null,\\\"notes\\\":null,\\\"created_at\\\":\\\"2025-12-08T14:45:15.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-08T14:45:15.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"check_out_date\\\":\\\"2025-12-08 14:48:00\\\",\\\"check_out_items\\\":\\\"{\\\\\\\"keys_returned\\\\\\\":\\\\\\\"1\\\\\\\",\\\\\\\"room_clear\\\\\\\":\\\\\\\"1\\\\\\\"}\\\",\\\"notes\\\":\\\"\\\\n[\\\\u00c7\\\\u0131k\\\\u0131\\\\u015f Notu]: \\\",\\\"updated_at\\\":\\\"2025-12-08 14:48:31\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 11:48:31','2025-12-08 11:48:31',NULL),(16,1,'create','App\\Models\\Asset',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-08 12:28:00','2025-12-08 12:28:00',NULL),(17,1,'create','App\\Models\\Resident',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 05:11:56','2025-12-09 05:11:56',NULL),(18,1,'create','App\\Models\\Stay',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 05:12:04','2025-12-09 05:12:04',NULL),(19,1,'create','App\\Models\\Contact',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 05:43:01','2025-12-09 05:43:01',NULL),(20,1,'create','App\\Models\\Contact',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 08:15:52','2025-12-09 08:15:52',NULL),(21,1,'create','App\\Models\\ServiceAssignment',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 08:16:12','2025-12-09 08:16:12',NULL),(22,1,'create','App\\Models\\ServiceAssignment',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 08:16:22','2025-12-09 08:16:22',NULL),(23,1,'update','App\\Models\\User',1,'\"{\\\"id\\\":1,\\\"name\\\":\\\"Sistem Y\\\\u00f6neticisi\\\",\\\"email\\\":\\\"admin@koksan.com\\\",\\\"role\\\":\\\"staff\\\",\\\"email_verified_at\\\":\\\"2025-12-08T13:26:56.000000Z\\\",\\\"password\\\":\\\"$2y$12$YfkyQf7moqA5YOoROR.40esZ0u1oNP0QMPyI3SqvZH4GJVBb9w4oa\\\",\\\"remember_token\\\":null,\\\"created_at\\\":\\\"2025-12-08T13:26:56.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-08T13:26:56.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"role\\\":\\\"admin\\\",\\\"updated_at\\\":\\\"2025-12-09 11:38:00\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 08:38:00','2025-12-09 08:38:00',NULL),(24,1,'create','App\\Models\\User',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 08:38:44','2025-12-09 08:38:44',NULL),(25,1,'delete','App\\Models\\Location',8,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:09:31','2025-12-09 14:09:31',NULL),(26,1,'delete','App\\Models\\Contact',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:14:34','2025-12-09 14:14:34',NULL),(27,1,'delete','App\\Models\\Asset',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:19:09','2025-12-09 14:19:09',NULL),(28,1,'delete','App\\Models\\Location',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:20:57','2025-12-09 14:20:57',NULL),(29,1,'create','App\\Models\\Location',9,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:21:23','2025-12-09 14:21:23',NULL),(30,1,'create','App\\Models\\ServiceAssignment',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:25:23','2025-12-09 14:25:23',NULL),(31,1,'delete','App\\Models\\ServiceAssignment',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:25:28','2025-12-09 14:25:28',NULL),(32,1,'create','App\\Models\\Stay',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:37:03','2025-12-09 14:37:03',NULL),(33,1,'update','App\\Models\\Stay',3,'\"{\\\"id\\\":3,\\\"resident_id\\\":2,\\\"location_id\\\":9,\\\"check_in_date\\\":\\\"2025-12-09T14:36:00.000000Z\\\",\\\"check_out_date\\\":null,\\\"check_in_items\\\":{\\\"keys\\\":\\\"1\\\",\\\"wifi\\\":\\\"1\\\",\\\"clean\\\":\\\"1\\\",\\\"ac\\\":\\\"1\\\"},\\\"check_out_items\\\":null,\\\"notes\\\":null,\\\"created_at\\\":\\\"2025-12-09T14:37:03.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-09T14:37:03.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"check_out_date\\\":\\\"2025-12-09 17:37:00\\\",\\\"check_out_items\\\":\\\"{\\\\\\\"keys_returned\\\\\\\":\\\\\\\"1\\\\\\\",\\\\\\\"room_clear\\\\\\\":\\\\\\\"1\\\\\\\",\\\\\\\"damage_check\\\\\\\":\\\\\\\"1\\\\\\\",\\\\\\\"card_returned\\\\\\\":\\\\\\\"1\\\\\\\"}\\\",\\\"notes\\\":\\\"\\\\n[\\\\u00c7\\\\u0131k\\\\u0131\\\\u015f Notu]: \\\",\\\"updated_at\\\":\\\"2025-12-09 17:37:37\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:37:37','2025-12-09 14:37:37',NULL),(34,1,'create','App\\Models\\Asset',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:38:00','2025-12-09 14:38:00',NULL),(35,1,'delete','App\\Models\\Asset',2,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:38:19','2025-12-09 14:38:19',NULL),(36,1,'delete','App\\Models\\Resident',1,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-09 14:46:36','2025-12-09 14:46:36',NULL),(37,1,'update','App\\Models\\User',1,'\"{\\\"id\\\":1,\\\"name\\\":\\\"Sistem Y\\\\u00f6neticisi\\\",\\\"email\\\":\\\"admin@koksan.com\\\",\\\"role\\\":\\\"admin\\\",\\\"email_verified_at\\\":\\\"2025-12-08T10:26:56.000000Z\\\",\\\"password\\\":\\\"$2y$12$YfkyQf7moqA5YOoROR.40esZ0u1oNP0QMPyI3SqvZH4GJVBb9w4oa\\\",\\\"remember_token\\\":null,\\\"created_at\\\":\\\"2025-12-08T10:26:56.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-09T08:38:00.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"name\\\":\\\"Admin\\\",\\\"updated_at\\\":\\\"2025-12-10 09:46:31\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 06:46:31','2025-12-10 06:46:31',NULL),(38,1,'update','App\\Models\\User',1,'\"{\\\"id\\\":1,\\\"name\\\":\\\"Admin\\\",\\\"email\\\":\\\"admin@koksan.com\\\",\\\"role\\\":\\\"admin\\\",\\\"email_verified_at\\\":\\\"2025-12-08T10:26:56.000000Z\\\",\\\"password\\\":\\\"$2y$12$YfkyQf7moqA5YOoROR.40esZ0u1oNP0QMPyI3SqvZH4GJVBb9w4oa\\\",\\\"remember_token\\\":null,\\\"created_at\\\":\\\"2025-12-08T10:26:56.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-10T06:46:31.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"password\\\":\\\"$2y$12$icks2iONQ9XLC\\\\\\/kmF8CEyO0qNO849vSbtz3PBn85OTAL3\\\\\\/YPrWLem\\\",\\\"updated_at\\\":\\\"2025-12-10 10:05:02\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 07:05:02','2025-12-10 07:05:02',NULL),(39,1,'update','App\\Models\\ServiceAssignment',3,'\"{\\\"id\\\":3,\\\"location_id\\\":9,\\\"contact_id\\\":1,\\\"service_type\\\":\\\"electric\\\",\\\"created_at\\\":\\\"2025-12-09T14:25:23.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-09T14:25:28.000000Z\\\",\\\"deleted_at\\\":\\\"2025-12-09T14:25:28.000000Z\\\"}\"','\"{\\\"updated_at\\\":\\\"2025-12-10 10:45:36\\\",\\\"deleted_at\\\":null}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 07:45:36','2025-12-10 07:45:36',NULL),(40,1,'create','App\\Models\\Resident',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 08:01:33','2025-12-10 08:01:33',NULL),(41,1,'update','App\\Models\\Location',3,'\"{\\\"id\\\":3,\\\"parent_id\\\":null,\\\"name\\\":\\\"K\\\\u00f6ksan Beytepe Lojmanlar\\\\u0131\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":0,\\\"wifi_password\\\":null,\\\"notes\\\":null,\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-08T11:22:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-08T11:22:44.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"name\\\":\\\"K\\\\u00f6ksan Beytepe Lojman (Elif Sitesi)\\\",\\\"capacity\\\":\\\"1\\\",\\\"updated_at\\\":\\\"2025-12-10 11:04:54\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 08:04:54','2025-12-10 08:04:54',NULL),(42,1,'create','App\\Models\\Location',10,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 08:06:37','2025-12-10 08:06:37',NULL),(43,1,'create','App\\Models\\Location',11,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 08:08:02','2025-12-10 08:08:02',NULL),(44,1,'create','App\\Models\\Location',12,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 08:47:32','2025-12-10 08:47:32',NULL),(45,1,'create','App\\Models\\Location',13,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 09:16:14','2025-12-10 09:16:14',NULL),(46,1,'update','App\\Models\\Location',3,'\"{\\\"id\\\":3,\\\"parent_id\\\":null,\\\"name\\\":\\\"K\\\\u00f6ksan Beytepe Lojman (Elif Sitesi)\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":null,\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-08T11:22:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-10T08:04:54.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"name\\\":\\\"K\\\\u00d6KSAN BEYTEPE LOJMAN (EL\\\\u0130F S\\\\u0130TES\\\\u0130)\\\",\\\"updated_at\\\":\\\"2025-12-10 12:17:33\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 09:17:33','2025-12-10 09:17:33',NULL),(47,1,'create','App\\Models\\Location',14,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 09:23:59','2025-12-10 09:23:59',NULL),(48,1,'create','App\\Models\\Resident',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 09:26:49','2025-12-10 09:26:49',NULL),(49,1,'create','App\\Models\\Stay',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 09:26:59','2025-12-10 09:26:59',NULL),(50,1,'create','App\\Models\\ServiceAssignment',6,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-10 09:30:02','2025-12-10 09:30:02',NULL),(51,1,'create','App\\Models\\Contact',3,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-12 08:32:47','2025-12-12 08:32:47',NULL),(52,1,'create','App\\Models\\ServiceAssignment',7,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-12 08:33:08','2025-12-12 08:33:08',NULL),(53,1,'create','App\\Models\\Contact',4,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-12 08:33:26','2025-12-12 08:33:26',NULL),(54,1,'update','App\\Models\\ServiceAssignment',7,'\"{\\\"id\\\":7,\\\"location_id\\\":12,\\\"contact_id\\\":3,\\\"service_type\\\":\\\"water\\\",\\\"created_at\\\":\\\"2025-12-12T08:33:08.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-12T08:33:08.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"contact_id\\\":\\\"4\\\",\\\"updated_at\\\":\\\"2025-12-12 11:33:39\\\"}\"','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-12 08:33:39','2025-12-12 08:33:39',NULL),(55,1,'create','App\\Models\\User',3,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 05:56:02','2025-12-15 05:56:02',NULL),(56,1,'create','App\\Models\\User',4,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 05:56:38','2025-12-15 05:56:38',NULL),(57,1,'create','App\\Models\\User',5,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 05:57:10','2025-12-15 05:57:10',NULL),(58,1,'update','App\\Models\\Stay',4,'\"{\\\"id\\\":4,\\\"resident_id\\\":4,\\\"location_id\\\":14,\\\"check_in_date\\\":\\\"2025-12-10T09:26:00.000000Z\\\",\\\"check_out_date\\\":null,\\\"check_in_items\\\":{\\\"keys\\\":\\\"1\\\",\\\"wifi\\\":\\\"1\\\",\\\"clean\\\":\\\"1\\\",\\\"ac\\\":\\\"1\\\"},\\\"check_out_items\\\":null,\\\"notes\\\":\\\"+2 K\\\\u0130\\\\u015e\\\\u0130\\\",\\\"created_at\\\":\\\"2025-12-10T09:26:59.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-10T09:26:59.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"check_out_date\\\":\\\"2025-12-15 08:59:00\\\",\\\"check_out_items\\\":\\\"{\\\\\\\"keys_returned\\\\\\\":\\\\\\\"1\\\\\\\",\\\\\\\"room_clear\\\\\\\":\\\\\\\"1\\\\\\\",\\\\\\\"damage_check\\\\\\\":\\\\\\\"1\\\\\\\",\\\\\\\"card_returned\\\\\\\":\\\\\\\"1\\\\\\\"}\\\",\\\"notes\\\":\\\"+2 K\\\\u0130\\\\u015e\\\\u0130\\\\n[\\\\u00c7\\\\u0131k\\\\u0131\\\\u015f Notu]: \\\",\\\"updated_at\\\":\\\"2025-12-15 09:00:04\\\"}\"','10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:00:04','2025-12-15 06:00:04',NULL),(59,1,'delete','App\\Models\\Location',14,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:00:09','2025-12-15 06:00:09',NULL),(60,1,'delete','App\\Models\\Location',12,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:00:26','2025-12-15 06:00:26',NULL),(61,1,'delete','App\\Models\\Location',9,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:00:39','2025-12-15 06:00:39',NULL),(62,1,'delete','App\\Models\\Location',13,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:01:04','2025-12-15 06:01:04',NULL),(63,1,'delete','App\\Models\\Location',5,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:01:17','2025-12-15 06:01:17',NULL),(64,1,'delete','App\\Models\\Location',11,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:01:35','2025-12-15 06:01:35',NULL),(65,1,'delete','App\\Models\\Location',10,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:01:46','2025-12-15 06:01:46',NULL),(66,1,'delete','App\\Models\\Location',3,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:01:57','2025-12-15 06:01:57',NULL),(67,1,'delete','App\\Models\\Contact',1,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:02:08','2025-12-15 06:02:08',NULL),(68,1,'delete','App\\Models\\Contact',3,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:02:12','2025-12-15 06:02:12',NULL),(69,1,'delete','App\\Models\\Contact',4,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:02:16','2025-12-15 06:02:16',NULL),(70,1,'delete','App\\Models\\Resident',3,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:03:07','2025-12-15 06:03:07',NULL),(71,1,'delete','App\\Models\\Resident',4,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 06:03:11','2025-12-15 06:03:11',NULL),(72,1,'delete','App\\Models\\Resident',2,NULL,NULL,'10.10.23.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','2025-12-15 07:03:53','2025-12-15 07:03:53',NULL),(73,4,'create','App\\Models\\Location',15,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:11:54','2025-12-29 11:11:54',NULL),(74,4,'create','App\\Models\\Location',16,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:12:13','2025-12-29 11:12:13',NULL),(75,4,'create','App\\Models\\Location',17,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:12:44','2025-12-29 11:12:44',NULL),(76,4,'update','App\\Models\\Location',15,'\"{\\\"id\\\":15,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 5 NO:17\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":null,\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:11:54.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:11:54.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  KAT 10 NO:37\\\",\\\"updated_at\\\":\\\"2025-12-29 14:16:23\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:16:23','2025-12-29 11:16:23',NULL),(77,4,'create','App\\Models\\Location',18,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:17:14','2025-12-29 11:17:14',NULL),(78,4,'update','App\\Models\\Location',17,'\"{\\\"id\\\":17,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  D BLOK KAT 7 NO:25\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":null,\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:12:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:12:44.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"notes\\\":\\\"MR SAHU\\\",\\\"updated_at\\\":\\\"2025-12-29 14:19:35\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:19:35','2025-12-29 11:19:35',NULL),(79,4,'update','App\\Models\\Location',16,'\"{\\\"id\\\":16,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 5 NO:18\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":null,\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:12:13.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:12:13.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"notes\\\":\\\"MUSTAFA BUYRUKO\\\\u011eLU 02.11.2025 TAR\\\\u0130H\\\\u0130NDE TESL\\\\u0130M ED\\\\u0130LD\\\\u0130\\\",\\\"updated_at\\\":\\\"2025-12-29 14:21:49\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:21:49','2025-12-29 11:21:49',NULL),(80,4,'delete','App\\Models\\Location',18,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:22:38','2025-12-29 11:22:38',NULL),(81,4,'create','App\\Models\\Location',19,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:23:03','2025-12-29 11:23:03',NULL),(82,4,'update','App\\Models\\Location',19,'\"{\\\"id\\\":19,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 5 NO:17\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MR S\\\\u0130BGH\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:23:03.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:23:03.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"notes\\\":\\\"MR S\\\\u0130NGH+ 1 H\\\\u0130NL\\\\u0130\\\",\\\"updated_at\\\":\\\"2025-12-29 14:24:25\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:24:25','2025-12-29 11:24:25',NULL),(83,4,'create','App\\Models\\Location',20,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:25:22','2025-12-29 11:25:22',NULL),(84,4,'update','App\\Models\\Location',20,'\"{\\\"id\\\":20,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 10 NO:37 K\\\\u0130RALIK\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MAV\\\\u0130 YAKA H\\\\u0130NTL\\\\u0130\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:25:22.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:25:22.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"ownership\\\":\\\"rented\\\",\\\"landlord_name\\\":\\\"RECEP KAST\\\\u00dcL\\\",\\\"landlord_phone\\\":\\\"05325044727\\\",\\\"updated_at\\\":\\\"2025-12-29 14:28:34\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:28:34','2025-12-29 11:28:34',NULL),(85,4,'create','App\\Models\\Location',21,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:29:24','2025-12-29 11:29:24',NULL),(86,4,'create','App\\Models\\Location',22,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:33:08','2025-12-29 11:33:08',NULL),(87,4,'create','App\\Models\\Location',23,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:35:13','2025-12-29 11:35:13',NULL),(88,4,'create','App\\Models\\Location',24,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:37:33','2025-12-29 11:37:33',NULL),(89,4,'create','App\\Models\\Location',25,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:40:38','2025-12-29 11:40:38',NULL),(90,4,'create','App\\Models\\Location',26,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:45:25','2025-12-29 11:45:25',NULL),(91,4,'create','App\\Models\\Location',27,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:53:19','2025-12-29 11:53:19',NULL),(92,4,'create','App\\Models\\Location',28,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 11:58:16','2025-12-29 11:58:16',NULL),(93,4,'create','App\\Models\\Location',29,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:02:10','2025-12-29 12:02:10',NULL),(94,4,'update','App\\Models\\Location',29,'\"{\\\"id\\\":29,\\\"parent_id\\\":null,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130 TU\\\\u011e\\\\u00c7E S\\\\u0130TES\\\\u0130 C BLOK KAT 8 NO:31\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"ERCAN EREN\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:02:10.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:02:10.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"ownership\\\":\\\"rented\\\",\\\"landlord_name\\\":\\\"SERHAT T\\\\u00dcMER\\\",\\\"landlord_phone\\\":\\\"05326965586\\\",\\\"updated_at\\\":\\\"2025-12-29 15:04:59\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:04:59','2025-12-29 12:04:59',NULL),(95,4,'create','App\\Models\\Location',30,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:06:19','2025-12-29 12:06:19',NULL),(96,4,'update','App\\Models\\Location',30,'\"{\\\"id\\\":30,\\\"parent_id\\\":null,\\\"name\\\":\\\"FISTIKLI MAHALALES\\\\u0130 S\\\\u0130NFA GARDEN S\\\\u0130TES\\\\u0130 NO:27\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"H\\\\u00dcSEY\\\\u0130N PARLAR\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:06:19.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:06:19.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"ownership\\\":\\\"rented\\\",\\\"landlord_name\\\":\\\"MUSTAFA T\\\\u00dcMER\\\",\\\"landlord_phone\\\":\\\"05432388378\\\",\\\"updated_at\\\":\\\"2025-12-29 15:07:15\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:07:15','2025-12-29 12:07:15',NULL),(97,4,'create','App\\Models\\Location',31,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:08:22','2025-12-29 12:08:22',NULL),(98,4,'create','App\\Models\\Location',32,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:09:14','2025-12-29 12:09:14',NULL),(99,4,'create','App\\Models\\Location',33,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:09:36','2025-12-29 12:09:36',NULL),(100,4,'create','App\\Models\\Location',34,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:10:01','2025-12-29 12:10:01',NULL),(101,4,'create','App\\Models\\Location',35,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:10:21','2025-12-29 12:10:21',NULL),(102,4,'create','App\\Models\\Location',36,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:11:00','2025-12-29 12:11:00',NULL),(103,4,'create','App\\Models\\Location',37,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:11:18','2025-12-29 12:11:18',NULL),(104,4,'update','App\\Models\\Location',34,'\"{\\\"id\\\":34,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 1 NO:3\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"AHMET AKIN-UMUT AKG\\\\u00dcN\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:10:01.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:10:01.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 2 NO:3\\\",\\\"updated_at\\\":\\\"2025-12-29 15:11:44\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:11:44','2025-12-29 12:11:44',NULL),(105,4,'update','App\\Models\\Location',35,'\"{\\\"id\\\":35,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 1 NO:4\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"RU\\\\u015eEN KARABUDAK\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:10:21.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:10:21.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 2 NO:4\\\",\\\"updated_at\\\":\\\"2025-12-29 15:12:49\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:12:49','2025-12-29 12:12:49',NULL),(106,4,'create','App\\Models\\Location',38,NULL,NULL,'10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:18:26','2025-12-29 12:18:26',NULL),(107,4,'update','App\\Models\\Location',38,'\"{\\\"id\\\":38,\\\"parent_id\\\":null,\\\"name\\\":\\\"B\\\\u0130NEVLER MAHALLES\\\\u0130 MURAT APARTMANI KAT:2 DA\\\\u0130RE 7\\\",\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MEHMET KUZU\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:18:26.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:18:26.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"ownership\\\":\\\"rented\\\",\\\"landlord_name\\\":\\\"\\\\u00d6MER ASIM \\\\u00d6ZSEVER\\\",\\\"landlord_phone\\\":\\\"05323416241\\\",\\\"updated_at\\\":\\\"2025-12-29 15:42:26\\\"}\"','10.10.22.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','2025-12-29 12:42:26','2025-12-29 12:42:26',NULL),(108,1,'create','App\\Models\\Location',39,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:41','2025-12-29 15:15:41',NULL),(109,1,'update','App\\Models\\Location',15,'\"{\\\"id\\\":15,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  KAT 10 NO:37\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":null,\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:11:54.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:16:23.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":39,\\\"name\\\":\\\"KAT 10 NO:37\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:41\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:41','2025-12-29 15:15:41',NULL),(110,1,'create','App\\Models\\Location',40,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(111,1,'update','App\\Models\\Location',16,'\"{\\\"id\\\":16,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 5 NO:18\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MUSTAFA BUYRUKO\\\\u011eLU 02.11.2025 TAR\\\\u0130H\\\\u0130NDE TESL\\\\u0130M ED\\\\u0130LD\\\\u0130\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:12:13.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:21:49.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":40,\\\"name\\\":\\\"KAT 5 NO:18\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(112,1,'create','App\\Models\\Location',41,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(113,1,'update','App\\Models\\Location',17,'\"{\\\"id\\\":17,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  D BLOK KAT 7 NO:25\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MR SAHU\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:12:44.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:19:35.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":41,\\\"name\\\":\\\"KAT 7 NO:25\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(114,1,'update','App\\Models\\Location',19,'\"{\\\"id\\\":19,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 5 NO:17\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MR S\\\\u0130NGH+ 1 H\\\\u0130NL\\\\u0130\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:23:03.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:24:25.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":40,\\\"name\\\":\\\"KAT 5 NO:17\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(115,1,'update','App\\Models\\Location',20,'\"{\\\"id\\\":20,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 10 NO:37 K\\\\u0130RALIK\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"rented\\\",\\\"landlord_name\\\":\\\"RECEP KAST\\\\u00dcL\\\",\\\"landlord_phone\\\":\\\"05325044727\\\",\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MAV\\\\u0130 YAKA H\\\\u0130NTL\\\\u0130\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:25:22.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:28:34.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":40,\\\"name\\\":\\\"KAT 10 NO:37 K\\\\u0130RALIK\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(116,1,'update','App\\Models\\Location',21,'\"{\\\"id\\\":21,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 8 NO:32\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MEHMET KIVRAK\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:29:24.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:29:24.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":40,\\\"name\\\":\\\"KAT 8 NO:32\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(117,1,'update','App\\Models\\Location',22,'\"{\\\"id\\\":22,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  D BLOK KAT 2 NO:6\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"BEYAZ YAKA H\\\\u0130NTL\\\\u0130\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:33:08.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:33:08.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":41,\\\"name\\\":\\\"KAT 2 NO:6\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(118,1,'update','App\\Models\\Location',23,'\"{\\\"id\\\":23,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  C BLOK KAT 5 NO:19\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"\\\\u00d6ZLEM Y\\\\u00dcKSEL\\\\u0130C\\\\u0130\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:35:13.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:35:13.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":40,\\\"name\\\":\\\"KAT 5 NO:19\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(119,1,'update','App\\Models\\Location',24,'\"{\\\"id\\\":24,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130  D BLOK KAT 8 NO:30\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"AYKUT KURUM\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:37:33.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:37:33.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":41,\\\"name\\\":\\\"KAT 8 NO:30\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(120,1,'update','App\\Models\\Location',25,'\"{\\\"id\\\":25,\\\"parent_id\\\":null,\\\"name\\\":\\\"BEYTEPE EL\\\\u0130F S\\\\u0130TES\\\\u0130 C BLOK KAT 4 NO:15\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"FEYZA TOKTAY\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:40:38.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:40:38.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":40,\\\"name\\\":\\\"KAT 4 NO:15\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(121,1,'create','App\\Models\\Location',42,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(122,1,'create','App\\Models\\Location',43,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(123,1,'update','App\\Models\\Location',26,'\"{\\\"id\\\":26,\\\"parent_id\\\":null,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130 TU\\\\u011e\\\\u00c7E S\\\\u0130TES\\\\u0130  C BLOK KAT 3 NO:12\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MR.KHURANA\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:45:25.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:45:25.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":43,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130    KAT 3 NO:12\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(124,1,'update','App\\Models\\Location',27,'\"{\\\"id\\\":27,\\\"parent_id\\\":null,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130 TU\\\\u011e\\\\u00c7E S\\\\u0130TES\\\\u0130 C BLOK KAT 4 NO:15\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"ERHAN AKG\\\\u00dcL\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:53:19.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:53:19.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":43,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130   KAT 4 NO:15\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(125,1,'update','App\\Models\\Location',28,'\"{\\\"id\\\":28,\\\"parent_id\\\":null,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130 TU\\\\u011e\\\\u00c7E S\\\\u0130TES\\\\u0130 C BLOK KAT 6 NO:23\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MR PATEL\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T11:58:16.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T11:58:16.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":43,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130   KAT 6 NO:23\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(126,1,'update','App\\Models\\Location',29,'\"{\\\"id\\\":29,\\\"parent_id\\\":null,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130 TU\\\\u011e\\\\u00c7E S\\\\u0130TES\\\\u0130 C BLOK KAT 8 NO:31\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"rented\\\",\\\"landlord_name\\\":\\\"SERHAT T\\\\u00dcMER\\\",\\\"landlord_phone\\\":\\\"05326965586\\\",\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"ERCAN EREN\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:02:10.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:04:59.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":43,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130   KAT 8 NO:31\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(127,1,'create','App\\Models\\Location',44,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(128,1,'create','App\\Models\\Location',45,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(129,1,'update','App\\Models\\Location',31,'\"{\\\"id\\\":31,\\\"parent_id\\\":null,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130 ASRIN S\\\\u0130TES\\\\u0130 A BLOK KAT 4 NO:15\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"ZEYNEL KIPIRTI\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:08:22.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:08:22.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":45,\\\"name\\\":\\\"FISTIKLIK MAHALLES\\\\u0130   KAT 4 NO:15\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(130,1,'create','App\\Models\\Location',46,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(131,1,'update','App\\Models\\Location',32,'\"{\\\"id\\\":32,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 1 NO:1\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"AHMET BA\\\\u015e\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:09:14.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:09:14.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":46,\\\"name\\\":\\\"KAT 1 NO:1\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(132,1,'update','App\\Models\\Location',33,'\"{\\\"id\\\":33,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 1 NO:2\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MET\\\\u0130N AKDEN\\\\u0130Z\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:09:36.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:09:36.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":46,\\\"name\\\":\\\"KAT 1 NO:2\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(133,1,'update','App\\Models\\Location',34,'\"{\\\"id\\\":34,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 2 NO:3\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"AHMET AKIN-UMUT AKG\\\\u00dcN\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:10:01.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:11:44.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":46,\\\"name\\\":\\\"KAT 2 NO:3\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(134,1,'update','App\\Models\\Location',35,'\"{\\\"id\\\":35,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 2 NO:4\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"RU\\\\u015eEN KARABUDAK\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:10:21.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:12:49.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":46,\\\"name\\\":\\\"KAT 2 NO:4\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(135,1,'update','App\\Models\\Location',36,'\"{\\\"id\\\":36,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 0  NO:5\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"EM\\\\u0130N KANDEM\\\\u0130R\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:11:00.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:11:00.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":46,\\\"name\\\":\\\"KAT 0  NO:5\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(136,1,'update','App\\Models\\Location',37,'\"{\\\"id\\\":37,\\\"parent_id\\\":null,\\\"name\\\":\\\"15 TEMMUZ LOJMAN KAT 0 NO:6\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"owned\\\",\\\"landlord_name\\\":null,\\\"landlord_phone\\\":null,\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"ASIM K\\\\u00d6KO\\\\u011eLU\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:11:18.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:11:18.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":46,\\\"name\\\":\\\"KAT 0 NO:6\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(137,1,'create','App\\Models\\Location',47,NULL,NULL,'10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL),(138,1,'update','App\\Models\\Location',38,'\"{\\\"id\\\":38,\\\"parent_id\\\":null,\\\"name\\\":\\\"B\\\\u0130NEVLER MAHALLES\\\\u0130 MURAT APARTMANI KAT:2 DA\\\\u0130RE 7\\\",\\\"address\\\":null,\\\"map_link\\\":null,\\\"type\\\":\\\"site\\\",\\\"ownership\\\":\\\"rented\\\",\\\"landlord_name\\\":\\\"\\\\u00d6MER ASIM \\\\u00d6ZSEVER\\\",\\\"landlord_phone\\\":\\\"05323416241\\\",\\\"capacity\\\":1,\\\"wifi_password\\\":null,\\\"notes\\\":\\\"MEHMET KUZU\\\",\\\"is_active\\\":1,\\\"created_at\\\":\\\"2025-12-29T12:18:26.000000Z\\\",\\\"updated_at\\\":\\\"2025-12-29T12:42:26.000000Z\\\",\\\"deleted_at\\\":null}\"','\"{\\\"parent_id\\\":47,\\\"name\\\":\\\"KAT:2 DA\\\\u0130RE 7\\\",\\\"type\\\":\\\"apartment\\\",\\\"updated_at\\\":\\\"2025-12-29 18:15:42\\\"}\"','10.10.23.141','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2025-12-29 15:15:42','2025-12-29 15:15:42',NULL);
/*!40000 ALTER TABLE `system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@koksan.com','admin','2025-12-08 10:26:56','$2y$12$icks2iONQ9XLC/kmF8CEyO0qNO849vSbtz3PBn85OTAL3/YPrWLem',NULL,'2025-12-08 10:26:56','2025-12-10 07:05:02',NULL),(2,'İdari İşler','idari@koksan.com','staff',NULL,'$2y$12$a8CfA6wY3lWJFUqDKfDuQOYCMDpQv6LoNAIwYqTRRCxIRSpCau3cq',NULL,'2025-12-09 08:38:44','2025-12-09 08:38:44',NULL),(3,'Leylanur Yardi','leylanur.yardi@koksan.com','staff',NULL,'$2y$12$Va9OhI4v8HviTwywdRMMB.I0nREPnu0kqmGO6cLtm0Yrf1w47rzk6',NULL,'2025-12-15 05:56:02','2025-12-15 05:56:02',NULL),(4,'Özgül Aslan','ozgul.aslan@koksan.com','staff',NULL,'$2y$12$OiGJ80XXtpntgyp8wYXHXelOMXUa2UNF/uwEDRhTxkVwjKKB.zkA6',NULL,'2025-12-15 05:56:38','2025-12-15 05:56:38',NULL),(5,'Rüveyda Öztepir','ruveyda.oztepir@koksan.com','staff',NULL,'$2y$12$38nXBk3xxOS/zIWxe0JaJut48eTt0ASoy1CfxkxnrKHADXcLHjx0C',NULL,'2025-12-15 05:57:10','2025-12-15 05:57:10',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-02 10:19:00
