<?php

	namespace LIS\Controllers;

	use LIS\RentalItem\RentalItem;
	use LIS\Checkout;
	use LIS\Reservation;
	use LIS\User\User;
	use LIS\Utility;

	class ReservationController extends BaseController {

		public function reserveRentalItem(RentalItem $rentalItem) {

			$reservation = new Reservation($this->_pdo);

			//todo: how does the controller pass along the item and the user so that the create function in reservation works?

			$rentalItem->updateStatus(5);   // set item status to reserved
		}
	}