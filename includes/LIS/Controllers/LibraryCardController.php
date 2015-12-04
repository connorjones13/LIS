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
     * @param data_array
     */
    public function addNewLibraryCard(User $user) {

        // get user from email
        //$user = User::findByEmail($this->_pdo, $email);

        // get old card or default card
        $card = $user->getLibraryCard();

        // Make old card or default card inactive
        $card->setStatus(LibraryCard::STATUS_INACTIVE);

        // create new Library Card
        $card = new LibraryCard($this->_pdo);
        $card->create($user);

        $card->setStatus(LibraryCard::STATUS_ACTIVE);

        $_SESSION["profile_update"] = "Card with number: " . $card->getNumber() . " was successfully added to "
            . $user->getNameFull() . "'s account";
        $loc = '/controlpanel/users/user/' . $user->getId() . '/';
        self::displayPage($loc);
    }
}