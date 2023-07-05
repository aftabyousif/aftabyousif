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

$pdf = new PDF('L','mm','A4');

$pdf->AddPage();

$x=7;


myFunction("BANK'S COPY",$x,$pdf,$row,$roll_no);
line($x,$pdf);
$x=75;
myFunction("ACCOUNT'S COPY",$x,$pdf,$row,$roll_no);
line($x,$pdf);
$x=145;
myFunction("ADMISSION'S COPY",$x,$pdf,$row,$roll_no);
line($x,$pdf);
$x=215;
myFunction("STUDENT'S COPY",$x,$pdf,$row,$roll_no);

 $pdf->AddPage('P','A4');
  $pdf->Image('assets/challan_instruction.jpeg',0,0,$pdf->GetPageWidth(),$pdf->GetPageHeight());


function line($x,$pdf){
    $pdf->Line($x+70,5,$x+70,213);
}

$pdf->Output("1.pdf",'I');

function myFunction($copy, $x,$pdf,$record,$roll_no)
{
    // echo "<pre>";
    // print_r($record);
    
    $stdName = $record['CANDIDATE_NAME'];
    $rollNo = $roll_no;
    $fName = $record['CANDIDATE_FNAME'];
    $surName = $record['CANDIDATE_SURNAME'];
    $application_id = $record['APPLICATION_ID'];
    $cnic_no = $record['CNIC_NO'];
    $campus_name = $record['CAMPUS_NAME'];




    $degree_program = $record['DEGREE_PROGRAM'];

    $total_amount = $record['TOTAL_AMOUNT'];

    // $in_words = $record['IN_WORDS'];
    $in_words =  convert_number_to_words($total_amount);
    //$in_words = "ASD";
    $in_words = ucwords(strtoupper($in_words)).' ONLY';

    $category_name = $record['CATEGORY_NAME'];
    $valid_upto = $record['VALID_UPTO'];

    $account_no = $record['ACCOUNT_NO'];
    $candidate_id = $record['CANDIDATE_ID'];
    $challan_no = $record['CHALLAN_NO'];
      $challan_no  =  ADMP_CODE.str_pad($challan_no, 7, "0", STR_PAD_LEFT);
  //  $challan_no = str_pad($challan_no, 5, '0', STR_PAD_LEFT);
    
    $YEAR = $record['YEAR'];
    $current_date = date("d-m-Y");

//    if (date("Y-m-d") >date_format($valid_upto,'Y-m-d'))
//    {
//        exit("Sorry your challan is expired..");
//    }
    $pdf->SetFont('Arial','B',20);

//    $pdf->text(5+$x,15,"HBL");
   $pdf->Image('assets/img/University_of_Sindh_logo.png',5+$x,4,18);
    $pdf->Image('assets/img/hbl_logo.jpg',25+$x,10,18);

    $pdf->SetFont('Arial','B',7);
    $height=25;
    $pdf->text(20+$x,$height,$copy);
    $pdf->Ln();

    $pdf->SetFont('Arial','B',8);

//    $pdf->text($x+5,33,"UNIVERSITY OF SINDH BRANCH, JAMSHORO");
    $height=$height+6;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+7,$height,"Please receive and credit to University of Sindh");
    $height = $height+5;
    $pdf->SetFont('Arial','B',8);
    $pdf->text($x+5,$height,"ADMISSION MISCELLANEOUS ACCOUNT NO.");
    $pdf->SetFont('Arial','B',11);
    $height = $height+5;
    $pdf->text($x+15,$height,"CMD. $account_no");
//   $height = $height+5;
//     $pdf->SetFont('Arial','',8);
//     $pdf->text($x+43,$height,"DATE: $current_date");
  $height = $height+2;
  $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','B',11);
     $pdf->SetXY($x + 7, $height);
    //$pdf->text($x+13,$height,"CHALLAN NO: ");
    $pdf->Cell(30,7,"CHALLAN NO",1,"","C",false);
    $height = $height+ 6;
  $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','B',11);
  //  $pdf->text($x+13,$height,$challan_no);
   //$pdf->SetXY($x + 13, $height);
    $pdf->Cell(30,7,$challan_no,1,"","C",false);
    $pdf->SetFont('Arial','B',9);
    $height=$height+7;
    $pdf->SetTextColor(255,0,0);
    $pdf->text($x+7,$height,"This challan is valid upto: $valid_upto");

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
    $pdf->text($x+5,$height,"CANDIDATE NAME:");
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
    $pdf->text($x+5,$height,"CNIC NO:");
    $pdf->SetFont('Arial','B',9);
    $height =$height+4;
    $pdf->text($x+5,$height,$cnic_no);
    
    // $height =$height+5;
    // $pdf->SetFont('Arial','',8);
    // $pdf->text($x+5,$height,"APPLIED CAMPUS:");
    // $height =$height+4;
    // $pdf->SetFont('Arial','B',9);
    // $pdf->text($x+5,$height,strtoupper($campus_name));

    $height =$height+5;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,$height,"APPLIED CAMPUS:");
    $pdf->SetFont('Arial','B',8);
    $height = $height+1;
    $pdf->SetXY($x + 4, $height);
    $pdf->MultiCell(65,4,"$campus_name",0,"L",false);

    $height =$height+13;
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,$height,"APPLIED FOR:");
    $height =$height+4;
    $pdf->SetFont('Arial','B',9);
    $pdf->text($x+5,$height,strtoupper($degree_program).' DEGREE PROGRAM');


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
    $height=$height+4;
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
    $pdf->MultiCell(40,5,"Admission Registration and Processing Fee",1,"J");
    $pdf->SetXY($x1+40, $y);
    $pdf->Cell(25,10,"Rs. ".number_format($total_amount,2),1,"","R",false);
    $height = $height+15;
    $pdf->SetXY($x + 3, $height);
    $pdf->SetFont('Times','B',9);

//    $pdf->TableCell(65,4,"Amount (in words): $in_words",0,'L',0);

    $pdf->MultiCell(65,4,"Amount (in words): $in_words",0,"L",false);

    $pdf->SetXY($x + 4, 157);
    $pdf->SetFont('ARIAL','',8);

    $pdf->MultiCell(64,4,"                      IMPORTANT NOTE
         This paid amount (Rs: ".number_format($total_amount,2)."/=) is non-transferable and non-refundable. In case any applicant submitted / provided wrong information in admission form (detected at any stage), his/her admission shall be cancelled. The University of Sindh reserves the right to rectify any error / omission detected at any stage.",1,"L",false);

    $data = $candidate_id. "~"  . $application_id. "~". $challan_no . "~".$cnic_no."~" . $total_amount . "~" . $valid_upto . "~" . $account_no . "~" . $current_date;
    //$result=str_pad($data, 10, "0", STR_PAD_LEFT);


    $s="                                                                                ".$data;

    $result=substr($s, strlen($s) - 80, strlen($s));

//    $pdf->text($x+5,190,"MANAGER");
//    $pdf->text($x+52,190,"CASHIER");

    $pdf->setTextColor(0,0,0);

    $pdf->SetFont('Arial','',4);
    $pdf->text($x+5,199,$data);
    $pdf->SetFont('Times','',7);
    $pdf->text($x+5,203,"Powered by: Information Technology Services Centre (ITSC)");

    QRcode::png("$result","../eportal_resource/qr_images/".$challan_no.".png", 'QR_ECLEVEL_L', 3, 2);
    $path="../eportal_resource/qr_images/".$challan_no.".png";
    $pdf->Image($path,44+$x,6,18);
}
?>