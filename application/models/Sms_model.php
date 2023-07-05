<?php
class Sms_model extends CI_model
{
    function sac_message($form_array){

        //load loging model
        // $this->load->model('log_model');

        $this->db->db_debug = false;
        if($this->db->insert('sac_message', $form_array)){

            } else {
    
            }

    }
}
?>