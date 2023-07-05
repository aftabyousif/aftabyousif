<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PDF extends FPDF
{
	public $array_x=array();
	public $array_y=array();
	public $array_w=array();
	public $array_h=array();
	public $count=0;

	function customCell($w,$h,$txt,$bdr,$alin,$ln=0,$fill=false){
		//$this->SetFont('Time','','',0)
		$prey=$this->GetY();
		$prex=$this->GetX();
		$this->MultiCell($w,$h,$txt,0,$alin,$fill);
		$currnty=$this->GetY();
		if($ln== 0){
			$this->SetXY($prex+$w,$prey);
		}
		if($bdr==1){
			$this->Rect($prex,$prey,$w,$currnty-$prey);
		}


	}

	function TableCell($w,$h,$txt,$bdr,$alin,$ln=0,$fill=false){
		//$this->SetFont('Time','','',0)
		$prey=$this->GetY();
		$prex=$this->GetX();
		$this->MultiCell($w,$h,$txt,0,$alin,$fill);
		$currnty=$this->GetY();
		$this->array_x[$this->count]= $prex;
		$this->array_y[$this->count]= $prey;
		$this->array_w[$this->count]= $w;
		$this->array_h[$this->count]= $currnty;
		$this->count++;
		if($ln== 0){

			$this->SetXY($prex+$w,$prey);
		}else{
			if(count($this->array_h)>0)
				$max_h = max($this->array_h);
			for($i=0;$i<$this->count ;$i++){
				$p_x =  $this->array_x[$i];

				$p_y = $this->array_y[$i];
				$p_w = $this->array_w[$i];
				$this->Rect($p_x,$p_y,$p_w,$max_h-$prey);
			}
			$this->SetY($max_h);
			$this->count=0;

		}
		if($bdr==1){
			//$this->Rect($prex,$prey,$w,$currnty-$prey);
		}
	}

	function Header()
	{
		$this->SetFont('Arial','B',15);
	}
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		// $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$pdf = new PDF('P','mm','A4');

$pdf->AddPage();
$pdf->Image('assets/img/eligibility_cert.jpg',-5,-11,220,325);
$first_column_width = 50;
$sec_column_width = 109;
$row_height = 6;
$left_align=15;
$app_camp="ALLAMA I.I. KAZI CAMPUS JAMSHORO";

$qualifications = $QUALIFICATION;
$eligibility_certificate = $ELIGIBILITY_CERTIFICATE;
$application=$APPLICATION;

$user_id=$APPLICATION['USER_ID'];
$part_name=$APPLICATION['PART_NAME'];
$eligibility_certificate_id = str_pad($ELIGIBILITY_CERTIFICATE['ELIGIBILITY_CERTIFICATE_ID'], 8, '0', STR_PAD_LEFT);
$application_id=$APPLICATION['APPLICATION_ID'];
$campus_name=$APPLICATION['CAMPUS_NAME'];
$session= $APPLICATION['SESSION_CODE'];
$year= $APPLICATION['YEAR'];
$program_type_title= $APPLICATION['PROGRAM_TYPE_TITLE'];
$program_title= $APPLICATION['PROGRAM_TITLE'];
$name = strtoupper($APPLICATION['FIRST_NAME']);
$roll_no = strtoupper($APPLICATION['ROLL_NO']);
$fname = strtoupper($APPLICATION['FNAME']);
$last_name =strtoupper($APPLICATION['LAST_NAME']);
$GENDER = strtoupper($APPLICATION['GENDER'])=='M'?"MALE":"FEMALE";
$MOBILE_NO = ($APPLICATION['MOBILE_CODE']=="0092"?"0":$APPLICATION['MOBILE_CODE'])."".$APPLICATION['MOBILE_NO'];
$CNIC_NO =strtoupper($APPLICATION['CNIC_NO']);
$DATE_OF_BIRTH =getDateCustomeView($APPLICATION['DATE_OF_BIRTH'],'d-m-Y');
$issue_date =getDateCustomeView($eligibility_certificate['ISSUE_DATE'],'d-m-Y');
$admission_date =getDateCustomeView($application['PAID_DATE'],'d-m-Y');
$EMAIL =strtolower($APPLICATION['EMAIL']);

$pdf->SetFillColor(0,0,0);

$current_date = date("d-m-Y");

$data = $application_id.'~'.$eligibility_certificate_id.'~'.$roll_no.'~'.$current_date.'~'.$issue_date.'~ELIGIBILITYCERTIFICATE';
$qr_file_name = str_replace('/','_',$data);

$profile_image = PROFILE_IMAGE_CHECK_PATH.$APPLICATION['PROFILE_IMAGE'];

if(!is_dir('../eportal_resource/enrolment_qr')){
	mkdir('../eportal_resource/enrolment_qr');
}

QRcode::png("$data","../eportal_resource/enrolment_qr/".$qr_file_name.".png", 'QR_ECLEVEL_L', 3, 2);
$path="../eportal_resource/enrolment_qr/".$qr_file_name.".png";

$pdf->SetFont("Times",'',10);
$pdf->ln(20);
$pdf->Cell($left_align);
$pdf->Cell(190,$row_height,"Serial No. {$eligibility_certificate_id}",0,1);
$pdf->text(170,53,$application_id);
$pdf->Image($path,165,30,20,20);
//$pdf->Image('assets/img/University_of_Sindh_logo.png',90,6,26,26);
$pdf->Image($profile_image,155,75,28,30); //23 28

$pdf->ln(18);
//$pdf->SetFont("Times",'B',20);
//$pdf->Cell(0,$row_height,"UNIVERSITY OF SINDH",0,1,'C',false);
$pdf->Ln(32);
$pdf->SetFont("Times",'',15);
$pdf->Cell(55, 25, '', 0, 0, 'C');
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(80,$row_height,"CERTIFICATE OF ELIGIBILITY",0,1,'C',true);
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(1);

$pdf->SetFont("Times",'B',11);
$pdf->Cell($left_align);
$pdf->customCell($first_column_width,$row_height,"Student Profile",0,'L',1);
$pdf->SetFont("Times",'',10);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Name",1,'L',0);

$pdf->TableCell($sec_column_width-31,$row_height,$name,1,'L',1);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Father's Name",1,'L',0);

$pdf->TableCell($sec_column_width,$row_height,$fname,1,'L',1);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Surname",1,'L',0);
$pdf->TableCell($sec_column_width,$row_height,$last_name,1,'L',1);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Gender",1,'L',0);
$pdf->TableCell($sec_column_width,$row_height,$GENDER,1,'L',1);

$pdf->Ln(1);
$pdf->SetFont("Times",'B',11);
$pdf->Cell($left_align);
$pdf->customCell($first_column_width,$row_height,"Academic Record",0,'L',1);

$pdf->SetFont("Times",'B',9);

$pdf->Cell($left_align);
$pdf->TableCell($first_column_width-5,$row_height-1,"Examination Passed ",1,'C',0);
$pdf->TableCell(24,$row_height-1,"Group",1,'C',0);
$pdf->TableCell(15,$row_height-1,"Marks Obtained",1,'C',0);
$pdf->TableCell(15,$row_height-1,"Total Marks",1,'C',0);
$pdf->TableCell(10,$row_height-1,"Year",1,'C',0);
$pdf->TableCell(15,$row_height-1,"Seat No.",1,'C',0);
$pdf->TableCell($first_column_width-14,$row_height-1,"Name of Board/University",1,'C',1);

$pdf->SetFont("Times",'',9);
$check = 0;
$count_qual =  count($qualifications)-1;
if($application['PROGRAM_TYPE_ID']==2){
	if($qualifications[0]['DEGREE_ID']==10){
		$last = $qualifications[1]['DEGREE_ID'];
	}else{
		$last = $qualifications[0]['DEGREE_ID'];
	}
	$list_degree = array(2,3,$last);
}
if($application['PROGRAM_TYPE_ID']==1){
	$list_degree = array(2,3);
}
for($i=$count_qual ; $i>=0;$i--){

	$qualification = $qualifications[$i];

	if(in_array($qualification['DEGREE_ID'], $list_degree)){
		$pdf->Cell($left_align);
		$pdf->TableCell($first_column_width-5,$row_height-1,$qualification['DEGREE_TITLE'],1,'L',0);
		$pdf->TableCell(24,$row_height-1,$qualification['DISCIPLINE_NAME'],1,'L',0);
		$pdf->TableCell(15,$row_height-1,$qualification['OBTAINED_MARKS'],1,'L',0);
		$pdf->TableCell(15,$row_height-1,$qualification['TOTAL_MARKS'],1,'L',0);
		$pdf->TableCell(10,$row_height-1,$qualification['PASSING_YEAR'],1,'L',0);
		$pdf->TableCell(15,$row_height-1,$qualification['ROLL_NO'],1,'L',0);
		$org = $qualification['ORGANIZATION'];
		if(strlen($qualification['ORGANIZATION'])>30){
		    $org = getShortForm($qualification['ORGANIZATION']);
		}
		$pdf->TableCell(36,$row_height-1,$org,1,'L',1);
	}
}

$pdf->Ln(1);

if($campus_name=="UNIVERSITY OF SINDH, JAMSHORO"){
	$campus_name=$app_camp;
}

$pdf->SetFont("Times",'B',11);
$pdf->Cell($left_align);
$pdf->customCell($first_column_width,$row_height,"Admission Details",0,'L',1);
$pdf->SetFont("Times",'',10);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Campus",1,'L',0);
$pdf->SetFont("Times",'B',10);
$pdf->TableCell($sec_column_width,$row_height,$campus_name,1,'L',1);
$pdf->SetFont("Times",'',10);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Degree Program Type",1,'L',0);
$pdf->SetFont("Times",'B',10);
$pdf->TableCell($sec_column_width,$row_height,$program_type_title,1,'L',1);
$pdf->SetFont("Times",'',10);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Degree Program Title",1,'L',0);
$pdf->SetFont("Times",'B',10);
$pdf->TableCell($sec_column_width,$row_height,$program_title,1,'L',1);
$pdf->SetFont("Times",'',10);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Eligibility No.",1,'L',0);
$pdf->SetFont("Times",'B',10);
$pdf->TableCell($sec_column_width,$row_height,$roll_no,1,'L',1);
$pdf->SetFont("Times",'',10);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Admission Academic Year",1,'L',0);
$pdf->SetFont("Times",'B',10);
$pdf->TableCell($sec_column_width,$row_height,$year,1,'L',1);
$pdf->SetFont("Times",'',10);
$pdf->Cell($left_align);
$pdf->TableCell($first_column_width,$row_height,"Date of Admission",1,'L',0);
$pdf->SetFont("Times",'B',10);
$pdf->TableCell($sec_column_width,$row_height,$admission_date,1,'L',1);
$pdf->SetFont("Times",'',10);

$pdf->SetFont("Times",'B',12);
$pdf->text($row_height+20,225,'Date of issue: ');
$pdf->SetFont("Times",'B',11);
$pdf->text($row_height+45,225,$issue_date);

$pdf->SetFont("Times",'B',12);
$pdf->text($row_height+20,230,'Printed on: ');
$pdf->SetFont("Times",'',11);
$pdf->text($row_height+45,230,date('d-m-Y ; h:i A'));

$pdf->SetFont("Times",'B',11);
$pdf->text($row_height+132,230,'Deputy Registrar (General)');
$pdf->text($row_height+128,235,'University of Sindh, Jamshoro');

$pdf->setY(239);
$pdf->SetFont("Times",'BI',10);
$pdf->Cell($left_align);
$pdf->Cell(0,$row_height,"The University of Sindh reserves the right to withdraw/ cancel/ correct this issued Eligibility Certificate at",0,1);
$pdf->Cell(70,$row_height);
$pdf->Cell(0,$row_height,"any stage based on original record/ documents.",0,1);
$pdf->Ln(1);

$pdf->SetFont("Times",'BI',10);
$pdf->Cell(30,$row_height);
$pdf->Cell(0,$row_height,"This is system generated Certificate of Eligibility; hence it does not require any signature.",0,1);

$pdf->SetFont('Times','',10);
$pdf->text(25,265,"Powered by: Information Technology Services Centre (ITSC)");
$pdf->SetFont('Times','',7);
$pdf->text(85,273,$data);

$file_name = str_replace('/','_',$roll_no)."_EL.pdf";
$file_name_saving_path="../eligibility_certificates/".$file_name;
$attachments = array ($file_name_saving_path);

$pdf->Output($file_name_saving_path,'F');
$email_body="<p>$name $last_name,</p>
			<p>Your eligibility certificate is successfully generated against Roll No. $roll_no</p>
			<p>Please download your eligibility certificate for future use and download link will be expired from your eportal account after 15 days of date of issue.</p>
<br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
";

if ($BY == "STUDENT" && date_format(date_create($enrolment_card['ISSUE_DATE']),'Y-m-d') == date('Y-m-d')){
send_smtp_email_with_attachment("Your eligibility certificate is successfully generated",$email_body,$EMAIL,$this,$attachments);
}elseif($BY == "ADMIN"){
	send_smtp_email_with_attachment("Enrolment Card, $roll_no",$email_body,$EMAIL,$this,$attachments);
}

$pdf->Output($file_name,'I');
?>
