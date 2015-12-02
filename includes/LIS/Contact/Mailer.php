<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/12/15
	 * Time: 1:13 AM
	 */

	namespace LIS\Contact;

	use LIS\User\User;
	use PHPMailer;

	class Mailer {

		/* @var PHPMailer $_mailer */
		protected $_mailer;

		public function __construct() {
			$this->_mailer = new PHPMailer(true);
			$this->_mailer->isSendmail();
		}


		public function sendPasswordResetEmail(User $user) {
			$this->_mailer->setFrom("no-reply@library.com");
			$this->_mailer->addAddress($user->getEmail(), $user->getNameFull());

			//TODO: Move this to external loading via html files if there is time.
			$this->_mailer->msgHTML("Hello " . $user->getNameFirst() . ",<br><br>Please click "
					. "<a href=\"localhost:8888/account/reset_password/?rt=" . $user->getResetToken() . "\">here</a> "
					. "to reset your password.<br>If you did not request a password reset you may ignore this email.");

			if (!$this->_mailer->send()) {
				throw new \ErrorException;
			}
		}


		public function sendAccountConfirmationEmail(User $user) {
			$this->_mailer->setFrom("no-reply@library.com");
			$this->_mailer->addAddress($user->getEmail(), $user->getNameFull());

			//TODO: Move this to external loading via html files if there is time.
			$this->_mailer->msgHTML("Hello " . $user->getNameFirst() . ",<br><br>Please click "
					. "<a href=\"localhost:8888/account/confirm/?st=" . $user->getAccountConfirmToken() . "\">here</a> "
					. "to confirm your account.");

			$this->_mailer->send();
		}
	}