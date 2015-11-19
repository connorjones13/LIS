# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.26)
# Database: COMP3700_ECC
# Generation Time: 2015-11-19 22:23:54 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table author
# ------------------------------------------------------------

DROP TABLE IF EXISTS `author`;

CREATE TABLE `author` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `book` int(11) unsigned NOT NULL,
  `name_full` varchar(255) NOT NULL DEFAULT '' COMMENT 'google only provides this',
  PRIMARY KEY (`id`),
  KEY `author_book_id` (`book`),
  CONSTRAINT `author_book_id` FOREIGN KEY (`book`) REFERENCES `rental_item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;

INSERT INTO `author` (`id`, `book`, `name_full`)
VALUES
  (1,2,'Jim'),
  (2,2,'Bob'),
  (3,2,'Billy'),
  (4,5,'Jim'),
  (5,5,'Bob'),
  (6,5,'Billy'),
  (7,8,'Jim'),
  (8,8,'Bob'),
  (9,8,'Billy'),
  (10,11,'Jim'),
  (11,11,'Bob'),
  (12,11,'Billy'),
  (13,14,'Jim'),
  (14,14,'Bob'),
  (15,14,'Billy'),
  (16,17,'Jim'),
  (17,17,'Bob'),
  (18,17,'Billy'),
  (19,20,'Jim'),
  (20,20,'Bob'),
  (21,20,'Billy');

/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table checkout
# ------------------------------------------------------------

DROP TABLE IF EXISTS `checkout`;

CREATE TABLE `checkout` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `checkout_employee` int(11) unsigned DEFAULT NULL,
  `checkin_employee` int(11) unsigned DEFAULT NULL,
  `user` int(11) unsigned NOT NULL,
  `rental_item` int(11) unsigned NOT NULL,
  `date_checked_out` datetime NOT NULL,
  `date_due` date NOT NULL,
  `date_returned` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkout_admin_id` (`checkout_employee`),
  KEY `checkout_user_id` (`user`),
  KEY `checkout_rental_item_id` (`rental_item`),
  KEY `checkin_employee_id` (`checkin_employee`),
  CONSTRAINT `checkin_employee_id` FOREIGN KEY (`checkin_employee`) REFERENCES `user` (`id`),
  CONSTRAINT `checkout_rental_item_id` FOREIGN KEY (`rental_item`) REFERENCES `rental_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `checkout_user_id` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table late_fee
# ------------------------------------------------------------

DROP TABLE IF EXISTS `late_fee`;

CREATE TABLE `late_fee` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `checkout` int(11) unsigned NOT NULL,
  `fee` decimal(12,2) NOT NULL,
  `date_paid` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkout` (`checkout`),
  CONSTRAINT `late_fee_ibfk_1` FOREIGN KEY (`checkout`) REFERENCES `checkout` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table library_card
# ------------------------------------------------------------

DROP TABLE IF EXISTS `library_card`;

CREATE TABLE `library_card` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned DEFAULT NULL,
  `number` varchar(16) NOT NULL DEFAULT '',
  `date_issued` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'active = 1 | inactive = 0 | lost = 2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_number` (`number`),
  KEY `library_card_user` (`user`),
  CONSTRAINT `library_card_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `library_card` WRITE;
/*!40000 ALTER TABLE `library_card` DISABLE KEYS */;

INSERT INTO `library_card` (`id`, `user`, `number`, `date_issued`, `status`)
VALUES
  (1,1,'SHGMP0035PZBV9FG','2015-11-18',1);

/*!40000 ALTER TABLE `library_card` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rental_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rental_item`;

