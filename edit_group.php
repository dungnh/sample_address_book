<?php
/**
 * File edit_group.php
 *
 * PHP version 5.3+
 * A view to get user interface of edit group form
 * 
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2013-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root 
 */
require_once 'autoloader.php';
$groupCtrl = new Controllers\GroupController();
$groups = $groupCtrl->getAllGroups();
$classError = ''; // this is get class of span display message error or not
if (!empty($_POST)) {
	$groupCtrl->data = $_POST;
	$edit = $groupCtrl->editGroupAction($groupCtrl->data['id']);
	if ($edit !== false) {
		header('Location: edit_group.php?id=' . $groupCtrl->data['id'].'&status=1');		
	} else {
		$message_log = 'Error: Cannot update data!';
		$classError = 'error';
	}
} else {
	@$id = $_GET['id'];
	$groupById = $groupCtrl->getGroupById($id);
	if ($groupById === false) {
		header('Location: groups.php');
	}
	if(isset($_GET['status'])){
		$message_log = 'The group was updated successfully';		
	}
}
// get list parent
$groupParents = $groupCtrl->getGroupInherited($id);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Address book :: Edit group</title>
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
                    <h2 class="title">Edit group</h2>
                    <div class="nav-menu">
						<a href="groups.php">Groups list</a> | <a href="create_group.php">Create group</a> | <a href="index.php">Contacts list</a>
                    </div>
					<div id="form">
						<?php if (isset($message_log)) { ?>
							<span class='<?php echo $classError?>'><?php echo $message_log; ?></span>
						<?php } ?>
						<form id="fmEditGroup" method="POST" action="edit_group.php">
							<input type="hidden" name='id' value='<?php echo (!empty($id) ? $id : '') ?>' />
							<label for="name">Name</label>
							<input type="text" id="name" name="group_name" value="<?php echo isset($groupById['group_name']) ? $groupById['group_name'] : '' ?>" class="text" required>
							<label for="description">Description</label>
							<textarea id="description" rows="2" name="description"><?php echo isset($groupById['description']) ? $groupById['description'] : '' ?></textarea>
							<label for="name">Inherit contacts from</label>
							<div id="checkbox" style="width:340px">
								<ul>
									<?php
									foreach ($groups as $group):
										if ($group['id'] == $id) {
											continue;
										}
										foreach ($groupParents as $groupParent) {
											$checked = '';
											if ($groupParent['id_parent'] == $group['id']) {
												$checked = 'checked';
												break;
											}
										}
										?>
										<li>
											<input <?php echo isset($checked) ? $checked : '' ?> type="checkbox" class="check-group" style="display:inline" id="group_<?php echo $group['id'] ?>" value="<?php echo $group['id'] ?>" name="id_parent[]">
											<label style="display:inline" for="group_<?php echo $group['id'] ?>"><?php echo $group['group_name'] ?></lable>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
							<input type="submit" value="Save" class="submit" />
							<input type="reset" value="Cancel" onclick='document.location.href="groups.php"' class="submit" />
						</form>
					</div>
                </div>
            </div>
        </div>


        <!-- script -->
        <script src="assets/js/main.js"></script>
    </body>
</html>