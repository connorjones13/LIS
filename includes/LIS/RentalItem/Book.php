<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;

	class Book extends RentalItem {

		protected $isbn10, $isbn13, $authors;

		public function create($summary, $title, $category, $date_published, $status, $isbn10,
		                       $isbn13, array $authors) {

			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $status,
				$isbn10, $isbn13, $authors);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));
		}

		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category, $date_published, $status,
		                                    $isbn10, $isbn13, array $authors) {
			$id = parent::createNew($_pdo, $summary, $title, $category, $date_published, $status);

			$arguments = ["id" => $id, "i10" => $isbn10, "i13" => $isbn13];

			$query = "INSERT INTO rental_item_book (id, isbn10, isbn13)
						VALUES (:id, :i10, :i13)";

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
						VALUES ()";

			$query = rtrim($query, '()');

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

		public function getISBN10() {
			return $this->isbn10;
		}

		public function getISBN13() {
			return $this->isbn13;
		}

		public function getAuthors() {
			return $this->authors;
		}

		public function updateISBN10($isbn10){
			$this->isbn10 = $isbn10;

			$args = ["isbn" => $this->isbn10, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_book SET isbn10 = :isbn WHERE id = :id", $args);
		}

		public function updateISBN13($isbn13){
			$this->isbn13 = $isbn13;

			$args = ["isbn" => $this->isbn13, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_book SET isbn13 = :isbn WHERE id = :id", $args);
		}

		public function updateAuthors($authors){
			$this->authors = $authors;


			// todo: figure out logic to override authors (also account for removing an author or adding additional author)
		}

		public static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
			$args = ["val" => $value];

			return $_pdo->fetchOne("SELECT * FROM `ri_book` WHERE $column = :val", $args);
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			$row = self::findRowBy($_pdo, "id", $id);

			return $row ? new Book($_pdo, $row) : null;
		}

		public static function getAllByCategory(PDO_MySQL $_pdo, $category) {
			$args = ["val" => $category];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_book` WHERE `category` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new Book($_pdo, $row);
			}, $rows);
		}

		public static function getAllByStatus(PDO_MySQL $_pdo, $status) {
			$args = ["val" => $status];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_book` WHERE `status` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new Book($_pdo, $row);
			}, $rows);
		}

		protected function parse(array $data_arr) {
			parent::parse($data_arr);

			$this->authors = explode(", ", $data_arr["authors"]);
		}
	}