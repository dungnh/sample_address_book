<?php
/**
 * File create_group.php
 *
 * PHP version 5.3+
 * A view to get user interface of create group form
 * 
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2013-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root 
 */
require_once 'autoloader.php';
$contactCtrl = new Controllers\GroupController();
$groups = $contactCtrl->getAllGroups();
if (!empty($_POST)) {
	$contactCtrl->data = $_POST;
	$insert = $contactCtrl->createGroupAction();
	if ($insert !== false) {
		header('Location: groups.php');
	} else {
		$error_log = 'Error: Cannot insert data!';
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Address book :: Create group</title>
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
                    <h2 class="title">Create group</h2>
                    <div class="nav-menu">
						<a href="groups.php">Groups list</a> | <a href="index.php">Contacts list</a>
                    </div>
					<div id="form">
						<?php if (isset($error_log)) { ?>
							<span class='error'><?php echo $error_log; ?></span>
						<?php } ?>
						<form id="fmCreateGroup" method="POST" action="create_group.php">
							<label for="name">Name</label>
							<input type="text" id="name" name="group_name" class="text" required>
							<label for="description">Description</label>
							<textarea id="description" rows="2"name="description"></textarea>
							<label for="name">Inherit contacts from</label>
							<div id="checkbox" style="width:340px">
								<ul>
									<?php foreach ($groups as $group): ?>
										<li>
											<input type="checkbox" class="check-group" style="display:inline" id="group_<?php echo $group['id'] ?>" value="<?php echo $group['id'] ?>" name="id_parent[]">
											<label style="display:inline" for="group_<?php echo $group['id'] ?>"><?php echo $group['group_name'] ?></lable>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
							<input type="submit" value="Save" class="submit" />
							<input type="reset" value="Cancel" class="submit" />
						</form>
					</div>
                </div>
            </div>
        </div>


        <!-- script -->
        <script src="assets/js/main.js"></script>
    </body>
</html>