<?php
/**
 * Created by PhpStorm.
 * User: YASIR MEHBOOB
 * Date: 9/20/2020
 * Time: 01:03 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class FormVerification extends AdminLogin
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
//		$this->load->library('javascript');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function index(){
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		
// 		prePrint($user_role);
// 		prePrint($user);
		$this->verify_path($this->script_name,$side_bar_data);

		$academic_session = $this->Admission_session_model->getSessionData();
		$program_types 	= $this->Administration->programTypes ();
		$application_status_list = $this->FormVerificationModel->get_application_status_list ();
        $district_list = $this->Api_location_model->getDistrictByProvinceId(6);
		$data['district_list'] = $district_list;
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
		$this->load->view('admin/form_verification',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}//method

	public function getAnnouncedCampus ()
	{

		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('session','session type is required','required|trim|integer');
		if($this->form_validation->run())
		{
			$program_type 	= isValidData($this->input->post('program_type'));
			$session_id 	= isValidData($this->input->post('session'));
			$record = $this->FormVerificationModel->get_announced_campus ($program_type,$session_id);
			if (is_array($record) || is_object($record))
			{
				$record = json_encode($record);
				http_response_code(200);
				exit($record);
			}else
			{
				$reponse = "<div class='text-danger'>Sorry record not found.</div>";
				http_response_code(405);
				exit(json_encode($reponse));
			}
		}else
		{
			$reponse = "<div class='text-danger'>Sorry you have provided invalid parameters</div>";
			http_response_code(405);
			exit(json_encode($reponse));
		}

	}//method

	public function VerificationStart (){
		$user 		= $this->session->userdata($this->SessionName);
		$user_role 	= $this->session->userdata($this->user_role);
		$user_id 	= $user['USER_ID'];
		$role_id 	= $user_role['ROLE_ID'];

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];
		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		//		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('campus','campus is required','required|trim|integer');
		$this->form_validation->set_rules('application_status','application status is required','required|trim|integer');
		$this->form_validation->set_rules('district_id','application district is required','required|trim|integer');

		if($this->form_validation->run())
		{
			$program_type 		= htmlspecialchars($this->input->post('program_type'));
			$session 			= htmlspecialchars($this->input->post('session'));
			$campus 			= htmlspecialchars($this->input->post('campus'));
			$application_status = htmlspecialchars($this->input->post('application_status'));
			$district_id = htmlspecialchars($this->input->post('district_id'));

			$admission_session = $this->Admission_session_model->getAdmissionSessionID($session,$campus,$program_type);
			if (empty($admission_session))
			{
				$this->session->set_flashdata('message','Sorry system could not found announced session.');
				redirect("FormVerification");
			}

			$ADMISSION_SESSION_ID = $admission_session['ADMISSION_SESSION_ID'];
		//	if($user_id==158729){
			    
			     	$applicant = $this->FormVerificationModel->get_single_Application_for_verification_by_district_id($ADMISSION_SESSION_ID,$application_status,$district_id);
	//		}else{
			 //  	$applicant = $this->FormVerificationModel->get_single_Application_for_verification($ADMISSION_SESSION_ID,$application_status); 
	//		}
		
//			prePrint();
			$data['program_type'] = $program_type;
			$data['session'] = $session;
			$data['campus'] = $campus;
			$data['application_status'] = $application_status;
			$data['applicant_data'] = $applicant;


//		$data['academic_sessions'] = $academic_session;
//		$data['program_types'] = $program_types;
//		$data['application_status_list'] = $application_status_list;

			$this->load->view('include/header',$data);
			$this->load->view('include/preloder');
//			$this->load->view('include/side_bar',$data);
//			$this->load->view('include/nav',$data);
			$this->load->view('admin/form_verification_profile',$data);
//		$this->load->view('include/footer_area');
			$this->load->view('include/footer');
		}else
		{
			$this->session->set_flashdata('message','Following fields are required.');
			redirect("FormVerification");
		}//else
	}//method

	public function review()
	{
	    	$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$admin_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		if($this->session->has_userdata('STUDENT_USER_ID')&&$this->session->has_userdata('STUDENT_APPLICATION_ID')){

			$USER_ID = $this->session->userdata('STUDENT_USER_ID');
			$APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');

// 			$user_fulldata = $this->User_model->getUserFullDetailById($USER_ID);

// 			$data['user'] = $user_fulldata;
			  $user_fulldata = $this->User_model->getUserFullDetailWithChoiceById($USER_ID,$APPLICATION_ID);
			 // prePrint($user_fulldata);
             //$data['profile_url'] = $this->profile;
            $data['user'] = $user_fulldata;
			$data['APPLICATION_ID'] = $APPLICATION_ID;
			$application = $this->Application_model->getApplicationByUserAndApplicationId($USER_ID, $APPLICATION_ID);
			
				$lat_info = $this->Application_model->getLatInfoByUserAndApplicationId($USER_ID, $APPLICATION_ID);

			if ($application) {

				$bank = $this->Admission_session_model->getBankInformationByBranchId($application['BRANCH_ID']);
				$application_status_list = $this->FormVerificationModel->get_application_status_list ();
				$application_verifier_data = $this->FormVerificationModel->getApplicationVerifierData($APPLICATION_ID);
				//$bank = $this->Admission_session_model;
				// prePrint();
				// exit();
				$data['user'] = $user_fulldata['users_reg'];
				$data['qualifications'] = $user_fulldata['qualifications'];
				$data['guardian'] = $user_fulldata['guardian'];
				$data['applicants_minors'] = $user_fulldata['applicants_minors'];
				if(isset($user_fulldata['application_choices'])){
				     $data['application_choices'] = $user_fulldata['application_choices'];
				}
				if(isset($user_fulldata['application_choices_evening'])){
				     $data['application_choices_evening'] = $user_fulldata['application_choices_evening'];
				}
				if(isset($user_fulldata['application_category'])){
				     $data['application_category'] = $user_fulldata['application_category'];
				
				}
				   
               
				$data['next_page'] = 'FormVerification';
				$data['application'] = $application;
				$data['bank'] = $bank;
				$data['application_status_list'] = $application_status_list;
				$data['lat_info'] = $lat_info;
				$data['VERIFIER_PROFILE'] = $application_verifier_data;


				$this->load->view('include/header', $data);
//		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//		$this->load->view('include/nav',$data);
$data['ROLE_ID'] = $role_id;
				$this->load->view('admin/form_review', $data);
//		$this->load->view('include/footer_area');
				$this->load->view('include/footer');

			}else{
				echo "Application Id not found";
			}
		}else{
			echo "Application Id not found";
		}
//        if($this->session->has_userdata('APPLICATION_ID')) {
//            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');
//
//            $user = $this->user;
//            $user_id = $user['USER_ID'];
//
//            $user_data = $this->User_model->getUserFullDetailById($user_id);
//            //prePrint($user_data);
//            $data['user'] = $user_data['users_reg'];
//            $data['qualifications'] = $user_data['qualifications'];
//            $data['guardian'] = $user_data['guardian'];
//            $data['next_page'] = $next_page;
//
//
//            $this->load->view('include/header', $data);
////		$this->load->view('include/preloder');
////		$this->load->view('include/side_bar');
////		$this->load->view('include/nav',$data);
//            $this->load->view('form_review', $data);
////		$this->load->view('include/footer_area');
//            $this->load->view('include/footer');
//        }else{
//            echo "Application Id not found";
//        }


	}

	public function UpdateStatus ()
	{
		if($this->session->has_userdata('STUDENT_USER_ID')&&$this->session->has_userdata('STUDENT_APPLICATION_ID')) {

			$this->form_validation->set_rules('application_status', 'Please select form status', 'trim|required|integer');
			if ($this->form_validation->run()) {

				$form_fee = $form_fee_status = $this->input->post('challan_verified');
				$profile_photo_verified = $profile_photo_verified_status = $this->input->post('profile_photo_verified');
				$additional_documents_verified = $additional_documents_verified_status = $this->input->post('additional_documents_verified');
				$application_status = isValidData($this->input->post('application_status'));
				$message = isValidData($this->input->post('message'));

//				if (empty($form_fee)) {
//					$form_fee_status = 0;
//				} else {
//					$form_fee_status = 1;
//				}
//				if (empty($profile_photo_verified)) {
//					$profile_photo_verified_status = 0;
//				} else {
//					$profile_photo_verified_status = 1;
//				}
//				if (empty($additional_documents_verified)) {
//					$additional_documents_verified_status = 0;
//				} else {
//					$additional_documents_verified_status = 1;
//				}

				$USER_ID = $this->session->userdata('STUDENT_USER_ID');
				$APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');

				$application = $this->Application_model->getApplicationByUserAndApplicationId($USER_ID, $APPLICATION_ID);
				$form_status_previous = "";
				$form_status = $form_status_previous = json_decode($application['FORM_STATUS'],true);

				$form_status['CHALLAN']['STATUS'] = $form_fee_status;
				$form_status['PROFILE_PHOTO']['STATUS'] = $profile_photo_verified_status;
				$form_status['ADDITIONAL_DOCUMENT']['STATUS'] = $additional_documents_verified_status;

				$user_system = $this->session->userdata($this->SessionName);
				$user_role = $this->session->userdata($this->user_role);
				$verifier_id = $user_system['USER_ID'];
                $profile_photo_verified_status=null;
                if($profile_photo_verified == "VERIFIED"){
                    $profile_photo_verified_status = 1;
                }else if($profile_photo_verified == "RE_UPLOAD"){
                    $profile_photo_verified_status = 2;
                }
				$update_array = array
				(
					'FORM_STATUS'=>json_encode($form_status),
					'MESSAGE'=>$message,
					'STATUS_ID'=>$application_status,
					'IS_PROFILE_PHOTO_VERIFIED'=>$profile_photo_verified_status
				);
				$return1=true;
				$c_date = date('Y-m-d');
				if($form_fee_status=="VERIFIED"){
				    $update_rec  = array("IS_VERIFIED"=>'Y',"PAID"=>"Y","REMARKS"=>"MANUALLY VERIFIED BY USER_ID = ".$verifier_id,"VERIFIER_ID"=>$verifier_id,"VERIFICATION_DATE"=>$c_date);
				}else{
				    $update_rec  = array("IS_VERIFIED"=>'N',"PAID"=>"N","REMARKS"=>"MANUALLY VERIFIED BY USER_ID = ".$verifier_id,"VERIFIER_ID"=>$verifier_id,"VERIFICATION_DATE"=>$c_date);
				}
				    
				    
          
				   	$return1 = $this->FormVerificationModel->UpdateStatus("APPLICATION_ID=$APPLICATION_ID",$update_rec,"",'form_challan',$verifier_id,$APPLICATION_ID);
			 
				
				$return = $this->FormVerificationModel->UpdateStatus("APPLICATION_ID=$APPLICATION_ID",$update_array,$form_status_previous,'applications',$verifier_id,$APPLICATION_ID);
				if ($return == true&&$return1==true)
				{
					$alert = array('MSG'=>"<h4 class='text-center text-success'>Status successfully updated.<br> <br/> <a href='javascript:void(0);' onclick='window.close();'> Click Here to close this window</a> </h4>",'TYPE'=>'ALERT');
					
					$application_after_status_update = $this->Application_model->getApplicationByUserAndApplicationId($USER_ID, $APPLICATION_ID);
					/*
					Status ID 4 is IN Review
					Status ID 6 is Form Rejected
					*/
					
					if($application_status == 4 || $application_status == 6)
					{
					    send_form_status_email($application_after_status_update);
					}
				// 	prePrint($application_after_status_update);
				// 	exit();
					$this->session->set_flashdata('ALERT_MSG',$alert);
					redirect(base_url()."FormVerification/review");
				}else
				{
					$alert = array('MSG'=>"<h4 class='text-center text-danger'>Error occurred while updating status or you are submitting this repeatedly. <br> <br/> <a href='javascript:void(0);' onclick='window.close();'> Click Here to close this window</a></h4>",'TYPE'=>'ALERT');
					$this->session->set_flashdata('ALERT_MSG',$alert);
					redirect(base_url()."FormVerification/review");
				}

