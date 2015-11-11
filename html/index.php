<?php
	require_once(__DIR__ . "/../includes/LIS/autoload.php");
	$session = new \LIS\Controllers\BaseController();
	$pdo = new \LIS\Database\PDO_MySQL();

	$page_title = "Test";
?>
<!doctype html>
<html lang="en">
	<head>
		<?php require_once(__DIR__ . "/../includes/html_templates/head.php"); ?>
	</head>
	<body>
		<header class="navbar navbar-fixed-top navbar-inverse">
			<?php require_once(__DIR__ . "/../includes/html_templates/header.php"); ?>
		</header>
		<div class="content">
			<?php var_dump($user = \LIS\User\User::find($pdo, 1)); ?>
			<?= $user->getId(); ?><br><br>
			<?= $user->getNameFull(); ?><br><br>
			<?= $user->getAddressFull(); ?><br><br>
			<?= $user->getDateOfBirth()->format("m-d-Y"); ?><br><br>
			<?= $user->getDateSignedUp()->format("m-d-Y"); ?><br><br>
			<?= $user->getLibraryCardNumber(); ?><br><br>
			<?= $user->getLibraryCardDateIssued()->format("m-d-Y"); ?><br><br>
		</div>
		<footer>
			<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>