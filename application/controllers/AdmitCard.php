<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class AdmitCard extends AdminLogin
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Configuration_model");
		$this->load->model("Admission_session_model");
		$this->load->model("AdmitCard_model");
//		$this->load->library('javascript');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function venue ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$sessions = $this->Admission_session_model->getSessionData();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['sessions'] = $sessions;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('venue',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function save_venue ()
	{
		$this->form_validation->set_rules('session_id','Session is required','required|trim|integer');
		$this->form_validation->set_rules('venue_no','Venue no is required','trim');
		$this->form_validation->set_rules('venue_name','Venue name  is required','trim|required');
		$this->form_validation->set_rules('venue_location','Venue location is required','trim|required');
		$this->form_validation->set_rules('venue_id','','trim');
		$this->form_validation->set_rules('remarks','','trim');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message','Following * marked fields are required.');
			redirect("AdmitCard/venue");
		}else
		{
			$session_id 	= html_escape(htmlspecialchars($this->input->post('session_id')));
			$venue_no 		= html_escape(htmlspecialchars($this->input->post('venue_no')));
			$venue_name		= html_escape(htmlspecialchars($this->input->post('venue_name')));
			$location		= html_escape(htmlspecialchars($this->input->post('venue_location')));
			$remarks		= html_escape(htmlspecialchars($this->input->post('remarks')));
			$venue_id		= html_escape(htmlspecialchars($this->input->post('venue_id')));

			$record = array(
				'SESSION_ID'=>$session_id,
				'VENUE_NO'=>$venue_no,
				'VENUE_NAME'=>ucwords(strtoupper($venue_name)),
				'LOCATION'=>ucwords(strtoupper($location)),
				'REMARKS'=>ucwords(strtoupper($remarks)),
			);
			if ($venue_id == 0 || empty($venue_id) || is_nan($venue_id))
			{
				$response = $this->AdmitCard_model->insert($record,'venue');
			}else
			{
				$previous_record = $this->AdmitCard_model->getVenueOnVenue_ID ($venue_id);
				$response = $this->Administration->update("VENUE_ID=$venue_id",$record,$previous_record,'venue');
			}

			if ($response == true)
			{
				$this->session->set_flashdata('message',"Successfully added.");
				redirect("AdmitCard/venue");

			}else
			{
				$this->session->set_flashdata('message','Process failed, Please try again.');
				redirect("AdmitCard/venue");
			}
		}//else
	}

	public function getVenue ()
	{
		$this->form_validation->set_rules('session_id','Minor Subject is required','required|trim|integer');
		$session_id 			= html_escape(htmlspecialchars($this->input->post('session_id')));

		$rows = $this->AdmitCard_model->getVenueOnSession_ID ($session_id);
		echo json_encode($rows);
	}

	public function DeleteVenue ()
	{
		$this->form_validation->set_rules('venue_id','venue id is required','required|trim|integer');
		if ($this->form_validation->run())
		{
			$venue_id = html_escape(htmlspecialchars($this->input->post('venue_id')));

			$response = $this->AdmitCard_model->DeleteVenue($venue_id);
			if ($response == true)
			{
				http_response_code(202);
				echo json_encode("Successfully Deleted");
			}
			else
			{
				http_response_code(406);
				echo json_encode("Could not delete mapped category");
			}
		}
	}//function

	public function block ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$sessions = $this->Admission_session_model->getSessionData();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['sessions'] = $sessions;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('block',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}
	public function save_block ()
	{
		$this->form_validation->set_rules('session_id','Session is required','required|trim|integer');
		$this->form_validation->set_rules('venue_id','Session is required','required|trim|integer');
		$this->form_validation->set_rules('block_no','Block no is required','trim');
		$this->form_validation->set_rules('block_name','Block name  is required','trim|required');
		$this->form_validation->set_rules('block_location','Venue location is required','trim|required');
		$this->form_validation->set_rules('seating_capacity','Seating Capacity is required','trim|required');
		$this->form_validation->set_rules('for','for is required','trim|required');
		$this->form_validation->set_rules('block_id','','trim');
		$this->form_validation->set_rules('remarks','','trim');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message','Following * marked fields are required.');
			redirect("AdmitCard/block");
		}else
		{
			$session_id 	= html_escape(htmlspecialchars($this->input->post('session_id')));
			$block_no 		= html_escape(htmlspecialchars($this->input->post('block_no')));
			$block_name		= html_escape(htmlspecialchars($this->input->post('block_name')));
			$location		= html_escape(htmlspecialchars($this->input->post('block_location')));
			$seating_capacity		= html_escape(htmlspecialchars($this->input->post('seating_capacity')));
			$remarks		= html_escape(htmlspecialchars($this->input->post('remarks')));
			$venue_id		= html_escape(htmlspecialchars($this->input->post('venue_id')));
			$block_id		= html_escape(htmlspecialchars($this->input->post('block_id')));
			$for		= html_escape(htmlspecialchars($this->input->post('for')));

			$record = array(
				'VENUE_ID'=>$venue_id,
				'BLOCK_NO'=>$block_no,
				'BLOCK_NAME'=>ucwords(strtoupper($block_name)),
				'LOCATION'=>ucwords(strtoupper($location)),
				'SEATING_CAPACITY'=>ucwords(strtoupper($seating_capacity)),
				'RESERVED_FOR'=>ucwords(strtoupper($for)),
				'REMARKS'=>ucwords(strtoupper($remarks)),
			);
			if ($block_id == 0 || empty($block_id) || is_nan($block_id))
			{
				$response = $this->AdmitCard_model->insert($record,'block');
			}else
			{
				$previous_record = $this->getBlockOnBlock_ID ($block_id);
				$response = $this->Administration->update("BLOCK_ID=$block_id",$record,$previous_record,'block');
			}

			if ($response == true)
			{
				$this->session->set_flashdata('message',"Successfully added.");
				redirect("AdmitCard/block");

			}else
			{
				$this->session->set_flashdata('message','Process failed, Please try again.');
				redirect("AdmitCard/block");
			}
		}//else
	}
	public function getBlock ()
	{
		$this->form_validation->set_rules('venue_id','venue is required','required|trim|integer');
		$venue_id 			= html_escape(htmlspecialchars($this->input->post('venue_id')));

		$rows = $this->AdmitCard_model->getBlockOnVenue_ID ($venue_id);
		echo json_encode($rows);
	}
}
