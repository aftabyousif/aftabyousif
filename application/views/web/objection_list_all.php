<div style="height:100px"></div>
<div class="container-fluid">
	<div class=" text-center">
			<h2 class="title">First Provisional Merit/Selection List - Bachelor Degree Programs <?=SESSION_YEAR?></h2>
			
		<p class="text-center text-danger" style="font-weight: bold; font-size: 18pt"><u>Important Dates : </u></p>
		
		<p class="" style="font-weight: bold; font-size: 18pt">	Last date for submission of objection (if any) at Directorate of Admissions, University of Sindh or through Email (admission@usindh.edu.pk) upto<br><span class="text-danger">24-01-2022 05:00 PM</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">Admission Fees challans for selected candidates will be available on their E-portal accounts on <span class="text-danger"> Tuesday 25-01-2022</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">	Last Date for payment of Admission Fees: <span class="text-danger">Wednesday 02-02-2022.</span>.</p>
    
    	<p class=" " style="font-weight: bold; font-size: 18pt">Last Date for submission of all required documents at Shaheed Benazir Bhutto Convention Center, University of Sindh Jamshoro: <span class="text-danger"> Friday 04-02-2022</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">Candidates selected in other campuses of Sindh University will submit their all required documents at their concerned campuses.</p>
	    <!--<p class="text-center text-primary" style="font-weight: bold; font-size: 18pt">LL.M (Law): Last Date for payment of Admission Fees and submission of all required documents at Directorate of Admissions is Friday 30-04-2022.</p>-->
	    <!--<p class="text-center text-info" style="font-weight: bold; font-size: 18pt">For Sindh University Other Campuses: Last Date for payment of Admission Fees and submission of all required documents at respective campuses is Friday 29-01-2022.</p>-->
	<br/>
	<p class="text-center text-success" style="font-weight: bold; font-size: 14pt">
	    Candidates selected in any Provisional Merit / Selection list for Bachelor Degree Programs for the academic year <?=SESSION_YEAR?> can download their admission fees challans from their E-Portal accounts.
	 </p>

	</div>
	<div class="row">
		<div class="col-md-4 col-sm-12 col-xs-12">
			<div class="form-group has-default">
				<label>Campus <span class='text-rose'>*</span></label>
				<select id="campus" class="form-control">
					<option value=""></option>
					<?php
					foreach ($campus as $campus_detail){
						?>
						<option value="<?=$campus_detail['CAMPUS_ID']?>"><?=$campus_detail['NAME']?></option>
					<?php
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-4 col-sm-12 col-xs-12">
			<div class="form-group has-default">
				<label>Program <span class='text-rose'>*</span></label>
				<select id="program_id" class="form-control">
				</select>
			</div>
			<button class="btn btn-info" id="search" onclick="getStatus()"><i class="fa fa-search"></i> Search</button>
			<img src="<?=base_url().'/assets/img/ajax-loader.gif'?>" id="loader" style="display: none">
		</div>
	</div>

	<div class="section">
		<div id="display"></div>
		<!--<div class="clearfix"></div>-->
	</div>
	<!--<div class="space-10"></div>-->
	<div >
		<div class="container tim-container">
			<!--     	        typography -->
			<div id="typography" class="cd-section">
				<div class="title">
					<!--<p class="text-danger">Note: if you have any objection regarding provisional merit list please login to your e-portal account and submit your objection form.</p>-->
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

<script>
	$('#campus').select2();
	$("#campus").change(function (){
		loadPrograms();
	});
	function loadPrograms (){

		let campus_id = $("#campus").val();
		let prog_type_id = 1;
		let shift_id = 1;

		$("#program_id").html('')

		if (campus_id === "" || campus_id === 0 || campus_id == null || isNaN(campus_id))
			return;
		if (prog_type_id === "" || prog_type_id === 0 || prog_type_id == null || isNaN(prog_type_id))
			return;
		if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			return;

		// $("#selected_programs").empty();

		$.ajax({
			url:'<?=base_url()?>web/getMappedPrograms',
			method: 'POST',
			data: {shift_id:shift_id,program_type:prog_type_id,campus_id:campus_id,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				// alert(response)
				// console.log(response);
				let i=0;
				$.each(response, function (index,value) {
					// i++;
					// if (value['REMARKS'] == null)
					// 	var remarks = '';
					let option="";
					option+= "<option value='"+value['PROG_ID']+"'>"+value['PROGRAM_TITLE']+"</option>";
					$("#program_id").append(option);
					
				});
				$('#program_id').select2();
			}
		});
	}
	function getStatus (){

		let campus_id = $("#campus").val();
		let program_id = $("#program_id").val();
		let program_type = 1;
		let shift_id = 1;
		let is_ob = 'Y';

// 		let program_type = $("#program_type").val();
		// alert(shift_id);
		if (campus_id === "" || campus_id === 0 || campus_id == null || isNaN(campus_id))
			return;
		if (program_id === "" || program_id === 0 || program_id == null || isNaN(program_id))
			return;

		// campus = 0;
		$("#loader").show();
		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>web/get_program_objection_list',
			method: 'POST',
			data: {campus_id:campus_id,program_type:program_type,program_id:program_id,shift_id:shift_id,is_ob:is_ob},
			// dataType: 'json',
			success: function(response){
				$("#display").html(response);
				$("#loader").hide();
			}
		});
	}
</script>
