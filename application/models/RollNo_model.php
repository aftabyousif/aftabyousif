<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 08/28/2021
 * Time: 04:38 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class RollNo_model extends CI_Model{

	function __construct(){
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
	}

	public function start_end_rollnos ($admission_session_id,$shift_id,$program_ids){
		$this->legacy_db = $this->load->database('admission_db',true);
		  $this->legacy_db->db_debug = false;
		$this->legacy_db->select("MIN(`ROLL_NO_CODE`) AS START_ROLL_NO,MAX(`ROLL_NO_CODE`) AS END_ROLL_NO,PROG_LIST_ID,SHIFT_ID,ADMISSION_SESSION_ID,`PROGRAM_TITLE`")
			->from('selection_list')
			->where('ADMISSION_SESSION_ID',$admission_session_id)
			->where('SHIFT_ID',$shift_id)
//			->where('ROLL_NO_CODE !=0')
			->where("PROG_LIST_ID IN ($program_ids)")
			->group_by("ADMISSION_SESSION_ID,SHIFT_ID,PROG_LIST_ID,`PROGRAM_TITLE`");
			
		$result_array =	$this->legacy_db->get()->result_array();
//  		prePrint($this->legacy_db->last_query());
//  		exit();
		return $result_array;
	}//method

	public function get_candidates($admission_session_id,$shift_id,$program_ids){

		$this->legacy_db= $this->load->database('admission_db',true);
		$result_array 	= $this->legacy_db->distinct()->select("sl.SELECTION_LIST_ID AS SELECTION_LIST_ID,ca.ACCOUNT_ID AS ACCOUNT_ID, ca.APPLICATION_ID AS APPLICATION_ID,CAMPUS_NAME,PROGRAM_TITLE, ca.FIRST_NAME AS FIRST_NAME,ca.FNAME AS FNAME, ca.LAST_NAME AS LAST_NAME,ROLL_NO_CODE,sl.PROG_LIST_ID AS PROG_LIST_ID")
			->from('candidate_account ca')
			->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID AND ca.ACTIVE=1')
			->join('selection_list sl','sl.SELECTION_LIST_ID=fl.SELECTION_LIST_ID')
			->where('sl.ADMISSION_SESSION_ID',$admission_session_id)
			->where('sl.SHIFT_ID',$shift_id)
			->where("sl.PROG_LIST_ID IN ($program_ids)")
			->where("fl.IS_YES",'Y')
			->where("sl.IS_PROVISIONAL",'N')
			->where("sl.ACTIVE",'1')
			->where("fl.CHALLAN_TYPE_ID",'1')
			->order_by('ca.FIRST_NAME,sl.PROG_LIST_ID')
			->get()->result_array();
			return $result_array;
	}

	public function get_candidate_roll_no_report($admission_session_id,$shift_id,$program_ids,$part_id){

		$this->legacy_db= $this->load->database('admission_db',true);
		$this->legacy_db->db_debug = true;
		$result_array 	= $this->legacy_db->select("sl.CARD_ID,sl.ADMISSION_SESSION_ID,
                  sl.SELECTION_LIST_ID AS SELECTION_LIST_ID,
                  ca.ACCOUNT_ID AS ACCOUNT_ID,
                  ca.APPLICATION_ID AS APPLICATION_ID,
                  c.NAME AS CAMPUS_NAME,
                  pt.PROGRAM_TITLE,
                  pl.PROGRAM_TITLE AS PROG_TITLE,
                  ca.FIRST_NAME AS FIRST_NAME,
                  ca.FNAME AS FNAME,
                  ca.LAST_NAME AS LAST_NAME,
                  d.DISTRICT_NAME AS DISTRICT_NAME,
                  ur.U_R,
                  sl.PROG_LIST_ID AS PROG_LIST_ID,
                  ROLL_NO_CODE,
                  PROG_CODE,
                  SESSION_CODE,
                  CONCAT(SESSION_CODE,'/',PROG_CODE,'/',ROLL_NO_CODE) AS ROLL_NO,
                  cat.CATEGORY_NAME,
                  cat.CATEGORY_ID,
                  SUM(fl.PAID_AMOUNT) AS PAID_AMOUNT,
                  s.YEAR AS YEAR,
                  p.NAME AS PART_NAME,
                  sl.SHIFT_ID AS SHIFT_ID,
                  a.USER_ID,
                  p.PART_ID,
                  s.SESSION_ID,
                  pt.PROGRAM_TYPE_ID,
                  c.CAMPUS_ID,
                  ur.MOBILE_NO,
                  ur.CNIC_NO,
                  ur.EMAIL")
			->from('shift_program_mapping spm')
			->from(' campus c ')
			->join('admission_session adms ','(c.`CAMPUS_ID` = adms.`CAMPUS_ID`)')
			->join('sessions s ','(s.`SESSION_ID`= adms.`SESSION_ID`)')
			->join('program_type pt','(pt.PROGRAM_TYPE_ID = adms.PROGRAM_TYPE_ID)')
			->join('applications a','(adms.ADMISSION_SESSION_ID=a.ADMISSION_SESSION_ID)','LEFT')
			->join('users_reg ur','(ur.USER_ID = a.USER_ID)')
			->join('districts d','(d.DISTRICT_ID = ur.DISTRICT_ID)')
			->join('`selection_list` `sl`','(adms.`ADMISSION_SESSION_ID` = sl.`ADMISSION_SESSION_ID` AND a.APPLICATION_ID=sl.APPLICATION_ID)')
			->join('category cat','(cat.CATEGORY_ID = sl.CATEGORY_ID)')
			->join('`candidate_account` `ca` ','(sl.`APPLICATION_ID` = ca.`APPLICATION_ID` AND `ca`.`ACTIVE` = 1)')
			->join('`fee_ledger` `fl` ',"(ca.`ACCOUNT_ID` = fl.`ACCOUNT_ID` AND fl.`IS_YES` = 'Y' AND fl.`SELECTION_LIST_ID` = sl.`SELECTION_LIST_ID`)")
			->join('part p',"(adms.`PROGRAM_TYPE_ID` = p.`PROGRAM_TYPE_ID`)")
			->join('fee_program_list fpl  ',"(sl.`PROG_LIST_ID` = fpl.PROG_LIST_ID AND p.`PART_ID` = fpl.PART_ID AND sl.`SHIFT_ID` = fpl.SHIFT_ID AND c.`CAMPUS_ID` = fpl.CAMPUS_ID AND fpl.FEE_PROG_LIST_ID=fl.`FEE_PROG_LIST_ID`)")
			->join('program_list pl','(pl.PROG_LIST_ID = sl.PROG_LIST_ID)')
			->where("spm.`PROG_LIST_ID` = sl.PROG_LIST_ID")
			->where("spm.`SHIFT_ID` = sl.SHIFT_ID")
			->where("spm.`CAMPUS_ID` = c.`CAMPUS_ID`")
			->where('sl.ADMISSION_SESSION_ID',$admission_session_id)
			->where('sl.SHIFT_ID',$shift_id)
			->where("sl.PROG_LIST_ID IN ($program_ids)")
			->where("p.PART_ID",$part_id)
			->where("sl.IS_PROVISIONAL",'N')
			->where("sl.ACTIVE",'1')
			->where("fl.CHALLAN_TYPE_ID",'1')
			->group_by("ca.`ACCOUNT_ID`, `sl`.`CARD_ID`, `sl`.`SELECTION_LIST_ID`")
			->order_by('c.CAMPUS_ID,PROG_LIST_ID,ROLL_NO_CODE')
			->get()->result_array();
			//prePrint($this->legacy_db->last_query());
 			//exit; 
		return $result_array;
	}

	public function save_roll_nos($prog_records){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->trans_begin();
			$flag = false;
		foreach ($prog_records as $prog_record){
			$flag = false;
			foreach ($prog_record as $record){
				$ROLL_NO_CODE = $record['ROLL_NO_CODE'];
				$SELECTION_LIST_ID = $record['SELECTION_LIST_ID'];
				$new_array = array('ROLL_NO_CODE'=>$ROLL_NO_CODE);
				$this->legacy_db->where("SELECTION_LIST_ID",$SELECTION_LIST_ID);
				$this->legacy_db->update('selection_list',$new_array);
				if($this->legacy_db->affected_rows() ==1){
					$flag = true;
				}else{
					$flag = false;
					break;
				}
			}//foreach
			if ($flag == false) break;
		}

		if ($flag){
			$this->legacy_db->trans_commit();
			return 12;
		}else{
			$this->legacy_db->trans_rollback();
			return  02;
		}
	}
    
    public function get_student_examination($roll_no,$batch_id){

		$this->legacy_db=$this->load->database("admission_online",true);
		$this->legacy_db->select(" * ");
		$this->legacy_db->from('enrolment');
		if (!empty($roll_no)) $this->legacy_db->where('ROLL_NO',$roll_no);
		if ($batch_id>0) $this->legacy_db->where('BATCH_ID',$batch_id);
		return $this->legacy_db->get()->row_array();
	}
}
