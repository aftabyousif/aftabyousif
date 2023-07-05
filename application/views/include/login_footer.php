
  
  <!--   Core JS Files   -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script src="<?=base_url()?>assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="<?=base_url()?>assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <script src="<?=base_url()?>assets/js/plugins/moment.min.js"></script>
  <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
  <script src="<?=base_url()?>assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="<?=base_url()?>assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <!--  Google Maps Plugin    -->
  <!--<script src="<?=base_url()?>assets/google.js"></script>-->
  <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
  <script src="<?=base_url()?>assets/js/material-kit.js?v=2.0.5" type="text/javascript"></script>
  <script src="<?=base_url()?>assets/js/input-validation.js"></script>
  <footer class="footer" data-background-color="black">
    <div class="container">
      <nav class="float-left">
        <ul>
          <li>
            <a href="<?=base_url()?>developer"">
              Developer Team
            </a>
          </li>
          
        </ul>
      </nav>
      <div class="copyright float-right">
        &copy; 2020, 
        <a href="https://www.usindh.edu.pk/itsc/" target="_blank">IT Services Centre, University of Sindh, Jamshoro</a>
      </div>
    </div>
  </footer>
   
</body>

</html>
  <script>
      $("#passport_view").hide();

      $("#is_passport").change(function(){
          if($("#is_passport").is(':checked')){
              //console.log("passport");
              $("#passport_view").show();
              $("#cnic_view").hide();
          }else{
              $("#cnic_view").show();
              $("#passport_view").hide();
              //console.log("cnic");
          }
      });

      $("#is_cnic").change(function(){
          if($("#is_cnic").is(':checked')){
              $("#passport_view").hide();
              $("#cnic_view").show();
              //console.log("is_cnic");
          }else{

              $("#passport_view").show();
              $("#cnic_view").hide();
              //    console.log("pass");
          }
      });
  </script>
  <script>
      function changeImage(target,imageFile,veiwImage){


          var imageFile1 = imageFile+"1";
          var v = document.getElementById(imageFile);
          document.getElementById(imageFile1).value = v.value;
          if(checksize(target)){

              if(v.files && v.files[0]){


                  var obj = new FileReader();
                  obj.onload = function(data){

                      var image = document.getElementById(veiwImage);
                      image.src = data.target.result;
                      image.style.display = "block";

                  }
                  obj.readAsDataURL(v.files[0]);
              }
          }
          else{
              document.getElementById(imageFile).value =null;

          }

      }


      function checksize(target){

          if(target.files[0].type.indexOf("image") == -1) {
              alert("File not supported");
              return false;
          }
          var  fileSize = 1024*100*1;
          if(target.files[0].size >fileSize ) {
              alert("Image size too big (max 100KB)");

              return false;
          }
          return true;
      }
      function loadDateTime()
      {
          $('.datetimepicker').datetimepicker({
              format: 'DD/MM/YYYY',
              icons: {
                  time: "fa fa-clock-o",
                  date: "fa fa-calendar",
                  up: "fa fa-chevron-up",
                  down: "fa fa-chevron-down",
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
              }
          });
      }
      loadDateTime();
      $(window).on('load', function () {
          $('.preloader').fadeOut(700);
      });
      function alert_msg(msg,title='ALERT') {
          //alert('as');
          $('#alert_title').html(title);
          $('#alert_body').html(msg);
          $('#alert_btn').click();

      }
      $('.preloader').fadeOut(700);

  </script>
  <?php

  if(isset($_SESSION['ALERT_MSG'])){
      $msg = isValidData($_SESSION['ALERT_MSG']['MSG']);
      $title = $_SESSION['ALERT_MSG']['TYPE'];
      echo "<script>
        alert_msg('$msg','$title');
        </script>";
      unset($_SESSION['ALERT_MSG']);
  }
  ?>