<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/10/2020
 * Time: 10:54 PM
 */

class User_model extends CI_model
{
    function getUserByCnicAndPassword($cnic,$password){
            $this->db->where('CNIC_NO',$cnic);
            $this->db->where('PASSWORD',$password);
            $user = $this->db->get('users_reg')->row_array();
            return $user;

        }
    function getUserByPassportAndPassword($passport,$password){
        $this->db->where('PASSPORT_NO',$passport);
        $this->db->where('PASSWORD',$password);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }
    function getUserByCnic($cnic){
        $this->db->where('CNIC_NO',$cnic);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }
    
	function getUserById($user_id){
        $this->db->where('USER_ID',$user_id);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }
	
	// JOIN QUERY TO GET USER ROLE FROM ROLE AND ROLE_RELATION TABLE
	// SELECT r.`ROLE_NAME`,r.`ACTIVE`, rr.`USER_ID`, r.`KEYWORD` from role r, role_relation rr where rr.USER_ID=93774 AND r.ROLE_ID=rr.ROLE_ID
	function getUserRoleByUserId($user_id){
		$this->db->select('r.`ROLE_NAME`,r.`ACTIVE`, rr.`USER_ID`, r.`KEYWORD`');
		$this->db->from('role_relation rr');
		$this->db->join('role AS r', 'rr.ROLE_ID = r.ROLE_ID');
		$this->db->where('rr.USER_ID',$user_id);
		$this->db->where('r.KEYWORD','UG_A');
        $this->db->where('r.ACTIVE','1');
		$user = $this->db->get()->row_array();
        return $user; 

    }

