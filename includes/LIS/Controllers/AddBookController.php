<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 10:34 AM
 */

	namespace LIS\Controllers;
	
	use LIS\RentalItem\Book;
	use DateTime;
	use LIS\Utility;
	
	class AddBookController extends BaseController {

		private static $ERROR_SUMMARY = 0;
		private static $ERROR_TITLE= 1;
		private static $ERROR_CATEGORY = 2;
		private static $ERROR_DATE_PUBLISHED = 3;
		private static $ERROR_ISBN10 = 4;
		private static $ERROR_ISBN13 = 5;
		private static $ERROR_AUTHORS = 6;

		public function createNewBook($summary, $title, $category, $date_published, $status, $isbn10 = "",
		                              $isbn13 = "", $authors) {
			if (!$summary)
				$this->setError(self::$ERROR_SUMMARY);

			else if (!$title)
				$this->setError(self::$ERROR_TITLE);

			else if (!$category)
				$this->setError(self::$ERROR_CATEGORY);

			else if (Utility::getDateTimeFromMySQLDate($date_published) >= new DateTime())
				$this->setError(self::$ERROR_DATE_PUBLISHED);

			else if (!$isbn10)
				$this->setError(self::$ERROR_ISBN10);

			else if (!$isbn13)
				$this->setError(self::$ERROR_ISBN13);

			else if (!$authors)
				$this->setError(self::$ERROR_AUTHORS);

			if ($this->hasError())
				return;

			$book = new Book($this->_pdo);
			$authorsArray = explode(',', $authors);
			$book->create($summary, $title, $category, Utility::getDateTimeFromMySQLDate($date_published),
				$status = 0, $isbn10, $isbn13, $authorsArray);
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
				case self::$ERROR_ISBN10:
					return "Please enter a valid publication.";
				case self::$ERROR_ISBN13:
					return "Please enter a valid issue number.";
				case self::$ERROR_AUTHORS:
					return"Please enter at least one valid author.";
				default:
					return false;
			}
		}
	}