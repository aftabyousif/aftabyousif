<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh & Yasir Mehboob
 * Date: 7/11/2020
 * Time: 12:17 PM
 */
 
 function send_smtp_email_with_attachment($email_subject,$email_body,$email,$this_in,$attachments){

	$mail_obj = $this_in->phpmailer_lib->mail_admission();
	try {
		$mail_obj->addAddress($email);     //Add a recipient
		$mail_obj->addReplyTo("admission@usindh.edu.pk");
		$mail_obj->Subject = $email_subject;
		$mail_obj->Body    = $email_body;
		foreach ($attachments as $attachment){
			$mail_obj->AddAttachment($attachment);
		}
		$mail_obj->send();
		return true;
	} catch (Exception $e) {
		return false;
	}

}

 function sendPasswordTokenByEmail_smtp($email,$token,$user_id,$this_in){


    $token= urlencode(EncryptThis($token));
    $user_id= urlencode(EncryptThis($user_id));
    $from = 'admission@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='We have received password reset request';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum, <br/> Dear Candidate<br>
We have recieved password reset request for your account of E-Portal. Please visit the following link to reset your password:<br>".
        "         
                      <br><br><b><a href='".base_url()."forget/set_pwd/$user_id/$token'>Password Reset Link Click Here</a></b><br>
                      Note that above link for password reset is valid for one time use only
                      <br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";

        send_smtp_email($subject,$body,$email,$this_in);
}

function getShortForm($string){
    $array = explode(' ',$string);
    $str = "";
    foreach($array as $word){
        $str.=$word[0].".";
    }
    return $str;
}
function certificate_card_status($status){
	if ($status == 0) return "<span class='pull-right badge bg-warning'>DE-ACTIVE</span>";
	elseif ($status == 1) return '<span class="pull-left badge bg-green">ACTIVE</span>';
	elseif ($status == 2) return '<span class="pull-left badge bg-red">CANCEL</span>';
	else return $status;
}

function send_smtp_email_old($email_subject,$email_body,$email,$this_in){


    $file = fopen("./dash_assets/email_count.txt","r");
    $count =  fgets($file);
    fclose($file);
//array("smtp_user"=>"no-reply@usindh.edu.pk","smtp_pass"=>"itsc098**"),
    $list= array(
        array("smtp_user"=>"no-reply-sutc2@usindh.edu.pk","smtp_pass"=>"SUTCNOREPLY092022"),
     //array("smtp_user"=>"no-reply@usindh.edu.pk","smtp_pass"=>"itsc098**"),
      // array("smtp_user"=>"no-reply-sutc3@usindh.edu.pk","smtp_pass"=>"SUTCNOREPLY092022"),
        array("smtp_user"=>"no-reply-sutc@usindh.edu.pk","smtp_pass"=>"SUTCNOREPLY092022")
    );

    $num = (floor($count/200)+1)%count($list);

    $user = $list[$num];

    $fp = fopen("./dash_assets/email_count.txt",'w');//opens file in append mode
    fwrite($fp, $count+1);
    //fwrite($fp, $num);
    fclose($fp);

    $config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_port' => 465,
        'smtp_user' => $user['smtp_user'],
        'smtp_pass' => $user['smtp_pass'],
        'mailtype'  => 'html',
        'wordwrap'   => TRUE,
        'charset'   => 'utf-8',
        'newline'   => "\r\n"
    );

    //'smtp_user' => 'no-reply@usindh.edu.pk',
	//'smtp_pass' => 'itsc098**'
    $this_in->load->library('email', $config);
    $this_in->email->from('no-reply@usindh.edu.pk', 'Directorate Of Admission');
    $this_in->email->to($email);
    $this_in->email->subject($email_subject);
    $this_in->email->message($email_body);
    if($this_in->email->send()){
        return true;
    }else{
        return false;
    }
} 

function send_smtp_email($email_subject,$email_body,$email,$this_in){

    $mail_obj = $this_in->phpmailer_lib->mail_admission();
    try {
    		$mail_obj->addAddress($email);     //Add a recipient
    		$mail_obj->addReplyTo("admission@usindh.edu.pk"); 
			$mail_obj->Subject = $email_subject;
    		$mail_obj->Body    = $email_body;
    		$mail_obj->send();
    	return true;
	} catch (Exception $e) {
    	return false;
	}
  
} 

