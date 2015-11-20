<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/10/15
	 * Time: 10:44 PM
	 */

	namespace LIS\User;

	use LIS\Database\PDO_MySQL;

	class Admin extends Employee {
		public function create($name_first, $name_last, $email, $phone, $gender, $date_of_birth,
		                       $address_line_1, $address_line_2, $address_zip, $address_city,
		                       $address_state, $address_country_code, $password) {
			$id = self::createNew($this->_pdo, $name_first, $name_last, $email, $phone, $gender,
					$date_of_birth, $address_line_1, $address_line_2, $address_zip, $address_city,
					$address_state, $address_country_code, $password, self::PRIVILEGE_ADMIN);

			$this->parse(self::findRowBy($this->_pdo, "id", $id, self::PRIVILEGE_ADMIN));
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			$row = self::findRowBy($_pdo, "id", $id, self::PRIVILEGE_ADMIN);
			return $row ? new Admin($_pdo, $row) : null;
		}

		public static function findByEmail(PDO_MySQL $_pdo, $email) {
			$row = self::findRowBy($_pdo, "email", $email, self::PRIVILEGE_ADMIN);
			return $row ? new Admin($_pdo, $row) : null;
		}

		public static function findByPhone(PDO_MySQL $_pdo, $phone) {
			$row = self::findRowBy($_pdo, "phone", $phone, self::PRIVILEGE_ADMIN);
			return $row ? new Admin($_pdo, $row) : null;
		}

		public static function getAllActive(PDO_MySQL $_pdo) {
			$args = array("pl" => self::PRIVILEGE_ADMIN);
			$query = "SELECT * FROM `user_view` WHERE `active` = 1 AND privilege_level >= :pl";
			$rows = $_pdo->fetchAssoc($query, $args);

			return array_map(function($row) use ($_pdo) {
				return new Admin($_pdo, $row);
			}, $rows);
		}

		public static function setToPrivilegeLevel(User $user) {
			$user->privilege_level = self::PRIVILEGE_ADMIN;
			$user->_pdo->perform("UPDATE user SET privilege_level = :pl", ["pl" => self::PRIVILEGE_ADMIN]);
			return new Admin($user->_pdo, get_object_vars($user));
		}
	}