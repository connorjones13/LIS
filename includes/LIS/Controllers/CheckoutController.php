<?php

	namespace LIS\Controllers;

	use LIS\LibraryCard;
	use LIS\RentalItem\RentalItem;
	use LIS\Checkout;
	use LIS\User\User;

	class CheckoutController extends BaseController {

		public function checkoutRentalItem(array $ids, $library_card_num) {

			// get library card from card number
			$lib_card = LibraryCard::findByCardNumber($this->_pdo, $library_card_num);
			// get user from library card
			$user = $lib_card->getUser();
			$ids = array_filter($ids);
			// get checkout employee

			foreach($ids as $id) {

				// get rental item -> check status
				$rental_item = RentalItem::find($this->_pdo, $id);

				if($rental_item->getStatus() > 0) {
					if($rental_item->getStatus() == 5) {
						// check that this user is same user on reservation, then add to checkout basket
					}

					// return error that book is unavailable
				}


				// create checkout record for item
				$checkout = new Checkout($this->_pdo);
//				var_dump($rental_item);
				$checkout->create($this->getSessionUser(), $user, $rental_item);
			}

			// return due date & successful checkout message!
		}

		public function checkInRentalItem(RentalItem $rentalItem) {

			$rentalItem->markAvailable();   // set available

			$checkout = new Checkout($this->_pdo);

			if($checkout->findActiveCheckout($rentalItem) != null) {
				$checkout->checkIn($this->getSessionUser());
			}


		}
	}