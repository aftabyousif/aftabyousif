<?php


class Application_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        //		$CI =& get_instance();
        $this->load->model('log_model');
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

                //this code is use for loging
                $this->legacy_db->where('APPLICATION_ID',$id);
                $CURRENT_RECORD =  $this->legacy_db->get('applications')->row_array();
                $this->log_model->create_log(0,$id,"",$CURRENT_RECORD,"ADD_APPLICATION",'applications',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_APPLICATION","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'applications');

                return $id;
            }

        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$form_array,"ADD_APPLICATION_FAILED",'applications',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_APPLICATION","FAILED","",'CANDIDATE',$form_array['USER_ID'],$form_array,"",0,'applications');

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

    function getApplicationByUserIdAndAdmissionSessionId($user_id,$admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->where("USER_ID",$user_id);
        $this->legacy_db->where("ADMISSION_SESSION_ID",$admission_session_id);
        return $this->legacy_db->get('applications')->row_array();

    }
    function getApplicationByAdmissionSessionId($admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->where("ADMISSION_SESSION_ID",$admission_session_id);
        return $this->legacy_db->get('applications')->result_array();

    }
    function getApplicationByUserId($user_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->where("USER_ID",$user_id);
        return $this->legacy_db->get()->result_array();

    }
    function getApplicationByUserAndApplicationId($user_id,$application_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->where("USER_ID",$user_id);
        $this->legacy_db->where("APPLICATION_ID",$application_id);
        return $this->legacy_db->get()->result_array();

    }


}
