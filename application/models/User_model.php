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

    function getExperiancesByUserId($user_id){

        $this->db->where('USER_ID',$user_id);
        $this->db->where('ACTIVE',1);
        $this->db->from('experiances');
        $experiances_list = $this->db->get()->result_array();
        return $experiances_list;
    }

    function addExperiances($form_array){
        $this->db->trans_begin();
        $this->db->insert('experiances', $form_array);
        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }

    function deleteExperiance($USER_ID,$experiance_id){
        $this->db->trans_begin();

        $formArray = array('ACTIVE'=>0);

        $this->db->where('EXPERIANCE_ID',$experiance_id);
        $this->db->where('USER_ID',$USER_ID);
        $this->db->where('ACTIVE',1);
        $this->db->update('experiances',$formArray);

        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }


}