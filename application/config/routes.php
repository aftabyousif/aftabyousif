<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['profile'] = 'Candidate/profile';
$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['application_status'] = 'web/application_status';
$route['selection_list'] = 'web/candidate_objection_list';
$route['slip/(:any)'] = 'Web/candidate_slip/$1';
$route['app_auth'] = 'AdminLogin/invg_app_auth';

$route['dept_wise_selection_list'] = 'web/objection_list';
$route['merit_list_bachelor'] = 'web/merit_list_bachelor';
$route['candidate_merit_list_bachelor'] = 'web/candidate_merit_list_bachelor';
$route['view_candidate_profile'] = 'web/candidate_profile_search';
$route['merit_list_master'] = 'web/merit_list_master';
$route['candidate_merit_list_master'] = 'web/candidate_merit_list_master';
$route['application_form_report'] = 'Statistics/application_form_report';
$route['paidChallanReport/(:num)/(:num)'] = 'StudentIDCard/paidChallanReport/$1/$2';
$route['admissionLetterReport/(:num)/(:num)'] = 'StudentIDCard/admissionLetterReport/$1/$2';
$route['correctionLetterAndList/(:num)/(:num)'] = 'StudentIDCard/correctionLetterAndList/$1/$2';

$route['enrollment_card_pdf/(:any)'] = 'EnrolmentCard/enrollment_card_pdf/$1';
$route['eligibility_certificate_pdf/(:any)'] = 'EnrolmentCard/eligibility_certificate_pdf/$1';
$route['general_branch_challan/(:any)'] = 'GeneralBranch/challan_print/$1';

$route['api/get_candidate_roll_no'] = 'Api/get_candidate_by_roll_no';