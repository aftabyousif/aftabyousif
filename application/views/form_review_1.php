<?php
$is_specila_self = false;
// prePrint($application);
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
     <br>
                 <?php
                $data['application']=$application;
                $data['users_reg']=$user;
                $data['qualifications']=$qualifications;
                $data['category']=$application_category;
                $data['program_choice']=$application_choices;
                show_progress_status($data);
                ?>
	<!-- Static Table Start -->
	<div class="static-table-area">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="sparkline8-list">
						<div class="sparkline8-hd">
							<div class="main-sparkline8-hd">
								<h1 class="text-center">ADMISSION APPLICATION FORM REVIEW <span id="draft_msg"></span></h1>
								<h5 class="text-center text-danger">PLEASE READ AND CHECK YOUR ADMISSION FORM CAREFULLY</h5>
							</div>
						</div>
						<div class="sparkline8-graph">
							<div class="static-table-list table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Applied For <?=ucwords(strtolower($application['PROGRAM_TITLE']))?> Degree Program <?=$application['YEAR']?></h4></th>

                                    </tr>
                                    <tr>
                                        <th>Application ID</th>
                                        <td><h4><?=$application['APPLICATION_ID']?></h4></td>
                                    </tr>
                                   
                                    <tr>
                                        <th>Applied Campus</th>
                                        <td><?=$application['NAME']?></td>
                                    </tr>
                                    <?php
                                    if($next_page == "final_lock") {
                                        ?>
                                        <tr>
                                            <th>Applied Category</th>
                                            <td><?php
                                                $sp_ch = "";
                                                foreach ($application_category as $applicant_cat){
                                                    echo $sp_ch.$applicant_cat['FORM_CATEGORY_NAME'];
                                                    $sp_ch = ", ";
                                                }
                                                ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>


                                </table>
                                <?php
                                if($next_page == "final_lock") {
                                ?>
                                <br>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Selected Choices (Morning)</h4></th>

                                    </tr>
                                    <tr>
                                        <th>Choice No</th>
                                        <th>Program</th>
                                    </tr>
                                    <?php
                                    foreach ($application_choices as $applicant_cho){
                                        if($applicant_cho['IS_SPECIAL_CHOICE']=='Y'){
                                            $is_specila_self = true;
                                            continue;
                                        }
                                        ?>
                                        <tr>
                                            <th><?=$applicant_cho['CHOICE_NO']?></th>
                                            <td><?=$applicant_cho['PROGRAM_TITLE']?></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>

                                </table>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Selected Choices (Evening)</h4></th>

                                    </tr>
                                    <tr>
                                        <th>Choice No</th>
                                        <th>Program</th>
                                    </tr>
                                    <?php
                                    foreach ($application_choices_evening as $applicant_cho){
                                        if($applicant_cho['IS_SPECIAL_CHOICE']=='Y'){
                                            $is_specila_self = true;
                                            continue;
                                        }
                                        ?>
                                        <tr>
                                            <th><?=$applicant_cho['CHOICE_NO']?></th>
                                            <td><?=$applicant_cho['PROGRAM_TITLE']?></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>

                                </table>
                                    <?php
                                
                                if($is_specila_self==true){
                                    ?>
                                <br>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Special Self Finance Choices</h4></th>

                                    </tr>
                                    <tr>
                                        <th>Choice No</th>
                                        <th>Program</th>
                                    </tr>
                                    <?php
                                    foreach ($application_choices as $applicant_cho){
                                        if($applicant_cho['IS_SPECIAL_CHOICE']=='N'){
                                            $is_specila_self = true;
                                            continue;
                                        }
                                        ?>
                                        <tr>
                                            <th><?=$applicant_cho['CHOICE_NO']?></th>
                                            <td><?=$applicant_cho['PROGRAM_TITLE']?></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>

                                </table>
                                    <?php
                                    
                                }
                                
                                }
                                ?>
                                    <br>
								<table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Basic Information</h4></th>

                                    </tr>

                                    <tr>
                                        <th>Profile Image</th>
                                        <td><img style="height: 200px;width: 150px;" class="img-rounded" src="<?=itsc_url().PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE']?>" alt="Profile Image"></td>
                                    </tr>

                                    <tr>
										<th>Student Name
										<br>
											<span class="text-danger" style="font-size: 9pt">As per matriculation certificate</span>
										</th>
										<td><?=$user['FIRST_NAME']?></td>
									</tr>
									<tr>
										<th>Father's Name</th>
										<td><?=$user['FNAME']?></td>
									</tr>
									<tr>
										<th>Surname</th>
										<td><?=$user['LAST_NAME']?></td>
									</tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td><?=$user['GENDER']=="M"?"MALE":($user['GENDER']=="F"?"FEMALE":"OTHER");?></td>

                                    </tr>
                                    <?php
                                    if($user['IS_CNIC_PASS']=='P'){
                                        $title = "Passport No";
                                        $value = $user['PASSPORT_NO'];
                                    }else{
                                        $title = "CNIC / B-Form No";
                                        $value = $user['CNIC_NO'];
                                    }
                                    ?>
									<tr>
										<th><?=$title?></th>
										<td><?=$value?></td>
									</tr>

									<tr>
										<th>Mobile No</th>
										<td><?=$user['MOBILE_CODE']?>-<?=$user['MOBILE_NO']?></td>
									</tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td><?=$user['PHONE']?$user['PHONE']:"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Date Of Birth</th>
                                        <td><?=getDateForView($user['DATE_OF_BIRTH'])?></td>
                                    </tr>
                                    <tr>
                                        <th>Religion</th>
                                        <td><?=$user['RELIGION']?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?=$user['EMAIL']?></td>
                                    </tr>
                                    <tr>
                                        <th>Domicile District</th>
                                        <td><?=$user['DISTRICT_NAME']?></td>
                                    </tr>
                                    <tr>
                                        <th>Area</th>
                                        <td><?=$user['U_R']=="U"?"URBAN":"RURAL"?></td>
                                    </tr>
                                    <tr>
                                        <th>Home / Postal Address</th>
                                        <td><?=$user['HOME_ADDRESS']?></td>
                                    </tr>
                                    <tr>
                                        <th>Permanent Address</th>
                                        <td><?=$user['PERMANENT_ADDRESS']?></td>
                                    </tr>
                                    <tr>
                                        <th>Blood Group</th>
                                        <td><?=$user['BLOOD_GROUP']?></td>
                                    </tr>
                                    <tr>
                                        <th>Zip / Postal Code</th>
                                        <td><?=$user['ZIP_CODE']?$user['ZIP_CODE']:"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Place of Birth</th>
                                        <td><?=$user['PLACE_OF_BIRTH']?$user['PLACE_OF_BIRTH']:"N/A"?></td>
                                    </tr>
                                </table>
                                <br>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Guardian Information</h4></th>

                                    </tr>
                                    <tr>
                                        <th>Guardian Name</th>
                                        <td><?=$guardian['FIRST_NAME']?$guardian['FIRST_NAME']:"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Relationship</th>
                                        <td><?=$guardian['RELATIONSHIP']?$guardian['RELATIONSHIP']:"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No</th>
                                        <td><?=$guardian['MOBILE_CODE']?>-<?=$guardian['MOBILE_NO']?></td>
                                    </tr>
                                    <tr>
                                        <th>Home / Postal Address</th>
                                        <td><?=$guardian['HOME_ADDRESS']?></td>
                                    </tr>
                                </table>
                                <br>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="10"><h4>Qualification Information</h4></th>

                                    </tr>
                                    <tr>
                                        <th>Qualification / Degree / Certificate</th>
                                        <th>Discipline / Subject / Group</th>
                                        <th>Organization / University / Board</th>
                                        
                                        <th>Roll No</th>
                                        <th>Total Marks</th>
                                        <th>Obtained Marks</th>
                                        <th>Percentage</th>
                                        <th>Marksheet</th>
                                        <th>Pass certificate</th>
                                        <th>Exam Year</th>


                                    </tr>
                                    <?php
                                    $output = "";
                                    foreach($qualifications as $degree) {
                                        $per = $degree['OBTAINED_MARKS']*100/$degree['TOTAL_MARKS'];
                                        $per = round($per,2);
                                        if($per<0||$per>100){
                                            $per = "N/A";
                                        }
                                        $output.= "<tr>
                        <td>{$degree['DEGREE_TITLE']}</td>
                        <td>{$degree['DISCIPLINE_NAME']}</td>
                        <td>{$degree['ORGANIZATION']}</td>
                        
                        <td>{$degree['ROLL_NO']}</td>
                        <td>{$degree['TOTAL_MARKS']}</td>
                        <td>{$degree['OBTAINED_MARKS']}</td>
                        <td>{$per}</td>
                        <td><img class='img-table-certificate' src='".itsc_url().EXTRA_IMAGE_PATH.$degree['MARKSHEET_IMAGE']."' alt='MARKSHEET_IMAGE'></td>
                        <td><img class='img-table-certificate' src='".itsc_url().EXTRA_IMAGE_PATH.$degree['PASSCERTIFICATE_IMAGE']."' alt='PASSCERTIFICATE_IMAGE'></td>
                        <td>{$degree['PASSING_YEAR']}</td>
                       
                    </tr>";


                                    }


                                    echo $output;
                                    ?>
                                    <style>
                                        .img-table-certificate{
                                            border: #40402e;
                                            border-style: double;
                                            border-radius: 10%;
                                        }
                                    </style>
                                </table>
                                <br>

                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="4"><h4>Document Information</h4></th>

                                    </tr>
                                    <?php
                                    if($user['IS_CNIC_PASS']=='P'){
                                        $title = "Passport";
                                        $value = 'PASSPORT';
                                    }else{
                                        $title = "CNIC / B-Form";
                                        $value = 'CNIC';
                                    }
                                    ?>
                                    <tr>
                                        <th><?=$title?> Front Image</th>
                                        <th><?=$title?> Back Image</th>
                                        <th>Domicile Image</th>
                                        <th>Form-C Image</th>
                                    </tr>
                                    <tr>
                                        <td><img class='img-table-certificate' src='<?=itsc_url().EXTRA_IMAGE_PATH.$user[$value.'_FRONT_IMAGE']?>' alt='CNIC_FRONT_IMAGE'></td>
                                        <td><img class='img-table-certificate' src='<?=itsc_url().EXTRA_IMAGE_PATH.$user[$value.'_BACK_IMAGE']?>' alt='CNIC_BACK_IMAGE'></td>
                                        <td><img class='img-table-certificate' src='<?=itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_IMAGE']?>' alt='DOMICILE_IMAGE'></td>
                                        <td><img class='img-table-certificate' src='<?=itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_FORM_C_IMAGE']?>' alt='DOMICILE_FORM_C_IMAGE'></td>
                                    </tr>
                                </table>
                                <br>
                                <?php
                                if(isValidData($application['PAID'])){
                                ?>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Bank Challan Information</h4></th>

                                    </tr>
                                    <tr>
                                        <th>Bank Branch</th>
                                        <td><?=$bank['BRANCH_NAME']?$bank['BRANCH_CODE']." ".$bank['BRANCH_NAME']:"N/A"?></td>
                                    </tr><tr>
                                        <th>Challan No</th>
                                        <td><?=$application['FORM_CHALLAN_ID']? str_pad($application['FORM_CHALLAN_ID'], 5, '0', STR_PAD_LEFT):"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Paid Amount</th>
                                        <td><?=$application['PAID_AMOUNT']?$application['PAID_AMOUNT']:"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Challan Date</th>
                                        <td><?=$application['CHALLAN_DATE']?getDateForView($application['CHALLAN_DATE']):"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Challan Image</th>
                                        <td><img class='img-table-certificate' src='<?=itsc_url().EXTRA_IMAGE_PATH.$application['CHALLAN_IMAGE']?>' alt='CHALLAN_IMAGE'></td>
                                    </tr>

                                </table>
                                <?php
                                }
                                ?>

                                <br>
                                <br>
                                <?php
                                if($next_page == "lock_form"||$next_page == "final_lock"||$next_page == "final_lock_evening"){
                                ?>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th ><h4>Undertaking</h4></th>

                                        </tr>

                                        <tr>
                                            <th class="text-danger"><input type="checkbox" id="undertaking_checkbox"onchange="visible_submit()"> I do hereby state that all information and data given by me as above is true and correct and shall always be binding to me and undertake to abide all provisions of act, statutes, rules and regulatios of the University.</th>

                                        </tr>
                                        <tr>
                                            <th class="text-danger">Once submitted, data can not be changed. Your admission form will be locked</th>

                                        </tr>

                                    </table>
                                <?php
                                }
                                ?>
                                <hr>
                                
                                <div class="text-center">
                                    <?php
                                    if($next_page=="select_subject" &&!($application['ADMISSION_END_DATE']<date('Y-m-d'))){
                                        ?>
                                        <a style="margin-right: 50px;"class="btn btn-warning btn-lg" href="<?=base_url()."candidate/profile"?>">Back</a>
                                        <a style="margin-left: 50px;"class="btn btn-success btn-lg" href="<?=base_url()."form/".$next_page?>">Next</a>
                                        <?php
                                    }else if($next_page == "lock_form"&&!($application['ADMISSION_END_DATE']<date('Y-m-d'))){
                                        ?>
                                        
                                        <?php
                                        if($application['IS_SUBMITTED']=='N'&&!($application['ADMISSION_END_DATE']<date('Y-m-d'))){
                                        ?>
                                        <a style="margin-right: 50px;" class="btn btn-warning btn-lg" href="<?=base_url()."form/upload_application_challan"?>">Back</a>
                                        <a style="margin-left: 50px;"class="btn btn-success btn-lg" id="submit_button" href="<?=base_url()."form/".$next_page?>">Proceed</a>
                                        
                                        <?php
                                        }
                                    }else if($next_page == "dashboard"){
                                        ?>
                                        <button style="margin-left: 50px;"class="btn btn-warning btn-lg" onclick="window.location='<?=base_url()."form/$next_page"?>'">Back to dashboard</button>
                                         
                                         <button style="margin-left: 50px;"class="btn btn-primary btn-lg" onclick="display()">Print Draft Copy</button>
                                        <?php
                                    }else if($next_page == "final_lock"){
                                        ?>
                                        <a style="margin-right: 50px;" class="btn btn-warning btn-lg" href="<?=base_url()."form/select_program"?>">Back</a>
                                        <a style="margin-left: 50px;"class="btn btn-success btn-lg" id="submit_button" href="<?=base_url()."form/".$next_page?>">Submit</a>

                                        <?php
                                    }else if($next_page == "final_lock_evening"){
                                        ?>
                                        <a style="margin-right: 50px;" class="btn btn-warning btn-lg" href="<?=base_url()."form/evening_choices"?>">Back</a>
                                        <a style="margin-left: 50px;"class="btn btn-success btn-lg" id="submit_button" href="<?=base_url()."form/".$next_page?>">Submit</a>

                                        <?php
                                    }
                                    ?>
                                    

                                </div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

</section>
<a id="priamry_modal_btn" class="Primary mg-b-10" href="#" data-toggle="modal" data-target="#PrimaryModalalert" hidden>Primary</a>
<div id="PrimaryModalalert" class="modal modal-edu-general default-popup-PrimaryModal fade " role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-close-area modal-close-df">
                <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
            </div>
            <div class="modal-body">
                <i class="educate-icon educate-checked modal-check-pro"></i>
                <h4 id="priamry_modal_title">Awesome!</h4>
                <div id="priamry_modal_msg" class="text-left">The Modal plugin is a dialog box/popup window that is displayed on top of the current page</div>
            </div>
            <div class="modal-footer" id="add_btn">
                <a data-dismiss="modal" href="#">OK</a>

                <!--                                        <a href="#">Process</a>-->
            </div>
        </div>
    </div>
</div>
<a id="image_modal_btn" class="Primary mg-b-10" href="#" data-toggle="modal" data-target="#ImageModalalert" hidden>Primary</a>
<div id="ImageModalalert" class="modal modal-edu-general default-popup-PrimaryModal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-close-area modal-close-df">
                <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
            </div>
            <div class="modal-body">
                <i class="educate-icon educate-checked modal-check-pro"></i>
                <h2 id="image_modal_title">Awesome!</h2>
                <p id="image_modal_msg">
                    <img src="<?=$image_path_default =base_url()."dash_assets/img/avatar/default-avatar.png";?>" alt="">
                </p>
            </div>
            <div class="modal-footer" id="add_btn">
                <a data-dismiss="modal" href="#">OK</a>

                <!--                                        <a href="#">Process</a>-->
            </div>
        </div>
    </div>
</div>

<script>
    function alertMsg(title,msg){
        document.getElementById("priamry_modal_title").innerHTML= title;
        document.getElementById("priamry_modal_msg").innerHTML= msg;
        document.getElementById("priamry_modal_btn").click();
    }
    function alertImage(title,path){
        document.getElementById("image_modal_title").innerHTML= title;
        document.getElementById("image_modal_msg").innerHTML= "<img src='"+path+"' alt=''>";
        document.getElementById("image_modal_btn").click();
    }
    function alertConfirm(title,msg,id){
        document.getElementById("priamry_modal_msg").innerHTML= msg;
        document.getElementById("priamry_modal_title").innerHTML= title;
        id = "'"+id+"'";
        document.getElementById("add_btn").innerHTML = "<a data-dismiss=\"modal\" href=\"#\">Cancel</a><a href='#' data-dismiss='modal' onclick=\"submitConfirm("+id+")\" id='confirm_btn'>Process</a>";
        document.getElementById("priamry_modal_btn").click();


    }
    function submitConfirm(id){
        document.getElementById(id).click();
    }

</script>

<script>
function display() {
    //$('#draft_msg').html("(Draft copy don't need to submit.)");
            window.print();
         }
    $('#submit_button').hide();
    function visible_submit(){

        if($("#undertaking_checkbox").is(':checked')) {
            $('#submit_button').show();
        }   else{
            $('#submit_button').hide();
        }
    }
    $( '.img-table-certificate' ).click(function() {
        alertImage('Image',$(this).attr('src'));
    });
</script>
</div>