<?php
class Subjects extends Base {
    public function __construct(){
   		 parent::__construct();
   	}
	/*function for adding Subject*/
	/*public function addSubject() {
			$sessionName = (isset($_POST['sessionName'])) ? ($_POST['sessionName']) : '';
			$sessionNameArr=$this->formingArray($sessionName);
			$orderNumber = (isset($_POST['sessionOrder'])) ? ($_POST['sessionOrder']) : '';
			$orderNumberArr=$this->formingArray($orderNumber);
			$sessionDesp = (isset($_POST['sessionDesc'])) ? ($_POST['sessionDesc']) : '';
			$sessionDespArr=$this->formingArray($sessionDesp);
			$txtCaseNum = (isset($_POST['txtCaseNum'])) ? ($_POST['txtCaseNum']) : '';
			$txtCaseNumArr=$this->formingArray($txtCaseNum);
			$txtTechNotes = (isset($_POST['txtTechNotes'])) ? ($_POST['txtTechNotes']) : '';
			$txtTechNotesArr=$this->formingArray($txtTechNotes);
			//check if the subject code already exists
			$subject_query="select subject_name, subject_code from  subject where subject_code='".Base::cleanText($_POST['txtSubjCode'])."'";
			$q_res = mysqli_query($this->conn, $subject_query);
			$dataAll = mysqli_fetch_assoc($q_res);
			if(count($dataAll)>0){
				$message="Subject code already exists.";
				$_SESSION['error_msg'] = $message;
				return 0;
			}else{
				//add the new subject
				$currentDateTime = date("Y-m-d H:i:s");
				//fectching area id
				$area_id=$this->getAreaId();
				//fectching program id
				//$program_id=$this->getProgramId();
				$program_name=$_POST['slctProgram'];
				$program_Val=explode('#',$program_name);
				$program_year_id=$program_Val[0];
				$cycle_no=$_POST['slctCycle'];
				$program_id=$program_Val[1];
				//inserting values
				$SQL = "INSERT INTO subject VALUES ('', '".$area_id."', '".$program_year_id."', '".$cycle_no."', '".$_POST['txtSubjName']."','".$_POST['txtSubjCode']."','".$currentDateTime."', '".$currentDateTime."')";
				$result = $this->conn->query($SQL);
				$last_ins_id = $this->conn->insert_id;
				if($last_ins_id) {
					if($last_ins_id!=""){
					$j=0;
					if($sessionName!=""){
					foreach ($sessionNameArr as $key => $value) {
						$sessionNameval=$value;
						//inserting subject session values
						if($seesion_result = mysqli_query($this->conn, "INSERT INTO subject_session VALUES ('', '".$last_ins_id."', '".$sessionNameval."','".$orderNumberArr[$j]."','".$sessionDespArr[$j]."','".$txtCaseNumArr[$j]."','".$txtTechNotesArr[$j]."','".$currentDateTime."', '".$currentDateTime."')")){
						$j++;
						if($j==count($sessionNameArr)){
						 $message="New subject has been added successfully with session";
						 $_SESSION['succ_msg'] = $message;
						 return 1;
						 }
						}else{
						 $message="Cannot add the subject's sesssion";
						 $_SESSION['succ_msg'] = $message;
						 return 0;
						}
					  }
					}else{
						$message="New subject has been added successfully";
						$_SESSION['succ_msg'] = $message;
						return 1;
					}
				  }
				}else{
				    $message="Cannot add the subject";
					$_SESSION['error_msg'] = $message;
					return 0;
				  }
		}
	}*/
	/*function for adding Subject*/
	public function addSubject() {

			//check if the subject code already exists
			$subject_query="select subject_name, subject_code from  subject where subject_code='".Base::cleanText($_POST['txtSubjCode'])."'";
			$q_res = mysqli_query($this->conn, $subject_query);
			$dataAll = mysqli_fetch_assoc($q_res);
			if(count($dataAll)>0){
				$message="Subject code already exists.";
				$_SESSION['error_msg'] = $message;
				return 0;
			}else{
				//add the new subject
				$currentDateTime = date("Y-m-d H:i:s");
				//fectching area id
				//$area_id=$this->getAreaId();
				$area_id=$_POST['slctArea'];
				//fectching program id
				//$program_id=$this->getProgramId();
				//$program_name=$_POST['slctProgram'];
				//$program_Val=explode('#',$program_name);
				$program_year_id = $_POST['slctProgram'];
				$cycle_no=$_POST['slctCycle'];
				//$program_id=$program_Val[1];
				//inserting values
				$SQL = "INSERT INTO subject VALUES ('', '".$area_id."', '".$program_year_id."', '".$cycle_no."', '".$_POST['txtSubjName']."','".$_POST['txtSubjCode']."','".$currentDateTime."', '".$currentDateTime."')";
				$result = $this->conn->query($SQL);
				$last_ins_id = $this->conn->insert_id;
				if($last_ins_id) {
					$message="Subject has been added successfully, You can manage sessions for this subject now.";
					$_SESSION['succ_msg'] = $message;
					//return back data to the form
					echo "<html><head></head><body>";
					echo "<form name='formsubject' method='post' action='subjects.php?edit=".base64_encode($last_ins_id)."'>";
					reset($_POST);
					while(list($iname,$ival) = each($_POST)) {
						echo "<input type='hidden' name='$iname' value='$ival'>";
					}
					echo "</form>";
					echo "</body></html>";
					echo"<script language='JavaScript'>function submit_back(){ window.document.formsubject.submit();}submit_back();</script>";
				}else{
				    $message="Cannot add the subject, Please try again";
					$_SESSION['error_msg'] = $message;
					return 0;
				  }
		}
	}


