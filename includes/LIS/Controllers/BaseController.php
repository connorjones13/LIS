<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/11/15
	 * Time: 12:23 AM
	 */

	namespace LIS\Controllers;


	class BaseController {

		/**
		 * Session constructor. Basically the Login controller
		 * This class is going to be much larger
		 */
		public function __construct() {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
	}