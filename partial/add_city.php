<?php

/**
 * File add_city.php
 *
 * PHP version 5.3+
 * Handle an Ajax request to add new city to cities table
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2013-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  partial 
 * 
 */

require_once '../autoloader.php';
$city = new Controllers\CityController();
if(!empty($_POST)){
	$response = array('lastId' => 0, 'status'=>false, 'message'=>'Error: cannot add city!');
	$city->data = $_POST;
	$result = $city->createCityAction();
	if($result!==false){
		$response['lastId'] = $result;
		$response['status'] = true;
		$response['message'] = 'The city is added';		
	}
	echo json_encode($response);
	exit();
}else{
	header("Location: ../index.php");
}

?>
