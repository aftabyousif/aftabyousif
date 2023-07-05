<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 3/30/2022
 * Time: 6:34 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    function index(){
        $nav_data['MARQUEE_MSG'] = false;
        $data['POP_MSG']=false;
        $data['POP_IMAGE']=false;
        $data['POP_NEWS']=false ;
        $this->load->view('include/adms_header');
        $this->load->view('include/adms_nav',$nav_data);
        $this->load->view('home',$data);
        $this->load->view('include/adms_footer',$data);

    }
}