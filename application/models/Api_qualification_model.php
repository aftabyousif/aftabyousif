<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/15/2020
 * Time: 2:16 PM
 */

class Api_qualification_model extends CI_Model
{
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

    function getQualificatinByUserId($user_id){

        $this->db->select('q.*,d.DEGREE_ID,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME INSTITUTE,o.INSTITUTE_NAME ORGANIZATION');
        $this->db->from('qualifications q');
        $this->db->join('institute AS i', 'q.INSTITUTE_ID = i.INSTITUTE_ID');
        $this->db->join('institute AS o', 'q.ORGANIZATION_ID = o.INSTITUTE_ID');
        $this->db->join('discipline AS d', 'q.DISCIPLINE_ID = d.DISCIPLINE_ID');
        $this->db->join('degree_program AS p', 'd.DEGREE_ID = p.DEGREE_ID');
        $this->db->where('q.USER_ID',$user_id);
        $this->db->where('q.ACTIVE',1);
        $this->db->order_by('p.DEGREE_ID', 'DESC');
        $qulification_list = $this->db->get()->result_array();
        return $qulification_list;

    }

    function getQualificationByUserIdAndQulificationId($USER_ID,$qul_id){
        $qualificationList = $this->getQualificatinByUserId($USER_ID);
        foreach ($qualificationList as $qualification){
            if($qualification['QUALIFICATION_ID']==$qul_id){
                return $qualification;
            }
        }
        return false;
    }
    function getQualificatinByUserIdAndDegreeId($user_id,$degree_id){

        $this->db->select('q.*,d.DEGREE_ID,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME INSTITUTE,o.INSTITUTE_NAME ORGANIZATION');
        $this->db->from('qualifications q');
        $this->db->join('institute AS i', 'q.INSTITUTE_ID = i.INSTITUTE_ID');
        $this->db->join('institute AS o', 'q.ORGANIZATION_ID = o.INSTITUTE_ID');
        $this->db->join('discipline AS d', 'q.DISCIPLINE_ID = d.DISCIPLINE_ID');
        $this->db->join('degree_program AS p', 'd.DEGREE_ID = p.DEGREE_ID');
        $this->db->where('q.USER_ID',$user_id);
        $this->db->where('d.DEGREE_ID',$degree_id);
        $this->db->where('q.ACTIVE',1);
        $this->db->order_by('p.DEGREE_ID', 'DESC');
        $qulification_list = $this->db->get()->result_array();
        return $qulification_list;

    }


    function addQualification($form_array){
        $this->db->trans_begin();
        $this->db->insert('qualifications', $form_array);
        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
            }
    }

    function updateQualification($qual_id,$form_array){
        $this->db->trans_begin();
        $this->db->where('QUALIFICATION_ID',$qual_id);
        $this->db->update('qualifications',$form_array);

        if($this->db->affected_rows() ==1){
            $this->db->trans_commit();
            return 1;
        }elseif($this->db->affected_rows() ==0){
            $this->db->trans_commit();
            return 0;
        }else{
            $this->db->trans_rollback();
            return -1;
        }
    }

    function deleteQualification($user_id,$qualification_id){
        $this->db->trans_begin();
        $formArray = array('ACTIVE'=>0);
        $this->db->where('QUALIFICATION_ID',$qualification_id);
        $this->db->where('USER_ID',$user_id);
        $this->db->where('ACTIVE',1);
        $this->db->update('qualifications',$formArray);
        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }

    function addInstitute($form_array){
        $this->db->trans_begin();
        $this->db->insert('institute', $form_array);
        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();
            return false;
        }else {
            $this->db->trans_commit();
            return true;
        }
    }
}