<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 4/20/2022
 * Time: 9:30 PM
 */
?>
<body>
<div class="product-status mg-b-15" id="min-height">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="product-status-wrap">
                    <h4>Import Fee Challan Amount in Candidate Accounts</h4>
                    <div class="row">
                        <div class="col-md-3"><a  href="<?=base_url('assets/sample template.xls')?>" target='_blank' class="btn btn-info">Download Sample Template File</a></div>
                        <div class="col-md-3">  <input type="file" name="cmd" id="fileUpload" accept="xls|xlxs"></div>
                        <div class="col-md-3">  <input type="button" class="btn btn-primary" id="upload" value="Upload File"></div>
                        <div class="col-md-3"><button  onclick="processFile()" class="btn btn-success">Process File</button></div>
                        
                    </div>

                    <hr>
                    <br>
                    <br>
                    <br>
                    <hr>
                    <div class="asset-inner">
                        <div class="row">
                            <div class="col-md-4"><h1>Valid Challan Number For Update</h1></div>
                            <div class="col-md-4"> <button onclick="importdb()" class="btn btn-danger">Import Into Database</button></div>
                            <div class="col-md-4"><button onclick="download_valid_challan()" class="btn btn-info">Download Data</button></div>
                        </div>


                        <table>
                            <thead id="table-head">
                            <tr><th>S #</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Deposit Slip No</th>
                                <th>Collection Date</th>
                                <th>Mode of Payment</th>
                                <th>Instrument No</th>
                                <th>Amount</th>
                                <th>Credit Date</th>
                                <th>Challan No</th>
                                <th>Candidate Name</th>
                                <th>Father's Name</th>
                                <th>Batch ID</th>
                                <th>Program/Class</th>
                                <th>Campus Name</th><tr/>
                            </thead>
                            <tbody id="vcnfu"></tbody>
                        </table>
                    </div>

                    <br>
                    <br>
                    <br>
                    <hr>
                    <div class="asset-inner">

                        <div class="row">
                            <div class="col-md-4"><h1>Invalid Challan Number</h1></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button onclick="download_invalid_challan()" class="btn btn-success">Download Data</button></div>
                        </div>

                        <table>
                            <thead id="table-head">
                            <tr><th>S #</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Deposit Slip No</th>
                                <th>Collection Date</th>
                                <th>Mode of Payment</th>
                                <th>Instrument No</th>
                                <th>Amount</th>
                                <th>Credit Date</th>
                                <th>Challan No</th>
                                <th>Candidate Name</th>
                                <th>Father's Name</th>
                                <th>Batch ID</th>
                                <th>Program/Class</th>
                                <th>Campus Name</th><tr/>
                            </thead>
                            <tbody id="ivcnfu"></tbody>
                        </table>
                    </div>

                    <br>
                    <br>
                    <br>
                    <hr>
                    <div class="asset-inner">

                        <div class="row">
                            <div class="col-md-4"><h1>Invalid Challan Number Not Found In Database</h1></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button onclick="download_not_found_challan()" class="btn btn-warning">Download Data</button></div>
                        </div>

                        <table>
                            <thead id="table-head">
                            <tr><th>S #</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Deposit Slip No</th>
                                <th>Collection Date</th>
                                <th>Mode of Payment</th>
                                <th>Instrument No</th>
                                <th>Amount</th>
                                <th>Credit Date</th>
                                <th>Challan No</th>
                                <th>Candidate Name</th>
                                <th>Father's Name</th>
                                <th>Batch ID</th>
                                <th>Program/Class</th>
                                <th>Campus Name</th><tr/>
                            </thead>
                            <tbody id="ivcnnfd"></tbody>
                        </table>
                    </div>

                    <br>
                    <br>
                    <br>
                    <hr>
                    <div class="asset-inner">

                        <div class="row">
                            <div class="col-md-4"><h1>Valid Challan Number Already Update</h1></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button  onclick="download_already_verified_challan()" class="btn btn-primary">Download Data</button></div>
                        </div>

                        <table>
                            <thead id="table-head">
                            <tr><th>S #</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Deposit Slip No</th>
                                <th>Collection Date</th>
                                <th>Mode of Payment</th>
                                <th>Instrument No</th>
                                <th>Amount</th>
                                <th>Credit Date</th>
                                <th>Challan No</th>
                                <th>Candidate Name</th>
                                <th>Father's Name</th>
                                <th>Batch ID</th>
                                <th>Program/Class</th>
                                <th>Campus Name</th><tr/>
                            </thead>
                            <tbody id="vcnau"></tbody>
                        </table>
                    </div>

                    <div id="msg"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script type="text/javascript" src="<?=base_url()?>dash_assets/xls-import/xlsx.full.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>dash_assets/xls-import/jszip.js"></script>
