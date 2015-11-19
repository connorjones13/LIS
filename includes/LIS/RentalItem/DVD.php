<?php

	namespace LIS\RentalItem;
	use LIS\Database\PDO_MySQL;

	class DVD extends RentalItem {

		protected $director;

		public function create($summary, $title, $category, $date_published, $status, $director = "") {

			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $status, $director);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));
		}

		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category,
		                                    $date_published, $status, $director) {

			$id = parent::createNew($_pdo, $summary, $title, $category, $date_published, $status);

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

		public static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
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

		public static function getAllByStatus(PDO_MySQL $_pdo, $status) {
			$args = ["val" => $status];
			$rows = $_pdo->fetchAssoc("SELECT * FROM `ri_dvd` WHERE `status` = :val", $args);

			return array_map(function ($row) use ($_pdo) {
				return new DVD($_pdo, $row);
			}, $rows);
		}

	}