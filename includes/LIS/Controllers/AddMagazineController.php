<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 9:05 AM
 */

	namespace LIS\Controllers;

	use DateTime;
	use LIS\RentalItem\Magazine;
	use LIS\Utility;

	class AddMagazineController extends BaseController {

		private static $ERROR_SUMMARY = 0;
		private static $ERROR_TITLE= 1;
		private static $ERROR_CATEGORY = 2;
		private static $ERROR_DATE_PUBLISHED = 3;
		private static $ERROR_PUBLICATION = 4;
		private static $ERROR_ISSUE_NUMBER = 5;


		public function createNewMagazine($summary, $title, $category, $date_published, $status,
		                                  $publication, $issue_number) {

			if (!$summary)
				$this->setError(self::$ERROR_SUMMARY);

			else if (!$title)
				$this->setError(self::$ERROR_TITLE);

			else if (!$category)
				$this->setError(self::$ERROR_CATEGORY);

			else if (Utility::getDateTimeFromMySQLDate($date_published) >= new DateTime())
				$this->setError(self::$ERROR_DATE_PUBLISHED);

			else if (!$publication)
				$this->setError(self::$ERROR_PUBLICATION);

			else if (!$issue_number)
				$this->setError(self::$ERROR_ISSUE_NUMBER);

			if ($this->hasError())
				return;

			$magazine = new Magazine($this->_pdo);
			$magazine->create($summary, $title, $category, Utility::getDateTimeFromMySQLDate($date_published),
				$status = 0, $publication, $issue_number);
		}

		/**
		 * @return bool|string
		 */
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
				case self::$ERROR_PUBLICATION:
					return "Please enter a valid publication.";
				case self::$ERROR_ISSUE_NUMBER:
					return "Please enter a valid issue number.";
				default:
					return false;
			}
		}
	}