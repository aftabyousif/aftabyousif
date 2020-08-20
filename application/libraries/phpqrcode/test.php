<?php    
    
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
  

  
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "qrlib.php";    

		
		//$filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png("ID 12345", "TEMP/12345.png", 'L', 4, 2);    
      
  
    