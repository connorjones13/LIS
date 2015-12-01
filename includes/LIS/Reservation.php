<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/18/15
 * Time: 10:17 PM
 */

namespace LIS;


use LIS\Database\PDO_MySQL;
use DateInterval;
use DateTime;
use LIS\RentalItem\RentalItem;
use LIS\User\User;

class Reservation {

	private $id, $user, $rental_item, $date_created, $is_expired, $checkout;

	/* @var PDO_MySQL $_pdo */
	private $_pdo; //Since this is an internal dependency, I mark it with an _

	/* @var User $_user */
	private $_user;

	/* @var RentalItem $_rental_item*/
	private $_rental_item;

	/**
	 * Reservation constructor. Takes a database object as a dependency and an optional
	 * array parameter to initialize the Reservation object with data.
	 * @param PDO_MySQL $_pdo
	 * @param array $data_arr
	 */
	public function __construct(PDO_MySQL $_pdo, array $data_arr = array()) {
		$this->_pdo = $_pdo;

		if (!empty($data_arr))
			$this->parse($data_arr);
	}

	protected function create(User $user, RentalItem $rentalItem) {
		$this->date_created = Utility::getDateTimeForMySQLDateTime();

		$this->user = $user->getId();
		$this->rental_item = $rentalItem->getId();
		$this->is_expired = 0;

		// save objects
		$this->_user = $user;
		$this->_rental_item = $rentalItem;

		$args = ["usr" => $this->user, "ri" => $this->rental_item, "dc" => $this->date_created];

		$query = "INSERT INTO reservation (user, rental_item, date_created) VALUES (:usr, :ri, :dc)";

		$this->_pdo->perform($query, $args);

		$this->id = $this->_pdo->lastInsertId();
	}

	public function setPickedUp(Checkout $checkout) {
		$this->checkout = $checkout->getId();
		$args = ["co" => $this->checkout];

		$query = "UPDATE reservation SET checkout = :co";

		$this->_pdo->perform($query, $args);

	}

	public function setExpired() {
		$this->is_expired = 1;

		$args = ["exp" => $this->is_expired];

		$query = "UPDATE reservation SET is_expired = :exp";

		$this->_pdo->perform($query, $args);

	}

	public function isExpired() {
		return $this->getDateCreated()->add(new DateInterval("1 day")) < new DateTime();
	}

	public function getDateCreated() {
		return Utility::getDateTimeFromMySQLDateTime($this->date_created);
	}

	public static function getItemCheckedOutCount(PDO_MySQL $_pdo) {
		$query = "SELECT COUNT(`id`) AS `count` FROM `reservation` WHERE `checkout` IS NULL AND `is_expired` = 0";

		return $_pdo->fetchOne($query)["count"];
	}

	public static function findForBook(PDO_MySQL $_pdo, RentalItem $_ri) {
		$query = "SELECT * FROM reservation WHERE rental_item = :ri ORDER BY date_created ASC";

		return $_pdo->fetchAll($query, [
			"ri" => $_ri->getId()
		], function($row) use ($_pdo) {
			return new Reservation($_pdo, $row);
		});
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