function startsWith($haystack, $needle) {
	// search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

// function send_smtp_email($email_subject,$email_body,$email,$this_in){
//       $this_in->email->from('no-reply@usindh.edu.pk', 'ADMISSIONS');
//         $this_in->email->to($email);
//         $this_in->email->subject($email_subject);
//         $this_in->email->message($email_body);
//         if($this_in->email->send()){
//             return true;
//         }else{
//             return false;
//         }
// }
function email_notification($email_subject,$email_body,$subject,$faculty_member_name,$email,$this_in){
    /*
    $subject['COURSE_NO'] = "";
    $subject['COURSE_TITLE'] = "";
    $subject['PART_REMARKS'] = "";
    $subject['SHIFT'] = "";
    $subject['GROUP_DESC'] = "";
    $subject['SEMESTER'] = "";
    $subject['EXAM_YEAR'] = "";
    $subject['EXAM_TYPE'] = "";
    $subject['CURRENT_STATUS']="";
    */
    $from = 'itsc@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $body = "<h3>أسلم عليكم</h3>";
    $body.= "<p style='font-size: 12pt'>".$faculty_member_name."</p>";
    $body.=$email_body;
    /*
    $body.="    <table border='1' style='border: 1px solid black; border-collapse: collapse;'>
                <tr style='text-align: center;font-size: 14pt;'><th colspan='2' style='padding: 5px;background-color: black;color: white'>Award List Information/Status</th></tr>
                <tr style='text-align: left;font-size: 12pt;'><th style='padding: 5px'>Course No</th> <td>".$subject['COURSE_NO']."</td></tr>
                <tr style='text-align: left;font-size: 12pt;'><th style='padding: 5px'>Course Title</th> <td>".$subject['COURSE_TITLE']."</td></tr>
                <tr style='text-align: left;font-size: 12pt;'><th style='padding: 5px'>Program</th> <td>".$subject['PART_REMARKS']." (".get_shift_encode($subject['SHIFT']).") ".$subject['GROUP_DESC']."</td></tr>
                <tr style='text-align: left;font-size: 12pt;'><th style='padding: 5px'>Semester</th> <td>".semester_decode($subject['SEMESTER'])."</td></tr>
                <tr style='text-align: left;font-size: 12pt;'><th style='padding: 5px'>Exam Year</th> <td>".$subject['EXAM_YEAR']."</td></tr>
                <tr style='text-align: left;font-size: 12pt;'><th style='padding: 5px'>Exam Type</th> <td>".$subject['EXAM_TYPE']."</td></tr>
                <tr style='text-align: left;font-size: 12pt;'><th style='padding: 5px'>Current Status</th> <td><span style='font-size:12pt; font-weight: bold;background-color: darkseagreen ;color: white;padding: 4px;border-radius: 10px'>".$subject['CURRENT_STATUS']."</span></td></tr>
                </table> 
                ";

    $body.= "</b><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>
                      E-portal url: <a href='http://eportal.usindh.edu.pk/'>http://eportal.usindh.edu.pk</a>";
    */
    
        $this_in->email->from('no-reply@usindh.edu.pk', 'E-Result');
        $this_in->email->to($email);
        $this_in->email->subject($email_subject);
        $this_in->email->message($email_body);
        if($this_in->email->send()){
            return true;
        }else{
            return false;
        }
}//method

function category_decode($category_title){
	if ($category_title == "QUOTA / GENERAL MERIT (JURISDICTION)") return "MERIT";
	elseif ($category_title == "SPORTS QUOTA") return "MERIT";
	elseif ($category_title == "QUOTA / GENERAL MERIT (OUT OF JURISDICTION)") return "MERIT";
	elseif ($category_title == "FEMALE QUOTA (JURISDICTION)") return "MERIT";
	elseif ($category_title == "FEMALE QUOTA (OUT OF JURISDICTION)") return "MERIT";
	elseif ($category_title == "DISABLE PERSONS QUOTA") return "MERIT";
	elseif ($category_title == "SUE SON DAUGHTER QUOTA") return "MERIT";
	elseif ($category_title == "SPECIAL SELF FINANCE") return "SPECIAL SELF FINANCE";
	elseif ($category_title == "SUE AFFILIATED COLLEGE SD QUOTA") return "MERIT";
	elseif ($category_title == "SUE SD NCEAC QUOTA") return "MERIT";
	elseif ($category_title == "NORTHERN AREAS NOMINATION") return "MERIT";
	elseif ($category_title == "SELF FINANCE") return "SELF FINANCE";
	elseif ($category_title == "PUNJAB PROVINCE NOMINATION") return "MERIT";
	elseif ($category_title == "AJK GOVERNMENT NOMINATION") return "MERIT";
	elseif ($category_title == "PHARMACEUTICAL INDUSTRY") return "MERIT";
	elseif ($category_title == "ARMY PERSONNEL NOMINATION") return "MERIT";
	elseif ($category_title == "FOREIGN PKTAP") return "MERIT";
	elseif ($category_title == "OTHER PROVINCES SELF FINANCE") return "SELF FINANCE";
	elseif ($category_title == "KPK PROVINCE NOMINATION") return "MERIT";
	elseif ($category_title == "BALOCHISTAN PROVINCE NOMINATION") return "MERIT";
	elseif ($category_title == "COMMERCE QUOTA") return "MERIT";
	elseif ($category_title == "FATA NOMINATION") return "MERIT";
	elseif ($category_title == "KARACHI RESERVED QUOTA") return "MERIT";
	elseif ($category_title == "SHUHDA WARDS NOMINATION QUOTA") return "MERIT";
	elseif ($category_title == "EVENING SELF FINANCE") return "EVENING";
	else return $category_title;

}

function part_decode ($part_id){
	if ($part_id == 1) return "FIRST YEAR";
	elseif ($part_id == 2) return "SECOND YEAR";
	elseif ($part_id == 3) return "THIRD YEAR";
	elseif ($part_id == 4) return "FOURTH YEAR";
	elseif ($part_id == 5) return "FIFTH YEAR";
	elseif ($part_id == 6) return "PREVIOUS";
	elseif ($part_id == 7) return "FINAL";
	elseif ($part_id == 8) return "FIRST YEAR (MBA 4 YEAR)";
	elseif ($part_id == 9) return "SECOND YEAR (MBA 4 YEAR)";
	elseif ($part_id == 10) return "THIRD YEAR (MBA 4 YEAR)";
	elseif ($part_id == 11) return "FOURTH YEAR (MBA 4 YEAR)";
}

function web_list_no_dropDown(){
    for($i=1; $i<=5; $i++){
        echo "<option value='$i'>$i</option>";
    }
}

function getIndexOfObjectInList_with_multi_check($list,$key_1,$value_1,$key_2,$value_2){
    //This method use to find the index of obejct in a list if not find return -1
    foreach ($list as $k=>$object){
        if($object[$key_1]==$value_1 && $object[$key_2]==$value_2){
            return $k;
        }
    }
    return -1;
}

function merge_list_with_key($list,$key){
	$new_list = array();
	foreach ($list as $value){
		if(!isset($new_list[$value[$key]])){
			$new_list[$value[$key]] = array();
		}
		array_push($new_list[$value[$key]],$value);
	}
	return $new_list;
}

function get_campus_by_id($campus_id){
	if ($campus_id == 1) return "UNIVERSITY OF SINDH, JAMSHORO";
	elseif ($campus_id == 2) return "SINDH UNIVERSITY CAMPUS, LARKANA";
	elseif ($campus_id == 3) return "SYED ALLAHNDO SHAH SINDH UNIVERSITY CAMPUS, NAUSHEHRO FEROZE";
	elseif ($campus_id == 4) return "SINDH UNIVERSITY CAMPUS, THATTA";
	elseif ($campus_id == 5) return "MOHTARMA BENAZIR BHUTTO SHAHEED SINDH UNIVERSITY CAMPUS, DADU";
	elseif ($campus_id == 6) return "SINDH UNIVERSITY CAMPUS, MIRPURKHAS";
	elseif ($campus_id == 7) return "SINDH UNIVERSITY LAAR CAMPUS @ BADIN";
	else $campus_id;

}

function shift_decode ($shift_id){
	if ($shift_id == 1) return "MORNING";
	elseif ($shift_id == 2) return "EVENING";
	elseif ($shift_id == 3) return "AFTERNOON";
}
function merit_list_decode ($list_no){
	if ($list_no == 1) return "FIRST";
	elseif ($list_no == 2) return "SECOND";
	elseif ($list_no == 3) return "THIRD";
	elseif ($list_no == 4) return "THIRD UPDATED";
	elseif ($list_no == 5) return "FIFTH";
	elseif ($list_no == 6) return "SIXTH";
	elseif ($list_no == 7) return "SEVENTH";
	elseif ($list_no == 8) return "EIGHTH";
	elseif ($list_no == 11) return "SPECIAL SELF FINANCE LIST 1";
	elseif ($list_no == 12) return "SPECIAL SELF FINANCE LIST 2";
	elseif ($list_no == 21) return "EVENING PROGRAMS LIST 1";
	
	else $list_no;

}
function getListValueArray($list,$key){
	//This method use to find the index of obejct in a list if not find return -1
	$new_array = array();
	foreach ($list as $k=>$object){
		if(isset($object[$key])){
//			return $k;
			array_push($new_array,$object[$key]);
		}
	}
	return $new_array;
}

function area_decode ($area){
	if ($area == "R") return "Rural";
	elseif ($area == "U") return "Urban";
	else return $area;
}

 
 function getIndexOfObjectInList($list,$key,$value){
    //This method use to find the index of obejct in a list if not find return -1
  
    foreach ($list as $k=>$object){
        
        if($object[$key]==$value){
            return $k;
        }
        
    }
   // prePrint($list);
    return -1;
}

function quicksort_form_verification($array,$key,$order="ASC"){
//	$form_data = json_decode($applicant['FORM_DATA'],true);
//	$applicant['form_data'] = $form_data;

	if (count($array) == 0)
		return array();

	$pivot_element = $array[0];
	$left_element = $right_element = array();

	for ($i = 1; $i < count($array); $i++) {
		$array_data = json_decode($array[$i]['FORM_DATA'],true);
		$pivot_data = json_decode($pivot_element['FORM_DATA'],true);

		$array_data_qual=$array_data['qualifications'];

		$pivot_data_qual=$pivot_data['qualifications'];
		$search_value = 0;
		$pivot_value = 0;
		if($array_data_qual[0]['DEGREE_ID']==10){
			$search_value = $array_data_qual[1]['OBTAINED_MARKS'];
		}else{
			$search_value = $array_data_qual[0]['OBTAINED_MARKS'];
		}

		if($pivot_data_qual[0]['DEGREE_ID']==10){
			$pivot_value = $pivot_data_qual[1]['OBTAINED_MARKS'];
		}else{
			$pivot_value = $pivot_data_qual[0]['OBTAINED_MARKS'];
		}
		if($order=="DESC"){
			//$array[$i]['FORM_DATA']


			if ($search_value > $pivot_value)
				$left_element[] = $array[$i];
			else
				$right_element[] = $array[$i];
		}else {
			if ($search_value < $pivot_value)

				$left_element[] = $array[$i];
			else
				$right_element[] = $array[$i];
		}
	}

	return array_merge(quicksort_form_verification($left_element,$key,$order), array($pivot_element), quicksort_form_verification($right_element,$key,$order));
}

function quicksort($array,$key,$order="ASC"){
    // ini_set('memory_limit', '-1');
    if (count($array) == 0)
        return array();

    $pivot_element = $array[0];
    $left_element = $right_element = array();

    for ($i = 1; $i < count($array); $i++) {
        if($order=="DESC"){
            if ($array[$i][$key] > $pivot_element[$key])
                $left_element[] = $array[$i];
            else
                $right_element[] = $array[$i];
        }else {
            if ($array[$i][$key] < $pivot_element[$key])
                $left_element[] = $array[$i];
            else
                $right_element[] = $array[$i];
        }
    }

    return array_merge(quicksort($left_element,$key,$order), array($pivot_element), quicksort($right_element,$key,$order));
}

function find_qualification($qualifications,$degree_id){
    //  prePrint($qualifications);
     
     foreach($qualifications as $qualification){
         if($qualification['DEGREE_ID'] == $degree_id)
         {
             return $qualification;
         }
     }
     return null;
 }
 
 function send_form_status_email($form_data){

    $APPLICATION_ID = $form_data['APPLICATION_ID'];
    $MESSAGE        = $form_data['MESSAGE'];
    $FORM_DATA_JSON = $form_data['FORM_DATA'];
    $FORM_STATUS_JSON= $form_data['FORM_STATUS'];
    $CAMPUS_NAME    = $form_data['NAME'];
    $PROGRAM_TITLE  = $form_data['PROGRAM_TITLE'];
    $STATUS_NAME    = $form_data['STATUS_NAME'];
    $YEAR           = $form_data['YEAR'];
    
    $FORM_DATA_JSON = json_decode($FORM_DATA_JSON,true);
    $users_reg      = $FORM_DATA_JSON['users_reg'];
    $email          = $users_reg['EMAIL'];
    $name           = $users_reg['FIRST_NAME'];
    $fname          = $users_reg['FNAME'];
    $cnic_no        = $users_reg['CNIC_NO'];
    
    // $email = "vk.rajani@usindh.edu.pk";
    
    $from = 'admission@usindh.edu.pk';
    $from_name ='UoS Admission';

    $subject ='Application Form Status Updated';
    $body = "
        <style>
            table, td, th {
                style='border: 1px solid black;'
                }
            table {
                    width: 100%;
                    border-collapse: collapse;
                    padding:7px;
            }
            th {
                width:15%;
                text-align:left;
            }
</style>

    <img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br>  <h3>Assalam  Alaikum,</h3>"
        ."<p>Your application status has been updated. Kindly <a href='admission.usindh.edu.pk' target='_new'> login into your eportal account </a> & visit dashboard for more information.</p>".
        "<br>
        <table border='1' style='border: 1px solid black; width: 100%; border-collapse: collapse; padding:7px;'>
        
        <tr style='padding:7px;'><th style='width:15%; text-align:left; padding:7px;'>Application No</th> <td> $APPLICATION_ID </td> </tr>
         <tr style='padding:7px;'> <th style='width:15%; text-align:left; padding:7px;'>Name</th> <td> $name </td> </tr>
         <tr style='padding:7px;'> <th style='width:15%; text-align:left; padding:7px;'>Father Name</th> <td> $fname </td> </tr>
 <tr style='padding:7px;'> <th style='width:15%; text-align:left; padding:7px;'>Cnic No</th> <td> $cnic_no </td> </tr>
 <tr style='padding:7px;'> <th style='width:15%; text-align:left; padding:7px;'>Applied Campus</th> <td> $CAMPUS_NAME </td> </tr>
 <tr style='padding:7px;'> <th style='width:15%; text-align:left; padding:7px;'>Applied Program</th> <td> $PROGRAM_TITLE $YEAR </td> </tr>
 <tr style='padding:7px;'> <th style='width:15%; text-align:left; padding:7px;'>Application Status</th> <td> $STATUS_NAME </td> </tr>
 <tr style='padding:7px;'> <th style='width:15%; text-align:left; padding:7px;'>Message</th> <td style='color:red'> $MESSAGE </td> </tr>
       
        </table>
                     
                      <br><br>
                      Best Regards, <br>
                      -------------------------------------<br>
                      Director Admission<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
    
    return $m;
 }
 
 function binary_search($list_of_array,$key,$search_value){
    $beg = 0;
    $end = count($list_of_array);
    $mid = round(($beg+$end)/2);
    $end--;
    $index= -1;
    while($beg<=$end){
        if($list_of_array[$mid][$key]==$search_value){
            $index = $mid;
            $check = false;
            break;
        }else if($list_of_array[$mid][$key]<$search_value){
            $beg = $mid+1;
        }else if(($list_of_array[$mid][$key]>$search_value)){
            $end = $mid-1;
        }
        $mid = round(($beg+$end)/2);
    }
    return $index;
}
 
 function isValidEmail($email)
 {
     
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else {
      return false;
    }
 }
 function itsc_url(){
     return "https://itsc.usindh.edu.pk/eportal/public/";
 }
 function send_unlock_mail($email){

    $from = 'admission@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='UNLOCK APPLICATION FORM';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br>  <h3>Assalam  Alaikum,</h3><br>"
        ."<h3>Your application has been unlocked. Kindly edit and submit your application form before due date</h3>".
        "<br><br><b><a href='".base_url()."'>Click Here To Login Admission Portal</a></b><br>
                     
                      <br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
 }
function send_confirmation_email($email,$password){

    $from = 'admission@usindh.edu.pk';
    $from_name ='ADMISSIONS';
    $subject ='WELLCOME TO UNIVERSITY OF SINDH';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> <p style='font-size:14pt'>السلام عليكم</p><br>
You have been successfully registered on admission portal, you can Login using CNIC /B-Form No and Password for further process.  <a href='".base_url()."assets/advertisement_2022.pdf'>Advertisement - Admissions 2022</a> <br><br>Your password is: <b>$password</b><br>
<p>You will have to add your qualifications later on for that you will be notified through Email. Keep visiting your email account and E-portal account dashboard for further process regarding Admissions 2022.</p>".
" 
<p> <a href='https://youtu.be/F7S-NMvJTNw'>Click here to watch tutorial how to fill online admission form?</a></p>
Prepare the following documents in softcopy before filling the online admission form.<br> <br>  
1.	 “Admission copy” of paid up challan of Admission application processing fee. (Rs. 2500/=) (Original)<br>
2.	Matriculation (S.S.C-Part II) - Marks and Pass Certificates (Original)<br>
3.	Intermediate (HSC-Part II) - Marks and Pass Certificates (Original)<br>
4.	Bachelor’s degree (14 years OR 16 years ) - Marks and Pass Certificates  for admission in Master’s degree program (Original)<br>
5.	HEC LAT score card for admission in L.L.B Program <br>
6.	Computerized National Identity Card (CNIC) / B-Form from NADRA. (Original)<br>
7.	Domicile Certificate and Permanent Residence Certificate (Form- C) (Original)<br>
".
        "<br><b><a href='".base_url()."'>Click Here for login and fill your online admission form </a></b><br> 
                     
                      <br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      DIRECTOR ADMISSIONS<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
 }

function findObjectinList($list,$key,$value){
    foreach($list as $obj){
        if($obj[$key]==$value){
            return $obj;

        }
    }
    return false;
}

function getcsrf($obj){
    $reponse = array(
        'csrfName' => $obj->security->get_csrf_token_name(),
        'csrfHash' => $obj->security->get_csrf_hash()
    );
    return $reponse;
}

function passwordRule($password){
    $pattern = "/(?=.*[a-z]).{8,50}/";
// (?=.*[0-9])
// (?=.*[A-Z])

    if(preg_match($pattern, $password)){
        // $special_char = "!@#$%^&*()+=-[]';,./{}|:<>?~";
        // if (false === strpbrk($password, $special_char))
        //     return false;
        // else
        //     return true;
        return true;
    }else{
        return false;
    }

}
function EncryptThis($ClearTextData,$ENCRYPTION_ALGORITHM='AES-256-CBC',$ENCRYPTION_KEY='this is key') {

    $EncryptionKey = base64_decode($ENCRYPTION_KEY);
    $InitializationVector  = openssl_random_pseudo_bytes(openssl_cipher_iv_length($ENCRYPTION_ALGORITHM));
    $EncryptedText = openssl_encrypt($ClearTextData, $ENCRYPTION_ALGORITHM, $EncryptionKey, 0, $InitializationVector);
    return base64_encode($EncryptedText . '::' . $InitializationVector);
}

function DecryptThis($CipherData,$ENCRYPTION_ALGORITHM='AES-256-CBC',$ENCRYPTION_KEY='this is key') {

    $EncryptionKey = base64_decode($ENCRYPTION_KEY);
    list($Encrypted_Data, $InitializationVector ) = array_pad(explode('::', base64_decode($CipherData), 2), 2, null);
    return openssl_decrypt($Encrypted_Data, $ENCRYPTION_ALGORITHM, $EncryptionKey, 0, $InitializationVector);
}
function my_encode($msg){
//$msg = base64url_encode(base64_encode(urlencode($msg)));
    return EncryptThis($msg);
}
function my_decode($msg){
    //$msg = urldecode(base64_decode(base64url_decode($msg)));
    return DecryptThis($msg);
}
function getTokenForRedirect($REQUEST_FROM,$REQUEST_TO,$SERVICE,$USER_REG_ID,$DATA_TIME,$REQUEST_PAGE,$ROLE=-1){
    $userAgent = userAgent();

    $token = array
    (
        "REQUEST-FROM"=>$REQUEST_FROM,
        "REQUEST-TO"=>$REQUEST_TO,
        "REQUEST-PAGE"=>$REQUEST_PAGE,
        "SERVICE"=>$SERVICE,
        "USER_REG_ID"=>$USER_REG_ID,
        "TOKEN_DATETIME"=>$DATA_TIME,
        "USER-AGENT"=>$userAgent,
        "USER_ROLE"=>$ROLE,
    );
    $token = json_encode($token);

    $token_encoded = base64url_encode(base64_encode(urlencode($token)));
    return $token_encoded;
}
function writeLogFile($user,$text){
    // prePrint("method call");
    $user_id = $user['USER_ID'];
    $min = 1;
    $max = 5000;
    while(!($min<=$user_id&&$user_id<=$max)){
        $min += 5000;
        $max += 5000;
    }
    $path = "../log/$min-$max";
    if(!file_exists($path)){
        $result = mkdir ($path);
        chmod("$path", 0755);
    }
    $path.="/".$user_id.".txt";
    $date_time =date('Y F d l h:i A');
    //printDateTime($date)


    $data ="[$date_time] $text\n";
    //file_put_contents($path, $data, FILE_APPEND | LOCK_EX);
    $fp = fopen($path,'a+');//opens file in append mode

    fwrite($fp, $data);

    fclose($fp);




}
function printDateTime($date)
{
    $date = strtotime($date);
    $date_day = date('d', $date);
    $day = date('l', $date);
    $month = date('F', $date);
    $year = date('Y', $date);
    $hour = date('h', $date);
    $min = date('i', $date);
    $am = date('A', $date);
    $time = $hour . ":" . $min . " " . $am;

    $date_time = $day . " " . $date_day . ' ' . $month . ' ' . $year . ' ' . $time;
    return $date_time;
}
function writeQuery($text){
    // prePrint("method call");

    $path = "../log/ftp_log";
    if(!file_exists($path)){
        $result = mkdir ($path);
        chmod("$path", 0755);
    }
    $path.="/ftp_log.txt";
    $date_time =date('Y F d l h:i A');
    //printDateTime($date)


    $data ="$text\n";
    //file_put_contents($path, $data, FILE_APPEND | LOCK_EX);
    $fp = fopen($path,'a+');//opens file in append mode

    fwrite($fp, $data);

    fclose($fp);




}
function encode($value){
    return $value;
//    return base64_encode($value);
}

function decode($value){
    return base64_decode($value);
}
function isValidData($data){

    //$data = htmlspecialchars(addslashes(trim($data)), ENT_QUOTES, 'UTF-8');;
    $data = addslashes(trim($data));
    return $data;
}

function getDateForDatabase($orgDate){
    $arr = explode('/',$orgDate);
    $d= $arr[0];
    $m=$arr[1];
    $y=$arr[2];
    //$newDate = date("Y-m-d", strtotime($orgDate));
    return "$y-$m-$d";
}
function getDateForView($orgDate){
    $newDate = date("d/m/Y", strtotime($orgDate));
    return $newDate;
}
function getDateCustomeView($orgDate,$format){
    $newDate = date($format, strtotime($orgDate));
    return $newDate;
}
function isValidTimeDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
function prePrint($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
if(!function_exists('redirect')){
    function redirect($path){
        echo "<script>";
        echo "window.location.href='$path';";
        echo "</script>";

    }
}

function mergeDateAndTime($date)
{
    date_default_timezone_set('Asia/karachi');
    $time = date('H:i:s');
    $combinedDT = date('Y-m-d H:i:s', strtotime("$date $time"));
    return $combinedDT;
}
function cryptPassowrd($password){
    $algorithm  = '$2a$07$youcantseethisisthejadoosalt$';
    $password = md5($password);
    $password = sha1($password);
    $password =  crypt($password,$algorithm);
    return $password;
}
function cryptPassowrdWithOutMD5($password){
    $algorithm  = '$2a$07$youcantseethisisthejadoosalt$';
    // $password = md5($password);
    $password = sha1($password);
    $password =  crypt($password,$algorithm);
    return $password;
}
function uploadImageByPath($image,$folder_path,$db_path,$name){

    $path_list = array();

    for($i = 0 ; $i < count($image['name']) ; $i++) {
        if($image['error'][$i]==0) {
            $post_image = $image['name'][$i];
            $post_image_temp = $image['tmp_name'][$i];


            $length = strrpos($post_image, ".");
            $image_type = substr($post_image, $length);
            $image_name = $name . "" . ($i + 1) . "" . $image_type;
            if (isValidFile($image_type)) {


                if (!file_exists($folder_path)) {
                    mkdir($folder_path, 0777, true);
                }
                $db_path1 = "$db_path/$image_name";
                $folder_path1 = "$folder_path/$image_name";
                move_uploaded_file($post_image_temp, $folder_path1);
                $path_list[$i] = $db_path1;
            } else {
                // echo "asd";
                $path_list[$i] = false;
            }
        }else{
            return false;
        }

    }
    return $path_list;
}
function isValidFile($ext,$size){
    $ext = strtolower($ext);
    $max_size = 1024*1024*MAX_FILE_SIZE;
    $file_type = array("bmp", "jpg", "jpeg", "jpe", "jfif", "png", "gif","doc","txt", "pdf","docx","ppt","pptx","xls","xlsx","mp3","mp4","flv");
    $check = true;
    for( $j = 0; $j < count($file_type);$j++){

        if($ext == $file_type[$j])
        {
            $check = false;
            break;
        }
    }
    if($size>$max_size){
        return false;
    }
    if($check)
    {
        return false;
    }else{
        return true;
    }
}
function isValidFileExt($ext){
    $ext = strtolower($ext);
    $max_size = 1024*1024*MAX_FILE_SIZE;
    $file_type = array("bmp", "jpg", "jpeg", "jpe", "jfif", "png", "gif","doc","txt", "pdf","docx","ppt","pptx","xls","xlsx");
    $check = true;
    for( $j = 0; $j < count($file_type);$j++){

        if($ext == $file_type[$j])
        {
            $check = false;
            break;
        }
    }

    if($check)
    {
        return false;
    }else{
        return true;
    }
}
function isValidImage($files)
{
    $size = 1024*MAX_IMAGE_SIZE*1;

    if(($files['type']=="image/jpeg" || $files['type']=="image/jpg" || $files['type']=="image/png") && $files['size']<=$size){
        // echo "true<br><br><br>";
        return true;
    }
    //echo "false<br><br><br>";
    return false;
}
function uploadProfileImage($image,$id,$name){

    $post_image = $image['name'];
    $post_image_temp = $image['tmp_name'];
    //$post_image_type = $file['image']['type'];
    $length= strrpos($post_image,".");
    $image_type=substr($post_image, $length);
    $image_name = $name."_".$id."".$image_type;
    $dir = $id;
    $dir_path = "../../eportal_resource/images/applicants_profile_image";
    if(!file_exists($dir_path)){
        mkdir($dir_path,0777,true);
    }
    $path = "$dir_path/$image_name";
    move_uploaded_file($post_image_temp,$path );
    //return getBaseUrl()."../images/applicants/$dir/$image_name";
    //return "../../eprotal_resource/images/applicants/$dir/$image_name";
    return $path;
}

function uploadImage($image,$id,$name){

    $post_image = $image['name'];
    $post_image_temp = $image['tmp_name'];
    //$post_image_type = $file['image']['type'];
    $length= strrpos($post_image,".");
    $image_type=substr($post_image, $length);
    $image_name = $name."_".$id."".$image_type;
    $dir = $id;
    $dir_path = "../../eportal_resource/images/applicants/$dir";
    if(!file_exists($dir_path)){
        mkdir($dir_path,0777,true);
    }
    $path = "$dir_path/$image_name";
    move_uploaded_file($post_image_temp,$path );
    //return getBaseUrl()."../images/applicants/$dir/$image_name";
    //return "../../eprotal_resource/images/applicants/$dir/$image_name";
    return $path;
}
function getIpAddress() {
    // check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // check for IPs passing through proxies
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check if multiple ips exist in var
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (validate_ip($ip))
                    return $ip;
            }
        } else {
            if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];

    // return unreliable ip since all else failed
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;

    // generate ipv4 network address
    $ip = ip2long($ip);

    // if the ip is set and not equivalent to 255.255.255.255
    if ($ip !== false && $ip !== -1) {
        // make sure to get unsigned long representation of ip
        // due to discrepancies between 32 and 64 bit OSes and
        // signed numbers (ints default to signed in PHP)
        $ip = sprintf('%u', $ip);
        // do private network range checking
        if ($ip >= 0 && $ip <= 50331647) return false;
        if ($ip >= 167772160 && $ip <= 184549375) return false;
        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
        if ($ip >= 4294967040) return false;
    }
    return true;
}
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function userAgent ()
{
    $iphone 	= strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $android 	= strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    $palmpre 	= strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
    $berry 		= strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
    $ipod 		= strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $win 		= strpos($_SERVER['HTTP_USER_AGENT'],'Windows');
    $Macintosh 	= strpos($_SERVER['HTTP_USER_AGENT'],'Macintosh');
    $Linux 		= strpos($_SERVER['HTTP_USER_AGENT'],'Linux');

    if($iphone == true)
        $user_agent = "iPhone";
    elseif ($android == true)
        $user_agent= "Android";
    elseif ($palmpre == true)
        $user_agent= "WebOS";
    elseif ($berry == true)
        $user_agent= "BlackBerry";
    elseif ($ipod == true)
        $user_agent= "iPod";
    elseif ($win == true)
        $user_agent= "Windows";
    elseif ($Macintosh == true)
        $user_agent= "Macintosh";
    elseif ($Linux == true)
        $user_agent= "Linux";
    else
        $user_agent=$_SERVER['HTTP_USER_AGENT'];

    return $user_agent;
}//function

