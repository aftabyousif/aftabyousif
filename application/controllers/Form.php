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

	public function review($APPLICATION_ID,$next_page)
	{
	    $APPLICATION_ID =urldecode($APPLICATION_ID);
        $APPLICATION_ID = base64_decode($APPLICATION_ID);
        $next_page =urldecode($next_page);
        $next_page = base64_decode($next_page);

        $user = $this->user ;
        $user_id = $user['USER_ID'];

        $user_data = $this->User_model->getUserFullDetailById($user_id);
prePrint($user_data);
        $data['user'] = $user_data['users_reg'];
        $data['qualifications'] = $user_data['qualifications'];


			$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//		$this->load->view('include/nav',$data);
			$this->load->view('form_review',$data);
//		$this->load->view('include/footer_area');
			$this->load->view('include/footer');



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
                                    $url = base_url() . "form/admission_form_challan/$APPLICATION_ID";
                                    $this->session->set_flashdata('OPEN_TAB', $url);
                                    redirect(base_url() . "candidate/profile");
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

    public function admission_form_challan($APPLICATION_ID){
        $APPLICATION_ID =urldecode($APPLICATION_ID);
        $APPLICATION_ID = base64_decode($APPLICATION_ID);
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


                        $row = array('CHALLAN_NO' => $application['FORM_CHALLAN_ID'],
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

    }

    public function upload_application_challan($APPLICATION_ID){
        $APPLICATION_ID =urldecode($APPLICATION_ID);
        $APPLICATION_ID = base64_decode($APPLICATION_ID);
        $user = $this->session->userdata($this->SessionName);
        $user = $this->User_model->getUserById($user['USER_ID']);

        $data['user'] = $user;
        $data['APPLICATION_ID']=$APPLICATION_ID;
        $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'],$APPLICATION_ID);
        $bank_branches = $this->Admission_session_model->getAllBankInformation();
        if($application){
            $form_fees = $this->Admission_session_model->getFormFeesBySessionAndCampusId($application['SESSION_ID'],$application['CAMPUS_ID']);
            if($form_fees){
                $valid_upto = getDateCustomeView($application['ADMISSION_END_DATE'],'d-m-Y');

                if ($application['ADMISSION_END_DATE']<date('Y-m-d'))
                {
                    exit("Sorry your challan is expired..");
                }



                $data['profile_url'] = base_url().$this->profile;
                $data['bank_branches'] = $bank_branches;
                $data['application'] = $application;

                $data['roll_no'] = $user['USER_ID'];
                $this->load->view('include/header',$data);
                $this->load->view('include/preloder');
                $this->load->view('include/side_bar',$data);
                $this->load->view('include/nav',$data);
                $this->load->view('upload_challan_detail',$data);
                $this->load->view('include/footer_area',$data);
                $this->load->view('include/footer',$data);


            }else{
                echo "fees not found";
            }

        }else{
            echo "this application id is not associate with you";
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
            if(isset($_POST['APPLICATION_ID'])&&isValidData($_POST['APPLICATION_ID'])){
                $APPLICATION_ID =isValidData($_POST['APPLICATION_ID']);
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
