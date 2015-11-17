<?php

	namespace LIS\RentalItem;

	use LIS\Database\PDO_MySQL;

	class Magazine extends RentalItem {

		protected $publication, $issue_number;

		public function create($summary, $title, $category, $date_published, $status, $publication, $issue_number) {

			//todo: createNew()
		}


	}