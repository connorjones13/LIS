<?php

	namespace LIS\Controllers;

	use LIS\LibraryCard;
	use LIS\RentalItem\RentalItem;
	use LIS\Checkout;
	use LIS\Reservation;

	class CheckoutController extends BaseController {
		private static $ERROR_ITEM_RESERVED = 0;
		private static $ERROR_ITEM_UNAVAILABLE = 1;
		private static $ERROR_ITEM_NOT_CHECKED_OUT = 2;
		private static $ERROR_ITEM_DOES_NOT_EXIST = 3;
		private static $ERROR_LIBRARY_CARD_NOT_FOUND = 4;
		private static $ERROR_LIBRARY_CARD_NOT_ACTIVE = 5;
		private static $ERROR_LIBRARY_CARD_NOT_ATTACHED = 6;
		private static $ERROR_USER_NOT_ACTIVE = 7;
		private static $ERROR_ITEM_CHECKED_OUT = 8;
		private static $ERROR_ENTER_ITEMS = 9;

		private $error_in_id;

		public function checkoutRentalItem(array $ids, $library_card_num) {

			// get library card from card number
			$lib_card = LibraryCard::findByCardNumber($this->_pdo, $library_card_num);

			if (is_null($lib_card)) {
				$this->setError(self::$ERROR_LIBRARY_CARD_NOT_FOUND);
				return;
			}

			if ($lib_card->getStatus() != LibraryCard::STATUS_ACTIVE) {
				$this->setError(self::$ERROR_LIBRARY_CARD_NOT_ACTIVE);
				return;
			}

			$user = $lib_card->getUser();   // get user from library card

			if (is_null($user)) {
				$this->setError(self::$ERROR_LIBRARY_CARD_NOT_ATTACHED);
				return;
			}

			if (!$user->isActive()) {
				$this->setError(self::$ERROR_USER_NOT_ACTIVE);
				return;
			}

			$ids = array_filter($ids);      // remove null values if not checking out max items

			if (empty($ids)) {
				$this->setError(self::$ERROR_ENTER_ITEMS);
				return;
			}

			$ris = [];

			foreach ($ids as $id) {
				// get rental item -> check status
				$rental_item = RentalItem::find($this->_pdo, $id);

				if (is_null($rental_item)) {
					$this->setError(self::$ERROR_ITEM_DOES_NOT_EXIST);

					$this->error_in_id = $id;
					return;
				}

				if ($rental_item->getStatus() != RentalItem::STATUS_AVAILABLE) {
					if ($rental_item->getStatus() == RentalItem::STATUS_CHECKED_OUT) {
						$this->setError(self::$ERROR_ITEM_CHECKED_OUT);
					}
					else if ($rental_item->getStatus() == RentalItem::STATUS_RESERVED) {
						$reservation = Reservation::findForRentalItem($this->_pdo, $rental_item);

						if ($user->getId() != $reservation->getUserId())
							$this->setError(self::$ERROR_ITEM_RESERVED);
					}
					else {
						$this->setError(self::$ERROR_ITEM_UNAVAILABLE);
					}

					$this->error_in_id = $id;
					return;
				}

				$ris[] = $rental_item;
			}

			foreach ($ris as $ri) {
				// create checkout record for item
				$checkout = new Checkout($this->_pdo);
				$checkout->create($this->getSessionUser(), $user, $ri);

				$_SESSION["checkout_alert"] = "Successfully checked out items for " . $user->getNameFull()
					. ". Item(s) due " . $checkout->getDateDue()->format("m-d-Y");
			}
		}

		public function checkInRentalItem($rental_item_id) {

			$rental_item = RentalItem::find($this->_pdo, $rental_item_id);

			if (is_null($rental_item)) {
				$this->setError(self::$ERROR_ITEM_DOES_NOT_EXIST);
				return;
			}

			$rental_item->markCheckedIn();

			$checkout = Checkout::findActiveCheckout($this->_pdo, $rental_item);

			if (is_null($checkout)) {
				$this->setError(self::$ERROR_ITEM_NOT_CHECKED_OUT);
				return;
			}

			$checkout->checkIn($this->getSessionUser());
			$_SESSION["checkin_alert"] = "Successfully checked in " . $rental_item->getTitle();
		}

		public function getErrorMessage() {
			switch ($this->getError()) {
				case self::$ERROR_ITEM_RESERVED:
					return "The highlighted item is already reserved by another user and cannot be checked out.";
				case self::$ERROR_ITEM_UNAVAILABLE:
					return "The highlighted item is unavailable.";
				case self::$ERROR_ITEM_NOT_CHECKED_OUT:
					return "This item is not already checked out.";
				case self::$ERROR_ITEM_DOES_NOT_EXIST:
					return "The highlighted item does not exist.";
				case self::$ERROR_LIBRARY_CARD_NOT_FOUND:
					return "The library card entered was not found.";
				case self::$ERROR_LIBRARY_CARD_NOT_ACTIVE:
					return "The library card entered is no longer active.";
				case self::$ERROR_LIBRARY_CARD_NOT_ATTACHED:
					return "The library card entered is not attached to a user.";
				case self::$ERROR_USER_NOT_ACTIVE:
					return "The found user is no longer active.";
				case self::$ERROR_ITEM_CHECKED_OUT:
					return "The highlighted item is already checked out.";
				case self::$ERROR_ENTER_ITEMS:
					return "Please enter at least one item.";
				default:
					return false;
			}
		}

		public function getErrorInId() {
			return $this->error_in_id;
		}
	}