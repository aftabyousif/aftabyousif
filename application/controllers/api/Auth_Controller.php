<?php 

class Auth_Controller extends RestApi_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('api_auth');
        $this->load->model('auth_model');
        $this->load->model('api_model');
    }

    /*function register(){
        $username = $this->input->post('name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required');
        $this->form_validation->set_rules('password','Pasword','required');
        if($this->form_validation->run()){
            $data  = array(
                'name'=>$username,
                'email'=>$email,
                'password'=>sha1($password),
            );
            $this->api_model->registerUser($data);
            $responseData = array(
                'status'=>true,
                'message' => 'Successfully Registerd',
                'data'=> []
            );
            return $this->response($responseData,200);
        }else{
            $responseData = array(
                'status'=>false,
                'message' => 'fill all the required fields',
                'data'=> []
            );
            return $this->response($responseData);
        }
    }*/

    function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $this->form_validation->set_rules('username','Username',array('required', 'exact_length[13]', 'numeric'));
        $this->form_validation->set_rules('password','Pasword','required');        
        if($this->form_validation->run()){            
            $data = array('CNIC_NO'=>$username,'PASSWORD'=> $this->api_auth->cryptPassowrd($password));
            $loginStatus = $this->auth_model->checkLogin($data);
            if($loginStatus != false){
                $userId = $loginStatus->USER_ID;
                $bearerToken = $this->api_auth->generateToken($userId);
                $responseData = array(
                    'status'=> true,
                    'message' => 'Successfully Logged In',
                    'token'=> $bearerToken,
                );
                return $this->response($responseData,200);
            } else {
                $responseData = array(
                    'status'=>false,
                    'message' => 'Invalid Crendentials',
                    'data'=> []
                );
                return $this->response($responseData);
            }
        } else {
            $responseData = array(
                'status'=>false,
                'message' => validation_errors(),
                'data'=> []
            );
            return $this->response($responseData);
        }
    }

}