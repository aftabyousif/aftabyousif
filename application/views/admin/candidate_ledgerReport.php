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
		h3{
			font-size: 13pt;
			font-family: "Times New Roman", serif;
		}
		.amt-total{
			text-align: center;
			font-family: Helvetica;
			font-size: 11pt;
		}
		.active-bg {
			background-color: #FF6064;
		}
	</style>
<!--	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>-->
	<script type="text/javascript">
		var app = angular.module('myApp', []);
		app.controller('formCtrl', function($scope,$http,$filter) {
			//$scope.isyes = true;
			$scope.modifyChallan={};
			$scope.ledgerSum={};
			$scope.balance=0;
			$scope.postdata = function (search_by,search_value,show_retain){
				if (search_by == ""){
					$scope.errorMSG = "Search By is required";
					return;
				}else if (search_value == ""){
					$scope.errorMSG = "Search value is required";
					return;
				}
                // let show_retain=false;
				if ($scope.show_retain){
					show_retain=true;
				}else{show_retain=false;}
				$scope.errorMSG=null;
				$scope.account_id = null;
				$scope.account_status = null;
				$scope.firstname = null;
				$scope.lastname = null;
				$scope.fname = null;
				$scope.ledger = null;
				$scope.selection_list = null;
				$scope.challanInfo = null;
				$scope.errorMSG = "Finding record, please wait....";
				$scope.ledgerSum={};
				let data = {search_value:search_value,search_by:search_by,show_retain:show_retain};
				
				$http.post('<?=base_url()?>FeesReports/get_candidate_ledger',data).then(function success(response){
				    console.log(response.data);
					if (response.status == 204 ){
						$scope.errorMSG= 'Sorry could not find data';
					}
					if (response.status == 200 ){
						let array_data = response.data;
						let profile = array_data['PROFILE'];
						let ledger_data	= array_data['LEDGER']
						let selection_list_db	= array_data['SELECTION_LIST'];
						let challan_info	= array_data['CHALLAN'];
						$scope.profile = profile;
						$scope.account_id = profile['ACCOUNT_ID'];
						$scope.firstname = profile['FIRST_NAME'];
						$scope.lastname = profile['LAST_NAME'];
						$scope.fname = profile['FNAME'];
						$scope.application_id = profile['APPLICATION_ID'];
						$scope.account_status = profile['ACTIVE'] == 1 ? true : false;
						$scope.challanInfo = challan_info;
						$scope.ledger = ledger_data;
						$scope.selection_list= selection_list_db;
						$scope.errorMSG = null;
						$scope.data =$scope.ledger;
						$scope.dataCopy = angular.copy( $scope.data );
						$scope.selectionData = $scope.selection_list
						$scope.selectionDataCopy = angular.copy($scope.selectionData);
                        
						$scope.parseCheckboxes = function() {
							for( var i = 0, len = $scope.data.length ; i < len ; i++ ) {
								$scope.data[i].checkbox = $scope.data[i].IS_YES == 'Y' ? true : false;
								$scope.data[i].ismerit_checkbox = $scope.data[i].IS_MERIT == 'Y' ? true : false;
								$scope.data[i].is_retain_hide = $scope.data[i].CHALLAN_TYPE_ID == 1 ? true : false;
							}
							for( var j = 0, len = $scope.selectionData.length ; j < len ; j++ ) {
								$scope.selectionData[j].checkbox = $scope.selectionData[j].ACTIVE == 1 ? true : false;
							}
				// 		console.log($scope.data);
						}
						$scope.parseCheckboxes();
						$scope.calculateLedger();
					}

				},function error(response){
					// console.log(response);
				});
			}
			$scope.is_yes_change = function (obj, index){
				// console.log()
				// return;
				let ID = obj.FEE_LEDGER_ID;
				$scope.errorMSG=null;

				if (ID == ""){
					$scope.errorMSG = "Invalid parameters";
					return;
				}
				let is_yes ;
				// console.log(this);
				if(obj.checkbox){
					is_yes = "Y";
				}else{
					is_yes = "N";
				}

				$scope.errorMSG = "please wait....";

				let data = {FEE_LEDGER_ID:ID,IS_YES:is_yes};

				$http.post('<?=base_url()?>FeesReports/change_is_yes',data).then(function success(response){
					if (response.status == 204 ){
						$scope.errorMSG= 'Process failed...';
					}
					if (response.status == 200 ){
						$scope.errorMSG = "Successfully updated...";
					}
				},function error(response){
					// console.log(response);
				});
			}
			$scope.is_merit_change = function (obj, index){
				// console.log()
				// return;
				let ID = obj.FEE_LEDGER_ID;
				$scope.errorMSG=null;

				if (ID == ""){
					$scope.errorMSG = "Invalid parameters";
					return;
				}
				let is_merit ;
				// console.log(this);
				if(obj.ismerit_checkbox){
					is_merit = "Y";
				}else{
					is_merit = "N";
				}

				$scope.errorMSG = "please wait....";

				let data = {FEE_LEDGER_ID:ID,IS_MERIT:is_merit};
				$http.post('<?=base_url()?>FeesReports/change_is_merit',data).then(function success(response){
					if (response.status == 204 ){
						$scope.errorMSG= 'Process failed...';
					}
					if (response.status == 200 ){
						$scope.errorMSG = "Successfully updated...";
					}
				},function error(response){
					// console.log(response);
				});
			}
			$scope.change_selection_status = function (remarks,obj, index){
			
				let ID = obj.SELECTION_LIST_ID;
				$scope.errorMSG=null;

				if (ID == ""){
					$scope.errorMSG = "Invalid parameters";
					return;
				}
				let is_active ;
				// console.log(this);
				if(obj.checkbox){
					is_active = 1;
				}else{
					is_active = 0;
				}

				$scope.errorMSG = "please wait....";

				let data = {SELECTION_LIST_ID:ID,is_active:is_active,remarks:remarks};
				$http.post('<?=base_url()?>FeesReports/change_selection_status',data).then(function success(response){
					if (response.status == 204 ){
						$scope.errorMSG= 'Process failed...';
					}
					if (response.status == 200 ){
						$scope.errorMSG = "Successfully updated...";
					}
				},function error(response){
					// console.log(response);
				});
			}
			$scope.transfer_fee = function (obj, index){

				let ID = obj.SELECTION_LIST_ID;
				let APPLICATION_ID = obj.APPLICATION_ID;
				$scope.errorMSG=null;

				if (ID == ""){
					$scope.errorMSG = "Invalid parameters";
					return;
				}
				let is_active ;
				// console.log(this);
				if(obj.PAID_FEE){
					// is_active = 1;
				}else{
					return;
				}

				$scope.errorMSG = "please wait....";

				let data = {SELECTION_LIST_ID:ID,APPLICATION_ID:APPLICATION_ID};
				$http.post('<?=base_url()?>FeesReports/transfer_fee',data).then(function success(response){
					if (response.status == 204 ){
						$scope.errorMSG= 'Process failed...';
					}
					if (response.status == 200 ){
						$scope.errorMSG = "Successfully updated...";
					}
				},function error(response){
					// console.log(response);
				});
			}
			$scope.candidate_account_status = function (account_status,id){

				let account_id_input = id;
				$scope.errorMSG=null;

				if (account_id_input == ""){
					$scope.errorMSG = "Invalid parameters";
					return;
				}
				let is_active ;
				// console.log(this);
				if(account_status){
					is_active = 1;
				}else{
					is_active = 0;
				}

				$scope.errorMSG = "please wait....";

				let data = {ACCOUNT_ID:account_id_input,IS_ACTIVE:is_active};
				$http.post('<?=base_url()?>FeesReports/disable_account',data).then(function success(response){
					if (response.status == 204 ){
						$scope.errorMSG= 'Process failed...';
					}
					if (response.status == 200 ){
						$scope.errorMSG = "Successfully updated...";
					}
				},function error(response){
					// console.log(response);
				});
			}
			$scope.produce_retain_challan = function (obj, index){
                    // console.log(obj);
				let ID = obj.SELECTION_LIST_ID;
				let APPLICATION_ID = obj.APPLICATION_ID;
				let ac_id = $scope.account_id;
				
				// console.log(ac_id);
				$scope.errorMSG=null;

				if (ID == ""){
					$scope.errorMSG = "Invalid parameters";
					return;
				}
			    
			    if(confirm("Do you want to generate retain challan?")== false){
			        return;
			    }
				$scope.errorMSG = "please wait....";

				
				let data = {account_id:ac_id,obj_data:obj};
				$http.post('<?=base_url()?>FeesReports/generate_retain_challan',data).then(function success(response){
				    // console.log(response);
					if (response.status == 204 ){
						$scope.errorMSG= "Failed or challan already created...";
					}
					if (response.status == 200 ){
						$scope.errorMSG = "Successfully updated...";
					}
				},function error(response){
					console.log(response);
				});
				
				$scope.postdata(2,ac_id);
				
			}
            $scope.printReport = function (search_by,search_value){
				if (search_by == null){
					$scope.errorMSG = "Search By is required";
					return;
				}else if (search_value == null){
					$scope.errorMSG = "Search value is required";
					return;
				}
				query_string = search_by+"/"+$scope.application_id;
		        window.open('<?=base_url()?>paidChallanReport/'+query_string, "_blank");
			}
			$scope.printIDCardChallan = function (search_by,search_value){
				if (search_by == null){
					$scope.errorMSG = "Search By is required";
					return;
				}else if (search_value == null){
					$scope.errorMSG = "Search value is required";
					return;
				}
				query_string = search_by+"/"+$scope.application_id;
		        window.open('<?=base_url()?>StudentIDCard/idCardChallan/'+query_string, "_blank");
			}
			$scope.printAdmissionLetter = function (search_by,search_value){
				if (search_by == null){
					$scope.errorMSG = "Search By is required";
					return;
				}else if (search_value == null){
					$scope.errorMSG = "Search value is required";
					return;
				}
				query_string = search_by+"/"+$scope.application_id;
		        window.open('<?=base_url()?>StudentIDCard/admissionLetterReport/'+query_string, "_blank");
			}
			$scope.correctionLetterAndList = function (search_by,search_value){
				if (search_by == null){
					$scope.errorMSG = "Search By is required";
					return;
				}else if (search_value == null){
					$scope.errorMSG = "Search value is required";
					return;
				}
				query_string = search_by+"/"+$scope.application_id;
		        window.open('<?=base_url()?>StudentIDCard/correctionLetterAndList/'+query_string, "_blank");
			}			
			$scope.calculateAmountChallan = function (){
			    
			    $scope.modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT = parseInt($scope.modifyChallan.FEE_CHALLAN.CHALLAN_AMOUNT)+parseInt($scope.modifyChallan.FEE_CHALLAN.DUES)
			}
			$scope.printIDCard = function (search_by,search_value){
				if (search_by == null){
					$scope.errorMSG = "Search By is required";
					return;
				}else if (search_value == null){
					$scope.errorMSG = "Search value is required";
					return;
				}
				var query_string = '';
                $scope.selection_list.forEach(myFunction);
                
                function myFunction(obj) {
                  if(obj.PAID_FEE){
                   console.log(obj); 
                   query_string ="roll_no='"+obj.ROLL_NO+"'&sh_id="+obj.SHIFT_ID+"&pl_id="+obj.PROG_LIST_ID+"&c_id="+obj.CAMPUS_ID+"&pt_id="+obj.PROGRAM_TYPE_ID+"&p_id="+$scope.max_part+"&s_id="+obj.SESSION_ID;
                  }
                }
				
		        window.open('<?=base_url()?>StudentIDCard/idcardpaper?'+query_string, "_blank");
			}
			$scope.editChallan = function (challan){
			    
				$scope.ChallanError=null;
				$scope.modifyChallan = angular.copy(challan);
				let paid_challan = angular.copy($filter('filter')($scope.ledger, {'CHALLAN_NO':challan.FEE_CHALLAN.CHALLAN_NO})[0]);
				if (paid_challan != undefined){
						$scope.modifyChallan.FEE_CHALLAN.PAID_AMOUNT=paid_challan.PAID_AMOUNT;
						$scope.modifyChallan.FEE_CHALLAN.PAID_DATE=paid_challan.LEDGER_COLLECTION_DATE;
						$scope.balance=paid_challan.PAID_AMOUNT-challan.FEE_CHALLAN.PAYABLE_AMOUNT;
					}
				$("#editChallanWindow").modal("show");
			}
			$scope.saveChallanChanges = function (){
				$scope.ChallanError=null;
				if ($scope.modifyChallan == null){
					$scope.ChallanError = "Invalid parameters";
					return;
				}
				if (confirm("Do want to save changes?") == false){
					return;
				}
				$scope.ChallanError = "please wait....";

				let data = {challan:$scope.modifyChallan,profile:$scope.profile};
				$http.post('<?=base_url()?>FeesReports/updateChallan',data).then(function success(response){
					if (response.status == 206 ){
						$scope.ChallanError= response.data;
					}
					if (response.status == 200 ){
						$scope.ChallanError = response.data;
					}
				},function error(response){
				});
			}
			$scope.markChallanPaid = function (){

				$scope.ChallanError=null;

				if ($scope.modifyChallan == null){
					$scope.ChallanError = "Invalid parameters";
					return;
				}
				if (confirm("Do want to mark as paid?") == false){
					return;
				}
				$scope.ChallanError = "please wait....";

				let data = {challan:$scope.modifyChallan};
				$http.post('<?=base_url()?>FeesReports/mark_paid',data).then(function success(response){
					if (response.status == 206 ){
						$scope.ChallanError= response.data;
					}
					if (response.status == 200 ){
						$scope.ChallanError = response.data;
					}
				},function error(response){
				});
			}
			$scope.calculateLedger = function(){
				var ledger = $scope.ledger;
				var sum_challan_amount=0;
				var sum_payable_amount=0;
				var sum_paid_amount=0;
				var late_fee = sum_dues=0;
				$scope.max_part = 0
				angular.forEach(ledger,function (value,key){
				    if(value['PART_NO']>$scope.max_part){
				        $scope.max_part = value['PART_NO'];
				    }
		            if(value['CHALLAN_TYPE_ID']==1 || value['CHALLAN_TYPE_ID']==3){
		            sum_challan_amount+=parseInt(value['CHALLAN_AMOUNT'])
					sum_payable_amount+=parseInt(value['PAYABLE_AMOUNT'])
					sum_paid_amount+=parseInt(value['PAID_AMOUNT'])
					late_fee += parseInt(value['LATE_FEE'])
					sum_dues+=parseInt(value['PAYABLE_AMOUNT'])-parseInt(value['PAID_AMOUNT'])+parseInt(value['LATE_FEE']);
		            }
				});
				$scope.ledgerSum.CHALLAN_AMOUNT =sum_challan_amount
				$scope.ledgerSum.PAYABLE_AMOUNT = sum_payable_amount
				$scope.ledgerSum.PAID_AMOUNT = sum_paid_amount
				$scope.ledgerSum.DUES = sum_dues;
				$scope.ledgerSum.LATE_FEE = late_fee;

				// console.log($scope.ledgerSum);
			}
			$scope.checkAll= function (){
				if ($scope.LedgerCheckboxAll){
					$("input[name=LedgerCheckbox]").prop("checked","checked");
				}else{
					$("input[name=LedgerCheckbox]").attr("checked",false);
				}
			}
			$scope.DeleteLedger = function (){

				$scope.errorMSG=null;
				let fee_ledger_ids = [];
				$("input[name=LedgerCheckbox]:checked").each(function(key,value){
					fee_ledger_ids.push(this.value);
				});

				if (Object.keys('fee_ledger_ids').length===0){
					$scope.errorMSG = "Select Ledger Row";
					return;
				}

				if (confirm("Do want to delete ledger records?") === false){
					return;
				}
				$scope.errorMSG = "please wait....";

				let data = {fee_ledger_ids:fee_ledger_ids};
				$http.post('<?=base_url()?>FeesReports/delete_ledger_record_handler',data).then(function success(response){
					if (response.status === 206 ){
						$scope.errorMSG= response.data;
					}
					if (response.status === 200 ){
						$scope.errorMSG = response.data;
						$scope.postdata($scope.search_by,$scope.search_value)
					}
				},function error(response){
				});
			}
		});
	</script>
