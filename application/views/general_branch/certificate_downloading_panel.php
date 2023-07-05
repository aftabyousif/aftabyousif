<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<style type="text/css">
		body
		{
			font-family: Arial;
			font-size: 10pt;
		}
		table
		{
			border: 1px solid #ccc;
			border-collapse: collapse;
		}
		table th
		{
			background-color: #F7F7F7;
			color: #333;
			font-weight: bold;
		}
		table th, table td
		{
			padding: 5px;
			border: 1px solid #ccc;
		}
		h3{
			font-size: 13pt;
			font-family: "Times New Roman", serif;
		}
		.amt-total{
			text-align: center;
			font-family: Helvetica;
			font-size: 11pt;
		}
		input[type="text"] {
			font-size: 9pt;
			/*font-weight: bold;*/
		}
	</style>

	<script type="text/javascript">

		var app = angular.module('myApp', ['ngSanitize']);
		app.controller('formCtrl', function($scope,$http,$window,$filter) {
			//$scope.isyes = true;
			$scope.modifyChallan={};
			$scope.newChallan={};
			$scope.ledgerSum={};
			$scope.balance=0;
            
            $scope.setNull = function(){
                $scope.PROFILE=null;
                $scope.firstname = null;
				$scope.fname = null;
				$scope.lastname = null;
				$scope.campus = null;
				$scope.dept = null;
				$scope.prog_type = null;
				$scope.program = null;
				$scope.session_year = null;
				$scope.challan_logs = null;
				$scope.QUALIFICATION=null;
				$scope.ENROLLMENT_LOG=null;
				$scope.ELIGIBILITY_LOG=null;
            }
			$scope.postdata = function (search_value){

				if (search_value == ""){
					$scope.errorMSG = "Roll No. is required";
					return;
				}

				$scope.errorMSG = "Finding record, please wait....";
				let data = {search_value:search_value};

				$http.post('<?=base_url()?>GeneralBranch/get_profile',data).then(function success(response){

					if (response.status === 206 ){
						$scope.errorMSG= response.data;
						 $scope.setNull();
					}
					if (response.status === 200 ){
						let array_data = response.data;
						$scope.PROFILE=array_data.PROFILE;
						if($scope.PROFILE == null){
						    $scope.errorMSG = "Roll No not found....";
						    $scope.setNull();
						    return;
						}
						$scope.firstname = $scope.PROFILE.FIRST_NAME;
						$scope.fname = $scope.PROFILE.FNAME;
						$scope.lastname = $scope.PROFILE.LAST_NAME;
						$scope.campus = $scope.PROFILE.CAMPUS_NAME;
						$scope.dept = $scope.PROFILE.DEPT_NAME;
						$scope.prog_type = $scope.PROFILE.PROGRAM_TYPE_TITLE+' ('+$scope.PROFILE.SHIFT_NAME+')';
						$scope.program = $scope.PROFILE.PROGRAM_TITLE;
						$scope.session_year = $scope.PROFILE.YEAR;

						$scope.challan_logs = array_data.CHALLAN_LOG;
						$scope.QUALIFICATION=array_data.QUALIFICATION;
						$scope.ENROLLMENT_LOG=array_data.ENROLLMENT_LOG;
						$scope.ELIGIBILITY_LOG=array_data.ELIGIBILITY_LOG;

						$scope.errorMSG = null;
					}

				},function error(response){
				});
			}
			$scope.getCertificate = function (){

				if ($scope.certificate_type == ""){
					$scope.errorMSG = "Certificate/ Card is required";
					return;
				}else if ($scope.PROFILE == ""){
					$scope.errorMSG = "Profile is required";
					return;
				}
				$scope.cert = null;
				$scope.serial_no=null;
				$scope.issue_date=null;
				$scope.remarks=null;
				$scope.challan_selection=null;
				$scope.challan_no=null;
				$scope.status=null;

				$scope.errorMSG = "Finding Certificate, please wait....";
				let data = {profile:$scope.PROFILE,certificate_type:$scope.certificate_type};

				$http.post('<?=base_url()?>GeneralBranch/get_certificate',data).then(function success(response){

					if (response.status == 206 ){
						$scope.errorMSG= response.data;
					}
					if (response.status == 200 ){
						$scope.cert=response.data;
						if ($scope.certificate_type == "ENROLLMENT_CARD"){
							$scope.serial_no=response.data.ENROLMENT_CARD_ID;
						}else if($scope.certificate_type == "ELIGIBILITY_CERTIFICATE"){
							$scope.serial_no=response.data.ELIGIBILITY_CERTIFICATE_ID;
						}
						$scope.issue_date=response.data.ISSUE_DATE;
						$scope.remarks=response.data.REMARKS;
						$scope.status=response.data.ACTIVE;

						$scope.errorMSG = null;

					}

				},function error(response){
				});
			}
			$scope.saveCertificate= function (){

				if ($scope.certificate_type == ""){
					$scope.saveCerterrorMSG = "Certificate/ Card is required";
					return;
				}else if ($scope.PROFILE == ""){
					$scope.saveCerterrorMSG = "Profile is required";
					return;
				}else if($scope.challan_selection == ""){
					$scope.saveCerterrorMSG="Certificate/ Card Challan is required";
					return;
				}else if($scope.challan_no == ""){
					$scope.saveCerterrorMSG="Challan No. is required";
					return;
				}else if($scope.status == ""){
					$scope.saveCerterrorMSG="Status is required";
					return;
				}
				$scope.saveCerterrorMSG = "Saving Certificate, please wait....";
				let data = {profile:$scope.PROFILE,certificate_type:$scope.certificate_type,challan_selection:$scope.challan_selection,challan_no:$scope.challan_no,remarks:$scope.remarks,status:$scope.status};
				$http.post('<?=base_url()?>GeneralBranch/save_certificate',data).then(function success(response){

					if (response.status == 206 ){
						$scope.saveCerterrorMSG= response.data;
					}
					if (response.status == 200 ){
						$scope.postdata($scope.search_value);
						$scope.saveCerterrorMSG= response.data;
					}
				},function error(response){
				});
			}
			$scope.saveNewChallan = function (){
				$scope.newChallan.PROFILE=$scope.PROFILE

				$scope.newChallanError = "Saving Challan, please wait....";
				let data = {challanInfo:$scope.newChallan};

				$http.post('<?=base_url()?>GeneralBranch/save_single_challan_handler',data).then(function success(response){
					if (response.status === 206 ){
						$scope.newChallanError= response.data;
					}
					if (response.status === 200 ){
						$scope.newChallanError= response.data;
						$scope.newChallan={};
						$scope.postdata($scope.search_value);
					}
				},function error(response){
					// console.log(response);
				});
			}

		});
	</script>
