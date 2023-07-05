<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 9/16/2020
 * Time: 10:28 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';
class AdminApi extends AdminLogin
{
    private $script_name = "";
    private $file_size = 500;
    private $user = null;
    public function __construct(){
        parent::__construct();

        $this->load->model('Administration');
        $this->load->model('log_model');
        $this->load->model('Api_qualification_model');
        $this->load->model('Api_location_model');
        $this->load->model('User_model');
        $this->load->model('Application_model');
        $this->load->model('Admission_session_model');
        $this->load->model('Selection_list_report_model');
        $this->load->model('TestResult_model');
        //$this->load->library('javascript');
        $self = $_SERVER['PHP_SELF'];
        $self = explode('index.php/',$self);
        $this->script_name = $self[1];
        $this->verify_login();
    }

    function apiGetQualificationList($user_id){
        $APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
       
        $qulificationList = $this->Api_qualification_model->getQualificatinByUserId($user_id,$APPLICATION_ID);
        $output= "<div style='overflow-x:auto'>
            <table class='table table-bordered' >
                <tr>
                    <th>Qualification / Degree / Certificate</th>
                    <th>Discipline / Subject / Group</th>
                    <th>Organization / University / Board</th>
                   
                    <th>Roll No</th>
                    <th>Total Marks</th>
                    <th>Obtained Marks</th>
                    <th>Marksheet</th>
                    <th>Pass certificate</th>
                    <th>Passing Year</th>
                    
                    <th colspan='2'>ACTION</th>
                </tr>";

        foreach($qulificationList as $degree) {
            $edit_button="";
            $delete_button="";
            //if($degree['STATUS']==0){
                $edit_button = "<button class='btn btn-info' onclick=\"editQualification('{$degree['QUALIFICATION_ID']}') \"><i class='fa fa-pencil-square-o'></i> Edit</button>";
                $delete_button ="<button onclick=\"deleteQualification('{$degree['QUALIFICATION_ID']}')\" class='btn btn-danger'><i class='fa fa-trash'></i> Delete</button>";
            //}
            if($degree['DEGREE_ID']==10){
                continue;
            }
            $output.= "<tr>
                        <td>{$degree['DEGREE_TITLE']}</td>
                        <td>{$degree['DISCIPLINE_NAME']}</td>
                        <td>{$degree['ORGANIZATION']}</td>
                        
                        <td>{$degree['ROLL_NO']}</td>
                        <td>{$degree['TOTAL_MARKS']}</td>
                        <td>{$degree['OBTAINED_MARKS']}</td>
                        <td><img class='img-table-cert' src='".itsc_url().EXTRA_IMAGE_PATH.$degree['MARKSHEET_IMAGE']."' alt='MARKSHEET_IMAGE'></td>
                        <td><img class='img-table-cert' src='".itsc_url().EXTRA_IMAGE_PATH.$degree['PASSCERTIFICATE_IMAGE']."' alt='PASSCERTIFICATE_IMAGE'></td>
                        <td>{$degree['PASSING_YEAR']}</td>
                        <td>$edit_button</td>
                        <td>$delete_button</td>
                    </tr>";


        }


        $output .="</table> 
                    
                    </div>
                    <style>
                    .img-table-certificate{
                    border: #40402e;
                    border-style: double;
                    border-radius: 10%;
                    }
                    </style>
                    <script>
                     $( '.img-table-cert' ).click(function() {
            alertImage('Image',$(this).attr('src'));
        });
                    </script>
                    
                    ";
        echo $output;
    }

    function apiGetAddQualificationForm(){
        //$user = $this->session->userdata($this->SessionName);

        $degree = $this->Api_qualification_model->getAllDegreeProgram();
        $organization = $this->Api_qualification_model->getAllOrganization();
        $program_type_id = 0;
        if(isset($_GET['program_type_id'])){
            $program_type_id = $_GET['program_type_id'];
        }
        $data['degree_program'] = $degree;
        $data['organizations'] = $organization;
        $data['program_type_id'] = $program_type_id;
        $this->load->view('admin/qualification_form',$data);
    }

    function apiGetEditQualificationForm($user_id){
        $qul_id = $this->input->get('qualification_id');
        if($qul_id){
             $APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
            $user = $this->session->userdata($this->SessionName);

            $data['qualification'] =$qualification= $this->Api_qualification_model->getQualificationByUserIdAndQulificationId($user_id,$qul_id,$APPLICATION_ID);
            if(isset($data['qualification'])&&$data['qualification']) {
                $program_type_id = 0;
                if(isset($_GET['program_type_id'])){
                    $program_type_id = $_GET['program_type_id'];
                }
                $degree = $this->Api_qualification_model->getAllDegreeProgram();

                $organization = $this->Api_qualification_model->getAllOrganization();
                $institute = $this->Api_qualification_model->getInstituteByOrgId($qualification['ORGANIZATION_ID']);
                $discipline = $this->Api_qualification_model->getDisciplineByDegreeId($qualification['DEGREE_ID']);

                $data['degree_program'] = $degree;
                $data['organizations'] = $organization;
                $data['institutes'] = $institute;
                $data['disciplines'] = $discipline;
                $data['program_type_id'] = $program_type_id;
                $data['user_id'] = $user_id;
                $this->load->view('admin/edit_qualification_form', $data);
            }else{
                echo "<div class='text-danger'>Something went wrong</div>";
            }
        }else{
            echo "<div class='text-danger'>Something went wrong</div>";
        }

    }

