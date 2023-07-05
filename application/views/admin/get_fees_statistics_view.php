<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="sparkline10-hd">
		<div class="main-sparkline10-hd text-center bg-warning">
			<h1>Fees Reports</h1>
		</div>
	</div>
    <div ng-app="myApp" ng-controller="formCtrl">
		<div class="form-check">
			<div class='row'>
				
					<input class="form-check-input" type="checkbox" value="" id="showSession" ng-model="showSession">
					<label class="form-check-label" for="showSession">
						Select Session
					</label>
				
				
					<input class="form-check-input" type="checkbox" value="" id="showCampus" ng-model="showCampus">
					<label class="form-check-label" for="showCampus">
						Select Campus
					</label>
				
				
					<input class="form-check-input" type="checkbox" value="" id="showShift" ng-model="showShift">
					<label class="form-check-label" for="showShift">
						Select Shift
					</label>
				
				
					<input class="form-check-input" type="checkbox" value="" id="showProgramType" ng-model="showProgramType">
					<label class="form-check-label" for="showProgramType">
						Select Program Type
					</label>
				
				
					<input class="form-check-input" type="checkbox" value="" id="showProgram" ng-model="showProgram">
					<label class="form-check-label" for="showProgram">
						Select Program
					</label>
				
				
					<input class="form-check-input" type="checkbox" value="" id="showPart" ng-model="showPart">
					<label class="form-check-label" for="showPart">
						Select Part
					</label>
				
				
					<input class="form-check-input" type="checkbox" value="" id="showSemester" ng-model="showSemester">
					<label class="form-check-label" for="showSemester">
						Select Semester
					</label>
				
			</div>
		
		<div class='row'>
			<div class='col-md-8'>
				<div class="col-md-2" ng-show="showSession">
					<label>Session</label>
					<select ng-model="session_id"  class="form-control">
						<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
					</select>
				</div>
				<div class="col-md-2" ng-show="showCampus">
					<label>Campus</label>
					<select ng-model="campus_id" ng-change="getPrograms()" class="form-control">
						<option ng-repeat="campus in campusess" ng-value="{{campus.CAMPUS_ID}}">{{campus.LOCATION}}</option>
					</select>
				</div>
				<div class="col-md-2" ng-show="showShift">
					<label>Shift</label>
					<select ng-model="shift_id" ng-change="getPrograms()" class="form-control">
						<option ng-repeat="shift in shifts" ng-value="{{shift.SHIFT_ID}}">{{shift.SHIFT_NAME}}</option>
					</select>
				</div>
				<div class="col-md-3" ng-show="showProgramType">
					<label>Program Type</label>
					<select ng-model="program_type_id" class="form-control" ng-change="getPrograms(); getParts(ProgramTypesModel)">
						<option ng-repeat="types in programTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
					</select>
				</div>
				<div class="col-md-3" ng-show="showProgram">
					<label>Program</label>
					<select ng-model="prog_list_id" ng-change="getPrograms()" class="form-control">
						<option ng-repeat="program in programs" ng-value="{{program.PROG_LIST_ID}}">{{program.PROGRAM_TITLE}}</option>
					</select>
				</div>
				<div class="col-md-3" ng-show="showPart">
					<label>Class</label>
					<select ng-model="part_id" class="form-control">
						<option ng-repeat="class in parts" ng-value="{{class.PART_ID}}">{{class.NAME}}</option>
					</select>
				</div>
				<div class="col-md-3" ng-show="showSemester">
					<label>Semester</label>
					<select ng-model="semester_id" class="form-control">
						<option ng-repeat="semester in semesters" ng-value="{{semester.SEMESTER_ID}}">{{semester.NAME}}</option>
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
							<button 
								type="button" 
								class="btn btn-custon-rounded-four btn-primary" 
								ng-click="feeReportByCategory(SessionModel,ProgramTypesModel,PartModel);">
								<i class="fa fa-search edu-informatio" aria-hidden="true"></i>
								Fees Reports Category
							</button>
							<button 
								type="button" 
								class="btn btn-custon-rounded-four btn-primary" 
								ng-click="candidateImport();">
								<i class="fa fa-search edu-informatio" aria-hidden="true"></i>
								Import Candidates
							</button>
							<button 
								type="button" 
								class="btn btn-custon-rounded-four btn-primary" 
								ng-click="challanImport();">
								<i class="fa fa-search edu-informatio" aria-hidden="true"></i>
								Import Challan
							</button>
							<button 
								type="button" 
								class="btn btn-custon-rounded-four btn-primary" 
								ng-click="paidChallanImport();">
								<i class="fa fa-search edu-informatio" aria-hidden="true"></i>
								Import Paid Challan
							</button>
						</div>
						<span id="loading">{{loading}}</span>
						<span class="text-danger">{{errorMsg}}</span>
					</div>
				</div>
			</div>
		</div>
		<br/>
		</div>
			<div class="asset-inner">
				<table class="table table-condensed">
					<thead>
					<tr style="font-size: 10pt;text-align: center">    						
						<th>S#</th>
						<th>CHALLAN NO</th>
						<th>INSTITUTE #</th>
						<th>INSTITUTE NAME</th>
					</tr>
					</thead>
					<tbody>
					<tr ng-repeat="fee in fees_report track by $index" style="font-size: 11pt">    						
						<td>{{$index+1}}</td>
						<td>{{fee.CHALLAN_NO}}</td>
						<td>{{fee.FEE_CATEGORY_TYPE_NAME}}</td>
						<td>{{fee.PART_ID}}</td>
					</tr>
					</tbody>
				</table>
			</div>
    </div>
