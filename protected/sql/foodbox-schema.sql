-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: francis-laptop    Database: foodbox
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

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
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('admin','1',NULL,NULL),('customer','2',NULL,'N;');
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `customer_notes` text,
  PRIMARY KEY (`customer_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,7,'Testing notes'),(2,9,'');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('grower','customer'),('admin','grower');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `grower_id` int(11) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_name` varchar(45) DEFAULT NULL,
  `user_phone` varchar(45) DEFAULT NULL,
  `user_mobile` varchar(45) DEFAULT NULL,
  `user_address` varchar(150) DEFAULT NULL,
  `user_address2` varchar(150) DEFAULT NULL,
  `user_suburb` varchar(45) DEFAULT NULL,
  `user_state` varchar(45) DEFAULT NULL,
  `user_postcode` varchar(45) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `create_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,1,'admin@snapfrozen.com.au','66cbef2423f117231b6e039d204896c6','Francis Beresford','','','','','','','','2012-08-07 14:29:59','2012-08-07 10:50:57',1,'0000-00-00 00:00:00',NULL),(2,2,NULL,'customer@customer.com','a36d7c1b2832bb7c0f1001ae3e6051bb','Joe Bloggs','','','','','','','','2012-08-07 14:44:20','2012-08-07 14:50:07',2,'2012-07-26 16:04:07',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grower_items`
--

DROP TABLE IF EXISTS `grower_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grower_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `grower_id` int(11) DEFAULT NULL,
  `item_name` varchar(45) DEFAULT NULL,
  `item_value` decimal(7,2) DEFAULT NULL,
  `item_unit` varchar(20) DEFAULT NULL,
  `item_available_from` smallint(6) DEFAULT NULL,
  `item_available_to` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `grower_id` (`grower_id`),
  CONSTRAINT `grower_id` FOREIGN KEY (`grower_id`) REFERENCES `growers` (`grower_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grower_items`
--

LOCK TABLES `grower_items` WRITE;
/*!40000 ALTER TABLE `grower_items` DISABLE KEYS */;
INSERT INTO `grower_items` VALUES (2,2,'Bananas',5.00,'KG',1,12),(3,3,'Apples',5.00,'KG',2,11),(5,1,'Beans',7.00,'KG',4,10);
/*!40000 ALTER TABLE `grower_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(45) DEFAULT NULL,
  `location_delivery_value` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (5,'Bellingen - collect from 19 Casuarina Ave',0.00),(6,'Bellingen CBD & Coffs Harbour Region delivery',7.00),(7,'Thora - collect from Thora Hall',7.00),(8,'Dorrigo - collect from United Service Station',7.00),(9,'Dorrigo - delivery to home or workplace',7.00);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `box_sizes`
--

DROP TABLE IF EXISTS `box_sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `box_sizes` (
  `box_size_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_size_name` varchar(45) DEFAULT NULL,
  `box_size_value` decimal(7,2) DEFAULT NULL,
  `box_size_markup` decimal(7,2) DEFAULT NULL,
  `box_size_price` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`box_size_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `box_sizes`
--

LOCK TABLES `box_sizes` WRITE;
/*!40000 ALTER TABLE `box_sizes` DISABLE KEYS */;
INSERT INTO `box_sizes` VALUES (1,'Small',10.00,10.00,20.00),(2,'Medium',15.00,15.00,30.00),(3,'Large',25.00,25.00,50.00);
/*!40000 ALTER TABLE `box_sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boxes`
--

DROP TABLE IF EXISTS `boxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boxes` (
  `box_id` int(11) NOT NULL AUTO_INCREMENT,
  `size_id` int(11) DEFAULT NULL,
  `box_price` decimal(7,2) DEFAULT NULL,
  `week_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`box_id`),
  KEY `week_id` (`week_id`),
  KEY `size_id` (`size_id`),
  KEY `box_size_id` (`box_id`),
  CONSTRAINT `box_size_id` FOREIGN KEY (`size_id`) REFERENCES `box_sizes` (`box_size_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `week_id` FOREIGN KEY (`week_id`) REFERENCES `weeks` (`week_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boxes`
--

LOCK TABLES `boxes` WRITE;
/*!40000 ALTER TABLE `boxes` DISABLE KEYS */;
INSERT INTO `boxes` VALUES (1,2,30.00,2),(2,3,50.00,2),(10,1,20.00,3),(11,2,30.00,3),(12,1,20.00,2),(13,1,20.00,1),(14,3,50.00,3);
/*!40000 ALTER TABLE `boxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `box_items`
--

DROP TABLE IF EXISTS `box_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `box_items` (
  `box_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(45) DEFAULT NULL,
  `box_id` int(11) DEFAULT NULL,
  `grower_id` int(11) DEFAULT NULL,
  `item_value` decimal(7,2) DEFAULT NULL,
  `item_unit` varchar(20) DEFAULT NULL,
  `item_quantity` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`box_item_id`),
  KEY `box_id` (`box_id`),
  KEY `grower_id` (`grower_id`),
  CONSTRAINT `fk_boxItems_boxes` FOREIGN KEY (`box_id`) REFERENCES `boxes` (`box_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_boxItems_grower` FOREIGN KEY (`grower_id`) REFERENCES `growers` (`grower_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `box_items`
--

LOCK TABLES `box_items` WRITE;
/*!40000 ALTER TABLE `box_items` DISABLE KEYS */;
INSERT INTO `box_items` VALUES (11,'Bananas',1,2,5.00,'KG',5),(12,'Apples',1,3,5.00,'KG',1),(19,'Apples',12,3,4.00,'KG',4),(21,'Bananas',12,2,5.00,'KG',1),(49,'Apples',12,3,5.00,'KG',2),(50,'Bananas',10,2,5.00,'KG',1),(51,'Apples',10,3,5.00,'KG',1),(52,'Apples',11,3,5.00,'KG',1),(53,'Beans',11,1,7.00,'KG',1),(55,'Beans',14,1,7.00,'KG',1),(56,'Beans',14,1,7.00,'KG',1),(57,'Beans',14,1,7.00,'KG',1);
/*!40000 ALTER TABLE `box_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('admin',2,'',NULL,'N;'),('customer',2,'',NULL,'N;'),('grower',2,'',NULL,'N;');
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_boxes`
--

DROP TABLE IF EXISTS `customer_boxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_boxes` (
  `customer_box_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `box_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `delivery_cost` decimal(9,2) DEFAULT '0.00',
  PRIMARY KEY (`customer_box_id`),
  KEY `customer_id` (`customer_id`),
  KEY `box_id` (`box_id`),
  CONSTRAINT `box_id` FOREIGN KEY (`box_id`) REFERENCES `boxes` (`box_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_boxes`
--

LOCK TABLES `customer_boxes` WRITE;
/*!40000 ALTER TABLE `customer_boxes` DISABLE KEYS */;
INSERT INTO `customer_boxes` VALUES (13,2,11,1,0.00),(14,1,10,2,14.00),(15,1,10,2,14.00),(16,1,14,1,7.00);
/*!40000 ALTER TABLE `customer_boxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_payments`
--

DROP TABLE IF EXISTS `customer_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_value` decimal(7,2) DEFAULT NULL,
  `payment_type` varchar(45) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `customer_id` (`customer_id`),
  KEY `fk_Customer_Payments` (`customer_id`),
  CONSTRAINT `fk_Customer_Payments` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_payments`
--

LOCK TABLES `customer_payments` WRITE;
/*!40000 ALTER TABLE `customer_payments` DISABLE KEYS */;
INSERT INTO `customer_payments` VALUES (3,30.00,'Credit Card','2012-08-07 13:49:19',1);
/*!40000 ALTER TABLE `customer_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weeks`
--

DROP TABLE IF EXISTS `weeks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weeks` (
  `week_id` int(11) NOT NULL AUTO_INCREMENT,
  `week_starting` date DEFAULT NULL,
  `week_notes` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`week_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weeks`
--

LOCK TABLES `weeks` WRITE;
/*!40000 ALTER TABLE `weeks` DISABLE KEYS */;
INSERT INTO `weeks` VALUES (1,'2012-07-01',NULL),(2,'2012-08-08',NULL),(3,'2012-08-15',NULL);
/*!40000 ALTER TABLE `weeks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `growers`
--

DROP TABLE IF EXISTS `growers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `growers` (
  `grower_id` int(11) NOT NULL AUTO_INCREMENT,
  `grower_name` varchar(45) DEFAULT NULL,
  `grower_email` varchar(150) DEFAULT NULL,
  `grower_website` varchar(100) DEFAULT NULL,
  `grower_phone` varchar(45) DEFAULT NULL,
  `grower_mobile` varchar(45) DEFAULT NULL,
  `grower_address` varchar(150) DEFAULT NULL,
  `grower_address2` varchar(150) DEFAULT NULL,
  `grower_postcode` varchar(15) DEFAULT NULL,
  `grower_suburb` varchar(45) DEFAULT NULL,
  `grower_state` varchar(45) DEFAULT NULL,
  `grower_distance_kms` varchar(45) DEFAULT NULL,
  `grower_bank_account_name` varchar(45) DEFAULT NULL,
  `grower_bank_bsb` varchar(45) DEFAULT NULL,
  `grower_bank_acc` varchar(45) DEFAULT NULL,
  `grower_certification_status` varchar(150) DEFAULT NULL,
  `grower_order_days` varchar(255) DEFAULT NULL,
  `grower_produce` text,
  `grower_notes` text,
  `grower_payment_details` text,
  PRIMARY KEY (`grower_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `growers`
--

LOCK TABLES `growers` WRITE;
/*!40000 ALTER TABLE `growers` DISABLE KEYS */;
INSERT INTO `growers` VALUES (1,'Sonja Engelhardt','sonniengel@gmail.com','',NULL,NULL,'Orara Valley',NULL,NULL,NULL,NULL,'','','','','','','blueberries, mandarins, kiwi, citrus, walnuts','inactive - doesn\'t have supply to make the distance travelled worthwhile.','test'),(2,'Alan Johnstone Biodynamic Agriculture','bdpreps@biodynamics.net.au','',NULL,NULL,'Maher\'s Rd, Bellingen/Boggy Creek',NULL,NULL,NULL,NULL,'','','','','','',NULL,NULL,NULL),(3,'Bamboo Gardens Farmstay (Richard Hersel)','bamboogardensfarmstay@gmail.com ','www.bamboosatnambuccavalley.com.au','02 6564 8089 ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `growers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-07 15:12:31
