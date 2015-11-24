<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;
	use DateTime;
	use LIS\Utility;

	abstract class RentalItem {

		const STATUS_AVAILABLE = 0;
		const STATUS_LOST = 1;
		const STATUS_DAMAGED = 2;
		const STATUS_REMOVED = 3;
		const STATUS_CHECKED_OUT = 4;
		const STATUS_RESERVED = 5;

		protected $id, $summary, $title, $category, $date_published, $date_added, $type, $status,
			$is_checked_out, $is_reserved;

		/* @var PDO_MySQL $_pdo */
		protected $_pdo; //Since this is an internal dependency, I mark it with an _

		/**
		 * RentalItem constructor. Takes a database object as a dependency and an optional
		 * array parameter to initialize the RentalItem object with data.
		 * @param PDO_MySQL $_pdo
		 * @param array $data_arr
		 */
		public function __construct(PDO_MySQL $_pdo, array $data_arr = array()) {
			$this->_pdo = $_pdo;

			if (!empty($data_arr))
				$this->parse($data_arr);
		}

		/**
		 * The above constructor and the below debugInfo methods are special methods
		 * called Magic Methods. All PHPs magic methods start with __ and are run as
		 * specific events occur.
		 */

		/**
		 * Keeps unwanted data out of var_dump functions. Here we are hiding the pdo
		 * variable from printing out since it contains our connection data and also
		 * happens to be unnecessary for debugging the RentalItem object.
		 */
		function __debugInfo() {
			$rental_object = get_object_vars($this);
			unset($rental_object["_pdo"]);

			return $rental_object;
		}

		/**
		 * @param string $summary
		 * @param string $title
		 * @param int $category
		 * @param DateTime $date_published
		 * @param int $status
		 */
		abstract public function create($summary, $title, $category, $date_published, $status);

		/**
		 * @param PDO_MySQL $_pdo
		 * @param string $summary
		 * @param string $title
		 * @param int $category
		 * @param DateTime $date_published
		 * @param int $status
		 * @param int $type
		 * @return int
		 */
		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category, $date_published, $status, $type) {
			$time = Utility::getDateTimeForMySQLDateTime();

			$arguments = ["su" => $summary, "ti" => $title, "ca" => $category,
					"dp" => Utility::getDateTimeForMySQLDate($date_published),
					"da" => $time, "st" => $status, "tp" => $type];

			$query = "INSERT INTO rental_item (summary, title, category, date_published, date_added, status, type)
						VALUES (:su, :ti, :ca, :dp, :da, :st, :tp)";

			$_pdo->perform($query, $arguments);

			$id = $_pdo->lastInsertId();

			return $id;
		}

		/**
		 * @return int
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * @return string
		 */
		public function getSummary() {
			return $this->summary;
		}

		/**
		 * @return string
		 */
		public function getTitle() {
			return $this->title;
		}

		/**
		 * @return int
		 */
		public function getCategory() {
			return $this->category;
		}

		/**
		 * @return DateTime
		 */
		public function getDatePublished() {
			return Utility::getDateTimeFromMySQLDate($this->date_published);
		}

		public function getDateAdded() {
			return Utility::getDateTimeFromMySQLDate($this->date_added);
		}

		/**
		 * @return int
		 */
		public function getStatus() {
			if ($this->is_checked_out)
				return self::STATUS_CHECKED_OUT;

			if ($this->is_reserved)
				return self::STATUS_RESERVED;

			return $this->status;
		}

		/**
		 * @param $title
		 */
		public function updateTitle($title) {
			$this->title = $title;

			$args = ["ti" => $this->title, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item SET title = :ti WHERE id = :id", $args);

		}

		/**
		 * @param $summary
		 */
		public function updateSummary($summary){
			$this->summary = $summary;

			$args = ["su" => $this->summary, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item SET summary = :su WHERE id = :id", $args);
		}

		/**
		 * Database not updated since the database is updated on record creation.
		 */
		public function markAvailable() {
			$this->is_checked_out = 0;
			$this->is_reserved = 0;
		}

		/**
		 * Database not updated since the database is updated on record creation.
		 */
		public function markCheckedOut() {
			$this->is_checked_out = 1;
		}

		/**
		 * Database not updated since the database is updated on record creation.
		 */
		public function markReserved() {
			$this->is_reserved = 1;
		}

		/**
		 * Updates database since this status is tracked in the table itself.
		 */
		public function markLost() {
			$this->status = self::STATUS_LOST;

			self::updateStatus($this->status);
		}

		/**
		 * Updates database since this status is tracked in the table itself.
		 */
		public function markDestroyed() {
			$this->status = self::STATUS_LOST;

			self::updateStatus($this->status);
		}

		/**
		 * Updates database since this status is tracked in the table itself.
		 */
		public function markRemoved() {
			$this->status = self::STATUS_REMOVED;

			self::updateStatus($this->status);
		}

		public function isBook() {
			return $this->type == Book::TYPE;
		}

		public function isMagazine() {
			return $this->type == Magazine::TYPE;
		}

		public function isDVD() {
			return $this->type == DVD::TYPE;
		}

		/**
		 * Used to update status of an item.
		 * @param int $status
		 */
		protected function updateStatus($status) {
			$args = ["st" => $status, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item SET status = :st WHERE id = :id", $args);
		}

		/**
		 * @param $date_published
		 */
		public function updateDatePublished($date_published) {
			$this->date_published = $date_published;

			$args = ["dp" => $this->date_published, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item SET date_published = :dp WHERE id = :id", $args);
		}

		/**
		 * @param $category
		 */
		public function updateCategory($category) {
			$this->category = $category;

			$args = ["ca" => $this->category, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item SET category = :ca WHERE id = :id", $args);
		}

		public function updateRentalItem($summary, $title, $category, $date_published, $status) {
			$this->summary = $summary;
			$this->title = $title;
			$this->category = $category;
			$this->date_published = $date_published;
			$this->status = $status;

			$args = ["su" => $summary, "ti" => $title, "ca" => $category,
					"dp" => Utility::getDateTimeForMySQLDate($date_published), "st" => $status, "id" => $this->id];
			$this->_pdo->perform("UPDATE rental_item SET summary = :su, title = :ti, category = :ca,
				date_published = :dp, status = :st WHERE id = :id", $args);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param $column
		 * @param $value
		 * @return array
		 */
		protected static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
			$args = ["val" => $value];

			return $_pdo->fetchOne("SELECT * FROM `rental_item` WHERE $column = :val", $args);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return int
		 */
		public static function getAllItemCount(PDO_MySQL $_pdo) {
			return intval($_pdo->fetchOne("SELECT COUNT(`id`) AS `count` FROM `rental_item`")["count"]);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return int
		 */
		public static function getAvailableItemCount(PDO_MySQL $_pdo) {
			$query = "SELECT COUNT(ri.id) as `count` FROM `rental_item` ri
						LEFT JOIN checkout c
						    ON ri.id = c.rental_item AND c.checkin_employee IS NULL
						  LEFT JOIN reservation r
						    ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
						WHERE ri.`status` = :s AND c.id IS NULL AND r.id IS NULL";

			return intval($_pdo->fetchOne($query, ["s" => self::STATUS_AVAILABLE])["count"]);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return int
		 */
		public static function getDamagedItemCount(PDO_MySQL $_pdo) {
			return intval($_pdo->fetchOne("SELECT COUNT(`id`) AS `count` FROM `rental_item` WHERE `status` = :s", [
				"s" => self::STATUS_DAMAGED
			])["count"]);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return int
		 */
		public static function getLostItemCount(PDO_MySQL $_pdo) {
			return intval($_pdo->fetchOne("SELECT COUNT(`id`) AS `count` FROM `rental_item` WHERE `status` = :s", [
				"s" => self::STATUS_LOST
			])["count"]);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return int
		 */
		public static function getRemovedItemCount(PDO_MySQL $_pdo) {
			return intval($_pdo->fetchOne("SELECT COUNT(`id`) AS `count` FROM `rental_item` WHERE `status` = :s", [
				"s" => self::STATUS_REMOVED
			])["count"]);
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			$query = "SELECT ri.*, rib.isbn10, rib.isbn13, rid.director, rim.publication,
					    rim.issue_number FROM rental_item ri
					    LEFT JOIN rental_item_book rib ON ri.id = rib.id
					    LEFT JOIN rental_item_dvd rid ON ri.id = rid.id
					    LEFT JOIN rental_item_magazine rim ON ri.id = rim.id
					  WHERE ri.id = :id
					  GROUP BY ri.id";

			$row = $_pdo->fetchOne($query, ["id" => $id]);
			return $row ? self::getInstance($_pdo, $row) : null;
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return Book[]|Magazine[]|DVD[]
		 */
		public static function getAllAvailable(PDO_MySQL $_pdo) {
			$query = "SELECT ri.*, 0 as is_checked_out, 0 as is_reserved, rib.isbn10, rib.isbn13, rid.director,
					    rim.publication, rim.issue_number FROM rental_item ri
					    LEFT JOIN rental_item_book rib ON ri.id = rib.id
					    LEFT JOIN rental_item_dvd rid ON ri.id = rid.id
					    LEFT JOIN rental_item_magazine rim ON ri.id = rim.id
					    LEFT JOIN checkout c ON ri.id = c.rental_item AND c.checkin_employee IS NULL
					    LEFT JOIN reservation r ON ri.id = r.rental_item AND r.checkout IS NULL AND r.is_expired = 0
					  WHERE ri.status = 0 AND c.id IS NULL AND r.id IS NULL
					  GROUP BY ri.id";

			$rows = $_pdo->fetchAssoc($query);

			return array_map(function ($row) use ($_pdo) {
				return self::getInstance($_pdo, $row);
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
		 * @return Book[]|Magazine[]|DVD[]
		 */
		protected static function getAllByStatus(PDO_MySQL $_pdo, $status) {
			$query = "SELECT ri.*, rib.isbn10, rib.isbn13, rid.director, rim.publication,
					    rim.issue_number FROM rental_item ri
					    LEFT JOIN rental_item_book rib ON ri.id = rib.id
					    LEFT JOIN rental_item_dvd rid ON ri.id = rid.id
					    LEFT JOIN rental_item_magazine rim ON ri.id = rim.id
					  WHERE ri.status = :s
					  GROUP BY ri.id";
			$args = ["s" => $status];

			$rows = $_pdo->fetchAssoc($query, $args);

			return array_map(function ($row) use ($_pdo) {
				return self::getInstance($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param array $row
		 * @return Book|DVD|Magazine
		 */
		protected static function getInstance(PDO_MySQL $_pdo, array $row) {
			if (!$row || !isset($row["type"]))
				return null;

			switch ($row["type"]) {
				case Book::TYPE:
					return new Book($_pdo, $row);
				case DVD::TYPE:
					return new DVD($_pdo, $row);
				case Magazine::TYPE:
					return new Magazine($_pdo, $row);
				default:
					return null;
			}
		}

		/**
		 * This function takes the data that a query returns and parses it into the
		 * variables of the class. It iterates over the values in $data_arr finding
		 * the two parts, the $key and the $value. Since the variables of the class
		 * and the columns in the database have the same name, they can be purposed
		 * to find the matching variables within the object and set their values to
		 * the value of the row in the database.
		 * @param array $data_arr
		 */
		protected function parse(array $data_arr) {
			foreach ($data_arr as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}