    function addQualification($USER_ID){
        $APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
        $reponse = getcsrf($this);
        //$USER_ID = $this->user['USER_ID'];
        $folder = EXTRA_IMAGE_CHECK_PATH."$USER_ID";
        //echo $folder;
        if(!is_dir($folder)){
            // $folder;
            mkdir(EXTRA_IMAGE_CHECK_PATH."/$USER_ID");
        }
        $ROLL_NO = $grade = "";
        $IS_DECLARE = 'N';
        $PASSING_YEAR =$RESULT_DATE = $START_DATE = $END_DATE = '1900-01-01';
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
                    //$error .= "<div class='text-danger'>Grade must be select</div>";
                }
            } else if ($_POST['check_grade'] == 'cgpa') {
                $GRADING_AS = "C";
                if (isset($_POST['cgpa']) && isValidData($_POST['cgpa'])) {
                    $cgpa = isValidData($_POST['cgpa']);
                } else {
                   // $error .= "<div class='text-danger'>CGPA Must Enter</div>";
                }
                if (isset($_POST['out_of']) && isValidData($_POST['out_of'])) {
                    $out_of = isValidData($_POST['out_of']);
                } else {
                   // $error .= "<div class='text-danger'>Out Of must select</div>";
                }

                if (!is_numeric($cgpa)) {
                    //$error .= "<div class='text-danger'> Must Enter Valid CGPA / Percentage</div>";
                } else {
                    $cgpa = number_format((float)$cgpa, 2, '.', '');
                    if ($cgpa > $out_of) {
                    //    $error .= "<div class='text-danger'> Must Enter Valid CGPA / Percentage</div>";
                    }
                }


            }
        }
        if (!isset($_POST['result_not_declare'])) {
            $IS_DECLARE = 'Y';
            if (isset($_POST['RESULT_DATE']) && isValidTimeDate($_POST['RESULT_DATE'], 'd/m/Y')) {
                $RESULT_DATE = getDateForDatabase($_POST['RESULT_DATE']);
            } else {
                // $error .= "<div class='text-danger'>Result Declare Date must be Filled</div>";
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
            if($TOTAL_MARKS<100){
                $error .= "<div class='text-danger'>Total Marks must be Filled</div>";
            }
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

        if (isset($_POST['PASSING_YEAR']) && isValidData($_POST['PASSING_YEAR'])) {
            $PASSING_YEAR = isValidData($_POST['PASSING_YEAR']);
        } else {

            $error .= "<div class='text-danger'>Passing Year Must be Select</div>";
        }


        if (isset($_POST['START_DATE']) && isValidTimeDate($_POST['START_DATE'], 'd/m/Y')) {
            $START_DATE = getDateForDatabase($_POST['START_DATE']);
        } else {
            // $error .= "<div class='text-danger'>Start Date must be Filled</div>";
        }
        if (isset($_POST['END_DATE']) && isValidTimeDate($_POST['END_DATE'], 'd/m/Y')) {
            $END_DATE = getDateForDatabase($_POST['END_DATE']);
        } else {
            //$error .= "<div class='text-danger'>End Date must be Filled</div>";
        }
        if ($END_DATE <= $START_DATE) {
            // $error .= "<div class='text-danger'>Invalid Date Start Date must be less then End Date</div>";
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
                    $error.="<div class='text-danger'>Must Upload Marksheet and image size must be less then 500kb </div>";
            }
        }
        else{
            if($DEGREE_ID!=8)
                $error.="<div class='text-danger'>Must Upload Marksheet and image size must be less then 500kb Id Not found something went worng </div>";
        }
        $passcertificate_image = "";
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
                if(!($DEGREE_ID==8 || $DEGREE_ID==10))
                    $error.="<div class='text-danger'>Must Upload Pass Certificate and image size must be less then 500kb </div>";
            }

        }
        else{
            if(!($DEGREE_ID==8 || $DEGREE_ID==10))
                $error.="<div class='text-danger'>Must Upload Pass Certificate and image size must be less then 500kb Id Not found something went worng </div>";
        }


        $if_exist = $this->Api_qualification_model->getQualificatinByUserIdAndDegreeId($USER_ID,$DEGREE_ID,$APPLICATION_ID);

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
                "PASSING_YEAR"=>  $PASSING_YEAR,
            );
            $res = $this->Api_qualification_model->addQualification($form_array,$APPLICATION_ID);



            if($res===true){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>Successfully Save ..!<div>";
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
            if(!isset($reponse['MESSAGE'])){
                $reponse['MESSAGE'] = "<div class='text-danger'>Message Not Defined..!</div>";
            }
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

    function updateQualification($USER_ID){
         $APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
         $admin = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $admin_id = $admin['USER_ID'];
        $reponse = getcsrf($this);
        //$USER_ID = $this->user['USER_ID'];
        $error = "";
        $QUAL_ID = 0;
         $this->user =  array("USER_ID"=>$USER_ID) ;
        if(isset($_POST['QUAL_ID'])&& isValidData($_POST['QUAL_ID'])&&is_numeric($_POST['QUAL_ID'])){
            $QUAL_ID = (int)isValidData($_POST['QUAL_ID']);
        }else{
            $error .= "<div class='text-danger'>Invalid Qualification Id</div>";
        }
        $STUDENT_APPLICATION_ID = 0;
        if($this->session->has_userdata('STUDENT_APPLICATION_ID')){
            $STUDENT_APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
        }else{
            $error.="<div class='text-danger'>Application Id Not Found</div>";
        }


        $qualification = $this->Api_qualification_model->getQualificationByUserIdAndQulificationId($USER_ID,$QUAL_ID,$APPLICATION_ID);

        if(!$qualification){
            $error .= "<div class='text-danger'>Qualification Not Found</div>";
        }
        else {

            $folder = EXTRA_IMAGE_CHECK_PATH . "$USER_ID";

            if (!is_dir($folder)) {

                mkdir(EXTRA_IMAGE_CHECK_PATH . "/$USER_ID");
            }

            $ROLL_NO = $grade = "";
            $IS_DECLARE = 'N';
            $PASSING_YEAR = $RESULT_DATE = $START_DATE = $END_DATE = '1900-01-01';
            $cgpa = $out_of = $DEGREE_ID = $DISCIPLINE_ID = $INSTITUTE_ID = $ORGANIZATION_ID = $TOTAL_MARKS = $OBTAINED_MARKS = 0;


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
                    // $error .= "<div class='text-danger'>Result Declare Date must be Filled</div>";
                }

            }


            if (isset($_POST['INSTITUTE_ID']) && isValidData($_POST['INSTITUTE_ID'])) {
                $INSTITUTE_ID = isValidData($_POST['INSTITUTE_ID']);
                if ($INSTITUTE_ID <= 0) {
                   // $error .= "<div class='text-danger'>Institute / Department / School / College must be select</div>";
                }
            } else {
                //$error .= "<div class='text-danger'>Institute / Department / School / College must be select</div>";
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
                if($TOTAL_MARKS<100){
                    $error .= "<div class='text-danger'>Total Marks must be Filled</div>";
                }
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
            if (isset($_POST['PASSING_YEAR']) && isValidData($_POST['PASSING_YEAR'])) {
                $PASSING_YEAR = isValidData($_POST['PASSING_YEAR']);
            } else {

                $error .= "<div class='text-danger'>Passing Year Must be Select</div>";
            }

            if (isset($_POST['START_DATE']) && isValidTimeDate($_POST['START_DATE'], 'd/m/Y')) {
                $START_DATE = getDateForDatabase($_POST['START_DATE']);
            } else {
                //$error .= "<div class='text-danger'>Start Date must be Filled</div>";
            }
            if (isset($_POST['END_DATE']) && isValidTimeDate($_POST['END_DATE'], 'd/m/Y')) {
                $END_DATE = getDateForDatabase($_POST['END_DATE']);
            } else {
                //  $error .= "<div class='text-danger'>End Date must be Filled</div>";
            }
            if ($END_DATE <= $START_DATE) {
                //$error .= "<div class='text-danger'>Invalid Date </div>";
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


            $degree_name = $this->Api_qualification_model->getDegreeProgramById($DEGREE_ID);
            $degree_name = str_replace(' ', '_', $degree_name['DEGREE_TITLE']);
            $degree_name = str_replace('/', '_', $degree_name);
            $config_a = array();
            $config_a['maintain_ratio'] = true;
            $config_a['width'] = 360;
            $config_a['height'] = 500;

            $config_a['resize'] = false;

            $marksheet_image = $qualification['MARKSHEET_IMAGE'];

            if ($marksheet_image)
                $marksheet_image = EXTRA_IMAGE_CHECK_PATH . $marksheet_image;
            $marks_check = true;
            $marksheet_image = $qualification['MARKSHEET_IMAGE'];
            if (!(!empty($marksheet_image) && file_exists($marksheet_image) && (filesize($marksheet_image) > 0))) {

             //   $marksheet_image = "";
               // $qualification['MARKSHEET_IMAGE'] = "";
              //  $marks_check = false;
            }else{
                $marksheet_image = $qualification['MARKSHEET_IMAGE'];
            }

            $passcertificate_image = $qualification['PASSCERTIFICATE_IMAGE'];

            if ($passcertificate_image)
                $passcertificate_image = EXTRA_IMAGE_CHECK_PATH . $passcertificate_image;

            $pass_check = true;
             $passcertificate_image = $qualification['PASSCERTIFICATE_IMAGE'];
            if (!(!empty($passcertificate_image) && file_exists($passcertificate_image) && (filesize($passcertificate_image) > 0))) {

               // $passcertificate_image = "";
               // $qualification['PASSCERTIFICATE_IMAGE'] = "";
              //  $pass_check = false;
            }else{
                $passcertificate_image = $qualification['PASSCERTIFICATE_IMAGE'];
            }

            if (isset($_FILES['marksheet_image'])) {
                if (isValidData($_FILES['marksheet_image']['name'])) {

                    $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                    $image_name = $degree_name . "_marksheet_image_$USER_ID"."_".$APPLICATION_ID;
                    $res = $this->upload_image('marksheet_image', $image_name, $this->file_size, $file_path, $config_a);
                    if ($res['STATUS'] === true) {
                        $marksheet_image = "$USER_ID/" . $res['IMAGE_NAME'];

                    } else {
                        $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                    }
                } else {
                    if ($DEGREE_ID != 8 && !$marks_check) {
                        $error .= "<div class='text-danger'>Must Upload Marksheet and image size must be less then 500kb </div>";
                    }

                }
            } else {
                if ($DEGREE_ID != 8 && !$marks_check)
                    $error .= "<div class='text-danger'>Must Upload Marksheet and image size must be less then 500kb Id Not found something went worng </div>";
            }

            if (isset($_FILES['passcertificate_image'])) {
                if (isValidData($_FILES['passcertificate_image']['name'])) {

                    $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                    $image_name = $degree_name . "_passcertificate_image_$USER_ID"."_".$APPLICATION_ID;
                    $res = $this->upload_image('passcertificate_image', $image_name, $this->file_size, $file_path, $config_a);
                    if ($res['STATUS'] === true) {
                        $passcertificate_image = "$USER_ID/" . $res['IMAGE_NAME'];
                    } else {
                        $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                    }
                } else {
                    if (!($DEGREE_ID==8 || $DEGREE_ID==10) && !$pass_check)
                        $error .= "<div class='text-danger'>Must Upload Pass Certificate and image size must be less then 500kb </div>";
                }

            } else {

                if (!($DEGREE_ID==8 || $DEGREE_ID==10) && !$pass_check)
                    $error .= "<div class='text-danger'>Must Upload Pass Certificate and image size must be less then 500kb Id Not found something went worng </div>";
            }

            // $if_exist = $this->Api_qualification_model->getQualificatinByUserIdAndDegreeId($USER_ID, $DEGREE_ID,$APPLICATION_ID);
            // if (!(count($if_exist) == 1 && $DEGREE_ID == $qualification['DEGREE_ID'])) {
            //     $error .= "<div class='text-danger'>Same Qualification is Already Exist OR You Can't change Degree at this stage. Please Update OR Delete...!</div>";
            // }
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
                "PASSING_YEAR"=>  $PASSING_YEAR
            );

          $PRE_RECORD = $this->User_model->getUserFullDetailWithChoiceById($USER_ID,$STUDENT_APPLICATION_ID,$SHIFT_ID=1);
           
            $PRE_RECORD = json_encode($PRE_RECORD);
            
            $res = $this->Api_qualification_model->updateQualification($QUAL_ID,$form_array,$APPLICATION_ID);


            if($res===1){
                $user_fulldata = $this->User_model->getUserFullDetailWithChoiceById($USER_ID,$STUDENT_APPLICATION_ID,$SHIFT_ID=1);

                $user_fulldata = json_encode($user_fulldata);

                $application_array = array("FORM_DATA"=>$user_fulldata,"USER_ID"=>$USER_ID);

                $res_app = $this->Application_model->updateApplicationById($STUDENT_APPLICATION_ID,$application_array);
                  $this->log_model->create_log($STUDENT_APPLICATION_ID,$STUDENT_APPLICATION_ID,$PRE_RECORD,$user_fulldata,"ADMIN_UPDATE_APPLICATION_QUALIFICATION",'applications',13,$admin_id);
                if($res_app){
                    $reponse['RESPONSE'] = "SUCCESS";
                    $reponse['MESSAGE'] = "<div class='text-success'>Successfully Save ..!<div>";
                }else{
                    $reponse['RESPONSE'] = "Error";
                    $reponse['MESSAGE'] = "<div class='text-success'>Qualification Update but Application Form Data not update!<div>";
                }

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

    function apiDeleteQualification($USER_ID){
        $APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
        $reponse = getcsrf($this);
        //$USER_ID = $this->user['USER_ID'];



        $qul_id = $this->input->post('qualification_id');

        if(is_numeric($qul_id)) {
            $res = $this->Api_qualification_model->deleteQualification($USER_ID,$qul_id,$APPLICATION_ID);
            if($res ===true){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>Successfully Deleted Qualification...!</div>";
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "<div class='text-success'>Somethis went wrong may be your not authorize to delete your qualification...!</div>";
            }
        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "<div class='text-success'>Invalid Qualification ID...!</div>";
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

    function apiGetInstituteByOrgId(){
        $user = $this->session->userdata($this->SessionName);


        $ORG_ID = $this->input->get('ORG_ID');


        if($ORG_ID){
            $institutes = $this->Api_qualification_model->getInstituteByOrgId($ORG_ID);
            echo "<option value='0'>--Choose--</option>";
            echo "<option value='-1'>--Add School / College--</option>";
            foreach ($institutes as $institute) {

                echo "<option value='{$institute['INSTITUTE_ID']}'  >{$institute['INSTITUTE_NAME']}</option>";
            }

        }else{
            echo "<option value='0'>--Choose--</option>";
        }


    }

    function apiGetOrganization(){
        $user = $this->session->userdata($this->SessionName);

        $organization = $this->Api_qualification_model->getAllOrganization();
        echo "<option value='0'>--Choose--</option>";
        foreach ($organization as $institute) {

            echo "<option value='{$institute['INSTITUTE_ID']}'  >{$institute['INSTITUTE_NAME']}</option>";
        }
        echo "<option value='-1'>--Other--</option>";


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

    function addOrganizationForQualification(){

        $reponse = getcsrf($this);
        $USER_ID = $this->user['USER_ID'];
        $org_name = strtoupper(isValidData($this->input->post('org_name')));
        if(!empty($org_name)){
            $DATE =date('Y-m-d H:i:s');
            $type_id = 1;
            $PARENT_ID = 0;
            $active = 0;
            $IS_INST = 'Y';
            $remarks = 'ADDED_BY_NORMAL_USER';
            $form_array = array('INSTITUTE_NAME'=>$org_name ,
                'INSTITUTE_TYPE_ID'=>$type_id,
                'PARENT_ID'=>$PARENT_ID,
                'REMARKS'=>$remarks ,
                'USER_ID'=> $USER_ID,
                'DATE_TIME'=> $DATE,
                'ACTIVE'=> $active,
                'IS_INST'=> $IS_INST);
            $res = $this->Api_qualification_model->addInstitute($form_array);
            if($res===true){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>Organization Add Success Fully...!</div>";
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "<div class='text-success'>Something Went Worng...!</div>";
            }

        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "<div class='text-danger'>Organization Name Must be Provide...!</div>";
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

    function addInstituteForQualification(){
        $reponse = getcsrf($this);
        $USER_ID = $this->user['USER_ID'];

        $institute = strtoupper(isValidData($this->input->post('institute')));
        $PARENT_ID = isValidData($this->input->post('org_id'));


        if($institute&&$PARENT_ID){

            $DATE =date('Y-m-d H:i:s');
            $type_id = 2;
            $active = 0;
            $IS_INST = 'N';
            $remarks = 'ADDED_BY_NORMAL_USER';

            $form_array = array('INSTITUTE_NAME'=>$institute ,
                'INSTITUTE_TYPE_ID'=>$type_id,
                'PARENT_ID'=>$PARENT_ID,
                'REMARKS'=>$remarks ,
                'USER_ID'=> $USER_ID,
                'DATE_TIME'=> $DATE,
                'ACTIVE'=> $active,
                'IS_INST'=> $IS_INST);

            $res = $this->Api_qualification_model->addInstitute($form_array);

            if($res===true){
                $reponse['RESPONSE'] = "SUCCESS";
                $reponse['MESSAGE'] = "<div class='text-success'>Institute / Department / School / College ...!</div>";
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = "<div class='text-success'>Something Went Worng...!</div>";
            }

        }else{
            $reponse['RESPONSE'] = "ERROR";
            $reponse['MESSAGE'] = "<div class='text-danger'>Organization must be select and Institute Must be Provided...!</div>";
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
    function uploadDocuments($USER_ID){
        $APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
        if ($this->input->server('REQUEST_METHOD') == 'POST'){
            $reponse = getcsrf($this);
            //$USER_ID = $this->user['USER_ID'];
            $user = $this->User_model->getUserById($USER_ID);
            $this->user =  $user ;

            $folder = EXTRA_IMAGE_CHECK_PATH."$USER_ID";
            if(!is_dir($folder)){
                mkdir(EXTRA_IMAGE_CHECK_PATH."/$USER_ID");
            }

            $CNIC_FRONT_IMAGE = "";

            if($user['CNIC_FRONT_IMAGE']){
                $CNIC_FRONT_IMAGE = EXTRA_IMAGE_CHECK_PATH.$user['CNIC_FRONT_IMAGE'] ;
            }

            if(!(!empty($CNIC_FRONT_IMAGE)&&file_exists($CNIC_FRONT_IMAGE)&&(filesize($CNIC_FRONT_IMAGE)>0))){

                $CNIC_FRONT_IMAGE="";
                $user['CNIC_FRONT_IMAGE']="";
            }else{
                $CNIC_FRONT_IMAGE = $user['CNIC_FRONT_IMAGE'] ;
            }

            $CNIC_BACK_IMAGE = "";

            if($user['CNIC_BACK_IMAGE']){
                $CNIC_BACK_IMAGE = EXTRA_IMAGE_CHECK_PATH.$user['CNIC_BACK_IMAGE'] ;
            }

            if(!(!empty($CNIC_BACK_IMAGE)&&file_exists($CNIC_BACK_IMAGE)&&(filesize($CNIC_BACK_IMAGE)>0))){

                $CNIC_BACK_IMAGE="";
                $user['CNIC_BACK_IMAGE']="";
            }else{
                $CNIC_BACK_IMAGE = $user['CNIC_BACK_IMAGE'] ;
            }

            $PASSPORT_FRONT_IMAGE = "";

            if($user['PASSPORT_FRONT_IMAGE']){
                $PASSPORT_FRONT_IMAGE = EXTRA_IMAGE_CHECK_PATH.$user['PASSPORT_FRONT_IMAGE'] ;
            }

            if(!(!empty($PASSPORT_FRONT_IMAGE)&&file_exists($PASSPORT_FRONT_IMAGE)&&(filesize($PASSPORT_FRONT_IMAGE)>0))){

                $PASSPORT_FRONT_IMAGE="";
                $user['PASSPORT_FRONT_IMAGE']="";
            }else{
                $PASSPORT_FRONT_IMAGE = $user['PASSPORT_FRONT_IMAGE'] ;
            }

            $PASSPORT_BACK_IMAGE = "";

            if($user['PASSPORT_BACK_IMAGE']){
                $PASSPORT_BACK_IMAGE = EXTRA_IMAGE_CHECK_PATH.$user['PASSPORT_BACK_IMAGE'] ;
            }

            if(!(!empty($PASSPORT_BACK_IMAGE)&&file_exists($PASSPORT_BACK_IMAGE)&&(filesize($PASSPORT_BACK_IMAGE)>0))){

                $PASSPORT_BACK_IMAGE="";
                $user['PASSPORT_BACK_IMAGE']="";
            }else{
                $PASSPORT_BACK_IMAGE = $user['PASSPORT_BACK_IMAGE'] ;
            }

            $DOMICILE_IMAGE = "";

            if($user['DOMICILE_IMAGE']){
                $DOMICILE_IMAGE = EXTRA_IMAGE_CHECK_PATH.$user['DOMICILE_IMAGE'] ;
            }

            if(!(!empty($DOMICILE_IMAGE)&&file_exists($DOMICILE_IMAGE)&&(filesize($DOMICILE_IMAGE)>0))){

                $DOMICILE_IMAGE="";
                $user['DOMICILE_IMAGE']="";
            }else{
                $DOMICILE_IMAGE = $user['DOMICILE_IMAGE'] ;
            }

            $DOMICILE_FORM_C_IMAGE = "";

            if($user['DOMICILE_FORM_C_IMAGE']){
                $DOMICILE_FORM_C_IMAGE = EXTRA_IMAGE_CHECK_PATH.$user['DOMICILE_FORM_C_IMAGE'] ;
            }

            if(!(!empty($DOMICILE_FORM_C_IMAGE)&&file_exists($DOMICILE_FORM_C_IMAGE)&&(filesize($DOMICILE_FORM_C_IMAGE)>0))){

                $DOMICILE_FORM_C_IMAGE="";
                $user['DOMICILE_FORM_C_IMAGE']="";
            }else{
                $DOMICILE_FORM_C_IMAGE =$user['DOMICILE_FORM_C_IMAGE'] ;
            }

            //start method work
            $is_upload_any_doc = false;
            $config_a = array();
            $config_a['maintain_ratio'] = true;
            $config_a['width']         = 360;
            $config_a['height']       = 500;
            $config_a['resize']       = false;

            $error="";
            
                if ($user['IS_CNIC_PASS'] === 'P') {

                    //checking passport validation
                    if (isset($_FILES['passport_front_image'])) {
                        if (isValidData($_FILES['passport_front_image']['name'])) {

                            $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                            $image_name = "passport_front_image_$USER_ID"."_".$APPLICATION_ID;
                            $res = $this->upload_image('passport_front_image', $image_name, $this->file_size, $file_path, $config_a);
                            if ($res['STATUS'] === true) {
                                $PASSPORT_FRONT_IMAGE = "$USER_ID/" . $res['IMAGE_NAME'];
                                $is_upload_any_doc = true;
                            } else {
                                $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                            }
                        } else {
                            if ($PASSPORT_FRONT_IMAGE == "")
                                $error .= "<div class='text-danger'>Must Passport Front Image and image size must be less then 500kb </div>";
                        }
                    } else {
                        if ($PASSPORT_FRONT_IMAGE == "")
                            $error .= "<div class='text-danger'>Must Passport Front Image and image size must be less then 500kb </div>";
                    }

                    if (isset($_FILES['passport_back_image'])) {
                        if (isValidData($_FILES['passport_back_image']['name'])) {

                            $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                            $image_name = "passport_back_image_$USER_ID"."_".$APPLICATION_ID;
                            $res = $this->upload_image('passport_back_image', $image_name, $this->file_size, $file_path, $config_a);
                            if ($res['STATUS'] === true) {
                                $PASSPORT_BACK_IMAGE = "$USER_ID/" . $res['IMAGE_NAME'];
                                $is_upload_any_doc = true;
                            } else {
                                $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                            }
                        } else {
                            if ($PASSPORT_BACK_IMAGE == "")
                                $error .= "<div class='text-danger'>Must Passport Back Image and image size must be less then 500kb </div>";
                        }

                    } else {
                        if ($PASSPORT_BACK_IMAGE == "")
                            $error .= "<div class='text-danger'>Must Passport Back Image and image size must be less then 500kb Id Not found something went worng </div>";
                    }


                } else {

                    //checking cnic validation
                    if (isset($_FILES['cnic_front_image'])) {
                        if (isValidData($_FILES['cnic_front_image']['name'])) {

                            $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                            $image_name = "cnic_front_image_$USER_ID"."_".$APPLICATION_ID;
                            $res = $this->upload_image('cnic_front_image', $image_name, $this->file_size, $file_path, $config_a);
                            if ($res['STATUS'] === true) {
                                $CNIC_FRONT_IMAGE = "$USER_ID/" . $res['IMAGE_NAME'];
                                $is_upload_any_doc = true;

                            } else {
                                $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                            }
                        } else {
                            if ($CNIC_FRONT_IMAGE == "")
                                $error .= "<div class='text-danger'>Must Cnic Front Image and image size must be less then 500kb </div>";
                        }
                    } else {

                        if ($CNIC_FRONT_IMAGE == "")
                            $error .= "<div class='text-danger'>Must Cnic Front Image and image size must be less then 500kb </div>";
                    }

                    if (isset($_FILES['cnic_back_image'])) {
                        if (isValidData($_FILES['cnic_back_image']['name'])) {

                            $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                            $image_name = "cnic_back_image_$USER_ID"."_".$APPLICATION_ID;
                            $res = $this->upload_image('cnic_back_image', $image_name, $this->file_size, $file_path, $config_a);
                            if ($res['STATUS'] === true) {
                                $CNIC_BACK_IMAGE = "$USER_ID/" . $res['IMAGE_NAME'];
                                $is_upload_any_doc = true;
                            } else {
                                $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                            }
                        } else {
                            if ($CNIC_BACK_IMAGE == "")
                                $error .= "<div class='text-danger'>Must Cnic Back Image and image size must be less then 500kb </div>";
                        }

                    } else {
                        if ($CNIC_BACK_IMAGE == "")
                            $error .= "<div class='text-danger'>Must Cnic Back Image and image size must be less then 500kb Id Not found something went worng </div>";
                    }

                }


                //checking domicile validation
                if (isset($_FILES['domicile_image'])) {
                    if (isValidData($_FILES['domicile_image']['name'])) {

                        $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                        $image_name = "domicile_image_$USER_ID"."_".$APPLICATION_ID;
                        $res = $this->upload_image('domicile_image', $image_name, $this->file_size, $file_path, $config_a);
                        if ($res['STATUS'] === true) {
                            $DOMICILE_IMAGE = "$USER_ID/" . $res['IMAGE_NAME'];
                            $is_upload_any_doc = true;

                        } else {
                            $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                        }
                    } else {
                        if ($DOMICILE_IMAGE == "")
                            $error .= "<div class='text-danger'>Must Upload Domicile Image and image size must be less then 500kb </div>";
                    }
                } else {

                    if ($DOMICILE_IMAGE == "")
                        $error .= "<div class='text-danger'>Must Upload Domicile Image and image size must be less then 500kb </div>";
                }

                if (isset($_FILES['domicile_formc_image'])) {
                    if (isValidData($_FILES['domicile_formc_image']['name'])) {

                        $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                        $image_name = "domicile_formc_image_$USER_ID"."_".$APPLICATION_ID;
                        $res = $this->upload_image('domicile_formc_image', $image_name, $this->file_size, $file_path, $config_a);
                        if ($res['STATUS'] === true) {
                            $DOMICILE_FORM_C_IMAGE = "$USER_ID/" . $res['IMAGE_NAME'];
                            $is_upload_any_doc = true;
                        } else {
                            $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                        }
                    } else {
                        if ($DOMICILE_FORM_C_IMAGE == "")
                            $error .= "<div class='text-danger'>Must Upload Domicile  Form-C Image and image size must be less then 500kb </div>";
                    }

                } else {
                    if ($DOMICILE_FORM_C_IMAGE == "")
                        $error .= "<div class='text-danger'>Must Upload Domicile Form-C Image and image size must be less then 500kb Id Not found something went worng </div>";
                }
          
            

            if($error==""){
                $form_array = array(
                    "DOMICILE_FORM_C_IMAGE"=>$DOMICILE_FORM_C_IMAGE,
                    "DOMICILE_IMAGE"=>$DOMICILE_IMAGE,
                    "CNIC_FRONT_IMAGE"=>$CNIC_FRONT_IMAGE,
                    "CNIC_BACK_IMAGE"=>$CNIC_BACK_IMAGE,
                    "PASSPORT_FRONT_IMAGE"=>$PASSPORT_FRONT_IMAGE,
                    "PASSPORT_BACK_IMAGE"=>$PASSPORT_BACK_IMAGE,

                );
                $res = $this->User_model->updateUserById($USER_ID,$form_array);
               //$res = 1;
                if($res===1){
                    $reponse['RESPONSE'] = "SUCCESS";
                    $reponse['MESSAGE'] = "<div class='text-success'>Document Upload Successfully...!<div>";
                }else if($res===0){
                    $reponse['RESPONSE'] = "SUCCESS";
                    if($is_upload_any_doc == true){
                        $reponse['MESSAGE'] = "<div class='text-success'>Document Upload Successfully...!<div>";
                    }else{
                        $reponse['MESSAGE'] = "<div class='text-success'>No changes has been made..!<div>";
                    }
                }else{
                    $reponse['RESPONSE'] = "ERROR";
                    $reponse['MESSAGE'] = "<div class='text-danger'>Something went worng..!</div>";
                }
            }else{
                $reponse['RESPONSE'] = "ERROR";
                $reponse['MESSAGE'] = $error;
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
    
    function challan_upload_handler(){
        if($this->session->has_userdata('STUDENT_USER_ID')&&$this->session->has_userdata('STUDENT_APPLICATION_ID')){
            $USER_ID = $this->session->userdata('STUDENT_USER_ID');
            $APPLICATION_ID = $this->session->userdata('STUDENT_APPLICATION_ID');
            $user = $this->User_model->getUserById($USER_ID);
            $this->user =  $user ;
        }
        $user = $this->user ;
        $USER_ID = $user['USER_ID'];
        $is_upload_any_doc = false;
        $config_a = array();
        $config_a['maintain_ratio'] = true;
        $config_a['width']         = 360;
        $config_a['height']       = 500;
        $config_a['resize']       = false;
        $error = "";
        $challan_image ="";
        $CHALLAN_AMOUNT =$BRANCH_ID = 0;
        $CHALLAN_PAID_DATE='0000-00-00';
        $APPLICATION_ID = 0;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if($this->session->has_userdata('STUDENT_APPLICATION_ID')) {
                $APPLICATION_ID_SESSION = $this->session->userdata('STUDENT_APPLICATION_ID');
            }else{
                $error.="<div class='text-danger'>Application Id not found in Session</div>";
            }
            if(isset($_POST['APPLICATION_ID'])&&isValidData($_POST['APPLICATION_ID'])){
                $APPLICATION_ID =isValidData($_POST['APPLICATION_ID']);
                if($APPLICATION_ID_SESSION!=$APPLICATION_ID){
                    $error.="<div class='text-danger'>Application Id Missmatch</div>";
                }
            }
            else{
                $error.="<div class='text-danger'>Application Id not found</div>";
            }
            $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'],$APPLICATION_ID);
            if($application) {
                $FORM_CHALLAN_ID = $application['FORM_CHALLAN_ID'];
                $challan_image = $application['CHALLAN_IMAGE'];
                $CHALLAN_PAID_DATE = $application['CHALLAN_DATE'];
                $CHALLAN_AMOUNT = $application['PAID_AMOUNT'];
                $BRANCH_ID = $application['BRANCH_ID'];
                // $valid_upto = getDateCustomeView($application['ADMISSION_END_DATE'], 'd-m-Y');



                    
                        $folder = EXTRA_IMAGE_CHECK_PATH . "$USER_ID";
                        if (!is_dir($folder)) {
                            mkdir(EXTRA_IMAGE_CHECK_PATH . "/$USER_ID");
                        }


                        if(isset($_POST['BRANCH_ID'])&&isValidData($_POST['BRANCH_ID'])){
                            $BRANCH_ID =isValidData($_POST['BRANCH_ID']);
                        }else{
                            $error.="<div class='text-danger'>Bank Branch must be select</div>";
                        }
                        if(isset($_POST['CHALLAN_AMOUNT'])&&isValidData($_POST['CHALLAN_AMOUNT'])){
                            $CHALLAN_AMOUNT =isValidData($_POST['CHALLAN_AMOUNT']);
                            if($CHALLAN_AMOUNT != $application['CHALLAN_AMOUNT']){
                                $error.="<div class='text-danger'>Your entered amount does not match actual challan amount </div>";
                            }

                        }else{
                            $error.="<div class='text-danger'>Challan Amount Must be Enter</div>";
                        }
                        if(isset($_POST['CHALLAN_NO'])&&isValidData($_POST['CHALLAN_NO'])){
                            $CHALLAN_NO =isValidData($_POST['CHALLAN_NO']);
                            if($CHALLAN_NO!=$FORM_CHALLAN_ID){
                               // $error.="<div class='text-danger'>Invalid Challan No..!</div>";
                            }
                        }else{
                            $error.="<div class='text-danger'>Challan Number Must be Enter</div>";
                        }

                        if(isset($_POST['CHALLAN_PAID_DATE'])&&isValidTimeDate($_POST['CHALLAN_PAID_DATE'],'d/m/Y')){
                            $CHALLAN_PAID_DATE = getDateForDatabase($_POST['CHALLAN_PAID_DATE']);
                            if($CHALLAN_PAID_DATE>date('Y-m-d')){
                                $error.="<div class='text-danger'>Choose Valid Challan Paid Date</div>";
                            }
                        }else{
                            $error.="<div class='text-danger'>Challan Paid Date Must be Choose</div>";
                        }


                        if (isset($_FILES['challan_image'])) {
                            if (isValidData($_FILES['challan_image']['name'])) {

                                $file_path = EXTRA_IMAGE_CHECK_PATH . "$USER_ID/";
                                $image_name = "challan_image_$FORM_CHALLAN_ID"."_"."$USER_ID";
                                $res = $this->upload_image('challan_image', $image_name, $this->file_size, $file_path, $config_a);
                                if ($res['STATUS'] === true) {
                                    $challan_image = "$USER_ID/" . $res['IMAGE_NAME'];
                                    $is_upload_any_doc = true;

                                } else {
                                    $error .= "<div class='text-danger'>Error {$res['MESSAGE']}</div>";
                                }
                            } else {
                                if ($challan_image == "")
                                    $error .= "<div class='text-danger'>Must Upload Challan Image and image size must be less then {$this->file_size}kb </div>";
                            }
                        }
                        else {

                            if ($challan_image == "")
                                $error .= "<div class='text-danger'>Must Upload Challan Image and image size must be less then {$this->file_size}kb </div>";
                        }

                        if($error==""){
                            $form_data=array("BRANCH_ID"=>$BRANCH_ID,
                                "CHALLAN_DATE"=>$CHALLAN_PAID_DATE,
                                "PAID_AMOUNT"=>$CHALLAN_AMOUNT,
                                "CHALLAN_IMAGE"=>$challan_image,
                                "PAID"=>"N",
                                "USER_ID"=>$USER_ID);
                            $res = $this->Application_model->updateChallanById($FORM_CHALLAN_ID,$form_data);
                            if($res==1){

                                $APPLICATION_ID = base64_encode($APPLICATION_ID);
                                $APPLICATION_ID = urlencode($APPLICATION_ID);
                                $success= "<div class='text-success'>Challan Information Update Successfully</div>";
                                $alert = array('MSG'=>$success,'TYPE'=>'SUCCESS');
                                $this->session->set_flashdata('ALERT_MSG',$alert);
                                redirect(base_url()."AdminPanel/student_update");

                            }else if($res==0){

                                $APPLICATION_ID = base64_encode($APPLICATION_ID);
                                $APPLICATION_ID = urlencode($APPLICATION_ID);
                                if($is_upload_any_doc){
                                    $success= "<div class='text-success'>Challan Information Update Successfully</div>";
                                }else{
                                    $success= "<div class='text-success'>No data has been changed...! </div>";
                                    $success= "<div class='text-success'>Challan Information Update Successfully...!</div>";
                                }

                                $alert = array('MSG'=>$success,'TYPE'=>'SUCCESS');
                                $this->session->set_flashdata('ALERT_MSG',$alert);
                                redirect(base_url()."AdminPanel/student_update");

                            }else{

                                $APPLICATION_ID = base64_encode($APPLICATION_ID);
                                $APPLICATION_ID = urlencode($APPLICATION_ID);
                                $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
                                $this->session->set_flashdata('ALERT_MSG',$alert);
                                redirect(base_url()."AdminPanel/student_update");

                            }
                        }
                    


            }else{
                    $error.="<div class='text-danger'>This Application is not associate with you</div>";
            }

        }
        else{
            $error.="<div class='text-danger'>Invalid Request</div>";
        }
        if($error!=""){
            $APPLICATION_ID = base64_encode($APPLICATION_ID);
            $APPLICATION_ID = urlencode($APPLICATION_ID);
            $alert = array('MSG'=>$error,'TYPE'=>'ERROR');
            $this->session->set_flashdata('ALERT_MSG',$alert);
            redirect(base_url()."AdminPanel/student_update");
        }


    }

    private function upload_image($index_name,$image_name,$max_size = 100,$path = '../eportal_resource/images/applicants_profile_image/',$con_array=array())
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
            return array("STATUS"=>false,"MESSAGE"=> $config['upload_path'].$this->upload->display_errors());
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
            }
            else{
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



            $this->load->library('ftp');



            $this->CI_ftp($path,$image_data['file_name']);

            // exit("YES");
            return array("STATUS"=>true,"IMAGE_NAME"=>$image_data['file_name']);

        }
    }

    private function CI_ftp($path,$name){
        $user = $this->user ;
        $date_time =date('Y F d l h:i A');
        $msg = array(
            "USER_ID"=>$user['USER_ID'],
            "FILE_NAME"=>$name,
            "DATE_TIME"=>$date_time,
            "MSG"=>""
        );

        $this->load->library('ftp');
        $config['hostname'] = FTP_URL;
        $config['username'] = FTP_USER;
        $config['password'] = FTP_PASSWORD;
        $config['debug']        = false;
        $connect = false;
        for($i=1;$i<=3;$i++){
            $connect = $this->ftp->connect($config);
            if($connect){
                break;
            }
        }
        if(!$connect){
            $msg['MSG'] = 'CONNECTION FAILED';
            $msg = json_encode($msg);
            writeQuery($msg);
            $this->ftp->close();
            return false;
        }

        $ftp_path = str_replace("..","/public_html",$path);
        $ftp_dir_path = rtrim($ftp_path,"/");

        // $ftp_path = '/public_html/eportal_resource/foo/';
        // $ftp_dir_path = '/public_html/eportal_resource/foo';



        $already_exist = $this->ftp->list_files($ftp_path);

        if($already_exist){

        }else{
            $dir  = $this->ftp->mkdir($ftp_dir_path, 0755);
        }

        $up = $this->ftp->upload($path.$name,$ftp_path.$name, 'binary', 0775);
        if(!$up){
            $msg['MSG'] = 'UPLOADING FAILED';
            $msg = json_encode($msg);
            writeQuery($msg);
            $this->ftp->close();
            return false;
        }

        $this->ftp->close();
        return true;

    }
    
    /*
     * Yasir Created following methods on 20-02-2021
     * */

	public function getProgramTypes(){

    	$program_types = $this->Administration->programTypes ();

		if (empty($program_types)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed"));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($program_types));
		}
	}//method

	public function getSessions(){
		$sessions = $this->Admission_session_model->getSessionData();
		if (empty($sessions)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed"));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($sessions));
		}
	}//method
    
	public function getShifts(){
		$shifts = $this->Administration->shifts();
		if (empty($shifts)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed"));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($shifts));
		}
	}//method
	public function getCampuses(){
		$campuses = $this->Administration->getCampus();
		if (empty($campuses)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed"));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($campuses));
		}
	}//method

	public function getCampusPrograms(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$program_type_id 	= isValidData($request->program_type_id);
		$shift_id 			= isValidData($request->shift_id);
		$campus_id 			= isValidData($request->campus_id);

		$error = "";
		if (empty($program_type_id))
			$error.="Program Type is Required";
		elseif (empty($shift_id))
			$error.="Shift is Required";
		elseif (empty($campus_id))
			$error.="Campus is Required";

		if (empty($error)){
			$program = $this->Administration->getMappedPrograms ($shift_id,$program_type_id,$campus_id);
		}else{
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		if (empty($program)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed..."));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($program));
		}
//		}
	}
	
	public function getParts(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$program_type_id 	= isValidData($request->program_type_id);
		$error = "";
		if ($program_type_id == null)
			$error.="Program Type is Required";
		if (empty($error)){
			$part = $this->Administration->getPartByTypeID($program_type_id);
		}else{
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		if (empty($part)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed..."));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($part));
		}
	}
	
    public function getSemesters(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$fee_demerit_id = isValidData($request->fee_demerit_id);
		$error = "";
		if (empty($fee_demerit_id))
			$error.="Fee Type is Required";
		if (empty($error)){
			$semester = $this->Administration->getSemesterByDemeritID($fee_demerit_id);
		}else{
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		if (empty($fee_demerit_id)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed..."));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($semester));
		}
	}
    /*
	 * YASIR CREATED FOLLOWING METHODS ON 22-02-2021
	 * */
	public function AdmissionListNo(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$program_type_id 	= isValidData($request->program_type_id);
		$shift_id 			= isValidData($request->shift_id);
		$campus_id 			= isValidData($request->campus_id);
		$session_id			= isValidData($request->session_id);

		$error = "";
		if (empty($program_type_id))
			$error.="Program Type is Required";
		elseif (empty($shift_id))
			$error.="Shift is Required";
		elseif (empty($campus_id))
			$error.="Campus is Required";
		elseif (empty($session_id))
			$error.="Session ID is Required";

		if (empty($error)){
			$admission_session_data = $this->Admission_session_model->getAdmissionSessionID($session_id,$campus_id,$program_type_id);
			$admission_session_id=$admission_session_data['ADMISSION_SESSION_ID'];
			$record =$this->Selection_list_report_model->get_admission_list_no($admission_session_id,$shift_id);
		}else{
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		if (empty($record)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed..."));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($record));
		}
//		}
	}

	public function getCategory(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$error = "";

		if (empty($error)){
			$category_type_id=0;
			$record =$this->Selection_list_report_model->getCategory($category_type_id);
		}else{
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode($error));
		}
		if (empty($record)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed..."));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($record));
		}
//		}
	}
	public function getTestType(){

		$postdata = file_get_contents("php://input");
		$request= json_decode($postdata);

		$year = 0;
		if(isset($request->SESSION_ID)){
			$session_id 	= isValidData($request->SESSION_ID);
			$session = $this->Admission_session_model->getSessionByID($session_id);
			$year = $session['YEAR'];
		}elseif (isset($request->YEAR)){
			$year 	= isValidData($request->YEAR);
		}
		$test_types =$this->TestResult_model->getTestTypeByYear($year);

		$error = "";

		if (empty($test_types)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode('Record not found...'));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($test_types));
		}
	}//method
	
	public function getPart(){
		$postdata = file_get_contents("php://input");
		$request= json_decode($postdata);
		$part = null;
		if(isset($request->flag)){
			$program_type_id 	= isValidData($request->PROGRAM_TYPE_ID);
			$part = $this->Administration->getPart($program_type_id);

		}
		if (empty($part)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode('Record not found...'));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($part));
		}
	}//method
	public function getSemester(){
		$postdata = file_get_contents("php://input");
		$request= json_decode($postdata);
		$semester = null;
		if(isset($request->flag)){
            $fee_demerit_id 	= isValidData($request->FEE_DEMERIT_ID);
			$semester = $this->Administration->getSemester($fee_demerit_id);

		}
		if (empty($semester)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode('Record not found...'));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($semester));
		}
	}//method
	
	public function getDemerit(){
		$demerit = $this->FeeChallan_model->get_demerit();
		if (empty($demerit)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed"));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($demerit));
		}
	}//method
	
	public function getChallanType(){
		$challan_types = $this->FeeChallan_model->get_challan_type();
		if (empty($challan_types)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode("Failed"));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($challan_types));
		}
	}//method
}