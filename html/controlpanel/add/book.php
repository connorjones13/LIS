<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 8:39 AM
 */

require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\RentalItemController($pdo);

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
				<form action method="post">
					<?php if ($controller->hasError()) { ?>
						<p class="alert bg-danger">
							<?= $controller->getErrorMessage(); ?>
						</p>
					<?php } ?>
					<div class="form-group">
						<label for="name_first">First Name</label>
						<input type="text" class="form-control" id="name_first" name="name_first"
						       placeholder="Johnny" value="<?= $_POST["name_first"] ?>">
					</div>
					<div class="form-group">
						<label for="name_last">Last Name</label>
						<input type="text" class="form-control" id="name_last" name="name_last"
						       placeholder="Appleseed" value="<?= $_POST["name_last"] ?>">
					</div>
					<div class="form-group">
						<label for="username">Username/Email</label>
						<input type="text" class="form-control" id="username" name="username"
						       placeholder="japple@example.com" value="<?= $_POST["username"] ?>">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password"
						       placeholder="password" value="<?= $_POST["password"] ?>">
					</div>
					<div class="form-group">
						<label for="password_confirm">Password</label>
						<input type="password" class="form-control" id="password_confirm" name="password_confirm"
						       placeholder="password" value="<?= $_POST["password_confirm"] ?>">
					</div>
					<div class="form-group">
						<label for="phone">Phone</label>
						<input type="tel" class="form-control" id="phone" name="phone"
						       placeholder="(251) 555-1234" value="<?= $_POST["phone"] ?>">
					</div>
					<div class="form-group">
						<label for="gender">Gender <span class="small text-info">(optional)</span></label>
						<select class="form-control" id="gender" name="gender">
							<option value="0" <?= $_POST["gender"] == "0" ? "selected" : ""; ?>>Unspecified</option>
							<option value="1" <?= $_POST["gender"] == "1" ? "selected" : ""; ?>>Male</option>
							<option value="2" <?= $_POST["gender"] == "2" ? "selected" : ""; ?>>Female</option>
							<option value="3" <?= $_POST["gender"] == "3" ? "selected" : ""; ?>>Other</option>
						</select>
					</div>
					<div class="form-group">
						<label for="dob">Date Of Birth</label>
						<!-- Comes in as yyyy-mm-dd -->
						<input type="date" class="form-control" id="dob" name="dob"
						       placeholder="(251) 555-1234" value="<?= $_POST["dob"] ?>">
					</div>
					<div class="row">
						<div class="form-group col-lg-9 col-md-8">
							<label for="address_line_1">Address Line 1</label>
							<input type="tel" class="form-control" id="address_line_1" name="address_line_1"
							       placeholder="123 W South Road" value="<?= $_POST["address_line_1"] ?>">
						</div>
						<div class="form-group col-lg-3 col-md-4">
							<label for="address_line_2">Address Line 2</label>
							<input type="tel" class="form-control" id="address_line_2" name="address_line_2"
							       placeholder="Suite 10" value="<?= $_POST["address_line_2"] ?>">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-6 col-md-6">
							<label for="address_city">City</label>
							<input type="tel" class="form-control" id="address_city" name="address_city"
							       placeholder="Auburn" value="<?= $_POST["address_city"] ?>">
						</div>
						<div class="form-group col-lg-3 col-md-3">
							<label for="address_state">State</label>
						</div>
						<div class="form-group col-lg-3 col-md-3">
							<label for="address_zip">Zip</label>
							<input type="tel" class="form-control" id="address_zip" name="address_zip"
							       placeholder="36830" value="<?= $_POST["address_zip"] ?>">
						</div>
					</div>
					<button type="submit" class="btn btn-default">Create</button>
				</form>
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