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

$book = \LIS\RentalItem\Book::find($pdo, $_GET['id']);


if (\LIS\Utility::requestHasPost()) {
	$controller->updateBookInfo($book, $_POST["summary"], $_POST["title"], $_POST["category"],
		$_POST["date_published"],
		$_POST["isbn10"], $_POST["isbn13"], $_POST["authors"]);
}
$_POST["summary"] = $book->getSummary();
$_POST["title"] = $book->getTitle();
$_POST["category"] = $book->getCategory();
$_POST["date_published"] = $book->getDatePublished();
$_POST["isbn10"] = $book->getISBN10();
$_POST["isbn13"] = $book->getISBN13();
$_POST["authors"] = implode(',', \LIS\RentalItem\Author::findAllForBook($pdo, $book));

$page_title = "Update Book";
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
					<h1 class="page-header">Update Book</h1>
					<form action method="post">
						<?php if ($controller->hasError()) { ?>
							<p class="alert bg-danger">
								<?= $controller->getErrorMessage(); ?>
							</p>
						<?php } ?>
						<div class="form-group">
							<label for="title">Title</label>
							<input type="text" class="form-control" id="title" name="title"
							        value="<?= $_POST["title"] ?>">
						</div>
						<div class="form-group">
							<label for="summary">Summary</label>
							<input type="text" class="form-control" id="summary" name="summary"
							       value="<?= $_POST["summary"] ?>">
						</div>
						<div class="form-group">
							<label for="category">Category</label>
							<input type="text" class="form-control" id="category" name="category"
							       value="<?= $_POST["category"] ?>">
						</div>
						<div class="form-group">
							<label for="isbn10">ISBN10</label>
							<input type="text" class="form-control" id="isbn10" name="isbn10"
							       value="<?= $_POST["isbn10"] ?>">
						</div>

						<div class="form-group">
							<label for="isbn13">ISBN13</label>
							<input type="text" class="form-control" id="isbn13" name="isbn13"
							       value="<?= $_POST["isbn13"] ?>">
						</div>

						<div class="form-group">
							<label for="authors">Authors (separate by comma WITHOUT spaces)</label>
							<input type="text" class="form-control" id="authors" name="authors"
							       value="<?= $_POST["authors"] ?>">
						</div>

						<div class="form-group">
							<label for="date_published">Date Published</label>
							<!-- Comes in as yyyy-mm-dd -->
							<input type="date" class="form-control" id="date_published" name="date_published"
							       value="<?= \LIS\Utility::getDateTimeForMySQLDate($_POST["date_published"]) ?>">
						</div>
						<button type="submit" onclick="history.go(-1)" class="btn btn-default">Update</button>
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