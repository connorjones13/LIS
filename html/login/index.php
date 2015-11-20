<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\LoginController($pdo);
	$controller->checkCredentials($_POST["username"], $_POST["password"]);

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
					<?php if ($controller->hasError()) { ?>
						<p class="alert bg-danger">
							<?= $controller->getErrorMessage(); ?>
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
					<span class="pull-right">Click <a href="/account/create">here</a> to create an account.</span>
				</form>
			</div>
		</div>
		<footer class="footer">
			<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>