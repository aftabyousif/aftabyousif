<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 9/16/2020
 * Time: 10:28 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once  APPPATH.'controllers/AdminLogin.php';
class StudentIDCard extends AdminLogin {
    private $script_name = "";
    public function __construct() {
        parent::__construct();

        $this->load->model('Administration');
        $this->load->model('log_model');
        $this->load->model('Api_qualification_model');
        $this->load->model('Api_location_model');
        $this->load->model('User_model');
        $this->load->model('Application_model');
        $this->load->model('Admission_session_model');
        $this->load->model('TestResult_model');
        $this->load->model('AdmitCard_model');
        $this->load->model('FeeChallan_model');
        $this->load->model('Prerequisite_model');
        $this->load->model('StudentReports_model');
        $this->load->library('Tcpdf_master');
		$this->load->helper('url');
        $this->legacy_db = $this->load->database('admission_db',true);
        $this->online_db = $this->load->database('admission_online',true);
		$this->db = $this->load->database('admission_v2',true);
		//		$this->load->library('javascript');
        $self = $_SERVER['PHP_SELF'];
        $self = explode('index.php/',$self);
        $this->script_name = $self[1];
        $this->verify_login();
    }
    public function testpdf(){
         if(isset($_GET['s_id'])&&isset($_GET['pt_id'])
	     &&isset($_GET['sh_id'])&&isset($_GET['c_id'])
	     &&isset($_GET['p_id'])&&isset($_GET['pl_id'])){
            
            $session_id = isValidData($_GET['s_id']);
            $shift_id = isValidData($_GET['sh_id']);
            $prog_type_id = isValidData($_GET['pt_id']);
            $campus_id = isValidData($_GET['c_id']);
            $part_id = isValidData($_GET['p_id']);
            $prog_list_id = json_decode(urldecode($_GET['pl_id']));
            $prog_list_id_str = join($prog_list_id,',');
            prePrint($prog_list_id_str);

	     }else{
	         exit("<h1>Please Must Select All parameters</h1>");
	     }
        $response = $this->StudentReports_model->getStudentByProgram($campus_id,$prog_type_id,$session_id,$shift_id,$prog_list_id_str,$part_id);
        foreach ($response as $key=>$candidate) {				
			$image_path  =$value['PROFILE_IMAGE'];
 			//echo "<img src='$image_path'>";
 			
 			//$image_headers = get_headers($image_path);
 			//if($image_headers[0] == 'HTTP/1.1 200 OK') {
 				$data[] = $value;
 			//}			
		}

	    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor(PDF_AUTHOR);
			$pdf->SetTitle('ID Card Report '.$data[0]['PROGRAM_TITLE'].' '.$data[0]['PART_NAME']);
			$pdf->SetSubject('');
			$pdf->SetKeywords('');
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetAutoPageBreak(FALSE);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$imageF = K_PATH_IMAGES.'id_card_front.jpg';
			$imageB = K_PATH_IMAGES.'id_card_back.jpg';
			$sign = K_PATH_IMAGES.'DA_sign.png';
			$logoT = K_PATH_IMAGES.'logo_t.jpg';
			$pdf->AddPage();
			$x = 18.65;
			$y = 9;
			$border = 1;
			$count = count($data);
			 
			for ($i = 0; $i < $count; $i++) {
			    
			    $image_path = K_PATH_PROFLE_IMAGES.$data[$i]['PROFILE_IMAGE'];;
			    
			   	$pdf->Image($imageF, $x, $y, 85.6, 0, '','',true);
				$pdf->Image($imageB, $x+86.2, $y, 85.6, 0, '','',true);
				$pdf->Image($image_path, $x+3, $y+16.5, 20, 26.1, '','',true);
				$pdf->Image($sign, $x+61, $y+32.5, 22, 8, '','',true);
				$pdf->Image($logoT, $x+86.2, $y+16.7, 85.6, 25.6, $type = 'JPG', $link = '', $align = 'C', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, 0, $fitbox = 'CM', $hidden = false, $fitonpage = false, $alt = false, $altimgs = array());
			
			    $y = $y + 55.8;
				if(($i+1)%5==0){
					$pdf->AddPage();
					$x = 18.65;
					$y = 9;
				}				
			}
			
			$pdf->lastPage();
			ob_end_clean();
			$pdf->Output('Test.pdf', 'I');
			exit;
	}
    public function idcardreport(){
		$candidate_id = $this->input->post('candidate_id');
		if ($candidate_id != "") {
			$response = $this->studentmodel->getIDCardCandidateData($candidate_id);
			foreach ($response as $key=>$value) {				
				$image_path = $this->studentmodel->getImagePath($value['candidate_id']);
				$image_headers = @get_headers($image_path);	
				if($image_headers[0] == 'HTTP/1.1 200 OK') {
					$data[] = $value;
				}			
			}			
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor(PDF_AUTHOR);
			$pdf->SetTitle('ID Card Report '.$candidate_id);
			$pdf->SetSubject('');
			$pdf->SetKeywords('');
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetAutoPageBreak(FALSE);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}		
			$imageF = K_PATH_IMAGES.'id_card_front.jpg';
			$imageB = K_PATH_IMAGES.'id_card_back.jpg';
			$sign = K_PATH_IMAGES.'DA_sign.png';
			$logoT = K_PATH_IMAGES.'logo_t.jpg';
			$pdf->AddPage();
			$border = 0;
			$x = 18.65;
			$y = 9;
			$count = count($data);
			$numPages = round(count($data)/6);
			for ($i = 0; $i < $count; $i++) {
				$category = '';
				$program_name = $data[$i]['program_name'];
				$campus_name = $data[$i]['campus_name'];
				$department_name = $data[$i]['department_name'];
				$academic_year = $data[$i]['batch_year']+$data[$i]['part_no']-1;
				if($data[$i]['shift_name'] == 'EVENING') {
					$category = 'EVENING';
				} elseif(
				    $data[$i]['shift_name'] == 'MORNING' and 
				    ($data[$i]['category_name'] == 'SELF FINANCE' or $data[$i]['category_name'] == 'OTHER PROVINCES SELF FINANCE')) {
					$category = 'SELF FINANCE';
				} elseif(
				    $data[$i]['shift_name'] == 'MORNING' and 
				    $data[$i]['category_name'] == 'FOREIGN PKTAP') {
					$category = 'FOREIGN PKTAP';
				} elseif(
				    $data[$i]['shift_name'] == 'MORNING' and 
				    $data[$i]['category_name'] == 'FOREIGN SELF FINANCE') {
					$category = 'FOREIGN SELF';
				} elseif(
				    $data[$i]['shift_name'] == 'MORNING' and 
				    ($data[$i]['category_name'] != 'SELF FINANCE' or $data[$i]['category_name'] != 'OTHER PROVINCES SELF FINANCE' or $data[$i]['category_name'] != 'FOREIGN SELF FINANCE')) {
					$category = 'MERIT';
				}
				
				if($data[$i]['program_name'] == 'BS (PHYSICAL EDUCATION, HEALTH AND SPORTS SCIENCES)') {
					$program_name = 'BS (PHYSICAL EDU., HEALTH & SPORTS SCI.)';
				} elseif(
				    $data[$i]['program_name'] == 'B.B.A (HONS) (BUSINESS ADMINISTRATION)') {
					$program_name = 'B.B.A (HONS)';
				} elseif(
				    $data[$i]['program_name'] == 'B.Ed (B.ED (SECONDARY) 1.5-YEAR)') {
					$program_name = 'B.ED (SECONDARY) 1.5-YEAR';
				} elseif(
				    $data[$i]['program_name'] == 'B.Ed (B.ED (SECONDARY) 2.5-YEAR)') {
					$program_name = 'B.ED (SECONDARY) 2.5-YEAR';
				} elseif(
				    $data[$i]['program_name'] == 'B.Ed (B.ED (ELEMENTARY))') {
					$program_name = 'B.ED (ELEMENTARY)';
				} elseif(
				    $data[$i]['program_name'] == 'M.B.A (BUSINESS ADMINISTRATION)') {
					$program_name = 'M.B.A (4-Year Degree Program)';
				} elseif(
				    $data[$i]['program_name'] == 'M.B.A (HONS) (BUSINESS ADMINISTRATION)') {
					$program_name = 'M.B.A (HONS)';
				} elseif(
				    $data[$i]['program_name'] == 'LL.B (LAW)') {
					$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
				} else {
					$program_name = $data[$i]['program_name'];
				}
				if($data[$i]['shift_name'] == 'EVENING') {
					if($program_name == 'B.B.A (HONS) (BUSINESS ADMINISTRATION)') {
						$program_name = 'B.B.A (HONS) - EVENING';
					} elseif($program_name == 'B.B.A (HONS) (BUSINESS ADMINISTRATION (OLD CAMPUS))') {
						$program_name = 'B.B.A (HONS) - OLD CAMPUS EVENING';
						$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
						$department_name = 'ELSA KAZI CAMPUS, HYDERABAD';
					} elseif($program_name == 'M.B.A (EVENING) (BUSINESS ADMINISTRATION)') {
						$program_name = 'M.B.A (EVENING)';
					} elseif($program_name == 'BS (ENGLISH LANGUAGE & LITERATURE)') {
						$program_name = 'BS (ENGLISH LANG. & LITER.) - EVENING';
					} elseif($program_name == 'M.A (ENGLISH LANGUAGE & LITERATURE)') {
						$program_name = 'M.A (ENGLISH LANG. & LITER.) EVENING';
					} elseif($program_name == 'BS (MEDIA AND COMMUNICATION STUDIES)') {
						$program_name = 'BS (MEDIA AND COMM. STUDIES) - EVENING';
					
					} elseif($program_name == 'BS (MEDICAL LABORATORY TECHNOLOGY)') {
						$program_name = 'BS (MEDICAL LAB. TECHNOLOGY) - EVENING';
						
					} elseif($program_name == 'BS (ENGLISH LANGUAGE AND LITERATURE (OLD CAMPUS))') {
						$program_name = 'BS (ENGLISH LANG. & LITER.) OLD CAMPUS';
						$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
						$department_name = 'ELSA KAZI CAMPUS, HYDERABAD';					
					} else {
						$program_name = $program_name.' - EVENING';
						$campus_name = $data[$i]['campus_name'];
						$department_name = $data[$i]['department_name'];
					}
				} else {
					$program_name = $program_name;
				}
				if($data[$i]['campus_name'] !== 'ALLAMA I.I. KAZI CAMPUS, JAMSHORO') {
					$department_name = $data[$i]['campus_name'];
				} else {
					$department_name = $data[$i]['department_name'];
				}
				$image_path = $this->studentmodel->getImagePath($data[$i]['candidate_id']);
				$pdf->Image($imageF, $x, $y, 85.6, 0, '','',true);
				$pdf->Image($imageB, $x+86.2, $y, 85.6, 0, '','',true);
				$pdf->Image($image_path, $x+3, $y+16.5, 20, 26.1, '','',true);
				$pdf->Image($sign, $x+61, $y+30, 22, 12, '','',true);
				$pdf->Image($logoT, $x+86.2, $y+16.7, 85.6, 25.6, $type = 'JPG', $link = '', $align = 'C', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, 0, $fitbox = 'CM', $hidden = false, $fitonpage = false, $alt = false, $altimgs = array());
				$style = array('border' => false,'vpadding' => 0,'hpadding' => 0,'fgcolor' => array(0,0,0),'bgcolor' => array(255,255,255));
				$pdf->write2DBarcode($data[$i]['candidate_id'], 'QRCODE,H',$x+155, $y+1, 14, 14, $style, 'N', true);
				$pdf->setPageMark();
				$pdf->SetTextColor(0, 0, 0, 0);
				$pdf->SetFont('calibrib', '', 8.5);
				$pdf->MultiCell(68, 0, $campus_name, $border, 'L', 0, 1, $x+16, $y+11.1, true, 0, false, true, 0);			
				if(strlen($program_name)>30){	$pdf->SetFont('clrndnk', '', 9); } else { $pdf->SetFont('clrndnk', '', 10);	}
				$pdf->MultiCell(84.5, 0, $program_name, $border, 'C', 0, 1, $x+0.5, $y+44.8, true, 0, false, true, 0);
				$pdf->SetFont('clrndnk', '', 10);
				$pdf->MultiCell(83.5, 0, $data[$i]['part_name'].' - ACADEMIC YEAR '.$academic_year, $border, 'C', 0, 1, $x+1, $y+49.3, true, 0, false, true, 0);
				$pdf->SetFont('clrndnk', '', 9);
				$pdf->MultiCell(67, 13, $department_name, $border, 'C', 0, 1, $x+87.5, $y+1.5, true, 0, false, true, 13, 'M');
				$pdf->SetFont('calibrib', 'B', 4);
				$pdf->MultiCell(10.5, 0, 'Design by AYP', $border, 'R', 0, 1, $x+157, $y+51.2, true, 0, false, true, 0);
				$pdf->SetTextColor(100, 87, 0, 0);
				$pdf->SetFont('tangent', '', 7);
				$pdf->setFontSpacing(0.254);
				$pdf->MultiCell(10, 0, 'ID # ', $border, 'L', 0, 1, $x+68.2, $y+16.7, true, 0, false, true, 0);
				$pdf->MultiCell(20, 0, 'Name :', $border, 'L', 0, 1, $x+26.5, $y+17, true, 0, false, true, 0);
				$pdf->MultiCell(35, 0, 'Roll No :', $border, 'L', 0, 1, $x+26.5, $y+28.2, true, 0, false, true, 0);
				$pdf->MultiCell(25, 0, 'Valid Upto :', $border, 'L', 0, 1, $x+26.5, $y+36.5, true, 0, false, true, 0);
				if(!empty($data[$i]['fathers_name'])){ $pdf->MultiCell(35, 0, 'Father\'s Name :', $border, 'L', 0, 1, $x+90, $y+17, true, 0, false, true, 0); }
				if(!empty($data[$i]['surname'])){ $pdf->MultiCell(35, 0, 'Surname :', $border, 'L', 0, 1, $x+90, $y+23.4, true, 0, false, true, 0); }
				if(!empty($data[$i]['blood_group'])){ $pdf->MultiCell(35, 0, 'Blood Group :', $border, 'L', 0, 1, $x+90, $y+30.6, true, 0, false, true, 0); }
				if(!empty($data[$i]['family_mobile'])){ $pdf->MultiCell(30, 0, 'Emergency Contact :', $border, 'L', 0, 1, $x+140, $y+27.8, true, 0, false, true, 0); }
				$pdf->MultiCell(35, 0, 'Address :', $border, 'L', 0, 1, $x+90, $y+34, true, 0, false, true, 0);
				$pdf->MultiCell(15.3, 0, 'Category :', $border, 'L', 0, 1, $x+137, $y+16.7, true, 0, false, true, 0);
				$pdf->SetTextColor(255, 255, 255, 255);
				$pdf->SetFont('calibrib', 'B', 7.7);
				$pdf->setFontSpacing(0);
				$pdf->MultiCell(10, 0, $data[$i]['seat_no'], $border, 'L', 0, 1, $x+74, $y+16.6, true, 0, false, true, 0);
				$pdf->MultiCell(21, 0, $category, $border, 'L', 0, 1, $x+151.6, $y+16.6, true, 0, false, true, 0);
				$pdf->SetFont('sanskrit', 'B', 10);
				$pdf->MultiCell(58, 8, $data[$i]['candidate_name'], $border, 'L', 0, 1, $x+26.5, $y+19.3, true, 0, false, true, 0);
				$pdf->MultiCell(79, 0, $data[$i]['fathers_name'], $border, 'L', 0, 1, $x+90, $y+19.3, true, 0, false, true, 0);
				$pdf->MultiCell(79, 0, $data[$i]['surname'], $border, 'L', 0, 1, $x+90, $y+25.7, true, 0, false, true, 0);
				$pdf->SetFont('times', 'B', 9);
				$pdf->MultiCell(35, 0, $data[$i]['rollno'], $border, 'L', 0, 1, $x+26.5, $y+30.5, true, 0, false, true, 0);
				$pdf->SetFont('times', 'BI', 9);
				$pdf->MultiCell(15, 0, $data[$i]['blood_group'], $border, 'L', 0, 1, $x+110, $y+30.1, true, 0, false, true, 0);
				$pdf->MultiCell(30, 0, $data[$i]['family_mobile'], $border, 'L', 0, 1, $x+140, $y+30.1, true, 0, false, true, 0);
				$pdf->SetFont('times', 'i', 7);
				$pdf->MultiCell(79, 6.3, $data[$i]['present_postel_address'], $border, 'L', 0, 1, $x+90, $y+36.3, true, 0, false, true, 6.5);
				$pdf->SetFont('calibrib', 'B', 6.5);
				$pdf->MultiCell(25, 0, 'DIRECTOR ADMISSIONS', $border, 'L', 0, 1, $x+59.8, $y+39.7, true, 0, false, true, 0);
				$pdf->SetTextColor(0, 100, 100, 0);
				$pdf->SetFont('times', 'B', 8);
				$pdf->MultiCell(25, 0, 'DECEMBER '.$academic_year, $border, 'L', 0, 1, $x+26.5, $y+38.8, true, 0, false, true, 0);
				$pdf->SetFont('arialb', '', 11);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],0,1), $border, 'C', 0, 1, $x+91.55, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],1,1), $border, 'C', 0, 1, $x+96.58, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],2,1), $border, 'C', 0, 1, $x+101.61, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],3,1), $border, 'C', 0, 1, $x+106.64, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],4,1), $border, 'C', 0, 1, $x+111.67, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],5,1), $border, 'C', 0, 1, $x+116.7, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],6,1), $border, 'C', 0, 1, $x+121.73, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],7,1), $border, 'C', 0, 1, $x+126.76, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],8,1), $border, 'C', 0, 1, $x+131.79, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],9,1), $border, 'C', 0, 1, $x+136.82, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],10,1), $border, 'C', 0, 1, $x+141.85, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],11,1), $border, 'C', 0, 1, $x+146.88, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],12,1), $border, 'C', 0, 1, $x+151.91, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],13,1), $border, 'C', 0, 1, $x+156.94, $y+46, true, 0, false, true, 0);
				$pdf->MultiCell(4.5, 0, substr($data[$i]['cnic_no'],14,1), $border, 'C', 0, 1, $x+161.97, $y+46, true, 0, false, true, 0);
				$y = $y + 55.8;
				if(($i+1)%5==0){
					$pdf->AddPage();
					$x = 18.65;
					$y = 9;
				}				
			}
			$pdf->lastPage();
			ob_end_clean();
			$pdf->Output('ID_Card_Report_'.$department_name.'.pdf', 'I');
			exit;
		}
	}
	public function idcardpaper(){
    	if(isset($_GET['s_id'])&&isset($_GET['pt_id']) && isset($_GET['sh_id']) && isset($_GET['c_id']) && isset($_GET['p_id']) && isset($_GET['pl_id'])){
            $session_id = isValidData($_GET['s_id']);
            $shift_id = isValidData($_GET['sh_id']);
            $prog_type_id = isValidData($_GET['pt_id']);
            $campus_id = isValidData($_GET['c_id']);
            $part_id = isValidData($_GET['p_id']);
            
            $prog_list_id = json_decode(urldecode($_GET['pl_id']));
            //prePrint($prog_list_id);
            if(!is_numeric($prog_list_id)){
            $prog_list_id_str = join($prog_list_id,',');    
            }else{
            $prog_list_id_str=$_GET['pl_id'];
            }
            
            //prePrint($prog_list_id_str);
    
    	}else{
    	    exit("<h1>Please Must Select All parameters</h1>");
    	}
    	$roll_no = null;
    	if(isset($_GET['roll_no'])){
    	     $roll_no = $_GET['roll_no'];
    	   //  $roll_no = explode($roll_no,",");
    	}
    	$response = $this->StudentReports_model->getStudentByProgram($campus_id,$prog_type_id,$session_id,$shift_id,$prog_list_id_str,$part_id,$roll_no);
        foreach ($response as $key=>$value) {				
        	$image_path  =$value['PROFILE_IMAGE'];
           // prePrint($value);
            //exit();
        	$data[] = $value;
        }		
    	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
    	$pdf->SetCreator(PDF_CREATOR);
    	$pdf->SetAuthor(PDF_AUTHOR);
    	$pdf->SetTitle('ID Card Report '.$data[0]['PROGRAM_TITLE'].' '.$data[0]['PART_NAME']);
    	$pdf->SetSubject('');
    	$pdf->SetKeywords('');
    	$pdf->setPrintHeader(false);
    	$pdf->setPrintFooter(false);
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	$pdf->SetAutoPageBreak(FALSE);
    	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    		require_once(dirname(__FILE__).'/lang/eng.php');
    		$pdf->setLanguageArray($l);
    	}		
    	$imageF = K_PATH_IMAGES.'id_card_front.jpg';
    	$imageB = K_PATH_IMAGES.'id_card_back.jpg';
    	$sign = K_PATH_IMAGES.'DA_sign.png';
    	$logoT = K_PATH_IMAGES.'logo_t.jpg';
    			
    	$pdf->AddPage();
    	$border = 0;
    	$x = 18.65;
    	$y = 9;
    	$count = count($data);
    	$numPages = round(count($data)/6);
    	$prev_program = "";
    	$k = 0;
    	for ($i = 0; $i < $count; $k++,$i++) {
    		$category = '';
    		$part_name = $data[$i]['PART_NAME'];
    		$program_name = $data[$i]['PROGRAM_TITLE'];
    		$campus_name = $data[$i]['CAMPUS_NAME'];
    		$department_name = $data[$i]['DEPT_NAME'];
    		$academic_year = $data[$i]['BATCH_YEAR']+$data[$i]['PART_NO']-1;
            if($prev_program!=""&&$prev_program!=$data[$i]['PROGRAM_TITLE']){
                $k=0;
                $pdf->AddPage();
    			$x = 18.65;
    			$y = 9;
            }
            $prev_program = $data[$i]['PROGRAM_TITLE'];
    		if($data[$i]['SHIFT_NAME'] == 'EVENING') {
    			$category = 'EVENING';
    		} elseif ($data[$i]['SHIFT_NAME'] == 'MORNING' and ($data[$i]['CATEGORY_NAME'] == 'SELF FINANCE' or $data[$i]['CATEGORY_NAME'] == 'OTHER PROVINCES SELF FINANCE')) {
    			$category = 'SELF FINANCE';
    		} elseif ($data[$i]['SHIFT_NAME'] == 'MORNING' and $data[$i]['CATEGORY_NAME'] == 'FOREIGN PKTAP') {
    			$category = 'MERIT';
    		} elseif ($data[$i]['SHIFT_NAME'] == 'MORNING' and $data[$i]['CATEGORY_NAME'] == 'FOREIGN SELF FINANCE') {
    			$category = 'FOREIGN SELF FINANCE';
    		} elseif ($data[$i]['SHIFT_NAME'] == 'MORNING' and $data[$i]['CATEGORY_NAME'] == 'SPECIAL SELF FINANCE') {
    			$category = 'SPECIAL SELF FINANCE';
    		} elseif ($data[$i]['SHIFT_NAME'] == 'MORNING' and ($data[$i]['CATEGORY_NAME'] != 'SELF FINANCE' or $data[$i]['CATEGORY_NAME'] != 'OTHER PROVINCES SELF FINANCE' or $data[$i]['CATEGORY_NAME'] != 'FOREIGN SELF FINANCE')) {
    			$category = 'MERIT';
    		}
    				
    		//$category = "TEST";
    
    		if($data[$i]['PROGRAM_TITLE'] == 'BS (PHYSICAL EDUCATION, HEALTH AND SPORTS SCIENCE)') {
    			$program_name = 'BS (PHYSICAL EDU., HEALTH & SPORTS SCI.)';
    		} elseif ($data[$i]['PROGRAM_TITLE'] == 'B.B.A (HONS) BUSINESS ADMINISTRATION') {
    			$program_name = 'B.B.A (HONS)';
    		} elseif($data[$i]['PROGRAM_TITLE'] == 'M.B.A (4-Year Degree Program) BUSINESS ADMINISTRATION)') {
    			$program_name = 'M.B.A (4-Year Degree Program)';
    		} elseif($data[$i]['PROGRAM_TITLE'] == 'M.B.A (HONS) BUSINESS ADMINISTRATION') {
    			$program_name = 'M.B.A (HONS)';
    		} elseif($data[$i]['PROGRAM_TITLE'] == 'M.A (PASS) ENGLISH LANGUAGE AND LITERATURE') {
    			$program_name = 'M.A (PASS) ENGLISH LANG. & LITERATURE';
    		} elseif($data[$i]['PROGRAM_TITLE'] == 'L.L.B (HONS)') {
    			$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
    		} elseif($data[$i]['PROGRAM_TITLE'] == 'L.L.M (LAW)') {
    			$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
    		} elseif($data[$i]['PROGRAM_TITLE'] == 'DOCTOR OF PHARMACY (PHARM.D)') {
    		    $part_name = str_replace("YEAR","PROF.",$part_name);
    		} else {
    			$program_name = $data[$i]['PROGRAM_TITLE'];
    		}
    
    		if($data[$i]['SHIFT_NAME'] == 'EVENING') {
    			if($program_name == 'B.B.A (HONS) BUSINESS ADMINISTRATION') {
    				$program_name = 'B.B.A (HONS) - EVENING';
    			} elseif($program_name == 'B.B.A (HONS) BUSINESS ADMINISTRATION - OLD CAMPUS') {
    				$program_name = 'B.B.A (HONS) - EVENING';
    				$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
    				$department_name = 'ELSA KAZI CAMPUS, HYDERABAD';
    			} elseif($program_name == 'BS (ENGLISH LANGUAGE AND LITERATURE)') {
    				$program_name = 'BS (ENGLISH LANG. & LIT.) - EVENING';
    			} elseif($program_name == 'M.A (PASS) ENGLISH LANGUAGE AND LITERATURE') {
    				$program_name = 'M.A (ENGLISH LANG. & LIT.) EVENING';
    			} elseif($program_name == 'BS (MEDIA AND COMMUNICATION STUDIES)') {
    				$program_name = 'BS (MEDIA & COMM. STUDIES) - EVENING';
    			} elseif($program_name == 'BS (MEDICAL LABORATORY TECHNOLOGY)') {
    				$program_name = 'BS (MEDICAL LAB. TECHNOLOGY) - EVENING';
    			} elseif($program_name == 'BS (ENGLISH LANGUAGE AND LITERATURE) - OLD CAMPUS') {
    				$program_name = 'BS (ENGLISH LANG. & LIT.) - OLD CAMPUS ';
    				$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
    				$department_name = 'ELSA KAZI CAMPUS, HYDERABAD';
    			}elseif($program_name == 'L.L.M (LAW)') {
    				$campus_name = 'ELSA KAZI CAMPUS, HYDERABAD';
    			} else {
    				$program_name = $program_name.' - EVENING';
    				$campus_name = $data[$i]['CAMPUS_NAME'];
    				$department_name = $data[$i]['DEPT_NAME'];
    			}
    		} else {
    			$program_name = $program_name;
    		}
    				
    		if($data[$i]['CAMPUS_NAME'] !== 'UNIVERSITY OF SINDH, JAMSHORO') {
    			$department_name = $data[$i]['DEPT_NAME'];
    			$campus_name = $data[$i]['CAMPUS_NAME'];
    		} else {
    			$department_name = $data[$i]['DEPT_NAME'];
    			$campus_name = 'ALLAMA I.I. KAZI CAMPUS';
    		}
    		//$image_path = $this->studentmodel->getImagePath($data[$i]['candidate_id']);
            $image_path = K_PATH_PROFLE_IMAGES.$data[$i]['PROFILE_IMAGE'];
            $pdf->Image($imageF, $x, $y, 85.6, 0, '','',true);
    		$pdf->Image($imageB, $x+86.2, $y, 85.6, 0, '','',true);
    		$pdf->Image($image_path, $x+3, $y+16.5, 20, 26.1, '','',true);
    		$pdf->Image($sign, $x+61, $y+32.5, 22, 8, '','',true);
    		$pdf->Image($logoT, $x+86.2, $y+16.7, 85.6, 25.6, $type = 'JPG', $link = '', $align = 'C', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, 0, $fitbox = 'CM', $hidden = false, $fitonpage = false, $alt = false, $altimgs = array());
    		$style = array('border' => false,'vpadding' => 0,'hpadding' => 0,'fgcolor' => array(0,0,0),'bgcolor' => array(255,255,255));
    		$qr_data = json_encode(array("USER_ID"=>$data[$i]['USER_ID'],"APPLICATION_ID"=>$data[$i]['APPLICATION_ID']));
            $pdf->write2DBarcode($qr_data, 'QRCODE,H',$x+155, $y+1, 14, 14, $style, 'N', true);
    		$pdf->setPageMark();
    		$pdf->SetTextColor(0, 0, 0, 0);
    		$pdf->SetFont('calibrib', '', 8.5);
    		$pdf->MultiCell(68, 0, $campus_name, $border, 'L', 0, 1, $x+16, $y+11.1, true, 0, false, true, 0);			
    		if(strlen($program_name)>30){	$pdf->SetFont('clrndnk', '', 9); } else { $pdf->SetFont('clrndnk', '', 10);	}
    		$pdf->MultiCell(84.5, 0, $program_name, $border, 'C', 0, 1, $x+0.5, $y+44.8, true, 0, false, true, 0);
    		$pdf->SetFont('clrndnk', '', 10);
    		$pdf->MultiCell(83.5, 0, $part_name.' - ACADEMIC YEAR '.$academic_year, $border, 'C', 0, 1, $x+1, $y+49.3, true, 0, false, true, 0);
    		$pdf->SetFont('clrndnk', '', 9);
    		$pdf->MultiCell(67, 13, $department_name, $border, 'C', 0, 1, $x+87.5, $y+1.5, true, 0, false, true, 13, 'M');
    		$pdf->SetFont('calibrib', 'B', 4);
    		$pdf->MultiCell(10.5, 0, 'Design by AYP', $border, 'R', 0, 1, $x+157, $y+51.2, true, 0, false, true, 0);
    		$pdf->SetTextColor(100, 87, 0, 0);
    		$pdf->SetFont('tangent', '', 7);
    		$pdf->setFontSpacing(0.254);
			$pdf->MultiCell(10, 0, 'ID # ', $border, 'L', 0, 1, $x+68.2, $y+16.7, true, 0, false, true, 0);
			$pdf->MultiCell(20, 0, 'Name :', $border, 'L', 0, 1, $x+26.5, $y+17, true, 0, false, true, 0);
			$pdf->MultiCell(35, 0, 'Roll No :', $border, 'L', 0, 1, $x+26.5, $y+28.2, true, 0, false, true, 0);
			$pdf->MultiCell(25, 0, 'Valid Upto :', $border, 'L', 0, 1, $x+26.5, $y+36.5, true, 0, false, true, 0);
			if(!empty($data[$i]['FNAME'])){ $pdf->MultiCell(35, 0, 'Father\'s Name :', $border, 'L', 0, 1, $x+90, $y+17, true, 0, false, true, 0); }
			if(!empty($data[$i]['LAST_NAME'])){ $pdf->MultiCell(35, 0, 'Surname :', $border, 'L', 0, 1, $x+90, $y+23.4, true, 0, false, true, 0); }
			if(!empty($data[$i]['BLOOD_GROUP'])){ $pdf->MultiCell(35, 0, 'Blood Group :', $border, 'L', 0, 1, $x+90, $y+30.6, true, 0, false, true, 0); }
			if(!empty($data[$i]['FAMILY_CONTACT_NO'])){ $pdf->MultiCell(30, 0, 'Emergency Contact :', $border, 'L', 0, 1, $x+140, $y+27.8, true, 0, false, true, 0); }
			$pdf->MultiCell(35, 0, 'Address :', $border, 'L', 0, 1, $x+90, $y+34, true, 0, false, true, 0);
			$pdf->MultiCell(15.3, 0, 'Category :', $border, 'L', 0, 1, $x+137, $y+16.7, true, 0, false, true, 0);
			$pdf->SetTextColor(255, 255, 255, 255);
			$pdf->SetFont('calibrib', 'B', 7.7);
			$pdf->setFontSpacing(0);
			$pdf->MultiCell(12, 0, $data[$i]['APPLICATION_ID'], $border, 'L', 0, 1, $x+74, $y+16.6, true, 0, false, true, 0);
			$pdf->MultiCell(21, 0, $category, $border, 'L', 0, 1, $x+151.6, $y+16.6, true, 0, false, true, 0);
			$pdf->SetFont('sanskrit', 'B', 10);
			$pdf->MultiCell(58, 8, $data[$i]['FIRST_NAME'], $border, 'L', 0, 1, $x+26.5, $y+19.3, true, 0, false, true, 0);
			$pdf->MultiCell(79, 0, $data[$i]['FNAME'], $border, 'L', 0, 1, $x+90, $y+19.3, true, 0, false, true, 0);
			$pdf->MultiCell(79, 0, $data[$i]['LAST_NAME'], $border, 'L', 0, 1, $x+90, $y+25.7, true, 0, false, true, 0);
			$pdf->SetFont('times', 'B', 9);
			$pdf->MultiCell(35, 0, $data[$i]['ROLL_NO'], $border, 'L', 0, 1, $x+26.5, $y+30.5, true, 0, false, true, 0);
			$pdf->SetFont('times', 'BI', 9);
			$pdf->MultiCell(15, 0, $data[$i]['BLOOD_GROUP'], $border, 'L', 0, 1, $x+110, $y+30.1, true, 0, false, true, 0);
			$pdf->MultiCell(30, 0, $data[$i]['FAMILY_CONTACT_NO'], $border, 'L', 0, 1, $x+140, $y+30.1, true, 0, false, true, 0);
			$pdf->SetFont('times', 'i', 7);
			$pdf->MultiCell(79, 6.3, $data[$i]['HOME_ADDRESS'], $border, 'L', 0, 1, $x+90, $y+36.3, true, 0, false, true, 6.5);
			$pdf->SetFont('calibrib', 'B', 6.5);
			$pdf->MultiCell(25, 0, 'DIRECTOR ADMISSIONS', $border, 'L', 0, 1, $x+59.8, $y+39.7, true, 0, false, true, 0);
			$pdf->SetTextColor(0, 100, 100, 0);
			$pdf->SetFont('times', 'B', 8);
			$pdf->MultiCell(25, 0, 'DECEMBER '.$academic_year, $border, 'L', 0, 1, $x+26.5, $y+38.8, true, 0, false, true, 0);
			$pdf->SetFont('arialb', '', 11);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],0,1), $border, 'C', 0, 1, $x+91.55, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],1,1), $border, 'C', 0, 1, $x+96.58, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],2,1), $border, 'C', 0, 1, $x+101.61, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],3,1), $border, 'C', 0, 1, $x+106.64, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],4,1), $border, 'C', 0, 1, $x+111.67, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, "-", $border, 'C', 0, 1, $x+116.7, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],5,1), $border, 'C', 0, 1, $x+121.73, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],6,1), $border, 'C', 0, 1, $x+126.76, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],7,1), $border, 'C', 0, 1, $x+131.79, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],8,1), $border, 'C', 0, 1, $x+136.82, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],9,1), $border, 'C', 0, 1, $x+141.85, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],10,1), $border, 'C', 0, 1, $x+146.88, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],11,1), $border, 'C', 0, 1, $x+151.91, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, "-", $border, 'C', 0, 1, $x+156.94, $y+46, true, 0, false, true, 0);
			$pdf->MultiCell(4.5, 0, substr($data[$i]['CNIC_NO'],12,1), $border, 'C', 0, 1, $x+161.97, $y+46, true, 0, false, true, 0);
			$y = $y + 55.8;
			if(($k+1)%5==0){
				$pdf->AddPage();
				$x = 18.65;
				$y = 9;
				$k=-1;
			}							
		}
    	$pdf->lastPage();
    	ob_end_clean();
    	$pdf->Output('ID_Card_Report_'.$department_name.'.pdf', 'I');
    	exit;
	}
	public function paidChallanReport($search_by=0,$search_value=0){
	
		if( $search_by<0 || $search_value<0) {
			exit("Invalid input");
		}

		$search_by = isValidData($search_by);
		$search_value = isValidData($search_value);
		
		$studentInfo = $this->StudentReports_model->getStudentInfo($search_by,$search_value);
		$studentAccount = $this->StudentReports_model->getStudentPaidChallan($search_by,$search_value);
		$refundstudentAccount = $this->StudentReports_model->getStudentRefundChallan($search_by,$search_value);
		
		$SESSION_ID = $studentInfo['SESSION_ID'];
		$CAMPUS_ID = $studentInfo['CAMPUS_ID'];
		$PROGRAM_TYPE_ID = $studentInfo['PROGRAM_TYPE_ID'];
		$SHIFT_ID = $studentInfo['SHIFT_ID'];
		$PROG_LIST_ID = $studentInfo['PROG_LIST_ID'];
		$FEE_CATEGORY_TYPE_ID = $studentInfo['FEE_CATEGORY_TYPE_ID'];
		$USER_ID = $studentInfo['USER_ID'];
		$APPLICATION_ID = $studentInfo['APPLICATION_ID'];
		$SELECTION_LIST_ID = $studentInfo['SELECTION_LIST_ID'];
		$stufeestructure = $this->StudentReports_model->getFeeStructure($SESSION_ID,$CAMPUS_ID,$PROGRAM_TYPE_ID,$SHIFT_ID,$PROG_LIST_ID,$FEE_CATEGORY_TYPE_ID);
		$enrollmentFee = $this->StudentReports_model->getEnrollmentFee($USER_ID,$APPLICATION_ID,$SESSION_ID,$PROGRAM_TYPE_ID);
		
		$qr_code = $studentInfo['APPLICATION_ID'];
		// $qr_code = json_encode($qr_code);
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle('Paid_Challan_Report_'.$studentInfo['APPLICATION_ID'].'.pdf');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetAutoPageBreak(FALSE);
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}		
        $logo = K_PATH_IMAGES.'avatar-1.jpg';
		$phone = K_PATH_IMAGES.'phone.jpg';
		$email = K_PATH_IMAGES.'email.jpg';
		$url = K_PATH_IMAGES.'url.jpg';
		$style = array('border' => false,'padding' => 'auto','fgcolor' => array(0,0,0),'bgcolor' => false,'position' => 'R','module_width' => 1,'module_height' => 1);
		$tDate=date("F j, Y");
		$pdf->AddPage();
		//****************** HEADER START ***********************//
		$pdf->Image($logo, 23, 10, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		$pdf->SetXY(50, 13);
		$pdf->SetFont('Times', 'B', 24);
		$pdf->Cell(0, 0, 'UNIVERSITY OF SINDH', 0, 1, 'L', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(50, 23);
		$pdf->SetFont('Times', '', 16);
		$pdf->Cell(0, 0, 'Jamshoro, Sindh, Pakistan.', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
		$pdf->Ln(2);		
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetXY(50, 24);
        $pdf->Cell(121, 0, '022-9213166', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 29);
		$pdf->Cell(121, 0, 'dir.adms@usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 34);
		$pdf->Cell(121, 0, 'www.usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');		
        $pdf->Image($phone, 173, 20, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($email, 173, 25, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($url, 173, 30, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);
		$pdf->write2DBarcode($qr_code, 'QRCODE,L', 0, 17, 20, 20, $style, 'B');
		$pdf->SetXY(15, 33);
		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(35, 0, 'Directorate of', 0, 1, 'C', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(15, 37);
		$pdf->SetFont('Times', 'B', 12);
		$pdf->Cell(35, 0, 'Admissions', 0, 0, 'C', 0, '', 0, false, 'T', 'T');
		$pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Dated : '.$tDate, 0, 1, 'R', 0, '', 0, false, 'T', 'T');
		$pdf->Cell(0, 5, '', 'T', 1, 'C', 0, '', 0, false, 'B', 'B');
		//****************** HEADER END ***********************//
		
		$boarder = 1;
		$x = 20;
		$y = 15;
		//Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
		$pdf->SetXY(15, 55);
		$pdf->SetFont('Times', 'B', 16);
		//$pdf->Cell(180, 0, 'ADMISSION FEES PAID CHALLAN DETAILS', 0, 0, 'C', 0, '', 0, false, 'T', 'T');
		$pdf->MultiCell(180, 0, 'ADMISSION FEES PAID CHALLAN DETAILS', 0, 'C', 0, 1, $x, $y+30, true, 0, false, true);
		$pdf->SetFont('Times', '', 10);
		$pdf->MultiCell(40, 7, 'Application ID :', 0, 'R', 0, 1, $x, $y+40, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(30, 7, 'CNIC No. :', 0, 'R', 0, 1, $x+80, $y+40, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Student\'s Name :', 0, 'R', 0, 1, $x, $y+48, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Father\'s Name :', 0, 'R', 0, 1, $x, $y+56, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Surname : ', 0, 'R', 0, 1, $x, $y+64, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Degree Program :', 0, 'R', 0, 1, $x, $y+72, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Roll No. :', 0, 'R', 0, 1, $x, $y+80, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Category :', 0, 'R', 0, 1, $x, $y+88, true, 0, false, true, 7, 'M');
		
		$pdf->SetFont('Times', 'B', 12);
		$pdf->MultiCell(125, 7, $studentInfo['FIRST_NAME'], $boarder, 'L', 0, 1, $x+42, $y+48, true, 0, false, true, 7, 'M');
		$pdf->SetFont('Times', '', 12);
		$pdf->MultiCell(35, 7, $studentInfo['APPLICATION_ID'], $boarder, 'L', 0, 1, $x+42, $y+40, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(55, 7, $studentInfo['CNIC_NO'], $boarder, 'L', 0, 1, $x+112, $y+40, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['FNAME'], $boarder, 'L', 0, 1, $x+42, $y+56, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['LAST_NAME'], $boarder, 'L', 0, 1, $x+42, $y+64, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['PROGRAM_TITLE'], $boarder, 'L', 0, 1, $x+42, $y+72, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['ROLL_NO'], $boarder, 'L', 0, 1, $x+42, $y+80, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['CATEGORY_NAME'], $boarder, 'L', 0, 1, $x+42, $y+88, true, 0, false, true, 7, 'M');
		
		//****************** FEE STRUCTURE START ***********************//
		$x = 15;
		$pdf->SetFont('Times', 'BU', 13);
		$pdf->MultiCell(125, 7, 'Fees Structure', 0, 'L', 0, 1, $x, $y+95, true, 0, false, true, 7, 'M');
		
		$pdf->SetFillColor(0, 0, 0, 30);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0, 100);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('Times', '', 10);
        $header_fs = array('Class','Semester','Fee Amount','Enrolment / Eligibility Fee','Late Fee','Total Fee');
		$header_width_fs = array(45,25,25,40,25,25);
        $num_headers_fs = count($header_fs);
        for($i = 0; $i < $num_headers_fs; ++$i) {
			$pdf->MultiCell($header_width_fs[$i], 7, $header_fs[$i], 1, 'L', 1, 1, $x, $y+102, true, 0, false, true, 7, 'M');
			$x = $x+$header_width_fs[$i];
        }
        $sum_fees_amount = 0;
        foreach($stufeestructure as $fee){
			$x = 15;
			$enr_fee = 0;
			//echo "<pre>";
			//print_r($feeChallan);
			// print_r($search_value);
			//exit;
			if($fee['PART_NO'] == 1 && ($fee['SEMESTER_ID'] == 1 || $fee['SEMESTER_ID'] == 11)) $enr_fee = $enrollmentFee[0]['AMOUNT'];
			
			$feeChallan = $this->StudentReports_model->getFeeChallan($fee['FEE_PROG_LIST_ID'],$SELECTION_LIST_ID);
			$late_fee = $feeChallan['LATE_FEE'] ? $feeChallan['LATE_FEE'] : "";
			$fees = $fee['FEE_AMOUNT']+$enr_fee+$late_fee;
			$sum_fees_amount+=$fees;
			$pdf->MultiCell($header_width_fs[0], 7, $fee['PART_NAME'], $boarder, 'L', 0, 1, $x, $y+109, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width_fs[1], 7, $fee['SEMESTER_NAME'], $boarder, 'L', 0, 1, $x+$header_width_fs[0], $y+109, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width_fs[2], 7, $fee['FEE_AMOUNT'], $boarder, 'R', 0, 1, $x+$header_width_fs[0]+$header_width_fs[1], $y+109, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width_fs[3], 7, $enr_fee, $boarder, 'R', 0, 1, $x+$header_width_fs[0]+$header_width_fs[1]+$header_width_fs[2], $y+109, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width_fs[4], 7, $late_fee, $boarder, 'R', 0, 1, $x+$header_width_fs[0]+$header_width_fs[1]+$header_width_fs[2]+$header_width_fs[3], $y+109, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width_fs[4], 7, $fee['FEE_AMOUNT']+$enr_fee+$late_fee, $boarder, 'R', 0, 1, $x+$header_width_fs[0]+$header_width_fs[1]+$header_width_fs[2]+$header_width_fs[3]+$header_width_fs[4], $y+109, true, 0, false, true, 7, 'M');
			$y = $y + 7;
        }
        
        $pdf->SetFont('Times', 'B', 13);
		$pdf->MultiCell(array_sum($header_width_fs), 7, 'Total Admission Fees Amount : Rs. '.number_format($sum_fees_amount,2), 0, 'R', 0, 1, $x, $y+109, true, 0, false, true, 7, 'M');
		//****************** FEE STRUCTURE END ***********************//
		
		//****************** PAID FEES CHALLAN START ***********************//
		$x = 15;
		$pdf->SetFont('Times', 'BU', 13);
		$pdf->MultiCell(125, 7, 'Paid Admission Fees Record', 0, 'L', 0, 1, $x, $y+116, true, 0, false, true, 7, 'M');
		
		$pdf->SetFillColor(0, 0, 0, 30);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0, 100);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('Times', '', 10);
        $header = array('Class','Challan No.','Paid Amount','Challan Date','Remarks');
		$header_width = array(45,25,25,25,65);
        $num_headers = count($header);
        
        for($i = 0; $i < $num_headers; ++$i) {
			$pdf->MultiCell($header_width[$i], 7, $header[$i], 1, 'L', 1, 1, $x, $y+123, true, 0, false, true, 7, 'M');
			$x = $x+$header_width[$i];
		}
        
        $sum_paid_amount = 0;
        $dues = 0;
        foreach($studentAccount as $challan){
			$x = 15;
			$paid = $challan['PAID_AMOUNT'];
			$sum_paid_amount+=$paid;
			$pdf->MultiCell($header_width[0], 7, $challan['PART_NAME'], $boarder, 'L', 0, 1, $x, $y+130, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width[1], 7, $challan['CHALLAN_NO'], $boarder, 'L', 0, 1, $x+$header_width[0], $y+130, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width[2], 7, $challan['PAID_AMOUNT'], $boarder, 'L', 0, 1, $x+$header_width[0]+$header_width[1], $y+130, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width[3], 7, $challan['CHALLAN_DATE'], $boarder, 'L', 0, 1, $x+$header_width[0]+$header_width[1]+$header_width[2], $y+130, true, 0, false, true, 7, 'M');
			$pdf->MultiCell($header_width[4], 7, $challan['DETAILS'], $boarder, 'L', 0, 1, $x+$header_width[0]+$header_width[1]+$header_width[2]+$header_width[3], $y+130, true, 0, false, true, 7, 'M');
			$y = $y + 7;
        }		
		//****************** PAID FEES CHALLAN END ***********************//
		//****************** REFUND FEES START ***********************//
		$sum_refund_amount = 0;
		if(count($refundstudentAccount)){
			$y = $y+130;
			$x = 15;
			$pdf->SetFont('Times', 'BU', 13);
			$pdf->MultiCell(125, 7, 'Refund Fees Record', 0, 'L', 0, 1, $x, $y+7, true, 0, false, true, 7, 'M');
		
			$pdf->SetFillColor(0, 0, 0, 30);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0, 0, 0, 100);
			$pdf->SetLineWidth(0.3);
			$pdf->SetFont('Times', '', 10);
			$header = array('Class','Challan No.','Refund Amount','Challan Date','Remarks');
			$header_width = array(45,25,25,25,65);
			$num_headers = count($header);
        
        for($i = 0; $i < $num_headers; ++$i) {
        $pdf->MultiCell($header_width[$i], 7, $header[$i], 1, 'L', 1, 1, $x, $y+14, true, 0, false, true, 7, 'M');
        $x = $x+$header_width[$i];
        }
        
        $dues = 0;
        foreach($refundstudentAccount as $challan){
            
        $x = 15;
        
        $paid = $challan['PAID_AMOUNT'];
        $sum_refund_amount+=$paid;
        $pdf->MultiCell($header_width[0], 7, $challan['PART_NAME'], $boarder, 'L', 0, 1, $x, $y+21, true, 0, false, true, 7, 'M');
        $pdf->MultiCell($header_width[1], 7, $challan['CHALLAN_NO'], $boarder, 'L', 0, 1, $x+$header_width[0], $y+21, true, 0, false, true, 7, 'M');
        $pdf->MultiCell($header_width[2], 7, -$challan['PAID_AMOUNT'], $boarder, 'L', 0, 1, $x+$header_width[0]+$header_width[1], $y+21, true, 0, false, true, 7, 'M');
        $pdf->MultiCell($header_width[3], 7, $challan['CHALLAN_DATE'], $boarder, 'L', 0, 1, $x+$header_width[0]+$header_width[1]+$header_width[2], $y+21, true, 0, false, true, 7, 'M');
        $pdf->MultiCell($header_width[4], 7, $challan['DETAILS'], $boarder, 'L', 0, 1, $x+$header_width[0]+$header_width[1]+$header_width[2]+$header_width[3], $y+21, true, 0, false, true, 7, 'M');
        
        $y = $y + 7;
        
        }
        $pdf->SetFont('Times', 'B', 13);
		$pdf->MultiCell(array_sum($header_width), 7, 'Total Admission Fees Paid Amount : Rs. '.number_format($sum_paid_amount+$sum_refund_amount,2), 0, 'R', 0, 1, $x, $y+28, true, 0, false, true, 7, 'M');
	
		}else{
			$pdf->SetFont('Times', 'B', 13);
		$pdf->MultiCell(array_sum($header_width), 7, 'Total Admission Fees Paid Amount : Rs. '.number_format($sum_paid_amount+$sum_refund_amount,2), 0, 'R', 0, 1, $x, $y+135, true, 0, false, true, 7, 'M');
		}
		
		//****************** REFUND FEES END ***********************//
		//$pdf->MultiCell(45, 8, 'Total Payable Amount', $boarder, 'L', 0, 1, $x, $y+150, true, 0, false, true, 7, 'M');
		//$pdf->MultiCell(45, 8, 'Total Paid Amount', $boarder, 'L', 0, 1, $x, $y+158, true, 0, false, true, 7, 'M');
		//$pdf->MultiCell(45, 8, 'Dues', $boarder, 'L', 0, 1, $x, $y+166, true, 0, false, true, 7, 'M');
        //$x+=45;
        $pdf->SetFont('Times', 'B', 12);
		//$pdf->MultiCell(45, 8,number_format($sum_payable_amount) , $boarder, 'R', 0, 1, $x, $y+150, true, 0, false, true, 7, 'M');
		//$pdf->MultiCell(45, 8, number_format($sum_paid_amount), $boarder, 'R', 0, 1, $x, $y+158, true, 0, false, true, 7, 'M');
		//$pdf->MultiCell(45, 8,number_format($dues), $boarder, 'R', 0, 1, $x, $y+166, true, 0, false, true, 7, 'M');
		
		// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
		//****************** FOOTER START ***********************//
		$pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
		$pdf->Cell(0, 5, '', 'B', 1, 'C', 0, '', 0, false, 'B', 'B');
        $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //****************** FOOTER END ***********************//
		$pdf->lastPage();
		ob_end_clean();
		$pdf->Output('Paid_Challan_Report_'.$studentInfo['APPLICATION_ID'].'.pdf', 'I');
		exit;
	}
	public function admissionLetterReport($search_by=0,$search_value=0){
	 
	    if( $search_by<0 || $search_value<0) {
	        exit("Invalid input");
	    }
	    
	    $search_by = isValidData($search_by);
	    $search_value = isValidData($search_value);
	    $studentInfo = $this->StudentReports_model->getStudentInfo($search_by,$search_value);
	    $studentAccount = $this->StudentReports_model->getStudentPaidChallan($search_by,$search_value);
	    
	    $rec = array();
	    foreach($studentAccount as $value){
	        $rec[$value['PART_NAME']] = $value;
	    }
	    
	    $studentAccount = $rec;
	    
	    $program_type_id = $studentInfo['PROGRAM_TYPE_ID'];
	    $selection_list_id = $studentInfo['SELECTION_LIST_ID'];
	    $stufeestructure = $this->FeeChallan_model->getFeeStructure($program_type_id,$selection_list_id);
	    
	    $qr_code = $studentInfo['APPLICATION_ID'];
	   
	    $rollNo=$studentInfo['ROLL_NO'];
	    $rec=explode("/",$rollNo);
	    
	    $roll = $rec[0];
	    $ACEDIMIC_YEAR=str_replace("K","0",$roll);
	    
	    
	   
	   
	    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle('Paid_Challan_Report_'.$studentInfo['APPLICATION_ID'].'.pdf');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetAutoPageBreak(FALSE);
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}		
        $logo = K_PATH_IMAGES.'avatar-1.jpg';
		$phone = K_PATH_IMAGES.'phone.jpg';
		$email = K_PATH_IMAGES.'email.jpg';
		$url = K_PATH_IMAGES.'url.jpg';
		$style = array('border' => false,'padding' => 'auto','fgcolor' => array(0,0,0),'bgcolor' => false,'position' => 'R','module_width' => 1,'module_height' => 1);
		$tDate=date("F j, Y");
		$pdf->AddPage();
		//****************** HEADER START ***********************//
	    $pdf->Image($logo, 23, 10, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	    $pdf->SetXY(50, 13);
		$pdf->SetFont('Times', 'B', 24);
		$pdf->Cell(0, 0, 'UNIVERSITY OF SINDH', 0, 1, 'L', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(50, 23);
		$pdf->SetFont('Times', '', 16);
		$pdf->Cell(0, 0, 'Jamshoro, Sindh, Pakistan.', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
		$pdf->Ln(2);		
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetXY(50, 24);
        $pdf->Cell(121, 0, '022-9213166', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 29);
		$pdf->Cell(121, 0, 'dir.adms@usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 34);
		$pdf->Cell(121, 0, 'www.usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');		
        $pdf->Image($phone, 173, 20, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($email, 173, 25, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($url, 173, 30, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);
	    $pdf->write2DBarcode($qr_code, 'QRCODE,L', 0, 17, 20, 20, $style, 'B');
	    $pdf->SetXY(15, 33);
		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(35, 0, 'Directorate of', 0, 1, 'C', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(15, 37);
		$pdf->SetFont('Times', 'B', 12);
		$pdf->Cell(35, 0, 'Admissions', 0, 0, 'C', 0, '', 0, false, 'T', 'T');
		$pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Dated : '.$tDate, 0, 1, 'R', 0, '', 0, false, 'T', 'T');
		$pdf->Cell(0, 5, '', 'T', 1, 'C', 0, '', 0, false, 'B', 'B');
		//****************** HEADER END ***********************//
		
		$boarder = 1;
		$x = 20;
		$y = 20;
		//Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
		$pdf->SetXY(15, 55);
		$pdf->SetFont('Times', 'B', 16);
		//$pdf->Cell(180, 0, 'ADMISSION FEES PAID CHALLAN DETAILS', 0, 0, 'C', 0, '', 0, false, 'T', 'T');
		$pdf->MultiCell(180, 0, 'ADMISSION LIST', 0, 'C', 0, 1, $x, $y+30, true, 0, false, true);
		$pdf->SetFont('Times', '', 10);
		$pdf->MultiCell(40, 7, 'Application ID :', 0, 'R', 0, 1, $x, $y+45, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(30, 7, 'CNIC No. :', 0, 'R', 0, 1, $x+80, $y+45, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Student\'s Name :', 0, 'R', 0, 1, $x, $y+53, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Father\'s Name :', 0, 'R', 0, 1, $x, $y+59, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Surname : ', 0, 'R', 0, 1, $x, $y+69, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Degree Program :', 0, 'R', 0, 1, $x, $y+77, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Roll No. :', 0, 'R', 0, 1, $x, $y+85, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(40, 7, 'Category :', 0, 'R', 0, 1, $x, $y+93, true, 0, false, true, 7, 'M');
		
		$pdf->SetFont('Times', 'B', 12);
		$pdf->MultiCell(125, 7, $studentInfo['FIRST_NAME'], $boarder, 'L', 0, 1, $x+42, $y+53, true, 0, false, true, 7, 'M');
		$pdf->SetFont('Times', '', 12);
		$pdf->MultiCell(35, 7, $studentInfo['APPLICATION_ID'], $boarder, 'L', 0, 1, $x+42, $y+45, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(55, 7, $studentInfo['CNIC_NO'], $boarder, 'L', 0, 1, $x+112, $y+45, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['FNAME'], $boarder, 'L', 0, 1, $x+42, $y+61, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['LAST_NAME'], $boarder, 'L', 0, 1, $x+42, $y+69, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['PROGRAM_TITLE'], $boarder, 'L', 0, 1, $x+42, $y+77, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['ROLL_NO'], $boarder, 'L', 0, 1, $x+42, $y+85, true, 0, false, true, 7, 'M');
		$pdf->MultiCell(125, 7, $studentInfo['CATEGORY_NAME'], $boarder, 'L', 0, 1, $x+42, $y+93, true, 0, false, true, 7, 'M');
		
		
		
		//****************** PAID FEES CHALLAN START ***********************//
		$x = 62;
		
		$pdf->SetFillColor(0, 0, 0, 30);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0, 100);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('Times', '', 12);
        $header = array('CLASS','ACADEMIC YEAR');
		$header_width = array(63,63);
        $num_headers = count($header);
        
        for($i = 0; $i < $num_headers; ++$i) {
        $pdf->MultiCell($header_width[$i], 7, $header[$i], 1, 'L', 1, 1, $x, $y+123, true, 0, false, true, 7, 'M');
        $x = $x+$header_width[$i];
        }
        
        $sum_paid_amount = 0;
        $dues = 0;
        foreach($studentAccount as $challan){
            
        $x = 62;
        
        $paid = $challan['PAID_AMOUNT'];
        $sum_paid_amount+=$paid;
        $pdf->MultiCell($header_width[0], 7, $challan['PART_NAME'], $boarder, 'L', 0, 1, $x, $y+130, true, 0, false, true, 7, 'M');
        $pdf->MultiCell($header_width[1], 7, "REGULAR - ".$ACEDIMIC_YEAR++, $boarder, 'L', 0, 1, $x+$header_width[0], $y+130, true, 0, false, true, 7, 'M');
        
        $y = $y + 7;
        
        }
        
        $x=30;
        $pdf->SetFont('Times', 'B', 13);
		$pdf->MultiCell(125, 7, 'DEPUTY DIRECTOR ADMISSIONS', 0, 'L', 0, 1, $x, $y+200, true, 0, false, true, 7, 'M');
        
		//$pdf->SetFont('Times', 'B', 13);
		//$pdf->MultiCell(array_sum($header_width), 7, 'Total Admission Fees Paid Amount : Rs. '.number_format($sum_paid_amount,2), 0, 'R', 0, 1, $x, $y+130, true, 0, false, true, 7, 'M');
	
        $pdf->SetFont('Times', 'B', 12);
		
		//****************** FOOTER START ***********************//
		$pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
		$pdf->Cell(0, 5, '', 'B', 1, 'C', 0, '', 0, false, 'B', 'B');
        $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //****************** FOOTER END ***********************//
	    $pdf->lastPage();
		ob_end_clean();
		$pdf->Output('Admission_List_Report_'.$studentInfo['APPLICATION_ID'].'.pdf', 'I');
		exit;
	}
	public function correctionLetterAndList($search_by=0,$search_value=0){
	 
	    if( $search_by<0 || $search_value<0) {
	        exit("Invalid input");
	    }
	    
	    
	    $search_by = isValidData($search_by);
	    $search_value = isValidData($search_value);
	    $studentInfo = $this->StudentReports_model->getStudentInfo($search_by,$search_value);
	    $studentAccount = $this->StudentReports_model->getStudentPaidChallan($search_by,$search_value);
	    
	    $rec = array();
	    foreach($studentAccount as $value){
	        $rec[$value['PART_NAME']] = $value;
	    }
	    
	    $studentAccount = $rec;
	    
	    $program_type_id = $studentInfo['PROGRAM_TYPE_ID'];
	    $selection_list_id = $studentInfo['SELECTION_LIST_ID'];
	    $stufeestructure = $this->FeeChallan_model->getFeeStructure($program_type_id,$selection_list_id);
	    
	    $qr_code = $studentInfo['APPLICATION_ID'];
	   
	    $rollNo=$studentInfo['ROLL_NO'];
	    $rec=explode("/",$rollNo);
	    
	    $roll = $rec[0];
	    $ACEDIMIC_YEAR=str_replace("K","0",$roll);
	    
	    
	   
	   
	    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle('Paid_Challan_Report_'.$studentInfo['APPLICATION_ID'].'.pdf');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetAutoPageBreak(FALSE);
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}		
        $logo = K_PATH_IMAGES.'avatar-1.jpg';
		$phone = K_PATH_IMAGES.'phone.jpg';
		$email = K_PATH_IMAGES.'email.jpg';
		$url = K_PATH_IMAGES.'url.jpg';
		$style = array('border' => false,'padding' => 'auto','fgcolor' => array(0,0,0),'bgcolor' => false,'position' => 'R','module_width' => 1,'module_height' => 1);
		$tDate=date("F j, Y");
		$pdf->AddPage();
		//****************** HEADER START ***********************//
	    $pdf->Image($logo, 23, 10, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	    $pdf->SetXY(50, 13);
		$pdf->SetFont('Times', 'B', 24);
		$pdf->Cell(0, 0, 'UNIVERSITY OF SINDH', 0, 1, 'L', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(50, 23);
		$pdf->SetFont('Times', '', 16);
		$pdf->Cell(0, 0, 'Jamshoro, Sindh, Pakistan.', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
		$pdf->Ln(2);		
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetXY(50, 24);
        $pdf->Cell(121, 0, '022-9213166', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 29);
		$pdf->Cell(121, 0, 'dir.adms@usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 34);
		$pdf->Cell(121, 0, 'www.usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');		
        $pdf->Image($phone, 173, 20, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($email, 173, 25, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($url, 173, 30, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);
	    $pdf->write2DBarcode($qr_code, 'QRCODE,L', 0, 17, 20, 20, $style, 'B');
	    $pdf->SetXY(15, 33);
		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(35, 0, 'Directorate of', 0, 1, 'C', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(15, 37);
		$pdf->SetFont('Times', 'B', 12);
		$pdf->Cell(35, 0, 'Admissions', 0, 0, 'C', 0, '', 0, false, 'T', 'T');
		$pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Dated : '.$tDate, 0, 1, 'R', 0, '', 0, false, 'T', 'T');
		$pdf->Cell(0, 5, '', 'T', 1, 'C', 0, '', 0, false, 'B', 'B');
		//****************** HEADER END ***********************//
		
		$boarder = 1;
		$x = 20;
		$y = 20;
	
		$pdf->SetXY(15, 55);
		$pdf->SetFont('Times', '', 12);
		$pdf->MultiCell(180, 0, 'No. DA/ ', 0, 'C', 0, 1, $x+60, $y+30, true, 0, false, true);
		$pdf->SetFont('Times', '', 10);
		
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(120, 0, 'The Controller of Examinations (Semester)', 0, 'C', 0, 1, 20, $y+40, true, 0, false, true);
		
		$pdf->SetFont('Times', '', 16);
		$pdf->MultiCell(100, 0, 'University of Sindh, Jamshoro.', 0, 'C', 0, 1, 13, $y+48, true, 0, false, true);
		
		$pdf->SetFont('Times', '', 16);
		$pdf->MultiCell(30, 0, 'Subject:', 0, 'C', 0, 1, 22, $y+70, true, 0, false, true);
		
		$pdf->SetFont('Times', 'BU', 16);
		$pdf->MultiCell(60, 0, 'CORRECTION LIST', 0, 'C', 0, 1, 50, $y+70, true, 0, false, true);
		
		$pdf->SetFont('Times', '', 16);
		$pdf->MultiCell(30, 0, 'Dear Sir,', 0, 'C', 0, 1, 23, $y+90, true, 0, false, true);

		$pdf->SetFont('Times', '', 16);
		$pdf->MultiCell(180, 0, 'I am directed to enclose a list on subject cited above of the following student for your kind information and necessary action as per rules.', 0, 'L', 0, 1, 27, $y+105, true, 0, false, true);
		
		if($studentInfo['GENDER']=="M"){
		    $tag="S/O";
		}else{
		    $tag="D/O";
		}
		
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(180, 0,$studentInfo['FIRST_NAME'], 0, 'L', 0, 1, 27, $y+130, true, 0, false, true);

		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(180, 0,$tag." ".$studentInfo['FNAME'].", ".$studentInfo['LAST_NAME'], 0, 'L', 0, 1, 27, $y+138, true, 0, false, true);
		
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(180, 0,'Assistant Director Admissions', 0, 'L', 0, 1, 27, $y+190, true, 0, false, true);
		
		$pdf->SetFont('Times', '', 16);
		$pdf->MultiCell(180, 0,'University of Sindh, Jamshoro', 0, 'L', 0, 1, 27, $y+197, true, 0, false, true);
		
	
        $pdf->SetFont('Times', 'B', 12);
		
		//****************** FOOTER START ***********************//
		$pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
		$pdf->Cell(0, 5, '', 'B', 1, 'C', 0, '', 0, false, 'B', 'B');
        $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //****************** FOOTER END ***********************//
	    
	    
	    $pdf->AddPage();
		//****************** HEADER START ***********************//
	    $pdf->Image($logo, 23, 10, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	    $pdf->SetXY(50, 13);
		$pdf->SetFont('Times', 'B', 24);
		$pdf->Cell(0, 0, 'UNIVERSITY OF SINDH', 0, 1, 'L', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(50, 23);
		$pdf->SetFont('Times', '', 16);
		$pdf->Cell(0, 0, 'Jamshoro, Sindh, Pakistan.', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
		$pdf->Ln(2);		
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetXY(50, 24);
        $pdf->Cell(121, 0, '022-9213166', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 29);
		$pdf->Cell(121, 0, 'dir.adms@usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');
		$pdf->SetXY(50, 34);
		$pdf->Cell(121, 0, 'www.usindh.edu.pk', 0, 0, 'R', 0, '', 0, false, 'B', 'C');		
        $pdf->Image($phone, 173, 20, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($email, 173, 25, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);		
        $pdf->Image($url, 173, 30, 4, '', 'JPG', '', 'R', false, 300, '', false, false, 0, false, false, false);
	    $pdf->write2DBarcode($qr_code, 'QRCODE,L', 0, 17, 20, 20, $style, 'B');
	    $pdf->SetXY(15, 33);
		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(35, 0, 'Directorate of', 0, 1, 'C', 0, '', 0, false, 'T', 'B');
		$pdf->SetXY(15, 37);
		$pdf->SetFont('Times', 'B', 12);
		$pdf->Cell(35, 0, 'Admissions', 0, 0, 'C', 0, '', 0, false, 'T', 'T');
		$pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Dated : '.$tDate, 0, 1, 'R', 0, '', 0, false, 'T', 'T');
		$pdf->Cell(0, 5, '', 'T', 1, 'C', 0, '', 0, false, 'B', 'B');
		//****************** HEADER END ***********************//
		
		
	    $boarder = 1;
		$x = 20;
		$y = 20;
	
		$pdf->SetXY(15, 55);
		$pdf->SetFont('Times', '', 12);
		$pdf->MultiCell(180, 0, 'No. DA/ ', 0, 'C', 0, 1, $x+60, $y+30, true, 0, false, true);
	    
	    
	    $pdf->SetFont('Times', 'BU', 16);
		$pdf->MultiCell(60, 0, 'CORRECTION LIST', 0, 'C', 0, 1, $x+60, $y+70, true, 0, false, true);
	    
	    $pdf->SetFont('Times', '', 16);
		$pdf->MultiCell(30, 0, 'NAME: ', 0, 'C', 0, 1, 23, $y+100, true, 0, false, true);
		
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(130, 0, $studentInfo['FIRST_NAME'], 0, 'L', 0, 1, 80, $y+100, true, 0, false, true);
		
		$pdf->SetFont('Times', '', 16);
	    $pdf->MultiCell(60, 0, "FATHER'S NAME: ", 0, 'C', 0, 1, 22, $y+110, true, 0, false, true);
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(130, 0, $studentInfo['FNAME'], 0, 'L', 0, 1, 80, $y+110, true, 0, false, true);
		
		$pdf->SetFont('Times', '', 16);
	    $pdf->MultiCell(34, 0, "SURNAME: ", 0, 'C', 0, 1, 27, $y+120, true, 0, false, true);
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(130, 0, $studentInfo['LAST_NAME'], 0, 'L', 0, 1, 80, $y+120, true, 0, false, true);
		
	    $pdf->SetFont('Times', '', 16);
	    $pdf->MultiCell(30, 0, "ROLL NO: ", 0, 'C', 0, 1, 27, $y+130, true, 0, false, true);
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(130, 0, $studentInfo['ROLL_NO'], 0, 'L', 0, 1, 80, $y+130, true, 0, false, true);
	    
	    $pdf->SetFont('Times', '', 16);
	    $pdf->MultiCell(30, 0, "CLASS: ", 0, 'C', 0, 1, 24, $y+140, true, 0, false, true);
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(130, 0, $studentInfo['PROGRAM_TITLE'], 0, 'L', 0, 1, 80, $y+140, true, 0, false, true);
		
		$pdf->SetFont('Times', '', 16);
		$pdf->MultiCell(30, 0, "CAMPUS: ", 0, 'C', 0, 1, 27, $y+160, true, 0, false, true);
		$pdf->SetFont('Times', 'B', 16);
		$pdf->MultiCell(130, 0, $studentInfo['CAMPUS_NAME'], 0, 'L', 0, 1, 80, $y+160, true, 0, false, true);
		
	
		$pdf->MultiCell(180, 0,'Computer Programmer', 0, 'L', 0, 1, 27, $y+220, true, 0, false, true);
		
		
		
	    //****************** FOOTER START ***********************//
		$pdf->SetY(-15);
        $pdf->SetFont('helvetica', 'I', 8);
		$pdf->Cell(0, 5, '', 'B', 1, 'C', 0, '', 0, false, 'B', 'B');
        $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //****************** FOOTER END ***********************//
	    
	    
	    $pdf->lastPage();
		ob_end_clean();
		$pdf->Output('correctionLetterAndList_'.$studentInfo['APPLICATION_ID'].'.pdf', 'I');
		exit;
	}	
	public function idCardChallan($search_by=0,$search_value=0){
	    if( $search_by<0 || $search_value<0) {
	        exit("Invalid input");
	    }
	    $search_by = isValidData($search_by);
	    $search_value = isValidData($search_value);
	    $studentInfo = $this->StudentReports_model->getStudentInfoByPart($search_by,$search_value);
	    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle('IDCard_Challan_Report_.pdf');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetAutoPageBreak(FALSE);
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->setJPEGQuality(150);
		
		$uslogo = K_PATH_IMAGES.'avatar-1.jpg';
		$hbllogo = K_PATH_IMAGES.'hbl-logo.jpg';
		$pdf->AddPage();
		
		
		//MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
		//     Cell($w, $h, $txt, $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
		$x = 7;
		$y = 25;
		$w = 65;
		$h = 0;
		$y_title_increment = 6;
		$y_field_increment = 3.5;
		$font_size_title = 8;
		$font_size_field = 10;
		$title8 = array('arial', '', 8);
		$title9 = array('arial', '', 9);
		$style = array('border' => false,'padding' => 'auto','fgcolor' => array(0,0,0),'bgcolor' => false,'position' => 'S','module_width' => 1,'module_height' => 1);
		$copy = array('BANK\'S COPY', 'ACCOUNT\'S COPY', 'ADMISSION\'S COPY', 'STUDENT\'S COPY');
		$challan_no = substr($studentInfo['YEAR'], -2).$studentInfo['APPLICATION_ID'];
		$program_name_len = strlen($studentInfo['PROGRAM_TITLE']);
		$pdf->MultiCell(1, 185, '', 'R', 'C', 0, 0, 74.5, 10, true, 0, false, true, 0);
		$pdf->MultiCell(1, 185, '', 'R', 'C', 0, 0, 146.5, 10, true, 0, false, true, 0);
		$pdf->MultiCell(1, 185, '', 'R', 'C', 0, 0, 218.5, 10, true, 0, false, true, 0);
		for ($i = 0; $i < 4; ++$i) {
			$pdf->Image($uslogo, $x+3, 10, 15, 17, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);
			$pdf->Image($hbllogo, $x+22, 10, 23, 15, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);
			$pdf->write2DBarcode($studentInfo['APPLICATION_ID'], 'QRCODE,H', $x+45, 10, 20, 20, $style, 'B');
			
			$pdf->SetFont('arial', 'B', 7);
			$pdf->MultiCell($w, $h, $copy[0+$i], 0, 'C', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + 4;
			$pdf->SetFont('arial', '', 8);
			$pdf->MultiCell($w, $h, 'Please receive and creadit to Uinversity of Sindh', 0, 'C', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + 5;
			$pdf->SetFont('arial', '', 9);
			$pdf->MultiCell($w, $h, 'ADMISSION ACCOUNT NUMBER', 0, 'C', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + 4;
			$pdf->SetFont('arial', 'B', 11);
			$pdf->MultiCell($w, $h, 'CMD. 00427992039203', 0, 'C', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + 6;
			$pdf->SetFont('arial', 'B', 9);
			$pdf->MultiCell($w-26, $h, 'CHALLAN NO: '.$challan_no, 0, 'L', 0, 0, $x, $y, true, 0, false, true, 0);
			$pdf->SetFont('arial', '', 9);
			$pdf->MultiCell($w-35, $h, 'DATE: '.date("d-m-Y"), 0, 'R', 0, 1, $x+35, $y, true, 0, false, true, 0);
			$y = $y + 5;
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('arial', 'B', 10);
			$pdf->MultiCell($w, $h, 'This challan is valid upto: '.date("d-m-Y"), 0, 'C', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + 4.5;
			$pdf->SetTextColor(255, 255, 255);
			$pdf->SetFont('arial', 'B', 11);
			$pdf->MultiCell($w, $h, 'ID CARD FEE CHALLAN', 0, 'C', 1, 1, $x, $y, true, 0, false, true, 0);
			$pdf->SetTextColor(0, 0, 0);
			$y = $y + 7;
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w-35, $h, 'ROLL NO :', 0, 'L', 0, 0, $x, $y, true, 0, false, true, 0);
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w-30, $h, $studentInfo['ROLL_NO'], 0, 'L', 0, 1, $x+30, $y, true, 0, false, true, 0);
			$y = $y + 5;
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w-35, $h, 'APPLICATION ID :', 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w-30, $h, $studentInfo['APPLICATION_ID'], 0, 'L', 0, 1, $x+30, $y, true, 0, false, true, 0);
			$y = $y + $y_title_increment;
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w, $h, 'STUDENT\'S NAME :', 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_field_increment;
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w, $h, $studentInfo['FIRST_NAME'], 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_title_increment;
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w, $h, 'FATHER\'S NAME :', 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_field_increment;
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w, $h, $studentInfo['FNAME'], 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_title_increment;
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w, $h, 'SURNAME :', 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_field_increment;
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w, $h, $studentInfo['LAST_NAME'], 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_title_increment;
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w-50, $h, 'CLASS :', 0, 'L', 0, 0, $x, $y, true, 0, false, true, 0);
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w-15, $h, $studentInfo['PART_NAME'], 0, 'L', 0, 1, $x+15, $y, true, 0, false, true, 0);
			$y = $y + $y_title_increment;
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w, $h, 'PROGRAM :', 0, 'L', 0, 1, $x, $y, true, 0, false, false, 0);
			$y = $y + $y_field_increment;
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w, $h, $studentInfo['PROGRAM_TITLE'], 0, 'L', 0, 2, $x, $y, true, 0, false, true);			
			if ($program_name_len > 30) { $y = $y + 10;	} else { $y = $y + $y_title_increment; }
			$pdf->SetFont('arial', '', $font_size_title);
			$pdf->MultiCell($w, $h, 'CAMPUS :', 0, 'L', 0, 2, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_field_increment;
			$pdf->SetFont('arialb', 'B', $font_size_field);
			$pdf->MultiCell($w, $h, $studentInfo['CAMPUS_NAME'], 0, 'L', 0, 1, $x, $y, true, 0, false, true, 0);
			$y = $y + 10;
			$pdf->SetFont('times', 'B', 11);
			$pdf->MultiCell($w-25, $h+12, 'ID CARD FEE', 1, 'R', 0, 1, $x, $y, true, 0, false, true, 12, 'M', true);
			$pdf->MultiCell($w-40, $h+12, 'Rs. 300.00', 1, 'R', 0, 1, $x+40, $y, true, 0, false, true, 12, 'M', true);
			//$y = $y + $y_field_increment + 5;
			//$pdf->MultiCell($w-25, $h, 'DUES', 1, 'R', 0, 1, $x, $y, true, 0, false, true, 0, 'M', true);
			//$pdf->MultiCell($w-40, $h, 'Rs. ', 1, 'R', 0, 1, $x+40, $y, true, 0, false, true, 0, 'M', true);
			//$y = $y + $y_field_increment + 0.65;
			//$pdf->MultiCell($w-25, $h, 'TOTAL FEE', 1, 'R', 0, 1, $x, $y, true, 0, false, true, 0, 'M', true);
			//$pdf->MultiCell($w-40, $h, 'Rs. ', 1, 'R', 0, 1, $x+40, $y, true, 0, false, true, 0, 'M', true);
			$y = $y + $y_title_increment + 7;
			$pdf->MultiCell($w, $h+11, 'Amount (In words):', 0, 'L', 0, 2, $x, $y, true, 0, false, true, 11);
			$y = $y + $y_title_increment;
			$pdf->MultiCell($w, $h+11, 'THREE HUNDRED ONLY', 0, 'L', 0, 2, $x, $y, true, 0, false, true, 11);
			$y = $y + $y_title_increment + 5;
			$pdf->SetFont('arialb', 'B', 9);
			$pdf->SetTextColor(127);
			$pdf->MultiCell($w, $h, 'For Admission Office use only', 1, 'C', 0, 2, $x, $y, true, 0, false, true, 0);
			$y = $y + $y_title_increment - 2;
			$pdf->MultiCell($w, $h+24, '', 1, 'C', 0, 2, $x, $y, true, 0, false, true, 24);
			$y = $y + $y_title_increment + 18;
			$pdf->SetFont('arialb', '', 7);
			$pdf->MultiCell($w, $h, '(Signature & Stamp of Issuing Officer)', 1, 'C', 0, 2, $x, $y, true, 0, false, true, 0);
			$pdf->SetTextColor(0, 0, 0, 100);
			$x = $x + 72;
			$y = 25;
		}
		
		$pdf->lastPage();
		ob_end_clean();
		$pdf->Output('IDCard_Challan_Report_.pdf', 'I');
		exit;
	}
	public function feesDuesChallan(){
		
	}
	public function get_fees_statistics_data(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$program_type_id= isValidData($request->program_type_id);
		$session_id 	= isValidData($request->session_id);
		$part_id 	= isValidData($request->part_id);

		$this->legacy_db = $this->load->database('admission_db',true);
		
		$upto_date = $this->legacy_db->from('fee_ledger')->order_by('DATE','DESC')->get()->result_array();
		$upto_date = date_create($upto_date[0]['DATE']);
		$upto_date = date_format($upto_date, "d-m-Y");

		$this->load->library('Tcpdf_master');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle('Paid Challan Report '.$upto_date);
		$pdf->SetSubject('');
		$pdf->SetKeywords('');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetAutoPageBreak(FALSE);
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}		
		$logo = K_PATH_IMAGES.'avatar-1.jpg';
		$phone = K_PATH_IMAGES.'phone.jpg';
		$email = K_PATH_IMAGES.'email.jpg';
		$url = K_PATH_IMAGES.'url.jpg';
		$style = array('border' => false,'padding' => 'auto','fgcolor' => array(0,0,0),'bgcolor' => false,'position' => 'R','module_width' => 1,'module_height' => 1);
		$tDate=date("F j, Y");
		$pdf->AddPage('L');
		
		$pdf->SetFont('Times', 'B', 14);
		$pdf->MultiCell(300, 0, 'STATEMENT OF PAID ADMISSION FEES CHALLAN BATCH WISE BACHELOR AND MASTER DEGREE PROGRAMS', 0, 'C', 0, 1, 0, 15, true, 0, false, true);
		$pdf->MultiCell(300, 0, 'FOR ACADEMIC YEAR 2023 UPTO '.$upto_date, 0, 'C', 0, 1, 0, 23, true, 0, false, true);
		$boarder = 1;
		$w = 86;
		$h = 8;
		$x = 30;
		$y = 40;
		$pdf->SetFont('Times', 'B', 12);
		$pdf->MultiCell($w, $h, 'MERIT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$pdf->MultiCell($w, $h, 'SELF / SPECIAL SELF', $boarder, 'C', 0, 1, $x = $x + $w, $y, true, 0, false, true, $h, 'M');
		$pdf->MultiCell($w, $h, 'EVENING', $boarder, 'C', 0, 1, $x = $x + $w, $y, true, 0, false, true, $h, 'M');
		$w = 17;
		$x = 30;
		$y = $y + $h;
		$pdf->SetFont('arial', 'B', 8);
		$pdf->MultiCell($w, $h, 'ISSUED CHALLAN', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'CHALLAN AMOUNT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, 'PAID CHALLAN', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'PAID AMOUNT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, 'ISSUED CHALLAN', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'CHALLAN AMOUNT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, 'PAID CHALLAN', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'PAID AMOUNT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, 'ISSUED CHALLAN', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'CHALLAN AMOUNT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, 'PAID CHALLAN', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'PAID AMOUNT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$w = 25;
		$h = 16;
		$x = 5;
		$y = 56;
		$challans = array();
		$MERIT_TOTAL_CHALLAN = 0;
		$SELF_TOTAL_CHALLAN = 0;
		$EVENING_TOTAL_CHALLAN = 0;
		$MERIT_PAID_CHALLAN = 0;
		$SELF_PAID_CHALLAN = 0;
		$EVENING_PAID_CHALLAN = 0;
		$MERIT_TOTAL_AMOUNT = 0;
		$SELF_TOTAL_AMOUNT = 0;
		$EVENING_TOTAL_AMOUNT = 0;
		$MERIT_PAID_AMOUNT = 0;
		$SELF_PAID_AMOUNT = 0;
		$EVENING_PAID_AMOUNT = 0;
		$batches = $this->legacy_db->select('*')->from('sessions')->where_in('YEAR',[2021,2022])->order_by('YEAR','DESC')->get()->result_array();
		foreach ($batches as $key => $batch) {
			$pdf->SetFont('helvetica', 'B', 10);
			$pdf->MultiCell($w, $h, $batch['SESSION_CODE'], $boarder, 'R', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
			
			$this->legacy_db->select('se.YEAR, fct.FEE_TYPE_TITLE AS CATEGORY, COUNT(fc.CHALLAN_NO) AS TOTAL_CHALLAN, SUM(fc.PAYABLE_AMOUNT) AS TOTAL_AMOUNT');
			$this->legacy_db->from('fee_challan fc');
			$this->legacy_db->join('selection_list sl','fc.SELECTION_LIST_ID = sl.SELECTION_LIST_ID');
			$this->legacy_db->join('admission_session ads','sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID');
			$this->legacy_db->join('sessions se','ads.SESSION_ID = se.SESSION_ID');
			$this->legacy_db->join('category cat','cat.CATEGORY_ID = sl.CATEGORY_ID');
			$this->legacy_db->join('fee_category_type fct','fct.FEE_CATEGORY_TYPE_ID = cat.FEE_CATEGORY_TYPE_ID');
			$this->legacy_db->join('bank_account ba','fc.BANK_ACCOUNT_ID = ba.BANK_ACCOUNT_ID');
			$this->legacy_db->where(array('fc.CHALLAN_TYPE_ID' => 1, 'fc.ACTIVE' => 1, 'fc.PAYABLE_AMOUNT >' => 0, 'se.YEAR' => $batch['YEAR'], 'fc.CHALLAN_NO >=' => 212330000, 'fc.CHALLAN_NO <=' => 212379999));
			$this->legacy_db->group_by('ba.CMD_NO');
			$this->legacy_db->order_by('fct.FEE_CATEGORY_TYPE_ID');
			$issued_challan_2k23 = $this->legacy_db->get();
			$issued_challan_2k23 = $issued_challan_2k23->result_array();
			$MERIT_TOTAL_CHALLAN += $issued_challan_2k23[0]['TOTAL_CHALLAN'];
			$SELF_TOTAL_CHALLAN += $issued_challan_2k23[1]['TOTAL_CHALLAN'];
			$EVENING_TOTAL_CHALLAN += $issued_challan_2k23[2]['TOTAL_CHALLAN'];
			$MERIT_TOTAL_AMOUNT += $issued_challan_2k23[0]['TOTAL_AMOUNT'];
			$SELF_TOTAL_AMOUNT += $issued_challan_2k23[1]['TOTAL_AMOUNT'];
			$EVENING_TOTAL_AMOUNT += $issued_challan_2k23[2]['TOTAL_AMOUNT'];
			
			$w = 17;
			$x = 30;
			$pdf->SetFont('arial', 'B', 8);
			foreach ($issued_challan_2k23 as $key => $issued_challan) {
				$pdf->MultiCell($w, $h, $issued_challan['TOTAL_CHALLAN'], $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w + 9;
				$pdf->MultiCell($w, $h, 'Rs. '.round($issued_challan['TOTAL_AMOUNT']), $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w - 9;
				
				$x = $x + $w;
				$w = $w + 9;
				
				$x = $x + $w;
				$w = $w - 9;
				
			}

			$this->legacy_db->select('fct.FEE_TYPE_TITLE AS CATEGORY, COUNT(fl.CHALLAN_NO) AS PAID_CHALLAN, SUM(fl.PAID_AMOUNT) AS PAID_AMOUNT');
			$this->legacy_db->from('fee_ledger fl');
			$this->legacy_db->join('fee_challan fc','fl.CHALLAN_NO = fc.CHALLAN_NO');
			$this->legacy_db->join('selection_list sl','fc.SELECTION_LIST_ID = sl.SELECTION_LIST_ID');
			$this->legacy_db->join('admission_session ads','sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID');
			$this->legacy_db->join('sessions se','ads.SESSION_ID = se.SESSION_ID');
			$this->legacy_db->join('category cat','cat.CATEGORY_ID = sl.CATEGORY_ID');
			$this->legacy_db->join('fee_category_type fct','fct.FEE_CATEGORY_TYPE_ID = cat.FEE_CATEGORY_TYPE_ID');
			$this->legacy_db->join('bank_account ba','fc.BANK_ACCOUNT_ID = ba.BANK_ACCOUNT_ID');
			$this->legacy_db->where(array('fc.CHALLAN_TYPE_ID' => 1, 'fc.ACTIVE' => 1, 'fc.PAYABLE_AMOUNT >' => 0, 'se.YEAR' => $batch['YEAR'], 'fc.CHALLAN_NO >=' => 212330000, 'fc.CHALLAN_NO <=' => 212379999));
			$this->legacy_db->group_by('ba.CMD_NO');
			$this->legacy_db->order_by('fct.FEE_CATEGORY_TYPE_ID');
			$paid_challan_2k23 = $this->legacy_db->get();
			$paid_challan_2k23 = $paid_challan_2k23->result_array();
			$MERIT_PAID_CHALLAN += $paid_challan_2k23[0]['PAID_CHALLAN'];
			$SELF_PAID_CHALLAN += $paid_challan_2k23[1]['PAID_CHALLAN'];
			$EVENING_PAID_CHALLAN += $paid_challan_2k23[2]['PAID_CHALLAN'];
			$MERIT_PAID_AMOUNT += $paid_challan_2k23[0]['PAID_AMOUNT'];
			$SELF_PAID_AMOUNT += $paid_challan_2k23[1]['PAID_AMOUNT'];
			$EVENING_PAID_AMOUNT += $paid_challan_2k23[2]['PAID_AMOUNT'];
			$w = 17;
			$x = 73;
			foreach ($paid_challan_2k23 as $key => $paid_challan) {
				$pdf->MultiCell($w, $h, $paid_challan['PAID_CHALLAN'], $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w + 9;
				$pdf->MultiCell($w, $h, 'Rs. '.round($paid_challan['PAID_AMOUNT']), $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w - 9;

				$x = $x + $w;
				$w = $w + 9;

				$x = $x + $w;
				$w = $w - 9;

			}
			$w = 25;
			$x = 5;
			$y = $y + $h;
		}
		// echo "<pre>";
		// print_r($MERIT_TOTAL_CHALLAN);
		// exit();
		$old_batches = $this->db->select('*')->from('admission_year')->where_in('year',[2019,2020])->order_by('year','DESC')->get()->result_array();
		foreach ($old_batches as $key => $batch) {
			$pdf->SetFont('helvetica', 'B', 10);
			$pdf->MultiCell($w, $h, $batch['remarks'], $boarder, 'R', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
			
			$this->db->select('ay.year,	ugcc.ACCOUNT_NO, ugcc.CATEGORY_NAME, COUNT(ugcc.CHALLAN_NO) AS TOTAL_CHALLAN, SUM(ugcc.TOTAL_AMOUNT) AS TOTAL_AMOUNT');
			$this->db->from('ug_candidate_challan ugcc');
			$this->db->join('admission_list_details ald','ugcc.CANDIDATE_ID = ald.candidate_id');
			$this->db->join('candidate ca','ald.candidate_id = ca.candidate_id');
			$this->db->join('admission_year ay','ca.admission_year_id = ay.admission_year_id');
			$this->db->where(array('ugcc.ACTIVE' => 1, 'ugcc.TOTAL_AMOUNT >' => 0, 'ay.year' => $batch['year'], 'ugcc.CHALLAN_NO >=' => 212330000, 'ugcc.CHALLAN_NO <=' => 212379999));
			$this->db->group_by('ugcc.ACCOUNT_NO');
			
			$issued_challan_2k20 = $this->db->get();
			$issued_challan_2k20 = $issued_challan_2k20->result_array();
			$MERIT_TOTAL_CHALLAN += $issued_challan_2k20[0]['TOTAL_CHALLAN'];
			$SELF_TOTAL_CHALLAN += $issued_challan_2k20[1]['TOTAL_CHALLAN'];
			$EVENING_TOTAL_CHALLAN += $issued_challan_2k20[2]['TOTAL_CHALLAN'];
			$MERIT_TOTAL_AMOUNT += $issued_challan_2k20[0]['TOTAL_AMOUNT'];
			$SELF_TOTAL_AMOUNT += $issued_challan_2k20[1]['TOTAL_AMOUNT'];
			$EVENING_TOTAL_AMOUNT += $issued_challan_2k20[2]['TOTAL_AMOUNT'];
			$w = 17;
			$x = 30;
			$pdf->SetFont('arial', 'B', 8);
			foreach ($issued_challan_2k20 as $key => $issued_challan) {
				$pdf->MultiCell($w, $h, $issued_challan['TOTAL_CHALLAN'], $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w + 9;
				$pdf->MultiCell($w, $h, 'Rs. '.$issued_challan['TOTAL_AMOUNT'], $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w - 9;

				$x = $x + $w;
				$w = $w + 9;

				$x = $x + $w;
				$w = $w - 9;
				
			}

			$this->db->select('ay.year, ugcc.ACCOUNT_NO, COUNT(ugcc.CHALLAN_NO) AS PAID_CHALLAN, SUM(pr.amount) AS PAID_AMOUNT, SUM(ugcc.TOTAL_AMOUNT) AS PAID_AMOUNT_FEE');
			$this->db->from('part_registry pr');
			$this->db->join('ug_candidate_challan ugcc','pr.challan_no = ugcc.CHALLAN_NO');
			$this->db->join('admission_list_details ald','ugcc.CANDIDATE_ID = ald.candidate_id');
			$this->db->join('candidate ca','ald.candidate_id = ca.candidate_id');
			$this->db->join('admission_year ay','ca.admission_year_id = ay.admission_year_id');
			$this->db->where(array('pr.type' => 0, 'ugcc.ACTIVE' => 1, 'ugcc.TOTAL_AMOUNT >' => 0, 'ay.year' => $batch['year'], 'ugcc.CHALLAN_NO >=' => 212330000, 'ugcc.CHALLAN_NO <=' => 212379999));
			$this->db->group_by('ugcc.ACCOUNT_NO');
			
			$paid_challan_2k20 = $this->db->get();
			$paid_challan_2k20 = $paid_challan_2k20->result_array();
			$MERIT_PAID_CHALLAN += $paid_challan_2k20[0]['PAID_CHALLAN'];
			$SELF_PAID_CHALLAN += $paid_challan_2k20[1]['PAID_CHALLAN'];
			$EVENING_PAID_CHALLAN += $paid_challan_2k20[2]['PAID_CHALLAN'];
			$MERIT_PAID_AMOUNT += $paid_challan_2k20[0]['PAID_AMOUNT'];
			$SELF_PAID_AMOUNT += $paid_challan_2k20[1]['PAID_AMOUNT'];
			$EVENING_PAID_AMOUNT += $paid_challan_2k20[2]['PAID_AMOUNT'];
			$w = 17;
			$x = 73;
			foreach ($paid_challan_2k20 as $key => $paid_challan) {
				$pdf->MultiCell($w, $h, $paid_challan['PAID_CHALLAN'], $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w + 9;
				$pdf->MultiCell($w, $h, 'Rs. '.$paid_challan['PAID_AMOUNT'], $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
				$x = $x + $w;
				$w = $w - 9;

				$x = $x + $w;
				$w = $w + 9;

				$x = $x + $w;
				$w = $w - 9;
			}
			$w = 25;
			$x = 5;
			$y = $y + $h;
		}
		
		$pdf->SetFont('arial', 'B', 9);
		$w = 25;
		$h = 10;
		$x = 5;
		$y = 120;
		$pdf->MultiCell($w, $h, 'TOTAL', $boarder, 'R', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 8;
		$pdf->MultiCell($w, $h, $MERIT_TOTAL_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'Rs. '.$MERIT_TOTAL_AMOUNT, $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, $MERIT_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'Rs. '.$MERIT_PAID_AMOUNT, $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, $SELF_TOTAL_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'Rs. '.$SELF_TOTAL_AMOUNT, $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, $SELF_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'Rs. '.$SELF_PAID_AMOUNT, $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, $EVENING_TOTAL_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'Rs. '.$EVENING_TOTAL_AMOUNT, $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 9;
		$pdf->MultiCell($w, $h, $EVENING_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w + 9;
		$pdf->MultiCell($w, $h, 'Rs. '.$EVENING_PAID_AMOUNT, $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = 47;
		$y = 140;
		$w = $w + 10;
		$pdf->MultiCell($w, $h, 'TOTAL CHALLAN', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'TOTAL AMOUNT', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'PAID CHALLAN', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'PAID AMOUNT', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'REMAINING CHALLAN', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');

		$w = 42;
		$x = 5;
		$y = 150;
		$pdf->MultiCell($w, $h, 'GRAND TOTAL', $boarder, 'R', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = $w - 6;
		$pdf->MultiCell($w, $h, $MERIT_TOTAL_CHALLAN+$SELF_TOTAL_CHALLAN+$EVENING_TOTAL_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'Rs. '.($MERIT_TOTAL_AMOUNT+$SELF_TOTAL_AMOUNT+$EVENING_TOTAL_AMOUNT), $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, $MERIT_PAID_CHALLAN+$SELF_PAID_CHALLAN+$EVENING_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'Rs. '.($MERIT_PAID_AMOUNT+$SELF_PAID_AMOUNT+$EVENING_PAID_AMOUNT), $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, ($MERIT_TOTAL_CHALLAN+$SELF_TOTAL_CHALLAN+$EVENING_TOTAL_CHALLAN)-($MERIT_PAID_CHALLAN+$SELF_PAID_CHALLAN+$EVENING_PAID_CHALLAN), $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');

		$w = 38;
		$x = 47;
		$y = 170;
		$pdf->MultiCell($w, $h, 'MERIT', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'SELF / SPECIAL SELF', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, 'EVENING', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');

		$x = $x + $w;
		$pdf->SetFont('arial', 'B', 10);
		$pdf->MultiCell($w, $h, 'TOTAL', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->SetFont('arial', 'B', 12);
		$pdf->MultiCell($w, $h, 'GRAND TOTAL', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = 45;
		$pdf->SetFont('arial', 'B', 10);
		$pdf->MultiCell($w, $h, 'REGULAR EXAM FORMS', $boarder, 'C', 0, 1, $x, $y, true, 0, false, true, $h, 'M');
		$w = 42;
		$x = 5;
		$y = $y + $h;
		$pdf->SetFont('arial', 'B', 9);
		$pdf->MultiCell($w, $h, '2K23 BATCH', $boarder, 'R', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = 38;
		$pdf->MultiCell($w, $h, '8010', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, '1147', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, '988', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');

		$x = $x + $w;
		$pdf->SetFont('arial', 'B', 12);
		$pdf->MultiCell($w, $h, 8010+1147+988, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');

		$w = 42;
		$x = 5;
		$y = $y + $h;
		$pdf->SetFont('arial', 'B', 9);
		$pdf->MultiCell($w, $h, 'NEXT HIGHER CLASSES', $boarder, 'R', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = 38;
		$pdf->MultiCell($w, $h, $MERIT_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, $SELF_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$pdf->MultiCell($w, $h, $EVENING_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');

		$x = $x + $w;
		$pdf->SetFont('arial', 'B', 12);
		$pdf->MultiCell($w, $h, $MERIT_PAID_CHALLAN+$SELF_PAID_CHALLAN+$EVENING_PAID_CHALLAN, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;

		$y = $y - $h;
		$h = 20;
		$pdf->SetFont('arial', 'B', 12);
		$pdf->MultiCell($w, $h, $MERIT_PAID_CHALLAN+$SELF_PAID_CHALLAN+$EVENING_PAID_CHALLAN+8010+1147+988, $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');
		$x = $x + $w;
		$w = 45;
		$pdf->MultiCell($w, $h, '28863', $boarder, 'C', 0, 0, $x, $y, true, 0, false, true, $h, 'M');

		$pdf->lastPage();
		ob_end_clean();
		$pdf->Output('Paid_Challan_Report_'.$upto_date.'.pdf', 'I');
		exit;
	}
	public function candidateImport(){
		$session_id = $this->input->post('session_id');
		$shift_id = $this->input->post('shift_id');
		$user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $ADMIN_USER_ID = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];

		$this->db->select('
			cm.SESSION_ID, 
			cm.ADMISSION_SESSION_ID, 
			cm.USER_ID, 
			cm.APPLICATION_ID, 
			cm.CANDIDATE_ID, 
			ca.seat_no, 
			ca.percentage, 
			pos.name AS POS_NAME, 
			ald.roll AS ROLE_NO, 
			cposg.code, 
			cpos.shift_id, 
			ct.category_id, 
			ct.name AS CATEGORY_NAME, 
			ald.roll_no as ROLL_NO_CODE, 
			cpos.campus_id, 
			ca.program_type_id, 
			cpos.campus_program_of_study_id, 
			c.location, ca.test_score, 
			capos.choice_no
		');
		$this->db->from('candidate_mapping cm');
		$this->db->join('candidate ca','cm.candidate_id = ca.CANDIDATE_ID');
		$this->db->join('admission_list_details ald','ca.candidate_id = ald.candidate_id');
		$this->db->join('cpos_group cposg','ald.cpos_group_id = cposg.cpos_group_id');
		$this->db->join('campus_program_of_study cpos','cposg.campus_program_of_study_id = cpos.campus_program_of_study_id');
		$this->db->join('campus c','cpos.campus_id = c.campus_id');
		$this->db->join('program_of_study pos','cpos.program_of_study_id = pos.program_of_study_id');
		$this->db->join('campus_category cc','ald.campus_category_id = cc.campus_category_id');
		$this->db->join('category ct','cc.category_id = ct.category_id');
		$this->db->join('candidate_program_of_study capos','ca.candidate_id = capos.candidate_id AND cpos.campus_program_of_study_id = capos.campus_program_of_study_id');
		$this->db->where(array('cm.SESSION_ID' => 3, 'ald.active' => 1));
		//$this->db->limit(3000,0);
		$candidates = $this->db->get()->result_array();
		$status = 'OK';
		$flag = false;
		$candidate_record = array();
		$limit = 0;
		foreach ($candidates as &$candidate) {
			$SESSION_ID = $candidate['SESSION_ID'];
			$ADMISSION_SESSION_ID = $candidate['ADMISSION_SESSION_ID'];
			$USER_ID = $candidate['USER_ID'];
			$APPLICATION_ID = $candidate['APPLICATION_ID'];
			$CANDIDATE_ID = $candidate['CANDIDATE_ID'];
			$SEAT_NO = $candidate['seat_no'];
			$CPN = $candidate['percentage'];
			$TEST_SCORE = $candidate['test_score'];
			$PROGRAM_TYPE_ID = $candidate['program_type_id'];
			$SHIFT_ID = $candidate['shift_id'];
			$CHOICE_NO = $candidate['choice_no'];
			$ROLL_NO_CODE = $candidate['ROLL_NO_CODE'];
			$ROLL_NO = $candidate['ROLE_NO'];

			if($SHIFT_ID == 2){
				$FORM_CATEGORY_ID = 7;
				$CATEGORY_ID = 29;
			} elseif($SHIFT_ID == 1){
				$CATEGORY_ID = $candidate['category_id'];
				if ($CATEGORY_ID == 1 || $CATEGORY_ID == 3 || $CATEGORY_ID == 4 || $CATEGORY_ID == 5 || $CATEGORY_ID == 12 || $CATEGORY_ID == 14 || $CATEGORY_ID == 1 || $CATEGORY_ID == 16 || $CATEGORY_ID == 18 || $CATEGORY_ID == 22 || $CATEGORY_ID == 23 || $CATEGORY_ID == 25 || $CATEGORY_ID == 26 || $CATEGORY_ID == 27 || $CATEGORY_ID == 28) $FORM_CATEGORY_ID = 1;
				if ($CATEGORY_ID == 13 || $CATEGORY_ID == 21) $FORM_CATEGORY_ID = 2;
				if ($CATEGORY_ID == 7 || $CATEGORY_ID == 11) $FORM_CATEGORY_ID = 3;
				if ($CATEGORY_ID == 9) $FORM_CATEGORY_ID = 4;
				if ($CATEGORY_ID == 6) $FORM_CATEGORY_ID = 5;
				if ($CATEGORY_ID == 2) $FORM_CATEGORY_ID = 6;
				if ($CATEGORY_ID == 24) $FORM_CATEGORY_ID = 8;
			}			

			$ADMISSION_LIST = $this->legacy_db->get_where('admission_list', array('ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID));
			$ADMISSION_LIST_ID = $ADMISSION_LIST->row()->ADMISSION_LIST_ID;

			$CAMPUS = $this->legacy_db->get_where('campus', array('LOCATION' => $candidate['location']));
			$CAMPUS_ID = $CAMPUS->row()->CAMPUS_ID;

			$PROG_LIST = $this->legacy_db->get_where('shift_program_mapping', array('PROG_CODE' => $candidate['code']));
			$PROG_LIST_ID = $PROG_LIST->row()->PROG_LIST_ID;			
			
			// Add/Update record in admit_card table
			$ADMIT_CARD_WHERE = array('CARD_ID' => $SEAT_NO, 'SESSION_ID' => $SESSION_ID,'APPLICATION_ID' => $APPLICATION_ID);
			$ADMIT_CARD = array('CARD_ID' => $SEAT_NO, 'SESSION_ID' => $SESSION_ID, 'APPLICATION_ID' => $APPLICATION_ID, 'IS_DISPATCHED' => "N", 'REMARKS' => "OLD STUDENT", 'ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID, 'TEST_DATETIME' => '2023-07-07', 'PROGRAM_TYPE_ID' => $PROGRAM_TYPE_ID);
			$ADMIT_CARD = $this->StudentReports_model->addUpdateTable('admit_card',$ADMIT_CARD_WHERE,$ADMIT_CARD);
			
			// Add/Update record in test_result table
			$TEST_RESULT_WHERE = array('CARD_ID' => $SEAT_NO, 'USER_ID' => $USER_ID,'APPLICATION_ID' => $APPLICATION_ID);
			$TEST_RESULT = array('CARD_ID' => $SEAT_NO, 'TEST_ID' => 7, 'TEST_SCORE' => $TEST_SCORE, 'USER_ID' => $USER_ID, 'APPLICATION_ID' => $APPLICATION_ID, 'ACTIVE' => 1, 'REMARKS' => "OLD STUDENT", 'DETAIL_CPN' => $CPN, 'CPN' => $CPN);
			$TEST_RESULT = $this->StudentReports_model->addUpdateTable('test_result',$TEST_RESULT_WHERE,$TEST_RESULT);
			
			// Add/Update record in application_category table
			$APPLICATION_CATEGORY_WHERE = array('USER_ID' => $USER_ID,'APPLICATION_ID' => $APPLICATION_ID);
			$APPLICATION_CATEGORY = array('USER_ID' => $USER_ID, 'APPLICATION_ID' => $APPLICATION_ID, 'FORM_CATEGORY_ID' => $FORM_CATEGORY_ID, 'CATEGORY_INFO' => "OLD STUDENT", 'REMARKS' => "OLD STUDENT", 'IS_ENABLE' => "Y");
			$APPLICATION_CATEGORY = $this->StudentReports_model->addUpdateTable('application_category',$APPLICATION_CATEGORY_WHERE,$APPLICATION_CATEGORY);

			// Add/Update record in application_choices table
			$APPLICATION_CHOICES_WHERE = array('USER_ID' => $USER_ID,'APPLICATION_ID' => $APPLICATION_ID);
			$APPLICATION_CHOICES = array('USER_ID' => $USER_ID, 'APPLICATION_ID' => $APPLICATION_ID, 'PROG_LIST_ID' => $PROG_LIST_ID, 'SHIFT_ID' => $SHIFT_ID, 'CHOICE_NO' => $CHOICE_NO, 'IS_RECOMMENDED' => "Y", 'REMARKS' => "OLD STUDENT", 'IS_SPECIAL_CHOICE' => "N");
			$APPLICATION_CHOICES = $this->StudentReports_model->addUpdateTable('application_choices',$APPLICATION_CHOICES_WHERE,$APPLICATION_CHOICES);

			// Add/Update record in applied_shift table
			$APPLIED_SHIFT_WHERE = array('USER_ID' => $USER_ID,'APPLICATION_ID' => $APPLICATION_ID);
			$APPLIED_SHIFT = array('APPLICATION_ID' => $APPLICATION_ID, 'SHIFT_ID' => $SHIFT_ID, 'USER_ID' => $USER_ID, 'DATETIME' => '2023-07-07', 'REMARKS' => "OLD STUDENT");
			$APPLIED_SHIFT = $this->StudentReports_model->addUpdateTable('applied_shift',$APPLIED_SHIFT_WHERE,$APPLIED_SHIFT);

			// Add/Update record in selection_list table
			$SELECTION_LIST_WHERE = array('APPLICATION_ID' => $APPLICATION_ID);
			$SELECTION_LIST = array('SHIFT_ID' => $SHIFT_ID, 'ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID, 'APPLICATION_ID' => $APPLICATION_ID, 'PROG_LIST_ID' => $PROG_LIST_ID, 'CATEGORY_ID' => $CATEGORY_ID, 'CHOICE_NO' => $CHOICE_NO, 'ACTIVE' => 1, 'TEST_ID' => 7, 'CARD_ID' => $SEAT_NO, 'CPN' => $CPN, 'REMARKS' => "OLD STUDENT", 'IS_PROVISIONAL' => "N", 'ADMISSION_LIST_ID' => $ADMISSION_LIST_ID, 'ROLL_NO_CODE' => $ROLL_NO_CODE, 'ROLL_NO' => $ROLL_NO, 'IS_ENROLLED' => "Y"); 
			$SELECTION_LIST = $this->StudentReports_model->addUpdateTable('selection_list',$SELECTION_LIST_WHERE,$SELECTION_LIST);
			
			// Add/Update record in candidate_account table
			$CANDIDATE_ACCOUNT_WHERE = array('APPLICATION_ID' => $APPLICATION_ID);
			$CANDIDATE_ACCOUNT = array('APPLICATION_ID' => $APPLICATION_ID, 'FIRST_NAME' => "OLD STUDENT", 'FNAME' => "OLD STUDENT", 'LAST_NAME' => "OLD STUDENT", 'DATE' => NOW(), 'ACTIVE' => 1, 'REMARKS' => "OLD STUDENT", 'CANDIDATE_ID' => $CANDIDATE_ID, 'USER_ID' => $USER_ID); 
			$CANDIDATE_ACCOUNT = $this->StudentReports_model->addUpdateTable('candidate_account',$CANDIDATE_ACCOUNT_WHERE,$CANDIDATE_ACCOUNT);
			
			$records = array();
			$records['ADMIT_CARD'] = $ADMIT_CARD;
			$records['TEST_RESULT'] = $TEST_RESULT;
			$records['APPLICATION_CATEGORY'] = $APPLICATION_CATEGORY;
			$records['APPLICATION_CHOICES'] = $APPLICATION_CHOICES;
			$records['APPLIED_SHIFT'] = $APPLIED_SHIFT;
			$records['SELECTION_LIST'] = $SELECTION_LIST;
			$records['CANDIDATE_ACCOUNT'] = $CANDIDATE_ACCOUNT;

			$candidate_record[] = $records;
			$limit++;
			if($limit >= 100) break;
		}
		
		http_response_code(200);
		echo (json_encode($candidate_record));
		$this->output->set_content_type('application/json')->set_output(json_encode($candidate_record));
		unset($candidate);
		exit;
	}

	public function challanImport(){
		$user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $ADMIN_USER_ID = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];
		$challan_records = array();
		$this->legacy_db->select('cm.CANDIDATE_ID, cm.APPLICATION_ID, sl.SELECTION_LIST_ID, ads.CAMPUS_ID, sl.SHIFT_ID, sl.PROG_LIST_ID, ads.PROGRAM_TYPE_ID')->from('candidate_mapping cm')->join('selection_list sl','cm.APPLICATION_ID = sl.APPLICATION_ID')->join('admission_session ads','sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID')->where('cm.SESSION_ID', 3)->limit(100);
		$candidates = $this->legacy_db->get()->result_array();
		foreach ($candidates as $key => $candidate) {
			$challans = $this->online_db->get_where('ug_candidate_challan', array('CANDIDATE_ID' => $candidate['CANDIDATE_ID']));
			$challans = $challans->result_array();
			foreach ($challans as $key => $challan) {
				$BANK_ACCOUNT = $this->legacy_db->get_where('bank_account', array('ACCOUNT_NO' => $challan['ACCOUNT_NO'], 'ACCOUNT_TITLE' => $challan['CATEGORY_NAME']));
				$BANK_ACCOUNT = $BANK_ACCOUNT->row()->BANK_ACCOUNT_ID;

				if($challan['PART_ID'] == 1) $PART_ID = 6;
				if($challan['PART_ID'] == 2) $PART_ID = 7;
				if($challan['PART_ID'] == 3) $PART_ID = 1;
				if($challan['PART_ID'] == 4) $PART_ID = 8;
				if($challan['PART_ID'] == 5) $PART_ID = 9;
				if($challan['PART_ID'] == 6) $PART_ID = 10;
				if($challan['PART_ID'] == 7) $PART_ID = 11;
				if($challan['PART_ID'] == 8) $PART_ID = 2;
				if($challan['PART_ID'] == 9) $PART_ID = 3;
				if($challan['PART_ID'] == 10) $PART_ID = 4;
				if($challan['PART_ID'] == 11) $PART_ID = 5;

				$FEE_DEMERIT = $this->legacy_db->get_where('fee_program_list', array('CAMPUS_ID' => $candidate['CAMPUS_ID'],'PROGRAM_TYPE_ID' => $candidate['PROGRAM_TYPE_ID'], 'SHIFT_ID' => $candidate['SHIFT_ID'], 'PROG_LIST_ID' => $candidate['PROG_LIST_ID'], 'PART_ID' => $PART_ID));
				$FEE_DEMERIT = $FEE_DEMERIT->row();
				$FEE_DEMERIT_ID = $FEE_DEMERIT->FEE_DEMERIT_ID;
				
				if($FEE_DEMERIT_ID == 1){
					$SEMESTER_ID = 11;			
				} elseif($FEE_DEMERIT_ID == 2){
					$SEMESTER_ID = 1;
					if(strstr($challan['FEE_LABLE'], "SECOND")) $SEMESTER_ID = 2;
					if(strstr($challan['FEE_LABLE'], "THIRD")) $SEMESTER_ID = 3;
					if(strstr($challan['FEE_LABLE'], "FOURTH")) $SEMESTER_ID = 4;
					if(strstr($challan['FEE_LABLE'], "FIFTH")) $SEMESTER_ID = 5;
					if(strstr($challan['FEE_LABLE'], "SIXTH")) $SEMESTER_ID = 6;
					if(strstr($challan['FEE_LABLE'], "SEVENTH")) $SEMESTER_ID = 7;
					if(strstr($challan['FEE_LABLE'], "EIGHTH")) $SEMESTER_ID = 8;
					if(strstr($challan['FEE_LABLE'], "NINTH")) $SEMESTER_ID = 9;
					if(strstr($challan['FEE_LABLE'], "TENTH")) $SEMESTER_ID = 10;
				}
				
				$FEE_PROG_LIST = $this->legacy_db->get_where('fee_program_list', array('CAMPUS_ID' => $candidate['CAMPUS_ID'],'PROGRAM_TYPE_ID' => $candidate['PROGRAM_TYPE_ID'], 'SHIFT_ID' => $candidate['SHIFT_ID'], 'PROG_LIST_ID' => $candidate['PROG_LIST_ID'], 'PART_ID' => $PART_ID, 'SEMESTER_ID' => $SEMESTER_ID));
				$FEE_PROG_LIST = $FEE_PROG_LIST->row();
				$FEE_PROG_LIST_ID = $FEE_PROG_LIST->FEE_PROG_LIST_ID;

				$CHALLAN = array();
				$CHALLAN['CHALLAN_NO'] = $challan['CHALLAN_NO'];
				$CHALLAN['APPLICATION_ID'] = $candidate['APPLICATION_ID'];
				$CHALLAN['CHALLAN_TYPE_ID'] = 1;
				$CHALLAN['BANK_ACCOUNT_ID'] = $BANK_ACCOUNT;
				$CHALLAN['SELECTION_LIST_ID'] = $candidate['SELECTION_LIST_ID'];
				$CHALLAN['CHALLAN_AMOUNT'] = $challan['FEE_AMOUNT'];
				$CHALLAN['INSTALLMENT_AMOUNT'] = $challan['FEE_AMOUNT'];
				$CHALLAN['DUES'] = $challan['DUES'];
				$CHALLAN['LATE_FEE'] = $challan['LATE_FEE'];
				$CHALLAN['PAYABLE_AMOUNT'] = $challan['TOTAL_AMOUNT'];
				$CHALLAN['VALID_UPTO'] = $challan['VALID_UPTO'];
				$CHALLAN['DATETIME'] = $challan['VALID_UPTO'];
				$CHALLAN['REMARKS'] = $challan['FEE_LABLE'];
				$CHALLAN['ADMIN_USER_ID'] = $ADMIN_USER_ID;
				$CHALLAN['PART_ID'] = $PART_ID;
				$CHALLAN['SEMESTER_ID'] = $SEMESTER_ID;
				$CHALLAN['FEE_PROG_LIST_ID'] = $FEE_PROG_LIST_ID;
				$CHALLAN['ACTIVE'] = $challan['ACTIVE'];
				
				$challan_records[] = $CHALLAN;
			}
		}
		echo (json_encode($challan_records));
		exit();
			
			// $APPLICATION_ID = intval($candidate['APPLICATION_ID']);
			// $CANDIDATE_ID = intval($candidate['CANDIDATE_ID']);
			// $CAMPUS_ID = $candidate['CAMPUS_ID'];
			// $PROGRAM_TYPE_ID = $candidate['program_type_id'];
			// $SHIFT_ID = $candidate['shift_id'];
			// $PROG_LIST_ID = intval($candidate['PROG_LIST_ID']);
			// $SELECTION_LIST_ID = intval($candidate['SELECTION_LIST_ID']);
			// $ACCOUNT_ID = intval($candidate['ACCOUNT_ID']);
			
			// $this->legacy_db->select('ct.FEE_CATEGORY_TYPE_ID')->from('selection_list sl')->join('category ct','sl.CATEGORY_ID = ct.CATEGORY_ID')->where('sl.SELECTION_LIST_ID',$SELECTION_LIST_ID);
			// $FEE_CATEGORY_TYPE_ID = $this->legacy_db->get()->row()->FEE_CATEGORY_TYPE_ID;
			
		// if($FEE_CATEGORY_TYPE_ID >= 4 && $FEE_CATEGORY_TYPE_ID <= 8){
		// 	$IS_MERIT = "N";
		// } else {
		// 	$IS_MERIT = "Y";
		// }


		// 	$CHALLAN_NO = $challan['CHALLAN_NO'];
		// 	$FEE_AMOUNT = $challan['FEE_AMOUNT'];
		// 	$DUES = $challan['DUES'];
		// 	$LATE_FEE = $challan['LATE_FEE'];
		// 	$TOTAL_AMOUNT = $challan['TOTAL_AMOUNT'];
		// 	$PAYABLE_AMOUNT = $TOTAL_AMOUNT + $LATE_FEE;
		// 	$PAID_AMOUNT = $challan['amount'];
		// 	$PAID_DATE = $challan['challan_date'];
		// 	$VALID_UPTO = $challan['VALID_UPTO'];
		// 	$CATEGORY_NAME = $challan['CATEGORY_NAME'];
		// 	$ACCOUNT_NO = $challan['ACCOUNT_NO'];
		// 	$OLD_PART_ID = $challan['part_id'];
		// 	$REMARKS = $challan['FEE_LABLE'];
		// 	$ACTIVE = $challan['ACTIVE'];
		// 	$OLD_CHALLAN_TYPE_ID = $challan['type'];

		// 	if($OLD_CHALLAN_TYPE_ID == 0) $CHALLAN_TYPE_ID = 1;
		// 	if($OLD_CHALLAN_TYPE_ID == 1) $CHALLAN_TYPE_ID = 2;
		// 	if($OLD_CHALLAN_TYPE_ID == 2) $CHALLAN_TYPE_ID = 3;

		// 	if($OLD_PART_ID == 1) $PART_ID = 6;
		// 	if($OLD_PART_ID == 2) $PART_ID = 7;
		// 	if($OLD_PART_ID == 3) $PART_ID = 1;
		// 	if($OLD_PART_ID == 4) $PART_ID = 8;
		// 	if($OLD_PART_ID == 5) $PART_ID = 9;
		// 	if($OLD_PART_ID == 6) $PART_ID = 10;
		// 	if($OLD_PART_ID == 7) $PART_ID = 11;
		// 	if($OLD_PART_ID == 8) $PART_ID = 2;
		// 	if($OLD_PART_ID == 9) $PART_ID = 3;
		// 	if($OLD_PART_ID == 10) $PART_ID = 4;
		// 	if($OLD_PART_ID == 11) $PART_ID = 5;

		// 	$BANK_ACCOUNT = $this->legacy_db->get_where('bank_account', array('ACCOUNT_NO' => $ACCOUNT_NO, 'ACCOUNT_TITLE' => $CATEGORY_NAME));
		// 	$BANK_ACCOUNT = $BANK_ACCOUNT->row();
		// 	$BANK_ACCOUNT_ID = $BANK_ACCOUNT->BANK_ACCOUNT_ID;

		// 	$FEE_PROG_LIST = $this->legacy_db->get_where('fee_program_list', array('CAMPUS_ID' => $CAMPUS_ID,'PROGRAM_TYPE_ID' => $PROGRAM_TYPE_ID, 'SHIFT_ID' => $SHIFT_ID, 'PROG_LIST_ID' => $PROG_LIST_ID, 'PART_ID' => $PART_ID));
		// 	$FEE_PROG_LIST = $FEE_PROG_LIST->row();
		// 	$FEE_DEMERIT_ID = $FEE_PROG_LIST->FEE_DEMERIT_ID;

		// 	if($FEE_DEMERIT_ID == 1){
		// 		$SEMESTER_ID = 11;			
		// 	} elseif($FEE_DEMERIT_ID == 2){
		// 		$SEMESTER_ID = 1;
		// 		if(strstr($REMARKS, "SECOND")) $SEMESTER_ID = 2;
		// 		if(strstr($REMARKS, "THIRD")) $SEMESTER_ID = 3;
		// 		if(strstr($REMARKS, "FOURTH")) $SEMESTER_ID = 4;
		// 		if(strstr($REMARKS, "FIFTH")) $SEMESTER_ID = 5;
		// 		if(strstr($REMARKS, "SIXTH")) $SEMESTER_ID = 6;
		// 		if(strstr($REMARKS, "SEVENTH")) $SEMESTER_ID = 7;
		// 		if(strstr($REMARKS, "EIGHTH")) $SEMESTER_ID = 8;
		// 		if(strstr($REMARKS, "NINTH")) $SEMESTER_ID = 9;
		// 		if(strstr($REMARKS, "TENTH")) $SEMESTER_ID = 10;
		// 	}

		// 	$FEE_PROG_LIST = $this->legacy_db->get_where('fee_program_list', array('CAMPUS_ID' => $CAMPUS_ID,'PROGRAM_TYPE_ID' => $PROGRAM_TYPE_ID, 'SHIFT_ID' => $SHIFT_ID, 'PROG_LIST_ID' => $PROG_LIST_ID, 'PART_ID' => $PART_ID, 'SEMESTER_ID' => $SEMESTER_ID));
		// 	$FEE_PROG_LIST = $FEE_PROG_LIST->row();
		// 	$FEE_PROG_LIST_ID = $FEE_PROG_LIST->FEE_PROG_LIST_ID;

		// 	$check_fee_challan = $this->legacy_db->get_where('fee_challan', array( 'CHALLAN_NO' => $CHALLAN_NO));
		// 	$check_fee_challan = $check_fee_challan->row();
		// 	if($check_fee_challan === null){
		// 		$FEE_CHALLAN = array('CHALLAN_NO' => $CHALLAN_NO, 'APPLICATION_ID' => $APPLICATION_ID, 'CHALLAN_TYPE_ID' => $CHALLAN_TYPE_ID, 'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID, 'SELECTION_LIST_ID' => $SELECTION_LIST_ID, 'CHALLAN_AMOUNT' => $TOTAL_AMOUNT, 'INSTALLMENT_AMOUNT' => $TOTAL_AMOUNT, 'DUES' => $DUES, 'LATE_FEE' => $LATE_FEE, 'PAYABLE_AMOUNT' => $PAYABLE_AMOUNT, 'VALID_UPTO' => $VALID_UPTO, 'DATETIME' => $VALID_UPTO, 'REMARKS' => $REMARKS, 'ADMIN_USER_ID' => $ADMIN_USER_ID, 'PART_ID' => $PART_ID, 'SEMESTER_ID' => $SEMESTER_ID, 'FEE_PROG_LIST_ID' => $FEE_PROG_LIST_ID, 'ACTIVE' => $ACTIVE);
		// 		$this->legacy_db->insert('fee_challan',$FEE_CHALLAN);
		// 		$challan_records[] = $FEE_CHALLAN;
		// 		//$challan_records[] = $PART_ID;
		// 		//$challan_records[] = $SEMESTER_ID;
		// 	} else {
		// 		$challan_records[] = "Fee Challan Record already exists.";
		// 	}

		// 	$check_fee_ledger = $this->legacy_db->get_where('fee_ledger', array( 'CHALLAN_NO' => $CHALLAN_NO));
		// 	$check_fee_ledger = $check_fee_ledger->row();
		// 	if($check_fee_ledger === null) {
		// 		$FEE_LEDGER = array('ACCOUNT_ID' => $ACCOUNT_ID, 'CHALLAN_TYPE_ID' => $CHALLAN_TYPE_ID, 'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID, 'CHALLAN_NO' => $CHALLAN_NO, 'CHALLAN_AMOUNT' => $TOTAL_AMOUNT, 'PAYABLE_AMOUNT' => $PAYABLE_AMOUNT, 'PAID_AMOUNT' => $PAID_AMOUNT, 'DETAILS' => $REMARKS, 'DATE' => $PAID_DATE, 'REMARKS' => "OLD STUDENT", 'FEE_PROG_LIST_ID' => $FEE_PROG_LIST_ID, 'IS_YES' => "Y", 'IS_MERIT' => $IS_MERIT, 'SELECTION_LIST_ID' => $SELECTION_LIST_ID);
		// 		$this->legacy_db->insert('fee_ledger',$FEE_LEDGER);
		// 		$challan_records[] = $FEE_LEDGER;
		// 	} else {
		// 		$challan_records[] = "Fee Ledger Record already exists.";
		// 	}
			
	}

	public function paidChallanImport(){
		$user = $this->session->userdata($this->SessionName);
        $user_role = $this->session->userdata($this->user_role);
        $ADMIN_USER_ID = $user['USER_ID'];
        $role_id = $user_role['ROLE_ID'];
		$this->db->select('pr.*, cm.*');
		$this->db->from('part_registry pr');
		$this->db->join('accounts ac','pr.account_id = ac.account_id');
		$this->db->join('candidate ca','ac.candidate_id = ca.candidate_id');
		$this->db->join('candidate_mapping cm','ca.candidate_id = cm.CANDIDATE_ID');
		$this->db->where(array('pr.amount >' => 0,'pr.challan_no >' => 2000000));
		$this->db->limit(20000);
		$paid_challans = $this->db->get()->result_array();
		$paid_challan_records = array();
		foreach ($paid_challans as $key => $paid_challan) {
			$candidate_challan = $this->online_db->get_where('ug_candidate_challan',array('CHALLAN_NO' => $paid_challan['challan_no']));
			if($candidate_challan->num_rows() > 0){

				$paid_challan_records[] = $candidate_challan->row();
			} else {

			}			
		}
		echo (json_encode($paid_challan_records));
		exit();
	}

	public function part_registry(){
		$challans = $this->db->get_where('part_registry', array('challan_date' => '2023-04-05', 'type' => 0, 'amount >' => 0));
		$records = array();
		foreach($challans->result_array() as $challan){
			$query = $this->online_db->get_where('ug_part_registry', array('PART_REGISTRY_ID' => $challan['part_registry_id']));
			if($query->num_rows() > 0){
				$records[] = "Record exsist";
			} else {
				$records[] = "Record not exsist";
			}
		}
		echo (json_encode($records));
	}

}