<?php


class AdmitCard_model extends CI_Model
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

	function getVenueOnVenue_ID ($venue_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`venue`');
		$this->legacy_db->where("VENUE_ID=$venue_id");
		return($this->legacy_db->get()->result_array());
	}

	function getVenueOnSession_ID ($session_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`venue`');
		$this->legacy_db->where("SESSION_ID=$session_id");
		return($this->legacy_db->get()->result_array());
	}
	function DeleteVenue($venue_id)
	{
		$prev_record = $this->getVenueOnVenue_ID($venue_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("VENUE_ID",$venue_id);
		$query = $this->legacy_db->delete('venue');
		if ($query)
		{
			$this->log_model->create_log($venue_id,$this->legacy_db->insert_id(),$prev_record,'','storing venue id','venue',13,0);
			return true;
		}
		else return false;
	}

	function getBlockOnBlock_ID ($block_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`block`');
		$this->legacy_db->where("block_id=$block_id");
		return($this->legacy_db->get()->result_array());
	}

	function getBlockOnVenue_ID ($venue_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('v.`VENUE_ID`, v.`SESSION_ID`, v.`VENUE_NO`, v.`VENUE_NAME`, v.`LOCATION` AS VENUE_LOCATION, v.`REMARKS` AS VENUE_REMARKS, b.`BLOCK_ID`, b.`BLOCK_NO`, b.`BLOCK_NAME`, b.`LOCATION` AS BLOCK_LOCATION, `SEATING_CAPACITY`, `RESERVED_FOR`, b.`REMARKS` AS BLOCK_REMARKS');
		$this->legacy_db->from('`venue v`');
		$this->legacy_db->join('`block b`','v.VENUE_ID=b.VENUE_ID','INNER');
		$this->legacy_db->where("v.VENUE_ID=$venue_id");
		return($this->legacy_db->get()->result_array());
	}
}
