<?php

namespace LIS\Controllers;

use LIS\Checkout;
use LIS\RentalItem\RentalItem;
use LIS\LibraryCard;

class ReportController extends BaseController{

    private static $ITEM_ERROR = 0;
    private static $USER_ERROR = 1;

    /**
     * @param $rentalItemId
     * @return \LIS\Checkout[]
     */
    public function generateRentalItemReport ($rentalItemId) {

        $rt = RentalItem::find($this->_pdo, $rentalItemId);

        if($rt == NULL) {
            $this->setError(self::$ITEM_ERROR);
            return;
        }

        $allCheckouts = Checkout::getAllCheckoutsByItem($this->_pdo, $rt);

        return $allCheckouts;

    }
    //Employee Report is the same as this
    //Specific User/Employee and their related Rental Items/Checkouts/Checkins
    /**
     * @param $cardNum
     * @return Checkout[]
     */
    public function generateUserReport ($cardNum) {

        $card = LibraryCard::findByCardNumber($this->_pdo, $cardNum);
        if($card == NULL) {
            $this->setError(self::$USER_ERROR);
            return;
        }
        $user = $card->getUser();

        if($user == NULL) {
            $this->setError(self::$USER_ERROR);
            return;
        }

        $allCheckouts = Checkout::getAllCheckoutsByUser($this->_pdo, $user);

        return $allCheckouts;

    }
    //all damaged/lost
    /**
     * @return RentalItem[]
     */
    public function generateLibraryStatusReport () {

        $a = RentalItem::getAllLost($this->_pdo);
        $b = RentalItem::getAllDamaged($this->_pdo);
        $c = RentalItem::getAllRemoved($this->_pdo);

        $allRentalItems = array_merge($a, $b, $c);


        return $allRentalItems;
    }

    public function getErrorMessage() {
        switch ($this->getError()) {
            case self::$ITEM_ERROR:
                return "Please enter a valid Item ID";
            case self::$USER_ERROR:
                return "Please enter a valid Library Card Number";
            default:
                return false;
        }
    }



}