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
	use LIS\RentalItem\RentalItem;
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
			$_SESSION["successful_update"] = "Successfully updated Book!";
			$loc = 'Location: /item/' . $book->getId() . '/';
			header($loc);
		}

		public function updateDvdInfo(DVD $dvd, $summary, $title, $category, $date_published, $director) {
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

			$dvd->updateDvd($summary, $title, $category, Utility::getDateTimeFromMySQLDate($date_published),
					$status = 0, $director);
			$_SESSION["successful_update"] = "Successfully updated DVD!";
			$loc = 'Location: /item/' . $dvd->getId() . '/';
			header($loc);
		}

		public function updateMagazineInfo(Magazine $magazine, $summary, $title, $category, $date_published,
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

			$magazine->updateMagazine($summary, $title, $category, Utility::getDateTimeFromMySQLDate($date_published),
					$status = 0, $publication, $issue_number);
			$_SESSION["successful_update"] = "Successfully updated Magazine!";
			$loc = 'Location: /item/' . $magazine->getId() . '/';
			header($loc);
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

		public function markItemLost(RentalItem $rentalItem) {
			$rentalItem->markLost();
			// todo: cancel any reservations and automatically check the item in
			// todo: error in case there is any reason it could not be marked as lost
			$_SESSION["lost_success"] = "Successfully marked " . $rentalItem->getTitle() . " as lost.";
			self::displayPage('/item/' . $rentalItem->getId() . '/');
		}

		public function markItemDamaged(RentalItem $rentalItem) {
			$rentalItem->markDamaged();
			// todo: cancel any reservations and automatically check the item in
			// todo: error in case there is any reason it could not be marked as damaged
			$_SESSION["damaged_success"] = "Successfully marked " . $rentalItem->getTitle() . " as damaged.";
			self::displayPage('/item/' . $rentalItem->getId() . '/');

		}
		public function markItemAvailable(RentalItem $rentalItem) {
			$rentalItem->markAvailable();
			// todo: cancel any reservations and automatically check the item in
			// todo: error in case there is any reason it could not be marked as available
			$_SESSION["damaged_success"] = "Successfully marked " . $rentalItem->getTitle() . " as available.";
			self::displayPage('/item/' . $rentalItem->getId() . '/');
		}
	}