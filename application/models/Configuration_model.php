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

}