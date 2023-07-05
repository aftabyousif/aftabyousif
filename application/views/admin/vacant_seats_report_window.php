<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>
<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Vacant Seats Reporting</h1>
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
					<label>Program Type</label>
					<select ng-model="ProgramTypesModel" class="form-control">
						<option ng-repeat="types in ProgramTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
					</select>
				</div>

				<div class="col-md-4">
					<label>Session</label>
					<select ng-model="SessionModel"  class="form-control">
						<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
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
					<select ng-model="CampusModel" class="form-control" ng-change="getPrograms(ProgramTypesModel,ShiftsModel,CampusModel); getVacantSeats(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel)">
						<option ng-repeat="campus in campuses" ng-value="{{campus.CAMPUS_ID}}">{{campus.NAME}}</option>
					</select>
				</div>

				<div class="col-md-3">
					<label>Total Seats</label>
					<input type="text" ng-model="TotalSeats" class="form-control">
				</div>

				<div class="col-md-3">
					<label>Filled Seats</label>
					<input type="text" ng-model="FilledSeats" class="form-control">
				</div>

				<div class="col-md-3">
					<label>Vacant Seats</label>
					<input type="text" ng-model="VacantSeats" class="form-control">
				</div>
			</div>
			<div class='col-md-6'>
				<label>Program List</label>
				<select id="program_id"  ng-model="ProgramModel" ng-change="showVacantSeats(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel)" class="form-control" >
					<option ng-repeat="program in programs" ng-value="{{program.PROG_ID}}">{{program.PROGRAM_TITLE}}</option>
				</select>

			</div>
		</div>
		<br>

		<div class="button-style-two btn-mg-b-10">
			<button type="button"  class="btn btn-custon-rounded-two btn-primary" ng-click="PrintDocument(ProgramSeats)"><i class="fa fa-print edu-informatio" aria-hidden="true"></i> Print / Download Report</button>
			<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
			<span id="loading"></span>
			<span class="text-danger">{{errorMsg}}</span>
		</div>
			<div class="asset-inner">
				<table class="table table-hover table-condesed">
					<thead>
					<tr style="font-size: 10pt;text-align: center">
						<th>Category</th>
						<th>Total Seats</th>
						<th>Fill Seats</th>
						<th>Vacant Seat</th>
					</thead>
					<tbody ng-repeat="x in DisplayCategories  track by $index">
					<tr style="font-size: 10pt;text-align: center">
						<th colspan="4" class="bg-info" style="text-align: center">{{x.CATEGORY_TYPE_NAME}}</th>
					</tr>
					<tr ng-repeat="z in x.CATEGORIES  track by $index" style="font-size: 10pt">
						<td>{{z.CATEGORY_NAME}}</td>
						<td>{{z.TOTAL_SEATS}}</td>
						<td>{{z.FILLED_SEATS}}</td>
						<td>{{z.VACANT_SEATS}}</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script type="text/javascript">
    
	var app = angular.module('myApp', []);
	app.controller('formCtrl', function($scope,$http,$window) {
		$scope.fetchedVacantSeats = null;
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
						$('#program_id').select2();
					// console.log(array_data);
				}

			},function error(response){
				// console.log(response);
			});
		}
		$scope.getVacantSeats	= function (program_type_id,shift_id,campus_id,program_id,session_id){

			$scope.errorMsg = null;
			$scope.TotalSeats = null;
			$scope.FilledSeats = null;
			$scope.VacantSeats = null;
			$scope.DisplayCategories = null;
			$scope.ProgramSeats = null;

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
			}

			$scope.errorMsg = "Getting Records....";

			let data = {program_type_id:program_type_id,shift_id:shift_id,campus_id:campus_id,session_id:session_id};
			// $scope.programs = null;
			$http.post('<?=base_url()?>Selection_list_report/vacantSeatsData',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.errorMsg=null;
					let array_data = response.data;
					$scope.fetchedVacantSeats = array_data
					/*
					let programSeats = array_data[campus_id][program_id]
					let ProgramCategory = programSeats['CATEGORY_TYPE'];
					$scope.DisplayCategories = ProgramCategory
					$scope.ProgramSeats = programSeats;
					// console.log(programSeats);
							angular.forEach(ProgramCategory,function (categoryTypes){
							angular.forEach(categoryTypes['CATEGORIES'],function (categories){
						$scope.TotalSeats+=parseInt(categories['TOTAL_SEATS']);
						$scope.FilledSeats+=parseInt(categories['FILLED_SEATS']);
						$scope.VacantSeats+=parseInt(categories['VACANT_SEATS']);
							console.log(categories);
						});
						});

					 */
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.showVacantSeats	= function (program_type_id,shift_id,campus_id,program_id,session_id){

			$scope.errorMsg = null;
			$scope.TotalSeats = null;
			$scope.FilledSeats = null;
			$scope.VacantSeats = null;
			$scope.DisplayCategories = null;
			$scope.ProgramSeats = null;

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
			}

			$scope.errorMsg = "Getting Records....";

					$scope.errorMsg=null;
					let array_data = $scope.fetchedVacantSeats;
					console.log(array_data);
					let programSeats = array_data[campus_id][program_id]
					let ProgramCategory = programSeats['CATEGORY_TYPE'];
					$scope.DisplayCategories = ProgramCategory
					$scope.ProgramSeats = programSeats;
					// console.log(programSeats);
					angular.forEach(ProgramCategory,function (categoryTypes){
						angular.forEach(categoryTypes['CATEGORIES'],function (categories){
							$scope.TotalSeats+=parseInt(categories['TOTAL_SEATS']);
							$scope.FilledSeats+=parseInt(categories['FILLED_SEATS']);
							$scope.VacantSeats+=parseInt(categories['VACANT_SEATS']);
							console.log(categories);
						});
					});
		}
		$scope.PrintDocument	= function (programSeats){
			var $popup = $window.open("printVacantSeatReport", "popup", "width=700,height=500,left=20,top=150");
			$popup.Name = programSeats;
		//	 console.log(programSeats);
		}

		$scope.getProgramTypes();
		$scope.getSessions();
		$scope.getShifts();
		$scope.getCampus();
	});
</script>
