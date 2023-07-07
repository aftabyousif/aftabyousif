<?php

class StudentReports_model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$this->load->model('log_model');
    $this->legacy_db = $this->load->database('admission_db',true);
	}

  function getStudentByProgram($campus_id,$program_type_id,$session_id,$shif_id,$prog_list_id_str,$part_id,$roll_no=null) {
      $cond = "";
      if($roll_no){
          $cond = "AND CONCAT(sess.SESSION_CODE,'/',spm.PROG_CODE,'/',sl.ROLL_NO_CODE) in ($roll_no) ";
      }
      //print_r($cond);
      //exit();
      $query ="SELECT cam.NAME AS CAMPUS_NAME, app.APPLICATION_ID,ur.USER_ID, ur.FIRST_NAME, ur.FNAME, ur.LAST_NAME, 
      pl.PROGRAM_TITLE, cat.CATEGORY_NAME, ur.BLOOD_GROUP, ur.HOME_ADDRESS, ur.CNIC_NO, ur.FAMILY_CONTACT_NO, 
      CONCAT(sess.SESSION_CODE,'/',spm.PROG_CODE,'/',sl.ROLL_NO_CODE) AS ROLL_NO, ur.PROFILE_IMAGE, class.PART_NAME, spm.DEPT_NAME,
      sess.YEAR AS BATCH_YEAR, class.PART_NO, sh.SHIFT_NAME
      FROM selection_list sl 
      JOIN applications app ON (sl.APPLICATION_ID = app.APPLICATION_ID)
      JOIN users_reg ur ON (ur.USER_ID = app.USER_ID)
      JOIN admission_session ass ON (ass.ADMISSION_SESSION_ID = app.ADMISSION_SESSION_ID)
      JOIN campus cam ON (cam.CAMPUS_ID = ass.CAMPUS_ID)
      JOIN sessions sess ON (sess.SESSION_ID = ass.SESSION_ID)
      JOIN program_list pl ON (sl.PROG_LIST_ID = pl.PROG_LIST_ID)
      JOIN category cat ON (sl.CATEGORY_ID = cat.CATEGORY_ID)
      JOIN shift_program_mapping spm ON (cam.CAMPUS_ID = spm.CAMPUS_ID AND ass.PROGRAM_TYPE_ID = spm.PROGRAM_TYPE_ID AND sl.SHIFT_ID = spm.SHIFT_ID AND pl.PROG_LIST_ID = spm.PROG_LIST_ID )
      JOIN department dpt ON (spm.DEPT_ID = dpt.DEPT_ID)
      JOIN shift sh ON (sl.SHIFT_ID = sh.SHIFT_ID)
      JOIN (
        SELECT ca.APPLICATION_ID, fl.SELECTION_LIST_ID, p.PART_ID, p.NAME AS PART_NAME, p.PART_NO
        FROM candidate_account ca 
        JOIN fee_ledger fl ON (ca.ACCOUNT_ID = fl.ACCOUNT_ID)
        JOIN fee_program_list fpl ON (fl.FEE_PROG_LIST_ID = fpl.FEE_PROG_LIST_ID)
        JOIN part p ON (fpl.PART_ID = p.PART_ID)
        WHERE ca.ACTIVE=1 AND fl.IS_YES = 'Y' AND fl.CHALLAN_TYPE_ID = 1 AND p.PART_ID = '$part_id'
        GROUP BY fl.SELECTION_LIST_ID) AS class ON (sl.APPLICATION_ID = class.APPLICATION_ID AND sl.SELECTION_LIST_ID = class.SELECTION_LIST_ID)
      WHERE 
            sess.SESSION_ID = '$session_id'
        AND cam.CAMPUS_ID = '$campus_id'
        AND ass.PROGRAM_TYPE_ID = '$program_type_id'
        AND sl.SHIFT_ID = '$shif_id'
        AND pl.PROG_LIST_ID in ($prog_list_id_str)
        AND class.PART_ID = '$part_id'
        AND sl.IS_PROVISIONAL = 'N' 
        AND sl.ACTIVE = 1 
        AND sl.ROLL_NO_CODE>0
        $cond
      GROUP BY sl.SELECTION_LIST_ID
      ORDER BY spm.PROG_CODE,sl.ROLL_NO_CODE";
      
      $this->legacy_db = $this->load->database('admission_db',true);
	      //echo $query;
	     // exit();
      $q = $this->legacy_db->query($query);
	
      $result = $q->result_array();
      return $result;
  }
  
  function getStudentInfo($searchBy,$searchValue){
      if($searchBy==1)$searchValue;
      else exit('Invalid input');
      $stuInfo = "SELECT s.SESSION_ID, camp.CAMPUS_ID, sl.SHIFT_ID, pl.PROG_LIST_ID, cat.FEE_CATEGORY_TYPE_ID, ur.USER_ID, sl.APPLICATION_ID,camp.NAME as CAMPUS_NAME,sl.SELECTION_LIST_ID, pl.PROGRAM_TYPE_ID, ur.CNIC_NO, ur.FIRST_NAME, ur.FNAME, ur.LAST_NAME,ur.GENDER, pl.PROGRAM_TITLE, cat.CATEGORY_NAME, CONCAT(s.SESSION_CODE,'/',spm.PROG_CODE,'/',sl.ROLL_NO_CODE) AS ROLL_NO
          FROM selection_list sl
          JOIN admission_session ads ON ads.ADMISSION_SESSION_ID = sl.ADMISSION_SESSION_ID
          JOIN sessions s ON s.SESSION_ID = ads.SESSION_ID
          JOIN admit_card ac ON ac.APPLICATION_ID = sl.APPLICATION_ID
          JOIN applications app ON app.APPLICATION_ID = sl.APPLICATION_ID
          JOIN users_reg ur ON ur.USER_ID = app.USER_ID
          JOIN program_list pl ON pl.PROG_LIST_ID = sl.PROG_LIST_ID
          JOIN category cat ON cat.CATEGORY_ID = sl.CATEGORY_ID
          JOIN shift_program_mapping spm ON spm.CAMPUS_ID = ads.CAMPUS_ID AND spm.SHIFT_ID = sl.SHIFT_ID AND spm.PROGRAM_TYPE_ID = ads.PROGRAM_TYPE_ID AND spm.PROG_LIST_ID = sl.PROG_LIST_ID
          JOIN campus camp ON camp.CAMPUS_ID = ads.CAMPUS_ID
          WHERE sl.IS_ENROLLED LIKE 'Y' AND sl.APPLICATION_ID = $searchValue";
      $this->legacy_db = $this->load->database('admission_db',true);
      $studentInfo = $this->legacy_db->query($stuInfo);
      $result = $studentInfo->row_array();
      return $result;
  }
  
  function getStudentInfoByPart($searchBy,$searchValue){
      if($searchBy==1)$searchValue;
      else exit('Invalid input');
      $stuInfo = "SELECT s.YEAR, sl.APPLICATION_ID, sl.SELECTION_LIST_ID, pl.PROGRAM_TYPE_ID, c.NAME AS CAMPUS_NAME, prt.NAME AS PART_NAME, sem.NAME AS SEMESTER_NAME, ur.CNIC_NO, ur.FIRST_NAME, ur.FNAME, ur.LAST_NAME, pl.PROGRAM_TITLE, cat.CATEGORY_NAME, CONCAT(s.SESSION_CODE,'/',spm.PROG_CODE,'/',sl.ROLL_NO_CODE) AS ROLL_NO
      FROM selection_list sl
      JOIN admission_session ads ON ads.ADMISSION_SESSION_ID = sl.ADMISSION_SESSION_ID
      JOIN sessions s ON s.SESSION_ID = ads.SESSION_ID
      JOIN campus c ON c.CAMPUS_ID = ads.CAMPUS_ID
      JOIN admit_card ac ON ac.APPLICATION_ID = sl.APPLICATION_ID
      JOIN applications app ON app.APPLICATION_ID = sl.APPLICATION_ID
      JOIN users_reg ur ON ur.USER_ID = app.USER_ID
      JOIN program_list pl ON pl.PROG_LIST_ID = sl.PROG_LIST_ID
      JOIN category cat ON cat.CATEGORY_ID = sl.CATEGORY_ID
      JOIN shift_program_mapping spm ON spm.CAMPUS_ID = ads.CAMPUS_ID AND spm.SHIFT_ID = sl.SHIFT_ID AND spm.PROGRAM_TYPE_ID = ads.PROGRAM_TYPE_ID AND spm.PROG_LIST_ID = sl.PROG_LIST_ID
      JOIN fee_program_list fpl ON fpl.CAMPUS_ID = ads.CAMPUS_ID AND fpl.PROG_LIST_ID = pl.PROG_LIST_ID
      JOIN part prt ON prt.PART_ID = fpl.PART_ID
      JOIN semester sem ON sem.SEMESTER_ID = fpl.SEMESTER_ID
      JOIN candidate_account ca ON app.APPLICATION_ID = ca.APPLICATION_ID
      JOIN fee_ledger fl ON fl.FEE_PROG_LIST_ID = fpl.FEE_PROG_LIST_ID AND ca.ACCOUNT_ID = fl.ACCOUNT_ID
      JOIN challan_type ct ON ct.CHALLAN_TYPE_ID = fl.CHALLAN_TYPE_ID
      WHERE sl.IS_ENROLLED LIKE 'Y' AND ct.CHALLAN_TITLE LIKE 'ADMISSION' AND sl.APPLICATION_ID =  $searchValue
      ORDER BY prt.PART_NO DESC";
      $this->legacy_db = $this->load->database('admission_db',true);
		  $studentInfo = $this->legacy_db->query($stuInfo);
		  $result = $studentInfo->row_array();
		  return $result;
  }
  
  function getFeeStructure($SESSION_ID,$CAMPUS_ID,$PROGRAM_TYPE_ID,$SHIFT_ID,$PROG_LIST_ID,$FEE_CATEGORY_TYPE_ID){
      $this->legacy_db = $this->load->database('admission_db',true);
      $condition = array('fs.SESSION_ID' => $SESSION_ID, 'fpl.CAMPUS_ID' => $CAMPUS_ID, 'fpl.PROGRAM_TYPE_ID' => $PROGRAM_TYPE_ID, 'fpl.SHIFT_ID' => $SHIFT_ID, 'fpl.PROG_LIST_ID' => $PROG_LIST_ID, 'fct.FEE_CATEGORY_TYPE_ID' => $FEE_CATEGORY_TYPE_ID);
      
      $this->legacy_db->select('fpl.FEE_PROG_LIST_ID, fpl.CAMPUS_ID, fpl.PROGRAM_TYPE_ID, fpl.SHIFT_ID,fpl.PROG_LIST_ID,
      fs.FEE_CATEGORY_TYPE_ID,fs.SESSION_ID,ba.BANK_ACCOUNT_ID,p.PART_ID,s.SEMESTER_ID,fd.FEE_DEMERIT_ID,fd.NAME AS FEE_TYPE,SUM(fs.AMOUNT) AS FEE_AMOUNT, p.PART_NO, p.NAME AS PART_NAME, s.NAME AS SEMESTER_NAME');
      $this->legacy_db->from('fee_structure fs');
      $this->legacy_db->join('fee_program_list fpl','fpl.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID');
      $this->legacy_db->join('fee_category_type fct','fct.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID');
      $this->legacy_db->join('bank_account ba','ba.BANK_ACCOUNT_ID = fct.BANK_ACCOUNT_ID');
      $this->legacy_db->join('fee_demerit fd','fpl.FEE_DEMERIT_ID = fd.FEE_DEMERIT_ID');
      $this->legacy_db->join('part p','fpl.PART_ID = p.PART_ID');
      $this->legacy_db->join('semester s','fpl.SEMESTER_ID = s.SEMESTER_ID');
      $this->legacy_db->where($condition);
      $this->legacy_db->group_by('fpl.FEE_PROG_LIST_ID');
      $this->legacy_db->order_by('p.PART_NO');
      $result = $this->legacy_db->get()->result_array(); 
  
      /*  $query = "SELECT * FROM (SELECT fs.SEMESTER_ID, fs.PART_ID, app.APPLICATION_ID, sl.SELECTION_LIST_ID, cam.NAME, sh.SHIFT_ID, sh.SHIFT_NAME, pl.PROGRAM_TITLE, fs.PART_NO, fs.PART_NAME, fs.SEMESTER_NAME, fs.AMOUNT AS FEE_AMOUNT, fe.AMOUNT AS ENR_AMOUNT, fc.LATE_FEE AS LATE_FEE
          FROM selection_list sl
          JOIN applications app ON (sl.APPLICATION_ID = app.APPLICATION_ID AND app.IS_DELETED='N') 
          JOIN users_reg reg ON (app.USER_ID = reg.USER_ID) 
          JOIN admission_session admss ON (sl.ADMISSION_SESSION_ID = admss.ADMISSION_SESSION_ID)
          JOIN campus cam ON(admss.CAMPUS_ID = cam.CAMPUS_ID)
          JOIN shift sh ON(sl.SHIFT_ID = sh.SHIFT_ID)
          JOIN program_list pl ON (pl.PROG_LIST_ID = sl.PROG_LIST_ID) 
          JOIN category c ON (c.CATEGORY_ID = sl.CATEGORY_ID)
          JOIN fee_category_type fct ON (fct.FEE_CATEGORY_TYPE_ID = c.FEE_CATEGORY_TYPE_ID)
          JOIN qualifications q ON (reg.USER_ID = q.USER_ID) 
          JOIN discipline dis ON (q.DISCIPLINE_ID = dis.DISCIPLINE_ID) 
          JOIN degree_program dp ON (dis.DEGREE_ID = dp.DEGREE_ID) 
          LEFT JOIN fee_enrolment fe ON (q.ORGANIZATION_ID = fe.INSTITUTE_ID AND fe.SESSION_ID = admss.SESSION_ID)
          JOIN (
            SELECT fpl.FEE_PROG_LIST_ID, fee.SESSION_ID, fpl.CAMPUS_ID, fpl.SHIFT_ID, fpl.PROG_LIST_ID, fee.FEE_CATEGORY_TYPE_ID, sem.SEMESTER_ID, p.PART_ID, p.PART_NO, p.NAME AS PART_NAME, sem.NAME AS SEMESTER_NAME, fee.AMOUNT
            FROM fee_program_list fpl
            JOIN part p ON(fpl.PART_ID = p.PART_ID)
            JOIN semester sem ON(fpl.SEMESTER_ID = sem.SEMESTER_ID)
            JOIN (
              SELECT fs.SESSION_ID, fs.FEE_PROG_LIST_ID, fs.FEE_CATEGORY_TYPE_ID, SUM(fs.AMOUNT) AS AMOUNT
              FROM fee_structure fs 
              GROUP BY fs.SESSION_ID, fs.FEE_PROG_LIST_ID, fs.FEE_CATEGORY_TYPE_ID
            ) fee ON(fpl.FEE_PROG_LIST_ID = fee.FEE_PROG_LIST_ID)
          ) AS fs ON (admss.SESSION_ID = fs.SESSION_ID AND cam.CAMPUS_ID = fs.CAMPUS_ID AND sh.SHIFT_ID = fs.SHIFT_ID AND pl.PROG_LIST_ID = fs.PROG_LIST_ID AND c.FEE_CATEGORY_TYPE_ID = fs.FEE_CATEGORY_TYPE_ID)
          LEFT JOIN fee_challan fc ON (fc.SELECTION_LIST_ID = sl.SELECTION_LIST_ID AND fc.FEE_PROG_LIST_ID = fs.FEE_PROG_LIST_ID AND fc.CHALLAN_TYPE_ID = 1)
          WHERE dp.DEGREE_ID IN(3,4,5) AND sl.SELECTION_LIST_ID = $selection_list_id
          ORDER BY dp.DEGREE_ID DESC) AS fee_str GROUP BY fee_str.SEMESTER_ID, fee_str.PART_ID";
      $q = $this->legacy_db->query($query);
      $result = $q->result_array(); */
      return $result;
  }

  function getEnrollmentFee($USER_ID,$APPLICATION_ID,$SESSION_ID,$PROGRAM_TYPE_ID){
      $this->legacy_db = $this->load->database('admission_db',true);
      if($PROGRAM_TYPE_ID == 1) {
        $condition = array('q.ACTIVE' => 1, 'q.USER_ID' => $USER_ID, 'q.APPLICATION_ID' => $APPLICATION_ID, 'fe.SESSION_ID' => $SESSION_ID);
      }
      if($PROGRAM_TYPE_ID == 2) {
        $condition = array('q.ACTIVE' => 1, 'q.USER_ID' => $USER_ID, 'q.APPLICATION_ID' => $APPLICATION_ID, 'fe.SESSION_ID' => $SESSION_ID);
      }
      $this->legacy_db->select('q.USER_ID, q.APPLICATION_ID, fe.SESSION_ID, fe.AMOUNT, dis.DEGREE_ID');
      $this->legacy_db->from('qualifications q');
      $this->legacy_db->join('discipline dis', 'q.DISCIPLINE_ID = dis.DISCIPLINE_ID');
      $this->legacy_db->join('fee_enrolment fe', 'q.ORGANIZATION_ID = fe.INSTITUTE_ID');
      $this->legacy_db->where($condition);
      $this->legacy_db->order_by('dis.DEGREE_ID','DESC');
      $result = $this->legacy_db->get()->result_array();
      return $result;
  }

  function getFeeChallan($FEE_PROG_LIST_ID,$SELECTION_LIST_ID){
      $this->legacy_db = $this->load->database('admission_db',true);
      $condition = array('FEE_PROG_LIST_ID' => $FEE_PROG_LIST_ID, 'SELECTION_LIST_ID' => $SELECTION_LIST_ID, 'CHALLAN_TYPE_ID' => 1);
      $this->legacy_db->where($condition);
      $result = $this->legacy_db->get('fee_challan');
      return $result->row_array();
  }

  function getStudentPaidChallan($searchBy,$searchValue){
      $searchValue = $this->security->xss_clean($searchValue);
      $stuAccount = "SELECT 
      p.NAME AS PART_NAME, 
      fl.CHALLAN_NO, 
      fl.CHALLAN_AMOUNT,
      fl.PAYABLE_AMOUNT,
      fl.PAID_AMOUNT,
      fc.LATE_FEE,
      DATE_FORMAT(fl.DATE,'%d-%m-%Y') AS CHALLAN_DATE, 
      fl.DETAILS, 
      fl.REMARKS,
      fl.CHALLAN_TYPE_ID
      FROM candidate_account ca
      JOIN fee_ledger fl ON fl.ACCOUNT_ID = ca.ACCOUNT_ID
      JOIN fee_challan fc ON fc.CHALLAN_NO = fl.CHALLAN_NO 
      JOIN fee_program_list fpl ON fpl.FEE_PROG_LIST_ID = fl.FEE_PROG_LIST_ID
      JOIN part p ON p.PART_ID = fpl.PART_ID
      JOIN semester s ON s.SEMESTER_ID = fpl.SEMESTER_ID
      WHERE fl.CHALLAN_TYPE_ID = 1 AND fl.PAID_AMOUNT > 0 AND ca.APPLICATION_ID = $searchValue";
      $this->legacy_db = $this->load->database('admission_db',true);
      $studentAccount = $this->legacy_db->query($stuAccount);
      $result = $studentAccount->result_array();
      return $result;
  }
  function getStudentRefundChallan($searchBy,$searchValue){
      $searchValue = $this->security->xss_clean($searchValue);
      $stuAccount = "SELECT 
      p.NAME AS PART_NAME, 
      fl.CHALLAN_NO, 
      fl.CHALLAN_AMOUNT,
      fl.PAYABLE_AMOUNT,
      fl.PAID_AMOUNT,
      fc.LATE_FEE,
      DATE_FORMAT(fl.DATE,'%d-%m-%Y') AS CHALLAN_DATE, 
      fl.DETAILS, 
      fl.REMARKS,
      fl.CHALLAN_TYPE_ID
      FROM candidate_account ca
      JOIN fee_ledger fl ON fl.ACCOUNT_ID = ca.ACCOUNT_ID
      JOIN fee_challan fc ON fc.CHALLAN_NO = fl.CHALLAN_NO 
      JOIN fee_program_list fpl ON fpl.FEE_PROG_LIST_ID = fl.FEE_PROG_LIST_ID
      JOIN part p ON p.PART_ID = fpl.PART_ID
      JOIN semester s ON s.SEMESTER_ID = fpl.SEMESTER_ID
      WHERE fl.CHALLAN_TYPE_ID = 3 AND fl.PAID_AMOUNT < 0 AND ca.APPLICATION_ID = $searchValue";
      $this->legacy_db = $this->load->database('admission_db',true);
      $studentAccount = $this->legacy_db->query($stuAccount);
      $result = $studentAccount->result_array();
      return $result;
  }

  function getFeeChallanStatistics(){
      $query = "SELECT 
      fct.FEE_TYPE_TITLE AS CATEGORY, 
      COUNT(fc.CHALLAN_NO) AS TOTAL_CHALLAN, 
      SUM(fc.PAYABLE_AMOUNT) AS TOTAL_AMOUNT
      FROM fee_challan fc 
      JOIN selection_list sl ON(fc.SELECTION_LIST_ID = sl.SELECTION_LIST_ID)
      JOIN admission_session ads ON(sl.ADMISSION_SESSION_ID = ads.ADMISSION_SESSION_ID)
      JOIN admission_list al ON(sl.ADMISSION_LIST_ID = al.ADMISSION_LIST_ID)
      JOIN category cat ON(cat.CATEGORY_ID = sl.CATEGORY_ID)
      JOIN fee_category_type fct ON(fct.FEE_CATEGORY_TYPE_ID = cat.FEE_CATEGORY_TYPE_ID)
      WHERE fc.CHALLAN_TYPE_ID = 1 AND fc.ACTIVE = 1 AND fc.PAYABLE_AMOUNT > 0 AND ads.SESSION_ID = 2 AND fc.PART_ID IN(2,7,9)
      GROUP BY fct.FEE_CATEGORY_TYPE_ID
      ORDER BY fct.FEE_CATEGORY_TYPE_ID";
      $this->legacy_db = $this->load->database('admission_db',true);
      $records = $this->legacy_db->query($query);
      $result = $records->result_array();
      return $result;
  }

  function addUpdateTable($table,$where,$record){
    $query = $this->legacy_db->get_where($table, $where);
    if($query->num_rows() > 0){
      $this->legacy_db->where($where);
      if($this->legacy_db->update($table,$record)){
        $addUpdate = array_merge($record, array('MESSAGE' => 'Record updated successfully.'));
      } else {
        $addUpdate = array_merge($record, array('MESSAGE' => 'Record not updated successfully.'));
      };
    } else {
      if($this->legacy_db->insert($table,$record)){
        $addUpdate = array_merge($record, array('MESSAGE' => 'Record inserted successfully.'));
      } else {
        $addUpdate = array_merge($record, array('MESSAGE' => 'Record not inserted successfully.'));
      };
    }
    return $addUpdate;
  }

}