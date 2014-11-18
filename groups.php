<?php
/**
 * File groups.php
 *
 * PHP version 5.3+
 * A view of groups page. This is listing all of data from groups table
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2014-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  root
 */
require_once 'autoloader.php';
$groupCtrl = new Controllers\GroupController();
$groups = $groupCtrl->getAllGroups();
$groupInherits = $groupCtrl->getGroupInherits();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>List groups</title>
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
                    <h2 class="title">List groups</h2>
                    <div class="nav-menu">
                        <a href="create_group.php">Create group</a> | <a href="index.php">Contacts list</a>
                    </div>    
					<div id="content_data">
						<table id="table">
							<thead>
								<tr>
									<th class="first">#</th>
									<th>Group name</th>
									<th>Inherit contacts from</th>
									<th class="long-text">Description</th>
									<th class="last">Actions</th>
								</tr>
							</thead>
							<tbody>	
								<?php
								if (!empty($groups)):
									$i = 0;
									foreach ($groups as $group):
										$classRow = ($i % 2 == 0 ? 'even' : 'odd');
										$i++;
										?>
										<tr class="<?php echo $classRow ?>">
											<td><?php echo $i ?></td>
											<td><?php echo $group['group_name'] ?></td>
											<td>
												<?php 
													$j = 0;
													// display all groups inherit
													foreach($groupInherits as $groupInherit):
														if($groupInherit['id']==$group['id']){
															if($j!=0){
																echo '<br/>';
															}
															echo $parentName = '- '.$groupInherit['parent_name'];
															$j++;
															continue;
														}
													endforeach;
													// if it has not any inherit, display none
													if($j==0){
														echo 'none';
													}
												?>
											</td>
											<td><?php echo $group['description'] ?></td>
											<td nowrap><a href="view_group_contacts.php?id_group=<?php echo $group['id']?>" class="edit">View contacts</a> 
												| <a href="edit_group.php?id=<?php echo $group['id'] ?>" class="edit">Edit</a> 
												| <a href="javascript:void(0)" onclick='deleteConfirm("delete_group.php?id=<?php echo $group['id'] ?>")' id="delete" class="delete">Delete</a>
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