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
	</style>
</head>
<body>
<div class="product-status mg-b-15" id="min-height">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

				<div class="product-status-wrap">
					<h4>Logs of fee challans while importing to candidate account</h4>
					<div class="table-responsive">
						<div class="asset-inner">
						<table class="">
							<tr>
								<th style="text-align: center">Log File</th>
<!--								<th>Action</th>-->
							</tr>
							<?php
							foreach ($log_files as $log_file):
							?>
							<tr>
								<td>
									<a href="<?=base_url().FEE_LEDGER_LOG_FOLDER.'/'.$log_file?>"><?=$log_file?></a>
								</td>
							</tr>
							<?php
							endforeach;
							?>

						</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--<hr />-->
<!--<div id="dvExcel"></div>-->
<script type="text/javascript" src="<?=base_url()?>dash_assets/xls-import/1-8-3-jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>dash_assets/xls-import/xlsx.full.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>dash_assets/xls-import/jszip.js"></script>
<script type="text/javascript">
	let records = "";
	$("body").on("click", "#upload", function () {
		//Reference the FileUpload element.
		var fileUpload = $("#fileUpload")[0];

		//Validate whether File is valid Excel file.
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
		if (regex.test(fileUpload.value.toLowerCase())) {
			if (typeof (FileReader) != "undefined") {
				var reader = new FileReader();

				//For Browsers other than IE.
				if (reader.readAsBinaryString) {
					reader.onload = function (e) {
						ProcessExcel(e.target.result);
					};
					reader.readAsBinaryString(fileUpload.files[0]);
				} else {
					//For IE Browser.
					reader.onload = function (e) {
						var data = "";
						var bytes = new Uint8Array(e.target.result);
						for (var i = 0; i < bytes.byteLength; i++) {
							data += String.fromCharCode(bytes[i]);
						}
						ProcessExcel(data);
					};
					reader.readAsArrayBuffer(fileUpload.files[0]);
				}
			} else {
				alert("This browser does not support HTML5.");
			}
		} else {
			alert("Please upload a valid Excel file.");
		}
	});
	function ProcessExcel(data) {
		//Read the Excel File data.
		var workbook = XLSX.read(data, {
			type: 'binary'
		});

		//Fetch the name of First Sheet.
		var firstSheet = workbook.SheetNames[0];

		//Read all rows from First Sheet into an JSON array.
		var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);

		//Create a HTML Table element.
		// let table = "<tbody/>";

		// var table = $("<table />");
		// table[0].border = "1";

		//Add the header row.
		// var row = $(table[0].insertRow(-1));

		//Add the header cells.
		// var headerCell = $("<th />");
		// headerCell.html("Id");
		// row.append(headerCell);

		// var headerCell = $("<th />");
		// headerCell.html("Name");
		// row.append(headerCell);

		// var headerCell = $("<th />");
		// headerCell.html("Country");

		// row.append(headerCell);

		//Add the data rows from Excel file.
		records = null;
		records = excelRows;
		// console.log(excelRows);
		let row = "";
		let sno = 0;
		for (var i = 0; i < excelRows.length; i++) {
			sno++;
			//Add the data row.
			// var row = $(table[0].insertRow(-1));
			row+="<tr>";

			let Branch_Code = $.trim(excelRows[i].BRANCH_CODE);
			let Branch_Name = $.trim(excelRows[i].BRANCH_NAME);
			let Deposit_Slip_No = $.trim(excelRows[i].DEPOSIT_SLIP_NO);
			let Collection_Date = $.trim(excelRows[i].COLLECTION_DATE);
			let Mode_of_Payment = $.trim(excelRows[i].MODE_OF_PAYMENT);
			let Instrument_No = $.trim(excelRows[i].INSTRUMENT_NO);
			let Amount = $.trim(excelRows[i].AMOUNT);
			let Credit_Date = $.trim(excelRows[i].CREDIT_DATE);
			let Challan_No = $.trim(excelRows[i].CHALLAN_NO);
			let Challan_No_Description = $.trim(excelRows[i].CANDIDATE_NAME);
			let father_name = $.trim(excelRows[i].FNAME);
			let BATCH_ID = $.trim(excelRows[i].ROLL_NO);
			let Program_Class = $.trim(excelRows[i].PROGRAM);
			let campus_name = $.trim(excelRows[i].CAMPUS_NAME);

			if (Instrument_No == null || isNaN(Instrument_No)) Instrument_No='-';

			row+="<td>"+sno+"</td>";
			row+="<td>"+Branch_Code+"</td>";
			row+="<td>"+Branch_Name+"</td>";
			row+="<td>"+Deposit_Slip_No+"</td>";
			row+="<td>"+Collection_Date+"</td>";
			row+="<td>"+Mode_of_Payment+"</td>";
			row+="<td>"+Instrument_No+"</td>";
			row+="<td>"+Amount+"</td>";
			row+="<td>"+Credit_Date+"</td>";
			row+="<td>"+Challan_No+"</td>";
			row+="<td>"+Challan_No_Description+"</td>";
			row+="<td>"+father_name+"</td>";
			row+="<td>"+BATCH_ID+"</td>";
			row+="<td>"+Program_Class+"</td>";
			row+="<td>"+campus_name+"</td>";
			row+= "</tr>";
		}

		var dvExcel = $("#dvExcel");
		dvExcel.html("");
		dvExcel.append(row);
	};

	function import_db(){

		let bank_account_id = $("#bank_account_id").val();
		let start_date 		= $("#start_date").val();
		let end_date 		= $("#end_date").val();

		if (bank_account_id == "" || isNaN(bank_account_id)) {
			$('#msg').html("<p class='text-center text-danger'>Please select bank account</p>");
			return
		}else if (start_date == "") {
			$('#msg').html("<p class='text-center text-danger'>Please select start date</p>");
			return
		}else if (end_date == ""){
			$('#msg').html("<p class='text-center text-danger'>Please select end date</p>");
			return
		}

		$('#msg').html("");
		// console.log(records);

		if (confirm("Do you want to import?") === false)
			return;
		// $("#selected_programs").empty();
		$('#msg').html("<p class='text-center'> Processing please wait....</p>");
		$.ajax({
			url:'<?=base_url()?>Cmd/import_ledger_handler',
			type: 'POST',
			data: {bank_account_id:bank_account_id,start_date:start_date,end_date:end_date},
			// dataType: 'json',
			success: function (data) {
				$('#msg').html("<p class='text-center'>"+data+"</p>");
			},
		});
	}

	function search_record(){

		let bank_account_id = $("#bank_account_id").val();
		let start_date 		= $("#start_date").val();
		let end_date 		= $("#end_date").val();

		$('#msg').html("<p class='text-center'> Processing please wait....</p>");
		$.ajax({
			url:'<?=base_url()?>Cmd/getCmdRecord',
			type: 'POST',
			data: {bank_account_id:bank_account_id,start_date:start_date,end_date:end_date},
			dataType: 'json',
			success: function (data) {
				$('#msg').html("");
				console.log(data)
				let row = "";
				let sno=0;
				$.each(data,function (k,v){
					sno++;
					let AMOUNT = v['AMOUNT'];
					let BRANCH_CODE = v['BRANCH_CODE'];
					let BRANCH_NAME = v['BRANCH_NAME'];
					let CAMPUS_NAME = v['CAMPUS_NAME'];
					let CANDIDATE_NAME = v['CANDIDATE_NAME'];
					let CHALLAN_NO = v['CHALLAN_NO'];
					let COLLECTION_DATE = v['COLLECTION_DATE'];
					let CREDIT_DATE = v['CREDIT_DATE'];
					let DEPOSIT_SLIP_NO = v['DEPOSIT_SLIP_NO'];
					let FNAME = v['FNAME'];
					let INSTRUMENT_NO = v['INSTRUMENT_NO'];
					let MODE_OF_PAYMENT = v['MODE_OF_PAYMENT'];
					let PROGRAM = v['PROGRAM'];
					let ROLL_NO = v['ROLL_NO'];

					row+="<tr>";
					row+="<td>"+sno+"</td>";
					row+="<td>"+BRANCH_CODE+"</td>";
					row+="<td>"+BRANCH_NAME+"</td>";
					row+="<td>"+DEPOSIT_SLIP_NO+"</td>";
					row+="<td>"+COLLECTION_DATE+"</td>";
					row+="<td>"+MODE_OF_PAYMENT+"</td>";
					row+="<td>"+INSTRUMENT_NO+"</td>";
					row+="<td>"+AMOUNT+"</td>";
					row+="<td>"+CREDIT_DATE+"</td>";
					row+="<td>"+CHALLAN_NO+"</td>";
					row+="<td>"+CANDIDATE_NAME+"</td>";
					row+="<td>"+FNAME+"</td>";
					row+="<td>"+ROLL_NO+"</td>";
					row+="<td>"+PROGRAM+"</td>";
					row+="<td>"+CAMPUS_NAME+"</td>";
					row+= "</tr>";
				});

				var dvExcel = $("#dvExcel");
				dvExcel.html("");
				dvExcel.append(row);
			},
		});
	}

	$("#fetch").click(function (){
		search_record();
	});
	$("#import_xls").click(function (){
		import_db();
	});
</script>
</body>
</html>
