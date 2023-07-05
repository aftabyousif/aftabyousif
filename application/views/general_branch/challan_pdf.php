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

	function Header(){
		$this->SetFont('Arial','B',15);
	}

	function Footer(){
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		// $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$pdf = new PDF('L','mm','A4');

$pdf->AddPage();

$x=7;


myFunction("BANK'S COPY",$x,$pdf,$challan);
line($x,$pdf);
$x=75;
myFunction("ACCOUNT'S COPY",$x,$pdf,$challan);
line($x,$pdf);
$x=145;
myFunction("GENERAL BRANCH'S COPY",$x,$pdf,$challan);
line($x,$pdf);
$x=215;
myFunction("STUDENT'S COPY",$x,$pdf,$challan);



function line($x,$pdf){
	$pdf->Line($x+70,5,$x+70,213);
}

$pdf->Output($challan['CHALLAN_NO'].".pdf",'I');

function myFunction($copy, $x,$pdf,$challan){

	$stdName = $challan['FIRST_NAME'];
	$fName = $challan['FNAME'];
	$surName = $challan['LAST_NAME'];
	$application_id = $challan['APPLICATION_ID'];
	$cnic_no = $challan['CNIC_NO'];
	$roll_no = $challan['ROLL_NO'];
	$shift_name = $challan['SHIFT_NAME'];
	$campus_name = $challan['CAMPUS_NAME'];

	$degree_program = $challan['PROGRAM_TITLE'];

	$total_amount = $challan['CHALLAN_AMOUNT'];
	$category_name = '';
	$label = '';

	if ($challan['TYPE_CODE'] == "53-001"){
		$category_name='ENROLMENT CHALLAN';
		$label = 'ENROLMENT CARD FEE';
	}elseif ($challan['TYPE_CODE'] == "53-002"){
		$category_name='ELIGIBILITY CHALLAN';
		$label = 'ELIGIBILITY CERTIFICATE FEE';
	}

	$in_words =  convert_number_to_words($total_amount);

	$in_words = ucwords(strtoupper($in_words)).' ONLY';


	$valid_upto = $challan['DUE_DATE'];

	$account_no = '00427991823103';
	$challan_no = $challan['CHALLAN_NO'];

	$current_date = date("d-m-Y");

//	$valid_upto = date_create($valid_upto);
//	$valid_upto = date_format($valid_upto,'d-m-Y');
	$pdf->SetFont('Arial','B',20);
// prePrint($challan);
	// $pdf->text(5+$x,15,"HBL");
	$pdf->Image('./assets/img/University_of_Sindh_logo.png',5+$x,4,18);
	$pdf->Image('./assets/img/hbl_logo.jpg',25+$x,10,18);

	$pdf->SetFont('Arial','B',7);
	$height=26;
	$pdf->text(10+$x,$height,$copy);
	$pdf->Ln();

	$pdf->SetFont('Arial','B',8);

//    $pdf->text($x+5,33,"UNIVERSITY OF SINDH BRANCH, JAMSHORO");
	$height=$height+6;
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+7,$height,"Please receive and credit to University of Sindh");
	$height = $height+5;
	$pdf->SetFont('Arial','B',8);
	$pdf->text($x+12,$height,"GENERAL BRANCH ACCOUNT NO.");
	$pdf->SetFont('Arial','B',11);
	$height = $height+5;
	$pdf->text($x+15,$height,"CMD. $account_no");
	$height = $height+5;
	$pdf->SetFont('Arial','B',8);
	$pdf->text($x+43,$height,"DATE: $current_date");
	$height = $height+5;
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->text($x+7,$height,"CHALLAN NO: ".$challan_no);
	$pdf->SetFont('Arial','B',10);
	$height=$height+7;
	$pdf->SetTextColor(255,0,0);
	$pdf->text($x+5,$height,"This challan is valid upto: $valid_upto");

	$pdf->SetFont('Arial','B',11);
	$height =$height+ 3;
	$pdf->SetXY($x + 5, $height);
	$pdf->SetTextColor(255,255,255);
	$pdf->MultiCell(60, 5, $category_name, 1, 'C', true);
	$pdf->SetTextColor(0,0,0);

//    $pdf->text($x+19,49,$category_name);

	$pdf->setTextColor(60,60,60);
	// $pdf->setTextColor(0,0,0);

//    $pdf->SetFont('Arial','B',10);
//    $pdf->text($x+10,85,"CANDIDATE INFORMATION");

//    $pdf->SetFont('Arial','',8);
//    $pdf->text($x+5,54,"ROLL NO:");
//    $pdf->SetFont('Arial','B',9);
//    $pdf->text($x+22,54,strtoupper($rollNo));
//
//    $pdf->SetFont('Arial','',8);
//    $pdf->text($x+5,58,"SEAT NO:");
//    $pdf->SetFont('Arial','B',9);
//    $pdf->text($x+22,58,$seat_no);
	$height =$height+ 10;
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+5,$height,"STUDENT NAME:");
	$pdf->SetFont('Arial','B',9);
	$height =$height+4;
	$pdf->text($x+5,$height,strtoupper($stdName));

	$pdf->SetFont('Arial','',8);
	$height =$height+5;
	$pdf->text($x+5,$height,"FATHER'S NAME:");
	$pdf->SetFont('Arial','B',9);
	$height =$height+4;
	$pdf->text($x+5,$height,strtoupper($fName));
	$height =$height+5;
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+5,$height,"SURNAME:");
	$pdf->SetFont('Arial','B',9);
	$height =$height+4;
	$pdf->text($x+5,$height,strtoupper($surName));

	$height =$height+5;
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+5,$height,"ROll NO:");
	$pdf->SetFont('Arial','B',9);
	$height =$height+0;
	$pdf->text($x+20,$height,$roll_no);

	$height =$height+5;
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+5,$height,"APP NO:");
	$pdf->SetFont('Arial','B',9);
	$height =$height+0;
	$pdf->text($x+20,$height,$application_id);

