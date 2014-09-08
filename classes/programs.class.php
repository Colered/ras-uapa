<?php
class Programs extends Base {
    public function __construct(){
   		 parent::__construct();
   	}
   	/*function for add program*/
	public function addProgram()
	{
     	$txtPrgmName = Base::cleanText($_POST['txtPrgmName']);
		$slctPrgmType = trim($_POST['slctPrgmType']);
		$prog_from_date = date("Y-m-d h:i:s", strtotime($_POST['prog_from_date']));
		$prog_to_date = date("Y-m-d h:i:s", strtotime($_POST['prog_to_date']));
		$slctNumcycle = trim($_POST['slctNumcycle']);

		$result =  $this->conn->query("SELECT program_name FROM program WHERE program_name='".$txtPrgmName."'");
		$row_cnt = $result->num_rows;
		if($row_cnt > 0){
			$this->conn->close();
			$message="'".$txtPrgmName."' program already exist in database.";
			$_SESSION['error_msg'] = $message;
			return 0;
		}

		$sql = "INSERT INTO program (program_name, program_type, start_date, end_date , date_add) VALUES ('".$txtPrgmName."', '".$slctPrgmType."', '".$prog_from_date."', '".$prog_to_date."', now())";
		$rel = $this->conn->query($sql);
		$last_ins_id = $this->conn->insert_id;
		if(!$rel){
			$_SESSION['error_msg'] = $this->conn->error;
			return 0;
		}else{
			//INSERT PROGRAM YEARS  DATA
			for($j=1; $j<=$slctPrgmType; $j++){
				$progName = $txtPrgmName.'-'.$j;
				$start_year = date("Y", strtotime($prog_from_date));
				$start_year = $start_year + ($j-1);
				$end_year = $start_year + $j;
				$sql = "INSERT INTO program_years (program_id, name, start_year, end_year) VALUES ('".$last_ins_id."', '".$progName."', '".$start_year."', '".$end_year."')";
				$rel = $this->conn->query($sql);
				$last_yr_id = $this->conn->insert_id;
				//INSERT CYCLES DATA
				for($i=1; $i<=$slctNumcycle; $i++){
				   $days = implode(',',$_POST['slctDays'.$i]);
				   $sql = "INSERT INTO cycle (program_year_id, no_of_cycle, start_week, end_week, days, date_add) VALUES ('".$last_yr_id."', '".$slctNumcycle."', '".$_POST['startweek'.$i]."', '".$_POST['endweek'.$i]."', '".$days."', now())";
				   $rel = $this->conn->query($sql);

				}
				//END HERE
			}
			//END HERE
			$message="New program has been added successfully";
			$_SESSION['succ_msg'] = $message;
			return 1;
		}
	}

