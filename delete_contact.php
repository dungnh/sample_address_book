<?php 
/**
 * File delete_contact.php
 *
 * PHP version 5.3+
 * A view to transfer data to delete an contact form
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2013-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root
 * 
 */
if(!isset($_GET['id'])){
	header("Location: index.php");
}else{
	require_once 'autoloader.php';
	$contactCtrl = new Controllers\ContactController();
	$id = $_GET['id'];
	$contactCtrl->deleteContactAction($id);
	header("Location: index.php");
}

?>