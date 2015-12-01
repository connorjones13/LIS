<?php

	namespace LIS\Controllers;

	use LIS\RentalItem\RentalItem;
	use LIS\Reservation;

	class ReservationController extends BaseController {

		public function reserveRentalItem(RentalItem $rentalItem) {

			//todo: check that a reservation does not already exist (in case 2 users are on the same page)

			$reservation = null;
			$reservation = Reservation::findForItem($this->_pdo, $rentalItem);

			if($reservation != null) {
				$_SESSION["reserve_fail"] = "Sorry, but " . $rentalItem->getTitle() . " is already reserved.";
				self::displayPage("/item/" . $rentalItem->getId() . "/");
			}

			$reservation = new Reservation($this->_pdo);
			$reservation->create($this->getSessionUser(), $rentalItem);

			$_SESSION["reserve_success"] = "Successfully reserved " . $rentalItem->getTitle();
			self::displayPage("/item/" . $rentalItem->getId() . "/");

		}
	}