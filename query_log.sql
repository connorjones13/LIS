/* ---------------------------------------------------------------------------------------------------------------------
 * 11-23-2015
 ---------------------------------------------------------------------------------------------------------------------*/

ALTER TABLE checkout ADD CONSTRAINT fk_co_emp_id FOREIGN KEY (checkout_employee) REFERENCES user(id);

UPDATE rental_item_book rie LEFT JOIN rental_item ri ON rie.id = ri.id SET ri.type = 0;
UPDATE rental_item_dvd rie LEFT JOIN rental_item ri ON rie.id = ri.id SET ri.type = 1;
UPDATE rental_item_magazine rie LEFT JOIN rental_item ri ON rie.id = ri.id SET ri.type = 2;

CREATE OR REPLACE VIEW `ri_book` AS
  SELECT
    `ri`.`id`             AS `id`,
    `ri`.`type`           AS `type`,
    `ri`.`title`          AS `title`,
    `ri`.`summary`        AS `summary`,
    `ri`.`category`       AS `category`,
    `ri`.`date_published` AS `date_published`,
    `ri`.`date_added`     AS `date_added`,
    `ri`.`status`         AS `status`,
    (c.id IS NOT NULL)    AS `is_checked_out`,
    (r.id IS NOT NULL)    AS `is_reserved`,
    `rib`.`isbn10`        AS `isbn10`,
    `rib`.`isbn13`        AS `isbn13`
  FROM (((`rental_item` `ri`
    RIGHT JOIN `rental_item_book` `rib` ON ((`ri`.`id` = `rib`.`id`)))
    LEFT JOIN `checkout` `c` ON (((`c`.`rental_item` = `ri`.`id`) AND isnull(`c`.`date_returned`))))
    LEFT JOIN `reservation` `r` ON (((`r`.`rental_item` = `ri`.`id`) AND isnull(`r`.`checkout`))))
  GROUP BY `ri`.`id`;

CREATE OR REPLACE VIEW `ri_dvd` AS
  SELECT
    `ri`.`id`             AS `id`,
    `ri`.`type`           AS `type`,
    `ri`.`title`          AS `title`,
    `ri`.`summary`        AS `summary`,
    `ri`.`category`       AS `category`,
    `ri`.`date_published` AS `date_published`,
    `ri`.`date_added`     AS `date_added`,
    `ri`.`status`         AS `status`,
    (c.id IS NOT NULL)    AS `is_checked_out`,
    (r.id IS NOT NULL)    AS `is_reserved`,
    `rid`.`director`      AS `director`
  FROM (((`rental_item` `ri`
    RIGHT JOIN `rental_item_dvd` `rid` ON ((`ri`.`id` = `rid`.`id`)))
    LEFT JOIN `checkout` `c` ON (((`c`.`rental_item` = `ri`.`id`) AND isnull(`c`.`date_returned`))))
    LEFT JOIN `reservation` `r` ON (((`r`.`rental_item` = `ri`.`id`) AND isnull(`r`.`checkout`))))
  GROUP BY `ri`.`id`;

CREATE OR REPLACE VIEW `ri_magazine` AS
  SELECT
    `ri`.`id`             AS `id`,
    `ri`.`type`           AS `type`,
    `ri`.`title`          AS `title`,
    `ri`.`summary`        AS `summary`,
    `ri`.`category`       AS `category`,
    `ri`.`date_published` AS `date_published`,
    `ri`.`date_added`     AS `date_added`,
    `ri`.`status`         AS `status`,
    (c.id IS NOT NULL)    AS `is_checked_out`,
    (r.id IS NOT NULL)    AS `is_reserved`,
    `rim`.`publication`   AS `publication`,
    `rim`.`issue_number`  AS `issue_number`
  FROM (((`rental_item` `ri`
    RIGHT JOIN `rental_item_magazine` `rim` ON ((`ri`.`id` = `rim`.`id`)))
    LEFT JOIN `checkout` `c` ON (((`c`.`rental_item` = `ri`.`id`) AND isnull(`c`.`date_returned`))))
    LEFT JOIN `reservation` `r` ON (((`r`.`rental_item` = `ri`.`id`) AND isnull(`r`.`checkout`))))
  GROUP BY `ri`.`id`;