</head>
<body>
<div class="product-status mg-b-15" id="min-height">
	<div class="container-fluid">
				<div ng-app="myApp" ng-controller="formCtrl">
				<div class="product-status-wrap">
					<div class="row">
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
							<h4>Candidate Ledger Report</h4>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label>Search By</label>
							<select class="form-control" ID="search_by" ng-model="search_by" ng-init="search_by = '1'">
								<option value="1">Application ID</option>
								<option value="3">Roll No</option>
								<option value="2">Account No</option>
							</select>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label>Searched Value</label>
							<div class="form-group data-custon-pick data-custom-mg">
								<input type="text" class="form-control" id="search_value" ng-model="search_value" />
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label>Show retain challans</label>
							<div class="form-group data-custon-pick data-custom-mg">
								<input 
							      class="form-check-input ml-2"
							      type="checkbox" 
							      ng-model="show_retain"
							      />
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<button 
								type="button" 
								ng-click="postdata(search_by,search_value)"  
								class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary mt-8" 
								title="Click here to fetch data from database" 
								style="background-color: #00e676"><i class="fa fa-search"></i>&nbsp; <strong>Search Record</strong>
							</button>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<button 
								type="button" 
								onclick="location.reload()" 
								class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" 
								style="background-color: #00cae3">
								<i class="fa fa-refresh"></i>&nbsp; <strong>Re-Fresh</strong>
							</button>
						</div>
                    </div>
                    <br/>
					<div class="form-group-inner">
						<div class="row">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<button 
								    type="button" 
								    ng-click="printReport(1,search_value)" 
								    class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" 
								    title="Click here from print PDF" 
								    style="background-color: #fa6ee3"
								>
								<i class="fa fa-print"></i>&nbsp; <strong>Print Fees Paid Statement</strong>
								</button>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<button 
								    type="button" 
								    ng-click="printIDCardChallan(1,search_value)" 
								    class="form-control basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" 
								    title="Click here to download ID Crad challan" 
								    style="background-color: #77b5fe">
								    <i class="fa fa-print"></i>&nbsp; <strong>Print ID Card Challan</strong>
								</button>
						    </div>
						    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<button 
								    type="button" 
								    ng-click="printIDCard(1,search_value)" 
								    class="form-control basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" 
								    title="Click here to download ID Crad challan" 
								    style="background-color: #c3e8de">
								    <i class="fa fa-print"></i>&nbsp; <strong>Print ID Card</strong>
								</button>
						    </div>
						    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<button 
								    type="button" 
								    ng-click="printAdmissionLetter(1,search_value)" 
								    class="form-control basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" 
								    title="Click here to download ID Crad challan" 
								    style="background-color: #ff3569">
								    <i class="fa fa-print"></i>&nbsp; <strong>Print Admission List</strong>
								</button>
						    </div>
						    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<button 
								    type="button" 
								    ng-click="correctionLetterAndList(1,search_value)" 
								    class="form-control basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" 
								    title="Click here to download ID Crad challan" 
								    style="background-color: #ab0068">
								    <i class="fa fa-print"></i>&nbsp; <strong>Print Correction Letter & List</strong>
								</button>
						    </div>
						    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<button 
								    type="button" 
								    ng-click="DeleteLedger()"  
								    class="form-control  basic-ele-mg-b-10 responsive-mg-b-10 btn btn-sm btn-primary" 
								    title="Click here to fetch data from database" 
								    style="background-color: rgba(229,57,81,0.81)">
								    <i class="fa fa-remove"></i>&nbsp; <strong>Delete Ledger Entries</strong></button>
							</div>
							<span class="text-danger" id="msg">{{errorMSG}}</span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Account #
									<input type="checkbox" ng-model="account_status" ng-change="candidate_account_status(account_status,account_id)" />
								</label>
								<input type="text" ng-model="account_id" class="form-control" style="background-color: white" READONLY/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Application ID</label>
								<input type="text" ng-model="application_id" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" ng-model="firstname" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label>Father's Name </label>
								<input type="text" ng-model="fname" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Surname</label>
								<input type="text" ng-model="lastname" class="form-control" style="background-color: white" readonly/>
							</div>
						</div>
					</div>
				</div>
					<!-- Single pro tab review Start-->
					<div class="single-pro-review-area mt-t-30 mg-b-15">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="product-payment-inner-st">
										<ul id="myTabedu1" class="tab-review-design">
											<li class="active"><a href="#ledger">Ledger</a></li>
											<li><a href="#selection">Selections</a></li>
											<li><a href="#challan">Challans</a></li>
										</ul>
										<div id="myTabContent" class="tab-content custom-product-edit">
											<div class="product-tab-list tab-pane fade active in" id="ledger">
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="asset-inner">
															<table class="table table-condesed">
																<thead>
																	<tr style="font-size: 9pt;">
																	<th><input type="checkbox" ng-model="LedgerCheckboxAll" ng-change="checkAll()" name="LedgerCheckboxAll" id="LedgerCheckboxAll" /></th>
																    <th>-</th>
																	<th>PROGRAM</th>
																	<th>CATEGORY</th>
																	<th>PART</th>
																	<th>SEMESTER</th>
																	<th>TYPE</th>
																	<th>CHALLAN NO</th>
																
																	<th class="bg-warning">CHALLAN AMOUNT</th>
																	<th class="bg-danger">DUES</th>
																	<th class="bg-primary">PAYABLE AMOUNT</th>
																	<th class="bg-danger">LATE FEE</th>
																	<th class="bg-success">PAID AMOUNT</th>
																	<th class="bg-danger">DUES</th>
																	<th>DATE</th>
																	<th>IS YES</th>
																	<th>IS MERIT</th>
																	<th>REMARKS</th>
																</thead>
																<tbody>
																	<tr ng-repeat="x in ledger  track by $index" style="font-size: 9pt;">
																	<td><input type="checkbox" ng-value="x.FEE_LEDGER_ID" ng-model="LedgerCheckbox" name="LedgerCheckbox" id="LedgerCheckbox" /></td>
																    <td><a href="javascript:void(0)"  ng-if="x.is_retain_hide" ng-model="generate_retain_challan" ng-click="produce_retain_challan(x, $index )"> Generate Retain Challan</a></td>
																	
																	<td>{{x.PROGRAM_TITLE}}</td>
																	<td>{{x.CATEGORY_NAME}}</td>
																	<td>{{x.PART_NAME}}</td>
																	<td>{{x.SEMESTER_NAME}}</td>
																	<td>{{x.CHALLAN_TITLE}}</td>
																	<td>{{x.CHALLAN_NO}}</td>
																
																	<td class="bg-warning" style="font-weight: bold; text-align: right">{{x.CHALLAN_AMOUNT}}</td>
																	<td class="bg-danger" style="font-weight: bold; text-align: right">{{x.DUES}}</td>
																	<td class="bg-primary" style="font-weight: bold; text-align: right">{{x.PAYABLE_AMOUNT}}</td>
																	<td class="bg-danger" style="font-weight: bold; text-align: right">{{x.LATE_FEE}}</td>
																	<td class="bg-success" style="font-weight: bold; text-align: right">{{x.PAID_AMOUNT}}</td>
																	<td class="bg-danger" style="font-weight: bold; text-align: right">{{(x.LATE_FEE*1 + x.PAYABLE_AMOUNT*1) - x.PAID_AMOUNT}}</td>
																	<td>{{x.DATE}}</td>
																	<td>
																		<!--										ng-checked="x.IS_YES=='Y'"-->
																		<input type="checkbox" ng-model="x.checkbox" ng-change="is_yes_change(x, $index )" />
																		<!--										<input type="checkbox" ng-model="is_yes" ng-value="x.IS_YES"   ng-change="is_yes_change({{x.FEE_LEDGER_ID}})" />-->
																		<!--										<input type="checkbox" [(ngModel)]="isChecked" (change)="checkValue(isChecked?'A':'B')" />-->
																	</td>
																	<td>
																		<input type="checkbox" ng-model="x.ismerit_checkbox" ng-change="is_merit_change(x, $index )" />
																	</td>
																	<td>{{x.REMARKS}}</td>
																	</tr>
																	<tr>
																	<th colspan="8" style="text-align: right">Grand Total</th>
																	<th colspan="1" style="text-align: right"></th>
																	<th colspan="1" style="text-align: right"></th>
																	<!--<th class="amt-total">{{ledgerSum.CHALLAN_AMOUNT}}</th>-->
																	<th class="amt-total">{{ledgerSum.PAYABLE_AMOUNT}}</th>
																	<th class="amt-total">{{ledgerSum.LATE_FEE}}</th>
																	<th class="amt-total">{{ledgerSum.PAID_AMOUNT}}</th>
																	<th class="amt-total">{{ledgerSum.DUES}}</th>
																	<th colspan="3"></th>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
											<div class="product-tab-list tab-pane fade" id="selection">
												<!--					SELECTION LIST TABLE-->
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="asset-inner">
															<table class="table table-condesed">
																<thead>
																<tr style="font-size: 9pt;">
																	<th>ACTIVE</th>
																	<th>ROLL NO</th>
																	<th>SL #</th>
																	<th>CAMPUS</th>
																	<th>PROGRAM TITLE</th>
																	<th>SHIFT</th>
																	<th>CATEGORY</th>
																	<th class="bg-primary">CH #</th>
																	<th class="bg-warning">LST #</th>
																	<th>TST CPN</th>
																	<th class="bg-danger">M.L CPN</th>
																	<th>REMARKS</th>
																	<th>STATUS</th>
																</thead>
																<tbody>
																<tr ng-repeat="list in selection_list  track by $index" style="font-size: 9pt;">
																	<td>
																		<input type="checkbox" ng-model="list.PAID_FEE" ng-change="transfer_fee(list, $index )" />
																	</td>
																	<td><b class="text-danger">{{list.ROLL_NO}}</b></td>
																	<td>{{list.SELECTION_LIST_ID}}</td>
																	<td>{{list.NAME}}</td>
																	<td><b>{{list.PROGRAM_TITLE}}</b></td>
																	<td>{{list.SHIFT_NAME}}</td>
																	<td><b>{{list.CATEGORY_NAME}}</b></td>
																	<td class="bg-primary">{{list.CHOICE_NO}}</td>
																	<td class="bg-warning">{{list.LIST_NO}}</td>
																	<td class="text-danger">{{list.TEST_CPN}}</td>
																	<td class="bg-danger">{{list.CPN_MERIT_LIST}}</td>
																	<td><input type='text' class='form-control' ng-model="remarks" ng-value="list.REMARKS"></td>
																	<td>
																		<input type="checkbox" ng-model="list.checkbox" ng-change="change_selection_status(remarks,list, $index )" />
																	</td>
																</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
											<div class="product-tab-list tab-pane fade" id="challan">
												<!--					CHALLAN TABLE-->

												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="asset-inner">
															<table class="table table-condesed">
																<thead>
																<tr style="font-size: 9pt;">
																    <th>PROGRAM TITLE</th>
																    <th>CATEGORY</th>
																    <th>SHIFT</th>
																	<th>PART</th>
																	<th>SEMESTER</th>
																	<th>CHALLAN TYPE</th>
																	<th>REMARKS</th>
																	<th>CHALLAN #</th>
																	<!--<th>CAMPUS</th>-->
																	<th class="bg-warning">CHALLAN AMT</th>
																	<th class="bg-warning">DUES</th>
																		<th class="bg-warning">LATE FEE</th>
																	<th class="bg-danger">PAYABLE AMT</th>
																	<th>DUE DATE</th>
																	<th>FEE CHALLAN</th>
																	<th>MODIFY</th>
																	
																</tr>
																</thead>
																<tbody>
																<tr ng-repeat="challans in challanInfo  track by $index"  style="font-size: 9pt;">
																	<td><b>{{challans.PROFILE.PROGRAM_TITLE}}</b></td>
																	<td><b>{{challans.PROFILE.CATEGORY_NAME}}</b></td>
																	<td>{{challans.PROFILE.SHIFT_NAME}}</td>
																	<td>{{challans.FEE_CHALLAN.PART_NAME}}</td>
																	<td>{{challans.FEE_CHALLAN.SEMESTER_NAME}}</td>
																	<td>{{challans.FEE_CHALLAN.CHALLAN_TITLE}}</td>
																	<td>{{challans.FEE_CHALLAN.REMARKS}}</td>
																	<td ng-class="{ 'active-bg': challans.FEE_CHALLAN.ACTIVE == 0}">{{challans.FEE_CHALLAN.CHALLAN_NO}}</td>
																	<!--<td>{{challans.PROFILE.NAME}}</td>-->
																	
																	<td class="bg-warning" style="font-weight: bold; text-align: right">{{challans.FEE_CHALLAN.CHALLAN_AMOUNT}}</td>
																	<td class="bg-warning" style="font-weight: bold; text-align: right">{{challans.FEE_CHALLAN.DUES}}</td>
																	
																	<td class="bg-warning" style="font-weight: bold; text-align: right">{{challans.FEE_CHALLAN.LATE_FEE}}</td>
																	<td class="bg-danger" style="font-weight: bold; text-align: right">{{challans.FEE_CHALLAN.PAYABLE_AMOUNT*1+challans.FEE_CHALLAN.LATE_FEE*1}}</td>
																	<td class="text-danger" style="font-weight: bold; text-align: center">{{challans.FEE_CHALLAN.DUE_DATE}}</td>
																	<td class=""><a href={{challans.URL_INFO}} target='_blank'>Admission</a></td>
																	<td class=""><a href='javascript:void(0)' ng-click="editChallan(challans)">Modify/ Pay Challan</a></td>

																</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Customwidth-popup-WarningModal  fade-->
					<div id="editChallanWindow" class="modal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header header-color-modal bg-color-3">
									<h4 class="modal-title">Edit/ Pay Challan</h4>
									<div class="modal-close-area modal-close-df">
										<a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
									</div>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<h3>Challan Information</h3>
											<table class="table table-condensed">
												<tr><th>Challan No</th><td style="background-color: black;color: white;font-weight: bold; text-align: center">{{modifyChallan.FEE_CHALLAN.CHALLAN_NO}}</td></tr>
												<tr><th>Program Title</th><td>{{modifyChallan.PROFILE.PROGRAM_TITLE}}</td></tr>
												<tr><th>Category</th><td>{{modifyChallan.PROFILE.CATEGORY_NAME}}</td></tr>
												<tr><th>Shift</th><td>{{modifyChallan.PROFILE.SHIFT_NAME}}</td></tr>
												<tr><th>Part</th><td>{{modifyChallan.FEE_CHALLAN.PART_NAME}}</td></tr>
												<tr><th>Semester</th><td>{{modifyChallan.FEE_CHALLAN.SEMESTER_NAME}}</td></tr>
											</table>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
											<div style="border: 1px solid black; padding: 10px">
											<h3>Modify Challan</h3>

											<div class="form-group-inner">
												<label>Challan Amount <span class="text-danger"><em>Editable</em></span></label>
												<input type="text" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.CHALLAN_AMOUNT" ng-change="calculateAmountChallan()" ng-value="modifyChallan.FEE_CHALLAN.CHALLAN_AMOUNT"/>
											</div>
											<div class="form-group-inner">
												<label>DUES <span class="text-danger"><em></em></span></label>
												<input readonly type="text" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.DUES" ng-value="modifyChallan.FEE_CHALLAN.DUES"/>
											</div>
										
											<div class="form-group-inner">
												<label>Payable Amount <span class="text-danger"><em>Editable</em></span></label>
												<input type="text" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT" ng-value="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT" readonly/>
											</div>
												<div class="form-group-inner">
												<label>Late Fees <span class="text-danger"><em>Editable</em></span></label>
												<input type="text" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.LATE_FEE" ng-value="modifyChallan.FEE_CHALLAN.LATE_FEE"/>
											</div>
											<div class="form-group-inner">
												<label>Valid Upto <span class="text-danger">yyyy-mm-dd</span></label>
												<input type="text" ng-model="modifyChallan.FEE_CHALLAN.VALID_UPTO" ng-value="modifyChallan.FEE_CHALLAN.VALID_UPTO" class="form-control"/>
											</div>
											<div class="form-group-inner">
												<label>ACTIVE </label>
												<input type="text" ng-model="modifyChallan.FEE_CHALLAN.ACTIVE" ng-value="modifyChallan.FEE_CHALLAN.ACTIVE" class="form-control"/>
											</div>
											<button ng-click="saveChallanChanges()" class="btn btn-sm- btn-primary">Save Changes</button>
											</div>
										</div>

										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div style="border: 1px solid black; padding: 10px">
											<h3>Mark Paid</h3>
											<div class="form-group-inner">
												<label>Receivable Amount</label>
												<input type="text" readonly style="text-align: center;background-color: black; color: white;font-weight: bold" class="form-control" ng-model="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT" ng-value="modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT"/>
											</div>
											<div class="form-group-inner">
												<label>Paid Amount</label>
												<input type="text" ng-init="balance=0"  ng-keyup="balance = modifyChallan.FEE_CHALLAN.PAYABLE_AMOUNT - modifyChallan.FEE_CHALLAN.PAID_AMOUNT" ng-model="modifyChallan.FEE_CHALLAN.PAID_AMOUNT" class="form-control"/>
											</div>
											<div class="form-group-inner">
												<label>Balance</label>
												<input type="text" ng-value="balance" class="form-control" style="text-align: center;background-color: red; color: white;font-weight: bold" readonly/>
											</div>
											<div class="form-group-inner">
												<label>Paid Date <span class="text-danger">yyyy-mm-dd</span></label>
												<input type="text" ng-model="modifyChallan.FEE_CHALLAN.PAID_DATE" ng-value="modifyChallan.FEE_CHALLAN.PAID_DATE" class="form-control"/>
											</div>
											<button ng-click="markChallanPaid()" class="btn btn-sm- btn-warning">Mark Paid</button>
										</div>
										</div>
									</div>

								</div>
								<div class="modal-footer warning-md">
									<span class="text-danger text-left">{{ChallanError}}</span>
									<a data-dismiss="modal" href="#">Cancel</a>

								</div>
							</div>
						</div>
					</div>

					</div>
			</div>
		</div>
</body>
</html>
