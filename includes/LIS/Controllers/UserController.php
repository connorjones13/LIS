<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 12/2/15
 * Time: 11:23 AM
 */

	namespace LIS\Controllers;


	use LIS\User\User;
	use LIS\Utility;
	use DateTime;

	class UserController extends BaseController {

		private static $ERROR_NAME_FIRST = 0;
		private static $ERROR_NAME_LAST = 1;
		private static $ERROR_EMAIL = 2;
		private static $ERROR_PHONE = 3;
		private static $ERROR_GENDER = 4;
		private static $ERROR_DOB = 5;
		private static $ERROR_ADDRESS_1 = 6;
		private static $ERROR_ADDRESS_2 = 7;
		private static $ERROR_CITY = 8;
		private static $ERROR_STATE = 9;
		private static $ERROR_ZIP = 10;

		public function updateUser(User $user, $name_first, $name_last, $email,
		                           $phone, $gender, $dob, $address_1, $address_2, $city, $state, $zip ) {
			if(!$name_first)
				$this->setError(self::$ERROR_NAME_FIRST);
			elseif(!$name_last)
				$this->setError(self::$ERROR_NAME_LAST);
			elseif(!$email)
				$this->setError(self::$ERROR_EMAIL);
			elseif(!$phone)
				$this->setError(self::$ERROR_PHONE);
			elseif(!$gender)
				$this->setError(self::$ERROR_GENDER);
			else if (Utility::getDateTimeFromMySQLDate($dob) >= new DateTime())
				$this->setError(self::$ERROR_DOB);
			elseif(!$address_1)
				$this->setError(self::$ERROR_ADDRESS_1);
			elseif(!$address_2)
				$this->setError(self::$ERROR_ADDRESS_2);
			elseif(!$city)
				$this->setError(self::$ERROR_CITY);
			elseif(!$state)
				$this->setError(self::$ERROR_STATE);
			elseif(!$zip)
				$this->setError(self::$ERROR_ZIP);

			// update user

			$_SESSION["profile_update_success"] = "Successfully updated user profile!";
			$loc = 'Location: /controlpanel/users/user' . $user->getId() . '/';
			header($loc);

		}

		public function getErrorMessage() {
			switch ($this->getError()) {
				case self::$ERROR_NAME_FIRST:
					return "Please enter a valid first name.";
				case self::$ERROR_NAME_LAST:
					return "Please enter a valid last name.";
				case self::$ERROR_EMAIL:
					return "Please enter a valid email.";
				case self::$ERROR_PHONE:
					return "Please enter a valid phone number";
				case self::$ERROR_GENDER:
					return "Please select a gender.";
				case self::$ERROR_DOB:
					return "Please enter a valid birthday.";
				case self::$ERROR_ADDRESS_1:
					return"Please enter a valid address.";
				case self::$ERROR_ADDRESS_2:
					return "Please enter a valid address.";
				case self::$ERROR_CITY:
					return "Please enter a valid city.";
				case self::$ERROR_STATE:
					return "Please select a valid state.";
				case self::$ERROR_ZIP:
					return "Please enter a valid zip code.";
				default:
					return false;
			}
		}
	}