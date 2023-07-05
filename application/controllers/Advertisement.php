<?php


class Advertisement extends CI_Controller
{
    private $SelfController = 'Advertisement';
    private $profile = 'candidate/profile';
    private $LoginController = 'login';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    private $user ;
    private $file_size = 500;

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
        $this->load->model("Api_location_model");
    }

	public function ug_advertisement ()
	{
        if($this->user['DISTRICT_ID']<=0){
            redirect(base_url()."Advertisement/select_district");
            exit();
        }
        redirect(base_url()."form/dashboard");
            exit();
		$data['user'] = $this->user;

		$data['profile_url'] = '';
		$APPLICATION_ID = 0;
        if($this->session->has_userdata('APPLICATION_ID')){
            $APPLICATION_ID = $this->session->has_userdata('APPLICATION_ID');
        }
        $data['APPLICATION_ID'] = $APPLICATION_ID;
		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//		$this->load->view('include/nav',$data);
		$this->load->view('ug_adv',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');

	}
	public function select_district(){
        if($this->user['DISTRICT_ID']>0){
            redirect(base_url()."Advertisement/ug_advertisement");
            exit();
        }

        $data['user'] =   $this->User_model->getUserByCnic($this->user['CNIC_NO']);;
        $data['profile_url'] = '';
        $countries =$this->Api_location_model->getAllCountry();
        $data['countries'] = $countries;
        $this->load->view('include/header',$data);
		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//	$this->load->view('include/nav',$data);
        $this->load->view('profile_section/select_district',$data);
//		$this->load->view('include/footer_area');
        $this->load->view('include/footer');
    }

    public function select_district_handler(){
        $user = $this->session->userdata($this->SessionName);
        if($user['DISTRICT_ID']>0){
            redirect(base_url()."Advertisement/ug_advertisement");
            exit();
        }




            $error = "";
//        if(isset($_POST['COUNTRY_ID'])&&isValidData($_POST['COUNTRY_ID'])){
//            $COUNTRY_ID = isValidData($_POST['COUNTRY_ID']);
//        }else{
//            //$error.="<div class='text-danger'>Country Must be Select</div>";
//        }
        if(isset($_POST['PROVINCE_ID'])&&isValidData($_POST['PROVINCE_ID'])){
            $PROVINCE_ID = isValidData($_POST['PROVINCE_ID']);
        }else{
              $error.="<div class='text-danger'>Province Must be Select</div>";
        }
        if(isset($_POST['DISTRICT_ID'])&&isValidData($_POST['DISTRICT_ID'])){
            $DISTRICT_ID = isValidData($_POST['DISTRICT_ID']);
        }else{
            $error.="<div class='text-danger'>District Must be Select</div>";
        }

            if($error==""){

                $form_array = array("PROVINCE_ID"=>$PROVINCE_ID,"DISTRICT_ID"=>$DISTRICT_ID);
                $res = $this->User_model->updateUserById($user['USER_ID'],$form_array);


                $user = $this->User_model->getUserById($user['USER_ID']);
                $session_data=$this->getSessionData($user);
                $this->session->set_userdata($this->SessionName, $session_data);

                if($res === -1){
                    $error = "<div class='text-danger'>Something went worng..!</div>";
                    $alert = array('MSG'=>$error,'TYPE'=>'ALERT');
                    $this->session->set_flashdata('ALERT_MSG',$alert);
                    redirect(base_url()."Advertisement/select_district");
                    exit();
                }
                if($res===0){
                    $error = "<div class='text-success'>No data has been changed..!<div>";

                    $alert = array('MSG'=>$error,'TYPE'=>'ALERT');
                    $this->session->set_flashdata('ALERT_MSG',$alert);
                    redirect(base_url()."Advertisement/ug_advertisement");
                    exit();
                }else{
                    $error = "<div class='text-success'>Your domicile's district is successfully updated..!<div>";

                    $alert = array('MSG'=>$error,'TYPE'=>'ALERT');
                    $this->session->set_flashdata('ALERT_MSG',$alert);
                    redirect(base_url()."Advertisement/ug_advertisement");
                    exit();
                }

            }
            else{

                $alert = array('MSG'=>$error,'TYPE'=>'ALERT');
                $this->session->set_flashdata('ALERT_MSG',$alert);
                redirect(base_url()."Advertisement/select_district");
                exit();
            }


    }
    private function getSessionData($user){
        $session_data =array('USER_ID'=>$user['USER_ID'],'FIRST_NAME'=>$user['FIRST_NAME'],'LAST_NAME'=>$user['LAST_NAME'],'EMAIL'=>$user['EMAIL'],'CNIC_NO'=>$user['CNIC_NO'],'PROFILE_IMAGE'=>$user['PROFILE_IMAGE'],'PASSPORT_NO'=>$user['PASSPORT_NO'],'DISTRICT_ID'=>$user['DISTRICT_ID']);

        return $session_data;
    }

}
