<?php
require_once(__DIR__ . "/../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\BaseController($pdo);

$page_title = "Contact Us";
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
	<h1>Contact Us</h1>
	<p>Have questions? Drop us an <a href="mailto:ski0005@auburn.edu">email</a> and we'll get back to you as soon as possible!</p>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/footer.php"); ?>
</footer>
</body>
</html>