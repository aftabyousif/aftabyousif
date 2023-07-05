<?php


class FormVerificationModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
		$this->load->model('User_model');
	}

	function get_application_status_list ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->order_by('DISPLAY_ORDER ASC');
		return $this->legacy_db->get('application_status')->result_array();
	}

	function get_announced_campus ($program_type,$session_id)
	{
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->select("`ADMISSION_SESSION_ID`,
									  c.`CAMPUS_ID`,
									  c.`IS_COLLEGE`,
									  s.`SESSION_ID`,
									  pt.`PROGRAM_TYPE_ID`,
									  `ADMISSION_START_DATE`,
									  `ADMISSION_END_DATE`,
									  `NAME`,
									  `LOCATION`,
									  `PROGRAM_TITLE`,
									  `YEAR`,
									  `BATCH_REMARKS` ");
		$this->legacy_db->from("`sessions` s");
		$this->legacy_db->join("`admission_session` ass","s.`SESSION_ID` = ass.`SESSION_ID` AND s.SESSION_ID=$session_id",'INNER');
		$this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`",'INNER');
		$this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID` AND pt.`PROGRAM_TYPE_ID`=$program_type",'INNER');
//		$this->legacy_db->where("ass.`DISPLAY` = 1");
		$this->legacy_db->order_by("c.DISPLAY_ORDER",'ASC');
		return $this->legacy_db->get()->result_array();
	}

	function get_single_Application_for_verification($admission_session_id,$status_id)
	{
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->select('*,a.REMARKS');
		$this->legacy_db->from('applications a');
		$this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
		$this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
		$this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
		$this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
		$this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID` AND aps.STATUS_ID=$status_id","INNER");
		$this->legacy_db->where("ass.ADMISSION_SESSION_ID",$admission_session_id);
		$this->legacy_db->limit(700);
		return $this->legacy_db->get()->result_array();
	}

	function UpdateStatus($where,$record,$prev_record,$table,$user_id,$application_id){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->trans_begin();

		$this->legacy_db->where($where);
		$this->legacy_db->update($table,$record);
		if($this->legacy_db->affected_rows() ==1){
			$this->log_model->create_log($application_id,0,$prev_record,$record,"($where) FROM update METHOD",$table,27,$user_id);
			$this->legacy_db->trans_commit();
			return true;
		}else{
			$this->legacy_db->trans_rollback();
			return false;
		}
	}//function
	
	//this method is created for printable report to hand over to the verification team for the verification of the record.
	
	function get_form_verification_list($admission_session_id,$status_id)
	{
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->select("a.APPLICATION_ID AS APPLICATION_ID,a.USER_ID,ass.ADMISSION_SESSION_ID,a.STATUS_ID,a.IS_SUBMITTED,a.FORM_DATA,ass.CAMPUS_ID,ass.SESSION_ID,ass.PROGRAM_TYPE_ID,ss.YEAR,ss.BATCH_REMARKS,c.NAME,pt.PROGRAM_TITLE,aps.STATUS_NAME,a.REMARKS,
		TRIM(BOTH '\"' FROM JSON_EXTRACT (FORM_DATA,'$.users_reg.DISTRICT_ID')) AS `DISTRICT_ID`");
		$this->legacy_db->from('applications a');
		$this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
		$this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
		$this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
		$this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
		$this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID` AND aps.STATUS_ID=$status_id","INNER");
		$this->legacy_db->where("ass.ADMISSION_SESSION_ID",$admission_session_id);
		$this->legacy_db->order_by("DISTRICT_ID");
		$this->legacy_db->order_by("APPLICATION_ID");
// 		$this->legacy_db->limit(30);
		return $this->legacy_db->get()->result_array();
	}//method

//this method add on 31-dec-2020 by kashif shaikh
    function get_single_Application_for_verification_by_district_id($admission_session_id,$status_id,$district_id=0)
	{
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->select("*,ur.USER_ID,a.APPLICATION_ID,a.REMARKS,ur.DISTRICT_ID AS `DISTRICT_ID`");
		$this->legacy_db->from('applications a');
		$this->legacy_db->join("`users_reg` ur","ur.`USER_ID` = a.USER_ID");
		$this->legacy_db->join("`admission_session` ass","ass.`ADMISSION_SESSION_ID` = a.`ADMISSION_SESSION_ID`");
		$this->legacy_db->join("`sessions` ss","ass.`SESSION_ID` = ss.`SESSION_ID`");
		$this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`");
		$this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`");
		$this->legacy_db->join("`application_status` aps","aps.`STATUS_ID` = a.`STATUS_ID` AND aps.STATUS_ID=$status_id","INNER");
		$this->legacy_db->join("`test_result` tr","a.`APPLICATION_ID` = tr.`APPLICATION_ID`","LEFT OUTER");
		$this->legacy_db->where("ass.ADMISSION_SESSION_ID",$admission_session_id);
		
		if($district_id>0){
		$this->legacy_db->where("ur.DISTRICT_ID = $district_id");    
		}else if($district_id==-1){
		  $this->legacy_db->where("TRIM(BOTH '\"' FROM JSON_EXTRACT (FORM_DATA,'$.users_reg.PROVINCE_ID')) != 6"); 
		}
	   	$this->legacy_db->order_by("tr.CPN DESC");
		$this->legacy_db->limit(700);
		$res  = $this->legacy_db->get()->result_array();
		// echo $this->legacy_db->last_query();
	   /// exit();
		return $res;
	}
	
		/*
	 * yasir added following 31-12-2020
	 * */
	function countAdminVerifiedApplication($user_id){
	    
	    
		$this->legacy_db = $this->load->database("admission_db",true);
		$cond =(isset($_GET['year'])&&is_numeric($_GET['year']))?"se.YEAR = '".isValidData($_GET['year'])."' AND":"";
		$cond .=(isset($_GET['program_type'])&&is_numeric($_GET['program_type']))?" ass.PROGRAM_TYPE_ID = '".isValidData($_GET['program_type'])."' AND":"";
	   $sql = "SELECT COUNT(DISTINCT l.PREV_ID) AS TOTAL_COUNT,l.USER_ID  FROM `log` l join applications ap on (l.PREV_ID = ap.APPLICATION_ID) join admission_session ass on (ap.ADMISSION_SESSION_ID = ass.ADMISSION_SESSION_ID) join sessions se on (ass.SESSION_ID = se.SESSION_ID) WHERE $cond l.`OPERATION_CODE` = 27  AND l.USER_ID = $user_id";
		//echo($sql);
		//exit();
		//echo "<br>";
		$query = $this->legacy_db->query($sql);
		return $query->row_array();
		//		SELECT COUNT(DISTINCT PREV_ID) AS TOTAL_COUNT,USER_ID  FROM `log` WHERE `OPERATION_CODE` = 27 AND USER_ID = 158729
	}
	
	function getApplicationVerifierData($application_id){
		$this->legacy_db = $this->load->database("admission_db",true);
		$query = $this->legacy_db->query("SELECT *  FROM `log` WHERE `OPERATION_CODE` = 27 AND PREV_ID=$application_id");
//		echo $this->legacy_db->last_query();
		$records =  $query->result_array();
		if (is_array($records) || is_object($records)):
			$new_array = array();
		foreach ($records as $record):
		$verifier_id = $record['USER_ID'];
		$user_data = $this->User_model->getUserById($verifier_id);
			$record['VERIFIER_PROFILE']=$user_data;
		array_push($new_array,$record);
		endforeach;
			return $new_array;
		endif;
		return null;
	}
	function addUpdateHardCopyDocumentSubmission($form_array,$admin_id=0){
	        $application_id = $form_array['APPLICATION_ID'];
	        
	        $this->legacy_db = $this->load->database("admission_db",true);
	    	$this->legacy_db->select("*");
		    $this->legacy_db->from("hardcopy_submitted_forms hsf");
		    $this->legacy_db->where('APPLICATION_ID',$application_id);
	    	$res  = $this->legacy_db->get()->row_array();
	    	
	    	$this->legacy_db->trans_begin();
	    if($res){
    		$this->legacy_db->where('APPLICATION_ID',$application_id);
    		$this->legacy_db->update("hardcopy_submitted_forms",$form_array);
    		if($this->legacy_db->affected_rows() ==1){
    			$this->log_model->create_log($application_id,0,"",json_encode($form_array),"SUCCESSFULLY UPDATE","hardcopy_submitted_forms",27,$admin_id);
    			$this->legacy_db->trans_commit();
    			return true;
    		}else{
    			$this->legacy_db->trans_rollback();
    			return false;
    		}
	    }else{
	        if($this->legacy_db->insert('hardcopy_submitted_forms',$form_array)){
	            $this->log_model->create_log($application_id,0,"",json_encode($form_array),"SUCCESSFULLY INSERT","hardcopy_submitted_forms",27,$admin_id);
    			$this->legacy_db->trans_commit();
    			return true;
	        }else{
	           	$this->legacy_db->trans_rollback();
    			return false; 
	        }	    
	    }
	}
		function UpdateVerifiedChallan($list_of_challan_no,$verifier_id){
		
		      
	        $this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->trans_begin();
		
		foreach($list_of_challan_no as $challan){
			if(isset($challan['FORM_CHALLAN_ID'])){
				$this->legacy_db->where("FORM_CHALLAN_ID",$challan['FORM_CHALLAN_ID']);
				
				$c_date = date('Y-m-d');
				$rec = array
				(
					'REMARKS'=>"AUTO_CMD",
					'VERIFIER_ID'=>$verifier_id,
					'IS_VERIFIED'=>"Y",
					'VERIFICATION_DATE'=>$c_date
				);	
				
				
				$this->legacy_db->update("form_challan",$rec);
				if($this->legacy_db->affected_rows() ==1||$this->legacy_db->affected_rows() ==0){
					
				}else{
					$this->legacy_db->trans_rollback();
				    $rec["FORM_CHALLAN_ID"]= $challan['FORM_CHALLAN_ID'];  
					return array("RETURN"=>0,"RECORD"=>$rec);
				}					
			}else{
				$this->legacy_db->trans_rollback();
				return array("RETURN"=>0,"RECORD"=>$challan);
			}
		}
		
		$this->legacy_db->trans_commit();
		return  array("RETURN"=>1,"RECORD"=>"");
		
	}//function
	
}
