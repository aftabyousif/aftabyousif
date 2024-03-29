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
		$this->legacy_db->order_by("pl.`PROGRAM_TITLE`");
		return($this->legacy_db->get()->result_array());
	}
    function getPrerequisiteByMinorMappingIdList ($minor_mapping_id)
    {
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('*');
        $this->legacy_db->from('`prerequisite`');


        //$minor_mapping_id = array('5', '6');
        $this->legacy_db->or_where_in('MINOR_MAPPING_ID', $minor_mapping_id);
        $result = $this->legacy_db->get()->result_array();

        return($result);
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
	
	/*
 * following new method added yasir mehboob on 15-10-2020
 * */
	function insert_batch($data,$table)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		if ($this->legacy_db->insert_batch($table, $data))
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT_BATCH METHOD',$table,11,0);
			return true;
		}else return $this->legacy_db->error();
	}//method
	/*
	 * END
	 * */
	  //add method after 15-dec-2020 By KASHIF SHAIKH
	    function getPrerequisiteByProgramTypeId($program_type_id){
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('*');
        $this->legacy_db->from('`prerequisite` as pre');
        $this->legacy_db->join('`program_list` as pl',"pl.PROG_LIST_ID = pre.PROG_LIST_ID");


        //$minor_mapping_id = array('5', '6');
        $this->legacy_db->or_where_in('pl.PROGRAM_TYPE_ID', $program_type_id);
         $this->legacy_db->where('pre.IS_ENABLE','Y');
        $result = $this->legacy_db->get()->result_array();

        return($result);
    }

}