    function getQulificatinByUserId($user_id){
        $this->db->select('q.*,p.DEGREE_TITLE,d.DISCIPLINE_NAME,i.INSTITUTE_NAME INSTITUTE,o.INSTITUTE_NAME ORGANIZATION');
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
	
    function getUserByPassport($passport){
        $this->db->where('PASSPORT_NO',$passport);
        $user = $this->db->get('users_reg')->row_array();
        return $user;

    }

	function changePasswordByCNIC($cnic,$password){
		$formArray = array('PASSWORD'=>$password);
		$this->db->trans_begin();
//		$this->db->where('PASSWORD',$curr_password);
		$this->db->where('CNIC_NO',$cnic);
		$this->db->update('users_reg',$formArray);
		if($this->db->affected_rows() ==1){
			$this->db->trans_commit();
			return true;
		}else{
			$this->db->trans_rollback();
			return false;
		}
	}

    function changePassword($user_id,$curr_password,$password){
        //load loging model
        $this->load->model('log_model');
        $this->db->where('USER_ID',$user_id);
        $PRE_RECORD =  $this->db->get('users_reg')->row_array();


        $formArray = array('PASSWORD'=>$password);
        $this->db->trans_begin();
        $this->db->where('PASSWORD',$curr_password);
        $this->db->where('USER_ID',$user_id);
        $this->db->update('users_reg',$formArray);

        //this code is use for loging
        $QUERY = $this->db->last_query();

        if($this->db->affected_rows() ==1){
            $this->db->trans_commit();
            //this code is use for loging
            $this->db->where('USER_ID',$user_id);
            $CURRENT_RECORD =  $this->db->get('users_reg')->row_array();
            $this->log_model->create_log($user_id,$user_id,$PRE_RECORD,$CURRENT_RECORD,"CHANGE_PASSWORD_SUCCESS",'users_reg',24,$user_id);
            $this->log_model->itsc_log("CHANGE_PASSWORD","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$user_id,'users_reg');

            return true;
        }else{
            $this->db->trans_rollback();
            //this code is use for loging
            $this->db->where('USER_ID',$user_id);
            $CURRENT_RECORD =  $this->db->get('users_reg')->row_array();
            $this->log_model->create_log($user_id,$user_id,$PRE_RECORD,$CURRENT_RECORD,"CHANGE_PASSWORD_FAILED",'users_reg',24,$user_id);
            $this->log_model->itsc_log("CHANGE_PASSWORD","FAILED",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$user_id,'users_reg');

            return false;
        }

    }

    function updateUserById($user_id,$formArray){
        //load loging model
        $this->load->model('log_model');
        $this->db->where('USER_ID',$user_id);
        $PRE_RECORD =  $this->db->get('users_reg')->row_array();

            $this->db->trans_begin();
            $this->db->where('USER_ID',$user_id);
            $this->db->update('users_reg',$formArray);

         //this code is use for loging
        $QUERY = $this->db->last_query();

            if($this->db->affected_rows() ==1){
                $this->db->trans_commit();
                //this code is use for loging
                $this->db->where('USER_ID',$user_id);
                $CURRENT_RECORD =  $this->db->get('users_reg')->row_array();
                $this->log_model->create_log($user_id,$user_id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_USER_INFORMATION",'users_reg',12,$user_id);
                $this->log_model->itsc_log("UPDATE_USER_INFORMATION","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$user_id,'users_reg');

                return 1;
            }elseif($this->db->affected_rows() ==0){
                $this->db->trans_commit();

                //this code is use for loging
                $this->db->where('USER_ID',$user_id);
                $CURRENT_RECORD =  $this->db->get('users_reg')->row_array();
                $this->log_model->create_log($user_id,$user_id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_USER_INFORMATION",'users_reg',12,$user_id);
                $this->log_model->itsc_log("UPDATE_USER_INFORMATION","SUCCESS",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$user_id,'users_reg');

                return 0;
            }else{
                $this->db->trans_rollback();

                //this code is use for loging
                $this->db->where('USER_ID',$user_id);
                $CURRENT_RECORD =  $this->db->get('users_reg')->row_array();
                $this->log_model->create_log($user_id,$user_id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_USER_INFORMATION",'users_reg',12,$user_id);
                $this->log_model->itsc_log("UPDATE_USER_INFORMATION","FAILED",$QUERY,'CANDIDATE',$user_id,$CURRENT_RECORD,$PRE_RECORD,$user_id,'users_reg');

                return -1;
            }

    }

    function getExperiancesByUserId($user_id){

        $this->db->where('USER_ID',$user_id);
        $this->db->where('ACTIVE',1);
        $this->db->from('experiances');
        $experiances_list = $this->db->get()->result_array();
        return $experiances_list;
    }

    function addExperiances($form_array){
        //load loging model
        $this->load->model('log_model');

        $this->db->trans_begin();
        $this->db->insert('experiances', $form_array);

        //this code is use for loging
        $QUERY = $this->db->last_query();
        $id = $this->db->insert_id();


        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();

            //this code is use for loging
            $this->log_model->create_log(0,$id,"","","ADD_EXPERIANCE",'experiances',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_EXPERIANCE","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'experiances');



            return false;
        }else {
            $this->db->trans_commit();

            //this code is use for loging

            $this->db->where('EXPERIANCE_ID',$id);
            $CURRENT_RECORD =  $this->db->get('experiances')->row_array();
            $this->log_model->create_log(0,$id,"","","ADD_EXPERIANCE",'experiances',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_EXPERIANCE","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'experiances');

            return true;
        }
    }

    function deleteExperiance($USER_ID,$experiance_id){
        //load loging model
        $this->load->model('log_model');
        $this->db->where('EXPERIANCE_ID',$experiance_id);
        $PRE_RECORD =  $this->db->get('experiances')->row_array();


        $this->db->trans_begin();

        $formArray = array('ACTIVE'=>0);

        $this->db->where('EXPERIANCE_ID',$experiance_id);
        $this->db->where('USER_ID',$USER_ID);
        $this->db->where('ACTIVE',1);
        $this->db->update('experiances',$formArray);
        //this code is use for loging
        $QUERY = $this->db->last_query();


        if($this->db->affected_rows() != 1){
            $this->db->trans_rollback();

            //this code is use for loging
            $this->db->where('EXPERIANCE_ID',$experiance_id);
            $CURRENT_RECORD =  $this->db->get('experiances')->row_array();
            $this->log_model->create_log($experiance_id,$experiance_id,$PRE_RECORD,$CURRENT_RECORD,"DELETE_EXPERIANCE",'experiances',13,$CURRENT_RECORD['USER_ID']);
            $this->log_model->itsc_log("DELETE_EXPERIANCE","FAILED",$QUERY,'CANDIDATE',$USER_ID,$CURRENT_RECORD,$PRE_RECORD,$experiance_id,'experiances');


            return false;
        }else {
            $this->db->trans_commit();

            //this code is use for loging
            $this->db->where('EXPERIANCE_ID',$experiance_id);
            $CURRENT_RECORD =  $this->db->get('experiances')->row_array();
            $this->log_model->create_log($experiance_id,$experiance_id,$PRE_RECORD,$CURRENT_RECORD,"DELETE_EXPERIANCE",'experiances',13,$CURRENT_RECORD['USER_ID']);
            $this->log_model->itsc_log("DELETE_EXPERIANCE","SUCCESS",$QUERY,'CANDIDATE',$USER_ID,$CURRENT_RECORD,$PRE_RECORD,$experiance_id,'experiances');

            return true;
        }
    }


}
