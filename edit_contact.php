<?php
/**
 * File edit_contact.php
 *
 * PHP version 5.3+
 * A view to get user interface of edit contact form
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2013-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root 
 */
require_once 'autoloader.php';
$contactCtrl = new Controllers\ContactController();

if (!empty($_POST)) {
	$contactCtrl->data = $_POST;
	$edit = $contactCtrl->editContactAction($contactCtrl->data['id']);
	if ($edit !== false) {
		header('Location: index.php');
	} else {
		$error_log = 'Error: Cannot update data!';
	}
} else {
	@$id = $_GET['id'];
	$cities = $contactCtrl->getCities();
	$contacts = $contactCtrl->findById($id);
	if ($contacts === false) {
		header('Location: index.php');
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Address book :: Edit contact</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>
        <!-- container -->
        <div id="container">
            <!-- main -->
            <div id="main">
                <div class="center">
                    <h2 class="title">Edit contact</h2>
                    <div class="nav-menu">
                        <a href="index.php">Contacts list</a> | <a href="create_contact.php">Create contact</a>
                    </div>
					<div id="form">
						<?php if (isset($error_log)) { ?>
							<span class='error'><?php echo $error_log; ?></span>
						<?php } ?>
						<form id="fmEditContact" method="POST" action="edit_contact.php">
							<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '' ?>" />
							<label for="name">Name</label>
							<input type="text" id="name" value="<?php echo isset($contacts['name']) ? $contacts['name'] : '' ?>" name="name" class="text" required>
							<label for="first_name">First name</label>
							<input type="text" id="first_name" value="<?php echo isset($contacts['first_name']) ? $contacts['first_name'] : '' ?>" name="first_name" class="text" required>
							<label for="street">Street</label>
							<input type="text" id="street" value="<?php echo isset($contacts['street']) ? $contacts['street'] : '' ?>" name="street" class="text" required>
							<label for="zip_code">Zip-code</label>
							<input type="text" id="zip_code" value="<?php echo isset($contacts['zip_code']) ? $contacts['zip_code'] : '' ?>" name="zip_code" class="text" required>
							<label for="city">City</label>
							<select id="id_city" name="id_city" class="select">
								<option value="">--Select City--</option>
								<?php
									foreach ($cities as $city):
									$selected = $city['id'] == $contacts['id_city'] ? 'selected' : '';
								?>
									<option <?php echo $selected ?> value="<?php echo $city['id'] ?>"><?php echo $city['city_name'] ?></option>
								<?php endforeach; ?>
							</select> <input type="button" onclick="javascript: dialogModal()" value="Add" style="height: 30px" />
							<input type="submit" value="Save" class="submit">
							<input type="reset" value="Cancel" onclick='document.location.href="index.php"' class="submit">
						</form>
					</div>
                </div>
            </div>
        </div>
		<!-- Dialog model to add city -->
		<div id="overlay">
			<div id="content" class="content-dialog">
				<div id="toolbar" class="toolbar-dialog"><p>Add City</p><a href="javascript:void(0)" onclick="javascript: dialogModal()">[X]</a></div>
				<div>
					<div class="message"><span id="message"></span></div>
					<form id="fmAddCity" method="POST" action="add_cites.php">
						<label for="city_name">City name</label>
						<input type="text" id="city_name" name="city_name" class="text" required>
						<label for="description">Description</label>
						<input type="text" id="description" name="description" class="text">					
						<input type="submit" onclick="addCity();return false;" value="Save" class="submit">
						<input type="reset" onclick="javascript:dialogModal();" value="Cancel" class="submit">
					</form>
				</div>
			</div>
		</div>
        <!-- script -->
        <script src="assets/js/main.js"></script>
    </body>
</html>