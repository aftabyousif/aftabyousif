<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
<!--		<form class="container-fluid">-->
			<div class="sparkline10-hd">
				<div class="main-sparkline10-hd text-center bg-warning">
					<h1>Applicant Form Verification List</h1>
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
			$attributes = array ("target"=>"_blank");
			?>
			<?=form_open(base_url().'FormVerification/pdfVerificationList',$attributes)?>
			<div class="row">
				<div class="col-md-2">
					<label>Program Type</label>
					<select name="program_type" id="program_type" onchange="loadCampus ();" class="form-control">
						<option value="0"></option>
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
				<div class="col-md-2">
					<label>Session</label>
					<select name="session" id="session" onchange="loadCampus();" class="form-control">
						<option value="0"></option>
						<?php
						foreach ($academic_sessions as $academic_session)
						{
							?>
							<option value=<?=$academic_session['SESSION_ID']?>><?=$academic_session['YEAR'].' '.$academic_session['BATCH_REMARKS']?></option>";
							<?php
						}
						unset($academic_session);
						unset($academic_sessions);
						?>
					</select>
				</div>
				<div class="col-md-6">
					<label>Campus</label>
					<select name="campus" id="campus" class="form-control">
						<option value="0"></option>
					</select>
				</div>

				<div class="col-md-2">
					<label>Application Status</label>
					<select name="application_status" id="application_status" class="form-control">
						<option value="0"></option>
						<?php
						foreach ($application_status_list as $application_status)
						{
							?>
							<option value=<?=$application_status['STATUS_ID']?>> <?=$application_status['STATUS_NAME']?> </option>";
							<?php
						}
						unset($application_status);
						unset($application_status_list);
						?>
					</select>
				</div>
				<span id="loading"></span>
			</div>
			<br>
			<div class="button-style-two btn-mg-b-10">
				<button type="submit" class="btn btn-custon-rounded-two btn-primary"><i class="fa fa-play edu-informatio" aria-hidden="true"></i> Generate Report</button>
				<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Refresh</button>
			</div>
<!--		</form>-->
	</div>
</div>

<?php $CI =& get_instance(); ?>
<script>
	var csrf_name = '<?php echo $CI->security->get_csrf_token_name(); ?>';
	var csrf_hash = '<?php echo $CI->security->get_csrf_hash(); ?>';
</script>

<script type="text/javascript">

	function loadCampus (){

		let program_type = $("#program_type").val();
		let session = $("#session").val();

		$("#campus").html('');
		$('#loading').html('');
		if ((program_type === "" || program_type === 0 || program_type == null || isNaN(program_type) || session === "") || session === 0 || session == null || isNaN(session))
		{
			return;
		}
		$.ajax({
			url:'<?=base_url()?>FormVerification/getAnnouncedCampus',
			method: 'POST',
			data: {program_type:program_type,session:session,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				$('#loading').html('');
				let i=0;
				let option="";
				option+= "<option value=''></option>";
				$("#campus").append(option);

				$.each(response, function (index,value) {
					// i++;
					// if (value['REMARKS'] == null)
					// 	var remarks = '';
					let option="";
					option+= "<option value='"+value['CAMPUS_ID']+"'>"+value['NAME']+"</option>";
					$("#campus").append(option);
				});
			},
			beforeSend:function (data, status) {

				$('#loading').html("LOADING...!");
				//$('#alert_msg_for_ajax_call').html("LOADING...!");
			},
			error:function (data, status) {
				//var value = data.responseJSON;
				alertMsg("Error",data.responseText);
				//$('input[name="csrf_form_token"]').val(value.csrfHash);
				//$('#alert_msg_for_ajax_call').html(value.MESSAGE);
				$('#loading').html(data);
				// $('.preloader').fadeOut(700);
			},
		});
	}
	/*
	function loadMappedPrograms (){

		let shift_id = $("#shift").val();
		let program_type = $("#program_type").val();
		// alert(shift_id);
		if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			shift_id = 0;
		if (program_type === "" || program_type === 0 || program_type == null || isNaN(program_type))
			program_type = 0;

		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>mapping/getMappedPrograms',
			method: 'POST',
			data: {shift_id:shift_id,program_type:program_type,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				let i=0;
				$.each(response, function (index,value) {
					i++;
					if (value['REMARKS'] == null)
						var remarks = '';
					else remarks = value['REMARKS'];

					let tr="<tr>";
					tr+= "<td>"+i+"</td>";
					tr+= "<td>"+value['PROG_ID']+"</td>";
					tr+= "<td>"+value['SHIFT_ID']+"</td>";
					tr+= "<td>"+value['PROGRAM_TITLE']+"</td>";
					tr+= "<td>"+value['DEGREE_TITLE']+"</td>";
					tr+= "<td>"+value['SHIFT_NAME']+"</td>";
					tr+= "<td>"+remarks+"</td>";
					// tr+= "<td>-</td>";
					tr+= "<td><a href='javascript:void(0)' onclick=DeleteProgram("+value['PROG_ID']+","+value['SHIFT_ID']+");>Delete</a></td>";
					tr+="</tr>";
					$("#table_data").append(tr);
				});
			}
		});
	}
	*/
	
	$(document).ready(function () {
		$("#shift").change(function () {
			ignoreMappedPrograms ();
		});
		loadMappedPrograms ();
	});
</script>