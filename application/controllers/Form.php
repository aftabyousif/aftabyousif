<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends CI_Controller
{
    private $SelfController = 'form';
    private $profile = 'candidate/profile';
    private $LoginController = 'login';
    private $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    private $user ;
    private $file_size = 500;

	public function __construct()
	{
		parent::__construct();

        if(!$this->session->has_userdata($this->SessionName)){
            redirect(base_url().$this->LoginController);
            exit();
        }else{
            $this->user = $this->session->userdata($this->SessionName);
        }
		$this->load->model('Administration');
		$this->load->model('log_model');
		$this->load->model("Admission_session_model");
		$this->load->model("Application_model");
		$this->load->model("User_model");
	}

	public function announcement ()
	{
		$admission_announcements = $this->Admission_session_model->get_form_admission_session ();
        $user = $this->user ;
		$data['user'] = $user;
		$data['profile_url'] = '';

		$data['admission_announcement'] = $admission_announcements;
        $data['user_application_list'] = $this->Application_model->getApplicationByUserId($user['USER_ID']);
		$this->load->view('include/header',$data);

		$this->load->view('display_form_announcement',$data);

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
    public function addApplication (){

        $user = $this->user ;
        $user_id = $user['USER_ID'];
	    $this->form_validation->set_rules('ADMISSION_SESSION_ID','form session','required');
        $this->form_validation->set_rules('CAMPUS_ID','campus','required');

        if ($this->form_validation->run())
        {
            $ADMISSION_SESSION_ID =  $this->input->post('ADMISSION_SESSION_ID');
            $admission = $this->Admission_session_model->getAdmissionSessionById($ADMISSION_SESSION_ID);


            if($admission){
                $end_date = $admission['ADMISSION_END_DATE'];
                $SESSION_ID = $admission['SESSION_ID'];
                $CAMPUS_ID = $admission['CAMPUS_ID'];

                $form_fees = $this->Admission_session_model->getFormFeesBySessionAndCampusId($SESSION_ID,$CAMPUS_ID);
                if($form_fees) {
                    $datetime = gmdate('Y-m-d', time());
                    if ($end_date >= $datetime) {

                        $result = $this->Application_model->getApplicationByUserIdAndAdmissionSessionId($user_id, $ADMISSION_SESSION_ID);
                        if (!$result) {
                            $user_data = $this->User_model->getUserFullDetailById($user_id);
                            $user_data = json_encode($user_data);
                            $datetime = gmdate('Y-m-d H:i:s', time());
                            $form_array = array(
                                "USER_ID" => $user_id,
                                'ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID,
                                'FORM_DATE' => $datetime,
                                'STATUS_ID' => 1,
                                'IS_SUBMITTED' => 'N',
                                'FORM_DATA' => $user_data);
                            $is_add_application = $this->Application_model->addApplication($form_array);


                            if ($is_add_application) {
                                $APPLICATION_ID = $is_add_application;
                                $form_array = array(
                                    "USER_ID" => $user_id,
                                    'ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID,
                                    'APPLICATION_ID' => $APPLICATION_ID,
                                    'FORM_FEE_ID' => $form_fees['FORM_FEE_ID'],
                                    'CHALLAN_AMOUNT' => $form_fees['AMOUNT']);
                                $is_add_challan = $this->Application_model->addChallan($form_array);


                                if ($is_add_challan) {
                                    $url = base_url() . "form/admission_form_challan/$APPLICATION_ID";
                                    $this->session->set_flashdata('OPEN_TAB', $url);
                                    redirect(base_url() . "candidate/profile");
                                } else {
                                    echo "can not generate challan";
                                }


                            } else {
                                echo "form dose not submit";
                            }

                        } else {
                            echo "You are application is already submit";
                            //     redirect(base_url().'form/announcement');
                        }

                    } else {
                        echo "Date Expire...!";
                        //   redirect(base_url().'form/announcement');
                    }
                }else{
                    echo "Form Fees Not Found";
                }

            }else{
                echo "Invalid Admission Session Id";
                //redirect(base_url().'form/announcement');
            }

        }
        else{
            redirect(base_url().'form/announcement');
        }
    }
    public function admission_form_challan($APPLICATION_ID){
        $user = $this->session->userdata($this->SessionName);
        $data['user'] = $user;
        $data['APPLICATION_ID']=$APPLICATION_ID;
        $application = $this->Application_model->getApplicationByUserAndApplicationId($user['USER_ID'],$APPLICATION_ID);
        if($application){
            $this->load->view('fpdf',$data);
        }else{
            echo "this application id is not associate with you";
        }

    }

}
