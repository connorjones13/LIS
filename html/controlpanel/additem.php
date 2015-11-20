<?php
require_once(__DIR__ . "/../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\CreateUserController($pdo);
if (\LIS\Utility::requestHasPost())
	$controller->createNewUser($_POST["name_first"], $_POST["name_last"], $_POST["username"], $_POST["phone"],
		$_POST["gender"], $_POST["dob"], $_POST["address_line_1"], $_POST["address_line_2"], $_POST["address_zip"],
		$_POST["address_city"], $_POST["address_state"], $_POST["password"], $_POST["password_confirm"]);

$page_title = "Add Rental Item";
?>
<!doctype html>
<html lang="en">
<head>
	<?php require_once(__DIR__ . "/../../includes/html_templates/head.php"); ?>
</head>
<body>
<header class="navbar navbar-fixed-top navbar-inverse">
	<?php require_once(__DIR__ . "/../../includes/html_templates/header.php"); ?>
</header>
<div class="container">
	<div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
		<h2>Add Rental Item</h2>
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
					<select class="form-control" id="address_state" name="address_state">
						<option value="AL" <?= $_POST["address_state"] == "AL" ? "selected" : ""; ?>>Alabama</option>
						<option value="AK" <?= $_POST["address_state"] == "AK" ? "selected" : ""; ?>>Alaska</option>
						<option value="AZ" <?= $_POST["address_state"] == "AZ" ? "selected" : ""; ?>>Arizona</option>
						<option value="AR" <?= $_POST["address_state"] == "AR" ? "selected" : ""; ?>>Arkansas</option>
						<option value="CA" <?= $_POST["address_state"] == "CA" ? "selected" : ""; ?>>California</option>
						<option value="CO" <?= $_POST["address_state"] == "CO" ? "selected" : ""; ?>>Colorado</option>
						<option value="CT" <?= $_POST["address_state"] == "CT" ? "selected" : ""; ?>>Connecticut</option>
						<option value="DE" <?= $_POST["address_state"] == "DE" ? "selected" : ""; ?>>Delaware</option>
						<option value="DC" <?= $_POST["address_state"] == "DC" ? "selected" : ""; ?>>District Of Columbia</option>
						<option value="FL" <?= $_POST["address_state"] == "FL" ? "selected" : ""; ?>>Florida</option>
						<option value="GA" <?= $_POST["address_state"] == "GA" ? "selected" : ""; ?>>Georgia</option>
						<option value="HI" <?= $_POST["address_state"] == "HI" ? "selected" : ""; ?>>Hawaii</option>
						<option value="ID" <?= $_POST["address_state"] == "ID" ? "selected" : ""; ?>>Idaho</option>
						<option value="IL" <?= $_POST["address_state"] == "IL" ? "selected" : ""; ?>>Illinois</option>
						<option value="IN" <?= $_POST["address_state"] == "IN" ? "selected" : ""; ?>>Indiana</option>
						<option value="IA" <?= $_POST["address_state"] == "IA" ? "selected" : ""; ?>>Iowa</option>
						<option value="KS" <?= $_POST["address_state"] == "KS" ? "selected" : ""; ?>>Kansas</option>
						<option value="KY" <?= $_POST["address_state"] == "KY" ? "selected" : ""; ?>>Kentucky</option>
						<option value="LA" <?= $_POST["address_state"] == "LA" ? "selected" : ""; ?>>Louisiana</option>
						<option value="ME" <?= $_POST["address_state"] == "ME" ? "selected" : ""; ?>>Maine</option>
						<option value="MD" <?= $_POST["address_state"] == "MD" ? "selected" : ""; ?>>Maryland</option>
						<option value="MA" <?= $_POST["address_state"] == "MA" ? "selected" : ""; ?>>Massachusetts</option>
						<option value="MI" <?= $_POST["address_state"] == "MI" ? "selected" : ""; ?>>Michigan</option>
						<option value="MN" <?= $_POST["address_state"] == "MN" ? "selected" : ""; ?>>Minnesota</option>
						<option value="MS" <?= $_POST["address_state"] == "MS" ? "selected" : ""; ?>>Mississippi</option>
						<option value="MO" <?= $_POST["address_state"] == "MO" ? "selected" : ""; ?>>Missouri</option>
						<option value="MT" <?= $_POST["address_state"] == "MT" ? "selected" : ""; ?>>Montana</option>
						<option value="NE" <?= $_POST["address_state"] == "NE" ? "selected" : ""; ?>>Nebraska</option>
						<option value="NV" <?= $_POST["address_state"] == "NV" ? "selected" : ""; ?>>Nevada</option>
						<option value="NH" <?= $_POST["address_state"] == "NH" ? "selected" : ""; ?>>New Hampshire</option>
						<option value="NJ" <?= $_POST["address_state"] == "NJ" ? "selected" : ""; ?>>New Jersey</option>
						<option value="NM" <?= $_POST["address_state"] == "NM" ? "selected" : ""; ?>>New Mexico</option>
						<option value="NY" <?= $_POST["address_state"] == "NY" ? "selected" : ""; ?>>New York</option>
						<option value="NC" <?= $_POST["address_state"] == "NC" ? "selected" : ""; ?>>North Carolina</option>
						<option value="ND" <?= $_POST["address_state"] == "ND" ? "selected" : ""; ?>>North Dakota</option>
						<option value="OH" <?= $_POST["address_state"] == "OH" ? "selected" : ""; ?>>Ohio</option>
						<option value="OK" <?= $_POST["address_state"] == "OK" ? "selected" : ""; ?>>Oklahoma</option>
						<option value="OR" <?= $_POST["address_state"] == "OR" ? "selected" : ""; ?>>Oregon</option>
						<option value="PA" <?= $_POST["address_state"] == "PA" ? "selected" : ""; ?>>Pennsylvania</option>
						<option value="RI" <?= $_POST["address_state"] == "RI" ? "selected" : ""; ?>>Rhode Island</option>
						<option value="SC" <?= $_POST["address_state"] == "SC" ? "selected" : ""; ?>>South Carolina</option>
						<option value="SD" <?= $_POST["address_state"] == "SD" ? "selected" : ""; ?>>South Dakota</option>
						<option value="TN" <?= $_POST["address_state"] == "TN" ? "selected" : ""; ?>>Tennessee</option>
						<option value="TX" <?= $_POST["address_state"] == "TX" ? "selected" : ""; ?>>Texas</option>
						<option value="UT" <?= $_POST["address_state"] == "UT" ? "selected" : ""; ?>>Utah</option>
						<option value="VT" <?= $_POST["address_state"] == "VT" ? "selected" : ""; ?>>Vermont</option>
						<option value="VA" <?= $_POST["address_state"] == "VA" ? "selected" : ""; ?>>Virginia</option>
						<option value="WA" <?= $_POST["address_state"] == "WA" ? "selected" : ""; ?>>Washington</option>
						<option value="WV" <?= $_POST["address_state"] == "WV" ? "selected" : ""; ?>>West Virginia</option>
						<option value="WI" <?= $_POST["address_state"] == "WI" ? "selected" : ""; ?>>Wisconsin</option>
						<option value="WY" <?= $_POST["address_state"] == "WY" ? "selected" : ""; ?>>Wyoming</option>
					</select>
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
<footer class="footer">
	<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>