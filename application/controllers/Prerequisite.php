<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class Prerequisite extends AdminLogin
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model('Api_qualification_model');
		$this->load->model('Prerequisite_model');
		$this->load->model("Configuration_model");
//		$this->load->library('javascript');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function add_prerequisite ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';

		$degree_programs = $this->Api_qualification_model->getAllDegreeProgram();
		$programs = $this->Administration->programs();
		$data['degree_programs'] = $degree_programs;
		$data['program_list'] = $programs;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('display_prerequisite',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}//function

	public function save_prerequisite ()
	{
		$this->form_validation->set_rules('degree_id','Degree is required','required|trim|integer');
		$this->form_validation->set_rules('discipline_id','Discipline type is required','required|trim|integer');
//		$this->form_validation->set_rules('percentage','Percentage is required','required|trim|integer');
		$this->form_validation->set_rules('subject_id','Minor subject is required','trim|required');
		$this->form_validation->set_rules('study_program','Program of Study is required','trim|required');
		$this->form_validation->set_rules('prerequisite_id','','trim');
		$this->form_validation->set_rules('remarks','','trim');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message','Following * marked fields are required.');
			redirect("Prerequisite/add_prerequisite");
		}else
		{
			$degree_id 				= html_escape(htmlspecialchars($this->input->post('degree_id')));
			$discipline_id 			= html_escape(htmlspecialchars($this->input->post('discipline_id')));
			$prerequisite_id		= html_escape(htmlspecialchars($this->input->post('prerequisite_id')));
			$minor_mapping_id		= html_escape(htmlspecialchars($this->input->post('subject_id')));
//			$percentage				= html_escape(htmlspecialchars($this->input->post('percentage')));
			$remarks				= html_escape(htmlspecialchars($this->input->post('remarks')));
			$prerequisite_id		= html_escape(htmlspecialchars($this->input->post('prerequisite_id')));
			$study_program			= html_escape(htmlspecialchars($this->input->post('study_program')));

			$record = array(
				'MINOR_MAPPING_ID'=>html_escape(htmlspecialchars($minor_mapping_id)),
				'PROG_LIST_ID'=>html_escape(htmlspecialchars($study_program)),
//				'PERCENTAGE'=>html_escape(htmlspecialchars($discipline_id)),
				'REMARKS'=>html_escape(htmlspecialchars($remarks)),
			);
			if ($prerequisite_id == 0 || empty($prerequisite_id) || is_nan($prerequisite_id))
			{
				$response = $this->Prerequisite_model->insert($record,'prerequisite');
			}else
			{
				$previous_record = $this->Prerequisite_model->getPrerequisite_Prerequisite_id ($prerequisite_id);
				$response = $this->Administration->update("PREREQUISITE_ID=$prerequisite_id",$record,$previous_record,'prerequisite');
			}

			if ($response == true)
			{
				$this->session->set_flashdata('message',"Successfully added.");
				redirect("Prerequisite/add_prerequisite");

			}else
			{
				$this->session->set_flashdata('message','Process failed, Please try again.');
				redirect("Prerequisite/add_prerequisite");
			}
		}//else
	}

	public function getPrerequisite ()
	{
		$this->form_validation->set_rules('minor_mapping_id','Minor Subject is required','required|trim|integer');
		$minor_mapping_id 			= html_escape(htmlspecialchars($this->input->post('minor_mapping_id')));

		$prerequisite = $this->Prerequisite_model->getPrerequisite_minor_mapping_id ($minor_mapping_id);
		if (is_array($prerequisite) || is_object($prerequisite))
		{
			$array = array ();
			$i=0;
			foreach ($prerequisite as $prerequisite_key=>$prerequisite_value)
			{
				$pre_array = array();
				$MINOR_MAPPING_ID 	= $prerequisite_value['MINOR_MAPPING_ID'];
				$SUBJECT_TITLE 		= $prerequisite_value['SUBJECT_TITLE'];
				$REMARKS 			= $prerequisite_value['REMARKS'];
				$PREREQUISITE_ID 	= $prerequisite_value['PREREQUISITE_ID'];
				$PROG_LIST_ID 		= $prerequisite_value['PROG_LIST_ID'];
				$PROGRAM_TITLE 		= $prerequisite_value['PROGRAM_TITLE'];
				$PRE_REQ_PER 		= $prerequisite_value['PRE_REQ_PER'];
				$DISCIPLINE_ID 		= $prerequisite_value['DISCIPLINE_ID'];

				$disciplines = $this->Api_qualification_model->getDisciplineById($DISCIPLINE_ID);
				$DEGREE_ID = $disciplines['DEGREE_ID'];
				$DISCIPLINE_NAME = $disciplines['DISCIPLINE_NAME'];

				$pre_array['DEGREE_ID'] = $DEGREE_ID;
				$pre_array['DISCIPLINE_ID'] = $DISCIPLINE_ID;
				$pre_array['DISCIPLINE_NAME'] = $DISCIPLINE_NAME;
				$pre_array['MINOR_MAPPING_ID'] = $MINOR_MAPPING_ID;
				$pre_array['SUBJECT_TITLE'] = $SUBJECT_TITLE;
				$pre_array['REMARKS'] = $REMARKS;
				$pre_array['PREREQUISITE_ID'] = $PREREQUISITE_ID;
				$pre_array['PROG_LIST_ID'] = $PROG_LIST_ID;
				$pre_array['PROGRAM_TITLE'] = $PROGRAM_TITLE;
				$pre_array['PRE_REQ_PER'] = $PRE_REQ_PER;

				$array[$i] = $pre_array;
				$i++;
			}//foreach
			echo json_encode($array);
			exit();

		}//if
	}
	public function DeletePrerequisite ()
	{
		$this->form_validation->set_rules('prerequisite_id','Prerequisite id is required','required|trim|integer');
		if ($this->form_validation->run())
		{
			$prerequisite_id = html_escape(htmlspecialchars($this->input->post('prerequisite_id')));

			$response = $this->Prerequisite_model->DeletePrerequisite($prerequisite_id);
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
}
