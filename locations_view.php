<?php
include('header.php');
$obj = new Locations();
$result = $obj->viewLoc();
$user = getPermissions('locations');
if($user['view'] != '1')
{
	header("location:page_not_found.php");
}
?>
<script src="js/jquery.dataTables.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	$('#datatables').dataTable({
		"sPaginationType":"full_numbers",
		"aaSorting":[[0, "asc"]],
		"bJQueryUI":true
	});
})
</script>
<style type="text/css">
	@import "css/demo_table_jui.css";
	@import "css/jquery-ui-1.8.4.custom.css";
</style>
<div id="content">
    <div id="main">
		<div class="full_w green center">
		<?php if(isset($_SESSION['succ_msg'])){ echo $_SESSION['succ_msg']; unset($_SESSION['succ_msg']);} ?>
		</div>
        <div class="full_w">
            <div class="h_title">Locations View
			<?php if($user['add_role'] != '0'){?>
			<a href="locations.php" class="gird-addnew" title="Add New Location">Add New</a>
			<?php } ?>
			</div>
            <table id="datatables" class="display">
                <thead>
                    <tr>
                        <th >ID</th>
                        <th >Name</th>                        
                        <th >Add Date</th>
						<th >Update Date</th>
						<?php if($user['edit'] != '0' || $user['delete_role'] != '0'){?>
                        <th >Action</th>
						<?php } ?>
                    </tr>
                </thead>
                <tbody>

					<?php while ($data = $result->fetch_assoc()){ ?>
					<tr>
                        <td class="align-center"><?php echo $data['id'] ?></td>
                        <td><?php echo $data['name'] ?></td>                        
                        <td><?php echo $data['date_add'] ?></td>
						<td><?php echo $data['date_update'] ?></td>
						<?php if($user['edit'] != '0' || $user['delete_role'] != '0'){?>
							<td class="align-center" id="<?php echo $data['id'] ?>">
								<?php if($user['edit'] != '0'){?>					
									 <a href="locations.php?edit=<?php echo base64_encode($data['id']) ?>" class="table-icon edit" title="Edit"></a>
								<?php } ?>
								<?php if($user['delete_role'] != '0'){?>					
									 <a href="#" class="table-icon delete" onClick="deleteLoc(<?php echo $data['id'] ?>)"></a>
								<?php } ?>
							</td>
					   <?php }?>						
                        </td>
                    </tr>
					<?php }?>
                </tbody>
            </table>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>

