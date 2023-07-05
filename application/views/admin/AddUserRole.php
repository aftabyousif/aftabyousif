<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Admin User Account Management</h1>

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

		<div class="row">
			<div class="col-md-2">
				<label>Enter User's CNIC No</label>
				<input type="text" id="cnic_no" name="cnic_no" class="form-control"/>
			</div>
		</div>

		<span id="loading"></span>
	<br>
	<div class="button-style-two btn-mg-b-10">
		<button type="button" id="findUserAccount" class="btn btn-custon-rounded-two btn-primary"><i class="fa fa-search edu-informatio" aria-hidden="true"></i> Find User's Account</button>
		<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
	</div>

		<div id="displayUserData"></div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function (){

		$("#findUserAccount").click(function (){
			getUserData();
		});

	});

	function getUserData (){
		let cnic_no = $.trim($("#cnic_no").val());
		$('#loading').html('');
		if (cnic_no == 0 || cnic_no == "" || cnic_no == null)
		{
			return;
		}
		$.ajax({
			url:'<?=base_url()?>AdminAccount/getUserData',
			method: 'POST',
			data: {cnic_no:cnic_no},
			// dataType: 'json',
			success: function(response){
				// console.log(response);
				$('#loading').html('');
				$('#displayUserData').html(response);
			}
		});
	}
</script>
