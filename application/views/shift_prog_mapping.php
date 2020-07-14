<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15">
	<div class="sparkline10-list">
	<div class="container-fluid">
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Shift Program Mapping</h1>
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
		<?=form_open('mapping/save_shift_program_mapping')?>
<!--		<form id="form" action="save_shift_program_mapping" method="post" class="wizard-big">-->
		<div class="row">
			<div class="col-md-6">
				<label>Shift</label>
				<select name="shift" id="shift" onchange="loadMappedPrograms()" class="form-control">
					<option value=""></option>
					<?php
					foreach ($shifts as $shift_key=>$shift_value)
					{
						?>
						<option value=<?=$shift_value['SHIFT_ID']?>><?=$shift_value['SHIFT_NAME']?></option>";
						<?php
					}
					unset($shifts);
					unset($shift_key);
					unset($shift_value);
					?>
				</select>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

					<div class="sparkline10-hd">
						<div class="main-sparkline10-hd">
						<label>Programs List</label>
						</div>
					</div>
					<div class="sparkline10-graph">
						<div class="basic-login-form-ad">
							<div class="row">

								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<div class="dual-list-box-inner">

											<select class="form-control" name="selected_programs[]" id="selected_programs" style="height: 200px" multiple="multiple">
												<?php
//												foreach ($programs as $key=>$value)
//												{
													?>
<!--													<option value=--><?//=$value['PROG_LIST_ID']?><!-->--><?//=$value['PROGRAM_TITLE']?><!--</option>";-->
												<?php
//												}
//												unset($programs);
//												unset($key);
//												unset($value);
												?>
											</select>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<button type="submit" name="save" class="btn btn-primary">Save</button>
		</form>
			<br/>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12"
				 <div class="table-responsive">
					 <table class="table">
						 <thead>
						 <th>S.NO</th>
						 <th>PROG ID</th>
						 <th>SHIFT ID</th>
						 <th>PROGRAM TITLE</th>
						 <th>SHIFT</th>
						 <th>REMARKS</th>
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

	function DeleteProgram (prog_id,shift_id){

		if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id) || prog_id === "" || prog_id === 0 || prog_id == null || isNaN(prog_id))
			return;

		if (confirm("Do you want to delete?") === false)
			return;
		// $("#selected_programs").empty();

		$.ajax({
			url:'<?=base_url()?>mapping/DeleteMappedPrograms',
			method: 'POST',
			data: {shift_id:shift_id,prog_id:prog_id,csrf_name:csrf_hash},
			dataType: 'json',
			// success: function(response){
			// 	console.log(response);
			// }
			success: function (data, status) {
				// console.log(status);
				alert_msg("<div class='text-danger'>" + data+ "</div>");
				$('#msg').hide();
				loadMappedPrograms ();
			},
			beforeSend:function (data, status) {
				alert_msg("<div class='text-warning text-center'>Processing.... Please wait</div>");
			},
			error:function (data, status) {
				alert_msg("<div class='text-danger'>" + data.responseText + "</div>");
				// $('#msg').html("<div class='text-danger'>" + data.responseText + "</div>");
				$('#msg').hide();
			},
		});
	}

	function ignoreMappedPrograms (){

		let shift_id = $("#shift").val();
		// alert(shift_id);
		$("#selected_programs").html('')
		if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
			return;
		// $("#selected_programs").empty();

		$.ajax({
			url:'<?=base_url()?>mapping/ignoreMappedPrograms',
			method: 'POST',
			data: {shift_id:shift_id,csrf_name:csrf_hash},
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
					$("#selected_programs").append(option);
				});
			}
		});
	}
	function loadMappedPrograms (){

		let shift_id = $("#shift").val();
		// alert(shift_id);
			if (shift_id === "" || shift_id === 0 || shift_id == null || isNaN(shift_id))
				shift_id = 0;
		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>mapping/getMappedPrograms',
			method: 'POST',
			data: {shift_id:shift_id,csrf_name:csrf_hash},
			dataType: 'json',
			success: function(response){
				// console.log(response);
				let i=0;
				$.each(response, function (index,value) {
					i++;
				if (value['REMARKS'] == null)
					var remarks = '';

					let tr="<tr>";
					tr+= "<td>"+i+"</td>";
					tr+= "<td>"+value['PROG_ID']+"</td>";
					tr+= "<td>"+value['SHIFT_ID']+"</td>";
					tr+= "<td>"+value['PROGRAM_TITLE']+"</td>";
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
	$(document).ready(function () {
		$("#shift").change(function () {
			ignoreMappedPrograms ();
		});
		loadMappedPrograms ();
	});
</script>
