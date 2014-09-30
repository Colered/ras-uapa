<?php include('header.php');
$obj = new Classroom_Availability();
$classroomAvailData = $obj->getClassroomAvailRule();
$roomTypedata=$obj->getRoomType();
/*$timeslotData=$obj->getTimeslot();
$options = "";
while($data = $timeslotData->fetch_assoc()){
	$options .= '<option value="'.$data['id'].'">'.$data['timeslot_range'].'</option>';
}*/
$options = '<option value="08:00-08:15">08:00-08:15</option>
			<option value="08:15-08:30">08:15-08:30</option>
			<option value="08:30-08:45">08:30-08:45</option>
			<option value="08:45-09:00">08:45-09:00</option>
			<option value="09:00-09:15">09:00-09:15</option>
			<option value="09:15-09:30">09:15-09:30</option>
			<option value="09:30-09:45">09:30-09:45</option>
			<option value="09:45-10:00">09:45-10:00</option>
			<option value="10:00-10:15">10:00-10:15</option>
			<option value="10:15-10:30">10:15-10:30</option>
			<option value="10:30-10:45">10:30-10:45</option>
			<option value="10:45-11:00">10:45-11:00</option>
			<option value="11:00-11:15">11:00-11:15</option>
			<option value="11:15-11:30">11:15-11:30</option>
			<option value="11:30-11:45">11:30-11:45</option>
			<option value="11:45-12:00">11:45-12:00</option>
			<option value="12:00-12:15">12:00-12:15</option>
			<option value="12:15-12:30">12:15-12:30</option>
			<option value="12:30-12:45">12:30-12:45</option>
			<option value="12:45-13:00">12:45-13:00</option>
			<option value="13:00-13:15">13:00-13:15</option>
			<option value="13:15-13:30">13:15-13:30</option>
			<option value="13:30-13:45">13:30-13:45</option>
			<option value="13:45-14:00">13:45-14:00</option>
			<option value="14:00-14:15">14:00-14:15</option>
			<option value="14:15-14:30">14:15-14:30</option>
			<option value="14:30-14:45">14:30-14:45</option>
			<option value="14:45-15:00">14:45-15:00</option>
			<option value="15:00-15:15">15:00-15:15</option>
			<option value="15:15-15:30">15:15-15:30</option>
			<option value="15:30-15:45">15:30-15:45</option>
			<option value="15:45-16:00">15:45-16:00</option>
			<option value="16:00-16:15">16:00-16:15</option>
			<option value="16:15-16:30">16:15-16:30</option>
			<option value="16:30-16:45">16:30-16:45</option>
			<option value="16:45-17:00">16:45-17:00</option>
			<option value="17:00-17:15">17:00-17:15</option>
			<option value="17:15-17:30">17:15-17:30</option>
			<option value="17:30-17:45">17:30-17:45</option>
			<option value="17:45-18:00">17:45-18:00</option>
			<option value="18:00-18:15">18:00-18:15</option>
			<option value="18:15-18:30">18:15-18:30</option>
			<option value="18:30-18:45">18:30-18:45</option>
			<option value="18:45-19:00">18:45-19:00</option>
			<option value="19:00-19:15">19:00-19:15</option>
			<option value="19:15-19:30">19:15-19:30</option>
			<option value="19:30-19:45">19:30-19:45</option>
			<option value="19:45-20:00">19:45-20:00</option>';
