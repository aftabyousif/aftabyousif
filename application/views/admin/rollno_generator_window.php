<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>
<!-- dual list Start -->
<div id="min-height">
<div class="dual-list-box-area  container-fluid">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Roll No Generator</h1>
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

		<div ng-app="myApp" ng-controller="formCtrl">
			<div class='row'>
				<div class='col-md-6'>

					<div class="col-md-4">
						<label>Session</label>
						<select ng-model="SessionModel"  class="form-control">
							<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
						</select>
					</div>

					<div class="col-md-4">
						<label>Program Type</label>
						<select ng-model="ProgramTypesModel" class="form-control">
							<option ng-repeat="types in ProgramTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
						</select>
					</div>



					<div class="col-md-4">
						<label>Shifts</label>
						<select ng-model="ShiftsModel" class="form-control">
							<option ng-repeat="shift in shifts" ng-value="{{shift.SHIFT_ID}}">{{shift.SHIFT_NAME}}</option>

						</select>
					</div>

					<div class="col-md-12">
						<label>Campus</label>
						<select ng-model="CampusModel" class="form-control" ng-change="getPrograms(ProgramTypesModel,ShiftsModel,CampusModel);">
							<option ng-repeat="campus in campuses" ng-value="{{campus.CAMPUS_ID}}">{{campus.NAME}}</option>
						</select>
					</div>

					<div class="col-md-12 bg-warning">
						<label>Roll No From: </label>
					<div class="input-group">
						<input type="radio" ng-model="rollNoFlag" value="new" name="roll_no_option"> &nbsp; <span>New</span> &nbsp;&nbsp;
						<input type="radio" ng-model="rollNoFlag" value="previous" name="roll_no_option">&nbsp;&nbsp; <span>Continue with Previous</span>
					</div>
					</div>

				</div>
				<div class='col-md-6'>
					<label>Program List</label>
					<span class="text-danger" ng-model="pNoReportMsg"></span>
					<select  ng-model="ProgramModel" ng-change="showStartEndRollNos(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel)" class="form-control" style='height:200px' multiple>
						<option ng-repeat="program in programs" ng-value="{{program.PROG_ID}}">{{program.PROGRAM_TITLE}}</option>
					</select>

				</div>
			</div>
			<br>

			<div class="button-style-two btn-mg-b-10">
				<button type="button"  class="btn btn-custon-rounded-two btn-primary" ng-click="generateRollNos(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel,rollNoFlag)"><i class="fa fa-save" aria-hidden="true"></i> Generate Roll No</button>
				<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
				<span id="loading"></span>
				<span class="text-danger">{{errorMsg}}</span>
			</div>
			<div class="asset-inner">
				<table class="table table-hover table-condesed">
					<thead>
					<tr style="font-size: 10pt;text-align: center">
						<th>Program</th>
						<th>Start</th>
						<th>End</th>
					</thead>
					<tbody>
					<tr ng-repeat="z in getOldRollNos  track by $index" style="font-size: 10pt">
						<td>{{z.PROGRAM_TITLE}}</td>
						<td>{{z.START_ROLL_NO}}</td>
						<td>{{z.END_ROLL_NO}}</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>-->
<script type="text/javascript">

	var app = angular.module('myApp', []);
	app.controller('formCtrl', function($scope,$http,$window) {
		$scope.getOldRollNos = null;
		//$scope.isyes = true;
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
		$scope.showStartEndRollNos= function (program_type_id,shift_id,campus_id,program_id,session_id){

			$scope.pNoReportMsg = null;
			$scope.TotalSeats = null;
			$scope.FilledSeats = null;
			$scope.VacantSeats = null;
			$scope.DisplayCategories = null;
			$scope.ProgramSeats = null;

			if (program_type_id == null){
				$scope.pNoReportMsg = "Select Program Type";
				return;
			}else if (shift_id == null){
				$scope.pNoReportMsg = "Select Shift";
				return;
			}else if (campus_id == null){
				$scope.pNoReportMsg = "Select Campus";
				return;
			}else if (session_id == null){
				$scope.pNoReportMsg = "Select session";
				return;
			}else if (program_id == null){
				$scope.pNoReportMsg = "Select Program";
				return;
			}

			// $scope.errorMsg = "Getting Records....";
			$scope.program_id = angular.toJson(program_id)
			// $scope.errorMsg=null;
			let data = {program_type_id:program_type_id,shift_id:shift_id,campus_id:campus_id,session_id:session_id,program_ids:program_id};
			$http.post('<?=base_url()?>RollNo/getViewStartEndRollNos',data).then(function success(response){
				if (response.status == 204 ){
					$scope.pNoReportMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.pNoReportMsg=null;
					let array_data = response.data;
					$scope.getOldRollNos = array_data
				}
			},function error(response){
				// console.log(response);
			});

		}
		$scope.generateRollNos= function (program_type_id,shift_id,campus_id,program_id,session_id,rollNoFlag){

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
			}else if (rollNoFlag == null){
				$scope.errorMsg = "Select Roll No From";
				return;
			}

			// $scope.errorMsg = "Getting Records....";
			$scope.program_id = angular.toJson(program_id)
			// $scope.errorMsg=null;
			let data = {program_type_id:program_type_id,shift_id:shift_id,campus_id:campus_id,session_id:session_id,program_ids:program_id,rollNoFlag:rollNoFlag};
			$http.post('<?=base_url()?>RollNo/generateRollNos',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= response.data;
				}
				if (response.status == 200 ){
					$scope.errorMsg = response.data
					$scope.showStartEndRollNos(program_type_id,shift_id,campus_id,program_id,session_id)
				}
			},function error(response){
				// console.log(response);
			});

		}

		$scope.getProgramTypes();
		$scope.getSessions();
		$scope.getShifts();
		$scope.getCampus();
	});
</script>
