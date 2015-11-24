<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\CreateUserController($pdo);

	$item = \LIS\RentalItem\RentalItem::find($pdo, $_GET["id"]);

	$page_title = $item ? $item->getTitle() : "No Item";
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
	<?php if (!is_null($item)) { ?>
		<div class="container ip-container">
			<div class="pull-left ip-img">
				<img src="http://lorempixel.com/400/400/">
			</div>
			<div class="">
				<h1><?= $item->getTitle() ?></h1>
				<h4>Category: <?= $item->getCategory() ?></h4>
				<p><?= $item->getSummary() ?></p>
				<p>Published: <?= $item->getDatePublished()->format("m-d-Y") ?></p>
				<?php if ($item->isBook()) { ?>
					<p>ISBN10: <?= $item->getISBN10() ?></p>
					<p>ISBN13: <?= $item->getISBN13() ?></p>
					<p>Authors: <?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)); ?></p>
				<?php } else if ($item->isDVD()) { ?>
					<p>Director: <?= $item->getDirector() ?></p>
				<?php } else if ($item->isMagazine()) { ?>
					<p>Publication: <?= $item->getPublication() ?></p>
					<p>Issue #: <?= $item->getIssueNumber() ?></p>
				<?php } ?>

				<?php if ($controller->isLoggedIn()) { ?>
					<a href="#" class="btn btn-default btn-success">Reserve</a>
				<?php } ?>
				<?php if ($controller->isLoggedIn() &&
						$controller->getSessionUser()->getPrivilegeLevel() > \LIS\User\User::PRIVILEGE_USER) { ?>
					<!-- todo: change url depending on item type || make the update page generic? -->
					<a href="/controlpanel/update/update_book.php?id=<?= $item->getId() ?>"
					   class ="btn btn-default btn-warning">Edit Item</a>
				<?php } ?>
			</div>
		</div>
	<?php } else { ?>
	<div class="container ip-container">
		No item found by that id
	</div>
	<?php } ?>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>
