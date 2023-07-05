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

	
	</div>

	<div class="row">
	    
	    <div class="col-md-4 col-sm-12 col-xs-12">
	    <table class='table'>
		        <tr>
		            <th>For Admission</th>
		            <th>Master</th>
		            <th>LL.M Degree Programme </th>
		            
		        </tr>
		        <tr>
		            <td>S.S.C</td>
		            <td>20%</td>
		            <td>05%</td>
		        </tr>
		        <tr>
		            <td>H.S.C. (adjusted marks)</td>
		            <td>30%</td>
		            <td>05%</td>
		        </tr>
		        <tr>
		            <td>Bachelor Degree (adjusted marks)</td>
		            <td>50%</td>
		            <td>30%</td>
		        </tr>
		       <tr>
		            <td>Pre-Entry Test/Aptitude test</td>
		            <td>---</td>
		            <td>60%</td>
		        </tr>
		        <tr>
		            <th>Total</th>
		            <th>100%</th>
		            <th>100%</th>
		        </tr>
		    </table>
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
<script>
	function getStatus (){

		let cnic_no = $("#cnic_no").val();
		let program_type = 2;
		let is_ob='<?=IS_PROVISIONAL_MASTER?>';

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
