<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\ReservationController($pdo);
	$page_title = "Reservation";

	$rental_item = \LIS\RentalItem\RentalItem::find($pdo, $_GET['id']);
	$controller->reserveRentalItem($rental_item);