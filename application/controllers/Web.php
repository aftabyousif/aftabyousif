<?php
/**
 * Created by PhpStorm.
 * User: YASIR MEHBOOB
 * Date: 10/03/2020
 * Time: 05:17 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("Web_model");
		$this->load->model("TestResult_model");
		$this->load->model("Administration");
		$this->load->model("Admission_session_model");
		$this->load->model("Application_model");
		$this->load->model("AdmitCard_model");
		$this->load->model("Selection_list_report_model");
		
	}

	public function index (){
	    redirect(base_url().'web/news');
	}
	
	public function news (){
	    $news_array = $this->news_array (-1);
	    $news_download = $this->news_download (0);
	    $data['newsarray']=$news_array;
	    $data['downloadarray']=$news_download;
	    
	    $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
	    $this->load->view("web/display_news",$data);
	    $this->load->view('include/login_footer');
	}
	
	public function read_news ($id=0){
	    $news_array = $this->news_array ($id);
	    $data['newsarray']=$news_array;
	    
	    $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
	    $this->load->view("web/display_news_detail",$data);
	    $this->load->view('include/login_footer');
	}
	
	protected function news_array ($id){
	    $newsarray=array(
        array(
        "ID"=>1,
        "TITLE"=>"Admit card / Admit Slip will be available at online admission portal...",
        "DATE"=>"21/09/2021",
        "NEWS_DETAIL"=>"Admit card / Admit Slip will be available at online admission portal after the verification of challan and validation of your uploaded photograph and documents.
        Please frequently visit your E-portal account dashboard & your email account for further process, latest information and updates regarding Admissions 2022"),
        array("ID"=>2,
        "TITLE"=>"Process of verification of Online Admission Forms will be started...",
        "DATE"=>"21/09/2021",
        "NEWS_DETAIL"=>"Process of verification of Online Admission Forms will be started after the announcement of all intermediate results by the concerned boards.
        Please frequently visit your E-portal account dashboard & your email account for further process, latest information and updates regarding Admissions 2022."),
        array("ID"=>3,
        "TITLE"=>"Choices and categories options will be opened soon...",
        "DATE"=>"21/09/2021",
        "NEWS_DETAIL"=>"Choices and categories options will be opened soon as pending results of intermediate will be announced by concerned boards.
        Please frequently visit your E-portal account dashboard & your email account for further process, latest information and updates regarding Admissions 2022.")
        );
            if($id >=0)
            {
                // Preprint($newsarray);
                return $newsarray[$id];
            }else
            {
                return $newsarray;
            }
	}
	
	protected function news_download ($id){
	
	$downloadarray=array(
    array("ID"=>1,"TITLE"=>"Advertisement of Admissions 2023 Morning Programmes ","PATH"=>"https://admission.usindh.edu.pk/admission/assets/advertisement_2023.pdf"),
    array("ID"=>2,"TITLE"=>"Undergraduate Prospectus 2023","PATH"=>"https://usindh.edu.pk/wp-content/uploads/2021/12/Prospectus-2023-Complete.pdf"),
    array("ID"=>3,"TITLE"=>"Circular for date of extention of Online Registration / submission of Admission Form","PATH"=>"../assets/img/kashif.jpg"),
    );
      if($id >0)
            {
                return $downloadarray[$id];
            }else
            {
                return $downloadarray;
            }
	}
	
	public function application_status (){

       $this->output->cache(60);
        
		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/check_app_status");
		$this->load->view('include/login_footer');
	}

	public function get_application_status (){
		$this->form_validation->set_rules('cnic_no','CNIC NO is required','trim|required|integer');
		if ($this->form_validation->run()) {
			$cnic_no = htmlspecialchars(html_escape($this->input->post("cnic_no")));
			$session_id = CURRENT_SESSION_ID;
			$user = $this->Web_model->getApplicationByUserId($cnic_no,0,0,$session_id);
			if (empty($user)){
				echo json_encode("No record found...");
				exit();
			}else
			{
				echo json_encode($user);
				exit();
			}

		}
	}
	
		/*
	 * YASIR NEW METHODS 13-01-2021
	 * */
	public function candidate_objection_list (){

// 		$this->output->cache(60);
        redirect(base_url()."candidate_merit_list_bachelor");
        exit();
		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/objection_list_candidate_search");
		$this->load->view('include/login_footer');
	}

	public function get_candidate_objection_list (){

		$this->form_validation->set_rules('cnic_no','CNIC NO is required','trim|required|integer');
		$this->form_validation->set_rules('program_type','Program type is required','trim|required|integer');
		$this->form_validation->set_rules('is_ob','objection is required','trim|required');
		
		if ($this->form_validation->run()) {
			$cnic_no = htmlspecialchars(html_escape($this->input->post("cnic_no")));
			$program_type = htmlspecialchars(html_escape($this->input->post("program_type")));
			$is_ob = htmlspecialchars(html_escape($this->input->post("is_ob")));
			$session_id = CURRENT_SESSION_ID;
			$user = $this->Web_model->get_candidate_objection_list(0,$cnic_no,0,0,$session_id,$program_type,0,0,0,$is_ob);
			if (empty($user)){
				echo ("<H3 class='text-center text-rose'>You are not selected...</H3>");
				exit();
			}else{
				$data['CANDIDATE']=$user;
				$this->load->view("web/objection_list_candidate_display",$data);
			}
		}
	}

	public function objection_list(){
// 		$this->output->cache(60);
// this URl is closed due to dublicate programe so need of this programe . we have same program in 2 differnt function
        redirect(base_url()."merit_list_bachelor");
        exit();
		$campus			= $this->Administration->getCampus();
		$sessions 		= $this->Admission_session_model->getSessionData();
		$shift 			= $this->Admission_session_model->getShiftData();
		$category_types = $this->Administration->category_type();
		$program_types  = $this->Administration->programTypes();

		$data['sessions'] = $sessions;
		$data['campus'] = $campus;
		$data['shifts'] = $shift;
		$data['category_types'] = $category_types;
		$data['program_types'] = $program_types;

		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/objection_list_all",$data);
		$this->load->view('include/login_footer');
	}

	public function getMappedPrograms (){
//		echo json_encode('hello');
		$this->form_validation->set_rules('shift_id','shift is required','required|trim');
		$this->form_validation->set_rules('program_type','program is required','required|trim');
		$this->form_validation->set_rules('campus_id','Campus Id is required','required|trim');

		if ($this->form_validation->run())
		{
			$shift_id = $this->input->post("shift_id");
			$program_type = $this->input->post("program_type");
			$campus_id = $this->input->post("campus_id");

			$record = $this->Administration->getMappedPrograms($shift_id,$program_type,$campus_id);
			echo json_encode($record);
		}//if
	}//function

	public function get_program_objection_list (){

		$this->form_validation->set_rules('campus_id','campus is required','trim|required|integer');
		$this->form_validation->set_rules('program_type','Program type is required','trim|required|integer');
		$this->form_validation->set_rules('program_id','Program is required','trim|required|integer');
		$this->form_validation->set_rules('shift_id','shift is required','trim|required|integer');
		$this->form_validation->set_rules('is_ob','shift is required','trim|required');
		if ($this->form_validation->run()) {
			$campus_id 		= htmlspecialchars(html_escape($this->input->post("campus_id")));
			$program_type 	= htmlspecialchars(html_escape($this->input->post("program_type")));
			$program_id 	= htmlspecialchars(html_escape($this->input->post("program_id")));
			$shift_id 		= htmlspecialchars(html_escape($this->input->post("shift_id")));
			$is_ob   		= htmlspecialchars(html_escape($this->input->post("is_ob")));
			$list_no   		= htmlspecialchars(html_escape($this->input->post("list_no")));
			
			$session_id 	= CURRENT_SESSION_ID;
			$test_id = 0;
                           
			$user = $this->Web_model->get_candidate_objection_list($campus_id,0,0,0,$session_id,$program_type,$shift_id,$program_id,$test_id,$is_ob,$list_no);
		
			if (empty($user)){
				echo ("<H3 class='text-center text-rose'>You are not selected in this list...</H3>");
				exit();
			}else{
				$data['CANDIDATE']=$user;
				$this->load->view("web/objection_list_program_display_sorted",$data);
			}
		}
	}
	
	public function candidate_merit_list_bachelor (){

// 		$this->output->cache(60);

		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/bachelor_merit_list_candidate_search");
		$this->load->view('include/login_footer');
	}
	
	public function merit_list_bachelor (){

// 		$this->output->cache(60);

		$campus			= $this->Administration->getCampus();
		$sessions 		= $this->Admission_session_model->getSessionData();
		$shift 			= $this->Admission_session_model->getShiftData();
		$category_types = $this->Administration->category_type();
		$program_types  = $this->Administration->programTypes();

		$data['sessions'] = $sessions;
		$data['campus'] = $campus;
		$data['shifts'] = $shift;
		$data['category_types'] = $category_types;
		$data['program_types'] = $program_types;


		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/bachelor_merit_program_wise",$data);
		$this->load->view('include/login_footer');
	}
	
	public function candidate_profile_search (){

// 		$this->output->cache(60);

		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/candidate_profile_search");
		$this->load->view('include/login_footer');
	}
	
	function get_candidate_profile(){
	    	$this->form_validation->set_rules('cnic_no','CNIC NO is required','trim|required|integer');
		$this->form_validation->set_rules('program_type','Program type is required','trim|required|integer');
		$this->form_validation->set_rules('is_ob','objection is required','trim|required');
		
		if ($this->form_validation->run()) {
			$cnic_no = htmlspecialchars(html_escape($this->input->post("cnic_no")));
			$program_type = htmlspecialchars(html_escape($this->input->post("program_type")));
			$is_ob = htmlspecialchars(html_escape($this->input->post("is_ob")));
			$session_id = CURRENT_SESSION_ID;
			$user = $this->Web_model->get_candidate_profile_display(0,$cnic_no,0,0,$session_id,$program_type,0,0,0,$is_ob);
		
			//prePrint($user);
			if (empty($user)){
				echo ("<H3 class='text-center text-rose'>Record Not Found...</H3>");
				exit();
			}else{
			   $APPLICATION_ID =  $user[0]['APPLICATION_ID'];
			   $USER_ID =  $user[0]['USER_ID'];
			    
			    $morning_choice = $this->Application_model->getChoiceByUserAndApplicationAndShiftId($USER_ID,$APPLICATION_ID,1);
			    $evening_choice = $this->Application_model->getChoiceByUserAndApplicationAndShiftId($USER_ID,$APPLICATION_ID,2);
			    $category = $this->Application_model->getApplicantCategory($APPLICATION_ID,$USER_ID);
			    $users = $this->User_model->getUserFullDetailById($USER_ID,$APPLICATION_ID);
			    
				$data['morning_choice']=$morning_choice;
				$data['evening_choice']=$evening_choice;
				$data['category']=$category;
				$data['CANDIDATE']=$user;
				$data['users']=$users;
			//	$data['this']=$this;
				$this->load->view("web/candidate_profile_display",$data);
			}
		}
	}
	
	private function CI_ftp_Download($path,$name){
        
        $date_time =date('Y F d l h:i A');
        $msg = array(
            "USER_ID"=>"UNKNOWN",
            "FILE_NAME"=>$name,
            "DATE_TIME"=>$date_time,
            "MSG"=>""
        );

        $this->load->library('ftp');
        $config['hostname'] = FTP_URL;
        $config['username'] = FTP_USER;
        $config['password'] = FTP_PASSWORD;
        $config['debug']        = false;
        $connect = false;
        for($i=1;$i<=3;$i++){
            $connect = $this->ftp->connect($config);
            if($connect){
                break;
            }
        }
        if(!$connect){
            $msg['MSG'] = 'CONNECTION FAILED';
            $msg = json_encode($msg);
            writeQuery($msg);
            $this->ftp->close();
            return false;
        }

        $ftp_path = str_replace("..","/public_html",$path);
        $ftp_dir_path = rtrim($ftp_path,"/");

        // $ftp_path = '/public_html/eportal_resource/foo/';
        // $ftp_dir_path = '/public_html/eportal_resource/foo';



        // $already_exist = $this->ftp->list_files($ftp_path);

        // if($already_exist){

        // }else{
        //     $dir  = $this->ftp->mkdir($ftp_dir_path, 0755);
        // }
//        prePrint($ftp_path.$name);
//        prePrint($path.$name);
//        exit();

        $up = $this->ftp->download($ftp_path.$name,$path.$name, 'binary');
        if(!$up){
            $msg['MSG'] = 'Downloading FAILED';
            $msg = json_encode($msg);
            $this->ftp->close();
            writeQuery($msg);

            return false;
        }

        $this->ftp->close();
        return true;

    }
    
    public function candidate_slip($data=null){
// 		$user = array("USER_ID"=>86747,"APPLICATION_ID"=>38812,"CARD_ID"=>1);
// 		$data = Base64url_encode(base64_encode(urlencode(json_encode($user))));
		$data_array = json_decode(urldecode(base64_decode(Base64url_decode($data))),true);
	
		if(isset($data_array['USER_ID'])&&isset($data_array['APPLICATION_ID'])&&isset($data_array['CARD_ID'])){
			$APPLICATION_ID = $data_array['APPLICATION_ID'];
			$application = $this->Application_model->getApplicationByUserAndApplicationId($data_array['USER_ID'], $APPLICATION_ID);
			$user_fulldata = $this->User_model->getUserFullDetailWithChoiceById($data_array['USER_ID'], $APPLICATION_ID);
			$admit_card = $this->AdmitCard_model->getAdmitCardOnAppID($APPLICATION_ID);
			 //	prePrint($application);
			 $block = $this->AdmitCard_model->getBlockBySeatNoAndSessionId($admit_card['CARD_ID'],$application['SESSION_ID']);
		
			if($application&&$user_fulldata&&$admit_card){
			
				$users_reg = $user_fulldata['users_reg'];
				if(!file_exists(PROFILE_IMAGE_CHECK_PATH.$users_reg['PROFILE_IMAGE'])){
   
					do {
						$resutl = $this->CI_ftp_Download(PROFILE_IMAGE_CHECK_PATH, $users_reg['PROFILE_IMAGE']);
	 
					   //prePrint("RES".$resutl);
					}while(!$resutl);
					 //exit();
				 }
				 //prePrint($user);
				 $data = array();
				 $user_role = null;
				 if($this->session->has_userdata('ADMISSION_ROLE')){
				   $user_role =  $this->session->userdata('ADMISSION_ROLE'); 
				   $user_role = $user_role['ROLE_ID'];
				 }
				 $data['user_role'] = $user_role;
				 $data['card_id'] = $data_array['CARD_ID'];
				 $data['admit_card'] = $admit_card;
				 $data['user_fulldata'] = $user_fulldata;
				 $data['application'] = $application;
				 $data['block'] = $block;
				// prePrint($block);
				 //exit();
				  $this->load->view('web/candidate_slip',$data);
   
			}else{
			   //application not found
			   $_SESSION['ALERT_MSG']['TYPE'] = 'ERROR';
			   $_SESSION['ALERT_MSG']['MSG'] = 'APPLICATION / ADMIT CARD NOT FOUND';
			   redirect(base_url() . "view_candidate_profile");
		   }
		}else{
			//some thing went wrong id not found
			 $_SESSION['ALERT_MSG']['TYPE'] = 'ERROR';
			   $_SESSION['ALERT_MSG']['MSG'] = 'SOMETHING WENT WRONG INVALID PARAMETER';
			redirect(base_url() . "view_candidate_profile");
		}
        
    }
    
    public function get_list (){
//		echo json_encode('hello');
		$this->form_validation->set_rules('shift_id','shift is required','required|trim');
		$this->form_validation->set_rules('program_type','program is required','required|trim');
		$this->form_validation->set_rules('campus_id','Campus Id is required','required|trim');

		if ($this->form_validation->run())
		{
			$shift_id = $this->input->post("shift_id");
			$program_type_id = $this->input->post("program_type");
			$campus_id = $this->input->post("campus_id");
			$session_id = CURRENT_SESSION_ID;

			$session_record = $this->Admission_session_model->getAdmissionSessionID($session_id,$campus_id,$program_type_id);
			$admission_session_id = $session_record['ADMISSION_SESSION_ID'];
			$lists = $this->Selection_list_report_model->get_admission_list_no($admission_session_id,$shift_id);
			$new_lists = array();
			foreach($lists as $obj){
			    if($obj['IS_DISPLAY']==1){
			        $new_lists[]=$obj;
			    }
			}
			echo json_encode($new_lists);
		}//if
	}//function
	
	public function merit_list_master (){

		$campus			= $this->Administration->getCampus();
		$sessions 		= $this->Admission_session_model->getSessionData();
		$shift 			= $this->Admission_session_model->getShiftData();
		$category_types = $this->Administration->category_type();
		$program_types  = $this->Administration->programTypes();

		$data['sessions'] = $sessions;
		$data['campus'] = $campus;
		$data['shifts'] = $shift;
		$data['category_types'] = $category_types;
		$data['program_types'] = $program_types;


		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/master_merit_program_wise",$data);
		$this->load->view('include/login_footer');
	}
	
	public function candidate_merit_list_master (){

		$this->load->view('include/login_header');
//		$this->load->view('include/preloder');
		$this->load->view('include/login_nav');
		$this->load->view("web/master_merit_list_candidate_search");
		$this->load->view('include/login_footer');
	}
	
	public function set_qualification(){
	    echo "asd";
	}
}