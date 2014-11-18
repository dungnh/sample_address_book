<?php

/**
 * File DbModel.php
 *
 * PHP version 5.3+
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 Dzung Nguyen
 * @license   http://evolpas.com
 * @version   XXX
 * @link      http://evolpas.com
 * @category  models
 * @package   models
 */

namespace Models;

use \PDO;

/**
 * Class DbModel
 *
 * Create an connection instance to database with configuration params at config.php
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 Dzung Nguyen
 * @license   http://evolpas.com license
 * @version   XXX
 * @link      http://evolpas.com
 * @category  models
 * @package   models
 * @since     XXX
 */
class DbModel {

	/**
	 * @var object connect to database: PDO
	 */
	public $conn = null;

	/**
	 * @var string dsn is string data source name
	 */
	private $dsn = null;

	/**
	 * @var string db_username is usename to access database
	 */
	private $db_username;

	/**
	 * @var string db_password is password to access database
	 */
	private $db_password;

	/**
	 * DbModel construction method: get database configuration parameters
	 *
	 * @param array $config defined configuration parameters of connection database
	 * @since  XXX
	 */
	public function __construct($config) {
		$this->dsn = $config['database']['dsn'];
		$this->db_username = $config['database']['db_user'];
		$this->db_password = $config['database']['db_pass'];
	}

	/**
	 * Create an connection instance to database
	 *
	 * @since  XXX
	 */
	public function connection() {
		try {
			return $this->conn = new \PDO(
					$this->dsn, $this->db_username, $this->db_password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
			);
		} catch (\PDOException $e) {
			echo $e->getMessage();
			exit();
		}
	}

}
