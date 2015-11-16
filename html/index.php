<?php
	require_once(__DIR__ . "/../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\BaseController($pdo);

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
			<?php
				$user = \LIS\User\User::find($pdo, 1);
				$user->updatePassword("password");

				//Example code to create a new Admin
				if (false) {
					$user = new \LIS\User\Admin($pdo);
					$user->create("Testy", "McTesterson", "test@example.com", "1235554321", 1,
							DateTime::createFromFormat("m-d-Y", "11-10-1993"), "2 Test Rd", "", "12345", "Testin",
							"TE", "USA", "test_hash");
				}
			?>
			ID:<br>
			<?= $user->getId(); ?><br><br>
			Full Name:<br>
			<?= $user->getNameFull(); ?><br><br>
			Email:<br>
			<?= $user->getEmail(); ?><br><br>
			Address:<br>
			<?= $user->getAddressFull(); ?><br><br>
			DOB:<br>
			<?= $user->getDateOfBirth()->format("m-d-Y"); ?><br><br>
			DSU:<br>
			<?= $user->getDateSignedUp()->format("m-d-Y"); ?><br><br>
			Library Card:<br>
			<?= $user->getLibraryCardNumber(); ?><br><br>
			Card Issue Date:<br>
			<?= $user->getLibraryCardDateIssued()->format("m-d-Y"); ?>
		</div>
		<footer>
			<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>