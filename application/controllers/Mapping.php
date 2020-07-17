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
	public function category_management ()
	{
		$data['user'] = '';
		$data['user'] = '';
		$data['profile_url'] = '';

		$category_type = $this->Administration->category_type();
//		$shifts	= $this->Administration->shifts();

		$data['category_type'] = $category_type;
//		$data['shifts'] = $shifts;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('display_category',$data);
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

	public function save_category ()
	{
		$this->form_validation->set_rules('category_type_id','Category type is required','required|trim|integer');
		$this->form_validation->set_rules('category_name','Category Name is required','trim|required');
		$this->form_validation->set_rules('code','','trim|integer');
		$this->form_validation->set_rules('p_code','','trim');
		$this->form_validation->set_rules('remarks','','trim');
		$this->form_validation->set_rules('category_id','','trim');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message','Following * marked fields are required.');
			redirect("mapping/category_management");
		}else
		{
			$category_type_id 	= html_escape(htmlspecialchars($this->input->post('category_type_id')));
			$category_name 		= (htmlspecialchars(ucwords(strtoupper($this->input->post('category_name')))));
			$code 				= html_escape(htmlspecialchars($this->input->post('code')));
			$p_code 			= html_escape(htmlspecialchars(ucwords(strtoupper($this->input->post('p_code')))));
			$remarks 			= html_escape(htmlspecialchars(ucwords(strtoupper($this->input->post('remarks')))));
			$category_id		= html_escape(htmlspecialchars(ucwords(strtoupper($this->input->post('category_id')))));

			$record = array(
				'CATEGORY_TYPE_ID'=>html_escape(htmlspecialchars($category_type_id)),
				'CATEGORY_NAME'=>(htmlspecialchars($category_name)),
				'P_CODE'=>html_escape(htmlspecialchars($p_code)),
				'CODE'=>html_escape(htmlspecialchars($code)),
				'REMARKS'=>html_escape(htmlspecialchars($remarks))
							);
			if ($category_id == 0 || empty($category_id) || is_nan($category_id))
			{
				$response = $this->Administration->insert($record,'category');
			}else
			{
				$previous_record = $this->Administration->MappedCategory(0,$category_id);
				$response = $this->Administration->update("CATEGORY_ID=$category_id",$record,$previous_record,'category');
			}

			if ($response == true)
			{
				$this->session->set_flashdata('message',"This $category_name is successfully mapped.");
				redirect("mapping/category_management");

			}else
			{
				$this->session->set_flashdata('message','Process failed, Please try again.');
				redirect("mapping/category_management");
			}
		}//else
	}

	public function getMappedCategory ()
	{
//		$prev_record = $this->Administration->MappedCategory(0,5);
//		echo print_r($prev_record);
//		exit();
		$this->form_validation->set_rules('category_type_id','category type id is required','trim|required|integer');
		if ($this->form_validation->run())
		{
			$category_type_id = html_escape(htmlspecialchars($this->input->post('category_type_id')));

			$record = $this->Administration->MappedCategory($category_type_id,0);

			echo json_encode($record);
			die();
		}
	}
	public function DeleteMappedCategory ()
	{
		$this->form_validation->set_rules('category_id','category id is required','required|trim|integer');
			if ($this->form_validation->run())
		{
			$category_id = html_escape(htmlspecialchars($this->input->post('category_id')));

			$response = $this->Administration->DeleteMappedCategory($category_id);
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