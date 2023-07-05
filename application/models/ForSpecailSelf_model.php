<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 12/13/2020
 * Time: 3:44 PM
 */
class ForSpecailSelf_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
//		$CI =& get_instance();
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database('admission_db', true);
    }
    //add method after 28-feb-2020
//    function getTestResultAndCPNbyTestIdAndCampusId($test_id,$campus_id){
//        $this->legacy_db->select("app.ADMISSION_SESSION_ID,ass.PROGRAM_TYPE_ID,tr.CARD_ID,app.APPLICATION_ID,app.USER_ID,tr.DETAIL_CPN,`app`.`FORM_DATA`,TEST_SCORE,CPN,app.STATUS_ID");
//        $this->legacy_db->from('test_result tr');
//        $this->legacy_db->join('test_type AS td', 'td.TEST_ID = tr.TEST_ID');
//
//        $this->legacy_db->join('applications AS app', 'app.APPLICATION_ID = tr.APPLICATION_ID');
//        $this->legacy_db->join('admission_session AS ass', 'app.ADMISSION_SESSION_ID = ass.ADMISSION_SESSION_ID');
//
//        $this->legacy_db->join('application_category AS ac', 'ac.APPLICATION_ID = app.APPLICATION_ID');
//
//        $this->legacy_db->where('ac.FORM_CATEGORY_ID', SPECIAL_SELF_FINANCE);
//
//        $this->legacy_db->where('tr.TEST_ID', $test_id);
//        $this->legacy_db->where('ass.CAMPUS_ID', $campus_id);
//        $this->legacy_db->where_in('app.STATUS_ID', array(3,4,5));
//        //$this->legacy_db->limit(50);
//        $this->legacy_db->order_by("CPN", "desc");
//
//        $result = $this->legacy_db->get()->result_array();
////        prePrint($this->legacy_db->last_query());
////        exit();
//        return $result;
//    }

    function getTestResultAndCPNbyTestIdAndCampusId($test_id,$campus_id){
        $this->legacy_db->select("ur.*,dist.DISTRICT_NAME,qual.*,di.*,app.ADMISSION_SESSION_ID,ass.PROGRAM_TYPE_ID,tr.CARD_ID,app.APPLICATION_ID,app.USER_ID,tr.DETAIL_CPN,TEST_SCORE,CPN,app.STATUS_ID");
        $this->legacy_db->from('test_result tr');

        $this->legacy_db->join('test_type AS td', 'td.TEST_ID = tr.TEST_ID');
        $this->legacy_db->join('applications AS app', 'app.APPLICATION_ID = tr.APPLICATION_ID');

        $this->legacy_db->join('users_reg AS ur', 'app.USER_ID = ur.USER_ID');
           $this->legacy_db->join('districts AS dist', 'dist.DISTRICT_ID = ur.DISTRICT_ID');
        $this->legacy_db->join('qualifications AS qual', 'qual.USER_ID = app.USER_ID');
        $this->legacy_db->join('discipline AS di', 'di.DISCIPLINE_ID = qual.DISCIPLINE_ID');

        $this->legacy_db->join('admission_session AS ass', 'app.ADMISSION_SESSION_ID = ass.ADMISSION_SESSION_ID');

        $this->legacy_db->join('application_category AS ac', 'ac.APPLICATION_ID = app.APPLICATION_ID');

        $this->legacy_db->where('ac.FORM_CATEGORY_ID', SPECIAL_SELF_FINANCE);

        $this->legacy_db->where('tr.TEST_ID', $test_id);
        $this->legacy_db->where('ass.CAMPUS_ID', $campus_id);
        $this->legacy_db->where('qual.ACTIVE', 1);
         $this->legacy_db->where('tr.TEST_SCORE >= td.PASSING_SCORE');
        $this->legacy_db->where('di.DEGREE_ID <> 10');

        $this->legacy_db->where_in('app.STATUS_ID', array(4,5));
        //$this->legacy_db->limit(50);
        $this->legacy_db->order_by("`tr`.`CPN` DESC, `di`.`DEGREE_ID` DESC ");

        $result = $this->legacy_db->get()->result_array();

        $new_array = array();
        foreach($result as $obj){
            $application_id = $obj['APPLICATION_ID'];
            if(isset($new_array[$application_id])){
                array_push($new_array[$application_id]['qualifications'],$obj);
            }else{
                $new_array[$application_id]=$obj;
                $new_array[$application_id]['users_reg']=$obj;
                $new_array[$application_id]['qualifications'] = array();
                array_push($new_array[$application_id]['qualifications'],$obj);
            }
        }

//        prePrint($this->legacy_db->last_query());
//        exit();
        return $new_array;
    }


