<?php
/**
 * File sort_contact.php
 *
 * PHP version 5.3+
 * Handle an Ajax request to sorting data of contact table
 *
 * @author    Dzung Nguyen <dungnh@gmail.com>
 * @copyright 2013-2015 evolpas
 * @license   http://www.evolpas.com license
 * @version   XXX
 * @link      http://www.evolpas.com
 * @category  partial 
 */
require_once '../autoloader.php';
$contactCtrl = new Controllers\ContactController();
$sort = 'DESC';
if (!empty($_GET)) {
	$order_by = $_GET['order_by'];
	$sort = $_GET['sort'];
	$contacts = $contactCtrl->listContactAction($order_by, $sort);
} else {
	$contacts = $contactCtrl->listContactAction();
}
?>
<input type="hidden" value="<?php echo $sort == 'DESC' ? 'ASC' : 'DESC'; ?>" name="sort" id="sort" />
<table id="table">
	<thead>
		<tr>
			<th class="first">#</th>
			<th><a class="sort" onclick="sortData('name')" href="#">Name</a></th>
			<th><a class="sort" onclick="sortData('first_name')" href="#">First name</a></th>
			<th class="long-text"><a class="sort" onclick="sortData('street')" href="#">Street</a></th>
			<th><a class="sort" onclick="sortData('zip_code')" href="#">Zip-code</a></th>
			<th><a class="sort" onclick="sortData('city_name')" href="#">City</a></th>
			<th class="last">Actions</th>
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
					<td nowrap>
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
