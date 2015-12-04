<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 8:39 AM
 */

require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\ReportController($pdo);

    $rental_items = $controller->generateLibraryStatusReport($_POST["bad_eggs"]);

$page_title = "Library Report";
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
        <?php if ($controller->getSessionUser()->isEmployee() || $controller->getSessionUser()->isAdmin()) { ?>
        <div class="row">
            <?php require_once(__DIR__ . "/../../../includes/html_templates/control_panel_nav.php"); ?>
            <div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
                <h1 class="page-header">Library Status Report</h1>
                <div class="table-responsive">
                    <table class="table table-striped" id="view_table">
                        <thead>
                        <tr>
                            <th>Rental Item</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="available">
                        <?php if (isset($rental_items)) { ?>
                            <?php foreach ($rental_items as $rental_item) { ?>
                                <tr>
                                    <td>
                                        <a href="/item/<?= $rental_item->getId() ?>/">
                                            <?= $rental_item->getTitle() ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php switch($rental_item->getStatus()) {
                                            case \LIS\RentalItem\RentalItem::STATUS_DAMAGED: echo "Damaged"; break;

                                            case \LIS\RentalItem\RentalItem::STATUS_LOST: echo "Lost"; break;

                                            case \LIS\RentalItem\RentalItem::STATUS_REMOVED: echo "Removed"; break;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
        <h4 class="alert bg-warning">
            You do not have permission to view this page.
        </h4>
    <?php } ?>
</div>
<footer class="footer">
    <?php require_once(__DIR__ . "/../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>
