<?php
	require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\LibraryCardController($pdo);

	$user = \LIS\User\User::find($pdo, $_GET['id']);

	$controller->addNewLibraryCard($user);
