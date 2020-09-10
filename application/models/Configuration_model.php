<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/13/2020
 * Time: 12:42 PM
 */

class Configuration_model extends CI_model
{
    function getPrefix(){
        $this->db->where('DESCRIPTION','PREFIXS');
        $this->db->where('ACTIVE',1);
        $PREFIXS = $this->db->get('configurations')->row_array();
        return $PREFIXS;
    }

	function get_privilages($user_id,$role_id){

		$this->legacy_db = $this->load->database('admission_db',true);
		$this->legacy_db->select("p.*");
		$this->legacy_db->from("privilages p");
		$this->legacy_db->join("privilage_relation pr","p.PRIVILAGE_ID=pr.PRIVILAGE_ID AND (pr.USER_ID={$user_id} OR pr.ROLE IN ($role_id) OR pr.ROLE=-1) ORDER BY ORD","INNER");
		return($this->legacy_db->get()->result_array());

	}

	function side_bar_data ($user_id,$role_id)
	{
		$rows = $this->get_privilages($user_id,$role_id);
		$dummy = array();
		foreach ($rows as $p){
//			print_r($p);
			//$dum = array();
			if($p['IS_PARENT']=='Y'&&$p['IS_DASHBOARD_ITEM']==1){

				$sub_item=array();
				foreach ($rows as $k){
					if($p['PRIVILAGE_ID']==$k['PARENT_ID']){

						$sub_item[] = array(
							'is_tab_base'=>$p['IS_TAB_BASE'],
							'value' => $k['NAME'],
							'link' => $k['LINK']);

					}
				}

				$dum=array(  'is_submenu' => 1,
					'is_tab_base'=>$p['IS_TAB_BASE'],
					'value' => $p['NAME'],
					'link' => $p['LINK'],
					'class' =>$p['SIDE_ICON'],
					'sub_menu'=>$sub_item);
				$dummy[]=$dum;
			}
			else if($p['PARENT_ID']=='0'&&$p['IS_DASHBOARD_ITEM']==1){

				$dum=array(  'is_submenu' => 0,
					'is_tab_base'=>$p['IS_TAB_BASE'],
					'value' => $p['NAME'],
					'link' => $p['LINK'],
					'class' =>$p['SIDE_ICON']);
				// prePrint($dum);
				$dummy[]=$dum;
			}

		}
//		print_r($dummy);
		return $dummy;
	}

}
