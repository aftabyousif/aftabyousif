<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>
<style>
	#table-wrapper {
		position:relative;
	}
	#table-scroll {
		height:200px;
		overflow:auto;
		margin-top:20px;
	}
	#table-wrapper table {
		width:100%;

	}
	#table-wrapper table * {
		/*background:yellow;*/
		/*color:black;*/
	}
	#table-wrapper table thead th .text {
		position:absolute;
		top:-20px;
		z-index:2;
		height:20px;
		width:35%;
		border:1px solid red;
	}
	.AmountField{
		padding: 4px;
		font-weight: bold;
		width: 4cm;
		font-family: Helvetica;
	}
</style>
<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid" id="min-height">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Enrolment Fee</h1>
			</div>
		</div>
		<div ng-app="myApp" ng-controller="formCtrl">

			<div class='row'>
				<div class='col-md-8'>

					<div class="col-md-2">
						<label>Session</label>
						<select ng-model="SessionModel"  class="form-control" ng-change="getFeeEnrolment(ProgramTypesModel,SessionModel);">
							<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>Program Type</label>
						<select ng-model="ProgramTypesModel" class="form-control" ng-change="getFeeEnrolment(ProgramTypesModel,SessionModel);">
							<option ng-repeat="types in ProgramTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
						</select>
					</div>

				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="button-ap-list responsive-btn">
						<div class="button-style-four">
							<div class="button-drop-style-two">
								<button type="button" class="btn btn-custon-rounded-four btn-primary" ng-click="saveFeeEnrolment();"><i class="fa fa-save edu-informatio" aria-hidden="true"></i> Save Fees Structure</button>
							</div>
							<div class="button-drop-style-two">
								<button type="button" class="btn btn-custon-rounded-four btn-danger" ng-click="deleteFeesEnrolment()"><i class="fa fa-trash edu-informatio" aria-hidden="true"></i> Delete Ledger</button>
							</div>
							<div class="button-drop-style-two">
								<button type="button" class="btn btn-custon-rounded-four btn-warning" ng-click="download()"><i class="fa fa-file-excel-o edu-informatio" aria-hidden="true"></i> Csv Report</button>
							</div>
							<div class="button-drop-style-two">
								<button type="button" class="btn btn-custon-rounded-four btn-success" ng-click="openFeesImportWindow(CampusModel,SessionModel,ProgramTypesModel,ShiftsModel,DemeritModel,PartModel,SemesterModel,FeeCategoryType)"><i class="fa fa-upload edu-informatio" aria-hidden="true"></i> Dump Fees Structure</button>
							</div>

							<span id="loading">{{loading}}</span>
							<span class="text-danger">{{errorMsg}}</span>

						</div>
					</div>

				</div>
			</div>
			<br/>
			<div class="asset-inner">
				<table class="table table-condensed">
					<thead>
					<tr style="font-size: 10pt;text-align: center">
						<th><input type="checkbox" ng-model="checkallModel" id="checkAll" name="checkAll" ng-change="checkAll()"></th>
						<th>S#</th>
						<th>FEE ENROL #</th>
						<th>INSTITUTE #</th>
						<th>INSTITUTE NAME</th>
						<th>AMOUNT</th>
						<th>REMARKS</th>
					</tr>
					</thead>
					<tbody>
					<tr ng-repeat="fee_enrolment in fee_enrolment_list track by $index" style="font-size: 11pt">
						<th><input type="checkbox" ng-model="checkoneModel" id="checkone" name="checkone" value="{{fee_enrolment.FEE_ENROLMENT_ID}}"></th>
						<td>{{$index+1}}</td>
						<td>{{fee_enrolment.FEE_ENROLMENT_ID}}</td>
						<td>{{fee_enrolment.INSTITUTE_ID}}</td>
						<td>{{fee_enrolment.INSTITUTE_NAME}}</td>
						<td><input type="number" min="0" value="{{fee_enrolment.AMOUNT}}" ng-model="feeAmount"  class="AmountField" ng-keyup="addAmount(fee_enrolment,feeAmount,fee_enrolment_remarks)"/></td>
						<td><input type="text" ng-value="fee_enrolment.FEE_ENROLMENT_REMARKS" ng-model="fee_enrolment_remarks"  class="AmountField" ng-keyup="addAmount(fee_enrolment,feeAmount,fee_enrolment_remarks)"/></td>

					</tr>
					</tbody>
				</table>
			</div>

			<div id="ImportFeeStructure" class="modal" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header header-color-modal bg-color-3">
							<h4 class="modal-title">Dump Fees Structure</h4>
							<div class="modal-close-area modal-close-df">
								<a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
							</div>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="button-ap-list responsive-btn">
										<div class="button-style-four">
											<div class="button-drop-style-two">
												<label>From Session</label>
												<select ng-model="FromSessionModel"  class="form-control">
													<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
												</select>
											</div> <br/>

											<div class="button-drop-style-two">
												<label>To Session </label>
												<select ng-model="NewSessionModel"  class="form-control">
													<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
												</select>
											</div> <br/><br/>
											<div class="button-drop-style-two">
												<button type="button" class="btn btn-custon-rounded-four btn-primary" ng-click="FeesImport(FromSessionModel,NewSessionModel)"><i class="fa fa-upload edu-informatio" aria-hidden="true"></i> Upload Fees Structure</button>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
						<div class="modal-footer warning-md">
							<span class="text-danger text-left">{{DumpError}}</span>
							<a data-dismiss="modal" href="#">Cancel</a>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>-->
