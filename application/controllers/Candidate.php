<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 4:07 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Candidate extends CI_Controller
{
    private $SelfController = 'Candidate';
    private $profile = 'Candidate/profile';
    private $LoginController = 'login';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    public function __construct()
    {
        parent::__construct();

        if(!$this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->LoginController);
            exit();
        }
    }
    function index(){
        $user = $this->session->userdata($this->SessionName);
        if($user){
            $data['user'] = $user;
            $data['profile_url'] = base_url().$this->profile;

            $this->load->view('include/header',$data);
            $this->load->view('include/preloder');
            $this->load->view('include/side_bar',$data);
            $this->load->view('include/nav',$data);
            $this->load->view('home',$data);
            $this->load->view('include/footer_area',$data);
            $this->load->view('include/footer',$data);
        }

    }
    function profile(){
        $user = $this->session->userdata($this->SessionName);
        if($user){
            $data['user'] = $user;
            $data['profile_url'] = base_url().$this->profile;

            $this->load->view('include/header',$data);
            $this->load->view('include/preloder');
            $this->load->view('include/side_bar',$data);
            $this->load->view('include/nav',$data);
            $this->load->view('profile',$data);
            $this->load->view('include/footer_area',$data);
            $this->load->view('include/footer',$data);
        }

    }

}