<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15">
	<div class="sparkline10-list">
		<div class="container-fluid">
			<div class="sparkline10-hd">
				<div class="main-sparkline10-hd text-center bg-warning">
					<h1>Discipline Seat Distribution</h1>
				</div>
			</div>
			<?php
			if($this->session->flashdata('message'))
			{
				echo '
                    <div class="alert alert-warning">
                        '.$this->session->flashdata("message").'
                    </div>
                    ';
			}
			?>
			<!--		<form id="form" action="save_shift_program_mapping" method="post" class="wizard-big">-->
			<div class="row">
				<div class="col-md-2">
					<label>Session
					<span class="text-danger"> *</span>
					</label>
					<select name="session_id" id="session_id" class="form-control">
						<option value=""></option>
						<?php
						foreach ($sessions as $session)
						{
							?>
							<option value=<?=$session['SESSION_ID']?>><?=$session['YEAR']?> <?=$session['BATCH_REMARKS']?></option>";
							<?php
						}
						unset($sessions);
						unset($session);
						?>
					</select>
				</div>

				<div class="col-md-2">
					<label>Shift <span class="text-danger"> *</span> </label>
					<select name="shift_id" id="shift_id" class="form-control">
						<option value=""></option>
						<?php
						foreach ($shifts as $shift)
						{
							?>
							<option value=<?=$shift['SHIFT_ID']?>><?=$shift['SHIFT_NAME']?></option>";
							<?php
						}
						unset($sessions);
						unset($session);
						?>
					</select>
				</div>

				<div class="col-md-4">
					<label>Category Type <span class="text-danger"> *</span> </label>
					<select name="category_type_id" id="category_type_id" onchange="loadCategory ();" class="form-control">
						<option value=""></option>
						<?php
						foreach ($category_types as $category_type)
						{
							?>
							<option value=<?=$category_type['CATEGORY_TYPE_ID']?>><?=$category_type['CATEGORY_NAME']?></option>";
							<?php
						}
						unset($category_types);
						unset($category_type);
						?>
					</select>
				</div>

				<div class="col-md-4">
					<label>Category <span class="text-danger"> *</span> </label>
					<select name="category_id" id="category_id" class="form-control">
						<option value=""></option>
					</select>
				</div>


				<div class="col-md-4">
					<label>Campus <span class="text-danger"> *</span> </label>
					<select name="campus" id="campus" class="form-control">
						<option value=""></option>
						<?php
						foreach ($campus as $campus_value)
						{
							?>
							<option value=<?=$campus_value['CAMPUS_ID']?>><?=$campus_value['NAME']?></option>";
							<?php
						}
						unset($campus);
						unset($campus_value);
						?>
					</select>
				</div>
				<div class="col-md-2">
					<label>Prog Type <span class="text-danger"> *</span> </label>
					<select name="prog_type_id" id="prog_type_id" class="form-control">
						<option value=""></option>
						<?php

						foreach ($program_types as $program_type)
						{
							?>
							<option value=<?=$program_type['PROGRAM_TYPE_ID']?>><?=$program_type['PROGRAM_TITLE']?></option>";
							<?php
						}
						unset($program_types);
						unset($program_type);
						?>
					</select>
				</div>

				<div class="col-md-3">
					<label>Program <span class="text-danger"> *</span> </label>
					<select name="prog_list_id" id="prog_list_id" class="form-control" size="5" multiple>
						<option value=""></option>
					</select>
				</div>

				<div class="col-md-2">
					<label>Total Seats <span class="text-danger"> *</span> </label>
					<input type="number" min="0" name="total_seats" id="total_seats" class="form-control">
				</div>
			</div>
			<br/>
			<button type="button"  id="save" name="save" class="btn btn-primary">Save & Update</button>
			<span class="text-danger" id="msg"></span>
			</form>
			<br/>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12"
				<div class="table-responsive">
					<table class="table">
						<thead>
						<th>S.NO</th>
						<th>DISCIPLINE SEAT ID</th>
						<th>CATEGORY TYPE</th>
						<th>CATEGORY TITLE</th>
						<th>PROGRAM TITLE</th>
						<th>TOTAL SEATS</th>
						<th>REMAINING SEATS</th>
						<th>ACTION</th>
						</thead>
						<tbody id="table_data">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $CI =& get_instance(); ?>
