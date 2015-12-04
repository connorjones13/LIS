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

	$rental_item = \LIS\RentalItem\RentalItem::find($pdo, $_GET['id']);

	if (\LIS\Utility::requestHasPost()) {
		if ($rental_item->isBook()) {
			$controller->updateBookInfo($rental_item, $_POST["summary"], $_POST["title"], $_POST["category"],
				$_POST["date_published"], $_POST["isbn10"], $_POST["isbn13"], $_POST["authors"]);
		}
		elseif ($rental_item->isDVD()) {
			$controller->updateDvdInfo($rental_item, $_POST["summary"], $_POST["title"], $_POST["category"],
				$_POST["date_published"], $_POST["director"]);
		}
		else {
			$controller->updateMagazineInfo($rental_item, $_POST["summary"], $_POST["title"], $_POST["category"],
				$_POST["date_published"], $_POST["publication"], $_POST["issue_number"]);
		}
	}

	$_POST["summary"] = $rental_item->getSummary();
	$_POST["title"] = $rental_item->getTitle();
	$_POST["category"] = $rental_item->getCategory();
	$_POST["date_published"] = $rental_item->getDatePublished();

	if ($rental_item->isBook()) {
		$_POST["isbn10"] = $rental_item->getISBN10();
		$_POST["isbn13"] = $rental_item->getISBN13();
		$_POST["authors"] = implode(',', \LIS\RentalItem\Author::findAllForBook($pdo, $rental_item));
	}
	elseif ($rental_item->isDVD()) {
		$_POST["director"] = $rental_item->getDirector();
	}
	else {
		$_POST["publication"] = $rental_item->getPublication();
		$_POST["issue_number"] = $rental_item->getIssueNumber();
	}

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
					<h1 class="page-header">Update Item</h1>
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
						<?php if ($rental_item->isBook()) { ?>
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
						<?php } ?>
						<?php if ($rental_item->isDVD()) { ?>
							<div class="form-group">
								<label for="director">Director</label>
								<input type="text" class="form-control" id="director" name="director"
								       value="<?= $_POST["director"] ?>">
							</div>
						<?php } ?>
						<?php if ($rental_item->isMagazine()) { ?>
							<div class="form-group">
								<label for="publication">Publication</label>
								<input type="text" class="form-control" id="publication" name="publication"
								       value="<?= $_POST["publication"] ?>">
							</div>
							<div class="form-group">
								<label for="issue_number">Issue Number</label>
								<input type="number" class="form-control" id="issue_number" name="issue_number"
								       value="<?= $_POST["issue_number"] ?>">
							</div>
						<?php } ?>
						<div class="form-group">
							<label for="date_published">Date Published</label>
							<input type="date" class="form-control" id="date_published" name="date_published"
							       value="<?= \LIS\Utility::getDateTimeForMySQLDate($_POST["date_published"]) ?>">
						</div>
						<button type="submit" class="btn btn-default">Update</button>
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