<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\CreateUserController($pdo); //todo: why is this createusercontroller?

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
			<?php if ($_SESSION["successful_update"]) { ?>
				<p class="alert alert-success"><?= $_SESSION["successful_update"] ?></p>
				<?php unset($_SESSION["successful_update"]) ?>
			<?php } ?>
			<?php if ($_SESSION["reserve_success"]) { ?>
				<p class="alert alert-success"><?php echo $_SESSION["reserve_success"] ?></p>
				<?php unset($_SESSION["reserve_success"]) ?>
			<?php } ?>
			<?php if ($_SESSION["reserve_fail"]) { ?>
				<p class="alert alert-danger"><?php echo $_SESSION["reserve_fail"] ?></p>
				<?php unset($_SESSION["reserve_fail"]) ?>
			<?php } ?>
			<?php if ($_SESSION["lost_success"]) { ?>
				<p class="alert alert-success"><?php echo $_SESSION["lost_success"] ?></p>
				<?php unset($_SESSION["lost_success"]) ?>
			<?php } ?>
			<?php if ($_SESSION["damaged_success"]) { ?>
				<p class="alert alert-success"><?php echo $_SESSION["damaged_success"] ?></p>
				<?php unset($_SESSION["damaged_success"]) ?>
			<?php } ?>

			<div class="pull-left ip-img">
				<img src="http://lorempixel.com/400/400/cats">
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
					<a href="/item/reserve/<?= $item->getId() ?>/" class="btn btn-default btn-success">Reserve</a>
				<?php } ?>
				<?php if ($controller->isLoggedIn() &&
						$controller->getSessionUser()->getPrivilegeLevel() > \LIS\User\User::PRIVILEGE_USER) { ?>
					<!-- todo: change url depending on item type || make the update page generic? -->
					<a href="/controlpanel/update/<?= $item->getId() ?>/"
					   class ="btn btn-default btn-warning">Edit Item</a>
					<?php if ($controller->getSessionUser()->getPrivilegeLevel() > \LIS\User\User::PRIVILEGE_EMPLOYEE) { ?>
						<?php if($item->getStatus() != \LIS\RentalItem\RentalItem::STATUS_DAMAGED) { ?>
							<a href="/item/damaged/<?= $item->getId() ?>/" class="btn btn-default btn-info">Mark Damaged</a>
						<?php } ?>
						<?php if($item->getStatus() != \LIS\RentalItem\RentalItem::STATUS_LOST) { ?>
						<a href="/item/lost/<?= $item->getId() ?>/" class="btn btn-default btn-info">Mark Lost</a>
						<?php } ?>
						<?php if($item->getStatus() != \LIS\RentalItem\RentalItem::STATUS_AVAILABLE) { ?>
							<a href="/item/available/<?= $item->getId() ?>/" class="btn btn-default btn-info">Mark Available</a>
						<?php } ?>
							<?php
							var_dump($item->getStatus())
						?>
					<?php } ?>
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