CREATE TABLE `rental_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `summary` varchar(6143) NOT NULL DEFAULT '',
  `category` varchar(255) NOT NULL DEFAULT '',
  `date_published` date DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = nominal | 1 = lost | 2 = damaged',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `rental_item` WRITE;
/*!40000 ALTER TABLE `rental_item` DISABLE KEYS */;

INSERT INTO `rental_item` (`id`, `title`, `summary`, `category`, `date_published`, `date_added`, `status`)
VALUES
  (1,'My First Magazine','This is a magazine summary.','Adult','2015-11-07','2015-11-18',2),
  (2,'My First Book','This is a book summary.','Horror','2015-11-07','2015-11-18',2),
  (3,'My First DVD','This is a DVD summary.','Action','2011-05-15','2015-11-18',3),
  (4,'My First Magazine','This is a magazine summary.','Adult','2015-11-07','2015-11-18',2),
  (5,'My First Book','This is a book summary.','Horror','2015-11-07','2015-11-18',2),
  (6,'My First DVD','This is a DVD summary.','Action','2011-05-15','2015-11-18',3),
  (7,'My First Magazine','This is a magazine summary.','Adult','2015-11-07','2015-11-18',2),
  (8,'My First Book','This is a book summary.','Horror','2015-11-07','2015-11-18',2),
  (9,'My First DVD','This is a DVD summary.','Action','2011-05-15','2015-11-18',3),
  (10,'My First Magazine','This is a magazine summary.','Adult','2015-11-07','2015-11-18',2),
  (11,'My First Book','This is a book summary.','Horror','2015-11-07','2015-11-18',2),
  (12,'My First DVD','This is a DVD summary.','Action','2011-05-15','2015-11-18',3),
  (13,'My First Magazine','This is a magazine summary.','Adult','2015-11-07','2015-11-18',2),
  (14,'My First Book','This is a book summary.','Horror','2015-11-07','2015-11-18',2),
  (15,'My First DVD','This is a DVD summary.','Action','2011-05-15','2015-11-18',3),
  (16,'My First Magazine','This is a magazine summary.','Adult','2015-11-07','2015-11-18',2),
  (17,'My First Book','This is a book summary.','Horror','2015-11-07','2015-11-18',2),
  (18,'My First DVD','This is a DVD summary.','Action','2011-05-15','2015-11-18',3),
  (19,'My First Magazine','This is a magazine summary.','Adult','2015-11-07','2015-11-18',2),
  (20,'My First Book','This is a book summary.','Horror','2015-11-07','2015-11-18',2),
  (21,'My First DVD','This is a DVD summary.','Action','2011-05-15','2015-11-18',3);

/*!40000 ALTER TABLE `rental_item` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rental_item_book
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rental_item_book`;

CREATE TABLE `rental_item_book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `isbn10` varchar(13) DEFAULT '',
  `isbn13` varchar(13) DEFAULT '',
  PRIMARY KEY (`id`),
  CONSTRAINT `rental_item_book_ibfk_2` FOREIGN KEY (`id`) REFERENCES `rental_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `rental_item_book` WRITE;
/*!40000 ALTER TABLE `rental_item_book` DISABLE KEYS */;

INSERT INTO `rental_item_book` (`id`, `isbn10`, `isbn13`)
VALUES
  (2,'',''),
  (5,'',''),
  (8,'',''),
  (11,'',''),
  (14,'',''),
  (17,'',''),
  (20,'','');

/*!40000 ALTER TABLE `rental_item_book` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rental_item_dvd
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rental_item_dvd`;

CREATE TABLE `rental_item_dvd` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `director` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  CONSTRAINT `rental_item_dvd_ibfk_2` FOREIGN KEY (`id`) REFERENCES `rental_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `rental_item_dvd` WRITE;
/*!40000 ALTER TABLE `rental_item_dvd` DISABLE KEYS */;

INSERT INTO `rental_item_dvd` (`id`, `director`)
VALUES
  (3,'Joseph Maxwell'),
  (6,'Joseph Maxwell'),
  (9,'Joseph Maxwell'),
  (12,'Joseph Maxwell'),
  (15,'Joseph Maxwell'),
  (18,'Joseph Maxwell'),
  (21,'Joseph Maxwell');

/*!40000 ALTER TABLE `rental_item_dvd` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rental_item_magazine
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rental_item_magazine`;

CREATE TABLE `rental_item_magazine` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publication` varchar(255) NOT NULL DEFAULT '',
  `issue_number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `rental_item_magazine_ibfk_2` FOREIGN KEY (`id`) REFERENCES `rental_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `rental_item_magazine` WRITE;
/*!40000 ALTER TABLE `rental_item_magazine` DISABLE KEYS */;

