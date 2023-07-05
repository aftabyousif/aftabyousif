<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Developer extends CI_Controller
{
  function index(){
       
       
        $this->load->helper("form");
        $this->load->view('include/login_header');
        $this->load->view('include/preloder');
        $this->load->view('include/login_nav');
        $this->load->view('developer');
        $this->load->view('include/login_footer');
    } 
  
 }