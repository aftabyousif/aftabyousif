<div style="height:100px"></div>
<div class="container-fluid">
		<div class=" text-center">
			<h2 class="title">First Provisional Merit/Selection List - Bachelor Degree Programs 2022</h2>
			
		<p class="text-center text-danger" style="font-weight: bold; font-size: 18pt"><u>Important Dates : </u></p>
		
		<p class="" style="font-weight: bold; font-size: 18pt">	Last date for submission of objection (if any) at Directorate of Admissions, University of Sindh or through Email (admission@usindh.edu.pk) upto<br><span class="text-danger">24-01-2022 05:00 PM</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">Admission Fees challans for selected candidates will be available on their E-portal accounts on <span class="text-danger"> Tuesday 25-01-2022</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">	Last Date for payment of Admission Fees: <span class="text-danger">Wednesday 02-02-2022.</span>.</p>
    
    	<p class=" " style="font-weight: bold; font-size: 18pt">Last Date for submission of all required documents at Shaheed Benazir Bhutto Convention Center, University of Sindh Jamshoro: <span class="text-danger"> Friday 04-02-2022</span>.</p>
    	<p class=" " style="font-weight: bold; font-size: 18pt">Candidates selected in constituent campuses of University of Sindh will submit their all required documents at their concerned campuses.</p>
	    
	    <!--<p class="text-center text-primary" style="font-weight: bold; font-size: 18pt">LL.M (Law): Last Date for payment of Admission Fees and submission of all required documents at Directorate of Admissions is Friday 30-04-2022.</p>-->
	    <!--<p class="text-center text-info" style="font-weight: bold; font-size: 18pt">For Sindh University Other Campuses: Last Date for payment of Admission Fees and submission of all required documents at respective campuses is Friday 29-01-2022.</p>-->
	<br/>
	<p class="text-center text-success" style="font-weight: bold; font-size: 14pt">
	    Candidates selected in any Provisional Merit / Selection list for Bachelor Degree Programs for the academic year 2022 can download their admission fees challans from their E-Portal accounts.
	 </p>

	</div>

	<div class="row">
	    <div class="col-md-4 col-sm-12 col-xs-12">
	        
	        <iframe width="560" height="315" src="https://www.youtube.com/embed/37i0yPfEHHo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	        
	   </div>
		<div class="col-md-4 col-sm-12 col-xs-12 offset-1">
			<div class="form-group has-default">
				<label>Enter your CNIC # <span class='text-rose'>without " - " hyphen</span></label>
				<input type="text" min="0" class="form-control" id="cnic_no" placeholder="CNIC NO">
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
	function getStatus (){

		let cnic_no = $("#cnic_no").val();
		let program_type = 1;
		let is_ob = 'Y';

// 		let program_type = $("#program_type").val();
		// alert(shift_id);
		if (cnic_no === "" || cnic_no === 0 || cnic_no == null || isNaN(cnic_no))
			return;
		// campus = 0;
		$("#loader").show();
		$("#table_data").empty();
		$.ajax({
			url:'<?=base_url()?>web/get_candidate_objection_list',
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
