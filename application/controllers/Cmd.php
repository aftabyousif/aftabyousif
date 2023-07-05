<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 01/19/2021
 * Time: 05:59 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class Cmd extends AdminLogin
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model('User_model');
		$this->load->model('AdminAccount_model');
		$this->load->model('FeeChallan_model');
		$this->load->model('Application_model');
		$this->load->model('FormVerificationModel');
		
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/', $self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function index ()
	{
		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$bank_accounts = $this->FeeChallan_model->get_bank_account();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['bank_account'] = $bank_accounts;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('admin/cmd_import',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function cmd_import ()
	{
		$this->form_validation->set_rules('array_cmd','Record is required','required|trim');
		$this->form_validation->set_rules('bank_account_id','Bank Account is required','required|trim|integer');
		if ($this->form_validation->run())
		{
			$bank_account_id = htmlspecialchars(html_escape($this->input->post('bank_account_id')));

			$records = (($this->input->post('array_cmd')));
			$records = json_decode($records,true);

			if (empty($records)) exit("data not found...");
			$date = date ("Y-m-d");

			$main_array = array();

			foreach ($records as $record):

				$Branch_Code = isset($record['BRANCH_CODE'])?$record['BRANCH_CODE']:'.';
				$Branch_Name = isset($record['BRANCH_NAME'])?$record['BRANCH_NAME']:'.';
				$Deposit_Slip_No = isset($record['DEPOSIT_SLIP_NO'])?$record['DEPOSIT_SLIP_NO']:'.';
				$Collection_Date = isset($record['COLLECTION_DATE'])?$record['COLLECTION_DATE']:'.';
				$Mode_of_Payment = isset($record['MODE_OF_PAYMENT'])?$record['MODE_OF_PAYMENT']:'.';
				$Instrument_No = isset($record['INSTRUMENT_NO'])?$record['INSTRUMENT_NO']:'.';
				$Amount = isset($record['AMOUNT'])?$record['AMOUNT']:'.';
				$Credit_Date = isset($record['CREDIT_DATE'])?$record['CREDIT_DATE']:'.';
				$Challan_No = isset($record['CHALLAN_NO'])?$record['CHALLAN_NO']:'.';
				$Challan_No_Description = isset($record['CANDIDATE_NAME'])?$record['CANDIDATE_NAME']:'.';
				$father_name = isset($record['FNAME'])?$record['FNAME']:'.';
				$BATCH_ID = isset($record['ROLL_NO'])?$record['ROLL_NO']:'.';
				$Program_Class = isset($record['PROGRAM'])?$record['PROGRAM']:'.';
				$campus_name = isset($record['CAMPUS_NAME'])?$record['CAMPUS_NAME']:'.';

				$Collection_Date = date_create($Collection_Date);
				$Collection_Date = date_format($Collection_Date,'Y-m-d');

				$Credit_Date = date_create($Credit_Date);
				$Credit_Date = date_format($Credit_Date,'Y-m-d');

				$Amount = str_replace(',','',$Amount);

				$array = array(
					"BRANCH_CODE"=>$Branch_Code,
					"BRANCH_NAME"=>$Branch_Name,
				 	"DEPOSIT_SLIP_NO"=>$Deposit_Slip_No,
					"COLLECTION_DATE"=>$Collection_Date,
					"MODE_OF_PAYMENT"=>$Mode_of_Payment,
					"INSTRUMENT_NO"=>$Instrument_No,
					"AMOUNT"=>$Amount,
					"CREDIT_DATE"=>$Credit_Date,
					"CHALLAN_NO"=>$Challan_No,
					"CANDIDATE_NAME"=>$Challan_No_Description,
					"FNAME"=>$father_name,
					"ROLL_NO"=>$BATCH_ID,
					"PROGRAM"=>$Program_Class,
					"CAMPUS_NAME"=>$campus_name,
					"IMPORT_DATE"=>$date,
					"REMARKS"=>'',
					"BANK_ACCOUNT_ID"=>$bank_account_id,
					);
				array_push($main_array,$array);
			endforeach;

//			prePrint($main_array);
			$response = $this->FeeChallan_model->import_cmd_table($main_array);
//			$response = true;
			if ($response <> false)
			{
//				http_response_code(202);
				$file_path = base_url().$response;
				echo ("Successfully imported...");
//				echo ("Successfully imported... <a href='$file_path' download> Download CMD failed import log</a>");
//				prePrint($records);
			}
			else
			{
//				http_response_code(406);
				echo ("Import failed...");
			}
		}
	}//function

	public function getCmdRecord (){
	    
		$this->form_validation->set_rules('bank_account_id','bank account is required','required|trim');
		$this->form_validation->set_rules('start_date','start date is required','required|trim');
		$this->form_validation->set_rules('end_date','end date is required','required|trim');
		
		if ($this->form_validation->run())
		{
			$bank_account_id = htmlspecialchars(html_escape($this->input->post('bank_account_id')));
			$start_date = htmlspecialchars(html_escape($this->input->post('start_date')));
			$end_date = htmlspecialchars(html_escape($this->input->post('end_date')));

			$start_date = str_replace('/','-',$start_date);
			$end_date 	= str_replace('/','-',$end_date);

			$start_date = date_create($start_date);
			$start_date = date_format($start_date,'Y-m-d');
//			echo $start_date;

			$end_date = date_create($end_date);
			$end_date = date_format($end_date,'Y-m-d');
            
            if($bank_account_id == -1){
                $response = $this->FeeChallan_model->get_online_paid_challan (-1,$start_date,$end_date);
            }else{
			$response = $this->FeeChallan_model->get_cmd_record ($bank_account_id,$start_date,$end_date);
        }
			if (is_array($response) || is_object($response))
			{
//				http_response_code(202);

				echo json_encode($response);
//				prePrint($records);
			}
			else
			{
//				http_response_code(406);
				echo ("Record not found...");
			}
		}
	}//function
	
	public function import_ledger (){

		$user 		= $this->session->userdata($this->SessionName);
		$user_role 	= $this->session->userdata($this->user_role);
		$user_id 	= $user['USER_ID'];
		$role_id 	= $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);

		$bank_accounts = $this->FeeChallan_model->get_bank_account();

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['bank_account'] = $bank_accounts;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('admin/create_candidate_payment_account',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function import_ledger_handler (){
            
		$this->form_validation->set_rules('bank_account_id','bank account is required','integer|trim');
		$this->form_validation->set_rules('start_date','start date is required','required|trim');
		$this->form_validation->set_rules('end_date','end date is required','required|trim');

		if ($this->form_validation->run()){

			$bank_account_id= htmlspecialchars(html_escape($this->input->post('bank_account_id')));
			$start_date 	= (($this->input->post('start_date')));
			$end_date 		= htmlspecialchars(html_escape($this->input->post('end_date')));

			$start_date = getDateForDatabase($start_date);
			$end_date 	= getDateForDatabase($end_date);
            
            if($bank_account_id == '-1'){
                $response = $this->FeeChallan_model->get_online_paid_challan (21,$start_date,$end_date);
                $response1 = $this->FeeChallan_model->get_online_paid_challan (22,$start_date,$end_date);
                $response = array_merge($response,$response1);
            }else{
			    $response = $this->FeeChallan_model->get_cmd_record ($bank_account_id,$start_date,$end_date);
            }
            //prePrint($response);
            //exit("HELLO");
			if (empty($response)) exit("data not found...");
			$date = date ("Y-m-d");

			$main_array = array();
			$records = $response;
			foreach ($records as $record):

				$Branch_Code = isset($record['BRANCH_CODE'])?$record['BRANCH_CODE']:'.';
				$Branch_Name = isset($record['BRANCH_NAME'])?$record['BRANCH_NAME']:'.';
				$Deposit_Slip_No = isset($record['DEPOSIT_SLIP_NO'])?$record['DEPOSIT_SLIP_NO']:'.';
				$Collection_Date = isset($record['COLLECTION_DATE'])?$record['COLLECTION_DATE']:'.';
				$Mode_of_Payment = isset($record['MODE_OF_PAYMENT'])?$record['MODE_OF_PAYMENT']:'.';
				$Instrument_No = isset($record['INSTRUMENT_NO'])?$record['INSTRUMENT_NO']:'.';
				$Amount = isset($record['AMOUNT'])?$record['AMOUNT']:'.';
				$Credit_Date = isset($record['CREDIT_DATE'])?$record['CREDIT_DATE']:'.';
				$Challan_No = isset($record['CHALLAN_NO'])?$record['CHALLAN_NO']:'.';
				$Challan_No_Description = isset($record['CANDIDATE_NAME'])?$record['CANDIDATE_NAME']:'.';
				$father_name = isset($record['FNAME'])?$record['FNAME']:'.';
				$BATCH_ID = isset($record['ROLL_NO'])?$record['ROLL_NO']:'.';
				$Program_Class = isset($record['PROGRAM'])?$record['PROGRAM']:'.';
				$campus_name = isset($record['CAMPUS_NAME'])?$record['CAMPUS_NAME']:'.';
				$BANK_ACCOUNT_ID = isset($record['BANK_ACCOUNT_ID'])?$record['BANK_ACCOUNT_ID']:'0';

				$Amount = str_replace(',','',$Amount);

				$array = array(
					"BRANCH_CODE"=>$Branch_Code,
					"BRANCH_NAME"=>$Branch_Name,
					"DEPOSIT_SLIP_NO"=>$Deposit_Slip_No,
					"COLLECTION_DATE"=>$Collection_Date,
					"MODE_OF_PAYMENT"=>$Mode_of_Payment,
					"INSTRUMENT_NO"=>$Instrument_No,
					"AMOUNT"=>$Amount,
					"CREDIT_DATE"=>$Credit_Date,
					"CHALLAN_NO"=>$Challan_No,
					"CANDIDATE_NAME"=>$Challan_No_Description,
					"FNAME"=>$father_name,
					"ROLL_NO"=>$BATCH_ID,
					"PROGRAM"=>$Program_Class,
					"CAMPUS_NAME"=>$campus_name,
					"IMPORT_DATE"=>$date,
					"REMARKS"=>'',
					"BANK_ACCOUNT_ID"=>$BANK_ACCOUNT_ID,
				);
				array_push($main_array,$array);
			endforeach;
           // prePrint($main_array);
        //    exit();
			$response = $this->FeeChallan_model->import_fee_ledger($main_array);
//			$response = true;
			if ($response <> false) {
//				http_response_code(202);
				$file_path = base_url().$response;
				echo ("Successfully imported...");
//				echo ("Successfully imported... <a href='$file_path' download> Download CMD failed import log</a>");
//				prePrint($records);
			}
			else
			{
//				http_response_code(406);
				echo ("Import failed...");
			}
		}
	}//function

	public function ledger_logs (){

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
		$this->verify_path($this->script_name,$side_bar_data);
		$logs = directory_map('./fee_import_log/', 1);

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;
		$data['log_files'] = $logs;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('admin/load_fee_ledger_logs',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}
	public function import_challan_ledger(){

        $user 		= $this->session->userdata($this->SessionName);
        $user_role 	= $this->session->userdata($this->user_role);
        $user_id 	= $user['USER_ID'];
        $role_id 	= $user_role['ROLE_ID'];

        $side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
      //  $this->verify_path($this->script_name,$side_bar_data);

        $bank_accounts = $this->FeeChallan_model->get_bank_account();
        $list_of_unverified_challan = $this->FeeChallan_model->get_form_challan();
        $json_data = json_encode($list_of_unverified_challan);


        $data['user'] = $user;
        $data['json_data'] = $json_data;
        $data['profile_url'] = '';
        $data['side_bar_values'] = $side_bar_data;
        $data['script_name'] = $this->script_name;
        $data['bank_account'] = $bank_accounts;

        $this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
        $this->load->view('include/side_bar');
        $this->load->view('include/nav',$data);
        $this->load->view('admin/import_challan_ledger',$data);
        $this->load->view('include/footer_area');
        $this->load->view('include/footer');
    }
    public function import_challan_ledger_handler(){
        $user 		= $this->session->userdata($this->SessionName);
        $user_id 	= $user['USER_ID'];
        $list_of_verified_challan = $this->input->post('list_of_verified_challan');
        $list_of_verified_challan = json_decode($list_of_verified_challan,true);
        if(count($list_of_verified_challan)>0){
            $result = $this->FeeChallan_model->update_form_challan($list_of_verified_challan,$user_id);
            if($result==true){
                $result = array("STATUS"=>"OK","MESSAGE"=>"SUCCESSFULLY UPADTED");
                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($result));
            }else{
                $result = array("STATUS"=>"DB_ERROR","MESSAGE"=>"SOMETHING WENT WRONG IN DATABASE UPDATION");
                $this->output
                    ->set_status_header(500)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($result));
            }
        }else{
            $result = array("STATUS"=>"NOT FOUND","MESSAGE"=>"LIST OF CHALLAN IS EMPTY");
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($result));
        }

    }
   
    public function UploadCmd(){
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
		$data['log_files'] = $logs;

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
		$this->load->view('include/side_bar');
		$this->load->view('include/nav',$data);
		$this->load->view('admin/upload_cmd',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	
    }
   
    public function verifyChallan(){
        $json = file_get_contents('php://input');
        $list_of_challan_no = json_decode($json,true);
		
				
		
        if(count($list_of_challan_no)>0){
			$user_system = $this->session->userdata($this->SessionName);
			$verifier_id = $user_system['USER_ID'];
			$return = $this->FormVerificationModel->UpdateVerifiedChallan($list_of_challan_no,$verifier_id);

			if($return['RETURN'] == 1){

                $c_date=date("Y_m_d_h_i_sa");
                $fileName="cmd_log/SUCCESS_CMD_".$c_date.".csv";

                $this->generateCSV($list_of_challan_no,$fileName);
           $json =json_encode(array("list_of_challan_no"=>$list_of_challan_no,"error_record"=> null));
				$this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($json);

			}else{
                $c_date=date("Y_m_d_h_i_sa");
                $fileName="cmd_log/FAILED_CMD_".$c_date.".csv";

                $this->generateCSV($list_of_challan_no,$fileName);
                
                 $json =json_encode(array("list_of_challan_no"=>$list_of_challan_no,"error_record"=> $return['RECORD']));
                $this->output
            ->set_status_header(500)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($json);
			}
				
        }else{
            $c_date=date("Y_m_d_h_i_sa");
            $fileName="cmd_log/FAILED_CMD_".$c_date.".csv";
            $this->generateCSV($list_of_challan_no,$fileName);
             $json =json_encode(array("list_of_challan_no"=>$list_of_challan_no,"error_record"=> null));
            $this->output
            ->set_status_header(400)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($json);
        }
    }
    
    public function generateCSV($list_of_challan_no,$file_name){
		$file = fopen($file_name,"w");
		foreach($list_of_challan_no as $key=>$row){
			if($key==0){
				$heading=array_keys($row);
				fputcsv($file, $heading);
			}
			
			$arr_value = array_values($row);
			fputcsv($file, $arr_value);
		}
		fclose($file);
	}
	public function getChallanVerify(){

        $json = file_get_contents('php://input');
        $list_of_challan_no = json_decode($json,true);
        $c_date=date("Y_m_d_h_i_sa");
        $fileName="verification_log/CMD_".$c_date.".csv";
        $this->generateCSV($list_of_challan_no,$fileName);

        $invalid_challan_no = array();
        $not_found_challan_no = array();
        $found_challan_no = array();
        $paid_challan_no = array();


        foreach ($list_of_challan_no as $challan_no){
            $dsn = $challan_no['DEPOSITE_SLIP_NO'];
            if(strlen($dsn)>2) {
                if ($dsn[0] == '2' && $dsn[1] == '0') {

                    $FORM_CHALLAN_ID = (int)substr($dsn,2);

                    $row = $this->Application_model->getChallanById($FORM_CHALLAN_ID);
                    if($row){
                        $new_challan = array_merge($challan_no,$row);
                        if($row['IS_VERIFIED']=='Y'){

                            array_push($paid_challan_no,$new_challan);
                        }else{
                            array_push($found_challan_no,$new_challan);
                        }
                    }else{
                        array_push($not_found_challan_no,$challan_no);
                    }

                }else{
                    array_push($invalid_challan_no,$challan_no);
                }
            }else{
                array_push($invalid_challan_no,$challan_no);
            }




        }

        $response =  array("INVALID_CHALLAN_NO"=>$invalid_challan_no,"NOT_FOUND_CHALLAN"=>$not_found_challan_no,"FOUND_CHALLAN"=>$found_challan_no,"PAID_CHALLAN"=>$paid_challan_no);

        $result = json_encode($response);

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($result);
    }


}
