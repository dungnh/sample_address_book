<?php

/**
 * File Group.php
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
 * Class Group
 *
 * Model of Group
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
class Group extends DbModel implements ModelInterface {

	/**
	 * @var object connect to database: PDO
	 */
	public $conn;

	/**
	 * @var string is name of groups table
	 */
	private $_name = 'groups';

	/**
	 * @var string is name of group_relations table
	 */
	private $_nameGroupRelation = 'group_relations';

	/**
	 * @var string is name of contacts_groups table
	 */
	private $_nameContactsGroups = 'contacts_groups';

	/**
	 * @var int is id property of group
	 */
	public $id;

	/**
	 * @var int is parent id property of group_relations
	 */
	public $id_parent;

	/**
	 * @var int is id contact property of group_contacts
	 */
	public $id_contact;

	/**
	 * @var string is group name property of group
	 */
	public $group_name;

	/**
	 * @var string is group description property of group	 
	 */
	public $description;

	/**
	 * Group model construction method. Get connection from parent class
	 * 
	 */
	public function __construct() {
		$config = include __DIR__ . DIRECTORY_SEPARATOR . '../config.php';
		parent::__construct($config);
		$this->conn = parent::connection();
	}

	/**
	 * Execute insert SQL to store database. 
	 * This is a tree table so we need handle insert a node to tree.
	 * 
	 * @return mixed is value of latest id inserted or false if insert error
	 */
	private function _insert() {
		$result = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'INSERT INTO ' . $this->_name . ' (
														group_name,
														description
													) VALUES(
														:group_name,
														:description
													)';

			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':group_name', $this->group_name);
			$smtp->bindValue(':description', $this->description);
			$smtp->execute();
			$smtp->closeCursor();
			$idGroup = $this->conn->lastInsertId();
			// check to create relation of contacts
			$idps = $this->id_parent;
			if (!empty($idps)) {
				$sql = 'INSERT INTO ' . $this->_nameGroupRelation . ' (id_group, id_parent) VALUES ';
				// Create values insert batch
				for ($i = 0; $i < count($idps); $i++) {
					$sql .="($idGroup, $idps[$i])";
					if ($i != count($idps) - 1) {
						$sql .=',';
					}
				}
				$smtp = $this->conn->prepare($sql);
				$smtp->execute();
				$smtp->closeCursor();
			}

			$result = $idGroup;
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
			// update info of group
			$sql = 'UPDATE ' . $this->_name . ' SET group_name = :group_name,
												description = :description ';
			$sql .=' WHERE id=:id';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':group_name', $this->group_name);
			$smtp->bindValue(':description', $this->description);
			$smtp->bindValue(':id', $this->id);
			$result = $smtp->execute();
			$smtp->closeCursor();
			if ($result) { // if update info group OK, will check and update the relations of group
				$idGroup = $this->id;
				// delete all relations of group.
				// If this group be set to become top parent, the update will don't anything.
				$sqlDel = 'DELETE FROM ' . $this->_nameGroupRelation . ' WHERE id_group=' . $this->id;
				$smtp = $this->conn->prepare($sqlDel);
				$deleted = $smtp->execute();
				$smtp->closeCursor();
				// update inherit groups of this by insert new groups to group_relations
				if (!empty($this->id_parent)) {
					if ($deleted) {
						$idps = $this->id_parent;
						$sql = 'INSERT INTO ' . $this->_nameGroupRelation . ' (id_group, id_parent) VALUES ';
						// Create values insert batch
						for ($i = 0; $i < count($idps); $i++) {
							$sql .="($idGroup, $idps[$i])";
							if ($i != count($idps) - 1) {
								$sql .=',';
							}
						}
						$smtp = $this->conn->prepare($sql);
						$smtp->execute();
						$smtp->closeCursor();
					}
				}
				$result = $idGroup;
			}
			// update group relations
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
			$sql = 'SELECT * FROM ' . $this->_name . ' AS g	WHERE g.id=:id';
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
			$sql = 'SELECT * FROM ' . $this->_name . ' AS g	ORDER BY g.group_name';
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
	 * Get all groups are parents (not inherit from any)
	 * 
	 * @return mixed is an array all of rows in database or false if select fail
	 */
	public function getParentNode() {
		$parentList = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT g1.id, g1.group_name, g2.id as id_parent, g2.group_name as parent_name 
					FROM ' . $this->_name . ' as g1, ' . $this->_name . ' as g2, ' . $this->_nameGroupRelation . ' as r
					WHERE r.id_group = g1.id and r.id_parent=g2.id';

			$smtp = $this->conn->prepare($sql);
			$smtp->execute();
			$parentList = $smtp->fetchAll();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $parentList;
	}

	/**
	 * Get all parents of id group
	 * 
	 * @return mixed is an array all of rows in database or false if select fail
	 */
	public function getParentNodeById($id) {
		$parentList = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT g1.id, g1.group_name, g2.id as id_parent, g2.group_name as parent_name 
					FROM ' . $this->_name . ' as g1, ' . $this->_name . ' as g2, ' . $this->_nameGroupRelation . ' as r
					WHERE r.id_group = g1.id and r.id_parent=g2.id AND r.id_group=:id';

			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$smtp->execute();
			$parentList = $smtp->fetchAll();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $parentList;
	}

	/**
	 * Override method from interface. Delete a row by id
	 * When a group is deleted, it will check:
	 * - If deleted group is inherited from other groups, these connections will be removed
	 * - If deleted group is content contacts, these contacts will be removed in contacts_groups table
	 * - If deleted group is parent of some groups, remove it's connection
	 * 
	 * @param boolean true if deleted false if cannot
	 */
	public function deleteRow($id) {
		$result = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			// First: delete all connections of group
			$sql = 'DELETE FROM ' . $this->_nameGroupRelation . '
				WHERE id_group=:id OR id_parent=:id';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$smtp->execute();
			$smtp->closeCursor();
			// Second: remove all contacts that is content in this group in contacts_groups table
			$sql = 'DELETE FROM ' . $this->_nameContactsGroups . ' WHERE id_group=:id ';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id', $id);
			$smtp->execute();
			$smtp->closeCursor();
			// Latest: Delete group info from groups table
			$sql = 'DELETE FROM ' . $this->_name . ' WHERE id=:id ';
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
	 * Remove contact from group
	 * 
	 * @return boolean false if can not remove, true if removed
	 */
	public function removeContactGroup() {
		$idContact = $this->id_contact;
		$idGroup = $this->id;
		$result = false;
		if (!empty($idContact) && !empty($idGroup)) {
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			try {
				$sql = 'DELETE FROM ' . $this->_nameContactsGroups .
						' WHERE id_contact=' . $idContact .
						' AND id_group=' . $idGroup;
				$smtp = $this->conn->prepare($sql);
				$result = $smtp->execute();
				$smtp->closeCursor();
			} catch (\PDOException $ex) {
				error_log($ex->getMessage());
			}
		}
		return $result;
	}

	/**
	 * Get parent groups that group inherit contacts
	 * 
	 * @param int is id group needs get parents
	 * @return array parents group
	 */
	public function getParentArray($idGroup) {
		$result = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT g2.id, g2.group_name 
				FROM ' . $this->_name . ' as g1,  ' . $this->_name . ' as g2, ' . $this->_nameGroupRelation . ' as r 
				WHERE g1.id = r.id_parent 
				AND g2.id=r.id_parent 
				AND r.id_group=:id_group';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id_group', $idGroup);
			$smtp->execute();
			$result = $smtp->fetchAll();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $result;
	}

	/**
	 * Get contacts by id group, not include inherited contacts
	 * 
	 * @param int is id group
	 * @return array contacts by id group, not include inherited contact
	 */
	public function getPrivateContactArrayOf($idGroup) {
		$contacts = array();
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT c.*, ct.city_name 
				FROM contacts as c, cities as ct ,' . $this->_nameContactsGroups . ' as cg
				WHERE c.id = cg.id_contact 
				AND ct.id=c.id_city 
				AND cg.id_group =:id_group';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id_group', $idGroup);
			$smtp->execute();
			$contacts = $smtp->fetchAll();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $contacts;
	}

	/**
	 * Get contacts by id group, include inherited contact
	 * 
	 * @param int is id group
	 * @return array contacts by id group, include inherited contact
	 */
	public function getContactArrayOf($idGroup) {
		$contactList = array();
		$groupList = array($idGroup);
		$this->getGroupIdList($groupList, $idGroup);
		// browser all groups that inherit
		foreach ($groupList as $idg) {
			// get contacts of group
			$tmps = $this->getPrivateContactArrayOf($idg);
			foreach ($tmps as $tmp) {
				if (!in_array($tmp, $contactList)) {
					$contactList[] = $tmp;
				}
			}
		}
		return $contactList;
	}

	/**
	 * This method recursive to get group ids list that groups inherit contacts
	 * 
	 * @param array is a reference of array groups
	 * @param int is id group of inherit contacts
	 */
	public function getGroupIdList(&$groupList, $idGroup) {
		$arrParents = $this->getParentArray($idGroup);
		foreach ($arrParents as $parent) {
			if (!in_array($parent['id'], $groupList)) {
				$groupList[] = $parent['id'];
				$this->getGroupIdList($groupList, $parent['id']);
			}
		}
	}

	/**
	 * Get groups of contact.
	 * 
	 * @param int $idContact is id of contact that get groups
	 * @return array groups of contact
	 */
	public function getGroupsOfContact($idContact) {
		$groups = false;
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		try {
			$sql = 'SELECT g.id, g.group_name, cg.id_contact 
					FROM ' . $this->_name . ' as g, contacts as c, ' . $this->_nameContactsGroups . ' as cg
					WHERE cg.id_group = g.id 
						AND cg.id_contact = c.id 
						AND cg.id_contact =:id_contact';
			$smtp = $this->conn->prepare($sql);
			$smtp->bindValue(':id_contact', $idContact);
			$smtp->execute();
			$groups = $smtp->fetchAll();
			$smtp->closeCursor();
		} catch (\PDOException $ex) {
			error_log($ex->getMessage());
		}
		return $groups;
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
