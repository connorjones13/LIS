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
		public function create($name_first, $name_last, $email, $phone, $gender, $date_of_birth, $address_line_1,
		                       $address_line_2, $address_zip, $address_city, $address_state, $address_country_code,
		                       $password_hash, $privilege_level = self::PRIVILEGE_ADMIN) {
			parent::create($name_first, $name_last, $email, $phone, $gender, $date_of_birth, $address_line_1,
					$address_line_2, $address_zip, $address_city, $address_state, $address_country_code,
					$password_hash, $privilege_level);
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			return new Admin($_pdo, self::findRowBy($_pdo, "id", $id, self::PRIVILEGE_ADMIN));
		}

		public static function findByEmail(PDO_MySQL $_pdo, $email) {
			return new Admin($_pdo, self::findRowBy($_pdo, "email", $email, self::PRIVILEGE_ADMIN));
		}

		public static function findByPhone(PDO_MySQL $_pdo, $phone) {
			return new Admin($_pdo, self::findRowBy($_pdo, "phone", $phone, self::PRIVILEGE_ADMIN));
		}

		public static function getAllActive(PDO_MySQL $_pdo) {
			$args = array("pl" => self::PRIVILEGE_ADMIN);
			$rows = $_pdo->fetchAssoc("SELECT * FROM `user_view` WHERE `active` = 1 AND privilege_level >= :pl", $args);

			return array_map(function($row) use ($_pdo) {
				return new Admin($_pdo, $row);
			}, $rows);
		}
	}