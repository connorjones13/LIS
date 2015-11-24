<?php
/**
 * Created by PhpStorm.
 * User: connorjones
 * Date: 11/24/15
 * Time: 4:50 PM
 */

require_once(__DIR__ . "/../../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\RentalItemController($pdo);

$rental_item = \LIS\RentalItem\RentalItem::find($pdo, $_GET['id']);

if (\LIS\Utility::requestHasPost()) {
	if($rental_item->isBook()) {
		$controller->updateBookInfo($rental_item, $_POST["summary"], $_POST["title"], $_POST["category"],
			$_POST["date_published"],
			$_POST["isbn10"], $_POST["isbn13"], $_POST["authors"]);
	} elseif($rental_item->isDVD()) {
		$controller->updateDvdInfo($rental_item, $_POST["summary"], $_POST["title"], $_POST["category"],
			$_POST["date_published"], $_POST["director"]);
	} else {
		$controller->updateMagazineInfo($rental_item, $_POST["summary"], $_POST["title"], $_POST["category"],
			$_POST["date_published"], $_POST["publication"], $_POST["issue_number"]);
	}
}


$page_title = "Checkout Item";
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
					<h1 class="page-header">Add Book</h1>
					<form action method="post">
						<?php if ($controller->hasError()) { ?>
							<p class="alert bg-danger">
								<?= $controller->getErrorMessage(); ?>
							</p>
						<?php } ?>
						<div class="form-group">
							<label for="title">Enter ID:</label>
							<input type="text" class="form-control" id="title" name="title"
							       placeholder="To Kill a Mockingbird" value="<?= $_POST["title"] ?>">
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