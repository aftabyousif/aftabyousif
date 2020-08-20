<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('fpdf/fpdf.php');
class Fpdf_master extends fpdf
{
	function __construct() {
	    parent::__construct();
		$CI =& get_instance();
	}//method
}//class
