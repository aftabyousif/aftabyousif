<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Query_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
	}

	function upload_query($form_array){

		//load loging model
		// $this->load->model('log_model');
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->db->db_debug = false;
		if($this->legacy_db->insert('open_ticket', $form_array)){
			return true;
		} else {
			return  false;
		}
	}//method

	function get_uploaded_ticket($ticket_no)
	{
		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->select("*");
		$this->legacy_db->from("open_ticket");
		$this->legacy_db->where("TICKET_ID=$ticket_no");
		return $this->legacy_db->get()->row_array();
	}

	function reply_ticket($where,$record,$table){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->trans_begin();

		$this->legacy_db->where($where);
		$this->legacy_db->update($table,$record);
		if($this->legacy_db->affected_rows() ==1){
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),null,$record,"($where) FROM update METHOD",$table,12,0);
			$this->legacy_db->trans_commit();
			return true;
		}else{
			$this->legacy_db->trans_rollback();
			return false;
		}
	}//function
	
		function getUserTicketByCnic($user_id){
        $this->db = $this->load->database('admission_db',true);
		$this->db->select("*");
		$this->db->where('USER_ID',$user_id);
		$this->db->order_by("TICKET_ID", "DESC");
        $tickets = $this->db->get('open_ticket')->result_array();
        return $tickets;
    }
	
	function getAllTickets(){
        $this->db = $this->load->database('admission_db',true);
		$this->db->select("*");
		$this->db->where('REPLIER_ID',NULL);
		$this->db->order_by("DATETIME", "DESC");
        $tickets = $this->db->get('open_ticket')->result_array();
		//print_r($this->db->last_query());    
        return $tickets;
    }
		
	function getAllRepliedTickets(){
        $this->db = $this->load->database('admission_db',true);
		$this->db->select("*");
		$this->db->where('RESPONSE IS NOT NULL');
		//$this->db->order_by("TICKET_ID", "DESC");
        $tickets = $this->db->get('open_ticket')->result_array();
		//print_r($this->db->last_query());    
        return $tickets;
    }
}