// KAshif CREATED FOLLOWING METHODS 28-0s-2020
    function ForGetListOfStudentByTestIdAndCampusIdAndShiftId($test_id,$campus_id,$shift_id){


        $result = $this->getTestResultAndCPNbyTestIdAndCampusId($test_id,$campus_id);

        $this->legacy_db->select("ac.*,pl.* " );
        $this->legacy_db->from('test_result tr');
        $this->legacy_db->join('applications AS app', 'app.APPLICATION_ID = tr.APPLICATION_ID');
        $this->legacy_db->join('application_choices AS ac', 'ac.APPLICATION_ID = tr.APPLICATION_ID');
        $this->legacy_db->join('program_list AS pl', 'pl.PROG_LIST_ID = ac.PROG_LIST_ID');

        $this->legacy_db->join('admission_session AS ass', 'app.ADMISSION_SESSION_ID = ass.ADMISSION_SESSION_ID');

        $this->legacy_db->where('tr.TEST_ID', $test_id);
        $this->legacy_db->where('ass.CAMPUS_ID', $campus_id);
        $this->legacy_db->where('ac.SHIFT_ID', $shift_id);

        $this->legacy_db->where("(`ac`.`IS_RECOMMENDED` IS NULL OR `ac`.`IS_RECOMMENDED` LIKE 'Y')");

        $this->legacy_db->where('ac.IS_SPECIAL_CHOICE', 'Y');
        $application_choices = $this->legacy_db->get()->result_array();

        $application_choices_array = array();
        foreach ($application_choices as $application_choice){
            $application_id = $application_choice['APPLICATION_ID'];

            if(!isset($application_choices_array[$application_id])){
                $application_choices_array[$application_id] = array();
            }
            array_push($application_choices_array[$application_id],$application_choice);
        }

        //prePrint($this->legacy_db->last_query());

        $this->legacy_db->select("app_cat.*, f_cat.*" );
        $this->legacy_db->from('test_result tr');
        $this->legacy_db->join('applications AS app', 'app.APPLICATION_ID = tr.APPLICATION_ID');
        $this->legacy_db->join('application_category AS app_cat', 'app_cat.APPLICATION_ID = app.APPLICATION_ID');
        $this->legacy_db->join('form_category AS f_cat', 'app_cat.FORM_CATEGORY_ID = f_cat.FORM_CATEGORY_ID');

        $this->legacy_db->join('admission_session AS ass', 'app.ADMISSION_SESSION_ID = ass.ADMISSION_SESSION_ID');

        $this->legacy_db->where('tr.TEST_ID', $test_id);

        $this->legacy_db->where('ass.CAMPUS_ID', $campus_id);
        $this->legacy_db->where('app_cat.IS_ENABLE','Y');

        $application_category = $this->legacy_db->get()->result_array();

        $application_category_array = array();
        foreach ($application_category as $application_cat){
            $application_id = $application_cat['APPLICATION_ID'];

            if(!isset($application_category_array[$application_id])){
                $application_category_array[$application_id] = array();
            }
            array_push($application_category_array[$application_id],$application_cat);
        }

        //prePrint($this->legacy_db->last_query());

//        exit();
        $this->legacy_db->select("app_min.*");
        $this->legacy_db->from('test_result tr');
        $this->legacy_db->join('applications AS app', 'app.APPLICATION_ID = tr.APPLICATION_ID');
        $this->legacy_db->join('applicants_minors AS app_min', 'app_min.APPLICATION_ID = app.APPLICATION_ID');

        $this->legacy_db->join('admission_session AS ass', 'app.ADMISSION_SESSION_ID = ass.ADMISSION_SESSION_ID');

        $this->legacy_db->where('tr.TEST_ID', $test_id);
        $this->legacy_db->where('ass.CAMPUS_ID', $campus_id);
        // $this->legacy_db->limit(100);
        // $this->legacy_db->order_by("CPN", "desc");

        $application_minors = $this->legacy_db->get()->result_array();

        $application_minor_array = array();
        foreach ($application_minors as $application_minor){
            $application_id = $application_minor['APPLICATION_ID'];

            if(!isset($application_minor_array[$application_id])){
                $application_minor_array[$application_id] = array();
            }
            array_push($application_minor_array[$application_id],$application_minor);
        }
        //prePrint($this->legacy_db->last_query());

        $all_applicants = array();

        foreach ($result as $candidate) {
            $application_id = $candidate['APPLICATION_ID'];


            $applicants_category = $applicants_choices = $applicants_minor =array();
            if(isset($application_category_array[$application_id])){
                $applicants_category=$application_category_array[$application_id];
            }
            if(isset($application_choices_array[$application_id])){
                $applicants_choices=$application_choices_array[$application_id];
            }
            if(isset($application_minor_array[$application_id])){
                $applicants_minor=$application_minor_array[$application_id];
            }

            $applicants_choices= quicksort($applicants_choices,'CHOICE_NO','ASC');
            $candidate['applicants_minors']=$applicants_minor;
            $candidate['application_choices']=$applicants_choices;
            $candidate['application_category']=$applicants_category;
            $all_applicants[$application_id]=$candidate;

        }

        prePrint("End Time geting student data".date("d-m-y h:i:s A"));
        //exit();


        return $all_applicants;

    }
    /*
   * KAshif CREATED FOLLOWING METHODS 28-0s-2020
   * */
    function ForSpecailSelfGetSelectedStudent($admission_session_id,$shift_id,$session_id,$prog_type_id,$test_id){

        $this->legacy_db->select("sl.*");
        $this->legacy_db->from('selection_list AS sl');
        $this->legacy_db->join('program_list AS pl', ' sl.`PROG_LIST_ID` = pl.`PROG_LIST_ID`');
        $this->legacy_db->join('category AS cat', 'sl.`CATEGORY_ID` = cat.`CATEGORY_ID`');
        $this->legacy_db->join('admission_session AS ass', 'sl.`ADMISSION_SESSION_ID` = ass.`ADMISSION_SESSION_ID`');
        $this->legacy_db->join('applications AS app', 'sl.`APPLICATION_ID` = app.`APPLICATION_ID`');
        $this->legacy_db->where('ass.ADMISSION_SESSION_ID', $admission_session_id);
        $this->legacy_db->where('sl.SHIFT_ID', $shift_id);
        $this->legacy_db->where('sl.SESSION_ID', $session_id);
        $this->legacy_db->where('pl.PROGRAM_TYPE_ID', $prog_type_id);
        $this->legacy_db->where('sl.TEST_ID', $test_id);
        $this->legacy_db->where('sl.ACTIVE > 0 ');
        $this->legacy_db->where("sl.IS_PROVISIONAL = 'N' ");
        $this->legacy_db->where("sl.CATEGORY_ID ",SPECIAL_SELF_FINANCE_CATEGORY_ID );

        $this->legacy_db->order_by("sl.LIST_NO","DESC");
        $result = $this->legacy_db->get()->result_array();
        echo $this->legacy_db->last_query();
        $key_array = array();
        foreach ($result as $row){

            if(!isset($key_array[$row['APPLICATION_ID']])){
                $key_array[$row['APPLICATION_ID']] = array("SELF"=>null,"MERIT"=>null);
            }

            if(($row['CATEGORY_ID']==SPECIAL_SELF_FINANCE_CATEGORY_ID &&$key_array[$row['APPLICATION_ID']]['SELF']==null)){
                $key_array[$row['APPLICATION_ID']]['SELF'] = $row;
            }
            // array_push($key_array[$row['APPLICATION_ID']],$row);
        }
        return $key_array;
    }

    function ForSpecailSelfGetFeeLedger($admission_session_id,$shift_id,$session_id,$prog_type_id,$test_id){


        $this->legacy_db->select("fl.CHALLAN_TYPE_ID,fl.BANK_ACCOUNT_ID,fl.CHALLAN_NO,fl.DETAILS,fl.CHALLAN_AMOUNT,fl.PAYABLE_AMOUNT,fl.PAID_AMOUNT,fl.DATE,fl.IS_MERIT");
        $this->legacy_db->select("sl.APPLICATION_ID,sl.USER_ID,sl.SELECTION_LIST_ID,sl.PROG_LIST_ID,sl.CHOICE_NO,sl.CATEGORY_ID");
        $this->legacy_db->from('candidate_account AS ca');
        $this->legacy_db->join('fee_ledger AS fl', 'fl.`ACCOUNT_ID` = ca.`ACCOUNT_ID`');
        $this->legacy_db->join('selection_list AS sl', 'sl.`SELECTION_LIST_ID` = fl.`SELECTION_LIST_ID`');
        $this->legacy_db->join('applications AS app', 'ca.`APPLICATION_ID` = app.`APPLICATION_ID`');
        $this->legacy_db->join('admission_session AS ass', 'app.`ADMISSION_SESSION_ID` = ass.`ADMISSION_SESSION_ID`');
        $this->legacy_db->where('ass.ADMISSION_SESSION_ID', $admission_session_id);
        $this->legacy_db->where('sl.SHIFT_ID', $shift_id);
        $this->legacy_db->where('ass.SESSION_ID', $session_id);
        $this->legacy_db->where('ass.PROGRAM_TYPE_ID', $prog_type_id);
        $this->legacy_db->where('sl.TEST_ID', $test_id);
        $this->legacy_db->where('sl.CATEGORY_ID ', SPECIAL_SELF_FINANCE_CATEGORY_ID);
        $this->legacy_db->where('fl.IS_YES', 'Y');
        $this->legacy_db->where('ca.ACTIVE', 1);

        $this->legacy_db->where('sl.ACTIVE > 0 ');
        $this->legacy_db->where("sl.IS_PROVISIONAL = 'N' ");

        $result = $this->legacy_db->get()->result_array();

        $key_array = array();
        foreach ($result as $row){

            if(!isset($key_array[$row['APPLICATION_ID']])){
                $key_array[$row['APPLICATION_ID']] = array("SELF_FEE"=>null,"MERIT_FEE"=>null,"RETAIN_FEE"=>null);
            }

            if($row['IS_MERIT']=='Y'&&$row['CHALLAN_TYPE_ID']==1){
                $key_array[$row['APPLICATION_ID']]['MERIT_FEE'] = $row;
            }else if($row['IS_MERIT']=='N'&&$row['CHALLAN_TYPE_ID']==1){
                $key_array[$row['APPLICATION_ID']]['SELF_FEE'] = $row;
            }else if($row['CHALLAN_TYPE_ID']==2){
                $key_array[$row['APPLICATION_ID']]['RETAIN_FEE'] = $row;
            }

        }
        echo $this->legacy_db->last_query();
        return $key_array;
    }



}