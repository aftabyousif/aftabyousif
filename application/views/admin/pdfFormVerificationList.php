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

prePrint($applicant_data);

$pdf = new PDF('P','mm','A4');

$pdf->AddPage();
$first_column_width = 60;
$sec_column_width = 130;
$row_height = 5;
$app_camp="ALLAMA I.I. KAZI CAMPUS JAMSHORO";
$app_cat="";
$sp_ch="";

$pdf->Image(base_url().'assets/img/University_of_Sindh_logo.png',10,4,26,26);
$pdf->SetFont("Arial",'B',20);
$pdf->Cell(0,$row_height,"University of Sindh, Jamshoro ",0,1,'C',false);
$pdf->Ln(1);
$pdf->SetFont("Times",'',12);
$pdf->Cell(0,$row_height,"ONLINE ADMISSION FORM VERIFICATION LIST",0,1,'C',false);
$pdf->SetFont("Times",'',13);


$pdf->SetFont("Times",'B',12);

$pdf->TableCell($sec_column_width-25,$row_height,$application['NAME'],1,'L',1);

$pdf->SetFont("Times",'B',11);
$pdf->TableCell($sec_column_width-25,$row_height,$app_cat,1,'L',1);

$pdf->Ln(5);
$pdf->SetFont("Times",'B',11);
$pdf->customCell($first_column_width,$row_height,"Personal Information",0,'L',1);

$pdf->SetFont("Times",'',10);

for($i=$count_qual ; $i>=0;$i--){

$qualification = $qualifications[$i];
    // if($qualification['DEGREE_ID']==10){
    //     $check = 1;
    //     continue;
       
        
    // }
    // if($application['PROGRAM_TYPE_ID']==2){
    //     if($i>$check&&$qualification['DEGREE_ID']>3){
    //         continue;
    //     }
    // }
    if(in_array($qualification['DEGREE_ID'], $list_degree)){
        
    
    $pdf->TableCell($first_column_width,$row_height-1,$qualification['DEGREE_TITLE'],1,'L',0);
    $pdf->TableCell(30,$row_height-1,$qualification['DISCIPLINE_NAME'],1,'L',0);
    $pdf->TableCell(18,$row_height-1,$qualification['OBTAINED_MARKS'],1,'L',0);
    $pdf->TableCell(15,$row_height-1,$qualification['TOTAL_MARKS'],1,'L',0);
    $pdf->TableCell(15,$row_height-1,$qualification['PASSING_YEAR'],1,'L',0);
    $pdf->TableCell(17,$row_height-1,$qualification['ROLL_NO'],1,'L',0);
    $pdf->TableCell(35,$row_height-1,$qualification['ORGANIZATION'],1,'L',1);
    }
}

$pdf->SetFont('Times','',10);
$pdf->text(160,280,"Signature of Checker");

$pdf->text(10,285,"Powered by: Information Technology Services Centre (ITSC)");
$pdf->SetFont('Times','',7);

$new_datetime = date("d-m-y h:i:s A");
$pdf->Output("application_form_verification_list_$new_datetime.pdf",'I');
?>