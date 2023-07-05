<main id="main">

	<!-- ======= Contact Section ======= -->
	<!--    <div class="map-section">-->
	<!--        <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621" frameborder="0" allowfullscreen></iframe>-->
	<!--    </div>-->

	<br/>
	<section id="contact" class="contact">
		<div class="container">

			<div class="row justify-content-center" data-aos="fade-up">

				<div class="col-lg-10">

					<div class="info-wrap">
						<div class="row">
							<div class="col-lg-4 info">
								<i class="icofont-google-map"></i>
								<h4>Location:</h4>
								<p>First Floor AC2 Old Building<br>University of Sindh, Jamshoro, Sindh, Pakistan</p>
							</div>

							<div class="col-lg-4 info mt-4 mt-lg-0">
								<i class="icofont-envelope"></i>
								<h4>Email:</h4>
								<p>admission@usindh.edu.pk</p>
							</div>

							<div class="col-lg-4 info mt-4 mt-lg-0">
								<i class="icofont-phone"></i>
								<h4>Call:</h4>
								<p>0229213166 - 0229213199 (during office Hours 9:00am to 5:00pm)</p>
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="row mt-5 justify-content-center" data-aos="fade-up">
				<div class="col-lg-10">

					<?php
					if($this->session->flashdata('message'))
					{
						echo '
                    <div class="alert alert-warning">
                        '.$this->session->flashdata("message").'
                    </div>
                    ';
					}
//					form_open_multipart('site/ticketSubmit','POST')
					?>
<!--					role="form" class="php-email-form"-->
					<form action="<?=base_url("Query/ticketSubmit")?>" method="post" enctype="multipart/form-data" role="form" >
						<div class="form-row">
							<div class="col-md-6 form-group">
								<input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
								<div class="validate"></div>
							</div>
							<div class="col-md-6 form-group">
								<input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
								<div class="validate"></div>
							</div>
						</div>
						<div class="form-row">
						<div class="col-md-6 form-group">
							<input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="Contact No" data-rule="minlen:11" data-msg="Please enter at least 11 digit Contact No" />
							<div class="validate"></div>
						</div>
						<div class="col-md-6 form-group">
							<input type="text" class="form-control" name="roll_no" id="roll_no" placeholder="Please provide your CNIC No" />
							<div class="validate"></div>
						</div>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
							<div class="validate"></div>
						</div>
						<div class="form-group">

							<textarea class="form-control" id="summernote1" name="message" rows="5" data-rule="required" data-msg="Please write your query in detail" placeholder="Message"></textarea>
							<div class="validate"></div>
						</div>
						<div class="mb-3">
<!--							<div class="loading">Loading</div>-->
<!--							<div class="error-message"></div>-->
<!--							<div class="sent-message">Your message has been sent. Thank you!</div>-->
							<input type="file" name="userfile" size="20" />
							<p class="text-danger"> Only images are allowed to upload but not greater than 6 MB</p>
						</div>
<!--						<div class="g-recaptcha" data-sitekey="6LePfasZAAAAADanhoSG4O4u28h53hRFwnNTWl_j"></div>-->
						<div class="text-center"><button type="submit" class="btn btn-success">Send Message</button></div>
					</form>
				</div>

			</div>

		</div>
	</section><!-- End Contact Section -->

</main><!-- End #main -->
<!--<script src="https://www.google.com/recaptcha/api.js"></script>-->
<!--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"-->
<!--		async defer>-->

