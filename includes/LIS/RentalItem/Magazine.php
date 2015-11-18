<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;

	class Magazine extends RentalItem {

		protected $publication, $issue_number;

		public function create($summary, $title, $category, $date_published, $status, $publication, $issue_number) {

			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $status, $publication,
				$issue_number);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));

		}

		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category, $date_published, $status,
		                                    $publication, $issue_number) {

			$id = parent::createNew($_pdo, $summary, $title, $category, $date_published, $status);

			$arguments = ["id" => $id, "pu" => $publication, "inum" => $issue_number];

			$query = "INSERT INTO rental_item_magazine (id, publication, issue_number)
						VALUES (:id, :pu, :inum)";

			$_pdo->perform($query, $arguments);

			$id = $_pdo->lastInsertId();

			return $id;
		}

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

		public static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
			$args = ["val" => $value];

			return $_pdo->fetchOne("SELECT * FROM `ri_magazine` WHERE $column = :val", $args);
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			$row = self::findRowBy($_pdo, "id", $id);

			return $row ? new Magazine($_pdo, $row) : null;
		}



		// TODO: find methods

	}