<?php
ob_start();
include('header.php');
$subjectName=""; $subjectCode=""; $sessionNum=""; $subjectCode="";$areaCode="";$areaName="";$programName="";$roomType="";$roomName="";$subjectId="";
$program_year_detail="";$programYearId=""; $cycle_no="";$cycleData="";$disTest=""; $disSession=""; $disDivCss=""; $disFDivCss="";$subIdEncrypt="";
$objT = new Teacher();
$rel_teacher = $objT->getTeachers();
$objS = new Subjects();
$objB = new Buildings();
$objTS = new Timeslot();
//room dropdown
$room_dropDwn = $objB->getRoomsDropDwn();
//timeslot dropdown
$tslot_dropDwn = $objTS->getTimeSlotStartDateDropDwn();


if(isset($_GET['edit']) && $_GET['edit']!=""){
	$disTest = "disabled";
	$disFDivCss  = "style='opacity:.5; pointer-event:none'";
	$subIdEncrypt = $_GET['edit'];
	$subjectId= base64_decode($_GET['edit']);
	$obj = new Subjects();
	$result = $obj->getDataBySubjectID($subjectId);
	$row = $result->fetch_assoc();
	if( $row){
		if(isset($row['program_year_id'])){
		$cycleData = $obj->getCycleByProgId($row['program_year_id']);
		}
		if(isset($row['cycle_no'])){
			$cycle_no = $row['cycle_no'];
		}
	}else{
		header('Location: subject_view.php');
		exit();
	}
}else{
	$disSession = "disabled";
	$disDivCss  = "style='opacity:.5; pointer-event:none'";
}
$objT = new Teacher();
$rel_teacher = $objT->getTeachers();
$subjectName = isset($_GET['edit']) ? $row['subject_name'] : (isset($_POST['txtSubjName'])? $_POST['txtSubjName']:'');
$subjectCode = isset($_GET['edit']) ? $row['subject_code'] : (isset($_POST['txtSubjCode'])? $_POST['txtSubjCode']:'');
$areaId = isset($_GET['edit']) ? $row['area_id'] : (isset($_POST['slctArea'])? $_POST['slctArea']:'');
$progId = isset($_GET['edit']) ? $row['program_year_id'] : (isset($_POST['slctProgram'])? $_POST['slctProgram']:'');
?>
<div id="content">
    <div id="main">
		<?php if(isset($_SESSION['succ_msg'])){ echo '<div class="full_w green center">'.$_SESSION['succ_msg'].'</div>'; unset($_SESSION['succ_msg']);} ?>
		<div class="custtd_left red">
			<?php if(isset($_SESSION['error_msg']))
				  echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
		</div>
		<div id="addSubAndSess" class="addSubAndSess" <?php /*?>style="opacity:.5; pointer-event:none;"<?php */?>>
	    <div class="full_w">
            <div class="h_title">Subject</div>
            <form name="subjectForm" id="subjectForm" action="postdata.php" method="post">
			<input type="hidden" name="form_action" value="addEditSubject" />
			<input type="hidden" id="subjectId" name="subjectId" value="<?php echo $subjectId; ?>" />
			<input type="hidden" id="subIdEncrypt" name="subIdEncrypt" value="<?php echo $subIdEncrypt; ?>" />

                <div class="custtable_left">
					<div class="addSubDiv" <?php echo $disFDivCss; ?>>
					<div class="custtd_left">
                        <h2>Choose Program<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <select id="slctProgram" name="slctProgram" class="select1 required" <?php echo $disTest; ?> onchange="getCycleByProgId(this)">
                            <option value="" selected="selected">--Select Program--</option>
                             <?php
					          $program_qry="select * from program_years";
					          $program_result= mysqli_query($db, $program_qry);
							  while ($program_data = mysqli_fetch_assoc($program_result)){
							  $program_year_detail=$program_data['name'].' '.$program_data['start_year'].' '.$program_data['end_year'];
							  $selected = (trim($progId) == trim($program_data['id'])) ? ' selected="selected"' : '';
							  ?>
					          <option value="<?php echo $program_data['id'];?>" <?php echo $selected;?>><?php echo $program_data['name'].' '.$program_data['start_year'].' '.$program_data['end_year'];?></option>
					     	 <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
					<div class="custtd_left">
                        <h2>Choose Cycle<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <select id="slctCycle" name="slctCycle" class="select1 required" <?php echo $disTest; ?>>
                            <option value="" selected="selected">--Select Cycle--</option>
							 <?php
							  for($i=1; $i<=$cycleData; $i++){
							  $selected = ($i == $cycle_no) ? ' selected="selected"' : '';
							  ?>
					          <option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i; ?></option>
					     	 <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
					<div class="custtd_left">
                        <h2>Choose Area <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
					     <select id="slctArea" name="slctArea" class="select1 required" <?php echo $disTest; ?>>
						 	  <option value="" selected="selected">--Select Area--</option>
					         <?php
					          $area_qry="select * from area";
					          $area_result= mysqli_query($db, $area_qry);
					          while ($area_data = mysqli_fetch_assoc($area_result)){
							  $selected = (trim($areaId) == trim($area_data['id'])) ? ' selected="selected"' : '';?>
					          <option value="<?php echo $area_data['id']?>" <?php echo $selected;?>><?php echo $area_data['area_name'];?></option>
					     <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Subject Name<span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" class="inp_txt required" id="txtSubjName" maxlength="50" name="txtSubjName" value="<?php echo $subjectName; ?>" <?php echo $disTest; ?>>
                    </div>
                    <div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Subject Code <span class="redstar">*</span></h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" class="inp_txt required" id="txtSubjCode" maxlength="50" name="txtSubjCode" value="<?php echo $subjectCode; ?>" <?php if($subjectId!=""){echo "readonly";} ?> <?php echo $disTest; ?>>
                    </div>
					<div class="sessionboxSub btnSessiondiv">
					    <input type="submit" name="saveSubject" class="buttonsub" <?php echo $disTest; ?> value="Save Subject">
                       </div>
                    <div class="clear"></div>
					</div>
					<div class="sessionData" <?php echo $disDivCss; ?>>
                    <div class="custtd_left">
                        <h2><strong>Manage Sessions:-</strong></h2>
                    </div>
						<div class="txtfield ">
						<div class="sessionboxSub" style="width:110px;">
						<h3>Session Name<span class="redstar">*</span></h3>
							<input type="text" class="inp_txt_session required" <?php echo $disSession; ?> id="txtSessionName" maxlength="50" style="width:94px;" name="txtSessionName" value="">
						</div>
						<!--<div class="sessionboxSub" style="width:110px;">
						<h3>Order Number<span class="redstar">*</span></h3>
							<input type="text" class="inp_txt_session number required" <?php //echo $disSession; ?> id="txtOrderNum" maxlength="10" style="width:94px;" name="txtOrderNum" value="">
						</div>-->
						<div class="sessionboxSub" style="width:110px;">
						<h3>Duration(Hr)</h3>
							<select name="duration" id="duration" class="activity_row_chk" <?php echo $disSession; ?> style="height:27px; width:106px">';
							<option value="">--Select--</option>';
							<option value="15">00:15</option>
							<option value="30">00:30</option>
							<option value="45">00:45</option>
							<option value="60" selected="selected">01:00</option>
							<option value="75">01:15</option>
							<option value="90">01:30</option>
							<option value="105">01:45</option>
							<option value="120">02:00</option>
							<option value="135">02:15</option>
							<option value="150">02:30</option>
							<option value="165">02:45</option>
							<option value="180">03:00</option>
							<option value="195">03:15</option>
							<option value="210">03:30</option>
							<option value="225">03:45</option>
							<option value="240">04:00</option>
							<option value="255">04:15</option>
							<option value="270">04:30</option>
							<option value="285">04:45</option>
							<option value="300">05:00</option>
							<option value="315">05:15</option>
							<option value="330">05:30</option>
							<option value="345">05:45</option>
							<option value="300">06:00</option>
							<option value="315">06:15</option>
							<option value="330">06:30</option>
							<option value="345">06:45</option>
							<option value="300">07:00</option>
							<option value="315">07:15</option>
							<option value="330">07:30</option>
							<option value="345">07:45</option>
							<option value="345">08:00</option>
							</select>
						</div>
						<div class="sessionboxSub" style="width:110px;">
						<h3>Teacher</h3>
						<select id="slctTeacher" name="slctTeacher" class="required" <?php echo $disSession; ?> style="width:106px; height:27px;">
						<option value="">--Select--</option>';
                        <?php
							while($row = $rel_teacher->fetch_assoc()){
								echo '<option value="'.$row['id'].'">'.$row['teacher_name'].' ('.$row['email'].')</option>';
							}
						?>
                        </select>
						</div>
						<div class="sessionboxSub" style="width:110px;">
						<h3>Room</h3>
							<select name="room_id" id="room_id" class="activity_row_chk" <?php echo $disSession; ?> style="height:27px; width:106px;">
							<option value="">--Select--</option>
							<?php echo $room_dropDwn; ?>
							</select>
						</div>
						<div class="sessionboxSub" style="width:110px;">
						<h3>Date</h3>
							 <input type="text" size="12" id="subSessDate" <?php echo $disSession; ?> style="height:23px; width:102px;" readonly />
						</div>
						<div class="sessionboxSub" style="width:110px;">
						<h3>Start Time</h3>
							<select name="tslot_id" id="tslot_id" class="activity_row_chk" <?php echo $disSession; ?> style="height:27px; width:106px">';
							<option value="">--Select--</option>';
							<?php echo $tslot_dropDwn; ?>
							</select>
						</div>


						<div class="sessionboxSub" style="width:110px;">
						<h3>Case No</h3>
							 <input type="text" class="inp_txt_session alphanumeric" <?php echo $disSession; ?> id="txtCaseNo" maxlength="10" style="width:94px;" name="txtCaseNo" value="">
						</div>
						<div class="sessionboxSub"style="width:152px;">
						<h3>Technical Notes</h3>
							 <textarea style="height:40px; width:135px" class="inp_txt_session alphanumeric" <?php echo $disSession; ?> id="txtareatechnicalNotes" cols="20" rows="2" name="txtTechnicalNotes"></textarea>
						</div>
						<div class="sessionboxSub" style="width:154px;" >
						<h3>Description</h3>
							 <textarea style="height:40px;" class="inp_txt_session alphanumeric" <?php echo $disSession; ?> id="txtareaSessionDesp" cols="20" rows="2" name="txtSessionDesp"></textarea>
						</div>

					   <div class="sessionboxSub addbtnSession">
						<input type="button" name="btnCheckAvail" id="btnCheckAvail" class="btnSession buttonsub" <?php echo $disSession; ?> value="Check Availability" style="height:30px;">
						<span style="display:none" name="showstatusAvail" id="showstatusAvail" ><img alt="OK" src="images/ok.gif" /></span>
						<span style="display:none" name="showstatusNoAvail" id="showstatusNoAvail" ><img alt="OK" src="images/error.gif" /></span>
						<!--<input style="display:none" type="button" name="showstatus" id="showstatus" class="btnSession buttonsub" value="">-->
                       </div>
					    <div class="sessionboxSub addbtnSession">
					    <input type="button" name="btnAddMore" <?php echo $disSession; ?> id="btnAddNewSess" class="btnSession buttonsub" value="Add Session" style="width: 115px; height:30px; margin-bottom: 1px;">

					   </div></div>
					<div class="clear"></div>
					</div>
					<div class="divSession" style="text-align:left">
					<?php
					if($subjectId!=""){
						$x=0;
						$sessionHtml='';
						$subj_session_query="select subs.id as sessionID, subs.*, ta.id as activityId,  ta.*, tea.teacher_name, room.room_name, ts.start_time from  subject_session as subs LEFT JOIN teacher_activity as ta ON subs.id = ta.session_id LEFT JOIN teacher as tea ON ta.teacher_id = tea.id LEFT JOIN room ON ta.room_id = room.id LEFT JOIN timeslot as ts ON ta.timeslot_id = ts.id WHERE subs.subject_id='".$subjectId."' order by subs.order_number ASC";
						$subj_session_result= mysqli_query($db, $subj_session_query);
						while($subj_session_data = mysqli_fetch_assoc($subj_session_result)){
						$x++;
						$actID = 0;
						if($subj_session_data['activityId']!=""){
							$actID = $subj_session_data['activityId'];
						}
						//setting default duration as 15 min for the activities
						$duration = $subj_session_data['duration'];
						if($duration==""){$duration = "15"; }
						if($x==1){
							$sessionHtml.='<div class="sessionList">
   							<table id="datatables" class="display datatableSession">
       						  <thead>
          					   <tr>
								<th>Sr. No.</th>
          						<th >Session Name</th>
								<th >Duration</th>
								<th >Teacher</th>
								<th >Room</th>
								<th >Date</th>
								<th >Start Time</th>
          						<th >Case No</th>
								<th >Technical Notes</th>
								<th >Description</th>
								<th >Remove</th>
							   </tr>
       					      </thead>
       					      <tbody>';}
						 	$sessionHtml.='<tr id="'.$x.'" >
           						<td class="number">'.$x.'</td>
	   							<td>'.$subj_session_data['session_name'].'</td>
								<td>'.date('H:i', mktime(0,$duration)).'</td>
								<td>'.$subj_session_data['teacher_name'].'</td>
								<td>'.$subj_session_data['room_name'].'</td>
								<td>'.$subj_session_data['act_date'].'</td>
								<td>'.$subj_session_data['start_time'].'</td>
								<td>'.$subj_session_data['case_number'].'</td>
	   							<td>'.$subj_session_data['technical_notes'].'</td>
								<td>'.$subj_session_data['description'].'</td>';
							$sessionHtml.='<td style="display:none"><input type="hidden" name="sessionName[]" id="sessionName'.$x.'"  value="'.$subj_session_data['session_name'].'"/>

								<input type="hidden" name="sessionDesc[]" id="sessionDesc'.$x.'"  value="'.$subj_session_data['description'].'"/>
								<input type="hidden" name="sessionCaseNo[]" id="sessionCaseNo'.$x.'"  value="'.$subj_session_data['case_number'].'"/>
								<input type="hidden" name="sessionTechNote[]" id="sessionTechNote'.$x.'"  value="'.$subj_session_data['technical_notes'].'"/>
								<input type="hidden" name="sessionOrder[]" id="sessionOrder'.$x.'"  value="'.$subj_session_data['order_number'].'"/>
								<input type="hidden" name="sessionRowId[]" id="sessionRowId'.$x.'"  value="'.$subj_session_data['id'].'"/>
								<input type="hidden" name="sessionSubId[]" id="sessionSubId'.$x.'"  value="'.$subj_session_data['sessionID'].'"/></td>
								<td id='.$x.'><a class="remove_field" onclick="removeSession('.$actID.','.$subj_session_data['sessionID'].', '.$subjectId.', '.$x.');">Remove</a></td></tr>';
       					}
					$sessionHtml.='<input type="hidden" name="maxSessionListVal" id="maxSessionListVal"  value="'.$x.'"/>';
					$sessionHtml.='<input type="hidden" name="EditMaxExceptnListVal" id="EditMaxExceptnListVal"  value="'.$x.'"/>';
					$sessionHtml.='<input type="hidden" name="sessionIDVal" id="sessionIDVal"  value=""/>';
					$sessionHtml.='<input type="hidden" name="sessionIDSubject" id="sessionIDSubject"  value=""/>';
					$sessionHtml.='<input type="hidden" name="serialNum" id="serialNum"  value=""/>';
					$sessionHtml.='<input type="hidden" name="sessionIDSArr" id="sessionIDSArr"  value=""/>';
					$sessionHtml.='</tbody></table></div>';
					echo $sessionHtml;?>
					<script type="text/javascript">sortingSession();</script>
					<?php }?>
					</div>
					<div class="clear"></div>
                    <div class="custtd_left">
                     </div>
						<div class="txtfield">
						 <?php //if($subjectName==""){ ?>
							<!--<input type="submit" name="btnAddSubject" class="buttonsub" value="Save Session">-->
						 <?php //} ?>
						</div>
                    <div class="txtfield">
						<input type="button" name="btnCancel" class="buttonsub" value="<?php echo $buttonName = ($subjectName!="") ? "Done":"Cancel" ?>" onclick="location.href = 'subject_view.php';">
                    </div>
                </div>
            </form>
        </div>
        <div class="clear"></div>
	</div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>
