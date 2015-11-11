<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/11/15
	 * Time: 12:23 AM
	 */

	namespace LIS\Controllers;


	use LIS\Database\PDO_MySQL;

	class BaseController {

		/* @var PDO_MySQL $_pdo */
		protected $_pdo;

		/**
		 * Session constructor. Basically the Login controller
		 * This class is going to be much larger
		 * @param PDO_MySQL $_pdo
		 */
		public function __construct(PDO_MySQL $_pdo) {
			$this->_pdo = $_pdo;

			error_reporting(E_ALL);
			ini_set("display_errors", "1");
		}
	}