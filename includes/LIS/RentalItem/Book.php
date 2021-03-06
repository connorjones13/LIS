<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;
	use LIS\Utility;

	class Book extends RentalItem {

		const TYPE = 0;

		protected $isbn10, $isbn13;


		public function create($summary, $title, $category, $date_published, $status, $isbn10 = "",
		                       $isbn13 = "", array $authors = []) {

			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $status,
				$isbn10, $isbn13);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));

			Author::createNewForBook($this->_pdo, $this, $authors);
		}

		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category, $date_published, $status,
		                                    $isbn10, $isbn13) {
			$id = parent::createNew($_pdo, $summary, $title, $category, $date_published, $status, self::TYPE);

			$arguments = ["id" => $id, "i10" => $isbn10, "i13" => $isbn13];

			$query = "INSERT INTO rental_item_book (id, isbn10, isbn13) VALUES (:id, :i10, :i13)";

			$_pdo->perform($query, $arguments);

			return $id;
		}

		public function getISBN10() {
			return $this->isbn10;
		}

		public function getISBN13() {
			return $this->isbn13;
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

		/**
		 * @param $summary
		 * @param $title
		 * @param $category
		 * @param $date_published
		 * @param $status
		 * @param string $isbn10
		 * @param string $isbn13
		 * @param array $authors
		 */
		public function updateBook($summary, $title, $category, $date_published, $status, $isbn10 = "",
		                           $isbn13 = "", array $authors = []) {

			self::updateRentalItem($summary, $title, $category, $date_published, $status);

			$this->isbn10 = $isbn10;
			$this->isbn13 = $isbn13;
			Author::deleteAllForBook($this->_pdo, $this);       // remove authors since we're replacing them

			$args = ["is10" => $isbn10, "is13" => $isbn13, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_book SET isbn10 = :is10, isbn13 = :is13 WHERE id = :id", $args);

			Author::createNewForBook($this->_pdo, $this, $authors); // add updated authors for book
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param $column
		 * @param $value
		 * @return array
		 */
		protected static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
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

		/**
		 * @param PDO_MySQL $_pdo
		 * @return Book[]
		 */
		public static function getAllAvailable(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_book` ri
					    LEFT JOIN checkout c
					      ON ri.id = c.rental_item AND c.checkin_employee IS NULL
					    LEFT JOIN reservation r
					      ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
					  WHERE ri.`status` = 0 AND c.id IS NULL AND r.id IS NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new Book($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return Book[]
		 */
		public static function getAllCheckedOut(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_book` ri
					    LEFT JOIN checkout c
					      ON ri.id = c.rental_item AND c.checkin_employee IS NULL
					  WHERE ri.`status` = 0 AND c.id IS NOT NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new Book($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return Book[]
		 */
		public static function getAllReserved(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_book` ri
					    LEFT JOIN reservation r
					      ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
					  WHERE ri.`status` = 0 AND r.id IS NOT NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new Book($_pdo, $row);
			}, $rows);
		}

		public static function getAllLost(PDO_MySQL $_pdo) {
			return self::getAllByStatus($_pdo, self::STATUS_LOST);
		}

		public static function getAllDamaged(PDO_MySQL $_pdo) {
			return self::getAllByStatus($_pdo, self::STATUS_DAMAGED);
		}

		public static function getAllRemoved(PDO_MySQL $_pdo) {
			return self::getAllByStatus($_pdo, self::STATUS_REMOVED);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param $status
		 * @return Book[]
		 */
		protected static function getAllByStatus(PDO_MySQL $_pdo, $status) {
			$args = ["val" => $status];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_book` WHERE `status` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new Book($_pdo, $row);
			}, $rows);
		}

		protected function parse(array $data_arr) {
			parent::parse($data_arr);
		}
	}