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

	private $id, $user, $rental_item, $date_created, $date_pickup, $isExpired;

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

		// save objects
		$this->_user = $user;
		$this->_rental_item = $rentalItem;

		$args = ["usr" => $this->user, "ri" => $this->rental_item, "dc" => $this->date_created];

		$query = "INSERT INTO reservation (user, rental_item, date_created) VALUES (:usr, :ri, :dc)";

		$this->_pdo->perform($query, $args);

		$this->id = $this->_pdo->lastInsertId();
	}

	public function setPickedUp() {
		$this->date_pickup = Utility::getDateTimeForMySQLDateTime();

		$args = ["dp" => $this->date_pickup];

		$query = "UPDATE reservation SET date_pickup = :dp";

		$this->_pdo->perform($query, $args);

	}

	public function setExpired() {

	}

	public function isExpired() {
		return $this->getDateCreated()->add(new DateInterval("1 day")) < new DateTime();
	}

	public function getDateCreated() {
		return Utility::getDateTimeFromMySQLDateTime($this->date_created);
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