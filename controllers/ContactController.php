<?php

/**
 * File ContactController.php
 *
 * PHP version 5.3+ * 
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

use Models\Contact;
use Models\City;

/**
 * Class ContactController
 *
 * Controller of Contact: Handle request actions & resopnse data to view
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
class ContactController {

	/**
	 * @var object instance of Contact model
	 */
	public $contact;

	/**
	 * @var array content data of contact model
	 */
	public $data = array();

	/**
	 * Contact controller construction method. Init an contact model
	 * 
	 */
	public function __construct() {
		$this->contact = new Contact();
	}

	/**
	 * Handle data to create new contact
	 * 
	 * @return mixed is value of latest id inserted or false if insert error
	 */
	public function createContactAction() {

		$this->contact->name = $this->data['name'];
		$this->contact->first_name = $this->data['first_name'];
		$this->contact->street = $this->data['street'];
		$this->contact->zip_code = $this->data['zip_code'];
		$this->contact->id_city = $this->data['id_city'];
		$this->contact->published = $this->data['published'];
		return $this->contact->save();
	}

	/**
	 * Handle data to edit contact
	 * 
	 * @return boolean is true if updated or false is not
	 */
	public function editContactAction($id) {

		$checkRow = $this->findById($id);
		if ($checkRow === false) {
			return $checkRow;
		} else {
			$this->contact->id = $id;
			$this->contact->name = $this->data['name'];
			$this->contact->first_name = $this->data['first_name'];
			$this->contact->street = $this->data['street'];
			$this->contact->zip_code = $this->data['zip_code'];
			$this->contact->id_city = $this->data['id_city'];
			return $this->contact->save();
		}
	}
	/**
	 * Handle update group of contact
	 * 
	 * @param int $id is id of contact
	 */
	public function updateGroupContactAction($id){
		$this->contact->id = $id;
		$this->contact->id_group = $this->data['id_group'];
		
		return $this->contact->updateGroupOfContact();
	}
	
	/**
	 * Handle data to delete contact
	 * 
	 * @param boolean true if deleted false if cannot
	 */
	public function deleteContactAction($id) {
		$checkRow = $this->findById($id);
		if ($checkRow === false) {
			return $checkRow;
		} else {
			return $this->contact->deleteRow($id);
		}
	}

	/**
	 * Handle list all data contact
	 * 
	 * @return mixed is an array all of rows in database or false if select fail
	 */
	public function listContactAction($order_by = 'name', $sort = 'ASC') {
		return $this->contact->getAll($order_by, $sort);
	}

	/**
	 * Handle export data to XML action
	 * 
	 * @return string XML format of list data contact
	 */
	public function exportContactAction() {
		$contact = $this->contact->getAll('id', 'ASC');
		// new dom object
		$domXml = new \DOMDocument();
		$domXml->formatOutput = true;
		$domXml->encoding = 'utf-8';
		// create root element
		$rootXml = $domXml->createElement("address_book");
		$domXml->appendChild($rootXml);

		// create the simple xml element
		$simpleXmlElement = simplexml_import_dom($domXml);
		$i = 1;
		foreach ($contact as $add) {
			$contactNode = $simpleXmlElement->addchild("contact_".$i); 
			$contactNode->addChild('id', $add['id']);
			$contactNode->addChild('name', $add['name']);
			$contactNode->addChild('first_name', $add['first_name']);
			$contactNode->addChild('street', $add['street']);
			$contactNode->addChild('zip_code', $add['zip_code']);
			$contactNode->addChild('city_name', $add['city_name']);
			$contactNode->addChild('created', $add['created']);
			$contactNode->addChild('modified', $add['modified']);
			$i++;
		}
		return $simpleXmlElement->asXML();		
	}

	/**
	 * Handle data to find an contact with id
	 * 
	 * @return array content data of row
	 */
	public function findById($id) {
		return $this->contact->findById($id);
	}

	/**
	 * Handle get list cities
	 * 
	 * @return array list cities
	 */
	public function getCities() {
		$cityCtrl = new City();
		return $cityCtrl->getAll();
	}

}

?>