<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 11:00 AM
 */

	namespace LIS\Controllers;

	use LIS\RentalItem\DVD;
	use DateTime;
	use LIS\Utility;

	class AddDvdController extends BaseController {

		private static $ERROR_SUMMARY = 0;
		private static $ERROR_TITLE= 1;
		private static $ERROR_CATEGORY = 2;
		private static $ERROR_DATE_PUBLISHED = 3;
		private static $ERROR_DIRECTOR = 4;


		public function createNewDvd($summary, $title, $category, $date_published, $status, $director) {

			if (!$summary)
				$this->setError(self::$ERROR_SUMMARY);

			else if (!$title)
				$this->setError(self::$ERROR_TITLE);

			else if (!$category)
				$this->setError(self::$ERROR_CATEGORY);

			else if (Utility::getDateTimeFromMySQLDate($date_published) >= new DateTime())
				$this->setError(self::$ERROR_DATE_PUBLISHED);

			else if (!$director)
				$this->setError(self::$ERROR_DIRECTOR);

			if ($this->hasError())
				return;

			$book = new DVD($this->_pdo);
			$book->create($summary, $title, $category, Utility::getDateTimeFromMySQLDate($date_published),
				$status = 0, $director);
		}

		public function getErrorMessage() {
			switch ($this->getError()) {
				case self::$ERROR_SUMMARY:
					return "Please enter a valid summary.";
				case self::$ERROR_TITLE:
					return "Please enter a valid title.";
				case self::$ERROR_CATEGORY:
					return "Please enter a valid category.";
				case self::$ERROR_DATE_PUBLISHED:
					return "Please enter a valid date published";
				case self::$ERROR_DIRECTOR:
					return "Please enter a valid director.";
				default:
					return false;
			}
		}
	}