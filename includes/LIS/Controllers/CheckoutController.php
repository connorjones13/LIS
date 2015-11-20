<?php

	namespace LIS\Controllers;

	use LIS\RentalItem\RentalItem;
	use LIS\Checkout;
	use LIS\User\User;
	use LIS\Utility;

	class CheckoutController extends BaseController {

		public function checkoutRentalItem(array $ids, /*LibraryCard*/ $libraryCard) {

			// check that user library card is valid

			foreach($ids as $id) {

				// get rental item -> check status

				// if (rentalItem->getStatus() > 0) {
				//      if (rentalItem->getStatus() == 5) {
				//          check that this user is same user on reservation, then add to checkout basket
				//      }
				//
				//      return error that book is unavailable
				// }

				// create checkout record for item
			}

			// return due date?? Nah, employee can tell them.
			// return successful checkout!
		}

		public function checkInRentalItem(RentalItem $rentalItem) {

			$rentalItem->updateStatus(0);   // set available

			$checkout = new Checkout($this->_pdo);

			if($checkout->findActiveCheckout($rentalItem) != null) {
				$checkout->checkIn($this->getSessionUser());
			}


		}
	}