/* ---------------------------------------------------------------------------------------------------------------------
 * 11-18-2015
 ---------------------------------------------------------------------------------------------------------------------*/

ALTER TABLE `checkout`
ADD CONSTRAINT `checkout_employee_id`
FOREIGN KEY (`checkout_employee`)
REFERENCES `user` (`id`);

ALTER TABLE `checkout`
ADD CONSTRAINT `checkin_employee_id`
FOREIGN KEY (`checkin_employee`)
REFERENCES `user` (`id`);

CREATE TABLE `reservation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL,
  `rental_item` int(11) unsigned NOT NULL,
  `date_created` date NOT NULL,
  `date_pickup` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservation_user_id` (`user`),
  KEY `reservation_rental_item_id` (`rental_item`),
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`rental_item`) REFERENCES `rental_item` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* ---------------------------------------------------------------------------------------------------------------------
 * 11-17-2015
 ---------------------------------------------------------------------------------------------------------------------*/

ALTER TABLE rental_item_book DROP google_id;
ALTER TABLE rental_item DROP COLUMN category;
DROP TABLE IF EXISTS category;
ALTER TABLE rental_item ADD COLUMN category VARCHAR(255) NOT NULL DEFAULT '' AFTER summary;

CREATE OR REPLACE VIEW `ri_book`
AS SELECT
     `ri`.`id` AS `id`,
     `ri`.`title` AS `title`,
     `ri`.`summary` AS `summary`,
     `ri`.`category` AS `category`,
     `ri`.`date_published` AS `date_published`,
     `ri`.`date_added` AS `date_added`,
     `ri`.`status` AS `status`,
     group_concat(`a`.`name_full` separator ', ') AS `authors`,
     `rib`.`isbn10` AS `isbn10`,
     `rib`.`isbn13` AS `isbn13`
   FROM (((`rental_item_book` `rib`
     left join `rental_item` `ri` on((`ri`.`id` = `rib`.`id`)))
     left join `rel_rental_item_book_author` `rriba` on((`rriba`.`book` = `rib`.`id`)))
     left join `author` `a` on((`rriba`.`author` = `a`.`id`))) group by `ri`.`id`;

CREATE OR REPLACE VIEW `ri_dvd`
AS SELECT
     `ri`.`id` AS `id`,
     `ri`.`title` AS `title`,
     `ri`.`summary` AS `summary`,
     `ri`.`category` AS `category`,
     `ri`.`date_published` AS `date_published`,
     `ri`.`date_added` AS `date_added`,
     `ri`.`status` AS `status`,
     `rid`.`director` AS `director`
   FROM (`rental_item_dvd` `rid`
     left join `rental_item` `ri` on((`ri`.`id` = `rid`.`id`)));

CREATE OR REPLACE VIEW `ri_magazine`
AS SELECT
     `ri`.`id` AS `id`,
     `ri`.`title` AS `title`,
     `ri`.`summary` AS `summary`,
     `ri`.`category` AS `category`,
     `ri`.`date_published` AS `date_published`,
     `ri`.`date_added` AS `date_added`,
     `ri`.`status` AS `status`,
     `rim`.`publication` AS `publication`,
     `rim`.`issue_number` AS `issue_number`
   FROM (`rental_item_magazine` `rim`
     left join `rental_item` `ri` on((`ri`.`id` = `rim`.`id`)));


/* ---------------------------------------------------------------------------------------------------------------------
 * 11-11-2015
 ---------------------------------------------------------------------------------------------------------------------*/

ALTER TABLE `user` MODIFY COLUMN reset_token_expiry DATETIME DEFAULT NULL;

CREATE OR REPLACE VIEW `user_view`
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
   FROM (`user` `u`
     left join `library_card` `lc` on(((`u`.`id` = `lc`.`user`) and (`lc`.`status` = 1))));