<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>
<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
<!--		<form class="container-fluid">-->
			<div class="sparkline10-hd">
				<div class="main-sparkline10-hd text-center bg-warning">
					<h1>Download / Print Selection Reports</h1>
				</div>
			</div>

			<?php
			if($this->session->flashdata('message')) {
				echo '
                    <div class="alert alert-warning">
                        '.$this->session->flashdata("message").'
                    </div>
                    ';
			}
			
			$attribute = array ('target'=>'new')
			?>
<!--			--><?//=form_open(base_url().'Selection_list_report/display_select_list_for_verification_pdf',$attribute)?>
			<?=form_open(base_url().'Selection_list_report/display_select_list_for_verification_pdf',$attribute)?>
			<div class='row'>
			    <div class='col-md-6'>
			        <div class="col-md-4">
					<label>Program Type</label>
					<select name="program_type" id="program_type" onchange="loadCampus ();" class="form-control">
						<option value="0"></option>
						<?php
						foreach ($program_types as $program_type)
						{
						  //  if($program_type['PROGRAM_TYPE_ID'] == 2) continue;
						   
							?>
							<option value=<?=$program_type['PROGRAM_TYPE_ID']?>><?=$program_type['PROGRAM_TITLE']?></option>";
							<?php
						}
						unset($program_types);
						unset($program_type);
						?>
					</select>
				</div>
				
					<div class="col-md-4">
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
				
				<div class="col-md-4">
					<label>Test</label>
					<select name="test_id" id="test_id" class="form-control">
						<option value="0"></option>
					</select>
				</div>
				
				<div class="col-md-6">
					<label>Campus</label>
					<select name="campus" id="campus" class="form-control">
						<option value="0"></option>
					</select>
				</div>
				<div class="col-md-6">
                        <label>IS OBJECTION  *</label>

                        <select class="form-control" name="is_provisional" id="is_provisional">
                            <option value="0">--Choose--</option>
                            <option value="Y">YES</option>
                            <option value="N">NO</option>

                        </select>
                    </div>
					<div class="col-md-6">
					<label>Shifts</label>
					<select name="shift_id" id="shift_id" class="form-control">
						<option value="0"></option>
						<?php
						foreach ($shifts as $shift) {
							?>
							<option value=<?=$shift['SHIFT_ID']?>><?=$shift['SHIFT_NAME']?></option>";
							<?php
						}
						unset($shift);
						unset($shifts);
						?>
					</select>
				</div>
				
				<div class="col-md-6">
					<label>List No</label>
					<select name="list_no" id="list_no" class="form-control">
						<option value="0"></option>
					</select>
				</div>
				
				
				<div class="col-md-12">
					<label>Message</label>
				    <textarea name='message' class='form-control'></textarea>
				</div>
				
			    </div>
			    
			    <div class='col-md-6'>
			        <label>Program List</label>
					<select name="PROG_LIST_ID[]" id="PROG_LIST_ID" class="form-control" style='height:250px' multiple>
						<option value="0"></option>
					</select>
			        
			    </div>
			</div>
			<br>
			
			<div class="button-style-two btn-mg-b-10">
				<button type="submit" class="btn btn-custon-rounded-two btn-primary"><i class="fa fa-print edu-informatio" aria-hidden="true"></i> Print / Download Report</button>
				<button type="button" class="btn btn-custon-rounded-two btn-success" onclick="DownloadExcel()"><i class="fa fa-print edu-informatio" aria-hidden="true"></i> Total Selection Discipline Wise</button>
				<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
			<span id="loading"></span>
			</div>

	</div>
</div>

<?php $CI =& get_instance(); ?>
<script>
	var csrf_name = '<?php echo $CI->security->get_csrf_token_name(); ?>';
	var csrf_hash = '<?php echo $CI->security->get_csrf_hash(); ?>';
</script>

