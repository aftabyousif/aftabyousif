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
    private $HomeController = 'advertisement/ug_advertisement';
    private $SelfController = 'Register';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';

    public function __construct()
    {
        parent::__construct();

        if($this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->HomeController);
            exit();
        }
    }

    function index(){
        $this->load->model('Api_location_model');
        $countries =$this->Api_location_model->getAllCountry();
        $data['countries']=$countries;
        $this->load->helper("form");
        $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
        $this->load->view('register',$data);
        $this->load->view('include/login_footer');
    }
    function user_register_handler(){
        $this->load->model('User_model');
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
                &&isset($_POST['PROVINCE_ID'])
            ){

                $cnic = null;
                $passport = null;
                $error_msg = "";
                if($_POST['check_cnic']==='cnic'){
                    $IS_CNIC_PASS = 'C';
                    $cnic = isValidData($_POST['cnic']);
                    $r_cnic = isValidData($_POST['retype_cnic']);
                    if(!($cnic&&$r_cnic&&$cnic===$r_cnic&&strlen($cnic)==13)){
                        $cnic = "";
                        $error_msg .="Invalid Cnic..!<br>";
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
                    $error_msg .="Invalid Surname..!<br>";

                }
                $f_name = strtoupper(isValidData($_POST['f_name']));
                if(!$f_name){
                    $error_msg .="Invalid Father Name..!<br>";

                }


                $email = isValidData($_POST['email']);
                if(!$email){
                    $error_msg .="Invalid Email..!<br>";

                }
                $email = strtolower($email);
                $mobile = isValidData($_POST['mobile']);
                if(!$mobile){
                    $error_msg .="Invalid mobile..!<br>";

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
                        $error_msg .="This CNIC No $cnic is already exist kindly use your personal cnic or bform to register yourself <br>";
                    }
                }else if($IS_CNIC_PASS=='C'){
                    $user = $this->User_model->getUserByPassport($passport);
                    if($user){
                        $error_msg .="This Passport No $passport is already exist kindly use your personal Passport to register yourself <br>";
                    }
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
                       "DISTRICT_ID"=>$DISTRICT_ID
                   );

                    $bol=$this->User_model->addUser($data);
                        if($bol === true){
                            $reponse['RESPONSE'] = "SUCCESS";
                            $reponse['MESSAGE'] = "Your account has been created successfully";
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



}