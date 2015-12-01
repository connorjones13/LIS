<?php

	namespace LIS\Controllers;

	use LIS\RentalItem\RentalItem;
	use LIS\Reservation;

	class ReservationController extends BaseController {

		public function reserveRentalItem(RentalItem $rentalItem) {

			$reservation = new Reservation($this->_pdo);
			$reservation->create($this->getSessionUser(), $rentalItem);

		}
	}