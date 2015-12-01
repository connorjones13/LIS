<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/19/15
	 * Time: 6:53 PM
	 */

	namespace LIS;

	use DateTime;
	use LIS\Database\PDO_MySQL;
	use LIS\User\Admin;
	use LIS\User\Employee;
	use LIS\User\User;

	class LibraryCard {
		const STATUS_INACTIVE = 0;
		const STATUS_ACTIVE = 1;

		private $id, $user, $number, $date_issued, $status;

		/* @var PDO_MySQL $_pdo */
		private $_pdo;

		/* @var User|Employee|Admin $_user */
		private $_user;

		/**
		 * LibraryCard constructor.
		 * @param PDO_MySQL $_pdo
		 * @param array $data_array
		 */
		public function __construct(PDO_MySQL $_pdo, array $data_array = array()) {
			$this->_pdo = $_pdo;

			if (!empty($data_array))
				$this->parse($data_array);
		}

		public function create(User $user) {
			$arguments = [
				"uid" => $user->getId(),
				"ti" => Utility::getDateTimeForMySQLDate(),
				"lc" => Utility::getRandomString(16, true, false, true)
			];
			$query = "INSERT INTO library_card (user, date_issued, number) VALUES (:uid, :ti, :lc)";

			for (;;) {
				try {
					$this->_pdo->perform($query, $arguments);
					break;
				} catch (\PDOException $er) {
					if (!PDO_MySQL::isDuplicateKeyError($er))
						throw $er;

					$arguments["lc"] = Utility::getRandomString(16, true, false, true);
				}
			};
		}

		/** @return int */
		public function getId() {
			return $this->id;
		}

		/** @return int */
		public function getUserId() {
			return $this->user;
		}

		/** @return User|Employee|Admin */
		public function getUser() {
			if (!$this->_user)
				$this->_user = User::find($this->_pdo, $this->user);

			if ($this->_user)
				$this->_user->referenceLibraryCard($this);

			return $this->_user;
		}

		/** @return string */
		public function getNumber() {
			return $this->number;
		}

		/** @return DateTime */
		public function getDateIssued() {
			return Utility::getDateTimeFromMySQLDate($this->date_issued);
		}

		/** @return int */
		public function getStatus() {
			return intval($this->status);
		}

		/** @param int $status */
		public function setStatus($status) {
			if (!in_array($status, [self::STATUS_ACTIVE, self::STATUS_INACTIVE]))
				throw new \InvalidArgumentException("Passed status is invalid");

			$this->status = $status;
		}

		public static function findByUser(PDO_MySQL $_pdo, User $_user) {
			$row = $_pdo->fetchOne("SELECT * FROM library_card WHERE user = :id", [
				"id" => $_user->getId()
			]);

			$card = $row ? new LibraryCard($_pdo, $row) : null;

			if (!is_null($card))
				$card->_user = $_user;

			return $card;
		}

		public static function findByCardNumber(PDO_MySQL $_pdo, $number) {

			$row = $_pdo->fetchOne("SELECT * FROM library_card WHERE number = :num", [
				"num" => $number
			]);

			$card = $row ? new LibraryCard($_pdo, $row) : null;

			if (!is_null($card))
				$card->number= $number;

			return $card;

		}

		private function parse(array $data_arr) {
			foreach ($data_arr as $key => $value) {
				$this->{$key} = $value;
			}
		}

		public function referenceUser(User $_user) {
			if ($_user->getId() != $this->user)
				throw new \InvalidArgumentException("User does not own this card");

			$this->_user = $_user;
		}
	}