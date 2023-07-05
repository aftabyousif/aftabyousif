<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 12/15/2020
 * Time: 1:57 PM
 */


defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';
class PromoteList extends AdminLogin
{
    private $script_name = "";
    private $pre_requiste_list = null;
    private $pre_req_log = null;
    private $minor_maping_list = null;


    public function __construct()
    {
        parent::__construct();

        set_time_limit(1500);
        ini_set('memory_limit', '-1');

        $this->load->model('Administration');
        $this->load->model('log_model');
        $this->load->model('Api_qualification_model');
        $this->load->model('Api_location_model');
        $this->load->model('User_model');
        $this->load->model('Application_model');
        $this->load->model('Admission_session_model');
        $this->load->model('TestResult_model');
        $this->load->model('MeritList_model');
        $this->load->model('Prerequisite_model');
        $this->load->model('Promotelist_model');
        $this->load->model('FeeChallan_model');
        $this->load->model('Selection_list_report_model');
//		$this->load->library('javascript');
        $self = $_SERVER['PHP_SELF'];
        $self = explode('index.php/', $self);
        $this->script_name = $self[1];
        $this->verify_login();
        $this->date = date("d_m_y_h_i_s_A");

        //echo "yes";
    }

    public function view(){
        $user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $user_id = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];

        $side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
        $this->verify_path($this->script_name,$side_bar_data);


        $data['test_year'] =$this->TestResult_model->getTestTypeYear();
        $data['campus'] =$this->Administration->getCampus();
        $data['side_bar_values'] = $side_bar_data;


