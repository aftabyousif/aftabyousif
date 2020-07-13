<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/12/2020
 * Time: 12:31 PM
 */

class Api_country_model extends CI_Model
{
    function getAllCountry()
    {
        $this->db->order_by('COUNTRY_NAME');
        $this->db->where('ACTIVE',1);
        return $this->db->get('countries')->result_array();
    }
    function getCountryById($country_id)
    {
        $country_id = isValidData($country_id);
        if(!$country_id){
            $country_id=0;
        }
        $this->db->order_by('COUNTRY_NAME');
        $this->db->where('COUNTRY_ID',$country_id);
        $this->db->where('ACTIVE',1);
        return $this->db->get('countries')->row_array();
    }


}