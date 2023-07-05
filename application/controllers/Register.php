<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/10/2020
 * Time: 9:42 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Register extends CI_Controller {
    /**
     * Login constructor.
     */
    private $HomeController = 'form/dashboard';
    private $SelfController = 'Register';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';

    public function __construct(){
        parent::__construct();
//send_confirmation_email("kscsm32@gmail.com","576576");
//send_confirmation_email("m.yasir088@gmail.com","576576");
        if($this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->HomeController);
            exit();
        }
    }
    
    function test_registration_email (){
        send_confirmation_email("yasir.mehboob@usindh.edu.pk","4120209109363");
    }
    
    function index(){
        $this->load->model('Api_location_model');
        $countries =$this->Api_location_model->getAllCountry();
        $data['countries']=$countries;
        $this->load->helper("form");
        $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
        if(isset($_GET['user'])&&$_GET['user']=="admin"){
            $this->load->view('register',$data);
        }else{
         echo '<div style="height:100px"></div>';
         echo "<h1>Registration is closed for admission 2023.<br>Please wait for new admission session</h1>";
        }
        //
        $this->load->view('include/login_footer');
    }
    function user_register_handler(){
        $this->load->model('User_model');
        $this->load->model('Sms_model');
        $reponse = getcsrf($this);

        if ($this->input->server('REQUEST_METHOD') == 'POST'){

            if(
                isset($_POST['full_name'])
                &&isset($_POST['email'])
                &&isset($_POST['surname'])
                &&isset($_POST['mobile'])
                &&isset($_POST['check_cnic'])
                &&isset($_POST['cnic'])
                &&isset($_POST['passport'])
                &&isset($_POST['retype_passport'])
                &&isset($_POST['password'])
                &&isset($_POST['retype_password'])
                &&isset($_POST['retype_cnic'])
                &&isset($_POST['COUNTRY_ID'])
                &&isset($_POST['DISTRICT_ID'])
                 &&isset($_POST['GENDER'])
                &&isset($_POST['PROVINCE_ID'])
                &&isset($_POST['email_verification_code'])
                //&&isset($_POST['mobile_verification_code'])
            ){

                $cnic = null;
                $passport = null;
                $error_msg = "";
                if($_POST['check_cnic']==='cnic'){
                    $IS_CNIC_PASS = 'C';
                    $cnic = isValidData($_POST['cnic']);
                    $r_cnic = isValidData($_POST['retype_cnic']);
                    if(is_numeric($cnic)){

                        if(!($cnic&&$r_cnic&&$cnic===$r_cnic&&strlen($cnic)==13)){
                            $cnic = "";
                            $error_msg .="Invalid Cnic..!<br>";
                        }
                    }else{
                        $error_msg .="Invalid Cnic No Please do not enter dashesh..!<br>";
                    }

                }else if($_POST['check_cnic']==='passport'){

                    $IS_CNIC_PASS = 'P';
                    $passport = isValidData($_POST['passport']);
                    $r_passport = isValidData($_POST['retype_passport']);
                    if(!($passport&&$r_passport&&$r_passport===$passport)){
                        $passport = "";
                        $error_msg .="Invalid Passport..!<br>";
                    }


                }else{
                    $error_msg .="Invalid Request..!<br>";
                }
                $name = strtoupper(isValidData($_POST['full_name']));
                if(!$name){
                    $error_msg .="Invalid Name..!<br>";

                }
                $COUNTRY_ID = isValidData($_POST['COUNTRY_ID']);
                if(!$COUNTRY_ID){
                    $error_msg .="Invalid Country..!<br>";

                }
                $PROVINCE_ID = isValidData($_POST['PROVINCE_ID']);
                if(!$PROVINCE_ID){
                    $error_msg .="Invalid Domicile Province..!<br>";

                }
                $DISTRICT_ID = isValidData($_POST['DISTRICT_ID']);
                if(!$DISTRICT_ID){
                    $error_msg .="Invalid Domicile District..!<br>";

                }


                $surname = strtoupper(isValidData($_POST['surname']));
                if(!$surname){
                    //$error_msg .="Invalid Surname..!<br>";

                }
                $f_name = strtoupper(isValidData($_POST['f_name']));
                if(!$f_name){
                    $error_msg .="Invalid Father Name..!<br>";

                }
                $gender = strtoupper(isValidData($_POST['GENDER']));
                if(!$gender){
                    $error_msg .="Invalid Gender..!<br>";

                }


                $email = isValidData($_POST['email']);
                if(!$email){
                    $error_msg .="Please provide Email Address..!<br>";

                }elseif(isValidEmail($email) == false)
                {
                    $error_msg .="You have given invalid Email..!<br>";
                }
                
                $email = strtolower($email);
                $mobile = isValidData($_POST['mobile']);
                if(!$mobile){
                    $error_msg .="Invalid mobile..!<br>";

                }
                $firstCharacter = $mobile[0];

            if($firstCharacter ==0){
                $mobile=  substr($mobile ,1);
            }
                if(strlen($mobile)>=12 ||strlen($mobile)<=9){
                    $error_msg .="Invalid mobile..!<br>";
                }
                $PHONE_CODE = isValidData($_POST['PHONE_CODE']);
                if(!$PHONE_CODE){
                    $error_msg .="Invalid MObile_Code..!<br>";

                }

                $password = isValidData($_POST['password']);
                $r_password = isValidData($_POST['retype_password']);
                if(!($password&&$r_password&&$password===$r_password&&strlen($r_password)>=8)){

                    $error_msg .="Password length should be minimum 8 characters..!<br>";
                }
                $password = cryptPassowrd($password);
                if($IS_CNIC_PASS=='C'){
                    $user = $this->User_model->getUserByCnic($cnic);
                    if($user){
                        $error_msg .="<span class='text-danger'>This CNIC No $cnic is already registered. Kindly use your own CNIC No or B-Form No to register yourself. <br>If you are already registered or currently enrolled or Ex-Student of University of Sindh and want to apply for the Admissions 2022, you can login with your previous LMS/ E-portal account password. 
<br> <a href='".base_url()."login"."'>Click Here For Login</a></span>";
                    }
                }else if($IS_CNIC_PASS=='C'){
                    $user = $this->User_model->getUserByPassport($passport);
                    if($user){
                        $error_msg .="<span class='text-danger'>This Passport No $passport is already registered. Kindly use your own Passport to register yourself. <br>If you are already registered or currently enrolled or Ex-Student of University of Sindh and want to apply for the Admissions 2022, you can login with your previous LMS/ E-portal account password. 
<br> <a href='".base_url()."login"."'>Click Here For Login</a></span>";
                    }
                }
                 
                    // if($this->session->has_userdata('email_for_verfi')
                    //  &&$this->session->has_userdata('email_verfi_code')){
                    //       $email_for_verfi = $this->session->userdata('email_for_verfi');
                    //         $email_verfi_code = $this->session->userdata('email_verfi_code');
                      $email_verfi_code = substr(md5(strrev($_POST['email'])), 0, 8);
                    
                            $email_verification_code = strtolower($_POST['email_verification_code']);
                         if( $email_verification_code== $email_verfi_code){
                           //  <div class='text-success'>email is Verified</div>
                         }else{
                            
                             $error_msg .= "<div class='text-danger'>Email not Verified</div>";  
                         }
                    //  }else{
                       
                    //   $error_msg .= "First Click Send Email Verification Code Then Enter Verification Code";  
                    //  }
                     /*
                 if($this->session->has_userdata('mobile_for_verfi')
                     &&$this->session->has_userdata('mobile_verfi_code')){
                           $mobile_for_verfi = $this->session->userdata('mobile_for_verfi');
                            $mobile_verfi_code = $this->session->userdata('mobile_verfi_code');
                         if($mobile_for_verfi == $_POST['mobile'] && $_POST['mobile_verification_code']== $mobile_verfi_code){
                            
                            // $reponse['MESSAGE'] = "<div class='text-success'>mobile is Verified</div>";  
                         }else{
                               
                             $error_msg .= "<div class='text-danger'>mobile not Verified</div>";  
                         }
                     }else{
                       
                        $error_msg .="First Click Send mobile Verification Code Then Enter Verification Code";  
                     }*/
            if (isset($_FILES['profile_image'])) {
                // prePrint($_FILES['profile_image'][]);
                if (isValidData($_FILES['profile_image']['name'])) {

                 
                } else {
                    
                        $error .= "<div class='text-danger'>Must Upload Profile Picture</div>";
                    
                }
            } else {
                
                    $error .= "<div class='text-danger'>Must Upload Profile Picture</div>";
                
            }

             

             


        

                if($error_msg==""){
                    $DATE =date('Y-m-d H:i:s');
                    $token = rand(10000000,99999999);
                    $password_token  = cryptPassowrd($token);

                   $data=array(
                       "FIRST_NAME"=>$name,
                       "FNAME"=>$f_name,
                       "LAST_NAME"=>$surname,
                       "PASSWORD"=>$password,
                       "CNIC_NO "=>$cnic,
                       "PASSPORT_NO "=>$passport,
                       "IS_CNIC_PASS"=>$IS_CNIC_PASS,
                       "COUNTRY_ID"=>$COUNTRY_ID,
                       "EMAIL"=>$email,
                       "MOBILE_NO"=>$mobile,
                       "MOBILE_CODE"=>$PHONE_CODE,
                       "ACCT_OPENING_DATE"=>$DATE,
                       "PASSWORD_TOKEN"=>$password_token,
                       "PROVINCE_ID"=>$PROVINCE_ID,
                       "DISTRICT_ID"=>$DISTRICT_ID,
                       "GENDER"=>$gender,
                       "REMARKS"=>"NEW_ADMISSION"
                   );

                    $bol=$this->User_model->addUser($data,$this);
                        if($bol === true){
                           // send_confirmation_email($email,$r_password);
                          
    $email_subject ='WELLCOME TO UNIVERSITY OF SINDH';
    $email_body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> <p style='font-size:14pt'>السلام عليكم</p><br>
You have been successfully registered on admission portal, you can Login using CNIC /B-Form No and Password for further process.  <a href='".base_url()."assets/advertisement_2023.pdf'>Advertisement - Admissions 2023</a> <br><br>Your password is: <b>$r_password</b><br>
<p>You will have to add your qualifications later on for that you will be notified through Email. Keep visiting your email account and E-portal account dashboard for further process regarding Admissions 2022.</p>".
" 
<p> <a href='https://youtu.be/s3cOrP0CqNQ'>Click here to watch tutorial how to fill online admission form?</a></p>
Prepare the following documents in softcopy before filling the online admission form.<br> <br>  
1.	 “Admission copy” of paid up challan of Admission application processing fee. (Rs. 2500/=) (Original)<br>
2.	Matriculation (S.S.C-Part II) - Marks and Pass Certificates (Original)<br>
3.	Intermediate (HSC-Part II) - Marks and Pass Certificates (Original)<br>
4.	Bachelor’s degree (14 years OR 16 years ) - Marks and Pass Certificates  for admission in Master’s degree program (Original)<br>
5.	HEC LAT score card for admission in L.L.B Program <br>
6.	Computerized National Identity Card (CNIC) / B-Form from NADRA. (Original)<br>
7.	Domicile Certificate and Permanent Residence Certificate (Form- C) (Original)<br>
".
        "<br><b><a href='".base_url()."'>Click Here for login and fill your online admission form </a></b><br> 
                     
                      <br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      DIRECTOR ADMISSIONS<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";
                            $res  = send_smtp_email($email_subject,$email_body,$email,$this);
                            $msg = "Welcome to University of Sindh.~You have been successfully registered on admission portal. You can Login using CNIC /B-Form No and Password for further process.~Best Regards. ITSC Support Team";
                                               $sms=array(
                       "MESSAGE"=>$msg,
                       "CONTACT"=>"0".$mobile,
                       "STATUS"=>1
                       
                   );
                            /* sac_message temprory commented by yasir 02-07-2021*/
                            // $this->Sms_model->sac_message($sms);
                            $this->session->unset_userdata('mobile_for_verfi');
                            $this->session->unset_userdata('mobile_verfi_code');
                            $this->session->unset_userdata('email_for_verfi');
                            $this->session->unset_userdata('email_verfi_code');
                            $reponse['RESPONSE'] = "SUCCESS";
                            $reponse['MESSAGE'] = "You are successfully registered <br> <a href='".base_url()."login"."'>Click Here For Login</a>";
                        }else{
                            $reponse['RESPONSE'] = "ERROR";
                            $reponse['MESSAGE'] = "some thing went wrong may be The cnic already exist";
                        }
                }else{


                    $reponse['RESPONSE'] = "ERROR";
                    $reponse['MESSAGE'] = $error_msg;

                }
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "Invalid Request Method";
            }






        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "Invalid Request Method";
        }

        if ($reponse['RESPONSE'] == "ERROR") {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        } else {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    }

    function send_email_code(){
        $this->load->model('User_model');
        $this->load->model('Sms_model');
        $reponse = getcsrf($this);

        if ($this->input->server('REQUEST_METHOD') == 'POST'){

            if(isset($_POST['email'])){

             
                $error_msg = "";
                $email = isValidData($_POST['email']);
                 $email = strtolower($email);
                if(!$email){
                    $error_msg .="Please provide Email Address..!<br>";

                }elseif(isValidEmail($email) == false)
                {
                    $error_msg .="You have given invalid Email..!<br>";
                }
                elseif(count($this->User_model->getUserByEmailAddress($email))>0){
                   $error_msg .="Email Address is Already Registered<br>Try to use different email Address or forget password to <a href='${base_url()}forget'>click here</a>"; 
                }
               
                
               
                  if($error_msg==""){
                    $DATE =date('Y-m-d H:i:s');
                    $token = rand(10000000,99999999);
                     
                     $email_token = substr(md5(strrev($email)), 0, 8);
                     $token = $email_token;
                     
                     if($this->session->has_userdata('email_for_verfi')
                     &&$this->session->has_userdata('email_verfi_code')){
                            
                            $email_for_verfi = $this->session->userdata('email_for_verfi');
                            $email_verfi_code = $this->session->userdata('email_verfi_code');
                            if($email_for_verfi == $email){
                                 // sendVerificationEmail($email,$email_verfi_code);
                                 $token =$email_verfi_code;
                            }else{
                                $this->session->set_userdata('email_for_verfi', $email);
                                 $this->session->set_userdata('email_verfi_code', $token);
                              //  sendVerificationEmail($email,$token);
                            }
                     }else{
                                $this->session->set_userdata('email_for_verfi', $email);
                                 $this->session->set_userdata('email_verfi_code', $token);
                               // sendVerificationEmail($email,$token);  
                     }
                    //  $from = 'admission@usindh.edu.pk';
   // $from_name ='IT Services Support Team';
    $email_subject ='Email Verification';
    $email_body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum, <br/> Dear Candidate,<br>
We have recieved your registration request for University of Sindh admissions portal.<br>".
        "      
                      <br><br><b style='font-size:30px;'>Email verification Token is:  <span style='color:red'>$token</span></b><br><br>
                      
                     
                      <br><br>
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";
                            $res  = send_smtp_email($email_subject,$email_body,$email,$this);
                       $reponse['RESPONSE'] = "SUCCESS";
                    $reponse['MESSAGE'] = "<div class='text-success'> verification code has been sent at this $email kindly check your email for verification code and also check spam/junk folder</div>";
                     
                }else{


                    $reponse['RESPONSE'] = "ERROR";
                    $reponse['MESSAGE'] = $error_msg;

                }
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "Invalid Request Method";
            }






        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "Invalid Request Method";
        }

        if ($reponse['RESPONSE'] == "ERROR") {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        } else {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    }

    function verify_email_code(){
        
        $reponse = getcsrf($this);

        if ($this->input->server('REQUEST_METHOD') == 'POST'){

            if(isset($_POST['email'])&&isset($_POST['email_verification_code'])){
                    // if($this->session->has_userdata('email_for_verfi')
                    //  &&$this->session->has_userdata('email_verfi_code')){
                           
                            // $email_verification_code = strtolower($_POST['email_verification_code']);
                            
                            // $email_for_verfi = $this->session->userdata('email_for_verfi');
                            // $email_verfi_code = $this->session->userdata('email_verfi_code');
                            //$email_verfi_code = substr(md5($email), 0, 8);
                            
                            
                            //$token = $email_token;
                               $email_verfi_code = substr(md5(strrev($_POST['email'])), 0, 8);
                    
                            $email_verification_code = strtolower($_POST['email_verification_code']);
                         if( $email_verification_code== $email_verfi_code){
                              $reponse['RESPONSE'] = "Success";
                             $reponse['MESSAGE'] = "<div class='text-success'>Email Address is Verified</div>";  
                         }else{
                               $reponse['RESPONSE'] = "ERROR";
                             $reponse['MESSAGE'] = "<div class='text-danger'>Email Address not Verified</div>";  
                         }
                    //  }else{
                    //   $reponse['RESPONSE'] = "ERROR";
                    //     $reponse['MESSAGE'] = "First Click Send Email Verification Code Then Enter Verification Code";  
                    //  }
             


        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "Invalid Request Method";
        }

        if ($reponse['RESPONSE'] == "ERROR") {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        } else {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    
}
    
    }
    
    function send_mobile_code(){
        $this->load->model('User_model');
        $this->load->model('Sms_model');
        $reponse = getcsrf($this);

        if ($this->input->server('REQUEST_METHOD') == 'POST'){

            if(isset($_POST['mobile'])){

             
                $error_msg = "";
                $mobile = isValidData($_POST['mobile']);
                 
                if(!$mobile){
                    $error_msg .="Please provide Mobile No..!<br>";

                }elseif(strlen($mobile) != 10)
                {
                    $error_msg .="You have given invalid Mobile..!<br>";
                }
                // elseif(count($this->User_model->getUserByMobileAddress($mobile))>0){
                //   $error_msg .="mobile Address is Already Registered<br>Try to use different mobile Address or forget password to <a href='${base_url()}forget'>click here</a>"; 
                // }
               
                
               
                  if($error_msg==""){
                    $DATE =date('Y-m-d H:i:s');
                    $token = rand(10000000,99999999);
                   
                     if($this->session->has_userdata('mobile_for_verfi')
                     &&$this->session->has_userdata('mobile_verfi_code')){
                            
                            $mobile_for_verfi = $this->session->userdata('mobile_for_verfi');
                            $mobile_verfi_code = $this->session->userdata('mobile_verfi_code');
                            if($mobile_for_verfi == $mobile){
                                sendVerificationMobile($mobile,$mobile_verfi_code);
                            }else{
                                $this->session->set_userdata('mobile_for_verfi', $mobile);
                                 $this->session->set_userdata('mobile_verfi_code', $token);
                                sendVerificationMobile($mobile,$token);
                            }
                     }else{
                                $this->session->set_userdata('mobile_for_verfi', $mobile);
                                 $this->session->set_userdata('mobile_verfi_code', $token);
                                sendVerificationMobile($mobile,$token);  
                     }
                    $reponse['RESPONSE'] = "SUCCESS";
                    $reponse['MESSAGE'] = "<div class='text-success'>verification code has been sent at this $mobile kindly check your mobile for verification code.</div>";
                     
                }else{


                    $reponse['RESPONSE'] = "ERROR";
                    $reponse['MESSAGE'] = $error_msg;

                }
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "Invalid Request Method";
            }






        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "Invalid Request Method";
        }

        if ($reponse['RESPONSE'] == "ERROR") {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        } else {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    }

    function verify_mobile_code(){
        
        $reponse = getcsrf($this);

        if ($this->input->server('REQUEST_METHOD') == 'POST'){

            if(isset($_POST['mobile'])&&isset($_POST['mobile_verification_code'])){
                    if($this->session->has_userdata('mobile_for_verfi')
                     &&$this->session->has_userdata('mobile_verfi_code')){
                           $mobile_for_verfi = $this->session->userdata('mobile_for_verfi');
                            $mobile_verfi_code = $this->session->userdata('mobile_verfi_code');
                         if($mobile_for_verfi == $_POST['mobile'] && $_POST['mobile_verification_code']== $mobile_verfi_code){
                              $reponse['RESPONSE'] = "Success";
                             $reponse['MESSAGE'] = "<div class='text-success'>Mobile No is Verified</div>";  
                         }else{
                               $reponse['RESPONSE'] = "ERROR";
                             $reponse['MESSAGE'] = "<div class='text-danger'>Mobile No not Verified</div>";  
                         }
                     }else{
                       $reponse['RESPONSE'] = "ERROR";
                        $reponse['MESSAGE'] = "First Click Send mobile Verification Code Then Enter Verification Code";  
                     }
             


        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "Invalid Request Method";
        }

        if ($reponse['RESPONSE'] == "ERROR") {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        } else {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    
}
    
    }
    
      function check_cnic_already_exist(){
           $this->load->model('User_model');
        $this->load->model('Sms_model');
        $reponse = getcsrf($this);

        if ($this->input->server('REQUEST_METHOD') == 'POST'){

            if(isset($_POST['cnic'])){

             
                $error_msg = "";
                $cnic = isValidData($_POST['cnic']);
                 $data = $this->User_model->getUserByCnic($cnic);
                if(!$cnic || strlen($cnic)!=13){
                    $error_msg .="Please provide Valid  Cnic No..!<br>";

                }
                else if($data&&count($data)>0){
                   $error_msg .="<div class='text-warning'>$cnic Cnic No is Already Registered<br> <a href='${base_url()}forget'>click here to forget password</a></div>"; 
                }
               
                
               
                  if($error_msg==""){
                   
                       $reponse['RESPONSE'] = "SUCCESS";
                    $reponse['MESSAGE'] = "<div class='text-success'>Cnic Validate</div>";
                     
                  }else{


                    $reponse['RESPONSE'] = "ERROR";
                    $reponse['MESSAGE'] = $error_msg;

                }
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "Invalid Request Method";
            }






        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "Invalid Request Method";
        }

        if ($reponse['RESPONSE'] == "ERROR") {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        } else {
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    }
    
}