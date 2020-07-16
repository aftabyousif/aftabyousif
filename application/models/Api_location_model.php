<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/12/2020
 * Time: 12:31 PM
 */

class Api_location_model extends CI_Model
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

    function getAllDistrict()
    {
        $this->db->order_by('DISTRICT_NAME');
        return $this->db->get('districts')->result_array();
    }
    function getDistrictByProvinceId($province_id)
    {
        $province_id = isValidData($province_id);
        if(!$province_id){
            $province_id=0;
        }
        $this->db->order_by('DISTRICT_NAME');
        $this->db->where('PROVINCE_ID',$province_id);
        return $this->db->get('districts')->result_array();
    }
    function getDistrictById($district_id)
    {
        $district_id = isValidData($district_id);
        if(!$district_id){
            $district_id=0;
        }
        $this->db->order_by('DISTRICT_NAME');
        $this->db->where('DISTRICT_ID',$district_id);
        return $this->db->get('districts')->row_array();
    }

    function getAllCity()
    {
        $this->db->order_by('CITY_NAME');
        return $this->db->get('cities')->result_array();
    }
    function getCityByDistrictId($district_id)
    {
        $district_id = isValidData($district_id);
        if(!$district_id){
            $district_id=0;
        }
        $this->db->order_by('CITY_NAME');
        $this->db->where('DISTRICT_ID',$district_id);
        return $this->db->get('cities')->result_array();
    }
    function getCityById($city_id)
    {
        $city_id = isValidData($city_id);
        if(!$city_id){
            $city_id=0;
        }
        $this->db->order_by('CITY_NAME');
        $this->db->where('CITY_ID',$city_id);
        return $this->db->get('cities')->row_array();
    }

}