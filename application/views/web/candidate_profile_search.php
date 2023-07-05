<div style="height:100px"></div>
<div class="container-fluid">
	<div class=" text-center">
		<h2 class="title">Candidate Profile</h2>
		<p class="text-center text-danger" style="font-weight: bold; font-size: 18pt"></p>
	</div>

	<div class="row">
	    <div class="col-md-4 col-sm-12 col-xs-12">
	        
	        <!--<iframe width="560" height="315" src="https://www.youtube.com/embed/37i0yPfEHHo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>-->
	        
	   </div>
		<div class="col-md-4 col-sm-12 col-xs-12 offset-1">
			<div class="form-group has-default">
				<label>Enter your CNIC # <span class='text-rose'>without " - " hyphen</span></label>
				<input type="text" min="0" class="form-control" id="cnic_no" placeholder="ENTER YOUR CNIC NO WITHOUT - HYPHEN">
			</div>
			<button class="btn btn-info" id="search" onclick="getStatus()"><i class="fa fa-search"></i> Search</button>
			<img src="<?=base_url().'/assets/img/ajax-loader.gif'?>" id="loader" style="display: none">
		</div>
		
			<div class="col-md-3 col-sm-12 col-xs-12">
		    <h4 style='font-weight:bold; font-size:14pt' class='text-success'>CPN Calculation Formula</h4>
		    <p style='font-weight:bold; font-size:14pt'>CPN = SSC (Matric) 10% + HSC (Inter) 30% + PET SCORE 60%</p>
		    <h4 style='font-weight:bold; font-size:14pt' class='text-success'>CPN Calculation Formula For LAT</h4>
		<p style='font-weight:bold; font-size:14pt'>CPN = SSC 10% + HSC 30% + LAT SCORE 60%</p>
        
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
<!--					<p class="text-success"><a href="">Watch tutorial how to fill objection form</a></p>-->
				</div>

			</div>
		</div>
	</div>
</div>
<script>
    var t = <?=isset($_GET['t'])?$_GET['t']:1;?>;
	function getStatus (){

		let cnic_no = $("#cnic_no").val();
		let program_type = t;
		let is_ob = 'Y';

// 		let program_type = $("#program_type").val();
		// alert(shift_id);
		if (cnic_no === "" || cnic_no === 0 || cnic_no == null || isNaN(cnic_no))
			return;
		// campus = 0;
		$("#loader").show();
		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>web/get_candidate_profile',
			method: 'POST',
			data: {cnic_no:cnic_no,program_type:program_type,is_ob:is_ob},
			// dataType: 'json',
			success: function(response){
				$("#display").html(response);
				$("#loader").hide();
		}
	});
	}
</script>
