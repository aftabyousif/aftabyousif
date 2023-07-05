<script>
	$( function() {
		$( "#datepicker" ).datepicker({
			showOtherMonths: true,
			selectOtherMonths: true
		});
	} );
</script>

<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline13-hd">
			<div class="main-sparkline13-hd">
				<h1>Application <span class="table-project-n">Form</span> Statistics</h1>
			</div>
		</div>

		<div class="row" style="margin-top: 30px;">
			<div class="col-md-2">
				<label>Session
					<span class='text-danger'> *</span>
				</label>
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

			<div class="col-md-2">
				<label>Program Type
					<span class='text-danger'> *</span>
				</label>
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
			<!--			onchange="loadMappedPrograms()"-->
			<div class="col-md-5">
				<label>Campus
					<span class='text-danger'> *</span>
				</label>
				<select name="campus[]" id="campus" onchange="get_statistics ();"  class="form-control" multiple="multiple">
					<!--					<option value="0"></option>-->
				</select>
			</div>

			<div class="col-md-2">
				<label>Gender
					<span class='text-danger'> *</span>
				</label>
				<select  name="gender[]" id="gender" class="form-control" multiple="multiple" onchange="get_statistics ();">
					<option value=""></option>
					<option value="'M'">Male</option>
					<option value="'F'">Female</option>
				</select>
			</div>
			<p id="loading"></p>
		</div>
		<div class="data-table-area mg-b-15">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="sparkline13-list">
							<div class="sparkline13-graph">
								<div class="datatable-dashv1-list custom-datatable-overright">
<!--									<div id="toolbar">-->
<!--										<select class="form-control dt-tb">-->
<!--											<option value="">Export Basic</option>-->
<!--											<option value="all">Export All</option>-->
<!--											<option value="selected">Export Selected</option>-->
<!--										</select>-->
<!--									</div>-->
									<table id="table" data-toggle="table" data-pagination="false" data-search="false" data-show-columns="true" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
										   data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
										<thead style="font-size: 8pt">
										<?php
											$table_column = array("S.NO","CAMPUS","DEGREE","GENDER","TOTAL","CHALLAN","CHALLAN VERIFIED","DRAFT","SUBMITTED","IN REVIEW","IN PROCESS","FORM VERIFIED","FORM REJECTED","ADMIT CARDS","NOT DISPATCHED","DISPATCHED");
										?>
<!--										<th data-field="state" data-checkbox="true"></th>-->
									<?php
											$i=0;
											foreach($table_column as $col){
												?>
												<th data-field="<?=$col?>"><?=$col?></th>
<!--												<th style="font-size: 8pt" >--><?//=$col?><!--</th>-->
												<?php
												$i++;
												}
											?>
										</thead>
										<tbody id="table_data"  style="font-size: 9pt">
									</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<!--		<div class="single-pro-review-area mt-t-20 mg-b-10" style="margin-top: 15px">-->
