<?php
/**
 * File add_contact_group.php
 *
 * PHP version 5.3+
 * A view of add contact group page.
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root
 */
require_once 'autoloader.php';

// new contact controller
$contactCtrl = new Controllers\ContactController();
$classError = ''; // this is get class of span display message error or not
if (!empty($_POST)) {
	$contactCtrl->data = $_POST;
	$edit = $contactCtrl->updateGroupContactAction($contactCtrl->data['id']);
	if ($edit !== false) {
		header('Location: add_contact_group.php?id_contact=' . $contactCtrl->data['id'].'&status=1');
		//$error_log = 'Update group of contact successful!';
	} else {
		$message_log = 'Error: Cannot update data!';
	}
} else {
	@$id = $_GET['id_contact'];
	if(isset($_GET['status'])){
		$message_log = 'The contact was added to group successfully';		
	}
// get contact info based on id contact passed via url
	$contact = $contactCtrl->findById($id);
	if ($contact === false) {
		header('Location: groups.php');
	}
// new group controller
	$groupCtrl = new Controllers\GroupController();
// get all groups
	$groups = $groupCtrl->getAllGroups();
// get connections of group
	$groupInherits = $groupCtrl->getGroupInherits();
// get groups list of contact
	$groupsInherited = $groupCtrl->listGroupsOfContact($contact['id']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Address book::List groups</title>
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
                    <h2 class="title">Add contact <?php echo $contact['name'] ?> to groups</h2>
                    <div class="nav-menu">
                        <a href="index.php">Contacts list</a> | <a href="groups.php">Groups list</a> | <a href="create_group.php">Create group</a>
                    </div>    
					<div id="content_data">
						<div id="form">
							<?php if (isset($message_log)) { ?>
								<span class='<?php echo $classError ?>'><?php echo $message_log; ?></span>
							<?php } ?>
							<form id='fmAddContactToGroups' method='POST' action='add_contact_group.php' >
								<input type='hidden' name='id' value='<?php echo isset($id) ? $id : '' ?>' />
								<table id="table">
									<thead>
										<tr>
											<th class="first">#</th>
											<th class="first"></th>
											<th>Group name</th>
											<th>Inherit contacts from</th>									
										</tr>
									</thead>
									<tbody>	
										<?php
										if (!empty($groups)):
											$i = 0;
											foreach ($groups as $group):
												$classRow = ($i % 2 == 0 ? 'even' : 'odd');
												$i++;
												foreach ($groupsInherited as $gh):
													$checked = '';
													if ($gh['id'] == $group['id']) {
														$checked = 'checked';
														break;
													}
												endforeach;
												?>
												<tr class="<?php echo $classRow ?>">
													<td><?php echo $i ?></td>
													<td><input type="checkbox" <?php echo isset($checked) ? $checked : '' ?> class="check-group" id="group_<?php echo $group['id'] ?>" value="<?php echo $group['id'] ?>" name="id_group[]"></td>
													<td><?php echo $group['group_name'] ?></td>
													<td>
														<?php
														$j = 0;
														foreach ($groupInherits as $groupInherit):
															if ($groupInherit['id'] == $group['id']) {
																if ($j != 0) {
																	echo '<br/>';
																}
																echo $parentName = '- ' . $groupInherit['parent_name'];
																$j++;
																continue;
															}
														endforeach;
														if ($j == 0) {
															echo 'none';
														}
														?>
													</td>																						
												</tr>
												<?php
											endforeach;
										else:
											?>  
											<tr class="even">
												<td colspan="6"><?php echo 'The list is empty!' ?></td>
											</tr>
										<?php
										endif;
										?>
									</tbody>
								</table>
								<div style='text-align: center; padding: 5px'>
									<input type='submit' style='width: auto' class='submit' name='btSubmit' value ='Add to Groups' /> 
									<input type='button' style='width: auto' class='submit' name='btBack' value ='Back' onclick='document.location.href="index.php";' />
								</div>	
							</form>	
						</div>
					</div>
                </div>
            </div>
        </div>
        <!-- script -->
        <script src="assets/js/main.js"></script>
    </body>
</html>