<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';
class FeesSetup extends AdminLogin{

	public function __construct(){
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Configuration_model");
		$this->load->model('Api_location_model');
		$this->load->model("Admission_session_model");
		$this->load->model("AdmitCard_model");
		$this->load->model('User_model');
		$this->load->model('Application_model');
		$this->load->model('FormVerificationModel');
		$this->load->model('FeeChallan_model');
		$this->load->model('Selection_list_report_model');
		$this->load->model('TestResult_model');
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function feeProgramList(){

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('admin/FeesSetup/fee_program_list',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}//method

	public function get_fee_program_list_handler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$campus_id		= isValidData($request->campus_id);
		$program_type_id= isValidData($request->program_type_id);
		$shift_id 		= isValidData($request->shift_id);
		$part_id 		= ($request->part_id);
		$demerit_id 	= isValidData($request->demerit_id);
		$semester_id 	= isValidData($request->semester_id);

		$error = "";
		if (empty($campus_id)) $error.="Campus is Required";
		elseif (empty($program_type_id)) $error.="Program Type is Required";
		elseif (empty($shift_id)) $error.="Shift is Required";
		elseif (empty($part_id)) $error.="Part is Required";
		elseif (empty($demerit_id)) $error.="Demerit is Required";
		elseif (empty($semester_id)) $error.="Semester is Required";

		if (empty($error)){
			$programs = $this->Administration->getMappedPrograms ($shift_id,$program_type_id,$campus_id);
			$fee_program_lists = $this->FeeChallan_model->get_fee_program_list($campus_id,$program_type_id,$shift_id,$part_id,$demerit_id,$semester_id);
			foreach($fee_program_lists as $key=>$value){
				$prog_list_id = $value['PROG_LIST_ID'];

				$find = getIndexOfObjectInList($programs,'PROG_ID',$prog_list_id);
				if($find>=0){
					unset($programs[$find]);
				}
			}

			http_response_code(200);
			$arr['FEE_PROG_LIST']=$fee_program_lists;
			$arr['PROGRAM']=$programs;
			echo json_encode($arr);
		}else{
			http_response_code(204);
			echo $error;
			die();
		}
	}

	public function save_fee_program_list_handler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$campus_id		= isValidData($request->campus_id);
		$program_type_id= isValidData($request->program_type_id);
		$shift_id 		= isValidData($request->shift_id);
		$part_id 		= ($request->part_id);
		$demerit_id 	= isValidData($request->demerit_id);
		$semester_id 	= isValidData($request->semester_id);
		$prog_ids 	= ($request->prog_ids);

		$error = "";
		if (empty($campus_id)) $error.="Campus is Required";
		elseif (empty($program_type_id)) $error.="Program Type is Required";
		elseif (empty($shift_id)) $error.="Shift is Required";
		elseif (empty($part_id)) $error.="Part is Required";
		elseif (empty($demerit_id)) $error.="Demerit is Required";
		elseif (empty($semester_id)) $error.="Semester is Required";
		elseif (empty($prog_ids)) $error.="Programs are Required";

		if (empty($error)){
			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;
			foreach($prog_ids as $prog_id){
				$arr = array(
					'CAMPUS_ID'=>$campus_id,
					'PROGRAM_TYPE_ID'=>$program_type_id,
					'SHIFT_ID'=>$shift_id,
					'PROG_LIST_ID'=>isValidData($prog_id),
					'FEE_DEMERIT_ID'=>$demerit_id,
					'PART_ID'=>$part_id,
					'SEMESTER_ID'=>$semester_id
				);
				if ($this->legacy_db->insert('fee_program_list', $arr)){
					$flag=true;
				}else{
					$flag=false;
					break;

				}//else
			}//foreach

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully Saved...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(200);
				echo ("Transaction failed...");
			}
		}else{
			http_response_code(204);
			echo $error;
			die();
		}
	}

	public function delete_fee_program_list_handler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata,true);

		$fee_prog_list_ids 	= $request['fee_prog_list_ids'];

		$error = "";
		if (empty($fee_prog_list_ids)) $error.="Please select fee program";

		if (empty($error)){
			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;
			foreach($fee_prog_list_ids as $fee_prog_list_id){
			    
					$this->legacy_db->where('FEE_PROG_LIST_ID',$fee_prog_list_id);
				if ($this->legacy_db->delete('fee_program_list')){
					$flag=true;
				}else{
				    // prePrint($this->legacy_db->last_query());
					$flag=false;
					break;

				}//else
			}//foreach

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully deleted...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(206);
				echo ("Transaction failed...");
			}
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}

	public function programFeeStructure(){

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('admin/FeesSetup/programFeeStructureWindow',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function get_fee_program_list_for_fee_structure_handler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$campus_id		= isValidData($request->campus_id);
		$program_type_id= isValidData($request->program_type_id);
		$shift_id 		= isValidData($request->shift_id);
		$part_id 		= ($request->part_id);
		$demerit_id 	= isValidData($request->demerit_id);
		$semester_id 	= isValidData($request->semester_id);

		$error = "";
		if (empty($campus_id)) $error.="Campus is Required";
		elseif (empty($program_type_id)) $error.="Program Type is Required";
		elseif (empty($shift_id)) $error.="Shift is Required";
		elseif (empty($part_id)) $error.="Part is Required";
		elseif (empty($demerit_id)) $error.="Demerit is Required";
		elseif (empty($semester_id)) $error.="Semester is Required";

		if (empty($error)){
			$fee_program_lists = $this->FeeChallan_model->get_fee_program_list($campus_id,$program_type_id,$shift_id,$part_id,$demerit_id,$semester_id);

			http_response_code(200);
			$arr['FEE_PROG_LIST']=$fee_program_lists;
			echo json_encode($arr);
		}else{
			http_response_code(204);
			echo $error;
			die();
		}
	}

	public function getFeeCategoryType(){

		$postdata = file_get_contents("php://input");
		$request= json_decode($postdata);
		$fee_category_type = null;
		if(isset($request->flag)){
			$fee_category_type = $this->FeeChallan_model->get_fee_category_type();

		}
		if (empty($fee_category_type)){
			http_response_code(204);
			$this->output->set_content_type('application/json')->set_output(json_encode('Record not found...'));
		}else{
			http_response_code(200);
			$this->output->set_content_type('application/json')->set_output(json_encode($fee_category_type));
		}
	}//method

	public function saveFeesStructureHandler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$fee_category_type_id	= isValidData($request->fee_category_type_id);
		$session_id				= isValidData($request->session_id);
		$fee_prog_list_id		= $request->fee_prog_list_id;
		$fee_categories_amount	= json_decode(json_encode($request->fee_categories_amount),true);

		$error = "";
		if (empty($fee_category_type_id)) $error.="Fee Category Type is Required";
		elseif (empty($session_id)) $error.="Session is Required";
		elseif (empty($fee_prog_list_id)) $error.="Fee Program List is Required";
		elseif (empty($fee_categories_amount)) $error.="Fee Category Amount is Required";

		if (empty($error)){

			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;

			foreach ($fee_prog_list_id as $fee_prog_list_id_single){ // iteration on fee program list id
				$fee_structure = $this->FeeChallan_model->get_fee_structure_table($fee_category_type_id,0,$fee_prog_list_id_single,$session_id,'inserted_rows');
            //   prePrint($fee_structure);
				foreach ($fee_categories_amount as $fee_category){ // iteration on fee category where amount is entered
					$FEE_CATEGORY_ID = isValidData($fee_category['FEE_CATEGORY_ID']);

					$fee_amount=0;
					if (isset($fee_category['FEE_AMOUNT'])){
						$fee_amount = isValidData($fee_category['FEE_AMOUNT']);
					}

					if (count($fee_structure)>0&&isset($fee_category['FEE_STRUCTURE_ID'])){
						$FEE_STRUCTURE_ID = isValidData($fee_category['FEE_STRUCTURE_ID']);
						if ($FEE_STRUCTURE_ID>0){
							$this->legacy_db->set('AMOUNT',$fee_amount);
							$this->legacy_db->where("FEE_CATEGORY_TYPE_ID",$fee_category_type_id);
							$this->legacy_db->where("FEE_CATEGORY_ID",$FEE_CATEGORY_ID);
							$this->legacy_db->where("FEE_PROG_LIST_ID",$fee_prog_list_id_single);
							$this->legacy_db->where("SESSION_ID",$session_id);
							if ($this->legacy_db->update("fee_structure")){
								$flag = true;
								continue;
							}else{
								$flag=false;
								break;
							}
						}//if
					}//isset

						$find = getIndexOfObjectInList($fee_structure, 'FEE_CATEGORY_ID', $FEE_CATEGORY_ID);
						if ($find >= 0) { //check if record already inserted on then skip
							continue;
						}
						if ($fee_amount == 0 || $fee_amount == null) continue; //insertion will be skipped where amount is 0 or null

						$arr = array(
							'FEE_CATEGORY_TYPE_ID' => $fee_category_type_id,
							'FEE_CATEGORY_ID' => $FEE_CATEGORY_ID,
							'FEE_PROG_LIST_ID' => $fee_prog_list_id_single,
							'SESSION_ID' => $session_id,
							'AMOUNT' => $fee_amount
						);

						if ($this->legacy_db->insert('fee_structure', $arr)) {
							$flag = true;
						} else {
							$flag = false;
							break;
						}//else
						unset($arr);
				} //foreach amount
			} //foreach fee program list id

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully Saved...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(206);
				echo ("Transaction failed or already exist...");
			}

		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

	public function get_fee_program_list_fee_structure(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$fee_category_type_id	= isValidData($request->fee_category_type_id);
		$session_id				= isValidData($request->session_id);
		$fee_prog_list_id		= isValidData($request->fee_prog_list_id);

		$error = "";
		if (empty($fee_category_type_id)) $error.="Fee Category Type is Required";
		elseif (empty($session_id)) $error.="Session is Required";
		elseif (empty($fee_prog_list_id)) $error.="Fee Program List is Required";

		if (empty($error)){

			$fee_structure_inserted = $this->FeeChallan_model->get_fee_structure_table($fee_category_type_id,0,$fee_prog_list_id,$session_id,'inserted_rows');
			$fee_structure_all = $this->FeeChallan_model->get_fee_structure_table($fee_category_type_id,0,$fee_prog_list_id,$session_id,'all_rows');

			$arr['ALL']=$fee_structure_all;
			$arr['INSERTED']=$fee_structure_inserted;
			if ($arr){
				http_response_code(200);
				echo json_encode($arr);
			}else{
				http_response_code(206);
				echo ("Record not found...");
			}
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

	public function DownloadFeesStructureHandler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$fee_category_type_id	= isValidData($request->fee_category_type_id);
		$session_id				= isValidData($request->session_id);
		$fee_prog_list_id		= $request->fee_prog_list_id;

		$error = "";
		if (empty($fee_category_type_id)) $error.="Fee Category Type is Required";
		elseif (empty($session_id)) $error.="Session is Required";
		elseif (empty($fee_prog_list_id)) $error.="Fee Program List is Required";

		if (empty($error)){
			$rows = array ();
			foreach ($fee_prog_list_id as $fee_prog_list_id_single){ // iteration on fee program list id
				$fee_category_id = 0;
				$row=$this->FeeChallan_model->get_fee_structure_table($fee_category_type_id,$fee_category_id,$fee_prog_list_id_single,$session_id,'inserted_rows');
				if (empty($row)) continue;
				$rows[]=$row;
			} //foreach fee program list id
			if (empty($rows)){
				http_response_code(206);
				echo "record not found";
			}else{
				http_response_code(200);
				echo json_encode($rows);
			}
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

	public function DeleteFeesStructureHandler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$fee_category_type_id	= isValidData($request->fee_category_type_id);
		$session_id				= isValidData($request->session_id);
		$fee_prog_list_id		= $request->fee_prog_list_id;

		$error = "";
		if (empty($fee_category_type_id)) $error.="Fee Category Type is Required";
		elseif (empty($session_id)) $error.="Session is Required";
		elseif (empty($fee_prog_list_id)) $error.="Fee Program List is Required";

		if (empty($error)){

			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;

			foreach ($fee_prog_list_id as $fee_prog_list_id_single){ // iteration on fee program list id
				$this->legacy_db->where('SESSION_ID',$session_id);
				$this->legacy_db->where('FEE_CATEGORY_TYPE_ID',$fee_category_type_id);
				$this->legacy_db->where('FEE_PROG_LIST_ID',$fee_prog_list_id_single);
				if ($this->legacy_db->delete('fee_structure')){
					$flag=true;
				}else{
					$flag=false;
				}
			} //foreach fee program list id

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully deleted...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(206);
				echo ("Transaction failed...");
			}

		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

	public function DumpFeesStructureHandler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);


		$session_id		= isValidData($request->session_id);

		$new_session_id	= isValidData($request->new_session_id);

		$error = "";

		if (empty($session_id)) $error.="From Session is Required";
		elseif (empty($new_session_id)) $error.="New Session is Required";

		if (empty($error)){
			$fee_structure = $this->FeeChallan_model->get_fee_structure_table(0,0,0,$session_id,'table_rows');
			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;

			foreach ($fee_structure as $fees){
				$arr = array(
						'FEE_CATEGORY_TYPE_ID'=>$fees['FEE_CATEGORY_TYPE_ID'],
						'FEE_CATEGORY_ID'=>$fees['FEE_CATEGORY_ID'],
						'FEE_PROG_LIST_ID'=>$fees['FEE_PROG_LIST_ID'],
						'SESSION_ID'=>$new_session_id,
						'AMOUNT'=>$fees['AMOUNT'],
						'REMARKS'=>'Dump Program',
				);
				if ($this->legacy_db->insert('fee_structure',$arr)){
					$flag=true;
				}else{
					$flag=false;
				}
			} //foreach

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully uploaded...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(206);
				echo ("Transaction failed...");
			}
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method
	
	public function fee_enrolment(){

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('admin/FeesSetup/fee_enrolment_window',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function get_fee_enrolment_handler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$program_type_id= isValidData($request->program_type_id);
		$session_id 	= isValidData($request->session_id);

		$error = "";
		if (empty($program_type_id)) $error.="Program Type is Required";
		elseif (empty($session_id)) $error.="Session is Required";

		if (empty($error)){
			$fee_category_id=4;
			$fee_enrolment_id=0;
			$institute_type_id=1;
			$query_type='get_fee_enrolment_left';
			$fee_enrolments = $this->FeeChallan_model->fee_enrolment($session_id,0,$fee_category_id,$fee_enrolment_id,$institute_type_id,$query_type);
			if (empty($fee_enrolments)){
				http_response_code(206);
				echo 'Record not found...';
				die();
			}else{
				$arr = array();
				foreach ($fee_enrolments as $fee_enrolment){
					$INSTITUTE_NAME = $fee_enrolment['INSTITUTE_NAME'];
					if ($program_type_id==1){
						if (startsWith($INSTITUTE_NAME, 'BISE')){
							$arr[]=$fee_enrolment;
						}elseif (startsWith($INSTITUTE_NAME, 'BOARD')){
							$arr[]=$fee_enrolment;
						}
					}else{
						if (startsWith($INSTITUTE_NAME, 'BISE') == false){
							$arr[]=$fee_enrolment;
						}elseif (startsWith($INSTITUTE_NAME, 'OTHER BOARD / UNIVERSITY')){
							$arr[]=$fee_enrolment;
						}
					}

				}//foreach
			}//else
			http_response_code(200);
			echo json_encode($arr);
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}

	public function saveFeesEnrolmentHandler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$session_id = isValidData($request->session_id);
		$fee_enrolment_list	= json_decode(json_encode($request->fee_enrolment_list),true);
		$fee_category_id=4;

		$error = "";
		if (empty($session_id)) $error.="Session is Required";
		if (empty($fee_enrolment_list)) $error.="Fee Enrolment is Required";

		if (empty($error)){

			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;

			foreach ($fee_enrolment_list as $fee_enrolment){ // iteration on fee program list id
				$FEE_ENROLMENT_ID = isValidData($fee_enrolment['FEE_ENROLMENT_ID']);
				$INSTITUTE_ID = isValidData($fee_enrolment['INSTITUTE_ID']);
				$INSTITUTE_NAME = isValidData($fee_enrolment['INSTITUTE_NAME']);
				$AMOUNT = isValidData($fee_enrolment['AMOUNT']);
				$FEE_ENROLMENT_REMARKS = isValidData(ucwords(strtoupper($fee_enrolment['FEE_ENROLMENT_REMARKS'])));

				if ($AMOUNT == null || $AMOUNT ==0 || $AMOUNT == "") continue;
				if ($FEE_ENROLMENT_REMARKS == null || $FEE_ENROLMENT_REMARKS == "") $FEE_ENROLMENT_REMARKS=$INSTITUTE_NAME;

				if ($FEE_ENROLMENT_ID>0){
						$update_record = array ('AMOUNT'=>$AMOUNT,'REMARKS'=>$FEE_ENROLMENT_REMARKS);
						$this->legacy_db->where('FEE_ENROLMENT_ID',$FEE_ENROLMENT_ID);
						$this->legacy_db->update('fee_enrolment',$update_record);
					if($this->legacy_db->affected_rows() >0){
						$flag = true;
					}
				}else{
						$insert_record = array ('SESSION_ID'=>$session_id,'INSTITUTE_ID'=>$INSTITUTE_ID,'FEE_CATEGORY_ID'=>$fee_category_id,'AMOUNT'=>$AMOUNT,'REMARKS'=>$FEE_ENROLMENT_REMARKS);
						if ($this->legacy_db->insert('fee_enrolment',$insert_record)){
							$flag=true;
						}else{
							$flag=false;
							break;
						}
				}
			} //foreach

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully Saved...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(206);
				echo ("Transaction failed or already exist...");
			}

		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

	public function DeleteFeesEnrolmentHandler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$fee_enrolment_ids= $request->fee_enrolment_ids;

		$error = "";
		if (empty($fee_enrolment_ids)) $error.="Fee Enrolment is Required";

		if (empty($error)){

			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;

			foreach ($fee_enrolment_ids as $fee_enrolment_id){ // iteration on fee program list id
				$fee_enrolment_id = isValidData($fee_enrolment_id);
				$this->legacy_db->where('FEE_ENROLMENT_ID',$fee_enrolment_id);
				if ($this->legacy_db->delete('fee_enrolment')){
					$flag=true;
				}else{
					$flag=false;
					break;
				}
			} //foreach

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully deleted...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(206);
				echo ("Transaction failed...");
			}

		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

	public function DumpFeesEnrolmentHandler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);


		$session_id		= isValidData($request->session_id);
		$new_session_id	= isValidData($request->new_session_id);
		$fee_category_id=0;

		$error = "";

		if (empty($session_id)) $error.="From Session is Required";
		elseif (empty($new_session_id)) $error.="New Session is Required";

		if (empty($error)){
			$fee_enrolment_list = $this->FeeChallan_model->fee_enrolment($session_id,0,$fee_category_id,0,0,'get_fee_enrolment');
			$this->legacy_db = $this->load->database('admission_db',true);
			$this->legacy_db->trans_begin();
			$flag = false;

			foreach ($fee_enrolment_list as $fee_enrolment){
				$arr = array(
					'SESSION_ID'=>$new_session_id,
					'INSTITUTE_ID'=>$fee_enrolment['INSTITUTE_ID'],
					'FEE_CATEGORY_ID'=>$fee_enrolment['FEE_CATEGORY_ID'],
					'AMOUNT'=>$fee_enrolment['AMOUNT'],
					'REMARKS'=>$fee_enrolment['FEE_ENROLMENT_REMARKS'],
				);
				if ($this->legacy_db->insert('fee_enrolment',$arr)){
					$flag=true;
				}else{
					$flag=false;
				}
			} //foreach

			if ($flag){
				$this->legacy_db->trans_commit();
				http_response_code(200);
				echo ("Successfully uploaded...");
			}else{
				$this->legacy_db->trans_rollback();
				http_response_code(206);
				echo ("Transaction failed...");
			}
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method
	
	public function get_fees_information_single(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata,true);
		$fee_info = $request['challanInfo'];
		$challan_type_id	= isValidData($fee_info['CHALLAN_TYPE_ID']);
		$part_id			= isValidData($fee_info['PART_ID']);
		$fee_demerit_id		= isValidData($fee_info['FEE_DEMERIT_ID']);
		$semester_id		= isValidData($fee_info['SEMESTER_ID']);
		$application_id		= isValidData($fee_info['APPLICATION_ID']);
		$selection_list_id	= isValidData($fee_info['SELECTION_LIST_ID']);

		$error = "";

		if (empty($selection_list_id)) $error.="Selection is Required";
		elseif (empty($challan_type_id)) $error.="Challan Type is Required";
		elseif (empty($part_id)) $error.="Part is Required";
		elseif (empty($fee_demerit_id)) $error.="Demerit is Required";
		elseif (empty($semester_id)) $error.="Semester is Required";
		elseif (empty($application_id)) $error.="Application is Required";

		if (empty($error)){
			$student = $this->FeeChallan_model->get_student_for_challan($selection_list_id,$application_id,$part_id,$semester_id,$fee_demerit_id);

			$campus_id 			= $student['CAMPUS_ID'];
			$program_type_id 	= $student['PROGRAM_TYPE_ID'];
			$shift_id 			= $student['SHIFT_ID'];
			$prog_list_id 		= $student['PROG_LIST_ID'];
			$session_id 		= $student['SESSION_ID'];
			$fee_category_type_id= $student['FEE_CATEGORY_TYPE_ID'];
			$institute_id 		= $student['ORGANIZATION_ID'];
			$previous_challan_amount= $student['OLD_CHALLAN_AMOUNT'];
			$bank_account_id=$student['BANK_ACCOUNT_ID'];
			$fee_enrolment_id	=0;
			$fee_category_id	=4;
			$institute_type_id	=0;

			$paid_fee=0;
			$challan_amount=0;

			$paid_fee = $this->FeeChallan_model->get_candidate_paid_amount($application_id, 0, 1, array(1,3), 'Y');

			if (empty($paid_fee)) $paid_fee = 0;
			elseif ($paid_fee['PAID_AMOUNT'] == 0 || $paid_fee['PAID_AMOUNT'] == null || $paid_fee['PAID_AMOUNT'] == '') $paid_fee = 0;
			else $paid_fee = $paid_fee['PAID_AMOUNT'];

			$fee = $this->FeeChallan_model->get_sum_fee_structure_amount($campus_id,$program_type_id,$shift_id,$prog_list_id,$part_id,$semester_id,$session_id,0,$fee_category_type_id);

			if (empty($fee['FEE_PROG_LIST_ID']) || !isset($fee)){
				$error.= "Fee structure is missing";
			}else{
				$fee_prog_list_id=$fee['FEE_PROG_LIST_ID'];
				$challan_amount=$fee['AMOUNT'];
			}
			$enrolment_fee_amount=0;
			if (($part_id==1 || $part_id ==6 || $part_id==8) && ($semester_id==1 || $semester_id==11)){
				$enrolment_fee = $this->FeeChallan_model->fee_enrolment($session_id,$institute_id,$fee_category_id,$fee_enrolment_id,$institute_type_id,'get_fee_enrolment');
				if (!empty($enrolment_fee)){
					$enrolment_fee = $enrolment_fee[0];
					$enrolment_fee_amount=$enrolment_fee['AMOUNT'];
				}
			}

			$challan_amount = $challan_amount+$enrolment_fee_amount;
			$payable_amount = ($challan_amount+$previous_challan_amount)-$paid_fee;
			$dues = $previous_challan_amount-$paid_fee;
			$late_fee=null;
			$valid_upto=null;

			$remarks='';

			if ($shift_id == 1 && $payable_amount+$previous_challan_amount<=$paid_fee) $remarks = "NOT PAYABLE";
			elseif (($shift_id == 2 && $payable_amount+$previous_challan_amount<=$paid_fee)) $remarks = "NOT PAYABLE";
			elseif ($paid_fee>0) $remarks='DIFFERENCE FEE';
			elseif ($fee_demerit_id == 1) $remarks="FIRST AND SECOND SEMESTER FEE";
			elseif ($fee_demerit_id == 2) $remarks="FIRST SEMESTER FEE";

			$record_out = array(
							'CHALLAN_NO'=>'',
							'APPLICATION_ID'=>$application_id,
							'CHALLAN_TYPE_ID'=>intval($challan_type_id),
							'BANK_ACCOUNT_ID'=>intval($bank_account_id),
							'SELECTION_LIST_ID'=>$selection_list_id,
							'CHALLAN_AMOUNT'=>$challan_amount,
							'INSTALLMENT_AMOUNT'=>$challan_amount,
							'DUES'=>$dues,
							'LATE_FEE'=>$late_fee,
							'PAYABLE_AMOUNT'=>$payable_amount,
							'VALID_UPTO'=>$valid_upto,
							'REMARKS'=>$remarks,
							'ADMIN_USER_ID'=>intval($_SESSION['ADMIN_LOGIN_FOR_ADMISSION']['USER_ID']),
							'PART_ID'=>$part_id,
							'SEMESTER_ID'=>$semester_id,
							'FEE_DEMERIT_ID'=>intval($fee_demerit_id),
							'FEE_PROG_LIST_ID'=>$fee_prog_list_id,
							'ACTIVE'=>0
				);

			if (empty($error)){
				http_response_code(200);
				echo (json_encode($record_out));
				die();
			}else{
				http_response_code(206);
				echo ($error);
				die();
			}
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

	public function save_single_challan_handler(){

		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata,true);
		$fee_info = $request['challanInfo'];
//		prePrint($fee_info);

		$error = "";

		if (empty($fee_info['CHALLAN_NO'])) $error.="Challan No is Required";
		elseif (empty($fee_info['CHALLAN_AMOUNT'])) $error.="Challan Amount is Required";
		elseif (empty($fee_info['INSTALLMENT_AMOUNT'])) $error.="Installment Amount is Required";
		elseif (empty($fee_info['PAYABLE_AMOUNT'])) $error.="Payable Amount is Required";

		if (empty($error)){
			$this->legacy_db = $this->load->database('admission_db',true);
			unset($fee_info['FEE_DEMERIT_ID']);
			if (empty($fee_info['LATE_FEE'])) $fee_info['LATE_FEE']=0;
			if (empty($fee_info['DUES'])) $fee_info['DUES']=0;
			if ($this->legacy_db->insert('fee_challan',$fee_info)){
				http_response_code(200);
				echo ("Successfully uploaded...");
			}else{
				http_response_code(206);
				echo ("Transaction failed...");
			}
		}else{
			http_response_code(206);
			echo $error;
			die();
		}
	}//method

}
