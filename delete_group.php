<?php 
/**
 * File delete_group.php
 *
 * PHP version 5.3+
 * A view to transfer data to delete an group
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
	header("Location: groups.php");
}else{
	require_once 'autoloader.php';
	$groupCtrl = new Controllers\GroupController();
	$id = $_GET['id'];
	$groupCtrl->deleteGroupAction($id);
	header("Location: groups.php");
}

?>