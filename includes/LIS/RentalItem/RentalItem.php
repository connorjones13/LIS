<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;
	use DateTime;
	use LIS\Utility;

	class RentalItem {

		protected $id, $summary, $title, $category, $date_published, $date_added, $status;

		/* @var PDO_MySQL $_pdo */
		protected $_pdo; //Since this is an internal dependency, I mark it with an _

		/**
		 * RentalItem constructor. Takes a database object as a dependency and an optional
		 * array parameter to initialize the User object with data.
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
		 * happens to be unnecessary for debugging the User object.
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
		 * @param DateTime $date_added
		 * @param int $status
		 */
		public function create($summary, $title, $category, $date_published, $date_added, $status){
			$id = self::createNew($this->_pdo, $summary, $title, $category, $date_published, $date_added, $status);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param string $summary
		 * @param string $title
		 * @param int $category
		 * @param DateTime $date_published
		 * @param DateTime $date_added
		 * @param int $status
		 * @return int
		 */
		protected static function createNew(PDO_MySQL $_pdo, $summary, $title, $category,
		                                    $date_published, $date_added, $status){

			$time = Utility::getDateTimeForMySQLDate();

			$arguments = ["su" => $summary, "ti" => $title, "ca" => $category, "dp" => $date_published,
				"da" => $time, "st" => $status];

			$query = "INSERT INTO rental_item (summary, title, category, date_published, date_added, status)
						VALUES (:su, :ti, :ca, :dp, :da, :st)";

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
			return $this->date_published;
		}

		public function getDateAdded() {
			return $this->date_added;
		}

		/**
		 * @return int
		 */
		public function getStatus() {
			//todo: switch statement?

			return $this->status;

		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param $column
		 * @param $value
		 * @return array
		 */
		public static function findRowBy(PDO_MySQL $_pdo, $column, $value) {

			$args = ["val" => $value];
			return $_pdo->fetchOne("SELECT * FROM `rental_item` WHERE $column = :val", $args);
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			return new RentalItem($_pdo, self::findRowBy($_pdo, "id", $id));
		}

		public static function findByTitle(PDO_MySQL $_pdo, $title) {

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