<?php
$personal_info = $CANDIDATE[0];
$DETAIL_CPN = $personal_info['DETAIL_CPN'];
$DETAIL_CPN = json_decode($DETAIL_CPN,true);

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
			<td colspan="6"><?=$personal_info['FIRST_NAME']?></td>
			</tr>
			<tr>
				<th colspan="2">Father's Name</th>
			<td colspan="6"><?=$personal_info['FNAME']?></td>
			</tr>
			<tr>
				<th colspan="2">Surname</th>
				<td colspan="6"><?=$personal_info['LAST_NAME']?></td>
			</tr>

			<tr>
				<th colspan="2">District</th>
				<td colspan="6"><?=$personal_info['DISTRICT_NAME']?></td>
			</tr>

		
			<tr>
				<th colspan="2">Application Status</th>
				<td colspan="6"><?=$personal_info['STATUS_NAME']?></td>
			</tr>
			<tr>
				<th colspan="2">Message</th>
				<td colspan="6"><p class="text-danger" style="font-weight: bold"><?=$personal_info['MESSAGE']?></p></td>
			</tr>


			<tr>
				<th colspan="8" style="text-align: center;font-size: 12pt" class="bg-primary">Qualification</th>
			</tr>
			<tr>
				<th>Degree Title</th>
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
				$cpn_percentage = $this->TestResult_model->truncate_cpn($cpn_percentage,2);
				$percentage = $this->TestResult_model->truncate_cpn($percentage,2);

				?>
				<tr>
					<td><?=$qualification['DEGREE_TITLE']?></td>
					
					<td><?=isset($qualification['TOTAL_MARKS'])?$qualification['TOTAL_MARKS']:'0';?></td>
					<td><?=isset($qualification['OBTAINED_MARKS'])?$qualification['OBTAINED_MARKS']:'0';?></td>
					<td><?=isset($qualification['PASSING_YEAR'])?$qualification['PASSING_YEAR']:'0';?></td>
				
					<td><?=isset($qualification['DEDUCT_MARKS'])?$qualification['DEDUCT_MARKS']:'0';?></td>
					<td><?=isset($qualification['AFTER_DEDUCT_MARKS'])?$qualification['AFTER_DEDUCT_MARKS']:'0';?></td>
					<td><?=$percentage?></td>
<!--					<td>--><?//=$qualification['CPN_WEIGHTAGE_IN_PERCENTAGE'].' % of '.$qualification['PERCENTAGE'].'%'?><!--</td>-->
					<td><?=$cpn_percentage?></td>
				</tr>
			<?php
			}
			?>
		</table>
	</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<table class="table table-hover table-bordered">
				<tr>
					<th colspan="8" style="text-align: center;font-size: 12pt" class="bg-primary">Selection</th>
				</tr>
				<tr>
						<th>Campus</th>
						<th>Degree Program</th>
						<th>Choice</th>
						<th>Shift</th>
						<th>Category</th>
						<th>List No</th>
						<th>Choice No</th>
						<th>CPN</th>
				</tr>
				<?php
				foreach ($CANDIDATE as $choices){
					$cpn = $choices['TEST_CPN'];
					$cpn = $this->TestResult_model->truncate_cpn($cpn,2);
					
					if($choices['PROG_LIST_ID']==270){
					    $choices['SHIFT_NAME'] = "WEEKEND";
				 	}
				?>
					<tr>
						<td><?=$choices['NAME']?></td>
						<td><?=$choices['PROGRAM_TITLE_CATE']?></td>
						<td><?=$choices['PROGRAM_TITLE']?></td>
						<td><?=$choices['SHIFT_NAME']?></td>
						<td><?=$choices['CATEGORY_NAME']?></td>
						<td><?=($choices['LIST_NO']%10)?></td>
						<td><?=$choices['CHOICE_NO']?></td>
						<td><?=$cpn?></td>
					</tr>
				<?php
				}
				?>
			</table>
	</div>
</div>
