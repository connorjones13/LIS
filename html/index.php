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
		<header>
			<?php require_once(__DIR__ . "/../includes/html_templates/header.php"); ?>
		</header>
		<div class="content">
			<?php var_dump(\LIS\User\User::find($pdo, 1)); ?>
		</div>
		<footer>
			<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>