<script type="text/javascript">

    $('#session').change(function (){
        let session_id = $('#session').val();
        
        if(session_id>0){
            jQuery.ajax({
                url: "<?=base_url()?>AdminPanel/getTestType?SESSION_ID="+session_id,
                async:true,
                success: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("");

                    $('#test_id').html(data);


                },
                beforeSend:function (data, status) {


                    $('#alert_msg_for_ajax_call').html("LOADING...!");
                },
                error:function (data, status) {
                    alertMsg("Error",data.responseText);
                    $('#alert_msg_for_ajax_call').html("Something went worng..!");
                },
            });

        }else{
            $('#test_id').html(" <option value='0'>--Choose--</option>");
        }
    });
    
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
					option+= "<option value='"+value['ADMISSION_SESSION_ID']+"'>"+value['NAME']+"</option>";
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
	
	function loadAdmissionList (){

		let admission_session_id = $("#campus").val();
		let shift_id = $("#shift_id").val();

		$("#list_no").html('');
		$('#loading').html('');
		
		if ((admission_session_id === "" || admission_session_id === 0 || admission_session_id == null || isNaN(admission_session_id) || shift_id === "") || shift_id === 0 || shift_id == null || isNaN(shift_id)){
			return;
		}
		
		$.ajax({
			url:'<?=base_url()?>Selection_list_report/getAdmissionList',
			method: 'POST',
			data: {admission_session_id:admission_session_id,shift_id:shift_id,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				$('#loading').html('');
				let i=0;
				let option="";
				option+= "<option value=''></option>";
				$("#list_no").append(option);

				$.each(response, function (index,value) {
	               // console.log(value);
					let option="";
					option+= "<option value='"+value['LIST_NO']+"'>"+value['LIST_NO']+"</option>";
					$("#list_no").append(option);
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
	
	function loadMappedPrograms (){
        
		let shift_id = $("#shift_id").val();
		let program_type = $("#program_type").val();
		let CAMPUS_ID = $("#campus").val();
	
		if (shift_id == "" || shift_id == 0 || shift_id == null || isNaN(shift_id))
			return;
		if (program_type === "" || program_type === 0 || program_type == null || isNaN(program_type))
			return;
		if (CAMPUS_ID === "" || CAMPUS_ID === 0 || CAMPUS_ID == null || isNaN(CAMPUS_ID))
			return;

        $("#PROG_LIST_ID").html('');
	        jQuery.ajax({
                    url:'<?=base_url()?>mapping/getMappedPrograms',
                    method: 'POST',
                    data: {shift_id:shift_id,program_type:program_type,admission_session_id:CAMPUS_ID},
                    dataType: 'json',
                    success: function (response) {
                        let i=0;
                        $.each(response, function (index,value) {
                            // i++;
                            // if (value['REMARKS'] == null)
                            // 	var remarks = '';
                            let option="";
                            option+= "<option value='"+value['PROG_ID']+"'>"+value['PROGRAM_TITLE']+"</option>";
                            $("#PROG_LIST_ID").append(option);
                        });
                    },
                    beforeSend:function (data, status) {
                        $('#alert_msg_for_ajax_call').html("LOADING...!");
                    },
                    error:function (data, status) {
                        alertMsg("Error",data.responseText);
                        $('#alert_msg_for_ajax_call').html("Something went worng..!");
                        $("#PROG_LIST_ID").html("");
                    },
                });
	}

	function DownloadExcel (){

		let shift_id = $("#shift_id").val();
		let program_type = $("#program_type").val();
		let CAMPUS_ID = $("#campus").val();
		let session = $("#session").val();
		let test_id = $("#test_id").val();
		let is_provisional = $("#is_provisional").val();
		let list_no = $("#list_no").val();

		if (shift_id == "" || shift_id == 0 || shift_id == null || isNaN(shift_id))
			return;
		if (program_type === "" || program_type === 0 || program_type == null || isNaN(program_type))
			return;
		if (CAMPUS_ID === "" || CAMPUS_ID === 0 || CAMPUS_ID == null || isNaN(CAMPUS_ID))
			return;
		if (session === "" || session === 0 || session == null || isNaN(session))
			return;
		if (test_id === "" || test_id === 0 || test_id == null || isNaN(test_id))
			return;
		if (is_provisional === "" || is_provisional == null )
			return;
		if (list_no === "" || list_no === 0 || list_no == null || isNaN(list_no))
			return;

		$("#PROG_LIST_ID").html('');
		jQuery.ajax({
			url:'<?=base_url()?>Selection_list_report/total_selection_discipline_wise_handler',
			method: 'POST',
			data: {shift_id:shift_id,program_type:program_type,admission_session_id:CAMPUS_ID,session:session,test_id:test_id,is_provisional:is_provisional,list_no:list_no},
			dataType: 'json',
			success: function (response) {

				var  heading = response.HEADING;
				var  data = response.DATA;
				// console.log(data)
				var csv_out="";
				var csv_heading = "";
				$.each(heading, function (index,value) {
					csv_heading+=value+",";
				});
				csv_heading+="\n";
				csv_out+=csv_heading

				$.each(data, function (index,value) {
					var program_title = value['PROGRAM_TITLE'];
						program_title= program_title.replace(',',' ')
					csv_out+=program_title+",";
					// csv_out+=value['CATEGORY_NAME']+",";
					csv_out+=value['COMMERCE_QUOTA']+",";
					csv_out+=value['DISABLE_PERSONS_QUOTA']+",";
					csv_out+=value['SUE_AFFILIATED_COLLEGE_SD_QUOTA']+",";
					csv_out+=value['QUOTA_GENERAL_MERIT_OUT_OF_JURISDICTION']+",";
					csv_out+=value['QUOTA_GENERAL_MERIT_JURISDICTION']+",";
					csv_out+=value['FEMALE_QUOTA_JURISDICTION']+",";
					csv_out+=value['FEMALE_QUOTA_OUT_OF_JURISDICTION']+",";
					csv_out+=value['KARACHI_RESERVED_QUOTA']+",";
					csv_out+=value['OTHER_PROVINCES_SELF_FINANCE']+",";
					csv_out+=value['SELF_FINANCE']+",";
					csv_out+=value['SUE_SON_DAUGHTER_QUOTA']+",";
					csv_out+=value['TOTAL']+",";
					csv_out+=value['MALE']+",";
					csv_out+=value['FEMALE']+",";
					csv_out+="\n";
					// console.log(value['PROGRAM_TITLE']);
				});
				var a = $('<a/>', {
					style:'display:none',
					href:'data:application/octet-stream;base64,'+btoa(csv_out),
					download:'total_selection_dsicipline_wise.csv'
				}).appendTo('body')
				a[0].click()
				a.remove();
			},
			beforeSend:function (data, status) {
				$('#alert_msg_for_ajax_call').html("LOADING...!");
			},
			error:function (data, status) {
				alertMsg("Error",data.responseText);
				$('#alert_msg_for_ajax_call').html("Something went worng..!");
				$("#PROG_LIST_ID").html("");
			},
		});
	}

	$(document).ready(function () {
		$("#shift_id").change(function () {
		    loadAdmissionList();
		});
		
		$("#shift_id,#campus").change(function () {
		   loadMappedPrograms();
		});
		
		
// 		loadMappedPrograms ();
	});
</script>
