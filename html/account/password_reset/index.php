<?php
/**
 * Created by PhpStorm.
 * User: tyler
 * Date: 12/1/15
 * Time: 5:56 PM
 */


	require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\PasswordResetController($pdo);


if (\LIS\Utility::requestHasPost()) {
    $controller->resetPassword($_POST["confirmEmail"], $_POST["email"]);

}

	$page_title = "Template";
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
<div class="content">
    <!-- CONTENT -->
</div>
<div class="container">
    <div class="center col-lg-6 col-md-8 col-sm-10 col-lg-offset-3 col-md-offset-2 col-sm-offset-1">
        <h2>Reset Password</h2>
        <form action method="post">
            <?php if ($controller->hasError()) { ?>
                <p class="alert bg-danger">
                    <?= $controller->getErrorMessage(); ?>
                </p>
            <?php } ?>
            <div class="form-group">
                <label for="username">Email address</label>
                <input type="email" class="form-control" name="email" placeholder="example@email.com"
                       value="<?= $_POST["email"] ?>">
            </div>
            <div class="form-group">
                <label for="username">Confirm Email address</label>
                <input type="email" class="form-control" name="confirmEmail"
                       placeholder="example@email.com">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</div>

<footer class="footer">
    <?php require_once(__DIR__ . "/../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>