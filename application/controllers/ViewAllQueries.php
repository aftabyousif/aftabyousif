<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';
class ViewAllQueries extends AdminLogin
{

// 	protected $SessionName = 'USER_LOGIN_FOR_ADMISSION';
    private $user ;

	public function __construct()
	{
	   // set_time_limit(1800);
	    
		parent::__construct();
		
// 		if(!$this->session->has_userdata($this->SessionName)){
//             redirect(base_url().$this->LoginController);
//             exit();
//         }else{
//             $this->user = $this->session->userdata($this->SessionName);
//         }
        		
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->set_mailtype("html");
		$this->load->model("Query_model");
	}//method

	public function ticket ()
	{		
		//$cnic=$_SESSION['USER_LOGIN_FOR_ADMISSION']['CNIC_NO'];
		$this->load->model('User_model');
		//$data['data'] = $this->User_model->getUserByCnic($cnic);
		$data['userTickets'] = $this->Query_model->getAllTickets();
		
		
		$user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $user_id = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];
        
		$data['user'] = $user;
        $data['profile_url'] = $user['PROFILE_IMAGE'];
		
		
		
		$this->load->view('include/header',$data);
        $this->load->view('include/preloder');
        $this->load->view('include/side_bar',$data);
        $this->load->view('include/nav',$data);
        $this->load->view("E_ticket/view_all_tickets",$data);
		$this->load->view('include/footer_area',$data);
        $this->load->view('include/footer',$data);
				
	}

	public function ticketResponse ($ticket_no)
	{
		try {
			if (empty($ticket_no))
			{
				throw new Exception("Sorry Ticket No is required");
			}else
			{
				$ticket_no = html_escape(htmlspecialchars(trim($ticket_no)));
				$response = $this->Query_model->get_uploaded_ticket($ticket_no);
			
				$array['TICKET_DETAIL'] = $response;
				$this->load->view('include/login_header');
				$this->load->view("E_ticket/responseTicket",$array);
				$this->load->view('include/login_footer');
			}
		}catch (Exception $e)
		{
			echo $e->getMessage();
		}
	}//function

	public function ticketReply ()
	{

		$userId=$_SESSION['ADMIN_LOGIN_FOR_ADMISSION']['USER_ID'];
		
		$this->form_validation->set_rules('reply', 'Reply', 'required|trim');
		$this->form_validation->set_rules('TICKET_ID', 'Ticket ID', 'required|trim|integer');

		if ($this->form_validation->run())
		{
			$reply =  html_escape(htmlspecialchars($this->input->post('reply')));
			$ticket_id = html_escape(htmlspecialchars($this->input->post('TICKET_ID')));
			$email 	= html_escape(htmlspecialchars($this->input->post('email')));
			$subject = html_escape(htmlspecialchars($this->input->post('subject')));
			$dateTime = date('Y-m-d h:i:s');
			
			$array = array (
				"RESPONSE"=>$reply,
				"REPLY_DATETIME"=>$dateTime,
				"REPLIER_ID"=>$userId
			);
			
			
			$out = $this->Query_model->reply_ticket("TICKET_ID=$ticket_id",$array,'open_ticket');
			if ($out == true)
			{
				$sent = $this->ticket_email_reply($email,$reply,$subject,$ticket_id);
				echo $sent;
			}else
			{
				exit("Something went wrong");
			}
		}else
		{
			exit("Something went wrong");
		}
	}//function

	public function ticket_email_reply ($to,$msg,$subject,$ticket_id)
	{
//		$name = $ticket_array['NAME'];
//		$email = $ticket_array['EMAIL'];
//		$mobile = $ticket_array['MOBILE_NO'];
//		$datetime = $ticket_array['DATETIME'];
//		$subject_text = $ticket_array['SUBJECT'];
//		$message_text = $ticket_array['MESSAGE'];
//		$ref = $ticket_array['REF_NO'];
//		$ticket_no = $ticket_array['TICKET_NO'];

//		$to = 'yasir.mehboob@usindh.edu.pk';
//		$this->email->bcc('director.itsc@usindh.edu.pk');

		$subject = "Reply:: please read reply of TICKET NO: $ticket_id";
		$message = "
	<h3 style='font-size: 12pt; text-align: center; font-family: Times New Roman'>Ticket Reply</h3> <br/>
    <p style='font-size: 12pt; font-family: Times New Roman'>$msg</p> <br/>
        
    <p>Thanks,</p>
    <p>Support Team</p>
    ";
		$this->email->from('no-reply@usindh.edu.pk','Directorate of Admission');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if($this->email->send())
		{
			echo "<b>Email successfully sent to $to AND Ticket NO is $ticket_id</b>";
//			$this->session->set_flashdata('message', "Your ticket successfully created. Your Ticket No is $ticket_no");
//			redirect('ticket');
		}else{
			echo "<b>Email sending failed</b>";
		}
	}

	public function ticket_email ($ticket_array,$image_path)
	{
		$name = $ticket_array['NAME'];
		$email = $ticket_array['EMAIL'];
		$mobile = $ticket_array['MOBILE_NO'];
		$datetime = $ticket_array['DATETIME'];
		$subject_text = $ticket_array['SUBJECT'];
		$message_text = $ticket_array['MESSAGE'];
		$ref = $ticket_array['REF_NO'];
		$ticket_no = $ticket_array['TICKET_ID'];

		
		if (!empty($ref))
			$ref = "($ref)";

		$to = 'admission.objection@usindh.edu.pk';
//		$this->email->bcc('director.itsc@usindh.edu.pk');

		$subject = "$subject_text";
		$message = "
    <p>NAME:<b>      ".$name." $ref </b></p>
    <p>EMAIL:<b>     ".$email."</b></p>
    <p>MOBILE:<b>    ".$mobile."</b></p>
    <p>TICKET NO:<b> ".$ticket_no."</b></p>
    
    <p style='font-size: 12pt; font-family: Times New Roman'>$message_text</p> <br/>
    <a href='".base_url()."Query/ticketResponse/".$ticket_no."'>CLICK HERE to give response</a>
    <br/>
	<p>This ticket is generated from Admission's E-Ticketing System.</p>
    
    <p>Thanks,</p>
    <p>Support Team</p>
    ";
		$this->email->from('itsc@usindh.edu.pk','ITSC Support Team');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if (!empty($image_path)){
			$this->email->attach($image_path);
		}

		if($this->email->send())
		{
//			echo "OK";
			$this->session->set_flashdata('message', "Your ticket successfully created. <b>Your Ticket No is $ticket_no </b><br/> You will get your reply on your email address within 24 to 48 hrs. Please wait, don't open another ticket within 48 hrs.");
			redirect('ObjectionQuery/ticket','refresh',302);
		}else{
//			echo "OK";
			$this->session->set_flashdata('message', "Your ticket successfully created. <b>Your Ticket No is $ticket_no </b><br/> You will get your reply on your email address within 24 to 48 hrs. Please wait, don't open another ticket within 48 hrs.");
			redirect('ObjectionQuery/ticket','refresh',302);
		}
	}//method

	public function upload_ticket_image($ticket_no)
	{

		$config['upload_path']          = './ticket_uploads/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['max_size']             = 6144;
		$config['max_width']            = 0;
		$config['max_height']           = 0;
//		$config['encrypt_name']         = true;
		$config['file_name']			= $ticket_no;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('userfile'))
		{
			$error = array('error' => $this->upload->display_errors());

			$this->session->set_flashdata('message',$this->upload->display_errors());
			redirect("ObjectionQuery/ticket");
		}
		else
		{
			$image_data = $this->upload->data();

			$data = array('error' => $this->upload->data());
			$image_path = $image_data['full_path'];

			$config['image_library'] = 'gd2';
			$config['source_image'] = $image_path;
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 500;
			$config['height']       = 600;

			$this->load->library('image_lib',$config);

			$this->image_lib->resize();

//			$this->CI_ftp($image_path,$ticket_no);

			return $image_path;
//			$this->load->view('upload', $data);
		}
	}//method

	public function upload ()
	{
		$array['error'] ='';
		$this->load->view('upload',$array);
	}//method

	private function CI_ftp($path,$name){

		define("FTP_URL_DATACENTRE","121.52.154.62");
		define("FTP_USER_DATACENTRE","ditsc");
		define("FTP_PASSWORD_DATACENTRE",'Dit$c123');

		$date_time =date('Y F d l h:i A');
		$msg = array(
			"FILE_NAME"=>$name,
			"DATE_TIME"=>$date_time,
			"MSG"=>""
		);

		$this->load->library('ftp');
		$config['hostname'] = FTP_URL_DATACENTRE;
		$config['username'] = FTP_USER_DATACENTRE;
		$config['password'] = FTP_PASSWORD_DATACENTRE;
		$config['debug']        = true;
		$connect = false;
		for($i=1;$i<=3;$i++){
			$connect = $this->ftp->connect($config);
			if($connect){
				break;
			}
		}

		/*
		if(!$connect){
			$msg['MSG'] = 'CONNECTION FAILED';
			$msg = json_encode($msg);
			writeQuery($msg);
			$this->ftp->close();
			return false;
		}
		*/
		$ftp_path = str_replace("..","/public_html",$path);
		$ftp_dir_path = rtrim($ftp_path,"/");

//		$ftp_path = str_replace("..","/htdocs",$path);
//		$ftp_dir_path = rtrim($ftp_path,"/admission_ticket");

		// $ftp_path = '/public_html/eportal_resource/foo/';
		// $ftp_dir_path = '/public_html/eportal_resource/foo';



		$already_exist = $this->ftp->list_files($ftp_path);

		if($already_exist){

		}else{
			$dir  = $this->ftp->mkdir($ftp_dir_path, 0755);
		}

		$up = $this->ftp->upload($path.$name,$ftp_path.$name, 'binary', 0775);
		if(!$up){
			$msg['MSG'] = 'UPLOADING FAILED';
			$msg = json_encode($msg);
			$this->ftp->close();
//			writeQuery($msg);
			return false;
		}

		$this->ftp->close();
		return true;
	}//method
	
	
	private function send_email ($to,$msg,$subject)
	{
	    
	$message = $msg;
	
		$this->email->from('admission@usindh.edu.pk',"Director Admissions");
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if($this->email->send())
		{
			return $to;
		}else{
			return false;
		}//else
		
	}//method
	
    public function email_xls (){
        
        $this->load->helper('file');
        $string = read_file('email_list.csv');
        
        $string_array = explode(";",$string);
     
        $content = "<html><head><body>
        <p style='font-size: 12pt; font-family: 'Times New Roman;'>Assalam Alaikum,</p>
        <p style='font-size: 12pt; font-family: 'Times New Roman;'>It is informed to you that your online admission form’s status is “Submitted” and you are required to select your desired subjects in preference order and then submit it. Thereafter, please keep the printout of the form with yourself. After selection, you have to submit the form along with other required documents at Directorate of Admissions, University of Sindh, Jamshoro.</p>
        <p style='font-size: 12pt; font-family: 'Times New Roman;'><a href='https://youtu.be/EHK3wmwezI0' target='_blank'>Please click here to watch the tutorial - How to select choices and submit online admission form.</a></p>
        <p style='font-size: 12pt; font-family: 'Times New Roman;'>After successfully submission of form, the status of form will be changed from “Submitted” to “In-Process”. Please frequently visit your E-portal account’s dashboard for further admission process, latest information and updates regarding Admissions 2021.</p>
        <p style='font-size: 12pt; font-family: 'Times New Roman;'> <a href='https://admission.usindh.edu.pk/admission/application_status' target='_blank'>Click here to check current status of your admission form.</a></p>
        <p style='font-size: 12pt; font-family: 'Times New Roman;'><b>Note: Please select your choices & complete your form within 48 hrs of receiving this email otherwise your form will be REJECTED & will not be considered for admission 2021.</b></p>
        <br> Best Regards,<br>Director Admissions.
        <br><br>
        --------------------------------------------------------------<br>
        This is a system generated email / response<br>
       ----------------------------------------------------------------<br>
       </body></head></html>";
        
        $subject = "Last notification regarding admission form";

        $email ="yasir.rind@outlook.com";
        $response = $this->send_email($email,$content,$subject);
        
        return;
        
        $i=0;
        foreach($string_array as $email){
            
            $email = trim($email);
            
            if(empty($email)) continue;
            
            $response = $this->send_email($email,$content,$subject);
            // $response=true;
            if ($response == false){
                prePrint("Email failed sending: ".$email);
                break;
            }else
            {
                $i++;
                prePrint("$i -  Sent: ".$response);
            }
            // prePrint($email);
        }//foreach
        
    }//method
}