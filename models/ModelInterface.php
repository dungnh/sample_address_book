<?php

/**
 * File ModelInterface.php
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

/**
 * Interface ModelInterface
 *
 * Define some methods of CURD in model
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
interface ModelInterface {

	/**
	 * Save data to database
	 * 
	 * Depend to data, if id (primary key) == null it means insert else it means update
	 */
	public function save();

	/**
	 * Get a single row of table in database
	 * 
	 * @param int $id primary key of row
	 * @return array the data of row in database
	 */
	public function findById($id);

	/**
	 * Get all rows in database
	 * 
	 * @return array all of rows in database
	 */
	public function getAll();

	/**
	 * Delete a single row by id
	 * 
	 * @param int $id id of single row to delete
	 */
	public function deleteRow($id);

	/**
	 * Save a field of a row that fetched from database
	 * 
	 * @param string $fieldname name of field that need to update
	 * @param string $value value to update
	 * @param int $id id of row 
	 */
	public function updateField($fieldName, $value, $id);
}

?>
