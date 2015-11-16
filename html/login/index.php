<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\BaseController($pdo);
	$controller->checkCredentials("ski0005@auburn.edu", "password");
	echo "" . $controller->getError() . "";