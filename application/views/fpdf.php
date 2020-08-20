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
$roll_no="asd";
$row = array('CHALLAN_NO'=>"1123",
    "FIRST_NAME"=>"FIRST_NAME",
    "CANDIDATE_SURNAME"=>"CANDIDATE_SURNAME",
    "CANDIDATE_FNAME"=>"CANDIDATE_FNAME",
    "CANDIDATE_NAME"=>"CANDIDATE_NAME",
    "FEE_AMOUNT"=>"10000",
    "FEE_LABLE"=>"FORM FEES",
    "DUES"=>"10000",
    "TOTAL_AMOUNT"=>"10000",
    "CATEGORY_NAME"=>"ADMISSION FORM",
    "VALID_UPTO"=>"12-12-2020",
    "ACCOUNT_NO"=>"123123",
    "CANDIDATE_ID"=>"1",
    );

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



function line($x,$pdf){
    $pdf->Line($x+70,5,$x+70,213);
}

$pdf->Output("1.pdf",'I');

function myFunction($copy, $x,$pdf,$record,$roll_no)
{
    $stdName = $record['CANDIDATE_NAME'];
    $rollNo = $roll_no;
    $fName = $record['CANDIDATE_FNAME'];
    $surName = $record['CANDIDATE_SURNAME'];



    $fee_label = $record['FEE_LABLE'];
    $fee_amount = $record['FEE_AMOUNT'];

    $due = $record['DUES'];
    $total_amount = $record['TOTAL_AMOUNT'];

    // $in_words = $record['IN_WORDS'];

    $in_words = "ASD";
    $in_words = ucwords(strtoupper($in_words)).' ONLY';

    $category_name = $record['CATEGORY_NAME'];
    $valid_upto = $record['VALID_UPTO'];

    $account_no = $record['ACCOUNT_NO'];
    $candidate_id = $record['CANDIDATE_ID'];
    $challan_no = $record['CHALLAN_NO'];
    $current_date = date("d-m-Y");

//    if (date("Y-m-d") >date_format($valid_upto,'Y-m-d'))
//    {
//        exit("Sorry your challan is expired..");
//    }
    $pdf->SetFont('Arial','B',20);

//    $pdf->text(5+$x,15,"HBL");
   $pdf->Image(base_url().'assets/img/University_of_Sindh_logo.png',5+$x,4,18);
    $pdf->Image(base_url().'assets/img/hbl_logo.jpg',25+$x,10,18);

    $pdf->SetFont('Arial','B',7);
    $pdf->text(20+$x,23,$copy);
    $pdf->Ln();

    $pdf->SetFont('Arial','B',8);

//    $pdf->text($x+5,33,"UNIVERSITY OF SINDH BRANCH, JAMSHORO");
    $pdf->SetFont('Arial','',7.5);
    $pdf->text($x+9,28,"Please receive and credit to University of Sindh");

    $pdf->SetFont('Arial','B',7.5);
    $pdf->text($x+15,32,"ADMISSION ACCOUNT NUMBER");
    $pdf->SetFont('Arial','B',11);
    $pdf->text($x+15,36,"CMD. $account_no");

    $pdf->SetFont('Arial','B',9);
    $pdf->text($x+4,40,"CHALLAN NO: ".$challan_no);
    $pdf->SetFont('Arial','',8);
    $pdf->text($x+43,40,"DATE: $current_date");

    $pdf->SetFont('Arial','B',9);
    $pdf->text($x+7,44,"This challan is valid upto: $valid_upto");

    $pdf->SetFont('Arial','B',10);

    $pdf->SetXY($x + 5, 45);
    $pdf->SetTextColor(255,255,255);
    $pdf->MultiCell(60, 5, $category_name, 1, 'C', true);
    $pdf->SetTextColor(0,0,0);

//    $pdf->text($x+19,49,$category_name);

    $pdf->setTextColor(110,110,110);
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

    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,62,"STUDENT'S NAME:");
    $pdf->SetFont('Arial','B',9);
    $pdf->text($x+5,66,strtoupper($stdName));

    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,70,"FATHER'S NAME:");
    $pdf->SetFont('Arial','B',9);
    $pdf->text($x+5,74,strtoupper($fName));

    $pdf->SetFont('Arial','',8);
    $pdf->text($x+5,78,"SURNAME:");
    $pdf->SetFont('Arial','B',9);
    $pdf->text($x+5,82,strtoupper($surName));


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
//    $pdf->SetFont('Arial','',8);
//    $pdf->text($x+5,102,"CAMPUS:");
//    $pdf->SetFont('Arial','B',8);
//    $pdf->SetXY($x + 4, 103);
//    $pdf->MultiCell(65,4,"$campus_name",0,"L",false);

    $pdf->setTextColor(0,0,0);

    $pdf->ln(0);
    $pdf->SetXY($x + 3, 115);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(40,6,"$fee_label",1,"","R",false);
    $pdf->Cell(25,6,"Rs. ".number_format($fee_amount,2),1,"","R",false);
//    $pdf->ln();
    $pdf->SetXY($x + 3, 121);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(40,6,"DUES",1,"","R",false);
    $pdf->Cell(25,6,"Rs. ".number_format($due,2),1,"","R",false);

    $pdf->SetXY($x + 3, 127);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(40,6,"TOTAL FEE",1,"","R",false);
    $pdf->Cell(25,6,"Rs. ".number_format($total_amount,2),1,"","R",false);

    $pdf->SetXY($x + 3, 134);
    $pdf->SetFont('Times','B',9);

//    $pdf->TableCell(65,4,"Amount (in words): $in_words",0,'L',0);

    $pdf->MultiCell(65,4,"Amount (in words): $in_words",0,"L",false);

    $pdf->SetXY($x + 4, 145.8);
    $pdf->SetFont('ARIAL','',7);

    $pdf->MultiCell(64,4,"                               IMPORTANT NOTE:
         The criteria for promotion to next higher classes shall be according to the rules and regulations of the University.The provisional admission to next higher class is allowed on the basis of data provided / submitted by the candidate him/herself. In case any applicant submitted / provided wrong information in admission form (detected at any stage), his/her admission shall be cancelled.The University of Sindh reserves the right to rectify any error / omission detected at any stage.",1,"L",false);

    $data = strtoupper($rollNo). "~" . $challan_no . "~" . $candidate_id. "~" . $total_amount . "~" . $valid_upto . "~" . $account_no . "~" . $current_date;
    //$result=str_pad($data, 10, "0", STR_PAD_LEFT);


    $s="                                                                                ".$data;

    $result=substr($s, strlen($s) - 80, strlen($s));

//    $pdf->text($x+5,190,"MANAGER");
//    $pdf->text($x+52,190,"CASHIER");

    $pdf->setTextColor(0,0,0);

    $pdf->SetFont('Arial','',4.5);
    $pdf->text($x+5,199,$data);
    $pdf->SetFont('Times','',7);
    $pdf->text($x+5,203,"Powered by: Information Technology Services Centre (ITSC)");

    QRcode::png("$result","../eportal_resource/qr_images/".$challan_no.".png", 'QR_ECLEVEL_L', 3, 2);
    $path="../eportal_resource/qr_images/".$challan_no.".png";
    $pdf->Image($path,44+$x,6,18);
}
?>