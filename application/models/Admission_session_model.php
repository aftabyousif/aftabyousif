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
			$this->legacy_db->order_by("ass.`PROGRAM_TYPE_ID`",'ASC');
			return $this->legacy_db->get()->result_array();

		}
		function getFormFeesBySessionAndCampusId($session_id,$campus_id){
            $this->legacy_db = $this->load->database("admission_db",true);
            $this->legacy_db->from('form_fees ff');
            $this->legacy_db->join('bank_account AS ba', 'ba.BANK_ACCOUNT_ID = ff.BANK_ACCOUNT_ID');
            $this->legacy_db->join('campus AS c', 'c.CAMPUS_ID = ff.CAMPUS_ID');
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
		return $this->legacy_db->order_by('YEAR','DESC')->get('sessions')->result_array();
	}
	function getAdmissionSession(){
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->select("ass.*,c.NAME,s.YEAR,pt.PROGRAM_TITLE");
		$this->legacy_db->from('admission_session ass');
		$this->legacy_db->join('sessions AS s', 's.SESSION_ID = ass.SESSION_ID');
		$this->legacy_db->join('campus AS c', 'c.CAMPUS_ID = ass.CAMPUS_ID');
		$this->legacy_db->join('program_type AS pt', 'pt.PROGRAM_TYPE_ID= ass.PROGRAM_TYPE_ID');
	    $this->legacy_db->order_by('ADMISSION_SESSION_ID','DESC');
		return $this->legacy_db->get()->result_array();
	}
	
			// new created yasir on 05-10-2020

	function getAdmissionSessionID($session_id,$campus_id,$program_type_id){
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->from('admission_session ass');
		$this->legacy_db->join('sessions AS s', 's.SESSION_ID = ass.SESSION_ID');
		$this->legacy_db->join('campus AS c', 'c.CAMPUS_ID = ass.CAMPUS_ID');
		$this->legacy_db->join('program_type AS pt', 'pt.PROGRAM_TYPE_ID= ass.PROGRAM_TYPE_ID');
		$this->legacy_db->where('ass.SESSION_ID',$session_id);
		$this->legacy_db->where('ass.CAMPUS_ID',$campus_id);
		$this->legacy_db->where('ass.PROGRAM_TYPE_ID',$program_type_id);
		return $this->legacy_db->get()->row_array();
	}
	
			// new created yasir on 23-12-2020

	function getShiftData(){
		$this->legacy_db = $this->load->database("admission_db",true);
		return $this->legacy_db->get('shift')->result_array();
	}
	// new created kashif on 7-jan-2021
    function getSessionByYearData($year){
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where('YEAR',$year);
        return $this->legacy_db->get('sessions')->row_array();
    }
    // new created kashif on 31-jan-2021
    function getSessionByID($session_id){
        $this->legacy_db = $this->load->database("admission_db",true);
        $this->legacy_db->where('SESSION_ID',$session_id);
        return $this->legacy_db->get('sessions')->row_array();
    }
    function addSession($array){
         $this->legacy_db = $this->load->database("admission_db",true);
         $year = $array['YEAR'];
          $this->legacy_db->where('YEAR',$year);
        $data =  $this->legacy_db->get('year')->row_array();
        if(!$data){
            $year_array = array("YEAR"=>$year,"YEAR_ENCODE"=>$array['SESSION_CODE']);
            $this->legacy_db->insert('year', $year_array);
        }
         return $this->legacy_db->insert('sessions', $array);
    }
    function updateSession($id,$array){
         $this->legacy_db = $this->load->database("admission_db",true);
         $year = $array['YEAR'];
          $this->legacy_db->where('YEAR',$year);
        $data =  $this->legacy_db->get('year')->row_array();
        if(!$data){
            $year_array = array("YEAR"=>$year,"YEAR_ENCODE"=>$array['SESSION_CODE']);
            $this->legacy_db->insert('year', $year_array);
        }
        $this->legacy_db->where("SESSION_ID",$id);
         return $this->legacy_db->update('sessions', $array);
    }
}
