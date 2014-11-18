<?php

/**
 * File GroupController.php
 *
 * PHP version 5.3+
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 Dzung Nguyen
 * @license   http://evolpas.com
 * @version   XXX
 * @link      http://evolpas.com
 * @category  controllers
 * @package   controllers
 */

namespace Controllers;

use Models\Group;

/**
 * Class GroupController
 *
 * Controller of Group: Handle request actions & resopnse data to view
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 Dzung Nguyen
 * @license   http://evolpas.com license
 * @version   XXX
 * @link      http://evolpas.com
 * @category  controllers
 * @package   controllers
 * @since     XXX
 */
class GroupController {

	/**
	 * @var object instance of group model
	 */
	public $group;

	/**
	 * @var array containt data of group model
	 */
	public $data = array();

	/**
	 * Group controller construction method
	 * 
	 */
	public function __construct() {
		$this->group = new Group();
	}

	/**
	 * Handle data to create new group
	 * 
	 * @return mixed is value of latest id inserted or false if insert error
	 */
	public function createGroupAction() {
		$this->group->id_parent = isset($this->data['id_parent']) ? $this->data['id_parent'] : null;
		$this->group->group_name = $this->data['group_name'];
		$this->group->description = $this->data['description'];
		return $this->group->save();
	}

	/**
	 * Handle data to create new group
	 * 
	 * @return mixed is value of latest id inserted or false if insert error
	 */
	public function editGroupAction($id) {
		$this->group->id = $id;
		$this->group->id_parent = $this->data['id_parent'];
		$this->group->group_name = $this->data['group_name'];
		$this->group->description = $this->data['description'];
		return $this->group->save();
	}

	/**
	 * Handle get list groups that inherit
	 * 
	 * @return array list groups
	 */
	public function getGroupInherits() {
		$groupParent = $this->group->getParentNode();
		return $groupParent;
	}

	/**
	 * Get all list contacts include inherited contacts
	 * 
	 * @param int $id_group is id of group that to list contacts
	 * @return array list contacts include inherited contacts
	 */
	public function getContactListAction($id_group) {
		return $this->group->getContactArrayOf($id_group);
	}

	/**
	 * Get all contacts of group (not include inherited contacts)
	 * 
	 * @param int $id_group is id of group that to list contacts
	 * @return array list contacts not include inherited contacts
	 */
	public function getPrivateContactsAction($id_group) {
		return $this->group->getPrivateContactArrayOf($id_group);
	}

	/**
	 * Get all groups of database
	 * 
	 * @param int $id_group is id of group that to list contacts
	 * @return array groups of database
	 */
	public function getAllGroups() {
		$groups = $this->group->getAll();
		return $groups;
	}

	/**
	 * Get groups inherited of a group
	 * 
	 * @param int $idGroup id of group that get groups inherit
	 * @return array groups inherited of a group
	 */
	public function getGroupInherited($idGroup) {
		$parentNode = $this->group->getParentNodeById($idGroup);
		return $parentNode;
	}

	/**
	 * Get group info by id
	 * 
	 * @param int $id is id of group
	 * @return array info of groups
	 */
	public function getGroupById($id) {
		$groups = $this->group->findById($id);
		return $groups;
	}

	/**
	 * Get list groups of a contact
	 * 
	 * @param int $id is id of group
	 * @return array groups that content contact also inherited group
	 */
	public function listGroupsOfContact($idContact) {
		return $this->group->getGroupsOfContact($idContact);
	}

	/**
	 * Handle remove contact from group
	 * 
	 * @param int $id_contact is id of contact will be removed
	 * @param int $id_group is id of group will remove contact
	 * @return boolean false if can not remove, true if removed
	 */
	public function removeContactGroupAction($id_contact, $id_group) {
		$this->group->id = $id_group;
		$this->group->id_contact = $id_contact;
		return $this->group->removeContactGroup();
	}

	/**
	 * Handle delete group info: remove all connections and contacts of it
	 * 
	 * @param int $id_group is id of group will be deleted
	 * @return boolean true if groups deleted or false if not
	 */
	public function deleteGroupAction($idGroup) {
		return $this->group->deleteRow($idGroup);
	}

}

?>