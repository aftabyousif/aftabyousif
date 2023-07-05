<?php
/**
 * Created by PhpStorm.
 * User: YASIR MEHBOOB
 * Date: 01/01/2021
 * Time: 07:46 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';

class Notification extends AdminLogin
{

	public function __construct()
	{
		parent::__construct();
		set_time_limit(400);

		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->set_mailtype("html");
		$this->email->set_protocol("sendmail");
		$self = $_SERVER['PHP_SELF'];
		$self = explode('index.php/',$self);
		$this->script_name = $self[1];
		$this->verify_login();
	}

	public function mailbox(){

		$user = $this->session->userdata($this->SessionName);
		$user_role = $this->session->userdata($this->user_role);
		$user_id = $user['USER_ID'];
		$role_id = $user_role['ROLE_ID'];

		$side_bar_data = $this->Configuration_model->side_bar_data($user_id,$role_id);
//		$this->verify_path($this->script_name,$side_bar_data);

		$data['user'] = $user;
		$data['profile_url'] = '';
		$data['side_bar_values'] = $side_bar_data;
		$data['script_name'] = $this->script_name;

		$this->load->view('include/header',$data);
		$this->load->view('include/preloder');
		$this->load->view('include/side_bar',$data);
		$this->load->view('include/nav',$data);
		$this->load->view('notification/index_email',$data);
		$this->load->view('include/footer_area');
		$this->load->view('include/footer');
	}

	public function email_ready_queue(){

		$email_body = $this->input->post("email_body");
		$subject 	= $this->input->post("subject");
		$email_to 	= json_decode($this->input->post("email_to"),true);
		$cc_to 		= json_decode($this->input->post("cc_to"),true);
		$bcc_to 	= json_decode($this->input->post("bcc_to"),true);

		if (empty($email_to)) exit("please type recipient");
		if (empty($subject)) exit("please type email subject");
		if (empty($email_body)) exit("please type email");

		if (empty($cc_to)) $cc_to="";
//		else $cc_to = implode(";",$cc_to);
		if (empty($bcc_to)) $bcc_to="";
//		else $bcc_to = implode(";",$bcc_to);
		if (empty($subject)) $subject="";
		if (empty($email_body)) $email_body=".";

//		$file_name = "mail_xml/".date("d-m-Y-h-i-s").".xml";

		$sent=0;
		$unsent=0;
		foreach ($email_to as $to){
			$to = strtolower($to);
			$to = trim($to);
			if (empty($to)) continue;

			$response = $this->send_email($to,$email_body,$subject,$cc_to,$bcc_to);
			// $response=true;
			if ($response == false){
				$unsent++;
				prePrint("Email failed sending: ".$to);
//				break;
			}else
			{
				$sent++;
				prePrint("Sent: ".$response);
			}
		}

		echo "<h4>Total Sent: $sent</h4>";
		echo "<h4>Total Failed: $unsent</h4>";
	}
	private function send_email ($to,$message,$subject,$cc,$bcc)
	{
		if (!empty($cc)) $this->email->cc($cc);
		if (!empty($bcc)) $this->email->bcc($bcc);
		
		$rep = send_smtp_email($subject,$message,$to,$this);
		if($rep){
		    return $to;
		}else{
		    return false;
		}

/*
		$this->email->from('admission@usindh.edu.pk',"Director Admissions");
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
// 		$this->email->reply_to('admission@usindh.edu.pk', 'Director Admissions');
		if($this->email->send())
		{
			$this->email->clear(TRUE);
			return $to;
		}else{
			$this->email->clear(TRUE);
			return false;
		}//else
		
		*/

	}//method

	private function create_send_emails_xml($to,$subject,$mail_body,$datetime,$status,$cc,$bcc,$file_name){

		$mail_body = json_encode($mail_body);

		$dom = new DOMDocument();

		$dom->encoding = 'utf-8';

		$dom->xmlVersion = '1.0';

		$dom->formatOutput = true;

		$xml_file_name = $file_name;

		$root = $dom->createElement('Inbox');

		$movie_node = $dom->createElement('Recipient');

		$attr_movie_id = new DOMAttr('recipient_id', $to);

		$movie_node->setAttributeNode($attr_movie_id);

		$child_node_title = $dom->createElement('To', $to);

		$movie_node->appendChild($child_node_title);

		$child_node_cc = $dom->createElement('Cc', $cc);

		$movie_node->appendChild($child_node_cc);

		$child_node_bcc = $dom->createElement('Bcc', $bcc);

		$movie_node->appendChild($child_node_bcc);

		$child_node_year = $dom->createElement('Subject', $subject);

		$movie_node->appendChild($child_node_year);

		$child_node_genre = $dom->createElement('Message', $mail_body);

		$movie_node->appendChild($child_node_genre);

		$child_node_ratings = $dom->createElement('Datetime', $datetime);

		$movie_node->appendChild($child_node_ratings);

		$child_node_status = $dom->createElement('Status', $status);

		$movie_node->appendChild($child_node_status);

		$root->appendChild($movie_node);

		$dom->appendChild($root);

		$dom->save($xml_file_name);

		return true;
	}
}