function getUserAgent(){

    $useragent=$_SERVER['HTTP_USER_AGENT'];

    return $useragent;
//    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
//        return "Mobile";
//
//    return "PC/Laptop";
}
function sendTokenByEmail($email,$token){
    $from = 'admission@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='Your Verification Code';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum,<br>".
        "         
                      <br><br><b> Your verification code is: $token </b><br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>
                      E-portal url: <a href='http://eportal.usindh.edu.pk/'>http://eportal.usindh.edu.pk</a>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
}
function sendPasswordByEmail($email,$token){
    $from = 'admission@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='Your New Password';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum,<br>".
        "         
                      <br><br><b> Your new Password is: $token </b><br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>
                      E-portal url: <a href='http://eportal.usindh.edu.pk/'>http://eportal.usindh.edu.pk</a>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
}
function sendPasswordTokenByEmail($email,$token,$user_id){


    $token= urlencode(EncryptThis($token));
    $user_id= urlencode(EncryptThis($user_id));
    $from = 'admission@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='We have received password reset request';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum, <br/> Dear Candidate<br>
We have recieved password reset request for your account of E-Portal. Please visit the following link to reset your password:<br>".
        "         
                      <br><br><b><a href='".base_url()."forget/set_pwd/$user_id/$token'>Password Reset Link Click Here</a></b><br>
                      Note that above link for password reset is valid for one time use only
                      <br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
}
function uploadFile($image,$id,$c_m_id,$name){
//$cf_id/$topic_id
//print_r($image);
    $post_image = $image['name'];
    $post_image_temp = $image['tmp_name'];
    //$post_image_type = $file['image']['type'];
    $length= strrpos($post_image,".");
    $image_type=substr($post_image, $length);
    //$image_type=str_replace(,'',$post_image);
    $image_name = $name."_".$c_m_id."".$image_type;
    $image_name = $c_m_id."_".$post_image;
    $dir = $id;
    $dir_path = "../../DATA-CENTRE/LMS/$id";
    if(!file_exists($dir_path)){
        mkdir($dir_path,0777,true);
    }

    $image_name = str_replace(array('#',' ','?','/','','<','>','~','!',':',';','+',"=","'",'"','@','%'),"_",$image_name);
    $path = "$dir_path/$image_name";
    move_uploaded_file($post_image_temp,$path );
    //return getBaseUrl()."images/applicants/$dir/$image_name";
    return $dir_path."/".$image_name;
}

