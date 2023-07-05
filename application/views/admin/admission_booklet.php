<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>
<style type="text/css">
	.disable_textbox_color{
		background-color: #85c5e5;
		font-weight: bold;
	}
</style>
<!-- dual list Start -->
<div class="dual-list-box-area mg-b-15 container-fluid">
	<div class="sparkline10-list">
		<!--		<form class="container-fluid">-->
		<div class="sparkline10-hd">
			<div class="main-sparkline10-hd text-center bg-warning">
				<h1>Admission Booklet</h1>
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
						<select ng-model="SessionModel" ng-change="getTestTypes(SessionModel);"  class="form-control">
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
						<select ng-model="CampusModel" class="form-control" ng-change="getPrograms(ProgramTypesModel,ShiftsModel,CampusModel); getVacantSeats(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel);getAdmissionList(ProgramTypesModel,ShiftsModel,CampusModel,SessionModel)">
							<option ng-repeat="campus in campuses" ng-value="{{campus.CAMPUS_ID}}">{{campus.NAME}}</option>
						</select>
					</div>

					<div class="col-md-8">
						<label>Subjects</label>
						<select  ng-model="ProgramModel" ng-change="showVacantSeats(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel,Category)" class="form-control">
							<option ng-repeat="program in programs" ng-value="{{program.PROG_ID}}{{program.PROG_LIST_ID}}">{{program.PROGRAM_TITLE}}</option>
						</select>
					</div>
					<div class="col-md-4">
						<br/><br/>
					<div class="button-style-two btn-mg-b-10">
						<button type="button"  class="btn btn-custon-rounded-two btn-primary" ng-click="PrintDocument(ProgramSeats)"><i class="fa fa-print edu-informatio" aria-hidden="true"></i>Define</button>
					</div>
					</div>

						<div class="col-md-3">
						<label>Total Seats</label>
						<input type="text" ng-model="TotalSeats" class="form-control" style="background-color: white" readonly>
					</div>

					<div class="col-md-3">
						<label>Filled Seats</label>
						<input type="text" ng-model="FilledSeats" class="form-control" style="background-color: white" readonly>
					</div>

					<div class="col-md-3">
						<label>Vacant Seats</label>
						<input type="text" ng-model="VacantSeats" class="form-control" style="background-color: white" readonly>
					</div>

					<div class="col-md-3">
						<label>Admission List</label>
						<select ng-model="AdmissionListModel" class="form-control">
							<option ng-repeat="lists in admissionLists" ng-value="lists.ADMISSION_LIST_ID">({{lists.LIST_NO}}) {{lists.LIST_TITLE}}</option>
						</select>
					</div>

					<div class="col-md-12">
						<label>Category</label>
						<select ng-model="Category" ng-change="showVacantSeats(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel,Category)" class="form-control">
							<option ng-repeat="cat in admissionCategory" ng-value="cat.CATEGORY_ID">{{cat.CATEGORY_NAME}}</option>
						</select>
					</div>

					<div class="button-style-two btn-mg-b-10">
						<button type="button"  class="btn btn-custon-rounded-two btn-primary" ng-click="AddProgram(SessionModel,ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,VacantSeats,AdmissionListModel,Category,AppNo,CnicNo,FirstName,FName,LastName,RollNo,FormChoices,Remarks,TestTypesModel)"><i class="fa fa-save edu-informatio" aria-hidden="true"></i> Save </button>

						<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
						<span id="loading"></span>
						<span class="text-danger">{{errorMsg}}</span>
					</div>

				</div>
				<div class='col-md-6'>
					<div class="col-md-12">
						<label>Test Types</label>
						<select ng-model="TestTypesModel" class="form-control">
							<option ng-repeat="test in TestTypes" ng-value="test.TEST_ID">{{test.TEST_NAME}}</option>
						</select>
					</div>
					<div class="col-md-3">
						<label>APP #</label>
						<input type="text" ng-model="AppNo" ng-enter="getApplicant(AppNo,SessionModel,ProgramTypesModel,ShiftsModel,CampusModel,TestTypesModel)" class="form-control">
					</div>
					<div class="col-md-5">
						<label>CNIC #</label>
						<input type="text" ng-model="CnicNo" class="form-control" style="background-color: white" readonly>
					</div>
					<div class="col-md-4">
						<label>CPN</label>
						<input type="text" ng-model="Cpn" class="form-control" style="background-color: white" readonly>
					</div>
					<div class="col-md-6">
						<label>First Name</label>
						<input type="text" ng-model="FirstName" class="form-control" style="background-color: white" readonly>
					</div>

					<div class="col-md-6">
						<label>Fathers Name</label>
						<input type="text" ng-model="FName" class="form-control" style="background-color: white" readonly>
					</div>

					<div class="col-md-6">
						<label>Last Name</label>
						<input type="text" ng-model="LastName" class="form-control" style="background-color: white" readonly>
					</div>

					<div class="col-md-6">
						<label>Roll No</label>
						<input type="text" ng-model="RollNo" class="form-control">
					</div>

					<div class="col-md-12">
						<label>Choices</label>
						<select ng-model="FormChoices" class="form-control">
						<option ng-repeat="Choice in Choices" ng-value="{{Choice.CHOICE_ID}}">{{Choice.PROGRAM_TITLE}} {{Choice.IS_SPECIAL_CHOICE}} ({{Choice.CHOICE_NO}})</option>
						</select>
					</div>

					<div class="col-md-12">
						<label>Remarks</label>
						<input type="text" ng-model="Remarks" class="form-control" placeholder="write remarks here">
					</div>
					<br/>
					<div class="col-md-12">
						<ul class="list-group hover">
							<li class="list-group-item list-group-item-action active" style="text-align: center"><b>Selections</b></li>
							<li ng-repeat="selection in selection_list" class="list-group-item list-group-item-action" style="font-size: 10pt; font-family: 'Times New Roman'">
								<span class="text-danger">{{selection.PROGRAM_TITLE}} - {{selection.SHIFT_NAME}} </span>({{selection.NAME}})
								<P style="font-weight: bold">{{selection.CATEGORY_NAME}}</P>
							</li>
						</ul>
					</div>

				</div>
			</div>
			<br>
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
		$scope.showVacantSeats	= function (program_type_id,shift_id,campus_id,program_id,session_id,category_id){

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
			// console.log(array_data);
			let programSeats = array_data[campus_id][program_id]
			let ProgramCategory = programSeats['CATEGORY_TYPE'];
			$scope.DisplayCategories = ProgramCategory
			$scope.ProgramSeats = programSeats;
			// console.log(programSeats);
			let loop_go = true;
			angular.forEach(ProgramCategory,function (categoryTypes){
				if (loop_go === false){
					return false;
				}
				angular.forEach(categoryTypes['CATEGORIES'],function (categories){
					let CATEGORY_ID=categories['CATEGORY_ID'];
					if (category_id >0  && CATEGORY_ID == category_id){
						$scope.TotalSeats=null;
						$scope.FilledSeats=null;
						$scope.VacantSeats=null;
						$scope.TotalSeats+=parseInt(categories['TOTAL_SEATS']);
						$scope.FilledSeats+=parseInt(categories['FILLED_SEATS']);
						$scope.VacantSeats+=parseInt(categories['VACANT_SEATS']);
						loop_go = false;
						return false;
					}else{
						if (loop_go == false){
							return false;
						}
						$scope.TotalSeats+=parseInt(categories['TOTAL_SEATS']);
						$scope.FilledSeats+=parseInt(categories['FILLED_SEATS']);
						$scope.VacantSeats+=parseInt(categories['VACANT_SEATS']);
					}
				});
			});
		}
		$scope.PrintDocument	= function (programSeats){
			var $popup = $window.open("printVacantSeatReport", "popup", "width=700,height=500,left=20,top=150");
			$popup.Name = programSeats;
			// console.log(programSeats);
		}
		$scope.getAdmissionList	= function (program_type_id,shift_id,campus_id,session_id){

			$scope.errorMsg = null;
			$scope.admissionLists = null;

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
			$http.post('<?=base_url()?>AdminApi/AdmissionListNo',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.errorMsg=null;
					$scope.admissionLists = response.data;
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.getAdmissionCategory	= function (){

			$scope.errorMsg = null;
			$scope.admissionCategory = null;
			let category_type_id=0;

			$scope.errorMsg = "Getting Records....";

			let data = {category_type_id:category_type_id};
			// $scope.programs = null;
			$http.post('<?=base_url()?>AdminApi/getCategory',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.errorMsg=null;
					$scope.admissionCategory = response.data;
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.getTestTypes	= function (session_id){

			$scope.errorMsg = null;
			$scope.TestTypes=null;

			let data = {SESSION_ID:session_id};
			// $scope.programs = null;
			$http.post('<?=base_url()?>AdminApi/getTestType',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.errorMsg=null;
					$scope.TestTypes = response.data;
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.getApplicant		= function (AppNo,session_id,program_type_id,shift_id,campus_id,test_id) {

			$scope.selection_list=null;
			$scope.FirstName=null;
			$scope.FName=null;
			$scope.LastName=null;
			$scope.CnicNo=null;
			$scope.programs=null;
			$scope.Choices=null;
			$scope.errorMsg = null;
			$scope.Cpn=null;
			let msg = null;
			if (session_id == null) {
				msg+= "<p class='text-danger'>Session is required</p>"
			}
			if (program_type_id == null) {
				msg+= "<p class='text-danger'>Program Type is required</p>"
			}
			if (shift_id == null) {
				msg+= "<p class='text-danger'>Shift is required</p>"
			}
			if (campus_id == null) {
				msg+= "<p class='text-danger'>Campus is required</p>"
			}
			if (AppNo == null) {
				msg+= "<p class='text-danger'>APP No is required</p>"
			}
			if (test_id == null) {
				msg+= "<p class='text-danger'>Test Type is required</p>"
			}

			if (msg != null) {
				alertMsg("Required Fields",msg)
				return;
			}

			$scope.errorMsg = "Getting Records....";

			let data = {application_id:AppNo,session_id:session_id,program_type_id:program_type_id,shift_id:shift_id,campus_id:campus_id,test_id,test_id};
			// $scope.programs = null;
			$http.post('<?=base_url()?>SelectionList/getApplicantDataForBookletAdmission',data).then(function success(response){
				if (response.status == 204 ){
					$scope.errorMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.errorMsg=null;
					let users_reg = response.data['users_reg'];
					let selection_list = response.data['selection_list'];
					$scope.Cpn= response.data['cpn'];
					$scope.selection_list = selection_list;
					$scope.FirstName=users_reg['FIRST_NAME'];
					$scope.FName=users_reg['FNAME'];
					$scope.LastName=users_reg['LAST_NAME'];
					$scope.CnicNo=users_reg['CNIC_NO'];

					let pre_req = response.data['pre_req'];
					$scope.Choices = response.data['application_choices'];
					// console.log(response.data)

					let new_array_program = [];
					angular.forEach(pre_req,function (pre_req_value){
						angular.forEach(pre_req_value,function (req_value){
							new_array_program.push(req_value)

						});
					});

					$scope.programs = new_array_program;
					// console.log(selection_list);
				}
			},function error(response){
				// console.log(response);
			});
		}
		$scope.AddProgram		= function (session_id,program_type_id,shift_id,campus_id,program_id,VacantSeats,AdmissionListModel,Category,AppNo,CnicNo,FirstName,FName,LastName,RollNo,FormChoices,Remarks,test_id) {

			$scope.errorMsg = null;
			let msg = "";
			if (session_id == null) {
				msg+= "<p class='text-danger'>Session is required</p>"
			}
			if (program_type_id == null) {
				msg+= "<p class='text-danger'>Program Type is required</p>"
			}
			if (shift_id == null) {
				msg+= "<p class='text-danger'>Shift is required</p>"
			}
			if (campus_id == null) {
				msg+= "<p class='text-danger'>Campus is required</p>"
			}
			if (program_id == null) {
				msg+= "<p class='text-danger'>Subject is required</p>"
			}
			if (VacantSeats == null) {
				msg+= "<p class='text-danger'>Vacant Seat is required</p>"
			}
			if (AdmissionListModel == null) {
				msg+= "<p class='text-danger'>Admission List is required</p>"
			}
			if (Category == null) {
				msg+= "<p class='text-danger'>Category is required</p>"
			}
			if (AppNo == null) {
				msg+= "<p class='text-danger'>APP No is required</p>"
			}
			if (CnicNo == null) {
				msg+= "<p class='text-danger'>Cnic No is required</p>"
			}
			if (FormChoices == null) {
				msg+= "<p class='text-danger'>Form Choices are required</p>"
			}
			if (test_id == null) {
				msg+= "<p class='text-danger'>Test Type is required</p>"
			}


			if (msg != "") {
				alertMsg("Required Fields",msg)
				return;
			}
			if (RollNo==null) RollNo="";
			if (Remarks==null) Remarks="";

			if (confirm("Do you want to save?")== false){
				return;
			}
			$scope.errorMsg = "Processing....";

			let data = {application_id:AppNo,
						session_id:session_id,
						program_type_id:program_type_id,
						shift_id:shift_id,
						campus_id:campus_id,
						program_id:program_id,
						VacantSeats:VacantSeats,
						AdmissionListModel:AdmissionListModel,
						Category:Category,
						CnicNo:CnicNo,
						RollNo:RollNo,
						FormChoices:FormChoices,
						Remarks:Remarks,
						test_id:test_id
						};
			// $scope.programs = null;
			$http.post('<?=base_url()?>SelectionList/SaveApplicantDataForBookletAdmission',data).then(function success(response){
				// console.log(response);
				if (response.status == 201 ){
					$scope.errorMsg= response.data;
				}
				if (response.status == 200 ){
					alertMsg("Server Response Message",response.data)
					$scope.errorMsg=null;
					$scope.getApplicant(AppNo,session_id,program_type_id,shift_id,campus_id,test_id)
				}
			},function error(response){
				// console.log(response);
			});
		}

		$scope.getProgramTypes();
		$scope.getSessions();
		$scope.getShifts();
		$scope.getCampus();
		$scope.getAdmissionCategory();

	});

	app.directive('ngEnter', function () { //a directive to 'enter key press' in elements with the "ng-enter" attribute

				return function (scope, element, attrs) {

					element.bind("keydown keypress", function (event) {
						if (event.which === 13) {
							scope.$apply(function () {
								scope.$eval(attrs.ngEnter);
							});

							event.preventDefault();
						}
					});
				};
			})
</script>
