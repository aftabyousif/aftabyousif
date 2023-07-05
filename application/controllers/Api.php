<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/12/2020
 * Time: 12:28 PM
 */
header("Access-Control-Allow-Origin: *");
defined('BASEPATH') OR exit('No direct script access allowed');


class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Api_location_model');
         $this->load->model('User_model');
         $this->load->model('Application_model');
         $this->load->model('AdmitCard_model');

    }

    public function getAllProvince(){
        $result = json_encode($this->Api_location_model->getAllProvince());
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }
    public function getProvinceByCountryId(){
        $country_id = $this->input->get('country_id');

        if($country_id){

            $result = json_encode($this->Api_location_model->getProvinceByCountryId($country_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
                $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }

    }
    public function getProvinceById(){
        $province_id = $this->input->get('province_id');

        if($province_id){

            $result = json_encode($this->Api_location_model->getProvinceById($province_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }

    public function getAllCountry(){

        $result = json_encode($this->Api_location_model->getAllCountry());
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output($result);

    }
    public function getCountryById(){
        $country_id = $this->input->get('country_id');

        if($country_id){

            $result = json_encode($this->Api_location_model->getCountryById($country_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }

    public function getAllDistrict(){
        $result = json_encode($this->Api_location_model->getAllDistrict());
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }
    public function getDistrictByProvinceId(){
        $province_id = $this->input->get('province_id');

        if($province_id){

            $result = json_encode($this->Api_location_model->getDistrictByProvinceId($province_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }

    }
    public function getDistrictById(){
        $district_id = $this->input->get('district_id');

        if($district_id){

            $result = json_encode($this->Api_location_model->getProvinceById($district_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }

    public function getAllCity(){
        $result = json_encode($this->Api_location_model->getAllCity());
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }
    public function getCityByDistrictId(){
        $district_id = $this->input->get('district_id');

        if($district_id){

            $result = json_encode($this->Api_location_model->getCityByDistrictId($district_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }

    }
    public function getCityById(){
        $city_id = $this->input->get('city_id');

        if($city_id){

            $result = json_encode($this->Api_location_model->getCityById($city_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }
    public function get_candidate_information(){
        $cnic = $this->input->get('cnic');
        $dob = $this->input->get('dob');

        if(isValidData($cnic)&&isValidData($dob)){
            $cnic = isValidData($cnic);
            $result = $this->User_model->getUserByCnic($cnic);
            if($result){
                if($result['DATE_OF_BIRTH']==$dob){
                    
                    $list_of_application = $this->Application_model->getApplicationByUserIdForApi($result['USER_ID']);
                    
                   $data = array('CODE'=>"200","USER_INFORMATION"=>array(
                       "USER_ID"=>$result['USER_ID'],
                       "FIRST_NAME"=>$result['FIRST_NAME'],
                       "LAST_NAME"=>$result['LAST_NAME'],
                       "FNAME"=>$result['FNAME'],
                       "CNIC_NO"=>$result['CNIC_NO'],
                        "DATE_OF_BIRTH"=>$result['DATE_OF_BIRTH'],
                       "PROFILE_IMAGE"=>$result['PROFILE_IMAGE']
                       )
                   ,"LIST_OF_APPLICATION"=>$list_of_application);
                    
                      $this->output
                        ->set_status_header(200)
                        ->set_content_type('application/json', 'utf-8')
                        ->set_output(json_encode($data));
                        
                        
                }else{
                     $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('CODE'=>"404",'ERROR'=>"Invalid Date Of Birth.")));
                }
               
            }else{
                 $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('CODE'=>"404",'ERROR'=>"Invalid CNIC No.")));
            }
          
        }else{
                $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('CODE'=>"404",'ERROR'=>"invalid input")));
        }

    }
    public function get_all_candidate_list(){
        $session_id = 2;
        $program_type_id = 1;
        $date_time = '2021-10-31';
        $param = $this->input->get('param');
        $data = null;
        if($param=="count"){
            $data = $this->AdmitCard_model->getAdmitCardCountForApp($session_id,$program_type_id,$date_time);
        }else if($param=="data"){
            $limit = $this->input->get('limit');
            $offset = $this->input->get('offset');
            if(!$limit){
                $limit = 0;
            }
            if(!$offset){
                $offset = 0;
            }
            $data = $this->AdmitCard_model->getAdmitCardForApp($session_id,$program_type_id,$date_time,$limit,$offset);
        }
   
         $this->output
                        ->set_status_header(200)
                        ->set_content_type('application/json', 'utf-8')
                        ->set_output(json_encode($data));
       
    }
    
    /*
    public function get_candidate_by_roll_no (){
    
        if ($this->input->server('REQUEST_METHOD') == 'POST'){
            
                $postdata = file_get_contents("php://input");
              //  prePrint($postdata);
	        	$request= json_decode($postdata,true);
	        //prePrint($request);
	       // exit();
            if(!(isset($request['key']) && isset($request['roll_no']) && isValidData($request['key']) && isValidData($request['roll_no']))){
                $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('CODE'=>"404",'MESSAGE'=>"invalid input",'DATA'=>'')));
            }else{
        
                $key = isValidData($request['key']);
                $roll_no = isValidData($request['roll_no']);
               
               // prePrint($_POST);
                //prePrint($roll_no);
                // exit;
                if($key !=API_KEY){
                    
                $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('CODE'=>"404",'MESSAGE'=>"invalid api key",'DATA'=>'')));
                }else{
                   $record = $this->Application_model->get_application_by_roll_no($roll_no);
                   if(empty($record)){
                $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('CODE'=>"404",'MESSAGE'=>"Record not found.",'DATA'=>'')));
                   }else{
                $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('CODE'=>"200",'MESSAGE'=>"",'DATA'=>$record)));
                   
                       
                   }//else
                } //else api key
            } // else form validation
        }// request method
    }//method
    */
}