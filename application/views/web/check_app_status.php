<div style="height:100px"></div>
<div class="container-fluid">
	<div class="tim-typo">
		<h2 class="title">Application Status For The Admissions <?=SESSION_YEAR?></h2>
	</div>
	
	<div class="row">
		<div class="col-md-4 col-sm-12 col-xs-12 offset-3">
			<div class="form-group has-default">
			    <label>CNIC NO <span class='text-rose'>without " - " hyphen</span></label>
				<input type="number" min="0" class="form-control" id="cnic_no" placeholder="CNIC NO">
			</div>
			<button class="btn btn-info" id="search" onclick="getStatus()"><i class="fa fa-search"></i> Search</button>
		</div>
	</div>
	<div class="section">
		<table class="table table-hover">
			<tr>
				<th>Application #</th>
				<th>Full Name</th>
				<th>Father's Name</th>
				<th>Applied Campus</th>
				<th>Admission</th>
				<th>Degree Program</th>
				<th>Message</th>
				<th>Status</th>
			</tr>
			<tbody id="table_data">
			</tbody>
		</table>
		<!--<div class="clearfix"></div>-->
	</div>
	<!--<div class="space-10"></div>-->
	<div >
		<div class="container tim-container">
			<!--     	        typography -->
			<div id="typography" class="cd-section">
				<div class="title">
					<h3>Please read the following detailed instructions of application status</h3>
				</div>
				<div class="row">
					<div class="tim-typo">
						<span class="badge badge-pill badge-secondary tim-note hd" style="color:ghostwhite;font-size: 12pt">Draft</span>
<!--						<span class="tim-note">Muted Text</span>-->
						<p>
							Applicant just registered for Admissions <?=SESSION_YEAR?>. <b>Applications having “DRAFT” status will not be considered for selection process.</b>
						</p>
					</div>