</head>
<body>
<div class="product-status mg-b-15" id="min-height">
	<div class="container-fluid">
		<!--		<div class="row">-->
		<!--			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">-->
		<div ng-app="myApp" ng-controller="formCtrl">
			<!--					<form>-->
			<div class="product-status-wrap">
				<h4>Print Certificates</h4>

				<div class="form-group-inner">
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label>Roll No.</label>
							<div class="form-group data-custon-pick data-custom-mg">
								<input type="text" class="form-control" id="search_value" ng-model="search_value" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Student Name</label>
								<input type="text" ng-model="firstname" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Father's Name </label>
								<input type="text" ng-model="fname" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Surname</label>
								<input type="text" ng-model="lastname" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label>Session</label>
								<input type="text" ng-model="session_year" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
							<label>Campus</label>
							<div class="form-group data-custon-pick data-custom-mg">
								<input type="text" class="form-control" id="campus" ng-model="campus" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Department</label>
								<input type="text" ng-model="dept" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Program Type </label>
								<input type="text" ng-model="prog_type" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Program </label>
								<input type="text" ng-model="program" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>

						<br/>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<button type="button" ng-click="postdata(search_value)"  class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" title="Click here to fetch data from database" style="background-color: #00e676"><i class="fa fa-search"></i>&nbsp; <strong>Search Record</strong></button>
						</div>

						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<button
									type="button"
									class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary"
									title="Click here from print PDF"
									style="background-color: rgba(36,172,211,0.87)" data-toggle="modal" data-target="#viewQualification">

								<strong>View Qualification</strong>
							</button>
						</div>

						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<button
									type="button"
									class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary"
									title="Click here from print PDF"
									style="background-color: rgba(159,225,194,0.55)" data-toggle="modal" data-target="#CardLog">

								<strong>Certificate/ Card Log</strong>
							</button>
						</div>

						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<button
									type="button"
									class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary"
									title="Click here from print PDF"
									style="background-color: rgba(92,162,129,0.55)" data-toggle="modal" data-target="#GenerateChallanWindow">

								<strong>Generate Challan</strong>
							</button>
						</div>

						<span class="text-danger" id="msg">{{errorMSG}}</span>
					</div>
					<br/>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Certificate/ Card</label>
								<select class="form-control" ng-model="certificate_type" ng-change="getCertificate()">
									<option value="ENROLLMENT_CARD">Enrollment Card</option>
									<option value="ELIGIBILITY_CERTIFICATE">Eligibility Certificate</option>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Serial No.</label>
								<input type="text" ng-model="serial_no" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Issue Date</label>
								<input type="text" ng-model="issue_date" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Cert: /Card Challan</label>
								<select class="form-control" ng-model="challan_selection">
									<option></option>
									<option value="withChallan">With Challan</option>
									<option value="withoutChallan">Without Challan</option>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Challan No.</label>
								<input type="text" ng-model="challan_no" class="form-control"/>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Status</label>
								<select class="form-control" ng-model="status">
									<option></option>
									<option value="0">De-Active</option>
									<option value="1">Active</option>
									<option value="2">Cancel</option>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Remarks</label>
								<input type="text" ng-model="remarks" class="form-control"/>
							</div>
						</div>
					</div>


					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						<button type="button" ng-click="saveCertificate()"  class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" style="background-color: rgb(221,225,223)"><i class="fa fa-save"></i>&nbsp; <strong>Save Certificate</strong></button>
					</div>
					<span class="text-danger" id="msg">{{saveCerterrorMSG}}</span>
				</div>
				<br/>
			</div>
			<!--					 Customwidth-popup-WarningModal  fade-->
			<div id="editChallanWindow" class="modal" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header header-color-modal bg-color-3">
							<h4 class="modal-title">Edit/ Pay Challan</h4>
							<div class="modal-close-area modal-close-df">
								<a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
							</div>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<h3>Challan Information</h3>
									<table class="table table-condensed">
										<tr><th>Challan No</th><td style="background-color: black;color: white;font-weight: bold; text-align: center">{{modifyChallan.FEE_CHALLAN.CHALLAN_NO}}</td></tr>
										<tr><th>Program Title</th><td>{{modifyChallan.PROFILE.PROGRAM_TITLE}}</td></tr>
										<tr><th>Category</th><td>{{modifyChallan.PROFILE.CATEGORY_NAME}}</td></tr>
										<tr><th>Shift</th><td>{{modifyChallan.PROFILE.SHIFT_NAME}}</td></tr>
										<tr><th>Part</th><td>{{modifyChallan.FEE_CHALLAN.PART_NAME}}</td></tr>
										<tr><th>Semester</th><td>{{modifyChallan.FEE_CHALLAN.SEMESTER_NAME}}</td></tr>
									</table>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
									<div style="border: 1px solid black; padding: 10px">
										<h3>Modify Challan</h3>

										<div class="form-group-inner">
											<label>Challan Amount <span class="text-danger"><em>Editable</em></span></label>
											<input type="text" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.CHALLAN_AMOUNT" ng-value="modifyChallan.FEE_CHALLAN.CHALLAN_AMOUNT"/>
										</div>
										<div class="form-group-inner">
											<label>Payable Amount <span class="text-danger"><em>Editable</em></span></label>
											<input type="text" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT" ng-value="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT"/>
										</div>
										<div class="form-group-inner">
											<label>Valid Upto <span class="text-danger">yyyy-mm-dd</span></label>
											<input type="text" ng-model="modifyChallan.FEE_CHALLAN.VALID_UPTO" ng-value="modifyChallan.FEE_CHALLAN.VALID_UPTO" class="form-control"/>
										</div>
										<button ng-click="saveChallanChanges()" class="btn btn-sm- btn-primary">Save Changes</button>
									</div>
								</div>

								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									<div style="border: 1px solid black; padding: 10px">
										<h3>Mark Paid</h3>
										<div class="form-group-inner">
											<label>Receivable Amount</label>
											<input type="text" readonly style="text-align: center;background-color: black; color: white;font-weight: bold" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT" ng-value="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT"/>
										</div>
										<div class="form-group-inner">
											<label>Paid Amount</label>
											<input type="text" ng-init="balance=0"  ng-keyup="balance = modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT - modifyChallan.FEE_CHALLAN.PAID_AMOUNT" ng-model="modifyChallan.FEE_CHALLAN.PAID_AMOUNT" class="form-control"/>
										</div>
										<div class="form-group-inner">
											<label>Balance</label>
											<input type="text" ng-value="balance" class="form-control" style="text-align: center;background-color: red; color: white;font-weight: bold" readonly/>
										</div>
										<div class="form-group-inner">
											<label>Paid Date <span class="text-danger">yyyy-mm-dd</span></label>
											<input type="text" ng-model="modifyChallan.FEE_CHALLAN.PAID_DATE" ng-value="modifyChallan.FEE_CHALLAN.PAID_DATE" class="form-control"/>
										</div>
										<button ng-click="markChallanPaid()" class="btn btn-sm- btn-warning">Mark Paid</button>
									</div>
								</div>
							</div>

						</div>
						<div class="modal-footer warning-md">
							<span class="text-danger text-left">{{ChallanError}}</span>
							<a data-dismiss="modal" href="#">Cancel</a>

						</div>
					</div>
				</div>
			</div>

			<div id="GenerateChallanWindow" class="modal" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header header-color-modal bg-color-3">
							<h4 class="modal-title">Generate Challan</h4>
							<div class="modal-close-area modal-close-df">
								<a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
							</div>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<h3>Challans</h3>
									<div class="table-responsive">
										<table class="table table-condensed">
											<thead style="font-size: 8pt;">
											<tr>
												<th>#</th>
												<th>Challan #</th>
												<th>Roll No.</th>
												<th>Program Title</th>
												<th>Challan Type</th>
												<th>Amount</th>
												<th>CH: Date</th>
												<th>Due Date</th>
											</tr>
											</thead>
											<tr ng-repeat="challan_log in challan_logs  track by $index" style="font-size: 8pt;">
												<td>{{$index+1}}</td>
												<td><a href="<?=base_url()?>general_branch_challan/{{challan_log.DECODED}}" target="_blank">{{challan_log.CHALLAN_NO}}</a></td>
												<td><b>{{challan_log.ROLL_NO}}</b></td>
												<td><b>{{challan_log.PROGRAM_TITLE}}</b></td>
												<td><b>{{challan_log.TYPE_CODE}}</b></td>
												<td>{{challan_log.CHALLAN_AMOUNT}}</td>
												<td>{{challan_log.CHALLAN_DATE}}</td>
												<td>{{challan_log.DUE_DATE}}</td>
											</tr>
										</table>
									</div>
								</div>
						</div>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<label>Challan Type</label>
											<select ng-model="newChallan.CHALLAN_TYPE_ID" class="form-control">
												<option value="53-001">Enrollment Card</option>
												<option value="53-002">Eligibility Cert:</option>
											</select>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<label>Challan Amount</label>
											<input type="text" class="form-control" id="end_date" ng-model="newChallan.CHALLAN_AMOUNT"  style="background-color: black;color: white; text-align: center;font-weight: bold;" />
										</div>
									</div>
									<br/>
									<button ng-click="saveNewChallan()" class="btn btn-sm- btn-warning">Generate Challan</button>

						<div class="modal-footer warning-md">
							<span class="text-danger text-left">{{newChallanError}}</span>
							<a data-dismiss="modal" href="#">Cancel</a>

						</div>
					</div>
				</div>
			</div>
			</div>

			<div id="CardLog" class="modal" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header header-color-modal bg-color-4">
							<h4 class="modal-title">Certificate/ Card Log History</h4>
							<div class="modal-close-area modal-close-df">
								<a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
							</div>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-condensed">
											<thead style="font-size: 8pt;">
											<tr>
												<th>Download/Print</th>
												<th>Serial No.</th>
												<th>Cert:/ Card Name</th>
												<th>Roll No.</th>
												<th>Program</th>
												<th>Issue Date</th>
												<th>Remarks</th>
												<th>Challan No</th>
												<th>Challan Date</th>
												<th>Is Reissued</th>
												<th>Issued By</th>
												<th>Status</th>
											</tr>
											</thead>
											<tr ng-repeat="ENROLLMENT in ENROLLMENT_LOG  track by $index" style="font-size: 8pt;">
												<td ng-bind-html="ENROLLMENT.URL"></td>
												<td>{{ENROLLMENT.ENROLMENT_CARD_ID}}</td>
												<td>{{ENROLLMENT.CERT_TYPE}}</td>
												<td>{{ENROLLMENT.ROLL_NO}}</td>
												<td>{{ENROLLMENT.PROGRAM_TITLE}}</td>
												<td>{{ENROLLMENT.ISSUE_DATE}}</td>
												<td>{{ENROLLMENT.REMARKS}}</td>
												<td>{{ENROLLMENT.CHALLAN_NO}}</td>
												<td>{{ENROLLMENT.CHALLAN_DATE}}</td>
												<td>{{ENROLLMENT.IS_REISSUED_DECODE}}</td>
												<td>{{ENROLLMENT.ISSUER_NAME}}</td>
												<td ng-bind-html="ENROLLMENT.STATUS_DECODE"></td>
											</tr>

											<tr ng-repeat="ELIGIBILITY in ELIGIBILITY_LOG  track by $index" style="font-size: 8pt;">
												<td ng-bind-html="ELIGIBILITY.URL"></td>
												<td>{{ELIGIBILITY.ELIGIBILITY_CERTIFICATE_ID}}</td>
												<td>{{ELIGIBILITY.CERT_TYPE}}</td>
												<td>{{ELIGIBILITY.ROLL_NO}}</td>
												<td>{{ELIGIBILITY.PROGRAM_TITLE}}</td>
												<td>{{ELIGIBILITY.ISSUE_DATE}}</td>
												<td>{{ELIGIBILITY.REMARKS}}</td>
												<td>{{ELIGIBILITY.CHALLAN_NO}}</td>
												<td>{{ELIGIBILITY.CHALLAN_DATE}}</td>
												<td>{{ELIGIBILITY.IS_REISSUED_DECODE}}</td>
												<td>{{ELIGIBILITY.ISSUER_NAME}}</td>
												<td ng-bind-html="ELIGIBILITY.STATUS_DECODE"></td>
											</tr>

										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer warning-md">
							<a data-dismiss="modal" href="#">Cancel</a>
						</div>
					</div>
				</div>
			</div>

			<div id="viewQualification" class="modal" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header header-color-modal bg-color-2">
							<h4 class="modal-title">Qualifications</h4>
							<div class="modal-close-area modal-close-df">
								<a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
							</div>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-condensed">
											<thead style="font-size: 8pt;">
											<tr>
												<th>Examination Passed </th>
												<th>Group</th>
												<th>Marks Obtained</th>
												<th>Total Marks</th>
												<th>Year</th>
												<th>Seat No</th>
												<th>Name of Board/University</th>
											</tr>
											</thead>
											<tr ng-repeat="QUAL in QUALIFICATION  track by $index" style="font-size: 8pt;">
												<td>{{QUAL.DEGREE_TITLE}}</td>
												<td>{{QUAL.DISCIPLINE_NAME}}</td>
												<td>{{QUAL.OBTAINED_MARKS}}</td>
												<td>{{QUAL.TOTAL_MARKS}}</td>
												<td>{{QUAL.PASSING_YEAR}}</td>
												<td>{{QUAL.ROLL_NO}}</td>
												<td>{{QUAL.ORGANIZATION}}</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer warning-md">
							<a data-dismiss="modal" href="#">Cancel</a>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
</body>
</html>
