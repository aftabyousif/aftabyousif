<?php

prePrint($user);
?>
	<!-- Static Table Start -->
	<div class="static-table-area">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="sparkline8-list">
						<div class="sparkline8-hd">
							<div class="main-sparkline8-hd">
								<h1 class="text-center">APPLICATION FORM REVIEW</h1>
							</div>
						</div>
						<div class="sparkline8-graph">
							<div class="static-table-list table-responsive">
								<table class="table table-bordered">
                                    <tr>
                                        <th>Profile Image</th>
                                        <td><img style="height: 200px;width: 150px;" class="img-rounded" src="<?=base_url().PROFILE_IMAGE_CHECK_PATH.$user['PROFILE_IMAGE']?>" alt="Profile Image"></td>
                                    </tr>
                                    <tr>
										<th>Student Name
											<span class="text-danger" style="font-size: 9pt">As per in your matriculation certificate</span>
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
									<tr>
										<th>CNIC No</th>
										<td><?=$user['CNIC_NO']?></td>
									</tr>

									<tr>
										<th>Mobile No</th>
										<td><?=$user['MOBILE_CODE']?>-<?=$user['MOBILE_NO']?></td>
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
                                        <th>District</th>
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

								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
