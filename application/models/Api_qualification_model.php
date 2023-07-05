<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/15/2020
 * Time: 2:16 PM
 */

class Api_qualification_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        $this->legacy_db = $this->load->database('admission_db',true);
    }
    function getAllDegreeProgram()
    {
        
        return $this->db->get('degree_program')->result_array();
    }

    function getDegreeProgramById($degree_id)
    {
        $degree_id = isValidData($degree_id);
        if(!$degree_id){
            $degree_id=0;
        }

        $this->db->where('DEGREE_ID',$degree_id);

        return $this->db->get('degree_program')->row_array();
    }

    function getAllInstitute()
    {
        $this->db->where('ACTIVE',1);
        $this->db->order_by('INSTITUTE_NAME');
        return $this->db->get('institute')->result_array();
    }

    function getInstituteByOrgId($org_id)
    {

        $this->db->where('PARENT_ID',$org_id);
        $this->db->where('INSTITUTE_TYPE_ID',2);
        $this->db->order_by('INSTITUTE_NAME');
        return $this->db->get('institute')->result_array();
    }

    function getAllOrganization()
    {

        $this->db->where('IS_INST','Y');
        $this->db->where('INSTITUTE_TYPE_ID',1);
         $this->db->where('ACTIVE',1);
        $this->db->order_by('INSTITUTE_NAME');
        return $this->db->get('institute')->result_array();
    }

    function getAllDiscipline()
    {
        return $this->db->get('discipline')->result_array();
    }

    function getDisciplineByDegreeId($degree_id)
    {
        $degree_id = isValidData($degree_id);
        if(!$degree_id){
            $degree_id=0;
        }

        $this->db->where('DEGREE_ID',$degree_id);
        $this->db->where('ACTIVE',1);
        $this->db->order_by('DISCIPLINE_NAME');
        return $this->db->get('discipline')->result_array();
    }

    function getDisciplineById($discipline_id)
    {
        $discipline_id = isValidData($discipline_id);
        if(!$discipline_id){
            $discipline_id=0;
        }

        $this->db->where('DISCIPLINE_ID',$discipline_id);
        return $this->db->get('discipline')->row_array();
    }

    function getQualificatinByUserId($user_id,$application_id){

        $this->db->select('q.*,d.DEGREE_ID,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME INSTITUTE,o.INSTITUTE_NAME ORGANIZATION');
        $this->db->from('qualifications q');
        $this->db->join('institute AS i', 'q.INSTITUTE_ID = i.INSTITUTE_ID','LEFT');
        $this->db->join('institute AS o', 'q.ORGANIZATION_ID = o.INSTITUTE_ID');
        $this->db->join('discipline AS d', 'q.DISCIPLINE_ID = d.DISCIPLINE_ID');
        $this->db->join('degree_program AS p', 'd.DEGREE_ID = p.DEGREE_ID');
        $this->db->where('q.USER_ID',$user_id);
         $this->db->where('q.APPLICATION_ID',$application_id);
        $this->db->where('q.ACTIVE',1);
        $this->db->order_by('p.DEGREE_ID', 'DESC');
        $qulification_list = $this->db->get()->result_array();
        return $qulification_list;

    }

    function getQualificationByUserIdAndQulificationId($USER_ID,$qul_id,$application_id){
        $qualificationList = $this->getQualificatinByUserId($USER_ID,$application_id);
        foreach ($qualificationList as $qualification){
            if($qualification['QUALIFICATION_ID']==$qul_id){
                return $qualification;
            }
        }
        return false;
    }
    
    function getQualificatinByUserIdAndDegreeId($user_id,$degree_id,$application_id){

        $this->db->select('q.*,d.DEGREE_ID,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME INSTITUTE,o.INSTITUTE_NAME ORGANIZATION');
        $this->db->from('qualifications q');
        $this->db->join('institute AS i', 'q.INSTITUTE_ID = i.INSTITUTE_ID','LEFT');
        $this->db->join('institute AS o', 'q.ORGANIZATION_ID = o.INSTITUTE_ID');
        $this->db->join('discipline AS d', 'q.DISCIPLINE_ID = d.DISCIPLINE_ID');
        $this->db->join('degree_program AS p', 'd.DEGREE_ID = p.DEGREE_ID');
         $this->db->where('q.APPLICATION_ID',$application_id);
        $this->db->where('q.USER_ID',$user_id);
        $this->db->where('d.DEGREE_ID',$degree_id);
        $this->db->where('q.ACTIVE',1);
        $this->db->order_by('p.DEGREE_ID', 'DESC');
        $qulification_list = $this->db->get()->result_array();
        return $qulification_list;

    }

    function addQualification($form_array,$application_id = null){
        //load loging model
        $this->load->model('log_model');
        $form_array['APPLICATION_ID'] = $application_id;
        $this->db->trans_begin();
        $this->legacy_db->trans_begin();
        $this->db->insert('qualifications', $form_array);

        //this code is use for loging
        $QUERY = $this->db->last_query();
        $id = $this->db->insert_id();
        $form_array['QUALIFICATION_ID']= $id;
        if($this->db->affected_rows() != 1){

            $this->db->trans_rollback();

            //this code is use for loging
            $this->log_model->create_log(0,$id,'','',"ADD_QUALIFICATION",'qualifications',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_QUALIFICATION","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'qualifications');

            return false;
        }else {
            $this->legacy_db->insert('qualifications', $form_array);
            if($this->legacy_db->affected_rows() != 1){
                $this->db->trans_rollback();
                $this->legacy_db->trans_rollback();

                //this code is use for loging
                $this->log_model->create_log(0,$id,'','',"ADD_QUALIFICATION",'qualifications',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_QUALIFICATION","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'qualifications');

                return false;
            }else {
                //  if($application_id!=null){
                //     $form_array['APPLICATION_ID'] = $application_id;
                //     unset($form_array['QUALIFICATION_ID']);
                //     $this->legacy_db->insert('qualifications_applications', $form_array);
                // }
               
                $this->db->trans_commit();
                $this->legacy_db->trans_commit();
                //this code is use for loging
                $this->db->where('QUALIFICATION_ID', $id);
                $CURRENT_RECORD = $this->db->get('qualifications')->row_array();
                $this->log_model->create_log(0, $id, '', $CURRENT_RECORD, "ADD_QUALIFICATION", 'qualifications', 11, $form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_QUALIFICATION", "SUCCESS", $QUERY, 'CANDIDATE', $form_array['USER_ID'], $CURRENT_RECORD, "", $id, 'qualifications');


                return true;
            }
        }
    }

    function updateQualification($qual_id,$form_array,$application_id = null){
        //load loging model
        $this->load->model('log_model');
        $this->db->where('QUALIFICATION_ID',$qual_id);
        $PRE_RECORD =  $this->db->get('qualifications')->row_array();


        $this->db->trans_begin();
        $this->db->where('QUALIFICATION_ID',$qual_id);
        $this->db->update('qualifications',$form_array);
        $this->legacy_db->trans_begin();
        $this->legacy_db->where('QUALIFICATION_ID',$qual_id);
        $this->legacy_db->update('qualifications',$form_array);

        //this code is use for loging
        $QUERY = $this->db->last_query();
        // if($application_id!=null){
        //     $this->legacy_db->where('APPLICATION_ID',$application_id);
        //     $this->legacy_db->where('ACTIVE',1);
        //     $this->legacy_db->where('DISCIPLINE_ID',$form_array['DISCIPLINE_ID']);
        //     $RECORD =  $this->legacy_db->get('qualifications_applications')->row_array();
        //     if($RECORD){
        //         $this->legacy_db->where('APPLICATION_ID',$application_id);
        //         $this->legacy_db->where('ACTIVE',1);
        //         $this->legacy_db->where('DISCIPLINE_ID',$form_array['DISCIPLINE_ID']);
        //         $this->legacy_db->update('qualifications_applications',$form_array);
        //     }else{
        //         $form_array['APPLICATION_ID'] = $application_id;
        //         $this->legacy_db->insert('qualifications_applications', $form_array);
             
        //     }
 
        // }

        if($this->db->affected_rows() ==1&&$this->legacy_db->affected_rows() ==1){
            $this->db->trans_commit();
            $this->legacy_db->trans_commit();

            //this code is use for loging
            $this->db->where('QUALIFICATION_ID',$qual_id);
            $CURRENT_RECORD =  $this->db->get('qualifications')->row_array();
            $this->log_model->create_log($qual_id,$qual_id,$PRE_RECORD,$CURRENT_RECORD,"EDIT_QUALIFICATION",'qualifications',12,$form_array['USER_ID']);
            $this->log_model->itsc_log("EDIT_QUALIFICATION","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$qual_id,'qualifications');

            return 1;
        }elseif($this->db->affected_rows() ==0&&$this->legacy_db->affected_rows() ==0){
            $this->db->trans_commit();
            $this->legacy_db->trans_commit();
            //this code is use for loging
            $this->db->where('QUALIFICATION_ID',$qual_id);
            $CURRENT_RECORD =  $this->db->get('qualifications')->row_array();
            
            $this->log_model->create_log($qual_id,$qual_id,$PRE_RECORD,$CURRENT_RECORD,"EDIT_QUALIFICATION",'qualifications',12,$form_array['USER_ID']);
            $this->log_model->itsc_log("EDIT_QUALIFICATION","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$qual_id,'qualifications');

            return 0;
        }else{
            $this->db->trans_rollback();
            $this->legacy_db->trans_rollback();

            //this code is use for loging
            $this->db->where('QUALIFICATION_ID',$qual_id);
            $CURRENT_RECORD =  $this->db->get('qualifications')->row_array();
            $this->log_model->create_log($qual_id,$qual_id,$PRE_RECORD,$CURRENT_RECORD,"EDIT_QUALIFICATION",'qualifications',12,$form_array['USER_ID']);
            $this->log_model->itsc_log("EDIT_QUALIFICATION","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$qual_id,'qualifications');

            return -1;
        }
    }

    function deleteQualification($user_id,$qualification_id,$application_id=null){
        //load loging model
        $this->load->model('log_model');
        $this->db->where('QUALIFICATION_ID',$qualification_id);
        $PRE_RECORD =  $this->db->get('qualifications')->row_array();


        $formArray = array('ACTIVE'=>0);

        $this->db->trans_begin();
        $this->db->where('QUALIFICATION_ID',$qualification_id);
        $this->db->where('USER_ID',$user_id);
        $this->db->where('ACTIVE',1);
        $this->db->update('qualifications',$formArray);

        $this->legacy_db->trans_begin();
        $this->legacy_db->where('QUALIFICATION_ID',$qualification_id);
        $this->legacy_db->where('USER_ID',$user_id);
        $this->legacy_db->where('ACTIVE',1);
        $this->legacy_db->update('qualifications',$formArray);
       
      
        //this code is use for loging
        $QUERY = $this->db->last_query();


        if($this->db->affected_rows() != 1&&$this->legacy_db->affected_rows() != 1){
            $this->db->trans_rollback();
            $this->legacy_db->trans_rollback();
            //this code is use for loging
            $this->db->where('QUALIFICATION_ID',$qualification_id);
            $CURRENT_RECORD =  $this->db->get('qualifications')->row_array();
            $this->log_model->create_log($qualification_id,$qualification_id,$PRE_RECORD,$CURRENT_RECORD,"DELETE_QUALIFICATION",'qualifications',13,$user_id);
            $this->log_model->itsc_log("DELETE_QUALIFICATION","FAILED",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$qualification_id,'qualifications');

            return false;
        }else {
            $this->legacy_db->trans_commit();
            $this->db->trans_commit();

            //this code is use for loging
            $this->db->where('QUALIFICATION_ID',$qualification_id);
            $CURRENT_RECORD =  $this->db->get('qualifications')->row_array();
            $this->log_model->create_log($qualification_id,$qualification_id,$PRE_RECORD,$CURRENT_RECORD,"DELETE_QUALIFICATION",'qualifications',13,$user_id);
            $this->log_model->itsc_log("EDIT_QUALIFICATION","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$qualification_id,'qualifications');

            return true;
        }
    }

    function addInstitute($form_array){
        //load loging model
        $this->load->model('log_model');

        $this->db->trans_begin();
        $this->db->insert('institute', $form_array);

        //this code is use for loging
        $QUERY = $this->db->last_query();
        $id = $this->db->insert_id();


        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();

            //this code is use for loging
            $this->log_model->create_log($id,$id,'','',"ADD_INSTITUTE",'institute',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_INSTITUTE","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'institute');

            return false;
        }else {
            $this->db->trans_commit();
            //this code is use for loging

            $this->db->where('INSTITUTE_ID',$id);
            $CURRENT_RECORD =  $this->db->get('institute')->row_array();
            $this->log_model->create_log($id,$id,'',$CURRENT_RECORD,"ADD_INSTITUTE",'institute',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_INSTITUTE","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'institute');

            return true;
        }
    }
    
    function getQualificatinBySeatNoAndYear($seat_no=0,$year=0,$degree_id=0,$board_id=0,$application_id=0){

		$this->db->select('u.CNIC_NO,q.*,d.DEGREE_ID,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME INSTITUTE,o.INSTITUTE_NAME ORGANIZATION');
		$this->db->from('users_reg u');
		$this->db->join('qualifications q','u.USER_ID=q.USER_ID');
		$this->db->join('institute AS i', 'q.INSTITUTE_ID = i.INSTITUTE_ID');
		$this->db->join('institute AS o', 'q.ORGANIZATION_ID = o.INSTITUTE_ID');
		$this->db->join('discipline AS d', 'q.DISCIPLINE_ID = d.DISCIPLINE_ID');
		$this->db->join('degree_program AS p', 'd.DEGREE_ID = p.DEGREE_ID');
		if ($seat_no>0)$this->db->where('q.ROLL_NO',$seat_no);
		if ($year>0)$this->db->where('q.PASSING_YEAR',$year);
		if ($degree_id>0)$this->db->where('d.DEGREE_ID',$degree_id);
		if ($board_id>0)$this->db->where('q.ORGANIZATION_ID',$board_id);
		if ($application_id>0)$this->db->where('q.APPLICATION_ID',$application_id);
		$this->db->where('q.ACTIVE',1);
//		$this->db->order_by('p.DEGREE_ID', 'DESC');
		$qulification_list = $this->db->get()->result_array();
//		echo  $this->db->last_query();
//		exit();
		return $qulification_list;
	}
}