//				prePrint($form_status);
//				echo $message;
//				exit();
			} else {
				$alert = array('MSG'=>"<h4 class='text-center text-danger'>Following * marked fields are required.</h4>",'TYPE'=>'ALERT');
				$this->session->set_flashdata('ALERT_MSG',$alert);
				redirect(base_url()."FormVerification/review");
			}
		} else{
			exit("Application ID not found start verification process from beginning.");
		}

	}
	
	public function set_application_id($user_id,$APPLICATION_ID,$url){
		$APPLICATION_ID = base64_decode(urldecode($APPLICATION_ID));
		$user_id = base64_decode(urldecode($user_id));
		$this->session->set_userdata('STUDENT_APPLICATION_ID', $APPLICATION_ID);
		$this->session->set_userdata('STUDENT_USER_ID', $user_id);
		$url = base_url() . base64_decode(urldecode($url));
		redirect($url);
		exit();
	}
	
	public function verification_list(){
	    
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
		$this->load->view('admin/verification_list',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}//method
	
	public function pdfVerificationList (){
	    
		$user 		= $this->session->userdata($this->SessionName);
		$user_role 	= $this->session->userdata($this->user_role);
		$user_id 	= $user['USER_ID'];
		$role_id 	= $user_role['ROLE_ID'];

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];
// 		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		//		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';
// 		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->form_validation->set_rules('program_type','program type is required','required|trim|integer');
		$this->form_validation->set_rules('session','session is required','required|trim|integer');
		$this->form_validation->set_rules('campus','campus is required','required|trim|integer');
		$this->form_validation->set_rules('application_status','application status is required','required|trim|integer');

		if($this->form_validation->run())
		{
			$program_type 		= htmlspecialchars($this->input->post('program_type'));
			$session 			= htmlspecialchars($this->input->post('session'));
			$campus 			= htmlspecialchars($this->input->post('campus'));
			$application_status = htmlspecialchars($this->input->post('application_status'));

			$admission_session = $this->Admission_session_model->getAdmissionSessionID($session,$campus,$program_type);
			if (empty($admission_session))
			{
				$this->session->set_flashdata('message','Sorry system could not found announced session.');
				redirect("FormVerification/verification_list");
			}

			$ADMISSION_SESSION_ID = $admission_session['ADMISSION_SESSION_ID'];
			$applicant = $this->FormVerificationModel->get_form_verification_list($ADMISSION_SESSION_ID,$application_status);
			$data['program_type'] = $program_type;
			$data['session'] = $session;
			$data['campus'] = $campus;
			$data['application_status'] = $application_status;
			$data['applicant_data'] = $applicant;

			$this->load->view('admin/FormVerificationList',$data);
		
		}else
		{
			$this->session->set_flashdata('message','Following fields are required.');
			redirect("FormVerification/verification_list");
		}//else
	}//method

    public function document_submission(){
        $user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		
		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
			$this->load->view('admin/document_submission',$data);
		
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
    }
    public function getApplicationById(){
        	$this->form_validation->set_rules('APPLICATION_ID','APPLICATION ID is required','required|trim|integer');
        	if($this->form_validation->run())
		    {
			   	$application_id	= htmlspecialchars($this->input->post('APPLICATION_ID'));
			  $application = $this->Application_model->getApplicationByApplicationID($application_id);
			  if($application){
			     $data['application'] = $application;
                $this->load->view('admin/application_view',$data);
			  }else{
			      $reponse = "<div class='text-danger'>Invalid Application Id</div>";
                http_response_code(404);
                exit($reponse); 
			  }
		    }else{
    		     $reponse = "<div class='text-danger'>Invalid Application Id</div>";
                http_response_code(405);
                exit($reponse);
		    }
		    
    }
    public function update_document_msg(){
        $admin = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $user_id = $admin['USER_ID'];
        $role_id = $user_role['ROLE_ID'];
        	$this->form_validation->set_rules('APPLICATION_ID','APPLICATION ID is required','required|trim|integer');
        //	$this->form_validation->set_rules('DOC_MSG','DOCUMENT SUBMISSION MSG is required','required|trim');
        	if($this->form_validation->run())
		    { 
		        	$application_id	= htmlspecialchars($this->input->post('APPLICATION_ID'));
		        		$DOC_MSG	= htmlspecialchars($this->input->post('DOC_MSG'));
		        			$recived_by	= htmlspecialchars($this->input->post('recived_by'));
		        			if(!$DOC_MSG){
		        			    $DOC_MSG = null;
		        			}
		        			$form_array = array("APPLICATION_ID"=>$application_id,"RECIVING_DATE"=>date('Y-m-d'),"RECIVED_BY"=>$recived_by,"REMARKS"=>$DOC_MSG);
		        		
		        		$res = $this->FormVerificationModel->addUpdateHardCopyDocumentSubmission($form_array,$user_id);
		       
              
                if ($res == true) {
                    $reponse['RESPONSE'] = "SUCCESS";
                    $reponse['MESSAGE'] = "<div class='text-success'>Successfully Save ..!<div>";
                } else {
                    $reponse['RESPONSE'] = "ERROR";
                    $reponse['MESSAGE'] = "<div class='text-danger'>Something went worng..!</div>";
                    
                }
		        
		    }else{
		           $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = $error;
		    }
		     if ($reponse['RESPONSE'] == "ERROR") {
                $this->output
                    ->set_status_header(500)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($reponse));
            } else {
                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($reponse));
            }

    }
}