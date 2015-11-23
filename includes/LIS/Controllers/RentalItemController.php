<?php
	/**
	 * Created by PhpStorm.
	 * User: connorjones
	 * Date: 11/23/15
	 * Time: 8:23 AM
    */

	namespace LIS\Controllers;

	use LIS\RentalItem\Book;
	use LIS\RentalItem\DVD;
	use LIS\RentalItem\Magazine;

	class RentalItemController extends BaseController {

		public function getAllRentalItems() {

			$book = new Book($this->_pdo);
			$books = $book->getAllByStatus($this->_pdo, 0);

			$dvd = new DVD($this->_pdo);
			$dvds = $dvd->getAllByStatus($this->_pdo, 0);

			$magazine = new Magazine($this->_pdo);
			$magazines = $magazine->getAllByStatus($this->_pdo, 0);

			foreach ($books as $book) {

			}

			foreach ($dvds as $dvd) {

			}

			foreach ($magazines as $magazine) {

			}

		}
	}