<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 09/19/2022
 * Time: 11:52 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class EnrolmentCard_model extends CI_Model{


	public function get_application ($roll_no){
		$query="SELECT 
					  s.`SESSION_ID`,
					  s.`YEAR`,
					  s.`SESSION_CODE`,
					  c.`CAMPUS_ID`,
					  c.`NAME` AS CAMPUS_NAME,
					  `LOCATION`,
					  pt.`PROGRAM_TYPE_ID`,
					  pt.`PROGRAM_TITLE` AS PROGRAM_TYPE_TITLE,
					  adms.`ADMISSION_SESSION_ID`,
					  app.`APPLICATION_ID`,
					  app.`STATUS_ID`,
					  reg.`CNIC_NO`,
					  reg.`EMAIL`,
       				reg.USER_ID,
					  reg.`FIRST_NAME`,
					  reg.`FNAME`,
					  reg.`LAST_NAME`,
					  reg.`GENDER`,
					  reg.`MOBILE_NO`,
       				reg.MOBILE_CODE,
       				reg.PROFILE_IMAGE,
					  reg.`DATE_OF_BIRTH`,
					  sl.`ROLL_NO`,
					  pl.`PROGRAM_TITLE`,
					  shft.`SHIFT_NAME`,
       					sl.SELECTION_LIST_ID,
       					spm.DEPT_NAME
					FROM
					  `sessions` s 
					  JOIN `admission_session` adms 
						ON (
						  s.`SESSION_ID` = adms.`SESSION_ID`
						) 
					  JOIN `campus` c 
						ON (c.`CAMPUS_ID` = adms.`CAMPUS_ID`) 
					  JOIN `program_type` pt 
						ON (
						  pt.`PROGRAM_TYPE_ID` = adms.`PROGRAM_TYPE_ID`
						) 
					  JOIN `applications` app 
						ON (
						  adms.`ADMISSION_SESSION_ID` = app.`ADMISSION_SESSION_ID` 
						  AND app.`IS_DELETED` = 'N' 
						  AND app.`IS_SUBMITTED` = 'Y' 
						  AND app.`STATUS_ID` = 10
						) 
					  JOIN users_reg reg 
						ON (reg.USER_ID = app.`USER_ID`) 
					  JOIN selection_list sl 
						ON (
						  app.`APPLICATION_ID` = sl.`APPLICATION_ID` 
						  AND sl.`IS_ENROLLED` = 'Y'
						) 
					  JOIN program_list pl 
						ON (
						  pl.`PROG_LIST_ID` = sl.`PROG_LIST_ID`
						) 
					  JOIN shift shft 
						ON (shft.`SHIFT_ID` = sl.`SHIFT_ID`)
					  JOIN shift_program_mapping spm 
						ON (shft.SHIFT_ID=spm.SHIFT_ID AND c.CAMPUS_ID=spm.CAMPUS_ID AND pl.PROG_LIST_ID=spm.PROG_LIST_ID)
					WHERE sl.`ROLL_NO` = '".$roll_no."'";

		$this->legacy_db = $this->load->database('admission_db',true);
		$q = $this->legacy_db->query($query);
		$result = $q->row_array();
		return $result;
	}

	public function get_qualification($application_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('o.INSTITUTE_ID AS BOARD_ID, q.TOTAL_MARKS,q.OBTAINED_MARKS,q.PASSING_YEAR,q.ROLL_NO,d.DEGREE_ID,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME AS INSTITUTE,o.INSTITUTE_NAME AS ORGANIZATION');
		$this->legacy_db->from('qualifications q');
		$this->legacy_db->join('institute AS i', 'q.INSTITUTE_ID = i.INSTITUTE_ID','LEFT');
		$this->legacy_db->join('institute AS o', 'q.ORGANIZATION_ID = o.INSTITUTE_ID');
		$this->legacy_db->join('discipline AS d', 'q.DISCIPLINE_ID = d.DISCIPLINE_ID');
		$this->legacy_db->join('degree_program AS p', 'd.DEGREE_ID = p.DEGREE_ID');
		$this->legacy_db->where('q.ACTIVE',1);
		$this->legacy_db->where('q.APPLICATION_ID',$application_id);
		$this->legacy_db->order_by('p.DEGREE_ID', 'DESC');
		$qulification_list = $this->legacy_db->get()->result_array();

		return $qulification_list;
	}

	public function get_enrolment_card($application_id,$selection_list_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->from('enrolment_card');
		$this->legacy_db->where('APPLICATION_ID',$application_id);
		$this->legacy_db->where('SELECTION_LIST_ID',$selection_list_id);
		$enrolment = $this->legacy_db->get()->row_array();
		return $enrolment;
	}

	function get_first_paid_challan ($application_id,$part_id,$semester_id){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('fl.DATE AS PAID_DATE,p.NAME AS PART_NAME');
		$this->legacy_db->from ('candidate_account ca');
		$this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
		$this->legacy_db->join('fee_program_list fpl','fl.FEE_PROG_LIST_ID=fpl.FEE_PROG_LIST_ID');
		$this->legacy_db->join('part p','p.PART_ID=fpl.PART_ID');
		$this->legacy_db->where('fl.CHALLAN_TYPE_ID',1);
		$this->legacy_db->where('fl.IS_YES','Y');
		$this->legacy_db->where('ca.ACTIVE',1);
		$this->legacy_db->where_in('fpl.PART_ID',$part_id);
		$this->legacy_db->where_in('fpl.SEMESTER_ID',$semester_id);
		if($application_id>0)$this->legacy_db->where ('ca.APPLICATION_ID',$application_id);
		$row = $this->legacy_db->get()->row_array();
		if ($row){
			return $row;
		}else{
			return false;
		}
	}//method

	public function get_eligibility_certificate($application_id,$selection_list_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->from('eligibility_certificate');
		$this->legacy_db->where('APPLICATION_ID',$application_id);
		$this->legacy_db->where('SELECTION_LIST_ID',$selection_list_id);
		$enrolment = $this->legacy_db->get()->row_array();
		return $enrolment;
	}

	public function get_certificate_log($application_id,$cert_type){

		switch ($cert_type){
			case 'ENROLLMENT':
				$this->legacy_db = $this->load->database('admission_db',true);
				$this->legacy_db->select("`ENROLMENT_CARD_ID`,sl.`APPLICATION_ID`,sl.`SELECTION_LIST_ID`,`ISSUE_DATE`,ec.`ACTIVE`,ec.`REMARKS`,ec.`CHALLAN_NO`,ec.`CHALLAN_DATE`,ec.`IS_REISSUED`,ec.`ISSUER_ID`,ec.`ISSUED_BY`,sl.ROLL_NO,pl.PROGRAM_TITLE,reg.FIRST_NAME AS ISSUER_NAME,'ENROLLMENT' AS CERT_TYPE");
				$this->legacy_db->from('enrolment_card ec');
				$this->legacy_db->join('selection_list sl','ec.APPLICATION_ID=sl.APPLICATION_ID AND ec.SELECTION_LIST_ID=sl.SELECTION_LIST_ID');
				$this->legacy_db->join('program_list pl','pl.PROG_LIST_ID=sl.PROG_LIST_ID');
				$this->legacy_db->join('users_reg reg','reg.USER_ID=ec.ISSUER_ID','LEFT');
				$this->legacy_db->where('sl.APPLICATION_ID',$application_id);
				$enrolments = $this->legacy_db->get()->result_array();
				return $enrolments;

			case 'ELIGIBILITY':
				$this->legacy_db = $this->load->database('admission_db',true);
				$this->legacy_db->select("`ELIGIBILITY_CERTIFICATE_ID`,sl.`APPLICATION_ID`,sl.`SELECTION_LIST_ID`,`ISSUE_DATE`,ec.`ACTIVE`,ec.`REMARKS`,ec.`CHALLAN_NO`,ec.`CHALLAN_DATE`,ec.`IS_REISSUED`,ec.`ISSUER_ID`,ec.`ISSUED_BY`,sl.ROLL_NO,pl.PROGRAM_TITLE,reg.FIRST_NAME AS ISSUER_NAME,'ELIGIBILITY' AS CERT_TYPE");
				$this->legacy_db->from('eligibility_certificate ec');
				$this->legacy_db->join('selection_list sl','ec.APPLICATION_ID=sl.APPLICATION_ID AND ec.SELECTION_LIST_ID=sl.SELECTION_LIST_ID');
				$this->legacy_db->join('program_list pl','pl.PROG_LIST_ID=sl.PROG_LIST_ID');
				$this->legacy_db->join('users_reg reg','reg.USER_ID=ec.ISSUER_ID','LEFT');
				$this->legacy_db->where('sl.APPLICATION_ID',$application_id);
				$eligibilities = $this->legacy_db->get()->result_array();
				return $eligibilities;
		}
	}

	public function get_challan_log ($application_id){
		$query="SELECT 
					  s.`SESSION_ID`,
					  s.`YEAR`,
					  s.`SESSION_CODE`,
					  c.`CAMPUS_ID`,
					  c.`NAME` AS CAMPUS_NAME,
					  `LOCATION`,
					  pt.`PROGRAM_TYPE_ID`,
					  pt.`PROGRAM_TITLE` AS PROGRAM_TYPE_TITLE,
					  adms.`ADMISSION_SESSION_ID`,
					  app.`APPLICATION_ID`,
					  app.`STATUS_ID`,
					  reg.`CNIC_NO`,
					  reg.`EMAIL`,
       				  reg.USER_ID,
					  reg.`FIRST_NAME`,
					  reg.`FNAME`,
					  reg.`LAST_NAME`,
					  reg.`GENDER`,
					  reg.`MOBILE_NO`,
       				  reg.MOBILE_CODE,
       				  reg.PROFILE_IMAGE,
					  reg.`DATE_OF_BIRTH`,
					  sl.`ROLL_NO`,
					  pl.`PROGRAM_TITLE`,
					  shft.`SHIFT_NAME`,
       				  sl.SELECTION_LIST_ID,
       				  spm.DEPT_NAME,
       				  CONCAT(gbc.SECTION_ACCOUNT_ID,LPAD(gbc.CHALLAN_NO,7,'0')) AS CHALLAN_NO,
       				  gbc.CHALLAN_AMOUNT,
       				  gbc.DUE_DATE,
       				  gbc.CHALLAN_DATE,
       				  gbc.TYPE_CODE
					FROM
					  `sessions` s 
					  JOIN `admission_session` adms 
						ON (
						  s.`SESSION_ID` = adms.`SESSION_ID`
						) 
					  JOIN `campus` c 
						ON (c.`CAMPUS_ID` = adms.`CAMPUS_ID`) 
					  JOIN `program_type` pt 
						ON (
						  pt.`PROGRAM_TYPE_ID` = adms.`PROGRAM_TYPE_ID`
						) 
					  JOIN `applications` app 
						ON (
						  adms.`ADMISSION_SESSION_ID` = app.`ADMISSION_SESSION_ID` 
						  AND app.`IS_DELETED` = 'N' 
						  AND app.`IS_SUBMITTED` = 'Y' 
						  AND app.`STATUS_ID` = 10
						) 
					  JOIN users_reg reg 
						ON (reg.USER_ID = app.`USER_ID`) 
					  JOIN selection_list sl 
						ON (
						  app.`APPLICATION_ID` = sl.`APPLICATION_ID` 
						  AND sl.`IS_ENROLLED` = 'Y'
						) 
					  JOIN program_list pl 
						ON (
						  pl.`PROG_LIST_ID` = sl.`PROG_LIST_ID`
						) 
					  JOIN shift shft 
						ON (shft.`SHIFT_ID` = sl.`SHIFT_ID`)
					  JOIN shift_program_mapping spm 
						ON (shft.SHIFT_ID=spm.SHIFT_ID AND c.CAMPUS_ID=spm.CAMPUS_ID AND pl.PROG_LIST_ID=spm.PROG_LIST_ID)
						JOIN general_branch_challan gbc 
						    ON (gbc.APPLICATION_ID=app.APPLICATION_ID AND gbc.SELECTION_LIST_ID=sl.SELECTION_LIST_ID)
					WHERE app.`APPLICATION_ID` = $application_id";
//			exit($query);
		$this->legacy_db = $this->load->database('admission_db',true);
		$q = $this->legacy_db->query($query);
		$result = $q->result_array();
		return $result;
	}


}
