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

}