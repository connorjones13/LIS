<?php
	require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\RentalItemController($pdo);

	if (is_null($controller->getSessionUser())
		|| $controller->getSessionUser()->getPrivilegeLevel() < \LIS\User\User::PRIVILEGE_EMPLOYEE) {
		header("Location: /");
		exit();
	}

	$page_title = "Add Rental Item";
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
				<h1 class="page-header">Add Rental Item</h1>
				<?php if ($_SESSION["show_created_alert"]) { ?>
					<p class="alert alert-success"><?php echo $_SESSION["show_created_alert"] ?></p>
					<?php unset($_SESSION["show_created_alert"]) ?>
				<?php } ?>
				<!-- todo: formatting is weird on tablet/mobile -->
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<a href="magazine.php"><img src="/assets/images/magazine_stack.jpg" alt="Add Magazine"></a>
							<div class="caption">
								<a href="magazine.php" class="btn btn-primary center-block" role="button">Add Magazine</a>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<a href="book.php"><img src="/assets/images/book_shelf.jpg" alt="Add Book"></a>
							<div class="caption">
								<a href="book.php" class="btn btn-primary center-block" role="button">Add Book</a>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="thumbnail">
							<a href="dvd.php"><img src="/assets/images/dvds.jpg" alt="Add DVD"></a>
							<div class="caption">
								<a href="dvd.php" class="btn btn-primary center-block" role="button">Add DVD</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php }
		else { ?>
			<h4 class="alert bg-warning">
				You do not have permission to view this page.
			</h4>
		<?php } ?>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>