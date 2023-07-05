<?php
//print_r($userTickets);
//exit();
?>
<main id="main">

	<!-- ======= Contact Section ======= -->
	<!--    <div class="map-section">-->
	<!--        <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621" frameborder="0" allowfullscreen></iframe>-->
	<!--    </div>-->

	<br/>
	<section id="contact" class="contact">
		
		<p align="center"><b><font size=15>ALL TICKETS</font></b></p>
					<div class="row justify-content-center" data-aos="fade-up">

				<div class="col-lg-10">

					<div class="container">
  
  <table class="table table-hover">
    <thead>
      <tr>
        <th>TICKET ID</th>
        <th>NAME</th>
        <TH>CNIC NO</TH>
        <th>SUBJECT</th>
        <th>MESSAGE</th>
        <th>Query DATE</th>
        <th>RESPONSE</th>
      </tr>
    </thead>
    <tbody>
      <?php
		foreach($userTickets as $tickets){
		  //  prePrint($tickets);
			$datetime = $tickets['DATETIME'];
			$dt = strtotime($datetime);
			$dt=date("d-m-Y", $dt); //echo the year of the datestamp just created
			
			echo "<tr>";
			echo "<td>";
				echo $tickets['TICKET_ID'];
			echo "</td>";
				echo "<td>";
				echo $tickets['NAME'];
			echo "</td>";
			echo "<td>";
			echo $tickets['REF_NO'];
			echo "</td>";

			echo "<td>";
				echo "<p align=justify>".$tickets['SUBJECT']."<p>";
			echo "</td>";
			
			echo "<td>";
				echo "<p align=justify>".$tickets['MESSAGE']."</p>";
			echo "</td>";
			
			echo "<td>";
				echo "<p align=justify>".$dt."</p>";
			echo "</td>";
			
			echo "<td>";			
				echo "<a href='".base_url()."ViewAllQueries/ticketResponse/".$tickets['TICKET_ID']."' target='new'>CLICK HERE to give response</a>";
				//echo "<p align=justify>".$tickets['RESPONSE']."<p>";
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