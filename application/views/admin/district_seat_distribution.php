<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15">
	<div class="sparkline10-list">
		<div class="container-fluid">
			<div class="sparkline10-hd">
				<div class="main-sparkline10-hd text-center bg-warning">
					<h1>District Seat Distribution</h1>
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

<!--				<div class="col-md-4">-->
<!--					<label>Category Type <span class="text-danger"> *</span> </label>-->
<!--					<select name="category_type_id" id="category_type_id" onchange="loadCategory ();" class="form-control">-->
<!--						<option value=""></option>-->
<!--						--><?php
//						foreach ($category_types as $category_type)
//						{
//							?>
<!--							<option value=--><?//=$category_type['CATEGORY_TYPE_ID']?><!--><?//=$category_type['CATEGORY_NAME']?><!--</option>";-->
<!--							--><?php
//						}
//						unset($category_types);
//						unset($category_type);
//						?>
<!--					</select>-->
<!--				</div>-->

<!--				<div class="col-md-4">-->
<!--					<label>Category <span class="text-danger"> *</span> </label>-->
<!--					<select name="category_id" id="category_id" class="form-control">-->
<!--						<option value=""></option>-->
<!--					</select>-->
<!--				</div>-->


				<div class="col-md-5">
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

				<div class="col-md-3">
					<label>District <span class="text-danger"> *</span> </label>
					<select name="district_id" id="district_id" class="form-control">
						<option value=""></option>
						<?php
						$new_array = array();
						foreach ($districts as $district)
						{
							$district_id = $district['DISTRICT_ID'];
							$district_name = $district['DISTRICT_NAME'];
							$new_array[$district_id]=$district_name;

							?>
							<option value=<?=$district['DISTRICT_ID']?>><?=$district['DISTRICT_NAME']?></option>";
							<?php
						}
						?>
					</select>
					<?php
					$new_array = json_encode($new_array);
					//					prePrint($new_array);
					?>
					<script type="text/javascript">
						sessionStorage.setItem("quota_district_names",JSON.stringify(<?=$new_array?>));
					</script>
				</div>

				<div class="col-md-2">
					<label>Prog Type <span class="text-danger"> </span> </label>
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
					<label>Rural Seats <span class="text-danger"> </span> </label>
					<input type="number" min="0" name="rural_seats" id="rural_seats" class="form-control">
				</div>
				<div class="col-md-2">
					<label>Urban Seats <span class="text-danger"> </span> </label>
					<input type="number" min="0" name="urban_seats" id="urban_seats" class="form-control">
				</div>

				<div class="col-md-2">
					<label>Total Seats <span class="text-danger"> </span> </label>
					<input type="number" min="0" name="total_seats" id="total_seats" class="form-control">
				</div>
			</div>

			<button type="button"  id="save" name="save" class="btn btn-primary">Save & Update</button>
			<span class="text-danger" id="msg"></span>
			</form>
			<br/><br/>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12"
				<div class="table-responsive">
					<table class="table table-hover table-bordered" style="font-size:11px">
						<thead>
						<th>S.NO</th>
						<th>DISTRICT QUOTA ID</th>
						<th>DISTRICT NAME</th>
						<th>PROGRAM TITLE</th>
						<th>RURAL SEATS</th>
						<th>URBAN SEATS</th>
						<th>TOTAL SEATS</th>
						<th>RURAL REM: SEATS</th>
						<th>URBAN REM: SEATS</th>
						<th>TOTAL REM: SEATS</th>
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
		// let category_type_id = $("#category_type_id").val();
		let prog_type_id = $("#prog_type_id").val();
		let shift_id = $("#shift_id").val();
		let session_id = $("#session_id").val();
		let prog_list_id = $("#prog_list_id").val();
		// let category_id = $("#category_id").val();
		let urban_seats = $("#urban_seats").val();
		let rural_seats = $("#rural_seats").val();
		let total_seats = $("#total_seats").val();
		let district_id = $("#district_id").val();

		if (campus === "" || campus === 0 || campus == null || isNaN(campus))
			return;
		// else if (category_type_id === "" || category_type_id === 0 || category_type_id == null || isNaN(category_type_id))
		// 	return;
		// else if (prog_type_id === "" || prog_type_id === 0 || prog_type_id == null || isNaN(prog_type_id))
		// 	return;
		else if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			return;
		else if (session_id === "" || session_id === 0 || session_id == null || isNaN(session_id))
			return;
		else if (prog_list_id.length === 0)
			return;
		// else if (category_id === "" || category_id === 0 || category_id == null || isNaN(category_id))
		// 	return;
		else if (district_id === "" || district_id === 0 || district_id == null || isNaN(district_id))
			return;
		else if (total_seats === "" || total_seats === 0 || total_seats == null || isNaN(total_seats))
			total_seats = 0;
		else if (urban_seats === "" || urban_seats === 0 || urban_seats == null || isNaN(urban_seats))
			urban_seats = 0;
		else if (rural_seats === "" || rural_seats === 0 || rural_seats == null || isNaN(rural_seats))
			rural_seats = 0;

		$.ajax({
			url:'<?=base_url()?>mapping/save_district_quota_seat_distribution',
			method: 'POST',
			data: {prog_list_id:prog_list_id,
				campus:campus,
				// category_type_id:category_type_id,
				// prog_type_id:prog_type_id,
				shift_id:shift_id,
				session_id:session_id,
				// category_id:category_id,
				total_seats:total_seats,
				rural_seats:rural_seats,
				urban_seats:urban_seats,
				district_id:district_id,
			},
			// dataType: 'json',
			success: function(response){
				// console.log(response);
				$("#msg").html(response);
				loadSeatDistribution();
			}
		});
	}

	function DeleteSeatDistribution (DISTRICT_QUOTE_ID){

		if (DISTRICT_QUOTE_ID == null || DISTRICT_QUOTE_ID === "" || isNaN(DISTRICT_QUOTE_ID))
		{
			alert("Please reload page & try again...")
			return;
		}

		if (confirm("Do you want to delete?") === false) return;

		$.ajax({
			url:'<?=base_url()?>mapping/delete_district_quota_seat_distribution',
			method: 'POST',
			data: {DISTRICT_QUOTE_ID:DISTRICT_QUOTE_ID
			},
			success: function(response){
				alertMsg("Message",response);
				loadSeatDistribution();
			}
		});
	}

	function updateSeatDistribution (DISTRICT_QUOTE_ID,RURAL_SEATS,URBAN_SEATS,TOTAL_SEATS) {

		let TOTAL_SEATS_INPUT = parseInt($("#TOTAL_SEATS" + DISTRICT_QUOTE_ID).val());
		let RURAL_SEATS_INPUT = parseInt($("#RURAL_SEATS" + DISTRICT_QUOTE_ID).val());
		let URBAN_SEATS_INPUT = parseInt($("#URBAN_SEATS" + DISTRICT_QUOTE_ID).val());

		if (TOTAL_SEATS_INPUT == null || TOTAL_SEATS_INPUT === "" || isNaN(TOTAL_SEATS_INPUT)) TOTAL_SEATS_INPUT=0;
		if (RURAL_SEATS_INPUT == null || RURAL_SEATS_INPUT === "" || isNaN(RURAL_SEATS_INPUT)) RURAL_SEATS_INPUT=0;
		if (URBAN_SEATS_INPUT == null || URBAN_SEATS_INPUT === "" || isNaN(URBAN_SEATS_INPUT)) URBAN_SEATS_INPUT=0;

		if (DISTRICT_QUOTE_ID == null || DISTRICT_QUOTE_ID === "" || isNaN(DISTRICT_QUOTE_ID)) {
			alert("Please reload page & try again...")
			return;
		} else if (TOTAL_SEATS_INPUT === TOTAL_SEATS && RURAL_SEATS_INPUT === RURAL_SEATS && URBAN_SEATS_INPUT === URBAN_SEATS) {
			// alert("Please write total seats");
			return;
		}
		if (confirm("Do you want to save modifications?") === false) return;

		$.ajax({
			url:'<?=base_url()?>mapping/update_district_quota_seat_distribution',
			method: 'POST',
			async:false,
			data: {DISTRICT_QUOTE_ID:DISTRICT_QUOTE_ID,TOTAL_SEATS_INPUT:TOTAL_SEATS_INPUT,RURAL_SEATS_INPUT:RURAL_SEATS_INPUT,URBAN_SEATS_INPUT:URBAN_SEATS_INPUT
			},
			// dataType: 'json',
			success: function(response){
				alertMsg("Message",response);
				loadSeatDistribution();
			}
		});
	}

	function loadSeatDistribution (){

		$("#table_data").empty();

		let campus = $("#campus").val();
		let category_type_id = $("#category_type_id").val();
		let prog_type_id = $("#prog_type_id").val();
		let shift_id = $("#shift_id").val();
		let session_id = $("#session_id").val();
		let prog_list_id = $("#prog_list_id").val();
		let district_id = $("#district_id").val();

// 		let program_type = $("#program_type").val();
		// alert(shift_id);
		if (campus === "" || campus === 0 || campus == null || isNaN(campus))
			return;
		// campus = 0;


		$.ajax({
			url:'<?=base_url()?>mapping/getDistrictQuotaSeatDistribution',
			method: 'POST',
			data: {prog_list_id:prog_list_id,campus:campus,prog_type_id:prog_type_id,shift_id:shift_id,session_id:session_id,district_id:district_id},
			dataType: 'json',
			async:false,
			success: function(response){
				// console.log(response);
				let quota_districts_names = sessionStorage.getItem("quota_district_names");
				quota_districts_names = JSON.parse(quota_districts_names);

				let i=0;
				let sum_urban=0;
				let sum_rural=0;
				let sum_total=0;
				
				let sum_urban_r=0;
				let sum_rural_r=0;
				let sum_total_r=0;
				
				$.each(response, function (index,value) {
					i++;
					let DISTRICT_QUOTE_ID = value['DISTRICT_QUOTE_ID'];
					let DISTRICT_ID = value['DISTRICT_ID'];
					let RURAL_SEATS = parseInt(value['RURAL_SEATS']);
					let URBAN_SEATS = parseInt(value['URBAN_SEATS']);

					let RURAL_SEATS_REMAINING = parseInt(value['RURAL_SEATS_REMAINING']);
					let URBAN_SEATS_REMAINING = parseInt(value['URBAN_SEATS_REMAINING']);

					let TOTAL_SEATS = parseInt(value['TOTAL_SEATS']);
					let TOTAL_SEATS_REMAINING = parseInt(value['TOTAL_SEATS_REMAINING']);

					let district_name = quota_districts_names[DISTRICT_ID];

	             sum_urban+=URBAN_SEATS;
				 sum_rural+=RURAL_SEATS;
				 sum_total+=TOTAL_SEATS;
				
				sum_urban_r+=URBAN_SEATS_REMAINING;
				 sum_rural_r+=RURAL_SEATS_REMAINING;
				 sum_total_r+=TOTAL_SEATS_REMAINING;
				 
					let tr="<tr>";
					tr+= "<td>"+i+"</td>";
					tr+= "<td>"+DISTRICT_QUOTE_ID+"</td>";
					tr+= "<td>"+district_name+"</td>";
					tr+= "<td>"+value['PROGRAM_TITLE']+"</td>";
					tr+= "<td><input type='number' style='width: 70px;' min='0' value='"+RURAL_SEATS+"' id='RURAL_SEATS"+DISTRICT_QUOTE_ID+"' onblur='updateSeatDistribution("+DISTRICT_QUOTE_ID+","+RURAL_SEATS+","+URBAN_SEATS+","+TOTAL_SEATS+")'></td>";

					// tr+= "<td>"+RURAL_SEATS+"</td>";
					// tr+= "<td>"+URBAN_SEATS+"</td>";
					tr+= "<td><input type='number' min='0' style='width: 70px;' value='"+URBAN_SEATS+"' id='URBAN_SEATS"+DISTRICT_QUOTE_ID+"' onblur='updateSeatDistribution("+DISTRICT_QUOTE_ID+","+RURAL_SEATS+","+URBAN_SEATS+","+TOTAL_SEATS+")'></td>";
					// tr+= "<td>"+TOTAL_SEATS+"</td>";
					tr+= "<td><input type='number' min='0' style='width: 70px;' value='"+TOTAL_SEATS+"' id='TOTAL_SEATS"+DISTRICT_QUOTE_ID+"' onblur='updateSeatDistribution("+DISTRICT_QUOTE_ID+","+RURAL_SEATS+","+URBAN_SEATS+","+TOTAL_SEATS+")'></td>";

					tr+= "<td>"+RURAL_SEATS_REMAINING+"</td>";
					tr+= "<td>"+URBAN_SEATS_REMAINING+"</td>";
					tr+= "<td>"+TOTAL_SEATS_REMAINING+"</td>";


					tr+= "<td><a href='javascript:void(0)' title='Please click carefully this row will be deleted from database' onclick=DeleteSeatDistribution("+DISTRICT_QUOTE_ID+");>Delete</a></td>";
					tr+="</tr>";
					$("#table_data").append(tr);
				});
				    let tr ="<tr>";
					tr+= "<th colspan=4>Total</th>";
					tr+= "<td>"+sum_rural+"</td>";
					tr+= "<td>"+sum_urban+"</td>";
					tr+= "<td>"+sum_total+"</td>";
				    tr+= "<td>"+sum_rural_r+"</td>";
					tr+= "<td>"+sum_urban_r+"</td>";
					tr+= "<td>"+sum_total_r+"</td>";
					tr+="</tr>";
					$("#table_data").append(tr);
			}
		});
	}

	$("#prog_type_id,#campus,#shift_id,#session_id,#district_id").change(function (){
		loadPrograms();
		loadSeatDistribution();
	});

	$("#prog_list_id").change(function (){
		loadSeatDistribution();
	});

	$(document).ready(function () {
	});

</script>
