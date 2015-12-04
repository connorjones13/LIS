<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 12/1/2015
 * Time: 10:05 AM
 */

require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\UserController($pdo);

if (is_null($controller->getSessionUser()) || $controller->getSessionUser()->getPrivilegeLevel() < \LIS\User\User::PRIVILEGE_EMPLOYEE) {
	header("Location: /");
	exit();
}


$page_title = "Manage Users";
?>
<!doctype html>
<html lang="en">
<head>
	<?php require_once(__DIR__ . "/../../../includes/html_templates/head.php"); ?>
</head>
<body>
<header class="navbar navbar-fixed-top navbar-inverse">
	<?php require_once(__DIR__ . "/../../../includes/html_templates/header.php"); ?>
</header>
<div class="container">
	<div class="container-fluid">
		<?php if ($controller->getSessionUser()->isEmployee() || $controller->getSessionUser()->isAdmin()) { ?>
			<div class="row">
				<?php require_once(__DIR__ . "/../../../includes/html_templates/control_panel_nav.php"); ?>
				<div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
					<h1 class="page-header">Manage Users</h1>

					<table class="table table-striped" id="view_table">
						<thead>
						<tr>
							<th>Name</th>
							<th>Number</th>
							<th>Email</th>
							<th>Library #</th>
							<th>Deactivate</th>
						</tr>
						</thead>
						<tbody>
						<?php $users = \LIS\User\User::getAllActive($pdo); ?>
						<?php foreach ($users as $user) { ?>
							<tr>
								<td><b><a href="/controlpanel/users/user/<?= $user->getId() ?>/"><?= $user->getNameFull(); ?></a></b></td>
								<td><?= $user->getPhoneFormatted(); ?></td>
								<td><?= $user->getEmail() ?></td>
								<td><?= $user->getLibraryCard()->getNumber() ?></td>
								<td><a href="#" class="btn btn-sm btn-danger">Deactivate</a></td>
							</tr>

						<?php } ?>
						</tbody>
					</table>

				</div>
			</div>
		<?php } else { ?>
			<h4 class="alert bg-warning">
				You do not have permission to view this page.
			</h4>
		<?php } ?>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>