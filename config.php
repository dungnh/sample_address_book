<?php

/**
 * File config.php
 *
 * PHP version 5.3+
 * Content defined configuration parameters of app
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 Dzung Nguyen
 * @license   http://evolpas.com license
 * @version   XXX
 * @link      http://evolpas.com
 * @category  root
 */

/* database hosting */
$db_host = '192.168.1.150';
/* database name */
$db_name = 'address_books';
/* enter database username */
$db_username = 'ilucians_inrs';
/* enter database password */
$db_password = 'inrs2410';

$configParams = array(
	'database' => array(
		'dsn' => 'mysql:host=' . $db_host . ';dbname=' . $db_name,
		'db_user' => $db_username,
		'db_pass' => $db_password
	),
);
return $configParams;
?>