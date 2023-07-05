<div id = "min-height" class="container-fluid" style="padding:30px">


    <div class="container">

        <div class="row" style="padding-bottom: 20px;">
            <div class="col-md-6">

                <div class="calender-inner">
                    <h3>Password Rule</h3>
                    <div class="row">
                        <!--<div class='text-danger'>At least one digit ...!</div>-->
                        <!--<div class='text-danger'>At least one lowercase character ...!</div>-->
                        <!--<div class='text-danger'>At least one uppercase character ...!</div>-->
                        <!--<div class='text-danger'>At least one special character ...!</div>-->
                        <div class='text-danger'>At least 8 characters in length, but no more than 50 ...!</div>
                    </div>

<!--                    <form action="" method="post">-->
                    <?=form_open('changePassword/changePasswordHandler')?>
                        <div id="pwd-container1">
                            <div class="form-group">
                                <label for="password1">Current/Old Password</label>
                                <input type="password" name="current_password"class="form-control example1" id="current_password" placeholder="Current Password" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="password1">New Password</label>
                                <input type="password" name="new_password"class="form-control example1" id="new_password" placeholder="New Password" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="password1">Confirm New Password</label>
                                <input type="password" name="re_type_password"class="form-control example1" id="re_type_password" placeholder="Re-Type Password" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" name="submit" value="Change Password">
                        </div>

                    </form>
                </div>



            </div>



            <div class="col-md-6">
                <div class="calender-inner">
                    <div id='calendar'></div>
                </div>
            </div>

        </div>
    </div>

</div>