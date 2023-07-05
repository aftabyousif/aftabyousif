<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';
class ViewAllRepliedQueries extends AdminLogin
{

	protected $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    private $user ;

	public function __construct()
	{
	    set_time_limit(1800);
	    
		parent::__construct();
		if(!$this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->LoginController);
            exit();
        }else{
            $this->user = $this->session->userdata($this->SessionName);
        }
        		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->set_mailtype("html");
		$this->load->model("Query_model");
	}//method

	public function ticket ()
	{		
		//$cnic=$_SESSION['USER_LOGIN_FOR_ADMISSION']['CNIC_NO'];
		$this->load->model('User_model');
		//$data['data'] = $this->User_model->getUserByCnic($cnic);
		$data['userTickets'] = $this->Query_model->getAllRepliedTickets();
		
		
		$user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $user_id = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];
        
		$data['user'] = $user;
        $data['profile_url'] = $user['PROFILE_IMAGE'];
		
		
		
		$this->load->view('include/header',$data);
        $this->load->view('include/preloder');
        $this->load->view('include/side_bar',$data);
        $this->load->view('include/nav',$data);
        $this->load->view("E_ticket/view_all_replied_tickets",$data);
		$this->load->view('include/footer_area',$data);
        $this->load->view('include/footer',$data);
				
	}
}