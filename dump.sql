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
  `pic_small` int(10) unsigned NOT NULL,
  `pic_normal` int(10) unsigned NOT NULL,
  `pic_big` int(10) unsigned NOT NULL,
  `pic_orig` int(10) unsigned NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `private` (`private`),
  KEY `sex` (`sex`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
INSERT INTO `album` VALUES (1,43,'Лёнечка',1352469713,1352469757,'2011-10-24',0,228,229,230,231,1);
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
  `pic_small` int(11) NOT NULL,
  `pic_normal` int(11) NOT NULL,
  `pic_big` int(11) NOT NULL,
  `pic_orig` int(10) unsigned NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_events`
--

LOCK TABLES `album_events` WRITE;
/*!40000 ALTER TABLE `album_events` DISABLE KEYS */;
INSERT INTO `album_events` VALUES (1,1,0,0,1352470852,232,233,234,235,'умаялся и спит','Мама наиграла так, что заснул прямо на месте)','2012-11-09 18:04:00',1,0),(2,1,0,7,1352470344,236,237,238,238,'Вечеринка в честь первого дня рождения удалась','Мы готовились целую неделю, и праздник удался. Пока папа надувал шарики, мама готовила потрясающие десерты и шашлыки. А на день рождения пришел наш друг - Паша, и тоже принес сладких подарков)','2012-11-09 18:09:00',1,0);
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
  `value_int` int(11) DEFAULT NULL,
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
INSERT INTO `album_events_fields` VALUES (1,1,NULL,'2012-11-09 18:04:00',NULL),(1,2,NULL,'умаялся и спит',NULL),(1,3,NULL,NULL,'Мама наиграла так, что заснул прямо на месте)'),(2,12,NULL,'2012-11-09 18:09:00',NULL),(2,13,NULL,NULL,'Мы готовились целую неделю, и праздник удался. Пока папа надувал шарики, мама готовила потрясающие десерты и шашлыки. А на день рождения пришел наш друг - Паша, и тоже принес сладких подарков)'),(2,16,176,NULL,NULL),(2,17,13,NULL,NULL),(2,18,NULL,'Вечеринка в честь первого дня рождения удалась',NULL);
/*!40000 ALTER TABLE `album_events_fields` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
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
INSERT INTO `event_likes` VALUES (2,43,1352471262),(1,43,1352471266);
/*!40000 ALTER TABLE `event_likes` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_events`
--

LOCK TABLES `lib_events` WRITE;
/*!40000 ALTER TABLE `lib_events` DISABLE KEYS */;
INSERT INTO `lib_events` VALUES (1,'Рождение',0,0,31,'Этого события ждали долгие месяцы, и вот - это произошло! С днём рождения, малыш!',2,0),(2,'Выписка',0,0,31,'Наконец-то домой! Показываться родственникам и обживаться на новом месте.',1,0),(5,'Первые самостоятельные шаги',0,200,600,'За ручку мы уже находились, теперь - сами!',1,1),(6,'Держим головку',0,31,120,'Охх сколько всего интересного вокруг! Только успевай шеей крутить!',1,1),(7,'Первый день рождения',0,363,600,'Мне уже целый год! Сколько всего интересного за год успело произойти!',3,1),(8,'Первый раз сели',0,40,200,'Лежать надоело, сидеть - весело!',1,1),(9,'Первый раз засмеялись',0,55,150,'Рассмешили так рассмешили) Теперь буду смеяться без остановки!',1,1),(10,'Первое кормление',0,90,365,'Что это? Совсем не похоже на мамино молоко!',1,1),(11,'Стоим сами',0,210,400,'Сидеть, лежать... А я вот постою! А вам слабо?',1,1),(12,'Первый зуб',0,60,380,'Всё, вылез! Первый зубик, привет!',1,1),(13,'Первая игрушка',0,0,63,'Моя самая первая в жизни игрушка!',1,1),(14,'Я и мама',0,0,50,'Первая фотография с мамой',1,1),(15,'Я и папа',0,0,60,'Первая фотография с папой',1,1),(16,'Я и семья',0,5,90,'Первая семейная фотография',1,1),(17,'Танцую',0,100,450,'Вот как я танцую, завидуйте все!',1,1),(18,'Первый поход в поликлинику',0,30,180,'А там куча деток, и все с мамами! Вот где настоящее веселье!',1,1),(19,'Второй день рождения',0,720,800,'Мне уже два года! Вот как мы празднуем!',3,1),(20,'Моё первое слово',0,365,728,'Вот теперь, мама и папа, держитесь! Скоро я выучу еще пару слов и делать вид, что вы меня не понимаете, будет сложнее!',1,1),(21,'Первое узи',0,0,25,'Первый раз мама не только почуствует, но и увидит малыша',1,1),(22,'Последнее узи',0,0,26,'Последний раз мы видим малышку на экране. В следующий раз встретимся с глазу на глаз!',1,1),(23,'Первое молочко',0,0,14,'Первый раз пробуем мамино молочко',1,1);
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
  `avatar_small` int(10) unsigned NOT NULL,
  `avatar_normal` int(10) unsigned NOT NULL,
  `role` int(11) NOT NULL DEFAULT '10',
  `hash` varchar(32) NOT NULL,
  `family_role` tinyint(3) unsigned NOT NULL,
  `wife_user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nickname` (`nickname`),
  KEY `role` (`role`),
  KEY `hash` (`hash`),
  KEY `wife_user_id` (`wife_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (34,'058bf1a530b7f22c3cb082bc80f2a5f4','baka_neko@mail.ru','cca47b4a5300169cd21659ed39165f24',1352363450,0,'katary',0,0,20,'',0,0),(43,'1b9219cee1729c807a72803df35bc146','amuhc@yandex.ru','c68c9c8258ea7d85472dd6fd0015f047',1352469577,0,'папа',226,227,10,'',0,0);
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
  PRIMARY KEY (`user_id`,`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_album`
--

LOCK TABLES `user_album` WRITE;
/*!40000 ALTER TABLE `user_album` DISABLE KEYS */;
INSERT INTO `user_album` VALUES (43,1);
/*!40000 ALTER TABLE `user_album` ENABLE KEYS */;
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-11-09 15:37:49
