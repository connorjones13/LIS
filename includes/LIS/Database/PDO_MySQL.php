<?php
	/**
	 * Created by PhpStorm.
	 * User: ski
	 * Date: 11/11/15
	 * Time: 12:02 AM
	 */

	namespace LIS\Database;

	use Aura\Sql\ExtendedPdo;
	use PDOException;

	class PDO_MySQL extends ExtendedPdo {
		const DUPLICATE_KEY_ERROR = 1062;

		protected static $HOSTNAME = "127.0.0.1";
		protected static $DATABASE = "COMP3700_ECC";
		protected static $USERNAME = "comp3700_ecc";
		protected static $PASSWORD = "needmorecoffee";

		public function __construct(array $options = array(), array $attributes = array()) {
			$dsn = "mysql:host=" . self::$HOSTNAME . ";dbname=" . self::$DATABASE;
			parent::__construct($dsn, self::$USERNAME, self::$PASSWORD, $options, $attributes);
		}

		public static function isDuplicateKeyError(PDOException $er) {
			return $er->errorInfo[1] === self::DUPLICATE_KEY_ERROR;
		}
	}