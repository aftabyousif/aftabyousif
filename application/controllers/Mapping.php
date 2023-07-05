<?php
/**
 * Created by PhpStorm.
 * User: YASIR MEHBOOB
 * Date: 7/13/2020
 * Time: 05:17 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';
class Mapping extends AdminLogin
{
//	private $SessionName = 'ADMIN_LOGIN_FOR_ADMISSION';
	private $script_name = "";
	public function __construct()
	{
		parent::__construct();
        
        $this->load->model("Admission_session_model");
		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model('Api_qualification_model');
		$this->load->model('Api_location_model');
		$this->load->model('MeritList_model');
		$this->load->model('Selection_list_report_model');
//		$this->load->library('javascript');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	 public function shift_program_mapping ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';

		$programs 		= $this->Administration->programs();
		$shifts			= $this->Administration->shifts();
		$program_types 	= $this->Administration->programTypes ();
		$campus 	= $this->Administration->getCampus();


		$data['programs'] = $programs;
		$data['shifts'] = $shifts;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['program_types'] = $program_types;
		$data['campus'] = $campus;


		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('shift_prog_mapping',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');

	}
	
	 public function jurisdiction ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';

// 		$districts 		= $this->Api_location_model->getAllDistrict();
        $province        = $this->Api_location_model->getAllProvince();
		$campus			= $this->Administration->getCampus ();
	

		$data['province'] = $province;
		$data['campus'] = $campus;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
// 		$data['program_types'] = $program_types;

		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('jurisdiction_mapping',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}
	
	public function category_management ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];
		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';

		$category_type = $this->Administration->category_type();

//		$shifts	= $this->Administration->shifts();

		$data['category_type'] = $category_type;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('display_category',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');

	}

	/*
	 * YASIR MEHBOOB UPDATED add_minor METHOD ON 15-10-2020
	 * */
	public function add_minor ()
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
		$data['degree_programs'] = $degree_programs;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('display_minors',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');

	}

	 public function save_shift_program_mapping ()
	{
			$this->form_validation->set_rules('selected_programs[]','Programs are required','required');
			$this->form_validation->set_rules('shift','Shift is required','trim|required');
			$this->form_validation->set_rules('program_type','program type is required','trim|required');
			$this->form_validation->set_rules('campus_id','campus_id type is required','trim|required');

			if (!$this->form_validation->run())
			{
				$this->session->set_flashdata('message','Above fields are required.');
				redirect("mapping/shift_program_mapping");
			}else
			{
				$shift_id = $this->input->post('shift');
				$selected_programs = $this->input->post('selected_programs[]');
				$program_type = $this->input->post('program_type');
				$campus_id = $this->input->post('campus_id');

				$mega_array = array();
				foreach ($selected_programs as $key=>$value)
				{
					$prog_id = $value;
					$record = array(
						'PROG_LIST_ID'=>html_escape($prog_id),
						'SHIFT_ID'=>html_escape($shift_id),
						'PROGRAM_TYPE_ID'=>html_escape($program_type),
						'CAMPUS_ID'=>html_escape($campus_id)
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
		$this->form_validation->set_rules('program_type','program is required','required|trim');
		$this->form_validation->set_rules('campus_id','Campus Id is required','trim');
		$this->form_validation->set_rules('admission_session_id','Admission Session Id is required','trim');
		
		
		if ($this->form_validation->run())
		{
			$shift_id = $this->input->post("shift_id");
			$program_type = $this->input->post("program_type");
			$campus_id = $this->input->post("campus_id");
			$admission_session_id = $this->input->post("admission_session_id");
            
            if(empty($admission_session_id) || $admission_session_id == 0) $admission_session_id = 0;
            
        if($admission_session_id>0){
        $admission_session 	= $this->Selection_list_report_model->getDetailOnAdmissionSessionById($admission_session_id);
        $campus_id = $admission_session['CAMPUS_ID']; 
        }
        

			$record = $this->Administration->getMappedPrograms($shift_id,$program_type,$campus_id);
			echo json_encode($record);
		}//if
	}//function

	/* this function is created for mapping dropdown to ignore already added programs*/

	public function ignoreMappedPrograms ()
	{
//		echo json_encode('hello');
		$this->form_validation->set_rules('shift_id','shift is required','required|trim');
			//$this->form_validation->set_rules('campus_id','campus_id is required','required|trim');
		if ($this->form_validation->run())
		{
			$shift_id = $this->input->post("shift_id");
			$campus_id = $this->input->post("campus_id");

			$mapped_programs = $this->Administration->ignoreMappedPrograms($shift_id,$campus_id);
			echo json_encode($mapped_programs);
		}//if
	}//function

	public function DeleteMappedPrograms ()
	{
		$this->form_validation->set_rules('shift_id','shift is required','required|trim|integer');
		$this->form_validation->set_rules('prog_id','program is required','required|trim|integer');
		$this->form_validation->set_rules('campus_id','campus is required','required|trim|integer');
		if ($this->form_validation->run())
		{
			$shift_id = html_escape($this->input->post('shift_id'));
			$prog_id  = html_escape($this->input->post('prog_id'));
			$campus_id  = html_escape($this->input->post('campus_id'));

			$response = $this->Administration->DeleteMappedPrograms_model($shift_id,$prog_id,$campus_id);
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

	public function get_discipline ()
	{
//		echo json_encode('hello');
		$this->form_validation->set_rules('degree_id','degree program is required','required|trim');
		if ($this->form_validation->run())
		{
			$degree_id = $this->input->post("degree_id");

			$record = $this->Api_qualification_model->getDisciplineByDegreeId($degree_id);
			echo json_encode($record);
		}//if
	}//function

	public function save_minor ()
	{
		$this->form_validation->set_rules('degree_id','Degree is required','required|trim|integer');
		$this->form_validation->set_rules('discipline_id','Discipline type is required','required|trim|integer');
		$this->form_validation->set_rules('minor_name','Minor Title is required','trim|required');
		$this->form_validation->set_rules('minor_mapping_id','','trim');

		if (!$this->form_validation->run())
		{
			$this->session->set_flashdata('message','Following * marked fields are required.');
			redirect("mapping/add_minor");
		}else
		{
			$degree_id 				= html_escape(htmlspecialchars($this->input->post('degree_id')));
			$discipline_id 			= html_escape(htmlspecialchars($this->input->post('discipline_id')));
			$minor_name 			= html_escape(htmlspecialchars(ucwords(strtoupper($this->input->post('minor_name')))));
			$minor_mapping_id		= html_escape(htmlspecialchars($this->input->post('minor_mapping_id')));

			$record = array(
				'DISCIPLINE_ID'=>html_escape(htmlspecialchars($discipline_id)),
				'SUBJECT_TITLE'=>(htmlspecialchars($minor_name)),
			);
			if ($minor_mapping_id == 0 || empty($minor_mapping_id) || is_nan($minor_mapping_id))
			{
				$response = $this->Administration->insert($record,'minor_mapping');
			}else
			{
				$previous_record = $this->Administration->MinorMapping ($minor_mapping_id);
				$response = $this->Administration->update("MINOR_MAPPING_ID=$minor_mapping_id",$record,$previous_record,'minor_mapping');
			}

			if ($response == true)
			{
				$this->session->set_flashdata('message',"This $minor_name is successfully added.");
				redirect("mapping/add_minor");

			}else
			{
				$this->session->set_flashdata('message','Process failed, Please try again.');
				redirect("mapping/add_minor");
			}
		}//else
	}

public function getMappedMinors ()
{
	$this->form_validation->set_rules('discipline_id','Discipline is required','required|trim|integer');
	$discipline_id 			= html_escape(htmlspecialchars($this->input->post('discipline_id')));

	$disciplines = $this->Api_qualification_model->getDisciplineById($discipline_id);
	if (is_array($disciplines) || is_object($disciplines))
	{
			$DEGREE_ID = $disciplines['DEGREE_ID'];
			$DISCIPLINE_ID = $disciplines['DISCIPLINE_ID'];
			$DISCIPLINE_NAME = $disciplines['DISCIPLINE_NAME'];
			$DISCIPLINE_REMARKS = $disciplines['REMARKS'];

			$minor_list = $this->Administration->getMinorsByDiscipline_id($DISCIPLINE_ID);

			if (is_array($minor_list) || is_object($minor_list))
			{
				$array = array ();
				$i=0;
				foreach ($minor_list as $minor_list_key=>$minor_list_value)
				{
					$minor_array = array();
					$MINOR_MAPPING_ID = $minor_list_value['MINOR_MAPPING_ID'];
					$SUBJECT_TITLE = $minor_list_value['SUBJECT_TITLE'];
					$MINOR_REMARKS = $minor_list_value['REMARKS'];

					$minor_array['DISCIPLINE_ID'] = $DISCIPLINE_ID;
					$minor_array['DISCIPLINE_NAME'] = $DISCIPLINE_NAME;
					$minor_array['DEGREE_ID'] = $DEGREE_ID;
					$minor_array['MINOR_MAPPING_ID'] = $MINOR_MAPPING_ID;
					$minor_array['SUBJECT_TITLE'] = $SUBJECT_TITLE;
					$minor_array['DISCIPLINE_REMARKS'] = $DISCIPLINE_REMARKS;
					$minor_array['MINOR_REMARKS'] = $MINOR_REMARKS;

					$array[$i] = $minor_array;
					$i++;
				}//foreach
			}//if
		echo json_encode($array);
		exit();
		}//if

}//function

	public function DeleteMinorSubject ()
	{
		$this->form_validation->set_rules('minor_mapping_id','Minor subject id is required','required|trim|integer');
		if ($this->form_validation->run())
		{
			$minor_mapping_id = html_escape(htmlspecialchars($this->input->post('minor_mapping_id')));

			$response = $this->Administration->DeleteMinorSubject($minor_mapping_id);
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
	
	public function getdistricts ()
	{
//		echo json_encode('hello');
		$this->form_validation->set_rules('province','province is required','required|trim');
		if ($this->form_validation->run())
		{
			$province = $this->input->post("province");
	
			$record = $this->Api_location_model->getDistrictByProvinceId($province);
			echo json_encode($record);
		}//if
	}//function
	
	public function save_jurisdiction ()
	{
			$this->form_validation->set_rules('Districts[]','Districts are required','required');
			$this->form_validation->set_rules('campus','campus is required','trim|required');
			$this->form_validation->set_rules('province','province type is required','trim|required');
			$this->form_validation->set_rules('is_jurisdiction','province type is required','trim');

			if (!$this->form_validation->run())
			{
				$this->session->set_flashdata('message','Following * fields are required.');
				redirect("mapping/jurisdiction");
			}else
			{
				$campus = $this->input->post('campus');
				$Districts = $this->input->post('Districts[]');
				$province = $this->input->post('province');
				if(!empty(($this->input->post('is_jurisdiction'))))
				{
				    $is_jurisdiction = "Y";
				}else
				{
				    $is_jurisdiction = "N";
				}

				$mega_array = array();
				foreach ($Districts as $key=>$value)
				{
					$district_id = $value;
					$record = array(
						'DISTRICT_ID'=>html_escape($district_id),
						'CAMPUS_ID'=>html_escape($campus),
						'IS_JURISDICTION'=>html_escape($is_jurisdiction)
					);
					array_push($mega_array,$record);
				}//foreach
						$response = $this->Administration->insert_batch($mega_array,'jurisdiction');
							if ($response == true)
							{
								$this->session->set_flashdata('message','Campus mapping successfully done.');
								redirect("mapping/jurisdiction");

							}else
							{
								$this->session->set_flashdata('message','Process failed, Please try again.');
								redirect("mapping/jurisdiction");
							}
			}//else
	}//function
	
	
	public function getAllDistricts ()
	{
			$record = $this->Api_location_model->getAllDistrict();
			echo json_encode($record);

	}//function
	
	public function getMappedCampusJurisdiction ()
	{

		$this->form_validation->set_rules('campus','campus is required','required|trim');
		if ($this->form_validation->run())
		{
			$campus = $this->input->post("campus");

			$record = $this->Administration->getMappedCampusJurisdiction($campus,0);
			echo json_encode($record);
		}//if
	}//function
	
	public function DeleteJurisdiction ()
	{
		$this->form_validation->set_rules('jurisdiction_id','jurisdiction_id is required','required|trim|integer');
	    if ($this->form_validation->run())
		{
			$jurisdiction_id = html_escape($this->input->post('jurisdiction_id'));

			$response = $this->Administration->DeleteJurisdiction($jurisdiction_id);
			if ($response == true)
			{
				http_response_code(202);
				echo json_encode("Successfully Deleted");
			}
			else
			{
				http_response_code(406);
				echo json_encode("Could not delete mapped jurisdiction");
			}
		}
	}
	
	/*
	 * YASIR MEHBOOB ADDED NEW METHOD getProgramByProgramTypeID ON 15-10-2020
	 * */

	public function getProgramByProgramTypeID ()
	{
//		echo json_encode('hello');
//		$this->form_validation->set_rules('shift_id','shift is required','required|trim');
		$this->form_validation->set_rules('program_type','program is required','required|trim');
		if ($this->form_validation->run())
		{
//			$shift_id = $this->input->post("shift_id");
			$program_type = $this->input->post("program_type");

			$record = $this->Administration->getProgramsByProgramType ($program_type);
			
			

			
			echo json_encode($record);
		}//if
	}//function
	
	/*
	 * YASIR MEHBOOB ADDED NEW METHODS ON 23-12-2020
	 * */

	public function discipline_seat_distribution (){

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';

		$campus			= $this->Administration->getCampus();
		$sessions 		= $this->Admission_session_model->getSessionData();
		$shift 			= $this->Admission_session_model->getShiftData();
		$category_types = $this->Administration->category_type();
		$program_types  = $this->Administration->programTypes();

		$data['sessions'] = $sessions;
		$data['campus'] = $campus;
		$data['shifts'] = $shift;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['category_types'] = $category_types;
 		$data['program_types'] = $program_types;

		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('admin/discipline_seat_distribution',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function getDisciplineSeatDistribution (){

//		exit("working");
		$this->form_validation->set_rules('category_type_id','category type id is required','trim|required|integer');
		$this->form_validation->set_rules('campus','campus id is required','trim|required|integer');
		$this->form_validation->set_rules('prog_type_id','prog_type_id is required','trim|required|integer');
		$this->form_validation->set_rules('shift_id','shift_id is required','trim|required|integer');
		$this->form_validation->set_rules('session_id','session_id is required','trim|required|integer');

		if ($this->form_validation->run())
		{
			$category_type_id 	= html_escape(htmlspecialchars($this->input->post('category_type_id')));
			$campus 			= html_escape(htmlspecialchars($this->input->post('campus')));
			$prog_type_id 		= html_escape(htmlspecialchars($this->input->post('prog_type_id')));
			$shift_id 			= html_escape(htmlspecialchars($this->input->post('shift_id')));
			$session_id 		= html_escape(htmlspecialchars($this->input->post('session_id')));
			$prog_list_id 		= $this->input->post('prog_list_id');
			if (!$prog_list_id) $prog_list_id = array();

			$record = $this->MeritList_model->getDisciplineSeatsDistributions_detail($campus,$shift_id,$session_id,$prog_type_id,$prog_list_id);

			echo json_encode($record);
			die();
		}
	}

	public function save_discipline_seat_distribution (){

		$this->form_validation->set_rules('category_type_id','category type id is required','trim|required|integer');
		$this->form_validation->set_rules('campus','campus id is required','trim|required|integer');
		$this->form_validation->set_rules('prog_type_id','prog_type_id is required','trim|required|integer');
		$this->form_validation->set_rules('shift_id','shift_id is required','trim|required|integer');
		$this->form_validation->set_rules('session_id','session_id is required','trim|required|integer');
//		$this->form_validation->set_rules('prog_list_id','prog_list_id is required','trim|required');
		$this->form_validation->set_rules('category_id','category_id is required','trim|required|integer');
		$this->form_validation->set_rules('total_seats','total_seats is required','trim|required|integer');

		if ($this->form_validation->run())
		{
			$category_type_id 	= html_escape(htmlspecialchars($this->input->post('category_type_id')));
			$campus 			= html_escape(htmlspecialchars($this->input->post('campus')));
			$prog_type_id 		= html_escape(htmlspecialchars($this->input->post('prog_type_id')));
			$shift_id 			= html_escape(htmlspecialchars($this->input->post('shift_id')));
			$session_id			= html_escape(htmlspecialchars($this->input->post('session_id')));
			$category_id 		= html_escape(htmlspecialchars($this->input->post('category_id')));
			$total_seats 		= html_escape(htmlspecialchars($this->input->post('total_seats')));
			$prog_list_id 		= $this->input->post('prog_list_id');

			if (empty($prog_list_id)) exit("Please select program");

			$arr = array();
			foreach ($prog_list_id as $prog_id)
			{
				$arr[]= array(
				"SESSION_ID"=>$session_id,
				"CAMPUS_ID"=>$campus,
				"SHIFT_ID"=>$shift_id,
				"CATEGORY_ID"=>$category_id,
				"PROG_LIST_ID"=>$prog_id,
				"TOTAL_SEATS"=>$total_seats,
				"TOTAL_SEATS_REMAINING"=>$total_seats,
					);
			}

			$response = $this->Administration->insert_batch($arr,'discipline_seats_distributions');
			if ($response == true)
			{
				exit('Seat Distribution successfully done.');
			}else
			{
				exit('Process failed, Please try again.');
			}
		}else{
			echo("Invalid parameters");
		}
	}

	public function delete_discipline_seat_distribution (){

		$this->form_validation->set_rules('seat_distribution_id','seat distribution id is required','trim|required|integer');

		if ($this->form_validation->run())
		{
			$seat_distribution_id 	= html_escape(htmlspecialchars($this->input->post('seat_distribution_id')));

			$response = $this->Administration->DeleteDisciplineSeatDistribution($seat_distribution_id);
			if ($response == true)
			{
				exit("<h4 class='text-danger'>Successfully deleted. </h4>");
			}else
			{
				exit("<h4 class='text-danger'>Process failed, Please try again.</h4>");
			}
		}else{
			echo("<h4 class='text-danger'>Invalid parameters</h4>");
		}
	}

	public function update_discipline_seat_distribution (){

		$this->form_validation->set_rules('seat_distribution_id','seat distribution id is required','trim|required|integer');
		$this->form_validation->set_rules('total_seats','total seats is required','trim|required|integer');

		if ($this->form_validation->run())
		{
			$seat_distribution_id= html_escape(htmlspecialchars($this->input->post('seat_distribution_id')));
			$total_seats 		= html_escape(htmlspecialchars($this->input->post('total_seats')));

				$arr= array(
					"TOTAL_SEATS"=>$total_seats,
					"TOTAL_SEATS_REMAINING"=>$total_seats,
				);

			$response = $this->Administration->update("DISCIPLINE_SEAT_ID=$seat_distribution_id",$arr,null,'discipline_seats_distributions');
			if ($response == true)
			{
				exit('Seat Distribution successfully saved.');
			}else
			{
				exit('Process failed, Please try again.');
			}
		}else{
			echo("Invalid parameters");
		}
	}

	public function district_seat_distribution (){

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';

		$province_id = SINDH_PROVINCE_ID;

		$campus			= $this->Administration->getCampus();
		$sessions 		= $this->Admission_session_model->getSessionData();
		$shift 			= $this->Admission_session_model->getShiftData();
		$category_types = $this->Administration->category_type();
		$program_types  = $this->Administration->programTypes();
		$districts  = $this->Api_location_model->getDistrictByProvinceId($province_id);

		$data['sessions'] = $sessions;
		$data['campus'] = $campus;
		$data['shifts'] = $shift;
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['category_types'] = $category_types;
		$data['program_types'] = $program_types;
		$data['districts'] = $districts;

		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('admin/district_seat_distribution',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function getDistrictQuotaSeatDistribution (){

//		$this->form_validation->set_rules('category_type_id','category type id is required','trim|required|integer');
		$this->form_validation->set_rules('campus','campus id is required','trim|required|integer');
		$this->form_validation->set_rules('prog_type_id','prog_type_id is required','trim|integer');
		$this->form_validation->set_rules('shift_id','shift_id is required','trim|required|integer');
		$this->form_validation->set_rules('session_id','session_id is required','trim|required|integer');
		$this->form_validation->set_rules('district_id','district_id is required','trim|integer');

		if ($this->form_validation->run())
		{
//			$category_type_id 	= html_escape(htmlspecialchars($this->input->post('category_type_id')));
			$campus 			= html_escape(htmlspecialchars($this->input->post('campus')));
			$prog_type_id 		= html_escape(htmlspecialchars($this->input->post('prog_type_id')));
			$shift_id 			= html_escape(htmlspecialchars($this->input->post('shift_id')));
			$session_id 		= html_escape(htmlspecialchars($this->input->post('session_id')));
			$district_id 		= html_escape(htmlspecialchars($this->input->post('district_id')));

			$prog_list_id 		= $this->input->post('prog_list_id');
			if (!$prog_list_id) $prog_list_id = array();
			$record = $this->MeritList_model->getDistrictQuotaSeats_detail($campus,$shift_id,$session_id,$prog_type_id,$prog_list_id,$district_id);
			echo json_encode($record);
			die();
		}
	}

	public function save_district_quota_seat_distribution (){

//		prePrint($_POST);
//		$this->form_validation->set_rules('category_type_id','category type id is required','trim|required|integer');
		$this->form_validation->set_rules('campus','campus id is required','trim|required|integer');
//		$this->form_validation->set_rules('prog_type_id','prog_type_id is required','trim|required|integer');
		$this->form_validation->set_rules('shift_id','shift_id is required','trim|required|integer');
		$this->form_validation->set_rules('session_id','session_id is required','trim|required|integer');
//		$this->form_validation->set_rules('prog_list_id','prog_list_id is required','trim|required');
//		$this->form_validation->set_rules('category_id','category_id is required','trim|required|integer');
		$this->form_validation->set_rules('total_seats','total seats is required','trim|integer');
		$this->form_validation->set_rules('urban_seats','urban seats is required','trim|integer');
		$this->form_validation->set_rules('rural_seats','rural seats is required','trim|integer');
		$this->form_validation->set_rules('district_id','district id is required','trim|required|integer');

		if ($this->form_validation->run())
		{
//			$category_type_id 	= html_escape(htmlspecialchars($this->input->post('category_type_id')));
			$campus 			= html_escape(htmlspecialchars($this->input->post('campus')));
//			$prog_type_id 		= html_escape(htmlspecialchars($this->input->post('prog_type_id')));
			$shift_id 			= html_escape(htmlspecialchars($this->input->post('shift_id')));
			$session_id			= html_escape(htmlspecialchars($this->input->post('session_id')));
//			$category_id 		= html_escape(htmlspecialchars($this->input->post('category_id')));
			$total_seats 		= html_escape(htmlspecialchars($this->input->post('total_seats')));
			$urban_seats 		= html_escape(htmlspecialchars($this->input->post('urban_seats')));
			$rural_seats 		= html_escape(htmlspecialchars($this->input->post('rural_seats')));
			$district_id 		= html_escape(htmlspecialchars($this->input->post('district_id')));
			$prog_list_id 		= $this->input->post('prog_list_id');

			if (empty($prog_list_id)) exit("Please select program");

			$arr = array();
			foreach ($prog_list_id as $prog_id)
			{
				$arr[]= array(
					"SESSION_ID"=>$session_id,
					"CAMPUS_ID"=>$campus,
					"SHIFT_ID"=>$shift_id,
					"PROG_LIST_ID"=>$prog_id,
					"DISTRICT_ID"=>$district_id,
					"RURAL_SEATS"=>$rural_seats,
					"URBAN_SEATS"=>$urban_seats,
					"TOTAL_SEATS"=>$total_seats,
					"RURAL_SEATS_REMAINING"=>$rural_seats,
					"URBAN_SEATS_REMAINING"=>$urban_seats,
					"TOTAL_SEATS_REMAINING"=>$total_seats,
				);
			}

			$response = $this->Administration->insert_batch($arr,'district_quota_seats');
			if ($response == true)
			{
				exit('Seat Distribution successfully done.');
			}else
			{
				exit('Process failed, Please try again.');
			}
		}else{
			echo("Invalid parameters");
		}
	}

	public function delete_district_quota_seat_distribution (){

		$this->form_validation->set_rules('DISTRICT_QUOTE_ID','DISTRICT QUOTE ID is required','trim|required|integer');

		if ($this->form_validation->run())
		{
			$DISTRICT_QUOTE_ID 	= html_escape(htmlspecialchars($this->input->post('DISTRICT_QUOTE_ID')));

			$response = $this->Administration->DeleteDistrictQuotaSeatDistribution($DISTRICT_QUOTE_ID);
			if ($response == true)
			{
				exit("<h4 class='text-danger'>Successfully deleted. </h4>");
			}else
			{
				exit("<h4 class='text-danger'>Process failed, Please try again.</h4>");
			}
		}else{
			echo("<h4 class='text-danger'>Invalid parameters</h4>");
		}
	}

	public function update_district_quota_seat_distribution (){

		$this->form_validation->set_rules('DISTRICT_QUOTE_ID','DISTRICT QUOTE ID is required','trim|required|integer');
		$this->form_validation->set_rules('TOTAL_SEATS_INPUT','total seats is required','trim|required|integer');
		$this->form_validation->set_rules('RURAL_SEATS_INPUT','RURAL SEATS is required','trim|required|integer');
		$this->form_validation->set_rules('URBAN_SEATS_INPUT','URBAN SEATS is required','trim|required|integer');

		if ($this->form_validation->run())
		{
			$DISTRICT_QUOTE_ID= html_escape(htmlspecialchars($this->input->post('DISTRICT_QUOTE_ID')));
			$total_seats 		= html_escape(htmlspecialchars($this->input->post('TOTAL_SEATS_INPUT')));
			$rural_seats 		= html_escape(htmlspecialchars($this->input->post('RURAL_SEATS_INPUT')));
			$urban_seats 		= html_escape(htmlspecialchars($this->input->post('URBAN_SEATS_INPUT')));

			$arr= array(
				"RURAL_SEATS"=>$rural_seats,
				"URBAN_SEATS"=>$urban_seats,
				"TOTAL_SEATS"=>$total_seats,
				"RURAL_SEATS_REMAINING"=>$rural_seats,
				"URBAN_SEATS_REMAINING"=>$urban_seats,
				"TOTAL_SEATS_REMAINING"=>$total_seats,
			);

			$response = $this->Administration->update("DISTRICT_QUOTE_ID=$DISTRICT_QUOTE_ID",$arr,null,'district_quota_seats');
			if ($response == true)
			{
				exit('Seat Distribution successfully saved.');
			}else
			{
				exit('Process failed, Please try again.');
			}
		}else{
			echo("Invalid parameters");
		}
	}

}
