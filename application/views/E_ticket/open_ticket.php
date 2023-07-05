<?php
//print_r($userTickets);
//exit();
?>
<div class='container' id = "min-height">
<main id="main">

	<section id="contact" class="contact">
		<div class="container" >
				<br>
			<div class="row"  >
				<div class="col-lg-12">
				    <!--<div align="left">-->
				    <!--</div>-->
						<div align="right">
							<button class="btn btn-primary btn-lg" onclick="toggle()">Click here to submit objection</button>
						</div>

				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="row mt-5 justify-content-center" class="hidden" data-aos="fade-up" id="myDiv">

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
					<form action="<?=base_url("ObjectionQuery/ticketSubmit")?>" method="post" enctype="multipart/form-data" role="form" >
						<div class="form-row">
							<div class="col-md-6 form-group">
								<input type="text" name="name" class="form-control" id="name" value=<?=$data['FIRST_NAME'].' '.$data['LAST_NAME']?> readonly placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
								<div class="validate"></div>
							</div>
							<div class="col-md-6 form-group">
								<input type="email" class="form-control" name="email" id="email" value=<?=$data['EMAIL']?> readonly placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
								<div class="validate"></div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 form-group">
								<input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="Contact No" value=<?=$data['MOBILE_NO']?> readonly data-rule="minlen:11" data-msg="Please enter at least 11 digit Contact No" />
								<div class="validate"></div>
							</div>
							<div class="col-md-6 form-group">
								<input type="text" class="form-control" name="roll_no" id="roll_no" value=<?=$data['CNIC_NO']?> readonly placeholder="Please provide your CNIC No" />
								<div class="validate"></div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 form-group">
							<select name="objection_type" id="objection_type" class="form-control">
								<option value='0'>--SELECT OBJECTION TYPE--</option>
								<option value="OBJECTION MERIT LIST">OBJECTION MERIT LIST</option>
								<option value="CORRECTION PROFILE">CORRECTION PROFILE</option>
							</select>
							<div class="validate"></div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-12 form-group">
							<textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write your query in detail" placeholder="Message"></textarea>
							<div class="validate"></div>
						</div>
						</div>
						<div class="mb-3">
							<input type="file" name="userfile" size="20" />
							<p class="text-danger"> Only images are allowed to upload but not greater than 6 MB</p>
						</div>
						<!--						<div class="g-recaptcha" data-sitekey="6LePfasZAAAAADanhoSG4O4u28h53hRFwnNTWl_j"></div>-->
						<div class="text-center"><button type="submit" class="btn btn-success">Send Message</button></div>
					</form>
						<hr>
				</div>

			</div>
				</div>


<!--	</section>-->
<!--		<hr/>-->





	<div class="row justify-content-center no-padding" data-aos="fade-up">
			<p align="center"><b><font size=15>YOUR EARLY TICKETS</font></b></p>
				<div class="col-lg-12">
							  <table class="table table-hover" >
								<thead style="background: #00acee">
								  <tr>
									<th>TICKET ID</th>
									<th>SUBJECT</th>
									<th>MESSAGE</th>
									<th>REPLY</th>
								  </tr>
								</thead>
								<tbody style="background: azure">
								  <?php
									foreach($userTickets as $tickets){
										echo "<tr>";
										echo "<td>";
											echo $tickets['TICKET_ID'];
										echo "</td>";

										echo "<td>";
											echo "<p align=justify>".$tickets['SUBJECT']."<p>";
										echo "</td>";

										echo "<td>";
											echo "<p align=justify>".$tickets['MESSAGE']."</p>";
										echo "</td>";

										echo "<td>";
											echo "<p align=justify>".$tickets['RESPONSE']."<p>";
										echo "</td>";

										echo "</tr>";
									}
								  ?>
								</tbody>
							  </table>
				</div>
			</div>
		</div>

	</section><!-- End Contact Section -->

</main><!-- End #main -->

</div>

<!--<script src="https://www.google.com/recaptcha/api.js"></script>-->
<!--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"-->
<!--		async defer>-->

<script>
$( document ).ready(function() {
    var x = document.getElementById("myDiv");
	x.style.display = "none";
});
function toggle(){
  var x = document.getElementById("myDiv");
  if (x.style.display === "none") {
    x.style.display = "";
  } else {
    x.style.display = "none";
  }
}
</script>
