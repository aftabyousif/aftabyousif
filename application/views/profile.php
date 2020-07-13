<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 4:22 PM
 */
$readonly ="";
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-payment-inner-st">
                <ul id="myTabedu1" class="tab-review-design">
                    <li class="active"><a href="#basic_information">Basic Information</a></li>
                    <li class=""><a href="#education"> Education Information</a></li>
                    <li class=""><a href="#INFORMATION">Social Information</a></li>
                </ul>
                <div id="myTabContent" class="tab-content custom-product-edit">
                    <div class="product-tab-list tab-pane fade active in" id="basic_information">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="review-content-section">
                                    <div id="dropzone1" class="pro-ad">
                                        <form action="/upload" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="demo1-upload" novalidate="novalidate">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <input name="firstname" type="text" class="form-control" placeholder="Full Name">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="address" type="text" class="form-control" placeholder="Address">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="mobileno" type="number" class="form-control" placeholder="Mobile no.">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="finish" id="finish" type="text" class="form-control hasDatepicker" placeholder="Date of Birth">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="postcode" id="postcode" type="text" class="form-control" placeholder="Postcode">
                                                    </div>
                                                    <div class="form-group alert-up-pd">
                                                        <div class="dz-message needsclick download-custom">
                                                            <i class="fa fa-download edudropnone" aria-hidden="true"></i>
                                                            <h2 class="edudropnone">Drop image here or click to upload.</h2>
                                                            <p class="edudropnone"><span class="note needsclick">(This is just a demo dropzone. Selected image is <strong>not</strong> actually uploaded.)</span>
                                                            </p>
                                                            <input name="imageico" class="hd-pro-img" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <input name="department" type="text" class="form-control" placeholder="Department">
                                                    </div>
                                                    <div class="form-group res-mg-t-15">
                                                        <textarea name="description" placeholder="Description"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <select name="gender" class="form-control">
                                                            <option value="none" selected="" disabled="">Select Gender</option>
                                                            <option value="0">Male</option>
                                                            <option value="1">Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <select name="country" class="form-control">
                                                            <option value="none" selected="" disabled="">Select country</option>
                                                            <option value="0">India</option>
                                                            <option value="1">Pakistan</option>
                                                            <option value="2">Amerika</option>
                                                            <option value="3">China</option>
                                                            <option value="4">Dubai</option>
                                                            <option value="5">Nepal</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <select name="state" class="form-control">
                                                            <option value="none" selected="" disabled="">Select state</option>
                                                            <option value="0">Gujarat</option>
                                                            <option value="1">Maharastra</option>
                                                            <option value="2">Rajastan</option>
                                                            <option value="3">Maharastra</option>
                                                            <option value="4">Rajastan</option>
                                                            <option value="5">Gujarat</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <select name="city" class="form-control">
                                                            <option value="none" selected="" disabled="">Select city</option>
                                                            <option value="0">Surat</option>
                                                            <option value="1">Baroda</option>
                                                            <option value="2">Navsari</option>
                                                            <option value="3">Baroda</option>
                                                            <option value="4">Surat</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="website" type="text" class="form-control" placeholder="Website URL">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="payment-adress">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-tab-list tab-pane fade" id="education">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="review-content-section">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <form id="acount-infor" action="#" class="acount-infor" novalidate="novalidate">
                                                <div class="devit-card-custom">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="email" placeholder="Email">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="phoneno" type="number" class="form-control" placeholder="Phone">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="password" type="password" class="form-control" placeholder="Password">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="confarmpassword" type="password" class="form-control" placeholder="Confirm Password">
                                                    </div>
                                                    <a href="#" class="btn btn-primary waves-effect waves-light">Submit</a>
                                                </div>
                                            </form>
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
