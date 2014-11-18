<?php

/**
 * File autoloader.php
 *
 * PHP version 5.3+
 * Handle an autoloader classes of app
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 Dzung Nguyen
 * @license   http://evolpas.com license
 * @version   XXX
 * @link      http://evolpas.com
 */
$config = include __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
function __autoload($className) {
	$arrPathFile = explode("\\", $className);
	$folderNamespace = strtolower($arrPathFile[0]);
	$classFile = $arrPathFile[1];
	$pathClass = __DIR__ . DIRECTORY_SEPARATOR . $folderNamespace . DIRECTORY_SEPARATOR . $classFile . '.php';
	if (file_exists($pathClass)) {			
		include $pathClass;			
	} else {
		echo "Cannot found class " . $className;
		exit();
	}
}

?>
