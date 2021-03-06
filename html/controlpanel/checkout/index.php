<?php
	require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\CheckoutController($pdo);

	if (is_null($controller->getSessionUser())
		|| $controller->getSessionUser()->getPrivilegeLevel() < \LIS\User\User::PRIVILEGE_EMPLOYEE) {
		header("Location: /");
		exit();
	}

	$rental_item = \LIS\RentalItem\RentalItem::find($pdo, $_GET['id']);

	if (\LIS\Utility::requestHasPost()) {
		$library_card = $_POST["library_card"];
		$ids = $_POST;
		$controller->checkoutRentalItem($_POST['id'], $_POST['library_card']);
	}

	$page_title = "Checkout";
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
					<h1 class="page-header">Checkout</h1>
					<form action method="post">
						<?php if ($controller->hasError()) { ?>
							<p class="alert bg-danger">
								<?= $controller->getErrorMessage(); ?>
							</p>
						<?php } ?>
						<?php if ($_SESSION["checkout_alert"]) { ?>
							<p class="alert alert-success"><?php echo $_SESSION["checkout_alert"] ?></p>
							<?php unset($_SESSION["checkout_alert"]) ?>
						<?php } ?>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">Library Card #</span>
							<input type="text" class="form-control" name="library_card" placeholder="GPQ5X5T32I1NHN7A"
							       value="<?= $_POST["library_card"] ?>">
						</div>
						<br/>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 01</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][0] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][0] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 02</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][1] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][1] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 03</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][2] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][2] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 04</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][3] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][3] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 05</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][4] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][4] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 06</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][5] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][5] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 07</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][6] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][6] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 08</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][7] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][7] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 09</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][8] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][8] ?>">
						</div>
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon2">ID 10</span>
							<input class="form-control <?= $controller->getErrorInId() && $_POST["id"][9] == $controller->getErrorInId() ? "alert-danger" : "" ?>"
							       type="text" name="id[]" placeholder="1234" value="<?= $_POST["id"][9] ?>">
						</div>
						<br/>
						<button type="submit" class="btn btn-default">Checkout</button>
					</form>
				</div>
			</div>
		<?php }
		else { ?>
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