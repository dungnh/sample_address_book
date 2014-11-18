<?php 
/**
 * File remove_contact_group.php
 *
 * PHP version 5.3+
 * A view to transfer data to remove an contact from group
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2013-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root
 * 
 */
if(!isset($_GET['id_contact']) && !isset($_GET['id_group'])){
	header("Location: groups.php");
}else{
	require_once 'autoloader.php';
	$contactCtrl = new Controllers\GroupController();
	$id_contact = $_GET['id_contact'];
	$id_group = $_GET['id_group'];
	$contactCtrl->removeContactGroupAction($id_contact, $id_group);
	header("Location: view_group_contacts.php?id_group=$id_group");
}

?>