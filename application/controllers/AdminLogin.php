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
    private $HomeController = 'mapping/shift_program_mapping';
    private $SessionName = 'ADMIN_LOGIN_FOR_ADMISSION';

    public function __construct()
    {
        parent::__construct();

        if($this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->HomeController);
            exit();
        }
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


    function adminLoginHandler(){
        $this->load->model('User_model');

        if(isset($_POST['login'])
            &&isset($_POST['password'])
            &&isset($_POST['cnic'])){

            $cnic =isValidData($this->input->post('cnic',TRUE));
            $password = isValidData($this->input->post('password',TRUE));

            $hashpassword = cryptPassowrd($password);

            if($cnic&&$password){

                $user = $this->User_model->getUserByCnic($cnic);

                if($user) {
                    if(strcmp($hashpassword,$user['PASSWORD'])===0){
						$userId=$user['USER_ID']; // recieved user_id, now pass this id to get and verify user_role.
                        $user_role_object = $this->User_model->getUserRoleByUserId($userId);

                        if($user_role_object!=null || !(empty($user_role_object))){
                            //set session and redirect to another page
                            $session_data=$this->getSessionData($user_role_object);
                            $this->session->set_userdata($this->SessionName, $session_data);
                            redirect(base_url().$this->HomeController);
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

    private function getSessionData($user){
        $session_data =array('USER_ID'=>$user['USER_ID'],'ROLE_NAME'=>$user['ROLE_NAME'],'KEYWORD'=>$user['KEYWORD'],'ACTIVE'=>$user['ACTIVE']);
        return$session_data;
    }
}