<?php
$user_role = $_SESSION['ADMISSION_ROLE'];
$role_id   = $user_role['ROLE_ID'];
?>

	<!-- Static Table Start -->
	<div class="static-table-area">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="sparkline8-list">
						<div class="sparkline8-hd">
							<div class="main-sparkline8-hd">
								<h1 class="text-center">UNIVERSITY OF SINDH ADMISSION APPLICATION FORM VERIFICATION <span id="draft_msg"></span></h1>
								<h5 class="text-center text-danger">PLEASE VERIFY DETAILS CAREFULLY</h5>
							</div>
						</div>
						<div class="sparkline8-graph">
							<div class="static-table-list table-responsive">
                           
                                 <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><h4>Applied For <?=ucwords(strtolower($application['PROGRAM_TITLE']))?> Degree Program </h4></th>

                                    </tr>
                                    <tr>
                                        <th>Application ID</th>
                                        <td><h4><?=$application['APPLICATION_ID']?></h4></td>
                                    </tr>
                                    <tr>
                                        <th>Degree Program</th>
                                        <td><?=$application['PROGRAM_TITLE']?></td>
                                    </tr>
                                    <tr>
                                        <th>Applied Campus</th>
                                        <td><?=$application['NAME']?></td>
                                    </tr>
                                    
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
                                       

                                </table>
                                    
                                 <br>
                                <table class="table table-bordered">
                                    <?php
                                    $sp_ch = "";
                                    foreach ($application_category as $i=>$applicant_cat) {
                                        ?>
                                        <tr>
                                            <th colspan="2" class='text-danger'><?= (++$i).") ".$applicant_cat['FORM_CATEGORY_NAME'] ?></th>
                                        </tr>

                                        <?php
                                        if($applicant_cat['CATEGORY_INFO']){
                                            $CATEGORY_INFOS = json_decode( $applicant_cat['CATEGORY_INFO'],true);
                                            foreach($CATEGORY_INFOS as $key=>$CATEGORY_INFO){
                                                echo "<tr>";
                                                echo "<td>".str_replace('_',' ',$key)."</td>";
                                                if($key=="CERTIFICATE_IMAGE"){
                                                    echo "<td><img class='img-table-certificate' src='".itsc_url().EXTRA_IMAGE_PATH.$CATEGORY_INFO."' alt='PASSCERTIFICATE_IMAGE'></td>";
                                                }else{
                                                    echo "<td>$CATEGORY_INFO</td>";
                                                }

                                                echo "<tr>";
                                            }
                                        }


                                    }
                                    ?>

                                </table>
                                <br>
								<table class="table table-bordered">
                                    <tr CLASS="bg-success text-primary">
                                        <th colspan="2"><h4>Basic Information</h4></th>

                                    </tr>

                                    <tr>
                                        <th>Profile Image</th>
                                        <td><img style="height: 200px;width: 150px;" class="img-rounded" src="<?=itsc_url().PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE']?>" alt="Profile Image"></td>
                                    </tr>

                                    <tr>
										<th>Student Name
											<span class="text-danger" style="font-size: 9pt">As per in matriculation certificate</span>
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
                                <?php
                                if($role_id ==1){
                                ?>
									<tr>
										<th>Mobile No</th>
										<td><?=$user['MOBILE_CODE']?>-<?=$user['MOBILE_NO']?></td>
									</tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td><?=$user['PHONE']?$user['PHONE']:"N/A"?></td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth</th>
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
                                <?php
                                }
                                ?>
                                    <tr>
                                        <th>Domicile District</th>
                                        <td><?=$user['DISTRICT_NAME']?></td>
                                    </tr>
                                    <tr>
                                        <th>Area</th>
                                        <td><?=$user['U_R']=="U"?"URBAN":"RURAL"?></td>
                                    </tr>
                                       <?php
                                if($role_id ==1){
                                ?>
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
                                    <tr CLASS="bg-success text-primary">
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
                                  <?php } ?>
                            
                                <br/>

                                <table class="table table-bordered">
                                    <tr CLASS="bg-success text-primary">
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
                                <table class="table table-bordered">
                                    <tr CLASS="bg-success text-primary">
                                        <th colspan="11"><h4>Qualification Information</h4></th>

                                    </tr>
                                    <tr>
                                        <th>Qualification / Degree / Certificate</th>
                                        <th>Discipline / Subject / Group</th>
                                        <th>Organization / University / Board</th>
                                        <th>Institute / Department / School / College</th>
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
                                       
                                       if($degree['DEGREE_ID'] == 10) continue;
                                       
                                        $per = $degree['OBTAINED_MARKS']*100/$degree['TOTAL_MARKS'];
                                        $per = round($per,2);
                                        if($per<0||$per>100){
                                            $per = "N/A";
                                        }
                                        $output.= "<tr>
                        <td>{$degree['DEGREE_TITLE']}</td>
                        <td>{$degree['DISCIPLINE_NAME']}</td>
                        <td>{$degree['ORGANIZATION']}</td>
                        <td>{$degree['INSTITUTE']}</td>
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
                                    <tr CLASS="bg-success text-primary">
                                        <th colspan="2"><h4 style="text-align:center">Applicant's Minor Subjects</h4></th>
                                    </tr>
                                    <?php
                                   
                                     if(count($applicants_minors)>0){
                                    ?>
                                    
                                    <tr>
                                        <th>No</th>
                                        <th>Minor Subject</th>
                                    </tr>
                                    <?php
                                    foreach ($applicants_minors as $no => $applicants_minor){
                                        ?>
                                        <tr>
                                            <th><?=++$no?></th>
                                            <td><?=$applicants_minor['SUBJECT_TITLE']?></td>
                                        </tr>
                                    <?php
                                    }
                                }  
                                    ?>

                                </table>
                                
                                <br>
                                <?php
                                if($lat_info){
                                    ?>
                                      <table class="table table-bordered">
                                    <tr CLASS="bg-success text-primary">
                                        <th colspan="2"><h4>LAT Info</h4></th>

                                    </tr>
                                    <tr>
                                        <td>Test Score</td>
                                        <td><?=$lat_info['TEST_SCORE']?></td>
                                    </tr>
                                    <tr>
                                        <td>Test Date</td>
                                        <td><?=getDateForView($lat_info['TEST_DATE'])?></td>
                                    </tr>
                                    <tr>
                                        <td>Token No</td>
                                        <td><?=$lat_info['TOKEN_NO']?></td>
                                    </tr>
                                    <tr>
                                        <td>Result Image</td>
                                         <td><img class='img-table-certificate' src='<?=base_url().EXTRA_IMAGE_PATH.strtolower($lat_info['RESULT_IMAGE'])?>' alt='CHALLAN_IMAGE'></td>
                                       
                                    </tr>
                                    <?php
                                }
                                
                                ?>
                                </table>
                                <br>
                                
                                <table class="table table-bordered">
                                    <tr CLASS="bg-success text-primary">
                                        <th colspan="2"><h4 style="text-align:center">Applicant's Selected Choices</h4></th>
                                    </tr>
                                    <?php
                                    if(count($application_choices)>0){
                                    ?>
                                    <tr CLASS="bg-danger">
                                        <th colspan="2"><h4>General Merit (Morning)</h4></th>
                                    </tr>
                                    <tr>
                                        <th>Choice No</th>
                                        <th>Program</th>
                                    </tr>
                                    <?php
                                    foreach ($application_choices as $applicant_cho){
                                        if($applicant_cho['IS_SPECIAL_CHOICE'] == "Y") continue;
                                        ?>
                                        <tr>
                                            <th><?=$applicant_cho['CHOICE_NO']?></th>
                                            <td><?=$applicant_cho['PROGRAM_TITLE']?></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>
                                    
                                    <tr CLASS="bg-danger">
                                        <th colspan="2"><h4>Special Self Finance (Morning)</h4></th>
                                    </tr>
                                    <tr>
                                        <th>Choice No</th>
                                        <th>Program</th>
                                    </tr>
                                    <?php
                                    foreach ($application_choices as $applicant_cho){
                                        if($applicant_cho['IS_SPECIAL_CHOICE'] == "N") continue;
                                        ?>
                                        <tr>
                                            <th><?=$applicant_cho['CHOICE_NO']?></th>
                                            <td><?=$applicant_cho['PROGRAM_TITLE']?></td>
                                        </tr>
                                    <?php
                                    }
                                }//check isset of application choices for morning and special self finance
                            // prePrint($application_choices_evening);
                            if(count($application_choices_evening)>0){
                                    ?>
                                    <tr CLASS="bg-danger">
                                        <th colspan="2"><h4>Evening Choices</h4></th>
                                    </tr>
                                    <tr>
                                        <th>Choice No</th>
                                        <th>Program</th>
                                    </tr>
                                    <?php
                                    foreach ($application_choices_evening as $applicant_evening){
                                        ?>
                                        <tr>
                                            <th><?=$applicant_evening['CHOICE_NO']?></th>
                                            <td><?=$applicant_evening['PROGRAM_TITLE']?></td>
                                        </tr>
                                    <?php
                                    }
                                }  
                                    ?>

                                </table>
                                <br>
                                <?php
                                if(isValidData($application['PAID'])){
                                ?>
                                <table class="table table-bordered">
                                    <tr CLASS="bg-success text-primary">
                                        <th colspan="2"><h4>Bank Payment Information</h4></th>

                                    </tr>
                                <?php
                                if($role_id ==1){
                                ?>
                                    <tr>
                                        <th>Bank Branch</th>
                                        <td><?=$bank['BRANCH_NAME']?$bank['BRANCH_CODE']." ".$bank['BRANCH_NAME']:"N/A"?></td>
                                    </tr>
                            
                                    <tr>
                                        <th>Paid Amount</th>
                                        <td><?=$application['PAID_AMOUNT']?$application['PAID_AMOUNT']:"N/A"?></td>
                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <th>Challan No</th>
                                        <td><?=$application['FORM_CHALLAN_ID']? str_pad($application['FORM_CHALLAN_ID'], 5, '0', STR_PAD_LEFT):"N/A"?></td>
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
                                if($next_page == "lock_form"){
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
									if($next_page=="upload_application_challan" &&!($application['ADMISSION_END_DATE']<date('Y-m-d'))){
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
                                        <a style="margin-left: 50px;"class="btn btn-success btn-lg" id="submit_button" href="<?=base_url()."form/".$next_page?>">Submit</a>
                                        
                                        <?php
                                        }
                                    }else if($next_page == "dashboard"){
                                        ?>
                                        <button style="margin-left: 50px;"class="btn btn-warning btn-lg" onclick="window.location='<?=base_url()."form/$next_page"?>'">Back to dashboard</button>
                                         
                                         <button style="margin-left: 50px;"class="btn btn-primary btn-lg" onclick="display()">Print Draft Copy</button>
                                        <?php
                                    }
                                    ?>
                                    <?php if($role_id!=8){?>
									<?=form_open(base_url().'FormVerification/UpdateStatus')?>
								<h4 class="text-primary">Form verification Section</h4>
									<div class="row">
										<?php
									//	prePrint($application);
									//	exit();
										$message = $application['MESSAGE'];
										$form_old_status_id = $application['STATUS_ID'];
										$form_status  = json_decode($application['FORM_STATUS'],true);
										$fee_status = $application['IS_VERIFIED'];
										$profile_photo_status = $application['IS_PROFILE_PHOTO_VERIFIED'];
										$additional_document_status = $form_status['ADDITIONAL_DOCUMENT']['STATUS'];
//										prePrint($form_status);
										?>
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Form Fee</label>

												<select name="challan_verified" id="challan_verified" class="form-control col-md-2">
													<option value="PENDING VERIFICATION" <?php if ($fee_status == "N" ||$fee_status == null) echo "SELECTED"?>>PENDING VERIFICATION</option>
													<option value="VERIFIED" <?php if ($fee_status == "Y") echo "SELECTED"?>>FORM FEE VERIFIED</option>
<!--													<option value="RE-UPLOAD">OPEN RE-UPLOAD</option>-->
												</select>
											</div>
											<div class="form-group">
												<label>Profile Photo</label>
												<select name="profile_photo_verified" id="profile_photo_verified" class="form-control col-md-2">
													<option value="PENDING VERIFICATION" <?php if ($profile_photo_status == 0 || $profile_photo_status==null) echo "SELECTED"?>>PENDING VERIFICATION</option>
													<option value="VERIFIED" <?php if ($profile_photo_status == 1) echo "SELECTED"?>>PROFILE PHOTO VERIFIED</option>
													<option value="RE_UPLOAD" <?php if ($profile_photo_status == 2) echo "SELECTED"?>>OPEN RE_UPLOAD</option>
												</select>
											</div>

											<div class="form-group">
												<label>Documents</label>
												<select name="additional_documents_verified" id="additional_documents_verified" class="form-control col-md-2">
													<option value="PENDING VERIFICATION" <?php if ($additional_document_status == "PENDING VERIFICATION") echo "SELECTED"?>>PENDING VERIFICATION</option>
													<option value="DOCUMENT(S) VERIFIED" <?php if ($additional_document_status == "DOCUMENT(S) VERIFIED") echo "SELECTED"?>>DOCUMENT(S) VERIFIED</option>
<!--													<option value="RE-UPLOAD">OPEN RE-UPLOAD</option>-->
												</select>
											</div>
										</div>

										<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Choose Status</label>
												<p class="text-danger">The current status will be selected, you can change status after verification as directed by the concerned officer.</p>
												<select name="application_status" id="application_status" class="form-control col-md-2">
													<option value="0"></option>
													<?php
													foreach ($application_status_list as $application_status)
													{
													       //if($role_id !=1)
													           //if($application_status['STATUS_ID'] <3 || $application_status['STATUS_ID'] >=8) continue;
													       
														$status_selected = "";
														if ($application_status['STATUS_ID'] == $form_old_status_id) $status_selected = "selected";
														?>
														<option value="<?=$application_status['STATUS_ID']?>" <?=$status_selected?>> <?=$application_status['STATUS_NAME']?> </option>";
														<?php
													}
													unset($application_status);
													unset($application_status_list);
													?>
												</select>
											</div>
											<div class="form-group">
												<label>Message</label> <p class="text-danger">The message you type here will be displayed to the candidate.</p>
												<textarea class="form-control" id="message" name="message"><?=$message?></textarea>
											</div>
										</div>
									</div>
									<div class="form-group-inner">
										<div class="login-btn-inner">
											<div class="row">
												<div class="col-lg-4"></div>
												<div class="col-lg-8">
													<div class="login-horizental cancel-wp pull-left form-bc-ele">
														<p class="text-danger">Please do verification carefully after clicking on <em>'  Save Change  '</em>  candidate will get an email of this.</p>
														<button class="btn btn-danger" type="button" onclick="window.close()">Close Window</button>
														<button name="save" class="btn btn-sm btn-primary login-submit-cs" type="submit">Save Change</button>
													</div>
												</div>
											</div>
										</div>
									</div>

<!--									<button type="submit" name="save"> UPDATE STATUS</button>-->
								<?=form_close()?>
                                </div>
	                            <?php
                                    }
									?>
									
                                <?php if($role_id ==1): ?>
								<br/>
								<div class="alert-icon shadow-inner wrap-alert-b">
									<div class="alert alert-warning alert-success-style3">
<!--										<button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">-->
<!--											<span class="icon-sc-cl" aria-hidden="true">&times;</span>-->
<!--										</button>-->
<!--										<i class="fa fa-exclamation-triangle edu-warning-danger admin-check-pro" aria-hidden="true"></i>-->
										<p class="text-center"><strong>LOG DETAIL OF APPLICATION  SCRUTINY</strong> </p>
									</div>
								</div>
									<?php
//								prePrint($VERIFIER_PROFILE);
								if(is_array($VERIFIER_PROFILE) || is_object($VERIFIER_PROFILE)):
								$sno=0;
									foreach ($VERIFIER_PROFILE as $VERIFIER_PROFILE_DATA):
									$sno++;
									$v_name = $VERIFIER_PROFILE_DATA['VERIFIER_PROFILE']['FIRST_NAME'].' '.$VERIFIER_PROFILE_DATA['VERIFIER_PROFILE']['LAST_NAME'];
									$v_mobile= $VERIFIER_PROFILE_DATA['VERIFIER_PROFILE']['MOBILE_NO'];
									$v_email= $VERIFIER_PROFILE_DATA['VERIFIER_PROFILE']['EMAIL'];
									$prev_record = json_decode($VERIFIER_PROFILE_DATA['PREV_RECORD'],true);
									$new_record = json_decode($VERIFIER_PROFILE_DATA['NEW_RECORD'],true);
									$ip_address = $VERIFIER_PROFILE_DATA['IP_ADDRESS'];
									$operating_system = $VERIFIER_PROFILE_DATA['USER_AGENT'];
									$datetime = $VERIFIER_PROFILE_DATA['DATETIME'];
									$datetime = date_create($datetime);
//									prePrint($datetime);
									$datetime = date_format($datetime,"d-m-Y h:i A");
//									prePrint($datetime);
									?>
									<br/>
								<table class="table">
									<tr class="bg-primary"><th colspan="2" class="text-center">Scrutiny (<?=$sno?>)</th></tr>
									<tr>
										<th>Name</th> <td><?=$v_name?></td>
									</tr>
									<tr>
										<th>Mobile</th> <td><?=$v_mobile?></td>
									</tr>
									<tr>
										<th>Email</th> <td><?=$v_email?></td>
									</tr>
									<tr>
										<th>Ip</th> <td><?=$ip_address?></td>
									</tr>
									<tr>
										<th>OS</th> <td><?=$operating_system?></td>
									</tr>
									<tr>
										<th>DateTime</th> <td><?=$datetime?></td>
									</tr>
									<tr>
										<th>Previous Status</th> <td><?=prePrint($prev_record)?></td>
									</tr>
									<tr>
										<th>New Record</th> <td><?=prePrint($new_record)?></td>
									</tr>
								</table>
								<?php
								endforeach;
								endif;
									?>

								<?php endif ?>
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
                   <button id="image_rotate" class='btn btn-warning' ><i class='fa fa-arrow-left'></i>  Rotate Image</button>
                <a data-dismiss="modal" href="#">OKAY</a>

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
    
 // we need to save the total rotation angle as a global variable 
    var current_rotation = 0;
    
    // change CSS transform property on click
document.querySelector("#image_rotate").addEventListener('click', function() {
	// update total rotation
	// if angle is positive, rotation happens clockwise. if negative, rotation happens anti-clockwise.
	current_rotation += 90;

	// rotate clockwise by 90 degrees
	document.querySelector("#image_display").style.transform = 'rotate(' + current_rotation + 'deg)';
    });

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
