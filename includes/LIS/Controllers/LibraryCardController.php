<?php
/**
 * Created by PhpStorm.
 * User: Bethany
 * Date: 11/19/2015
 * Time: 5:49 PM
 */


namespace LIS\Controllers;

use LIS\User\User;
use LIS\LibraryCard;

class LibraryCardController extends BaseController {

    /*
     * @param email
     * @param data_array            // what data array (card number??)
     */
    public function addNewLibraryCard($email, $data_array) {

        // get user from email
        $user = User::findByEmail($this->_pdo, $email);
        if ($user == null) {
            // TODO: no email found error
        }

        // see if user has a card associated with it
        $card = $user->getLibraryCard();

        if ($card != null) {
            // set old card status to inactive
            $card->setStatus(LibraryCard::STATUS_INACTIVE);
        }

        // create new Library Card
        $card = new LibraryCard($this->_pdo, $data_array);
        $card->create($user);

        // check to see if card was assigned to user
        if ($card != LibraryCard::findByUser($this->_pdo, $user)) {
            // TODO: error message card not issued to User
        }
        else {
            $card->setStatus(LibraryCard::STATUS_ACTIVE);
            // TODO: success message "card issued to User with email:" $email
        }

    }
}