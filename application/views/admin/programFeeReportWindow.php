<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>
<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid" id="min-height">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Department Fees Reporting</h1>
			</div>
		</div>
		<div ng-app="myApp" ng-controller="formCtrl">
			<div class='row'>
				<div class='col-md-6'>
					<div class="col-md-12">
						<label>Campus</label>
						<select ng-model="CampusModel" class="form-control" ng-change="getPrograms(ProgramTypesModel,ShiftsModel,CampusModel);">
							<option ng-repeat="campus in campuses" ng-value="{{campus.CAMPUS_ID}}">{{campus.NAME}}</option>
						</select>
					</div>

					<div class="col-md-4">
						<label>Session</label>
						<select ng-model="SessionModel"  class="form-control">
							<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
						</select>
					</div>
					<div class="col-md-4">
						<label>Program Type</label>
						<select ng-model="ProgramTypesModel" class="form-control" ng-change="getPrograms(ProgramTypesModel,ShiftsModel,CampusModel); getPart(ProgramTypesModel)">
							<option ng-repeat="types in ProgramTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
						</select>
					</div>
					<div class="col-md-4">
						<label>Shifts</label>
						<select ng-model="ShiftsModel" class="form-control" ng-change="getPrograms(ProgramTypesModel,ShiftsModel,CampusModel);">
							<option ng-repeat="shift in shifts" ng-value="{{shift.SHIFT_ID}}">{{shift.SHIFT_NAME}}</option>
						</select>
					</div>
					<div class="col-md-4">
						<label>Part</label>
						<select ng-model="PartModel" class="form-control">
							<option ng-repeat="part in parts" ng-value="{{part.PART_ID}}">{{part.NAME}} {{part.REMARKS}}</option>
						</select>
					</div>
					<div class="col-md-4">
						<label>Semester</label>
						<select ng-model="SemesterModel" class="form-control">
							<option value="">Optional</option>
							<option ng-repeat="semester in semesters" ng-value="{{semester.SEMESTER_ID}}">{{semester.ORDINAL_NUM}}</option>
						</select>
					</div>

<!--					<div class="col-md-12">-->
<!--						<label>Fee Category</label>-->
<!--						<select ng-model="FeeCategoryModel" class="form-control" multiple size="4">-->
<!--							<option ng-repeat="fee_category in fee_categories" ng-value="{{fee_category.FEE_CATEGORY_ID}}">{{fee_category.CATEGORY_TITLE}}</option>-->
<!--						</select>-->
<!--					</div>-->

				</div>
				<div class='col-md-6'>
					<label>Program List</label>
					<select  ng-model="ProgramModel" class="form-control" style='height:200px' multiple>
						<option ng-repeat="program in programs" ng-value="{{program.PROG_ID}}">{{program.PROGRAM_TITLE}}</option>
					</select>

				</div>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="login-social-inner">
					<a href="#" class="button btn-social basic-ele-mg-b-10 facebook span-left" ng-click="getFeesReport(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel,PartModel,SemesterModel,FeeCategoryModel)"> <span><i class="fa fa-search"></i></span> Search Report </a>
					<a href="#" class="button btn-social basic-ele-mg-b-10 twitter span-left" ng-click="PrintDocument(recordFullArray)"> <span><i class="fa fa-print"></i></span> Print Report </a>
					<a href="#" class="button btn-social basic-ele-mg-b-10 googleplus span-left" ng-click="download(recordFullArray)"> <span><i class="fa fa-file-excel-o"></i></span> CSV Report </a>
					<a href="#" class="button btn-social basic-ele-mg-b-10 linkedin span-left" ng-click="backNewSearch()"> <span><i class="fa fa-search-plus"></i></span> Back To New Search </a>
					<span id="loading"></span>
					<span class="text-danger">{{errorMsg}}</span>
				</div>
			</div>

			<br>
