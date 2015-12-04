<?php
	namespace LIS\Controllers;

	use LIS\RentalItem\RentalItem;
	use LIS\Reservation;

	class ReservationController extends BaseController {

		public function reserveRentalItem(RentalItem $rentalItem) {
			$reservation = null;
			$reservation = Reservation::findForRentalItem($this->_pdo, $rentalItem);

			if ($reservation != null) {
				$_SESSION["reserve_fail"] = "Sorry, but " . $rentalItem->getTitle() . " is already reserved.";
				self::displayPage("/item/" . $rentalItem->getId() . "/");
			}

			$reservation = new Reservation($this->_pdo);
			$reservation->create($this->getSessionUser(), $rentalItem);

			$_SESSION["reserve_success"] = "Successfully reserved " . $rentalItem->getTitle();
			self::displayPage("/item/" . $rentalItem->getId() . "/");
		}
	}