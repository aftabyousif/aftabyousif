<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/10/2020
 * Time: 9:42 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
    /**
     * Login constructor.
     */
    private $HomeController = 'Candidate';
    private $SelfController = 'login';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';

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
        $this->load->view('login');
        $this->load->view('include/login_footer');
    }



    function loginHandler(){
        $this->load->model('User_model');

        if(isset($_POST['login'])
            &&isset($_POST['password'])
            &&isset($_POST['cnic'])
            &&isset($_POST['passport'])
            &&isset($_POST['check_cnic'])){


            $cnic =isValidData($this->input->post('cnic',TRUE));
            $password = isValidData($this->input->post('password',TRUE));
            $passport = isValidData($this->input->post('passport',TRUE));
            $check_cnic = isValidData($this->input->post('check_cnic',TRUE));

            $hashpassword = cryptPassowrd($password);

            if($check_cnic==='cnic'&&$cnic&&$password){

                $user = $this->User_model->getUserByCnic($cnic);

                if($user) {

                    if(strcmp($hashpassword,$user['PASSWORD'])===0){

                        $session_data=$this->getSessionData($user);
                        $this->session->set_userdata($this->SessionName, $session_data);
                        redirect(base_url().$this->HomeController);
                        //set session
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
            elseif($check_cnic==='passport'&&$passport&&$password){



                $user = $this->User_model->getUserByPassport($passport);

                if($user) {
                    if(strcmp($hashpassword,$user['PASSWORD'])===0){


                        $session_data=$this->getSessionData($user);
                        $this->session->set_userdata($this->SessionName, $session_data);
                        redirect(base_url().$this->HomeController);

                    }else{

                        $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Password');
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        redirect(base_url().$this->SelfController);
                        //invalid password

                    }
                }else{

                    $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Passport No');
                    $this->session->set_flashdata('ALERT_MSG', $error);
                    redirect(base_url().$this->SelfController);
                    //invalid Passport

                }
            }
            else{
                $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Request Please Must Enter Cnic / Passport And Password ');
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
        $session_data =array('USER_ID'=>$user['USER_ID'],'FIRST_NAME'=>$user['FIRST_NAME'],'LAST_NAME'=>$user['LAST_NAME'],'EMAIL'=>$user['EMAIL'],'CNIC_NO'=>$user['CNIC_NO'],'PROFILE_IMAGE'=>$user['PROFILE_IMAGE'],'PASSPORT_NO'=>$user['PASSPORT_NO']);

        return$session_data;
    }
}