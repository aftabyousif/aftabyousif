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
		
		input[type=text]{
			border: 1px solid black;
		}
		.number{
			text-align: right;
			font-weight: bold;
			font-family: Roboto Slab, Times New Roman, serif;
			font-size: 15px;
		}
	</style>

	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
	<script type="text/javascript">

		var app = angular.module('myApp', []);
		app.controller('formCtrl', function($scope,$http) {
			//$scope.isyes = true;
			$scope.challan_no = "00000000"
			$scope.postdata = function (search_value){

				if (search_value == ""){
					$scope.errorMSG = "Search value is required";
					return;
				}
				$scope.setFieldsNull();
				$scope.errorMSG=null;

				$scope.errorMSG = "Finding record, please wait....";

				let data = {search_value:search_value};

				$http.post('<?=base_url()?>FeesReports/get_student_challan_online',data).then(function success(response){
					if (response.status == 204 ){
						$scope.errorMSG= 'Sorry could not find data';
					}
					if (response.status == 200 ){
						$scope.errorMSG = "";
						let array_data = response.data;
						$scope.challans = array_data;
						// console.log(array_data);
					}

				},function error(response){
					// console.log(response);
				})
			}
			$scope.editChallan = function (challan){
				// console.log(challan)
				$scope.updated_challan = null;
				$scope.old_challan = null
				$scope.old_challan = angular.copy(challan)
				$scope.updated_challan = angular.copy(challan)
				$scope.setFieldsNull();
				$scope.firstname = challan['CANDIDATE_NAME']
				$scope.fname = challan['CANDIDATE_FNAME']
				$scope.surname = challan['CANDIDATE_SURNAME']
				$scope.valid_upto = challan['VALID_UPTO']
				$scope.program = challan['PROGRAM']
				$scope.class = challan['CLASS']
				$scope.fee_label = challan['FEE_LABLE']
				$scope.account_no = challan['ACCOUNT_NO']
				$scope.fee_amount = challan['FEE_AMOUNT']
				$scope.dues = challan['DUES']
				$scope.late_fee = challan['LATE_FEE']
				$scope.total_amount = challan['LATE_FEE']*1+challan['DUES']*1+challan['FEE_AMOUNT']*1
				$scope.challan_no = challan['CHALLAN_NO']

				$scope.challan_status = challan['ACTIVE'] == 1 ? true : false
			}
			$scope.SumAmount = function (){
// +parseInt($scope.late_fee);
				let fee_amount = parseInt($scope.fee_amount);
					let late_fee = parseInt($scope.late_fee);
				$scope.total_amount = fee_amount+parseInt($scope.dues)+late_fee

				$scope.updated_challan['FEE_AMOUNT'] =fee_amount
				$scope.updated_challan['LATE_FEE'] =late_fee
				$scope.updated_challan['TOTAL_AMOUNT'] =$scope.total_amount
			}
			$scope.saveChallan = function (){

				let challan = $scope.updated_challan
				challan['ACTIVE'] = $scope.challan_status == true ? 1 : 0
				challan['VALID_UPTO'] = $scope.valid_upto
				let old_challan = $scope.old_challan;

				console.log(challan)
				console.log($scope.old_challan)

				$scope.errorMSG=null;

				if (challan == ""){
					$scope.errorMSG = "Invalid parameters";
					return;
				}
				$scope.errorMSG = "please wait....";

				let data = {challan:challan,old_challan:old_challan};
				$http.post('<?=base_url()?>FeesReports/updateOnlineChallan',data).then(function success(response){
					if (response.status == 204 ){
						$scope.errorMSG= 'Process failed...';
					}
					if (response.status == 200 ){
						$scope.errorMSG = "Successfully updated...";
						$scope.setFieldsNull();
					}
				},function error(response){
					// console.log(response);
				});
			}
			$scope.setFieldsNull = function (){

				$scope.firstname = null
				$scope.fname = null
				$scope.surname = null
				$scope.valid_upto = null
				$scope.program = null
				$scope.class = null
				$scope.fee_label = null
				$scope.account_no = null
				$scope.fee_amount = null
				$scope.dues = null
				$scope.late_fee = null
				$scope.total_amount = null
				$scope.challan_no = null
				$scope.challan_status = false
			}
		})
	</script>
