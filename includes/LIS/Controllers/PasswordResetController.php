<?php
	namespace LIS\Controllers;

	use LIS\Contact\Mailer;
	use LIS\User\User;

	class PasswordResetController extends BaseController {
		private static $ERROR_USER_NOT_FOUND = 1;
		private static $ERROR_EMAIL_NO_MATCH = 2;


		public function resetPassword($confirmationEmail, $email) {
			if ($confirmationEmail != $email) {
				$this->setError(self::$ERROR_EMAIL_NO_MATCH);

				return;
			}

			$user = User::findByEmail($this->_pdo, $email);

			if (is_null($user)) {
				$this->setError(self::$ERROR_USER_NOT_FOUND);

				return;
			}

			$user->initiatePasswordReset();
			$mailer = new Mailer();
			$mailer->sendPasswordResetEmail($user);
			$_SESSION["successMessage"] = "Password reset email sent";
		}

		public function getErrorMessage() {
			switch ($this->getError()) {
				case self::$ERROR_EMAIL_NO_MATCH:
					return "Emails do not match.";
				case self::$ERROR_USER_NOT_FOUND:
					return "No user exists with that email.";
				default:
					return false;
			}
		}
	}