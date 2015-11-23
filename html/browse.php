<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 11:29 AM
 */

	require_once(__DIR__ . "/../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\RentalItemController($pdo);

	$page_title = "Browse Rental Items";

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
				<?php
					$book = new \LIS\RentalItem\Book($pdo);

					$books = $book->getAllByStatus($pdo, 0);

					foreach ($books as $bookarr) {
						var_dump($bookarr);
						echo gettype($bookarr);
						$book = $bookarr;
						echo gettype($bookarr);
					?>

					<?php } ?>

			</div>
			<footer class="footer">
				<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
			</footer>
		</body>
</html>