$name="";
$classRmAvailId="";
$roomId="";
$roomTypeId="";
$mappedruleids = array();
if(isset($_GET['rid']) && $_GET['rid']!=""){
	 $roomId = $_GET['rid'];
	 $resultRoomTypeId=$obj->getRoomTypeById($roomId);
	 $roomTypeIdOrigin=$resultRoomTypeId->fetch_row();
	 $roomTypeId=$roomTypeIdOrigin[0];
	 $mappedruleids = $obj->getRuleIdsForRoom($roomId);
}
?>
<div id="content">
    <div id="main">
        <div class="full_w">
            <div class="h_title">Classroom Availability</div>
			<form action="postdata.php" method="post" class="form-align" name="classroomAvailabilityForm" id="classroomAvailabilityForm">
				<input type="hidden" name="form_action" value="addEditClassAvailability" />
				<input type="hidden" id="classRmAvailId" name="classRmAvailId" value="<?php echo $classRmAvailId; ?>" />
				<input type="hidden" id="roomId" name="roomId" value="<?php echo $roomId; ?>" />
                <div class="custtable_left">
				<div class="custtd_left green">
					<?php if(isset($_SESSION['succ_msg']))
						echo $_SESSION['succ_msg']; unset($_SESSION['succ_msg']);?>
				</div>
				<div class="clear"></div>
                <div class="custtable_left">
                    <div class="custtd_left">
                        <h2>Room Type <span class="redstar">*</span></h2>
                    </div>
					<div class="txtfield">
						<select id="slctRmType" name="slctRmType[]"  class="selectMultiple inp_txt required" >
					 		  <option value="">--Select Room Type--</option>
                              <?php //if($roomTypedata!="0"){
								while($roomTypedataResult = $roomTypedata->fetch_assoc()){ ?>
									<option value="<?php echo $roomTypedataResult['id'].'#'.$roomTypedataResult['room_type']?>"  <?php if($roomTypeId == $roomTypedataResult['id']){echo "selected"; } ?> ><?php echo $roomTypedataResult['room_type'];?></option>
							<?php }//}else{ ?>
								<!--<option value="">No room type available</option>-->
                            <?php //} ?>
                        </select>
					</div>
                    <div class="clear"></div>
				    <div class="custtd_left">
                        <h2>Room Name <span class="redstar">*</span></h2>
                    </div>
					<?php if(isset($roomId) && $roomId!=''){?>
						<script type="text/javascript">
							getRoomByType('<?php echo $roomId;?>');
						</script>
					<?php } ?>
                    <div class="txtfield">
                         <select id="slctRmName" name="slctRmName" class="select1 inp_txt required" onchange="changeRoomData()" >
						  	<option value="">--Select Room--</option>
                         </select>
                    </div>
					<div class="createScheduleBlock">
					 <div class="custtd_left">
                        <span style="font-size:14px"><b>Create A New Rule(optional):</b></span>
                    </div>
					 <div class="clear"></div>
					 <div class="custtd_left">
                        <h2>Schedule Name <span class="redstar">*</span></h2>
                    </div>
                     <div class="txtfield">
                        <input type="text" class="inp_txt alphanumeric" id="txtSchd" maxlength="50" name="txtSchd">
                    </div>
                     <div class="clear"></div>
                     <div class="custtd_left">
                        <h2>Time Interval <span class="redstar">*</span></h2>
                    </div>
                     <div class="txtfield">
                        From:<input type="text" size="12" id="fromTmDuratn" />
                        To:<input type="text" size="12" id="toTmDuratn" />
                    </div>
                     <div class="clear"></div>
                     <div class="custtd_left">
                        <h2>Days and Timeslot<span class="redstar">*</span></h2>
                    </div>
                     <div class="txtfield" >
					    <div class="tmSlot">
                        <input type="checkbox" id="Mon" name="day[]"  value="0" class="days"/><span class="dayName"> Mon </span>
						<select id="ts-avail-day-0" name="Mon[]" class="slctTs" multiple="multiple">
						<?php echo $options; ?>
                        </select>
						</div>
						<div class="tmSlot">
                        <input type="checkbox" id="Tue" name="day[]"  value="1" class="days"/><span class="dayName"> Tue </span>
						<select id="ts-avail-day-1" name="Tue[]" class="slctTs" multiple="multiple">
                           <?php echo $options; ?>
                        </select>
						</div>
						<div class="tmSlot">
                        <input type="checkbox" id="Wed" name="day[]"  value="2" class="days"/><span class="dayName"> Wed </span>
						 <select id="ts-avail-day-2" name="Wed[]" class="slctTs" multiple="multiple">
                          <?php echo $options; ?>
                        </select>
						</div>
						<div class="tmSlot">
                        <input type="checkbox" id="Thu" name="day[]"  value="3" class="days"/><span class="dayName"> Thu </span>
						 <select id="ts-avail-day-3" name="Thu[]" class="slctTs" multiple="multiple">
                         <?php echo $options; ?>
                        </select>
						</div>
						<div class="tmSlot">
                        <input type="checkbox" id="Fri" name="day[]"  value="4" class="days"/><span class="dayName"> Fri </span>
						 <select id="ts-avail-day-4" name="Fri[]" class="slctTs" multiple="multiple">
                          <?php echo $options; ?>
                        </select>
						</div>
						<div class="tmSlot">
                        <input type="checkbox" id="Sat" name="day[]"  value="5" class="days"/><span class="dayName"> Sat </span>
						 <select id="ts-avail-day-5" name="Sat[]" class="slctTs" multiple="multiple">
                          <?php echo $options; ?>
                        </select>
						</div>
                    </div>
					 <div class="custtable_left div-arrow-img" style="cursor:pointer">
					  <input type="button" name="saveRule" class="buttonsub btnCreateRule" value="Create Rule">
                     </div>
					 <div class="clear"></div>
                   </div>
                </div>

			<div class="clear"></div>
			<div class="scheduleBlock">
				<div >
					<span style="font-size:14px"><b>Select A Rule For Classroom Availability:</b></span>
				</div>
				<div >
                    <ul id="rules" name="rules" class="rule">
                       <table width="1200" border="0" >
					   <?php
					    $count = 0;
					   	while($data = $classroomAvailData->fetch_assoc()){
							if($count%6 == 0){ echo "<tr>"; }?>
								<td class="sched-data"><div style="width:200px;"><li class="main-title"><input type="checkbox" name="ckbruleVal[]" value="<?php echo $data['id']; ?>" <?php if(in_array($data['id'], $mappedruleids)) { echo "checked"; } ?>  /><b>&nbsp;<?php echo $data['rule_name']; ?></b></li>
								<span>From <?php echo $data['start_date']; ?> to <?php echo $data['end_date']; ?></span>
								<ul class="listing">
									<?php //get the day and timeslot
									$dayData = $obj->getClassroomAvailDay($data['id']);
									
									while($ddata = $dayData->fetch_assoc()){
										$timeslotData = $obj->getClassroomAvailTimeslot($ddata['timeslot_id']);?>
										<li><?php
											if($ddata['day']==0){echo $day_name="Mon ";}
											if($ddata['day']==1){echo $day_name="Tue ";}
											if($ddata['day']==2){echo $day_name="Wed ";}
											if($ddata['day']==3){echo $day_name="Thu ";}
											if($ddata['day']==4){echo $day_name="Fri ";}
											if($ddata['day']==5){echo $day_name="Sat ";}											
											 echo $ddata['timeslot_id'].",";
											  ?>
										</li>
									<?php } ?>
								</ul>
								</div>
								</td>
						<?php $count++; } ?>
						</tr>
