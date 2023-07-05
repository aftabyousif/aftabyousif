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
					<h1>Roll No Reports</h1>
				</div>
			</div>
			<div ng-app="myApp" ng-controller="formCtrl">
				<div class='row'>
					<div class='col-md-6'>

						<div class="col-md-4">
							<label>Session</label>
							<select id="session_id" ng-model="SessionModel"  class="form-control">
								<option ng-repeat="session in sessions" ng-value="{{session.SESSION_ID}}">{{session.YEAR}} {{session.BATCH_REMARKS}}</option>
							</select>
						</div>

						<div class="col-md-4">
							<label>Program Type</label>
							<select id="program_type_id" ng-model="ProgramTypesModel" class="form-control" ng-change="getPart(ProgramTypesModel)">
								<option ng-repeat="types in ProgramTypes" ng-value="{{types.PROGRAM_TYPE_ID}}">{{types.PROGRAM_TITLE}}</option>
							</select>
						</div>

						<div class="col-md-4">
							<label>Shifts</label>
							<select id="shift_id" ng-model="ShiftsModel" class="form-control">
								<option ng-repeat="shift in shifts" ng-value="{{shift.SHIFT_ID}}">{{shift.SHIFT_NAME}}</option>

							</select>
						</div>

						<div class="col-md-8">
							<label>Campus</label>
							<select id="campus_id" ng-model="CampusModel" class="form-control" ng-change="getPrograms(ProgramTypesModel,ShiftsModel,CampusModel);">
								<option ng-repeat="campus in campuses" ng-value="{{campus.CAMPUS_ID}}">{{campus.NAME}}</option>
							</select>
						</div>
					
					<div class="col-md-4">
						<label>Part</label>
						<select id="part_id" ng-model="PartModel" class="form-control">
							<option ng-repeat="part in parts" ng-value="{{part.PART_ID}}">{{part.NAME}} {{part.REMARKS}}</option>
						</select>
					</div>

						<div class="col-md-12">
						<div class="input-group">
							<br/>
							<!--<button type="button" class="btn btn-custon-rounded-two btn-primary" ng-click="PrintDocument(RollNoData)"><i class="fa fa-print"></i> Print Report</button>-->
						    <!--<button type="button" class="btn btn-custon-rounded-two btn-success" ng-click="PrintDocumentWithContact(RollNoData)"><i class="fa fa-print"></i> Print Report With Contact Details</button>-->
							<!--<button type='button'class="btn btn-custon-rounded-two btn-warning" ng-click=download(RollNoData)>Download CSV Report</button>-->
							</div>
						</div>
						<!--<div class="row">-->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="login-social-inner">
                                <a href="#" class="button btn-social basic-ele-mg-b-10 facebook span-left" ng-click="PrintDocument(RollNoData)">
                                    <span>
                                        <i class="fa fa-print"></i>
                                    </span> Roll No Report 
                                </a>
                                <a href="#" class="button btn-social basic-ele-mg-b-10 twitter span-left" ng-click="PrintDocumentWithContact(RollNoData)">
                                    <span>
                                        <i class="fa fa-phone"></i>
                                    </span> Contact Details 
                                </a>
                                <br>
                                <a href="#" class="button btn-social basic-ele-mg-b-10 googleplus span-left" ng-click="download(RollNoData)">
                                    <span>
                                        <i class="fa fa-file-excel-o"></i>
                                    </span> CSV Report 
                                </a>
                                <a href="#" class="button btn-social basic-ele-mg-b-10 googleplus span-left" ng-click="printIDCard(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel,PartModel)">
                                    <span>
                                        <i class="fa fa-print"></i>
                                    </span> Print Id Card 
                                </a>
                            </div>
                        </div>
                        <!--</div>-->

					</div>
					<div class='col-md-6'>
						<label>Program List</label>
						<span class="text-danger" ng-model="pNoReportMsg"></span>
						<select id="prog_list_id" ng-model="ProgramModel" ng-change="getCandidates(ProgramTypesModel,ShiftsModel,CampusModel,ProgramModel,SessionModel,PartModel)" class="form-control" style='height:200px' multiple>
							<option ng-repeat="program in programs" ng-value="{{program.PROG_ID}}">{{program.PROGRAM_TITLE}}</option>
						</select>

					</div>
				</div>
				<br>

				<div class="button-style-two btn-mg-b-10">
					<button type="button" class="btn btn-custon-rounded-two btn-warning" onclick="location.reload();"><i class="fa fa-refresh edu-checked-pro" aria-hidden="true"></i> Reset Panel</button>
					<span id="loading"></span>
					<span class="text-danger">{{errorMsg}}</span>
				</div>
				<div class="asset-inner">
					<table class="table table-hover table-condesed">
						<tbody >
						<tr>
							<td colspan="2">{{getCandidateReportData.CAMPUS_NAME}}</td>
						</tr>

						<tr ng-repeat="dataRecord in getCandidateReportData.DATA track by $index" style='font-size: 11pt; font-family: "Times New Roman", serif'>
						<td>{{dataRecord.PROGRAM_TITLE}}</td>

							<TD>
								<table class="table table-hover table-condesed">
									<tr>
										<th>SEAT#</th>
										<th>APPLICATION ID</th>
										<th>CNIC NO</th>
										<th>NAME</th>
										<th>FATHER'S NAME</th>
										<th>SURNAME</th>
										<TH>DISTRICT NAME</TH>
										<TH>U_R</TH>
										<TH>CATEGORY</TH>
										<th>PROGRAM TITLE</th>
										<th>ROLL NO.</th>
										<TH>AMOUNT</TH>
									</tr>
									<tr ng-repeat="z in dataRecord.STD_DATA ">
										<td>{{z.CARD_ID}}</td>
										<td>{{z.APPLICATION_ID}}</td>
										<td>{{z.CNIC_NO}}</td>
										<td>{{z.FIRST_NAME}}</td>
										<td>{{z.FNAME}}</td>
										<td>{{z.LAST_NAME}}</td>
										<td>{{z.DISTRICT_NAME}}</td>
										<td>{{z.U_R}}</td>
										<td>{{z.CATEGORY_NAME}}</td>
										<td>{{z.PROGRAM_TITLE}}</td>
										<td class=""><a href="<?=base_url()?>/StudentIDCard/idcardpaper?roll_no='{{z.ROLL_NO}}'&p_id={{z.PART_ID}}&sh_id={{z.SHIFT_ID}}&pl_id={{z.PROG_LIST_ID}}&c_id={{z.CAMPUS_ID}}&pt_id={{z.PROGRAM_TYPE_ID}}&s_id={{z.SESSION_ID}}" ng-click="printSingleIDCard(PartModel,{{z.ROLL_NO}})" target="_blank">{{z.ROLL_NO}}</a></td>
										<!-- <td>{{z.ROLL_NO}}</td> -->
										<td>{{z.PAID_AMOUNT}}</td>
									</tr>

								</table>
							</TD>


						</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
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
		$scope.getCandidates= function (program_type_id,shift_id,campus_id,program_id,session_id,part_id){
        // alert(part_id);
			$scope.pNoReportMsg = null;
			$scope.TotalSeats = null;
			$scope.FilledSeats = null;
			$scope.VacantSeats = null;
			$scope.DisplayCategories = null;
			$scope.ProgramSeats = null;
			$scope.getCandidateReportData = null
			$scope.RollNoData = null;

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
			}else if (part_id == null){
				$scope.pNoReportMsg = "Select Part";
				return;
			}

			// $scope.errorMsg = "Getting Records....";
			$scope.program_id = angular.toJson(program_id)
			// $scope.errorMsg=null;
			let data = {program_type_id:program_type_id,shift_id:shift_id,campus_id:campus_id,session_id:session_id,program_ids:program_id,part_id:part_id};
			$http.post('<?=base_url()?>RollNo/getRollNoReportHandler',data).then(function success(response){

				if (response.status == 204 ){
					$scope.pNoReportMsg= 'Sorry no data found';
				}
				if (response.status == 200 ){
					$scope.pNoReportMsg=null;
					let array_data = response.data;
				// 	console.log(array_data);
					$scope.getCandidateReportData = array_data;
					$scope.RollNoData = array_data;
				}
			},function error(response){
				// console.log(response);
			});

		}
		$scope.PrintDocument	= function (RollNoData){
			var $popup = $window.open("rollNoReportPrint", "popup", "width=1300,height=800,left=40,top=150");
			$popup.Name = RollNoData;
			// console.log(programSeats);
		}
		$scope.PrintDocumentWithContact	= function (RollNoData){
			var $popup = $window.open("rollNoReportWithContact", "popup", "width=1300,height=800,left=40,top=150");
			$popup.Name = RollNoData;
			// console.log(programSeats);
		}
		$scope.download	= function (RollNoData){
		  //  console.log(RollNoData);
		    let campus_name = RollNoData['CAMPUS_NAME'];
		    let part = RollNoData['PART_NAME']
		    let shift_name = RollNoData['SHIFT_NAME']
		    let year = RollNoData['YEAR']
		    let data = RollNoData['DATA'];
		    let csvString = '';
	
		    angular.forEach(data,function(value,key){
		        let program_title = value['PROGRAM_TITLE'];
		        let std_data      = value['STD_DATA'];
		        
		            csvString = csvString+"\n"
		            csvString = csvString+"CAMPUS:"+",";
		            csvString = csvString+""+campus_name;
		            csvString = csvString+"\n"
		    
		            csvString = csvString+"PROGRAM TITLE:"+",";
		            csvString = csvString+""+program_title+",";
		            csvString = csvString+"YEAR:"+",";
		            csvString = csvString+""+year+",";
		            csvString = csvString+"PART:"+",";
		            csvString = csvString+""+part+",";
		            csvString = csvString+"SHIFT:"+",";
		            csvString = csvString+""+shift_name;
		            
		            csvString = csvString+"\n";
		            csvString = csvString+"\n"
		            
		            csvString = csvString+"SNO,APPLICATION ID,ACCOUNT ID,NAME,FATHER'S NAME,SURNAME,MOBILE NO,EMAIL,DISTRICT,AREA,CATEGORY,AMOUNT,ROLL NO\n";
		            
		            let sno=0;
		    
		    angular.forEach(std_data,function(value2,key2){
		        sno++;
		       csvString = csvString+sno+",";
		       csvString = csvString+value2['APPLICATION_ID']+",";
		       csvString = csvString+value2['ACCOUNT_ID']+",";
		       csvString = csvString+value2['FIRST_NAME']+",";
		       csvString = csvString+value2['FNAME']+",";
		       csvString = csvString+value2['LAST_NAME']+",";
		       csvString = csvString+value2['MOBILE_NO']+",";
		       csvString = csvString+value2['EMAIL']+",";
		       csvString = csvString+value2['DISTRICT_NAME']+",";
		       csvString = csvString+value2['U_R']+",";
		       csvString = csvString+value2['CATEGORY_NAME']+",";
		       csvString = csvString+value2['PAID_AMOUNT']+",";
		       csvString = csvString+value2['ROLL_NO']+"\n";
		 
		    })
		         
		    });
		  //  console.log(csvString);
		  //  return;
		  
	            var a = $('<a/>', {
		            style:'display:none',
		            href:'data:application/octet-stream;base64,'+btoa(csvString),
		            download:'rollNoReport.csv'
		        }).appendTo('body')
		        a[0].click()
		        a.remove();
		}
		$scope.getPart 	= function (program_type_id){

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
		
  		$scope.getProgramTypes();
		$scope.getSessions();
		$scope.getShifts();
		$scope.getCampus();
	  $scope.printIDCard = function (program_type_id,shift_id,campus_id,prog_list_id,session_id,part_id){
	      	$scope.errorMsg = null;
	  // let session_id =  $('session_id').val();
	   if (session_id == null){
				$scope.errorMsg = "Select Session";
				return;
			}
		
	   //let prog_list_id =  $('prog_list_id').val();
	   	if (prog_list_id == null){
				$scope.errorMsg = "Select Program";
				return;
			}
	  // let part_id =  $('part_id').val();
	   	if (part_id == null){
				$scope.errorMsg = "Select Part";
				return;
			}
	   //let campus_id =  $('campus_id').val();
	   	if (campus_id == null){
				$scope.errorMsg = "Select Campus";
				return;
			}
	   //let shift_id =  $('shift_id').val();
	   	if (shift_id == null){
				$scope.errorMsg = "Select Shift";
				return;
			}
	   //let program_type_id =  $('program_type_id').val();
	   	if (program_type_id == null){
				$scope.errorMsg = "Select Program Type";
				return;
			}
			var prog_list_Str = encodeURIComponent(JSON.stringify(prog_list_id));
			console.log(prog_list_Str);
			query_string = "s_id="+session_id+"&pt_id="+program_type_id+"&sh_id="+shift_id+"&c_id="+campus_id+"&p_id="+part_id+"&pl_id="+prog_list_Str
			
		    window.open('<?=base_url()?>/StudentIDCard/idcardpaper?'+query_string, "_blank")	
	   
	  }
	  $scope.printSingleIDCard = function (part_id,roll_no){
	      	$scope.errorMsg = null;
	   	if (part_id == null){
				$scope.errorMsg = "Select Part";
				return;
		}
		query_string = "p_id="+part_id+"&r_no="+roll_no
		console.log(query_string);
		window.open('<?=base_url()?>/StudentIDCard/idcardpaper?'+query_string, "_blank")
	  }
	});
	

</script>
