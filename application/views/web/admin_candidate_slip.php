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
$list_of_abest_can  = array(80);


$first_column_width = 60;
$sec_column_width = 130;
$row_height = 5;
$pdf = new PDF('P','mm','A4');

//   [CARD_ID] => 2
//             [SESSION_ID] => 9
//             [APPLICATION_ID] => 151761
//             [IS_DISPATCHED] => Y
//             [REMARKS] => DAY 1
//             [ADMISSION_SESSION_ID] => 0
//             [TEST_DATETIME] => 2022-10-30 09:00
//             [PROGRAM_TYPE_ID] => 1
//             [CNIC_NO] => 4530385016932
//             [FIRST_NAME] => AALIYA AFZAL
//             [LAST_NAME] => BURIRO
//             [FNAME] => MUHAMMAD AFZAL
//             [PROFILE_IMAGE] => profile_image_269980.jpg
//             [BLOCK_NAME] => AUDITORIUM (RIGHT SIDE)
//             [LOCATION] => GROUND FLOOR
//             [BUILDING_NAME] => INSTITUTE OF MICROBIOLOGY
//             [BLOCK_NO] => 1
//             [PROFILE_PICTURE] => 

foreach($list_of_candidate as $application){
     $pdf->AddPage();
     $pdf->Image("assets/img/slip_bg.png",0,0,$pdf->GetPageWidth(),$pdf->GetPageHeight());

//$test_date_time = getCustomDate($admit_card['TEST_DATETIME'],'Y');
//prePrint($admit_card);
//exit();
// if(in_array($card_id, $list_of_abest_can)){
//   $block ="-";
//   $test_venue="INSTITUTE OF ART AND DESIGN";
//   $test_date_time = "Sunday, 07-11-2021 09:00 AM";
// }
$date = DateTime::createFromFormat('Y-m-d h:i', $application['TEST_DATETIME']);
//prePrint($date);
$test_date_time =$date->format('l, d-m-Y h:i A');
$test_time =$date->format('h:i A');
$test_venue = $application['BUILDING_NAME']." (".$application['LOCATION'].")";
$block = $application['BLOCK_NO']." - ".$application['BLOCK_NAME'];
$session_id = $application['SESSION_ID'];
$card_id = $application['CARD_ID'];
//$test_type = "PRE-ENTRY TEST FOR ADMISSIONS TO LL.M (EVENING) 2023;
$test_type = "PRE-ENTRY TEST FOR ADMISSIONS TO BACHELOR DEGREE PROGRAMS ".$application['YEAR'];

 
$remarks  = "";
if($application['IS_PROFILE_PHOTO_VERIFIED']==2){
    // echo "<center><h1 style='color:red;'> Your Pre-Entry Test Admit Card is blocked</h1>";
    // echo "<h1>Your profile photo has been rejected due to inappropriate photo, kindly re-upload your profile photo otherwise your admit card will not be issued.</h1>";
    // echo "<h1>or send your profile photo on <span style='color:blue;'>admission@usindh.edu.pk</span> along with your <span style='color:blue;'>CNIC No</span>.</h1></center>";  
    // exit();
    $remarks = "Your profile photo has been rejected due to inappropriate photo";
}else if(!$application['IS_PROFILE_PHOTO_VERIFIED']){
    $remarks = "Profile photo is not verified yet";
    // echo "<center><h1>Your profile photo is not verfied yet, please email your query at <span style='color:blue;'>admission@usindh.edu.pk</span>  with your  <span style='color:blue;'>CNIC No</span>.</h1></center>";  
    // exit();
}
else if(!$application['CHALLAN_IMAGE']){
    $remarks = "You have not uploaded the paid copy of challan of Rs.2500/=.";
    // echo "<center><h1 style='color:red;'> Your Pre-Entry Test Admit Card is blocked</h1>";
    // echo "<h1>You have not uploaded the paid copy of challan of Rs.2500/=. Kindly upload your paid copy of challan otherwise your admit card will not be issued.</h1></center>";  
    // exit();
}
$APPLICATION_ID = $application['APPLICATION_ID'];

$user_id=$application['USER_ID'];
$name = strtoupper($application['FIRST_NAME']);
$fname = strtoupper($application['FNAME']);
$last_name =strtoupper( $application['LAST_NAME']);
$GENDER = strtoupper($application['GENDER'])=='M'?"MALE":"FEMALE";
//$MOBILE_NO = ($user_fulldata['users_reg']['MOBILE_CODE']=="0092"?"0":$user_fulldata['users_reg']['MOBILE_CODE'])."".$user_fulldata['users_reg']['MOBILE_NO'];
$CNIC_NO =strtoupper( $application['CNIC_NO']);

$current_date = date("d-m-Y");
//$card_id = str_pad($card_id,6,"0",STR_PAD_LEFT);
$data = $application['USER_ID']. "~"  . $application['APPLICATION_ID']. "~" . $CNIC_NO. "~".$card_id."~" . $current_date."~".$session_id;
//$result=str_pad($data, 10, "0", STR_PAD_LEFT);

$profile_image = PROFILE_IMAGE_CHECK_PATH.$application['PROFILE_IMAGE'];
// prePrint($profile_image);
// exit();
if(!is_dir('../eportal_resource/qr_images')){
    mkdir('../eportal_resource/qr_images');
}
QRcode::png("$data","../eportal_resource/qr_images/".$application['APPLICATION_ID'].".png", 'QR_ECLEVEL_L', 3, 2);
$path="../eportal_resource/qr_images/".$application['APPLICATION_ID'].".png";
$pdf->Image($path,175,8,16,16);
$pdf->Image('assets/img/uos_logo.png',10,7,26,26);
try{
$pdf->Image($profile_image,167,70,35,48);
}catch(Exception $ex){
    $msg = "<br>name : $name <br>profile_image : $profile_image<br>user_id : $user_id<br>cnic : $CNIC_NO <br>application id : $APPLICATION_ID ";
   
    if($application['PROFILE_IMAGE']){
         emailDeveloperLog("ADMIT CARD INCORRECT IMAGE $CNIC_NO",$msg);
         echo $msg;
    echo "<h1>Your profile image invalid. Download your Admit Card after one hour.</h1>";    
    }else{
        emailDeveloperLog("ADMIT CARD  IMAGE NOT UPLOAD $CNIC_NO",$msg);
        echo $msg;
    echo "<h1>Your profile image is not uploaded please send your profile image on admission@usindh.edu.pk along with your cnic no.</h1>";  
    }
    
    exit();
}
// print_r($application);
// exit();
if($application['PROGRAM_TYPE_ID']==2){
     $block ="-";
     $test_venue="INSTITUTE OF MATHEMATICS AND COMPUTER SCIENCE";
     $test_type = "PRE-ENTRY TEST FOR ADMISSIONS TO LL.M (EVENING) ".$application['YEAR'];
    // $test_date_time = "Sunday, 07-11-2021 09:00 AM"; 
}
$pdf->Ln(20);
$pdf->SetFont("Arial",'B',20);
$pdf->Cell(0,$row_height,"UNIVERSITY OF SINDH, JAMSHORO ",0,1,'C',false);
$pdf->Ln(4);
$pdf->SetFont("Arial",'B',12);
$pdf->Cell(0,$row_height,"ADMIT CARD",0,1,'C',false);
$pdf->Ln(4);
$pdf->Cell(0,$row_height,$test_type,0,1,'C',false);
//$pdf->Ln(3);
//$pdf->SetFont("Arial",'B',14);
//$pdf->Cell(0,$row_height,$test_date_time,0,1,'C',false);

$pdf->Ln(8);

$x = $pdf->getX();
$y = $pdf->getY();

$p_y = $pdf->getY();
$pdf->setXY($x+165,$y);
$pdf->SetFont("Arial",'B',10);
$pdf->Cell(40,$row_height,"ID : ".$APPLICATION_ID ,0,1);
$pdf->setY($p_y);
$pdf->Ln(3);
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"SEAT NO : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',14);
$pdf->Cell(110,$row_height,$card_id,0,0);

$pdf->Ln(7);
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"NAME : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',11);
$pdf->MultiCell(110,$row_height,$name,0,1);
$pdf->Ln(3);
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"FATHER'S NAME : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',11);
$pdf->MultiCell(110,$row_height,$fname,0,1);
$pdf->Ln(3);
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"SURNAME : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',11);
$pdf->MultiCell(110,$row_height,$last_name,0,1);
$pdf->Ln(3);
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"CNIC NO : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',11);
$pdf->MultiCell(110,$row_height,$CNIC_NO,0,1);
$pdf->Ln(8);
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"TEST DATE : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',14);
$pdf->MultiCell(110,$row_height,$test_date_time,0,1);
$pdf->Ln(3);
//$pdf->Rect($pdf->getX()-2,$pdf->getY()-2,150,32);
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"TEST VENUE : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',11);
$pdf->MultiCell(110,$row_height,$test_venue,0,1);
$pdf->Ln(3);
if($application['PROGRAM_TYPE_ID']==1){
$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"BLOCK : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',11);
$pdf->MultiCell(110,$row_height,$block,0,1);
}
if($remarks){
    $pdf->SetTextColor(255,0,0);
 $pdf->SetFont("Arial",'',9);
$pdf->Cell(45,$row_height,"Remarks : ",0,0,'L',false);
$pdf->SetFont("Arial",'B',9);
$pdf->MultiCell(130,$row_height,$remarks,0,1);   

    $pdf->Ln(15);
}else{
    $pdf->Ln(25);
}
$pdf->SetTextColor(0,0,0);

$pdf->SetFont("Arial",'',11);
$pdf->Cell(45,$row_height,"SIGNATURE OF CANDIDATE  ",0,1,'L',false);
$pdf->SetX(0);
$pdf->MultiCell(250,0.5,"",1,1,1);
$pdf->SetFont("Arial",'B',11);
$pdf->Ln(5);
$pdf->MultiCell(140,$row_height,"IMPORTANT INFORMATION / INSTRUCTIONS FOR THE DAY OF TEST:",0,1);
$pdf->MultiCell(130,0.2,"",1,1,1);
$pdf->SetFont("Arial",'B',10);
$pdf->Ln(2);
$pdf->Cell(5,$row_height,"I.");
$pdf->MultiCell(0,$row_height-1,"It is mandatory to bring coloured printout of this Admit Card along with your Original CNIC/ B-FORM with you for appearing in the Pre-Entry Test.",0,1);
$pdf->SetFont("Arial",'B',10);
$pdf->Ln(2);
$pdf->SetFont("Arial",'',10);
$pdf->Cell(5,$row_height,"II.");

$pdf->MultiCell(0,$row_height-1,"Please note that wearing a face mask is mandatory to enter in the Examination Centre. Face mask has to be worn at all times inside the Examination Centre.",0,1);
$pdf->Ln(2);
$pdf->Cell(5,$row_height,"III.");
$pdf->SetFont("Arial",'',10);
$pdf->MultiCell(0,$row_height-1,"Please note down your Seat Number carefully, your Pre-Entry Test result will be announced through your Seat Number.",0,1);

$pdf->Ln(2);
$pdf->Cell(5,$row_height,"IV.");
$pdf->SetFont("Arial",'',10);
$pdf->MultiCell(0,$row_height-1,"You are required to be present in your respective block up to $test_time.",0,1);

$pdf->Ln(2);
$pdf->Cell(5,$row_height,"V.");
$pdf->SetFont("Arial",'',10);
$pdf->MultiCell(0,$row_height-1,"Please bring black ball point pen with you.",0,1);
$pdf->Ln(2);
$pdf->Cell(5,$row_height,"VI.");
$pdf->SetFont("Arial",'',10);
$pdf->MultiCell(0,$row_height-1,"Candidate is required not to bring items such as Mobile Phone, electronic device, calculators, personal computing device, smart watch, camera, ear plugs or other devices that can be used to send, receive or record information, Books, reference notes etc or any paper are also not allowed.",0,1);

$pdf->Ln(2);
$pdf->Cell(5,$row_height,"VII.");
$pdf->SetFont("Arial",'',10);
$pdf->MultiCell(0,$row_height-1,"This Pre-Entry Test Admit Card is being issued provisionally subject to the verification of documents.",0,1);
$pdf->SetFont("Arial",'B',11);
$pdf->Ln(8);
$pdf->MultiCell(0,$row_height,"MOBILE PHONE AND OTHER COMPUTING DEVICES ARE",0,'C');
$pdf->MultiCell(0,$row_height,"STRICTLY PROHIBITED AT THE PRE-ENTRY TEST CENTRE.",0,'C');

$pdf->Ln(4);
$pdf->MultiCell(0,$row_height,"PLEASE FOLLOW THE COVID-19 SOPs STRICTLY.",0,'C');
if($application['ADMISSION_SESSION_ID'] != 15 ){
    // 
    $pdf->Ln(3);
$pdf->MultiCell(0,$row_height,"Please note that Pre-Entry Test will be conducted at Allama I.I Kazi Campus (Main Campus),",0,'C');
$pdf->MultiCell(0,$row_height,"University of Sindh, Jamshoro.",0,'C');
}
$pdf->SetFont('Times','',10);

$pdf->text(10,285,"Powered by: Information Technology Services Centre (ITSC)");
$pdf->SetFont('Times','',7);
$pdf->text(145,285,$data);


}



$pdf->Output("candiadte_slip_{$card_id}.pdf",'I');

//Base64url_encode(base64_encode(urlencode(json_encode($data))))