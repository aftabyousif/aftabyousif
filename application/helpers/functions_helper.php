<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 12:17 PM
 */

function getcsrf($obj){
    $reponse = array(
        'csrfName' => $obj->security->get_csrf_token_name(),
        'csrfHash' => $obj->security->get_csrf_hash()
    );
    return $reponse;
}
function passwordRule($password){
    $pattern = "/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,50}/";

    if(preg_match($pattern, $password)){
        $special_char = "!@#$%^&*()+=-[]';,./{}|:<>?~";
        if (false === strpbrk($password, $special_char))
            return false;
        else
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

    $path = "../log/ITSC_QUERY";
    if(!file_exists($path)){
        $result = mkdir ($path);
        chmod("$path", 0755);
    }
    $path.="/ITSC_QUERY.txt";
    $date_time =date('Y F d l h:i A');
    //printDateTime($date)


    $data ="[$date_time] $text\n";
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

    $data = htmlspecialchars(addslashes(trim($data)), ENT_QUOTES, 'UTF-8');;
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
    $from = 'itsc@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='Your Verification Code';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum,<br>".
        "         
                      <br><br><b> Your verification code is: $token </b><br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: itsc@usindh.edu.pk<br>
                      E-portal url: <a href='http://eportal.usindh.edu.pk/'>http://eportal.usindh.edu.pk</a>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: itsc@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <itsc@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
}
function sendPasswordByEmail($email,$token){
    $from = 'itsc@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='Your New Password';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br> Assalam Alaikum,<br>".
        "         
                      <br><br><b> Your new Password is: $token </b><br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: itsc@usindh.edu.pk<br>
                      E-portal url: <a href='http://eportal.usindh.edu.pk/'>http://eportal.usindh.edu.pk</a>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: itsc@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <itsc@usindh.edu.pk>\r\n";

    if(mail($email,$subject,$body,$headers)){
        $m= 'An email has been sent with password.';

    }else{
        $m= 'Some unknown system error occurd - Sorry! your password reset request can not be processed this time - Please try again later...';
    }
}
function sendPasswordTokenByEmail($email,$token,$user_id){


    $token= urlencode(EncryptThis($token));
    $user_id= urlencode(EncryptThis($user_id));
    $from = 'itsc@usindh.edu.pk';
    $from_name ='IT Services Support Team';
    $subject ='PASSWORD RESET LINK';
    $body = "<img src='https://usindh.edu.pk/wp-content/uploads/2018/10/2logo-usindh.png'> <br>  Dear Sir/Madam,<br>
We have recieved password reset request for your account of LMS/E-Portal. Please visit the following link to reset your password:<br>".
        "         
                      <br><br><b><a href='https://itsc.usindh.edu.pk/eportal/public/reset_password.php?i=$user_id&t=$token'>Password Reset Link Click Here</a></b><br>
                      Note that above link for password reset is valid for one time use only
                      <br><br>
                      
                      Best Regards, <br>
                      -------------------------------------<br>
                      IT Services Support Team<br>
                      University of Sindh, Jamshoro, Pakistan.<br>
                      Email: itsc@usindh.edu.pk<br>";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: itsc@usindh.edu.pk' . "\r\n";
    $headers .= "X-Priority: 3\r\n";
// $headers .= 'Reply-To: director.itsc@usindh.edu.pk' . "\r\n";
    $headers .= "Return-Path: The Sender <itsc@usindh.edu.pk>\r\n";

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
function sendMultipleEmail($email_list,$msg,$sender='itsc@usindh.edu.pk'){
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
