<!-- TEMPLATE -->
<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\BaseController($pdo);

	$page_title = "Template";
?>
<!doctype html>
<html lang="en">
	<head>
		<?php require_once(__DIR__ . "/head.php"); ?>
	</head>
	<body>
		<header class="navbar navbar-fixed-top navbar-inverse">
			<?php require_once(__DIR__ . "/header.php"); ?>
		</header>
		<div class="content">
			<!-- CONTENT -->
		</div>
		<footer>
			<?php require_once(__DIR__ . "/footer.php"); ?>
		</footer>
	</body>
</html>