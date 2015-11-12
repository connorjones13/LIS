<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/10/15
	 * Time: 9:47 PM
	 */

	namespace LIS;

	use DateTime;

	class Utility {
		/**
		 * @return string
		 */
		public static function getAppBasePath() {
			return realpath(__DIR__ . "/../../");
		}

		/**
		 * @param string $phone
		 * @return string
		 */
		public static function getPhoneFormatted($phone) {
			return "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) . "-" . substr($phone, 6, 4);
		}

		/**
		 * @param string $date_string
		 * @return DateTime
		 */
		public static function getDateTimeFromMySQLDate($date_string) {
			return DateTime::createFromFormat("Y-m-d", $date_string);
		}

		/**
		 * @param DateTime $date
		 * @return string
		 */
		public static function getDateTimeForMySQLDate($date = null) {
			if ($date === null)
				$date = new DateTime();

			return $date->format("Y-m-d");
		}

		/**
		 * @param string $date_string
		 * @return DateTime
		 */
		public static function getDateTimeFromMySQLDateTime($date_string) {
			return DateTime::createFromFormat("Y-m-d H:i:s", $date_string);
		}

		/**
		 * @param DateTime $date
		 * @return string
		 */
		public static function getDateTimeForMySQLDateTime($date = null) {
			if ($date === null)
				$date = new DateTime();

			return $date->format("Y-m-d H:i:s");
		}

		/**
		 * Remove unnecessary parts of phone number for storage.
		 * @param string $phone
		 * @return string
		 */
		public static function cleanPhoneString($phone) {
			return preg_replace("/[^0-9]/", "", $phone);
		}

		/**
		 * @param string $password
		 * @return bool|string
		 */
		public static function hashPassword($password) {
			return password_hash($password, PASSWORD_BCRYPT);
		}

		/**
		 * @param string $password
		 * @param string $password_hash
		 * @return bool
		 */
		public static function verifyPassword($password, $password_hash) {
			return password_verify($password, $password_hash);
		}

		/**
		 * @param int $length - Desired length of string
		 * @param bool|true $numbers - Use numbers
		 * @param bool|true $lowercase - Use lowercase letters
		 * @param bool|true $uppercase - Use uppercase letters
		 * @return string
		 */
		public static function getRandomString($length = 16, $numbers = true, $lowercase = true, $uppercase = true) {
			$possible_chars = $numbers ? "0123456789" : "";
			$possible_chars .= $lowercase ? "abcdefghijklmnopqrstuvwxyz" : "";
			$possible_chars .= $uppercase ? "ABCDEFGHIJKLMNOPQRSTUVWXYZ" : "";

			$char_list_len = strlen($possible_chars);

			$randomString = "";
			for ($i = 0; $i < $length; $i++)
				$randomString .= $possible_chars[rand(0, $char_list_len - 1)];

			return $randomString;
		}
	}