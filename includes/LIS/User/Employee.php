<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/10/15
	 * Time: 10:44 PM
	 */

	namespace LIS\User;

	use LIS\Database\PDO_MySQL;

	class Employee extends User {
		public function create($name_first, $name_last, $email, $phone, $gender, $date_of_birth,
		                       $address_line_1, $address_line_2, $address_zip, $address_city,
		                       $address_state, $address_country_code, $password_hash) {
			$id = self::createNew($this->_pdo, $name_first, $name_last, $email, $phone, $gender,
					$date_of_birth, $address_line_1, $address_line_2, $address_zip, $address_city,
					$address_state, $address_country_code, $password_hash, self::PRIVILEGE_EMPLOYEE);

			$this->parse(self::findRowBy($this->_pdo, "id", $id, self::PRIVILEGE_EMPLOYEE));
		}

		public static function find(PDO_MySQL $_pdo, $id) {
			return new Employee($_pdo, self::findRowBy($_pdo, "id", $id, self::PRIVILEGE_EMPLOYEE));
		}

		public static function findByEmail(PDO_MySQL $_pdo, $email) {
			return new Employee($_pdo, self::findRowBy($_pdo, "email", $email, self::PRIVILEGE_EMPLOYEE));
		}

		public static function findByPhone(PDO_MySQL $_pdo, $phone) {
			return new Employee($_pdo, self::findRowBy($_pdo, "phone", $phone, self::PRIVILEGE_EMPLOYEE));
		}

		public static function getAllActive(PDO_MySQL $_pdo) {
			$args = array("pl" => self::PRIVILEGE_EMPLOYEE);
			$rows = $_pdo->fetchAssoc("SELECT * FROM `user` WHERE `active` = 1 AND privilege_level >= :pl", $args);

			return array_map(function($row) use ($_pdo) {
				return new Employee($_pdo, $row);
			}, $rows);
		}
	}