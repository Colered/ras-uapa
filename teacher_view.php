<?php include('header.php');
$objT = new Teacher();
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
		<?php if(isset($_SESSION['succ_msg'])){ echo '<div class="full_w green center">'.$_SESSION['succ_msg'].'</div>'; unset($_SESSION['succ_msg']);} ?>
        <div class="full_w">
            <div class="h_title">Teachers View<a href="professor.php" class="gird-addnew" title="Add New Teacher">Add New</a></div>
            <table id="datatables" class="display">
                <thead>
                    <tr>
                        <th >ID</th>
                        <th >Name</th>
                        <th >Address</th>
                        <th >DOB</th>
                        <th >DOJ</th>
                        <th >Desigation</th>
                        <th >Qualification </th>
                        <th >Experience </th>
                        <th >Email</th>
                        <th >Username</th>
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
					$result =  $db->query("select * from teacher order by id");
					$row_cnt = $result->num_rows;
					while($row = $result->fetch_assoc()){
                ?>
                    <tr>
                        <td class="align-center"><?php echo $row['id'];?></td>
                        <td><?php echo $row['teacher_name'];?></td>
                        <td><?php echo $row['address'];?></td>
                        <td><?php echo $objT->formatDate($row['dob']);?></td>
                        <td><?php echo $objT->formatDate($row['doj']);?></td>
                        <td><?php echo $row['designation'];?></td>
                        <td><?php echo $row['qualification'];?></td>
                        <td><?php echo $objT->printTeacherExp($row['experience']);?></td>
                        <td><?php echo $row['email'];?></td>
                        <td><?php echo $row['username'];?></td>
                        <td class="align-center" id="<?php echo $row['id'] ?>">
                            <a href="professor.php?edit=<?php echo base64_encode($row['id']);?>" class="table-icon edit" title="Edit"></a>
                            <a href="#" class="table-icon delete" onClick="deleteTeacher(<?php echo $row['id'] ?>)"></a>
                        </td>
                    </tr>
              <?php }?>

                </tbody>
            </table>

            <?php if(isset($_SESSION['error_msg'])){ ?>
				<div><span class="red"><?php echo $_SESSION['error_msg']; $_SESSION['error_msg']=""; ?></span></div>
			<?php } ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>

