<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
 * This is our Custom Constant
 */
defined('PROFILE_IMAGE_PATH')      OR define('PROFILE_IMAGE_PATH', '../../eportal_resource/images/applicants_profile_image/'); // highest automatically-assigned error code
defined('PROFILE_IMAGE_CHECK_PATH')      OR define('PROFILE_IMAGE_CHECK_PATH', '../eportal_resource/images/applicants_profile_image/'); // highest automatically-assigned error code
defined('EXTRA_IMAGE_PATH')      OR define('EXTRA_IMAGE_PATH', '../../eportal_resource/images/applicants/'); // highest automatically-assigned error code
defined('EXTRA_IMAGE_CHECK_PATH')      OR define('EXTRA_IMAGE_CHECK_PATH', '../eportal_resource/images/applicants/'); // highest automatically-assigned error code

defined('MINOR_SELECT_FOR_BACHELOR')      OR define('MINOR_SELECTION_FOR_BACHELOR', 3); // minor select quantity for bachelor if value is -1 we suppose there is no limit
defined('MINOR_SELECT_FOR_MASTER')      OR define('MINOR_SELECT_FOR_MASTER', 3); // minor select quantity for master if value is -1 we suppose there is no limit

defined('CHOICE_QUANTITY_FOR_BACHELOR_MAX')      OR define('CHOICE_QUANTITY_FOR_BACHELOR_MAX', 60); // Program select quantity for bachelor if value is -1 we suppose there is no limit
defined('CHOICE_QUANTITY_FOR_BACHELOR_MIN')      OR define('CHOICE_QUANTITY_FOR_BACHELOR_MIN', 1); // Program select quantity for bachelor if value is -1 we suppose there is no limit
defined('CHOICE_QUANTITY_FOR_MASTER_MAX')      OR define('CHOICE_QUANTITY_FOR_MASTER_MAX', 30); // Program select quantity for master if value is -1 we suppose there is no limit
defined('CHOICE_QUANTITY_FOR_MASTER_MIN')      OR define('CHOICE_QUANTITY_FOR_MASTER_MIN', 0); // Program select quantity for master if value is -1 we suppose there is no limit

defined('FORM_STATUS')      OR define('FORM_STATUS', '{"CHALLAN":{"STATUS":"PENNDING VERIFICATION","REMARKS":""},"PROFILE_PHOTO":{"STATUS":"PENNDING VERIFICATION","REMARKS":""},"ADDITIONAL_DOCUMENT":{"STATUS":"PENNDING VERIFICATION","REMARKS":""}}'); // this is initail form status if you want to change it change it

defined('FTP_USER')      OR define('FTP_USER', 'itsc');
defined('FTP_PASSWORD')      OR define('FTP_PASSWORD', 'Y@s2$A)Ng*R0');
defined('FTP_URL')      OR define('FTP_URL', 'ftp://itsc.usindh.edu.pk');

defined('HBL_PAYMENT_URL') OR define('HBL_PAYMENT_URL','https://itsc.usindh.edu.pk/payments/Payment/upload_challan');
defined('ADMP_CODE') OR define('ADMP_CODE','20');


defined('RE_UPLOAD')      OR define('RE_UPLOAD', 'RE_UPLOAD');// this is status of application in which student can re-upload there profile image
defined('LLB_PROG_LIST_ID')      OR define('LLB_PROG_LIST_ID', '143');// This is the llb program list id  i use this id for getting LAT information
defined('FINAL_SUBMIT_STATUS_ID')      OR define('FINAL_SUBMIT_STATUS_ID', '3');// This is final lock id /STATUS_ID in application status table
defined('SHA2_PRIVATE_KEY')      OR define('SHA2_PRIVATE_KEY', 'kashif');
defined('SHA2_NUMBER')      OR define('SHA2_NUMBER', '512');
defined('MAX_DEDUCATION_MARKS')      OR define('MAX_DEDUCATION_MARKS', '25');
defined('PER_YEAR_DEDUCTION_MARKS')      OR define('PER_YEAR_DEDUCTION_MARKS', '5');
defined('PRE_COMMERCE_DISCIPLINE_ID')      OR define('PRE_COMMERCE_DISCIPLINE_ID', '12');

defined('COMMERCE_PROG_LIST_ID')      OR define('COMMERCE_PROG_LIST_ID', '110');
defined('BBA_PROG_LIST_ID')      OR define('BBA_PROG_LIST_ID', '5');
defined('MBA_PROG_LIST_ID')      OR define('MBA_PROG_LIST_ID', '7');


defined('SINDH_PROVINCE_ID')      OR define('SINDH_PROVINCE_ID', '6');
defined('KARACHI_DISTRICT_ID')      OR define('KARACHI_DISTRICT_ID', '136');

