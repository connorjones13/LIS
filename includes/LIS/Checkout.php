<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/18/15
 * Time: 9:35 PM
 */

	namespace LIS;

	use DateInterval;
	use DateTime;
	use LIS\Database\PDO_MySQL;
	use LIS\RentalItem\RentalItem;
	use LIS\User\Employee;
	use LIS\User\User;

	class Checkout {


		private $id, $checkout_employee, $checkin_employee, $user, $rental_item, $date_due,
				$date_returned, $date_checked_out;

		/* @var PDO_MySQL $_pdo */
		private $_pdo; //Since this is an internal dependency, I mark it with an _

		/* @var User $_user */
		private $_user;

		/* @var RentalItem $_rental_item*/
		private $_rental_item;

		/* @var Employee $_co_employee */
		private $_co_employee;

		/* @var Employee $_ci_employee */
		private $_ci_employee;

		/**
		 * Checkout constructor. Takes a database object as a dependency and an optional
		 * array parameter to initialize the Checkout object with data.
		 * @param PDO_MySQL $_pdo
		 * @param array $data_arr
		 */
		public function __construct(PDO_MySQL $_pdo, array $data_arr = array()) {
			$this->_pdo = $_pdo;

			if (!empty($data_arr))
				$this->parse($data_arr);
		}

		protected function create(Employee $checkout_employee, User $user,
		                                 RentalItem $rental_item) {

			$this->date_due = new DateTime();
			$this->date_due->add(new DateInterval("7 days"));
			$this->date_due = Utility::getDateTimeForMySQLDate($this->date_due);

			$this->date_checked_out = Utility::getDateTimeForMySQLDateTime();

			$this->checkout_employee = $checkout_employee->getId();
			$this->user = $user->getId();
			$this->rental_item = $rental_item->getId();


			// save objects
			$this->_user = $user;
			$this->_co_employee= $checkout_employee;
			$this->_rental_item = $rental_item;

			$args = ["co_emp" => $checkout_employee->getId(),
				"usr" => $user->getId(), "ri" => $rental_item->getId(), "dd" => $this->date_due, "dco" => $this->date_checked_out];

			$query = "INSERT INTO checkout (checkout_employee, user, rental_item, date_due, date_checked_out)
						VALUES (:co_emp, :usr, :ri, :dd, :dco)";

			$this->_pdo->perform($query, $args);

			$this->id = $this->_pdo->lastInsertId();

		}


		public function checkIn(Employee $employee) {
			$this->date_returned = Utility::getDateTimeForMySQLDate();

			$this->_ci_employee = $employee;
			$this->checkin_employee = $employee->getId();

			$args = ["dr" => $this->date_returned, "ci_emp" => $this->checkin_employee, "id" => $this->id];

			$query = "UPDATE checkout SET date_returned = :dr, checkin_employee = :ci_emp WHERE id = :id";

			$this->_pdo->perform($query, $args);

		}

		public function isCheckedIn(){
			return $this->date_returned == null;
		}

		/**
		 * @return int
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * @return int
		 */
		public function getCheckoutEmployeeID() {
			return $this->checkout_employee;
		}

		/**
		 * @return int
		 */
		public function getCheckinEmployeeID() {
			return $this->checkin_employee;
		}

		/**
		 * @return int
		 */
		public function getUserID() {
			return $this->user;
		}

		/**
		 * @return int
		 */
		public function getRentalItemID() {
			return $this->rental_item;
		}

		/**
		 * @return DateTime
		 *
		 */
		public function getDateDue() {
			return Utility::getDateTimeFromMySQLDate($this->date_due);
		}

		/**
		 * @return DateTime
		 */
		public function getDateReturned() {
			return Utility::getDateTimeFromMySQLDate($this->date_returned);
		}

		/**
		 * @return User
		 */
		public function getUser() {
			return $this->_user;
		}

		/**
		 * @return RentalItem
		 */
		public function getRentalItem() {
			return $this->_rental_item;
		}

		/**
		 * @return Employee
		 */
		public function getCheckinEmployee() {
			return $this->_ci_employee;
		}

		/**
		 * @return Employee
		 */
		public function getCheckoutEmployee() {
			return $this->_co_employee;
		}

		public function findActiveCheckout(RentalItem $rentalItem) {
			$args = ["val" => $rentalItem->getId()];

			$activeCheckout = null;
			return $this->_pdo->fetchOne("SELECT * FROM `checkout` WHERE rental_item = :val AND date_returned IS NULL ", $args);
		}

		public static function getItemCheckedOutCount(PDO_MySQL $_pdo) {
			return $_pdo->fetchOne("SELECT COUNT(`id`) AS `count` FROM `checkout` WHERE `date_returned` IS NULL")["count"];
		}

		/**
		 * @param array $data_arr
		 */
		protected function parse(array $data_arr) {
			foreach ($data_arr as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}