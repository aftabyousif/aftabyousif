<?php
/**
 * Created by PhpStorm.
 * User: YASIR MEHBOOB
 * Date: 7/13/2020
 * Time: 05:17 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Mapping extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
//		$this->load->library('javascript');
	}

	 public function shift_program_mapping ()
	{
		$data['user'] = '';
		$data['user'] = '';
		$data['profile_url'] = '';

		$programs = $this->Administration->programs();
		$shifts	= $this->Administration->shifts();

		$data['programs'] = $programs;
		$data['shifts'] = $shifts;

		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('shift_prog_mapping',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');

	}
	 public function save_shift_program_mapping ()
	{
			$this->form_validation->set_rules('selected_programs[]','Programs are required','required');
			$this->form_validation->set_rules('shift','Shift is required','trim|required');
			if (!$this->form_validation->run())
			{
				$this->session->set_flashdata('message','Above fields are required.');
				redirect("mapping/shift_program_mapping");
			}else
			{
				$shift_id = $this->input->post('shift');
				$selected_programs = $this->input->post('selected_programs[]');
				$mega_array = array();
				foreach ($selected_programs as $key=>$value)
				{
					$prog_id = $value;
					$record = array(
						'PROG_LIST_ID'=>html_escape($prog_id),
						'SHIFT_ID'=>html_escape($shift_id)
					);
					array_push($mega_array,$record);
				}//foreach
						$response = $this->Administration->insert_batch($mega_array,'shift_program_mapping');
							if ($response == true)
							{
								$this->session->set_flashdata('message','Program mapping successfully done.');
								redirect("mapping/shift_program_mapping");

							}else
							{
								$this->session->set_flashdata('message','Process failed, Please try again.');
								redirect("mapping/shift_program_mapping");
							}
			}//else
	}//function

	public function getMappedPrograms ()
	{
//		echo json_encode('hello');
		$this->form_validation->set_rules('shift_id','shift is required','required|trim');
		if ($this->form_validation->run())
		{
			$shift_id = $this->input->post("shift_id");

			$record = $this->Administration->getMappedPrograms($shift_id);
			echo json_encode($record);
		}//if
	}//function

	/* this function is created for mapping dropdown to ignore already added programs*/

	public function ignoreMappedPrograms ()
	{
//		echo json_encode('hello');
		$this->form_validation->set_rules('shift_id','shift is required','required|trim');
		if ($this->form_validation->run())
		{
			$shift_id = $this->input->post("shift_id");

			$mapped_programs = $this->Administration->ignoreMappedPrograms($shift_id);
			echo json_encode($mapped_programs);
		}//if
	}//function

	public function DeleteMappedPrograms ()
	{
		$this->form_validation->set_rules('shift_id','shift is required','required|trim|integer');
		$this->form_validation->set_rules('prog_id','program is required','required|trim|integer');
		if ($this->form_validation->run())
		{
			$shift_id = html_escape($this->input->post('shift_id'));
			$prog_id  = html_escape($this->input->post('prog_id'));

			$response = $this->Administration->DeleteMappedPrograms_model($shift_id,$prog_id);
			if ($response == true)
			{
				http_response_code(202);
				echo json_encode("Successfully Deleted");
			}
			else
			{
				http_response_code(406);
				echo json_encode("Could not delete mapped program");
			}
		}
	}
//	public function onlineUser()
//	{
//		$this->load->view("online_users");
//	}
//	public function getLogUpdate ()
//	{
//		header('Content-Type: text/event-stream');
//		header('Cache-Control: no-cache');
////		$log = $this->log_model->get_log();
////		$time = date('r');
////		echo "data: The server time is: {$time}\n\n";
//		echo 'hello';
//		flush();
//	}

}
