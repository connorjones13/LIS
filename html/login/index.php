<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\LoginController($pdo);
	$controller->checkCredentials($_POST["username"], $_POST["password"]);

	if ($controller->hasError()) {
		switch ($controller->getError()) {
			case \LIS\Controllers\LoginController::$ERROR_USERNAME_NOT_FOUND:
				$error = "This username does not exist. Please create an account.";
				break;
			case \LIS\Controllers\LoginController::$ERROR_CREDENTIALS_INVALID:
				$error = "Credentials invalid. Please try again.";
				break;
			case \LIS\Controllers\LoginController::$ERROR_ACCOUNT_INACTIVE:
				$error = "This account has been made inactive. Please contact support.";
				break;
			case \LIS\Controllers\LoginController::$ERROR_SESSION_TIMED_OUT:
				$error = "You were logged in for too long without action and have been logged out.";
				break;
		}
	}

	$page_title = "Login";
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
			<div class="center col-lg-6 col-md-8 col-sm-10 col-lg-offset-3 col-md-offset-2 col-sm-offset-1">
				<h2>Login</h2>
				<form action method="post">
					<?php if (isset($error)) { ?>
						<p class="alert bg-danger">
							<?= $error; ?>
						</p>
					<?php } ?>
					<div class="form-group">
						<label for="username">Email address</label>
						<input type="email" class="form-control" id="username" name="username" placeholder="Username"
						       value="<?= $_POST["username"] ?>">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password"
						       placeholder="Password">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
		<footer class="footer">
			<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>