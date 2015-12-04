<?php

namespace LIS\Controllers;

use LIS\Checkout;
use LIS\RentalItem\RentalItem;
use LIS\User\User;

class ReportController extends BaseController{

    //Specific Rental ITem, who used it
    public function generateRentalItemReport ($rentalItemId) {

        $rt = RentalItem::find($this->_pdo, $rentalItemId);

        $allCheckouts = Checkout::getAllCheckoutsByItem($this->_pdo, $rt);

        return $allCheckouts;

    }
    //Employee Report is the same as this
    //Specific User/Employee and their related Rental Items/Checkouts/Checkins
    public function generateUserReport ($userId) {

        $user = User::find($this->_pdo, $userId);

        $allCheckouts = Checkout::getAllCheckoutsByUser($this->_pdo, $user);

        return $allCheckouts;

    }
    //all damaged/lost
    public function generateLibraryStatusReport () {

        $a = RentalItem::getAllLost($this->_pdo);
        $b = RentalItem::getAllDamaged($this->_pdo);
        $c = RentalItem::getAllRemoved($this->_pdo);

        $allRentalItems = array_merge($a, $b, $c);


        return $allRentalItems;
    }


}