INSERT INTO `rental_item_magazine` (`id`, `publication`, `issue_number`)
VALUES
  (1,'Maxim',3),
  (4,'Maxim',3),
  (7,'Maxim',3),
  (10,'Maxim',3),
  (13,'Maxim',3),
  (16,'Maxim',3),
  (19,'Maxim',3);

/*!40000 ALTER TABLE `rental_item_magazine` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table reservation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reservation`;

CREATE TABLE `reservation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `rental_item` int(11) unsigned NOT NULL,
  `checkout` int(11) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `is_expired` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `reservation_user_id` (`user`),
  KEY `reservation_rental_item_id` (`rental_item`),
  KEY `reservation_checkout_id` (`checkout`),
  CONSTRAINT `reservation_checkout_id` FOREIGN KEY (`checkout`) REFERENCES `checkout` (`id`),
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`rental_item`) REFERENCES `rental_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table ri_book
# ------------------------------------------------------------

DROP VIEW IF EXISTS `ri_book`;

CREATE TABLE `ri_book` (
  `id` INT(11) UNSIGNED NULL DEFAULT '0',
  `title` VARCHAR(255) NULL DEFAULT '',
  `summary` VARCHAR(6143) NULL DEFAULT '',
  `category` VARCHAR(255) NULL DEFAULT '',
  `date_published` DATE NULL DEFAULT NULL,
  `date_added` DATE NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT '0',
  `is_checked_out` BIGINT(21) NOT NULL DEFAULT '0',
  `is_reserved` BIGINT(21) NOT NULL DEFAULT '0',
  `authors` TEXT NULL DEFAULT NULL,
  `isbn10` VARCHAR(13) NULL DEFAULT '',
  `isbn13` VARCHAR(13) NULL DEFAULT ''
) ENGINE=MyISAM;



# Dump of table ri_dvd
# ------------------------------------------------------------

DROP VIEW IF EXISTS `ri_dvd`;

CREATE TABLE `ri_dvd` (
  `id` INT(11) UNSIGNED NULL DEFAULT '0',
  `title` VARCHAR(255) NULL DEFAULT '',
  `summary` VARCHAR(6143) NULL DEFAULT '',
  `category` VARCHAR(255) NULL DEFAULT '',
  `date_published` DATE NULL DEFAULT NULL,
  `date_added` DATE NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT '0',
  `is_checked_out` BIGINT(21) NOT NULL DEFAULT '0',
  `is_reserved` BIGINT(21) NOT NULL DEFAULT '0',
  `director` VARCHAR(255) NULL DEFAULT ''
) ENGINE=MyISAM;



# Dump of table ri_magazine
# ------------------------------------------------------------

DROP VIEW IF EXISTS `ri_magazine`;

CREATE TABLE `ri_magazine` (
  `id` INT(11) UNSIGNED NULL DEFAULT '0',
  `title` VARCHAR(255) NULL DEFAULT '',
  `summary` VARCHAR(6143) NULL DEFAULT '',
  `category` VARCHAR(255) NULL DEFAULT '',
  `date_published` DATE NULL DEFAULT NULL,
  `date_added` DATE NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT '0',
  `is_checked_out` BIGINT(21) NOT NULL DEFAULT '0',
  `is_reserved` BIGINT(21) NOT NULL DEFAULT '0',
  `publication` VARCHAR(255) NULL DEFAULT '',
  `issue_number` BIGINT(11) NULL DEFAULT NULL
) ENGINE=MyISAM;



# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `privilege_level` tinyint(1) NOT NULL DEFAULT '0',
  `name_first` varchar(255) NOT NULL DEFAULT '',
  `name_last` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL,
  `date_signed_up` date NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not_specified | 1 = male | 2 = female | 3 = other',
  `date_of_birth` date DEFAULT NULL COMMENT '0 = not_specified',
  `address_line_1` varchar(255) NOT NULL DEFAULT '',
  `address_line_2` varchar(255) NOT NULL DEFAULT '',
  `address_zip` varchar(10) NOT NULL DEFAULT '',
  `address_city` varchar(255) NOT NULL DEFAULT '',
  `address_state` varchar(255) NOT NULL,
  `address_country_code` varchar(3) NOT NULL DEFAULT 'USA',
  `password_hash` varchar(255) NOT NULL DEFAULT '',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `account_confirm_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_account_confirm_token_uindex` (`account_confirm_token`),
  UNIQUE KEY `user_reset_token_uindex` (`reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `active`, `privilege_level`, `name_first`, `name_last`, `email`, `phone`, `date_signed_up`, `gender`, `date_of_birth`, `address_line_1`, `address_line_2`, `address_zip`, `address_city`, `address_state`, `address_country_code`, `password_hash`, `reset_token`, `reset_token_expiry`, `account_confirm_token`)
VALUES
  (1,1,2,'Steven','Imle','ski0005@auburn.edu','(251) 533-0631','2015-11-18',1,'1993-11-10','','','','','','','$2y$10$c.GpLM8wZkWyxQj.rfS7.eyOg1.LMCtTv.UMyI8xWdDdiNWeISU4S','',NULL,NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_view
# ------------------------------------------------------------

DROP VIEW IF EXISTS `user_view`;

CREATE TABLE `user_view` (
  `id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `active` TINYINT(1) NOT NULL DEFAULT '1',
  `privilege_level` TINYINT(1) NOT NULL DEFAULT '0',
  `name_first` VARCHAR(255) NOT NULL DEFAULT '',
  `name_last` VARCHAR(255) NOT NULL DEFAULT '',
  `email` VARCHAR(255) NOT NULL DEFAULT '',
  `phone` VARCHAR(255) NOT NULL,
  `date_signed_up` DATE NOT NULL,
  `gender` TINYINT(1) NOT NULL DEFAULT '0',
  `date_of_birth` DATE NULL DEFAULT NULL,
  `address_line_1` VARCHAR(255) NOT NULL DEFAULT '',
  `address_line_2` VARCHAR(255) NOT NULL DEFAULT '',
  `address_zip` VARCHAR(10) NOT NULL DEFAULT '',
  `address_city` VARCHAR(255) NOT NULL DEFAULT '',
  `address_state` VARCHAR(255) NOT NULL,
  `address_country_code` VARCHAR(3) NOT NULL DEFAULT 'USA',
  `password_hash` VARCHAR(255) NOT NULL DEFAULT '',
  `reset_token` VARCHAR(255) NULL DEFAULT NULL,
  `reset_token_expiry` DATETIME NULL DEFAULT NULL,
  `library_card` VARCHAR(16) NULL DEFAULT '',
  `library_card_date_issued` DATE NULL DEFAULT NULL
) ENGINE=MyISAM;





# Replace placeholder table for ri_magazine with correct view syntax
# ------------------------------------------------------------

DROP TABLE `ri_magazine`;

CREATE ALGORITHM=UNDEFINED DEFINER=`comp3700_ecc`@`127.0.0.1` SQL SECURITY DEFINER VIEW `ri_magazine`
AS SELECT
     `ri`.`id` AS `id`,
     `ri`.`title` AS `title`,
     `ri`.`summary` AS `summary`,
     `ri`.`category` AS `category`,
     `ri`.`date_published` AS `date_published`,
     `ri`.`date_added` AS `date_added`,
     `ri`.`status` AS `status`,count(`c`.`id`) AS `is_checked_out`,count(`r`.`id`) AS `is_reserved`,
     `rim`.`publication` AS `publication`,
     `rim`.`issue_number` AS `issue_number`
   FROM (((`rental_item_magazine` `rim` left join `rental_item` `ri` on((`ri`.`id` = `rim`.`id`))) left join `checkout` `c` on(((`c`.`rental_item` = `ri`.`id`) and isnull(`c`.`date_returned`)))) left join `reservation` `r` on(((`r`.`rental_item` = `ri`.`id`) and isnull(`r`.`checkout`))));


# Replace placeholder table for ri_book with correct view syntax
# ------------------------------------------------------------

DROP TABLE `ri_book`;

CREATE ALGORITHM=UNDEFINED DEFINER=`comp3700_ecc`@`127.0.0.1` SQL SECURITY DEFINER VIEW `ri_book`
AS SELECT
     `ri`.`id` AS `id`,
     `ri`.`title` AS `title`,
     `ri`.`summary` AS `summary`,
     `ri`.`category` AS `category`,
     `ri`.`date_published` AS `date_published`,
     `ri`.`date_added` AS `date_added`,
     `ri`.`status` AS `status`,count(`c`.`id`) AS `is_checked_out`,count(`r`.`id`) AS `is_reserved`,group_concat(`a`.`name_full` separator ', ') AS `authors`,
     `rib`.`isbn10` AS `isbn10`,
     `rib`.`isbn13` AS `isbn13`
   FROM ((((`rental_item_book` `rib` left join `rental_item` `ri` on((`ri`.`id` = `rib`.`id`))) left join `author` `a` on((`a`.`book` = `rib`.`id`))) left join `checkout` `c` on(((`c`.`rental_item` = `ri`.`id`) and isnull(`c`.`date_returned`)))) left join `reservation` `r` on(((`r`.`rental_item` = `ri`.`id`) and isnull(`r`.`checkout`)))) group by `ri`.`id`;


# Replace placeholder table for ri_dvd with correct view syntax
# ------------------------------------------------------------

DROP TABLE `ri_dvd`;

CREATE ALGORITHM=UNDEFINED DEFINER=`comp3700_ecc`@`127.0.0.1` SQL SECURITY DEFINER VIEW `ri_dvd`
AS SELECT
     `ri`.`id` AS `id`,
     `ri`.`title` AS `title`,
     `ri`.`summary` AS `summary`,
     `ri`.`category` AS `category`,
     `ri`.`date_published` AS `date_published`,
     `ri`.`date_added` AS `date_added`,
     `ri`.`status` AS `status`,count(`c`.`id`) AS `is_checked_out`,count(`r`.`id`) AS `is_reserved`,
     `rid`.`director` AS `director`
   FROM (((`rental_item_dvd` `rid` left join `rental_item` `ri` on((`ri`.`id` = `rid`.`id`))) left join `checkout` `c` on(((`c`.`rental_item` = `ri`.`id`) and isnull(`c`.`date_returned`)))) left join `reservation` `r` on(((`r`.`rental_item` = `ri`.`id`) and isnull(`r`.`checkout`))));


# Replace placeholder table for user_view with correct view syntax
# ------------------------------------------------------------

DROP TABLE `user_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`comp3700_ecc`@`127.0.0.1` SQL SECURITY DEFINER VIEW `user_view`
AS SELECT
     `u`.`id` AS `id`,
     `u`.`active` AS `active`,
     `u`.`privilege_level` AS `privilege_level`,
     `u`.`name_first` AS `name_first`,
     `u`.`name_last` AS `name_last`,
     `u`.`email` AS `email`,
     `u`.`phone` AS `phone`,
     `u`.`date_signed_up` AS `date_signed_up`,
     `u`.`gender` AS `gender`,
     `u`.`date_of_birth` AS `date_of_birth`,
     `u`.`address_line_1` AS `address_line_1`,
     `u`.`address_line_2` AS `address_line_2`,
     `u`.`address_zip` AS `address_zip`,
     `u`.`address_city` AS `address_city`,
     `u`.`address_state` AS `address_state`,
     `u`.`address_country_code` AS `address_country_code`,
     `u`.`password_hash` AS `password_hash`,
     `u`.`reset_token` AS `reset_token`,
     `u`.`reset_token_expiry` AS `reset_token_expiry`,
     `lc`.`number` AS `library_card`,
     `lc`.`date_issued` AS `library_card_date_issued`
   FROM (`user` `u` left join `library_card` `lc` on(((`u`.`id` = `lc`.`user`) and (`lc`.`status` = 1))));

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
