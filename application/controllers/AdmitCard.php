<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class AdmitCard extends AdminLogin
{
	/*
	 * __construct method is updated by Yasir Mehboob 16-10-2020
	 * */
	 public function test(){
	     //s_id=1&pt_id=1&sh_id=1&c_id=1&p_id=1&pl_id=%5B5%2C150%2C141%5D
	     if(isset($_GET['s_id'])&&isset($_GET['pt_id'])
	     &&isset($_GET['sh_id'])&&isset($_GET['c_id'])
	     &&isset($_GET['p_id'])&&isset($_GET['pl_id'])){
            
            $session_id = isValidData($_GET['s_id']);
            $shift_id = isValidData($_GET['sh_id']);
            $prog_type_id = isValidData($_GET['pt_id']);
            $campus_id = isValidData($_GET['c_id']);
            $part_id = isValidData($_GET['p_id']);
            $prog_list_id = json_decode(urldecode($_GET['pl_id']));
            $prog_list_id_str = join($prog_list_id,',');
            prePrint($prog_list_id_str);

	     }else{
	         exit("<h1>Please Must Select All parameters</h1>");
	     }
	    // prePrint();
	 }
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Configuration_model");
		$this->load->model('Api_location_model');
		$this->load->model("Admission_session_model");
		$this->load->model("AdmitCard_model");
		$this->load->model('User_model');
		$this->load->model('Application_model');
		$this->load->model('FormVerificationModel');
//		$this->load->library('javascript');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
//		prePrint($self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function venue ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$sessions = $this->Admission_session_model->getSessionData();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['sessions'] = $sessions;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('venue',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function save_venue ()
	{
		$this->form_validation->set_rules('session_id','Session is required','required|trim|integer');
		$this->form_validation->set_rules('venue_no','Venue no is required','trim');
		$this->form_validation->set_rules('venue_name','Venue name  is required','trim|required');
		$this->form_validation->set_rules('venue_location','Venue location is required','trim|required');
		$this->form_validation->set_rules('venue_id','','trim');
		$this->form_validation->set_rules('remarks','','trim');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message','Following * marked fields are required.');
			redirect("AdmitCard/venue");
		}else
		{
			$session_id 	= html_escape(htmlspecialchars($this->input->post('session_id')));
			$venue_no 		= html_escape(htmlspecialchars($this->input->post('venue_no')));
			$venue_name		= html_escape(htmlspecialchars($this->input->post('venue_name')));
			$location		= html_escape(htmlspecialchars($this->input->post('venue_location')));
			$remarks		= html_escape(htmlspecialchars($this->input->post('remarks')));
			$venue_id		= html_escape(htmlspecialchars($this->input->post('venue_id')));

			$record = array(
				'SESSION_ID'=>$session_id,
				'VENUE_NO'=>$venue_no,
				'VENUE_NAME'=>ucwords(strtoupper($venue_name)),
				'LOCATION'=>ucwords(strtoupper($location)),
				'REMARKS'=>ucwords(strtoupper($remarks)),
			);
			if ($venue_id == 0 || empty($venue_id) || is_nan($venue_id))
			{
				$response = $this->AdmitCard_model->insert($record,'venue');
			}else
			{
				$previous_record = $this->AdmitCard_model->getVenueOnVenue_ID ($venue_id);
				$response = $this->Administration->update("VENUE_ID=$venue_id",$record,$previous_record,'venue');
			}

			if ($response == true)
			{
				$this->session->set_flashdata('message',"Successfully added.");
				redirect("AdmitCard/venue");

			}else
			{
				$this->session->set_flashdata('message','Process failed, Please try again.');
				redirect("AdmitCard/venue");
			}
		}//else
	}

	public function getVenue ()
	{
		$this->form_validation->set_rules('session_id','Minor Subject is required','required|trim|integer');
		$session_id 			= html_escape(htmlspecialchars($this->input->post('session_id')));

		$rows = $this->AdmitCard_model->getVenueOnSession_ID ($session_id);
		echo json_encode($rows);
	}

	public function DeleteVenue ()
	{
		$this->form_validation->set_rules('venue_id','venue id is required','required|trim|integer');
		if ($this->form_validation->run())
		{
			$venue_id = html_escape(htmlspecialchars($this->input->post('venue_id')));

			$response = $this->AdmitCard_model->DeleteVenue($venue_id);
			if ($response == true)
			{
				http_response_code(202);
				echo json_encode("Successfully Deleted");
			}
			else
			{
				http_response_code(406);
				echo json_encode("Could not delete mapped category");
			}
		}
	}//function

	public function block ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$sessions = $this->Admission_session_model->getSessionData();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['sessions'] = $sessions;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('block',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}
	public function save_block ()
	{
		$this->form_validation->set_rules('session_id','Session is required','required|trim|integer');
		$this->form_validation->set_rules('venue_id','Session is required','required|trim|integer');
		$this->form_validation->set_rules('block_no','Block no is required','trim');
		$this->form_validation->set_rules('block_name','Block name  is required','trim|required');
		$this->form_validation->set_rules('block_location','Venue location is required','trim|required');
		$this->form_validation->set_rules('seating_capacity','Seating Capacity is required','trim|required');
		$this->form_validation->set_rules('for','for is required','trim|required');
		$this->form_validation->set_rules('block_id','','trim');
		$this->form_validation->set_rules('remarks','','trim');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message','Following * marked fields are required.');
			redirect("AdmitCard/block");
		}else
		{
			$session_id 	= html_escape(htmlspecialchars($this->input->post('session_id')));
			$block_no 		= html_escape(htmlspecialchars($this->input->post('block_no')));
			$block_name		= html_escape(htmlspecialchars($this->input->post('block_name')));
			$location		= html_escape(htmlspecialchars($this->input->post('block_location')));
			$seating_capacity		= html_escape(htmlspecialchars($this->input->post('seating_capacity')));
			$remarks		= html_escape(htmlspecialchars($this->input->post('remarks')));
			$venue_id		= html_escape(htmlspecialchars($this->input->post('venue_id')));
			$block_id		= html_escape(htmlspecialchars($this->input->post('block_id')));
			$for		= html_escape(htmlspecialchars($this->input->post('for')));

			$record = array(
				'VENUE_ID'=>$venue_id,
				'BLOCK_NO'=>$block_no,
				'BLOCK_NAME'=>ucwords(strtoupper($block_name)),
				'LOCATION'=>ucwords(strtoupper($location)),
				'SEATING_CAPACITY'=>ucwords(strtoupper($seating_capacity)),
				'RESERVED_FOR'=>ucwords(strtoupper($for)),
				'REMARKS'=>ucwords(strtoupper($remarks)),
			);
			if ($block_id == 0 || empty($block_id) || is_nan($block_id))
			{
				$response = $this->AdmitCard_model->insert($record,'block');
			}else
			{
				$previous_record = $this->getBlockOnBlock_ID ($block_id);
				$response = $this->Administration->update("BLOCK_ID=$block_id",$record,$previous_record,'block');
			}

			if ($response == true)
			{
				$this->session->set_flashdata('message',"Successfully added.");
				redirect("AdmitCard/block");

			}else
			{
				$this->session->set_flashdata('message','Process failed, Please try again.');
				redirect("AdmitCard/block");
			}
		}//else
	}
	public function getBlock ()
	{
		$this->form_validation->set_rules('venue_id','venue is required','required|trim|integer');
		$venue_id 			= html_escape(htmlspecialchars($this->input->post('venue_id')));

		$rows = $this->AdmitCard_model->getBlockOnVenue_ID ($venue_id);
		echo json_encode($rows);
	}
	
	/*
	 * YASIR MEHBOOB STARTED NEW METHODS BELOW THIS LINE ON 16-10-2020
	 * */

	public function generate()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$academic_session = $this->Admission_session_model->getSessionData();
		$program_types 	= $this->Administration->programTypes ();
		$application_status_list = $this->FormVerificationModel->get_application_status_list ();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['academic_sessions'] = $academic_session;
		$data['program_types'] = $program_types;
		$data['application_status_list'] = $application_status_list;

		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('admin/generate_admit_card',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}//method

	public function produce_card ()
	{
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('campus[]','campus is required','required|trim|integer');
		$this->form_validation->set_rules('application_status[]','application status is required','required|trim|integer');
		$this->form_validation->set_rules('entry_datetime','datetime is required','required|trim');
		$this->form_validation->set_rules('start_seat_no','Seat No starting point is required','required|trim');
		$this->form_validation->set_rules('gender[]','gender is required','required|trim');

		if($this->form_validation->run())
		{
			$program_type 		= html_escape(htmlspecialchars($this->input->post('program_type')));
			$session 			= html_escape(htmlspecialchars($this->input->post('session')));
			$session 			= html_escape(htmlspecialchars($this->input->post('session')));
			$start_seat_no_user_given = html_escape(htmlspecialchars($this->input->post('start_seat_no')));
			$campus 			= $this->input->post('campus');
			$application_status = $this->input->post('application_status');
			$entry_datetime		= $this->input->post('entry_datetime');
			$gender		= $this->input->post('gender');

			$campus_ids = implode(',',$campus); // this is admission session ID variable name is wrong
			$status_ids = implode(',',$application_status);
			$genders = implode(",",$gender);
//			exit($genders);
			$entry_datetime = date_create($entry_datetime);
			$entry_datetime = date_format($entry_datetime,'Y-m-d h:i A');
//			exit($entry_datetime);

			$first_seat_no = 0;
			$last_seat_no  = 0;
			$last_seat_no = $start_seat_no_user_given;
//			$last_seat_no = $this->last_seat_no_by_session_id($session,$program_type);
			$candidates_for_admit_card = $this->AdmitCard_model->get_applications_for_admit_card_generation ($session,$program_type,$campus_ids,$status_ids,$genders);

//			prePrint($candidates_for_admit_card);
//			exit();
			if (empty($candidates_for_admit_card))
			{
				$alert = array('MSG'=>"<h4 class='text-danger text-center'>Sorry candidates could not be found in the database, Please check your parameters which you have given...</h4>",'TYPE'=>'ALERT');
				$this->session->set_flashdata('ALERT_MSG',$alert);
				redirect(base_url()."AdmitCard/generate");
				exit();
			}else
			{
				$this->create_seat_nos($last_seat_no,$candidates_for_admit_card,$entry_datetime);
			}
			//			echo $first_seat_no;
		}else
		{
			$alert = array('MSG'=>"<h4 class='text-danger text-center'>The ' * ' marked fields are required.</h4>",'TYPE'=>'ALERT');
			$this->session->set_flashdata('ALERT_MSG',$alert);
			redirect(base_url()."AdmitCard/generate");
			exit();
		}//else
	}//method

	protected function create_seat_nos ($last_seat_no,$candidate_records,$entry_datetime)
	{
		$last_seat_no = $last_seat_no - 1; // -1 to manage seat no because it is incrementing in loop
//		echo $last_seat_no;
//		echo "Process started, Please wait.. it may take a while...";

		$is_dispatched = "N";
		$new_array_candidates = array();
		foreach ($candidate_records as  $candidate_record)
		{
//			prePrint($candidate_record);
			$application_id = $candidate_record['APPLICATION_ID'];
			$session_id = $candidate_record['SESSION_ID'];
			$admission_session_id = $candidate_record['ADMISSION_SESSION_ID'];
			$program_type_id = $candidate_record['PROGRAM_TYPE_ID'];

			$last_seat_no++;
		$candidate_card = array
		(
			'CARD_ID'=>$last_seat_no,
			'APPLICATION_ID'=>$application_id,
			'ADMISSION_SESSION_ID'=>$admission_session_id,
			'SESSION_ID'=>$session_id,
			'IS_DISPATCHED'=>$is_dispatched,
			'TEST_DATETIME'=>$entry_datetime,
			'PROGRAM_TYPE_ID'=>$program_type_id,
		);
		$new_array_candidates[] = $candidate_card;
		}//foreach

		$result_back = $this->AdmitCard_model->insert_seat_nos($new_array_candidates);
		if ($result_back)
		{
			$alert = array('MSG'=>"<h4 class='text-danger text-center'>Successfully generated.</h4>",'TYPE'=>'ALERT');
			$this->session->set_flashdata('ALERT_MSG',$alert);
			redirect(base_url()."AdmitCard/generate");
			exit();
		}else
		{
			$alert = array('MSG'=>"<h4 class='text-danger text-center'>Failed due to some technical issue Or your given SEAT NO is repeating please check manually before start it again. If issue still persist contact with the ITSC Developer Team.</h4>",'TYPE'=>'ALERT');
			$this->session->set_flashdata('ALERT_MSG',$alert);
			redirect(base_url()."AdmitCard/generate");
			exit();
		}
//		prePrint($new_array_candidates);
//		prePrint($candidate_record);
	}
	protected function first_seat_no_by_session_id ($session_id){
		$num = 0;
		$row = $this->AdmitCard_model->get_first_seat_no_on_session_id($session_id);
		if (empty($row[0]['FIRST_SEAT_NO']) ||$row[0]['FIRST_SEAT_NO'] == 0 || $row[0]['FIRST_SEAT_NO'] == "" || $row[0]['FIRST_SEAT_NO'] == null) $num=0;
		elseif($row[0]['FIRST_SEAT_NO'] > 0) $num = $row[0]['FIRST_SEAT_NO'];
		else exit("System could not find first ' SEAT NO ' please check manually or try again");

		return $num;
//		print_r($row);
	}
	protected function last_seat_no_by_session_id ($session_id,$program_type_id){
		$num = 0;
		$row = $this->AdmitCard_model->get_last_seat_no_on_session_id($session_id,$program_type_id);
		if (empty($row[0]['LAST_SEAT_NO']) ||$row[0]['LAST_SEAT_NO'] == 0 || $row[0]['LAST_SEAT_NO'] == "" || $row[0]['LAST_SEAT_NO'] == null) $num=0;
		elseif($row[0]['LAST_SEAT_NO'] > 0) $num = $row[0]['LAST_SEAT_NO'];
		else exit("System could not find last ' SEAT NO ' please check manually or try again");

		return $num;
//		print_r($row);
	}
	public function getStatistics ()
	{
		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('campus','campus is required','required|trim|integer');

		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
		$campus_id		= html_escape(htmlspecialchars($this->input->post('campus')));


		$rows = $this->AdmitCard_model->get_statistics ($session_id,$program_type_id,$campus_id);

		echo json_encode($rows);
	}

	public function getStatistics_gender_wise (){

		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('campus[]','campus is required','required|trim|integer');
		$this->form_validation->set_rules('gender[]','gender is required','required');

		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
		$admission_session_id= $this->input->post('campus[]');
		$gender			= $this->input->post('gender[]');

		if (!empty($admission_session_id)){
			$admission_session_id = implode(',',$admission_session_id);
		}
		if (!empty($gender)){
			$gender = implode(',',$gender);
		}else{
			$gender = null;
		}

		//		$rows = $this->AdmitCard_model->get_statistics ($session_id,$program_type_id,$campus_id);
		$rows = $this->AdmitCard_model->get_statistics_gender_wise ($session_id,$program_type_id,$admission_session_id,$gender);
		
	
		
		echo json_encode($rows);
	}

//	protected function admit_card_log ($message,$remarks)
//	{
//		$folder = "log/admitCard";
//		if (!is_dir($folder))
//		{
//			mkdir($folder,0777,true);
//		}
//		$date = date ("d-m-Y");
//		$file = $folder.'/'.$date;
//		$open = fopen($file,'a');
//		$text = "[".date('d-m-Y h:i A') ."]";
//		fwrite($open,$text);
//	}

    // public function get_query(){
    //     $this->AdmitCard_model->getAdmitCardForApp(2,1,'2021-11-07',10,10);
    // }

}
