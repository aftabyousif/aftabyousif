<?php 

class Api_Controller extends RestApi_Controller 
{
    function __construct() {
        parent::__construct();
        $this->load->library('api_auth');
        if($this->api_auth->isNotAuthenticated()) {
            $err = array(
                'status'=>false,
                'message'=>'unauthorised',
                'data'=>[]
            );
            $this->response($err);
        }
    }

    function getProfile() {
        $userId = $this->api_auth->getUserId();
        $this->load->model('api_model');
        $profileData = $this->api_model->getProfile($userId);
        $err = array(
            'status'=>true,
            'message'=>'successfully fetched profile',
            'data'=>$profileData
        );
        $this->response($err,200);
    }

}