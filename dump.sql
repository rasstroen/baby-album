-- MySQL dump 10.13  Distrib 5.5.28, for Linux (x86_64)
--
-- Host: localhost    Database: baby_album
-- ------------------------------------------------------
-- Server version	5.5.28-log

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
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `child_name` varchar(70) NOT NULL,
  `createTime` int(10) unsigned NOT NULL,
  `updateTime` int(10) unsigned NOT NULL,
  `birthDate` date NOT NULL,
  `private` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `picture` int(10) unsigned NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `private` (`private`),
  KEY `sex` (`sex`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
INSERT INTO `album` VALUES (1,43,'Лёнечка',1352469713,1354182443,'2011-10-24',0,56,1),(3,45,'Сережа',1353076071,1353077223,'2003-07-24',0,267,1);
/*!40000 ALTER TABLE `album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_events`
--

DROP TABLE IF EXISTS `album_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `event_id` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `eventTime` datetime NOT NULL,
  `is_public` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `comments_count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eventTime` (`eventTime`),
  KEY `is_public` (`is_public`),
  KEY `event_id` (`album_id`,`event_id`,`eventTime`),
  KEY `comments_count` (`comments_count`),
  KEY `creator_id` (`creator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_events`
--

LOCK TABLES `album_events` WRITE;
/*!40000 ALTER TABLE `album_events` DISABLE KEYS */;
INSERT INTO `album_events` VALUES (1,1,34,0,1352470852,45,'умаялся и спит','Мама наиграла так, что заснул прямо на месте)','2012-11-09 18:04:00',1,3),(2,1,43,7,1352986967,33,'Вечеринка в честь первого дня рождения удалась','Мы готовились целую неделю, и праздник удался. Пока папа надувал шарики, мама готовила потрясающие десерты и шашлыки. А на день рождения пришел наш друг - Паша, и тоже принес сладких подарков)','2012-10-27 18:09:00',1,0),(3,1,43,1,1352880386,49,'Вот и я!','Мама и папа были вместе, когда это произошло. Появившись на свет, хомяк удивленно огляделся и пребывал в удивлении всё время, пока вокруг него суетились медсестры. Попробовав маминого молочка, мы заснули, а родители ещё долго разглядывали чудо.','2011-10-24 16:10:00',1,0),(4,1,43,15,1352887233,41,'Колбасёр','маам, а мы тут с папой колбасимся!','2012-08-01 13:59:00',1,0),(5,1,43,24,1352980530,37,'Первые травяные ванночки','В четыре, а то и в шесть рук - вода идеальной температуры, ужас в глазах родителей - но всё оказалось не так уж и страшно)','2011-10-31 15:41:00',1,0),(6,1,43,15,1353304429,29,'Языкастик','','2012-03-09 15:30:00',1,0),(13,1,43,0,1353356103,25,'Потягушки','Слаааадко поспали!','2012-07-11 00:00:00',1,0),(14,1,43,0,1353408603,21,'отдыхаем','','2012-03-09 00:00:00',1,0),(15,1,43,0,1354193062,60,'задумались','','2012-11-29 16:44:00',1,0),(16,1,43,0,1354263001,68,'вкусные ноги)','','2012-11-30 12:09:00',1,0),(17,1,43,0,1354263776,72,'купака','','2012-11-30 12:22:00',1,0),(18,1,43,0,1354264195,80,'купаемся','','2012-11-30 12:29:00',0,0),(19,1,43,0,1354278553,84,'ня','','2012-11-30 16:29:00',1,0),(20,1,43,0,1354451670,110,'Фотосет','','2012-12-02 16:33:00',1,0);
/*!40000 ALTER TABLE `album_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_events_fields`
--

DROP TABLE IF EXISTS `album_events_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album_events_fields` (
  `event_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value_int` decimal(8,3) unsigned DEFAULT NULL,
  `value_varchar` varchar(255) DEFAULT NULL,
  `value_text` text,
  PRIMARY KEY (`event_id`,`field_id`),
  KEY `value_int` (`value_int`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_events_fields`
--

LOCK TABLES `album_events_fields` WRITE;
/*!40000 ALTER TABLE `album_events_fields` DISABLE KEYS */;
INSERT INTO `album_events_fields` VALUES (1,1,NULL,'2012-11-09 18:04:00',NULL),(1,2,NULL,'умаялся и спит',NULL),(1,3,NULL,NULL,'Мама наиграла так, что заснул прямо на месте)'),(2,12,NULL,'2012-10-27 18:09:00',NULL),(2,13,NULL,NULL,'Мы готовились целую неделю, и праздник удался. Пока папа надувал шарики, мама готовила потрясающие десерты и шашлыки. А на день рождения пришел наш друг - Паша, и тоже принес сладких подарков)'),(2,16,83.000,NULL,NULL),(2,17,11.300,NULL,NULL),(2,18,NULL,'Вечеринка в честь первого дня рождения удалась',NULL),(3,5,NULL,'2011-10-24 16:10:00',NULL),(3,6,NULL,NULL,'Мама и папа были вместе, когда это произошло. Появившись на свет, хомяк удивленно огляделся и пребывал в удивлении всё время, пока вокруг него суетились медсестры. Попробовав маминого молочка, мы заснули, а родители ещё долго разглядывали чудо.'),(3,8,NULL,'Леонид',NULL),(3,9,3.570,NULL,NULL),(3,10,53.000,NULL,NULL),(3,11,1.000,NULL,NULL),(3,19,NULL,'Вот и я!',NULL),(4,1,NULL,'2012-08-01 13:59:00',NULL),(4,2,NULL,'Колбасёр',NULL),(4,3,NULL,NULL,'маам, а мы тут с папой колбасимся!'),(5,1,NULL,'2011-10-31 15:41:00',NULL),(5,2,NULL,'Первые травяные ванночки',NULL),(5,3,NULL,NULL,'В четыре, а то и в шесть рук - вода идеальной температуры, ужас в глазах родителей - но всё оказалось не так уж и страшно)'),(6,1,NULL,'2012-03-09 15:30:00',NULL),(6,2,NULL,'Языкастик',NULL),(6,3,NULL,NULL,''),(7,1,NULL,'2012-11-20 00:04:00',NULL),(7,2,NULL,'Потягушки',NULL),(7,3,NULL,NULL,'Слаааадко поспали!'),(8,1,NULL,'2012-11-20 00:09:00',NULL),(8,2,NULL,'Потягушки',NULL),(8,3,NULL,NULL,'Слаааадко поспали!'),(9,1,NULL,'2012-11-20 00:09:00',NULL),(9,2,NULL,'Потягушки',NULL),(9,3,NULL,NULL,'Слаааадко поспали!'),(10,1,NULL,'2012-11-20 00:09:00',NULL),(10,2,NULL,'Потягушки',NULL),(10,3,NULL,NULL,'Слаааадко поспали!'),(11,1,NULL,'2012-11-20 00:09:00',NULL),(11,2,NULL,'Потягушки',NULL),(11,3,NULL,NULL,'Слаааадко поспали!'),(12,1,NULL,'2012-11-20 00:09:00',NULL),(12,2,NULL,'Потягушки',NULL),(12,3,NULL,NULL,'Слаааадко поспали!'),(13,1,NULL,'2012-07-11 00:00:00',NULL),(13,2,NULL,'Потягушки',NULL),(13,3,NULL,NULL,'Слаааадко поспали!'),(14,1,NULL,'2012-03-09 00:00:00',NULL),(14,2,NULL,'отдыхаем',NULL),(14,3,NULL,NULL,''),(15,1,NULL,'2012-11-29 16:44:00',NULL),(15,2,NULL,'задумались',NULL),(15,3,NULL,NULL,''),(16,1,NULL,'2012-11-30 12:09:00',NULL),(16,2,NULL,'вкусные ноги)',NULL),(16,3,NULL,NULL,''),(17,1,NULL,'2012-11-30 12:22:00',NULL),(17,2,NULL,'купака',NULL),(17,3,NULL,NULL,''),(18,1,NULL,'2012-11-30 12:29:00',NULL),(18,2,NULL,'купаемся',NULL),(18,3,NULL,NULL,''),(19,1,NULL,'2012-11-30 16:29:00',NULL),(19,2,NULL,'ня',NULL),(19,3,NULL,NULL,''),(20,1,NULL,'2012-12-02 16:33:00',NULL),(20,2,NULL,'Фотосет',NULL),(20,3,NULL,NULL,'');
/*!40000 ALTER TABLE `album_events_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_family`
--

DROP TABLE IF EXISTS `album_family`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album_family` (
  `album_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `family_role` tinyint(3) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `accepted_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`album_id`,`user_id`),
  KEY `add_time` (`add_time`),
  KEY `accepted_time` (`accepted_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_family`
--

LOCK TABLES `album_family` WRITE;
/*!40000 ALTER TABLE `album_family` DISABLE KEYS */;
INSERT INTO `album_family` VALUES (1,34,1,1352712078,0),(1,43,2,1352700970,1354182443),(3,45,1,1353076071,1353077223);
/*!40000 ALTER TABLE `album_family` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_invites`
--

DROP TABLE IF EXISTS `album_invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album_invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned NOT NULL,
  `family_role` tinyint(3) unsigned NOT NULL,
  `inviter_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `inviter_user_id` (`inviter_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_invites`
--

LOCK TABLES `album_invites` WRITE;
/*!40000 ALTER TABLE `album_invites` DISABLE KEYS */;
/*!40000 ALTER TABLE `album_invites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amazon_limit`
--

DROP TABLE IF EXISTS `amazon_limit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amazon_limit` (
  `day` int(10) unsigned NOT NULL,
  `uploaded_bytes` int(10) unsigned NOT NULL,
  `uploaded_count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amazon_limit`
--

LOCK TABLES `amazon_limit` WRITE;
/*!40000 ALTER TABLE `amazon_limit` DISABLE KEYS */;
INSERT INTO `amazon_limit` VALUES (15673,11740038,9),(15674,27342676,9),(15675,0,0),(15676,2459148,1),(15677,31931524,12),(15678,0,0);
/*!40000 ALTER TABLE `amazon_limit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `amazon_upload`
--

DROP TABLE IF EXISTS `amazon_upload`;
/*!50001 DROP VIEW IF EXISTS `amazon_upload`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `amazon_upload` (
  `day` tinyint NOT NULL,
  `uploaded_bytes` tinyint NOT NULL,
  `uploaded_count` tinyint NOT NULL,
  `Mb` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `object_type` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text NOT NULL,
  `thread` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_type` (`object_type`,`object_id`),
  KEY `thread` (`thread`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,0,1,1,43,1353326846,'Котёнок)',0),(2,0,1,1,43,1353326935,'Нельзя равнодушно пройти мимо)',0),(3,2,1,1,43,1354029312,')',2);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_likes`
--

DROP TABLE IF EXISTS `event_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_likes` (
  `event_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_likes`
--

LOCK TABLES `event_likes` WRITE;
/*!40000 ALTER TABLE `event_likes` DISABLE KEYS */;
INSERT INTO `event_likes` VALUES (1,34,1352533192),(3,45,1353076570),(6,43,1353324597),(2,43,1353324600),(1,43,1353324790),(3,43,1353324957),(4,43,1353324959),(13,43,1353404096),(14,43,1353421190);
/*!40000 ALTER TABLE `event_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned NOT NULL,
  `size_id` int(10) unsigned NOT NULL,
  `crop_method` tinyint(3) unsigned NOT NULL,
  `is_orig` tinyint(3) unsigned NOT NULL,
  `width_requested` int(10) unsigned NOT NULL,
  `height_requested` int(10) unsigned NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `server_id` int(10) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `photo_time` int(10) unsigned NOT NULL,
  `vendor` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `orientation` tinyint(3) unsigned NOT NULL,
  `software` varchar(255) NOT NULL,
  `dpi` int(10) unsigned NOT NULL,
  `uploaded` tinyint(3) unsigned NOT NULL,
  `ready` tinyint(3) unsigned NOT NULL,
  `private` tinyint(3) unsigned NOT NULL,
  `bytes` int(10) unsigned NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `error_code` int(10) unsigned NOT NULL,
  `amazon_stored_time` int(10) unsigned NOT NULL,
  `deleted_real` tinyint(3) unsigned NOT NULL,
  `private_real` tinyint(3) unsigned NOT NULL,
  `stored_local_real` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`),
  KEY `ready` (`ready`),
  KEY `is_orig` (`is_orig`),
  KEY `server_id` (`server_id`),
  KEY `amazon_stored_time` (`amazon_stored_time`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (1,1,0,1,1,0,0,4272,2848,2,1354111562,1333715792,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,5004586,0,0,1354192278,0,0,1),(2,1,1,0,0,100,100,100,100,1,1354111562,0,'','',0,'',0,0,1,0,7042,0,0,0,0,0,1),(3,1,2,1,0,500,500,500,333,1,1354111562,0,'','',0,'',0,0,1,0,58170,0,0,0,0,0,1),(4,1,3,1,0,250,250,250,166,1,1354111562,0,'','',0,'',0,0,1,0,19831,0,0,0,0,0,1),(5,5,0,1,1,0,0,4272,2848,2,1354111583,1333715792,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,5004586,0,0,1354192279,0,0,1),(6,5,1,0,0,100,100,100,100,1,1354111583,0,'','',0,'',0,0,1,0,7042,0,0,0,0,0,1),(7,5,2,1,0,500,500,500,333,1,1354111583,0,'','',0,'',0,0,1,0,58170,0,0,0,0,0,1),(8,5,3,1,0,250,250,250,166,1,1354111583,0,'','',0,'',0,0,1,0,19831,0,0,0,0,0,1),(9,9,0,1,1,0,0,4272,2848,2,1354111682,1333715792,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,5004586,0,0,1354192280,0,0,1),(10,9,1,0,0,100,100,100,100,1,1354111682,0,'','',0,'',0,0,1,0,7042,0,0,0,0,0,1),(11,9,2,1,0,500,500,500,333,1,1354111682,0,'','',0,'',0,0,1,0,58170,0,0,0,0,0,1),(12,9,3,1,0,250,250,250,166,1,1354111682,0,'','',0,'',0,0,1,0,19831,0,0,0,0,0,1),(13,13,0,1,1,0,0,4272,2848,2,1354112091,1333715792,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,5004586,0,0,1354192385,0,0,1),(14,13,1,0,0,100,100,100,100,1,1354112091,0,'','',0,'',0,0,1,0,7042,0,0,0,0,0,1),(15,13,2,1,0,500,500,500,333,1,1354112091,0,'','',0,'',0,0,1,0,58170,0,0,0,0,0,1),(16,13,3,1,0,250,250,250,166,1,1354112091,0,'','',0,'',0,0,1,0,19831,0,0,0,0,0,1),(17,17,0,1,1,0,0,612,612,1,1354115033,0,'','',1,'',102,1,1,0,95123,0,0,0,0,0,1),(18,17,30,0,0,200,200,200,200,1,1354115033,0,'','',0,'',0,0,1,0,14470,0,0,0,0,0,1),(19,17,40,1,0,450,450,450,450,1,1354115033,0,'','',0,'',0,0,1,0,62457,0,0,0,0,0,1),(20,17,50,1,0,980,980,612,612,1,1354115033,0,'','',0,'',0,0,1,0,110081,0,0,0,0,0,1),(21,21,0,1,1,0,0,612,612,1,1354115053,0,'','',1,'',102,1,1,0,95123,0,0,0,0,0,1),(22,21,30,0,0,200,200,200,200,1,1354115053,0,'','',0,'',0,0,1,0,14470,0,0,0,0,0,1),(23,21,40,1,0,450,450,450,450,1,1354115053,0,'','',0,'',0,0,1,0,62457,0,0,0,0,0,1),(24,21,50,1,0,980,980,612,612,1,1354115053,0,'','',0,'',0,0,1,0,110081,0,0,0,0,0,1),(25,25,0,1,1,0,0,4272,2848,2,1354181558,1331283648,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,4425861,0,0,1354192386,0,0,1),(26,25,30,0,0,200,200,200,200,1,1354181558,0,'','',0,'',0,0,1,0,17848,0,0,0,0,0,1),(27,25,40,1,0,450,450,450,300,1,1354181558,0,'','',0,'',0,0,1,0,49475,0,0,0,0,0,1),(28,25,50,1,0,980,980,980,653,2,1354181558,0,'','',0,'',0,0,1,0,172176,0,0,1354192783,0,0,1),(29,29,0,1,1,0,0,4272,2848,2,1354181618,1344243924,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,7648525,0,0,1354192387,0,0,1),(30,29,30,0,0,200,200,200,200,1,1354181618,0,'','',0,'',0,0,1,0,13778,0,0,0,0,0,1),(31,29,40,1,0,450,450,450,300,1,1354181618,0,'','',0,'',0,0,1,0,36040,0,0,0,0,0,1),(32,29,50,1,0,980,980,980,653,2,1354181618,0,'','',0,'',0,0,1,0,135477,0,0,1354192784,0,0,1),(33,33,0,1,1,0,0,612,612,1,1354181698,0,'','',6,'',102,1,1,0,97560,0,0,0,0,0,1),(34,33,30,0,0,200,200,200,200,1,1354181698,0,'','',0,'',0,0,1,0,18147,0,0,0,0,0,1),(35,33,40,1,0,450,450,450,450,1,1354181698,0,'','',0,'',0,0,1,0,74942,0,0,0,0,0,1),(36,33,50,1,0,980,980,612,612,1,1354181698,0,'','',0,'',0,0,1,0,102627,0,0,0,0,0,1),(37,37,0,1,1,0,0,1680,1120,2,1354181730,0,'','',6,'',281,1,1,0,498924,0,0,1354192387,0,0,1),(38,37,30,0,0,200,200,200,200,1,1354181730,0,'','',0,'',0,0,1,0,11234,0,0,0,0,0,1),(39,37,40,1,0,450,450,450,300,1,1354181730,0,'','',0,'',0,0,1,0,33706,0,0,0,0,0,1),(40,37,50,1,0,980,980,980,653,2,1354181730,0,'','',0,'',0,0,1,0,170102,0,0,1354192784,0,0,1),(41,41,0,1,1,0,0,1680,1120,2,1354181751,0,'','',6,'',281,1,1,0,388502,0,0,1354192388,0,0,1),(42,41,30,0,0,200,200,200,200,1,1354181751,0,'','',0,'',0,0,1,0,21186,0,0,0,0,0,1),(43,41,40,1,0,450,450,450,300,1,1354181751,0,'','',0,'',0,0,1,0,53730,0,0,0,0,0,1),(44,41,50,1,0,980,980,980,653,2,1354181751,0,'','',0,'',0,0,1,0,182578,0,0,1354192785,0,0,1),(45,45,0,1,1,0,0,1680,1259,2,1354181883,0,'','',6,'',281,1,1,0,888518,0,0,1354192389,0,0,1),(46,45,30,0,0,200,200,200,200,1,1354181883,0,'','',0,'',0,0,1,0,22549,0,0,0,0,0,1),(47,45,40,1,0,450,450,450,337,1,1354181883,0,'','',0,'',0,0,1,0,80551,0,0,0,0,0,1),(48,45,50,1,0,980,980,980,734,2,1354181883,0,'','',0,'',0,0,1,0,350566,0,0,1354192785,0,0,1),(49,49,0,1,1,0,0,4272,2848,2,1354181957,1321624052,'Canon','Canon EOS 450D',6,'Adobe Photoshop Lightroom 3.6 (Windows)',714,1,1,0,6690327,0,0,1354192390,0,0,1),(50,49,30,0,0,200,200,200,200,1,1354181957,0,'','',0,'',0,0,1,0,23926,0,0,0,0,0,1),(51,49,40,1,0,450,450,450,300,1,1354181957,0,'','',0,'',0,0,1,0,65863,0,0,0,0,0,1),(52,49,50,1,0,980,980,980,653,2,1354181957,0,'','',0,'',0,0,1,0,250407,0,0,1354192786,0,0,1),(53,53,0,1,1,0,0,4272,2848,2,1354182037,1337185809,'Canon','Canon EOS 450D',6,'Adobe Photoshop Lightroom 3.6 (Windows)',714,1,1,0,4755898,0,0,1354192393,0,0,1),(54,53,10,0,0,50,50,50,50,1,1354182037,0,'','',0,'',0,0,1,0,2571,0,0,0,0,0,1),(55,53,20,0,0,100,100,100,100,1,1354182037,0,'','',0,'',0,0,1,0,6218,0,0,0,0,0,1),(56,56,0,1,1,0,0,4272,2848,2,1354182443,1341822325,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,6395653,0,0,1354192447,0,0,1),(57,56,30,0,0,150,150,150,150,1,1354182443,0,'','',0,'',0,0,1,0,13593,0,0,0,0,0,1),(58,56,40,0,0,230,230,230,230,1,1354182443,0,'','',0,'',0,0,1,0,25791,0,0,0,0,0,1),(59,56,50,1,0,450,450,450,300,1,1354182443,0,'','',0,'',0,0,1,0,52612,0,0,0,0,0,1),(60,60,0,1,1,0,0,4272,2848,2,1354193062,1335779620,'Canon','Canon EOS 450D',6,'Adobe Photoshop Lightroom 3.6 (Windows)',714,1,1,0,3928458,0,0,1354193103,0,0,1),(61,60,30,0,0,200,200,200,200,1,1354193062,0,'','',0,'',0,0,1,0,16562,0,0,0,0,0,1),(62,60,40,1,0,450,450,450,300,1,1354193062,0,'','',0,'',0,0,1,0,42411,0,0,0,0,0,1),(63,60,50,1,0,980,980,980,653,2,1354193062,0,'','',0,'',0,0,1,0,154621,0,0,1354193103,0,0,1),(68,68,0,1,1,0,0,4272,2848,2,1354263472,1338054534,'Canon','Canon EOS 450D',6,'Adobe Photoshop Lightroom 3.6 (Windows)',714,1,1,0,4071919,0,0,1354263684,0,0,1),(69,68,30,0,0,200,200,200,200,1,1354263472,0,'','',0,'',0,0,1,0,15711,0,0,0,0,0,1),(70,68,40,1,0,450,450,450,300,1,1354263472,0,'','',0,'',0,0,1,0,41257,0,0,0,0,0,1),(71,68,50,1,0,980,980,980,653,2,1354263472,0,'','',0,'',0,0,1,0,151857,0,0,1354263685,0,0,1),(72,72,0,1,1,0,0,4272,2848,3,1354263776,1325703308,'Canon','Canon EOS 450D',6,'Adobe Photoshop Lightroom 3.6 (Windows)',714,1,1,1,6681979,0,0,1354263813,0,1,0),(73,72,30,0,0,200,200,200,200,3,1354263776,0,'','',0,'',0,0,1,1,20971,0,0,0,0,1,0),(74,72,40,1,0,450,450,450,300,3,1354263776,0,'','',0,'',0,0,1,1,53597,0,0,0,0,1,0),(75,72,50,1,0,980,980,980,653,3,1354263776,0,'','',0,'',0,0,1,1,188986,0,0,1354263813,0,1,0),(76,76,0,1,1,0,0,4272,2848,2,1354264195,1341840150,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,0,5783965,0,0,1354264204,0,0,1),(77,76,30,0,0,200,200,200,200,1,1354264195,0,'','',0,'',0,0,1,0,18292,0,0,0,0,0,1),(78,76,40,1,0,450,450,450,300,1,1354264195,0,'','',0,'',0,0,1,0,54487,0,0,0,0,0,1),(79,76,50,1,0,980,980,980,653,2,1354264195,0,'','',0,'',0,0,1,0,174702,0,0,1354264502,0,0,1),(80,80,0,1,1,0,0,4272,2848,3,1354265217,1331234275,'Canon','Canon EOS 450D',1,'Adobe Photoshop CS5 Windows',714,1,1,1,5011449,0,0,1354265404,0,1,1),(81,80,30,0,0,200,200,200,200,3,1354265217,0,'','',0,'',0,0,1,1,19144,0,0,0,0,1,1),(82,80,40,1,0,450,450,450,300,3,1354265217,0,'','',0,'',0,0,1,1,47881,0,0,0,0,1,1),(83,80,50,1,0,980,980,980,653,3,1354265217,0,'','',0,'',0,0,1,1,158311,0,0,1354265404,0,1,1),(84,84,0,1,1,0,0,4272,2848,2,1354278553,1320405007,'Canon','Canon EOS 450D',6,'Adobe Photoshop Lightroom 3.6 (Windows)',714,1,1,0,5119508,0,0,1354278903,0,0,1),(85,84,30,0,0,200,200,200,200,1,1354278553,0,'','',0,'',0,0,1,0,17606,0,0,0,0,0,1),(86,84,40,1,0,450,450,450,300,1,1354278553,0,'','',0,'',0,0,1,0,51232,0,0,0,0,0,1),(87,84,50,1,0,980,980,980,653,1,1354278553,0,'','',0,'',0,0,1,0,178538,0,0,0,0,0,1),(88,88,0,1,1,0,0,3264,2448,2,1354451670,0,'','',6,'',545,1,1,0,2459148,1,0,1354451703,0,0,1),(89,88,30,0,0,200,200,200,200,1,1354451670,0,'','',0,'',0,0,1,0,26547,1,0,0,0,0,1),(90,88,40,1,0,450,450,450,337,1,1354451670,0,'','',0,'',0,0,1,0,90500,1,0,0,0,0,1),(91,88,50,1,0,980,980,980,735,1,1354451670,0,'','',0,'',0,0,1,0,348754,1,0,0,0,0,1),(92,92,0,1,1,0,0,3264,2448,2,1354517531,0,'','',6,'',545,1,1,0,2459148,0,0,1354517702,0,0,1),(93,92,1,0,0,100,100,100,100,1,1354517531,0,'','',0,'',0,0,1,0,7844,0,0,0,0,0,1),(94,92,2,1,0,500,500,500,375,1,1354517531,0,'','',0,'',0,0,1,0,109341,0,0,0,0,0,1),(95,92,3,1,0,250,250,250,187,1,1354517531,0,'','',0,'',0,0,1,0,30508,0,0,0,0,0,1),(97,97,0,1,1,0,0,3264,2448,2,1354517626,0,'','',1,'Microsoft Windows Photo Viewer 6.1.7600.16385',545,1,1,0,2668158,0,0,1354517704,0,0,1),(98,97,1,0,0,100,100,100,100,1,1354517626,0,'','',0,'',0,0,1,0,7049,0,0,0,0,0,1),(99,97,2,1,0,500,500,500,375,1,1354517626,0,'','',0,'',0,0,1,0,98507,0,0,0,0,0,1),(100,97,3,1,0,250,250,250,187,1,1354517626,0,'','',0,'',0,0,1,0,27049,0,0,0,0,0,1),(101,101,0,1,1,0,0,3264,2448,2,1354517624,0,'','',6,'',545,1,1,0,2459148,0,0,1354517703,0,0,1),(102,101,1,0,0,100,100,100,100,1,1354517624,0,'','',0,'',0,0,1,0,7868,0,0,0,0,0,1),(103,101,2,1,0,500,500,500,375,1,1354517624,0,'','',0,'',0,0,1,0,106464,0,0,0,0,0,1),(104,101,3,1,0,250,250,250,187,1,1354517624,0,'','',0,'',0,0,1,0,31218,0,0,0,0,0,1),(105,105,0,1,1,0,0,3264,2448,2,1354517885,0,'','',6,'',545,1,1,0,2459148,1,0,1354518004,0,0,1),(106,105,30,0,0,200,200,200,200,1,1354517885,0,'','',0,'',0,0,1,0,26495,1,0,0,0,0,1),(107,105,40,1,0,450,450,450,337,1,1354517885,0,'','',0,'',0,0,1,0,88751,1,0,0,0,0,1),(108,105,50,1,0,980,980,980,735,1,1354517885,0,'','',0,'',0,0,1,0,329013,1,0,0,0,0,1),(110,110,0,1,1,0,0,2448,3264,2,1354518097,0,'','',6,'',545,1,1,0,2459148,0,0,1354518305,0,0,1),(111,110,30,0,0,200,200,200,200,1,1354518097,0,'','',0,'',0,0,1,0,26495,0,0,0,0,0,1),(112,110,40,1,0,450,450,337,450,1,1354518097,0,'','',0,'',0,0,1,0,90237,0,0,0,0,0,1),(113,110,50,1,0,980,980,735,980,1,1354518097,0,'','',0,'',0,0,1,0,347628,0,0,0,0,0,1),(114,114,0,1,1,0,0,2448,3264,2,1354518794,0,'','',6,'',545,1,1,0,2459148,0,0,1354518903,0,0,1),(115,114,1,0,0,100,100,100,100,1,1354518794,0,'','',0,'',0,0,1,0,7868,0,0,0,0,0,1),(116,114,2,1,0,500,500,375,500,1,1354518794,0,'','',0,'',0,0,1,0,108795,0,0,0,0,0,1),(117,114,3,1,0,250,250,187,250,1,1354518794,0,'','',0,'',0,0,1,0,30546,0,0,0,0,0,1),(118,118,0,1,1,0,0,2448,3264,2,1354519063,0,'','',6,'',545,1,1,0,2459148,0,0,1354519203,0,0,1),(119,118,1,0,0,100,100,100,100,1,1354519063,0,'','',0,'',0,0,1,0,7868,0,0,0,0,0,1),(120,118,2,1,0,500,500,375,500,1,1354519063,0,'','',0,'',0,0,1,0,108795,0,0,0,0,0,1),(121,118,3,1,0,250,250,187,250,1,1354519063,0,'','',0,'',0,0,1,0,30546,0,0,0,0,0,1),(122,122,0,1,1,0,0,2448,3264,2,1354519098,0,'','',6,'',545,1,1,0,2459148,0,0,1354519204,0,0,1),(123,122,1,0,0,100,100,100,100,1,1354519098,0,'','',0,'',0,0,1,0,7868,0,0,0,0,0,1),(124,122,2,1,0,500,500,375,500,1,1354519098,0,'','',0,'',0,0,1,0,108795,0,0,0,0,0,1),(125,122,3,1,0,250,250,187,250,1,1354519098,0,'','',0,'',0,0,1,0,30546,0,0,0,0,0,1),(127,127,0,1,1,0,0,2448,3264,2,1354519424,0,'','',6,'',545,1,1,0,2459148,0,0,1354519503,0,0,1),(128,127,1,0,0,100,100,100,100,1,1354519424,0,'','',0,'',0,0,1,0,7868,0,0,0,0,0,1),(129,127,2,1,0,500,500,375,500,1,1354519424,0,'','',0,'',0,0,1,0,108795,0,0,0,0,0,1),(130,127,3,1,0,250,250,187,250,1,1354519424,0,'','',0,'',0,0,1,0,30546,0,0,0,0,0,1),(136,136,0,1,1,0,0,2448,3264,2,1354520859,0,'','',6,'',545,1,1,0,2459148,0,0,1354521004,0,0,1),(137,136,1,0,0,100,100,100,100,1,1354520859,0,'','',0,'',0,0,1,0,7868,0,0,0,0,0,1),(138,136,2,1,0,500,500,375,500,1,1354520859,0,'','',0,'',0,0,1,0,108795,0,0,0,0,0,1),(139,136,3,1,0,250,250,187,250,1,1354520859,0,'','',0,'',0,0,1,0,30546,0,0,0,0,0,1),(140,140,0,1,1,0,0,2448,3264,2,1354520872,0,'','',0,'',545,1,1,0,4462876,0,0,1354521005,0,0,1),(141,140,1,0,0,100,100,100,100,1,1354520872,0,'','',0,'',0,0,1,0,7868,0,0,0,0,0,1),(142,140,2,1,0,500,500,375,500,1,1354520872,0,'','',0,'',0,0,1,0,108795,0,0,0,0,0,1),(143,140,3,1,0,250,250,187,250,1,1354520872,0,'','',0,'',0,0,1,0,30546,0,0,0,0,0,1),(144,144,0,1,1,0,0,3264,2448,2,1354521947,0,'','',1,'Microsoft Windows Photo Viewer 6.1.7600.16385',545,1,1,0,2668158,0,0,1354522203,0,0,1),(145,144,1,0,0,100,100,100,100,1,1354521947,0,'','',0,'',0,0,1,0,7049,0,0,0,0,0,1),(146,144,2,1,0,500,500,500,375,1,1354521947,0,'','',0,'',0,0,1,0,98507,0,0,0,0,0,1),(147,144,3,1,0,250,250,250,187,1,1354521947,0,'','',0,'',0,0,1,0,27049,0,0,0,0,0,1),(148,148,0,1,1,0,0,450,534,1,1354521952,0,'','',1,'',89,1,1,0,81832,0,0,0,0,0,1),(149,148,1,0,0,100,100,100,100,1,1354521952,0,'','',0,'',0,0,1,0,7944,0,0,0,0,0,1),(150,148,2,1,0,500,500,421,500,1,1354521952,0,'','',0,'',0,0,1,0,88295,0,0,0,0,0,1),(151,148,3,1,0,250,250,210,250,1,1354521952,0,'','',0,'',0,0,1,0,30644,0,0,0,0,0,1),(152,152,0,1,1,0,0,26,54,1,1354521967,0,'','',0,'',9,1,1,0,870,0,0,0,0,0,1),(153,152,1,0,0,100,100,26,54,1,1354521967,0,'','',0,'',0,0,1,0,1501,0,0,0,0,0,1),(154,152,2,1,0,500,500,26,54,1,1354521967,0,'','',0,'',0,0,1,0,1501,0,0,0,0,0,1),(155,152,3,1,0,250,250,26,54,1,1354521967,0,'','',0,'',0,0,1,0,1501,0,0,0,0,0,1),(156,156,0,1,1,0,0,50,50,1,1354621584,0,'','',0,'',8,1,1,0,2649,0,0,0,0,0,1),(157,156,10,0,0,50,50,50,50,1,1354621584,0,'','',0,'',0,0,1,0,2174,0,0,0,0,0,1),(158,156,20,0,0,100,100,50,50,1,1354621584,0,'','',0,'',0,0,1,0,2174,0,0,0,0,0,1),(159,159,0,1,1,0,0,100,100,1,1354621610,0,'','',0,'',17,1,1,0,6690,0,0,0,0,0,1),(160,159,10,0,0,50,50,50,50,1,1354621610,0,'','',0,'',0,0,1,0,2512,0,0,0,0,0,1),(161,159,20,0,0,100,100,100,100,1,1354621610,0,'','',0,'',0,0,1,0,7137,0,0,0,0,0,1),(162,162,0,1,1,0,0,128,128,1,1354626653,0,'','',0,'',21,1,1,0,5252,0,0,0,0,0,1),(163,162,10,0,0,50,50,50,50,1,1354626653,0,'','',0,'',0,0,1,0,2475,0,0,0,0,0,1),(164,162,20,0,0,100,100,100,100,1,1354626653,0,'','',0,'',0,0,1,0,6819,0,0,0,0,0,1);
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_event_templates`
--

DROP TABLE IF EXISTS `lib_event_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_event_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_event_templates`
--

LOCK TABLES `lib_event_templates` WRITE;
/*!40000 ALTER TABLE `lib_event_templates` DISABLE KEYS */;
INSERT INTO `lib_event_templates` VALUES (1,'Событие'),(2,'Рождение'),(3,'День рождения');
/*!40000 ALTER TABLE `lib_event_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_event_templates_fields`
--

DROP TABLE IF EXISTS `lib_event_templates_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_event_templates_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `pos` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `important` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`field_id`),
  UNIQUE KEY `template_id_2` (`template_id`,`type`),
  KEY `pos` (`pos`),
  KEY `template_id` (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_event_templates_fields`
--

LOCK TABLES `lib_event_templates_fields` WRITE;
/*!40000 ALTER TABLE `lib_event_templates_fields` DISABLE KEYS */;
INSERT INTO `lib_event_templates_fields` VALUES (1,1,1,1,1,'Время'),(2,1,2,2,1,'Заголовок'),(3,1,3,3,0,'Описание'),(4,1,4,4,0,'Фотография'),(5,2,5,1,1,'Дата и время рождения'),(6,2,6,3,1,'Как это было?'),(7,2,7,4,0,'Фотография'),(8,2,8,5,0,'Как назвали?'),(9,2,9,6,1,'Вес счастья'),(10,2,10,7,1,'Рост счастья'),(11,2,11,8,0,'Цвет глазок радости'),(12,3,12,1,1,'Дата'),(13,3,13,3,1,'Опишите день'),(14,3,14,4,0,'Фотография'),(16,3,16,7,0,'Наш рост'),(17,3,17,6,0,'Наш вес'),(18,3,1,2,0,'Заголовок'),(19,2,1,2,0,'Заголовок');
/*!40000 ALTER TABLE `lib_event_templates_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_event_templates_fields_types`
--

DROP TABLE IF EXISTS `lib_event_templates_fields_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_event_templates_fields_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_name` varchar(22) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_event_templates_fields_types`
--

LOCK TABLES `lib_event_templates_fields_types` WRITE;
/*!40000 ALTER TABLE `lib_event_templates_fields_types` DISABLE KEYS */;
INSERT INTO `lib_event_templates_fields_types` VALUES (1,'eventTime','eventTime'),(2,'eventTitle','eventTitle'),(3,'eventDescription','description'),(4,'eventPhoto','photo'),(5,'имя ребёнка','name'),(6,'вес','weight'),(7,'рост','height'),(8,'цвет глаз','eyecolor');
/*!40000 ALTER TABLE `lib_event_templates_fields_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_events`
--

DROP TABLE IF EXISTS `lib_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `male` tinyint(4) NOT NULL,
  `age_start_days` int(10) unsigned NOT NULL,
  `age_end_days` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  `multiple` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `age_start_days` (`age_start_days`,`age_end_days`),
  KEY `multiple` (`multiple`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_events`
--

LOCK TABLES `lib_events` WRITE;
/*!40000 ALTER TABLE `lib_events` DISABLE KEYS */;
INSERT INTO `lib_events` VALUES (1,'Рождение',0,0,31,'Этого события ждали долгие месяцы, и вот - это произошло! С днём рождения, малыш!',2,0),(2,'Выписка',0,0,31,'Наконец-то домой! Показываться родственникам и обживаться на новом месте.',1,0),(5,'Первые самостоятельные шаги',0,200,600,'За ручку мы уже находились, теперь - сами!',1,1),(6,'Держим головку',0,31,120,'Охх сколько всего интересного вокруг! Только успевай шеей крутить!',1,1),(7,'Первый день рождения',0,363,600,'Мне уже целый год! Сколько всего интересного за год успело произойти!',3,1),(8,'Первый раз сели',0,40,200,'Лежать надоело, сидеть - весело!',1,1),(9,'Первый раз засмеялись',0,55,150,'Рассмешили так рассмешили) Теперь буду смеяться без остановки!',1,1),(10,'Первое кормление',0,90,365,'Что это? Совсем не похоже на мамино молоко!',1,1),(11,'Стоим сами',0,210,400,'Сидеть, лежать... А я вот постою! А вам слабо?',1,1),(12,'Первый зуб',0,60,380,'Всё, вылез! Первый зубик, привет!',1,1),(13,'Первая игрушка',0,0,63,'Моя самая первая в жизни игрушка!',1,1),(14,'Я и мама',0,0,50,'Первая фотография с мамой',1,1),(15,'Я и папа',0,0,60,'Первая фотография с папой',1,1),(16,'Я и семья',0,5,90,'Первая семейная фотография',1,1),(17,'Танцую',0,100,450,'Вот как я танцую, завидуйте все!',1,1),(18,'Первый поход в поликлинику',0,30,180,'А там куча деток, и все с мамами! Вот где настоящее веселье!',1,1),(19,'Второй день рождения',0,720,800,'Мне уже два года! Вот как мы празднуем!',3,1),(20,'Моё первое слово',0,365,728,'Вот теперь, мама и папа, держитесь! Скоро я выучу еще пару слов и делать вид, что вы меня не понимаете, будет сложнее!',1,1),(21,'Первое узи',0,0,25,'Первый раз мама не только почуствует, но и увидит малыша',1,1),(22,'Последнее узи',0,0,26,'Последний раз мы видим малышку на экране. В следующий раз встретимся с глазу на глаз!',1,1),(23,'Первое молочко',0,0,14,'Первый раз пробуем мамино молочко',1,1),(24,'Первое купание',0,0,30,'Как хорошо, когда ванна для тебя - целое море, а родительские руки - корабль!',1,0);
/*!40000 ALTER TABLE `lib_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publications`
--

DROP TABLE IF EXISTS `publications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `age_start` int(11) NOT NULL,
  `age_end` int(11) NOT NULL,
  `sex` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text_short` text NOT NULL,
  `text` longtext NOT NULL,
  `keywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publications`
--

LOCK TABLES `publications` WRITE;
/*!40000 ALTER TABLE `publications` DISABLE KEYS */;
INSERT INTO `publications` VALUES (1,2,0,365,0,1350390101,0,'Вес и рост от новорожденного до годовалого ребёнка','Каким должен быть рост и вес малыша, какой рост является нормой, какой вес является нормой, отличаются ли средние параметры девочки от параметров мальчика?','Рост ребенка от 0 до 12 месяцев\r\n<table>\r\n    <tr>\r\n        <td>Возраст</td>\r\n        <td>Мальчики</td>\r\n        <td>Девочки</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Новорожденный</td>\r\n        <td>50</td>\r\n        <td>50</td>\r\n    </tr>\r\n    <tr>\r\n        <td>1</td>\r\n        <td>54</td>\r\n        <td>54</td>\r\n    </tr>\r\n    <tr>\r\n        <td>2</td>\r\n        <td>57</td>\r\n        <td>57</td>\r\n    </tr>\r\n    <tr>\r\n        <td>3</td>\r\n        <td>60</td>\r\n        <td>60</td>\r\n    </tr>\r\n    <tr>\r\n        <td>4</td>\r\n        <td>63</td>\r\n        <td>62</td>\r\n    </tr>\r\n    <tr>\r\n        <td>5</td>\r\n        <td>66</td>\r\n        <td>64</td>\r\n    </tr>\r\n    <tr>\r\n        <td>6</td>\r\n        <td>68</td>\r\n        <td>66</td>\r\n    </tr>\r\n    <tr>\r\n        <td>7</td>\r\n        <td>70</td>\r\n        <td>68</td>\r\n    </tr>\r\n    <tr>\r\n        <td>8</td>\r\n        <td>72</td>\r\n        <td>70</td>\r\n    </tr>\r\n    <tr>\r\n        <td>9</td>\r\n        <td>73</td>\r\n        <td>72</td>\r\n    </tr>\r\n    <tr>\r\n        <td>10</td>\r\n        <td>74</td>\r\n        <td>73</td>\r\n    </tr>\r\n    <tr>\r\n        <td>11</td>\r\n        <td>75</td>\r\n        <td>74</td>\r\n    </tr>\r\n    <tr>\r\n        <td>12</td>\r\n        <td>76</td>\r\n        <td>75</td>\r\n    </tr>\r\n</table>','параметры малыша, рост ребёнка, рост новорожденного'),(3,2,0,365,0,1350399466,0,'Желтуха у новорожденных ','У детей первых дней жизни бывает, что кожа и слизистые оболочки приобретают желтоватый оттенок. Это происходит из-за нарушения билирубинового обмена, вследствие незрелости ферментных систем печени.','У детей первых дней жизни бывает, что кожа и слизистые оболочки приобретают желтоватый оттенок. Это происходит из-за нарушения билирубинового обмена, вследствие незрелости ферментных систем печени. Билирубин - это вещество из группы желчных пигментов. Он является продуктом обмена гемоглобина, входящего в состав эритроцитов. После их разрушения гемоглобин проходит ряд превращений, в конце которых образуется билирубин. С током крови он попадает в печень, где происходит его обезвреживание, за счёт присоединения глюкуроновой кислоты. Так образуется прямой (связанный) билирубин,он мало токсичен,и легко выводится с мочой,придавая ей характерный желтый цвет. При некоторых состояниях билирубин не обезвреживается, он называется непрямым, и может повреждать органы и ткани, особенно головной мозг.\r\n<br>\r\nЖелтуха новорожденных возникает при сильном кровоизлиянии во время родов, при острых и хронических инфекциях, при несовместимости крови матери и плода, при механической задержке желчи, при врожденной недостаточности фермента глюкозо-6-фосфатдегидрогеназы, который учувствует в обмене билирубина. Желтуха новорожденных возникает обычно, когда вы находитесь в роддоме. Врачи замечают это и делают все необходимые анализы. Анализы покажут содержание билирубина в крови. Его показатели зависят от времени,прошедшего со дня родов,и в динамике должны снижаться. Основным методом лечения является фототерапия,она способствует переходу непрямого билирубина в прямой. Так же возможно применение таких лекарственных препаратов как фенобарбитал, вит Е, сорбенты и др. Но только по назначению врача. В крайних случаях проводят переливания крови ( только при патологических желтухах,чаще при резус-конфликте). В настоящее время допускается сохранение лёгкой желтухи или иктеричности склер (пожелтение склер) до 3-4 нед. При этом необходим контроль билирубина,особенно при нарастании желтухи или нарушениях в поведении малыша. Держите ребенка на свежем воздухе и под лучами солнца, почаще прикладывайте к груди, если ребёнок получает смесь, обязательно допаивайте!\r\n<br>\r\nПризнаки желтухи новорожденных: желтый цвет кожи и слизистых оболочек. Если желтушный цвет кожи у новорожденного долго не проходит, необходимо провести всестороннее обследование ребенка, чтобы выяснить причину заболевания. Физиологическая желтуха новорожденных обычно не требует лечения. В других случаях лечение проводится в зависимости от причин.\r\n<br>\r\nВстречаются несколько видов желтухи:\r\n<ul>\r\n   <li>Конъюгационная. Это следствие нарушения процессов превращения непрямого билирубина в прямой.\r\n    <li>Гемолитическая. Возникает вследствие интенсивного распада эритроцитов.\r\n    <li>Механическая (обтурационная ). Происходит из-за механического препятствия оттоку желчи в двенадцатиперстную кишку.\r\n    <li>Печеночная (паренхиматозная). Это поражение тканей печени, которая происходит при гепатите.\r\n    <li>Физиологическая (желтуха новорожденных) - это так называемая, транзиторная (временная) коньюгационная желтуха. Возникает из-за того, что в эритроцитах плода есть особый гемоглобин, F - фетальный. После рождения эритроциты разрушаются. \r\n</ul>\r\n<br/>\r\nИмеющийся у новорожденных дефицит белка, который отвечает за перенос билирубина, способствует его накоплению. Кроме того, печень новорожденного обладает низкой выделительной способностью.\r\n<br/>\r\nОбычно желтуха новорожденных исчезает через 2-3 недели после появления, не причиняя вреда малышу. В крайнем случае, когда желтуха явно выражена, иногда используют внутривенное вливание растворов глюкозы, аскорбиновую кислоту, фенобарбитал, желчегонные средства, используют фототерапию. Чаще желтуха новорожденных встречается у недоношенных детишек, она более длительна и выражена. То есть выраженность желтухи зависит от доношенности плода и болезней матери во время беременности.\r\n<br/>\r\nВстречается желтуха, вызванная молоком матери. Недуг появляется через неделю после рождения малыша, а исчезает к 3-4 недели. Считается, что причиной такой желтухи является содержание определенного вида жирных кислот в молоке. Эти вещества подавляют функции печени, тормозя превращения непрямого билирубина в прямой. При такой желтухе ребенка следует чаще кормить грудным молоком, чтобы организм со стулом быстрее выделял билирубин.\r\n<br/>\r\nУ новорожденных желтуха может развиться при гипотиреозе ( снижение функциональной активности щитовидной железы ). Кроме желтухи, гипотиреоз характеризуется отечностью, сухостью волос, грубостью голоса, повышением холестерина, задержка окостенения. Такая желтуха возникает на 2-3 день после рождения и угасает к 3 - 12 недели, но может продлиться 4 - 5 месяцев. Лечится гипотиреоз эндокринологом.\r\n<br/>\r\nПричиной обтурационной (механической) желтухи у новорожденного может стать непроходимость печёночных или желчного протоков.Для такого вида желтухи характерен жёлтый с зеленоватым оттенком цвет кожи,зуд,потемнение мочи и осветление кала.\r\n<br/>\r\nГемолитическая желтуха появляется, в основном, вследствие несовместимости крови матери и ребенка по группе или резус - фактора. Характеризуется болезнь повышенным разрушением эритроцитов. Кроме этого, причинами могут послужить нарушение структур гемоглобина, нарушение формы и структуры эритроцитов и дефицит ферментативных систем эритроцитов.\r\n<br/>\r\nПоявление желтухи в первые часы или сутки после рождения является плохим прогностическим признаком,и говорит о высоких цифрах билирубина, при которых может возникнуть так называемая \"ядерная желтуха\" или \"билирубиновая энцефалопатия\" . Это состояние характерезуется пропитыванием билирубином серого вещества головного мозга. Признаки заболевания - сонливость, плохое сосание, изменение рефлексов, монотоный слабый крик, постанование. Осложнения - глухота, параличи, умственная отсталость.\r\n<br/>\r\nТяжелые формы гемолитической желтухи лечатся переливанием крови. Так что будьте внимательны к своим деткам, к их здоровью и состоянию. ','желтый младенец, желтуха у новорожденного'),(4,2,0,60,0,0,0,'Что брать с собой в роддом?','<p>Многих беременных интересует вопрос,- что же нужно брать с собой в роддом?</p>\r\n<p>Ниже представлен примерный список вещей, которые могут вам понадобиться. Рекомендуем вам заранее (после 25 недели) сложить необходимые вещи в отдельные пакеты (как правило, сумку с собой брать не разрешают) на всякий непредвиденный случай.</p>','Первая группа: документы в роддоме<br>\r\n<br>\r\nПаспорт<br>\r\nПолис медицинского страхования и его ксерокопия (после оформления в больницу лучше отдать паспорт и полис родственникам, так как эти документы вам больше не понадобятся).<br>\r\nДоговор контракта на роды (если женщина заключала договор).<br>\r\nОбменная карта.<br>\r\nРодовой сертификат (выдается в женской консультации на сроке 30 недель беременности, а при многоплодной беременности – на 28-й неделе).<br>\r\nКсерокопия больничного листа (декрета).<br>\r\n<br>\r\nВторая группа: вещи, которые пригодятся во время родов<br>\r\n<br>\r\nВещи желательно складывать в пакеты, иные сумки брать в роддом не разрешается.<br>\r\n<br>\r\nМобильный телефон (с достаточным количеством денег на счете!) и зарядка для него.<br>\r\nХалат<br>\r\nНоски – 2 пары, желательно теплые, но не шерстяные (во время родов часто знобит, пригодятся и после родов)<br>\r\nТапочки (по требованиям некоторых роддомов должны быть моющимися. Если нет, то вторую пару для душа).<br>\r\nПитье во время родов. Хорошо, если это будет минеральная вода без газов или специальный травяной чай для родов. Травяной чай для родов удобно брать в термосе. Если разрешат в роддоме, можно взять легкую еду.<br>\r\nНебольшое махровое полотенце (во время родов можно намочить и приложить к лицу)<br>\r\nГигиеническая помада! (губы пересохнут во время родов)<br>\r\nЗаколки для волос, нетугие резинки (в родах все снять)<br>\r\nПротивоварикозные чулки или эластичные бинты для родов (если у вас есть варикоз).<br>\r\nРенни от изжоги<br>\r\nЕсли разрешат в роддоме, можно взять надувной мяч больших размеров (80 на 90 см); теннисный мячик; плеер, диски с приятной ритмичной музыкой; фотоаппарат, видеокамеру (дети так быстро растут, что не успеваешь оглянуться. Фотографии из роддома станут лично для вас и ребенка бесценными. Каждый месяц ребенок меняется – так пусть останется память).<br>\r\nСменную обувь мужу, если он будет с вами<br>\r\n<br>\r\nТретья группа: вещи, которые могут понадобиться после родов<br>\r\n<br>\r\nНочные рубашки с завязками спереди (2-3 штуки)<br>\r\nУпаковка одноразовых трусов для роддома (5 — 7 штук, в настоящее время в продаже есть как импортного, так и российского производства) или несколько штук хлопчатобумажных трусов (забудьте о красоте: всевозможных стрингах, кружевах и подобном. Заранее купите в магазине натуральное, добротное и широкое белье.)<br>\r\n2 упаковки толстых прокладок (в дальнейшем по мере необходимости можно попросить передать еще) с высокой гигроскопичностью на первые дни и ни в коем случае не берите с поверхностью – сеточка (можно купить специальные после родов, а можно фирмы Тена)<br>\r\nСредства личной гигиены и косметика (для чистоты тела: шампунь, гель для душа, мочалка, жидкое мыло для рук (оно не требует мыльницы, чистоплотно и удобно при транспортировке); принадлежности по уходу за полостью рта: зубная щетка и паста; расческа; крем для лица и рук); туалетная бумага (самая мягкая); бумажные полотенца; влажные салфетки.<br>\r\nПолотенце для душа<br>\r\nМогут пригодиться сменные прокладки для грудных желез, впитывающие молоко, если соски слабые. Через несколько дней после родов у вас заметно прибавится молока, причем оно будет вырабатываться в больших количествах. Просыпаться утром в мокрой и липкой ночной рубашке не самое приятное, да и малышом заниматься надо. Как выход, специалисты выпустили специальные прокладки для груди, которые впитывают молоко. Предпочтение отдайте тем, что стоят дороже. Качественные вкладыши для бюстгальтера стоят немало – вытекающее молоко они преобразуют в гель, но зато лишают маму хлопот, лишних неприятных ощущений вне кормления и мокрой одежды. Менять пару вкладышей вам придется каждые 2-4 часа.<br>\r\nКрем для сосков (в первые дни начала кормления соски очень чувствительные, поэтому во избежание трещин желательно пользоваться таким кремом – Пурелан либо Avent). Если эти роды у вас первые, то вы еще не знаете, с какой силой умеет малыш сосать грудь. Чтобы насытиться и спокойно заснуть, поверьте, он сделает все возможное для того, чтобы высосать больше молока. И как результат – у каждой женщины появляются трещины на сосках. С трещинами приходит боль при кормлении, довольно сильная. Заранее купите качественный крем для профилактики появления трещин на груди.<br>\r\nБюстгальтеры для кормления, в которых легко освободить грудь (с застежкой впереди). Вам следует заранее купить несколько специальных бюстгальтеров для кормления. Выбирать их следует на последних месяцах беременности – к этому времени грудь значительно увеличится. Мысленно добавив объем молока для своей груди, купите два или три бюстгальтера. Они просто незаменимы для организации удобного кормления.<br>\r\nБумага и ручка, чтобы писать записки или дневник.<br>\r\nВозьмите шпаргалки, которые вы писали на практических занятиявх. Они обязательно вам поднимут настроение, придадут уверенности в себе согреют и зарядят новыми силами.<br>\r\nТарелка, чашка, ложка<br>\r\nБандаж послеродовой (Для тех кому светит кесарево обязательно возьмите бандаж, ОЧЕНЬ нужная вещь, ещё трусы берите с заниженной или с завышеной талией, только не классические, очень неприятно когда они попадают на шов).<br>\r\nЗаранее познакомьтесь со списком продуктов, которые можно приносить в роддом после родов. Вспомните о правилах питания во время кормления грудью. Обязательно учтите сезон: весна, лето, зима, осень. Поведайте это папе или тому человеку, который будет приходить к вам в роддом. Будьте особенно внимательны к продуктам первые два три месяца после родов, если кормите малыша грудью и в летнее время!<br>\r\nПолиэтиленовые мешочки для мусора.<br>\r\nИнтересная книжка или журнал, чтобы «скоротать» время. Расслабиться, отвлечься, отдохнуть – вы будет много лежать, набираясь сил. Кроме того, из специальных журналов можно узнать много полезной информации по уходу за малышом.<br>\r\nЛактогонный чай<br>\r\n<br>\r\nДеньги в некрупных купюрах (сейчас во многих роддомах есть кафе или маленькие магазинчики, где можно что-нибудь купить)<br>\r\nМолокоотсос (не покупайте сразу, может быть и не пригодится)<br>\r\n<br>\r\nЧетвертая группа: приданое для малыша в роддоме<br>\r\n<br>\r\n<br>\r\nМалышу тоже нужна одежда. Уточните, что потребуется принести для ухода за ребенком, в выбранный вами роддом. Что приготовить для малыша на выписку, когда вы отправитесь домой. Не забудьте, что всю одежду для малыша предварительно необходимо выстирать и затем выгладить.<br>\r\n<br>\r\nПакетик с одеждой для новорожденного, который вы возьмете в родильный зал (конечно, если это практикуется в выбранном вами роддоме), лучше собрать отдельно. Туда рекомендую положить: одноразовый подгузник, 2 пеленки, боди или распашонку, тоненький комбинезон, носочки, шапочку или чепчик, одеяльце.<br>\r\n<br>\r\nВ некоторых роддомах могут запретить приносить с собой какие бы то ни было детские вещи; где-то, возможно, попросят принести только пачку одноразовых подгузников; а бывают хозрасчетные родильные отделения, где детки так красиво одеты, что и переодевать их не захочется. Так что этот список наверняка пригодится не всем — он адресован мамам, желающим (и имеющим возможность) использовать в роддоме принесенные с собой детские вещи.<br>\r\n<br>\r\nУпаковку памперсов для ребенка – Pampers/Huggis – дышащие для новорожденных с надписью New Born от 3 до 5 кг;<br>\r\n2-4 нижних распашонок или кофточек, боди (обязательно хлопчатобумажную, со швами наружу);<br>\r\n2 теплых (фланелевых, байковых) кофточек с длинными рукавами;<br>\r\n2 пары ползунков, застегивающиеся на плечах, размер 56 от 3 до 3,5 кг, 58 – от 3,5 до 4 кг, 62 размер после 4-х кг;<br>\r\n2 теплых из фланели/байки пеленок;<br>\r\nАнтицарапательные рукавички и носочки;<br>\r\nШапочка без завязок + чепчик с завязками;<br>\r\nОдеялко байковое либо другое без пододеяльника 90x90;<br>\r\nДиски для снятия макияжа, чтобы проирать личико и смазывать маслицем малыша;<br>\r\nМасло для протиирания складочек, крем под подгузник Bubchen (в некоторых роддомах просят принести); пенка «Джонсон» с дозатором (удобно, когда неопытными руками держишь младенца, да и дома потом пригодится) для подмывания малыша (в палате была, но закончилась, пришлось попросить, чтобы муж привез);<br>\r\n<br>\r\n<br>\r\nХочется предупредить: в качестве обязательной процедуры, в роддоме будут настойчиво предлагать докармливать ребенка из бутылочки! Отнеситесь к этому серьезно и внимательно. Практика докармливания смесями, допаивания кипяченой водой или раствором глюкозы повышает риск внесения ребенку инфекции. Использование молочных смесей в первые дни после рождения может привести к развитию дисбактериоза, к диатезу или аллергическим реакциям. Кроме этого, при сосании из соски, у ребенка работают мышцы щек, а при сосании материнской груди задействованы мышцы языка. При раннем знакомстве с бутылочкой (сосание из соски) у малыша формируется неправильный способ сосания. Из-за этого, дети рано отказываются от груди, у них плохо развиваются мышцы языка и нередко возникают проблемы с речью. А у матери из-за этого, может значительно снизиться лактация. Вы имеете полное право, также настойчиво, как и предлагают, отказаться от докармливания и допаивания младенца! Будьте ответственны при принятии решения.<br>\r\n<br>\r\nОдним из положений Декларации ВОЗ/ЮНИСЕФ по поддержке грудного вскармливания младенцев, является требование ничего не давать новорожденным в качестве докорма или питья. За исключением жизненно важных медицинских показаний. Это положение часто нарушается.<br>\r\n<br>\r\nПри выписке НЕ ЗАБЫТЬ!!!<br>\r\n<br>\r\nзабрать обменную карта (на себя и на ребенка) из роддома;<br>\r\nзабрать из роддома справку в ЗАГС для регистрации малыша;<br>\r\nлист с рекомендациями и заключение о здоровье ребенка в роддоме (обязательно уточните, какие процедуры проводились, какие лекарственные препараты вводились ребенку);<br>\r\nсообщите в детскую поликлинику о рождении ребенка и пригласите патронажную сестру.<br>\r\nВещи для выписки из родильного дома:<br>\r\n<br>\r\nЧтобы с почетом и гордостью покинуть роддом, вам понадобятся некоторые вещи (их не можно не брать сразу, их позже вам сможет принести муж).<br>\r\n<br>\r\nВ настоящее время врачи-педиатры не рекомендуют пеленать ребенка. Считается, что это плохо влияет на его моторное развитие. Поэтому в последние годы у новорожденных появилась своя мода в одежде, а традиционные пеленки-одеяла несколько отошли на второй план. Итак, во что же можно одеть новорожденного ребенка? Безусловно, красивый костюмчик и конверт для новорожденных. Все остальное у вас будет.<br>\r\n<br>\r\nДля вас: декоративная косметика – вы будете позировать перед камерами в обязательном порядке! – вам следует быть на высоте. Нарядная одежда, на размер больше той, которую вы носили до беременности. Идеальный летний вариант – платье с завышенной талией. Ваши размеры одежды и обуви после родов могут несколько отличаться от тех, что вы носили до беременности. Поэтому предусмотрите для выписки из родильного дома свободную одежду, в который вы будете чувствовать себя комфортно.<br>\r\n<br>\r\nКроме того, подумайте и о том, как вы повезете ребенка. К сожалению, в настоящее время Россия остается страной, в которой ребенка в автомобиле можно перевозить без специального кресла. Вы можете позаботиться о дополнительной безопасности вашего малыша, приобретя для него автомобильное кресло, рассчитанное для детей с рождения и до 1 — 1,5 лет или специальную люльку для перевозки детей в автомобиле.<br>\r\n<br>\r\nЭти вещи брать не стоит<br>\r\n<br>\r\n<br>\r\n<br>\r\n<br>\r\nВнимание! Список вещей, которые НЕ следует брать с собой в роддом!<br>\r\n<br>\r\n<br>\r\nВозможно, вам кажется, что чем больше вы возьмете с собой вещей, тем комфортнее пройдет ваше с малышом пребывание в роддоме. Однако не переусердствуйте и не берите лишний багаж. В роддоме вы долго не задержитесь.<br>\r\n<br>\r\n<br>\r\nВ роддоме не следует пользоваться декоративной косметикой – вы находитесь в постоянном контакте с малышом, ваше лицо и руки должны быть идеально чистыми. Можно сделать исключение в виде накрашенных ресниц – не более.<br>\r\n<br>\r\nНе рекомендуется использовать косметику, антиперспирант с сильными ароматами. Забудьте на время о дезодорантах и духах. Малыш должен чувствовать мамин запах – он его интуитивно узнает с рождения и выделяет из всех остальных. Чувствуя его, он успокаивается и спит спокойнее – не мешайте свой неповторимый запах тела с косметическими ароматами. Главное правило – минимум резкости и больше натуральности.<br>\r\n<br>\r\nСоска – это главный враг грудного вскармливания. В роддоме можно и нужно обходиться без нее. Малыш, сосущий мамино молоко, очень редко сосет соску.<br>\r\n<br>\r\nСиликоновые накладки на грудь. Только в крайнем случае и только при сильных болевых ощущениях.<br>\r\n<br>\r\nЛекарства и иные медикаменты. Все, что вам или ребенку понадобится, пропишет или порекомендует врач – ваш муж вам принесет.<br>\r\n<br>\r\n<br>\r\nБезусловно, вы переживаете ответственный период в своей жизни. Рождение ребенка – это великое таинство. И совершается оно в большинстве случаях в роддоме. Надеемся, что рекомендации помогут вам спланировать это событие и сделать все возможное для ощущения наибольшего комфорта во время пребывания в роддоме.  ','необходимые вещи в роддом');
/*!40000 ALTER TABLE `publications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publications_tags`
--

DROP TABLE IF EXISTS `publications_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publications_tags` (
  `publication_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`publication_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publications_tags`
--

LOCK TABLES `publications_tags` WRITE;
/*!40000 ALTER TABLE `publications_tags` DISABLE KEYS */;
INSERT INTO `publications_tags` VALUES (1,1),(1,2),(2,1);
/*!40000 ALTER TABLE `publications_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'младенец'),(2,'норма');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `registerTime` int(10) unsigned NOT NULL,
  `lastAccessTime` int(10) unsigned NOT NULL,
  `nickname` varchar(42) NOT NULL,
  `avatar` int(10) unsigned NOT NULL,
  `role` int(11) NOT NULL DEFAULT '10',
  `hash` varchar(32) NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `vk_id` bigint(20) unsigned NOT NULL,
  `vk_name` varchar(200) NOT NULL,
  `vk_access_token` varchar(255) NOT NULL,
  `vk_access_token_expire` int(11) unsigned NOT NULL,
  `fb_id` bigint(11) unsigned NOT NULL,
  `fb_access_token` varchar(255) NOT NULL,
  `fb_access_token_expire` int(11) NOT NULL,
  `fb_name` varchar(255) NOT NULL,
  `ok_id` bigint(20) unsigned NOT NULL,
  `ok_access_token` varchar(255) NOT NULL,
  `ok_access_token_expire` int(11) NOT NULL,
  `ok_name` varchar(255) NOT NULL,
  `ok_refresh_token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nickname` (`nickname`),
  KEY `role` (`role`),
  KEY `hash` (`hash`),
  KEY `points` (`points`),
  KEY `id_vk` (`vk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (34,'223da944dbcda8a0b26cf7b7f0cd1ae3','baka_neko@mail.ru','cca47b4a5300169cd21659ed39165f24',1352363450,1354181911,'katary',240,20,'',300,0,'','',0,0,'0',0,'0',0,'',0,'',''),(43,'b27c62bac0b79223b69856eb79035fcc','amuhc@yandex.ru','202cb962ac59075b964b07152d234b70',1352469577,1354626796,'папа',162,20,'changing',500,2373635,'Михаил Чубарь','391aab51393e9352393e935265390f60923393f393b9354698371fb66e8f9d8396b8398',1354621609,100000092891495,'AAAFgzOpYRScBAGIom9nJCDbJiLnDIa15U5eu5giDUfsXe4izVBA2CIX4z4vl0pY1zMKkQCcnItwll71pirsSbTxpCNImUKQdsbrrogZDZD',1359800814,'Mihail Chubar',454068230972,'11ipa.15fa000w5u3e583h483k5absrva10-6',1354626741,'Михаил Чубарь','e2147684586c3bc5a55d2220a11c24f4630273_454068230972_135465'),(44,'989e9b9ea01bdcbdb246aa2d39f7a136','varmelised@gmail.com','d0335e543b4f4bea401641ad91826f85',1352807209,1352812457,'varmelised',0,10,'3f85c8483a5dd1aa4ab09049bad26aae',100,0,'','',0,0,'0',0,'0',0,'',0,'',''),(45,'7494e82687866b59b570e44bc14d7834','rybanv@ya.ru','6b00c0a7200481af7a90d6a7aacb0947',1353075773,1353415569,'ryba',262,10,'',100,0,'','',0,0,'0',0,'0',0,'',0,'',''),(46,'e150484c88194673d8d0b571a02f979c','russianswaggi@gmail.com','669285cc7d0ccdb544d00469b0363013',1354496507,1354496511,'glirtiela',0,0,'d96b31ead643a0882112d469cb306543',100,0,'','',0,0,'0',0,'0',0,'',0,'',''),(47,'a02d1b11279df08243ca284bbf9c226b','korotkov.hrumer@gmail.com','1ba0bbeef3e087f7d016620fd87130f0',1354614559,1354614563,'amatheexeme',0,0,'7b4eb5d0ae083d455c1eb8d3e247f5a7',100,0,'','',0,0,'0',0,'0',0,'',0,'','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_album`
--

DROP TABLE IF EXISTS `user_album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_album` (
  `user_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `role` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`album_id`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_album`
--

LOCK TABLES `user_album` WRITE;
/*!40000 ALTER TABLE `user_album` DISABLE KEYS */;
INSERT INTO `user_album` VALUES (45,3,1),(43,1,2);
/*!40000 ALTER TABLE `user_album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_badges`
--

DROP TABLE IF EXISTS `user_badges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_badges` (
  `user_id` int(10) unsigned NOT NULL,
  `badge_type_id` int(10) unsigned NOT NULL,
  `badge_id` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL,
  `progress` int(10) unsigned NOT NULL,
  `gained_time` int(10) unsigned NOT NULL,
  `accepted_time` int(10) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `points_gained` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`badge_type_id`,`badge_id`),
  KEY `time` (`update_time`),
  KEY `gained_time` (`gained_time`),
  KEY `progress` (`progress`),
  KEY `accepted_time` (`accepted_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_badges`
--

LOCK TABLES `user_badges` WRITE;
/*!40000 ALTER TABLE `user_badges` DISABLE KEYS */;
INSERT INTO `user_badges` VALUES (34,9,100,1353325202,1,1353325202,0,'За регистрацию на сайте',100),(34,40,500,0,1,1354181941,0,'За добавление фотографии',100),(34,90,1000,1353326941,2,1353326941,0,'За комментарий моей фотографии',100),(43,9,100,0,1,1353403672,0,'За регистрацию на сайте',100),(43,20,300,0,7,1353408661,0,'За добавление события',100),(43,40,500,0,19,1353408661,0,'За добавление фотографии',100),(43,60,700,1353403981,5,1353404101,0,'За лайк чужой фотографии',100),(43,60,701,1353404101,6,0,0,'За лайк 10 чужих фотографий',0),(43,80,900,1353326941,2,1353326941,0,'За комментарий',100),(44,9,100,1353325202,1,1353325202,0,'За регистрацию на сайте',100),(45,9,100,1353325202,1,1353325202,0,'За регистрацию на сайте',100),(46,9,100,0,1,1354496522,0,'За регистрацию на сайте',100),(47,9,100,0,1,1354614602,0,'За регистрацию на сайте',100);
/*!40000 ALTER TABLE `user_badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_badges_actions`
--

DROP TABLE IF EXISTS `user_badges_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_badges_actions` (
  `user_id` int(11) NOT NULL,
  `badge_type_id` int(11) NOT NULL,
  `progress_set` int(11) NOT NULL,
  `progress_add` int(11) NOT NULL,
  `processed` tinyint(3) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `user_id` (`user_id`,`processed`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_badges_actions`
--

LOCK TABLES `user_badges_actions` WRITE;
/*!40000 ALTER TABLE `user_badges_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_badges_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_points_log`
--

DROP TABLE IF EXISTS `user_points_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_points_log` (
  `user_id` int(10) unsigned NOT NULL,
  `points` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `user_id` (`user_id`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_points_log`
--

LOCK TABLES `user_points_log` WRITE;
/*!40000 ALTER TABLE `user_points_log` DISABLE KEYS */;
INSERT INTO `user_points_log` VALUES (44,100,'За регистрацию на сайте',1353325202),(45,100,'За регистрацию на сайте',1353325202),(34,100,'За регистрацию на сайте',1353325202),(34,100,'За комментарий моей фотографии',1353326941),(43,100,'За регистрацию на сайте',1353403672),(43,100,'За лайк чужой фотографии',1353404101),(43,100,'За добавление события',1353408661),(43,100,'За добавление фотографии',1353408661),(34,100,'За добавление фотографии',1354181941),(46,100,'За регистрацию на сайте',1354496522),(47,100,'За регистрацию на сайте',1354614602);
/*!40000 ALTER TABLE `user_points_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_suggest_inactive`
--

DROP TABLE IF EXISTS `user_suggest_inactive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_suggest_inactive` (
  `album_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`album_id`,`event_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_suggest_inactive`
--

LOCK TABLES `user_suggest_inactive` WRITE;
/*!40000 ALTER TABLE `user_suggest_inactive` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_suggest_inactive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wife_requests`
--

DROP TABLE IF EXISTS `wife_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wife_requests` (
  `user_id` int(11) NOT NULL,
  `wife_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `wife_id` (`wife_id`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wife_requests`
--

LOCK TABLES `wife_requests` WRITE;
/*!40000 ALTER TABLE `wife_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `wife_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `amazon_upload`
--

/*!50001 DROP TABLE IF EXISTS `amazon_upload`*/;
/*!50001 DROP VIEW IF EXISTS `amazon_upload`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `amazon_upload` AS select `amazon_limit`.`day` AS `day`,`amazon_limit`.`uploaded_bytes` AS `uploaded_bytes`,`amazon_limit`.`uploaded_count` AS `uploaded_count`,((`amazon_limit`.`uploaded_bytes` / 1024) / 1024) AS `Mb` from `amazon_limit` where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-12-04 17:14:00