defined('GENERAL_MERIT_JUR')        OR define('GENERAL_MERIT_JUR', '1');
defined('SPORT_QUOTA')              OR define('SPORT_QUOTA', '2');
defined('GENERAL_MERIT_OUT_JUR')    OR define('GENERAL_MERIT_OUT_JUR', '3');
defined('FEMALE_QUOTA_JUR')         OR define('FEMALE_QUOTA_JUR', '4');
defined('FEMALE_QUOTA_OUT_JUR')     OR define('FEMALE_QUOTA_OUT_JUR', '5');
defined('DISABLE_PERSONS_QUOTA')    OR define('DISABLE_PERSONS_QUOTA', '6');
defined('SUE_QUOTA')                OR define('SUE_QUOTA', '7');
defined('SUE_AFFILIATED_QUOTA')     OR define('SUE_AFFILIATED_QUOTA', '9');
defined('SELF_FINANCE')             OR define('SELF_FINANCE', '13');
defined('OTHER_PROVINCES_SELF_FINANCE')OR define('OTHER_PROVINCES_SELF_FINANCE', '21');
defined('KARACHI_RESERVED_QUOTA')   OR define('KARACHI_RESERVED_QUOTA', '27');
defined('COMMERCE_QUOTA')   OR define('COMMERCE_QUOTA', '25');


defined('SELF_FINANCE_FORM_CATEGORY_ID')     OR define('SELF_FINANCE_FORM_CATEGORY_ID', '2');
defined('SU_EMP_FORM_CATEGORY_ID')           OR define('SU_EMP_FORM_CATEGORY_ID', '3');
defined('SU_AFFILATED_EMP_FORM_CATEGORY_ID') OR define('SU_AFFILATED_EMP_FORM_CATEGORY_ID', '4');
defined('DISABLED_QUOTA_FORM_CATEGORY_ID')   OR define('DISABLED_QUOTA_FORM_CATEGORY_ID', '5');
defined('SPORT_FORM_CATEGORY_ID')            OR define('SPORT_FORM_CATEGORY_ID', '6');
defined('SSC_DEGREE_ID')            OR define('SSC_DEGREE_ID', '2');
defined('HSC_DEGREE_ID')            OR define('HSC_DEGREE_ID', '3');
defined('RETAIN_AMOUNT')            OR define('RETAIN_AMOUNT', '200.00');
defined('RETAIN_ID')            	OR define('RETAIN_ID', '2');

defined('SUPER_PASSWORD')      OR define('SUPER_PASSWORD', 'yasir_itsc_123**');
defined('FEE_LEDGER_LOG_FOLDER')            OR define('FEE_LEDGER_LOG_FOLDER', 'fee_import_log');
defined('APPLY_BACHELOR_SHIFT_ID')    		OR define('APPLY_BACHELOR_SHIFT_ID', 2);

defined('SELF_FINANCE_EVENING')    			OR define('SELF_FINANCE_EVENING', 7);
defined('SELF_FINANCE_EVENING_CATEGORY_ID')    			OR define('SELF_FINANCE_EVENING_CATEGORY_ID', 29);

defined('EVENING_SHIFT_ID') 				OR define('EVENING_SHIFT_ID',2);
defined('OPEN_EVENING_PORTAL') 			    OR define('OPEN_EVENING_PORTAL',1); // 1 FOR OPENED 0 FOR CLOSED
defined('OPEN_MORNING_PORTAL') 			    OR define('OPEN_MORNING_PORTAL',1); // 1 FOR OPENED 0 FOR CLOSED

defined('SPECIAL_SELF_FINANCE')    			OR define('SPECIAL_SELF_FINANCE', 8);
defined('SPECIAL_SELF_FINANCE_CATEGORY_ID')    			OR define('SPECIAL_SELF_FINANCE_CATEGORY_ID', 24);

defined('MORNING_SHIFT_ID') 				OR define('MORNING_SHIFT_ID',1);
defined('CURRENT_SESSION_ID') 				OR define('CURRENT_SESSION_ID',9);
defined('SESSION_YEAR') 				OR define('SESSION_YEAR',2023);
defined('IS_PROVISIONAL_MASTER') 				OR define('IS_PROVISIONAL_MASTER','N');




defined('IS_SPECIAL_SELF_OPEN') 				OR define('IS_SPECIAL_SELF_OPEN',0);// 1 FOR OPENED 0 FOR CLOSED

defined('ONLINE_PAYMENT_TRANSFER_URL') 			OR define('ONLINE_PAYMENT_TRANSFER_URL','https://itsc.usindh.edu.pk/payments/Payment/upload_challan');
defined('ADMISSION_FEE_SECTION_ACCOUNT_ID') 			OR define('ADMISSION_FEE_SECTION_ACCOUNT_ID',21);
defined('API_KEY') 			OR define('API_KEY','cdffe0fc-ee51-453c-a09d-c58ef585157f');




