<div style="height:100px"></div>
<div class="container-fluid">
	<div class=" text-center">

		<h2 class="title">Second Provisional Selection List - Master Degree Programs <?=SESSION_YEAR?></h2>
			
		<p class="text-center text-danger" style="font-weight: bold; font-size: 18pt"><u>Important Dates : </u></p>
		<!--<p class="" style="font-weight: bold; font-size: 18pt">	Last date for submission of objection (if any) at Directorate of Admissions OR send through email at admission@usindh.edu.pk upto: <span class="text-danger">Satuday 10-12-2022 05:00 PM</span>.</p>-->
		
    	<p class=" " style="font-weight: bold; font-size: 18pt">Admission Fees challans for selected candidates will be available on their E-portal accounts on <span class="text-danger"> Wednesday 11-01-2023</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">Last Date for payment of Admission Fees: <span class="text-danger"> Wednesday 18-01-2023</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">Last Date for submission of all required documents at Directorate of Admissions: <span class="text-danger"> Friday 20-01-2023</span>.</p>
	    <!--<p class="text-center text-primary" style="font-weight: bold; font-size: 18pt">LL.M (Law): Last Date for payment of Admission Fees and submission of all required documents at Directorate of Admissions is Friday 30-04-2022.</p>-->
	    <p class="text-center text-info" style="font-weight: bold; font-size: 18pt">For Sindh University Other Campuses: Last Date for payment of Admission Fees and submission of all required documents at respective campuses is Friday 20-01-2023.</p>
	<br/>
	<p class="text-center text-success" style="font-weight: bold; font-size: 14pt">
	    Candidates selected in any Provisional Merit / Selection list can download their admission fees challans from their E-Portal accounts.
	 </p>

		<h3 class="title">Department wise</h3>
	</div>
	<div class="row">
	   
	   	<div class="col-md-2 col-sm-12 col-xs-12">
			<div class="form-group has-default">
				<label>Shift <span class='text-rose'>*</span></label>
				<select id="shift" class="form-control">
					<option value=""></option>
					<?php
					foreach ($shifts as $shift){
						?>
						<option value="<?=$shift['SHIFT_ID']?>"><?=$shift['SHIFT_NAME']?></option>
					<?php
					}
					?>
					<option value="3">WEEKEND EVENING PROGRAM</option>
				</select>
			</div>
		</div>

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
		
			<div class="col-md-2 col-sm-12 col-xs-12">
			<div class="form-group has-default">
				<label>List No <span class='text-rose'>*</span></label>
				<select id="list_no" class="form-control">
					<option value=""></option>
					<?php
		              //  web_list_no_dropDown();
					?>
					<!--<option value='11'>Special Self Finance List 1</option>-->
					<!--<option value='12'>Special Self Finance List 2</option>-->
					<!--<option value='21'>EVENING Self Finance List 1</option>-->
				</select>
			</div>
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
			<p class="text-danger">The provisional selection/admission is allowed on the basis of data submitted by candidates. The provisional selection/admission shall be cancelled if any error found at any stage.</p>
	<p class="">
	    University of Sindh reserves the right to rectify any error / omission detected at any stage and also reserves the right to cancel any Provisional Admission at any time without issuing prior notice.
	</p>
				
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
    $("#campus,#shift").select2();
	$("#campus,#shift").change(function (){
		loadPrograms();
		get_list ();
	});
	
	function get_list (){

		let campus_id = $("#campus").val();
		let prog_type_id = 2;
		let shift_id = $("#shift").val();
        if(shift_id==3){
            shift_id = 1;
        }
		$("#list_no").html('')

		if (campus_id === "" || campus_id === 0 || campus_id == null || isNaN(campus_id))
			return;
		if (prog_type_id === "" || prog_type_id === 0 || prog_type_id == null || isNaN(prog_type_id))
			return;
		if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			return;

		// $("#selected_programs").empty();

		$.ajax({
			url:'<?=base_url()?>web/get_list',
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
					option+= "<option value='"+value['LIST_NO']+"'>"+value['LIST_TITLE']+"</option>";
					$("#list_no").append(option);
				});
				$("#list_no").select2();
			}
		});
	}
	function loadPrograms (){

		let campus_id = $("#campus").val();
		let prog_type_id = 2;
		var shift_id = $("#shift").val();

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
			     console.log(response);
				let i=0;
			
				if(shift_id==3){
				response = [
				    {'PROG_ID':270,'PROGRAM_TITLE':"B.ED. (HONS) SECONDARY 1.5 YEAR (WEEKEND PROGRAM)"},
				    {'PROG_ID':281,'PROGRAM_TITLE':"B.ED. (HONS) SECONDARY 2.5 YEAR (WEEKEND PROGRAM)"}
				];
				
				console.log(response);
				
				}
				
				$.each(response, function (index,value) {
					// i++;
					// if (value['REMARKS'] == null)
					// 	var remarks = '';
					let option="";
					if(shift_id==1 && (value['PROG_ID']==270 || value['PROG_ID']==281)){
					    return;
					}
					option+= "<option value='"+value['PROG_ID']+"'>"+value['PROGRAM_TITLE']+"</option>";
					$("#program_id").append(option);
				});
				$("#program_id").select2();
			}
		});
	}
	function getStatus (){

		let campus_id = $("#campus").val();
		let program_id = $("#program_id").val();
		let program_type = 2;
		let shift_id = $("#shift").val();
		let is_ob = '<?=IS_PROVISIONAL_MASTER?>';
		let list_no = $("#list_no").val();

        //let program_type = $("#program_type").val();
		//alert(shift_id);
		if (campus_id === "" || campus_id === 0 || campus_id == null || isNaN(campus_id))
			return;
		if (program_id === "" || program_id === 0 || program_id == null || isNaN(program_id))
			return;
		if (list_no === "" || list_no === 0 || list_no == null || isNaN(list_no))
			return;
		if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			return;
			
        var acutal_shift = shift_id;	
        if(shift_id==3){
            shift_id= 1;
        }
		// campus = 0;
		$("#loader").show();
		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>web/get_program_objection_list',
			method: 'POST',
			data: {campus_id:campus_id,program_type:program_type,program_id:program_id,shift_id:shift_id,is_ob:is_ob,list_no:list_no},
			// dataType: 'json',
			success: function(response){
				$("#display").html(response);
				$("#loader").hide();
				if(acutal_shift==3){
				  	columnTh = $("table td:contains('MORNING')"); 
                
                // Get the index & increment by 1 to match nth-child indexing
                                columnIndex = columnTh.index() + 1; // Set all the elements with that index in a tr red
                $('table tr td:nth-child(' + columnIndex + ')').html("WEEKEND");   
				}
			
			}
		});
	}
</script>
