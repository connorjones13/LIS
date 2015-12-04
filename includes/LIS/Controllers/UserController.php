<?php
	/**
	 * Created by PhpStorm.
	 * User: Bethany
	 * Date: 11/26/2015
	 * Time: 5:18 PM
	 */

	namespace LIS\Controllers;

	use LIS\User\Admin;
	use LIS\User\Employee;
	use LIS\User\User;
	use DateTime;
	use LIS\Utility;

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

		public function updateUser(User $user, $name_first, $name_last, $email, $phone, $gender, $dob, $address_1,
		                           $address_2, $city, $state, $zip) {

			$dob = Utility::getDateTimeFromMySQLDate($dob);

			if (!$name_first)
				$this->setError(self::$ERROR_NAME_FIRST);
			else if (!$name_last)
				$this->setError(self::$ERROR_NAME_LAST);
			else if (!$email || !Utility::stringContains($email, ["@", "."]))
				$this->setError(self::$ERROR_EMAIL);
			else if (!$phone)
				$this->setError(self::$ERROR_PHONE);
			else if (!$gender)
				$this->setError(self::$ERROR_GENDER);
			else if ($dob >= new DateTime())
				$this->setError(self::$ERROR_DOB);
			else if (!$address_1)
				$this->setError(self::$ERROR_ADDRESS_1);
			else if (!$address_2)
				$this->setError(self::$ERROR_ADDRESS_2);
			else if (!$city)
				$this->setError(self::$ERROR_CITY);
			else if (!$state)
				$this->setError(self::$ERROR_STATE);
			else if (!$zip)
				$this->setError(self::$ERROR_ZIP);

			$user->updateUserInfo($name_first, $name_last, $email, $phone, $gender, $dob, $address_1, $address_2,
				$city, $state, $zip);


			$_SESSION["profile_update"] = "Successfully updated user profile!";

			$loc = '/controlpanel/users/' . $user->getId() . '/';
			self::displayPage($loc);
		}

		public function changeToUserPrivilege(User $user) {
			User::setToPrivilegeLevel($user);
			$_SESSION["profile_update"] = $user->getNameFull() . "'s privilege changed to User";
			$loc = '/controlpanel/users/' . $user->getId() . '/';
			self::displayPage($loc);
		}

		public function changeToEmployeePrivilege(User $user) {
			Employee::setToPrivilegeLevel($user);
			$_SESSION["profile_update"] = $user->getNameFull() . "'s privilege changed to Employee";
			$loc = '/controlpanel/users/' . $user->getId() . '/';
			self::displayPage($loc);
		}

		public function changeToAdminPrivilege(User $user) {
			Admin::setToPrivilegeLevel($user);
			$_SESSION["profile_update"] = $user->getNameFull() . "'s privilege changed to Admin";
			$loc = '/controlpanel/users/' . $user->getId() . '/';
			self::displayPage($loc);
		}

		// Deactivate User
		public function deactivateUser(User $user) {
			$user->setInactive();

			$_SESSION["profile_update"] = $user->getNameFull() . "'s account has been deactivated";
			$loc = '/controlpanel/users/' . $user->getId() . '/';
			self::displayPage($loc);
		}

		public function activateUser(User $user) {
			$user->setActive();

			$_SESSION["profile_update"] = $user->getNameFull() . "'s account has been activated";
			$loc = '/controlpanel/users/' . $user->getId() . '/';
			self::displayPage($loc);
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
					return "Please enter a valid address.";
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