	/*function for add program*/
	public function editProgram()
	{
		$edit_id = base64_decode($_POST['programId']);
		$txtPrgmName = Base::cleanText($_POST['txtPrgmName']);
		$slctPrgmType = trim($_POST['slctPrgmType']);
		$prog_from_date = date("Y-m-d h:i:s", strtotime($_POST['prog_from_date']));
		$prog_to_date = date("Y-m-d h:i:s", strtotime($_POST['prog_to_date']));
		$slctNumcycle = trim($_POST['slctNumcycle']);

		$result =  $this->conn->query("SELECT program_name FROM program WHERE program_name='".$txtPrgmName."' AND id != '".$edit_id."'");
		$row_cnt = $result->num_rows;
		if($row_cnt > 0){
			$this->conn->close();
			$message="'".$txtPrgmName."' program already exist in database.";
			$_SESSION['error_msg'] = $message;
			return 0;
		}

		$sql = "UPDATE program SET
		               program_name = '".$txtPrgmName."',
		               program_type = '".$slctPrgmType."',
		               start_date = '".$prog_from_date."',
		               end_date = '".$prog_to_date."',
		               date_update = now() WHERE id=$edit_id";
		$rel = $this->conn->query($sql);
		if(!$rel){
			$_SESSION['error_msg'] = $this->conn->error;
			return 0;
		}else{
			//delete all the previous programs cycles and insert again
			$del_query="DELETE FROM cycle WHERE program_year_id in(select id from program_years where program_id='".$edit_id."')";
			$qry = mysqli_query($this->conn, $del_query);

			//delete all the previous programs year and insert again
			$del_query="DELETE FROM program_years WHERE program_id = '".$edit_id."'";
			$qry = mysqli_query($this->conn, $del_query);

			//INSERT PROGRAM YEARS  DATA
			for($j=1; $j<=$slctPrgmType; $j++){
				$progName = $txtPrgmName.'-'.$j;
				$start_year = date("Y", strtotime($prog_from_date));
				$start_year = $start_year + ($j-1);
				$end_year = $start_year + $j;
				$sql = "INSERT INTO program_years (program_id, name, start_year, end_year) VALUES ('".$edit_id."', '".$progName."', '".$start_year."', '".$end_year."')";
				$rel = $this->conn->query($sql);
				$last_yr_id = $this->conn->insert_id;
				//INSERT CYCLES DATA
				for($i=1; $i<=$slctNumcycle; $i++){
				   $days = implode(',',$_POST['slctDays'.$i]);
				   $sql = "INSERT INTO cycle (program_year_id, no_of_cycle, start_week, end_week, days, date_add) VALUES ('".$last_yr_id."', '".$slctNumcycle."', '".$_POST['startweek'.$i]."', '".$_POST['endweek'.$i]."', '".$days."', now())";
				   $rel = $this->conn->query($sql);

				}
				//END HERE
			}
			//END HERE
			$message="Record has been updated successfully";
			$_SESSION['succ_msg'] = $message;
			return 1;
		}
	}

	//function to  a program by id
	public function getProgramById($id){
		$result =  $this->conn->query("select * from program where id='".$id."'");
		return $result;
    }
	//function to  get all programs
	public function getProgramListData(){
		$result =  $this->conn->query("select * from program");
		return $result;
    }
	//function to  get all cycles data related to a program
	public function getProgramCycleList($prog_id){
		$result =  $this->conn->query("select * from cycle where program_year_id in(select id from program_years where program_id='".$prog_id."')");
		return $result;
    }
    //function to get no of cycle
    public function getCyclesInProgram($prog_id){
    	$result =  $this->conn->query("select no_of_cycle from cycle where program_year_id in(select id from program_years where program_id='".$prog_id."')");
		$row = $result->fetch_assoc();
		return $row['no_of_cycle'];
    }

	/*function for add student group*/
	public function associateStudentGroup()
	{
		$slctProgram = trim($_POST['slctProgram']);
		$slctSgroups = $_POST['slctSgroups'];

		//delete all the previous program groups and insert again
		$del_query="DELETE FROM program_group WHERE program_year_id in(select id from program_years where program_id='".$slctProgram."')";
		$qry = mysqli_query($this->conn, $del_query);

		foreach($slctSgroups as $val){
		  $result =  $this->conn->query("select id from program_years where program_id='".$slctProgram."'");
		  while($row = $result->fetch_assoc()){
			  $sql = "INSERT INTO program_group (program_year_id, group_id) VALUES ('".$row['id']."', '".$val."')";
			  $rel = $this->conn->query($sql);
		  }
		}

		$message="Program has been associated successfully";
		$_SESSION['succ_msg'] = $message;
		return 1;
	}

	//Function to list all the available groups list
	public function getGroupsList(){
		$result =  $this->conn->query("select * from group_master");
		return $result;
	}
	//Function to list sub programs
	public function getSubPrograms($pid){
		$result =  $this->conn->query("SELECT * FROM program_years WHERE program_id='".$pid."' ");
		return $result;
	}
	//Function to list all groups of a program
	public function getAllGroupByProgId($prog_id){
		$result =  $this->conn->query("select * from group_master where id in(select group_id FROM program_group WHERE program_year_id in(select id from program_years where program_id='".$prog_id."'))");
		return $result;
	}

}