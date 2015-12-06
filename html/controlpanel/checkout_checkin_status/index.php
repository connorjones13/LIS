<?php
require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\UserController($pdo);

if (is_null($controller->getSessionUser())
    || $controller->getSessionUser()->getPrivilegeLevel() < \LIS\User\User::PRIVILEGE_EMPLOYEE) {
    header("Location: /");
    exit();
}

$page_title = "User Report";
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
                <?php require_once(__DIR__ . "/../../../includes/html_templates/control_panel_nav.php"); ?>
                <div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
                    <h1 class="page-header">User Report</h1>

                    <p style="font-size:24px"><b>Reports</b></p>


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


                    <p style="font-size:24px"><b>Reservations</b></p>


                    <table class="table table-striped" id="view_table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Author</th>
                            <th>Date Reserved</th>
                            <th>Item Status</th>
                            <th>Reservation Expiration Date</th>
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