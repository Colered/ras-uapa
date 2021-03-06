<?php
include('header.php');
$objP = new Programs();
$objS = new Subjects();
$objT = new Teacher();
$rel_teacher = $objT->getTeachers();
$rel_prog = $objP->getProgramListYearWise();
$rel_subject = $objS->getSubjects();
$rel_session = $objS->getSessions();
?>
<div id="content">
    <div id="main">
        <div class="full_w">
            <div class="h_title">Teacher Activity</div>
			<form name="frmTactivity" id="frmTactivity" action="postdata.php" method="post">
			  <input type="hidden" name="form_action" value="add_teacher_activity" />
                <div class="custtable_left">
					<div class="custtd_left red">
					<?php if(isset($_SESSION['error_msg']))
						echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
					</div>
					<div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Program<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
					<select id="slctProgram" name="slctProgram" class="select1 required" onchange="showSubjects(this.value);">
					<option value="" selected="selected">--Select Program--</option>
					<?php
						while($row = $rel_prog->fetch_assoc()){
							//echo '<option value="'.$row['id'].'">'.$row['name'].' '.$row['start_year'].' '.$row['end_year'].'</option>';
							echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
						}
					?>
					</select>
                    </div>
                    <div class="clear"></div>
					<div class="custtd_left">
						<h2>Cycle <span class="redstar">*</span></h2>
					</div>
					<div class="txtfield">
					<select id="slctProgramCycle" name="slctProgramCycle" class="select1 required">
					<option value="" selected="selected">--Select Cycle--</option>

					</select>
					<div id="ajaxload_progCycle" style="float: right;display:none;"><img src="images/loading2.gif"  /><div class="wait-text">Please Wait...</div></div>
					</div>
					<div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Subject <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
					<select id="slctSubject" name="slctSubject" class="select1 required" onChange="showSessions(this.value);">
					<option value="" selected="selected">--Select Subject--</option>
					 <?php
						while($row = $rel_subject->fetch_assoc()){
							echo '<option value="'.$row['id'].'">'.$row['subject_name'].'</option>';
						}
					 ?>
					</select>
					<div id="ajaxload_subject" style="float: right;display:none;"><img src="images/loading2.gif"  /><div class="wait-text">Please Wait...</div></div>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
						<h2>Session <span class="redstar">*</span></h2>
					</div>
					<div class="txtfield">
					<select id="slctSession" name="slctSession" class="select1 required">
					<option value="" selected="selected">--Select Session--</option>
				    <?php
						while($row = $rel_session->fetch_assoc()){
							echo '<option value="'.$row['id'].'">'.$row['session_name'].'</option>';
						}
					 ?>
					</select>
					<div  id="ajaxload_session" style="float: right;display:none;"><img src="images/loading2.gif"  /><div class="wait-text">Please Wait...</div></div>
					</div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Teacher <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield" style="float:left">
                        <select id="slctTeacher" name="slctTeacher[]" class="selectMultiple required" size="10" multiple="multiple">
                        <?php
							while($row = $rel_teacher->fetch_assoc()){
								echo '<option value="'.$row['id'].'">'.$row['teacher_name'].' ('.$row['email'].')</option>';
							}
						?>
                        </select>
                    </div>
                    <div style="float:left;padding:133px 0px 0px 20px;"><input class="buttonsub" type="button" value="Add" name="btnTeacherAct" id="btnTeacherAct"></div>
                    <div id="ajaxload_actDiv" style="padding-top:130px;float:right;display:none;"><img src="images/loading2.gif"  /><div class="wait-text">Please Wait...</div></div>
                    <div class="clear"></div>

					<div id="activityReset" style="display:none;padding-left:10px;"><input class="buttonsub" type="button" value="Reset" name="btnTeacherActReset" id="btnTeacherActReset" onclick="reset_reserved_flag();"></div>
                    <div id="activityAddMore"></div>
                    <div><br /><br /><br /><br /></div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h3><span class="redstar">*</span>All Fields are mandatory.</h3>
                    </div>
                    <div class="txtfield">
                        <input type="submit" name="btnAdd" id="btnAddTeacherActivity" class="buttonsub" value="Save Activity">
                    </div>
                    <div class="txtfield">
                        <input type="button" name="btnCancel" class="buttonsub" value="Cancel" onclick="location.href = 'teacher_activity_view.php';">
                    </div>
                </div>
            </form>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>

