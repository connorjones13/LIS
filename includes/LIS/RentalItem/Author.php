<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/20/15
	 * Time: 10:57 AM
	 */

	namespace LIS\RentalItem;


	use LIS\Database\PDO_MySQL;

	class Author {
		private $id, $book, $name_full;

		/* @var PDO_MySQL $_pdo */
		private $_pdo;

		/* @var Book $_book */
		private $_book;

		public function __construct(PDO_MySQL $_pdo, array $data_array = array()) {
			$this->_pdo = $_pdo;

			if (!empty($data_array))
				$this->parse($data_array);
		}

		/**
		 * @param Book $_book
		 * @param string $name_full
		 */
		public function create(Book $_book, $name_full) {
			$this->_book = $_book;
			$this->book = $this->_book->getId();
			$this->name_full = $name_full;

			$this->_pdo->perform("INSERT INTO author (book, name_full) VALUES (:bid, :nf)", [
				"bid" => $this->_book->getId(),
				"nf" => $this->name_full
			]);

			$this->id = $this->_pdo->lastInsertId();
		}

		/** @return int */
		public function getId() {
			return $this->id;
		}

		/** @return int */
		public function getBookId() {
			return $this->book;
		}

		/** @return string */
		public function getNameFull() {
			return $this->name_full;
		}

		/**
		 * @param array $data_array
		 */
		public function parse(array $data_array) {
			foreach ($data_array as $key => $value) {
				$this->{$key} = $value;
			}
		}

		/**
		 * @return Book
		 */
		public function getBook() {
			if (!$this->_book)
				$this->_book = Book::find($this->_pdo, $this->book);

			return $this->_book;
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param Book $_book
		 * @return Author[]
		 */
		public static function findAllForBook(PDO_MySQL $_pdo, Book $_book) {
			$authors = $_pdo->fetchAssoc("SELECT * FROM author WHERE book = :bid", [
				"bid" => $_book->getId()
			]);

			foreach ($authors as $key => $author)
				$authors[$key] = new Author($_pdo, $author);

			return $authors;
		}

		public static function deleteAllForBook(PDO_MySQL $_pdo, Book $_book) {
			$_pdo->perform("DELETE FROM author WHERE book = :bid", [
				"bid" => $_book->getId()
			]);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param Book $_book
		 * @param string[] $authors
		 * @return Author[]
		 */
		public static function createNewForBook(PDO_MySQL $_pdo, Book $_book, array $authors) {
			foreach ($authors as $key => $author) {
				$temp = new Author($_pdo);
				$temp->create($_book, $author);
				$authors[$key] = $temp;
			}

			return $authors;
		}

		public function __toString() {
			return $this->name_full;
		}
	}