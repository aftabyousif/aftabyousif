<style>
.news{

    background-image: linear-gradient(to right, #d6d7fb  , #fefeff);

}
#news_text{
        color: navy;font-size: large;
        text-shadow: 2px 2px 8px papayawhip;
        text-indent: 50px;
    }
</style>
    <div style="height:100px"></div>
<marquee><h3 style="color: #FF6633"><b>Last date for submission of Online Admission Form is Friday 28-10-2022</b></h3></marquee>
    <div class="container-fluid">
        <div class="row">
    <div class="card" style="margin-top: 20px;margin-bottom: 50px;min-height: 400px;">
        <div class="card-header card-header-primary text-center">
            <h3 class="card-title ">Important News / Latest Announcements</h3>
        </div>
        <div class="card-body" style='margin-top:0px'>
            <div class="row">

                <div class="col-lg-8">
                    <div class="newsletter border border-success"
                         style="background-image: linear-gradient(to right, #9193e4  , #fefeff);border-radius: 15px;padding: 10px">
                        <h3 style="text-decoration: #00353b;text-align: center;font-family:'Agency FB'"><b>NEWS
                                LETTER</b></h3>
                        <?php
                        $i=0;
                        foreach ($newsarray as $key=>$value){
                        ?>
                        <!-- Card -->
                        <div class="card promoting-card" style="background-color:white">

                            <!-- Card content -->
                            <div class="card-body d-flex flex-row">

                                <!-- Avatar -->
                                <img src="<?php echo base_url()?>assets/img/University_of_Sindh_logo.png" class="rounded-circle mr-3"
                                     height="50px" width="50px" alt="avatar">

                                <!-- Content -->
                                <div>

                                    <!-- Title -->
                                    <h4 class="card-title font-weight-bold mb-2"><?php echo $value['TITLE']; ?></h4>
                                    <!-- Subtitle -->
                                    <p class="card-text"><i class="far fa-clock pr-2" style="text-align: right"></i><?php echo $value['DATE']?></p>

                                </div>

                            </div>

                            <!-- Card image -->
                            <div class="view overlay">
                                <!img class="card-img-top rounded-0"
                                src="https://mdbootstrap.com/img/Photos/Horizontal/Food/full page/2.jpg" alt="Card image
                                cap">
                                <a href="#!">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>

                        <!-- Card content -->
                        <div class="card-body">

                            <div class="collapse-content">

                                <!-- Button -->
                                <a class="btn btn-success btn-sm"  href="read_news/<?=$key?>" TARGET="_blank" aria-expanded="false" aria-controls="collapseContent" style=" margin-left: 60px">Read More</a>
                            </div>
                        </div>
                    </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="newsletter border border-success"
                         style="background-image: linear-gradient(to right, #d6d7fb  , #fefeff);border-radius: 15px;padding: 10px">
                        <h3 style="text-decoration: #00353b;text-align: center;font-family:'Agency FB'"><b>DOWNLOADS</b></h3>
                        <div class="card promoting-card" style="background-color:white">
                        <?php
                        $i=0;
                        if(empty($downloadarray)){
                            echo"Downloads not found currently ";
                            exit();
                        }
                        foreach ($downloadarray as $key=>$value){
                            ?>

                                <div class="card-body d-flex flex-row">

                                    <!-- Avatar -->
                                    <img src="<?php echo base_url()?>assets/img/download_logo.png" class="rounded-circle mr-3"
                                         height="50px" width="50px" alt="avatar">

                                    <!-- Content -->
                                    <div>
                                        <!-- Title -->
                                        <h4 class="card-title font-weight-bold mb-2" style="font-size: 14px"><a href="<?php echo $value['PATH']?>" TARGET="_blank"> <?php echo $value['TITLE']; ?></a></h4>
                                        <!-- Subtitle -->
                                    </div>
                                </div>
                                <hr>
                            <?php
                            $i++;
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>