<?php
/**
 * Created by PhpStorm.
 * User: YASIR MEHBOOB
 * Date: 10/03/2020
 * Time: 05:17 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class Statistics extends AdminLogin
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model('Api_qualification_model');
		$this->load->model('Api_location_model');
		$this->load->model('Admission_session_model');
		$this->load->model('FormVerificationModel');
		$this->load->model('User_model');
		$this->load->model('Application_model');
		$this->load->model('Statistics_model');
		$this->load->model('AdminAccount_model');
		$this->load->model('FormVerificationModel');
			$this->load->model('AdmitCard_model');
		
//		$this->load->library('javascript');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
		$this->script_name = $self[1];
		$this->verify_login();
		ini_set('memory_limit', '-1');
	}
    
    public function show_enrolment_data(){
        
        $user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
        
        //		$this->verify_path($this->script_name,$side_bar_data);
		
		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['data']= $this->Statistics_model->get_enrolment_data(1);
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
	
		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
	
	
       
       // p int_r(count($data));
        $this->load->view('admin/enrollment_data');
        $this->load->view('include/footer_area');
        $this->load->view('include/footer');
    }
	public function index ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
//		$this->verify_path($this->script_name,$side_bar_data);

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
		$this->load->view('admin/statistics');
		$this->load->view('include/footer_area');
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function total_apps_date_wise ()
	{
		$record = $this->Statistics_model->count_submitted_applications();
		echo json_encode($record);
	}
	public function application_district_wise()
	{
		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('campus','campus is required','required|trim|integer');
		$this->form_validation->set_rules('province_id','province is required','required|trim|integer');
		$this->form_validation->set_rules('division_id','division is required','required|trim|integer');
		$this->form_validation->set_rules('district_id','district is required','required|trim|integer');

		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
		$campus_id		= html_escape(htmlspecialchars($this->input->post('campus')));
		$province_id		= html_escape(htmlspecialchars($this->input->post('province_id')));
		$division_id		= html_escape(htmlspecialchars($this->input->post('division_id')));
		$district_id		= html_escape(htmlspecialchars($this->input->post('district_id')));

		$record = $this->Statistics_model->get_application_statistics_district_wise($session_id,$program_type_id,$campus_id,$province_id,$division_id,$district_id);
		echo json_encode($record);
		exit();
	}
	public function getStatistics ()
	{
		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('campus','campus is required','required|trim|integer');

		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
		$campus_id= html_escape(htmlspecialchars($this->input->post('campus')));

		$rows = $this->Statistics_model->get_statistics($session_id,$program_type_id,$campus_id);
		echo json_encode($rows);
	}

	public function config ()
	{
		$config = array(
			"SESSION_ID"=>1,
			"PROGRAM_TYPE"=>0,
			"CAMPUS_ID"=>0,
			"PROVINCE_ID"=>0,
			"DIVISION_ID"=>0,
			"DISTRICT_ID"=>0,
			"ADMISSION_SESSION_ID"=>0,
		);
		echo json_encode($config);
	}//method
	
/*
		public function application_district_wise_two()
	{
// 		$this->form_validation->set_rules('session','session is required','required|trim|integer');
// 		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
// 		$this->form_validation->set_rules('campus','campus is required','required|trim|integer');
// 		$this->form_validation->set_rules('province_id','province is required','required|trim|integer');
// 		$this->form_validation->set_rules('division_id','division is required','required|trim|integer');
// 		$this->form_validation->set_rules('district_id','district is required','required|trim|integer');

// 		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
// 		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
// 		$campus_id		= html_escape(htmlspecialchars($this->input->post('campus')));
// 		$province_id		= html_escape(htmlspecialchars($this->input->post('province_id')));
// 		$division_id		= html_escape(htmlspecialchars($this->input->post('division_id')));
// 		$district_id		= html_escape(htmlspecialchars($this->input->post('district_id')));

		$record = $this->Statistics_model->get_application_statistics_district_wise_two(0,0,0,0,0,0);
		echo json_encode($record);
		exit();
	}
	*/
	
		public function v_admins()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
//		$this->verify_path($this->script_name,$side_bar_data);

