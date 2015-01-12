# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.34)
# Database: inforuutu
# Generation Time: 2015-01-12 16:20:58 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table instagram
# ------------------------------------------------------------

DROP TABLE IF EXISTS `instagram`;

CREATE TABLE `instagram` (
  `id` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `username` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `image_url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `likes` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table metadata
# ------------------------------------------------------------

DROP TABLE IF EXISTS `metadata`;

CREATE TABLE `metadata` (
  `parameter` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value` varchar(255) CHARACTER SET utf8 DEFAULT '',
  PRIMARY KEY (`parameter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

LOCK TABLES `metadata` WRITE;
/*!40000 ALTER TABLE `metadata` DISABLE KEYS */;

INSERT INTO `metadata` (`parameter`, `value`)
VALUES
	('social_last_updated','1421079374');

/*!40000 ALTER TABLE `metadata` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table slide
# ------------------------------------------------------------

DROP TABLE IF EXISTS `slide`;

CREATE TABLE `slide` (
  `number` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `text` text COLLATE utf8_bin,
  `delay` int(11) NOT NULL,
  `visibility` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

LOCK TABLES `slide` WRITE;
/*!40000 ALTER TABLE `slide` DISABLE KEYS */;

INSERT INTO `slide` (`number`, `title`, `text`, `delay`, `visibility`)
VALUES
	(0,'Example Slide 1',X'3C703E4578616D706C6520536C69646520636F6E74656E743C703E',5,1),
	(1,'Example Slide 2',X'3C703E3C7374726F6E673E48544D4C3C2F7374726F6E673E206172652074616773203C656D3E737570706F727465643C2F656D3E3C2F703E0A0A3C756C3E0A3C6C693E4C697374204974656D20313C2F6C693E0A3C6C693E4C697374204974656D20323C2F6C693E0A3C2F756C3E',5,1);

/*!40000 ALTER TABLE `slide` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table twitter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `twitter`;

CREATE TABLE `twitter` (
  `id` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `text` text COLLATE utf8_bin,
  `created_at` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `screen_name` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `profile_image_url` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `media` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `is_retweet` tinyint(1) DEFAULT NULL,
  `original_poster` varchar(255) CHARACTER SET utf8 DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