</table>
                    </ul>
                </div>
			</div>
			<div class="clear"></div>
                    <div class="custtd_left">
                        <h2>Add Exception</h2>
                    </div>
                    <div class="txtfield">
                        <input type="text" size="12" id="exceptnClsrmAval" />
                    </div>
					<div class="addbtnException">
					    <input type="button" name="btnAddMore" class="btnclsrmException" value="Add">
                     </div>
                    <div class="clear"></div>
					<div class="custtd_left">
                    </div>
					<div class="divException">
						<?php
					if($roomId!=""){
						$x=0;
						$sessionHtml='';
						$subj_session_query="select * from  classroom_availability_exception where room_id='".$roomId."'";
						$subj_session_result= mysqli_query($db, $subj_session_query);
						while($subj_session_data = mysqli_fetch_assoc($subj_session_result)){
						$x++;
						if($x==1){
							$sessionHtml.='<div class="sessionList">
   							<table id="datatables" class="exceptionTbl">
       						  <thead>
          					   <tr>
								<th>Sr. No.</th>
          						<th >Exception Date</th>
          						<th >Remove</th>
							   </tr>
       					      </thead>
       					      <tbody>';}
						 	$sessionHtml.='<tr>
           						<td>'.$x.'</td>
	   							<td>'.$subj_session_data['exception_date'].'</td>
	   							';
							$sessionHtml.='<td style="display:none">
							<input type="hidden" name="exceptionDate[]" id="sessionName'.$x.'"  value="'.$subj_session_data['exception_date'].'"/>
							<input type="hidden" name="sessionRowId[]" id="sessionRowId'.$x.'"  value="'.$subj_session_data['id'].'"/></td>
							<td id='.$subj_session_data['id'].'><a class="remove_field" onclick="removeClassException('.$subj_session_data['id'].', 0);">Remove</a></td></tr>';
       					}
					$sessionHtml.='<input type="hidden" name="maxSessionListVal" id="maxSessionListVal"  value="'.$x.'"/>';
					$sessionHtml.='</tbody></table></div>';
					echo $sessionHtml;
				 }?>

					</div>
					<div class="clear"></div>
					<div class="txtfield">
                        <input type="submit" name="btnSave" class="buttonsub" value="Save">
                    </div>
                    <div class="txtfield">
                        <input type="submit" name="btnCancel" class="buttonsub" value="Cancel">
                    </div>
			 </form>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php include('footer.php'); ?>

