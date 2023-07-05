<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 09/19/2022
 * Time: 11:52 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class EnrolmentCard extends CI_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Admission_session_model");
		$this->load->model("Application_model");
		$this->load->model("User_model");
		$this->load->model("Prerequisite_model");
		$this->load->model("TestResult_model");
		$this->load->model("AdmitCard_model");
	}

	public function enrollment_card_pdf($param){
		if (empty($param)){
			exit('Invalid input.');
		}
		$param = base64url_decode($param);
		$param = json_decode($param,true);
		if (!isset($param['ROLL_NO'])){
			exit('Invalid parameters.');
		}elseif (empty($param['ROLL_NO'])){
			exit('Roll No. is required.');
		}
		$role_no=isValidData($param['ROLL_NO']);
		$by=isValidData($param['BY']);

		$application 	= $this->EnrolmentCard_model->get_application ($role_no);
	
		if (empty($application)){
			exit("Record not found against this Roll No. $role_no ");
		}
		  
		  if(!file_exists(PROFILE_IMAGE_CHECK_PATH.$application['PROFILE_IMAGE'])){
               do {
                   $resutl = $this->CI_ftp_Download(PROFILE_IMAGE_CHECK_PATH, $application['PROFILE_IMAGE']);
               }while(!$resutl);
            }
            
		$application_id = $application['APPLICATION_ID'];
		$qualification 	= $this->EnrolmentCard_model->get_qualification($application_id);
		$program_type_id= $application['PROGRAM_TYPE_ID'];
		if ($program_type_id == 1 ){
			$part_id = array (1);
			$semester_id = array(11,1);
		}else{
			$part_id = array (6,8);
			$semester_id = array(11,1);
		}

		$paid_fee = $this->EnrolmentCard_model->get_first_paid_challan ($application_id,$part_id,$semester_id);
		$application['PAID_DATE']=$paid_fee['PAID_DATE'];
		$application['PART_NAME']=$paid_fee['PART_NAME'];

		// 96 BISE HYDERABAD
		// 100 BISE MIRPURKHAS

		$enrolment_card = $this->EnrolmentCard_model->get_enrolment_card($application_id,$application['SELECTION_LIST_ID']);
		if (empty($enrolment_card)){

			if ($program_type_id == 1){
				$qual = find_qualification($qualification,3);
				if ($qual == null) exit("Intermediate qualification not found...");
			}elseif ($program_type_id == 2){
			    
			    if(empty($qualification)){
                    exit("Qualification not found...");
               }elseif($qualification[0]['DEGREE_ID'] == 10){
                   $qual = $qualification[1];
               }else{
                   $qual = $qualification[0];
               }
			    
			    /*
				$qual = find_qualification($qualification,4);
				if ($qual == null)
					$qual = find_qualification($qualification,5);
				if ($qual == null)
					$qual = find_qualification($qualification,6);
				if ($qual == null) exit("Master qualification not found...");
				*/
			}

			$active=0;
				if ($qual['BOARD_ID'] == 96 || $qual['BOARD_ID'] == 100 || $qual['BOARD_ID'] ==2){
					$active=1;
				}
				$issuer_id = 0;
				$issued_by = '';

			$arr = array (
				'APPLICATION_ID'=>$application['APPLICATION_ID'],
				'SELECTION_LIST_ID'=>$application['SELECTION_LIST_ID'],
				'ISSUE_DATE'=>date('Y-m-d h:i:s'),
				'ACTIVE'=>$active,
//				'ISSUER_ID'=>$issuer_id,
//				'ISSUED_BY'=>$issued_by
			);

			$this->legacy_db = $this->load->database("admission_db",true);
			$this->legacy_db->trans_begin();
			$this->legacy_db->db_debug = false;

			$is_add_enrolment_card  = $this->legacy_db->insert('enrolment_card', $arr);
			$enrolment_card_id = $this->legacy_db->insert_id();
			if ($enrolment_card_id<0){
				exit("Enrolment Card is not generating.");
				$this->legacy_db->trans_rollback();
			}else{
				$this->legacy_db->trans_commit();
			}
		}//if enrolment card is empty
		$enrolment_card = $this->EnrolmentCard_model->get_enrolment_card($application_id,$application['SELECTION_LIST_ID']);
		if ($enrolment_card['ACTIVE'] == 0){
			exit("Your Enrolment Card is not enabled.");
		}elseif ($enrolment_card['ACTIVE'] == 2){
			exit("Your Enrolment Card is canceled.");
		}

		$arr['APPLICATION']=$application;
		$arr['QUALIFICATION']=$qualification;
		$arr['ENROLMENT_CARD']=$enrolment_card;
		$arr['BY']=$by;

		$this->load->view('general_branch/enrolment_card_pdf',$arr);
	}//method

	public function eligibility_certificate_pdf($param){
		if (empty($param)){
			exit('Invalid input.');
		}
		$param = base64url_decode($param);
		$param = json_decode($param,true);
		if (!isset($param['ROLL_NO'])){
			exit('Invalid parameters.');
		}elseif (empty($param['ROLL_NO'])){
			exit('Roll No. is required.');
		}

		$role_no=isValidData($param['ROLL_NO']);
		$by=isValidData($param['BY']);
		$application 	= $this->EnrolmentCard_model->get_application ($role_no);
			
			if(!file_exists(PROFILE_IMAGE_CHECK_PATH.$application['PROFILE_IMAGE'])){
               do {
                   $resutl = $this->CI_ftp_Download(PROFILE_IMAGE_CHECK_PATH, $application['PROFILE_IMAGE']);
               }while(!$resutl);
            }
            
		$application_id = $application['APPLICATION_ID'];
		$qualification 	= $this->EnrolmentCard_model->get_qualification($application_id);
		$program_type_id= $application['PROGRAM_TYPE_ID'];
		if ($program_type_id == 1 ){
			$part_id = array (1);
			$semester_id = array(11,1);
		}else{
			$part_id = array (6,8);
			$semester_id = array(11,1);
		}

		$paid_fee = $this->EnrolmentCard_model->get_first_paid_challan ($application_id,$part_id,$semester_id);
		$application['PAID_DATE']=$paid_fee['PAID_DATE'];
		$application['PART_NAME']=$paid_fee['PART_NAME'];

		// 96 BISE HYDERABAD
		// 100 BISE MIRPURKHAS

		$eligibility_certificate = $this->EnrolmentCard_model->get_eligibility_certificate($application_id,$application['SELECTION_LIST_ID']);
		if (empty($eligibility_certificate)){

			if ($program_type_id == 1){
				$qual = find_qualification($qualification,3);
				if ($qual == null) exit("Intermediate qualification not found...");
			}elseif ($program_type_id == 2){
				$qual = find_qualification($qualification,4);
				if ($qual == null)
					$qual = find_qualification($qualification,5);
				if ($qual == null)
					$qual = find_qualification($qualification,6);
				if ($qual == null) exit("Master qualification not found...");
			}

			$active=0;
				if ($qual['BOARD_ID'] == 96 || $qual['BOARD_ID'] == 100){
					$active=1;
				}

			$issuer_id = 0;
			$issued_by = '';

			$arr = array (
				'APPLICATION_ID'=>$application['APPLICATION_ID'],
				'SELECTION_LIST_ID'=>$application['SELECTION_LIST_ID'],
				'ISSUE_DATE'=>date('Y-m-d h:i:s'),
				'ACTIVE'=>$active,
//				'ISSUER_ID'=>$issuer_id,
//				'ISSUED_BY'=>$issued_by
			);

			$this->legacy_db = $this->load->database("admission_db",true);
			$this->legacy_db->trans_begin();
			$this->legacy_db->db_debug = false;

			$is_add_enrolment_card  = $this->legacy_db->insert('eligibility_certificate', $arr);
			$enrolment_card_id = $this->legacy_db->insert_id();
			if ($enrolment_card_id<0){
				$this->legacy_db->trans_rollback();
				exit("Eligibility Certificate is not generating.");
			}else{
				$this->legacy_db->trans_commit();
			}
		}//if enrolment card is empty
		$eligibility_certificate = $this->EnrolmentCard_model->get_eligibility_certificate($application_id,$application['SELECTION_LIST_ID']);
		if ($eligibility_certificate['ACTIVE'] == 0){
			exit("Your Eligibility Certificate is not enabled.");
		}if ($eligibility_certificate['ACTIVE'] == 2){
			exit("Your Eligibility Certificate is canceled.");
		}

		$arr['APPLICATION']=$application;
		$arr['QUALIFICATION']=$qualification;
		$arr['ELIGIBILITY_CERTIFICATE']=$eligibility_certificate;
		$arr['BY']=$by;

		$this->load->view('general_branch/eligibility_certificate_pdf',$arr);
	}//method

}//class
