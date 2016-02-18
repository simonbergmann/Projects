-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: freewtt
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `canadian_sms_gateways`
--

DROP TABLE IF EXISTS `canadian_sms_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `canadian_sms_gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telco_name` varchar(60) NOT NULL,
  `gateway_address` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canadian_sms_gateways`
--

LOCK TABLES `canadian_sms_gateways` WRITE;
/*!40000 ALTER TABLE `canadian_sms_gateways` DISABLE KEYS */;
INSERT INTO `canadian_sms_gateways` VALUES (1,'bell','txt.bell.ca'),(2,'bell','txt.bellmobility.ca'),(3,'koodo mobile','msg.koodomobile.com'),(4,'fido','fido.ca'),(5,'manitoba telecom systems','text.mtsmobility.com'),(6,'nbtel','wirefree.informe.ca'),(7,'pagenet','pagegate.pagenet.ca'),(8,'rogers','pcs.rogers.com'),(9,'sasktel','sms.sasktel.com'),(10,'telus','msg.telus.com'),(11,'virgin mobile','vmobile.ca'),(12,'fido','sms.fido.ca'),(13,'mts mobility','text.mtsmobility.com'),(14,'rogers wireless','sms.rogers.com'),(15,'wind mobile','txt.windmobile.ca');
/*!40000 ALTER TABLE `canadian_sms_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provinces` (
  `province` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`province`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES ('alberta'),('british columbia'),('manitoba'),('new brunswick'),('newfoundland and labrador'),('northwest territories'),('nova scotia'),('nunavut'),('ontario'),('prince edward island'),('quebec'),('saskatchewan'),('yukon');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_input`
--

DROP TABLE IF EXISTS `user_input`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_input` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(140) NOT NULL,
  `dest_phone` varchar(20) NOT NULL,
  `message` varchar(160) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_input`
--

LOCK TABLES `user_input` WRITE;
/*!40000 ALTER TABLE `user_input` DISABLE KEYS */;
INSERT INTO `user_input` VALUES (1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36','7785837777','hi Simon\r\n        ','2015-12-03'),(2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36','7785837777','2nd message to Simon\r\n        ','2015-12-03'),(3,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36','7785837777','Hello this is the 3rd message\r\n        ','2015-12-03');
/*!40000 ALTER TABLE `user_input` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-04 10:10:12
