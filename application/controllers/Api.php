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
        $this->load->model('Api_province_model');
    }
    public function getAllProvince(){
        $result = json_encode($this->Api_province_model->getAllProvince());
        prePrint($result);
    }
    public function getProvinceByCountryId(){
        $country_id = $this->input->get('country_id');

        if($country_id){
            //$this->output->set_status_header(200);
            $result = json_encode($this->Api_province_model->getProvinceByCountryId($country_id));
            ///prePrint($result);
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