<!--
			<div class="button-style-two btn-mg-b-10">
				<button type="button"  class="btn btn-custon-rounded-two btn-primary" ng-click="getFeesReport(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel,PartModel,SemesterModel,FeeCategoryModel)">Show Report</button>
				<button type="button"  class="btn btn-custon-rounded-two btn-primary" ng-click="PrintDocument(recordFullArray)"><i class="fa fa-print edu-informatio" aria-hidden="true"></i> Print / Download Report</button>
				<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
			</div>
	-->
			<div class="asset-inner">
				<table class="table table-condensed">
					<thead>
					<tr style="font-size: 10pt;text-align: center">
						<th>Program Title</th>
						<th>Category</th>
						<th>Total Students</th>
						<th>Challan Amount</th>
						<th>Paid Amount</th>
						<th>Balance</th>
					</thead>
					<tbody ng-repeat="z in records  track by $index">
					<tr ng-repeat="x in z.DATA track by $index" style="font-size: 10pt">
						<td>{{x.PROGRAM_TITLE}}</td>
						<td>{{x.CATEGORY_NAME}}</td>
						<td>{{x.TOTAL_CANDIDATES}}</td>
						<td>{{x.CHALLAN_AMOUNT}}</td>
						<td>{{x.PAID_AMOUNT}}</td>
						<td>{{x.BALANCE}}</td>
					</tr>
					<tr CLASS="bg-primary" style="font-size: 12pt: color:white; font-weight: bold">
						<td colspan="2" style="text-align: center">Grand Total</td>
						<td>{{z.SUM.SUM_TOTAL_CANDIDATES}}</td>
						<td>{{z.SUM.SUM_CHALLAN_AMOUNT}}</td>
						<td>{{z.SUM.SUM_PAID_AMOUNT}}</td>
						<td>{{z.SUM.SUM_BALANCE}}</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>-->