<!--					<div class="tim-typo">-->
<!--						<span class="badge badge-pill badge-primary tim-note" style="color:ghostwhite;font-size: 12pt">Submitted</span>-->
<!--						<span class="tim-note">Primary Text</span>-->
<!--						<p class="text-primary">-->
<!--							<b>Applications having status “SUBMITTED” will not be considered for selection process.</b> Applicants are required to login to their accounts & select subjects of their choice in preference order and then submit the form. Status of application/ form will be changed to "IN PROCESS". </p>-->
<!--					</div>-->
					<div class="tim-typo">
						<span class="badge badge-pill badge-info tim-note" style="color:ghostwhite;font-size: 12pt">In Process</span>
						<p class="text-info">
							Applications having status <b>“IN PROCESS”</b> are under verification process. Applicants are advised to frequently visit their E-Portal account dashboard for updates.</p>
					</div>
					<div class="tim-typo">
						<span class="badge badge-pill badge-rose tim-note" style="color:ghostwhite;font-size: 12pt">In Review</span>
						<p class="text-rose">
							Applications having status <b>“IN REVIEW”</b> are incomplete or having some wrong information. Applicants are required to clear / complete requirements as mentioned in “Remarks” within 24 hours. </p>
					</div>
					<div class="tim-typo">
						<span class="badge badge-pill badge-success tim-note" style="color:ghostwhite;font-size: 12pt">Form Verified</span>
						<p class="text-success">
							<b>Applications having status “FORM VERIFIED” will be considered for selection process.</b>
						</p>
					</div>
					<div class="tim-typo">
						<span class="badge badge-pill badge-danger tim-note" style="color:ghostwhite;font-size: 12pt">Form Rejected</span>
						<p class="text-danger">
							<b>Applications having status “FORM REJECTED” will NOT be considered for selection.</b></p>
					</div>

					<!--<div class="tim-typo">-->
					<!--	<span class="badge badge-pill badge-warning tim-note" style="color:ghostwhite;font-size: 12pt">Enrolled</span>-->
					<!--	<p class="text-danger">-->
					<!--		I will be the leader of a company that ends up being worth billions of dollars, because I got the answers... </p>-->
					<!--</div>-->
					
					<div class="tim-typo">
						<!--<span class="text-danger tim-note" style="color:ghostwhite;font-size: 12pt">Please Note</span>-->
						<p class="text-danger">
						<b>Please Note:</b>	Applications having DRAFT / IN REVIEW / REJECTED status will <b><u>NOT</u></b> be considered for Selection Process.</p>
						<p class='text-danger'>Applications having <b>“IN PROCESS”</b> status will be considered for verification. </p>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<script>
	const _0x196b=['</p></td>','val','ajax','json','<h2\x20class=\x27text-danger\x27>No\x20record\x20found...</h2>','<?=base_url()?>web/get_application_status','<td><b>','parse','</td>','STATUS_NAME','</b></td>','FORM_DATA','PROGRAM_TITLE','<tr>','append','FIRST_NAME','MESSAGE','empty','<td>','FNAME','</tr>','each','users_reg','<td>\x20Admission\x20','YEAR','No\x20record\x20found...','#table_data'];(function(_0x13593b,_0x234b03){const _0x196bc7=function(_0x5f5137){while(--_0x5f5137){_0x13593b['push'](_0x13593b['shift']());}};_0x196bc7(++_0x234b03);}(_0x196b,0x1b2));const _0x5f51=function(_0x13593b,_0x234b03){_0x13593b=_0x13593b-0x10d;let _0x196bc7=_0x196b[_0x13593b];return _0x196bc7;};function getStatus(){const _0x524f4a=_0x5f51;let _0x4dfba9=$('#cnic_no')[_0x524f4a(0x127)]();if(_0x4dfba9===''||_0x4dfba9===0x0||_0x4dfba9==null||isNaN(_0x4dfba9))return;$('#table_data')[_0x524f4a(0x11c)](),$[_0x524f4a(0x10d)]({'url':_0x524f4a(0x110),'method':'POST','data':{'cnic_no':_0x4dfba9},'dataType':_0x524f4a(0x10e),'success':function(_0x1dcc9e){const _0x3db418=_0x524f4a;if(_0x1dcc9e==_0x3db418(0x124)){alert_msg(_0x3db418(0x10f),'Message');return;}$[_0x3db418(0x120)](_0x1dcc9e,function(_0x908a52,_0x29d9fc){const _0x2e523b=_0x3db418;let _0x2f24e5=_0x29d9fc[_0x2e523b(0x11b)],_0x2b6d6c=_0x29d9fc[_0x2e523b(0x114)],_0x2306ed=_0x29d9fc[_0x2e523b(0x116)];_0x2306ed=JSON[_0x2e523b(0x112)](_0x2306ed);let _0x5c588b=_0x2306ed[_0x2e523b(0x121)];if(_0x2f24e5==null||_0x2f24e5==='')_0x2f24e5='';let _0x19fce3=_0x2e523b(0x118);_0x19fce3+=_0x2e523b(0x11d)+_0x29d9fc['APPLICATION_ID']+_0x2e523b(0x113),_0x19fce3+=_0x2e523b(0x111)+_0x5c588b[_0x2e523b(0x11a)]+_0x2e523b(0x115),_0x19fce3+=_0x2e523b(0x111)+_0x5c588b[_0x2e523b(0x11e)]+_0x2e523b(0x115),_0x19fce3+=_0x2e523b(0x11d)+_0x29d9fc['NAME']+'</td>',_0x19fce3+=_0x2e523b(0x122)+_0x29d9fc[_0x2e523b(0x123)]+_0x2e523b(0x113),_0x19fce3+=_0x2e523b(0x11d)+_0x29d9fc[_0x2e523b(0x117)]+_0x2e523b(0x113),_0x19fce3+='<td><p\x20class=\x27text-danger\x27>'+_0x2f24e5+_0x2e523b(0x126),_0x19fce3+='<td><b>'+_0x2b6d6c+_0x2e523b(0x115),_0x19fce3+=_0x2e523b(0x11f),$(_0x2e523b(0x125))[_0x2e523b(0x119)](_0x19fce3);});}});}

</script>
