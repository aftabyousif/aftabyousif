<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/10/2020
 * Time: 10:54 PM
 */

class User_model extends CI_model
{
    function getUserByCnicAndPassword($cnic,$password){
            $this->db->where('CNIC_NO',$cnic);
            $this->db->where('PASSWORD',$password);
            $user = $this->db->get('users_reg')->row_array();
            return $user;

        }
    function getUserByPassportAndPassword($passport,$password){
        $this->db->where('PASSPORT_NO',$passport);
        $this->db->where('PASSWORD',$password);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }
    function getUserByCnic($cnic){
        $this->db->where('CNIC_NO',$cnic);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }
    
	function getUserById($user_id){
        $this->db->where('USER_ID',$user_id);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }
	
	// JOIN QUERY TO GET USER ROLE FROM ROLE AND ROLE_RELATION TABLE
	// SELECT r.`ROLE_NAME`,r.`ACTIVE`, rr.`USER_ID`, r.`KEYWORD` from role r, role_relation rr where rr.USER_ID=93774 AND r.ROLE_ID=rr.ROLE_ID
	function getUserRoleByUserId($user_id){
		$this->db->select('r.`ROLE_NAME`,r.`ACTIVE`, rr.`USER_ID`, r.`KEYWORD`');
		$this->db->from('role_relation rr');
		$this->db->join('role AS r', 'rr.ROLE_ID = r.ROLE_ID');
		$this->db->where('rr.USER_ID',$user_id);
		$this->db->where('r.KEYWORD','UG_A');
        $this->db->where('r.ACTIVE','1');
		$user = $this->db->get()->row_array();
        return $user; 

    }
	function getQulificatinByUserId($user_id){
        $this->db->select('q.*,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME INSTITUTE,o.INSTITUTE_NAME ORGANIZATION');
        $this->db->from('qualifications q');
        $this->db->join('institute AS i', 'q.INSTITUTE_ID = i.INSTITUTE_ID');
        $this->db->join('institute AS o', 'q.ORGANIZATION_ID = o.INSTITUTE_ID');
        $this->db->join('discipline AS d', 'q.DISCIPLINE_ID = d.DISCIPLINE_ID');
        $this->db->join('degree_program AS p', 'd.DEGREE_ID = p.DEGREE_ID');
        $this->db->where('q.USER_ID',$user_id);
        $this->db->where('q.ACTIVE',1);
        $this->db->order_by('p.DEGREE_ID', 'DESC');
        $qulification_list = $this->db->get()->result_array();
        return $qulification_list;

    }
	
    function getUserByPassport($passport){
        $this->db->where('PASSPORT_NO',$passport);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }
    function changePassword($user_id,$curr_password,$password){
        $formArray = array('PASSWORD'=>$password);
        $this->db->trans_begin();
        $this->db->where('PASSWORD',$curr_password);
        $this->db->where('USER_ID',$user_id);
        $this->db->update('users_reg',$formArray);
        if($this->db->affected_rows() ==1){
            $this->db->trans_commit();
            return true;
        }else{
            $this->db->trans_rollback();
            return false;
        }

    }
    function updateUserById($user_id,$formArray){

            $this->db->trans_begin();
            $this->db->where('USER_ID',$user_id);
            $this->db->update('users_reg',$formArray);

            if($this->db->affected_rows() ==1){
                $this->db->trans_commit();
                return 1;
            }elseif($this->db->affected_rows() ==0){
                $this->db->trans_commit();
                return 0;
            }else{
                $this->db->trans_rollback();
                return -1;
            }

    }


}