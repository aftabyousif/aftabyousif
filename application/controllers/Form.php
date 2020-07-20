<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Admission_session_model");
	}

	public function announcement ()
	{
		$admission_announcements = $this->Admission_session_model->get_form_admission_session ();

		$data['user'] = '';
		$data['user'] = '';
		$data['profile_url'] = '';

		$data['admission_announcement'] = $admission_announcements;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//		$this->load->view('include/nav',$data);
		$this->load->view('display_form_announcement',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function review ()
	{
		$this->form_validation->set_rules('ADMISSION_SESSION_ID','form session','required');
		$this->form_validation->set_rules('CAMPUS_ID','campus','required');
		if ($this->form_validation->run())
		{
			$ADMISSION_SESSION_ID = html_escape(htmlspecialchars($this->input->post('ADMISSION_SESSION_ID')));
			$CAMPUS_ID 			  = html_escape(htmlspecialchars($this->input->post('CAMPUS_ID')));

			$data['user'] = '';
			$data['user'] = '';
			$data['profile_url'] = '';

			$data['student_profile'] = '';

			$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//		$this->load->view('include/nav',$data);
			$this->load->view('form_review',$data);
//		$this->load->view('include/footer_area');
			$this->load->view('include/footer');

		}else
		{
			echo "please try again";
		}

	}

}
