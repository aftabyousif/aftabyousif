<?php
// prePrint($applicant_data);
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong">
<style>
    table {
        font-size:10pt;
        font-family: "Trirong", serif;
        padding:1px;
        width: 100%;
        border-collapse: collapse;
        border: 1px solid black;
    }
    td,th {
        padding:4px;
    }
    tr { border: 1px solid black; }
  @page {
  size: A3 landscape;
}
@media print {
  footer {page-break-after: always;}
}

    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
    
    tr:nth-child(even){background-color: #f2f2f2;}
</style>
<table>
    <thead>
    <!--<tr>-->
    <!--    <th colspan=5>PROFILE INFO</th>-->
    <!--    <th colspan=5>SSC</th>-->
    <!--    <th colspan=5>HSC</th>-->
    <!--</tr>-->
    <tr>
        <th style='width:5%'>APP #</th>
        <th>CNIC #</th>
        <th style='width:40%'>FULL NAME</th>
        <th>DISTRICT</th>
        <th style='width:5%'>U / R</th>
        <th></th>
        <th>OBT</th>
        <th>TOTAL</th>
        <th>YEAR</th>
        <th>GROUP</th>
        <th>BOARD</th>
        <th></th>
        <th>OBT</th>
        <th>TOTAL</th>
        <th>YEAR</th>
        <th>GROUP</th>
        <th>BOARD</th>
    </tr>
    </thead>
    <?php
    foreach ($applicant_data as $application)
    {
        $APPLICATION_ID = $application['APPLICATION_ID'];
        $FORM_DATA      = $application['FORM_DATA'];
        $FORM_DATA      = json_decode($FORM_DATA,true);
        $FIRST_NAME     = $FORM_DATA['users_reg']['FIRST_NAME'];
        $FNAME          = $FORM_DATA['users_reg']['FNAME'];
        $LAST_NAME      = $FORM_DATA['users_reg']['LAST_NAME'];
        $CNIC_NO        = $FORM_DATA['users_reg']['CNIC_NO'];
        $DISTRICT_NAME  = $FORM_DATA['users_reg']['DISTRICT_NAME'];
        $U_R            = $FORM_DATA['users_reg']['U_R'];
        $qualifications = $FORM_DATA['qualifications'];
     
        $ssc = find_qualification($qualifications,2);
        $hsc = find_qualification($qualifications,3);
        
        $full_name = "$FIRST_NAME, $FNAME, $LAST_NAME";
   
        ?>
            <tr>
        <th><?=$APPLICATION_ID?></th>
        <th><?=$CNIC_NO?></th>
        <th><?=$full_name?></th>
        <th><?=$DISTRICT_NAME?></th>
        <th><?=$U_R?></th>
        <th>SSC</th>
        <td><?=$ssc['OBTAINED_MARKS']?$ssc['OBTAINED_MARKS']:"N/A"?></td>
        <td><?=$ssc['TOTAL_MARKS']?$ssc['TOTAL_MARKS']:"N/A"?></td>
        <td><?=$ssc['PASSING_YEAR']?$ssc['PASSING_YEAR']:"N/A"?></td>
        <td><?=$ssc['DISCIPLINE_NAME']?$ssc['DISCIPLINE_NAME']:"N/A"?></td>
        <td><?=$ssc['ORGANIZATION']?$ssc['ORGANIZATION']:"N/A"?></td>
        
        <th>HSC</th>
        <td><?=$hsc['OBTAINED_MARKS']?$hsc['OBTAINED_MARKS']:"N/A"?></td>
        <td><?=$hsc['TOTAL_MARKS']?$hsc['TOTAL_MARKS']:"N/A"?></td>
        <td><?=$hsc['PASSING_YEAR']?$hsc['PASSING_YEAR']:"N/A"?></td>
        <td><?=$hsc['DISCIPLINE_NAME']?$hsc['DISCIPLINE_NAME']:"N/A"?></td>
        <td><?=$hsc['ORGANIZATION']?$hsc['ORGANIZATION']:"N/A"?></td>
       
    </tr>
        <?php
    }
    ?>
</table>
<footer></footer>