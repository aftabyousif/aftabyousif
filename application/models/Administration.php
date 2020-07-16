<?php

class Administration extends CI_Model
{
	function __construct()
	{
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
	}

	function programs ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('PROG_LIST_ID, PROGRAM_TITLE, REMARKS');
		return $this->legacy_db->get('program_list')->result_array();
	}

	function shifts ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('`SHIFT_ID`, `SHIFT_NAME`, `REMARKS`');
		return $this->legacy_db->get('shift')->result_array();
	}

	function insert($data,$table)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		if ($this->legacy_db->insert($table, $data))
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT METHOD',$table,11,0);
			return true;
		}else return false;
	}//method

	function insert_batch($data,$table)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		if ($this->legacy_db->insert_batch($table, $data))
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT_BATCH METHOD',$table,11,0);
			return true;
		}else return $this->legacy_db->error();
	}//method

	function getMappedPrograms ($shift_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('p.PROG_LIST_ID AS PROG_ID, PROGRAM_TITLE,pm.REMARKS AS REMARKS,s.SHIFT_ID AS SHIFT_ID, SHIFT_NAME');
		$this->legacy_db->from('program_list p');
		$this->legacy_db->join('shift_program_mapping pm','p.PROG_LIST_ID=pm.PROG_LIST_ID','INNER');
		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID','INNER');

		if ($shift_id>0)	$this->legacy_db->where("s.SHIFT_ID IN ({$shift_id})");

		return $this->legacy_db->get()->result_array();
	}
	function ignoreMappedPrograms ($shift_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('p.PROG_LIST_ID AS PROG_ID,p.PROGRAM_TITLE AS PROGRAM_TITLE');
		$this->legacy_db->from('program_list p');
		$this->legacy_db->join('shift_program_mapping pm',"(p.PROG_LIST_ID=pm.PROG_LIST_ID AND pm.SHIFT_ID=$shift_id)",'LEFT');
//		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID ','INNER');
		$this->legacy_db->where("pm.PROG_LIST_ID IS NULL");

		return($this->legacy_db->get()->result_array());
	}
	function DeleteMappedPrograms_model($shift_id,$prog_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("SHIFT_ID=$shift_id AND PROG_LIST_ID=$prog_id");
		$query = $this->legacy_db->delete('shift_program_mapping');
		if ($query)
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),array('PROG_LIST_ID'=>$prog_id,'SHIFT_ID'=>$shift_id),'','storing prog_list_id and shift_id','shift_program_mapping',13,0);
			return true;
		}
		else return false;
	}
}
