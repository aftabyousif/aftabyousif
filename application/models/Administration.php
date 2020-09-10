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
    function getProgramByTypeID ($program_type_id)
    {
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('PROG_LIST_ID, PROGRAM_TITLE, REMARKS');
        $this->legacy_db->where('PROGRAM_TYPE_ID',$program_type_id);
        return $this->legacy_db->get('program_list')->result_array();
    }
	
	function getCategory ()
    {
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('FORM_CATEGORY_ID, FORM_CATEGORY_NAME, REMARKS');
        return $this->legacy_db->get('form_category')->result_array();
    }

	function programTypes ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('PROGRAM_TYPE_ID, PROGRAM_TITLE, REMARKS');
		return $this->legacy_db->get('program_type')->result_array();
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

	function update($where,$record,$prev_record,$table){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->trans_begin();

		$this->legacy_db->where($where);
		$this->legacy_db->update($table,$record);
		if($this->legacy_db->affected_rows() ==1){
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),$prev_record,$record,"($where) FROM update METHOD",$table,12,0);
			$this->legacy_db->trans_commit();
			return true;
		}else{
			$this->legacy_db->trans_rollback();
			return false;
		}
	}//function

	function insert_batch($data,$table)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		if ($this->legacy_db->insert_batch($table, $data))
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT_BATCH METHOD',$table,11,0);
			return true;
		}else return $this->legacy_db->error();
	}//method

	function getMappedPrograms ($shift_id,$program_type)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('p.PROG_LIST_ID AS PROG_ID, pt.PROGRAM_TITLE AS DEGREE_TITLE,p.PROGRAM_TITLE,pm.REMARKS AS REMARKS,s.SHIFT_ID AS SHIFT_ID, SHIFT_NAME');
		$this->legacy_db->from('program_list p');
		$this->legacy_db->join('shift_program_mapping pm','p.PROG_LIST_ID=pm.PROG_LIST_ID','INNER');
		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID','INNER');
		$this->legacy_db->join('program_type pt','pt.PROGRAM_TYPE_ID=pm.PROGRAM_TYPE_ID','INNER');

		if ($shift_id>0)		$this->legacy_db->where("s.SHIFT_ID IN ({$shift_id})");
		if ($program_type>0) 	$this->legacy_db->where("pt.PROGRAM_TYPE_ID IN ({$program_type})");

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

	function category_type ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		return $this->legacy_db->get('category_type')->result_array();
	}

	function MappedCategory ($category_type_id,$category_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('ct.`CATEGORY_TYPE_ID`,ct.`CATEGORY_NAME` AS CATEGORY_TYPE_NAME,ct.`CODE` AS CATEGORY_TYPE_CODE ,`DISPLAY`,c.`CATEGORY_ID`,c.`CATEGORY_NAME`,c.`P_CODE`,c.`CODE` AS CATEGORY_CODE,c.`REMARKS` AS CATEGORY_REMARKS');
		$this->legacy_db->from('`category_type` ct');
		$this->legacy_db->join('`category` c',"(ct.CATEGORY_TYPE_ID=c.CATEGORY_TYPE_ID)",'INNER');
//		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID ','INNER');
		if ($category_type_id>0)$this->legacy_db->where("ct.CATEGORY_TYPE_ID=$category_type_id");
		if ($category_id>0)$this->legacy_db->where("c.CATEGORY_ID",$category_id);
//		return($this->legacy_db->last_query());
		return($this->legacy_db->get()->result_array());
	}

	function DeleteMappedCategory($category_id)
	{
		$prev_record = $this->MappedCategory(0,$category_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("CATEGORY_ID",$category_id);
		$query = $this->legacy_db->delete('category');
		if ($query)
		{
			$this->log_model->create_log($category_id,$this->legacy_db->insert_id(),$prev_record,'','storing category id','category',13,0);
			return true;
		}
		else return false;
	}

	function MinorMapping ($minor_mapping_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`minor_mapping`');
		$this->legacy_db->where("MINOR_MAPPING_ID=$minor_mapping_id");
		return($this->legacy_db->get()->result_array());
	}

	function getMinorsByDiscipline_id ($discipline_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`minor_mapping`');
		$this->legacy_db->where("DISCIPLINE_ID=$discipline_id");
		return($this->legacy_db->get()->result_array());
	}

	function DeleteMinorSubject($minor_mapping_id)
	{
		$prev_record = $this->MinorMapping($minor_mapping_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("MINOR_MAPPING_ID",$minor_mapping_id);
		$query = $this->legacy_db->delete('minor_mapping');
		if ($query)
		{
			$this->log_model->create_log($minor_mapping_id,$this->legacy_db->insert_id(),$prev_record,'','storing minor_mapping_id','minor_mapping',13,0);
			return true;
		}
		else return false;
	}
}