// 		$academic_session = $this->Admission_session_model->getSessionData();
// 		$program_types 	= $this->Administration->programTypes ();
// 		$application_status_list = $this->FormVerificationModel->get_application_status_list ();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['ADMINS_DATA'] = $this->get_form_verifier_data();

		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view("admin/form_verifier_admins_record",$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');

	}
    
    public function form_status()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];
          
	$side_bar_data	 = $this->Configuration_model->side_bar_data($user_id,$role_id);
	//	  prePrint($side_bar_data);
//            exit();
	//	$this->verify_path($this->script_name,$side_bar_data);

		$academic_session = $this->Admission_session_model->getSessionData();
		$program_types 	= $this->Administration->programTypes ();
		$application_status_list = $this->FormVerificationModel->get_application_status_list();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['academic_sessions'] = $academic_session;
		$data['program_types'] = $program_types;
		$data['application_status_list'] = $application_status_list;

  	    $this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('campus[]','campus is required','required|trim|integer');
		$this->form_validation->set_rules('gender[]','gender is required','required');

		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
		$admission_session_id= $this->input->post('campus[]');
		$gender			= $this->input->post('gender[]');
		if ($admission_session_id > 0 ) $admission_session_id = implode(',',$admission_session_id);
		if ($gender > 0) $gender = implode(',',$gender);
		else $gender = null;
		//		$rows = $this->AdmitCard_model->get_statistics ($session_id,$program_type_id,$campus_id);
		$rows = $this->AdmitCard_model->get_challan_statistics ($session_id,$program_type_id,$admission_session_id);
      //  prePrint($rows);
    //    exit();
        
        $data['statistics'] = json_encode($rows);
		
		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('admin/form_status_statistics',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}//method
    public function get_statistics_gender_wise_new(){
        	$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('campus[]','campus is required','required|trim|integer');
		$this->form_validation->set_rules('gender[]','gender is required','required');

		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
		$admission_session_id= $this->input->post('campus[]');
		$gender			= $this->input->post('gender[]');
		if ($admission_session_id > 0 ) $admission_session_id = implode(',',$admission_session_id);
		if ($gender > 0) $gender = implode(',',$gender);
		else $gender = null;
		//		$rows = $this->AdmitCard_model->get_statistics ($session_id,$program_type_id,$campus_id);
		$rows = $this->AdmitCard_model->get_challan_statistics ($session_id=2,$program_type_id=1,$admission_session_id="15,16");
		echo prePrint($rows);
    }
    
    public function application_form_report(){

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
		$this->load->view('admin/application_form_report_panel',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}//method

	public function getNewReport (){

		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('campus[]','campus is required','required|trim|integer');
		$this->form_validation->set_rules('gender[]','gender is required','required');

		$session_id 	= html_escape(htmlspecialchars($this->input->post('session')));
		$program_type_id= html_escape(htmlspecialchars($this->input->post('program_type')));
		$admission_session_id= $this->input->post('campus[]');
		$gender			= $this->input->post('gender[]');

		if ($admission_session_id > 0 ) $admission_session_id = implode(',',$admission_session_id);
		if ($gender > 0) $gender = implode(',',$gender);
		else $gender = null;

				$rows = $this->AdmitCard_model->get_statistics ($session_id,$program_type_id,$campus_id);
//		$rows = $this->Statistics_model->get_application_statistics ($session_id,$program_type_id,$admission_session_id,$gender);
//		prePrint($rows);
		//		echo json_encode($rows);
	}
	
	private function get_form_verifier_data()
	{
		$role_id[]=4;
		$role_id[]=6;

		$user_records = $this->AdminAccount_model->get_user_list_by_roll($role_id,0);
		$user_array = array();
		foreach ($user_records as $user_record){
//			prePrint($user_record);
			if (!isset($user_record['USER_ID'])) continue;
			$user_id = $user_record['USER_ID'];
			$form_verified_data = $this->FormVerificationModel->countAdminVerifiedApplication($user_id);
			$total_count = $form_verified_data['TOTAL_COUNT'];
			if($total_count==0)continue;
			$user_record['TOTAL_COUNT']=$total_count;
			$user_array[$user_id]=$user_record;
		}
		return $user_array;
//		echo json_encode($user_array);
//		prePrint($total_count);
//		prePrint($user_array);
	}
}
