<?php
	/**
	 * Created by PhpStorm.
	 * User: connorjones
	 * Date: 11/23/15
	 * Time: 8:23 AM
    */

	namespace LIS\Controllers;

	use DateTime;
	use LIS\RentalItem\Book;
	use LIS\RentalItem\DVD;
	use LIS\RentalItem\Magazine;
	use LIS\Utility;

	class RentalItemController extends BaseController {

		private static $ERROR_SUMMARY = 0;
		private static $ERROR_TITLE= 1;
		private static $ERROR_CATEGORY = 2;
		private static $ERROR_DATE_PUBLISHED = 3;
		private static $ERROR_ISBN10 = 4;
		private static $ERROR_ISBN13 = 5;
		private static $ERROR_AUTHORS = 6;
		private static $ERROR_DIRECTOR = 7;
		private static $ERROR_PUBLICATION = 8;
		private static $ERROR_ISSUE_NUMBER = 9;

		public function getAllRentalItems() {

			$book = new Book($this->_pdo);
			$books = $book->getAllAvailable($this->_pdo);

			$dvd = new DVD($this->_pdo);
			$dvds = $dvd->getAllAvailable($this->_pdo);

			$magazine = new Magazine($this->_pdo);
			$magazines = $magazine->getAllAvailable($this->_pdo);

			foreach ($books as $book) {

			}

			foreach ($dvds as $dvd) {

			}

			foreach ($magazines as $magazine) {

			}

		}


		public function updateBookInfo(Book $book, $summary, $title, $category, $date_published, $isbn10 = "",
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

			$authorsArray = explode(',', $authors);
			$book->updateBook($summary, $title, $category, Utility::getDateTimeFromMySQLDate($date_published),
					$status = 0, $isbn10, $isbn13, $authorsArray);
			$_SESSION["show_created_alert"] = "Successfully updated a Book!";
			self::displayPage(self::$PAGE_ADD_ITEM);
		}

		public function updateDvdInfo(DVD $dvd, $summary, $title, $category, $date_published, $director) {

		}

		public function updateMagazineInfo(Magazine $magazine, $summary, $title, $category, $date_published,
		                                   $publication, $issue_number) {

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
				case self::$ERROR_PUBLICATION:
					return "Please enter a valid publication.";
				case self::$ERROR_ISSUE_NUMBER:
					return "Please enter a valid issue number.";
				case self::$ERROR_DIRECTOR:
					return "Please enter a valid director.";
				default:
					return false;
			}
		}
	}