function ftp_mksubdirs($ftpcon,$ftpbasedir,$ftpath){



    @ftp_chdir($ftpcon, $ftpbasedir); //   /public_ftp
    $parts = explode('/',$ftpath); // 2013/06/11/username
    foreach($parts as $part){
        if(!@ftp_chdir($ftpcon, $part)){
            ftp_mkdir($ftpcon, $part);
            ftp_chdir($ftpcon, $part);
            //ftp_chmod($ftpcon, 0777, $part);
        }
    }
}


function writeFtp($connection,$base,$ftpath,$destination,$source){

    ftp_mksubdirs($connection,$base,$ftpath);

    $destination="$base/$ftpath/$destination";
    // ftp_chdir($connection, "$base/$ftpath/");
//    ftp_pasv ( $connection, true );
    //print_r($destination);
    $res = ftp_put($connection,$destination, $source, FTP_BINARY);
//var_dump($res);
    if ($res)
    {
        //  echo "Successfully uploaded $source.";
        return true;
    }
    else
    {
        //echo "Error uploading $source.";
        return false;
    }

}
function getBaseUrlFtp(){
    return "https://itsc.usindh.edu.pk/";
}
function getPrefixInListById($PREFIXS,$PREFIX_ID){
    foreach ($PREFIXS as $PREFIX){
        if($PREFIX['PREFIX_ID']==$PREFIX_ID){
            return $PREFIX['PREFIX'];
        }
    }
    return '';
}
function getUserInListById($user_list,$user_id){
    foreach ($user_list as $user){
        if($user['USER_ID']==$user_id){
            return $user;
        }
    }
    return null;
}
function sendMultipleEmail($email_list,$msg,$sender='admission@usindh.edu.pk'){
    $msg = nl2br($msg);
    $from = $sender;
    $from_name ='IT Services Support Team';
    $subject ='Digital Notice';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum,<br>".
        "         
                      <br><br><b> $msg </b><br><br>
                      <strong>Note: This is a system generated email notification from University of Sindh Learning Management System (LMS), hence do not reply to this email. </strong>
                      ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: '.$sender. "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <$sender>\r\n";

    foreach ($email_list as $email_obj){
        $email =  $email_obj['EMAIL'];
        if(mail($email,$subject,$body,$headers)){
            $m= "Your notice send at $email <br>";


        }else{
            $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
        }
    }

}
function convert_number_to_words($number) {

    $hyphen      = ' ';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';

    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }
    $number = (int) $number;
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}//method
function sendVerificationEmail($email,$token){


   
    $from = 'admission@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='Email Verification Code';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum, <br/> Dear Candidate,<br>
We have recieved your registration request for University of Sindh admissions portal.<br>".
        "      
                      <br><br><b style='font-size:30px;'>Email verification code is:  <span style='color:red'>$token</span></b><br><br>
                      <br><b>Do not share this code with anyone.</b><br>
                      <a href='https://www.youtube.com/c/ZainNetworks?sub_confirmation=1'>CLICK HERE</a> to watch tutorial how to fill online admission form without giving qualifications & admission process.
                      <br><br>
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: admission@usindh.edu.pk<br>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
}
function sendVerificationMobile($mobile,$token){
    // sendVerificationEmail("kscsm32@gmail.com",$token);
     $message = "Dear Applicant,
University of Sindh admissions portal Mobile No verification code is: $token
Do not share this code with anyone.";
     $mobile = "92".$mobile;
     smsSender($mobile,$message);
}
function smsSender($to,$message){
    
    // $return = api_sms_lifetime($to,$message);
    $return = api_sms_zong($to,$message);
    prePrint($return);
}

