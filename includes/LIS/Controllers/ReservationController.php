<?php

	namespace LIS\Controllers;

	use LIS\RentalItem\RentalItem;
	use LIS\Reservation;

	class ReservationController extends BaseController {

		public function reserveRentalItem(RentalItem $rentalItem) {

			$reservation = new Reservation($this->_pdo);

			//todo: how does the controller pass along the item and the user so that the create function in reservation works?

			$rentalItem->markReserved();   // set item status to reserved
		}
	}