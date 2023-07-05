<?php


class FeeChallan_model extends CI_Model{
	function __construct(){
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
	}
	function get_candidate_admission_challan ($application_id=0,$selection_list_id=0,$challan_no=0,$challan_type_id=0){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('CHALLAN_NO, CHALLAN_AMOUNT, PAYABLE_AMOUNT, VALID_UPTO, ADMIN_USER_ID, PART_ID, SEMESTER_ID,fc.REMARKS,ba.ACCOUNT_NO,ba.ACCOUNT_TITLE AS ACCOUNT_TITLE,FEE_PROG_LIST_ID');
		$this->legacy_db->from ('bank_account ba');
		$this->legacy_db->join('fee_challan fc','ba.BANK_ACCOUNT_ID=fc.BANK_ACCOUNT_ID');
		$this->legacy_db->join('challan_type ct','ct.CHALLAN_TYPE_ID=fc.CHALLAN_TYPE_ID');
		$this->legacy_db->join('selection_list sl','sl.SELECTION_LIST_ID=fc.SELECTION_LIST_ID');
		if ($application_id>0) $this->legacy_db->where ('fc.APPLICATION_ID',$application_id);
		if ($selection_list_id>0) $this->legacy_db->where ('fc.SELECTION_LIST_ID',$selection_list_id);
		if ($challan_no>0) $this->legacy_db->where ('fc.CHALLAN_NO',$challan_no);
		if ($challan_type_id>0) $this->legacy_db->where ('fc.CHALLAN_TYPE_ID',$challan_type_id);
		$this->legacy_db->where ('sl.IS_PROVISIONAL','N');
	    $data = $this->legacy_db->get()->row_array();
// 		print_r($this->legacy_db->last_query());
// 		exit();
			return $data;
	}
	
	function get_candidate_admission_challan_list ($application_id=0,$selection_list_id=0,$challan_no=0,$challan_type_id=0){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('sem.NAME AS SEMESTER_NAME, p.NAME AS PART_NAME,CHALLAN_NO, CHALLAN_AMOUNT, LATE_FEE, DUES, PAYABLE_AMOUNT, VALID_UPTO, ADMIN_USER_ID, p.PART_ID, sem.SEMESTER_ID,fc.REMARKS,ba.ACCOUNT_NO,ba.ACCOUNT_TITLE AS ACCOUNT_TITLE,FEE_PROG_LIST_ID,fc.DATETIME AS CHALLAN_DATE,ct.CHALLAN_TITLE,fc.ACTIVE');
		$this->legacy_db->from ('bank_account ba');
		$this->legacy_db->join('fee_challan fc','ba.BANK_ACCOUNT_ID=fc.BANK_ACCOUNT_ID');
		$this->legacy_db->join('challan_type ct','ct.CHALLAN_TYPE_ID=fc.CHALLAN_TYPE_ID');
		$this->legacy_db->join('semester sem','sem.SEMESTER_ID=fc.SEMESTER_ID');
		$this->legacy_db->join('part p','p.PART_ID=fc.PART_ID');
		if ($application_id>0) $this->legacy_db->where ('fc.APPLICATION_ID',$application_id);
		if ($selection_list_id>0) $this->legacy_db->where ('fc.SELECTION_LIST_ID',$selection_list_id);
		if ($challan_no>0) $this->legacy_db->where ('fc.CHALLAN_NO',$challan_no);
		if ($challan_type_id>0) $this->legacy_db->where ('fc.CHALLAN_TYPE_ID',$challan_type_id);
		$this->legacy_db->order_by('fc.VALID_UPTO ASC');
		$result =  $this->legacy_db->get()->result_array();
	//	print_r($this->legacy_db->last_query());
	//	exit();
		return $result;
	//	print_r($this->legacy_db->last_query());
//		exit();
	}
	
/*
 * Yasir created following methods 19-01-2021
 * */

	protected function check_and_create_candidate_account ($application_id,$record){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->from ('candidate_account');
		$this->legacy_db->where ('APPLICATION_ID',$application_id);
		$row = $this->legacy_db->get()->row_array();
		if ($row){
			return $row['ACCOUNT_ID'];
		}else{
			if ($this->legacy_db->insert('candidate_account',$record)){
				return $this->legacy_db->insert_id();
			}else return  false;
		}
	}

    function get_candidate_account ($application_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->from ('candidate_account');
		$this->legacy_db->where ('APPLICATION_ID',$application_id);
		$row = $this->legacy_db->get()->row_array();
		if ($row){
			return $row;
		}else{
			return 0;
		}
	}
	
	function create_candidate_account ($record){
			$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->insert('candidate_account',$record);
		$last_id = $this->legacy_db->insert_id();
//		echo $this->legacy_db->last_query();
//		echo "<br/>";
//		prePrint($this->legacy_db);
//		exit();
			if ($last_id){
//				echo "YES";
				return $last_id;
			}else{
//				echo "NO";
				return  false;
			}

	}

	public function get_candidate_challan ($challan_no){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('fc.*,a.USER_ID AS USER_ID, a.FORM_DATA AS FORM_DATA,fc.FEE_PROG_LIST_ID AS FEE_PROG_LIST_ID');
		$this->legacy_db->from ('fee_challan fc');
		$this->legacy_db->join('applications a','a.APPLICATION_ID=fc.APPLICATION_ID');
		$this->legacy_db->where ('CHALLAN_NO',$challan_no);
		$row = $this->legacy_db->get()->row_array();
		if ($row) return $row;
		else return  false;
	}
	
	public function get_candidate_challan_ledger_entry ($challan_no){
        ini_set('memory_limit', '-1');
        set_time_limit(1500);
		$this->legacy_db = $this->load->database('admission_db',true);
		//$this->legacy_db->db_debug = false;
		$this->legacy_db->select('fc.*,ur.USER_ID AS USER_ID,fc.FEE_PROG_LIST_ID AS FEE_PROG_LIST_ID,sl.CATEGORY_ID,ur.FIRST_NAME,ur.LAST_NAME,ur.FNAME,sl.SELECTION_LIST_ID AS SELECTION_LIST_ID');
		$this->legacy_db->from ('fee_challan fc');
		$this->legacy_db->join('selection_list sl','fc.SELECTION_LIST_ID=sl.SELECTION_LIST_ID');
		$this->legacy_db->join('applications a','sl.APPLICATION_ID = a.APPLICATION_ID');
		$this->legacy_db->join('users_reg ur','a.USER_ID = ur.USER_ID');
		if ($challan_no>0)$this->legacy_db->where ('CHALLAN_NO',$challan_no);
		$row = $this->legacy_db->get()->result_array();
		//echo $this->legacy_db->last_query();
		//exit();
		if ($row) return $row;
		else return  false;
	}
	
	public function check_challan_ledger_entry ($challan_no){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('fl.CHALLAN_NO');
		$this->legacy_db->from ('fee_ledger fl');
		$this->legacy_db->where ('CHALLAN_NO',$challan_no);
		$row = $this->legacy_db->get()->row_array();
		//echo $this->legacy_db->last_query();
		//exit();
		if ($row) return $row;
		else return  false;
	}

	protected function batch_insert($table,$record){
// 		prePrint($record);
		$this->legacy_db = $this->load->database('admission_db',true);
		if ($this->legacy_db->insert_batch($table,$record)){
//			prePrint($this->legacy_db->last_query());
			return true;
		}else return  false;
	}

	function get_bank_account (){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('BANK_ACCOUNT_ID,ACCOUNT_TITLE,ACCOUNT_NO');
		$this->legacy_db->from ('bank_account');
//		$this->legacy_db->where ('APPLICATION_ID',$application_id);
		$rows = $this->legacy_db->get()->result_array();
		return $rows;
	}

	function insert($data,$table){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->db_debug = false;
		if (! $this->legacy_db->insert($table, $data)){
//			$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT METHOD',$table,11,0);
			return false;
		}else return true;
	}//method

	function import_cmd_table($records){
		$cmd_batch_insert = $this->batch_insert('cmd_report',$records);
		if ($cmd_batch_insert){
			return  true;
		}else{
			return false;
		}
	}//method

	function get_cmd_record ($bank_account_id,$start_date,$end_date){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('BRANCH_CODE,BRANCH_NAME,DEPOSIT_SLIP_NO,COLLECTION_DATE,MODE_OF_PAYMENT,INSTRUMENT_NO,AMOUNT,CREDIT_DATE,CHALLAN_NO,CANDIDATE_NAME,FNAME,ROLL_NO,PROGRAM,CAMPUS_NAME,BANK_ACCOUNT_ID');
		$this->legacy_db->from ('cmd_report');
		if ($bank_account_id>0)$this->legacy_db->where ('BANK_ACCOUNT_ID',$bank_account_id);
		$this->legacy_db->where ("COLLECTION_DATE BETWEEN '$start_date' AND '$end_date'");
		$this->legacy_db->order_by('COLLECTION_DATE');
		$rows = $this->legacy_db->get()->result_array();
		return $rows;
	}
	
	function get_online_paid_challan ($section_account_id,$start_date,$end_date){

// 		$this->legacy_db = $this->load->database('admission_db',true);
		$this->db->select("'0000' AS BRANCH_CODE,'ONLINE' AS BRANCH_NAME,CHALLAN_NO AS DEPOSIT_SLIP_NO,PAID_DATE AS COLLECTION_DATE,'ONLINE' AS MODE_OF_PAYMENT,TRANSACTION_ID AS INSTRUMENT_NO,PAID_AMOUNT AS AMOUNT,PAID_DATE AS CREDIT_DATE,CHALLAN_NO,NAME AS CANDIDATE_NAME,FNAME AS FNAME,ROLL_NO,PROGRAM,CAMPUS_NAME,SECTION_ACCOUNT_ID AS BANK_ACCOUNT_ID");
		$this->db->from ('challan');
		$this->db->where ('IS_PAID',1);
		if ($section_account_id>0)$this->db->where ('SECTION_ACCOUNT_ID',$section_account_id);
		$this->db->where ("PAID_DATE BETWEEN '$start_date' AND '$end_date'");
		$this->db->where("TYPE_CODE IN ('20-001','21-001','21-002','21-003','21-004','21-005','22-001')");
		$this->db->order_by('PAID_DATE');
		$rows = $this->db->get()->result_array();
		return $rows;
	}
	
