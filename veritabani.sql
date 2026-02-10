-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: tedarik_db
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
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',29,'App\\Models\\User',1,'{\"old\": {\"role\": \"kullanici\", \"updated_at\": \"2025-12-15T07:22:40.000000Z\"}, \"attributes\": {\"role\": \"lojistik_personeli\", \"updated_at\": \"2025-12-15T07:48:53.000000Z\"}}',NULL,'2025-12-15 07:48:53','2025-12-15 07:48:53'),(2,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',21,'App\\Models\\User',1,'{\"old\": {\"role\": \"kullanici\", \"updated_at\": \"2025-12-02T15:01:23.000000Z\"}, \"attributes\": {\"role\": \"bakim_personeli\", \"updated_at\": \"2025-12-15T07:50:31.000000Z\"}}',NULL,'2025-12-15 07:50:31','2025-12-15 07:50:31'),(3,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',23,'App\\Models\\User',1,'{\"old\": {\"role\": \"kullanici\", \"updated_at\": \"2025-12-04T09:49:33.000000Z\"}, \"attributes\": {\"role\": \"idari_isler_personeli\", \"updated_at\": \"2025-12-15T07:58:28.000000Z\"}}',NULL,'2025-12-15 07:58:28','2025-12-15 07:58:28'),(4,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',24,'App\\Models\\User',1,'{\"old\": {\"role\": \"kullanici\", \"updated_at\": \"2025-12-04T09:50:19.000000Z\"}, \"attributes\": {\"role\": \"idari_isler_personeli\", \"updated_at\": \"2025-12-15T07:58:37.000000Z\"}}',NULL,'2025-12-15 07:58:37','2025-12-15 07:58:37'),(5,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',25,'App\\Models\\User',1,'{\"old\": {\"role\": \"kullanici\", \"updated_at\": \"2025-12-04T09:51:01.000000Z\"}, \"attributes\": {\"role\": \"idari_isler_personeli\", \"updated_at\": \"2025-12-15T07:58:43.000000Z\"}}',NULL,'2025-12-15 07:58:43','2025-12-15 07:58:43'),(6,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',9,'App\\Models\\User',1,'{\"old\": {\"updated_at\": \"2025-11-06T09:28:59.000000Z\", \"department_id\": null}, \"attributes\": {\"updated_at\": \"2025-12-15T07:59:05.000000Z\", \"department_id\": 1}}',NULL,'2025-12-15 07:59:05','2025-12-15 07:59:05'),(7,'default','yeni bir shipment kaydı oluşturdu.','App\\Models\\Shipment','created',1,'App\\Models\\User',29,'{\"attributes\": {\"id\": 1, \"plaka\": \"27 ABC 123\", \"user_id\": 29, \"gemi_adi\": null, \"arac_tipi\": \"kamyon\", \"sofor_adi\": \"deneme\", \"created_at\": \"2025-12-15T08:01:25.000000Z\", \"created_by\": null, \"deleted_at\": null, \"dosya_yolu\": null, \"kargo_tipi\": \"Ton (T)\", \"updated_at\": \"2025-12-15T08:01:25.000000Z\", \"aciklamalar\": null, \"cikis_tarihi\": \"2025-12-15T09:01:00.000000Z\", \"imo_numarasi\": null, \"is_important\": false, \"varis_limani\": null, \"dorse_plakasi\": null, \"kalkis_limani\": null, \"kargo_icerigi\": \"kopet\", \"kargo_miktari\": \"13\", \"shipment_type\": \"import\", \"varis_noktasi\": \"deneme\", \"kalkis_noktasi\": \"deneme\", \"ekstra_bilgiler\": null, \"business_unit_id\": 1, \"onaylanma_tarihi\": null, \"onaylayan_user_id\": null, \"tahmini_varis_tarihi\": \"2025-12-15T12:01:00.000000Z\"}}',NULL,'2025-12-15 08:01:25','2025-12-15 08:01:25'),(8,'default','bir shipment kaydını güncelledi.','App\\Models\\Shipment','updated',1,'App\\Models\\User',29,'{\"old\": {\"updated_at\": \"2025-12-15T08:01:25.000000Z\", \"onaylanma_tarihi\": null, \"onaylayan_user_id\": null}, \"attributes\": {\"updated_at\": \"2025-12-15T08:48:27.000000Z\", \"onaylanma_tarihi\": \"2025-12-15T08:48:27.000000Z\", \"onaylayan_user_id\": 29}}',NULL,'2025-12-15 08:48:27','2025-12-15 08:48:27'),(9,'default','bir shipment kaydını sildi.','App\\Models\\Shipment','deleted',1,'App\\Models\\User',29,'{\"old\": {\"id\": 1, \"plaka\": \"27 ABC 123\", \"user_id\": 29, \"gemi_adi\": null, \"arac_tipi\": \"kamyon\", \"sofor_adi\": \"deneme\", \"created_at\": \"2025-12-15T08:01:25.000000Z\", \"created_by\": null, \"deleted_at\": \"2025-12-15T08:49:06.000000Z\", \"dosya_yolu\": null, \"kargo_tipi\": \"Ton (T)\", \"updated_at\": \"2025-12-15T08:49:06.000000Z\", \"aciklamalar\": null, \"cikis_tarihi\": \"2025-12-15T09:01:00.000000Z\", \"imo_numarasi\": null, \"is_important\": false, \"varis_limani\": null, \"dorse_plakasi\": null, \"kalkis_limani\": null, \"kargo_icerigi\": \"kopet\", \"kargo_miktari\": \"13\", \"shipment_type\": \"import\", \"varis_noktasi\": \"deneme\", \"kalkis_noktasi\": \"deneme\", \"ekstra_bilgiler\": null, \"business_unit_id\": 1, \"onaylanma_tarihi\": \"2025-12-15T08:48:27.000000Z\", \"onaylayan_user_id\": 29, \"tahmini_varis_tarihi\": \"2025-12-15T12:01:00.000000Z\"}}',NULL,'2025-12-15 08:49:07','2025-12-15 08:49:07'),(10,'default','yeni bir vehicle assignment kaydı oluşturdu.','App\\Models\\VehicleAssignment','created',1,'App\\Models\\User',19,'{\"attributes\": {\"id\": 1, \"notes\": null, \"title\": \"SEDEX DENETİMİ\", \"end_km\": null, \"status\": \"pending\", \"user_id\": 19, \"end_time\": \"2025-12-15T15:00:00.000000Z\", \"start_km\": null, \"fuel_cost\": null, \"created_at\": \"2025-12-15T11:26:18.000000Z\", \"created_by\": null, \"deleted_at\": null, \"start_time\": \"2025-12-15T11:30:00.000000Z\", \"updated_at\": \"2025-12-15T11:26:18.000000Z\", \"vehicle_id\": null, \"assigned_by\": 19, \"customer_id\": null, \"destination\": \"KÖKSAN MERKEZ\", \"resource_id\": null, \"is_important\": false, \"vehicle_type\": null, \"resource_type\": null, \"end_fuel_level\": null, \"requester_name\": \"Ömer Faruk Akpınar\", \"responsible_id\": 19, \"assignment_type\": \"general\", \"business_unit_id\": null, \"responsible_type\": \"App\\\\Models\\\\User\", \"start_fuel_level\": null, \"task_description\": \"SEDEX FİRMASI FABRİKA DENETİMİ BELGİN ATAK BİLGİSİ DAHİLİNDE\"}}',NULL,'2025-12-15 11:26:18','2025-12-15 11:26:18'),(11,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',1,'App\\Models\\User',19,'{\"old\": {\"notes\": null, \"status\": \"pending\", \"updated_at\": \"2025-12-15T11:26:18.000000Z\"}, \"attributes\": {\"notes\": \"EMEL YILMAZ SEDEX DENETİMİ\", \"status\": \"in_progress\", \"updated_at\": \"2025-12-15T11:26:53.000000Z\"}}',NULL,'2025-12-15 11:26:53','2025-12-15 11:26:53'),(12,'default','yeni bir team kaydı oluşturdu.','App\\Models\\Team','created',1,'App\\Models\\User',19,'{\"attributes\": {\"id\": 1, \"name\": \"İDARİ İŞLER EKİBİ\", \"is_active\": true, \"created_at\": \"2025-12-15T11:28:36.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-15T11:28:36.000000Z\", \"description\": null, \"created_by_user_id\": 19}}',NULL,'2025-12-15 11:28:36','2025-12-15 11:28:36'),(13,'default','yeni bir vehicle assignment kaydı oluşturdu.','App\\Models\\VehicleAssignment','created',2,'App\\Models\\User',19,'{\"attributes\": {\"id\": 2, \"notes\": null, \"title\": \"SABUNCULAR FİRMASI DEENTİM\", \"end_km\": null, \"status\": \"pending\", \"user_id\": 19, \"end_time\": \"2025-12-15T15:00:00.000000Z\", \"start_km\": null, \"fuel_cost\": null, \"created_at\": \"2025-12-15T11:32:38.000000Z\", \"created_by\": null, \"deleted_at\": null, \"start_time\": \"2025-12-15T11:33:00.000000Z\", \"updated_at\": \"2025-12-15T11:32:38.000000Z\", \"vehicle_id\": null, \"assigned_by\": 19, \"customer_id\": null, \"destination\": \"KÖKSAN MERKEZ\", \"resource_id\": null, \"is_important\": false, \"vehicle_type\": null, \"resource_type\": null, \"end_fuel_level\": null, \"requester_name\": \"Ömer Faruk Akpınar\", \"responsible_id\": 19, \"assignment_type\": \"general\", \"business_unit_id\": null, \"responsible_type\": \"App\\\\Models\\\\User\", \"start_fuel_level\": null, \"task_description\": \"SABUNCULAR FİRMASI DENETİMİ SERKAN TÖLEK NEZARETİNDE\"}}',NULL,'2025-12-15 11:32:38','2025-12-15 11:32:38'),(14,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',2,'App\\Models\\User',19,'{\"old\": {\"notes\": null, \"status\": \"pending\", \"updated_at\": \"2025-12-15T11:32:38.000000Z\"}, \"attributes\": {\"notes\": \"BATIHAN BEY\", \"status\": \"in_progress\", \"updated_at\": \"2025-12-15T11:33:30.000000Z\"}}',NULL,'2025-12-15 11:33:30','2025-12-15 11:33:30'),(15,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',2,'App\\Models\\User',19,'{\"old\": {\"status\": \"in_progress\", \"updated_at\": \"2025-12-15T11:33:30.000000Z\"}, \"attributes\": {\"status\": \"completed\", \"updated_at\": \"2025-12-16T05:23:58.000000Z\"}}',NULL,'2025-12-16 05:23:58','2025-12-16 05:23:58'),(16,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',1,'App\\Models\\User',19,'{\"old\": {\"status\": \"in_progress\", \"updated_at\": \"2025-12-15T11:26:53.000000Z\"}, \"attributes\": {\"status\": \"completed\", \"updated_at\": \"2025-12-16T05:24:14.000000Z\"}}',NULL,'2025-12-16 05:24:14','2025-12-16 05:24:14'),(17,'default','yeni bir vehicle assignment kaydı oluşturdu.','App\\Models\\VehicleAssignment','created',3,'App\\Models\\User',19,'{\"attributes\": {\"id\": 3, \"notes\": null, \"title\": \"SEDEX DENETİM\", \"end_km\": null, \"status\": \"pending\", \"user_id\": 19, \"end_time\": \"2025-12-16T15:00:00.000000Z\", \"start_km\": null, \"fuel_cost\": null, \"created_at\": \"2025-12-16T05:25:18.000000Z\", \"created_by\": null, \"deleted_at\": null, \"start_time\": \"2025-12-16T07:00:00.000000Z\", \"updated_at\": \"2025-12-16T05:25:18.000000Z\", \"vehicle_id\": null, \"assigned_by\": 19, \"customer_id\": null, \"destination\": \"KÖKSAN MERKEZ\", \"resource_id\": null, \"is_important\": false, \"vehicle_type\": null, \"resource_type\": null, \"end_fuel_level\": null, \"requester_name\": \"Ömer Faruk Akpınar\", \"responsible_id\": 19, \"assignment_type\": \"general\", \"business_unit_id\": null, \"responsible_type\": \"App\\\\Models\\\\User\", \"start_fuel_level\": null, \"task_description\": \"SEDX DENETİM\"}}',NULL,'2025-12-16 05:25:18','2025-12-16 05:25:18'),(18,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',3,'App\\Models\\User',19,'{\"old\": {\"status\": \"pending\", \"updated_at\": \"2025-12-16T05:25:18.000000Z\"}, \"attributes\": {\"status\": \"in_progress\", \"updated_at\": \"2025-12-16T06:11:36.000000Z\"}}',NULL,'2025-12-16 06:11:36','2025-12-16 06:11:36'),(19,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',19,'App\\Models\\User',1,'{\"old\": {\"role\": \"mudur\", \"updated_at\": \"2025-12-02T14:43:51.000000Z\"}, \"attributes\": {\"role\": \"user\", \"updated_at\": \"2025-12-16T07:05:49.000000Z\"}}',NULL,'2025-12-16 07:05:49','2025-12-16 07:05:49'),(20,'default','yeni bir vehicle assignment kaydı oluşturdu.','App\\Models\\VehicleAssignment','created',4,'App\\Models\\User',19,'{\"attributes\": {\"id\": 4, \"notes\": null, \"title\": \"ZİYARET\", \"end_km\": null, \"status\": \"pending\", \"user_id\": 19, \"end_time\": \"2025-12-16T11:00:00.000000Z\", \"start_km\": null, \"fuel_cost\": null, \"created_at\": \"2025-12-16T08:07:31.000000Z\", \"created_by\": null, \"deleted_at\": null, \"start_time\": \"2025-12-16T08:10:00.000000Z\", \"updated_at\": \"2025-12-16T08:07:31.000000Z\", \"vehicle_id\": null, \"assigned_by\": 19, \"customer_id\": null, \"destination\": \"KÖKSAN MERKEZ\", \"resource_id\": null, \"is_important\": false, \"vehicle_type\": null, \"resource_type\": null, \"end_fuel_level\": null, \"requester_name\": \"Ömer Faruk Akpınar\", \"responsible_id\": 19, \"assignment_type\": \"general\", \"business_unit_id\": null, \"responsible_type\": \"App\\\\Models\\\\User\", \"start_fuel_level\": null, \"task_description\": \"GÜLSAN VE SANKO DAN MİSAFİRLER ERHAN AKGÜL BEYİN MİSAFİRLERİ\"}}',NULL,'2025-12-16 08:07:31','2025-12-16 08:07:31'),(21,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',4,'App\\Models\\User',19,'{\"old\": {\"notes\": null, \"status\": \"pending\", \"updated_at\": \"2025-12-16T08:07:31.000000Z\"}, \"attributes\": {\"notes\": \"ERHAN AKGÜL MİSAFİRİ\", \"status\": \"in_progress\", \"updated_at\": \"2025-12-16T08:07:55.000000Z\"}}',NULL,'2025-12-16 08:07:55','2025-12-16 08:07:55'),(22,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',4,'App\\Models\\User',19,'{\"old\": {\"notes\": \"ERHAN AKGÜL MİSAFİRİ\", \"updated_at\": \"2025-12-16T08:07:55.000000Z\"}, \"attributes\": {\"notes\": \"ERHAN AKGÜL MİSAFİRİ ALPHAN ULUTAŞ VE  GÜLBEY EKİCİ\", \"updated_at\": \"2025-12-16T08:08:39.000000Z\"}}',NULL,'2025-12-16 08:08:39','2025-12-16 08:08:39'),(23,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',4,'App\\Models\\User',19,'{\"old\": {\"status\": \"in_progress\", \"updated_at\": \"2025-12-16T08:08:39.000000Z\"}, \"attributes\": {\"status\": \"completed\", \"updated_at\": \"2025-12-17T05:55:56.000000Z\"}}',NULL,'2025-12-17 05:55:56','2025-12-17 05:55:56'),(24,'default','yeni bir vehicle assignment kaydı oluşturdu.','App\\Models\\VehicleAssignment','created',5,'App\\Models\\User',19,'{\"attributes\": {\"id\": 5, \"notes\": null, \"title\": \"SEDEX DENETİM EMEL YILMAZ\", \"end_km\": null, \"status\": \"pending\", \"user_id\": 19, \"end_time\": \"2025-12-17T15:00:00.000000Z\", \"start_km\": null, \"fuel_cost\": null, \"created_at\": \"2025-12-17T05:57:46.000000Z\", \"created_by\": null, \"deleted_at\": null, \"start_time\": \"2025-12-17T06:00:00.000000Z\", \"updated_at\": \"2025-12-17T05:57:46.000000Z\", \"vehicle_id\": null, \"assigned_by\": 19, \"customer_id\": null, \"destination\": \"KÖKSAN MERKEZ\", \"resource_id\": null, \"is_important\": false, \"vehicle_type\": null, \"resource_type\": null, \"end_fuel_level\": null, \"requester_name\": \"Ömer Faruk Akpınar\", \"responsible_id\": 19, \"assignment_type\": \"general\", \"business_unit_id\": null, \"responsible_type\": \"App\\\\Models\\\\User\", \"start_fuel_level\": null, \"task_description\": \"SEDEX DENETİM 3. GÜNÜ BELGİN ATAK EŞLİĞİNDE KIRMIZI ODADA DEVAM ETMEKTE\"}}',NULL,'2025-12-17 05:57:46','2025-12-17 05:57:46'),(25,'default','bir vehicle assignment kaydını güncelledi.','App\\Models\\VehicleAssignment','updated',5,'App\\Models\\User',19,'{\"old\": {\"status\": \"pending\", \"updated_at\": \"2025-12-17T05:57:46.000000Z\"}, \"attributes\": {\"status\": \"in_progress\", \"updated_at\": \"2025-12-17T05:57:55.000000Z\"}}',NULL,'2025-12-17 05:57:55','2025-12-17 05:57:55'),(26,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',27,'App\\Models\\User',1,'{\"old\": {\"role\": \"kullanici\", \"updated_at\": \"2025-12-12T12:55:42.000000Z\"}, \"attributes\": {\"role\": \"lojistik_personeli\", \"updated_at\": \"2025-12-17T11:02:35.000000Z\"}}',NULL,'2025-12-17 11:02:35','2025-12-17 11:02:35'),(27,'default','yeni bir production plan kaydı oluşturdu.','App\\Models\\ProductionPlan','created',2,'App\\Models\\User',1,'{\"attributes\": {\"id\": 2, \"user_id\": 1, \"created_at\": \"2025-12-18T08:27:39.000000Z\", \"created_by\": null, \"deleted_at\": null, \"plan_title\": \"test\", \"updated_at\": \"2025-12-18T08:27:39.000000Z\", \"is_important\": false, \"plan_details\": [{\"machine\": \"test\", \"product\": \"123456\", \"birim_id\": \"4\", \"quantity\": \"13\"}], \"week_start_date\": \"2025-12-17T21:00:00.000000Z\", \"business_unit_id\": 1}}',NULL,'2025-12-18 08:27:39','2025-12-18 08:27:39'),(28,'default','yeni bir shipment kaydı oluşturdu.','App\\Models\\Shipment','created',2,'App\\Models\\User',1,'{\"attributes\": {\"id\": 2, \"plaka\": \"test\", \"user_id\": 1, \"gemi_adi\": null, \"arac_tipi\": \"kamyon\", \"sofor_adi\": \"test\", \"created_at\": \"2025-12-18T08:28:36.000000Z\", \"created_by\": null, \"deleted_at\": null, \"dosya_yolu\": \"sevkiyat_dosyalari/qqpFA4xteaqQIKQpPRCxi5UmuNFot4AVhDfUiqn2.jpg\", \"kargo_tipi\": \"Ton (T)\", \"updated_at\": \"2025-12-18T08:28:36.000000Z\", \"aciklamalar\": null, \"cikis_tarihi\": \"2025-12-18T09:30:00.000000Z\", \"imo_numarasi\": null, \"is_important\": false, \"varis_limani\": null, \"dorse_plakasi\": null, \"kalkis_limani\": null, \"kargo_icerigi\": \"test\", \"kargo_miktari\": \"13\", \"shipment_type\": \"import\", \"varis_noktasi\": \"test\", \"kalkis_noktasi\": \"test\", \"ekstra_bilgiler\": null, \"business_unit_id\": 1, \"onaylanma_tarihi\": null, \"onaylayan_user_id\": null, \"tahmini_varis_tarihi\": \"2025-12-18T12:30:00.000000Z\"}}',NULL,'2025-12-18 08:28:36','2025-12-18 08:28:36'),(29,'default','bir shipment kaydını güncelledi.','App\\Models\\Shipment','updated',2,'App\\Models\\User',1,'{\"old\": {\"dosya_yolu\": \"sevkiyat_dosyalari/qqpFA4xteaqQIKQpPRCxi5UmuNFot4AVhDfUiqn2.jpg\", \"updated_at\": \"2025-12-18T08:28:36.000000Z\"}, \"attributes\": {\"dosya_yolu\": null, \"updated_at\": \"2025-12-18T08:34:45.000000Z\"}}',NULL,'2025-12-18 08:34:45','2025-12-18 08:34:45'),(30,'default','bir shipment kaydını güncelledi.','App\\Models\\Shipment','updated',2,'App\\Models\\User',1,'{\"old\": {\"dosya_yolu\": null, \"updated_at\": \"2025-12-18T08:34:45.000000Z\"}, \"attributes\": {\"dosya_yolu\": \"sevkiyat_dosyalari/GjgsiglbC1bKaDBiQjzgFDOftgxcGmIWWkFjNvyZ.jpg\", \"updated_at\": \"2025-12-18T08:58:13.000000Z\"}}',NULL,'2025-12-18 08:58:13','2025-12-18 08:58:13'),(31,'default','BAŞARISIZ kullanıcı girişi denemesi',NULL,NULL,NULL,NULL,NULL,'{\"ip_adresi\": \"10.10.23.110\", \"email_denemesi\": \"hilal.sahin@koksan.com\"}',NULL,'2025-12-19 14:51:43','2025-12-19 14:51:43'),(32,'default','BAŞARISIZ kullanıcı girişi denemesi',NULL,NULL,NULL,NULL,NULL,'{\"ip_adresi\": \"10.10.22.44\", \"email_denemesi\": \"erkut.agar@koksan.com\"}',NULL,'2025-12-19 15:00:54','2025-12-19 15:00:54'),(33,'default','BAŞARISIZ kullanıcı girişi denemesi',NULL,NULL,NULL,NULL,NULL,'{\"ip_adresi\": \"10.10.23.110\", \"email_denemesi\": \"hilal.sahin@koksan.com\"}',NULL,'2025-12-19 15:07:48','2025-12-19 15:07:48'),(34,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',31,'App\\Models\\User',1,'{\"attributes\": {\"id\": 31, \"name\": \"Erkut Agar\", \"role\": \"user\", \"email\": \"erkut.agar@koksan.com\", \"password\": \"$2y$10$kmCXJzLb7kxt7Lqesn4PhuiRXU1Lmf17TOOEm5ZLwFFo5sf/svV9O\", \"created_at\": \"2025-12-22T06:41:08.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T06:41:08.000000Z\", \"department_id\": 5, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-22 06:41:08','2025-12-22 06:41:08'),(35,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',32,'App\\Models\\User',1,'{\"attributes\": {\"id\": 32, \"name\": \"Hilal Şahin\", \"role\": \"user\", \"email\": \"hilal.sahin@koksan.com\", \"password\": \"$2y$10$3JY0WAaC2zeArofxDIrpFely/nYeARGe0Ij1WAOyuZ2hsY2YDO.Qi\", \"created_at\": \"2025-12-22T06:41:47.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T06:41:47.000000Z\", \"department_id\": 5, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-22 06:41:47','2025-12-22 06:41:47'),(36,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',32,'App\\Models\\User',1,'{\"old\": {\"role\": \"user\", \"updated_at\": \"2025-12-22T06:41:47.000000Z\"}, \"attributes\": {\"role\": \"bakim_personeli\", \"updated_at\": \"2025-12-22T06:42:09.000000Z\"}}',NULL,'2025-12-22 06:42:09','2025-12-22 06:42:09'),(37,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',31,'App\\Models\\User',1,'{\"old\": {\"role\": \"user\", \"updated_at\": \"2025-12-22T06:41:08.000000Z\"}, \"attributes\": {\"role\": \"bakim_personeli\", \"updated_at\": \"2025-12-22T06:42:16.000000Z\"}}',NULL,'2025-12-22 06:42:16','2025-12-22 06:42:16'),(38,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',33,'App\\Models\\User',1,'{\"attributes\": {\"id\": 33, \"name\": \"Ergün Güçlü\", \"role\": \"bakim_personeli\", \"email\": \"ergun.guclu@koksan.com\", \"password\": \"$2y$10$3IFrHvXjog8Op.tMNujm7uQU8efozlY6GCSSdRjSqZi6LtMbKwX96\", \"created_at\": \"2025-12-22T06:42:49.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T06:42:49.000000Z\", \"department_id\": 5, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-22 06:42:49','2025-12-22 06:42:49'),(39,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',34,'App\\Models\\User',1,'{\"attributes\": {\"id\": 34, \"name\": \"Hüseyin Oğuz\", \"role\": \"bakim_personeli\", \"email\": \"huseyin.oguz@koksan.com\", \"password\": \"$2y$10$JcyboHV8TebMncG6XmtFKO/29JInvMN55pXXf/ZR4AcIQoxBZPNYq\", \"created_at\": \"2025-12-22T06:43:48.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T06:43:48.000000Z\", \"department_id\": 5, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-22 06:43:48','2025-12-22 06:43:48'),(40,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',35,'App\\Models\\User',1,'{\"attributes\": {\"id\": 35, \"name\": \"Mesut Altın\", \"role\": \"bakim_personeli\", \"email\": \"mesut.altin@koksan.com\", \"password\": \"$2y$10$F4GvhtQp4DACsscHO7GK9O3UOuu7pdVvLJaOIy0IWUPAOzal/JUXu\", \"created_at\": \"2025-12-22T06:44:24.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-22T06:44:24.000000Z\", \"department_id\": 5, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-22 06:44:24','2025-12-22 06:44:24'),(41,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',36,'App\\Models\\User',1,'{\"attributes\": {\"id\": 36, \"name\": \"bakım yönetici\", \"role\": \"yonetici\", \"email\": \"bkmyntc@koksan.com\", \"password\": \"$2y$10$0C.NHnGmiRx9Ww5/ipxob..ger9uuTvw7SgsMmEqTE1NPG6cGexaW\", \"created_at\": \"2025-12-23T05:44:49.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-23T05:44:49.000000Z\", \"department_id\": 5, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-23 05:44:49','2025-12-23 05:44:49'),(42,'default','BAŞARISIZ kullanıcı girişi denemesi',NULL,NULL,NULL,NULL,NULL,'{\"ip_adresi\": \"10.10.23.2\", \"email_denemesi\": \"admin@guvenlik.com\"}',NULL,'2025-12-23 14:36:09','2025-12-23 14:36:09'),(43,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',37,'App\\Models\\User',1,'{\"attributes\": {\"id\": 37, \"name\": \"Ercan Eren\", \"role\": \"yonetici\", \"email\": \"ercan.eren@koksan.com\", \"password\": \"$2y$10$zDNmEWO2yXU/V0aKFk8hTeglraVkzhf6MXhgjjipjfoh6WUbFtZBu\", \"created_at\": \"2025-12-23T14:41:15.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-23T14:41:15.000000Z\", \"department_id\": 5, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-23 14:41:15','2025-12-23 14:41:15'),(44,'default','yeni bir user kaydı oluşturdu.','App\\Models\\User','created',38,'App\\Models\\User',1,'{\"attributes\": {\"id\": 38, \"name\": \"Mesut Yiğit\", \"role\": \"yonetici\", \"email\": \"mesut.yigit@koksan.com\", \"password\": \"$2y$10$9R8gAfeiDiSPHjP88DHwBue3Cr9VrBpmIbOo7xYcm13bHTFDzfvr.\", \"created_at\": \"2025-12-25T07:17:36.000000Z\", \"deleted_at\": null, \"updated_at\": \"2025-12-25T07:17:36.000000Z\", \"department_id\": 1, \"remember_token\": null, \"email_verified_at\": null}}',NULL,'2025-12-25 07:17:36','2025-12-25 07:17:36'),(45,'default','BAŞARISIZ kullanıcı girişi denemesi',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip_adresi\": \"10.10.22.133\", \"email_denemesi\": \"admin@koksan.com\"}',NULL,'2025-12-25 09:29:16','2025-12-25 09:29:16'),(46,'default','bir user kaydını güncelledi.','App\\Models\\User','updated',20,'App\\Models\\User',1,'{\"old\": {\"role\": \"kullanici\", \"updated_at\": \"2025-12-02T14:54:01.000000Z\"}, \"attributes\": {\"role\": \"yonetici\", \"updated_at\": \"2025-12-25T10:35:58.000000Z\"}}',NULL,'2025-12-25 10:35:58','2025-12-25 10:35:58'),(47,'default','Kullanıcı sistemden çıkış yaptı',NULL,NULL,NULL,'App\\Models\\User',1,'[]',NULL,'2025-12-26 08:46:34','2025-12-26 08:46:34'),(48,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',20,'[]',NULL,'2025-12-26 08:46:44','2025-12-26 08:46:44'),(49,'default','Kullanıcı sistemden çıkış yaptı',NULL,NULL,NULL,'App\\Models\\User',20,'[]',NULL,'2025-12-26 08:47:10','2025-12-26 08:47:10'),(50,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',20,'[]',NULL,'2025-12-26 08:48:18','2025-12-26 08:48:18'),(51,'default','Kullanıcı sistemden çıkış yaptı',NULL,NULL,NULL,'App\\Models\\User',37,'[]',NULL,'2025-12-26 08:49:09','2025-12-26 08:49:09'),(52,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',1,'[]',NULL,'2025-12-26 08:49:16','2025-12-26 08:49:16'),(53,'default','bir shipment kaydını sildi.','App\\Models\\Shipment','deleted',2,'App\\Models\\User',1,'{\"old\": {\"id\": 2, \"plaka\": \"test\", \"user_id\": 1, \"gemi_adi\": null, \"arac_tipi\": \"kamyon\", \"sofor_adi\": \"test\", \"created_at\": \"2025-12-18T08:28:36.000000Z\", \"created_by\": null, \"deleted_at\": \"2025-12-26T08:49:43.000000Z\", \"dosya_yolu\": \"sevkiyat_dosyalari/GjgsiglbC1bKaDBiQjzgFDOftgxcGmIWWkFjNvyZ.jpg\", \"kargo_tipi\": \"Ton (T)\", \"updated_at\": \"2025-12-26T08:49:43.000000Z\", \"aciklamalar\": null, \"cikis_tarihi\": \"2025-12-18T09:30:00.000000Z\", \"imo_numarasi\": null, \"is_important\": false, \"varis_limani\": null, \"dorse_plakasi\": null, \"kalkis_limani\": null, \"kargo_icerigi\": \"test\", \"kargo_miktari\": \"13\", \"shipment_type\": \"import\", \"varis_noktasi\": \"test\", \"kalkis_noktasi\": \"test\", \"ekstra_bilgiler\": null, \"business_unit_id\": 1, \"onaylanma_tarihi\": null, \"onaylayan_user_id\": null, \"tahmini_varis_tarihi\": \"2025-12-18T12:30:00.000000Z\"}}',NULL,'2025-12-26 08:49:44','2025-12-26 08:49:44'),(54,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',29,'[]',NULL,'2025-12-29 06:57:21','2025-12-29 06:57:21'),(55,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',29,'[]',NULL,'2025-12-29 08:01:18','2025-12-29 08:01:18'),(56,'default','yeni bir shipment kaydı oluşturdu.','App\\Models\\Shipment','created',3,'App\\Models\\User',29,'{\"attributes\": {\"id\": 3, \"plaka\": \"test\", \"user_id\": 29, \"gemi_adi\": null, \"arac_tipi\": \"kamyon\", \"sofor_adi\": \"test\", \"created_at\": \"2025-12-29T08:06:38.000000Z\", \"created_by\": null, \"deleted_at\": null, \"dosya_yolu\": \"sevkiyat_dosyalari/j50NfY6V64geSltVqC6Zt8B9vfV62RpX9WIDUc3y.jpg\", \"kargo_tipi\": \"Adet\", \"updated_at\": \"2025-12-29T08:06:38.000000Z\", \"aciklamalar\": \"test\", \"cikis_tarihi\": \"2025-12-29T09:02:00.000000Z\", \"imo_numarasi\": null, \"is_important\": false, \"varis_limani\": null, \"dorse_plakasi\": null, \"kalkis_limani\": null, \"kargo_icerigi\": \"kopet\", \"kargo_miktari\": \"13\", \"shipment_type\": \"export\", \"varis_noktasi\": \"adana\", \"kalkis_noktasi\": \"köksan\", \"ekstra_bilgiler\": null, \"business_unit_id\": 1, \"onaylanma_tarihi\": null, \"onaylayan_user_id\": null, \"tahmini_varis_tarihi\": \"2025-12-29T14:02:00.000000Z\"}}',NULL,'2025-12-29 08:06:38','2025-12-29 08:06:38'),(57,'default','Kullanıcı sistemden çıkış yaptı',NULL,NULL,NULL,'App\\Models\\User',29,'[]',NULL,'2025-12-29 08:39:34','2025-12-29 08:39:34'),(58,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',1,'[]',NULL,'2025-12-29 08:39:41','2025-12-29 08:39:41'),(59,'default','Kullanıcı sistemden çıkış yaptı',NULL,NULL,NULL,'App\\Models\\User',1,'[]',NULL,'2025-12-29 08:40:45','2025-12-29 08:40:45'),(60,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',29,'[]',NULL,'2025-12-29 08:40:51','2025-12-29 08:40:51'),(61,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',1,'[]',NULL,'2025-12-29 12:11:35','2025-12-29 12:11:35'),(62,'default','yeni bir maintenance plan kaydı oluşturdu.','App\\Models\\MaintenancePlan','created',22,'App\\Models\\User',1,'{\"attributes\": {\"id\": 22, \"title\": \"yağ bakımı\", \"status\": \"pending\", \"user_id\": 1, \"priority\": \"critical\", \"created_at\": \"2025-12-29T12:17:39.000000Z\", \"created_by\": null, \"deleted_at\": null, \"updated_at\": \"2025-12-29T12:17:39.000000Z\", \"description\": \"yaklaşık 200 litre şanzıman yağı değişiim\", \"actual_end_date\": null, \"completion_note\": null, \"business_unit_id\": 3, \"planned_end_date\": \"2025-12-29T15:20:00.000000Z\", \"actual_start_date\": null, \"planned_start_date\": \"2025-12-29T13:16:00.000000Z\", \"maintenance_type_id\": 2, \"maintenance_asset_id\": 2}}',NULL,'2025-12-29 12:17:39','2025-12-29 12:17:39'),(63,'default','Kullanıcı sistemden çıkış yaptı',NULL,NULL,NULL,'App\\Models\\User',1,'[]',NULL,'2025-12-29 12:29:37','2025-12-29 12:29:37'),(64,'default','BAŞARISIZ kullanıcı girişi denemesi',NULL,NULL,NULL,'App\\Models\\User',25,'{\"ip_adresi\": \"10.10.22.220\", \"email_denemesi\": \"ruveyda.oztepir@koksan.com\"}',NULL,'2025-12-29 13:00:29','2025-12-29 13:00:29'),(65,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',25,'[]',NULL,'2025-12-29 13:01:13','2025-12-29 13:01:13'),(66,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',24,'[]',NULL,'2025-12-29 13:03:19','2025-12-29 13:03:19'),(67,'default','Başarılı kullanıcı girişi',NULL,NULL,NULL,'App\\Models\\User',24,'[]',NULL,'2025-12-30 06:22:33','2025-12-30 06:22:33');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `birims`
--

DROP TABLE IF EXISTS `birims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `birims` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `birims_ad_unique` (`ad`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `birims`
--

LOCK TABLES `birims` WRITE;
/*!40000 ALTER TABLE `birims` DISABLE KEYS */;
INSERT INTO `birims` VALUES (2,'Kilogram (Kg)','2025-10-31 03:42:26','2025-10-31 03:42:26',NULL),(3,'Gram (Gr)','2025-10-31 03:42:30','2025-10-31 03:42:30',NULL),(4,'Ton (T)','2025-10-31 03:42:33','2025-10-31 03:42:33',NULL),(5,'Metreküp (m³)','2025-10-31 03:42:37','2025-10-31 03:42:37',NULL),(6,'Litre (L)','2025-10-31 03:42:40','2025-10-31 03:42:40',NULL),(7,'Metre (M)','2025-10-31 03:43:31','2025-10-31 03:43:31',NULL),(8,'Santimetre (Cm)','2025-10-31 03:43:44','2025-10-31 03:43:44',NULL),(9,'Adet','2025-10-31 03:43:58','2025-10-31 03:43:58',NULL),(10,'Koli','2025-10-31 03:44:06','2025-10-31 03:44:06',NULL),(11,'Palet','2025-10-31 03:44:15','2025-10-31 03:44:15',NULL),(12,'Paket','2025-10-31 03:44:19','2025-10-31 03:44:19',NULL);
/*!40000 ALTER TABLE `birims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `bookable_id` bigint unsigned DEFAULT NULL,
  `bookable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmation_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_business_unit_id_foreign` (`business_unit_id`),
  KEY `bookings_created_by_foreign` (`created_by`),
  CONSTRAINT `bookings_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_unit_user`
--

DROP TABLE IF EXISTS `business_unit_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_unit_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `business_unit_id` bigint unsigned NOT NULL,
  `role_in_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_unit_user_user_id_business_unit_id_unique` (`user_id`,`business_unit_id`),
  KEY `business_unit_user_business_unit_id_foreign` (`business_unit_id`),
  CONSTRAINT `business_unit_user_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `business_unit_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_unit_user`
--

LOCK TABLES `business_unit_user` WRITE;
/*!40000 ALTER TABLE `business_unit_user` DISABLE KEYS */;
INSERT INTO `business_unit_user` VALUES (1,29,1,NULL,'2025-12-15 07:48:54','2025-12-15 07:48:54'),(2,21,2,NULL,'2025-12-15 07:50:31','2025-12-15 07:50:31'),(5,27,1,NULL,'2025-12-17 11:02:35','2025-12-17 11:02:35'),(6,31,2,NULL,'2025-12-22 06:41:08','2025-12-22 06:41:08'),(7,32,2,NULL,'2025-12-22 06:41:47','2025-12-22 06:41:47'),(8,33,2,NULL,'2025-12-22 06:42:49','2025-12-22 06:42:49'),(9,34,2,NULL,'2025-12-22 06:43:48','2025-12-22 06:43:48'),(10,35,2,NULL,'2025-12-22 06:44:24','2025-12-22 06:44:24'),(12,36,2,NULL,'2025-12-23 05:44:49','2025-12-23 05:44:49'),(13,37,2,NULL,'2025-12-23 14:41:15','2025-12-23 14:41:15'),(14,38,1,NULL,'2025-12-25 07:17:36','2025-12-25 07:17:36'),(15,38,2,NULL,'2025-12-25 07:17:36','2025-12-25 07:17:36'),(16,38,3,NULL,'2025-12-25 07:17:36','2025-12-25 07:17:36');
/*!40000 ALTER TABLE `business_unit_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_units`
--

DROP TABLE IF EXISTS `business_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_units_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_units`
--

LOCK TABLES `business_units` WRITE;
/*!40000 ALTER TABLE `business_units` DISABLE KEYS */;
INSERT INTO `business_units` VALUES (1,'Kopet Fabrikası','KPT','kopet-fabrikasi',1,NULL,'2025-12-15 07:47:57','2025-12-15 07:47:57'),(2,'PREFORM','PRFRM','preform',1,NULL,'2025-12-15 07:48:12','2025-12-15 07:48:12'),(3,'LEVHA','LVH','levha',1,NULL,'2025-12-25 07:16:40','2025-12-25 07:16:40');
/*!40000 ALTER TABLE `business_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `customer_machine_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaints_customer_id_foreign` (`customer_id`),
  KEY `complaints_user_id_foreign` (`user_id`),
  KEY `complaints_customer_machine_id_foreign` (`customer_machine_id`),
  KEY `complaints_business_unit_id_foreign` (`business_unit_id`),
  KEY `complaints_created_by_foreign` (`created_by`),
  CONSTRAINT `complaints_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `complaints_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `complaints_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_customer_machine_id_foreign` FOREIGN KEY (`customer_machine_id`) REFERENCES `customer_machines` (`id`),
  CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaints`
--

LOCK TABLES `complaints` WRITE;
/*!40000 ALTER TABLE `complaints` DISABLE KEYS */;
/*!40000 ALTER TABLE `complaints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_activities`
--

DROP TABLE IF EXISTS `customer_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_activities_customer_id_foreign` (`customer_id`),
  KEY `customer_activities_user_id_foreign` (`user_id`),
  KEY `customer_activities_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `customer_activities_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_activities`
--

LOCK TABLES `customer_activities` WRITE;
/*!40000 ALTER TABLE `customer_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_activity_logs`
--

DROP TABLE IF EXISTS `customer_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_activity_logs_user_id_foreign` (`user_id`),
  KEY `customer_activity_logs_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `customer_activity_logs_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `customer_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_activity_logs`
--

LOCK TABLES `customer_activity_logs` WRITE;
/*!40000 ALTER TABLE `customer_activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_machines`
--

DROP TABLE IF EXISTS `customer_machines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_machines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `installation_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_machines_serial_number_unique` (`serial_number`),
  KEY `customer_machines_customer_id_foreign` (`customer_id`),
  KEY `customer_machines_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `customer_machines_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_machines`
--

LOCK TABLES `customer_machines` WRITE;
/*!40000 ALTER TABLE `customer_machines` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_machines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_visits`
--

DROP TABLE IF EXISTS `customer_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `event_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `travel_id` bigint unsigned DEFAULT NULL,
  `customer_machine_id` bigint unsigned DEFAULT NULL,
  `visit_purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `after_sales_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_visits_event_id_foreign` (`event_id`),
  KEY `customer_visits_customer_id_foreign` (`customer_id`),
  KEY `customer_visits_travel_id_foreign` (`travel_id`),
  KEY `customer_visits_customer_machine_id_foreign` (`customer_machine_id`),
  KEY `customer_visits_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `customer_visits_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_visits_customer_machine_id_foreign` FOREIGN KEY (`customer_machine_id`) REFERENCES `customer_machines` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customer_visits_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_visits_travel_id_foreign` FOREIGN KEY (`travel_id`) REFERENCES `travels` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_visits`
--

LOCK TABLES `customer_visits` WRITE;
/*!40000 ALTER TABLE `customer_visits` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department_user`
--

DROP TABLE IF EXISTS `department_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `department_user_user_id_department_id_unique` (`user_id`,`department_id`),
  KEY `department_user_department_id_foreign` (`department_id`),
  CONSTRAINT `department_user_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `department_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department_user`
--

LOCK TABLES `department_user` WRITE;
/*!40000 ALTER TABLE `department_user` DISABLE KEYS */;
INSERT INTO `department_user` VALUES (5,9,1),(6,9,2),(7,9,3),(8,9,5),(9,9,6),(4,13,3),(1,19,6),(2,21,5),(10,23,3),(11,24,3),(12,25,3),(13,26,1),(14,26,2),(15,26,5),(17,27,1),(18,28,5),(19,29,1),(20,31,5),(21,32,5),(22,33,5),(23,34,5),(24,35,5),(25,36,5),(26,37,5),(27,38,1),(28,38,2),(29,38,3),(30,38,5),(31,38,6);
/*!40000 ALTER TABLE `department_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Lojistik','lojistik','2025-10-31 03:47:41','2025-10-31 03:47:41',NULL),(2,'Üretim','uretim','2025-10-31 03:47:41','2025-10-31 03:47:41',NULL),(3,'İdari İşler','hizmet','2025-10-31 03:47:41','2025-10-31 03:47:41',NULL),(5,'Bakım','bakim','2025-12-02 14:22:00','2025-12-02 14:22:00',NULL),(6,'Ulaştırma','ulastirma','2025-12-02 14:22:00','2025-12-02 14:22:00',NULL);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_types`
--

DROP TABLE IF EXISTS `event_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3699FF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_types`
--

LOCK TABLES `event_types` WRITE;
/*!40000 ALTER TABLE `event_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visit_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planlandi',
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT '0',
  `customer_id` bigint unsigned DEFAULT NULL,
  `customer_machine_id` bigint unsigned DEFAULT NULL,
  `visit_purpose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `after_sales_notes` text COLLATE utf8mb4_unicode_ci,
  `event_type_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_user_id_foreign` (`user_id`),
  KEY `events_customer_id_foreign` (`customer_id`),
  KEY `events_customer_machine_id_foreign` (`customer_machine_id`),
  KEY `events_event_type_id_foreign` (`event_type_id`),
  KEY `events_created_by_foreign` (`created_by`),
  KEY `events_business_unit_id_foreign` (`business_unit_id`),
  CONSTRAINT `events_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `events_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `events_customer_machine_id_foreign` FOREIGN KEY (`customer_machine_id`) REFERENCES `customer_machines` (`id`) ON DELETE SET NULL,
  CONSTRAINT `events_event_type_id_foreign` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`),
  CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,NULL,3,'101',NULL,'2025-10-31 11:50:00','2025-10-31 17:50:00','Köksan','egitim','planlandi',NULL,'2025-10-31 05:50:34','2025-10-31 08:56:13','2025-10-31 08:56:13',0,NULL,NULL,NULL,NULL,NULL,NULL),(2,NULL,3,'Yapay Zeka Eğitimi 101',NULL,'2025-10-31 16:00:00','2025-10-31 18:25:00','Köksan','egitim','planlandi',NULL,'2025-10-31 08:56:46','2025-11-05 04:19:50','2025-11-05 04:19:50',0,NULL,NULL,NULL,NULL,NULL,NULL),(3,NULL,3,'Yapay Zeka Eğitimi 101',NULL,'2025-11-05 13:30:00','2025-11-05 15:30:00','Köksan','egitim','planlandi',NULL,'2025-11-05 05:33:52','2025-11-06 08:52:16','2025-11-06 08:52:16',0,NULL,NULL,NULL,NULL,NULL,NULL),(4,NULL,3,'Müşteri Ziyareti',NULL,'2025-11-05 09:30:00','2025-11-05 12:30:00','Köksan/ Preform','musteri_ziyareti','planlandi',NULL,'2025-11-05 05:34:38','2025-11-06 08:52:18','2025-11-06 08:52:18',0,NULL,NULL,NULL,NULL,NULL,NULL),(5,NULL,3,'İş Sağlığı ve Güvenliği Eğitimi',NULL,'2025-11-05 15:00:00','2025-11-05 16:00:00','Köksan','egitim','planlandi',NULL,'2025-11-05 05:36:45','2025-11-06 08:52:14','2025-11-06 08:52:14',0,NULL,NULL,NULL,NULL,NULL,NULL),(6,NULL,3,'Müşteri Ziyareti',NULL,'2025-11-03 10:00:00','2025-11-03 13:00:00','Köksan','musteri_ziyareti','planlandi',NULL,'2025-11-05 05:37:32','2025-11-06 08:52:10','2025-11-06 08:52:10',0,NULL,NULL,NULL,NULL,NULL,NULL),(7,NULL,9,'koordinasyon',NULL,'2025-11-07 16:48:00','2025-11-07 17:48:00','denetim birimi','toplanti','planlandi',NULL,'2025-11-06 10:48:18','2025-11-06 10:48:18',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(8,NULL,9,'takvim','takvim çalışması','2025-11-07 16:50:00','2025-11-07 17:50:00','denetim birimi','egitim','planlandi',NULL,'2025-11-06 10:50:47','2025-11-06 10:50:47',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(9,NULL,9,'Genel Kontrol','Araç Talep edilecektir.','2025-11-09 00:00:00','2025-11-09 02:00:00','denetim birimi','diger','planlandi',NULL,'2025-11-07 04:59:43','2025-11-07 04:59:43',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(10,NULL,9,'tamirci ziyareti','tır tamir atölye sahibi ziyareti','2025-11-11 09:13:00','2025-11-11 18:13:00','atölye','musteri_ziyareti','planlandi',NULL,'2025-11-07 12:14:06','2025-11-07 12:14:06',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(11,NULL,9,'Köksan IT / Yazılım Proje Birimi Toplantısı',NULL,'2025-11-11 09:20:00','2025-11-11 11:00:00','IT','toplanti','planlandi',NULL,'2025-11-10 10:21:17','2025-11-10 10:21:17',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(12,NULL,1,'saat sorunu',NULL,'2025-11-17 17:56:00','2025-11-18 17:56:00','Köksan','diger','planlandi',NULL,'2025-11-10 11:56:23','2025-11-10 11:56:31','2025-11-10 11:56:31',0,NULL,NULL,NULL,NULL,NULL,NULL),(13,NULL,18,'Resin Bölümleri için Acil Durum Tahliye','Bugün saat 23:00 ve gece saat 01:00 saatlerinde Resin Bölümleri için Acil Durum Tahliye ve Yangın Tatbikatı yapılacaktır.','2025-11-25 23:00:00','2025-11-26 01:00:00','Petresin Önü','egitim','planlandi',NULL,'2025-11-25 10:11:06','2025-11-27 02:19:55',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(14,NULL,18,'PETRESİN HABERSİZ TATBİKAT','HABERSİZ TABİKAT YAPILACAK','2025-11-27 14:16:00','2025-11-27 23:50:00','Petresin Önü','diger','planlandi',NULL,'2025-11-27 08:17:42','2025-11-27 08:24:17',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(15,NULL,9,'İSG toplantısı','Gn.Md.Yrd.mızın da uygun görmesiyle perşembe günü saat 15:00’da Boğaziçi Toplantı Salonunda İş Sağlığı ve Güvenliği genel başlığı altında toplantı icra edilecektir. Toplantıya Köksan Holding  Danışmanı Mehmet GÜNEŞ Bey de katılacaktır.\r\nToplantıda genel hatlarıyla;\r\n1.	Oluşabilecek kazanın iş kazası kapsamına girip girmeyeceği haller,\r\n2.	İş kazalarını önleyici tedbirler,\r\n3.	Oluşan iş kazası sonrasında Köksan Holding adına alınacak önleyici tedbirler,\r\n4.	İş kazalarının hukuksal boyutu,\r\n5.	Köksan Holding bünyesinde yapılacak olan sözleşmelerin iş güvenliği ve bilirkişi boyutunda incelenmesi,\r\n6.	Kaza sonrasında hukuksal boyutun bilirkişi penceresinden incelenmesi,\r\n7.	Araç kazaları sonrasında bilirkişi tarafından istenilen evraklar ve dosya örneği,\r\n8.	T.C. Vatandaşı olmayanların iş kazası boyutunda hukuksal durumu ve alınacak tedbirler,\r\n9.	Katılımcı personelin katkıları, beyin fırtınası çalışması ve iş kazaları konusunda beyaz yaka farkındalığının oluşturulması,\r\n10.	Varsa katılımcıların ilave eklemek istedikleri konular ve çözüm önerileri.','2025-12-04 15:00:00','2025-12-04 15:51:00','Boğaziçi toplantı salonu','toplanti','planlandi',NULL,'2025-12-03 09:52:49','2025-12-03 09:52:49',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(16,NULL,1,'test deneme',NULL,'2025-12-08 14:10:00','2025-12-08 15:10:00','Köksan','fuar','planlandi',NULL,'2025-12-08 06:03:55','2025-12-08 06:28:25','2025-12-08 06:28:25',0,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
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
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `files_fileable_type_fileable_id_index` (`fileable_type`,`fileable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (1,'App\\Models\\Shipment',2,'sevkiyat_dosyalari/GjgsiglbC1bKaDBiQjzgFDOftgxcGmIWWkFjNvyZ.jpg','Ataturk-diyor-ki.jpg','image/jpeg',1,'2025-12-18 08:58:13','2025-12-18 08:58:13'),(2,'App\\Models\\Shipment',3,'sevkiyat_dosyalari/j50NfY6V64geSltVqC6Zt8B9vfV62RpX9WIDUc3y.jpg','Ataturk-diyor-ki.jpg','image/jpeg',29,'2025-12-29 08:06:38','2025-12-29 08:06:38');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
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
-- Table structure for table `logistics_vehicles`
--

DROP TABLE IF EXISTS `logistics_vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logistics_vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` decimal(8,2) DEFAULT NULL,
  `current_km` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fuel_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logistics_vehicles_created_by_foreign` (`created_by`),
  KEY `logistics_vehicles_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `logistics_vehicles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logistics_vehicles`
--

LOCK TABLES `logistics_vehicles` WRITE;
/*!40000 ALTER TABLE `logistics_vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `logistics_vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_activity_logs`
--

DROP TABLE IF EXISTS `maintenance_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `maintenance_plan_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_activity_logs_maintenance_plan_id_foreign` (`maintenance_plan_id`),
  KEY `maintenance_activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `maintenance_activity_logs_maintenance_plan_id_foreign` FOREIGN KEY (`maintenance_plan_id`) REFERENCES `maintenance_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `maintenance_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_activity_logs`
--

LOCK TABLES `maintenance_activity_logs` WRITE;
/*!40000 ALTER TABLE `maintenance_activity_logs` DISABLE KEYS */;
INSERT INTO `maintenance_activity_logs` VALUES (9,2,3,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:38:15','2025-12-19 14:38:15'),(10,2,4,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:39:23','2025-12-19 14:39:23'),(11,2,5,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:40:21','2025-12-19 14:40:21'),(12,2,6,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:41:09','2025-12-19 14:41:09'),(13,2,7,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:41:55','2025-12-19 14:41:55'),(14,2,8,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:42:57','2025-12-19 14:42:57'),(15,2,9,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:43:39','2025-12-19 14:43:39'),(16,2,10,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:44:19','2025-12-19 14:44:19'),(17,2,11,21,'created','Bakım planı oluşturuldu.','2025-12-19 14:45:00','2025-12-19 14:45:00'),(18,2,12,32,'created','Bakım planı oluşturuldu.','2025-12-22 07:27:49','2025-12-22 07:27:49'),(19,2,13,32,'created','Bakım planı oluşturuldu.','2025-12-22 07:34:48','2025-12-22 07:34:48'),(20,2,14,32,'created','Bakım planı oluşturuldu.','2025-12-22 07:35:50','2025-12-22 07:35:50'),(21,2,14,32,'status_change','Durum: Bekliyor -> İşlemde olarak güncellendi.','2025-12-22 07:38:22','2025-12-22 07:38:22'),(22,2,15,32,'created','Bakım planı oluşturuldu.','2025-12-22 08:10:52','2025-12-22 08:10:52'),(23,2,16,32,'created','Bakım planı oluşturuldu.','2025-12-22 08:12:19','2025-12-22 08:12:19'),(24,2,17,32,'created','Bakım planı oluşturuldu.','2025-12-22 08:13:34','2025-12-22 08:13:34'),(25,2,17,32,'deleted','Bakım planı silindi (Çöp kutusuna taşındı).','2025-12-22 08:16:20','2025-12-22 08:16:20'),(26,2,18,32,'created','Bakım planı oluşturuldu.','2025-12-22 08:17:37','2025-12-22 08:17:37'),(27,2,19,32,'created','Bakım planı oluşturuldu.','2025-12-22 08:21:04','2025-12-22 08:21:04'),(28,2,20,32,'created','Bakım planı oluşturuldu.','2025-12-22 08:23:00','2025-12-22 08:23:00'),(29,2,21,32,'created','Bakım planı oluşturuldu.','2025-12-22 08:24:06','2025-12-22 08:24:06'),(30,2,12,32,'status_change','Durum: Bekliyor -> İşlemde olarak güncellendi.','2025-12-22 15:03:59','2025-12-22 15:03:59'),(31,2,4,1,'file_upload','1 adet yeni dosya yüklendi.','2025-12-26 13:10:54','2025-12-26 13:10:54'),(32,3,22,1,'created','Bakım planı oluşturuldu.','2025-12-29 12:17:39','2025-12-29 12:17:39');
/*!40000 ALTER TABLE `maintenance_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_assets`
--

DROP TABLE IF EXISTS `maintenance_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturing_year` year DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'machine',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_assets_business_unit_id_foreign` (`business_unit_id`),
  KEY `maintenance_assets_created_by_foreign` (`created_by`),
  CONSTRAINT `maintenance_assets_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `maintenance_assets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_assets`
--

LOCK TABLES `maintenance_assets` WRITE;
/*!40000 ALTER TABLE `maintenance_assets` DISABLE KEYS */;
INSERT INTO `maintenance_assets` VALUES (1,2,'test','test','test','1234567',2002,'machine','123456','test',NULL,1,'2025-12-18 09:20:27','2025-12-18 09:20:27',NULL,NULL),(2,3,'bandera','lv02',NULL,'123456',2021,'machine','12345','levha',NULL,1,'2025-12-29 12:16:35','2025-12-29 12:16:35',NULL,NULL);
/*!40000 ALTER TABLE `maintenance_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_files`
--

DROP TABLE IF EXISTS `maintenance_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `maintenance_plan_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_files_maintenance_plan_id_foreign` (`maintenance_plan_id`),
  KEY `maintenance_files_created_by_foreign` (`created_by`),
  CONSTRAINT `maintenance_files_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `maintenance_files_maintenance_plan_id_foreign` FOREIGN KEY (`maintenance_plan_id`) REFERENCES `maintenance_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_files`
--

LOCK TABLES `maintenance_files` WRITE;
/*!40000 ALTER TABLE `maintenance_files` DISABLE KEYS */;
INSERT INTO `maintenance_files` VALUES (2,4,'/storage/uploads/maintenance/1766754654_694e895e8a3ee_ataturk-diyor-ki.jpg','Ataturk-diyor-ki.jpg','jpg','2025-12-26 13:10:54','2025-12-26 13:11:02','2025-12-26 13:11:02',NULL);
/*!40000 ALTER TABLE `maintenance_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_plans`
--

DROP TABLE IF EXISTS `maintenance_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `maintenance_type_id` bigint unsigned NOT NULL,
  `maintenance_asset_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `status` enum('pending','in_progress','completed','cancelled','pending_approval') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `planned_start_date` datetime NOT NULL,
  `planned_end_date` datetime NOT NULL,
  `actual_start_date` datetime DEFAULT NULL,
  `actual_end_date` datetime DEFAULT NULL,
  `completion_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_plans_user_id_foreign` (`user_id`),
  KEY `maintenance_plans_maintenance_type_id_foreign` (`maintenance_type_id`),
  KEY `maintenance_plans_maintenance_asset_id_foreign` (`maintenance_asset_id`),
  KEY `maintenance_plans_created_by_foreign` (`created_by`),
  KEY `maintenance_plans_business_unit_id_foreign` (`business_unit_id`),
  CONSTRAINT `maintenance_plans_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `maintenance_plans_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `maintenance_plans_maintenance_asset_id_foreign` FOREIGN KEY (`maintenance_asset_id`) REFERENCES `maintenance_assets` (`id`),
  CONSTRAINT `maintenance_plans_maintenance_type_id_foreign` FOREIGN KEY (`maintenance_type_id`) REFERENCES `maintenance_types` (`id`),
  CONSTRAINT `maintenance_plans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_plans`
--

LOCK TABLES `maintenance_plans` WRITE;
/*!40000 ALTER TABLE `maintenance_plans` DISABLE KEYS */;
INSERT INTO `maintenance_plans` VALUES (3,2,21,2,1,'NETSTAL-20 PLANALI GENEL BAKIM VE TEMİZLİK','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2025-12-28 08:00:00','2026-01-04 18:30:00',NULL,NULL,NULL,'2025-12-19 14:38:15','2025-12-19 14:38:15',NULL,NULL),(4,2,21,2,1,'NETSTAL-31 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2026-01-05 08:00:00','2026-01-11 18:30:00',NULL,NULL,NULL,'2025-12-19 14:39:23','2025-12-19 14:39:23',NULL,NULL),(5,2,21,2,1,'NETSTAL-21 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2025-12-19 08:00:00','2026-12-28 18:30:00',NULL,NULL,NULL,'2025-12-19 14:40:21','2025-12-19 14:40:21',NULL,NULL),(6,2,21,2,1,'NETSTAL-14 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2025-12-28 08:00:00','2026-01-04 18:30:00',NULL,NULL,NULL,'2025-12-19 14:41:08','2025-12-19 14:41:08',NULL,NULL),(7,2,21,2,1,'NETSTAL-5 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2025-12-22 08:00:00','2025-12-28 18:30:00',NULL,NULL,NULL,'2025-12-19 14:41:55','2025-12-19 14:41:55',NULL,NULL),(8,2,21,2,1,'NETSTAL-38 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2026-01-05 08:00:00','2026-01-11 18:30:00',NULL,NULL,NULL,'2025-12-19 14:42:57','2025-12-19 14:42:57',NULL,NULL),(9,2,21,2,1,'NETSTAL-39 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2026-01-05 08:00:00','2026-01-11 18:30:00',NULL,NULL,NULL,'2025-12-19 14:43:39','2025-12-19 14:43:39',NULL,NULL),(10,2,21,2,1,'NETSTAL-40 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2025-12-28 08:00:00','2026-01-04 18:30:00',NULL,NULL,NULL,'2025-12-19 14:44:19','2025-12-19 14:44:19',NULL,NULL),(11,2,21,2,1,'NETSTAL-16 PLANLI PERİYODİK BAKIM','YAĞLAMA BAKIMI,EXTRUDER BÖLGE BAKIMI,ROBOT BAKIMI,BANT KONTROLLERİ,MENGENE BÖLGE BAKIMI,FİLTRE VE EŞANJÖR KONTROLLERİ,TERAZI KONTROLLERİ,GENEL MEKANİK PARÇA KONTROLLERİ,KIZAK YAĞLAMA BAKIM KONTROLÜ','normal','pending','2025-12-22 08:00:00','2025-12-28 18:30:00',NULL,NULL,NULL,'2025-12-19 14:45:00','2025-12-19 14:45:00',NULL,NULL),(12,2,32,1,1,'MK-21 PLANLI BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi-Vakum motorları bakım / onarım ve yön kontrolleri-Vida motoru bakım / onarım ve sıkılık kontrolleri-Vida motoru sürücü bağlantı kontrollü-Mikser motoru bakım / onarım ve akım kontrolü Vibratör motorları klemens bağlantı ve kapak kontrolü-Konveyör bant motorlarının bakım / onarım ve kontrolü-A1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği-A1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü-Ana besleme şalterlerinin sıkılık kontrolü-A5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü-Elektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği -Barel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü -Klamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü -Robot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü -Extruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü-Extruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü-Makine kurutucu platform üzeri dağınık kablolaların düzeltilmesi-Makina güvenlik siviçlerinin kontrolü-Acil stop (emergency) butonlarının kontrolü-Makina içi ve makine dışı aydınlatma kontrolü-Pano önü antistatik paspasların kontrolü-Makine barel kapakları eksik vidaların kontrolü-Proses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','in_progress','2025-12-19 08:00:00','2025-12-28 16:00:00','2025-12-22 18:03:59',NULL,NULL,'2025-12-22 07:27:49','2025-12-22 15:03:59',NULL,NULL),(13,2,32,1,1,'NETSTAL 16 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2025-12-22 08:00:00','2025-12-28 16:00:00',NULL,NULL,NULL,'2025-12-22 07:34:48','2025-12-22 07:34:48',NULL,NULL),(14,2,32,1,1,'NETSTAL 5 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','in_progress','2025-12-22 08:00:00','2025-12-28 16:00:00','2025-12-22 10:38:22',NULL,NULL,'2025-12-22 07:35:50','2025-12-22 07:38:22',NULL,NULL),(15,2,32,1,1,'NETSTAL 14 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2025-12-28 08:00:00','2026-01-04 16:00:00',NULL,NULL,NULL,'2025-12-22 08:10:52','2025-12-22 08:10:52',NULL,NULL),(16,2,32,1,1,'NETSTAL 20 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2025-12-28 08:00:00','2026-01-04 16:00:00',NULL,NULL,NULL,'2025-12-22 08:12:19','2025-12-22 08:12:19',NULL,NULL),(17,2,32,1,1,'NETSTAL 20 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2025-12-28 08:00:00','2026-01-04 16:00:00',NULL,NULL,NULL,'2025-12-22 08:13:34','2025-12-22 08:16:20','2025-12-22 08:16:20',NULL),(18,2,32,1,1,'NETSTAL 40 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2025-12-28 08:00:00','2026-01-04 16:00:00',NULL,NULL,NULL,'2025-12-22 08:17:37','2025-12-22 08:17:37',NULL,NULL),(19,2,32,1,1,'NETSTAL 31 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2026-01-05 08:00:00','2026-01-11 16:00:00',NULL,NULL,NULL,'2025-12-22 08:21:04','2025-12-22 08:21:04',NULL,NULL),(20,2,32,1,1,'NETSTAL 38 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2026-01-05 08:00:00','2026-01-11 16:00:00',NULL,NULL,NULL,'2025-12-22 08:23:00','2025-12-22 08:23:00',NULL,NULL),(21,2,32,1,1,'NETSTAL 30 PLANLI PERİYODİK BAKIM','Tahrik(Hidrolik)  motoru bakım / onarım işlemi					\r\nVakum motorları bakım / onarım ve yön kontrolleri					\r\nVida motoru bakım / onarım ve sıkılık kontrolleri					\r\nVida motoru sürücü bağlantı kontrollü					\r\nMikser motoru bakım / onarım ve akım kontrolü 					\r\nVibratör motorları klemens bağlantı ve kapak kontrolü					\r\nKonveyör bant motorlarının bakım / onarım ve kontrolü					\r\nA1-A2-A3-A4 Panolarının görsel kontrolü ve temizliği					\r\nA1-A2-A3-A4 Panolarının elektriksel bağlantıların sıkılık kontrolü					\r\nAna besleme şalterlerinin sıkılık kontrolü					\r\nA5003-A5005-A5006 -A5007-A5008 Servo sürücülerin soğutma fan kontrolü					\r\nElektrik panolarının ortam soğutma klimaları kontrolü gerekirse temizliği 					\r\nBarel bölümü valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nKlamp ünitesi valf,sensör ve elektrik bağlantılarının sıkılık kontrolü 					\r\nRobot ünitesi valf,sensör ve servo motor bağlantılarının sıkılık kontrolü 					\r\nExtruder ve Barel bölgesindeki rezistansların omaj ve akım kontrolü					\r\nExtruder ve Barel bölgesindeki rezistans ana dağıtım soketi sıkılık kontrolü 					\r\nMakine kurutucu platform üzeri dağınık kablolaların düzeltilmesi					\r\nMakina güvenlik siviçlerinin kontrolü					\r\nAcil stop (emergency) butonlarının kontrolü					\r\nMakina içi ve makine dışı aydınlatma kontrolü					\r\nPano önü antistatik paspasların kontrolü					\r\nMakine barel kapakları eksik vidaların kontrolü					\r\nProses,hammadde emiş,rejenerasyon blower motorlarının bakım & kontrolü					\r\nProses rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon rezistansları omaj ve akım kontrolü 					\r\nRejenerasyon ısıtıcı klemens baraların kontrolü					\r\nSSR kontrolü ve soğutma fanlarının kontrolü 					\r\nTüm sensör ve ölçüm problarının kontrolü 					\r\nPano temizliği ve sıkılık kontrolü 					\r\nPano içi  klima temizliği ve bakımı','normal','pending','2026-01-05 08:00:00','2026-01-11 16:00:00',NULL,NULL,NULL,'2025-12-22 08:24:06','2025-12-22 08:24:06',NULL,NULL),(22,3,1,2,2,'yağ bakımı','yaklaşık 200 litre şanzıman yağı değişiim','critical','pending','2025-12-29 16:16:00','2025-12-29 18:20:00',NULL,NULL,NULL,'2025-12-29 12:17:39','2025-12-29 12:17:39',NULL,NULL);
/*!40000 ALTER TABLE `maintenance_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_time_entries`
--

DROP TABLE IF EXISTS `maintenance_time_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_time_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `maintenance_plan_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration_minutes` int NOT NULL DEFAULT '0',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_time_entries_maintenance_plan_id_foreign` (`maintenance_plan_id`),
  KEY `maintenance_time_entries_user_id_foreign` (`user_id`),
  CONSTRAINT `maintenance_time_entries_maintenance_plan_id_foreign` FOREIGN KEY (`maintenance_plan_id`) REFERENCES `maintenance_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `maintenance_time_entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_time_entries`
--

LOCK TABLES `maintenance_time_entries` WRITE;
/*!40000 ALTER TABLE `maintenance_time_entries` DISABLE KEYS */;
INSERT INTO `maintenance_time_entries` VALUES (3,14,32,'2025-12-22 10:38:22',NULL,0,NULL,'2025-12-22 07:38:22','2025-12-22 07:38:22',NULL),(4,12,32,'2025-12-22 18:03:59',NULL,0,NULL,'2025-12-22 15:03:59','2025-12-22 15:03:59',NULL);
/*!40000 ALTER TABLE `maintenance_time_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_types`
--

DROP TABLE IF EXISTS `maintenance_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#333333',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_types`
--

LOCK TABLES `maintenance_types` WRITE;
/*!40000 ALTER TABLE `maintenance_types` DISABLE KEYS */;
INSERT INTO `maintenance_types` VALUES (1,'Elektronik Bakım','#3498db','2025-12-05 09:06:03','2025-12-05 09:06:03',NULL),(2,'Mekanik Bakım','#e74c3c','2025-12-05 09:06:03','2025-12-05 09:06:03',NULL),(3,'Yardımcı İşletmeler','#2ecc71','2025-12-05 09:06:03','2025-12-05 09:06:03',NULL);
/*!40000 ALTER TABLE `maintenance_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_17_143507_create_shipments_table',1),(6,'2025_10_18_140215_add_ship_fields_to_shipments_table',1),(7,'2025_10_20_121959_create_birims_table',1),(8,'2025_10_21_105250_add_dosya_yolu_to_shipments_table',1),(9,'2025_10_22_074520_add_kalkis_varis_noktasi_to_shipments_table',1),(10,'2025_10_22_081210_add_soft_deletes_to_shipments_table',1),(11,'2025_10_22_082749_add_onaylanma_tarihi_to_shipments_table',1),(12,'2025_10_24_083111_add_shipment_type_to_shipments_table',1),(13,'2025_10_28_063737_create_departments_table',1),(14,'2025_10_28_063844_add_department_id_to_users_table',1),(15,'2025_10_28_080152_create_production_plans_table',1),(16,'2025_10_28_084615_create_events_table',1),(17,'2025_10_28_091827_create_vehicles_table',1),(18,'2025_10_28_092008_create_vehicle_assignments_table',1),(19,'2025_10_28_140847_create_service_schedules_table',1),(20,'2025_10_30_060120_add_soft_deletes_to_events_table',1),(21,'2025_10_31_121524_add_deleted_at_to_vehicles_table',2),(22,'2025_10_31_121713_add_deleted_at_to_vehicle_assignments_table',3),(23,'2025_10_31_121820_add_deleted_at_to_userts_table',4),(24,'2025_10_31_121921_add_deleted_at_to_service_schedules_table',5),(25,'2025_10_31_122018_add_deleted_at_to_production_plans_table',6),(26,'2025_10_31_122122_add_deleted_at_to_departments_table',7),(27,'2025_10_31_122346_add_deleted_at_to_birims_table',8),(28,'2025_10_31_121820_add_deleted_at_to_users_table',9),(29,'2025_11_05_105341_add_is_important_to_events_table',9),(30,'2025_11_05_105425_add_is_important_to_production_plans_table',9),(31,'2025_11_05_105519_add_is_important_to_shipments_table',9),(32,'2025_11_05_105613_add_is_important_to_vehicle_assignments_table',9),(33,'2025_11_06_121559_add_is_important_columns',9),(34,'2025_11_06_121559_add_is_important_columns',10),(35,'2025_11_06_121559_add_is_important_columns',10),(36,'2025_11_07_092324_create_customers_table',11),(37,'2025_11_07_102356_create_media_table',11),(38,'2025_11_07_102446_create_customer_machines_table',11),(39,'2025_11_07_102543_create_complaints_table',11),(40,'2025_11_07_102616_create_test_results_table',11),(41,'2025_11_07_102934_create_travel_table',11),(42,'2025_11_07_103014_create_customer_visits_table',11),(43,'2025_11_07_103959_create_customer_activity_logs_table',11),(44,'2025_11_07_111119_add_soft_deletes_to_customers_table',11),(45,'2025_11_07_115546_update_visits_for_machine_relation',11),(46,'2025_11_07_134021_add_booking_details_to_travels_table',11),(47,'2025_11_07_135129_remove_booking_details_from_travels_table',11),(48,'2025_11_07_135225_create_bookings_table',11),(49,'2025_11_10_060344_add_is_important_to_travels_table',11),(50,'2025_11_11_122657_create_activity_log_table',11),(51,'2025_11_11_122658_add_event_column_to_activity_log_table',11),(52,'2025_11_11_122659_add_batch_uuid_column_to_activity_log_table',11),(53,'2025_11_13_065659_add_visit_details_to_events_table',11),(54,'2025_11_13_070706_update_events_table_for_crm',11),(55,'2025_11_14_110645_create_teams_table',12),(56,'2025_11_14_110700_create_team_user_table',12),(57,'2025_11_14_110816_update_vehicle_assignments_table_for_polymorphism',12),(58,'2025_11_14_112153_remove_created_by_user_id_from_vehicle_assignments_table',12),(59,'2025_11_14_150758_add_title_to_vehicle_assignments_table',12),(60,'2025_11_14_150931_add_status_to_vehicle_assignments_table',12),(61,'2025_11_17_103909_add_description_to_teams_table',13),(62,'2025_11_17_104232_add_timestamps_to_team_user_table',14),(63,'2025_11_17_105229_add_deleted_at_to_teams_table',14),(64,'2025_11_18_070937_create_jobs_table',14),(65,'2025_11_24_082540_create_logistics_vehicles_table',14),(66,'2025_11_24_082950_update_vehicle_assignments_for_polymorphism',14),(67,'2025_11_24_090445_add_soft_deletes_to_logistics_vehicles_table',14),(68,'2025_11_24_151347_create_customer_activities_table',14),(69,'2025_11_24_151431_add_customer_id_to_vehicle_assignments_table',14),(70,'2025_11_25_091853_create_maintenance_types_table',14),(71,'2025_11_25_091921_create_maintenance_assets_table',14),(72,'2025_11_25_091943_create_maintenance_plans_table',14),(73,'2025_11_25_091957_create_maintenance_files_table',14),(74,'2025_11_25_092012_create_maintenance_time_entries_table',14),(75,'2025_11_25_092029_create_maintenance_activity_logs_table',14),(76,'2025_11_25_145638_add_soft_deletes_to_maintenance_tables',14),(77,'2025_11_25_161719_add_details_to_maintenance_assets_table',14),(78,'2025_11_26_162944_create_roles_table',14),(79,'2025_11_26_164015_create_department_user_table',14),(80,'2025_11_26_164034_create_role_user_table',14),(81,'2025_11_26_180343_update_status_enum_in_maintenance_plans_table',14),(82,'2025_11_27_113355_add_completion_note_to_maintenance_plans',14),(83,'2025_11_28_114437_add_assigned_by_to_vehicle_assignments_table',14),(84,'2025_11_28_114653_create_notifications_table',14),(85,'2025_12_01_104357_add_tracking_columns_to_vehicle_assignments_table',14),(86,'2025_12_01_172237_create_files_table',14),(87,'2025_12_02_115236_create_event_types_table',14),(88,'2025_12_02_115607_add_event_type_id_to_events_table',14),(89,'2025_12_03_154710_add_time_columns_to_travels_table',15),(90,'2025_12_03_164625_make_bookings_polymorphic',15),(91,'2025_12_04_100054_add_status_to_bookings_table',15),(92,'2025_12_04_145638_create_custom_permissions_table',15),(93,'2025_12_06_092332_create_shipments_vehicle_types_table',15),(94,'2025_12_11_100144_create_business_units_table',16),(95,'2025_12_11_100354_create_business_unit_user_table',16),(96,'2025_12_11_100524_add_business_unit_id_to_operational_tables',16),(97,'2025_12_11_100615_add_audit_fields_to_tables',16),(98,'2025_12_11_110919_create_permission_tables',17),(99,'2025_12_11_115634_add_business_unit_id_to_maintenance_plans',17),(100,'2025_12_11_120048_add_business_unit_id_to_missing_tables',17),(101,'2025_12_11_153026_create_todos_table',17),(102,'2025_12_15_142040_create_shipment_stops_table',18),(103,'2025_12_16_145442_add_business_unit_id_to_maintenance_activity_logs_table',18),(104,'2025_12_16_150315_add_business_unit_id_to_all_tables',18);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',9),(10,'App\\Models\\User',19),(2,'App\\Models\\User',20),(7,'App\\Models\\User',21),(2,'App\\Models\\User',22),(6,'App\\Models\\User',23),(6,'App\\Models\\User',24),(6,'App\\Models\\User',25),(4,'App\\Models\\User',27),(4,'App\\Models\\User',29),(7,'App\\Models\\User',31),(7,'App\\Models\\User',32),(7,'App\\Models\\User',33),(7,'App\\Models\\User',34),(7,'App\\Models\\User',35),(2,'App\\Models\\User',36),(2,'App\\Models\\User',37),(2,'App\\Models\\User',38);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view_dashboard','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(2,'view_logistics','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(3,'view_production','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(4,'view_maintenance','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(5,'view_administrative','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(6,'manage_users','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(7,'manage_bookings','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(8,'manage_fleet','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(9,'approve_maintenance','web','2025-12-15 07:38:38','2025-12-15 07:38:38');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_plans`
--

DROP TABLE IF EXISTS `production_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `plan_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `week_start_date` date NOT NULL,
  `plan_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_plans_user_id_foreign` (`user_id`),
  KEY `production_plans_business_unit_id_foreign` (`business_unit_id`),
  KEY `production_plans_created_by_foreign` (`created_by`),
  CONSTRAINT `production_plans_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `production_plans_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `production_plans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `production_plans_chk_1` CHECK (json_valid(`plan_details`))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_plans`
--

LOCK TABLES `production_plans` WRITE;
/*!40000 ALTER TABLE `production_plans` DISABLE KEYS */;
INSERT INTO `production_plans` VALUES (1,NULL,4,'Silmek için Üretim Planı','2025-11-01','[{\"machine\":\"a\",\"product\":\"123\",\"quantity\":\"13\"}]','2025-10-31 05:47:42','2025-11-05 04:20:24','2025-11-05 04:20:24',0,NULL),(2,1,1,'test','2025-12-18','[{\"machine\":\"test\",\"product\":\"123456\",\"quantity\":\"13\",\"birim_id\":\"4\"}]','2025-12-18 08:27:39','2025-12-18 08:27:39',NULL,0,NULL);
/*!40000 ALTER TABLE `production_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(2,3),(3,3),(2,4),(3,5),(5,6),(4,7),(5,8),(7,8),(5,9),(8,9),(1,10);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(2,'yonetici','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(3,'mudur','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(4,'lojistik_personeli','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(5,'uretim_personeli','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(6,'idari_isler_personeli','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(7,'bakim_personeli','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(8,'booking_manager','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(9,'fleet_manager','web','2025-12-15 07:38:38','2025-12-15 07:38:38'),(10,'user','web','2025-12-15 07:38:38','2025-12-15 07:38:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_schedules`
--

DROP TABLE IF EXISTS `service_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure_time` time NOT NULL,
  `cutoff_minutes` int NOT NULL DEFAULT '30',
  `default_vehicle_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_schedules_default_vehicle_id_foreign` (`default_vehicle_id`),
  KEY `service_schedules_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `service_schedules_default_vehicle_id_foreign` FOREIGN KEY (`default_vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_schedules`
--

LOCK TABLES `service_schedules` WRITE;
/*!40000 ALTER TABLE `service_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_stops`
--

DROP TABLE IF EXISTS `shipment_stops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_stops` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `shipment_id` bigint unsigned NOT NULL,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dropped_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remaining_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stop_date` datetime NOT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipment_stops_shipment_id_foreign` (`shipment_id`),
  KEY `shipment_stops_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `shipment_stops_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_stops`
--

LOCK TABLES `shipment_stops` WRITE;
/*!40000 ALTER TABLE `shipment_stops` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipment_stops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `shipment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `arac_tipi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plaka` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dorse_plakasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sofor_adi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imo_numarasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gemi_adi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kalkis_limani` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `varis_limani` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kalkis_noktasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `varis_noktasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `onaylanma_tarihi` timestamp NULL DEFAULT NULL,
  `onaylayan_user_id` bigint unsigned DEFAULT NULL,
  `kargo_icerigi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kargo_tipi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kargo_miktari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cikis_tarihi` datetime NOT NULL,
  `tahmini_varis_tarihi` datetime NOT NULL,
  `ekstra_bilgiler` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `aciklamalar` text COLLATE utf8mb4_unicode_ci,
  `dosya_yolu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipments_user_id_foreign` (`user_id`),
  KEY `shipments_onaylayan_user_id_foreign` (`onaylayan_user_id`),
  KEY `shipments_business_unit_id_foreign` (`business_unit_id`),
  KEY `shipments_created_by_foreign` (`created_by`),
  CONSTRAINT `shipments_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `shipments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `shipments_onaylayan_user_id_foreign` FOREIGN KEY (`onaylayan_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `shipments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shipments_chk_1` CHECK (json_valid(`ekstra_bilgiler`))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments`
--

LOCK TABLES `shipments` WRITE;
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
INSERT INTO `shipments` VALUES (1,1,'import',29,'kamyon','27 ABC 123',NULL,'deneme',NULL,NULL,NULL,NULL,'deneme','deneme','2025-12-15 08:48:27',29,'kopet','Ton (T)','13','2025-12-15 12:01:00','2025-12-15 15:01:00',NULL,NULL,NULL,'2025-12-15 08:01:25','2025-12-15 08:49:06','2025-12-15 08:49:06',0,NULL),(2,1,'import',1,'kamyon','test',NULL,'test',NULL,NULL,NULL,NULL,'test','test',NULL,NULL,'test','Ton (T)','13','2025-12-18 12:30:00','2025-12-18 15:30:00',NULL,NULL,'sevkiyat_dosyalari/GjgsiglbC1bKaDBiQjzgFDOftgxcGmIWWkFjNvyZ.jpg','2025-12-18 08:28:36','2025-12-26 08:49:43','2025-12-26 08:49:43',0,NULL),(3,1,'export',29,'kamyon','test',NULL,'test',NULL,NULL,NULL,NULL,'köksan','adana',NULL,NULL,'kopet','Adet','13','2025-12-29 12:02:00','2025-12-29 17:02:00',NULL,'test','sevkiyat_dosyalari/j50NfY6V64geSltVqC6Zt8B9vfV62RpX9WIDUc3y.jpg','2025-12-29 08:06:38','2025-12-29 08:06:38',NULL,0,NULL);
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipments_vehicle_types`
--

DROP TABLE IF EXISTS `shipments_vehicle_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments_vehicle_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments_vehicle_types`
--

LOCK TABLES `shipments_vehicle_types` WRITE;
/*!40000 ALTER TABLE `shipments_vehicle_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipments_vehicle_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`),
  KEY `team_user_user_id_foreign` (`user_id`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_user`
--

LOCK TABLES `team_user` WRITE;
/*!40000 ALTER TABLE `team_user` DISABLE KEYS */;
INSERT INTO `team_user` VALUES (2,1,15,'2025-12-15 11:29:01','2025-12-15 11:29:01'),(3,1,13,'2025-12-15 11:29:01','2025-12-15 11:29:01'),(6,1,18,'2025-12-15 11:29:29','2025-12-15 11:29:29');
/*!40000 ALTER TABLE `team_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by_user_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_created_by_user_id_foreign` (`created_by_user_id`),
  KEY `teams_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `teams_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,NULL,'İDARİ İŞLER EKİBİ',NULL,19,1,'2025-12-15 11:28:36','2025-12-15 11:28:36',NULL);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_results`
--

DROP TABLE IF EXISTS `test_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `customer_machine_id` bigint unsigned DEFAULT NULL,
  `test_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_date` date NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test_results_customer_id_foreign` (`customer_id`),
  KEY `test_results_user_id_foreign` (`user_id`),
  KEY `test_results_customer_machine_id_foreign` (`customer_machine_id`),
  KEY `test_results_business_unit_id_index` (`business_unit_id`),
  CONSTRAINT `test_results_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `test_results_customer_machine_id_foreign` FOREIGN KEY (`customer_machine_id`) REFERENCES `customer_machines` (`id`),
  CONSTRAINT `test_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_results`
--

LOCK TABLES `test_results` WRITE;
/*!40000 ALTER TABLE `test_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todos`
--

DROP TABLE IF EXISTS `todos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `todos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `due_date` datetime DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `priority` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `todos_user_id_foreign` (`user_id`),
  KEY `todos_business_unit_id_foreign` (`business_unit_id`),
  CONSTRAINT `todos_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `todos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todos`
--

LOCK TABLES `todos` WRITE;
/*!40000 ALTER TABLE `todos` DISABLE KEYS */;
/*!40000 ALTER TABLE `todos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travels`
--

DROP TABLE IF EXISTS `travels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `travels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date NOT NULL,
  `end_time` time DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `is_important` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `travels_user_id_foreign` (`user_id`),
  KEY `travels_created_by_foreign` (`created_by`),
  KEY `travels_business_unit_id_foreign` (`business_unit_id`),
  CONSTRAINT `travels_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `travels_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `travels_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travels`
--

LOCK TABLES `travels` WRITE;
/*!40000 ALTER TABLE `travels` DISABLE KEYS */;
/*!40000 ALTER TABLE `travels` ENABLE KEYS */;
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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_foreign` (`department_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@koksan.com','2025-10-31 03:38:26','$2y$10$octk4uC8MBLpoVdpGHgFp.MnJwnoisM5Au2xFQM75Z6RNF9Dddpmi','admin','tAw0ayelEUelm3yDwZFgksQhYYXtbpSSsQR6Rm62N3brQo7nM7cc5bRkJxEU','2025-10-31 03:38:26','2025-10-31 03:38:26',NULL,NULL),(2,'Aslıhan Aydın','aslihan.aydin@koksan.com',NULL,'$2y$10$c9z85mTlNQDLyiiC51b/AuiajEpWJ4a3TANaXm94fjdX8TpdYGT7C','yönetici',NULL,'2025-10-31 03:39:44','2025-11-06 09:27:07',NULL,'2025-11-06 09:27:07'),(3,'İdari İşler Departmanı Kullanıcısı','hizmetdeneme@koksan.com',NULL,'$2y$10$rFgRoUikAC/SL7Xq8SWoxuVGSLqKi7KZ9uYDcZLsk.SX86g0k0kRK','kullanıcı',NULL,'2025-10-31 03:50:46','2025-11-06 09:27:11',3,'2025-11-06 09:27:11'),(4,'Üretim Departmanı Kullanıcısı','uretimdeneme@koksan.com',NULL,'$2y$10$t0Z1ub9kAJQ8trBzKIyy1.7pslCfMrNB0eBlrJIEFJ.RR7s1Kf9c2','kullanıcı',NULL,'2025-10-31 03:53:29','2025-11-06 09:27:23',2,'2025-11-06 09:27:23'),(5,'Lojistik Departmanı Kullanıcısı','lojistikdeneme@koksan.com',NULL,'$2y$10$beJfuLkRwAjHgDqz048/leUdUitKFcqERdfmAZTd21unVX.bpeAjm','kullanıcı',NULL,'2025-10-31 03:54:17','2025-11-06 09:27:15',1,'2025-11-06 09:27:15'),(7,'Taylor Swift','taylorswift@koksan.com',NULL,'$2y$10$cenTZOYdqpFf1lbZWR1dOe9HKy1jDt..8eLwWyk1GgyOHkyWUs9gS','kullanıcı',NULL,'2025-10-31 10:46:59','2025-10-31 10:47:06',3,'2025-10-31 10:47:06'),(8,'Lojistik Departmanı Kullanıcısı 2','lojistikdeneme2@koksan.com',NULL,'$2y$10$PRLM5gNixOPqNIgjNnZ1ne7S/GhNP/7eOArSEDGDhv69ZTAz3w6wG','kullanıcı',NULL,'2025-11-03 10:21:45','2025-11-06 09:27:19',1,'2025-11-06 09:27:19'),(9,'Semih Bozpolat','semih.bozpolat@koksan.com',NULL,'$2y$10$ku.oT/fxwMObsJV914mVteBCDZMFFocsDvKl9HJMb5WnoT56VOVo6','admin','pxNrfZH9OfycnE7Ehzwv7NHz7GWmppHnkGpselXEmp4VMaIMkRQglmuICJcf','2025-11-06 09:28:59','2025-12-15 07:59:05',1,NULL),(10,'Adem Savaş','adem.savas@koksan.com.tr',NULL,'$2y$10$Cpp461EzU6aMZNwxxCamRuEt9IBICFdXaDoIrFzUDnINKOpZxcueW','yönetici',NULL,'2025-11-06 10:05:36','2025-11-12 02:41:53',1,'2025-11-12 02:41:53'),(11,'Hizmet Departmanı Kullanıcısı 3','hizmetdeneme3@koksan.com',NULL,'$2y$10$FJknut8ECPpxIJEzCYrTWenMGBVlBnNXER7WAaq3GlF6H0HXgPUba','kullanıcı',NULL,'2025-11-06 10:21:41','2025-11-06 10:22:36',3,'2025-11-06 10:22:36'),(12,'mustafa özaydın','mustafa.ozaydin@koksan.com',NULL,'$2y$10$ZRfWQNFz8q.w5rDhKLURW.dOk83/13q4tAj5lXlwpdTlp/Q0MCp82','yönetici',NULL,'2025-11-06 11:02:50','2025-11-13 05:39:46',2,NULL),(13,'Ali Rıfat KARTAL','ali.kartal@koksan.com',NULL,'$2y$10$4BmRdK7a8bUxYME9jyoC.OiAKu/c/Ij1HQk1YpYSGGeoyDMtRhanm','yönetici','CvOvULC4aeBPMsNokhS23KRMbNNRjQJvAd4KbeN051pCCtJCrXVm9yE4t4X1','2025-11-06 11:09:54','2025-12-05 06:27:30',3,NULL),(14,'Ömer Faruk Akpınar','omer.akpinat@koksan.com',NULL,'$2y$10$M2sXTb6dVseu.5PCaBcObufFGTAXfDSpxG5JCP6gsm3qwKxKtS/9C','yönetici',NULL,'2025-11-07 09:51:17','2025-12-03 08:08:34',3,'2025-12-03 08:08:34'),(15,'Adem Savaş','adem.savas@koksan.com',NULL,'$2y$10$n7qkCDBMbqwokWoAgW6eVu4ES6mDP1mtmY.lBqzxyZrAayEPfnTby','yönetici','zhrYXF8aEvPkrFoYXQVkKnt3kOBBTSg15nEGOciY2OjefXNjJ2jdLD9omFuN','2025-11-11 02:31:33','2025-11-11 02:31:33',1,NULL),(16,'sema özkeskin','sema.ozkeskin@koksan.com',NULL,'$2y$10$UShGolXIUK..dw1X9QTDc.JCHil6GaYtw9o12kLnQYXGIpXLa5a/O','kullanıcı',NULL,'2025-11-14 11:48:29','2025-11-14 11:48:29',3,NULL),(18,'Mehmet GÜLTEKİN','mehmet.gultekin@koksan.com',NULL,'$2y$10$NLHUa2ujnCY9kYxvazdIRe2.JQosH063Zm0jU/A/DG0e5hilWLmze','yönetici',NULL,'2025-11-25 08:00:37','2025-11-25 10:11:33',3,NULL),(19,'Ömer Faruk Akpınar','omer.akpinar@koksan.com',NULL,'$2y$10$77gikCTHu6m8ELjFTav47e/sCLjScOo29M9V9wuCfeNG1WWe8pg0q','user','fJfANdIB6GAF5KtzAs8b96lWIEU4n0x7oq3IowEQD3zrGpCDuwuTxNJYEiiQ','2025-12-02 14:43:51','2025-12-16 07:05:49',6,NULL),(20,'KÖKSAN TV','tv@koksan.com',NULL,'$2y$10$bG8YaZ6SqqrOfBu6U9y0P.6jR5H5MZvyArbpRuV1MuSgx7JheQs9y','yonetici',NULL,'2025-12-02 14:54:01','2025-12-25 10:35:58',NULL,NULL),(21,'Preform Mekanik Bakım','mekanik.bakim@koksan.com',NULL,'$2y$10$6GjeBEfeoPvYwbDzmmZNU.7xI/LQ2weuz69YVZVkXgDf/C57wqtxO','bakim_personeli','U8NBLpzWgW7FoC6KtqJBHxa6WBfzRno8SkrReI8LWo3OA6sFCK0Mry0NUP5Y','2025-12-02 15:01:23','2025-12-15 07:50:31',5,NULL),(23,'Leylanur Yardi','leylanur.yardi@koksan.com',NULL,'$2y$10$YwoEEk1x0lcxlk..2wweUOTeLg5q9timWU1YVG4mIFanON/P.00nG','idari_isler_personeli','6aZryVabA2IVA7ErIduaWXg35ohB0KJjuAv9WPFomPmn5zFKOFg6qTsooTua','2025-12-04 09:49:33','2025-12-15 07:58:28',3,NULL),(24,'Özgül Aslan','ozgul.aslan@koksan.com',NULL,'$2y$10$MOMHGwf09NsdwsRN2yNXD.i.fbHQqBqnB7x2u2xup6YjQYLcf0gdq','idari_isler_personeli','WIhcAT45TzihewQSlJ7kS4IGVCofakNyJPdIWlRgG6NfKOBFbChOjGnsQk2a','2025-12-04 09:50:19','2025-12-15 07:58:37',3,NULL),(25,'Rüveyda Öztepir','ruveyda.oztepir@koksan.com',NULL,'$2y$10$8xEVM7ZTkRfMjRfEdNW6NenWmAXOH3lvMBaW2O4hQ3ybauj9B21oG','idari_isler_personeli',NULL,'2025-12-04 09:51:01','2025-12-15 07:58:43',3,NULL),(26,'deneme','c@koksan.com',NULL,'$2y$10$Pl64HZlm1rwLLnVSdzVzDOglXUWfDttuljlwrMR8huCH0OvRbs7gW','kullanici',NULL,'2025-12-09 08:06:49','2025-12-09 08:06:49',1,NULL),(27,'sevkiyat','sevkiyat1@koksan.com',NULL,'$2y$10$uKHfOlG81CZpoKcXYbIoGeXmW/BT1cqxI7/yTzV.3.G9RPh6U/4AC','lojistik_personeli',NULL,'2025-12-12 12:55:42','2025-12-17 11:02:35',1,NULL),(28,'bakım','bakim1@koksan.com',NULL,'$2y$10$qOF2Noi5sTnsVa9C0L7gYuKYM1Bh3YwAzklrQb86QlPuWJZu6zOny','kullanici',NULL,'2025-12-12 13:21:58','2025-12-12 13:21:58',5,NULL),(29,'Nuri Oğuz','nuri.oguz@koksan.com',NULL,'$2y$10$WuUWXmNTVM88up/03NZGM.WguDhR2DzbvHWCcBtA7vaKfLffoOoVG','lojistik_personeli','JtDO7nFfHqMcDXbVPbfCKEvKHU0db59ouiikFJxJjpJ9RGGZXDe6YxWfeIHp','2025-12-15 07:22:40','2025-12-15 07:48:53',1,NULL),(31,'Erkut Agar','erkut.agar@koksan.com',NULL,'$2y$10$kmCXJzLb7kxt7Lqesn4PhuiRXU1Lmf17TOOEm5ZLwFFo5sf/svV9O','bakim_personeli',NULL,'2025-12-22 06:41:08','2025-12-22 06:42:16',5,NULL),(32,'Hilal Şahin','hilal.sahin@koksan.com',NULL,'$2y$10$3JY0WAaC2zeArofxDIrpFely/nYeARGe0Ij1WAOyuZ2hsY2YDO.Qi','bakim_personeli','BL3vPNTZeUxVBoc1NYuFOfXnKS7ZfxQlsOsW58FObhJirx5YVFxig2IVFJnG','2025-12-22 06:41:47','2025-12-22 06:42:09',5,NULL),(33,'Ergün Güçlü','ergun.guclu@koksan.com',NULL,'$2y$10$3IFrHvXjog8Op.tMNujm7uQU8efozlY6GCSSdRjSqZi6LtMbKwX96','bakim_personeli',NULL,'2025-12-22 06:42:49','2025-12-22 06:42:49',5,NULL),(34,'Hüseyin Oğuz','huseyin.oguz@koksan.com',NULL,'$2y$10$JcyboHV8TebMncG6XmtFKO/29JInvMN55pXXf/ZR4AcIQoxBZPNYq','bakim_personeli',NULL,'2025-12-22 06:43:48','2025-12-22 06:43:48',5,NULL),(35,'Mesut Altın','mesut.altin@koksan.com',NULL,'$2y$10$F4GvhtQp4DACsscHO7GK9O3UOuu7pdVvLJaOIy0IWUPAOzal/JUXu','bakim_personeli',NULL,'2025-12-22 06:44:24','2025-12-22 06:44:24',5,NULL),(36,'bakım yönetici','bkmyntc@koksan.com',NULL,'$2y$10$0C.NHnGmiRx9Ww5/ipxob..ger9uuTvw7SgsMmEqTE1NPG6cGexaW','yonetici',NULL,'2025-12-23 05:44:49','2025-12-23 05:44:49',5,NULL),(37,'Ercan Eren','ercan.eren@koksan.com',NULL,'$2y$10$zDNmEWO2yXU/V0aKFk8hTeglraVkzhf6MXhgjjipjfoh6WUbFtZBu','yonetici',NULL,'2025-12-23 14:41:15','2025-12-23 14:41:15',5,NULL),(38,'Mesut Yiğit','mesut.yigit@koksan.com',NULL,'$2y$10$9R8gAfeiDiSPHjP88DHwBue3Cr9VrBpmIbOo7xYcm13bHTFDzfvr.','yonetici',NULL,'2025-12-25 07:17:36','2025-12-25 07:17:36',1,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_assignments`
--

DROP TABLE IF EXISTS `vehicle_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `assignment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'company_vehicle',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_id` bigint unsigned DEFAULT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `task_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requester_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `assigned_by` bigint unsigned DEFAULT NULL,
  `start_km` decimal(10,2) DEFAULT NULL,
  `end_km` decimal(10,1) DEFAULT NULL COMMENT 'Bitiş Kilometresi',
  `start_fuel_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_fuel_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bitiş Yakıt',
  `fuel_cost` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT '0',
  `responsible_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsible_id` bigint unsigned NOT NULL,
  `resource_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_assignments_vehicle_id_foreign` (`vehicle_id`),
  KEY `vehicle_assignments_user_id_foreign` (`user_id`),
  KEY `idx_responsible_assignment` (`responsible_type`,`responsible_id`),
  KEY `idx_resource_assignment` (`resource_type`,`resource_id`),
  KEY `vehicle_assignments_vehicle_id_vehicle_type_index` (`vehicle_id`,`vehicle_type`),
  KEY `vehicle_assignments_customer_id_foreign` (`customer_id`),
  KEY `vehicle_assignments_created_by_foreign` (`created_by`),
  KEY `vehicle_assignments_business_unit_id_foreign` (`business_unit_id`),
  CONSTRAINT `vehicle_assignments_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `vehicle_assignments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `vehicle_assignments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `vehicle_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_assignments`
--

LOCK TABLES `vehicle_assignments` WRITE;
/*!40000 ALTER TABLE `vehicle_assignments` DISABLE KEYS */;
INSERT INTO `vehicle_assignments` VALUES (1,NULL,'general','SEDEX DENETİMİ',NULL,NULL,19,NULL,'SEDEX FİRMASI FABRİKA DENETİMİ BELGİN ATAK BİLGİSİ DAHİLİNDE','KÖKSAN MERKEZ','Ömer Faruk Akpınar','2025-12-15 14:30:00','2025-12-15 18:00:00','EMEL YILMAZ SEDEX DENETİMİ','completed',19,NULL,NULL,NULL,NULL,NULL,'2025-12-15 11:26:18','2025-12-16 05:24:14',NULL,0,'App\\Models\\User',19,NULL,NULL,NULL),(2,NULL,'general','SABUNCULAR FİRMASI DEENTİM',NULL,NULL,19,NULL,'SABUNCULAR FİRMASI DENETİMİ SERKAN TÖLEK NEZARETİNDE','KÖKSAN MERKEZ','Ömer Faruk Akpınar','2025-12-15 14:33:00','2025-12-15 18:00:00','BATIHAN BEY','completed',19,NULL,NULL,NULL,NULL,NULL,'2025-12-15 11:32:38','2025-12-16 05:23:58',NULL,0,'App\\Models\\User',19,NULL,NULL,NULL),(3,NULL,'general','SEDEX DENETİM',NULL,NULL,19,NULL,'SEDX DENETİM','KÖKSAN MERKEZ','Ömer Faruk Akpınar','2025-12-16 10:00:00','2025-12-16 18:00:00',NULL,'in_progress',19,NULL,NULL,NULL,NULL,NULL,'2025-12-16 05:25:18','2025-12-16 06:11:36',NULL,0,'App\\Models\\User',19,NULL,NULL,NULL),(4,NULL,'general','ZİYARET',NULL,NULL,19,NULL,'GÜLSAN VE SANKO DAN MİSAFİRLER ERHAN AKGÜL BEYİN MİSAFİRLERİ','KÖKSAN MERKEZ','Ömer Faruk Akpınar','2025-12-16 11:10:00','2025-12-16 14:00:00','ERHAN AKGÜL MİSAFİRİ ALPHAN ULUTAŞ VE  GÜLBEY EKİCİ','completed',19,NULL,NULL,NULL,NULL,NULL,'2025-12-16 08:07:31','2025-12-17 05:55:56',NULL,0,'App\\Models\\User',19,NULL,NULL,NULL),(5,NULL,'general','SEDEX DENETİM EMEL YILMAZ',NULL,NULL,19,NULL,'SEDEX DENETİM 3. GÜNÜ BELGİN ATAK EŞLİĞİNDE KIRMIZI ODADA DEVAM ETMEKTE','KÖKSAN MERKEZ','Ömer Faruk Akpınar','2025-12-17 09:00:00','2025-12-17 18:00:00',NULL,'in_progress',19,NULL,NULL,NULL,NULL,NULL,'2025-12-17 05:57:46','2025-12-17 05:57:55',NULL,0,'App\\Models\\User',19,NULL,NULL,NULL);
/*!40000 ALTER TABLE `vehicle_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_unit_id` bigint unsigned DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`),
  KEY `vehicles_business_unit_id_foreign` (`business_unit_id`),
  KEY `vehicles_created_by_foreign` (`created_by`),
  CONSTRAINT `vehicles_business_unit_id_foreign` FOREIGN KEY (`business_unit_id`) REFERENCES `business_units` (`id`),
  CONSTRAINT `vehicles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-30 15:43:08
