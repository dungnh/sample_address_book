<?php

/**
 * File Contact.php
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
 * Class Contact
 *
 * Model of Contact
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
class Contact extends DbModel implements ModelInterface {

	/**
	 * @var object connect to database: PDO
	 */
	public $conn;

	/**
	 * @var string is name of contacts table
	 */
	private $_name = 'contacts';

	/**
	 * @var string is name of cities table
	 */
	private $_nameCity = 'cities';

	/**
	 * @var string is name of contact_groups table
	 */
	private $_nameContactGroup = 'contacts_groups';

	/**
	 * @var int is id property of contact
	 */
	public $id;

	/**
	 * @var int is id_group property of contact_groups
	 */
	public $id_group;

	/**
	 * @var string is name property of contact
	 */
	public $name;

	/**
	 * @var string is first name property of contact	 
	 */
	public $first_name;

	/**
	 * @var int is city id property of contact
	 */
	public $id_city;

	/**
	 * @var string is street property of contact
	 */
	public $street;

	/**
	 * @var string is zip code property of contact
	 */
	public $zip_code;

	/**
	 * @var datetime created data of contact
	 */
	public $created;

	/**
	 * @var datetime modified data of contact
	 */
	public $modified;

	/**
	 * Contact model construction method. Get connection from parent class
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
														id_city, 
														name, 
														first_name,
														street,
														zip_code,
														created,
														modified
													) VALUES(
														:id_city, 
														:name, 
														:first_name,
														:street,
														:zip_code,
														:created,
														:modified
													)';

			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id_city', $this->id_city);
			$smtp->bindValue(':name', $this->name);
			$smtp->bindValue(':first_name', $this->first_name);
			$smtp->bindValue(':street', $this->street);
			$smtp->bindValue(':zip_code', $this->zip_code);
			$smtp->bindValue(':created', date('Y-m-d H:i:s'));
			$smtp->bindValue(':modified', date('Y-m-d H:i:s'));
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
			$sql = 'UPDATE ' . $this->_name . ' SET id_city = :id_city, 
													name = :name, 
													first_name = :first_name,
													street = :street,
													zip_code = :zip_code,
													modified = :modified
													';
			$sql .=' WHERE id=:id';

			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id_city', $this->id_city);
			$smtp->bindValue(':name', $this->name);
			$smtp->bindValue(':first_name', $this->first_name);
			$smtp->bindValue(':street', $this->street);
			$smtp->bindValue(':zip_code', $this->zip_code);
			$smtp->bindValue(':modified', date('Y-m-d H:i:s'));

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
	 * Update groups of contact by delete old groups of contact and insert new groups.
	 * 
	 * @return mixed int is last id if insert (false if insert error), true or false if update
	 */
	public function updateGroupOfContact() {
		$result = false;
		if (!empty($this->id_group)) {
			$idgs = $this->id_group;
			if (!empty($this->id)) { // if id is not empty, delete all connections with groups and add with new groups.
				$sqlDel = 'DELETE FROM ' . $this->_nameContactGroup . ' WHERE id_contact=' . $this->id;
				$smtp = $this->conn->prepare($sqlDel);
				$deleted = $smtp->execute();
				$smtp->closeCursor();
				if ($deleted) {
					$sql = 'INSERT INTO ' . $this->_nameContactGroup . ' (id_contact, id_group) VALUES ';
					for ($i = 0; $i < count($idgs); $i++) {
						$sql .="($this->id, $idgs[$i])";
						if ($i != count($idgs) - 1) {
							$sql .=',';
						}
					}
					$smtp = $this->conn->prepare($sql);
					$result = $smtp->execute();
					$smtp->closeCursor();
				}
			}
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
		$contact = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT
						a.id,
						a.id_city,
						a.name,
						a.first_name,
						a.street,
						a.zip_code,
						a.created,
						a.modified,
						c.city_name,
						c.description
				FROM ' . $this->_name . ' AS a
				INNER JOIN ' . $this->_nameCity . ' AS c ON a.id_city = c.id 
				WHERE a.id=:id';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$smtp->execute();
			$contact = $smtp->fetch();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $contact;
	}

	/**
	 * Override method from interface. Get all rows in database
	 * 
	 * @return mixed is an array all of rows in database or false if select fail
	 */
	public function getAll($order_by = 'name', $sort = 'ASC') {
		$contacts = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT
						a.id,
						a.id_city,
						a.name,
						a.first_name,
						a.street,
						a.zip_code,
						a.created,
						a.modified,
						c.city_name,
						c.description
				FROM ' . $this->_name . ' AS a
				INNER JOIN ' . $this->_nameCity . ' AS c ON a.id_city = c.id  
				ORDER BY ' . $order_by . ' ' . $sort . ' ';
			$smtp = $this->conn->prepare($sql);
			$smtp->execute();
			$contacts = $smtp->fetchAll();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $contacts;
	}

	/**
	 * Override method from interface. Delete a single row by id.
	 * - remove contact from contacts_groups if it is avaiable there
	 * @param boolean true if deleted false if cannot
	 */
	public function deleteRow($id) {
		$result = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			// Frist: remove this contact from contacts_groups if it is available there
			$sql = "DELETE FROM " . $this->_nameContactGroup . " WHERE id_contact=:id";
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$smtp->execute();
			$smtp->closeCursor();
			
			$sql = "DELETE FROM " . $this->_name . " WHERE id=:id";
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$result = $smtp->execute();
			$smtp->closeCursor();
			
		} catch (\PDOException $ex) {
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