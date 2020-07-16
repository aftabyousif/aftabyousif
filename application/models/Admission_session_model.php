<?php


class Admission_session_model extends CI_Model
{
		function __construct()
		{
			parent::__construct();
	//		$CI =& get_instance();
			$this->load->model('log_model');
		}//function

		function get_form_admission_session ()
		{
			$this->legacy_db = $this->load->database("admission_db",true);
			$this->legacy_db->select("`ADMISSION_SESSION_ID`,
									  c.`CAMPUS_ID`,
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
}