	/*function for listing Subject*/
	public function viewSubject()
	{
		$subject_query="select * from subject order by date_update DESC";
		$q_res = mysqli_query($this->conn, $subject_query);
		return $q_res;
	}
	/*function for fetch data using area ID*/
	public function getDataBySubjectID($id) {
		$subject_query="select * from subject where id='".$id."' limit 1";
		$q_res = mysqli_query($this->conn, $subject_query);
		return $q_res;
	}
	public function updateSubject() {
	        //check if the subject code already exists
			$subject_query="select subject_name, subject_code from subject where subject_code='".Base::cleanText($_POST['txtSubjCode'])."' and id !='".$_POST['subjectId']."'";
			$q_res = mysqli_query($this->conn, $subject_query);
			$dataAll = mysqli_fetch_assoc($q_res);
			if(count($dataAll)>0){
				$message="Subject code already exists.";
				$_SESSION['error_msg'] = $message;
				return 0;
			}else{
			//get area id
			$area_id=$this->getAreaId();
			//get program id
			$program_name=$_POST['slctProgram'];
			$program_Val=explode('#',$program_name);
			$program_year_id=$program_Val[0];
			$cycle_no=$_POST['slctCycle'];
			$program_id=$program_Val[1];
			$sessionName = (isset($_POST['sessionName'])) ? ($_POST['sessionName']) : '';
			$sessionNameArr=$this->formingArray($sessionName);
			$orderNumber = (isset($_POST['sessionOrder'])) ? ($_POST['sessionOrder']) : '';
			$orderNumberArr=$this->formingArray($orderNumber);
			$sessionDesp = (isset($_POST['sessionDesc'])) ? ($_POST['sessionDesc']) : '';
			$sessionDespArr=$this->formingArray($sessionDesp);

			$txtCaseNum = (isset($_POST['sessionCaseNo'])) ? ($_POST['sessionCaseNo']) : '';
			$txtCaseNumArr=$this->formingArray($txtCaseNum);
			$txtTechNotes = (isset($_POST['sessionTechNote'])) ? ($_POST['sessionTechNote']) : '';
			$txtTechNotesArr=$this->formingArray($txtTechNotes);

			$sessionRowId = (isset($_POST['sessionRowId'])) ? ($_POST['sessionRowId']) : '';
			$sessionRowIdArr=$this->formingArray($sessionRowId);
			//updating subject values
			if ($result = mysqli_query($this->conn, "Update subject  Set area_id = '".$area_id."', program_year_id = '".$program_year_id."', cycle_no = '".$cycle_no."', subject_name= '".$_POST['txtSubjName']."' , subject_code= '".$_POST['txtSubjCode']."',date_update = '".date("Y-m-d H:i:s")."' where id='".$_POST['subjectId']."'")) {
			        if($_POST['subjectId']!=""){
					$j=0;
					$k=0;
					//updating session values
				if($sessionName!=""){
					foreach ($sessionNameArr as $key => $value) {
						$sessionNameval=$value;
						if($j!=count($sessionRowIdArr)){
							if($seesion_result = mysqli_query($this->conn, "Update  subject_session  Set session_name = '".$sessionNameval."', order_number = '".$orderNumberArr[$j]."', description= '".$sessionDespArr[$j]."', case_number= '".$txtCaseNumArr[$j]."', technical_notes= '".$txtTechNotesArr[$j]."',date_update = '".date("Y-m-d H:i:s")."' where id='".$sessionRowIdArr[$j]."'")){
								$j++;
								$k=$j;
								}
								if($j==count($sessionRowIdArr) && ($_POST['maxSessionListVal']==$_POST['EditMaxExceptnListVal'])){
									$message="Subject has been updated successfully ";
						   			$_SESSION['succ_msg'] = $message;
									return 1;
								}
					 	}else{
						   if($seesion_result = mysqli_query($this->conn, "INSERT INTO  subject_session VALUES ('', '".$_POST['subjectId']."', '".$sessionNameval."','".$orderNumberArr[$k]."','".$sessionDespArr[$k]."','".$txtCaseNumArr[$k]."','".$txtTechNotesArr[$k]."','".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')")){
						     $k++;
							 if($k == (count($sessionNameArr))){
						   			$message="Subject has been updated successfully with session";
						   			$_SESSION['succ_msg'] = $message;
									return 1;
							 }
						   }else{
								$message="Subject's session cannot be added";
						   		$_SESSION['error_msg'] = $message;
								return 0;
							}
						}
				    }
				 }
				}else{
					$message="Please enter the session name";
					$_SESSION['error_msg'] = $message;die;
					return 0;
				}
		}else{
			$message="Cannot update the subject";
			$_SESSION['error_msg'] = $message;
			return 0;
		}
	}
  }
	public function getProgramId(){
		$program_query="select id from program where program_name='".$_POST['slctProgram']."'";
		$program_result= mysqli_query($this->conn, $program_query);
		$program_data = mysqli_fetch_assoc($program_result);
		if(count($program_data)>0){
			$program_id=$program_data['id'];
			return $program_id;
		}else{
		   echo  $message="Program does not exists.";
		   $_SESSION['error_msg'] = $message;
		}
	}
	public function getAreaId(){
		$area_query="select id from area where area_code='".$_POST['slctArea']."'";
		$area_result= mysqli_query($this->conn, $area_query);
		$area_data = mysqli_fetch_assoc($area_result);
		if(count($area_data)>0){
			$area_id=$area_data['id'];
			return $area_id;
		}else{
			$message="Area does not exists.";
			$_SESSION['error_msg'] = $message;
		}
	}
	public function formingArray($dataArr){
		 $newArr = array();
			foreach ($dataArr as $key => $val) {
					if (trim($val) <> "") {
					 $newArr[] = trim($val);
					}
			}
  		return $newArr;
	}

 /*function for all subjects for add form*/
 	public function getSubjects(){
		$sql="SELECT id,subject_name FROM subject ORDER BY subject_name";
		$result = $this->conn->query($sql);
		return $result;
   }
 /*function for all sessions for add form*/
 	public function getSessions(){
		$sql="SELECT id,session_name FROM subject_session ORDER BY session_name";
		$result = $this->conn->query($sql);
		return $result;
   }

    /*get subject name by id*/
	public function getSubjectByID($id){
		$sql="SELECT id,subject_name FROM subject WHERE id='".$id."'";
		$result = $this->conn->query($sql);
		if(!$result->num_rows){
			return '';
		}else{
		  $row = $result->fetch_assoc();
		  return $row['subject_name'];
		}
   }
   /*get session name by id*/
   public function getSessionByID($id){
   		$sql="SELECT id,session_name FROM subject_session WHERE id='".$id."'";
   		$result = $this->conn->query($sql);
   		if(!$result->num_rows){
   			return 'N/A';
   		}else{
   		  $row = $result->fetch_assoc();
   		  return (trim($row['session_name'])<>"") ? $row['session_name'] : "N/A";
   		}
   }
   public function getProgramYearDetail($slctProgram){
        $subject_program=explode('#',$slctProgram);
		$program_year_id=$subject_program['0'];
	    $program_query="select * from  program_years where id='".$program_year_id."'";
	    $program_result= mysqli_query($this->conn, $program_query);
	    $program_data = mysqli_fetch_assoc($program_result);
		$program_detail=$program_data['name'].' '.$program_data['start_year'].' '.$program_data['end_year'];
		return $program_detail;
	}
	public function getWebSubjectDetail($subject_id='')
	{
		$row=$rowmainArr=$newArr=array();
		$result =  $this->conn->query("SELECT we.cal_name, we.cal_description, we.cal_date, we.cal_time, we.cal_id, we.cal_ext_for_id, we.cal_priority, we.cal_access, we.cal_duration, weu.cal_status, we.cal_create_by, weu.cal_login, we.cal_type, we.cal_location, we.cal_url, we.cal_due_date, we.cal_due_time, weu.cal_percent, we.cal_mod_date, we.cal_mod_time FROM webcal_entry we,webcal_entry_user weu WHERE we.cal_id = weu.cal_id and we.subject_id='".$subject_id."' ORDER BY we.cal_time, we.cal_name ");
		if($result->num_rows){
			while ($rows =$result->fetch_assoc()){
					$row[]=$rows;
			}
		}
		if(count($row)>0){
		   $rowNewArr=array(array());
		   for($i=0;$i<count($row);$i++){
		    $j=0;
		    foreach($row[$i] as $key=>$val){
			  $rowNewArr[$i][$j]=$val;
			  $j++;
		   	}
		  }
		  return $rowNewArr;
		}
	}
    /*function for get cycle from program id*/
 	public function getCycleByProgId($progId){
		$data="";
		if($progId!=""){
				$cycle_query="select no_of_cycle from cycle where program_year_id='".$progId."' limit 1";
				$qry = $this->conn->query($cycle_query);
				$row = $qry->fetch_assoc();
				$data = $row['no_of_cycle'];
		}
		return $data;
   }
}