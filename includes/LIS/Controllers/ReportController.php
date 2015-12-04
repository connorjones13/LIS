<?php
	namespace LIS\Controllers;

	use LIS\Checkout;
	use LIS\RentalItem\RentalItem;
	use LIS\LibraryCard;

	class ReportController extends BaseController {
		private static $ITEM_ERROR = 0;
		private static $USER_ERROR = 1;

		/**
		 * @param $rentalItemId
		 * @return \LIS\Checkout[]
		 */
		public function generateRentalItemReport($rentalItemId) {
			$rt = RentalItem::find($this->_pdo, $rentalItemId);

			if (is_null($rt)) {
				$this->setError(self::$ITEM_ERROR);
				return [];
			}

			$allCheckouts = Checkout::getAllCheckoutsByItem($this->_pdo, $rt);

			return $allCheckouts;
		}

		/**
		 * Employee Report is the same as this
		 * Specific User/Employee and their related Rental Items/Checkouts/Checkins
		 *
		 * @param $cardNum
		 * @return Checkout[]
		 */
		public function generateUserReport($cardNum) {
			$card = LibraryCard::findByCardNumber($this->_pdo, $cardNum);

			if (is_null($card)) {
				$this->setError(self::$USER_ERROR);
				return [];
			}

			$user = $card->getUser();

			if (is_null($user)) {
				$this->setError(self::$USER_ERROR);
				return [];
			}

			$allCheckouts = Checkout::getAllCheckoutsByUser($this->_pdo, $user);

			return $allCheckouts;
		}

		/**
		 * @return RentalItem[]
		 */
		public function generateLibraryStatusReport() {
			return RentalItem::getAllRDL($this->_pdo);
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