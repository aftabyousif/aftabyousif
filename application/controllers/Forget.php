<?php
/**
 * Created by PhpStorm.
 * User: YASIR MEHBOOB
 * Date: 11/09/2020
 * Time: 4:42 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Forget extends CI_Controller {
    
    private $HomeController = 'advertisement/ug_advertisement';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    private $SelfController = "Forget";
      
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sms_model');
        // if($this->session->has_userdata($this->SessionName)){
        //     redirect(base_url().$this->HomeController);
        //     exit();
        // }
    }

    /**
     * Login constructor.
     */

    function index(){
        
        $this->session->unset_userdata("user_data_forget_pwd_qualification");
        $this->session->unset_userdata("user_data_forget_pwd");
                            
        $this->load->helper("form");
        $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
        $this->load->view('forget_password');
        $this->load->view('include/login_footer');
    }
    
     function step_2 ()
    {
        $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
        $this->load->view('forget_password_step_2');
        $this->load->view('include/login_footer');
    }

     function set_pwd ($user_id="",$token="")
    {
        $this->load->model('User_model');
        
        $user_id_decrypt = DecryptThis(urldecode($user_id));
        $token_decrypt   = DecryptThis(urldecode($token));
        
        
        $user = $this->User_model->getUserById($user_id_decrypt);
        $PASSWORD_TOKEN = $user['PASSWORD_TOKEN'];
        $data_arr['USER_DATA'] = array 
                    (
                        'USER_ID_ENCRYPTED'=> $user_id,
                        'TOKEN'=> $token,
                        'NAME'=> $user['FIRST_NAME'],
                        'EMAIL'=> $user['EMAIL'],
                        'CNIC_NO'=> $user['CNIC_NO']
                        );
        if($token_decrypt !=$PASSWORD_TOKEN)
        {
            $error =array('TYPE'=>'SUCCESS','MSG'=>"<p>Sorry, Your password reset token is expired, Please generate new token.</p>");
            $this->session->set_flashdata('ALERT_MSG', $error);
            $this->session->set_userdata('user_data_forget_pwd', $data);
            $this->session->set_userdata('user_data_forget_pwd_qualification', $data_qualification);
            redirect("Forget");
        }

        $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
        $this->load->view('reset_password_email',$data_arr);
        $this->load->view('include/login_footer');
    }
    
    function set_pwd_handler ()
    {
        $this->load->model('User_model');
        
        $error = "";
        $this->form_validation->set_rules('user_data','user_data','required');
        if(!$this->form_validation->run())
        {
            redirect("Forget");
        }
     
        $user_data = ($this->input->post('user_data'));
        $user_data = json_decode($user_data,true);
        
        // print_r($user_data);
        // exit();
        $user_id = $user_data['USER_ID_ENCRYPTED'];
        $token = $user_data['TOKEN'];
        
        // exit($token);
        $this->form_validation->set_rules('password','','required');
        $this->form_validation->set_rules('re_type_password','','required');
        if($this->form_validation->run())
        {
            $password = ($this->input->post('password'));
            $re_type_password = ($this->input->post('re_type_password'));
            
             if($password == $re_type_password)
             {
              
                          if (passwordRule($password)) {
                                    // echo "yes4";
                                    $id = $user_id_decrypt = DecryptThis(urldecode($user_id));
                                    // exit($id);
                                    $password = cryptPassowrd($password);
                                    // $curr_password = cryptPassowrd($curr_password);
                                    $result = $this->User_model->resetPassword($id,$password);
                                   // $result = "";
                                    if($result){
                                        $error =array('TYPE'=>'SUCCESS','MSG'=>'Password Change Successfully');
                                        $this->session->set_flashdata('ALERT_MSG', $error);
                                        redirect(base_url().'login');
                                        exit();
                                    }else{
                                        $error .= "<div class='text-danger'>Provided Current Password is Wrong..!</div>";
                                    }


                                } else {
                                    // $error .= "<div class='text-danger'>At least one digit ...!</div>";
                                    // $error .= "<div class='text-danger'>At least one lowercase character ...!</div>";
                                    // $error .= "<div class='text-danger'>At least one uppercase character ...!</div>";
                                    // $error .= "<div class='text-danger'>At least one special character ...!</div>";
                                    $error .= "<div class='text-danger'>At least 8 characters in length, but no more than 50 ...!</div>";
                                }
                                                $error =array('TYPE'=>'MESSAGE','MSG'=>"$error");
                                                $this->session->set_flashdata('ALERT_MSG', $error);
                                                $this->set_pwd($user_id,$token);  
             }else
             {
                 $error =array('TYPE'=>'MESSAGE','MSG'=>"Password & Retype Password does'nt match");
                 $this->session->set_flashdata('ALERT_MSG', $error);
                 $this->set_pwd($user_id,$token);  
             }
        }else
        {
          $error =array('TYPE'=>'MESSAGE','MSG'=>"Please type Password & Retype Password");
                 $this->session->set_flashdata('ALERT_MSG', $error);
                 $this->set_pwd($user_id,$token); 
        }
    }
    
    function forgetHandler(){
        
        $this->load->model('User_model');
        $this->load->model('log_model');
        $check_cnic = "cnic";

        if(isset($_POST['submit'])
            // &&isset($_POST['password'])
            &&isset($_POST['cnic_no'])
            // &&isset($_POST['passport'])
            // &&isset($_POST['check_cnic'])
            ){

            $cnic 	= isValidData($this->input->post('cnic_no',TRUE));

            
            if($check_cnic == "cnic" && !empty($cnic)){

                $user = $this->User_model->getUserByCnic($cnic);
                
                 $token = rand(10000000,99999999);
                 $code  = cryptPassowrd($token);
                 $DATE  = date('Y-m-d H:i:s');

                if($user) {
                    
                    $user_id = $user['USER_ID'];
                    $FIRST_NAME = $user['FIRST_NAME'];
                    $FNAME = $user['FNAME'];
                    $LAST_NAME = $user['LAST_NAME'];
                    $EMAIL = $user['EMAIL'];
                    $MOBILE_NO = $user['MOBILE_NO'];
                    
                    $qualification = $this->User_model->getQulificatinByUserID_DEGREE_ID($user_id,2,NULL);
                // echo "<pre>";
                // print_r($qualification);
                // exit();
                 if (!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)) {
                     
                    exit("failed process");
                 }else
                 {
                        $formArray = array (
                        'PASSWORD_TOKEN'=>$code,
                        'FORGET_DATE_TIME'=>$DATE
                        );
                 
                    $update_token = $this->User_model->updateUserById($user_id,$formArray);
                    if($update_token == 1)
                    {
                        $this->log_model->create_log($user['USER_ID'],$user['USER_ID'],$user,$user,"FORGET_PASSWORD_SUCCESS",'users_reg',22,$user['USER_ID']);
                        $this->log_model->itsc_log("FORGET-PASSWORD","SUCCESS","FORGET PASSWORD ON $DATE","CANDIDATE",$user['USER_ID'],$user,$user,$user['USER_ID'],'users_reg');   
                        sendPasswordTokenByEmail_smtp($EMAIL,$code,$user_id,$this);
                        
                        if($user['USER_ID'] == "62043" || $user['USER_ID'] == "93774")
                        {
                                $code_encrypted    = urlencode(EncryptThis($code));
                                $user_id_encrypted = urlencode(EncryptThis($user_id));
                                $MOBILE_NO = ltrim($MOBILE_NO,'0');
                                $MOBILE_NO = '0'.$MOBILE_NO;
                                
                                // exit($MOBILE_NO);
                        $msg = "Assalam Alaikum,~$FIRST_NAME $LAST_NAME~   We have received password reset request for your account of admission portal. Please visit the following link to reset your password.~ admission.usindh.edu.pk/admission/forget/set_pwd/$user_id/$token~Note.~(1) That above link for password reset is valid for one time use only.~(2) Iphone users, please copy above link & paste into address bar of iphone’s browser.~Best Regards,~ITSC Support Team";
                        // $msg = "Assalam Alaikum";
                        $sms=array(
                       "MESSAGE"=>$msg,
                       "CONTACT"=>$MOBILE_NO,
                       "STATUS"=>1,
                       "REQUEST_TIME"=>date('Y-m-d h:s:i'),
                       
                   );
                            $this->Sms_model->sac_message($sms);
                        }
                        
                    $data = json_encode($user);
                    $data_qualification = json_encode ($qualification);
                    unset($user);
                    
                    if(empty($qualification))
                    {
                        unset($qualification);
                        
                        $error =array('TYPE'=>'SUCCESS','MSG'=>"<p class='text-success' style='font-size:14;font-weight:bold'>Please check your email <span class='text-danger'>[ $EMAIL ]</span> for password reset link.If you do not find any email in inbox, then please also check the Spam or Junk/Queue/folders of your email.<p>");
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        redirect("login");
                    }else{
                         unset($qualification);
                        $this->session->set_userdata('user_data_forget_pwd', $data);
                        $this->session->set_userdata('user_data_forget_pwd_qualification', $data_qualification);
                        redirect("Forget/step_2");
                        exit();
                    }
                
                    }else
                    {
                        $error =array('TYPE'=>'ERROR','MSG'=>'Invalid request please must your cnic / passport No.');
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        redirect(base_url().$this->SelfController);
                    }
                 }
        
     
                        // $session_data=$this->getSessionData($user);
                        // $this->session->set_userdata($this->SessionName, $session_data);

                        // redirect(base_url().$this->HomeController);
                
                }else{
                    $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Cnic No');
                    $this->session->set_flashdata('ALERT_MSG', $error);
                    $this->log_model->create_log(0,0,'','',"LOGIN_FAILED",'users_reg',22,0);
                    $this->log_model->itsc_log("LOGIN","FAILED","LOGIN CNIC=$cnic AND PASSWORD=$hashpassword","CANDIDATE",0,'','',0,'users_reg');
                    redirect(base_url().$this->SelfController);
                    //invalid Cnic

                }
            }
            else{
                $error =array('TYPE'=>'ERROR','MSG'=>'Invalid request please must your cnic / passport No.');
                $this->session->set_flashdata('ALERT_MSG', $error);
                redirect(base_url().$this->SelfController);
            }


        }else{
            $error =array('TYPE'=>'ERROR','MSG'=>'Invalid Form Request ');
            $this->session->set_flashdata('ALERT_MSG', $error);
            redirect(base_url().$this->SelfController);
        }


    }
    
    public function step2_process ()
    {
        $this->load->model('User_model');
        $this->load->model('log_model');
        
                if(isset($_POST['submit']))
                {
                    $this->form_validation->set_rules('cnic_no','cnic','required|trim');
                    $this->form_validation->set_rules('seat_no','seat no','trim');
                    $this->form_validation->set_rules('obt_marks','obtain marks','required|trim');
                    $this->form_validation->set_rules('passing_year','passing year','required|trim');
                    $this->form_validation->set_rules('dob','date of birth','trim');
                    
                    if($this->form_validation->run())
                    {
                        $form_cnic_no       = html_escape ($this->input->post('cnic_no'));
                        $form_seat_no       = html_escape ($this->input->post('seat_no'));
                        $form_obt_marks     = html_escape ($this->input->post('obt_marks'));
                        $form_passing_year  = html_escape ($this->input->post('passing_year'));
                        $form_dob           = html_escape ($this->input->post('dob'));
                        
                        $form_dob = getDateForDatabase($form_dob);
                        
                        $form_cnic_no = str_replace ('-','',$form_cnic_no);
                        
                        $USER_DATA = $this->session->userdata("user_data_forget_pwd");
                        $USER_DATA = json_decode($USER_DATA,true);
                        $email  = $USER_DATA['EMAIL'];
                        $cnic_no = $USER_DATA['CNIC_NO'];
                        $dob = $USER_DATA['DATE_OF_BIRTH'];
                        $user_id = $USER_DATA['USER_ID'];
                      
                        
                        $USER_QUALIFICATION = $this->session->userdata("user_data_forget_pwd_qualification");
                        $USER_QUALIFICATION = json_decode($USER_QUALIFICATION,true);
                        $USER_QUALIFICATION = $USER_QUALIFICATION[0];
                        
                        // echo "<pre>";
                        // print_r($USER_QUALIFICATION);
                        // exit();
                        $obtained_marks = $USER_QUALIFICATION['OBTAINED_MARKS'];
                        $passing_year = $USER_QUALIFICATION['PASSING_YEAR'];
                        $seat_no = $USER_QUALIFICATION['ROLL_NO'];
                        
                        $error = "";
                        if($form_cnic_no == $cnic_no)
                        {
                            if($form_obt_marks != $obtained_marks )
                            {
                                $error.="<p class='text-danger'>You have given invalid obtained marks</p>";
                            }
                            
                            if($form_passing_year != $passing_year )
                            {
                                $error.="<p class='text-danger'>You have given invalid passing year</p>";
                            }
                            
                             if($form_seat_no == $seat_no) 
                            {
        
                                
                            }elseif($form_dob == $dob)
                            {
                                
                            }else
                            {
                                $error.="<p class='text-danger'>Sorry, Seat No or Date of Birth is not matching, it is compulary to match anyone of these (Seat No or Date of Birth)</p>";
                            }
                            
                            if(empty($error) && $user_id>0)
                            {
                                    $token = rand(10000000,99999999);
                                    $code  = cryptPassowrd($token);
                                    $DATE  = date('Y-m-d H:i:s');
                                
                                $formArray = array (
                                    'PASSWORD_TOKEN'=>$code,
                                    'FORGET_DATE_TIME'=>$DATE
                                    );
                    $update_token = $this->User_model->updateUserById($user_id,$formArray);
                    if($update_token == 1)
                    {
                    
                        $this->log_model->create_log($user_id,$user_id,$USER_DATA,$USER_DATA,"FORGET_PASSWORD_SUCCESS",'users_reg',22,$user_id);
                        $this->log_model->itsc_log("FORGET-PASSWORD","SUCCESS","FORGET PASSWORD ON $DATE","CANDIDATE",$user_id,$USER_DATA,$USER_DATA,$user_id,'users_reg');   
                        // sendPasswordTokenByEmail($email,$code,$user_id);
                        
                            $this->session->unset_userdata("user_data_forget_pwd_qualification");
                            $this->session->unset_userdata("user_data_forget_pwd");
                            $token= urlencode(EncryptThis($code));
                            $user_id_encoded= urlencode(EncryptThis($user_id));
                            redirect("forget/set_pwd/$user_id_encoded/$token");
                    }else
                    {   
                        $this->session->unset_userdata("user_data_forget_pwd_qualification");
                        $this->session->unset_userdata("user_data_forget_pwd");
                        $error =array('TYPE'=>'ERROR','MSG'=>"Something went wrong, Please try again");
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        redirect("forget");
                    }
                            }else
                            {
                                $error =array('TYPE'=>'ERROR','MSG'=>"$error");
                                $this->session->set_flashdata('ALERT_MSG', $error);
                                redirect("forget/step_2");
                                
                            }
                            
                        }else
                        {
                        $error =array('TYPE'=>'ERROR','MSG'=>"<p class='text-danger text-center'>Sorry, You have provided invalid CNIC NO</p>");
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        redirect("forget/step_2");
                        }
                        
                    }else
                    {
                        $error =array('TYPE'=>'ERROR','MSG'=>"Sorry, The following options are required for password reset.");
                        $this->session->set_flashdata('ALERT_MSG', $error);
                        redirect("forget/step_2");
                    }
                }
         
    }

    private function getSessionData($user){
        $session_data =array('USER_ID'=>$user['USER_ID'],'FIRST_NAME'=>$user['FIRST_NAME'],'LAST_NAME'=>$user['LAST_NAME'],'EMAIL'=>$user['EMAIL'],'CNIC_NO'=>$user['CNIC_NO'],'PROFILE_IMAGE'=>$user['PROFILE_IMAGE'],'PASSPORT_NO'=>$user['PASSPORT_NO']);

        return$session_data;
    }
}
