<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;

	class Book extends RentalItem {

		public function create($summary, $title, $category, $date_published, $status, $google_id, $isbn10,
		                       $isbn13, array $authors) {

			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $status, $google_id,
				$isbn10, $isbn13, $authors);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));
		}

		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category, $date_published, $status,
		                                    $google_id, $isbn10, $isbn13, array $authors) {
			$id = parent::createNew($_pdo, $summary, $title, $category, $date_published, $status);

			$arguments = ["id" => $id, "gi" => $google_id, "i10" => $isbn10, "i13" => $isbn13];

			$query = "INSERT INTO rental_item_book (id, google_id, isbn10, isbn13)
						VALUES (:id, :gi, :i10, :i13)";

			$_pdo->perform($query, $arguments);

			$book_id = $_pdo->lastInsertId();

			$author_ids = array();

			foreach ($authors as $author) {
				$arguments = ["au" => $author];

				$query = "INSERT INTO author (name_full) VALUES (:au)";

				$_pdo->perform($query, $arguments);

				$author_ids[] = $_pdo->lastInsertId();
			}

			$query = "INSERT INTO rel_rental_item_book_author (book, author)
						VALUES ";

			$data = array();

			foreach ($author_ids as $author_id) {
				$data[] = $book_id;
				$data[] = $author_id;

				$query .= "(?,?),";
			}
			$query = rtrim($query, ',');

			$_pdo->perform($query, $data);

			return $id;
		}
	}