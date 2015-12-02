<?php
/**
 * Created by PhpStorm.
 * User: ski
 * Date: 11/11/15
 * Time: 12:09 AM
 */

namespace LIS\Controllers;

use LIS\Contact\Mailer;
use LIS\User\User;
use LIS\Utility;

class PasswordResetController extends BaseController {
    private static $ERROR_USER_NOT_FOUND = 69;


    public function resetPassword($confirmationEmail, $email) {
        if($confirmationEmail == $email) {
            $user = User::findByEmail($this->_pdo, $email);

            if(is_null($user)) {
                //error
                $this->setError(self::$ERROR_USER_NOT_FOUND);
            } else {
                $user->initiatePasswordReset();
                $mailer = new Mailer();
                $mailer->sendPasswordResetEmail($user);
                $_SESSION["successMessage"] = "Password reset email sent";
            }
        }
    }

    public function getErrorMessage() {
        switch ($this->getError()) {
            case self::$ERROR_USER_NOT_FOUND:
                return "User not found.";
            default:
                return false;
        }
    }

}