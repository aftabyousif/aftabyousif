<section>
	<!-- Static Table Start -->
	<div class="static-table-area">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="sparkline8-list">
						<div class="sparkline8-hd">
							<div class="main-sparkline8-hd">
								<h1 class="text-center">Directorate of Admission Announced Admissions in the Following Campuses</h1>

							<ul class="list-group dual-list-box-inner" style="margin-top: 2%; margin-bottom: 2%">
								<li class="list-group-item text-center" style="font-weight: bold">Please Read Important Instructions</li>
								<li class="list-group-item list-group-item-danger font-weight-bold" style="font-weight: bold">1. Dear Candidate,&nbsp;&nbsp;&nbsp;Please carefully select your desired <span style="color: black"> CAMPUS & DEGREE PROGRAM </span> from the following list in which do you want to take the admission and you are allowed to choose only one campus.</li>
								<li class="list-group-item list-group-item-danger font-weight-bold" style="font-weight: bold">2. Must verify your form at the final stage after submission you will not be allowed to edit you application form.</li>
								<li class="list-group-item" style="font-weight: bold">3. If you have any query feel free to contact @ director.admission@usindh.edu.pk. You will get reply within 24 to 48 hrs .</li>
							</ul>

							</div>
						</div>

						<div class="sparkline8-graph">
							<div class="static-table-list table-responsive">
								<table class="table table-hover">
									<thead>
									<tr style="font-size: 11pt; font-family: 'Times New Roman'" class="text-center">
										<th>#</th>
										<th><i class="educate-icon educate-library"></i> Campus</th>
										<th><i class="fa fa-location-arrow"></i> Campus City</th>
										<th>Degree Program</th>
										<th>Admission Session</th>
<!--										<th>Batch</th>-->
										<th>Form Start Date</th>
										<th>Form Last Date</th>
										<th>Apply Now</th>
									</tr>
									</thead>
									<?php
									if(is_array($admission_announcement) || is_object($admission_announcement))
									{
										$sno=0;
										foreach ($admission_announcement as $admission_announcement_key=>$admission_announcement_value)
										{

										    $is_already_applied = false;

                                            //this method is define in functions_helper in this mehtod we provide Array and key of arary and finding value method return obj if exists else return false;
                                            $res = findObjectinList($user_application_list,'ADMISSION_SESSION_ID',$admission_announcement_value['ADMISSION_SESSION_ID']);

                                            // if res contain not false value or any o object it mean user already applied
                                            if($res){

                                                $APPLICATION_ID=$res['APPLICATION_ID'];
                                                $is_already_applied = true;
                                            }
//                                            foreach($user_application_list as $user_app){
//                                                if($SESSION_ID==$user_app['SESSION_ID']){
//
//                                                }
//                                            }
                                            $sno++;
											$NAME = $admission_announcement_value['NAME'];
											$YEAR = $admission_announcement_value['YEAR'];
											$ADMISSION_SESSION_ID = $admission_announcement_value['ADMISSION_SESSION_ID'];
											$CAMPUS_ID = $admission_announcement_value['CAMPUS_ID'];
											$SESSION_ID = $admission_announcement_value['SESSION_ID'];
											$PROGRAM_TYPE_ID = $admission_announcement_value['PROGRAM_TYPE_ID'];
											$ADMISSION_START_DATE = $admission_announcement_value['ADMISSION_START_DATE'];
											$ADMISSION_END_DATE = $admission_announcement_value['ADMISSION_END_DATE'];
											$LOCATION = $admission_announcement_value['LOCATION'];
											$PROGRAM_TITLE = $admission_announcement_value['PROGRAM_TITLE'];
											$BATCH_REMARKS = $admission_announcement_value['BATCH_REMARKS'];
											if ($BATCH_REMARKS == 'S') $BATCH_REMARKS = "Spring";
											elseif ($BATCH_REMARKS == 'F') $BATCH_REMARKS = "Fall";

											$start_date = date_create($ADMISSION_START_DATE);
											$start_date = date_format($start_date,'D, d-m-Y');
											$end_date = date_create($ADMISSION_END_DATE);
											$end_date = date_format($end_date,'D, d-m-Y');

											$link = "";
										    if ($ADMISSION_START_DATE>date('Y-m-d'))
												$link = "will be open soon";
											elseif ($ADMISSION_END_DATE<date('Y-m-d'))
												$link = 'Form over due date';
											else {
											    if($is_already_applied){
                                                    $url = "candidate/profile";
                                                    $challan_url = "form/admission_form_challan/$APPLICATION_ID";
                                                    $link="<a href='".base_url().$url."' class='btn btn-warning widget-btn-1 btn-sm'>Already Applied click here to next</a>";
                                                    $challan_link = "<a href='".base_url().$challan_url."' class='btn btn-info widget-btn-1 btn-sm'>Download Challan</a>";
                                                }else{

                                                    $link="<button type='submit' class='btn btn-success widget-btn-1 btn-sm'>Apply Now</button>";
                                                    $url = "form/addApplication";
                                                    $challan_link="";
                                                }

                                            }
											$hidden = array('ADMISSION_SESSION_ID' => $ADMISSION_SESSION_ID, 'CAMPUS_ID' => $CAMPUS_ID);
											?>

											<?=$is_already_applied?'':form_open(base_url().$url,'',$hidden)?>
									<tbody>
									<tr style="font-size: 11pt;color: black">
										<td><?=$sno?></td>
										<td><?=ucwords(strtolower($NAME))?></td>
										<td><?=ucwords(strtolower($LOCATION))?></td>
										<td><?=ucwords(strtolower($PROGRAM_TITLE))?></td>
										<td><?=ucwords(strtolower($BATCH_REMARKS))?> <?=$YEAR?></td>
										<td><?=$start_date?></td>
										<td><?=$end_date?></td>

										<td><?=$link?></td>
                                        <td><?=$challan_link?></td>
									</tr>
									</tbody>
											<?=$is_already_applied?'':form_close()?>
								<?php
										}//foreach
									}//if
									?>
								</table>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>

</section>

