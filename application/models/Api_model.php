<?php
class Api_model extends CI_Model {
    function registerUser($data){
        $this->db->insert('users_reg',$data);
    }

    function checkLogin($data){
        $this->db->where($data);
        $query = $this->db->get('users_reg');
        if($query->num_rows()==1){
            return $query->row();
        }else{
            return false;
        }
    }

    function getProfile($userId){
        $this->db->select('USER_ID,ROLE,REF_NO,CNIC_NO,EMAIL,FIRST_NAME,PREFIX_ID,DESIGNATION_ID,FNAME,LAST_NAME,GENDER,MOBILE_NO,HOME_ADDRESS,PERMANENT_ADDRESS,DATE_OF_BIRTH,PLACE_OF_BIRTH,BLOOD_GROUP,ZIP_CODE,IS_CNIC_PASS,MOBILE_CODE,FAMILY_CONTACT_NO,PHONE,CNIC,DISTRICT,PASSPORT_NO,PASSPORT_EXPIRY,CNIC_EXPIRY,NATIONALITY,CNIC_OF,PROFILE_IMAGE,CNIC_FRONT_IMAGE,CNIC_BACK_IMAGE,PASSPORT_FRONT_IMAGE,PASSPORT_BACK_IMAGE,RELIGION,CITY_ID,UC_ID,COUNTRY_ID,MARITAL_STATUS,DISTRICT_ID,PROVINCE_ID,REMARKS,ACCT_OPENING_DATE,ACTIVE,PASSWORD,PASSWORD_TOKEN,BATCH_ID,U_R,NIC_OF,DOMICILE_PROVINCE,URL_AUTHENTICATION_TOKEN,URL_TOKEN_DATETIME,TOKEN_EXPIRY_DURATION,STATUS,WHATSAPP_NO,ACTIVE_TIME,LAST_LOGIN_TIME,CURRENT_LOGIN_TIME,FORGET_PASSWORD,FORGET_DATE_TIME,DOMICILE_IMAGE,DOMICILE_FORM_C_IMAGE,APPLICATION_ID');
        $this->db->where(['USER_ID'=>$userId]);
        $query = $this->db->get('users_reg');
        return $query->row();
    }

}