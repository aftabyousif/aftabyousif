<?php

class Auth_Model extends CI_Model {
    function checkLogin($data){
        $this->db->where($data);
        $query = $this->db->get('users_reg');
        if($query->num_rows()==1){
            return $query->row();
        }else{
            return false;
        }
    }
}