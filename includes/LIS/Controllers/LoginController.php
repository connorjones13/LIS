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
		private $login_error = false;

		public function checkCredentials($username, $password) {
			if ($this->isLoggedIn())
				self::displayPage(self::$PAGE_HOME);

			if ($_SESSION[self::TIMEOUT] === true) {
				unset($_SESSION[self::TIMEOUT]);
				$this->setError(self::$ERROR_SESSION_TIMED_OUT);
			}

			if ($username == "" && $password == "")
				return;

			$this->_user = User::findByEmail($this->_pdo, $username);

			if (!$this->_user) {
				$this->setError(self::$ERROR_USERNAME_NOT_FOUND);
			}

			else if (!$this->_user->isActive()) {
				$this->setError(self::$ERROR_ACCOUNT_INACTIVE);
			}

			else if (!Utility::verifyPassword($password, $this->_user->getPasswordHash())) {
				$this->setError(self::$ERROR_CREDENTIALS_INVALID);
			}


			if (!$this->hasError()) {
				$_SESSION[self::$VALID_LOGIN] = $this->_user->getEmail();
				$_SESSION[self::$LAST_ACTION] = time();

				self::displayPage($_SESSION[self::$REQUEST_URI]);
			}
		}

		private function setError($error) {
			$this->login_error = $error;
		}

		public function hasError() {
			return $this->login_error !== false;
		}

		public function getError() {
			return $this->login_error;
		}
	}