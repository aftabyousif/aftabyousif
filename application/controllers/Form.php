<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends CI_Controller
{
    private $SelfController = 'form';
    private $profile = 'candidate/profile';
    private $LoginController = 'login';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    private $user ;
    private $file_size = 200;

	public function __construct()
	{
		parent::__construct();

        if(!$this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->LoginController);
            exit();
        }else{
            $this->user = $this->session->userdata($this->SessionName);
        }
		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Admission_session_model");
		$this->load->model("Application_model");
		$this->load->model("User_model");
		$this->load->model("Prerequisite_model");
	}

	public function announcement ()
	{
		$admission_announcements = $this->Admission_session_model->get_form_admission_session ();
        $user = $this->user ;
		$data['user'] = $user;
		$data['profile_url'] = '';

		$data['admission_announcement'] = $admission_announcements;
        $data['user_application_list'] = $this->Application_model->getApplicationByUserId($user['USER_ID']);
		$this->load->view('include/header',$data);

		$this->load->view('display_form_announcement',$data);

		$this->load->view('include/footer');
	}

	public function review($next_page)
	{

        $next_page =urldecode($next_page);
        $next_page = base64_decode($next_page);
        if($this->session->has_userdata('APPLICATION_ID')){
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');
            $user = $this->session->userdata($this->SessionName);
            //prePrint($user);
            $user_fulldata = $this->User_model->getUserFullDetailById($user['USER_ID']);

            $data['user'] = $user_fulldata;
            $data['APPLICATION_ID'] = $APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);

            if ($application) {

                $bank = $this->Admission_session_model->getBankInformationByBranchId($application['BRANCH_ID']);
                //$bank = $this->Admission_session_model;
                $data['user'] = $user_fulldata['users_reg'];
                $data['qualifications'] = $user_fulldata['qualifications'];
                $data['guardian'] = $user_fulldata['guardian'];
                $data['next_page'] = $next_page;
                $data['application'] = $application;
                $data['bank'] = $bank;


                $this->load->view('include/header', $data);
//		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//		$this->load->view('include/nav',$data);
                $this->load->view('form_review', $data);
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

	public function application_list(){

        $user = $this->user ;
        $data['user'] = $user;
        $data['profile_url'] = '';


        $data['user_application_list'] = $this->Application_model->getApplicationByUserId($user['USER_ID']);
        $this->load->view('include/header',$data);

        $this->load->view('application_list',$data);

        $this->load->view('include/footer');
    }

    public function addApplication (){

        $user = $this->user ;
        $user_id = $user['USER_ID'];
	    $this->form_validation->set_rules('ADMISSION_SESSION_ID','form session','required');
        $this->form_validation->set_rules('CAMPUS_ID','campus','required');

        if ($this->form_validation->run())
        {
            $ADMISSION_SESSION_ID =  $this->input->post('ADMISSION_SESSION_ID');
            $admission = $this->Admission_session_model->getAdmissionSessionById($ADMISSION_SESSION_ID);


            if($admission){
                $end_date = $admission['ADMISSION_END_DATE'];
                $SESSION_ID = $admission['SESSION_ID'];
                $CAMPUS_ID = $admission['CAMPUS_ID'];

                $form_fees = $this->Admission_session_model->getFormFeesBySessionAndCampusId($SESSION_ID,$CAMPUS_ID);
                if($form_fees) {
                    $datetime = gmdate('Y-m-d', time());
                    if ($end_date >= $datetime) {

                        $result = $this->Application_model->getApplicationByUserIdAndAdmissionSessionId($user_id, $ADMISSION_SESSION_ID);
                        if (!$result) {
                            $user_data = $this->User_model->getUserFullDetailById($user_id);
                            $user_data = json_encode($user_data);
                            $datetime = gmdate('Y-m-d H:i:s', time());
                            $form_array = array(
                                "USER_ID" => $user_id,
                                'ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID,
                                'FORM_DATE' => $datetime,
                                'STATUS_ID' => 1,
                                'IS_SUBMITTED' => 'N',
                                'FORM_DATA' => $user_data);
                            $is_add_application = $this->Application_model->addApplication($form_array);


                            if ($is_add_application) {
                                $APPLICATION_ID = $is_add_application;
                                $form_array = array(
                                    "USER_ID" => $user_id,
                                    'ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID,
                                    'APPLICATION_ID' => $APPLICATION_ID,
                                    'FORM_FEE_ID' => $form_fees['FORM_FEE_ID'],
                                    'CHALLAN_AMOUNT' => $form_fees['AMOUNT']);
                                $is_add_challan = $this->Application_model->addChallan($form_array);


                                if ($is_add_challan) {

                                    $APPLICATION_ID = urlencode(base64_encode($APPLICATION_ID));
                                    //setting session data for application
                                    $this->session->set_userdata('APPLICATION_ID', $APPLICATION_ID);

                                    $url = base_url() . "form/admission_form_challan";
                                    $this->session->set_flashdata('OPEN_TAB', $url);
                                    $this->set_application_id($APPLICATION_ID,urlencode(base64_encode('candidate/profile')));


                                } else {
                                    echo "can not generate challan";
                                }


                            } else {
                                echo "form dose not submit";
                            }

                        } else {
                            echo "You are application is already submit";
                            //     redirect(base_url().'form/announcement');
                        }

                    } else {
                        echo "Date Expire...!";
                        //   redirect(base_url().'form/announcement');
                    }
                }else{
                    echo "Form Fees Not Found";
                }

            }else{
                echo "Invalid Admission Session Id";
                //redirect(base_url().'form/announcement');
            }

        }
        else{
            redirect(base_url().'form/announcement');
        }
    }

    public function admission_form_challan(){

        if($this->session->has_userdata('APPLICATION_ID')){
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');

            $user = $this->session->userdata($this->SessionName);
            $user = $this->User_model->getUserById($user['USER_ID']);

            $data['user'] = $user;
            $data['APPLICATION_ID']=$APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'],$APPLICATION_ID);

            if($application){
                $form_fees = $this->Admission_session_model->getFormFeesBySessionAndCampusId($application['SESSION_ID'],$application['CAMPUS_ID']);
                if($form_fees){
                    $valid_upto = getDateCustomeView($application['ADMISSION_END_DATE'],'d-m-Y');

                    if ($application['ADMISSION_END_DATE']<date('Y-m-d'))
                    {
                        exit("Sorry your challan is expired..");
                    }


                    $row = array(
                        'CNIC_NO' => $user['CNIC_NO'],
                        'APPLICATION_ID' => $application['APPLICATION_ID'],
                        'CHALLAN_NO' => $application['FORM_CHALLAN_ID'],
                        "FIRST_NAME" => $user['FIRST_NAME'],
                        "CANDIDATE_SURNAME" => $user['LAST_NAME'],
                        "CANDIDATE_FNAME" => $user['FNAME'],
                        "CANDIDATE_NAME" => $user['FIRST_NAME'],
                        "TOTAL_AMOUNT" => $form_fees['AMOUNT'],
                        "CATEGORY_NAME" => "ADMISSION FORM",
                        "VALID_UPTO" => $valid_upto,
                        "ACCOUNT_NO" => $form_fees['ACCOUNT_NO'],
                        "ACCOUNT_TITLE" => $form_fees['ACCOUNT_TITLE'],
                        "CANDIDATE_ID" => $user['USER_ID'],
                        "DEGREE_PROGRAM" => $application['PROGRAM_TITLE']
                    );
                    $data['row'] = $row;
                    $data['roll_no'] = $user['USER_ID'];
                    $this->load->view('admission_form_challan', $data);

                }else{
                    echo "fees not found";
                }

            }else{
                echo "this application id is not associate with you";
            }

        }else{
            echo "Application Id Not Found";
        }


    }

    public function upload_application_challan(){

        if($this->session->has_userdata('APPLICATION_ID')) {
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');
            $user = $this->session->userdata($this->SessionName);
            $user = $this->User_model->getUserById($user['USER_ID']);

            $data['user'] = $user;
            $data['APPLICATION_ID'] = $APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);
            $bank_branches = $this->Admission_session_model->getAllBankInformation();
            if ($application) {
                $form_fees = $this->Admission_session_model->getFormFeesBySessionAndCampusId($application['SESSION_ID'], $application['CAMPUS_ID']);
                if ($form_fees) {
                    $valid_upto = getDateCustomeView($application['ADMISSION_END_DATE'], 'd-m-Y');

                    if ($application['ADMISSION_END_DATE'] < date('Y-m-d')) {
                        exit("Sorry your challan is expired..");
                    }


                    $data['profile_url'] = base_url() . $this->profile;
                    $data['bank_branches'] = $bank_branches;
                    $data['application'] = $application;

                    $data['roll_no'] = $user['USER_ID'];
                    $this->load->view('include/header', $data);
                    $this->load->view('include/preloder');
                    $this->load->view('include/side_bar', $data);
                    $this->load->view('include/nav', $data);
                    $this->load->view('upload_challan_detail', $data);
                    $this->load->view('include/footer_area', $data);
                    $this->load->view('include/footer', $data);


                } else {
                    echo "fees not found";
                }

            } else {
                echo "this application id is not associate with you";
            }
        }
        else{
            echo "Application Id Not Found";
        }
    }

    public function challan_upload_handler(){

        $user = $this->user ;
        $USER_ID = $user['USER_ID'];
        $is_upload_any_doc = false;
        $config_a = array();
        $config_a['maintain_ratio'] = true;
        $config_a['width']         = 360;
        $config_a['height']       = 500;
        $config_a['resize']       = false;
        $error = "";
        $challan_image ="";
        $CHALLAN_AMOUNT =$BRANCH_ID = 0;
        $CHALLAN_PAID_DATE='0000-00-00';
        $APPLICATION_ID = 0;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if($this->session->has_userdata('APPLICATION_ID')) {
                $APPLICATION_ID_SESSION = $this->session->userdata('APPLICATION_ID');
            }else{
                $error.="<div class='text-danger'>Application Id not found in Session</div>";
            }
            if(isset($_POST['APPLICATION_ID'])&&isValidData($_POST['APPLICATION_ID'])){
                $APPLICATION_ID =isValidData($_POST['APPLICATION_ID']);
                if($APPLICATION_ID_SESSION!=$APPLICATION_ID){
                    $error.="<div class='text-danger'>Application Id Missmatch</div>";
                }
            }
            else{
                $error.="<div class='text-danger'>Application Id not found</div>";
            }
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'],$APPLICATION_ID);
            if($application) {
                $FORM_CHALLAN_ID = $application['FORM_CHALLAN_ID'];
                $challan_image = $application['CHALLAN_IMAGE'];
                $CHALLAN_PAID_DATE = $application['CHALLAN_DATE'];
                $CHALLAN_AMOUNT = $application['PAID_AMOUNT'];
                $BRANCH_ID = $application['BRANCH_ID'];
                if($application['IS_SUBMITTED']=="N"){

                }else{
                    $error.="<div class='text-danger'>Your Form has been Submitted thats why you can't change the challan information</div>";
                }
                   // $valid_upto = getDateCustomeView($application['ADMISSION_END_DATE'], 'd-m-Y');



                    if ($application['ADMISSION_END_DATE'] < date('Y-m-d')) {

                        $error.="<div class='text-danger'>Sorry your challan is expired..</div>";
                    }else {
                        $folder = EXTRA_IMAGE_CHECK_PATH . "$USER_ID";
                        if (!is_dir($folder)) {
                            mkdir(EXTRA_IMAGE_CHECK_PATH . "/$USER_ID");
                        }


                        if(isset($_POST['BRANCH_ID'])&&isValidData($_POST['BRANCH_ID'])){
                            $BRANCH_ID =isValidData($_POST['BRANCH_ID']);
                        }else{
                            $error.="<div class='text-danger'>Bank Branch must be select</div>";
                        }
                        if(isset($_POST['CHALLAN_AMOUNT'])&&isValidData($_POST['CHALLAN_AMOUNT'])){
                            $CHALLAN_AMOUNT =isValidData($_POST['CHALLAN_AMOUNT']);
                            if($CHALLAN_AMOUNT != $application['CHALLAN_AMOUNT']){
                                $error.="<div class='text-danger'>Your entered amount does not match actual challan amount </div>";
                            }

                        }else{
                            $error.="<div class='text-danger'>Challan Amount Must be Enter</div>";
                        }
                        if(isset($_POST['CHALLAN_NO'])&&isValidData($_POST['CHALLAN_NO'])){
                            $CHALLAN_NO =isValidData($_POST['CHALLAN_NO']);
                            if($CHALLAN_NO!=$FORM_CHALLAN_ID){
                                $error.="<div class='text-danger'>Invalid Challan No..!</div>";
                            }
                        }else{
                            $error.="<div class='text-danger'>Challan Number Must be Enter</div>";
                        }

                        if(isset($_POST['CHALLAN_PAID_DATE'])&&isValidTimeDate($_POST['CHALLAN_PAID_DATE'],'d/m/Y')){
                            $CHALLAN_PAID_DATE = getDateForDatabase($_POST['CHALLAN_PAID_DATE']);
                            if($CHALLAN_PAID_DATE>date('Y-m-d')){
                                $error.="<div class='text-danger'>Choose Valid Challan Paid Date</div>";
                            }
                        }else{
                            $error.="<div class='text-danger'>Challan Paid Date Must be Choose</div>";
                        }


                        if (isset($_FILES['challan_image'])) {
                            if (isValidData($_FILES['challan_image']['name'])) {

                                $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                                $image_name = "challan_image_$USER_ID";
                                $res = $this->upload_image('challan_image', $image_name, $this->file_size, $file_path, $config_a);
                                if ($res['STATUS'] === true) {
                                    $challan_image = "$USER_ID/" . $res['IMAGE_NAME'];
                                    $is_upload_any_doc = true;

                                } else {
                                    $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                                }
                            } else {
                                if ($challan_image == "")
                                    $error .= "<div class='text-danger'>Must Upload Challan Image and image size must be less then {$this->file_size}kb </div>";
                            }
                        }
                        else {

                            if ($challan_image == "")
                                $error .= "<div class='text-danger'>Must Upload Challan Image and image size must be less then {$this->file_size}kb </div>";
                        }

                        if($error==""){
                            $form_data=array("BRANCH_ID"=>$BRANCH_ID,
                                "CHALLAN_DATE"=>$CHALLAN_PAID_DATE,
                                "PAID_AMOUNT"=>$CHALLAN_AMOUNT,
                                "CHALLAN_IMAGE"=>$challan_image,
                                "PAID"=>"N",
                                "USER_ID"=>$USER_ID);
                            $res = $this->Application_model->updateChallanById($FORM_CHALLAN_ID,$form_data);
                            if($res==1){

                                $APPLICATION_ID = base64_encode($APPLICATION_ID);
                                $APPLICATION_ID = urlencode($APPLICATION_ID);
                                $success= "<div class='text-success'>Challan Information Update Successfully</div>";
                                $alert = array('MSG'=>$success,'TYPE'=>'SUCCESS');
                                $this->session->set_flashdata('ALERT_MSG',$alert);
                                redirect(base_url()."form/upload_application_challan/$APPLICATION_ID");

                            }else if($res==0){

                                $APPLICATION_ID = base64_encode($APPLICATION_ID);
                                $APPLICATION_ID = urlencode($APPLICATION_ID);
                                if($is_upload_any_doc){
                                    $success= "<div class='text-success'>Challan Information Update Successfully</div>";
                                }else{
                                    $success= "<div class='text-success'>No data has been changed...! </div>";
                                    $success= "<div class='text-success'>Challan Information Update Successfully...!</div>";
                                }

                                $alert = array('MSG'=>$success,'TYPE'=>'SUCCESS');
                                $this->session->set_flashdata('ALERT_MSG',$alert);
                                redirect(base_url()."form/upload_application_challan/$APPLICATION_ID");

                            }else{

                                $APPLICATION_ID = base64_encode($APPLICATION_ID);
                                $APPLICATION_ID = urlencode($APPLICATION_ID);
                                $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
                                $this->session->set_flashdata('ALERT_MSG',$alert);
                                redirect(base_url()."form/upload_application_challan/$APPLICATION_ID");

                            }
                        }
                    }


            }else{
                    $error.="<div class='text-danger'>This Application is not associate with you</div>";
            }

        }
        else{
            $error.="<div class='text-danger'>Invalid Request</div>";
        }
        if($error!=""){
            $APPLICATION_ID = base64_encode($APPLICATION_ID);
            $APPLICATION_ID = urlencode($APPLICATION_ID);
            $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
            $this->session->set_flashdata('ALERT_MSG',$alert);
            redirect(base_url()."form/upload_application_challan/$APPLICATION_ID");
        }


    }

    public function upload_minor_subjects(){
        $error = "";
        $success = true;
        $success_msg = "";
        $user = $this->session->userdata($this->SessionName);

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            if ($this->session->has_userdata('APPLICATION_ID')) {
                $APPLICATION_ID_SESSION = $this->session->userdata('APPLICATION_ID');
            } else {
                $error .= "<div class='text-danger'>Application Id not found in Session</div>";
            }
            if(isset($_POST['DISCIPLINE_ID'])&&isValidData($_POST['DISCIPLINE_ID'])){
                $DISCIPLINE_ID =isValidData($_POST['DISCIPLINE_ID']);
            }else{
                $error.="<div class='text-danger'>Discipline Id Not found</div>";
            }

            if(isset($_POST['minor_subject_array'])&&is_array($_POST['minor_subject_array'])&&count($_POST['minor_subject_array'])>0&&$error==""){

                $minor_subject_array = $_POST['minor_subject_array'];
                $delete_result = $this->Application_model->deleteApplicantsMinorsByUserIdAndDisciplineId($user['USER_ID'],$DISCIPLINE_ID);
                if($delete_result>0) {
                    foreach ($minor_subject_array as $MINOR_MAPPING_ID) {

                        //$is_exist = $this->Application_model->getApplicantsMinorsByUserIdAndMinorMappingId($user['USER_ID'],$MINOR_MAPPING_ID);

                        $applicants_minnor = array(
                            "APPLICATION_ID" => $APPLICATION_ID_SESSION,
                            "DISCIPLINE_ID" => $DISCIPLINE_ID,
                            "MINOR_MAPPING_ID" => $MINOR_MAPPING_ID,
                            "USER_ID" => $user['USER_ID'],
                            "ACTIVE" => 1
                        );
                        $is_add = $this->Application_model->addApplicantsMinors($applicants_minnor);
                        if ($is_add) {
                            $success_msg .= "<div class='text-success'>Successfully added $MINOR_MAPPING_ID</div>";
                            //success add
                        } else {
                            $success = false;
                            $error .= "<div class='text-danger'>Something went wrong in minor id $MINOR_MAPPING_ID</div>";
                            // something went wrong
                        }

                    }
                }else{
                    $error .= "<div class='text-danger'>Something went wrong delete previous minor</div>";
                }


            }else{
                $error .= "<div class='text-danger'>Must select at least one subject </div>";
            }

        }else{
            $error .= "<div class='text-danger'>Invalid request upload_minor_subjects</div>";

        }
        if($error){
            $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
            $this->session->set_flashdata('ALERT_MSG',$alert);
        }else{
            $success_msg .= "<div class='text-success'>your subject add successfully</div>";
            $alert = array('MSG'=>$success_msg,'TYPE'=>'SUCCESS');
            $this->session->set_flashdata('ALERT_MSG',$alert);
        }
        redirect(base_url()."form/select_subject");

    }

    public function select_subject(){

        if($this->session->has_userdata('APPLICATION_ID')) {
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');

            $user = $this->session->userdata($this->SessionName);
            $user = $this->User_model->getUserById($user['USER_ID']);

            $data['user'] = $user;
            $data['APPLICATION_ID'] = $APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);

            if ($application) {

                $form_data = $this->User_model->getUserFullDetailById($user['USER_ID']);

                $degree_list = array(
                    'BACHELOR'=>array('PROGRAM_TYPE_ID'=>1,'DEGREE_ID'=>3),
                    'MASTER'=>array('PROGRAM_TYPE_ID'=>2,'DEGREE_ID'=>4)
                );

                //$form_data = json_decode($application['FORM_DATA'],true);
                $bool = false;
                $valid_qualification = null;
                if($application['PROGRAM_TYPE_ID']==$degree_list['BACHELOR']['PROGRAM_TYPE_ID']){
                    // echo "bach";
                    foreach ($form_data['qualifications'] as $qualification){
                        if($qualification['DEGREE_ID'] ==$degree_list['BACHELOR']['DEGREE_ID']){
                            $bool  = true;
                            $valid_qualification = $qualification;
                            break;
                        }
                    }


                }else if($application['PROGRAM_TYPE_ID']==$degree_list['MASTER']['PROGRAM_TYPE_ID']){
                    //echo "master";
                    //4
                    // prePrint($form_data['qualifications']);
                    foreach ($form_data['qualifications'] as $qualification){
                        if($qualification['DEGREE_ID'] ==$degree_list['MASTER']['DEGREE_ID']){
                            $bool  = true;
                            $valid_qualification = $qualification;
                            break;
                        }
                    }
                }
                //$from_data = json_encode($from_data);


                $form_fees = $this->Admission_session_model->getFormFeesBySessionAndCampusId($application['SESSION_ID'], $application['CAMPUS_ID']);

                if ($form_fees) {
                    $valid_upto = getDateCustomeView($application['ADMISSION_END_DATE'], 'd-m-Y');

                    if ($application['ADMISSION_END_DATE'] < date('Y-m-d')) {
                        exit("Sorry your challan is expired..");
                    }


                    $data['profile_url'] = base_url() . $this->profile;
//                    $data['is_valid_qualification'] = $bool;
//                    $data['form_data'] = $form_data;
                    //$data['application'] = $application;
                    if($bool&&$valid_qualification!=null){

                        $result = $this->Application_model->getMinorMappingByDisciplineId($valid_qualification['DISCIPLINE_ID']);

                        if($result!=null && count($result)==1){
                        //prePrint($result);
                            $result =$result[0];

                            $is_exist = $this->Application_model->getApplicantsMinorsByUserIdAndMinorMappingId($user['USER_ID'],$result['MINOR_MAPPING_ID']);
                            if(count($is_exist)==0) {
                                $applicants_minnor = array(
                                    "APPLICATION_ID" => $APPLICATION_ID,
                                    "DISCIPLINE_ID" => $result['DISCIPLINE_ID'],
                                    "MINOR_MAPPING_ID" => $result['MINOR_MAPPING_ID'],
                                    "USER_ID" => $user['USER_ID'],
                                    "ACTIVE" => 1
                                );
                                $is_add = $this->Application_model->addApplicantsMinors($applicants_minnor);
                                if ($is_add) {
                                    echo "Minor Automatic Added";
                                } else{
                                    echo "ByDefault Minor Not added";
                                }

                            }else{
                                echo "Already selected minors";
                            }

                        }
                        else if($result!=null && count($result)>1){
                            $data['minors'] = $result;
                            $data['DISCIPLINE_ID'] = $valid_qualification['DISCIPLINE_ID'];
                            $data['applicantsMinors'] = $this->Application_model->getApplicantsMinorsByUserIdAndDisciplineID($user['USER_ID'],$valid_qualification['DISCIPLINE_ID']);
                            $data['PROGRAM_TYPE_ID'] =$application['PROGRAM_TYPE_ID'];
                           // $data['roll_no'] = $user['USER_ID'];
                            $this->load->view('include/header', $data);
                            $this->load->view('include/preloder');
                            $this->load->view('include/side_bar', $data);
                            $this->load->view('include/nav', $data);
                            $this->load->view('select_minor_subject', $data);
                            $this->load->view('include/footer_area', $data);
                            $this->load->view('include/footer', $data);
                        }else{
                            echo "minors not found";
                        }
                    }else{
                        echo "Invalid Degree Please must add appropriate degree";
                    }
                   // prePrint($application);



                } else {
                    echo "fees not found";
                }

            } else {
                echo "this application id is not associate with you";
            }
        }else{
            echo "Application Id Not Found";
        }
    }

    public function select_program(){

        if($this->session->has_userdata('APPLICATION_ID')) {
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');

            $user = $this->session->userdata($this->SessionName);
            $user = $this->User_model->getUserById($user['USER_ID']);

            $data['user'] = $user;
            $data['APPLICATION_ID'] = $APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);

            if ($application) {

                $form_data = $this->User_model->getUserFullDetailById($user['USER_ID']);

                $degree_list = array(
                    'BACHELOR'=>array('PROGRAM_TYPE_ID'=>1,'DEGREE_ID'=>3),
                    'MASTER'=>array('PROGRAM_TYPE_ID'=>2,'DEGREE_ID'=>4)
                );

                //$form_data = json_decode($application['FORM_DATA'],true);
                $bool = false;
                $valid_qualification = null;
                if($application['PROGRAM_TYPE_ID']==$degree_list['BACHELOR']['PROGRAM_TYPE_ID']){
                    // echo "bach";
                    foreach ($form_data['qualifications'] as $qualification){
                        if($qualification['DEGREE_ID'] ==$degree_list['BACHELOR']['DEGREE_ID']){
                            $bool  = true;
                            $valid_qualification = $qualification;
                            break;
                        }
                    }


                }else if($application['PROGRAM_TYPE_ID']==$degree_list['MASTER']['PROGRAM_TYPE_ID']){
                    //echo "master";
                    //4
                    // prePrint($form_data['qualifications']);
                    foreach ($form_data['qualifications'] as $qualification){
                        if($qualification['DEGREE_ID'] ==$degree_list['MASTER']['DEGREE_ID']){
                            $bool  = true;
                            $valid_qualification = $qualification;
                            break;
                        }
                    }
                }
                //$from_data = json_encode($from_data);


                $form_fees = $this->Admission_session_model->getFormFeesBySessionAndCampusId($application['SESSION_ID'], $application['CAMPUS_ID']);

                if ($form_fees) {
                    $valid_upto = getDateCustomeView($application['ADMISSION_END_DATE'], 'd-m-Y');

                    if ($application['ADMISSION_END_DATE'] < date('Y-m-d')) {
                        exit("Sorry your challan is expired..");
                    }


                    $data['profile_url'] = base_url() . $this->profile;
//                    $data['is_valid_qualification'] = $bool;
//                    $data['form_data'] = $form_data;
                    //$data['application'] = $application;
                    if($bool&&$valid_qualification!=null){

                      //  $result = $this->Application_model->getMinorMappingByDisciplineId($valid_qualification['DISCIPLINE_ID']);


                      
                            $data['DISCIPLINE_ID'] = $valid_qualification['DISCIPLINE_ID'];

                            $applicantsMinors = $this->Application_model->getApplicantsMinorsByUserIdAndDisciplineID($user['USER_ID'],$valid_qualification['DISCIPLINE_ID']);
                            $minorMappingIds  = array();
                            foreach ($applicantsMinors as $applicantsMinor)
                            {
                                $minorMappingIds[]=$applicantsMinor['MINOR_MAPPING_ID'];
                            }
                            $valid_program_list = $this->Prerequisite_model->getPrerequisiteByMinorMappingIdList($minorMappingIds);
                            $program_list = $this->Administration->getProgramByTypeID($application['PROGRAM_TYPE_ID']);
                            $data['VALID_PROGRAM_LIST'] =$valid_program_list;
                            $data['PROGRAM_LIST'] =$program_list;
                            $data['PROGRAM_TYPE_ID'] =$application['PROGRAM_TYPE_ID'];
                       //     prePrint($valid_program_list);
                       // prePrint($program_list);
//                            exit();
                            // $data['roll_no'] = $user['USER_ID'];
                            $this->load->view('include/header', $data);
                            $this->load->view('include/preloder');
                            $this->load->view('include/side_bar', $data);
                            $this->load->view('include/nav', $data);
                            $this->load->view('select_program', $data);
                            $this->load->view('include/footer_area', $data);
                            $this->load->view('include/footer', $data);

                    }else{
                        echo "Invalid Degree Please must add appropriate degree";
                    }
                    // prePrint($application);



                } else {
                    echo "fees not found";
                }

            } else {
                echo "this application id is not associate with you";
            }
        }else{
            echo "Application Id Not Found";
        }
    }

    public function set_application_id($APPLICATION_ID,$url){
        $APPLICATION_ID = base64_decode(urldecode($APPLICATION_ID));
	    $this->session->set_userdata('APPLICATION_ID', $APPLICATION_ID);
        $url = base_url() . base64_decode(urldecode($url));
        redirect($url);
        exit();
    }

    public function lock_form(){
        if($this->session->has_userdata('APPLICATION_ID')){
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');
            $user = $this->session->userdata($this->SessionName);
            //prePrint($user);
            $user_fulldata = $this->User_model->getUserFullDetailById($user['USER_ID']);

            $data['user'] = $user_fulldata;
            $data['APPLICATION_ID'] = $APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);


            if ($application) {

                //prePrint($application);
                if($application['IS_SUBMITTED']) {


                    $error = $this->isValidProfileInformation($user_fulldata, $application);


                    //prePrint($error);
                    if ($error == "") {
                        if ($application['PAID'] == 'N' && isValidData($application['CHALLAN_IMAGE'])) {

                            $this->Application_model->lock_form($APPLICATION_ID, $user_fulldata);
                            echo "Your form has been submited...!";

                        } else {
                            $error .= "<div class='text-danger'>Challan image not found</div>";
                        }


                    } else {
                        $alert = array('MSG' => $error, 'TYPE' => 'ERROR');
                        $this->session->set_flashdata('ALERT_MSG', $alert);
                        redirect(base_url() . "form/upload_application_challan");
                        //prePrint($error);
                    }
                }else{
                    echo "Your form Already submited...!";
                }


            }else{
                $alert = array('MSG'=>"<div class='text-danger'>Application Not found </div>",'TYPE'=>'ERROR');
                $this->session->set_flashdata('ALERT_MSG',$alert);
                redirect(base_url()."form/announcement");
            }
        }
        else {
            redirect(base_url() . "login");
        }
    }

    public function check_validation_and_challan(){
        if($this->session->has_userdata('APPLICATION_ID')){
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');
            $user = $this->session->userdata($this->SessionName);
            //prePrint($user);
            $user_fulldata = $this->User_model->getUserFullDetailById($user['USER_ID']);

            $data['user'] = $user_fulldata;
            $data['APPLICATION_ID'] = $APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);

            if ($application) {

                //prePrint($application);

                $error = $this->isValidProfileInformation($user_fulldata,$application);


                //prePrint($error);
                if($error==""){
                    if($application['PAID']=='N'&&isValidData($application['CHALLAN_IMAGE'])){
                        $next_page = "lock_form";
                        $next_page = base64_encode($next_page);
                        $next_page =urlencode($next_page);

                        redirect(base_url() . "form/review/$next_page");
                    }else{
                        $error.="<div class='text-danger'>Bank Branchnch not found</div>";
                        $error.="<div class='text-danger'>Challan image not found</div>";
                        $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
                        $this->session->set_flashdata('ALERT_MSG',$alert);
                        redirect(base_url()."form/upload_application_challan");
                    }


                }else{
                    $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
                    $this->session->set_flashdata('ALERT_MSG',$alert);
                    redirect(base_url()."form/upload_application_challan");
                    //prePrint($error);
                }


            }else{
                $alert = array('MSG'=>"<div class='text-danger'>Application Not found </div>",'TYPE'=>'ERROR');
                $this->session->set_flashdata('ALERT_MSG',$alert);
                redirect(base_url()."form/announcement");
            }
        }else {
            redirect(base_url() . "login");
        }
    }

    public function check_validation(){
            if($this->session->has_userdata('APPLICATION_ID')){
                $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');
                $user = $this->session->userdata($this->SessionName);
                //prePrint($user);
                $user_fulldata = $this->User_model->getUserFullDetailById($user['USER_ID']);

                $data['user'] = $user_fulldata;
                $data['APPLICATION_ID'] = $APPLICATION_ID;
                $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);

                if ($application) {

                    //prePrint($application);

                    $error = $this->isValidProfileInformation($user_fulldata,$application);


//                   prePrint($error);
//                   exit();
                   if($error==""){
                       $next_page = "upload_application_challan";

                       $next_page = base64_encode($next_page);
                       $next_page =urlencode($next_page);
                       redirect(base_url() . "form/review/$next_page");
                       //redirect(base_url() . "form/");

                   }else{
                       $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
                       $this->session->set_flashdata('ALERT_MSG',$alert);
                       redirect(base_url()."candidate/profile");
                      // prePrint($error);
                   }


                }
            }else {
                redirect(base_url() . "login");
            }
    }

    public function dashboard(){
        if($this->session->has_userdata('APPLICATION_ID')){
            $APPLICATION_ID = $this->session->userdata('APPLICATION_ID');
            $user = $this->session->userdata($this->SessionName);
            //prePrint($user);
            $user_fulldata = $this->User_model->getUserFullDetailById($user['USER_ID']);
            $data['profile_url'] = base_url().$this->profile;
            $data['user'] = $user_fulldata;
            $data['APPLICATION_ID'] = $APPLICATION_ID;
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'], $APPLICATION_ID);
            $data['user_application_list'] = $this->Application_model->getApplicationByUserId($user['USER_ID']);

            if ($application) {

//                $error = $this->isValidProfileInformation($user_fulldata,$application);
//               if($error==""){
//                   $data['basic_profile'] = 100;
//               }else{
//                   substr_count($error, '<div>', 3);
//               }
                    //prePrint($application);
                    $this->load->view('include/header',$data);
                $this->load->view('include/preloder');
                $this->load->view('include/side_bar',$data);
                $this->load->view('include/nav',$data);
                $this->load->view('dashboard',$data);
                $this->load->view('include/footer_area',$data);
                $this->load->view('include/footer',$data);


            }else{
                $alert = array('MSG'=>"<div class='text-danger'>Application Not found </div>",'TYPE'=>'ERROR');
                $this->session->set_flashdata('ALERT_MSG',$alert);
                redirect(base_url()."form/announcement");
            }
        }
        else {
            redirect(base_url() . "login");
        }
    }

    private function getValidationArray($application){
        $must_provide = "Must Be Provided";
        $must_select = "Must Be Provided";
        $must_upload = "Must Upload";

        $qualification = array();
        if($application['PROGRAM_TYPE_ID']==1){
        $qualification =  $bachelor = array(2,3);
        $qualification_error_msg =  $bachelor_error_msg = array("Matriculation Degree Missing Must add","Intermediate Degree Missing Must add");

        }else if($application['PROGRAM_TYPE_ID']==2){
            $qualification =  $master_id = array(2,3,4);
            $qualification_error_msg =  $master_error_msg = array("Matriculation Degree Missing Must add","Intermediate Degree Missing Must add","Bachelor 14 Year / BA / BSC / BCOM Degree Missing Must add");
        }

        $validation_array=array(
                "users_reg" =>array(
                    "FIRST_NAME"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Full Name $must_provide as per Matriculation"),
                    "LAST_NAME"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Surname $must_provide"),
                    "FNAME"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Father $must_provide"),
                    "GENDER"=>array("regex"=>"[A-Za-z]{1}","error_msg"=>"Gender $must_select"),
                    "MOBILE_NO"=>array("regex"=>"[0-9]{10}","error_msg"=>"Mobile Number $must_provide"),
                    "HOME_ADDRESS"=>array("regex"=>"[A-Za-z0-9\-\\,.]+","error_msg"=>"Home Address $must_provide"),
                    "PERMANENT_ADDRESS"=>array("regex"=>"[A-Za-z0-9\-\\,.]+","error_msg"=>"Parmanent Address $must_provide"),
                    "DATE_OF_BIRTH"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Date of Birth $must_provide"),
                    "BLOOD_GROUP"=>array("regex"=>"^(A|B|AB|O)[+-]$","error_msg"=>"Blood Group $must_select"),
                    "MOBILE_CODE"=>array("regex"=>"[0-9]{4}","error_msg"=>"Mobile $must_select"),
                    "COUNTRY_ID"=>array("regex"=>"[0-9]","error_msg"=>"Country $must_select"),
                    "PROVINCE_ID"=>array("regex"=>"[0-9]","error_msg"=>"Province $must_select"),
                    "DISTRICT_ID"=>array("regex"=>"[0-9]","error_msg"=>"District $must_select"),
                    "PROFILE_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Profile Image $must_upload"),
                    "DOMICILE_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Domicile Image $must_upload"),
                    "DOMICILE_FORM_C_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Domicile Form C Image $must_upload"),
                    "EMAIL"=>array("regex"=>"^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$","error_msg"=>"Email $must_provide"),
                    "RELIGION"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Religion $must_provide"),
                    "U_R"=>array("regex"=>"^\w{1}$","error_msg"=>"Area $must_select"),

                ),
                "CNIC"=>array(
                    "CNIC_NO"=>array("regex"=>"[0-9]{13}","error_msg"=>"CNIC No $must_provide"),
                    "CNIC_FRONT_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"CNIC Front / B-Form Image $must_upload"),
                    "CNIC_BACK_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"CNIC Back / B-Form  Image $must_upload"),

                ),
                "PASSPORT"=>array(
                    "PASSPORT_NO"=>array("regex"=>"[0-9]{13}","error_msg"=>"Passport No $must_provide"),
                    "PASSPORT_FRONT_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Passport Front Image $must_upload"),
                    "PASSPORT_BACK_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Passport Back / B-Form Image $must_upload"),
                ),
                "guardian"=>array(
                    "FIRST_NAME"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Guardian Name $must_provide"),
                    "RELATIONSHIP"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Relationship Name $must_select"),
                    "MOBILE_CODE"=>array("regex"=>"[0-9]{4}","error_msg"=>"Guardian Mobile Code $must_select"),
                    "MOBILE_NO"=>array("regex"=>"[0-9]{10}","error_msg"=>"Guardian Mobile Number $must_provide"),
                    "HOME_ADDRESS"=>array("regex"=>"[A-Za-z0-9\-\\,.]+","error_msg"=>"Home Address $must_provide"),
                ),
                "qualifications"=>array(
                    "DEGREE_ID" =>$qualification,
                    "DEGREE_ID_MSG" =>$qualification_error_msg
                )
            );
            return $validation_array;
    }

    private function isValidProfileInformation($user_fulldata,$application){
        //calling private method get validationArray
        $validation_array = $this->getValidationArray($application);

        $user_reg_validation = $validation_array['users_reg'];
        $guardian_validation = $validation_array['guardian'];
        $qualifications_validation = $validation_array['qualifications'];
        $qualification_error_msg = $validation_array['qualifications']['DEGREE_ID_MSG'];

        $users_reg = $user_fulldata['users_reg'];
        $guardian = $user_fulldata['guardian'];
        $qualifications = $user_fulldata['qualifications'];


        if($users_reg['IS_CNIC_PASS']=='P'){
            $user_reg_validation = array_merge($user_reg_validation,$validation_array['PASSPORT']);
        }else{
            $user_reg_validation = array_merge($user_reg_validation,$validation_array['CNIC']);
        }

        $error = "";
        foreach($user_reg_validation as $column=>$value){


            if(preg_match("/".$value['regex']."/", $users_reg[$column])){

            }else{
                $error.="<div class='text-danger'>{$value['error_msg']}</div>";

            }
        }

        foreach($guardian_validation as $column=>$value){


            if(preg_match("/".$value['regex']."/", $guardian[$column])){

            }else{
                $error.="<div class='text-danger'>{$value['error_msg']}</div>";
            }
        }
        foreach($qualifications as $qual){

            foreach($qualifications_validation['DEGREE_ID'] as $k=>$val){
                if($qual['DEGREE_ID']==$val){
                    unset($qualifications_validation['DEGREE_ID'][$k]);
                    unset($qualification_error_msg[$k]);

                    break;
                }
            }
        }
        foreach ($qualification_error_msg as $error_msg){
            $error.="<div class='text-danger'>{$error_msg}</div>";
        }
        return $error;
        //prePrint($qualification_error_msg);

    }
    private function upload_image($index_name,$image_name,$max_size = 50,$path = '../eportal_resource/images/applicants_profile_image/',$con_array=array())
    {

        $config['upload_path']          = $path;
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = $max_size;
        $config['max_width']            = 0;
        $config['max_height']           = 0;
        $config['file_name']			= $image_name;
        $config['overwrite']			= true;

        if(isset($this->upload)){
            $this->upload =  null;
        }
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($index_name))
        {
            return array("STATUS"=>false,"MESSAGE"=>$this->upload->display_errors());
        }
        else
        {
            $image_data = $this->upload->data();

            $image_path = $image_data['full_path'];

            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['create_thumb'] = FALSE;
            if(!count($con_array)){
                $config['maintain_ratio'] = TRUE;
                $config['width']         = 180;
                $config['height']       = 260;
            }else{
                if(isset($con_array['maintain_ratio'])){
                    $config['maintain_ratio']=$con_array['maintain_ratio'];
                }

                if(isset($con_array['width'])){
                    $config['width']=$con_array['width'];
                }

                if(isset($con_array['height'])){
                    $config['height']=$con_array['height'];
                }
            }
            if(isset($this->image_lib)){
                $this->image_lib =  null;
            }
            if(isset($con_array['resize'])){
                if($con_array['resize']===true){
                    $this->load->library('image_lib',$config);

                    $this->image_lib->resize();
                }
            }else{
                $this->load->library('image_lib',$config);

                $this->image_lib->resize();

            }

            return array("STATUS"=>true,"IMAGE_NAME"=>$image_data['file_name']);

        }
    }

}
