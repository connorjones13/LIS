<?php
	require_once(__DIR__ . "/../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\BaseController($pdo);

	$page_title = "Welcome";
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
		<div class="container">
			<div class="center jumbotron">
				<h1>Welcome to the LIS</h1>
				<h2>View the test classes on the <a href="classes.php">Test Classes</a> page.</h2>
				<a href="#" class="btn btn-lg btn-primary">Create an Account</a>
			</div>
		</div>
		<footer class="footer">
			<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>