/* ---------------------------------------------------------------------------------------------------------------------
 * 11-19-2015
 ---------------------------------------------------------------------------------------------------------------------*/

ALTER TABLE rental_item ADD type int DEFAULT 0 NOT NULL AFTER id;

CREATE OR REPLACE VIEW `ri_magazine` AS SELECT
    `ri`.`id`             AS `id`,
    `ri`.`type`           AS `type`,
    `ri`.`title`          AS `title`,
    `ri`.`summary`        AS `summary`,
    `ri`.`category`       AS `category`,
    `ri`.`date_published` AS `date_published`,
    `ri`.`date_added`     AS `date_added`,
    `ri`.`status`         AS `status`,
    count(`c`.`id`)       AS `is_checked_out`,
    count(`r`.`id`)       AS `is_reserved`,
    `rim`.`publication`   AS `publication`,
    `rim`.`issue_number`  AS `issue_number`
  FROM (((`rental_item_magazine` `rim`
    RIGHT JOIN `rental_item` `ri` ON ((`ri`.`id` = `rim`.`id`)))
    LEFT JOIN `checkout` `c` ON (((`c`.`rental_item` = `ri`.`id`) AND isnull(`c`.`date_returned`))))
    LEFT JOIN `reservation` `r` ON (((`r`.`rental_item` = `ri`.`id`) AND isnull(`r`.`checkout`))))
  GROUP BY `ri`.`id`;

CREATE OR REPLACE VIEW `ri_book` AS SELECT
    `ri`.`id`                                    AS `id`,
    `ri`.`type`                                  AS `type`,
    `ri`.`title`                                 AS `title`,
    `ri`.`summary`                               AS `summary`,
    `ri`.`category`                              AS `category`,
    `ri`.`date_published`                        AS `date_published`,
    `ri`.`date_added`                            AS `date_added`,
    `ri`.`status`                                AS `status`,
    count(`c`.`id`)                              AS `is_checked_out`,
    count(`r`.`id`)                              AS `is_reserved`,
    group_concat(`a`.`name_full` SEPARATOR ', ') AS `authors`,
    `rib`.`isbn10`                               AS `isbn10`,
    `rib`.`isbn13`                               AS `isbn13`
  FROM ((((`rental_item_book` `rib`
    RIGHT JOIN `rental_item` `ri` ON ((`ri`.`id` = `rib`.`id`)))
    LEFT JOIN `author` `a` ON ((`a`.`book` = `rib`.`id`)))
    LEFT JOIN `checkout` `c` ON (((`c`.`rental_item` = `ri`.`id`) AND isnull(`c`.`date_returned`))))
    LEFT JOIN `reservation` `r` ON (((`r`.`rental_item` = `ri`.`id`) AND isnull(`r`.`checkout`))))
  GROUP BY `ri`.`id`;

CREATE OR REPLACE VIEW `ri_dvd` AS SELECT
    `ri`.`id`             AS `id`,
    `ri`.`type`           AS `type`,
    `ri`.`title`          AS `title`,
    `ri`.`summary`        AS `summary`,
    `ri`.`category`       AS `category`,
    `ri`.`date_published` AS `date_published`,
    `ri`.`date_added`     AS `date_added`,
    `ri`.`status`         AS `status`,
    count(`c`.`id`)       AS `is_checked_out`,
    count(`r`.`id`)       AS `is_reserved`,
    `rid`.`director`      AS `director`
  FROM (((`rental_item_dvd` `rid`
    RIGHT JOIN `rental_item` `ri` ON ((`ri`.`id` = `rid`.`id`)))
    LEFT JOIN `checkout` `c` ON (((`c`.`rental_item` = `ri`.`id`) AND isnull(`c`.`date_returned`))))
    LEFT JOIN `reservation` `r` ON (((`r`.`rental_item` = `ri`.`id`) AND isnull(`r`.`checkout`))))
  GROUP BY `ri`.`id`;