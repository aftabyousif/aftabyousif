<style>
.blink_me {
  animation: blinker 3s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
.notice-msg{
    font-size: 13pt;margin-top:15px;font-family: Times, serif; 
}
</style>

<div style="height:100px"></div>

<marquee><h3 style='margin-left: 50px;font-weight: bold;' class="text-success">
<!--*  Last Date for Online Registeration is Tuesday 01-11-2022.-->
<!--New Registration for Admissions 2023 is CLOSED.-->
</marquee>


<!--<center><a href='https://admission.usindh.edu.pk/admission/candidate_merit_list_master' target='new'><h3 style='margin-left: 50px;font-weight: bold;font-size:18pt' class="text-info ">First Provisional Merit/Selection List - Master Degree Programs 2022 is announced</h3></a></center>-->
<!--<center><a href='https://admission.usindh.edu.pk/admission/dept_wise_selection_list' target='new'><h3 style='margin-left: 50px;font-weight: bold;font-size:18pt' class="text-warning ">First Provisional Merit/Selection List - Bachelor Degree Programs 2022 is announced</h3></a></center>-->
<!--    <div class="container">-->
        <!--<div class="row"> -->
        <!--    <div class="col-sm-4">-->
        <!--                                                        <div class="alert alert-success" role="alert">-->
        <!--                                                             <strong>-->
        <!--                                                               <center>  <a href='https://sutc.usindh.edu.pk/sutc/result?ID=103' target='_blank'> CLICK HERE TO CHECK PRE-ENTRY TEST MARKS FOR THE ADMISSIONS TO BACHELOR DEGREE MORNING PROGRAMMES 2023, [Day-1] HELD ON 30-10-2022 UNIVERSITY OF SINDH, JAMSHORO.</a></center>-->
        <!--                                                                </strong>-->
        <!--                                                                </div>-->
        <!--                                                                </div>-->
            <!--<div class="col-sm-4">-->
            <!--                                                    <div class="alert alert-success" role="alert">-->
            <!--                                                         <strong>-->
            <!--                                                           <center>  <a href='https://sutc.usindh.edu.pk/sutc/result?ID=57' target='_blank'> CLICK HERE TO CHECK PRE-ENTRY TEST MARKS FOR THE ADMISSIONS TO BACHELOR DEGREE MORNING PROGRAMMES 2022, [Day-2] HELD ON 31-10-2021 UNIVERSITY OF SINDH, JAMSHORO.</a></center>-->
            <!--                                                            </strong>-->
            <!--                                                            </div>-->
            <!--                                                            </div>-->
            <!--<div class="col-sm-4">-->
            <!--                                                    <div class="alert alert-success" role="alert">-->
            <!--                                                         <strong>-->
            <!--                                                           <center>  <a href='https://sutc.usindh.edu.pk/sutc/result?ID=56' target='_blank'> CLICK HERE TO CHECK PRE-ENTRY TEST MARKS FOR THE ADMISSIONS TO BACHELOR DEGREE MORNING PROGRAMMES 2022, [Day-1] HELD ON 30-10-2021 UNIVERSITY OF SINDH, JAMSHORO.</a></center>-->
            <!--                                                            </strong>-->
            <!--                                                            </div>-->
            <!--                                                            </div>-->
        </div>
    </div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4" style="padding-left: 40px;">
            <div class="card" style="margin-top: 20px;margin-bottom: 50px;min-height: 400px;">
           <div class="card-header card-header-primary text-center">
                    <h3 class="card-title ">Login</h3>
                </div>
                <div class="card-body">
                    <div class="login">
<!--                        <form  id="registration" action="--><?//=base_url()?><!--login/loginHandler" class="row" method="post" >-->
                        <?=form_open('login/loginHandler')?>
                            <div class="col-12">
                                <label for="" style="font-size:17px">CNIC No.<span class="text-danger"></span></label>
                                <input style="width:1.3em;height:1.3em;" type="radio" class=" mb-3" id="is_cnic" name="check_cnic" value="cnic" checked>
                                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;
                                <label for="" style="font-size:17px">Passport No.<span class="text-danger"></span></label>
                                <input style="width:1.3em;height:1.3em;" type="radio" class=" mb-3" id="is_passport" name="check_cnic" value="passport">
                            </div>
                            <div id="cnic_view" style="width:100%">

                                <div class="col-12">
                                    <label for="" style="font-size:17px">CNIC No.<span class="text-danger">* (without dashes)</span></label>
                                    <input  type="text" class="form-control mb-3" id="cnic" name="cnic" placeholder="CNIC or Form-B(xxxxxxxxxxxxx) ">
                                </div>

                            </div>
                            <div id="passport_view" style="width:100%">



                                <div class="col-12">
                                    <label for="" style="font-size:17px">Passport No<span class="text-danger">* </span></label>
                                    <input  type="text" class="form-control mb-3" id="passport" name="passport" placeholder="Passport No">
                                </div>

                            </div>
                            <div class="col-12">
                                <label for="" style="font-size:17px">Password<span class="text-danger">* </span></label>
                                <input  type="password" class="form-control mb-3" id="password" name="password" placeholder="Password">
                            </div>

                            <div class="col-12">
                                <button type="submit" id="register" name='login' class="btn btn-primary btn-md"><span class='fa fa-unlock'></span>&nbsp;&nbsp;login</button>
                            
                            <a  class="text-right text-success" style="font-size:13pt; font-weight:bold"  href="<?=base_url()?>forget">Forgot Password?</a>
                            </div>
                        <hr/>
                            <div class="col-12 top-margin" style="font-size:17px;  " >
                                <b>New Student? click below to Register </b><br /><br />
                                <a  class="text-right" style="font-size:11pt; text-decoration: none; margin: 0px; padding: 14px; background-color:green; color: white;"  href="register"> New Registration</a>
          <!--                  <a id="" href="register"  class="btn btn-success text-center">-->
          <!--  <i class="fa fa-signup"></i> New Registration-->
          <!--</a>-->
          <p><br /> </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card" style="margin-top: 20px;margin-bottom: 50px;min-height: 420px;">
                <div class="card-header card-header-primary text-center">
                    <h3 class="card-title">DIRECTORATE OF ADMISSIONS</h3>
                    <h4 class="card-title">Important Instructions</h4>
                </div>
                <div class="card-body" style='margin-top:0px'>
                    <div class="row">
                        <div class="col-md-12">
                             <h4 class=' notice-msg' >-&nbsp;<a href="<?=base_url().'assets/advertisement_2023.pdf'?>" target='_blank' ><b>Click here to download  advertisement 2023</b></a>.</h4>
                     
                        <h4 class=' notice-msg' >-&nbsp;First create your account on University of Sindh Admission Portal by clicking on <a href=<?=base_url().'register'?>><b>New Registeration</b></a>.</h4>
                        <h4 class=' notice-msg' >-&nbsp;It is mandatory to use your own CNIC or B-Form Number for new registration. </h2>
                            <h4 class=' notice-msg' >-&nbsp;If you are currently enrolled or Ex-Student of University of Sindh and want to apply for the Admissions in 2023, you can login with your previous LMS/ Eportal account password. You don't need to select New Registration.</h4>
                            
                            <h4 class=' notice-msg' >-&nbsp;Please use your own Mobile Number and Email Address in registration process because University of Sindh may correspond/contact with you during admission process on your given mobile number or email address.</h4>
                    
                            
                            <h4 class=' notice-msg' >-&nbsp;Please Login with your CNIC Number and password, and complete your Online Admission Form by filling all required information and uploading all required documents.</h4>
                            
                            <!-- <p class='' style="font-size:15px; font-family:'Times New Roman', Times, serif">-&nbsp; <a href='#'>Click here to watch video tutorial how to fill admission form</a>, if you want to read guidlines in (English/ Sindh/ Urdu) <a href='#'>please click here.</a> </h2> -->
                            
                            <h4 class=' notice-msg' >-&nbsp;If you have any query please email <b>Directorate of Admissions Help Desk</b> at <a href="mailto: admission@usindh.edu.pk" style="text-decoration: none; margin: 0px; padding: 05px; background-color:blue; color: white;"> <b>admission@usindh.edu.pk</b></a>, you will get reply within 24 to 48 hrs (working days) 
                             or Contact on given numbers : 0229213166 - 0229213199 (during office Hours 9:00am to 5:00pm) </h4>
                            
                            <h4 class=' notice-msg' >-&nbsp;It is recommended to use Google Chrome / Mozilla FireFox / Microsoft Internet Explorer Browser for form filling process on your Desktop / Laptop Pc. Please avoid form filling process through your Smart Phone.
                            </h4>
                            <!--<h4 style="color:red;text-align: justify;padding:20px;font-size: 14pt;">-->


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<br/>
<br/>
  <!--<div class="main main-raised">-->
  <!--  <div class="container">-->
  <!--    <div class="section text-center">-->
  <!--      <img src="<?=base_url()."assets/img/Advertisement 2022.jpg"?>" alt="adv"/>-->
  <!--      <br/>-->
  <!--      <img src="<?=base_url()."assets/img/kashif.jpg"?>" alt="adv"/>-->
       
  <!--    </div>-->
       
      
  <!--  </div>-->
  <!--</div>-->
  

</div>
<script>

$(document).ready(function(){
//alert_msg('<a href="https://admission.usindh.edu.pk/admission/selection_list"><img width="950px" src="assets/img/list.jpeg"></a>','Provisinol List');

//alert_msg('<center><iframe width="560" height="315" src="https://www.youtube.com/embed/r-9eV2F5QOs" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></center>','<h3 class="text-success" style="text-align:centre">How to apply in Evening Bachelor/Master Degree Programs?</h3>');
    
});
</script>
