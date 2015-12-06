<?php
require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\UserController($pdo);

if (is_null($controller->getSessionUser())
    || $controller->getSessionUser()->getPrivilegeLevel() < \LIS\User\User::PRIVILEGE_EMPLOYEE) {
    header("Location: /");
    exit();
}

$page_title = "Status Report";
?>
<!doctype html>
<html lang="en">
<head>
    <?php require_once(__DIR__ . "/../../../includes/html_templates/head.php"); ?>
</head>
<body>
<header class="navbar navbar-fixed-top navbar-inverse">
    <?php require_once(__DIR__ . "/../../../includes/html_templates/header.php"); ?>
</header>
<div class="container">
    <div class="container-fluid">
        <?php if ($controller->getSessionUser()->isEmployee() || $controller->getSessionUser()->isActive()) { ?>
            <div class="row">
                <div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
                    <h1 class="page-header">Status Report</h1>

                    <p style="font-size:24px"><b>Currently Checked Out Items</b></p>


                    <table class="table table-striped" id="view_table">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date Out</th>
                            <th>Due date</th>
                            <th>Late Fee</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $user = $controller->getSessionUser() ?>
                        <?php $checkOuts = \LIS\Checkout::getAllCheckoutsByUser($pdo,$user); ?>
                        <?php foreach ($checkOuts as $rentalItem) { ?>
                            <tr>
                                <?php $dateOne = $rentalItem->getDateCheckedOut();?>
                                <?php $dateTwo = $rentalItem->getDateDue();?>
                                <?php $fee = date_diff($dateOne,$dateTwo)->days * 1.5;?>
                                <td><?= $rentalItem->getRentalItem()->getTitle(); ?></td>
                                <td><?= $dateOne->format('Y-m-d'); ?></td>
                                <td><?= $dateTwo->format('Y-m-d') ?></td>
                                <?php if($fee > 0) {
                                    $fee = 0;
                                } ?>
                                <td><?= "\$".$fee ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>


                    <p style="font-size:24px"><b>Your Reserved Items</b></p>


                    <table class="table table-striped" id="view_table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Date Reserved</th>
                            <th>Item Status</th>
                            <th>Time Left on Reservation</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $user = $controller->getSessionUser() ?>
                        <?php $reservations = \LIS\Reservation::getAllReservationsByUser($pdo,$user); ?>
                        <?php foreach ($reservations as $reservedItem) { ?>
                            <tr>
                                <?php $yesOrNo = "Checked Out"; ?>
                                <?php $status = $reservedItem->getRentalItem()->getStatus(); ?>
                                <?php if($status != 4) {
                                    $yesOrNo = "Available for Pick up";
                                } ?>
                                <td><?= $reservedItem->getRentalItem()->getTitle(); ?></td>
                                <td><?= $reservedItem->getDateCreated()->format('Y-m-d');?></td>
                                <td><?= $yesOrNo ?></td>
<!--                                --><?php //$expirationDate = $reservedItem->day + 1; ?>
                                <td> <?php if($status) { ?>
                                        1 day
                                <?php } else { ?>
                                    None.
                                <?php } ?></td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        <?php }
        else { ?>
            <h4 class="alert bg-warning">
                You do not have permission to view this page.
            </h4>
        <?php } ?>
    </div>
</div>
<footer class="footer">
    <?php require_once(__DIR__ . "/../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>