        $data['user'] = $user;
        $data['profile_url'] = $user['PROFILE_IMAGE'];
        $this->load->view('include/header',$data);
        $this->load->view('include/preloder');
        $this->load->view('include/side_bar',$data);
        $this->load->view('include/nav',$data);
        $this->load->view('admin/promote_list');
        $this->load->view('include/footer_area',$data);
        $this->load->view('include/footer',$data);
    }
    public function promote_list(){

        if(isset($_POST['PROG_TYPE_ID'])&&isset($_POST['SHIFT_ID'])&&isset($_POST['YEAR'])&&isset($_POST['TEST_ID'])&&isset($_POST['CAMPUS_ID'])) {



            $session = $this->Admission_session_model->getSessionByYearData($_POST['YEAR']);

            $IS_SPECAIL_SELF = '';
            if(isset($_POST['IS_SPECIAL_SELF'])&&$_POST['IS_SPECIAL_SELF']=='N'){
                $IS_SPECAIL_SELF = 'N';
            }else if(isset($_POST['IS_SPECIAL_SELF'])&&$_POST['IS_SPECIAL_SELF']=='Y') {
                $IS_SPECAIL_SELF = 'Y';
            }else{
                exit("<h1>IS_SPECAIL_SELF Must Select</h1>");
            }
            $ADMISSION_LIST_ID = $_POST['ADMISSION_LIST_ID'];
            $TEST_ID = $_POST['TEST_ID'];
            $campus_id = $_POST['CAMPUS_ID'];
            $shift_id = $_POST['SHIFT_ID'];
            $session_id = $session['SESSION_ID'];
            $prog_type_id = $_POST['PROG_TYPE_ID'];

            $admission_list =  $this->Selection_list_report_model->get_admission_list_no_by_id($ADMISSION_LIST_ID);
            $cur_list_no = $admission_list['LIST_NO'];

            $admission_session_obj = $this->Admission_session_model->getAdmissionSessionID($session_id, $campus_id, $prog_type_id);
            $admission_session_id = $admission_session_obj['ADMISSION_SESSION_ID'];


            $user_id = 0;

            $list_no = $cur_list_no - 1;


            $previous_selection_list = $this->Promotelist_model->get_previous_list($admission_session_id, $shift_id, $list_no, $TEST_ID,$IS_SPECAIL_SELF);
            $current_selection_list = $this->Promotelist_model->get_current_list($admission_session_id, $shift_id, $cur_list_no, $TEST_ID,$IS_SPECAIL_SELF);

            $final_update_list = array();
            $selection_list = array();
            $promoted_candidate = fopen("merit_list/promoted_candiadte".$this->date.".csv", "w") or die("Unable to open file!");
            $not_promoted_candidate = fopen("merit_list/not_promoted_candiadte".$this->date.".csv", "w") or die("Unable to open file!");
            $csv = array("APPLICATION_ID",'FIRST_NAME','CNIC_NO','PREV_SELECTION_LIST_ID','PREV_CHOICE_NO','PREV_PROGRAM_TITLE','PREV_CATEGORY_NAME','CUR_SELECTION_LIST_ID','PREV_CHOICE_NO','PREV_PROGRAM_TITLE','PREV_CATEGORY_NAME',"REASON");
            fputcsv($not_promoted_candidate,$csv);
            $csv = array("APPLICATION_ID",'FIRST_NAME','CNIC_NO','PREV_SELECTION_LIST_ID','PREV_CHOICE_NO','PREV_PROGRAM_TITLE','PREV_CATEGORY_NAME','CUR_SELECTION_LIST_ID','PREV_CHOICE_NO','PREV_PROGRAM_TITLE','PREV_CATEGORY_NAME');
            fputcsv($promoted_candidate,$csv);

            foreach ($current_selection_list as $application_id=> $current_selection) {

                $both = false;
                $merit_selction = null;
                $self_selction = null;
                $is_merit_paid = false;
                $is_self_paid = false;
               // prePrint($current_selection);
                if($current_selection['SELF']){
                    $self_selction =   $current_selection['SELF'];
                    $is_self_paid = $this->FeeChallan_model->getPaidProgramFeeCandidateBySelectionListId(0, 0, $self_selction['SELECTION_LIST_ID']);
                }
                if($current_selection['MERIT']){
                    $merit_selction =   $current_selection['MERIT'];
                    $is_merit_paid = $this->FeeChallan_model->getPaidProgramFeeCandidateBySelectionListId(0, 0, $merit_selction['SELECTION_LIST_ID']);
                }

                if (isset($previous_selection_list[$application_id])) {

                    if($is_merit_paid == false && $self_selction && $previous_selection_list[$application_id]['SELF']){

                        $current_selection = $self_selction;
                        $value = array("FEE_PROG_LIST_ID" => $current_selection['FEE_PROG_LIST_ID'], 'SELECTION_LIST_ID' => $current_selection['SELECTION_LIST_ID'], 'ACCOUNT_ID' => $previous_selection_list[$application_id]['SELF']['ACCOUNT_ID']);
                        array_push($final_update_list, $value);

                        $pre_selection =  $previous_selection_list[$application_id]['SELF'];
                        $csv = array($pre_selection['APPLICATION_ID'],$pre_selection['FIRST_NAME'],$pre_selection['CNIC_NO'],$pre_selection['SELECTION_LIST_ID'],$pre_selection['CHOICE_NO'],$pre_selection['PROGRAM_TITLE'],$pre_selection['CATEGORY_NAME'],$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME']);
                        fputcsv($promoted_candidate,$csv);

                    }
                    else if($merit_selction && $previous_selection_list[$application_id]['SELF']){
                        $current_selection = $merit_selction;
                        if ($is_merit_paid!=false || $current_selection['CHOICE_NO'] <= $previous_selection_list[$application_id]['SELF']['CHOICE_NO']) {
                            // $current_selection = $merit_selction;
                            $value = array("FEE_PROG_LIST_ID" => $current_selection['FEE_PROG_LIST_ID'], 'SELECTION_LIST_ID' => $current_selection['SELECTION_LIST_ID'], 'ACCOUNT_ID' => $previous_selection_list[$application_id]['SELF']['ACCOUNT_ID'], 'IS_MERIT' => 'Y');
                            array_push($final_update_list, $value);
                            $selection = array("SELECTION_LIST_ID" => $previous_selection_list[$application_id]['SELF']['SELECTION_LIST_ID'], 'REMARKS' => "DISABLED BY PROMOTE LIST", 'ACTIVE' => 0);
                            array_push($selection_list, $selection);

                            $pre_selection =  $previous_selection_list[$application_id]['SELF'];
                            $csv = array($pre_selection['APPLICATION_ID'],$pre_selection['FIRST_NAME'],$pre_selection['CNIC_NO'],$pre_selection['SELECTION_LIST_ID'],$pre_selection['CHOICE_NO'],$pre_selection['PROGRAM_TITLE'],$pre_selection['CATEGORY_NAME'],$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME']);
                            fputcsv($promoted_candidate,$csv);

                        }
                        else{
                            $current_selection = $self_selction;
                            $pre_selection =  $previous_selection_list[$application_id]['SELF'];
                            $csv = array($pre_selection['APPLICATION_ID'],$pre_selection['FIRST_NAME'],$pre_selection['CNIC_NO'],$pre_selection['SELECTION_LIST_ID'],$pre_selection['CHOICE_NO'],$pre_selection['PROGRAM_TITLE'],$pre_selection['CATEGORY_NAME'],$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME'],"PAID SELF FEE AND CURRENT SELECTION IS MERIT AND NOT THE BEST OF SELF");
                            fputcsv($not_promoted_candidate,$csv);
                        }


                    }
                    else if($is_self_paid!=false && $self_selction && $previous_selection_list[$application_id]['MERIT']) {

                        $current_selection = $self_selction;
                            $value = array("FEE_PROG_LIST_ID" => $current_selection['FEE_PROG_LIST_ID'], 'SELECTION_LIST_ID' => $current_selection['SELECTION_LIST_ID'], 'ACCOUNT_ID' => $previous_selection_list[$application_id]['MERIT']['ACCOUNT_ID'], 'IS_MERIT' => 'N');
                            array_push($final_update_list, $value);

                            $pre_selection = $previous_selection_list[$application_id]['MERIT'];
                            $csv = array($pre_selection['APPLICATION_ID'], $pre_selection['FIRST_NAME'], $pre_selection['CNIC_NO'], $pre_selection['SELECTION_LIST_ID'], $pre_selection['CHOICE_NO'], $pre_selection['PROGRAM_TITLE'], $pre_selection['CATEGORY_NAME'], $current_selection['SELECTION_LIST_ID'], $current_selection['CHOICE_NO'], $current_selection['PROGRAM_TITLE'], $current_selection['CATEGORY_NAME']);
                            fputcsv($promoted_candidate, $csv);

                    }
                    else if($merit_selction && $previous_selection_list[$application_id]['MERIT']){
                        $current_selection = $merit_selction;
                        $value = array("FEE_PROG_LIST_ID" => $current_selection['FEE_PROG_LIST_ID'], 'SELECTION_LIST_ID' => $current_selection['SELECTION_LIST_ID'], 'ACCOUNT_ID' => $previous_selection_list[$application_id]['MERIT']['ACCOUNT_ID']);
                        array_push($final_update_list, $value);

                        $pre_selection =  $previous_selection_list[$application_id]['MERIT'];
                        $csv = array($pre_selection['APPLICATION_ID'],$pre_selection['FIRST_NAME'],$pre_selection['CNIC_NO'],$pre_selection['SELECTION_LIST_ID'],$pre_selection['CHOICE_NO'],$pre_selection['PROGRAM_TITLE'],$pre_selection['CATEGORY_NAME'],$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME']);
                        fputcsv($promoted_candidate,$csv);
                    }
                    else if($is_self_paid==false && $previous_selection_list[$application_id]['MERIT']){
                        //must check
                        $pre_disabled_self = $this->Promotelist_model->get_selection_list_by_application_id_and_remarks($admission_session_id, $shift_id, $list_no, $TEST_ID,$application_id,"DISABLED BY PROMOTE LIST");
                        if($pre_disabled_self){
                            $current_selection = $self_selction;
                            $value = array("FEE_PROG_LIST_ID" => $current_selection['FEE_PROG_LIST_ID'], 'SELECTION_LIST_ID' => $current_selection['SELECTION_LIST_ID'], 'ACCOUNT_ID' => $previous_selection_list[$application_id]['SELF']['ACCOUNT_ID'], 'IS_MERIT' => 'N');
                            array_push($final_update_list, $value);
                            $pre_selection =  $previous_selection_list[$application_id]['MERIT'];
                            $csv = array($pre_selection['APPLICATION_ID'],$pre_selection['FIRST_NAME'],$pre_selection['CNIC_NO'],$pre_selection['SELECTION_LIST_ID'],$pre_selection['CHOICE_NO'],$pre_selection['PROGRAM_TITLE'],$pre_selection['CATEGORY_NAME'],$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME']);
                            fputcsv($promoted_candidate,$csv);

                        }else{
                            $current_selection = $self_selction;
                            $pre_selection =  $previous_selection_list[$application_id]['MERIT'];
                            $csv = array($pre_selection['APPLICATION_ID'],$pre_selection['FIRST_NAME'],$pre_selection['CNIC_NO'],$pre_selection['SELECTION_LIST_ID'],$pre_selection['CHOICE_NO'],$pre_selection['PROGRAM_TITLE'],$pre_selection['CATEGORY_NAME'],$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME'],"NOT PROMOTED DUE UNPAID SELF FEES");
                            fputcsv($not_promoted_candidate,$csv);
                        }

                    }
                    else{

                        $pre_selection =  $previous_selection_list[$application_id]['SELF'];
                        $current_selection = $self_selction?$self_selction:$merit_selction;
                        $csv = array($pre_selection['APPLICATION_ID'],$pre_selection['FIRST_NAME'],$pre_selection['CNIC_NO'],$pre_selection['SELECTION_LIST_ID'],$pre_selection['CHOICE_NO'],$pre_selection['PROGRAM_TITLE'],$pre_selection['CATEGORY_NAME'],$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME'],"MISMATCH SINERIO KINDLY CHECK THIS ROW MANUALLY");
                        fputcsv($not_promoted_candidate,$csv);
                    }


                }
                else{
                    //$pre_selection =  $previous_selection_list[$application_id]['SELF'];
                    if($current_selection['SELF']){
                        $current_selection = $current_selection['SELF'];
                    }else {
                        $current_selection = $current_selection['MERIT'];
                    }
                    $csv = array($current_selection['APPLICATION_ID'],$current_selection['FIRST_NAME'],$current_selection['CNIC_NO'],"","","","",$current_selection['SELECTION_LIST_ID'],$current_selection['CHOICE_NO'],$current_selection['PROGRAM_TITLE'],$current_selection['CATEGORY_NAME'],"NEW SELECTION");
                    fputcsv($not_promoted_candidate,$csv);
                    //fputcsv($not_promoted_candidate,$current_selection);
                }
            }


            $result = $this->Promotelist_model->update_ledeger_list($final_update_list, $selection_list, $user_id,$IS_SPECAIL_SELF,$shift_id);
           //$result = true;
            if ($result) {
                echo "success";
            } else {
                echo "error";
            }
        }

    }

    public function check_fee_ledger(){
        $user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $user_id = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];

        $side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
        //  $this->verify_path($this->script_name,$side_bar_data);


        // $data['test_year'] =$this->TestResult_model->getTestTypeYear();
        // $data['campus'] =$this->Administration->getCampus();
        $data['side_bar_values'] = $side_bar_data;

        $data['user'] = $user;
        $data['profile_url'] = $user['PROFILE_IMAGE'];
        $this->load->view('include/header',$data);
        $this->load->view('include/preloder');
        $this->load->view('include/side_bar',$data);
        $this->load->view('include/nav',$data);
        $result = $this->FeeChallan_model->getFeeLedgerForCheckingDuplicate('N');
        $result1 = $this->FeeChallan_model->getFeeLedgerForCheckingDuplicate('Y');
        $data['result']  = $result;

        // prePrint($result);

        $this->load->view('admin/get_fee_ledger_duplicat_entry',$data);
        $this->load->view('include/footer_area',$data);
        $this->load->view('include/footer',$data);
    }
    public function check_duplicate_in_fee_ledger(){
        $user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $user_id = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];

        $side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
        //  $this->verify_path($this->script_name,$side_bar_data);


        // $data['test_year'] =$this->TestResult_model->getTestTypeYear();
        // $data['campus'] =$this->Administration->getCampus();
        $data['side_bar_values'] = $side_bar_data;

        $data['user'] = $user;
        $data['profile_url'] = $user['PROFILE_IMAGE'];
        $this->load->view('include/header',$data);
        $this->load->view('include/preloder');
        $this->load->view('include/side_bar',$data);
        $this->load->view('include/nav',$data);
        //$result = $this->FeeChallan_model->getFeeLedgerForCheckingDuplicate('N');
        $result = $this->FeeChallan_model->getFeeLedgerForCheckingDuplicate('Y');
        $data['result']  = $result;

        // prePrint($result);

        $this->load->view('admin/get_fee_ledger_duplicat_entry',$data);
        $this->load->view('include/footer_area',$data);
        $this->load->view('include/footer',$data);
    }
    public function check_duplicate_in_fee_ledger_all(){
        $user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $user_id = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];

        $side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
        //  $this->verify_path($this->script_name,$side_bar_data);


        // $data['test_year'] =$this->TestResult_model->getTestTypeYear();
        // $data['campus'] =$this->Administration->getCampus();
        $data['side_bar_values'] = $side_bar_data;

        $data['user'] = $user;
        $data['profile_url'] = $user['PROFILE_IMAGE'];
        $this->load->view('include/header',$data);
        $this->load->view('include/preloder');
        $this->load->view('include/side_bar',$data);
        $this->load->view('include/nav',$data);
        //$result = $this->FeeChallan_model->getFeeLedgerForCheckingDuplicate('N');
        $result = $this->FeeChallan_model->getFeeLedgerForCheckingDuplicate('Z');
        $data['result']  = $result;

        // prePrint($result);

        $this->load->view('admin/get_fee_ledger_duplicat_entry',$data);
        $this->load->view('include/footer_area',$data);
        $this->load->view('include/footer',$data);
    }
}

