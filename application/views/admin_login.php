<div style="height:100px"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4" style="padding-left: 40px;">
            <div class="card" style="margin-top: 20px;margin-bottom: 50px;min-height: 450px;">
                <div class="card-header">
                    <h1 >LOGIN</h1>
                </div>
                <div class="card-body">
                    <div class="login">
<!--                        <form  id="registration" action="--><?//=base_url()?><!--login/loginHandler" class="row" method="post" >-->
                        <?=form_open('AdminLogin/adminLoginHandler')?>
<!--                            <div class="col-12">-->
<!--                                <label for="" style="font-size:17px">CNIC<span class="text-danger"></span></label>-->
<!--                                <input style="width:1.3em;height:1.3em;" type="radio" class=" mb-3" id="is_cnic" name="check_cnic" value="cnic" checked>-->
<!--                                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;-->
<!--                                <label for="" style="font-size:17px">Passport <span class="text-danger"></span></label>-->
<!--                                <input style="width:1.3em;height:1.3em;" type="radio" class=" mb-3" id="is_passport" name="check_cnic" value="passport">-->
<!--                            </div>-->
                            <div id="cnic_view" style="width:100%">



                                <div class="col-12">
                                    <label for="" style="font-size:17px">CNIC<span class="text-danger">* (without dashes)</span></label>
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
                                <button type="submit" id="register" name='login' class="btn btn-primary btn-lg">login</button>
                            </div>

                            <div class="col-12 top-margin" >

                                <a  class="btn btn-primary " href="forget">Forgot Password</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card" style="margin-top: 20px;margin-bottom: 50px;min-height: 400px;">
                <div class="card-header card-header-primary text-center">
                    <h3 class="card-title ">Important Notes / Instructions</h3>
                </div>
                <div class="card-body" style='margin-top:0px'>
                    <div class="row">
                        <div class="col-md-12">
                            <!--<h4 style="font-size: 14pt;margin-top:15px;font-family:'Times New Roman', Times, serif ">-->

                            <!--<h4 style="color:red;text-align: justify;padding:20px;font-size: 14pt;">-->

                            <!-- Carousel Card -->
                            <div class="card card-raised card-carousel">
                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="4000">
                                    <ol class="carousel-indicators">
                                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                    </ol>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img class="d-block w-100" src="<?=base_url()?>assets/img/LMS.png" alt="First slide">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h4 style='color:black'>
                                                    <i class="material-icons">app</i> University of Sindh - Learning Management System (LMS)
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="<?=base_url()?>assets/img/IMG_3191.JPG" alt="Second slide">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h4>
                                                    <i class="material-icons">location_on</i> Statue of wisdom @ Zero point!
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src="<?=base_url()?>assets/img/IMG_3191.JPG" alt="Third slide">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h4>
                                                    <i class="material-icons">location_on</i> Statue of wisdom @ Zero point!
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                        <i class="material-icons">keyboard_arrow_left</i>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                        <i class="material-icons">keyboard_arrow_right</i>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                            <!-- End Carousel Card -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
