<?php
/**
 * File view_group_contacts.php
 *
 * PHP version 5.3+
 * A view of view_group_contacts page. This is listing all of data from view_group_contacts table
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root
 */
require_once 'autoloader.php';
// new group controller object
$groupCtrl = new Controllers\GroupController();
@$id_group = $_GET['id_group'];
// get group info
$group = $groupCtrl->getGroupById($id_group);
// get list contacts of group include contacts inherited
$contacts = $groupCtrl->getContactListAction($id_group);
// get list private contacts
$privateContacts = $groupCtrl->getPrivateContactsAction($id_group);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Address book::List contacts of group</title>
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
                    <h2 class="title">List contacts of <?php echo $group['group_name'] ?></h2>
                    <div class="nav-menu">
                        <a href="groups.php">Groups list</a> | <a href="create_group.php">Create group</a> | <a href="index.php">Contacts list</a>
                    </div>    
					<div id="content_data">
						<table id="table">
							<thead>
								<tr>
									<th class="first">#</th>
									<th>Name</th>
									<th>First name</th>
									<th class="long-text">Street</th>
									<th>Zip-code</th>
									<th>City</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>	
								<?php
								if (!empty($contacts)):
									$i = 0;
									foreach ($contacts as $contact):
										$classRow = ($i % 2 == 0 ? 'even' : 'odd');
										$i++;
										$isPrivateContact = false;
										foreach ($privateContacts as $privateContact):
											$isPrivateContact = ($privateContact['id']==$contact['id']);
											if($isPrivateContact){ // if contacts are private of group, display white
												$classRow = 'even';
												break;
											}else{  // if contacts are inherited of groups, display dark
												$classRow = 'odd';
											}
										endforeach;
										?>
										<tr class="<?php echo $classRow ?>">
											<td><?php echo $i ?></td>
											<td><?php echo $contact['name'] ?></td>
											<td><?php echo $contact['first_name'] ?></td>
											<td><?php echo $contact['street'] ?></td>
											<td><?php echo $contact['zip_code'] ?></td>
											<td><?php echo $contact['city_name'] ?></td>
											<td>
												<a href="add_contact_group.php?id_contact=<?php echo $contact['id'] ?>" class="edit">Group</a>
												<?php if ($isPrivateContact): // if it is not private contact of group, prevent to remove contacts from group ?>
													| <a href="remove_contact_group.php?id_contact=<?php echo $contact['id'] ?>&id_group=<?php echo $id_group ?>" id="delete" class="delete">Remove</a>
												<?php endif; ?>
											</td>
										</tr>
										<?php
									endforeach;
								else:
									?>  
									<tr class="even">
										<td colspan="7"><?php echo 'The list is empty!' ?></td>
									</tr>
								<?php
								endif;
								?>
							</tbody>
						</table>
					</div>
                </div>
            </div>
        </div>
        <!-- script -->
        <script src="assets/js/main.js"></script>
    </body>
</html>