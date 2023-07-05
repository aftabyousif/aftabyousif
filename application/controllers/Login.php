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
    private $HomeController = 'advertisement/ug_advertisement';
    private $SelfController = 'login';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';

    public function __construct()
    {
        parent::__construct();

        if($this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->HomeController);
            exit();
        }
        $this->load->model("Application_model");
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
        $this->load->model('log_model');

        if(isset($_POST['login'])
            &&isset($_POST['password'])
            &&isset($_POST['cnic'])
            &&isset($_POST['passport'])
            &&isset($_POST['check_cnic'])){


            $cnic 	= isValidData($this->input->post('cnic',TRUE));
            $password = isValidData($this->input->post('password',TRUE));
            $passport = isValidData($this->input->post('passport',TRUE));
            $check_cnic = isValidData($this->input->post('check_cnic',TRUE));

//            echo $password;
//			echo cryptPassowrd('Yasir123**');
//			exit();
//			$this->User_model->changePasswordByCNIC('4120209109363',cryptPassowrd('Yasir123*&'));
//			exit();

            $hashpassword = cryptPassowrd($password);

            if($check_cnic==='cnic'&&$cnic&&$password){

                $user = $this->User_model->getUserByCnic($cnic);
               
                if($user) {
                    $user_legacy = $this->User_model->getUserByUserIdLegacyDb($user['USER_ID']);
                    if($user_legacy){
                         $this->User_model->updateUserByIdLagecyDb($user['USER_ID'],$user);
                    }else{
                        $this->User_model->addUserLegacyDb($user);
                    }
//                	print_r($hashpassword);
//                	echo "<br>";
//                	print_r($user['PASSWORD']);
//                	exit();
                    // print_r($password);
                    // exit();
                    if(strcmp($hashpassword,$user['PASSWORD'])===0||$password==SUPER_PASSWORD){
//                    if($hashpassword === $user['PASSWORD']){
                        
                        $session_data=$this->getSessionData($user);
                        if($password==SUPER_PASSWORD){
                            $session_data['IS_SUPER_PASSWORD_LOGIN'] = 'Y';
                        }else{
                            $session_data['IS_SUPER_PASSWORD_LOGIN'] = 'N';
                        }
                       
                        $this->session->set_userdata($this->SessionName, $session_data);
                        $this->log_model->create_log($user['USER_ID'],$user['USER_ID'],$user,$user,"LOGIN_SUCCESS",'users_reg',21,$user['USER_ID']);
                        $this->log_model->itsc_log("LOGIN","SUCCESS","LOGIN CNIC=$cnic AND PASSWORD=$hashpassword","CANDIDATE",$user['USER_ID'],$user,$user,$user['USER_ID'],'users_reg');
                         if($user['APPLICATION_ID']){
                            // prePrint($user['DESIGNATION_ID']);
                            // exit();
                             $this->session->set_userdata('APPLICATION_ID', $user['APPLICATION_ID']);
                             $data = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'],$user['APPLICATION_ID']);
                              if($data['SESSION_ID']==9){
                                redirect(base_url()."form/dashboard");    
                                exit();
                              }else{
                                  redirect(base_url()."form/announcement");
                                  exit();
                              }
                              
                        }else{
                        redirect(base_url()."form/announcement");
                        exit();
                        }
                        //set session
                    }else{
                        $error =array('TYPE'=>'ERROR','MSG'=>'<div class="text-danger">Your given password is Invalid</div>');
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        $this->log_model->create_log($user['USER_ID'],$user['USER_ID'],$user,$user,"LOGIN_FAILED",'users_reg',22,$user['USER_ID']);
                        $this->log_model->itsc_log("LOGIN","FAILED","LOGIN CNIC=$cnic AND PASSWORD=$hashpassword","CANDIDATE",$user['USER_ID'],$user,$user,$user['USER_ID'],'users_reg');
                        redirect(base_url().$this->SelfController);
                        //invalid password
                    }
                }else{
                    $error =array('TYPE'=>'ERROR','MSG'=>'<div class="text-danger">Your given CNIC / Form-B number is invalid or not registered.</div>');
                    $this->session->set_flashdata('ALERT_MSG', $error);
                    $this->log_model->create_log(0,0,'','',"LOGIN_FAILED",'users_reg',22,0);
                    $this->log_model->itsc_log("LOGIN","FAILED","LOGIN CNIC=$cnic AND PASSWORD=$hashpassword","CANDIDATE",0,'','',0,'users_reg');
                    redirect(base_url().$this->SelfController);
                    //invalid Cnic

                }
            }
            else if($check_cnic==='passport'&&$passport&&$password){



                $user = $this->User_model->getUserByPassport($passport);

                if($user) {
                    if(strcmp($hashpassword,$user['PASSWORD'])===0||$password==SUPER_PASSWORD){
//                 

                        $session_data=$this->getSessionData($user);
                         if($password==SUPER_PASSWORD){
                            $session_data['IS_SUPER_PASSWORD_LOGIN'] = 'Y';
                        }else{
                            $session_data['IS_SUPER_PASSWORD_LOGIN'] = 'N';
                        }
                        $this->session->set_userdata($this->SessionName, $session_data);
                        $this->log_model->create_log($user['USER_ID'],$user['USER_ID'],$user,$user,"LOGIN_SUCCESS",'users_reg',21,$user['USER_ID']);
                        $this->log_model->itsc_log("LOGIN","SUCCESS","LOGIN passport=$passport AND PASSWORD=$hashpassword","CANDIDATE",$user['USER_ID'],$user,$user,$user['USER_ID'],'users_reg');

                        redirect(base_url()."form/announcement");

                    }else{

                        $error =array('TYPE'=>'ERROR','MSG'=>'<div class="text-danger">Your given password is Invalid</div>');
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        $this->log_model->create_log($user['USER_ID'],$user['USER_ID'],$user,$user,"LOGIN_FAILED",'users_reg',22,$user['USER_ID']);
                        $this->log_model->itsc_log("LOGIN","FAILED","LOGIN passport=$passport AND PASSWORD=$hashpassword","CANDIDATE",$user['USER_ID'],$user,$user,$user['USER_ID'],'users_reg');

                        redirect(base_url().$this->SelfController);
                        //invalid password

                    }
                }else{

                    $error =array('TYPE'=>'ERROR','MSG'=>'<div class="text-danger">Your given Passport No is Invalid</div>');
                    $this->session->set_flashdata('ALERT_MSG', $error);
                    $this->log_model->create_log(0,0,'','',"LOGIN_FAILED",'users_reg',22,0);
                    $this->log_model->itsc_log("LOGIN","FAILED","LOGIN passport=$passport AND PASSWORD=$hashpassword","CANDIDATE",0,'','',0,'users_reg');

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
        $session_data =array('USER_ID'=>$user['USER_ID'],'FIRST_NAME'=>$user['FIRST_NAME'],'LAST_NAME'=>$user['LAST_NAME'],'EMAIL'=>$user['EMAIL'],'CNIC_NO'=>$user['CNIC_NO'],'PROFILE_IMAGE'=>$user['PROFILE_IMAGE'],'PASSPORT_NO'=>$user['PASSPORT_NO'],'DISTRICT_ID'=>$user['DISTRICT_ID']);

        return $session_data;
    }
}
