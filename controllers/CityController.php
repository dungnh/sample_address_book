<?php

/**
 * File CityController.php
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

use Models\City;

/**
 * Class CityController
 *
 * Controller of City: Handle request actions & resopnse data to view
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
class CityController {

	/**
	 * @var object instance of City model
	 */
	public $city;

	/**
	 * @var array containt data of city model
	 */
	public $data = array();

	/**
	 * City controller construction method
	 * 
	 */
	public function __construct() {
		$this->city = new City();
	}

	/**
	 * Handle data to create new city
	 * 
	 * @return mixed is value of latest id inserted or false if insert error
	 */
	public function createCityAction() {

		$this->city->city_name = $this->data['city_name'];
		$this->city->description = $this->data['description'];
		return $this->city->save();
	}

	/**
	 * Handle data to edit city
	 * 
	 * @return boolean is true if updated or false is not
	 */
	public function editCityAction($id) {

		$checkRow = $this->findById($id);
		if ($checkRow === false) {
			return $checkRow;
		} else {
			$this->city->id = $id;
			$this->city->city_name = $this->data['city_name'];
			$this->city->description = $this->data['description'];
			return $this->city->save();
		}
	}

	/**
	 * Handle data to delete city
	 * 
	 * @param boolean true if deleted false if cannot
	 */
	public function deleteCityAction($id) {
		$checkRow = $this->findById($id);
		if ($checkRow === false) {
			return $checkRow;
		} else {
			return $this->city->deleteRow($id);
		}
	}

	/**
	 * Handle list all data city
	 * 
	 * @return array list data city
	 */
	public function listCityAction() {
		return $this->city->getAll();
	}

}

?>