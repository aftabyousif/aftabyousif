<?php
//prePrint($applicant_data);
//$form_data = $applicant_data['FORM_DATA'];
//$form_data = json_decode($form_data,true);
//$candidate_user_reg = $form_data['users_reg'];
//prePrint($form_data);

?>
<div class="single-pro-review-area mt-t-20 mg-b-10">
	<div class="container-fluid">

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="profile-info-inner">

					<h4 class="text-center">Form Verification</h4>
					<?php
					$table_column = array("ACTION","APP ID","USER ID","CNIC NO","NAME","FATHER NAME","SURNAME","EMAIL","MOBILE NO","GUARDIAN MOBILE NO","REMARKS","CAMPUS");
					?>
					<div class="data-table-area mg-b-15">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="sparkline13-list">
										<div class="sparkline13-hd">
											<div class="main-sparkline13-hd">
												<h1>All People</h1>
												<!--
																		<div class="col-md-12" align="right" style="margin-top:-35px;">
																				<a href="add_news.php">
																					<button class="btn btn-custon-rounded-three btn-primary btn-lg" style="align:right;">Add News</button>
																				</a>
																			</div>
												-->
											</div>

										</div>


										<div class="sparkline13-graph">
											<div class="datatable-dashv1-list custom-datatable-overright">

												<div id="toolbar">
													<select class="form-control dt-tb">
														<option value="">Export Basic</option>
														<option value="all">Export All</option>
														<option value="selected">Export Selected</option>
													</select>
												</div>
												<table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
													   data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
													<thead>
													<tr>
														<th data-field="state" data-checkbox="true"></th>
														<?php
														$i=0;
														foreach($table_column as $col){
															?>

															<th data-field="<?=$col?>" onclick="sortTable(<?=$i?>)" ><?=$col?></th>
															<?php
															$i++;
														}
														?>
													</tr>
													</thead>
													<tbody>
													<?php
//													prePrint($applicant_data);
                                                   // $applicant_data = quicksort_form_verification($applicant_data,'N/A','DESC');
													foreach($applicant_data as $k=>$applicant){
													    if ($k==2000) break;
													   //prePrint($applicant);
														$form_data = json_decode($applicant['FORM_DATA'],true);
														$candidate = $form_data['users_reg'];
														$guardian = $form_data['guardian'];

														$APPLICATION_ID = urlencode(base64_encode($applicant['APPLICATION_ID']));
														$APPLICANT_USER_ID = urlencode(base64_encode($applicant['USER_ID']));

														$url = "FormVerification/review";
														$url = urlencode(base64_encode($url));
														$url = base_url()."FormVerification/set_application_id/$APPLICANT_USER_ID/$APPLICATION_ID/$url";
														$verification_link = "<a class='btn btn-warning' href='{$url}' target='_blank'><i class='fa fa-edit'>&nbsp;&nbsp; Start Verification</i></a>";

														?>
															<td></td>
															<td> <?=$verification_link?> </td>
															<!--<td><a target="_blank" class="btn btn-info" style="color:white;" href="donate_item.php?cnic_no=<?=$user['CNIC_NO']?>">View</a></td>-->
															<td><?=$applicant['APPLICATION_ID']?></td>
															<td><?=$applicant['USER_ID']?></td>
															<td><?=$applicant['CNIC_NO']?></td>
															<td><?=$applicant['FIRST_NAME']?></td>
															<td><?=$applicant['FNAME']?></td>
															<td><?=$applicant['LAST_NAME']?></td>
															<td><?=$applicant['EMAIL']?></td>
															<td><?="0".$applicant['MOBILE_NO']?></td>
															<td><?="0".$guardian['MOBILE_NO']?></td>
																<td><textarea style="height:100px"><?=$applicant['MESSAGE']?></textarea></td>
															<td><?=$applicant['NAME']?></td>
														</tr>

													<?php }?>

													</tbody>


												</table>


											</div> <!-- /.table-stats -->
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
</div>
<script type="text/javascript">
	addEventListener('mouseover',function () {
		let total_seconds = 1000*60;
		//
// 		setTimeout('location.reload()',total_seconds);
		// let current_seconds = new Date().getSeconds();
		// if (current_seconds > total_seconds)
		// {
		// 	location.reload();
		// }
	})
</script>
