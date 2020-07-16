<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/12/2020
 * Time: 12:28 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');


class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Api_location_model');

    }

    public function getAllProvince(){
        $result = json_encode($this->Api_location_model->getAllProvince());
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }
    public function getProvinceByCountryId(){
        $country_id = $this->input->get('country_id');

        if($country_id){

            $result = json_encode($this->Api_location_model->getProvinceByCountryId($country_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
                $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }

    }
    public function getProvinceById(){
        $province_id = $this->input->get('province_id');

        if($province_id){

            $result = json_encode($this->Api_location_model->getProvinceById($province_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }

    public function getAllCountry(){

        $result = json_encode($this->Api_location_model->getAllCountry());
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output($result);

    }
    public function getCountryById(){
        $country_id = $this->input->get('country_id');

        if($country_id){

            $result = json_encode($this->Api_location_model->getCountryById($country_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }

    public function getAllDistrict(){
        $result = json_encode($this->Api_location_model->getAllDistrict());
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }
    public function getDistrictByProvinceId(){
        $province_id = $this->input->get('province_id');

        if($province_id){

            $result = json_encode($this->Api_location_model->getDistrictByProvinceId($province_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }

    }
    public function getDistrictById(){
        $district_id = $this->input->get('district_id');

        if($district_id){

            $result = json_encode($this->Api_location_model->getProvinceById($district_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }

    public function getAllCity(){
        $result = json_encode($this->Api_location_model->getAllCity());
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }
    public function getCityByDistrictId(){
        $district_id = $this->input->get('district_id');

        if($district_id){

            $result = json_encode($this->Api_location_model->getCityByDistrictId($district_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }

    }
    public function getCityById(){
        $city_id = $this->input->get('city_id');

        if($city_id){

            $result = json_encode($this->Api_location_model->getCityById($city_id));
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output($result);
        }else{
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array('error'=>"invalid input")));
        }
    }
}