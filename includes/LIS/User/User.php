<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/10/15
	 * Time: 10:14 PM
	 */

	namespace LIS\User;

	use LIS\Database\PDO_MySQL;
	use DateTime;
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
				$address_country_code, $password_hash, $reset_token, $reset_token_expiry, $library_card,
				$library_card_date_issued;

		/* @var PDO_MySQL $_pdo */
		protected $_pdo; //Since this is an internal dependency, I mark it with an _

		/**
		 * User constructor. Takes a database object as a dependency and an optional
		 * array parameter to initialize the User object with data.
		 * @param PDO_MySQL $_pdo
		 * @param array $data_arr
		 */
		public function __construct(PDO_MySQL $_pdo, array $data_arr = array()) {
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
		 * @param string $password_hash
		 */
		public function create($name_first, $name_last, $email, $phone, $gender, $date_of_birth,
		                       $address_line_1, $address_line_2, $address_zip, $address_city,
		                       $address_state, $address_country_code, $password_hash) {
			$id = self::createNew($this->_pdo, $name_first, $name_last, $email, $phone, $gender,
					$date_of_birth, $address_line_1, $address_line_2, $address_zip, $address_city,
					$address_state, $address_country_code, $password_hash, self::PRIVILEGE_USER);

			$this->parse(self::findRowBy($this->_pdo, "id", $id, self::PRIVILEGE_USER));
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
		 * @param string $password_hash
		 * @param int $privilege_level
		 * @return int
		 */
		protected static function createNew(PDO_MySQL $_pdo, $name_first, $name_last, $email, $phone, $gender,
		                                  $date_of_birth, $address_line_1, $address_line_2, $address_zip, $address_city,
		                                  $address_state, $address_country_code, $password_hash, $privilege_level) {
			$time = Utility::getDateTimeForMySQLDate();

			$arguments = ["nf" => $name_first, "nl" => $name_last, "em" => $email, "ph" => $phone, "dsu" => $time,
				"ge" => $gender, "dob" => Utility::getDateTimeForMySQLDate($date_of_birth),
				"al1" => $address_line_1, "al2" => $address_line_2, "az" => $address_zip, "ac" => $address_city,
				"ads" => $address_state, "acc" => $address_country_code, "pa" => $password_hash,
				"pl" => $privilege_level
			];

			$_pdo->perform("INSERT INTO user (name_first, name_last, email, phone, date_signed_up, gender,
									date_of_birth, address_line_1, address_line_2, address_zip, address_city,
									address_state, address_country_code, password_hash, privilege_level)
									VALUES (:nf, :nl, :em, :ph, :dsu, :ge, :dob, :al1, :al2, :az, :ac, :ads, :acc,
									:pa, :pl)", $arguments);

			$id = $_pdo->lastInsertId();

			/**
			 * This while loop will usually only ever only run once. It will perform
			 * the query until it finds an unused card number and then break. I have
			 * surrounded it in a try catch to check the error code of the Exception
			 * which was thrown. If it is any other kind of exception, it will throw
			 * the exception again without catching it so we may see the error.
			 */
			while (true) {
				try {
					$arguments = [
						"uid" => $id,
						"ti" => $time,
						"lc" => Utility::getRandomString(16, true, false, true)
					];
					$_pdo->perform("INSERT INTO library_card (user, date_issued, number) VALUES (:uid, :ti, :lc)",
							$arguments);
					break;
				} catch (\PDOException $er) {
					if (!PDO_MySQL::isDuplicateKeyError($er))
						throw $er;
				}
			};

			return $id;
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

		/** @return string */
		public function getLibraryCardNumber() {
			return $this->library_card;
		}

		/** @return DateTime */
		public function getLibraryCardDateIssued() {
			return Utility::getDateTimeFromMySQLDate($this->library_card_date_issued);
		}

		/**
		 * This function returns the user's full address.
		 * @param bool|true $html - Is text for html or text-area/email?
		 * @param bool|false $country - Add country code to end?
		 * @return string
		 */
		public function getAddressFull($html = true, $country = false) {
			$tag = $html ? "<br>" : "\n";

			return $this->address_line_1
				. $tag . $this->address_line_2
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

		/** @return DateTime */
		public function getResetTokenExpiry() {
			return Utility::getDateTimeFromMySQLDate($this->reset_token_expiry);
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
		 * @param $privilege_level
		 * @return array
		 */
		protected static function findRowBy(PDO_MySQL $_pdo, $column, $value, $privilege_level) {
			$allowed_privileges = array(self::PRIVILEGE_USER, self::PRIVILEGE_EMPLOYEE, self::PRIVILEGE_ADMIN);

			if (!in_array($privilege_level, $allowed_privileges))
				throw new \InvalidArgumentException("Invalid privilege level.");


			$args = ["val" => $value, "pl" => $privilege_level];
			return $_pdo->fetchOne("SELECT * FROM `user_view` WHERE $column = :val AND privilege_level >= :pl", $args);
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param int $id
		 * @return User
		 */
		public static function find(PDO_MySQL $_pdo, $id) {
			return new User($_pdo, self::findRowBy($_pdo, "id", $id, self::PRIVILEGE_USER));
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param string $email
		 * @return User
		 */
		public static function findByEmail(PDO_MySQL $_pdo, $email) {
			return new User($_pdo, self::findRowBy($_pdo, "email", $email, self::PRIVILEGE_USER));
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @param string $phone
		 * @return User
		 */
		public static function findByPhone(PDO_MySQL $_pdo, $phone) {
			return new User($_pdo, self::findRowBy($_pdo, "phone", $phone, self::PRIVILEGE_USER));
		}

		/**
		 * @param PDO_MySQL $_pdo
		 * @return User[]
		 */
		public static function getAllActive(PDO_MySQL $_pdo) {
			$rows = $_pdo->fetchAssoc("SELECT * FROM `user` WHERE `active` = 1");

			/**
			 * The function array_map is used to run a single function on each array
			 * member without the need for creating temporary variables to mutate an
			 * array of values. In this case each data row is being converted to the
			 * User object type.
			 */
			return array_map(function($row) use ($_pdo) {
				return new User($_pdo, $row);
			}, $rows);
		}
	}