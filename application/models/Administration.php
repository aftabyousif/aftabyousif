<?php

class Administration extends CI_Model
{
	function __construct()
	{
		parent::__construct();
//		$CI =& get_instance();
		$this->load->model('log_model');
	}

	function programs ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('PROG_LIST_ID, PROGRAM_TITLE, REMARKS');
		return $this->legacy_db->get('program_list')->result_array();
	}
	
	function getCampus ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		return $this->legacy_db->get('campus')->result_array();
	}
	
	function getPartByTypeID ($program_type_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
	if ($program_type_id>0)	$this->legacy_db->where('PROGRAM_TYPE_ID',$program_type_id);
		return $this->legacy_db->get('part')->result_array();
	}
	
	function getSemesterByDemeritID ($fee_demerit_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->where('FEE_DEMERIT_ID',$fee_demerit_id);
		return $this->legacy_db->get('semester')->result_array();
	}
	
    function getProgramByTypeID ($program_type_id,$MORNING_SHIFT=0)
    {
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('PROG_LIST_ID, PROGRAM_TITLE, REMARKS,PRE_REQ_PER');
        $this->legacy_db->where('PROGRAM_TYPE_ID',$program_type_id);
        return $this->legacy_db->get('program_list')->result_array();
    }
	
	function getCategory ()
    {
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('FORM_CATEGORY_ID, FORM_CATEGORY_NAME, REMARKS');
        return $this->legacy_db->get('form_category')->result_array();
    }

	function programTypes ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('PROGRAM_TYPE_ID, PROGRAM_TITLE, REMARKS');
		return $this->legacy_db->get('program_type')->result_array();
	}

	function shifts ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('`SHIFT_ID`, `SHIFT_NAME`, `REMARKS`');
		return $this->legacy_db->get('shift')->result_array();
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

	function update($where,$record,$prev_record,$table){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->trans_begin();

		$this->legacy_db->where($where);
		$this->legacy_db->update($table,$record);
		if($this->legacy_db->affected_rows() ==1){
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),$prev_record,$record,"($where) FROM update METHOD",$table,12,0);
			$this->legacy_db->trans_commit();
			return true;
		}else{
			$this->legacy_db->trans_rollback();
			return false;
		}
	}//function

	function insert_batch($data,$table)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		if ($this->legacy_db->insert_batch($table, $data))
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),'',$data,'FROM INSERT_BATCH METHOD',$table,11,0);
			return true;
		}else return $this->legacy_db->error();
	}//method

	function getMappedPrograms ($shift_id,$program_type,$campus_id=0){
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('p.PROG_LIST_ID AS PROG_ID, pt.PROGRAM_TITLE AS DEGREE_TITLE,p.PROGRAM_TITLE,pm.REMARKS AS REMARKS,s.SHIFT_ID AS SHIFT_ID, SHIFT_NAME');
		$this->legacy_db->from('program_list p');
		$this->legacy_db->join('shift_program_mapping pm','p.PROG_LIST_ID=pm.PROG_LIST_ID','INNER');
		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID','INNER');
		$this->legacy_db->join('program_type pt','pt.PROGRAM_TYPE_ID=pm.PROGRAM_TYPE_ID','INNER');
        
		if ($shift_id>0)		$this->legacy_db->where("s.SHIFT_ID IN ({$shift_id})");
		if ($program_type>0) 	$this->legacy_db->where("pt.PROGRAM_TYPE_ID IN ({$program_type})");
		if ($campus_id>0) 	$this->legacy_db->where("pm.CAMPUS_ID IN ({$campus_id})");
        $this->legacy_db->order_by('p.program_title','ASC');
		return $this->legacy_db->get()->result_array();
	}

    //ADDED BY VIKESH KUMAR FOR LLB(LAW)
    function getMappedProgramsLaw ($shift_id,$program_type,$campus_id=0)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('p.PROG_LIST_ID AS PROG_ID, pt.PROGRAM_TITLE AS DEGREE_TITLE,p.PROGRAM_TITLE,pm.REMARKS AS REMARKS,s.SHIFT_ID AS SHIFT_ID, SHIFT_NAME');
		$this->legacy_db->from('program_list p');
		$this->legacy_db->join('shift_program_mapping pm','p.PROG_LIST_ID=pm.PROG_LIST_ID','INNER');
		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID','INNER');
		$this->legacy_db->join('program_type pt','pt.PROGRAM_TYPE_ID=pm.PROGRAM_TYPE_ID','INNER');
        $this->legacy_db->where("p.PROG_LIST_ID",143);
		if ($shift_id>0)		$this->legacy_db->where("s.SHIFT_ID IN ({$shift_id})");
		if ($program_type>0) 	$this->legacy_db->where("pt.PROGRAM_TYPE_ID IN ({$program_type})");
		if ($campus_id>0) 	$this->legacy_db->where("pm.CAMPUS_ID IN ({$campus_id})");

		return $this->legacy_db->get()->result_array();
	}

	function ignoreMappedPrograms ($shift_id,$campus_id=0)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('p.PROG_LIST_ID AS PROG_ID,p.PROGRAM_TITLE AS PROGRAM_TITLE');
		$this->legacy_db->from('program_list p');
		$this->legacy_db->join('shift_program_mapping pm',"(p.PROG_LIST_ID=pm.PROG_LIST_ID AND pm.SHIFT_ID=$shift_id AND pm.CAMPUS_ID=$campus_id)",'LEFT');
