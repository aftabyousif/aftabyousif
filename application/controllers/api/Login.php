<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';

use chriskacerguis\RestServer\RestController;

class Login extends RestController {

	function __construct() 
    {
        parent::__construct();
        $this->load->library('api_auth');
        $this->load->model('api_model');
    }

	public function index_get(){       
		$username = $this->input->post('username');
        $password = $this->input->post('password');
		
        $this->form_validation->set_rules('username','Username','required');
        $this->form_validation->set_rules('password','Pasword','required');
        if($this->form_validation->run()){
			$user = $this->api_model->checkUser($username);
			if($user){
				$hashpassword = $this->api_auth->cryptPassword($password);
				if(strcmp($hashpassword,$user['PASSWORD'])===0){
					$userId=$user['USER_ID']; // recieved user_id, now pass this id to get and verify user_role.
					$user_role_object = $this->api_model->getUserRoleByUserId($userId);
					$user_admission_role = $this->api_model->getUserAdmissionRoleByUserId($userId);
					if(!count($user_admission_role)){
						$error =array('TYPE'=>'ERROR','MSG'=>'You Dont have any role');
						$this->session->set_flashdata('ALERT_MSG', $error);
						redirect(base_url().$this->SelfController);
					}
					if($user_role_object!=null || !(empty($user_role_object))){
						//set session and redirect to another page
						$session_data=$this->getSessionData($user_role_object,$user);
						$this->session->set_userdata($this->SessionName, $session_data);
						$this->set_admission_role($user_admission_role);
						if($user_admission_role[0]['ROLE_ID'] == 6 || $user_admission_role[0]['ROLE_ID'] == 4){
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
					}
				}else{
					$error =array('TYPE'=>'ERROR','MSG'=>'Invalid Password');
					$this->session->set_flashdata('ALERT_MSG', $error);
					redirect(base_url().$this->SelfController);
				}
			}
            $data = array('username'=>$username,'password'=> $hashpassword);
            $loginStatus = $this->api_model->checkLogin($data);
            if($loginStatus != false){
                $userId = $loginStatus->id;
                $bearerToken = $this->api_auth->generateToken($userId);
                $responseData = array(
                    'status'=> true,
                    'message' => 'Successfully Logged In',
                    'token'=> $bearerToken,
                );
                return $this->response($responseData,200);
            }else{
                $responseData = array(
                    'status'=>false,
                    'message' => 'Invalid Crendentials',
                    'data'=> []
                );
                return $this->response($responseData);
            }
        }else{
            $responseData = array(
                'status'=>false,
                'message' => 'Username and password is required',
                'data'=> []
            );
            return $this->response($responseData);
        }
    }
}