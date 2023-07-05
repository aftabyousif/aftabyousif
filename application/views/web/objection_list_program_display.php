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
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<table class="table table-hover table-bordered">
			<tr>
				<th colspan="15" style="text-align: center;font-size: 12pt" class="bg-primary">Provisional Merit List</th>
			</tr>
			<tr>
				<th>Application #</th>
				<th>Seat #</th>
				<th>Name</th>
				<th>Father's Name</th>
				<th>Surname</th>
				<th>District</th>
				
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
				?>
				<tr>
					<td><?=$choices['APPLICATION_ID']?></td>
					<td><?=$choices['CARD_ID']?></td>
					<td><?=$choices['FIRST_NAME']?></td>
					<td><?=$choices['FNAME']?></td>
					<td><?=$choices['LAST_NAME']?></td>
					<td><?=$choices['DISTRICT_NAME']?></td>
				
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
