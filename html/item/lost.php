<?php
require_once(__DIR__ . "/../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\RentalItemController($pdo);
$page_title = "Reservation";

$rental_item = \LIS\RentalItem\RentalItem::find($pdo, $_GET['id']);
$controller->markItemLost($rental_item);
?>

<!doctype html>
<html>

</html>
