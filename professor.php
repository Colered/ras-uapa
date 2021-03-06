<?php
include('header.php');
$user = getPermissions('teachers');
$objT = new Teacher();
if(isset($_GET['edit']) && $_GET['edit']!=''){
	if($user['edit'] != '1')
	{
		echo '<script type="text/javascript">window.location = "page_not_found.php"</script>';
	}
    $result =  $db->query("select * from teacher where id='".base64_decode($_GET['edit'])."'");
	$row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    // set the value
    $button_save = 'Edit Teacher';
    $years = floor($row['experience']/12);
    $months = $row['experience']-$years*12;
}else{
	if($user['add_role'] != '1')
	{
		echo '<script type="text/javascript">window.location = "page_not_found.php"</script>';
	}
    $button_save = 'Add Teacher';
    $years = isset($_POST['years']) ? $_POST['years']:'';
    $months = isset($_POST['months']) ? $_POST['months']:'';
}
$teachername = isset($_GET['edit']) ? $row['teacher_name'] : (isset($_POST['txtPname'])? $_POST['txtPname']:'');
$proftype = isset($_GET['edit']) ? $row['teacher_type'] : (isset($_POST['proftype'])? $_POST['proftype']:'');
$address = isset($_GET['edit']) ? $row['address'] : (isset($_POST['txtAreaAddress'])? $_POST['txtAreaAddress']:'');
$dob = isset($_GET['edit'])? $row['dob'] : (isset($_POST['dob']) ? $_POST['dob']:'');
$doj = isset($_GET['edit'])? $row['doj'] : (isset($_POST['doj'])? $_POST['doj'] : '');
$sex = isset($_GET['edit'])? $row['gender'] : (isset($_POST['sex'])? $_POST['sex'] : '');
$degination = isset($_GET['edit'])? $row['designation'] : (isset($_POST['txtDegination']) ? $_POST['txtDegination']:'');
$qualification = isset($_GET['edit'])? $row['qualification'] : (isset($_POST['txtQualification'])? $_POST['txtQualification'] : '');
$email = isset($_GET['edit'])? $row['email'] : (isset($_POST['txtEmail'])? $_POST['txtEmail'] : '');
$username = isset($_GET['edit'])? $row['username'] : (isset($_POST['txtUname'])? $_POST['txtUname'] : '');
$payrate = isset($_GET['edit'])? $row['payrate'] : (isset($_POST['txtPayrate'])? $_POST['txtPayrate'] : '');
$teacher_code = isset($_GET['edit'])? (isset($row['teacher_code'])? $row['teacher_code'] : ''): (isset($_POST['txtteacher_code'])? $_POST['txtteacher_code'] : '');

$result_type = $objT->getTeachersType();


?>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/additional-methods.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#frmProff').validate({ // initialize the plugin
        rules: {
            actual: {
                required: true,
                email: true
            }
        }
    });
});
</script>
<div id="content">
    <div id="main">
        <div class="full_w">
            <div class="h_title">Teacher</div>
            <form name="frmProff" id="frmProff" action="postdata.php" method="post">
              <input type="hidden" name="form_action" value="add_edit_professor" />
              <?php if(isset($_GET['edit'])){?>
              		<input type="hidden" name="form_edit_id" value="<?php echo $_GET['edit'];?>" />
              <?php } ?>
                <div class="custtable_left" >
                    <div class="custtd_left red">
						<?php if(isset($_SESSION['error_msg'])) echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
					</div>
					<div class="clear"></div>

                    <div class="custtd_left">
                        <h2>Teacher Name<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" class="inp_txt required" id="txtPname" maxlength="50" name="txtPname" value="<?php echo $teachername;?>">
                    </div>
                    <div class="clear"></div>
					
					 <div class="custtd_left">
                        <h2>Teacher Code<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" class="inp_txt required" id="txtteacher_code" maxlength="50" name="txtteacher_code" value="<?php echo $teacher_code;?>">
                    </div>
                    <div class="clear"></div>

                    <div class="custtd_left">
					  <h2>Type</h2>
					</div>
					<div class="txtfield">
					<select id="proftype" name="proftype" class="select1">
					<option value="">--Select Type--</option>
					<?php while($row_type = $result_type->fetch_assoc()){?>						
						<option value="<?php echo $row_type['id'];?>"><?php echo $row_type['teacher_type_name'];?></option>
					<?php } ?>
					</select>
					<script type="text/javascript">
						jQuery('#proftype').val("<?php echo $proftype;?>");
					</script>
					</div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Address</h2>
                    </div>
                    <div class="txtfield">
                        <textarea style="height:40px;" class="inp_txt" id="txtAreaAddress" cols="20" rows="2" name="txtAreaAddress"><?php echo $address;?></textarea>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Date of Birth</h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" id="dob" name="dob" size="12" value="<?php echo $objT->formatDate($dob);?>" readonly />
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Joining Date</h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" id="doj" name="doj" size="12" value="<?php echo $objT->formatDate($doj);?>" readonly />
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Gender</h2>
                    </div>
                    <div class="txtfield">
                        <input type="radio" name="sex" value="male" class="radiobutt" <?php echo ($sex=='male')? 'checked':'';?> />Male
                        <input type="radio" name="sex" value="female" class="radiobutt" <?php echo ($sex=='female')? 'checked':'';?> />Female
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Desigation</h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" class="inp_txt" id="txtDegination" maxlength="20" name="txtDegination" value="<?php echo $degination;?>">
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Qualifications</h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" class="inp_txt" id="txtQualification" maxlength="50" name="txtQualification" value="<?php echo $qualification;?>"/>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Pay Rate</h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" class="inp_txt" id="txtPayrate" maxlength="50" name="txtPayrate" value="<?php echo $payrate;?>"/>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Experience </h2>
                    </div>
                    <div class="txtfield">
                        <select id="years" name="years" class="select">
						<option value="">--Select Years--</option>
						<?php
							for($i=1;$i<=50;$i++){
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
                        <script type="text/javascript">
							jQuery('#years').val("<?php echo $years;?>");
			            </script>
                        </select>
                        <select id="months" name="months" class="select">
						<option value="">--Select Month--</option>
						<?php
								for($i=1;$i<=11;$i++){
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
						?>
						<script type="text/javascript">
							jQuery('#months').val("<?php echo $months;?>");
			            </script>
                        </select>
                    </div>
                    <div class="clear"></div>

                    <?php if(!isset($_GET['edit'])){?>
							<div class="custtd_left">
								<h2>Email<span class="redstar">*</span></h2>
							</div>
							<div class="txtfield">
								<input type="text" class="inp_txt required email" id="txtEmail" maxlength="50" name="txtEmail" value="<?php echo $email;?>">
							</div>
							<div class="clear"></div>
							<div class="custtd_left">
								<h2>Username<span class="redstar">*</span></h2>
							</div>
							<div class="txtfield">
								<input type="text" class="inp_txt required" id="txtUname" maxlength="50" name="txtUname" value="<?php echo $username;?>">
							</div>
							<div class="clear"></div>
                    <?php } ?>
                    <div class="custtd_left">
                        <h3>All Fields are mandatory.</h3>
                    </div>
                    <div class="txtfield">
                        <input type="submit" name="btnAdd" id="btnAdd" class="buttonsub" value="<?php echo $button_save;?>">
                        <input type="button" name="btnCancel" class="buttonsub" value="Cancel" onclick="location.href = 'teacher_view.php';">
                    </div>
                </div>
            </form>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>


