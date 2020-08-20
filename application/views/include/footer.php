<!-- bootstrap JS
       ============================================ -->
<script src="<?=base_url()?>dash_assets/js/bootstrap.min.js"></script>
<!-- wow JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/wow.min.js"></script>
<!-- price-slider JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/jquery-price-slider.js"></script>
<!-- meanmenu JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/jquery.meanmenu.js"></script>
<!-- owl.carousel JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/owl.carousel.min.js"></script>
<!-- sticky JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/jquery.sticky.js"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/jquery.scrollUp.min.js"></script>
<!-- counterup JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/counterup/jquery.counterup.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/counterup/waypoints.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/counterup/counterup-active.js"></script>
<!-- mCustomScrollbar JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/scrollbar/mCustomScrollbar-active.js"></script>
<!-- metisMenu JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/metisMenu/metisMenu.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/metisMenu/metisMenu-active.js"></script>

<!-- summernote JS
============================================ -->
<script src="<?=base_url()?>dash_assets/js/summernote/summernote.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/summernote/summernote-active.js"></script>

<!-- morrisjs JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/morrisjs/raphael-min.js"></script>
<!--    <script src="assets/js/morrisjs/morris.js"></script>-->
<!--    <script src="assets/js/morrisjs/morris-active.js"></script>-->
<!-- morrisjs JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/sparkline/jquery.sparkline.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/sparkline/jquery.charts-sparkline.js"></script>
<script src="<?=base_url()?>dash_assets/js/sparkline/sparkline-active.js"></script>
<!-- calendar JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/calendar/moment.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/calendar/fullcalendar.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/calendar/fullcalendar-active.js"></script>
<!-- data table JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/duallistbox/jquery.bootstrap-duallistbox.js"></script>
<script src="<?=base_url()?>dash_assets/js/duallistbox/duallistbox.active.js"></script>

<script src="<?=base_url()?>dash_assets/js/data-table/bootstrap-table.js"></script>
<script src="<?=base_url()?>dash_assets/js/data-table/tableExport.js"></script>
<script src="<?=base_url()?>dash_assets/js/data-table/data-table-active.js"></script>
<script src="<?=base_url()?>dash_assets/js/data-table/bootstrap-table-editable.js"></script>
<script src="<?=base_url()?>dash_assets/js/data-table/bootstrap-editable.js"></script>
<script src="<?=base_url()?>dash_assets/js/data-table/bootstrap-table-resizable.js"></script>
<script src="<?=base_url()?>dash_assets/js/data-table/colResizable-1.5.source.js"></script>
<script src="<?=base_url()?>dash_assets/js/data-table/bootstrap-table-export.js"></script>
<!--  editable JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/editable/jquery.mockjax.js"></script>
<script src="<?=base_url()?>dash_assets/js/editable/mock-active.js"></script>
<!--    <script src="<?=base_url()?>dash_assets/js/editable/select2.js"></script>-->
<script src="<?=base_url()?>dash_assets/js/editable/moment.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/editable/bootstrap-datetimepicker.js"></script>
<script src="<?=base_url()?>dash_assets/js/editable/bootstrap-editable.js"></script>
<script src="<?=base_url()?>dash_assets/js/editable/xediable-active.js"></script>
<!-- Chart JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/chart/jquery.peity.min.js"></script>
<script src="<?=base_url()?>dash_assets/js/peity/peity-active.js"></script>
<!-- tab JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/tab.js"></script>
<!-- datapicker JS
============================================ -->
<script src="<?=base_url()?>dash_assets/js/datapicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>dash_assets/js/datapicker/datepicker-active.js"></script>
<!-- plugins JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/plugins.js"></script>
<!-- main JS
    ============================================ -->
<script src="<?=base_url()?>dash_assets/js/main.js"></script>

<script src="<?=base_url()?>assets/js/input-validation.js"></script>
    <script>
document.getElementById('min-height').style="padding:30px;min-height:"+(window.innerHeight-120)+"px;";
	</script>
    
    <!-- tawk chat JS
		============================================ -->
<!--    <script src="assets/js/tawk-chat.js"></script>-->
</body>

</html>

<script>
 //addOnclick();
     function redirect(path){
         //  console.log('adf');
           window.location.href=path;
       }
</script>
    <script>

        function escapeHtml(text) {
            return text
                .replace(/&/g, "-AND-amp;")
                .replace(/</g, "-AND-lt;")
                .replace(/>/g, "-AND-gt;")
                .replace(/"/g, "-AND-quot;")
                .replace(/'/g, "-AND-#039;");
        }
        function changeImage(target,imageFile,veiwImage,kb=50){


            var imageFile1 = imageFile+"1";
            var v = document.getElementById(imageFile);
            document.getElementById(imageFile1).value = v.value;
            if(checksize(target,kb)){

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


        function checksize(target,kb=50){

            if(target.files[0].type.indexOf("image") == -1) {
                alertMsg('ALERT',"File not supported");
                return false;
            }
            var  fileSize = 1024*kb;
            if(target.files[0].size >fileSize ) {
                if(kb>=1024){
                    var msg = (kb/1024)+"MB";
                }else{
                    var msg = kb+"KB";
                }


                alertMsg('ALERT',"Image size too big (max "+msg+")");
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
        //loadDateTime();
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
    if($url = $this->session->flashdata('OPEN_TAB')){
       ?>
        <script>
            //console.log('ASD');
            window.open('<?=$url?>', '_blank');
        </script>
        <?php
    }
    if(isset($_SESSION['ALERT_MSG'])){
        $msg = isValidData($_SESSION['ALERT_MSG']['MSG']);
        $title = $_SESSION['ALERT_MSG']['TYPE'];
        // echo 'asd';
        echo "<script>
        //console.log('yes');
        alertMsg('$title','$msg');
        </script>";
        unset($_SESSION['ALERT_MSG']);
    }
    ?>
