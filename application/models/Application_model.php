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
    
    
    function getEnrollmentByRollNo($roll_no){
        	$this->legacy_db=$this->load->database("admission_online",true);
			$row = $this->legacy_db->select(" * ")
				->from('enrolment enr')
				
				->where('enr.ROLL_NO',$roll_no)
				->get()->row_array();
				return $row;
    }
    function addApplicationWithTransction($form_array ,$form_fees,$PROGRAM_TYPE_ID=0){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = false;
        $transaction_flag = false;

        $is_add_application  = $this->legacy_db->insert('applications', $form_array);
        $application_id = $this->legacy_db->insert_id();
        $application_shift_id = 0;
        $form_challan_id = 0;


        if($application_id>0){
            
            //1= morning, 2 = evening;
            //bydefault consider as a morning shift
            $shift_id  = 1;
            if($PROGRAM_TYPE_ID == 1){
             //$shift_id  = 2; 
             $programTitle="BACHELOR";
            }else{
               // $shift_id  = 1;
                $programTitle="MASTER";
            }
       
            $datetime = date('Y-m-d H:i:s');

            $shift_array  = array(
                "USER_ID"=>$form_array['USER_ID'],
                "APPLICATION_ID"=>$application_id,
                "SHIFT_ID"=>$shift_id,
                "DATETIME"=>$datetime
            );

            $is_add_shift = $this->legacy_db->insert('applied_shift', $shift_array);

            $application_shift_id = $this->legacy_db->insert_id();

            if($application_shift_id>0){

                $challan_array = array(
                    "USER_ID" => $form_array['USER_ID'],
                    'ADMISSION_SESSION_ID' => $form_array['ADMISSION_SESSION_ID'],
                    'APPLICATION_ID' => $application_id,
                    'FORM_FEE_ID' => $form_fees['FORM_FEE_ID'],
                    'CHALLAN_AMOUNT' => $form_fees['AMOUNT']);

                $this->legacy_db->insert('form_challan', $challan_array);

                $form_challan_id = $this->legacy_db->insert_id();
                $challan_no  =  str_pad($form_challan_id, 7, "0", STR_PAD_LEFT);
                $users_reg = $form_fees['USER_DATA']['users_reg'];
                $params = array (
	                'CHALLAN_NO'=>ADMP_CODE.$challan_no,
        			'SECTION_ACCOUNT_ID'=>20,
        			'REF_NO'=>$form_challan_id,
        			'ROLL_NO'=>null,
        			'BATCH_ID'=>null,
        			'DESCRIPTION'=>"ADMISSION PROCESSING FEES",
        			'AMOUNT'=> $form_fees['AMOUNT'],
        			'CHALLAN_DATE'=>date('Y-m-d'),
        			'CNIC_NO'=>$users_reg['CNIC_NO'],
        			'NAME'=>$users_reg['FIRST_NAME'],
        			'FNAME'=>$users_reg['FNAME'],
        			'SURNAME'=>$users_reg['LAST_NAME'],
        			'MOBILE_NO'=>"0".$users_reg['MOBILE_NO'],
        			'EMAIL'=>$users_reg['EMAIL'],
        			'PROGRAM'=>$programTitle,
        			'PROG_TYPE'=>$programTitle,
        			'DUE_DATE'=>$form_fees['DUE_DATE'],
        			'PROG_CODE'=>null,
        			'TYPE_CODE'=>'20-001'
    		);
    	//	$transaction_flag = true;
                $response = postCURL(HBL_PAYMENT_URL,$params);
                if($form_challan_id>0&&$response['response_code']=="200"){
                    $transaction_flag = true;
                }


            }

        }

        $QUERY = "";
        $PRE_RECORD ="";
        $CURRENT_RECORD =  array("APPLICATION_ID"=>$application_id,
            "APPLIED_SHIFT_ID"=>$application_shift_id,
            "FORM_CHALLAN_ID"=>$form_challan_id);

       if($transaction_flag ==true){
           $this->legacy_db->trans_commit();

           $this->log_model->create_log(0,$application_id,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICATION",'applications',11,$form_array['USER_ID']);
           $this->log_model->itsc_log("ADD_APPLICATION","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$application_id,'applications');

           return $application_id;
       }else{
           $this->legacy_db->trans_rollback();
           $this->log_model->create_log(0,$application_id,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICATION_FAILED",'applications',11,$form_array['USER_ID']);
           $this->log_model->itsc_log("ADD_APPLICATION","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$application_id,'applications');

           return false;
       }


    }
    
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
$this->legacy_db->delete('applicants_minors', array('APPLICATION_ID' => $APPLICATION_ID));
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
                $this->legacy_db->where('FORM_CHALLAN_ID',$id);
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

    function updateApplicationById($id,$formArray,$admin_id= 0){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where('APPLICATION_ID',$id);
        if(isset($formArray['USER_ID'])&&$admin_id==0){
            $admin_id = $formArray['USER_ID'];
        }
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
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_APPLICATION",'applications',12,$admin_id);
            $this->log_model->itsc_log("UPDATE_APPLICATION","SUCCESS",$QUERY,'CANDIDATE',$admin_id,$CURRENT_RECORD,$PRE_RECORD,$id,'applications');

            return 1;
        }elseif($this->legacy_db->affected_rows() ==0){
            $this->legacy_db->trans_commit();

            //this code is use for loging
            $this->legacy_db->where('APPLICATION_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('applications')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_APPLICATION",'applications',12,$admin_id);
            $this->log_model->itsc_log("UPDATE_APPLICATION","SUCCESS",$QUERY,'CANDIDATE',$admin_id,$CURRENT_RECORD,$PRE_RECORD,$id,'applications');

            return 0;
        }else{
            $this->legacy_db->trans_rollback();

            //this code is use for loging
            $this->legacy_db->where('APPLICATION_ID',$id);
            $CURRENT_RECORD =  $this->legacy_db->get('applications')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_APPLICATION",'applications',12,$admin_id);
            $this->log_model->itsc_log("UPDATE_APPLICATION","FAILED",$QUERY,'CANDIDATE',$admin_id,$CURRENT_RECORD,$PRE_RECORD,$id,'applications');

            return -1;
        }

    }
    
    function updateApplicationsByIds($ids,$formArray,$admin_id){
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where_in('APPLICATION_ID',$ids);
        $this->legacy_db->update('applications',$formArray);
        $PRE_RECORD = json_encode($ids);
        $CURRENT_RECORD = json_encode($formArray);
        $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_APPLICATION",'applications',12,$admin_id);

    }

    function lock_form($APPLICATION_ID,$user_fulldata){
        $user_id = $user_fulldata['users_reg']['USER_ID'];
        $form_array = array('STATUS'=>'C');
        $this->User_model->updateUserById($user_id,$form_array);

        foreach ($user_fulldata['qualifications'] as $qual){
           $form_array = array(
                            'DISCIPLINE_ID'=>$qual['DISCIPLINE_ID'],
                            'USER_ID'=>$qual['USER_ID'],
                            'ORGANIZATION_ID'=>$qual['ORGANIZATION_ID'],
                            'INSTITUTE_ID'=>$qual['INSTITUTE_ID'],
                            'START_DATE'=>$qual['START_DATE'],
                            'END_DATE'=>$qual['END_DATE'],
                            'IS_RESULT_DECLARE'=>$qual['IS_RESULT_DECLARE'],
                            'ROLL_NO'=>$qual['ROLL_NO'],
                            'TOTAL_MARKS'=>$qual['TOTAL_MARKS'],
                            'OBTAINED_MARKS'=>$qual['OBTAINED_MARKS'],
                            'CGPA'=>$qual['CGPA'],
                            'OUT_OF'=>$qual['OUT_OF'],
                            'GRADE'=>$qual['GRADE'],
                            'GRADING_AS'=>$qual['GRADING_AS'],
                            'REMARKS'=>$qual['REMARKS'],
                            'ACTIVE'=>$qual['ACTIVE'],
                            'MARKSHEET_IMAGE'=>$qual['MARKSHEET_IMAGE'],
                            'PASSCERTIFICATE_IMAGE'=>$qual['PASSCERTIFICATE_IMAGE'],
                            'PASSING_YEAR'=>$qual['PASSING_YEAR'],
                            'STATUS'=>1);
              
            
           // $form_array = $qual;
            $this->Api_qualification_model->updateQualification($qual['QUALIFICATION_ID'],$form_array,$APPLICATION_ID);
        }
        //$user_fulldata = $this->User_model->getUserFullDetailById($user_id,$APPLICATION_ID);

       // $user_json = json_encode($user_fulldata);
        $date = date('Y-m-d H:i:s');
        $form_array = array(
            "USER_ID"=>$user_id,
            "FORM_DATA"=>'',
            "IS_SUBMITTED"=>'Y',
            "SUBMISSION_DATE"=>$date,
            "STATUS_ID"=>2
        );

        $this->updateApplicationById($APPLICATION_ID ,$form_array);


        //$APPLICATION_ID,$user_fulldata
    }
     
    function unlock_form($APPLICATION_ID,$user_fulldata,$admin_id){
        $user_id = $user_fulldata['users_reg']['USER_ID'];
        $form_array = array('STATUS'=>'N');
        $this->User_model->updateUserById($user_id,$form_array);

        foreach ($user_fulldata['qualifications'] as $qual){
            $form_array = array('STATUS'=>'0',"USER_ID"=>$user_id);
            $this->Api_qualification_model->updateQualification($qual['QUALIFICATION_ID'],$form_array);
        }

        //$user_fulldata = $this->User_model->getUserFullDetailById($user_id);


        $form_array = array(
            "USER_ID"=>$user_id,
            "IS_SUBMITTED"=>'N',
            "STATUS_ID"=>1
        );

        $this->updateApplicationById($APPLICATION_ID ,$form_array);
        $QUERY = "";
        $PRE_RECORD ="";
        $CURRENT_RECORD =  array("APPLICATION_ID"=>$APPLICATION_ID,
            "USER_ID"=>$user_id
        );
        $this->log_model->create_log(0,$APPLICATION_ID,$PRE_RECORD,$CURRENT_RECORD,"UNLOCK_APPLICATION",'applications',11,$admin_id);
        //$APPLICATION_ID,$user_fulldata
    }

    function getApplicationByUserIdAndAdmissionSessionId($user_id,$admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->where("a.USER_ID",$user_id);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
         $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`form_challan` fc","fc.`APPLICATION_ID` = a.`APPLICATION_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->where("ass.ADMISSION_SESSION_ID",$admission_session_id);
        $this->legacy_db->where("a.IS_DELETED","N");
        return $this->legacy_db->get()->row_array();

    }
    function getApplicationByAdmissionSessionId($admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
         $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        $this->legacy_db->where("ADMISSION_SESSION_ID",$admission_session_id);
        return $this->legacy_db->get()->result_array();

    }
    function getApplicationByUserId($user_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*,a.REMARKS,a.STATUS_ID');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`form_challan` fc","a.`APPLICATION_ID` = fc.`APPLICATION_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        $this->legacy_db->where("a.USER_ID",$user_id);
        $this->legacy_db->where("a.IS_DELETED","N");
        return $this->legacy_db->get()->result_array();

    }
    function getApplicationByUserAndApplicationId($user_id,$application_id)
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
        $this->legacy_db->where("a.APPLICATION_ID",$application_id);
        $this->legacy_db->where("a.IS_DELETED","N");
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
    function getApplicantsMinorsByApplicationIdAndMinorMappingId($application_id,$minor_mapping_id){

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('applicants_minors');
        $this->legacy_db->where("APPLICATION_ID",$application_id);
        $this->legacy_db->where("MINOR_MAPPING_ID",$minor_mapping_id);
        $this->legacy_db->where("ACTIVE",1);

        return $this->legacy_db->get()->result_array();
    }
   function getApplicantsMinorsByUserIdAndApplicationId($user_id,$application_id){

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('applicants_minors am');
        $this->legacy_db->join("`minor_mapping` mm","am.`MINOR_MAPPING_ID` = mm.`MINOR_MAPPING_ID`");
        $this->legacy_db->where("am.USER_ID",$user_id);
        $this->legacy_db->where("am.APPLICATION_ID",$application_id);
        $this->legacy_db->where("ACTIVE",1);

        return $this->legacy_db->get()->result_array();
    }
    function getApplicantsMinorsByApplicationIdAndDisciplineID($application_id,$discipline_id){

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('applicants_minors am');
        $this->legacy_db->join("`minor_mapping` mm","am.`MINOR_MAPPING_ID` = mm.`MINOR_MAPPING_ID`");
        $this->legacy_db->where("am.APPLICATION_ID",$application_id);
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
    
    function getApplicationByApplicationID($application_id){
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
         $this->legacy_db->join("`users_reg` ur","ur.`USER_ID` = a.`USER_ID`");
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
         $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`form_challan` fc","a.`APPLICATION_ID` = fc.`APPLICATION_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        // $this->legacy_db->where("a.USER_ID",$user_id);
        $this->legacy_db->where("a.APPLICATION_ID",$application_id);
        $this->legacy_db->where("a.IS_DELETED","N");
        return $this->legacy_db->get()->row_array();
    }
    
    
     //ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php
    function getApplicantCategory($application_id,$user_id){
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('application_category ac');
        $this->legacy_db->join('form_category AS fc', 'fc.FORM_CATEGORY_ID = ac.FORM_CATEGORY_ID');
        $this->legacy_db->where("ac.USER_ID",$user_id);
        $this->legacy_db->where("ac.APPLICATION_ID",$application_id);

        return $this->legacy_db->get()->result_array();
    }
//ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php
    function insertApplicantCategory($form_array ){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $user_id = $form_array[0]['USER_ID'];
        $APPLICATION_ID = $form_array[0]['APPLICATION_ID'];
        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = false;
        $transaction_flag = false;
        if($this->legacy_db->insert_batch('application_category', $form_array)){
            $transaction_flag = true;
        }else{
            $transaction_flag = false;
        }
        if ($this->legacy_db->affected_rows() >=1) {
            $transaction_flag = true;
        }else{
            $transaction_flag = false;
        }
        $QUERY = $this->legacy_db->last_query();
        $CURRENT_RECORD=  json_encode($form_array);
        $PRE_RECORD = "";

        if($transaction_flag ==true){
            $this->legacy_db->trans_commit();

            $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICATION_CATEGORY",'application_category',11,$user_id);
            $this->log_model->itsc_log("ADD_APPLICATION_CATEGORY","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,"",0,'application_category');

            return true;
        }else{
            $this->legacy_db->trans_rollback();
            $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICATION_CATEGORYFAILED",'application_category',11,$user_id);
            $this->log_model->itsc_log("ADD_APPLICATION_CATEGORY","FAILED",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,"",0,'application_category');

            return false;
        }


    }
//ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php
    function deleteAndInsertApplicantCategory($form_array,$admin_id=0 ){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $user_id = $form_array[0]['USER_ID'];
        $APPLICATION_ID = $form_array[0]['APPLICATION_ID'];
        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = true;
        $transaction_flag = false;

        $this->legacy_db->where('USER_ID', $user_id);
        $this->legacy_db->where('APPLICATION_ID', $APPLICATION_ID);

        if($this->legacy_db->delete('application_category')){

            if($this->legacy_db->insert_batch('application_category', $form_array)){
                if ($this->legacy_db->affected_rows() >=1) {
                    $transaction_flag = true;
                }else{
                    $transaction_flag = false;
                }
            }else{
                $transaction_flag = false;
            }

        }else{
            $transaction_flag = false;
        }


        $QUERY = $this->legacy_db->last_query();
        $CURRENT_RECORD=  json_encode($form_array);
        $PRE_RECORD = "";
        if($admin_id==0){
            $admin_id = $user_id;
        }
        if($transaction_flag ==true){
            $this->legacy_db->trans_commit();

            $this->log_model->create_log($user_id,0,$PRE_RECORD,$CURRENT_RECORD,"DELETE_AND_ADD_APPLICATION_CATEGORY",'application_category',11,$admin_id);
            $this->log_model->itsc_log("DELETE_AND_ADD_APPLICATION_CATEGORY","SUCCESS",$QUERY,'CANDIDATE',$admin_id,$CURRENT_RECORD,"",0,'application_category');

            return true;
        }else{
            $this->legacy_db->trans_rollback();
            $this->log_model->create_log($user_id,0,$PRE_RECORD,$CURRENT_RECORD,"DELETE_AND_ADD_APPLICATION_CATEGORYFAILED",'application_category',11,$admin_id);
            $this->log_model->itsc_log("DELETE_AND_ADD_APPLICATION_CATEGORY","FAILED",$QUERY,'CANDIDATE',$admin_id,$CURRENT_RECORD,"",0,'application_category');

            return false;
        }


    }

//ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php
      function deleteAndInsertApplicantChoice($form_array,$lat_info=null,$admin_id=0 ){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $user_id = $form_array[0]['USER_ID'];
        $APPLICATION_ID = $form_array[0]['APPLICATION_ID'];
        $SHIFT_ID = $form_array[0]['SHIFT_ID'];
        $IS_SPECIAL_CHOICE = isset($form_array[0]['IS_SPECIAL_CHOICE'])?$form_array[0]['IS_SPECIAL_CHOICE']:'N';
        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = true;
        $transaction_flag = false;
        if($lat_info!=null){

            $this->legacy_db->where('USER_ID', $user_id);
            $this->legacy_db->where('APPLICATION_ID', $APPLICATION_ID);
            $this->legacy_db->where('ACTIVE', 1);
         
            $this->legacy_db->set('ACTIVE', '0');
            if($this->legacy_db->update('applicants_lat_info')) {
                
                if($this->legacy_db->insert('applicants_lat_info', $lat_info)){
                    $transaction_flag = true;
                }else{
                    $transaction_flag = false;
                }
            }else{
                $transaction_flag = false;
            }
        }

        $this->legacy_db->where('USER_ID', $user_id);
        $this->legacy_db->where('SHIFT_ID', $SHIFT_ID);
        $this->legacy_db->where('APPLICATION_ID', $APPLICATION_ID);
        $this->legacy_db->where('IS_SPECIAL_CHOICE', $IS_SPECIAL_CHOICE);
        if($this->legacy_db->delete('application_choices')){

            if($this->legacy_db->insert_batch('application_choices', $form_array)){
                if ($this->legacy_db->affected_rows() >=1) {
                    $transaction_flag = true;
                }else{
                    $transaction_flag = false;
                }
            }else{
                $transaction_flag = false;
            }

        }else{
            $transaction_flag = false;
        }


        $QUERY = $this->legacy_db->last_query();
        $CURRENT_RECORD=  json_encode($form_array);
        $PRE_RECORD = "";
        if($admin_id==0){
            $admin_id = $user_id;
        }
        if($transaction_flag ==true){
            $this->legacy_db->trans_commit();

            $this->log_model->create_log($user_id,0,$PRE_RECORD,$CURRENT_RECORD,"DELETE_AND_ADD_APPLICATION_CHOICE",'application_choices',11,$admin_id);
            $this->log_model->itsc_log("DELETE_AND_ADD_APPLICATION_CHOICE","SUCCESS",$QUERY,'CANDIDATE',$admin_id,$CURRENT_RECORD,"",0,'application_choices');

            return true;
        }else{
            $this->legacy_db->trans_rollback();
            $this->log_model->create_log($user_id,0,$PRE_RECORD,$CURRENT_RECORD,"DELETE_AND_ADD_APPLICATION_CHOICE_FAILED",'application_choices',11,$admin_id);
            $this->log_model->itsc_log("DELETE_AND_ADD_APPLICATION_CHOICE","FAILED",$QUERY,'CANDIDATE',$admin_id,$CURRENT_RECORD,"",0,'application_choices');

            return false;
        }


    }
//ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php
    function getChoiceByUserAndApplicationAndShiftId($USER_ID,$APPLICATION_ID,$SHIFT_ID){
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('application_choices ac');
        $this->legacy_db->join('program_list AS pl', 'pl.PROG_LIST_ID = ac.PROG_LIST_ID');
        if($USER_ID>0) $this->legacy_db->where("ac.USER_ID",$USER_ID); //this row updated yasir on 09-06-2021
        $this->legacy_db->where("ac.APPLICATION_ID",$APPLICATION_ID);
        $this->legacy_db->where("ac.SHIFT_ID",$SHIFT_ID);
        $this->legacy_db->order_by('ac.CHOICE_NO', 'ASC');
        return $this->legacy_db->get()->result_array();
    }
//ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php
    function getLatInfoByUserAndApplicationId($USER_ID,$APPLICATION_ID){
        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('applicants_lat_info ali');

        $this->legacy_db->where("ali.USER_ID",$USER_ID);
        $this->legacy_db->where("ali.ACTIVE",1);
        $this->legacy_db->where("ali.APPLICATION_ID",$APPLICATION_ID);


        return $this->legacy_db->get()->row_array();
    }
//ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php
     function final_lock_form($APPLICATION_ID,$user_fulldata,$status_id=0){
        $user_id = $user_fulldata['users_reg']['USER_ID'];
        $form_array = array('STATUS'=>'C');
        $this->User_model->updateUserById($user_id,$form_array);
      
        foreach ($user_fulldata['qualifications'] as $qual){
            $form_array = array(
                            'DISCIPLINE_ID'=>$qual['DISCIPLINE_ID'],
                            'USER_ID'=>$qual['USER_ID'],
                            'ORGANIZATION_ID'=>$qual['ORGANIZATION_ID'],
                            'INSTITUTE_ID'=>$qual['INSTITUTE_ID'],
                            'START_DATE'=>$qual['START_DATE'],
                            'END_DATE'=>$qual['END_DATE'],
                            'IS_RESULT_DECLARE'=>$qual['IS_RESULT_DECLARE'],
                            'ROLL_NO'=>$qual['ROLL_NO'],
                            'TOTAL_MARKS'=>$qual['TOTAL_MARKS'],
                            'OBTAINED_MARKS'=>$qual['OBTAINED_MARKS'],
                            'CGPA'=>$qual['CGPA'],
                            'OUT_OF'=>$qual['OUT_OF'],
                            'GRADE'=>$qual['GRADE'],
                            'GRADING_AS'=>$qual['GRADING_AS'],
                            'REMARKS'=>$qual['REMARKS'],
                            'ACTIVE'=>$qual['ACTIVE'],
                            'MARKSHEET_IMAGE'=>$qual['MARKSHEET_IMAGE'],
                            'PASSCERTIFICATE_IMAGE'=>$qual['PASSCERTIFICATE_IMAGE'],
                            'PASSING_YEAR'=>$qual['PASSING_YEAR'],
                            'STATUS'=>1);
              
            
           // $form_array = $qual;
            $this->Api_qualification_model->updateQualification($qual['QUALIFICATION_ID'],$form_array,$APPLICATION_ID);
        }
        $user_fulldata = $this->User_model->getUserFullDetailWithChoiceById($user_id,$APPLICATION_ID);

        //$user_json = json_encode($user_fulldata);
        $date = date('Y-m-d H:i:s');
        if($status_id==0){
            $status_id = FINAL_SUBMIT_STATUS_ID;
        }
        $form_array = array(
            "USER_ID"=>$user_id,
            "FORM_DATA"=>'',
            "IS_SUBMITTED"=>'Y',
            "SUBMISSION_DATE"=>$date,
            "STATUS_ID"=>$status_id
        );

        $this->updateApplicationById($APPLICATION_ID ,$form_array);


        //$APPLICATION_ID,$user_fulldata
    }
//ADDED FUNCTION ON  5-nov-2020 MODAL FILE Application_model.php

    function addApplicantsMinorsBatch($form_array ){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $user_id = $form_array[0]['USER_ID'];
        $APPLICATION_ID = $form_array[0]['APPLICATION_ID'];
        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = false;
        $this->legacy_db->delete('applicants_minors', array('APPLICATION_ID' => $APPLICATION_ID));
        $transaction_flag = false;
        if($this->legacy_db->insert_batch('applicants_minors', $form_array)){
            $transaction_flag = true;
        }else{
            $transaction_flag = false;
        }
        if ($this->legacy_db->affected_rows() >=1) {
            $transaction_flag = true;
        }else{
            $transaction_flag = false;
        }
        $QUERY = $this->legacy_db->last_query();
        $CURRENT_RECORD=  json_encode($form_array);
        $PRE_RECORD = "";

        if($transaction_flag ==true){
            $this->legacy_db->trans_commit();

            $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICANTS_MINORS",'applicants_minors',11,$user_id);
            $this->log_model->itsc_log("ADD_APPLICANTS_MINORS","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,"",0,'applicants_minors');

            return true;
        }else{
            $this->legacy_db->trans_rollback();
            $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICANTS_MINORS_FAILED",'applicants_minors',11,$user_id);
            $this->log_model->itsc_log("ADD_APPLICANTS_MINORS","FAILED",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,"",0,'applicants_minors');

            return false;
        }


    }

//ADDED FUNCTION ON  30-dec-2020 MODAL FILE Application_model.php
    function addApplicantsMinorsBatchAdmin($form_array ){
        //load loging model
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $user_id = $form_array[0]['USER_ID'];
        $APPLICATION_ID = $form_array[0]['APPLICATION_ID'];
        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = false;
        $transaction_flag = false;
        $this->legacy_db->delete('applicants_minors', array('APPLICATION_ID' => $form_array['APPLICATION_ID']));
        if($this->legacy_db->insert_batch('applicants_minors', $form_array)){
            $transaction_flag = true;
        }else{
            $transaction_flag = false;
        }
        if ($this->legacy_db->affected_rows() >=1) {
            $transaction_flag = true;
        }else{
            $transaction_flag = false;
        }
        $QUERY = $this->legacy_db->last_query();
        $CURRENT_RECORD=  json_encode($form_array);
        $PRE_RECORD = "";

        if($transaction_flag ==true){
            $this->legacy_db->trans_commit();

           // $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICANTS_MINORS",'applicants_minors',11,$user_id);
           // $this->log_model->itsc_log("ADD_APPLICANTS_MINORS","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,"",0,'applicants_minors');

            return true;
        }else{
            $this->legacy_db->trans_rollback();
           // $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"ADD_APPLICANTS_MINORS_FAILED",'applicants_minors',11,$user_id);
//$this->log_model->itsc_log("ADD_APPLICANTS_MINORS","FAILED",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,"",0,'applicants_minors');

            return false;
        }


    }
    
    function getApplicationByAdmissionSessionIdAdmin($admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*,a.REMARKS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
         $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        $this->legacy_db->where("a.ADMISSION_SESSION_ID",$admission_session_id);
        $this->legacy_db->where("a.STATUS_ID > 1");
        
        $this->legacy_db->where("a.IS_DELETED","N");
        $result = $this->legacy_db->get()->result_array();
       // echo $this->legacy_db->last_query();
       // exit();
        return $result;

    }
    
      //ADDED FUNCTION ON  7-dec-2021 MODAL FILE Application_model.php
    function update_lat_info($id,$lat_info){
        //load loging model
        $this->load->model('log_model');

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = true;
        $this->legacy_db->where('LAT_INFO_ID',$id);
        $this->legacy_db->update('applicants_lat_info',$lat_info);
        if($this->legacy_db->affected_rows() ==1){
            $QUERY = "";
            $this->legacy_db->trans_commit();

            $this->log_model->create_log($id,$id,"",$lat_info,"UPDATE_LAT_INFO",'applicants_lat_info',12,$lat_info['USER_ID']);
            $this->log_model->itsc_log("UPDATE_LAT_INFO","SUCCESS",$QUERY,'CANDIDATE',$lat_info['USER_ID'],$lat_info,"",$id,'applicants_lat_info');

            return true;

        }else{
            $QUERY = "";
            $this->legacy_db->trans_rollback();

            $this->log_model->create_log($id,$id,"",$lat_info,"UPDATE_LAT_INFO",'applicants_lat_info',12,$lat_info['USER_ID']);
            $this->log_model->itsc_log("UPDATE_LAT_INFO","FAILED",$QUERY,'CANDIDATE',$lat_info['USER_ID'],$lat_info,"",$id,'applicants_lat_info');

            return false;
        }
    }
    function insert_lat_info($lat_info){

        //load loging model
        $this->load->model('log_model');

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = true;

        if($this->legacy_db->insert('applicants_lat_info', $lat_info)){

            //this code is use for loging
            $QUERY = $this->legacy_db->last_query();
            $id = $this->legacy_db->insert_id();
            $this->legacy_db->trans_commit();

            $this->log_model->create_log($id,$id,"",$lat_info,"ADD_LAT_INFO",'applicants_lat_info',11,$lat_info['USER_ID']);
            $this->log_model->itsc_log("ADD_LAT_INFO","SUCCESS",$QUERY,'CANDIDATE',$lat_info['USER_ID'],$lat_info,"",$id,'applicants_lat_info');

            return true;
        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$lat_info,"ADD_LAT_INFO",'applicants_lat_info',11,$lat_info['USER_ID']);
            $this->log_model->itsc_log("ADD_LAT_INFO","FAILED","",'CANDIDATE',$lat_info['USER_ID'],$lat_info,"",0,'applicants_lat_info');

            return false;
        }
    }
    function insert_choice($form_array,$lat_info){
        //load loging model
        $this->load->model('log_model');

        $this->legacy_db = $this->load->database("admission_db",true);

        $this->legacy_db->trans_begin();
        $this->legacy_db->db_debug = true;

        //$this->legacy_db->set();
        if($this->legacy_db->insert('applicants_lat_info',$lat_info)){
            //$this->legacy_db->set();
            if($this->legacy_db->insert('application_choices',$form_array)){
                $QUERY = $this->legacy_db->last_query();
                $id = $this->legacy_db->insert_id();
                $this->legacy_db->trans_commit();
                $this->log_model->create_log(0,$id,"","","ADD_LAT_CHOICE",'application_choices',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_LAT_CHOICE","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'application_choices');
                return true;
            }else{
                $this->legacy_db->trans_rollback();
                $this->log_model->create_log(0,0,"","","ADD_LAT_CHOICE_FAILED",'application_choices',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_LAT_CHOICE","FAILED","",'CANDIDATE',$form_array['USER_ID'],"","",0,'application_choices');

            }

        }
        else{
            //this code is use for loging
            $this->legacy_db->trans_rollback();
            $this->log_model->create_log(0,0,"","","ADD_LAT_CHOICE_FAILED",'application_choices',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_LAT_CHOICE","FAILED","",'CANDIDATE',$form_array['USER_ID'],"","",0,'application_choices');


        }

        return false;
    }
    
     function updateChoiceByApplicationAndProgListID($id,$prog_list_id,$formArray,$admin_id){
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where('APPLICATION_ID',$id);
        $this->legacy_db->where('PROG_LIST_ID',$prog_list_id);
        
        $this->legacy_db->update('application_choices',$formArray);
        $PRE_RECORD = json_encode($ids);
        $CURRENT_RECORD = json_encode($formArray);
        $this->log_model->create_log(0,0,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_LAT_CHOIDEAPPLICATION",'applications',12,$admin_id);

    }
    
     function getApplicationByAdmissionSessionIdAdminForBackup($admission_session_id)
    {
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('a.*,a.REMARKS,tr.CPN');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
         $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
         $this->legacy_db->join("`test_result` tr","tr.`APPLICATION_ID` = a.`APPLICATION_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
        $this->legacy_db->where("a.ADMISSION_SESSION_ID",$admission_session_id);
        $this->legacy_db->where("a.STATUS_ID > 1");
        
        $this->legacy_db->where("a.IS_DELETED","N");
        $result = $this->legacy_db->get()->result_array();
       // echo $this->legacy_db->last_query();
       // exit();
        return $result;

    }
    
    /*
    Yasir added following code on 26-03-2021
    */
    
    // ,SUM(fl.PAID_AMOUNT) AS PAID_AMOUNT
    
    function getApplicantCurrentAdmission($user_id=0,$application_id=0,$selection_list_id=0){
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->distinct();
		$this->legacy_db->select('c.NAME,sl.SELECTION_LIST_ID,sl.APPLICATION_ID,cat.CATEGORY_NAME,pl.PROGRAM_TITLE,
  SHIFT_NAME,
  YEAR,
  BATCH_REMARKS,
  hcf.APPLICATION_ID AS FORM_SUBMITTED,
  pt.PROGRAM_TITLE AS PROGRAM_TYPE_TITLE');
		$this->legacy_db->from(' campus c ');
		$this->legacy_db->join("admission_session adm_s ","(c.CAMPUS_ID = adm_s.CAMPUS_ID)");
		$this->legacy_db->join("program_type pt ","(pt.PROGRAM_TYPE_ID = adm_s.PROGRAM_TYPE_ID)");
		$this->legacy_db->join("sessions s ","(s.SESSION_ID = adm_s.SESSION_ID)");
		$this->legacy_db->join("selection_list sl","(adm_s.ADMISSION_SESSION_ID = sl.ADMISSION_SESSION_ID)");
		$this->legacy_db->join("applications ap ","(ap.APPLICATION_ID = sl.APPLICATION_ID)");
		$this->legacy_db->join("users_reg reg ","(ap.USER_ID = reg.USER_ID)");
		$this->legacy_db->join("program_list pl ","(pl.PROG_LIST_ID = sl.PROG_LIST_ID)");
		$this->legacy_db->join("shift sf","(sf.SHIFT_ID = sl.SHIFT_ID)");
		$this->legacy_db->join("category cat","(cat.CATEGORY_ID = sl.CATEGORY_ID)");
		$this->legacy_db->join("candidate_account ca","(sl.APPLICATION_ID=ca.APPLICATION_ID AND ca.ACTIVE = 1)");
		$this->legacy_db->join(" fee_ledger fl ","(ca.ACCOUNT_ID = fl.ACCOUNT_ID)");
		$this->legacy_db->join(" hardcopy_submitted_forms hcf ","(hcf.APPLICATION_ID = ca.APPLICATION_ID)","LEFT");
		$this->legacy_db->where("sl.SELECTION_LIST_ID = fl.SELECTION_LIST_ID");
		
		$this->legacy_db->where("sl.ACTIVE=1");
		if($user_id>0) $this->legacy_db->where("reg.USER_ID",$user_id);
		if($application_id>0) $this->legacy_db->where("sl.APPLICATION_ID",$application_id);
		if($selection_list_id>0) $this->legacy_db->where("sl.SELECTION_LIST_ID",$selection_list_id);
	
		$result = $this->legacy_db->get()->result_array();
		// echo $this->legacy_db->last_query();
		// exit();
		return $result;
	}
	
	function getCandidateRollNo($selection_list_id){
	    
		$this->legacy_db = $this->load->database("admission_db",true);

		/*
		$this->legacy_db->select('ROLL_NO');
		$this->legacy_db->from(' examination_data ');
		$this->legacy_db->where("SELECTION_LIST_ID",$selection_list_id);
		$result = $this->legacy_db->get()->row_array();
//		 echo $this->legacy_db->last_query();
//		 exit();
		return $result;
		*/

		$this->legacy_db->select("GROUP_CONCAT(ss.SESSION_CODE,'/',smp.PROG_CODE,'/',slc.ROLL_NO_CODE)  AS ROLL_NO");
		$this->legacy_db->from('users_reg ur');
		$this->legacy_db->join('applications a','ur.`USER_ID` = a.`USER_ID`');
		$this->legacy_db->join('`selection_list` `slc`','slc.`APPLICATION_ID` = a.`APPLICATION_ID` ');
		$this->legacy_db->join('`program_list` `pl`','slc.`PROG_LIST_ID` = pl.`PROG_LIST_ID`');
		$this->legacy_db->join('`admission_session` `ass` ','ass.`ADMISSION_SESSION_ID` = slc.`ADMISSION_SESSION_ID`');
		$this->legacy_db->join('`sessions` `ss` ','ass.`SESSION_ID` = ss.`SESSION_ID` ');
		$this->legacy_db->join('`campus` `c` ','ass.`CAMPUS_ID` = c.`CAMPUS_ID` ');
		$this->legacy_db->join('`shift` `s` ','slc.`SHIFT_ID` = s.`SHIFT_ID`  ');
		$this->legacy_db->join('shift_program_mapping smp  ','smp.PROG_LIST_ID = slc.`PROG_LIST_ID` AND smp.`SHIFT_ID` = s.`SHIFT_ID` AND smp.`CAMPUS_ID` = ass.`CAMPUS_ID`');
		$this->legacy_db->where("SELECTION_LIST_ID",$selection_list_id);
		$result = $this->legacy_db->get()->row_array();
		return $result;
	}//method
	
	function getApplicationByUserIdForApi($user_id){
	     $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('ac.*,aps.*,ss.*,fc.*,c.*,pt.*,a.REMARKS,a.FORM_STATUS');
        $this->legacy_db->from('applications a');
        $this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
        $this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
        $this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
        $this->legacy_db->join("`form_challan` fc","a.`APPLICATION_ID` = fc.`APPLICATION_ID`");
        $this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
        $this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID`","LEFT");
         $this->legacy_db->join("`admit_card` ac","ac.`APPLICATION_ID` = a.`APPLICATION_ID`","LEFT");
        $this->legacy_db->where("a.USER_ID",$user_id);
        $this->legacy_db->where("a.IS_DELETED","N");
        return $this->legacy_db->get()->result_array();
	}
	function getChallanById($FORM_CHALLAN_ID){
$this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->select('*');
        $this->legacy_db->from('form_challan AS fc');
        $this->legacy_db->where('fc.FORM_CHALLAN_ID',$FORM_CHALLAN_ID);

        $result = $this->legacy_db->get()->row_array();

        return $result;
    }
   
}
