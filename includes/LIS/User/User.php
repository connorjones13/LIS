<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/10/15
	 * Time: 10:14 PM
	 */

	namespace LIS\User;

	use DateInterval;
	use LIS\Database\PDO_MySQL;
	use DateTime;
	use LIS\LibraryCard;
	use LIS\Utility;

	class User {
		const PRIVILEGE_USER = 0;
		const PRIVILEGE_EMPLOYEE = 1;
		const PRIVILEGE_ADMIN = 2;

		const GENDER_NOT_GIVEN = 0;
		const GENDER_MALE = 1;
		const GENDER_FEMALE = 2;
		const GENDER_OTHER = 3;

		protected $id, $active, $privilege_level, $name_first, $name_last, $email, $phone, $date_signed_up, $gender,
				$date_of_birth, $address_line_1, $address_line_2, $address_zip, $address_city, $address_state,
				$address_country_code, $password_hash, $reset_token, $reset_token_expiry, $account_confirm_token;

		/* @var PDO_MySQL $_pdo */
		protected $_pdo; //Since this is an internal dependency, I mark it with an _

		/* @var LibraryCard $_library_card */
		protected $_library_card;

		/**
		 * User constructor. Takes a database object as a dependency and an optional
		 * array parameter to initialize the User object with data.
		 * @param PDO_MySQL $_pdo
		 * @param array $data_arr
		 */
		public function __construct(PDO_MySQL $_pdo, array $data_arr = []) {
			$this->_pdo = $_pdo;

			if (!empty($data_arr))
				$this->parse($data_arr);
		}

		/**
		 * The above constructor and the below debugInfo methods are special methods
		 * called Magic Methods. All PHPs magic methods start with __ and are run as
		 * specific events occur.
		 */

		/**
		 * Keeps unwanted data out of var_dump functions. Here we are hiding the pdo
		 * variable from printing out since it contains our connection data and also
		 * happens to be unnecessary for debugging the User object.
		 */
		function __debugInfo() {
			$user_object = get_object_vars($this);
			unset($user_object["_pdo"]);

			return $user_object;
		}

		/**
		 * @param string $name_first
		 * @param string $name_last
		 * @param string $email
		 * @param string $phone
		 * @param int $gender
		 * @param DateTime $date_of_birth
		 * @param string $address_line_1
		 * @param string $address_line_2
		 * @param string $address_zip
		 * @param string $address_city
		 * @param string $address_state
		 * @param string $address_country_code
		 * @param string $password
		 * @param LibraryCard $_library_card
		 */
		public function create($name_first, $name_last, $email, $phone, $gender, $date_of_birth, $address_line_1,
		                       $address_line_2, $address_zip, $address_city, $address_state, $address_country_code,
		                       $password, LibraryCard $_library_card) {
			$id = self::createNew($this->_pdo, $name_first, $name_last, $email, $phone, $gender,
					$date_of_birth, $address_line_1, $address_line_2, $address_zip, $address_city,
					$address_state, $address_country_code, $password, self::PRIVILEGE_USER);

			$this->parse(self::findRowBy($this->_pdo, "id", $id));

			$this->_library_card = $_library_card;
			$this->_library_card->create($this);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param string $name_first
		 * @param string $name_last
		 * @param string $email
		 * @param string $phone
		 * @param int $gender
		 * @param DateTime $date_of_birth
		 * @param string $address_line_1
		 * @param string $address_line_2
		 * @param string $address_zip
		 * @param string $address_city
		 * @param string $address_state
		 * @param string $address_country_code
		 * @param string $password
		 * @param int $privilege_level
		 * @return int
		 */
		protected static function createNew(PDO_MySQL $_pdo, $name_first, $name_last, $email, $phone,
		                                    $gender, $date_of_birth, $address_line_1, $address_line_2,
		                                    $address_zip, $address_city, $address_state, $address_country_code,
		                                    $password, $privilege_level) {
			$time = Utility::getDateTimeForMySQLDate();

			$arguments = [
				"nf" => $name_first, "nl" => $name_last, "em" => $email, "ph" => Utility::cleanPhoneString($phone),
				"dsu" => $time, "ge" => $gender, "dob" => Utility::getDateTimeForMySQLDate($date_of_birth),
				"al1" => $address_line_1, "al2" => $address_line_2, "az" => $address_zip, "ac" => $address_city,
				"ast" => $address_state, "acc" => $address_country_code, "pa" => Utility::hashPassword($password),
				"pl" => $privilege_level
			];
			$query = "INSERT INTO user (name_first, name_last, email, phone, date_signed_up, gender, date_of_birth,
					  address_line_1, address_line_2, address_zip, address_city, address_state, address_country_code,
					  password_hash, privilege_level) VALUES (:nf, :nl, :em, :ph, :dsu, :ge, :dob, :al1, :al2, :az,
					  :ac, :ast, :acc, :pa, :pl)";

			$_pdo->perform($query, $arguments);

			$id = $_pdo->lastInsertId();

			self::createAccountConfirmationToken($_pdo, $id);

			return $id;
		}

		public function getLibraryCard() {
			if (!$this->_library_card)
				$this->_library_card = LibraryCard::findByUser($this->_pdo, $this);

			return $this->_library_card;
		}

		/** @return int */
		public function getId() {
			return intval($this->id);
		}

		/** @return string */
		public function getNameFirst() {
			return $this->name_first;
		}

		/** @return string */
		public function getNameLast() {
			return $this->name_last;
		}

		/** @return string */
		public function getNameFull() {
			return $this->name_first . " " . $this->name_last;
		}

		/** @return bool */
		public function isActive() {
			return !!$this->active;
		}

		/** @return int */
		public function getPrivilegeLevel() {
			return intval($this->privilege_level);
		}

		/** @return bool */
		public function isEmployee() {
			return $this->privilege_level == self::PRIVILEGE_EMPLOYEE;
		}

		/** @return bool */
		public function isAdmin() {
			return $this->privilege_level == self::PRIVILEGE_ADMIN;
		}

		/** @return string */
		public function getEmail() {
			return $this->email;
		}

		/** @return string */
		public function getPhone() {
			return $this->phone;
		}

		/** @return string */
		public function getPhoneFormatted() {
			return Utility::getPhoneFormatted($this->phone);
		}

		/** @return DateTime */
		public function getDateSignedUp() {
			return Utility::getDateTimeFromMySQLDate($this->date_signed_up);
		}

		/** @return string */
		public function getGender() {
			switch ($this->gender) {
				case self::GENDER_MALE:
					return "Male";
				case self::GENDER_FEMALE:
					return "Female";
				case self::GENDER_OTHER:
					return "Other";
				case self::GENDER_NOT_GIVEN:
				default:
					return "Not Given";
			}
		}

		/** @return DateTime */
		public function getDateOfBirth() {
			return Utility::getDateTimeFromMySQLDate($this->date_of_birth);
		}

		/** @return string */
		public function getAddressLine1() {
			return $this->address_line_1;
		}

		/** @return string */
		public function getAddressLine2() {
			return $this->address_line_2;
		}

		/** @return string */
		public function getAddressCity() {
			return $this->address_city;
		}

		/** @return string */
		public function getAddressState() {
			return $this->address_state;
		}

		/** @return string */
		public function getAddressZip() {
			return $this->address_zip;
		}

		/** @return string */
		public function getAddressCountryCode() {
			return $this->address_country_code;
		}

		/**
		 * This function returns the user's full address.
		 * @param bool|true $html - Is text for html or text-area/email?
		 * @param bool|false $country - Add country code to end?
		 * @return string
		 */
		public function getAddressFull($html = true, $country = false) {
			/**
			 * This is a ternary statement. In case you have not seen one, it simply
			 * shortens an if statement to (if_statement ? true_value : false_value)
			 * so that you can set a value based on a boolean.
			 */
			$tag = $html ? "<br>" : "\n";

			return $this->address_line_1
				. ($this->address_line_2 ? $tag . $this->address_line_2 : "")
				. $tag . $this->address_city . " " . $this->address_state . ", " . $this->address_zip
				. ($country ? $tag . $this->address_country_code : "");
		}

		/** @return string */
		public function getPasswordHash() {
			return $this->password_hash;
		}

		/** @return string */
		public function getResetToken() {
			return $this->reset_token;
		}

		/** @return string */
		public function getAccountConfirmToken() {
			return $this->account_confirm_token;
		}

		/** @return DateTime */
		public function getResetTokenExpiry() {
			return Utility::getDateTimeFromMySQLDate($this->reset_token_expiry);
		}

		public function setActive() {
			$this->active = 1;
			$this->_pdo->perform("UPDATE user SET active = 1 WHERE id = :id", ["id" => $this->id]);
		}

		public function setInactive() {
			$this->active = 0;
			$this->_pdo->perform("UPDATE user SET active = 0 WHERE id = :id", ["id" => $this->id]);
		}

		/**
		 * @param string $email
		 */
		public function updateEmail($email) {
			$this->email = $email;

			$args = ["em" => $this->email, "id" => $this->id];
			$this->_pdo->perform("UPDATE user SET email = :em WHERE id = :id", $args);
		}

		/**
		 * @param string $phone
		 */
		public function updatePhoneNumber($phone) {
			$this->phone = Utility::cleanPhoneString($phone);

			$args = ["ph" => $this->phone, "id" => $this->id];
			$this->_pdo->perform("UPDATE user SET phone = :ph WHERE id = :id", $args);
		}

		/*
		 *  @param string $dateOfBirth
		 *  not 100% sure this function works
		 */
		public function updateDateOfBirth(DateTime $dateOfBirth) {
			$args = ["ph" => Utility::getDateTimeForMySQLDate($this->$dateOfBirth), "id" => $this->id];
			$this->_pdo->perform("UPDATE user SET date_of_birth = :ph WHERE id = :id", $args);
		}

		public function updateFirstName($firstName) {
			$args = ["ph" => $this->$firstName, "id" => $this->id];
			$this->_pdo->perform("UPDATE user SET name_first = :ph WHERE id = :id", $args);
		}

		public function updateLastName($lastName) {
			$args = ["ph" => $this->$lastName, "id" => $this->id];
			$this->_pdo->perform("UPDATE user SET name_last = :ph WHERE id = :id", $args);
		}

		public function updateGender($gender) {
			$args = ["ph" => $this->$gender, "id" => $this->id];
			$this->_pdo->perform("UPDATE user SET gender = :ph WHERE id = :id", $args);
		}

		/**
		 * @param mixed $address_line_1
		 * @param $address_line_2
		 * @param $address_zip
		 * @param $address_city
		 * @param $address_state
		 * @param $address_country_code
		 */
		public function updateAddress($address_line_1, $address_line_2, $address_zip, $address_city,
		                              $address_state) {
			$this->address_line_1 = $address_line_1;
			$this->address_line_2 = $address_line_2;
			$this->address_zip = $address_zip;
			$this->address_city = $address_city;
			$this->address_state = $address_state;

			$query = "UPDATE user SET address_line_1 = :al1, address_line_2 = :al2, address_zip = :az,
					  address_city = :ac, address_state = :ast WHERE id = :id";
			$args = [
				"al1" => $address_line_1, "al2" => $address_line_2, "az" => $address_zip, "ac" => $address_city,
				"ast" => $address_state, "id" => $this->id
			];
			$this->_pdo->perform($query, $args);
		}

		/**
		 * @param string $password
		 */
		public function updatePassword($password) {
			$this->password_hash = Utility::hashPassword($password);

			$args = ["ph" => $this->password_hash, "id" => $this->id];
			$this->_pdo->perform("UPDATE user SET password_hash = :ph WHERE id = :id", $args);
		}

		public function initiatePasswordReset() {
			$this->reset_token = Utility::getRandomString(32);
			$this->reset_token_expiry = new DateTime();
			$this->reset_token_expiry->add(DateInterval::createFromDateString("10 days"));
			$this->reset_token_expiry = Utility::getDateTimeForMySQLDateTime($this->reset_token_expiry);

			$args = ["rt" => $this->reset_token, "rte" => $this->reset_token_expiry, "id" => $this->id];
			$query = "UPDATE user SET reset_token = :rt, reset_token_expiry = :rte WHERE id = :id";

			for (;;) {
				try {
					$this->_pdo->perform($query, $args);
					break;
				} catch (\PDOException $er) {
					if (!PDO_MySQL::isDuplicateKeyError($er))
						throw $er;

					$this->reset_token = Utility::getRandomString(32);
				}
			}
		}

		private static function createAccountConfirmationToken(PDO_MySQL $_pdo, $id) {
			$args = ["rt" => Utility::getRandomString(32), "id" => $id];
			$query = "UPDATE user SET account_confirm_token = :rt WHERE id = :id";

			for (;;) {
				try {
					$_pdo->perform($query, $args);
					break;
				} catch (\PDOException $er) {
					if (!PDO_MySQL::isDuplicateKeyError($er))
						throw $er;

					$args["rt"] = Utility::getRandomString(32);
				}
			}
		}

		/**
		 * This function takes the data that a query returns and parses it into the
		 * variables of the class. It iterates over the values in $data_arr finding
		 * the two parts, the $key and the $value. Since the variables of the class
		 * and the columns in the database have the same name, they can be purposed
		 * to find the matching variables within the object and set their values to
		 * the value of the row in the database.
		 * @param array $data_arr
		 */
		protected function parse(array $data_arr) {
			foreach ($data_arr as $key => $value) {
				$this->{$key} = $value;
			}
		}

		/**
		 * Method to get a user based on a specific column value and their privilege
		 * level. This method reduces the amount of code necessary for the retrieval
		 * of many different types of single column finds.
		 * @param PDO_MySQL $_pdo
		 * @param $column
		 * @param $value
		 * @return array
		 */
		protected static function findRowBy(PDO_MySQL $_pdo, $column, $value) {
			$args = ["val" => $value];

			return $_pdo->fetchOne("SELECT * FROM `user_view` WHERE $column = :val", $args);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param int $id
		 * @return Admin|Employee|User|null
		 */
		public static function find(PDO_MySQL $_pdo, $id) {
			$row = self::findRowBy($_pdo, "id", $id);

			return $row ? self::getInstanceFromData($_pdo, $row) : null;
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param string $email
		 * @return Admin|Employee|User|null
		 */
		public static function findByEmail(PDO_MySQL $_pdo, $email) {
			$row = self::findRowBy($_pdo, "email", $email);

			return $row ? self::getInstanceFromData($_pdo, $row) : null;
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param string $phone
		 * @return Admin|Employee|User|null
		 */
		public static function findByPhone(PDO_MySQL $_pdo, $phone) {
			$row = self::findRowBy($_pdo, "phone", $phone);

			return $row ? self::getInstanceFromData($_pdo, $row) : null;
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return User[]
		 */
		public static function getAllActive(PDO_MySQL $_pdo) {
			$rows = $_pdo->fetchAssoc("SELECT * FROM `user_view` WHERE `active` = 1");

			/**
			 * The function array_map is used to run a single function on each array
			 * member without the need for creating temporary variables to mutate an
			 * array of values. In this case each data row is being converted to the
			 * User object type.
			 */
			return array_map(function ($row) use ($_pdo) {
				return new User($_pdo, $row);
			}, $rows);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param array $data_arr
		 * @return Admin|Employee|User|null
		 */
		public static function getInstanceFromData(PDO_MySQL $_pdo, array $data_arr) {
			switch ($data_arr["privilege_level"]) {
				case self::PRIVILEGE_USER:
					return new User($_pdo, $data_arr);
				case self::PRIVILEGE_EMPLOYEE:
					return new Employee($_pdo, $data_arr);
				case self::PRIVILEGE_ADMIN:
					return new Admin($_pdo, $data_arr);
				default:
					return null;
			}
		}

		public static function setToPrivilegeLevel(User $user) {
			$user->privilege_level = self::PRIVILEGE_USER;
			$user->_pdo->perform("UPDATE user SET privilege_level = :pl", ["pl" => self::PRIVILEGE_USER]);

			return new User($user->_pdo, get_object_vars($user));
		}

		public function referenceLibraryCard(LibraryCard $library_card) {
			if ($library_card->getUserId() != $this->id)
				throw new \InvalidArgumentException("User does not own this card");

			$this->_library_card = $library_card;
		}
	}