	function import_fee_ledger($records){

		set_time_limit(-1);

		$records = quicksort($records,'AMOUNT','DESC');
		
    
		$file_name = "fee_import_log/CMD_IMPORT_FAILED_RECORDS_".date("d_m_y_h_i_s_A").".csv";
		$myfile  = fopen($file_name,'w+');
		$heading = array_keys($records[0]);
		
		array_push($heading,'ERROR_MESSAGE');
		fputcsv($myfile,$heading);

		$this->legacy_db = $this->load->database("admission_db",true);
		$this->legacy_db->trans_begin();
	    $this->legacy_db->db_debug = true;

			$transaction = false;
			$candidate_challan_list = $this->get_candidate_challan_ledger_entry(0);
			$candidate_challan_list = merge_list_with_key($candidate_challan_list,'CHALLAN_NO');
			foreach ($records as $record):
				$Challan_No 	= $record['CHALLAN_NO'];
				$candidate_challan = null;
                $candidate_challan_verify = null;
				/*
				 * is this challan exist in fees challan
				 * */
                
				if (isset($candidate_challan_list[$Challan_No])){
					$candidate_challan=$candidate_challan_list[$Challan_No][0];
				}else{
					array_push($record,'FEE_CHALLAN_NOT_EXIST');
					fputcsv($myfile,$record);
					continue;
				}
				
				/*
				 * is this challan exist in fees ledger
				 * */
				$Found_Challan = $this->check_challan_ledger_entry($Challan_No);
				if (!$Found_Challan){
					$candidate_challan_verify = $Challan_No;
				}else{
					array_push($record,'FEE_CHALLAN_ALREADY_EXIST');
					fputcsv($myfile,$record);
					continue;
				}

				$Collection_Date= $record['COLLECTION_DATE'];
				$Credit_Date 	= $record['CREDIT_DATE'];
				$Amount 		= $record['AMOUNT'];
				$Mode_of_Payment= $record['MODE_OF_PAYMENT'];

				$CHALLAN_TYPE_ID = 0;
				$BANK_ACCOUNT_ID = 0;
				$SELECTION_LIST_ID = 0;
				$CHALLAN_AMOUNT = 0;
				$CHALLAN_REMARKS = "";
				$APPLICATION_ID = 0;
				$USER_ID = 0;
				$NAME = "";
				$FNAME = "";
				$SURNAME = "";
				$CHALLAN_DATE = "";

				$ACCOUNT_CREATE_DATE = date('Y-m-d');
				$ACCOUNT_REMARKS = 'CMD-GENERATED';
				if (is_array($candidate_challan)){

					$transaction = true;

					$APPLICATION_ID 	= $candidate_challan['APPLICATION_ID'];
					$USER_ID 			= $candidate_challan['USER_ID'];
					$CHALLAN_AMOUNT		= $candidate_challan['CHALLAN_AMOUNT'];
					$PAYABLE_AMOUNT		= $candidate_challan['PAYABLE_AMOUNT'];
					$CHALLAN_REMARKS	= $candidate_challan['REMARKS'];
					$CHALLAN_DATE		= $candidate_challan['DATETIME'];
					$CHALLAN_TYPE_ID	= $candidate_challan['CHALLAN_TYPE_ID'];
					$BANK_ACCOUNT_ID	= $candidate_challan['BANK_ACCOUNT_ID'];
					$FEE_PROG_LIST_ID	= $candidate_challan['FEE_PROG_LIST_ID'];
					$CATEGORY_ID		= $candidate_challan['CATEGORY_ID'];
					$SELECTION_LIST_ID	= $candidate_challan['SELECTION_LIST_ID'];

					$CATEGORY_LABEL = null;
					if ($CATEGORY_ID == SELF_FINANCE || $CATEGORY_ID == OTHER_PROVINCES_SELF_FINANCE || $CATEGORY_ID ==SPECIAL_SELF_FINANCE_CATEGORY_ID || $CATEGORY_ID ==SELF_FINANCE_EVENING_CATEGORY_ID ) {
						$CATEGORY_LABEL = "N";
					}else{
						$CATEGORY_LABEL = "Y";
					}

					if ($Amount == RETAIN_AMOUNT) {
						$CHALLAN_TYPE_ID=RETAIN_ID;
						$CHALLAN_AMOUNT = RETAIN_AMOUNT;
						$CHALLAN_REMARKS = "RETAINING FEE";
						$PAYABLE_AMOUNT = RETAIN_AMOUNT;
					}

					$NAME 			= $candidate_challan['FIRST_NAME'];
					$FNAME 			= $candidate_challan['FNAME'];
					$SURNAME 		= $candidate_challan['LAST_NAME'];

					$account_create_array = array (
						'USER_ID'=>$USER_ID,
						'APPLICATION_ID'=>$APPLICATION_ID,
						'FIRST_NAME'=>$NAME,
						'FNAME'=>$FNAME,
						'LAST_NAME'=>$SURNAME,
						'DATE'=>$ACCOUNT_CREATE_DATE,
						'REMARKS'=>$ACCOUNT_REMARKS,
					);
                    
					$CANDIDATE_ACCOUNT = $this->get_candidate_account($APPLICATION_ID);
					$ACCOUNT_ID = $CANDIDATE_ACCOUNT['ACCOUNT_ID'];
					$ACCOUNT_STATUS = $CANDIDATE_ACCOUNT['ACTIVE'];

					if ($ACCOUNT_ID == 0){
						if ($Amount == RETAIN_AMOUNT){
							array_push($record,'ONLY_RETAIN_FOUND');
							fputcsv($myfile,$record);
							continue;
						}else{
							$ACCOUNT_ID = $this->create_candidate_account($account_create_array);
							$ACCOUNT_STATUS = 1;
						}
					}
                    
					if ($ACCOUNT_STATUS == 0){
						array_push($record,'ACCOUNT_DEACTIVE');
						fputcsv($myfile,$record);
						continue;
					}

					$candidate_ledger = array(
						'ACCOUNT_ID'=>$ACCOUNT_ID,
						'CHALLAN_TYPE_ID'=>$CHALLAN_TYPE_ID,
						'BANK_ACCOUNT_ID'=>$BANK_ACCOUNT_ID,
						'CHALLAN_NO'=>$candidate_challan_verify,
						'DETAILS'=>$CHALLAN_REMARKS,
						'CHALLAN_AMOUNT'=>$CHALLAN_AMOUNT,
						'PAYABLE_AMOUNT'=>$PAYABLE_AMOUNT,
						'PAID_AMOUNT'=>$Amount,
						'DATE'=>$Collection_Date,
						'REMARKS'=>'AUTO-CMD',
						'FEE_PROG_LIST_ID'=>$FEE_PROG_LIST_ID,
						'IS_MERIT'=>$CATEGORY_LABEL,
						'SELECTION_LIST_ID'=>$SELECTION_LIST_ID,
					);

					if ($ACCOUNT_ID){
						$transaction = true;
							$success_candidate_ledger_entry = $this->insert($candidate_ledger,'fee_ledger');
							if ($success_candidate_ledger_entry) {
								$transaction = true;
							}else{
								$transaction = false;
								array_push($record,'LEDGER_INSERT_FAILED');
								fputcsv($myfile,$record);
//								break;
							}
						}else{
							$transaction = false;
							array_push($record,'CANDIDATE_ACCOUNT_NOT_FOUND');
							fputcsv($myfile,$record);
//							break;
						}
				}else{
					$transaction = false;
					array_push($record,'EMPTY_ARRAY_FEE_CHALLAN');
					fputcsv($myfile,$record);
					continue;
				}
			endforeach;

			if ($transaction){
				$this->legacy_db->trans_commit();
				return  $file_name;
			}else{
				$this->legacy_db->trans_rollback();
				return false;
			}
	}//method
	
	function get_candidate_paid_history ($application_id,$challan_no=0){
		
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('ca.ACCOUNT_ID AS ACCOUNT_ID, ur.USER_ID AS USER_ID, ca.APPLICATION_ID AS APPLICATION_ID, ur.FIRST_NAME, ur.FNAME, ur.LAST_NAME, ca.DATE AS ACCOUNT_DATE, ca.ACTIVE AS ACCOUNT_STATUS, ca.REMARKS AS ACCOUNT_REMARKS,CHALLAN_TYPE_ID,BANK_ACCOUNT_ID,CHALLAN_NO,CHALLAN_AMOUNT, PAYABLE_AMOUNT,PAID_AMOUNT,DETAILS,fl.DATE AS POST_DATE,FEE_PROG_LIST_ID,IS_YES, IS_MERIT');
		$this->legacy_db->from ('candidate_account ca');
		$this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
		$this->legacy_db->join('applications ap','ca.APPLICATION_ID=ap.APPLICATION_ID');
		$this->legacy_db->join('users_reg ur','ur.USER_ID=ap.USER_ID');
		if ($application_id>0) $this->legacy_db->where ('ca.APPLICATION_ID',$application_id);
		if ($challan_no>0) $this->legacy_db->where ('fl.CHALLAN_NO',$challan_no);
		return $this->legacy_db->get()->result_array();
//		print_r($this->legacy_db->last_query());
//		exit();
	}
	
	protected function get_candidate_account_by_different_search ($search_by,$search_value){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('ca.*,ur.FIRST_NAME,ur.LAST_NAME,ur.FNAME');
		$this->legacy_db->from ('candidate_account ca');
		$this->legacy_db->join('applications app','ca.APPLICATION_ID=app.APPLICATION_ID');
			$this->legacy_db->join('users_reg ur','app.USER_ID=ur.USER_ID');
			$this->legacy_db->join('selection_list sl','ca.APPLICATION_ID=sl.APPLICATION_ID');
		if ($search_by == 1)$this->legacy_db->where ('ca.APPLICATION_ID',$search_value);
		if ($search_by == 2)$this->legacy_db->where ('ca.ACCOUNT_ID',$search_value);
		if ($search_by == 3)$this->legacy_db->where ("sl.ROLL_NO LIKE '$search_value'");
		$row = $this->legacy_db->get()->row_array();
		if ($row){
			return $row;
		}else{
			return 0;
		}
	}

	function get_candidate_ledger ($search_by,$search_value,$show_retain){
		$candidate_account = $this->get_candidate_account_by_different_search($search_by,$search_value);
		if ($candidate_account == 0) return 0;

		$ACCOUNT_ID = $candidate_account['ACCOUNT_ID'];
		$condition = [];
		if(empty($show_retain)) {
			$condition = ['fl.ACCOUNT_ID' => $ACCOUNT_ID, 'fl.CHALLAN_TYPE_ID !=' => 2];
		} else {
			$condition = ['fl.ACCOUNT_ID' => $ACCOUNT_ID];
		}
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('fc.DUES,fc.LATE_FEE,p.PART_NO,sem.NAME AS SEMESTER_NAME,p.NAME AS PART_NAME,pl.PROGRAM_TITLE, fl.FEE_LEDGER_ID,ct.CHALLAN_TITLE,fl.CHALLAN_NO,fl.CHALLAN_AMOUNT,fl.PAYABLE_AMOUNT,fl.PAID_AMOUNT,fl.DETAILS,DATE_FORMAT(fl.DATE,"%d/%m/%Y") AS DATE,fl.FEE_PROG_LIST_ID, fl.IS_YES, fl.IS_MERIT,cat.CATEGORY_NAME,fl.SELECTION_LIST_ID AS FEE_LEDGER_SELECTION_LIST_ID,fl.BANK_ACCOUNT_ID AS BANK_ACCOUNT_ID,fl.DATE AS LEDGER_COLLECTION_DATE,fl.CHALLAN_TYPE_ID AS CHALLAN_TYPE_ID,fl.REMARKS');
		$this->legacy_db->from ('fee_ledger fl');
		$this->legacy_db->join('fee_challan fc','fc.CHALLAN_NO=fl.CHALLAN_NO');
		$this->legacy_db->join('selection_list sl','sl.SELECTION_LIST_ID=fl.SELECTION_LIST_ID');		
		$this->legacy_db->join('category cat','sl.CATEGORY_ID=cat.CATEGORY_ID');
		$this->legacy_db->join('challan_type ct','ct.CHALLAN_TYPE_ID=fl.CHALLAN_TYPE_ID');
		$this->legacy_db->join('fee_program_list fpl','fl.FEE_PROG_LIST_ID=fpl.FEE_PROG_LIST_ID');
		$this->legacy_db->join('semester sem','sem.SEMESTER_ID=fpl.SEMESTER_ID');
		$this->legacy_db->join('part p','p.PART_ID=fpl.PART_ID');
		$this->legacy_db->join('program_list pl','pl.PROG_LIST_ID=fpl.PROG_LIST_ID');
		$this->legacy_db->where($condition);
		$this->legacy_db->order_by('fl.CHALLAN_TYPE_ID','ASC');
		$this->legacy_db->order_by('sem.SEMESTER_ID','ASC');
		$this->legacy_db->order_by('fl.DATE','ASC');
		$row = $this->legacy_db->get()->result_array();
		if (count($row)>0){
			$arr = array();
			$arr['PROFILE'] = $candidate_account;
			$arr['LEDGER'] = $row;
			return $arr;
		}else{
			return 0;
		}
	}
	
	function getPaidProgramFeeCandidate ($account_id=0,$application_id=0,$selection_list_id=0){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('SUM(fl.PAID_AMOUNT) AS PAID_AMOUNT');
		$this->legacy_db->from ('candidate_account ca');
		$this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
		$this->legacy_db->where('fl.CHALLAN_TYPE_ID',1);
		$this->legacy_db->where('fl.IS_YES','Y');
		if($account_id>0)$this->legacy_db->where ('ca.ACCOUNT_ID',$account_id);
		if($application_id>0)$this->legacy_db->where ('ca.APPLICATION_ID',$application_id);
		if($selection_list_id>0)$this->legacy_db->where ('fl.SELECTION_LIST_ID',$selection_list_id);
		$row = $this->legacy_db->get()->row_array();
		if (count($row)>0){
			return $row['PAID_AMOUNT'];
		}else{
			return false;
		}
	}

