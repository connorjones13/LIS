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

	class BaseController {
		const TIMEOUT = "to";
		const LOGOUT = "logout_request";

		protected static $REQUEST_URI = "REQUEST_URI";
		protected static $LAST_ACTION = "la";
		protected static $SESSION_COOKIE = "s";
		protected static $VALID_LOGIN = "valid_login";

		protected static $PAGE_LOGIN = "/login";
		protected static $PAGE_HOME = "/";
		protected static $PAGE_LOGOUT = "/logout";

		/* @var PDO_MySQL $_pdo */
		protected $_pdo;

		/* @var $_user User */
		protected $_user;

		/**
		 * @param PDO_MySQL $_pdo
		 */
		final public function __construct(PDO_MySQL $_pdo) {
			$this->_pdo = $_pdo;

			$this->startSession();
		}

		final public function validateLogin() {
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

		final public function isLoggedIn() {
			return isset($_SESSION[self::$VALID_LOGIN]);
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

		final public function getUser() {
			if (!$this->_user && $this->isLoggedIn())
				$this->_user = User::findByEmail($this->_pdo, $_SESSION[self::$VALID_LOGIN]);

			return $this->_user;
		}

		final public function logout() {
			setcookie(self::$SESSION_COOKIE, "", -1, "/", "", true);
			unset($_SESSION);

			session_destroy();
			session_start();
		}

		final protected static function displayPage($page) {
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
	}