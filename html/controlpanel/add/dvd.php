<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 8:39 AM
 */

require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\AddDvdController($pdo);
if (\LIS\Utility::requestHasPost())

	$controller->createNewDvd($_POST["summary"], $_POST["title"], $_POST["category"], $_POST["date_published"],
			$_POST["status"], $_POST["director"]);

$page_title = "Add DVD";
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
				<h1 class="page-header">Add DVD</h1>
				<form action method="post">
					<?php if ($controller->hasError()) { ?>
						<p class="alert bg-danger">
							<?= $controller->getErrorMessage(); ?>
						</p>
					<?php } ?>
					<div class="form-group">
						<label for="title">Title</label>
						<input type="text" class="form-control" id="title" name="title"
						       placeholder="Frozen" value="<?= $_POST["title"] ?>">
					</div>
					<div class="form-group">
						<label for="summary">Summary</label>
						<input type="text" class="form-control" id="summary" name="summary"
						       placeholder="Add a summary for the dvd" value="<?= $_POST["summary"] ?>">
					</div>
					<div class="form-group">
						<label for="category">Category</label>
						<input type="text" class="form-control" id="category" name="category"
						       placeholder="Disney" value="<?= $_POST["category"] ?>">
					</div>
					<div class="form-group">
						<label for="director">Director</label>
						<input type="text" class="form-control" id="director" name="director"
						       placeholder="John Lee" value="<?= $_POST["director"] ?>">
					</div>
					<div class="form-group">
						<label for="date_published">Date Published</label>
						<!-- Comes in as yyyy-mm-dd -->
						<input type="date" class="form-control" id="date_published" name="date_published"
						       placeholder="11/19/1990" value="<?= $_POST["date_published"] ?>">
					</div>
					<div class="form-group">
						<label for="status"></label>
						<input type="hidden" class="form-control" id="status" name="status"
						       placeholder="0" value="<?= $_POST["status"] == 0; ?>"
					</div>
					<!-- todo: clear form after submit, also refreshing the page creates an object in the databse -->
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
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>