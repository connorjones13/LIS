<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/11/15
	 * Time: 12:23 AM
	 */

	namespace LIS\Controllers;


	use LIS\Database\PDO_MySQL;
	use LIS\User\User;
	use LIS\Utility;

	class BaseController {
		const TIMEOUT = "to";
		const LOGOUT = "logout_request";

		private static $REQUEST_URI = "REQUEST_URI";
		private static $LAST_ACTION = "la";
		private static $SESSION_COOKIE = "s";
		private static $VALID_LOGIN = "valid_login";

		private static $PAGE_LOGIN = "/login";
		private static $PAGE_HOME = "/";
		private static $PAGE_LOGOUT = "/logout";

		static $ERROR_CREDENTIALS_INVALID = 0;
		static $ERROR_ACCOUNT_INACTIVE = 1;
		static $ERROR_SESSION_TIMED_OUT = 2;
		static $ERROR_USERNAME_NOT_FOUND = 3;

		/* @var PDO_MySQL $_pdo */
		protected $_pdo;

		/* @var $_user User */
		protected $_user;

		private $login_error = false;

		/**
		 * Session constructor. Basically the Login controller
		 * This class is going to be much larger
		 * @param PDO_MySQL $_pdo
		 */
		public function __construct(PDO_MySQL $_pdo) {
			$this->_pdo = $_pdo;

			$this->startSession();
		}

		public function validateLogin() {
			if ($this->isLoggedIn()) {
				if ($this->isLogoutRequested())
					$this->logout();

				if ($this->isTimedOut())
					$this->logout();
			}
			else {
				$_SESSION[self::$REQUEST_URI] = $_SERVER[self::$REQUEST_URI];

				self::displayPage(self::$PAGE_LOGIN);
			}
		}

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

			if (!$this->_user || !$this->_user->getId()) {
				$this->setError(self::$ERROR_USERNAME_NOT_FOUND);
			}

			else if (!$this->_user->isActive()) {
				$this->setError(self::$ERROR_ACCOUNT_INACTIVE);
			}

			else if (!Utility::verifyPassword($password, $this->_user->getPasswordHash())) {
				$this->setError(self::$ERROR_CREDENTIALS_INVALID);
			}


			if (!$this->hasError()) {
				$_SESSION[self::$VALID_LOGIN] = true;
				$_SESSION[self::$LAST_ACTION] = time();

				self::displayPage($_SESSION[self::$REQUEST_URI]);
			}
		}

		private function isLoggedIn() {
			return isset($_SESSION[self::$VALID_LOGIN]) && $_SESSION[self::$VALID_LOGIN] === true;
		}

		private function isTimedOut() {
			if (time() > $_SESSION[self::$LAST_ACTION] + 3600) //Seconds in an hour
				return true;

			$_SESSION[self::$LAST_ACTION] = time();
			return false;
		}

		private function isLogoutRequested() {
			return isset($_POST[self::LOGOUT]);
		}

		public function getUser() {
			return $this->_user;
		}

		private function logout() {
			setcookie(self::$SESSION_COOKIE, "", -1, "/", "", true);
			unset($_SESSION);

			session_destroy();
			session_start();

			self::displayPage(self::$PAGE_LOGOUT);
		}

		private static function displayPage($page) {
			$allowed_pages = [self::$PAGE_LOGIN, self::$PAGE_HOME, self::$PAGE_LOGOUT, $_SESSION[self::$REQUEST_URI]];

			if (!in_array($page, $allowed_pages))
				$page = self::$PAGE_HOME;

			header("Location: /" . ltrim($page, "/"));
			exit();
		}

		private function startSession() {
			date_default_timezone_set("America/Chicago");

			session_name(self::$SESSION_COOKIE);
			session_set_cookie_params(0, "/", NULL);
			session_start();

			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
			ini_set("display_errors", 1);
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