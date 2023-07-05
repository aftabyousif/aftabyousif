<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 2/18/2021
 * Time: 5:19 PM
 */
class Promotelist_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
//		$CI =& get_instance();
        $this->load->model('log_model');
        $this->legacy_db = $this->load->database('admission_db', true);
    }

    public function  get_previous_list($admission_session_id,$shift_id,$list_no,$test_id,$is_special_self){
        $this->legacy_db->select("*");

        $this->legacy_db->from('candidate_account AS ca');
        $this->legacy_db->join('fee_ledger AS fl', 'fl.`ACCOUNT_ID` = ca.`ACCOUNT_ID`');
        $this->legacy_db->join('selection_list AS sl', 'sl.`SELECTION_LIST_ID` = fl.`SELECTION_LIST_ID`');
        $this->legacy_db->where('sl.ADMISSION_SESSION_ID', $admission_session_id);
        $this->legacy_db->where('sl.LIST_NO <= ', $list_no);
        $this->legacy_db->where("sl.TEST_ID ",$test_id);
        $this->legacy_db->where("sl.SHIFT_ID ",$shift_id);
        if($is_special_self=='N'){
            $this->legacy_db->where("sl.CATEGORY_ID != ".SPECIAL_SELF_FINANCE_CATEGORY_ID );
        }else if($is_special_self=='Y'){
            $this->legacy_db->where("sl.CATEGORY_ID = ".SPECIAL_SELF_FINANCE_CATEGORY_ID );
        }

        $this->legacy_db->where('fl.IS_YES', 'Y');
        $this->legacy_db->where("fl.CHALLAN_TYPE_ID = 1 ");

        $this->legacy_db->where('ca.ACTIVE', 1);
        $this->legacy_db->where('sl.ACTIVE > 0 ');
        $this->legacy_db->where("sl.IS_PROVISIONAL = 'N' ");

        $result = $this->legacy_db->get()->result_array();
        $key_array = array();
        foreach ($result as $row){
            if(!isset($key_array[$row['APPLICATION_ID']])){
                $key_array[$row['APPLICATION_ID']] = array("SELF"=>null,"MERIT"=>null);
            }
            if($row['IS_MERIT']=='Y'){
                $key_array[$row['APPLICATION_ID']]['MERIT'] = $row;
            }else if($row['IS_MERIT']=='N'){
                $key_array[$row['APPLICATION_ID']]['SELF'] = $row;
            }

        }
        prePrint($this->legacy_db->last_query());
        return $key_array;
    }

    public function get_current_list($admission_session_id,$shift_id,$list_no,$test_id,$is_special_self){
        $this->legacy_db->select("*");
        $this->legacy_db->from('selection_list AS sl');
        $this->legacy_db->join('fee_challan AS fc ',"fc.SELECTION_LIST_ID = sl.SELECTION_LIST_ID");
        $this->legacy_db->where('sl.ADMISSION_SESSION_ID', $admission_session_id);
        $this->legacy_db->where('sl.LIST_NO', $list_no);
        $this->legacy_db->where("sl.TEST_ID ",$test_id);
        $this->legacy_db->where("sl.SHIFT_ID ",$shift_id);
        $this->legacy_db->where('sl.ACTIVE > 0 ');
        $this->legacy_db->where("sl.IS_PROVISIONAL = 'N' ");
        if($is_special_self=='N'){
            $this->legacy_db->where("sl.CATEGORY_ID != ".SPECIAL_SELF_FINANCE_CATEGORY_ID );
        }else if($is_special_self=='Y'){
            $this->legacy_db->where("sl.CATEGORY_ID = ".SPECIAL_SELF_FINANCE_CATEGORY_ID );
        }
        $result = $this->legacy_db->get()->result_array();
        $key_array = array();
        foreach ($result as $row){

            if(!isset($key_array[$row['APPLICATION_ID']])){
                $key_array[$row['APPLICATION_ID']] = array("SELF"=>null,"MERIT"=>null);
            }
            if($row['CATEGORY_ID']!=SELF_FINANCE &&$row['CATEGORY_ID']!=OTHER_PROVINCES_SELF_FINANCE&&$row['CATEGORY_ID']!=SELF_FINANCE_EVENING_CATEGORY_ID&&$row['CATEGORY_ID']!=SPECIAL_SELF_FINANCE_CATEGORY_ID){
                $key_array[$row['APPLICATION_ID']]['MERIT'] = $row;
            }else if($row['CATEGORY_ID']==SELF_FINANCE  || $row['CATEGORY_ID']==OTHER_PROVINCES_SELF_FINANCE|| $row['CATEGORY_ID']==SPECIAL_SELF_FINANCE_CATEGORY_ID|| $row['CATEGORY_ID']==SELF_FINANCE_EVENING_CATEGORY_ID){
                $key_array[$row['APPLICATION_ID']]['SELF'] = $row;
            }

        }
        prePrint($this->legacy_db->last_query());
        return $key_array;
    }

    public function get_selection_list_by_application_id_and_remarks($admission_session_id, $shift_id, $list_no, $test_id,$application_id,$remarks){
        $this->legacy_db->select("*");
        $this->legacy_db->from('selection_list AS sl');
        $this->legacy_db->where('sl.ADMISSION_SESSION_ID', $admission_session_id);
        $this->legacy_db->where('sl.LIST_NO <= ', $list_no);
        $this->legacy_db->where("sl.TEST_ID ",$test_id);
        $this->legacy_db->where("sl.SHIFT_ID ",$shift_id);
        $this->legacy_db->where("sl.IS_PROVISIONAL = 'N' ");
        $this->legacy_db->where("sl.APPLICATION_ID",$application_id);
        $this->legacy_db->where("sl.REMARKS",$remarks);
        $result = $this->legacy_db->get()->row_array();
        return $result;


    }


    function update_ledeger_list($feeledger,$selection_list,$USER_ID=0,$is_special_self,$shift_id){
        $check = true;
        $this->load->model('log_model');
        $this->legacy_db->trans_begin();
        $count=1;
        foreach($feeledger as $array){

            $this->legacy_db->where('fl.CHALLAN_TYPE_ID IN (1,2)');
            $this->legacy_db->where('fl.ACCOUNT_ID',$array['ACCOUNT_ID']);
            //$this->legacy_db->update('fee_ledger',$array);
            $this->legacy_db->set('fl.FEE_PROG_LIST_ID',$array['FEE_PROG_LIST_ID']);
            $this->legacy_db->set('fl.SELECTION_LIST_ID',$array['SELECTION_LIST_ID']);
            $this->legacy_db->set('fl.ACCOUNT_ID',$array['ACCOUNT_ID']);
            
                $this->legacy_db->where("sl.SHIFT_ID",$shift_id);
            if(isset($array['IS_MERIT']))
            $this->legacy_db->set('fl.IS_MERIT',$array['IS_MERIT']);
            if($is_special_self=='N'){
                $this->legacy_db->where("sl.CATEGORY_ID != ".SPECIAL_SELF_FINANCE_CATEGORY_ID );
            }else if($is_special_self=='Y'){
                $this->legacy_db->where("sl.CATEGORY_ID = ".SPECIAL_SELF_FINANCE_CATEGORY_ID );
            }else{
                exit("Something went wrong in update_ledeger_list");
            }
           // array("FEE_PROG_LIST_ID" => $current_selection['FEE_PROG_LIST_ID'], 'SELECTION_LIST_ID' => $current_selection['SELECTION_LIST_ID'], 'ACCOUNT_ID' => $previous_selection_list[$application_id]['SELF']['ACCOUNT_ID'], 'IS_MERIT' => 'N');

            //$this->db->where('user_data.id',$id);
            //$this->db->where('user.id',$id);
            $this->legacy_db->update('`fee_ledger` AS `fl` JOIN `selection_list` AS `sl` ON (`fl`.`SELECTION_LIST_ID` = `sl`.`SELECTION_LIST_ID`)');
//            echo $this->legacy_db->last_query();
//            exit();
//            if ($this->legacy_db->affected_rows()>1)
//            {
//                $check = false;
//                break;
//            }
            $count++;

        }
        $detail = "PROMOTE_FEES";
        if($check){
            $detail = "SELECTION_LIST";
            $count = 0;
            foreach($selection_list as $array){

                $this->legacy_db->where('SELECTION_LIST_ID',$array['SELECTION_LIST_ID']);
                $this->legacy_db->update('selection_list',$array);

                if ($this->legacy_db->affected_rows()>1)
                {
                    $check = false;
                    break;
                }
                $count++;

            }
        }

        if($check==false){
            $QUERY ="Something went wrong in row $count";
            $this->legacy_db->trans_rollback();
            $this->log_model->create_log(0,0,$QUERY,$QUERY,$detail,'fee_ledger',11,$USER_ID);
            return false;
        }else{
            $QUERY="";
            $this->legacy_db->trans_commit();

            $this->log_model->create_log(0,0,"","",$detail,'fee_ledger',11,$USER_ID);
            return true;
        }



    }
}