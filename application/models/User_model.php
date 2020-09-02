<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/10/2020
 * Time: 10:54 PM
 */

class User_model extends CI_model
{
    function getUserFullDetailById($user_id){
        $user_reg  = $this->getUserById($user_id);
        if($user_reg){
            $qual = $this->getQulificatinByUserId($user_id);
            $expr = $this->getExperiancesByUserId($user_id);
            return array("users_reg"=>$user_reg,"qualifications"=>$qual,"experiances"=>$expr);
        }else{
            return false;
        }

    }
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
        $this->db->from("users_reg ur");
        $this->db->join('districts AS d', 'ur.DISTRICT_ID = d.DISTRICT_ID');
        $this->db->where('USER_ID',$user_id);

        $user = $this->db->get()->row_array();
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

	function getUserAdmissionRoleByUserId($user_id){
		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select('r.ROLE_ID, rr.R_R_ID, r.`ROLE_NAME`,r.`ACTIVE`, rr.`USER_ID`, r.`KEYWORD`');
		$this->legacy_db->from('role_relation rr');
		$this->legacy_db->join('role AS r', 'rr.ROLE_ID = r.ROLE_ID');
		$this->legacy_db->where('rr.USER_ID',$user_id);
//		$this->db->where('r.KEYWORD','UG_A');
		$this->legacy_db->where('r.ACTIVE','1');
		$user = $this->legacy_db->get()->result_array();
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

    function addUser($form_array){

        //load loging model
        $this->load->model('log_model');

        $this->db->trans_begin();
        $this->db->db_debug = false;
        if($this->db->insert('users_reg', $form_array)){

            //this code is use for loging
            $QUERY = $this->db->last_query();
            $id = $this->db->insert_id();

            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();

                //this code is use for loging
                $this->log_model->create_log(0,$id,"","","ADD_USER_FAILED",'users_reg',11,$id);
                $this->log_model->itsc_log("ADD_USER","FAILED",$QUERY,'CANDIDATE',$id,"","",$id,'users_reg');

                return false;
            } else {
                $this->db->trans_commit();

                //this code is use for loging
                $this->db->where('USER_ID',$id);
                $CURRENT_RECORD =  $this->db->get('users_reg')->row_array();
                $this->log_model->create_log(0,$id,"",$CURRENT_RECORD,"ADD_USER",'users_reg',11,$id);
                $this->log_model->itsc_log("ADD_USER","SUCCESS",$QUERY,'CANDIDATE',$id,$CURRENT_RECORD,"",$id,'users_reg');

                return true;
            }

        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$form_array,"ADD_USER_FAILED",'users_reg',11,0);
            $this->log_model->itsc_log("ADD_USER","FAILED","",'CANDIDATE',0,$form_array,"",0,'users_reg');

            return false;
        }

    }
    function addFamilyInfo($form_array){

        //load loging model
        $this->load->model('log_model');

        $this->db->trans_begin();
        $this->db->db_debug = false;
        if($this->db->insert('family_info', $form_array)){

            //this code is use for loging
            $QUERY = $this->db->last_query();
            $id = $this->db->insert_id();

            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();

                //this code is use for loging
                $this->log_model->create_log(0,$id,"","","ADD_FAMILY_INFO_FAILED",'family_info',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_FAMILY_INFO","FAILED",$QUERY,'CANDIDATE',$form_array['USER_ID'],"","",$id,'family_info');

                return -1;

            } else {
                $this->db->trans_commit();

                //this code is use for loging
                $this->db->where('USER_ID',$id);
                $CURRENT_RECORD =  $this->db->get('family_info')->row_array();
                $this->log_model->create_log(0,$id,"",$CURRENT_RECORD,"ADD_FAMILY_INFO",'family_info',11,$form_array['USER_ID']);
                $this->log_model->itsc_log("ADD_FAMILY_INFO","SUCCESS",$QUERY,'CANDIDATE',$form_array['USER_ID'],$CURRENT_RECORD,"",$id,'family_info');

                return 1;
            }

        }
        else{
            //this code is use for loging
            $this->log_model->create_log(0,0,"",$form_array,"ADD_FAMILY_INFO_FAILED",'family_info',11,$form_array['USER_ID']);
            $this->log_model->itsc_log("ADD_FAMILY_INFO","FAILED","",'CANDIDATE',$form_array['USER_ID'],$form_array,"",0,'family_info');

            return -1;
        }

    }
    function updateFamilyInfoById($id,$formArray){
        //load loging model
        $this->load->model('log_model');
        $this->db->where('FAMILY_INFO_ID',$id);
        $PRE_RECORD =  $this->db->get('family_info')->row_array();

        $this->db->trans_begin();
        $this->db->where('FAMILY_INFO_ID',$id);
        $this->db->update('family_info',$formArray);

        //this code is use for loging
        $QUERY = $this->db->last_query();

        if($this->db->affected_rows() ==1){
            $this->db->trans_commit();
            //this code is use for loging
            $this->db->where('FAMILY_INFO_ID',$id);
            $CURRENT_RECORD =  $this->db->get('family_info')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_FAMILY_INFO",'family_info',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_FAMILY_INFO","SUCCESS",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'family_info');

            return 1;
        }elseif($this->db->affected_rows() ==0){
            $this->db->trans_commit();

            //this code is use for loging
            $this->db->where('FAMILY_INFO_ID',$id);
            $CURRENT_RECORD =  $this->db->get('family_info')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_FAMILY_INFO",'family_info',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_FAMILY_INFO","SUCCESS",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'family_info');

            return 0;
        }else{
            $this->db->trans_rollback();

            //this code is use for loging
            $this->db->where('FAMILY_INFO_ID',$id);
            $CURRENT_RECORD =  $this->db->get('family_info')->row_array();
            $this->log_model->create_log($id,$id,$PRE_RECORD,$CURRENT_RECORD,"UPDATE_FAMILY_INFO",'family_info',12,$formArray['USER_ID']);
            $this->log_model->itsc_log("UPDATE_FAMILY_INFO","FAILED",$QUERY,'CANDIDATE',$formArray['USER_ID'],$CURRENT_RECORD,$PRE_RECORD,$id,'family_info');

            return -1;
        }

    }
    function saveGuardianByUserId($user_id,$formArray){

        $family_info = $this->getGuardianByUserId($user_id);
        if($family_info){
            return $this->updateFamilyInfoById($family_info['FAMILY_INFO_ID'],$formArray);
        }else{
            return $this->addFamilyInfo($formArray);
        }

    }
    function getGuardianByUserId($user_id){
        $this->db->where('USER_ID',$user_id);
        $this->db->where('IS_CANDIDATE_GUARDIAN','Y');

       return $this->db->get('family_info')->row_array();
    }


}
