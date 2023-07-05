<main id="main">
	<!-- ======= Breadcrumbs ======= -->
	<section id="breadcrumbs" class="breadcrumbs">
		<div class="container">

			<div class="d-flex justify-content-between align-items-center">
				<h2>Reply Ticket</h2>
			</div>
		</div>
	</section><!-- End Breadcrumbs -->

	<!-- ======= Contact Section ======= -->
	<!--    <div class="map-section">-->
	<!--        <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621" frameborder="0" allowfullscreen></iframe>-->
	<!--    </div>-->

	<section id="contact" class="contact">
		<div class="container">
			<div class="row mt-5 justify-content-center" data-aos="fade-up">
				<div class="col-lg-10">
					<form action="<?php echo  base_url('ViewAllQueries/ticketReply')?>" method="post" role="form" class="php-email-form">
						<?php
						//	print_r($TICKET_DETAIL);
//						form_hidden('TICKET_ID',);
						?>
						<input type="hidden" name="TICKET_ID" value="<?=$TICKET_DETAIL['TICKET_ID']?>">
						<div class="form-row">
							<div class="col-md-6 form-group">
								<input type="text" name="name" class="form-control" id="name" value="<?=$TICKET_DETAIL['NAME']?>" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" readonly />
								<div class="validate"></div>
							</div>
							<div class="col-md-6 form-group">
								<input type="email" class="form-control" name="email" id="email" value="<?=$TICKET_DETAIL['EMAIL']?>" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" readonly />
								<div class="validate"></div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 form-group">
								<input type="text" class="form-control" name="contact_no" id="contact_no" value="<?=$TICKET_DETAIL['MOBILE_NO']?>" placeholder="Contact No" data-rule="minlen:11" data-msg="Please enter at least 11 digit Contact No" readonly />
								<div class="validate"></div>
							</div>
							<div class="col-md-6 form-group">
								<input type="text" class="form-control" name="roll_no" id="roll_no" value="<?=$TICKET_DETAIL['REF_NO']?>" placeholder="Please provide Roll No if you are student" readonly/>
								<div class="validate"></div>
							</div>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="<?=$TICKET_DETAIL['SUBJECT']?>" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" readonly />
							<div class="validate"></div>
						</div>
						<div class="form-group">
							<textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write your query in detail" placeholder="Message" readonly><?=$TICKET_DETAIL['MESSAGE']?></textarea>
							<div class="validate"></div>
						</div>

						<div class="form-group">
							<textarea class="form-control" name="reply" rows="5" data-rule="required" data-msg="Please write your response" placeholder="Reply"><?=$TICKET_DETAIL['RESPONSE']?></textarea>
							<div class="validate"></div>
						</div>

						<div class="text-center"><button type="submit">Reply & Close Ticket</button></div>
					</form>
				</div>

                    <?php
                    //  $challan_path = "";
                    //  $t_id = $TICKET_DETAIL['TICKET_ID'];
                    //  $base_path = base_url().'../eportal_resource/ticket_uploads/'.$t_id;
                    //  echo $base_path;
                    //     if(!empty(glob("$base_path/.{jpeg,jpg,gif,png}",GLOB_BRACE)))
                    //     {
                    //     	$challan_path = glob("$base_path/$t_id.{jpeg,jpg,gif,png}",GLOB_BRACE)[0];
                    //     }
                        
                    //     echo "$challan_path";
                    //     echo "<img src=$challan_path>";
                      
                    ?>
			</div>

		</div>
	</section><!-- End Contact Section -->

</main><!-- End #main -->