//		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID ','INNER');
		$this->legacy_db->where("pm.PROG_LIST_ID IS NULL");

		return($this->legacy_db->get()->result_array());
	}

	function DeleteMappedPrograms_model($shift_id,$prog_id,$campus_id=0)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		if($campus_id==0){
		    	$this->legacy_db->where("SHIFT_ID=$shift_id AND PROG_LIST_ID=$prog_id");
		}else{
		    	$this->legacy_db->where("SHIFT_ID=$shift_id AND PROG_LIST_ID=$prog_id AND CAMPUS_ID=$campus_id");
		}
	
		$query = $this->legacy_db->delete('shift_program_mapping');
		if ($query)
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),array('PROG_LIST_ID'=>$prog_id,'SHIFT_ID'=>$shift_id),'','storing prog_list_id and shift_id','shift_program_mapping',13,0);
			return true;
		}
		else return false;
	}

	function category_type ()
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		return $this->legacy_db->get('category_type')->result_array();
	}

	function MappedCategory ($category_type_id,$category_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('ct.`CATEGORY_TYPE_ID`,ct.`CATEGORY_NAME` AS CATEGORY_TYPE_NAME,ct.`CODE` AS CATEGORY_TYPE_CODE ,`DISPLAY`,c.`CATEGORY_ID`,c.`CATEGORY_NAME`,c.`P_CODE`,c.`CODE` AS CATEGORY_CODE,c.`REMARKS` AS CATEGORY_REMARKS');
		$this->legacy_db->from('`category_type` ct');
		$this->legacy_db->join('`category` c',"(ct.CATEGORY_TYPE_ID=c.CATEGORY_TYPE_ID)",'INNER');
//		$this->legacy_db->join('shift s','s.SHIFT_ID=pm.SHIFT_ID ','INNER');
		if ($category_type_id>0)$this->legacy_db->where("ct.CATEGORY_TYPE_ID=$category_type_id");
		if ($category_id>0)$this->legacy_db->where("c.CATEGORY_ID",$category_id);
//		return($this->legacy_db->last_query());
		return($this->legacy_db->get()->result_array());
	}

	function DeleteMappedCategory($category_id)
	{
		$prev_record = $this->MappedCategory(0,$category_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("CATEGORY_ID",$category_id);
		$query = $this->legacy_db->delete('category');
		if ($query)
		{
			$this->log_model->create_log($category_id,$this->legacy_db->insert_id(),$prev_record,'','storing category id','category',13,0);
			return true;
		}
		else return false;
	}

	function MinorMapping ($minor_mapping_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`minor_mapping`');
		$this->legacy_db->where("MINOR_MAPPING_ID=$minor_mapping_id");
		return($this->legacy_db->get()->result_array());
	}

	function getMinorsByDiscipline_id ($discipline_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('`minor_mapping`');
		$this->legacy_db->where("DISCIPLINE_ID=$discipline_id");
		return($this->legacy_db->get()->result_array());
	}

	function DeleteMinorSubject($minor_mapping_id)
	{
		$prev_record = $this->MinorMapping($minor_mapping_id);
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("MINOR_MAPPING_ID",$minor_mapping_id);
		$query = $this->legacy_db->delete('minor_mapping');
		if ($query)
		{
			$this->log_model->create_log($minor_mapping_id,$this->legacy_db->insert_id(),$prev_record,'','storing minor_mapping_id','minor_mapping',13,0);
			return true;
		}
		else return false;
	}
	
	function getMappedCampusJurisdiction ($campus_id,$district_id=0)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('c.CAMPUS_ID AS CAMPUS_ID, c.NAME AS CAMPUS_NAME,c.`CODE`, c.`LOCATION`, c.`IS_MAIN`, c.`IS_CAMPUS`, c.`IS_COLLEGE`,j.IS_JURISDICTION,j.REMARKS AS REMARKS_JURISDICTION,j.JURISDICTION_ID AS JURISDICTION_ID,j.DISTRICT_ID AS DISTRICT_ID');
		$this->legacy_db->from('campus c');
		$this->legacy_db->join('jurisdiction j','c.CAMPUS_ID=j.CAMPUS_ID','INNER');

		if ($campus_id>0)		$this->legacy_db->where("c.CAMPUS_ID IN ({$campus_id})");
		if ($district_id>0) 	$this->legacy_db->where("j.DISTRICT_ID IN ({$district_id})");

		return $this->legacy_db->get()->result_array();
	}
	function getJurisdictionByDistrictId ($district_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('*');
		$this->legacy_db->from('jurisdiction');
		$this->legacy_db->where("DISTRICT_ID",$district_id);
        return $this->legacy_db->get()->result_array();
	}
	
	function DeleteJurisdiction($jurisdiction_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("JURISDICTION_ID=$jurisdiction_id");
		$query = $this->legacy_db->delete('jurisdiction');
		if ($query)
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),array('JURISDICTION_ID'=>$JURISDICTION_ID),'','storing JURISDICTION_ID','jurisdiction',13,0);
			return true;
		}
		else return false;
	}
	
	/*
	 * YASIR MEHBOOB ADDED NEW METHOD getProgramsByProgramType ON 15-10-2020
	 * */
	function getProgramsByProgramType ($program_type)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('p.PROG_LIST_ID AS PROG_ID, pt.PROGRAM_TITLE AS DEGREE_TITLE,p.PROGRAM_TITLE');
		$this->legacy_db->from('program_list p');
		$this->legacy_db->join('program_type pt','p.PROGRAM_TYPE_ID=pt.PROGRAM_TYPE_ID','INNER');

		if ($program_type>0) 	$this->legacy_db->where("pt.PROGRAM_TYPE_ID IN ({$program_type})");
		
		$this->legacy_db->order_by("p.PROGRAM_TITLE");

		return $this->legacy_db->get()->result_array();
	}
	 /*
     * Kashif Shaikh ADDED NEW METHOD getProgListByShiftAndProgTypeAndCampusId ON 12-11-2020
     * */
    function getProgListByShiftAndProgTypeAndCampusId ($shift_id,$program_type_id,$campus_id)
    {
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('spm.*,pl.PROGRAM_TITLE');
        $this->legacy_db->from('shift_program_mapping spm');

        $this->legacy_db->join('program_list pl','spm.PROG_LIST_ID=pl.PROG_LIST_ID','INNER');

       $this->legacy_db->where("spm.SHIFT_ID",$shift_id);
       $this->legacy_db->where("spm.PROGRAM_TYPE_ID",$program_type_id);
       $this->legacy_db->where("spm.CAMPUS_ID",$campus_id);
       $this->legacy_db->order_by("pl.PROGRAM_TITLE");

        return $this->legacy_db->get()->result_array();
    }
    
    function DeleteDisciplineSeatDistribution($discipline_seat_distribution_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("DISCIPLINE_SEAT_ID =$discipline_seat_distribution_id");
		$query = $this->legacy_db->delete('discipline_seats_distributions');
		if ($query)
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),array('DISCIPLINE_SEAT_ID'=>$discipline_seat_distribution_id),'','storing DISCIPLINE_SEAT_ID','discipline_seats_distributions',13,0);
			return true;
		}
		else return false;
	}

	function DeleteDistrictQuotaSeatDistribution($DISTRICT_QUOTE_ID)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->where("DISTRICT_QUOTE_ID =$DISTRICT_QUOTE_ID");
		$query = $this->legacy_db->delete('district_quota_seats');
		if ($query)
		{
			$this->log_model->create_log(0,$this->legacy_db->insert_id(),array('DISTRICT_QUOTE_ID'=>$DISTRICT_QUOTE_ID),'','storing DISTRICT_QUOTE_ID','district_quota_seats',13,0);
			return true;
		}
		else return false;
	}

    function getMinorMapping ()
    {
        $this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
        $this->legacy_db->select('*');
        $this->legacy_db->from('`minor_mapping`');
       // $this->legacy_db->where("MINOR_MAPPING_ID=$minor_mapping_id");
        return($this->legacy_db->get()->result_array());
    }
    
    function getProgram_prog_list_id ($program_list_id)
	{
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('PROG_LIST_ID, PROGRAM_TITLE, REMARKS');
		$this->legacy_db->where("PROG_LIST_ID",$program_list_id);
		return $this->legacy_db->get('program_list')->row_array();
	}
	
	function getPart ($program_type_id){
		$this->legacy_db = $this->load->database('admission_db',true);
//		print_r($adm_con);
		$this->legacy_db->select('PART_ID, NAME,NAME_PHARM, REMARKS');
		$this->legacy_db->where("PROGRAM_TYPE_ID",$program_type_id);
		return $this->legacy_db->get('part')->result_array();
	}
	
	function getSemester ($fee_demerit_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('*');
		$this->legacy_db->where("FEE_DEMERIT_ID",$fee_demerit_id);
		return $this->legacy_db->get('semester')->result_array();
	}
	
	function get_all_old_data ()
    {
        $this->legacy_db = $this->load->database('admission_db',true);

        $this->legacy_db->select('*');
        $this->legacy_db->from('admission_session ass');
        $this->legacy_db->join('sessions s','ass.SESSION_ID = s.SESSION_ID');


        $this->legacy_db->where('s.SESSION_ID >=', 3);
        $this->legacy_db->where('s.SESSION_ID <=', 8);
       // $this->legacy_db->limit(8);
        $admission_sessions = $this->legacy_db->get()->result_array();
        foreach ($admission_sessions as $admission_session){
            // prePrint($admission_session);
            $prog_type_id = $admission_session['PROGRAM_TYPE_ID'];
            $campus_id =  $admission_session['CAMPUS_ID'];
            $session_id =  $admission_session['SESSION_ID'];
            $admission_session_id = $admission_session['ADMISSION_SESSION_ID'];
            $year = $admission_session['YEAR'];
           // if($campus_id!=8){
            //    continue;
            //}
            $this->legacy_db->select('*');
            $this->legacy_db->where("CAMPUS_ID",$campus_id);
            $this->legacy_db->where("PROGRAM_TYPE_ID",$prog_type_id);
            $this->legacy_db->where("YEAR",$year);
            $this->legacy_db->where("IS_ADD",0);
            $this->legacy_db->limit(500);
            $list_of_old_student = $this->legacy_db->get('old_admission_data')->result_array();


            foreach ($list_of_old_student as $old_student){
                $PROG_LIST_ID = $old_student['PROG_LIST_ID'];
                $SHIFT_ID = $old_student['SHIFT_ID'];
                $CATEGORY_ID = $old_student['CATEGORY_ID'];
                $SEAT_NO = $old_student['SEAT_NO'];
                $ENTRY_TEST_MARKS = $old_student['ENTRY_TEST_MARKS'];
                $CANDIDATE_ID = $old_student['CANDIDATE_ID'];
                $CPN = $old_student['CPN'];
                $ROL_NO_DIGIT = $old_student['ROL_NO_DIGIT'];
                $user_id = $old_student['USER_ID'];
                $this->legacy_db->where('CANDIDATE_ID',$CANDIDATE_ID);
                $is_already_exist = $this->legacy_db->get('candidate_account')->row_array();
                if($is_already_exist){
                    prePrint("-------------------ALREADY EXISIST--------------------");
                    prePrint($is_already_exist);
                    prePrint("------------------------------------------------------");
                    continue;
                }

               // prePrint($old_student);

                $this->legacy_db->trans_begin();

                $application = array(
                    'ADMISSION_SESSION_ID' => $admission_session_id,
                    'USER_ID' => $user_id,
                    'STATUS_ID' => 11,
                    'REMARKS' =>'OLD STUDENT'
                );
                if(!$this->legacy_db->insert('applications', $application)){
                    prePrint("-------------------APPLICATION NOT ADDED --------------------");
                    prePrint($application);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }

                $application_id = $this->legacy_db->insert_id();

                $form_challan = array(
                    'ADMISSION_SESSION_ID' => $admission_session_id,
                    'USER_ID' => $user_id,
                    'APPLICATION_ID' => $application_id,
                    'FORM_FEE_ID' => 15,
                    'CHALLAN_AMOUNT' =>2500,
                    'REMARKS' =>'OLD STUDENT'
                );
                //$this->legacy_db->insert('form_challan', $form_challan);
                if(!$this->legacy_db->insert('form_challan', $form_challan)){
                    prePrint("-------------------form_challan NOT ADDED --------------------");
                    prePrint($form_challan);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }


                switch($CATEGORY_ID){
                    CASE  6: $form_cat_id = 5;break;
                    CASE  2: $form_cat_id = 6;break;
                    CASE  7: $form_cat_id = 3;break;
                    CASE  9: $form_cat_id = 4;break;
                    CASE  13:$form_cat_id = 2;break;
                    CASE  21:$form_cat_id = 2;break;
                    CASE  24: $form_cat_id = 8;break;
                    CASE  29: $form_cat_id = 7;break;
                    DEFAULT: $form_cat_id = 1;
                }
                $application_category = array(
                    'APPLICATION_ID' => $application_id,
                    'USER_ID' => $user_id,
                    'IS_ENABLE' => 'Y',
                    'FORM_CATEGORY_ID' =>$form_cat_id,
                    'REMARKS' =>'OLD STUDENT'
                );
                //prePrint($application_category);
               // $this->legacy_db->insert('application_category', $application_category);
                if(!$this->legacy_db->insert('application_category', $application_category)){
                    prePrint("-------------------application_category NOT ADDED --------------------");
                    prePrint($application_category);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }


                $applied_shift = array(
                    'APPLICATION_ID' => $application_id,
                    'USER_ID' => $user_id,
                    'SHIFT_ID' =>$SHIFT_ID,
                    'REMARKS' =>'OLD STUDENT'
                );


               // $this->legacy_db->insert('applied_shift', $applied_shift);
                if(!$this->legacy_db->insert('applied_shift', $applied_shift)){
                    prePrint("-------------------applied_shift NOT ADDED --------------------");
                    prePrint($applied_shift);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }


                $application_choices = array(
                    'APPLICATION_ID' => $application_id,
                    'USER_ID' => $user_id,
                    'CHOICE_NO' => '1',
                    'PROG_LIST_ID' =>$PROG_LIST_ID,
                    'SHIFT_ID' =>$SHIFT_ID,
                    'REMARKS' =>'OLD STUDENT'
                );
                //$this->legacy_db->insert('application_choices', $application_choices);
                if(!$this->legacy_db->insert('application_choices', $application_choices)){
                    prePrint("-------------------application_choices NOT ADDED --------------------");
                    prePrint($application_choices);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }

                $admit_card = array(
                    'APPLICATION_ID' => $application_id,
                    'ADMISSION_SESSION_ID'=>$admission_session_id,
                    'SESSION_ID'=>$session_id,
                    'CARD_ID'=>$SEAT_NO,
                    'PROGRAM_TYPE_ID'=>$prog_type_id,
                    'REMARKS' =>'OLD STUDENT'
                );
               // $this->legacy_db->insert('admit_card', $admit_card);
                if(!$this->legacy_db->insert('admit_card', $admit_card)){
                    prePrint("-------------------admit_card NOT ADDED --------------------");
                    prePrint($admit_card);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }


                $test_result = array(
                    'TEST_ID'=>7,
                    'TEST_SCORE'=>$ENTRY_TEST_MARKS,
                    'CARD_ID'=>$SEAT_NO,
                    'APPLICATION_ID' => $application_id,
                    'USER_ID' => $user_id,
                    'ACTIVE' =>1,
                    'CPN'=>$CPN,
                    'REMARKS' =>'OLD STUDENT'
                );
                //$this->legacy_db->insert('test_result', $test_result);
                if(!$this->legacy_db->insert('test_result', $test_result)){
                    prePrint("-------------------test_result NOT ADDED --------------------");
                    prePrint($test_result);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }

                $selection_list = array(
                    'APPLICATION_ID' => $application_id,
                    'USER_ID' => $user_id,
                    'CHOICE_NO' => '1',
                    'PROG_LIST_ID' =>$PROG_LIST_ID,
                    'SHIFT_ID' =>$SHIFT_ID,
                    'SESSION_ID'=>$session_id,
                    'ADMISSION_SESSION_ID'=>$admission_session_id,
                    'LIST_NO'=>1,
                    'CATEGORY_ID'=>$CATEGORY_ID,
                    'ACTIVE'=>1,
                    'ADMISSION_LIST_ID'=>74,
                    'TEST_ID'=>7,
                    'CPN'=>$CPN,
                    'IS_PROVISIONAL'=>'N',
                    'CARD_ID'=>$SEAT_NO,
                    'ROLL_NO_CODE'=>$ROL_NO_DIGIT,
                    'REMARKS' =>'OLD STUDENT'
                );
               // $this->legacy_db->insert('selection_list', $selection_list);
                if(!$this->legacy_db->insert('selection_list', $selection_list)){
                    prePrint("-------------------selection_list NOT ADDED --------------------");
                    prePrint($selection_list);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }


               // prePrint($selection_list);

                $candidate_account = array(
                    'APPLICATION_ID' => $application_id,
                    'USER_ID' => $user_id,
                    'ACTIVE'=>1,
                    'REMARKS'=>"OLD STUDENT",
                    'CANDIDATE_ID'=>$CANDIDATE_ID

                );
                //$this->legacy_db->insert('candidate_account', $candidate_account);
                if(!$this->legacy_db->insert('candidate_account', $candidate_account)){
                    prePrint("-------------------candidate_account NOT ADDED --------------------");
                    prePrint($candidate_account);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }




                $this->legacy_db->set('IS_ADD', '1');
                $this->legacy_db->where('CANDIDATE_ID', $CANDIDATE_ID);
                if(!$this->legacy_db->update('old_admission_data')){
                    prePrint("-------------------old_admission_data NOT UPDATED --------------------");
                    //($candidate_account);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    continue;
                }

                $this->legacy_db->trans_commit();
            }
            prePrint("-------------------TOTAL RECORD----------");
           echo  count($list_of_old_student);
        }
    }
    
    function get_all_old_challan_data(){
        $this->legacy_db = $this->load->database('admission_db',true);

        $this->legacy_db->select('*');
        $this->legacy_db->from("candidate_account ca");
        $this->legacy_db->join("old_admission_challan_data oacd",' ca.`CANDIDATE_ID` = oacd.`CANDIDATE_ID`');
        $this->legacy_db->join("selection_list sl",' ca.`APPLICATION_ID` = sl.`APPLICATION_ID`');
        $this->legacy_db->where("oacd.FEE_PROG_LIST_ID > 0");
        $this->legacy_db->where("oacd.IS_ADD = 0 ");
        $this->legacy_db->where("oacd.PART_ID > 0 ");
        $this->legacy_db->where("oacd.CHALLAN_TYPE_ID > 0 ");
        $this->legacy_db->where("oacd.BANK_ACCOUNT_ID > 0 ");
        $this->legacy_db->where("oacd.SEMESTER_ID > 0 ");
        $this->legacy_db->where("oacd.FEE_DEMERIT_ID > 0 ");
        $this->legacy_db->where("oacd.CHALLAN_NO > 0 ");
        $this->legacy_db->limit(5000);
        $list_of_challan =  $this->legacy_db->get()->result_array();
        //echo $this->legacy_db->last_query();
        foreach($list_of_challan as $challan_object){
            $APPLICATION_ID = $challan_object['APPLICATION_ID'];
           // $USER_ID = $challan_object['USER_ID'];
            $CANDIDATE_ID = $challan_object['CANDIDATE_ID'];
            $CHALLAN_NO = $challan_object['CHALLAN_NO'];
            $CHALLAN_TYPE_ID = $challan_object['CHALLAN_TYPE_ID'];
            $BANK_ACCOUNT_ID = $challan_object['BANK_ACCOUNT_ID'];
            $CHALLAN_AMOUNT = $challan_object['CHALLAN_AMOUNT'];
            $PAYABLE_AMOUNT = $challan_object['PAYABLE_AMOUNT'];
            $PAID_AMOUNT = $challan_object['PAID_AMOUNT'];
            $PAID_DATE = $VALID_UPTO = $challan_object['DATE'];
            $PART_ID = $challan_object['PART_ID'];
            $SEMESTER_ID = $challan_object['SEMESTER_ID'];
            $FEE_PROG_LIST_ID = $challan_object['FEE_PROG_LIST_ID'];
            //$FEE_DEMERIT_ID = $challan_object['FEE_DEMERIT_ID'];
            $ID = $challan_object['ID'];
            $ACCOUNT_ID = $challan_object['ACCOUNT_ID'];
            $SELECTION_LIST_ID = $challan_object['SELECTION_LIST_ID'];
            $TEST_ID = $challan_object['TEST_ID'];
            $CATEGORY_ID = $challan_object['CATEGORY_ID'];
            $IS_MERIT='Y';
               switch($CATEGORY_ID){
                   CASE  13:$IS_MERIT = 'N';break;
                   CASE  21:$IS_MERIT = 'N';break;
                   CASE  24: $IS_MERIT = 'N';break;
                   CASE  29: $IS_MERIT = 'N';break;
                   DEFAULT: $IS_MERIT = 'Y';
               }
            $this->legacy_db->trans_begin();

            $fee_challan = array(
                'CHALLAN_NO' => $CHALLAN_NO,
                'APPLICATION_ID' => $APPLICATION_ID,
                'CHALLAN_TYPE_ID'=>$CHALLAN_TYPE_ID,
                'BANK_ACCOUNT_ID'=>$BANK_ACCOUNT_ID,
                'SELECTION_LIST_ID'=>$SELECTION_LIST_ID,
                'CHALLAN_AMOUNT'=>$CHALLAN_AMOUNT,
                'PAYABLE_AMOUNT'=>$PAYABLE_AMOUNT,
                'DATETIME'=>date('Y-m-d'),
                'REMARKS'=>"OLD STUDENT",
                'ADMIN_USER_ID'=>158729,
                'PART_ID'=>$PART_ID,
                'SEMESTER_ID'=>$SEMESTER_ID,
                'FEE_PROG_LIST_ID'=>$FEE_PROG_LIST_ID,
            );

            if(!$this->legacy_db->insert('fee_challan', $fee_challan)){
                prePrint("-------------------fee_challan NOT ADDED --------------------");
                prePrint($fee_challan);
                prePrint("------------------------------------------------------");
                $this->legacy_db->trans_rollback();
                $this->legacy_db->set('IS_ADD', '2');
                $this->legacy_db->where('ID', $ID);
                if(!$this->legacy_db->update('old_admission_challan_data')){
                    prePrint("-------------------old_admission_challan_data NOT UPDATED fee_challan --------------------");
                    //($candidate_account);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    //continue;
                }
                
                continue;
            }

            $fee_ledger = array(
                'CHALLAN_NO' => $CHALLAN_NO,
                'CHALLAN_TYPE_ID'=>$CHALLAN_TYPE_ID,
                'BANK_ACCOUNT_ID'=>$BANK_ACCOUNT_ID,
                'SELECTION_LIST_ID'=>$CANDIDATE_ID,
                'CHALLAN_AMOUNT'=>$CHALLAN_AMOUNT,
                'PAYABLE_AMOUNT'=>$PAYABLE_AMOUNT,
                'REMARKS'=>"OLD STUDENT",
                'FEE_PROG_LIST_ID'=>$FEE_PROG_LIST_ID,
                'ACCOUNT_ID'=>$ACCOUNT_ID,
                'PAID_AMOUNT'=>$PAID_AMOUNT,
                'DATE'=>$PAID_DATE,
                'IS_YES'=>'Y',
                'IS_MERIT'=>$IS_MERIT,
                'TEST_ID'=>$TEST_ID,
                'SELECTION_LIST_ID'=>$SELECTION_LIST_ID

            );

            if(!$this->legacy_db->insert('fee_ledger', $fee_ledger)){
                prePrint("-------------------fee_ledger NOT ADDED --------------------");
                prePrint($fee_challan);
                prePrint("------------------------------------------------------");
                $this->legacy_db->trans_rollback();
                 $this->legacy_db->set('IS_ADD', '2');
                $this->legacy_db->where('ID', $ID);
                if(!$this->legacy_db->update('old_admission_challan_data')){
                    prePrint("-------------------old_admission_challan_data NOT UPDATED fee_ledger --------------------");
                    //($candidate_account);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    //continue;
                }
                continue;
            }


            $this->legacy_db->set('IS_ADD', '1');
            $this->legacy_db->where('ID', $ID);
            if(!$this->legacy_db->update('old_admission_challan_data')){
                prePrint("-------------------old_admission_challan_data NOT UPDATED --------------------");
                //($candidate_account);
                prePrint("------------------------------------------------------");
                $this->legacy_db->trans_rollback();
                continue;
            }

            $this->legacy_db->trans_commit();


        }
        prePrint("-------------------TOTAL RECORD----------");
        echo  count($list_of_challan);


    }
    
    function get_all_old_challan_mission_data(){
        $this->legacy_db = $this->load->database('admission_db',true);
/*SELECT 
 concat( mod(s.YEAR,2000),sl.ROLL_NO_CODE,'000',oad.`CHALLAN_NO`) 
FROM
  `old_admission_challan_data` oad 
  JOIN candidate_account ca 
    ON (
      oad.`CANDIDATE_ID` = ca.`CANDIDATE_ID`
    )
  JOIN selection_list sl on (sl.APPLICATION_ID  = ca.APPLICATION_ID)
  join admission_session ass on (ass.ADMISSION_SESSION_ID = sl.ADMISSION_SESSION_ID)
  join sessions s on (ass.SESSION_ID = s.SESSION_ID)
WHERE oad.`IS_ADD` = 2*/
        $this->legacy_db->select('oacd.*,ca.*,sl.*,s.YEAR');
        $this->legacy_db->from("candidate_account ca");
        $this->legacy_db->join("old_admission_challan_data oacd",' ca.`CANDIDATE_ID` = oacd.`CANDIDATE_ID`');
        $this->legacy_db->join("selection_list sl",' ca.`APPLICATION_ID` = sl.`APPLICATION_ID`');
        $this->legacy_db->join("admission_session ass",' ass.`ADMISSION_SESSION_ID` = sl.`ADMISSION_SESSION_ID`');
        $this->legacy_db->join("sessions s",' s.`SESSION_ID` = ass.`SESSION_ID`');
        $this->legacy_db->where("oacd.FEE_PROG_LIST_ID > 0");
        $this->legacy_db->where("oacd.IS_ADD = 2 ");
        $this->legacy_db->where("oacd.PART_ID > 0 ");
        $this->legacy_db->where("oacd.CHALLAN_TYPE_ID > 0 ");
        $this->legacy_db->where("oacd.BANK_ACCOUNT_ID > 0 ");
        $this->legacy_db->where("oacd.SEMESTER_ID > 0 ");
        $this->legacy_db->where("oacd.FEE_DEMERIT_ID > 0 ");
        $this->legacy_db->where("oacd.CHALLAN_NO > 0 ");
        $this->legacy_db->limit(5000);
        $list_of_challan =  $this->legacy_db->get()->result_array();
        //echo $this->legacy_db->last_query();
        foreach($list_of_challan as $challan_object){
            $APPLICATION_ID = $challan_object['APPLICATION_ID'];
           // $USER_ID = $challan_object['USER_ID'];
            $CANDIDATE_ID = $challan_object['CANDIDATE_ID'];
            $CHALLAN_NO = $challan_object['CHALLAN_NO'];
            $CHALLAN_TYPE_ID = $challan_object['CHALLAN_TYPE_ID'];
            $BANK_ACCOUNT_ID = $challan_object['BANK_ACCOUNT_ID'];
            $CHALLAN_AMOUNT = $challan_object['CHALLAN_AMOUNT'];
            $PAYABLE_AMOUNT = $challan_object['PAYABLE_AMOUNT'];
            $PAID_AMOUNT = $challan_object['PAID_AMOUNT'];
            $PAID_DATE = $VALID_UPTO = $challan_object['DATE'];
            $PART_ID = $challan_object['PART_ID'];
            $SEMESTER_ID = $challan_object['SEMESTER_ID'];
            $FEE_PROG_LIST_ID = $challan_object['FEE_PROG_LIST_ID'];
            //$FEE_DEMERIT_ID = $challan_object['FEE_DEMERIT_ID'];
            $ID = $challan_object['ID'];
            $ACCOUNT_ID = $challan_object['ACCOUNT_ID'];
            $SELECTION_LIST_ID = $challan_object['SELECTION_LIST_ID'];
            $TEST_ID = $challan_object['TEST_ID'];
            $CATEGORY_ID = $challan_object['CATEGORY_ID'];
            $YEAR = $challan_object['YEAR'];
            $ROLL_NO_CODE = $challan_object['ROLL_NO_CODE'];
            //$pre_fix = ($YEAR%2000).$ROLL_NO_CODE;
            $pre_fix = ($YEAR%2000)."18".$ROLL_NO_CODE;
            
            $addition_zero = 13-strlen($pre_fix);
            //echo $addition_zero;
            $new_challan_no = $pre_fix.str_pad($CHALLAN_NO,$addition_zero,"0",STR_PAD_LEFT);
            $IS_MERIT='Y';
               switch($CATEGORY_ID){
                   CASE  13:$IS_MERIT = 'N';break;
                   CASE  21:$IS_MERIT = 'N';break;
                   CASE  24: $IS_MERIT = 'N';break;
                   CASE  29: $IS_MERIT = 'N';break;
                   DEFAULT: $IS_MERIT = 'Y';
               }
            $this->legacy_db->trans_begin();

            $fee_challan = array(
                'CHALLAN_NO' => $new_challan_no,
                'APPLICATION_ID' => $APPLICATION_ID,
                'CHALLAN_TYPE_ID'=>$CHALLAN_TYPE_ID,
                'BANK_ACCOUNT_ID'=>$BANK_ACCOUNT_ID,
                'SELECTION_LIST_ID'=>$SELECTION_LIST_ID,
                'CHALLAN_AMOUNT'=>$CHALLAN_AMOUNT,
                'PAYABLE_AMOUNT'=>$PAYABLE_AMOUNT,
                'DATETIME'=>date('Y-m-d'),
                'REMARKS'=>"OLD STUDENT",
                'ADMIN_USER_ID'=>158729,
                'PART_ID'=>$PART_ID,
                'SEMESTER_ID'=>$SEMESTER_ID,
                'FEE_PROG_LIST_ID'=>$FEE_PROG_LIST_ID,
            );
            //prePrint($fee_challan);
            //continue;
            if(!$this->legacy_db->insert('fee_challan', $fee_challan)){
                prePrint("-------------------fee_challan NOT ADDED --------------------");
                prePrint($fee_challan);
                prePrint("------------------------------------------------------");
                $this->legacy_db->trans_rollback();
                $this->legacy_db->set('IS_ADD', '2');
                $this->legacy_db->where('ID', $ID);
                if(!$this->legacy_db->update('old_admission_challan_data')){
                    prePrint("-------------------old_admission_challan_data NOT UPDATED fee_challan --------------------");
                    //($candidate_account);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    //continue;
                }
                
                continue;
            }

            $fee_ledger = array(
                'CHALLAN_NO' => $new_challan_no,
                'CHALLAN_TYPE_ID'=>$CHALLAN_TYPE_ID,
                'BANK_ACCOUNT_ID'=>$BANK_ACCOUNT_ID,
                'SELECTION_LIST_ID'=>$CANDIDATE_ID,
                'CHALLAN_AMOUNT'=>$CHALLAN_AMOUNT,
                'PAYABLE_AMOUNT'=>$PAYABLE_AMOUNT,
                'REMARKS'=>"OLD STUDENT",
                'FEE_PROG_LIST_ID'=>$FEE_PROG_LIST_ID,
                'ACCOUNT_ID'=>$ACCOUNT_ID,
                'PAID_AMOUNT'=>$PAID_AMOUNT,
                'DATE'=>$PAID_DATE,
                'IS_YES'=>'Y',
                'IS_MERIT'=>$IS_MERIT,
                'TEST_ID'=>$TEST_ID,
                'SELECTION_LIST_ID'=>$SELECTION_LIST_ID

            );

            if(!$this->legacy_db->insert('fee_ledger', $fee_ledger)){
                prePrint("-------------------fee_ledger NOT ADDED --------------------");
                prePrint($fee_challan);
                prePrint("------------------------------------------------------");
                $this->legacy_db->trans_rollback();
                 $this->legacy_db->set('IS_ADD', '2');
                $this->legacy_db->where('ID', $ID);
                if(!$this->legacy_db->update('old_admission_challan_data')){
                    prePrint("-------------------old_admission_challan_data NOT UPDATED fee_ledger --------------------");
                    //($candidate_account);
                    prePrint("------------------------------------------------------");
                    $this->legacy_db->trans_rollback();
                    //continue;
                }
                continue;
            }


            $this->legacy_db->set('IS_ADD', '1');
            $this->legacy_db->where('ID', $ID);
            if(!$this->legacy_db->update('old_admission_challan_data')){
                prePrint("-------------------old_admission_challan_data NOT UPDATED --------------------");
                //($candidate_account);
                prePrint("------------------------------------------------------");
                $this->legacy_db->trans_rollback();
                continue;
            }

            $this->legacy_db->trans_commit();


        }
        prePrint("-------------------TOTAL RECORD----------");
        echo  count($list_of_challan);


    }
    
    function get_enrolled_candidate_category_wise(){
        	$this->legacy_db = $this->load->database('admission_db',true);
       $sql =  "SELECT 
              COUNT(DISTINCT(`sl`.`SELECTION_LIST_ID`)) AS TOTAL,
              `sl`.`CATEGORY_NAME` AS `CATEGORY_NAME`,
              `sl`.`CAMPUS_NAME` AS `CAMPUS_NAME`,
              `sl`.`SHIFT_ID` AS `SHIFT_ID`,
              `sl`.`PROGRAM_TITLE` AS `PROGRAM_TITLE`
            FROM `admissio_itsc`.`sessions` `s` 
            
            JOIN `admissio_itsc`.`shift_program_mapping` `spm`
            JOIN `admissio_itsc`.`campus` `c`
            JOIN `admissio_itsc`.`admission_session` `adms`
            JOIN `admissio_itsc`.`applications` `a`
            JOIN `admissio_itsc`.`selection_list` `sl`
            JOIN `admissio_itsc`.`candidate_account` `ca`
            JOIN `admissio_itsc`.`fee_ledger` `fl`
            JOIN `admissio_itsc`.`users_reg` `ur` ON (`ur`.`USER_ID` = `a`.`USER_ID`)
            
            WHERE `c`.`CAMPUS_ID` = `adms`.`CAMPUS_ID` 
              AND `s`.`SESSION_ID` = `adms`.`SESSION_ID` 
              AND `adms`.`ADMISSION_SESSION_ID` = `a`.`ADMISSION_SESSION_ID` 
              AND `spm`.`PROG_LIST_ID` = `sl`.`PROG_LIST_ID` 
              AND `spm`.`SHIFT_ID` = `sl`.`SHIFT_ID` 
              AND `spm`.`CAMPUS_ID` = `c`.`CAMPUS_ID` 
              AND `spm`.`PROGRAM_TYPE_ID` = `adms`.`PROGRAM_TYPE_ID` 
              AND `a`.`APPLICATION_ID` = `sl`.`APPLICATION_ID` 
              AND `sl`.`ACTIVE` = 1 
              AND `sl`.`IS_PROVISIONAL` = 'N' 
              AND `spm`.`SHIFT_ID` = `sl`.`SHIFT_ID` 
              AND `spm`.`PROG_LIST_ID` = `sl`.`PROG_LIST_ID` 
              AND `a`.`APPLICATION_ID` = `ca`.`APPLICATION_ID` 
              AND `ca`.`ACCOUNT_ID` = `fl`.`ACCOUNT_ID` 
              AND `ca`.`ACTIVE` = 1 
              AND `sl`.`SELECTION_LIST_ID` = `fl`.`SELECTION_LIST_ID` 
              AND `fl`.`IS_YES` = 'Y' 
              AND `fl`.`CHALLAN_TYPE_ID` = 1 
              AND `sl`.`ROLL_NO_CODE` > 0 
              AND `a`.`ADMISSION_SESSION_ID` BETWEEN 15 AND 21 
              AND c.CAMPUS_ID = 1
            GROUP BY `sl`.`PROG_LIST_ID`,  `sl`.`CATEGORY_ID` ";
            $query = $this->legacy_db->query($sql);
            return $query->result_array();

    }
    function get_all_cateorgy(){
        	$this->legacy_db = $this->load->database('admission_db',true);
              $sql =  "SELECT * from category";
            $query = $this->legacy_db->query($sql);
            return $query->result_array();
    }
}
