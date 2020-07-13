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
        //$this->load->model('Api_province_model');
        $this->load->model('Api_country_model');
    }
    public function getAllProvince(){
        $result = json_encode($this->Api_province_model->getAllProvince());
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }
    public function getProvinceByCountryId(){
        $country_id = $this->input->get('country_id');

        if($country_id){

            $result = json_encode($this->Api_province_model->getProvinceByCountryId($country_id));
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

            $result = json_encode($this->Api_province_model->getProvinceById($province_id));
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

        $result = json_encode($this->Api_country_model->getAllCountry());
        $this->output
        ->set_status_header(200)
        ->set_content_type('application/json', 'utf-8')
        ->set_output($result);

    }
    public function getCountryById(){
        $country_id = $this->input->get('country_id');

        if($country_id){

            $result = json_encode($this->Api_country_model->getCountryById($country_id));
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