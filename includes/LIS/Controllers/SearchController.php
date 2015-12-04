<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 12/3/15
 * Time: 7:56 PM
 */


namespace LIS\Controllers;

use LIS\RentalItem\RentalItem;

class SearchController extends BaseController {

    private static $ERROR_INVALID_SEARCH = 1;
    /**
     * @param $input
     * @return \LIS\RentalItem\Book[]|\LIS\RentalItem\DVD[]|\LIS\RentalItem\Magazine[]
     */
    public function newSearch($input)
    {
        if ($input == null) {
            $this->setError(self::$ERROR_INVALID_SEARCH);
        }

        $ItemsMatched = [];

        if (!$input == "")
            $ItemsMatched = RentalItem::search( $this->_pdo , $input);

        return $ItemsMatched;
    }


    public function getErrorMessage() {
        switch ($this->getError()) {
            case self::$ERROR_INVALID_SEARCH:
                return "The search field can not be empty, please enter a valid search.";
            default:
                return false;
        }
    }

}