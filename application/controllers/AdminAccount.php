<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 12/16/2020
 * Time: 11:06 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class AdminAccount extends AdminLogin
{

	private $script_name = "";

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model('User_model');
		$this->load->model('AdminAccount_model');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/', $self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function UserRole()
	{

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id, $role_id);
		$this->verify_path($this->script_name,$side_bar_data);
		$data['side_bar_values'] = $side_bar_data;

		$data['user'] = $user;
		$data['profile_url'] = $user['PROFILE_IMAGE'];
		$this->load->view('include/header', $data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar', $data);
		$this->load->view('include/nav', $data);
		$this->load->view('admin/AddUserRole');
		$this->load->view('include/footer_area', $data);
		$this->load->view('include/footer', $data);
	}

	public function getUserData(){

		$this->form_validation->set_rules("cnic_no","CNIC No is required","trim|required|integer");

		if (!$this->form_validation->run()) {
			exit("<h3>Invalid input</h3>");
		}else {
			$cnic_no = html_escape(htmlspecialchars($this->input->post('cnic_no')));
			$userData = $this->User_model->getUserByCnic($cnic_no);
			if ($userData == null) exit("<h3>User Data not found</h3>");

			$user_id = $userData['USER_ID'];
			$userRoles = $this->AdminAccount_model->getUserAdmissionRoleByUserId($user_id);
			$role_list = $this->AdminAccount_model->getRoleList();

			if (empty($userRoles)) $userRoles = null;

//			prePrint($userData);
//			prePrint($userRoles);

			$data['USER_PROFILE_DATA']=$userData;
			$data['USER_ROLES']=$userRoles;
			$data['ROLE_LIST']=$role_list;
			$this->load->view('admin/UserAccount',$data);
		}//else
	}//method

	public function addUserRole(){

		$this->form_validation->set_rules("user_id","User ID is required","trim|required|integer");
		$this->form_validation->set_rules("role_id","Role ID is required","trim|required|integer");

		if (!$this->form_validation->run()) {
			exit("<span class='text-danger'>Invalid input</span>");
		}else {
			$user_id = html_escape(htmlspecialchars($this->input->post('user_id')));
			$role_id = html_escape(htmlspecialchars($this->input->post('role_id')));

			$userRoles = $this->AdminAccount_model->checkUserRoleByUserAndRoleID($user_id,$role_id);

			if ($userRoles == true) exit("<span class='text-danger'>Role already Exist...</span>");
			else{
					$array_record = array(
						"USER_ID"=>$user_id,
						"ROLE_ID"=>$role_id,
						"ACTIVE"=>1,
					);
					$response = $this->AdminAccount_model->insert($array_record,'role_relation');
					if ($response == true) exit("<span class='text-success'>Successfully Saved...</span>");
					else exit("<span class='text-danger'>Please try again...</span>");
			}
		}//else
	}//method

	public function disableUserRole(){

		$this->form_validation->set_rules("user_id","User ID is required","trim|required|integer");
		$this->form_validation->set_rules("r_r_id","Role Relation ID is required","trim|required|integer");
		$this->form_validation->set_rules("active","Active is required","trim|required|integer");

		if (!$this->form_validation->run()) {
			http_response_code(405);
			exit("<span class='text-danger'>Invalid input</span>");
		}else {
			$user_id = html_escape(htmlspecialchars($this->input->post('user_id')));
			$r_r_id = html_escape(htmlspecialchars($this->input->post('r_r_id')));
			$active = html_escape(htmlspecialchars($this->input->post('active')));

			$record = array("ACTIVE"=>$active);

			$userRoles = $this->AdminAccount_model->getUserAdmissionRoleByUserId($user_id);

			$response = $this->AdminAccount_model->update("R_R_ID=$r_r_id AND USER_ID=$user_id",$record,$userRoles,'role_relation');

			if ($response == true)
			{
				exit("<span class='text-success'>Successfully Saved...</span>");
			}else{
				http_response_code(405);
				exit("<span class='text-danger'>Please try again...</span>");
			}
		}//else
	}//method
}//class
