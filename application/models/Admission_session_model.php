<?php


class Admission_session_model extends CI_Model
{
		function __construct()
		{
			parent::__construct();
			$this->load->model('log_model');
		}//function

		function get_form_admission_session ()
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
			$this->legacy_db->join("`admission_session` ass","s.`SESSION_ID` = ass.`SESSION_ID`",'INNER');
			$this->legacy_db->join("`campus` c","ass.`CAMPUS_ID` = c.`CAMPUS_ID`",'INNER');
			$this->legacy_db->join("`program_type` pt","ass.`PROGRAM_TYPE_ID` = pt.`PROGRAM_TYPE_ID`",'INNER');
			$this->legacy_db->where("ass.`DISPLAY` = 1");
			$this->legacy_db->order_by("c.DISPLAY_ORDER",'ASC');
			return $this->legacy_db->get()->result_array();

		}
		function getFormFeesBySessionAndCampusId($session_id,$campus_id){
            $this->legacy_db = $this->load->database("admission_db",true);
            $this->legacy_db->from('form_fees ff');
            $this->legacy_db->join('bank_account AS ba', 'ba.BANK_ACCOUNT_ID = ff.ACCOUNT_ID');
            $this->legacy_db->where('ff.SESSION_ID',$session_id);
            $this->legacy_db->where('ff.CAMPUS_ID',$campus_id);
            return $this->legacy_db->get()->row_array();
        }
		function getAdmissionSessionById($admission_session_id){
                $this->legacy_db = $this->load->database("admission_db",true);
                $this->legacy_db->where('ADMISSION_SESSION_ID',$admission_session_id);
                return $this->legacy_db->get('admission_session')->row_array();
		}
		function getAllBankInformation(){
            $this->legacy_db = $this->load->database("admission_db",true);
            return $this->legacy_db->get('bank_information')->result_array();
        }


        function getBankInformationByBranchId($branch_id){
            $this->legacy_db = $this->load->database("admission_db",true);
            $this->legacy_db->where('BRANCH_ID',$branch_id);
            return $this->legacy_db->get('bank_information')->row_array();
        }


	function getSessionData(){
		$this->legacy_db = $this->load->database("admission_db",true);
		return $this->legacy_db->get('sessions')->result_array();
	}


}
