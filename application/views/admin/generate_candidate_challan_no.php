<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<style type="text/css">
		body
		{
			font-family: Arial;
			font-size: 9pt;
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
		.daidh {
			width: 12.499999995%;
			flex: 0 0 12.499%;
			max-width: 12.499%;
		}
		.adhai {
			width: 14.499999995%;
			flex: 0 0 14.499%;
			max-width: 14.499%;
		}
		.active-bg {
			background-color: #FF6064;
		}
		.fee-structure-bg {
			background-color: #FF6064;
		}
	</style>
	<script type="text/javascript">
		var app = angular.module('myApp', []);
		app.controller('formCtrl', ['$scope','$window','$http', function($scope,$window,$http) {
			$scope.search = function(generateby) {
				$scope.IsVisible = false;
				if (generateby == 'generatebyapplication') {
					if (!$scope.application_id) { $scope.errorMSG = "Application ID is required"; return; }
					let Indata = {generateby:$scope.generateby,application_id:$scope.application_id,fee_demerit_id:$scope.fee_demerit_id,part_id:$scope.part_id,semester_id:$scope.semester_id};
					$scope.getChallanData(Indata);
					$scope.IsVisible = $scope.IsVisible = true;
				} else if (generateby == 'generatebyprogram') {
					if (!$scope.session_id) { $scope.errorMSG = "Sessions is required"; return; } else 
					if (!$scope.campus_id) { $scope.errorMSG = "Campus is required"; return; } else 
					if (!$scope.program_type_id) { $scope.errorMSG = "Program Type is required"; return; } else
					if (!$scope.shift_id) { $scope.errorMSG = "Shift is required"; return; } else
					if (!$scope.part_id) { $scope.errorMSG = "Part is required"; return; } else
					if (!$scope.program_id) { $scope.errorMSG = "Program is required"; return; } else
					if (!$scope.semester_id) { $scope.errorMSG = "Semester is required"; return; } else
					if (!$scope.fee_demerit_id) { $scope.errorMSG = "Demerit is required"; return; } else
					//if (!$scope.starting_challan_no) { $scope.errorMSG = "Starting challan #"; return; }else
					if (!$scope.challan_type_id) { $scope.errorMSG = "Challan type"; return; } else
                    if (!$scope.validUpto) { $scope.errorMSG = "Valid upto is required"; return; } 
					let Indata = {
						generateby:$scope.generateby,
						campus_id:$scope.campus_id,
						program_type_id:$scope.program_type_id,
						shift_id:$scope.shift_id,
						part_id:$scope.part_id,
						program_id:$scope.program_id,
						semester_id:$scope.semester_id,
						starting_challan_no:$scope.starting_challan_no,
						session_id:$scope.session_id,
						challan_type_id:$scope.challan_type_id,
						fee_demerit_id:$scope.fee_demerit_id,
						validUpto:$scope.validUpto
					};
					$scope.getChallanData(Indata);
				} else if (generateby == 'generatebyselectionlist') {
					let Indata = {
						generateby:$scope.generateby,
						admission_list_id:$scope.admission_list_id
					};
					// console.log(Indata);
					$scope.getChallanData(Indata);
				} else if (generateby == null) {
					$scope.errorMSG = "Generate By is required";
					return;
				}
			}
			
			$scope.getChallanData = function(Indata) {
				$scope.Indata = Indata;
				$scope.challan_info=null;
				$scope.errorMSG="Loading data...";
				$scope.GenerateMSG=null;
				$http.post('<?=base_url()?>FeesReports/getChallanDataForNumber',Indata).then(function success(response){
					console.log(response.data)
					if (response.status === 206 ){
						$scope.errorMSG = response.data;
					}
					if (response.status === 200 ){
						$scope.challan_info = response.data;
						$scope.errorMSG=null;

					}
				},function error(response){
					console.log(response);
				});
			}
			function filterList(list1, challanNumbers) {
				return list1.filter(item => challanNumbers.includes(item.CHALLAN_NO));
			}
			$scope.generate_challan_no = function() {
				$scope.errorMSG=null;				
				let checked_challan = [];
				$("input:checkbox[name=ChallanCheckbox]:checked").each(function(key,value){
					checked_challan.push(this.value);
				});
				
				let challanGenerate = $scope.challan_info.filter(challan => checked_challan.includes(challan.CHALLAN_NO));
					
				if (challanGenerate.length===0){
					$scope.errorMSG = "Select Challan Row";
					return;
				}

				if ($window.confirm("Do you want to generate challan number?") === false){
					return;
				}
				$scope.GenerateMSG="Processing your request please wait.";
				
				
				$http.post('<?=base_url()?>FeesReports/generate_bank_challan',challanGenerate).then(function success(response){
					
					$scope.CHALLAN_NOT_GENERATED = response.data.CHALLAN_NOT_GENERATED;
					$scope.CHALLAN_GENERATED = response.data.CHALLAN_GENERATED;
					if (response.status === 206 ){
						$scope.GenerateMSG = response.data.MESSAGE;
					}
					if (response.status === 200 ){
						$scope.GenerateMSG = response.data.MESSAGE;
					}
					$scope.download($scope.CHALLAN_NOT_GENERATED,'CHALLAN_NOT_GENERATED');
					$scope.download($scope.CHALLAN_GENERATED,'CHALLAN_GENERATED');
				},function error(response){
					// console.log(response);
				});
			}
			$scope.getSession = function () {
				$http({
                method: 'get',
                url: '<?php echo base_url(); ?>AdminApi/getSessions'
                }).then(function successCallback(response) {
                $scope.session = response.data;
                }); 
			}
			$scope.getSession();
			$scope.getCampus = function () {
				$http({
                method: 'get',
                url: '<?php echo base_url(); ?>AdminApi/getCampuses'
                }).then(function successCallback(response) {
                // Store response data
                $scope.campus = response.data;
                }); 
			}
			$scope.getCampus();
			$scope.getProgramTypes = function () {
				$http({
                method: 'get',
                url: '<?php echo base_url(); ?>AdminApi/getProgramTypes'
                }).then(function successCallback(response) {
                // Store response data
                $scope.programtypes = response.data;
                }); 
			}
			$scope.getProgramTypes();
			$scope.getShifts = function () {
				$http({
                method: 'get',
                url: '<?php echo base_url(); ?>AdminApi/getShifts'
                }).then(function successCallback(response) {
                // Store response data
                $scope.shifts = response.data;
                }); 
			}
			$scope.getShifts();
			$scope.getParts = function () {
                var Indata = {program_type_id:$scope.program_type_id};
				$http.post("<?php echo base_url(); ?>AdminApi/getParts", Indata).then(function (response) {
				$scope.parts = response.data;
				},function (response) { 
				alert("error"); 
				});
			}
			$scope.getParts();
			$scope.getSemester = function (demerit_id) {

				let data = {flag:"proper_channel",FEE_DEMERIT_ID:demerit_id};
				$http.post("<?php echo base_url(); ?>AdminApi/getSemester", data).then(function (response) {
				$scope.semesters = response.data;
				},function (response) { 
				alert("error"); 
				});
			}
			$scope.getSemester();
			$scope.getDemerit= function (){
				$scope.demerit = null;
				$http.post('<?=base_url()?>AdminApi/getDemerit').then(function success(response){
					if (response.status == 200 ){
						let array_data = response.data;
						$scope.demerit = array_data;
					}

				},function error(response){
					$scope.errorMSG= 'Sorry could not find data';
				});
			}
			$scope.getDemerit();
			$scope.getPrograms = function () {
                var Indata = {shift_id:$scope.shift_id, program_type_id:$scope.program_type_id, campus_id:$scope.campus_id};
				$http.post("<?php echo base_url(); ?>AdminApi/getCampusPrograms", Indata).then(function (response) {				
				$scope.programs = response.data;
				},function error(response) { 
				alert("error"); 
				});
			}
			$scope.getPrograms();
			$scope.getAdmissionLists = function () {
                var Indata = {shift_id:$scope.shift_id, program_type_id:$scope.program_type_id, campus_id:$scope.campus_id, session_id:$scope.session_id};
				$http.post("<?php echo base_url(); ?>AdminApi/AdmissionListNo", Indata).then(function (response) {				
				$scope.lists = response.data;
				},function (response) { 
				alert("error"); 
				});
			}
			$scope.getAdmissionLists();
			$scope.getChallanType= function (){
				$scope.challan_types = null;
				$http.post('<?=base_url()?>AdminApi/getChallanType').then(function success(response){
					if (response.status === 200 ){
						$scope.challan_types = response.data;
						// console.log($scope.challan_types[0]['CHALLAN_TYPE_ID'])
						$scope.challan_type_id=1;
					}
				},function error(response){
					$scope.errorMSG= 'Sorry could not find data';
				});
			}
			$scope.getChallanType();
			$scope.checkAll= function (){
				if ($scope.ChallanCheckboxAll){
					$("input[name=ChallanCheckbox]").prop("checked","checked");
				}else{
					$("input[name=ChallanCheckbox]").attr("checked",false);
				}
			}
			$scope.download	= function (records,file_name){
				let date = new Date();
				let csvString = '';
				let columns = records[0];
				angular.forEach(columns,function (column,column_key){
					csvString = csvString+""+column_key;
					csvString = csvString+",";
				});
				csvString = csvString+"\n";

				angular.forEach(records,function(value) {
					let FEE_PROG_LIST_ID =  value['FEE_PROG_LIST_ID']
					if(FEE_PROG_LIST_ID == null || FEE_PROG_LIST_ID ==="") FEE_PROG_LIST_ID='';

					csvString = csvString +value['STATUS']+",";
					csvString = csvString +value['CHALLAN_NO']+",";
					csvString = csvString +value['APPLICATION_ID']+",";
					csvString = csvString +value['CHALLAN_TYPE_ID']+",";
					csvString = csvString +value['BANK_ACCOUNT_ID']+",";
					csvString = csvString +value['SELECTION_LIST_ID']+",";
					csvString = csvString +value['FIRST_NAME']+",";
					csvString = csvString +value['FNAME']+",";
					csvString = csvString +value['LAST_NAME']+",";
					csvString = csvString +value['PROGRAM_TITLE']+",";
					csvString = csvString +value['CATEGORY_NAME']+",";
					csvString = csvString +FEE_PROG_LIST_ID+",";
					csvString = csvString +value['FEE_DEMERIT_ID']+",";
					csvString = csvString +value['PART_ID']+",";
					csvString = csvString +value['SEMESTER_ID']+",";
					csvString = csvString +value['CHALLAN_AMOUNT']+",";
					csvString = csvString +value['INSTALLMENT_AMOUNT']+",";
					csvString = csvString +value['DUES']+",";
					csvString = csvString +value['LATE_FEE']+",";
					csvString = csvString +value['PAID_AMOUNT']+",";
					csvString = csvString +value['PAYABLE_AMOUNT']+",";
					csvString = csvString +value['VALID_UPTO']+",";
					csvString = csvString +value['DATETIME']+",";
					csvString = csvString +value['ADMIN_USER_ID']+",";
					csvString = csvString +value['REMARKS']+"\n";
				});
				var a = $('<a/>', {
					style:'display:none',
					href:'data:application/octet-stream;base64,'+btoa(csvString),
					download:file_name+date+'.csv'
				}).appendTo('body')
				a[0].click()
				a.remove();
			}
		}]);
	</script>
</head>
<body>
<div class="product-status mg-b-15" id="min-height">	
	<div ng-app="myApp" ng-controller="formCtrl">
		<div class="product-status-wrap">
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<h4 style="font-size: 11pt">Generate Candidate Challan Number</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 daidh">
					<label>Generate By</label>
					<select class="form-control" name="generateby" id="generateby" ng-model="generateby" ng-init="generateby = 'generatebyapplication'">
						<option value="generatebyapplication">Application</option>
						<option value="generatebyprogram">Program</option>
						<option value="generatebyselectionlist">Selection List</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
					<label>Challan Type</label>
					<select ng-model="challan_type_id" class="form-control">
						<option ng-repeat="challan_type in challan_types" ng-value="{{challan_type.CHALLAN_TYPE_ID }}">{{challan_type.CHALLAN_TITLE}}</option>
					</select>
				</div>
    			<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12 daidh" ng-show="generateby=='generatebyapplication'">
					<label>Application ID</label>
					<input type="text" class="form-control" id="application_id" ng-model="application_id"/>						
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 daidh" ng-show="generateby=='generatebyprogram' || generateby=='generatebyapplication'">
					<label>Demerit</label>
					<select ng-model="fee_demerit_id" class="form-control" ng-change="getSemester(fee_demerit_id)">
						<option ng-repeat="demeri in demerit" ng-value="{{demeri.FEE_DEMERIT_ID }}">{{demeri.NAME}}</option>
					</select>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 daidh" ng-show="generateby=='generatebyprogram' || generateby=='generatebyapplication'">
    				<label>Part</label>
    				<select class="form-control" ng-model="part_id">
        				<option ng-repeat="x in parts" value="{{x.PART_ID}}">{{x.NAME}}</option>
        			</select>
    			</div>
    			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 daidh" ng-show="generateby=='generatebyprogram' || generateby=='generatebyapplication'">
    			    <label>Semester</label>
    				<select class="form-control" ng-model="semester_id" ng-init="semester_id = '1'">
        				<option ng-repeat="x in semesters" value="{{x.SEMESTER_ID}}">{{x.NAME}}</option>
        			</select>
    			</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
					<label>Valid Upto</label>
					<input type="text" class="form-control"  id="validUpto" ng-model="validUpto" value="<?=date('Y-m-d')?>" title="yyyy-mm-dd" style="font-size: 7pt; font-weight: bold" />
				</div>
			</div>
		
			<div class="row">
				
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 daidh ">
					<label></label>
					<button type="button" ng-click="search(generateby)"  class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" title="Click here to fetch data from database" style="background-color: #00e676"><i class="fa fa-search"></i>&nbsp; <strong>Search Records</strong></button>
					<span class="text-danger" id="msg">{{errorMSG}}</span>
				</div>
			</div>
			<br>
			<div  id="generatebyapplication" ng-show="generateby=='generatebyapplication'">

					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" ng-show="IsVisible">
						<label>First Name</label>
						<span>{{challan_info.FIRST_NAME}}</span>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" ng-show="IsVisible">
						<label>Father's Name</label>
						<span>{{challan.FNAME}}</span>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" ng-show="IsVisible">
						<label>Last Name</label>
						<span>{{challan.LAST_NAME}}</span>
					</div>
				</div>
				
			</div>    		
			<div id="generatebyprogram" ng-show="generateby=='generatebyprogram' || generateby=='generatebyselectionlist'">
				<div class="row">
					<div class="col-lg-1 col-md-2 col-sm-12 col-xs-12">
						<label>Session</label>
						<select class="form-control" name="session_id" ng-model="session_id" ng-required="true" ng-change="getSession()">
							<option ng-repeat="x in session" value="{{x.SESSION_ID}}">{{x.YEAR}}</option>
						</select>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
						<label>Campus</label>
						<select class="form-control" name="campus_id" ng-model="campus_id" ng-required="true" ng-change="getPrograms(); getAdmissionLists()">
                            <option ng-repeat="x in campus" value="{{x.CAMPUS_ID}}">{{x.LOCATION}}</option>
						</select>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
						<label>Program Type</label>
						<select class="form-control" ng-model="program_type_id" ng-change="getPrograms(); getParts(); getAdmissionLists()">
							<option ng-repeat="x in programtypes | orderBy:'x.PROGRAM_TITLE'" value="{{x.PROGRAM_TYPE_ID}}">{{x.PROGRAM_TITLE}}</option>
						</select>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
						<label>Shift</label>
						<select class="form-control" ng-model="shift_id" ng-change="getPrograms(); getAdmissionLists()">
							<option ng-repeat="x in shifts" value="{{x.SHIFT_ID}}">{{x.SHIFT_NAME}}</option>
						</select>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" ng-show="generateby=='generatebyprogram'">
						<label>Part</label>
						<select class="form-control" ng-model="part_id">
							<option ng-repeat="x in parts" value="{{x.PART_ID}}">{{x.NAME}}</option>
						</select>
					</div>
					<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12" ng-show="generateby=='generatebyprogram'">
						<label>Program</label>
						<select class="form-control" ng-model="program_id">
							<option ng-repeat="x in programs" value="{{x.PROG_ID}}">{{x.PROGRAM_TITLE}}</option>
						</select>
					</div>
					<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12" ng-show="generateby=='generatebyselectionlist'">
						<label>Selection List No.</label>
						<select class="form-control" ng-model="admission_list_id">
							<option ng-repeat="x in lists" value="{{x.ADMISSION_LIST_ID}}">{{x.LIST_TITLE}}</option>
						</select>
					</div>
				</div>        				
			</div>
			<br>
			<div class="row" ng-model="selection">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="asset-inner">
						<div ng-show="generateby=='generatebyselectionlist'">
							<table class="table table-condesed">
								<thead>
									<tr style="font-size: 9pt;">
										<th>S#</th>							
										<th>CH:#</th>
										<th>APP ID</th>
										<th>FIRST NAME</th>
										<th>FATHER NAME</th>
										<th>BATCH</th>
										<th>PROGRAM</th>
										<th>CAMPUS</th>
										<th>SHIFT</th>
										<th>CATEGORY</th>
										<th>AY</th>
										<th>SESSION</th>
										<th>SL ID</th>
										<th>FPL ID</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="challan in challan_info" style="font-size: 9pt;">
										<td>{{$index + 1}}</td>	
										<td>{{challan.CHALLAN_NO}}</td>
										<td>{{challan.APPLICATION_ID}}</td>
										<td>{{challan.CANDIDATE_NAME}}</td>
										<td>{{challan.CANDIDATE_FNAME}}</td>
										<td>{{challan.BATCH_ID}}</td>
										<td>{{challan.PROGRAM_CLASS}}</td>
										<td>{{challan.CAMPUS_NAME}}</td>
										<td>{{challan.SHIFT}}</td>
										<td>{{challan.CATEGORY}}</td>
										<td>{{challan.AY}}</td>
										<td>{{challan.SESSION_ID}}</td>
										<td>{{challan.SELECTION_LIST_ID}}</td>
										<td>{{challan.FEE_PROG_LIST_ID}}</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div ng-show="generateby=='generatebyprogram'">
							<table class="table table-condesed">
								<thead>
									<tr style="font-size: 9pt;">
									<th><input type="checkbox" ng-model="ChallanCheckboxAll" ng-change="checkAll()" name="ChallanCheckboxAll" id="ChallanCheckboxAll" /></th>
										<th>S#</th>							
										<th>CH:#</th>
										<th>APP ID</th>
										<th>FIRST NAME</th>
										<th>FATHER NAME</th>
										<th>ROLL NO</th>
										<th>CATEGORY</th>
										<th>CHALLAN_CATEGORY</th>
										<th>FEE LABEL</th>
										<th>CHALLAN AMOUNT</th>
										<th>DUES</th>
										<th>PAYABLE AMOUNT</th>
										<th>ENROLMENT FEE</th>
										<th>SL ID</th>
										<th>FPL ID</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="challan in challan_info" style="font-size: 9pt;">
										<td><input type="checkbox" ng-model="ChallanCheckbox" ng-value="challan.CHALLAN_NO" name="ChallanCheckbox" id="ChallanCheckbox" /></td>
										<td>{{$index + 1}}</td>	
										<td ng-class="{active-bg: challan.ACTIVE == '0'}" >{{challan.CHALLAN_NO}}</td>
										<td>{{challan.APPLICATION_ID}}</td>
										<td>{{challan.CANDIDATE_NAME}}</td>
										<td>{{challan.CANDIDATE_FNAME}}</td>
										<td>{{challan.BATCH_ID}}</td>
										<td>{{challan.CATEGORY}}</td>
										<td ng-class="{fee-structure-bg: challan.FEE_STRUCTURE == '0'}">{{challan.CHALLAN_CATEGORY}}</td>
										<td>{{challan.REMARKS}}</td>
										<td>{{challan.CHALLAN_AMOUNT}}</td>
										<td>{{challan.DUES}}</td>
										<td>{{challan.PAYABLE_AMOUNT}}</td>
										<td>{{challan.ENROLMENT_FEE}}</td>
										<td>{{challan.SELECTION_LIST_ID}}</td>
										<td>{{challan.FEE_PROG_LIST_ID}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<br/><br/>
			<br/><br/>
			<hr/>
			<button type="button" ng-click="generate_challan_no()" class=" col-md-4 form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-warning" title="click here to generate fee challan numbers to database" style="background-color: #7991E8"><i class="fa fa-database"></i> <strong>Generate Fee Challan Numbers</strong></button>
			<br/>
				<span class="text-danger">{{errorMSG}}</span>
				<span class="text-danger">{{GenerateMSG}}</span>
		</div>
	</div>	
</div>
</body>
</html>