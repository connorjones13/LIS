<?php

	namespace LIS\RentalItem;
	use LIS\Database\PDO_MySQL;

	class DVD extends RentalItem {

		const TYPE = 1;

		protected $director;

		public function create($summary, $title, $category, $date_published, $status, $director = "") {

			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $status, $director);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));
		}

		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category,
		                                    $date_published, $status, $director) {

			$id = parent::createNew($_pdo, $summary, $title, $category, $date_published, $status, self::TYPE);

			$arguments = ["id" => $id, "dir" => $director];

			$query = "INSERT INTO rental_item_dvd (id, director) VALUES (:id, :dir)";

			$_pdo->perform($query, $arguments);

			return $_pdo->lastInsertId();
		}

		public function getDirector() {
			return $this->director;
		}

		public function updateDirector($director) {
			$this->director = $director;

			$args = ["dir" => $director, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_dvd SET director = :dir WHERE id = :id", $args);
		}

		public function updateDvd($summary, $title, $category, $date_published, $status, $director) {

			self::updateRentalItem($summary, $title, $category, $date_published, $status);

			$this->director = $director;

			$args = ["dir" => $director, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item_dvd SET director = :dir WHERE id = :id", $args);

		}

		protected static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
			$args = ["val" => $value];

			return $_pdo->fetchOne("SELECT * FROM `ri_dvd` WHERE $column = :val", $args);
		}


		public static function find(PDO_MySQL $_pdo, $id) {
			$row = self::findRowBy($_pdo, "id", $id);

			return $row ? new DVD($_pdo, $row) : null;
		}

		public static function getAllByCategory(PDO_MySQL $_pdo, $category) {
			$args = ["val" => $category];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_dvd` WHERE `category` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new DVD($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return DVD[]
		 */
		public static function getAllAvailable(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_dvd` ri
					    LEFT JOIN checkout c
					      ON ri.id = c.rental_item AND c.checkin_employee IS NULL
					    LEFT JOIN reservation r
					      ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
					  WHERE ri.`status` = 0 AND c.id IS NULL AND r.id IS NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new DVD($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return DVD[]
		 */
		public static function getAllCheckedOut(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_dvd` ri
					    LEFT JOIN checkout c
					      ON ri.id = c.rental_item AND c.checkin_employee IS NULL
					  WHERE ri.`status` = 0 AND c.id IS NOT NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new DVD($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return DVD[]
		 */
		public static function getAllReserved(PDO_MySQL $_pdo) {
			$query = "SELECT ri.* FROM `ri_dvd` ri
					    LEFT JOIN reservation r
					      ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
					  WHERE ri.`status` = 0 AND r.id IS NOT NULL";
			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return new DVD($_pdo, $row);
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
		 * @return DVD[]
		 */
		protected static function getAllByStatus(PDO_MySQL $_pdo, $status) {
			$args = ["val" => $status];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_dvd` WHERE `status` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new DVD($_pdo, $row);
			}, $rows);
		}
	}