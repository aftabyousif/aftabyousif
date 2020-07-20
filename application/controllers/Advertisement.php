<?php


class Advertisement extends CI_Controller
{
	public function ug_advertisement ()
	{
		$data['user'] = '';
		$data['user'] = '';
		$data['profile_url'] = '';

		$this->load->view('include/header',$data);
//		$this->load->view('include/preloder');
//		$this->load->view('include/side_bar');
//		$this->load->view('include/nav',$data);
		$this->load->view('ug_adv',$data);
//		$this->load->view('include/footer_area');
		$this->load->view('include/footer');

	}

}
