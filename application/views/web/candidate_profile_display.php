<?php
$personal_info = $CANDIDATE[0];
$form_data = json_decode($personal_info['FORM_DATA'],true);
$users_reg = $users['users_reg'];
$qualifications = $users['qualifications'];
$DETAIL_CPN = $personal_info['DETAIL_CPN'];
$DETAIL_CPN = json_decode($DETAIL_CPN,true);
$is_specical = false;
//prePrint($DETAIL_CPN);
?>
<style>
	th{
		font-size: 10pt;
		font-family: "Times New Roman", serif;
		text-align: left;
	}
	td{
		font-size: 11pt;
		font-family: "Trirong", serif;
		text-align: center;
	}
</style>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<table class="table table-hover table-bordered">
			<tr>
				<th colspan="8" style="text-align: center;font-size: 12pt" class="bg-primary">Personal Information</th>
			</tr>
			<tr>
				<th colspan="2">Application #</th>
				<td colspan="6"><?=$personal_info['APPLICATION_ID']?></td>
			</tr>
			<tr>
				<th colspan="2">Seat #</th>
			<td colspan="6"><?=$personal_info['CARD_ID']?></td>
			</tr>
			<tr>
				<th colspan="2">Full Name</th>
			<td colspan="6"><?=$users_reg['FIRST_NAME']?></td>
			</tr>
			<tr>
				<th colspan="2">Father's Name</th>
			<td colspan="6"><?=$users_reg['FNAME']?></td>
			</tr>
			<tr>
				<th colspan="2">Surname</th>
				<td colspan="6"><?=$users_reg['LAST_NAME']?></td>
			</tr>

			<tr>
				<th colspan="2">District</th>
				<td colspan="6"><?=$users_reg['DISTRICT_NAME']?></td>
			</tr>


			<tr>
				<th colspan="2">Application Status</th>
				<td colspan="6"><?=$personal_info['STATUS_NAME']?></td>
			</tr>
			<tr>
				<th colspan="2">Message</th>
				<td colspan="6"><p class="text-danger" style="font-weight: bold"><?=$personal_info['MESSAGE']?></p></td>
			</tr>

        
		</table>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		    	<table class="table table-hover table-bordered">
		    	  
			<tr>
				<th colspan="8" style="text-align: center;font-size: 12pt" class="bg-primary">Choice</th>
			</tr>
			<tr>
				<th>Choice No</th>
				<th>Desired Program</th>
			</tr>
			<?php
			foreach ($morning_choice as $choice){
			    if( $choice['IS_SPECIAL_CHOICE'] =='Y'){
			        $is_specical = true;
			        continue;
			    }
		
				?>
				<tr>
					<td><?=$choice['CHOICE_NO']?></td>
					
					<td><?=$choice['PROGRAM_TITLE'];?></td>
				
				</tr>
				<tr>
				
			</tr>
			<?php
			}
			?>
			
		    	    </table>
		    
		    </div>
		    
		    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		    	<table class="table table-hover table-bordered">
		    	  
			<tr>
				<th colspan="8" style="text-align: center;font-size: 12pt" class="bg-primary">Applied Category(ies)</th>
			</tr>
			<tr>
				<th>No</th>
				<th>Category</th>
			</tr>
			<?php
			foreach ($category as $k=>$cat){
			  
		
				?>
				<tr>
					<td><?=++$k?></td>
					
					<td><?=$cat['FORM_CATEGORY_NAME'];?></td>
				
				</tr>
				<tr>
				
			</tr>
			<?php
			}
			?>
			
		    	    </table>
		    
		    </div>
    <?php if($is_specical){
		        ?>
		        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		    	<table class="table table-hover table-bordered">
		    	  
			<tr>
				<th colspan="8" style="text-align: center;font-size: 12pt" class="bg-primary">Special Self Morning Choice</th>
			</tr>
			<tr>
				<th>Choice No</th>
				<th>Desired Program</th>
			</tr>
			<?php
			foreach ($morning_choice as $choice){
			    if( $choice['IS_SPECIAL_CHOICE'] =='N'){
			       
			        continue;
			    }
		
				?>
				<tr>
					<td><?=$choice['CHOICE_NO']?></td>
					
					<td><?=$choice['PROGRAM_TITLE'];?></td>
				
				</tr>
				<tr>
				
			</tr>
			<?php
			}
			?>
			
		    	    </table>
		    
		    </div>
		        <?php
		    }
	?>
	 <?php if(count($evening_choice)){
		        ?>
		        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		    	<table class="table table-hover table-bordered">
		    	  
			<tr>
				<th colspan="8" style="text-align: center;font-size: 12pt" class="bg-primary">Evening Choice</th>
			</tr>
			<tr>
				<th>Choice No</th>
				<th>Desired Program</th>
			</tr>
			<?php
			foreach ($evening_choice as $choice){
			    
		
				?>
				<tr>
					<td><?=$choice['CHOICE_NO']?></td>
					
					<td><?=$choice['PROGRAM_TITLE'];?></td>
				
				</tr>
				<tr>
				
			</tr>
			<?php
			}
			?>
			
		    	    </table>
		    
		    </div>
		        <?php
		    }
	?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		    	<table class="table table-hover table-bordered">
		    	     <?php
            foreach($CANDIDATE as $personal_info){
            $DETAIL_CPN = $personal_info['DETAIL_CPN'];
            $TEST_TYPE = $personal_info['TEST_TYPE'];
            if(!$DETAIL_CPN){
                continue;
            }
            $DETAIL_CPN = json_decode($DETAIL_CPN,true);
            
            if($personal_info['ACTIVE']!=1){
                continue;
            }
            ?>
			<tr>
				<th colspan="9" style="text-align: center;font-size: 12pt" class="bg-primary">Qualification</th>
			</tr>
			<tr>
				<th>Degree Title</th>
				<th>Group</th>
				<th>Total Marks</th>
				<th>Obtained Marks</th>
				<th>Year</th>
				<th>Deduction Marks</th>
				<th>Marks After Deduction</th>
				<th>Percentage</th>
<!--				<th>CPN Weightage % </th>-->
				<th>CPN Percentage</th>
			</tr>
			<?php
			foreach ($DETAIL_CPN as $qualification){
				$cpn_percentage = $qualification['CPN_PERCENTAGE'];
				$percentage = $qualification['PERCENTAGE'];
				if(!$cpn_percentage){
				    $cpn_percentage = 0;
				}
				if(!$percentage){
				  $percentage = 0;  
				}
				$cpn_percentage = $this->TestResult_model->truncate_cpn($cpn_percentage,2);
				$percentage = $this->TestResult_model->truncate_cpn($percentage,2);
				$dis_name  = "";
				if($qualification['DEGREE_TITLE']=='SSC'){
				    $index  = getIndexOfObjectInList($qualifications,"DEGREE_ID",2);
				     $dis_name = $qualifications[$index]['DISCIPLINE_NAME'];
				    
				}else if($qualification['DEGREE_TITLE']=='HSC'){
				  $index  = getIndexOfObjectInList($qualifications,"DEGREE_ID",3);   
				   $dis_name = $qualifications[$index]['DISCIPLINE_NAME'];
				}else if($qualification['DEGREE_TITLE']=='GRADUATION'){
				    $dis_name = $qualifications[0]['DISCIPLINE_NAME'];
				    
				}
				 
				
				    //prePrint( $qualifications[$index]);
			
				

				?>
				<tr>
					<td><?=$qualification['DEGREE_TITLE']?></td>
						<td><?=$dis_name?></td>
					
					<td><?=isset($qualification['TOTAL_MARKS'])?$qualification['TOTAL_MARKS']:'0';?></td>
					<td><?=isset($qualification['OBTAINED_MARKS'])?$qualification['OBTAINED_MARKS']:'0';?></td>
					<td><?=isset($qualification['PASSING_YEAR'])?$qualification['PASSING_YEAR']:'0';?></td>
				
					<td><?=isset($qualification['DEDUCT_MARKS'])?$qualification['DEDUCT_MARKS']:'0';?></td>
					<td><?=isset($qualification['AFTER_DEDUCT_MARKS'])?$qualification['AFTER_DEDUCT_MARKS']:'0';?></td>
					<td><?=$percentage?></td>
<!--					<td>--><?//=$qualification['CPN_WEIGHTAGE_IN_PERCENTAGE'].' % of '.$qualification['PERCENTAGE'].'%'?><!--</td>-->
					<td><?=$cpn_percentage?></td>
				</tr>
				<tr>
				
			</tr>
			<?php
			}
			?>
			<th colspan="9" style="text-align: right;font-size: 12pt;color:white" class="bg-success"><?=$TEST_TYPE?> CPN <?=$this->TestResult_model->truncate_cpn($personal_info['CPN']?$personal_info['CPN']:0)?></th>
			<?php
			
            }
			?>
		    	    </table>
		    
		    </div>

</div>
