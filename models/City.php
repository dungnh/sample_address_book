<?php

/**
 * File City.php
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

use Models\DbModel;
use Models\ModelInterface;
use \PDO;

/**
 * Class City
 *
 * Model of City
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
class City extends DbModel implements ModelInterface {

	/**
	 * @var object connect to database: PDO
	 */
	public $conn;

	/**
	 * @var string is name of cities table
	 */
	private $_name = 'cities';

	/**
	 * @var int is id property of cities
	 */
	public $id;

	/**
	 * @var string is city name property of cities
	 */
	public $city_name;

	/**
	 * @var string is cities description property of cities	 
	 */
	public $description;
	/**
	 * Cities model construction method. Get connection from parent class
	 * 
	 */
	public function __construct() {
		$config = include __DIR__ . DIRECTORY_SEPARATOR . '../config.php';	
		parent::__construct($config);
		$this->conn = parent::connection();
	}

	/**
	 * Execute insert SQL to store database
	 * 
	 * @return mixed is value of latest id inserted or false if insert error
	 */
	private function _insert() {
		$result = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'INSERT INTO ' . $this->_name . ' (
														city_name, 
														description														
													) VALUES(
														:city_name, 
														:description														
													)';

			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':city_name', $this->city_name);
			$smtp->bindValue(':description', $this->description);
			$smtp->execute();
			$smtp->closeCursor();
			$result = $this->conn->lastInsertId();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $result;
	}

	/**
	 * Execute update SQL to modify database
	 * 
	 * @return boolean is true if updated or false is not
	 */
	private function _update() {
		$result = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'UPDATE ' . $this->_name . ' SET city_name = :city_name, 
													description = :description											
													';
			$sql .=' WHERE id=:id';

			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':city_name', $this->city_name);
			$smtp->bindValue(':description', $this->description);
			$smtp->bindValue(':id', $this->id);

			$result = $smtp->execute();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $result;
	}

	/**
	 * Override method from interface. Save data to database. If primary key is null it means insert else it means update
	 * 
	 * @return mixed int is last id if insert (false if insert error), true or false if update
	 */
	public function save() {
		$result = false;
		if (empty($this->id)) { // insert data
			$result = $this->_insert();
		} else {  // update data
			$result = $this->_update();
		}
		return $result;
	}

	/**
	 * Override method from interface. Get a single row of table in database
	 * 
	 * @param int $id primary key of row
	 * @return mixed is an array the data of row in database or false if select fail
	 */
	public function findById($id) {
		$group = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT * FROM ' . $this->_name . ' AS c	WHERE c.id=:id';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$smtp->execute();
			$group = $smtp->fetch();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $group;
	}

	/**
	 * Override method from interface. Get all rows in database
	 * 
	 * @return mixed is an array all of rows in database or false if select fail
	 */
	public function getAll() {
		$groups = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT * FROM ' . $this->_name . ' AS c	ORDER BY c.city_name';
			$smtp = $this->conn->prepare($sql);
			$smtp->execute();
			$groups = $smtp->fetchAll();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $groups;
	}

	/**
	 * Override method from interface. Delete a single row by id
	 * @param boolean true if deleted false if cannot
	 */
	public function deleteRow($id) {
		$result = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = "DELETE FROM ".$this->_name." WHERE id=:id";
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$result = $smtp->execute();
			$smtp->closeCursor();
		}catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $result;
	}

	/**
	 * Override method from interface. Save a field of a row that fetched from database
	 * 
	 * @param string $fieldname name of field that need to update
	 * @param string $value value to update
	 * @param int $id id of row 
	 */
	public function updateField($fieldName, $value, $id) {
		
	}

}

?>