//	$height =$height+0;
//	$pdf->SetFont('Arial','',8);
//	$pdf->text($x+35,$height,"SELECTION LIST #:");
//	$pdf->SetFont('Arial','B',9);
//	$height =$height+0;
//	$pdf->text($x+62,$height,$list_no);

	$height =$height+5;
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+5,$height,"CAMPUS:");
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x + 4, 110);
	$pdf->MultiCell(65,4,"$campus_name",0,"L",false);

	$height =$height+11;
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+5,$height,"PROGRAM TITLE:");

	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x + 4, 120);
	$pdf->MultiCell(65,4,strtoupper($degree_program).' - ('.$shift_name.')',0,"L",false);
	/*
	$height =$height+4;
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+5,$height,strtoupper($degree_program).' DEGREE PROGRAM');
*/

//    $pdf->SetFont('Arial','',8);
//    $pdf->text($x+5,86,"CLASS:");
//    $pdf->SetFont('Arial','B',9);
//    $pdf->text($x+18,86,strtoupper($class));
//
//    $pdf->SetFont('Arial','',8);
//    $pdf->text($x+5,90,"PROGRAM:");
//    $pdf->SetFont('Arial','B',8);
//    $pdf->SetXY($x + 4, 91);
//    $pdf->MultiCell(65,4,"$Program",0,"L",false);
//


	$pdf->setTextColor(0,0,0);

	$pdf->ln(0);
	$height=$height+10;
	$pdf->SetXY($x + 3, $height);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell(40,6,"Purpose of Payment",1,"","C",false);
	$pdf->Cell(25,6,"Amount (Rs.)",1,"","C",false);
//    $pdf->ln();
//    $pdf->SetXY($x + 3, 121);
//    $pdf->SetFont('Times','B',10);
//    $pdf->Cell(40,6,"DUES",1,"","R",false);
//    $pdf->Cell(25,6,"Rs. ".number_format($due,2),1,"","R",false);
	$height = $height+6;
	$pdf->SetXY($x + 3,$height );
	$pdf->SetFont('Times','B',10);
	$x1 = $pdf->getX();
	$y = $pdf->getY();
	$pdf->MultiCell(40,5,$label,1,"J");
	$pdf->SetXY($x1+40, $y);
	$pdf->Cell(25,10,"Rs. ".number_format($total_amount,2),1,"","R",false);
	$height = $height+12;
	$pdf->SetXY($x + 3, $height);
	$pdf->SetFont('Times','B',9);

//    $pdf->TableCell(65,4,"Amount (in words): $in_words",0,'L',0);

	$pdf->MultiCell(68,4,"Amount (in words): $in_words",0,"L",false);

	$pdf->SetXY($x + 3, 160);
	$pdf->SetFont('ARIAL','',8);

	$pdf->MultiCell(64,3,"                      IMPORTANT NOTE
	
         This paid amount (Rs: $total_amount/=) is
non-transferable. In case any applicant submitted
/ provided wrong information in Admission's Form
(detected at any stage), his/her Admission shall
be cancelled. The University of Sindh reserves
the right to rectify any error / omission detected at
any stage.
",1,"L",false);

	$data = $application_id. "~". $challan_no . "~".$cnic_no."~" . $total_amount . "~" . $valid_upto . "~" . $account_no . "~" . $current_date;

	QRcode::png($data,"../eportal_resource/general_branch_challan_qrcode/".$challan_no.".png", 'QR_ECLEVEL_L', 3, 2);
	//	$s="                                                                                ".$data;

//	$result=substr($s, strlen($s) - 80, strlen($s));
//	prePrint($data);
//	exit();
	$pdf->setTextColor(0,0,0);

// 	$pdf->SetFont('Arial','',4);
// 	$pdf->text($x+5,199,$data);
	$pdf->SetFont('Times','',7);
	$pdf->text($x+5,200,"Powered by: Information Technology Services Centre (ITSC)");



	$path="../eportal_resource/general_branch_challan_qrcode/".$challan_no.".png";
	$pdf->Image($path,44+$x,6,18);
}
?>
