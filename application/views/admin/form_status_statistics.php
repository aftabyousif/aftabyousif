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
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Generate Admit Cards & Statistics</h1>
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
	    <form action="" method="post">
    		<div class="row">
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
    				<select name="campus[]" id="campus" onchange="view_report()"  class="form-control" multiple="multiple">
    <!--					<option value="0"></option>-->
    				</select>
    			</div>
    
    		
    				<span id="loading"></span>
    		</div>
    		
    		<br>
    		<div class="button-style-two btn-mg-b-10">
    		
    			<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
    		</div>
		</form>


<div class="single-pro-review-area mt-t-20 mg-b-10">
	<div class="container-fluid">

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h4 class="text-center">Application Statistics</h4>
					<?php
					$table_column = array("S.NO","CAMPUS","DEGREE","SESSION","TOTAL NO OF FORM","TOTAL NO OF CHALLAN UPLOAD","CHALLAN NOT UPLOAD AND NOT VERIFIED","CHALLAN UPLOAD BUT NOT VERIFIED","CHALLAN UPLOAD AND VERIFIED","CHALLAN VERIFIED BUT NOT UPLOAD","TOTAL NO OF CHALLAN VERIFIED");
					?>
				<div class="table-responsive">
					<table class="table table-condesed">
					<thead>
					<tr>
					<?php
					$i=0;
					foreach($table_column as $col){
					?>
					<th style="font-size: 8pt" ><?=$col?></th>
					<?php
					$i++;
					}
					?>
					</tr>
					</thead>
					<tbody id="table_data">
					    
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
    	<div class="row">
			<div class="col-md-6">
				<div id="piechart_draft" style="width: 900px; height: 500px;"></div>
			</div>
			<div class="col-md-6">
				<div id="piechart" style="width: 900px; height: 500px;"></div>
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
    var stat = <?=$statistics?>;
    function loadCampus (){

	 $('#table_data').html("");
		let program_type = $("#program_type").val();
		let session = $("#session").val();

		$("#campus").empty();
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
				//	get_statistics();

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
	function view_report(){
	    $('#table_data').html("");
	    let program_type = $("#program_type").val();
	    let program_txt = $("#program_type option:selected").text();
		let session = $("#session").val();
		 let session_txt = $("#session option:selected").text();
		let campus = $("#campus").val();
		    var sr = 1;
		    var g_total_no=0;
		    var g_total_no_upload=0;
		    var g_total_no_verified=0;
		    var g_total_no_verified_with_out_upload =0;
		    var g_not_upload_not_verified = 0;
		    var g_total_no_upload_and_verified = 0;
		    var g_challan_upload_but_not_verified=0;
			campus.forEach(function(value){
			    var total_no = 0;
			    var total_no_upload = 0;
			    var total_no_verified = 0;
			    var total_no_verified_with_out_upload=0;
			    var not_upload_not_verified = 0;
			    var total_no_upload_and_verified=0;
			    var challan_upload_but_not_verified=0;
			    var g_total_upload_not_verified=0;
			    var campus_name = $('#campus option[value='+value+']').text();
			    stat[value].forEach(function(value1){
			     //   console.log(value1);
			     //   console.log(value1.TOTAL_UPLOAD);
			     //   console.log(value1.IS_VERIFIED);
			     //   console.log(value1.TOTAL_VERIFIED);
			     total_no += Number(value1.TOTAL_UPLOAD);
			        if(value1.IS_UPLOAD == 0){
			            total_no_upload += Number(value1.TOTAL_UPLOAD);
			        }
			        if(value1.IS_VERIFIED=='Y'){
			            
			       //  total_no_upload += Number(value1.TOTAL_UPLOAD);
			         total_no_verified += Number(value1.TOTAL_UPLOAD);
			        }
			        if(value1.IS_VERIFIED=='Y'&&value1.IS_UPLOAD == 1){
			            
			       //  total_no_upload += Number(value1.TOTAL_UPLOAD);
			         total_no_verified_with_out_upload += Number(value1.TOTAL_UPLOAD);
			        }
			        if(value1.IS_VERIFIED!='Y'&&value1.IS_UPLOAD == 0){
			            
			       //  total_no_upload += Number(value1.TOTAL_UPLOAD);
			         challan_upload_but_not_verified += Number(value1.TOTAL_UPLOAD);
			        }
			        if(value1.IS_VERIFIED=='Y'&&value1.IS_UPLOAD == 0){
			            
			       //  total_no_upload += Number(value1.TOTAL_UPLOAD);
			         total_no_upload_and_verified += Number(value1.TOTAL_UPLOAD);
			        }
			        
			        if(value1.IS_VERIFIED!='Y' &&value1.IS_UPLOAD == 1){
			            
			       //  total_no_upload += Number(value1.TOTAL_UPLOAD);
			         not_upload_not_verified += Number(value1.TOTAL_UPLOAD);
			        }
			        
			    });
			    g_total_no+=total_no;
			    g_total_no_upload+=total_no_upload;
			    g_total_no_verified+=total_no_verified;
			     g_total_no_verified_with_out_upload+=total_no_verified_with_out_upload;
			     g_not_upload_not_verified+=not_upload_not_verified;
			     g_total_no_upload_and_verified+=total_no_upload_and_verified;
			     g_challan_upload_but_not_verified +=challan_upload_but_not_verified;
			    var row = "<tr><td>"+sr+"</td><td>"+campus_name+"</td><td>"+program_txt+"</td><td>"+session_txt+"</td><td>"+total_no+"</td><td>"+total_no_upload+"</td><td>"+not_upload_not_verified+"</td><td>"+challan_upload_but_not_verified+"</td><td>"+total_no_upload_and_verified+"</td><td>"+total_no_verified_with_out_upload+"</td><td>"+total_no_verified+"</td></tr>";
			   sr++;
			    $('#table_data').append(row);
			    
			});
			var row = "<tr><td>"+sr+"</td><td></td><td></td><td></td><td>"+g_total_no+"</td><td>"+g_total_no_upload+"</td><td>"+g_not_upload_not_verified+"</td><td>"+g_challan_upload_but_not_verified+"</td><td>"+g_total_no_upload_and_verified+"</td><td>"+g_total_no_verified_with_out_upload+"</td><td>"+g_total_no_verified+"</td></tr>";
			 
			$('#table_data').append(row);
		
	}
</script>
