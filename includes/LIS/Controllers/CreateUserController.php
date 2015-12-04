<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/18/15
	 * Time: 5:19 PM
	 */

	namespace LIS\Controllers;

	use DateTime;
	use LIS\Contact\Mailer;
	use LIS\LibraryCard;
	use LIS\User\User;
	use LIS\Utility;

	class CreateUserController extends BaseController {
		private static $ERROR_NAME_FIRST = 0;
		private static $ERROR_NAME_LAST = 1;
		private static $ERROR_EMAIL = 2;
		private static $ERROR_PHONE = 3;
		private static $ERROR_GENDER = 4;
		private static $ERROR_DOB = 5;
		private static $ERROR_AL1 = 6;
		private static $ERROR_AZ = 7;
		private static $ERROR_AC = 8;
		private static $ERROR_AS = 9;
		private static $ERROR_ACC = 10;
		private static $ERROR_PASSWORD_INVALID = 11;
		private static $ERROR_PASSWORD_NOT_MATCH = 11;
		private static $ERROR_USER_ALREADY_EXISTS = 12;

		/**
		 * @param string $name_first
		 * @param string $name_last
		 * @param string $email
		 * @param string $phone
		 * @param int $gender
		 * @param string $date_of_birth
		 * @param string $address_line_1
		 * @param string $address_line_2
		 * @param string $address_zip
		 * @param string $address_city
		 * @param string $address_state
		 * @param string $password
		 * @param string $password_confirm
		 */
		public function createNewUser($name_first, $name_last, $email, $phone, $gender, $date_of_birth,
		                              $address_line_1, $address_line_2, $address_zip, $address_city,
		                              $address_state, $password, $password_confirm) {
			$date_of_birth = Utility::getDateTimeFromUserInput($date_of_birth);

			if (!$name_first)
				$this->setError(self::$ERROR_NAME_FIRST);

			else if (!$name_last)
				$this->setError(self::$ERROR_NAME_LAST);

			else if (!$email || !Utility::stringContains($email, ["@", "."]))
				$this->setError(self::$ERROR_EMAIL);

			else if (!is_null(User::findByEmail($this->_pdo, $email)))
				$this->setError(self::$ERROR_USER_ALREADY_EXISTS);

			else if (!$password)
				$this->setError(self::$ERROR_PASSWORD_INVALID);

			else if ($password !== $password_confirm)
				$this->setError(self::$ERROR_PASSWORD_NOT_MATCH);

			else if (!$phone || strlen(Utility::cleanPhoneString($phone)) < 10)
				$this->setError(self::$ERROR_PHONE);

			else if ($gender > User::GENDER_OTHER)
				$this->setError(self::$ERROR_GENDER);

			else if ($date_of_birth >= new DateTime())
				$this->setError(self::$ERROR_DOB);

			else if (!$address_line_1)
				$this->setError(self::$ERROR_AL1);

			else if (!$address_zip)
				$this->setError(self::$ERROR_AZ);

			else if (!$address_city)
				$this->setError(self::$ERROR_AC);

			else if (!$address_state)
				$this->setError(self::$ERROR_AS);

			if ($this->hasError())
				return;

			$library_card = new LibraryCard($this->_pdo);

			$user = new User($this->_pdo);
			$user->create($name_first, $name_last, $email, $phone, $gender,
					$date_of_birth, $address_line_1, $address_line_2, $address_zip,
					$address_city, $address_state, "USA", $password, $library_card);

			$mailer = new Mailer();
			$mailer->sendAccountConfirmationEmail($user);
		}

		/**
		 * @return bool|string
		 */
		public function getErrorMessage() {
			switch ($this->getError()) {
				case self::$ERROR_NAME_FIRST:
					return "Please enter a first name.";
				case self::$ERROR_NAME_LAST:
					return "Please enter a last name.";
				case self::$ERROR_EMAIL:
					return "Please enter a valid email.";
				case self::$ERROR_PHONE:
					return "Please enter a valid phone number";
				case self::$ERROR_GENDER:
					return "Invalid gender.";
				case self::$ERROR_DOB:
					return "Please enter a valid date of birth.";
				case self::$ERROR_AL1:
					return "Please enter a valid address.";
				case self::$ERROR_AZ:
					return "Please enter a valid zip code.";
				case self::$ERROR_AC:
					return "Please enter a valid city.";
				case self::$ERROR_AS:
					return "Please choose a valid state.";
				case self::$ERROR_ACC:
					return "Please enter a valid country code.";
				case self::$ERROR_PASSWORD_INVALID:
					return "Please enter a valid password.";
				case self::$ERROR_PASSWORD_NOT_MATCH:
					return "Your passwords do not match.";
				case self::$ERROR_USER_ALREADY_EXISTS:
					return "A user with this email already exists.";
				default:
					return false;
			}
		}
	}