<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 4:07 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Candidate extends CI_Controller
{
    private $SelfController = 'Candidate';
    private $profile = 'Candidate/profile';
    private $LoginController = 'login';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    private $user ;
    private $file_size = 2048;
    public function __construct()
    {
        parent::__construct();

        if(!$this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->LoginController);
            exit();
        }else{
            $this->user = $this->session->userdata($this->SessionName);
        }
        $this->load->model('User_model');
        $this->load->model('Api_location_model');
        $this->load->model('Configuration_model');
        $this->load->model('Api_qualification_model');

    }

    function index(){
        $user = $this->session->userdata($this->SessionName);
        if($user){
            $data['user'] = $user;
            $data['profile_url'] = base_url().$this->profile;

            $this->load->view('include/header',$data);
            $this->load->view('include/preloder');
            $this->load->view('include/side_bar',$data);
            $this->load->view('include/nav',$data);
            $this->load->view('home',$data);
            $this->load->view('include/footer_area',$data);
            $this->load->view('include/footer',$data);
        }

    }

    function profile(){
        $user = $this->session->userdata($this->SessionName);

        $countries =$this->Api_location_model->getAllCountry();

        $prefixs = $this->Configuration_model->getPrefix();
        $prefixs = json_decode($prefixs['VALUE'],true);
        $blood_groups=array("A+","A-","B+","B-","O+","O-","AB+","AB-");

        if($user){

            $user = $this->User_model->getUserById($user['USER_ID']);

            $data['user'] = $user;
            $data['profile_url'] = base_url().$this->profile;
            $data['countries'] = $countries;
            $data['prefixs'] = $prefixs;
            $data['blood_groups'] = $blood_groups;

            $this->load->view('include/header',$data);
            $this->load->view('include/preloder');
            $this->load->view('include/side_bar',$data);
            $this->load->view('include/nav',$data);
            $this->load->view('profile',$data);
            $this->load->view('include/footer_area',$data);
            $this->load->view('include/footer',$data);
        }

    }

    function updateProfile(){
        $reponse = getcsrf($this);
        $user = $this->User_model->getUserById($this->user['USER_ID']);
        $USER_ID = $user['USER_ID'] ;

        $PROFILE_IMAGE = "";
        if($user['PROFILE_IMAGE']){
            $PROFILE_IMAGE = PROFILE_IMAGE_CHECK_PATH.$user['PROFILE_IMAGE'] ;
        }

        if(!(!empty($PROFILE_IMAGE)&&file_exists($PROFILE_IMAGE)&&(filesize($PROFILE_IMAGE)>0))){

            $PROFILE_IMAGE="";
            $user['PROFILE_IMAGE']="";
        }
        $IS_CNIC_PASS = $user['IS_CNIC_PASS'] ;
        $PASSPORT_EXPIRY=$user['PASSPORT_EXPIRY'];
        $CNIC_EXPIRY=$user['CNIC_EXPIRY'];
        $error ="";

        if(isset($_POST['FIRST_NAME'])&&isValidData($_POST['FIRST_NAME'])){
            $FIRST_NAME = strtoupper(isValidData($_POST['FIRST_NAME']));
        }else{
            $error.="<div class='text-danger'>Name Must be Enter</div>";
        }
        if(isset($_POST['PREFIX_ID'])&&isValidData($_POST['PREFIX_ID'])){
            $PREFIX_ID = isValidData($_POST['PREFIX_ID']);
        }else{
            $error.="<div class='text-danger'>PREFIX Must be Select</div>";
        }

//        if(isset($_POST['EMAIL'])&&isValidData($_POST['EMAIL'])){
//            $EMAIL = strtolower(isValidData($_POST['EMAIL']));
//            $this->form_validation->set_rules('EMAIL','Email','required|valid_email');
//            if($this->form_validation->run()==false){
//                $error.="<div class='text-danger'>Please Provide Valid Email</div>";
//            }
//        }else{
//            $error.="<div class='text-danger'>Email Must be Enter</div>";
//        }
        if(isset($_POST['MOBILE_NO'])&&isValidData($_POST['MOBILE_NO'])){
            $MOBILE_NO = isValidData($_POST['MOBILE_NO']);
            if(strlen($MOBILE_NO)>=12 ||strlen($MOBILE_NO)<=9){
                $error.="<div class='text-danger'>Invalid Mobile</div>";
            }
        }else{
            $error.="<div class='text-danger'>Mobile Must be Enter</div>";
        }

        if(isset($_POST['LAST_NAME'])&&isValidData($_POST['LAST_NAME'])){
            $LAST_NAME = strtoupper(isValidData($_POST['LAST_NAME']));
        }else{
            $error.="<div class='text-danger'>Last Name / Surname Must be Enter</div>";
        }
        if(isset($_POST['FNAME'])&&isValidData($_POST['FNAME'])){
            $FNAME = strtoupper(isValidData($_POST['FNAME']));
        }else{
            $error.="<div class='text-danger'>Father Name Must be Enter</div>";
        }

        if(isset($_POST['MOBILE_CODE'])&&isValidData($_POST['MOBILE_CODE'])){
            $MOBILE_CODE = isValidData($_POST['MOBILE_CODE']);
        }
        if(isset($_POST['PLACE_OF_BIRTH'])&&isValidData($_POST['PLACE_OF_BIRTH'])){
            $PLACE_OF_BIRTH = strtoupper(isValidData($_POST['PLACE_OF_BIRTH']));
        }
        if(isset($_POST['HOME_ADDRESS'])&&isValidData($_POST['HOME_ADDRESS'])){
            $HOME_ADDRESS = strtoupper(isValidData($_POST['HOME_ADDRESS']));
        }else{
            $error.="<div class='text-danger'>Home Address Must be Enter</div>";
        }
        if(isset($_POST['PERMANENT_ADDRESS'])&&isValidData($_POST['PERMANENT_ADDRESS'])){
            $PERMANENT_ADDRESS = strtoupper(isValidData($_POST['PERMANENT_ADDRESS']));
        }else{
            $error.="<div class='text-danger'>Permanent Address Must be Enter</div>";
        }
        if(isset($_POST['COUNTRY_ID'])&&isValidData($_POST['COUNTRY_ID'])){
            $COUNTRY_ID = isValidData($_POST['COUNTRY_ID']);
        }else{
            $error.="<div class='text-danger'>Country Must be Select</div>";
        }
        if(isset($_POST['PROVINCE_ID'])&&isValidData($_POST['PROVINCE_ID'])){
            $PROVINCE_ID = isValidData($_POST['PROVINCE_ID']);
        }else{
            $error.="<div class='text-danger'>Province Must be Select</div>";
        }
        if(isset($_POST['DISTRICT_ID'])&&isValidData($_POST['DISTRICT_ID'])){
            $DISTRICT_ID = isValidData($_POST['DISTRICT_ID']);
        }else{
            $error.="<div class='text-danger'>District Must be Select</div>";
        }
        if(isset($_POST['CITY_ID'])&&isValidData($_POST['CITY_ID'])){
            $CITY_ID = isValidData($_POST['CITY_ID']);
        }else{
            $error.="<div class='text-danger'>City Must be Select</div>";
        }

        if(isset($_POST['DATE_OF_BIRTH'])&&isValidTimeDate($_POST['DATE_OF_BIRTH'],'d/m/Y')){
            $DATE_OF_BIRTH = getDateForDatabase($_POST['DATE_OF_BIRTH']);
            if($DATE_OF_BIRTH>date('Y-m-d')){
                $error.="<div class='text-danger'>Choose Valid Date Of Bith</div>";
            }
        }else{
            $error.="<div class='text-danger'>Date Of Birth Must be Choose</div>";
        }
        if(isset($_POST['ZIP_CODE'])&&isValidData($_POST['ZIP_CODE'])){
            $ZIP_CODE = strtoupper(isValidData($_POST['ZIP_CODE']));
        }
        if(isset($_POST['BLOOD_GROUP'])&&isValidData($_POST['BLOOD_GROUP'])){
            $BLOOD_GROUP = isValidData($_POST['BLOOD_GROUP']);
        }else{
            $error.="<div class='text-danger'>Blood Group Must be Select</div>";
        }
        if(isset($_POST['CNIC_EXPIRY'])&&isValidTimeDate($_POST['CNIC_EXPIRY'],'d/m/Y')){
            $CNIC_EXPIRY = getDateForDatabase($_POST['CNIC_EXPIRY']);
        }
        if(isset($_POST['PASSPORT_EXPIRY'])&&isValidTimeDate($_POST['PASSPORT_EXPIRY'],'d/m/Y')){
            $PASSPORT_EXPIRY = getDateForDatabase($_POST['PASSPORT_EXPIRY']);
        }

        if(isset($_POST['GENDER'])&&isValidData($_POST['GENDER'])){
            $GENDER = isValidData($_POST['GENDER']);
        }else{
            $error.="<div class='text-danger'>Gender Must be select</div>";
        }

        if (isset($_FILES['profile_image'])) {
            // prePrint($_FILES['profile_image'][]);
            if (isValidData($_FILES['profile_image']['name'])) {

                $res =  $this->upload_image('profile_image',"profile_image_".$this->user['USER_ID']);
                if($res['STATUS']===true){
                    $PROFILE_IMAGE = $res['IMAGE_NAME'];

                }else{
                    $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                }
            } else {
                if ($user['PROFILE_IMAGE']) {
                    $PROFILE_IMAGE = $user['PROFILE_IMAGE'];
                } else {
                    $error .= "<div class='text-danger'>Must Upload Profile Picture</div>";
                }
            }
        } else {
            if ($user['PROFILE_IMAGE']) {
                $PROFILE_IMAGE = $user['PROFILE_IMAGE'];
            } else {
                $error .= "<div class='text-danger'>Must Upload Profile Picture</div>";
            }
        }

        $form_array = array(
            "FIRST_NAME"=>$FIRST_NAME,
            "PREFIX_ID"=>$PREFIX_ID,
            "MOBILE_NO"=>$MOBILE_NO,
            "LAST_NAME"=>$LAST_NAME,
            "FNAME"=>$FNAME,
            "MOBILE_CODE"=>$MOBILE_CODE,
            "PLACE_OF_BIRTH"=>$PLACE_OF_BIRTH,
            "HOME_ADDRESS"=>$HOME_ADDRESS,
            "PERMANENT_ADDRESS"=>$PERMANENT_ADDRESS,
            "COUNTRY_ID"=>$COUNTRY_ID,
            "PROVINCE_ID"=>$PROVINCE_ID,
            "DISTRICT_ID"=>$DISTRICT_ID,
            "CITY_ID"=>$CITY_ID,
            "DATE_OF_BIRTH"=>$DATE_OF_BIRTH,
            "ZIP_CODE"=>$ZIP_CODE,
            "BLOOD_GROUP"=>$BLOOD_GROUP,
            "CNIC_EXPIRY"=>$CNIC_EXPIRY,
            "PASSPORT_EXPIRY"=>$PASSPORT_EXPIRY,
            "GENDER"=>$GENDER,
            "PROFILE_IMAGE"=>$PROFILE_IMAGE,
        );

        if($error==""){
            $res = $this->User_model->updateUserById($USER_ID,$form_array);
            if($res===1){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>Successfully Save ..!<div>";
            }else if($res===0){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>No data has been changed..!<div>";
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "<div class='text-danger'>Something went worng..!</div>";
            }

        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = $error;
        }


        if($reponse['RESPONSE'] == "ERROR"){
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }else{
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }

    }

    function apiGetQualificationList(){
        $user = $this->session->userdata($this->SessionName);
        $qulificationList = $this->Api_qualification_model->getQulificatinByUserId($user['USER_ID']);
        $output= "<div style='overflow-x:auto'>
            <table class='table table-bordered' >
                <tr>
                    <th>Qualification / Degree / Certificate</th>
                    <th>Discipline / Subject / Group</th>
                    <th>Organization / University / Board</th>
                    <th>Institute / Department / School / College</th>
                    <th>Total Marks</th>
                    <th>Obtained Marks</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th colspan='2'>ACTION</th>
                </tr>";
        foreach($qulificationList as $degree) {
            $output.= "<tr>
                        <td>{$degree['DEGREE_TITLE']}</td>
                        <td>{$degree['DISCIPLINE_NAME']}</td>
                        <td>{$degree['ORGANIZATION']}</td>
                        <td>{$degree['INSTITUTE']}</td>
                        <td>{$degree['TOTAL_MARKS']}</td>
                        <td>{$degree['OBTAINED_MARKS']}</td>
                        <td>".getDateCustomeView($degree['START_DATE'],'d,M,Y')."</td>
                        <td>".getDateCustomeView($degree['END_DATE'],'d,M,Y')."</td>
                        <td><button class='btn btn-info' onclick=\"editQulification('{$degree['QUALIFICATION_ID']}') \">Edit</button></td>
                        <td><button onclick=\"deleteQulification('{$degree['QUALIFICATION_ID']}')\" class='btn btn-danger'>Delete</button></td>
                    </tr>";


        }


        $output .="</table></div>";
        echo $output;
    }

    function apiGetAddQualificationForm(){
        $user = $this->session->userdata($this->SessionName);

        $degree = $this->Api_qualification_model->getAllDegreeProgram();
        $organization = $this->Api_qualification_model->getAllOrganization();
        $data['degree_program'] = $degree;
        $data['organizations'] = $organization;
        $this->load->view('qulification_form',$data);
    }
    function apiDeleteQualification(){
        $reponse['RESPONSE'] = "ERROR";
        $reponse['MESSAGE'] = "YES";
        if($reponse['RESPONSE'] == "ERROR"){
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
        else{
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    }
    function apiGetInstituteByOrgId(){
        $user = $this->session->userdata($this->SessionName);


        $ORG_ID = $this->input->get('ORG_ID');


        if($ORG_ID){
            $institutes = $this->Api_qualification_model->getInstituteByOrgId($ORG_ID);
            echo "<option value='0'>--Choose--</option>";
            foreach ($institutes as $institute) {

                echo "<option value='{$institute['INSTITUTE_ID']}'  >{$institute['INSTITUTE_NAME']}</option>";
            }
            echo "<option value='-1'>--Other--</option>";
        }else{
            echo "<option value='0'>--Choose--</option>";
        }


    }

    function apiGetDisciplineById(){
        $user = $this->session->userdata($this->SessionName);


        $DEGREE_ID = $this->input->get('DEGREE_ID');


        if($DEGREE_ID){
            $disciplines = $this->Api_qualification_model->getDisciplineByDegreeId($DEGREE_ID);
            echo "<option value='0'>--Choose--</option>";


            foreach ($disciplines as $discipline) {

                echo "<option value='{$discipline['DISCIPLINE_ID']}'  >{$discipline['DISCIPLINE_NAME']}</option>";
            }

        }else{
            echo "<option value='0'>--Choose--</option>";
        }


    }

    function addQualification(){
        $reponse = getcsrf($this);
        $USER_ID = $this->user['USER_ID'];
        $folder = EXTRA_IMAGE_CHECK_PATH."$USER_ID";
        //echo $folder;
        if(!is_dir($folder)){
            // $folder;
            mkdir(EXTRA_IMAGE_CHECK_PATH."/$USER_ID");
        }
        $reponse['RESPONSE'] = "ERROR";
        $reponse['MESSAGE'] = "<div class='text-danger'>This is starting message there is no error..!</div>";

        $ROLL_NO = $grade = "";
        $IS_DECLARE = 'N';
        $RESULT_DATE = $START_DATE = $END_DATE = '1900-01-01';
        $cgpa = $out_of = $DEGREE_ID = $DISCIPLINE_ID = $INSTITUTE_ID = $ORGANIZATION_ID = $TOTAL_MARKS = $OBTAINED_MARKS = 0;
        $error = "";

        if (isset($_POST['DEGREE_ID']) && isValidData($_POST['DEGREE_ID'])) {
            $DEGREE_ID = (int)isValidData($_POST['DEGREE_ID']);
        } else {
            $error .= "<div class='text-danger'>Qualification / Degree / Certificate must be select</div>";
        }

        if (isset($_POST['check_grade'])) {
            if ($_POST['check_grade'] == 'grade') {
                $GRADING_AS = "G";
                if (isset($_POST['grade']) && isValidData($_POST['grade'])) {
                    $grade = isValidData($_POST['grade']);
                } else {
                    $error .= "<div class='text-danger'>Grade must be select</div>";
                }
            } else if ($_POST['check_grade'] == 'cgpa') {
                $GRADING_AS = "C";
                if (isset($_POST['cgpa']) && isValidData($_POST['cgpa'])) {
                    $cgpa = isValidData($_POST['cgpa']);
                } else {
                    $error .= "<div class='text-danger'>CGPA Must Enter</div>";
                }
                if (isset($_POST['out_of']) && isValidData($_POST['out_of'])) {
                    $out_of = isValidData($_POST['out_of']);
                } else {
                    $error .= "<div class='text-danger'>Out Of must select</div>";
                }

                if (!is_numeric($cgpa)) {
                    $error .= "<div class='text-danger'> Must Enter Valid CGPA / Percentage</div>";
                } else {
                    $cgpa = number_format((float)$cgpa, 2, '.', '');
                    if ($cgpa > $out_of) {
                        $error .= "<div class='text-danger'> Must Enter Valid CGPA / Percentage</div>";
                    }
                }


            }
        }
        if (!isset($_POST['result_not_declare'])) {
            $IS_DECLARE = 'Y';
            if (isset($_POST['RESULT_DATE']) && isValidTimeDate($_POST['RESULT_DATE'], 'd/m/Y')) {
                $RESULT_DATE = getDateForDatabase($_POST['RESULT_DATE']);
            } else {
                $error .= "<div class='text-danger'>Result Declare Date must be Filled</div>";
            }

        }


        if (isset($_POST['INSTITUTE_ID']) && isValidData($_POST['INSTITUTE_ID'])) {
            $INSTITUTE_ID = isValidData($_POST['INSTITUTE_ID']);
            if ($INSTITUTE_ID <= 0) {
                $error .= "<div class='text-danger'>Institute / Department / School / College must be select</div>";
            }
        } else {
            $error .= "<div class='text-danger'>Institute / Department / School / College must be select</div>";
        }
        if (isset($_POST['ORGANIZATION_ID']) && isValidData($_POST['ORGANIZATION_ID'])) {
            $ORGANIZATION_ID = isValidData($_POST['ORGANIZATION_ID']);
        } else {
            $error .= "<div class='text-danger'>Organization / University / Board must be select</div>";
        }
        if (isset($_POST['DISCIPLINE_ID']) && isValidData($_POST['DISCIPLINE_ID'])) {
            $DISCIPLINE_ID = isValidData($_POST['DISCIPLINE_ID']);
            if ($DISCIPLINE_ID <= 0) {
                $error .= "<div class='text-danger'>Discipline / Subject / Group must be select</div>";
            }
        } else {
            $error .= "<div class='text-danger'>Discipline / Subject / Group must be select</div>";
        }
        if (isset($_POST['ROLL_NO']) && isValidData($_POST['ROLL_NO'])) {
            $ROLL_NO = isValidData($_POST['ROLL_NO']);
        } else {
            $error .= "<div class='text-danger'>Roll Number must be Filled</div>";
        }
        if (isset($_POST['TOTAL_MARKS']) && isValidData($_POST['TOTAL_MARKS'])) {
            $TOTAL_MARKS = isValidData($_POST['TOTAL_MARKS']);
        } else {
            if ($DEGREE_ID != 8)
                $error .= "<div class='text-danger'>Total Marks must be Filled</div>";
        }
        if (isset($_POST['OBTAINED_MARKS']) && isValidData($_POST['OBTAINED_MARKS'])) {
            $OBTAINED_MARKS = isValidData($_POST['OBTAINED_MARKS']);
        } else {
            if ($DEGREE_ID != 8)
                $error .= "<div class='text-danger'>Obtained Marks must be Filled</div>";
        }


        if (isset($_POST['START_DATE']) && isValidTimeDate($_POST['START_DATE'], 'd/m/Y')) {
            $START_DATE = getDateForDatabase($_POST['START_DATE']);
        } else {
            $error .= "<div class='text-danger'>Start Date must be Filled</div>";
        }
        if (isset($_POST['END_DATE']) && isValidTimeDate($_POST['END_DATE'], 'd/m/Y')) {
            $END_DATE = getDateForDatabase($_POST['END_DATE']);
        } else {
            $error .= "<div class='text-danger'>End Date must be Filled</div>";
        }
        if ($END_DATE <= $START_DATE) {
            $error .= "<div class='text-danger'>Invalid Date </div>";
        }



        if ($DEGREE_ID != 8 && $TOTAL_MARKS <= 0) {
            $error .= "<div class='text-danger'>Invalid Total Marks </div>";
        }
        if ($DEGREE_ID != 8 && $OBTAINED_MARKS <= 0) {
            $error .= "<div class='text-danger'>Invalid Obtained Marks </div>";
        }
        if ($DEGREE_ID == 8 && $TOTAL_MARKS < 0) {
            $error .= "<div class='text-danger'>Invalid Total Marks </div>";
        }
        if ($DEGREE_ID == 8 && $OBTAINED_MARKS < 0) {
            $error .= "<div class='text-danger'>Invalid Obtained Marks </div>";
        }


        if ($TOTAL_MARKS < $OBTAINED_MARKS) {
            $error .= "<div class='text-danger'>Obtained Marks must be less then or Equal to Total Marks </div>";
        }


        $degree_name =  $this->Api_qualification_model->getDegreeProgramById($DEGREE_ID);
        $degree_name = str_replace(' ','_',$degree_name['DEGREE_TITLE']);
        $degree_name = str_replace('/','_',$degree_name);
        $config_a = array();
        $config_a['maintain_ratio'] = true;
        $config_a['width']         = 360;
        $config_a['height']       = 500;

        $config_a['resize']       = false;

        if(isset($_FILES['marksheet_image'])){
            if (isValidData($_FILES['marksheet_image']['name'])) {

                $file_path = EXTRA_IMAGE_CHECK_PATH."$USER_ID/";
                $image_name = $degree_name."_marksheet_image_$USER_ID";
                $res =  $this->upload_image('marksheet_image',$image_name,$this->file_size,$file_path,$config_a);
                if($res['STATUS']===true){
                    $marksheet_image = "$USER_ID/".$res['IMAGE_NAME'];

                }else{
                    $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                }
            }else{
                if($DEGREE_ID!=8)
                    $error.="<div class='text-danger'>Must Upload Marksheet and image size must be less then 1mb </div>";
            }
        }
        else{
            if($DEGREE_ID!=8)
                $error.="<div class='text-danger'>Must Upload Marksheet and image size must be less then 200kb Id Not found something went worng </div>";
        }

        if(isset($_FILES['passcertificate_image'])){
            if (isValidData($_FILES['passcertificate_image']['name'])) {

                $file_path = EXTRA_IMAGE_CHECK_PATH."$USER_ID/";
                $image_name = $degree_name."_passcertificate_image_$USER_ID";
                $res =  $this->upload_image('passcertificate_image',$image_name,$this->file_size,$file_path,$config_a);
                if($res['STATUS']===true){
                    $passcertificate_image = "$USER_ID/".$res['IMAGE_NAME'];
                }else{
                    $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                }
            }else{
                if($DEGREE_ID!=8)
                    $error.="<div class='text-danger'>Must Upload Pass Certificate and image size must be less then 1mb </div>";
            }

        }else{
            $error.="<div class='text-danger'>Must Upload Pass Certificate and image size must be less then 200kb Id Not found something went worng </div>";
        }
        $if_exist = $this->Api_qualification_model->getQulificatinByUserIdAndDegreeId($USER_ID,$DEGREE_ID);
        if(count($if_exist)) {
            $error.="<div class='text-danger'>Same Qualification is Already Exist Please Update OR Delete...!</div>";
        }

        if($error==""){



            $form_array = array(
                "USER_ID"           =>  $USER_ID,
                "DISCIPLINE_ID"     =>$DISCIPLINE_ID,
                "ORGANIZATION_ID"   =>  $ORGANIZATION_ID,
                "INSTITUTE_ID"      =>  $INSTITUTE_ID,
                "START_DATE"        =>  $START_DATE,
                "END_DATE"          =>  $END_DATE,
                "RESULT_DATE"       =>  $RESULT_DATE,
                "TOTAL_MARKS"       =>  $TOTAL_MARKS,
                "OBTAINED_MARKS"    =>  $OBTAINED_MARKS,
                "CGPA"              =>  $cgpa,
                "GRADING_AS"        =>  $GRADING_AS,
                "GRADE"             =>  $grade,
                "ACTIVE"            =>  1,
                "IS_RESULT_DECLARE" =>  $IS_DECLARE,
                "ROLL_NO"           =>  $ROLL_NO,
                "OUT_OF"            =>  $out_of,
                "MARKSHEET_IMAGE"   =>  $marksheet_image,
                "PASSCERTIFICATE_IMAGE"=>  $passcertificate_image,
            );
            $res = $this->Api_qualification_model->addQulification($form_array);



            if($res===true){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>Successfully Save ..!<div>";
            }else if($res===0){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>No data has been changed..!<div>";
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "<div class='text-danger'>Something went worng..!</div>";
            }

        }
        else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = $error;
        }

        if($reponse['RESPONSE'] == "ERROR"){
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
        else{
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($reponse));
        }
    }
    private function upload_image($index_name,$image_name,$max_size = 50,$path = '../eportal_resource/images/applicants_profile_image/',$con_array=array())
    {

        $config['upload_path']          = $path;
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = $max_size;
        $config['max_width']            = 0;
        $config['max_height']           = 0;
        $config['file_name']			= $image_name;
        $config['overwrite']			= true;

        if(isset($this->upload)){
            $this->upload =  null;
        }
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($index_name))
        {
            return array("STATUS"=>false,"MESSAGE"=>$this->upload->display_errors());
        }
        else
        {
            $image_data = $this->upload->data();

            $image_path = $image_data['full_path'];

            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['create_thumb'] = FALSE;
            if(!count($con_array)){
                $config['maintain_ratio'] = TRUE;
                $config['width']         = 180;
                $config['height']       = 260;
            }else{
                if(isset($con_array['maintain_ratio'])){
                    $config['maintain_ratio']=$con_array['maintain_ratio'];
                }

                if(isset($con_array['width'])){
                    $config['width']=$con_array['width'];
                }

                if(isset($con_array['height'])){
                    $config['height']=$con_array['height'];
                }
            }
            if(isset($this->image_lib)){
                $this->image_lib =  null;
            }
            if(isset($con_array['resize'])){
                if($con_array['resize']===true){
                    $this->load->library('image_lib',$config);

                    $this->image_lib->resize();
                }
            }else{
                $this->load->library('image_lib',$config);

                $this->image_lib->resize();

            }

            return array("STATUS"=>true,"IMAGE_NAME"=>$image_data['file_name']);

        }
    }


}