<?php
/**
 * Created by PhpStorm.
 * User: Yasir Mehboob
 * Date: 16/01/2021
 * Time: 05:00 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class PdfReport extends CI_Controller
{
	private $SelfController = 'CandidateSelection';
	private $profile = 'candidate/profile';
	private $LoginController = 'login';
	private $SessionName = 'USER_LOGIN_FOR_ADMISSION';
	private $user;
	private $APPLICATION_ID = 0;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Admission_session_model");
		$this->load->model("Application_model");
		$this->load->model("User_model");
		$this->load->model("Prerequisite_model");
		$this->load->model('User_model');
		$this->load->model('Api_location_model');
		$this->load->model('Configuration_model');
		$this->load->model('Api_qualification_model');
		$this->load->model('Selection_list_report_model');
		$this->load->model("TestResult_model");
		$this->load->model("FeeChallan_model");
	}


	public function FeeChallanPrint($challan){
		if (empty($challan)) exit("Require valid parameter....");

		$challan = urldecode(base64_decode(base64url_decode($challan)));
		$challan = json_decode($challan,true);
		if (empty($challan)) exit('Invalid input');
        // prePrint($challan);
		$data['challan']=$challan;
		$this->load->view('admission_fee_challan_pdf',$data);

	}
	public function RetainChallanPrint($challan){
		if (empty($challan)) exit("Require valid parameter....");

		$challan = urldecode(base64_decode(base64url_decode($challan)));
		$challan = json_decode($challan,true);
		if (empty($challan)) exit('Invalid input');

		$data['challan']=$challan;
		$this->load->view('admission_retain_challan_pdf',$data);

	}

}
