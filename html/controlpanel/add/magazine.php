<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/23/15
 * Time: 8:39 AM
 */

require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\AddMagazineController($pdo);
if (\LIS\Utility::requestHasPost())

	$controller->createNewMagazine($_POST["summary"], $_POST["title"], $_POST["category"], $_POST["date_published"],
		$_POST["status"], $_POST["publication"], $_POST["issue_number"]);

$page_title = "Add Magazine";
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
		<div class="row">
			<?php require_once(__DIR__ . "/../../../includes/html_templates/control_panel_nav.php"); ?>
			<div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
				<h1 class="page-header">Add Magazine</h1>
				<form action method="post">
					<?php if ($controller->hasError()) { ?>
						<p class="alert bg-danger">
							<?= $controller->getErrorMessage(); ?>
						</p>
					<?php } ?>
					<div class="form-group">
						<label for="title">Title</label>
						<input type="text" class="form-control" id="title" name="title"
						       placeholder="Sports Illustrated Summer Edition" value="<?= $_POST["title"] ?>">
					</div>
					<div class="form-group">
						<label for="summary">Summary</label>
						<input type="text" class="form-control" id="summary" name="summary"
						       placeholder="Add a summary for the magazine" value="<?= $_POST["summary"] ?>">
					</div>
					<div class="form-group">
						<label for="category">Category</label>
						<input type="text" class="form-control" id="category" name="category"
						       placeholder="Sports" value="<?= $_POST["category"] ?>">
					</div>
					<div class="form-group">
						<label for="publication">Publication</label>
						<input type="text" class="form-control" id="publication" name="publication"
						       placeholder="Sports Illustrated" value="<?= $_POST["publication"] ?>">
					</div>

					<div class="form-group">
						<label for="issue_number">Issue Number</label>
						<input type="number" class="form-control" id="issue_number" name="issue_number"
						       placeholder="11101324132" value="<?= $_POST["issue_number"] ?>">
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
					<button type="submit" class="btn btn-default">Create</button>
				</form>
			</div>
		</div>
	</div>

</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>