<script type="text/javascript">

	var app = angular.module('myApp', []);
	app.controller('formCtrl', function($scope,$http,$window) {
		$scope.getProgramTypes 	= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.ProgramTypes = null;
			$http.post('<?=base_url()?>AdminApi/getProgramTypes').then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.ProgramTypes=array_data;
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getSessions 		= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.sessions = null;
			$http.post('<?=base_url()?>AdminApi/getSessions').then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.sessions=array_data;
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getShifts 		= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.shifts = null;
			$http.post('<?=base_url()?>AdminApi/getShifts').then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.shifts=array_data;
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getCampus 		= function (){

			// let data = {search_value:search_value,search_by:search_by};
			$scope.campuses = null;
			$http.post('<?=base_url()?>AdminApi/getCampuses').then(function success(response){
				if (response.status == 204 ){
					// $scope.errorMSG= 'Sorry could not find data';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.campuses=array_data;
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getPrograms 		= function (program_type_id,shift_id,campus_id){

			$scope.errorMsg = null
			if (program_type_id == null){
				$scope.errorMsg = "Select Program Type";
				return;
			}else if (shift_id == null){
				$scope.errorMsg = "Select Shift";
				return;
			}else if (campus_id == null){
				$scope.errorMsg = "Select Campus";
				return;
			}

			$scope.errorMsg = null;

			let data = {program_type_id:program_type_id,shift_id:shift_id,campus_id:campus_id};
			$scope.programs = null;
			$http.post('<?=base_url()?>AdminApi/getCampusPrograms',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.programs=array_data;
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getFeesReport	= function (program_type_id,shift_id,campus_id,program_id,session_id,part_id,semester_id){

			$scope.records = null;
			$scope.recordFullArray = null;
			$scope.errorMsg = null;
			if (program_type_id == null){
				$scope.errorMsg = "Select Program Type";
				return;
			}else if (shift_id == null){
				$scope.errorMsg = "Select Shift";
				return;
			}else if (campus_id == null){
				$scope.errorMsg = "Select Campus";
				return;
			}else if (session_id == null){
				$scope.errorMsg = "Select session";
				return;
			}else if (program_id == null){
				$scope.errorMsg = "Select Program";
				return;
			}else if (part_id == null){
				$scope.errorMsg = "Select Part";
				return;
			}

			$scope.errorMsg = "Getting Records....";
			let data = {program_type_id:program_type_id,shift_id:shift_id,campus_id:campus_id,session_id:session_id,program_id:program_id,part_id:part_id,semester_id:semester_id};
			$http.post('<?=base_url()?>FeesReports/getProgramFeesReport',data).then(function success(response){
				// console.log(response.data);
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.errorMsg = null;
					let array_data = response.data;
					$scope.records=array_data['RECORD'];
					$scope.recordFullArray = array_data;
					// console.log(array_data['RECORD']);
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.PrintDocument	= function (records){
			var $popup = $window.open("printProgramFeeReport", "popup", "width=1100,height=800,left=10,top=50");
			$popup.Name = records;
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
		$scope.getSemester		= function (){

			$scope.errorMsg = null;
			let data = {flag:"proper_channel"};
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
		$scope.getFeeCategory	= function (){

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
		$scope.download	= function (records){

			let campus_name = records['CAMPUS_NAME'];
			campus_name = campus_name.replace(',','-');
			let part = records['PART']
			let shift_name = records['SHIFT']
			let year = records['SESSION_CODE']
			let degree = records['DEGREE_TITLE']
			let date = new Date();

			let csvString = '';

				csvString = csvString+"\n"
				csvString = csvString+"CAMPUS"+",";
				csvString = csvString+""+campus_name;
				csvString = csvString+","

				csvString = csvString+"DEGREE TITLE"+",";
				csvString = csvString+""+degree+",";

				csvString = csvString+"YEAR"+",";
				csvString = csvString+""+year+",\n";

				csvString = csvString+"PART"+",";
				csvString = csvString+""+part+",";
				csvString = csvString+"SHIFT"+",";
				csvString = csvString+""+shift_name+",";
				csvString = csvString+"Report Date"+",";
				csvString = csvString+""+date;

				csvString = csvString+"\n";
				csvString = csvString+"\n"

				csvString = csvString+"PROGRAM TITLE,CATEGORY,TOTAL STUDENTS,CHALLAN AMOUNT,PAID AMOUNT,BALANCE\n";
			angular.forEach(records['RECORD'],function(value,key2) {
				let data = value['DATA'];
				angular.forEach(data, function (value2, key2) {

					csvString = csvString + value2['PROGRAM_TITLE'] + ",";
					csvString = csvString + value2['CATEGORY_NAME'] + ",";
					csvString = csvString + value2['TOTAL_CANDIDATES'] + ",";
					csvString = csvString + value2['CHALLAN_AMOUNT'] + ",";
					csvString = csvString + value2['PAID_AMOUNT'] + ",";
					csvString = csvString + value2['BALANCE'] + ",\n";
				});
				let sum = value['SUM']
				csvString = csvString +",Grand Total,";
				csvString = csvString +sum['SUM_TOTAL_CANDIDATES']+",";
				csvString = csvString +sum['SUM_CHALLAN_AMOUNT']+",";
				csvString = csvString +sum['SUM_PAID_AMOUNT']+",";
				csvString = csvString +sum['SUM_BALANCE']+",\n";

			});
			var a = $('<a/>', {
				style:'display:none',
				href:'data:application/octet-stream;base64,'+btoa(csvString),
				download:'deptCollectionReport_'+date+'.csv'
			}).appendTo('body')
			a[0].click()
			a.remove();
		}
		$scope.backNewSearch = function (){

		}

		$scope.getProgramTypes();
		$scope.getSessions();
		$scope.getShifts();
		$scope.getCampus();
		$scope.getSemester();
		// $scope.getFeeCategory();
	});
</script>