</head>
<body>
<div class="product-status mg-b-15" >
	<div class="container-fluid" id="min-height">
		<!--		<div class="row">-->
		<!--			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">-->
		<div ng-app="myApp" ng-controller="formCtrl">
			<!--					<form>-->
			<div class="product-status-wrap">
				<span class="text-left" style="font-weight: bold; font-size: 14pt">Edit Student Challan (Online)</span>
				<h4 class="text-right"><span style="background-color: black; color: white; padding: 10px">CHALLAN NO: {{challan_no}} </span></h4>
				<div class="form-group-inner">
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label>Type Roll No.</label>
							<div class="form-group data-custon-pick data-custom-mg">
								<input type="text" class="form-control" id="search_value" ng-model="search_value" />
							</div>
						</div>
						<br/>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<button type="button" ng-click="postdata(search_value)"  class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" title="Click here to fetch data from database" style="background-color: #00e676"><i class="fa fa-search"></i>&nbsp; <strong>Search Record</strong></button>
						</div>
						<span class="text-danger" id="msg">{{errorMSG}}</span>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							<table class="table table-condensed">
								<thead>
									<tr>
										<th>#</th>
										<th>Edit</th>
										<th>Print Challan</th>
										<th>Name</th>
										<th>Challan No.</th>
										<TH>Class</TH>
										<th>Due Date</th>
										<th>Fee Amount</th>
										<th>Dues</th>
										<th>Late Fee</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
								<tr ng-repeat="challan in challans">
									<td>{{$index+1}}</td>
									<td><a href="javascript:void(0)" ng-click="editChallan(challan)">Edit</a></td>
									
									<td><a href="https://itsc.usindh.edu.pk/student/public/challan2.php?id={{challan.CODE_CHALLAN_NO}}&request=itsc&rollno={{challan.BATCH}}&batchID={{challan.BATCH_ID}}&USER_ID={{challan.USER_ID}}" target='_blank'>Print</a></td>
									<td>{{challan.CANDIDATE_NAME}}</td>
									<td>{{challan.CHALLAN_NO}}</td>
									<td>{{challan.CLASS}}</td>
									<td>{{challan.VALID_UPTO}}</td>
									<td>{{challan.FEE_AMOUNT}}</td>
									<td>{{challan.DUES}}</td>
									<td>{{challan.LATE_FEE}}</td>
									<td>{{challan.FEE_AMOUNT*1+challan.DUES*1+challan.LATE_FEE*1}}</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Candidate Name</label>
							<input type="text" ng-model="firstname" class="form-control" style="background-color: white" readonly/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Father's Name</label>
							<input type="text" ng-model="fname" class="form-control" style="background-color: white" readonly/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Candidate Surname</label>
							<input type="text" ng-model="surname" class="form-control" style="background-color: white" readonly/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Valid Upto <span class="text-danger"><em>Editable</em></span></label>
							<div class="form-group data-custon-pick" id="data_1">
								<div class="input-group date">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" ng-model="valid_upto" class="form-control">
								</div>
								<span class="text-danger"><em>format yyyy-mm-dd</em></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Program</label>
							<input type="text" ng-model="program" class="form-control" style="background-color: white" readonly/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Class</label>
							<input type="text" ng-model="class" class="form-control" style="background-color: white" readonly/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Fee Label</label>
							<input type="text" ng-model="fee_label" class="form-control" style="background-color: white" readonly/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Account No</label>
							<input type="text" ng-model="account_no" class="form-control" style="background-color: white" readonly/>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Fee Amount <span class="text-danger"><em>Editable</em></span></label>
							<input type="text" ng-model="fee_amount" ng-change="SumAmount()" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Dues</label>
							<input type="text" ng-model="dues" class="form-control number" style="background-color: white" readonly/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Late Fee</label>
							<input type="text" ng-model="late_fee" class="form-control number" ng-change="SumAmount()" style="background-color: white"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label>Total Amount</label>
							<input type="text" ng-model="total_amount" class="form-control number" style="background-color: white" readonly/>
						</div>
					</div>
				</div>


				<div class="form-group">
					<label>Challan Status</label>
					<input type="checkbox"  ng-model="challan_status" />
				</div>
				<button type="button" ng-click="saveChallan()"  class=" form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" style="background-color: #84e7b7; width: 15%"><i class="fa fa-save"></i>&nbsp; <strong>Save Challan</strong></button>

				<!--						</form>-->
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
</script>
</html>

