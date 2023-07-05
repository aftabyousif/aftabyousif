<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/10/2020
 * Time: 9:42 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class AdminLogin extends CI_Controller {
    /**
     * Login constructor.
     */
    private $SelfController = 'AdminLogin';
    private $HomeController = 'AdminPanel/search_student_by_cnic';
    protected $SessionName = 'ADMIN_LOGIN_FOR_ADMISSION';
	protected $user_role	= 'ADMISSION_ROLE';
	protected $generalBranch_mainpage = 'GeneralBranch/certificates';

    public function __construct()
    {
        parent::__construct();
		$this->load->model('Configuration_model');
    }

    /**
     * Login constructor.
     */

    function index(){

        $this->load->helper("form");
        $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
        $this->load->view('admin_login');
        $this->load->view('include/login_footer');
    }

	function set_admission_role ($user_admission_role) {
		$this->session->set_userdata($this->user_role, $user_admission_role[0]);
	}
    function adminLoginHandler(){
        $this->load->model('User_model');
        if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['cnic'])){
            $cnic =isValidData($this->input->post('cnic',TRUE));
            $password = isValidData($this->input->post('password',TRUE));
            $hashpassword = cryptPassowrd($password);
            if($cnic && $password){
                $user = $this->User_model->getUserByCnic($cnic);
                if($user) {                    
                    if(strcmp($hashpassword,$user['PASSWORD'])===0){
                        $userId=$user['USER_ID']; // recieved user_id, now pass this id to get and verify user_role.
                        $user_role_object = $this->User_model->getUserRoleByUserId($userId);
                        $user_admission_role = $this->User_model->getUserAdmissionRoleByUserId($userId);
                        if(!count($user_admission_role)){
                            $error =array('TYPE'=>'ERROR','MSG'=>'You Dont have any role');
                            $this->session->set_flashdata('ALERT_MSG', $error);
                            redirect(base_url().$this->SelfController);
                        }
                        //prePrint($user_role_object);
                        //exit();
                        if($user_role_object!=null || !(empty($user_role_object))){
                            //set session and redirect to another page
                            $session_data=$this->getSessionData($user_role_object,$user);
                            $this->session->set_userdata($this->SessionName, $session_data);
                            $this->set_admission_role($user_admission_role);
                            if($user_admission_role[0]['ROLE_ID'] == 6 || $user_admission_role[0]['ROLE_ID'] == 4)
                            {
                                redirect(base_url()."FormVerification");
                            }elseif($user_admission_role[0]['ROLE_ID'] == 7){
                                redirect(base_url().$this->generalBranch_mainpage); 
                            }else{
                                redirect(base_url().$this->HomeController);    
                            }
                            
                        }else{
                            $error =array('TYPE'=>'ERROR','MSG'=>'Your are un-authorized person, please stay away');
                            $this->session->set_flashdata('ALERT_MSG', $error);
                            redirect(base_url().$this->SelfController);
                            //UN-AUTHORIZED USER
                        }
                    }else{
                        $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Password');
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        redirect(base_url().$this->SelfController);
                        //invalid password
                    }
                }else{
                    $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Cnic No');
                    $this->session->set_flashdata('ALERT_MSG', $error);
                    redirect(base_url().$this->SelfController);
                    //invalid Cnic
                }
            }
            else{
                $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Request Please Must Enter Cnic And Password ');
                $this->session->set_flashdata('ALERT_MSG', $error);
                redirect(base_url().$this->SelfController);
            }
        }else{
            $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Form Request ');
            $this->session->set_flashdata('ALERT_MSG', $error);
            redirect(base_url().$this->SelfController);
        }
    }

    private function getSessionData($user,$user_profile) {
        $session_data =array('USER_ID'=>$user['USER_ID'],'ROLE_NAME'=>$user['ROLE_NAME'],'KEYWORD'=>$user['KEYWORD'],'ACTIVE'=>$user['ACTIVE'],'FIRST_NAME'=>$user_profile['FIRST_NAME'],'LAST_NAME'=>$user_profile['LAST_NAME'],'EMAIL'=>$user_profile['EMAIL'],'CNIC_NO'=>$user_profile['CNIC_NO'],'PROFILE_IMAGE'=>$user_profile['PROFILE_IMAGE'],'PASSPORT_NO'=>$user_profile['PASSPORT_NO'],'PROFILE'=>$user_profile);
        return $session_data;
    }

    protected function verify_login() {
		if((!$this->session->has_userdata($this->SessionName))){
			redirect(base_url().$this->SelfController);
			exit();
		}
	}
	
	
	/*
	 * verify_path method is updated on 15-10-2020 by Yasir Mehboob bcz sub menu was giving access prohibited
	 * */
	
	protected function verify_path ($path=null,$side_bar_data) {
			foreach ($side_bar_data as $p){
				if ($path == null)
				{
					$self = $_SERVER['PHP_SELF'];
					$path = explode('index.php/',$self);
				}
				if ($p['link'] == $path)
				{
					return true;
				}else
				{
					if ($p['is_submenu'] >0  && ( is_array($p['sub_menu']) || is_object($p['sub_menu'])))
					{
						foreach ($p['sub_menu'] as $sub_menu)
						{
							if($sub_menu['link'] == $path)
							{
								return true;
							}
						}
					}
				}
			}
			exit("<h2>Access Prohibited</h2>");
	}
	
	public function invg_app_auth(){
	      $this->load->model('User_model');

        if($this->input->server('REQUEST_METHOD') === 'POST'){
        		$postdata = file_get_contents("php://input");
	        	$request= json_decode($postdata,true);
            if(isset($request['auth_key'])&&!empty(trim($request['auth_key']))){
	        	    $auth_key = $this->security->xss_clean($request['auth_key']);
	        	    $auth_key = addslashes(trim($auth_key));
	        	}else{
	        	    log_message('error', "Auth Key index not found.");
	                  $reponse = array("ID"=>"2","DESCRIPTION"=>"Auth Key index not found.");
                			  $this->output
                                ->set_status_header(502)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                        return 2;
                                       // exit();
	        	}
	        if(isset($request['mac_address'])&&!empty(trim($request['mac_address']))){
	        	    $mac_address = $this->security->xss_clean($request['mac_address']);
	        	    $mac_address = addslashes(trim($mac_address));
	        	}else{
	        	    log_message('error', "Mac Address index not found.");
	                  $reponse = array("ID"=>"2","DESCRIPTION"=>"Mac Address index not found.");
                			  $this->output
                                ->set_status_header(502)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                        return 2;
                                       // exit();
	        	}
	        	
          
               $data = $this->User_model->getInvgAppAuthByKey($auth_key); 
               if($data){
                   if($data['MAC_ADDRESS']){
                        if($data['MAC_ADDRESS'] == $mac_address){
                            $reponse = array("ID"=>"1","DESCRIPTION"=>"SUCCESS","DATA"=>$data);
                			  $this->output
                                ->set_status_header(200)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                          return 2;
                        }else{
                            $reponse = array("ID"=>"2","DESCRIPTION"=>"Mac Address invalid.");
                			  $this->output
                                ->set_status_header(502)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                          return 2; 
                        }
                   }else{
                       //updateing mac address
                       $form_array  =array("MAC_ADDRESS"=>$mac_address);
                       $ok = $this->User_model->updateInvgAppAuthByKey($auth_key,$form_array);
                       if($ok){
                           $data['MAC_ADDRESS'] = $mac_address;
                             $reponse = array("ID"=>"1","DESCRIPTION"=>"SUCCESS","DATA"=>$data);
                			  $this->output
                                ->set_status_header(200)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                          return 2;   
                       }else{
                             $reponse = array("ID"=>"2","DESCRIPTION"=>"MAC ADDRESS UPDATING FAIL.");
                			  $this->output
                                ->set_status_header(502)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                          return 2; 
                       }
                  
                   }
                   
               }else{
                     $reponse = array("ID"=>"2","DESCRIPTION"=>"Auth Key invalid.");
                			  $this->output
                                ->set_status_header(502)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                          return 2;
               }
             
               
               
           
        }else{
              $reponse = array("ID"=>"2","DESCRIPTION"=>"Invalid Request Method.");
                			  $this->output
                                ->set_status_header(502)
                                ->set_content_type('application/json', 'utf-8')
                                        ->set_output(json_encode($reponse));
                                        return 2;
        }
	}
	
}//class
