<?php


class Prerequisite_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
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
	function getPrerequisite_Prerequisite_id ($prerequisite_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`prerequisite`');
		$this->legacy_db->where("PREREQUISITE_ID=$prerequisite_id");
		return($this->legacy_db->get()->result_array());
	}

	function getPrerequisite_minor_mapping_id ($minor_mapping_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('pre.`PREREQUISITE_ID`,pre.`REMARKS`,pl.`PROG_LIST_ID`, pl.`PROGRAM_TITLE`,pl.`PRE_REQ_PER`,mp.`MINOR_MAPPING_ID`, mp.`DISCIPLINE_ID`, mp.`SUBJECT_TITLE`');
		$this->legacy_db->from('`prerequisite` pre');
		$this->legacy_db->join('`minor_mapping` mp',"mp.MINOR_MAPPING_ID=pre.MINOR_MAPPING_ID",'INNER');
		$this->legacy_db->join('`program_list` pl',"pl.PROG_LIST_ID=pre.PROG_LIST_ID",'INNER');
		$this->legacy_db->where("mp.MINOR_MAPPING_ID=$minor_mapping_id");
		return($this->legacy_db->get()->result_array());
	}

	function DeletePrerequisite($prerequisite_id)
	{
		$prev_record = $this->getPrerequisite_Prerequisite_id($prerequisite_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("PREREQUISITE_ID",$prerequisite_id);
		$query = $this->legacy_db->delete('prerequisite');
		if ($query)
		{
			$this->log_model->create_log($prerequisite_id,$this->legacy_db->insert_id(),$prev_record,'','storing prerequisite id','prerequisite',13,0);
			return true;
		}
		else return false;
	}

}
