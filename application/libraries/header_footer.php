<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require('tcpdf/tcpdf.php');
//require_once dirname(__FILE__).'/tcpdf/tcpdf.php';

class Header_footer extends TCPDF 
{
	function __construct() {
	    parent::__construct();
		$CI =& get_instance();
	}//method
}//class
