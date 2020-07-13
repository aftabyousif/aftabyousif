<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/12/2020
 * Time: 12:31 PM
 */

class Api_province_model extends CI_Model
{
    function getAllProvince()
    {
        $this->db->order_by('PROVINCE_NAME');
        return $this->db->get('provinces')->result_array();
    }
    function getProvinceByCountryId($country_id)
    {
        $country_id = isValidData($country_id);
        if(!$country_id){
            $country_id=0;
        }
        $this->db->order_by('PROVINCE_NAME');
        $this->db->where('COUNTRY_ID',$country_id);
        return $this->db->get('provinces')->result_array();
    }
    function getProvinceById($province_id)
    {
        $province_id = isValidData($province_id);
        if(!$province_id){
            $province_id=0;
        }
        $this->db->order_by('PROVINCE_NAME');
        $this->db->where('PROVINCE_ID',$province_id);
        return $this->db->get('provinces')->row_array();
    }

}