<!--			<div class="container-fluid">-->
<!---->
<!--				<div class="row">-->
<!--					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">-->
<!--						<h4 class="text-center">Application Statistics</h4>-->
<!--						--><?php
//						$table_column = array("S.NO","CAMPUS","DEGREE","GENDER","TOTAL","CHALLAN","CHALLAN VERIFIED","DRAFT","SUBMITTED","IN REVIEW","IN PROCESS","FORM VERIFIED","FORM REJECTED","ADMIT CARDS","NOT DISPATCHED","DISPATCHED");
//						?>
<!--						<div class="table-responsive">-->
<!--							<table class="table table-condesed">-->
<!--								<thead>-->
<!--								<tr>-->
<!--									--><?php
//									$i=0;
//									foreach($table_column as $col){
//										?>
<!--										<th style="font-size: 8pt" >--><?//=$col?><!--</th>-->
<!--										--><?php
//										$i++;
//									}
//									?>
<!--								</tr>-->
<!--								</thead>-->
<!--								<tbody id="table_data">-->
<!--								</tbody>-->
<!--							</table>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->

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

		$("#campus").empty();
		$('#loading').html('');
		if (program_type == "" || session == "")
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
				get_statistics();

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
	function get_statistics (){

		let session 	 = $("#session").val();
		let program_type = $("#program_type").val();
		let campus = $("#campus").val();
		let gender = $("select[name='gender[]']").val();


		if (session === "" || session === 0 || session == null || isNaN(session))
			session = 0;
		if (program_type === "" || program_type === 0 || program_type == null || isNaN(program_type))
			program_type = 0;
		if (campus === "" || campus === 0 || campus == null || isNaN(campus))
			campus = 0;
		if (gender === "" || gender === 0 || gender == null)
			gender = 0;

		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>AdmitCard/getStatistics_gender_wise',
			method: 'POST',
			data: {session:session,program_type:program_type,campus:campus,gender:gender,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				console.log(response);
				let i=0;
				let total_draft 	= 0;
				let total_submitted = 0;
				let total_in_review = 0;
				let total_in_process= 0;
				let total_verified 	= 0;
				let total_rejected 	= 0;
				let total_admit_cards 	= 0;
				let total_not_dispatched= 0;
				let total_dispatched 	= 0;
				let total_applications_sum 	= 0;
				let sum_challan_verified	= 0;
				let sum_challan_uploaded	= 0;

				// let submitted_pie_data = {};
				// let draft_pie_data = {};

				$.each(response, function (index,value) {
					i++;
					let CHALLAN_VERIFIED = value['CHALLAN_VERIFIED'];
					let UPLOADED_CHALLAN = value['UPLOADED_CHALLAN'];
					let TOTAL_APPLICATIONS = value['TOTAL_APPLICATIONS'];
					let DRAFT = value['DRAFT'];
					let SUBMITTED = value['SUBMITTED'];
					let IN_REVIEW = value['IN_REVIEW'];
					let IN_PROCESS = value['IN_PROCESS'];
					let FORM_VERIFIED = value['FORM_VERIFIED'];
					let FORM_REJECTED = value['FORM_REJECTED'];
					let TOTAL_ADMIT_CARDS = value['TOTAL_ADMIT_CARDS'];
					let NOT_DISPATCHED = value['NOT_DISPATCHED'];
					let DISPATCHED = value['DISPATCHED'];
					let NAME = value['NAME'];
					let SESSION_REMARKS = value['REMARKS'];
					let GENDER = value['GENDER'];
					if (GENDER === "M") GENDER = "MALE";
					else if (GENDER === "F") GENDER = "FEMALE";
					// else GENDER = GENDER
					let tr="<tr style='font-size: 9pt'>";
					// tr+= "<td></td>";
					tr+= "<td>"+i+"</td>";
					tr+= "<td>"+value['NAME']+"</td>";
					tr+= "<td>"+value['PROGRAM_TITLE']+"</td>";
					// tr+= "<td>"+value['REMARKS']+"</td>";
					tr+= "<td>"+GENDER+"</td>";
					tr+= "<td>"+TOTAL_APPLICATIONS+"</td>";
					tr+= "<td>"+UPLOADED_CHALLAN+"</td>";
					tr+= "<td>"+CHALLAN_VERIFIED+"</td>";
					tr+= "<td>"+DRAFT+"</td>";
					tr+= "<td>"+SUBMITTED+"</td>";
					tr+= "<td>"+IN_REVIEW+"</td>";
					tr+= "<td>"+IN_PROCESS+"</td>";
					tr+= "<td>"+FORM_VERIFIED+"</td>";
					tr+= "<td>"+FORM_REJECTED+"</td>";
					tr+= "<td>"+TOTAL_ADMIT_CARDS+"</td>";
					tr+= "<td>"+NOT_DISPATCHED+"</td>";
					tr+= "<td>"+DISPATCHED+"</td>";
					// tr+= "<td>"+remarks+"</td>";
					// tr+= "<td>-</td>";
					// tr+= "<td><a href='javascript:void(0)' onclick=DeleteProgram("+value['PROG_ID']+","+value['SHIFT_ID']+");>Delete</a></td>";

					tr+="</tr>";
					$("#table_data").append(tr);

					total_draft 	+=parseInt(DRAFT);
					total_submitted += parseInt(SUBMITTED);
					total_in_review +=parseInt(IN_REVIEW);
					total_in_process+= parseInt(IN_PROCESS);
					total_verified 	+=parseInt(FORM_VERIFIED);
					total_rejected 	+=parseInt(FORM_REJECTED);
					total_admit_cards 	+=parseInt(TOTAL_ADMIT_CARDS);
					total_not_dispatched+=parseInt(NOT_DISPATCHED);
					total_dispatched 	+=parseInt(DISPATCHED);
					total_applications_sum 	+=parseInt(TOTAL_APPLICATIONS);
					sum_challan_verified 	+=parseInt(CHALLAN_VERIFIED);
					sum_challan_uploaded +=parseInt(UPLOADED_CHALLAN);

					// submitted_pie_data[""+NAME+""] = parseInt(SUBMITTED);
					// draft_pie_data[""+NAME+""] = parseInt(DRAFT);
				});

				let tr="<tr style='font-size: 9pt; font-weight: bold' class='bg-warning' >";
				tr+= "<td colspan='4' class='text-right'>TOTALS</td>";
				tr+= "<td>"+total_applications_sum+"</td>";
				tr+= "<td>"+sum_challan_uploaded+"</td>";
				tr+= "<td>"+sum_challan_verified+"</td>";
				tr+= "<td>"+total_draft+"</td>";
				tr+= "<td>"+total_submitted+"</td>";
				tr+= "<td>"+total_in_review+"</td>";
				tr+= "<td>"+total_in_process+"</td>";
				tr+= "<td>"+total_verified+"</td>";
				tr+= "<td>"+total_rejected+"</td>";
				tr+= "<td>"+total_admit_cards+"</td>";
				tr+= "<td>"+total_not_dispatched+"</td>";
				tr+= "<td>"+total_dispatched+"</td>";
				tr+="</tr>";
				$("#table_data").append(tr);
				// $("#table").DataTable();
				// localStorage.setItem('submitted_pie_campus',JSON.stringify(submitted_pie_data));
				// localStorage.setItem('draft_pie_campus',JSON.stringify(draft_pie_data));
				// loadStatistics ();
			}
		});
	}

	// function loadStatistics () {
	// 	google.charts.load('current', {'packages':['corechart']});
	// 	google.charts.setOnLoadCallback(drawChart);
	// }

	// $(document).ready(function () {
	// 	get_statistics();
	// 	// loadStatistics ();
	// });
</script>