	function update($where,$record,$prev_record,$table){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->trans_begin();

        //echo $this->legacy_db->last_query();

		$this->legacy_db->where($where);
		$this->legacy_db->update($table,$record);
		if($this->legacy_db->affected_rows() >0){
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),$prev_record,$record,"($where) FROM update METHOD",$table,12,0);
			$this->legacy_db->trans_commit();
			return true;
		}else{
			$this->legacy_db->trans_rollback();
			return false;
		}
	}//function
	
	
	//added by kashif shaikh 27-02-2021
    function getPaidProgramFeeCandidateBySelectionListId ($account_id=0,$application_id=0,$selection_list_id=0){

        $this->legacy_db = $this->load->database('admission_db',true);
        $this->legacy_db->select('SUM(fl.PAID_AMOUNT) AS PAID_AMOUNT');
        $this->legacy_db->from ('candidate_account ca');
        $this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
        $this->legacy_db->where('fl.CHALLAN_TYPE_ID in (1,2)');
        $this->legacy_db->where('fl.IS_YES','Y');
        if($account_id>0)$this->legacy_db->where ('ca.ACCOUNT_ID',$account_id);
        if($application_id>0)$this->legacy_db->where ('ca.APPLICATION_ID',$application_id);
        if($selection_list_id>0)$this->legacy_db->where ('fl.SELECTION_LIST_ID',$selection_list_id);
        $row = $this->legacy_db->get()->row_array();
        if (count($row)>0){
            return $row['PAID_AMOUNT'];
        }else{
            return false;
        }
    }
    
    //added by kashif shaikh 07-03-2021
    function getFeeLedgerForCheckingDuplicate($is_special_self = 'N'){
         
         $this->legacy_db = $this->load->database('admission_db',true);
        $this->legacy_db->select('ca.*,fl.*,sl.*,ur.*,al.LIST_NO,pl.PROGRAM_TITLE,cat.CATEGORY_NAME');
        $this->legacy_db->from ('candidate_account ca');
        
        $this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
         $this->legacy_db->join('selection_list sl','sl.SELECTION_LIST_ID=fl.SELECTION_LIST_ID');
         
        $this->legacy_db->join('admission_list AS al', 'sl.`ADMISSION_LIST_ID` = al.`ADMISSION_LIST_ID`');
          $this->legacy_db->join('category AS cat', 'sl.`CATEGORY_ID` = cat.`CATEGORY_ID`');
          $this->legacy_db->join('program_list AS pl', 'sl.`PROG_LIST_ID` = pl.`PROG_LIST_ID`');
           $this->legacy_db->join('applications AS app', 'sl.`APPLICATION_ID` = app.`APPLICATION_ID`');
        $this->legacy_db->join('users_reg AS ur', 'ur.`USER_ID` = app.`USER_ID`');
     
        $this->legacy_db->where('fl.CHALLAN_TYPE_ID in (1,2)');
        $this->legacy_db->where('fl.IS_YES','Y');
        if($is_special_self=='N'){
            $this->legacy_db->where('sl.CATEGORY_ID != '.SPECIAL_SELF_FINANCE_CATEGORY_ID);
        }else if($is_special_self=='Y'){
          $this->legacy_db->where('sl.CATEGORY_ID = '.SPECIAL_SELF_FINANCE_CATEGORY_ID);
        }
        $result = $this->legacy_db->get()->result_array();
        $new_array = array();
        foreach($result as $row){
            $ACCOUNT_ID = $row['ACCOUNT_ID'];
            $SELECTION_LIST_ID = $row['SELECTION_LIST_ID'];
            if(!isset($new_array[$ACCOUNT_ID][$SELECTION_LIST_ID])){
                $new_array[$ACCOUNT_ID][$SELECTION_LIST_ID] = $row;
            }
            
        }
        return $new_array;
    }
    
    function get_challan_data($request) {
        
        $generateby = isValidData($request->generateby);
		if ($generateby == 'generatebyapplication') {
				$application_id = isValidData($request->application_id);
		} elseif ($generateby == 'generatebyprogram') {
				$session_id = isValidData($request->session_id);
				$campus_id	= isValidData($request->campus_id);
				$program_type_id = isValidData($request->program_type_id);
				$shift_id = isValidData($request->part_id);
				$prog_id = isValidData($request->prog_id);
		} elseif ($generateby == 'generatebyselectionlist') {
				$session_id = isValidData($request->session_id);
				$campus_id	= isValidData($request->campus_id);
				$program_type_id = isValidData($request->program_type_id);
				$shift_id = isValidData($request->part_id);
				$prog_id = isValidData($request->prog_id);
				$admission_list_id = isValidData($request->admission_list_id);
				$fee_demerit_id = isValidData($request->fee_demerit_id);
		}

		$query = "SELECT 
                  sl.APPLICATION_ID,
                  1 AS CHALLAN_TYPE_ID,
                  fs.BANK_ACCOUNT_ID,
                  sl.SELECTION_LIST_ID,
                  (fs.CHALLAN_AMOUNT + en_fee.AMOUNT) AS CHALLAN_AMOUNT,
                  ((fs.CHALLAN_AMOUNT + en_fee.AMOUNT) - IFNULL(paid_fee.PAID_AMOUNT, 0)) AS PAYABLE_AMOUNT,
                  '2022-03-21' AS VALID_UPTO,
                  CURDATE() AS DATETIME,
                  (CASE
                      WHEN ((fs.CHALLAN_AMOUNT + en_fee.AMOUNT) <= IFNULL(paid_fee.PAID_AMOUNT, 0)) THEN 'NOT PAYABLE'
                      WHEN (IFNULL(paid_fee.PAID_AMOUNT, 0) > 0) THEN 'DIFFERENCE FEE'
                      WHEN (fs.FEE_DEMERIT_ID = 1) THEN 'FIRST AND SECOND SEMESTER FEE' 
                      WHEN (fs.FEE_DEMERIT_ID = 2) THEN 'FIRST SEMESTER FEE' END
                  ) AS REMARKS,
                  180868 AS ADMIN_USER_ID,
                  fs.PART_ID,
                  fs.SEMESTER_ID,
                  fs.FEE_PROG_LIST_ID,
                  sl.CAMPUS_NAME,
                  cat.CATEGORY_NAME,
                  sl.PROGRAM_TITLE
                FROM
                  selection_list sl 
                  JOIN admission_session ads ON (sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID) 
                  JOIN category cat ON (sl.CATEGORY_ID = cat.CATEGORY_ID) 
                  JOIN program_list pl ON (sl.PROG_LIST_ID = pl.PROG_LIST_ID)
                  JOIN applications app ON (sl.APPLICATION_ID = app.APPLICATION_ID)
                  JOIN users_reg ur ON (app.USER_ID = ur.USER_ID)
                  JOIN 
                    (SELECT 
                      fpl.FEE_PROG_LIST_ID,
                      fpl.CAMPUS_ID,
                      fpl.PROG_LIST_ID,
                      fs.FEE_CATEGORY_TYPE_ID,
                      ba.BANK_ACCOUNT_ID,
                      fpl.PART_ID,
                      fpl.SEMESTER_ID,
                      fpl.FEE_DEMERIT_ID,
                      SUM(fs.AMOUNT) AS CHALLAN_AMOUNT 
                    FROM
                      fee_structure fs 
                      JOIN fee_program_list fpl ON (fpl.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID) 
                      JOIN fee_category_type fct ON (fct.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID) 
                      JOIN bank_account ba ON (ba.BANK_ACCOUNT_ID = fct.BANK_ACCOUNT_ID) 
                    WHERE fs.SESSION_ID = 2 AND fpl.SEMESTER_ID IN (1, 11) AND fpl.PART_ID
                    GROUP BY fs.FEE_CATEGORY_TYPE_ID, fs.FEE_PROG_LIST_ID) AS fs 
                    ON (
                      cat.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID 
                      AND sl.PROG_LIST_ID = fs.PROG_LIST_ID 
                      AND ads.CAMPUS_ID = fs.CAMPUS_ID
                    ) 
                  LEFT JOIN 
                    (SELECT 
                      ca.APPLICATION_ID appid,
                      SUM(fl.PAID_AMOUNT) AS PAID_AMOUNT 
                    FROM
                      fee_ledger fl 
                      JOIN candidate_account ca ON (fl.ACCOUNT_ID = ca.ACCOUNT_ID) 
                    WHERE fl.CHALLAN_TYPE_ID = 1 
                    GROUP BY ca.ACCOUNT_ID) AS paid_fee 
                    ON (sl.APPLICATION_ID = paid_fee.appid) 
                  LEFT JOIN 
                    (SELECT 
                      q.USER_ID,
                      fe.SESSION_ID,
                      fe.AMOUNT 
                    FROM
                      qualifications q 
                      JOIN discipline dis ON (q.DISCIPLINE_ID = dis.DISCIPLINE_ID) 
                      JOIN fee_enrolment fe ON (q.ORGANIZATION_ID = fe.INSTITUTE_ID) 
                    WHERE q.ACTIVE = 1 
                      AND dis.DEGREE_ID = 3) AS en_fee ON (ur.USER_ID = en_fee.USER_ID AND sl.SESSION_ID = en_fee.SESSION_ID) 
                WHERE sl.ADMISSION_LIST_ID = '$admission_list_id' AND sl.IS_PROVISIONAL LIKE 'N'
                ORDER BY ads.CAMPUS_ID, sl.PROGRAM_TITLE, sl.CATEGORY_ID";
        $this->legacy_db = $this->load->database('admission_db',true);
	
		$q = $this->legacy_db->query($query);
	
		$result = $q->result_array();
		return $result;
        
	}

    function get_cmd_record_challan_no ($challan_no){
     
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('BRANCH_CODE,BRANCH_NAME,DEPOSIT_SLIP_NO,COLLECTION_DATE,MODE_OF_PAYMENT,INSTRUMENT_NO,AMOUNT,CREDIT_DATE,CHALLAN_NO,CANDIDATE_NAME,FNAME,ROLL_NO,PROGRAM,CAMPUS_NAME,BANK_ACCOUNT_ID');
		$this->legacy_db->from ('cmd_report');
        $this->legacy_db->where("CHALLAN_NO",$challan_no);
		$this->legacy_db->order_by('COLLECTION_DATE');
		$rows = $this->legacy_db->get()->result_array();
		return $rows;
	}
	
	function getCandidateRetainPaid ($account_id=0,$challan=0,$amount){

        $this->legacy_db = $this->load->database('admission_db',true);
        $this->legacy_db->select('*');
        $this->legacy_db->from ('candidate_account ca');
        $this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
        $this->legacy_db->where('fl.CHALLAN_TYPE_ID in (2)');
        if($account_id>0)$this->legacy_db->where('ca.ACCOUNT_ID',$account_id);
        if($challan>0)$this->legacy_db->where('fl.CHALLAN_NO',$challan);
        if($amount>0)$this->legacy_db->where('fl.CHALLAN_AMOUNT',$amount);
        $row = $this->legacy_db->get()->row_array();
        if (count($row)>0){
            return $row;
        }else{
            return false;
        }
    }
    
    function get_fee_category(){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->from ('fee_category');
		$row = $this->legacy_db->get()->result_array();
		if (count($row)>0){
			return $row;
		}else{
			return false;
		}
	}

	function get_program_fee_paid_details($admission_session_id,$shift_id,$prog_list_id,$category_id,$part_id,$semester_id){

		$this->legacy_db = $this->load->database('admission_db',true);
		$array = array();
		foreach ($prog_list_id as $prog_id) {
			$this->legacy_db->select("pl.`PROGRAM_TITLE`,c.`CATEGORY_NAME`,flsp.TOTAL_CANDIDATES,flsp.CHALLAN_AMOUNT,flsp.PAYABLE_AMOUNT,flsp.PAID_AMOUNT,flsp.BALANCE,flsp.RECEIVABLE_BALANCE");
			$this->legacy_db->from("fee_program_list fpl");
			$this->legacy_db->join("fee_ledger_summary_program flsp","fpl.FEE_PROG_LIST_ID=flsp.FEE_PROG_LIST_ID");
			$this->legacy_db->join("`program_list` pl", "pl.`PROG_LIST_ID`=flsp.PROG_LIST_ID");
			$this->legacy_db->join("`category` c", "c.`CATEGORY_ID`=flsp.CATEGORY_ID");
			$this->legacy_db->where("flsp.ADMISSION_SESSION_ID", $admission_session_id);
			$this->legacy_db->where("flsp.SHIFT_ID", $shift_id);
			$this->legacy_db->where("pl.PROG_LIST_ID", $prog_id);
			$this->legacy_db->where("fpl.PART_ID", $part_id);
			if ($semester_id>0) $this->legacy_db->where_in("fpl.SEMESTER_ID", $semester_id);
			if (!empty($category_id)) $this->legacy_db->where_in("c.CATEGORY_ID", $category_id);
			$row = $this->legacy_db->get()->result_array();
			if (count($row) > 0) {

				$CHALLAN_AMOUNT		=0;
				$TOTAL_CANDIDATES	=0;
				$PAYABLE_AMOUNT		=0;
				$PAID_AMOUNT		=0;
				$BALANCE			=0;
				$RECEIVABLE_BALANCE	=0;

				foreach($row as $key=>$value){
					$CHALLAN_AMOUNT+=		$value['CHALLAN_AMOUNT'];
					$TOTAL_CANDIDATES+=		$value['TOTAL_CANDIDATES'];
					$PAYABLE_AMOUNT+=		$value['PAYABLE_AMOUNT'];
					$PAID_AMOUNT+=			$value['PAID_AMOUNT'];
					$BALANCE+=				$value['BALANCE'];
					$RECEIVABLE_BALANCE+=	$value['RECEIVABLE_BALANCE'];
				}
				$sum_array = array(
					'SUM_CHALLAN_AMOUNT'=>$CHALLAN_AMOUNT,
					'SUM_TOTAL_CANDIDATES'=>$TOTAL_CANDIDATES,
					'SUM_PAYABLE_AMOUNT'=>$PAYABLE_AMOUNT,
					'SUM_PAID_AMOUNT'=>$PAID_AMOUNT,
					'SUM_BALANCE'=>$BALANCE,
					'SUM_RECEIVABLE_BALANCE'=>$RECEIVABLE_BALANCE,
				);
				 $array[$prog_id]['DATA']=$row;
				 $array[$prog_id]['SUM']=$sum_array;
			}
		}//foreach
		return $array;
	}//end
    
    function get_form_challan(){
        $this->legacy_db = $this->load->database('admission_db',true);
        $this->legacy_db->select('fc.*,app.FORM_STATUS');
        $this->legacy_db->from ('form_challan fc');
        $this->legacy_db->join ('applications app','fc.APPLICATION_ID = app.APPLICATION_ID');
        $this->legacy_db->where('app.ADMISSION_SESSION_ID > 112');
        $this->legacy_db->where("(`IS_VERIFIED` != 'Y' OR `IS_VERIFIED` IS NULL)");
        $list = $this->legacy_db->get()->result_array();
       // echo $this->legacy_db->last_query();
       // prePrint($list);
        $new_list  = array();
        foreach ($list as $obj){
            if(!isset($new_list[$obj['FORM_CHALLAN_ID']])) {
                $new_list[$obj['FORM_CHALLAN_ID']] = $obj;
            }
        }
        return $new_list;
    }
    
    function update_form_challan($list_of_challan , $user_id){

        $this->legacy_db = $this->load->database('admission_db',true);
        $transaction = false;
        $this->legacy_db->trans_begin();
        foreach ($list_of_challan as $challan){
            $c_date = date('Y-m-d');
            $record = array("IS_VERIFIED"=>'Y',"PAID"=>"Y","REMARKS"=>"VERIFIED BY CMD USER_ID = ".$user_id,"VERIFIER_ID"=>$user_id,"VERIFICATION_DATE"=>$c_date);
            $this->legacy_db->where("FORM_CHALLAN_ID",$challan['FORM_CHALLAN_ID']);
            $this->legacy_db->update("form_challan",$record);
            $form_challan_row = $this->legacy_db->affected_rows();



            $form_status = json_decode($challan['FORM_STATUS'],true);
            $form_status['CHALLAN']= array("STATUS"=>"VERIFIED","REMARKS"=>"AUTO VERIFIED");
            $form_status =  json_encode($form_status);
            $record = array("FORM_STATUS"=>$form_status);

            $this->legacy_db->where("APPLICATION_ID",$challan['APPLICATION_ID']);
            $this->legacy_db->update("applications",$record);

            $applications_row = $this->legacy_db->affected_rows();

            if($form_challan_row >0&&$applications_row >0){
                $transaction = true;
            }else{
                $transaction = false;
            }

        }

        if($transaction == true){
            $this->legacy_db->trans_commit();
            $this->log_model->create_log(0,0,"","","update form challan status successfullty","form_challan",27,$user_id);

            return true;
        }else{
            $this->legacy_db->trans_rollback();
            $this->log_model->create_log(0,0,"","","update form challan status failed","form_challan",27,$user_id);

            return false;
        }
    }
    
    function get_form_challan_for_verification($challan_status = null,$limit=null,$offset=null,$branch_code=null){
        $this->legacy_db = $this->load->database('admission_db',true);
        $this->legacy_db->select('fc.*,app.FORM_STATUS,ur.FIRST_NAME,ur.LAST_NAME,ur.FNAME,bi.*');
        $this->legacy_db->from ('form_challan fc');
        $this->legacy_db->join ('applications app','fc.APPLICATION_ID = app.APPLICATION_ID');
         $this->legacy_db->join ('users_reg ur','ur.USER_ID = app.USER_ID');
          $this->legacy_db->join ('bank_information bi','bi.BRANCH_ID = fc.BRANCH_ID');
        $this->legacy_db->where('app.ADMISSION_SESSION_ID BETWEEN 120 and 124');
        //$this->legacy_db->where('app.STATUS_ID = 1');
        $this->legacy_db->where("app.IS_DELETED = 'N'");
        $this->legacy_db->where("app.STATUS_ID != 6");
        $this->legacy_db->where('app.USER_ID > 0');
        if($challan_status==null || $challan_status == 'N'){
        $this->legacy_db->where("(`IS_VERIFIED` = 'N' OR `IS_VERIFIED` IS NULL)");    
        }else{
            $this->legacy_db->where("(`IS_VERIFIED` = '$challan_status')");
        }
        if($branch_code!=null){
             $this->legacy_db->where("bi.BRANCH_CODE LIKE '%$branch_code%'");
        }
        if($limit!=null&&$offset!=null){
            $this->legacy_db->limit($limit, $offset);
        }elseif($limit!=null){
            $this->legacy_db->limit($limit);
        }
        
        $this->legacy_db->where("CHALLAN_IMAGE IS NOT NULL");
         $this->legacy_db->order_by('fc.CHALLAN_DATE');
        $list = $this->legacy_db->get()->result_array();
        // echo $this->legacy_db->last_query();
        // exit();
       // prePrint($list);
        $new_list  = array();
        foreach ($list as $obj){
            if(!isset($new_list[$obj['FORM_CHALLAN_ID']])) {
                $new_list[$obj['FORM_CHALLAN_ID']] = $obj;
            }
        }
        return $new_list;
    }
    function get_cmd_unmatch(){
          $this->legacy_db = $this->load->database('admission_db',true);
        $this->legacy_db->select('*');
        $this->legacy_db->from ('cmd_report_unmatched cru');
        $list = $this->legacy_db->get()->result_array();
        return($list);
    }
    
    function get_fee_structure($session_id,$campus_id,$program_id,$part_id,$semester_id) {
        $query = "SELECT c.NAME AS CAMPUS_NAME, pl.PROGRAM_TITLE,fct.FEE_TYPE_TITLE,fc.CATEGORY_TITLE,fpl.FEE_PROG_LIST_ID,fpl.CAMPUS_ID,fpl.PROG_LIST_ID,fs.FEE_CATEGORY_TYPE_ID,ba.BANK_ACCOUNT_ID,fpl.PART_ID,fpl.SEMESTER_ID,fpl.FEE_DEMERIT_ID,fs.AMOUNT
        FROM fee_structure fs 
        JOIN fee_program_list fpl ON (fpl.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID) 
        JOIN campus c ON (c.CAMPUS_ID = fpl.CAMPUS_ID)
        JOIN program_list pl ON (pl.PROG_LIST_ID = fpl.PROG_LIST_ID)
        JOIN fee_category_type fct ON (fct.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID) 
        JOIN fee_category fc ON (fc.FEE_CATEGORY_ID = fs.FEE_CATEGORY_ID)
        JOIN bank_account ba ON (ba.BANK_ACCOUNT_ID = fct.BANK_ACCOUNT_ID) 
        WHERE fs.SESSION_ID = '$session_id' 
        AND fpl.CAMPUS_ID = '$campus_id'
        AND fpl.PROG_LIST_ID = '$program_id'
        AND fpl.PART_ID = '$part_id'
        AND fpl.SEMESTER_ID = '$semester_id'";
        $this->legacy_db = $this->load->database('admission_db',true);
	
		$q = $this->legacy_db->query($query);
	
		$result = $q->result_array();
		return $result;
    }
  
  	function get_paid_challan ($account_id=0,$challan_no=0){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->from ('candidate_account ca');
		$this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
//		$this->legacy_db->where('fl.CHALLAN_TYPE_ID',1);
//		$this->legacy_db->where('fl.IS_YES','Y');
		if($account_id>0)$this->legacy_db->where ('ca.ACCOUNT_ID',$account_id);
		if($challan_no>0)$this->legacy_db->where ('fl.CHALLAN_NO',$challan_no);
		$row = $this->legacy_db->get()->row_array();
		if ($row){
			return $row;
		}else{
			return false;
		}
	}//method
	
	/*
    * Aftab created following methods 14-04-2022
    * */
    function getFeeProgramList($campus_id,$program_type_id,$shift_id,$prog_list_id,$fee_demerit_id,$part_id,$semester_id){
		$condition = array('fpl.CAMPUS_ID' => $campus_id, 'fpl.PROGRAM_TYPE_ID' => $program_type_id, 'fpl.SHIFT_ID' => $shift_id, 'fpl.PROG_LIST_ID' => $prog_list_id, 'fpl.FEE_DEMERIT_ID' => $fee_demerit_id, 'fpl.PART_ID' => $part_id, 'fpl.SEMESTER_ID' => $semester_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$record = $this->legacy_db->get_where('fee_program_list fpl',$condition);
		return $record->row();
	}
	
    
    function getChallanByApplicationID($application_id,$fee_demerit_id,$part_id,$semester_id) {

		$query = "";
        $this->legacy_db = $this->load->database('admission_db',true);
	
		$q = $this->legacy_db->query($query);
	
		$result = $q->result_array();
		return $result;
        
	}
	
	function getChallanByProgram($session_id,$fee_prog_list_id){
	    
	    $this->legacy_db = $this->load->database('admission_db',true); 
		$query = $this->legacy_db->query("SELECT 
  fcb.CHALLAN_NO,
  app.APPLICATION_ID,
  sl.ROLL_NO,
  sl.SELECTION_LIST_ID,
  ur.FIRST_NAME,
  ur.FNAME,
  ur.LAST_NAME,
  pl.PROGRAM_TITLE,
  cat.CATEGORY_NAME,
  fpl.FEE_PROG_LIST_ID,
  fpl.PART_ID,
  fpl.SEMESTER_ID,
  fct.FEE_CATEGORY_TYPE_ID
  
FROM applications app
JOIN users_reg ur ON(app.USER_ID = ur.USER_ID)
JOIN admission_session ads ON(app.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID)
JOIN sessions se ON(ads.SESSION_ID = se.SESSION_ID)
JOIN campus c ON(c.CAMPUS_ID = ads.CAMPUS_ID)
JOIN selection_list sl ON(app.APPLICATION_ID = sl.APPLICATION_ID)
JOIN program_list pl ON(sl.PROG_LIST_ID = pl.PROG_LIST_ID)
JOIN shift sh ON(sl.SHIFT_ID = sh.SHIFT_ID)
JOIN shift_program_mapping spm ON(spm.CAMPUS_ID = ads.CAMPUS_ID AND spm.SHIFT_ID = sl.SHIFT_ID AND spm.PROG_LIST_ID = pl.PROG_LIST_ID)
JOIN fee_program_list fpl ON(ads.CAMPUS_ID = fpl.CAMPUS_ID AND ads.PROGRAM_TYPE_ID = fpl.PROGRAM_TYPE_ID AND sl.SHIFT_ID = fpl.SHIFT_ID AND pl.PROG_LIST_ID = fpl.PROG_LIST_ID)
JOIN semester sem ON(fpl.SEMESTER_ID = sem.SEMESTER_ID)
JOIN category cat ON(sl.CATEGORY_ID = cat.CATEGORY_ID)
JOIN fee_category_type fct ON(cat.FEE_CATEGORY_TYPE_ID = fct.FEE_CATEGORY_TYPE_ID)
JOIN fee_challan_bank fcb ON(sl.SELECTION_LIST_ID = fcb.SELECTION_LIST_ID AND fpl.FEE_PROG_LIST_ID = fcb.FEE_PROG_LIST_ID)

		WHERE se.SESSION_ID = $session_id AND fpl.FEE_PROG_LIST_ID = $fee_prog_list_id");
        $records = $query->result();		
		return  $records;
	}
	
	function getChallanBySelectionList($campus_id,$program_type_id,$shift_id,$fee_demerit_id,$admission_list_id) {

		$degree = null;
	    if($program_type_id = 1) {
	        $degree = 3;
	    } else {
	        $degree = 4;
	    }
	    $query = "SELECT @CHALLAN_NO:=@CHALLAN_NO+1 AS CHALLAN_NO, result.* FROM (
        SELECT
          sl.APPLICATION_ID,
          sl.FIRST_NAME,
          sl.FNAME,
          sl.LAST_NAME,
          sl.PROGRAM_TITLE,
          cat.CATEGORY_NAME,
          1 AS CHALLAN_TYPE_ID,
          fs.BANK_ACCOUNT_ID,
          sl.SELECTION_LIST_ID,
          (CASE
            WHEN sl.SHIFT_ID = 1 THEN fs.CHALLAN_AMOUNT + en_fee.AMOUNT
            WHEN sl.SHIFT_ID = 2 THEN fs.CHALLAN_AMOUNT END) AS CHALLAN_AMOUNT,
          (CASE
            WHEN sl.SHIFT_ID = 1 THEN fs.CHALLAN_AMOUNT + en_fee.AMOUNT
            WHEN sl.SHIFT_ID = 2 THEN fs.CHALLAN_AMOUNT END) AS INSTALLMENT_AMOUNT,
          (CASE
            WHEN sl.SHIFT_ID = 1 THEN ((fs.CHALLAN_AMOUNT + en_fee.AMOUNT) - IFNULL(paid_fee.PAID_AMOUNT, 0)) - (fs.CHALLAN_AMOUNT + en_fee.AMOUNT)
            WHEN sl.SHIFT_ID = 2 THEN (fs.CHALLAN_AMOUNT - IFNULL(paid_fee.PAID_AMOUNT, 0)) - fs.CHALLAN_AMOUNT END) AS DUES,
          0.00 AS LATE_FEE,
          (CASE
            WHEN sl.SHIFT_ID = 1 THEN ((fs.CHALLAN_AMOUNT + en_fee.AMOUNT) - IFNULL(paid_fee.PAID_AMOUNT, 0))
            WHEN sl.SHIFT_ID = 2 THEN (fs.CHALLAN_AMOUNT - IFNULL(paid_fee.PAID_AMOUNT, 0)) END) AS PAYABLE_AMOUNT,
          '2022-04-22' AS VALID_UPTO,
          CURDATE() AS DATETIME,
          (CASE
              WHEN sl.SHIFT_ID = 1 AND ((fs.CHALLAN_AMOUNT + en_fee.AMOUNT) <= IFNULL(paid_fee.PAID_AMOUNT, 0)) THEN 'NOT PAYABLE'
              WHEN sl.SHIFT_ID = 2 AND (fs.CHALLAN_AMOUNT <= IFNULL(paid_fee.PAID_AMOUNT, 0)) THEN 'NOT PAYABLE'
              WHEN (IFNULL(paid_fee.PAID_AMOUNT, 0) > 0) THEN 'DIFFERENCE FEE'
              WHEN (fs.FEE_DEMERIT_ID = 1) THEN 'FIRST AND SECOND SEMESTER FEE' 
              WHEN (fs.FEE_DEMERIT_ID = 2) THEN 'FIRST SEMESTER FEE' END
          ) AS REMARKS,
          180868 AS ADMIN_USER_ID,
          fs.PART_ID,
          fs.SEMESTER_ID,
          fs.FEE_PROG_LIST_ID
        FROM
          selection_list sl 
          JOIN admission_session ads ON (sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID) 
          JOIN category cat ON (sl.CATEGORY_ID = cat.CATEGORY_ID) 
          JOIN program_list pl ON (sl.PROG_LIST_ID = pl.PROG_LIST_ID) 
          JOIN applications app ON (sl.APPLICATION_ID = app.APPLICATION_ID)
          JOIN users_reg ur ON (app.USER_ID = ur.USER_ID)
          JOIN 
            (SELECT 
              fpl.FEE_PROG_LIST_ID,
              fpl.CAMPUS_ID,
              fpl.PROG_LIST_ID,
              fs.FEE_CATEGORY_TYPE_ID,
              ba.BANK_ACCOUNT_ID,
              fpl.PART_ID,
              fpl.SEMESTER_ID,
              fpl.FEE_DEMERIT_ID,
              SUM(fs.AMOUNT) AS CHALLAN_AMOUNT 
            FROM
              fee_structure fs 
              JOIN fee_program_list fpl ON (fpl.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID) 
              JOIN fee_category_type fct ON (fct.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID) 
              JOIN bank_account ba ON (ba.BANK_ACCOUNT_ID = fct.BANK_ACCOUNT_ID) 
            WHERE fs.SESSION_ID = 2 AND fpl.SEMESTER_ID IN (1, 11) AND fpl.PART_ID
            GROUP BY fs.FEE_CATEGORY_TYPE_ID, fs.FEE_PROG_LIST_ID) AS fs 
            ON (
              cat.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID 
              AND sl.PROG_LIST_ID = fs.PROG_LIST_ID 
              AND ads.CAMPUS_ID = fs.CAMPUS_ID
            ) 
          LEFT JOIN 
            (SELECT 
              ca.APPLICATION_ID appid,
              SUM(fl.PAID_AMOUNT) AS PAID_AMOUNT 
            FROM
              fee_ledger fl 
              JOIN candidate_account ca ON (fl.ACCOUNT_ID = ca.ACCOUNT_ID) 
            WHERE fl.CHALLAN_TYPE_ID = 1 
            GROUP BY ca.ACCOUNT_ID) AS paid_fee 
            ON (sl.APPLICATION_ID = paid_fee.appid) 
          LEFT JOIN 
            (SELECT 
              q.USER_ID,
              fe.SESSION_ID,
              fe.AMOUNT 
            FROM
              qualifications q 
              JOIN discipline dis ON (q.DISCIPLINE_ID = dis.DISCIPLINE_ID) 
              JOIN fee_enrolment fe ON (q.ORGANIZATION_ID = fe.INSTITUTE_ID) 
            WHERE q.ACTIVE = 1 
              AND dis.DEGREE_ID = 3) AS en_fee ON (ur.USER_ID = en_fee.USER_ID AND sl.SESSION_ID = en_fee.SESSION_ID) 
        WHERE sl.IS_PROVISIONAL LIKE 'N' AND sl.ACTIVE = 1 AND sl.ADMISSION_LIST_ID = 129
        ORDER BY ads.CAMPUS_ID, sl.PROGRAM_TITLE, sl.CATEGORY_ID) AS result, 
        (SELECT @CHALLAN_NO := (SELECT fc.CHALLAN_NO
        			FROM fee_challan fc
        			JOIN selection_list sl ON(fc.SELECTION_LIST_ID = sl.SELECTION_LIST_ID)
        			JOIN admission_session ads ON(sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID)
        			WHERE ads.SESSION_ID = 2 AND fc.CHALLAN_TYPE_ID = 1 AND fc.CHALLAN_NO < 222200000 AND fc.PART_ID IN(1,6,8) AND fc.SEMESTER_ID IN(1,11)
        			ORDER BY fc.CHALLAN_NO DESC LIMIT 0,1)
        ) r";

        $this->legacy_db = $this->load->database('admission_db',true);
		$q = $this->legacy_db->query($query);
	
		$result = $q->result_array();
		return $result;
        
	}
	
	function get_demerit(){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select("*");
		$this->legacy_db->from('fee_demerit');
		return $this->legacy_db->get()->result_array();
	}//method

	function get_fee_program_list($campus_id,$program_type_id,$shift_id,$part_id,$demerit_id,$semester_id){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select("c.CAMPUS_ID,fpl.FEE_PROG_LIST_ID,c.NAME AS CAMPUS_NAME,c.LOCATION AS CAMPUS_LOCATION,pl.PROGRAM_TITLE,pl.PROG_LIST_ID,s.SHIFT_NAME,fd.NAME AS DEMERIT_NAME,p.NAME AS PART_NAME,p.NAME_PHARM,p.REMARKS AS PART_REMARKS,sem.NAME AS SMESTER_NAME, sem.ORDINAL_NUM");
		$this->legacy_db->from('fee_program_list fpl');
		$this->legacy_db->join('campus c','c.CAMPUS_ID=fpl.CAMPUS_ID');
		$this->legacy_db->join('program_type pt','pt.PROGRAM_TYPE_ID=fpl.PROGRAM_TYPE_ID');
		$this->legacy_db->join('shift s','s.SHIFT_ID=fpl.SHIFT_ID');
		$this->legacy_db->join('program_list pl','pl.PROG_LIST_ID=fpl.PROG_LIST_ID');
		$this->legacy_db->join('fee_demerit fd','fd.FEE_DEMERIT_ID =fpl.FEE_DEMERIT_ID');
		$this->legacy_db->join('part p','p.PART_ID =fpl.PART_ID');
		$this->legacy_db->join('semester sem','sem.SEMESTER_ID =fpl.SEMESTER_ID');
		$this->legacy_db->where('fpl.CAMPUS_ID',$campus_id);
		$this->legacy_db->where('fpl.PROGRAM_TYPE_ID',$program_type_id);
		$this->legacy_db->where('fpl.SHIFT_ID',$shift_id);
		$this->legacy_db->where('fpl.FEE_DEMERIT_ID',$demerit_id);
		$this->legacy_db->where('fpl.PART_ID',$part_id);
		$this->legacy_db->where('fpl.SEMESTER_ID',$semester_id);
		$this->legacy_db->order_by('pl.PROGRAM_TITLE','ASC');
		return $this->legacy_db->get()->result_array();
	}//method

	function get_fee_category_type(){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->from ('fee_category_type');
		$row = $this->legacy_db->get()->result_array();
		if (count($row)>0){
			return $row;
		}else{
			return false;
		}
	}

	function get_fee_structure_table($fee_category_type_id,$fee_category_id,$fee_prog_list_id,$session_id,$query_type){

    	switch ($query_type) {
			case 'inserted_rows':
				$this->legacy_db = $this->load->database('admission_db', true);
				$this->legacy_db->select("pt.PROGRAM_TYPE_ID,fs.FEE_STRUCTURE_ID,fct.FEE_CATEGORY_TYPE_ID,fct.FEE_TYPE_TITLE AS FEE_CATEGORY_TYPE_TITLE,fc.FEE_CATEGORY_ID,fc.CATEGORY_TITLE,fs.AMOUNT,c.CAMPUS_ID,fpl.FEE_PROG_LIST_ID,c.NAME AS CAMPUS_NAME,c.LOCATION AS CAMPUS_LOCATION,pl.PROGRAM_TITLE,pl.PROG_LIST_ID,s.SHIFT_NAME,fd.NAME AS DEMERIT_NAME,p.NAME AS PART_NAME,p.NAME_PHARM,p.REMARKS AS PART_REMARKS,sem.NAME AS SMESTER_NAME, sem.ORDINAL_NUM");
				$this->legacy_db->from('fee_program_list fpl');
				$this->legacy_db->join('campus c', 'c.CAMPUS_ID=fpl.CAMPUS_ID');
				$this->legacy_db->join('program_type pt', 'pt.PROGRAM_TYPE_ID=fpl.PROGRAM_TYPE_ID');
				$this->legacy_db->join('shift s', 's.SHIFT_ID=fpl.SHIFT_ID');
				$this->legacy_db->join('program_list pl', 'pl.PROG_LIST_ID=fpl.PROG_LIST_ID');
				$this->legacy_db->join('fee_demerit fd', 'fd.FEE_DEMERIT_ID =fpl.FEE_DEMERIT_ID');
				$this->legacy_db->join('part p', 'p.PART_ID =fpl.PART_ID');
				$this->legacy_db->join('semester sem', 'sem.SEMESTER_ID =fpl.SEMESTER_ID');
				$this->legacy_db->join('fee_structure fs', 'fs.FEE_PROG_LIST_ID=fpl.FEE_PROG_LIST_ID');
				$this->legacy_db->join('fee_category_type fct', 'fct.FEE_CATEGORY_TYPE_ID=fs.FEE_CATEGORY_TYPE_ID');
				$this->legacy_db->join('fee_category fc', 'fc.FEE_CATEGORY_ID=fs.FEE_CATEGORY_ID', "LEFT");
				if ($fee_prog_list_id > 0) $this->legacy_db->where('fpl.FEE_PROG_LIST_ID ', $fee_prog_list_id);
				if ($fee_category_type_id > 0) $this->legacy_db->where('fct.FEE_CATEGORY_TYPE_ID ', $fee_category_type_id);
				if ($session_id > 0) $this->legacy_db->where('fs.SESSION_ID', $session_id);
				if ($fee_category_id > 0) $this->legacy_db->where('fc.FEE_CATEGORY_ID ', $fee_category_id);
				return $this->legacy_db->get()->result_array();
			case 'all_rows':
				$this->legacy_db = $this->load->database('admission_db', true);
				$this->legacy_db->select("fc.FEE_CATEGORY_ID,fc.CATEGORY_TITLE,fs.FEE_STRUCTURE_ID,fct.FEE_CATEGORY_TYPE_ID,fs.FEE_PROG_LIST_ID,fs.SESSION_ID,fs.AMOUNT AS FEE_AMOUNT,fct.FEE_TYPE_TITLE,fct.REMARKS,fct.BANK_ACCOUNT_ID");
				$this->legacy_db->from('fee_category fc');
				$this->legacy_db->join('fee_structure fs', "fc.`FEE_CATEGORY_ID` = fs.`FEE_CATEGORY_ID` 
										  AND fs.`FEE_PROG_LIST_ID` = $fee_prog_list_id 
										  AND fs.`SESSION_ID` = $session_id 
										  AND fs.`FEE_CATEGORY_TYPE_ID` = $fee_category_type_id","LEFT");
				$this->legacy_db->join('fee_category_type fct', 'fs.`FEE_CATEGORY_TYPE_ID` = fct.`FEE_CATEGORY_TYPE_ID`',"LEFT");
				$this->legacy_db->order_by("fc.FEE_CATEGORY_ID");
				return $this->legacy_db->get()->result_array();
			case 'table_rows':
				$this->legacy_db = $this->load->database('admission_db', true);
				$this->legacy_db->select("*");
				$this->legacy_db->from('fee_structure fs');
				if ($fee_category_type_id>0)$this->legacy_db->where('FEE_CATEGORY_TYPE_ID',$fee_category_type_id);
				if ($fee_category_id>0)$this->legacy_db->where('FEE_CATEGORY_ID',$fee_category_id);
				if ($fee_prog_list_id>0)$this->legacy_db->where('FEE_PROG_LIST_ID',$fee_prog_list_id);
				if ($session_id>0)$this->legacy_db->where('SESSION_ID ',$session_id);
				return $this->legacy_db->get()->result_array();
			default:
				return  false;
		}
	}//method

	function fee_enrolment($session_id,$institute_id,$fee_category_id,$fee_enrolment_id,$institute_type_id,$query_type){

    	switch ($query_type){
			case 'get_fee_enrolment_left':
				$this->legacy_db = $this->load->database('admission_db', true);
				$this->legacy_db->select("ins.`INSTITUTE_ID`,ins.`INSTITUTE_NAME`,`fe.`FEE_ENROLMENT_ID`,fe.`AMOUNT`,fe.`REMARKS` AS FEE_ENROLMENT_REMARKS,fe.FEE_CATEGORY_ID");
				$this->legacy_db->from('`institute` ins');
				$this->legacy_db->join('`fee_enrolment` fe',"ins.`INSTITUTE_ID` = fe.`INSTITUTE_ID` AND fe.SESSION_ID=$session_id AND fe.FEE_CATEGORY_ID=$fee_category_id",'LEFT');
				if ($institute_type_id>0)$this->legacy_db->where('ins.`INSTITUTE_TYPE_ID`',$institute_type_id);
				if ($institute_id>0)$this->legacy_db->where('ins.`INSTITUTE_ID`',$institute_id);
				return $this->legacy_db->get()->result_array();
			case 'get_fee_enrolment':
				$this->legacy_db = $this->load->database('admission_db', true);
				$this->legacy_db->select("ins.`INSTITUTE_ID`,ins.`INSTITUTE_NAME`,`fe.`FEE_ENROLMENT_ID`,fe.`AMOUNT`,fe.`REMARKS` AS FEE_ENROLMENT_REMARKS,fe.FEE_CATEGORY_ID");
				$this->legacy_db->from('`institute` ins');
				$this->legacy_db->join('`fee_enrolment` fe',"ins.`INSTITUTE_ID` = fe.`INSTITUTE_ID`");
				if ($institute_type_id>0)$this->legacy_db->where('ins.`INSTITUTE_TYPE_ID`',$institute_type_id);
				if ($institute_id>0)$this->legacy_db->where('ins.`INSTITUTE_ID`',$institute_id);
				if ($session_id>0)$this->legacy_db->where('fe.`SESSION_ID`',$session_id);
				if ($fee_category_id>0)$this->legacy_db->where('fe.`FEE_CATEGORY_ID`',$fee_category_id);
				if ($fee_enrolment_id>0)$this->legacy_db->where('fe.`FEE_ENROLMENT_ID`',$fee_enrolment_id);
				if ($fee_enrolment_id>0){
					return $this->legacy_db->get()->row_array();
				}else{
					return $this->legacy_db->get()->result_array();
				}
		}

	}
	
	function getStudentListForChallan($campus_id,$program_type_id,$shift_id,$fee_demerit_id,$admission_list_id,$prog_list_id,$session_id,$fee_prog_list_id,$case) {
    
    	switch ($case){
			case 'SelectionList':
				if($program_type_id = 1) {
					$degree_id = 3;
				} else {
					$degree_id = [4,5,6];
				}
				$query="SELECT * FROM (SELECT 
				          fcb.CHALLAN_NO,
						  sl.SELECTION_LIST_ID,
						  app.`APPLICATION_ID`,
						  reg.`USER_ID`,
						  sl.`SHIFT_ID`,
						  admss.`ADMISSION_SESSION_ID`,
						  admss.`SESSION_ID`,
						  admss.`PROGRAM_TYPE_ID`,
						  reg.`FIRST_NAME`,
						  reg.FNAME,
						  reg.`LAST_NAME`,
						  pl.PROG_LIST_ID,
						  pl.`PROGRAM_TITLE`,
						  c.`CATEGORY_ID`,
						  c.`CATEGORY_NAME`,
						  fe.`AMOUNT` AS ENROLMENT_FEE,
						  fct.BANK_ACCOUNT_ID,
						  fct.FEE_CATEGORY_TYPE_ID
						FROM users_reg reg 
						JOIN applications app ON (reg.`USER_ID` = app.`USER_ID` AND app.`IS_DELETED`='N') 
						JOIN selection_list sl ON (app.`APPLICATION_ID` = sl.`APPLICATION_ID`) 
						JOIN admission_session admss ON (sl.`ADMISSION_SESSION_ID` = admss.`ADMISSION_SESSION_ID`) 
						JOIN program_list pl ON (pl.`PROG_LIST_ID` = sl.`PROG_LIST_ID`) 
						JOIN category c ON (c.`CATEGORY_ID` = sl.`CATEGORY_ID`) 
						JOIN fee_category_type fct ON (fct.FEE_CATEGORY_TYPE_ID = c.FEE_CATEGORY_TYPE_ID) 
						JOIN qualifications q ON (reg.`USER_ID` = q.`USER_ID`)
						JOIN discipline dis ON (q.`DISCIPLINE_ID` = dis.`DISCIPLINE_ID`) 
						JOIN degree_program dp ON (dis.`DEGREE_ID` = dp.`DEGREE_ID`) 
						LEFT JOIN fee_enrolment fe ON (q.ORGANIZATION_ID = fe.INSTITUTE_ID AND fe.`SESSION_ID` = admss.`SESSION_ID`)
						JOIN fee_challan_bank fcb ON (app.APPLICATION_ID = fcb.CANDIDATE_ID AND admss.SESSION_ID = fcb.SESSION_ID)
						WHERE sl.`ADMISSION_LIST_ID` = $admission_list_id AND sl.`ACTIVE` = 1 AND sl.IS_PROVISIONAL LIKE 'N' ";
				
				            if ($prog_list_id>0)$query.=" AND sl.PROG_LIST_ID=$prog_list_id";
				            $query.="ORDER BY fcb.CHALLAN_NO, dp.DEGREE_ID DESC) AS gc GROUP BY gc.CHALLAN_NO";
				$this->legacy_db = $this->load->database('admission_db',true);
				$q = $this->legacy_db->query($query);
				$result = $q->result_array();
				return $result;
			case 'ProgramWise':
				$query="SELECT fcb.CHALLAN_NO, sl.ROLL_NO, sl.SELECTION_LIST_ID, app.APPLICATION_ID, reg.USER_ID, sl.SHIFT_ID, admss.ADMISSION_SESSION_ID, admss.SESSION_ID, admss.PROGRAM_TYPE_ID, reg.FIRST_NAME, reg.FNAME, reg.LAST_NAME, sl.PROG_LIST_ID, pl.PROGRAM_TITLE, c.CATEGORY_ID, c.CATEGORY_NAME, fct.BANK_ACCOUNT_ID, fct.FEE_CATEGORY_TYPE_ID, fcb.FEE_PROG_LIST_ID, fcb.CHALLAN_NO AS OLD_CHALLAN_AMOUNT, prt.PART_NO
					FROM users_reg reg 
					JOIN applications app ON (reg.USER_ID = app.USER_ID AND app.IS_DELETED = 'N') 
					JOIN selection_list sl ON (app.APPLICATION_ID = sl.APPLICATION_ID) 
					JOIN admission_session admss ON (sl.ADMISSION_SESSION_ID = admss.ADMISSION_SESSION_ID) 
					JOIN program_list pl ON (pl.PROG_LIST_ID = sl.PROG_LIST_ID) 
					JOIN category c ON (c.CATEGORY_ID = sl.CATEGORY_ID) 
					JOIN fee_category_type fct ON (fct.FEE_CATEGORY_TYPE_ID = c.FEE_CATEGORY_TYPE_ID) 
					JOIN fee_challan_bank fcb ON (fcb.SELECTION_LIST_ID = sl.SELECTION_LIST_ID)
					JOIN fee_program_list fpl ON (fcb.FEE_PROG_LIST_ID = fpl.FEE_PROG_LIST_ID)
					JOIN part prt ON (fpl.PART_ID = prt.PART_ID)
					WHERE pl.PROG_LIST_ID = $prog_list_id
						AND sl.SHIFT_ID = $shift_id
						AND admss.CAMPUS_ID = $campus_id
						AND admss.SESSION_ID = $session_id
						AND admss.PROGRAM_TYPE_ID = $program_type_id
						AND fpl.FEE_PROG_LIST_ID = $fee_prog_list_id
						AND sl.ACTIVE = 1 
						AND sl.IS_PROVISIONAL LIKE 'N' 
						AND sl.IS_ENROLLED LIKE 'Y'
				    ORDER BY sl.ROLL_NO_CODE";
//				exit($query);
				$this->legacy_db = $this->load->database('admission_db',true);
				$q = $this->legacy_db->query($query);
				$result = $q->result_array();
				return $result;
			default:
				return false;
    	}
	}//function

	function get_sum_fee_structure_amount($campus_id,$prog_type_id,$shift_id,$prog_list_id,$part_id,$semester_id,$session_id,$fee_demerit_id,$fee_category_type_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('fpl.FEE_PROG_LIST_ID,fpl.FEE_DEMERIT_ID,fpl.PART_ID,fpl.SEMESTER_ID,SUM(fs.AMOUNT) AS AMOUNT,sem.NAME AS SEMESTER_NAME');
		$this->legacy_db->from('fee_program_list fpl');
		$this->legacy_db->join('fee_structure fs','fpl.FEE_PROG_LIST_ID =fs.FEE_PROG_LIST_ID');
		$this->legacy_db->join('semester sem','fpl.SEMESTER_ID = sem.SEMESTER_ID');
		$this->legacy_db->where('fpl.CAMPUS_ID',$campus_id);
		$this->legacy_db->where('fpl.PROGRAM_TYPE_ID',$prog_type_id);
		$this->legacy_db->where('fpl.SHIFT_ID',$shift_id);
		$this->legacy_db->where('fpl.PROG_LIST_ID',$prog_list_id);
		$this->legacy_db->where('fpl.FEE_DEMERIT_ID',$fee_demerit_id);
		$this->legacy_db->where_in('fpl.PART_ID',$part_id);
		$this->legacy_db->where_in('fpl.SEMESTER_ID',$semester_id);
		$this->legacy_db->where('fs.SESSION_ID',$session_id);
		$this->legacy_db->where('fs.FEE_CATEGORY_TYPE_ID ',$fee_category_type_id);
		return $this->legacy_db->get()->row_array();
	}
	
	function get_total_fee_structure_amount($campus_id,$prog_type_id,$shift_id,$prog_list_id,$session_id,$fee_category_type_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('SUM(AMOUNT) AS TOTAL_AMOUNT');
		$this->legacy_db->from('fee_program_list fpl');
		$this->legacy_db->join('fee_structure fs','fpl.FEE_PROG_LIST_ID =fs.FEE_PROG_LIST_ID');
		$this->legacy_db->where('fpl.CAMPUS_ID',$campus_id);
		$this->legacy_db->where('fpl.PROGRAM_TYPE_ID',$prog_type_id);
		$this->legacy_db->where('fpl.SHIFT_ID',$shift_id);
		$this->legacy_db->where('fpl.PROG_LIST_ID',$prog_list_id);
		$this->legacy_db->where('fs.SESSION_ID',$session_id);
		$this->legacy_db->where('fs.FEE_CATEGORY_TYPE_ID ',$fee_category_type_id);
		return $this->legacy_db->get()->row_array();
	}
	
	function get_due_fee($campus_id,$program_type_id,$shift_id,$prog_list_id,$fee_category_type_id,$session_id,$part_id,$semester){
	    //prePrint($semester);
        //exit();
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('SUM(fs.AMOUNT) AS TOTAL_AMOUNT');
		$this->legacy_db->from('fee_program_list fpl');
		$this->legacy_db->join('fee_structure fs','fpl.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID');
		$this->legacy_db->where_in('fpl.SEMESTER_ID',$semester);
		$this->legacy_db->where('fpl.CAMPUS_ID',$campus_id);
	//	$this->legacy_db->where('fpl.PROGRAM_TYPE_ID',$prog_type_id);
		$this->legacy_db->where('fpl.SHIFT_ID',$shift_id);
		$this->legacy_db->where('fpl.PROG_LIST_ID',$prog_list_id);
		$this->legacy_db->where('fs.SESSION_ID',$session_id);
		$this->legacy_db->where('fs.FEE_CATEGORY_TYPE_ID ',$fee_category_type_id);
		
		return $this->legacy_db->get()->row_array();
	}
	
	function get_pre_challan_paid($part,$selection_list_id){
	    $this->legacy_db = $this->load->database('admission_db',true);
	    $this->legacy_db->select('fpl.PART_ID');
	    $this->legacy_db->from('fee_ledger fl');
	    $this->legacy_db->join('candidate_account ca','fl.ACCOUNT_ID = ca.ACCOUNT_ID');
	    $this->legacy_db->join('fee_program_list fpl','fl.FEE_PROG_LIST_ID = fpl.FEE_PROG_LIST_ID');
	    $this->legacy_db->join('part prt','fpl.PART_ID = prt.PART_ID');
	    $this->legacy_db->where('ca.ACTIVE',1);
	    $this->legacy_db->where('fl.IS_YES','Y');
	    $this->legacy_db->where('fl.CHALLAN_TYPE_ID',1);
	    $this->legacy_db->where('fl.SELECTION_LIST_ID',$selection_list_id);
	    $this->legacy_db->where('prt.PART_NO',$part);
	    $this->legacy_db->group_by('fl.SELECTION_LIST_ID');
	    return $this->legacy_db->get()->row_array();
	}

	function get_candidate_paid_amount ($application_id,$account_id,$is_active,$challan_type_id,$is_yes){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('SUM(PAID_AMOUNT) AS PAID_AMOUNT');
		$this->legacy_db->from ('candidate_account ca');
		$this->legacy_db->join('fee_ledger fl','ca.ACCOUNT_ID=fl.ACCOUNT_ID');
		if ($application_id>0) $this->legacy_db->where ('ca.APPLICATION_ID',$application_id);
		if ($account_id>0) $this->legacy_db->where ('ca.ACCOUNT_ID',$account_id);
		if ($is_active==1) $this->legacy_db->where ('ca.ACTIVE',1);
		if ($challan_type_id>0) $this->legacy_db->where_in ('fl.CHALLAN_TYPE_ID',$challan_type_id);
		if ($is_yes !=null) $this->legacy_db->where ('fl.IS_YES',$is_yes);
		return $this->legacy_db->get()->row_array();
//		print_r($this->legacy_db->last_query());
//		exit();
	}

	function get_last_challan_no (){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('MAX(CHALLAN_NO) AS CHALLAN_NO');
		$this->legacy_db->from ('fee_challan');
		$row = $this->legacy_db->get()->row_array();
		return $row['CHALLAN_NO'];
//		print_r($this->legacy_db->last_query());
//		exit();
	}

	function get_challan_type(){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select("*");
		$this->legacy_db->from('challan_type');
		return $this->legacy_db->get()->result_array();
	}//method

    function get_candidate_dues ($session_id,$fee_category_type_id,$campus_id,$prog_type_id,$shift_id,$prog_list_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('fs.SESSION_ID, fct.FEE_CATEGORY_TYPE_ID, fpl.CAMPUS_ID, fpl.PROGRAM_TYPE_ID, fpl.SHIFT_ID, fpl.PROG_LIST_ID, SUM(fs.AMOUNT) AS TOTAL_FEE');
		$this->legacy_db->from('fee_structure fs');
		$this->legacy_db->join('fee_program_list fpl','fpl.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID');
		$this->legacy_db->join('fee_category_type fct','fct.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID');
		$this->legacy_db->join('fee_category fc','fc.FEE_CATEGORY_ID = fs.FEE_CATEGORY_ID');
		$this->legacy_db->join('campus c','c.CAMPUS_ID = fpl.CAMPUS_ID');
		$this->legacy_db->join('program_type pt','pt.PROGRAM_TYPE_ID = fpl.PROGRAM_TYPE_ID');
		$this->legacy_db->join('shift sh','sh.SHIFT_ID = fpl.SHIFT_ID');
		$this->legacy_db->join('program_list pl','pl.PROG_LIST_ID = fpl.PROG_LIST_ID');
		$this->legacy_db->join('fee_demerit fd','fd.FEE_DEMERIT_ID = fpl.FEE_DEMERIT_ID');
		$this->legacy_db->join('part prt','prt.PART_ID = fpl.PART_ID');
		$this->legacy_db->join('semester sem','sem.SEMESTER_ID = fpl.SEMESTER_ID');
		$this->legacy_db->join('shift_program_mapping spm','spm.CAMPUS_ID = c.CAMPUS_ID AND spm.PROGRAM_TYPE_ID = pt.PROGRAM_TYPE_ID AND spm.PROG_LIST_ID = pl.PROG_LIST_ID AND spm.SHIFT_ID = sh.SHIFT_ID');
		$this->legacy_db->join('bank_account ba','ba.BANK_ACCOUNT_ID = fct.BANK_ACCOUNT_ID');
		$this->legacy_db->where('fs.SESSION_ID',$session_id);
		$this->legacy_db->where('fs.FEE_CATEGORY_TYPE_ID ',$fee_category_type_id);
		$this->legacy_db->where('fpl.CAMPUS_ID',$campus_id);
		$this->legacy_db->where('fpl.PROGRAM_TYPE_ID',$prog_type_id);
		$this->legacy_db->where('fpl.SHIFT_ID',$shift_id);
		$this->legacy_db->where('fpl.PROG_LIST_ID',$prog_list_id);
		$this->legacy_db->group_by('fs.SESSION_ID');
		$this->legacy_db->group_by('fpl.CAMPUS_ID');
		$this->legacy_db->group_by('fpl.PROGRAM_TYPE_ID');
		$this->legacy_db->group_by('fpl.SHIFT_ID');
		$this->legacy_db->group_by('fpl.PROG_LIST_ID');
		$this->legacy_db->group_by('fct.FEE_CATEGORY_TYPE_ID');
		return $this->legacy_db->get()->row_array();
	}
	
	function get_student_for_challan($selection_list_id,$application_id,$part_id,$semester_id,$demerit_id){
		if ($part_id ==1) $part_join = "AND fpl.PART_ID = $part_id";
		else $part_join = "AND fpl.PART_ID < $part_id";

		if ($semester_id == 1) $semester_join = " AND fpl.SEMESTER_ID = $semester_id";
		elseif ($semester_id == 11) $semester_join = " AND fpl.SEMESTER_ID = $semester_id";
		else $semester_join=" AND fpl.SEMESTER_ID < $semester_id";

    	$query="SELECT 
					  sl.ROLL_NO,
					  sl.SELECTION_LIST_ID,
					  app.`APPLICATION_ID`,
					  reg.`USER_ID`,
					  sl.`SHIFT_ID`,
					  admss.`ADMISSION_SESSION_ID`,
					  admss.`SESSION_ID`,
					  admss.`PROGRAM_TYPE_ID`,
					  admss.`CAMPUS_ID`,
					  reg.`FIRST_NAME`,
					  reg.FNAME,
					  reg.`LAST_NAME`,
					  sl.PROG_LIST_ID,
					  pl.`PROGRAM_TITLE`,
					  c.`CATEGORY_ID`,
					  c.`CATEGORY_NAME`,
					  fct.BANK_ACCOUNT_ID,
					  fct.FEE_CATEGORY_TYPE_ID,
					  SUM(fs.AMOUNT) AS OLD_CHALLAN_AMOUNT,
					  dis.`DISCIPLINE_ID`,
					  dp.`DEGREE_ID`,
					  q.ORGANIZATION_ID, 
					  CASE
						  WHEN admss.`PROGRAM_TYPE_ID` = 1 THEN 3
						  ELSE 4
						  END
						AS DEGREE_ID_CASE 
					FROM
					  users_reg reg 
					  JOIN applications app 
						ON (
						  reg.`USER_ID` = app.`USER_ID` 
						  AND app.`IS_DELETED` = 'N'
						) 
					  JOIN selection_list sl 
						ON (
						  app.`APPLICATION_ID` = sl.`APPLICATION_ID`
						) 
					  JOIN admission_session admss 
						ON (
						  sl.`ADMISSION_SESSION_ID` = admss.`ADMISSION_SESSION_ID`
						) 
					  JOIN program_list pl 
						ON (
						  pl.`PROG_LIST_ID` = sl.`PROG_LIST_ID`
						) 
					  JOIN category c 
						ON (
						  c.`CATEGORY_ID` = sl.`CATEGORY_ID`
						) 
					  JOIN fee_category_type fct 
						ON (
						  fct.FEE_CATEGORY_TYPE_ID = c.FEE_CATEGORY_TYPE_ID
						) 
					  JOIN qualifications q 
						ON (reg.`USER_ID` = q.`USER_ID`) 
					  JOIN discipline dis 
						ON (
						  q.`DISCIPLINE_ID` = dis.`DISCIPLINE_ID`
						) 
					  JOIN degree_program dp 
						ON (dis.`DEGREE_ID` = dp.`DEGREE_ID`) 
					  LEFT JOIN fee_enrolment fe 
						ON (
						  q.ORGANIZATION_ID = fe.INSTITUTE_ID 
						  AND fe.`SESSION_ID` = admss.`SESSION_ID`
						)  
						JOIN fee_program_list fpl 
						ON (fpl.CAMPUS_ID=admss.CAMPUS_ID 
						        AND fpl.PROGRAM_TYPE_ID=admss.PROGRAM_TYPE_ID 
						        AND fpl.SHIFT_ID=sl.SHIFT_ID 
						        AND fpl.PROG_LIST_ID=sl.PROG_LIST_ID		
						         $part_join
					  			AND fpl.FEE_DEMERIT_ID = $demerit_id 
					  			$semester_join
						    )
						LEFT JOIN fee_structure fs ON (fpl.FEE_PROG_LIST_ID=fs.FEE_PROG_LIST_ID 
						                            AND fs.SESSION_ID=admss.SESSION_ID 
						                            AND fs.FEE_CATEGORY_TYPE_ID=fct.FEE_CATEGORY_TYPE_ID
						    )
					WHERE app.`APPLICATION_ID` = $application_id 
					  AND sl.`SELECTION_LIST_ID` = $selection_list_id 
					  AND dp.`DEGREE_ID` = (  CASE
						  WHEN admss.`PROGRAM_TYPE_ID` = 1 THEN 3
						  ELSE 4
						  END)
					GROUP BY sl.`SELECTION_LIST_ID`";

		$this->legacy_db = $this->load->database('admission_db',true);
		$q = $this->legacy_db->query($query);
		$result = $q->row_array();
		return $result;
	}
	function get_paid_online_challan_stat(){
	    $query = "select PAID_DATE,count(*) as TOTAL from challan where SECTION_ACCOUNT_ID = 20 group by PAID_DATE ";
	    $q = $this->db->query($query);
		$result = $q->result_array();
		return $result;
	}
    function getFeeStructure($SESSION_ID,$FEE_PROG_LIST_ID,$FEE_CATEGORY_TYPE_ID){
		$this->legacy_db = $this->load->database('admission_db',true);
		$condition = array('se.SESSION_ID' => $SESSION_ID, 'fpl.FEE_PROG_LIST_ID' => $FEE_PROG_LIST_ID, 'cat.FEE_CATEGORY_TYPE_ID' => $FEE_CATEGORY_TYPE_ID);
		/* $this->legacy_db->select('fpl.FEE_PROG_LIST_ID, fpl.CAMPUS_ID, fpl.PROGRAM_TYPE_ID, fpl.SHIFT_ID,fpl.PROG_LIST_ID,
		fs.FEE_CATEGORY_TYPE_ID,fs.SESSION_ID,ba.BANK_ACCOUNT_ID,fpl.PART_ID,fpl.SEMESTER_ID,fd.FEE_DEMERIT_ID,fd.NAME AS FEE_TYPE,SUM(fs.AMOUNT) AS CHALLAN_AMOUNT');
		$this->legacy_db->from('fee_structure fs');
		$this->legacy_db->join('fee_program_list fpl','fpl.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID');
		$this->legacy_db->join('fee_category_type fct','fct.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID');
		$this->legacy_db->join('bank_account ba','ba.BANK_ACCOUNT_ID = fct.BANK_ACCOUNT_ID');
		$this->legacy_db->join('fee_demerit fd','fpl.FEE_DEMERIT_ID = fd.FEE_DEMERIT_ID');
		$this->legacy_db->where($condition);
		$result = $this->legacy_db->get()->result_array(); */

	    $query = "SELECT * FROM (SELECT fs.SEMESTER_ID, fs.PART_ID, app.APPLICATION_ID, sl.SELECTION_LIST_ID, cam.NAME, sh.SHIFT_ID, sh.SHIFT_NAME, pl.PROGRAM_TITLE, fs.PART_NO, fs.PART_NAME, fs.SEMESTER_NAME, fs.AMOUNT AS FEE_AMOUNT, fe.AMOUNT AS ENR_AMOUNT, fc.LATE_FEE AS LATE_FEE
        FROM selection_list sl
        JOIN applications app ON (sl.APPLICATION_ID = app.APPLICATION_ID AND app.IS_DELETED='N') 
        JOIN users_reg reg ON (app.USER_ID = reg.USER_ID) 
        JOIN admission_session admss ON (sl.ADMISSION_SESSION_ID = admss.ADMISSION_SESSION_ID)
        JOIN campus cam ON(admss.CAMPUS_ID = cam.CAMPUS_ID)
        JOIN shift sh ON(sl.SHIFT_ID = sh.SHIFT_ID)
        JOIN program_list pl ON (pl.PROG_LIST_ID = sl.PROG_LIST_ID) 
        JOIN category c ON (c.CATEGORY_ID = sl.CATEGORY_ID)
        JOIN fee_category_type fct ON (fct.FEE_CATEGORY_TYPE_ID = c.FEE_CATEGORY_TYPE_ID)
        JOIN qualifications q ON (reg.USER_ID = q.USER_ID) 
        JOIN discipline dis ON (q.DISCIPLINE_ID = dis.DISCIPLINE_ID) 
        JOIN degree_program dp ON (dis.DEGREE_ID = dp.DEGREE_ID) 
        LEFT JOIN fee_enrolment fe ON (q.ORGANIZATION_ID = fe.INSTITUTE_ID AND fe.SESSION_ID = admss.SESSION_ID)
        JOIN (
          SELECT fpl.FEE_PROG_LIST_ID, fee.SESSION_ID, fpl.CAMPUS_ID, fpl.SHIFT_ID, fpl.PROG_LIST_ID, fee.FEE_CATEGORY_TYPE_ID, sem.SEMESTER_ID, p.PART_ID, p.PART_NO, p.NAME AS PART_NAME, sem.NAME AS SEMESTER_NAME, fee.AMOUNT
          FROM fee_program_list fpl
          JOIN part p ON(fpl.PART_ID = p.PART_ID)
          JOIN semester sem ON(fpl.SEMESTER_ID = sem.SEMESTER_ID)
          JOIN (
            SELECT fs.SESSION_ID, fs.FEE_PROG_LIST_ID, fs.FEE_CATEGORY_TYPE_ID, SUM(fs.AMOUNT) AS AMOUNT
            FROM fee_structure fs 
            GROUP BY fs.SESSION_ID, fs.FEE_PROG_LIST_ID, fs.FEE_CATEGORY_TYPE_ID
          ) fee ON(fpl.FEE_PROG_LIST_ID = fee.FEE_PROG_LIST_ID)
        ) AS fs ON (admss.SESSION_ID = fs.SESSION_ID AND cam.CAMPUS_ID = fs.CAMPUS_ID AND sh.SHIFT_ID = fs.SHIFT_ID AND pl.PROG_LIST_ID = fs.PROG_LIST_ID AND c.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID)
        LEFT JOIN fee_challan fc ON (fc.SELECTION_LIST_ID = sl.SELECTION_LIST_ID AND fc.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID AND fc.CHALLAN_TYPE_ID = 1)
        WHERE dp.DEGREE_ID IN(3,4,5) AND sl.SELECTION_LIST_ID = $selection_list_id
        ORDER BY dp.DEGREE_ID DESC) AS fee_str GROUP BY fee_str.SEMESTER_ID, fee_str.PART_ID";
		$q = $this->legacy_db->query($query);
		$result = $q->result_array();
		return $result;
	}
	
	function checkFeeChallanBank($selection_list_id,$fee_prog_list_id) {
	    $this->legacy_db = $this->load->database('admission_db',true);
	    $this->legacy_db->select('*');
	    $this->legacy_db->from('fee_challan_bank');
	    $this->legacy_db->where('SELECTION_LIST_ID',$selection_list_id);
	    $this->legacy_db->where('FEE_PROG_LIST_ID',$fee_prog_list_id);
	    $row = $this->legacy_db->get()->row_array();
	    return $row;
	}
	
	function getDataByAdmissionList($admission_list_id) {
        
        $this->legacy_db = $this->load->database('admission_db',true);
        $this->legacy_db->select(
            'sl.APPLICATION_ID AS CANDIDATE_ID,
            ur.FIRST_NAME AS CANDIDATE_NAME,
            CONCAT(ur.FNAME,","\'\', ur.LAST_NAME) AS CANDIDATE_FNAME,
            sl.APPLICATION_ID AS BATCH_ID,
            pl.PROGRAM_TITLE AS PROGRAM_CLASS,
            cam.LOCATION AS CAMPUS_NAME,
            sh.SHIFT_NAME AS SHIFT,
            cat.CATEGORY_NAME AS CATEGORY,
            se.YEAR AS AY,
            se.SESSION_ID,
            sl.SELECTION_LIST_ID,
            fpl.FEE_PROG_LIST_ID');
        $this->legacy_db->from('selection_list sl');
        $this->legacy_db->join('admission_session ads','sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID');
        $this->legacy_db->join('admission_list al','sl.ADMISSION_LIST_ID = al.ADMISSION_LIST_ID AND ads.ADMISSION_SESSION_ID = al.ADMISSION_SESSION_ID');
        $this->legacy_db->join('sessions se','ads.SESSION_ID = se.SESSION_ID');
        $this->legacy_db->join('program_list pl','sl.PROG_LIST_ID = pl.PROG_LIST_ID');
        $this->legacy_db->join('shift_program_mapping spm','ads.CAMPUS_ID = spm.CAMPUS_ID AND sl.SHIFT_ID = spm.SHIFT_ID AND pl.PROG_LIST_ID = spm.PROG_LIST_ID');
        $this->legacy_db->join('applications app','sl.APPLICATION_ID = app.APPLICATION_ID');
        $this->legacy_db->join('users_reg ur','app.USER_ID = ur.USER_ID');
        $this->legacy_db->join('campus cam','ads.CAMPUS_ID = cam.CAMPUS_ID');
        $this->legacy_db->join('shift sh','sl.SHIFT_ID = sh.SHIFT_ID');
        $this->legacy_db->join('category cat','sl.CATEGORY_ID = cat.CATEGORY_ID');
        $this->legacy_db->join('fee_program_list fpl','cam.CAMPUS_ID = fpl.CAMPUS_ID AND sh.SHIFT_ID = fpl.SHIFT_ID AND pl.PROG_LIST_ID = fpl.PROG_LIST_ID AND fpl.PART_ID IN(1,6,8) AND fpl.SEMESTER_ID IN(1,11)');
        $this->legacy_db->where('al.ADMISSION_LIST_ID',$admission_list_id);
        $this->legacy_db->where('sl.IS_PROVISIONAL','N');
        $this->legacy_db->order_by('spm.PROG_CODE, cat.CATEGORY_ID');
		$result = $this->legacy_db->get()->result_array();
		return $result;
        
	}
	
	function getDataByProgram($session_id,$campus_id,$program_type_id,$shift_id,$program_id){
	    $this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select(
		    'app.APPLICATION_ID, 
		     ur.FIRST_NAME, 
		     ur.FNAME, 
		     pl.PROGRAM_TITLE, 
		     c.LOCATION AS CAMPUS_NAME, 
		     sh.SHIFT_NAME, 
		     cat.CATEGORY_NAME, 
		     se.YEAR, sl.ROLL_NO, 
		     sl.SELECTION_LIST_ID,
		     c.CAMPUS_ID,
		     ads.PROGRAM_TYPE_ID,
		     sh.SHIFT_ID,
		     pl.PROG_LIST_ID,
		     se.SESSION_ID,
		     cat.FEE_CATEGORY_TYPE_ID,
		     ur.USER_ID');
		$this->legacy_db->from('applications app');
		$this->legacy_db->join('admission_session ads','app.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID');
		$this->legacy_db->join('sessions se','ads.SESSION_ID = se.SESSION_ID');
		$this->legacy_db->join('selection_list sl','app.APPLICATION_ID = sl.APPLICATION_ID');
		$this->legacy_db->join('campus c','ads.CAMPUS_ID = c.CAMPUS_ID');
		$this->legacy_db->join('program_list pl','sl.PROG_LIST_ID = pl.PROG_LIST_ID');
		$this->legacy_db->join('shift sh','sl.SHIFT_ID = sh.SHIFT_ID');
		$this->legacy_db->join('category cat','sl.CATEGORY_ID = cat.CATEGORY_ID');
		$this->legacy_db->join('candidate_account ca','app.APPLICATION_ID = ca.APPLICATION_ID');
		$this->legacy_db->join('users_reg ur','app.USER_ID = ur.USER_ID');
		$this->legacy_db->where('se.SESSION_ID =',$session_id);
		$this->legacy_db->where('c.CAMPUS_ID =',$campus_id);
		$this->legacy_db->where('ads.PROGRAM_TYPE_ID =',$program_type_id);
		$this->legacy_db->where('sh.SHIFT_ID =',$shift_id);
		$this->legacy_db->where('pl.PROG_LIST_ID =',$program_id);
		$this->legacy_db->where('sl.IS_ENROLLED = "Y"');
		$this->legacy_db->where('ca.ACTIVE = 1');
		$this->legacy_db->order_by('sl.ROLL_NO_CODE');
		$result = $this->legacy_db->get()->result_array();
		return $result;
	}
	
	function getDataByApplication($application_id){
	    $this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select(
		    'app.APPLICATION_ID, 
		     ur.FIRST_NAME, 
		     ur.FNAME, 
		     pl.PROGRAM_TITLE, 
		     c.LOCATION AS CAMPUS_NAME, 
		     sh.SHIFT_NAME, 
		     cat.CATEGORY_NAME, 
		     se.YEAR, sl.ROLL_NO, 
		     sl.SELECTION_LIST_ID,
		     c.CAMPUS_ID,
		     ads.PROGRAM_TYPE_ID,
		     sh.SHIFT_ID,
		     pl.PROG_LIST_ID,
		     se.SESSION_ID,
		     cat.FEE_CATEGORY_TYPE_ID,
		     ur.USER_ID');
		$this->legacy_db->from('applications app');
		$this->legacy_db->join('admission_session ads','app.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID');
		$this->legacy_db->join('sessions se','ads.SESSION_ID = se.SESSION_ID');
		$this->legacy_db->join('selection_list sl','app.APPLICATION_ID = sl.APPLICATION_ID');
		$this->legacy_db->join('campus c','ads.CAMPUS_ID = c.CAMPUS_ID');
		$this->legacy_db->join('program_list pl','sl.PROG_LIST_ID = pl.PROG_LIST_ID');
		$this->legacy_db->join('shift sh','sl.SHIFT_ID = sh.SHIFT_ID');
		$this->legacy_db->join('category cat','sl.CATEGORY_ID = cat.CATEGORY_ID');
		$this->legacy_db->join('candidate_account ca','app.APPLICATION_ID = ca.APPLICATION_ID');
		$this->legacy_db->join('users_reg ur','app.USER_ID = ur.USER_ID');
		$this->legacy_db->where('app.APPLICATION_ID =',$application_id);
		$this->legacy_db->where('sl.IS_ENROLLED = "Y"');
		$this->legacy_db->where('ca.ACTIVE = 1');
		$result = $this->legacy_db->get()->result_array();
		return $result;
	}

	function getLastChallanNo($generateby){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('MAX(fcb.CHALLAN_NO) AS CHALLAN_NO');
		$this->legacy_db->from ('fee_challan_bank fcb');
		if($generateby == "generatebyselectionlist") {
			$this->legacy_db->where('fcb.CHALLAN_NO >=',212300000);
			$this->legacy_db->where('fcb.CHALLAN_NO <=',212330000);
		} elseif($generateby == "generatebyprogram") {
			$this->legacy_db->where('fcb.CHALLAN_NO >=',212330001);
			$this->legacy_db->where('fcb.CHALLAN_NO <=',212380000);
		}
		$row = $this->legacy_db->get()->row_array();
		return $row['CHALLAN_NO'];
	}
}