<script type="text/javascript">
    let records = [];
   let PAID_CHALLAN= NOT_FOUND_CHALLAN= INVALID_CHALLAN_NO= FOUND_CHALLAN=[];

    $("body").on("click", "#upload", function () {
        //Reference the FileUpload element.
        console.log("hello");
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

        PAID_CHALLAN= NOT_FOUND_CHALLAN= INVALID_CHALLAN_NO= FOUND_CHALLAN=[];
        view_table(FOUND_CHALLAN,"vcnfu");
        view_table(INVALID_CHALLAN_NO,"ivcnfu");
        view_table(NOT_FOUND_CHALLAN,"ivcnnfd");
        view_table(PAID_CHALLAN,"vcnau");
        records = [];
        records = excelRows;
        return records;
    };
    function processFile(){
     
        jQuery.ajax({
            url: "<?=base_url();?>Cmd/getChallanVerify",
            type: "POST",
            enctype: 'json',
            processData: false,
            contentType: false,
            data: JSON.stringify(records),
            success: function (data, status) {
                //console.log(data);
                FOUND_CHALLAN=data.FOUND_CHALLAN;
                INVALID_CHALLAN_NO=data.INVALID_CHALLAN_NO;
                NOT_FOUND_CHALLAN=data.NOT_FOUND_CHALLAN;
                PAID_CHALLAN=data.PAID_CHALLAN;
                view_table(FOUND_CHALLAN,"vcnfu");
                view_table(INVALID_CHALLAN_NO,"ivcnfu");
                view_table(NOT_FOUND_CHALLAN,"ivcnnfd");
                view_table(PAID_CHALLAN,"vcnau");
            },
            beforeSend:function (data, status) {

            },
            error:function (data, status) {

            },
        });
    }
    function view_table(list_of_data,table_id){
        $('#'+table_id).html();
        for(let  i = 0 ; i<list_of_data.length;i++){
            let row = "<tr>"+
             "<td>"+(i+1)+"</td>"+
            "<td>"+list_of_data[i].BRANCH_CODE+"</td>"+
            "<td>"+list_of_data[i].BRANCH_NAME+"</td>"+
            "<td>"+list_of_data[i].DEPOSITE_SLIP_NO+"</td>"+
            "<td>"+list_of_data[i].COLLECTION_DATE+"</td>"+
            "<td>"+list_of_data[i].MODE_OF_PAYMENT+"</td>"+
            "<td>"+list_of_data[i].INSTRUMENT_NO+"</td>"+
            "<td>"+list_of_data[i].AMOUNT+"</td>"+
            "<td>"+list_of_data[i].CREDIT_DATE+"</td>"+
            "<td>"+list_of_data[i].DEPOSITE_SLIP_NO+"</td>"+
            "<td>"+list_of_data[i].CANDIDATE_NAME+"</td>"+
            "<td>"+list_of_data[i].FATHER_NAME+"</td>"+
            "<td>"+list_of_data[i].DISCIPLINE_NAME+"</td>"+
            "<td>"+list_of_data[i].YEAR+"</td>"+
            "<td>"+list_of_data[i].INSTITUTE+"</td>"+
            "<td>"+list_of_data[i].PURPOSE_OF_PAYMENT+"</td>"+
            "<td>"+list_of_data[i].ROLL_NO+"</td>" +
                "</tr>";
            $('#'+table_id).append(row);

        }


    }

    function download(list_of_data,file_name){
        var csvString = "";
        for (var i = 0; i < list_of_data.length; i++) {

            var line = '';
            var heading = '';

            for (var index in list_of_data[i]) {

                if (line != '') line += ',';
                if (heading != '') heading += ',';

                heading += '"'+index+'"';
                line += '"'+list_of_data[i][index]+'"';
            }

            // heading
            if(i===0){
                csvString += heading + '\r\n';
            }

            csvString += line + '\r\n';
        }

        //console.log(csvString);
        var a = $('<a/>', {
            style:'display:none',
            href:'data:application/octet-stream;base64,'+btoa(csvString),
            download:file_name+'.csv'
        }).appendTo('body');
        a[0].click();
        a.remove();
    }
    function download_valid_challan(){
        download(FOUND_CHALLAN,"valid_challan_found_in_database");
    }
    function download_invalid_challan(){
        download(INVALID_CHALLAN_NO,"invalid_challan");
    }
    function download_already_verified_challan(){
        download(PAID_CHALLAN,"already_verified_challan");
    }
    function download_not_found_challan(){
        download(NOT_FOUND_CHALLAN,"not_found_in_database");
    }
    function importdb(){
        if(FOUND_CHALLAN.length>0){
            jQuery.ajax({
                url: "<?=base_url();?>Cmd/verifyChallan",
                type: "POST",
                enctype: 'json',
                processData: false,
                contentType: false,
                data: JSON.stringify(FOUND_CHALLAN),
                success: function (data, status) {
                    alertMsg('SUCCESS','Successfully Verified');
                },
                beforeSend:function (data, status) {

                },
                error:function (data, status) {
                    alertMsg('Error','Something went wrong check cmd log file ');
                },
            });
        }


    }
</script>