</div>

<script type="text/javascript">
    var app = angular.module('myApp', []);
	app.controller('formCtrl', function($scope,$http,$window) {
		$scope.feeReportByCategory = function (SessionModel,ProgramTypesModel,PartModel){
			$scope.errorMsg = null
			// if (SessionModel == null){
			// 	$scope.errorMsg = "Select Session";
			// 	return;
			// }else if (ProgramTypesModel == null){
			// 	$scope.errorMsg = "Select Program type";
			// 	return;
			// }else if (PartModel == null){
			// 	$scope.errorMsg = "Select Class";
			// 	return;
			// }
			$scope.loading="Loading data please wait...";
			let data = {session_id:$scope.session_id,program_type_id:$scope.program_type_id,part_id:$scope.part_id};
			$scope.fees_report=null;
			window.open('<?=base_url()?>StudentIDCard/get_fees_statistics_data',data, "_blank");
			// $http.post('<?=base_url()?>StudentIDCard/get_fees_statistics_data',data).then(function success(response){
			// 	if (response.status == 206 ){
			// 		$scope.errorMsg= response.data;
			// 		$scope.loading=null;
			// 	}
			// 	if (response.status == 200 ){
			// 		let array_data = response.data;
			// 		$scope.fees_report=array_data;
			// 		$scope.loading=null;
			// 	}
			// },function error(response){
			// 	console.log(response);
			// });
		}
		$scope.candidateImport = function () {
			$http({
				method: 'GET',
				url: '<?=base_url()?>StudentIDCard/candidateImport'
			}).then(function successCallback(response) {
				console.log(response);
			// this callback will be called asynchronously
			// when the response is available
			}, function errorCallback(response) {
				console.log(response);
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			});
		}
		$scope.challanImport = function () {
			$http({
				method: 'GET',
				url: '<?=base_url()?>StudentIDCard/challanImport'
			}).then(function successCallback(response) {
				console.log(response);
			// this callback will be called asynchronously
			// when the response is available
			}, function errorCallback(response) {
				console.log(response);
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			});
		}
		$scope.paidChallanImport = function () {
			$http({
				method: 'GET',
				url: '<?=base_url()?>StudentIDCard/paidChallanImport'
			}).then(function successCallback(response) {
				console.log(response);
			}, function errorCallback(response) {
				console.log(response);
			});
		}

		$scope.getSessions 		= function (){
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
		$scope.getCampus = function () {
				$http({
                method: 'get',
                url: '<?php echo base_url(); ?>AdminApi/getCampuses'
                }).then(function successCallback(response) {
                // Store response data
                $scope.campusess = response.data;
                }); 
		}
		$scope.getShifts = function () {
				$http({
                method: 'get',
                url: '<?php echo base_url(); ?>AdminApi/getShifts'
                }).then(function successCallback(response) {
                // Store response data
                $scope.shifts = response.data;
                }); 
		}
		$scope.getProgramTypes	= function (){
			$scope.programTypes = null;
			$http.post('<?=base_url()?>AdminApi/getProgramTypes').then(function success(response){
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.programTypes=array_data;
				}
			},function error(response){
				$scope.errorMSG= 'Sorry could not find data';
			});
		}
		$scope.getPrograms = function () {
                var Indata = {shift_id:$scope.shift_id, program_type_id:$scope.program_type_id, campus_id:$scope.campus_id};
				$http.post("<?php echo base_url(); ?>AdminApi/getCampusPrograms", Indata).then(function (response) {				
				$scope.programs = response.data;
				},function error(response) { 
				alert("error"); 
				});
		}
		$scope.getParts = function (){
			let data = {program_type_id:$scope.program_type_id};
			$scope.parts = null;
			$http.post('<?=base_url()?>AdminApi/getParts',data).then(function success(response){
				if (response.status == 200 ){
					let array_data = response.data;
					$scope.parts=array_data;
				}
				
			},function error(response){
				$scope.errorMSG= 'Sorry could not find data';
			});
		}
		$scope.getSemester = function (demerit_id) {
			let data = {flag:"proper_channel",FEE_DEMERIT_ID:demerit_id};
			$http.post("<?php echo base_url(); ?>AdminApi/getSemester", data).then(function (response) {
			$scope.semesters = response.data;
			},function (response) { 
			alert("error"); 
		});
		}
		$scope.getSemester();
		$scope.getSessions();
		$scope.getCampus();
		$scope.getShifts();
		$scope.getProgramTypes();
		$scope.getPrograms();
		$scope.getParts();
	});
</script>