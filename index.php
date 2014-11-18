<?php
/**
 * File index.php
 *
 * PHP version 5.3+
 * A view of index page. This is listing all of data from contact table
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
$contacts = $contactCtrl->listContactAction();
// new group controller
$groupCtrl = new Controllers\GroupController();
// get all groups
$groups = $groupCtrl->getAllGroups();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Address book</title>
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
                    <h2 class="title">Contacts list</h2>
                    <div class="nav-menu">
                        <a href="create_contact.php">Create contact</a> | <a href="export_xml.php">Export XML</a> | <a href="groups.php">Groups list</a>
                    </div>    
					<div id="content_data">
						<input type="hidden" value="DESC" name="sort" id="sort" />
						<table id="table">
							<thead>
								<tr>
									<th class="first">#</th>
									<th><a class="sort" onclick="sortData('name')" href="#">Name</a></th>
									<th><a class="sort" onclick="sortData('first_name')" href="#">First name</a></th>
									<th class="long-text"><a class="sort" onclick="sortData('street')" href="#">Street</a></th>
									<th><a class="sort" onclick="sortData('zip_code')" href="#">Zip-code</a></th>
									<th><a class="sort" onclick="sortData('city_name')" href="#">City</a></th>
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
												| <a href="edit_contact.php?id=<?php echo $contact['id'] ?>" class="edit">Edit</a>
												| <a href="javascript:void(0)" onclick='deleteConfirm("delete_contact.php?id=<?php echo $contact['id'] ?>")' id="delete" class="delete">Delete</a>
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

		<!-- Dialog model to add city -->
		<div id="overlay">
			<div id="content" class="content-dialog">
				<div id="toolbar" class="toolbar-dialog"><p id="toolbar-title">Add Group</p><a href="javascript:void(0)" onclick="javascript: dialogModal()">[X]</a></div>
				<div>
					<div class="message"><span id="message"></span></div>
					<form id="fmAddCity" method="POST" action="add_contact_group.php">
						<input type="hidden" name="id_contact" id="id_contact" />
						<div id="checkbox">
							<ul>
								<?php foreach ($groups as $group): ?>
									<li>
										<input type="checkbox" class="check-group" style="display:inline" id="group_<?php echo $group['id'] ?>" value="<?php echo $group['id'] ?>" name="id_group[]">
										<label style="display:inline" for="group_<?php echo $group['id'] ?>"><?php echo $group['group_name'] ?></lable>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<input style="display:inline" type="button" onclick="addGroup();
											return false;" value="Save" class="submit">
						<input style="display:inline" type="reset" onclick="javascript:dialogModal();" value="Cancel" class="submit">
					</form>
				</div>
			</div>
		</div>

        <!-- script -->
        <script src="assets/js/main.js"></script>
    </body>
</html>