<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$item = new LIS\RentalItem\Book($this->_pdo);
	$controller = new \LIS\Controllers\CreateUserController($pdo);

	$page_title = "$item->getTitle()";

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
	<div class="pull-left">
		<img src="http://lorempixel.com/400/400/cats/">
	</div>
	<div class="pull-right">
		<h1><?= $item->getTitle() ?></h1>
		<h4><?= $item->getCategory() ?></h4>
		<p><?= $item->getSummary() ?></p>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/footer.php"); ?>
</footer>
</body>
</html>
