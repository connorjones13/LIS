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
	}