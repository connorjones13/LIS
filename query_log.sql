CREATE OR REPLACE VIEW ri_all AS
  SELECT
    ri.*,
    (`c`.`id` IS NOT NULL) AS `is_checked_out`,
    (`r`.`id` IS NOT NULL) AS `is_reserved`,
    rib.isbn10,
    rib.isbn13,
    rid.director,
    rim.publication,
    rim.issue_number
  FROM rental_item ri
    LEFT JOIN rental_item_book rib ON ri.id = rib.id
    LEFT JOIN rental_item_dvd rid ON ri.id = rid.id
    LEFT JOIN rental_item_magazine rim ON ri.id = rim.id
    LEFT JOIN checkout c ON ri.id = c.rental_item AND c.checkin_employee IS NULL
    LEFT JOIN reservation r ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
  GROUP BY ri.id;