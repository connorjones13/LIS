<?php
	require_once(__DIR__ . "/../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$session = new \LIS\Controllers\BaseController($pdo);

	$page_title = "Test";
?>
<!doctype html>
<html lang="en">
	<head>
		<?php require_once(__DIR__ . "/../includes/html_templates/head.php"); ?>
	</head>
	<body>
		<header>
			<?php require_once(__DIR__ . "/../includes/html_templates/header.php"); ?>
		</header>
		<div class="content">
			<?php $user = new \LIS\User\Admin($pdo); ?>
			<?php
				$user->create("Testy", "McTesterson", "test@example.com", "1235554321", 1,
						DateTime::createFromFormat("m-d-Y", "11-10-1993"), "2 Test Rd", "", "12345", "Testin", "TE",
						"USA", "test_hash");
			?>
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