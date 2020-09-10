<?php


class Application_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        //		$CI =& get_instance();
        $this->load->model('log_model');
        $this->load->model('User_model');
        $this->load->model('Api_qualification_model');
    }//function


    function addApplication($form_array){
        //load loging model
        $this->load->model('log_model');

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = false;

        if($this->legacy_db->insert('applications', $form_array)){

            //this code is use for loging
            $QUERY = $this->legacy_db->last_query();
            $id = $this->legacy_db->insert_id();

            if ($this->legacy_db->affected_rows() != 1) {
                $this->legacy_db->trans_rollback();

                //this code is use for loging
                $this->log_model->create_log(0,$id,"","","ADD_APPLICATION_FAILED",'applications',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_APPLICATION","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'applications');

                return false;
            } else {
                $this->legacy_db->trans_commit();

                //add shift as  a morning shift
                $datetime = date('Y-m-d H:i:s');
                $shift_array  = array(
                    "USER_ID"=>$form_array['USER_ID'],
                    "APPLICATION_ID"=>$id,
                    "SHIFT_ID"=>1,
                    "DATETIME"=>$datetime
                );
                $check = $this->addApplicationShift($shift_array);
                if($check){


                    //this code is use for loging
                    $this->legacy_db->where('APPLICATION_ID',$id);
                    $CURRENT_RECORD =  $this->legacy_db->get('applications')->row_array();
                    $this->log_model->create_log(0,$id,"",$CURRENT_RECORD,"ADD_APPLICATION",'applications',11,$form_array['USER_ID']);
                    $this->log_model->itsc_log("ADD_APPLICATION","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'applications');

                    return $id;
                }else{
                  //  echo "addaplication_error";
                   // $this->legacy_db->trans_rollback();
                    $this->legacy_db->where('APPLICATION_ID', $id);
                    $this->legacy_db->delete('applications');

                    //this code is use for loging
                    $this->log_model->create_log(0,$id,"","","ADD_APPLICATION_FAILED",'applications',11,$form_array['USER_ID']);
                    $this->log_model->itsc_log("ADD_APPLICATION","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'applications');

                    return false;
                }


            }

        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$form_array,"ADD_APPLICATION_FAILED",'applications',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_APPLICATION","FAILED","",'CANDIDATE',$form_array['USER_ID'],$form_array,"",0,'applications');

            return false;
        }
    }

    function addApplicationShift($form_array){
        //load loging model
        $this->load->model('log_model');

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = false;

        if($this->legacy_db->insert('applied_shift', $form_array)){

            //this code is use for loging
            $QUERY = $this->legacy_db->last_query();
            $id = $this->legacy_db->insert_id();

            if ($this->legacy_db->affected_rows() != 1) {
                $this->legacy_db->trans_rollback();

                //this code is use for loging
                $this->log_model->create_log(0,$id,"","","ADD_APPLIED_SHIFT_FAILED",'applied_shift',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_APPLIED_SHIFT","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'applied_shift');

                return false;
            } else {
                $this->legacy_db->trans_commit();

                //this code is use for loging
                $this->legacy_db->where('APPLIED_SHIFT_ID',$id);
                $CURRENT_RECORD =  $this->legacy_db->get('applied_shift')->row_array();
                $this->log_model->create_log(0,$id,"",$CURRENT_RECORD,"ADD_APPLIED_SHIFT",'applied_shift',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_APPLIED_SHIFT","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'applied_shift');

                return $id;
            }

        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$form_array,"ADD_APPLIED_SHIFT_FAILED",'applied_shift',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_APPLIED_SHIFT","FAILED","",'CANDIDATE',$form_array['USER_ID'],$form_array,"",0,'applied_shift');

            return false;
        }
    }

    function addApplicantsMinors($form_array){
        //load loging model
        $this->load->model('log_model');

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = false;

        if($this->legacy_db->insert('applicants_minors', $form_array)){

            //this code is use for loging
            $QUERY = $this->legacy_db->last_query();
            $id = $this->legacy_db->insert_id();

            if ($this->legacy_db->affected_rows() != 1) {
                $this->legacy_db->trans_rollback();

                //this code is use for loging
                $this->log_model->create_log(0,$id,"","","ADD_APPLICANTS_MINORS_FAILED",'applicants_minors',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_APPLICANTS_MINORS","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'applicants_minors');

                return false;
            } else {
                $this->legacy_db->trans_commit();

                //this code is use for loging
                $this->legacy_db->where('APPLICANTS_MINORS_ID',$id);
                $CURRENT_RECORD =  $this->legacy_db->get('applicants_minors')->row_array();
                $this->log_model->create_log(0,$id,"",$CURRENT_RECORD,"ADD_APPLICANTS_MINORS",'applicants_minors',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_APPLICANTS_MINORS","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'applicants_minors');

                return $id;
            }

        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$form_array,"ADD_APPLICANTS_MINORS_FAILED",'applicants_minors',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_APPLICANTS_MINORS","FAILED","",'CANDIDATE',$form_array['USER_ID'],$form_array,"",0,'applicants_minors');

            return false;
        }
    }

    function addChallan($form_array){
        //load loging model
        $this->load->model('log_model');

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = true;

        if($this->legacy_db->insert('form_challan', $form_array)){

            //this code is use for loging
            $QUERY = $this->legacy_db->last_query();
            $id = $this->legacy_db->insert_id();

            if ($this->legacy_db->affected_rows() != 1) {
                $this->legacy_db->trans_rollback();

                //this code is use for loging
                $this->log_model->create_log(0,$id,"","","ADD_CHALLAN_FAILED",'form_challan',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_CHALLAN","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'form_challan');

                return false;
            } else {
                $this->legacy_db->trans_commit();

                //this code is use for loging
                $this->legacy_db->where('APPLICATION_ID',$id);
                $CURRENT_RECORD =  $this->legacy_db->get('form_challan')->row_array();
                $this->log_model->create_log(0,$id,"",$CURRENT_RECORD,"ADD_CHALLAN",'form_challan',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_CHALLAN","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'form_challan');

                return true;
            }

        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$form_array,"ADD_CHALLAN_FAILED",'form_challan',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_CHALLAN","FAILED","",'CANDIDATE',$form_array['USER_ID'],$form_array,"",0,'form_challan');

            return false;
        }
    }

    function updateChallanById($id,$formArray){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where('FORM_CHALLAN_ID',$id);
        $this->legacy_db->where('USER_ID',$formArray['USER_ID']);
        $PRE_RECORD =  $this->legacy_db->get('form_challan')->row_array();

        $this->legacy_db->trans_begin();
        $this->legacy_db->where('FORM_CHALLAN_ID',$id);
        $this->legacy_db->update('form_challan',$formArray);

        //this code is use for loging
        $QUERY = $this->legacy_db->last_query();

        if($this->legacy_db->affected_rows() ==1){
            $this->legacy_db->trans_commit();
            //this code is use for loging
            $this->legacy_db->where('FORM_CHALLAN_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('form_challan')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_FORM_CHALLAN",'form_challan',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_FORM_CHALLAN","SUCCESS",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'form_challan');

            return 1;
        }elseif($this->legacy_db->affected_rows() ==0){
            $this->legacy_db->trans_commit();

            //this code is use for loging
            $this->legacy_db->where('FORM_CHALLAN_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('form_challan')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_FORM_CHALLAN",'form_challan',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_FORM_CHALLAN","SUCCESS",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'form_challan');

            return 0;
        }else{
            $this->legacy_db->trans_rollback();

            //this code is use for loging
            $this->legacy_db->where('FORM_CHALLAN_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('form_challan')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_FORM_CHALLAN",'form_challan',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_FORM_CHALLAN","FAILED",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'form_challan');

            return -1;
        }

    }

    function updateApplicationById($id,$formArray){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where('APPLICATION_ID',$id);
        $this->legacy_db->where('USER_ID',$formArray['USER_ID']);
        $PRE_RECORD =  $this->legacy_db->get('applications')->row_array();

        $this->legacy_db->trans_begin();
        $this->legacy_db->where('APPLICATION_ID',$id);
        $this->legacy_db->update('applications',$formArray);

        //this code is use for loging
        $QUERY = $this->legacy_db->last_query();

        if($this->legacy_db->affected_rows() ==1){
            $this->legacy_db->trans_commit();
            //this code is use for loging
            $this->legacy_db->where('APPLICATION_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('applications')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_APPLICATION",'applications',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_APPLICATION","SUCCESS",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'applications');

            return 1;
        }elseif($this->legacy_db->affected_rows() ==0){
            $this->legacy_db->trans_commit();

            //this code is use for loging
            $this->legacy_db->where('APPLICATION_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('applications')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_APPLICATION",'applications',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_APPLICATION","SUCCESS",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'applications');

            return 0;
        }else{
            $this->legacy_db->trans_rollback();

            //this code is use for loging
            $this->legacy_db->where('APPLICATION_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('applications')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_APPLICATION",'applications',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_APPLICATION","FAILED",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'applications');

            return -1;
        }

    }


    function lock_form($APPLICATION_ID,$user_fulldata){
        $user_id = $user_fulldata['users_reg']['USER_ID'];
        $form_array = array('STATUS'=>'C');
        $this->User_model->updateUserById($user_id,$form_array);

        foreach ($user_fulldata['qualifications'] as $qual){
            $form_array = array('STATUS'=>'1',"USER_ID"=>$user_id);
            $this->Api_qualification_model->updateQualification($qual['QUALIFICATION_ID'],$form_array);
        }
        $user_fulldata = $this->User_model->getUserFullDetailById($user_id);

        $user_json = json_encode($user_fulldata);
        $date = date('Y-m-d H:i:s');
        $form_array = array(
            "USER_ID"=>$user_id,
            "FORM_DATA"=>$user_json,
            "IS_SUBMITTED"=>'Y',
            "SUBMISSION_DATE"=>$date,
            "STATUS_ID"=>2
        );

        $this->updateApplicationById($APPLICATION_ID ,$form_array);


        //$APPLICATION_ID,$user_fulldata
    }

    function getApplicationByUserIdAndAdmissionSessionId($user_id,$admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->where("USER_ID",$user_id);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->where("ass.ADMISSION_SESSION_ID",$admission_session_id);
        return $this->legacy_db->get()->row_array();

    }
    function getApplicationByAdmissionSessionId($admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        $this->legacy_db->where("ADMISSION_SESSION_ID",$admission_session_id);
        return $this->legacy_db->get()->result_array();

    }
    function getApplicationByUserId($user_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`form_challan` fc","a.`APPLICATION_ID` = fc.`APPLICATION_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        $this->legacy_db->where("a.USER_ID",$user_id);
        return $this->legacy_db->get()->result_array();

    }
    function getApplicationByUserAndApplicationId($user_id,$application_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`form_challan` fc","a.`APPLICATION_ID` = fc.`APPLICATION_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        $this->legacy_db->where("a.USER_ID",$user_id);
        $this->legacy_db->where("a.APPLICATION_ID",$application_id);
        return $this->legacy_db->get()->row_array();

    }

    function getMinorMappingByDisciplineId($DISCIPLINE_ID){
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('minor_mapping');
        $this->legacy_db->where("DISCIPLINE_ID",$DISCIPLINE_ID);

        return $this->legacy_db->get()->result_array();
    }

    function getApplicantsMinorsByUserIdAndMinorMappingId($user_id,$minor_mapping_id){

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('applicants_minors');
        $this->legacy_db->where("USER_ID",$user_id);
        $this->legacy_db->where("MINOR_MAPPING_ID",$minor_mapping_id);
        $this->legacy_db->where("ACTIVE",1);

        return $this->legacy_db->get()->result_array();
    }
    function getApplicantsMinorsByUserIdAndDisciplineID($user_id,$discipline_id){

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('applicants_minors am');
        $this->legacy_db->join("`minor_mapping` mm","am.`MINOR_MAPPING_ID` = mm.`MINOR_MAPPING_ID`");
        $this->legacy_db->where("am.USER_ID",$user_id);
        $this->legacy_db->where("am.DISCIPLINE_ID",$discipline_id);
        $this->legacy_db->where("ACTIVE",1);

        return $this->legacy_db->get()->result_array();
    }

    function deleteApplicantsMinorsByUserIdAndDisciplineId($user_id,$discipline_id){
        //load loging model
        $id = 0;
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where('DISCIPLINE_ID',$discipline_id);
        $this->legacy_db->where('USER_ID',$user_id);
        $this->legacy_db->where('ACTIVE',1);
        $PRE_RECORD =  $this->legacy_db->get('applicants_minors')->result_array();

        $this->legacy_db->trans_begin();
        $this->legacy_db->where('DISCIPLINE_ID',$discipline_id);
        $this->legacy_db->where('USER_ID',$user_id);
        $this->legacy_db->where('ACTIVE',1);

        $this->legacy_db->delete('applicants_minors');
        //this code is use for loging
        $QUERY = $this->legacy_db->last_query();

        if($this->legacy_db->affected_rows() >=0){
            $this->legacy_db->trans_commit();
            //this code is use for loging
            $this->legacy_db = $this->load->database("admission_db",true);
            $this->legacy_db->where('DISCIPLINE_ID',$discipline_id);
            $this->legacy_db->where('USER_ID',$user_id);
            $CURRENT_RECORD =  $this->legacy_db->get('applicants_minors')->result_array();

            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"DELETE_MINOR_FORM",'applicants_minors',13,$user_id);
            $this->log_model->itsc_log("DELETE_MINOR_FORM","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$id,'form_challan');

            return 1;
        }else{
            $this->legacy_db->trans_rollback();

            //this code is use for loging
            $this->legacy_db = $this->load->database("admission_db",true);
            $this->legacy_db->where('DISCIPLINE_ID',$discipline_id);
            $this->legacy_db->where('USER_ID',$user_id);
            $CURRENT_RECORD =  $this->legacy_db->get('applicants_minors')->result_array();

            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"DELETE_MINOR_FORM_FAILED",'applicants_minors',13,$user_id);
            $this->log_model->itsc_log("DELETE_MINOR_FORM","FAILED",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$id,'form_challan');

            return -1;
        }

    }



}
