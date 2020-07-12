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
        $this->db->order_by('PROVINCE_NAME');
        $this->db->where('COUNTRY_ID',$country_id);
        return $this->db->get('provinces')->result_array();
    }
    function getProvinceById($province_id)
    {
        $this->db->order_by('PROVINCE_NAME');
        $this->db->where('PROVINCE_ID',$province_id);
        return $this->db->get('provinces')->result_array();
    }

}