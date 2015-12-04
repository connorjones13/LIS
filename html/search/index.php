<!-- TEMPLATE -->
<?php
require_once(__DIR__ . "/../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\SearchController($pdo);

if(\LIS\Utility::requestHasPost()) {
    $rentalitems = $controller->newSearch($_POST["search_input"]);

}



$page_title = "Template";
?>
<!doctype html>
<html lang="en">
<head>
    <?php require_once(__DIR__ . "/../../includes/html_templates/head.php"); ?>
</head>
<body>
<header class="navbar navbar-fixed-top navbar-inverse">
    <?php require_once(__DIR__ . "/../../includes/html_templates/header.php"); ?>
</header>

<div class="content">
    <h2 class="sub-header">Available Items</h2>
    <div class="table-responsive">
        <table class="table table-striped" id="view_table">
            <thead>
            <tr>
                <th>ID#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Creators</th>
            </tr>
            </thead>
            <tbody id="available">
        <?php foreach ($rentalitems as $item) { ?>
                <tr>
                    <td><?= \LIS\Utility::formatNumber($item->getId()) ?></td>
                    <td><a href="/item/<?= $item->getId() ?>/"><?= $item->getTitle() ?></td>
                    <td><?= $item->getCategory() ?></td>
                    <td>
                        <?php if ($item->isBook()) { ?>
                            <?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)) ?>
                        <?php } else if ($item->isDVD()) { ?>
                            <?= $item->getDirector() ?>
                        <?php } else if ($item->isMagazine()) { ?>
                            <?= $item->getPublication() ?> (#<?= $item->getIssueNumber() ?>)
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>


</div>
<footer class="footer">
    <?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>