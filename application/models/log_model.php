<?php

class log_model extends CI_Model
{
	protected $SessionName = 'ADMIN_LOGIN_FOR_ADMISSION';

	/*
	 * operation codes
	 * 11 FOR INSERT
	 * 12 FOR UPDATE
	 * 13 FOR DELETE
	 * 14 FOR TABLE DELETE
	 * 15 FOR TABLE TRUNCATE
	 * 16 FOR TABLE DROP
	 * 17 FOR DATABASE DROP
	 * 18 FOR SELECT QUERY
	 * 21 FOR LOGIN
	 * 22 FOR FAILED LOGIN
	 * 23 FOR PASSWORD CHANGE OUTSIDE PORTAL
	 * 24 FOR PASSWORD CHANGE INSIDE PORTAL
	 * 25 FOR USER RIGHT CHANGED
	 * */

	function create_log($PREV_ID,$NEW_ID,$PREV_RECORD,$NEW_RECORD,$DETAIL,$TABLE_NAME,$OPERATION_CODE,$USER_ID)
	{
//		$datetime = date('Y-m-d H:i:s');
		$datetime = gmdate('Y-m-d H:i:s',time());

		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';

		$ip_addr = $ipaddress;

		$MAC = exec('getmac');
		// Storing 'getmac' value in $MAC
		$MAC = strtok($MAC, ' ');

		$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
		$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
		$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$win = strpos($_SERVER['HTTP_USER_AGENT'],'Windows');
		$Macintosh = strpos($_SERVER['HTTP_USER_AGENT'],'Macintosh');
		$Linux = strpos($_SERVER['HTTP_USER_AGENT'],'Linux');

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

		$PREV_RECORD = json_encode($PREV_RECORD);
		$NEW_RECORD = json_encode($NEW_RECORD);

		if ($USER_ID == 0 || $USER_ID == null || $USER_ID == "" || empty($USER_ID))
		{
			$user_data =$this->session->userdata($this->SessionName);
			$USER_ID = $user_data['USER_ID'];
		}

		$array = array(
			'PREV_ID'=>$PREV_ID,
			'NEW_ID'=>$NEW_ID,
			'PREV_RECORD'=>$PREV_RECORD,
			'NEW_RECORD'=>$NEW_RECORD,
			'DETAIL'=>$DETAIL,
			'OPERATION_CODE'=>$OPERATION_CODE,
			'USER_ID'=>$USER_ID,
			'IP_ADDRESS'=>$ip_addr,
			'MAC_ADDRESS'=>$MAC,
			'USER_AGENT'=>$user_agent,
			'DATETIME'=>$datetime,
			'TABLE_NAME'=>$TABLE_NAME
		);

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->insert('log', $array);
	}//function
    function itsc_log($QUERY_TITLE,$QUERY_STATUS,$QUERY,$USER_TYPE,$USER_ID,$CURRENT_RECORD,$PRE_RECORD,$ROW_ID,$TABLE_NAME){

	    $datetime = gmdate('Y-m-d H:i:s',time());

        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        $ip_addr = $ipaddress;


        $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
        $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $win = strpos($_SERVER['HTTP_USER_AGENT'],'Windows');
        $Macintosh = strpos($_SERVER['HTTP_USER_AGENT'],'Macintosh');
        $Linux = strpos($_SERVER['HTTP_USER_AGENT'],'Linux');

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

        $CURRENT_RECORD = json_encode($CURRENT_RECORD);
        $PRE_RECORD = json_encode($PRE_RECORD);

        $array = array(
            'QUERY_TITLE'=>$QUERY_TITLE,
            'QUERY_STATUS'=>$QUERY_STATUS,
            'PRE_RECORD'=>$PRE_RECORD,
            'CURRENT_RECORD'=>$CURRENT_RECORD,
            'USER_ID'=>$USER_ID,
            'IP_ADDRESS'=>$ip_addr,
            'USER_AGENT'=>$user_agent,
            'DATETIME'=>$datetime,
            'TABLE_NAME'=>$TABLE_NAME,
            'ROW_ID'=>$ROW_ID,
            'REMARKS'=>"ADMISSION"
        );


        $this->db->insert('log', $array);
    }
//	function get_log ()
//	{
//		$this->legacy_db = $this->load->database('admission_db',true);
////		print_r($adm_con);
//		$this->legacy_db->select('`LOG_ID`, `NEW_RECORD`');
////		return $this->legacy_db->get('log')->result_array();
//		return date();
//	}
}
