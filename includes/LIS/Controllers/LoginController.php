<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/11/15
	 * Time: 12:09 AM
	 */

	namespace LIS\Controllers;

	use LIS\User\User;
	use LIS\Utility;

	class LoginController extends BaseController {
		private static $ERROR_CREDENTIALS_INVALID = 0;
		private static $ERROR_ACCOUNT_INACTIVE = 1;
		private static $ERROR_SESSION_TIMED_OUT = 2;
		private static $ERROR_USERNAME_NOT_FOUND = 3;

		/**
		 * @param $username
		 * @param $password
		 */
		public function checkCredentials($username, $password) {
			if ($this->isLoggedIn())
				self::displayPage(self::$PAGE_HOME);

			if ($_SESSION[self::TIMEOUT] === true) {
				unset($_SESSION[self::TIMEOUT]);
				$this->setError(self::$ERROR_SESSION_TIMED_OUT);
			}

			if ($username == "" && $password == "")
				return;

			$this->_session_user = User::findByEmail($this->_pdo, $username);

			if (!$this->_session_user) {
				$this->setError(self::$ERROR_USERNAME_NOT_FOUND);
			}

			else if (!$this->_session_user->isActive()) {
				$this->setError(self::$ERROR_ACCOUNT_INACTIVE);
			}

			else if (!Utility::verifyPassword($password, $this->_session_user->getPasswordHash())) {
				$this->setError(self::$ERROR_CREDENTIALS_INVALID);
			}


			if (!$this->hasError()) {
				$_SESSION[self::$VALID_LOGIN] = $this->_session_user->getEmail();
				$_SESSION[self::$LAST_ACTION] = time();

				self::displayPage($_SESSION[self::$REQUEST_URI]);
			}
		}

		/**
		 * @return bool|string
		 */
		public function getErrorMessage() {
			switch ($this->getError()) {
				case self::$ERROR_USERNAME_NOT_FOUND:
					return "This username does not exist. Please create an account.";
				case self::$ERROR_CREDENTIALS_INVALID:
					return "Credentials invalid. Please try again.";
				case self::$ERROR_ACCOUNT_INACTIVE:
					return "This account has been made inactive. Please contact support.";
				case self::$ERROR_SESSION_TIMED_OUT:
					return "You were logged in for too long without action and have been logged out.";
				default:
					return false;
			}
		}
	}