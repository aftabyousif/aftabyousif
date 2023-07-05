<?php
class AdmitCard_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
	}

	function insert($data,$table)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		if ($this->legacy_db->insert($table, $data))
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT METHOD',$table,11,0);
			return true;
		}else return false;
	}//method

	function getVenueOnVenue_ID ($venue_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`venue`');
		$this->legacy_db->where("VENUE_ID=$venue_id");
		return($this->legacy_db->get()->result_array());
	}

	function getVenueOnSession_ID ($session_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`venue`');
		$this->legacy_db->where("SESSION_ID=$session_id");
		return($this->legacy_db->get()->result_array());
	}
	function DeleteVenue($venue_id)
	{
		$prev_record = $this->getVenueOnVenue_ID($venue_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("VENUE_ID",$venue_id);
		$query = $this->legacy_db->delete('venue');
		if ($query)
		{
			$this->log_model->create_log($venue_id,$this->legacy_db->insert_id(),$prev_record,'','storing venue id','venue',13,0);
			return true;
		}
		else return false;
	}

	function getBlockOnBlock_ID ($block_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`block`');
		$this->legacy_db->where("block_id=$block_id");
		return($this->legacy_db->get()->result_array());
	}

	function getBlockOnVenue_ID ($venue_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('v.`VENUE_ID`, v.`SESSION_ID`, v.`VENUE_NO`, v.`VENUE_NAME`, v.`LOCATION` AS VENUE_LOCATION, v.`REMARKS` AS VENUE_REMARKS, b.`BLOCK_ID`, b.`BLOCK_NO`, b.`BLOCK_NAME`, b.`LOCATION` AS BLOCK_LOCATION, `SEATING_CAPACITY`, `RESERVED_FOR`, b.`REMARKS` AS BLOCK_REMARKS');
		$this->legacy_db->from('`venue v`');
		$this->legacy_db->join('`block b`','v.VENUE_ID=b.VENUE_ID','INNER');
		$this->legacy_db->where("v.VENUE_ID=$venue_id");
		return($this->legacy_db->get()->result_array());
	}
	
	/*
	 * created new methods Yasir Mehboob 16-10-2020
	 * */

	function get_first_seat_no_on_session_id  ($session_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('MIN(CARD_ID) AS FIRST_SEAT_NO');
		$this->legacy_db->from('`admit_card`');
		$this->legacy_db->where("SESSION_ID=$session_id");
		return($this->legacy_db->get()->result_array());
	}

	function get_last_seat_no_on_session_id  ($session_id,$program_type_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('MAX(CARD_ID) AS LAST_SEAT_NO');
		$this->legacy_db->from('`admit_card`');
		$this->legacy_db->where("SESSION_ID=$session_id");
		$this->legacy_db->where("PROGRAM_TYPE_ID=$program_type_id");
		return($this->legacy_db->get()->result_array());
	}

	function get_applications_for_admit_card_generation ($session_id,$prog_type_id,$campus_id,$status_id,$genders)
	{
		//those candidates are skipped who's admit card is already generated.
        // $campus_id is actually admission_session_id this is variable name mistake did by yasir Mehboob
        
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select("app.`APPLICATION_ID`,
					  ass.`ADMISSION_SESSION_ID`,
					  s.`SESSION_ID`,
					  ass.`PROGRAM_TYPE_ID` AS PROGRAM_TYPE_ID,
					  ur.FIRST_NAME AS `NAME`,
					  ur.FNAME AS FNAME,
					  ur.GENDER AS GENDER ");
		$this->legacy_db->from('`sessions` s');
		$this->legacy_db->join('`admission_session` ass','s.SESSION_ID = ass.SESSION_ID','INNER');
		$this->legacy_db->join('`applications` app','ass.ADMISSION_SESSION_ID = app.ADMISSION_SESSION_ID','INNER');
		$this->legacy_db->join('`admit_card` ac','app.`APPLICATION_ID` = ac.`APPLICATION_ID`','LEFT OUTER');
		$this->legacy_db->join('`users_reg` ur','app.`USER_ID` = ur.`USER_ID`');
		$this->legacy_db->where("s.`SESSION_ID`=$session_id");
		$this->legacy_db->where("ass.`ADMISSION_SESSION_ID` IN ($campus_id)");
		$this->legacy_db->where("ass.`PROGRAM_TYPE_ID` IN ($prog_type_id)");
		$this->legacy_db->where("app.`IS_SUBMITTED` = 'Y' ");
		$this->legacy_db->where("app.`STATUS_ID` IN  ($status_id) ");
		$this->legacy_db->where("ur.GENDER IN  ($genders) ");
		$this->legacy_db->where("ac.`APPLICATION_ID` IS NULL");
		$this->legacy_db->order_by('s.`SESSION_ID`');
		$this->legacy_db->order_by('ass.`CAMPUS_ID`');
		$this->legacy_db->order_by('ass.`PROGRAM_TYPE_ID`');
		$this->legacy_db->order_by('GENDER');
		$this->legacy_db->order_by('`NAME`');
//		print_r($this->legacy_db->last_query());
//		exit();
		return($this->legacy_db->get()->result_array());
	}

	function insert_seat_nos($records)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->trans_begin();
		$this->legacy_db->db_debug = false;
		$transaction_flag = false;

		foreach ($records as $record)
		{
//			prePrint($record);
			if ($this->legacy_db->insert('admit_card',$record))
			{
//				$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT METHOD',$table,11,0);
//				return true;
				$transaction_flag = true;
			}else
			{
				$transaction_flag = false;
				break;
			}
		}
		if ($transaction_flag == true)
		{
			$this->legacy_db->trans_commit();
			return true;
		}else
		{
			$this->legacy_db->trans_rollback();
			return false;
		}
	}//method

	function get_statistics ($session_id,$program_type_id,$admission_session_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`application_statistics`');

		if($session_id>0) $this->legacy_db->where("SESSION_ID=$session_id");
		if($program_type_id>0) $this->legacy_db->where("PROGRAM_TYPE_ID=$program_type_id");
		if($admission_session_id>0) $this->legacy_db->where("ADMISSION_SESSION_ID IN ($admission_session_id)");
		return($this->legacy_db->get()->result_array());
	}

    function get_statistics_gender_wise ($session_id,$program_type_id,$admission_session_id,$gender){

		$this->legacy_db = $this->load->database('admission_db',true);

		$this->legacy_db->select("ass.`ADMISSION_SESSION_ID`,
  ass.`CAMPUS_ID`,
  ass.`PROGRAM_TYPE_ID`,
  ass.`SESSION_ID`,
  c.`NAME`,
  pt.`PROGRAM_TITLE`,
  s.`REMARKS`,
  ureg.GENDER AS GENDER,
  COUNT(app.APPLICATION_ID) AS TOTAL_APPLICATIONS,
  COUNT(
    CASE
      WHEN app.`STATUS_ID` = 1 
      THEN app.`APPLICATION_ID` 
    END
  ) 'DRAFT',
  COUNT(
    CASE
      WHEN app.`IS_SUBMITTED` = 'Ý' 
      THEN app.`APPLICATION_ID` 
    END
  ) 'SUBMITTED',
  COUNT(
    CASE
      WHEN app.`STATUS_ID` = 4 
      THEN app.`APPLICATION_ID` 
    END
  ) 'IN_REVIEW',
  COUNT(
    CASE
      WHEN app.`STATUS_ID` = 3 
      THEN app.`APPLICATION_ID` 
    END
  ) 'IN_PROCESS',
  COUNT(
    CASE
      WHEN app.`STATUS_ID` = 5 
      THEN app.`APPLICATION_ID` 
    END
  ) 'FORM_VERIFIED',
  COUNT(
    CASE
      WHEN app.`STATUS_ID` = 6 
      THEN app.`APPLICATION_ID` 
    END
  ) 'FORM_REJECTED',
  COUNT(
    CASE
      WHEN app.`STATUS_ID` = 8 
      THEN app.`APPLICATION_ID` 
    END
  ) 'ENROLLED',
  COUNT(
    CASE
      WHEN app.`APPLICATION_ID` = ac.`APPLICATION_ID` 
      THEN ac.`APPLICATION_ID` 
    END
  ) 'TOTAL_ADMIT_CARDS',
  COUNT(
    CASE
      WHEN ac.`IS_DISPATCHED` = 'N' 
      THEN ac.`APPLICATION_ID` 
    END
  ) 'NOT_DISPATCHED',
  COUNT(
    CASE
      WHEN ac.`IS_DISPATCHED` = 'Y' 
      THEN ac.`APPLICATION_ID` 
    END
  ) 'DISPATCHED',
  COUNT(
    CASE
      WHEN fc.`CHALLAN_IMAGE` != '' 
      THEN fc.`APPLICATION_ID` 
    END
  ) 'UPLOADED_CHALLAN',
   COUNT(
    CASE
      WHEN fc.`IS_VERIFIED` = 'Y' 
      THEN fc.`APPLICATION_ID` 
    END
  ) 'CHALLAN_VERIFIED' ");
		$this->legacy_db->from('program_type pt');
		$this->legacy_db->from('campus c');
		$this->legacy_db->from('`sessions` s');
		$this->legacy_db->from('`users_reg` ureg');
		$this->legacy_db->join('`admission_session` ass ','s.SESSION_ID = ass.SESSION_ID','INNER');
		$this->legacy_db->join('`applications` app','ass.ADMISSION_SESSION_ID = app.ADMISSION_SESSION_ID AND app.USER_ID=ureg.USER_ID','INNER');
		$this->legacy_db->join('form_challan fc','(app.APPLICATION_ID=fc.APPLICATION_ID)','INNER');
		$this->legacy_db->join('admit_card ac','app.`APPLICATION_ID` = ac.`APPLICATION_ID`','LEFT');
		$this->legacy_db->where("c.`CAMPUS_ID` = ass.`CAMPUS_ID`");
		$this->legacy_db->where("pt.`PROGRAM_TYPE_ID` = ass.`PROGRAM_TYPE_ID` ");
		$this->legacy_db->where("app.APPLICATION_ID > 0 ");
		$this->legacy_db->where("app.IS_DELETED = 'N' ");
		if($session_id>0) $this->legacy_db->where("s.SESSION_ID=$session_id");
		if($program_type_id>0) $this->legacy_db->where("pt.PROGRAM_TYPE_ID=$program_type_id");
		if(!empty($admission_session_id)) $this->legacy_db->where("ass.ADMISSION_SESSION_ID IN ($admission_session_id)");
		if($gender != null) $this->legacy_db->where("ureg.GENDER IN ($gender)");
		$this->legacy_db->group_by("ass.`ADMISSION_SESSION_ID`,ureg.GENDER");
		$this->legacy_db->order_by('s.`SESSION_ID`,ass.`CAMPUS_ID`,ass.`PROGRAM_TYPE_ID`');
		return ($this->legacy_db->get()->result_array());
//		prePrint($this->legacy_db->last_query());
	}
	
	function get_statistics_gender_wise_new ($session_id,$program_type_id,$admission_session_id,$gender)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select(" fc.PAID_AMOUNT,fc.IS_VERIFIED,app.STATUS_ID,ur.GENDER,ass.ADMISSION_SESSION_ID,c.NAME ");
		$this->legacy_db->from('applications app');
	
	
		$this->legacy_db->join('`admission_session` ass','ass.ADMISSION_SESSION_ID = app.ADMISSION_SESSION_ID','INNER');
		$this->legacy_db->join('`sessions` s ','s.SESSION_ID = ass.SESSION_ID','INNER');
		$this->legacy_db->join('`campus` c','c.CAMPUS_ID = ass.CAMPUS_ID','INNER');
		$this->legacy_db->join('`program_type` pt','pt.PROGRAM_TYPE_ID = ass.PROGRAM_TYPE_ID','INNER');
	
		$this->legacy_db->join('`users_reg` ur','ur.USER_ID = app.USER_ID','INNER');
		$this->legacy_db->join('`form_challan` fc','fc.APPLICATION_ID = app.APPLICATION_ID','INNER');
	
		$this->legacy_db->where("app.APPLICATION_ID > 0 ");
		
		if($session_id>0) $this->legacy_db->where("s.SESSION_ID=$session_id");
		if($program_type_id>0) $this->legacy_db->where("pt.PROGRAM_TYPE_ID=$program_type_id");
		if($admission_session_id>0) $this->legacy_db->where("ass.ADMISSION_SESSION_ID IN ($admission_session_id)");
		if($gender != null) $this->legacy_db->where("ur.GENDER IN ($gender)");
		$this->legacy_db->limit(10);
		$data = $this->legacy_db->get()->result_array();
		echo $this->legacy_db->last_query();
		//$this->legacy_db->order_by('s.`SESSION_ID`,ass.`CAMPUS_ID`,ass.`PROGRAM_TYPE_ID`');
		return($data);
	}
	
	function get_challan_statistics($session_id,$program_type_id,$admission_session_id){
	    	
	    $this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select("ISNULL(fc.CHALLAN_IMAGE) IS_UPLOAD, count(ISNULL(fc.CHALLAN_IMAGE)) AS TOTAL_UPLOAD,`fc`.`IS_VERIFIED`, count(IFNULL(fc.IS_VERIFIED, 0)) AS TOTAL_VERIFIED, `ass`.`ADMISSION_SESSION_ID`");
		
		$this->legacy_db->from('applications app');
		$this->legacy_db->join('`admission_session` ass','ass.ADMISSION_SESSION_ID = app.ADMISSION_SESSION_ID','INNER');
		$this->legacy_db->join('`sessions` s ','s.SESSION_ID = ass.SESSION_ID','INNER');
		$this->legacy_db->join('`campus` c','c.CAMPUS_ID = ass.CAMPUS_ID','INNER');
		$this->legacy_db->join('`program_type` pt','pt.PROGRAM_TYPE_ID = ass.PROGRAM_TYPE_ID','INNER');
		$this->legacy_db->join('`users_reg` ur','ur.USER_ID = app.USER_ID','INNER');
		$this->legacy_db->join('`form_challan` fc','fc.APPLICATION_ID = app.APPLICATION_ID','INNER');
	
		$this->legacy_db->where("app.APPLICATION_ID > 0 ");
		
		if($session_id>0) $this->legacy_db->where("s.SESSION_ID=$session_id");
		if($program_type_id>0) $this->legacy_db->where("pt.PROGRAM_TYPE_ID=$program_type_id");
		if($admission_session_id>0) $this->legacy_db->where("ass.ADMISSION_SESSION_ID IN ($admission_session_id)");
	   
	   $this->legacy_db->group_by("ass.`ADMISSION_SESSION_ID`, ISNULL(fc.CHALLAN_IMAGE),fc.IS_VERIFIED");
		$list = $this->legacy_db->get()->result_array();
		//print_r($list);
		//exit();
		$new_list = array();
		foreach($list as $data){
		    if(!isset($new_list[$data['ADMISSION_SESSION_ID']])){
		        $new_list[$data['ADMISSION_SESSION_ID']] = array();
		    }
		     array_push($new_list[$data['ADMISSION_SESSION_ID']],$data);
		}
	//	echo $this->legacy_db->last_query();
// 		prePrint($new_list);
 //		exit();
		//$this->legacy_db->order_by('s.`SESSION_ID`,ass.`CAMPUS_ID`,ass.`PROGRAM_TYPE_ID`');
		return($new_list);
	}
	
	/*
	 * YASIR CREATED FOLLOWING METHODS ON 25-02-2021
	 * */
	function getAdmitCardOnAppID ($application_id){
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`admit_card`');
		$this->legacy_db->where("APPLICATION_ID=$application_id");
		return($this->legacy_db->get()->row_array());
	}
	function getAdmitCardForApp($session_id,$program_type_id,$date_time,$limit,$offset,$start_card_id=null,$last_card_id=null){
	    
	    $this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('ac.*,fc.CHALLAN_IMAGE,ur.CNIC_NO,ur.FIRST_NAME,ur.LAST_NAME,ur.FNAME,ur.PROFILE_IMAGE,b.BLOCK_NAME,b.LOCATION,b.BUILDING_NAME,b.BLOCK_NO,app.IS_PROFILE_PHOTO_VERIFIED');
		$this->legacy_db->from('`admit_card` ac');
	    $this->legacy_db->join('`applications` app','app.APPLICATION_ID = ac.APPLICATION_ID');
	    $this->legacy_db->join('`form_challan` fc','app.APPLICATION_ID = fc.APPLICATION_ID');
	    $this->legacy_db->join('`users_reg` ur','ur.USER_ID = app.USER_ID');
	    $this->legacy_db->join('`venue` v','v.SESSION_ID = ac.SESSION_ID');
	    
	    $this->legacy_db->join('`block` b','(v.VENUE_ID = b.VENUE_ID AND b.START_SEAT_NO <=ac.CARD_ID && b.END_SEAT_NO >=ac.CARD_ID )',"LEFT");
		$this->legacy_db->where("ac.SESSION_ID",$session_id);
		$this->legacy_db->where("ac.PROGRAM_TYPE_ID",$program_type_id);
		if($start_card_id!=null&&$last_card_id!=null){
		    $this->legacy_db->where("ac.CARD_ID BETWEEN $start_card_id AND $last_card_id ");
		}
		if($date_time){
		$this->legacy_db->where("DATE_FORMAT(`ac`.`TEST_DATETIME`,'%Y-%m-%d') LIKE '$date_time'");    
		}
		if($limit!=null&$offset!=null)
		$this->legacy_db->limit($limit,$offset);
		$data = $this->legacy_db->get()->result_array();
//  		echo $this->legacy_db->last_query();
//  		exit();
		return $data;
	}
	function getAdmitCardCountForApp($session_id,$program_type_id,$date_time){
	       $this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('COUNT(*) TOTAL_RECORD');
		$this->legacy_db->from('`admit_card` ac');
	    $this->legacy_db->join('`applications` app','app.APPLICATION_ID = ac.APPLICATION_ID');
	    $this->legacy_db->join('`users_reg` ur','ur.USER_ID = app.USER_ID');
		$this->legacy_db->where("ac.SESSION_ID",$session_id);
		$this->legacy_db->where("ac.PROGRAM_TYPE_ID",$program_type_id);
		$this->legacy_db->where("DATE_FORMAT(`ac`.`TEST_DATETIME`,'%Y-%m-%d') LIKE '$date_time'");
		//$this->legacy_db->limit("6000");
		$data = $this->legacy_db->get()->result_array();
		//echo $this->legacy_db->last_query();
		//exit();
		return $data;
	}
	function getBlockBySeatNoAndSessionId($seat_no ,$session_id){
	    //SELECT * FROM `block` join  where START_SEAT_NO <=6047 AND END_SEAT_NO>=6047
	     $this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*,b.LOCATION');
		$this->legacy_db->from('block b');
	    $this->legacy_db->join('`venue` v','v.VENUE_ID = b.VENUE_ID');
	    $this->legacy_db->where("b.START_SEAT_NO <= $seat_no");
	   $this->legacy_db->where("b.END_SEAT_NO>= $seat_no");
	   $this->legacy_db->where("v.SESSION_ID", $session_id);
	   	$data = $this->legacy_db->get()->row_array();
	   	//echo $this->legacy_db->last_query();
	   	return($data);
	}
	//for temporary
	function getDataForPhoto($date_time,$program_type_id,$gender,$is_verified,$limit,$challan_upload,$DISTRICT_ID,$IS_PROFILE_PHOTO_VERIFIED,$PROVINCE_ID){
	    
	    $this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('app.APPLICATION_ID,ur.FIRST_NAME,ur.LAST_NAME,ur.FNAME,ur.PROFILE_IMAGE,ac.CARD_ID,ur.GENDER,ur.USER_ID,app.IS_PROFILE_PHOTO_VERIFIED');
		$this->legacy_db->from('`applications` app');
	    $this->legacy_db->join('`users_reg` ur','ur.USER_ID = app.USER_ID');
	    $this->legacy_db->join('`districts` dis','dis.DISTRICT_ID = ur.DISTRICT_ID');
	    $this->legacy_db->join('`form_challan` fc','fc.APPLICATION_ID = app.APPLICATION_ID');
	    $this->legacy_db->join('`admit_card` ac',"ac.APPLICATION_ID = app.APPLICATION_ID AND ac.PROGRAM_TYPE_ID =$program_type_id","LEFT");
	    $this->legacy_db->join('`admission_session` as','app.ADMISSION_SESSION_ID = as.ADMISSION_SESSION_ID');
		
		$this->legacy_db->where("app.ADMISSION_SESSION_ID>=99");
		if($IS_PROFILE_PHOTO_VERIFIED == 0){
             $this->legacy_db->where("(app.IS_PROFILE_PHOTO_VERIFIED IS NULL OR app.IS_PROFILE_PHOTO_VERIFIED =0)");	
    	}else if($IS_PROFILE_PHOTO_VERIFIED>0){
    	    $this->legacy_db->where("app.IS_PROFILE_PHOTO_VERIFIED = $IS_PROFILE_PHOTO_VERIFIED");	
    	}
		
		   
		if($challan_upload=='Y'){
		$this->legacy_db->where("fc.CHALLAN_IMAGE is not null ");    
		}else if($challan_upload=='N'){
		    $this->legacy_db->where("fc.CHALLAN_IMAGE is null ");
		}
		if($program_type_id != null) 
		    $this->legacy_db->where("as.PROGRAM_TYPE_ID = '$program_type_id'");
		else{
		     $this->legacy_db->where("as.PROGRAM_TYPE_ID = '1'");
		}
		
        //$this->legacy_db->where("ac.PROGRAM_TYPE_ID",$program_type_id);
        // $this->legacy_db->where_in("ur.DISTRICT_ID",array(127,128,129,130,131,132,133,134,135,136,147,148,149,150,152));
		if($date_time != null) $this->legacy_db->where("DATE_FORMAT(`ac`.`TEST_DATETIME`,'%Y-%m-%d') LIKE '$date_time'");
		if($gender != null) $this->legacy_db->where("ur.GENDER LIKE '$gender'");
		if($DISTRICT_ID != null) $this->legacy_db->where("ur.DISTRICT_ID IN ($DISTRICT_ID)");
		if($PROVINCE_ID != null) $this->legacy_db->where("dis.PROVINCE_ID = '$PROVINCE_ID'");
		
		if($is_verified != null) {
		    if($is_verified=="Y"){
		    $this->legacy_db->where("fc.IS_VERIFIED = '$is_verified'");    
		    }else{
		        $this->legacy_db->where("(fc.IS_VERIFIED = '$is_verified' OR fc.IS_VERIFIED IS NULL)");    
		    }
		    
		}
		//$this->legacy_db->where("ac.CARD_ID IS NULL");
        $this->legacy_db->order_by('ur.GENDER');
        $this->legacy_db->order_by('app.APPLICATION_ID');
        if($limit !=null) $this->legacy_db->limit($limit);
		$data = $this->legacy_db->get()->result_array();
 	//	echo $this->legacy_db->last_query();
 	//	exit;
		return $data;
	}
	function add_data_in_test_result($TEST_ID,$ADMISSION_SESSION_IDS,$IS_LLB){
	    $this->legacy_db = $this->load->database('admission_db',true);
	    if($IS_LLB=='Y'){
                $sql = "INSERT into test_result(CARD_ID,APPLICATION_ID,USER_ID,TEST_SCORE,TEST_ID,ACTIVE)(select ac.CARD_ID,app.APPLICATION_ID,  ali.USER_ID,  ali.TEST_SCORE,  $TEST_ID,  1  from admit_card ac  left join (select * from test_result where TEST_ID=$TEST_ID) tr on (ac.CARD_ID = tr.CARD_ID ) join applications app on (ac.APPLICATION_ID = app.APPLICATION_ID) join applicants_lat_info ali ON (ali.APPLICATION_ID = ac.APPLICATION_ID ) where app.ADMISSION_SESSION_ID IN ($ADMISSION_SESSION_IDS) AND ali.ACTIVE = 1 and tr.CARD_ID IS NULL)";     
                }else{
                $sql = "INSERT into test_result(CARD_ID,TEST_ID,USER_ID,APPLICATION_ID,ACTIVE)(select ac.CARD_ID,$TEST_ID,app.USER_ID,ac.APPLICATION_ID,1  from admit_card ac  left join (select * from test_result where TEST_ID=$TEST_ID) tr on (ac.CARD_ID = tr.CARD_ID ) join applications app on (ac.APPLICATION_ID = app.APPLICATION_ID) where app.ADMISSION_SESSION_ID IN ($ADMISSION_SESSION_IDS) and tr.CARD_ID IS NULL)";    
                }
               // echo $sql;
               // exit();
	    $query = $this->legacy_db->query($sql);
	   // prePrint( $this->db->error());
	    //var_dump($query);
	    //exit();
	    return $query;
	}
}
