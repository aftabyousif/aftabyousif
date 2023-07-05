<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 17/12/2020
 * Time: 10:00 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminAccount_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
	}

	function getUserAdmissionRoleByUserId($user_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('r.ROLE_ID, rr.R_R_ID, r.`ROLE_NAME`,rr.`ACTIVE`, rr.`USER_ID`, r.`KEYWORD`');
		$this->legacy_db->from('role_relation rr');
		$this->legacy_db->join('role AS r', 'rr.ROLE_ID = r.ROLE_ID');
		$this->legacy_db->where('rr.USER_ID',$user_id);
//		$this->db->where('r.KEYWORD','UG_A');
//		$this->legacy_db->where('r.ACTIVE','1');
		$user = $this->legacy_db->get()->result_array();
		return $user;
	}

	function getRoleList(){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('r.ROLE_ID, r.`ROLE_NAME`, r.`KEYWORD`');
		$this->legacy_db->from('role AS r');
		$this->legacy_db->where('r.ACTIVE','1');
		$role_list = $this->legacy_db->get()->result_array();
		return $role_list;
	}//method

	function checkUserRoleByUserAndRoleID($user_id,$role_id){

		$userRole = $this->getUserAdmissionRoleByUserId($user_id);
		if ($userRole !=null)
		{

		foreach ($userRole as $user_role) {
			$db_role_id = $user_role['ROLE_ID'];

			if ($db_role_id==$role_id) return true;
			else return  false;

		}//foreach
		}else
		{
			return false;
		}//else
	}//method

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
	
	function get_user_list_by_roll($role_id,$active = 1)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('r.ROLE_ID, rr.R_R_ID, r.`ROLE_NAME`,rr.`ACTIVE`, rr.`USER_ID`, r.`KEYWORD`');
		$this->legacy_db->from('role_relation rr');
		$this->legacy_db->join('role AS r', 'rr.ROLE_ID = r.ROLE_ID');
		$this->legacy_db->where_in('r.ROLE_ID',$role_id);
		if($active==1)
		$this->legacy_db->where('rr.ACTIVE',$active);
		$users = $this->legacy_db->get()->result_array();
//			prePrint($this->legacy_db->last_query());
		$user_array = array();

		foreach ($users as $user){
			$user_id = $user['USER_ID'];
			$user_role_id = $user['ROLE_ID'];
			$role_name = $user['ROLE_NAME'];
			$user_data = $this->User_model->getUserById($user_id);
			$user_data['ROLE_ID']=$user_role_id;
			$user_data['ROLE_NAME']=$role_name;
			$user_array[]=$user_data;
		}
//		prePrint($user_array);
		return $user_array;
	}//method

}//class