<script type="text/javascript">

	var app = angular.module('myApp', []);
	app.controller('formCtrl', function($scope,$http,$window) {
		$scope.check_fee_update = false; // its created to check if we are updating through same program true if we are updating single fee program list false if it is bulk.
		$scope.fee_program_details = null;

		$scope.checkSingleCheckbox = function (){
			$scope.check_fee_update = false;
			$scope.fee_program_details = null;
			$scope.SelectedProgramTitle=null;
		}

		$scope.getProgramTypes	= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.ProgramTypes = null;
			$http.post('<?=base_url()?>AdminApi/getProgramTypes').then(function success(response){
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.ProgramTypes=array_data;
				}

			},function error(response){
				$scope.errorMSG= 'Sorry could not find data';
			});
		}
		$scope.getSessions 		= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.sessions = null;
			$http.post('<?=base_url()?>AdminApi/getSessions').then(function success(response){
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.sessions=array_data;
				}

			},function error(response){
				$scope.errorMSG= 'Sorry could not find data';
			});
		}
		$scope.getFeeEnrolment 	= function (ProgramTypesModel,SessionModel){

			$scope.errorMsg = null
			if (SessionModel == null){
				$scope.errorMsg = "Select Session";
				return;
			}else if (ProgramTypesModel == null){
				$scope.errorMsg = "Select Program type";
				return;
			}

			$scope.errorMsg = null;
			$scope.loading="Loading data please wait...";
			let data = {program_type_id:ProgramTypesModel,session_id:SessionModel};

			$scope.fee_enrolment_list=null;
			$http.post('<?=base_url()?>FeesSetup/get_fee_enrolment_handler',data).then(function success(response){
				if (response.status == 206 ){
					$scope.errorMsg= response.data;
					$scope.loading=null;
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.fee_enrolment_list=array_data;
					$scope.loading=null;
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.checkAll 		= function (){

			if ($scope.checkallModel){
				$("input[name=checkone]").prop("checked","checked");
			}else{
				$("input[name=checkone]").attr("checked",false);
			}
		}
		$scope.addAmount 		= function (fee_enrolment,feeAmount,fee_enrolment_remarks){
			if (feeAmount !==undefined) fee_enrolment.AMOUNT=feeAmount;
			if (fee_enrolment_remarks !==undefined) fee_enrolment.FEE_ENROLMENT_REMARKS=fee_enrolment_remarks;
		}
		$scope.saveFeeEnrolment	= function (){

			$scope.errorMsg = null;
			if ($scope.SessionModel == null){
				$scope.errorMsg = "Session is Required.";
				return;
			}else if (($scope.fee_enrolment_list == null || Object.keys($scope.fee_enrolment_list).length===0)){
				$scope.errorMsg = "Fee Enrolment list is empty";
				return;
			}

			$scope.errorMsg = "Saving Records....";
			let data = {fee_enrolment_list:$scope.fee_enrolment_list,session_id:$scope.SessionModel};
			$http.post('<?=base_url()?>FeesSetup/saveFeesEnrolmentHandler',data).then(function success(response){
				if (response.status === 206 ){
					$scope.errorMsg= response.data;
				}
				if (response.status === 200 ){
					$scope.errorMsg= response.data;
					$scope.getFeeEnrolment($scope.ProgramTypesModel,$scope.SessionModel);
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.deleteFeesEnrolment= function (){

			let fee_enrolment_ids = [];
			$("input[name=checkone]:checked").each(function(key,value){
				if (this.value == null || this.value == "") return;
				fee_enrolment_ids.push(this.value);
			});

			if(Object.keys(fee_enrolment_ids).length===0){
				$scope.errorMsg="Tick Fee Enrolment"
				return;
			}
			if (confirm("Do you want to delete?") === false) {
				return;
			}

			$scope.errorMsg = "Deleting Records....";
			let data = {fee_enrolment_ids:fee_enrolment_ids};
			$http.post('<?=base_url()?>FeesSetup/DeleteFeesEnrolmentHandler',data).then(function success(response){
				if (response.status === 206 ){
					$scope.errorMsg= response.data;
				}
				if (response.status === 200 ){
					$scope.errorMsg= response.data;
					$scope.getFeeEnrolment($scope.ProgramTypesModel,$scope.SessionModel);
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.download			= function (){

			let records = $scope.fee_enrolment_list;

			let date = new Date();

			let csvString = '';

			csvString = csvString+"FEE_ENROLMENT_ID"+",";
			csvString = csvString+"INSTITUTE_ID,"
			csvString = csvString+"INSTITUTE_NAME,"
			csvString = csvString+"AMOUNT,"
			csvString = csvString+"REMARKS\n"
			angular.forEach(records,function(value,key) {
				if (value['FEE_ENROLMENT_ID']=="" || value['FEE_ENROLMENT_ID']== null) return

				csvString = csvString +value['FEE_ENROLMENT_ID']+",";
				csvString = csvString +value['INSTITUTE_ID']+",";
				csvString = csvString +value['INSTITUTE_NAME'].replace(',',' - ')+",";
				csvString = csvString +value['AMOUNT']+",";
				csvString = csvString +value['FEE_ENROLMENT_REMARKS'].replace(',',' - ')+"\n";
			});
			var a = $('<a/>', {
				style:'display:none',
				href:'data:application/octet-stream;base64,'+btoa(csvString),
				download:'fee_enrolment'+date+'.csv'
			}).appendTo('body')
			a[0].click()
			a.remove();

		}
		$scope.FeesImport= function (SessionModel,NewSessionModel){

			if(SessionModel == null){
				$scope.DumpError="Select from session";
				return;
			}else if(NewSessionModel == null){
				$scope.DumpError="Select new session";
				return;
			}
			if (confirm("Do you want to import Fee Enrolment?") === false){
				return;
			}
			$scope.DumpError = null;
			$scope.DumpError="Importing data please wait...";
			let data = {session_id:SessionModel,new_session_id:NewSessionModel};

			$http.post('<?=base_url()?>FeesSetup/DumpFeesEnrolmentHandler',data).then(function success(response){
				if (response.status == 206 ){
					$scope.DumpError= response.data;
				}
				if (response.status == 200 ){
					$scope.DumpError  = response.data;
				}
			},function error(response){
				// console.log(response);
			});

		}


		$scope.openFeesImportWindow= function (CampusModel,SessionModel,ProgramTypesModel,ShiftsModel,DemeritModel,PartModel,SemesterModel,FeeCategoryType){

			$scope.FeeDumpErrorCampus=null;
			$scope.FeeDumpErrorSession=null;
			$scope.FeeDumpErrorProgramType=null;
			$scope.FeeDumpErrorShift=null;
			$scope.FeeDumpErrorDemerit=null;
			$scope.FeeDumpErrorPart=null;
			$scope.FeeDumpErrorSemester=null;
			$scope.FeeDumpErrorFeeCategoryType=null;

			$("#ImportFeeStructure").modal("show");

			if (CampusModel==null){
				$scope.FeeDumpErrorCampus="is Required";
				return;
			}else if(SessionModel == null){
				$scope.FeeDumpErrorSession="is Required";
				return;
			}else if(ProgramTypesModel == null){
				$scope.FeeDumpErrorProgramType="is Required";
				return;
			}else if(ShiftsModel == null){
				$scope.FeeDumpErrorShift="is Required";
				return;
			}else if(DemeritModel == null){
				$scope.FeeDumpErrorDemerit="is Required";
				return;
			}else if(PartModel == null){
				$scope.FeeDumpErrorPart="is Required";
				return;
			}else if(SemesterModel == null){
				$scope.FeeDumpErrorSemester="is Required";
				return;
			}else if(FeeCategoryType == null){
				$scope.FeeDumpErrorFeeCategoryType="is Required";
				return;
			}

		}

		$scope.getProgramTypes();
		$scope.getSessions();
	});
</script>
