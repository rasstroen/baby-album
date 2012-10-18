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
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
INSERT INTO `album` VALUES (1,2,'Лёнька',1350036783,1350036783,'2011-10-24');
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
  `event_id` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  `pic_small` int(11) NOT NULL,
  `pic_normal` int(11) NOT NULL,
  `pic_big` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `eventTime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eventTime` (`eventTime`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_events`
--

LOCK TABLES `album_events` WRITE;
/*!40000 ALTER TABLE `album_events` DISABLE KEYS */;
INSERT INTO `album_events` VALUES (1,1,0,0,0,0,0,'','',0),(2,1,0,0,0,0,0,'','',0),(3,1,2,0,0,0,0,'','',0),(4,1,1,0,0,0,0,'','',0);
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
  `value_int` int(11) NOT NULL,
  `value_varchar` varchar(255) NOT NULL,
  `value_text` text NOT NULL,
  PRIMARY KEY (`event_id`,`field_id`),
  KEY `value_int` (`value_int`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_events_fields`
--

LOCK TABLES `album_events_fields` WRITE;
/*!40000 ALTER TABLE `album_events_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `album_events_fields` ENABLE KEYS */;
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
INSERT INTO `lib_event_templates` VALUES (1,'Рождение'),(3,'День рождения');
/*!40000 ALTER TABLE `lib_event_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_event_templates_fields`
--

DROP TABLE IF EXISTS `lib_event_templates_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_event_templates_fields` (
  `field_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `pos` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `important` tinyint(3) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`field_id`),
  UNIQUE KEY `template_id_2` (`template_id`,`type`),
  KEY `pos` (`pos`),
  KEY `template_id` (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_event_templates_fields`
--

LOCK TABLES `lib_event_templates_fields` WRITE;
/*!40000 ALTER TABLE `lib_event_templates_fields` DISABLE KEYS */;
INSERT INTO `lib_event_templates_fields` VALUES (1,1,5,1,1,'Назвали'),(2,1,1,12,1,'Время рождения'),(3,1,3,8,1,'Вес'),(4,1,4,9,1,'Цвет глаз'),(5,1,2,10,1,'Рост'),(6,3,1,8,1,'Мой вес'),(7,3,2,10,1,'Мой рост');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_event_templates_fields_types`
--

LOCK TABLES `lib_event_templates_fields_types` WRITE;
/*!40000 ALTER TABLE `lib_event_templates_fields_types` DISABLE KEYS */;
INSERT INTO `lib_event_templates_fields_types` VALUES (1,'строка','string'),(2,'текст','text'),(3,'фотография','photo'),(4,'время','time'),(5,'дата и время','datetime'),(6,'дата','date'),(7,'вес в граммах','weight'),(8,'вес в килограммах','weightkg'),(9,'цвет глаз','eye'),(10,'рост в сантиметрах','height'),(12,'время события','eventTime'),(13,'описание события','description'),(14,'заголовок события','title');
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
  `need_photo` tinyint(3) unsigned NOT NULL,
  `need_description` tinyint(3) unsigned NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `age_start_days` (`age_start_days`,`age_end_days`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_events`
--

LOCK TABLES `lib_events` WRITE;
/*!40000 ALTER TABLE `lib_events` DISABLE KEYS */;
INSERT INTO `lib_events` VALUES (1,'Рождение',0,0,31,'Этого события ждали долгие месяцы, и вот - это произошло! С днём рождения, малыш!',0,0,1),(2,'Выписка',0,0,31,'Наконец-то домой! Показываться родственникам и обживаться на новом месте.',0,0,0),(5,'Первые самостоятельные шаги',0,200,600,'За ручку мы уже находились, теперь - сами!',0,0,0),(6,'Держим головку',0,31,120,'Охх сколько всего интересного вокруг! Только успевай шеей крутить!',0,0,0),(7,'Первый день рождения',0,363,600,'Мне уже целый год! Сколько всего интересного за год успело произойти!',1,0,3),(8,'Первый раз сели',0,40,200,'Лежать надоело, сидеть - весело!',0,0,0),(9,'Первый раз засмеялись',0,55,150,'Рассмешили так рассмешили) Теперь буду смеяться без остановки!',0,0,0),(10,'Первое кормление',0,90,365,'Что это? Совсем не похоже на мамино молоко!',0,0,0),(11,'Стоим сами',0,210,400,'Сидеть, лежать... А я вот постою! А вам слабо?',0,0,0),(12,'Первый зуб',0,60,380,'Всё, вылез! Первый зубик, привет!',0,0,0),(13,'Первая игрушка',0,0,63,'Моя самая первая в жизни игрушка!',1,0,0),(14,'Я и мама',0,0,50,'Первая фотография с мамой',1,0,0),(15,'Я и папа',0,0,60,'Первая фотография с папой',1,0,0),(16,'Я и семья',0,5,90,'Первая семейная фотография',1,0,0),(17,'Танцую',0,100,450,'Вот как я танцую, завидуйте все!',0,0,0),(18,'Первый поход в поликлинику',0,30,180,'А там куча деток, и все с мамами! Вот где настоящее веселье!',0,0,0),(19,'Второй день рождения',0,720,800,'Мне уже два года! Вот как мы празднуем!',0,0,3),(20,'Моё первое слово, кроме \"мама\"',0,365,728,'',0,1,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publications`
--

LOCK TABLES `publications` WRITE;
/*!40000 ALTER TABLE `publications` DISABLE KEYS */;
INSERT INTO `publications` VALUES (1,2,0,365,0,1350390101,0,'Вес и рост от новорожденного до годовалого ребёнка','Каким должен быть рост и вес малыша, какой рост является нормой, какой вес является нормой, отличаются ли средние параметры девочки от параметров мальчика?','Рост ребенка от 0 до 12 месяцев\r\n<table>\r\n    <tr>\r\n        <td>Возраст</td>\r\n        <td>Мальчики</td>\r\n        <td>Девочки</td>\r\n    </tr>\r\n    <tr>\r\n        <td>Новорожденный</td>\r\n        <td>50</td>\r\n        <td>50</td>\r\n    </tr>\r\n    <tr>\r\n        <td>1</td>\r\n        <td>54</td>\r\n        <td>54</td>\r\n    </tr>\r\n    <tr>\r\n        <td>2</td>\r\n        <td>57</td>\r\n        <td>57</td>\r\n    </tr>\r\n    <tr>\r\n        <td>3</td>\r\n        <td>60</td>\r\n        <td>60</td>\r\n    </tr>\r\n    <tr>\r\n        <td>4</td>\r\n        <td>63</td>\r\n        <td>62</td>\r\n    </tr>\r\n    <tr>\r\n        <td>5</td>\r\n        <td>66</td>\r\n        <td>64</td>\r\n    </tr>\r\n    <tr>\r\n        <td>6</td>\r\n        <td>68</td>\r\n        <td>66</td>\r\n    </tr>\r\n    <tr>\r\n        <td>7</td>\r\n        <td>70</td>\r\n        <td>68</td>\r\n    </tr>\r\n    <tr>\r\n        <td>8</td>\r\n        <td>72</td>\r\n        <td>70</td>\r\n    </tr>\r\n    <tr>\r\n        <td>9</td>\r\n        <td>73</td>\r\n        <td>72</td>\r\n    </tr>\r\n    <tr>\r\n        <td>10</td>\r\n        <td>74</td>\r\n        <td>73</td>\r\n    </tr>\r\n    <tr>\r\n        <td>11</td>\r\n        <td>75</td>\r\n        <td>74</td>\r\n    </tr>\r\n    <tr>\r\n        <td>12</td>\r\n        <td>76</td>\r\n        <td>75</td>\r\n    </tr>\r\n</table>','параметры малыша, рост ребёнка, рост новорожденного'),(3,2,0,365,0,1350399466,0,'Желтуха у новорожденных ','У детей первых дней жизни бывает, что кожа и слизистые оболочки приобретают желтоватый оттенок. Это происходит из-за нарушения билирубинового обмена, вследствие незрелости ферментных систем печени.','У детей первых дней жизни бывает, что кожа и слизистые оболочки приобретают желтоватый оттенок. Это происходит из-за нарушения билирубинового обмена, вследствие незрелости ферментных систем печени. Билирубин - это вещество из группы желчных пигментов. Он является продуктом обмена гемоглобина, входящего в состав эритроцитов. После их разрушения гемоглобин проходит ряд превращений, в конце которых образуется билирубин. С током крови он попадает в печень, где происходит его обезвреживание, за счёт присоединения глюкуроновой кислоты. Так образуется прямой (связанный) билирубин,он мало токсичен,и легко выводится с мочой,придавая ей характерный желтый цвет. При некоторых состояниях билирубин не обезвреживается, он называется непрямым, и может повреждать органы и ткани, особенно головной мозг.\r\n<br>\r\nЖелтуха новорожденных возникает при сильном кровоизлиянии во время родов, при острых и хронических инфекциях, при несовместимости крови матери и плода, при механической задержке желчи, при врожденной недостаточности фермента глюкозо-6-фосфатдегидрогеназы, который учувствует в обмене билирубина. Желтуха новорожденных возникает обычно, когда вы находитесь в роддоме. Врачи замечают это и делают все необходимые анализы. Анализы покажут содержание билирубина в крови. Его показатели зависят от времени,прошедшего со дня родов,и в динамике должны снижаться. Основным методом лечения является фототерапия,она способствует переходу непрямого билирубина в прямой. Так же возможно применение таких лекарственных препаратов как фенобарбитал, вит Е, сорбенты и др. Но только по назначению врача. В крайних случаях проводят переливания крови ( только при патологических желтухах,чаще при резус-конфликте). В настоящее время допускается сохранение лёгкой желтухи или иктеричности склер (пожелтение склер) до 3-4 нед. При этом необходим контроль билирубина,особенно при нарастании желтухи или нарушениях в поведении малыша. Держите ребенка на свежем воздухе и под лучами солнца, почаще прикладывайте к груди, если ребёнок получает смесь, обязательно допаивайте!\r\n<br>\r\nПризнаки желтухи новорожденных: желтый цвет кожи и слизистых оболочек. Если желтушный цвет кожи у новорожденного долго не проходит, необходимо провести всестороннее обследование ребенка, чтобы выяснить причину заболевания. Физиологическая желтуха новорожденных обычно не требует лечения. В других случаях лечение проводится в зависимости от причин.\r\n<br>\r\nВстречаются несколько видов желтухи:\r\n<ul>\r\n   <li>Конъюгационная. Это следствие нарушения процессов превращения непрямого билирубина в прямой.\r\n    <li>Гемолитическая. Возникает вследствие интенсивного распада эритроцитов.\r\n    <li>Механическая (обтурационная ). Происходит из-за механического препятствия оттоку желчи в двенадцатиперстную кишку.\r\n    <li>Печеночная (паренхиматозная). Это поражение тканей печени, которая происходит при гепатите.\r\n    <li>Физиологическая (желтуха новорожденных) - это так называемая, транзиторная (временная) коньюгационная желтуха. Возникает из-за того, что в эритроцитах плода есть особый гемоглобин, F - фетальный. После рождения эритроциты разрушаются. \r\n</ul>\r\n<br/>\r\nИмеющийся у новорожденных дефицит белка, который отвечает за перенос билирубина, способствует его накоплению. Кроме того, печень новорожденного обладает низкой выделительной способностью.\r\n<br/>\r\nОбычно желтуха новорожденных исчезает через 2-3 недели после появления, не причиняя вреда малышу. В крайнем случае, когда желтуха явно выражена, иногда используют внутривенное вливание растворов глюкозы, аскорбиновую кислоту, фенобарбитал, желчегонные средства, используют фототерапию. Чаще желтуха новорожденных встречается у недоношенных детишек, она более длительна и выражена. То есть выраженность желтухи зависит от доношенности плода и болезней матери во время беременности.\r\n<br/>\r\nВстречается желтуха, вызванная молоком матери. Недуг появляется через неделю после рождения малыша, а исчезает к 3-4 недели. Считается, что причиной такой желтухи является содержание определенного вида жирных кислот в молоке. Эти вещества подавляют функции печени, тормозя превращения непрямого билирубина в прямой. При такой желтухе ребенка следует чаще кормить грудным молоком, чтобы организм со стулом быстрее выделял билирубин.\r\n<br/>\r\nУ новорожденных желтуха может развиться при гипотиреозе ( снижение функциональной активности щитовидной железы ). Кроме желтухи, гипотиреоз характеризуется отечностью, сухостью волос, грубостью голоса, повышением холестерина, задержка окостенения. Такая желтуха возникает на 2-3 день после рождения и угасает к 3 - 12 недели, но может продлиться 4 - 5 месяцев. Лечится гипотиреоз эндокринологом.\r\n<br/>\r\nПричиной обтурационной (механической) желтухи у новорожденного может стать непроходимость печёночных или желчного протоков.Для такого вида желтухи характерен жёлтый с зеленоватым оттенком цвет кожи,зуд,потемнение мочи и осветление кала.\r\n<br/>\r\nГемолитическая желтуха появляется, в основном, вследствие несовместимости крови матери и ребенка по группе или резус - фактора. Характеризуется болезнь повышенным разрушением эритроцитов. Кроме этого, причинами могут послужить нарушение структур гемоглобина, нарушение формы и структуры эритроцитов и дефицит ферментативных систем эритроцитов.\r\n<br/>\r\nПоявление желтухи в первые часы или сутки после рождения является плохим прогностическим признаком,и говорит о высоких цифрах билирубина, при которых может возникнуть так называемая \"ядерная желтуха\" или \"билирубиновая энцефалопатия\" . Это состояние характерезуется пропитыванием билирубином серого вещества головного мозга. Признаки заболевания - сонливость, плохое сосание, изменение рефлексов, монотоный слабый крик, постанование. Осложнения - глухота, параличи, умственная отсталость.\r\n<br/>\r\nТяжелые формы гемолитической желтухи лечатся переливанием крови. Так что будьте внимательны к своим деткам, к их здоровью и состоянию. ','желтый младенец, желтуха у новорожденного');
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'c09c58da1dd29917bd07a83fd44f65d1','amuhc@ya.ru','c68c9c8258ea7d85472dd6fd0015f047',1350044399,0,'Александрович',105,106,20),(9,'f6b3ccd5ea079bd4e4b44f368aa8598b','amuhc@yandex.ru','c68c9c8258ea7d85472dd6fd0015f047',0,0,'',0,0,20);
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
INSERT INTO `user_album` VALUES (2,1);
/*!40000 ALTER TABLE `user_album` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-10-18 15:47:53
