<?php
/**
 * File export_xml.php
 *
 * PHP version 5.3+
 * A view to handle exporting data of Contact to XML
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com 
 * @category  root
 */
require_once 'autoloader.php';
$contactCtrl = new Controllers\ContactController();
$filename = "Address_book_".date('y_m_d').'.xml';
header('Content-type: text/xml');
header("Content-Disposition: attachment; filename=\"$filename\"");
echo $contactCtrl->exportContactAction();
exit();
?>