<script>
	var csrf_name = '<?php echo $CI->security->get_csrf_token_name(); ?>';
	var csrf_hash = '<?php echo $CI->security->get_csrf_hash(); ?>';
</script>

<script type="text/javascript">

	$("#save").click(function (){
		if (confirm("Do you want to save?"))
		{
			SaveSeatDistribution();
		}
	})

	function loadCategory (){

		let category_type_id = $("#category_type_id").val();
		// alert(shift_id);
		$("#category_id").html('')
		if (category_type_id === "" || category_type_id === 0 || category_type_id == null || isNaN(category_type_id))
			return;
		// $("#selected_programs").empty();

		$.ajax({
			url:'<?=base_url()?>mapping/getMappedCategory',
			method: 'POST',
			data: {category_type_id:category_type_id,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				let i=0;
				$.each(response, function (index,value) {
					// i++;
					// if (value['REMARKS'] == null)
					// 	var remarks = '';
					let option="";
					option+= "<option value='"+value['CATEGORY_ID']+"'>"+value['CATEGORY_NAME']+"</option>";
					$("#category_id").append(option);
				});
			}
		});
	}

	function loadPrograms (){

		let campus_id = $("#campus").val();
		let prog_type_id = $("#prog_type_id").val();
		let shift_id = $("#shift_id").val();
		// alert(shift_id);
		$("#prog_list_id").html('')

		if (campus_id === "" || campus_id === 0 || campus_id == null || isNaN(campus_id))
			return;
		if (prog_type_id === "" || prog_type_id === 0 || prog_type_id == null || isNaN(prog_type_id))
			return;
		if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			return;

		// $("#selected_programs").empty();

		$.ajax({
			url:'<?=base_url()?>mapping/getMappedPrograms',
			method: 'POST',
			data: {shift_id:shift_id,program_type:prog_type_id,campus_id:campus_id,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				let i=0;
				$.each(response, function (index,value) {
					// i++;
					// if (value['REMARKS'] == null)
					// 	var remarks = '';
					let option="";
					option+= "<option value='"+value['PROG_ID']+"'>"+value['PROGRAM_TITLE']+"</option>";
					$("#prog_list_id").append(option);
				});
			}
		});
	}

	function SaveSeatDistribution (){

		let campus = $("#campus").val();
		let category_type_id = $("#category_type_id").val();
		let prog_type_id = $("#prog_type_id").val();
		let shift_id = $("#shift_id").val();
		let session_id = $("#session_id").val();
		let prog_list_id = $("#prog_list_id").val();
		let category_id = $("#category_id").val();
		let total_seats = $("#total_seats").val();

		if (campus === "" || campus === 0 || campus == null || isNaN(campus))
			return;
		else if (category_type_id === "" || category_type_id === 0 || category_type_id == null || isNaN(category_type_id))
			return;
		else if (prog_type_id === "" || prog_type_id === 0 || prog_type_id == null || isNaN(prog_type_id))
			return;
		else if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			return;
		else if (session_id === "" || session_id === 0 || session_id == null || isNaN(session_id))
			return;
		else if (prog_list_id.length === 0)
			return;
		else if (category_id === "" || category_id === 0 || category_id == null || isNaN(category_id))
			return;
		else if (total_seats === "" || total_seats === 0 || total_seats == null || isNaN(total_seats))
			return;

		$.ajax({
			url:'<?=base_url()?>mapping/save_discipline_seat_distribution',
			method: 'POST',
			data: {prog_list_id:prog_list_id,
					campus:campus,
					category_type_id:category_type_id,
					prog_type_id:prog_type_id,
					shift_id:shift_id,
					session_id:session_id,
					category_id:category_id,
					total_seats:total_seats
					},
			// dataType: 'json',
			success: function(response){
				// console.log(response);
				$("#msg").html(response);
				loadSeatDistribution();
			}
		});
	}


	function DeleteSeatDistribution (seat_distribution_id){

		if (seat_distribution_id == null || seat_distribution_id === "" || isNaN(seat_distribution_id))
		{
			alert("Please reload page & try again...")
			return;
		}

		if (confirm("Do you want to delete?") === false) return;

		$.ajax({
			url:'<?=base_url()?>mapping/delete_discipline_seat_distribution',
			method: 'POST',
			data: {seat_distribution_id:seat_distribution_id
			},
			// dataType: 'json',
			success: function(response){
				// console.log(response);
				// $("#msg").html(response);
				// alert(response);
				alertMsg("Message",response);
				loadSeatDistribution();
			}
		});
	}

	function updateSeatDistribution (seat_distribution_id) {

		let total_seats = $("#total_seats" + seat_distribution_id).val();

		if (localStorage.getItem(seat_distribution_id) != null) {
			if (localStorage.getItem(seat_distribution_id) === total_seats){
				return;
			}
		}else
		{
			alert("Please reload page & try again...")
			return;
		}

		if (seat_distribution_id == null || seat_distribution_id === "" || isNaN(seat_distribution_id)) {
			alert("Please reload page & try again...")
			return;
		} else if (total_seats == null || total_seats === "" || isNaN(total_seats)) {
			alert("Please write total seats");
			return;
		}

		if (confirm("Do you want to save?") === false) return;

		$.ajax({
			url:'<?=base_url()?>mapping/update_discipline_seat_distribution',
			method: 'POST',
			data: {seat_distribution_id:seat_distribution_id,total_seats:total_seats
			},
			// dataType: 'json',
			success: function(response){
				alertMsg("Message",response);
				loadSeatDistribution();
			}
		});
	}

	function loadSeatDistribution (){

		let campus = $("#campus").val();
		let category_type_id = $("#category_type_id").val();
		let prog_type_id = $("#prog_type_id").val();
		let shift_id = $("#shift_id").val();
		let session_id = $("#session_id").val();
		let prog_list_id = $("#prog_list_id").val();

// 		let program_type = $("#program_type").val();
		// alert(shift_id);
		if (campus === "" || campus === 0 || campus == null || isNaN(campus))
			return;
		// campus = 0;

		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>mapping/getDisciplineSeatDistribution',
			method: 'POST',
			data: {prog_list_id:prog_list_id,campus:campus,category_type_id:category_type_id,prog_type_id:prog_type_id,shift_id:shift_id,session_id:session_id},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				let i=0;
				let sum_total = 0;
				let sum_remain_total = 0;
				$.each(response, function (index,value) {
					i++;

					let discipline_seat_id = value['DISCIPLINE_SEAT_ID'];
					let total_seats = value['TOTAL_SEATS'];
					localStorage.setItem(discipline_seat_id,total_seats);
					let tr="<tr>";
					tr+= "<td>"+i+"</td>";
					tr+= "<td>"+value['DISCIPLINE_SEAT_ID']+"</td>";
					tr+= "<td>"+value['CATEGORY_TYPE_NAME']+"</td>";
					tr+= "<td>"+value['CATEGORY_NAME']+"</td>";
					tr+= "<td>"+value['PROGRAM_TITLE']+"</td>";
					tr+= "<td><input type='number' min='0' value='"+value['TOTAL_SEATS']+"' id='total_seats"+value['DISCIPLINE_SEAT_ID']+"' onblur='updateSeatDistribution("+discipline_seat_id+")'></td>";
					tr+= "<td>"+value['TOTAL_SEATS_REMAINING']+"</td>";
					// tr+= "<td>-</td>";
					tr+= "<td><a href='javascript:void(0)' onclick=DeleteSeatDistribution("+discipline_seat_id+");>Delete</a></td>";
					tr+="</tr>";
					sum_total+=1*value['TOTAL_SEATS'];
					sum_remain_total+=1*value['TOTAL_SEATS_REMAINING'];
					$("#table_data").append(tr);
				});
				let tr="<tr>";
					tr+= "<td></td>";
					tr+= "<td></td>";
					tr+= "<td></td>";
					tr+= "<td></td>";
					tr+= "<td></td>";
					tr+= "<td>"+sum_total+"</td>";
					tr+= "<td>"+sum_remain_total+"</td>";
					// tr+= "<td>-</td>";
					tr+= "<td></td>";
					tr+="</tr>";
				$("#table_data").append(tr);
			}
		});
	}

	$("#prog_type_id,#campus,#shift_id,#session_id").change(function (){
		loadPrograms();
		loadSeatDistribution();
	});

	$("#prog_list_id").change(function (){
		loadSeatDistribution();
	});

	$(document).ready(function () {

	});

</script>
