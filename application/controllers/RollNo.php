<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 08/28/2021
 * Time: 04:38 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class RollNo extends  AdminLogin
{
	private $script_name = "";

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model('User_model');
		$this->load->model('AdminAccount_model');
		$this->load->model('Admission_session_model');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/', $self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function rollno_generator()
	{

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id, $role_id);
		$this->verify_path($this->script_name,$side_bar_data);
		$data['side_bar_values'] = $side_bar_data;

		$data['user'] = $user;
		$data['profile_url'] = $user['PROFILE_IMAGE'];
		$this->load->view('include/header', $data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar', $data);
		$this->load->view('include/nav', $data);
		$this->load->view('admin/rollno_generator_window');
		$this->load->view('include/footer_area', $data);
		$this->load->view('include/footer', $data);
	}

	public function rollno_report()
	{

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id, $role_id);
		$this->verify_path($this->script_name,$side_bar_data);
		$data['side_bar_values'] = $side_bar_data;

		$data['user'] = $user;
		$data['profile_url'] = $user['PROFILE_IMAGE'];
		$this->load->view('include/header', $data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar', $data);
		$this->load->view('include/nav', $data);
		$this->load->view('admin/rollno_report_window');
		$this->load->view('include/footer_area', $data);
		$this->load->view('include/footer', $data);
	}
//getViewStartEndRollNos
	public function getViewStartEndRollNos()
	{

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
       
		$program_type_id = isValidData($request->program_type_id);
		$shift_id = isValidData($request->shift_id);
		$campus_id = isValidData($request->campus_id);
		$session_id = isValidData($request->session_id);
		$program_ids = $request->program_ids;
		$program_ids = implode(',', $program_ids);
//		$program_ids= json_decode($program_ids,true);

		$error = "";
		if (empty($program_type_id))
			$error .= "Program Type is Required";
		elseif (empty($shift_id))
			$error .= "Shift is Required";
		elseif (empty($campus_id))
			$error .= "Campus is Required";
		elseif (empty($program_ids))
			$error .= "Program is Required";

		if (empty($error)) {
			
			$admission_session_data = $this->Admission_session_model->getAdmissionSessionID($session_id, $campus_id, $program_type_id);
			$admission_session_id = $admission_session_data['ADMISSION_SESSION_ID'];
		
			$prev_roll_nos = $this->RollNo_model->start_end_rollnos($admission_session_id, $shift_id, $program_ids);
// 			prePrint($prev_roll_nos);
//             exit;
		} else {
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		if (empty($prev_roll_nos)) {
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode('Record Not Found...'));
		} else {
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($prev_roll_nos));
		}

	}//method

	public function getRollNoReportHandler()
	{

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$program_type_id 	= isValidData($request->program_type_id);
		$shift_id 			= isValidData($request->shift_id);
		$campus_id 			= isValidData($request->campus_id);
		$session_id 		= isValidData($request->session_id);
		$part_id 			= isValidData($request->part_id);
		$program_ids 		= $request->program_ids;
		$program_ids 		= implode(',', $program_ids);

// exit($part_id);
		$error = "";
		if (empty($program_type_id))
			$error .= "Program Type is Required";
		elseif (empty($shift_id))
			$error .= "Shift is Required";
		elseif (empty($campus_id))
			$error .= "Campus is Required";
		elseif (empty($program_ids))
			$error .= "Program is Required";
		elseif (empty($part_id))
			$error .= "Part is Required";

		if (empty($error)) {

			$admission_session_data = $this->Admission_session_model->getAdmissionSessionID($session_id, $campus_id, $program_type_id);
			$admission_session_id = $admission_session_data['ADMISSION_SESSION_ID'];
			$candidate_data = $this->RollNo_model->get_candidate_roll_no_report($admission_session_id,$shift_id,$program_ids,$part_id);
			   // prePrint($candidate_data);
            // exit;
			$candidate_data = $this->manage_array_candidate_roll_no_report($candidate_data);
//			$candidate_data = $candidate_data[$admission_session_id];
		} else {
			http_response_code(205);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		if (empty($candidate_data)) {
			http_response_code(203);
			$this->output->set_content_type('application/json')->set_output(json_encode('Record Not Found...'));
		} else {
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($candidate_data));
		}

	}//method

	public function generateRollNos(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$program_type_id    = isValidData($request->program_type_id);
		$shift_id           = isValidData($request->shift_id);
		$campus_id          = isValidData($request->campus_id);
		$session_id         = isValidData($request->session_id);
		$program_ids        = $request->program_ids;
		$rollFlag           = $request->rollNoFlag;
		$program_ids        = implode(',', $program_ids);

		$error = "";
		if (empty($program_type_id))
			$error .= "Program Type is Required";
		elseif (empty($shift_id))
			$error .= "Shift is Required";
		elseif (empty($campus_id))
			$error .= "Campus is Required";
		elseif (empty($program_ids))
			$error .= "Program is Required";
		elseif (empty($rollFlag))
			$error .= "Roll No From sequence is Required";

		if (empty($error)) {

			$admission_session_data = $this->Admission_session_model->getAdmissionSessionID($session_id, $campus_id, $program_type_id);
			$admission_session_id = $admission_session_data['ADMISSION_SESSION_ID'];
			$prev_roll_nos = $this->RollNo_model->start_end_rollnos($admission_session_id, $shift_id, $program_ids);
		
			$candidates = $this->RollNo_model->get_candidates($admission_session_id, $shift_id, $program_ids);
			$candidates_all = $this->setCandidateArrayForRollNoGenerate($candidates);
			$candidates_with_rollnos = $this->setCandidateWithRollNo($candidates_all);
			$candidates_without_rollnos = $this->setCandidateWithOutRollNo($candidates_all);
			$db_status = null;
			if ($rollFlag == "new"){
				$db_status = $this->create_roll_nos_from_new($candidates_all);
			}elseif ($rollFlag == "previous"){
				$db_status = $this->create_roll_nos_from_previous($candidates_without_rollnos,$prev_roll_nos);
			}
		} else {
		    prePrint($error);
			exit;
			http_response_code(204);
			echo $error;
			exit();
		}

		if ($db_status == 12) {
			http_response_code(200);
			echo "Roll Nos successfully generated...";
			exit();
		}elseif($db_status == 02){
			http_response_code(200);
			echo  'DB transaction rollback.';
			exit();
		}else{
			http_response_code(200);
			echo $db_status;
			exit();
		}
	}//method

	public function rollNoReportPrint(){
		$this->load->view('admin/rollNoPrintReport.html');
	}
	public function rollNoReportWithContact(){
		$this->load->view('admin/rollNoReportPrintWithContactDetails.html');
	}

	private function setCandidateArrayForRollNoGenerate($candidates)
	{
		$new_array = array();

		foreach ($candidates as $candidate) {
			$prog_list_id = $candidate['PROG_LIST_ID'];
			$new_array[$prog_list_id][] = $candidate;
		}
		return $new_array;
	}//method

	private function setCandidateWithRollNo($prog_candidates)
	{
		$new_array = array();
		foreach ($prog_candidates as $prog_candidate) {
			foreach ($prog_candidate as $candidate) {
				$prog_list_id = $candidate['PROG_LIST_ID'];
				$roll_no = $candidate['ROLL_NO_CODE'];
				if (empty($roll_no) || $roll_no == 0) continue;
				$new_array[$prog_list_id][] = $candidate;
			}

		}
		return $new_array;
	}//method

	private function setCandidateWithOutRollNo($prog_candidates){
		$new_array = array();
		foreach ($prog_candidates as $prog_candidate) {
			foreach ($prog_candidate as $candidate) {
				$prog_list_id = $candidate['PROG_LIST_ID'];
				$roll_no = $candidate['ROLL_NO_CODE'];
				if ($roll_no != null || $roll_no > 0) continue;
				$new_array[$prog_list_id][] = $candidate;
			}
		}
		return $new_array;
	}//method

	private function setProgramPreviousRollNo($prog_previous_roll_nos){
		$new_array = array();
		foreach ($prog_previous_roll_nos as $previous_roll_nos) {
			$prog_list_id = $previous_roll_nos['PROG_LIST_ID'];
			$new_array[$prog_list_id] = $previous_roll_nos;
		}
		return $new_array;
	}//method

	private function create_roll_nos_from_new($prog_candidates){
		$new_array = array();
		foreach ($prog_candidates as $prog_candidate) {
			$roll_no_counter = 0;
			foreach ($prog_candidate as $candidate) {
				$prog_list_id = $candidate['PROG_LIST_ID'];
				$roll_no_counter++;
				$candidate['ROLL_NO_CODE']=$roll_no_counter;
				$new_array[$prog_list_id][]=$candidate;
			}
		}
		if (count($new_array)>0){
			$save_status = $this->RollNo_model->save_roll_nos($new_array);
		}else{
			$save_status = "Roll No array is empty";
		}
		return $save_status;
	}

	private function create_roll_nos_from_previous($prog_candidates,$prog_previous_roll_nos){
		$prog_previous_roll_nos = $this->setProgramPreviousRollNo($prog_previous_roll_nos);
//		prePrint($prog_previous_roll_nos);
		$new_array = array();

		foreach ($prog_candidates as $key=>$prog_candidate) {
			$roll_no_counter= 0;
			$last_roll_no 	= 0;
			if (isset($prog_previous_roll_nos[$key]) || is_array($prog_previous_roll_nos[$key])) {
					$last_roll_no = $prog_previous_roll_nos[$key]['END_ROLL_NO'];
			}else{
			exit("failed...");
			}
			if (empty($last_roll_no) || $last_roll_no == 0){
				$roll_no_counter =0;
			}elseif ($last_roll_no>0){
				$roll_no_counter = $last_roll_no;
			}
			foreach ($prog_candidate as $candidate) {
				$prog_list_id = $candidate['PROG_LIST_ID'];
					$roll_no_counter++;
					$candidate['ROLL_NO_CODE']=$roll_no_counter;
					$new_array[$prog_list_id][]=$candidate;

			}//foreach
			$prog_previous_roll_nos[$key]['END_ROLL_NO']=$roll_no_counter;
		}//foreach
//		prePrint($new_array[5]);
		if (count($new_array)>0){
			$save_status = $this->RollNo_model->save_roll_nos($new_array);
		}else{
			$save_status = "Roll No array is empty";
		}
		return $save_status;
	}//method

private function manage_array_candidate_roll_no_report($candidate_data){
	
		$main_array = array();
		foreach ($candidate_data as $c_data){
			$admission_session_id 	= $c_data['ADMISSION_SESSION_ID'];
			$prog_list_id 			= $c_data['PROG_LIST_ID'];
			$campus_name 			= $c_data['CAMPUS_NAME'];
			$program_title 			= $c_data['PROG_TITLE'];
			$year        			= $c_data['YEAR'];
			$part_name   			= $c_data['PART_NAME'];
			$shift_id   			= $c_data['SHIFT_ID'];
            if($shift_id == 1) $shift_name="MORNING"; elseif($shift_id == 2) $shift_name = "EVENING"; else $shift_name = $shift_id;
			$main_array['CAMPUS_NAME']=$campus_name;
			$main_array['YEAR']=$year;
			$main_array['PART_NAME']=$part_name;
			$main_array['SHIFT_NAME']=$shift_name;
			$main_array['DATA'][$prog_list_id]['PROGRAM_TITLE']=$program_title;
			$main_array['DATA'][$prog_list_id]['STD_DATA'][]= $c_data;
		}
		return $main_array;
	}
}//class
