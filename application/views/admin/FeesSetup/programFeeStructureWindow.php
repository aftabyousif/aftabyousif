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
				<h1>Fees Structure</h1>
			</div>
		</div>
		<div ng-app="myApp" ng-controller="formCtrl">

			<div class='row'>
				<div class='col-md-8'>
					<div class="col-md-4">
						<label>Campus</label>
						<select ng-model="CampusModel" class="form-control" ng-change="getFeeProgramList(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel);">
							<option ng-repeat="campus in campuses" ng-value="{{campus.CAMPUS_ID}}">{{campus.LOCATION}}</option>
						</select>
					</div>

					<div class="col-md-2">
						<label>Session</label>
						<select ng-model="SessionModel"  class="form-control">
							<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>Program Type</label>
						<select ng-model="ProgramTypesModel" class="form-control" ng-change="getFeeProgramList(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel); getPart(ProgramTypesModel)">
							<option ng-repeat="types in ProgramTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>Shifts</label>
						<select ng-model="ShiftsModel" class="form-control" ng-change="getFeeProgramList(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel);">
							<option ng-repeat="shift in shifts" ng-value="{{shift.SHIFT_ID}}">{{shift.SHIFT_NAME}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>Demerit</label>
						<select ng-model="DemeritModel" class="form-control" ng-change="getSemester(DemeritModel);getFeeProgramList(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel)">
							<option ng-repeat="demeri in demerit" ng-value="{{demeri.FEE_DEMERIT_ID }}">{{demeri.NAME}}</option>
						</select>
					</div>

					<div class="col-md-3">
						<label>Part</label>
						<select ng-model="PartModel" class="form-control" ng-change="getFeeProgramList(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel)">
							<option ng-repeat="part in parts" ng-value="{{part.PART_ID}}">{{part.NAME}} {{part.REMARKS}}</option>
						</select>
					</div>

					<div class="col-md-3">
						<label>Semester</label>
						<select ng-model="SemesterModel" class="form-control" ng-change="getFeeProgramList(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel)">
							<option ng-repeat="semester in semesters" ng-value="{{semester.SEMESTER_ID}}">{{semester.ORDINAL_NUM}}</option>
						</select>
					</div>

					<div class="col-md-4">
						<label>Fee Category Type</label>
						<select ng-model="FeeCategoryType" class="form-control" ng-change="getFeeCategory()">
							<option ng-repeat="fee_category in fee_category_type" ng-value="{{fee_category.FEE_CATEGORY_TYPE_ID}}">{{fee_category.FEE_TYPE_TITLE}}</option>
						</select>
					</div>

				</div>
				<div class='col-md-4'>
					<div id="table-wrapper">
						<div id="table-scroll">
					<table class="table table-condensed">
						<thead>
						<tr>
							<th><input type="checkbox" name="checkAll" ng-model="checkAllModel" ng-change="checkAll()"></th>
							<th>Program Title</th>
							<th>Edit</th>
						</tr>
						</thead>
						<tbody>
						<tr ng-repeat="fee_prog_list in fee_prog_lists">
							<td><span class="text"><input type="checkbox" ng-model="fee_prog_list_checkbox" name="selectFeeCategoryRow" value="{{fee_prog_list.FEE_PROG_LIST_ID}}" ng-change="checkSingleCheckbox()"></span></td>
							<td><span class="text">{{fee_prog_list.PROGRAM_TITLE}}</span></td>
							<td><span class="text"><a href="javascript:void(0)" ng-click="editFeeStructure(fee_prog_list,SessionModel,FeeCategoryType)">Edit</a></span></td>
						</tr>
						</tbody>
					</table>
						</div>
					</div>
					<span style="background-color: black;color: white;font-size: 11pt;font-weight: bold;padding: 4px;border-radius: 4px">{{SelectedProgramTitle}}</span>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="button-ap-list responsive-btn">
						<div class="button-style-four">
							<div class="button-drop-style-two">
								<button type="button" class="btn btn-custon-rounded-four btn-primary" ng-click="saveFeeStructure(SessionModel,FeeCategoryType)"><i class="fa fa-save edu-informatio" aria-hidden="true"></i> Save Fees Structure</button>
							</div>
							<div class="button-drop-style-two">
								<button type="button" class="btn btn-custon-rounded-four btn-danger" ng-click="deleteFeesStructure(SessionModel,FeeCategoryType)"><i class="fa fa-trash edu-informatio" aria-hidden="true"></i> Delete Ledger</button>
							</div>
							<div class="button-drop-style-two">
								<button type="button" class="btn btn-custon-rounded-four btn-warning" ng-click="download(SessionModel,FeeCategoryType)"><i class="fa fa-file-excel-o edu-informatio" aria-hidden="true"></i> Csv Report</button>
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

			<div class="asset-inner">
				<table class="table table-condensed">
					<thead>
					<tr style="font-size: 10pt;text-align: center">
						<th>S.NO</th>
						<th>FEE CATEGORY ID</th>
						<th>FEE CATEGORY TITLE</th>
						<th>AMOUNT</th>
					</thead>
					<tbody>
					<tr ng-repeat="fee_category in fee_categories track by $index" style="font-size: 11pt">
					    <td>{{$index+1}}</td>
					    <td>{{fee_category.FEE_CATEGORY_ID}}</td>
						<td>{{fee_category.CATEGORY_TITLE}}</td>
						<td><input type="number" min="0" value="{{fee_category.FEE_AMOUNT}}" ng-model="feeAmount" ng-keyup="addAmount(fee_category,feeAmount)" class="AmountField"/></td>
					</tr>
					<tr>
                            <td></td>
                            <td></td>
                            <td  style="text-align: right"><h4>Total Amount : </h5></td>
                            <td><h4>{{sum}}</h4></td>
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
		$scope.checkAll= function (){
				if ($scope.checkAllModel){
					$("input[name=selectFeeCategoryRow]").prop("checked","checked");
				}else{
					$("input[name=selectFeeCategoryRow]").attr("checked",false);
				}
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
		$scope.getShifts 		= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.shifts = null;
			$http.post('<?=base_url()?>AdminApi/getShifts').then(function success(response){
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.shifts=array_data;
				}

			},function error(response){
				$scope.errorMSG= 'Sorry could not find data';
			});
		}
		$scope.getCampus 		= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.campuses = null;
			$http.post('<?=base_url()?>AdminApi/getCampuses').then(function success(response){
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.campuses=array_data;
				}

			},function error(response){
				$scope.errorMSG= 'Sorry could not find data';
			});
		}
		$scope.getFeeProgramList= function (ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel){

			$scope.errorMsg = null
			if (CampusModel == null){
				$scope.errorMsg = "Select Campus";
				return;
			}else if (ProgramTypesModel == null){
				$scope.errorMsg = "Select Program type";
				return;
			}else if (ShiftsModel == null){
				$scope.errorMsg = "Select Shift";
				return;
			}else if (DemeritModel == null){
				$scope.errorMsg = "Select Demerit";
				return;
			}else if (PartModel == null){
				$scope.errorMsg = "Select Part";
				return;
			}else if (SemesterModel == null){
				$scope.errorMsg = "Select Semester";
				return;
			}

			$scope.errorMsg = null;
			$scope.loading="Loading data please wait...";
			let data = {campus_id:CampusModel,program_type_id:ProgramTypesModel,shift_id:ShiftsModel,part_id:PartModel,demerit_id:DemeritModel,semester_id:SemesterModel};

			$scope.fee_prog_lists=null;
			$http.post('<?=base_url()?>FeesSetup/get_fee_program_list_for_fee_structure_handler',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
					$scope.loading=null;
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.fee_prog_lists=array_data.FEE_PROG_LIST;
					$scope.loading=null;
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.editFeeStructure = function (fee_prog_list,SessionModel,FeeCategoryType){

			$scope.checkAllModel=false;
			$("input[name=selectFeeCategoryRow]").attr("checked",false);

			$scope.errorMsg = null
			$scope.fee_categories=null;
			$scope.sum = 0;
			if (SessionModel == null){
				$scope.errorMsg = "Select Session";
			}else if (FeeCategoryType == null){
				$scope.errorMsg="Select Fee Category";
			}else if (Object.keys(fee_prog_list).length===0){
				$scope.errorMsg = "Select Fee Program list";
				return;
			}
			$scope.fee_program_details = fee_prog_list;
			$scope.SelectedProgramTitle= fee_prog_list.PROGRAM_TITLE;
			$scope.errorMsg = null;
			$scope.loading="Loading data please wait...";
			let data = {fee_prog_list_id:fee_prog_list.FEE_PROG_LIST_ID,session_id:SessionModel,fee_category_type_id:FeeCategoryType};

			$http.post('<?=base_url()?>FeesSetup/get_fee_program_list_fee_structure',data).then(function success(response){
				if (response.status == 206 ){
					$scope.errorMsg= response.data;
					$scope.fee_categories=null;
					$scope.loading=null;
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.fee_categories = array_data.ALL;
					angular.forEach($scope.fee_categories, function(key,item){
					    toNumber = Number(key.FEE_AMOUNT)
					    $scope.sum += toNumber;
					});
					$scope.loading=null;
					$scope.check_fee_update=true;
				}

			},function error(response){
				// console.log(response);
			});
			
			
		}
		
                    
		$scope.saveFeeStructure	= function (session_id,FeeCategoryType){

			$scope.errorMsg = null;

			let fee_prog_list_id = [];
			$("input[name=selectFeeCategoryRow]:checked").each(function(key,value){
				fee_prog_list_id.push(this.value);
			});

			let fee_categories_amount = $scope.fee_categories;

			if (session_id == null){
				$scope.errorMsg = "Select Session";
				return;
			}else if (FeeCategoryType == null){
				$scope.errorMsg = "Select Fee Category Type";
				return;
			}else if($scope.fee_categories == null || Object.keys($scope.fee_categories).length===0){
				$scope.errorMsg = "Category empty can not update fees"
				return;
			}else if ((fee_prog_list_id == null || Object.keys(fee_prog_list_id).length===0) && $scope.check_fee_update === false){
				$scope.errorMsg = "Select Fee Program";
				return;
			}else if ($scope.check_fee_update == true){
				fee_prog_list_id.push($scope.fee_program_details.FEE_PROG_LIST_ID);
			}

			$scope.errorMsg = "Saving Records....";
			let data = {fee_category_type_id:FeeCategoryType,session_id:session_id,fee_prog_list_id:fee_prog_list_id,fee_categories_amount:fee_categories_amount};
			$http.post('<?=base_url()?>FeesSetup/saveFeesStructureHandler',data).then(function success(response){
				if (response.status === 206 ){
					$scope.errorMsg= response.data;
				}
				if (response.status === 200 ){
					$scope.errorMsg= response.data;
					// $scope.getFeeCategoryType();
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.getPart 			= function (program_type_id){

			$scope.errorMsg = null;
			if (program_type_id == null){
				$scope.errorMsg = "Select Program Type";
				return;
			}
			let data = {flag:"proper_channel",PROGRAM_TYPE_ID:program_type_id};
			$scope.parts = null;
			$http.post('<?=base_url()?>AdminApi/getPart',data).then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.parts=array_data;
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getSemester		= function (demerit_id){

			$scope.errorMsg = null;
			let data = {flag:"proper_channel",FEE_DEMERIT_ID:demerit_id};
			$scope.semesters = null;
			$http.post('<?=base_url()?>AdminApi/getSemester',data).then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.semesters=array_data;
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getFeeCategoryType= function (){

			$scope.errorMsg = null;
			let data = {flag:"proper_channel"};
			$scope.fee_category_type = null;
			$http.post('<?=base_url()?>FeesSetup/getFeeCategoryType',data).then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.fee_category_type=array_data;
				}
			},function error(response){
			});
		}
		$scope.getDemerit 		= function (){
			$scope.demerit = null;
			$http.post('<?=base_url()?>AdminApi/getDemerit').then(function success(response){
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.demerit=array_data;
				}

			},function error(response){
				$scope.errorMSG= 'Sorry could not find data';
			});
		}
		$scope.deleteFeesStructure= function (SessionModel,FeeCategoryType){

			let fee_prog_list_id = [];
			$("input[name=selectFeeCategoryRow]:checked").each(function(key,value){
				fee_prog_list_id.push(this.value);
			});

			if (SessionModel == null){
				$scope.errorMsg="Select Session";
				return;
			}else if(FeeCategoryType == null){
				$scope.errorMsg="Select Fee Category Type";
				return;
			}else if(Object.keys(fee_prog_list_id).length===0){
				$scope.errorMsg="Tick Fee Program list"
				return;
			}
			if (confirm("Do you want to delete?") === false) {
				return;
			}

			$scope.errorMsg = "Deleting Records....";
			let data = {fee_category_type_id:FeeCategoryType,session_id:SessionModel,fee_prog_list_id:fee_prog_list_id};
			$http.post('<?=base_url()?>FeesSetup/DeleteFeesStructureHandler',data).then(function success(response){
				if (response.status === 206 ){
					$scope.errorMsg= response.data;
				}
				if (response.status === 200 ){
					$scope.errorMsg= response.data;
					$scope.fee_categories=null;
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.download			= function (SessionModel,FeeCategoryType){

			let fee_prog_list_id = [];
			$("input[name=selectFeeCategoryRow]:checked").each(function(key,value){
				fee_prog_list_id.push(this.value);
			});

			if (SessionModel == null){
				$scope.errorMsg="Select Session";
				return;
			}else if(FeeCategoryType == null){
				$scope.errorMsg="Select Fee Category Type";
				return;
			}else if(Object.keys(fee_prog_list_id).length===0){
				$scope.errorMsg="Tick Fee Program list"
				return;
			}

			$scope.errorMsg = "Getting Records....";
			let data = {fee_category_type_id:FeeCategoryType,session_id:SessionModel,fee_prog_list_id:fee_prog_list_id};
			$http.post('<?=base_url()?>FeesSetup/DownloadFeesStructureHandler',data).then(function success(response){
				if (response.status === 206 ){
					$scope.errorMsg= response.data;
				}
				if (response.status === 200 ){
					$scope.errorMsg = null
					let records = response.data;
					let date = new Date();

					let csvString = '';

					csvString = csvString+"CAMPUS NAME"+",";
					csvString = csvString+"PROGRAM TITLE,"
					csvString = csvString+"SHIFT,"
					csvString = csvString+"PART,"
					csvString = csvString+"SEMESTER,"
					csvString = csvString+"DEMERIT NAME,"
					csvString = csvString+"FEE CATEGORY TYPE,"
					csvString = csvString+"FEE CATEGORY,"
					csvString = csvString+"AMOUNT\n"
					angular.forEach(records,function(value,key) {
						angular.forEach(value,function (value2,key2){
							csvString = csvString +value2['CAMPUS_LOCATION']+",";
							csvString = csvString +value2['PROGRAM_TITLE'].replace(',',' - ')+",";
							csvString = csvString +value2['SHIFT_NAME']+",";
							csvString = csvString +value2['PART_NAME']+",";
							csvString = csvString +value2['SMESTER_NAME']+",";
							csvString = csvString +value2['DEMERIT_NAME']+",";
							csvString = csvString +value2['FEE_CATEGORY_TYPE_TITLE']+",";
							csvString = csvString +value2['CATEGORY_TITLE']+",";
							csvString = csvString +value2['AMOUNT']+"\n";
						})
					});
					var a = $('<a/>', {
						style:'display:none',
						href:'data:application/octet-stream;base64,'+btoa(csvString),
						download:'fee_structure'+date+'.csv'
					}).appendTo('body')
					a[0].click()
					a.remove();
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.getFeeCategory 	= function (){
			$scope.check_fee_update=false;
			$scope.errorMsg = null;
			let data = {flag:"proper_channel"};
			$scope.fee_categories = null;
			$http.post('<?=base_url()?>FeesReports/getFeeCategory',data).then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.fee_categories=array_data;
				}
			},function error(response){
			});
		}
		$scope.addAmount 		= function (fee_category,feeAmount){
			fee_category.FEE_AMOUNT = feeAmount
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
		$scope.FeesImport= function (SessionModel,NewSessionModel){


			if(SessionModel == null){
				$scope.DumpError="Select from session";
				return;
			}else if(NewSessionModel == null){
				$scope.DumpError="Select new session";
				return;
			}
			if (confirm("Do you want to import Fees Structure?") === false){
				return;
			}
			$scope.DumpError = null;
			$scope.DumpError="Importing data please wait...";
			let data = {session_id:SessionModel,new_session_id:NewSessionModel};

			$http.post('<?=base_url()?>FeesSetup/DumpFeesStructureHandler',data).then(function success(response){
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

		$scope.getProgramTypes();
		$scope.getSessions();
		$scope.getShifts();
		$scope.getCampus();
		$scope.getSemester();
		$scope.getDemerit();
		$scope.getFeeCategoryType();
	});
</script>