function api_sms_lifetime($to,$message){
    
    $url = "https://lifetimesms.com/plain";

    $parameters = [
        "api_token" => "fb2224b9cae75a2e659c152271b8d06d6337c24547",
        "api_secret" => "@@usindh_itsc##",
        "to" => "$to",
        "from" => "USINDH",
        "message" => "$message",
    ];

    $ch = curl_init();
    $timeout  =  30;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $response = curl_exec($ch);
    curl_close($ch);

return $response;
}

function api_sms_zong($to,$message){
    
    ini_set("soap.wsdl_cache_enabled", 0);
    $url        = 'http://cbs.zong.com.pk/ReachCWSv2/CorporateSMS.svc?wsdl';
    $client     = new SoapClient($url, array("trace" => 1, "exception" => 0));
    $loginId = "923128851833";
    $loginPassword = "@Usindh123@";
    $Mask = "USINDH";
    echo "<pre>";
    
                $resultBulkSMS = $client->QuickSMS(  
                    array('obj_QuickSMS' => 
                                array(	'loginId'=>  $loginId, //here type your account name
                                        'loginPassword'=>$loginPassword, //here type your password
                                        'Mask'=>$Mask, //here set allowed mask against your account or you will get invalid mask
										'Message'=>$message,//Your Messge Text
										'UniCode'=>'0', //If sms is unicode place 1 otherwise 0
										'ShortCodePrefered'=>'n',
										'Destination'=>$to //Destination Mobile No
									))
              );

// echo "<br>REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";

return $resultBulkSMS->QuickSMSResult;
// print_r($resultBulkSMS);
}

function postCURL($_url, $_param){

	    $data_string = json_encode($_param);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);    
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json')
			);
        $output=curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array("response"=>$output,"response_code"=>$httpcode);
    }
function emailDeveloperLog($subject,$msg,$email='developer@usindh.edu.pk'){
    $from = 'admission@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    
    $body = "$msg";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: admission@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <admission@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
}