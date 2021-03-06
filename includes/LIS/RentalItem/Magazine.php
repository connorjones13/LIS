<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;

	class Magazine extends RentalItem {

		const TYPE = 2;

		protected $publication, $issue_number;

		public function create($summary, $title, $category, $date_published, $status, $publication = "", $issue_number = 0) {

			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $status, $publication,
				$issue_number);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));

		}

		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category, $date_published, $status,
		                                    $publication, $issue_number) {

			$id = parent::createNew($_pdo, $summary, $title, $category, $date_published, $status, self::TYPE);

			$arguments = ["id" => $id, "pu" => $publication, "inum" => $issue_number];

			$query = "INSERT INTO rental_item_magazine (id, publication, issue_number)
						VALUES (:id, :pu, :inum)";

			$_pdo->perform($query, $arguments);

//			$id = $_pdo->lastInsertId();

			return $id;
		}

		/**
		 * @return string
		 */
		public function getPublication() {
			return $this->publication;
		}

		public function getIssueNumber() {
			return $this->issue_number;
		}

		public function updatePublication($publication){
			$this->publication = $publication;

			$args = ["pu" => $this->publication, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_magazine SET publication = :pu WHERE id = :id", $args);
		}

		public function updateIssueNumber($issue_number) {
			$this->issue_number = $issue_number;

			$args = ["inum" => $this->issue_number, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_magazine SET issue_number = :inum WHERE id = :id", $args);
		}

		public function updateMagazine($summary, $title, $category, $date_published, $status, $publication, $issue_number) {

			self::updateRentalItem($summary, $title, $category, $date_published, $status);

			$this->publication = $publication;
			$this->issue_number = $issue_number;

			$args = ["inum" => $issue_number, "pu" => $publication, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_magazine SET issue_number = :inum, publication = :pu WHERE id = :id", $args);

		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param $column
		 * @param $value
		 * @return array
		 */
		protected static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
			$args = ["val" => $value];

			return $_pdo->fetchOne("SELECT * FROM `ri_magazine` WHERE $column = :val", $args);
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			$row = self::findRowBy($_pdo, "id", $id);

			return $row ? new Magazine($_pdo, $row) : null;
		}

		public static function getAllByCategory(PDO_MySQL $_pdo, $category) {
			$args = ["val" => $category];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_magazine` WHERE `category` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new Magazine($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return Magazine[]
		 */
		public static function getAllAvailable(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_magazine` ri
					    LEFT JOIN checkout c
					      ON ri.id = c.rental_item AND c.checkin_employee IS NULL
					    LEFT JOIN reservation r
					      ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
					  WHERE ri.`status` = 0 AND c.id IS NULL AND r.id IS NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new Magazine($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return Magazine[]
		 */
		public static function getAllCheckedOut(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_magazine` ri
					    LEFT JOIN checkout c
					      ON ri.id = c.rental_item AND c.checkin_employee IS NULL
					  WHERE ri.`status` = 0 AND c.id IS NOT NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new Magazine($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return Magazine[]
		 */
		public static function getAllReserved(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_magazine` ri
					    LEFT JOIN reservation r
					      ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
					  WHERE ri.`status` = 0 AND r.id IS NOT NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new Magazine($_pdo, $row);
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
		 * @return Magazine[]
		 */
		protected static function getAllByStatus(PDO_MySQL $_pdo, $status) {
			$args = ["val" => $status];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_magazine` WHERE `status` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new Magazine($_pdo, $row);
			}, $rows);
		}
	}