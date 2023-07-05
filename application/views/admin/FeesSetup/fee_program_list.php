<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>
<!-- dual list Start -->
<div id="min-height"">
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Fee Program List</h1>
			</div>
		</div>
		<div ng-app="myApp" ng-controller="formCtrl">
			<div class='row'>
				<div class='col-md-6'>
					<div class="col-md-12">
						<label>Campus</label>
						<select ng-model="CampusModel" class="form-control">
							<option ng-repeat="campus in campuses" ng-value="{{campus.CAMPUS_ID}}">{{campus.NAME}}</option>
						</select>
					</div>

					<div class="col-md-4">
						<label>Program Type</label>
						<select ng-model="ProgramTypesModel" class="form-control" ng-change="getPart(ProgramTypesModel)">
							<option ng-repeat="types in ProgramTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
						</select>
					</div>
					<div class="col-md-4">
						<label>Shifts</label>
						<select ng-model="ShiftsModel" class="form-control" >
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
						<label>Demerit</label>
						<select ng-model="DemeritModel" class="form-control" ng-change="getSemester(DemeritModel)">
							<option ng-repeat="demeri in demerit" ng-value="{{demeri.FEE_DEMERIT_ID }}">{{demeri.NAME}}</option>
						</select>
					</div>

					<div class="col-md-4">
						<label>Semester</label>
						<select ng-model="SemesterModel" class="form-control" >
<!--							<option value="">Optional</option>-->
							<option ng-repeat="semester in semesters" ng-value="{{semester.SEMESTER_ID}}">{{semester.ORDINAL_NUM}}</option>
						</select>
					</div>

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
					<a href="javascript:void(0)" class="button btn-social basic-ele-mg-b-10 facebook span-left" ng-click="getRecord(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel)"> <span><i class="fa fa-search"></i></span> Search Record </a>
					<a href="javascript:void(0)" class="button btn-social basic-ele-mg-b-10 twitter span-left" ng-click="saveRecord(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel,ProgramModel)"> <span><i class="fa fa-save"></i></span> Add Record </a>
					<a href="javascript:void(0)" class="button btn-social basic-ele-mg-b-10 googleplus span-left" ng-click="deleteRecord()"> <span><i class="fa fa-remove"></i></span> Delete Records </a>
					<a href="javascript:void(0)" class="button btn-social basic-ele-mg-b-10 linkedin span-left" ng-click="download(recordFullArray)"> <span><i class="fa fa-file-excel-o"></i></span> CSV Report </a>
					<span id="loading"></span>
					<span class="text-danger">{{errorMsg}}</span>
				</div>
			</div>

			<br>
			<div class="asset-inner">
				<table class="table table-condensed">
					<thead>
					<tr style="font-size: 10pt;text-align: center">
						<th>S.NO</th>
						<th><input type="checkbox" ng-model="checkAll" id="checkAll" name="checkAll" onchange="checkAll()"></th>
						<th>FEE PROG LIST ID</th>
						<th>FEE PROG ID</th>
						<th>PROGRAM TITLE</th>
					</thead>
					<tbody>
					<tr ng-repeat="fee_prog_list in fee_prog_lists track by $index" style="font-size: 10pt">
						<td>{{$index+1}}</td>
						<td><input type="checkbox" ng-model="selectFeeList" id="selectFeeList" name="selectFeeList" value="{{fee_prog_list.FEE_PROG_LIST_ID}}"></td>
						<td>{{fee_prog_list.FEE_PROG_LIST_ID}}</td>
						<td>{{fee_prog_list.PROG_LIST_ID}}</td>
						<td>{{fee_prog_list.PROGRAM_TITLE}}</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">

	function checkAll(){

		if ($("input[name=checkAll]").prop("checked")==true){
			$("input[name=selectFeeList]").prop("checked","checked");
		}else{
			$("input[name=selectFeeList]").attr("checked",false);
		}

	}
	var app = angular.module('myApp', []);
	app.controller('formCtrl', function($scope,$http,$window) {
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

		$scope.getDemerit= function (){
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
		$scope.getShifts = function (){

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
		$scope.getCampus = function (){

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
		$scope.getRecord = function (ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel){

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
			}else if (PartModel == null){
				$scope.errorMsg = "Select Part";
				return;
			}else if (DemeritModel == null){
				$scope.errorMsg = "Select Demerit";
				return;
			}else if (SemesterModel == null){
				$scope.errorMsg = "Select Semester";
				return;
			}

			$scope.errorMsg = null;

			let data = {campus_id:CampusModel,program_type_id:ProgramTypesModel,shift_id:ShiftsModel,part_id:PartModel,demerit_id:DemeritModel,semester_id:SemesterModel};
			$scope.programs = null;
			$scope.fee_prog_lists=null;
			$http.post('<?=base_url()?>FeesSetup/get_fee_program_list_handler',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.fee_prog_lists=array_data.FEE_PROG_LIST;
					$scope.programs=array_data.PROGRAM;
				}

			},function error(response){
				// console.log(response);
			});
		}

		$scope.saveRecord = function (ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel,ProgramModel){

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
			}else if (PartModel == null){
				$scope.errorMsg = "Select Part";
				return;
			}else if (DemeritModel == null){
				$scope.errorMsg = "Select Demerit";
				return;
			}else if (SemesterModel == null){
				$scope.errorMsg = "Select Semester";
				return;
			}else if (ProgramModel == null){
				$scope.errorMsg = "Select Programs";
				return;
			}

			$scope.errorMsg = null;

			let data = {campus_id:CampusModel,program_type_id:ProgramTypesModel,shift_id:ShiftsModel,part_id:PartModel,demerit_id:DemeritModel,semester_id:SemesterModel,prog_ids:ProgramModel};
			$http.post('<?=base_url()?>FeesSetup/save_fee_program_list_handler',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.errorMsg= array_data;

					$scope.getRecord(ProgramTypesModel,ShiftsModel,CampusModel,PartModel,SemesterModel,DemeritModel);
				}

			},function error(response){
				// console.log(response);
			});
		}

		$scope.deleteRecord = function (){

			if (confirm("are you sure? do you want to delete?")=== false){
				return;
			}

			let fee_prog_list_ids = [];
			$("input[name=selectFeeList]:checked").each(function(key,value){
				fee_prog_list_ids.push(this.value);
			});
			if (Object.keys(fee_prog_list_ids).length ===0){
				$scope.errorMsg = "Please select record";
				return;
			}

			$scope.errorMsg = null;

			let data = {fee_prog_list_ids:fee_prog_list_ids};
			$http.post('<?=base_url()?>FeesSetup/delete_fee_program_list_handler',data).then(function success(response){
				if (response.status == 206 ){
					$scope.errorMsg= response.data;
				}
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.errorMsg= array_data;
					$scope.getRecord($scope.ProgramTypesModel,$scope.ShiftsModel,$scope.CampusModel,$scope.PartModel,$scope.SemesterModel,$scope.DemeritModel);
				}
			},function error(response){
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

		$scope.getDemerit();
		$scope.getProgramTypes();
		$scope.getShifts();
		$scope.getCampus();
		$scope.getSemester();
	});
</script>
